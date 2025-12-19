<div class="row">
    <div class="col-12 card border-0 shadow-lg overflow-hidden"
         style="background: linear-gradient(135deg, #00aaf2 0%, #e502f5 100%); border-radius: 20px;">
        <div class="card-body  position-relative">
            <div class="d-flex align-items-center flex-lg-row flex-column">
                <div class="flex-shrink-0">
                    <div class="position-relative">
                        <div
                            class="avatar-xl bg-white bg-opacity-20 d-flex align-items-center justify-content-center shadow-lg mx-5"
                            style="width: 90px; height: 90px; border-radius: 24px; backdrop-filter: blur(10px);">
                            <img class="avatar-xl"
                                 style="border-radius: 20px; width: 82px; height: 82px; object-fit: cover; border: 3px solid rgba(255,255,255,0.3);"
                                 src="{{ URL::asset($userProfileImage) }}"
                                 alt="{{ getUserDisplayedName() }}">
                        </div>
                    </div>
                </div>

                <div class="flex-grow-1 text-lg-start text-center">
                    <div class="d-flex align-items-center justify-content-lg-start justify-content-center gap-3 mb-3">
                        <div>
                            <h2 class="text-white mb-1 fw-bold" style="font-size: 1.75rem; letter-spacing: -0.5px;">
                                {{$greeting}}, {{$userName}}!
                            </h2>
                            <p class="text-white text-opacity-90 mb-0 fs-6 fw-medium">
                                <i class="ri-check-double-line me-2"></i>
                                {{__(getSettingStringParam('Welcome back', __('Everything\'s set—let\'s get started')))}}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Date Badge -->
                <div class="flex-shrink-0 text-lg-end text-center">
                    <div class="d-inline-flex align-items-center gap-3 bg-opacity-15 px-4 py-3 shadow-sm"
                         style="border-radius: 16px; backdrop-filter: blur(10px);">
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 44px; height: 44px;">
                            <i class="ri-calendar-check-line fs-4 text-white"></i>
                        </div>
                        <div class="text-start">
                            <div class="text-white text-opacity-75 small fw-medium">{{__('Today')}}</div>
                            <div class="text-white fw-bold">{{$currentDate}}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="position-absolute bottom-0 start-0 opacity-10" style="transform: rotate(15deg);">
                <i class="ri-sparkling-2-fill" style="font-size: 100px; line-height: 1; color: white;"></i>
            </div>
        </div>
    </div>
</div>

