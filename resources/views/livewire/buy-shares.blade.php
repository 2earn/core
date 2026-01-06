<div class="col-12 card shadow-sm">
    <div class="card-header">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <div class="flex-shrink-0">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-18">
                            <i class="ri-shopping-cart-line"></i>
                        </div>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h5 class="card-title mb-0 fw-semibold text-dark">{{ __('Buy Shares') }}</h5>
                    <p class="text-muted small mb-0">{{ __('Invest in your future') }}</p>
                </div>
            </div>
            @if($flash)
                <div class="badge bg-gradient bg-warning text-dark fs-14 px-3 py-2 flash-background">
                    <i class="ri-vip-crown-line me-1"></i>{{__('V I P')}}
                </div>
            @endif
        </div>
    </div>
    <div class="card-body p-4">
        @if($flash)
            <div class="alert alert-warning border-warning bg-warning-subtle mb-3" role="alert">
                <div class="d-flex align-items-start gap-3">
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <div class="avatar-title bg-warning text-white rounded-circle fs-20">
                                <i class="ri-vip-crown-fill"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="alert-heading text-warning fw-bold mb-2">
                            <i class="ri-flashlight-fill"></i> {{__('VIP Flash Mode Active!')}}
                        </h5>
                        <p class="mb-0 text-dark">
                            {{__('A mode for a')}}
                            <span class="badge bg-warning text-dark fw-bold mx-1">{{$flashTimes}}x</span>
                            {{__('times bonus over')}}
                            <span
                                class="badge bg-warning text-dark fw-bold mx-1">{{$flashPeriod}} {{__('hours')}}</span>
                            {{__('with a minimum of')}}
                            <span
                                class="badge bg-warning text-dark fw-bold mx-1">{{$flashMinShares}} {{__('Shares')}}</span>
                        </p>
                    </div>
                </div>
            </div>
        @endif
        <div
            class="alert @if($flash) alert-warning border-warning bg-warning-subtle @else alert-info border-info bg-info-subtle @endif mb-3"
            role="alert">
            <div class="d-flex align-items-start gap-2">
                <i class="ri-information-line fs-18 @if($flash) text-warning @else text-info @endif mt-1"></i>
                <div>
                    <strong class="@if($flash) text-warning @else text-info @endif">{{ __('Notice') }}: </strong>
                    <span class="text-dark">{{ __('buy_shares_notice') }}</span>
                </div>
            </div>
        </div>
        <a href="{{route('user_balance_cb',app()->getLocale())}}" class="text-decoration-none">
            <div
                class="card @if($cashBalance < $ammount) border-danger bg-danger-subtle @else border-info bg-info-subtle @endif mb-3">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="flex-shrink-0">
                            <div class="avatar-md">
                                <div
                                    class="avatar-title @if($cashBalance < $ammount) bg-danger-subtle text-danger @else bg-info-subtle text-info @endif rounded-circle fs-24">
                                    <i class="bx bx-dollar-circle"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <p class="text-muted text-uppercase fw-medium mb-1 small">{{ __('Cash Balance') }}</p>
                            <h4 class="mb-0 @if($cashBalance < $ammount) text-danger @else text-info @endif fw-bold">
                                {{__('DPC')}} {{$soldeBuyShares->soldeCB}}
                            </h4>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ri-arrow-right-s-line fs-24 @if($cashBalance < $ammount) text-danger @else text-info @endif"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        <div class="row">
            <form class="needs-validation" novalidate>
                <div class="card @if($flash) border-warning bg-warning-subtle @else border-light bg-light @endif mb-3">
                    <div class="card-body p-3">
                        <h6 class="card-title mb-3 fw-semibold">
                            <i class="ri-user-line me-1"></i>{{ __('Buy For') }}:
                        </h6>
                        <div class="d-flex gap-4 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                       name="inlineRadioOptions"
                                       checked
                                       id="inlineRadio1" value="me">
                                <label class="form-check-label fw-medium"
                                       for="inlineRadio1">{{ __('me') }}</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                       name="inlineRadioOptions"
                                       id="inlineRadio2" value="other" disabled>
                                <label class="form-check-label fw-medium"
                                       for="inlineRadio2">{{ __('other') }}</label>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6 d-none" id="contact-select">
                                <label for="phone" class="form-label fw-medium">
                                    <i class="ri-phone-line me-1"></i>{{ __('Mobile_Number') }}
                                </label>
                                <input type="tel"
                                       class="form-control"
                                       name="mobile" id="phone" required>
                            </div>
                            <div class="col-md-6 d-none" id="bfs-select">
                                <label class="form-label fw-medium mb-2">
                                    <i class="ri-gift-line me-1"></i>{{ __('BFS bonuses  for') }}
                                </label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="bfs-for"
                                               id="bfs-for-1"
                                               value="me">
                                        <label for="bfs-for-1"
                                               class="form-check-label">{{ __('me') }}</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="bfs-for"
                                               id="bfs-for-2"
                                               value="other">
                                        <label for="bfs-for-2"
                                               class="form-check-label">{{ __('The chosen user') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="simulator">
                    @if($flash)
                        <div class="card border-warning bg-warning-subtle mb-3">
                            <div class="card-body p-3 text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                                    <i class="ri-timer-flash-line fs-20 text-warning"></i>
                                    <h6 class="mb-0 fw-semibold text-warning">{{__('VIP Flash Sale Ends In')}}</h6>
                                </div>
                                <h5 id="flash-timer1" class="mb-0 text-dark fw-bold"></h5>
                            </div>
                        </div>
                    @endif
                    <div class="card border-primary bg-primary-subtle mb-3">
                        <div class="card-body p-3">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="ammount" class="form-label fw-semibold">
                                        <i class="ri-money-dollar-circle-line me-1"></i>{{ __('Amount_pay') }}
                                        ({{config('app.currency')}})
                                    </label>
                                    <input aria-describedby="simulateAmmount" type="number"
                                           max="{{round($cashBalance)}}"
                                           wire:keyup.debounce="simulateAmmount()" wire:model.lazy="ammount"
                                           id="ammount"
                                           oninput="if(this.value !== '' && Number(this.value) < 1) this.value = 1"
                                           class="form-control form-control @if($flash) border-warning @endif"
                                           placeholder="0.00">
                                </div>
                                <div class="col-md-6">
                                    <label for="action" class="form-label fw-semibold">
                                        <i class="ri-stock-line me-1"></i>{{ __('Number of shares') }}
                                    </label>
                                    <input aria-describedby="simulateAction" type="number"
                                           max="{{$maxActions}}"
                                           wire:keyup.debounce="simulateAction()" wire:model.lazy="action"
                                           id="action"
                                           class="form-control form-control @if($flash) border-warning @endif"
                                           placeholder="0">
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($ammount)
                        <div class="card border-success bg-success-subtle">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <i class="ri-calculator-line fs-18 text-success"></i>
                                    <h6 class="mb-0 fw-semibold text-success">{{__('Simulation Result')}}</h6>
                                </div>
                                <div class="row g-2 mb-3">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="text-muted">{{__('You pay')}}:</span>
                                            <span class="fw-bold text-dark" id="amount_val">{{$ammount}}</span>
                                            <span class="text-muted">{{config('app.currency')}}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="text-muted">{{__('To buy')}}:</span>
                                            <span class="fw-bold text-dark" id="action_val">{{$action}}</span>
                                            <span class="text-muted">{{__('Actions')}}</span>
                                        </div>
                                    </div>
                                </div>
                                @if($gift&&$profit && $profit>0)
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered mb-0 bg-white">
                                            <thead class="table-success">
                                            <tr>
                                                @if($gift)
                                                    <th class="fw-semibold">
                                                        <i class="ri-gift-line me-1"></i>{{ __('Gifted Shares') }}
                                                    </th>
                                                @endif
                                                @if($profit && $profit>0)
                                                    <th class="fw-semibold">
                                                        <i class="ri-money-dollar-circle-line me-1"></i>{{ __('Profit') }}
                                                    </th>
                                                @endif
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                @if($gift)
                                                    <td class="fw-bold text-success">{{$gift}}</td>
                                                @endif
                                                @if($profit && $profit>0)
                                                    <td class="fw-bold text-success">
                                                        {{formatSolde($profit, 2)}} {{config('app.currency')}}
                                                    </td>
                                                @endif
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                    <div class="col-12 mt-3">
                        <div class="hstack gap-2 justify-content-end flex-wrap">
                            @if($flash)
                                <button type="button" id="flash-gift"
                                        class="btn btn-flash-gift btn-sm d-flex align-items-center gap-2">
                                    <i class="ri-gift-fill fs-16 flash-gift-icon"></i>
                                    <span class="d-flex align-items-center gap-2">
                                        <span style="white-space: nowrap;">{{__('Flash gift')}}:</span>
                                        <span class="flash-value-badge">{{$flashGift}}</span>
                                    </span>
                                </button>

                                <button type="button" id="flash-gain"
                                        class="btn btn-flash-gain btn-sm d-flex align-items-center gap-2">
                                    <i class="ri-money-dollar-circle-fill fs-16 flash-gain-icon"></i>
                                    <span class="d-flex align-items-center gap-2">
                                        <span style="white-space: nowrap;">{{__('Flash gain')}}:</span>
                                        <span class="flash-value-badge">{{$flashGain}}$</span>
                                    </span>
                                </button>
                            @endif
                            <button type="button" id="buy-action-submit"
                                    wire:loading.attr="disabled"
                                    wire:target="simulate"
                                    class="btn @if($flash) btn-warning text-white @else btn-primary @endif btn d-flex align-items-center gap-2 shadow-sm"
                                    style="font-weight: 500; padding: 0.5rem 1.25rem; transition: all 0.3s ease;">
                                <i class="ri-shopping-cart-2-line"></i>
                                <span>{{ __('Confirm the purchase of shares') }}</span>
                                <div class="spinner-border spinner-border-sm buy-action-submit-spinner"
                                     role="status" style="display: none;"></div>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
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
                $("#buy-action-submit").on("click", function () {
                    console.log('click');
                    this.disabled = true;
                    $('.buy-action-submit-spinner').show();
                    let ammount = parseFloat($('#amount_val').text()).toFixed(3);
                    let numberOfActions = parseInt($('#action').val());
                    let phone = $('#phone').val();
                    let me_or_other = $("input[name='inlineRadioOptions']:checked").val();
                    let bfs_for = $("input[name='bfs-for']:checked").val();
                    let country_code = iti.getSelectedCountryData().iso2;
                    $.ajax({
                        url: "{{ route('buy_action', app()->getLocale()) }}",
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
                            ranNum: Math.random() * 100,
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function (data) {
                            console.log('success')
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
                                title: data.title,
                                text: data.text,
                                showConfirmButton: true,
                                showCloseButton: false,
                                confirmButtonText: '{{__('Confirm action')}}',
                            }).then(() => {
                                $('.buy-action-submit-spinner').hide();
                                location.reload();
                            }).catch((error) => {
                                console.error('SweetAlert Error:', error);
                            });
                        },
                        error: function (data) {
                            console.log('error')
                            var responseData = JSON.parse(data.responseText);
                            Swal.fire({
                                icon: 'error',
                                title: "{{__('Error in action purchase transaction')}}",
                                cancelButtonText: '{{__('Cancel')}}',
                                confirmButtonText: '{{__('Confirm action 2')}}',
                                text: responseData.error[0]
                            });
                            $('.buy-action-submit-spinner').hide();
                        }
                    });
                    setTimeout(() => {
                        this.disabled = false;
                        $('.buy-action-submit-spinner').hide();
                    }, 5000);
                    console.log('fine')
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
                const d1 = new Date(Date.parse('{{ date(config('app.date_format'))}}'));
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
