@if ($news->mainImage)
  <img src="{{ asset('uploads/' . $news->mainImage->url) }}"
                     alt="Business Sector logo Image" class="img-thumbnail rounded float-left">
@endif
