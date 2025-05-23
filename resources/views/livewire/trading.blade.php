<div class="container-fluid">
    @section('title')
        {{ __('Trading') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Trading') }}
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-12">
            @include('layouts.flash-messages')
        </div>
    </div>
    <div class="row">
        <div class="col-xxl-5 col-lg-6">
            <livewire:buy-shares/>
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-0">{{__('Sale Shares')}}</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center pb-2"
                         @if(\App\Models\User::isSuperAdmin())
                             title="{{$selledActions." / ".$totalActions}}"
                        @endif
                    >
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar-xs">
                                <div class="avatar-title bg-light rounded-circle text-muted fs-16">
                                    <i class=" ri-creative-commons-nc-fill"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="progress animated-progress custom-progress progress-label">
                                <div class="progress-bar bg-primary" role="progressbar"
                                     style="width: {{$precentageOfActions}}%"
                                     aria-valuenow="{{$selledActions}}" aria-valuemin="0"
                                     aria-valuemax="{{$totalActions}}">
                                    <div class="label">{{$precentageOfActions}}%</div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                @if(!is_null($targetDate))
                    <div class="card-footer">
                        <div class="flex-grow-1">
                            <span class="btn text-muted"> {{__('Shares exchange estimated date')}}</span>
                            <span class="btn btn-soft-success float-end">{{$targetDate}}</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-xxl-7 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-0">{{__('Share Price Evolution')}}</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="chart1" wire:ignore>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-0">{{__('Sale Shares tables')}}</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table id="shares-solde" wire:ignore wire:key="{{uniqid()}}"
                           class="table table-striped table-bordered cell-border row-border table-hover mdl-data-table display nowrap"
                           style="width:100%">
                        <thead class="table-light">
                        <tr class="head2earn  tabHeader2earn">
                            <th>{{__('id')}}</th>
                            <th>{{__('date_purchase')}}</th>
                            <th>{{__('number_of_shares')}}</th>
                            <th>{{__('total_shares')}}</th>
                            <th>{{__('total_price')}}</th>
                            <th>{{__('present_value')}}</th>
                            <th>{{__('current_earnings')}}</th>
                        </tr>
                        </thead>
                        <tbody class="body2earn">
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <div class="col-xxl-12 col-lg-12">
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
                var url1 = '{{route('api_share_evolution',['locale'=> app()->getLocale()])}}';
                var url2 = '{{route('api_action_values',['locale'=> app()->getLocale()])}}';
                var url3 = '{{route('api_share_evolution_user',['locale'=> app()->getLocale()])}}';
                $.when($.getJSON(url1), $.getJSON(url2), $.getJSON(url3)
                ).then(function (response1, response2, response3) {
                    var series1 = {name: '{{__('Sales')}}', type: 'area', data: response1[0],};
                    var series2 = {name: '{{__('Function')}}', type: 'line', data: response2[0]};
                    var series3 = {name: '{{__('My Shares')}}', type: 'area', data: response3[0]};
                    chart1.updateSeries([series1, series2, series3]);
                });
            }
            $('#shares-solde').DataTable({
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
                "ajax": "{{route('api_shares_solde',['locale'=> app()->getLocale()])}}",
                "columns": [
                    {data: 'id'},
                    {data: 'formatted_created_at'},
                    {data: 'value_format'},
                    {data: 'total_shares'},
                    {data: 'total_price'},
                    {data: 'present_value'},
                    {data: 'current_earnings'},
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
