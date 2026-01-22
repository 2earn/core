<?php

namespace App\Notifications;

use App\Models\Deal;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DealChangeRequestApproved extends Notification
{
    use Queueable;

    protected $deal;
    protected $changes;

    /**
     * Create a new notification instance.
     */
    public function __construct(Deal $deal, array $changes)
    {
        $this->deal = $deal;
        $this->changes = $changes;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'idUser' => $notifiable->idUser,
            'url' => route('deals.index', ['locale' => app()->getLocale()]),
            'message_params' => [
                'deal_name' => $this->deal->name,
                'changes' => $this->changes
            ]
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
