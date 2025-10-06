<div class="container-fluid">
    <h2>{{ $userGuideId ? __('Update User Guide') : __('Create User Guide') }}</h2>
    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
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
                    <a href="{{ asset('storage/' . $file_path) }}" target="_blank">{{ __('Current Attachment') }}</a>
                </div>
            @endif
        </div>
        <button type="submit" class="btn btn-primary">{{ $userGuideId ? __('Update') : __('Create') }}</button>
        <a href="{{ route('user-guides.index', app()->getLocale()) }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
    </form>
</div>
