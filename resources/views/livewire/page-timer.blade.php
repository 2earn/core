<div class="auth-page-wrapper pt-5">
    <div class="auth-page-content">
        <div class="row">
            <div class="d-flex flex-column align-items-center mt-sm-1 pt-4 mb-4">
                <div class="mb-sm-5 pb-sm-4 pb-5 text-center">
                    <h1 class="display-1 mb-sm-0">{{ __('Coming Soon') }}</h1>
                </div>
                <img src="{{ $imagePath }}" alt="" height="150" class="move-animation">
            </div>

            <div id="countdown" class="countdownlist">
                <div class="countdownlist-item">
                    <div class="count-title">{{__('Days')}}</div>
                    <div class="count-num">{{ $timeRemaining['days'] }}</div>
                </div>
                <div class="countdownlist-item">
                    <div class="count-title">{{__('Hours')}}</div>
                    <div class="count-num">{{ $timeRemaining['hours'] }} </div>
                </div>
                <div class="countdownlist-item">
                    <div class="count-title">{{__('Minutes')}}</div>
                    <div class="count-num">{{ $timeRemaining['minutes'] }}</div>
                </div>
                <div class="countdownlist-item">
                    <div class="count-title">{{__('Seconds')}}</div>
                    <div class="count-num">{{ $timeRemaining['seconds'] }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@section('script')
    @vite(['resources/libs/particles.js/particles.js', 'resources/js/pages/particles.app.js'])
    <script>
        setInterval(function () {
            @this.
            decrementTime();
        }, 1000);
    </script>
@endsection

