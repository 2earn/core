<div>
    @section('title')
        {{ __('Busines Sector') }} :     {{$businessSector->name}}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Busines Sector') }} :     {{$businessSector->name}}
        @endslot
    @endcomponent
    <div class="card">


        @if ($businessSector?->thumbnailsImage)

            <img src="{{ asset('uploads/' . $businessSector->thumbnailsImage->url) }}"
                 class="card-img-top img-fluid">
        @else
            <img src="{{Vite::asset(\App\Models\BusinessSector::DEFAULT_IMAGE_TYPE_THUMB)}}"
                 class="card-img-top img-fluid">
        @endif
        <div class="card-body">
            <h2 class="card-title mb-2">                @if(\App\Models\User::isSuperAdmin())
                    {{$businessSector->id}} -
                @endif
                {{$businessSector->name}}
            </h2>
            <div class="card-body row my-2">

                <div class="col-md-4">
                    @if ($businessSector?->logoImage)
                        <div class="col-md-2">
                            <div class="mt-3">
                                <img src="{{ asset('uploads/' . $businessSector->logoImage->url) }}"
                                     alt="Business Sector logoImage" class="img-thumbnail">
                            </div>
                        </div>
                    @else
                        <img src="{{Vite::asset(\App\Models\BusinessSector::DEFAULT_IMAGE_TYPE_LOGO)}}"
                             class="d-block img-fluid img-business-square mx-auto rounded float-left">
                    @endif
                </div>
                <div class="col-md-7">
                    <h5>{{__('Description')}}</h5>
                    <blockquote class="text-muted">
                        {{$businessSector->description}}
                    </blockquote>
                </div>
            </div>

            <div class="text-end">
                <a class="btn btn-primary">{{__('Top up your balance')}}</a>
                <a href="javascript:void(0);" class="btn btn-primary">{{__('Coupon History')}}</a>
            </div>
        </div>
        <div class="card-footer">
            @if(\App\Models\User::isSuperAdmin())
                <a wire:click="deletebusinessSector('{{$businessSector->id}}')"
                   title="{{__('Delete business_sector')}}"
                   class="btn btn-soft-danger material-shadow-none">
                    {{__('Delete')}}
                    <div wire:loading wire:target="deletebusinessSector('{{$businessSector->id}}')">
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                      aria-hidden="true"></span>
                        <span class="sr-only">{{__('Loading')}}...</span>
                    </div>
                </a>
                <a href="{{route('business_sector_create_update',['locale'=> app()->getLocale(),'id'=>$businessSector->id])}}"
                   title="{{__('Edit business sector')}}"
                   class="btn btn-soft-primary material-shadow-none mx-1">
                    {{__('Edit')}}
                </a>
            @endif
            <span class="float-end"> {{__('Created at')}}: {{$businessSector->created_at}}</span>
        </div>
    </div>
        <div class="row">
            <div class="col-12">
                <div class="justify-content-between d-flex align-items-center mt-3 mb-4">
                    <h5 class="mb-0 text-decoration-underline">{{__('Platforms')}}</h5>
                </div>
            </div>
        </div>

    @foreach($platforms as $platform)
        <div class="card">
            <div class="row g-0">
                <div class="col-md-4">
                    @if ($platform?->logoImage)
                        <img src="{{ asset('uploads/' . $platform->logoImage->url) }}"
                             class="rounded-start img-fluid h-100 object-fit-cover">
                    @else
                        <img src="{{Vite::asset(\Core\Models\Platform::DEFAULT_IMAGE_TYPE_LOGO)}}"
                             class="rounded-start img-fluid h-100 object-fit-cover">
                    @endif
                </div>
                <div class="col-md-8">
                    <div class="card-header">
                        <h3 class="card-title mb-0">{{$platform->name}}</h3>
                    </div>
                    <div class="card-body">
                        <p class="card-text mb-2">
                            {{$platform->description}}
                        </p>
                        <p class="card-text"><small class="text-muted">    {{$platform->created_at}}</small></p>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
// items from platforms
    @if(count($items))
        <div class="card">
            <div class="card-header">
                <h5>{{__('Items')}}</h5>
            </div>
            <div class="card-body row my-2">
                <div class="row row-cols-xxl-5 row-cols-lg-3 row-cols-1">
                    @foreach ($items as $item)
                        <livewire:items-show :item="$item"/>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
