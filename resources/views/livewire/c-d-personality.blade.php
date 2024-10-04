<div>
    @section('title')
        {{ __('CD Personality') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('CD Personality') }}
        @endslot
    @endcomponent

    <div class="card">
        <div class="card-body">

        </div>
    </div>

</div>
