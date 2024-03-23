<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * When a visitor attempts to like a statsable object
 * which they have already liked previously, the
 * LikeException will be thrown. Same goes for
 * revoking an already revoked given like. 
 */

class LikeException extends RuntimeException
{

}