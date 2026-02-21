@extends('layouts.master-without-nav')

@section('title')
      {{ __('Welcome to CORE 2Earn.cash') }}
@endsection

@push('css')
      <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&family=Outfit:wght@400;700&display=swap"
            rel="stylesheet">
      @vite(['resources/css/landing.css'])
@endpush

@section('content')
      <div class="landing-wrapper">
            <nav class="navbar-custom">
                  <div class="nav-brand">
                        <img src="{{ Vite::asset('resources/images/logo.jpeg') }}" alt="CORE Logo">
                  </div>
            </nav>

            <footer style="padding: 4rem 2rem; border-top: 1px solid var(--glass-border); text-align: center;">
                  <p class="text-muted">&copy; {{ date('Y') }} CORE 2Earn.cash. {{ __('All rights reserved.') }}</p>
            </footer>
      </div>
@endsection