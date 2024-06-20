<div>
    <div class="app-menu navbar-menu">
        <div class="navbar-brand-box">
            <a href="{{route('home',app()->getLocale())}}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ URL::asset('assets/images/logo-sm.png') }}" alt="" height="22">
            </span>
                <span class="logo-lg">
                <img src="{{ URL::asset('assets/images/logo-dark.png') }}" alt="" height="35px">
            </span>
            </a>
            <a id="MyHover" href="{{route('home',app()->getLocale())}}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ URL::asset('assets/images/logo-sm.png') }}" alt="" height="40">
            </span>
                <span class="logo-lg">
                <img src="{{ URL::asset('assets/images/logo-light.png') }}" alt="" height="30">
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
                        <a href="{{route('home',app()->getLocale())}}"
                           class="nav-link menu-link {{Route::currentRouteName()=='home'? 'active' : ''}}"
                           role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                            <i class="ri-home-gear-fill"></i>
                            <span>{{ __('Home') }}</span>
                        </a>
                    </li>
                    <li class="nav-item {{Route::currentRouteName()=='account'? 'active' : ''}}">
                        <a href="{{route('account',app()->getLocale() )}}"
                           class="nav-link menu-link {{Route::currentRouteName()=='account'? 'active' : ''}}"
                           role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                            <i class="ri-account-pin-circle-fill"></i>
                            <span>{{ __('Account') }}</span>
                        </a>
                    </li>
                    <li class="nav-item {{Route::currentRouteName()=='contacts'? 'active' : ''}}">
                        <a href="{{route('contacts',app()->getLocale())}}"
                           class="nav-link menu-link {{Route::currentRouteName()=='contacts'? 'active' : ''}}"
                           role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                            <i class="ri-contacts-fill"></i>
                            <span>{{ __('Contact') }}</span>
                        </a>
                    </li>
                    <li class="nav-item {{Route::currentRouteName()=='user_purchase'? 'active' : ''}}">
                        <a href="{{route('user_purchase',app()->getLocale())}}"
                           class="nav-link menu-link {{Route::currentRouteName()=='user_purchase'? 'active' : ''}}"
                           role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                            <i class="ri-dashboard-2-line"></i>
                            <span>{{ __('Purchase history') }}</span>
                        </a>
                    </li>
                    <li class="nav-item {{Route::currentRouteName()=='recuperation_history'? 'active' : ''}}">
                        <a href="{{route('recuperation_history',app()->getLocale())}}"
                           class="nav-link menu-link {{Route::currentRouteName()=='recuperation_history'? 'active' : ''}} disabled"
                           role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                            <i class="fas fa-history"></i>
                            <span>{{ __('Historique_recuperation') }}</span>
                        </a>
                    </li>
                    <li class="nav-item {{Route::currentRouteName()=='tree_evolution'? 'active' : ''}}">
                        <a href="{{route('tree_evolution',app()->getLocale())}}"
                           class="nav-link menu-link {{Route::currentRouteName()=='tree_evolution'? 'active' : ''}}"
                           role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                            <i class="fa-solid fa-tree" style="color: #009fe3;"></i>
                            <span>{{ __('Evolution_arbre') }}</span>
                        </a>
                    </li>
                    <li class="nav-item {{Route::currentRouteName()=='tree_maintenance'? 'active' : ''}}">
                        <a href="{{route('tree_maintenance',app()->getLocale())}}"
                           class="nav-link menu-link {{Route::currentRouteName()=='tree_maintenance'? 'active' : ''}} disabled"
                           role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                            <i class="fa-brands fa-pagelines"></i>
                            <span>{{ __('Entretien_arbre') }}</span>
                        </a>
                    </li>
                    <li class="nav-item {{Route::currentRouteName()=='notification_settings'? 'active' : ''}}">
                        <a href="{{route('notification_settings',app()->getLocale())}}"
                           class="nav-link menu-link {{Route::currentRouteName()=='notification_settings'? 'active' : ''}}"
                           role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                            <i class="ri-notification-2-fill"></i>
                            <span>{{ __('Notification Settings') }}</span>
                        </a>
                    </li>
                    <li class="nav-item {{Route::currentRouteName()=='notification_history'? 'active' : ''}}">
                        <a href="{{route('notification_history',app()->getLocale())}}"
                           class="nav-link menu-link {{Route::currentRouteName()=='notification_history'? 'active' : ''}}"
                           role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                            <i class="ri-dashboard-2-line"></i>
                            <span>{{ __('Notification history') }}</span>
                        </a>
                    </li>
                    <li class="nav-item {{Route::currentRouteName()=='financial_transaction'? 'active' : ''}}">
                        <a href="{{route('financial_transaction',app()->getLocale())}}"
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
                        <a href="{{route('hobbies',app()->getLocale() )}}"
                           class="nav-link menu-link {{Route::currentRouteName()=='hobbies'? 'active' : ''}}"
                           role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                            <i class="ri-stack-line"></i>
                            <span>{{__('Hobbies')}}</span>
                        </a>
                    </li>
                    <li class="nav-item {{Route::currentRouteName()=='description'? 'active' : ''}}">
                        <a href="{{route('description',app()->getLocale() )}}"
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
                        <li class="nav-item {{Route::currentRouteName()=='configuration'? 'active' : ''}}">
                            <a href="{{route('configuration', app()->getLocale())}}"
                               class="nav-link menu-link {{Route::currentRouteName()=='configuration'? 'active' : ''}}"
                               role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                                <i class="ri-settings-5-line"></i>
                                <span>{{ __('Settings') }}</span>
                            </a>
                        </li>
                        <li class="nav-item {{Route::currentRouteName()=='edit_admin'? 'active' : ''}}">
                            <a href="{{route('edit_admin', app()->getLocale())}}"
                               class="nav-link menu-link {{Route::currentRouteName()=='edit_admin'? 'active' : ''}}"
                               role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                                <i class="ri-user-settings-line"></i>
                                <span>{{ __('Administrators Management') }}</span>
                            </a>
                        </li>
                        <li class="nav-item {{Route::currentRouteName()=='user_list'? 'active' : ''}}">
                            <a href="{{route('user_list', app()->getLocale())}}"
                               class="nav-link menu-link {{Route::currentRouteName()=='user_list'? 'active' : ''}}"
                               role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                                <i class="ri-user-settings-line"></i>
                                <span>{{ __('Users') }}</span>
                            </a>
                        </li>
                        <li class="nav-item {{Route::currentRouteName()=='countries_management'? 'active' : ''}}">
                            <a href="{{route('shares_sold', app()->getLocale())}}"
                               class="nav-link menu-link {{Route::currentRouteName()=='countries_management'? 'active' : ''}}"
                               role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                                <i class="ri-user-settings-line"></i> <span>{{ __('Shares Sold') }}</span>
                            </a>
                        </li>
                        <li class="nav-item {{Route::currentRouteName()=='stat_countrie'? 'active' : ''}}">
                            <a href="{{route('stat_countrie', app()->getLocale())}}"
                               class="nav-link menu-link {{Route::currentRouteName()=='stat_countrie'? 'active' : ''}}"
                               role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                                <i class="ri-user-settings-line"></i>
                                <span>{{ __('StatByCountry') }}</span>
                            </a>
                        </li>
                        <li class="nav-item {{Route::currentRouteName()=='countries_management'? 'active' : ''}}">
                            <a href="{{route('api_settings', app()->getLocale())}}"
                               class="nav-link menu-link {{Route::currentRouteName()=='countries_management'? 'active' : ''}}"
                               role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                                <i class="ri-settings-line"></i>
                                <span>{{ __('representatives Management') }}</span>
                            </a>
                        </li>
                        <li class="nav-item {{Route::currentRouteName()=='identification_request'? 'active' : ''}}">
                            <a href="{{route('identification_request', app()->getLocale())}}"
                               class="nav-link menu-link {{Route::currentRouteName()=='identification_request'? 'active' : ''}}"
                               role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                                <i class="ri-git-pull-request-line"></i>
                                <span>{{ __('Identification Requests') }}</span>
                            </a>
                        </li>
                        <li class="nav-item {{Route::currentRouteName()=='countries_management'? 'active' : ''}}">
                            <a href="{{route('countries_management', app()->getLocale())}}"
                               class="nav-link menu-link {{Route::currentRouteName()=='countries_management'? 'active' : ''}}"
                               role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                                <i class="ri-flag-line"></i>
                                <span>{{ __('Countries Management') }}</span>
                            </a>
                        </li>
                    @endif
                    @if(getExtraAdmin()=="0021653342666" || getExtraAdmin()=="0021629294046" ||getExtraAdmin()=="0021653615614" ||auth()->user()->getRoleNames()->first() =="Super admin")
                        <li class="nav-item {{Route::currentRouteName()=='translate'? 'active' : ''}}">
                            <a data-turbolinks="false" href="{{route('translate', app()->getLocale())}}"
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
</div>
