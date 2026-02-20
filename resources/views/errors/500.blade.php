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
                        <div class="col-md-8 col-lg-6 col-xl-5">
                            <div class="card mt-4">
                                <div class="card-body">
                                    <div class="text-center mt-sm-5 mb-4 text-white-50">
                                        <a href="#"
                                           class="d-inline-block auth-logo">
                                            <img src="{{ Vite::asset('resources/images/2earn.png') }}">
                                        </a>
                                    </div>
                                    <div class="text-center mt-sm-5 mb-4 text-white-50">
                                        <img src="{{ Vite::asset('resources/images/error_icon.png') }}">
                                    </div>
                                    <div class="text-center mt-sm-5 mb-4 text-white-50">
                                        <a href="{{route('home',app()->getLocale())}}" class="btn btn-success"><i
                                                class="mdi mdi-home me-1"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
