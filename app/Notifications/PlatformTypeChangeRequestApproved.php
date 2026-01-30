<?php

namespace App\Notifications;

use App\Models\Platform;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PlatformTypeChangeRequestApproved extends Notification
{
    use Queueable;

    protected $platform;
    protected $oldType;
    protected $newType;

    /**
     * Create a new notification instance.
     */
    public function __construct(Platform $platform, $oldType, $newType)
    {
        $this->platform = $platform;
        $this->oldType = $oldType;
        $this->newType = $newType;
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
            'url' => route('platform_index', ['locale' => app()->getLocale()]),
            'message_params' => [
                'platform_name' => $this->platform->name,
                'old_type' => $this->oldType,
                'new_type' => $this->newType
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

