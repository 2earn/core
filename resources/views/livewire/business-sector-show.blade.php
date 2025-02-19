<div>
    @section('title')
        {{ __('Busines sSector') }} :     {{$businessSector->name}}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Busines sSector') }} :     {{$businessSector->name}}
        @endslot
    @endcomponent
    <div class="card">
        <div class="card-header">
            <h3>
                @if(\App\Models\User::isSuperAdmin())
                    {{$businessSector->id}} -
                @endif
                {{$businessSector->name}}
            </h3>
        </div>
        <div class="card-body row my-2">
            <div class="col-md-7">
                <h5>{{__('Description')}}</h5>
                <blockquote class="text-muted">
                    {{$businessSector->description}}
                </blockquote>
            </div>
            @if ($businessSector?->logoImage)
                <div class="col-md-2">
                    <div class="mt-3">
                        <img src="{{ asset('uploads/' . $businessSector->logoImage->url) }}"
                             alt="Business Sector logoImage" class="img-thumbnail">
                    </div>
                </div>
            @endif

            @if ($businessSector?->thumbnailsImage)
                <div class="col-md-3">
                    <div class="mt-3">
                        <img src="{{ asset('uploads/' . $businessSector->thumbnailsImage->url) }}"
                             alt="Business Sector thumbnailsImage" class="img-thumbnail">
                    </div>
                </div>
            @endif
        </div>
        <div class="card-footer">
            @if(\App\Models\User::isSuperAdmin())
                <a wire:click="deletebusinessSector('{{$businessSector->id}}')"
                   title="{{__('Delete business_sector')}}"
                   class="btn btn-soft-danger material-shadow-none">
                    {{__('Delete')}}
                    <div wire:loading wire:target="deletebusinessSector('{{$businessSector->id}}')">
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                      aria-hidden="true"></span>
                        <span class="sr-only">{{__('Loading')}}...</span>
                    </div>
                </a>
                <a
                    href="{{route('business_sector_create_update',['locale'=> app()->getLocale(),'id'=>$businessSector->id])}}"
                    title="{{__('Edit business sector')}}"
                    class="btn btn-soft-primary material-shadow-none mx-1">
                    {{__('Edit')}}
                </a>
            @endif
            <span class="float-end"> {{__('Created at')}}: {{$businessSector->created_at}}</span>
        </div>
        @if(count($items))
            <div class="card-body row my-2">
                <h5>{{__('Items')}}</h5>

                <div class="row row-cols-xxl-5 row-cols-lg-3 row-cols-1">
                    @foreach ($items as $item)
                        <div class="col">
                            <div class="card card-body">
                                <div class="d-flex mb-4 align-items-center">
                                    <div class="flex-shrink-0">
                                        @if($item->photo_link)
                                            <img src="{{$item->photo_link}}" alt="" class="avatar-sm rounded-circle">
                                        @endif
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <h5 class="card-title mb-1"> #{{$item->ref}} - {{$item->name}}</h5>
                                        <p class="text-muted mb-0"></p>
                                    </div>
                                </div>
                                <h6 class="mb-1">{{__('Price')}} : {{$item->price}}</h6>
                                @if($item->discount)
                                    <h6 class="mb-1">{{__('Discount')}} : {{$item->discount}}</h6>
                                @endif
                                @if($item->discount_2earn)
                                    <h6 class="mb-1">{{__('Discount 2earn')}} : {{$item->discount_2earn}}</h6>
                                @endif
                                <span class="btn btn-success  btn-sm float-end my-1">{{__('Add to card')}}</span>
                                @if(\App\Models\User::isSuperAdmin())
                                    <a href="{{route('items_detail',['locale'=>app()->getLocale(),'id'=>$item->id])}}"
                                       class="btn btn-primary btn-sm">See Details</a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
