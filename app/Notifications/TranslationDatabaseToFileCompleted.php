<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TranslationDatabaseToFileCompleted extends Notification
{
    use Queueable;

    private string $executionTime;

    public function __construct(string $executionTime)
    {
        $this->executionTime = $executionTime;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'idUser' => $notifiable->idUser ?? $notifiable->id,
            'message' => trans('Keys to database operation completed successfully'),
            'execution_time' => $this->executionTime,
            'url' => route('translate', ['locale' => app()->getLocale()]),
            'type' => 'translation_database_to_file',
        ];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => trans('Keys to database operation completed successfully'),
            'execution_time' => $this->executionTime,
        ];
    }
}

