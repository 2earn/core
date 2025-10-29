<div class="{{getContainerType()}}">
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
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body table-responsive">
                    <table id="shares-solde"
                           class="table table-striped table-bordered cell-border row-border table-hover mdl-data-table display nowrap"
                           style="width:100%">
                        <thead class="table-light">
                        <tr class="head2earn  tabHeader2earn">
                            <th>{{__('id')}}</th>
                            <th>{{__('formatted_created_at')}}</th>
                            <th>{{__('value_format')}}</th>
                            <th>{{__('total_shares')}}</th>
                            <th>{{__('total_price')}}</th>
                            <th>{{__('present_value')}}</th>
                            <th>{{__('current_earnings')}}</th>
                            <th>{{ __('Complementary information') }}</th>
                        </tr>
                        </thead>
                        <tbody class="body2earn">
                        </tbody>
                    </table>
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
            $('#shares-solde').DataTable({
                "ordering": true,
                retrieve: true,
                "colReorder": false,
                "orderCellsTop": true,
                "fixedHeader": true,
                "order": [[5, 'asc']],
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
                    }
                },
                "columns": [
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
</div>
