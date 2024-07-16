<div>
    <header id="page-topbar">
        <div class="layout-width">
            <div class="navbar-header">
                <div class="d-flex">
                    <div class="navbar-brand-box horizontal-logo">
                        <a href="index" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="{{ Vite::asset('resources/images/logo-sm.png') }}" height="22">
                        </span>
                            <span class="logo-lg">
                            <img src="{{ Vite::asset('resources/images/logo-dark.png') }}" height="17">
                        </span>
                        </a>
                        <a href="index" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="{{ Vite::asset('resources/images/logo-sm.png') }}" height="22">
                        </span>
                            <span class="logo-lg">
                            <img src="{{ Vite::asset('resources/images/logo-light.png') }}" height="17">
                        </span>
                        </a>
                    </div>
                    <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger"
                            id="topnav-hamburger-icon">
                    <span class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                    </button>
                    <div class="ms-1 header-item d-none  d-xl-flex me-5 ">
                        <div class="d-flex align-items-end justify-content-between logoTopCash">
                            <a href="{{route('user_balance_cb',app()->getLocale())}}">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-info rounded fs-3">
                                       <i class="bx bx-dollar-circle text-info"></i>
                                    </span>
                                </div>
                            </a>
                        </div>
                        <div class="d-flex align-items-center logoTopCashLabel">
                            <div class="flex-grow-1 overflow-hidden">
                                <a href="{{route('user_balance_cb',app()->getLocale())}}">
                                    <p class="text-uppercase fw-medium     mb-0 ms-2">
                                        {{ __('Cash Balance') }}</p>
                                    <h5 class="fs-14 mb-0 ms-2">
                                        {{__('DPC')}}{{$solde->soldeCB}}
                                    </h5>
                                </a>
                            </div>
                            <div class="flex-shrink-0">
                            </div>
                        </div>
                    </div>
                    <div class="ms-1 header-item d-none d-xl-flex me-5">
                        <div class="d-flex align-items-end justify-content-between logoTopBFS">
                            <a href="{{route('user_balance_bfs',app()->getLocale())}}">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-success rounded fs-3">
                                        <i class="ri-shopping-cart-2-line text-success"></i>
                                    </span>
                                </div>
                            </a>
                        </div>
                        <div class="d-flex align-items-center logoTopBFSLabel">
                            <div class="flex-grow-1 overflow-hidden">
                                <a href="{{route('user_balance_bfs',app()->getLocale())}}">
                                    <p class="text-uppercase fw-medium     mb-0 ms-2">
                                        {{ __('Balance For Shopping') }}</p>
                                    <h5 class="text-success fs-14 mb-0  ms-2">
                                        {{__('DPC')}}{{$solde->soldeBFS}}
                                    </h5>
                                </a>
                            </div>
                            <div class="flex-shrink-0">
                            </div>
                        </div>
                    </div>
                    <div class="ms-1 header-item d-none d-xl-flex me-5">
                        <div class="d-flex align-items-end justify-content-between logoTopDB">
                            <a href="{{route('user_balance_db',app()->getLocale())}}">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-secondary rounded fs-3">
                                        <i class=" ri-coupon-4-line text-secondary"></i>
                                    </span>
                                </div>
                            </a>
                        </div>
                        <div class="d-flex align-items-center logoTopDBLabel">
                            <div class="flex-grow-1 overflow-hidden">
                                <a href="{{route('user_balance_db',app()->getLocale())}}">
                                    <p class="text-uppercase fw-medium     mb-0 ms-2">
                                        {{ __('Discounts Balance') }}</p>
                                    <h5 class="text-secondary fs-14 mb-0 ms-2">
                                        {{__('DPC')}}{{$solde->soldeDB}}
                                    </h5>
                                </a>
                            </div>
                            <div class="flex-shrink-0">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div class="dropdown ms-1 topbar-head-dropdown header-item">
                        <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary"
                                data-bs-toggle="dropdown" data-bs-auto-close="false" aria-expanded="true">
                            <img
                                src="{{ Vite::asset('resources/images/flags/'.config('app.available_locales')[app()->getLocale()]['flag'].'.svg') }}"
                                class="rounded" alt="Header Language"
                                height="20">
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            @foreach (config('app.available_locales') as  $locale => $value )
                                <a href="{{ route($currentRoute, ['locale'=> $locale ]) }} "
                                   class="dropdown-item notify-item language py-2  @if($locale==app()->getLocale()) active @endif" data-lang="{{$locale}}"
                                   title="{{ __('lang'.$locale)  }}" data-turbolinks="false">
                                    <img src="{{ Vite::asset('resources/images/flags/'.$value['flag'].'.svg') }}"
                                         alt="user-image" class="me-2 rounded" height="20">
                                    <span class="align-middle">{{ __('lang'.$locale)  }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div class="dropdown topbar-head-dropdown ms-1 header-item">
                        <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class='bx bx-category-alt fs-22'></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-lg p-0 dropdown-menu-end">
                            <div class="p-3 border-top-0 border-start-0 border-end-0 border-dashed border">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h6 class="m-0 fw-semibold fs-15"> {{__('Web Apps')}} </h6>
                                    </div>
                                    <div class="col-auto">
                                        <a href="" class="btn btn-sm btn-soft-info"> {{__('View All Apps')}}
                                            <i class="ri-arrow-right-s-line align-middle"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="p-2">
                                <div class="row g-0">
                                    <div class="col">
                                        <a class="dropdown-icon-item" href="{{route('coming_move')}}">
                                            <img src="{{Vite::asset('resources/images/Move2earn Icon.png')}}" alt="Move2earn">
                                            <span>Move2earn</span>
                                        </a>
                                    </div>
                                    <div class="col">
                                        <a class="dropdown-icon-item" href="{{route('coming_shop')}}">
                                            <img src="{{Vite::asset('resources/images/icon-shop.png')}}"
                                                 alt="Shop2earn">
                                            <span>Shop2earn</span>
                                        </a>
                                    </div>
                                    <div class="col">
                                        <a class="dropdown-icon-item" href="{{route('coming_learn')}}">
                                            <img src="{{Vite::asset('resources/images/icon-learn.png')}}" alt="Learn2earn">
                                            <span>Learn2earn</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ms-1 header-item d-none d-sm-flex">
                        <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary"
                                data-toggle="fullscreen">
                            <i class='bx bx-fullscreen fs-22'></i>
                        </button>
                    </div>
                    <div class="ms-1 header-item d-sm-flex">
                        <button id="btndark" type="button"
                                class="btn btn-icon btn-topbar btn-ghost-secondary light-dark-mode">
                            <i class='bx bx-moon fs-22'></i>
                        </button>
                    </div>
                    <div class="dropdown topbar-head-dropdown ms-1 header-item">
                        <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary"
                                id="page-header-notifications-dropdown"
                                data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                            <i class='bx bx-bell fs-22'></i>
                            <span
                                class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-danger" id="notif-counter">{{$count}}
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
                                        <div class="col-auto dropdown-tabs">
                                            <span class="badge badge-soft-light fs-13">
                                                {{__('New')}}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="px-2 pt-2">
                                    <ul class="nav nav-pills dropdown-tabs nav-tabs-custom" data-dropdown-tabs="true"
                                        id="notificationItemsTab" role="tablist">
                                        <li class="nav-item waves-effect waves-light">
                                            <a class="nav-link active py-1 px-2" data-bs-toggle="pill"
                                               data-bs-target="#all-noti-tab" role="tab"
                                               aria-selected="true">
                                                {{__('All')}} ( {{$count}} )
                                            </a>
                                        </li>
                                        <li class="nav-item waves-effect waves-light">
                                            <a class="nav-link py-1 px-2" data-bs-toggle="pill"
                                               data-bs-target="#messages-tab"
                                               href="#messages-tab" role="tab"
                                               aria-selected="false">
                                                {{__('Messages')}}
                                            </a>
                                        </li>
                                        <li class="nav-item waves-effect waves-light">
                                            <a class="nav-link py-1 px-2" data-bs-toggle="pill"
                                               data-bs-target="#alert-tab"
                                               href="#alert-tab" role="tab"
                                               aria-selected="false">
                                                {{__('Alerts')}}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="tab-content" id="notificationItemsTabContent">
                                <div class="tab-pane fade show active py-2 ps-2" id="all-noti-tab" role="tabpanel">
                                    @foreach($notifications as $notification)
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
                                                    </div>
                                                    <h6 class="mt-0 mb-2 fs-13 lh-base">
                                                        {{ formatNotification($notification) }}
                                                    </h6>
                                                    <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                                                        <span><i class="mdi mdi-clock-outline"></i> {{time_ago($notification->created_at)}}</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="tab-pane fade py-2 ps-2" id="messages-tab" role="tabpanel"
                                     aria-labelledby="messages-tab">
                                </div>
                                <div class="tab-pane fade py-2 ps-2" id="alert-tab" role="tabpanel"
                                     aria-labelledby="alert-tab">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown ms-sm-3 header-item topbar-user">
                        <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <img class="rounded-circle header-profile-user" alt="Header Avatar"
                                 src="@if (file_exists('uploads/profiles/profile-image-' . $user->idUser . '.png')) {{ URL::asset('uploads/profiles/profile-image-'.$user->idUser.'.png') }}@else{{ URL::asset('uploads/profiles/default.png') }} @endif">
                            <span class="text-center ms-xl-2">
                                <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">
                                    {{getUserDisplayedName()}} </span>
                                 <span
                                     class="d-none d-xl-block badge bg-light @if($user->status==1) text-success  @else text-muted @endif mb-0">
                                        <span class="mb-5">{{__($userRole)}}</span>
                                        @if($user->status==1)
                                         <i class="mdi mdi-24px mdi-account-check green validated-user"></i>
                                     @endif
                                 </span>
                            </span>
                        </span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="{{route('account',app()->getLocale() )}}">
                                <img class="rounded-circle header-profile-user"
                                     src="@if (file_exists('uploads/profiles/profile-image-' . $user->idUser . '.png')) {{ URL::asset('uploads/profiles/profile-image-'.$user->idUser.'.png') }}@else{{ URL::asset('uploads/profiles/default.png') }} @endif">
                                <span
                                    class="align-middle">{{ __('Account') }}</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{route('user_balance_cb',app()->getLocale())}}"><i
                                    class="mdi mdi-wallet text-muted fs-16 align-middle me-1"></i> <span
                                    class=""> {{ __('Cash Balance') }} : <b>  {{__('DPC')}}  {{$solde->soldeCB}}</b>
                                </span>
                            </a>
                            <a class="dropdown-item" wire:click="logout">
                                <i class="bx bx-power-off font-size-16 align-middle me-1"></i>
                                <span key="t-logout">{{ __('Logout') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <script>
        window.addEventListener('updateNotifications', event => {
            $("#page-header-notifications-dropdown").click();
        })
    </script>
</div>
