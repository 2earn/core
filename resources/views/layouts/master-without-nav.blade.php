<!doctype html>
<html dir="{{config('app.available_locales')[app()->getLocale()]['direction']}}"
      data-turbolinks='false' lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-topbar="light">
<head>
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
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.ga4.measurementId') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());
        gtag('config', '{{ config('services.ga4.measurementId') }}');
    </script>
    <meta charset="utf-8"/>
    <title>@yield('title') | 2Earn.cash</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="2earn.cash" name="description"/>
    <meta content="Themesbrand" name="author"/>
    <img src="{{ URL::asset('assets/images/2earn.png') }}" id="super-logo" alt="" height="60">
    <link rel="shortcut icon" href="{{ URL::asset('assets/images/favicon.ico')}}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{asset('assets/Styles/intlTelInput.css')}}">
    <script src="{{asset('assets/js/intlTelInput.js')}}"></script>
    @include('layouts.head-css')
    <style>
        @import url({{asset('/')."assets/icons/material-design-iconic-font/css/materialdesignicons.min.css"}});
        @import url({{asset('assets/icons/line-awesome/css/line-awesome.min.css')}});
        @import url({{asset('assets/icons/font-awesome/css/font-awesome.min.css')}});

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
    <script src="https://www.google.com/recaptcha/api.js?render={{config('services.recaptcha.key')}}"></script>

</head>

@yield('body')
<!-- Google Tag Manager (noscript) -->
<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PMK39HQQ"
            height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->
@if(app()->getLocale() == 'ar')
    <style>
        @font-face {
            font-family: ar400;
            src: url("{{asset('assets/NotoKufiArabic-Regular.ttf')}}");
            font-weight: 400;
        }

        label, h1, h2, h3, h4, h5, a, button, p, i, span, strong, .btn, div {
            font-family: ar400;
            font-weight: 500 !important;
        }
    </style>
@endif
<div class="container-fluid">
    @yield('content')
