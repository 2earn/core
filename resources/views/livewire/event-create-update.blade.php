<div class="container">
    @component('components.breadcrumb')
        @slot('title')
            @if($update)
                {{__('Update Event')}}
            @else
                {{__('Create Event')}}
            @endif
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12 card">
            <div class="card-body">
                <form wire:submit.prevent="save">
                    <input type="hidden" wire:model.live="idEvent">
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <label for="title" class="form-label fw-semibold">
                                {{__('Title')}} <span class="text-danger">*</span>
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
                        <div class="col-md-4">
                            <label class="form-label fw-semibold d-block">{{__('Status')}}</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input"
                                       role="switch"
                                       wire:model.live="enabled"
                                       type="checkbox"
                                       id="Enabled">
                                <label class="form-check-label" for="Enabled">
                                    {{__('Enabled')}}
                                </label>
                            </div>
                        </div>
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

                    <!-- Date & Time Section -->
                    <div class="mb-4">
                        <h6 class="fw-semibold text-secondary mb-3">{{__('Schedule')}}</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="published_at" class="form-label fw-semibold">{{__('Published At')}}</label>
                                <input type="datetime-local"
                                       id="published_at"
                                       wire:model.live="published_at"
                                       class="form-control @error('published_at') is-invalid @enderror">
                                @error('published_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="start_at" class="form-label fw-semibold">{{__('Start At')}}</label>
                                <input type="datetime-local"
                                       id="start_at"
                                       wire:model.live="start_at"
                                       class="form-control @error('start_at') is-invalid @enderror">
                                @error('start_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="end_at" class="form-label fw-semibold">{{__('End At')}}</label>
                                <input type="datetime-local"
                                       id="end_at"
                                       wire:model.live="end_at"
                                       class="form-control @error('end_at') is-invalid @enderror">
                                @error('end_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Location & Image Section -->
                    <div class="mb-4">
                        <h6 class="fw-semibold text-secondary mb-3">{{__('Details')}}</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="location" class="form-label fw-semibold">{{__('Location')}}</label>
                                <input type="text"
                                       class="form-control"
                                       id="location"
                                       @if($update) disabled @endif
                                       wire:model.live="location"
                                       placeholder="{{__('Enter location')}}">
                            </div>
                            <div class="col-md-6">
                                <label for="mainImage" class="form-label fw-semibold">{{__('Main Image')}}</label>
                                <input type="file"
                                       id="mainImage"
                                       wire:model.live="mainImage"
                                       class="form-control @error('mainImage') is-invalid @enderror"
                                       accept="image/*">
                                @error('mainImage')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        @if ($event?->mainImage)
                            <div class="mt-3">
                                <p class="text-muted small mb-2">{{__('Current Image')}}:</p>
                                <img src="{{ asset('uploads/' . $event->mainImage->url) }}"
                                     alt="Event Main Image"
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

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <button type="button"
                                class="btn btn-outline-secondary px-4"
                                wire:click="cancel">
                            {{__('Cancel')}}
                        </button>
                        <button type="submit"
                                class="btn btn-outline-success px-4">
                            {{ $update ? __('Update') : __('Create') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
