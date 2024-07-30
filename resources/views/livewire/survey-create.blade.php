<div>
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Survey Create') }}
        @endslot
    @endcomponent
    <div class="row card">
        <div class="card-header border-info">
            <div class="d-flex align-items-center">
                <h6 class="card-title flex-grow-1">   {{__('Survey Create')}}</h6>
            </div>
        </div>
        <div class="card-body row ">
            <div class="card mb-2 ml-4 border border-dashed ">
                <div class="card-body">
                </div>
            </div>
        </div>
    </div>
</div>
