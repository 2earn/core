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
    <section id="Soldes" class="mb-3">
        <div class="row g-3" title="{{__('Soldes calculated at')}} : {{Carbon\Carbon::now()->toDateTimeString()}}">
            <!-- Cash balance -->
            <div class="col-md-4 col-lg-4 solde-cash">
                <div class="card card-animate h-100 shadow-sm hover-scale">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="d-flex align-items-center">
                                <div
                                    class="me-3 avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center">
                                    <lord-icon src="{{ URL::asset('build/icons/nlmjynuq.json') }}" trigger="loop"
                                               style="width:40px;height:40px"></lord-icon>
                                </div>
                                <div class="overflow-hidden">
                                    <h6 class="text-uppercase text-muted mb-1 small">{{ __('Cash balance') }}</h6>
                                    <a href="{{route('user_balance_cb' , app()->getLocale() )}}"
                                       class="text-decoration-none small text-muted">{{ __('see_details') }}</a>
                                </div>
                            </div>
                            <div>
                                @php
                                    $cb_asd = floatval($cashBalance) - floatval($arraySoldeD[0]);
                                    $cb_class = $cb_asd >= 0 ? 'success' : 'danger';
                                    $cb_prefix = $cb_asd > 0 ? '+' : '';
                                    $cb_icon = $cb_asd > 0 ? 'ri-arrow-right-up-line' : 'ri-arrow-right-down-line';
                                @endphp
                                <span class="badge bg-{{ $cb_class }} align-middle">
                                    {{ $cb_prefix }}{{ formatSolde($cb_asd) }}
                                    <i class="{{ $cb_icon }} align-middle ms-1"></i>
                                </span>
                            </div>
                        </div>

                        <div class="mt-auto d-flex align-items-end justify-content-between">
                            <div>
                                <h3 class="mb-1 fs-20 fw-bold ff-secondary"
                                    aria-label="{{ __('Cash balance') }}: {{ formatSolde($cashBalance) }}">
                                    @if(app()->getLocale()!="ar")
                                        <span class="me-1">{{config('app.currency')}}</span>
                                        <span class="counter-value"
                                              data-target="{{intval($cashBalance)}}">{{formatSolde($cashBalance,0)}}</span>
                                        <small
                                            class="text-muted fs-13">{{ getDecimals($cashBalance) ? $decimalSeperator . getDecimals($cashBalance) : '' }}</small>
                                    @else
                                        <small
                                            class="text-muted fs-13">{{ getDecimals($cashBalance) ? getDecimals($cashBalance) . $decimalSeperator : '' }}</small>
                                        <span class="counter-value">{{intval($cashBalance)}}</span>
                                        <span class="ms-1">{{config('app.currency')}}</span>
                                    @endif
                                </h3>
                            </div>
                            <div class="text-end text-muted small">{{ __('updated') }}
                                <br><small>{{ Carbon\Carbon::now()->format('H:i') }}</small></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-lg-4 solde-bfs">
                <div class="card card-animate h-100 shadow-sm hover-scale">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="d-flex align-items-center">
                                <div
                                    class="me-3 avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center">
                                    <lord-icon
                                        src="{{ URL::asset('build/icons/146-basket-trolley-shopping-card-gradient-edited.json') }}"
                                        trigger="loop" colors="primary:#464fed,secondary:#bc34b6"
                                        style="width:40px;height:40px"></lord-icon>
                                </div>
                                <div class="overflow-hidden">
                                    <h6 class="text-uppercase text-muted mb-1 small">{{ __('Balance for Shopping') }}</h6>
                                    <a href="{{route('user_balance_bfs' , app()->getLocale() )}}"
                                       class="text-decoration-none small text-muted">{{ __('see_details') }}</a>
                                </div>
                            </div>
                            <div>
                                @php
                                    $bfs_asd = $balanceForSopping - floatval($arraySoldeD[1]);
                                    $bfs_class = $bfs_asd >= 0 ? 'success' : 'danger';
                                    $bfs_prefix = $bfs_asd > 0 ? '+' : '';
                                    $bfs_icon = $bfs_asd > 0 ? 'ri-arrow-right-up-line' : 'ri-arrow-right-down-line';
                                @endphp
                                <span class="badge bg-{{ $bfs_class }} align-middle">
                                    {{ $bfs_prefix }}{{ formatSolde($bfs_asd) }}
                                    <i class="{{ $bfs_icon }} align-middle ms-1"></i>
                                </span>
                            </div>
                        </div>

                        <div class="mt-auto d-flex align-items-end justify-content-between">
                            <div>
                                <h3 class="mb-1 fs-20 fw-bold ff-secondary"
                                    aria-label="{{ __('Balance for Shopping') }}: {{ formatSolde($balanceForSopping) }}">
                                    @if(app()->getLocale()!="ar")
                                        <span class="me-1">{{config('app.currency')}}</span>
                                        <span class="counter-value"
                                              data-target="{{intval($balanceForSopping)}}">{{formatSolde($balanceForSopping,0)}}</span>
                                        <small
                                            class="text-muted fs-13">{{ getDecimals($balanceForSopping) ? $decimalSeperator . getDecimals($balanceForSopping) : '' }}</small>
                                    @else
                                        <small
                                            class="text-muted fs-13">{{ getDecimals($balanceForSopping) ? getDecimals($balanceForSopping) . $decimalSeperator : '' }}</small>
                                        <span class="counter-value">{{formatSolde($balanceForSopping,0)}}</span>
                                        <span class="ms-1">{{config('app.currency')}}</span>
                                    @endif
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-lg-4 solde-discount">
                <div class="card card-animate h-100 shadow-sm hover-scale">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="d-flex align-items-center">
                                <div
                                    class="me-3 avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center">
                                    <lord-icon src="{{ URL::asset('build/icons/qrbokoyz.json') }}" trigger="loop"
                                               colors="primary:#464fed,secondary:#bc34b6"
                                               style="width:40px;height:40px"></lord-icon>
                                </div>
                                <div class="overflow-hidden">
                                    <h6 class="text-uppercase text-muted mb-1 small">{{ __('Discounts Balance') }}</h6>
                                    <a href="{{route('user_balance_db' , app()->getLocale() )}}"
                                       class="text-decoration-none small text-muted">{{ __('see_details') }}</a>
                                </div>
                            </div>
                            <div>
                                @php
                                    $db_asd = $discountBalance - floatval($arraySoldeD[2]);
                                    $db_class = $db_asd >= 0 ? 'success' : 'danger';
                                    $db_prefix = $db_asd > 0 ? '+' : '';
                                    $db_icon = $db_asd > 0 ? 'ri-arrow-right-up-line' : 'ri-arrow-right-down-line';
                                @endphp
                                <span class="badge bg-{{ $db_class }} align-middle">
                                    {{ $db_prefix }}{{ formatSolde($db_asd) }}
                                    <i class="{{ $db_icon }} align-middle ms-1"></i>
                                </span>
                            </div>
                        </div>

                        <div class="mt-auto d-flex align-items-end justify-content-between">
                            <div>
                                <h3 class="mb-1 fs-20 fw-bold ff-secondary"
                                    aria-label="{{ __('Discounts Balance') }}: {{ formatSolde($discountBalance) }}">
                                    @if(app()->getLocale()!="ar")
                                        <span class="me-1">{{config('app.currency')}}</span>
                                        <span class="counter-value"
                                              data-target="{{intval($discountBalance)}}">{{intval($discountBalance)}}</span>
                                        <small
                                            class="text-muted fs-13">{{ getDecimals($discountBalance) ? $decimalSeperator . getDecimals($discountBalance) : '' }}</small>
                                    @else
                                        <small
                                            class="text-muted fs-13">{{ getDecimals($discountBalance) ? getDecimals($discountBalance) . $decimalSeperator : '' }}</small>
                                        <span class="counter-value">{{formatSolde($discountBalance,0)}}</span>
                                        <span class="ms-1">{{config('app.currency')}}</span>
                                    @endif
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 solde-sms">
                <div class="card card-animate h-100 shadow-sm hover-scale">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="d-flex align-items-center">
                                <div
                                    class="me-3 avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center">
                                    <lord-icon
                                        src="{{ URL::asset('build/icons/981-consultation-gradient-edited.json') }}"
                                        trigger="loop" colors="primary:#464fed,secondary:#bc34b6"
                                        style="width:40px;height:40px"></lord-icon>
                                </div>
                                <div class="overflow-hidden">
                                    <h6 class="text-uppercase text-muted mb-1 small">{{ __('SMS Solde') }}</h6>
                                    <a href="{{route('user_balance_sms' , app()->getLocale() )}}"
                                       class="text-decoration-none small text-muted">{{ __('see_details') }}</a>
                                </div>
                            </div>
                            <div class="text-success small">{{ $SMSBalance }}</div>
                        </div>
                        <div class="mt-auto d-flex align-items-end justify-content-between">
                            <div>
                                <h3 class="mb-1 fs-20 fw-bold ff-secondary"
                                    aria-label="{{ __('SMS Solde') }}: {{ $SMSBalance }}">
                                    <span class="counter-value" data-target="{{$SMSBalance}}">{{$SMSBalance}}</span>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 solde-tree">
                <div class="card card-animate h-100 shadow-sm hover-scale">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="d-flex align-items-center">
                                <div
                                    class="me-3 avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center">
                                    <lord-icon src="{{ URL::asset('build/icons/1855-palmtree.json') }}" trigger="loop"
                                               colors="primary:#464fed,secondary:#bc34b6"
                                               style="width:40px;height:40px"></lord-icon>
                                </div>
                                <div class="overflow-hidden">
                                    <h6 class="text-uppercase text-muted mb-1 small">{{ __('Tree Solde') }}</h6>
                                    <a href="{{route('user_balance_tree' , app()->getLocale() )}}"
                                       class="text-decoration-none small text-muted">{{ __('see_details') }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="mt-auto d-flex align-items-end justify-content-between">
                            <div>
                                <h3 class="mb-1 fs-20 fw-bold ff-secondary"
                                    aria-label="{{ __('Tree Solde') }}: {{ $treeBalance }}%">
                                    <span>{{ $treeBalance }}</span> %
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 solde-chance">
                <div class="card card-animate h-100 shadow-sm hover-scale">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="d-flex align-items-center">
                                <div
                                    class="me-3 avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center">
                                    <lord-icon src="{{ URL::asset('build/icons/1471-dice-cube.json') }}" trigger="loop"
                                               colors="primary:#464fed,secondary:#bc34b6"
                                               style="width:40px;height:40px"></lord-icon>
                                </div>
                                <div class="overflow-hidden">
                                    <h6 class="text-uppercase text-muted mb-1 small">{{ __('Chance Sold') }}</h6>
                                    <a href="{{route('user_balance_chance' , app()->getLocale() )}}"
                                       class="text-decoration-none small text-muted">{{ __('see_details') }}</a>
                                </div>
                            </div>
                            <div class="text-success small">{{$chanceBalance}}</div>
                        </div>
                        <div class="mt-auto d-flex align-items-end justify-content-between">
                            <div>
                                <h3 class="mb-1 fs-20 fw-bold ff-secondary"
                                    aria-label="{{ __('Chance Sold') }}: {{$chanceBalance}}">
                                    <span class="counter-value"
                                          data-target="{{$chanceBalance}}">{{$chanceBalance}}</span>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 solde-action">
                <div class="card card-animate h-100 shadow-sm hover-scale">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="d-flex align-items-center">
                                <div
                                    class="me-3 avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center">
                                    <lord-icon src="{{ URL::asset('build/icons/wired-gradient-751-share.json') }}"
                                               trigger="loop"
                                               colors="primary:#464fed,secondary:#bc34b6"
                                               style="width:40px;height:40px"></lord-icon>
                                </div>
                                <div class="overflow-hidden">
                                    <h6 class="text-uppercase text-muted mb-1 small">{{ __('Actions (Shares)') }}</h6>
                                    <a href="{{route('shares_solde' , app()->getLocale() )}}"
                                       class="text-decoration-none small text-muted">{{ __('see_details') }}</a>
                                </div>
                            </div>
                            <div class="text-success small">{{$actualActionValue['int']}}
                                .{{$actualActionValue['2Fraction']}}<small
                                    class="action_fraction">{{$actualActionValue['3_2Fraction']}}</small></div>
                        </div>
                        <div class="mt-auto">
                            <div class="row">
                                <div class="col-7">
                                    <h3 class="mb-1 fs-16 fw-bold ff-secondary col-6"
                                        aria-label="{{ __('Actions (Shares)') }}: {{ formatSolde($userSelledAction,0) }}">
                                    <span class="counter-value"
                                          data-target="{{$userSelledAction}}">{{formatSolde($userSelledAction,0)}}</span>
                                        <small class="text-muted fs-10">({{$actionsValues}}) <span
                                                class="text-muted">{{config('app.currency')}}</span></small>
                                    </h3>
                                </div>
                                <div class="col-5">
                                               <span class="badge bg-light text-success ms-2"> <i
                                                       class="ri-arrow-up-line align-middle"></i> {{$userActualActionsProfit}} <span
                                                       class="text-muted">{{config('app.currency')}}</span></span>
                                </div>
                                <div class="col-12">
                                    <a href="{{route('business_hub_trading',app()->getLocale())}}"
                                       class="btn btn-sm float-end @if($flash) btn-flash @else btn-soft-secondary @endif">{{ __('Buy Shares') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="bussiness" class="mb-1">
        <livewire:bussiness-sectors-home/>
    </section>
    <section id="communication" class="p-1">
        <livewire:communication-board/>
    </section>
    <section id="stats">
        <div class="card col-12">
            <div class="card-header border-info">
                <div class="d-flex align-items-center">
                    <h5 class="card-title mb-0 flex-grow-1 text-info">{{ __('we_are_present_in') }}</h5>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12" style="padding-right: 0;padding-left: 0;">
                        <div class="card border-0 " style="height: 480px;box-shadow: none">
                            <div class="card-body">
                                <div id="any4"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card col-12">
            <div class="card-header border-info">
                <div class="d-flex align-items-center">
                    <h5 class="card-title mb-0 flex-grow-1 text-info">{{ __('Country ponderation') }}</h5>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="card border-0 " style="height: 480px;box-shadow: none">
                        <div class="card-body">
                            <div id="any5"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
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

            var series;
            $(document).ready(function () {
                anychart.onDocumentReady(function () {
                    var $any4 = $('#any4');
                    if ($any4.length > 0 && $any4.is(':empty')) {
                        anychart.data.loadJsonFile(
                            "{{route('api_stat_countries',app()->getLocale())}}",
                            function (data) {
                                var map = anychart.map();
                                map.geoData('anychart.maps.world');
                                map.padding(0);
                                var dataSet = anychart.data.set(data);
                                var densityData = dataSet.mapAs({id: 'apha2', value: 'COUNT_USERS'});
                                series = map.choropleth(densityData);
                                series.labels(false);
                                series.hovered().fill('#f48fb1').stroke(anychart.color.darken('#f48fb1'));
                                series.tooltip(false);
                                var scale = anychart.scales.ordinalColor([
                                    {less: 2},
                                    {from: 2, to: 5},
                                    {from: 5, to: 10},
                                    {from: 10, to: 15},
                                    {from: 15, to: 30},
                                    {from: 30, to: 50},
                                    {from: 50, to: 100},
                                    {from: 100, to: 500},
                                    {greater: 500}
                                ]);
                                scale.colors(['#81d4fa', '#4fc3f7', '#29b6f6', '#039be5', '#0288d1', '#0277bd', '#01579b', '#014377', '#000000']);
                                series.colorScale(scale);
                                var zoomController = anychart.ui.zoom();
                                zoomController.render(map);
                                map.container('any4');
                                map.draw();
                                var mapping = dataSet.mapAs({
                                    x: "name",
                                    value: "COUNT_USERS",
                                    category: "continant"
                                });
                                var colors = anychart.scales.ordinalColor().colors(['#26959f', '#f18126', '#3b8ad8', '#60727b', '#e24b26']);
                                var chart = anychart.tagCloud();
                                chart.data(mapping).colorScale(colors).angles([-90, 0, 90,]);
                                chart.tooltip(false);
                                var colorRange = chart.colorRange();
                                colorRange.enabled(true).colorLineSize(15);
                                var normalFillFunction = chart.normal().fill();
                                var hoveredFillFunction = chart.hovered().fill();
                                chart.listen('pointsHover', function (e) {
                                    if (e.actualTarget === colorRange) {
                                        if (e.points.length) {
                                            chart.normal({
                                                fill: 'black 0.1'
                                            });
                                            chart.hovered({
                                                fill: chart.colorScale().valueToColor(e.point.get('category'))
                                            });
                                        } else {
                                            chart.normal({fill: normalFillFunction});
                                            chart.hovered({fill: hoveredFillFunction});
                                        }
                                    }
                                });
                                chart.container('any5');
                                chart.draw();
                            }
                        );
                    }
                });
            });

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
            /* Small local styles for Soldes UI tweaks */
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
