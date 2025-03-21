<div class="container-fluid">
    <div>
        @component('components.breadcrumb')
            @slot('title')
                {{ __('ConfigurationHA BO') }}
            @endslot
        @endcomponent
        <div class="row">
            <div class="col-12">
                @include('layouts.flash-messages')
            </div>
        </div>
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive table-card mt-3 mb-1">
                        <table wire:ignore
                               class="table table-striped table-bordered cell-border row-border table-hover mdl-data-table display nowrap"
                               id="BalanceOperationsTable">
                            <thead class="table-light">
                            <tr>
                                <th>{{ __('id') }}</th>
                                <th>{{ __('Operation') }}</th>
                                <th>{{ __('I/O') }}</th>
                                <th>{{ __('Source') }}</th>
                                <th>{{ __('Balance') }}</th>
                                <th>{{ __('Parent') }}</th>
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
        <div wire:ignore.self class="modal fade" id="BoModal" tabindex="-1" style="z-index: 200000"
             aria-labelledby="BoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="BoModalLabel">{{__('Balance Operation')}}</h5>
                        <button type="button" class="btn-close btn-close-bo" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">

                                <div class="mb-3 col-xl-6">
                                    <label class="me-sm-2">{{ __('operation') }}</label>
                                    <input wire:model="operation" type="text" class="form-control"
                                           placeholder="operation" name="operation">
                                </div>
                                <div class="mb-3 col-xl-6">
                                    <label class="me-sm-2">{{ __('source') }}</label>
                                    <input wire:model="source" type="text" class="form-control"
                                           placeholder="source" name="source">
                                </div>
                                <div class="mb-3 col-xl-4">
                                    <label class="me-sm-2">{{ __('I/O') }}</label>
                                    <select wire:model="io" class="form-control" name="io">
                                        <option value="I">{{__('I')}}</option>
                                        <option value="O">{{__('O')}}</option>
                                        <option value="IO">{{__('IO')}}</option>
                                    </select>
                                </div>
                                <div class="mb-3 col-xl-4">
                                    <label class="me-sm-2">{{ __('Amount') }}</label>
                                    <select class="form-control" id="amounts_id" name="amounts_id"
                                            wire:model="amounts_id">
                                        @foreach(\Core\Models\Amount::all() as $amount)
                                            <option value="{{$amount->idamounts}}">{{$amount->amountsname}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3 col-xl-4">
                                    <label class="me-sm-2">{{ __('Modify Amount') }}</label>
                                    <select wire:model="modify_amount" class="form-control" name="modify_amount">
                                        <option value="0">{{ __('No') }}</option>
                                        <option value="1">{{ __('Yes') }}</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="saveBO" class="btn btn-primary">{{__('Save')}}</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <script type="module">

            function emitBO(idBO) {
                if (idBO) {
                    window.Livewire.dispatch('initBOFunction', [idBO]);
                }
            }

            document.addEventListener("DOMContentLoaded", function () {

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
                                            that.search(this.value).draw();
                                        }
                                    });
                                });
                        },
                        "processing": true,
                        search: {return: true},
                        "ajax": "{{route('api_bal_operations' ,app()->getLocale())}}",
                        "columns": [
                            {"data": "id"},
                            {"data": "operation"},
                            {"data": "io"},
                            {"data": "source"},
                            {"data": "amounts_id"},
                            {"data": "parent_id"},
                            {"data": "amountsshortname"},
                            {data: 'modify_amount'},
                            {data: 'action', name: 'action', orderable: false, searchable: false},
                        ],
                        "language": {"url": urlLang},
                        "drawCallback": function (settings, json) {
                            $(".edit-bo-btn").each(function () {
                                $(this).on("click", function () {
                                    emitBO($(this).attr('data-id'))
                                });
                            });
                        },
                    }
                );
            });
        </script>
    </div>
</div>








