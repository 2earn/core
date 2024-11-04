<div>
    @section('title')
        {{ __('Deals') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Deals') }}
        @endslot
    @endcomponent

    <div class="card">
        <div class="card-body">
        </div>
    </div>

</div>
