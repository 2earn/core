<div class="tab-pane   @if($filter=="3" ) active show @endif" id="bfs_sms" role="tabpanel">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-gradient bg-warning-subtle align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">
                <i class="ri-message-3-line text-warning me-2"></i>{{ __('backand_BFS_Account_Funding') }}
            </h4>
            <span class="badge bg-warning-subtle text-warning">
                <i class="ri-arrow-right-line me-1"></i>BFS >> SMS
            </span>
        </div>
        <div class="card-body p-4">
            <div class="alert alert-info border-0 shadow-sm mb-4">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-xs">
                            <div class="avatar-title bg-info text-white rounded-circle">
                                <i class="ri-information-line"></i>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0">
                            {{ __('SMS price') }}: <strong class="text-info">{{ $prix_sms}} {{__('DPC')}}</strong>
                        </h6>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-xxl-5 mx-auto">
                    <div class="card border border-warning h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-4">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-warning-subtle text-warning rounded">
                                            <i class="ri-message-2-line fs-4"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="card-title mb-1">{{ __('Enter number of SMS') }}</h5>
                                    <p class="text-muted mb-0 small">How many SMS do you need?</p>
                                </div>
                            </div>
                            <div class="input-group input-group-lg mb-3">
                                <span class="input-group-text bg-light">
                                    <i class="ri-hashtag text-warning"></i>
                                </span>
                                <input type="number"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                       name="NSMS" id="NSMS"
                                       class="form-control form-control-lg text-center fw-bold"
                                       placeholder="0"
                                       onpaste="handlePaste(event)">
                                <span class="input-group-text bg-light text-muted">SMS</span>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small text-muted mb-1">
                                    <i class="ri-money-dollar-circle-line me-1"></i>{{ __('Enter your amount') }}
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="ri-coins-line text-warning"></i>
                                    </span>
                                    <input type="number" name="soldeSMS" id="soldeSMS"
                                           disabled
                                           class="form-control text-center bg-light"
                                           placeholder="0.00"
                                           onpaste="handlePaste(event)">
                                    <span class="input-group-text bg-light text-muted">
                                        {{config('app.currency')}}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-2 d-none d-xxl-flex align-items-center justify-content-center">
                    <div class="text-center">
                        <div class="avatar-sm mx-auto mb-2">
                            <div class="avatar-title bg-success-subtle text-success rounded-circle">
                                <i class="ri-arrow-right-line fs-4"></i>
                            </div>
                        </div>
                        <small class="text-muted d-block">{{ __('Exchange') }}</small>
                    </div>
                </div>
                <div class="col-12 d-xxl-none text-center my-2">
                    <div class="avatar-sm mx-auto">
                        <div class="avatar-title bg-success-subtle text-success rounded-circle">
                            <i class="ri-arrow-down-line fs-4"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-5 mx-auto">
                    <div class="card border border-success h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-4">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-success-subtle text-success rounded">
                                            <i class="ri-shopping-bag-line fs-4"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="card-title mb-1">{{ __('Balance For Shopping') }}</h5>
                                    <p class="text-muted mb-0 small">BFS 100.00%</p>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small text-muted mb-1">
                                    <i class="ri-wallet-2-line me-1"></i>{{ __('Remaining BFS Balance') }}
                                </label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light">
                                        <i class="ri-wallet-3-line text-success"></i>
                                    </span>
                                    <input type="number" name="soldeBFSSMS" id="soldeBFSSMS"
                                           class="form-control form-control-lg text-center bg-light fw-bold"
                                           disabled>
                                    <span class="input-group-text bg-light text-muted">
                                        {{config('app.currency')}}
                                    </span>
                                </div>
                            </div>

                            <div class="alert alert-success mb-0">
                                <div class="d-flex align-items-center">
                                    <i class="ri-checkbox-circle-line fs-18 me-2"></i>
                                    <small>Your BFS balance after exchange will be updated automatically</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-light">
            <div class="d-flex justify-content-end gap-2">
                <button class="btn btn-warning btn-lg" id="submitExchangeSms"
                        onclick="ConfirmExchangeSms()">
                    <i class="ri-exchange-line me-2"></i>{{ __('Exchange Now') }}
                </button>
            </div>
        </div>
    </div>
    <script>
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
                title: '{{trans('Are you sure to exchange')}} ' + parseInt(soldeExchange) + ' {{trans('BFS To SMS ?')}}',
                text: '{{trans('Irreversible operation')}}',
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
    </script>
    <script type="module">
        var timerInterval;
        var mnt = '{{$testprop}}';
        var soldeBFS = {{$soldeBFS}};
        var prixSms = "{{$prix_sms}}";
        var mntSms = mnt * prixSms;
        var newsoldeBFS = soldeBFS - mntSms
        var soldeBFS = '{{$soldeBFS}}';
        var inputMontantSms = $("#soldeSMS");
        var inputSms = $("#NSMS");
        var inputsoldeBFSSMS = $("#soldeBFSSMS");
        var inputsoldeBFS = $("#soldeBFS");
        var Mymnt = '{{$soldeExchange}}';
        var newmntBFS = soldeBFS + Mymnt;
        inputsoldeBFS.val(newmntBFS);

        inputSms.val(mnt);

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
                inputAttributes: {
                    autocapitalize: 'off'
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
</div>
