<?php

namespace App\Observers;

use App\Models\Interfaces\Attachable;

/**
 * The AttachableObserver is used on all models which
 * implement the Attachable interface.
 * Its goal is to delete all associated attachments once
 * the main object is deleted since it cannot be done
 * on polymorphic tables using cascade delete in the
 * database.
 * 
 * NB! Keep in mind the observer is registered upon object
 *     initialization, and NOT in the EventServiceProvider.
 * 
 * @see \App\Models\Traits\HasAttachments :: initializeHasAttachments()
 */

class AttachableObserver
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Once a attachable object gets deleted, all its attachments
     * from the polymorphic relationship should be gone, too.
     * 
     * @param  Attachable $object – object implementing the Attachable interface
     * @return void
     */
    public function deleted(Attachable $object): void {
        // make sure the attachable object is *really* gone:
        // if it's being soft deleted, its $exists property
        // will remain true
        if ( ! $object->exists) {
            $object->attachments()->delete();
        }
    }

}
