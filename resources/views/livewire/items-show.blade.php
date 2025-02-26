<div class="col">
    <div class="card card-body">
        <div class="d-flex mb-4 align-items-center">
            <div class="flex-shrink-0">
                @if($item->photo_link)
                    <img src="{{$item->photo_link}}" class="avatar-sm rounded-circle">
                @endif
            </div>
            <div class="flex-grow-1 ms-2">
                <h5 class="card-title mb-1"> #{{$item->ref}} - {{$item->name}}</h5>
                @if($item->stock)
                    <p class="text-muted mb-0">{{$item->stock}}</p>
                @endif
            </div>
        </div>
        <h6 class="mb-1">{{__('Price')}} : <span
                class="badge bg-success text-end float-end fs-14">{{$item->price}}  {{config('app.currency')}}</span>
        </h6>
        @if($item->discount)
            <h6 class="mb-1">{{__('Discount')}} : <span
                    class="badge badge-outline-success text-end float-end fs-14">{{$item->discount}}  {{config('app.percentage')}}</span>
            </h6>
        @else
            <br>
        @endif
        @if($item->discount_2earn)
            <h6 class="mb-1">{{__('Discount 2earn')}} : <span
                    class="badge badge-outline-info text-end float-end fs-14">{{$item->discount_2earn}}  {{config('app.percentage')}}</span>
            </h6>
        @else
            <br>
        @endif


        @if($orderedQty==0)
            <span
                class="btn btn-soft-success  btn-sm float-end my-1"
                wire:click="addToCard()"
            >
            {{__('Add to card')}}
        </span>
        @else
            <div class="input-group">
                <input type="text" class="form-control"
                       wire:model="quantityToAdd" aria-label="Recipient's username"
                       aria-describedby="button-addon2">
                <button class="btn btn-outline-success btn-sm material-shadow-none"
                        wire:click="addMoreToCard()" type="button" id="addMoreToCard">{{__('Add more')}}</button>
            </div>
        @endif
        <a href="{{route('items_detail',['locale'=>app()->getLocale(),'id'=>$item->id])}}"
           class="btn btn-soft-primary btn-sm mt-1">
            {{__('See Details')}}
        </a>
    </div>
</div>
