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
     * NB! Calling delete() on the relationship method alone
     *     will simply delete all records from the attachments
     *     table for that object, but not the actual files.
     *     Instead, attachments should first be loaded as a
     *     collection (by calling the relationship method as
     *     a property) – from then on, calling the delete()
     *     method on each Attachment instance will fire up the
     *     Attachment observer which takes care of the file
     *     deletion.
     * 
     * @param  Attachable $object – object implementing the Attachable interface
     * @return void
     */
    public function deleted(Attachable $object): void {
        // make sure the attachable object is *really* gone:
        // if it's being soft deleted, its $exists property
        // will remain true
        if ( ! $object->exists) {
            $object->attachments->each->delete();
        }
    }

}
