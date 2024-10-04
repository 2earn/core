<div>
    @section('title')
        {{ __('Personal Characterization') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Personal Characterization') }}
        @endslot
    @endcomponent

    <div class="card">
        <div class="card-body">

        </div>
    </div>

</div>
