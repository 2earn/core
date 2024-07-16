<!doctype html>
<html dir="{{config('app.available_locales')[app()->getLocale()]['direction']}}" data-turbolinks='false'
      lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-topbar="light">
<head>
    <meta charset="utf-8"/>
    <title>@yield('title') | 2Earn.cash</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="2earn.cash" name="description"/>
    <meta content="Themesbrand" name="author"/>
    <link rel="shortcut icon" href="{{ Vite::asset('resources/images/favicon.ico')}}">
    @livewireStyles
    <script src="https://www.google.com/recaptcha/api.js?render={{config('services.recaptcha.key')}}"></script>
    @if(config('app.available_locales')[app()->getLocale()]['direction'] === 'rtl')
        @vite(['resources/css/bootstrap-rtl.css','resources/css/icons-rtl.css','resources/css/app-rtl.css','resources/css/custom-rtl.css'])
    @else
        @vite(['resources/css/bootstrap.min.css','resources/css/icons.css','resources/css/app.css','resources/css/custom.css'])
    @endif
    @vite([ 'resources/css/intlTelInput.min.css','resources/js/sweetalert2@11.js','resources/js/appWithoutNav.js','resources/js/livewire-turbolinks.js','resources/js/intlTelInput.js'])
</head>
<body>
<div class="container-fluid">
    @yield('content')
</div>
@livewireScripts
<script type="module">
    $(function () {
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
                utilsScript: " {{asset('/build/utils.js/utils.js')}}"
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
                utilsScript: " {{asset('/build/utils.js/utils.js')}}"
            });
            input.addEventListener('keyup', reset);
            input.addEventListener('countrychange', reset);
            for (var i = 0; i < countryData.length; i++) {
                var country = countryData[i];
                var optionNode = document.createElement("option");
                optionNode.value = country.iso2;
            }
        }
        if (pathPage == 'forget_password') {
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
                utilsScript: " {{asset('/build/utils.js/utils.js')}}"
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
            var mobile = $("#phoneforget").val();
            var countryData = itiforget.getSelectedCountryData();
            if (!phone.startsWith('00' + countryData.dialCode)) {
                phone = '00' + countryData.dialCode + phone;
            }
            $("#outputforget").val(phone);
            $("#ccodeforget").val(countryData.dialCode);
            var fullphone = $("#outputforget").val();
        };

        function reset() {
            var phone = iti.getNumber();
            var textNode = document.createTextNode(phone);
            phone = phone.replace('+', '00');
            var mobile = $("#phonereg").val();
            var countryData = iti.getSelectedCountryData();
            if (!phone.startsWith('00' + countryData.dialCode)) {
                phone = '00' + countryData.dialCode + phone;
            }
            $("#output").val(phone);
            $("#ccode").val(countryData.dialCode);
            $("#ccodelog").val(countryData.dialCode);
            $("#iso2Country").val(countryData.iso2);
            var fullphone = $("#output").val();
        };

        function resetLog() {
            $("#signin").prop("disabled", false);
            var phone = itiLog.getNumber();
            var textNode = document.createTextNode(phone);
            phone = phone.replace('+', '00');
            var mobile = $("#phoneLog").val();
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
    })
</script>
</body>
</html>
