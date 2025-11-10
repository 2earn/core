<div class="auth-page-wrapper pt-5 min-vh-100 d-flex align-items-center">
    <div class="auth-page-content w-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10 col-xl-8">
                    {{-- Header Section --}}
                    <div class="text-center mb-5">
                        <header class="mb-4">
                            <h1 class="display-1 fw-bold mb-3 text-primary">{{ __('Coming Soon') }}</h1>
                            <p class="lead text-muted fs-4">{{ __('We are working hard to bring you something amazing') }}</p>
                        </header>

                        {{-- Progress Image --}}
                        <div class="my-5 py-4">
                            <figure class="mb-4">
                                <img src="{{ $imagePath }}"
                                     alt="{{ __('Progress') }}: {{ $passedDays }} {{ __('days passed') }}"
                                     title="{{ __('Progress') }}: {{ number_format($passedDays * 100, 1) }}%"
                                     height="180"
                                     width="auto"
                                     class="move-animation img-fluid"
                                     loading="lazy">
                            </figure>
                            <div class="progress mx-auto" style="max-width: 400px; height: 8px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                     role="progressbar"
                                     style="width: {{ number_format($passedDays * 100, 1) }}%"
                                     aria-valuenow="{{ number_format($passedDays * 100, 1) }}"
                                     aria-valuemin="0"
                                     aria-valuemax="100">
                                </div>
                            </div>
                            <small class="text-muted d-block mt-2">{{ number_format($passedDays * 100, 1) }}% {{ __('Complete') }}</small>
                        </div>
                    </div>

                    {{-- Countdown Timer Section --}}
                    <div class="card border-0 shadow-lg">
                        <div class="card-body p-4 p-md-5">
                            <h3 class="text-center mb-4 fw-semibold">{{ __('Launch Countdown') }}</h3>
                            <div id="countdown"
                                 class="row g-3 g-md-4 justify-content-center"
                                 role="timer"
                                 aria-live="polite"
                                 aria-label="{{ __('Countdown timer') }}">
                                <div class="col-6 col-sm-3">
                                    <div class="card bg-soft-primary bg-gradient text-white border-0 shadow-sm h-100">
                                        <div class="card-body text-center p-1 p-md-4">
                                            <div class="display-4 fw-bold mb-2" aria-label="{{ $timeRemaining['days'] }} {{ __('days') }}">
                                                {{ str_pad($timeRemaining['days'], 2, '0', STR_PAD_LEFT) }}
                                            </div>
                                            <div class="text-uppercase small fw-semibold opacity-75">{{ __('Days') }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-3">
                                    <div class="card bg-soft-info bg-gradient text-white border-0 shadow-sm h-100">
                                        <div class="card-body text-center p-1 p-md-4">
                                            <div class="display-4 fw-bold mb-2" aria-label="{{ $timeRemaining['hours'] }} {{ __('hours') }}">
                                                {{ str_pad($timeRemaining['hours'], 2, '0', STR_PAD_LEFT) }}
                                            </div>
                                            <div class="text-uppercase small fw-semibold opacity-75">{{ __('Hours') }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-3">
                                    <div class="card bg-soft-warning bg-gradient text-white border-0 shadow-sm h-100">
                                        <div class="card-body text-center p-1 p-md-4">
                                            <div class="display-4 fw-bold mb-2" aria-label="{{ $timeRemaining['minutes'] }} {{ __('minutes') }}">
                                                {{ str_pad($timeRemaining['minutes'], 2, '0', STR_PAD_LEFT) }}
                                            </div>
                                            <div class="text-uppercase small fw-semibold opacity-75">{{ __('Minutes') }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-3">
                                    <div class="card bg-soft-danger bg-gradient text-white border-0 shadow-sm h-100">
                                        <div class="card-body text-center p-1 p-md-4">
                                            <div class="display-4 fw-bold mb-2" aria-label="{{ $timeRemaining['seconds'] }} {{ __('seconds') }}">
                                                {{ str_pad($timeRemaining['seconds'], 2, '0', STR_PAD_LEFT) }}
                                            </div>
                                            <div class="text-uppercase small fw-semibold opacity-75">{{ __('Seconds') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Optional: Call to Action Section --}}
                    <div class="text-center mt-5 pt-4">
                        <p class="text-muted mb-3">{{ __('Stay tuned for updates') }}</p>
                        <div class="d-flex gap-3 justify-content-center flex-wrap">
                            <a href="{{ route('home',app()->getLocale()) }}" class="btn btn-outline-primary btn-lg">
                                <i class="ri-home-line me-2"></i>{{ __('Back to Home') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Update countdown every second using Livewire 3 syntax
        setInterval(function() {
            @this.call('decrementTime');
        }, 1000);
    });
</script>
@endpush

