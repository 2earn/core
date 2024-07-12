<div>
    @section('title')
        {{ __('Cash Balance') }}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Cash Balance') }}
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
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
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-striped table-bordered"  id="ub_table"
                           style="width: 100%">
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
    <div class="modal fade" id="tr_paytabs" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalgridLabel">{{ __('Cash transfert') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>{{ __('validate_transfert') }}</h5>
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
            const usd = document.getElementById("usd");
            $('#usd').removeClass("text-success");
            $('#usd').removeClass("text-danger");
            usd.innerHTML = '<div class="fs-20 ff-secondary fw-semibold mb-0 mt-2 col-12">' + $("#ammount1").val() + ' USD</div><div class="fs-20 ff-secondary fw-semibold mb-0 mt-2">{{__('to')}}</div><div class="fs-20 ff-secondary fw-semibold mb-0 mt-2 col-12">' + $("#ammount1").val() * {{ usdToSar() }} + ' SQR</div>';
            $('#ammount2').val($("#ammount1").val() * {{ usdToSar() }});
        });

        $(document).on("click", "#tran_paytabs", function () {
            if ($("#ammount1").val() > 0 && $("#ammount2").val() > 0) {
                this.disabled = true;
                $('#usd').removeClass("text-danger");
                $('#usd').addClass("text-success");
                $('#ammount2').val($("#ammount1").val() * {{ usdToSar() }});
                var amount = $('#ammount2').val();
                var routeUrl = "{{ route('paytabs', app()->getLocale()) }}";
                routeUrl += "?amount=" + encodeURIComponent(amount);
                window.location.href = routeUrl;
            } else {
                $('#usd').addClass("text-danger");
                $('#usd').removeClass("text-success");
            }
        });

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
                    "language": {"url": urlLang}
                }
            );
        });
    </script>
</div>
