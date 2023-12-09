<?php

namespace App\Observers;

use App\Models\Interfaces\Attachable;
use Illuminate\Validation\ValidationException;

/**
 * The AttachableObserver is used on all models which
 * implement the Attachable interface.
 * It has several purposes:
 *     - to validate requests for attachments files
 *     - to save said files as object attachments
 *     - to delete all associated attachments once the
 *       main object is deleted since it cannot be done
 *       on polymorphic tables using cascade delete in
 *       the database
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
     * The saving event is the last chance to prevent an attachable
     * object from being created/updated. This is where the request
     * should be scanned for attachments and if they're not in the
     * right format, an exception gets thrown.
     * 
     * @throws ValidationException
     */
    public function saving(Attachable $object): void {
        $this->validateRequestAttachments($object);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * @throws ValidationException
     */
    private function validateRequestAttachments(): void {
        $rules = [
            'attachments'   => ['sometimes', 'array'],
            'attachments.*' => ['file'],
        ];

        request()->validate($rules);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Once an attachable object gets saved (created or updated)
     * the request should be scanned for potential uploaded attachments
     * which should be stored locally.
     * 
     * @param  Attachable $object – object implementing the Attachable interface
     * @return void
     */
    public function saved(Attachable $object): void {
        $this->saveAttachmentsFromRequest($object);
    }
    
    ///////////////////////////////////////////////////////////////////////////
    /**
     * Cycle through all uploaded files from the request and save each
     * file individually as an attachment for the current attachable object.
     * 
     * NB! Keep in mind that at this point the attachments array should
     *     be validated beforehand.
     * 
     * @param Attachable $object – object implementing the Attachable interface
     */
    private function saveAttachmentsFromRequest(Attachable $object): void {
        $files       = request()->allFiles();
        $attachments = $files['attachments'] ?? []; 

        foreach ($attachments as $file) {
            $attachment = $object->uploadAttachment($file);

            // image attachments should have thumbnails
            if ($attachment->isImage()) {
                $attachment->generateThumbnail();
            }
        }
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Once an attachable object gets deleted, all its attachments
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
