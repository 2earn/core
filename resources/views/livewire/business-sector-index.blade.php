<div class="{{getContainerType()}}">
    @section('title')
        {{ __('Bussiness sector') }}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Bussiness sector') }}
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-12">
            @include('layouts.flash-messages')
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="card-header bg-light border-0 py-4">
                <div class="row align-items-center g-3">
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <form class="items-center">
                            <label for="simple-search" class="sr-only">{{__('Search')}}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="ri-search-line text-muted"></i>
                                </span>
                                <input wire:model.live="search" type="text" id="simple-search"
                                       class="form-control border-start-0 ps-0"
                                       placeholder="{{__('Search business sector')}}">
                            </div>
                        </form>
                    </div>
                    @if(\App\Models\User::isSuperAdmin())
                        <div class="col-sm-12 col-md-6 col-lg-6 text-end">
                            <a href="{{route('business_sector_create_update', app()->getLocale())}}"
                               class="btn btn-outline-info waves-effect waves-light"
                               id="create-btn">
                                <i class="ri-add-line align-middle me-1"></i>
                                {{__('Create new business sector')}}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @forelse($business_sectors as $business_sector)
        <div class="card border-0 shadow-sm hover-shadow-lg transition-all">
            <div class="card-header bg-gradient-info bg-opacity-10 border-0 py-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h4 class="card-title mb-2 fw-semibold text-dark">
                            <span class="badge bg-info-subtle text-info me-2">{{$business_sector->id}}</span>
                            {{\App\Models\TranslaleModel::getTranslation($business_sector,'name',$business_sector->name)}}
                        </h4>
                        @if(\App\Models\User::isSuperAdmin())
                            <small class="text-muted">
                                <i class="ri-translate-2 align-middle me-1"></i>
                                <a class="link-info text-decoration-none"
                                   href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($business_sector,'name')])}}">
                                    <i class="ri-translate-2 align-bottom me-1"></i> {{__('Update Translation')}}
                                </a>
                            </small>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <!-- Description Section -->
                <div class="mb-4">
                    <h5 class="text-muted mb-3 fw-semibold">
                        <i class="ri-file-text-line align-middle me-2"></i>{{__('Description')}}
                    </h5>
                    <div class="description-content ps-4">
                        {!! \App\Models\TranslaleModel::getTranslation($business_sector,'description',$business_sector->description) !!}
                        @if(\App\Models\User::isSuperAdmin())
                            <div class="mt-2">
                                <small class="text-muted">
                                    <i class="ri-translate-2 align-middle me-1"></i>
                                    <a class="link-info text-decoration-none"
                                       href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($business_sector,'description')])}}">
                                        <i class="ri-translate-2 align-bottom me-1"></i> {{__('Update Translation')}}
                                    </a>
                                </small>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Images Section -->
                <div class="row g-4 mb-4">
                    <div class="col-md-4 col-lg-3">
                        <div class="image-wrapper">
                            <label class="d-block text-muted small mb-2 fw-semibold">
                                <i class="ri-image-line align-middle me-1"></i>Logo Image
                            </label>
                            <div class="ratio ratio-1x1 rounded-3 overflow-hidden bg-light border">
                                @if ($business_sector->logoImage)
                                    <img src="{{ asset('uploads/' . $business_sector->logoImage->url) }}"
                                         alt="Business Sector logo Image"
                                         class="object-fit-cover">
                                @else
                                    <img src="{{Vite::asset(\App\Models\BusinessSector::DEFAULT_IMAGE_TYPE_LOGO)}}"
                                         alt="Default Business Sector logo"
                                         class="object-fit-cover">
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="image-wrapper">
                            <label class="d-block text-muted small mb-2 fw-semibold">
                                <i class="ri-image-line align-middle me-1"></i>Home Thumbnail
                            </label>
                            <div class="ratio ratio-1x1 rounded-3 overflow-hidden bg-light border">
                                @if ($business_sector->thumbnailsHomeImage)
                                    <img src="{{ asset('uploads/' . $business_sector->thumbnailsHomeImage->url) }}"
                                         alt="Business Sector Home Image"
                                         class="object-fit-cover">
                                @else
                                    <img
                                        src="{{Vite::asset(\App\Models\BusinessSector::DEFAULT_IMAGE_TYPE_THUMB_HOME)}}"
                                        alt="Default Home Thumbnail"
                                        class="object-fit-cover">
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-5">
                        <div class="image-wrapper">
                            <label class="d-block text-muted small mb-2 fw-semibold">
                                <i class="ri-image-line align-middle me-1"></i>Thumbnail
                            </label>
                            <div class="rounded-3 overflow-hidden bg-light border" style="height: 150px;">
                                @if ($business_sector->thumbnailsImage)
                                    <img src="{{ asset('uploads/' . $business_sector->thumbnailsImage->url) }}"
                                         alt="Business Sector Image"
                                         class="w-100 h-100 object-fit-cover">
                                @else
                                    <img src="{{Vite::asset(\App\Models\BusinessSector::DEFAULT_IMAGE_TYPE_THUMB)}}"
                                         alt="Default Thumbnail"
                                         class="w-100 h-100 object-fit-cover">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if(\App\Models\User::isSuperAdmin())
                    <!-- Action Buttons -->
                    <div class="d-flex gap-2 flex-wrap justify-content-end border-top pt-3">
                        <a href="{{route('business_sector_show',['locale'=> app()->getLocale(),'id'=>$business_sector->id])}}"
                           title="{{__('Show busines sector')}}"
                           class="btn btn-outline-success btn-sm">
                            <i class="ri-eye-line align-middle me-1"></i>
                            {{__('Show')}}
                        </a>
                        <a href="{{route('business_sector_create_update',['locale'=> app()->getLocale(),'id'=>$business_sector->id])}}"
                           title="{{__('Edit business sector')}}"
                           class="btn btn-outline-primary btn-sm">
                            <i class="ri-edit-line align-middle me-1"></i>
                            {{__('Edit')}}
                        </a>
                        <button wire:click="deletebusinessSector('{{$business_sector->id}}')"
                                title="{{__('Delete business_sector')}}"
                                class="btn btn-outline-danger btn-sm">
                            <i class="ri-delete-bin-line align-middle me-1"></i>
                            {{__('Delete')}}
                            <div wire:loading
                                 wire:target="deletebusinessSector('{{$business_sector->id}}')">
                                                <span class="spinner-border spinner-border-sm ms-1" role="status"
                                                      aria-hidden="true"></span>
                            </div>
                        </button>
                    </div>
                @endif
            </div>
            <div class="card-footer bg-light border-0 py-3">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <p class="card-text mb-0">
                            <i class="ri-calendar-line align-middle me-1 text-muted"></i>
                            <strong class="text-muted">{{__('Created at')}}:</strong>
                            <small class="text-muted ms-1">{{$business_sector->created_at->format('M d, Y')}}</small>
                        </p>
                    </div>
                    @if(\App\Models\User::isSuperAdmin())
                        <div class="col-sm-6 text-sm-end">
                            <p class="card-text mb-0">
                                <i class="ri-time-line align-middle me-1 text-muted"></i>
                                <strong class="text-muted">{{__('Updated at')}}:</strong>
                                <small
                                    class="text-muted ms-1">{{$business_sector->updated_at->format('M d, Y')}}</small>
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="text-center py-5">
                    <i class="ri-inbox-line display-4 text-muted d-block mb-3"></i>
                    <p class="text-muted fs-5">{{__('No business sectors')}}</p>
                </div>
            </div>
        </div>
    @endforelse

    @if($business_sectors->hasPages())
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="mt-4">
                    {{ $business_sectors->links() }}
                </div>
            </div>
        </div>
    @endif
</div>
