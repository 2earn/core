<div class="{{getContainerType()}}">
    @section('title')
        {{ __('Be influencer') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Be influencer') }}
        @endslot
    @endcomponent
    <div class="row mt-2">
        <div class="col-xxl-4">
            <div class="card">
                <img class="card-img-top img-fluid"
                     src="{{ Vite::asset('resources/images/be-influencer/the-tree-money.png') }}"
                     alt="Card image cap">
                <div class="card-body">
                    <h4 class="card-title mb-2">{{ __('Evolution arbre title') }}</h4>
                    <p class="card-text mb-0">{{ __('Evolution arbre description') }}</p>
                </div>
                <div class="card-footer">
                    <a href="{{route('be_influencer_tree_evolution',app()->getLocale(),false)}}"
                       class="card-link link-secondary">{{ __('Evolution_arbre') }} <i
                            class="ri-arrow-right-s-line ms-1 align-middle lh-1"></i></a>
                </div>
            </div>
        </div>
        <div class="col-xxl-4">
            <div class="card">
                <img class="card-img-top img-fluid"
                     src="{{ Vite::asset('resources/images/be-influencer/nature-the-tree.png') }}"
                     alt="Card image cap">
                <div class="card-body">
                    <h4 class="card-title mb-2">{{ __('Entretien arbre title') }}</h4>
                    <p class="card-text mb-0">{{ __('Entretien arbre description') }}</p>
                </div>
                <div class="card-footer">
                    <a href="{{route('be_influencer_tree_maintenance',app()->getLocale(),false)}}"
                       class="card-link link-secondary">{{ __('Entretien arbre') }} <i
                            class="ri-arrow-right-s-line ms-1 align-middle lh-1"></i></a>
                </div>
            </div>
        </div>
        <div class="col-xxl-4">
            <div class="card">
                <img class="card-img-top img-fluid"
                     src="{{ Vite::asset('resources/images/be-influencer/sharing-is-winning.png') }}"
                     alt="Card image cap">
                <div class="card-body">
                    <h4 class="card-title mb-2">{{ __('Successful Sharing Pool title') }}</h4>
                    <p class="card-text mb-0">{{ __('Successful Sharing Pool description') }}</p>
                </div>
                <div class="card-footer">
                    <a href="{{route('be_influencer_successful_sharing_pool',app()->getLocale(),false)}}"
                       class="card-link link-secondary">{{ __('Successful Sharing Pool') }} <i
                            class="ri-arrow-right-s-line ms-1 align-middle lh-1"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>
