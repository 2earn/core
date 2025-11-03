<div class="d-grid gap-2">
    {{-- Add Cash Button --}}
    <button type="button"
            class="btn btn-soft-primary btn-sm d-flex align-items-center justify-content-between shadow-sm"
            data-bs-toggle="modal"
            data-bs-target="#AddCash"
            data-phone="{{$phone}}"
            data-country="{{$country}}"
            data-reciver="{{$reciver}}">
        <span class="d-flex align-items-center">
            <i class="ri-money-dollar-circle-line me-2 fs-5" aria-hidden="true"></i>
            <span class="fw-medium">{{__('Add cash')}}</span>
        </span>
        <i class="ri-arrow-right-s-line" aria-hidden="true"></i>
    </button>

    {{-- Promote Button --}}
    <a href="{{route('platform_promotion',['locale'=>app()->getLocale(),'userId'=>$userId])}}"
       class="btn btn-soft-secondary btn-sm d-flex align-items-center justify-content-between shadow-sm">
        <span class="d-flex align-items-center">
            <i class="ri-megaphone-line me-2 fs-5" aria-hidden="true"></i>
            <span class="fw-medium">{{__('Promote')}}</span>
        </span>
        <i class="ri-arrow-right-s-line" aria-hidden="true"></i>
    </a>

    {{-- VIP Status Badge --}}
    @if(!is_null($isVip))
        @if($isVip)
            <div class="alert alert-success mb-0 py-2 px-3 d-flex align-items-center shadow-sm" role="status">
                <i class="ri-vip-crown-line me-2 fs-5" aria-hidden="true"></i>
                <span class="fw-medium">{{__('Currently VIP')}}</span>
                <span class="badge bg-success ms-auto">
                    <i class="ri-checkbox-circle-line" aria-hidden="true"></i>
                </span>
            </div>
        @else
            <div class="alert alert-info mb-0 py-2 px-3 d-flex align-items-center shadow-sm" role="status">
                <i class="ri-vip-crown-2-line me-2 fs-5" aria-hidden="true"></i>
                <span class="fw-medium">{{__('Was VIP')}}</span>
                <span class="badge bg-info ms-auto">
                    <i class="ri-history-line" aria-hidden="true"></i>
                </span>
            </div>
        @endif
    @endif

    {{-- Make VIP Button --}}
    <button type="button"
            class="btn btn-flash btn-sm d-flex align-items-center justify-content-between shadow-sm"
            data-bs-toggle="modal"
            data-bs-target="#vip"
            data-phone="{{$phone}}"
            data-country="{{$country}}"
            data-reciver="{{$reciver}}">
        <span class="d-flex align-items-center">
            <i class="ri-vip-crown-fill me-2 fs-5" aria-hidden="true"></i>
            <span class="fw-medium">{{__('VIP')}}</span>
        </span>
        <i class="ri-add-line" aria-hidden="true"></i>
    </button>

    {{-- Update Password Button --}}
    <button type="button"
            class="btn btn-soft-danger btn-sm d-flex align-items-center justify-content-between shadow-sm"
            data-bs-toggle="modal"
            data-id="{{$userId}}"
            data-phone="{{$phone}}"
            id="updatePasswordBtn"
            data-bs-target="#updatePassword">
        <span class="d-flex align-items-center">
            <i class="ri-lock-password-line me-2 fs-5" aria-hidden="true"></i>
            <span class="fw-medium">{{__('Update password')}}</span>
        </span>
        <i class="ri-arrow-right-s-line" aria-hidden="true"></i>
    </button>
</div>
