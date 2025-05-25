<div class="container-fluid">
    <div>
        @section('title')
            {{ __('Chance Balance') }}
        @endsection
        @component('components.breadcrumb')
            @slot('li_1')@endslot
            @slot('title')
                {{ __('Chance Balance') }}
            @endslot
        @endcomponent
        <div class="row card">
            <div class="card-body">
                <div class="table-responsive">
                    <table
                        class="table table-striped table-bordered cell-border row-border table-hover mdl-data-table display nowrap"
                        id="ub_table_chance" style="width: 100%">
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
        <script type="module">
            document.addEventListener("DOMContentLoaded", function () {
                    $('#page-title-box').addClass('page-title-box-bfs');
                }
            );
            document.addEventListener("DOMContentLoaded", function () {

                var select2_array = [];

                $('#ub_table_chance').DataTable(
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
                                    if ($.fn.dataTable.isDataTable('#ub_table_chance')) {
                                        var that = $('#ub_table_chance').DataTable();
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
                        "ajax": "{{route('api_user_chance',app()->getLocale())}}",
                        "columns": [
                            {data: 'ranks'},
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
                                        if (data < 0)
                                            return '<span class="badge bg-danger con fs-14">' + data + '</span>';
                                        else
                                            return '<span class="badge bg-success con fs-14">' + data + '</span>';

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
</div>
