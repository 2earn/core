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
                    <div class="ms-1 header-item d-none  d-xl-flex me-5">
                        <div class="d-flex align-items-end justify-content-between logoTopCash"
                             title="{{__('Soldes calculated at')}} : {{Carbon\Carbon::now()->toDateTimeString()}}">
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
                                        {{__('DPC')}} {{formatSolde(intval($cash),0)}}
                                    </h5>
                                </a>
                            </div>
                            <div class="flex-shrink-0">
                            </div>
                        </div>
                    </div>
                    <div class="ms-1 header-item d-none d-xl-flex me-5"
                         title="{{__('Soldes calculated at')}} : {{Carbon\Carbon::now()->toDateTimeString()}}">
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
                                        {{__('DPC')}}{{formatSolde(intval($bfs),0)}}
                                    </h5>
                                </a>
                            </div>
                            <div class="flex-shrink-0">
                            </div>
                        </div>
                    </div>
                    <div class="ms-1 header-item d-none d-xl-flex me-5"
                         title="{{__('Soldes calculated at')}} : {{Carbon\Carbon::now()->toDateTimeString()}}">
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
                                        {{__('DPC')}}{{formatSolde(intval($db),0)}}
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
                                @if(strpos(Request::url(), '/'.app()->getLocale().'/'))
                                    <a href="{{str_replace('/'.app()->getLocale().'/', '/'.$value['name'].'/', Request::url())}}"
                                       class="dropdown-item notify-item language py-2  @if($locale==app()->getLocale()) active @endif"
                                       data-lang="{{$locale}}"
                                       title="{{ __('lang'.$locale)  }}" data-turbolinks="false">
                                        <img src="{{ Vite::asset('resources/images/flags/'.$value['flag'].'.svg') }}"
                                             alt="user-image" class="me-2 rounded" height="20">
                                        <span class="align-middle">{{ __('lang'.$locale)  }}</span>
                                    </a>
                                @else
                                    <a href="{{str_replace('/'.app()->getLocale(), '/'.$value['name'].'/', Request::url())}}"
                                       class="dropdown-item notify-item language py-2  @if($locale==app()->getLocale()) active @endif"
                                       data-lang="{{$locale}}"
                                       title="{{ __('lang'.$locale)  }}" data-turbolinks="false">
                                        <img src="{{ Vite::asset('resources/images/flags/'.$value['flag'].'.svg') }}"
                                             alt="user-image" class="me-2 rounded" height="20">
                                        <span class="align-middle">{{ __('lang'.$locale)  }}</span>
                                    </a>
                                @endif

                            @endforeach
                        </div>
                    </div>
                    <div class="dropdown topbar-head-dropdown ms-1 header-item">
                        <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class='ri-currency-fill fs-22'></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-lg p-0 dropdown-menu-end">
                            <div class="p-3 border-top-0 border-start-0 border-end-0 border-dashed border">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h6 class="m-0 fw-semibold fs-15"> {{__('Business sectors')}} </h6>
                                    </div>
                                    <div class="col-auto">
                                        <a href="{{route('business_sector_index',['locale'=> app()->getLocale()])}}"
                                           class="btn btn-sm btn-soft-info"> {{__('View All Business sectors')}}
                                            <i class="ri-currency-fill align-middle"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="p-2">
                                <div class="row g-0">
                                    @foreach($sectors as $sector)
                                        <div class="col">
                                            <a class="dropdown-icon-item"
                                               href="{{route('business_sector_show',['locale'=>app()->getLocale(),'id'=>$sector->id] )}}">
                                                @if (!$sector->logoImage)
                                                    <img
                                                        src="{{Vite::asset(\App\Models\BusinessSector::DEFAULT_IMAGE_TYPE_LOGO)}}"
                                                        alt="Move2earn">
                                                @else
                                                    <img src="{{ asset('uploads/' . $sector->logoImage->url) }}"
                                                         alt="Move2earn">
                                                @endif
                                                <span>{{$sector->name}}</span>
                                            </a>
                                        </div>
                                    @endforeach
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
                        <button type="button" class="btn btn-icon btn-topbar material-shadow-none btn-ghost-secondary rounded-circle show" id="page-header-cart-dropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="true">
                            <i class="bx bx-shopping-bag fs-22"></i>
                            <span class="position-absolute topbar-badge cartitem-badge fs-10 translate-middle badge rounded-pill bg-info">5</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-xl dropdown-menu-end p-0 dropdown-menu-cart show" aria-labelledby="page-header-cart-dropdown" style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate(0px, 58px);" data-popper-placement="bottom-end">
                            <div class="p-3 border-top-0 border-start-0 border-end-0 border-dashed border">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h6 class="m-0 fs-16 fw-semibold"> My Cart</h6>
                                    </div>
                                    <div class="col-auto">
                                    <span class="badge bg-warning-subtle text-warning fs-13"><span class="cartitem-badge">5</span>
                                        items</span>
                                    </div>
                                </div>
                            </div>
                            <div data-simplebar="init" style="max-height: 300px;" class="simplebar-scrollable-y">
                                <div class="simplebar-wrapper" style="margin: 0px;">
                                    <div class="simplebar-height-auto-observer-wrapper">
                                        <div class="simplebar-height-auto-observer">

                                        </div>
                                    </div>
                                    <div class="simplebar-mask">
                                        <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                                            <div class="simplebar-content-wrapper" tabindex="0" role="region" aria-label="scrollable content" style="height: auto; overflow: hidden scroll;">
                                                <div class="simplebar-content" style="padding: 0px;">
                                                    <div class="p-2">
                                                        <div class="text-center empty-cart" id="empty-cart" style="display: none;">
                                                            <div class="avatar-md mx-auto my-3">
                                                                <div class="avatar-title bg-info-subtle text-info fs-36 rounded-circle">
                                                                    <i class="bx bx-cart"></i>
                                                                </div>
                                                            </div>
                                                            <h5 class="mb-3">Your Cart is Empty!</h5>
                                                            <a href="apps-ecommerce-products.html" class="btn btn-success w-md mb-3">Shop Now</a>
                                                        </div>
                                                        <div class="d-block dropdown-item dropdown-item-cart text-wrap px-3 py-2">
                                                            <div class="d-flex align-items-center">
                                                                <img src="assets/images/products/img-1.png" class="me-3 rounded-circle avatar-sm p-2 bg-light" alt="user-pic">
                                                                <div class="flex-grow-1">
                                                                    <h6 class="mt-0 mb-1 fs-14">
                                                                        <a href="apps-ecommerce-product-details.html" class="text-reset">Branded
                                                                            T-Shirts</a>
                                                                    </h6>
                                                                    <p class="mb-0 fs-12 text-muted">
                                                                        Quantity: <span>10 x $32</span>
                                                                    </p>
                                                                </div>
                                                                <div class="px-2">
                                                                    <h5 class="m-0 fw-normal">$<span class="cart-item-price">320</span></h5>
                                                                </div>
                                                                <div class="ps-2">
                                                                    <button type="button" class="btn btn-icon btn-sm btn-ghost-secondary remove-item-btn"><i class="ri-close-fill fs-16"></i></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="d-block dropdown-item dropdown-item-cart text-wrap px-3 py-2">
                                                            <div class="d-flex align-items-center">
                                                                <img src="assets/images/products/img-2.png" class="me-3 rounded-circle avatar-sm p-2 bg-light" alt="user-pic">
                                                                <div class="flex-grow-1">
                                                                    <h6 class="mt-0 mb-1 fs-14">
                                                                        <a href="apps-ecommerce-product-details.html" class="text-reset">Bentwood Chair</a>
                                                                    </h6>
                                                                    <p class="mb-0 fs-12 text-muted">
                                                                        Quantity: <span>5 x $18</span>
                                                                    </p>
                                                                </div>
                                                                <div class="px-2">
                                                                    <h5 class="m-0 fw-normal">$<span class="cart-item-price">89</span></h5>
                                                                </div>
                                                                <div class="ps-2">
                                                                    <button type="button" class="btn btn-icon btn-sm btn-ghost-secondary remove-item-btn"><i class="ri-close-fill fs-16"></i></button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="d-block dropdown-item dropdown-item-cart text-wrap px-3 py-2">
                                                            <div class="d-flex align-items-center">
                                                                <img src="assets/images/products/img-3.png" class="me-3 rounded-circle avatar-sm p-2 bg-light" alt="user-pic">
                                                                <div class="flex-grow-1">
                                                                    <h6 class="mt-0 mb-1 fs-14">
                                                                        <a href="apps-ecommerce-product-details.html" class="text-reset">
                                                                            Borosil Paper Cup</a>
                                                                    </h6>
                                                                    <p class="mb-0 fs-12 text-muted">
                                                                        Quantity: <span>3 x $250</span>
                                                                    </p>
                                                                </div>
                                                                <div class="px-2">
                                                                    <h5 class="m-0 fw-normal">$<span class="cart-item-price">750</span></h5>
                                                                </div>
                                                                <div class="ps-2">
                                                                    <button type="button" class="btn btn-icon btn-sm btn-ghost-secondary remove-item-btn"><i class="ri-close-fill fs-16"></i></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="d-block dropdown-item dropdown-item-cart text-wrap px-3 py-2">
                                                            <div class="d-flex align-items-center">
                                                                <img src="assets/images/products/img-6.png" class="me-3 rounded-circle avatar-sm p-2 bg-light" alt="user-pic">
                                                                <div class="flex-grow-1">
                                                                    <h6 class="mt-0 mb-1 fs-14">
                                                                        <a href="apps-ecommerce-product-details.html" class="text-reset">Gray
                                                                            Styled T-Shirt</a>
                                                                    </h6>
                                                                    <p class="mb-0 fs-12 text-muted">
                                                                        Quantity: <span>1 x $1250</span>
                                                                    </p>
                                                                </div>
                                                                <div class="px-2">
                                                                    <h5 class="m-0 fw-normal">$ <span class="cart-item-price">1250</span></h5>
                                                                </div>
                                                                <div class="ps-2">
                                                                    <button type="button" class="btn btn-icon btn-sm btn-ghost-secondary remove-item-btn"><i class="ri-close-fill fs-16"></i></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="d-block dropdown-item dropdown-item-cart text-wrap px-3 py-2">
                                                            <div class="d-flex align-items-center">
                                                                <img src="assets/images/products/img-5.png" class="me-3 rounded-circle avatar-sm p-2 bg-light" alt="user-pic">
                                                                <div class="flex-grow-1">
                                                                    <h6 class="mt-0 mb-1 fs-14">
                                                                        <a href="apps-ecommerce-product-details.html" class="text-reset">Stillbird Helmet</a>
                                                                    </h6>
                                                                    <p class="mb-0 fs-12 text-muted">
                                                                        Quantity: <span>2 x $495</span>
                                                                    </p>
                                                                </div>
                                                                <div class="px-2">
                                                                    <h5 class="m-0 fw-normal">$<span class="cart-item-price">990</span></h5>
                                                                </div>
                                                                <div class="ps-2">
                                                                    <button type="button" class="btn btn-icon btn-sm btn-ghost-secondary remove-item-btn"><i class="ri-close-fill fs-16"></i></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="simplebar-placeholder" style="width: 420px; height: 336px;"></div>
                                </div>
                                <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                                    <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
                                </div>
                                <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
                                    <div class="simplebar-scrollbar" style="height: 267px; display: block; transform: translate3d(0px, 0px, 0px);">
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="p-3 border-bottom-0 border-start-0 border-end-0 border-dashed border" id="checkout-elem" style="display: block;">
                                <div class="d-flex justify-content-between align-items-center pb-3">
                                    <h5 class="m-0 text-muted">Total:</h5>
                                    <div class="px-2">
                                        <h5 class="m-0" id="cart-item-total">$3399.00</h5>
                                    </div>
                                </div>

                                <a href="apps-ecommerce-checkout.html" class="btn btn-success text-center w-100">
                                    Checkout
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown topbar-head-dropdown ms-1 header-item">
                        <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary"
                                id="page-header-notifications-dropdown"
                                data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                            <i class='bx bx-bell fs-22'></i>
                            <span
                                class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-danger"
                                id="notif-counter">{{$count}}
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
                                 src="{{ URL::asset($userProfileImage) }}">
                            <span class="text-center ms-xl-2">
                                <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text"
                                      title="{{$userStatus}}">
                                    {{getUserDisplayedName()}} </span>
                                 <span
                                     class="d-none d-xl-block badge bg-light @if($user->status==1) text-success  @else text-muted @endif mb-0">
                                        <span class="mb-5">{{__($userRole)}}</span>
                                        @if($userStatus==2)
                                         <i class="mdi mdi-24px mdi-account-check text-success validated-user"
                                            title="{{__('National identified')}}"></i>
                                     @elseif($userStatus==1)
                                         <i class="mdi mdi-24px mdi-account-alert text-warning validated-user"
                                            title="{{__('National identification request in process')}}"></i>
                                     @elseif($userStatus==5)
                                         <i class="mdi mdi-24px mdi-account-alert text-warning validated-user"
                                            title="{{__('International identification request in process')}}"></i>
                                     @elseif($userStatus==6)
                                         <i class="mdi mdi-24px mdi-account-alert text-warning validated-user"
                                            title="{{__('Global identification request in process')}}"></i>
                                     @elseif($userStatus==4)
                                         <i class="mdi mdi-24px mdi-account-check text-info validated-user"
                                            title="{{__('International identified')}}"></i>
                                     @endif
                                 </span>
                            </span>
                        </span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="{{route('account',app()->getLocale() )}}">
                                <img class="rounded-circle header-profile-user"
                                     src="{{ URL::asset($userProfileImage) }}">
                                <span
                                    class="align-middle">{{ __('Account') }}</span>
                            </a>
                            <div class="dropdown-divider">
                            </div>
                            <a class="dropdown-item" href="{{route('user_balance_cb',app()->getLocale())}}"><i
                                    class="mdi mdi-wallet text-muted fs-16 align-middle me-1"></i> <span>  <b> {{ __('Cash Balance') }} : {{__('DPC')}}  {{formatSolde($cash,3)}}</b>
                                </span>
                            </a>
                            <div class="dropdown-divider">
                            </div>
                            <a class="dropdown-item" href="{{route('orders_previous',app()->getLocale())}}">
                                <span class=""> {{ __('Previous orders') }}</span>
                            </a>
                            <a class="dropdown-item" href="{{route('notification_history',app()->getLocale())}}">
                                <span class=""> {{ __('Notification history') }}</span>
                            </a>
                            <a class="dropdown-item" href="{{route('notification_settings',app()->getLocale())}}">
                                <span class=""> {{ __('Notification Settings') }}</span>
                            </a>
                            <a class="dropdown-item" href="{{route('faq_index',app()->getLocale())}}">
                                <span class=""> {{ __('Frequently asked questions') }}</span>
                            </a>
                            <div class="dropdown-divider">
                            </div>
                            <a class="dropdown-item " wire:click="logout">
                                <span class="font-size-16 me-1 text-dark" key="t-logout">
                                    <i class="bx bx-power-off"></i>
                                    {{ __('Logout') }}
                                </span>
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
