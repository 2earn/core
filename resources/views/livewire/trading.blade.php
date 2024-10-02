<div>
    @section('title')
        {{ __('Trading') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Trading') }}
        @endslot
    @endcomponent

    <div class="card">
        <div class="card-body">

        </div>
    </div>

</div>
