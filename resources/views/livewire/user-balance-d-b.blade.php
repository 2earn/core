<div class="container-fluid">
    <div>
        @section('title')
            {{ __('Discounts Balance') }}
        @endsection
        @component('components.breadcrumb')
            @slot('li_1')@endslot
            @slot('title')
                {{ __('Discounts Balance') }}
            @endslot
        @endcomponent
        <div class="row card">
            <div class="card-body">
                <div class="table-responsive">
                    <table
                        class="table table-striped table-bordered cell-border row-border table-hover mdl-data-table display nowrap"
                        id="userBalanceDB_table" style="width: 100%">
                        <thead class="table-light">
                        <tr class="head2earn  tabHeader2earn">
                            <th>{{ __('Operation order') }}</th>
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
                    $('#page-title-box').addClass('page-title-box-db');
                    $('#userBalanceDB_table').DataTable(
                        {
                            "ordering": true,
                            retrieve: true,
                            "colReorder": false,
                            "orderCellsTop": true,
                            "fixedHeader": true,
                            initComplete: function () {
                                this.api()
                                    .columns()
                                    .every(function () {
                                        if ($.fn.dataTable.isDataTable('#countries_table')) {
                                            var that = $('#userBalanceDB_table').DataTable();
                                        }
                                        $('input', this.footer()).on('keydown', function (ev) {
                                            if (ev.keyCode == 13) {//only on enter keypress (code 13)
                                                that
                                                    .search(this.value)
                                                    .draw();
                                            }
                                        });
                                    });
                            },
                            "order": [[1, 'desc']],
                            "processing": true,
                            "serverSide": true,
                            "aLengthMenu": [[10, 30, 50], [10, 30, 50]],
                            search: {
                                return: true
                            },
                            "ajax": "{{route('api_user_balances',['locale'=> app()->getLocale(), 'idAmounts'=>'Discounts-Balance'])}}",
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
                                            if (data.indexOf('+') == -1)
                                                return '<span class="badge bg-danger text-end  fs-14">' + data + '</span>';
                                            else
                                                return '<span class="badge bg-success text-end  fs-14">' + data + '</span>';

                                        },
                                        className: classAl,
                                    },
                                    {
                                        "targets": [6],
                                        render: function (data, type, row) {
                                            if (row.ranks == 1)
                                                return '<div class="' +
                                                    '' +
                                                    'logoTopDBLabel"><h5 class="text-success fs-14 mb-0 ms-2">' + data + '</h5></div>';
                                            else
                                                return data;
                                        }
                                    },
                                ],
                            "language": {"url": urlLang},
                        }
                    );
                }
            );
        </script>
    </div>
</div>
