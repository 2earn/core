<div class="tab-pane" id="bfs_sms" role="tabpanel">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('backand_BFS_Account_Funding') }}</h4>
        </div>
        <div class="card-body">
            <div
                class="alert alert-info material-shadow border-0 rounded-top rounded-0 m-0 d-flex align-items-center mb-3">
                <div class="flex-grow-1 text-truncate ">
                    {{ __('SMS price') }} <b>{{ $prix_sms}} </b> {{__('DPC')}}
                </div>
            </div>
            <div class="row gy-4">
                <div class="col-xxl-8 mx-auto ">
                    <div class="input-group">
                                                <span class="input-group-text"
                                                      id="inputGroup-sizing-default">{{ __('Enter number of SMS') }}</span>
                        <input type="number"
                               oninput="this.value = this.value.replace(/[^0-9]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                               name="NSMS" id="NSMS"
                               class="form-control text-center" placeholder=""
                               onpaste="handlePaste(event)">
                        <span class="input-group-text"
                              id="inputGroup-sizing-default">{{ __('Enter your amount') }}</span>
                        <input type="number" name="soldeSMS" id="soldeSMS"
                               disabled
                               class="form-control text-center"
                               placeholder="{{ __('Enter your amount') }}"
                               onpaste="handlePaste(event)">
                    </div>
                </div>
                <div class="col-xxl-8 mx-auto text-center ">
                    <div class="input-group">
                                                <span class="input-group-text"
                                                      id="inputGroup-sizing-default">{{ __('Balance For Shopping') }} : (100.00%)</span>
                        <input type="number" name="soldeBFSSMS" id="soldeBFSSMS"
                               class="form-control text-center" disabled>
                    </div>
                </div>

            </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-soft-primary float-end mt-3" id="submitExchangeSms"
                    onclick="ConfirmExchangeSms()">
                {{ __('Exchange Now') }}
            </button>
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
