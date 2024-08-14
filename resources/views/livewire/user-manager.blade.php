<div>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
    <div class="row">
        <div class="card">

            <ul class="nav nav-pills" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a href="" class="nav-link active"
                       id="pills-RepresentativesManagement-tab" data-bs-toggle="pill"
                       data-bs-target="#tabRepresentativesManagement"
                       type="button"
                       role="tab"
                       aria-controls="pills-RepresentativesManagement"
                       aria-selected="true">{{ __('Balance For Shopping') }}</a>
                </li>
            </ul>
            <div class="card-body pt-0">

                <div class="transaction-table">

                    <div class="table-responsive ">
                        <table class="table table-striped table-bordered cell-border row-border table-hover mdl-data-table display nowrap"   id="userManager_table"
                               style="width: 100%">
                            <thead>
                            <tr class="head2earn">
                                <th style=" border: none;">N</th>
                                <th style=" border: none;">{{ __('idUser') }}</th>
                                <th style=" border: none;">{{ __('Status') }}</th>
                                <th style=" border: none;"> {{ __('Source') }}</th>
                                <th style=" border: none;"> {{ __('PhoneNumber') }}</th>
                                <th style=" border: none;">{{ __('Name english') }}</th>
                                <th style=" border: none;">{{ __('Name Arabic') }}</th>
                                <th style=" border: none;">{{ __('LastOperationDate') }}</th>
                                <th style=" border: none;">{{ __('CountryName') }}</th>
                                <th style=" border: none;">{{ __('Actions') }}</th>
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
    <!-- Modal -->
    <div class="modal fade" id="userManagerModal" style="z-index: 200000" tabindex="-99999999"
         aria-labelledby="userManagerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <script type="module">
        function deleteUser(idUser) {
            Swal.fire({
                title: '{{ __('delete_user') }}',
                text: '{{ __('operation_irreversible') }}',
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: '{{trans('ok')}}',
                cancelButtonText: '{{trans('canceled !')}}',
                denyButtonText: '{{trans('No')}}',
                customClass: {
                    actions: 'my-actions',
                    cancelButton: 'order-1 right-gap',
                    confirmButton: 'order-2',
                    denyButton: 'order-3',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.Livewire.emit('deleteUser', idUser);
                }
            });
        }

            $(document).on('turbolinks:load', function () {
                $('#userManager_table').DataTable({
                    retrieve: true,
                    "colReorder": true,
                    "orderCellsTop": true,
                    "fixedHeader": true,
                    initComplete: function () {
                        this.api()
                            .columns()
                            .every(function () {
                                var that = $('#userManager_table').DataTable();
                                $('input', this.footer()).on('keydown', function (ev) {
                                    if (ev.keyCode == 13) {
                                        that
                                            .search(this.value)
                                            .draw();
                                    }
                                });
                            });
                    },
                    "processing": true,
                    search: {return: true},
                    "ajax": "{{route('API_usermanager',app()->getLocale())}}",
                    "columns": [
                        {data: 'N'},
                        {data: 'idUser'},
                        {data: 'status'},
                        {data: 'registred_from'},
                        {data: 'fullphone_number'},
                        {data: 'LatinName'},
                        {data: 'ArabicName'},
                        {data: 'lastOperation'},
                        {data: 'country'},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ],
                    "language": {"url": urlLang}
                });
            });

    </script>
    <script data-turbolinks-eval="false">
        var SuccesUpdatePasswordUserAdmin = '{{ Session::has('SuccesUpdatePasswordUserAdmin')}}'
        if (SuccesUpdatePasswordUserAdmin) {
            toastr.success('{{Session::get('SuccesUpdatePasswordUserAdmin')}}');
        }
        var SuccesUpdateProfil = '{{ Session::has('SuccesUpdateProfil')}}'
        if (SuccesUpdateProfil) {
            toastr.success('{{Session::get('SuccesUpdateProfil')}}');
        }


    </script>

</div>
