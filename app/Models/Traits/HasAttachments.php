<?php

namespace App\Models\Traits;

use App\Models\Attachment;
use App\Models\Helpers\UploadHelper;
use App\Models\Interfaces\Attachable;
use App\Observers\AttachableObserver;
use Illuminate\Http\UploadedFile;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\Collection;
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

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Each attachable object can have multiple comments
     * and all comments are stored in a polymorphic table.
     * When fetched, records are ordered by their 'order'
     * column value, and not by their primary key.
     * 
     * @return MorphMany
     */
    public function attachments(): MorphMany {
        return $this->morphMany(Attachment::class, 'attachable')->orderBy('order');
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Since there's no way to register an observer on all models
     * implementing a certain interface in the event service provider,
     * a work-around is to register it upon trait boot which is taken
     * care of by the Eloquent constructor.
     * 
     * @see \Illuminate\Database\Eloquent\Model :: bootTraits()
     */
    public static function bootHasAttachments(): void {
        if (is_a(static::class, Attachable::class, true)) {
            static::observe(AttachableObserver::class);
        }
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * @throws FileNotFoundException – missing temp file path
     */
    public function uploadAttachment(UploadedFile $file): Attachment {
        return (new UploadHelper($this))->upload($file);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get the first image attachment record, but keep in mind
     * that there might be no such record.
     * 
     * @return Attachment|null
     */
    private function getFirstImage(): ?Attachment {
        return $this->getImages()->first();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get all image attachments.
     * 
     * @return Collection<Attachment>
     */
    public function getImages(): Collection {
        return $this->getAttachmentsByType('image');
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Filter all attachments by a specific MIME type.
     * 
     * @param  string $mimeType – regex supported
     * @return Collection<Attachment>
     */
    private function getAttachmentsByType(string $mimeType): Collection {
        return $this->attachments->filter(function (Attachment $attachment) use ($mimeType) {
            return $attachment->hasType($mimeType);
        });
    }

}