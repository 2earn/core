<div class="tab-pane  @if( $filter=="1" or $filter="" ) active @endif" id="cash_bfs" role="tabpanel">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-gradient bg-primary-subtle align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">
                <i class="ri-exchange-funds-line text-primary me-2"></i>{{ __('BFS Transaction') }}
            </h4>
            <span class="badge bg-primary-subtle text-primary">
                <i class="ri-money-dollar-circle-line me-1"></i>Cash >> BFS
            </span>
        </div>
        <div class="card-body p-4">
            <div class="row g-4">
                <div class="col-xxl-5 mx-auto">
                    <div class="card border border-primary h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded">
                                            <i class="ri-wallet-line fs-4"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="card-title mb-1">{{ __('Enter your amount') }}</h5>
                                    <p class="text-muted mb-0 small">{{ __('Amount to transfer') }}</p>
                                </div>
                            </div>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light">
                                    <i class="ri-money-dollar-circle-line text-primary"></i>
                                </span>
                                <input type="number"
                                       name="montantExchange" id="montantExchange"
                                       wire:model.lazy="soldeExchange"
                                       class="form-control form-control-lg text-center fw-bold"
                                       wire:keyup.debounce="updatetheSoldeExchange()"
                                       placeholder="0.00"
                                       onpaste="handlePaste(event)">
                                <span class="input-group-text bg-light text-muted">
                                    {{config('app.currency')}}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                @if(config('app.available_locales')[app()->getLocale()]['direction']=='ltr')
                    <div class="col-xxl-2 d-none d-xxl-flex align-items-center justify-content-center">
                        <div class="text-center">
                            <div class="avatar-sm mx-auto mb-2">
                                <div class="avatar-title bg-success-subtle text-success rounded-circle">
                                    <i class="ri-arrow-right-line fs-4"></i>
                                </div>
                            </div>
                            <small class="text-muted d-block">{{ __('Transfer') }}</small>
                        </div>
                    </div>
                @else
                    <div class="col-xxl-2 d-none d-xxl-flex align-items-center justify-content-center">
                        <div class="text-center">
                            <div class="avatar-sm mx-auto mb-2">
                                <div class="avatar-title bg-success-subtle text-success rounded-circle">
                                    <i class="ri-arrow-left-line fs-4"></i>
                                </div>
                            </div>
                            <small class="text-muted d-block">{{ __('Transfer') }}</small>
                        </div>
                    </div>
                @endif
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
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-success-subtle text-success rounded">
                                            <i class="ri-shopping-bag-line fs-4"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="card-title mb-1">{{ __('BFS Balance') }}</h5>
                                    <p class="text-muted mb-0 small">{{ __('Your shopping balance') }}</p>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small text-muted mb-1">
                                    <i class="ri-wallet-2-line me-1"></i>{{ __('Actual Solde BFS 100') }}
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="ri-money-dollar-box-line text-muted"></i>
                                    </span>
                                    <input type="number"
                                           name="soldeBFS" id="soldeBFS"
                                           class="form-control text-center bg-light"
                                           wire:model.lazy="soldeBFS"
                                           value="" disabled wire:model="soldeBFS" onpaste="handlePaste(event)">
                                    <span class="input-group-text bg-light text-muted">
                                        {{config('app.currency')}}
                                    </span>
                                </div>
                            </div>

                            <div class="alert alert-success mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="small">
                                        <i class="ri-arrow-up-circle-line me-1"></i>{{ __('New Sold BFS 100') }}
                                    </span>
                                    <h5 class="mb-0 text-success">
                                        {{$newBfsSolde}} {{config('app.currency')}}
                                    </h5>
                                </div>
                            </div>

                            <div class="alert alert-info mb-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="small">
                                        <i class="ri-gift-line me-1"></i>{{ __('Earned Discount') }}
                                    </span>
                                    <h6 class="mb-0 text-info">
                                        {{formatSolde($ernedDiscount,3)}} {{config('app.currency')}}
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-light">
            <div class="d-flex justify-content-end gap-2">
                <button class="btn btn-success btn-lg"
                        onclick="ConfirmExchange()"
                        id="exchange">
                    <i class="ri-exchange-line me-2"></i>{{ __('CASH to BFS exchange') }}
                </button>
            </div>
        </div>
    </div>
    <script type="module">
        var timerInterval;
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
                cancelButtonText: '{{trans('cancel')}}',
                willClose: () => {
                    clearInterval(timerInterval)
                },
                input: 'text',
                inputAttributes: {autocapitalize: 'off'},
            }).then((resultat) => {
                console.log(resultat)
                if (resultat.value) {
                    window.Livewire.dispatch('ExchangeCashToBFS', [resultat.value]);
                }
            })
        })
    </script>
    <script>
        function ConfirmExchange() {
            var soldecashB = {{ $soldecashB}};
            var soldeExchange = document.getElementById('montantExchange').value
            if (Number.isNaN(soldecashB) || Number.isNaN(soldeExchange)) return;
            if (soldeExchange <= 0) {
                Swal.fire({
                    title: '{{trans('Please enter the transfer amount!')}}',
                    icon: "warning",
                    showCancelButton: false,
                    confirmButtonText: '{{trans('ok')}}',
                })
                return;
            }
            var newSolde = soldecashB - soldeExchange;
            if (newSolde < 0) {
                Swal.fire({
                    title: '{{trans('Your_cash_balance')}}',
                    icon: "warning",
                    showCancelButton: false,
                    confirmButtonText: '{{trans('ok')}}',
                })
                return;
            }
            Swal.fire({
                title: '{{trans('Are you sure to exchange ?')}}' + " " + '<br>' + soldeExchange + "$ " + '{{trans('from cash to Bfs 100?')}}',
                text: '{{trans('operation irreversible')}}',
                icon: "warning",
                showCancelButton: true,
                cancelButtonText: '{{trans('cancel')}}',
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
                    window.Livewire.dispatch('PreExchange');
                } else if (result.isDenied) {
                    Swal.fire('Changes are not saved', '', 'info')
                }
            })
        }
    </script>
</div>

