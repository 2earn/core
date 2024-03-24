<div>
    <header id="page-topbar">
        <div class="layout-width">
            <div class="navbar-header">
                <div class="d-flex">
                    <!-- LOGO -->
                    <div class="navbar-brand-box horizontal-logo">
                        <a href="index" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="{{ URL::asset('assets/images/logo-sm.png') }}" alt="" height="22">
                        </span>
                            <span class="logo-lg">
                            <img src="{{ URL::asset('assets/images/logo-dark.png') }}" alt="" height="17">
                        </span>
                        </a>
                        <a href="index" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="{{ URL::asset('assets/images/logo-sm.png') }}" alt="" height="22">
                        </span>
                            <span class="logo-lg">
                            <img src="{{ URL::asset('assets/images/logo-light.png') }}" alt="" height="17">
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
                                        {{__('DPC')}}  <?php echo $solde->soldeCB /1 ?>
                                    </h5></a>
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

                                        {{__('DPC')}} <?php echo $solde->soldeBFS /1 ?>
                                    </h5></a>
                            </div>
                            <div class="flex-shrink-0">

                            </div>
                        </div>


                    </div>
                    <div class="ms-1 header-item d-none d-xl-flex me-5">
                        <div class="d-flex align-items-end justify-content-between logoTopDB">
                            <a href="{{route('user_balance_db',app()->getLocale())}}">
                                <div class="avatar-sm flex-shrink-0">
                                    <span
                                          class="avatar-title bg-soft-secondary rounded fs-3">
                                        <i
                                           class=" ri-coupon-4-line text-secondary"></i>
                                    </span>
                                </div>
                            </a>
                        </div>
                        <div class="d-flex align-items-center logoTopDBLabel">
                            <div class="flex-grow-1 overflow-hidden">
                                <a   href="{{route('user_balance_db',app()->getLocale())}}">
                                    <p
                                       class="text-uppercase fw-medium     mb-0 ms-2">
                                        {{ __('Discounts Balance') }}</p>
                                    <h5   class="text-secondary fs-14 mb-0 ms-2">

                                        {{__('DPC')}} </span> <?php  echo $solde->soldeDB /1 ?>
                                    </h5></a>
                            </div>
                            <div class="flex-shrink-0">

                            </div>
                        </div>


                    </div>
                </div>
                <div class="d-flex align-items-center">


                    <div class="dropdown ms-1 topbar-head-dropdown header-item">
                        <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img
                                src="{{ URL::asset('/assets/images/flags/'.config('app.available_locales')[app()->getLocale()]['flag'].'.svg') }}"
                                class="rounded" alt="Header Language"
                                height="20">

                        </button>
                        @php
                            $var = \Illuminate\Support\Facades\Route::currentRouteName() ;
                        @endphp
                        <div class="dropdown-menu dropdown-menu-end">
                            @foreach (config('app.available_locales') as  $locale => $value )
                                <a href="{{ route($var, ['locale'=> $locale ]) }} "
                                   class="dropdown-item notify-item language py-2" data-lang="en"
                                   title="{{ __('lang'.$locale)  }}" data-turbolinks="false">
                                    <img src="{{ URL::asset('assets/images/flags/'.$value['flag'].'.svg') }}"
                                         alt="user-image" class="me-2 rounded" height="20">
                                    <span class="align-middle">{{ __('lang'.$locale)  }}</span>
                                </a>

                            @endforeach

                        </div>
                    </div>

                    <div class="dropdown topbar-head-dropdown ms-1 header-item">
                        <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class='bx bx-category-alt fs-22'></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-lg p-0 dropdown-menu-end">
                            <div class="p-3 border-top-0 border-start-0 border-end-0 border-dashed border">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h6 class="m-0 fw-semibold fs-15"> Web Apps </h6>
                                    </div>
                                    <div class="col-auto">
                                        <a href="" class="btn btn-sm btn-soft-info"> View All Apps
                                            <i class="ri-arrow-right-s-line align-middle"></i></a>
                                    </div>
                                </div>
                            </div>

                            <div class="p-2">
                                <div class="row g-0">
                                    <div class="col">
                                        <a class="dropdown-icon-item" href="{{route('ComingMove')}}">
                                            <img src="{{asset('assets/images/Move2earn Icon.png')}}" alt="Move2earn">
                                            <span>Move2earn</span>
                                        </a>
                                    </div>
                                    <div class="col">
                                        <a class="dropdown-icon-item" href="{{route('ComingShop')}}">
                                            <img src="{{asset('assets/images/icon-shop.png')}}"
                                                 alt="Shop2earn">
                                            <span>Shop2earn</span>
                                        </a>
                                    </div>
                                    <div class="col">
                                        <a class="dropdown-icon-item" href="{{route('ComingLearn')}}">
                                            <img src="{{asset('assets/images/icon-learn.png')}}" alt="Learn2earn">
                                            <span>Learn2earn</span>
                                        </a>
                                    </div>
                                </div>

                                {{--                            <div class="row g-0">--}}
                                {{--                                <div class="col">--}}
                                {{--                                    <a class="dropdown-icon-item" href="#!">--}}
                                {{--                                        <img src="{{ URL::asset('assets/images/brands/dropbox.png') }}" alt="dropbox">--}}
                                {{--                                        <span>Dropbox</span>--}}
                                {{--                                    </a>--}}
                                {{--                                </div>--}}
                                {{--                                <div class="col">--}}
                                {{--                                    <a class="dropdown-icon-item" href="#!">--}}
                                {{--                                        <img src="{{ URL::asset('assets/images/brands/mail_chimp.png') }}"--}}
                                {{--                                             alt="mail_chimp">--}}
                                {{--                                        <span>Mail Chimp</span>--}}
                                {{--                                    </a>--}}
                                {{--                                </div>--}}
                                {{--                                <div class="col">--}}
                                {{--                                    <a class="dropdown-icon-item" href="#!">--}}
                                {{--                                        <img src="{{ URL::asset('assets/images/brands/slack.png') }}" alt="slack">--}}
                                {{--                                        <span>Slack</span>--}}
                                {{--                                    </a>--}}
                                {{--                                </div>--}}
                                {{--                            </div>--}}
                            </div>
                        </div>
                    </div>

                    {{--                <div class="dropdown topbar-head-dropdown ms-1 header-item">--}}
                    {{--                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"--}}
                    {{--                            id="page-header-cart-dropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside"--}}
                    {{--                            aria-haspopup="true" aria-expanded="false">--}}
                    {{--                        <i class='bx bx-shopping-bag fs-22'></i>--}}
                    {{--                        <span--}}
                    {{--                            class="position-absolute topbar-badge cartitem-badge fs-10 translate-middle badge rounded-pill bg-info">5</span>--}}
                    {{--                    </button>--}}
                    {{--                    <div class="dropdown-menu dropdown-menu-xl dropdown-menu-end p-0 dropdown-menu-cart"--}}
                    {{--                         aria-labelledby="page-header-cart-dropdown">--}}
                    {{--                        <div class="p-3 border-top-0 border-start-0 border-end-0 border-dashed border">--}}
                    {{--                            <div class="row align-items-center">--}}
                    {{--                                <div class="col">--}}
                    {{--                                    <h6 class="m-0 fs-16 fw-semibold"> My Cart</h6>--}}
                    {{--                                </div>--}}
                    {{--                                <div class="col-auto">--}}
                    {{--                                    <span class="badge badge-soft-warning fs-13"><span class="cartitem-badge">7</span>--}}
                    {{--                                        items</span>--}}
                    {{--                                </div>--}}
                    {{--                            </div>--}}
                    {{--                        </div>--}}
                    {{--                        <div data-simplebar style="max-height: 300px;">--}}
                    {{--                            <div class="p-2">--}}
                    {{--                                <div class="text-center empty-cart" id="empty-cart">--}}
                    {{--                                    <div class="avatar-md mx-auto my-3">--}}
                    {{--                                        <div class="avatar-title bg-soft-info text-info fs-36 rounded-circle">--}}
                    {{--                                            <i class='bx bx-cart'></i>--}}
                    {{--                                        </div>--}}
                    {{--                                    </div>--}}
                    {{--                                    <h5 class="mb-3">Your Cart is Empty!</h5>--}}
                    {{--                                    <a href="#" class="btn btn-success w-md mb-3">Shop Now</a>--}}
                    {{--                                </div>--}}
                    {{--                                <div class="d-block dropdown-item dropdown-item-cart text-wrap px-3 py-2">--}}
                    {{--                                    <div class="d-flex align-items-center">--}}
                    {{--                                        <img src="{{ URL::asset('assets/images/products/img-1.png') }}"--}}
                    {{--                                             class="me-3 rounded-circle avatar-sm p-2 bg-light" alt="user-pic">--}}
                    {{--                                        <div class="flex-1">--}}
                    {{--                                            <h6 class="mt-0 mb-1 fs-14">--}}
                    {{--                                                <a href="apps-ecommerce-product-details" class="text-reset">Branded--}}
                    {{--                                                    T-Shirts</a>--}}
                    {{--                                            </h6>--}}
                    {{--                                            <p class="mb-0 fs-12 text-muted">--}}
                    {{--                                                Quantity: <span>10 x $32</span>--}}
                    {{--                                            </p>--}}
                    {{--                                        </div>--}}
                    {{--                                        <div class="px-2">--}}
                    {{--                                            <h5 class="m-0 fw-normal">$<span class="cart-item-price">320</span></h5>--}}
                    {{--                                        </div>--}}
                    {{--                                        <div class="ps-2">--}}
                    {{--                                            <button type="button"--}}
                    {{--                                                    class="btn btn-icon btn-sm btn-ghost-secondary remove-item-btn"><i--}}
                    {{--                                                    class="ri-close-fill fs-16"></i></button>--}}
                    {{--                                        </div>--}}
                    {{--                                    </div>--}}
                    {{--                                </div>--}}

                    {{--                                <div class="d-block dropdown-item dropdown-item-cart text-wrap px-3 py-2">--}}
                    {{--                                    <div class="d-flex align-items-center">--}}
                    {{--                                        <img src="{{ URL::asset('assets/images/products/img-2.png') }}"--}}
                    {{--                                             class="me-3 rounded-circle avatar-sm p-2 bg-light" alt="user-pic">--}}
                    {{--                                        <div class="flex-1">--}}
                    {{--                                            <h6 class="mt-0 mb-1 fs-14">--}}
                    {{--                                                <a href="apps-ecommerce-product-details"--}}
                    {{--                                                   class="text-reset">Bentwood Chair</a>--}}
                    {{--                                            </h6>--}}
                    {{--                                            <p class="mb-0 fs-12 text-muted">--}}
                    {{--                                                Quantity: <span>5 x $18</span>--}}
                    {{--                                            </p>--}}
                    {{--                                        </div>--}}
                    {{--                                        <div class="px-2">--}}
                    {{--                                            <h5 class="m-0 fw-normal">$<span class="cart-item-price">89</span></h5>--}}
                    {{--                                        </div>--}}
                    {{--                                        <div class="ps-2">--}}
                    {{--                                            <button type="button"--}}
                    {{--                                                    class="btn btn-icon btn-sm btn-ghost-secondary remove-item-btn"><i--}}
                    {{--                                                    class="ri-close-fill fs-16"></i></button>--}}
                    {{--                                        </div>--}}
                    {{--                                    </div>--}}
                    {{--                                </div>--}}

                    {{--                                <div class="d-block dropdown-item dropdown-item-cart text-wrap px-3 py-2">--}}
                    {{--                                    <div class="d-flex align-items-center">--}}
                    {{--                                        <img src="{{ URL::asset('assets/images/products/img-3.png') }}"--}}
                    {{--                                             class="me-3 rounded-circle avatar-sm p-2 bg-light" alt="user-pic">--}}
                    {{--                                        <div class="flex-1">--}}
                    {{--                                            <h6 class="mt-0 mb-1 fs-14">--}}
                    {{--                                                <a href="apps-ecommerce-product-details" class="text-reset">--}}
                    {{--                                                    Borosil Paper Cup</a>--}}
                    {{--                                            </h6>--}}
                    {{--                                            <p class="mb-0 fs-12 text-muted">--}}
                    {{--                                                Quantity: <span>3 x $250</span>--}}
                    {{--                                            </p>--}}
                    {{--                                        </div>--}}
                    {{--                                        <div class="px-2">--}}
                    {{--                                            <h5 class="m-0 fw-normal">$<span class="cart-item-price">750</span></h5>--}}
                    {{--                                        </div>--}}
                    {{--                                        <div class="ps-2">--}}
                    {{--                                            <button type="button"--}}
                    {{--                                                    class="btn btn-icon btn-sm btn-ghost-secondary remove-item-btn"><i--}}
                    {{--                                                    class="ri-close-fill fs-16"></i></button>--}}
                    {{--                                        </div>--}}
                    {{--                                    </div>--}}
                    {{--                                </div>--}}

                    {{--                                <div class="d-block dropdown-item dropdown-item-cart text-wrap px-3 py-2">--}}
                    {{--                                    <div class="d-flex align-items-center">--}}
                    {{--                                        <img src="{{ URL::asset('assets/images/products/img-6.png') }}"--}}
                    {{--                                             class="me-3 rounded-circle avatar-sm p-2 bg-light" alt="user-pic">--}}
                    {{--                                        <div class="flex-1">--}}
                    {{--                                            <h6 class="mt-0 mb-1 fs-14">--}}
                    {{--                                                <a href="apps-ecommerce-product-details" class="text-reset">Gray--}}
                    {{--                                                    Styled T-Shirt</a>--}}
                    {{--                                            </h6>--}}
                    {{--                                            <p class="mb-0 fs-12 text-muted">--}}
                    {{--                                                Quantity: <span>1 x $1250</span>--}}
                    {{--                                            </p>--}}
                    {{--                                        </div>--}}
                    {{--                                        <div class="px-2">--}}
                    {{--                                            <h5 class="m-0 fw-normal">$ <span class="cart-item-price">1250</span></h5>--}}
                    {{--                                        </div>--}}
                    {{--                                        <div class="ps-2">--}}
                    {{--                                            <button type="button"--}}
                    {{--                                                    class="btn btn-icon btn-sm btn-ghost-secondary remove-item-btn"><i--}}
                    {{--                                                    class="ri-close-fill fs-16"></i></button>--}}
                    {{--                                        </div>--}}
                    {{--                                    </div>--}}
                    {{--                                </div>--}}

                    {{--                                <div class="d-block dropdown-item dropdown-item-cart text-wrap px-3 py-2">--}}
                    {{--                                    <div class="d-flex align-items-center">--}}
                    {{--                                        <img src="{{ URL::asset('assets/images/products/img-5.png') }}"--}}
                    {{--                                             class="me-3 rounded-circle avatar-sm p-2 bg-light" alt="user-pic">--}}
                    {{--                                        <div class="flex-1">--}}
                    {{--                                            <h6 class="mt-0 mb-1 fs-14">--}}
                    {{--                                                <a href="apps-ecommerce-product-details"--}}
                    {{--                                                   class="text-reset">Stillbird Helmet</a>--}}
                    {{--                                            </h6>--}}
                    {{--                                            <p class="mb-0 fs-12 text-muted">--}}
                    {{--                                                Quantity: <span>2 x $495</span>--}}
                    {{--                                            </p>--}}
                    {{--                                        </div>--}}
                    {{--                                        <div class="px-2">--}}
                    {{--                                            <h5 class="m-0 fw-normal">$<span class="cart-item-price">990</span></h5>--}}
                    {{--                                        </div>--}}
                    {{--                                        <div class="ps-2">--}}
                    {{--                                            <button type="button"--}}
                    {{--                                                    class="btn btn-icon btn-sm btn-ghost-secondary remove-item-btn"><i--}}
                    {{--                                                    class="ri-close-fill fs-16"></i></button>--}}
                    {{--                                        </div>--}}
                    {{--                                    </div>--}}
                    {{--                                </div>--}}
                    {{--                            </div>--}}
                    {{--                        </div>--}}
                    {{--                        <div class="p-3 border-bottom-0 border-start-0 border-end-0 border-dashed border"--}}
                    {{--                             id="checkout-elem">--}}
                    {{--                            <div class="d-flex justify-content-between align-items-center pb-3">--}}
                    {{--                                <h5 class="m-0 text-muted">Total:</h5>--}}
                    {{--                                <div class="px-2">--}}
                    {{--                                    <h5 class="m-0" id="cart-item-total">$1258.58</h5>--}}
                    {{--                                </div>--}}
                    {{--                            </div>--}}

                    {{--                            <a href="apps-ecommerce-checkout" class="btn btn-success text-center w-100">--}}
                    {{--                                Checkout--}}
                    {{--                            </a>--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}
                    {{--                </div>--}}

                    <div class="ms-1 header-item d-none d-sm-flex">
                        <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                                data-toggle="fullscreen">
                            <i class='bx bx-fullscreen fs-22'></i>
                        </button>
                    </div>

                    <div class="ms-1 header-item d-sm-flex">
                        <button id="btndark" type="button"
                                class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle light-dark-mode">
                            <i class='bx bx-moon fs-22'></i>
                        </button>
                    </div>


                    <div wire:ignore class="dropdown topbar-head-dropdown ms-1 header-item">
                        <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                                id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false" data-bs-auto-close="false">
                            <i class='bx bx-bell fs-22'></i>
                            <span
                                class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-danger">0<span
                                    class="visually-hidden">unread messages</span></span>
                        </button>
                        <div wire:ignore class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                             aria-labelledby="page-header-notifications-dropdown">

                            <div class="dropdown-head bg-primary bg-pattern rounded-top">
                                <div class="p-3">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h1 class="m-0 fs-16 text-white"> {{__('Notifications')}} </h1>
                                        </div>
                                        <div class="col-auto dropdown-tabs">
                                            <span class="badge badge-soft-light fs-13"> 0 {{__('New')}}</span>
                                        </div>
                                    </div>
                                </div>

                                <div wire:ignore class="px-2 pt-2">
                                    <ul class="nav nav-tabs dropdown-tabs nav-tabs-custom" data-dropdown-tabs="true"
                                        id="notificationItemsTab" role="tablist">
                                        <li class="nav-item waves-effect waves-light">
                                            <a class="nav-link active" data-bs-toggle="tab" href=""
                                               role="tab"
                                               aria-selected="true">
                                                {{__('All')}} (4)
                                            </a>
                                        </li>
                                        <li class="nav-item waves-effect waves-light">
                                            <a class="nav-link" data-bs-toggle="tab" href="#messages-tab" role="tab"
                                               aria-selected="false">
                                                {{__('Messages')}}
                                            </a>
                                        </li>
                                        <li class="nav-item waves-effect waves-light">
                                            <a class="nav-link" data-bs-toggle="tab" href="" role="tab"
                                               aria-selected="false">
                                                {{__('Alerts')}}
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                            </div>

                            <div class="tab-content" id="notificationItemsTabContent">
                                <div class="tab-pane fade show active py-2 ps-2" id="all-noti-tab" role="tabpanel">
                                    <div data-simplebar style="max-height: 300px;" class="pe-2">
                                        <div
                                            class="text-reset notification-item d-block dropdown-item position-relative">
                                            <div class="d-flex">
                                                <div class="avatar-xs me-3">
                                                <span class="avatar-title bg-soft-info text-info rounded-circle fs-16">
                                                    <i class="bx bx-badge-check"></i>
                                                </span>
                                                </div>
                                                {{--                                                <div class="flex-1">--}}
                                                {{--                                                    <a href="#!" class="stretched-link">--}}
                                                {{--                                                        <h6 class="mt-0 mb-2 lh-base">Your <b>Elite</b> author Graphic--}}
                                                {{--                                                            Optimization <span class="text-secondary">reward</span> is--}}
                                                {{--                                                            ready!--}}
                                                {{--                                                        </h6>--}}
                                                {{--                                                    </a>--}}
                                                {{--                                                    <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">--}}
                                                {{--                                                        <span><i--}}
                                                {{--                                                                class="mdi mdi-clock-outline"></i> Just 30 sec ago</span>--}}
                                                {{--                                                    </p>--}}
                                                {{--                                                </div>--}}
                                                {{--                                                <div class="px-2 fs-15">--}}
                                                {{--                                                    <input class="form-check-input" type="checkbox">--}}
                                                {{--                                                </div>--}}
                                            </div>
                                        </div>

                                        <div
                                            class="text-reset notification-item d-block dropdown-item position-relative">
                                            {{--                                            <div class="d-flex">--}}
                                            {{--                                                <img src="{{ URL::asset('assets/images/users/avatar-2.jpg') }}"--}}
                                            {{--                                                     class="me-3 rounded-circle avatar-xs" alt="user-pic">--}}
                                            {{--                                                <div class="flex-1">--}}
                                            {{--                                                    <a href="#!" class="stretched-link"><h6--}}
                                            {{--                                                            class="mt-0 mb-1 fs-13 fw-semibold">Angela Bernier</h6></a>--}}
                                            {{--                                                    <div class="fs-13 text-muted">--}}
                                            {{--                                                        <p class="mb-1">Answered to your comment on the cash flow--}}
                                            {{--                                                            forecast's--}}
                                            {{--                                                            graph 🔔.</p>--}}
                                            {{--                                                    </div>--}}
                                            {{--                                                    <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">--}}
                                            {{--                                                        <span><i class="mdi mdi-clock-outline"></i> 48 min ago</span>--}}
                                            {{--                                                    </p>--}}
                                            {{--                                                </div>--}}
                                            {{--                                                <div class="px-2 fs-15">--}}
                                            {{--                                                    <input class="form-check-input" type="checkbox">--}}
                                            {{--                                                </div>--}}
                                            {{--                                            </div>--}}
                                        </div>

                                        <div
                                            class="text-reset notification-item d-block dropdown-item position-relative">
                                            {{--                                            <div class="d-flex">--}}
                                            {{--                                                <div class="avatar-xs me-3">--}}
                                            {{--                                                <span--}}
                                            {{--                                                    class="avatar-title bg-soft-danger text-danger rounded-circle fs-16">--}}
                                            {{--                                                    <i class='bx bx-message-square-dots'></i>--}}
                                            {{--                                                </span>--}}
                                            {{--                                                </div>--}}
                                            {{--                                                <div class="flex-1">--}}
                                            {{--                                                    <a href="#!" class="stretched-link">--}}
                                            {{--                                                        <h6 class="mt-0 mb-2 fs-13 lh-base">You have received <b--}}
                                            {{--                                                                class="text-success">20</b> new messages in the--}}
                                            {{--                                                            conversation--}}
                                            {{--                                                        </h6>--}}
                                            {{--                                                    </a>--}}
                                            {{--                                                    <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">--}}
                                            {{--                                                        <span><i class="mdi mdi-clock-outline"></i> 2 hrs ago</span>--}}
                                            {{--                                                    </p>--}}
                                            {{--                                                </div>--}}
                                            {{--                                                <div class="px-2 fs-15">--}}
                                            {{--                                                    <input class="form-check-input" type="checkbox">--}}
                                            {{--                                                </div>--}}
                                            {{--                                            </div>--}}
                                        </div>

                                        <div
                                            class="text-reset notification-item d-block dropdown-item position-relative">
                                            {{--                                            <div class="d-flex">--}}
                                            {{--                                                <img src="{{ URL::asset('assets/images/users/avatar-8.jpg') }}"--}}
                                            {{--                                                     class="me-3 rounded-circle avatar-xs" alt="user-pic">--}}
                                            {{--                                                <div class="flex-1">--}}
                                            {{--                                                    <a href="#!" class="stretched-link"><h6--}}
                                            {{--                                                            class="mt-0 mb-1 fs-13 fw-semibold">Maureen Gibson</h6></a>--}}
                                            {{--                                                    <div class="fs-13 text-muted">--}}
                                            {{--                                                        <p class="mb-1">We talked about a project on linkedin.</p>--}}
                                            {{--                                                    </div>--}}
                                            {{--                                                    <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">--}}
                                            {{--                                                        <span><i class="mdi mdi-clock-outline"></i> 4 hrs ago</span>--}}
                                            {{--                                                    </p>--}}
                                            {{--                                                </div>--}}
                                            {{--                                                <div class="px-2 fs-15">--}}
                                            {{--                                                    <input class="form-check-input" type="checkbox">--}}
                                            {{--                                                </div>--}}
                                            {{--                                            </div>--}}
                                        </div>

                                        {{--                                        <div class="my-3 text-center">--}}
                                        {{--                                            <button type="button" class="btn btn-soft-success waves-effect waves-light">--}}
                                        {{--                                                View--}}
                                        {{--                                                All Notifications <i class="ri-arrow-right-line align-middle"></i>--}}
                                        {{--                                            </button>--}}
                                        {{--                                        </div>--}}
                                    </div>

                                </div>

                                <div class="tab-pane fade py-2 ps-2" id="messages-tab" role="tabpanel"
                                     aria-labelledby="messages-tab">
                                    {{--                                    <div data-simplebar style="max-height: 300px;" class="pe-2">--}}
                                    {{--                                        <div class="text-reset notification-item d-block dropdown-item">--}}
                                    {{--                                            <div class="d-flex">--}}
                                    {{--                                                <img src="{{ URL::asset('assets/images/users/avatar-3.jpg') }}"--}}
                                    {{--                                                     class="me-3 rounded-circle avatar-xs" alt="user-pic">--}}
                                    {{--                                                <div class="flex-1">--}}
                                    {{--                                                    <a href="#!" class="stretched-link"><h6--}}
                                    {{--                                                            class="mt-0 mb-1 fs-13 fw-semibold">James Lemire</h6></a>--}}
                                    {{--                                                    <div class="fs-13 text-muted">--}}
                                    {{--                                                        <p class="mb-1">We talked about a project on linkedin.</p>--}}
                                    {{--                                                    </div>--}}
                                    {{--                                                    <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">--}}
                                    {{--                                                        <span><i class="mdi mdi-clock-outline"></i> 30 min ago</span>--}}
                                    {{--                                                    </p>--}}
                                    {{--                                                </div>--}}
                                    {{--                                                <div class="px-2 fs-15">--}}
                                    {{--                                                    <input class="form-check-input" type="checkbox">--}}
                                    {{--                                                </div>--}}
                                    {{--                                            </div>--}}
                                    {{--                                        </div>--}}

                                    {{--                                        <div class="text-reset notification-item d-block dropdown-item">--}}
                                    {{--                                            <div class="d-flex">--}}
                                    {{--                                                <img src="{{ URL::asset('assets/images/users/avatar-2.jpg') }}"--}}
                                    {{--                                                     class="me-3 rounded-circle avatar-xs" alt="user-pic">--}}
                                    {{--                                                <div class="flex-1">--}}
                                    {{--                                                    <a href="#!" class="stretched-link"><h6--}}
                                    {{--                                                            class="mt-0 mb-1 fs-13 fw-semibold">Angela Bernier</h6></a>--}}
                                    {{--                                                    <div class="fs-13 text-muted">--}}
                                    {{--                                                        <p class="mb-1">Answered to your comment on the cash flow--}}
                                    {{--                                                            forecast's--}}
                                    {{--                                                            graph 🔔.</p>--}}
                                    {{--                                                    </div>--}}
                                    {{--                                                    <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">--}}
                                    {{--                                                        <span><i class="mdi mdi-clock-outline"></i> 2 hrs ago</span>--}}
                                    {{--                                                    </p>--}}
                                    {{--                                                </div>--}}
                                    {{--                                                <div class="px-2 fs-15">--}}
                                    {{--                                                    <input class="form-check-input" type="checkbox">--}}
                                    {{--                                                </div>--}}
                                    {{--                                            </div>--}}
                                    {{--                                        </div>--}}

                                    {{--                                        <div class="text-reset notification-item d-block dropdown-item">--}}
                                    {{--                                            <div class="d-flex">--}}
                                    {{--                                                <img src="{{ URL::asset('assets/images/users/avatar-6.jpg') }}"--}}
                                    {{--                                                     class="me-3 rounded-circle avatar-xs" alt="user-pic">--}}
                                    {{--                                                <div class="flex-1">--}}
                                    {{--                                                    <a href="#!" class="stretched-link"><h6--}}
                                    {{--                                                            class="mt-0 mb-1 fs-13 fw-semibold">Kenneth Brown</h6></a>--}}
                                    {{--                                                    <div class="fs-13 text-muted">--}}
                                    {{--                                                        <p class="mb-1">Mentionned you in his comment on 📃 invoice--}}
                                    {{--                                                            #12501. </p>--}}
                                    {{--                                                    </div>--}}
                                    {{--                                                    <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">--}}
                                    {{--                                                        <span><i class="mdi mdi-clock-outline"></i> 10 hrs ago</span>--}}
                                    {{--                                                    </p>--}}
                                    {{--                                                </div>--}}
                                    {{--                                                <div class="px-2 fs-15">--}}
                                    {{--                                                    <input class="form-check-input" type="checkbox">--}}
                                    {{--                                                </div>--}}
                                    {{--                                            </div>--}}
                                    {{--                                        </div>--}}

                                    {{--                                        <div class="text-reset notification-item d-block dropdown-item">--}}
                                    {{--                                            <div class="d-flex">--}}
                                    {{--                                                <img src="{{ URL::asset('assets/images/users/avatar-8.jpg') }}"--}}
                                    {{--                                                     class="me-3 rounded-circle avatar-xs" alt="user-pic">--}}
                                    {{--                                                <div class="flex-1">--}}
                                    {{--                                                    <a href="#!" class="stretched-link"><h6--}}
                                    {{--                                                            class="mt-0 mb-1 fs-13 fw-semibold">Maureen Gibson</h6></a>--}}
                                    {{--                                                    <div class="fs-13 text-muted">--}}
                                    {{--                                                        <p class="mb-1">We talked about a project on linkedin.</p>--}}
                                    {{--                                                    </div>--}}
                                    {{--                                                    <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">--}}
                                    {{--                                                        <span><i class="mdi mdi-clock-outline"></i> 3 days ago</span>--}}
                                    {{--                                                    </p>--}}
                                    {{--                                                </div>--}}
                                    {{--                                                <div class="px-2 fs-15">--}}
                                    {{--                                                    <input class="form-check-input" type="checkbox">--}}
                                    {{--                                                </div>--}}
                                    {{--                                            </div>--}}
                                    {{--                                        </div>--}}

                                    {{--                                        <div class="my-3 text-center">--}}
                                    {{--                                            <button type="button" class="btn btn-soft-success waves-effect waves-light">--}}
                                    {{--                                                View--}}
                                    {{--                                                All Messages <i class="ri-arrow-right-line align-middle"></i></button>--}}
                                    {{--                                        </div>--}}
                                    {{--                                    </div>--}}
                                </div>
                                {{--                                <div class="tab-pane fade p-4" id="alerts-tab" role="tabpanel"--}}
                                {{--                                     aria-labelledby="alerts-tab">--}}
                                {{--                                    <div class="w-25 w-sm-50 pt-3 mx-auto">--}}
                                {{--                                        <img src="{{ URL::asset('assets/images/svg/bell.svg') }}" class="img-fluid"--}}
                                {{--                                             alt="user-pic">--}}
                                {{--                                    </div>--}}
                                {{--                                    <div class="text-center pb-5 mt-2">--}}
                                {{--                                        <h6 class="fs-18 fw-semibold lh-base">Hey! You have no any notifications </h6>--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}
                            </div>
                        </div>
                    </div>

                    <div class="dropdown ms-sm-3 header-item topbar-user">
                        <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <img class="rounded-circle header-profile-user"
                                 src="@if (file_exists('uploads/profiles/profile-image-' . $user->idUser . '.png')) {{ URL::asset('uploads/profiles/profile-image-'.$user->idUser.'.png') }}@else{{ URL::asset('uploads/profiles/default.png') }} @endif"
                                 alt="Header Avatar">
                            <span class="text-start ms-xl-2">
                                <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">
{{--                                    //enFirstName--}}
                                    {{--        //enLastName--}}
                                    {{--        //arFirstName--}}
                                    {{--        //arLastName--}}
                                    {{--        //fullNumber--}}
                                    @if(config('app.available_locales')[app()->getLocale()]['direction'] === 'rtl')
                                        @if(isset($user->arFirstName) && isset($user->arLastName) && !empty($user->arFirstName) && !empty($user->arLastName))
                                            {{$user->arFirstName}} {{$user->arLastName}}
                                        @else
                                            @if((isset($user->arFirstName)&&!empty($user->arFirstName)) || (isset($user->arLastName)&&!empty($user->arLastName)))
                                                @if(isset($user->arFirstName)&& !empty($user->arFirstName))
                                                    {{$user->arFirstName}}
                                                @endif
                                                @if(isset($user->arLastName)&& !empty($user->arLastName))
                                                    {{$user->arLastName}}
                                                @endif
                                            @else
                                                {{$user->fullNumber}}
                                            @endif
                                        @endif
                                    @else
                                        @if(isset($user->enFirstName) && isset($user->enLastName) && !empty($user->enFirstName) && !empty($user->enLastName))
                                            {{$user->enFirstName}} {{$user->enLastName}}
                                        @else
                                            @if(   (isset( $user->enFirstName)&&!empty($user->enFirstName)) || (isset($user->enLastName)&&!empty($user->enLastName)))
                                                @if(isset($user->enFirstName) && !empty($user->enFirstName))
                                                    {{$user->enFirstName}}
                                                @endif
                                                @if(isset($user->enLastName)&& !empty($user->enLastName))
                                                    {{$user->enLastName}}
                                                @endif
                                            @else
                                                {{$user->fullNumber}}
                                            @endif
                                        @endif
                                    @endif
                                    {{--                                    {{Auth::user()->name}}--}}

                                </span>
                                <span
                                    <?php
                                    $cnP = \Illuminate\Support\Facades\Lang::get($userRole);
                                    ?>
                                    class="d-none d-xl-block ms-1 fs-12 text-muted user-name-sub-text">{{$cnP}}</span>
                            </span>
                        </span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <!-- item-->
                            {{--                        <h6 class="dropdown-header">Welcome !</h6>--}}
                            <a class="dropdown-item" href="{{route('account',app()->getLocale() )}}"><i
                                    class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span
                                    class="align-middle">{{ __('Account') }}</span></a>
                            {{--                            <a class="dropdown-item" href="#"><i--}}
                            {{--                                    class="mdi mdi-message-text-outline text-muted fs-16 align-middle me-1"></i> <span--}}
                            {{--                                    class="align-middle">Messages</span></a>--}}
                            {{--                            <a class="dropdown-item" href="#"><i--}}
                            {{--                                    class="mdi mdi-calendar-check-outline text-muted fs-16 align-middle me-1"></i> <span--}}
                            {{--                                    class="align-middle">Taskboard</span></a>--}}
                            {{--                            <a class="dropdown-item" href="#"><i--}}
                            {{--                                    class="mdi mdi-lifebuoy text-muted fs-16 align-middle me-1"></i> <span--}}
                            {{--                                    class="align-middle">Help</span></a>--}}
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{route('user_balance_cb',app()->getLocale())}}"><i
                                    class="mdi mdi-wallet text-muted fs-16 align-middle me-1"></i> <span
                                    class=""> {{ __('Cash Balance') }} : <b>  {{__('DPC')}}  {{$solde->soldeCB}}</b></span></a>
                            {{--                            @if("Super admin"=="Super admin")--}}
                            {{--                                <a class="dropdown-item" href="{{route('configuration',app()->getLocale())}}"><i--}}
                            {{--                                        class="mdi mdi-cog-outline text-muted fs-16 align-middle me-1"></i> <span--}}
                            {{--                                        class="align-middle">{{ __('Settings') }}</span></a>--}}
                            {{--                            @endif--}}
                            {{--                            <a class="dropdown-item" href="#"><i--}}
                            {{--                                    class="mdi mdi-lock text-muted fs-16 align-middle me-1"></i> <span--}}
                            {{--                                    class="align-middle">Lock screen</span></a>--}}
                            <a class="dropdown-item " href="" wire:click="logout">
                                <i class="bx bx-power-off font-size-16 align-middle me-1"></i>
                                <span key="t-logout">{{ __('Logout') }}</span></a>


                        </div>
                    </div>
                </div>
            </div>
        </div>

    </header>
    <script>

        // function testDark()
        // {
        //
        //
        //         // alert('dark from app.min');
        //         var html = document.getElementById("HTMLMain");
        //         alert(html);
        //         html.hasAttribute("data-layout-mode") && html.getAttribute("data-layout-mode") == "dark" ?
        //             setLayoutMode("data-layout-mode", "light", "layout-mode-light", html) :
        //             setLayoutMode("data-layout-mode", "dark", "layout-mode-dark", html);
        //
        // }
    </script>
</div>
