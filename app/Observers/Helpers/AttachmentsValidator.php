<?php

namespace App\Observers\Helpers;

use App\Models\Interfaces\Attachable;
use Illuminate\Validation\ValidationException;

/**
 * The AttachmentsValidator is invoked by the AttachableObserver.
 * Its goal is to validate for attachments the incoming requests
 * which would result in an attachable object being created/updated.
 * If attachments are required, but are not present in the request,
 * an exception will be thrown.
 */

class AttachmentsValidator
{

    /**
     * Attachable object being validated for attachments.
     * Gets set in constructor.
     * 
     * @var Attachable
     */
    private Attachable $object;


    ///////////////////////////////////////////////////////////////////////////
    public function __construct(Attachable $object) {
        $this->object = $object;
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * If the attachable object currently has no attachments (in case
     * of the object being updated), make sure the request contains
     * attachments properties.
     * 
     * @throws ValidationException
     */
    public function validate(): void {
        if ( ! $this->object->hasAttachments()) {
            $this->validateRequestAttachments();
        }
    }

    ///////////////////////////////////////////////////////////////////////////
    private function validateRequestAttachments(): void {
        request()->validate([
            'attachments'   => $this->getAttachmentsRules(),
            'attachments.*' => $this->getAttachmentsElementsRules(),
        ]);
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getAttachmentsRules(): array {
        return [
            'required',
            'array',
            'min:1',
        ];
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getAttachmentsElementsRules(): array {
        return [
            'file',
        ];
    }
}