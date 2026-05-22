<?php

namespace App\Exceptions;

use Illuminate\Http\Response;
use Throwable;

class IdentifierException extends ApplicationException
{

    ///////////////////////////////////////////////////////////////////////////
    public function __construct(string $message, ?Throwable $previous = null) {
        parent::__construct($message, $previous, Response::HTTP_NOT_FOUND);
    }

}