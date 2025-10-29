<div class="{{getContainerType()}}">
    @component('components.breadcrumb')
        @slot('title')
            {{ $userGuideId ? __('Update User Guide') : __('Create User Guide') }}
        @endslot
    @endcomponent
    <div class="row ">
        @include('layouts.flash-messages')
    </div>
    <div class="row card">
        <div class="card-header border-info">
            <div class="d-flex align-items-center">
                <h6 class="card-title flex-grow-1">
                    {{ $userGuideId ? __('Update User Guide') : __('Create User Guide') }}
                </h6>
            </div>
        </div>
        <div class="card-body ">
            <form wire:submit.prevent="save" class="row" enctype="multipart/form-data">
                <div class="form-group col-6">
                    <label for="title" class="form-label">{{ __('Title') }}</label>
                    <input type="text" id="title" class="form-control" wire:model.defer="title" @if($userGuideId) disabled @endif>
                    @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group col-6"><label for="file" class="form-label">{{ __('File') }}</label>
                    <input type="file" id="file" class="form-control" wire:model="file">
                    @error('file') <span class="text-danger">{{ $message }}</span> @enderror
                    @if($file_path)
                        <div class="mt-2">
                            <a href="{{ asset('storage/' . $file_path) }}"
                               target="_blank">{{ __('Current Attachment') }}</a>
                        </div>
                    @endif
                </div>
                <div class="form-group col-6">
                    <label for="description" class="form-label">{{ __('Description') }}</label>
                    <textarea id="description" class="form-control" wire:model.defer="description" @if($userGuideId) disabled @endif></textarea>
                    @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group col-6">
                    <label for="routes" class="form-label">{{ __('Routes') }}</label>
                    <select id="routes" class="form-control" wire:model="routes" multiple>
                        @foreach($allRoutes as $route)
                            <option value="{{ $route['name'] }}">{{ $route['name'] }} ({{ $route['uri'] }})</option>
                        @endforeach
                    </select>
                    @error('routes') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="mt-3">
                    <button type="submit"
                            class="btn btn-outline-success float-end mx-1">{{ $userGuideId ? __('Update') : __('Create') }}</button>
                    <a href="{{ route('user_guides_index', app()->getLocale()) }}"
                       class="btn btn-outline-danger float-end">{{ __('Cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
