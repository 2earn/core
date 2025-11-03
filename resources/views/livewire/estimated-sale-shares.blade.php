<div class="card shadow-sm">
    <div class="card-header bg-light border-bottom">
        <div class="d-flex align-items-center">
            <div class="flex-shrink-0 me-3">
                <div class="avatar-sm">
                    <div class="avatar-title bg-success-subtle text-success rounded-circle fs-18">
                        <i class="ri-funds-line"></i>
                    </div>
                </div>
            </div>
            <div class="flex-grow-1">
                <h5 class="card-title mb-0 fw-semibold text-dark">{{__('Estimated Sale Shares')}}</h5>
                <p class="text-muted small mb-0">{{__('Calculate your potential earnings')}}</p>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-12">
                <div class="card border-info bg-info-subtle mb-0">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <i class="ri-stock-line fs-18 text-info"></i>
                                <span class="fw-semibold text-dark">{{__('User Selled Action Number')}}</span>
                            </div>
                            <span class="badge bg-info fs-14 px-3 py-2">{{$userSelledActionNumber}}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <label for="estimatedGain" class="form-label fw-semibold">
                    <i class="ri-money-dollar-circle-line me-1 text-success"></i>{{__('Gain')}}
                </label>
                <div class="input-group input-group-lg">
                    <span class="input-group-text bg-success-subtle border-success">
                        <i class="bx bx-dollar text-success"></i>
                    </span>
                    <input aria-describedby="estimatedGain" type="number" wire:keyup.debounce="simulateAction()"
                           disabled wire:model.live="estimatedGain" id="estimatedGain"
                           class="form-control form-control-lg border-success bg-white">
                </div>
            </div>
            <div class="col-12">
                <div class="card border-light">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item bg-light">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ri-arrow-right-circle-line text-info"></i>
                                    <span class="fw-semibold text-dark">
                                        {{__('Total Paied')}}
                                        <i class="ri-arrow-right-s-line mx-1"></i>
                                        <span class="text-success">{{__('Estimated Gain')}}</span>
                                    </span>
                                </div>
                                <span class="badge bg-gradient bg-info-subtle text-info border border-info px-3 py-2">
                                    {{$totalPaied}}
                                    <i class="ri-arrow-right-s-line mx-1"></i>
                                    <span class="text-success fw-bold">{{$estimatedGain}}</span>
                                </span>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ri-price-tag-3-line text-secondary"></i>
                                    <span class="text-secondary fw-medium">{{__('Action value')}}</span>
                                </div>
                                <span class="badge bg-secondary-subtle text-secondary px-3 py-2">{{$actionValue}}</span>
                            </div>
                        </li>
                        @if(\App\Models\User::isSuperAdmin())
                            <li class="list-group-item bg-light">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ri-database-2-line text-primary"></i>
                                        <span class="fw-medium text-dark">
                                            {{__('Selled Action Cursor')}}
                                            <i class="ri-arrow-right-s-line mx-1"></i>
                                            {{__('Total number of shares for sale')}}
                                        </span>
                                    </div>
                                    <span class="badge bg-primary-subtle text-primary border border-primary px-3 py-2">
                                        {{$selledActionCursor}}
                                        <i class="ri-arrow-right-s-line mx-1"></i>
                                        {{$numberSharesSale}}
                                    </span>
                                </div>
                            </li>
                        @endif
                        <li class="list-group-item">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ri-pie-chart-2-line text-info"></i>
                                    <span class="fw-semibold text-info">{{__('Percent of progress of selled shares')}}</span>
                                </div>
                                <span class="badge bg-info fs-15 px-3 py-2">
                                    {{round((($selledActionCursor / $totalActions)*100),2) }}
                                    <i class="ri-percent-line ms-1"></i>
                                </span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-12">
                <div class="card border-primary bg-primary-subtle mb-0">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <label for="selledActionCursor" class="form-label mb-0 fw-semibold text-dark">
                                <i class="ri-slidebar-line me-1 text-primary"></i>{{__('Adjust Sold Shares')}}
                            </label>
                            <span class="badge bg-primary px-3 py-2">
                                <i class="ri-stock-line me-1"></i>{{$selledActionCursor}} / {{$totalActions}}
                            </span>
                        </div>
                        <input type="range" min="0" max="{{$totalActions}}" title="{{$totalActions}}"
                               class="form-range" wire:model.live="selledActionCursor" step="1" wire:change="simulateGain()"
                               id="selledActionCursor">
                        <div class="d-flex justify-content-between mt-2">
                            <small class="text-muted">0</small>
                            <small class="text-muted">{{$totalActions}}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
