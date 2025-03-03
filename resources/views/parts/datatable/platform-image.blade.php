@if ($platform?->logoImage)
    <img src="{{ asset('uploads/' . $platform->logoImage->url) }}"
         class="d-block img-fluid img-business-square mx-auto rounded float-left">
@else
    <img src="{{Vite::asset(\Core\Models\Platform::DEFAULT_IMAGE_TYPE_LOGO)}}"
         class="d-block img-fluid img-business-square mx-auto rounded float-left">
@endif
