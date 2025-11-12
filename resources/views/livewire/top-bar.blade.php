<div class="{{getContainerType()}} mb-3">
    <header id="page-topbar">
        <div class="layout-width">
            <div class="navbar-header">
                <div class="d-flex">
                    <div class="ms-1 header-item d-flex">
                        <a href="{{ route('home', app()->getLocale()) }}" class="m-2" title="{{ __('To Home') }}">
                            <img src="{{ Vite::asset('resources/images/logo2earn.png') }}" height="54px"
                                 alt="{{ config('app.name') }}">
                        </a>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div class="dropdown topbar-head-dropdown ms-1 header-item" id="business-sectors">
                        <button type="button"
                                class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                                aria-label="{{ __('Business sectors') }}"
                                title="{{ __('Business sectors') }}">
                            <i class='bx bx-category-alt fs-22'></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-lg p-0 dropdown-menu-end shadow-lg"
                             style="min-width: 380px; max-width: 480px;">
                            <!-- Header -->
                            <div class="p-3 bg-soft-primary border-bottom">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h6 class="m-0 fw-semibold fs-15 text-primary">
                                            <i class='bx bx-category-alt me-1'></i>
                                            {{__('Business sectors')}}
                                        </h6>
                                        @if($sectorsNumber > 0)
                                            <small
                                                class="text-muted">{{ $sectorsNumber }} {{ __('sectors available') }}</small>
                                        @endif
                                    </div>
                                    @if(\App\Models\User::isSuperAdmin())
                                        <div class="col-auto">
                                            <a href="{{route('business_sector_index',['locale'=> app()->getLocale()])}}"
                                               class="btn btn-sm btn-soft-info"
                                               title="{{ __('Manage business sectors') }}">
                                                <i class="bx bx-cog me-1"></i>
                                                <span class="d-none d-sm-inline">{{__('Manage')}}</span>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Sectors Grid -->
                            <div class="p-3" style="max-height: 400px; overflow-y: auto;">
                                @if($sectors && count($sectors) > 0)
                                    <div class="row g-3">
                                        @foreach($sectors as $sector)
                                            @php
                                                $logoUrl = empty($sector->logoImage)
                                                    ? Vite::asset(\App\Models\BusinessSector::DEFAULT_IMAGE_TYPE_LOGO)
                                                    : asset('uploads/' . $sector->logoImage->url);
                                                $sectorName = \App\Models\TranslaleModel::getTranslation($sector,'name',$sector->name);
                                            @endphp
                                            <div class="col-6 col-md-4">
                                                <a class="business-sector-card d-flex flex-column align-items-center text-center p-3 rounded border text-decoration-none transition-all"
                                                   href="{{route('business_sector_show',['locale'=>app()->getLocale(),'id'=>$sector->id])}}"
                                                   title="{{ $sectorName }}">
                                                    <div class="business-sector-icon mb-2">
                                                        <img src="{{ $logoUrl }}"
                                                             alt="{{ $sectorName }}"
                                                             class="rounded"
                                                             style="width: 48px; height: 48px; object-fit: contain;"
                                                             loading="lazy"
                                                             onerror="this.src='{{ Vite::asset(\App\Models\BusinessSector::DEFAULT_IMAGE_TYPE_LOGO) }}'">
                                                    </div>
                                                    <span class="business-sector-name small fw-medium text-dark">
                                                        {{ Str::limit($sectorName, 30) }}
                                                    </span>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class='bx bx-category fs-48 text-muted mb-3 d-block'></i>
                                        <p class="text-muted mb-0">{{ __('No business sectors available') }}</p>
                                    </div>
                                @endif
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
                            <img class="rounded-circle header-profile-user mx-2"
                                 src="{{ URL::asset($userProfileImage) }}" alt="{{ getUserDisplayedName() }}">
                            <span class="text-center ms-xl-2">
                                <span class="d-none d-xl-inline-block mx-2 fw-medium user-name-text"
                                      title="{{$userStatus}}">
                                    {{getUserDisplayedName()}} </span>
                                 <span
                                     class="d-none d-xl-block badge bg-light line-highlight @if($user->status==1) text-success  @else text-muted @endif mb-0">
                                        <span class="mb-5">{{__($userRole)}}</span>
                                        @if($userStatus==2)
                                         <i class="mdi mdi-22px mdi-account-check text-success validated-user"
                                            title="{{__('National identified')}}"></i>
                                     @elseif($userStatus==1)
                                         <i class="mdi mdi-22px mdi-account-alert text-warning validated-user"
                                            title="{{__('National identification request in process')}}"></i>
                                     @elseif($userStatus==5)
                                         <i class="mdi mdi-22px mdi-account-alert text-warning validated-user"
                                            title="{{__('International identification request in process')}}"></i>
                                     @elseif($userStatus==6)
                                         <i class="mdi mdi-22px mdi-account-alert text-warning validated-user"
                                            title="{{__('Global identification request in process')}}"></i>
                                     @elseif($userStatus==4)
                                         <i class="mdi mdi-22px mdi-account-check text-info validated-user"
                                            title="{{__('International identified')}}"></i>
                                     @endif
                                 </span>
                            </span>
                        </span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="{{route('account',app()->getLocale() )}}">
                                <img class="rounded-circle header-profile-user"
                                     src="{{ URL::asset($userProfileImage) }}" alt="{{ getUserDisplayedName() }}">
                                <strong
                                    class="align-middle mx-2 text-muted font-weight-bold">{{ __('Account') }}</strong>
                            </a>
                            <a class="dropdown-item" href="{{route('change_password',app()->getLocale())}}">
                                <i class="ri-lock-password-line text-muted fs-16 align-middle me-2"></i>
                                <span class="text-muted">{{ __('Change password') }}</span>
                            </a>
                            <a class="dropdown-item" href="{{route('identification',app()->getLocale())}}">
                                <i class="ri-shield-check-line text-muted fs-16 align-middle me-2"></i>
                                <span class="text-muted">{{ __('Identifications') }}</span>
                            </a>
                            <div class="dropdown-divider">
                            </div>
                            <a class="dropdown-item" href="{{route('user_balance_cb',app()->getLocale())}}"><i
                                    class="mdi mdi-wallet text-muted fs-14 align-middle me-1"></i> <span>  <b> {{ __('Cash Balance') }} : {{__('DPC')}}  {{formatSolde($cash,3)}}</b>
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

            <div class="navbar-header border-top ">
                <div class="d-flex w-100 py-2 align-items-center">
                    @php
                        $topBalances = [
                            [
                                'route' => route('user_balance_cb', app()->getLocale()),
                                'title' => __('Cash Balance'),
                                'value' => __('DPC') . ' ' . formatSolde(intval($cash), 0),
                                'icon'  => 'bx bx-dollar-circle',
                                'iconBg'=> 'bg-soft-info',
                                'iconColor' => 'text-info',
                                'textClass' => 'text-info',
                                'labelClass'=> 'text-muted'
                            ],
                            [
                                'route' => route('user_balance_bfs', app()->getLocale()),
                                'title' => __('BFS s'),
                                'value' => __('DPC') . formatSolde(intval($bfs), 0),
                                'icon'  => 'ri-shopping-cart-2-line',
                                'iconBg'=> 'bg-soft-success',
                                'iconColor' => 'text-success',
                                'textClass' => 'text-success',
                                'labelClass'=> 'text-muted'
                            ],
                            [
                                'route' => route('user_balance_db', app()->getLocale()),
                                'title' => __('Discount'),
                                'value' => __('DPC') . formatSolde(intval($db), 0),
                                'icon'  => 'ri-coupon-4-line',
                                'iconBg'=> 'bg-soft-secondary',
                                'iconColor' => 'text-secondary',
                                'textClass' => 'text-secondary',
                                'labelClass'=> 'text-muted'
                            ]
                        ];
                    @endphp
                    @foreach($topBalances as $bal)
                        <div class="mx-1"
                             title="{{__('Soldes calculated at')}} : {{Carbon\Carbon::now()->toDateTimeString()}}">
                            <a href="{{ $bal['route'] }}"
                               class="d-flex align-items-center text-decoration-none topbar-balance-link"
                               aria-label="{{ $bal['title'] }}">
                                <div class="avatar-xs flex-shrink-0 me-2">
                                    <span class="avatar-title {{ $bal['iconBg'] }} rounded fs-3">
                                        <i class="{{ $bal['icon'] }} {{ $bal['iconColor'] }}"></i>
                                    </span>
                                </div>
                                <div class="d-flex flex-column overflow-hidden">
                                    <p class="text-uppercase fw-medium mb-0 small {{ $bal['labelClass'] }}">{{ $bal['title'] }}</p>
                                    <h5 class="fs-10 mb-0 {{ $bal['textClass'] }}"
                                        aria-hidden="true">{{ $bal['value'] }}</h5>
                                </div>
                            </a>
                        </div>
                    @endforeach
                    <div class="dropdown topbar-head-dropdown header-item me-1 ms-auto">
                        <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary"
                                data-bs-toggle="dropdown" data-bs-auto-close="false" aria-expanded="true">
                            <img
                                src="{{ Vite::asset('resources/images/flags/'.config('app.available_locales')[app()->getLocale()]['flag'].'.svg') }}"
                                class="rounded" alt="{{__('Header Language')}}"
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

                </div>
            </div>
        </div>
    </header>
    <script>
        window.addEventListener('updateNotifications', () => {
            $("#page-header-notifications-dropdown").click();
        })
    </script>
</div>
