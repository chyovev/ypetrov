<?php

namespace App\API\Http\Requests\Traits;

use RateLimiter;
use App\Exceptions\IdentifierException;
use App\Models\Interfaces\Interactive;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * This trait should be used by the form requests which
 * register some sort of user interaction (comments, likes).
 * They are expected to contain an interaction identifier
 * ($id route parameter) which is used to retrieve an
 * interactive object from the database.
 * If the identifier does not correspond with an interactive
 * object, a IdentifierException gets thrown. If it does, but
 * no object is found, a ModelNotFoundException is thrown.
 * Both of them are rephased in the exception handler.
 * 
 * @see \App\Exceptions\Handler
 */

trait IsInteractive
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Try to find an interactive object which matches the encrypted
     * request route identifier.
     * 
     * @throws IdentifierException    – invalid identifier
     * @throws ModelNotFoundException – object not found
     */
    protected function getInteractiveObject(): Interactive {
        list ($model, $id) = $this->getModelAndIdFromIdentifier();

        // the model class name should implement the Interactive interface
        if ( ! is_a($model, Interactive::class, true)) {
            throw new IdentifierException("Model '{$model}' is not marked as interactive");
        }

        // the model ID should be an integer greater than 0
        if ( ! (ctype_digit($id) && $id > 0)) {
            throw new IdentifierException("Model ID '{$id}' is not a valid integer");
        }

        return $model::findOrFail($id);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * The decrypted identifier should consist of two parts:
     * model class name and model ID, separated by a pipe sign
     * as encrypted by the IsInteractive trait.
     * 
     * @see \App\Models\Traits\IsInteractive
     * 
     * @throws IdentifierException
     * @return array – [interactive model class name, model ID]
     */
    private function getModelAndIdFromIdentifier(): array {
        $identifier = $this->getDecryptedIdentifier();

        if (strpos($identifier, '|') === false) {
            throw new IdentifierException("Decrypted identifier '{$identifier}' ({$this->id}) cannot be broken down to model and ID");
        }

        return explode('|', $identifier, 2);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Try to decrypt the identifier and rephrase the exception on failure.
     * 
     * @throws IdentifierException
     * @return string
     */
    private function getDecryptedIdentifier(): string {
        try {
            return decrypt($this->id);
        }
        catch (DecryptException $e) {
            throw new IdentifierException("Identifier '{$this->id}' could not be decrypted");
        }
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Consecutive successful requests to the same interactive endpoint
     * should be discarded in case the limit is reached as a protection
     * against spam.
     * 
     * @param string $key             – endpoint identifier
     * @param string $throttleMessage – response message in case of throttle 
     * @param int    $limit           – limit of successful requests
     * @param int    $decaySeconds    – how long to keep further requests blocked
     * @return void
     */
    protected function discardConsecutiveRequests(
        string $key,
        string $throttleMessage,
        int $limit,
        int $decaySeconds = 60
    ): void {
        if ( ! RateLimiter::attempt($key, $limit, function() {}, $decaySeconds)) {
            abort(Response::HTTP_TOO_MANY_REQUESTS, $throttleMessage);
        }
    }
}