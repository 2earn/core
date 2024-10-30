<div>
    @section('title')
        {{ __('Platform') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Platform') }}
        @endslot
    @endcomponent
    <div class="modal fade" id="confirmDeletePlatformModal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{__('Confirm delete Platform')}}</h5>
                </div>
                <div class="modal-body">
                    <span class="text-muted" id="messageDeletePlatform"></span>
                </div>
                <div class="modal-footer">

                    <button type="button" id="confirmDeletePlatformConfirm" data-id=""
                            class="btn btn-danger">{{__('Confirm')}}</button>
                    <button type="button" class="btn btn-warning" id="confirmDeletePlatformClose"
                            data-dismiss="modal">{{__('Close')}}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row card">
        <div class="card-header border-info">

            <div class="row">
                <div class="col-sm-12 mx-2">
                    <a href="{{route('platform_create_update', app()->getLocale())}}"
                       class="btn btn-info add-btn float-end"
                       id="create-btn">
                        <i class="ri-add-line align-bottom me-1 ml-2"></i>
                        {{__('Create new platform')}}
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body table-responsive">
                            <table id="PlatformTable"
                                   class="table table-striped table-bordered cell-border row-border table-hover mdl-data-table display nowrap">
                                <thead class="table-light">
                                <tr class="head2earn  tabHeader2earn">
                                    <th>{{__('Id')}}</th>
                                    <th>{{__('Name')}}</th>
                                    <th>{{__('Type')}}</th>
                                    <th>{{__('Description')}}</th>
                                    <th>{{__('Created at')}}</th>
                                    <th>{{__('Updated at')}}</th>
                                    <th>{{__('Action')}}</th>
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
        $(document).on('turbolinks:load', function () {


            if (!$.fn.dataTable.isDataTable('#PlatformTable')) {
                $('#PlatformTable').DataTable({
                    "responsive": true,
                    "colReorder": true,
                    "orderCellsTop": true,
                    "fixedHeader": true,
                    initComplete: function () {
                        this.api()
                            .columns()
                            .every(function () {
                                var that = $('#PlatformTable').DataTable();
                                $('input', this.footer()).on('keydown', function (ev) {
                                    if (ev.keyCode == 13) {
                                        that.search(this.value).draw();
                                    }
                                });
                            });
                    },
                    "processing": true,
                    search: {return: true},
                    "ajax": "{{route('api_platform',app()->getLocale())}}",
                    "columns": [
                        {data: 'id'},
                        {data: 'name'},
                        {data: 'type'},
                        {data: 'description'},
                        {data: 'created_at'},
                        {data: 'updated_at'},
                        {data: 'action'},
                    ],
                    "language": {"url": urlLang},
                });
            }

            var confirmDeletePlatformModal = bootstrap.Modal.getOrCreateInstance('#confirmDeletePlatformModal');

            $('body').on('click', '#confirmDeletePlatformConfirm', function () {
                window.Livewire.emit("delete", $('#confirmDeletePlatformConfirm').attr('data-id'));
            });

            $('body').on('click', '#confirmDeletePlatformClose', function () {
                confirmDeletePlatformModal.hide();
            });

            $('body').on('click', '.deletePlatform', function (event) {
                $('#messageDeletePlatform').html('{{__('Are you sure to delete this platform')}}?' + ' <h5 class="float-end">' + $(event.target).attr('data-name') + ' </h5>');
                $('#confirmDeletePlatformConfirm').attr('data-id', $(event.target).attr('data-id'))
                confirmDeletePlatformModal.show();
            });

        });
    </script>

</div>
