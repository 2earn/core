<div class="auth-page-wrapper pt-5 min-vh-100 d-flex align-items-center justify-content-center">
    <div class="auth-page-content w-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10 col-xl-9">
                    <header class="text-center mb-5 pb-3">
                        <div class="mb-4">
                            <h1 class="display-1 fw-bold mb-4 text-primary">
                                <i class="ri-time-line me-3"></i>{{ __('Coming Soon') }}
                            </h1>
                            <p class="lead text-muted fs-3 mb-3">
                                {{ __('We are working hard to bring you something amazing') }}
                            </p>
                            <p class="text-muted fs-5 mb-0">
                                {{ __('Our team is putting the finishing touches on an exciting new feature') }}
                            </p>
                        </div>
                    </header>

                    <section class="mb-5 pb-3" aria-labelledby="progress-heading">
                        <h2 id="progress-heading" class="visually-hidden">{{ __('Development Progress') }}</h2>
                        <div class="text-center my-5 py-4">
                            <figure class="mb-4">
                                <img src="{{ $imagePath }}"
                                     alt="{{ __('Development progress visualization showing') }} {{ number_format($passedDays * 100, 1) }}% {{ __('completion') }}"
                                     title="{{ __('Project Progress') }}: {{ number_format($passedDays * 100, 1) }}% {{ __('Complete') }}"
                                     height="180"
                                     width="auto"
                                     class="move-animation img-fluid rounded"
                                     loading="lazy">
                                <figcaption class="visually-hidden">
                                    {{ __('Progress indicator showing project completion status') }}
                                </figcaption>
                            </figure>

                            <div class="progress mx-auto mb-3" style="max-width: 500px; height: 10px;"
                                 role="progressbar"
                                 aria-valuenow="{{ number_format($passedDays * 100, 1) }}"
                                 aria-valuemin="0"
                                 aria-valuemax="100"
                                 aria-label="{{ __('Development progress') }}: {{ number_format($passedDays * 100, 1) }}%">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                     style="width: {{ number_format($passedDays * 100, 1) }}%">
                                    <span class="visually-hidden">{{ number_format($passedDays * 100, 1) }}% {{ __('Complete') }}</span>
                                </div>
                            </div>

                            <div class="d-flex justify-content-center align-items-center gap-2 flex-wrap">
                                <span class="badge bg-success-subtle text-success px-3 py-2 fs-6">
                                    <i class="ri-check-double-line me-1"></i>
                                    {{ number_format($passedDays * 100, 1) }}% {{ __('Complete') }}
                                </span>
                                <span class="text-muted">•</span>
                                <span class="badge bg-primary-subtle text-primary px-3 py-2 fs-6">
                                    <i class="ri-calendar-check-line me-1"></i>
                                    {{ $passedDays }} {{ __('days passed') }}
                                </span>
                            </div>
                        </div>
                    </section>

                    <section class="mb-5" aria-labelledby="countdown-heading">
                        <div class="card border-0 shadow-lg">
                            <div class="card-body p-4 p-md-5">
                                <div class="text-center mb-4">
                                    <h2 id="countdown-heading" class="h3 fw-semibold mb-2">
                                        <i class="ri-rocket-line me-2 text-primary"></i>{{ __('Launch Countdown') }}
                                    </h2>
                                    <p class="text-muted mb-0">{{ __('Time remaining until launch') }}</p>
                                </div>

                                <div id="countdown"
                                     class="row g-3 g-md-4 justify-content-center"
                                     role="timer"
                                     aria-live="polite"
                                     aria-atomic="true"
                                     aria-label="{{ __('Countdown timer showing') }} {{ $timeRemaining['days'] }} {{ __('days') }}, {{ $timeRemaining['hours'] }} {{ __('hours') }}, {{ $timeRemaining['minutes'] }} {{ __('minutes') }}, {{ __('and') }} {{ $timeRemaining['seconds'] }} {{ __('seconds remaining') }}">

                                    <div class="col-6 col-sm-3">
                                        <article class="card bg-primary bg-gradient text-white border-0 shadow-sm h-100">
                                            <div class="card-body text-center p-3 p-md-4 d-flex flex-column justify-content-center">
                                                <div class="display-3 fw-bold mb-2 lh-1">
                                                    {{ str_pad($timeRemaining['days'], 2, '0', STR_PAD_LEFT) }}
                                                </div>
                                                <div class="text-uppercase small fw-semibold opacity-90 letter-spacing-1">
                                                    {{ __('Days') }}
                                                </div>
                                                <span class="visually-hidden">{{ $timeRemaining['days'] }} {{ __('days') }}</span>
                                            </div>
                                        </article>
                                    </div>

                                    <div class="col-6 col-sm-3">
                                        <article class="card bg-info bg-gradient text-white border-0 shadow-sm h-100">
                                            <div class="card-body text-center p-3 p-md-4 d-flex flex-column justify-content-center">
                                                <div class="display-3 fw-bold mb-2 lh-1">
                                                    {{ str_pad($timeRemaining['hours'], 2, '0', STR_PAD_LEFT) }}
                                                </div>
                                                <div class="text-uppercase small fw-semibold opacity-90 letter-spacing-1">
                                                    {{ __('Hours') }}
                                                </div>
                                                <span class="visually-hidden">{{ $timeRemaining['hours'] }} {{ __('hours') }}</span>
                                            </div>
                                        </article>
                                    </div>

                                    <div class="col-6 col-sm-3">
                                        <article class="card bg-warning bg-gradient text-white border-0 shadow-sm h-100">
                                            <div class="card-body text-center p-3 p-md-4 d-flex flex-column justify-content-center">
                                                <div class="display-3 fw-bold mb-2 lh-1">
                                                    {{ str_pad($timeRemaining['minutes'], 2, '0', STR_PAD_LEFT) }}
                                                </div>
                                                <div class="text-uppercase small fw-semibold opacity-90 letter-spacing-1">
                                                    {{ __('Minutes') }}
                                                </div>
                                                <span class="visually-hidden">{{ $timeRemaining['minutes'] }} {{ __('minutes') }}</span>
                                            </div>
                                        </article>
                                    </div>

                                    <div class="col-6 col-sm-3">
                                        <article class="card bg-danger bg-gradient text-white border-0 shadow-sm h-100">
                                            <div class="card-body text-center p-3 p-md-4 d-flex flex-column justify-content-center">
                                                <div class="display-3 fw-bold mb-2 lh-1">
                                                    {{ str_pad($timeRemaining['seconds'], 2, '0', STR_PAD_LEFT) }}
                                                </div>
                                                <div class="text-uppercase small fw-semibold opacity-90 letter-spacing-1">
                                                    {{ __('Seconds') }}
                                                </div>
                                                <span class="visually-hidden">{{ $timeRemaining['seconds'] }} {{ __('seconds') }}</span>
                                            </div>
                                        </article>
                                    </div>
                                </div>

                                <div class="text-center mt-4 pt-3 border-top">
                                    <p class="text-muted mb-2">
                                        <i class="ri-information-line me-1"></i>
                                        {{ __('The countdown updates in real-time') }}
                                    </p>
                                    <small class="text-muted">
                                        {{ __('Mark your calendars and check back soon') }}!
                                    </small>
                                </div>
                            </div>
                        </div>
                    </section>

                    <footer class="text-center mt-5 pt-4">
                        <div class="mb-4">
                            <p class="text-muted fs-5 mb-3">
                                <i class="ri-notification-3-line me-2"></i>{{ __('Stay tuned for updates') }}
                            </p>
                            <p class="text-muted small mb-4">
                                {{ __('Want to be notified when we launch? Keep an eye on your notifications') }}
                            </p>
                        </div>
                        <nav aria-label="{{ __('Page navigation') }}">
                            <div class="d-flex gap-3 justify-content-center flex-wrap">
                                <a href="{{ route('home', app()->getLocale()) }}"
                                   class="btn btn-primary btn-lg shadow-sm"
                                   aria-label="{{ __('Return to home page') }}">
                                    <i class="ri-home-line me-2"></i>{{ __('Back to Home') }}
                                </a>
                            </div>
                        </nav>
                    </footer>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        setInterval(function() {
            @this.call('decrementTime');
        }, 1000);
    });
</script>
@endpush

