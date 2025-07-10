<div class="row">
    @foreach($businessSectors as $businessSector)
        <div class="col-sm-6 col-md-4 col-lg-2">
            <div class="card">
                <div class="card-body p-3" title="{{$businessSector->name}}">
                    <a class="popup-img d-inline-block"
                       href="{{route('business_sector_show',['locale'=> app()->getLocale(),'id'=>$businessSector->id])}}"
                    >
                        @if ($businessSector->thumbnailsHomeImage)
                            <img src="{{ asset('uploads/' . $businessSector->thumbnailsHomeImage->url) }}"
                                 class="rounded img-fluid" alt="{{$businessSector->name}}">
                        @else
                            <img src="{{Vite::asset(\App\Models\BusinessSector::DEFAULT_IMAGE_TYPE_THUMB_HOME)}}"
                                 class="rounded img-fluid" alt="{{$businessSector->name}}">
                        @endif
                    </a>
                </div>
            </div>
        </div>
    @endforeach
</div>
