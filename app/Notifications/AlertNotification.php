<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
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
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'participant_id' => $this->participants['participant_id'],
            'Url' => $this->participants['Url'],
            'text' => $this->participants['text']
            // 'action' => $this->participants['action'],
            // 'company_name' => $this->participants['company_name'],
            // 'participant_name' => $this->participants['participant_name'],
            // 'event_name' => $this->participants['event_name']
        ];
    }

    // public function toDatabase($notifiable)
    // {
    //     return [
    //         'participant_id' => $this->participants['participant_id']
    //     ];
    // }
}
