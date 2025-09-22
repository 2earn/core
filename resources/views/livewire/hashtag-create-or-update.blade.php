<div class="container-fluid">
    @section('title')
        {{ __('Hashtags') }} {{ $hashtagId ? 'Edit' : 'Create' }}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Hashtags') }} {{ $hashtagId ? 'Edit' : 'Create' }}
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-12">
            @include('layouts.flash-messages')
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h1>{{ $hashtagId ? 'Edit' : 'Create' }} Hashtag</h1>
        </div>
        <form wire:submit.prevent="save">
            <div class="card-body">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" id="name" wire:model="name" class="form-control" required>
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="mb-3">
                    <label for="slug" class="form-label">Slug</label>
                    <input type="text" disabled id="slug" wire:model="slug" class="form-control" required readonly>
                    @error('slug') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success">{{ $hashtagId ? __('Update') : __('Create') }}</button>
                <a href="{{ route('hashtags_index',app()->getLocale()) }}" class="btn btn-secondary">{{__('Back')}}</a>
            </div>
        </form>
    </div>
</div>
