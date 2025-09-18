<div class="dropdown topbar-head-dropdown ms-1 header-item">
    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary"
            id="page-header-notifications-dropdown"
            data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
        <i class='bx bx-bell fs-22'></i>
        <span
            class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-danger"
            id="notif-counter">
            {{$unreadNotificationsNumber}}
            <span class="visually-hidden">{{__('unread messages')}}</span>
            </span>
    </button>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
         id="notification-dropdown" aria-labelledby="page-header-notifications-dropdown">
        <div class="dropdown-head bg-pattern rounded-top">
            <div class="p-3">
                <div class="row align-items-center">
                    <div class="col">
                        <h1 class="m-0 fs-16 text-info">{{__('Notifications')}}</h1>
                        @if($latests>0)
                            <div class="p-2 text-light float-end"><a
                                    href="{{route('notification_list',['locale'=>app()->getLocale()])}}">{{__('See all notifications')}}</a>
                            </div>
                            <div class="p-2 btn btn-primary float-end">
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
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @forelse($notifications as $notification)
            @include(\App\Helpers\NotificationHelper::getTemplate($notification) , ['notification' => $notification])
        @empty
            <div class="p-2 text-gray-500">{{__('No notifications')}}</div>
        @endforelse
    </div>
</div>
