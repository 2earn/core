<div>
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Survey Results') }}
        @endslot
    @endcomponent
    <div class="row card">
        <div class="card-header border-info">
            <div class="d-flex align-items-center">
                <h6 class="card-title mb-0">   {{__('Surveys')}}</h6>
            </div>
        </div>
        <div class="card-body row">
            @include('livewire.survey-item', ['survey' => $survey])
            <div class="card">
                <div class="card-header border-info fw-medium text-muted mb-0">
                    <h5 class="card-title mb-0 text-info ">      {{ __('Results') }}</h5>
                </div>
                <div class="card-header border-muted fw-medium text-muted mb-0">
                    <h6 class="card-title mb-0 text-info">   {{__('Participation')}}</h6>
                </div>
                <div class="card-body row">
                    <table class="table table-bordered mt-2 pl-2">
                        <thead>
                        <tr>
                            <th scope="col">{{__('Participation number')}}</th>
                            <th scope="col">{{__('Goals')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                {{$survey->surveyResponse->count()}}
                            </td>
                            <td>
                                @if($survey->goals)
                                    {{$survey->goals}}
                                @else
                                    <span class="text-muted">{{__('No gools')}}</span>
                                @endif
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-header border-muted fw-medium text-muted mb-0">
                    <h6 class="card-title mb-0 text-info ">   {{__('Participation response choices details')}}</h6>
                </div>
                <div class="card-body row">
                    <table class="table table-bordered mt-2 pl-2">
                        <thead>
                        <tr>
                            <th scope="col">{{__('#')}}</th>
                            <th scope="col">{{__('Title')}}</th>
                            <th scope="col">{{__('Choosen')}}</th>
                            <th scope="col">{{__('Persontage')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($stats as $statsItem)
                            <tr>
                                <td>
                                    {{$loop->index+1}}
                                </td>
                                <td>
                                    {{$statsItem['title']}}
                                </td>
                                <td>
                                    {{$statsItem['choosen']}} {{__('times')}}
                                </td>
                                <td>
                                    {{$statsItem['persontage']}}%
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
                <div class="card-header border-muted fw-medium text-muted mb-0">
                    <h6 class="card-title mb-0 flex-grow-1 text-info">
                        {{__('Participation details')}}</h6></div>
                <div class="card-body row">
                    <ul class="list-group list-group-flush pl-2">
                        @forelse($survey->surveyResponse as $surveyResponse)
                            <li class="list-group-item">
                                {{ getUserDisplayedName($surveyResponse->user->idUser)}} <span
                                    class="text-muted">{{__('at')}}: {{ $surveyResponse->created_at}} </span>
                            </li>
                        @empty
                            <li class="list-group-item">
                                {{__('No responces')}}
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @vite('resources/js/surveys.js')
</div>
