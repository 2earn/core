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
                'role' => $this->role
                'platform_name' => $this->platform->name,
            'message_params' => [
            'url' => route('platforms.index', ['locale' => app()->getLocale()]),
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
        $this->role = $role;
        $this->platform = $platform;
    {
    public function __construct(Platform $platform, string $role)
     */
     * Create a new notification instance.
    /**

    protected $role;
    protected $platform;

    use Queueable;
{
class PlatformRoleAssignmentApproved extends Notification

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Bus\Queueable;
use App\Models\Platform;
use App\Models\AssignPlatformRole;

namespace App\Notifications;


