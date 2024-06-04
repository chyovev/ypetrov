<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * Some malicious visitors can be banned from
 * accessing the website altogether. In such
 * cases the VisitorBanned middleware throws
 * the VisitorBannedException.
 */

class VisitorBannedException extends RuntimeException
{

}