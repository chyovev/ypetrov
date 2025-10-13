<?php

namespace App\Models\Interfaces;

use App\Models\Attachment;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * All models which have a polymorphic relationship to
 * the Attachment model should implement this interface
 * and use the HasAttachments trait.
 * Attachable models have an observer which takes care
 * of polymorphic data clean-up during deletion.
 * 
 * @see \App\Models\Traits\HasAttachments 
 * @see \App\Observers\AttachableObserver
 */

interface Attachable
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * All models implementing the Attachable interface should
     * have a polymorphic relationship to the Attachment model declared.
     * 
     * @return MorphMany
     */
    public function attachments(): MorphMany;


    ///////////////////////////////////////////////////////////////////////////
    /**
     * Each attachable object should have an uploadAttachment()
     * method which results in an Attachment record.
     * 
     * @param  UploadedFile $file – attachment being uploaded
     * @return Attachment
     */
    public function uploadAttachment(UploadedFile $file): Attachment;


}