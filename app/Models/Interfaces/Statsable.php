<?php

namespace App\Models\Interfaces;

use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * All models which have a polymorphic relationship to
 * the Stats model should implement this interface
 * and use the HasStats trait.
 * Statsable models have an observer which takes care
 * of polymorphic data clean-up during deletion.
 * 
 * @see \App\Models\Traits\HasStats
 * @see \App\Observers\StatsableObserver
 */

interface Statsable extends Interactive
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * All models implementing the Statsable interface should
     * have a polymorphic relationship to the Stats model declared.
     * 
     * @return MorphOne
     */
    public function stats(): MorphOne;
}