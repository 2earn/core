<div class="text-reset notification-item d-block dropdown-item position-relative border-0 border-bottom @if ($notification->read_at === null) bg-danger bg-opacity-10 @else bg-white @endif"
    id="{{$notification->id}}"
    title="{{$notification->id}}"
    style="transition: all 0.2s ease;">
    <div class="d-flex align-items-start p-2">
        <div class="avatar-sm me-3 flex-shrink-0">
            <span class="avatar-title bg-danger bg-opacity-10 text-danger rounded-2 fs-18">
                <i class="las la-times-circle"></i>
            </span>
        </div>
        <div class="flex-grow-1 overflow-hidden">
            <div class="d-flex justify-content-between align-items-start mb-1">
                <h6 class="fs-14 fw-semibold mb-1 text-dark">{{ __('notifications.settings.platform_change_request_rejected') }}</h6>
                @if ($notification->read_at === null)
                    <span class="badge bg-soft-primary rounded-pill ms-2 flex-shrink-0" style="font-size: 0.625rem; padding: 0.15rem 0.4rem;">
                        {{__('New')}}
                    </span>
                @endif
            </div>
            <p class="text-muted mb-2 fs-13" style="line-height: 1.5;">
                {{ __('notifications.platform_change_request_rejected.body', ['platform_name' => $notification->data['message_params']['platform_name'] ?? '']) }}
            </p>
            @if(isset($notification->data['message_params']['changes']) && is_array($notification->data['message_params']['changes']))
                <div class="mb-2">
                    <small class="text-muted">{{ __('Requested changes') }}:</small>
                    <ul class="list-unstyled mb-0 ms-2">
                        @foreach($notification->data['message_params']['changes'] as $field => $value)
                            <li class="fs-12 text-muted">
                                <i class="ri-arrow-right-s-fill"></i>
                                <strong>{{ ucfirst(str_replace('_', ' ', $field)) }}</strong>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if(isset($notification->data['message_params']['rejection_reason']))
                <div class="alert alert-danger alert-dismissible fade show p-2 mb-2" role="alert">
                    <strong>{{ __('Rejection Reason') }}:</strong> {{ $notification->data['message_params']['rejection_reason'] }}
                </div>
            @endif
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <a href="{{ \App\Helpers\NotificationHelper::localizeUrl($notification->data['url'],app()->getLocale()) }}"
                   class="btn btn-sm btn-outline-danger px-3 py-1 d-inline-flex align-items-center gap-1">
                    <span class="fw-semibold" style="font-size: 0.8rem;">{{ __('notifications.platform_change_request_rejected.action') }}</span>
                    <i class="ri-arrow-right-s-line fs-16"></i>
                </a>
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted fs-11 d-inline-flex align-items-center gap-1">
                        <i class="mdi mdi-clock-outline fs-12"></i>
                        <span>{{$notification->created_at->diffForHumans() }}</span>
                    </span>
                    @if ($notification->read_at === null)
                        <button type="button" class="btn btn-sm btn-link p-1 text-primary"
                                wire:click="markAsRead('{{$notification->id}}')"
                                id="all-notification-check{{$notification->id}}"
                                title="{{__('Mark as read')}}">
                            <i class="ri-mail-fill fs-16"></i>
                            <span wire:loading wire:target="markAsRead('{{$notification->id}}')">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            </span>
                        </button>
                    @else
                        <span class="text-success fs-16" title="{{__('Read')}}">
                            <i class="ri-mail-open-fill"></i>
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

