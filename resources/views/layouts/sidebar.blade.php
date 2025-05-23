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
        @livewire('side-bar-menu')
        <div class="sidebar-background"></div>
    </div>
    <div class="vertical-overlay">
    </div>
</div>
