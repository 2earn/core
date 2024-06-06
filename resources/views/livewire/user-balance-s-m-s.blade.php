<div>
    @section('title')
        {{ __('sms balance') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('sms balance') }}
        @endslot
    @endcomponent
    @section('content')
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table nowrap dt-responsive align-middle table-hover table-bordered"
                               id="userBalanceSMS_table" style="width: 100%">
                            <thead class="table-light">
                            <tr class="head2earn  tabHeader2earn">
                                <th>{{__('numero')}}</th>
                                <th>{{ __('ref') }}</th>
                                <th>{{ __('date') }}</th>
                                <th>{{ __('Operation Designation') }}</th>
                                <th>{{ __('description') }}</th>

                                <th>{{__('Prix')}}</th>
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
            <!--end col-->
        </div>
        <!--end row-->

</div>









