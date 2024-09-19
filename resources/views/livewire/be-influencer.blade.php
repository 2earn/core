<div>
    @section('title')
        {{ __('Be influencer') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Be influencer') }}
        @endslot
    @endcomponent

    <div class="card">
        <div class="card-body">
            <div class="row mt-2">
                <div class="col-auto col-md-3 col-lg-3 mt-2 m-auto">
                    <a href="{{route('tree_evolution',app()->getLocale(),false)}}"><h3>{{ __('Evolution_arbre') }}</h3>
                    </a>
                </div>
                <div class="col-auto col-md-3 col-lg-3 mt-2 m-auto">
                    <a href="{{route('tree_maintenance',app()->getLocale(),false)}}">
                        <h3>{{ __('Entretien_arbre') }}</h3></a>
                </div>
                <div class="col-auto col-md-3 col-lg-3 mt-2 m-auto">
                    <a href="" class="disabled"><h3>{{ __('Successful Sharing Pool') }}</h3></a>
                </div>
            </div>
        </div>
    </div>

</div>
