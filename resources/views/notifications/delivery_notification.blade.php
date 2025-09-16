<div class="text-reset notification-item d-block dropdown-item position-relative"
     id="{{$notification->id}}" title="{{$notification->id}}">
    <div class="d-flex">
        <div class="avatar-xs me-3 flex-shrink-0">
            <span class="btn btn-soft-info btn-sm material-shadow-none">
                  <i class="bx bx-message-square-dots"></i>
            </span>
        </div>
        <div class="flex-grow-1">
            <div class="float-end">
                @if ($notification->read_at === null)
                    <div class="form-check notification-check">
                        <button type="button" class="btn btn-link"
                                wire:click="markAsRead('{{$notification->id}}')"
                                id="all-notification-check{{$notification->id}}">
                            {{__('Mark as read')}}
                            <div wire:loading
                                 wire:target="markAsRead('{{$notification->id}}')">
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                      aria-hidden="true"></span>
                                <span
                                    class="sr-only">{{__('Loading')}}...</span>
                            </div>
                        </button>
                    </div>
                @endif
            </div>
            <h5>{{ __(__('notifications.settings.delivery_sms')) }}</h5>
            <p>{{ __('notifications.delivery_sms.body', $notification->data['message_params'] ?? []) }}</p>
            <a href="{{ $notification->data['url'] }}" class="btn btn-sm btn-primary">
                {{ __('notifications.delivery_sms.action') }}
            </a>

            <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                                <span><i
                                        class="mdi mdi-clock-outline"></i>   {{ $notification->created_at->diffForHumans() }}</span>
            </p>
        </div>
    </div>
</div>
