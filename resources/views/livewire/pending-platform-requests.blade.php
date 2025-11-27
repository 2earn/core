<div class="{{getContainerType()}}">
    @section('title')
        {{ __('Platform request') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Platform request') }}
        @endslot
    @endcomponent

    @if(\App\Models\User::isSuperAdmin())
        <div class="row mb-3">
            <div class="col-12 card shadow-sm border-0">
                <div class="card-header text-muted d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="ri-arrow-left-right-line me-2"></i>
                        <h5 class="card-title mb-0 text-muted">{{__('Pending Type Change Requests')}}</h5>
                    </div>
                    <a href="{{route('platform_type_change_requests', ['locale' => app()->getLocale()])}}"
                       class="btn btn-sm btn-warning">
                        <i class="ri-list-check me-1"></i>{{__('View All Requests')}}
                    </a>
                </div>
                <div class="card-body p-3">
                    @livewire('pending-platform-type-change-requests-inline')
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12 card shadow-sm border-0">
                <div class="card-header text-muted d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="ri-shield-check-line me-2"></i>
                        <h5 class="card-title mb-0 text-muted">{{__('Pending Validation Requests')}}</h5>
                    </div>
                    <a href="{{route('platform_validation_requests', ['locale' => app()->getLocale()])}}"
                       class="btn btn-sm btn-primary">
                        <i class="ri-list-check me-1"></i>{{__('View All Requests')}}
                    </a>
                </div>
                <div class="card-body p-3">
                    @livewire('pending-platform-validation-requests-inline')
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12 card shadow-sm border-0">
                <div class="card-header text-muted d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="ri-file-edit-line me-2"></i>
                        <h5 class="card-title mb-0 text-muted">{{__('Pending Platform Update Requests')}}</h5>
                    </div>
                    <a href="{{route('platform_change_requests', ['locale' => app()->getLocale()])}}"
                       class="btn btn-sm btn-success">
                        <i class="ri-list-check me-1"></i>{{__('View All Change Requests')}}
                    </a>
                </div>
                <div class="card-body p-3">
                    @livewire('pending-platform-change-requests-inline')
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12 card shadow-sm border-0">
                <div class="card-header text-muted d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="ri-user-add-line me-2"></i>
                        <h5 class="card-title mb-0 text-muted">{{__('Pending Role Assignments')}}</h5>
                    </div>
                    <a href="{{route('platform_role_assignments', ['locale' => app()->getLocale()])}}"
                       class="btn btn-sm btn-info">
                        <i class="ri-list-check me-1"></i>{{__('View All Role Assignments')}}
                    </a>
                </div>
                <div class="card-body p-3">
                    @livewire('pending-platform-role-assignments-inline')
                </div>
            </div>
        </div>
    @endif

</div>
