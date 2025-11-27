<div class="{{getContainerType()}}">
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
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <input type="text"
                                       wire:model.live.debounce.300ms="search"
                                       class="form-control"
                                       placeholder="{{ __('Search transactions...') }}">
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <select wire:model.live="perPage" class="form-select d-inline-block w-auto">
                                <option value="10">10</option>
                                <option value="30">30</option>
                                <option value="50">50</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Header Row -->
                    <div class="row fw-bold border-bottom pb-2 mb-3 bg-light p-3">
                        <div class="col-md-3">
                            <span wire:click="sortBy('value')" style="cursor: pointer;">
                                {{__('value')}}
                                @if($sortField === 'value')
                                    <i class="mdi mdi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </span>
                        </div>
                        <div class="col-md-6">
                            <span wire:click="sortBy('description')" style="cursor: pointer;">
                                {{__('Description')}}
                                @if($sortField === 'description')
                                    <i class="mdi mdi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </span>
                        </div>
                        <div class="col-md-3">
                            <span wire:click="sortBy('created_at')" style="cursor: pointer;">
                                {{__('formatted_created_at')}}
                                @if($sortField === 'created_at')
                                    <i class="mdi mdi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Data Rows -->
                    @forelse($transactions as $transaction)
                        <div class="row border-bottom py-3 align-items-center hover-bg-light">
                            <div class="col-md-3">
                                <strong>{{ $transaction->value }}</strong>
                            </div>
                            <div class="col-md-6">
                                {{ $transaction->description }}
                            </div>
                            <div class="col-md-3">
                                {{ \Carbon\Carbon::parse($transaction->created_at)->format('Y-m-d H:i:s') }}
                            </div>
                        </div>
                    @empty
                        <div class="row">
                            <div class="col-12 text-center py-5">
                                <p class="text-muted">{{ __('No transactions found') }}</p>
                            </div>
                        </div>
                    @endforelse

                    <!-- Pagination -->
                    <div class="row mt-4">
                        <div class="col-12">
                            {{ $transactions->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .hover-bg-light:hover {
            background-color: #f8f9fa;
            cursor: pointer;
        }
    </style>

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
