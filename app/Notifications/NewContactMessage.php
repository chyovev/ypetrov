<?php

namespace App\Notifications;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewContactMessage extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The ContactMessage object whose properties
     * will be used in the notification email.
     * Gets set in constructor.
     * 
     * @var ContactMessage
     */
    private ContactMessage $contactMessage;

    ///////////////////////////////////////////////////////////////////////////
    public function __construct(ContactMessage $contactMessage) {
        $this->contactMessage = $contactMessage;
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
            ->subject('Ново контактно съобщение')
            ->greeting("Ново контактно съобщение")
            ->line("Получено на: {$this->contactMessage->created_at}")
            ->line("От: {$this->contactMessage->name}")
            ->line("E-mail: {$this->contactMessage->email}")
            ->line("Съобщение: ")
            ->line($this->contactMessage->message);
    }

}
