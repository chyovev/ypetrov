<?php

namespace App\Models\Helpers;

use App\Models\Attachment;
use App\Models\Interfaces\Attachable;
use Illuminate\Http\UploadedFile;

class UploadHelper
{

    ///////////////////////////////////////////////////////////////////////////
    public function __construct(private Attachable $attachable) {
        // 
    }

    ///////////////////////////////////////////////////////////////////////////
    public function upload(UploadedFile $file): Attachment {
        $attachment = $this->createRecord($file);

        $attachment->getFileHelper()->save($file);

        return $attachment;
    }

    ///////////////////////////////////////////////////////////////////////////
    private function createRecord(UploadedFile $file): Attachment {
        $attachment = new Attachment([
            'original_file_name' => $file->getClientOriginalName(),
            'file_size'          => $file->getSize(),
            'mime_type'          => $file->getClientMimeType(),
            'order'              => $this->attachable->attachments()->count() + 1,
        ]);

        // action has observer
        return $this->attachable->attachments()->save($attachment);
    }

}
