<?php

namespace Exceptions;

class ValidationException extends \Exception {

    // all validation errors get stored in an array
    // which then gets thrown along the exception
    private $errors = [];

    ///////////////////////////////////////////////////////////////////////////
    public function __construct(array $errors, int $code = 0,  Exception $previous = null) {
        $this->errors = $errors;

        // convert errors array to string for parent exception constructor
        parent::__construct(json_encode($errors), $code, $previous);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getErrors(): array {
        return $this->errors;
    }
    
}
