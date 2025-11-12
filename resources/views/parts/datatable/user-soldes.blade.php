<div class="d-flex flex-wrap gap-2 align-items-center justify-content-center">
    {{-- Cash Balance --}}
    <button type="button"
            class="btn btn-soft-info btn-sm d-flex align-items-center shadow-sm"
            data-bs-toggle="modal"
            data-bs-target="#detail"
            data-amount="1"
            data-reciver="{{$idUser}}"
            title="{{__('SoldeCB')}}">
        <i class="ri-money-dollar-circle-line me-1 fs-5" aria-hidden="true"></i>
        <span class="fw-semibold">{{number_format(getUserBalanceSoldes($idUser, 1), 2)}}</span>
    </button>

    {{-- BFS Balance --}}
    <button type="button"
            class="btn btn-soft-secondary btn-sm d-flex align-items-center shadow-sm"
            data-bs-toggle="modal"
            data-bs-target="#detail"
            data-amount="2"
            data-reciver="{{$idUser}}"
            title="{{__('SoldeBFS')}}">
        <i class="ri-shopping-cart-line me-1 fs-5" aria-hidden="true"></i>
        <span class="fw-semibold">{{number_format(getUserBalanceSoldes($idUser, 2), 2)}}</span>
    </button>

    {{-- Discount Balance --}}
    <button type="button"
            class="btn btn-soft-primary btn-sm d-flex align-items-center shadow-sm"
            data-bs-toggle="modal"
            data-bs-target="#detail"
            data-amount="3"
            data-reciver="{{$idUser}}"
            title="{{__('SoldeDB')}}">
        <i class="ri-ri-coupon-4-line me-1 fs-5" aria-hidden="true"></i>
        <span class="fw-semibold">{{number_format(getUserBalanceSoldes($idUser, 3), 2)}}</span>
    </button>

    {{-- SMS Balance --}}
    <button type="button"
            class="btn btn-soft-warning btn-sm d-flex align-items-center shadow-sm"
            data-bs-toggle="modal"
            data-bs-target="#detail"
            data-amount="5"
            data-reciver="{{$idUser}}"
            title="{{__('SoldeSMS')}}">
        <i class="ri-message-3-line me-1 fs-5" aria-hidden="true"></i>
        <span class="fw-semibold">{{number_format(getUserBalanceSoldes($idUser, 5), 0)}}</span>
    </button>

    {{-- Shares Balance --}}
    <button type="button"
            class="btn btn-soft-success btn-sm d-flex align-items-center shadow-sm"
            data-bs-toggle="modal"
            data-bs-target="#detailsh"
            data-amount="6"
            data-reciver="{{$idUser}}"
            title="{{__('SoldeSHARES')}}">
        <i class="ri-line-chart-line me-1 fs-5" aria-hidden="true"></i>
        <span class="fw-semibold">{{number_format(getUserSelledActions($idUser), 0)}}</span>
    </button>
</div>

