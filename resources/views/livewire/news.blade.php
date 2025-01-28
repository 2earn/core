<div>
    @component('components.breadcrumb')
        @slot('title')
            {{ __('News') }}
        @endslot
    @endcomponent
    <div class="row m-1">
        <div class="col-10">
            @include('layouts.flash-messages')
        </div>
        @if(\App\Models\User::isSuperAdmin())
        <div class="col-2">
            <a href="{{route('news_create_update',app()->getLocale())}}"
               class="btn btn-soft-primary float-end">{{__('Add news')}}</a>
        </div>
        @endif
    </div>
    <div class="row card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table id="NewsTable"
                               class="table table-striped table-bordered cell-border row-border table-hover mdl-data-table display nowrap">
                            <thead class="table-light">
                            <tr class="head2earn  tabHeader2earn">
                                <th>{{__('Details')}}</th>
                                <th>{{__('Id')}}</th>
                                <th>{{__('Title')}}</th>
                                <th>{{__('Enabled')}}</th>
                                <th>{{__('Published at')}}</th>
                                <th>{{__('Actions')}}</th>
                                <th>{{__('Content')}}</th>
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
        $(document).on('turbolinks:load', function () {
            if (!$.fn.dataTable.isDataTable('#NewsTable')) {
                $('#NewsTable').DataTable({
                    "responsive": true,
                    "colReorder": true,
                    "orderCellsTop": true,
                    "fixedHeader": true,
                    initComplete: function () {
                        this.api()
                            .columns()
                            .every(function () {
                                var that = $('#NewsTable').DataTable();
                                $('input', this.footer()).on('keydown', function (ev) {
                                    if (ev.keyCode == 13) {
                                        that.search(this.value).draw();
                                    }
                                });
                            });
                    },
                    "processing": true,
                    search: {return: true},
                    "ajax": "{{route('api_news',app()->getLocale())}}",
                    "columns": [
                        datatableControlBtn,
                        {data: 'id'},
                        {data: 'title'},
                        {data: 'enabled'},
                        {data: 'published_at'},
                        {data: 'action'},
                        {data: 'content'},
                    ],
                    "language": {"url": urlLang},
                });
            }
            $('body').on('click', '.deleteNews', function (event) {
                console.log('aaaaa')
                Swal.fire({
                    title: '{{__('Are you sure to delete this News')}}? <h5 class="float-end">' + $(event.target).attr('data-name') + ' </h5>',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: "Delete",
                    denyButtonText: `Rollback`
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.Livewire.emit("delete", $(event.target).attr('data-id'));
                    }
                });
            });
        });
    </script>
</div>
