<div class="container-fluid">
    @section('title')
        {{ __('User purchase history') }}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
            {{ __('User purchase history') }}
        @endslot
    @endcomponent
    <div class="card row">
        <div class="card-header">
            <h6 class="card-title mb-0">{{__('Filters')}}</h6>
        </div>
        <div class="card-body row">
            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="row m-1 card border border-muted">
                    <div class="card-body border-info">
                        <label>{{__('Business sectors')}}</label>
                        <div class="row">
                            @if($allSectors->count())
                                @foreach($allSectors as $sector)
                                    <div class="col-auto">
                                        <div class="form-check form-switch form-check-inline" dir="ltr">
                                            <label
                                                class="text-muted"
                                                for="sector.{{__($sector->name)}}">{{__($sector->name)}}</label>
                                            <input type="checkbox" class="form-check-input"
                                                   wire:model="selectedSectorsIds"
                                                   value="{{$sector->id}}"
                                                   id="sector.{{__($sector->name)}}">
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-auto text-muted">
                                    {{__('No sector from orders')}}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="row m-1 card border border-muted">
                    <div class="card-body border-info">
                        <label>{{__('Platforms')}}</label>
                        <div class="row">
                            @if($allPlatforms->count())
                                @foreach($allPlatforms as $platform)
                                    <div class="col-auto">
                                        <div class="form-check form-switch form-check-inline" dir="ltr">
                                            <label
                                                class="text-muted"
                                                for="platform.{{__($platform->name)}}">{{__($platform->name)}}</label>
                                            <input type="checkbox" class="form-check-input"
                                                   wire:model="selectedPlatformIds"
                                                   value="{{$platform->id}}"
                                                   id="platform.{{__($platform->name)}}">
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-auto text-muted">
                                    {{__('No Platforms from orders')}}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="row m-1 card border border-muted">
                    <div class="card-body border-info">
                        <label>{{__('Deals')}}</label>
                        <div class="row">
                            @if($allDeals->count())
                                @foreach($allDeals as $deal)
                                    <div class="col-auto">
                                        <div class="form-check form-switch form-check-inline" dir="ltr">
                                            <label
                                                for="deal.{{__($deal->name)}}"
                                                class="text-muted">{{__($deal->name)}}</label>
                                            <input type="checkbox" class="form-check-input"
                                                   wire:model="selectedDealIds"
                                                   value="{{$deal->id}}"
                                                   id="deal.{{__($deal->name)}}">
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-auto text-muted">
                                    {{__('No deals from orders')}}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="row m-1 card border border-muted">
                    <div class="card-body border-info">
                        <label>{{__('Items')}}</label>
                        <div class="row">
                            @if($allItems->count())
                                @foreach($allItems as $item)
                                    <div class="col-auto">
                                        <div class="form-check form-switch form-check-inline" dir="ltr">
                                            <label
                                                for="item.{{__($item->name)}}.{{__($item->id)}}"
                                                class="text-muted">
                                                {{__($item->name)}}
                                                @if(!is_null($item->platform()->first()))
                                                    - {{ $item->platform()->first()->name}}
                                                @endif
                                            </label>
                                            <input type="checkbox" class="form-check-input"
                                                   wire:model="selectedItemsIds"
                                                   value="{{$item->id}}"
                                                   id="item.{{__($item->name)}}.{{__($item->id)}}">
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-auto text-muted">
                                    {{__('No items from orders')}}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="row m-1 card border border-muted">
                    <div class="card-body border-info">
                        <label>{{__('Status')}}</label>
                        <select class="form-select form-select-sm  mb-3" multiple wire:model="selectedStatuses">
                            @foreach($allStatuses as $status)
                                <option value="{{$status->value}}">{{__($status->name)}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-primary refreshOrders float-end">{{__('Search Orders')}}</button>
        </div>
    </div>

    <div class="card row">
        <div class="card-header">
            <h6 class="card-title mb-0">{{__('Results')}}</h6>
        </div>
        <div class=" card-body col-lg-12 ">
            <div class="table-responsive">
                @if($choosenOrders->count())
                    <table id="userPurchaseHistoryTable"
                           class="table table-striped table-bordered cell-border row-border table-hover mdl-data-table display nowrap">
                        <thead class="table-light">
                        <tr class="head2earn tabHeader2earn">
                            <th>{{__('Details')}}</th>
                            <th>{{__('id')}}</th>
                            <th>{{__('Status')}}</th>
                            <th>{{__('user')}}</th>
                            <th>{{__('Total order quantity')}}</th>
                            <th>{{__('Paid cash')}}</th>
                            <th>{{__('Details')}}</th>
                            <th>{{__('Other detals')}}</th>
                            <th>{{__('Actions')}}</th>
                        </tr>
                        </thead>
                        <tbody class="body2earn">
                        @foreach($choosenOrders as $order)
                            <tr>
                                <td>
                                    <i class="fa-solid fa-circle-question text-info fa-lg dtmdbtn"></i>
                                </td>
                                <td>{{$order->id}}</td>
                                <td>
                                    <span class="badge bg-info text-end fs-14"> {{__($order->status->name)}}</span>
                                </td>
                                <td>{{getUserDisplayedName(\App\Models\User::getIdUserById($order->user_id))}}</td>
                                <td>{{$order->total_order_quantity}}</td>
                                <td>{{$order->paid_cash}}</td>
                                <td>
                                    <div class="list-group">
                                        @foreach($order->OrderDetails()->get() as $key => $orderDetail)
                                            <a href="javascript:void(0);"
                                               class="list-group-item list-group-item-action">
                                                <div class="float-end">
                                                    {{$orderDetail->total_amount}} {{config('app.currency')}}
                                                </div>
                                                <div class="d-flex mb-2 align-items-center">
                                                    <div class="flex-shrink-0">

                                                        @if($orderDetail->item()->first()->photo_link)
                                                            <img alt="{{__('Item Image')}}"
                                                                 src="{{$orderDetail->item()->first()->photo_link}}"
                                                                 class="avatar-sm rounded-circle"/>
                                                        @elseif($orderDetail->item()->first()->thumbnailsImage)
                                                            <img
                                                                src="{{ asset('uploads/' . $orderDetail->item()->first()->thumbnailsImage->url) }}"
                                                                alt="{{__('Item Image')}}"
                                                                class="avatar-sm rounded-circle"
                                                            >
                                                        @else
                                                            <img
                                                                src="{{Vite::asset(\App\Models\Item::DEFAULT_IMAGE_TYPE_THUMB)}}"
                                                                alt="{{__('Item Image')}}"
                                                                class="avatar-sm rounded-circle">
                                                        @endif

                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h5 class="list-title fs-15 mb-1">
                                                            {{$orderDetail->item()->first()->name}}
                                                            @if(!is_null($orderDetail->item()->first()->platform()->first()))
                                                                - {{ $orderDetail->item()->first()->platform()->first()->name}}
                                                            @endif
                                                        </h5>
                                                        <p class="list-text mb-0 fs-12">
                                                            {{__('Qty')}}: {{$orderDetail->qty}}
                                                        </p>
                                                        <p class="list-text mb-0 fs-12">
                                                            {{__('Unit price')}}: {{$orderDetail->unit_price}}
                                                        </p>
                                                        @if($orderDetail->shipping)
                                                            <p class="list-text mb-0 fs-12">
                                                                {{__('shipping')}}: {{$orderDetail->shipping}}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    <h5>{{__('Note')}}</h5>
                                    <pre>
                                        {{$order->note}}
                                   </pre>
                                </td>
                                <td>
                                    <a href="{{route('orders_detail', ['locale'=>app()->getLocale(),'id'=>$order->id])}}"
                                       class=float-end">{{__('More details')}}</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="col-auto text-muted">
                        {{__('No orders')}}
                    </div>
                @endif

            </div>
        </div>
    </div>
    <script type="module">
        window.addEventListener('updateOrdersDatatable', event => {
            var table = $('#userPurchaseHistoryTable').DataTable();
            table.destroy();
            $('#userPurchaseHistoryTable').DataTable({
                "paging": true,
                "responsive": true,
                "language": {"url": urlLang},
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            if (!$.fn.dataTable.isDataTable('#userPurchaseHistoryTable')) {

                $('#userPurchaseHistoryTable').DataTable({
                    "paging": true,
                    "responsive": true,
                    "language": {"url": urlLang},
                });
            }

            $('body').on('click', '.refreshOrders', function (event) {
                window.Livewire.dispatch("refreshOrders", [$(event.target).attr('data-id')]);
            });
        });
    </script>
</div>
