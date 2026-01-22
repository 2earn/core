<?php

namespace App\Notifications;

use App\Models\Deal;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ItemsRemovedFromDeal extends Notification
{
    use Queueable;

    protected $deal;
    protected $itemsCount;
    protected $productIds;

    /**
     * Create a new notification instance.
     */
    public function __construct(Deal $deal, int $itemsCount, array $productIds)
    {
        $this->deal = $deal;
        $this->itemsCount = $itemsCount;
        $this->productIds = $productIds;
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
            ->subject(__('Items Removed from Deal'))
            ->line(__(':count items have been removed from deal: :deal', [
                'count' => $this->itemsCount,
                'deal' => $this->deal->name
            ]))
            ->action(__('View Deal'), route('deals_show', ['locale' => app()->getLocale(), 'id' => $this->deal->id]))
            ->line(__('Thank you for using our platform!'));
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'idUser' => $notifiable->idUser ?? $notifiable->id,
            'url' => route('deals_show', ['locale' => app()->getLocale(), 'id' => $this->deal->id]),
            'message_params' => [
                'deal_id' => $this->deal->id,
                'deal_name' => $this->deal->name,
                'items_count' => $this->itemsCount,
                'product_ids' => $this->productIds
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
            'deal_id' => $this->deal->id,
            'deal_name' => $this->deal->name,
            'items_count' => $this->itemsCount,
        ];
    }
}

