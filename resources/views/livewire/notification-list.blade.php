<div class="{{getContainerType()}}">
    @section('title')
        {{ __('Notifications list') }}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Notifications list') }}
        @endslot
    @endcomponent
    <div class="card">
        <div class="card-body">
            <div class="row card">
                <div class="card-header">

                    <div class="btn-group" role="group" aria-label="Notification Filter">
                        <input type="radio" class="btn-check" name="filter" id="filter-all" value="all"
                               wire:model.live="filter" autocomplete="off">
                        <label class="btn btn-outline-primary" for="filter-all">{{ __('All') }}</label>

                        <input type="radio" class="btn-check" name="filter" id="filter-unread" value="unread"
                               wire:model.live="filter" autocomplete="off">
                        <label class="btn btn-outline-primary" for="filter-unread">{{ __('Unread') }}</label>

                        <input type="radio" class="btn-check" name="filter" id="filter-read" value="read"
                               wire:model.live="filter" autocomplete="off">
                        <label class="btn btn-outline-primary" for="filter-read">{{ __('Read') }}</label>
                    </div>
                    <div class="p-2 btn btn-primary float-end mx-2">
                        <a wire:click="markThemAllRead()"
                        >{{__('Mark them all as read')}}
                            <div wire:loading
                                 wire:target="markThemAllRead()">
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                      aria-hidden="true"></span>
                                <span
                                    class="sr-only">{{__('Loading')}}...</span>
                            </div>
                        </a>

                    </div>
                </div>
                <div class="card-body">
                    @forelse($notifications as $notification)
                        @include(\App\Helpers\NotificationHelper::getTemplate($notification) , ['notification' => $notification])
                    @empty
                        <div class="text-muted">{{__('No notifications found.')}}</div>
                    @endforelse
                </div>
                <div class="card-footer">
                    {{ $notifications->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
