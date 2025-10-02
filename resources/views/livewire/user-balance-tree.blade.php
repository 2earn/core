<div class="container-fluid">
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
            <div class="card-body">
                <div class="table-responsive">
                    <table
                        class="table table-striped table-bordered cell-border row-border table-hover mdl-data-table display nowrap"
                        id="ub_table_tree" style="width: 100%">
                        <thead class="table-light">
                        <tr class=" tabHeader2earn">
                            <th>{{__('reference')}}</th>
                            <th>{{ __('Created at') }}</th>
                            <th>{{ __('Operation Designation') }}</th>
                            <th>{{ __('Value') }}</th>
                            <th>{{ __('Current balance') }}</th>
                            <th>{{ __('Complementary information') }}</th>
                        </tr>
                        </thead>
                        <tbody class="body2earn">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <script type="module">
            document.addEventListener("DOMContentLoaded", function () {
                    $('#page-title-box').addClass('page-title-box-bfs');
                }
            );
            document.addEventListener("DOMContentLoaded", function () {
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
                        "ajax": {
                            url: "{{route('api_user_tree',['locale'=> app()->getLocale()])}}",
                            type: "GET",
                            headers: {'Authorization': 'Bearer ' + "{{generateUserToken()}}"},
                            error: function (xhr, error, thrown) {
                                loadDatatableModalError('ub_table_tree')
                            }
                        },
                        "columns": [
                            {data: 'reference'},
                            {data: 'created_at'},
                            {data: 'operation'},
                            {data: 'value', className: classAl},
                            {data: 'current_balance', className: classAl},
                            {data: 'complementary_information'},
                        ],
                        "columnDefs":
                            [
                                {
                                    "targets": [4],
                                    render: function (data, type, row) {
                                        return '<span class="badge bg-danger con  fs-14">' + data + '</span>';
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
</div>
