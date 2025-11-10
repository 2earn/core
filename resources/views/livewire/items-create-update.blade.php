<div class="{{getContainerType()}}">
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
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header py-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-0 fw-bold">
                                @if($update)
                                    {{__('Update Item')}}
                                @else
                                    {{__('Create Item')}}
                                @endif
                            </h5>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form>
                        <input type="hidden" wire:model.live="id">

                        <!-- Basic Information Section -->
                        <div class="mb-4">
                            <h6 class="text-uppercase text-muted mb-3 fw-bold">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle me-2" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                    <path d="M8.93 6.588-2.29.287-.082.38.45.083c.294-.07.352-.176.288-.469l-.738-3.468c-.194.018-.335.055-.451.117l-.106.084c-.192.121-.293.279-.293.469 0 .191.096.336.275.426l.191.093c.071.036.131.072.179.107.033.024.054.041.063.052.093.088.138.205.138.35 0 .121-.036.22-.103.293-.007.008-.013.014-.021.021-.007.007-.014.014-.021.021l-.29.278c-.092.088-.138.205-.138.35 0 .121.036.22.103.293.007.007.013.014.021.021s.014.014.021.021l.29.278c.092.088.138.205.138.35 0 .121-.036.22-.103.293-.007.008-.013.014-.021.021-.007.007-.014.014-.021.021l-.29.278c-.092.088-.138.205-.138.35 0 .121.036.22.103.293l.021.021.021.021.29.278c.092.088.138.205.138.35 0 .121-.036.22-.103.293-.007.008-.013.014-.021.021-.007.007-.014.014-.021.021l-.29.278c-.092.088-.138.205-.138.35 0 .121.036.22.103.293l.021.021.021.021.29.278c.092.088.138.205.138.35 0 .121-.036.22-.103.293-.007.007-.013.014-.021.021-.007.007-.014.014-.021.021l-.427.385c-.063.057-.095.126-.095.21 0 .121.036.22.103.293l.021.021.021.021.578.508c.154.121.361.182.618.182.25 0 .457-.061.618-.182l.578-.508c.066-.057.1-.135.1-.231 0-.088-.033-.161-.1-.218l-.427-.385c-.007-.007-.013-.014-.021-.021-.007-.007-.014-.014-.021-.021-.066-.073-.1-.172-.1-.293 0-.145.045-.262.137-.35l.29-.278.021-.021c.007-.007.014-.014.021-.021.066-.073.1-.172.1-.293 0-.145-.045-.262-.137-.35l-.29-.278-.021-.021c-.007-.007-.014-.014-.021-.021-.066-.073-.1-.172-.1-.293 0-.145.045-.262.137-.35l.29-.278.021-.021c.007-.007.014-.014.021-.021.066-.073.1-.172.1-.293 0-.145-.045-.262-.137-.35l-.29-.278-.021-.021c-.007-.007-.014-.014-.021-.021-.066-.073-.1-.172-.1-.293 0-.145.045-.262.137-.35l.29-.278.021-.021c.007-.007.014-.013.021-.021.066-.073.1-.172.1-.293 0-.145-.045-.262-.137-.35l-.29-.278-.021-.021c-.007-.007-.014-.013-.021-.021-.066-.073-.1-.172-.1-.293 0-.121.034-.22.1-.293l.427-.385c.067-.057.1-.135.1-.231 0-.088-.033-.161-.1-.218L8.93 6.588z"/>
                                </svg>
                                {{__('Basic Information')}}
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="name" class="form-label fw-semibold">
                                        {{__('Name')}} <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control form-control @error('name') is-invalid @enderror"
                                           id="name"
                                           placeholder="{{__('Enter name')}}"
                                           wire:model.live="name">
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="ref" class="form-label fw-semibold">
                                        {{__('ref')}} <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control form-control @error('ref') is-invalid @enderror"
                                           id="ref"
                                           placeholder="{{__('Enter ref')}}"
                                           wire:model.live="ref">
                                    @error('ref') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="stock" class="form-label fw-semibold">
                                        {{__('Stock')}} <span class="text-danger">*</span>
                                    </label>
                                    <input type="number"
                                           class="form-control form-control @error('stock') is-invalid @enderror"
                                           id="stock"
                                           placeholder="{{__('Enter stock')}}"
                                           wire:model.live="stock">
                                    @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <label for="description" class="form-label fw-semibold">
                                        {{__('Description')}} <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control form-control @error('description') is-invalid @enderror"
                                              id="description"
                                              placeholder="{{__('Enter description')}}"
                                              wire:model.live="description"
                                              rows="5"></textarea>
                                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Pricing Section -->
                        <div class="mb-4">
                            <h6 class="text-uppercase text-muted mb-3 fw-bold">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-currency-dollar me-2" viewBox="0 0 16 16">
                                    <path d="M4 10.781c.148 1.667 1.513 2.85 3.591 3.003V15h1.043v-1.216c2.27-.179 3.678-1.438 3.678-3.3 0-1.59-.947-2.51-2.956-3.028l-.722-.187V3.467c1.122.11 1.879.714 2.07 1.616h1.47c-.166-1.6-1.54-2.748-3.54-2.875V1H7.591v1.233c-1.939.23-3.27 1.472-3.27 3.156 0 1.454.966 2.483 2.661 2.917l.61.162v4.031c-1.149-.17-1.94-.8-2.131-1.718H4zm3.391-3.836c-1.043-.263-1.6-.825-1.6-1.616 0-.944.704-1.641 1.8-1.828v3.495l-.2-.05zm1.591 1.872c1.287.323 1.852.859 1.852 1.769 0 1.097-.826 1.828-2.2 1.939V8.73l.348.086z"/>
                                </svg>
                                {{__('Pricing & Discounts')}}
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="price" class="form-label fw-semibold">
                                        {{__('Price')}} <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group input-group">
                                        <span class="input-group-text">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-tag" viewBox="0 0 16 16">
                                                <path d="M6 4.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm-1 0a.5.5 0 1 0-1 0 .5.5 0 0 0 1 0z"/>
                                                <path d="M2 1h4.586a1 1 0 0 1 .707.293l7 7a1 1 0 0 1 0 1.414l-4.586 4.586a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 1 6.586V2a1 1 0 0 1 1-1zm0 5.586 7 7L13.586 9l-7-7H2v4.586z"/>
                                            </svg>
                                        </span>
                                        <input type="number"
                                               class="form-control @error('price') is-invalid @enderror"
                                               id="price"
                                               placeholder="0.00"
                                               wire:model.live="price">
                                        @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label for="discount" class="form-label fw-semibold">
                                        {{__('Discount')}} <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group input-group">
                                        <input type="number"
                                               class="form-control @error('discount') is-invalid @enderror"
                                               id="discount"
                                               placeholder="0"
                                               wire:model.live="discount">
                                        <span class="input-group-text">%</span>
                                        @error('discount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label for="discount_2earn" class="form-label fw-semibold">
                                        {{__('Discount 2earn')}} <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group input-group">
                                        <input type="number"
                                               class="form-control @error('discount_2earn') is-invalid @enderror"
                                               id="discount_2earn"
                                               placeholder="0"
                                               wire:model.live="discount_2earn">
                                        <span class="input-group-text">%</span>
                                        @error('discount_2earn') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Media & Relations Section -->
                        <div class="mb-4">
                            <h6 class="text-uppercase text-muted mb-3 fw-bold">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-images me-2" viewBox="0 0 16 16">
                                    <path d="M4.502 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>
                                    <path d="M14.002 13a2 2 0 0 1-2 2h-10a2 2 0 0 1-2-2V5A2 2 0 0 1 2 3a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v8a2 2 0 0 1-1.998 2zM14 2H4a1 1 0 0 0-1 1h9.002a2 2 0 0 1 2 2v7A1 1 0 0 0 15 11V3a1 1 0 0 0-1-1zM2.002 4a1 1 0 0 0-1 1v8l2.646-2.354a.5.5 0 0 1 .63-.062l2.66 1.773 3.71-3.71a.5.5 0 0 1 .577-.094l1.777 1.947V5a1 1 0 0 0-1-1h-10z"/>
                                </svg>
                                {{__('Media & Relations')}}
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="photo_link" class="form-label fw-semibold">
                                        {{__('Photo link')}}
                                    </label>
                                    <div class="input-group input-group">
                                        <span class="input-group-text">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-link-45deg" viewBox="0 0 16 16">
                                                <path d="M4.715 6.542 3.343 7.914a3 3 0 1 0 4.243 4.243l1.828-1.829A3 3 0 0 0 8.586 5.5L8 6.086a1.002 1.002 0 0 0-.154.199 2 2 0 0 1 .861 3.337L6.88 11.45a2 2 0 1 1-2.83-2.83l.793-.792a4.018 4.018 0 0 1-.128-1.287z"/>
                                                <path d="M6.586 4.672A3 3 0 0 0 7.414 9.5l.775-.776a2 2 0 0 1-.896-3.346L9.12 3.55a2 2 0 1 1 2.83 2.83l-.793.792c.112.42.155.855.128 1.287l1.372-1.372a3 3 0 1 0-4.243-4.243L6.586 4.672z"/>
                                            </svg>
                                        </span>
                                        <input type="url"
                                               class="form-control @error('photo_link') is-invalid @enderror"
                                               id="photo_link"
                                               placeholder="https://example.com/image.jpg"
                                               wire:model.live="photo_link">
                                        @error('photo_link') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="thumbnailsImage" class="form-label fw-semibold">
                                        {{__('Thumbnails Image')}}
                                    </label>
                                    <input type="file"
                                           id="thumbnailsImage"
                                           wire:model.live="thumbnailsImage"
                                           class="form-control form-control @error('thumbnailsImage') is-invalid @enderror">
                                    @error('thumbnailsImage') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    @if ($item?->thumbnailsImage)
                                        <div class="mt-3">
                                            <img src="{{ asset('uploads/' . $item->thumbnailsImage->url) }}"
                                                 alt="Item thumbnailsImage"
                                                 class="img-thumbnail shadow-sm"
                                                 style="max-width: 200px;">
                                        </div>
                                    @endif
                                </div>
                                @if(count($deals))
                                    <div class="col-md-12">
                                        <label for="Deal" class="form-label fw-semibold">
                                            {{__('Deal')}} <span class="text-danger">*</span>
                                        </label>
                                        <select
                                            class="form-select form-select @error('Deal') is-invalid @enderror"
                                            wire:model.live="deal_id"
                                            id="Deal"
                                            aria-label="{{__('Enter Deal')}}">
                                            @foreach ($deals as $DealsItem)
                                                <option value="{{$DealsItem->id}}">
                                                    {{$DealsItem->id}} | {{$DealsItem->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('Deal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Description Section -->
                        <div class="mb-4">
                            <h6 class="text-uppercase text-muted mb-3 fw-bold">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-text me-2" viewBox="0 0 16 16">
                                    <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>
                                    <path d="M3 5.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3 8a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 8zm0 2.5a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5z"/>
                                </svg>
                                {{__('Description')}}
                            </h6>

                        </div>

                        <!-- Action Buttons -->
                        <div class="border-top pt-4 mt-4">
                            <div class="d-flex justify-content-end gap-2">
                                <button wire:click.prevent="cancel()"
                                        class="btn btn btn-outline-secondary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle me-2" viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                    </svg>
                                    {{__('Cancel')}}
                                </button>
                                @if($update)
                                    <button wire:click.prevent="updateI()"
                                            class="btn btn-primary px-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle me-2" viewBox="0 0 16 16">
                                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                            <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
                                        </svg>
                                        {{__('Update')}}
                                    </button>
                                @else
                                    <button wire:click.prevent="store()"
                                            class="btn  btn-success px-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-save me-2" viewBox="0 0 16 16">
                                            <path d="M2 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H9.5a1 1 0 0 0-1 1v7.293l2.646-2.647a.5.5 0 0 1 .708.708l-3.5 3.5a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L7.5 9.293V2a2 2 0 0 1 2-2H14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h2.5a.5.5 0 0 1 0 1H2z"/>
                                        </svg>
                                        {{__('Save')}}
                                    </button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
