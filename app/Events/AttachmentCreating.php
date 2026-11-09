<?php

namespace App\Events;

use App\Models\Attachment;

class AttachmentCreating
{

    ///////////////////////////////////////////////////////////////////////////
    public function __construct(public Attachment $attachment) {
        //
    }

}
