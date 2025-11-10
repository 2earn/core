<div class="{{getContainerType()}}">
    <div>
        @section('title')
            {{ __('Cash Balance') }}
        @endsection
        @component('components.breadcrumb')
            @slot('title')
                {{ __('Cash Balance') }}
            @endslot
        @endcomponent

        <div class="row card">
            <div class="card-body">
                <div class="col-lg-12">
                    <div class="row g-4">
                        <div class="col-sm-12 col-md-4 col-lg-2 col-xl-2">
                            <img src="{{ Vite::asset('resources/images/qr_code.jpg') }}"
                                 class="img-fluid img-thumbnail rounded avatar-lg" alt="QR code">
                        </div>
                        <div class="col-sm-12 col-md-8 col-lg-10 col-xl-10">
                            <div class="search-box ms-2">
                                <p>{!! __('You can replenish your Cash Balance through various payment methods') !!}</p>
                                <ol>
                                    <li>{{ __('Bank transfer to Al Rajhi account: 379000010006080004540, IBAN: SA5680000379000010006080004540') }}</li>
                                    <li>{{ __('Use of Visa and MasterCard.') }}</li>
                                    <li>{{ __('Payment via ApplePay.') }}</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <div class="card border card-border-info">
                        <div class="card-body row">
                            <div class="col-sm-12 col-md-6 col-lg-3">
                                <img id="logo-paytabs" src="{{ Vite::asset('resources/images/paytabs.jpeg') }}"
                                     class="rounded mx-auto d-block" alt="Paytabs logo"/>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-3">
                                <img id="logo-pay" src="{{ Vite::asset('resources/images/pay.jpeg') }}"
                                     class="rounded mx-auto d-block" alt="Pay logo"/>
                            </div>

                            <div class="col-sm-12 col-md-12 col-lg-4">
                                <div class="input-group" id="validate-group">
                                    <input aria-describedby="simulate" type="number" class="form-control"
                                           id="amount1" name="amount1" placeholder="{{ __('Put your balance here') }}" required min="1" max="10000" step="0.01">
                                    <span class="input-group-text">$</span>
                                    <button class="btn btn-success" type="button" data-bs-target="#tr_paytabs"
                                            data-bs-toggle="modal" id="validate">{{ __('Validate') }}
                                    </button>
                                </div>

                                <div class="input-group d-none">
                                    <input aria-describedby="simulate" type="number" class="form-control"
                                           id="amount2" name="amount2" required readonly>
                                    <span class="input-group-text">{{ __('SAR') }}</span>
                                </div>

                                <div class="input-group">
                                    <button class="btn btn-outline-secondary d-none" type="button"
                                            id="simulate1">{{ __('Simulate') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body table-responsive">
                        <table class="table table-striped table-bordered cell-border row-border table-hover mdl-data-table display nowrap"
                               id="ub_table" style="width: 100%">
                            <thead class="table-light">
                            <tr class="head2earn tabHeader2earn">
                                <th>{{__('Details')}}</th>
                                <th>{{ __('Operation order') }}</th>
                                <th>{{ __('ref') }}</th>
                                <th>{{ __('date') }}</th>
                                <th>{{ __('Operation Designation') }}</th>
                                <th>{{ __('Value') }}</th>
                                <th>{{ __('Balance') }}</th>
                                <th>{{ __('Complementary information') }}</th>
                            </tr>
                            </thead>
                            <tbody class="body2earn"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="tr_paytabs" tabindex="-1" aria-labelledby="trPaytabsLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="trPaytabsLabel">{{ __('Cash transfer') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h5>{{ __('validate_transfert') }}</h5>
                        <div class="alert alert-primary material-shadow" role="alert">
                            {{ __('The amount must be from 1 and less than 10000') }}
                        </div>

                        <div id="usd" class="text-secondary" aria-live="polite"></div>

                        <form class="needs-validation" novalidate>
                            <div class="row g-3">
                                <div class="col-lg-12">
                                    <div class="hstack gap-2 justify-content-end">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                                        <button type="button" id="tran_paytabs" class="btn btn-primary">{{ __('Submit') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @php
            // Prepare server-side values safely for serialization to JS
            $currentUserId = optional(Auth::user())->idUser ?? null;
            try {
                $userTransactionServer = getUsertransaction($currentUserId);
            } catch (\Throwable $e) {
                $userTransactionServer = null;
            }
            $usdRateServer = usdToSar() ?? 0;
            $paytabsRouteServer = route('paytabs', app()->getLocale());
            $balancesApiUrlServer = route('api_user_balances', ['locale' => app()->getLocale(), 'idAmounts' => 'cash-Balance']);
            $userTokenServer = generateUserToken();
        @endphp

        <script type="module">
            // Server values serialized safely
            const userTransaction = @json($userTransactionServer ?? null);
            const usdRate = Number(@json($usdRateServer));
            const paytabsRoute = @json($paytabsRouteServer);
            const balancesApiUrl = @json($balancesApiUrlServer);
            const userToken = @json($userTokenServer);

            // Cache USD DOM element
            const $usd = $('#usd');
            const usdEl = $usd.length ? $usd[0] : null;

            // Show transaction result if available
            window.addEventListener('load', function () {
                try {
                    console.log(userTransaction)
                    if (userTransaction && userTransaction[0] !== null && userTransaction[0] !== undefined) {
                        if (Number(userTransaction[0]) === 1) {
                            Swal.fire({
                                title: "{{ __('Transaction Accepted') }}",
                                text: String(userTransaction[2]) + " $ {{ __('Transferred') }}",
                                icon: "success"
                            });
                        }
                    }
                } catch (e) {
                    console.log(e)
                }
            });

            // Update SAR equivalent and show summary when user clicks Validate
            $(document).on('click', '#validate', function () {
                const amountInput = document.getElementById('amount1');
                const rawVal = Number(amountInput.value) || 0;

                if (usdEl) usdEl.classList.remove('text-success', 'text-danger');

                const sarVal = +(rawVal * (isFinite(usdRate) ? usdRate : 0));
                amountInput.value = rawVal ? rawVal.toFixed(2) : '';
                const amount2 = document.getElementById('amount2');
                if (amount2) amount2.value = sarVal ? sarVal.toFixed(2) : '';

                if (usdEl) {
                    usdEl.innerHTML = '';
                    usdEl.innerHTML += '<div class="fs-20 ff-secondary fw-semibold mb-0 mt-2 col-12">' + (rawVal ? rawVal.toFixed(2) : '0.00') + ' USD</div>';
                    usdEl.innerHTML += '<div class="fs-20 ff-secondary fw-semibold mb-0 mt-2">{{ __('to') }}</div>';
                    usdEl.innerHTML += '<div class="fs-20 ff-secondary fw-semibold mb-0 mt-2 col-12">' + (sarVal ? sarVal.toFixed(2) : '0.00') + ' SAR</div>';
                }
            });

            // Submit once to redirect to paytabs using SAR amount
            $(document).on('click', '#tran_paytabs', function () {
                const btn = this;
                const amountUsd = Number(document.getElementById('amount1').value) || 0;
                const amountSar = Number(document.getElementById('amount2').value) || 0;

                if (amountUsd <= 0 || amountSar <= 0) {
                    $usd.addClass('text-danger').removeClass('text-success');
                    return;
                }
                if (amountUsd > 10000) {
                    $usd.addClass('text-danger').removeClass('text-success');
                    return;
                }

                btn.setAttribute('disabled', 'disabled');
                $usd.removeClass('text-danger').addClass('text-success');

                try {
                    const url = new URL(paytabsRoute, window.location.origin);
                    url.searchParams.set('amount', amountSar.toFixed(2));
                    window.location.href = url.toString();
                } catch (e) {
                    // fallback: build simple query string
                    window.location.href = paytabsRoute + '?amount=' + encodeURIComponent(amountSar.toFixed(2));
                }
            });

            // Initialize DataTable
            document.addEventListener('DOMContentLoaded', function () {
                $('#ub_table').DataTable({
                    responsive: true,
                    ordering: true,
                    retrieve: true,
                    searching: false,
                    orderCellsTop: true,
                    fixedHeader: true,
                    order: [[1, 'desc']],
                    processing: true,
                    serverSide: true,
                    aLengthMenu: [[10, 30, 50], [10, 30, 50]],
                    search: { return: false },
                    autoWidth: false,
                    bAutoWidth: false,
                    ajax: {
                        url: balancesApiUrl,
                        type: 'GET',
                        headers: { 'Authorization': 'Bearer ' + userToken },
                        error: function () { loadDatatableModalError('ub_table'); }
                    },
                    columns: [
                        datatableControlBtn,
                        { data: 'ranks' },
                        { data: 'reference' },
                        { data: 'created_at' },
                        { data: 'operation' },
                        { data: 'value' },
                        { data: 'current_balance' },
                        { data: 'complementary_information' }
                    ],
                    columnDefs: [
                        {
                            targets: [5],
                            render: function (data) {
                                const str = (data === null || data === undefined) ? '' : String(data);
                                if (str.indexOf('+') === -1) {
                                    return '<span class="badge bg-danger text-end fs-14">' + str + '</span>';
                                }
                                return '<span class="badge bg-success text-end fs-14">' + str + '</span>';
                            },
                            className: (typeof classAl !== 'undefined') ? classAl : ''
                        }
                    ],
                    language: { "url": (typeof urlLang !== 'undefined') ? urlLang : '' }
                });
            });
        </script>
    </div>
</div>
