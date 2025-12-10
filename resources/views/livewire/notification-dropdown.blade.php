<div class="dropdown topbar-head-dropdown ms-1 header-item" wire:poll.30s="loadNotifications()">
    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary position-relative"
            id="page-header-notifications-dropdown"
            data-bs-toggle="dropdown"
            aria-expanded="false" wire:ignore.self
            data-bs-auto-close="outside"
    >
        <i class='bx bx-bell fs-22'></i>
        @if($unreadNotificationsNumber > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger fs-10 px-2"
                  id="notif-counter"
                  style="min-width: 20px; font-weight: 600;">
                {{$unreadNotificationsNumber > 99 ? '99+' : $unreadNotificationsNumber}}
                <span class="visually-hidden">{{__('unread messages')}}</span>
            </span>
        @endif
    </button>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0 shadow-lg border-0" wire:ignore.self
         id="notification-dropdown"
         aria-labelledby="page-header-notifications-dropdown"
         style="min-width: 380px; max-width: 420px;">

        <div class="dropdown-head bg-primary bg-gradient rounded-top">
            <div class="p-3 pb-2">
                <div class="row align-items-center g-0 mb-1">
                    <div class="col">
                        <h5 class="m-0 fs-16 fw-bold text-white d-flex align-items-center">
                            <i class='bx bx-bell me-2 fs-20'></i>
                            <span>{{__('Notifications')}}</span>
                        </h5>
                    </div>
                    @if($unreadNotificationsNumber > 0)
                        <div class="col-auto">
                            <span class="badge bg-soft-primary text-primary fw-bold px-2 py-1" style="font-size: 0.75rem;">
                                {{$unreadNotificationsNumber}} {{__('New')}}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if($latests > 0)
            <div class="px-3 py-2 bg-light bg-opacity-50 border-bottom">
                <div class="d-flex justify-content-center align-items-center">
                    <button wire:click="markThemAllRead()"
                            class="btn btn-sm btn-outline-primary py-1 px-3 d-flex align-items-center gap-1 fw-semibold w-100 justify-content-center"
                            type="button"
                            style="max-width: 250px;">
                        <i class='bx bx-check-double fs-16'></i>
                        <span>{{__('Mark them all as read')}}</span>
                        <span wire:loading wire:target="markThemAllRead" class="ms-1">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                </div>
            </div>
        @endif

        <div class="overflow-y-auto overflow-x-hidden" style="max-height: 345px;">
            @forelse($notifications as $notification)
                @include(\App\Helpers\NotificationHelper::getTemplate($notification) , ['notification' => $notification])
            @empty
                <div class="p-5 text-center text-muted">
                    <div class="mb-3">
                        <i class='bx bx-bell-off d-block mb-3 opacity-25' style="font-size: 4rem;"></i>
                    </div>
                    <h6 class="fw-bold mb-2 text-muted">{{__('No notifications')}}</h6>
                    <p class="small mb-0 opacity-75">{{__('You\'re all caught up!')}}</p>
                </div>
            @endforelse
        </div>
        @if($latests > 0)
            <div class="dropdown-footer border-top rounded-bottom">
                <div class="p-2 text-center">
                    <a href="{{route('notification_list',['locale'=>app()->getLocale()])}}"
                       class="text-decoration-none text-primary fw-bold small d-inline-flex align-items-center hover-underline">
                        <span>{{__('View all notifications')}}</span>
                        <i class='bx bx-chevron-right ms-1 fs-17'></i>
                    </a>
                </div>
            </div>
        @endif
    </div>

</div>
