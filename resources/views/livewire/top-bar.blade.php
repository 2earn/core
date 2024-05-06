<div>
    <header id="page-topbar">
        <div class="layout-width">
            <div class="navbar-header">
                <div class="d-flex">
                    <div class="navbar-brand-box horizontal-logo">
                        <a href="index" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="{{ URL::asset('assets/images/logo-sm.png') }}" height="22">
                        </span>
                            <span class="logo-lg">
                            <img src="{{ URL::asset('assets/images/logo-dark.png') }}" height="17">
                        </span>
                        </a>
                        <a href="index" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="{{ URL::asset('assets/images/logo-sm.png') }}" height="22">
                        </span>
                            <span class="logo-lg">
                            <img src="{{ URL::asset('assets/images/logo-light.png') }}" height="17">
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
                            </div>
                        </div>
                    </div>
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
                                    class="visually-hidden">{{__('unread messages')}}unread messages</span></span>
                        </button>
                        <div wire:ignore class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                             aria-labelledby="page-header-notifications-dropdown">
                            <div class="dropdown-head bg-primary bg-pattern rounded-top">
                                <div class="p-3">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h1 class="m-0 fs-16 text-white">{{__('Notifications')}}</h1>
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
                                            </div>
                                        </div>
                                        <div
                                            class="text-reset notification-item d-block dropdown-item position-relative">
                                        </div>
                                        <div
                                            class="text-reset notification-item d-block dropdown-item position-relative">
                                        </div>
                                        <div
                                            class="text-reset notification-item d-block dropdown-item position-relative">
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade py-2 ps-2" id="messages-tab" role="tabpanel"
                                     aria-labelledby="messages-tab">
                                </div>
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
                                </span>
                                <span class="d-none d-xl-block ms-1 fs-12 text-muted user-name-sub-text">
                                    {{__($userRole)}}
                                </span>
                            </span>
                        </span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="{{route('account',app()->getLocale() )}}"><i
                                    class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span
                                    class="align-middle">{{ __('Account') }}</span></a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{route('user_balance_cb',app()->getLocale())}}"><i
                                    class="mdi mdi-wallet text-muted fs-16 align-middle me-1"></i> <span
                                    class=""> {{ __('Cash Balance') }} : <b>  {{__('DPC')}}  {{$solde->soldeCB}}</b></span></a>
                            <a class="dropdown-item " href="" wire:click="logout">
                                <i class="bx bx-power-off font-size-16 align-middle me-1"></i>
                                <span key="t-logout">{{ __('Logout') }}</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
</div>
