<div class="container-fluid">
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
        <div class="card-body">
            <form wire:submit.prevent="save" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="title" class="form-label">{{ __('Title') }}</label>
                    <input type="text" id="title" class="form-control" wire:model.defer="title">
                    @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">{{ __('Description') }}</label>
                    <textarea id="description" class="form-control" wire:model.defer="description"></textarea>
                    @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="mb-3">
                    <label for="file" class="form-label">{{ __('Attachment') }}</label>
                    <input type="file" id="file" class="form-control" wire:model="file">
                    @error('file') <span class="text-danger">{{ $message }}</span> @enderror
                    @if($file_path)
                        <div class="mt-2">
                            <a href="{{ asset('storage/' . $file_path) }}"
                               target="_blank">{{ __('Current Attachment') }}</a>
                        </div>
                    @endif
                </div>
                <button type="submit" class="btn btn-primary">{{ $userGuideId ? __('Update') : __('Create') }}</button>
                <a href="{{ route('user_guides_index', app()->getLocale()) }}"
                   class="btn btn-secondary">{{ __('Cancel') }}</a>
            </form>
        </div>
</div>
</div>
