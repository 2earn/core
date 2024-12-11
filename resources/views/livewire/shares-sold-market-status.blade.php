<div>
    @section('title')
        {{ __('Shares Sold : market status') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Shares Sold : market status') }}
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-12">
            <div class="card" id="marketList">
                <div class="card-header border-bottom-dashed d-flex align-items-center">
                    <h4 class="card-title mb-0 flex-grow-1">{{__('Market Status')}}</h4>
                    <div class="flex-shrink-0">
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <button type="button" class="btn btn-primary btn-sm">{{__('Today')}}</button>
                            <button type="button" class="btn btn-outline-primary btn-sm">{{__('Overall')}}</button>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table id="shares-sold"
                           class="table table-striped table-bordered cell-border row-border table-hover mdl-data-table display nowrap">
                        <thead class="table-light">
                        <tr class="head2earn  tabHeader2earn">
                            <th>{{__('date_purchase')}}</th>
                            <th>{{__('countrie')}}</th>
                            <th>{{__('mobile')}}</th>
                            <th>{{__('Name')}}</th>
                            <th>{{__('total_shares')}}</th>
                            <th>{{__('sell_price_now')}}</th>
                            <th>{{__('gains')}}</th>
                            <th>{{__('Real_Sold')}}</th>
                            <th>{{__('Real_Sold_amount')}}</th>
                            <th>{{__('total_price')}}</th>
                            <th>{{__('number_of_shares')}}</th>
                            <th>{{__('share_price')}}</th>
                            <th>{{__('heure_purchase')}}</th>
                        </tr>
                        </thead>
                        <tfoot>
                        </tfoot>
                        <tbody class="body2earn">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal fade" id="realsoldmodif" tabindex="-1" aria-labelledby="exampleModalgridLabel"
                 aria-modal="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ __('Transfert Cash') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="javascript:void(0);">
                                <div class="row g-3">
                                    <div class="col-xxl-6">
                                        <div class="input-group">
                                                    <span class="input-group-text">
                                                        <img id="realsold-country" alt=""
                                                             class="avatar-xxs me-2"></span>
                                            <input type="text" class="form-control" disabled id="realsold-phone"
                                                   aria-describedby="basic-addon1">
                                        </div>
                                    </div>
                                    <div class="col-xxl-6">
                                        <div class="input-group">
                                            <input id="realsold-reciver" type="hidden">
                                            <input type="number" class="form-control" id="realsold-ammount">
                                            <input hidden type="number" class="form-control"
                                                   id="realsold-ammount-total">
                                            <span class="input-group-text">$</span>

                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="button" class="btn btn-light"
                                                    data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                                            <button type="button" id="realsold-submit"
                                                    class="btn btn-primary">{{ __('Submit') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="module">
        $(document).on('turbolinks:load', function () {
            $('#shares-sold').DataTable(
                {
                    "ordering": true,
                    retrieve: true,
                    "colReorder": false,
                    dom: 'Bfrtip',
                    buttons: [
                        {extend: 'copyHtml5', text: '<i class="ri-file-copy-2-line"></i>', titleAttr: 'Copy'},
                        {extend: 'excelHtml5', text: '<i class="ri-file-excel-2-line"></i>', titleAttr: 'Excel'},
                        {extend: 'csvHtml5', text: '<i class="ri-file-text-line"></i>', titleAttr: 'CSV'},
                        {extend: 'pdfHtml5', text: '<i class="ri-file-pdf-line"></i>', titleAttr: 'PDF'}
                    ],
                    "orderCellsTop": true,
                    "fixedHeader": true,
                    "order": [[14, 'desc']],
                    "processing": true,
                    "serverSide": false,
                    "pageLength": 1000,
                    "aLengthMenu": [[10, 30, 50, 100, 1000], [10, 30, 50, 100, 1000]],
                    search: {return: true},
                    autoWidth: false,
                    bAutoWidth: false,
                    "ajax": "{{route('api_shares_soldes',['locale'=> app()->getLocale()])}}",
                    "columns": [
                        {data: 'formatted_created_at_date'},
                        {data: 'flag'},
                        {data: 'mobile'},
                        {data: 'Name'},
                        {data: 'total_shares'},
                        {data: 'sell_price_now'},
                        {data: 'gain'},
                        {data: 'payed'},
                        {data: 'current_balance', "className": 'editable'},
                        {data: 'total_price'},
                        {data: 'value'},
                        {data: 'unit_price'},
                        {data: 'formatted_created_at'},
                    ],
                    "columnDefs":
                        [
                            {
                                "targets": [7],
                                render: function (data, type, row) {
                                    if (Number(row.payed) === 1)
                                        return '<span class="badge bg-success" data-id="' + row.id + '" data-phone="' + row.mobile +
                                            '" data-asset="' + row.asset + '" data-amount="' + row.total_price + '" >{{__('Transfert Made')}}</span>';
                                    if (Number(row.payed) === 0)
                                        return '<span class="badge bg-danger" data-id="' + row.id + '" data-phone="' + row.mobile +
                                            '" data-asset="' + row.asset + '" data-amount="' + row.total_price + '" >{{__('Free')}}</span>';
                                    if (Number(row.payed) === 2)
                                        return '<span class="badge bg-warning" data-id="' + row.id + '" data-phone="' + row.mobile +
                                            '" data-asset="' + row.asset + '" data-amount="' + row.total_price + '" >{{__('Mixed')}}</span>';
                                },
                            },
                        ],
                    "language": {"url": urlLang}
                }
            );
        });
    </script>
    <script type="module">
        $(document).on('turbolinks:load', function () {
            var select2_array = [];
            var classAl = "text-end";
            var tts = '{{config('app.available_locales')[app()->getLocale()]['direction']}}';
            if (tts == 'rtl') {
                classAl = "text-start";
            }
            var url = '';
            $(document).on('click', '.badge', function () {
                var id = $(this).data('id');
                var phone = $(this).data('phone');
                var amount = String($(this).data('amount')).replace(',', '');
                var asset = $(this).data('asset');
                $('#realsold-country').attr('src', asset);
                $('#realsold-reciver').attr('value', id);
                $('#realsold-phone').attr('value', phone);
                $('#realsold-ammount').attr('value', amount);
                $('#realsold-ammount-total').attr('value', amount);
                $('#realsoldmodif').modal('show');
                fetchAndUpdateCardContent();
                $('#shares-sold').DataTable().ajax.reload();
            });
            $(document).on("click", "#realsold-submit", function () {
                let reciver = $('#realsold-reciver').val();
                let ammount = $('#realsold-ammount').val();
                let total = $('#realsold-ammount-total').val()
                $.ajax({
                    url: "{{ route('update-balance-real', app()->getLocale()) }}",
                    type: "POST",
                    data: {total: total, amount: ammount, id: reciver, "_token": "{{ csrf_token() }}"},
                    success: function (data) {
                        $('#realsoldmodif').modal('hide');
                        $('#shares-sold').DataTable().ajax.reload();
                        fetchAndUpdateCardContent();
                    }

                });

                function saveHA() {
                    window.livewire.emit('saveHA', $("#tags").val());
                }

                function fetchAndUpdateCardContent() {
                    $.ajax({
                        url: '{{ route('get-updated-card-content',app()->getLocale()) }}', // Adjust the endpoint URL
                        method: 'GET',
                        success: function (data) {
                            $('#realrev').html('$' + data.value);
                        },
                        error: function (xhr, status, error) {
                            console.log(error)
                        }
                    });
                }

                $("#select2bfs").select2();

                $("#select2bfs").on("select2:select select2:unselect", function (e) {
                    var items = $(this).val();
                    if ($(this).val() == null) {
                        table_bfs.columns(3).search("").draw();
                    } else {
                        table_bfs.columns(3).search(items.join('|'), true, false).draw();
                    }
                })
            });
        });

    </script>
</div>
