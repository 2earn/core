
<div>
    @section('title'){{ __('history') }} @endsection
    @section('content')

        @component('components.breadcrumb')
            @slot('li_1')@endslot
            @slot('title') {{ __('history') }} @endslot
        @endcomponent
            <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                    </div>
                    <div class="card-body table-responsive">
                        <table id="userPurchase_table" class="table nowrap dt-responsive align-middle table-hover table-bordered" style="width:100%">
                            <thead  class="table-light">
                            <tr class="head2earn  tabHeader2earn" >
                                <th style=" border: none;">{{__('Date')}}</th>
                                <th style=" border: none ;text-align: center; ">{{__('Ref')}}</th>
                                <th style=" border: none;text-align: center; ">{{__('Item')}}</th>
                                <th style=" border: none ;text-align: center;">{{__('Quantity')}}</th>
                                <th style=" border: none;text-align: center; ">{{__('Amout')}}</th>
                                <th style=" border: none ;text-align: center;">{{__('invitation to purchase')}}</th>
                                <th style=" border: none ;text-align: center; ">{{__('Visit')}}</th>
                                <th style=" border: none ; text-align: center; ">{{__('Proactive BFS')}}</th>
                                <th style=" border: none ;text-align: center;">{{__('Proactive CB')}}</th>
                                <th style=" border: none;text-align: center; ">{{__('Cash back BFS')}}</th>
                                <th style=" border: none;text-align: center; ">{{__('Cash back CB')}}</th>
                                <th style=" border: none ;text-align: center;">{{__('Economy')}}</th>
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
