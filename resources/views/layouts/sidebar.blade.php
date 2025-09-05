@php use App\Models\User; @endphp
<div>
    <div class="app-menu navbar-menu">
        <div class="navbar-brand-box">
            <a href="{{route('home',app()->getLocale(),false)}}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ Vite::asset('resources/images/logo-sm.png') }}" alt="" height="22">
            </span>
                <span class="logo-lg">
                <img src="{{ Vite::asset('resources/images/logo-dark.png') }}" alt="" height="35px">
            </span>
            </a>
            <a id="MyHover" href="{{route('home',app()->getLocale(),false)}}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ Vite::asset('resources/images/logo-sm.png') }}" alt="" height="40">
            </span>
                <span class="logo-lg">
                <img src="{{ Vite::asset('resources/images/logo-light.png') }}" alt="" height="30">
            </span>
            </a>
            <button onclick="testClick()" style="cursor: text" type="button"
                    class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover">
                <i class="ri-record-circle-line"></i>
            </button>
        </div>
        <div id="scrollbar">
            <div class="container-fluid">
                <div id="two-column-menu">
                </div>
                @php
                    $currentRouteName = Route::currentRouteName();
                    $sidebarBusinessArray=['business_hub_trading','business_hub_additional_income','business_hub_be_influencer','business_hub_job_opportunities'];
                    $sidebarSavingsArray=['savings_user_purchase','savings_recuperation_history'];
                    $sidebarBiographyArray=['biography_academic_background','biography_career_experience','biography_hard_skills','biography_soft_skills','biography_personal_characterization','biography_NCDPersonality','biography_sensory_representation_system','biography_MBTI','biography_e_business_card','biography_generating_pdf_report'];
                    $sidebarArchiveArray=['surveys_archive','deals_archive'];
                    $sidebarRoleArray=['role_index','role_assign'];
                    $sidebarDashboardsArray=['configuration_setting','configuration_amounts','configuration_ha'];
                    $sidebarShareSoldArray=['shares_sold_dashboard','shares_sold_market_status','shares_sold_recent_transaction'];
                    $sidebarTranslateArray=['translate','translate_model_data'];
                    $sidebarRequestsArray=['requests_commited_investors','requests_instructor','requests_identification'];
                @endphp
                <ul class="navbar-nav" id="navbar-nav">
                    <li class="nav-item cool-link {{$currentRouteName=='home'? 'active' : ''}}">
                        <a data-name="home" href="{{route('home',app()->getLocale(),false)}}"
                           class="nav-link menu-link {{$currentRouteName=='home'? 'active' : ''}}"
                           role="button">
                            <i class="ri-home-gear-fill"></i>
                            <span>{{ __('Home') }}</span>
                        </a>
                    </li>
                    <li class="nav-item cool-link {{$currentRouteName=='account'? 'active' : ''}}">
                        <a data-name="account" href="{{route('account',app()->getLocale(),false )}}"
                           class="nav-link menu-link {{$currentRouteName=='account'? 'active' : ''}}"
                           role="button">
                            <i class="ri-account-pin-circle-fill"></i>
                            <span>{{ __('Account') }}</span>
                        </a>
                    </li>
                    <li class="nav-item cool-link {{$currentRouteName=='contacts'? 'active' : ''}}">
                        <a data-name="contacts" href="{{route('contacts',app()->getLocale(),false)}}"
                           class="nav-link menu-link {{$currentRouteName=='contacts'? 'active' : ''}}"
                           role="button">
                            <i class="ri-contacts-fill"></i>
                            <span>{{ __('Contact') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link menu-link {{!in_array($currentRouteName, $sidebarBusinessArray)? 'collapsed' : 'active'}}"
                           href="#sidebarBusiness" data-bs-toggle="collapse"
                           role="button"
                           aria-expanded="{{in_array($currentRouteName, $sidebarBusinessArray)? 'true' : 'false'}}"
                           aria-controls="sidebarBusiness">
                            <i class="ri-dashboard-fill"></i> <span>{{ __('Business Hub') }}</span>
                        </a>
                        <div
                            class="menu-dropdown collapse {{in_array($currentRouteName, $sidebarBusinessArray)? 'show' : ''}}"
                            id="sidebarBusiness">

                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item cool-link {{$currentRouteName==$sidebarBusinessArray[0]? 'active' : ''}}">
                                    <a href="{{route($sidebarBusinessArray[0], app()->getLocale(),false)}}"
                                       class="nav-link">{{ __('Trading') }}</a>
                                </li>

                                <li class="nav-item cool-link {{$currentRouteName==$sidebarBusinessArray[1]? 'active' : ''}}">
                                    <a href="{{route($sidebarBusinessArray[1], app()->getLocale(),false)}}"
                                       class="nav-link"
                                    >{{ __('Additional Income') }}</a>
                                </li>
                                <li class="nav-item cool-link {{$currentRouteName==$sidebarBusinessArray[2]? 'active' : ''}}">
                                    <a href="{{route($sidebarBusinessArray[2], app()->getLocale(),false)}}"
                                       class="nav-link">
                                        {{ __('Be Influencer') }}
                                    </a>
                                </li>

                                <li class="nav-item cool-link {{$currentRouteName==$sidebarBusinessArray[3]? 'active' : ''}}">
                                    <a href="{{route($sidebarBusinessArray[3], app()->getLocale(),false)}}"
                                       class="nav-link">
                                        {{ __('Job Opportunities') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link menu-link {{!in_array($currentRouteName,$sidebarSavingsArray)? 'collapsed' : 'active'}}"
                           href="#sidebarSavings" data-bs-toggle="collapse"
                           role="button"
                           aria-expanded="{{in_array($currentRouteName, $sidebarSavingsArray)? 'true' : 'false'}}"
                           aria-controls="sidebarSavings">
                            <i class="ri-vip-diamond-fill"></i>
                            <span>
                                {{ __('My Savings') }}
                            </span>
                        </a>
                        <div
                            class="menu-dropdown collapse {{in_array($currentRouteName,$sidebarSavingsArray)? 'show' : ''}}"
                            id="sidebarSavings">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item cool-link {{$currentRouteName==$sidebarSavingsArray[0]? 'active' : ''}}">
                                    <a href="{{route($sidebarSavingsArray[0], app()->getLocale(),false)}}"
                                       class="nav-link ">{{ __('Purchase history') }}</a>
                                </li>
                                <li class="nav-item cool-link {{$currentRouteName==$sidebarSavingsArray[1]? 'active' : ''}}">
                                    <a href="{{route($sidebarSavingsArray[1], app()->getLocale(),false)}}"
                                       class="nav-link">
                                        {{ __('Historique_recuperation') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link menu-link {{!in_array($currentRouteName, $sidebarBiographyArray)? 'collapsed' : 'active'}}"
                           href="#sidebarBiography" data-bs-toggle="collapse"
                           role="button"
                           aria-expanded="{{in_array($currentRouteName, $sidebarBiographyArray)? 'true' : 'false'}}"
                           aria-controls="sidebarBiography">
                            <i class="ri-briefcase-4-fill"></i>
                            <span>
                                {{ __('Biography') }}
                            </span>
                        </a>
                        <div
                            class="menu-dropdown collapse {{in_array($currentRouteName, $sidebarBiographyArray)? 'show' : ''}}"
                            id="sidebarBiography">

                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item d-none cool-link {{$currentRouteName==$sidebarBiographyArray[0]? 'active' : ''}} ">
                                    <a href="{{route($sidebarBiographyArray[0], app()->getLocale(),false)}}"
                                       class="nav-link ">
                                        {{ __('Academic Background') }}
                                    </a>
                                </li>
                                <li class="nav-item d-none cool-link {{$currentRouteName==$sidebarBiographyArray[1]? 'active' : ''}}">
                                    <a href="{{route($sidebarBiographyArray[1], app()->getLocale(),false)}}"
                                       class="nav-link ">
                                        {{ __('Career Experience') }}
                                    </a>
                                </li>
                                <li class="nav-item d-none  cool-link {{$currentRouteName==$sidebarBiographyArray[2]? 'active' : ''}}">
                                    <a href="{{route($sidebarBiographyArray[2], app()->getLocale(),false)}}"
                                       class="nav-link ">
                                        {{ __('Hard Skills') }}
                                    </a>
                                </li>
                                <li class="nav-item d-none  cool-link {{$currentRouteName==$sidebarBiographyArray[3]? 'active' : ''}}">
                                    <a href="{{route($sidebarBiographyArray[3], app()->getLocale(),false)}}"
                                       class="nav-link ">
                                        {{ __('Soft Skills') }}
                                    </a>
                                </li>
                                <li class="nav-item d-none  cool-link {{$currentRouteName==$sidebarBiographyArray[4]? 'active' : ''}}">
                                    <a href="{{route($sidebarBiographyArray[4], app()->getLocale(),false)}}"
                                       class="nav-link ">
                                        {{ __('Personal Characterization') }}
                                    </a>
                                </li>
                                <li class="nav-item  d-none cool-link {{$currentRouteName==$sidebarBiographyArray[5]? 'active' : ''}}">
                                    <a href="{{route($sidebarBiographyArray[5], app()->getLocale(),false)}}"
                                       class="nav-link ">
                                        {{ __('CD Personality') }}
                                    </a>
                                </li>
                                <li class="nav-item  d-none cool-link {{$currentRouteName==$sidebarBiographyArray[6]? 'active' : ''}}">
                                    <a href="{{route($sidebarBiographyArray[6], app()->getLocale(),false)}}"
                                       class="nav-link ">
                                        {{ __('Sensory Representation System') }}
                                    </a>
                                </li>
                                <li class="nav-item  d-none cool-link {{$currentRouteName==$sidebarBiographyArray[7]? 'active' : ''}}">
                                    <a href="{{route($sidebarBiographyArray[7], app()->getLocale(),false)}}"
                                       class="nav-link ">
                                        {{ __('Myers-Briggs Type Indicator (MBTI)') }}
                                    </a>
                                </li>
                                <li class="nav-item cool-link {{$currentRouteName==$sidebarBiographyArray[8]? 'active' : ''}}">
                                    <a href="{{route($sidebarBiographyArray[8], app()->getLocale(),false)}}"
                                       class="nav-link ">
                                        {{ __('e-Business Card (EBC)') }}
                                    </a>
                                </li>
                                <li class="nav-item  d-none cool-link {{$currentRouteName==$sidebarBiographyArray[9]? 'active' : ''}}">
                                    <a href="{{route($sidebarBiographyArray[9], app()->getLocale(),false)}}"
                                       class="nav-link ">
                                        {{ __('Rapport PDF') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item cool-link {{$currentRouteName=='financial_transaction'? 'active' : ''}}">
                        <a href="{{route('financial_transaction',app()->getLocale(),false)}}"
                           class="nav-link menu-link {{$currentRouteName=='financial_transaction'? 'active' : ''}}"
                           role="button">
                            <i class="ri-bank-fill"></i> <span>{{ __('Exchange') }}</span>
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
                            <i class="ri-stack-line"></i>
                            <span>{{__('Hobbies')}}</span>
                        </a>
                    </li>
                    <li class="nav-item cool-link {{$currentRouteName=='news_index'? 'active' : ''}}">
                        <a href="{{route('news_index',app()->getLocale(),false )}}"
                           class="nav-link menu-link {{$currentRouteName=='news_index'? 'active' : ''}}"
                           role="button">
                            <i class="ri-newspaper-fill"></i>
                            <span>{{__('News')}}</span>
                        </a>
                    </li>
                    @if(\Core\Models\Platform::canCheckDeals(auth()->user()->id))
                        <li class="nav-item cool-link {{$currentRouteName=='deals_index'? 'active' : ''}}">
                            <a href="{{route('deals_index',app()->getLocale(),false )}}"
                               class="nav-link menu-link {{$currentRouteName=='deals_index'? 'active' : ''}}"
                               role="button">
                                <i class="ri-honour-line"></i>
                                <span>{{__('Deals')}}</span>
                            </a>
                        </li>
                    @endIf
                    <li class="nav-item">
                        <a class="nav-link menu-link {{!in_array($currentRouteName, $sidebarArchiveArray)? 'collapsed' : 'active'}}"
                           href="#sidebarArchive" data-bs-toggle="collapse"
                           role="button"
                           aria-expanded="{{in_array($currentRouteName, $sidebarArchiveArray)? 'true' : 'false'}}"
                           aria-controls="sidebarArchive">
                            <i class="ri-archive-fill"></i>
                            <span>{{ __('Archives') }}</span>
                        </a>
                        <div
                            class="menu-dropdown collapse {{in_array($currentRouteName,$sidebarArchiveArray)? 'show' : ''}}"
                            id="sidebarArchive">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item cool-link {{$currentRouteName==$sidebarArchiveArray[0]? 'active' : ''}}">
                                    <a href="{{route($sidebarArchiveArray[0], app()->getLocale(),false)}}"
                                       class="nav-link"
                                    >{{ __('Survey Archive') }}</a>
                                </li>
                                <li class="nav-item cool-link {{$currentRouteName==$sidebarArchiveArray[1]? 'active' : ''}}">
                                    <a href="{{route($sidebarArchiveArray[1], app()->getLocale(),false)}}"
                                       class="nav-link"
                                    >{{ __('Deal Archive') }}</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item cool-link {{$currentRouteName=='coupon_injector_runner'? 'active' : ''}}">
                        <a href="{{route('coupon_injector_runner',app()->getLocale(),false )}}"
                           class="nav-link menu-link {{$currentRouteName=='coupon_injector_runner'? 'active' : ''}}"
                           role="button">
                            <i class="ri-coupon-2-fill"></i>
                            <span>{{__('Balance inj. Coupons')}}</span>
                        </a>
                    </li>

                    <li class="nav-item cool-link {{$currentRouteName=='settlement_tracking'? 'active' : ''}}">
                        <a href="{{route('settlement_tracking',app()->getLocale(),false )}}"
                           class="nav-link menu-link {{$currentRouteName=='settlement_tracking'? 'active' : ''}}"
                           role="button">
                            <i class="ri-money-dollar-circle-fill"></i>
                            <span>{{__('Settlement tracking')}}</span>
                        </a>
                    </li>
                    @if(User::isSuperAdmin())
                        <li class="menu-title">
                            <span data-key="t-menu">{{ __('SUPER ADMIN MENU') }}</span>
                        </li>
                        <li class="nav-item cool-link {{$currentRouteName=='business_sector_index'? 'active' : ''}}">
                            <a href="{{route('business_sector_index',app()->getLocale(),false )}}"
                               class="nav-link menu-link {{$currentRouteName=='business_sector_index'? 'active' : ''}}"
                               role="button">
                                <i class="bx bx-category-alt"></i>
                                <span>{{__('Business sector')}}</span>
                            </a>
                        </li>
                        <li class="nav-item cool-link {{$currentRouteName=='platform_index'? 'active' : ''}}">
                            <a href="{{route('platform_index',['locale'=>request()->route("locale")],false )}}"
                               class="nav-link menu-link {{$currentRouteName=='platform_index'? 'active' : ''}}"
                               role="button">
                                <i class="ri-git-repository-private-fill"></i>
                                <span>{{__('Platform')}}</span>
                            </a>
                        </li>
                        <li class="nav-item cool-link {{$currentRouteName=='coupon_index'? 'active' : ''}}">
                            <a href="{{route('coupon_index',app()->getLocale(),false )}}"
                               class="nav-link menu-link {{$currentRouteName=='coupon_index'? 'active' : ''}}"
                               role="button">
                                <i class="ri-coupon-3-fill"></i>
                                <span>{{__('Coupon')}}</span>
                            </a>
                        </li>
                        <li class="nav-item cool-link {{$currentRouteName=='coupon_injector_index'? 'active' : ''}}">
                            <a href="{{route('coupon_injector_index',app()->getLocale(),false )}}"
                               class="nav-link menu-link {{$currentRouteName=='coupon_injector_index'? 'active' : ''}}"
                               role="button">
                                <i class="ri-coupon-4-fill"></i>
                                <span>{{__('Balance injector')}}</span>
                            </a>
                        </li>
                        <li class="nav-item cool-link {{$currentRouteName=='orders_index'? 'active' : ''}}">
                            <a href="{{route('orders_index',['locale'=>request()->route("locale")],false )}}"
                               class="nav-link menu-link {{$currentRouteName=='orders_index'? 'active' : ''}}"
                               role="button">
                                <i class="ri-shopping-cart-fill"></i>
                                <span>{{__('Orders')}}</span>
                            </a>
                        </li>
                        <li class="nav-item cool-link {{$currentRouteName=='items_index'? 'active' : ''}}">
                            <a href="{{route('items_index',['locale'=>request()->route("locale")],false )}}"
                               class="nav-link menu-link {{$currentRouteName=='items_index'? 'active' : ''}}"
                               role="button">
                                <i class="ri-shopping-bag-fill"></i>
                                <span>{{__('Items')}}</span>
                            </a>
                        </li>
                        <li class="nav-item cool-link {{$currentRouteName=='target_index'? 'active' : ''}}">
                            <a href="{{route('target_index',['locale'=>request()->route("locale"),'idSurvey'=>request()->route("idSurvey")],false )}}"
                               class="nav-link menu-link {{$currentRouteName=='target_index'? 'active' : ''}}"
                               role="button">
                                <i class="ri-pushpin-fill"></i>
                                <span>{{__('Targets')}}</span>
                            </a>
                        </li>
                        <li class="nav-item cool-link {{$currentRouteName=='balances_index'? 'active' : ''}}">
                            <a href="{{route('balances_index',['locale'=>request()->route("locale")],false )}}"
                               class="nav-link menu-link {{$currentRouteName=='balances_index'? 'active' : ''}}"
                               role="button">
                                <i class="ri-wallet-fill"></i>
                                <span>{{__('Balance operations')}}</span>
                            </a>
                        </li>
                        <li class="nav-item cool-link {{$currentRouteName=='balances_categories_index'? 'active' : ''}}">
                            <a href="{{route('balances_categories_index',['locale'=>request()->route("locale")],false )}}"
                               class="nav-link menu-link {{$currentRouteName=='balances_categories_index'? 'active' : ''}}"
                               role="button">
                                <i class="ri-wallet-3-fill"></i>
                                <span>{{__('Balance categories')}}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link menu-link {{!in_array($currentRouteName, $sidebarRoleArray)? 'collapsed' : 'active'}}"
                               href="#sidebarRole" data-bs-toggle="collapse"
                               role="button"
                               aria-expanded="{{in_array($currentRouteName, $sidebarRoleArray)? 'true' : 'false'}}"
                               aria-controls="sidebarRole">
                                <i class="ri-user-settings-fill"></i>
                                <span>{{ __('Role') }}</span>
                            </a>
                            <div
                                class="menu-dropdown collapse {{in_array($currentRouteName,$sidebarRoleArray)? 'show' : ''}}"
                                id="sidebarRole">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item cool-link {{$currentRouteName==$sidebarRoleArray[0]? 'active' : ''}}">
                                        <a href="{{route($sidebarRoleArray[0], app()->getLocale(),false)}}"
                                           class="nav-link"
                                        >{{ __('Role') }}</a>
                                    </li>
                                    <li class="nav-item cool-link {{$currentRouteName==$sidebarRoleArray[1]? 'active' : ''}}">
                                        <a href="{{route($sidebarRoleArray[1], app()->getLocale(),false)}}"
                                           class="nav-link"
                                        >{{ __('Assign') }}</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item cool-link {{$currentRouteName=='surveys_index'? 'active' : ''}}">
                            <a href="{{route('surveys_index',['locale'=>request()->route("locale"),'idSurvey'=>request()->route("idSurvey")],false )}}"
                               class="nav-link menu-link {{$currentRouteName=='target_index'? 'active' : ''}}"
                               role="button">
                                <i class="ri-bookmark-fill"></i>
                                <span>{{__('Surveys')}}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link menu-link {{!in_array($currentRouteName, $sidebarDashboardsArray)? 'collapsed' : 'active'}}"
                               href="#sidebarDashboards"
                               data-bs-toggle="collapse"
                               role="button"
                               aria-expanded="{{in_array($currentRouteName, $sidebarDashboardsArray)? 'true' : 'false'}}"
                               aria-controls="sidebarDashboards">
                                <i class="ri-settings-fill"></i> <span
                                >{{ __('Settings') }}</span>
                            </a>
                            <div
                                class="menu-dropdown collapse {{in_array($currentRouteName, $sidebarDashboardsArray)? 'show' : ''}}"
                                id="sidebarDashboards">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item cool-link {{$currentRouteName==$sidebarDashboardsArray[0]? 'active' : ''}}">
                                        <a href="{{route($sidebarDashboardsArray[0], app()->getLocale(),false)}}"
                                           class="nav-link">{{ __('General Settings') }}</a>
                                    </li>
                                    <li class="nav-item cool-link {{$currentRouteName==$sidebarDashboardsArray[1]? 'active' : ''}}">
                                        <a href="{{route($sidebarDashboardsArray[1], app()->getLocale(),false)}}"
                                           class="nav-link"
                                        >{{ __('Amounts Settings') }}</a>
                                    </li>
                                    <li class="nav-item cool-link {{$currentRouteName==$sidebarDashboardsArray[2]? 'active' : ''}}">
                                        <a href="{{route($sidebarDashboardsArray[2], app()->getLocale(),false)}}"
                                           class="nav-link">{{ __('HA amount Settings') }}</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item cool-link {{$currentRouteName=='user_list'? 'active' : ''}}">
                            <a href="{{route('user_list', app()->getLocale(),false)}}"
                               class="nav-link menu-link {{$currentRouteName=='user_list'? 'active' : ''}}"
                               role="button">
                                <i class="ri-user-fill"></i>
                                <span>{{ __('Users') }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link menu-link {{!in_array($currentRouteName, $sidebarShareSoldArray)? 'collapsed' : 'active'}}"
                               href="#sidebarShareSold" data-bs-toggle="collapse"
                               role="button"
                               aria-expanded="{{in_array($currentRouteName, $sidebarShareSoldArray)? 'true' : 'false'}}"
                               aria-controls="sidebarShareSold">
                                <i class="ri-dashboard-fill"></i> <span
                                >{{ __('Shares sold') }}</span>
                            </a>
                            <div
                                class="menu-dropdown collapse {{in_array($currentRouteName, $sidebarShareSoldArray)? 'show' : ''}}"
                                id="sidebarShareSold">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item cool-link {{$currentRouteName==$sidebarShareSoldArray[0]? 'active' : ''}}">
                                        <a href="{{route($sidebarShareSoldArray[0], app()->getLocale(),false)}}"
                                           class="nav-link">{{ __('Shares sold') }}</a>
                                    </li>
                                    <li class="nav-item cool-link {{$currentRouteName==$sidebarShareSoldArray[1]? 'active' : ''}}">
                                        <a href="{{route($sidebarShareSoldArray[1], app()->getLocale(),false)}}"
                                           class="nav-link"
                                        >{{ __('Shares sold market status') }}</a>
                                    </li>
                                    <li class="nav-item cool-link {{$currentRouteName==$sidebarShareSoldArray[2]? 'active' : ''}}">
                                        <a href="{{route($sidebarShareSoldArray[2], app()->getLocale(),false)}}"
                                           class="nav-link"
                                        >{{ __('Shares sold recent transaction') }}</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item cool-link {{$currentRouteName=='stat_countrie'? 'active' : ''}}">
                            <a href="{{route('stat_countrie', app()->getLocale(),false)}}"
                               class="nav-link menu-link {{$currentRouteName=='stat_countrie'? 'active' : ''}}"
                               role="button">
                                <i class="ri-flag-fill"></i>
                                <span>{{ __('StatByCountry') }}</span>
                            </a>
                        </li>
                        <li class="nav-item cool-link {{$currentRouteName=='countries_management'? 'active' : ''}}">
                            <a href="{{route('api_settings', app()->getLocale(),false)}}"
                               class="nav-link menu-link disabled {{$currentRouteName=='countries_management'? 'active' : ''}}"
                               role="button">
                                <i class="ri-team-fill"></i>
                                <span>{{ __('representatives Management') }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link menu-link {{!in_array($currentRouteName, $sidebarRequestsArray)? 'collapsed' : 'active'}}"
                               href="#sidebarRequests" data-bs-toggle="collapse"
                               role="button"
                               aria-expanded="{{in_array($currentRouteName, $sidebarRequestsArray)? 'true' : 'false'}}"
                               aria-controls="sidebarRequests">
                                <i class="ri-git-pull-request-line"></i>
                                <span
                                >{{ __('Requests') }}</span>
                            </a>
                            <div
                                class="menu-dropdown collapse {{in_array($currentRouteName,$sidebarRequestsArray)? 'show' : ''}}"
                                id="sidebarRequests">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item cool-link {{$currentRouteName==$sidebarRequestsArray[0]? 'active' : ''}}">
                                        <a href="{{route($sidebarRequestsArray[0], app()->getLocale(),false)}}"
                                           class="nav-link"
                                        >{{ __('Commited investors') }}</a>
                                    </li>
                                    <li class="nav-item cool-link {{$currentRouteName==$sidebarRequestsArray[1]? 'active' : ''}}">
                                        <a href="{{route($sidebarRequestsArray[1], app()->getLocale(),false)}}"
                                           class="nav-link"
                                        >{{ __('Instructor') }}</a>
                                    </li>
                                    <li class="nav-item cool-link {{$currentRouteName==$sidebarRequestsArray[2]? 'active' : ''}}">
                                        <a href="{{route($sidebarRequestsArray[2], app()->getLocale(),false)}}"
                                           class="nav-link"
                                        >{{ __('Identification') }}</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item cool-link {{$currentRouteName=='countries_management'? 'active' : ''}}">
                            <a href="{{route('countries_management', app()->getLocale(),false)}}"
                               class="nav-link menu-link {{$currentRouteName=='countries_management'? 'active' : ''}}"
                               role="button">
                                <i class="ri-globe-fill"></i>
                                <span>{{ __('Countries Management') }}</span>
                            </a>
                        </li>
                    @endif
                    @if(User::isSuperAdmin())
                        <li class="nav-item">
                            <a class="nav-link menu-link {{!in_array($currentRouteName, $sidebarTranslateArray)? 'collapsed' : 'active'}}"
                               href="#sidebarTranslate" data-bs-toggle="collapse"
                               role="button"
                               aria-expanded="{{in_array($currentRouteName, $sidebarTranslateArray)? 'true' : 'false'}}"
                               aria-controls="sidebarTranslate">
                                <i class="ri-global-fill"></i> <span
                                >{{ __('Translation') }}</span>
                            </a>
                            <div
                                class="menu-dropdown collapse {{in_array($currentRouteName, $sidebarTranslateArray)? 'show' : ''}}"
                                id="sidebarTranslate">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item cool-link {{$currentRouteName==$sidebarTranslateArray[0]? 'active' : ''}}">
                                        <a href="{{route($sidebarTranslateArray[0], app()->getLocale(),false)}}"
                                           class="nav-link">{{ __('Translate') }}</a>
                                    </li>
                                    <li class="nav-item cool-link {{$currentRouteName==$sidebarTranslateArray[1]? 'active' : ''}}">
                                        <a href="{{route($sidebarTranslateArray[1], app()->getLocale(),false)}}"
                                           class="nav-link"
                                        >{{ __('Translate model data') }}</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
        <div class="sidebar-background"></div>
    </div>
    <div class="vertical-overlay">
    </div>
    <script type="module">
        var sidebarBusinessArray = {!! json_encode($sidebarBusinessArray) !!};
        var sidebarSavingsArray = {!! json_encode($sidebarSavingsArray) !!};
        var sidebarBiographyArray = {!! json_encode($sidebarBiographyArray) !!};
        var sidebarArchiveArray = {!! json_encode($sidebarArchiveArray) !!};
        var sidebarRoleArray = {!! json_encode($sidebarRoleArray) !!};
        var sidebarDashboardsArray = {!! json_encode($sidebarDashboardsArray) !!};
        var sidebarShareSoldArray = {!! json_encode($sidebarShareSoldArray) !!};
        var sidebarTranslateArray = {!! json_encode($sidebarTranslateArray) !!};
        var sidebarRequestsArray = {!! json_encode($sidebarRequestsArray) !!};

        function showDropDownMenu(dropDownId) {
            $('#' + dropDownId).addClass('show');
            $('#' + dropDownId).parent().find('a.menu-link').attr('aria-expanded', 'true');
            $('#' + dropDownId).parent().find('a.menu-link').addClass('collapse');
        }

        function hideDropDownMenu(dropDownId) {
            $('#' + dropDownId).removeClass('show');
            $('#' + dropDownId).parent().find('a.menu-link').attr('aria-expanded', 'false');
            $('#' + dropDownId).parent().find('a.menu-link').removeClass('collapse');
        }

        function init(theArray) {
            var currentRouteName = location.pathname.substring(4).replaceAll("/", '_').replaceAll("-", '_');

            $('#navbar-nav li').removeClass('active');
            $('#navbar-nav li a').removeClass('active');

            $('#navbar-nav a[href="' + location.pathname + '"]').addClass('active');
            $('#navbar-nav a[href="' + location.pathname + '"]').parent().addClass('active');


            for (const dropDownId of theArray) {
                hideDropDownMenu(dropDownId)
            }

            if (sidebarArchiveArray.includes(currentRouteName)) {
                showDropDownMenu('sidebarArchive')
            }
            if (sidebarRoleArray.includes(currentRouteName)) {
                showDropDownMenu('sidebarRole')
            }

            if (sidebarDashboardsArray.includes(currentRouteName)) {
                showDropDownMenu('sidebarDashboards')
            }

            if (sidebarShareSoldArray.includes(currentRouteName)) {
                showDropDownMenu('sidebarShareSold')
            }

            if (sidebarTranslateArray.includes(currentRouteName)) {
                showDropDownMenu('sidebarTranslate')
            }

            if (sidebarBusinessArray.includes(currentRouteName)) {
                showDropDownMenu('sidebarBusiness')
            }

            if (sidebarSavingsArray.includes(currentRouteName)) {
                showDropDownMenu('sidebarSavings')
            }

            if (sidebarBiographyArray.includes(currentRouteName)) {
                showDropDownMenu('sidebarBiography')
            }

            if (sidebarRequestsArray.includes(currentRouteName)) {
                showDropDownMenu('sidebarRequests')
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
            init(['sidebarArchive', 'sidebarDashboards', 'sidebarShareSold', 'sidebarTranslate', 'sidebarBusiness', 'sidebarSavings', 'sidebarBiography', 'sidebarRequests'])
        });
    </script>
</div>
