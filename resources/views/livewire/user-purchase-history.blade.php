<div class="{{getContainerType()}}">
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
            <h6 class="card-title mb-0 text-info">{{__('Filters')}}</h6>
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
            <h6 class="card-title mb-0 text-info">{{__('Results')}}</h6>
        </div>
        <div class="card-body col-lg-12">
            @if($chosenOrders->count())
                <div class="row g-3">
                    @foreach($chosenOrders as $key => $order)
                        <div class="col-12">
                            <div class="card border shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex mb-3 align-items-start">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="avatar-sm rounded-circle bg-info-subtle d-flex align-items-center justify-content-center">
                                                <i class="fa-solid fa-shopping-cart text-info fa-lg"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div
                                                class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                                <div>
                                                    @if(\App\Models\User::isSuperAdmin() )
                                                        <span class="badge bg-dark text-light border mb-1 mx-2">
                                                        #{{$key + 1 }}
                                                    </span>
                                                    @endif

                                                        <span class="badge bg-light text-dark border mb-1">
                                                        #{{$order->id}}
                                                    </span>
                                                    <h5 class="fs-15 mb-1">
                                                        {{getUserDisplayedName(\App\Models\User::getIdUserById($order->user_id))}}
                                                    </h5>
                                                </div>
                                                <div class="text-end">
                                                    <span class="badge bg-info-subtle text-info px-2 py-1 fs-14">
                                                        {{__($order->status->name)}}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-2 mb-3">
                                        <div class="col-md-4 col-6">
                                            <div class="p-2 bg-light rounded">
                                                <p class="text-primary fs-12 mb-1">
                                                    <i class="fas fa-boxes me-1"></i>{{__('Total order quantity')}}
                                                </p>
                                                <span
                                                    class="fw-semibold text-dark">{{$order->total_order_quantity}}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-6">
                                            <div class="p-2 bg-light rounded">
                                                <p class="text-primary fs-12 mb-1">
                                                    <i class="fas fa-money-bill-wave me-1"></i>{{__('Paid cash')}}
                                                </p>
                                                <span
                                                    class="fw-semibold text-dark">{{$order->paid_cash}} {{config('app.currency')}}</span>
                                            </div>
                                        </div>
                                    </div>

                                    @if($order->OrderDetails()->count())
                                        <div class="mb-3">
                                            <h6 class="text-primary mb-2">
                                                <i class="fas fa-list-ul me-1"></i>{{__('Order Details')}}
                                            </h6>
                                            <div class="border rounded p-2">
                                                @foreach($order->OrderDetails()->get() as $key => $orderDetail)
                                                    <div
                                                        class="d-flex mb-2 align-items-center p-2 bg-light rounded @if(!$loop->last) border-bottom @endif">
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
                                                            <h5 class="fs-14 mb-1 fw-semibold">
                                                                {{$orderDetail->item()->first()->name}}
                                                                @if(!is_null($orderDetail->item()->first()->platform()->first()))
                                                                    <span
                                                                        class="text-muted">- {{ $orderDetail->item()->first()->platform()->first()->name}}</span>
                                                                @endif
                                                            </h5>
                                                            <div class="row g-2">
                                                                <div class="col-auto">
                                                                    <span
                                                                        class="fs-12 text-muted">{{__('Qty')}}: <strong>{{$orderDetail->qty}}</strong></span>
                                                                </div>
                                                                <div class="col-auto">
                                                                    <span class="fs-12 text-muted">{{__('Unit price')}}: <strong>{{$orderDetail->unit_price}}</strong></span>
                                                                </div>
                                                                @if($orderDetail->shipping)
                                                                    <div class="col-auto">
                                                                        <span class="fs-12 text-muted">{{__('shipping')}}: <strong>{{$orderDetail->shipping}}</strong></span>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="flex-shrink-0 ms-2">
                                                            <span
                                                                class="badge bg-success-subtle text-success px-2 py-1">
                                                                {{$orderDetail->total_amount}} {{config('app.currency')}}
                                                            </span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    @if($order->note)
                                        <div class="mb-3">
                                            <h6 class="text-primary mb-2">
                                                <i class="fas fa-sticky-note me-1"></i>{{__('Note')}}
                                            </h6>
                                            <div class="p-2 bg-light rounded">
                                                <pre class="mb-0 fs-12 text-wrap">{{$order->note}}</pre>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="d-flex gap-2 flex-wrap">
                                        <a href="{{route('orders_detail', ['locale'=>app()->getLocale(),'id'=>$order->id])}}"
                                           class="btn btn-sm btn-soft-primary flex-fill">
                                            <i class="fas fa-eye me-1"></i>
                                            {{__('More details')}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination Links -->
                <div class="mt-4">
                    {{ $chosenOrders->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-3 fs-15">{{__('No orders')}}</p>
                </div>
            @endif
        </div>
    </div>
    <script type="module">
        document.addEventListener("DOMContentLoaded", function () {

            $('body').on('click', '.refreshOrders', function (event) {
                window.Livewire.dispatch("refreshOrders", [$(event.target).attr('data-id')]);
            });
        });
    </script>
</div>
