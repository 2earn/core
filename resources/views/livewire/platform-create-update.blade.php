<div class="{{getContainerType()}}">
    @component('components.breadcrumb')
        @slot('title')
            @if($update)
                {{__('Update Platform')}} : {{ $platform->name }}
            @else
                {{ __('Create Platform') }}
            @endif
        @endslot
    @endcomponent

    <div class="row">
        @include('layouts.flash-messages')
    </div>

    <div class="row">
        <div class="col-12 card ">
            <div class="card-body">
                <form>
                    <input type="hidden" wire:model.live="id">
                    <div class="mb-4">
                        <h6 class="fw-semibold text-secondary mb-3 pb-2 border-bottom">{{__('Basic Information')}}</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="Name" class="form-label fw-semibold">
                                    {{__('Name')}} <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="Name"
                                       @if($update) disabled @endif
                                       placeholder="{{__('Enter Name')}}"
                                       wire:model.live="name">
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="type" class="form-label fw-semibold">
                                    {{__('Type')}} <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('type') is-invalid @enderror"
                                        wire:model.live="type"
                                        id="type"
                                        aria-label="{{__('Enter type')}}">
                                    @foreach ($types as $typeItem)
                                        <option value="{{$typeItem['value']}}">{{__($typeItem['name'])}}</option>
                                    @endforeach
                                </select>
                                @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="Link" class="form-label fw-semibold">
                                    {{__('Link')}} <span class="text-danger">*</span>
                                </label>
                                <input type="url"
                                       class="form-control @error('link') is-invalid @enderror"
                                       id="Link"
                                       placeholder="{{__('Enter Link')}}"
                                       wire:model.live="link">
                                @error('link')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="sector" class="form-label fw-semibold">
                                    {{__('Sector')}} <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('sector') is-invalid @enderror"
                                        wire:model.live="sector"
                                        id="sector"
                                        aria-label="{{__('Enter sector')}}">
                                    @foreach ($sectors as $sectorsItem)
                                        <option value="{{$sectorsItem['value']}}">
                                            {{$sectorsItem['name']}}
                                        </option>
                                    @endforeach
                                </select>
                                @error('sector')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="Description" class="form-label fw-semibold">
                                    {{__('Description')}} <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          @if($update) disabled @endif
                                          id="Description"
                                          wire:model.live="description"
                                          rows="4"
                                          placeholder="{{__('Enter Description')}}"></textarea>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <h6 class="fw-semibold text-secondary mb-3 pb-2 border-bottom">{{__('Settings')}}</h6>
                        <div class="row g-3">
                            <div class="col-sm-6 col-lg-4">
                                <div class="border rounded p-3 h-100">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input"
                                               role="switch"
                                               wire:model.live="enabled"
                                               type="checkbox"
                                               id="Enabled"
                                               checked>
                                        <label class="form-check-label fw-semibold" for="Enabled">
                                            {{__('Enabled')}}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-lg-4">
                                <div class="border rounded p-3 h-100">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input"
                                               role="switch"
                                               wire:model.live="show_profile"
                                               type="checkbox"
                                               id="show_profile"
                                               checked>
                                        <label class="form-check-label fw-semibold" for="show_profile">
                                            {{__('Show profile')}}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-lg-4">
                                <div class="border rounded p-3 h-100 @if($update) bg-light @endif">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input"
                                               role="switch"
                                               wire:model.live="useCoupons"
                                               type="checkbox"
                                               @if($update) disabled @endif
                                               id="useCoupons"
                                               checked>
                                        <label class="form-check-label fw-semibold" for="useCoupons">
                                            {{__('Use coupons')}}
                                        </label>
                                        @if($update)
                                            <small class="d-block text-muted mt-1">
                                                <i class="fa fa-lock"></i> {{__('Cannot be changed')}}
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <h6 class="fw-semibold text-secondary mb-3 pb-2 border-bottom">{{__('Logo')}}</h6>
                        <div class="row">
                            <div class="col-12">
                                <label for="logoImage" class="form-label fw-semibold">{{__('Logo Image')}}</label>
                                <input type="file"
                                       id="logoImage"
                                       wire:model.live="logoImage"
                                       class="form-control @error('logoImage') is-invalid @enderror"
                                       accept="image/*">
                                @error('logoImage')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                @if ($platform?->logoImage)
                                    <div class="mt-3">
                                        <p class="text-muted small mb-2">{{__('Current Logo')}}:</p>
                                        <img src="{{ asset('uploads/' . $platform->logoImage->url) }}"
                                             alt="Platform Logo"
                                             class="img-thumbnail"
                                             style="max-width: 300px; max-height: 150px; object-fit: contain;">
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
                                    wire:click.prevent="updatePlatform()"
                                    class="btn btn-outline-success px-4">
                                {{__('Update')}}
                            </button>
                        @else
                            <button type="button"
                                    wire:click.prevent="storePlatform()"
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
