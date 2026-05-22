<?php

namespace App\Exceptions;

use Illuminate\Http\Response;
use RuntimeException;
use Throwable;

class ApplicationException extends RuntimeException
{

    protected array $errors = [];

    ///////////////////////////////////////////////////////////////////////////
    public function __construct(string $message, ?Throwable $previous = null, int $code = Response::HTTP_INTERNAL_SERVER_ERROR, array $errors = []) {
        $this->errors = $errors;

        parent::__construct($message, $code, $previous);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * If there's no errors list, transform the exception's message into one.
     */
    public function getErrors(): array {
        return $this->errors ?: [
            'generic' => [ $this->message ],
        ];
    }

}