<?php

namespace App\Models\Interfaces;

/**
 * All models which have some sort of interaction
 * with public visitors (can be commented on, can
 * be liked, etc.) should implement this interface
 * and use the IsInteractive trait.
 * 
 * @see \App\Models\Traits\IsInteractive
 */

interface Interactive
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Each interactive object should have its own unique interaction ID.
     * 
     * @return string
     */
    public function getInteractionId(): string;
    
}