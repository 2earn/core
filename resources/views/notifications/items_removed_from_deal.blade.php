<div class="notification-item position-relative border-0 @if ($notification->read_at === null) unread @endif"
    id="{{$notification->id}}"
    data-notification-id="{{$notification->id}}"
    style="transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); border-radius: 12px; margin-bottom: 8px; overflow: hidden;">

    @if ($notification->read_at === null)
        <div class="position-absolute top-0 start-0 h-100 bg-danger" style="width: 4px;"></div>
    @endif

    <div class="d-flex align-items-start p-1 gap-1 position-relative"
         style="background: @if ($notification->read_at === null) linear-gradient(135deg, rgba(var(--bs-danger-rgb), 0.05) 0%, rgba(var(--bs-danger-rgb), 0.02) 100%) @endif;">

        <div class="flex-grow-1 overflow-hidden">
            <div class="d-flex justify-content-between align-items-center mb-2 gap-2">
                <h6 class="fs-15 fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                    {{ __('Items Removed from Deal') }}
                    @if ($notification->read_at === null)
                        <span class="badge rounded-pill"
                              style="background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
                                     font-size: 0.65rem; padding: 0.2rem 0.5rem;
                                     box-shadow: 0 2px 4px rgba(244, 92, 67, 0.3);">
                            <i class="ri-notification-3-line me-1" style="font-size: 0.7rem;"></i>
                            {{__('New')}}
                        </span>
                    @endif
                </h6>
                <span class="text-muted fs-12 d-none d-sm-inline-flex align-items-center gap-1">
                    <i class="ri-time-line"></i>
                    {{$notification->created_at->diffForHumans()}}
                </span>
            </div>

            <p class="text-muted mb-3 fs-14" style="line-height: 1.6;">
                {{ __(':count items have been removed from deal: :deal', [
                    'count' => $notification->data['message_params']['items_count'] ?? 0,
                    'deal' => $notification->data['message_params']['deal_name'] ?? __('Unknown')
                ]) }}
            </p>

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <a href="{{ \App\Helpers\NotificationHelper::localizeUrl($notification->data['url'],app()->getLocale()) }}"
                   class="btn btn-sm btn-danger p-1 d-inline-flex align-items-center gap-2 rounded-pill"
                   style="transition: all 0.3s; box-shadow: 0 2px 6px rgba(var(--bs-danger-rgb), 0.25);">
                    <span class="fw-semibold">{{ __('View Deal') }}</span>
                </a>

                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted fs-12 d-sm-none d-inline-flex align-items-center gap-1">
                        <i class="ri-time-line"></i>
                        {{$notification->created_at->diffForHumans()}}
                    </span>
                    @if ($notification->read_at === null)
                        <button type="button"
                                class="btn btn-sm btn-light border-0 d-inline-flex align-items-center justify-content-center rounded-circle"
                                style="width: 36px; height: 36px; transition: all 0.3s;"
                                wire:click="markAsRead('{{$notification->id}}')"
                                id="all-notification-check{{$notification->id}}"
                                title="{{__('Mark as read')}}">
                            <i class="ri-mail-line fs-18"></i>
                            <span wire:loading wire:target="markAsRead('{{$notification->id}}')" class="position-absolute">
                                <span class="spinner-border spinner-border-sm text-danger" role="status"></span>
                            </span>
                        </button>
                    @else
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle"
                             style="width: 36px; height: 36px; background: rgba(var(--bs-danger-rgb), 0.1);"
                             title="{{__('Read')}}">
                            <i class="ri-mail-check-line fs-18 text-muted"></i>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

