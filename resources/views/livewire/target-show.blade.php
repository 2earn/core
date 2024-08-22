<div>
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Target Show') }} : {{ $target->id }} - {{ $target->name }}
        @endslot
    @endcomponent
    <div class="row">
        @include('layouts.flash-messages')
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12">
            @include('livewire.target-item', ['target' => $target])
        </div>
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header border-info fw-medium text-muted mb-0">
                    <h5 class="text-info"> {{ __('Target details') }}</h5>


                    <div id="warningDetail" class="alert alert-warning d-none" role="alert">
                    </div>
                </div>
                <div class="card-body row">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <h6 class="mt-2 text-info">{{__('SQL')}}:</h6>
                        <code class="text-muted">{{$sql}}</code>
                    </div>

                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <h6 class="mt-2 text-info">{{__('Resultats')}}:</h6>

                        <div class="card-body table-responsive">
                            <table id="TargetTable"
                                   class="table table-striped table-bordered cell-border row-border table-hover mdl-data-table display nowrap">
                                <thead class="table-light">
                                <tr class="head2earn  tabHeader2earn">
                                    <th>{{__('id')}}</th>
                                    <th>{{__('name')}}</th>
                                    <th>{{__('email')}}</th>
                                    <th>{{__('status')}}</th>
                                    <th>{{__('fullphone_number')}}</th>
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
            $.fn.dataTable.ext.errMode = function (settings, helpPage, message) {
                console.log('Error : ', message);
                $("#warningDetail").removeClass("d-none")
                $('#warningDetail').html('').append(message)
            };
            if (!$.fn.dataTable.isDataTable('#HistoryNotificationTable')) {
                $('#TargetTable').DataTable({
                    "responsive": true,
                    "colReorder": true,
                    "orderCellsTop": true,
                    "fixedHeader": true,
                    "processing": true,
                    search: {return: true},
                    "columns": [
                        {data: 'id'},
                        {data: 'name'},
                        {data: 'email'},
                        {data: 'status'},
                        {data: 'fullphone_number'}
                    ],
                    "ajax": "{{route('api_target_data',['locale'=>app()->getLocale(),'idTarget'=> $target->id])}}",
                    "language": {"url": urlLang},
                });
            }
        });

    </script>
</div>
