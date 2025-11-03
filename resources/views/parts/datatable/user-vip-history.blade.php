<div class="vstack gap-2">
    @if($user->periode)
        <div class="card border-0 shadow-sm bg-primary-subtle">
            <div class="card-body py-2 px-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="ri-time-line text-primary me-2 fs-5" aria-hidden="true"></i>
                        <strong class="text-primary small">{{__('Periode')}}:</strong>
                    </div>
                    <span class="badge bg-primary fs-6 fw-bold">
                        {{$user->periode}}
                        <i class="ri-calendar-line ms-1" aria-hidden="true"></i>
                    </span>
                </div>
            </div>
        </div>
    @endif

    @if($user->minshares)
        <div class="card border-0 shadow-sm bg-success-subtle">
            <div class="card-body py-2 px-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="ri-stock-line text-success me-2 fs-5" aria-hidden="true"></i>
                        <strong class="text-success small">{{__('Minshares')}}:</strong>
                    </div>
                    <span class="badge bg-success fs-6 fw-bold">
                        {{$user->minshares}}
                        <i class="ri-line-chart-line ms-1" aria-hidden="true"></i>
                    </span>
                </div>
            </div>
        </div>
    @endif

    @if($user->coeff)
        <div class="card border-0 shadow-sm bg-warning-subtle">
            <div class="card-body py-2 px-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="ri-percent-line text-warning me-2 fs-5" aria-hidden="true"></i>
                        <strong class="text-warning small">{{__('Coeff')}}:</strong>
                    </div>
                    <span class="badge bg-warning text-dark fs-6 fw-bold">
                        {{$user->coeff}}
                        <i class="ri-functions ms-1" aria-hidden="true"></i>
                    </span>
                </div>
            </div>
        </div>
    @endif

    @if($user->date)
        <div class="card border-0 shadow-sm bg-info-subtle">
            <div class="card-body py-2 px-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="ri-calendar-check-line text-info me-2 fs-5" aria-hidden="true"></i>
                        <strong class="text-info small">{{__('Date')}}:</strong>
                    </div>
                    <span class="badge bg-info fs-6 fw-bold">
                        {{$user->date}}
                        <i class="ri-time-fill ms-1" aria-hidden="true"></i>
                    </span>
                </div>
            </div>
        </div>
    @endif

    @if($user->note)
        <div class="card border-0 shadow-sm bg-secondary-subtle">
            <div class="card-body py-2 px-3">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="d-flex align-items-center flex-shrink-0">
                        <i class="ri-sticky-note-line text-secondary me-2 fs-5" aria-hidden="true"></i>
                        <strong class="text-secondary small">{{__('Note')}}:</strong>
                    </div>
                    <span class="badge bg-secondary fs-6 fw-normal text-end flex-grow-1 ms-2 text-wrap">
                        {{$user->note}}
                    </span>
                </div>
            </div>
        </div>
    @endif
</div>
