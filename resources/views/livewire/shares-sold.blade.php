<div>
    @section('title')
        {{ __('Share_sold') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Shares Sold') }}
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-xxl-9">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-0">{{__('My Portfolio Statistics')}}</h5>
                        </div>

                    </div>
                </div>
                <div class="card-body">
                    <div class="accordion" id="default-accordion-example">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                        aria-expanded="true" aria-controls="collapseOne">
                                    {{__('My Cash Balance')}}
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse"
                                 aria-labelledby="headingOne" data-bs-parent="#default-accordion-example">
                                <div class="accordion-body">
                                    <div id="chart">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                        aria-expanded="false" aria-controls="collapseTwo">
                                    {{__('Share Price Evolution')}}
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse"
                                 aria-labelledby="headingTwo" data-bs-parent="#default-accordion-example">
                                <div class="accordion-body">
                                    <div id="chart1">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseThree"
                                        aria-expanded="false" aria-controls="collapseThree">
                                    {{__(' Share Price')}}
                                </button>

                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse"
                                 aria-labelledby="headingThree" data-bs-parent="#default-accordion-example">
                                <div class="accordion-body">
                                    <div>
                                        <button id="date" type="button" class="btn btn-soft-secondary btn-sm">
                                            {{__('By date')}}
                                        </button>
                                        <button id="week" type="button" class="btn btn-soft-secondary btn-sm">
                                            {{__('By week')}}
                                        </button>
                                        <button id="month" type="button" class="btn btn-soft-secondary btn-sm">
                                            {{__('By month')}}
                                        </button>
                                        <button id="day" type="button" class="btn btn-soft-primary btn-sm">
                                            {{__('By day')}}
                                        </button>
                                    </div>
                                    <div id="chart2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center mb-3">
                <div class="flex-grow-1">
                    <h5 class="mb-0">{{__('Watchlist')}}</h5>
                </div>
                <div class="flexshrink-0">
                    <button class="btn btn-success btn-sm"><i class="ri-star-line align-bottom"></i>
                        {{__('Add Watchlist')}}
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-4 col-md-6">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="float-end">
                                <div class="dropdown">
                                    <a class="text-reset" href="#" data-bs-toggle="dropdown"
                                       aria-haspopup="true" aria-expanded="false">
                                                <span class="text-muted fs-18"><i
                                                        class="mdi mdi-dots-horizontal"></i></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#">{{__('View Details')}}</a>
                                        <a class="dropdown-item" href="#">{{__('Remove Watchlist')}}</a>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <img src="{{ Vite::asset('resources/images/svg/crypto-icons/ltc.svg') }}"
                                     class="bg-light rounded-circle p-1 avatar-xs img-fluid" alt="">
                                <h6 class="ms-2 mb-0 fs-14">{{__('Sold Shares')}}</h6>
                            </div>
                            <div class="row align-items-end g-0">
                                <div class="col-6">
                                    <h5 class="mb-1 mt-4">{{number_format(getSelledActions(),0)}}</h5>
                                    <p class="text-danger fw-medium mb-0"><span
                                            class="text-muted ms-2 fs-12"></span></p>
                                </div>
                                <div class="col-6">
                                    <div class="apex-charts crypto-widget"
                                         data-colors='["--vz-danger", "--vz-transparent"]'
                                         id="litecoin_sparkline_charts" dir="ltr"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="float-end">
                                <div class="dropdown">
                                    <a class="text-reset" href="#" data-bs-toggle="dropdown"
                                       aria-haspopup="true" aria-expanded="false">
                                                <span class="text-muted fs-18"><i
                                                        class="mdi mdi-dots-horizontal"></i></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#">{{__('View Details')}}</a>
                                        <a class="dropdown-item" href="#">{{__('Remove Watchlist')}}</a>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <img src="{{ Vite::asset('resources/images/svg/crypto-icons/eth.svg') }}"
                                     class="bg-light rounded-circle p-1 avatar-xs img-fluid" alt="">
                                <h6 class="ms-2 mb-0 fs-14">{{__('Gifted Shares')}}</h6>
                            </div>
                            <div class="row align-items-end g-0">
                                <div class="col-6">
                                    <h5 class="mb-1 mt-4">{{number_format(getGiftedShares(),0)}}</h5>
                                    <p class="text-danger fw-medium mb-0"><span
                                            class="text-muted ms-2 fs-12"></span></p>
                                </div>
                                <div class="col-6">
                                    <div class="apex-charts crypto-widget"
                                         data-colors='["--vz-danger", "--vz-transparent"]'
                                         id="eathereum_sparkline_charts" dir="ltr"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="float-end">
                                <div class="dropdown">
                                    <a class="text-reset" href="#" data-bs-toggle="dropdown"
                                       aria-haspopup="true" aria-expanded="false">
                                                <span class="text-muted fs-18"><i
                                                        class="mdi mdi-dots-horizontal"></i></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#">{{__('View Details')}}</a>
                                        <a class="dropdown-item" href="#">{{__('Remove Watchlist')}}</a>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <img src="{{ Vite::asset('resources/images/svg/crypto-icons/xmr.svg') }}"
                                     class="bg-light rounded-circle p-1 avatar-xs img-fluid" alt="">
                                <h6 class="ms-2 mb-0 fs-14">{{__('Gifted/Sold Shares')}}</h6>
                            </div>
                            <div class="row align-items-end g-0">
                                <div class="col-6">
                                    <h5 class="mb-1 mt-4">{{number_format(getGiftedShares()/getSelledActions()*100,2)}}
                                        %</h5>
                                    <p class="text-danger fw-medium mb-0"><span
                                            class="text-muted ms-2 fs-12"></span></p>
                                </div>
                                <div class="col-6">
                                    <div class="apex-charts crypto-widget"
                                         data-colors='["--vz-danger", "--vz-transparent"]'
                                         id="binance_sparkline_charts" dir="ltr"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="float-end">
                                <div class="dropdown">
                                    <a class="text-reset" href="#" data-bs-toggle="dropdown"
                                       aria-haspopup="true" aria-expanded="false">
                                                <span class="text-muted fs-18"><i
                                                        class="mdi mdi-dots-horizontal"></i></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#">{{__('View Details')}}</a>
                                        <a class="dropdown-item" href="#">{{__('Remove Watchlist')}}</a>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <img src="{{ Vite::asset('resources/images/svg/crypto-icons/btc.svg') }}"
                                     class="bg-light rounded-circle p-1 avatar-xs img-fluid" alt="">
                                <h6 class="ms-2 mb-0 fs-14">{{__('Shares actual price')}}</h6>
                            </div>
                            <div class="row align-items-end g-0">
                                <div class="col-6">
                                    <h5 class="mb-1 mt-4"><?php $val = number_format(actualActionValue(getSelledActions()), 2) ?>
                                        @if(1>0)
                                            {{$val}}$
                                        @endif</h5>
                                    <p class="text-success fw-medium mb-0"><span
                                            class="text-muted ms-2 fs-12"></span></p>
                                </div>
                                <div class="col-6">
                                    <div class="apex-charts crypto-widget"
                                         data-colors='["--vz-success" , "--vz-transparent"]'
                                         id="bitcoin_sparkline_charts" dir="ltr"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="float-end">
                                <div class="dropdown">
                                    <a class="text-reset" href="#" data-bs-toggle="dropdown"
                                       aria-haspopup="true" aria-expanded="false">
                                                <span class="text-muted fs-18"><i
                                                        class="mdi mdi-dots-horizontal"></i></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#">{{__('View Details')}}</a>
                                        <a class="dropdown-item" href="#">{{__('Remove Watchlist')}}</a>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <img src="{{ Vite::asset('resources/images/svg/crypto-icons/xmr.svg') }}"
                                     class="bg-light rounded-circle p-1 avatar-xs img-fluid" alt="">
                                <h6 class="ms-2 mb-0 fs-14">{{__('Revenue')}}</h6>
                            </div>
                            <div class="row align-items-end g-0">
                                <div class="col-6">
                                    <h5 class="mb-1 mt-4">${{number_format(getRevenuShares(),2)}}</h5>
                                    <p class="text-danger fw-medium mb-0"><span
                                            class="text-muted ms-2 fs-12"></span></p>
                                </div>
                                <div class="col-6">
                                    <div class="apex-charts crypto-widget"
                                         data-colors='["--vz-danger", "--vz-transparent"]'
                                         id="binance_sparkline_charts" dir="ltr"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="float-end">
                                <div class="dropdown">
                                    <a class="text-reset" href="#" data-bs-toggle="dropdown"
                                       aria-haspopup="true" aria-expanded="false">
                                                <span class="text-muted fs-18"><i
                                                        class="mdi mdi-dots-horizontal"></i></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#">{{__('View Details')}}</a>
                                        <a class="dropdown-item" href="#">{{__('Remove Watchlist')}}</a>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <img src="{{ Vite::asset('resources/images/svg/crypto-icons/xmr.svg') }}"
                                     class="bg-light rounded-circle p-1 avatar-xs img-fluid" alt="">
                                <h6 class="ms-2 mb-0 fs-14">{{__('Transfer Made')}}</h6>
                            </div>
                            <div class="row align-items-end g-0">
                                <div class="col-6">
                                    <h5 class="mb-1 mt-4" id="realrev">
                                        ${{number_format(getRevenuSharesReal(),2)}}</h5>
                                    <p class="text-danger fw-medium mb-0"><span
                                            class="text-muted ms-2 fs-12"></span></p>
                                </div>
                                <div class="col-6">
                                    <div class="apex-charts crypto-widget"
                                         data-colors='["--vz-danger", "--vz-transparent"]'
                                         id="binance_sparkline_charts" dir="ltr"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3">
            <div class="card">
                <div class="card-body bg-soft-warning">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <h5 class="fs-14 mb-3">{{__('My Portfolio')}}</h5>
                            <h2>$<?php echo $solde->soldeCB / 1 ?><small class="text-muted fs-14"></small></h2>
                            <p class="text-muted mb-0"><small class="badge badge-soft-success"><i
                                        class="ri-arrow-right-up-line fs-13 align-bottom"></i></small></p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="mdi mdi-wallet-outline text-primary h1"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <h5 class="fs-14 mb-3">{{__('Today\'s Cash Transfert')}}</h5>
                            <h2>$<?php echo $vente_jour / 1 ?><small class="text-muted fs-14"></small></h2>
                            <p class="text-muted mb-0"><small class="badge badge-soft-success"><i
                                        class="ri-arrow-right-up-line fs-13 align-bottom"></i></small></p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ri-hand-coin-line text-primary h1"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <h5 class="fs-14 mb-3">{{__('Overall Cash Transfert')}}</h5>
                            <h2>$<?php echo $vente_total / 1 ?><small class="text-muted fs-14"></small></h2>
                            <p class="text-muted mb-0"><small class="badge badge-soft-success"><i
                                        class="ri-arrow-right-up-line fs-13 align-bottom"></i></small></p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ri-line-chart-line text-primary h1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script id="rendered-js" type="module">
        var options = {
            chart: {height: 350, type: 'area',},
            dataLabels: {enabled: false},
            series: [],
            title: {text: 'Cash Balance',},
            noData: {text: 'Loading...'},
            xaxis: {type: 'datetime',}
        }
        var options1 = {
            chart: {height: 350, type: 'area',},
            dataLabels: {enabled: false},
            series: [],
            title: {text: 'Share Price Evolution',},
            noData: {text: 'Loading...'},
            xaxis: {type: 'numeric',}

        }
        var options2 = {
            chart: {height: 350, type: 'line',},
            plotOptions: {
                bar: {
                    borderRadius: 10,
                    dataLabels: {
                        position: 'top', enabled: true, formatter: function (val) {
                            return val;
                        }
                    },
                }
            },
            stroke: {width: 2, curve: 'smooth'},
            series: [],
            title: {text: 'Share Price Evolution',},
            noData: {text: 'Loading...'},
            xaxis: {type: 'date',}
        }

        $(document).on('turbolinks:load', function () {
            var chartOrigin = document.querySelector('#chart');
            var chart1Origin = document.querySelector('#chart1');
            var chart2Origin = document.querySelector('#chart2');
            if (chartOrigin) {
                var chart = new ApexCharts(document.querySelector("#chart"), options);
                chart.render();
            }
            if (chart1Origin) {
                var chart1 = new ApexCharts(document.querySelector("#chart1"), options1);
                chart1.render();
            }
            if (chart2Origin) {
                var chart2 = new ApexCharts(document.querySelector("#chart2"), options2);
                chart2.render();
            }

            if (chartOrigin) {
                var url = '{{route('API_usercash',['locale'=> app()->getLocale()])}}';
                $.getJSON(url, function (response) {
                    chart.updateSeries([{name: 'Balance', data: response}])
                });
            }
            if (chart2Origin && chart1Origin) {
                var url3 = '{{route('API_shareevolutiondate',['locale'=> app()->getLocale()])}}';
                $.getJSON(url3, function (response) {
                    var series1 = {name: 'Sales-bar', type: 'bar', data: response};
                    var series2 = {name: 'sales-line', type: 'line', data: response};
                    chart2.updateSeries([series1, series2]);
                });
            }
            $(document).on("click", "#date", function () {
                if (chart2Origin) {
                    var url3 = '{{route('API_shareevolutiondate',['locale'=> app()->getLocale()])}}';
                    $.getJSON(url3, function (response) {
                        var series1 = {name: 'Sales-bar', type: 'bar', data: response};
                        var series2 = {name: 'sales-line', type: 'line', data: response};

                        chart2.updateSeries([series1, series2]);
                    });
                }
            });
            $(document).on("click", "#week", function () {
                if (chart2Origin && chart1Origin) {
                    var url3 = '{{route('API_shareevolutionweek',['locale'=> app()->getLocale()])}}';
                    $.getJSON(url3, function (response) {
                        var series1 = {name: 'Sales-bar', type: 'bar', data: response};
                        var series2 = {name: 'sales-line', type: 'line', data: response};
                        chart2.updateSeries([series1, series2]);
                    });
                }
            });
            $(document).on("click", "#month", function () {
                if (chart2Origin && chart1Origin) {
                    var url3 = '{{route('API_shareevolutionmonth',['locale'=> app()->getLocale()])}}';
                    $.getJSON(url3, function (response) {
                        var series1 = {name: 'Sales-bar', type: 'bar', data: response};
                        var series2 = {name: 'sales-line', type: 'line', data: response};
                        chart2.updateSeries([series1, series2]);
                    });
                }
            });
            $(document).on("click", "#day", function () {
                if (chart2Origin && chart1Origin) {
                    var url3 = '{{route('API_shareevolutionday',['locale'=> app()->getLocale()])}}';
                    $.getJSON(url3, function (response) {
                        var series1 = {name: 'Sales-bar', type: 'bar', data: response};
                        var series2 = {name: 'sales-line', type: 'line', data: response};
                        chart2.updateSeries([series1, series2]);
                    });
                }
            });
            if (chart2Origin && chart1Origin) {
                chart2.render();
                var url1 = '{{route('API_shareevolution',['locale'=> app()->getLocale()])}}';
                var url2 = '{{route('API_actionvalues',['locale'=> app()->getLocale()])}}';

                $.when(
                    $.getJSON(url1),
                    $.getJSON(url2)
                ).then(function (response1, response2) {
                    var series1 = {name: 'Sales', type: 'area', data: response1[0]};

                    var series2 = {name: 'Function', type: 'line', data: response2[0]};
                    chart1.updateSeries([series1, series2]);
                });
            }
        });
    </script>
</div>
