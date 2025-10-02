<div class="container-fluid">
    <div>
        @section('title')
            {{ __('Cash Balance') }}
        @endsection
        @component('components.breadcrumb')
            @slot('title')
                {{ __('Cash Balance') }}
            @endslot
        @endcomponent
        <div class="row card">
            <div class="card-body">
                <div class="col-lg-12">
                    <div class="row g-4">
                        <div class="col-sm-12 col-md-4 col-lg-2 col-xl-2">
                            <img src=" {{ Vite::asset('resources/images/qr_code.jpg') }}"
                                 class="img-fluid img-thumbnail rounded avatar-lg">
                        </div>
                        <div class="col-sm-12 col-md-8 col-lg-10 col-xl-10">
                            <div class="search-box ms-2">
                                <p>{!! __('You can replenish your Cash Balance through various payment methods') !!}</p>
                                <ol>
                                    <li>{{__('Bank transfer to Al Rajhi account: 379000010006080004540, IBAN: SA5680000379000010006080004540')}}</li>
                                    <li>{{__('Use of Visa and MasterCard.')}}</li>
                                    <li>{{__('Payment via ApplePay.')}}</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="card border card-border-info">
                        <div class="card-body row">
                            <div class="col-sm-12 col-md-6 col-lg-3">
                                <img id="logo-paytabs" src="{{ Vite::asset('resources/images/paytabs.jpeg') }}"
                                     class="rounded mx-auto d-block"/>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-3">
                                <img id="logo-pay" src="{{ Vite::asset('resources/images/pay.jpeg') }}"
                                     class="rounded mx-auto d-block"/>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-4">
                                <div class="input-group" id="validate-group">
                                    <input aria-describedby="simulate" type="number" class="form-control"
                                           id="ammount1" placeholder="{{__('Put your solde here')}}" required>
                                    <span class="input-group-text">$</span>
                                    <button class="btn btn-success" type="button" data-bs-target="#tr_paytabs"
                                            data-bs-toggle="modal" id="validate">{{ __('validate') }}
                                    </button>
                                </div>
                                <div class="input-group d-none">
                                    <input aria-describedby="simulate" type="number" class="form-control"
                                           id="ammount2" required>
                                    <span class="input-group-text">{{__('SAR')}}</span>
                                </div>
                                <div class="input-group">
                                    <button class="btn btn-outline-secondary d-none" type="button"
                                            id="simulate1">{{ __('simulate') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table
                            class="table table-striped table-bordered cell-border row-border table-hover mdl-data-table display nowrap"
                            id="ub_table"
                            style="width: 100%">
                            <thead class="table-light">
                            <tr class="head2earn  tabHeader2earn">
                                <th>{{ __('Operation order') }}</th>
                                <th>{{ __('ref') }}</th>
                                <th>{{ __('date') }}</th>
                                <th>{{ __('Operation Designation') }}</th>
                                <th>{{ __('Value') }}</th>
                                <th>{{ __('Balance') }}</th>
                                <th>{{ __('Complementary information') }}</th>
                            </tr>
                            </thead>
                            <tbody class="body2earn">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="tr_paytabs" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalgridLabel">{{ __('Cash transfert') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h5>{{ __('validate_transfert') }}</h5>
                        <div class="alert alert-primary material-shadow" role="alert">
                            {{__('The amount must be from 1 and less than 10000')}}
                        </div>
                        <h5 class="text-secondary">
                            <div class="row" id="usd"></div>
                        </h5>
                        <form class="needs-validation" novalidate>
                            <div class="row g-3">
                                <div class="col-lg-12">
                                    <div class="hstack gap-2 justify-content-end">
                                        <button type="button" class="btn btn-light"
                                                data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                                        <button type="button" id="tran_paytabs" class="btn btn-primary">
                                            {{ __('Submit') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script type="module">
            window.onload = function () {
                if ("{{ getUsertransaction(Auth()->user()->idUser)[0] }}" !== "null") {
                    if ({{ getUsertransaction(Auth()->user()->idUser)[0] }} === 1)
                        Swal.fire({
                            title: "{{ __('Transarction Accepted') }}",
                            text: "{{ getUsertransaction(Auth()->user()->idUser)[2] }}" + "$ Transfered",
                            icon: "success"
                        });
                    else
                        Swal.fire({
                            title: "{{ __('Transarction declined') }}",
                            text: "{{ __(getUsertransaction(Auth()->user()->idUser)[1]) }}",
                            icon: "error"
                        });
                }
            };

            $(document).on("click", "#validate", function () {
                if ($('#ammount1').val().length == 0) {
                    $("#ammount1").val(0)
                }
                const usd = document.getElementById("usd");
                $('#usd').removeClass("text-success");
                $('#usd').removeClass("text-danger");
                usd.innerHTML = '<div class="fs-20 ff-secondary fw-semibold mb-0 mt-2 col-12">' + $("#ammount1").val() + ' USD</div><div class="fs-20 ff-secondary fw-semibold mb-0 mt-2">{{__('to')}}</div><div class="fs-20 ff-secondary fw-semibold mb-0 mt-2 col-12">' + $("#ammount1").val() * {{ usdToSar() }} + ' SQR</div>';
                $('#ammount2').val($("#ammount1").val() * {{ usdToSar() }});
            });


            $("#tran_paytabs").one("click", function () {
                if ($("#ammount1").val() <= 0 || $("#ammount2").val() <= 0) {
                    $('#usd').addClass("text-danger");
                    $('#usd').removeClass("text-success");
                    return;
                }
                if ($("#ammount1").val() > 10000) {
                    $('#usd').addClass("text-danger");
                    $('#usd').removeClass("text-success");
                    return;
                }
                this.disabled = true;
                $('#usd').removeClass("text-danger");
                $('#usd').addClass("text-success");
                $('#ammount2').val($("#ammount1").val() * {{ usdToSar() }});
                var amount = $('#ammount2').val();
                var routeUrl = "{{ route('paytabs', app()->getLocale()) }}";
                routeUrl += "?amount=" + encodeURIComponent(amount);
                window.location.href = routeUrl;
            });

            document.addEventListener("DOMContentLoaded", function () {

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
                        "ajax": {
                            url: "{{route('api_user_balances',['locale'=> app()->getLocale(), 'idAmounts'=>'cash-Balance'])}}",
                            type: "GET",
                            headers: {'Authorization': 'Bearer ' + "{{generateUserToken()}}"},
                            error: function (xhr, error, thrown) {
                                $('#ub_table_processing').hide();
                                $('#ub_table tbody').html(
                                    '<tr><td colspan="7" class="text-center text-danger fw-bold">@lang("An error suppressed")</td></tr>'
                                );
                                $('#ub_table').DataTable().clear();
                                let modal = new bootstrap.Modal(document.getElementById('errorModal'));
                                modal.show();
                            }
                        },
                        "columns": [
                            {data: 'ranks'},
                            {data: 'reference'},
                            {data: 'created_at'},
                            {data: 'operation'},
                            {data: 'value'},
                            {data: 'current_balance'},
                            {data: 'complementary_information'},
                        ],
                        "columnDefs":
                            [
                                {
                                    "targets": [5],
                                    render: function (data, type, row) {
                                        if (data.indexOf('+') == -1)
                                            return '<span class="badge bg-danger text-end fs-14">' + data + '</span>';
                                        else
                                            return '<span class="badge bg-success text-end fs-14">' + data + '</span>';
                                    },
                                    className: classAl,
                                },
                            ],
                        "language": {"url": urlLang}
                    }
                );

            });
        </script>
    </div>
</div>
