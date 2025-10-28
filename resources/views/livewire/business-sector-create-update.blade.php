<div class="container-fluid">
    <style>
        .preview-img {
            max-width: 200px;
            height: auto;
        }
    </style>
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
                <input type="hidden" wire:model.live="id">
                <div class="row">
                    <div class="form-group col-sm-12 col-md-6 mb-3">
                        <label for="name">{{__('name')}}</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name"
                               @if($update) disabled @endif
                               placeholder="{{__('Enter name')}}" wire:model.live="name">
                        @error('name') <span class="text-danger">{{ $message }}</span>@enderror
                        <div class="form-text">{{__('Required field')}}</div>
                    </div>
                    <div class="form-group col-sm-12 col-md-6 mb-3">
                        <label for="color">{{__('Color')}}</label>
                        <input type="color" class="form-control form-control-color w-100 @error('name') is-invalid @enderror"
                               id="color"
                               placeholder="{{__('Enter color')}}" wire:model.live="color">
                        @error('name') <span class="text-danger">{{ $message }}</span>@enderror
                        <div class="form-text">{{__('Required field')}}</div>
                    </div>
                    <div class="form-group col-sm-12 col-md-12 mb-3">
                        <label for="description">{{__('description')}}</label>
                        <textarea class="form-control @error('name') is-invalid @enderror"
                                  id="description"
                                  @if($update) disabled @endif
                                  placeholder="{{__('Enter description')}}" wire:model.live="description"></textarea>
                        @error('description') <span class="text-danger">{{ $message }}</span>@enderror
                        <div class="form-text">{{__('Required field')}}</div>
                    </div>

                    <div class="form-group col-sm-12 col-md-4 mb-3">
                    <label for="thumbnailsImage">{{__('Thumbnails Image')}}</label>
                    <input type="file" id="thumbnailsImage" wire:model="thumbnailsImage" class="form-control">
                    @error('thumbnailsImage') <span class="error">{{ $message }}</span> @enderror
                    @if ($thumbnailsImage)
                        <div class="mt-3 position-relative d-inline-block">
                            <img src="{{ $thumbnailsImage->temporaryUrl() }}" alt="Preview" class="img-thumbnail preview-img">
                            <button type="button" wire:click="removeImage('thumbnailsImage')"
                                    class="btn btn-sm btn-danger position-absolute top-0 end-0">
                                ×
                            </button>
                        </div>
                    @elseif ($businessSector?->thumbnailsImage)
                            <div class="mt-3 position-relative d-inline-block">
                                <img src="{{ asset('uploads/' . $businessSector->thumbnailsImage->url) }}"
                                    alt="Business Sector thumbnailsImage" class="img-thumbnail preview-img">
                            </div>
                    @endif
                    </div>

                  <div class="form-group col-sm-12 col-md-4 mb-3">
                    <label for="thumbnailsHomeImage">{{ __('Thumbnails Home Image') }}</label>
                    <input type="file" id="thumbnailsHomeImage" wire:model="thumbnailsHomeImage" class="form-control">
                    @error('thumbnailsHomeImage') <span class="error">{{ $message }}</span> @enderror

                    @if ($thumbnailsHomeImage)
                        <div class="mt-3 position-relative d-inline-block">
                            <img src="{{ $thumbnailsHomeImage->temporaryUrl() }}" alt="Preview" class="img-thumbnail preview-img">
                            <button type="button" wire:click="removeImage('thumbnailsHomeImage')"
                                    class="btn btn-sm btn-danger position-absolute top-0 end-0">
                                ×
                            </button>
                        </div>
                    @elseif ($businessSector?->thumbnailsHomeImage)
                        <div class="mt-3">
                            <img src="{{ asset('uploads/' . $businessSector->thumbnailsHomeImage->url) }}"
                                alt="Business Sector thumbnailsHomeImage" class="img-thumbnail preview-img">
                        </div>
                    @endif
                </div>

                <div class="form-group col-sm-12 col-md-4 mb-3">
                    <label for="logoImage">{{ __('Logo Image') }}</label>
                    <input type="file" id="logoImage" wire:model="logoImage" class="form-control">
                    @error('logoImage') <span class="error">{{ $message }}</span> @enderror

                    @if ($logoImage)
                        <div class="mt-3 position-relative d-inline-block">
                            <img src="{{ $logoImage->temporaryUrl() }}" alt="Preview" class="img-thumbnail preview-img">
                            <button type="button" wire:click="removeImage('logoImage')"
                                    class="btn btn-sm btn-danger position-absolute top-0 end-0">
                                ×
                            </button>
                        </div>
                    @elseif ($businessSector?->logoImage)
                        <div class="mt-3">
                            <img src="{{ asset('uploads/' . $businessSector->logoImage->url) }}"
                                alt="Business Sector logoImage" class="img-thumbnail preview-img">
                        </div>
                    @endif
                </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            @if($update)
                                <button wire:click.prevent="updateBU()"
                                        class="btn btn-success btn-block mx-2 float-end ">{{__('Update')}}</button>
                            @else
                                <button wire:click.prevent="storeBU()"
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
