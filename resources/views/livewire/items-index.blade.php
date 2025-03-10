<div>
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
        <div class="col-12">
            @include('layouts.flash-messages')
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="card-header border-info">
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
                                <div class="table-responsive">
                                    <table class="table table-nowrap align-middle mb-0">
                                        <thead class="table-light">
                                        <tr>
                                            <th scope="col">{{__('Ref')}}</th>
                                            <th scope="col">{{__('Image')}}</th>
                                            <th scope="col">{{__('Price')}}</th>
                                            <th scope="col">{{__('Discount')}}</th>
                                            <th scope="col">{{__('Discount 2earn')}}</th>
                                            <th scope="col">{{__('Deal')}}</th>
                                            <th scope="col">{{__('Platform')}}</th>
                                            @if ($item->stock)
                                                <th scope="col">{{__('Stock')}}</th>
                                            @endif
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <th scope="row"><a href="#" class="fw-semibold">#{{$item->ref}}</a></th>
                                            <td>
                                                @if($item->photo_link)
                                                    <img src="{{$item->photo_link}}"
                                                         class="d-block img-fluid img-business-square mx-auto rounded">
                                                @elseif($item->thumbnailsImage)
                                                    <img src="{{ asset('uploads/' . $item->thumbnailsImage->url) }}"
                                                         alt="Item Image"
                                                         class="d-block img-fluid img-business-square mx-auto rounded">
                                                @else
                                                    <img
                                                        src="{{Vite::asset(\App\Models\Item::DEFAULT_IMAGE_TYPE_THUMB)}}"
                                                        class="d-block img-fluid  img-business-square mx-auto rounded ">
                                                @endif
                                            </td>
                                            <td>{{$item->price}} {{config('app.currency')}}</td>
                                            <td>{{$item->discount}} {{config('app.percentage')}}</td>
                                            <td>{{$item->discount_2earn}} {{config('app.percentage')}}</td>
                                            <td>
                                                @if ($item->deal()->exists())
                                                    @if(\App\Models\User::isSuperAdmin())
                                                        <a href="{{route('deals_show',['locale'=>app()->getLocale(),'id'=>$item->deal->id])}}">
                                                            {{$item->deal->id}} - {{$item->deal->name}}
                                                        </a>
                                                    @else
                                                        {{$item->deal->id}} - {{$item->deal->name}}
                                                    @endif
                                                @else
                                                    <span
                                                        class="badge bg-muted-subtle text-muted">{{__('No deal')}}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($item->platform()->exists())
                                                    @if(\App\Models\User::isSuperAdmin())
                                                        <a href="{{route('platform_show',['locale'=>app()->getLocale(),'id'=>$item->platform->id])}}">
                                                            {{$item->platform->id}} - {{$item->platform->name}}
                                                        </a>
                                                    @else
                                                        {{$item->platform->id}} - {{$item->platform->name}}
                                                    @endif
                                                @else
                                                    <span
                                                        class="badge bg-muted-subtle text-muted">{{__('No Platform')}}</span>
                                                @endif
                                            </td>
                                            @if ($item->stock)
                                                <td>{{$item->stock}}</td>
                                            @endif
                                        </tr>
                                        </tbody>
                                    </table>
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
                                               class="btn btn-secondary mx-1 float-end">{{__('Update Item')}}
                                            </a>
                                            @if($item->ref!='#0001')
                                                <button class="btn btn-danger  mx-1 float-end"
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
