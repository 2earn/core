<div class="{{getContainerType()}}">
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

    <div class="row">
        <div class="col-12 card shadow-sm">
            <div class="card-header bg-white py-3">
                <div class="row align-items-center g-3">
                    <div class="col-12 col-md-6 col-lg-4">
                        <a href="{{route('target_create_update', app()->getLocale())}}"
                           class="btn btn-primary">
                            <i class="fa fa-plus me-2"></i>{{__('Create new target')}}
                        </a>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 ms-auto">
                        <form>
                            <label for="simple-search" class="visually-hidden">{{__('Search')}}</label>
                            <div class="input-group">
                                    <span class="input-group-text bg-white">
                                        <i class="fa fa-search text-muted"></i>
                                    </span>
                                <input wire:model.live="search"
                                       type="text"
                                       id="simple-search"
                                       class="form-control border-start-0"
                                       placeholder="{{__('Search target')}}">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @forelse($targets as $target)
            @include('livewire.target-item', ['target' => $target])
        @empty
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="fa fa-inbox fa-3x text-muted"></i>
                        </div>
                        <h5 class="text-muted">{{__('No Targets')}}</h5>
                        <p class="text-muted mb-3">{{__('Get started by creating a new target')}}</p>
                        <a href="{{route('target_create_update', app()->getLocale())}}"
                           class="btn btn-primary">
                            <i class="fa fa-plus me-2"></i>{{__('Create new target')}}
                        </a>
                    </div>
                </div>
            </div>
        @endforelse

        @if($targets->hasPages())
            <div class="card shadow-sm">
                <div class="card-body bg-white py-3">
                    {{ $targets->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
