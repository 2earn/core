<div id="business" class="row">
    <div class="col-12 card btn-light">
        <div class="card-header">
            <h5 class="fw-bold mb-2">{{__('Business Sectors')}}</h5>
            <p class="text-muted fs-15 mb-0">{{__('Explore our diverse range of business opportunities')}}</p>
        </div>
        <div class="card-body">
            <div class="row g-4 justify-content-center">
                @foreach($businessSectors as $businessSector)
                    <div class="col-sm-12 col-md-6 col-lg-3">
                        <div class="card border-0 shadow-sm h-100 overflow-hidden card-animate">
                            <!-- Image Section -->
                            <div class="position-relative overflow-hidden" style="height: 220px;">
                                @if ($businessSector->thumbnailsHomeImage)
                                    <img src="{{ asset('uploads/' . $businessSector->thumbnailsHomeImage->url) }}"
                                         alt="{{$businessSector->name}}"
                                         class="img-fluid w-100 h-100"
                                         style="object-fit: cover;">
                                @else
                                    <img
                                        src="{{Vite::asset(\App\Models\BusinessSector::DEFAULT_IMAGE_TYPE_THUMB_HOME)}}"
                                        alt="{{$businessSector->name}}"
                                        class="img-fluid w-100 h-100"
                                        style="object-fit: cover;">
                                @endif
                            </div>

                            <!-- Card Title -->
                            <div class="card-header bg-white border-bottom">
                                <h5 class="fw-semibold mb-0">{{$businessSector->name}}</h5>
                            </div>

                            <!-- Card Content -->
                            <div class="card-body p-4 d-flex flex-column">
                                @if($businessSector->description)
                                    <p class="text-muted mb-4 lh-base flex-grow-1" style="min-height: 60px;">
                                        {{Str::limit($businessSector->description, 100)}}
                                    </p>
                                @else
                                    <div class="mb-4" style="min-height: 60px;"></div>
                                @endif

                                <!-- Stats or Info Section (Optional) -->
                                <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ri-building-line text-primary fs-18"></i>
                                        <span class="text-muted small">{{__('Sector')}}</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ri-arrow-right-circle-line text-success fs-18"></i>
                                        <span class="text-muted small">{{__('Explore')}}</span>
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <a href="{{route('business_sector_show',['locale'=> app()->getLocale(),'id'=>$businessSector->id])}}"
                                   class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2 mt-auto">
                                    <i class="ri-eye-line"></i>
                                    <span>{{__('View Details')}}</span>
                                    <i class="ri-arrow-right-line ms-auto"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="row my-5">
                <div class="col-12 text-center">
                    <a href="{{route('business_sector_index',['locale'=> app()->getLocale()])}}"
                       class="btn btn-view-all-sectors btn-lg px-5 py-3 d-inline-flex align-items-center gap-3">
                        <i class="ri-grid-line fs-18"></i>
                        <span class="fw-semibold text-white">{{__('View All Business Sectors')}}</span>
                        <i class="ri-arrow-right-line fs-18 ms-1 arrow-icon"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
