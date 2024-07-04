<div>
    @section('title')
        {{ __('history') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('UsersList') }}
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-xl-12">
            <div class="crm-widget">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-sm-12 col-md-4 col-lg-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">{{__('Cash Balance')}}</h5>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="ri-exchange-dollar-line display-6 text-muted"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h3 class="mb-0">{{ $currency }}
                                                <span class="counter-value" data-target="{{getUserListCards()[0]}}">
                                                        {{getUserListCards()[0]}}
                                                    </span>
                                            </h3>
                                        </div>
                                    </div>
                                    <p class="text-muted mb-0"><i class="ri-building-line align-bottom"></i>
                                        {{number_format(getAdminCash()[0],2)}}
                                        <span class="ms-2"><i class="ri-map-pin-2-line align-bottom"></i>
                                                {{number_format(getUserListCards()[0]-getAdminCash()[0],2)}}
                                            </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">{{__('BFS')}}</h5>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="ri-shopping-cart-2-line display-6 text-muted"></i>
                                        </div>
                                        <div class="card-footer bg-transparent">
                                            <h4 class="mb-0">
                                                {{ $currency }}
                                                <span class="counter-value" data-target="{{getUserListCards()[1]}}">
                                                        {{getUserListCards()[1]}}
                                                    </span>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">{{__('Discount Balance')}}</h5>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class=" ri-percent-line display-6 text-muted"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h4 class="mb-0">
                                                {{ $currency }}
                                                <span class="counter-value" data-target="{{getUserListCards()[2]}}">
                                                        {{getUserListCards()[2]}}
                                                    </span>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title"> {{__('sms balance')}}</h5>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="ri-message-line display-6 text-muted"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h4 class="mb-0">
                                                    <span class="counter-value" data-target="{{getUserListCards()[3]}}">
                                                        {{getUserListCards()[3]}}
                                                    </span>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">{{__('Shares Sold')}}</h5>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="ri-stackshare-line display-6 text-muted"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h4 class="mb-0">
                                                    <span class="counter-value" data-target="{{getUserListCards()[4]}}">
                                                        {{getUserListCards()[4]}}
                                                    </span>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title"> {{__('Shares Revenue')}}</h5>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="ri-swap-line display-5 text-muted"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h4 class="mb-0">
                                                {{ $currency }}
                                                <span class="counter-value" data-target="{{getUserListCards()[5]}}">
                                                        {{getUserListCards()[5]}}
                                                    </span>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title"> {{__('Cash Flow')}}</h5>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <i class="ri-exchange-funds-line display-6 text-muted"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h4 class="mb-0">
                                                {{ $currency }}
                                                <span class="counter-value"
                                                      data-target="{{getUserListCards()[5]+getUserListCards()[0]}}">
                                                        {{getUserListCards()[5]+getUserListCards()[0]}}
                                                    </span>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="card">
            <div class="card-header">
            </div>
            <div class="card-body table-responsive">
                <table id="users-list"
                       class="table nowrap dt-responsive align-middle table-hover table-bordered"
                       style="width:100%">
                    <thead class="table-light">
                    <tr class="head2earn  tabHeader2earn">
                        <th>{{__('created at')}}</th>
                        <th>{{__('pays')}}</th>
                        <th>{{__('Phone')}}</th>
                        <th>{{__('Name')}}</th>
                        <th>{{__('SoldeCB')}}</th>
                        <th>{{__('SoldeBFS')}}</th>
                        <th>{{__('SoldeDB')}}</th>
                        <th>{{__('SoldeSMS')}}</th>
                        <th>{{__('SoldeSHARES')}}</th>
                        <th>{{__('otp')}}</th>
                        <th>{{__('Password')}}</th>
                        <th>{{__('register_upline')}}</th>
                        <th>{{__('Action')}}</th>
                        <th>{{__('MinShare')}}</th>
                        <th>{{__('Periode')}}</th>
                        <th>{{__('date')}}</th>
                        <th>{{__('COeff')}}</th>
                        <th>{{__('Note')}}</th>
                        <th>{{__('VIP')}}</th>
                    </tr>
                    </thead>
                    <tbody class="body2earn">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="AddCash" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalgridLabel">{{ __('Transfert Cash') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0);">
                        <div class="row g-3">
                            <div class="col-lg-12">
                                <label class="form-label" for="userlist-phone">
                                    {{__('Phone')}}
                                </label>
                                <div class="input-group">
                                        <span class="input-group-text">
                                            <img id="userlist-country" class="avatar-xxs me-2"/></span>
                                    <input type="text" class="form-control" disabled id="userlist-phone"
                                           aria-describedby="basic-addon1">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <input id="userlist-reciver" type="hidden">
                                <div class="form-group">
                                    <label class="form-label" for="ammount">
                                        {{__('Amount')}}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="ammount">
                                        <span class="input-group-text">{{ $currency }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="button" class="btn btn-light"
                                            data-bs-dismiss="modal">{{ __('Cancel') }}
                                    </button>
                                    <button type="button" id="userlist-submit"
                                            class="btn btn-primary">{{ __('Transfer du cash') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="vip" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalgridLabel">{{ __('VIP') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0);">
                        <div class="row g-3">
                            <div class="col-xxl-12">
                                <div class="input-group">
                                        <span class="input-group-text">
                                            <img id="vip-country" class="avatar-xxs me-2"/></span>
                                    <input type="text" class="form-control" disabled id="vip-phone"
                                           aria-describedby="basic-addon1">
                                </div>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-xxl-12">
                                <div class="input-group mt-2">
                                    <input id="vip-reciver" type="hidden">
                                    <input type="hidden" id="created_at">
                                    <label class="form-label">{{__('Minshares')}}</label>
                                    <input type="number" class="form-control-flash" id="minshares">
                                </div>
                                <div class="input-group mt-2">
                                    <label class="form-label">{{__('Periode')}}</label>
                                    <input type="number" class="form-control-flash" id="periode">
                                </div>
                                <div class="input-group mt-2">
                                    <label class="form-label">{{__('Coefficient')}}</label>
                                    <input type="number" class="form-control-flash" id="coefficient">
                                </div>
                                <div class="input-group mt-2">
                                    <label class="form-label">{{__('Note')}}</label>
                                    <input type="text" class="form-control-flash" id="note">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 mt-2">
                            <div class="hstack gap-2 justify-content-end">
                                <button type="button" class="btn btn-light"
                                        data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                                <button type="button" id="vip-submit"
                                        class="btn btn-flash">{{ __('Submit') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade modal-xl" id="detail" tabindex="-1" aria-labelledby="exampleModalgridLabel"
         aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalgridLabel">{{ __('Transfert Cash') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header">
                        </div>
                        <div class="card-body table-responsive">
                            <input id="balances-reciver" type="hidden">
                            <input id="balances-amount" type="hidden">
                            <table class="table nowrap dt-responsive align-middle table-hover table-bordered"
                                   id="ub_table_list" style="width: 100%">
                                <thead class="table-light">
                                <tr class="head2earn  tabHeader2earn">
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
    </div>
    <div class="modal fade modal-xl" id="detailsh" tabindex="-1" aria-labelledby="exampleModalgridLabel"
         aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalgridLabelsh">{{ __('Transfert Cash') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header">
                        </div>
                        <div class="card-body table-responsive">
                            <input id="balances-reciversh" type="hidden">
                            <input id="balances-amountsh" type="hidden">
                            <table class="table nowrap dt-responsive align-middle table-hover table-bordered"
                                   id="ub_table_listsh" style="width: 100%">
                                <thead class="table-light">
                                <tr class="head2earn  tabHeader2earn">
                                    <th>{{__('date_purchase')}}</th>
                                    <th>{{__('number_of_shares')}}</th>
                                    <th>{{__('gifted_shares')}}</th>
                                    <th>{{__('total_shares')}}</th>
                                    <th>{{__('total_price')}}</th>
                                    <th>{{__('present_value')}}</th>
                                    <th>{{__('current_earnings')}}</th>
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
    </div>
    <script type="module">
        function createOrUpdateDataTable(data) {
            if ($.fn.DataTable.isDataTable('#ub_table_list')) {
                $('#ub_table_list').DataTable().destroy();
            }
            $('#ub_table_list').DataTable({
                ordering: true,
                retrieve: true,
                searching: false,
                "orderCellsTop": true,
                "fixedHeader": true,
                "order": [[1, 'asc']],
                "processing": true,
                "data": data,
                "columns": [
                    {data: 'ref'},
                    {data: 'Date'},
                    {data: 'Designation'},
                    {data: 'Description'},
                    {data: 'value', className: window.classAl},
                    {data: 'balance', className: window.classAl},
                ],
                "columnDefs": [
                    {
                        "targets": [4],
                        render: function (data, type, row) {
                            if (row.value < 0) {
                                return '<span class="badge bg-danger text-end">' + data + '</span>';
                            } else {
                                return '<span class="badge bg-success text-end">' + data + '</span>';
                            }
                        },
                        className: window.classAl,
                    },
                    {"targets": [5], className: window.classAl}
                ],
            });
        }

        $(document).on("click", ".cb", function () {
            let reciver = $(this).data('reciver');
            let amount = $(this).data('amount');
            $('#balances-amount').attr('value', amount);
            $('#balances-reciver').attr('value', reciver);

            window.url = "{{ route('API_UserBalances_list', ['locale'=> app()->getLocale(),'idUser' => 'idUser1', 'idAmounts' => 'idamount1']) }}";
            window.url = window.url.replace('idUser1', reciver);
            window.url = window.url.replace('idamount1', amount);

            $(document).ready(function () {
                $.getJSON(window.url, function (data) {
                    createOrUpdateDataTable(data);
                });
            });
        });

        $(document).on("click", ".bfs", function () {
            let reciver = $(this).data('reciver');
            let amount = $(this).data('amount');
            $('#balances-amount').attr('value', amount);
            $('#balances-reciver').attr('value', reciver);
            window.url = "{{ route('API_UserBalances_list', ['locale'=> app()->getLocale(),'idUser' => 'idUser1', 'idAmounts' => 'idamount1']) }}";
            window.url = window.url.replace('idUser1', reciver);
            window.url = window.url.replace('idamount1', amount);

            $(document).ready(function () {
                $.getJSON(window.url, function (data) {
                    createOrUpdateDataTable(data);
                });
            });
        });
        $(document).on("click", ".db", function () {
            let reciver = $(this).data('reciver');
            let amount = $(this).data('amount');
            $('#balances-amount').attr('value', amount);
            $('#balances-reciver').attr('value', reciver);
            window.url = "{{ route('API_UserBalances_list', ['locale'=> app()->getLocale(),'idUser' => 'idUser1', 'idAmounts' => 'idamount1']) }}";
            window.url = window.url.replace('idUser1', reciver);
            window.url = window.url.replace('idamount1', amount);
            $(document).ready(function () {
                $.getJSON(window.url, function (data) {
                    createOrUpdateDataTable(data);
                });
            });
        });
        $(document).on("click", ".smsb", function () {
            let reciver = $(this).data('reciver');
            let amount = $(this).data('amount');
            $('#balances-amount').attr('value', amount);
            $('#balances-reciver').attr('value', reciver);

            window.url = "{{ route('API_UserBalances_list', ['locale'=> app()->getLocale(),'idUser' => 'idUser1', 'idAmounts' => 'idamount1']) }}";
            window.url = window.url.replace('idUser1', reciver);
            window.url = window.url.replace('idamount1', amount);

            $(document).ready(function () {
                $.getJSON(window.url, function (data) {
                    createOrUpdateDataTable(data);
                });
            });
        });

        function createOrUpdateDataTablesh(data) {
            if ($.fn.DataTable.isDataTable('#ub_table_listsh')) {
                $('#ub_table_listsh').DataTable().destroy();
            }

            $('#ub_table_listsh').DataTable({
                ordering: true,
                retrieve: true,
                searching: false,
                "orderCellsTop": true,
                "fixedHeader": true,
                "order": [[1, 'asc']],
                "processing": true,
                "data": data,
                "columns": [
                    {data: 'Date'},
                    {data: 'value'},
                    {data: 'gifted_shares'},
                    {data: 'total_shares'},
                    {data: 'total_price'},
                    {data: 'present_value'},
                    {data: 'current_earnings'},
                ],
                "columnDefs": [],
            });
        }

        $(document).on("click", ".sh", function () {
            let reciver = $(this).data('reciver');
            let amount = $(this).data('amount');
            $('#balances-amountsh').attr('value', amount);
            $('#balances-reciversh').attr('value', reciver);
            window.url = "{{ route('API_SharesSolde_list', ['locale'=> app()->getLocale(),'idUser' => 'idUser1']) }}";
            window.url = window.url.replace('idUser1', reciver);
            $(document).ready(function () {
                $.getJSON(window.url, function (data) {
                    createOrUpdateDataTablesh(data);
                });
            });
        });
        $(document).on('turbolinks:load', function () {
            $('#users-list').DataTable({
                "ordering": true,
                retrieve: true,
                "colReorder": false,
                "orderCellsTop": true,
                "fixedHeader": true,
                "order": [[0, 'desc']],
                "processing": true,
                "serverSide": false,
                "aLengthMenu": [[100, 500, 1000], [100, 500, 1000]],
                search: {return: true},
                autoWidth: false,
                bAutoWidth: false,
                "ajax": "{{route('API_UsersList',app()->getLocale())}}",
                "columns": [
                    {data: 'formatted_created_at'},
                    {data: 'flag'},
                    {data: 'formatted_mobile'},
                    {data: 'name'},
                    {data: 'SoldeCB'},
                    {data: 'SoldeBFS'},
                    {data: 'SoldeDB'},
                    {data: 'SoldeSMS'},
                    {data: 'SoldeSH'},
                    {data: 'OptActivation'},
                    {data: 'pass'},
                    {data: 'register_upline'},
                    {data: 'minshares'},
                    {data: 'periode'},
                    {data: 'date'},
                    {data: 'coeff'},
                    {data: 'mobile'},
                    {data: 'note'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                    {data: 'VIP', name: 'action', orderable: false, searchable: false},
                ],
                "columnDefs": [{"targets": [19], searchable: true, visible: false},],
                "language": {"url": urlLang}
            });
        });


    </script>
</div>
