<div class="{{getContainerType()}}">
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Target') }} <span class="text-muted mx-2">›</span>
            <span class="text-primary">#{{ $target->id }}</span>
            <span class="text-muted mx-2">-</span>
            {{ $target->name }}
        @endslot
    @endcomponent

    <div class="row">
        @include('layouts.flash-messages')
    </div>

    <div class="row">
        <div class="col-12 mb-3">
            @include('livewire.target-item', ['target' => $target])
        </div>

        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="fa fa-info-circle text-primary me-2"></i>
                        {{ __('Target details') }}
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Warning Alert -->
                    <div id="warningDetail" class="alert alert-warning alert-dismissible fade show d-none mb-4" role="alert">
                        <i class="fa fa-exclamation-triangle me-2"></i>
                        <span class="alert-message"></span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                    <!-- SQL Section -->
                    <div class="mb-4">
                        <h6 class="fw-semibold text-secondary mb-3 pb-2 border-bottom">
                            <i class="fa fa-code me-2"></i>{{__('SQL')}}
                        </h6>
                        <div class="bg-light rounded p-3">
                            <pre class="mb-0"><code class="text-dark">{{$sql}}</code></pre>
                        </div>
                    </div>

                    <!-- Results Section -->
                    <div>
                        <h6 class="fw-semibold text-secondary mb-3 pb-2 border-bottom">
                            <i class="fa fa-table me-2"></i>{{__('Resultats')}}
                        </h6>
                        <div class="table-responsive">
                            <table id="TargetTable"
                                   class="table table-striped table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{__('Id')}}</th>
                                        <th>{{__('Name')}}</th>
                                        <th>{{__('Email')}}</th>
                                        <th>{{__('Status')}}</th>
                                        <th>{{__('Fullphone number')}}</th>
                                        <th>{{__('Detail')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
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

            $.fn.dataTable.ext.errMode = function (settings, helpPage, message) {
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
                        {data: 'fullphone_number'},
                        {data: 'detail'}
                    ],
                    "ajax": {
                        url: "{{route('api_target_data',['idTarget'=> $target->id])}}",
                        type: "GET",
                        headers: {'Authorization': 'Bearer ' + "{{generateUserToken()}}"},
                        error: function (xhr, error, thrown) {
                            loadDatatableModalError('Coupon_table')
                        }
                    },
                    "language": {"url": urlLang},
                });
            }
        });
    </script>
</div>
