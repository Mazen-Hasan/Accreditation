<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AlertNotification extends Notification
{
    use Queueable;

    private $participants;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($participants)
    {
        $this->participants = $participants;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'participant_id' => $this->participants['participant_id'],
            'Url' => $this->participants['Url'],
            'text' => $this->participants['text']
        ];
    }
}