</div>
@include('layouts.vendor-scripts')
@livewireScripts
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/livewire/turbolinks@v0.1.x/dist/livewire-turbolinks.js"
        data-turbolinks-eval="false" data-turbo-eval="false"></script>
{{--<script src="{{ mix('js/turbo.js') }}" defer></script>--}}
{{--<script src="{{ URL::asset('/assets/js/app.min.js') }}" defer></script>--}}
<script>

    $(document).on('ready turbolinks:load', function () {
        const myParams = window.location.pathname.split("/");
        const pathPage = myParams[2];
        const pathPage2 = myParams[1];
        var countryData = (typeof window.intlTelInputGlobals !== "undefined") ? window.intlTelInputGlobals.getCountryData() : [],
            input = document.querySelector("#phonereg");
        var countryDataLog = (typeof window.intlTelInputGlobals !== "undefined") ? window.intlTelInputGlobals.getCountryData() : [],
            inputlog = document.querySelector("#phone");
        var countryDataforget = (typeof window.intlTelInputGlobals !== "undefined") ? window.intlTelInputGlobals.getCountryData() : [],
            inputforget = document.querySelector("#phoneforget");


        if (pathPage == 'login' || pathPage2 == 'login') {
            var itiLog = window.intlTelInput(inputlog, {
                initialCountry: "auto",
                useFullscreenPopup: false,
                geoIpLookup: function (callback) {
                    $.get('https://ipinfo.io', function () {
                    }, "jsonp").always(function (resp) {
                        callback((resp && resp.country) ? resp.country : "TN");
                    });
                },
                utilsScript: " {{asset('assets/js/utils.js')}}"
            });

            inputlog.addEventListener('keyup', resetLog);
            inputlog.addEventListener('countrychange', resetLog);
            for (var i = 0; i < countryDataLog.length; i++) {
                var country12 = countryDataLog[i];
                var optionNode12 = document.createElement("option");
                optionNode12.value = country12.iso2;
            }
            inputlog.focus();
            $("#password").focus();

            inputlog.addEventListener('blur', function () {
                if (inputlog.value.trim()) {
                    if (itiLog.isValidNumber()) {
                        $("#signin").prop("disabled", false);
                    } else {
                        $("#signin").prop("disabled", true);
                        inputlog.classList.add("error");
                    }
                } else {
                    $("#signin").prop("disabled", true);
                    inputlog.classList.add("error");
                    var errorCode = itiLog.getValidationError();
                }
            });
            resetLog();
        }
        if (pathPage == 'registre') {
            var iti = window.intlTelInput(input, {
                initialCountry: "auto",
                useFullscreenPopup: false,
                geoIpLookup: function (callback) {
                    $.get('https://ipinfo.io', function () {
                    }, "jsonp").always(function (resp) {
                        callback((resp && resp.country) ? resp.country : "TN");
                    });
                },
                utilsScript: " {{asset('assets/js/utils.js')}}"
            });
            input.addEventListener('keyup', reset);
            input.addEventListener('countrychange', reset);
            for (var i = 0; i < countryData.length; i++) {
                var country = countryData[i];
                var optionNode = document.createElement("option");
                optionNode.value = country.iso2;
            }
        }
        if (pathPage == 'forgetpassword') {
            var itiforget = window.intlTelInput(inputforget, {
                initialCountry: "auto",
                useFullscreenPopup: false,
                geoIpLookup: function (callback) {
                    $.get('https://ipinfo.io', function () {
                    }, "jsonp").always(function (resp) {
                        var countryCode = (resp && resp.country) ? resp.country : "TN";
                        callback(countryCode);
                    });
                },
                utilsScript: " {{asset('assets/js/utils.js')}}"
            });
            inputforget.addEventListener('keyup', resetforget);
            inputforget.addEventListener('countrychange', resetforget);
            for (var i = 0; i < countryDataforget.length; i++) {
                var country13 = countryDataforget[i];
                var optionNode13 = document.createElement("option");
                optionNode13.value = country13.iso2;
            }
        }

        function resetforget() {
            $("#submit_form").prop("disabled", false);
            var phone = itiforget.getNumber();
            var textNode = document.createTextNode(phone);
            phone = phone.replace('+', '00');
            mobile = $("#phoneforget").val();
            var countryData = itiforget.getSelectedCountryData();
            if (!phone.startsWith('00' + countryData.dialCode)) {
                phone = '00' + countryData.dialCode + phone;
            }
            $("#outputforget").val(phone);
            $("#ccodeforget").val(countryData.dialCode);
            fullphone = $("#outputforget").val();
        };

        function reset() {
            var phone = iti.getNumber();
            var textNode = document.createTextNode(phone);
            phone = phone.replace('+', '00');
            mobile = $("#phonereg").val();
            var countryData = iti.getSelectedCountryData();
            if (!phone.startsWith('00' + countryData.dialCode)) {
                phone = '00' + countryData.dialCode + phone;
            }
            $("#output").val(phone);
            $("#ccode").val(countryData.dialCode);
            $("#ccodelog").val(countryData.dialCode);
            $("#iso2Country").val(countryData.iso2);
            fullphone = $("#output").val();
        };

        function resetLog() {
            $("#signin").prop("disabled", false);
            var phone = itiLog.getNumber();
            var textNode = document.createTextNode(phone);
            phone = phone.replace('+', '00');
            mobile = $("#phoneLog").val();
            var countryData = itiLog.getSelectedCountryData();
            phone = '00' + countryData.dialCode + phone;
            $("#ccodelog").val(countryData.dialCode);
            $("#isoCountryLog").val(countryData.iso2);
            if (inputlog.value.trim()) {
                if (itiLog.isValidNumber()) {
                    $("#signin").prop("disabled", false);
                } else {
                    $("#signin").prop("disabled", true);
                    inputlog.classList.add("error");
                }
            } else {
                $("#signin").prop("disabled", true);
                inputlog.classList.remove("error");
            }
        };
    });
</script>
</body>
</html>
