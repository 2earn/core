<div>
    @section('title')
        {{ __('Balance For Shopping') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Balance For Shopping') }}
        @endslot
    @endcomponent
        <div class="row card">
            <div class="card-header border-info">
                <div class="d-flex align-items-center">
                    <h6 class="card-title mb-0 flex-grow-1">{{ __('Balance For Shopping') }}</h6>
                </div>
            </div>
            <div class="card-body">
                <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row g-4">
                        <div class="col-sm">
                            <div class="justify-content-sm-end">
                                <div class="search-box ms-2">
                                    <p>{{ __('bfs description') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <select class="select2-hidden-accessible bfs_operation_multiple" name="states[]"
                                id="select2bfs" multiple="multiple">
                        </select>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-striped table-bordered cell-border row-border table-hover mdl-data-table display nowrap"
                           id="ub_table_bfs" style="width: 100%">
                        <thead class="table-light">
                        <tr class=" tabHeader2earn">
                            <th>{{__('Num')}}</th>
                            <th>{{ __('ref') }}</th>
                            <th>{{ __('date') }}</th>
                            <th>{{ __('Operation Designation') }}</th>
                            <th>{{ __('description') }}</th>
                            <th>{{ __('Value') }}</th>
                            <th>{{ __('Balance') }}</th>
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

            $('#ub_table_bfs').DataTable(
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
                                if ($.fn.dataTable.isDataTable('#ub_table_bfs')) {
                                    var that = $('#ub_table_bfs').DataTable();
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
                    "ajax": "{{route('api_user_bfs_purchase',app()->getLocale())}}",
                    "columns": [
                        {data: 'ranks'},
                        {data: 'Ref'},
                        {data: 'Date'},
                        {data: 'Operation'},
                        {data: 'Description'},
                        {data: 'value', className: classAl},
                        {data: 'balance', className: classAl},
                    ],
                    "columnDefs":
                        [
                            {
                                "targets": [5],
                                render: function (data, type, row) {
                                    if (data.indexOf('+') == -1)
                                        return '<span class="badge bg-danger con">' + data + '</span>';
                                    else
                                        return '<span class="badge bg-success con">' + data + '</span>';

                                }
                            },
                            {
                                "targets": [6],
                                render: function (data, type, row) {
                                    if (row.ranks == 1)
                                        return '<div class="logoTopBFSLabel"><h5 class="text-success fs-14 mb-0 ms-2">' + data + '</h5></div>';
                                    else
                                        return data;
                                }
                            },
                            {
                                "targets": [3],
                                render: function (data, type, row) {
                                    if (select2_array.indexOf(data) == -1) {
                                        select2_array.push(data);
                                        $('.bfs_operation_multiple').append(('<option value="' + data + '">' + data + '</option>'));
                                    }
                                    return data;
                                }
                            }],
                    "language": {"url": urlLang}
                });
        });
    </script>
</div>
