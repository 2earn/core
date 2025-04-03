<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <div class="flex-grow-1">
                <h5 class="card-title mb-0">{{ __('Buy Shares') }}</h5>
                @if($flash)
                    <div class="flash-background float-end">{{__('V I P')}}</div>
                @endif
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="modal-body">
            @if($flash)
                <div class="row pink col-12" role="alert">
                    <p>{{__('A mode for a')}}
                        <span class="pinkbold col-auto">
                                        {{$flashTimes}}</span>
                        {{__('times bonus over')}}
                        <span class="pinkbold col-auto">
                                        {{$flashPeriod}} {{__('hours')}}</span>
                        {{__('with a minimum of')}}
                        <span class="pinkbold col-auto">
                                        {{$flashMinShares}} {{__('Shares')}}
                                    </span>
                    </p>
                </div>
            @endif
            <div class="row @if($flash) alert-flash @else alert  @endif alert-info" role="alert">
                <strong>{{ __('Notice') }}: </strong>{{ __('buy_shares_notice') }}
            </div>
            <a href="{{route('user_balance_cb',app()->getLocale())}}"
               class="@if($cashBalance < $ammount) logoTopCashDanger  @else logoTopCash  @endif">
                <div class="row d-flex mt-1">
                    <div class="col-4 avatar-xs flex-shrink-1 ">
                                <span class="avatar-title bg-soft-info custom rounded fs-3">
                                    <i class="bx bx-dollar-circle text-info"></i>
                                </span>
                    </div>
                    <div class="col-8 text-primary text-uppercase fs-16 pt-1 ms-5">
                        <h5 class="@if($cashBalance < $ammount) logoTopCashDanger  @else logoTopCashLabel  @endif">  {{ __('Cash Balance') }}
                            : {{__('DPC')}}{{$soldeBuyShares->soldeCB}}</h5>
                    </div>
                </div>
            </a>
            <div class="row d-flex">
                <form class="needs-validation" novalidate>
                    <div class="row mt-2 ml-1 @if($flash) alert-flash @else alert  @endif alert-light">
                        <h5 class="ml-3">
                            <span class="form-label">{{ __('Buy For') }}:</span>
                        </h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item list-group-item-light">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio"
                                           name="inlineRadioOptions"
                                           checked
                                           id="inlineRadio1" value="me">
                                    <label class="form-check-label"
                                           for="inlineRadio1">{{ __('me') }}</label>
                                </div>
                            </li>
                            <li class="list-group-item list-group-item-light">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio"
                                           name="inlineRadioOptions"
                                           id="inlineRadio2" value="other" disabled>
                                    <label class="form-check-label"
                                           for="inlineRadio2">{{ __('other') }}</label>
                                </div>
                            </li>

                        </ul>
                        <div class="col-6 d-none" id="contact-select">
                            <div>
                                <label for="phone" class="form-label">{{ __('Mobile_Number') }}</label>
                                <input type="tel"
                                       class="@if($flash) form-control-flash @else form-control  @endif"
                                       name="mobile" id="phone" required>
                            </div>
                        </div>
                        <div class="col-6 d-none" id="bfs-select">
                            <span class="form-label mb-3">{{ __('BFS bonuses  for') }} </span>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="bfs-for"
                                           id="bfs-for-1"
                                           value="me">
                                    <label for="bfs-for-1"
                                           class="form-check-label">{{ __('me') }}</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="bfs-for"
                                           id="bfs-for-2"
                                           value="other">
                                    <label for="bfs-for-2"
                                           class="form-check-label">{{ __('The chosen user') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="simulator" class="row mt-3 mb-3">
                        @if($flash)
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="discount-time text-center">
                                    <h5 id="flash-timer1" class="mb-0 text-black"></h5>
                                </div>
                            </div>
                        @endif
                        <div class="col-6  @if($flash) ribbon-box right overflow-hidden @endif ">
                            <label for="ammount" class="col-form-label">{{ __('Amount_pay') }}
                                ( {{config('app.currency')}}
                                )</label>
                            <div class="input-group mb-3">
                                <input aria-describedby="simulateAmmount" type="number"
                                       max="{{round($cashBalance)}}"
                                       wire:keyup.debounce="simulateAmmount()" wire:model.lazy="ammount"
                                       id="ammount"
                                       class="form-control @if($flash) flash @endif">

                            </div>

                        </div>
                        <div class="col-6 @if($flash) ribbon-box right overflow-hidden @endif ">
                            <label for="action" class="col-form-label">
                                {{ __('Number of shares') }}
                            </label>
                            <div class="input-group mb-3">
                                <input aria-describedby="simulateAction" type="number"
                                       max="{{$maxActions}}"
                                       wire:keyup.debounce="simulateAction()" wire:model.lazy="action"
                                       id="action"
                                       class="form-control @if($flash) flash @endif">
                            </div>

                        </div>
                        @if($ammount)
                            <div class="col-12 text-muted">
                                <div
                                    class="alert alert-success alert-top-border alert-dismissible fade show material-shadow"
                                    role="alert">
                                    <i class="ri-notification-fill me-3 align-middle fs-16 text-success"></i><strong>{{__('Simulation Result')}}</strong>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    <label
                                        class="col-form-label">
                                        {{__('You pay')}}</label>
                                    <strong id="amount_val" class="col-form-label">
                                        {{$ammount}}
                                    </strong>
                                    <label
                                        class="col-form-label">{{config('app.currency')}} {{__('To buy')}}</label>
                                    <strong id="action_val" class="col-form-label">
                                        {{$action}}
                                    </strong>
                                    <label class="col-form-label">{{__('Actions')}}</label>
                                    @if($gift&&$profit && $profit>0)
                                        <table class="table table-success table-striped align-middle table-nowrap mb-0 table-sm">
                                            <thead>
                                            <tr>
                                                @if($gift)
                                                    <th scope="col">{{ __('Gifted Shares') }}</th>
                                                @endif
                                                @if($gift)
                                                    <td>
                                                        {{$gift}}
                                                    </td>
                                                @endif
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                @if($profit && $profit>0)
                                                    <th scope="col">{{ __('Profit') }}</th>
                                                @endif
                                                @if($profit && $profit>0)
                                                    <td>
                                                        {{formatSolde($profit, 2)}} ( {{config('app.currency')}})
                                                    </td>
                                                @endif
                                            </tr>
                                            </tbody>
                                        </table>
                                    @endif
                                </div>
                            </div>
                        @endif
                        <div class="col-lg-12 mt-3">
                            <div class="hstack gap-2 justify-content-end">
                                @if($flash)
                                    <button type="button" class="btn btn-outline-gold">
                                        {{__('Flash gift')}}

                                        {{$flashGift}}
                                        <span class="flash-background">{{$flashGift}}</span>
                                    </button>
                                    <button type="button" class="btn btn-outline-gold">
                                        {{__('Flash gain')}}
                                        <span class="flash-background">{{$flashGain}}$</span>
                                    </button>
                                @endif
                                @if(!$flash)
                                    <button type="button" class="btn btn-light"
                                            data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                                @endif
                                <button type="button" id="buy-action-submit"
                                        wire:loading.attr="disabled"
                                        wire:target="simulate"
                                        class="btn @if($flash) btn-flash @else btn-soft-primary  @endif swal2-styled d-inline-flex">
                                    {{ __('Submit') }}
                                    <div
                                        class="spinner-border spinner-border-sm mx-2 mt-1 buy-action-submit-spinner"
                                        role="status"></div>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script type="module">
        $(document).ready(function () {
                const input = document.querySelector("#phone");
                const iti = window.intlTelInput(input, {
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
                console.log('----');
                $("#buy-action-submit").on("click", function () {
                    console.log('-thth---');
                    this.disabled = true;
                    $('.buy-action-submit-spinner').show();
                    let ammount = parseFloat($('#amount_val').text()).toFixed(3);
                    let numberOfActions = parseInt($('#action').val());
                    let phone = $('#phone').val();
                    let me_or_other = $("input[name='inlineRadioOptions']:checked").val();
                    let bfs_for = $("input[name='bfs-for']:checked").val();
                    let country_code = iti.getSelectedCountryData().iso2;
                    $.ajax({
                        url: "{{ route('buyAction', app()->getLocale()) }}",
                        type: "POST",
                        data: {
                            me_or_other: me_or_other,
                            bfs_for: bfs_for,
                            phone: phone,
                            country_code: country_code,
                            numberOfActions: numberOfActions,
                            ammount: ammount,
                            vip: {{$flashTimes}},
                            flashMinShares: {{$flashMinShares}},
                            flash: "{{$flash}}",
                            actions: {{$actions}},
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function (data) {
                            let backgroundColor = "#27a706"
                            if (data.error) {
                                backgroundColor = "#ba0404";
                                Swal.fire({
                                    icon: "error",
                                    title: "{{__('Validation failed')}}",
                                    html: response.error.join('<br>')
                                });
                            }

                            $('.btn-close-buy-share').trigger('click')

                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: data.message,
                                text: data.message,
                                showConfirmButton: false,
                                timer: 2000,
                                showCloseButton: true
                            });

                            $('.buy-action-submit-spinner').hide();
                            location.reload();
                        },
                        error: function (data) {
                            var responseData = JSON.parse(data.responseText);
                            Swal.fire({
                                icon: 'error',
                                title: "{{__('Error in action purchase transaction')}}",
                                cancelButtonText: '{{__('Cancel')}}',
                                confirmButtonText: '{{__('Confirm')}}',
                                text: responseData.error[0]
                            });
                            $('.buy-action-submit-spinner').hide();
                        }
                    });
                    setTimeout(() => {
                        this.disabled = false;
                        $('.buy-action-submit-spinner').hide();
                    }, 2000);

                })
            }
        );
    </script>

    @if($flash)
        <script type="module">
            const millisecondsInOneDay = 86400000;
            const millisecondsInOneHour = 3600000;
            const millisecondsInOneMinute = 60000;

            var setEndDate6 = "{{$flashDate}}";
            var vipInterval;
            var vipInterval1;

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
                clearInterval(vipInterval);
            }

            if ($('#flash-timer1').length) {
                vipInterval1 = setInterval(function () {
                    countDownTimer(flashTimer, "flash-timer1");
                }, 1000);
            } else {
                clearInterval(vipInterval1);
            }

        </script>
    @endif
</div>
