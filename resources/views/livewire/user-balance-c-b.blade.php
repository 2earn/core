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

                    <div class="card-body">
                        <div id="ub_container">
                            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                                <h5 class="mb-0">{{ __('Transaction History') }}</h5>
                                <select wire:model.live="perPage" class="form-select form-select-sm" style="width: auto;">
                                    <option value="10">10</option>
                                    <option value="30">30</option>
                                    <option value="50">50</option>
                                </select>
                            </div>

                            <div wire:loading class="text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">{{ __('Loading...') }}</span>
                                </div>
                            </div>

                            <div wire:loading.remove>
                                @if($transactions->count() > 0)
                                    <div class="row g-3">
                                        @foreach($transactions as $transaction)
                                            <div class="col-12">
                                                <div class="card border shadow-sm h-100">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
                                                            <div class="flex-grow-1">
                                                                <h6 class="card-title mb-1 fw-bold">{!! $transaction['operation'] ?? '' !!}</h6>
                                                                <small class="text-muted d-block">#{{ $transaction['ranks'] ?? '' }} - {!! $transaction['reference'] !!} </small>
                                                            </div>
                                                            @php
                                                                $value = $transaction['value'] ?? '0';
                                                                $isPositive = strpos($value, '+') !== false;
                                                            @endphp
                                                            <span class="badge {{ $isPositive ? 'bg-success' : 'bg-danger' }} fs-6 px-3 py-2">{{ $value }}</span>
                                                        </div>
                                                        <div class="row g-3">
                                                            <div class="col-6 col-md-3">
                                                                <small class="text-muted d-block mb-1">{{ __('date') }}</small>
                                                                <strong class="d-block">{{ $transaction['created_at'] ?? '-' }}</strong>
                                                            </div>
                                                            <div class="col-6 col-md-3">
                                                                <small class="text-muted d-block mb-1">{{ __('Balance') }}</small>
                                                                <strong class="d-block">{{ $transaction['current_balance'] ?? '-' }}</strong>
                                                            </div>
                                                            <div class="col-12 col-md-6">
                                                                <small class="text-muted d-block mb-1">{{ __('Complementary information') }}</small>
                                                                <span class="d-block text-break">{!! $transaction['complementary_information'] ?? '-' !!}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="alert alert-info text-center">{{ __('No transactions found') }}</div>
                                @endif

                                @if($transactions->hasPages())
                                    <div class="mt-4">
                                        <div class="d-flex justify-content-center">
                                            {{ $transactions->links() }}
                                        </div>
                                        <div class="text-center mt-2">
                                            <small class="text-muted">
                                                {{ __('Showing') }} {{ $transactions->firstItem() ?? 0 }}
                                                {{ __('to') }} {{ $transactions->lastItem() ?? 0 }}
                                                {{ __('of') }} {{ $transactions->total() }} {{ __('entries') }}
                                            </small>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
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
        @endphp

        <script type="module">
            // Server values serialized safely
            const userTransaction = @json($userTransaction ?? null);
            const usdRate = Number(@json($usdRate));
            const paytabsRoute = @json($paytabsRoute);

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
        </script>
    </div>
</div>
