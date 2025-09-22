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
                                    {{$event->id}} - {{$event->title}}
                                </h5>
                                @if($event->enabled)
                                    <span class="badge bg-success float-end">{{__('Enabled')}}</span>
                                @else
                                    <span class="badge bg-danger float-end">{{__('Disabled')}}</span>
                                @endif
                                @if(\App\Models\User::isSuperAdmin())
                                    <p class="mx-2 float-end">
                                        <a href="{{ route('event_create_update', ['locale' => app()->getLocale(), 'id' => $event->id]) }}" class="btn btn-primary btn-sm">{{__('Edit')}}</a>
                                        <button wire:click.prevent="delete({{$event->id}})" class="btn btn-danger btn-sm">{{__('Delete')}}</button>
                                    </p>
                                @endif
                            </div>
                            <div class="card-body">
                                <div><strong>{{__('Content')}}:</strong> {{$event->content}}</div>
                                <div class="float-end"><strong>{{__('Published at')}}:</strong> {{$event->published_at}}</div>
                                <div><strong>{{__('Start at')}}:</strong> {{$event->start_at}} / <strong>{{__('End at')}}:</strong> {{$event->end_at}}</div>
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
