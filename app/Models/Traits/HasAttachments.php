<?php

namespace App\Models\Traits;

use LogicException;
use App\Models\Attachment;
use App\Models\Interfaces\Attachable;
use App\Observers\AttachableObserver;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * The HasAttachments trait is supposed to be used on models
 * which have a polymorphic relationship to the Attachment model.
 * To be used together with the Attachable interface.
 * 
 * @see \App\Models\Interfaces\Attachable
 */

trait HasAttachments
{

    use CustomHasEvents;

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Each attachable object can have multiple comments
     * and all comments are stored in a polymorphic table.
     * 
     * @return MorphMany
     */
    public function attachments(): MorphMany {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Since there's no way to register an observer on all models
     * implementing a certain interface in the event service provider,
     * a work-around is to register it upon trait initialization.
     * 
     * NB! Traits are initialized automatically in the models'
     *     constructors.
     * 
     * NB! Both validation and registration methods are declared
     *     in the CustomHasEvents trait.
     * 
     * @see \Illuminate\Database\Eloquent\Model :: initializeTraits()
     * @see \App\Models\Traits\CustomHasEvents
     * 
     * @throws LogicException – class not implementing required interface
     * @return void
     */
    public function initializeHasAttachments(): void {
        $this->validateModelImplementsInterface(Attachable::class);

        $this->registerObserverToModel(AttachableObserver::class);
    }

}