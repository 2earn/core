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
    <div id="page-title-box" class="col-12 page-title-box my-1 p-2 rounded">
        <nav id="nav-breadcrumb" class=" d-flex align-items-center" aria-label="{{ __('Breadcrumb') }}">
            <ol class="breadcrumb m-0 flex-grow-1">
                <li class="breadcrumb-item">
                    <a href="{{ route('home', app()->getLocale()) }}" title="{{ __('To Home') }}">
                        <i class="ri-home-7-line align-middle" aria-hidden="true"></i>
                        <span class="visually-hidden">{{ __('Home') }}</span>
                    </a>
                </li>
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
                    <li class="breadcrumb-item active" aria-current="page">
                        {!! $pageTitle !!}
                    </li>
                @endif
            </ol>

            @if(!empty($helpUrl ?? null) && $helpUrl !== '#')
                <a href="{{ $helpUrl }}" title="{{ __('Check the help page') }}"
                   class="ms-2 icon-square">
                    <i class="ri-question-line align-middle" aria-hidden="true"></i>
                    <span class="visually-hidden">{{ __('Help') }}</span>
                </a>
            @endif

            @if(User::isSuperAdmin())
                <a href="#" id="admin-menu-btn" title="{{ __('Admin menu') }}"
                   class="ms-2 icon-square">
                    <i class="ri-admin-line align-middle" aria-hidden="true"></i>
                    <span class="visually-hidden">{{ __('Admin Menu') }}</span>
                </a>
            @endif

            <a href="#" id="site-menu-btn" title="{{ __('Site menu') }}"
               class="ms-2 icon-square">
                <i class="ri-menu-line align-middle" aria-hidden="true"></i>
                <span class="visually-hidden">{{ __('Menu') }}</span>
            </a>
        </nav>
    </div>
    <div class="col-12 menu-fade-container mb-2 rounded-0" id="user-menu-container">
        <div class="card border-0 shadow-lg mt-2">
            <div
                class="card-header bg-gradient bg-primary text-white d-flex justify-content-between align-items-center py-2">
                <h6 class="mb-0">
                    <i class="ri-user-line me-2"></i>{{ __('User Menu') }}
                </h6>
                <button type="button" class="btn btn-sm text-danger close-user-menu-btn"
                        style="background: none; border: none; font-size: 1.5rem; line-height: 1; opacity: 0.8;"
                        title="{{ __('Close menu') }}" aria-label="{{ __('Close') }}">
                    <i class="ri-close-line"></i>
                </button>
            </div>
            <div class="card-body p-3" style="max-height: 70vh; overflow-y: auto;">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-2">
                    <div class="col">
                        <a data-name="home" href="{{route('home',app()->getLocale(),false)}}"
                           class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName=='home'? 'active bg-light' : ''}}"
                           role="button">
                            <i class="ri-home-gear-fill me-2"></i>
                            <span>{{ __('Home') }}</span>
                        </a>
                    </div>
                    <div class="col">
                        <a data-name="account" href="{{route('account',app()->getLocale(),false )}}"
                           class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName=='account'? 'active bg-light' : ''}}"
                           role="button">
                            <i class="ri-account-pin-circle-fill me-2"></i>
                            <span>{{ __('Account') }}</span>
                        </a>
                    </div>
                    <div class="col">
                        <a data-name="change_password" href="{{route('change_password',app()->getLocale(),false )}}"
                           class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName=='change_password'? 'active bg-light' : ''}}"
                           role="button">
                            <i class="ri-lock-password-line me-2"></i>
                            <span>{{ __('Change password') }}</span>
                        </a>
                    </div>
                    <div class="col">
                        <a data-name="identification" href="{{route('identification',app()->getLocale(),false )}}"
                           class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName=='identification'? 'active bg-light' : ''}}"
                           role="button">
                            <i class="ri-shield-check-line me-2"></i>
                            <span>{{ __('Identifications') }}</span>
                        </a>
                    </div>
                    <div class="col">
                        <a data-name="contacts" href="{{route('contacts_index',app()->getLocale(),false)}}"
                           class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName=='contacts'? 'active bg-light' : ''}}"
                           role="button">
                            <i class="ri-contacts-fill me-2"></i>
                            <span>{{ __('Contact') }}</span>
                        </a>
                    </div>
                    <div class="col">
                        <a href="{{route($sidebarBusinessArray[0], app()->getLocale(),false)}}"
                           class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName==$sidebarBusinessArray[0]? 'active bg-light' : ''}}">
                            <i class="ri-line-chart-fill me-2"></i>
                            <span>{{ __('Trading') }}</span>
                        </a>
                    </div>
                    <div class="col">
                        <a href="{{route($sidebarBusinessArray[1], app()->getLocale(),false)}}"
                           class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName==$sidebarBusinessArray[1]? 'active bg-light' : ''}}">
                            <i class="ri-money-dollar-circle-line me-2"></i>
                            <span>{{ __('Additional Income') }}</span>
                        </a>
                    </div>
                    <div class="col">
                        <a href="{{route($sidebarBusinessArray[2], app()->getLocale(),false)}}"
                           class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName==$sidebarBusinessArray[2]? 'active bg-light' : ''}}">
                            <i class="ri-user-star-line me-2"></i>
                            <span>{{ __('Be Influencer') }}</span>
                        </a>
                    </div>
                    <div class="col">
                        <a href="{{route($sidebarBusinessArray[3], app()->getLocale(),false)}}"
                           class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName==$sidebarBusinessArray[3]? 'active bg-light' : ''}}">
                            <i class="ri-briefcase-line me-2"></i>
                            <span>{{ __('Job Opportunities') }}</span>
                        </a>
                    </div>
                    <div class="col">
                        <a href="{{route($sidebarSavingsArray[0], app()->getLocale(),false)}}"
                           class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName==$sidebarSavingsArray[0]? 'active bg-light' : ''}}">
                            <i class="ri-shopping-bag-line me-2"></i>
                            <span>{{ __('Purchase history') }}</span>
                        </a>
                    </div>
                    <div class="col">
                        <a href="{{route($sidebarSavingsArray[1], app()->getLocale(),false)}}"
                           class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName==$sidebarSavingsArray[1]? 'active bg-light' : ''}}">
                            <i class="ri-history-line me-2"></i>
                            <span>{{ __('Historique_recuperation') }}</span>
                        </a>
                    </div>
                    <div class="col">
                        <a href="{{route($sidebarBiographyArray[8], app()->getLocale(),false)}}"
                           class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName==$sidebarBiographyArray[8]? 'active bg-light' : ''}}">
                            <i class="ri-id-card-line me-2"></i>
                            <span>{{ __('e-Business Card (EBC)') }}</span>
                        </a>
                    </div>
                    <div class="col">
                        <a href="{{route('financial_transaction',app()->getLocale(),false)}}"
                           class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName=='financial_transaction'? 'active bg-light' : ''}}"
                           role="button">
                            <i class="ri-bank-fill me-2"></i>
                            <span>{{ __('Exchange') }}</span>
                        </a>
                    </div>
                    <div class="col">
                        <a href="{{route('hobbies',app()->getLocale(),false )}}"
                           class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName=='hobbies'? 'active bg-light' : ''}}"
                           role="button">
                            <i class="ri-stack-line me-2"></i>
                            <span>{{__('Hobbies')}}</span>
                        </a>
                    </div>
                    <div class="col">
                        <a href="{{route('news_index',app()->getLocale(),false )}}"
                           class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName=='news_index'? 'active bg-light' : ''}}"
                           role="button">
                            <i class="ri-newspaper-fill me-2"></i>
                            <span>{{__('News')}}</span>
                        </a>
                    </div>
                    <div class="col">
                        <a href="{{route('event_index',app()->getLocale(),false )}}"
                           class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName=='event_index'? 'active bg-light' : ''}}"
                           role="button">
                            <i class="ri-calendar-event-fill me-2"></i>
                            <span>{{__('Events')}}</span>
                        </a>
                    </div>
                    @if(\Core\Models\Platform::canCheckDeals(auth()->user()->id))
                        <div class="col">
                            <a href="{{route('deals_index',app()->getLocale(),false )}}"
                               class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName=='deals_index'? 'active bg-light' : ''}}"
                               role="button">
                                <i class="ri-honour-line me-2"></i>
                                <span>{{__('Deals')}}</span>
                            </a>
                        </div>
                    @endIf
                    <div class="col">
                        <a href="{{route('coupon_injector_runner',app()->getLocale(),false )}}"
                           class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName=='coupon_injector_runner'? 'active bg-light' : ''}}"
                           role="button">
                            <i class="ri-coupon-2-fill me-2"></i>
                            <span>{{__('Balance inj. Coupons')}}</span>
                        </a>
                    </div>
                    <div class="col">
                        <a href="{{route('settlement_tracking',app()->getLocale(),false )}}"
                           class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName=='settlement_tracking'? 'active bg-light' : ''}}"
                           role="button">
                            <i class="ri-money-dollar-circle-fill me-2"></i>
                            <span>{{__('Settlement tracking')}}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(User::isSuperAdmin())
        <div class="menu-fade-container mb-2 rounded-0" id="admin-menu-container">
            <div class="card border-0 shadow-lg mt-2">
                <div
                    class="card-header bg-gradient bg-danger text-white d-flex justify-content-between align-items-center py-2">
                    <h6 class="mb-0">
                        <i class="ri-admin-line me-2"></i>{{ __('Admin Menu') }}
                    </h6>
                    <button type="button" class="btn btn-sm text-danger close-admin-menu-btn"
                            style="background: none; border: none; font-size: 1.5rem; line-height: 1; opacity: 0.8;"
                            title="{{ __('Close menu') }}" aria-label="{{ __('Close') }}">
                        <i class="ri-close-line"></i>
                    </button>
                </div>
                <div class="card-body p-3" style="max-height: 70vh; overflow-y: auto;">
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-2">
                        <div class="col">
                            <a href="{{route('business_sector_index',app()->getLocale(),false )}}"
                               class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName=='business_sector_index'? 'active bg-light' : ''}}"
                               role="button">
                                <i class="bx bx-category-alt me-2"></i>
                                <span>{{__('Business sector')}}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route('platform_index',['locale'=>app()->getLocale()],false )}}"
                               class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName=='platform_index'? 'active bg-light' : ''}}"
                               role="button">
                                <i class="ri-git-repository-private-fill me-2"></i>
                                <span>{{__('Platform')}}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route('deals_index',['locale'=>app()->getLocale()],false )}}"
                               class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName=='deals_index'? 'active bg-light' : ''}}"
                               role="button">
                                <i class="fas fa-handshake me-2 me-2"></i>
                                <span>{{__('Deals')}}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route('coupon_index',app()->getLocale(),false )}}"
                               class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName=='coupon_index'? 'active bg-light' : ''}}"
                               role="button">
                                <i class="ri-coupon-3-fill me-2"></i>
                                <span>{{__('Coupon')}}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route('plan_label_index',app()->getLocale(),false )}}"
                               class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName=='plan_label_index'? 'active bg-light' : ''}}"
                               role="button">
                                <i class="ri-percent-line me-2"></i>
                                <span>{{__('Plan label')}}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route('coupon_injector_index',app()->getLocale(),false )}}"
                               class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName=='coupon_injector_index'? 'active bg-light' : ''}}"
                               role="button">
                                <i class="ri-coupon-4-fill me-2"></i>
                                <span>{{__('Balance injector')}}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route('orders_index',['locale'=>app()->getLocale()],false )}}"
                               class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName=='orders_index'? 'active bg-light' : ''}}"
                               role="button">
                                <i class="ri-shopping-cart-fill me-2"></i>
                                <span>{{__('Orders')}}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route('orders_dashboard',['locale'=>app()->getLocale()],false )}}"
                               class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName=='order_dashboard'? 'active bg-light' : ''}}"
                               role="button">
                                <i class="ri-dashboard-line me-2"></i>
                                <span>{{__('Order Dashboard')}}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route('user_list',['locale'=>app()->getLocale()],false )}}"
                               class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName=='user_list'? 'active bg-light' : ''}}"
                               role="button">
                                <i class="ri-user-2-fill me-2"></i>
                                <span>{{__('User list')}}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route('users_stats',['locale'=>app()->getLocale()],false )}}"
                               class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName=='users_stats'? 'active bg-light' : ''}}"
                               role="button">
                                <i class="ri-bar-chart-box-line me-2"></i>
                                <span>{{__('Users Statistics')}}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route('items_index',['locale'=>app()->getLocale()],false )}}"
                               class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName=='items_index'? 'active bg-light' : ''}}"
                               role="button">
                                <i class="ri-shopping-bag-fill me-2"></i>
                                <span>{{__('Items')}}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route('target_index',['locale'=>app()->getLocale(),'idSurvey'=>request()->route("idSurvey")],false )}}"
                               class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName=='target_index'? 'active bg-light' : ''}}"
                               role="button">
                                <i class="ri-pushpin-fill me-2"></i>
                                <span>{{__('Targets')}}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route('surveys_index',['locale'=>app()->getLocale()],false )}}"
                               class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName=='surveys_index'? 'active bg-light' : ''}}"
                               role="button">
                                <i class="ri-pushpin-fill me-2"></i>
                                <span>{{__('Surveys')}}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route('hashtags_index',['locale'=>app()->getLocale()],false )}}"
                               class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName=='hashtags_index'? 'active bg-light' : ''}}"
                               role="button">
                                <i class="ri-hashtag me-2"></i>
                                <span>{{__('Hashtags')}}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route('balances_index',['locale'=>app()->getLocale()],false )}}"
                               class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName=='balances_index'? 'active bg-light' : ''}}"
                               role="button">
                                <i class="ri-wallet-fill me-2"></i>
                                <span>{{__('Balance operations')}}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route('balances_categories_index',['locale'=>app()->getLocale()],false )}}"
                               class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName=='balances_categories_index'? 'active bg-light' : ''}}"
                               role="button">
                                <i class="ri-wallet-3-fill me-2"></i>
                                <span>{{__('Balance categories')}}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route($sidebarRoleArray[0], app()->getLocale(),false)}}"
                               class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName==$sidebarRoleArray[0]? 'active bg-light' : ''}}">
                                <i class="ri-shield-user-line me-2"></i>
                                <span>{{ __('Role') }}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route($sidebarRoleArray[1], app()->getLocale(),false)}}"
                               class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName==$sidebarRoleArray[1]? 'active bg-light' : ''}}">
                                <i class="ri-user-add-line me-2"></i>
                                <span>{{ __('Assign') }}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route($sidebarDashboardsArray[0], app()->getLocale(),false)}}"
                               class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName==$sidebarDashboardsArray[0]? 'active bg-light' : ''}}">
                                <i class="ri-settings-line me-2"></i>
                                <span>{{ __('General Settings') }}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route($sidebarDashboardsArray[1], app()->getLocale(),false)}}"
                               class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName==$sidebarDashboardsArray[1]? 'active bg-light' : ''}}">
                                <i class="ri-money-dollar-box-line me-2"></i>
                                <span>{{ __('Amounts Settings') }}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route($sidebarDashboardsArray[2], app()->getLocale(),false)}}"
                               class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName==$sidebarDashboardsArray[2]? 'active bg-light' : ''}}">
                                <i class="ri-coins-line me-2"></i>
                                <span>{{ __('H.A. amount Settings') }}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route($sidebarShareSoldArray[0], app()->getLocale(),false)}}"
                               class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName==$sidebarShareSoldArray[0]? 'active bg-light' : ''}}">
                                <i class="ri-stock-line me-2"></i>
                                <span>{{ __('Shares sold') }}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route($sidebarShareSoldArray[1], app()->getLocale(),false)}}"
                               class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName==$sidebarShareSoldArray[1]? 'active bg-light' : ''}}">
                                <i class="ri-bar-chart-box-line me-2"></i>
                                <span>{{ __('Shares sold market status') }}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route($sidebarShareSoldArray[2], app()->getLocale(),false)}}"
                               class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName==$sidebarShareSoldArray[2]? 'active bg-light' : ''}}">
                                <i class="ri-exchange-funds-line me-2"></i>
                                <span>{{ __('Shares sold recent transaction') }}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route($sidebarRequestsArray[0], app()->getLocale(),false)}}"
                               class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName==$sidebarRequestsArray[0]? 'active bg-light' : ''}}">
                                <i class="ri-user-received-line me-2"></i>
                                <span>{{ __('Commited investors') }}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route($sidebarRequestsArray[1], app()->getLocale(),false)}}"
                               class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName==$sidebarRequestsArray[1]? 'active bg-light' : ''}}">
                                <i class="ri-user-2-fill me-2"></i>
                                <span>{{ __('Instructor') }}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route($sidebarRequestsArray[2], app()->getLocale(),false)}}"
                               class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName==$sidebarRequestsArray[2]? 'active bg-light' : ''}}">
                                <i class="ri-shield-keyhole-line me-2"></i>
                                <span>{{ __('Identification') }}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route('sms_index', app()->getLocale(),false)}}"
                               class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName=='sms_index'? 'active bg-light' : ''}}">
                                <i class="ri-message-2-fill me-2"></i>
                                <span>{{ __('SMS') }}</span>
                            </a>
                        </div>

                        <div class="col">
                            <a href="{{route($sidebarTranslateArray[0], app()->getLocale(),false)}}"
                               class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName==$sidebarTranslateArray[0]? 'active bg-light' : ''}}">
                                <i class="ri-translate-2 me-2"></i>
                                <span>{{ __('Translate') }}</span>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{route($sidebarTranslateArray[1], app()->getLocale(),false)}}"
                               class="nav-link menu-link p-1 rounded hover-bg {{$currentRouteName==$sidebarTranslateArray[1]? 'active bg-light' : ''}}">
                                <i class="ri-database-2-line me-2"></i>
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

