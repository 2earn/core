
<div>
    <?php $__env->startSection('title'); ?><?php echo e(__('Share_sold')); ?> <?php $__env->stopSection(); ?>
    <?php $__env->startSection('content'); ?>

        <?php $__env->startComponent('components.breadcrumb'); ?>
            <?php $__env->slot('li_1'); ?><?php $__env->endSlot(); ?>
            <?php $__env->slot('title'); ?> <?php echo e(__('Shares Sold')); ?> <?php $__env->endSlot(); ?>
        <?php echo $__env->renderComponent(); ?>
<?php $__env->startSection('css'); ?>

                <link href="<?php echo e(URL::asset('assets/libs/swiper/swiper.min.css')); ?>" rel="stylesheet" type="text/css" />

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
<?php $__env->slot('li_1'); ?> Crypto <?php $__env->endSlot(); ?>
<?php $__env->slot('title'); ?> Transactions <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="row">
    <div class="col-xxl-9">







        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h5 class="card-title mb-0">My Portfolio Statistics</h5>
                    </div>

                </div>
            </div>
            <div class="card-body">
                    <!-- Base Example -->
                    <div class="accordion" id="default-accordion-example">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    My Cash Balance
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#default-accordion-example">
                                <div class="accordion-body">
                                    <div id="chart">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Share Price Evolution
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#default-accordion-example">
                                <div class="accordion-body">
                                    <div id="chart1">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Share Price
                                </button>

                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#default-accordion-example">
                                <div class="accordion-body">
                                    <div>
                                        <button id="date" type="button" class="btn btn-soft-secondary btn-sm">
                                            By date
                                        </button>
                                        <button id="week" type="button" class="btn btn-soft-secondary btn-sm">
                                            By week
                                        </button>
                                        <button id="month" type="button" class="btn btn-soft-secondary btn-sm">
                                            By month
                                        </button>
                                        <button id="day" type="button" class="btn btn-soft-primary btn-sm">
                                            By day
                                        </button>
                                    </div>
                                    <div id="chart2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>


        <script src="<?php echo e(URL::asset('assets/libs/apexcharts/apexcharts.min.js')); ?>"></script>
        <script id="rendered-js">
            var options = {
                chart: {
                    height: 350,
                    type: 'area',
                },
                dataLabels: {
                    enabled: false
                },
                series: [],
                title: {
                    text: 'Cash Balance',
                },
                noData: {
                    text: 'Loading...'
                },
                xaxis: {
                    type: 'datetime',
                    }
            }
            var options1 = {
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
                }

            }
            var options2 = {
                chart: {
                    height: 350,
                    type: 'line',
                },
                plotOptions: {
                    bar: {
                        borderRadius: 10,
                        dataLabels: {
                            position: 'top',
                            enabled: true,
                            formatter: function (val) {
                                return val;
                            },// top, center, bottom
                        },

                    }
                },
                stroke: {
                    width: 2,
                    curve: 'smooth'
                },

                series: [],
                title: {
                    text: 'Share Price Evolution',
                },
                noData: {
                    text: 'Loading...'
                },
                xaxis: {
                    type: 'date',
                }

            }

            var chart = new ApexCharts(
                document.querySelector("#chart"),
                options
            );
            var chart1 = new ApexCharts(
                document.querySelector("#chart1"),
                options1
            );
            var chart2 = new ApexCharts(
                document.querySelector("#chart2"),
                options2
            );
            chart.render();
            chart1.render();

            var url = '<?php echo e(route('API_usercash',['locale'=> app()->getLocale()])); ?>';
            $.getJSON(url, function(response) {
                chart.updateSeries([{
                    name: 'Balance',
                    data: response
                }])
            });

            var url3 = '<?php echo e(route('API_shareevolutiondate',['locale'=> app()->getLocale()])); ?>';
            $.getJSON(url3, function(response) {

                var series1 = {
                    name: 'Sales-bar',
                    type: 'bar',

                    data: response

                };

                var series2 = {
                    name: 'sales-line',
                    type: 'line',
                    data: response
                };
                //console.log(response1[0]);
                //console.log(response2[0]);
                // Update the chart with both series
                chart2.updateSeries([series1, series2]);




            });

            $(document).on("click", "#date", function () {
            var url3 = '<?php echo e(route('API_shareevolutiondate',['locale'=> app()->getLocale()])); ?>';
                $.getJSON(url3, function(response) {

                    var series1 = {
                        name: 'Sales-bar',
                        type: 'bar',

                        data: response

                    };

                    var series2 = {
                        name: 'sales-line',
                        type: 'line',
                        data: response
                    };
                    //console.log(response1[0]);
                    //console.log(response2[0]);
                    // Update the chart with both series
                    chart2.updateSeries([series1, series2]);




                });
            });
            $(document).on("click", "#week", function () {
                var url3 = '<?php echo e(route('API_shareevolutionweek',['locale'=> app()->getLocale()])); ?>';
                $.getJSON(url3, function(response) {

                    var series1 = {
                        name: 'Sales-bar',
                        type: 'bar',

                        data: response

                    };

                    var series2 = {
                        name: 'sales-line',
                        type: 'line',
                        data: response
                    };
                    //console.log(response1[0]);
                    //console.log(response2[0]);
                    // Update the chart with both series
                    chart2.updateSeries([series1, series2]);




                });
            });
            $(document).on("click", "#month", function () {
                var url3 = '<?php echo e(route('API_shareevolutionmonth',['locale'=> app()->getLocale()])); ?>';
                $.getJSON(url3, function(response) {

                    var series1 = {
                        name: 'Sales-bar',
                        type: 'bar',

                        data: response

                    };

                    var series2 = {
                        name: 'sales-line',
                        type: 'line',
                        data: response
                    };
                    //console.log(response1[0]);
                    //console.log(response2[0]);
                    // Update the chart with both series
                    chart2.updateSeries([series1, series2]);




                });
            });
            $(document).on("click", "#day", function () {
                var url3 = '<?php echo e(route('API_shareevolutionday',['locale'=> app()->getLocale()])); ?>';
                $.getJSON(url3, function(response) {

                    var series1 = {
                        name: 'Sales-bar',
                        type: 'bar',

                        data: response

                    };

                    var series2 = {
                        name: 'sales-line',
                        type: 'line',
                        data: response
                    };
                    //console.log(response1[0]);
                    //console.log(response2[0]);
                    // Update the chart with both series
                    chart2.updateSeries([series1, series2]);




                });
            });
            chart2.render();
            var url1 = '<?php echo e(route('API_shareevolution',['locale'=> app()->getLocale()])); ?>';
            var url2 = '<?php echo e(route('API_actionvalues',['locale'=> app()->getLocale()])); ?>';

            $.when(
                $.getJSON(url1),
                $.getJSON(url2)
            ).then(function(response1, response2) {
                var series1 = {
                    name: 'Sales',
                    type: 'area',
                    data: response1[0]
                };

                var series2 = {
                    name: 'Function',
                    type: 'line',
                    data: response2[0]
                };
                //console.log(response1[0]);
                //console.log(response2[0]);
                // Update the chart with both series
                chart1.updateSeries([series1, series2]);
            });
        </script>

        <div class="d-flex align-items-center mb-3">
            <div class="flex-grow-1">
                <h5 class="mb-0">Watchlist</h5>
            </div>
            <div class="flexshrink-0">
                <button class="btn btn-success btn-sm"><i class="ri-star-line align-bottom"></i> Add Watchlist</button>
            </div>
        </div>

            <div class="row">


                <div class="col-xl-4 col-md-6" ><div class="card card-animate">
                        <div class="card-body">
                            <div class="float-end">
                                <div class="dropdown">
                                    <a class="text-reset" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="text-muted fs-18"><i class="mdi mdi-dots-horizontal"></i></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#">View Details</a>
                                        <a class="dropdown-item" href="#">Remove Watchlist</a>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <img src="<?php echo e(URL::asset('assets/images/svg/crypto-icons/ltc.svg')); ?>" class="bg-light rounded-circle p-1 avatar-xs img-fluid" alt="">
                                <h6 class="ms-2 mb-0 fs-14">Sold Shares</h6>
                            </div>
                            <div class="row align-items-end g-0">
                                <div class="col-6">
                                    <h5 class="mb-1 mt-4"><?php echo e(number_format(getSelledActions(),0)); ?></h5>
                                    <p class="text-danger fw-medium mb-0"><span class="text-muted ms-2 fs-12"></span></p>
                                </div><!-- end col -->
                                <div class="col-6">
                                    <div class="apex-charts crypto-widget" data-colors='["--vz-danger", "--vz-transparent"]' id="litecoin_sparkline_charts" dir="ltr"></div>
                                </div><!-- end col -->
                            </div><!-- end row -->
                        </div><!-- end card body -->
                    </div></div><!-- end card -->
                <div class="col-xl-4 col-md-6" ><div class="card card-animate">
                        <div class="card-body">
                            <div class="float-end">
                                <div class="dropdown">
                                    <a class="text-reset" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="text-muted fs-18"><i class="mdi mdi-dots-horizontal"></i></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#">View Details</a>
                                        <a class="dropdown-item" href="#">Remove Watchlist</a>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <img src="<?php echo e(URL::asset('assets/images/svg/crypto-icons/eth.svg')); ?>" class="bg-light rounded-circle p-1 avatar-xs img-fluid" alt="">
                                <h6 class="ms-2 mb-0 fs-14">Gifted Shares</h6>
                            </div>
                            <div class="row align-items-end g-0">
                                <div class="col-6">
                                    <h5 class="mb-1 mt-4"><?php echo e(number_format(getGiftedShares(),0)); ?></h5>
                                    <p class="text-danger fw-medium mb-0"><span class="text-muted ms-2 fs-12"></span></p>
                                </div><!-- end col -->
                                <div class="col-6">
                                    <div class="apex-charts crypto-widget" data-colors='["--vz-danger", "--vz-transparent"]' id="eathereum_sparkline_charts" dir="ltr"></div>
                                </div><!-- end col -->
                            </div><!-- end row -->
                        </div><!-- end card body -->
                    </div></div><!-- end card -->
                <div class="col-xl-4 col-md-6" ><div class="card card-animate">
                        <div class="card-body">
                            <div class="float-end">
                                <div class="dropdown">
                                    <a class="text-reset" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="text-muted fs-18"><i class="mdi mdi-dots-horizontal"></i></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#">View Details</a>
                                        <a class="dropdown-item" href="#">Remove Watchlist</a>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <img src="<?php echo e(URL::asset('assets/images/svg/crypto-icons/xmr.svg')); ?>" class="bg-light rounded-circle p-1 avatar-xs img-fluid" alt="">
                                <h6 class="ms-2 mb-0 fs-14">Gifted/Sold Shares</h6>
                            </div>
                            <div class="row align-items-end g-0">
                                <div class="col-6">
                                    <h5 class="mb-1 mt-4"><?php echo e(number_format(getGiftedShares()/getSelledActions()*100,2)); ?>%</h5>
                                    <p class="text-danger fw-medium mb-0"><span class="text-muted ms-2 fs-12"></span></p>
                                </div><!-- end col -->
                                <div class="col-6">
                                    <div class="apex-charts crypto-widget" data-colors='["--vz-danger", "--vz-transparent"]' id="binance_sparkline_charts" dir="ltr"></div>
                                </div><!-- end col -->
                            </div><!-- end row -->
                        </div><!-- end card body -->
                    </div></div><!-- end card -->
                <div class="col-xl-4 col-md-6" ><div class="card card-animate">
                        <div class="card-body">
                            <div class="float-end">
                                <div class="dropdown">
                                    <a class="text-reset" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="text-muted fs-18"><i class="mdi mdi-dots-horizontal"></i></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#">View Details</a>
                                        <a class="dropdown-item" href="#">Remove Watchlist</a>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <img src="<?php echo e(URL::asset('assets/images/svg/crypto-icons/btc.svg')); ?>" class="bg-light rounded-circle p-1 avatar-xs img-fluid" alt="">
                                <h6 class="ms-2 mb-0 fs-14">Shares actual price</h6>
                            </div>
                            <div class="row align-items-end g-0">
                                <div class="col-6">
                                    <h5 class="mb-1 mt-4"><?php $val =number_format(actualActionValue(getSelledActions()), 2)  ?>
                                        <?php if(1>0): ?>
                                            <?php echo e($val); ?>$
                                        <?php endif; ?></h5>
                                    <p class="text-success fw-medium mb-0"><span class="text-muted ms-2 fs-12"></span></p>
                                </div><!-- end col -->
                                <div class="col-6">
                                    <div class="apex-charts crypto-widget" data-colors='["--vz-success" , "--vz-transparent"]' id="bitcoin_sparkline_charts" dir="ltr"></div>
                                </div><!-- end col -->
                            </div><!-- end row -->
                        </div><!-- end card body -->
                    </div></div><!-- end card -->
                <div class="col-xl-4 col-md-6" ><div class="card card-animate">
                        <div class="card-body">
                            <div class="float-end">
                                <div class="dropdown">
                                    <a class="text-reset" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="text-muted fs-18"><i class="mdi mdi-dots-horizontal"></i></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#">View Details</a>
                                        <a class="dropdown-item" href="#">Remove Watchlist</a>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <img src="<?php echo e(URL::asset('assets/images/svg/crypto-icons/xmr.svg')); ?>" class="bg-light rounded-circle p-1 avatar-xs img-fluid" alt="">
                                <h6 class="ms-2 mb-0 fs-14">Revenue</h6>
                            </div>
                            <div class="row align-items-end g-0">
                                <div class="col-6">
                                    <h5 class="mb-1 mt-4">$<?php echo e(number_format(getRevenuShares(),2)); ?></h5>
                                    <p class="text-danger fw-medium mb-0"><span class="text-muted ms-2 fs-12"></span></p>
                                </div><!-- end col -->
                                <div class="col-6">
                                    <div class="apex-charts crypto-widget" data-colors='["--vz-danger", "--vz-transparent"]' id="binance_sparkline_charts" dir="ltr"></div>
                                </div><!-- end col -->
                            </div><!-- end row -->
                        </div><!-- end card body -->
                    </div></div><!-- end card -->
                <div class="col-xl-4 col-md-6" ><div class="card card-animate">
                        <div class="card-body">
                            <div class="float-end">
                                <div class="dropdown">
                                    <a class="text-reset" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="text-muted fs-18"><i class="mdi mdi-dots-horizontal"></i></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#">View Details</a>
                                        <a class="dropdown-item" href="#">Remove Watchlist</a>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <img src="<?php echo e(URL::asset('assets/images/svg/crypto-icons/xmr.svg')); ?>" class="bg-light rounded-circle p-1 avatar-xs img-fluid" alt="">
                                <h6 class="ms-2 mb-0 fs-14">Transfer Made</h6>
                            </div>
                            <div class="row align-items-end g-0">
                                <div class="col-6">
                                    <h5 class="mb-1 mt-4" id="realrev">$<?php echo e(number_format(getRevenuSharesReal(),2)); ?></h5>
                                    <p class="text-danger fw-medium mb-0"><span class="text-muted ms-2 fs-12"></span></p>
                                </div><!-- end col -->
                                <div class="col-6">
                                    <div class="apex-charts crypto-widget" data-colors='["--vz-danger", "--vz-transparent"]' id="binance_sparkline_charts" dir="ltr"></div>
                                </div><!-- end col -->
                            </div><!-- end row -->
                        </div><!-- end card body -->
                    </div></div><!-- end card -->


            </div>

        <div class="card" id="marketList">
            <div class="card-header border-bottom-dashed d-flex align-items-center">
                <h4 class="card-title mb-0 flex-grow-1">Market Status</h4>
                <div class="flex-shrink-0">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-primary btn-sm">Today</button>
                        <button type="button" class="btn btn-outline-primary btn-sm">Overall</button>
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive">
                        <table id="shares-sold" class="table nowrap dt-responsive align-middle table-hover table-bordered" style="width:100%">
                            <thead  class="table-light">
                            <tr class="head2earn  tabHeader2earn" >
                                <th style=" border: none ;text-align: center;"><?php echo e(__('date_purchase')); ?></th>
                                <th style=" border: none;"><?php echo e(__('countrie')); ?></th>
                                <th style=" border: none;"><?php echo e(__('mobile')); ?></th>
                                <th style=" border: none;"><?php echo e(__('Name')); ?></th>
                                <th style=" border: none;"><?php echo e(__('total_shares')); ?></th>

                                <th style=" border: none;"><?php echo e(__('sell_price_now')); ?></th>
                                <th style=" border: none;"><?php echo e(__('gains')); ?></th>
                                <th style=" border: none;"><?php echo e(__('Real_Sold')); ?></th>
                                <th style=" border: none;"><?php echo e(__('Real_Sold_amount')); ?></th>
                                <th style=" border: none;text-align: center; "><?php echo e(__('total_price')); ?></th>
                                <th style=" border: none;"><?php echo e(__('number_of_shares')); ?></th>
                                <th style=" border: none;"><?php echo e(__('gifted_shares')); ?></th>
                                <th style=" border: none ;text-align: center; "><?php echo e(__('average_price')); ?></th>
                                <th style=" border: none;text-align: center; "><?php echo e(__('share_price')); ?></th>
                                <th style=" border: none ;text-align: center;"><?php echo e(__('heure_purchase')); ?></th>




                            </tr>
                            </thead>
                            <tfoot>
                            </tfoot>
                            <tbody class="body2earn">
                            </tbody>

                        </table>
                    </div>
        </div><!--end card-->
        <div class="modal fade" id="realsoldmodif" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" ><?php echo e(__('Transfert Cash')); ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="javascript:void(0);">
                            <div class="row g-3">
                                <div class="col-xxl-6">

                                    <!-- Basic example -->
                                    <div class="input-group">
                                        <span class="input-group-text" ><img  id="realsold-country" alt="" class="avatar-xxs me-2"></span>
                                        <input type="text" class="form-control"  disabled id="realsold-phone" aria-describedby="basic-addon1">
                                    </div>

                                </div><!--end col-->


                                <div class="col-xxl-6">
                                    <div class="input-group">
                                        <input  id ="realsold-reciver" type="hidden">
                                        <input type="number" class="form-control" id="realsold-ammount">
                                        <input hidden type="number" class="form-control" id="realsold-ammount-total">
                                        <span class="input-group-text">$</span>

                                    </div>
                                </div><!--end col-->
                                <div class="col-lg-12">
                                    <div class="hstack gap-2 justify-content-end">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
                                        <button type="button"   id="realsold-submit" class="btn btn-primary"><?php echo e(__('Submit')); ?></button>
                                    </div>
                                </div><!--end col-->
                            </div><!--end row-->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div><!--end col-->

    <div class="col-xxl-3">
        <div class="card">
            <div class="card-body bg-soft-warning">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <h5 class="fs-14 mb-3">My Portfolio</h5>
                        <h2>$<?php echo $solde->soldeCB /1 ?><small class="text-muted fs-14"></small></h2>
                        <p class="text-muted mb-0"> <small class="badge badge-soft-success"><i class="ri-arrow-right-up-line fs-13 align-bottom"></i></small></p>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="mdi mdi-wallet-outline text-primary h1"></i>
                    </div>
                </div>
            </div>
        </div><!--end card-->
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <h5 class="fs-14 mb-3">Today's Cash Transfert</h5>
                        <h2>$<?php echo $vente_jour /1 ?><small class="text-muted fs-14"></small></h2>
                        <p class="text-muted mb-0"> <small class="badge badge-soft-success"><i class="ri-arrow-right-up-line fs-13 align-bottom"></i></small></p>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="ri-hand-coin-line text-primary h1"></i>
                    </div>
                </div>
            </div>
        </div><!--end card-->
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <h5 class="fs-14 mb-3">Overall Cash Transfert</h5>
                        <h2>$<?php echo $vente_total /1 ?><small class="text-muted fs-14"></small></h2>
                        <p class="text-muted mb-0"> <small class="badge badge-soft-success"><i class="ri-arrow-right-up-line fs-13 align-bottom"></i></small></p>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="ri-line-chart-line text-primary h1"></i>
                    </div>
                </div>
            </div>
        </div><!--end card-->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Recent Transaction</h5>
            </div>
            <div class="card-body table-responsive">
                        <table id="transfert" class="table nowrap dt-responsive align-middle table-hover table-bordered" style="width:100%">
                            <tbody class="body2earn">
                            </tbody>

                        </table>
                    </div>
        </div>
        </div><!--end card-->
    </div><!--end col-->
</div><!--end row-->

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script src="<?php echo e(URL::asset('/assets/libs/list.js/list.js.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('/assets/libs/list.pagination.js/list.pagination.js.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('assets/libs/swiper/swiper.min.js')); ?>"></script>



<script src="<?php echo e(URL::asset('/assets/js/app.min.js')); ?>"></script>

<?php $__env->stopSection(); ?>


            <!-- Grids in modals -->


        <!--end row-->

</div>
<?php /**PATH C:\wamp64\www\2earn\resources\views/livewire/shares-sold.blade.php ENDPATH**/ ?>