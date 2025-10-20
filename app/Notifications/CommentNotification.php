<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Comment;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CommentNotification extends Notification
{

    ///////////////////////////////////////////////////////////////////////////
    public function __construct(private Comment $comment) {
        //
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Which notification delivery channels the notification
     * will be sent via. Currently only email supported.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array {
        return ['mail'];
    }

    ///////////////////////////////////////////////////////////////////////////
    /**
     * Get the mail representation of the notification.
     * 
     * NB! Keep in mind that the MailMessage object will be
     *     converted automatically to an HTML body. 
     * 
     * @param User $user – user receiving the notification
     */
    public function toMail(User $user): MailMessage {
        return (new MailMessage)
            ->subject("Нов коментар към {$this->comment->getCommentableTitle()}")
            ->markdown('mail.comment', ['comment' => $this->comment]);
    }

}
