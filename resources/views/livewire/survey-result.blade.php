<div @if($showDetail) class="container-fluid" @endif>
    @if($showDetail)
        @component('components.breadcrumb')
            @slot('title')
                {{__('Survey')}} > {{$survey->id}}
                - {{\App\Models\TranslaleModel::getTranslation($survey,'name',$survey->name)}} > {{__('Results')}}
            @endslot
        @endcomponent
    @endif
    @if($showDetail)
        @include('livewire.survey-item', ['survey' => $survey])
    @endif
    <div class="card-body row">
        <div class="card">
            @if($survey->canShowResult() )
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
                @if($survey->show_results_as_number || $survey->show_results_as_percentage)
                    <div class="card-header border-muted fw-medium text-muted mb-0">
                        <h6 class="card-title mb-0 text-info ">   {{__('Participation response choices details')}}</h6>
                    </div>
                    <div class="card-body row">
                        <table class="table table-bordered mt-2 pl-2">
                            <thead>
                            <tr>
                                <th scope="col">{{__('#')}}</th>
                                <th scope="col">{{__('title')}}</th>
                                <th scope="col">{{__('Chosen')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!empty($stats))
                                @foreach ($stats as $statsItem)
                                    <tr>
                                        <td>
                                            {{$loop->index+1}}
                                        </td>
                                        <td>
                                            {{$statsItem['title']}}
                                        </td>
                                        <td>
                                            @if($survey->show_results_as_number)
                                                {{$statsItem['choosenK']}} {{__('times')}}
                                            @endif
                                            @if($survey->show_results_as_percentage)
                                                - {{formatSolde($statsItem['persontageK'],2)}}%
                                            @endif

                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            <tr>
                                <td colspan="2"><h5
                                        class="float-end">{{__('Total')}} {{__('participations / participants')}}</h5>
                                </td>
                                <td>
                                    @if(isset($participation)&&$participation>0)
                                        @if($survey->show_results_as_number)
                                            {{ $totalChoosen}} / {{$participation}} {{__('times')}} -
                                        @endif
                                        @if($survey->show_results_as_percentage)
                                            {{ formatSolde(($totalChoosen /$participation)*100,2)}} %
                                            {{__('soit')}} {{ formatSolde($totalChoosen /$participation,2)}} {{__('choix par participant')}}
                                        @endif
                                    @else
                                        <div class="alert alert-warning material-shadow" role="alert">
                                            {{__('No participation')}}
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                @endif
            @endif
            @if(!$survey->canShowResult() )
                @if($currentRouteName=="surveys_show")
                    <div class="card-header border-muted fw-medium text-muted mb-0">
                        <h6 class="card-title mb-0 flex-grow-1 text-info">
                            {{__('Participation details')}}</h6>
                    </div>
                    <div class="card-body row">
                        <ul class="list-group list-group-flush pl-2">
                            @php
                                $total = $survey->surveyResponse->count();
                            @endphp
                            @forelse($survey->surveyResponse->sortByDesc('created_at') as $key => $surveyResponse)
                                <li class="list-group-item">

                                    <a class="fw-medium link-primary"> {{ $total - $loop->index }}</a>
                                    - {{ getUserDisplayedName($surveyResponse->user->idUser)}} <span
                                        class="text-muted">{{__('at')}}: {{ $surveyResponse->created_at}} </span>
                                </li>
                            @empty
                                <li class="list-group-item">
                                    <div class="alert alert-warning material-shadow" role="alert">
                                        {{__('No participation')}}
                                    </div>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                @endif
            @endif
        </div>
    </div>
    @vite('resources/js/surveys.js')
    @if($survey->status==\Core\Enum\StatusSurvey::OPEN->value)
        @vite('resources/js/surveys.js')
    @endif
</div>
