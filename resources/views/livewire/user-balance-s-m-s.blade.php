<div>
    @section('title'){{ __('SMS BALANCE') }} @endsection
    @section('content')

        @component('components.breadcrumb')
            @slot('li_1')@endslot
            @slot('title') {{ __('SMS BALANCE') }} @endslot
        @endcomponent
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
                                <th style="text-align: start; border: none;">N°</th>
                                <th style="text-align: start; border: none ">{{ __('Ref') }}</th>
                                <th style="text-align: start; border: none ">{{ __('Date') }}</th>
                                <th style=" text-align: start;border: none ">{{ __('Operation Designation') }}</th>
                                <th style="text-align: start; border: none ">{{ __('Description') }}</th>

                                <th style=" text-align: start;border: none ">{{__('Prix')}}</th>
                                <th style=" text-align: start; border: none ">{{ __('Value') }}</th>
                                <th style=" text-align: start; border: none ">{{ __('Balance') }}</th>
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









