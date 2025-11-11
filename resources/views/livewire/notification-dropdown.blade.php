<div class="dropdown topbar-head-dropdown ms-1 header-item" wire:poll.30s="loadNotifications()">
    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary position-relative"
            id="page-header-notifications-dropdown"
            data-bs-toggle="dropdown"
            aria-expanded="false"
            data-bs-auto-close="outside"
    >
        <i class='bx bx-bell fs-22'></i>
        @if($unreadNotificationsNumber > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger fs-10"
                  id="notif-counter">
                {{$unreadNotificationsNumber > 99 ? '99+' : $unreadNotificationsNumber}}
                <span class="visually-hidden">{{__('unread messages')}}</span>
            </span>
        @endif
    </button>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0 shadow-lg"
         id="notification-dropdown"
         aria-labelledby="page-header-notifications-dropdown"
         style="min-width: 360px;">

        <div class="dropdown-head bg-danger bg-gradient rounded-top">
            <div class="p-3">
                <div class="row align-items-center g-0">
                    <div class="col">
                        <h6 class="m-0 fs-15 fw-semibold text-white">
                            <i class='bx bx-bell me-1'></i>{{__('Notifications')}}
                        </h6>
                    </div>
                    @if($latests > 0)
                        <div class="col-auto">
                            <span class="badge bg-light text-primary">{{$unreadNotificationsNumber}}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if($latests > 0)
            <div class="px-3 py-2 bg-light border-bottom d-flex justify-content-between align-items-center">
                <a href="{{route('notification_list',['locale'=>app()->getLocale()])}}"
                   class="text-decoration-none text-primary fw-medium small">
                    <i class='bx bx-list-ul me-1'></i>{{__('See all notifications')}}
                </a>
                <button wire:click="markThemAllRead()"
                        class="btn btn-sm btn-outline-primary py-1 px-2 d-flex align-items-center gap-1"
                        type="button">
                    <i class='bx bx-check-double'></i>
                    <span>{{__('Mark them all as read')}}</span>
                    <div wire:loading wire:target="markThemAllRead" class="ms-1">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    </div>
                </button>
            </div>
        @endif

        <div class="overflow-y-auto overflow-x-hidden" style="max-height: 350px;">
            @forelse($notifications as $notification)
                @include(\App\Helpers\NotificationHelper::getTemplate($notification) , ['notification' => $notification])
            @empty
                <div class="p-4 text-center text-muted">
                    <i class='bx bx-bell-off fs-1 d-block mb-2 opacity-50'></i>
                    <p class="mb-0">{{__('No notifications')}}</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
