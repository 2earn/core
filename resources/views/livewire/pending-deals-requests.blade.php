<div class="container">
    @section('title')
        {{ __('Pending deals requests') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Pending deals requests') }}
        @endslot
    @endcomponent
    @if(\App\Models\User::isSuperAdmin())
        <div class="row mb-3">
            <div class="col-12 card shadow-sm border-0">
                <div class="card-header text-muted d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-clipboard-check me-2"></i>
                        <h5 class="card-title mb-0 text-muted">{{__('Pending Validation Requests')}}</h5>
                    </div>
                    <a href="{{route('deals_validation_requests', ['locale' => app()->getLocale()])}}"
                       class="btn btn-sm btn-primary">
                        <i class="fas fa-list me-1"></i>{{__('View All Requests')}}
                    </a>
                </div>
                <div class="card-body p-3">
                    @livewire('pending-deal-validation-requests-inline')
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12 card shadow-sm border-0">
                <div class="card-header text-muted d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-file-edit me-2"></i>
                        <h5 class="card-title mb-0 text-muted">{{__('Pending Change Requests')}}</h5>
                    </div>
                    <a href="{{route('deals_change_requests', ['locale' => app()->getLocale()])}}"
                       class="btn btn-sm btn-success">
                        <i class="fas fa-list me-1"></i>{{__('View All Change Requests')}}
                    </a>
                </div>
                <div class="card-body p-3">
                    @livewire('pending-deal-change-requests-inline')
                </div>
            </div>
        </div>
    @endif
</div>
