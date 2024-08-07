<div>
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Target Show') }} : {{ $target->id }} - {{ $target->name }}
        @endslot
    @endcomponent
    <div class="row">
        @include('layouts.flash-messages')
    </div>
    <div class="row">
        @include('livewire.target-item', ['target' => $target])
    </div>
</div>
