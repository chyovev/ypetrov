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

    /**
     * How many attachments are currently
     * associated with the attachable object.
     * Gets set in constructor.
     */
    private int $count = 0;

    /**
     * Validation rules for the attachments
     * array property, gets populated in constructor. 
     * 
     * @var array<int,mixed>
     */
    private array $rules = [];

    /**
     * User-friendly validation error messages.
     * 
     * @var array<string,string>
     */
    private array $messages = [];


    ///////////////////////////////////////////////////////////////////////////
    public function __construct(Attachable $object) {
        $this->object = $object;
        $this->count  = $object->attachments()->count();

        $this->setValidationRules();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Based on the attachable model's attachments' settings,
     * as well as the current state of the attachments association,
     * the validation rules may vary.
     * 
     * @return void
     */
    private function setValidationRules(): void {
        if ($this->objectIsMissingAttachments()) {
            $this->markAsRequired();
        }

        if ($this->objectHasAllAttachments()) {
            $this->markAsProhibitted();
        }

        if ($this->objectHasMimePreferences()) {
            $this->markAsExpectingCertainMimeTypes();
        }
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * An object is considered to be missing attachments if the minimal required
     * number of attachments is less than the current attachments associations.
     * 
     * @return bool
     */
    private function objectIsMissingAttachments(): bool {
        $min = $this->object->minAttachmentsRequired();

        // if the setting is not present (i.e. it's null),
        // the object is not considered missing attachments
        return (is_null($min))
            ? false
            : (($min - $this->count) > 0);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Marking attachments as required for an object also
     * specifies the minimal number of expected attachments.
     * 
     * @return void
     */
    private function markAsRequired(): void {
        $min     = $this->object->minAttachmentsRequired();
        $missing = $min - $this->count;

        $this->addRule("required",       "Attachments required for this object: {$missing}");
        $this->addRule("min:{$missing}", "At least {$missing} more attachments need to be uploaded.");
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Add a validation rule to the set alongside a user-friendly
     * error message (if present).
     * 
     * @param  string $rule
     * @param  string $message
     * @return void
     */
    private function addRule(string $rule, string $message = null): void {
        $this->rules[] = $rule;

        // if the validation rule has parameters (after the ':'),
        // remove them for the message
        if ( ! is_null($message)) {
            $rulePrefix = preg_match('/:/', $rule)
                ? strstr($rule, ':', true)
                : $rule;

            $this->messages["attachments.{$rulePrefix}"] = $message;
        }
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * An object is considered to be having all attachments if the
     * number of current attachments associations matches (or exceeds)
     * the maximum allowed attachments count.
     * 
     * @return bool
     */
    private function objectHasAllAttachments(): bool {
        $max = $this->object->maxAttachmentsRequired();

        // if the setting is not present (i.e. it's null),
        // the object is not considered to be having all attachments
        return (is_null($max))
            ? false
            : ($this->count >= $max);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * When the object has all attachments, new attachments cannot
     * be processed, i.e. having the attachments property in the
     * requiest should be prohibited.
     * 
     * @return void
     */
    private function markAsProhibitted(): void {
        $this->addRule('prohibited', "Object has too many attachments ({$this->count}), consider deleting some.");
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * An object has MIME preferences for the attachments
     * if the respective setting is populated.
     * 
     * @return bool
     */
    private function objectHasMimePreferences(): bool {
        return (bool) $this->object->getAllowedMimeTypes();
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Do not allow any other MIME types than the ones which are
     * specifically white-listed in the model's MIME settings.
     * 
     * @return void
     */
    private function markAsExpectingCertainMimeTypes(): void {
        $mimes = $this->object->getAllowedMimeTypes();

        $this->addRule("mimes:{$mimes}", "Unsupported MIME type. Supported types are: {$mimes}");
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Validate the request for attachments.
     * 
     * @throws ValidationException
     */
    public function validate(): void {
        $allRules = [
            'attachments'   => $this->rules,
            'attachments.*' => ['file'],
        ];

        request()->validate($allRules, $this->messages);
    }
}