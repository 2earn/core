<div>
    @section('title')
        {{ __('history') }}
    @endsection
    @section('content')

        @component('components.breadcrumb')
            @slot('li_1')@endslot
            @slot('title')
                {{ __('history') }}
            @endslot
        @endcomponent
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                    </div>
                    <div class="card-body table-responsive">
                        <table id="userPurchase_table"
                               class="table nowrap dt-responsive align-middle table-hover table-bordered"
                               style="width:100%">
                            <thead class="table-light">
                            <tr class="head2earn  tabHeader2earn">
                                <th>{{__('date')}}</th>
                                <th>{{__('ref')}}</th>
                                <th>{{__('Item')}}</th>
                                <th>{{__('Quantity')}}</th>
                                <th>{{__('Amout')}}</th>
                                <th>{{__('invitation to purchase')}}</th>
                                <th>{{__('Visit')}}</th>
                                <th>{{__('Proactive BFS')}}</th>
                                <th>{{__('Proactive CB')}}</th>
                                <th>{{__('Cash back BFS')}}</th>
                                <th>{{__('Cash back CB')}}</th>
                                <th>{{__('Economy')}}</th>
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
