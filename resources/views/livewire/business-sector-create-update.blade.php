<div>
    @component('components.breadcrumb')
        @slot('title')
            @if($update)
                {{__('Update businessSector')}}
            @else
                {{__('Create businessSector')}}
            @endif
        @endslot
    @endcomponent
    <div class="row card">
        <div class="card-header border-info">
            <div class="d-flex align-items-center">
                <h6 class="card-title flex-grow-1">
                    {{__('Business sector')}} :
                    @if($update)
                         <span
                            class="text-muted"> > </span> {{__('Update business sector')}} > {{$name}}
                    @else
                        <span
                            class="text-muted"> > </span> {{__('Create business sector')}}
                    @endif
                </h6>
            </div>
        </div>
        <div class="card-body row ">
            <form>
                <input type="hidden" wire:model="id">
                <div class="row">
                    <div class="form-group col-sm-12 col-md-12 mb-3">
                        <label for="name">{{__('name')}}</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name"
                               placeholder="{{__('Enter name')}}" wire:model="name">
                        @error('name') <span class="text-danger">{{ $message }}</span>@enderror
                        <div class="form-text">{{__('Required field')}}</div>
                    </div>
                    <div class="form-group col-sm-12 col-md-12 mb-3">
                        <label for="description">{{__('description')}}</label>
                        <textarea class="form-control @error('name') is-invalid @enderror"
                                  id="description"
                                  placeholder="{{__('Enter description')}}" wire:model="description"></textarea>
                        @error('description') <span class="text-danger">{{ $message }}</span>@enderror
                        <div class="form-text">{{__('Required field')}}</div>
                    </div>

                    <div class="form-group col-sm-12 col-md-12 mb-3">
                        <label for="thumbnailsImage">{{__('Thumbnails Image')}}</label>
                        <input type="file" id="thumbnailsImage" wire:model="thumbnailsImage" class="form-control">
                        @error('thumbnailsImage') <span class="error">{{ $message }}</span> @enderror
                        @if ($businessSector?->thumbnailsImage)
                            <div class="mt-3">
                                <img src="{{ asset('uploads/' . $businessSector->thumbnailsImage->url) }}" alt="Business Sector thumbnailsImage" class="img-thumbnail">
                            </div>
                        @endif
                    </div>
                    <div class="form-group col-sm-12 col-md-12 mb-3">
                        <label for="logoImage">{{__('Logo Image')}}</label>
                        <input type="file" id="logoImage" wire:model="logoImage" class="form-control">
                        @error('logoImage') <span class="error">{{ $message }}</span> @enderror
                        @if ($businessSector?->logoImage)
                            <div class="mt-3">
                                <img src="{{ asset('uploads/' . $businessSector->logoImage->url) }}" alt="Business Sector logoImage" class="img-thumbnail">
                            </div>
                        @endif
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

                        <button wire:click.prevent="cancel()"
                                class="btn btn-danger float-end  mx-2">{{__('Cancel')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
