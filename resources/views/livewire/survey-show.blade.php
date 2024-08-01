<div>
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Survey Show') }}
        @endslot
    @endcomponent
    <div class="row card">
        <div class="card-header  border-info">
            <div class="d-flex align-items-center">
                <h6 class="card-title mb-0 flex-grow-1">   {{__('Surveys')}}</h6>
            </div>
        </div>
        <div class="card-body row">
            <div class="card p-3 border border-dashed border-start-0">
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
                            class="badge badge-{{ $survey->showAchievement ? 'badge-success-subtle text-success' : 'badge-danger-subtle text-danger'  }}">
                            {{__('showAchievement')}}
                        </span>
                    </h6>
                    <p class="card-text text-muted">{{$survey->description}}</p>
                    <small class="text-muted">{{__('Creation date')}} : {{$survey->created_at}}</small>
                    <small class="text-muted">{{__('Update date')}} : {{$survey->updated_at}}</small>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="{{route('survey_participate', ['locale'=> request()->route("locale"),'idServey'=>$survey->id] )}}"
                       class="btn btn-soft-info material-shadow-none">{{__('Paticipate ')}}</a>
                    <a href="{{route('survey_results', ['locale'=> request()->route("locale"),'idServey'=>$survey->id] )}}"
                       class="btn btn-soft-info material-shadow-none">{{__('Show results')}}</a>
                </div>
            </div>
        </div>
    </div>
</div>
