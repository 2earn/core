<div class="container-fluid">
    @section('title')
        {{ __('Users List') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Users List') }}
        @endslot
    @endcomponent
    <div class="card">
        <div class="card-body row">
            <div class="col-12">
                @include('layouts.flash-messages')
            </div>
        </div>
        <div class="card-body row">
            <div class="col-xl-4 col-md-6">
                <div class="card border border-muted card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium  text-info text-truncate mb-0">
                                    {{__('Cash Balance')}}
                                </p>
                            </div>
                            <p class=" text-info mb-0">
                                                <span class="ms-2">
                                                    <i
                                                        class="ri-building-line align-bottom"></i>
                                                    {{number_format(getAdminCash()[0],2)}}</span>
                                <span class="ms-2"><i class="ri-map-pin-2-line align-bottom"></i>
                                                {{number_format(\App\Services\Balances\Balances::sommeSold('cash_balances')-floatval(getAdminCash()[0]),2)}}
                                            </span>
                            </p>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                    {{config('app.currency')}}
                                    <span>
                                                    {{formatSolde(\App\Services\Balances\Balances::sommeSold('cash_balances'))}}</span>
                                </h4>

                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                                        <span class=" bg-info-subtle rounded fs-3">
                                                          <i class="ri-exchange-dollar-line display-6 bx-dollar-circle   text-info"></i>
                                                        </span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card border border-muted card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium  text-info text-truncate mb-0">
                                    {{__('BFS')}}
                                </p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                    {{config('app.currency')}}
                                    <span class="counter-value"
                                          data-target="{{\App\Services\Balances\Balances::sommeSold('bfss_balances')}}">
                                                      {{\App\Services\Balances\Balances::sommeSold('bfss_balances')}}</span>
                                </h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                                        <span class=" bg-info-subtle rounded fs-3">
                                                           <i class="ri-shopping-cart-2-line display-6  text-info"></i>
                                                        </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card border border-muted card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium  text-info text-truncate mb-0">
                                    {{__('Discount Balance')}}
                                </p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                    {{config('app.currency')}}
                                    <span class="counter-value"
                                          data-target="{{\App\Services\Balances\Balances::sommeSold('discount_balances')}}">
                                                 {{\App\Services\Balances\Balances::sommeSold('discount_balances')}}</span>
                                </h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                                        <span class=" bg-info-subtle rounded fs-3">
                                                          <i class=" ri-percent-line display-6  text-info"></i>
                                                        </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border border-muted card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium  text-info text-truncate mb-0">
                                    {{__('sms balance')}}
                                </p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                                <span class="counter-value"
                                                      data-target="{{\App\Services\Balances\Balances::sommeSold('sms_balances')}}">
                                                   {{\App\Services\Balances\Balances::sommeSold('sms_balances')}}</span>
                                </h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                                        <span class=" bg-info-subtle rounded fs-3">
                                                           <i class="ri-message-line display-6  text-info"></i>
                                                        </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border border-muted card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium  text-info text-truncate mb-0">
                                    {{__('Shares Sold')}}
                                </p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                    {{config('app.currency')}}
                                    <span class="counter-value"
                                          data-target="   {{\App\Services\Balances\Balances::sommeSold('shares_balances')}}">
                                                       {{\App\Services\Balances\Balances::sommeSold('shares_balances')}}</span>
                                </h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                                        <span class=" bg-info-subtle rounded fs-3">
                                                            <i class="ri-stackshare-line display-6  text-info"></i>
                                                        </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border border-muted card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium  text-info text-truncate mb-0">
                                    {{__('Shares Revenue')}}
                                </p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                    {{config('app.currency')}}
                                    <span class="counter-value"
                                          data-target=" {{\App\Services\Balances\Balances::sommeSold('shares_balances','amount')}}">
                                                    {{\App\Services\Balances\Balances::sommeSold('shares_balances','amount')}}</span>
                                </h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                                        <span class=" bg-info-subtle rounded fs-3">
                                                        <i class="ri-swap-line display-6  text-info"></i>
                                                        </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border border-muted card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium  text-info text-truncate mb-0">
                                    {{__('Cash Flow')}}
                                </p>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                    {{config('app.currency')}}
                                    <span class="counter-value"
                                          data-target="{{\App\Services\Balances\Balances::sommeSold('shares_balances','amount')+\App\Services\Balances\Balances::sommeSold('cash_balances')}}">
                                                    {{\App\Services\Balances\Balances::sommeSold('shares_balances','amount')+\App\Services\Balances\Balances::sommeSold('cash_balances')}}</span>
                                </h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                                        <span class=" rounded fs-3">
<i class="ri-exchange-funds-line display-6  text-info"></i>
                                                        </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body table-responsive">
            <table id="users-list"
                   class="table table-striped table-bordered  display nowrap">
                <thead class="table-light">
                <tr class="head2earn  tabHeader2earn">
                    <th>{{__('Details')}}</th>
                    <th>{{__('created at')}}</th>
                    <th>{{__('pays')}}</th>
                    <th>{{__('Name')}}</th>
                    <th>{{__('Mobile')}}</th>
                    <th>{{__('Status')}}</th>
                    <th>{{__('Soldes')}}</th>
                    <th>{{__('Action')}}</th>
                    <th>{{__('More details')}}</th>
                    <th>{{__('VIP history')}}</th>
                    <th>{{__('Password')}}</th>
                    <th>{{__('Uplines')}}</th>
                </tr>
                </thead>
                <tbody class="body2earn">
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="AddCash" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalgridLabel">{{ __('Transfert Cash') }}</h5>
                    <button type="button" class="btn-close btn-vip-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
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
                                        <span class="input-group-text"> {{config('app.currency')}}</span>
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
    <div class="modal fade" id="updatePassword" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalgridLabel">{{ __('Update Password for') }}: <span
                            class="text-warning mx-2"
                            id="userIdMark"></span></h5>
                    <button type="button" class="btn-close btn-vip-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0);">
                        <div class="row g-3">

                            <div class="input-group mt-2">
                                <div class="col-sm-12 col-md-12">
                                    <label class="form-label">{{__('New Password')}}<span
                                            class="text-danger">*</span></label>
                                </div>
                                <div class="col-sm-12 col-md-12">
                                    <input type="password" class="form-control" id="updatePasswordInput">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 mt-2">
                            <div class="hstack gap-2 justify-content-end">
                                <button type="button" class="btn btn-light"
                                        data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                                <button type="button" id="password-update-submit"
                                        class="btn btn-soft-danger">{{ __('Update Password') }}</button>
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
                    <button type="button" class="btn-close btn-vip-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0);">
                        <div class="row g-3">
                            <div class="col-sm-12 col-md-12">
                                <div class="input-group">
                                        <span class="input-group-text">
                                            <img id="vip-country" class="avatar-xxs me-2"/></span>
                                    <input type="text" class="form-control" disabled id="vip-phone"
                                           aria-describedby="basic-addon1">
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="input-group mt-2">
                                    <input id="vip-reciver" type="hidden">
                                    <input type="hidden" id="created_at">
                                    <label class="form-label">{{__('Minshares')}}<span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control-flash" id="minshares">
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="input-group mt-2">
                                    <label class="form-label">{{__('Periode')}}<span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control-flash" id="periode">
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12">
                                <div class="input-group mt-2">
                                    <label class="form-label">{{__('Coefficient')}}<span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control-flash" id="coefficient">
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12">
                                <div class="input-group mt-2">
                                    <label class="form-label">{{__('Note')}}</label>
                                    <textarea type="text" class="form-control-flash" id="note"></textarea>
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
                    <h4 class="modal-title text-info" id="modalTitle">{{ __('Transfert Cash') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class=" table-responsive">
                        <input id="balances-reciver" type="hidden">
                        <input id="balances-amount" type="hidden">
                        <table
                            class="table table-striped table-bordered cell-border row-border table-hover mdl-data-table display nowrap"
                            id="ub_table_list" style="width: 100%">
                            <thead class="table-light">
                            <tr class="head2earn  tabHeader2earn">
                                <th>{{ __('ranks') }}</th>
                                <th>{{ __('id') }}</th>
                                <th>{{ __('ref') }}</th>
                                <th>{{ __('date') }}</th>
                                <th>{{ __('Operation Designation') }}</th>
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
    <div class="modal fade modal-xl" id="detailsh" tabindex="-1" aria-labelledby="exampleModalgridLabel"
         aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-info" id="exampleModalgridLabelsh">{{ __('Shares balances') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card-body table-responsive">
                        <input id="balances-reciversh" type="hidden">
                        <input id="balances-amountsh" type="hidden">
                        <table
                            class="table table-striped table-bordered cell-border row-border table-hover mdl-data-table display nowrap"
                            id="ub_table_listsh" style="width: 100%">
                            <thead class="table-light">
                            <tr class="head2earn  tabHeader2earn">
                                <th>{{__('Reference')}}</th>
                                <th>{{__('Created_at')}}</th>
                                <th>{{__('Value')}}</th>
                                <th>{{__('Real amount')}}</th>
                                <th>{{__('Current balance')}}</th>
                                <th>{{__('Unit price')}}</th>
                                <th>{{__('Total amount')}}</th>
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
        var ammount = 0;

        function fireSwalInformMessage(iconSwal, titleSwal, textSwal) {
            Swal.fire({
                position: 'center',
                icon: iconSwal,
                title: titleSwal,
                html: textSwal,
                showConfirmButton: true,
                confirmButtonText: '{{__('ok')}}',
                showCloseButton: true
            });
        }

        function transferCash(ammount) {
            let reciver = $('#userlist-reciver').val();
            let msg = "{{__('You transferred')}} " + ammount + " $ {{__('For')}} " + reciver;
            let user = 126;
            if (ammount > 0) {
                $.ajax({
                    url: "{{ route('add_cash', app()->getLocale()) }}",
                    type: "POST",
                    data: {amount: ammount, reciver: reciver, "_token": "{{ csrf_token() }}"},
                    success: function (dataTransfert) {
                        $.ajax({
                            url: "{{ route('send_sms',app()->getLocale()) }}",
                            type: "POST",
                            data: {user: user, msg: msg, "_token": "{{ csrf_token() }}"},
                            success: function (dataMessage) {
                                fireSwalInformMessage('success', '{{__('Transfer success')}}', dataTransfert + ' ' + dataMessage);
                            },
                            error: function (xhr, ajaxOptions, thrownError) {
                                fireSwalInformMessage('error', xhr.status, dataTransfert + ' ' + xhr.responseJSON);
                            }
                        });
                        $.getJSON(window.url, function (dataTransfert) {
                            createOrUpdateDataTable(data);
                        });
                        $('.btn-vip-close').trigger('click');
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        fireSwalInformMessage('error', '{{__('error')}}', xhr.responseJSON);
                    }
                });
            } else {
                fireSwalInformMessage('error', '{{__('wrong amount value')}}', '{{__('wrong amount value')}}')
            }

            $(this).prop("disabled", false);
        }

        function createOrUpdateDataTable(data) {
            try {
                if ($.fn.DataTable.isDataTable('#ub_table_list')) {
                    $('#ub_table_list').DataTable().destroy();
                }
            } catch (error) {
                console.error('Error destroying DataTable:', error);
            }

            $('#ub_table_list').DataTable({
                ordering: false,
                retrieve: true,
                searching: false,
                "fixedHeader": true,
                "processing": true,
                "data": data,
                "columns": [
                    {data: 'ranks'},
                    {data: 'id'},
                    {data: 'reference'},
                    {data: 'created_at'},
                    {data: 'operation'},
                    {data: 'value', className: window.classAl},
                    {data: 'current_balance', className: window.classAl},
                ],
            });
        }

        document.addEventListener("DOMContentLoaded", function () {

            $(document).on("click", ".cb", function () {
                let reciver = $(this).data('reciver');
                let amount = $(this).data('amount');
                $('#balances-amount').attr('value', amount);
                $('#balances-reciver').attr('value', reciver);

                window.url = "{{ route('api_user_balances_list', ['locale'=> app()->getLocale(),'idUser' => 'idUser1', 'idAmounts' => 'idamount1']) }}";
                window.url = window.url.replace('idUser1', reciver);
                window.url = window.url.replace('idamount1', amount);

                $(document).ready(function () {
                    $.ajax({
                        url: window.url,
                        method: 'GET',
                        headers: {
                            'Authorization': 'Bearer ' + "{{ generateUserToken() }}"
                        },
                        success: function (data) {
                            console.log(data)
                            $('#modalTitle').html('{{__('Cash bbalance')}}');
                            createOrUpdateDataTable(data);
                        },
                        error: function (xhr, status, error) {
                            console.error('Error fetching data:', error);
                        }
                    });
                });
            });

            $(document).on("click", ".bfs", function () {
                let reciver = $(this).data('reciver');
                let amount = $(this).data('amount');
                $('#balances-amount').attr('value', amount);
                $('#balances-reciver').attr('value', reciver);
                window.url = "{{ route('api_user_balances_list', ['locale'=> app()->getLocale(),'idUser' => 'idUser1', 'idAmounts' => 'idamount1']) }}";
                window.url = window.url.replace('idUser1', reciver);
                window.url = window.url.replace('idamount1', amount);
                $(document).ready(function () {
                    $.ajax({
                        url: window.url,
                        method: 'GET',
                        headers: {
                            'Authorization': 'Bearer ' + "{{ generateUserToken() }}"
                        },
                        success: function (data) {
                            console.log(data)
                            $('#modalTitle').html('{{__('BFSs balance')}}');
                            createOrUpdateDataTable(data);
                        },
                        error: function (xhr, status, error) {
                            console.error('Error fetching data:', error);
                        }
                    });
                });
            });
            $(document).on("click", ".db", function () {
                let reciver = $(this).data('reciver');
                let amount = $(this).data('amount');
                $('#balances-amount').attr('value', amount);
                $('#balances-reciver').attr('value', reciver);
                window.url = "{{ route('api_user_balances_list', ['locale'=> app()->getLocale(),'idUser' => 'idUser1', 'idAmounts' => 'idamount1']) }}";
                window.url = window.url.replace('idUser1', reciver);
                window.url = window.url.replace('idamount1', amount);
                $(document).ready(function () {
                    $.ajax({
                        url: window.url,
                        method: 'GET',
                        headers: {
                            'Authorization': 'Bearer ' + "{{ generateUserToken() }}"
                        },
                        success: function (data) {
                            $('#modalTitle').html('{{ __("Discount balance") }}');
                            console.log(data)
                            createOrUpdateDataTable(data);
                        },
                        error: function (xhr, status, error) {
                            console.error('Error fetching data:', error);
                        }
                    });
                });
            });
            $(document).on("click", ".smsb", function () {
                let reciver = $(this).data('reciver');
                let amount = $(this).data('amount');
                $('#balances-amount').attr('value', amount);
                $('#balances-reciver').attr('value', reciver);

                window.url = "{{ route('api_user_balances_list', ['locale'=> app()->getLocale(),'idUser' => 'idUser1', 'idAmounts' => 'idamount1']) }}";
                window.url = window.url.replace('idUser1', reciver);
                window.url = window.url.replace('idamount1', amount);

                $(document).ready(function () {
                    $.ajax({
                        url: window.url,
                        method: 'GET',
                        headers: {
                            'Authorization': 'Bearer ' + "{{ generateUserToken() }}"
                        },
                        success: function (data) {
                            console.log(data)
                            $('#modalTitle').html('{{ __("Sms balance") }}');
                            createOrUpdateDataTable(data);
                        },
                        error: function (xhr, status, error) {
                            console.error('Error fetching data:', error);
                        }
                    });

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
                    {data: 'reference'},
                    {data: 'created_at'},
                    {data: 'value'},
                    {data: 'real_amount'},
                    {data: 'current_balance'},
                    {data: 'unit_price'},
                    {data: 'total_amount'},
                ],
                "columnDefs": [],
            });
        }

        document.addEventListener("DOMContentLoaded", function () {

            $(document).on("click", ".sh", function () {
                let reciver = $(this).data('reciver');
                let amount = $(this).data('amount');
                $('#balances-amountsh').attr('value', amount);
                $('#balances-reciversh').attr('value', reciver);
                window.url = "{{ route('api_shares_solde_list', ['locale'=> app()->getLocale(),'amount' => 'amount1','idUser' => 'idUser1']) }}";
                window.url = window.url.replace('idUser1', reciver);
                window.url = window.url.replace('amount1', amount);

                $(document).ready(function () {
                    $.ajax({
                        url: window.url,
                        method: 'GET',
                        headers: {
                            'Authorization': 'Bearer ' + "{{ generateUserToken() }}"
                        },
                        success: function (data) {
                            console.log(data)
                            createOrUpdateDataTablesh(data);
                        },
                        error: function (xhr, status, error) {
                            console.error('Error fetching data:', error);
                        }
                    });
                });
            });
        });
        document.addEventListener("DOMContentLoaded", function () {

            $('#users-list').DataTable({
                "responsive": true,
                "ordering": true,
                retrieve: true,
                "colReorder": false,
                "orderCellsTop": true,
                "fixedHeader": true,
                "order": [[0, 'desc']],
                "processing": true,
                "serverSide": true,
                "aLengthMenu": [[20, 100, 500, 1000], [20, 100, 500, 1000]],
                search: {return: true},
                autoWidth: false,
                bAutoWidth: false,
                "ajax": "{{route('api_users_list',app()->getLocale())}}",
                "columns": [
                    datatableControlBtn,
                    {data: 'formatted_created_at'},
                    {data: 'flag'},
                    {data: 'name'},
                    {data: 'mobile'},
                    {data: 'status'},
                    {data: 'soldes', name: 'action', orderable: false, searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                    {data: 'more_details', name: 'action', orderable: false, searchable: false},
                    {data: 'vip_history', name: 'action', orderable: false, searchable: false},
                    {data: 'pass'},
                    {data: 'uplines'},
                ],
                "columnDefs": [{"targets": [10], searchable: true, visible: false},],
                "language": {"url": urlLang}
            });
        });

        $(document).on("click", ".addCash", function () {
            let reciver = $(this).data('reciver');
            let phone = $(this).data('phone');
            let country = $(this).data('country');
            $('#userlist-country').attr('src', country);
            $('#userlist-reciver').attr('value', reciver);
            $('#userlist-phone').attr('value', phone);
        });

        $("#userlist-submit").on('click', function (eventUserListTransfert) {
            $(this).prop("disabled", true);
            eventUserListTransfert.preventDefault();
            eventUserListTransfert.stopImmediatePropagation();
            transferCash(parseInt($('#ammount').val()));
            $('#ammount').val(0);
            $(this).prop("disabled", false);
        });

        $(document).on("click", ".vip", function () {
            let reciver = $(this).data('reciver');
            let phone = $(this).data('phone');
            let country = $(this).data('country');
            $('#vip-country').attr('src', country);
            $('#vip-reciver').attr('value', reciver);
            $('#vip-phone').attr('value', phone);
        });

        $(document).on("click", "#updatePasswordBtn", function () {
            let id = $(this).data('id');
            let phone = $(this).data('phone');
            $('#updatePassword').attr('data-id', id);
            $('#userIdMark').html(phone);
        });

        $(document).on("click", "#password-update-submit", function () {
            window.Livewire.dispatch('changePassword', [$('#updatePassword').attr('data-id'), $('#updatePasswordInput').val()]);
        });

        $("#vip-submit").one("click", function () {
            let reciver = $('#vip-reciver').val();
            let minshares = $('#minshares').val();
            let periode = $('#periode').val();
            let coefficient = $('#coefficient').val();
            let note = $('#note').val();
            let date = Date.now();
            let msgvip = "{{__('The user')}} " + reciver + " {{__('is VIP(x')}}" + coefficient + " {{__(') for a period of')}} " + periode + " {{__('from')}} " + Date().toLocaleString() + " {{__('with a minimum of')}} " + minshares + " {{__('shares bought')}}";
            let swalTitle = "{{__('VIP mode')}}";
            let user = 126;
            if (minshares && periode && coefficient) {
                $.ajax({
                    url: "{{ route('vip',app()->getLocale()) }}",
                    headers: {'Authorization': 'Bearer ' + "{{generateUserToken()}}"},
                    type: "POST",
                    data: {
                        reciver: reciver,
                        minshares: minshares,
                        periode: periode,
                        coefficient: coefficient,
                        note: note,
                        date: date,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function (data) {
                        $.ajax({
                            url: "{{ route('send_sms',app()->getLocale()) }}",
                            type: "POST",
                            data: {user: user, msg: msgvip, "_token": "{{ csrf_token() }}"},
                            success: function (data) {
                                fireSwalInformMessage('success', swalTitle, msgvip + '<br> <span class="text-success">{{__('SMS sending succeded')}}</span>');
                            },
                            error: function (xhr, ajaxOptions, thrownError) {
                                fireSwalInformMessage('warning', swalTitle, msgvip + '<br> <span class="text-danger">{{__('SMS sending failed')}}</span>')
                            }
                        });
                        $('.btn-vip-close').trigger('click');
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        fireSwalInformMessage('error', swalTitle, '{{__('VIP mode activation failed')}}')
                    }
                });
            } else {
                fireSwalInformMessage('error', swalTitle, '{{__('Please check form data')}}')
            }
        });
    </script>
</div>
