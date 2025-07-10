<div class="row">
    @foreach($communicationBoard as $communicationBoardItem)
        @if ($communicationBoardItem['value'] instanceof \App\Models\Survey)
            @include('livewire.survey-item', ['survey' => $communicationBoardItem['value']])
        @elseif ($communicationBoardItem['value'] instanceof \App\Models\News)
            @include('livewire.news-item', ['news' => $communicationBoardItem['value']])
        @endif
    @endforeach
</div>
