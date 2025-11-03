<div class="container-fluid">
    @section('title')
        {{ __('Coupon') }}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Coupon') }}
        @endslot
    @endcomponent
    @include('layouts.flash-messages')
    <div class="card">
        <div class="card-header border-info">
            <div class="row">
                <div class="col-sm-12 col-md-12  col-lg-12">
                    <button id="deleteAll"
                            class="btn btn-soft-danger material-shadow-none mt-1">{{__('Delete')}}</button>
                    <a href="{{route('coupon_injector_create',['locale'=>app()->getLocale()])}}"
                       class="btn btn-soft-info material-shadow-none mt-1 float-end"
                       id="create-btn">
                        {{__('Add Coupons list')}}
                    </a>
                </div>
            </div>
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
                        url: "{{route('api_coupon_injector',['locale'=> app()->getLocale()])}}",
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

                $('#selectAll').on('click', function () {
                    var rows = table.rows({'search': 'applied'}).nodes();
                    $('input[type="checkbox"]', rows).prop('checked', this.checked);
                });

                $('#Coupon_table tbody').on('change', 'input[type="checkbox"]', function () {
                    if (!this.checked) {
                        var el = $('#selectAll').get(0);
                        if (el && el.checked && ('indeterminate' in el)) {
                            el.indeterminate = true;
                        }
                    }
                });

                $('#deleteAll').on('click', function () {
                    var ids = [];
                    table.$('input[type="checkbox"]:checked').each(function () {
                        var row = $(this).closest('tr');
                        var data = table.row(row).data();
                        ids.push(data.id);
                    });
                    if (ids.length > 0) {
                        Swal.fire({
                            title: '{{__('Are you sure you want to delete the selected rows?')}}',
                            showDenyButton: true,
                            showCancelButton: true,
                            confirmButtonText: "{{__('Delete')}}",
                            denyButtonText: `Rollback`
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: "{{route('api_delete_injector_coupons',app()->getLocale())}}",
                                    method: "POST",
                                    headers: {'Authorization': 'Bearer ' + "{{generateUserToken()}}"},
                                    data: {
                                        ids: ids,
                                        _token: "{{ csrf_token() }}"
                                    },
                                    success: function (response) {
                                        table.ajax.reload();
                                    },
                                    error: function (xhr) {
                                        alert("An error occurred while deleting the records.");
                                    }
                                });
                            }
                        });
                    } else {

                        Swal.fire({
                            title: '{{__('No rows selected')}}',
                            text: '{{__('Please select at least one row before proceeding.')}}',
                            icon: 'warning',
                            confirmButtonText: 'OK'
                        });

                    }
                });
            }

            $('body').on('click', '.deletecoupon', function (event) {
                Swal.fire({
                    title: '{{__('Are you sure to delete this Coupon')}}? <h5 class="float-end">' + $(event.target).attr('data-name') + ' </h5>',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: "{{__('Delete')}}",
                    denyButtonText: `Rollback`
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.Livewire.dispatch("delete", [$(event.target).attr('data-id')]);
                    }
                });
            });
        });
    </script>
</div>
