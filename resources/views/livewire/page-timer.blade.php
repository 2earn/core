<div class="auth-page-wrapper pt-5">
    <div class="auth-page-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center mt-sm-1 pt-4 mb-4">
                    <div class="mb-sm-5 pb-sm-4 pb-5">
                        <h1 class="display-1 mb-sm-0">Coming Soon</h1>
                    </div>

                    <img src="{{ $imagePath }}" alt="" height="120" class="move-animation">
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
            </div>
        </div>
    </div>
</div>
<script>
    setInterval(function () {
        Livewire.emit('decrementTime');
    }, 1000);
</script>
@section('script')
    <script src="{{ URL::asset('build/libs/particles.js/particles.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/particles.app.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/coming-soon.init.js') }}"></script>
@endsection
