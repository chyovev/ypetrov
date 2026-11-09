<?php

namespace App\Models\Interfaces;

use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * All models which have a polymorphic relationship to
 * the Attachment model should implement this interface
 * and use the HasAttachments trait.
 * 
 * @see \App\Models\Traits\HasAttachments 
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


}