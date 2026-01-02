<div class="row">
    <div class="col-12 card  survey-item">
        <div class="card-header mt-2 ">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center flex-wrap gap-2">
                    @if(in_array($currentRouteName,["surveys_show","surveys_participate","surveys_results"]))
                        <a href="{{route('home', app()->getLocale())}}"
                           class="btn btn-outline-primary btn-sm"
                           title="{{__('To Home')}}">
                            <i class="ri-home-gear-line me-1"></i> {{__('Home')}}
                        </a>
                    @endif
                    <h4 class="m-2">
                        @if(\App\Models\User::isSuperAdmin())
                            {{$survey->id}} -
                        @endif
                        {{\App\Models\TranslaleModel::getTranslation($survey,'name',$survey->name)}}
                    </h4>
                </div>
            </div>
            @if(\App\Models\User::isSuperAdmin())
                <div class="mt-2">
                    <small class="text-muted">
                        <a class="link-info text-decoration-none"
                           href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($survey,'name')])}}">
                            <i class="ri-translate-2 align-bottom me-1"></i>{{__('Update Translation')}}
                        </a>
                    </small>
                </div>
            @endif
        </div>
        @if(\App\Models\User::isSuperAdmin() && in_array($currentRouteName,["surveys_show"]))
            <div class="card-body ">
                <h6 class="text-info mb-3"><i class="ri-settings-3-line me-2"></i>{{__('Admin Settings')}}</h6>
                <div class="row g-3">
                    <div class="col-sm-12 col-md-6 col-lg-3">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body p-3">
                                <h6 class="card-subtitle mb-3 text-muted">{{__('Status Controls')}}</h6>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span><i class="ri-toggle-line me-2"></i>{{__('Enabled')}}</span>
                                        <span
                                            class="badge {{ $survey->enabled ? 'bg-success' : 'bg-danger' }} px-2 py-1">
                                        {{__($survey->enabled ? 'True' : 'False')}}
                                    </span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span><i class="ri-broadcast-line me-2"></i>{{__('Published')}}</span>
                                        <span
                                            class="badge {{ $survey->published ? 'bg-success' : 'bg-danger' }} px-2 py-1">
                                        {{__($survey->published ? 'True' : 'False')}}
                                    </span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span><i class="ri-edit-box-line me-2"></i>{{__('Updatable')}}</span>
                                        <span
                                            class="badge {{ $survey->updatable ? 'bg-success' : 'bg-danger' }} px-2 py-1">
                                        {{__($survey->updatable ? 'True' : 'False')}}
                                    </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-6 col-lg-3">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body p-3">
                                <h6 class="card-subtitle mb-3 text-muted">{{__('Display Settings')}}</h6>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span><i class="ri-eye-line me-2"></i>{{__('Shows')}}</span>
                                        <span
                                            class="badge bg-info px-2 py-1">{{__(\App\Enums\TargetType::tryFrom($survey->show)?->name)}}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span><i class="ri-hashtag me-2"></i>{{__('Show results as number')}}</span>
                                        <span
                                            class="badge {{ $survey->show_results_as_number ? 'bg-success' : 'bg-danger' }} px-2 py-1">
                                        {{__($survey->show_results_as_number ? 'True' : 'False')}}
                                    </span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span><i
                                            class="ri-percent-line me-2"></i>{{__('Show results as percentage')}}</span>
                                        <span
                                            class="badge {{ $survey->show_results_as_percentage ? 'bg-success' : 'bg-danger' }} px-2 py-1">
                                        {{__($survey->show_results_as_percentage ? 'True' : 'False')}}
                                    </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-6 col-lg-3">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body p-3">
                                <h6 class="card-subtitle mb-3 text-muted">{{__('Achievement Settings')}}</h6>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                            <span><i
                                                    class="ri-time-line me-2"></i>{{__('Show attchivement Chrono')}}</span>
                                        <span
                                            class="badge bg-info px-2 py-1">{{__(\App\Enums\TargetType::tryFrom($survey->showAttchivementChrono)?->name)}}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span><i class="ri-trophy-line me-2"></i>{{__('Show achievement %')}}</span>
                                        <span
                                            class="badge bg-info px-2 py-1">{{__(\App\Enums\TargetType::tryFrom($survey->showAttchivementGool)?->name)}}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                            <span><i
                                                    class="ri-archive-line me-2"></i>{{__('Show after archiving')}}</span>
                                        <span
                                            class="badge bg-info px-2 py-1">{{__(\App\Enums\TargetType::tryFrom($survey->showAfterArchiving)?->name)}}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-6 col-lg-3">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body p-3">
                                <h6 class="card-subtitle mb-3 text-muted">{{__('Interaction Settings')}}</h6>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span><i class="ri-file-chart-line me-2"></i>{{__('Shows result')}}</span>
                                        <span
                                            class="badge bg-info px-2 py-1">{{__(\App\Enums\TargetType::tryFrom($survey->showResult)?->name)}}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span><i class="ri-thumb-up-line me-2"></i>{{__('Likable')}}</span>
                                        <span
                                            class="badge bg-info px-2 py-1">{{__(\App\Enums\TargetType::tryFrom($survey->likable)?->name)}}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span><i class="ri-chat-3-line me-2"></i>{{__('Commentable')}}</span>
                                        <span
                                            class="badge bg-info px-2 py-1">{{__(\App\Enums\TargetType::tryFrom($survey->commentable)?->name)}}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="card-body border-top border-muted">
            <div class="row g-4">
                @if($survey->canShowAttchivementChrono())
                    <div class="col-sm-12 col-md-6 col-lg-6">

                        @if($survey->status==\App\Enums\StatusSurvey::OPEN->value)
                            <div class="survey-countdown connect-page" title="{{$survey->endDate}}">
                                <div class="survey-countdown-body">
                                    <div class="survey-cd survey-cd-{{$survey->id}}"
                                         id="survey-cd-{{$survey->id}}"
                                         data-start="{{$survey->startDate}}"
                                         data-end="{{$survey->endDate}}">
                                        <div class="counter timer">
                                            <h5 class="title text-info mb-3"><i
                                                    class="ri-time-line me-2"></i>{{__('time remaining')}}</h5>
                                            <div class="counter-boxes d-flex justify-content-around flex-wrap">
                                                <div class="count-box text-center">
                                                    <h3 class="value day text-primary">0</h3>
                                                    <span class="text-muted">{{__('Days')}}</span>
                                                </div>
                                                <div class="count-box text-center">
                                                    <h3 class="value hour text-primary">0</h3>
                                                    <span class="text-muted">{{__('Hours')}}</span>
                                                </div>
                                                <div class="count-box text-center">
                                                    <h3 class="value minute text-primary">0</h3>
                                                    <span class="text-muted">{{__('Minutes')}}</span>
                                                </div>
                                                <div class="count-box text-center">
                                                    <h3 class="value second text-primary">0</h3>
                                                    <span class="text-muted">{{__('Seconds')}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <h6 class="text-info mb-2"><i
                                    class="ri-time-line me-2"></i>{{__('Chrono Achievement')}}
                            </h6>
                            <h3 class="text-primary">{{$survey->getChronoAttchivement()}} %</h3>
                        @endif
                    </div>
                @endif

                <div class="col-sm-12 col-md-6 col-lg-{{ $survey->canShowAttchivementChrono() ? '6' : '12' }}">
                    <div class="row g-3 h-100">
                        @if($survey->canShowAttchivementGools())
                            <div class="col-sm-12 {{ $survey->goals ? 'col-md-6' : 'col-md-12' }}">
                                <div class="card border-0 h-100">
                                    <div class="card-body">
                                        <h6 class="text-info mb-2"><i
                                                class="ri-trophy-line me-2"></i>{{__('Attchivement Gools')}}</h6>
                                        <h3 class="text-success">{{ $survey->getGoolsAttchivement()}} %</h3>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($survey->goals != null && !empty($survey->goals))
                            <div
                                class="col-sm-12 {{ $survey->canShowAttchivementGools() ? 'col-md-6' : 'col-md-12' }}">
                                <div class="card border-0  h-100">
                                    <div class="card-body">
                                        <h6 class="text-info mb-2"><i class="ri-flag-line me-2"></i>{{__('Goals')}}
                                        </h6>
                                        <p class="mb-0">{{$survey->goals}}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-12">
                    <div class="card border-0 ">
                        <div class="card-body">
                                                 <span class="badge fs-6 px-3 py-2 float-end
                {{ $survey->status==\App\Enums\StatusSurvey::NEW->value ? 'bg-primary' : ''  }}
                {{ $survey->status==\App\Enums\StatusSurvey::OPEN->value ? 'bg-success' : ''  }}
                {{ $survey->status==\App\Enums\StatusSurvey::CLOSED->value ? 'bg-warning' : ''  }}
                {{ $survey->status==\App\Enums\StatusSurvey::ARCHIVED->value ? 'bg-dark' : ''  }}">
                {{ __(\App\Enums\StatusSurvey::tryFrom($survey->status)->name)}}
            </span>
                        </div>
                        <div class="card-body">
                            <h6 class="text-info mb-3"><i class="ri-file-text-line me-2"></i>{{__('Description')}}
                            </h6>
                            <div class="text-muted">
                                @if($currentRouteName=="surveys_show")
                                    {!! \App\Models\TranslaleModel::getTranslation($survey,'description',$survey->description) !!}
                                @else
                                    {!! Str::limit(\App\Models\TranslaleModel::getTranslation($survey,'description',$survey->description),200) !!}
                                @endif
                            </div>
                            @if(\App\Models\User::isSuperAdmin())
                                <div class="mt-2">
                                    <a class="link-info text-decoration-none small"
                                       href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($survey,'description')])}}">
                                        <i class="ri-translate-2 align-bottom me-1"></i>{{__('Update Translation')}}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @if(\App\Models\User::isSuperAdmin() && in_array($currentRouteName,["surveys_show"]))
                    <div class="col-12">
                        <div class="card border-0 ">
                            <div class="card-body">
                                <h6 class="text-info mb-3"><i class="ri-focus-3-line me-2"></i>{{__('Target')}}</h6>
                                @if($survey->targets->isEmpty())
                                    <p class="text-muted mb-0">{{ __('No target') }}</p>
                                @else
                                    <div class="list-group list-group-flush">
                                        @foreach($survey->targets as $targetItem)
                                            <a href="{{route('target_show',['locale'=>app()->getLocale(),'idTarget'=> $targetItem->id])}}"
                                               class="list-group-item list-group-item-action d-flex align-items-center">
                                                <span class="badge bg-secondary me-2">{{ $targetItem->id }}</span>
                                                <span>{{ $targetItem->name}}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @if(\App\Models\User::isSuperAdmin() && in_array($currentRouteName,["surveys_show"]))
            @if(!is_null($survey->disabledResult) or !is_null($survey->disabledComment) or !is_null($survey->disabledLike) or !is_null($survey->disabledBtnDescription))
                <div class="card-body  border-top">
                    <h6 class="text-warning mb-3"><i
                            class="ri-error-warning-line me-2"></i>{{__('Disabled Messages')}}
                    </h6>
                    <div class="row g-3">
                        @if($survey->disabledBtnDescription != null && !$survey->enabled)
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <h6 class="text-info mb-2"><i
                                        class="ri-forbid-line me-2"></i>{{__('Disabled button description')}}
                                </h6>
                                <p class="card-text text-muted mb-2">
                                    @if($currentRouteName=="surveys_show")
                                        {{\App\Models\TranslaleModel::getTranslation($survey,'disabledBtnDescription',$survey->disabledBtnDescription)}}
                                    @else
                                        {{ Str::limit(\App\Models\TranslaleModel::getTranslation($survey,'disabledBtnDescription',$survey->disabledBtnDescription),200)}}
                                    @endif
                                </p>
                                <a class="link-info text-decoration-none small"
                                   href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($survey,'disabledBtnDescription')])}}">
                                    <i class="ri-translate-2 align-bottom me-1"></i>{{__('Update Translation')}}
                                </a>

                            </div>
                        @endif

                        @if($survey->disabledResult != null)
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <div class="card border-warning h-100">
                                    <div class="card-body">
                                        <h6 class="text-info mb-2"><i
                                                class="ri-file-chart-line me-2"></i>{{__('Disabled result description')}}
                                        </h6>
                                        <p class="card-text text-muted mb-2">
                                            @if($currentRouteName=="surveys_show")
                                                {{\App\Models\TranslaleModel::getTranslation($survey,'disabledResult',$survey->disabledResult)}}
                                            @else
                                                {{ Str::limit(\App\Models\TranslaleModel::getTranslation($survey,'disabledResult',$survey->disabledResult),200)}}
                                            @endif
                                        </p>
                                        <a class="link-info text-decoration-none small"
                                           href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($survey,'disabledResult')])}}">
                                            <i class="ri-translate-2 align-bottom me-1"></i>{{__('Update Translation')}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($survey->disabledComment != null)
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <div class="card border-warning h-100">
                                    <div class="card-body">
                                        <h6 class="text-info mb-2"><i
                                                class="ri-chat-off-line me-2"></i>{{__('Disabled comment description')}}
                                        </h6>
                                        <p class="card-text text-muted mb-2">
                                            @if($currentRouteName=="surveys_show")
                                                {{\App\Models\TranslaleModel::getTranslation($survey,'disabledComment',$survey->disabledComment)}}
                                            @else
                                                {{ Str::limit(\App\Models\TranslaleModel::getTranslation($survey,'disabledComment',$survey->disabledComment),200)}}
                                            @endif
                                        </p>
                                        <a class="link-info text-decoration-none small"
                                           href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($survey,'disabledComment')])}}">
                                            <i class="ri-translate-2 align-bottom me-1"></i>{{__('Update Translation')}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($survey->disabledLike != null)
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <div class="card border-warning h-100">
                                    <div class="card-body">
                                        <h6 class="text-info mb-2"><i
                                                class="ri-thumb-down-line me-2"></i>{{__('Disabled like description')}}
                                        </h6>
                                        <p class="card-text text-muted mb-2">
                                            @if($currentRouteName=="surveys_show")
                                                {{\App\Models\TranslaleModel::getTranslation($survey,'disabledLike',$survey->disabledLike)}}
                                            @else
                                                {{ Str::limit(\App\Models\TranslaleModel::getTranslation($survey,'disabledLike',$survey->disabledLike),200)}}
                                            @endif
                                        </p>
                                        <a class="link-info text-decoration-none small"
                                           href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($survey,'disabledLike')])}}">
                                            <i class="ri-translate-2 align-bottom me-1"></i>{{__('Update Translation')}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        @endif

        @if($currentRouteName=="surveys_show")
            @if(\App\Models\User::isSuperAdmin())
                <div class="card-body  border-top">
                    <div class="row g-3">
                        <div class="col-sm-12 col-md-6 col-lg-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-3 text-muted"><i
                                            class="ri-toggle-line me-2"></i>{{__('Enable/Disable Dates')}}</h6>
                                    <ul class="list-group list-group-flush">
                                        @if($survey->enableDate != null && !empty($survey->enableDate))
                                            @if($survey->enabled)
                                                <li class="list-group-item d-flex flex-column px-0">
                                                    <small class="text-muted mb-1">{{__('Enable date')}}</small>
                                                    <strong
                                                        class="text-success">{{\Carbon\Carbon::parse($survey->enableDate)->format(\App\Livewire\SurveyCreateUpdate::DATE_FORMAT)}}</strong>
                                                </li>
                                            @endif
                                        @else
                                            @if($survey->disableDate != null && !empty($survey->disableDate))
                                                <li class="list-group-item d-flex flex-column px-0">
                                                    <small class="text-muted mb-1">{{__('Disable date')}}</small>
                                                    <strong
                                                        class="text-danger">{{\Carbon\Carbon::parse($survey->disableDate)->format(\App\Livewire\SurveyCreateUpdate::DATE_FORMAT)}}</strong>
                                                </li>
                                            @endif
                                        @endif

                                        @if($survey->published)
                                            @if($survey->publishDate != null && !empty($survey->publishDate))
                                                <li class="list-group-item d-flex flex-column px-0">
                                                    <small class="text-muted mb-1">{{__('Publish date')}}</small>
                                                    <strong
                                                        class="text-success">{{\Carbon\Carbon::parse($survey->publishDate)->format(\App\Livewire\SurveyCreateUpdate::DATE_FORMAT)}}</strong>
                                                </li>
                                            @endif
                                        @else
                                            @if($survey->unpublishDate != null && !empty($survey->unpublishDate))
                                                <li class="list-group-item d-flex flex-column px-0">
                                                    <small class="text-muted mb-1">{{__('Un publish date')}}</small>
                                                    <strong
                                                        class="text-danger">{{\Carbon\Carbon::parse($survey->unpublishDate)->format(\App\Livewire\SurveyCreateUpdate::DATE_FORMAT)}}</strong>
                                                </li>
                                            @endif
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-3 text-muted"><i
                                            class="ri-door-line me-2"></i>{{__('Open/Close Dates')}}</h6>
                                    <ul class="list-group list-group-flush">
                                        @if($survey->openDate != null && !empty($survey->openDate))
                                            <li class="list-group-item d-flex flex-column px-0">
                                                <small class="text-muted mb-1">{{__('Open date')}}</small>
                                                <strong
                                                    class="text-success">{{\Carbon\Carbon::parse($survey->openDate)->format(\App\Livewire\SurveyCreateUpdate::DATE_FORMAT)}}</strong>
                                            </li>
                                        @endif

                                        @if($survey->closeDate != null && !empty($survey->closeDate))
                                            <li class="list-group-item d-flex flex-column px-0">
                                                <small class="text-muted mb-1">{{__('Close date')}}</small>
                                                <strong
                                                    class="text-warning">{{\Carbon\Carbon::parse($survey->closeDate)->format(\App\Livewire\SurveyCreateUpdate::DATE_FORMAT)}}</strong>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-3 text-muted"><i
                                            class="ri-calendar-line me-2"></i>{{__('Start/End Dates')}}</h6>
                                    <ul class="list-group list-group-flush">
                                        @if($survey->startDate != null && !empty($survey->startDate))
                                            <li class="list-group-item d-flex flex-column px-0">
                                                <small class="text-muted mb-1">{{__('Start date')}}</small>
                                                <strong
                                                    class="text-primary">{{\Carbon\Carbon::parse($survey->startDate)->format(\App\Livewire\SurveyCreateUpdate::DATE_FORMAT)}}</strong>
                                            </li>
                                        @endif

                                        @if($survey->endDate != null && !empty($survey->endDate))
                                            <li class="list-group-item d-flex flex-column px-0">
                                                <small class="text-muted mb-1">{{__('End date')}}</small>
                                                <strong
                                                    class="text-primary">{{\Carbon\Carbon::parse($survey->endDate)->format(\App\Livewire\SurveyCreateUpdate::DATE_FORMAT)}}</strong>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-3 text-muted"><i
                                            class="ri-history-line me-2"></i>{{__('System Dates')}}</h6>
                                    <ul class="list-group list-group-flush">
                                        @if($survey->archivedDate != null && !empty($survey->archivedDate))
                                            <li class="list-group-item d-flex flex-column px-0">
                                                <small class="text-muted mb-1">{{__('Archived date')}}</small>
                                                <strong
                                                    class="text-dark">{{\Carbon\Carbon::parse($survey->archivedDate)->format(\App\Livewire\SurveyCreateUpdate::DATE_FORMAT)}}</strong>
                                            </li>
                                        @endif

                                        @if($survey->created_at != null && !empty($survey->created_at))
                                            <li class="list-group-item d-flex flex-column px-0">
                                                <small class="text-muted mb-1">{{__('Creation date')}}</small>
                                                <strong>{{\Carbon\Carbon::parse($survey->created_at)->format(\App\Livewire\SurveyCreateUpdate::DATE_FORMAT)}}</strong>
                                            </li>
                                        @endif

                                        @if($survey->updated_at != null && !empty($survey->updated_at))
                                            <li class="list-group-item d-flex flex-column px-0">
                                                <small class="text-muted mb-1">{{__('Update date')}}</small>
                                                <strong>{{\Carbon\Carbon::parse($survey->updated_at)->format(\App\Livewire\SurveyCreateUpdate::DATE_FORMAT)}}</strong>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endif

        @if(!in_array( $currentRouteName,['surveys_results','surveys_participate']))
            <div class="card-footer  border-top">
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    @if($currentRouteName!="survey_show")
                        <a href="{{route('surveys_show', ['locale'=> app()->getLocale(),'idSurvey'=>$survey->id] )}}"
                           class="btn btn-outline-info float-end">
                            <i class="ri-eye-line me-1"></i>{{__('Details')}}
                        </a>
                    @endif

                    @if(\App\Models\User::isSuperAdmin() && $survey->status != \App\Enums\StatusSurvey::ARCHIVED->value &&$currentRouteName=="surveys_show")
                        <button type="button" class="btn btn-outline-primary"
                                wire:click="duplicateSurvey({{$survey->id}})">
                            <i class="ri-file-copy-line me-1"></i>{{__('Duplicate')}}
                        </button>

                        @if(intval($survey->status)==\App\Enums\StatusSurvey::NEW->value)
                            <a href="{{route('surveys_create_update', ['locale'=> app()->getLocale(),'idSurvey'=>$survey->id] )}}"
                               class="btn btn-outline-warning">
                                <i class="ri-edit-line me-1"></i>{{__('Edit')}}
                            </a>
                        @endif

                        @if($survey->status==\App\Enums\StatusSurvey::NEW->value)
                            <a wire:click="open('{{$survey->id}}')"
                               class="btn btn-outline-success">
                                <i class="ri-door-open-line me-1"></i>{{__('Open')}}
                            </a>
                        @endif

                        @if($survey->status==\App\Enums\StatusSurvey::OPEN->value)
                            <a wire:click="close('{{$survey->id}}')"
                               class="btn btn-outline-warning">
                                <i class="ri-door-close-line me-1"></i>{{__('Close')}}
                            </a>
                        @endif

                        @if($survey->status==\App\Enums\StatusSurvey::CLOSED->value)
                            <a wire:click="archive('{{$survey->id}}')"
                               class="btn btn-outline-secondary">
                                <i class="ri-archive-line me-1"></i>{{__('Send to archive')}}
                            </a>
                        @endif

                        @if($survey->status<\App\Enums\StatusSurvey::CLOSED->value)
                            @if(!$survey->enabled)
                                <a wire:click="enable('{{$survey->id}}')"
                                   class="btn btn-outline-success">
                                    <i class="ri-toggle-line me-1"></i>{{__('Enable')}}
                                </a>
                            @else
                                <button type="button" id="disableSurveyModalbtn_{{$survey->id}}"
                                        class="btn btn-outline-danger" data-bs-toggle="modal"
                                        data-bs-target="#disableSurveyModal_{{$survey->id}}">
                                    <i class="ri-close-circle-line me-1"></i>{{__('Disable')}}
                                </button>
                            @endif

                            @if(!$survey->published)
                                <a wire:click="publish('{{$survey->id}}')"
                                   class="btn btn-outline-success">
                                    <i class="ri-broadcast-line me-1"></i>{{__('Publish')}}
                                </a>
                            @else
                                <a wire:click="unpublish('{{$survey->id}}')"
                                   class="btn btn-outline-danger">
                                    <i class="ri-close-circle-line me-1"></i>{{__('Un Publish')}}
                                </a>
                            @endif

                            @if(!$survey->updatable)
                                <a wire:click="changeUpdatable('{{$survey->id}}')"
                                   class="btn btn-outline-success">
                                    <i class="ri-edit-box-line me-1"></i>{{__('Make it updatable')}}
                                </a>
                            @else
                                <a wire:click="changeUpdatable('{{$survey->id}}')"
                                   class="btn btn-outline-danger">
                                    <i class="ri-lock-line me-1"></i>{{__('Make it not updatable')}}
                                </a>
                            @endif
                        @endif
                    @endif
                </div>

                @if(!$survey->canShowResult()&&$currentRouteName=="surveys_show")
                    <div class="alert alert-info mt-3 mb-0" role="alert">
                        <div class="d-flex align-items-start">
                            <i class="ri-information-line fs-5 me-2"></i>
                            <div>
                                <h6 class="alert-heading mb-1">{{__('Disabled result title')}}</h6>
                                <p class="mb-0">{{$survey->disabledResult}}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if($survey->canShowResult() && !$survey->enabled &&$currentRouteName=="surveys_show")
                    <div class="alert alert-warning mt-3 mb-0" role="alert">
                        <div class="d-flex align-items-start">
                            <i class="ri-error-warning-line fs-5 me-2"></i>
                            <div>
                                <h6 class="alert-heading mb-1">{{__('Disabled result title')}}</h6>
                                <p class="mb-0">{{__('Disabled')}}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif
        @if(intval($survey->status)!==\App\Enums\StatusSurvey::OPEN->value && !$survey->enabled)
            <div class="card-header">
                <h5 class="mb-0 text-info"><i class="ri-question-line me-2"></i>{{__('Questions')}}</h5>
            </div>
        @endif

        <div class="card-body">
            @if($survey->question)
                @if(intval($survey->status)!==\App\Enums\StatusSurvey::OPEN->value && !$survey->enabled)
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span
                            class="badge {{ $survey->question->selection== \App\Enums\Selection::MULTIPLE->value ? 'bg-success' : 'bg-primary' }} px-3 py-2">
                            <i class="ri-checkbox-multiple-line me-1"></i>
                            {{ $survey->question->selection== \App\Enums\Selection::MULTIPLE->value ? __('Multiple') : __('Unique') }}
                        </span>

                        @if($survey->question->selection== \App\Enums\Selection::MULTIPLE->value)
                            <span class="badge bg-info px-3 py-2">
                                <i class="ri-list-check me-1"></i>
                                {{__('Max')}} : {{$survey->question->maxResponse}}
                            </span>
                        @endif

                        @if(!empty($survey->question->disableNote))
                            <span class="badge bg-warning px-3 py-2">
                                <i class="ri-error-warning-line me-1"></i>
                                {{__('Disable Note')}} : {{$survey->question->disableNote}}
                            </span>
                        @endif
                    </div>
                    <div class="row g-3">
                        <div class="col-lg-12">
                            <div class="card border-0 ">
                                <div class="card-body">
                                    <h6 class="text-info mb-2"><i
                                            class="ri-text me-2"></i>{{__('Question Content')}}
                                    </h6>
                                    <blockquote class="blockquote mb-2">
                                        <p class="text-muted">{{\App\Models\TranslaleModel::getTranslation($survey->question,'content',$survey->question->content)}}</p>
                                    </blockquote>
                                    @if(\App\Models\User::isSuperAdmin())
                                        <a class="link-info text-decoration-none small"
                                           href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($survey->question,'content')])}}">
                                            <i class="ri-translate-2 align-bottom me-1"></i>{{__('Update Translation')}}
                                        </a>
                                    @endif
                                </div>
                            </div>

                            @if(\App\Models\User::isSuperAdmin() && intval($survey->status)==\App\Enums\StatusSurvey::NEW->value)
                                <div class="mt-3 d-flex gap-2">
                                    <a href="{{route('surveys_question_create_update', ['locale'=> app()->getLocale(),'idSurvey'=>$survey->id,'IdQuestion'=>$survey->question->id] )}}"
                                       class="btn btn-warning btn-sm">
                                        <i class="ri-edit-line me-1"></i>{{__('Edit')}}
                                    </a>
                                    <a href="{{route('surveys_question_choice_create_update', ['locale'=> app()->getLocale(),'idSurvey'=>$survey->id,'idQuestion'=>$survey->question->id] )}}"
                                       class="btn btn-success btn-sm">
                                        <i class="ri-add-line me-1"></i>{{__('Add Choice')}}
                                    </a>
                                </div>
                            @endif
                        </div>

                        <div class="col-lg-12">
                            <h6 class="text-info mb-3"><i class="ri-list-check-2 me-2"></i>{{__('Choices')}}</h6>
                            @forelse ($survey->question->serveyQuestionChoice as $choice)
                                <div class="card mb-2">
                                    <div class="card-body p-2">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-start">
                                                    <span class="me-2">{{$loop->index+1}} - </span>
                                                    <div>
                                                        <p class="mb-1 small">{{\App\Models\TranslaleModel::getTranslation($choice,'title',$choice->title)}}</p>
                                                        @if(\App\Models\User::isSuperAdmin())
                                                            <a class="link-info text-decoration-none small"
                                                               href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($choice,'title')])}}">
                                                                <i class="ri-translate"></i>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            @if(\App\Models\User::isSuperAdmin() && intval($survey->status)==\App\Enums\StatusSurvey::NEW->value)
                                                <div class="btn-group btn-group-sm ms-2">
                                                    <a href="{{route('surveys_question_choice_create_update', ['locale'=> app()->getLocale(),'idSurvey'=>$survey->id,'idQuestion'=>$survey->question->id,'idChoice'=>$choice->id] )}}"
                                                       title="{{__('Update Choice')}}"
                                                       class="btn btn-outline-info btn-sm">
                                                        <i class="ri-edit-line"></i>
                                                    </a>
                                                    <a wire:click="removeChoice('{{$choice->id}}')"
                                                       title="{{__('Remove Choice')}}"
                                                       class="btn btn-outline-danger btn-sm">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="alert alert-warning mb-0">
                                    <i class="ri-error-warning-line me-2"></i>{{__('No Choices')}}
                                </div>
                            @endforelse

                            @if(!$survey->question)
                                <div class="mt-3">
                                    <a wire:click="removeQuestion('{{$question->id}}')"
                                       class="btn btn-danger btn-sm">
                                        <i class="ri-delete-bin-line me-1"></i>{{__('Remove')}}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            @else
                <div class="alert alert-info mb-0">
                    <i class="ri-information-line me-2"></i>{{__('No questions')}}.
                    @if(\App\Models\User::isSuperAdmin())
                        <a href="{{route('surveys_question_create_update', ['locale'=> app()->getLocale(),'idSurvey'=>$survey->id] )}}"
                           title="{{__('Add Question')}}" class="btn btn-success btn-sm ms-2">
                            <i class="ri-add-line me-1"></i>{{__('Add Question')}}
                        </a>
                    @endif
                </div>
            @endif

            @if($survey->openDate)
                <div class="mt-3 text-end">
                    <small class="text-muted">
                        <i class="ri-calendar-line me-1"></i>{{__('Opening date')}}: {{$survey->openDate}}
                    </small>
                </div>
            @endif
        </div>


        @if(intval($survey->status)==\App\Enums\StatusSurvey::OPEN->value && $survey->enabled)
            @livewire('survey-paricipate', ['idSurvey' => $survey->id])
        @endif
        @if($survey->status>\App\Enums\StatusSurvey::NEW->value )
            @if($survey->canShowResult() )
                <div class="card">
                    <div class="card-header fw-medium text-muted mb-0">
                        <h5 class="mt-2 text-info">{{__('Result')}} :</h5>
                    </div>
                    <div class="card-body">
                        @livewire('survey-result', ['idSurvey' => $survey->id])
                    </div>
                </div>
            @endif
        @endif

        @if($currentRouteName=="surveys_show")
            <div class="card">
                <div class="card-header fw-medium text-muted mb-0">
                    <h5 class="mt-2 text-info">{{__('Likes')}} : ({{ $survey->likes->count() }})</h5>
                </div>
                @if(!$survey->isLikable())
                    <div class="alert alert-info material-shadow mt-2" role="alert">
                        <h6 class="alert-heading">{{__('Disabled like title')}}</h6>
                        * {{\App\Models\TranslaleModel::getTranslation($survey,'disabledLike',$survey->disabledLike)}}
                    </div>
                @endif
                <div class="card-body row">
                    @if($like)
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <button wire:click="dislike()" class="btn btn-warning"
                                    @if(!$survey->isLikable()) disabled @endif>
                                <i class="ri-thumb-down-line align-bottom me-1"></i>
                                {{__('Un - Like')}}
                            </button>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="alert alert-success mb-0">
                                <i class="ri-check-line me-2"></i><strong>{{__('Liked')}}</strong>
                            </div>
                        </div>
                    @else
                        <div class="col-12">
                            <button wire:click="addLike()" class="btn btn-primary w-100"
                                    @if(!$survey->isLikable()) disabled @endif>
                                <i class="ri-thumb-up-line align-bottom me-1"></i>
                                {{__('Like')}}
                            </button>
                        </div>
                    @endif

                    @if($survey->isLikable())
                        <div class="col-12">
                            <h6 class="text-info mb-3"><i
                                    class="ri-user-heart-line me-2"></i>{{__('Users who liked')}}
                            </h6>
                            @php
                                $likeNumber = $survey->likes->count();
                            @endphp
                            @forelse ($survey->likes->sortByDesc('created_at') as $like)
                                <div class="card mb-2 border">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                    <span
                                                        class="badge bg-primary me-2">{{$likeNumber-$loop->index}}</span>
                                                <strong
                                                    class="text-dark">{{ getUserDisplayedName($like->user->idUser)}}</strong>
                                            </div>
                                            <small class="text-muted">
                                                <i class="ri-time-line me-1"></i>{{ $like->created_at}}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="alert alert-info mb-0">
                                    <i class="ri-information-line me-2"></i>{{__('No Likes')}}
                                </div>
                            @endforelse
                        </div>
                    @endif
                </div>
            </div>
        @endif

        @if($currentRouteName=="surveys_show")
            <div class="card mt-3 border shadow-sm">
                <div class="card-header  border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-info"><i class="ri-chat-3-line me-2"></i>{{__('Comments')}}</h5>
                        <span class="badge bg-info fs-6 px-3 py-2">{{ $survey->comments->count() }}</span>
                    </div>
                </div>

                @if(!$survey->isCommentable())
                    <div class="alert alert-warning m-3 mb-0">
                        <div class="d-flex align-items-start">
                            <i class="ri-error-warning-line fs-5 me-2"></i>
                            <div>
                                <h6 class="alert-heading mb-1">{{__('Disabled comment title')}}</h6>
                                * {{\App\Models\TranslaleModel::getTranslation($survey,'disabledComment',$survey->disabledComment)}}

                            </div>
                            @endif

                            <div class="card-body row">
                                @if($survey->isCommentable())
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <ul class="list-group mb-3">
                                            @forelse ($survey->comments as $comment)
                                                @if(\App\Models\User::isSuperAdmin() ||$comment->validated||$comment->user_id ==auth()->user()->id  )
                                                    <li class="list-group-item mt-2">

                                                        <blockquote class="blockquote ml-2 mt-2">
                                                                <span
                                                                    class=" text-muted mx-3">{{$comment->content }}</span>
                                                        </blockquote>

                                                        <span class="text-muted float-end"
                                                              title="{{$loop->index+1}}"><strong
                                                                class="text-muted">{{ getUserDisplayedName($comment->user->idUser)}}</strong> <strong>{{__('at')}}: </strong>  {{$comment->created_at}}</span>
                                                        @if(!$comment->validated)
                                                            <span
                                                                class="badge badge-soft-warning float-end mx-2">{{ __('Waiting for admin approving')}}</span>
                                                        @endif

                                                        @if(!$comment->validated && \App\Models\User::isSuperAdmin())
                                                            <br>
                                                            <button wire:click="deleteComment('{{$comment->id}}')"
                                                                    class="btn btn-soft-danger mt-3 mx-2 float-end">
                                                                {{__('Delete')}}
                                                            </button>
                                                            <button wire:click="validateComment('{{$comment->id}}')"
                                                                    class="btn btn-soft-success mt-3 mx-2 float-end">
                                                                {{__('Validate')}}
                                                            </button>
                                                        @endif
                                                    </li>
                                                @endif
                                            @empty
                                                <li class="list-group-item mt-2">
                                                    {{__('No Comments')}}:
                                                </li>
                                            @endforelse
                                        </ul>
                                    </div>
                                @endif
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <h6>{{__('Add a comment')}}</h6>
                                </div>
                                <div class="col-sm-12 col-md-9 col-lg-9">
                        <textarea class="form-control" maxlength="190" wire:model.live="comment" id="comment"
                                  rows="3"
                                  @if(!$survey->isCommentable()) disabled @endif></textarea>
                                </div>
                                <div class="col-sm-12 col-md-3 col-lg-3 ">
                                    <button wire:click="addComment()" class="btn btn-soft-info mt-2"
                                            @if(!$survey->isCommentable()) disabled @endif>
                                        {{__('Add comment')}}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div wire:ignore class="modal fade" id="disableSurveyModal_{{$survey->id}}" tabindex="-1"
                         aria-labelledby="disableSurveyModal_{{$survey->id}}Label"
                         aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">{{__('Disable Survey')}}</h5>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group mb-3">
                                        <label for="disableNote">{{__('Disable Note')}}</label>
                                        <textarea type="text"
                                                  class="form-control @error('disableNote') is-invalid @enderror"
                                                  maxlength="190" id="disableNote"
                                                  wire:model.live="disableNote"
                                                  placeholder="{{__('Enter Disable Note')}}"></textarea>
                                        @error('disableNote') <span
                                            class="text-danger">{{ $message }}</span>@enderror
                                        <div class="form-text">{{__('Required field')}}</div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" wire:click="disable('{{$survey->id}}')"
                                            class="btn btn-primary">{{__('Disable Survey')}}</button>
                                    <button type="button" class="btn btn-outline-secondary"
                                            data-bs-dismiss="modal">{{__('Close')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        @endif

        @if($currentRouteName!="surveys_show")
            <div class="card-footer border-top">
                <div class="d-flex gap-4 align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="ri-thumb-up-line text-primary fs-5 me-2"></i>
                        <span class="fw-medium">{{ $survey->likes()->count() ?? 0 }}</span>
                        <span class="text-muted ms-1">{{ __('Likes') }}</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="ri-chat-3-line text-info fs-5 me-2"></i>
                        <span
                            class="fw-medium">{{ $survey->comments()->where('validated',true)->count() ?? 0 }}</span>
                        <span class="text-muted ms-1">{{ __('Comments') }}</span>
                    </div>
                    @if(\App\Models\User::isSuperAdmin())
                        @if(in_array($currentRouteName,["surveys_show","surveys_participate","surveys_results"]))
                            <a href="{{route('surveys_index', app()->getLocale())}}"
                               class="btn btn-outline-info btn-sm"
                               title="{{__('To Surveys list')}}">
                                <i class="ri-bookmark-fill me-1"></i> {{__('Surveys')}}
                            </a>
                        @endif
                    @endif

                    <div class="d-flex gap-4 align-items-center">
                        <a href="{{route('surveys_show', ['locale'=> app()->getLocale(),'idSurvey'=>$survey->id] )}}"
                           class="btn btn-outline-secondary float-end btn-sm">
                            {{__('Details')}}
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

