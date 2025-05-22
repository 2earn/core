@extends('layouts.master-without-nav')
@section('title')
    @lang('Error page')
@endsection
@section('body')
    <body>
    @endsection
    @section('content')
        <div class="auth-page-wrapper py-5 d-flex justify-content-center align-items-center min-vh-100">
            <div class="auth-page-content overflow-hidden p-0">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                                    <span class="logo-sm">
                <img src="{{ Vite::asset('resources/images/logo-sm.png') }}" alt="" height="22">
            </span>
                        <span class="logo-lg">
                <img src="{{ Vite::asset('resources/images/logo-dark.png') }}" alt="" height="35px">
            </span>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-xl-4 text-center">
                            <div class="error-500 position-relative">
                                <h1>{{__('Error')}}</h1>
                            </div>
                            <div>
                                <h4>{{__('Internal Server Error')}}!</h4>
                                <p class="text-muted w-75 mx-auto">{{__('Server Error')}} @yield('code')
                                    . {{__("We're not exactly sure what happened, but our servers say something is wrong.")}}</p>
                                <a href="{{route('home',app()->getLocale())}}" class="btn btn-success"><i
                                        class="mdi mdi-home me-1"></i>{{__('Back to home')}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
