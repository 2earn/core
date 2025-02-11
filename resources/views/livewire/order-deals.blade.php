@if($orderDeals->count())
<div class="col-md-12 text-muted">
    <div class="card mt-2">
        <div class="card-header">
            <h6 class="card-title mb-0">{{__('Order deals Turnovers')}}</h6>
        </div>
        <div class="card-body">
            <table class="table table-border table-striped table-card table-nowrap">
                <thead>
                <tr>
                    <th  scope="col" >#</th>
                    <th  scope="col" >{{__('Deal')}}</th>
                    <th  scope="col" >{{__('Total amount')}}</th>
                    <th  scope="col" >{{__('Partner discount')}}</th>
                    <th  scope="col" >{{__('Earn discount')}}</th>
                    <th  scope="col" >{{__('Deal discount')}}</th>
                    <th  scope="col" >{{__('Total discount')}}</th>
                    <th  scope="col" >{{__('Final discount')}}</th>
                    <th  scope="col" >{{__('Lost discount')}}</th>
                    <th  scope="col" >{{__('Final amount')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($orderDeals as $key => $orderDealsItem)
                    <tr>
                        <td>
                            {{$orderDealsItem->id}}
                        </td>
                        <td>
                            @if(\App\Models\User::isSuperAdmin())
                                <a href="{{route('deals_show',['locale'=>app()->getLocale(),'id'=>$orderDealsItem->deals()->first()->id])}}"><span
                                        class="float-end"> {{$orderDealsItem->deals()->first()->id}} - {{$orderDealsItem->deals()->first()->name}}</span>
                                </a>
                            @else
                                <span
                                    class="float-end"> {{$orderDealsItem->deals()->first()->id}} - {{$orderDealsItem->deals()->first()->name}}</span>
                            @endif
                        </td>
                        <td>
                            {{$orderDealsItem->total_amount}}  {{config('app.currency')}}
                        </td>
                        <td>
                            {{$orderDealsItem->partner_discount}}  {{config('app.currency')}}

                            <span
                                class="badge bg-primary text-end fs-14 float-end"> {{$orderDealsItem->amount_after_partner_discount}}  {{config('app.currency')}}</span>
                        </td>
                        <td>
                            {{$orderDealsItem->earn_discount}}  {{config('app.currency')}}
                            <span
                                class="badge bg-primary text-end fs-14 float-end">   {{$orderDealsItem->amount_after_earn_discount}}  {{config('app.currency')}}</span>
                        </td>
                        <td>
                            {{$orderDealsItem->deal_discount}}  {{config('app.currency')}}
                            <hr>
                            {{$orderDealsItem->deal_discount_percentage}}  {{config('app.percentage')}}
                        </td>
                        <td>
                            {{$orderDealsItem->total_discount}}  {{config('app.currency')}}
                        </td>
                        <td>
                            {{$orderDealsItem->final_discount}}  {{config('app.currency')}}
                            <hr>
                            {{$orderDealsItem->final_discount_percentage}}  {{config('app.percentage')}}
                        </td>
                        <td>
                            {{$orderDealsItem->lost_discount}}  {{config('app.currency')}}
                        </td>
                        <td>
                            <span
                                class="badge bg-secondary text-end fs-14 float-end">      {{$orderDealsItem->final_amount}}  {{config('app.currency')}}</span>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
