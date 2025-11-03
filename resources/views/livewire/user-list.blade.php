<div class="{{getContainerType()}}">
    @section('title')
        {{ __('Users List') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Users List') }}
        @endslot
    @endcomponent

    <section class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    @include('layouts.flash-messages')
                </div>
            </div>
        </div>

        <div class="card-body" id="users-stats">
            <div class="row g-4">
                <div class="col-xl-4 col-md-6">
                    <article class="card card-animate border-0 shadow-sm h-100 overflow-hidden">
                        <div class="card-body position-relative">
                            <div class="d-flex align-items-center mb-4">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-md">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-1">
                                            <i class="ri-exchange-dollar-line" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <h5 class="text-uppercase fw-semibold text-primary mb-2 h6 text-truncate">
                                        {{__('Cash Balance')}}
                                    </h5>
                                    <div class="d-flex flex-wrap gap-2 small text-muted">
                                        <span class="badge bg-primary-subtle text-primary" title="{{ __('Admin') }}">
                                            <i class="ri-building-line align-bottom" aria-hidden="true"></i>
                                            <span class="visually-hidden">{{ __('Admin') }}:</span>
                                            {{number_format(getAdminCash()[0], 2)}}
                                        </span>
                                        <span class="badge bg-info-subtle text-info" title="{{ __('Users') }}">
                                            <i class="ri-user-line align-bottom" aria-hidden="true"></i>
                                            <span class="visually-hidden">{{ __('Users') }}:</span>
                                            {{number_format(\App\Services\Balances\Balances::sommeSold('cash_balances') - floatval(getAdminCash()[0]), 2)}}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <h5 class="mb-0 display-6 fw-bold text-dark">
                                    <small class="text-muted fw-normal me-1">{{config('app.currency')}}</small>
                                    {{formatSolde(\App\Services\Balances\Balances::sommeSold('cash_balances'))}}
                                </h5>
                            </div>
                        </div>
                    </article>
                </div>

                <div class="col-xl-4 col-md-6">
                    <article class="card card-animate border-0 shadow-sm h-100 overflow-hidden">
                        <div class="card-body position-relative">
                            <div class="d-flex align-items-center mb-4">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-md">
                                        <div class="avatar-title bg-success-subtle text-success rounded-circle fs-1">
                                            <i class="ri-shopping-cart-2-line" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <h5 class="text-uppercase fw-semibold text-success mb-0 h6 text-truncate">
                                        {{__('BFS')}}
                                    </h5>
                                </div>
                            </div>
                            <div class="mt-3">
                                <h3 class="mb-0 display-6 fw-bold text-dark">
                                    <small class="text-muted fs-5 fw-normal me-1">{{config('app.currency')}}</small>
                                    {{\App\Services\Balances\Balances::sommeSold('bfss_balances')}}
                                </h3>
                            </div>
                        </div>
                    </article>
                </div>

                <div class="col-xl-4 col-md-6">
                    <article class="card card-animate border-0 shadow-sm h-100 overflow-hidden">
                        <div class="card-body position-relative">
                            <div class="d-flex align-items-center mb-4">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-md">
                                        <div class="avatar-title bg-warning-subtle text-warning rounded-circle fs-1">
                                            <i class="ri-percent-line" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <h2 class="text-uppercase fw-semibold text-warning mb-0 h6 text-truncate">
                                        {{__('Discount Balance')}}
                                    </h2>
                                </div>
                            </div>
                            <div class="mt-3">
                                <h3 class="mb-0 display-6 fw-bold text-dark">
                                    <small class="text-muted fs-5 fw-normal me-1">{{config('app.currency')}}</small>
                                    {{\App\Services\Balances\Balances::sommeSold('discount_balances')}}
                                </h3>
                            </div>
                        </div>
                    </article>
                </div>

                <div class="col-xl-3 col-md-6">
                    <article class="card card-animate border-0 shadow-sm h-100 overflow-hidden">
                        <div class="card-body position-relative">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-info-subtle text-info rounded fs-3">
                                            <i class="ri-message-line" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <h5 class="text-uppercase fw-semibold text-info mb-0 small text-truncate">
                                        {{__('sms balance')}}
                                    </h5>
                                </div>
                            </div>
                            <div class="mt-2">
                                <h3 class="mb-0 fs-3 fw-bold text-dark">
                                    {{\App\Services\Balances\Balances::sommeSold('sms_balances')}}
                                </h3>
                            </div>
                        </div>
                    </article>
                </div>

                <div class="col-xl-3 col-md-6">
                    <article class="card card-animate border-0 shadow-sm h-100 overflow-hidden">
                        <div class="card-body position-relative">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-danger-subtle text-danger rounded fs-3">
                                            <i class="ri-stackshare-line" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <h5 class="text-uppercase fw-semibold text-danger mb-0 small text-truncate">
                                        {{__('Shares Sold')}}
                                    </h5>
                                </div>
                            </div>
                            <div class="mt-2">
                                <h3 class="mb-0 fs-3 fw-bold text-dark">
                                    <small class="text-muted fs-6 fw-normal me-1">{{config('app.currency')}}</small>
                                    {{\App\Services\Balances\Balances::sommeSold('shares_balances')}}
                                </h3>
                            </div>
                        </div>
                    </article>
                </div>

                <div class="col-xl-3 col-md-6">
                    <article class="card card-animate border-0 shadow-sm h-100 overflow-hidden">
                        <div class="card-body position-relative">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-secondary-subtle text-secondary rounded fs-3">
                                            <i class="ri-swap-line" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <h5 class="text-uppercase fw-semibold text-secondary mb-0 small text-truncate">
                                        {{__('Shares Revenue')}}
                                    </h5>
                                </div>
                            </div>
                            <div class="mt-2">
                                <h3 class="mb-0 fs-3 fw-bold text-dark">
                                    <small class="text-muted fs-6 fw-normal me-1">{{config('app.currency')}}</small>
                                    {{\App\Services\Balances\Balances::sommeSold('shares_balances','amount')}}
                                </h3>
                            </div>
                        </div>
                    </article>
                </div>

                <div class="col-xl-3 col-md-6">
                    <article class="card card-animate border-0 shadow-sm h-100 overflow-hidden">
                        <div class="card-body position-relative">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-dark-subtle text-dark rounded fs-3">
                                            <i class="ri-exchange-funds-line" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <h5 class="text-uppercase fw-semibold text-dark mb-0 small text-truncate">
                                        {{__('Cash Flow')}}
                                    </h5>
                                </div>
                            </div>
                            <div class="mt-2">
                                <h3 class="mb-0 fs-3 fw-bold text-dark">
                                    <small class="text-muted fs-6 fw-normal me-1">{{config('app.currency')}}</small>
                                    {{floatval(\App\Services\Balances\Balances::sommeSold('shares_balances','amount')) + floatval(\App\Services\Balances\Balances::sommeSold('cash_balances'))}}
                                </h3>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </div>

        {{-- Users Table --}}
        <div class="card-body">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="users-list" class="table table-striped table-bordered display nowrap" style="width: 100%">
                                <thead class="table-light">
                                <tr class="head2earn tabHeader2earn">
                                    <th scope="col">{{__('Details')}}</th>
                                    <th scope="col">{{__('created at')}}</th>
                                    <th scope="col">{{__('pays')}}</th>
                                    <th scope="col">{{__('Name')}}</th>
                                    <th scope="col">{{__('Mobile')}}</th>
                                    <th scope="col">{{__('Status')}}</th>
                                    <th scope="col">{{__('Soldes')}}</th>
                                    <th scope="col">{{__('Action')}}</th>
                                    <th scope="col">{{__('More details')}}</th>
                                    <th scope="col">{{__('VIP history')}}</th>
                                    <th scope="col">{{__('Password')}}</th>
                                    <th scope="col">{{__('Uplines')}}</th>
                                </tr>
                                </thead>
                                <tbody class="body2earn">
                                </tbody>
                            </table>
                        </div>
                    </div>
        </div>
    </section>

    {{-- Transfer Cash Modal --}}
    <div class="modal fade" id="AddCash" tabindex="-1" aria-labelledby="AddCashModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title h5" id="AddCashModalLabel">{{ __('Transfert Cash') }}</h2>
                    <button type="button" class="btn-close btn-vip-close" data-bs-dismiss="modal"
                            aria-label="{{ __('Close') }}"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0);">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label" for="userlist-phone">
                                    {{__('Phone')}}
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <img id="userlist-country"
                                             class="avatar-xxs me-2"
                                             src=""
                                             alt="{{ __('Country flag') }}"
                                             loading="lazy">
                                    </span>
                                    <input type="text"
                                           class="form-control"
                                           disabled
                                           id="userlist-phone"
                                           aria-describedby="userlist-phone-help">
                                </div>
                            </div>
                            <div class="col-12">
                                <input id="userlist-reciver" type="hidden">
                                <label class="form-label" for="ammount">
                                    {{__('Amount')}}
                                    <span class="text-danger" aria-label="{{ __('required') }}">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number"
                                           class="form-control"
                                           id="ammount"
                                           required
                                           min="0"
                                           step="0.01"
                                           aria-describedby="ammount-currency">
                                    <span class="input-group-text" id="ammount-currency">{{config('app.currency')}}</span>
                                </div>
                            </div>
                            <div class="col-12">
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

    {{-- Update Password Modal --}}
    <div class="modal fade" id="updatePassword" tabindex="-1" aria-labelledby="updatePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title h5" id="updatePasswordModalLabel">
                        {{ __('Update Password for') }}:
                        <span class="text-warning mx-2" id="userIdMark"></span>
                    </h2>
                    <button type="button" class="btn-close btn-vip-close" data-bs-dismiss="modal"
                            aria-label="{{ __('Close') }}"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0);">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label" for="updatePasswordInput">
                                    {{__('New Password')}}
                                    <span class="text-danger" aria-label="{{ __('required') }}">*</span>
                                </label>
                                <input type="password"
                                       class="form-control"
                                       id="updatePasswordInput"
                                       required
                                       minlength="6"
                                       autocomplete="new-password">
                            </div>
                            <div class="col-12">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="button" class="btn btn-light"
                                            data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                                    <button type="button" id="password-update-submit"
                                            class="btn btn-soft-danger">{{ __('Update Password') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- VIP Modal --}}
    <div class="modal fade" id="vip" tabindex="-1" aria-labelledby="vipModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title h5" id="vipModalLabel">{{ __('VIP') }}</h2>
                    <button type="button" class="btn-close btn-vip-close" data-bs-dismiss="modal"
                            aria-label="{{ __('Close') }}"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0);">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <img id="vip-country"
                                             class="avatar-xxs me-2"
                                             src=""
                                             alt="{{ __('Country flag') }}"
                                             loading="lazy">
                                    </span>
                                    <input type="text"
                                           class="form-control"
                                           disabled
                                           id="vip-phone"
                                           aria-describedby="vip-phone-help">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <input id="vip-reciver" type="hidden">
                                <input type="hidden" id="created_at">
                                <label class="form-label" for="minshares">
                                    {{__('Minshares')}}
                                    <span class="text-danger" aria-label="{{ __('required') }}">*</span>
                                </label>
                                <input type="number"
                                       class="form-control-flash form-control"
                                       id="minshares"
                                       required
                                       min="0">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label" for="periode">
                                    {{__('Periode')}}
                                    <span class="text-danger" aria-label="{{ __('required') }}">*</span>
                                </label>
                                <input type="number"
                                       class="form-control-flash form-control"
                                       id="periode"
                                       required
                                       min="0">
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="coefficient">
                                    {{__('Coefficient')}}
                                    <span class="text-danger" aria-label="{{ __('required') }}">*</span>
                                </label>
                                <input type="number"
                                       class="form-control-flash form-control"
                                       id="coefficient"
                                       required
                                       min="0"
                                       step="0.01">
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="note">{{__('Note')}}</label>
                                <textarea class="form-control-flash form-control"
                                          id="note"
                                          rows="3"></textarea>
                            </div>
                            <div class="col-12">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="button" class="btn btn-light"
                                            data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                                    <button type="button" id="vip-submit"
                                            class="btn btn-flash">{{ __('Submit') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Balance Details Modal --}}
    <div class="modal fade modal-xl" id="detail" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title text-info h4" id="modalTitle">{{ __('Transfert Cash') }}</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <input id="balances-reciver" type="hidden">
                        <input id="balances-amount" type="hidden">
                        <table class="table table-striped table-bordered cell-border row-border table-hover mdl-data-table display nowrap"
                               id="ub_table_list" style="width: 100%">
                            <thead class="table-light">
                            <tr class="head2earn tabHeader2earn">
                                <th scope="col">{{ __('ranks') }}</th>
                                <th scope="col">{{ __('id') }}</th>
                                <th scope="col">{{ __('ref') }}</th>
                                <th scope="col">{{ __('date') }}</th>
                                <th scope="col">{{ __('Operation Designation') }}</th>
                                <th scope="col">{{ __('Value') }}</th>
                                <th scope="col">{{ __('Balance') }}</th>
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

    {{-- Shares Balance Details Modal --}}
    <div class="modal fade modal-xl" id="detailsh" tabindex="-1" aria-labelledby="detailshModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title text-info h5" id="detailshModalLabel">{{ __('Shares balances') }}</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <input id="balances-reciversh" type="hidden">
                        <input id="balances-amountsh" type="hidden">
                        <table class="table table-striped table-bordered cell-border row-border table-hover mdl-data-table display nowrap"
                               id="ub_table_listsh" style="width: 100%">
                            <thead class="table-light">
                            <tr class="head2earn tabHeader2earn">
                                <th scope="col">{{__('Reference')}}</th>
                                <th scope="col">{{__('Created_at')}}</th>
                                <th scope="col">{{__('Value')}}</th>
                                <th scope="col">{{__('Real amount')}}</th>
                                <th scope="col">{{__('Current balance')}}</th>
                                <th scope="col">{{__('Unit price')}}</th>
                                <th scope="col">{{__('Total amount')}}</th>
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
                    headers: {
                        'Authorization': 'Bearer ' + "{{ generateUserToken() }}"
                    },
                    data: {amount: ammount, reciver: reciver, "_token": "{{ csrf_token() }}"},
                    success: function (dataTransfert) {
                        $.ajax({
                            url: "{{ route('send_sms',app()->getLocale()) }}",
                            type: "POST",
                            headers: {
                                'Authorization': 'Bearer ' + "{{ generateUserToken() }}"
                            },
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
