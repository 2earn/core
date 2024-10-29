<div class="card mb-2 border">
    <div class="card-header border-info fw-medium text-muted mb-0">
        <h4> {{$target->id}} - {{$target->name}}</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-6">
                <h5 class="mt-2 text-info">{{__('Details')}}:</h5>
                <h6 class="mt-2 text-info">{{__('Description')}}:</h6>
                {{$target->description}}
                <h6 class="mt-2 text-info">{{__('Dates')}}:</h6>
                <ul class="list-group">
                    @if($target->created_at != null && !empty($target->created_at))
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong class="text-muted">{{__('Creation date')}} :</strong>
                            {{\Carbon\Carbon::parse($target->created_at)->format(\App\Http\Livewire\SurveyCreateUpdate::DATE_FORMAT)?? __('Not set')}}
                        </li>
                    @endif
                    @if($target->updated_at != null && !empty($target->updated_at))
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong class="text-muted">{{__('Update date')}} :</strong>
                            {{\Carbon\Carbon::parse($target->updated_at)->format(\App\Http\Livewire\SurveyCreateUpdate::DATE_FORMAT)?? __('Not set')}}
                        </li>
                    @endif
                </ul>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-6">
                <h5 class="mt-2 text-info">{{__('Links')}}:</h5>
                <h6 class="mt-2 text-info">{{__('Surveys')}}:</h6>
                @if($target->surveys->isEmpty())
                    <span class="text-muted"> {{ __('No Surveys') }}</span>
                @else
                    <ul class="list-group">
                        @foreach($target->surveys as $surveysItem)
                            <li class="list-group-item">
                                <a class="link-info"
                                   href="{{route('surveys_show',['locale'=>app()->getLocale(),'idSurvey'=> $surveysItem->id])}}">  {{ $surveysItem->id }}
                                    - {{\App\Models\TranslaleModel::getTranslation($surveysItem,'name',$surveysItem->name)}}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
        @if(strtoupper(auth()?->user()?->getRoleNames()->first())==\App\Models\Survey::SUPER_ADMIN_ROLE_NAME)
            <div class="card-body row">
                <div class="col-sm-12 col-md-5 col-lg-5">
                    <div class="btn-group mt-2" role="group">
                        @if($currentRouteName!=="target_show")
                            <a href="{{route('target_show',['locale'=>app()->getLocale(),'idTarget'=>$target->id])}}"
                               title="{{__('Show target')}}" class="btn btn-soft-info material-shadow-none">
                                {{__('Details')}}
                            </a>
                        @endif
                        <a href="{{route('target_create_update',['locale'=>app()->getLocale(),'idTarget'=>$target->id])}}"
                           title="{{__('Edit target')}}" class="btn btn-soft-info material-shadow-none">
                            {{__('Edit')}}
                        </a>
                        <a wire:click="deleteTarget('{{$target->id}}')"
                           title="{{__('Delete target')}}" class="btn btn-soft-danger material-shadow-none">
                            {{__('Delete')}}
                            <div wire:loading wire:target="deleteTarget('{{$target->id}}')">
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                      aria-hidden="true"></span>
                                <span class="sr-only">{{__('Loading')}}...</span>
                            </div>

                        </a>
                        <a href="{{route('surveys_create_update', ['locale'=> request()->route("locale"),'idTarget'=>$target->id] )}}"
                           title="{{__('Create matched target Survey')}}"
                           class="btn btn-soft-primary material-shadow-none">
                            {{__('Create Survey')}}
                        </a>
                    </div>
                </div>
                <div class="col-sm-12 col-md-5 col-lg-5">
                    <div class="btn-group mt-2" role="group">
                        @if($currentRouteName=="target_show")
                            <a href="{{route('target_condition_create_update',['locale'=>app()->getLocale(),'idTarget'=>$target->id])}}"
                               title="{{__('Add Condition')}}" class="btn btn-soft-info material-shadow-none">
                                {{__('Add Condition')}}
                            </a>
                            <a href="{{route('target_group_create_update',['locale'=>app()->getLocale(),'idTarget'=>$target->id])}}"
                               title="{{__('Add Group')}}" class="btn btn-soft-info material-shadow-none">
                                {{__('Add Group')}}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endif
        @if($currentRouteName=="target_show")
            @if($target->condition->isNotEmpty())
                <div class="card-header border-opacity-50 fw-medium text-muted mb-0">
                    <h5 class="text-info"> {{ __('Conditions details') }}:</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($target->condition as $condition)
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-sm-12 col-md-1 col-lg-1 text-muted">
                                        {{$loop->index + 1}} )
                                    </div>
                                    <div class="col-sm-12 col-md-5 col-lg-6 text-info">
                                        <h4>
                                    <span
                                            class="badge border  text-secondary  btn-lg">{{ $condition->operand }}</span>
                                            <span
                                                    class="badge border  text-danger-emphasis  text-danger btn-lg">{{ $condition->operator }}</span>
                                            <span
                                                    class="badge border text-secondary  btn-lg">{{ $condition->value }}</span>
                                        </h4>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-5 mt-2">
                                        <div class="btn-group" role="group">
                                            <a href="{{route('target_condition_create_update', ['locale'=> request()->route("locale"),'idTarget'=>$condition->target_id,'idCondition'=>$condition->id] )}}"
                                               title="{{__('Edit Condition')}}"
                                               class="btn btn-soft-info material-shadow-none">
                                                {{__('Edit')}}
                                            </a>
                                        </div>
                                        <div class="btn-group" role="group">
                                            <a wire:click="removeCondition('{{$condition->id}}','{{$target->id}}')"
                                               title="{{__('Remove Condition')}}"
                                               class="btn btn-soft-danger material-shadow-none">
                                                {{__('Remove')}}
                                                <div wire:loading
                                                     wire:target="removeCondition('{{$condition->id}}','{{$target->id}}')">
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                      aria-hidden="true"></span>
                                                    <span class="sr-only">{{__('Loading')}}...</span>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if($target->group->isNotEmpty())
                <div class="card-header border-opacity-50 fw-medium text-muted mb-0">
                    <h5 class="text-info"> {{ __('Groups details') }}</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($target->group as $group)
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-sm-12 col-md-1 col-lg-1 text-secondary">
                                        {{$loop->index + 1}} )
                                    </div>
                                    <div class="col-sm-12 col-md-5 col-lg-6 text-info mt-2">
                                        <h3><span class="text-danger">{{ $group->operator }}</span></h3>
                                    </div>

                                    @if($currentRouteName=="target_show")
                                        <div class="col-sm-12 col-md-6 col-lg-5">
                                            <div class="btn-group" role="group">
                                                <a href="{{route('target_group_create_update', ['locale'=> request()->route("locale"),'idTarget'=>$group->target_id,'idGroup'=>$group->id] )}}"
                                                   title="{{__('Edit Group')}}"
                                                   class="btn btn-soft-info material-shadow-none">
                                                    {{__('Edit')}}
                                                </a>
                                            </div>
                                            <div class="btn-group" role="group">
                                                <a wire:click="removeGroup('{{$group->id}}','{{$target->id}}')"
                                                   title="{{__('Remove group')}}"
                                                   class="btn btn-soft-danger material-shadow-none">
                                                    {{__('Remove')}}
                                                    <div wire:loading
                                                         wire:target="removeGroup('{{$group->id}}','{{$target->id}}')">
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                      aria-hidden="true"></span>
                                                        <span class="sr-only">{{__('Loading')}}...</span>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="btn-group" role="group">
                                                <a href="{{route('target_condition_create_update', ['locale'=> request()->route("locale"),'idTarget'=>$group->target_id,'idGroup'=>$group->id] )}}"
                                                   title="{{__('Add Condition')}}"
                                                   class="btn btn-soft-info material-shadow-none">
                                                    {{__('Add Condition')}}
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                    @if($group->condition->isNotEmpty())
                                        <div class="col-sm-12 col-md-12 col-lg-12">
                                            <h6 class="text-muted">{{ __('Conditions details') }}:</h6>
                                            <ul class="list-group">
                                                @foreach($group->condition as $conditionItem)
                                                    <li class="list-group-item">
                                                        <div class="row">
                                                            <div class="col-sm-12 col-md-1 col-lg-1 text-muted">
                                                                {{$loop->index + 1}} )
                                                            </div>
                                                            <div class="col-sm-12 col-md-5 col-lg-6 text-info">
                                                                <h4>
                                                            <span
                                                                    class="badge border text-secondary btn-lg">{{ $conditionItem->operand }}</span>
                                                                    <span
                                                                            class="badge border  text-danger-emphasis  text-danger btn-lg">{{ $conditionItem->operator }}</span>
                                                                    <span
                                                                            class="badge border text-secondary  btn-lg">{{ $conditionItem->value }}</span>
                                                                </h4>
                                                            </div>
                                                            @if($currentRouteName=="target_show")
                                                                <div class="col-sm-12 col-md-6 col-lg-5 mt-2">
                                                                    <div class="btn-group" role="group"
                                                                    >
                                                                        <a href="{{route('target_condition_create_update', ['locale'=> request()->route("locale"),'idTarget'=>$group->target_id,'idGroup'=>$conditionItem->target_group_id,'idCondition'=>$conditionItem->id] )}}"
                                                                           title="{{__('Edit Condition')}}"
                                                                           class="btn btn-soft-info material-shadow-none">
                                                                            {{__('Edit')}}
                                                                        </a>
                                                                    </div>
                                                                    <div class="btn-group" role="group"
                                                                    >
                                                                        <a wire:click="removeCondition('{{$conditionItem->id}}','{{$target->id}}')"
                                                                           title="{{__('Remove Condition')}}"
                                                                           class="btn btn-soft-danger material-shadow-none">
                                                                            {{__('Remove')}}
                                                                            <div wire:loading
                                                                                 wire:target="removeCondition('{{$conditionItem->id}}','{{$target->id}}')">
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                      aria-hidden="true"></span>
                                                                                <span
                                                                                        class="sr-only">{{__('Loading')}}...</span>
                                                                            </div>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        @endif
    </div>
</div>
