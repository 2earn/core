@yield('css')
<!-- vite -->
@vite(['resources/css/select2.min.css','resources/js/layout.js'])
<!-- vite -->
@if(config('app.available_locales')[app()->getLocale()]['direction'] === 'rtl')
    <!-- vite -->
    @vite(['resources/css/bootstrap-rtl.css','resources/css/icons-rtl.css','resources/css/app-rtl.css','resources/css/custom-rtl.css'])
    <!-- vite -->
@else
    <!-- vite -->
    @vite(['resources/css/bootstrap.min.css','resources/css/icons.css','resources/css/app.css','resources/css/custom.css'])
    <!-- vite -->
@endif
