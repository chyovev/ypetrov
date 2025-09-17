<?php

namespace App\Observers;

use App\Models\Helpers\UploadHelper;
use App\Models\Interfaces\Attachable;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;

/**
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
    public function saving(): void {
        $this->validateRequestAttachments();
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
     * NB! Keep in mind that at this point the attachments array should
     *     be validated beforehand.
     */
    private function saveAttachmentsFromRequest(Attachable $object): void {
        $allFiles = request()->allFiles();
        $files    = collect($allFiles['attachments'] ?? []);

        $helper = new UploadHelper($object);

        $files->each(function(UploadedFile $file) use ($helper) {
            $attachment = $helper->upload($file);

            // image attachments should have thumbnails
            if ($attachment->isImage()) {
                $attachment->generateThumbnail();
            }
        });
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
