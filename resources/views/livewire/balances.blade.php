<div class="{{getContainerType()}}">
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
                            "ordering": false,
                            retrieve: true,
                            "colReorder": false,
                            "orderCellsTop": true,
                            "fixedHeader": true,
                            "order": [[1, 'asc']],
                            "processing": true,
                            "serverSide": false,
                            "aLengthMenu": [[10, 30, 50], [10, 30, 50]],
                            search: {return: true},
                            autoWidth: false,
                            bAutoWidth: false,

                            "ajax": {
                                url: "{{route('api_balance_operations',['locale'=> app()->getLocale()])}}",
                                type: "GET",
                                headers: {'Authorization': 'Bearer ' + "{{generateUserToken()}}"},
                                error: function (xhr, error, thrown) {
                                    loadDatatableModalError('BalanceOperationsTable')
                                }
                            },
                            "columns": [
                                {data: 'id'},
                                {data: 'details'},
                                {data: 'balance_id'},
                                {data: 'parent_id'},
                                {data: 'operation_category_id', className: classAl},
                            ],
                            "language": {"url": urlLang}
                        }
                    );
                });
            </script>

    </div>
</div>
