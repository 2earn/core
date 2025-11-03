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

    <section class="row mt-2 g-3">
        {{-- Tree Evolution Card --}}
        <div class="col-xxl-4 col-lg-6 col-md-6">
            <article class="card h-100 shadow-sm">
                <img class="card-img-top img-fluid"
                     src="{{ Vite::asset('resources/images/be-influencer/the-tree-money.png') }}"
                     alt="{{ __('Evolution arbre title') }}"
                     loading="lazy"
                     width="100%"
                     height="auto">
                <div class="card-body d-flex flex-column">
                    <h2 class="card-title mb-3 h4">{{ __('Evolution arbre title') }}</h2>
                    <p class="card-text flex-grow-1">{{ __('Evolution arbre description') }}</p>
                </div>
                <div class="card-footer bg-transparent border-top">
                    <a href="{{ route('be_influencer_tree_evolution', app()->getLocale(), false) }}"
                       class="card-link link-secondary d-inline-flex align-items-center"
                       aria-label="{{ __('Learn more about') }} {{ __('Evolution_arbre') }}">
                        {{ __('Evolution_arbre') }}
                        <i class="ri-arrow-right-s-line ms-1 align-middle lh-1" aria-hidden="true"></i>
                    </a>
                </div>
            </article>
        </div>

        {{-- Tree Maintenance Card --}}
        <div class="col-xxl-4 col-lg-6 col-md-6">
            <article class="card h-100 shadow-sm">
                <img class="card-img-top img-fluid"
                     src="{{ Vite::asset('resources/images/be-influencer/nature-the-tree.png') }}"
                     alt="{{ __('Entretien arbre title') }}"
                     loading="lazy"
                     width="100%"
                     height="auto">
                <div class="card-body d-flex flex-column">
                    <h2 class="card-title mb-3 h4">{{ __('Entretien arbre title') }}</h2>
                    <p class="card-text flex-grow-1">{{ __('Entretien arbre description') }}</p>
                </div>
                <div class="card-footer bg-transparent border-top">
                    <a href="{{ route('be_influencer_tree_maintenance', app()->getLocale(), false) }}"
                       class="card-link link-secondary d-inline-flex align-items-center"
                       aria-label="{{ __('Learn more about') }} {{ __('Entretien arbre') }}">
                        {{ __('Entretien arbre') }}
                        <i class="ri-arrow-right-s-line ms-1 align-middle lh-1" aria-hidden="true"></i>
                    </a>
                </div>
            </article>
        </div>

        {{-- Successful Sharing Pool Card --}}
        <div class="col-xxl-4 col-lg-6 col-md-6">
            <article class="card h-100 shadow-sm">
                <img class="card-img-top img-fluid"
                     src="{{ Vite::asset('resources/images/be-influencer/sharing-is-winning.png') }}"
                     alt="{{ __('Successful Sharing Pool title') }}"
                     loading="lazy"
                     width="100%"
                     height="auto">
                <div class="card-body d-flex flex-column">
                    <h2 class="card-title mb-3 h4">{{ __('Successful Sharing Pool title') }}</h2>
                    <p class="card-text flex-grow-1">{{ __('Successful Sharing Pool description') }}</p>
                </div>
                <div class="card-footer bg-transparent border-top">
                    <a href="{{ route('be_influencer_successful_sharing_pool', app()->getLocale(), false) }}"
                       class="card-link link-secondary d-inline-flex align-items-center"
                       aria-label="{{ __('Learn more about') }} {{ __('Successful Sharing Pool') }}">
                        {{ __('Successful Sharing Pool') }}
                        <i class="ri-arrow-right-s-line ms-1 align-middle lh-1" aria-hidden="true"></i>
                    </a>
                </div>
            </article>
        </div>
    </section>
</div>
