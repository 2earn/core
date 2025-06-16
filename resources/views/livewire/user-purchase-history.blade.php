<div class="container-fluid">
    @section('title')
        {{ __('User purchase history') }}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
                {{ __('User purchase history') }}
        @endslot
    @endcomponent
    <div class="card">
        <div class="card-body">
        </div>
    </div>
</div>
