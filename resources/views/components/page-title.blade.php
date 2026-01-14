@php
    use App\Models\User;
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
                            $crumbs[] = ['label' => (string) $it, 'url' => null];
                        }
                    }
                }
@endphp
<div class="row">
    <div id="page-title-box" class="col-12 page-title-box-modern my-2">
        <nav id="nav-breadcrumb" class="d-flex align-items-center" aria-label="{{ __('Breadcrumb') }}">
            <ol class="breadcrumb-modern m-0 flex-grow-1">
                <li class="breadcrumb-item-modern">
                    <a href="{{ route('home', app()->getLocale()) }}" title="{{ __('To Home') }}" class="breadcrumb-link-modern">
                        <i class="ri-home-7-line" aria-hidden="true"></i>
                        <span class="visually-hidden">{{ __('Home') }}</span>
                    </a>
                </li>
                @if($crumbs)
                    @foreach($crumbs as $index => $crumb)
                        @php $isLast = $index === count($crumbs) - 1; @endphp
                        <li class="breadcrumb-item-modern @if($isLast) active @endif" @if($isLast) aria-current="page" @endif>
                            <span class="breadcrumb-separator">/</span>
                            @if(!$isLast && !empty($crumb['url']) && $crumb['url'] !== '#')
                                <a href="{{ $crumb['url'] }}" class="breadcrumb-link-modern">{!! $crumb['label'] !!}</a>
                            @else
                                <span class="breadcrumb-text-modern">{!! $crumb['label'] !!}</span>
                            @endif
                        </li>
                    @endforeach
                @else
                    <li class="breadcrumb-item-modern active" aria-current="page">
                        <span class="breadcrumb-separator">/</span>
                        <span class="breadcrumb-text-modern">{!! $pageTitle !!}</span>
                    </li>
                @endif
            </ol>

            <div class="breadcrumb-actions d-flex align-items-center gap-2">
                @if(!empty($helpUrl ?? null) && $helpUrl !== '#')
                    <a href="{{ $helpUrl }}" title="{{ __('Check the help page') }}"
                       class="btn-icon-modern btn-help">
                        <i class="ri-question-line" aria-hidden="true"></i>
                        <span class="visually-hidden">{{ __('Help') }}</span>
                    </a>
                @endif

                @if(User::isSuperAdmin())
                    <a href="#" id="admin-menu-btn" title="{{ __('Admin menu') }}"
                       class="btn-icon-modern btn-admin">
                        <i class="ri-admin-line" aria-hidden="true"></i>
                        <span class="visually-hidden">{{ __('Admin Menu') }}</span>
                    </a>
                @endif

                <a href="#" id="site-menu-btn" title="{{ __('Site menu') }}"
                   class="btn-icon-modern btn-menu">
                    <i class="ri-menu-line" aria-hidden="true"></i>
                    <span class="visually-hidden">{{ __('Menu') }}</span>
                </a>
            </div>
        </nav>
    </div>
    <div class="col-12 menu-modern-container mb-2" id="user-menu-container">
        <div class="menu-modern-card">
            <div class="menu-modern-header menu-user">
                <div class="menu-header-content">
                    <i class="ri-user-line"></i>
                    <h6 class="mb-0">{{ __('User Menu') }}</h6>
                </div>
                <button type="button" class="btn-close-modern close-user-menu-btn"
                        title="{{ __('Close menu') }}" aria-label="{{ __('Close') }}">
                    <i class="ri-close-line"></i>
                </button>
            </div>
            <div class="menu-modern-body">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">
                    <div class="col">
                        <a data-name="home" href="{{route('home',app()->getLocale(),false)}}"
                           class="menu-link-modern {{$currentRouteName=='home'? 'active' : ''}}"
                           role="button">
                            <i class="ri-home-gear-fill"></i>
                            <span>{{ __('Home') }}</span>
                        </a>
                    </div>
                    <div class="col">
                        <a data-name="account" href="{{route('account',app()->getLocale(),false )}}"
                           class="menu-link-modern {{$currentRouteName=='account'? 'active' : ''}}"
                           role="button">
                            <i class="ri-account-pin-circle-fill"></i>
                            <span>{{ __('Account') }}</span>
                        </a>
                    </div>
                    <div class="col">
                        <a data-name="change_password" href="{{route('change_password',app()->getLocale(),false )}}"
                           class="menu-link-modern {{$currentRouteName=='change_password'? 'active' : ''}}"
                           role="button">
                            <i class="ri-lock-password-line"></i>
                            <span>{{ __('Change password') }}</span>
                        </a>
                    </div>
                    <div class="col">
                        <a data-name="identification" href="{{route('identification',app()->getLocale(),false )}}"
                           class="menu-link-modern {{$currentRouteName=='identification'? 'active' : ''}}"
                           role="button">
                            <i class="ri-shield-check-line"></i>
                            <span>{{ __('Identifications') }}</span>
                        </a>
                    </div>
                    <div class="col">
                        <a data-name="contacts" href="{{route('contacts_index',app()->getLocale(),false)}}"
                           class="menu-link-modern {{$currentRouteName=='contacts'? 'active' : ''}}"
                           role="button">
                            <i class="ri-contacts-fill"></i>
                            <span>{{ __('Contact') }}</span>
                        </a>
                    </div>
                    <div class="col">
                        <a href="{{route($sidebarBusinessArray[0], app()->getLocale(),false)}}"
                           class="menu-link-modern {{$currentRouteName==$sidebarBusinessArray[0]? 'active' : ''}}">
                            <i class="ri-line-chart-fill"></i>
                            <span>{{ __('Trading') }}</span>
                        </a>
                    </div>
                    <div class="col">
                        <a href="{{route($sidebarBusinessArray[1], app()->getLocale(),false)}}"
                           class="menu-link-modern {{$currentRouteName==$sidebarBusinessArray[1]? 'active' : ''}}">
                            <i class="ri-money-dollar-circle-line"></i>
                            <span>{{ __('Additional Income') }}</span>
                        </a>
                    </div>
                    <div class="col">
                        <a href="{{route($sidebarBusinessArray[2], app()->getLocale(),false)}}"
                           class="menu-link-modern {{$currentRouteName==$sidebarBusinessArray[2]? 'active' : ''}}">
                            <i class="ri-user-star-line"></i>
                            <span>{{ __('Be Influencer') }}</span>
                        </a>
                    </div>
                    <div class="col">
                        <a href="{{route($sidebarBusinessArray[3], app()->getLocale(),false)}}"
                           class="menu-link-modern {{$currentRouteName==$sidebarBusinessArray[3]? 'active' : ''}}">
                            <i class="ri-briefcase-line"></i>
                            <span>{{ __('Job Opportunities') }}</span>
                        </a>
                    </div>
                    <div class="col">
                        <a href="{{route($sidebarSavingsArray[0], app()->getLocale(),false)}}"
                           class="menu-link-modern {{$currentRouteName==$sidebarSavingsArray[0]? 'active' : ''}}">
                            <i class="ri-shopping-bag-line"></i>
                            <span>{{ __('Purchase history') }}</span>
                        </a>
                    </div>
                    <div class="col">
                        <a href="{{route($sidebarSavingsArray[1], app()->getLocale(),false)}}"
                           class="menu-link-modern {{$currentRouteName==$sidebarSavingsArray[1]? 'active' : ''}}">
                            <i class="ri-history-line"></i>
                            <span>{{ __('Historique_recuperation') }}</span>
                        </a>
                    </div>
                    <div class="col">
                        <a href="{{route($sidebarBiographyArray[8], app()->getLocale(),false)}}"
                           class="menu-link-modern {{$currentRouteName==$sidebarBiographyArray[8]? 'active' : ''}}">
                            <i class="ri-sd-card-fill"></i>
                            <span>{{ __('e-Business Card (EBC)') }}</span>
                        </a>
                    </div>
                    <div class="col">
                        <a href="{{route('financial_transaction',app()->getLocale(),false)}}"
                           class="menu-link-modern {{$currentRouteName=='financial_transaction'? 'active' : ''}}"
                           role="button">
                            <i class="ri-bank-fill"></i>
                            <span>{{ __('Exchange') }}</span>
                        </a>
                    </div>
                    <div class="col">
                        <a href="{{route('hobbies',app()->getLocale(),false )}}"
                           class="menu-link-modern {{$currentRouteName=='hobbies'? 'active' : ''}}"
                           role="button">
                            <i class="ri-stack-line"></i>
                            <span>{{__('Hobbies')}}</span>
                        </a>
                    </div>
                    <div class="col">
                        <a href="{{route('news_index',app()->getLocale(),false )}}"
                           class="menu-link-modern {{$currentRouteName=='news_index'? 'active' : ''}}"
                           role="button">
                            <i class="ri-newspaper-fill"></i>
                            <span>{{__('News')}}</span>
                        </a>
                    </div>
                    <div class="col">
                        <a href="{{route('event_index',app()->getLocale(),false )}}"
                           class="menu-link-modern {{$currentRouteName=='event_index'? 'active' : ''}}"
                           role="button">
                            <i class="ri-calendar-event-fill"></i>
                            <span>{{__('Events')}}</span>
                        </a>
                    </div>
                    <div class="col">
                        <a href="{{route('coupon_injector_runner',app()->getLocale(),false )}}"
                           class="menu-link-modern {{$currentRouteName=='coupon_injector_runner'? 'active' : ''}}"
                           role="button">
                            <i class="ri-coupon-2-fill"></i>
                            <span>{{__('Balance inj. Coupons')}}</span>
                        </a>
                    </div>
                    <div class="col">
                        <a href="{{route('settlement_tracking',app()->getLocale(),false )}}"
                           class="menu-link-modern {{$currentRouteName=='settlement_tracking'? 'active' : ''}}"
                           role="button">
                            <i class="ri-money-dollar-circle-fill"></i>
                            <span>{{__('Settlement tracking')}}</span>
                        </a>
                    </div>
                    @if(\App\Models\Platform::havePartnerSpecialRole(auth()->user()->id))
                        <div class="col">
                            <a href="{{route('platform_index',['locale'=>app()->getLocale()],false )}}"
                               class="menu-link-modern {{$currentRouteName=='platform_index'? 'active' : ''}}"
                               role="button">
                                <i class="ri-git-repository-private-fill"></i>
                                <span>{{__('Platform')}}</span>
                            </a>
                        </div>
                    @endif
                    @if(\App\Models\Platform::havePartnerSpecialRole(auth()->user()->id))
                        <div class="col">
                            <a href="{{route('deals_index',['locale'=>app()->getLocale()],false )}}"
                               class="menu-link-modern {{$currentRouteName=='deals_index'? 'active' : ''}}"
                               role="button">
                                <i class="fas fa-handshake me-2"></i>
                                <span>{{__('Deals')}}</span>
                            </a>
                        </div>
                    @endif
                    @if(\App\Models\Platform::havePartnerSpecialRole(auth()->user()->id))
                        <div class="col">
                            <a href="{{route('partner_payment_index',['locale'=>app()->getLocale()],false )}}"
                               class="menu-link-modern {{in_array($currentRouteName, ['partner_payment_index', 'partner_payment_detail', 'partner_payment_manage']) ? 'active' : ''}}"
                               role="button">
                                <i class="ri-money-dollar-circle-line"></i>
                                <span>{{__('Partner Payments')}}</span>
                            </a>
                        </div>
                    @endif
                    <div class="col">
                        <a href="{{route('communication_board',app()->getLocale(),false)}}"
                           class="menu-link-modern {{$currentRouteName=='communication_board'? 'active' : ''}}"
                           role="button">
                            <i class="ri-message-2-fill"></i>
                            <span>{{__('Communication Board')}}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(User::isSuperAdmin())
        <div class="col-12 menu-modern-container mb-2" id="admin-menu-container">
            <div class="menu-modern-card">
                <div class="menu-modern-header menu-admin">
                    <div class="menu-header-content">
                        <i class="ri-admin-line"></i>
                        <h6 class="mb-0">{{ __('Admin Menu') }}</h6>
                    </div>
                    <button type="button" class="btn-close-modern close-admin-menu-btn"
                            title="{{ __('Close menu') }}" aria-label="{{ __('Close') }}">
                        <i class="ri-close-line"></i>
                    </button>
                </div>
                <div class="menu-modern-body">
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">
                        <div class="col">
                            <a href="{{route('business_sector_index',app()->getLocale(),false )}}"
                               class="menu-link-modern {{$currentRouteName=='$1'? 'active' : ''}}"
                               role="button">
                                <i class="bx bx-category-alt "></i>
                                <span>{{__('Business sector')}}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route('coupon_index',app()->getLocale(),false )}}"
                               class="menu-link-modern {{$currentRouteName=='$1'? 'active' : ''}}"
                               role="button">
                                <i class="ri-coupon-3-fill "></i>
                                <span>{{__('Coupon')}}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route('plan_label_index',app()->getLocale(),false )}}"
                               class="menu-link-modern {{$currentRouteName=='$1'? 'active' : ''}}"
                               role="button">
                                <i class="ri-percent-line "></i>
                                <span>{{__('Plan label')}}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route('coupon_injector_index',app()->getLocale(),false )}}"
                               class="menu-link-modern {{$currentRouteName=='$1'? 'active' : ''}}"
                               role="button">
                                <i class="ri-coupon-4-fill "></i>
                                <span>{{__('Balance injector')}}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route('orders_index',['locale'=>app()->getLocale()],false )}}"
                               class="menu-link-modern {{$currentRouteName=='$1'? 'active' : ''}}"
                               role="button">
                                <i class="ri-shopping-cart-fill "></i>
                                <span>{{__('Orders')}}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route('orders_dashboard',['locale'=>app()->getLocale()],false )}}"
                               class="menu-link-modern {{$currentRouteName=='$1'? 'active' : ''}}"
                               role="button">
                                <i class="ri-dashboard-line "></i>
                                <span>{{__('Order Dashboard')}}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route('user_list',['locale'=>app()->getLocale()],false )}}"
                               class="menu-link-modern {{$currentRouteName=='$1'? 'active' : ''}}"
                               role="button">
                                <i class="ri-user-2-fill "></i>
                                <span>{{__('User list')}}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route('users_stats',['locale'=>app()->getLocale()],false )}}"
                               class="menu-link-modern {{$currentRouteName=='$1'? 'active' : ''}}"
                               role="button">
                                <i class="ri-bar-chart-box-line "></i>
                                <span>{{__('Users Statistics')}}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route('items_index',['locale'=>app()->getLocale()],false )}}"
                               class="menu-link-modern {{$currentRouteName=='$1'? 'active' : ''}}"
                               role="button">
                                <i class="ri-shopping-bag-fill "></i>
                                <span>{{__('Items')}}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route('target_index',['locale'=>app()->getLocale(),'idSurvey'=>request()->route("idSurvey")],false )}}"
                               class="menu-link-modern {{$currentRouteName=='$1'? 'active' : ''}}"
                               role="button">
                                <i class="ri-pushpin-fill "></i>
                                <span>{{__('Targets')}}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route('surveys_index',['locale'=>app()->getLocale()],false )}}"
                               class="menu-link-modern {{$currentRouteName=='$1'? 'active' : ''}}"
                               role="button">
                                <i class="ri-pushpin-fill "></i>
                                <span>{{__('Surveys')}}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route('hashtags_index',['locale'=>app()->getLocale()],false )}}"
                               class="menu-link-modern {{$currentRouteName=='$1'? 'active' : ''}}"
                               role="button">
                                <i class="ri-hashtag "></i>
                                <span>{{__('Hashtags')}}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route('balances_index',['locale'=>app()->getLocale()],false )}}"
                               class="menu-link-modern {{$currentRouteName=='$1'? 'active' : ''}}"
                               role="button">
                                <i class="ri-wallet-fill "></i>
                                <span>{{__('Balance operations')}}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route('balances_add_cash',['locale'=>app()->getLocale()],false )}}"
                               class="menu-link-modern {{$currentRouteName=='balances_add_cash'? 'active' : ''}}"
                               role="button">
                                <i class="ri-money-dollar-circle-fill "></i>
                                <span>{{__('Add Cash Balance')}}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route('balances_categories_index',['locale'=>app()->getLocale()],false )}}"
                               class="menu-link-modern {{$currentRouteName=='$1'? 'active' : ''}}"
                               role="button">
                                <i class="ri-wallet-3-fill "></i>
                                <span>{{__('Balance categories')}}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route($sidebarRoleArray[0], app()->getLocale(),false)}}"
                               class="menu-link-modern {{$currentRouteName=='$1'? 'active' : ''}}">
                                <i class="ri-shield-user-line "></i>
                                <span>{{ __('Role') }}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route($sidebarRoleArray[1], app()->getLocale(),false)}}"
                               class="menu-link-modern {{$currentRouteName=='$1'? 'active' : ''}}">
                                <i class="ri-user-add-line "></i>
                                <span>{{ __('Assign') }}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route($sidebarDashboardsArray[0], app()->getLocale(),false)}}"
                               class="menu-link-modern {{$currentRouteName=='$1'? 'active' : ''}}">
                                <i class="ri-settings-line "></i>
                                <span>{{ __('General Settings') }}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route($sidebarDashboardsArray[1], app()->getLocale(),false)}}"
                               class="menu-link-modern {{$currentRouteName=='$1'? 'active' : ''}}">
                                <i class="ri-money-dollar-box-line "></i>
                                <span>{{ __('Amounts Settings') }}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route($sidebarDashboardsArray[2], app()->getLocale(),false)}}"
                               class="menu-link-modern {{$currentRouteName=='$1'? 'active' : ''}}">
                                <i class="ri-coins-line "></i>
                                <span>{{ __('H.A. amount Settings') }}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route($sidebarShareSoldArray[0], app()->getLocale(),false)}}"
                               class="menu-link-modern {{$currentRouteName=='$1'? 'active' : ''}}">
                                <i class="ri-stock-line "></i>
                                <span>{{ __('Shares sold') }}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route($sidebarShareSoldArray[1], app()->getLocale(),false)}}"
                               class="menu-link-modern {{$currentRouteName=='$1'? 'active' : ''}}">
                                <i class="ri-bar-chart-box-line "></i>
                                <span>{{ __('Shares sold market status') }}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route($sidebarShareSoldArray[2], app()->getLocale(),false)}}"
                               class="menu-link-modern {{$currentRouteName=='$1'? 'active' : ''}}">
                                <i class="ri-exchange-funds-line "></i>
                                <span>{{ __('Shares sold recent transaction') }}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route($sidebarRequestsArray[0], app()->getLocale(),false)}}"
                               class="menu-link-modern {{$currentRouteName=='$1'? 'active' : ''}}">
                                <i class="ri-user-received-line "></i>
                                <span>{{ __('Commited investors') }}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route($sidebarRequestsArray[1], app()->getLocale(),false)}}"
                               class="menu-link-modern {{$currentRouteName=='$1'? 'active' : ''}}">
                                <i class="ri-user-2-fill "></i>
                                <span>{{ __('Instructor') }}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route($sidebarRequestsArray[2], app()->getLocale(),false)}}"
                               class="menu-link-modern {{$currentRouteName=='$1'? 'active' : ''}}">
                                <i class="ri-shield-keyhole-line "></i>
                                <span>{{ __('Identification') }}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route($sidebarRequestsArray[3], app()->getLocale(),false)}}"
                               class="menu-link-modern {{$currentRouteName=='$1'? 'active' : ''}}">
                                <i class="ri-hand-heart-fill"></i>
                                <span>{{ __('Partner Requests') }}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route('sms_index', app()->getLocale(),false)}}"
                               class="menu-link-modern {{$currentRouteName=='$1'? 'active' : ''}}">
                                <i class="ri-message-2-fill "></i>
                                <span>{{ __('SMS') }}</span>
                            </a>
                        </div>

                        <div class="col">
                            <a href="{{route($sidebarTranslateArray[0], app()->getLocale(),false)}}"
                               class="menu-link-modern {{$currentRouteName=='$1'? 'active' : ''}}">
                                <i class="ri-translate-2 "></i>
                                <span>{{ __('Translate') }}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route($sidebarTranslateArray[1], app()->getLocale(),false)}}"
                               class="menu-link-modern {{$currentRouteName=='$1'? 'active' : ''}}">
                                <i class="ri-database-2-line "></i>
                                <span>{{ __('Translate model data') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const siteMenuBtn = document.getElementById('site-menu-btn');
        const adminMenuBtn = document.getElementById('admin-menu-btn');
        const userMenuContainer = document.getElementById('user-menu-container');
        const adminMenuContainer = document.getElementById('admin-menu-container');
        const closeUserMenuBtn = document.querySelector('.close-user-menu-btn');
        const closeAdminMenuBtn = document.querySelector('.close-admin-menu-btn');

        function closeAllMenus() {
            if (userMenuContainer) {
                userMenuContainer.classList.remove('show');
            }
            if (adminMenuContainer) {
                adminMenuContainer.classList.remove('show');
            }
        }

        if (siteMenuBtn && userMenuContainer) {
            siteMenuBtn.addEventListener('click', function (e) {
                e.preventDefault();
                if (adminMenuContainer && adminMenuContainer.classList.contains('show')) {
                    adminMenuContainer.classList.remove('show');
                }
                userMenuContainer.classList.toggle('show');
            });
        }

        if (adminMenuBtn && adminMenuContainer) {
            adminMenuBtn.addEventListener('click', function (e) {
                e.preventDefault();
                if (userMenuContainer && userMenuContainer.classList.contains('show')) {
                    userMenuContainer.classList.remove('show');
                }
                adminMenuContainer.classList.toggle('show');
            });
        }

        if (closeUserMenuBtn) {
            closeUserMenuBtn.addEventListener('click', function (e) {
                e.preventDefault();
                if (userMenuContainer) {
                    userMenuContainer.classList.remove('show');
                }
            });
        }

        if (closeAdminMenuBtn) {
            closeAdminMenuBtn.addEventListener('click', function (e) {
                e.preventDefault();
                if (adminMenuContainer) {
                    adminMenuContainer.classList.remove('show');
                }
            });
        }

        document.addEventListener('click', function (e) {
            const isClickInside = e.target.closest('#user-menu-container') ||
                e.target.closest('#admin-menu-container') ||
                e.target.closest('#site-menu-btn') ||
                e.target.closest('#admin-menu-btn');

            if (!isClickInside) {
                closeAllMenus();
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeAllMenus();
            }
        });
    });
</script>




