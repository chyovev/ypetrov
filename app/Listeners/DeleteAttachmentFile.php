<?php

namespace App\Listeners;

use App\Events\AttachmentDeleted;

class DeleteAttachmentFile
{

    ///////////////////////////////////////////////////////////////////////////
    public function handle(AttachmentDeleted $event): void {
        $attachment = $event->attachment;

        $attachment->getFileHelper()->delete();
    }

}