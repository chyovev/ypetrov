<?php

namespace App\Models\Traits;

use LogicException;
use App\Models\Interfaces\Statsable;
use App\Models\Stats;
use App\Observers\StatsableObserver;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * Most models' records can be accessed through the application
 * by the end user which in effect makes them “public elements”.
 * For statistics purposes, such elements have their impressions
 * and likes kept track of, but they are all funneled through
 * a one-to-one polymorphic relationship to the Stats
 * model which in turn stores said statistical information.
 */

trait HasStats
{

    use CustomHasEvents;

    use IsInteractive;

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Each statsable object can have a single stats record
     * which is stored in a polymorphic table.
     * 
     * @return MorphOne
     */
    public function stats(): MorphOne {
        return $this->morphOne(Stats::class, 'statsable');
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Since there's no way to register an observer on all models
     * implementing a certain interface in the event service provider,
     * a work-around is to register it upon trait initialization.
     * 
     * NB! Traits are initialized automatically in the models'
     *     constructors.
     * 
     * NB! Both validation and registration methods are declared
     *     in the CustomHasEvents trait.
     * 
     * @see \Illuminate\Database\Eloquent\Model :: initializeTraits()
     * @see \App\Models\Traits\CustomHasEvents
     * 
     * @throws LogicException – class not implementing required interface
     * @return void
     */
    public function initializeHasStats(): void {
        $this->validateModelImplementsInterface(Statsable::class);

        $this->registerObserverToModel(StatsableObserver::class);
    }

}