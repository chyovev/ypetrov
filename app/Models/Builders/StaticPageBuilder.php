<?php

namespace App\Models\Builders;

use App\Models\StaticPage;
use Illuminate\Database\Eloquent\Builder;

class StaticPageBuilder extends Builder
{

    ///////////////////////////////////////////////////////////////////////////
    public function getBiography(): StaticPage {
        return $this->find(StaticPage::BIOGRAPHY_ID);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function getChrestomathy(): StaticPage {
        return $this->find(StaticPage::CHRESTOMATHY_ID);
    }

}