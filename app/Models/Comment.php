<?php

namespace App\Models;

use App\Models\Traits\HasVisitor;
use App\Notifications\NewComment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, HasVisitor, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,string>
     */
    public $fillable = [
        'name', 'message',
    ];


    ///////////////////////////////////////////////////////////////////////////
    /**
     * Most models are commentable, i.e. they have a morphMany
     * relationship to Comment model, but it is also possible
     * to get the main object through a comment using the
     * reversed morphTo relationship.
     * 
     * @return MorphTo
     */
    public function commentable(): MorphTo {
        return $this->morphTo('commentable');
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Once a Comment record gets created, an observer
     * listening for this event calls this method in order to
     * send the contact message as a notification to all
     * administrators.
     * 
     * NB! Keep in mind that the notification class implements
     *     the ShouldQueue interface, so instead of the email
     *     being sent straight away, it will be added to a queue
     *     which should be processed by a queue worker.
     * 
     * @see \App\Observers\CommentObserver
     * @see \App\Notifications\NewComment
     * 
     * @return void
     */
    public function sendAsNotification(): void {
        $users = User::getAllAdministrators();

        $users->each->notify((new NewComment($this))->afterCommit());
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get the title of the main commentable object.
     * Used in the notification email being sent upon creation.
     * 
     * @return string
     */
    public function getCommentableTitle(): string {
        return $this->commentable->title;
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Convert a comment to a public HTML string as
     * shown in the public part of the application.
     * 
     * @return string
     */
    public function asHtml(): string {
        $data = ['comment' => $this];

        return view('components.public.single-comment', $data)->render();
    }

}
