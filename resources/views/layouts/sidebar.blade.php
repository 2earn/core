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
                <ul class="navbar-nav" id="navbar-nav">
                    <li class="nav-item cool-link {{Route::currentRouteName()=='home'? 'active' : ''}}">
                        <a href="{{route('home',app()->getLocale(),false)}}"
                           class="nav-link menu-link {{Route::currentRouteName()=='home'? 'active' : ''}}"
                           role="button">
                            <i class="ri-home-gear-fill"></i>
                            <span>{{ __('Home') }}</span>
                        </a>
                    </li>
                    <li class="nav-item cool-link {{Route::currentRouteName()=='account'? 'active' : ''}}">
                        <a href="{{route('account',app()->getLocale(),false )}}"
                           class="nav-link menu-link {{Route::currentRouteName()=='account'? 'active' : ''}}"
                           role="button">
                            <i class="ri-account-pin-circle-fill"></i>
                            <span>{{ __('Account') }}</span>
                        </a>
                    </li>
                    <li class="nav-item cool-link {{Route::currentRouteName()=='contacts'? 'active' : ''}}">
                        <a href="{{route('contacts',app()->getLocale(),false)}}"
                           class="nav-link menu-link {{Route::currentRouteName()=='contacts'? 'active' : ''}}"
                           role="button">
                            <i class="ri-contacts-fill"></i>
                            <span>{{ __('Contact') }}</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link {{!in_array(Route::currentRouteName(), ['business_hub_user_running_business','business_hub_be_influencer','be_influencer_tree_evolution','tree_maintenance'])? 'collapsed' : 'active'}}"
                           href="#sidebarBusiness" data-bs-toggle="collapse"
                           role="button"
                           aria-expanded="{{in_array(Route::currentRouteName(), ['business_hub_user_running_business','business_hub_be_influencer'])? 'true' : 'false'}}"
                           aria-controls="sidebarBusiness">
                            <i class="ri-dashboard-fill"></i> <span
                                data-key="t-dashboards">{{ __('Business Hub') }}</span>
                        </a>
                        <div
                            class="menu-dropdown collapse {{in_array(Route::currentRouteName(), ['business_hub_trading','business_hub_be_influencer','be_influencer_tree_evolution','tree_maintenance','business_hub_job_opportunities'])? 'show' : ''}}"
                            id="sidebarBusiness">

                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item cool-link {{Route::currentRouteName()=='business_hub_trading'? 'active' : ''}}">
                                    <a href="{{route('business_hub_trading', app()->getLocale(),false)}}"
                                       class="nav-link" data-key="t-analytics">{{ __('Trading') }}</a>
                                </li>

                                <li class="nav-item cool-link {{Route::currentRouteName()=='business_hub_user_running_business'? 'active' : ''}}">
                                    <a href="{{route('business_hub_user_running_business', app()->getLocale(),false)}}"
                                       class="nav-link"
                                       data-key="t-analytics">{{ __('Additional Income') }}</a>
                                </li>
                                <li class="nav-item cool-link {{in_array(Route::currentRouteName(), ['business_hub_user_running_business','business_hub_be_influencer','be_influencer_tree_evolution','tree_maintenance','job_opportunities'])? ' active ' : ''}}">
                                    <a href="{{route('business_hub_be_influencer', app()->getLocale(),false)}}"
                                       class="nav-link"
                                       data-key="t-analytics">
                                        {{ __('Be Influencer') }}
                                    </a>
                                </li>

                                <li class="nav-item cool-link {{Route::currentRouteName()=='business_hub_job_opportunities'? 'active' : ''}}">
                                    <a href="{{route('business_hub_job_opportunities', app()->getLocale(),false)}}"
                                       class="nav-link "
                                       data-key="t-analytics">{{ __('Job Opportunities') }}</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link {{!in_array(Route::currentRouteName(), ['savings_user_purchase','savings_recuperation_history'])? 'collapsed' : 'active'}}"
                           href="#sidebarSavings" data-bs-toggle="collapse"
                           role="button"
                           aria-expanded="{{in_array(Route::currentRouteName(), ['savings_user_purchase','savings_recuperation_history'])? 'true' : 'false'}}"
                           aria-controls="sidebarSavings">
                            <i class="ri-vip-diamond-fill"></i> <span
                                data-key="t-dashboards">{{ __('My Savings') }}</span>
                        </a>
                        <div
                            class="menu-dropdown collapse {{in_array(Route::currentRouteName(), ['savings_user_purchase','savings_recuperation_history'])? 'show' : ''}}"
                            id="sidebarSavings">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item cool-link {{Route::currentRouteName()=='savings_user_purchase'? 'active' : ''}} disabled">
                                    <a href="{{route('savings_user_purchase', app()->getLocale(),false)}}"
                                       class="nav-link disabled" data-key="t-analytics">{{ __('Purchase history') }}</a>
                                </li>
                                <li class="nav-item cool-link {{Route::currentRouteName()=='savings_recuperation_history'? 'active' : ''}}">
                                    <a href="{{route('savings_recuperation_history', app()->getLocale(),false)}}"
                                       class="nav-link"
                                       data-key="t-analytics">{{ __('Historique_recuperation') }}</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link {{!in_array(Route::currentRouteName(), ['savings_user_purchase','savings_recuperation_history'])? 'collapsed' : 'active'}}"
                           href="#sidebarBiography" data-bs-toggle="collapse"
                           role="button"
                           aria-expanded="{{in_array(Route::currentRouteName(), ['savings_user_purchase','savings_recuperation_history'])? 'true' : 'false'}}"
                           aria-controls="sidebarBiography">
                            <i class="ri-briefcase-4-fill"></i> <span
                                data-key="t-dashboards">{{ __('Biography') }}</span>
                        </a>
                        <div
                            class="menu-dropdown collapse {{in_array(Route::currentRouteName(), ['savings_user_purchase','savings_recuperation_history'])? 'show' : ''}}"
                            id="sidebarBiography">

                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item cool-link {{Route::currentRouteName()=='biography_academic_background'? 'active' : ''}} disabled">
                                    <a href="{{route('biography_academic_background', app()->getLocale(),false)}}"
                                       class="nav-link disabled" data-key="t-analytics">
                                        {{ __('Academic Background') }}
                                    </a>
                                </li>
                                <li class="nav-item cool-link {{Route::currentRouteName()=='biography_career_experience'? 'active' : ''}}">
                                    <a href="{{route('biography_career_experience', app()->getLocale(),false)}}"
                                       class="nav-link" data-key="t-analytics">
                                        {{ __('Career Experience') }}
                                    </a>
                                </li>
                                <li class="nav-item cool-link {{Route::currentRouteName()=='biography_hard_skills'? 'active' : ''}}">
                                    <a href="{{route('biography_hard_skills', app()->getLocale(),false)}}"
                                       class="nav-link" data-key="t-analytics">
                                        {{ __('Hard Skills') }}
                                    </a>
                                </li>
                                <li class="nav-item cool-link {{Route::currentRouteName()=='biography_soft_skills'? 'active' : ''}}">
                                    <a href="{{route('biography_soft_skills', app()->getLocale(),false)}}"
                                       class="nav-link" data-key="t-analytics">
                                        {{ __('Soft Skills') }}
                                    </a>
                                </li>
                                <li class="nav-item cool-link {{Route::currentRouteName()=='biography_personal_characterization'? 'active' : ''}}">
                                    <a href="{{route('biography_personal_characterization', app()->getLocale(),false)}}"
                                       class="nav-link" data-key="t-analytics">
                                        {{ __('Personal Characterization') }}
                                    </a>
                                </li>
                                <li class="nav-item cool-link {{Route::currentRouteName()=='biography_NCDPersonality'? 'active' : ''}}">
                                    <a href="{{route('biography_NCDPersonality', app()->getLocale(),false)}}"
                                       class="nav-link" data-key="t-analytics">
                                        {{ __('CD Personality') }}
                                    </a>
                                </li>
                                <li class="nav-item cool-link {{Route::currentRouteName()=='biography_sensory_representation_system'? 'active' : ''}}">
                                    <a href="{{route('biography_sensory_representation_system', app()->getLocale(),false)}}"
                                       class="nav-link" data-key="t-analytics">
                                        {{ __('Sensory Representation System') }}
                                    </a>
                                </li>
                                <li class="nav-item cool-link {{Route::currentRouteName()=='biography_MBTI'? 'active' : ''}}">
                                    <a href="{{route('biography_MBTI', app()->getLocale(),false)}}"
                                       class="nav-link" data-key="t-analytics">
                                        {{ __('Myers-Briggs Type Indicator (MBTI)') }}
                                    </a>
                                </li>
                                <li class="nav-item cool-link {{Route::currentRouteName()=='biography_career_experience'? 'active' : ''}}">
                                    <a href="{{route('biography_career_experience', app()->getLocale(),false)}}"
                                       class="nav-link" data-key="t-analytics">
                                        {{ __('Career Experience') }}
                                    </a>
                                </li>
                                <li class="nav-item cool-link {{Route::currentRouteName()=='biography_e_business_card'? 'active' : ''}}">
                                    <a href="{{route('biography_e_business_card', app()->getLocale(),false)}}"
                                       class="nav-link" data-key="t-analytics">
                                        {{ __('e-Business Card (EBC)') }}
                                    </a>
                                </li>
                                <li class="nav-item cool-link {{Route::currentRouteName()=='biography_generating_pdf_report'? 'active' : ''}}">
                                    <a href="{{route('biography_generating_pdf_report', app()->getLocale(),false)}}"
                                       class="nav-link" data-key="t-analytics">
                                        {{ __('Rapport PDF') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>


                    <li class="nav-item cool-link {{Route::currentRouteName()=='notification_settings'? 'active' : ''}}">
                        <a href="{{route('notification_settings',app()->getLocale(),false)}}"
                           class="nav-link menu-link {{Route::currentRouteName()=='notification_settings'? 'active' : ''}}"
                           role="button">
                            <i class="ri-notification-2-fill"></i>
                            <span>{{ __('Notification Settings') }}</span>
                        </a>
                    </li>
                    <li class="nav-item cool-link {{Route::currentRouteName()=='notification_history'? 'active' : ''}}">
                        <a href="{{route('notification_history',app()->getLocale(),false)}}"
                           class="nav-link menu-link {{Route::currentRouteName()=='notification_history'? 'active' : ''}}"
                           role="button">
                            <i class="ri-dashboard-2-line"></i>
                            <span>{{ __('Notification history') }}</span>
                        </a>
                    </li>
                    <li class="nav-item cool-link {{Route::currentRouteName()=='financial_transaction'? 'active' : ''}}">
                        <a href="{{route('financial_transaction',app()->getLocale(),false)}}"
                           class="nav-link menu-link {{Route::currentRouteName()=='financial_transaction'? 'active' : ''}}"
                           role="button">
                            <i class="ri-bank-fill"></i> <span>{{ __('Exchange') }}</span>
                        </a>
                    </li>
                    <li style="margin-top: -20px" class="nav-item">
                        <a id="NotificationRequest" class="nav-link menu-link">
                        </a>
                    </li>
                    <li class="nav-item cool-link {{Route::currentRouteName()=='hobbies'? 'active' : ''}}">
                        <a href="{{route('hobbies',app()->getLocale(),false )}}"
                           class="nav-link menu-link {{Route::currentRouteName()=='hobbies'? 'active' : ''}}"
                           role="button">
                            <i class="ri-stack-line"></i>
                            <span>{{__('Hobbies')}}</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link {{!in_array(Route::currentRouteName(), ['shares_sold','shares_sold_market_status','shares_sold_recent_transaction'])? 'collapsed' : 'active'}}"
                           href="#sidebarSurvey" data-bs-toggle="collapse"
                           role="button"
                           aria-expanded="{{in_array(Route::currentRouteName(), ['shares_sold','shares_sold_market_status','shares_sold_recent_transaction'])? 'true' : 'false'}}"
                           aria-controls="sidebarSurvey">
                            <i class="ri-bookmark-fill"></i>
                            <span
                                data-key="t-dashboards">{{ __('Surveys') }}</span>
                        </a>
                        <div
                            class="menu-dropdown collapse {{in_array(Route::currentRouteName(), ['surveys_index','surveys_archive'])? 'show' : ''}}"
                            id="sidebarSurvey">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item cool-link {{Route::currentRouteName()=='surveys_index'? 'active' : ''}}">
                                    <a href="{{route('surveys_index', app()->getLocale(),false)}}"
                                       class="nav-link" data-key="t-analytics">{{ __('Surveys') }}</a>
                                </li>
                                <li class="nav-item cool-link {{Route::currentRouteName()=='surveys_archive'? 'active' : ''}}">
                                    <a href="{{route('surveys_archive', app()->getLocale(),false)}}"
                                       class="nav-link"
                                       data-key="t-analytics">{{ __('Archive') }}</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item cool-link {{Route::currentRouteName()=='description'? 'active' : ''}}">
                        <a href="{{route('description',app()->getLocale(),false )}}"
                           class="nav-link menu-link {{Route::currentRouteName()=='description'? 'active' : ''}} disabled"
                           role="button">
                            <i class="fa-solid fa-pen-fancy"></i>
                            <span>{{__('User guide')}}</span>
                        </a>
                    </li>
                    @if(auth()->user()->getRoleNames()->first() ==\App\Models\User::SUPER_ADMIN_ROLE_NAME)
                        <li class="menu-title">
                            <span data-key="t-menu">{{ __('SUPER ADMIN MENU') }}</span>
                        </li>
                        @if(auth()->user()->getRoleNames()->first() ==\App\Models\User::SUPER_ADMIN_ROLE_NAME)
                            <li class="nav-item cool-link {{Route::currentRouteName()=='target_index'? 'active' : ''}}">
                                <a href="{{route('target_index',['locale'=>request()->route("locale"),'idSurvey'=>request()->route("idSurvey")],false )}}"
                                   class="nav-link menu-link {{Route::currentRouteName()=='target_index'? 'active' : ''}}"
                                   role="button">
                                    <i class="ri-pushpin-fill"></i>
                                    <span>{{__('Targets')}}</span>
                                </a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link menu-link {{in_array(Route::currentRouteName(), ['configuration-setting','configuration-bo','configuration-ha','configuration-amounts'])? 'active' : ''}}"
                               href="#sidebarDashboards"
                               data-bs-toggle="collapse"
                               role="button"
                               aria-expanded="false"
                               aria-controls="sidebarDashboards">
                                <i class="ri-dashboard-2-line"></i> <span
                                    data-key="t-dashboards">{{ __('Settings') }}</span>
                            </a>
                            <div
                                class="menu-dropdown collapse {{in_array(Route::currentRouteName(), ['configuration-setting','configuration-bo','configuration-ha','configuration-amounts'])? 'show' : ''}}"
                                id="sidebarDashboards">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item cool-link {{Route::currentRouteName()=='configuration-setting'? 'active' : ''}}">
                                        <a href="{{route('configuration-setting', app()->getLocale(),false)}}"
                                           class="nav-link" data-key="t-analytics">{{ __('General Settings') }}</a>
                                    </li>
                                    <li class="nav-item cool-link {{Route::currentRouteName()=='configuration-bo'? 'active' : ''}}">
                                        <a href="{{route('configuration-bo', app()->getLocale(),false)}}"
                                           class="nav-link"
                                           data-key="t-analytics">{{ __('BO Settings') }}</a>
                                    </li>
                                    <li class="nav-item cool-link {{Route::currentRouteName()=='configuration-amounts'? 'active' : ''}}">
                                        <a href="{{route('configuration-amounts', app()->getLocale(),false)}}"
                                           class="nav-link" data-key="t-analytics">{{ __('Amounts Settings') }}</a>
                                    </li>
                                    <li class="nav-item cool-link {{Route::currentRouteName()=='configuration-ha'? 'active' : ''}}">
                                        <a href="{{route('configuration-ha', app()->getLocale(),false)}}"
                                           class="nav-link" data-key="t-analytics">{{ __('HA Settings') }}</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item cool-link {{Route::currentRouteName()=='edit_admin'? 'active' : ''}}">
                            <a href="{{route('edit_admin', app()->getLocale(),false)}}"
                               class="nav-link menu-link {{Route::currentRouteName()=='edit_admin'? 'active' : ''}}"
                               role="button">
                                <i class="ri-user-settings-line"></i>
                                <span>{{ __('Administrators Management') }}</span>
                            </a>
                        </li>
                        <li class="nav-item cool-link {{Route::currentRouteName()=='user_list'? 'active' : ''}}">
                            <a href="{{route('user_list', app()->getLocale(),false)}}"
                               class="nav-link menu-link {{Route::currentRouteName()=='user_list'? 'active' : ''}}"
                               role="button">
                                <i class="ri-user-fill"></i>
                                <span>{{ __('Users') }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link menu-link {{!in_array(Route::currentRouteName(), ['shares_sold','shares_sold_market_status','shares_sold_recent_transaction'])? 'collapsed' : 'active'}}"
                               href="#sidebarShareSold" data-bs-toggle="collapse"
                               role="button"
                               aria-expanded="{{in_array(Route::currentRouteName(), ['shares_sold','shares_sold_market_status','shares_sold_recent_transaction'])? 'true' : 'false'}}"
                               aria-controls="sidebarShareSold">
                                <i class="ri-dashboard-fill"></i> <span
                                    data-key="t-dashboards">{{ __('Shares sold') }}</span>
                            </a>
                            <div
                                class="menu-dropdown collapse {{in_array(Route::currentRouteName(), ['shares_sold','shares_sold_market_status','shares_sold_recent_transaction'])? 'show' : ''}}"
                                id="sidebarShareSold">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item cool-link {{Route::currentRouteName()=='shares_sold'? 'active' : ''}}">
                                        <a href="{{route('shares_sold', app()->getLocale(),false)}}"
                                           class="nav-link" data-key="t-analytics">{{ __('Shares sold') }}</a>
                                    </li>
                                    <li class="nav-item cool-link {{Route::currentRouteName()=='shares_sold_market_status'? 'active' : ''}}">
                                        <a href="{{route('shares_sold_market_status', app()->getLocale(),false)}}"
                                           class="nav-link"
                                           data-key="t-analytics">{{ __('Shares sold market status') }}</a>
                                    </li>
                                    <li class="nav-item cool-link {{Route::currentRouteName()=='shares_sold_recent_transaction'? 'active' : ''}}">
                                        <a href="{{route('shares_sold_recent_transaction', app()->getLocale(),false)}}"
                                           class="nav-link"
                                           data-key="t-analytics">{{ __('Shares sold recent transaction') }}</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="nav-item cool-link {{Route::currentRouteName()=='stat_countrie'? 'active' : ''}}">
                            <a href="{{route('stat_countrie', app()->getLocale(),false)}}"
                               class="nav-link menu-link {{Route::currentRouteName()=='stat_countrie'? 'active' : ''}}"
                               role="button">
                                <i class="ri-user-settings-line"></i>
                                <span>{{ __('StatByCountry') }}</span>
                            </a>
                        </li>
                        <li class="nav-item cool-link {{Route::currentRouteName()=='countries_management'? 'active' : ''}}">
                            <a href="{{route('api_settings', app()->getLocale(),false)}}"
                               class="nav-link menu-link disabled {{Route::currentRouteName()=='countries_management'? 'active' : ''}}"
                               role="button">
                                <i class="ri-settings-line"></i>
                                <span>{{ __('representatives Management') }}</span>
                            </a>
                        </li>
                        <li class="nav-item cool-link {{Route::currentRouteName()=='identification_request'? 'active' : ''}}">
                            <a href="{{route('identification_request', app()->getLocale(),false)}}"
                               class="nav-link menu-link {{Route::currentRouteName()=='identification_request'? 'active' : ''}}"
                               role="button">
                                <i class="ri-git-pull-request-line"></i>
                                <span>{{ __('Identification Requests') }}</span>
                            </a>
                        </li>
                        <li class="nav-item cool-link {{Route::currentRouteName()=='countries_management'? 'active' : ''}}">
                            <a href="{{route('countries_management', app()->getLocale(),false)}}"
                               class="nav-link menu-link {{Route::currentRouteName()=='countries_management'? 'active' : ''}}"
                               role="button">
                                <i class="ri-flag-line"></i>
                                <span>{{ __('Countries Management') }}</span>
                            </a>
                        </li>
                    @endif
                    @if(auth()->user()->getRoleNames()->first() ==\App\Models\User::SUPER_ADMIN_ROLE_NAME)

                        <li class="nav-item">
                            <a class="nav-link menu-link {{!in_array(Route::currentRouteName(), ['translate','translate_model_data'])? 'collapsed' : 'active'}}"
                               href="#sidebarTranslate" data-bs-toggle="collapse"
                               role="button"
                               aria-expanded="{{in_array(Route::currentRouteName(), ['translate','translate_model_data'])? 'true' : 'false'}}"
                               aria-controls="sidebarTranslate">
                                <i class="ri-dashboard-fill"></i> <span
                                    data-key="t-dashboards">{{ __('Translation') }}</span>
                            </a>
                            <div
                                class="menu-dropdown collapse {{in_array(Route::currentRouteName(), ['translate','translate_model_data'])? 'show' : ''}}"
                                id="sidebarTranslate">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item cool-link {{Route::currentRouteName()=='translate'? 'active' : ''}}">
                                        <a href="{{route('translate', app()->getLocale(),false)}}"
                                           class="nav-link" data-key="t-analytics">{{ __('Translate') }}</a>
                                    </li>
                                    <li class="nav-item cool-link {{Route::currentRouteName()=='translate_model_data'? 'active' : ''}}">
                                        <a href="{{route('translate_model_data', app()->getLocale(),false)}}"
                                           class="nav-link"
                                           data-key="t-analytics">{{ __('Translate model data') }}</a>
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

        function initMenuPart(array, menuDropdown) {
            console.log(location.pathname.substring(4))
            if (array.includes(location.pathname.substring(4))) {
                showDropDownMenu(menuDropdown)
            } else {
                hideDropDownMenu(menuDropdown);
            }
        }

        $(document).on('ready turbolinks:load', function () {
            $('#navbar-nav li').removeClass('active');
            $('#navbar-nav li a').removeClass('active');
            $('#navbar-nav a[href="' + location.pathname + '"]').addClass('active');
            $('#navbar-nav a[href="' + location.pathname + '"]').parent().addClass('active');

            initMenuPart(['survey/index', 'survey/archive'], 'sidebarSurvey')
            initMenuPart(['configuration/setting', 'configuration/bo', 'configuration/ha', 'configuration/amounts'], 'sidebarDashboards')
            initMenuPart(['shares-sold/dashboard', 'shares-sold/market-status', 'shares-sold/recent-transaction'], 'sidebarShareSold')
            initMenuPart(['translation', 'translation/model/data'], 'sidebarTranslate')
            initMenuPart([
                '/business-hub/trading',
                'business-hub/user/running-business',
                '/business-hub/be-influencer',
                '/be-influencer/tree/evolution',
                '/be-influencer/tree/maintenance',
                '/business-hub/job/opportunities',
                '/biography/generating/pdf/report'
            ], 'sidebarBusiness')
            initMenuPart(['recuperation/history'], 'sidebarSavings')
            initMenuPart([
                '/biography/career-experience',
                '/biography/hard-skills',
                '/biography/soft-skills',
                '/biography/personal-characterization',
                '/biography/NCDPersonality',
                '/biography/sensory-representation-system',
                '/biography/MBTI',
                '/biography/e-business-card',
                '/biography/academic-background',
            ], 'biography')
        });
    </script>
</div>
