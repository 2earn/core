<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeliveryNotification extends Notification
{
    use Queueable;


    public function __construct()
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

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
            'idUser' => $notifiable->idUser,
            'url' => route('home',['locale'=>app()->getLocale()]),
        ];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'delivery_sms',
            'title' => __('notifications.settings.delivery_sms'),
            'message' => __('notifications.delivery_sms.body', ['name' => $notifiable->name]),
            'action_text' => __('notifications.delivery_sms.action'),
            'url' => route('home', ['locale' => app()->getLocale()]),
            'timestamp' => now()->toDateTimeString(),
        ];
    }
}
