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
                    <li class="nav-item {{Route::currentRouteName()=='home'? 'active' : ''}}">
                        <a href="{{route('home',app()->getLocale(),false)}}"
                           class="nav-link menu-link {{Route::currentRouteName()=='home'? 'active' : ''}}"
                           role="button">
                            <i class="ri-home-gear-fill"></i>
                            <span>{{ __('Home') }}</span>
                        </a>
                    </li>
                    <li class="nav-item {{Route::currentRouteName()=='account'? 'active' : ''}}">
                        <a href="{{route('account',app()->getLocale(),false )}}"
                           class="nav-link menu-link {{Route::currentRouteName()=='account'? 'active' : ''}}"
                           role="button">
                            <i class="ri-account-pin-circle-fill"></i>
                            <span>{{ __('Account') }}</span>
                        </a>
                    </li>
                    <li class="nav-item {{Route::currentRouteName()=='contacts'? 'active' : ''}}">
                        <a href="{{route('contacts',app()->getLocale(),false)}}"
                           class="nav-link menu-link {{Route::currentRouteName()=='contacts'? 'active' : ''}}"
                           role="button">
                            <i class="ri-contacts-fill"></i>
                            <span>{{ __('Contact') }}</span>
                        </a>
                    </li>
                    <li class="nav-item {{Route::currentRouteName()=='user_purchase'? 'active' : ''}}">
                        <a href="{{route('user_purchase',app()->getLocale(),false)}}"
                           class="nav-link menu-link {{Route::currentRouteName()=='user_purchase'? 'active' : ''}}"
                           role="button">
                            <i class="ri-dashboard-2-line"></i>
                            <span>{{ __('Purchase history') }}</span>
                        </a>
                    </li>
                    <li class="nav-item {{Route::currentRouteName()=='recuperation_history'? 'active' : ''}}">
                        <a href="{{route('recuperation_history',app()->getLocale(),false)}}"
                           class="nav-link menu-link {{Route::currentRouteName()=='recuperation_history'? 'active' : ''}} disabled"
                           role="button">
                            <i class="fas fa-history"></i>
                            <span>{{ __('Historique_recuperation') }}</span>
                        </a>
                    </li>
                    <li class="nav-item {{Route::currentRouteName()=='tree_evolution'? 'active' : ''}}">
                        <a href="{{route('tree_evolution',app()->getLocale(),false)}}"
                           class="nav-link menu-link {{Route::currentRouteName()=='tree_evolution'? 'active' : ''}}"
                           role="button">
                            <i class="fa-solid fa-tree" style="color: #009fe3;"></i>
                            <span>{{ __('Evolution_arbre') }}</span>
                        </a>
                    </li>
                    <li class="nav-item {{Route::currentRouteName()=='tree_maintenance'? 'active' : ''}}">
                        <a href="{{route('tree_maintenance',app()->getLocale(),false)}}"
                           class="nav-link menu-link {{Route::currentRouteName()=='tree_maintenance'? 'active' : ''}} disabled"
                           role="button">
                            <i class="fa-brands fa-pagelines"></i>
                            <span>{{ __('Entretien_arbre') }}</span>
                        </a>
                    </li>
                    <li class="nav-item {{Route::currentRouteName()=='notification_settings'? 'active' : ''}}">
                        <a href="{{route('notification_settings',app()->getLocale(),false)}}"
                           class="nav-link menu-link {{Route::currentRouteName()=='notification_settings'? 'active' : ''}}"
                           role="button">
                            <i class="ri-notification-2-fill"></i>
                            <span>{{ __('Notification Settings') }}</span>
                        </a>
                    </li>
                    <li class="nav-item {{Route::currentRouteName()=='notification_history'? 'active' : ''}}">
                        <a href="{{route('notification_history',app()->getLocale(),false)}}"
                           class="nav-link menu-link {{Route::currentRouteName()=='notification_history'? 'active' : ''}}"
                           role="button">
                            <i class="ri-dashboard-2-line"></i>
                            <span>{{ __('Notification history') }}</span>
                        </a>
                    </li>
                    <li class="nav-item {{Route::currentRouteName()=='financial_transaction'? 'active' : ''}}">
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
                    <li class="nav-item {{Route::currentRouteName()=='hobbies'? 'active' : ''}}">
                        <a href="{{route('hobbies',app()->getLocale(),false )}}"
                           class="nav-link menu-link {{Route::currentRouteName()=='hobbies'? 'active' : ''}}"
                           role="button">
                            <i class="ri-stack-line"></i>
                            <span>{{__('Hobbies')}}</span>
                        </a>
                    </li>
                    <li class="nav-item {{Route::currentRouteName()=='surveys_index'? 'active' : ''}}">
                        <a href="{{route('surveys_index',['locale'=>request()->route("locale"),'idServey'=>request()->route("idServey")],false )}}"
                           class="nav-link menu-link {{Route::currentRouteName()=='surveys_index'? 'active' : ''}}"
                           role="button">
                            <i class="ri-bookmark-fill"></i>
                            <span>{{__('Surveys')}}</span>
                        </a>
                    </li>
                    <li class="nav-item {{Route::currentRouteName()=='description'? 'active' : ''}}">
                        <a href="{{route('description',app()->getLocale(),false )}}"
                           class="nav-link menu-link {{Route::currentRouteName()=='description'? 'active' : ''}} disabled"
                           role="button">
                            <i class="fa-solid fa-pen-fancy"></i>
                            <span>{{__('description')}}</span>
                        </a>
                    </li>
                    @if(auth()->user()->getRoleNames()->first() =="Super admin")
                        <li class="menu-title">
                            <span data-key="t-menu">{{ __('SUPER ADMIN MENU') }}</span>
                        </li>
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
                                    <li class="nav-item {{Route::currentRouteName()=='configuration-setting'? 'active' : ''}}">
                                        <a href="{{route('configuration-setting', app()->getLocale(),false)}}"
                                           class="nav-link" data-key="t-analytics">{{ __('General Settings') }}</a>
                                    </li>
                                    <li class="nav-item {{Route::currentRouteName()=='configuration-bo'? 'active' : ''}}">
                                        <a href="{{route('configuration-bo', app()->getLocale(),false)}}"
                                           class="nav-link"
                                           data-key="t-analytics">{{ __('BO Settings') }}</a>
                                    </li>
                                    <li class="nav-item {{Route::currentRouteName()=='configuration-amounts'? 'active' : ''}}">
                                        <a href="{{route('configuration-amounts', app()->getLocale(),false)}}"
                                           class="nav-link" data-key="t-analytics">{{ __('Amounts Settings') }}</a>
                                    </li>
                                    <li class="nav-item {{Route::currentRouteName()=='configuration-ha'? 'active' : ''}}">
                                        <a href="{{route('configuration-ha', app()->getLocale(),false)}}"
                                           class="nav-link" data-key="t-analytics">{{ __('HA Settings') }}</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item {{Route::currentRouteName()=='edit_admin'? 'active' : ''}}">
                            <a href="{{route('edit_admin', app()->getLocale(),false)}}"
                               class="nav-link menu-link {{Route::currentRouteName()=='edit_admin'? 'active' : ''}}"
                               role="button">
                                <i class="ri-user-settings-line"></i>
                                <span>{{ __('Administrators Management') }}</span>
                            </a>
                        </li>
                        <li class="nav-item {{Route::currentRouteName()=='user_list'? 'active' : ''}}">
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
                                    <li class="nav-item {{Route::currentRouteName()=='shares_sold'? 'active' : ''}}">
                                        <a href="{{route('shares_sold', app()->getLocale(),false)}}"
                                           class="nav-link" data-key="t-analytics">{{ __('Shares sold') }}</a>
                                    </li>
                                    <li class="nav-item {{Route::currentRouteName()=='shares_sold_market_status'? 'active' : ''}}">
                                        <a href="{{route('shares_sold_market_status', app()->getLocale(),false)}}"
                                           class="nav-link"
                                           data-key="t-analytics">{{ __('Shares sold market status') }}</a>
                                    </li>
                                    <li class="nav-item {{Route::currentRouteName()=='shares_sold_recent_transaction'? 'active' : ''}}">
                                        <a href="{{route('shares_sold_recent_transaction', app()->getLocale(),false)}}"
                                           class="nav-link"
                                           data-key="t-analytics">{{ __('Shares sold recent transaction') }}</a>
                                    </li>
                                </ul>
                            </div>
                        </li>


                        <li class="nav-item {{Route::currentRouteName()=='stat_countrie'? 'active' : ''}}">
                            <a href="{{route('stat_countrie', app()->getLocale(),false)}}"
                               class="nav-link menu-link {{Route::currentRouteName()=='stat_countrie'? 'active' : ''}}"
                               role="button">
                                <i class="ri-user-settings-line"></i>
                                <span>{{ __('StatByCountry') }}</span>
                            </a>
                        </li>
                        <li class="nav-item {{Route::currentRouteName()=='countries_management'? 'active' : ''}}">
                            <a href="{{route('api_settings', app()->getLocale(),false)}}"
                               class="nav-link menu-link disabled {{Route::currentRouteName()=='countries_management'? 'active' : ''}}"
                               role="button">
                                <i class="ri-settings-line"></i>
                                <span>{{ __('representatives Management') }}</span>
                            </a>
                        </li>
                        <li class="nav-item {{Route::currentRouteName()=='identification_request'? 'active' : ''}}">
                            <a href="{{route('identification_request', app()->getLocale(),false)}}"
                               class="nav-link menu-link {{Route::currentRouteName()=='identification_request'? 'active' : ''}}"
                               role="button">
                                <i class="ri-git-pull-request-line"></i>
                                <span>{{ __('Identification Requests') }}</span>
                            </a>
                        </li>
                        <li class="nav-item {{Route::currentRouteName()=='countries_management'? 'active' : ''}}">
                            <a href="{{route('countries_management', app()->getLocale(),false)}}"
                               class="nav-link menu-link {{Route::currentRouteName()=='countries_management'? 'active' : ''}}"
                               role="button">
                                <i class="ri-flag-line"></i>
                                <span>{{ __('Countries Management') }}</span>
                            </a>
                        </li>
                    @endif
                    @if(auth()->user()->getRoleNames()->first() =="Super admin")
                        <li class="nav-item {{Route::currentRouteName()=='translate'? 'active' : ''}}">
                            <a href="{{route('translate', app()->getLocale(),false)}}"
                               class="nav-link menu-link {{Route::currentRouteName()=='translate'? 'active' : ''}}"
                               role="button"
                            >
                                <i class="ri-translate"></i>
                                <span>{{ __('Translate') }}</span>
                            </a>
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

        $(document).on('ready turbolinks:load', function () {
            $('#navbar-nav li').removeClass('active');
            $('#navbar-nav li a').removeClass('active');
            $('#navbar-nav a[href="' + location.pathname + '"]').addClass('active');
            $('#navbar-nav a[href="' + location.pathname + '"]').parent().addClass('active');
            const settingArray = ['configuration-setting', 'configuration-bo', 'configuration-ha', 'configuration-amounts'];
            const shareSoldArray = ['shares-sold-dashboard', 'shares-sold-market-status', 'shares-sold-recent-transaction'];
            var currentRoutePath = location.pathname.substring(location.pathname.lastIndexOf("/") + 1);

            if (settingArray.includes(currentRoutePath)) {
                showDropDownMenu('sidebarDashboards')
            } else {
                hideDropDownMenu('sidebarDashboards');
            }

            if (shareSoldArray.includes(currentRoutePath)) {
                showDropDownMenu('sidebarShareSold')
            } else {
                hideDropDownMenu('sidebarShareSold');
            }
        });
    </script>
</div>
