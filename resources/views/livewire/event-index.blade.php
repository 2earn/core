<div class="container-fluid">
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Events') }}
        @endslot
    @endcomponent
    @include('layouts.flash-messages')
    <div class="card">
        <div class="card-body">
            <div class="card-header border-info">
                <div class="row">
                    <div class="float-end col-sm-12 col-md-6 col-lg-6">
                        <form class="items-center">
                            <label for="simple-search" class="sr-only">{{__('Search')}}</label>
                            <div class="w-full">
                                <input wire:model.live="search" type="text" id="simple-search"
                                       class="form-control float-end"
                                       placeholder="{{__('Search events')}}">
                            </div>
                        </form>
                    </div>
                    @if(\App\Models\User::isSuperAdmin())
                        <div class="col-sm-12 col-md-3  col-lg-6">
                            <a href="{{route('event_create_update', app()->getLocale())}}"
                               class="btn btn-soft-info add-btn float-end"
                               id="create-btn">
                                {{__('Create new event')}}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            <div class="card-body row">
                @forelse($events as $event)
                    <div class="col-sm-12 col-lg-12">
                        <div class="card border card-border-light">
                            <div class="card-header">
                                <h4 class="card-title mb-1">
                                    {{$event->id}}
                                    - {{\App\Models\TranslaleModel::getTranslation($event,'title',$event->title)}}
                                    @if($event->enabled)
                                        <span class="badge bg-success float-end">{{__('Enabled')}}</span>
                                    @else
                                        <span class="badge bg-danger float-end">{{__('Disabled')}}</span>
                                    @endif
                                </h4>
                                @if(\App\Models\User::isSuperAdmin())
                                    <p class="mx-2">
                                        <a class="link-info"
                                           href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($event,'title')])}}">{{__('See or update Translation')}}</a>
                                    </p>
                                @endif
                            </div>
                            <div class="card-body row">
                                @if($event->hashtags && $event->hashtags->count())
                                    <div class="mt-2">
                                        <span class="fw-semibold">{{ __('Hashtags:') }}</span>
                                        <br>
                                        @foreach($event->hashtags as $hashtag)
                                            <span class="badge bg-info text-light mx-1">#{{ $hashtag->name }}</span>
                                        @endforeach
                                    </div>
                                @endif
                                <div @if($event->mainImage) class="col-sm-12 col-md-8 col-lg-8"
                                     @else class="col-sm-12 col-md-12 col-lg-12" @endif>
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                         <span class="fw-semibold">{{__('Content')}}
                                            :</span>
                                        <blockquote class="text-muted">
                                            {!! \App\Models\TranslaleModel::getTranslation($event,'content',$event->content) !!}
                                        </blockquote>
                                        @if(\App\Models\User::isSuperAdmin())
                                            <p class="mx-2">
                                                <a class="link-info"
                                                   href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($event,'content')])}}">{{__('See or update Translation')}}</a>
                                            </p>
                                        @endif
                                    </div>

                                    <div class="col-sm-12 col-md-12 col-lg-12 mt-2">
                                        <p class="float-end text-muted mt-2">
                                            <strong>{{__('Published at')}} :</strong> {{$event->published_at}}</p>
                                            <strong>{{__('Start at')}}:</strong> {{$event->start_at}} /
                                            <strong>{{__('End at')}}:</strong> {{$event->end_at}}</p>
                                        <p>
                                    </div>
                                </div>
                                @if($event->mainImage)
                                    <div class="col-sm-12 col-md-4 col-lg-3">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <img src="{{ asset('uploads/' . $event->mainImage->url) }}"
                                                 class="img-thumbnail"
                                                 alt="{{ __('Event Image') }}">
                                        </div>
                                    </div>
                                @endif
                                @if($event->location)
                                    <div class="col-sm-12 col-md-12 col-lg-12 mt-2">
                                        <p>
                                            <strong>{{__('Location')}}
                                                :</strong> {{\App\Models\TranslaleModel::getTranslation($event,'location',$event->location)}}
                                            @if(\App\Models\User::isSuperAdmin())
                                                <span class="mx-2">
                                                <a class="link-info"
                                                   href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($event,'location')])}}">{{__('See or update Translation')}}</a>
                                            </span>
                                            @endif
                                        </p>
                                    </div>
                                @endif

                            </div>
                            <div class="card-footer text-muted">
                                <div class="mt-2">
                                    <span>
                                        <i class="fa fa-thumbs-up"></i>
                                        {{ $event->likes()->count() ?? 0 }} {{ __('Likes') }}
                                    </span>
                                    <span class="me-3">
                                        <i class="fa fa-comments"></i>
                                        {{ $event->comments()->where('validated',true)->count()  ?? 0 }} {{ __('Comments') }}
                                    </span>
                                </div>
                                @if(\App\Models\User::isSuperAdmin())
                                    <p class="mx-2 float-end">
                                        <a href="{{ route('event_create_update', ['locale' => app()->getLocale(), 'id' => $event->id]) }}"
                                           class="btn btn-outline-primary btn-sm">{{__('Edit')}}</a>
                                        <button type="button"
                                                class="btn btn-outline-danger btn-sm"
                                                wire:click="confirmDelete({{$event->id}})">
                                            {{__('Delete')}}
                                            <div wire:loading wire:target="delete">
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                      aria-hidden="true"></span>
                                                <span class="sr-only">{{__('Loading')}}...</span>
                                            </div>
                                        </button>
                                        <button wire:click.prevent="duplicate({{$event->id}})"
                                                class="btn btn-outline-warning btn-sm">{{__('Duplicate')}}</button>
                                    </p>
                                @endif
                                <a href="{{ route('event_show', ['locale' => app()->getLocale(), 'id' => $event->id]) }}"
                                   class="btn btn-outline-info btn-sm float-end">
                                    {{ __('View details') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info">{{__('No events found.')}}</div>
                    </div>
                @endforelse
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $events->links() }}
            </div>
        </div>
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-danger" wire:click="delete">{{ __('Delete') }}</button>
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

