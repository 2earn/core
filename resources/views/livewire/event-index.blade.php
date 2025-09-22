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
                                <h5 class="card-title mb-1">
                                    {{$event->id}}
                                    - {{\App\Models\TranslaleModel::getTranslation($event,'title',$event->title)}}
                                </h5>
                                @if($event->enabled)
                                    <span class="badge bg-success float-end">{{__('Enabled')}}</span>
                                @else
                                    <span class="badge bg-danger float-end">{{__('Disabled')}}</span>
                                @endif
                            </div>
                            <div class="card-body row">
                                <div @if($event->mainImage) class="col-sm-12 col-md-8 col-lg-8"
                                     @else class="col-sm-12 col-md-12 col-lg-12" @endif>
                                    <div class="col-md-12">
                                        <strong>{{__('Content')}}
                                            :</strong> {!! \App\Models\TranslaleModel::getTranslation($event,'content',$event->content) !!}

                                        @if(\App\Models\User::isSuperAdmin())
                                            <p class="mx-2 float-end">
                                                <a class="link-info"
                                                   href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($event,'content')])}}">{{__('See or update Translation')}}</a>
                                            </p>
                                        @endif
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="float-end">
                                            <strong>{{__('Published at')}}
                                                :</strong> {{$event->published_at}}</div>
                                        <div><strong>{{__('Start at')}}:</strong> {{$event->start_at}} /
                                            <strong>{{__('End at')}}:</strong> {{$event->end_at}}</div>
                                        <div><strong>{{__('Location')}}:</strong> {{$event->location}}
                                        </div>
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
                                        <button wire:click.prevent="delete({{$event->id}})"
                                                class="btn btn-outline-danger btn-sm">{{__('Delete')}}</button>
                                        <button wire:click.prevent="duplicate({{$event->id}})"
                                                class="btn btn-outline-warning btn-sm">{{__('Duplicate')}}</button>
                                    </p>
                                @endif
                                <a href="{{ route('event_show', ['locale' => app()->getLocale(), 'id' => $event->id]) }}"
                                   class="btn btn-outline-info btn-sm float-end">
                                    {{ __('Details') }}
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
</div>
