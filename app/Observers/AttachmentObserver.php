<?php

namespace App\Observers;

use File;
use App\Models\Attachment;
use App\Models\Interfaces\Attachable;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AttachmentObserver
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Once an Attachment record is about to be created,
     * it should be allocated a unique server file name.
     * 
     * @param Attachment $attachment – new yet unsaved record
     */
    public function creating(Attachment $attachment): void {
        $attachment->server_file_name = $this->generateServerFileName($attachment);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Once an Attachment record gets deleted, its server
     * file (and possibly subfolder) should be removed, too.
     * 
     * @param Attachment $attachment – deleted item
     */
    public function deleted(Attachment $attachment): void {
        $attachment->deleteFile();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Unlike the original file name which allows for duplicates,
     * the server file name is the name under which the file will
     * actually be stored on the server and should therefore be
     * unique for a single attachable object. Potential duplicates
     * will have a counter suffix starting from 1.
     * Additionally, since files will be publicly accessible,
     * their server name should be “slugified”, i.e. it should be
     * more URL-friendly: white spaces and non-ascii characters
     * are automatically discarded.
     * 
     * @param Attachment $attachment – new yet unsaved record
     * @return string
     */
    private function generateServerFileName(Attachment $attachment): string {
        $originalName  = File::name($attachment->original_file_name); // no extension
        $fileName      = Str::slug($originalName); // sanitized, no extension
        $extension     = File::extension($attachment->original_file_name);
        $suffixCounter = 0;

        do {
            $serverName = $this->generateFileName($fileName, $extension, $suffixCounter);
            $suffixCounter++;
        }
        while ($this->isNameAlreadyTaken($attachment, $serverName));

        return $serverName;
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Generate a new file name from a base name and extension.
     * If a suffix was passed, append it before the extension.
     * 
     * @param  string $baseName  – file name without extension
     * @param  string $extension – extension of file (optional)
     * @param  int    $suffix
     * @return string
     */
    private function generateFileName(string $baseName, ?string $extension, int $suffix = 0): string {
        $fileName = $baseName;

        // if suffix is equal to 0, don't add it
        if ($suffix) {
            $fileName .= "_{$suffix}";
        }

        if ($extension) {
            $fileName .= ".{$extension}"; 
        }

        return $fileName;
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Check if the generated server file name is already taken
     * by another existing file in the attachable's subfolder.
     * 
     * @param  Attachment $attachment – new yet unsaved record
     * @param  string $serverName     – generated server name
     * @return bool
     */
    private function isNameAlreadyTaken(Attachment $attachment, string $serverName): bool {
        $filePath = $attachment->getAbsolutePath() . DIRECTORY_SEPARATOR . $serverName;

        return File::exists($filePath);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Before an attachment is deleted, make sure it's not required
     * by the parent model, and if it is – abort the request.
     * 
     * @throws ValidationException
     * @param  Attachment $attachment – attachment record being deleted
     * @return void
     */
    public function deleting(Attachment $attachment): void {
        $this->validateAttachmentIsNotRequired($attachment);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Fetch the parent object of the attachment and check
     * if deleting it would go against its attachments' settings.
     * 
     * @throws ValidationException
     * @param  Attachment $attachment – attachment record being deleted
     * @return void
     */
    private function validateAttachmentIsNotRequired(Attachment $attachment): void {
        $object = $attachment->attachable;

        // under normal circumstances there will always be a parent object,
        // but if it's missing for some reason, simply abort the validation
        if ( ! $object) {
            return;
        }

        $count    = $object->attachments()->count();
        $newCount = $count - 1; // count after deletion
        $min      = $object->minAttachmentsRequired();

        // there could be no min setting, so make sure var is not empty
        if ($min && $newCount < $min) {
            throw ValidationException::withMessages([
                'attachments' => "Attachment cannot be deleted as it is required.",
            ]);
        }
    }

}
