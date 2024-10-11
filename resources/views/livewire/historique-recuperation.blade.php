<div>
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Historique_recuperation') }}
        @endslot
    @endcomponent
    <div class="card">
        <div class="card-body">
            @livewire('page-timer', ['deadline' => 'historique_recup_cs'])
        </div>
    </div>
</div>
