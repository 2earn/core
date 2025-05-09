<div class="container-fluid">
    <div class="container-fluid">
        @component('components.breadcrumb')
            @slot('title')
                {{ __('Coupon history') }}
            @endslot
        @endcomponent
        <div class="card">
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
                                            <th>{{__('Pin')}}</th>
                                            <th>{{__('sn')}}</th>
                                            <th>{{__('Dates')}}</th>
                                            <th>{{__('Value')}}</th>
                                            <th>{{__('Consumed')}}</th>
                                            <th>{{__('Platform')}}</th>
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
                        "ajax": "{{route('api_user_coupon',app()->getLocale())}}",
                        "columns": [
                            {data: null, defaultContent: '<input type="checkbox" class="row-select" />'},
                            datatableControlBtn,
                            {data: 'pin'},
                            {data: 'sn'},
                            {data: 'dates'},
                            {data: 'value'},
                            {data: 'consumed'},
                            {data: 'platform_id'},
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
                }
                $('body').on('click', '.consumecoupon', function (event) {
                    Swal.fire({
                        title: '{{__('Are you sure to mark this Coupon as consumed')}}? <h5 class="float-end">' + $(event.target).attr('data-name') + ' </h5>',
                        showDenyButton: true,
                        showCancelButton: true,
                        confirmButtonText: "{{__('Consume')}}",
                        denyButtonText: `Rollback`
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.Livewire.dispatch("markAsConsumed", [$(event.target).attr('data-id')]);
                        }
                    });
                });
                $('body').on('click', '.copycoupon', function (event) {
                    Swal.fire({
                        title: '{{__('Copy this Coupon')}}' + '<h5 class="float-end">' + $(event.target).attr('data-name') + ' </h5>',
                        html: '{{__('Give me your password')}}',
                        allowOutsideClick: false,
                        timer: '{{ env('timeOPT',180000) }}',
                        timerProgressBar: true,
                        showCancelButton: true,
                        cancelButtonText: '{{trans('canceled !')}}',
                        confirmButtonText: '{{trans('ok')}}',
                        footer: '<i></i><div class="footerOpt"></div>',
                        didOpen: () => {
                            const b = Swal.getFooter().querySelector('i');
                            const p22 = Swal.getFooter().querySelector('div');
                            p22.innerHTML = '<br>' + '{{trans('Dont get code?') }}' + ' <a>' + '{{trans('Resend')}}' + '</a>';
                        },
                        input: 'text',
                        inputAttributes: {autocapitalize: 'off'},
                    }).then((resultat) => {
                        if (resultat.isConfirmed) {
                            window.Livewire.dispatch('verifPassword', [resultat.value]);
                        }
                    }).catch((error) => {
                        console.error('SweetAlert Error:', error);
                    });
                });
                window.addEventListener('showPin', event => {
                    Swal.fire({
                        title: event.detail[0].title,
                        text: event.detail[0].text,
                        icon: 'error',
                        confirmButtonText: "{{__('ok')}}"
                    })
                });
                window.addEventListener('cancelPin', event => {
                    Swal.fire({
                        title: event.detail[0].title,
                        text: event.detail[0].text,
                        icon: 'error',
                        confirmButtonText: "{{__('ok')}}"
                    })
                });
            });
        </script>
    </div>
</div>
