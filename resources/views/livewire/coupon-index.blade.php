<div>
    @section('title')
        {{ __('Coupon') }}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
                {{ __('Coupon') }}
        @endslot
    @endcomponent
    <div class="card">
        <div class="card-body">

        </div>
    </div>
</div>
