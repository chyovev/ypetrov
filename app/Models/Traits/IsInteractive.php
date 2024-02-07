<?php

namespace App\Models\Traits;

/**
 * The IsInteractive trait is supposed to be used on models
 * which have some sort of interaction with public visitors.
 * To be used together with the Interactive interface.
 * 
 * @see \App\Models\Interfaces\Interactive
 */


trait IsInteractive
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Each interactive object should have its own unique interaction ID
     * by which the object can be identified when said interaction gets
     * registered. Since the ID will be part of the request (and therefore
     * publicly visible), it's best to mask it so as not to reveal too much
     * information about the system.
     * 
     * @return string
     */
    public function getInteractionId(): string {
        $model = get_class($this);
        $id    = $this->id;

        return encrypt("{$model}|{$id}");
    }

}