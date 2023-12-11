<?php

namespace App\Repositories;

use App\Models\StaticPage;

class StaticPageRepository
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get the biography static page.
     * 
     * @return StaticPage
     */
    public static function getBiography(): StaticPage {
        return self::getPageById(StaticPage::BIOGRAPHY_ID);
    }

    ///////////////////////////////////////////////////////////////////////////
    private static function getPageById(int $id): StaticPage {
        return StaticPage::find($id);
    }

}