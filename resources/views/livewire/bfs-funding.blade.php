<div class="tab-pane @if($filter=="2" ) active show @endif" id="bfs_funding" role="tabpanel">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('BFS account funding') }}</h4>
        </div>
        <div class="card-body">
            <div class="row gy-4">
                <div class="col-xxl-8 mx-auto ">
                    <div class="input-group">
                        <span class="input-group-text" id="inputGroup-sizing-default">
                            {{ __('backand_Amount_to_Fund_in_DCP') }}
                        </span>
                        <input type="number" name="fundAmountTXT" id="amount" class="form-control text-center"
                               placeholder="{{ __('backand_Enter_the_funding_amount') }}" onpaste="handlePaste(event)">
                    </div>
                </div>
                <div class="col-xxl-8 mx-auto text-center ">
                    <h5 class="mb-5 text-center "> {{ __('backand_Choose_payment_option') }}</h5>
                    <div class="form-check form-check-inline d-none">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions"
                               value="paypal" id="paypal" onclick="setPaymentFormTarget(0)">
                        <label class="form-check-label fs-5 text-primary"                               for="paypal">
                            <i class="ri-paypal-fill me-2 "></i>
                            {{__('Paypal')}}</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions"
                               value="creditCard" id="creditCard"
                               onclick="setPaymentFormTarget(1)">
                        <label class="form-check-label fs-5 text-success "
                               for="creditCard">
                            <i class="ri-bank-card-fill me-2 "></i>
                            {{__('Creditcard')}}
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions"
                               value="publicUser" id="publicUser"
                               onclick="setPaymentFormTarget(3)">
                        <label class="form-check-label fs-5 text-danger "
                               for="publicUser">
                            <i class="ri-team-fill me-2"></i>
                            {{__('PublicUsers')}}
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions"
                               value="upline" id="upline" onclick="setPaymentFormTarget(2)">
                        <label class="form-check-label fs-5 text-warning"
                               for="upline">
                            <i class="ri-user-2-fill me-2 "></i>
                            {{__('requstAdmin')}}
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-soft-primary mt-3 float-end" id="pay">
                {{ __('BFS (100.00) founding') }}
            </button>
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
