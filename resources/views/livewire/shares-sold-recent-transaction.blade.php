<div class="container-fluid">
@section('title')
        {{ __('Shares Sold: Recent transaction') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Shares Sold: Recent transaction') }}
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-xxl-12">
            <div class="card">
                <div class="card-body table-responsive">
                    <table id="transfert" class="table table-striped table-bordered cell-border row-border table-hover mdl-data-table display nowrap">
                        <thead class="table-light">
                        <tr class="head2earn  tabHeader2earn">
                            <th>{{__('value')}}</th>
                            <th>{{__('Description')}}</th>
                            <th>{{__('formatted_created_at')}}</th>
                        </tr>
                        </thead>
                        <tbody class="body2earn">
                        </tbody>
                    </table>
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
                        headers: {'Authorization': 'Bearer ' + "{{generateUserToken()}}"}
                    },
                    "columns": [
                        {data: 'value'},
                        {data: 'description'},
                        {data: 'created_at'}
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
                var url = '{{route('api_user_cash',['locale'=> app()->getLocale()])}}';
                $.getJSON(url, function (response) {
                    chart.updateSeries([{name: 'Balance', data: response}])
                });
            }
            if (chart2Origin && chart1Origin) {
                var url3 = '{{route('api_share_evolution_date',['locale'=> app()->getLocale()])}}';
                $.getJSON(url3, function (response) {
                    var series1 = {name: 'Sales-bar', type: 'bar', data: response};
                    var series2 = {name: 'sales-line', type: 'line', data: response};
                    chart2.updateSeries([series1, series2]);
                });
            }
            $(document).on("click", "#date", function () {
                if (chart2Origin) {
                    var url3 = '{{route('api_share_evolution_date',['locale'=> app()->getLocale()])}}';
                    $.getJSON(url3, function (response) {
                        var series1 = {name: 'Sales-bar', type: 'bar', data: response};
                        var series2 = {name: 'sales-line', type: 'line', data: response};

                        chart2.updateSeries([series1, series2]);
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
                        success: function(response) {
                            var series1 = { name: 'Sales-bar', type: 'bar', data: response };
                            var series2 = { name: 'sales-line', type: 'line', data: response };
                            chart2.updateSeries([series1, series2]);
                        },
                        error: function(xhr, status, error) {
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
                        success: function(response) {
                            var series1 = { name: 'Sales-bar', type: 'bar', data: response };
                            var series2 = { name: 'sales-line', type: 'line', data: response };
                            chart2.updateSeries([series1, series2]);
                        },
                        error: function(xhr, status, error) {
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
                        headers: {
                            'Authorization': 'Bearer ' + token
                        },
                        dataType: 'json',
                        success: function(response) {
                            var series1 = { name: 'Sales-bar', type: 'bar', data: response };
                            var series2 = { name: 'sales-line', type: 'line', data: response };
                            chart2.updateSeries([series1, series2]);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching daily evolution data:', error);
                        }
                    });
                }
            });
            if (chart2Origin && chart1Origin) {
                chart2.render();
                var url1 = '{{route('api_share_evolution',['locale'=> app()->getLocale()])}}';
                var url2 = '{{route('api_action_values',['locale'=> app()->getLocale()])}}';

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
