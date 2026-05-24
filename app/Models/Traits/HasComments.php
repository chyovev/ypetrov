<?php

namespace App\Models\Traits;

use DB;
use App\Models\Comment;
use App\Models\Visitor;
use App\Events\CommentCreated;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * The HasComments trait is supposed to be used on models
 * which have a polymorphic relationship to the Comment model.
 * To be used together with the Commentable interface.
 * 
 * @see \App\Models\Interfaces\Commentable
 */

trait HasComments
{

    use IsInteractive;

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Each commentable object can have multiple comments
     * and all comments are stored in a polymorphic table.
     * When fetched, by default the comments are ordered
     * from newest to oldest in a descending order.
     * 
     * @return MorphMany
     */
    public function comments(): MorphMany {
        return $this
            ->morphMany(Comment::class, 'commentable')
            ->oldest('id');
    }

    ///////////////////////////////////////////////////////////////////////////
    public function addComment(Visitor $visitor, string $name, string $message): Comment {
        return DB::transaction(function() use($visitor, $name, $message): Comment {
            $comment = $this->comments()->make([
                'name'    => $name,
                'message' => $message,
            ]);
            $comment->visitor()->associate($visitor);
            $comment->save();

            CommentCreated::dispatch($comment);

            return $comment;
        });
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Generate a unique API URL which can be used to add
     * comments to the current commentable object.
     * 
     * @return string 
     */
    public function getCommentsUrl(): string {
        return route('api.comment', ['id' => $this->getInteractionId()]);
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get the URL to the resource in the Content management system (CMS).
     * Used in the notification email sent on new comments.
     * 
     * NB! Since all commentable objects have admin resource routes defined,
     *     they all follow the convention of plural route name and singular
     *     main parameter. Therefore, the CMS URL can easily be generated
     *     using the model's table name as a route name and it's singular
     *     version for the parameter (which may be considered a hack).
     *     If a new commentable object which does not follow the convention
     *     ever gets introduced, this method can simply be overridden in
     *     said model's class.
     * 
     * @return string
     */
    public function getCMSUrl(): string {
        $name  = $this->getTable();
        $param = Str::singular($name);

        return route("admin.{$name}.edit", [$param => $this->id]);
    }

}