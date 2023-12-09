<?php

namespace App\Models\Traits;

use File;
use LogicException;
use App\Models\Attachment;
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

    use CustomHasEvents;

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

    ///////////////////////////////////////////////////////////////////////////
    /**
     * When an attachment gets uploaded, a database record
     * should be initially created in order to allocate a
     * unique file name on the server.
     * Only then can the file be moved to the respective
     * subfolder of the attachable object.
     * 
     * @throws FileNotFoundException – missing temp file path
     * @param  UploadedFile $file    – file being uploaded
     * @return Attachment
     */
    public function uploadAttachment(UploadedFile $file): Attachment {
        $data       = $this->prepareAttachmentData($file);
        $attachment = $this->attachments()->create($data);

        // separate the directory from the name
        // and create it if it does not exist
        $targetName = $attachment->server_file_name;
        $targetDir  = $attachment->getAbsolutePath();
        File::ensureDirectoryExists($targetDir);

        $file->move($targetDir, $targetName);

        return $attachment;
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Prepare data in order to create an Attachment record.
     * 
     * NB! The attachable_type and attachable_id fields are
     *     populated automatically by the create() method
     *     called on the attachments() association.
     *     Also, in order for the server file name to be
     *     generated, an Attachment record should already be
     *     on its way to be persisted to the database. Hence,
     *     this part is taken care of by the AttachmentObserver.
     *     are populated automatically by the create() method.
     * 
     * @param  UploadedFile $file
     * @return array<string,mixed>
     */
    private function prepareAttachmentData(UploadedFile $file): array {
        return [
            'original_file_name' => $file->getClientOriginalName(),
            'file_size'          => $file->getSize(),
            'mime_type'          => $file->getClientMimeType(),
            'order'              => $this->determineOrderForNewAttachmentRecord(),
        ];
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Since attachments are sorted by the 'order' column,
     * newly created attachments should have incrementing
     * values. To determine it, see how many attachments
     * are currently associated it with the attachable
     * object and add +1.
     * 
     * @return int
     */
    private function determineOrderForNewAttachmentRecord(): int {
        return $this->attachments()->count() + 1;
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get the first image attachment record, but keep in mind
     * that there might be no such record.
     * 
     * @return Attachment|null
     */
    private function getFirstImage(): ?Attachment {
        return $this->getAttachmentsByType('image')->first();
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