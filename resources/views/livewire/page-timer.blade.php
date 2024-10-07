@extends('layouts.master')
@section('title')
    @lang('translation.coming-soon')
@endsection
@section('content')

    <div class="auth-page-wrapper pt-5">
        <div class="auth-page-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center mt-sm-1 pt-4 mb-4">
                        <div class="mb-sm-5 pb-sm-4 pb-5">
                            <h1 class="display-1 coming-soon-text">Coming Soon</h1>
                        </div>
                        <img src="{{ Vite::asset('resources/images/timer/1.png') }}" alt=""
                             height="120" class="move-animation">
                    </div>

                    <div class="row justify-content-center mt-5">
                        <div class="col-lg-8">
                            <div id="countdown" class="countdownlist">
                                <div class="countdownlist-item">
                                    <div class="count-title">Days</div>
                                    <div class="count-num">{{ $this->days }}</div>
                                </div>
                                <div class="countdownlist-item">
                                    <div class="count-title">Hours</div>
                                    <div class="count-num">{{ $this->hours }}</div>
                                </div>
                                <div class="countdownlist-item">
                                    <div class="count-title">Minutes</div>
                                    <div class="count-num">{{ $this->minutes }}</div>
                                </div>
                                <div class="countdownlist-item">
                                    <div class="count-title">Seconds</div>
                                    <div class="count-num">{{ $this->seconds }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center mt-5">
                        <div class="col-lg-12 text-center mt-sm-2 pt-4 mb-4">
                            <h4>Get notified when we launch</h4>
                            <p class="text-muted">Don't worry we will not spam you 😊</p>
                        </div>
                    </div>
                        <div class="input-group countdown-input-group mx-auto my-4">
                            <input type="email" class="form-control border-light shadow"
                                   placeholder="Enter your email address" aria-label="search result"
                                   aria-describedby="button-email">
                            <button class="btn btn-success" type="button" id="button-email">Send<i
                                    class="ri-send-plane-2-fill align-bottom ms-2"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->

            <!-- end container -->
        </div>
            <!-- end auth page content -->

        <!-- end auth-page-wrapper -->
@endsection
@section('script')
    <script src="{{ URL::asset('build/libs/particles.js/particles.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/particles.app.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/coming-soon.init.js') }}"></script>
@endsection
