<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeliveryNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
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
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(__('notifications.settings.delivery_sms'))
            ->line(__('notifications.delivery_sms.body', ['name' => $notifiable->name]))
            ->action(__('notifications.delivery_sms.action'), route('home',['locale'=>app()->getLocale()]));
    }


    public function toDatabase($notifiable)
    {
        return [
            'title' => __('notifications.settings.delivery_sms'),
            'message' => __('notifications.delivery_sms.body', ['name' => $notifiable->name]),
            'url' => route('home',['locale'=>app()->getLocale()]),
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
