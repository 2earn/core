<?php
}
    }
        ];
            //
        return [
    {
    public function toArray(object $notifiable): array
     */
     * @return array<string, mixed>
     *
     * Get the array representation of the notification.
    /**

    }
        ];
            ]
                'changes' => $this->changes
                'deal_name' => $this->deal->name,
            'message_params' => [
            'url' => route('deals.index', ['locale' => app()->getLocale()]),
            'idUser' => $notifiable->idUser,
        return [
    {
    public function toDatabase($notifiable)
     */
     * Get the database representation of the notification.
    /**

    }
            ->line('Thank you for using our application!');
            ->action('Notification Action', url('/'))
            ->line('The introduction to the notification.')
        return (new MailMessage)
    {
    public function toMail(object $notifiable): MailMessage
     */
     * Get the mail representation of the notification.
    /**

    }
        return ['database'];
    {
    public function via(object $notifiable): array
     */
     * @return array<int, string>
     *
     * Get the notification's delivery channels.
    /**

    }
        $this->changes = $changes;
        $this->deal = $deal;
    {
    public function __construct(Deal $deal, array $changes)
     */
     * Create a new notification instance.
    /**

    protected $changes;
    protected $deal;

    use Queueable;
{
class DealChangeRequestApproved extends Notification

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Bus\Queueable;
use App\Models\Deal;

namespace App\Notifications;


