<div class="container-fluid">
    @section('title')
        {{ __('User Guides') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('User Guides') }}
        @endslot
    @endcomponent
    <div class="row ">
        @include('layouts.flash-messages')
    </div>
    <div class="row card">
        <div class="card mb-3">
            <div class="card-body row">
                <div class="col-md-6">
                    <input type="text" class="form-control" placeholder="{{ __('Search user guides...') }}"
                           wire:model.live="search">
                </div>
                @if(\App\Models\User::isSuperAdmin())
                <div class="col-md-6 text-end">
                    <a href="{{ route('user_guides_create', app()->getLocale()) }}" class="btn btn-success">
                        <i class="fa fa-plus"></i> {{ __('Add User Guide') }}
                    </a>
                </div>
                @endif
            </div>
        </div>
        <div class="col-md-12 mb-4 card-body">
            @forelse($userGuides as $guide)
                <div class="card mb-3">
                    <div class="card-title">
                        <h5 class="text-info m-2">{{\App\Models\TranslaleModel::getTranslation($guide,'title',$guide->title)}}</h5>
                        @if(\App\Models\User::isSuperAdmin())
                            <p class="mx-2 float-end">
                                <a class="link-info"
                                   href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($guide,'title')])}}">{{__('See or update Translation')}}</a>
                            </p>
                        @endif
                    </div>
                    <div class="card-body">
                        <blockquote class="text-muted">
                            {!! \App\Models\TranslaleModel::getTranslation($guide,'description',$guide->description) !!}
                        </blockquote>
                        @if(\App\Models\User::isSuperAdmin())
                            <p class="mx-2 float-end">
                                <a class="link-info"
                                   href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($guide,'description')])}}">{{__('See or update Translation')}}</a>
                            </p>
                        @endif
                        @if($guide->routes && is_array($guide->routes))
                            <div class="mb-2">
                                <strong>{{ __('Routes:') }}</strong>
                                <ul class="mb-0">
                                    @foreach($guide->routes as $routeName)
                                        @php
                                            $route = Route::getRoutes()->getByName($routeName);
                                        @endphp
                                        @if($route)
                                            <li>{{ $routeName }} ({{ $route->uri() }})</li>
                                        @else
                                            <li>{{ $routeName }}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if($guide->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($guide->file_path))
                            <a href="{{ asset('storage/' . $guide->file_path) }}" target="_blank">Download
                                Attachment</a>
                        @endif
                    </div>
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <div>
                             <span class="text-muted m-1">Created by: {{ $guide->user->name ?? __('Unknown') }}</span>
                            <span class="text-muted m-1">{{ __('Created at:') }} {{ $guide->created_at ? $guide->created_at->format('Y-m-d H:i') : __('N/A') }}</span>
                             <span class="text-muted m-1">{{ __('Updated at:') }} {{ $guide->updated_at ? $guide->updated_at->format('Y-m-d H:i') : __('N/A') }}</span>
                        </div>
                        <div>
                            <a href="{{ route('user_guides_show', [app()->getLocale(), $guide->id]) }}" class="btn btn-sm btn-info me-2">{{ __('Details') }}</a>
                            @if(\App\Models\User::isSuperAdmin())
                            <a href="{{ route('user_guides_edit', [app()->getLocale(), $guide->id]) }}" class="btn btn-sm btn-warning me-2">{{ __('Edit') }}</a>
                            <button type="button" class="btn btn-sm btn-danger"
                                    wire:click="confirmDelete({{ $guide->id }})">{{ __('Delete') }}</button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted">No user guides found.</p>
            @endforelse
            <div class="mt-3">
                {{ $userGuides->onEachSide(1)->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">{{ __('Confirm Deletion') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{ __('Are you sure you want to delete this user guide?') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-danger" wire:click="delete">{{ __('Delete') }}</button>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            window.addEventListener('show-delete-modal', () => {
                var modal = new bootstrap.Modal(document.getElementById('deleteModal'));
                modal.show();
            });
            window.addEventListener('hide-delete-modal', () => {
                var modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
                if (modal) modal.hide();
            });
        </script>
    @endpush
</div>
