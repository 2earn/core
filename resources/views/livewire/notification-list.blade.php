<div class="container-fluid">
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
                    <div class="btn-group float-end" role="group" aria-label="Notification Filter">
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
                </div>
                <div class="card-body">
                    @forelse($notifications as $notification)
                        <div class="p-3 border rounded mb-2 @if($notification->read_at === null) bg-gray-100 @endif">
                            <h5>{{ __(__('notifications.settings.delivery_sms')) }}</h5>
                            <p>{{ __('notifications.delivery_sms.body',  ['name'=> isset($notification->data['idUser'])? getUserDisplayedName($notification->data['idUser']):'No name']) }}</p>
                            <a href="{{ $notification->data['url'] }}" class="btn btn-sm btn-primary">
                                {{ __('notifications.delivery_sms.action') }}
                            </a>
                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>

                            @if($notification->read_at === null)
                                <button wire:click="markAsRead('{{ $notification->id }}')"
                                        class="btn btn-sm btn-link text-primary">
                                    {{__('Mark as read')}}
                                </button>
                            @endif
                        </div>
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
