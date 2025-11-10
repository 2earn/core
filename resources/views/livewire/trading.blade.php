<div class="{{getContainerType()}}">
    @section('title')
        {{ __('Trading') }}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Trading') }}
        @endslot
    @endcomponent
    <div class="row mb-1">
        <div class="col-12">
            @include('layouts.flash-messages')
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <livewire:buy-shares/>
        </div>
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar-sm">
                                <div class="avatar-title bg-info-subtle text-info rounded-circle fs-18">
                                    <i class="ri-stock-line"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-0 fw-semibold text-dark">{{__('Sale Shares')}}</h5>
                            @if(\App\Models\User::isSuperAdmin())
                                <p class="text-muted small mb-0">{{$selledActions}} / {{$totalActions}} {{__('shares sold')}}</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3"
                         @if(\App\Models\User::isSuperAdmin())
                             title="{{$selledActions." / ".$totalActions}}"
                        @endif
                    >
                        <div class="flex-shrink-0">
                            <div class="avatar-md">
                                <div class="avatar-title bg-primary-subtle text-primary rounded fs-24">
                                    <i class="ri-pie-chart-2-line"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0 text-muted">{{__('Sales Progress')}}</h6>
                                <h5 class="mb-0 text-primary fw-bold">{{$precentageOfActions}}%</h5>
                            </div>
                            <div class="progress animated-progress custom-progress progress-label" style="height: 24px;">
                                <div class="progress-bar bg-gradient bg-primary" role="progressbar"
                                     style="width: {{$precentageOfActions}}%"
                                     aria-valuenow="{{$selledActions}}" aria-valuemin="0"
                                     aria-valuemax="{{$totalActions}}">
                                    <div class="label fw-semibold">{{$precentageOfActions}}%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if(!is_null($targetDate))
                    <div class="card-footer bg-light border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-2">
                                <i class="ri-calendar-event-line text-success fs-18"></i>
                                <span class="text-muted fw-medium">{{__('Shares exchange estimated date')}}</span>
                            </div>
                            <span class="badge bg-success-subtle text-success fs-13 px-3 py-2">
                                <i class="ri-time-line me-1"></i>{{$targetDate}}
                            </span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar-sm">
                                <div class="avatar-title bg-success-subtle text-success rounded-circle fs-18">
                                    <i class="ri-line-chart-line"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-0 fw-semibold text-dark">{{__('Share Price Evolution')}}</h5>
                            <p class="text-muted small mb-0">{{__('Track your investment performance')}}</p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div id="chart1" wire:ignore style="min-height: 350px;">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar-sm">
                                <div class="avatar-title bg-warning-subtle text-warning rounded-circle fs-18">
                                    <i class="ri-table-line"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-0 fw-semibold text-dark">{{__('Table of my shares purchases')}}</h5>
                            <p class="text-muted small mb-0">{{__('Complete history of your transactions')}}</p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="shares-solde" wire:ignore wire:key="{{uniqid()}}"
                               class="table table-striped table-bordered table-hover align-middle nowrap mb-0"
                               style="width:100%">
                            <thead class="table-light">
                            <tr>
                                <th class="fw-semibold">{{__('Details')}}</th>
                                <th class="fw-semibold">{{__('id')}}</th>
                                <th class="fw-semibold">{{__('Date purchase')}}</th>
                                <th class="fw-semibold">{{__('Number of shares')}}</th>
                                <th class="fw-semibold">{{__('Total shares')}}</th>
                                <th class="fw-semibold">{{__('Total price')}}</th>
                                <th class="fw-semibold">{{__('Present value')}}</th>
                                <th class="fw-semibold">{{__('Current_earnings')}}</th>
                                <th class="fw-semibold">{{ __('Complementary information') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <livewire:estimated-sale-shares/>
        </div>
    </div>
    <script type="module">
        document.addEventListener("DOMContentLoaded", function () {
            var chart1Origin = document.querySelector('#chart1');
            if (chart1Origin) {
                var options1 = {
                    colors: ['#008ffb', '#00e396', '#d4526e'],
                    chart: {height: 350, type: 'area',},
                    dataLabels: {enabled: false},
                    series: [],
                    title: {text: '{{__('Share Price Evolution')}}',},
                    noData: {text: '{{__('Loading')}}...'},
                    xaxis: {type: 'numeric',},
                    stroke: {curve: 'straight',},
                    annotations: {
                        points: [{
                            x: {{getSelledActions(true) * 1.05/2}},
                            y: {{getHalfActionValue()*1.01}},
                            marker: {
                                size: 0,
                                fillColor: '#fff',
                                strokeColor: 'transparent',
                                radius: 0,
                                cssClass: 'apexcharts-custom-class'
                            },
                            label: {
                                borderColor: '#ffffff',
                                offsetY: 0,
                                style: {
                                    color: '#fff',
                                    background: '#00e396',
                                    fontSize: '15px',
                                },
                                text: "{{__('x_times')}}",
                            }
                        }]
                    }
                }
                var chart1 = new ApexCharts(document.querySelector("#chart1"), options1);
                chart1.render();
                var token = "{{ generateUserToken() }}";

                var url1 = '{{ route('api_share_evolution', ['locale' => app()->getLocale()]) }}';
                var url2 = '{{ route('api_action_values', ['locale' => app()->getLocale()]) }}';
                var url3 = '{{ route('api_share_evolution_user', ['locale' => app()->getLocale()]) }}';

                var request1 = $.ajax({
                    url: url1,
                    method: 'GET',
                    headers: {'Authorization': 'Bearer ' + token},
                    dataType: 'json'
                });

                var request2 = $.ajax({
                    url: url2,
                    method: 'GET',
                    headers: {'Authorization': 'Bearer ' + token},
                    dataType: 'json'
                });

                var request3 = $.ajax({
                    url: url3,
                    method: 'GET',
                    headers: {'Authorization': 'Bearer ' + token},
                    dataType: 'json'
                });

                $.when(request1, request2, request3).then(function (response1, response2, response3) {
                    var series1 = {name: '{{ __("Sales") }}', type: 'area', data: response1[0]};
                    var series2 = {name: '{{ __("Function") }}', type: 'line', data: response2[0]};
                    var series3 = {name: '{{ __("My Shares") }}', type: 'area', data: response3[0]};
                    chart1.updateSeries([series1, series2, series3]);
                });
            }
            $('#shares-solde').DataTable({
                responsive: true,
                retrieve: true,
                "colReorder": false,
                "orderCellsTop": true,
                "fixedHeader": true,
                "order": [[1, 'desc']],
                "processing": true,
                "serverSide": false,
                "aLengthMenu": [[10, 30, 50], [10, 30, 50]],
                search: {return: true},
                autoWidth: false,
                bAutoWidth: false,
                "ajax": {
                    url: "{{route('api_shares_solde',['locale'=> app()->getLocale()])}}",
                    type: "GET",
                    headers: {'Authorization': 'Bearer ' + "{{generateUserToken()}}"},
                    error: function (xhr, error, thrown) {
                        loadDatatableModalError('shares-solde')
                    }                },
                "columns": [
                    datatableControlBtn,
                    {data: 'id'},
                    {data: 'formatted_created_at'},
                    {data: 'value_format'},
                    {data: 'total_shares'},
                    {data: 'total_price'},
                    {data: 'present_value'},
                    {data: 'current_earnings'},
                    {data: 'complementary_information'},
                ],
                "language": {"url": urlLang}
            });
        });
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
