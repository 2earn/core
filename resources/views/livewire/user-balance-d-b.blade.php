<div>
    @section('title'){{ __('Discounts Balance') }} @endsection
    @section('content')
        @component('components.breadcrumb')
            @slot('li_1')@endslot
            @slot('title') {{ __('Discounts Balance') }} @endslot
        @endcomponent
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table nowrap dt-responsive align-middle table-hover table-bordered" id="userBalanceDB_table" style="width: 100%">
                            <thead class="table-light">
                            <tr class="head2earn  tabHeader2earn" >
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
</div>
@push('scripts')
    <script data-turbolinks-eval="false">
        $(document).on('ready ', function () {
                $('#page-title-box').addClass('page-title-box-db');
            }
        );
    </script>
@endpush
