<div>
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Surveys') }}
        @endslot
    @endcomponent
    <div class="row card">
        <div class="card-header border-info">
            <div class="d-flex align-items-center">
                <h6 class="card-title flex-grow-1">   {{__('Surveys')}}</h6>
                <div class="col-sm-auto float-end d-inline-flex">
                    <form class="items-center mr-2">
                        <label for="simple-search" class="sr-only">{{__('Search')}}</label>
                        <div class="relative w-full">
                            <input wire:model.live="search" type="text" id="simple-search" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Search Book..." required="">
                        </div>
                    </form>
                    <a href="{{route('survey_create_update', app()->getLocale())}}" class="btn btn-info add-btn"
                       id="create-btn">
                        <i class="ri-add-line align-bottom me-1 ml-2"></i>
                        {{__('Create new Servey')}}
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body row ">
            @foreach($surveys as $survey)
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
                                class="badge badge-{{ $survey->showAchievement ? 'badge-success-subtle text-success' : 'badge-danger-subtle text-danger'  }}">
                            {{__('showAchievement')}}
                        </span>
                        </h6>
                        <p class="card-text text-muted">{{$survey->description}}</p>
                        <small class="text-muted">{{__('Creation date')}} : {{$survey->created_at}}</small>
                        <small class="text-muted">{{__('Update date')}} : {{$survey->updated_at}}</small>
                    </div>
                    <div class="card-footer bg-transparent">
                        <a href="{{route('survey_show', ['locale'=> request()->route("locale"),'idServey'=>$survey->id] )}}"
                           class="btn btn-soft-info material-shadow-none">{{__('Details')}}</a>
                        <a href="{{route('survey_participate', ['locale'=> request()->route("locale"),'idServey'=>$survey->id] )}}"
                           class="btn btn-soft-info material-shadow-none">{{__('Paticipate ')}}</a>
                        <a href="{{route('survey_results', ['locale'=> request()->route("locale"),'idServey'=>$survey->id] )}}"
                           class="btn btn-soft-info material-shadow-none">{{__('Show results')}}</a>
                    </div>
                </div>
            @endforeach
            {{ $surveys->links() }}
        </div>
    </div>
</div>
