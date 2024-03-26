<?php

namespace App\API\Http\Requests;

use App\Models\ContactMessage;
use App\Models\Visitor;
use App\API\Http\Requests\Traits\IsInteractive;
use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{

    /**
     * Use trait to discard consecutive requests
     * via rate limiting.
     */
    use IsInteractive;


    ///////////////////////////////////////////////////////////////////////////
    /**
     * Always allow the store request to go through.
     * 
     * @return bool
     */
    public function authorize(): bool {
        return true;
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\\Rule|array|string>
     */
    public function rules(): array {
        return [
            'name'    => $this->getNameRules(),
            'email'   => $this->getEmailRules(),
            'message' => $this->getMessageRules(),
        ];
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getNameRules(): array {
        return [
            'required',
            'max:255',
        ];
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getEmailRules(): array {
        return [
            'sometimes',
            'nullable',
            'email',
            'max:255',
        ];
    }

    ///////////////////////////////////////////////////////////////////////////
    private function getMessageRules(): array {
        return [
            'required',
            'max:65535',
        ];
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Create a contact message using the currently registered
     * visitor as its author.
     * 
     * @param  Visitor $visitor
     * @return void
     */
    public function createContactMessage(Visitor $visitor): void {
        // as a spam protection, consecutive *successful* requests
        // should be discarded for a certain period of time;
        // there's no limit on the unsuccessful requests, they would
        // never reach this part of the code
        $this->discardConsecutiveRequests("add-contact-message", __('global.contact_limit'), 1);

        $data    = $this->validated();
        $message = ContactMessage::make($data);

        $message->visitor()->associate($visitor);

        $message->save();
    }

}