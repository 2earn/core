<div>
    @section('title')
        {{ __('Cash Balance') }}
    @endsection
    @section('content')
        @component('components.breadcrumb')
            @slot('li_1')
            @endslot
            @slot('title')
                {{ __('Cash Balance') }}
            @endslot
        @endcomponent
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row g-4">
                            <div class="col-sm-12 col-md-4 col-lg-2 col-xl-2">
                                <img src=" {{ asset('assets/images/qr_code.jpg') }}"
                                     class="img-fluid img-thumbnail rounded avatar-lg">
                            </div>
                            <div class="col-sm-6 col-md-8 col-lg-10 col-xl-10">
                                <div class="search-box ms-2">
                                    <p>{!! __('You can replenish your Cash Balance through various payment methods') !!}</p>
                                    <ol>
                                        <li>{{__('Bank transfer to Al Rajhi account: 379000010006080004540, IBAN: SA5680000379000010006080004540')}}</li>
                                        <li>{{__('Use of Visa and MasterCard.')}}</li>
                                        <li>{{__('Payment via ApplePay.')}}</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        <div class="card border card-border-info">
                            <div class="card-body row">
                                <div class="col-sm-12 col-md-6 col-lg-3">
                                    <img id="logo-paytabs" src="{{ URL::asset('assets/images/paytabs.jpeg') }}"
                                         class="rounded mx-auto d-block"/>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-3">
                                    <img id="logo-pay" src="{{ URL::asset('assets/images/pay.jpeg') }}"
                                         class="rounded mx-auto d-block"/>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-4">
                                    <div class="input-group" id="validate-group">
                                        <input aria-describedby="simulate" type="number" class="form-control"
                                               id="ammount1" placeholder="{{__('Put your solde here')}}" required>
                                        <span class="input-group-text">$</span>
                                        <button class="btn btn-success" type="button" data-bs-target="#tr_paytabs"
                                                data-bs-toggle="modal" id="validate">{{ __('validate') }}
                                        </button>
                                    </div>
                                    <div class="input-group d-none">
                                        <input aria-describedby="simulate" type="number" class="form-control"
                                               id="ammount2" required>
                                        <span class="input-group-text">{{__('SAR')}}</span>
                                    </div>
                                    <div class="input-group">
                                        <button class="btn btn-outline-secondary d-none" type="button"
                                                id="simulate1">{{ __('simulate') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-body table-responsive">
                        <table class="table nowrap dt-responsive align-middle table-hover table-bordered" id="ub_table"
                               style="width: 100%">
                            <thead class="table-light">
                            <tr class="head2earn  tabHeader2earn">
                                <th>{{ __('Ref') }}</th>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Operation Designation') }}</th>
                                <th>{{ __('Description') }}</th>
                                <th>{{ __('Value') }}</th>
                                <th>{{ __('Balance') }}</th>
                            </tr>
                            </thead>
                            <tbody class="body2earn">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="tr_paytabs" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalgridLabel">{{ __('Cash transfert') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h5>{{ __('validate_transfert') }}</h5>
                        <h5 class="text-secondary">
                            <div class="row" id="usd"></div>
                        </h5>
                        <form class="needs-validation" novalidate>
                            <div class="row g-3">
                                <div class="col-lg-12">
                                    <div class="hstack gap-2 justify-content-end">
                                        <button type="button" class="btn btn-light"
                                                data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                                        <button type="button" id="tran_paytabs" class="btn btn-primary">
                                            {{ __('Submit') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script>
            window.onload = function () {
                if ("{{ getUsertransaction(Auth()->user()->idUser)[0] }}" !== "null") {
                    if ({{ getUsertransaction(Auth()->user()->idUser)[0] }} === 1)
                        Swal.fire({
                            title: "{{ __('Transarction Accepted') }}",
                            text: "{{ getUsertransaction(Auth()->user()->idUser)[2] }}" + "$ Transfered",
                            icon: "success"
                        });
                    else
                        Swal.fire({
                            title: "{{ __('Transarction declined') }}",
                            text: "{{ __(getUsertransaction(Auth()->user()->idUser)[1]) }}",
                            icon: "error"
                        });
                }
            };

            $(document).on("click", "#validate", function () {
                const usd = document.getElementById("usd");

                usd.innerHTML = '<div class="fs-20 ff-secondary fw-semibold mb-0 mt-2 col-12">' + $("#ammount1").val() + ' USD</div><div class="fs-20 ff-secondary fw-semibold mb-0 mt-2">{{__('to')}}</div><div class="fs-20 ff-secondary fw-semibold mb-0 mt-2 col-12">' + $("#ammount1").val() * {{ usdToSar() }} + ' SQR</div>';

                $('#ammount2').val($("#ammount1").val() * {{ usdToSar() }});
            });
            $(document).on("click", "#tran_paytabs", function () {
                this.disabled = true;
                $('#ammount2').val($("#ammount1").val() * {{ usdToSar() }});
                var amount = $('#ammount2').val();
                var routeUrl = "{{ route('paytabs', app()->getLocale()) }}";
                routeUrl += "?amount=" + encodeURIComponent(amount);
                window.location.href = routeUrl;
            });
        </script>
</div>
