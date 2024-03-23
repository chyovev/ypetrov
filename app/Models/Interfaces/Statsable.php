<?php

namespace App\Models\Interfaces;

use App\Models\Like;
use App\Models\Visitor;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

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


    ///////////////////////////////////////////////////////////////////////////
    /**
     * Set up a direct statsable-object-to-likes relationship
     * via the polymorphic stats model.
     * 
     * @return HasManyThrough
     */
    public function likes(): HasManyThrough;


    ///////////////////////////////////////////////////////////////////////////
    /**
     * Check if a statsable object can be liked.
     * (Usually the activeness state of the object is verified).
     * 
     * @return bool
     */
    public function canBeLiked(): bool;


    ///////////////////////////////////////////////////////////////////////////
    /**
     * Check if a statsable object is already liked by a visitor.
     */
    public function isLikedByVisitor(Visitor $visitor): bool;


    ///////////////////////////////////////////////////////////////////////////
    /**
     * Register a like by a visitor.
     * 
     * @param  Visitor $visitor
     * @return Like
     */
    public function like(Visitor $visitor): Like;


    ///////////////////////////////////////////////////////////////////////////
    /**
     * Revoke an already given like by a visitor.
     * 
     * @param  Visitor $visitor
     * @return void
     */
    public function revokeLike(Visitor $visitor): void;

    
}