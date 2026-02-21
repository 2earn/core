@extends('layouts.master-without-nav')

@section('title')
      {{ __('Welcome to CORE 2Earn.cash') }}
@endsection

@push('css')
      <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&family=Outfit:wght@400;700&display=swap"
            rel="stylesheet">
      <link rel="stylesheet" href="{{ asset('resources/css/landing.css') }}">
@endpush

@section('content')
      <div class="landing-wrapper">
            <div class="hero-section">
                  <div class="hero-content">
                        <div class="glass-card">
                              <div class="logo-container">
                                    <img src="{{ Vite::asset('resources/images/logo.jpeg') }}" alt="CORE Logo"
                                          class="logo-img">
                              </div>
                              <h1>{{ __('Elevate Your Earnings') }}</h1>
                              <p class="lead-text">
                                    {{ __('Join the ultimate platform designed for community growth, innovative earning streams, and future-proof financial empowerment.') }}
                              </p>

                              <div class="feature-grid">
                                    <div class="feature-item">
                                          <div class="feature-icon">🚀</div>
                                          <h5>{{ __('Fast Growth') }}</h5>
                                          <p class="small text-muted mb-0">{{ __('Accelerate your potential.') }}</p>
                                    </div>
                                    <div class="feature-item">
                                          <div class="feature-icon">🛡️</div>
                                          <h5>{{ __('Secure') }}</h5>
                                          <p class="small text-muted mb-0">{{ __('Advanced protection.') }}</p>
                                    </div>
                                    <div class="feature-item">
                                          <div class="feature-icon">💎</div>
                                          <h5>{{ __('Premium') }}</h5>
                                          <p class="small text-muted mb-0">{{ __('Exclusive rewards.') }}</p>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
@endsection