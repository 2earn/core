<div class="app-menu navbar-menu">
    <div class="navbar-brand-box" title="{{ __('Version') . ' : ' . config('app.version') }}">
        <a href="{{ route('home', app()->getLocale(), false) }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ Vite::asset('resources/images/logo-sm.png') }}" alt="Logo Small" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ Vite::asset('resources/images/logo-dark.png') }}" alt="Logo Dark" height="35">
            </span>
        </a>
        <a id="MyHover" href="{{ route('home', app()->getLocale(), false) }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ Vite::asset('resources/images/logo-sm.png') }}" alt="Logo Small" height="40">
            </span>
            <span class="logo-lg">
                <img src="{{ Vite::asset('resources/images/logo-light.png') }}" alt="Logo Light" height="30">
            </span>
        </a>
        <button onclick="testClick()" type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" style="cursor: text">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>
    <div id="scrollbar" class="sidebar-scroll">
        <div class="container-fluid">
            <ul class="navbar-nav" id="navbar-nav">
                @foreach($menuItems as $item)
                    @if(isset($item['super_admin']) && $item['super_admin'])
                        @if(\App\Models\User::isSuperAdmin())
                            <li class="menu-title">
                                <span>{{ __($item['title']) }}</span>
                            </li>
                            @foreach($item['children'] as $child)
                                <li class="nav-item">
                                    <a href="{{ route($child['route'], app()->getLocale(), false) }}" class="nav-link menu-link">
                                        @if(isset($child['icon']))<i class="{{ $child['icon'] }}"></i>@endif
                                        <span>{{ __($child['title']) }}</span>
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    @elseif(isset($item['children']))
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link menu-link dropdown-toggle" data-bs-toggle="dropdown" role="button">
                                @if(isset($item['icon']))<i class="{{ $item['icon'] }}"></i>@endif
                                <span>{{ __($item['title']) }}</span>
                            </a>
                            <ul class="dropdown-menu">
                                @foreach($item['children'] as $child)
                                    <li>
                                        <a href="{{ route($child['route'], app()->getLocale(), false) }}" class="dropdown-item">
                                            @if(isset($child['icon']))<i class="{{ $child['icon'] }}"></i>@endif
                                            {{ __($child['title']) }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route($item['route'], app()->getLocale(), false) }}" class="nav-link menu-link">
                                @if(isset($item['icon']))<i class="{{ $item['icon'] }}"></i>@endif
                                <span>{{ __($item['title']) }}</span>
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
</div>
