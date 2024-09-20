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
                    $sidebarBusinessArray=['business_hub_trading','business_hub_user_running_business','business_hub_be_influencer','business_hub_job_opportunities'];
                    $sidebarSavingsArray=['savings_user_purchase','savings_recuperation_history'];
                    $sidebarBiographyArray=['biography_academic_background','biography_career_experience','biography_hard_skills','biography_soft_skills','biography_personal_characterization','biography_NCDPersonality','biography_sensory_representation_system','biography_MBTI','biography_e_business_card','biography_generating_pdf_report'];
                    $sidebarSurveyArray=['surveys_index','surveys_archive'];
                    $sidebarDashboardsArray=['configuration_setting','configuration_bo','configuration_ha','configuration_amounts'];
                    $sidebarShareSoldArray=['shares_sold_dashboard','shares_sold_market_status','shares_sold_recent_transaction'];
                    $sidebarTranslateArray=['translate','translate_model_data'];
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
                                <li class="nav-item cool-link {{$currentRouteName=='business_hub_trading'? 'active' : ''}}">
                                    <a href="{{route('business_hub_trading', app()->getLocale(),false)}}"
                                       class="nav-link disabled">{{ __('Trading') }}</a>
                                </li>

                                <li class="nav-item cool-link {{$currentRouteName=='business_hub_user_running_business'? 'active' : ''}}">
                                    <a href="{{route('business_hub_user_running_business', app()->getLocale(),false)}}"
                                       class="nav-link"
                                    >{{ __('Additional Income') }}</a>
                                </li>
                                <li class="nav-item cool-link {{$currentRouteName=='business_hub_user_running_business'? 'active' : ''}}">
                                    <a href="{{route('business_hub_be_influencer', app()->getLocale(),false)}}"
                                       class="nav-link">
                                        {{ __('Be Influencer') }}
                                    </a>
                                </li>

                                <li class="nav-item cool-link {{$currentRouteName=='business_hub_job_opportunities'? 'active' : ''}}">
                                    <a href="{{route('business_hub_job_opportunities', app()->getLocale(),false)}}"
                                       class="nav-link disabled">
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
                                <li class="nav-item cool-link {{$currentRouteName=='savings_user_purchase'? 'active' : ''}} disabled">
                                    <a href="{{route('savings_user_purchase', app()->getLocale(),false)}}"
                                       class="nav-link disabled">{{ __('Purchase history') }}</a>
                                </li>
                                <li class="nav-item cool-link {{$currentRouteName=='savings_recuperation_history'? 'active' : ''}}">
                                    <a href="{{route('savings_recuperation_history', app()->getLocale(),false)}}"
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
                                <li class="nav-item cool-link {{$currentRouteName=='biography_academic_background'? 'active' : ''}} disabled">
                                    <a href="{{route('biography_academic_background', app()->getLocale(),false)}}"
                                       class="nav-link disabled">
                                        {{ __('Academic Background') }}
                                    </a>
                                </li>
                                <li class="nav-item cool-link {{$currentRouteName=='biography_career_experience'? 'active' : ''}}">
                                    <a href="{{route('biography_career_experience', app()->getLocale(),false)}}"
                                       class="nav-link disabled">
                                        {{ __('Career Experience') }}
                                    </a>
                                </li>
                                <li class="nav-item cool-link {{$currentRouteName=='biography_hard_skills'? 'active' : ''}}">
                                    <a href="{{route('biography_hard_skills', app()->getLocale(),false)}}"
                                       class="nav-link disabled">
                                        {{ __('Hard Skills') }}
                                    </a>
                                </li>
                                <li class="nav-item cool-link {{$currentRouteName=='biography_soft_skills'? 'active' : ''}}">
                                    <a href="{{route('biography_soft_skills', app()->getLocale(),false)}}"
                                       class="nav-link disabled">
                                        {{ __('Soft Skills') }}
                                    </a>
                                </li>
                                <li class="nav-item cool-link {{$currentRouteName=='biography_personal_characterization'? 'active' : ''}}">
                                    <a href="{{route('biography_personal_characterization', app()->getLocale(),false)}}"
                                       class="nav-link disabled">
                                        {{ __('Personal Characterization') }}
                                    </a>
                                </li>
                                <li class="nav-item cool-link {{$currentRouteName=='biography_NCDPersonality'? 'active' : ''}}">
                                    <a href="{{route('biography_NCDPersonality', app()->getLocale(),false)}}"
                                       class="nav-link disabled">
                                        {{ __('CD Personality') }}
                                    </a>
                                </li>
                                <li class="nav-item cool-link {{$currentRouteName=='biography_sensory_representation_system'? 'active' : ''}}">
                                    <a href="{{route('biography_sensory_representation_system', app()->getLocale(),false)}}"
                                       class="nav-link disabled">
                                        {{ __('Sensory Representation System') }}
                                    </a>
                                </li>
                                <li class="nav-item cool-link {{$currentRouteName=='biography_MBTI'? 'active' : ''}}">
                                    <a href="{{route('biography_MBTI', app()->getLocale(),false)}}"
                                       class="nav-link disabled">
                                        {{ __('Myers-Briggs Type Indicator (MBTI)') }}
                                    </a>
                                </li>
                                <li class="nav-item cool-link {{$currentRouteName=='biography_e_business_card'? 'active' : ''}}">
                                    <a href="{{route('biography_e_business_card', app()->getLocale(),false)}}"
                                       class="nav-link disabled">
                                        {{ __('e-Business Card (EBC)') }}
                                    </a>
                                </li>
                                <li class="nav-item cool-link {{$currentRouteName=='biography_generating_pdf_report'? 'active' : ''}}">
                                    <a href="{{route('biography_generating_pdf_report', app()->getLocale(),false)}}"
                                       class="nav-link disabled">
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
                    <li style="margin-top: -20px" class="nav-item">
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

                    <li class="nav-item">
                        <a class="nav-link menu-link {{!in_array($currentRouteName, $sidebarSurveyArray)? 'collapsed' : 'active'}}"
                           href="#sidebarSurvey" data-bs-toggle="collapse"
                           role="button"
                           aria-expanded="{{in_array($currentRouteName, $sidebarSurveyArray)? 'true' : 'false'}}"
                           aria-controls="sidebarSurvey">
                            <i class="ri-bookmark-fill"></i>
                            <span
                            >{{ __('Surveys') }}</span>
                        </a>
                        <div
                            class="menu-dropdown collapse {{in_array($currentRouteName,$sidebarSurveyArray)? 'show' : ''}}"
                            id="sidebarSurvey">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item cool-link {{$currentRouteName=='surveys_index'? 'active' : ''}}">
                                    <a href="{{route('surveys_index', app()->getLocale(),false)}}"
                                       class="nav-link">{{ __('Surveys') }}</a>
                                </li>
                                <li class="nav-item cool-link {{$currentRouteName=='surveys_archive'? 'active' : ''}}">
                                    <a href="{{route('surveys_archive', app()->getLocale(),false)}}"
                                       class="nav-link"
                                    >{{ __('Archive') }}</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item cool-link {{$currentRouteName=='description'? 'active' : ''}}">
                        <a href="{{route('description',app()->getLocale(),false )}}"
                           class="nav-link menu-link {{$currentRouteName=='description'? 'active' : ''}} disabled"
                           role="button">
                            <i class="fa-solid fa-pen-fancy"></i>
                            <span>{{__('User guide')}}</span>
                        </a>
                    </li>
                    @if(auth()->user()->getRoleNames()->first() ==User::SUPER_ADMIN_ROLE_NAME)
                        <li class="menu-title">
                            <span data-key="t-menu">{{ __('SUPER ADMIN MENU') }}</span>
                        </li>
                        @if(auth()->user()->getRoleNames()->first() ==User::SUPER_ADMIN_ROLE_NAME)
                            <li class="nav-item cool-link {{$currentRouteName=='target_index'? 'active' : ''}}">
                                <a href="{{route('target_index',['locale'=>request()->route("locale"),'idSurvey'=>request()->route("idSurvey")],false )}}"
                                   class="nav-link menu-link {{$currentRouteName=='target_index'? 'active' : ''}}"
                                   role="button">
                                    <i class="ri-pushpin-fill"></i>
                                    <span>{{__('Targets')}}</span>
                                </a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link menu-link {{!in_array($currentRouteName, $sidebarDashboardsArray)? 'collapsed' : 'active'}}"
                               href="#sidebarDashboards"
                               data-bs-toggle="collapse"
                               role="button"
                               aria-expanded="{{in_array($currentRouteName, $sidebarDashboardsArray)? 'true' : 'false'}}"

                               aria-controls="sidebarDashboards">
                                <i class="ri-dashboard-2-line"></i> <span
                                >{{ __('Settings') }}</span>
                            </a>
                            <div
                                class="menu-dropdown collapse {{in_array($currentRouteName, $sidebarDashboardsArray)? 'show' : ''}}"
                                id="sidebarDashboards">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item cool-link {{$currentRouteName=='configuration_setting'? 'active' : ''}}">
                                        <a href="{{route('configuration_setting', app()->getLocale(),false)}}"
                                           class="nav-link">{{ __('General Settings') }}</a>
                                    </li>
                                    <li class="nav-item cool-link {{$currentRouteName=='configuration_bo'? 'active' : ''}}">
                                        <a href="{{route('configuration_bo', app()->getLocale(),false)}}"
                                           class="nav-link"
                                        >{{ __('BO Settings') }}</a>
                                    </li>
                                    <li class="nav-item cool-link {{$currentRouteName=='configuration_amounts'? 'active' : ''}}">
                                        <a href="{{route('configuration_amounts', app()->getLocale(),false)}}"
                                           class="nav-link">{{ __('Amounts Settings') }}</a>
                                    </li>
                                    <li class="nav-item cool-link {{$currentRouteName=='configuration_ha'? 'active' : ''}}">
                                        <a href="{{route('configuration_ha', app()->getLocale(),false)}}"
                                           class="nav-link">{{ __('HA Settings') }}</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item cool-link {{$currentRouteName=='edit_admin'? 'active' : ''}}">
                            <a href="{{route('edit_admin', app()->getLocale(),false)}}"
                               class="nav-link menu-link {{$currentRouteName=='edit_admin'? 'active' : ''}}"
                               role="button">
                                <i class="ri-user-settings-line"></i>
                                <span>{{ __('Administrators Management') }}</span>
                            </a>
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
                                    <li class="nav-item cool-link {{$currentRouteName=='shares_sold_dashboard'? 'active' : ''}}">
                                        <a href="{{route('shares_sold_dashboard', app()->getLocale(),false)}}"
                                           class="nav-link">{{ __('Shares sold') }}</a>
                                    </li>
                                    <li class="nav-item cool-link {{$currentRouteName=='shares_sold_market_status'? 'active' : ''}}">
                                        <a href="{{route('shares_sold_market_status', app()->getLocale(),false)}}"
                                           class="nav-link"
                                        >{{ __('Shares sold market status') }}</a>
                                    </li>
                                    <li class="nav-item cool-link {{$currentRouteName=='shares_sold_recent_transaction'? 'active' : ''}}">
                                        <a href="{{route('shares_sold_recent_transaction', app()->getLocale(),false)}}"
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
                                <i class="ri-user-settings-line"></i>
                                <span>{{ __('StatByCountry') }}</span>
                            </a>
                        </li>
                        <li class="nav-item cool-link {{$currentRouteName=='countries_management'? 'active' : ''}}">
                            <a href="{{route('api_settings', app()->getLocale(),false)}}"
                               class="nav-link menu-link disabled {{$currentRouteName=='countries_management'? 'active' : ''}}"
                               role="button">
                                <i class="ri-settings-line"></i>
                                <span>{{ __('representatives Management') }}</span>
                            </a>
                        </li>
                        <li class="nav-item cool-link {{$currentRouteName=='identification_request'? 'active' : ''}}">
                            <a href="{{route('identification_request', app()->getLocale(),false)}}"
                               class="nav-link menu-link {{$currentRouteName=='identification_request'? 'active' : ''}}"
                               role="button">
                                <i class="ri-git-pull-request-line"></i>
                                <span>{{ __('Identification Requests') }}</span>
                            </a>
                        </li>
                        <li class="nav-item cool-link {{$currentRouteName=='countries_management'? 'active' : ''}}">
                            <a href="{{route('countries_management', app()->getLocale(),false)}}"
                               class="nav-link menu-link {{$currentRouteName=='countries_management'? 'active' : ''}}"
                               role="button">
                                <i class="ri-flag-line"></i>
                                <span>{{ __('Countries Management') }}</span>
                            </a>
                        </li>
                    @endif
                    @if(auth()->user()->getRoleNames()->first() ==User::SUPER_ADMIN_ROLE_NAME)
                        <li class="nav-item">
                            <a class="nav-link menu-link {{!in_array($currentRouteName, $sidebarTranslateArray)? 'collapsed' : 'active'}}"
                               href="#sidebarTranslate" data-bs-toggle="collapse"
                               role="button"
                               aria-expanded="{{in_array($currentRouteName, $sidebarTranslateArray)? 'true' : 'false'}}"
                               aria-controls="sidebarTranslate">
                                <i class="ri-dashboard-fill"></i> <span
                                >{{ __('Translation') }}</span>
                            </a>
                            <div
                                class="menu-dropdown collapse {{in_array($currentRouteName, $sidebarTranslateArray)? 'show' : ''}}"
                                id="sidebarTranslate">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item cool-link {{$currentRouteName=='translate'? 'active' : ''}}">
                                        <a href="{{route('translate', app()->getLocale(),false)}}"
                                           class="nav-link">{{ __('Translate') }}</a>
                                    </li>
                                    <li class="nav-item cool-link {{$currentRouteName=='translate_model_data'? 'active' : ''}}">
                                        <a href="{{route('translate_model_data', app()->getLocale(),false)}}"
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
    <script data-turbolinks-eval="false" type="module">
        var sidebarBusinessArray = {!! json_encode($sidebarBusinessArray) !!};
        var sidebarSavingsArray = {!! json_encode($sidebarSavingsArray) !!};
        var sidebarBiographyArray = {!! json_encode($sidebarBiographyArray) !!};
        var sidebarSurveyArray = {!! json_encode($sidebarSurveyArray) !!};
        var sidebarDashboardsArray = {!! json_encode($sidebarDashboardsArray) !!};
        var sidebarShareSoldArray = {!! json_encode($sidebarShareSoldArray) !!};
        var sidebarTranslateArray = {!! json_encode($sidebarTranslateArray) !!};

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
            console.log(currentRouteName)

            $('#navbar-nav li').removeClass('active');
            $('#navbar-nav li a').removeClass('active');

            $('#navbar-nav a[href="' + location.pathname + '"]').addClass('active');
            $('#navbar-nav a[href="' + location.pathname + '"]').parent().addClass('active');


            for (const dropDownId of theArray) {
                hideDropDownMenu(dropDownId)
            }

            if (sidebarSurveyArray.includes(currentRouteName)) {
                showDropDownMenu('sidebarSurvey')
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
        }

        $(document).on('ready turbolinks:load', function () {
            init(['sidebarSurvey', 'sidebarDashboards', 'sidebarShareSold', 'sidebarTranslate', 'sidebarBusiness', 'sidebarSavings', 'sidebarBiography'])
        });
    </script>
</div>
