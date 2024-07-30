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
                <div class="col-sm-auto float-end ">
                    <a href="{{route('survey_create', app()->getLocale())}}" class="btn btn-info add-btn"
                       id="create-btn">
                        <i class="ri-add-line align-bottom me-1"></i>
                        {{__('Create new Servey')}}
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body row ">
            <div class="card mb-2 ml-4 border border-dashed ">
                <div class="card-header border-info fw-medium text-muted mb-0">
                    {{__('Survey title 582')}}
                </div>
                <div class="card-body">
                    <p class="card-text">This is a wider card with supporting text below as a natural lead-in to
                        additional content. This card has even longer content than the first to show that equal
                        height action.</p>
                    <small class="text-muted">{{__('Date end')}} : 20/12/2024</small>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="{{route('survey_show', ['locale'=> request()->route("locale"),'idServey'=>'555'] )}}"
                       class="btn btn-soft-info material-shadow-none">{{__('Details')}}</a>
                    <a href="{{route('survey_participate', ['locale'=> request()->route("locale"),'idServey'=>'555'] )}}"
                       class="btn btn-soft-info material-shadow-none">{{__('Paticipate ')}}</a>
                    <a href="{{route('survey_results', ['locale'=> request()->route("locale"),'idServey'=>'555'] )}}"
                       class="btn btn-soft-info material-shadow-none">{{__('Show results')}}</a>
                </div>
            </div>
            <div class="card mb-2 ml-4 border border-dashed ">
                <div class="card-header border-info fw-medium text-muted mb-0">
                    {{__('Survey title 582')}}
                </div>
                <div class="card-body">
                    <p class="card-text">This is a wider card with supporting text below as a natural lead-in to
                        additional content. This card has even longer content than the first to show that equal
                        height action.</p>
                    <small class="text-muted">{{__('Date end')}} : 20/12/2024</small>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="{{route('survey_show', ['locale'=> request()->route("locale"),'idServey'=>'555'] )}}"
                       class="btn btn-soft-info material-shadow-none">{{__('Details')}}</a>
                    <a href="{{route('survey_participate', ['locale'=> request()->route("locale"),'idServey'=>'555'] )}}"
                       class="btn btn-soft-info material-shadow-none">{{__('Paticipate ')}}</a>
                    <a href="{{route('survey_results', ['locale'=> request()->route("locale"),'idServey'=>'555'] )}}"
                       class="btn btn-soft-info material-shadow-none">{{__('Show results')}}</a>
                </div>
            </div>
        </div>
    </div>
</div>
