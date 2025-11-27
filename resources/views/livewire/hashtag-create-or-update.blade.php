<div class="{{getContainerType()}}">
    @section('title')
        {{ __('Hashtags') }} {{ $hashtagId ? 'Edit' : 'Create' }}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Hashtags') }} {{ $hashtagId ? 'Edit' : 'Create' }}
        @endslot
    @endcomponent
    <div class="row">
            @include('layouts.flash-messages')
    </div>

    <div class="row">
        <div class="col-12 card">
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
                    <div class="mb-3">
                        <label class="form-label">{{ __('Translations') }}</label>
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <input type="text" wire:model="translations.ar" class="form-control"
                                       placeholder="Arabic (ar)">
                            </div>
                            <div class="col-md-4 mb-2">
                                <input type="text" wire:model="translations.fr" class="form-control"
                                       placeholder="French (fr)">
                            </div>
                            <div class="col-md-4 mb-2">
                                <input type="text" wire:model="translations.en" class="form-control"
                                       placeholder="English (en)">
                            </div>
                            <div class="col-md-4 mb-2">
                                <input type="text" wire:model="translations.es" class="form-control"
                                       placeholder="Spanish (es)">
                            </div>
                            <div class="col-md-4 mb-2">
                                <input type="text" wire:model="translations.tr" class="form-control"
                                       placeholder="Turkish (tr)">
                            </div>
                            <div class="col-md-4 mb-2">
                                <input type="text" wire:model="translations.ru" class="form-control"
                                       placeholder="Russian (ru)">
                            </div>
                            <div class="col-md-4 mb-2">
                                <input type="text" wire:model="translations.de" class="form-control"
                                       placeholder="German (de)">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card-footer">
                    <button type="submit"
                            class="btn btn-success">{{ $hashtagId ? __('Update') : __('Create') }}</button>
                    <a href="{{ route('hashtags_index',app()->getLocale()) }}"
                       class="btn btn-secondary">{{__('Back')}}</a>
                </div>
            </form>
        </div>
    </div>
</div>
