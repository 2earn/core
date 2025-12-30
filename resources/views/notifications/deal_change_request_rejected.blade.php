<div class="notification-item position-relative border-0 @if ($notification->read_at === null) unread @endif"
    id="{{$notification->id}}"
    data-notification-id="{{$notification->id}}"
    style="transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); border-radius: 12px; margin-bottom: 8px; overflow: hidden;">

    @if ($notification->read_at === null)
        <div class="position-absolute top-0 start-0 h-100 bg-danger" style="width: 4px;"></div>
    @endif

    <div class="d-flex align-items-start p-1 gap-1 position-relative"
         style="background: @if ($notification->read_at === null) linear-gradient(135deg, rgba(var(--bs-danger-rgb), 0.05) 0%, rgba(var(--bs-danger-rgb), 0.02) 100%) @endif;">

        <div class="flex-shrink-0">
            <div class="avatar-md d-flex align-items-center justify-content-center rounded-3 position-relative"
                 style="background: linear-gradient(135deg, rgba(var(--bs-danger-rgb), 0.15) 0%, rgba(var(--bs-danger-rgb), 0.05) 100%);
                        width: 48px; height: 48px; box-shadow: 0 2px 8px rgba(var(--bs-danger-rgb), 0.1);">
                <i class="las la-times-circle text-danger" style="font-size: 24px;"></i>
            </div>
        </div>

        <div class="flex-grow-1 overflow-hidden">
            <div class="d-flex justify-content-between align-items-center mb-2 gap-2">
                <h6 class="fs-15 fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                    {{ __('notifications.settings.deal_change_request_rejected') }}
                    @if ($notification->read_at === null)
                        <span class="badge rounded-pill"
                              style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
                                     font-size: 0.65rem; padding: 0.2rem 0.5rem;
                                     box-shadow: 0 2px 4px rgba(238, 90, 111, 0.3);">
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
                {{ __('notifications.deal_change_request_rejected.body', ['deal_name' => $notification->data['message_params']['deal_name'] ?? '']) }}
            </p>

            @if(isset($notification->data['message_params']['reason']) && !empty($notification->data['message_params']['reason']))
                <div class="alert alert-soft-danger mb-3 py-2 px-3" style="border-radius: 8px; border-left: 3px solid var(--bs-danger);">
                    <div class="d-flex gap-2">
                        <i class="ri-information-line text-danger fs-16 flex-shrink-0 mt-1"></i>
                        <div>
                            <strong class="fs-13">{{ __('Rejection Reason') }}:</strong>
                            <p class="mb-0 fs-13 text-muted mt-1">{{ $notification->data['message_params']['reason'] }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <a href="{{ \App\Helpers\NotificationHelper::localizeUrl($notification->data['url'],app()->getLocale()) }}"
                   class="btn btn-sm btn-danger p-1 d-inline-flex align-items-center gap-2 rounded-pill"
                   style="transition: all 0.3s; box-shadow: 0 2px 6px rgba(var(--bs-danger-rgb), 0.25);">
                    <span class="fw-semibold">{{ __('notifications.deal_change_request_rejected.action') }}</span>

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
                             style="width: 36px; height: 36px; background: rgba(var(--bs-success-rgb), 0.1);"
                             title="{{__('Read')}}">

                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

