<?php

namespace App\Models\Traits;

use LogicException;
use App\Helpers\FileHelper;
use App\Models\Attachment;
use App\Models\Interfaces\Attachable;
use App\Observers\AttachableObserver;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
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
     * When a file gets uploaded for an attachable object,
     * a FileHelper has to “examine” it first in order to
     * extract data from it and use it on the Attachment
     * record. Once the record gets created, the file gets
     * moved to the respective subfolder of the attachable
     * object.
     * 
     * @throws FileNotFoundException – missing temp file path
     * @param  string $filePath – temp file path
     * @param  string $fileName – original name of file
     * @return Attachment
     */
    public function uploadAttachment(string $filePath, string $fileName = null): Attachment {
        $helper = new FileHelper($filePath, $fileName);

        $data       = $this->prepareAttachmentData($helper);
        $attachment = $this->attachments()->create($data);

        $helper->moveFile($filePath, $attachment->getServerFilePath());

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
     * @param  FileHelper $helper – initiated helper for a file
     * @return array<string,mixed>
     */
    private function prepareAttachmentData(FileHelper $helper): array {
        return [
            'original_file_name' => $helper->getBaseName(),
            'file_size'          => $helper->getFileSize(),
            'mime_type'          => $helper->getMimeType(),
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
     * Check minimum required attachments for an attachable model
     * (if specified). Information used during validation.
     * 
     * @see \App\Observers\Helpers\AttachmentsValidator
     * 
     * @return int|null
     */
    public function minAttachmentsRequired(): ?int {
        return $this->getAttachmentsSettings('min');
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Check maximum required attachments for an attachable model
     * (if specified). Information used during validation.
     * 
     * @see \App\Observers\Helpers\AttachmentsValidator
     * 
     * @return int|null
     */
    public function maxAttachmentsRequired(): ?int {
        return $this->getAttachmentsSettings('max');
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * A concatenated list of all allowed mime types for the
     * attachments (if specified). Information used during validation.
     * 
     * @see \App\Observers\Helpers\AttachmentsValidator
     * 
     * @return string|null
     */
    public function getAllowedMimeTypes(): ?string {
        return $this->getAttachmentsSettings('mimes');
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * All attachment settings for a model should be declared
     * in the settings property, under the 'attachments' subkey.
     * If no settings property is specified, or the desired
     * setting is not set, null value will be returned. 
     * 
     * @return mixed
     */
    private function getAttachmentsSettings(string $setting): mixed {
        return $this->settings['attachments'][$setting] ?? null;
    }

}