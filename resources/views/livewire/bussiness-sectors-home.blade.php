<div>
    <div class="team-list grid-view-filter row" id="team-member-list">
        @foreach($businessSectors as $businessSector)
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="card team-box">
                <div class="team-cover">
                    @if ($businessSector->thumbnailsHomeImage)
                        <img src="{{ asset('uploads/' . $businessSector->thumbnailsHomeImage->url) }}"
                             alt="{{$businessSector->name}}" class="img-fluid">
                    @else
                        <img src="{{Vite::asset(\App\Models\BusinessSector::DEFAULT_IMAGE_TYPE_THUMB_HOME)}}"
                             alt="{{$businessSector->name}}" class="img-fluid">
                    @endif
                </div>
                <div class="card-body p-4">
                    <div class="row align-items-center team-row">
                        <div class="col-12">
                            <div class="text-center">
                                <a href="{{route('business_sector_show',['locale'=> app()->getLocale(),'id'=>$businessSector->id])}}">
                                    <h5 class=" text-light fs-16 mb-1">{{$businessSector->name}}</h5>
                                </a>
                                @if($businessSector->description)
                                    <p class="text-light mb-0">{{Str::limit($businessSector->description, 100)}}</p>
                                @endif
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <div class="text-center">
                                <a href="{{route('business_sector_show',['locale'=> app()->getLocale(),'id'=>$businessSector->id])}}"
                                   class="btn btn-light view-btn w-100">{{__('View Details')}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

    </div>
    <div class="row mt-4">
        <div class="col-12 text-center">
            <a href="{{route('business_sector_index',['locale'=> app()->getLocale()])}}" class="btn btn-outline-secondary float-end">
                {{__('View All Business Sectors')}} <i class="ri-arrow-right-line align-middle ms-1"></i>
            </a>
        </div>
    </div>
</div>
