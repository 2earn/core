<!doctype html >
<html dir="{{config('app.available_locales')[app()->getLocale()]['direction']}}"
      lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="vertical" data-topbar="light"
      data-sidebar="light" data-sidebar-size="sm-hover-active" data-sidebar-image="none" data-preloader="disable"
      id="HTMLMain" data-layout-mode="light"
>
<head>
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.ga4.measurementId') }}"></script>
    <script>

        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        dataLayer.push({
            'user_id': '{{Auth()->user()->idUser}}',
            'phone_number': '{{Auth()->user()->fullphone_number}}'
        });
        gtag('js', new Date());
        gtag('config', '{{ config('services.ga4.measurementId') }}');

    </script>
    <!-- Google Tag Manager -->
    <script>(function (w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start':
                    new Date().getTime(), event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-PMK39HQQ');</script>
    <!-- End Google Tag Manager -->
    <script>

    </script>
    <style>
        .partiel {
            color: #fa896b;
        }

        .anychart-credits {
            display: none;
        }

        #any1, #any2, #any3 {
            width: 100%;
            height: 600px;;
            margin: 0;
            padding: 0;
        }

        #any4, #any5 {
            width: 100%;
            height: 100%;
            min-height: 80%;
            max-height: 95%;;
            margin: 0;
            padding: 0;
        }

        .install-app-btn-container {
            display: none;
        }

        .hide {
            display: none;
        }
    </style>
    <meta charset="utf-8"/>
    <title>@yield('title')| 2Earn.cash</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="2earn.cash" name="description"/>
    <meta content="" name="author"/>
    <meta property="og:image" content="{{ URL::asset('assets/images/2Earn.png') }}">
    <meta property="twitter:image" content="{{ URL::asset('assets/images/2Earn.png') }}">


    <script src="https://cdn.anychart.com/releases/v8/js/anychart-base.min.js"></script>
    <script src="https://cdn.anychart.com/releases/v8/js/anychart-ui.min.js"></script>
    <script src="https://cdn.anychart.com/releases/v8/js/anychart-exports.min.js"></script>
    <script src="https://cdn.anychart.com/releases/v8/js/anychart-data-adapter.min.js"></script>
    <link href="https://cdn.anychart.com/releases/v8/css/anychart-ui.min.css" type="text/css" rel="stylesheet">
    <link href="https://cdn.anychart.com/releases/v8/fonts/css/anychart-font.min.css" type="text/css" rel="stylesheet">
    <script src="https://cdn.anychart.com/geodata/latest/custom/world/world.js"></script>
    <script src="https://cdn.anychart.com/releases/v8/js/anychart-circular-gauge.min.js"></script>
    <script src="https://cdn.anychart.com/releases/v8/js/anychart-map.min.js"></script>
    <script src="https://cdn.anychart.com/releases/v8/js/anychart-table.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.3.15/proj4.js"></script>
    <script src="https://cdn.anychart.com/releases/v8/js/anychart-tag-cloud.min.js"></script>
    <script src="https://cdn.anychart.com/releases/v8/js/anychart-sankey.min.js"></script>


    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('assets/images/favicon.ico')}}">
    {{--    <link rel="stylesheet" href="{{asset('assets/fontawesome/all.min.css'}}">--}}
    <link rel="stylesheet" href="{{asset('assets/Styles/intlTelInput.css')}}">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="{{asset('assets/js/intlTelInput.js')}}"></script>
    @include('layouts.head-css')
    <link href="{{ URL::asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::asset('assets/libs/filepond/filepond.min.css') }}" type="text/css"/>
    <link rel="stylesheet"
          href="{{ URL::asset('assets/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.css') }}">
    <link
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap"
        rel="stylesheet"
    />

    <script src="{{ mix('js/turbo.js') }}" defer></script>
    <link rel="stylesheet" href="{{asset('assets/fontawesome/all.min.css')}}">
    <script src="https://kit.fontawesome.com/0c5b3847de.js" crossorigin="anonymous"
            data-mutate-approach="sync"></script>
    <script src="{{asset('assets/fontawesome/all.min.js')}}"></script>
    @include('layouts.vendor-scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.0.0/js/dataTables.responsive.min.js "></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

    <script src="{{ URL::asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css"/>
    <!--datatable responsive css-->
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" rel="stylesheet"
          type="text/css"/>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        @import url({{asset('/')."assets/icons/material-design-iconic-font/css/materialdesignicons.min.css"}});
        @import url({{asset('assets/icons/line-awesome/css/line-awesome.min.css')}});
        @import url({{asset('assets/icons/font-awesome/css/font-awesome.min.css')}});

        .page-title-box-db {
            background-color: #009fe3 !important;;
        }

        .page-title-box-bfs {
            background-color: #bc34b6 !important;
        }

        @font-face {

            font-family: 'iconearn';
            src: url({{ asset('assets/fonts/iconearn.eot?uerpdx')}});
            src: url({{ asset('assets/fonts/iconearn.eot?uerpdx#iefix')}}) format('embedded-opentype'),
            url({{ asset('assets/fonts/iconearn.ttf?uerpdx')}}) format('truetype'),
            url({{ asset('assets/fonts/iconearn.woff?uerpdx')}}) format('woff'),
            url({{ asset('assets/fonts/iconearn.svg?uerpdx#iconearn')}}) format('svg');
            font-weight: normal ;
            font-style: normal ;
            font-display: block ;
        }

        @font-face {
            font-family: 'shopearn' ;
            src: url({{ asset('assets/fonts/shopearn.eot?jeosj9')}});
            src: url({{ asset('assets/fonts/shopearn.eot?jeosj9#iefix')}}) format('embedded-opentype'),
            url({{ asset('assets/fonts/shopearn.ttf?jeosj9')}}) format('truetype'),
            url({{ asset('assets/fonts/shopearn.woff?jeosj9')}}) format('woff'),
            url({{ asset('assets/fonts/shopearn.svg?jeosj9#shopearn')}}) format('svg');
            font-weight: normal;
            font-style: normal;
            font-display: block;
        }
    </style>
    @livewireStyles
    <meta name="turbolinks-cache-control" content="no-cache">
    <meta name="theme-color" content="#6777ef"/>
    <link rel="apple-touch-icon" href="{{ asset('logo.PNG') }}">
    <link rel="manifest" href="{{ asset('/manifest.json') }}">
    @laravelPWA
</head>

<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet"
      type="text/css"/>
<link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet"
      type="text/css"/>
<script>
    var fromLogin = '{{Session::has('fromLogin')}}';

    if (fromLogin) {
        location.reload();
    }
</script>
@section('body')
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PMK39HQQ"
                height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>
    @include('layouts.body')
    <script src="{{ asset('/sw.js') }}"></script>

@show

@if(app()->getLocale() == 'ar')
    <style>

        @font-face {
            font-family: ar400;
            src: url("{{asset('assets/NotoKufiArabic-Regular.ttf')}}");
            font-weight: 400;
        }

        label, h1, h2, h3, h4, h5, h6, a, button, p, i, span, strong, .btn, div {
            font-family: ar400;
            font-weight: 500 !important;
        }

        .navbar-menu .navbar-nav .nav-link {
            font-family: ar400 !important;

        }
    </style>
@endif
<div id="layout-wrapper">
    <livewire:top-bar :currentRoute="Route::currentRouteName()"/>
    @include('layouts.sidebar')
    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                @yield('content')
            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->
    </div>
    <!-- end main content-->
</div>
@include('layouts.footer', ['pageName' => 'master'])
<!-- END layout-wrapper -->

{{--@include('layouts.customizer')--}}

<!-- JAVASCRIPT -->


@livewireScripts


<script src="https://cdn.jsdelivr.net/gh/livewire/turbolinks@v0.1.x/dist/livewire-turbolinks.js"
        data-turbolinks-eval="false" data-turbo-eval="false"></script>

<script src="{{ mix('js/turbo.js') }}" defer></script>
<script src="{{ URL::asset('/assets/libs/dropzone/dropzone.min.js') }}"></script>
<script src="{{ URL::asset('/assets/js/pages/crypto-kyc.init.js') }}"></script>
{{--<script src="{{ URL::asset('assets/js/pages/team.init.js') }}"></script>--}}
<script src="{{ URL::asset('assets/libs/filepond/filepond.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.js') }}">
</script>
<script
    src="{{ URL::asset('assets/libs/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js') }}">
</script>
<script
    src="{{ URL::asset('assets/libs/filepond-plugin-image-exif-orientation/filepond-plugin-image-exif-orientation.min.js') }}">
</script>
<script src="{{ URL::asset('assets/libs/filepond-plugin-file-encode/filepond-plugin-file-encode.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

{{--<script src="{{ URL::asset('assets/js/pages/form-file-upload.init.js') }}"></script>--}}
<script src="{{ URL::asset('/assets/js/app.min.js') }}" defer></script>
<script src="{{ URL::asset('assets/js/pages/datatables.init.js') }}"></script>

{{--<script src="{{ URL::asset('assets/libs/nouislider/nouislider.min.js') }}"></script>--}}
{{--<script src="{{ URL::asset('assets/libs/wnumb/wnumb.min.js') }}"></script>--}}
{{--<script src="{{ URL::asset('assets/js/pages/range-sliders.init.js') }}"></script>--}}
<script>
    anychart.onDocumentReady(function () {
        anychart.licenseKey('2earn.cash-953c5a55-712f04c3');
    });
    $(document).on('ready turbolinks:load', function () {
        var classAl = "text-end";
        var tts = '{{config('app.available_locales')[app()->getLocale()]['direction']}}';
        if (tts == 'rtl') {
            classAl = "text-start";
        }
        var lan = "{{config('app.available_locales')[app()->getLocale()]['tabLang']}}";
        var urlLang = "//cdn.datatables.net/plug-ins/1.12.1/i18n/" + lan + ".json";
        var url = '';


        $('#HistoryNotificationTable').DataTable(
            {
                retrieve: true,
                "colReorder": true,
                "orderCellsTop": true,
                "fixedHeader": true,
                initComplete: function () {
                    // Apply the search
                    this.api()
                        .columns()
                        .every(function () {
                            // if( !that.settings()[0].aoColumns[colIdx].bSearchable ){
                            //     that.column( colIdx ).header().innerHTML=table.column( colIdx ).footer().innerHTML;
                            // }
                            var that = $('#HistoryNotificationTable').DataTable();
                            $('input', this.footer()).on('keydown', function (ev) {
                                if (ev.keyCode == 13) {//only on enter keypress (code 13)
                                    that
                                        .search(this.value)
                                        .draw();
                                }
                            });
                        });
                },
                "processing": true,
                search: {
                    return: true
                },
                "ajax": "{{route('API_HistoryNotification',app()->getLocale())}}",
                "columns": [
                    {data: 'reference'},
                    {data: 'send'},
                    {data: 'receiver'},
                    {data: 'action'},
                    {data: 'date'},
                    {data: 'type'},
                    {data: 'responce'},
                    // {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                "language": {
                    "url": urlLang
                }
            }
        );
        $('#userManager_table').DataTable(
            {
                retrieve: true,
                "colReorder": true,
                "orderCellsTop": true,
                "fixedHeader": true,
                initComplete: function () {
                    // Apply the search
                    this.api()
                        .columns()
                        .every(function () {
                            // if( !that.settings()[0].aoColumns[colIdx].bSearchable ){
                            //     that.column( colIdx ).header().innerHTML=table.column( colIdx ).footer().innerHTML;
                            // }
                            var that = $('#userManager_table').DataTable();
                            $('input', this.footer()).on('keydown', function (ev) {
                                if (ev.keyCode == 13) {//only on enter keypress (code 13)
                                    that
                                        .search(this.value)
                                        .draw();
                                }
                            });
                        });
                },
                "processing": true,
                search: {
                    return: true
                },
                "ajax": "{{route('API_usermanager',app()->getLocale())}}",
                "columns": [
                    {data: 'N'},
                    {data: 'idUser'},
                    {data: 'status'},
                    {data: 'registred_from'},
                    {data: 'fullphone_number'},
                    {data: 'LatinName'},
                    {data: 'ArabicName'},
                    {data: 'lastOperation'},
                    {data: 'country'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                "language": {
                    "url": urlLang
                }
            }
        );

        $('#contacts_table').DataTable(
            {
                retrieve: true,
                searching: true,
                "bLengthChange": false,
                "processing": true,
                paging: true,
                "pageLength": 100,
                "aLengthMenu": [[100, 500, 1000], [100, 500, 1000]],
                "ajax": "{{route('API_UserContacts',app()->getLocale())}}",
                "columns": [
                    {"data": "name"},
                    {"data": "lastName"},
                    {"data": "mobile"},
                    {"data": "flag"},
                    {"data": "status"},
                    {"data": "availablity"},

                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                "columnDefs":
                    [
                        {
                            "targets": [5],
                            render: function (data, type, row) {
                                var givenDate = new Date(row.reserved_at);
                                var delai = (Date.now() - givenDate) / (1000 * 60 * 60);
                                if (Number(row.idUpline) !== 0) {
                                    if (row.idUpline == row.idUser)
                                        return '<span class="badge bg-info-subtle text-info" data-id="' + row.id + '" data-phone="' + row.mobile +
                                            '">{{__('i am his sponsor')}}</span>';
                                    else
                                        return '<span class="badge bg-danger-subtle text-danger" data-id="' + row.id + '" data-phone="' + row.mobile +
                                            '">{{__('Already has a sponsor')}}</span>';
                                } else {
                                    if (Number(row.availablity) === 0)
                                        return '<span class="badge bg-success-subtle text-success" data-id="' + row.id + '" data-phone="' + row.mobile +
                                            '">{{__('Available')}}</span>';
                                    else {
                                        if (row.reserved_by == row.idUser) {
                                            if (delai < 72) {
                                                var reste = 72 - delai;
                                                return '<span class="badge bg-warning-subtle text-warning" data-id="' + row.id + '" data-phone="' + row.mobile +
                                                    '">{{__('reserved for')}} ' + reste.toFixed(0) + ' {{__('hours')}}</span>';
                                            } else {
                                                var reste = 72 + 168 - delai;
                                                return '<span class="badge bg-primary-subtle text-primary" data-id="' + row.id + '" data-phone="' + row.mobile +
                                                    '">{{__('blocked for')}} ' + reste.toFixed(0) + ' {{__('hours')}}</span>';
                                            }

                                        } else {
                                            if (delai < 72) {
                                                var reste = 72 - delai;
                                                return '<span class="badge bg-warning-subtle text-warning" data-id="' + row.id + '" data-phone="' + row.mobile +
                                                    '">{{__('reserved by other user for')}} ' + reste.toFixed(0) + ' {{__('hours')}}</span>';
                                            } else {
                                                return '<span class="badge bg-success-subtle text-success" data-id="' + row.id + '" data-phone="' + row.mobile +
                                                    '">{{__('Available')}}</span>';
                                            }
                                        }
                                    }
                                }
                            },
                        },
                    ],
                "language": {
                    "url": urlLang
                }
            }
        );


        $('#userPurchase_table').DataTable(
            {
                "ordering": false,
                retrieve: true,
                "colReorder": false,

                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                "orderCellsTop": true,
                "fixedHeader": true,
                initComplete: function () {
                    // Apply the search
                    this.api()
                        .columns()
                        .every(function () {
                            // if( !that.settings()[0].aoColumns[colIdx].bSearchable ){
                            //     that.column( colIdx ).header().innerHTML=table.column( colIdx ).footer().innerHTML;
                            // }
                            if ($.fn.dataTable.isDataTable('#countries_table')) {

                                var that = $('#userPurchase_table').DataTable();
                            }
                            $('input', this.footer()).on('keydown', function (ev) {
                                if (ev.keyCode == 13) {//only on enter keypress (code 13)
                                    that

                                        .search(this.value)
                                        .draw();
                                }
                            });
                        });
                },
                "order": [[0, 'desc']],
                "processing": true,
                "serverSide": true,
                "aLengthMenu": [[10, 30, 50], [10, 30, 50]],
                search: {
                    return: true
                },
                autoWidth: false,
                bAutoWidth: false,
                "ajax": "{{route('API_userPurchase', app()->getLocale() )}}",
                "columns": [
                    {data: 'DateAchat'},
                    {data: 'ReferenceAchat'},
                    {data: 'item_title'},
                    {data: 'nbrAchat'},
                    {data: 'Amout'},
                    {data: 'invitationPurshase'},
                    {data: 'visit'},
                    {data: 'PRC_BFS'},
                    {data: 'PRC_CB'},
                    {data: 'CashBack_BFS'},
                    {data: 'CashBack_CB'},
                    {data: 'Economy'}


                    // {data: 'action', name: 'action', orderable: false, searchable: false},
                ],

                "language": {
                    "url": urlLang
                }

            }
        );
        $('#countries_table').DataTable(
            {
                retrieve: true,
                // "colReorder": true,
                // dom: 'Bfstrip',
                // buttons: [
                //     'csv', 'excel'
                // ],
                // "orderCellsTop": true,
                // "fixedHeader": true,
                initComplete: function () {
                    // Apply the search
                    this.api()
                        .columns()
                        .every(function () {
                            // if( !that.settings()[0].aoColumns[colIdx].bSearchable ){
                            //     that.column( colIdx ).header().innerHTML=table.column( colIdx ).footer().innerHTML;
                            // }
                            if ($.fn.dataTable.isDataTable('#countries_table')) {

                                var that = $('#countries_table').DataTable();
                            }
                            $('input', this.footer()).on('keydown', function (ev) {
                                if (ev.keyCode == 13) {//only on enter keypress (code 13)
                                    that

                                        .search(this.value)
                                        .draw();
                                }
                            });
                        });
                },
                // "processing": true,
                // "serverSide": true,
                // "aLengthMenu": [[10, 30, 50], [10, 30, 50]],
                // search: {
                //     return: true
                // },
                "ajax": "{{route('API_countries',app()->getLocale())}}",
                "columns": [

                    {"data": "name"},
                    {"data": "phonecode"},
                    {"data": "langage"},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                "language": {
                    "url": " //cdn.datatables.net/plug-ins/1.12.1/i18n/" + lan + ".json"
                }

            }
        );

        $('#userPurchase_table').DataTable(
            {
                "ordering": false,
                retrieve: true,
                "colReorder": false,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                "orderCellsTop": true,
                "fixedHeader": true,
                initComplete: function () {
                    // Apply the search
                    this.api()
                        .columns()
                        .every(function () {
                            // if( !that.settings()[0].aoColumns[colIdx].bSearchable ){
                            //     that.column( colIdx ).header().innerHTML=table.column( colIdx ).footer().innerHTML;
                            // }
                            if ($.fn.dataTable.isDataTable('#countries_table')) {

                                var that = $('#userPurchase_table').DataTable();
                            }
                            $('input', this.footer()).on('keydown', function (ev) {
                                if (ev.keyCode == 13) {//only on enter keypress (code 13)
                                    that

                                        .search(this.value)
                                        .draw();
                                }
                            });
                        });
                },
                "order": [[0, 'desc']],
                "processing": true,
                "serverSide": true,
                "aLengthMenu": [[10, 30, 50], [10, 30, 50]],
                search: {
                    return: true
                },
                autoWidth: false,
                bAutoWidth: false,
                "ajax": "{{route('API_userPurchase', app()->getLocale() )}}",
                "columns": [
                    {data: 'DateAchat'},
                    {data: 'ReferenceAchat'},
                    {data: 'item_title'},
                    {data: 'nbrAchat'},
                    {data: 'Amout'},
                    {data: 'invitationPurshase'},
                    {data: 'visit'},
                    {data: 'PRC_BFS'},
                    {data: 'PRC_CB'},
                    {data: 'CashBack_BFS'},
                    {data: 'CashBack_CB'},
                    {data: 'Economy'}


                    // {data: 'action', name: 'action', orderable: false, searchable: false},
                ],

                "language": {
                    "url": urlLang
                }

            }
        );
        $('#users-list').DataTable(
            {
                "ordering": true,
                retrieve: true,
                "colReorder": false,
                "orderCellsTop": true,
                "fixedHeader": true,
                "order": [[0, 'desc']],
                "processing": true,
                "serverSide": false,
                "aLengthMenu": [[100, 500, 1000], [100, 500, 1000]],
                search: {return: true},
                autoWidth: false,
                bAutoWidth: false,
                "ajax": "{{route('API_UsersList',['locale'=> app()->getLocale()])}}",
                "columns": [
                    {data: 'formatted_created_at'},
                    {data: 'flag'},
                    {data: 'formatted_mobile'},
                    {data: 'name'},
                    {data: 'SoldeCB'},
                    {data: 'SoldeBFS'},
                    {data: 'SoldeDB'},
                    {data: 'SoldeSMS'},
                    {data: 'SoldeSH'},
                    {data: 'OptActivation'},
                    {data: 'pass'},
                    {data: 'register_upline'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                    {data: 'minshares'},
                    {data: 'periode'},
                    {data: 'date'},
                    {data: 'coeff'},
                    {data: 'note'},
                    {data: 'VIP', name: 'action', orderable: false, searchable: false},
                    {data: 'mobile'},
                ],
                "columnDefs": [{"targets": [19], searchable: true, visible: false},],
                "language": {"url": urlLang}
            }
        );

        $('#shares-solde').DataTable(
            {
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
                "ajax": "{{route('API_sharessolde',['locale'=> app()->getLocale()])}}",
                "columns": [
                    {data: 'formatted_created_at'},
                    {data: 'value_format'},
                    {data: 'gifted_shares'},
                    {data: 'total_shares'},
                    {data: 'total_price'},
                    {data: 'present_value'},
                    {data: 'current_earnings'},
                ],
                "language": {
                    "url": urlLang
                }
            }
        );

        $('#countie-tab').DataTable(
            {
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
                "ajax": "{{ route('API_stat_countries', ['locale'=> app()->getLocale()]) }}",
                "columns": [
                    {data: 'name'},
                    {data: 'COUNT_USERS'},
                    {data: 'CASH_BALANCE'},
                    {data: 'BFS'},
                    {data: 'DISCOUNT_BALANCE'},
                    {data: 'SMS_BALANCE'},
                    {data: 'SOLD_SHARES'},
                    {data: 'GIFTED_SHARES'},
                    {data: 'TOTAL_SHARES'},
                    {data: 'COUNT_TRAIDERS'},
                    {data: 'SHARES_REVENUE'},
                    {data: 'COUNT_REAL_TRAIDERS'},
                    {data: 'TRANSFERT_MADE'},
                ],
                "language": {
                    "url": urlLang
                }
            }
        );

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
                "ajax": "{{route('API_transfert',['locale'=> app()->getLocale()])}}",
                "columns": [
                    {data: 'value'},
                    {data: 'Description'},
                    {data: 'formatted_created_at'},
                ],
                "language": {
                    "url": urlLang
                }
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
                                        '" data-asset="' + row.asset + '" data-amount="' + row.total_price + '" >Transfert Made</span>';
                                if (Number(row.WinPurchaseAmount) === 0)
                                    return '<span class="badge bg-danger" data-id="' + row.id + '" data-phone="' + row.mobile +
                                        '" data-asset="' + row.asset + '" data-amount="' + row.total_price + '" >Free</span>';

                                if (Number(row.WinPurchaseAmount) === 2)
                                    return '<span class="badge bg-warning" data-id="' + row.id + '" data-phone="' + row.mobile +
                                        '" data-asset="' + row.asset + '" data-amount="' + row.total_price + '" >Mixed</span>';
                            },
                        },
                    ],
                "language": {
                    "url": urlLang
                }
            }
        );

        $(document).on('click', '.badge', function () {
            var id = $(this).data('id');
            var phone = $(this).data('phone');
            var amount = String($(this).data('amount')).replace(',', '');
            var asset = $(this).data('asset');
            $('#realsold-country').attr('src', asset);
            $('#realsold-reciver').attr('value', id);
            $('#realsold-phone').attr('value', phone);
            $('#realsold-ammount').attr('value', amount);
            $('#realsold-ammount-total').attr('value', amount);
            $('#realsoldmodif').modal('show');
            fetchAndUpdateCardContent();
            $('#shares-sold').DataTable().ajax.reload();
        });
        $(document).on("click", "#realsold-submit", function () {
            let reciver = $('#realsold-reciver').val();
            let ammount = $('#realsold-ammount').val();
            let total = $('#realsold-ammount-total').val()
            $.ajax({
                url: "{{ route('update-balance-real') }}",
                type: "POST",

                data: {
                    total: total,
                    amount: ammount,
                    id: reciver,
                    "_token": "{{ csrf_token() }}"
                },
                success: function (data) {
                    // console.log(data);
                    if (ammount !== 0) {

                    }
                    $('#realsoldmodif').modal('hide');
                    $('#shares-sold').DataTable().ajax.reload();
                    fetchAndUpdateCardContent();
                }

            });
        });

        function fetchAndUpdateCardContent() {
            // Make an AJAX request to get the updated content
            $.ajax({
                url: '{{ route('get-updated-card-content') }}', // Adjust the endpoint URL
                method: 'GET',
                success: function (data) {
                    $('#realrev').html('$' + data.value);
                },
                error: function (xhr, status, error) {
                    console.log(error)
                }
            });
        }

        $('#ub_table').DataTable(
            {
                ordering: true,
                retrieve: true,
                searching: false,
                "orderCellsTop": true,
                "fixedHeader": true,
                "order": [[1, 'desc']],
                "processing": true,
                "serverSide": true,
                "aLengthMenu": [[10, 30, 50], [10, 30, 50]],
                search: {return: false},
                "ajax": "{{route('API_UserBalances',['locale'=> app()->getLocale(), 'idAmounts'=>'cash-Balance'])}}",
                "columns": [
                    {data: 'Ref'},
                    {data: 'Date'},
                    {data: 'Designation'},
                    {data: 'Description'},
                    {data: 'value'},
                    {data: 'balance'},
                    {data: 'ranks'},
                    {data: 'idamount'},
                ],
                "columnDefs":
                    [
                        {
                            "targets": [4],
                            render: function (data, type, row) {
                                if (data.indexOf('+') == -1)
                                    return '<span class="badge bg-danger text-end">' + data + '</span>';
                                else
                                    return '<span class="badge bg-success text-end">' + data + '</span>';

                            },
                            className: classAl,
                        },
                        {
                            "targets": [5],
                            render: function (data, type, row) {

                                if (row.ranks == 1)
                                    if (row.idamount == 1)
                                        return '<div class="logoTopCashLabel"><h5 class="text-success fs-14 mb-0 ms-2">' + data + '</h5></div>';
                                    else
                                        return '<div class="logoTopDBLabel"><h5 class="text-success fs-14 mb-0 ms-2">' + data + '</h5></div>';
                                else
                                    return data;

                            }
                        },
                        {"targets": [6, 7], searchable: false, visible: false},
                        {"targets": [5], className: classAl},
                    ],
                "language": {
                    "url": urlLang
                }
            }
        );

        $('#userBalanceDB_table').DataTable(
            {
                "ordering": true,
                retrieve: true,
                "colReorder": false,
                "orderCellsTop": true,
                "fixedHeader": true,
                initComplete: function () {
                    this.api()
                        .columns()
                        .every(function () {
                            if ($.fn.dataTable.isDataTable('#countries_table')) {
                                var that = $('#userBalanceDB_table').DataTable();
                            }
                            $('input', this.footer()).on('keydown', function (ev) {
                                if (ev.keyCode == 13) {//only on enter keypress (code 13)
                                    that
                                        .search(this.value)
                                        .draw();
                                }
                            });
                        });
                },
                "order": [[1, 'desc']],
                "processing": true,
                "serverSide": true,
                "aLengthMenu": [[10, 30, 50], [10, 30, 50]],
                search: {
                    return: true
                },
                "ajax": "{{route('API_UserBalances',['locale'=> app()->getLocale(), 'idAmounts'=>'Discounts-Balance'])}}",
                "columns": [
                    {data: 'Ref'},
                    {data: 'Date'},
                    {data: 'Designation'},
                    {data: 'Description'},
                    {data: 'value', className: classAl},
                    {data: 'balance', className: classAl},
                    {data: 'ranks'},
                    {data: 'idamount'},
                ],
                "columnDefs":
                    [
                        {
                            "targets": [4],
                            render: function (data, type, row) {
                                if (data.indexOf('+') == -1)
                                    return '<span class="badge bg-danger text-end">' + data + '</span>';
                                else
                                    return '<span class="badge bg-success text-end">' + data + '</span>';

                            },
                            className: classAl,
                        },
                        {
                            "targets": [5],
                            render: function (data, type, row) {
                                if (row.ranks == 1)
                                    if (row.idamount == 1)
                                        return '<div class="logoTopCashLabel"><h5 class="text-success fs-14 mb-0 ms-2">' + data + '</h5></div>';
                                    else
                                        return '<div class="logoTopDBLabel"><h5 class="text-success fs-14 mb-0 ms-2">' + data + '</h5></div>';
                                else
                                    return data;
                            }
                        },
                        {
                            "targets": [6, 7],
                            searchable: false,
                            visible: false
                        },
                        {
                            "targets": [5],
                            className: classAl,
                        },

                    ],
                "language": {
                    "url": urlLang
                }
            }
        );


        // configuratiion page

        $('#SettingsTable').DataTable(
            {
                retrieve: true,
                "colReorder": false,
                "orderCellsTop": false,
                "fixedHeader": true,
                search: {
                    return: true
                },
                /*  initComplete: function () {
                      // Apply the search
                      this.api()
                          .columns()
                          .every(function () {
                              // if( !that.settings()[0].aoColumns[colIdx].bSearchable ){
                              //     that.column( colIdx ).header().innerHTML=table.column( colIdx ).footer().innerHTML;
                              // }
                              var that = $('#SettingsTable').DataTable();
                              $('#dataTables_filter input', this.footer()).on('keydown', function (ev) {
                                  // if (ev.keyCode == 13) {//only on enter keypress (code 13)
                                  that
                                      .search(this.value)
                                      .draw();
                                  // }
                              });
                          });
                  },*/
                "processing": true,
                "aLengthMenu": [[5, 30, 50], [5, 30, 50]],

                "ajax": "{{route('API_settings',app()->getLocale())}}",
                "columns": [
                    {"data": "ParameterName"},
                    {"data": "IntegerValue"},
                    {"data": "StringValue"},
                    {"data": "DecimalValue"},
                    {"data": "Unit"},
                    {"data": "Automatically_calculated"},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                "language": {
                    "url": urlLang
                }
            }
        );

        function saveHA() {
            // alert($('#tags').tagsinput('input'));
            // alert($('#tags').val());
            window.livewire.emit('saveHA', $("#tags").val());
        }

        $('#BalanceOperationsTable').DataTable(
            {
                retrieve: true,
                "colReorder": true,
                "orderCellsTop": true,
                "fixedHeader": true,
                initComplete: function () {
                    // Apply the search
                    this.api()
                        .columns()
                        .every(function () {
                            // if( !that.settings()[0].aoColumns[colIdx].bSearchable ){
                            //     that.column( colIdx ).header().innerHTML=table.column( colIdx ).footer().innerHTML;
                            // }
                            var that = $('#BalanceOperationsTable').DataTable();
                            $('input', this.footer()).on('keydown', function (ev) {
                                if (ev.keyCode == 13) {//only on enter keypress (code 13)
                                    that
                                        .search(this.value)
                                        .draw();
                                }
                            });
                        });
                },
                "processing": true,
                search: {
                    return: true
                },
                "ajax": "{{route('API_BalOperations' ,app()->getLocale())}}",
                "columns": [
                    {"data": "Designation"},
                    {"data": "IO"},
                    {"data": "idSource"},
                    // { "data": "Mode" },
                    {"data": "amountsshortname"},
                    // { "data": "Note"   },
                    {data: 'MODIFY_AMOUNT'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                "language": {
                    "url": urlLang
                }
            }
        );
        $('#amountsTable').DataTable(
            {
                retrieve: true,
                "colReorder": true,
                "orderCellsTop": true,
                "fixedHeader": true,
                initComplete: function () {
                    // Apply the search
                    this.api()
                        .columns()
                        .every(function () {
                            // if( !that.settings()[0].aoColumns[colIdx].bSearchable ){
                            //     that.column( colIdx ).header().innerHTML=table.column( colIdx ).footer().innerHTML;
                            // }
                            var that = $('#amountsTable').DataTable();
                            $('input', this.footer()).on('keydown', function (ev) {
                                if (ev.keyCode == 13) {//only on enter keypress (code 13)
                                    that
                                        .search(this.value)
                                        .draw();
                                }
                            });
                        });
                },
                "processing": true,
                search: {
                    return: true
                },
                "ajax": "{{route('API_Amounts',app()->getLocale())}}",
                "columns": [
                    {data: 'amountsname'},
                    {data: 'amountsshortname'},
                    {data: 'amountswithholding_tax'},
                    {data: 'amountstransfer'},
                    {data: 'amountspaymentrequest'},
                    {data: 'amountscash'},
                    {data: 'amountsactive'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                "language": {
                    "url": urlLang
                }
            }
        );


        $('#ActionHistorysTable').DataTable(
            {
                retrieve: true,
                "colReorder": true,
                "orderCellsTop": true,
                "fixedHeader": true,
                initComplete: function () {
                    // Apply the search
                    this.api()
                        .columns()
                        .every(function () {
                            // if( !that.settings()[0].aoColumns[colIdx].bSearchable ){
                            //     that.column( colIdx ).header().innerHTML=table.column( colIdx ).footer().innerHTML;
                            // }
                            if ($.fn.dataTable.isDataTable('#ActionHistorysTable')) {

                                var that = $('#ActionHistorysTable').DataTable();
                            }
                            $('input', this.footer()).on('keydown', function (ev) {
                                if (ev.keyCode == 13) {//only on enter keypress (code 13)
                                    that
                                        .search(this.value)
                                        .draw();
                                }
                            });
                        });
                },
                "processing": true,
                search: {
                    return: true
                },
                "ajax": "{{route('API_ActionHistory',app()->getLocale())}}",
                "columns": [
                    {data: 'title'},
                    {data: 'reponce'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                "language": {
                    "url": urlLang
                }
            }
        );

        select2_array = [];
        table_bfs = $('#ub_table_bfs').DataTable(
            {
                retrieve: true,
                "colReorder": true,
                "orderCellsTop": true,
                "fixedHeader": true,
                "order": [[2, 'desc']],
                initComplete: function () {
                    // Apply the search
                    this.api()
                        .columns()
                        .every(function () {
                            if ($.fn.dataTable.isDataTable('#ub_table_bfs')) {
                                var that = $('#ub_table_bfs').DataTable();
                            }
                            $('input', this.footer()).on('keydown', function (ev) {
                                if (ev.keyCode == 13) {//only on enter keypress (code 13)
                                    that
                                        .search(this.value)
                                        .draw();
                                }
                            });
                        });
                },
                "processing": true,
                search: {
                    return: true
                },
                "ajax": "{{route('API_userBFSPurchase',app()->getLocale())}}",
                "columns": [
                    {data: 'ranks'},
                    {data: 'Ref'},
                    {data: 'Date'},
                    {data: 'Designation'},
                    {data: 'Description'},
                    {data: 'value', className: classAl},
                    {data: 'balance', className: classAl},
                ],
                "columnDefs":
                    [
                        {
                            "targets": [5],
                            render: function (data, type, row) {
                                if (data.indexOf('+') == -1)
                                    return '<span class="badge bg-danger con">' + data + '</span>';
                                else
                                    return '<span class="badge bg-success con">' + data + '</span>';

                            }
                        },
                        {
                            "targets": [6],
                            render: function (data, type, row) {

                                if (row.ranks == 1)
                                    return '<div class="logoTopBFSLabel"><h5 class="text-success fs-14 mb-0 ms-2">' + data + '</h5></div>';
                                else
                                    return data;

                            }
                        },
                        {
                            "targets": [3],
                            render: function (data, type, row) {
                                if (select2_array.indexOf(data) == -1) {
                                    select2_array.push(data);
                                    $('.bfs_operation_multiple').append(('<option value="' + data + '">' + data + '</option>'));
                                }
                                return data;
                            }
                        }],
                "language": {
                    "url": urlLang
                }
            });

        $("#select2bfs").select2();


        $("#select2bfs").on("select2:select select2:unselect", function (e) {

            //this returns all the selected item
            var items = $(this).val();
            if ($(this).val() == null) { //$('#ub_table_bfs').DataTable().ajax.reload();
                table_bfs.columns(3).search("").draw();
            } else {
                table_bfs

                    .columns(3)
                    .search(items.join('|'), true, false)
                    .draw();
            }
            //console.log(items);
        })
    })

    window.addEventListener('closeModal', event => {
        var myModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('settingModal'));
        myModal.hide();
        $('#SettingsTable').DataTable().ajax.reload();
        // $("#settingModal").modal.hide();
    })
    window.addEventListener('closeModalOp', event => {
        var myModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('BoModal'));
        myModal.hide();
        $('#BalanceOperationsTable').DataTable().ajax.reload();
        // $("#settingModal").modal.hide();
    })
    window.addEventListener('closeModalAmounts', event => {
        var myModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('AmountsModal'));
        myModal.hide();
        $('#amountsTable').DataTable().ajax.reload();
        // $("#settingModal").modal.hide();
    })

</script>

@stack('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/19.2.16/js/intlTelInput.min.js"></script>
<script data-turbolinks-eval="false">

    $(document).on('ready turbolinks:load', function () {

        var ipPhone = document.getElementById("inputPhoneUpdate");
        const myParams = window.location.pathname.split("/");
        const pathPage = myParams[2];
        const pathPageSeg3 = myParams[3];

        var countryData = window.intlTelInputGlobals.getCountryData(),
            input = document.querySelector("#phonereg");
        var countryDataLog = window.intlTelInputGlobals.getCountryData(),
            inputlog = document.querySelector("#phone");

        var errorMap = ['{{trans('Invalid number')}}', '{{trans('Invalid country code')}}', '{{trans('Too shortsss')}}', '{{trans('Too long')}}', '{{trans('Invalid number')}}'];
        var ipAddContact = document.querySelector("#ipAddContact");
        var ipAdd2Contact = document.querySelector("#ipAdd2Contact");
        var ipUpdatePhoneAd = document.querySelector("#inputPhoneUpdateAd");
        var ipNumberContact = document.querySelector("#inputNumberContact");
        if (pathPage == 'Account') {

            ipPhone.innerHTML =
                "<input type='tel'  placeholder= '{{ __("PH_EditPhone") }}'    data-turbolinks-permanent name='mobileUpPhone' id='phoneUpPhone' class='form-control' onpaste='handlePaste(event)'>" +
                "  <span id='valid-msg'   class='invisible'>✓ Valid</span><span id='error-msg' class='hide'></span>" +
                " <input type='hidden' name='fullnumberUpPhone' id='outputUpPhone' value='hidden' class='form-control'> " +
                " <input type='hidden' name='ccodeUpPhone' id='ccodeUpPhone'  ><input type='hidden' name='isoUpPhone' id='isoUpPhone'  >";
            var countryDataUpPhone = window.intlTelInputGlobals.getCountryData(),
                inputUpPhone = document.querySelector("#phoneUpPhone");
            try {
                itiUpPhone.destroy();
            } catch (e) {
            }
            var itiUpPhone = window.intlTelInput(inputUpPhone, {
                initialCountry: "auto",
                // showSelectedDialCode: true,
                useFullscreenPopup: false,
                geoIpLookup: function (callback) {
                    $.get('https://ipinfo.io', function () {
                    }, "jsonp").always(function (resp) {
                        var countryCode = (resp && resp.country) ? resp.country : "TN";
                        callback(countryCode);
                    });
                },
                utilsScript: " {{asset('assets/js/utils.js')}}" // just for formatting/placeholders etc
            });
            inputUpPhone.addEventListener('keyup', resetUpPhone);
            inputUpPhone.addEventListener('countrychange', resetUpPhone);
            for (var i = 0; i < countryDataUpPhone.length; i++) {
                var country = countryDataUpPhone[i];
                var optionNode = document.createElement("option");
                optionNode.value = country.iso2;
            }
            document.querySelector("#phoneUpPhone").addEventListener("keypress", function (evt) {
                if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57) {
                    evt.preventDefault();
                }
            });
            var validMsg = document.querySelector("#valid-msg");
            var errorMsg = document.querySelector("#error-msg")
            inputUpPhone.addEventListener('blur', function () {
                // resetUpPhone();
                if (inputUpPhone.value.trim()) {
                    // console.log(itiUpPhone.isValidNumber());
                    if (itiUpPhone.isValidNumber()) {
                        // validMsg.classList.add("invisible");
                        $("#submit_phone").prop("disabled", false);
                    } else {
                        $("#submit_phone").prop("disabled", true);
                        inputUpPhone.classList.add("error");
                        var errorCode = itiUpPhone.getValidationError();

                        errorMsg.innerHTML = errorMap[errorCode];
                        errorMsg.classList.remove("invisible");
                    }
                }
            });
            resetUpPhone();
        }

        function resetUpPhone() {
            inputUpPhone.classList.remove("error");
            errorMsg.innerHTML = "";
            errorMsg.classList.add("invisible");
            validMsg.classList.add("invisible");
            $("#submit_phone").prop("disabled", true);
            var phone = itiUpPhone.getNumber();
            var textNode = document.createTextNode(phone);
            phone = phone.replace('+', '00');
            mobile = $("#phoneUpPhone").val();
            var countryData = itiUpPhone.getSelectedCountryData();
            phone = '00' + countryData.dialCode + phone;
            // $("#output").val(phone);
            $("#outputUpPhone").val(phone);
            // window.livewire.emit('changefullNumber', phone);
            // window.livewire.emit('changefullNumber');
            $("#ccodeUpPhone").val(countryData.dialCode);
            $("#isoUpPhone").val(countryData.iso2);

            fullphone = $("#outputUpPhone").val();
            if (inputUpPhone.value.trim()) {
                // console.log(itiAdd2Contact.isValidNumber());
                if (itiUpPhone.isValidNumber()) {
                    // validMsg.classList.add("invisible");
                    errorMsg.classList.add("invisible");
                    $("#submit_phone").prop("disabled", false);

                } else {
                    $("#submit_phone").prop("disabled", true);
                    inputUpPhone.classList.add("error");
                    var errorCode = itiUpPhone.getValidationError();

                    if (errorCode == '-99') {
                        errorMsg.innerHTML = errorMap[2];
                        errorMsg.classList.remove("invisible");
                    } else {
                        errorMsg.innerHTML = errorMap[errorCode];
                        errorMsg.classList.remove("invisible");
                    }


                }
            } else {
                $("#submit_phone").prop("disabled", true);
                inputUpPhone.classList.remove("error");
                var errorCode = itiUpPhone.getValidationError();

                errorMsg.innerHTML = errorMap[errorCode];
                errorMsg.classList.add("invisible");
            }
        };

        if (pathPage == 'Contacts') {
            inputlog = document.querySelector("#ipAdd2Contact");
            var itiLog = window.intlTelInput(inputlog, {
                initialCountry: "auto",
                // showSelectedDialCode: true,
                useFullscreenPopup: false,
                geoIpLookup: function (callback) {
                    $.get('https://ipinfo.io', function () {
                    }, "jsonp").always(function (resp) {
                        var countryCodelog = (resp && resp.country) ? resp.country : "TN";
                        callback(countryCodelog);
                    });
                },
                utilsScript: " {{asset('assets/js/utils.js')}}" // just for formatting/placeholders etc
            });

            inputlog.addEventListener('keyup', resetContacts);
            inputlog.addEventListener('countrychange', resetContacts);
            for (var i = 0; i < countryDataLog.length; i++) {
                var country12 = countryDataLog[i];
                var optionNode12 = document.createElement("option");
                optionNode12.value = country12.iso2;
                // var textNode = document.createTextNode(country.name);
                // optionNode.appendChild(textNode);
            }
            inputlog.focus();


        }

        if (pathPage == 'editContact') {

            ipAddContact.innerHTML = "<div class='input-group-prepend'> " +
                "</div><input wire:model.defer='phoneNumber' type='tel' name='phoneAddContact' id='phoneAddContact' class='form-control' onpaste='handlePaste(event)'" +
                "placeholder='Mobile Number'><span id='valid-msgAddContact' class='invisible'>✓ Valid</span><span id='error-msgAddContact' class='hide'></span>" +
                "<input type='hidden' name='fullnumber' id='outputAddContact' class='form-control'><input type='hidden' name='ccodeAddContact' id='ccodeAddContact'>";

            var countryDataAddContact = window.intlTelInputGlobals.getCountryData(),
                inputAddContact = document.querySelector("#phoneAddContact");
            try {
                itiAddContact.destroy();
            } catch (e) {
            }

            var nameInput = document.querySelector('#inputNameContact');
            var lastNameInput = document.querySelector('#inputlLastNameContact');
            inputAddContact.addEventListener('keyup', resetAddContact);
            inputAddContact.addEventListener('countrychange', resetAddContact);
            nameInput.addEventListener('keyup', resetAddContact);
            lastNameInput.addEventListener('keyup', resetAddContact);
            var bbol = true;
            var autoInit = "auto";
            if (bbol) autoInit = codePays;
            var itiAddContact = window.intlTelInput(inputAddContact, {
                initialCountry: autoInit,
                showSelectedDialCode: true,
                useFullscreenPopup: false,
                geoIpLookup: function (callback) {
                    $.get('https://ipinfo.io', function () {
                    }, "jsonp").always(function (resp) {
                        var countryCode = (resp && resp.country) ? resp.country : "TN";
                        callback(countryCode);
                    });
                },
                utilsScript: " {{asset('assets/js/utils.js')}}" // just for formatting/placeholders etc
            });
            for (var i = 0; i < countryDataAddContact.length; i++) {
                var country = countryDataAddContact[i];
                var optionNode = document.createElement("option");
                optionNode.value = country.iso2;
            }
            ;
            document.querySelector("#phoneAddContact").addEventListener("keypress", function (evt) {
                if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57) {
                    evt.preventDefault();
                }
            });
            var validMsg = document.querySelector("#valid-msgAddContact");
            var errorMsg = document.querySelector("#error-msgAddContact");
            inputAddContact.addEventListener('blur', function () {
                if (inputAddContact.value.trim()) {
                    // console.log(itiAddContact.isValidNumber());
                    if (itiAddContact.isValidNumber()) {
                        // validMsg.classList.add("invisible");
                        errorMsg.classList.add("invisible");
                        $("#SubmitAddContact").prop("disabled", false);

                    } else {
                        $("#SubmitAddContact").prop("disabled", true);
                        inputAddContact.classList.add("error");
                        var errorCode = itiAddContact.getValidationError();
                        errorMsg.innerHTML = errorMap[errorCode];
                        errorMsg.classList.remove("invisible");

                    }
                } else {
                    $("#SubmitAddContact").prop("disabled", true);
                    inputAddContact.classList.add("error");
                    var errorCode = itiAddContact.getValidationError();
                    errorMsg.innerHTML = errorMap[errorCode];
                    errorMsg.classList.remove("invisible");
                }
            });
            resetAddContact();
            $("#phoneAddContact").val($("#pho").val());
        }


        function resetAddContact() {
            var phone = itiAddContact.getNumber();
            if (phone == "") {
                phone = $("#pho").val();
            }
            var textNode = document.createTextNode(phone);
            phone = phone.replace('+', '00');

            mobile = $("#phoneAddContact").val();
            var countryData = itiAddContact.getSelectedCountryData();
            if (!phone.startsWith('00' + countryData.dialCode)) {
                phone = '00' + countryData.dialCode + phone;
            }
            $("#outputAddContact").val(phone);
            // $("#output").val(phone);
            // window.livewire.emit('changefullNumber', phone);
            // window.livewire.emit('changefullNumber');
            $("#ccodeAddContact").val(countryData.dialCode);
            // $("#ccodelog").val(countryData.dialCode);
            // fullphone = $("#output").val();

            // console.log("dqsd" + inputAddContact.value);
            if (inputAddContact.value.trim()) {
                // console.log(itiAddContact.isValidNumber());
                if (itiAddContact.isValidNumber()) {
                    // validMsg.classList.add("invisible");
                    errorMsg.classList.add("invisible");
                    $("#SubmitAddContact").prop("disabled", false);

                } else {
                    $("#SubmitAddContact").prop("disabled", true);
                    inputAddContact.classList.add("error");
                    var errorCode = itiAddContact.getValidationError();
                    errorMsg.innerHTML = errorMap[errorCode];
                    errorMsg.classList.remove("invisible");
                }
            } else {
                $("#SubmitAddContact").prop("disabled", true);
                inputAddContact.classList.remove("error");
                var errorCode = itiAddContact.getValidationError();
                errorMsg.innerHTML = errorMap[errorCode];
                errorMsg.classList.add("invisible");
            }
        };

        function resetContacts() {

            // alert(document.getElementById("ipAdd2Contact").value) ;
            //  $("#signin").prop("disabled", false);
            var phone = itiLog.getNumber();
            var textNode = document.createTextNode(phone);
            phone = phone.replace('+', '00');
            mobile = $("#ipAdd2Contact").val();
            var countryData = itiLog.getSelectedCountryData();
            //console.log(countryData.iso2);
            phone = '00' + countryData.dialCode + phone;
            $("#ccodeAdd2Contact").val(countryData.dialCode);
            $("#outputAdd2Contact").val(phone);
            //console.log(inputlog.value.trim());
            // if (inputlog.value.trim()) {
            //   if (itiLog.isValidNumber()) {
            //        $("#ipAdd2Contact").prop("disabled", false);
            // } else {
            //          $("#ipAdd2Contact").prop("disabled", true);
            //         inputlog.classList.add("error");
            //      }
            // } else {
            //    $("#ipAdd2Contact").prop("disabled", true);
            //      inputlog.classList.remove("error");
            //  }
        };
        if (pathPage == 'ContactNumber') {

            ipNumberContact.innerHTML = "<div class='input-group-prepend'> " +
                "</div><input wire:model.defer='' type='tel' name='phoneContactNumber' id='phoneContactNumber' class='form-control' onpaste='handlePaste(event)'" +
                "placeholder='{{ __("Mobile Number") }}'><span id='valid-msgphoneContactNumber' class='invisible'>✓ Valid</span><span id='error-msgphoneContactNumber' class='hide'></span>" +
                " <input type='hidden' name='fullnumber' id='outputphoneContactNumber' class='form-control'><input type='hidden' name='ccodephoneContactNumber' id='ccodephoneContactNumber'>" +
                "<input type='hidden' name='isoContactNumber' id='isoContactNumber'>";
            var countryDataNumberContact = window.intlTelInputGlobals.getCountryData(),
                inputAddContactNumber = document.querySelector("#phoneContactNumber");
            try {
                itiAddContactNumber.destroy();
            } catch (e) {

            }
            var itiAddContactNumber = window.intlTelInput(inputAddContactNumber, {
                initialCountry: "auto",
                //showSelectedDialCode: true,
                useFullscreenPopup: false,
                geoIpLookup: function (callback) {
                    $.get('https://ipinfo.io', function () {
                    }, "jsonp").always(function (resp) {
                        var countryCode13 = (resp && resp.country) ? resp.country : "TN";
                        callback(countryCode13);
                    });
                },
                utilsScript: " {{asset('assets/js/utils.js')}}" // just for formatting/placeholders etc
            });
            inputAddContactNumber.addEventListener('keyup', resetAddNumberContact);
            inputAddContactNumber.addEventListener('countrychange', resetAddNumberContact);

            for (var i = 0; i < countryDataNumberContact.length; i++) {
                var country = countryDataNumberContact[i];
                var optionNode = document.createElement("option");
                optionNode.value = country.iso2;
                // var textNode = document.createTextNode(country.name);
                // optionNode.appendChild(textNode);
            }
            ;
            resetAddNumberContact();
        }

        function resetAddNumberContact() {
            var phoneCN = itiAddContactNumber.getNumber();
            phoneCN = phoneCN.replace('+', '00');
            mobileCN = $("#phoneContactNumber").val();
            var countryDataCN = itiAddContactNumber.getSelectedCountryData();
            if (!phoneCN.startsWith('00' + countryDataCN.dialCode)) {
                phoneCN = '00' + countryDataCN.dialCode + phoneCN;
            }
            $("#outputphoneContactNumber").val(phoneCN);
            $("#ccodephoneContactNumber").val(countryDataCN.dialCode);
            $("#isoContactNumber").val(countryDataCN.iso2);
        };
    });


    $(document).on("click", ".addCash", function () {
        let reciver = $(this).data('reciver');
        let phone = $(this).data('phone');
        let country = $(this).data('country');
        console.log(reciver);
        $('#userlist-country').attr('src', country);
        $('#userlist-reciver').attr('value', reciver);
        $('#userlist-phone').attr('value', phone);
        console.log(reciver);
    });
    $(document).on("click", "#userlist-submit", function () {

        console.log($('#userlist-reciver').val());
        console.log($('#ammount').val());
        let reciver = $('#userlist-reciver').val();
        let ammount = $('#ammount').val();
        let msg = "vous avez transferé " + ammount + " $ à " + reciver;
        let user = 126;
        $.ajax({
            url: "{{ route('addCash') }}",
            type: "POST",

            data: {
                amount: ammount,
                reciver: reciver,
                "_token": "{{ csrf_token() }}"
            },
            success: function (data) {
                console.log(data);
                $.ajax({
                    url: "{{ route('sendSMS') }}",
                    type: "POST",

                    data: {
                        user: user,
                        msg: msg,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function (data) {
                        console.log(data);
                    }

                });

                $('#AddCash').modal('hide');
                Toastify({
                    text: data,
                    gravity: "top",
                    duration: 3000,
                    className: "info",
                    position: "center",
                    backgroundColor: "#27a706"
                }).showToast();
            }

        });
    });
    $(document).on("click", ".vip", function () {
        let reciver = $(this).data('reciver');
        let phone = $(this).data('phone');
        let country = $(this).data('country');
        console.log(reciver);
        $('#vip-country').attr('src', country);
        $('#vip-reciver').attr('value', reciver);
        $('#vip-phone').attr('value', phone);
        console.log(reciver);
    });
    $(document).on("click", "#vip-submit", function () {

        console.log($('#vip-reciver').val());

        let reciver = $('#vip-reciver').val();
        let minshares = $('#minshares').val();
        let periode = $('#periode').val();
        let coefficient = $('#coefficient').val();
        let note = $('#note').val();
        let date = Date.now();
        let msg = "vous avez transferé " + ammount + " $ à " + reciver;
        let msgvip = "l'utilisateur " + reciver + " est VIP(x" + coefficient + ") pour une periode de " + periode + " à partir de " + date + " avec un minimum de " + minshares + " actions acheté";
        let user = 126;
        $.ajax({
            url: "{{ route('vip') }}",
            type: "POST",

            data: {
                reciver: reciver,
                minshares: minshares,
                periode: periode,
                coefficient: coefficient,
                note: note,
                date: date,
                "_token": "{{ csrf_token() }}"
            },
            success: function (data) {
                console.log(data);
                $.ajax({
                    url: "{{ route('sendSMS') }}",
                    type: "POST",

                    data: {
                        user: user,
                        msg: msgvip,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function (data) {
                        console.log(data);
                    }

                });

                $('#vip').modal('hide');
                Toastify({
                    text: data,
                    gravity: "top",
                    duration: 3000,
                    className: "info",
                    position: "center",
                    backgroundColor: "#27a706"
                }).showToast();
            }

        });
    });


</script>

<script>

    $.ajax({
        url: "{{ route('getRequestAjax') }}",
        // data: {"id":id},
        type: 'GET',
        dataType: "json",
        success: function (result) {
            try {
                document.getElementById("NotificationRequest").innerHTML = "";
                var resultData = result.data;
                // console.log(resultData['out']);
                // NotificationRequest
                // if(resultData['requestInOpen']==6)
                // {
                //     alert('jooooooooo');
                // }
                if (resultData['requestInOpen'] > 0) {
                    var tag = document.createElement("span");
                    tag.id = "sideNotIn"
                    // badge badge-pill bg-danger
                    tag.classList.add("badge")
                    tag.classList.add("badge-pill")
                    // tag.classList.add("bg-danger")
                    tag.style.backgroundColor = "#3fc3ee"
                    var text = document.createTextNode(resultData['requestInOpen']);
                    tag.appendChild(text);
                    var element = document.getElementById("NotificationRequest");
                    element.appendChild(tag);
                }
                if (resultData['requestOutAccepted'] > 0) {
                    var tag = document.createElement("span");
                    tag.id = "sideNotOutAccepted"
                    // badge badge-pill bg-danger
                    tag.classList.add("badge")
                    tag.classList.add("badge-pill")
                    // tag.classList.add("bg-danger")
                    tag.style.backgroundColor = "#198C48"
                    var text = document.createTextNode(resultData['requestOutAccepted']);
                    tag.appendChild(text);
                    var element = document.getElementById("NotificationRequest");
                    element.appendChild(tag);
                }
                if (resultData['requestOutRefused'] > 0) {

                    var tag = document.createElement("span");
                    tag.id = "sideNotOutRefused"
                    // badge badge-pill bg-danger
                    tag.classList.add("badge")
                    tag.classList.add("badge-pill")
                    // tag.classList.add("bg-danger")
                    tag.style.backgroundColor = "#dc3741"
                    var text = document.createTextNode(resultData['requestOutRefused']);
                    tag.appendChild(text);
                    var element = document.getElementById("NotificationRequest");
                    element.appendChild(tag);
                }
            } catch (e) {
            }
            try {
                document.getElementById('SReqIn').innerHTML = "";
                document.getElementById('SReqIn').innerHTML = "";
                document.getElementById('SReqIn').innerHTML = "";
            } catch (e) {
            }
            // console.log(result)
        }
    });
</script>
<script>
    $("#HTMLMain").attr("data-layout-mode", sessionStorage.getItem("data-layout-mode"));
    $("#HTMLMain").attr("data-sidebar", sessionStorage.getItem("data-sidebar"));
    $("#btndark").click(function () {
        // alert($("#HTMLMain").attr("data-layout-mode"));
        mode = $("#HTMLMain").attr("data-layout-mode");
        // alert(mode);
        if (mode == "dark") {
            $("#HTMLMain").attr("data-layout-mode", "light")
            $("#HTMLMain").attr("data-sidebar", "light")
            sessionStorage.setItem("data-sidebar", "light");
            sessionStorage.setItem("data-layout-mode", "light");
        } else {
            $("#HTMLMain").attr("data-layout-mode", "dark")
            $("#HTMLMain").attr("data-sidebar", "dark")
            sessionStorage.setItem("data-sidebar", "dark");
            sessionStorage.setItem("data-layout-mode", "dark");
        }
        // alert( "Handler for .click() called." );
    });
</script>
</html>
