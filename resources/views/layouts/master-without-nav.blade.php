<!doctype html>
<html dir="{{config('app.available_locales')[app()->getLocale()]['direction']}}"
      lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-topbar="light">
<head>
    <meta charset="utf-8"/>
    <title>@yield('title') | 2Earn.cash</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="2earn.cash" name="description"/>
    <meta content="Themesbrand" name="author"/>
    <link rel="shortcut icon" href="{{ Vite::asset('resources/images/favicon.ico')}}">
    <livewire:styles />
    <script src="https://www.google.com/recaptcha/api.js?render={{config('services.recaptcha.key')}}"></script>
    @if(config('app.available_locales')[app()->getLocale()]['direction'] === 'rtl')
        @vite(['resources/css/bootstrap-rtl.css','resources/css/icons-rtl.css','resources/css/app-rtl.css','resources/css/custom-rtl.css'])
    @else
        @vite(['resources/css/bootstrap.min.css','resources/css/icons.css','resources/css/app.css','resources/css/custom.css'])
    @endif
    @vite([ 'resources/css/intlTelInput.min.css','resources/js/sweetalert2@11.js','resources/js/appWithoutNav.js','resources/js/intlTelInput.js'])
</head>
<body>
<div class="container-fluid">
    @yield('content')
</div>
<livewire:scripts />
<script type="module">
    $(function () {
        var countryDataLog = (typeof window.intlTelInputGlobals !== "undefined") ? window.intlTelInputGlobals.getCountryData() : [],
            inputlog = document.querySelector("#intlTelInputPhone");
            var itiLog = window.intlTelInput(inputlog, {
                initialCountry: "auto",
                useFullscreenPopup: false,
                geoIpLookup: function (callback) {
                    $.get('https://ipinfo.io', function () {
                    }, "jsonp").always(function (resp) {
                        callback((resp && resp.country) ? resp.country : "TN");
                    });
                },
                utilsScript: " {{Vite::asset('/resources/js/utils.js')}}"
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
