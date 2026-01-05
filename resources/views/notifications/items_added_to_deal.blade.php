<div class="notification-item">
    <div class="d-flex align-items-start">
        <div class="flex-shrink-0 me-3">
            <div class="avatar-sm">
                <span class="avatar-title bg-success-subtle text-success rounded-circle fs-2">
                    <i class="ri-add-circle-line"></i>
                </span>
            </div>
        </div>
        <div class="flex-grow-1">
            <h6 class="mb-1">{{ __('Items Added to Deal') }}</h6>
            <p class="mb-1 text-muted">
                {{ __(':count items have been added to deal: :deal', [
                    'count' => $notification->data['message_params']['items_count'] ?? 0,
                    'deal' => $notification->data['message_params']['deal_name'] ?? __('Unknown')
                ]) }}
            </p>
            <p class="mb-0 text-muted fs-6">
                <i class="ri-time-line align-middle"></i>
                <span>{{ $notification->created_at->diffForHumans() }}</span>
            </p>
        </div>
    </div>
</div>

