<?php

namespace App\API\Http\Requests;

use App\Models\Like;
use App\Models\Visitor;
use App\Exceptions\LikeException;
use App\Exceptions\IdentifierException;
use App\Models\Interfaces\Statsable;
use App\Models\Interfaces\Interactive;
use App\API\Http\Requests\Traits\IsInteractive;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LikeRequest extends FormRequest
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
     * Create a comment using the currently registered visitor as its author.
     * 
     * @throws LikeException – object already liked by visitor
     * @param  Visitor $visitor
     * @return Like
     */
    public function like(Visitor $visitor): Like {
        $object = $this->getLikableObject();

        return $object->like($visitor);
    }

    ///////////////////////////////////////////////////////////////////////////
    public function revokeLike(Visitor $visitor): void {
        $object = $this->getLikableObject();

        $object->revokeLike($visitor);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Try to match the encoded request identifier to a likable
     * object and make sure said object can be liked.
     * 
     * @throws IdentifierException
     * @throws ModelNotFoundException
     * @return Statsable
     */
    private function getLikableObject(): Statsable {
        $object = $this->getInteractiveObject();

        $this->validateObjectLikable($object);

        return $object;
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * An object is likable if it implements the Statsable
     * interface, but also if likes are allowed on it.
     * 
     * @param  Interactive $object
     * @throws IdentifierException
     */
    private function validateObjectLikable(Interactive $object): void {
        $class = class_basename($object);

        if ( ! ($object instanceof Statsable)) {
            throw new IdentifierException("Interactive object '{$class}' is not likable");
        }

        if ( ! $object->canBeLiked()) {
            throw new IdentifierException("Object '{$class}' (#{$object->id}) cannot be liked");
        }
    }

}