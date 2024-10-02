<div>
    @section('title')
        {{ __('Biography') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Biography') }}
        @endslot
    @endcomponent

    <div class="card">
        <div class="card-body">

        </div>
    </div>

</div>
