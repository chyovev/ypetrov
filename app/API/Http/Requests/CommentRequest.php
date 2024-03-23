<?php

namespace App\API\Http\Requests;

use App\Models\Comment;
use App\Models\Visitor;
use App\Exceptions\IdentifierException;
use App\Models\Interfaces\Commentable;
use App\Models\Interfaces\Interactive;
use App\API\Http\Requests\Traits\IsInteractive;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CommentRequest extends FormRequest
{

    /**
     * Trait to fetch the interactive object
     * from the request.
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array {
        return [
            'name'    => $this->getNameRules(),
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
    private function getMessageRules(): array {
        return [
            'required',
            'max:65535',
        ];
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Create a comment using the currently registered visitor as its author.
     * 
     * @throws IdentifierException    – invalid commentable object
     * @throws ModelNotFoundException – missing/inactive commentable object
     * @throws HttpException          – too many consecutive requests
     * @param  Visitor $visitor
     * @return Comment
     */
    public function createComment(Visitor $visitor): Comment {
        $object  = $this->getCommentableObject();

        // as a spam protection, consecutive *successful* requests
        // should be discarded for a certain period of time;
        // there's no limit on the unsuccessful requests, they would
        // never reach this part of the code
        $this->discardConsecutiveRequests("add-comment", __('global.comment_limit'), 1);

        $name    = $this->validated('name');
        $message = $this->validated('message');

        return $object->addComment($visitor, $name, $message);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Try to match the encoded request identifier to a commentable
     * object and make sure said object can be commented on.
     * 
     * @throws IdentifierException
     * @throws ModelNotFoundException
     * @return Commentable
     */
    private function getCommentableObject(): Commentable {
        $object = $this->getInteractiveObject();

        $this->validateObjectCommentable($object);

        return $object;
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * An object is commentable if it implements the Commentable
     * interface, but also if commenting is allowed on it.
     * 
     * @param  Interactive $object
     * @throws IdentifierException
     */
    private function validateObjectCommentable(Interactive $object): void {
        $class = class_basename($object);

        if ( ! ($object instanceof Commentable)) {
            throw new IdentifierException("Interactive object '{$class}' is not commentable");
        }

        if ( ! $object->canBeCommentedOn()) {
            throw new IdentifierException("Object '{$class}' (#{$object->id}) cannot be commented on");
        }
    }

}