<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\ContactMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ContactMessageNotification extends Notification
{

    ///////////////////////////////////////////////////////////////////////////
    public function __construct(private ContactMessage $message) {
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
            ->subject('Ново контактно съобщение')
            ->markdown('mail.contact_message', ['message' => $this->message]);
    }

}
