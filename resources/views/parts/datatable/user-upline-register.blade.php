<div class="vstack gap-3 w-100">
    {{-- Upline Register --}}
    <article class="card border-0 shadow-sm">
        <div class="card-body p-3">
            <div class="d-flex align-items-start justify-content-between">
                <div class="d-flex align-items-start flex-grow-1">
                    <div class="avatar-xs flex-shrink-0 me-3">
                        <div class="avatar-title bg-primary-subtle text-primary rounded">
                            <i class="ri-user-add-line" aria-hidden="true"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-2 fw-semibold text-primary">{{__('Upline Register')}}</h6>
                        @if(!is_null($uplineRegister))
                            <div class="vstack gap-1">
                                <div class="d-flex align-items-center text-muted small">
                                    <i class="ri-user-3-line me-2 text-primary" aria-hidden="true"></i>
                                    <span class="fw-medium">{{getUserDisplayedName($uplineRegister->idUser)}}</span>
                                </div>
                                <div class="d-flex align-items-center text-muted small">
                                    <i class="ri-mail-line me-2 text-info" aria-hidden="true"></i>
                                    <a href="mailto:{{$uplineRegister->email}}" class="text-decoration-none">{{$uplineRegister->email}}</a>
                                </div>
                                <div class="d-flex align-items-center text-muted small">
                                    <i class="ri-phone-line me-2 text-success" aria-hidden="true"></i>
                                    <a href="tel:{{$uplineRegister->mobile}}" class="text-decoration-none">{{$uplineRegister->mobile}}</a>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning mb-0 py-2 px-3 d-flex align-items-center small">
                                <i class="ri-alert-line me-2" aria-hidden="true"></i>
                                <span>{{__('No register upline')}}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </article>

    {{-- Current Upline --}}
    <article class="card border-0 shadow-sm">
        <div class="card-body p-3">
            <div class="d-flex align-items-start justify-content-between">
                <div class="d-flex align-items-start flex-grow-1">
                    <div class="avatar-xs flex-shrink-0 me-3">
                        <div class="avatar-title bg-success-subtle text-success rounded">
                            <i class="ri-user-follow-line" aria-hidden="true"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-2 fw-semibold text-success">{{__('Upline')}}</h6>
                        @if(!is_null($upline))
                            <div class="vstack gap-1">
                                <div class="d-flex align-items-center text-muted small">
                                    <i class="ri-user-3-line me-2 text-success" aria-hidden="true"></i>
                                    <span class="fw-medium">{{getUserDisplayedName($upline->idUser)}}</span>
                                </div>
                                <div class="d-flex align-items-center text-muted small">
                                    <i class="ri-mail-line me-2 text-info" aria-hidden="true"></i>
                                    <a href="mailto:{{$upline->email}}" class="text-decoration-none">{{$upline->email}}</a>
                                </div>
                                <div class="d-flex align-items-center text-muted small">
                                    <i class="ri-phone-line me-2 text-primary" aria-hidden="true"></i>
                                    <a href="tel:{{$upline->mobile}}" class="text-decoration-none">{{$upline->mobile}}</a>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning mb-0 py-2 px-3 d-flex align-items-center small">
                                <i class="ri-alert-line me-2" aria-hidden="true"></i>
                                <span>{{__('No upline')}}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </article>
</div>

