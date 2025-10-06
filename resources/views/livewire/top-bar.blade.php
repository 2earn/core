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
                    <div class="dropdown topbar-head-dropdown header-item">
                        <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary"
                                data-bs-toggle="dropdown" data-bs-auto-close="false" aria-expanded="true">
                            <img
                                src="{{ Vite::asset('resources/images/flags/'.config('app.available_locales')[app()->getLocale()]['flag'].'.svg') }}"
                                class="rounded" alt="Header Language"
                                height="22">
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            @foreach (config('app.available_locales') as  $locale => $value )
                                @if(strpos(Request::url(), '/'.app()->getLocale().'/'))
                                    <a href="{{str_replace('/'.app()->getLocale().'/', '/'.$value['name'].'/', Request::url())}}"
                                       class="dropdown-item notify-item language py-2"
                                       data-lang="{{$locale}}"
                                       title="{{ __('lang'.$locale)  }}">
                                        <img src="{{ Vite::asset('resources/images/flags/'.$value['flag'].'.svg') }}"
                                             alt="user-image" class="me-2 rounded" height="20">
                                        <span class="align-middle">{{ __('lang'.$locale)  }}</span>
                                    </a>
                                @else
                                    <a href="{{str_replace('/'.app()->getLocale(), '/'.$value['name'].'/', Request::url())}}"
                                       class="dropdown-item notify-item language py-2 "
                                       data-lang="{{$locale}}"
                                       title="{{ __('lang'.$locale)  }}">
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
                            <i class='bx bx-category-alt fs-22'></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-lg p-0 dropdown-menu-end">
                            <div class="p-3 border-top-0 border-start-0 border-end-0 border-dashed border">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h6 class="m-0 fw-semibold fs-15"> {{__('Business sectors')}} </h6>
                                    </div>
                                    @if(\App\Models\User::isSuperAdmin())
                                        <div class="col-auto">
                                            <a href="{{route('business_sector_index',['locale'=> app()->getLocale()])}}"
                                               class="btn btn-sm btn-soft-info"> {{__('View All Business sectors')}}
                                                <i class="bx bx-category-alt"></i></a>
                                        </div>
                                    @endif
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
                                                <span>
                                                    {{\App\Models\TranslaleModel::getTranslation($sector,'name',$sector->name)}}
                                                </span>
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
                    <livewire:cart/>
                    @livewire('notification-dropdown')
                    <div class="dropdown ms-sm-3 header-item topbar-user">
                        <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <img class="rounded-circle header-profile-user mx-2" alt="Header Avatar"
                                 src="{{ URL::asset($userProfileImage) }}">
                            <span class="text-center ms-xl-2">
                                <span class="d-none d-xl-inline-block mx-2 fw-medium user-name-text"
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
                                <strong
                                    class="align-middle mx-2 text-muted font-weight-bold">{{ __('Account') }}</strong>
                            </a>
                            <div class="dropdown-divider">
                            </div>
                            <a class="dropdown-item" href="{{route('user_balance_cb',app()->getLocale())}}"><i
                                    class="mdi mdi-wallet text-muted fs-16 align-middle me-1"></i> <span>  <b> {{ __('Cash Balance') }} : {{__('DPC')}}  {{formatSolde($cash,3)}}</b>
                                </span>
                            </a>
                            <div class="dropdown-divider">
                            </div>
                            <a class="dropdown-item" href="{{route('notification_history',app()->getLocale())}}">
                                <span class="text-muted"> {{ __('Notification history') }}</span>
                            </a>
                            <a class="dropdown-item" href="{{route('notification_settings',app()->getLocale())}}">
                               <span class="text-muted"> {{ __('Notification Settings') }}</span>
                            </a>
                            <a class="dropdown-item" href="{{route('faq_index',app()->getLocale())}}">
                               <span class="text-muted"> {{ __('Frequently asked questions') }}</span>
                            </a>
                            <a class="dropdown-item" href="{{ route('user_guides_index', app()->getLocale()) }}">
                               <span class="text-muted">{{ __('User Guides') }}</span>
                            </a>
                            <a class="dropdown-item" href="{{route('coupon_history',['locale'=>app()->getLocale()])}}">
                               <span class="text-muted"> {{ __('Coupons History') }}</span>
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
