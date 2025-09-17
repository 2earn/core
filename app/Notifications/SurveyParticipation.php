<?php

namespace App\Notifications;

use App\Models\Survey;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SurveyParticipation extends Notification
{
    use Queueable;

    public function __construct(public Survey $survey)
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
            'url' => route('surveys_show', ['locale' => app()->getLocale(), 'idSurvey' => $this->survey->id]),
        ];
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
