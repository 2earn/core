<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="{{route('home',app()->getLocale())}}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ URL::asset('assets/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('assets/images/logo-dark.png') }}" alt="" height="25">
            </span>
        </a>
        <!-- Light Logo-->
        <a id="MyHover" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ URL::asset('assets/images/logo-sm.png') }}" alt="" height="40">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('assets/images/logo-light.png') }}" alt="" height="30">
            </span>
        </a>

        {{--        <button   >sdfsdfdsf</button>--}}
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
                {{--                <li class="menu-title"><span>@lang('translation.menu')</span></li>--}}

                <li class="nav-item">
                    <a href="{{route('home',app()->getLocale())}}" class="nav-link menu-link" data-bs-toggle=""
                       role="button"
                       aria-expanded="false" aria-controls="sidebarDashboards">
                        <i class="ri-home-gear-fill"></i> <span>{{ __('Home') }}</span>
                    </a>
                    {{--                    <a  class="nav-link">@lang('translation.analytics')</a>--}}
                </li>

                <li class="nav-item">
                    <a href="{{route('account',app()->getLocale() )}}" class="nav-link menu-link" data-bs-toggle=""
                       role="button"
                       aria-expanded="false" aria-controls="sidebarDashboards">
                        <i class="ri-account-pin-circle-fill"></i> <span>{{ __('Account') }}</span>
                    </a>
                    {{--                    <a  class="nav-link">@lang('translation.analytics')</a>--}}
                </li>

                <li class="nav-item">
                    <a href="{{route('contacts',app()->getLocale())}}" class="nav-link menu-link" data-bs-toggle=""
                       role="button"
                       aria-expanded="false" aria-controls="sidebarDashboards">
                        <i class="ri-contacts-fill"></i> <span>{{ __('Contact') }}</span>
                    </a>
                    {{--                    <a  class="nav-link">@lang('translation.analytics')</a>--}}
                </li>

                <li class="nav-item">

                    {{--                    <a href="{{route('user_purchase')}}" class="nav-link menu-link"   data-bs-toggle="" role="button" aria-expanded="false" aria-controls="sidebarDashboards">--}}

                    <a href="{{route('user_purchase',app()->getLocale())}}" class="nav-link menu-link" data-bs-toggle=""
                       role="button"
                       aria-expanded="false" aria-controls="sidebarDashboards">

                        <i class="ri-dashboard-2-line"></i> <span>{{ __('Purchase history') }}</span>
                    </a>
                    {{--                    <a  class="nav-link">@lang('translation.analytics')</a>--}}
                </li>
                <li class="nav-item">
                    <a href="{{route('RecuperationHistory',app()->getLocale())}}" class="nav-link menu-link" data-bs-toggle=""
                       role="button"
                       aria-expanded="false" aria-controls="sidebarDashboards">
                        <i class="ri-history-line"></i> <span>{{ __('Historique_recuperation') }}</span>
                    </a>
                    {{--                    <a  class="nav-link">@lang('translation.analytics')</a>--}}
                </li>
                <li class="nav-item">
                    <a href="{{route('TreeEvolution',app()->getLocale())}}" class="nav-link menu-link" data-bs-toggle=""
                       role="button"
                       aria-expanded="false" aria-controls="sidebarDashboards">
                        <i class="fa-solid fa-tree"></i> <span>{{ __('Evolution_arbre') }}</span>
                    </a>
                    {{--                    <a  class="nav-link">@lang('translation.analytics')</a>--}}
                </li>
                <li class="nav-item">
                    <a href="{{route('TreeMaintenance',app()->getLocale())}}" class="nav-link menu-link" data-bs-toggle=""
                       role="button"
                       aria-expanded="false" aria-controls="sidebarDashboards">
                        <i class="fa-brands fa-pagelines"></i> <span>{{ __('Entretien_arbre') }}</span>
                    </a>
                    {{--                    <a  class="nav-link">@lang('translation.analytics')</a>--}}
                </li>
                <li class="nav-item">
                    <a href="{{route('notification_settings',app()->getLocale())}}" class="nav-link menu-link"
                       data-bs-toggle=""
                       role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                        <i class="ri-notification-2-fill"></i> <span>{{ __('Notification Settings') }}</span>
                    </a>
                    {{--                    <a  class="nav-link">@lang('translation.analytics')</a>--}}
                </li>
                <li class="nav-item">
                    <a href="{{route('notification_history',app()->getLocale())}}" class="nav-link menu-link"
                       data-bs-toggle=""
                       role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                        <i class="ri-dashboard-2-line"></i> <span>{{ __('Notification history') }}</span>
                    </a>
                    {{--                    <a  class="nav-link">@lang('translation.analytics')</a>--}}
                </li>
                <li class="nav-item">
                    <a href="{{route('financial_transaction',app()->getLocale())}}" class="nav-link menu-link"
                       data-bs-toggle="" role="button"
                       aria-expanded="false" aria-controls="sidebarDashboards">
                        <i class="ri-bank-fill"></i> <span>{{ __('Exchange') }}</span>

                    </a>

                    {{--                    <a  class="nav-link">@lang('translation.analytics')</a>--}}
                </li>
                <li style="margin-top: -20px"  class="nav-item">
                    <a id="NotificationRequest" class="nav-link menu-link" >
{{--                        <span id="SReqInOpen" class="badge badge-pill bg-danger " style="background-color: #f02602!important;">0</span>--}}
{{--                        <span id="SReqOutAccepted" class="badge badge-pill bg-danger">0</span>--}}
{{--                        <span id="SReqOutRefused" class="badge badge-pill bg-danger">0</span>--}}

                    </a>

                    {{--                    <a  class="nav-link">@lang('translation.analytics')</a>--}}
                </li>
                <li class="nav-item">
                    <a href="{{route('hobbies',app()->getLocale() )}}" class="nav-link menu-link" data-bs-toggle=""
                       role="button"
                       aria-expanded="false" aria-controls="sidebarDashboards">
                        <i class="ri-stack-line"></i>
                        <span>{{__('Hobbies')}}</span>
                    </a>
                    {{--                    <a  class="nav-link">@lang('translation.analytics')</a>--}}
                </li>
                <li class="nav-item">
                    <a href="{{route('description',app()->getLocale() )}}" class="nav-link menu-link" data-bs-toggle=""
                       role="button"
                       aria-expanded="false" aria-controls="sidebarDashboards">
                        <i class="fa-solid fa-pen-fancy"></i>
                        <span>{{__('description')}}</span>
                    </a>
                    {{--                    <a  class="nav-link">@lang('translation.analytics')</a>--}}
                </li>
                {{--                <li class="nav-item">--}}
                {{--                    <div>--}}
                {{--                        <span class="badge badge-pill bg-danger">hii</span>--}}
                {{--                        <span class="badge badge-pill bg-danger">hii</span>--}}
                {{--                        <span class="badge badge-pill bg-danger">hii</span>--}}
                {{--                    </div>                </li>--}}

                @if(auth()->user()->getRoleNames()->first() =="Super admin")
                    <hr>
                    <li class="nav-item">

                        <a href="{{route('configuration', app()->getLocale())}}" class="nav-link menu-link"
                           data-bs-toggle="" role="button"
                           aria-expanded="false" aria-controls="sidebarDashboards">

                            <i class="ri-settings-5-line"></i> <span>{{ __('Settings') }}</span>
                        </a>
                        {{--                    <a  class="nav-link">@lang('translation.analytics')</a>--}}
                    </li>
                    <li class="nav-item">

                        <a href="{{route('edit_admin', app()->getLocale())}}" class="nav-link menu-link" data-bs-toggle="" role="button"
                           aria-expanded="false" aria-controls="sidebarDashboards">

                            <i class="ri-user-settings-line"></i> <span>{{ __('Administrators Management') }}</span>
                        </a>
                        {{--                    <a  class="nav-link">@lang('translation.analytics')</a>--}}
                    </li>
                    <li class="nav-item">

                        <a href="{{route('user_list', app()->getLocale())}}" class="nav-link menu-link" data-bs-toggle="" role="button"
                           aria-expanded="false" aria-controls="sidebarDashboards">

                            <i class="ri-user-settings-line"></i> <span>{{ __('UserList') }}</span>
                        </a>
                        {{--                    <a  class="nav-link">@lang('translation.analytics')</a>--}}
                    </li>

                    <li class="nav-item">

                        <a href="{{route('shares_sold', app()->getLocale())}}" class="nav-link menu-link" data-bs-toggle="" role="button"
                           aria-expanded="false" aria-controls="sidebarDashboards">

                            <i class="ri-user-settings-line"></i> <span>{{ __('Shares Sold') }}</span>
                        </a>
                        {{--                    <a  class="nav-link">@lang('translation.analytics')</a>--}}
                    </li>
                    <li class="nav-item">

                        <a href="{{route('stat_countrie', app()->getLocale())}}" class="nav-link menu-link" data-bs-toggle="" role="button"
                           aria-expanded="false" aria-controls="sidebarDashboards">

                            <i class="ri-user-settings-line"></i> <span>{{ __('StatByCountry') }}</span>
                        </a>
                        {{--                    <a  class="nav-link">@lang('translation.analytics')</a>--}}
                    </li>
                    <li class="nav-item">

                        <a href="" class="nav-link menu-link" data-bs-toggle="" role="button"
                           aria-expanded="false" aria-controls="sidebarDashboards">

                            <i class="ri-settings-line"></i> <span>{{ __('representatives Management') }}</span>
                        </a>

                    </li>
                    <li class="nav-item">

                        <a href="{{route('identificationRequest', app()->getLocale())}}" class="nav-link menu-link"
                           data-bs-toggle="" role="button"

                           aria-expanded="false" aria-controls="sidebarDashboards">

                            <i class="ri-git-pull-request-line"></i> <span>{{ __('Identification Requests') }}</span>
                        </a>

                    </li>
                    <li class="nav-item">

                        <a href="{{route('countries_management', app()->getLocale())}}" class="nav-link menu-link" data-bs-toggle="" role="button"
                           aria-expanded="false" aria-controls="sidebarDashboards">

                            <i class="ri-flag-line"></i> <span>{{ __('Countries Management') }}</span>
                        </a>

                    </li>
                @endif
                @if(getExtraAdmin()=="0021653342666" || getExtraAdmin()=="0021629294046" ||getExtraAdmin()=="0021653615614")
                    <li class="nav-item">

                        <a data-turbolinks="false" href="{{route('translate', app()->getLocale())}}"
                           class="nav-link menu-link" data-bs-toggle="" role="button"
                           aria-expanded="false" aria-controls="sidebarDashboards">

                            <i class="ri-flag-line"></i> <span>{{ __('Translate') }}</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>



