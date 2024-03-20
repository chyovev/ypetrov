<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * In order for an interaction to be registered,
 * the request identifier should be decoded.
 * If this task fails, the IdentifierException
 * should be thrown.
 * 
 * @see \App\API\Http\Requests\InteractiveRequest
 */

class IdentifierException extends RuntimeException
{

}