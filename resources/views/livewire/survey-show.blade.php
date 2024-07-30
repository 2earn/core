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
                    {{__('Survey title 582')}}
                </div>
                <div class="card-body">
                    <p class="card-text">This is a wider card with supporting text below as a natural lead-in to
                        additional content. This card has even longer content than the first to show that equal
                        height action.</p>
                    <small class="text-muted">{{__('Date end')}} : 20/12/2024</small>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="#" class="btn btn-soft-info material-shadow-none">{{__('Paticipate ')}}</a>
                    <a href="#" class="btn btn-soft-success material-shadow-none">{{__('Show results')}}</a>
                </div>
            </div>
        </div>
    </div>
</div>
