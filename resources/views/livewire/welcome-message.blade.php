<div class="row mb-1">
    <div class="col-12">
        <div class="card border-0 bg-primary shadow-sm overflow-hidden">
            <div class="card-body p-4 position-relative">
                <div class="d-flex align-items-center flex-lg-row flex-column gap-3">
                    <div class="flex-shrink-0">
                        <div class="avatar-lg bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center">
                            <img class="rounded-circle avatar-lg"
                                 src="{{ URL::asset($userProfileImage) }}" alt="{{ getUserDisplayedName() }}">
                        </div>
                    </div>

                    <!-- Welcome Content -->
                    <div class="flex-grow-1 text-lg-start text-center">
                        <div class="d-flex align-items-center justify-content-lg-start justify-content-center gap-2 mb-2">
                            <i class="{{$greetingIcon}} fs-4 text-white"></i>
                            <h3 class="text-white mb-0 fw-bold">{{$greeting}}, {{$userName}}!</h3>
                        </div>
                        <p class="text-white text-opacity-75 mb-0 fs-15">
                            <i class="ri-check-double-line me-1"></i>
                            {{__(getSettingStringParam('Welcome back', __('Everything\'s set—let\'s get started')))}}
                        </p>
                    </div>

                    <!-- Quick Stats or Action -->
                    <div class="flex-shrink-0 text-lg-end text-center">
                        <div class="d-inline-flex align-items-center gap-2 bg-white bg-opacity-10 rounded-pill px-3 py-2">
                            <i class="ri-calendar-check-line fs-5 text-white"></i>
                            <div class="text-start">
                                <div class="text-white text-opacity-75 small">{{__('Today')}}</div>
                                <div class="text-white fw-semibold small">{{$currentDate}}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Decorative Elements -->
                <div class="position-absolute top-0 end-0 opacity-25">
                    <i class="ri-shield-star-line" style="font-size: 120px; line-height: 1; color: white;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

