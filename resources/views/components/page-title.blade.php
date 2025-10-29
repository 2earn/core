@php use App\Models\User; @endphp

<div id="page-title-box" class="page-title-box p-1">
    <nav class="col d-flex align-items-center text-white" aria-label="{{ __('Breadcrumb') }}">
        <ol class="breadcrumb m-0 flex-grow-1">
            <li class="breadcrumb-item">
                <a href="{{ route('home', app()->getLocale()) }}" title="{{ __('To Home') }}"
                   class="text-muted icon-square">
                    <i class="ri-home-7-line fs-5 align-middle" aria-hidden="true"></i>
                    <span class="visually-hidden">{{ __('Home') }}</span>
                </a>
            </li>

            @php
                $crumbs = null;
                if (isset($items) && is_array($items) && count($items)) {
                    $crumbs = [];
                    foreach ($items as $it) {
                        if (is_array($it)) {
                            $crumbs[] = [
                                'label' => $it['label'] ?? ($it['title'] ?? ''),
                                'url' => $it['url'] ?? ($it['href'] ?? null),
                            ];
                        } else {
                            $crumbs[] = ['label' => (string)$it, 'url' => null];
                        }
                    }
                }
            @endphp

            @if($crumbs)
                @foreach($crumbs as $index => $crumb)
                    @php $isLast = $index === count($crumbs) - 1; @endphp
                    <li class="breadcrumb-item @if($isLast) active @endif" @if($isLast) aria-current="page" @endif>
                        @if(!$isLast && !empty($crumb['url']) && $crumb['url'] !== '#')
                            <a href="{{ $crumb['url'] }}">{!! $crumb['label'] !!}</a>
                        @else
                            {!! $crumb['label'] !!}
                        @endif
                    </li>
                @endforeach
            @else
                @if(($pageTitle ?? '') !== 'Home' && !empty($pageTitle))
                    <li class="breadcrumb-item fs-16 text-muted" aria-current="page">
                        {!! $pageTitle !!}
                    </li>
                @endif
            @endif
        </ol>

        @if(!empty($helpUrl ?? null) && $helpUrl !== '#')
            <a href="{{ $helpUrl }}" title="{{ __('Check the help page') }}"
               class="ms-auto float-end m-2 icon-square text-muted">
                <i class="ri-question-line fs-5 align-middle" aria-hidden="true"></i>
                <span class="visually-hidden">{{ __('Help') }}</span>
            </a>
        @endif

        <a href="#" id="site-menu-btn" title="{{ __('Site menu') }}"
           class="ms-auto float-end m-2 icon-square text-muted">
            <i class="ri-menu-line fs-5 align-middle" aria-hidden="true"></i>
            <span class="visually-hidden">{{ __('Menu') }}</span>
        </a>
    </nav>

    <div class="container-fluid text-muted" id="sidebar-menu-container" style="display: none">
        <div class="menu-content">
            <div class="row menu-content-row">
                <div class="card border card-border-light col-12"
                     style="display: flex; flex-direction: column; max-height: 100%;">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <ul class="nav nav-pills card-header-pills" id="menu-tabs" role="tablist">
                            <li class="nav-item text-muted" role="presentation">
                                <button class="nav-link p-2 active" id="user-menu-tab" data-bs-toggle="tab"
                                        data-bs-target="#user-menu-pane" type="button" role="tab"
                                        aria-controls="user-menu-pane" aria-selected="true">
                                    <i class="ri-user-line me-1 m-1"></i>{{ __('User') }}
                                </button>
                            </li>
                            @if(User::isSuperAdmin())
                                <li class="nav-item text-muted" role="presentation">
                                <button class="nav-link p-2" id="admin-menu-tab" data-bs-toggle="tab"
                                        data-bs-target="#admin-menu-pane" type="button" role="tab"
                                        aria-controls="admin-menu-pane" aria-selected="false">
                                    <i class="ri-admin-line me-1 m-1"></i>{{ __('SUPER ADMIN') }}
                                </button>
                            </li>
                            @endif
                        </ul>
                        <button class="btn btn-sm btn-close close-menu-btn ms-2" title="{{ __('Close menu') }}"
                                aria-label="{{ __('Close') }}"></button>
                    </div>
                    <div class="card-body" style="flex: 1; overflow-y: auto; overflow-x: hidden;">
                        <div class="tab-content" id="menu-tabs-content">
                            <div class="tab-pane fade show active" id="user-menu-pane" role="tabpanel"
                                 aria-labelledby="user-menu-tab" tabindex="0">
                                <ul class="navbar-nav" id="navbar-nav-1">
                            <li class="nav-item cool-link {{$currentRouteName=='home'? 'active' : ''}}">
                                <a data-name="home" href="{{route('home',app()->getLocale(),false)}}"
                                   class="nav-link menu-link {{$currentRouteName=='home'? 'active' : ''}}"
                                   role="button">
                                    <i class="ri-home-gear-fill mx-2"></i>
                                    <span>{{ __('Home') }}</span>
                                </a>
                            </li>
                            <li class="nav-item cool-link {{$currentRouteName=='account'? 'active' : ''}}">
                                <a data-name="account" href="{{route('account',app()->getLocale(),false )}}"
                                   class="nav-link menu-link {{$currentRouteName=='account'? 'active' : ''}}"
                                   role="button">
                                    <i class="ri-account-pin-circle-fill mx-2"></i>
                                    <span>{{ __('Account') }}</span>
                                </a>
                            </li>
                            <li class="nav-item cool-link {{$currentRouteName=='contacts'? 'active' : ''}}">
                                <a data-name="contacts" href="{{route('contacts',app()->getLocale(),false)}}"
                                   class="nav-link menu-link {{$currentRouteName=='contacts'? 'active' : ''}}"
                                   role="button">
                                    <i class="ri-contacts-fill mx-2"></i>
                                    <span>{{ __('Contact') }}</span>
                                </a>
                            </li>
                            <li class="nav-item cool-link {{$currentRouteName==$sidebarBusinessArray[0]? 'active' : ''}}">
                                <a href="{{route($sidebarBusinessArray[0], app()->getLocale(),false)}}"
                                   class="nav-link menu-link">
                                    <i class="ri-line-chart-fill mx-2"></i>
                                    <span>{{ __('Trading') }}</span>
                                </a>
                            </li>
                            <li class="nav-item cool-link {{$currentRouteName==$sidebarBusinessArray[1]? 'active' : ''}}">
                                <a href="{{route($sidebarBusinessArray[1], app()->getLocale(),false)}}"
                                   class="nav-link menu-link">
                                    <i class="ri-money-dollar-circle-line mx-2"></i>
                                    <span>{{ __('Additional Income') }}</span>
                                </a>
                            </li>
                            <li class="nav-item cool-link {{$currentRouteName==$sidebarBusinessArray[2]? 'active' : ''}}">
                                <a href="{{route($sidebarBusinessArray[2], app()->getLocale(),false)}}"
                                   class="nav-link menu-link">
                                    <i class="ri-user-star-line mx-2"></i>
                                    <span>{{ __('Be Influencer') }}</span>
                                </a>
                            </li>
                            <li class="nav-item cool-link {{$currentRouteName==$sidebarBusinessArray[3]? 'active' : ''}}">
                                <a href="{{route($sidebarBusinessArray[3], app()->getLocale(),false)}}"
                                   class="nav-link menu-link">
                                    <i class="ri-briefcase-line mx-2"></i>
                                    <span>{{ __('Job Opportunities') }}</span>
                                </a>
                            </li>
                            <li class="nav-item cool-link {{$currentRouteName==$sidebarSavingsArray[0]? 'active' : ''}}">
                                <a href="{{route($sidebarSavingsArray[0], app()->getLocale(),false)}}"
                                   class="nav-link menu-link">
                                    <i class="ri-shopping-bag-line mx-2"></i>
                                    <span>{{ __('Purchase history') }}</span>
                                </a>
                            </li>
                            <li class="nav-item cool-link {{$currentRouteName==$sidebarSavingsArray[1]? 'active' : ''}}">
                                <a href="{{route($sidebarSavingsArray[1], app()->getLocale(),false)}}"
                                   class="nav-link menu-link">
                                    <i class="ri-history-line mx-2"></i>
                                    <span>{{ __('Historique_recuperation') }}</span>
                                </a>
                            </li>
                            <li class="nav-item cool-link {{$currentRouteName==$sidebarBiographyArray[8]? 'active' : ''}}">
                                <a href="{{route($sidebarBiographyArray[8], app()->getLocale(),false)}}"
                                   class="nav-link menu-link">
                                    <i class="ri-id-card-line mx-2"></i>
                                    <span>{{ __('e-Business Card (EBC)') }}</span>
                                </a>
                            </li>

                            <li class="nav-item cool-link {{$currentRouteName=='financial_transaction'? 'active' : ''}}">
                                <a href="{{route('financial_transaction',app()->getLocale(),false)}}"
                                   class="nav-link menu-link {{$currentRouteName=='financial_transaction'? 'active' : ''}}"
                                   role="button">
                                    <i class="ri-bank-fill mx-2"></i> <span>{{ __('Exchange') }}</span>
                                </a>
                            </li>
                            <li style="margin-top: -20px" class="nav-item d-none">
                                <a id="NotificationRequest" class="nav-link menu-link">
                                </a>
                            </li>
                            <li class="nav-item cool-link {{$currentRouteName=='hobbies'? 'active' : ''}}">
                                <a href="{{route('hobbies',app()->getLocale(),false )}}"
                                   class="nav-link menu-link {{$currentRouteName=='hobbies'? 'active' : ''}}"
                                   role="button">
                                    <i class="ri-stack-line mx-2"></i>
                                    <span>{{__('Hobbies')}}</span>
                                </a>
                            </li>
                            <li class="nav-item cool-link {{$currentRouteName=='news_index'? 'active' : ''}}">
                                <a href="{{route('news_index',app()->getLocale(),false )}}"
                                   class="nav-link menu-link {{$currentRouteName=='news_index'? 'active' : ''}}"
                                   role="button">
                                    <i class="ri-newspaper-fill mx-2"></i>
                                    <span>{{__('News')}}</span>
                                </a>
                            </li>
                            <li class="nav-item cool-link {{$currentRouteName=='event_index'? 'active' : ''}}">
                                <a href="{{route('event_index',app()->getLocale(),false )}}"
                                   class="nav-link menu-link {{$currentRouteName=='event_index'? 'active' : ''}}"
                                   role="button">
                                    <i class="ri-calendar-event-fill mx-2"></i>
                                    <span>{{__('Events')}}</span>
                                </a>
                            </li>
                            @if(\Core\Models\Platform::canCheckDeals(auth()->user()->id))
                                <li class="nav-item cool-link {{$currentRouteName=='deals_index'? 'active' : ''}}">
                                    <a href="{{route('deals_index',app()->getLocale(),false )}}"
                                       class="nav-link menu-link {{$currentRouteName=='deals_index'? 'active' : ''}}"
                                       role="button">
                                        <i class="ri-honour-line mx-2"></i>
                                        <span>{{__('Deals')}}</span>
                                    </a>
                                </li>
                            @endIf
                            <li class="nav-item cool-link {{$currentRouteName=='coupon_injector_runner'? 'active' : ''}}">
                                <a href="{{route('coupon_injector_runner',app()->getLocale(),false )}}"
                                   class="nav-link menu-link {{$currentRouteName=='coupon_injector_runner'? 'active' : ''}}"
                                   role="button">
                                    <i class="ri-coupon-2-fill mx-2"></i>
                                    <span>{{__('Balance inj. Coupons')}}</span>
                                </a>
                            </li>

                            <li class="nav-item cool-link {{$currentRouteName=='settlement_tracking'? 'active' : ''}}">
                                <a href="{{route('settlement_tracking',app()->getLocale(),false )}}"
                                   class="nav-link menu-link {{$currentRouteName=='settlement_tracking'? 'active' : ''}}"
                                   role="button">
                                    <i class="ri-money-dollar-circle-fill mx-2"></i>
                                    <span>{{__('Settlement tracking')}}</span>
                                </a>
                            </li>
                        </ul>
                            </div>
                            @if(User::isSuperAdmin())
                            <div class="tab-pane fade" id="admin-menu-pane" role="tabpanel"
                                 aria-labelledby="admin-menu-tab" tabindex="0">
                                <ul class="navbar-nav" id="navbar-nav-2">
                                <li class="nav-item cool-link {{$currentRouteName=='business_sector_index'? 'active' : ''}}">
                                    <a href="{{route('business_sector_index',app()->getLocale(),false )}}"
                                       class="nav-link menu-link {{$currentRouteName=='business_sector_index'? 'active' : ''}}"
                                       role="button">
                                        <i class="bx bx-category-alt mx-2"></i>
                                        <span>{{__('Business sector')}}</span>
                                    </a>
                                </li>
                                <li class="nav-item cool-link {{$currentRouteName=='platform_index'? 'active' : ''}}">
                                    <a href="{{route('platform_index',['locale'=>app()->getLocale()],false )}}"
                                       class="nav-link menu-link {{$currentRouteName=='platform_index'? 'active' : ''}}"
                                       role="button">
                                        <i class="ri-git-repository-private-fill mx-2"></i>
                                        <span>{{__('Platform')}}</span>
                                    </a>
                                </li>
                                <li class="nav-item cool-link {{$currentRouteName=='coupon_index'? 'active' : ''}}">
                                    <a href="{{route('coupon_index',app()->getLocale(),false )}}"
                                       class="nav-link menu-link {{$currentRouteName=='coupon_index'? 'active' : ''}}"
                                       role="button">
                                        <i class="ri-coupon-3-fill mx-2"></i>
                                        <span>{{__('Coupon')}}</span>
                                    </a>
                                </li>
                                <li class="nav-item cool-link {{$currentRouteName=='coupon_injector_index'? 'active' : ''}}">
                                    <a href="{{route('coupon_injector_index',app()->getLocale(),false )}}"
                                       class="nav-link menu-link {{$currentRouteName=='coupon_injector_index'? 'active' : ''}}"
                                       role="button">
                                        <i class="ri-coupon-4-fill mx-2"></i>
                                        <span>{{__('Balance injector')}}</span>
                                    </a>
                                </li>
                                <li class="nav-item cool-link {{$currentRouteName=='orders_index'? 'active' : ''}}">
                                    <a href="{{route('orders_index',['locale'=>app()->getLocale()],false )}}"
                                       class="nav-link menu-link {{$currentRouteName=='orders_index'? 'active' : ''}}"
                                       role="button">
                                        <i class="ri-shopping-cart-fill mx-2"></i>
                                        <span>{{__('Orders')}}</span>
                                    </a>
                                </li>
                                <li class="nav-item cool-link {{$currentRouteName=='user_list'? 'active' : ''}}">
                                    <a href="{{route('user_list',['locale'=>app()->getLocale()],false )}}"
                                       class="nav-link menu-link {{$currentRouteName=='user_list'? 'active' : ''}}"
                                       role="button">
                                        <i class="ri-user-2-fill mx-2"></i>
                                        <span>{{__('User list')}}</span>
                                    </a>
                                </li>
                                <li class="nav-item cool-link {{$currentRouteName=='items_index'? 'active' : ''}}">
                                    <a href="{{route('items_index',['locale'=>app()->getLocale()],false )}}"
                                       class="nav-link menu-link {{$currentRouteName=='items_index'? 'active' : ''}}"
                                       role="button">
                                        <i class="ri-shopping-bag-fill mx-2"></i>
                                        <span>{{__('Items')}}</span>
                                    </a>
                                </li>
                                <li class="nav-item cool-link {{$currentRouteName=='target_index'? 'active' : ''}}">
                                    <a href="{{route('target_index',['locale'=>app()->getLocale(),'idSurvey'=>request()->route("idSurvey")],false )}}"
                                       class="nav-link menu-link {{$currentRouteName=='target_index'? 'active' : ''}}"
                                       role="button">
                                        <i class="ri-pushpin-fill mx-2"></i>
                                        <span>{{__('Targets')}}</span>
                                    </a>
                                </li>
                                <li class="nav-item cool-link {{$currentRouteName=='surveys_index'? 'active' : ''}}">
                                    <a href="{{route('surveys_index',['locale'=>app()->getLocale()],false )}}"
                                       class="nav-link menu-link {{$currentRouteName=='surveys_index'? 'active' : ''}}"
                                       role="button">
                                        <i class="ri-pushpin-fill mx-2"></i>
                                        <span>{{__('Surveys')}}</span>
                                    </a>
                                </li>
                                <li class="nav-item cool-link {{$currentRouteName=='hashtags_index'? 'active' : ''}}">
                                    <a href="{{route('hashtags_index',['locale'=>app()->getLocale()],false )}}"
                                       class="nav-link menu-link {{$currentRouteName=='hashtags_index'? 'active' : ''}}"
                                       role="button">
                                        <i class="ri-hashtag mx-2"></i>
                                        <span>{{__('Hashtags')}}</span>
                                    </a>
                                </li>
                                <li class="nav-item cool-link {{$currentRouteName=='balances_index'? 'active' : ''}}">
                                    <a href="{{route('balances_index',['locale'=>app()->getLocale()],false )}}"
                                       class="nav-link menu-link {{$currentRouteName=='balances_index'? 'active' : ''}}"
                                       role="button">
                                        <i class="ri-wallet-fill mx-2"></i>
                                        <span>{{__('Balance operations')}}</span>
                                    </a>
                                </li>
                                <li class="nav-item cool-link {{$currentRouteName=='balances_categories_index'? 'active' : ''}}">
                                    <a href="{{route('balances_categories_index',['locale'=>app()->getLocale()],false )}}"
                                       class="nav-link menu-link {{$currentRouteName=='balances_categories_index'? 'active' : ''}}"
                                       role="button">
                                        <i class="ri-wallet-3-fill mx-2"></i>
                                        <span>{{__('Balance categories')}}</span>
                                    </a>
                                </li>
                                <li class="nav-item cool-link {{$currentRouteName==$sidebarRoleArray[0]? 'active' : ''}}">
                                    <a href="{{route($sidebarRoleArray[0], app()->getLocale(),false)}}"
                                       class="nav-link menu-link">
                                        <i class="ri-shield-user-line mx-2"></i>
                                        <span>{{ __('Role') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item cool-link {{$currentRouteName==$sidebarRoleArray[1]? 'active' : ''}}">
                                    <a href="{{route($sidebarRoleArray[1], app()->getLocale(),false)}}"
                                       class="nav-link menu-link">
                                        <i class="ri-user-add-line mx-2"></i>
                                        <span>{{ __('Assign') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item cool-link {{$currentRouteName==$sidebarDashboardsArray[0]? 'active' : ''}}">
                                    <a href="{{route($sidebarDashboardsArray[0], app()->getLocale(),false)}}"
                                       class="nav-link menu-link">
                                        <i class="ri-settings-line mx-2"></i>
                                        <span>{{ __('General Settings') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item cool-link {{$currentRouteName==$sidebarDashboardsArray[1]? 'active' : ''}}">
                                    <a href="{{route($sidebarDashboardsArray[1], app()->getLocale(),false)}}"
                                       class="nav-link menu-link">
                                        <i class="ri-money-dollar-box-line mx-2"></i>
                                        <span>{{ __('Amounts Settings') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item cool-link {{$currentRouteName==$sidebarDashboardsArray[2]? 'active' : ''}}">
                                    <a href="{{route($sidebarDashboardsArray[2], app()->getLocale(),false)}}"
                                       class="nav-link menu-link">
                                        <i class="ri-coins-line mx-2"></i>
                                        <span>{{ __('HA amount Settings') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item cool-link {{$currentRouteName==$sidebarShareSoldArray[0]? 'active' : ''}}">
                                    <a href="{{route($sidebarShareSoldArray[0], app()->getLocale(),false)}}"
                                       class="nav-link menu-link">
                                        <i class="ri-stock-line mx-2"></i>
                                        <span>{{ __('Shares sold') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item cool-link {{$currentRouteName==$sidebarShareSoldArray[1]? 'active' : ''}}">
                                    <a href="{{route($sidebarShareSoldArray[1], app()->getLocale(),false)}}"
                                       class="nav-link menu-link">
                                        <i class="ri-bar-chart-box-line mx-2"></i>
                                        <span>{{ __('Shares sold market status') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item cool-link {{$currentRouteName==$sidebarShareSoldArray[2]? 'active' : ''}}">
                                    <a href="{{route($sidebarShareSoldArray[2], app()->getLocale(),false)}}"
                                       class="nav-link menu-link">
                                        <i class="ri-exchange-funds-line mx-2"></i>
                                        <span>{{ __('Shares sold recent transaction') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item cool-link {{$currentRouteName==$sidebarRequestsArray[0]? 'active' : ''}}">
                                    <a href="{{route($sidebarRequestsArray[0], app()->getLocale(),false)}}"
                                       class="nav-link menu-link">
                                        <i class="ri-user-received-line mx-2"></i>
                                        <span>{{ __('Commited investors') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item cool-link {{$currentRouteName==$sidebarRequestsArray[1]? 'active' : ''}}">
                                    <a href="{{route($sidebarRequestsArray[1], app()->getLocale(),false)}}"
                                       class="nav-link menu-link">
                                        <i class="ri-user-2-fill mx-2"></i>
                                        <span>{{ __('Instructor') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item cool-link {{$currentRouteName==$sidebarRequestsArray[2]? 'active' : ''}}">
                                    <a href="{{route($sidebarRequestsArray[2], app()->getLocale(),false)}}"
                                       class="nav-link menu-link">
                                        <i class="ri-shield-keyhole-line mx-2"></i>
                                        <span>{{ __('Identification') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item cool-link {{$currentRouteName==$sidebarTranslateArray[0]? 'active' : ''}}">
                                    <a href="{{route($sidebarTranslateArray[0], app()->getLocale(),false)}}"
                                       class="nav-link menu-link">
                                        <i class="ri-translate-2 mx-2"></i>
                                        <span>{{ __('Translate') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item cool-link {{$currentRouteName==$sidebarTranslateArray[1]? 'active' : ''}}">
                                    <a href="{{route($sidebarTranslateArray[1], app()->getLocale(),false)}}"
                                       class="nav-link menu-link">
                                        <i class="ri-database-2-line mx-2"></i>
                                        <span>{{ __('Translate model data') }}</span>
                                    </a>
                                </li>
                            </ul>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const siteMenuBtn = document.getElementById('site-menu-btn');
        const closeMenuBtns = document.querySelectorAll('.close-menu-btn');
        const sidebarMenuContainer = document.getElementById('sidebar-menu-container');

        if (siteMenuBtn && sidebarMenuContainer) {
            siteMenuBtn.addEventListener('click', function (e) {
                e.preventDefault();
                if (sidebarMenuContainer.style.display === 'none') {
                    sidebarMenuContainer.style.display = 'block';
                    document.body.classList.add('modal-open');
                } else {
                    sidebarMenuContainer.style.display = 'none';
                    document.body.classList.remove('modal-open');
                }
            });

            if (closeMenuBtns.length > 0) {
                closeMenuBtns.forEach(function (btn) {
                    btn.addEventListener('click', function (e) {
                        e.preventDefault();
                        sidebarMenuContainer.style.display = 'none';
                        document.body.classList.remove('modal-open');
                    });
                });
            }

            // Close menu when clicking on the overlay (dark background)
            sidebarMenuContainer.addEventListener('click', function (e) {
                if (e.target === sidebarMenuContainer) {
                    sidebarMenuContainer.style.display = 'none';
                    document.body.classList.remove('modal-open');
                }
            });
        }
    });
</script>
