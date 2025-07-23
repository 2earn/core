<div class="container-fluid">
    <div>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
        <script>

            var ErreurSoldeReqBFS2 = '{{Session::has('ErreurSoldeReqBFS2')}}';
            if (ErreurSoldeReqBFS2) {
                var someTabTriggerEl = document.querySelector('#pills-contact-tab');
                var tab = new bootstrap.Tab(someTabTriggerEl);
                tab.show();
                Swal.fire({
                    title: '{{trans('SureTransfertCashBFS')}}',
                    text: '{{trans('SoldeRequestInsuffisant')}} ',
                    icon: 'warning',
                    showCancelButton: true,
                    cancelButtonText: '{{trans('Cancel')}}',
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '{{trans('Yes')}}'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var url_string = window.location.href;
                        var url = new URL(url_string);
                        var paramValue = url.searchParams.get("FinRequestN");

                        window.Livewire.dispatch('redirectToTransfertCash', ['{{Session::get('ErreurSoldeReqBFS2')}}', paramValue]);
                    }
                })
            }

            var tabRequest = '{{Session::has('tabRequest')}}';
            if (tabRequest) {
                var someTabTriggerEl = document.querySelector('#pills-profile-tab');
                var tab = new bootstrap.Tab(someTabTriggerEl);
                tab.show();
            }
        </script>

        @component('components.breadcrumb')
            @slot('title')
                {{ __('Financial transaction') }}
            @endslot
        @endcomponent
        <div class="row">
            @include('layouts.flash-messages')
        </div>
        <div class="row card">
            <div class="card-body">
                <ul id="pills-tab" class="nav nav-tabs nav-justified" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" data-bs-toggle="tab" href="#cash_bfs" role="tab"
                           aria-selected="false" tabindex="-1">
                            {!! __('Cash >> BFS') !!}
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link align-middle" data-bs-toggle="tab" href="#bfs_funding" role="tab"
                           aria-selected="false" tabindex="-1">
                            {{ __('BFS Funding') }}
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link align-middle" data-bs-toggle="tab" href="#bfs_sms" role="tab"
                           aria-selected="false" tabindex="-1">
                            {!! __('BFS >> SMS') !!}
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a id="pills-profile-tab" class="nav-link" data-bs-toggle="tab" href="#me_others" role="tab"
                           aria-selected="true">
                            {!! __('Requests: Me >> Others') !!}
                            @if($requestOutAccepted>0)
                                <button id="btnNotRequestOutAcccepted" type="button"
                                        class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle">
                                    <i class=" ri-user-follow-line text-success fs-22"></i>
                                    <span id="pOutAccepted"
                                          class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-success">
                                            {{$requestOutAccepted}}
                                        </span>
                                </button>
                            @endif
                            @if($requestOutRefused>0)
                                <button id="btnNotRequestOutRefused" type="button"
                                        class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle">
                                    <i class=" ri-user-unfollow-line text-danger fs-22"></i>
                                    <span id="pOutRefused"
                                          class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-danger">{{$requestOutRefused}}</span>
                                </button>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a id="pills-contact-tab" class="nav-link" data-bs-toggle="tab" href="#others_me" role="tab"
                           aria-selected="true">
                            {!! __('Requests: Others >> Me') !!}
                            @if($requestInOpen>0)
                                <button id="btnNotRequestInOpen" type="button"
                                        class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle">
                                    <i class="  ri-user-received-2-line text-primary fs-22"></i>
                                    <span id="pIn"
                                          class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-primary">  {{$requestInOpen}}</span>
                                </button>
                            @endif
                        </a>
                    </li>
                </ul>
                <div class="tab-content text-muted">
                    <livewire:cash-to-bfs/>
                    <livewire:bfs-funding/>
                    <livewire:bfs-to-sms/>
                    <livewire:outgoing-request/>
                    <livewire:incoming-request/>
                </div>
            </div>
        </div>
        <script>
            var theUrl = '';

            function setPaymentFormTarget(gate) {
                if (gate == 0) {
                    theUrl = "paymentpaypal";
                } else if (gate == 1) {
                    theUrl = "paymentcreditcard";
                } else if (gate == 2) {
                    theUrl = "paymentviaadmin";
                } else if (gate == 3) {
                    theUrl = "req_public_user";
                }
            }

            function acceptRequst(numeroRequest) {
                window.Livewire.dispatch('AcceptRequest', [numeroRequest]);
            }

            function rejectRequst(numeroRequest) {
                Swal.fire({
                    title: `{{trans('reject_request')}}`,
                    confirmButtonText: '{{trans('Yes')}}',
                    showCancelButton: true,
                    cancelButtonText: '{{trans('No')}}',
                    customClass: {actions: 'my-actions', confirmButton: 'order-2', denyButton: 'order-3',}
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.Livewire.dispatch('RejectRequest', [numeroRequest]);
                    }
                })
            }


            function ConfirmExchangeSms() {
                var soldeBFS = {{ $soldeBFS}};
                var nbSMS = $("#NSMS").val();
                var soldeExchange = $("#soldeSMS").val();
                if (Number.isNaN(nbSMS) || Number.isNaN(soldeExchange)) return;
                if (soldeExchange < 0) return;
                if (soldeExchange == 0) {
                    Swal.fire({
                        title: '{{trans('Please enter the transfer amount!')}}',
                        icon: "warning",
                        showCancelButton: false,
                        confirmButtonText: '{{trans('ok')}}',
                    })
                    return;
                }
                var newSolde = soldeBFS - soldeExchange;
                if (newSolde < 0) {
                    Swal.fire({
                        title: '{{trans('BFS_not_allow')}}',
                        icon: "warning",
                        showCancelButton: false,
                        confirmButtonText: '{{trans('ok')}}',
                    })
                    return;

                }
                Swal.fire({
                    title: '{{trans('Are you sure to exchange')}}' + soldeExchange + '{{trans('BFS To SMS ?')}}',
                    text: '{{trans('operation_irreversible')}}',
                    icon: "warning",
                    showCancelButton: true,
                    cancelButtonText: '{{trans('canceled')}}',
                    confirmButtonText: '{{trans('ok')}}',
                    denyButtonText: 'No',
                    customClass: {
                        actions: 'my-actions',
                        cancelButton: 'order-1 right-gap',
                        confirmButton: 'order-2',
                        denyButton: 'order-3',
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.Livewire.dispatch('PreExchangeSMS');
                    } else if (result.isDenied) {
                        Swal.fire('Changes are not saved', '', 'info')
                    }
                })
            }

            function cancelRequestF(numReq) {
                Swal.fire({
                    title: `{{trans('cancel_request')}}`,
                    confirmButtonText: '{{trans('Yes')}}',
                    showCancelButton: true,
                    cancelButtonText: '{{trans('No')}}',
                    denyButtonText: 'No',
                    customClass: {actions: 'my-actions', confirmButton: 'order-2', denyButton: 'order-3',}
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.Livewire.dispatch('DeleteRequest', [numReq]);
                    }
                })
            }

            function hiddenTr(num) {
                $("#" + num).prop("hidden", !$("#" + num).prop("hidden"));
            }

            function ShowCanceledRequest() {
                if (document.getElementById('ShowCanceled').checked) {
                    window.Livewire.dispatch('ShowCanceled', [1])
                } else {
                    window.Livewire.dispatch('ShowCanceled', [0])
                }
            }
        </script>
        <script type="module">
            var timerInterval;
            var mnt = {{$testprop}};
            var prixSms = {{$prix_sms}};
            var soldeBFS = {{$soldeBFS}};
            var inputMontantSms = $("#soldeSMS");
            var inputSms = $("#NSMS");
            var inputsoldeBFSSMS = $("#soldeBFSSMS");
            var inputsoldeBFS = $("#soldeBFS");
            var Mymnt = {{$soldeExchange}};
            var newmntBFS = soldeBFS + Mymnt;
            inputsoldeBFS.val(newmntBFS.toFixed(2));
            // $("#montantExchange").keyup(function () {
            //     inputsoldeBFS.val(parseFloat($(this).val()));
            // })
            inputSms.val(mnt);
            var mntSms = mnt * prixSms;
            var newsoldeBFS = soldeBFS - mntSms
            inputMontantSms.val(mntSms.toFixed(2));
            inputsoldeBFSSMS.val(newsoldeBFS.toFixed(2));
            $("#NSMS").keyup(function () {
                var montantSms = $(this).val() * prixSms;
                inputMontantSms.val(montantSms.toFixed(2));
                var newsolde = soldeBFS - montantSms;
                newsoldeBFS = soldeBFS - montantSms;
                inputsoldeBFSSMS.val(newsolde.toFixed(2));
                if (montantSms == 0) {
                    $("#submitExchangeSms").prop('disabled', true);
                } else {
                    $("#submitExchangeSms").prop('disabled', false);
                }
            });

            $("#NSMS").keyup(function () {
                var montantSms = $(this).val() * prixSms;
                inputMontantSms.val(montantSms.toFixed(2));
                var newsolde = soldeBFS - montantSms;
                newsoldeBFS = soldeBFS - montantSms;
                inputsoldeBFSSMS.val(newsolde.toFixed(2));
                if (montantSms == 0) {
                    $("#submitExchangeSms").prop('disabled', true);
                } else {
                    $("#submitExchangeSms").prop('disabled', false);
                }
            });
            $("#soldeSMS").focusout(function () {
                var sms = $("#NSMS").val();
                var mnt = sms * prixSms;
                inputMontantSms.val(mnt.toFixed(2));
                var newsolde = soldeBFS - mnt;
                newsoldeBFS = soldeBFS - mnt;
                inputsoldeBFSSMS.val(newsolde.toFixed(2));
            });
            $("#submitExchangeSms").prop('disabled', true);

            window.addEventListener('OptExBFSCash', event => {
                Swal.fire({
                    title: '{{ __('Your verification code') }}',
                    html: '{{ __('We_will_send') }}<br>' + event.detail[0].FullNumber + '<br>' + '{{ __('Your OTP Code') }}',
                    input: 'text',
                    allowOutsideClick: false,
                    timer: '{{ env('timeOPT',180000) }}',
                    timerProgressBar: true,
                    confirmButtonText: '{{trans('ok')}}',
                    showCancelButton: true,
                    cancelButtonText: '{{trans('canceled !')}}',
                    footer: ' <i></i><div class="footerOpt"></div>',
                    didOpen: () => {
                        const b = Swal.getFooter().querySelector('i');
                        Swal.getFooter().querySelector('div').classList.add("row");
                        const p22 = Swal.getFooter().querySelector('div');
                        timerInterval = setInterval(() => {
                            p22.innerHTML = '<div class="col-12">{{trans('It will close in')}}' + ' ' + (Swal.getTimerLeft() / 1000).toFixed(0) + ' ' + '{{trans('secondes')}}' + '</br> ' + '{{trans('Dont get code?') }}' + ' <a>' + '{{trans('Resend')}}' + '</a> </div>'
                        }, 100)
                    },
                    willClose: () => {
                        clearInterval(timerInterval)
                    },
                    input: 'text',
                    inputAttributes: {autocapitalize: 'off'},
                }).then((resultat) => {
                    if (resultat.value) {
                        window.Livewire.dispatch('ExchangeCashToBFS', [resultat.value]);
                    }
                })
            })


            window.addEventListener('confirmSms', event => {
                Swal.fire({
                    title: '{{ __('Your verification code') }}',
                    html: '{{ __('We_will_send') }}<br> ',
                    html: '{{ __('We_will_send') }}<br>' + event.detail[0].FullNumber + '<br>' + '{{ __('Your OTP Code') }}',
                    input: 'text',
                    allowOutsideClick: false,
                    timer: '{{ env('timeOPT',180000) }}',
                    timerProgressBar: true,
                    confirmButtonText: '{{trans('ok')}}',
                    showCancelButton: true,
                    cancelButtonText: '{{trans('canceled !')}}',
                    footer: '<div class="footerOpt"></div>',
                    inputAttributes: {
                        autocapitalize: 'off'
                    },
                    didOpen: () => {
                        const b = Swal.getFooter().querySelector('i')
                        const p22 = Swal.getFooter().querySelector('div')
                        timerInterval = setInterval(() => {
                            p22.innerHTML = '{{trans('It will close in')}}' + (Swal.getTimerLeft() / 1000).toFixed(0) + '{{trans('secondes')}}' + '</br>' + '{{trans('Dont get code?') }}' + ' <a>' + '{{trans('Resend')}}' + '</a>'
                        }, 100)
                    },
                    willClose: () => {
                        clearInterval(timerInterval)
                    },
                }).then((resultat) => {
                    if (resultat.value) {
                        window.Livewire.dispatch('exchangeSms', [resultat.value, $("#NSMS").val()]);
                    }
                    if (resultat.isDismissed) {
                        location.reload();
                    }
                })
            })
        </script>
        <script type="module">
            document.addEventListener("DOMContentLoaded", function () {


                var triggerTabList = [].slice.call(document.querySelectorAll('#pills-tab a'))
                triggerTabList.forEach(function (triggerEl) {
                    var tabTrigger = new bootstrap.Tab(triggerEl)
                    triggerEl.addEventListener('click', function (event) {
                        var x = triggerEl.id;
                        if (triggerEl.id === "pills-contact-tab") {
                            $.ajax({
                                url: "{{ route('reset_incoming_notification') }}",
                                type: 'get',
                                success: function (result) {
                                    try {
                                        document.getElementById('pIn').innerHTML = "";
                                        document.getElementById('btnNotRequestInOpen').remove();
                                    } catch (e) {
                                    }
                                    try {
                                        document.getElementById('sideNotIn').innerHTML = "";
                                        document.getElementById('sideNotIn').remove();
                                    } catch (e) {
                                    }
                                }
                            });
                        }
                        if (triggerEl.id === "pills-profile-tab") {
                            $.ajax({
                                url: "{{ route('reset_out_going_notification') }}",
                                type: 'get',
                                success: function (result) {
                                    try {
                                        document.getElementById('pOutAccepted').innerHTML = "";
                                        document.getElementById('btnNotRequestOutAcccepted').remove();
                                    } catch (e) {
                                    }
                                    try {
                                        document.getElementById('pOutRefused').innerHTML = "";
                                        document.getElementById('btnNotRequestOutRefused').remove();
                                    } catch (e) {
                                    }
                                    try {
                                        document.getElementById('sideNotOutRefused').innerHTML = "";
                                        document.getElementById('sideNotOutRefused').remove();
                                    } catch (e) {
                                    }
                                    try {
                                        document.getElementById('sideNotOutAccepted').innerHTML = "";
                                        document.getElementById('sideNotOutAccepted').remove();
                                    } catch (e) {
                                    }
                                }
                            });
                        }
                    })
                })
            });
            $("#pay").click(function () {
                var amount = $("#amount").val();
                if (!(amount) || (amount == 0)) {
                    swal.fire({
                        title: `{{trans('the funding amount field can not be empty or 0! Please enter a valid amount!')}}`,
                        icon: "error",
                        confirmButtonText: '{{trans('Yes')}}'
                    });
                    return;
                }
                if ((!$("#paypal").is(':checked')) && (!$("#creditCard").is(':checked')) && (!$("#upline").is(':checked')) && (!$("#publicUser").is(':checked'))) {
                    swal.fire({
                        title: `{{trans('choose_payment_option')}}`,
                        icon: "error",
                        confirmButtonText: '{{trans('Yes')}}'
                    });
                    return;
                }
                window.Livewire.dispatch('redirectPay', [theUrl, amount]);
            });
            var lan = "{{config('app.available_locales')[app()->getLocale()]['tabLang']}}";
            var urlLang = "https://cdn.datatables.net/plug-ins/1.12.1/i18n/" + lan + ".json";
            $('#customerTable2').DataTable({
                order: [[1, 'desc']],
                "language": {"url": urlLang},
            });
        </script>
    </div>
</div>
