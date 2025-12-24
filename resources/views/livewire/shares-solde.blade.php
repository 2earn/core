<div class="container">
    @section('title')
        {{ __('history') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('My shares') }}
        @endslot
    @endcomponent
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <h5 class="card-title mb-0">{{__('Share Price Evolution')}}</h5>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="chart1">
            </div>
        </div>
    </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div id="shares_container">
                            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                                <h5 class="mb-0">{{ __('Transaction History') }}</h5>
                                <select wire:model.live="perPage" class="form-select form-select-sm per-page-width">
                                    <option value="10">10</option>
                                    <option value="30">30</option>
                                    <option value="50">50</option>
                                </select>
                            </div>

                            <div wire:loading class="text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">{{ __('Loading...') }}</span>
                                </div>
                            </div>

                            <div wire:loading.remove>
                                @if($transactions->count() > 0)
                                    <div class="row g-3">
                                        @foreach($transactions as $tr)
                                            @php
                                                $value = $tr['value'] ?? ($tr['value_format'] ?? '0');
                                                $isPositive = is_string($value) && strpos($value, '+') !== false;
                                            @endphp
                                            <div class="col-12">
                                                <div class="card border shadow-sm h-100">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
                                                            <div class="flex-grow-1">
                                                                <h6 class="card-title mb-1 fw-bold">{!! $tr['operation'] ?? __('Share') !!}</h6>
                                                                <small class="text-muted d-block">
                                                                    {{ $tr['reference'] ?? ($tr['formatted_created_at'] ?? ($tr['created_at'] ?? '')) }}
                                                                </small>
                                                            </div>
                                                            <span class="badge {{ $isPositive ? 'bg-success' : 'bg-danger' }} fs-6 px-3 py-2">
                                                    {{ $value }}
                                                </span>
                                                        </div>
                                                        <div class="row g-3">
                                                            <div class="col-6 col-md-3">
                                                                <small class="text-muted d-block mb-1">{{ __('Created at') }}</small>
                                                                <strong class="d-block">
                                                                    {{ $tr['created_at'] ?? ($tr['formatted_created_at'] ?? '-') }}
                                                                </strong>
                                                            </div>
                                                            <div class="col-6 col-md-3">
                                                                <small class="text-muted d-block mb-1">{{ __('Current balance') }}</small>
                                                                <strong class="d-block">
                                                                    {{ $tr['current_balance'] ?? ($tr['present_value'] ?? '-') }}
                                                                </strong>
                                                            </div>
                                                            <div class="col-12 col-md-6">
                                                                <small class="text-muted d-block mb-1">
                                                                    {{ __('Complementary information') }}
                                                                </small>
                                                                <span class="d-block text-break">
                                                        {!! $tr['complementary_information'] ?? '-' !!}
                                                    </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="alert alert-info text-center">{{ __('No transactions found') }}</div>
                                @endif

                                @if($transactions->hasPages())
                                    <div class="mt-4">
                                        <div class="d-flex justify-content-center">
                                            {{ $transactions->links() }}
                                        </div>
                                        <div class="text-center mt-2">
                                            <small class="text-muted">
                                                {{ __('Showing') }} {{ $transactions->firstItem() ?? 0 }}
                                                {{ __('to') }} {{ $transactions->lastItem() ?? 0 }}
                                                {{ __('of') }} {{ $transactions->total() }} {{ __('entries') }}
                                            </small>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="{{ URL::asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script id="rendered-js" type="module">
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

        });
    </script>
</div>
