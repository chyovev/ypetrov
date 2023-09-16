<?php

namespace App\Observers;

use File;
use App\Models\Attachment;
use Illuminate\Support\Str;

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
        $originalName  = File::filename($attachment->original_file_name);
        $baseName      = Str::slug($originalName);
        $extension     = File::extension($originalName);
        $suffixCounter = 0;

        do {
            $serverName = $this->generateFileName($baseName, $extension, $suffixCounter);
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

}
