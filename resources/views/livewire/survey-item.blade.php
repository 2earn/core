<div class="card mb-2 ml-4 border border-dashed ">
    <div class="card-header border-info fw-medium text-muted mb-0">
        {{$survey->id}} - {{$survey->name}}
    </div>
    <div class="card-body">
        <h6 class="card-subtitle mb-2 text-muted">
                            <span
                                class="badge {{ $survey->enabled ? 'badge-success-subtle text-success' : 'badge-danger-subtle text-danger'  }}">
                            {{__('Enabled')}}
                        </span>
            <span
                class="badge badge-{{ $survey->archived ? 'badge-success-subtle text-success' : 'badge-danger-subtle text-danger'  }}">
                            {{__('Archived')}}
                        </span>
            <span
                class="badge badge-{{ $survey->updatable ? 'badge-success-subtle text-success' : 'badge-danger-subtle text-danger'  }}">
                            {{__('Updatable')}}
                        </span>
            <span
                class="badge badge-{{ $survey->showResult ? 'badge-success-subtle text-success' : 'badge-danger-subtle text-danger'  }}">
                            {{__('Shows result')}}
                        </span>
            <span
                class="badge badge-{{ $survey->showAchievement ? 'badge-success-subtle text-success' : 'badge-danger-subtle text-danger'  }}">
                            {{__('Show achievement')}}
                        </span>
        </h6>
        <p class="card-text text-muted">{{$survey->description}}</p>
        <small class="text-muted">{{__('Creation date')}} : {{$survey->created_at}}</small>
        <small class="text-muted">{{__('Update date')}} : {{$survey->updated_at}}</small>
    </div>
    <div class="card-footer bg-transparent">
        <a href="{{route('survey_show', ['locale'=> request()->route("locale"),'idServey'=>$survey->id] )}}"
           class="btn btn-soft-info material-shadow-none">{{__('Details')}}</a>
        <a href="{{route('survey_create_update', ['locale'=> request()->route("locale"),'idServey'=>$survey->id] )}}"
           class="btn btn-soft-info material-shadow-none">{{__('Edit')}}</a>
        <a href="{{route('survey_show', ['locale'=> request()->route("locale"),'idServey'=>$survey->id] )}}"
           class="btn btn-soft-info material-shadow-none">{{__('Delete')}}</a>
        <a href="{{route('survey_participate', ['locale'=> request()->route("locale"),'idServey'=>$survey->id] )}}"
           class="btn btn-soft-info material-shadow-none">{{__('Paticipate ')}}</a>
        <a href="{{route('survey_results', ['locale'=> request()->route("locale"),'idServey'=>$survey->id] )}}"
           class="btn btn-soft-info material-shadow-none">{{__('Show results')}}</a>
    </div>
</div>
