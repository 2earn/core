<div>
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Hobbies') }}
        @endslot
    @endcomponent
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                @livewire('page-timer', ['deadline'=>'hobbies_cs'])
            </div>
        </div>
    </div>
</div>
