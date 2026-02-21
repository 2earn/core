<!doctype html>
<html dir="{{config('app.available_locales')[app()->getLocale()]['direction']}}"
    lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="vertical" data-topbar="light"
    data-sidebar="light" data-sidebar-size="sm-hover-active" data-sidebar-image="none" data-preloader="disable"
    id="HTMLMain">

<head>
    <script>
        // Set dark mode IMMEDIATELY before any CSS loads to prevent flash
        (function () {
            var darkMode = localStorage.getItem('data-layout-mode');
            if (darkMode === 'dark') {
                document.documentElement.setAttribute('data-layout-mode', 'dark');
            } else {
                document.documentElement.setAttribute('data-layout-mode', 'light');
            }
        })();
    </script>
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.ga4.measurementId') }}"></script>
    <script>
        var lan = "{{config('app.available_locales')[app()->getLocale()]['tabLang']}}";
        var urlLang = "https://cdn.datatables.net/plug-ins/1.12.1/i18n/" + lan + ".json";
        var classAl = "text-end";
        var datatableControlBtn = {
            className: 'dtr-control arrow-right',
            orderable: false,
            data: null,
            defaultContent: '<i class="fa-solid fa-circle-question text-info fa-lg dtmdbtn"></i>'
        };
    </script>
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
    <meta charset="utf-8" />
    <title>@yield('title')| 2Earn.cash</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="2earn.cash" name="description" />
    <meta content="" name="author" />
    <meta property="og:image" content="{{ Vite::asset('resources/images/2earn.png') }}">
    <meta property="twitter:image" content="{{ Vite::asset('resources/images/2earn.png') }}">
    @vite([
        'resources/js/app.js',
        'resources/css/app.css',
    ])
    <link rel="shortcut icon" href="{{ Vite::asset('resources/images/favicon.ico')}}">


    <meta name="theme-color" content="#6777ef" />
    <link rel="apple-touch-icon" href="{{ asset('logo.PNG') }}">
    <link rel="manifest" href="{{ asset('/manifest.json') }}">
    @laravelPWA

</head>

<body>
    @section('body')
    <div id="layout-wrapper">
        <div class="main-content">
            <div class="page-content">
                @yield('content')
            </div>
        </div>
    </div>
    @include('parts.error-modal')
    @include('layouts.footer', ['pageName' => 'master'])
    @vite('resources/js/pages/crypto-kyc.init.js')
    @stack('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            window.loadDatatableModalError = function (datatableID) {
                $('#' + datatableID).hide();
                $('#ub_table tbody').html(
                    '<tr><td colspan="7" class="text-center text-danger fw-bold">@lang("An error suppressed")</td></tr>'
                );
                $('#ub_table').DataTable().clear();
                let modal = new bootstrap.Modal(document.getElementById('errorModal'));
                modal.show();
            };
        });
    </script>

    <script type="module">

        document.addEventListener("DOMContentLoaded", function () {

            var select2_array = [];
            var classAl = "text-end";
            var tts = '{{config('app.available_locales')[app()->getLocale()]['direction']}}';
            if (tts == 'rtl') {
                classAl = "text-start";
            }
            var url = '';
        });
        document.addEventListener("DOMContentLoaded", function () {
            const elementNotificationRequest = document.getElementById("yourElementId");
            if (elementNotificationRequest) {
                $.ajax({
                    url: "{{ route('get_request_ajax') }}",
                    type: 'GET',
                    headers: { 'Authorization': 'Bearer ' + "{{generateUserToken()}}" },
                    dataType: "json",
                    success: function (result) {
                        try {
                            document.getElementById("NotificationRequest").innerHTML = "";
                            var resultData = result.data;

                            if (resultData['requestInOpen'] > 0) {
                                var tag = document.createElement("span");
                                tag.id = "sideNotIn"
                                tag.classList.add("badge")
                                tag.classList.add("badge-pill")
                                tag.style.backgroundColor = "#3fc3ee"
                                var text = document.createTextNode(resultData['requestInOpen']);
                                tag.appendChild(text);
                                var element = document.getElementById("NotificationRequest");
                                element.appendChild(tag);
                            }
                            if (resultData['requestOutAccepted'] > 0) {
                                var tag = document.createElement("span");
                                tag.id = "sideNotOutAccepted"
                                tag.classList.add("badge")
                                tag.classList.add("badge-pill")
                                tag.style.backgroundColor = "#198C48"
                                var text = document.createTextNode(resultData['requestOutAccepted']);
                                tag.appendChild(text);
                                var element = document.getElementById("NotificationRequest");
                                element.appendChild(tag);
                            }
                            if (resultData['requestOutRefused'] > 0) {
                                var tag = document.createElement("span");
                                tag.id = "sideNotOutRefused"
                                tag.classList.add("badge")
                                tag.classList.add("badge-pill")
                                tag.style.backgroundColor = "#dc3741"
                                var text = document.createTextNode(resultData['requestOutRefused']);
                                tag.appendChild(text);
                                var element = document.getElementById("NotificationRequest");
                                element.appendChild(tag);
                            }
                        } catch (e) {
                            console.error(e)
                        }
                        try {
                            var element = document.getElementById('SReqIn');
                            if (typeof (element) != 'undefined' && element != null) {
                                element.innerHTML = "";
                            }
                        } catch (e) {
                            console.error(e)
                        }
                    }
                });
            }
        });
    </script>
</body>

</html>