<?php

namespace App\Events;

use App\Models\Attachment;

class AttachmentDeleted
{

    ///////////////////////////////////////////////////////////////////////////
    public function __construct(public Attachment $attachment) {
        //
    }

}
