<?php

namespace App\Observers;

use App\Models\Attachment;

class AttachmentObserver
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Once an Attachment record gets deleted, its server
     * file (and possibly subfolder) should be removed, too.
     * 
     * @param Attachment $attachment – deleted item
     */
    public function deleted(Attachment $attachment): void {
        $attachment->getFileHelper()->delete();
    }

}
