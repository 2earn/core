@if ($platform?->logoImage)
    <img src="{{ asset('uploads/' . $platform->logoImage->url) }}"
         class="img-fluid d-block" style="height: 150px">
@else
    <img src="{{Vite::asset(\Core\Models\Platform::DEFAULT_IMAGE_TYPE_LOGO)}}"
         class="img-fluid d-block" style="height: 150px">
@endif
