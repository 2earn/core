<div class="container">
    @section('title')
        {{ __('Communication Board') }}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Communication Board') }}
        @endslot
    @endcomponent

    <section id="communication" class="p-1">
        @foreach($communicationBoard as $communicationBoardItem)
            @if ($communicationBoardItem['value'] instanceof \App\Models\Survey)
                @include('livewire.survey-item', ['survey' => $communicationBoardItem['value']])
            @elseif ($communicationBoardItem['value'] instanceof \App\Models\News)
                @include('livewire.news-item', ['news' => $communicationBoardItem['value']])
            @elseif ($communicationBoardItem['value'] instanceof \App\Models\Event)
                @include('livewire.event-item', ['event' => $communicationBoardItem['value']])
            @endif
        @endforeach
        @vite('resources/js/surveys.js')
    </section>
</div>

