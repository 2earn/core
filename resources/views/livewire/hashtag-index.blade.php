<div class="{{getContainerType()}}">
    @section('title')
        {{ __('Hashtags') }}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Hashtags') }}
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-12">
            @include('layouts.flash-messages')
        </div>
    </div>
    <div class="row">
        <div class="col-12 card">
            <div class="card-header">
                <div class="row">
                    <div class="float-end col-sm-12 col-md-6 col-lg-6">
                        <input type="text" wire:model.live="search" class="form-control input-sm col-auto"
                               placeholder="{{__('Search')}}"/>
                    </div>
                    <div class="float-end col-sm-12 col-md-6 col-lg-6">
                        <a href="{{ route('hashtags_create',app()->getLocale()) }}"
                           class="btn btn-primary float-end col-auto">{{__('Create new hashtag')}}</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="list-group">
                    @forelse($hashtags as $hashtag)
                        <div class="list-group-item mb-3 border rounded p-3">
                            <div class="row g-3">
                                <div class="col-md-1">
                                    <div class="d-flex flex-column">
                                        <small class="text-muted">{{__('ID')}}</small>
                                        <span class="fw-semibold">{{ $hashtag->id }}</span>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="d-flex flex-column">
                                        <small class="text-muted">{{__('Name')}}</small>
                                        <span class="fw-semibold">{{ $hashtag->name }}</span>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="d-flex flex-column">
                                        <small class="text-muted">{{__('Translations')}}</small>
                                        @php $translations = $hashtag->getAllTranslations(); @endphp
                                        <div class="row mt-2">
                                            <div class="col-md-4">
                                                <div class="d-flex align-items-center mb-2">
                                                    <img src="{{Vite::asset("resources/images/flags/" . strtolower('sa') . ".svg")}}"
                                                         alt="{{__('Arabe')}}" title="{{__('Arabe')}}"
                                                         class="avatar-xxs me-2">
                                                    <small>{{ $translations['ar'] }}</small>
                                                </div>
                                                <div class="d-flex align-items-center mb-2">
                                                    <img src="{{Vite::asset("resources/images/flags/" . strtolower('fr') . ".svg")}}"
                                                         alt="{{__('Francais')}}" title="{{__('Francais')}}"
                                                         class="avatar-xxs me-2">
                                                    <small>{{ $translations['fr'] }}</small>
                                                </div>
                                                <div class="d-flex align-items-center mb-2">
                                                    <img src="{{Vite::asset("resources/images/flags/" . strtolower('gb') . ".svg")}}"
                                                         alt="{{__('English')}}" title="{{__('English')}}"
                                                         class="avatar-xxs me-2">
                                                    <small>{{ $translations['en'] }}</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="d-flex align-items-center mb-2">
                                                    <img src="{{Vite::asset("resources/images/flags/" . strtolower('es') . ".svg")}}"
                                                         alt="{{__('Spanish')}}" title="{{__('Spanish')}}"
                                                         class="avatar-xxs me-2">
                                                    <small>{{ $translations['es'] }}</small>
                                                </div>
                                                <div class="d-flex align-items-center mb-2">
                                                    <img src="{{Vite::asset("resources/images/flags/" . strtolower('tr') . ".svg")}}"
                                                         alt="{{__('Turkish')}}" title="{{__('Turkish')}}"
                                                         class="avatar-xxs me-2">
                                                    <small>{{ $translations['tr'] }}</small>
                                                </div>
                                                <div class="d-flex align-items-center mb-2">
                                                    <img src="{{Vite::asset("resources/images/flags/" . strtolower('ru') . ".svg")}}"
                                                         alt="{{__('Russian')}}" title="{{__('Russian')}}"
                                                         class="avatar-xxs me-2">
                                                    <small>{{ $translations['ru'] }}</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="d-flex align-items-center mb-2">
                                                    <img src="{{Vite::asset("resources/images/flags/" . strtolower('de') . ".svg")}}"
                                                         alt="{{__('German')}}" title="{{__('German')}}"
                                                         class="avatar-xxs me-2">
                                                    <small>{{ $translations['de'] }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="d-flex flex-column">
                                        <small class="text-muted">{{__('Slug')}}</small>
                                        <span>{{ $hashtag->slug }}</span>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="d-flex flex-column text-muted">
                                        <small>{{__('Dates')}}</small>
                                        <span>{{ $hashtag->created_at }}</span>
                                        /
                                        <span>{{ $hashtag->updated_at }}</span>
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex align-items-end justify-content-end">
                                    <div class="d-flex flex-column gap-2">
                                        <a href="{{ route('hashtags_edit', ['locale'=>app()->getLocale(),'id'=>$hashtag->id]) }}"
                                           class="btn btn-sm btn-outline-primary">{{__('Edit')}}</a>
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                wire:click="confirmDelete({{ $hashtag->id }})">
                                            {{__('Delete')}}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info text-center" role="alert">
                            <i class="mdi mdi-information-outline me-2"></i>
                            {{__('No hashtags found')}}
                        </div>
                    @endforelse
                </div>
                <!-- Modal for delete confirmation -->
                <div class="modal fade @if($confirmingDelete) show d-block @endif" tabindex="-1" role="dialog"
                     @if($confirmingDelete) style="background:rgba(0,0,0,0.5);" @endif>
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{ __('Confirm Deletion') }}</h5>
                                <button type="button" class="btn-close" wire:click="cancelDelete"></button>
                            </div>
                            <div class="modal-body">
                                <p>{{ __('Are you sure you want to delete this hashtag?') }}</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                        wire:click="cancelDelete">{{ __('Cancel') }}</button>
                                <button type="button" class="btn btn-danger"
                                        wire:click="deleteConfirmed">{{ __('Delete') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                {{ $hashtags->links() }}
            </div>
        </div>
    </div>
</div>
