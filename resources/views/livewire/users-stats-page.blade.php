<div class="container">
    @section('title')
        {{ __('Users Statistics') }}
    @endsection

    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Users Statistics') }}
        @endslot
    @endcomponent

    <div class="row">
            @include('layouts.flash-messages')
    </div>

    <div class="row">
        <div class="col-12 card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1 text-primary">
                            <i class="ri-bar-chart-box-line me-2"></i>{{ __('Users Statistics Overview') }}
                        </h4>
                        <p class="text-muted mb-0">{{ __('Real-time balance and financial metrics') }}</p>
                    </div>
                    <button onclick="window.location.reload()" class="btn btn-outline-primary btn-sm">
                        <i class="ri-refresh-line me-1"></i>{{ __('Refresh') }}
                    </button>
                </div>
            </div>
            @livewire('users-stats')
        </div>
    </div>
</div>

