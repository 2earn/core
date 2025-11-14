<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SharePurchase extends Notification
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


    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }


    public function toDatabase($notifiable)
    {
        return [
            'idUser' => $notifiable->idUser,
            'url' => route('user_balance_shares',['locale'=>app()->getLocale()]),
        ];
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
