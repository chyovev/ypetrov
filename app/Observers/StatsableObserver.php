<?php

namespace App\Observers;

use App\Models\Interfaces\Statsable;

/**
 * The StatsableObserver is used on all models which
 * implement the Statsable interface.
 * Its goal is to delete all associated stats records
 * once the main object is deleted since it cannot be
 * done on polymorphic tables using cascade delete in
 * the database.
 * 
 * NB! Keep in mind the observer is registered upon object
 *     initialization, and NOT in the EventServiceProvider.
 * 
 * @see \App\Models\Traits\HasStats :: initializeHasStats()
 */

class StatsableObserver
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Once a statsable object gets deleted, its stats record
     * from the polymorphic relationship should be gone, too.
     * 
     * @param  Statsable $object – object implementing the Statsable interface
     * @return void
     */
    public function deleted(Statsable $object): void {
        // make sure the statsable object is *really* gone:
        // if it's being soft deleted, its $exists property
        // will remain true
        if ( ! $object->exists) {
            $object->stats()->delete();
        }
    }

}
