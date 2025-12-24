<div class="container">
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
    <div class="row">
        <div class="col-12 card mb-3">
            <div class="card-body row">
                <div class="col-md-6">
                    <input type="text" class="form-control" placeholder="{{ __('Search user guides...') }}"
                           wire:model.live="search">
                </div>
                @if(\App\Models\User::isSuperAdmin())
                <div class="col-md-6 text-end">
                    <a href="{{ route('user_guides_create', app()->getLocale()) }}" class="btn btn-outline-info">
                       {{ __('Add User Guide') }}
                    </a>
                </div>
                @endif
            </div>
        </div>
        <div class="col-12 mb-4 card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="small text-muted">{{ __('Showing') }} {{ $userGuides->count() }} / {{ $userGuides->total() }} {{ __('user guides') }}</div>
                <div class="d-flex align-items-center">
                    <input type="text" class="form-control form-control-sm me-2 d-md-none" placeholder="{{ __('Search...') }}" wire:model.live="search">
                    <!-- Primary create button retained in the header; no duplicate here -->
                </div>
            </div>

            @if($userGuides->count())
                <div class="row gx-3 gy-3">
                    @foreach($userGuides as $guide)
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="mb-0 text-truncate" title="{{ \App\Models\TranslaleModel::getTranslation($guide,'title',$guide->title) }}">{{ \App\Models\TranslaleModel::getTranslation($guide,'title',$guide->title) }}</h5>
                                        @if(\App\Models\User::isSuperAdmin())
                                            <a href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($guide,'title')])}}" class="ms-2 small text-muted">{{__('Translate')}}</a>
                                        @endif
                                    </div>

                                    <p class="text-muted mb-2 small">{!! \Illuminate\Support\Str::limit(strip_tags(\App\Models\TranslaleModel::getTranslation($guide,'description',$guide->description)), 180) !!}</p>

                                    @if($guide->routes && is_array($guide->routes))
                                        <div class="mb-2">
                                            @foreach($guide->routes as $routeName)
                                                <span class="badge bg-light text-muted me-1">{{ $routeName }}</span>
                                            @endforeach
                                        </div>
                                    @endif

                                    @if($guide->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($guide->file_path))
                                        <div class="mb-2">
                                            <i class="ri-attachment-line"></i>
                                            <a href="{{ asset('storage/' . $guide->file_path) }}" target="_blank" class="small">{{ __('Download attachment') }}</a>
                                        </div>
                                    @endif

                                    <div class="mt-auto d-flex justify-content-between align-items-center">
                                        <div class="small text-muted">
                                            <div>{{ __('Created by') }}: {{   getUserDisplayedName($guide->user->idUser) ?? __('Unknown') }}</div>
                                            <div>{{ $guide->created_at ? $guide->created_at->format('Y-m-d') : __('N/A') }}</div>
                                        </div>
                                        <div class="btn-group ms-2" role="group" aria-label="{{ __('Guide actions') }}">
                                            <a href="{{ route('user_guides_show', [app()->getLocale(), $guide->id]) }}" class="btn btn-sm btn-outline-info me-1">{{ __('Details') }}</a>
                                            @if(\App\Models\User::isSuperAdmin())
                                                <a href="{{ route('user_guides_edit', [app()->getLocale(), $guide->id]) }}" class="btn btn-sm btn-outline-warning me-1">{{ __('Edit') }}</a>
                                                <button type="button" class="btn btn-sm btn-outline-danger" wire:click="confirmDelete({{ $guide->id }})">{{ __('Delete') }}</button>
                                            @endif
                                        </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                    @endforeach
                 </div>

                 <div class="mt-3">{{ $userGuides->onEachSide(1)->links('pagination::bootstrap-4') }}</div>
            @else
                  <div class="py-5 text-center w-100">
                      <h5 class="text-muted">{{ __('No user guides found.') }}</h5>
                      <p class="text-muted">{{ __('There are no guides yet. Use the Add User Guide button to create one.') }}</p>
                  </div>
            @endif
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
