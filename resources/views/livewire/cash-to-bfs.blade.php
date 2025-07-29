<div class="tab-pane  @if( $filter=="1" or $filter="" ) active @endif" id="cash_bfs" role="tabpanel">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">{{ __('BFS Transaction') }}</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-xxl-4 mx-auto ">
                    <div class="input-group">
                    <span class="input-group-text" id="inputGroup-sizing-default">
                        {{ __('Enter your amount') }}
                    </span>
                        <input type="number"
                               name="montantExchange" id="montantExchange"
                               wire:model.lazy="soldeExchange" onpaste="handlePaste(event)"
                               class="form-control text-center"
                               placeholder="{{ __('Enter your amount') }}" onpaste="handlePaste(event)">
                    </div>
                </div>
                @if(config('app.available_locales')[app()->getLocale()]['direction']=='ltr')
                    <div class="col-1 mx-auto d-none d-xxl-block ">
                        <i class="ri-arrow-right-s-line fs-3 me-n3 text-primary"></i>
                        <i class="ri-arrow-right-s-line fs-3 ms-n1  text-primary"></i>
                    </div>
                @else
                    <div class="col-1 mx-auto d-none d-xxl-block ">
                        <i class="ri-arrow-left-s-line fs-3 me-n3 text-primary"></i>
                        <i class="ri-arrow-left-s-line fs-3 ms-n1  text-primary"></i>
                    </div>
                @endif
                <div class="col-1 mx-auto d-xxl-none">
                    <i class=" ri-download-line fs-3 mt-n3 text-primary"></i>
                </div>
                <div class="col-xxl-4 mx-auto ">
                    <div class="input-group">
                    <span class="input-group-text" id="inputGroup-sizing-default">
                        {{ __('BFS 100') }}
                    </span>
                        <input type="number"
                               name="soldeBFS" id="soldeBFS" class="form-control text-center" wire:model.lazy="soldeBFS"
                               value="" disabled wire:model="newBfsSolde" onpaste="handlePaste(event)">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-soft-primary float-end"
                    onclick="ConfirmExchange()"
                    id="exchange">{{ __('CASH to BFS exchange') }}</button>
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
                title: '{{trans('Are you sure to exchange ?')}}' + " " + '<br>' + soldeExchange + "$ " + '{{trans('?')}}',
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
