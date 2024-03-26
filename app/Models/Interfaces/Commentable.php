<?php

namespace App\Models\Interfaces;

use App\Models\Comment;
use App\Models\Visitor;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * All models which have a polymorphic relationship to
 * the Comment model should implement this interface
 * and use the hasComments trait.
 * Commentable models have an observer which takes care
 * of polymorphic data clean-up during deletion.
 * 
 * @see \App\Models\Traits\HasComments 
 * @see \App\Observers\CommentableObserver
 */

interface Commentable extends Interactive
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * All models implementing the Commentable interface should
     * have a polymorphic relationship to the Comment model declared.
     * 
     * @return MorphMany
     */
    public function comments(): MorphMany;


    ///////////////////////////////////////////////////////////////////////////
    /**
     * Create a new comment for the commentable object.
     * 
     * @param  Visitor $visitor – registered visitor (author)
     * @param  string  $name    – author of comment
     * @param  string  $message – body of comment
     * @return Comment
     */
    public function addComment(Visitor $visitor, string $name, string $message): Comment;


    ///////////////////////////////////////////////////////////////////////////
    /**
     * Check if a commentable object can actually be commented on.
     * (Usually the activeness state of the object is verified).
     * 
     * @return bool
     */
    public function canBeCommentedOn(): bool;


    ///////////////////////////////////////////////////////////////////////////
    /**
     * Generate a unique API URL which can be used to add
     * comments to the current commentable object.
     * 
     * @return string 
     */
    public function getCommentsUrl(): string;


    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get the URL to the resource in the Content management system (CMS).
     * Used in the notification email sent on new comments.
     * 
     * @return string
     */
    public function getCMSUrl(): string;


}