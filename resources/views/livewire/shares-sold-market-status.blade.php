<div>
    @section('title')
        {{ __('Share_sold') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Shares Sold : market status') }}
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-12">
            <div class="card" id="marketList">
                <div class="card-header border-bottom-dashed d-flex align-items-center">
                    <h4 class="card-title mb-0 flex-grow-1">{{__('Market Status')}}</h4>
                    <div class="flex-shrink-0">
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <button type="button" class="btn btn-primary btn-sm">{{__('Today')}}</button>
                            <button type="button" class="btn btn-outline-primary btn-sm">{{__('Overall')}}</button>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table id="shares-sold"
                           class="table table-striped table-bordered"
                           style="width:100%">
                        <thead class="table-light">
                        <tr class="head2earn  tabHeader2earn">
                            <th style=" border: none ;text-align: center;">{{__('date_purchase')}}</th>
                            <th>{{__('countrie')}}</th>
                            <th>{{__('mobile')}}</th>
                            <th>{{__('Name')}}</th>
                            <th>{{__('total_shares')}}</th>
                            <th>{{__('sell_price_now')}}</th>
                            <th>{{__('gains')}}</th>
                            <th>{{__('Real_Sold')}}</th>
                            <th>{{__('Real_Sold_amount')}}</th>
                            <th>{{__('total_price')}}</th>
                            <th>{{__('number_of_shares')}}</th>
                            <th>{{__('gifted_shares')}}</th>
                            <th>{{__('average_price')}}</th>
                            <th>{{__('share_price')}}</th>
                            <th>{{__('heure_purchase')}}</th>
                        </tr>
                        </thead>
                        <tfoot>
                        </tfoot>
                        <tbody class="body2earn">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal fade" id="realsoldmodif" tabindex="-1" aria-labelledby="exampleModalgridLabel"
                 aria-modal="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ __('Transfert Cash') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="javascript:void(0);">
                                <div class="row g-3">
                                    <div class="col-xxl-6">
                                        <div class="input-group">
                                                    <span class="input-group-text">
                                                        <img id="realsold-country" alt=""
                                                             class="avatar-xxs me-2"></span>
                                            <input type="text" class="form-control" disabled id="realsold-phone"
                                                   aria-describedby="basic-addon1">
                                        </div>
                                    </div>
                                    <div class="col-xxl-6">
                                        <div class="input-group">
                                            <input id="realsold-reciver" type="hidden">
                                            <input type="number" class="form-control" id="realsold-ammount">
                                            <input hidden type="number" class="form-control"
                                                   id="realsold-ammount-total">
                                            <span class="input-group-text">$</span>

                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="button" class="btn btn-light"
                                                    data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                                            <button type="button" id="realsold-submit"
                                                    class="btn btn-primary">{{ __('Submit') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="module">
    $(document).on('turbolinks:load', function () {
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
                "ajax": "{{route('API_sharessoldes',['locale'=> app()->getLocale()])}}",
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
                    {data: 'gifted_shares'},
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
                                    return '<span class="badge bg-success" data-id="' + row.id + '" data-phone="' + row.mobile +
                                        '" data-asset="' + row.asset + '" data-amount="' + row.total_price + '" >{{__('Transfert Made')}}</span>';
                                if (Number(row.WinPurchaseAmount) === 0)
                                    return '<span class="badge bg-danger" data-id="' + row.id + '" data-phone="' + row.mobile +
                                        '" data-asset="' + row.asset + '" data-amount="' + row.total_price + '" >{{__('Free')}}</span>';

                                if (Number(row.WinPurchaseAmount) === 2)
                                    return '<span class="badge bg-warning" data-id="' + row.id + '" data-phone="' + row.mobile +
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
