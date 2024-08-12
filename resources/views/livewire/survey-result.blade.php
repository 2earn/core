<div>
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Survey Results') }}
        @endslot
    @endcomponent
    <div class="row card">
        <div class="card-header border-info">
            <div class="d-flex align-items-center">
                <h6 class="card-title mb-0 flex-grow-1">   {{__('Surveys')}}</h6>
            </div>
        </div>
        <div class="card-body row">
            @include('livewire.survey-item', ['survey' => $survey])
            <div class="card">
                <div class="card-header border-info fw-medium text-muted mb-0">
                    {{ __('Results') }}
                </div>
                <div class="card-body row">
                    <h6 class="card-title mb-0 flex-grow-1 text-info">   {{__('Participation')}}</h6>
                    <table class="table table-bordered mt-2 pl-2">
                        <thead>
                        <tr>
                            <th scope="col">{{__('Participation number')}}</th>
                            <th scope="col">{{__('Limits')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                {{$survey->surveyResponse->count()}}
                            </td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                    <h6 class="card-title mb-0 flex-grow-1 text-info">
                        {{__('Participation details')}}</h6>
                    <ul class="list-group list-group-flush pl-2">
                        @foreach ($survey->surveyResponse as $surveyResponse)
                            <li class="list-group-item">
                                {{ getUserDisplayedName($surveyResponse->user->idUser)}} <span
                                    class="text-muted">{{__('at')}}: {{ $surveyResponse->created_at}} </span>
                            </li>
                        @endforeach
                    </ul>
                    <h6 class="card-title mb-0 flex-grow-1 text-info">   {{__('Participation responce details')}}</h6>

                </div>
            </div>
        </div>
    </div>
</div>
