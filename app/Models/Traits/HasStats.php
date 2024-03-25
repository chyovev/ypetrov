<?php

namespace App\Models\Traits;

use LogicException;
use App\Models\Interfaces\Statsable;
use App\Models\Like;
use App\Models\Stats;
use App\Models\Visitor;
use App\Exceptions\LikeException;
use App\Observers\StatsableObserver;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

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
     * Each statsable object has a single stats record which
     * in turn can have multiple like records.
     * An easy way to cut out the middle object and get the likes
     * directly is to use the HasManyThrough relation. Even though
     * it's designed to work with  non-polymorphic relations, it's
     * fairly easy to set it up for such relationships by specifying
     * the foreign-key column and adding a where clause for the main
     * object's class name.
     * 
     * @return HasManyThrough
     */
    public function likes(): HasManyThrough {
        return $this
            ->hasManyThrough(Like::class, Stats::class, 'statsable_id')
            ->where('statsable_type', static::class);
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

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Check whether a statsable object has a like
     * registered for a certain visitor.
     * 
     * @param  Visitor $visitor
     * @return bool
     */
    public function isLikedByVisitor(Visitor $visitor): bool {
        return $this->likes()->where('visitor_id', $visitor->id)->exists();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Register a like by a visitor.
     * 
     * @throws LikeException – object already liked by visitor
     * @param  Visitor $visitor
     * @return Like
     */
    public function like(Visitor $visitor): Like {
        if ($this->isLikedByVisitor($visitor)) {
            throw new LikeException(__('global.already_liked'));
        }
        
        // increase the total likes count
        $stats = $this->stats()->firstOrCreate();
        $stats->increment('total_likes');

        // actually register a visitor-like record
        $like = $stats->likes()->make();
        $like->visitor()->associate($visitor);
        $like->save();

        return $like;
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Revoke an already given like by a visitor.
     * 
     * @throws LikeException – object not liked by visitor
     * @param  Visitor $visitor
     * @return void
     */
    public function revokeLike(Visitor $visitor): void {
        if ( ! $this->isLikedByVisitor($visitor)) {
            throw new LikeException(__('global.not_liked'));
        }

        // decrease the total likes count
        $stats = $this->stats()->firstOrCreate();
        $stats->decrement('total_likes');

        // actually delete the visitor-like record
        $this->likes()->where('visitor_id', $visitor->id)->delete();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Check how many total likes a statsable object has.
     * 
     * NB! If the stats association is loaded as a property,
     *     it would be stored in memory for the main object,
     *     resulting in inaccurate total_likes value in case
     *     data gets altered inbetween.
     * 
     * @return int
     */
    public function getTotalLikes(): int {
        return $this->stats()->first()->total_likes ?? 0;
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Generate a unique API URL which can be used to like
     * or revoke like from the current statsable object.
     * 
     * @return string 
     */
    public function getLikeUrl(): string {
        return route('api.like', ['id' => $this->getInteractionId()]);
    }

}