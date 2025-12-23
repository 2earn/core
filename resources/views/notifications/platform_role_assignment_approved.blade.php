<div class="text-reset notification-item d-block dropdown-item position-relative border-0 border-bottom @if ($notification->read_at === null) bg-primary bg-opacity-10 @else bg-white @endif"
</div>
    </div>
        </div>
            </div>
                </div>
                    @endif
                        </span>
                            <i class="ri-mail-open-fill"></i>
                        <span class="text-success fs-16" title="{{__('Read')}}">
                    @else
                        </button>
                            </span>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            <span wire:loading wire:target="markAsRead('{{$notification->id}}')">
                            <i class="ri-mail-fill fs-16"></i>
                                title="{{__('Mark as read')}}">
                                id="all-notification-check{{$notification->id}}"
                                wire:click="markAsRead('{{$notification->id}}')"
                        <button type="button" class="btn btn-sm btn-link p-1 text-primary"
                    @if ($notification->read_at === null)
                    </span>
                        <span>{{$notification->created_at->diffForHumans() }}</span>
                        <i class="mdi mdi-clock-outline fs-12"></i>
                    <span class="text-muted fs-11 d-inline-flex align-items-center gap-1">
                <div class="d-flex align-items-center gap-2">
                </a>
                    <i class="ri-arrow-right-s-line fs-16"></i>
                    <span class="fw-semibold" style="font-size: 0.8rem;">{{ __('notifications.platform_role_assignment_approved.action') }}</span>
                   class="btn btn-sm btn-outline-success px-3 py-1 d-inline-flex align-items-center gap-1">
                <a href="{{ \App\Helpers\NotificationHelper::localizeUrl($notification->data['url'],app()->getLocale()) }}"
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            </p>
                ]) }}
                    'role' => ucfirst(str_replace('_', ' ', $notification->data['message_params']['role'] ?? ''))
                    'platform_name' => $notification->data['message_params']['platform_name'] ?? '',
                {{ __('notifications.platform_role_assignment_approved.body', [
            <p class="text-muted mb-2 fs-13" style="line-height: 1.5;">
            </div>
                @endif
                    </span>
                        {{__('New')}}
                    <span class="badge bg-soft-primary rounded-pill ms-2 flex-shrink-0" style="font-size: 0.625rem; padding: 0.15rem 0.4rem;">
                @if ($notification->read_at === null)
                <h6 class="fs-14 fw-semibold mb-1 text-dark">{{ __('notifications.settings.platform_role_assignment_approved') }}</h6>
            <div class="d-flex justify-content-between align-items-start mb-1">
        <div class="flex-grow-1 overflow-hidden">
        </div>
            </span>
                <i class="las la-user-check"></i>
            <span class="avatar-title bg-success bg-opacity-10 text-success rounded-2 fs-18">
        <div class="avatar-sm me-3 flex-shrink-0">
    <div class="d-flex align-items-start p-2">
    style="transition: all 0.2s ease;">
    title="{{$notification->id}}"
    id="{{$notification->id}}"

