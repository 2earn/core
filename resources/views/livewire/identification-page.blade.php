<div class="container">
    <div class="row">
        <div class="col-12">
            @component('components.breadcrumb')
                @slot('title')
                    {{ __('Identifications') }}
                @endslot
            @endcomponent

            <div class="row">
                    @include('layouts.flash-messages')
            </div>

            <div class="row">
                <div class="col-12 card shadow-sm">
                    <div class="card-header bg-transparent border-bottom">
                        <div class="d-flex align-items-center">
                            <i class="ri-shield-check-line fs-4 text-info me-2"></i>
                            <h5 class="card-title mb-0 text-info">{{ __('Identifications') }}</h5>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <livewire:identification-check/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

