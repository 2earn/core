@extends('layouts.master-without-nav')

@section('title')
      {{ __('Welcome to CORE 2Earn.cash') }}
@endsection

@section('content')
      <div class="row justify-content-center">
            <div class="col-12">
                  <div class="card mt-4">
                        <div class="card-body p-4 text-center">
                              <div class="mb-4">
                                    <img src="{{ Vite::asset('resources/images/logo.jpeg') }}" alt="" height="250">
                              </div>
                              <div class="p-2 mt-4">
                                    <h4>{{ __('Welcome to CORE 2Earn.cash') }}</h4>
                                    <p class="text-muted">{{ __('The ultimate platform for earning and growth.') }}</p>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
@endsection