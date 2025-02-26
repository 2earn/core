<div>
    @section('title')
        {{ __('Item') }}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Item') }}
        @endslot
    @endcomponent
    <div class="card">
        <div class="card-body">

        </div>
    </div>
</div>
