<?php

namespace App\Models\Interfaces;

use App\Utils\Seo;

interface MetaData
{

    ///////////////////////////////////////////////////////////////////////////
    public function getSeo(): Seo;

}