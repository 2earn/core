<div>
    @section('title')
        {{ __('Tree Balance') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Tree Balance') }}
        @endslot
    @endcomponent
    <div class="row card">
        <div class="card-header border-info">
            <div class="d-flex align-items-center">
                <h6 class="card-title mb-0 flex-grow-1">{{ __('Tree Balance') }}</h6>
            </div>
        </div>
        <div class="card-body">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="col-lg-4">
                            <select class="select2-hidden-accessible bfs_operation_multiple" name="states[]"
                                    id="select2bfs" multiple="multiple">
                            </select>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-striped table-bordered cell-border row-border table-hover mdl-data-table display nowrap"
                               id="ub_table_tree" style="width: 100%">
                            <thead class="table-light">
                            <tr class=" tabHeader2earn">
                                <th>{{__('reference')}}</th>
                                <th>{{ __('Created at') }}</th>
                                <th>{{ __('Operation Designation') }}</th>
                                <th>{{ __('description') }}</th>
                                <th>{{ __('Value') }}</th>
                                <th>{{ __('Current balance') }}</th>
                            </tr>
                            </thead>
                            <tbody class="body2earn">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="module">
        $(document).on('ready ', function () {
                $('#page-title-box').addClass('page-title-box-bfs');
            }
        );
        $(document).on('turbolinks:load', function () {
            var select2_array = [];

            $('#ub_table_tree').DataTable(
                {
                    retrieve: true,
                    "colReorder": true,
                    "orderCellsTop": true,
                    "fixedHeader": true,
                    "order": [[2, 'desc']],
                    initComplete: function () {
                        this.api()
                            .columns()
                            .every(function () {
                                if ($.fn.dataTable.isDataTable('#ub_table_tree')) {
                                    var that = $('#ub_table_tree').DataTable();
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
                    "ajax": "{{route('api_user_tree',app()->getLocale())}}",
                    "columns": [
                        {data: 'reference'},
                        {data: 'created_at'},
                        {data: 'operation'},
                        {data: 'description'},
                        {data: 'value', className: classAl},
                        {data: 'current_balance', className: classAl},
                    ],
                    "columnDefs":
                        [
                            {
                                "targets": [5],
                                render: function (data, type, row) {
                                    return '<span class="badge bg-danger con">' + data + '</span>';

                                }
                            },
                            {
                                "targets": [3],
                                render: function (data, type, row) {
                                    return data;
                                }
                            }],
                    "language": {"url": urlLang}
                });
        });
    </script>
</div>
