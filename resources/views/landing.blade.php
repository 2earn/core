@extends('layouts.master-without-nav')

@section('title')
      Welcome
@endsection

@section('content')
      <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                  <div class="card mt-4">
                        <div class="card-body p-4 text-center">
                              <div class="mb-4">
                                    <img src="{{ Vite::asset('resources/images/2earn.png') }}" alt="" height="50">
                              </div>
                              <div class="p-2 mt-4">
                                    <h4>{{ __('Welcome to 2Earn.cash') }}</h4>
                                    <p class="text-muted">The ultimate platform for earning and growth.</p>
                                    <div class="mt-4">
                                          <a href="#"
                                                class="btn btn-success w-100">Login</a>
                                    </div>
                                    <div class="mt-4 text-center">
                                          <p class="mb-0">Don't have an account ? <a href="#"
                                                      class="fw-semibold text-primary text-decoration-underline"> Signup </a>
                                          </p>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
@endsection