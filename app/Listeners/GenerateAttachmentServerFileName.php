<?php

namespace App\Listeners;

use App\Events\AttachmentCreating;
use App\Models\Attachment;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GenerateAttachmentServerFileName
{

    ///////////////////////////////////////////////////////////////////////////
    public function handle(AttachmentCreating $event): void {
        $attachment = $event->attachment;

        if (is_null($attachment->server_file_name)) {
            $attachment->server_file_name = $this->generateServerFileName($attachment);
        }
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Multiple attachments can share the same original file name,
     * but the server file name needs to be unique for each file.
     * Potential duplicates will have a counter suffix starting from 1.
     * 
     * Additionally, since files will be publicly accessible,
     * their server name should be “slugified”, i.e. it should be
     * more URL-friendly: white spaces and non-ascii characters
     * are automatically discarded.
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
     */
    private function generateFileName(string $baseName, ?string $extension, int $suffixCounter): string {
        $fileName = $baseName;

        // if suffix is equal to 0, don't add it
        if ($suffixCounter) {
            $fileName .= "_{$suffixCounter}";
        }

        if ($extension) {
            $fileName .= ".{$extension}"; 
        }

        return $fileName;
    }

    ///////////////////////////////////////////////////////////////////////////
    private function isNameAlreadyTaken(Attachment $attachment, string $serverName): bool {
        $filePath = $attachment->getFileHelper()->getFilePath() . DIRECTORY_SEPARATOR . $serverName;

        return File::exists($filePath);
    }
    
}