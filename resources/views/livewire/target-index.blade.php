<div>
    @if($currentRouteName=="target_index")
        @component('components.breadcrumb')
            @slot('title')
                {{ __('Targets') }}
            @endslot
        @endcomponent
        <div class="row">
            @include('layouts.flash-messages')
        </div>
    @endif
    <div class="row card">
        <div class="card-header border-info">
                <div class="row">
                    <div class="col-sm-12 col-md-3  col-lg-6  mx-2">
                        <a href="{{route('target_create_update', app()->getLocale())}}" class="btn btn-info add-btn"
                           id="create-btn">
                            <i class="ri-add-line align-bottom me-1 ml-2"></i>
                            {{__('Create new target')}}
                        </a>
                    </div>
                    <div class="float-end mt-1 col-sm-12 col-md-6  col-lg-5  mx-2">
                        <form class="items-center">
                            <label for="simple-search" class="sr-only">{{__('Search')}}</label>
                            <div class="w-full">
                                <input wire:model.live="search" type="text" id="simple-search"
                                       class="form-control"
                                       placeholder="{{__('Search target')}}">
                            </div>
                        </form>
                    </div>
                </div>
        </div>
        <div class="card-body row ml-1">
            @forelse($targets as $target)
                @include('livewire.target-item', ['target' => $target])
            @empty
                <p>{{__('No Targets')}}</p>
            @endforelse
            {{ $targets->links() }}
        </div>
    </div>

</div>
