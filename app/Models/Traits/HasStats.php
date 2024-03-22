<?php

namespace App\Models\Traits;

use LogicException;
use App\Models\Interfaces\Statsable;
use App\Models\Stats;
use App\Models\Visitor;
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

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Adding an impression to a statsable object consists of three actions:
     * 
     *     1. Find (or create) the morph record for the object
     *     2. Increase its total_impressions counter
     *     3. Actually create an impression record for the visitor
     *        doing the request (which is registered as an instance)
     * 
     * @return void
     */
    public function addImpression(): void {
        $stats = $this->stats()->firstOrCreate();
        $stats->increment('total_impressions');

        $visitor    = app(Visitor::class);
        $impression = $stats->impressions()->make();
        $impression->visitor()->associate($visitor);
        $impression->save();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Check how many total impressions a statsable object has.
     * 
     * NB! If the stats association is loaded as a property,
     *     it would be stored in memory for the main object,
     *     resulting in inaccurate total_impressions value
     *     in case data gets altered inbetween.
     * 
     * @return int
     */
    public function getTotalImpressions(): int {
        return $this->stats()->first()->total_impressions ?? 0;
    }

}