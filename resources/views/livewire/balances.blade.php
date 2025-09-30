<div class="container-fluid">
    <div>
        @component('components.breadcrumb')
            @slot('title')
                {{ __('Balance operations') }}
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
                                <th>{{ __('Details') }}</th>
                                <th>{{ __('Balance') }}</th>
                                <th>{{ __('Parent') }}</th>
                                <th>{{ __('Operation category') }}</th>
                                <th>{{ __('Others') }}</th>
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

        <script type="module">
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
                        "ajax": {
                            url: "{{route('api_balance_operations',['locale'=> app()->getLocale()])}}",
                            type: "GET",
                            headers: {'Authorization': 'Bearer ' + "{{generateUserToken()}}"}
                        },
                        "columns": [
                            {"data": "id"},
                            {"data": "details"},
                            {"data": "amounts_id"},
                            {"data": "parent_id"},
                            {"data": "operation_category_id"},
                            {"data": "others"},
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
