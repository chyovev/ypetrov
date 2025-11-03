<?php

namespace App\API\Http\Requests;

use DB;
use App\Models\Visitor;
use App\Models\ContactMessage;
use App\Events\ContactMessageCreated;
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
    public function rules(): array {
        return [
            'name'    => ['required', 'max:255'],
            'email'   => ['sometimes', 'max:255', 'nullable', 'email'],
            'message' => ['required', 'max:65535'],
        ];
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Create a contact message using the currently registered
     * visitor as its author.
     */
    public function createContactMessage(Visitor $visitor): void {
        // as a spam protection, consecutive *successful* requests
        // should be discarded for a certain period of time;
        // there's no limit on the unsuccessful requests, they would
        // never reach this part of the code
        $this->discardConsecutiveRequests("add-contact-message", __('global.contact_limit'), 1);

        DB::transaction(function() use ($visitor): void {
            $data    = $this->validated();
            $message = new ContactMessage($data);
            $message->visitor()->associate($visitor);
            $message->save();

            ContactMessageCreated::dispatch($message);
        });
    }

}