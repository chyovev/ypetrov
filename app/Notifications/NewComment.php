<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewComment extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The Comment object whose properties
     * will be used in the notification email.
     * Gets set in constructor.
     * 
     * @var Comment
     */
    private Comment $comment;

    ///////////////////////////////////////////////////////////////////////////
    public function __construct(Comment $comment) {
        $this->comment = $comment;
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
     * @param  object $notifiable – an instance of a User
     * @return MailMessage
     */
    public function toMail(object $notifiable): MailMessage {
        return (new MailMessage)
            ->subject("Нов коментар към {$this->comment->getCommentableTitle()}")
            ->greeting("Нов коментар към {$this->comment->getCommentableTitle()}")
            ->line("Получено на: {$this->comment->created_at}")
            ->line("От: {$this->comment->name}")
            ->line("Съобщение: ")
            ->line($this->comment->message);
    }

}
