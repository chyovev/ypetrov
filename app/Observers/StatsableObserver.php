<?php

namespace App\Observers;

use App\Models\Interfaces\Statsable;

class StatsableObserver
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Once a statsable object gets deleted, its stats record
     * from the polymorphic relationship should be gone, too.
     */
    public function deleted(Statsable $statsable): void {
        $statsable->stats()->delete();
    }

}
