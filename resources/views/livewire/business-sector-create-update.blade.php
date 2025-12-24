<div class="container">
    @component('components.breadcrumb')
        @slot('title')
            @if($update)
                {{__('Update businessSector')}} : {{$name}}
            @else
                {{__('Create businessSector')}}
            @endif
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12 card shadow-sm">
            <div class="card-body">
                <form>
                    <input type="hidden" wire:model.live="id">
                    <div class="mb-4">
                        <h6 class="fw-semibold text-secondary mb-3 pb-2 border-bottom">{{__('Basic Information')}}</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">
                                    {{__('name')}} <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="name"
                                       @if($update) disabled @endif
                                       placeholder="{{__('Enter name')}}"
                                       wire:model.live="name">
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="color" class="form-label fw-semibold">
                                    {{__('Color')}} <span class="text-danger">*</span>
                                </label>
                                <input type="color"
                                       class="form-control form-control-color w-100 @error('color') is-invalid @enderror"
                                       id="color"
                                       placeholder="{{__('Enter color')}}"
                                       wire:model.live="color">
                                @error('color')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="description" class="form-label fw-semibold">
                                    {{__('description')}} <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description"
                                          @if($update) disabled @endif
                                          rows="4"
                                          placeholder="{{__('Enter description')}}"
                                          wire:model.live="description"></textarea>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <h6 class="fw-semibold text-secondary mb-3 pb-2 border-bottom">{{__('Images')}}</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="thumbnailsImage" class="form-label fw-semibold">
                                    {{__('Thumbnails Image')}}
                                </label>
                                <input type="file"
                                       id="thumbnailsImage"
                                       wire:model.live="thumbnailsImage"
                                       class="form-control @error('thumbnailsImage') is-invalid @enderror"
                                       accept="image/*">
                                @error('thumbnailsImage')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                @if ($businessSector?->thumbnailsImage)
                                    <div class="mt-3">
                                        <p class="text-muted small mb-2">{{__('Current Image')}}:</p>
                                        <img src="{{ asset('uploads/' . $businessSector->thumbnailsImage->url) }}"
                                             alt="Business Sector Thumbnails"
                                             class="img-thumbnail"
                                             style="max-width: 100%; max-height: 200px; object-fit: cover;">
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-4">
                                <label for="thumbnailsHomeImage" class="form-label fw-semibold">
                                    {{__('Thumbnails Home Image')}}
                                </label>
                                <input type="file"
                                       id="thumbnailsHomeImage"
                                       wire:model.live="thumbnailsHomeImage"
                                       class="form-control @error('thumbnailsHomeImage') is-invalid @enderror"
                                       accept="image/*">
                                @error('thumbnailsHomeImage')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                @if ($businessSector?->thumbnailsHomeImage)
                                    <div class="mt-3">
                                        <p class="text-muted small mb-2">{{__('Current Image')}}:</p>
                                        <img src="{{ asset('uploads/' . $businessSector->thumbnailsHomeImage->url) }}"
                                             alt="Business Sector Home Thumbnails"
                                             class="img-thumbnail"
                                             style="max-width: 100%; max-height: 200px; object-fit: cover;">
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-4">
                                <label for="logoImage" class="form-label fw-semibold">
                                    {{__('Logo Image')}}
                                </label>
                                <input type="file"
                                       id="logoImage"
                                       wire:model.live="logoImage"
                                       class="form-control @error('logoImage') is-invalid @enderror"
                                       accept="image/*">
                                @error('logoImage')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                @if ($businessSector?->logoImage)
                                    <div class="mt-3">
                                        <p class="text-muted small mb-2">{{__('Current Image')}}:</p>
                                        <img src="{{ asset('uploads/' . $businessSector->logoImage->url) }}"
                                             alt="Business Sector Logo"
                                             class="img-thumbnail"
                                             style="max-width: 100%; max-height: 200px; object-fit: cover;">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <button type="button"
                                wire:click.prevent="cancel()"
                                class="btn btn-outline-secondary px-4">
                            {{__('Cancel')}}
                        </button>
                        @if($update)
                            <button type="button"
                                    wire:click.prevent="updateBU()"
                                    class="btn btn-outline-success px-4">
                                {{__('Update')}}
                            </button>
                        @else
                            <button type="button"
                                    wire:click.prevent="storeBU()"
                                    class="btn btn-outline-success px-4">
                                {{__('Save')}}
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
