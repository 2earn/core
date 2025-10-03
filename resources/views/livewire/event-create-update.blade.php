<div class="container-fluid">
    @component('components.breadcrumb')
        @slot('title')
            @if($update)
                {{__('Update Event')}}
            @else
                {{__('Create Event')}}
            @endif
        @endslot
    @endcomponent
    <div class="row card">
        <div class="card-body row ">
            <form wire:submit.prevent="save">
                <input type="hidden" wire:model.live="idEvent">
                <div class="row">
                    <div class="form-group col-6">
                        <label for="title">{{__('Title')}}</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                               id="title"
                               @if($update) disabled @endif
                               placeholder="{{__('Enter title')}}" wire:model.live="title">
                        @error('title') <span class="text-danger">{{ $message }}</span>@enderror
                        <div class="form-text">{{__('Required field')}}</div>
                    </div>
                    <div class="form-group col-6">
                        <label for="Enabled">{{__('Enabled')}}</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" role="switch" wire:model.live="enabled" type="checkbox"
                                   id="Enabled" placeholder="{{__('enabled')}}">
                            <label class="form-check-label" for="Enabled">{{__('Enabled')}}</label>
                        </div>
                    </div>  <div class="form-group col-12">
                        <label for="content">{{__('Content')}}</label>
                        <textarea class="form-control @error('content') is-invalid @enderror"
                                  id="content"
                                  @if($update) disabled @endif
                                  wire:model.live="content"
                                  placeholder="{{__('Enter content')}}"></textarea>
                        @error('content') <span class="text-danger">{{ $message }}</span>@enderror
                        <div class="form-text">{{__('Required field')}}</div>
                    </div>

                    <div class="form-group col-12">
                        <label for="published_at">{{__('Published At')}}</label>
                        <input type="datetime-local" id="published_at" wire:model.live="published_at"
                               class="form-control">
                        @error('published_at') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group col-6">
                        <label for="start_at">{{__('Start At')}}</label>
                        <input type="datetime-local" id="start_at" wire:model.live="start_at" class="form-control">
                        @error('start_at') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group col-6">
                        <label for="end_at">{{__('End At')}}</label>
                        <input type="datetime-local" id="end_at" wire:model.live="end_at" class="form-control">
                        @error('end_at') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group col-6">
                        <label for="mainImage">{{__('Main Image')}}</label>
                        <input type="file" id="mainImage" wire:model.live="mainImage" class="form-control">
                        @error('mainImage') <span class="error">{{ $message }}</span> @enderror
                        @if ($event?->mainImage)
                            <div class="mt-3">
                                <img src="{{ asset('uploads/' . $event->mainImage->url) }}"
                                     alt="Event Main Image" class="img-thumbnail">
                            </div>
                        @endif
                    </div>
                    <div class="form-group col-6">
                        <label for="location">{{__('Location')}}</label>
                        <input type="text" class="form-control" id="location" @if($update) disabled @endif
                        wire:model.live="location" placeholder="{{__('Enter location')}}">
                    </div>
                    <div class="form-group col-12">
                        <label>{{__('Hashtags')}}</label>
                        <div class="row ms-1">
                            @foreach($this->allHashtags as $hashtag)
                                <div class="form-check form-switch col-auto mb-2">
                                    <input class="form-check-input" type="checkbox"
                                           id="hashtag-{{ $hashtag->id }}"
                                           value="{{ $hashtag->id }}"
                                           wire:model.live="selectedHashtags">
                                    <label class="form-check-label" for="hashtag-{{ $hashtag->id }}">
                                        {{ $hashtag->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div class="form-text">{{__('Select one or more hashtags')}}</div>
                    </div>
                    <div class="form-group col-12 mt-3">
                        <button type="submit"
                                class="btn btn-success">{{ $update ? __('Update') : __('Create') }}</button>
                        <button type="button" class="btn btn-secondary" wire:click="cancel">{{__('Cancel')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
