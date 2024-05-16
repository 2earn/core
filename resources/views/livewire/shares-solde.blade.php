
<div>
    @section('title'){{ __('history') }} @endsection
    @section('content')

        @component('components.breadcrumb')
            @slot('li_1')@endslot
            @slot('title') {{ __('My shares') }} @endslot
        @endcomponent
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-0">Share Price Evolution</h5>
                        </div>

                    </div>
                </div>
                <div class="card-body">
                    <div id="chart1">
                    </div>
                </div>
            </div>


            <script src="{{ URL::asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
            <script id="rendered-js">

                var options1 = {
                    colors : ['#008ffb', '#00e396', '#d4526e'],
                    chart: {
                        height: 350,
                        type: 'area',
                    },
                    dataLabels: {
                        enabled: false
                    },
                    series: [],
                    title: {
                        text: 'Share Price Evolution',
                    },
                    noData: {
                        text: 'Loading...'
                    },
                    xaxis: {
                        type: 'numeric',

                    },

                    stroke: {
                        curve: 'straight',
                    },
                    annotations: {
                        points: [{
                            x: {{getSelledActions() * 1.05/2}},
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


                var chart1 = new ApexCharts(
                    document.querySelector("#chart1"),
                    options1
                );

                chart1.render();
                var url1 = '{{route('API_shareevolution',['locale'=> app()->getLocale()])}}';
                var url2 = '{{route('API_actionvalues',['locale'=> app()->getLocale()])}}';
                var url3 = '{{route('API_shareevolutionuser',['locale'=> app()->getLocale()])}}';
                $.when(
                    $.getJSON(url1),
                    $.getJSON(url2),
                    $.getJSON(url3)
                ).then(function(response1, response2, response3) {
                    var series1 = {
                        name: 'Sales',
                        type: 'area',
                        data: response1[0],


                    };
                    //console.log("Stroke property for series 1:", series1.stroke);
                    var series2 = {
                        name: 'Function',
                        type: 'line',
                        data: response2[0]

                    };
                    var series3 = {
                        name: 'My Shares',
                        type: 'area',
                        data: response3[0]

                    };
                    //console.log(response3[0]);
                    //console.log(response2[0]);
                    //console.log(response1[0]);
                    // Update the chart with both series
                    chart1.updateSeries([series1, series2, series3]);
                });
            </script>
            <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <!--    <h5 class="card-title mb-0">Alternative Pagination</h5>-->
                    </div>
                    <div class="card-body table-responsive">
                        <table id="shares-solde" class="table nowrap dt-responsive align-middle table-hover table-bordered" style="width:100%">
                            <thead  class="table-light">
                            <tr class="head2earn  tabHeader2earn" >
                                <th style=" border: none ;text-align: center;">{{__('date_purchase')}}</th>
                                <th style=" border: none;">{{__('number_of_shares')}}</th>
                                <th style=" border: none;">{{__('gifted_shares')}}</th>
                                <th style=" border: none ;text-align: center; ">{{__('total_shares')}}</th>
                                <th style=" border: none;text-align: center; ">{{__('total_price')}}</th>
                                <th style=" border: none;text-align: center; ">{{__('present_value')}}</th>
                                <th style=" border: none;text-align: center; ">{{__('current_earnings')}}</th>

                            </tr>
                            </thead>
                            <tbody class="body2earn">
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
            <!--end col-->
        </div>



</div>

