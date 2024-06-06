<div>
    @section('title')
        {{ __('Balance For Shopping') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Balance For Shopping') }}
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row g-4">
                        <div class="col-sm">
                            <div class="justify-content-sm-end">
                                <div class="search-box ms-2">
                                    <p>{{ __('bfs description') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <h6 class="fw-semibold">{{__('Opération')}}</h6>
                        <select class="select2-hidden-accessible bfs_operation_multiple" name="states[]"
                                id="select2bfs" multiple="multiple">
                        </select>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table class="table nowrap dt-responsive align-middle table-hover table-bordered"
                           id="ub_table_bfs" style="width: 100%">
                        <thead class="table-light">
                        <tr class=" tabHeader2earn">
                            <th>{{__('Num')}}</th>
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
    <script>
        $(document).on('ready ', function () {
                $('#page-title-box').addClass('page-title-box-bfs');
            }
        );
    </script>
</div>
