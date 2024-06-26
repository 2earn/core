<div>
    @section('title'){{ __('Discounts Balance') }} @endsection
    @section('content')
        @component('components.breadcrumb')
            @slot('li_1')@endslot
            @slot('title') {{ __('Discounts Balance') }} @endslot
        @endcomponent
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table nowrap dt-responsive align-middle table-hover table-bordered" id="userBalanceDB_table" style="width: 100%">
                            <thead class="table-light">
                            <tr class="head2earn  tabHeader2earn" >
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
@push('scripts')
    <script data-turbolinks-eval="false">
        $(document).on('ready ', function () {
                $('#page-title-box').addClass('page-title-box-db');
            }
        );
        window.addEventListener('load', () => {
            $(document).on('turbolinks:load', function () {
                $('#ub_table').DataTable(
                    {
                        ordering: true,
                        retrieve: true,
                        searching: false,
                        "orderCellsTop": true,
                        "fixedHeader": true,
                        "order": [[1, 'desc']],
                        "processing": true,
                        "serverSide": true,
                        "aLengthMenu": [[10, 30, 50], [10, 30, 50]],
                        search: {return: false},
                        "ajax": "{{route('API_UserBalances',['locale'=> app()->getLocale(), 'idAmounts'=>'cash-Balance'])}}",
                        "columns": [
                            {data: 'Ref'},
                            {data: 'Date'},
                            {data: 'Designation'},
                            {data: 'Description'},
                            {data: 'value'},
                            {data: 'balance'},
                            {data: 'ranks'},
                            {data: 'idamount'},
                        ],
                        "columnDefs":
                            [
                                {
                                    "targets": [4],
                                    render: function (data, type, row) {
                                        if (data.indexOf('+') == -1)
                                            return '<span class="badge bg-danger text-end">' + data + '</span>';
                                        else
                                            return '<span class="badge bg-success text-end">' + data + '</span>';
                                    },
                                    className: classAl,
                                },
                                {
                                    "targets": [5],
                                    render: function (data, type, row) {
                                        if (row.ranks == 1)
                                            if (row.idamount == 1)
                                                return '<div class="logoTopCashLabel"><h5 class="text-success fs-14 mb-0 ms-2">' + data + '</h5></div>';
                                            else
                                                return '<div class="logoTopDBLabel"><h5 class="text-success fs-14 mb-0 ms-2">' + data + '</h5></div>';
                                        else
                                            return data;

                                    }
                                },
                                {"targets": [6, 7], searchable: false, visible: false},
                                {"targets": [5], className: classAl},
                            ],
                        "language": {
                            "url": urlLang
                        }
                    }
                );
            });
        });


    </script>
@endpush
