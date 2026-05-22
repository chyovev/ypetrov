<?php

namespace App\Exceptions;

use Illuminate\Http\Response;

class LikeException extends ApplicationException
{

    ///////////////////////////////////////////////////////////////////////////
    public function __construct(string $message) {
        parent::__construct($message, null, Response::HTTP_CONFLICT);
    }

}