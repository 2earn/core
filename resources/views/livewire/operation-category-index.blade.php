<div class="container-fluid">
    <div>
        @component('components.breadcrumb')
            @slot('title')
                {{ __('Operation category') }}
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
                               id="OperationsTable">
                            <thead class="table-light">
                            <tr>
                                <th>{{ __('id') }}</th>
                                <th>{{ __('Code') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Description') }}</th>
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

                $('#OperationsTable').DataTable(
                    {
                        retrieve: true,
                        "colReorder": true,
                        "orderCellsTop": true,
                        "fixedHeader": true,
                        initComplete: function () {
                            this.api()
                                .columns()
                                .every(function () {
                                    var that = $('#OperationsTable').DataTable();
                                    $('input', this.footer()).on('keydown', function (ev) {
                                        if (ev.keyCode == 13) {
                                            that.search(this.value).draw();
                                        }
                                    });
                                });
                        },
                        "processing": true,
                        search: {return: true},
                        "ajax": "{{route('api_operations_categories' ,app()->getLocale())}}",
                        "columns": [
                            {"data": "id"},
                            {"data": "code"},
                            {"data": "name"},
                            {"data": "description"},
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








