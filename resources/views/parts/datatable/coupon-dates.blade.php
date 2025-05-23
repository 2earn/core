<div data-simplebar style="max-height: 215px;">
    <ul class="list-group">
        <li class="list-group-item">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="d-flex">
                        <div class="flex-shrink-0 ms-2">
                            <h6 class="fs-14 mb-0">{{__('Attachement')}}</h6>
                        </div>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <span class="text-danger">{{$coupon->attachment_date}}</span>
                </div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="d-flex">
                        <div class="flex-shrink-0 ms-2">
                            <h6 class="fs-14 mb-0">{{__('Purchase')}}</h6>
                        </div>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <span class="text-danger">{{$coupon->purchase_date}}</span>
                </div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="d-flex">
                        <div class="flex-shrink-0 ms-2">
                            <h6 class="fs-14 mb-0">{{__('Consumption')}}</h6>
                        </div>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <span class="text-danger">{{$coupon->consumption_date}}</span>
                </div>
            </div>
        </li>
    </ul>
</div>
