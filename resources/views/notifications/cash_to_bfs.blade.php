<div
    class="text-reset notification-item d-block dropdown-item position-relative m-1 @if  ($notification->read_at === null) alert alert-dark border-0 material-shadow @else alert alert-light  border-0 material-shadow @endif"
    id="{{$notification->id}}"
    title="{{$notification->id}}">
    <div class="d-flex">
        <div class="avatar-xs me-3 flex-shrink-0">
            <span class="btn btn-soft-info btn-sm material-shadow-none">
                <i class="las la-dollar-sign"></i>
            </span>
        </div>
        <div class="flex-grow-1">
            <h5>{{ __(__('notifications.settings.cash_to_bfs')) }}</h5>
            <p>{{ __('notifications.cash_to_bfs.body', $notification->data['message_params'] ?? []) }}</p>
            <a href="{{ $notification->data['url'] }}" class="btn btn-sm btn-primary">
                {{ __('notifications.cash_to_bfs.action') }}
            </a>
            <p class="mb-0 fs-11 fw-medium text-uppercase text-muted float-end">
                <span><i
                        class="mdi mdi-clock-outline"></i>   {{$notification->created_at->diffForHumans() }}
                </span>
            </p>
        </div>
        <div class="flex-grow-1">
            @if ($notification->read_at === null)
                <button type="button" class="btn btn-link"
                        wire:click="markAsRead('{{$notification->id}}')"
                        id="all-notification-check{{$notification->id}}">
                        <span class="btn btn-soft-info btn-sm material-shadow-none float-end" title="{{__('Mark as read')}}">
                <i class="ri-mail-fill"></i>
            </span>
                    <div wire:loading
                         wire:target="markAsRead('{{$notification->id}}')">
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                      aria-hidden="true"></span>
                        <span
                            class="sr-only">{{__('Loading')}}...</span>
                    </div>
                </button>

            @else
                <span class="btn btn-soft-warning btn-sm material-shadow-none float-end" title="{{__('Read')}}">
                    <i class="ri-mail-open-fill"></i>
                </span>
            @endif
        </div>
    </div>
</div>
