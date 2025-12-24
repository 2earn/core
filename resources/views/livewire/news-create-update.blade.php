<div class="container">
    @component('components.breadcrumb')
        @slot('title')
            @if($update)
                {{__('Update News')}}
            @else
                {{__('Create News')}}
            @endif
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        @if($update)
                            {{__('Update News')}}
                        @else
                            {{__('Create News')}}
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    <form>
                        <input type="hidden" wire:model.live="id">

                        <!-- Title Field -->
                        <div class="mb-4">
                            <label for="title" class="form-label fw-semibold">
                                {{__('title')}} <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('title') is-invalid @enderror"
                                   id="title"
                                   @if($update) disabled @endif
                                   placeholder="{{__('Enter title')}}"
                                   wire:model.live="title">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Content Field -->
                        <div class="mb-4">
                            <label for="content" class="form-label fw-semibold">
                                {{__('Content')}} <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('content') is-invalid @enderror"
                                      id="content"
                                      @if($update) disabled @endif
                                      wire:model.live="content"
                                      rows="6"
                                      placeholder="{{__('Enter content')}}"></textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Enabled Switch -->
                        <div class="mb-4">
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

                        <!-- Main Image Field -->
                        <div class="mb-4">
                            <label for="mainImage" class="form-label fw-semibold">
                                {{__('Main Image')}}
                            </label>
                            <input type="file"
                                   id="mainImage"
                                   wire:model.live="mainImage"
                                   class="form-control @error('mainImage') is-invalid @enderror"
                                   accept="image/*">
                            @error('mainImage')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror

                            @if ($news?->mainImage)
                                <div class="mt-3">
                                    <p class="text-muted small mb-2">{{__('Current Image')}}:</p>
                                    <img src="{{ asset('uploads/' . $news->mainImage->url) }}"
                                         alt="News Main Image"
                                         class="img-thumbnail"
                                         style="max-width: 300px; max-height: 300px;">
                                </div>
                            @endif
                        </div>

                        <!-- Hashtags Section -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">{{__('Hashtags')}}</label>
                            <div class="border rounded p-3 bg-light">
                                <div class="row g-3">
                                    @foreach($this->allHashtags as $hashtag)
                                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                            <div class="form-check">
                                                <input class="form-check-input"
                                                       type="checkbox"
                                                       id="hashtag-{{ $hashtag->id }}"
                                                       value="{{ $hashtag->id }}"
                                                       wire:model.live="selectedHashtags">
                                                <label class="form-check-label" for="hashtag-{{ $hashtag->id }}">
                                                    {{ $hashtag->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="form-text">{{__('Select one or more hashtags')}}</div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <button wire:click.prevent="cancel()"
                                    type="button"
                                    class="btn btn-outline-secondary px-4">
                                {{__('Cancel')}}
                            </button>

                            @if($update)
                                <button wire:click.prevent="updateNews()"
                                        type="button"
                                        class="btn btn-success px-4">
                                    {{__('Update')}}
                                </button>
                            @else
                                <button wire:click.prevent="store()"
                                        type="button"
                                        class="btn btn-success px-4">
                                    {{__('Save')}}
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
