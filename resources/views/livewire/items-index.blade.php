<div class="container">
    @section('title')
        {{ __('Items') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Items') }}
        @endslot
    @endcomponent
    <div class="row">
            @include('layouts.flash-messages')
    </div>
    <div class="row">
        <div class="col-12 card">
            <div class="card-body">
                <div class="card-header">
                    <div class="row">
                        <div class="float-end col-sm-12 col-md-6 col-lg-6">
                            <form class="items-center">
                                <label for="simple-search" class="sr-only">{{__('Search')}}</label>
                                <div class="w-full">
                                    <input wire:model.live="search" type="text" id="simple-search"
                                           class="form-control float-end"
                                           placeholder="{{__('Search item')}}">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-body row">
                    @forelse($items as $item)
                        <div class="col-sm-12 col-lg-12">
                            <div class="card border card-border-light">
                                <div class="card-header">
                                    <h5 class="card-title mb-1">
                                        {{$item->id}}
                                        - {{$item->name}}
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-4 col-lg-3">
                                            <div class="d-flex flex-column align-items-center">
                                                <label class="form-label fw-semibold">{{__('Image')}}</label>
                                                @if($item->photo_link)
                                                    <img src="{{$item->photo_link}}"
                                                         class="d-block img-fluid img-business-square rounded">
                                                @elseif($item->thumbnailsImage)
                                                    <img src="{{ asset('uploads/' . $item->thumbnailsImage->url) }}"
                                                         alt="Item Image"
                                                         class="d-block img-fluid img-business-square rounded">
                                                @else
                                                    <img
                                                        src="{{Vite::asset(\App\Models\Item::DEFAULT_IMAGE_TYPE_THUMB)}}"
                                                        class="d-block img-fluid img-business-square rounded">
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-8 col-lg-9">
                                            <div class="row g-3">
                                                <div class="col-sm-6 col-md-4">
                                                    <label class="form-label fw-semibold">{{__('Ref')}}</label>
                                                    <div><a href="#" class="fw-semibold">#{{$item->ref}}</a></div>
                                                </div>
                                                <div class="col-sm-6 col-md-4">
                                                    <label class="form-label fw-semibold">{{__('Price')}}</label>
                                                    <div>{{$item->price}} {{config('app.currency')}}</div>
                                                </div>
                                                <div class="col-sm-6 col-md-4">
                                                    <label class="form-label fw-semibold">{{__('Discount')}}</label>
                                                    <div>{{$item->discount}} {{config('app.percentage')}}</div>
                                                </div>
                                                <div class="col-sm-6 col-md-4">
                                                    <label class="form-label fw-semibold">{{__('Discount 2earn')}}</label>
                                                    <div>{{$item->discount_2earn}} {{config('app.percentage')}}</div>
                                                </div>
                                                <div class="col-sm-6 col-md-4">
                                                    <label class="form-label fw-semibold">{{__('Deal')}}</label>
                                                    <div>
                                                        @if ($item->deal()->exists())
                                                            @if(\App\Models\User::isSuperAdmin())
                                                                <a href="{{route('deals_show',['locale'=>app()->getLocale(),'id'=>$item->deal->id])}}">
                                                                    {{$item->deal->id}} - {{$item->deal->name}}
                                                                </a>
                                                            @else
                                                                {{$item->deal->id}} - {{$item->deal->name}}
                                                            @endif
                                                        @else
                                                            <span class="badge bg-muted-subtle text-muted">{{__('No deal')}}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 col-md-4">
                                                    <label class="form-label fw-semibold">{{__('Platform')}}</label>
                                                    <div>
                                                        @if ($item->platform()->exists())
                                                            @if(\App\Models\User::isSuperAdmin())
                                                                <a href="{{route('platform_show',['locale'=>app()->getLocale(),'id'=>$item->platform->id])}}">
                                                                    {{$item->platform->id}} - {{$item->platform->name}}
                                                                </a>
                                                            @else
                                                                {{$item->platform->id}} - {{$item->platform->name}}
                                                            @endif
                                                        @else
                                                            <span class="badge bg-muted-subtle text-muted">{{__('No Platform')}}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                @if ($item->stock)
                                                    <div class="col-sm-6 col-md-4">
                                                        <label class="form-label fw-semibold">{{__('Stock')}}</label>
                                                        <div>{{$item->stock}}</div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col">
                                            <p class="card-text">{{__('Created at')}}: <small
                                                    class="text-muted">{{$item->created_at}}</small>
                                            </p>
                                        </div>
                                        <div class="col">
                                            @if(\App\Models\User::isSuperAdmin())
                                                <p class="card-text  float-end">{{__('Updated at')}}: <small
                                                        class="text-muted">{{$item->updated_at}}</small></p>
                                            @endif
                                        </div>
                                        <div class="col">
                                            @if(\App\Models\User::isSuperAdmin())
                                                <a href="{{route('items_detail',['locale'=>app()->getLocale(),'id'=>$item->id])}}"
                                                   class="card-text  float-end">{{__('More details')}}</a>
                                            @endif
                                        </div>
                                        <div class="col">
                                            @if(\App\Models\User::isSuperAdmin())
                                                <a href="{{route('items_create_update',['locale'=>app()->getLocale(), 'id' => $item->id])}}"
                                                   class="btn btn-soft-secondary m-1 float-end">{{__('Update Item')}}
                                                </a>
                                                @if($item->ref!='#0001')
                                                    <button class="btn btn-soft-danger  m-1 float-end"
                                                            wire:click="delete({{$item->id}})">{{__('Delete')}}
                                                    </button>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p>{{__('No items')}}</p>
                    @endforelse
                    {{ $items->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
