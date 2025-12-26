<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TranslationMergeCompleted extends Notification
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
            'message' => trans('Translation merge operation completed successfully'),
            'execution_time' => $this->executionTime,
            'url' => route('translate', ['locale' => app()->getLocale()]),
            'type' => 'translation_merge',
        ];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => trans('Translation merge operation completed successfully'),
            'execution_time' => $this->executionTime,
        ];
    }
}
