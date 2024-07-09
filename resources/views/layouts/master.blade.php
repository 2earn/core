<!doctype html >
<html dir="{{config('app.available_locales')[app()->getLocale()]['direction']}}"
      lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="vertical" data-topbar="light"
      data-sidebar="light" data-sidebar-size="sm-hover-active" data-sidebar-image="none" data-preloader="disable"
      id="HTMLMain" data-layout-mode="light"
>
<head>
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.ga4.measurementId') }}"></script>
    <script>
        var lan = "{{config('app.available_locales')[app()->getLocale()]['tabLang']}}";
        var urlLang = "https://cdn.datatables.net/plug-ins/1.12.1/i18n/" + lan + ".json";
        var classAl = "text-end";
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
    <meta charset="utf-8"/>
    <title>@yield('title')| 2Earn.cash</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="2earn.cash" name="description"/>
    <meta content="" name="author"/>
    <meta property="og:image" content="{{ Vite::asset('resources/images/2earn.png') }}">
    <meta property="twitter:image" content="{{ Vite::asset('resources/images/2earn.png') }}">
    <!-- vite -->
    @vite([
                'resources/anychart/anychart-base.min.js',
                'resources/anychart/anychart-circular-gauge.min.js',
                'resources/anychart/anychart-data-adapter.min.js',
                'resources/anychart/anychart-exports.min.js',
                'resources/anychart/anychart-font.min.css',
                'resources/anychart/anychart-map.min.js',
                'resources/anychart/anychart-sankey.min.js',
                'resources/anychart/anychart-tag-cloud.min.js',
                'resources/anychart/anychart-ui.min.css',
                'resources/anychart/anychart-ui.min.js',
                'resources/anychart/proj4.js',
                'resources/anychart/world.js',
                'resources/anychart/anychart-table.min.js',
    ])

    <!-- vite -->

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ Vite::asset('resources/images/favicon.ico')}}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('layouts.vendor-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
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
<body>
@section('body')
    @include('components.styles');
    @vite(['resources/css/intlTelInput.min.css','resources/fontawesome/all.min.css','resources/js/sweetalert2@11.js','resources/js/app.js','resources/js/livewire-turbolinks.js','resources/js/intlTelInput.js'])
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PMK39HQQ"
                height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>
    <script src="{{ asset('/sw.js') }}"></script>
@show
<div id="layout-wrapper">
    <livewire:top-bar :currentRoute="Route::currentRouteName()"/>
    @include('layouts.sidebar')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
    </div>
</div>
@include('layouts.footer', ['pageName' => 'master'])
@livewireScripts
@vite('resources/js/pages/crypto-kyc.init.js')
<script type="module">
    window.addEventListener('load', () => {
        anychart.onDocumentReady(function () {
            anychart.licenseKey('2earn.cash-953c5a55-712f04c3');
        });
    });
</script>
<script type="module">
    $(document).on('turbolinks:load', function () {
        var select2_array = [];
        var classAl = "text-end";
        var tts = '{{config('app.available_locales')[app()->getLocale()]['direction']}}';
        if (tts == 'rtl') {
            classAl = "text-start";
        }
        var url = '';
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
                data: {total: total, amount: ammount, id: reciver, "_token": "{{ csrf_token() }}"},
                success: function (data) {
                    $('#realsoldmodif').modal('hide');
                    $('#shares-sold').DataTable().ajax.reload();
                    fetchAndUpdateCardContent();
                }

            });

            function saveHA() {
                window.livewire.emit('saveHA', $("#tags").val());
            }

            function fetchAndUpdateCardContent() {
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

            $("#select2bfs").select2();

            $("#select2bfs").on("select2:select select2:unselect", function (e) {
                var items = $(this).val();
                if ($(this).val() == null) {
                    table_bfs.columns(3).search("").draw();
                } else {
                    table_bfs
                        .columns(3)
                        .search(items.join('|'), true, false)
                        .draw();
                }
            })
        });
    });

