<div>
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Configuration') }}
        @endslot
    @endcomponent
    <div class="row">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-pills animation-nav nav-justified gap-2 mb-3" role="tablist">
                    <li class="nav-item waves-effect waves-light" role="presentation">
                        <a class="nav-link active" data-bs-toggle="tab" href="#setting" role="tab" aria-selected="true">
                            {{ __('Settings') }}
                        </a>
                    </li>
                    <li class="nav-item waves-effect waves-light" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" href="#profile" role="tab" aria-selected="false"
                           tabindex="-1">
                            {{ __('Balance Operations') }}
                        </a>
                    </li>
                    <li class="nav-item waves-effect waves-light" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" href="#amounts" role="tab" aria-selected="false"
                           tabindex="-1">
                            {{ __('Amounts') }}
                        </a>
                    </li>
                    <li class="nav-item waves-effect waves-light" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" href="#actionHistorys" role="tab" aria-selected="false"
                           tabindex="-1">
                            {{ __('history actions') }}
                        </a>
                    </li>
                </ul>
                <div class="tab-content text-muted">
                    <div class="tab-pane active show" id="setting" role="tabpanel">
                        <div wire:ignore class="card-body">
                            <div id="customerList">
                                <div class="row ">
                                    <div class="col-sm-auto">

                                    </div>
                                    <div class="table-responsive table-card mt-3 mb-1">
                                        <table
                                            class="table table-bordered dt-responsive nowrap table-striped align-middle"
                                            id="SettingsTable" style="width: 100%">
                                            <thead class="table-light">
                                            <tr>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('IntegerValue') }}</th>
                                                <th>{{ __('StringValue') }}</th>
                                                <th>{{ __('DecimalValue') }}</th>
                                                <th>{{ __('Unit') }}</th>
                                                <th>{{ __('AutoCalculated') }}</th>
                                                <th>{{ __('Actions') }}</th>
                                            </tr>
                                            </thead>
                                            <tbody class="list form-check-all">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel">
                        <div wire:ignore class="card-body">
                            <div id="customerList">
                                <div class="row ">
                                    <div class="col-sm-auto">
                                    </div>
                                    <div class="table-responsive table-card mt-3 mb-1">
                                        <table class="table align-middle table-nowrap flex-table"
                                               id="BalanceOperationsTable" style="width: 100%">
                                            <thead class="table-light">
                                            <tr>
                                                <th>{{ __('Operation Designation') }}</th>
                                                <th>{{ __('I/O') }}</th>
                                                <th>{{ __('Source') }}</th>
                                                <th>{{ __('Amount') }}</th>
                                                <th>{{ __('ModifyAmount') }}</th>
                                                <th>{{ __('Actions') }}</th>
                                            </tr>
                                            </thead>
                                            <tbody class="list form-check-all">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="amounts" role="tabpanel">
                        <div wire:ignore class="card-body">
                            <div id="customerList">
                                <div class="row ">
                                    <div class="col-sm-auto">
                                    </div>
                                    <div class="table-responsive table-card mt-3 mb-1">
                                        <table class="table align-middle table-nowrap flex-table" id="amountsTable"
                                               style="width: 100%">
                                            <thead class="table-light">
                                            <tr>
                                                <th>{{ __('Name') }}</th>
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
                        </div>
                    </div>
                    <div class="tab-pane  fade" id="actionHistorys" role="tabpanel">
                        <div wire:ignore class="card-body">
                            <div id="customerList">
                                <div class="row ">
                                    <div class="col-sm-auto">
                                    </div>
                                    <div class="table-responsive table-card mt-3 mb-1">
                                        <table class="table align-middle table-nowrap flex-table"
                                               id="ActionHistorysTable" style="width: 100%">
                                            <thead class="table-light">
                                            <tr>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('reponce') }}</th>
                                                <th>{{ __('Actions') }}</th>
                                            </tr>
                                            </thead>
                                            <tbody class="list form-check-all">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="settingModal" tabindex="-1" style="z-index: 200000"
         aria-labelledby="settingsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="settingsModalLabel">{{__('Add setting')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-xl-6">
                                <label class="me-sm-2">{{ __('Name') }}</label>
                                <input type="text" class="form-control" wire:model.defer="parameterName"
                                       placeholder="{{ __('Name') }}">
                            </div>
                            <div class="mb-3 col-xl-6">
                                <label class="me-sm-2">{{ __('IntegerValue') }}</label>
                                <input type="number" class="form-control" wire:model.defer="IntegerValue"
                                       placeholder="{{ __('IntegerValue') }}" name="IntegerValue">
                            </div>
                            <div class="mb-3 col-xl-6">
                                <label class="me-sm-2">{{ __('StringValue') }}</label>
                                <input type="text" class="form-control" wire:model.defer="StringValue"
                                       placeholder="{{ __('StringValue') }}" name="StringValue">
                            </div>
                            <div class="mb-3 col-xl-6">
                                <label class="me-sm-2"> {{ __('DecimalValue') }}</label>
                                <input type="number" class="form-control" wire:model.defer="DecimalValue"
                                       placeholder="{{ __('DecimalValue') }}" name="DecimalValue">
                            </div>
                            <div class="mb-3 col-xl-6">
                                <label class="me-sm-2">{{ __('Unit') }}</label>
                                <input maxlength="5" type="text" class="form-control" wire:model.defer="Unit"
                                       placeholder="{{ __('Unit') }}" name="Unit">
                            </div>
                            <div class="mb-3 col-xl-6">
                                <label class="me-sm-2">{{ __('I/O') }}</label>
                                <select class="form-control" name="Automatically_calculated"
                                        wire:model.defer="Automatically_calculated">
                                    <option value="0"> {{ __('No') }}</option>
                                    <option value="1">{{ __('Yes') }}</option>
                                </select>
                            </div>
                            <div class="mb-3 col-xl-12">
                                <label class="me-sm-2">{{ __('description') }}</label>
                                <textarea maxlength="250" class="form-control" wire:model.defer="Description"
                                          placeholder="{{ __('description') }}" name="Description"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" wire:click="save" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="BoModal" tabindex="-1" style="z-index: 200000"
         aria-labelledby="BoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="BoModalLabel">{{__('Save BO')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">

                            <div class="mb-3 col-xl-6">
                                <label class="me-sm-2">{{ __('Designation') }}</label>
                                <input wire:model.defer="DesignationBO" type="text" class="form-control"
                                       placeholder="Designation" name="Designation">
                            </div>
                            <div class="mb-3 col-xl-6">
                                <label class="me-sm-2">{{ __('idSource') }}</label>
                                <input wire:model.defer="idSourceBO" type="text" class="form-control"
                                       placeholder="idSource" name="idSource">
                            </div>
                            <div class="mb-3 col-xl-4">
                                <label class="me-sm-2">{{ __('I/O') }}</label>
                                <select wire:model.defer="IOBO" class="form-control" name="IO">
                                    <option value="I">I</option>
                                    <option value="O">O</option>
                                    <option value="IO">IO</option>
                                </select>
                            </div>
                            <div class="mb-3 col-xl-4">
                                <label class="me-sm-2">{{ __('Amount') }}</label>
                                <select class="form-control" id="langueCountrie" name=" "
                                        wire:model.defer="idamountsBO">
                                    @foreach($allAmounts as $amount)
                                        <option value="{{$amount->idamounts}}">{{$amount->amountsname}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-xl-4">
                                <label class="me-sm-2">{{ __('Modify Amount') }}</label>
                                <select wire:model.defer="MODIFY_AMOUNT" class="form-control" name="MODIFY_AMOUNT">
                                    <option value="0">{{ __('No') }}</option>
                                    <option value="1">{{ __('Yes') }}</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" wire:click="saveBO" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="AmountsModal" tabindex="-1" style="z-index: 200000"
         aria-labelledby="AmountsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="AmountsModalLabel">{{__('saveAmounts')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
    <div wire:ignore.self class="modal fade" id="HistoryActionModal" tabindex="-1" style="z-index: 200000"
         aria-labelledby="HistoryActionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="HistoryActionModalLabel">{{__('saveHA')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">

                            <div class="mb-3 col-xl-6">
                                <label class="me-sm-2">{{ __('backand.Name') }}</label>
                                <input type="text" class="form-control" placeholder="name" name="ParameterName"
                                       wire:model.defer="titleHA">
                            </div>
                            <div class="mb-3 col-xl-6">
                                <label class="me-sm-2">{{ __('backand.reponce') }}</label>
                                <select wire:model.defer="reponceHA" class="form-control" name="reponce">
                                    <option value="0">{{ __('backand.sans reponce') }}</option>
                                    <option value="1">{{ __('backand.create reponce') }}</option>
                                    <option value="2">{{ __('backand.list reponce') }}</option>
                                </select>
                            </div>
                            <div class="mb-3 col-xl-12">
                                <label class="me-sm-2">{{ __('backand.list reponce') }}</label>
                                {{--                                <input type="text" name="list_reponce"  >--}}
                                <input data-role="tagsinput" id="tags" name='tags' wire:model.defer="list_reponceHA"
                                       class="form-control" autofocus>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" onclick="saveHA()" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.addEventListener('load', () => {
            $(document).on('turbolinks:load', function () {
                $('#SettingsTable').DataTable(
                    {
                        retrieve: true,
                        "colReorder": false,
                        "orderCellsTop": false,
                        "fixedHeader": true,
                        search: {
                            return: true
                        },
                        "processing": true,
                        "aLengthMenu": [[5, 30, 50], [5, 30, 50]],
                        "ajax": "{{route('API_settings',app()->getLocale())}}",
                        "columns": [
                            {"data": "ParameterName"},
                            {"data": "IntegerValue"},
                            {"data": "StringValue"},
                            {"data": "DecimalValue"},
                            {"data": "Unit"},
                            {"data": "Automatically_calculated"},
                            {data: 'action', name: 'action', orderable: false, searchable: false},
                        ],
                        "language": {
                            "url": urlLang
                        }
                    }
                );
                $('#BalanceOperationsTable').DataTable(
                    {
                        retrieve: true,
                        "colReorder": true,
                        "orderCellsTop": true,
                        "fixedHeader": true,
                        initComplete: function () {
                            this.api()
                                .columns()
                                .every(function () {
                                    var that = $('#BalanceOperationsTable').DataTable();
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
                        search: {
                            return: true
                        },
                        "ajax": "{{route('API_BalOperations' ,app()->getLocale())}}",
                        "columns": [
                            {"data": "Designation"},
                            {"data": "IO"},
                            {"data": "idSource"},
                            {"data": "amountsshortname"},
                            {data: 'MODIFY_AMOUNT'},
                            {data: 'action', name: 'action', orderable: false, searchable: false},
                        ],
                        "language": {
                            "url": urlLang
                        }
                    }
                );
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
                        "ajax": "{{route('API_Amounts',app()->getLocale())}}",
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
                        "language": {"url": urlLang}
                    }
                );

                $('#ActionHistorysTable').DataTable(
                    {
                        retrieve: true,
                        "colReorder": true,
                        "orderCellsTop": true,
                        "fixedHeader": true,
                        initComplete: function () {
                            this.api()
                                .columns()
                                .every(function () {

                                    if ($.fn.dataTable.isDataTable('#ActionHistorysTable')) {
                                        var that = $('#ActionHistorysTable').DataTable();
                                    }
                                    $('input', this.footer()).on('keydown', function (ev) {
                                        if (ev.keyCode == 13) {
                                            that.search(this.value).draw();
                                        }
                                    });
                                });
                        },
                        "processing": true,
                        search: {return: true},
                        "ajax": "{{route('API_ActionHistory',app()->getLocale())}}",
                        "columns": [
                            {data: 'title'},
                            {data: 'reponce'},
                            {data: 'action', name: 'action', orderable: false, searchable: false},
                        ],
                        "language": {"url": urlLang}
                    }
                );
            });
        });
    </script>
</div>








