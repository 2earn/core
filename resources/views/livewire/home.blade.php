<div class="{{getContainerType()}}">
    @section('title')
        {{ __('Home') }}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Home') }}
        @endslot
    @endcomponent
    <div class="row">
        @include('layouts.flash-messages')
    </div>
    <div id="wellcome-message">
        <livewire:welcome-message/>
    </div>
    @if($flash)
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card vip-background">
                    <div class="card-body">
                        <div class="row col-12" role="alert">
                            <p>  {{__('Dear vip')}} : <br><strong
                                    class="mx-3">{{getUserDisplayedName(auth()->user()->idUser)}},</strong><br>
                                {{__('A mode for a')}} <span
                                    class="col-auto flash-red">{{$flashTimes}}</span> {{__('times bonus over')}}
                                <span
                                    class="col-auto flash-red">{{$flashPeriod}} {{__('hours')}}</span> {{__('with a minimum of')}}
                                <span
                                    class="col-auto flash-red">{{formatSolde($flashMinShares,0)}} {{__('Shares')}}</span>. {{__('il vous reste')}}
                                <span
                                    class="col-auto flash-red">{{formatSolde($vip->solde,0)}}{{__('Shares')}}</span>
                                {{__('à conssommer. avec lachat de')}}
                                <span
                                    class="col-auto flash-red">{{formatSolde($actions,0)}}</span>
                                {{__('actions')}} ,
                                {{__('et les benefices instentannés seront')}}
                                <span
                                    class="col-auto flash-red">{{formatSolde($benefices,2)}}                                    <span
                                        class="text-muted"> {{config('app.currency')}}</span>
                                </span>
                            </p>
                        </div>
                        <div class="row col-12">
                            <div class="discount-time text-center">
                                <h5 id="flash-timer" class="mb-0 flash-red"></h5>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endif
    <livewire:home-balances/>
    <livewire:bussiness-sectors-home/>
    <livewire:communication-board/>
    <livewire:home-stats/>
    @push('scripts')
        <script src="https://cdn.lordicon.com/lordicon.js"></script>
        @vite('resources/js/pages/form-validation.init.js');

        <script type="module">
            $(document).ready(function () {
                    const input = document.querySelector("#phone");
                    if (input) {
                        window.intlTelInput(input, {
                            initialCountry: "auto",
                            autoFormat: true,
                            separateDialCode: true,
                            useFullscreenPopup: false,
                            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.10/build/js/utils.js"
                        });
                        $('[name="inlineRadioOptions"]').on('change', function () {
                            if ($('#inlineRadio2').is(':checked')) {
                                $('#contact-select').removeClass('d-none');
                                $('#bfs-select').removeClass('d-none');
                            } else {
                                $('#contact-select').addClass('d-none');
                                $('#bfs-select').addClass('d-none');
                            }
                        });
                    }
                }
            );

        </script>

        @if($flash)
            <script type="module">
                const millisecondsInOneDay = 86400000;
                const millisecondsInOneHour = 3600000;
                const millisecondsInOneMinute = 60000;

                var setEndDate6 = "{{$flashDate}}";
                var vipInterval = null;
                var vipInterval1 = null;

                function formatCountDown(days, hours, minutes, seconds) {
                    var countDownValue = "- ";
                    if (days !== "00") {
                        countDownValue += days + " {{__('days')}}";
                    }
                    if (hours !== "00") {
                        countDownValue += days !== "00" ? " : " : "";
                        countDownValue += hours + " {{__('hours')}}";
                    }
                    if (minutes !== "00") {
                        countDownValue += (hours !== "00" || days !== "00") ? " : " : "";
                        countDownValue += minutes + " {{__('minutes')}}";
                    }
                    if (seconds !== "00") {
                        countDownValue += (hours !== "00" || days !== "00" || minutes !== "00") ? " : " : "";
                        countDownValue += seconds + " {{__('seconds')}}";
                    }
                    return countDownValue;
                }

                function startCountDownDate(dateVal) {
                    const d1 = new Date(Date.parse('{{ date('Y-m-d H:i:s')}}'));
                    const d2 = new Date();
                    return new Date(new Date(dateVal).getTime() + (d2 - d1));
                }

                function countDownTimer(start, targetDOM) {
                    var now = new Date().getTime();
                    var distance = start - now;
                    var days = Math.floor(distance / (millisecondsInOneDay));
                    var hours = Math.floor((distance % (millisecondsInOneDay)) / (millisecondsInOneHour));
                    var minutes = Math.floor((distance % (millisecondsInOneHour)) / (millisecondsInOneMinute));
                    var seconds = Math.floor((distance % (millisecondsInOneMinute)) / 1000);

                    days = days < 10 ? "0" + days : days;
                    hours = hours < 10 ? "0" + hours : hours;
                    minutes = minutes < 10 ? "0" + minutes : minutes;
                    seconds = seconds < 10 ? "0" + seconds : seconds;
                    if ($("#" + targetDOM).length) {
                        document.querySelector("#" + targetDOM).textContent = formatCountDown(days, hours, minutes, seconds);
                        if (distance < 0) {
                            document.querySelector("#" + targetDOM).textContent = "00 : 00 : 00 : 00";
                        }
                    }
                }

                var flashTimer = startCountDownDate(setEndDate6);
                if ($('#flash-timer').length) {
                    vipInterval = setInterval(function () {
                        countDownTimer(flashTimer, "flash-timer");
                    }, 1000);
                } else {
                    if (vipInterval) {
                        clearInterval(vipInterval);
                        vipInterval = null;
                    }
                }

                if ($('#flash-timer1').length) {
                    vipInterval1 = setInterval(function () {
                        countDownTimer(flashTimer, "flash-timer1");
                    }, 1000);
                } else {
                    if (vipInterval1) {
                        clearInterval(vipInterval1);
                        vipInterval1 = null;
                    }
                }

            </script>
        @endif
    @endpush
    @push('styles')
        <style>
            .hover-scale {
                transition: transform .15s ease, box-shadow .15s ease;
            }

            .hover-scale:hover {
                transform: translateY(-6px);
                box-shadow: 0 10px 30px rgba(33, 37, 41, 0.08);
            }

            .avatar-sm {
                width: 48px;
                height: 48px;
            }

            .badge.align-middle {
                padding: .45em .6em;
                font-weight: 600;
            }
        </style>
    @endpush
</div>
