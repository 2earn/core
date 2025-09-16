<div class="dropdown topbar-head-dropdown ms-1 header-item">
        <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary"
                id="page-header-notifications-dropdown"
                data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
            <i class='bx bx-bell fs-22'></i>
            <span
                class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-danger"
                id="notif-counter">{{$unreadNotificationsNumber}}
                                <span class="visually-hidden">{{__('unread messages')}}</span>
                            </span>
        </button>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
         id="notification-dropdown" aria-labelledby="page-header-notifications-dropdown">
        <div class="dropdown-head bg-primary bg-pattern rounded-top">
            <div class="p-3">
                <div class="row align-items-center">
                    <div class="col">
                        <h1 class="m-0 fs-16 text-white">{{__('Notifications')}}</h1>
                    </div>
                </div>
            </div>
        </div>
        @forelse($notifications as $notification)
            <div
                class="text-reset notification-item d-block dropdown-item position-relative"
                id="{{$notification->id}}" title="{{$notification->id}}">
                <div class="d-flex">
                    <div class="avatar-xs me-3 flex-shrink-0">
                                                <span
                                                    class="avatar-title bg-danger-subtle text-danger rounded-circle fs-16">
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
                        <h6 class="mt-0 mb-2 fs-13 lh-base">
                            {{ \App\Helpers\NotificationHelper::format($notification) }}
                        </h6>
                        <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                                <span><i
                                        class="mdi mdi-clock-outline"></i> {{time_ago($notification->created_at)}}</span>
                        </p>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-2 text-gray-500">{{__('No notifications')}}</div>
        @endforelse
        </div>
    </div>
