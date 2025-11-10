<div class="{{getContainerType()}}">
    @section('title')
        {{ __('Shares Sold :  Dashboard') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Shares Sold :  Dashboard') }}
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-0 fw-semibold">{{__('My Portfolio Statistics')}}</h5>
                        </div>

                    </div>
                </div>
                <div class="card-body p-4">
                    <ul class="nav nav-tabs nav-tabs-custom nav-justified mb-3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#cash-balance" role="tab">
                                <i class="ri-wallet-3-line me-2 fs-5 text-primary"></i>
                                {{__('My Cash Balance')}}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#price-evolution" role="tab">
                                <i class="ri-line-chart-line me-2 fs-5 text-success"></i>
                                {{__('Share Price Evolution')}}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#share-sold" role="tab">
                                <i class="ri-bar-chart-box-line me-2 fs-5 text-info"></i>
                                {{__('Share Price sold')}}
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content text-muted">
                        <div class="tab-pane active show" id="cash-balance" role="tabpanel">
                            <div id="chart"></div>
                        </div>
                        <div class="tab-pane" id="price-evolution" role="tabpanel">
                            <div id="chart1"></div>
                        </div>
                        <div class="tab-pane" id="share-sold" role="tabpanel">
                            <div class="btn-group mb-3" role="group" aria-label="Date filter">
                                <button id="date" type="button" class="btn btn-outline-secondary btn-sm">
                                    <i class="ri-calendar-line me-1"></i>{{__('By date')}}
                                </button>
                                <button id="week" type="button" class="btn btn-outline-secondary btn-sm">
                                    <i class="ri-calendar-2-line me-1"></i>{{__('By week')}}
                                </button>
                                <button id="month" type="button" class="btn btn-outline-secondary btn-sm">
                                    <i class="ri-calendar-check-line me-1"></i>{{__('By month')}}
                                </button>
                                <button id="day" type="button" class="btn btn-primary btn-sm">
                                    <i class="ri-calendar-event-line me-1"></i>{{__('By day')}}
                                </button>
                            </div>
                            <div id="chart2"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between mb-4 mt-4 p-3 bg-light rounded-3">
                <div class="flex-grow-1">
                    <h5 class="mb-0 fw-semibold text-dark">
                        <i class="ri-star-line me-2 text-warning"></i>{{__('Watchlist')}}
                    </h5>
                </div>
                <div class="flex-shrink-0">
                    <button class="btn btn-success btn-sm">
                        <i class="ri-add-line align-bottom me-1"></i>{{__('Add Watchlist')}}
                    </button>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-xl-4 col-md-6">
                    <div class="card card-animate shadow-sm border-0 h-100">
                        <div class="card-body p-4">
                            <div class="float-end">
                                <div class="dropdown">
                                    <a class="text-muted" href="#" data-bs-toggle="dropdown"
                                       aria-haspopup="true" aria-expanded="false">
                                        <i class="ri-more-2-fill fs-4"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#">
                                            <i class="ri-eye-line me-2"></i>{{__('View Details')}}
                                        </a>
                                        <a class="dropdown-item text-danger" href="#">
                                            <i class="ri-delete-bin-line me-2"></i>{{__('Remove Watchlist')}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-sm bg-light rounded-circle p-2">
                                    <img src="{{ Vite::asset('resources/images/svg/crypto-icons/ltc.svg') }}"
                                         class="img-fluid" alt="">
                                </div>
                                <h6 class="ms-3 mb-0 fs-15 fw-semibold">{{__('Sold Shares')}}</h6>
                            </div>
                            <div class="row align-items-end g-0 mt-4">
                                <div class="col-7">
                                    <h4 class="mb-2 fw-bold text-dark">{{number_format(getSelledActions(),0)}}</h4>
                                    <p class="text-muted mb-0 fs-13">
                                        <span class="badge bg-danger-subtle text-danger">
                                            <i class="ri-arrow-down-line align-middle"></i>
                                        </span>
                                    </p>
                                </div>
                                <div class="col-5">
                                    <div class="apex-charts crypto-widget"
                                         data-colors='["--vz-danger", "--vz-transparent"]'
                                         id="litecoin_sparkline_charts" dir="ltr"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="card card-animate shadow-sm border-0 h-100">
                        <div class="card-body p-4">
                            <div class="float-end">
                                <div class="dropdown">
                                    <a class="text-muted" href="#" data-bs-toggle="dropdown"
                                       aria-haspopup="true" aria-expanded="false">
                                        <i class="ri-more-2-fill fs-4"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#">
                                            <i class="ri-eye-line me-2"></i>{{__('View Details')}}
                                        </a>
                                        <a class="dropdown-item text-danger" href="#">
                                            <i class="ri-delete-bin-line me-2"></i>{{__('Remove Watchlist')}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-sm bg-light rounded-circle p-2">
                                    <img src="{{ Vite::asset('resources/images/svg/crypto-icons/eth.svg') }}"
                                         class="img-fluid" alt="">
                                </div>
                                <h6 class="ms-3 mb-0 fs-15 fw-semibold">{{__('Gifted Shares')}}</h6>
                            </div>
                            <div class="row align-items-end g-0 mt-4">
                                <div class="col-7">
                                    <h4 class="mb-2 fw-bold text-dark">{{number_format(getGiftedShares(),0)}}</h4>
                                    <p class="text-muted mb-0 fs-13">
                                        <span class="badge bg-danger-subtle text-danger">
                                            <i class="ri-arrow-down-line align-middle"></i>
                                        </span>
                                    </p>
                                </div>
                                <div class="col-5">
                                    <div class="apex-charts crypto-widget"
                                         data-colors='["--vz-danger", "--vz-transparent"]'
                                         id="eathereum_sparkline_charts" dir="ltr"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="card card-animate shadow-sm border-0 h-100">
                        <div class="card-body p-4">
                            <div class="float-end">
                                <div class="dropdown">
                                    <a class="text-muted" href="#" data-bs-toggle="dropdown"
                                       aria-haspopup="true" aria-expanded="false">
                                        <i class="ri-more-2-fill fs-4"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#">
                                            <i class="ri-eye-line me-2"></i>{{__('View Details')}}
                                        </a>
                                        <a class="dropdown-item text-danger" href="#">
                                            <i class="ri-delete-bin-line me-2"></i>{{__('Remove Watchlist')}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-sm bg-light rounded-circle p-2">
                                    <img src="{{ Vite::asset('resources/images/svg/crypto-icons/xmr.svg') }}"
                                         class="img-fluid" alt="">
                                </div>
                                <h6 class="ms-3 mb-0 fs-15 fw-semibold">{{__('Gifted/Sold Shares')}}</h6>
                            </div>
                            <div class="row align-items-end g-0 mt-4">
                                <div class="col-7">
                                    @if(getSelledActions()>0)
                                        <h4 class="mb-2 fw-bold text-dark">{{number_format(getGiftedShares()/getSelledActions()*100,2)}}
                                            %</h4>
                                    @endif
                                    <p class="text-muted mb-0 fs-13">
                                        <span class="badge bg-danger-subtle text-danger">
                                            <i class="ri-arrow-down-line align-middle"></i>
                                        </span>
                                    </p>
                                </div>
                                <div class="col-5">
                                    <div class="apex-charts crypto-widget"
                                         data-colors='["--vz-danger", "--vz-transparent"]'
                                         id="binance_sparkline_charts" dir="ltr"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="card card-animate shadow-sm border-0 h-100">
                        <div class="card-body p-4">
                            <div class="float-end">
                                <div class="dropdown">
                                    <a class="text-muted" href="#" data-bs-toggle="dropdown"
                                       aria-haspopup="true" aria-expanded="false">
                                        <i class="ri-more-2-fill fs-4"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#">
                                            <i class="ri-eye-line me-2"></i>{{__('View Details')}}
                                        </a>
                                        <a class="dropdown-item text-danger" href="#">
                                            <i class="ri-delete-bin-line me-2"></i>{{__('Remove Watchlist')}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-sm bg-light rounded-circle p-2">
                                    <img src="{{ Vite::asset('resources/images/svg/crypto-icons/btc.svg') }}"
                                         class="img-fluid" alt="">
                                </div>
                                <h6 class="ms-3 mb-0 fs-15 fw-semibold">{{__('Shares actual price')}}</h6>
                            </div>
                            <div class="row align-items-end g-0 mt-4">
                                <div class="col-7">
                                    <h4 class="mb-2 fw-bold text-dark">
                                        <?php $val = number_format(actualActionValue(getSelledActions(true)), 2) ?>
                                        @if(1>0)
                                            ${{$val}}
                                        @endif
                                    </h4>
                                    <p class="text-muted mb-0 fs-13">
                                        <span class="badge bg-success-subtle text-success">
                                            <i class="ri-arrow-up-line align-middle"></i>
                                        </span>
                                    </p>
                                </div>
                                <div class="col-5">
                                    <div class="apex-charts crypto-widget"
                                         data-colors='["--vz-success" , "--vz-transparent"]'
                                         id="bitcoin_sparkline_charts" dir="ltr"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="card card-animate shadow-sm border-0 h-100">
                        <div class="card-body p-4">
                            <div class="float-end">
                                <div class="dropdown">
                                    <a class="text-muted" href="#" data-bs-toggle="dropdown"
                                       aria-haspopup="true" aria-expanded="false">
                                        <i class="ri-more-2-fill fs-4"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#">
                                            <i class="ri-eye-line me-2"></i>{{__('View Details')}}
                                        </a>
                                        <a class="dropdown-item text-danger" href="#">
                                            <i class="ri-delete-bin-line me-2"></i>{{__('Remove Watchlist')}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-sm bg-light rounded-circle p-2">
                                    <img src="{{ Vite::asset('resources/images/svg/crypto-icons/xmr.svg') }}"
                                         class="img-fluid" alt="">
                                </div>
                                <h6 class="ms-3 mb-0 fs-15 fw-semibold">{{__('Revenue')}}</h6>
                            </div>
                            <div class="row align-items-end g-0 mt-4">
                                <div class="col-7">
                                    <h4 class="mb-2 fw-bold text-dark">${{number_format(getRevenuShares(),2)}}</h4>
                                    <p class="text-muted mb-0 fs-13">
                                        <span class="badge bg-danger-subtle text-danger">
                                            <i class="ri-arrow-down-line align-middle"></i>
                                        </span>
                                    </p>
                                </div>
                                <div class="col-5">
                                    <div class="apex-charts crypto-widget"
                                         data-colors='["--vz-danger", "--vz-transparent"]'
                                         id="binance_sparkline_charts" dir="ltr"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="card card-animate shadow-sm border-0 h-100">
                        <div class="card-body p-4">
                            <div class="float-end">
                                <div class="dropdown">
                                    <a class="text-muted" href="#" data-bs-toggle="dropdown"
                                       aria-haspopup="true" aria-expanded="false">
                                        <i class="ri-more-2-fill fs-4"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#">
                                            <i class="ri-eye-line me-2"></i>{{__('View Details')}}
                                        </a>
                                        <a class="dropdown-item text-danger" href="#">
                                            <i class="ri-delete-bin-line me-2"></i>{{__('Remove Watchlist')}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-sm bg-light rounded-circle p-2">
                                    <img src="{{ Vite::asset('resources/images/svg/crypto-icons/xmr.svg') }}"
                                         class="img-fluid" alt="">
                                </div>
                                <h6 class="ms-3 mb-0 fs-15 fw-semibold">{{__('Transfer Made')}}</h6>
                            </div>
                            <div class="row align-items-end g-0 mt-4">
                                <div class="col-7">
                                    <h4 class="mb-2 fw-bold text-dark" id="realrev">
                                        ${{number_format(getRevenuSharesReal(),2)}}
                                    </h4>
                                    <p class="text-muted mb-0 fs-13">
                                        <span class="badge bg-danger-subtle text-danger">
                                            <i class="ri-arrow-down-line align-middle"></i>
                                        </span>
                                    </p>
                                </div>
                                <div class="apex-charts crypto-widget"
                                     data-colors='["--vz-danger", "--vz-transparent"]'
                                     id="binance_sparkline_charts" dir="ltr"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body bg-soft-warning p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="fs-14 mb-3 fw-semibold text-dark">{{__('My Portfolio')}}</h5>
                            <h2 class="mb-2 fw-bold text-dark">$<?php echo $solde->soldeCB / 1 ?></h2>
                            <p class="text-muted mb-0">
                                <span class="badge bg-success-subtle text-success">
                                    <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                </span>
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            <div
                                class="avatar-lg bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center">
                                <i class="ri-wallet-3-line text-warning fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="fs-14 mb-3 fw-semibold text-dark">{{__('Today\'s Cash Transfert')}}</h5>
                            <h2 class="mb-2 fw-bold text-dark">$<?php echo $vente_jour / 1 ?></h2>
                            <p class="text-muted mb-0">
                                <span class="badge bg-success-subtle text-success">
                                    <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                </span>
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            <div
                                class="avatar-lg bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                <i class="ri-hand-coin-line text-primary fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="fs-14 mb-3 fw-semibold text-dark">{{__('Overall Cash Transfert')}}</h5>
                            <h2 class="mb-2 fw-bold text-dark">$<?php echo $vente_total / 1 ?></h2>
                            <p class="text-muted mb-0">
                                <span class="badge bg-success-subtle text-success">
                                    <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                </span>
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            <div
                                class="avatar-lg bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                <i class="ri-line-chart-line text-success fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="module">
        document.addEventListener("DOMContentLoaded", function () {

            $('#transfert').DataTable(
                {
                    "ordering": true,
                    retrieve: true,
                    "colReorder": false,
                    "orderCellsTop": true,
                    "fixedHeader": true,
                    "order": [[2, 'desc']],
                    "processing": true,
                    "serverSide": false,
                    "aLengthMenu": [[10, 30, 50], [10, 30, 50]],
                    search: {return: true},
                    autoWidth: false,
                    bAutoWidth: false,
                    "ajax": {
                        url: "{{route('api_transfert',['locale'=> app()->getLocale()])}}",
                        type: "GET",
                        headers: {'Authorization': 'Bearer ' + "{{generateUserToken()}}"},
                        error: function (xhr, error, thrown) {
                            loadDatatableModalError('transfert')
                        }
                    },
                    "columns": [
                        {data: 'value'},
                        {data: 'description'},
                        {data: 'created_at'}
                    ],
                    "language": {"url": urlLang}
                }
            );

            $('#shares-sold').DataTable(
                {
                    "ordering": true,
                    retrieve: true,
                    "colReorder": false,
                    dom: 'Bfrtip',
                    buttons: [
                        {extend: 'copyHtml5', text: '<i class="ri-file-copy-2-line"></i>', titleAttr: 'Copy'},
                        {extend: 'excelHtml5', text: '<i class="ri-file-excel-2-line"></i>', titleAttr: 'Excel'},
                        {extend: 'csvHtml5', text: '<i class="ri-file-text-line"></i>', titleAttr: 'CSV'},
                        {extend: 'pdfHtml5', text: '<i class="ri-file-pdf-line"></i>', titleAttr: 'PDF'}
                    ],
                    "orderCellsTop": true,
                    "fixedHeader": true,
                    "order": [[14, 'desc']],
                    "processing": true,
                    "serverSide": false,
                    "pageLength": 1000,
                    "aLengthMenu": [[10, 30, 50, 100, 1000], [10, 30, 50, 100, 1000]],
                    search: {return: true},
                    autoWidth: false,
                    bAutoWidth: false,
                    "ajax": "{{route('api_shares_soldes',['locale'=> app()->getLocale()])}}",
                    "columns": [
                        {data: 'formatted_created_at_date'},
                        {data: 'flag'},
                        {data: 'mobile'},
                        {data: 'Name'},
                        {data: 'total_shares'},
                        {data: 'sell_price_now'},
                        {data: 'gain'},
                        {data: 'WinPurchaseAmount'},
                        {data: 'Balance', "className": 'editable'},
                        {data: 'total_price'},
                        {data: 'value'},
                        {data: 'PU'},
                        {data: 'share_price'},
                        {data: 'formatted_created_at'},
                    ],
                    "columnDefs":
                        [
                            {
                                "targets": [7],
                                render: function (data, type, row) {
                                    if (Number(row.WinPurchaseAmount) === 1)
                                        return '<span class="badge bg-success fs-14" data-id="' + row.id + '" data-phone="' + row.mobile +
                                            '" data-asset="' + row.asset + '" data-amount="' + row.total_price + '" >{{__('Transfert Made')}}</span>';
                                    if (Number(row.WinPurchaseAmount) === 0)
                                        return '<span class="badge bg-danger fs-14" data-id="' + row.id + '" data-phone="' + row.mobile +
                                            '" data-asset="' + row.asset + '" data-amount="' + row.total_price + '" >{{__('Free')}}</span>';

                                    if (Number(row.WinPurchaseAmount) === 2)
                                        return '<span class="badge bg-warning fs-14" data-id="' + row.id + '" data-phone="' + row.mobile +
                                            '" data-asset="' + row.asset + '" data-amount="' + row.total_price + '" >{{__('Mixed')}}</span>';
                                },
                            },
                        ],
                    "language": {"url": urlLang}
                }
            );
        });
    </script>
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

        document.addEventListener("DOMContentLoaded", function () {

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
                var url = '{{ route('api_user_cash', ['locale' => app()->getLocale()]) }}';
                var token = "{{ generateUserToken() }}";

                $.ajax({
                    url: url,
                    method: 'GET',
                    headers: {
                        'Authorization': 'Bearer ' + token
                    },
                    dataType: 'json',
                    success: function (response) {
                        chart.updateSeries([{name: 'Balance', data: response}]);
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching user cash data:', error);
                    }
                });
            }
            if (chart2Origin) {
                var url3 = '{{ route('api_share_evolution_date', ['locale' => app()->getLocale()]) }}';
                var token = "{{ generateUserToken() }}";

                $.ajax({
                    url: url3,
                    method: 'GET',
                    headers: {
                        'Authorization': 'Bearer ' + token
                    },
                    dataType: 'json',
                    success: function (response) {
                        var series1 = {name: 'Sales-bar', type: 'bar', data: response};
                        var series2 = {name: 'sales-line', type: 'line', data: response};
                        chart2.updateSeries([series1, series2]);
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching data:', error);
                    }
                });
            }
            $(document).on("click", "#date", function () {
                if (chart2Origin) {
                    var url3 = '{{ route('api_share_evolution_date', ['locale' => app()->getLocale()]) }}';
                    var token = "{{ generateUserToken() }}";

                    $.ajax({
                        url: url3,
                        method: 'GET',
                        headers: {
                            'Authorization': 'Bearer ' + token
                        },
                        dataType: 'json',
                        success: function (response) {
                            var series1 = {name: 'Sales-bar', type: 'bar', data: response};
                            var series2 = {name: 'sales-line', type: 'line', data: response};
                            chart2.updateSeries([series1, series2]);
                        },
                        error: function (xhr, status, error) {
                            console.error('Failed to fetch evolution data:', error);
                        }
                    });
                }
            });
            $(document).on("click", "#week", function () {
                if (chart2Origin && chart1Origin) {
                    var url3 = '{{ route('api_share_evolution_week', ['locale' => app()->getLocale()]) }}';
                    var token = "{{ generateUserToken() }}";

                    $.ajax({
                        url: url3,
                        method: 'GET',
                        headers: {
                            'Authorization': 'Bearer ' + token
                        },
                        dataType: 'json',
                        success: function (response) {
                            var series1 = {name: 'Sales-bar', type: 'bar', data: response};
                            var series2 = {name: 'sales-line', type: 'line', data: response};
                            chart2.updateSeries([series1, series2]);
                        },
                        error: function (xhr, status, error) {
                            console.error('Error fetching weekly evolution data:', error);
                        }
                    });
                }
            });
            $(document).on("click", "#month", function () {
                if (chart2Origin && chart1Origin) {
                    var url3 = '{{ route('api_share_evolution_month', ['locale' => app()->getLocale()]) }}';
                    var token = "{{ generateUserToken() }}";

                    $.ajax({
                        url: url3,
                        method: 'GET',
                        headers: {
                            'Authorization': 'Bearer ' + token
                        },
                        dataType: 'json',
                        success: function (response) {
                            var series1 = {name: 'Sales-bar', type: 'bar', data: response};
                            var series2 = {name: 'sales-line', type: 'line', data: response};
                            chart2.updateSeries([series1, series2]);
                        },
                        error: function (xhr, status, error) {
                            console.error('Error fetching monthly evolution data:', error);
                        }
                    });
                }
            });
            $(document).on("click", "#day", function () {
                if (chart2Origin && chart1Origin) {
                    var url3 = '{{ route('api_share_evolution_day', ['locale' => app()->getLocale()]) }}';
                    var token = "{{ generateUserToken() }}";

                    $.ajax({
                        url: url3,
                        method: 'GET',
                        headers: {                            'Authorization': 'Bearer ' + token                        },
                        dataType: 'json',
                        success: function (response) {
                            var series1 = {name: 'Sales-bar', type: 'bar', data: response};
                            var series2 = {name: 'sales-line', type: 'line', data: response};
                            chart2.updateSeries([series1, series2]);
                        },
                        error: function (xhr, status, error) {
                            console.error('Error fetching daily evolution data:', error);
                        }
                    });
                }
            });
            if (chart2Origin && chart1Origin) {
                chart2.render();

                var token = "{{ generateUserToken() }}";
                var url1 = '{{ route('api_share_evolution', ['locale' => app()->getLocale()]) }}';
                var url2 = '{{ route('api_action_values', ['locale' => app()->getLocale()]) }}';

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

                $.when(request1, request2).then(function (response1, response2) {
                    var series1 = {name: 'Sales', type: 'area', data: response1[0]};
                    var series2 = {name: 'Function', type: 'line', data: response2[0]};
                    chart1.updateSeries([series1, series2]);
                });
            }
        });
    </script>
</div>
