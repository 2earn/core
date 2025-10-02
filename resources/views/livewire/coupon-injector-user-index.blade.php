<div class="card">
    <div class="card-header align-items-center border-0 d-flex">
        <h4 class="card-title mb-0 flex-grow-1">{{__('Coupon runner table')}}</h4>
    </div>
    <div class="card-body">
        <div class="row card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table id="Coupon_table"
                                   class="table table-striped table-bordered cell-border row-border table-hover mdl-data-table display nowrap">
                                <thead class="table-light">
                                <tr class="head2earn tabHeader2earn">
                                    <th><input type="checkbox" id="selectAll"/></th>
                                    <th>{{__('Details')}}</th>
                                    <th>{{__('Category')}}</th>
                                    <th>{{__('Pin')}}</th>
                                    <th>{{__('sn')}}</th>
                                    <th>{{__('Dates')}}</th>
                                    <th>{{__('Value')}}</th>
                                    <th>{{__('Consumed')}}</th>
                                    <th>{{__('Action')}}</th>
                                </tr>
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
        document.addEventListener("DOMContentLoaded", function () {

            if (!$.fn.dataTable.isDataTable('#Coupon_table')) {
                var table = $('#Coupon_table').DataTable({
                    "responsive": true,
                    "colReorder": true,
                    "orderCellsTop": true,
                    "fixedHeader": true,
                    initComplete: function () {
                        this.api().columns().every(function () {
                            var that = $('#Coupon_table').DataTable();
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
                        url: "{{route('api_user_coupon_injector',['locale'=> app()->getLocale()])}}",
                        type: "GET",
                        headers: {'Authorization': 'Bearer ' + "{{generateUserToken()}}"},
                        error: function (xhr, error, thrown) {
                            loadDatatableModalError('Coupon_table')
                        }
                    },
                    "columns": [
                        {
                            data: null,
                            defaultContent: '<input type="checkbox" class="row-select" />',
                            orderable: false,
                            searchable: false
                        },
                        datatableControlBtn,
                        {data: 'category'},
                        {data: 'pin'},
                        {data: 'sn'},
                        {data: 'dates'},
                        {data: 'value'},
                        {data: 'consumed'},
                        {data: 'action'},
                    ],
                    "language": {"url": urlLang},
                });
            }
        });
    </script>
</div>
