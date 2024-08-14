<div>
    @component('components.breadcrumb')
        @slot('title')
            {{ __('ConfigurationHA') }}
        @endslot
    @endcomponent
    <div class="row">
        <div class="card">
            <div class="card-body">
                <table class="table table-striped table-bordered cell-border row-border table-hover mdl-data-table display nowrap" id="amountsTable"
                       style="width: 100%">
                    <thead class="table-light">
                    <tr>
                        <th>{{ __('Name of setting') }}</th>
                        <th>{{ __('ShortName') }}</th>
                        <th>{{ __('WithHoldinTax') }}</th>
                        <th>{{ __('transfer') }}</th>
                        <th>{{ __('PaymentRequest') }}</th>
                        <th>{{ __('Cash') }}</th>
                        <th>{{ __('Active') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                    </thead>
                    <tbody class="list form-check-all">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="AmountsModal" tabindex="-1" style="z-index: 200000"
         aria-labelledby="AmountsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="AmountsModalLabel">{{__('saveAmounts')}}</h5>
                    <button type="button" class="btn-close btn-close-amount" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">

                            <div class="mb-3 col-xl-5">
                                <label class="me-sm-2">{{ __('Amount Name') }}</label>
                                <input wire:model.defer="amountsnameAm" type="text" class="form-control"
                                       placeholder="amountsname" name="amountsname">
                            </div>
                            <div class="mb-3 col-xl-5">
                                <label class="me-sm-2">{{ __('Amount Short Name') }}</label>
                                <input wire:model.defer="amountsshortnameAm" type="text" class="form-control"
                                       placeholder="amountsshortname" name="amountsshortname">
                            </div>

                            <div class="mb-3 col-xl-3">
                                <label class="me-sm-2">{{ __('With Holding Tax') }}</label>
                                <select wire:model.defer="amountswithholding_taxAm" class="form-control"
                                        name="amountswithholding_tax">
                                    <option value="0">{{ __('No') }}</option>
                                    <option value="1">{{ __('Yes') }}</option>
                                </select>
                            </div>
                            <div class="mb-3 col-xl-2">
                                <label class="me-sm-2">{{ __('Payment Request') }}</label>
                                <select wire:model.defer="amountspaymentrequestAm" class="form-control"
                                        name="amountspaymentrequest">
                                    <option value="0">{{ __('No') }}</option>
                                    <option value="1">{{ __('Yes') }}</option>
                                </select>
                            </div>
                            <div class="mb-3 col-xl-2">
                                <label class="me-sm-2">{{ __('Transfer') }}</label>
                                <select wire:model.defer="amountstransferAm" class="form-control"
                                        name="amountstransfer">
                                    <option value="0">{{ __('No') }}</option>
                                    <option value="1">{{ __('Yes') }}</option>
                                </select>
                            </div>
                            <div class="mb-3 col-xl-2">
                                <label class="me-sm-2">{{ __('Cash') }}</label>
                                <select wire:model.defer="amountscashAm" class="form-control" name="amountscash">
                                    <option value="0">{{ __('No') }}</option>
                                    <option value="1">{{ __('Yes') }}</option>
                                </select>
                            </div>
                            <div class="mb-3 col-xl-2">
                                <label class="me-sm-2">{{ __('Active') }}</label>
                                <select wire:model.defer="amountsactiveAm" class="form-control" name="amountsactive">
                                    <option value="0">{{ __('No') }}</option>
                                    <option value="1">{{ __('Yes') }}</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" wire:click="saveAmounts" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <script type="module">
        function emitAmount(idAmount) {
            if (idAmount) {
                window.Livewire.emit('initAmountsFunction', idAmount);
            }
        }

        $(document).on('turbolinks:load', function () {

            $('#amountsTable').DataTable(
                {
                    retrieve: true,
                    "colReorder": true,
                    "orderCellsTop": true,
                    "fixedHeader": true,
                    initComplete: function () {
                        this.api()
                            .columns()
                            .every(function () {
                                var that = $('#amountsTable').DataTable();
                                $('input', this.footer()).on('keydown', function (ev) {
                                    if (ev.keyCode == 13) {
                                        that
                                            .search(this.value)
                                            .draw();
                                    }
                                });
                            });
                    },
                    "processing": true,
                    search: {return: true},
                    "ajax": "{{route('api_Amounts',app()->getLocale())}}",
                    "columns": [
                        {data: 'amountsname'},
                        {data: 'amountsshortname'},
                        {data: 'amountswithholding_tax'},
                        {data: 'amountstransfer'},
                        {data: 'amountspaymentrequest'},
                        {data: 'amountscash'},
                        {data: 'amountsactive'},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ],
                    "language": {"url": urlLang},
                    "drawCallback": function (settings, json) {
                        $(".edit-amounts-btn").each(function () {
                            $(this).on("click", function () {
                                emitAmount($(this).attr('data-id'))
                            });
                        });
                    },
                }
            );

            window.addEventListener('closeModalAmounts', event => {
                $('.btn-close-amount').trigger('click');
                $('#amountsTable').DataTable().ajax.reload();
            });
        });
    </script>
</div>








