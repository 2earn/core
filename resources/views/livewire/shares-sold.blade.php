<div class="{{getContainerType()}}">
    @section('title')
        {{ __('Shares Sold Dashboard') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Shares Sold Dashboard') }}
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-12">
            @include('layouts.flash-messages')
        </div>
    </div>
    <div class="row">
        <div class="col-12 card shadow-sm border-0 mb-4">
            <div class="card-header bg-gradient border-0 py-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-sm">
                            <div class="avatar-title rounded bg-soft-primary text-primary fs-20">
                                <i class="ri-line-chart-line"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="card-title mb-0 fw-semibold">{{__('My Portfolio Statistics')}}</h5>
                        <p class="text-muted mb-0 small">{{__('Track your investment performance')}}</p>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <ul class="nav nav-tabs nav-tabs-custom nav-justified mb-4" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active py-3" data-bs-toggle="tab" href="#cash-balance" role="tab">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="ri-wallet-3-line me-2 fs-18"></i>
                                <span class="d-none d-sm-inline">{{__('My Cash Balance')}}</span>
                                <span class="d-inline d-sm-none">{{__('Cash')}}</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-3" data-bs-toggle="tab" href="#price-evolution" role="tab">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="ri-line-chart-line me-2 fs-18"></i>
                                <span class="d-none d-sm-inline">{{__('Share Price Evolution')}}</span>
                                <span class="d-inline d-sm-none">{{__('Evolution')}}</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-3" data-bs-toggle="tab" href="#share-sold" role="tab">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="ri-bar-chart-box-line me-2 fs-18"></i>
                                <span class="d-none d-sm-inline">{{__('Share Price sold')}}</span>
                                <span class="d-inline d-sm-none">{{__('Sold')}}</span>
                            </div>
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane active show" id="cash-balance" role="tabpanel">
                        <div id="chart" class="apex-charts"></div>
                    </div>
                    <div class="tab-pane" id="price-evolution" role="tabpanel">
                        <div id="chart1" class="apex-charts"></div>
                    </div>
                    <div class="tab-pane" id="share-sold" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h6 class="mb-0">{{__('Filter by Period')}}</h6>
                            <div class="btn-group" role="group" aria-label="Date filter">
                                <button id="day" type="button" class="btn btn-primary btn-sm">
                                    <i class="ri-calendar-event-line me-1"></i>{{__('Day')}}
                                </button>
                                <button id="week" type="button" class="btn btn-outline-primary btn-sm">
                                    <i class="ri-calendar-2-line me-1"></i>{{__('Week')}}
                                </button>
                                <button id="month" type="button" class="btn btn-outline-primary btn-sm">
                                    <i class="ri-calendar-check-line me-1"></i>{{__('Month')}}
                                </button>
                                <button id="date" type="button" class="btn btn-outline-primary btn-sm">
                                    <i class="ri-calendar-line me-1"></i>{{__('Date')}}
                                </button>
                            </div>
                        </div>
                        <div id="chart2" class="apex-charts"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <div class="row mb-3">
        <div class="col-12">
            <div class="card border-0 bg-primary-subtle">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm">
                                <div class="avatar-title rounded bg-primary text-white fs-20">
                                    <i class="ri-star-line"></i>
                                </div>
                            </div>
                            <div class="ms-3">
                                <h5 class="mb-0 fw-semibold">{{__('Portfolio Metrics')}}</h5>
                                <p class="text-muted mb-0 small">{{__('Key performance indicators')}}</p>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <button class="btn btn-primary btn-sm shadow-sm">
                                <i class="ri-add-line align-bottom me-1"></i>{{__('Add Watchlist')}}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <div class="row g-3 mb-3">
        <div class="col-sm-12 col-md-6 col-lg-4">
            <div class="card card-animate border-0 shadow-sm h-100 overflow-hidden">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="flex-grow-1">
                            <div class="avatar-sm bg-danger-subtle rounded-3 mb-3">
                                <div class="avatar-title bg-transparent text-danger fs-20">
                                    <i class="ri-arrow-down-circle-line"></i>
                                </div>
                            </div>
                            <h6 class="mb-1 text-muted fs-13">{{__('Sold Shares')}}</h6>
                            <h3 class="mb-0 fw-bold">{{number_format(getSelledActions(),0)}}</h3>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-ghost-secondary" data-bs-toggle="dropdown">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#"><i class="ri-eye-line me-2"></i>{{__('View Details')}}</a>
                                <a class="dropdown-item text-danger" href="#"><i class="ri-delete-bin-line me-2"></i>{{__('Remove')}}</a>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-danger-subtle text-danger"><i class="ri-arrow-down-line align-middle"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-4">
            <div class="card card-animate border-0 shadow-sm h-100 overflow-hidden">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="flex-grow-1">
                            <div class="avatar-sm bg-info-subtle rounded-3 mb-3">
                                <div class="avatar-title bg-transparent text-info fs-20">
                                    <i class="ri-gift-line"></i>
                                </div>
                            </div>
                            <h6 class="mb-1 text-muted fs-13">{{__('Gifted Shares')}}</h6>
                            <h3 class="mb-0 fw-bold">{{number_format(getGiftedShares(),0)}}</h3>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-ghost-secondary" data-bs-toggle="dropdown">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#"><i class="ri-eye-line me-2"></i>{{__('View Details')}}</a>
                                <a class="dropdown-item text-danger" href="#"><i class="ri-delete-bin-line me-2"></i>{{__('Remove')}}</a>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-info-subtle text-info"><i class="ri-information-line align-middle"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-4">
            <div class="card card-animate border-0 shadow-sm h-100 overflow-hidden">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="flex-grow-1">
                            <div class="avatar-sm bg-warning-subtle rounded-3 mb-3">
                                <div class="avatar-title bg-transparent text-warning fs-20">
                                    <i class="ri-percent-line"></i>
                                </div>
                            </div>
                            <h6 class="mb-1 text-muted fs-13">{{__('Gifted/Sold Ratio')}}</h6>
                            <h3 class="mb-0 fw-bold">
                                @if(getSelledActions()>0)
                                    {{number_format(getGiftedShares()/getSelledActions()*100,2)}}%
                                @else
                                    0%
                                @endif
                            </h3>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-ghost-secondary" data-bs-toggle="dropdown">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#"><i class="ri-eye-line me-2"></i>{{__('View Details')}}</a>
                                <a class="dropdown-item text-danger" href="#"><i class="ri-delete-bin-line me-2"></i>{{__('Remove')}}</a>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-warning-subtle text-warning"><i class="ri-information-line align-middle"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-sm-12 col-md-6 col-lg-4">
            <div class="card card-animate border-0 shadow-sm h-100 overflow-hidden">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="flex-grow-1">
                            <div class="avatar-sm bg-success-subtle rounded-3 mb-3">
                                <div class="avatar-title bg-transparent text-success fs-20">
                                    <i class="ri-money-dollar-circle-line"></i>
                                </div>
                            </div>
                            <h6 class="mb-1 text-muted fs-13">{{__('Shares Actual Price')}}</h6>
                            <h3 class="mb-0 fw-bold text-success">
                                ${{number_format(actualActionValue(getSelledActions(true)), 2)}}
                            </h3>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-ghost-secondary" data-bs-toggle="dropdown">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#"><i class="ri-eye-line me-2"></i>{{__('View Details')}}</a>
                                <a class="dropdown-item text-danger" href="#"><i class="ri-delete-bin-line me-2"></i>{{__('Remove')}}</a>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-success-subtle text-success"><i class="ri-arrow-up-line align-middle"></i></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-6 col-lg-4">
            <div class="card card-animate border-0 shadow-sm h-100 overflow-hidden">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="flex-grow-1">
                            <div class="avatar-sm bg-primary-subtle rounded-3 mb-3">
                                <div class="avatar-title bg-transparent text-primary fs-20">
                                    <i class="ri-funds-line"></i>
                                </div>
                            </div>
                            <h6 class="mb-1 text-muted fs-13">{{__('Revenue')}}</h6>
                            <h3 class="mb-0 fw-bold text-primary">${{number_format(getRevenuShares(),2)}}</h3>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-ghost-secondary" data-bs-toggle="dropdown">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#"><i class="ri-eye-line me-2"></i>{{__('View Details')}}</a>
                                <a class="dropdown-item text-danger" href="#"><i class="ri-delete-bin-line me-2"></i>{{__('Remove')}}</a>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-primary-subtle text-primary"><i class="ri-information-line align-middle"></i></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-6 col-lg-4">
            <div class="card card-animate border-0 shadow-sm h-100 overflow-hidden">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="flex-grow-1">
                            <div class="avatar-sm bg-secondary-subtle rounded-3 mb-3">
                                <div class="avatar-title bg-transparent text-secondary fs-20">
                                    <i class="ri-exchange-dollar-line"></i>
                                </div>
                            </div>
                            <h6 class="mb-1 text-muted fs-13">{{__('Transfer Made')}}</h6>
                            <h3 class="mb-0 fw-bold text-secondary" id="realrev">
                                ${{number_format(getRevenuSharesReal(),2)}}
                            </h3>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-ghost-secondary" data-bs-toggle="dropdown">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#"><i class="ri-eye-line me-2"></i>{{__('View Details')}}</a>
                                <a class="dropdown-item text-danger" href="#"><i class="ri-delete-bin-line me-2"></i>{{__('Remove')}}</a>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-secondary-subtle text-secondary"><i class="ri-information-line align-middle"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <div class="row g-3 mb-4">
        <div class="col-sm-12 col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm overflow-hidden card-animate">
                <div class="card-body bg-warning-subtle p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-2 fw-medium">{{__('My Portfolio')}}</p>
                            <h2 class="mb-0 fw-bold">${{number_format($solde->soldeCB, 2)}}</h2>
                            <span class="badge bg-success-subtle text-success mt-2">
                                <i class="ri-arrow-right-up-line fs-13 align-middle"></i> {{__('Active')}}
                            </span>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-lg">
                                <div class="avatar-title bg-white bg-opacity-50 rounded-circle">
                                    <i class="ri-wallet-3-line text-warning fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm overflow-hidden card-animate">
                <div class="card-body bg-primary-subtle p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-2 fw-medium">{{__('Today\'s Cash Transfer')}}</p>
                            <h2 class="mb-0 fw-bold">${{number_format($vente_jour ?? 0, 2)}}</h2>
                            <span class="badge bg-success-subtle text-success mt-2">
                                <i class="ri-arrow-right-up-line fs-13 align-middle"></i> {{__('Today')}}
                            </span>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-lg">
                                <div class="avatar-title bg-white bg-opacity-50 rounded-circle">
                                    <i class="ri-hand-coin-line text-primary fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm overflow-hidden card-animate">
                <div class="card-body bg-success-subtle p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-2 fw-medium">{{__('Overall Cash Transfer')}}</p>
                            <h2 class="mb-0 fw-bold">${{number_format($vente_total ?? 0, 2)}}</h2>
                            <span class="badge bg-success-subtle text-success mt-2">
                                <i class="ri-arrow-right-up-line fs-13 align-middle"></i> {{__('Total')}}
                            </span>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-lg">
                                <div class="avatar-title bg-white bg-opacity-50 rounded-circle">
                                    <i class="ri-line-chart-line text-success fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="module">
        document.addEventListener("DOMContentLoaded", function () {

            initializeTransferTable();
            initializeSharesSoldTable();
        });

        function initializeTransferTable() {
            $('#transfert').DataTable({
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
                        loadDatatableModalError('transfert');
                    }
                },
                "columns": [
                    {data: 'value'},
                    {data: 'description'},
                    {data: 'created_at'}
                ],
                "language": {"url": urlLang}
            });
        }

        function initializeSharesSoldTable() {
            $('#shares-sold').DataTable({
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
                "columnDefs": [{
                    "targets": [7],
                    render: function (data, type, row) {
                        const baseClass = 'badge fs-14';
                        const baseData = `data-id="${row.id}" data-phone="${row.mobile}" data-asset="${row.asset}" data-amount="${row.total_price}"`;

                        if (Number(row.WinPurchaseAmount) === 1) {
                            return `<span class="${baseClass} bg-success" ${baseData}>{{__('Transfert Made')}}</span>`;
                        }
                        if (Number(row.WinPurchaseAmount) === 0) {
                            return `<span class="${baseClass} bg-danger" ${baseData}>{{__('Free')}}</span>`;
                        }
                        if (Number(row.WinPurchaseAmount) === 2) {
                            return `<span class="${baseClass} bg-warning" ${baseData}>{{__('Mixed')}}</span>`;
                        }
                        return data;
                    },
                }],
                "language": {"url": urlLang}
            });
        }
    </script>
    <script type="module">

        const chartOptions = {
            chart: {height: 350, type: 'area'},
            dataLabels: {enabled: false},
            series: [],
            title: {text: 'Cash Balance'},
            noData: {text: 'Loading...'},
            xaxis: {type: 'datetime'}
        };

        const chartOptions1 = {
            chart: {height: 350, type: 'area'},
            dataLabels: {enabled: false},
            series: [],
            title: {text: 'Share Price Evolution'},
            noData: {text: 'Loading...'},
            xaxis: {type: 'numeric'}
        };

        const chartOptions2 = {
            chart: {height: 350, type: 'line'},
            plotOptions: {
                bar: {
                    borderRadius: 10,
                    dataLabels: {
                        position: 'top',
                        enabled: true,
                        formatter: function (val) {
                            return val;
                        }
                    }
                }
            },
            stroke: {width: 2, curve: 'smooth'},
            series: [],
            title: {text: 'Share Price Evolution'},
            noData: {text: 'Loading...'},
            xaxis: {type: 'date'}
        };

        document.addEventListener("DOMContentLoaded", function () {
            const chartOrigin = document.querySelector('#chart');
            const chart1Origin = document.querySelector('#chart1');
            const chart2Origin = document.querySelector('#chart2');

            let chart, chart1, chart2;

            if (chartOrigin) {
                chart = new ApexCharts(chartOrigin, chartOptions);
                chart.render();
                loadCashBalanceData(chart);
            }

            if (chart1Origin) {
                chart1 = new ApexCharts(chart1Origin, chartOptions1);
                chart1.render();
                loadShareEvolutionData(chart1);
            }

            if (chart2Origin) {
                chart2 = new ApexCharts(chart2Origin, chartOptions2);
                chart2.render();
                loadShareEvolutionByDate(chart2, 'api_share_evolution_date');
            }

            function loadCashBalanceData(chartInstance) {
                $.ajax({
                    url: '{{ route('api_user_cash', ['locale' => app()->getLocale()]) }}',
                    method: 'GET',
                    headers: {'Authorization': 'Bearer ' + "{{ generateUserToken() }}"},
                    dataType: 'json',
                    success: function (response) {
                        chartInstance.updateSeries([{name: 'Balance', data: response}]);
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching user cash data:', error);
                    }
                });
            }

            function loadShareEvolutionData(chartInstance) {
                const request1 = $.ajax({
                    url: '{{ route('api_share_evolution', ['locale' => app()->getLocale()]) }}',
                    method: 'GET',
                    headers: {'Authorization': 'Bearer ' + "{{ generateUserToken() }}"},
                    dataType: 'json'
                });

                const request2 = $.ajax({
                    url: '{{ route('api_action_values', ['locale' => app()->getLocale()]) }}',
                    method: 'GET',
                    headers: {'Authorization': 'Bearer ' + "{{ generateUserToken() }}"},
                    dataType: 'json'
                });

                $.when(request1, request2).then(function (response1, response2) {
                    const series1 = {name: 'Sales', type: 'area', data: response1[0]};
                    const series2 = {name: 'Function', type: 'line', data: response2[0]};
                    chartInstance.updateSeries([series1, series2]);
                });
            }

            function loadShareEvolutionByDate(chartInstance, route) {
                $.ajax({
                    url: "{{ route('api_share_evolution_date', ['locale' => app()->getLocale()]) }}".replace('', route),
                    method: 'GET',
                    headers: {'Authorization': 'Bearer ' + "{{ generateUserToken() }}"},
                    dataType: 'json',
                    success: function (response) {
                        const series1 = {name: 'Sales-bar', type: 'bar', data: response};
                        const series2 = {name: 'sales-line', type: 'line', data: response};
                        chartInstance.updateSeries([series1, series2]);
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching evolution data:', error);
                    }
                });
            }

            $(document).on("click", "#day", function () {
                if (chart2) {
                    $(this).addClass('btn-primary').removeClass('btn-outline-primary');
                    $('#week, #month, #date').addClass('btn-outline-primary').removeClass('btn-primary');
                    loadShareEvolutionByDate(chart2, 'api_share_evolution_day');
                }
            });

            $(document).on("click", "#week", function () {
                if (chart2) {
                    $(this).addClass('btn-primary').removeClass('btn-outline-primary');
                    $('#day, #month, #date').addClass('btn-outline-primary').removeClass('btn-primary');
                    loadShareEvolutionByDate(chart2, 'api_share_evolution_week');
                }
            });

            $(document).on("click", "#month", function () {
                if (chart2) {
                    $(this).addClass('btn-primary').removeClass('btn-outline-primary');
                    $('#day, #week, #date').addClass('btn-outline-primary').removeClass('btn-primary');
                    loadShareEvolutionByDate(chart2, 'api_share_evolution_month');
                }
            });

            $(document).on("click", "#date", function () {
                if (chart2) {
                    $(this).addClass('btn-primary').removeClass('btn-outline-primary');
                    $('#day, #week, #month').addClass('btn-outline-primary').removeClass('btn-primary');
                    loadShareEvolutionByDate(chart2, 'api_share_evolution_date');
                }
            });
        });
    </script>
</div>
