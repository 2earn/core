<div>
    @section('title')
        {{ __('Item') }}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
            @if($update)
                {{__('Update Item')}}
            @else
                {{__('Create Item')}}
            @endif
        @endslot
    @endcomponent
    <div class="row card">
        <div class="card-header border-info">
            <div class="d-flex align-items-center">
                <h6 class="card-title flex-grow-1">
                    @if($update)
                        {{__('Items')}} : <span
                            class="text-muted"> > </span> {{__('Update Item')}}
                    @else
                        {{__('Items')}} :<span
                            class="text-muted"> > </span> {{__('Create Item')}}
                    @endif
                </h6>
            </div>
        </div>
        <div class="card-body row ">
            <form>
                <input type="hidden" wire:model="id">
                <div class="row">
                    <div class="form-group col-sm-12 col-md-4 mb-3">
                        <label for="name">{{__('Name')}}</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name"
                               placeholder="{{__('Enter name')}}" wire:model="name">
                        @error('name') <span class="text-danger">{{ $message }}</span>@enderror
                        <div class="form-text">{{__('Required field')}}</div>
                    </div>
                    <div class="form-group col-sm-12 col-md-4 mb-3">
                        <label for="ref">{{__('ref')}}</label>
                        <input type="text" class="form-control @error('ref') is-invalid @enderror"
                               id="ref"
                               placeholder="{{__('Enter ref')}}" wire:model="ref">
                        @error('ref') <span class="text-danger">{{ $message }}</span>@enderror
                        <div class="form-text">{{__('Required field')}}</div>
                    </div>
                    <div class="form-group col-sm-12 col-md-4 mb-3">
                        <label for="price">{{__('Price')}}</label>
                        <input type="number" class="form-control @error('price') is-invalid @enderror"
                               id="price"
                               placeholder="{{__('Enter price')}}" wire:model="price">
                        @error('price') <span class="text-danger">{{ $message }}</span>@enderror
                        <div class="form-text">{{__('Required field')}}</div>
                    </div>
                    <div class="form-group col-sm-12 col-md-4 mb-3">
                        <label for="discount">{{__('Discount')}}</label>
                        <input type="number" class="form-control @error('discount') is-invalid @enderror"
                               id="discount"
                               placeholder="{{__('Enter discount')}}" wire:model="discount">
                        @error('discount') <span class="text-danger">{{ $message }}</span>@enderror
                        <div class="form-text">{{__('Required field')}}</div>
                    </div>
                    <div class="form-group col-sm-12 col-md-4 mb-3">
                        <label for="discount_2earn">{{__('Discount 2earn')}}</label>
                        <input type="number" class="form-control @error('discount_2earn') is-invalid @enderror"
                               id="discount_2earn"
                               placeholder="{{__('Enter discount_2earn')}}" wire:model="discount_2earn">
                        @error('discount_2earn') <span class="text-danger">{{ $message }}</span>@enderror
                        <div class="form-text">{{__('Required field')}}</div>
                    </div>
                    <div class="form-group col-sm-12 col-md-4 mb-3">
                        <label for="photo_link">{{__('Photo link')}}</label>
                        <input type="url" class="form-control @error('photo_link') is-invalid @enderror"
                               id="photo_link"
                               placeholder="{{__('Enter photo_link')}}" wire:model="photo_link">
                        @error('photo_link') <span class="text-danger">{{ $message }}</span>@enderror
                        <div class="form-text">{{__('Required field')}}</div>
                    </div>
                    <div class="form-group col-sm-12 col-md-12 mb-3">
                        <label for="thumbnailsImage">{{__('Thumbnails Image')}}</label>
                        <input type="file" id="thumbnailsImage" wire:model="thumbnailsImage" class="form-control">
                        @error('thumbnailsImage') <span class="error">{{ $message }}</span> @enderror
                        @if ($item?->thumbnailsImage)
                            <div class="mt-3">
                                <img src="{{ asset('uploads/' . $item->thumbnailsImage->url) }}" alt="Business Sector thumbnailsImage" class="img-thumbnail">
                            </div>
                        @endif
                    </div>
                    <div class="form-group col-sm-12 col-md-4 mb-3">
                        <label for="description">{{__('Description')}}</label>
                        <input type="text" class="form-control @error('description') is-invalid @enderror"
                               id="description"
                               placeholder="{{__('Enter description')}}" wire:model="description">
                        @error('description') <span class="text-danger">{{ $message }}</span>@enderror
                        <div class="form-text">{{__('Required field')}}</div>
                    </div>
                    <div class="form-group col-sm-12 col-md-4 mb-3">
                        <label for="stock">{{__('Stock')}}</label>
                        <input type="number" class="form-control @error('stock') is-invalid @enderror"
                               id="stock"
                               placeholder="{{__('Enter stock')}}" wire:model="stock">
                        @error('stock') <span class="text-danger">{{ $message }}</span>@enderror
                        <div class="form-text">{{__('Required field')}}</div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        @if($update)
                            <button wire:click.prevent="update()"
                                    class="btn btn-success btn-block mx-2 float-end ">{{__('Update')}}</button>
                        @else
                            <button wire:click.prevent="store()"
                                    class="btn btn-success btn-block float-end ">{{__('Save')}}</button>
                        @endif
                        <button
                            wire:click.prevent="cancel()"
                            class="btn btn-danger float-end  mx-2"
                        >{{__('Cancel')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
