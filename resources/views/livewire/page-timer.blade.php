<div class="auth-page-wrapper pt-5">
    <div class="auth-page-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    {{-- Header Section --}}
                    <div class="d-flex flex-column align-items-center mt-sm-1 pt-4 mb-4">
                        <header class="mb-sm-5 pb-sm-4 pb-5 text-center">
                            <h1 class="display-1 mb-sm-0">{{ __('Coming Soon') }}</h1>
                        </header>

                        <figure class="mb-0">
                            <img src="{{ $imagePath }}"
                                 alt="{{ __('Progress') }}: {{ $passedDays }} {{ __('days passed') }}"
                                 title="{{ __('Progress') }}: {{ number_format($passedDays * 100, 1) }}%"
                                 height="150"
                                 width="auto"
                                 class="move-animation"
                                 loading="lazy">
                        </figure>
                    </div>

                    {{-- Countdown Timer Section --}}
                    <div id="countdown"
                         class="countdownlist"
                         role="timer"
                         aria-live="polite"
                         aria-label="{{ __('Countdown timer') }}">
                        <div class="countdownlist-item">
                            <div class="count-title">{{ __('Days') }}</div>
                            <div class="count-num" aria-label="{{ $timeRemaining['days'] }} {{ __('days') }}">
                                {{ $timeRemaining['days'] }}
                            </div>
                        </div>
                        <div class="countdownlist-item">
                            <div class="count-title">{{ __('Hours') }}</div>
                            <div class="count-num" aria-label="{{ $timeRemaining['hours'] }} {{ __('hours') }}">
                                {{ $timeRemaining['hours'] }}
                            </div>
                        </div>
                        <div class="countdownlist-item">
                            <div class="count-title">{{ __('Minutes') }}</div>
                            <div class="count-num" aria-label="{{ $timeRemaining['minutes'] }} {{ __('minutes') }}">
                                {{ $timeRemaining['minutes'] }}
                            </div>
                        </div>
                        <div class="countdownlist-item">
                            <div class="count-title">{{ __('Seconds') }}</div>
                            <div class="count-num" aria-label="{{ $timeRemaining['seconds'] }} {{ __('seconds') }}">
                                {{ $timeRemaining['seconds'] }}
                            </div>
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
        // Update countdown every second
        setInterval(function() {
            if (typeof Livewire !== 'undefined') {
                Livewire.emit('decrementTime');
            }
        }, 1000);
    });
</script>
@endpush

