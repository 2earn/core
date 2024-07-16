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
                           role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                            <i class="ri-home-gear-fill"></i>
                            <span>{{ __('Home') }}</span>
                        </a>
                    </li>
                    <li class="nav-item {{Route::currentRouteName()=='account'? 'active' : ''}}">
                        <a href="{{route('account',app()->getLocale(),false )}}"
                           class="nav-link menu-link {{Route::currentRouteName()=='account'? 'active' : ''}}"
                           role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                            <i class="ri-account-pin-circle-fill"></i>
                            <span>{{ __('Account') }}</span>
                        </a>
                    </li>
                    <li class="nav-item {{Route::currentRouteName()=='contacts'? 'active' : ''}}">
                        <a href="{{route('contacts',app()->getLocale(),false)}}"
                           class="nav-link menu-link {{Route::currentRouteName()=='contacts'? 'active' : ''}}"
                           role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                            <i class="ri-contacts-fill"></i>
                            <span>{{ __('Contact') }}</span>
                        </a>
                    </li>
                    <li class="nav-item {{Route::currentRouteName()=='user_purchase'? 'active' : ''}}">
                        <a href="{{route('user_purchase',app()->getLocale(),false)}}"
                           class="nav-link menu-link {{Route::currentRouteName()=='user_purchase'? 'active' : ''}}"
                           role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                            <i class="ri-dashboard-2-line"></i>
                            <span>{{ __('Purchase history') }}</span>
                        </a>
                    </li>
                    <li class="nav-item {{Route::currentRouteName()=='RecuperationHistory'? 'active' : ''}}">
                        <a href="{{route('RecuperationHistory',app()->getLocale(),false)}}"
                           class="nav-link menu-link {{Route::currentRouteName()=='RecuperationHistory'? 'active' : ''}} disabled"
                           role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                            <i class="fas fa-history"></i>
                            <span>{{ __('Historique_recuperation') }}</span>
                        </a>
                    </li>
                    <li class="nav-item {{Route::currentRouteName()=='TreeEvolution'? 'active' : ''}}">
                        <a href="{{route('TreeEvolution',app()->getLocale(),false)}}"
                           class="nav-link menu-link {{Route::currentRouteName()=='TreeEvolution'? 'active' : ''}}"
                           role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                            <i class="fa-solid fa-tree" style="color: #009fe3;"></i>
                            <span>{{ __('Evolution_arbre') }}</span>
                        </a>
                    </li>
                    <li class="nav-item {{Route::currentRouteName()=='TreeMaintenance'? 'active' : ''}}">
                        <a href="{{route('TreeMaintenance',app()->getLocale(),false)}}"
                           class="nav-link menu-link {{Route::currentRouteName()=='TreeMaintenance'? 'active' : ''}} disabled"
                           role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                            <i class="fa-brands fa-pagelines"></i>
                            <span>{{ __('Entretien_arbre') }}</span>
                        </a>
                    </li>
                    <li class="nav-item {{Route::currentRouteName()=='notification_settings'? 'active' : ''}}">
                        <a href="{{route('notification_settings',app()->getLocale(),false)}}"
                           class="nav-link menu-link {{Route::currentRouteName()=='notification_settings'? 'active' : ''}}"
                           role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                            <i class="ri-notification-2-fill"></i>
                            <span>{{ __('Notification Settings') }}</span>
                        </a>
                    </li>
                    <li class="nav-item {{Route::currentRouteName()=='notification_history'? 'active' : ''}}">
                        <a href="{{route('notification_history',app()->getLocale(),false)}}"
                           class="nav-link menu-link {{Route::currentRouteName()=='notification_history'? 'active' : ''}}"
                           role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                            <i class="ri-dashboard-2-line"></i>
                            <span>{{ __('Notification history') }}</span>
                        </a>
                    </li>
                    <li class="nav-item {{Route::currentRouteName()=='financial_transaction'? 'active' : ''}}">
                        <a href="{{route('financial_transaction',app()->getLocale(),false)}}"
                           class="nav-link menu-link {{Route::currentRouteName()=='financial_transaction'? 'active' : ''}}"
                           role="button" aria-expanded="false" aria-controls="sidebarDashboards">
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
                           role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                            <i class="ri-stack-line"></i>
                            <span>{{__('Hobbies')}}</span>
                        </a>
                    </li>
                    <li class="nav-item {{Route::currentRouteName()=='description'? 'active' : ''}}">
                        <a href="{{route('description',app()->getLocale(),false )}}"
                           class="nav-link menu-link {{Route::currentRouteName()=='description'? 'active' : ''}} disabled"
                           role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                            <i class="fa-solid fa-pen-fancy"></i>
                            <span>{{__('description')}}</span>
                        </a>
                    </li>
                    @if(auth()->user()->getRoleNames()->first() =="Super admin")
                        <li class="menu-title">
                            <span data-key="t-menu">{{ __('SUPER ADMIN MENU') }}</span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link menu-link {{!in_array(Route::currentRouteName(), ['configuration-setting','configuration-bo','configuration-ha','configuration-amounts'])? 'collapsed' : 'active'}}"
                               href="#sidebarDashboards" data-bs-toggle="collapse"
                               role="button"
                               aria-expanded="{{in_array(Route::currentRouteName(), ['configuration-setting','configuration-bo','configuration-ha','configuration-amounts'])? 'true' : 'false'}}"
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
                               role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                                <i class="ri-user-settings-line"></i>
                                <span>{{ __('Administrators Management') }}</span>
                            </a>
                        </li>
                        <li class="nav-item {{Route::currentRouteName()=='user_list'? 'active' : ''}}">
                            <a href="{{route('user_list', app()->getLocale(),false)}}"
                               class="nav-link menu-link {{Route::currentRouteName()=='user_list'? 'active' : ''}}"
                               role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                                <i class="ri-user-settings-line"></i>
                                <span>{{ __('Users') }}</span>
                            </a>
                        </li>
                        <li class="nav-item {{Route::currentRouteName()=='countries_management'? 'active' : ''}}">
                            <a href="{{route('shares_sold', app()->getLocale(),false)}}"
                               class="nav-link menu-link {{Route::currentRouteName()=='countries_management'? 'active' : ''}}"
                               role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                                <i class="ri-user-settings-line"></i> <span>{{ __('Shares Sold') }}</span>
                            </a>
                        </li>
                        <li class="nav-item {{Route::currentRouteName()=='stat_countrie'? 'active' : ''}}">
                            <a href="{{route('stat_countrie', app()->getLocale(),false)}}"
                               class="nav-link menu-link {{Route::currentRouteName()=='stat_countrie'? 'active' : ''}}"
                               role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                                <i class="ri-user-settings-line"></i>
                                <span>{{ __('StatByCountry') }}</span>
                            </a>
                        </li>
                        <li class="nav-item {{Route::currentRouteName()=='countries_management'? 'active' : ''}}">
                            <a href="{{route('API_settings', app()->getLocale(),false)}}"
                               class="nav-link menu-link disabled {{Route::currentRouteName()=='countries_management'? 'active' : ''}}"
                               role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                                <i class="ri-settings-line"></i>
                                <span>{{ __('representatives Management') }}</span>
                            </a>
                        </li>
                        <li class="nav-item {{Route::currentRouteName()=='identificationRequest'? 'active' : ''}}">
                            <a href="{{route('identificationRequest', app()->getLocale(),false)}}"
                               class="nav-link menu-link {{Route::currentRouteName()=='identificationRequest'? 'active' : ''}}"
                               role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                                <i class="ri-git-pull-request-line"></i>
                                <span>{{ __('Identification Requests') }}</span>
                            </a>
                        </li>
                        <li class="nav-item {{Route::currentRouteName()=='countries_management'? 'active' : ''}}">
                            <a href="{{route('countries_management', app()->getLocale(),false)}}"
                               class="nav-link menu-link {{Route::currentRouteName()=='countries_management'? 'active' : ''}}"
                               role="button" aria-expanded="false" aria-controls="sidebarDashboards">
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
                               aria-expanded="false" aria-controls="sidebarDashboards">
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
        $(document).on('ready turbolinks:load', function () {
            $('#navbar-nav li').removeClass('active');
            $('#navbar-nav li a').removeClass('active');
            $('#navbar-nav a[href="' + location.pathname + '"]').addClass('active');
            $('#navbar-nav a[href="' + location.pathname + '"]').parent().addClass('active');
            const settingArray = ['configuration-setting', 'configuration-bo', 'configuration-ha', 'configuration-amounts'];
            var currentRoutePath = location.pathname.substring(location.pathname.lastIndexOf("/") + 1);
            if (settingArray.includes(currentRoutePath)) {
                $('#sidebarDashboards').addClass('show');
            } else {
                $('#sidebarDashboards').removeClass('show');
            }
        });
    </script>
</div>