</script>
@stack('scripts')
<script type="module">
    $(document).on('turbolinks:load', function () {
        var ipPhone = document.getElementById("inputPhoneUpdate");
        const myParams = window.location.pathname.split("/");
        const pathPage = myParams[2];
        const pathPageSeg3 = myParams[3];
        var countryData = (typeof window.intlTelInputGlobals !== "undefined") ? window.intlTelInputGlobals.getCountryData() : [],
            input = document.querySelector("#phonereg");
        var countryDataLog = (typeof window.intlTelInputGlobals !== "undefined") ? window.intlTelInputGlobals.getCountryData() : [],
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
            var countryDataUpPhone = (typeof window.intlTelInputGlobals !== "undefined") ? window.intlTelInputGlobals.getCountryData() : [],
                inputUpPhone = document.querySelector("#phoneUpPhone");
            try {
                itiUpPhone.destroy();
            } catch (e) {
            }
            var itiUpPhone = window.intlTelInput(inputUpPhone, {
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
                if (inputUpPhone.value.trim()) {
                    if (itiUpPhone.isValidNumber()) {
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
                utilsScript: " {{asset('/build/utils.js/utils.js')}}"
            });

            inputlog.addEventListener('keyup', resetContacts);
            inputlog.addEventListener('countrychange', resetContacts);
            for (var i = 0; i < countryDataLog.length; i++) {
                var country12 = countryDataLog[i];
                var optionNode12 = document.createElement("option");
                optionNode12.value = country12.iso2;

            }
            inputlog.focus();
        }
        if (pathPage == 'editContact') {

            ipAddContact.innerHTML = "<div class='input-group-prepend'> " +
                "</div><input wire:model.defer='phoneNumber' type='tel' name='phoneAddContact' id='phoneAddContact' class='form-control' onpaste='handlePaste(event)'" +
                "placeholder='Mobile Number'><span id='valid-msgAddContact' class='invisible'>✓ Valid</span><span id='error-msgAddContact' class='hide'></span>" +
                "<input type='hidden' name='fullnumber' id='outputAddContact' class='form-control'><input type='hidden' name='ccodeAddContact' id='ccodeAddContact'>";

            var countryDataAddContact = (typeof window.intlTelInputGlobals !== "undefined") ? window.intlTelInputGlobals.getCountryData() : [],
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
                utilsScript: " {{asset('/build/utils.js/utils.js')}}"
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
                    if (itiAddContact.isValidNumber()) {
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
        if (pathPage == 'ContactNumber') {

            ipNumberContact.innerHTML = "<div class='input-group-prepend'> " +
                "</div><input wire:model.defer='' type='tel' name='phoneContactNumber' id='phoneContactNumber' class='form-control' onpaste='handlePaste(event)'" +
                "placeholder='{{ __("Mobile Number") }}'><span id='valid-msgphoneContactNumber' class='invisible'>✓ Valid</span><span id='error-msgphoneContactNumber' class='hide'></span>" +
                " <input type='hidden' name='fullnumber' id='outputphoneContactNumber' class='form-control'><input type='hidden' name='ccodephoneContactNumber' id='ccodephoneContactNumber'>" +
                "<input type='hidden' name='isoContactNumber' id='isoContactNumber'>";
            var countryDataNumberContact = (typeof window.intlTelInputGlobals !== "undefined") ? window.intlTelInputGlobals.getCountryData() : [],
                inputAddContactNumber = document.querySelector("#phoneContactNumber");
            try {
                itiAddContactNumber.destroy();
            } catch (e) {

            }
            var itiAddContactNumber = window.intlTelInput(inputAddContactNumber, {
                initialCountry: "auto",
                useFullscreenPopup: false,
                geoIpLookup: function (callback) {
                    $.get('https://ipinfo.io', function () {
                    }, "jsonp").always(function (resp) {
                        var countryCode13 = (resp && resp.country) ? resp.country : "TN";
                        callback(countryCode13);
                    });
                },
                utilsScript: " {{asset('/build/utils.js/utils.js')}}"
            });
            inputAddContactNumber.addEventListener('keyup', resetAddNumberContact);
            inputAddContactNumber.addEventListener('countrychange', resetAddNumberContact);

            for (var i = 0; i < countryDataNumberContact.length; i++) {
                var country = countryDataNumberContact[i];
                var optionNode = document.createElement("option");
                optionNode.value = country.iso2;
            }
            resetAddNumberContact();
        }

        function resetAddContact() {
            var phone = itiAddContact.getNumber();
            if (phone == "") {
                phone = $("#pho").val();
            }
            var textNode = document.createTextNode(phone);
            phone = phone.replace('+', '00');

            var mobile = $("#phoneAddContact").val();
            var countryData = itiAddContact.getSelectedCountryData();
            if (!phone.startsWith('00' + countryData.dialCode)) {
                phone = '00' + countryData.dialCode + phone;
            }
            $("#outputAddContact").val(phone);

            $("#ccodeAddContact").val(countryData.dialCode);
            if (inputAddContact.value.trim()) {
                if (itiAddContact.isValidNumber()) {
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
            var phone = itiLog.getNumber();
            var textNode = document.createTextNode(phone);
            phone = phone.replace('+', '00');
            var mobile = $("#ipAdd2Contact").val();
            var countryData = itiLog.getSelectedCountryData();
            phone = '00' + countryData.dialCode + phone;
            $("#ccodeAdd2Contact").val(countryData.dialCode);
            $("#outputAdd2Contact").val(phone);
        };

        function resetAddNumberContact() {
            var phoneCN = itiAddContactNumber.getNumber();
            phoneCN = phoneCN.replace('+', '00');
            var mobileCN = $("#phoneContactNumber").val();
            var countryDataCN = itiAddContactNumber.getSelectedCountryData();
            if (!phoneCN.startsWith('00' + countryDataCN.dialCode)) {
                phoneCN = '00' + countryDataCN.dialCode + phoneCN;
            }
            $("#outputphoneContactNumber").val(phoneCN);
            $("#ccodephoneContactNumber").val(countryDataCN.dialCode);
            $("#isoContactNumber").val(countryDataCN.iso2);
            if (itiAddContactNumber.isValidNumber()) {
                $('#saveAddContactNumber').prop("disabled", false)
            } else {
                $('#saveAddContactNumber').prop("disabled", true)
            }
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
            var mobile = $("#phoneUpPhone").val();
            var countryData = itiUpPhone.getSelectedCountryData();
            phone = '00' + countryData.dialCode + phone;
            $("#outputUpPhone").val(phone);
            $("#ccodeUpPhone").val(countryData.dialCode);
            $("#isoUpPhone").val(countryData.iso2);

            var fullphone = $("#outputUpPhone").val();
            if (inputUpPhone.value.trim()) {
                if (itiUpPhone.isValidNumber()) {
                    errorMsg.classList.add("invisible");
                    $("#submit_phone").prop("disabled", false);
                } else {
                    $("#submit_phone").prop("disabled", true);
                    inputUpPhone.classList.add("error");
                    var errorCode = itiUpPhone.getValidationError();
                    errorMsg.classList.remove("invisible");
                    if (errorCode == '-99') {
                        errorMsg.innerHTML = errorMap[2];
                    } else {
                        errorMsg.innerHTML = errorMap[errorCode];
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

        $(document).on("click", ".addCash", function () {
            let reciver = $(this).data('reciver');
            let phone = $(this).data('phone');
            let country = $(this).data('country');
            $('#userlist-country').attr('src', country);
            $('#userlist-reciver').attr('value', reciver);
            $('#userlist-phone').attr('value', phone);
        });
        $(document).on("click", "#userlist-submit", function () {
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
                    $.ajax({
                        url: "{{ route('sendSMS') }}",
                        type: "POST",
                        data: {user: user, msg: msg, "_token": "{{ csrf_token() }}"},
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
            $('#vip-country').attr('src', country);
            $('#vip-reciver').attr('value', reciver);
            $('#vip-phone').attr('value', phone);
        });
        $(document).on("click", "#vip-submit", function () {
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
                    $.ajax({
                        url: "{{ route('sendSMS') }}",
                        type: "POST",
                        data: {user: user, msg: msgvip, "_token": "{{ csrf_token() }}"},
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


        $.ajax({
            url: "{{ route('getRequestAjax') }}",
            type: 'GET',
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
                }
                try {
                    document.getElementById('SReqIn').innerHTML = "";
                    document.getElementById('SReqIn').innerHTML = "";
                    document.getElementById('SReqIn').innerHTML = "";
                } catch (e) {
                }
            }
        });

        $("#HTMLMain").attr("data-layout-mode", sessionStorage.getItem("data-layout-mode"));
        $("#HTMLMain").attr("data-sidebar", sessionStorage.getItem("data-sidebar"));
        $("#btndark").click(function () {
            var mode = $("#HTMLMain").attr("data-layout-mode");
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
        });
    });
</script>
</body>
</html>
