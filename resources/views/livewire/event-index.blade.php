<div class="{{getContainerType()}}">
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Events') }}
        @endslot
    @endcomponent
    @include('layouts.flash-messages')
    <div class="card shadow-sm">
        <div class="card-header bg-light border-bottom">
            <div class="row align-items-center g-3">
                @if(\App\Models\User::isSuperAdmin())
                    <div class="col-sm-12 col-md-6 col-lg-6 order-2 order-md-1">
                        <form>
                            <label for="simple-search" class="sr-only">{{__('Search')}}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="fa fa-search text-muted"></i>
                                </span>
                                <input wire:model.live="search" type="text" id="simple-search"
                                       class="form-control border-start-0"
                                       placeholder="{{__('Search events')}}">
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6 order-1 order-md-2 text-end">
                        <a href="{{route('event_create_update', app()->getLocale())}}"
                           class="btn btn-outline-info text-white">
                            <i class="fa fa-plus me-2"></i>{{__('Create new event')}}
                        </a>
                    </div>
                @else
                    <div class="col-12">
                        <form>
                            <label for="simple-search" class="sr-only">{{__('Search')}}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="fa fa-search text-muted"></i>
                                </span>
                                <input wire:model.live="search" type="text" id="simple-search"
                                       class="form-control border-start-0"
                                       placeholder="{{__('Search events')}}">
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div class="row g-4">
                @forelse($events as $event)
                    <div class="col-12">
                        <div class="card border-0 shadow-sm h-100">
                            <!-- Card Header -->
                            <div class="card-header bg-white border-bottom">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <span class="badge bg-secondary">ID: {{$event->id}}</span>
                                            @if($event->enabled)
                                                <span class="badge bg-success">
                                                    <i class="fa fa-check-circle me-1"></i>{{__('Enabled')}}
                                                </span>
                                            @else
                                                <span class="badge bg-danger">
                                                    <i class="fa fa-times-circle me-1"></i>{{__('Disabled')}}
                                                </span>
                                            @endif
                                        </div>
                                        <h4 class="card-title mb-2 text-dark">
                                            {{\App\Models\TranslaleModel::getTranslation($event,'title',$event->title)}}
                                        </h4>
                                        @if(\App\Models\User::isSuperAdmin())
                                            <a class="link-info small text-decoration-none"
                                               href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($event,'title')])}}">
                                                <i class="fa fa-language me-1"></i>{{__('See or update Translation')}}
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                <!-- Hashtags -->
                                @if($event->hashtags && $event->hashtags->count())
                                    <div class="mt-3">
                                        @foreach($event->hashtags as $hashtag)
                                            <span class="badge bg-info bg-opacity-10 text-info border border-info me-1 mb-1">
                                                #{{ $hashtag->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <!-- Card Body -->
                            <div class="card-body">
                                <div class="row">
                                    <!-- Main Content Area -->
                                    <div @if($event->mainImage) class="col-lg-8 col-md-7" @else class="col-12" @endif>
                                        <!-- Date Info -->
                                        <div class="mb-3 p-3 bg-light rounded">
                                            <div class="row g-2 small">
                                                <div class="col-md-4">
                                                    <div class="text-muted">
                                                        <i class="fa fa-calendar me-2"></i>
                                                        <strong>{{__('Published at')}}:</strong>
                                                    </div>
                                                    <div>{{$event->published_at}}</div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="text-muted">
                                                        <i class="fa fa-clock me-2"></i>
                                                        <strong>{{__('Start at')}}:</strong>
                                                    </div>
                                                    <div>{{$event->start_at}}</div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="text-muted">
                                                        <i class="fa fa-clock me-2"></i>
                                                        <strong>{{__('End at')}}:</strong>
                                                    </div>
                                                    <div>{{$event->end_at}}</div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Location -->
                                        @if($event->location)
                                            <div class="mb-3">
                                                <div class="d-flex align-items-start">
                                                    <i class="fa fa-map-marker-alt text-danger me-2 mt-1"></i>
                                                    <div class="flex-grow-1">
                                                        <strong class="d-block mb-1">{{__('Location')}}:</strong>
                                                        <span class="text-muted">
                                                            {{\App\Models\TranslaleModel::getTranslation($event,'location',$event->location)}}
                                                        </span>
                                                        @if(\App\Models\User::isSuperAdmin())
                                                            <div class="mt-1">
                                                                <a class="link-info small text-decoration-none"
                                                                   href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($event,'location')])}}">
                                                                    <i class="fa fa-language me-1"></i>{{__('See or update Translation')}}
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Content -->
                                        <div class="mb-3">
                                            <h6 class="text-muted mb-2">
                                                <i class="fa fa-align-left me-2"></i>{{__('Content')}}
                                            </h6>
                                            <div class="text-muted lh-base">
                                                {!! \App\Models\TranslaleModel::getTranslation($event,'content',$event->content) !!}
                                            </div>
                                            @if(\App\Models\User::isSuperAdmin())
                                                <div class="mt-2">
                                                    <a class="link-info small text-decoration-none"
                                                       href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($event,'content')])}}">
                                                        <i class="fa fa-language me-1"></i>{{__('See or update Translation')}}
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Image Area -->
                                    @if($event->mainImage)
                                        <div class="col-lg-4 col-md-5">
                                            <div class="position-sticky" style="top: 20px;">
                                                <img src="{{ asset('uploads/' . $event->mainImage->url) }}"
                                                     class="img-fluid rounded shadow-sm w-100"
                                                     alt="{{ __('Event Image') }}"
                                                     style="object-fit: cover; max-height: 300px;">
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Card Footer -->
                            <div class="card-footer bg-light border-top">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <!-- Engagement Stats -->
                                    <div class="d-flex gap-3">
                                        <span class="text-muted">
                                            <i class="fa fa-thumbs-up text-primary me-1"></i>
                                            <strong>{{ $event->likes()->count() ?? 0 }}</strong> {{ __('Likes') }}
                                        </span>
                                        <span class="text-muted">
                                            <i class="fa fa-comments text-success me-1"></i>
                                            <strong>{{ $event->comments()->where('validated',true)->count()  ?? 0 }}</strong> {{ __('Comments') }}
                                        </span>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="d-flex gap-2 flex-wrap">
                                        <a href="{{ route('event_show', ['locale' => app()->getLocale(), 'id' => $event->id]) }}"
                                           class="btn btn-outline-info btn-sm">
                                            <i class="fa fa-eye me-1"></i>{{ __('View details') }}
                                        </a>
                                        @if(\App\Models\User::isSuperAdmin())
                                            <a href="{{ route('event_create_update', ['locale' => app()->getLocale(), 'id' => $event->id]) }}"
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="fa fa-edit me-1"></i>{{__('Edit')}}
                                            </a>
                                            <button wire:click.prevent="duplicate({{$event->id}})"
                                                    class="btn btn-outline-warning btn-sm">
                                                <i class="fa fa-copy me-1"></i>{{__('Duplicate')}}
                                            </button>
                                            <button type="button"
                                                    class="btn btn-outline-danger btn-sm"
                                                    wire:click="confirmDelete({{$event->id}})">
                                                <i class="fa fa-trash me-1"></i>{{__('Delete')}}
                                                <span wire:loading wire:target="delete" class="spinner-border spinner-border-sm ms-1" role="status" aria-hidden="true"></span>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fa fa-calendar-times fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">{{__('No events found.')}}</h5>
                            <p class="text-muted">{{__('Try adjusting your search criteria or create a new event.')}}</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
        @if($events->hasPages())
            <div class="card-footer bg-white border-top">
                <div class="d-flex justify-content-center">
                    {{ $events->links() }}
                </div>
            </div>
        @endif
    </div>

    <div class="modal fade" id="deleteEventModal" tabindex="-1" aria-labelledby="deleteEventModalLabel"
         aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteEventModalLabel">{{ __('Confirm Delete') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('Are you sure you want to delete this event? This action cannot be undone.') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-outline-danger" wire:click="delete">{{ __('Delete') }}</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            window.addEventListener('showDeleteModal', () => {
                var myModal = new bootstrap.Modal(document.getElementById('deleteEventModal'));
                myModal.show();
            });
            window.addEventListener('hideDeleteModal', () => {
                var myModalEl = document.getElementById('deleteEventModal');
                var modal = bootstrap.Modal.getInstance(myModalEl);
                if (modal) modal.hide();
            });
            document.getElementById('deleteEventModal').addEventListener('hidden.bs.modal', function () {
                Livewire.dispatch('clearDeleteEventId');
            });
        </script>
    @endpush
</div>

