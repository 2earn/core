@yield('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
<script src="{{ URL::asset('assets/js/layout.js') }}"></script>
@if(config('app.available_locales')[app()->getLocale()]['direction'] === 'rtl')
    <link href="{{ URL::asset('assets/css/bootstrap.rtl.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/css/icons.rtl.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/css/app.rtl.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/css/custom.rtl.css') }}" rel="stylesheet" type="text/css"/>
@else
    <link href="{{ URL::asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/css/custom.min.css') }}"  rel="stylesheet" type="text/css" />
@endif

