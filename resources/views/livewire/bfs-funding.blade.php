<div class="tab-pane @if($filter=="2" ) active show @endif" id="bfs_funding" role="tabpanel">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-gradient bg-info-subtle align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">
                <i class="ri-wallet-3-line text-info me-2"></i>{{ __('BFS account funding') }}
            </h4>
            <span class="badge bg-info-subtle text-info">
                <i class="ri-shopping-bag-line me-1"></i>BFS 100
            </span>
        </div>
        <div class="card-body p-4">
            <div class="row g-4">
                <div class="col-xxl-5 mx-auto">
                    <div class="card border border-info h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-4">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-info-subtle text-info rounded">
                                            <i class="ri-money-dollar-circle-line fs-4"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="card-title mb-1">{{ __('Fill BFS 100 amount') }}</h5>
                                    <p class="text-muted mb-0 small">{{ __('backand_Amount_to_Fund_in_DCP') }}</p>
                                </div>
                            </div>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light">
                                    <i class="ri-hand-coin-line text-info"></i>
                                </span>
                                <input type="number" name="fundAmountTXT" id="amount"
                                       class="form-control form-control-lg text-center fw-bold"
                                       placeholder="0.00"
                                       onpaste="handlePaste(event)">
                                <span class="input-group-text bg-light text-muted">
                                    {{config('app.currency')}}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-7 mx-auto">
                    <div class="card border border-primary h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-4">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded">
                                            <i class="ri-secure-payment-line fs-4"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="card-title mb-1">{{ __('Choose the payment method') }}</h5>
                                    <p class="text-muted mb-0 small">{{ __('Select your preferred option') }}</p>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-12 d-none">
                                    <div class="form-check card mb-0">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center">
                                                <input class="form-check-input me-3" type="radio" name="inlineRadioOptions"
                                                       value="paypal" id="paypal" onclick="setPaymentFormTarget(0)">
                                                <label class="form-check-label flex-grow-1 cursor-pointer" for="paypal">
                                                    <div class="d-flex align-items-center">
                                                        <i class="ri-paypal-fill fs-2 text-primary me-3"></i>
                                                        <div>
                                                            <h6 class="mb-0">{{__('Paypal')}}</h6>
                                                            <small class="text-muted">Pay with PayPal</small>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-check card mb-0 border-success">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center">
                                                <input class="form-check-input me-3" type="radio" name="inlineRadioOptions"
                                                       value="creditCard" id="creditCard"
                                                       onclick="setPaymentFormTarget(1)">
                                                <label class="form-check-label flex-grow-1 cursor-pointer" for="creditCard">
                                                    <div class="d-flex align-items-center">
                                                        <i class="ri-bank-card-fill fs-2 text-success me-3"></i>
                                                        <div>
                                                            <h6 class="mb-0">{{__('Creditcard')}}</h6>
                                                            <small class="text-muted">{{__('Pay with Credit/Debit Card')}}</small>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-check card mb-0 border-danger">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center">
                                                <input class="form-check-input me-3" type="radio" name="inlineRadioOptions"
                                                       value="publicUser" id="publicUser"
                                                       onclick="setPaymentFormTarget(3)">
                                                <label class="form-check-label flex-grow-1 cursor-pointer" for="publicUser">
                                                    <div class="d-flex align-items-center">
                                                        <i class="ri-team-fill fs-2 text-danger me-3"></i>
                                                        <div>
                                                            <h6 class="mb-0">{{__('PublicUsers')}}</h6>
                                                            <small class="text-muted">{{__('Request from public users')}}</small>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-check card mb-0 border-warning">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center">
                                                <input class="form-check-input me-3" type="radio" name="inlineRadioOptions"
                                                       value="upline" id="upline" onclick="setPaymentFormTarget(2)">
                                                <label class="form-check-label flex-grow-1 cursor-pointer" for="upline">
                                                    <div class="d-flex align-items-center">
                                                        <i class="ri-user-2-fill fs-2 text-warning me-3"></i>
                                                        <div>
                                                            <h6 class="mb-0">{{__('requstAdmin')}}</h6>
                                                            <small class="text-muted">{{__('Request via admin')}}</small>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-light">
            <div class="d-flex justify-content-end gap-2">
                <button class="btn btn-info btn-lg" id="pay">
                    <i class="ri-funds-line me-2"></i>{{ __('BFS (100.00) founding') }}
                </button>
            </div>
        </div>
    </div>
    <script>
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

    </script>
    <script type="module">
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
