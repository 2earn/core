<div class="vstack gap-2">
    @if($user->OptActivation)
        <div class="card border-0 shadow-sm">
            <div class="card-body py-2 px-3"
                 @if($user->activationCodeValue)
                     title="{{__('Last Opt value')}} : {{$user->activationCodeValue}}"
                @endif
            >
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="ri-key-2-line text-primary me-2 fs-5" aria-hidden="true"></i>
                        <strong class="text-muted small">{{__('Opt activation code')}}:</strong>
                    </div>
                    <span class="badge bg-primary-subtle text-primary fs-6 fw-semibold"
                          @if($user->activationCodeValue)
                              title="{{__('Last Opt value')}} : {{$user->activationCodeValue}}"
                          @endif>
                        {{$user->OptActivation}}
                    </span>
                </div>
            </div>
        </div>
    @endif

    @if($user->register_upline)
        <div class="card border-0 shadow-sm">
            <div class="card-body py-2 px-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="ri-user-follow-line text-info me-2 fs-5" aria-hidden="true"></i>
                        <strong class="text-muted small">{{__('Register upline')}}:</strong>
                    </div>
                    <span class="badge bg-info-subtle text-info fs-6 fw-semibold">
                        {{$user->register_upline}}
                    </span>
                </div>
            </div>
        </div>
    @endif

    @if($user->pass)
        <div class="card border-0 shadow-sm">
            <div class="card-body py-2 px-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="ri-lock-password-line text-danger me-2 fs-5" aria-hidden="true"></i>
                        <strong class="text-muted small">{{__('Password')}}:</strong>
                    </div>
                    <span class="badge bg-danger-subtle text-danger fs-6 fw-semibold font-monospace">
                        {{$user->pass}}
                    </span>
                </div>
            </div>
        </div>
    @endif

    @if($user->idUser)
        <div class="card border-0 shadow-sm">
            <div class="card-body py-2 px-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="ri-fingerprint-line text-secondary me-2 fs-5" aria-hidden="true"></i>
                        <strong class="text-muted small">{{__('idUser')}}:</strong>
                    </div>
                    <span class="badge bg-secondary-subtle text-secondary fs-6 fw-semibold font-monospace">
                        {{$user->idUser}}
                    </span>
                </div>
            </div>
        </div>
    @endif

    @if($user->id)
        <div class="card border-0 shadow-sm">
            <div class="card-body py-2 px-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="ri-hashtag text-dark me-2 fs-5" aria-hidden="true"></i>
                        <strong class="text-muted small">{{__('Id')}}:</strong>
                    </div>
                    <span class="badge bg-dark-subtle text-dark fs-6 fw-semibold font-monospace">
                        {{$user->id}}
                    </span>
                </div>
            </div>
        </div>
    @endif

    {{-- User Detail Link --}}
    <div class="mt-2">
        @include('parts.datatable.user-detail-link',['id' => $user->id])
    </div>
</div>




