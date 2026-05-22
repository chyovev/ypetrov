<?php

namespace App\Exceptions;

use Illuminate\Http\Response;

class VisitorBannedException extends ApplicationException
{

    ///////////////////////////////////////////////////////////////////////////
    public function __construct() {
        parent::__construct(__('exception.unauthorized'), null, Response::HTTP_FORBIDDEN);
    }
    
}