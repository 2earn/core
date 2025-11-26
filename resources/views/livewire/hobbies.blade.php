<div class="{{getContainerType()}}">
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Hobbies') }}
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-12 card">
            <div class="card-body">
                @livewire('page-timer', ['deadline'=>'hobbies_cs'])
            </div>
        </div>
    </div>
</div>
