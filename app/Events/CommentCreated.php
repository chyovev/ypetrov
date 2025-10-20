<?php

namespace App\Events;

use App\Models\Comment;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class CommentCreated
{
    use Dispatchable, SerializesModels;

    ///////////////////////////////////////////////////////////////////////////
    public function __construct(public Comment $comment) {
        //
    }

}
