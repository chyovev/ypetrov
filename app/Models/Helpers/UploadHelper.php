<?php

namespace App\Models\Helpers;

use App\Models\Attachment;
use App\Models\Interfaces\Attachable;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

class UploadHelper
{

    ///////////////////////////////////////////////////////////////////////////
    public function upload(Attachable $attachable, UploadedFile $file): Attachment {
        $attachment = $this->createRecord($attachable, $file);

        $attachment->getFileHelper()->save($file->getContent());

        File::delete($file->path());

        return $attachment;
    }

    ///////////////////////////////////////////////////////////////////////////
    private function createRecord(Attachable $attachable, UploadedFile $file): Attachment {
        $attachment = new Attachment([
            'original_file_name' => $file->getClientOriginalName(),
            'file_size'          => $file->getSize(),
            'mime_type'          => $file->getClientMimeType(),
            'order'              => $attachable->attachments()->count() + 1,
        ]);

        return $attachable->attachments()->save($attachment);
    }

}
