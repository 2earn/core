<div class="container-fluid">
    @section('title')
        {{ __('Business Sector') }} :     {{$businessSector->name}}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Business Sector') }} :     {{$businessSector->name}}
        @endslot
    @endcomponent
    <div class="card mt-4 mb-2">
        <div class="col-12">
            <div class="profile-foreground position-relative">
                <div class="profile-wid-bg">
                    @if ($businessSector?->thumbnailsImage)
                        <img src="{{ asset('uploads/' . $businessSector->thumbnailsImage->url) }}" alt=""
                             class="profile-wid-img">
                    @else
                        <img src="{{Vite::asset(\App\Models\BusinessSector::DEFAULT_IMAGE_TYPE_THUMB)}}" alt=""
                             class="profile-wid-img">
                    @endif
                </div>
            </div>
            <div class="pt-3 mb-2 mb-lg-2 pb-lg-3 profile-wrapper">
                <div class="row g-4 px-3 mt-2">
                    <div class="col-auto">
                        <div class="avatar-lg">
                            @if ($businessSector?->logoImage)
                                <img src="{{ asset('uploads/' . $businessSector->logoImage->url) }}"
                                     style="background-color: {{$businessSector->color}}; border: 5px;"
                                     alt="Business Sector logoImage" class="img-thumbnail rounded-circle">
                            @else
                                <img src="{{Vite::asset(\App\Models\BusinessSector::DEFAULT_IMAGE_TYPE_LOGO)}}"
                                     style="background-color: {{$businessSector->color}}; border: 3px;"
                                     class="img-thumbnail rounded-circle">
                            @endif
                        </div>
                    </div>
                    <div class="col">
                        <div class="p-2">
                            <h2 class="mb-1" style="color: {{$businessSector->color}}">
                                {{\App\Models\TranslaleModel::getTranslation($businessSector,'name',$businessSector->name)}}
                            </h2>
                            @if(\App\Models\User::isSuperAdmin())
                                <p class="text-dark">
                                    <a class="link-dark float-end"
                                       href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($businessSector,'name')])}}">{{__('See or update Translation')}}</a>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card mt-2">
            <div class="card-body">
                <div class="col-lg-3">
                    <h5 class="text-dark mt-2">{{__('Description')}}:</h5>
                    <blockquote class="text-dark">
                        {{\App\Models\TranslaleModel::getTranslation($businessSector,'description',$businessSector->description)}}
                        @if(\App\Models\User::isSuperAdmin())
                            <br>
                            <small class="m-2">
                                <a class="link-dark float-end"
                                   href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($businessSector,'description')])}}">{{__('See or update Translation')}}</a>
                            </small>
                        @endif
                    </blockquote>
                    <div class="d-flex profile-wrapper">
                        @if(\App\Models\User::isSuperAdmin())
                            <div class="flex-shrink-0">
                                <a wire:click="deletebusinessSector('{{$businessSector->id}}')"
                                   title="{{__('Delete business_sector')}}"
                                   class="btn btn-danger">
                                    {{__('Delete')}}
                                    <div wire:loading wire:target="deletebusinessSector('{{$businessSector->id}}')">
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                      aria-hidden="true"></span>
                                        <span class="sr-only">{{__('Loading')}}...</span>
                                    </div>
                                </a>
                                <a href="{{route('business_sector_create_update',['locale'=> app()->getLocale(),'id'=>$businessSector->id])}}"
                                   title="{{__('Edit business sector')}}"
                                   class="btn btn-info mx-2">{{__('Edit')}}</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(count($platforms))
        <div class="card card-height-100 mt-2">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">{{__('Platforms')}}</h4>
            </div>
            <div class="card-body">
                @foreach($platforms as $platform)
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-0">
                                <div class="col-md-4">
                                    @if ($platform?->logoImage)
                                        <img src="{{ asset('uploads/' . $platform->logoImage->url) }}"
                                             class="img-fluid d-block" style="height: 150px">
                                    @else
                                        <img src="{{Vite::asset(\Core\Models\Platform::DEFAULT_IMAGE_TYPE_LOGO)}}"
                                             class="img-fluid d-block" style="height: 150px">
                                    @endif

                                    @if ($platform?->link)
                                        <a href="{{$platform->link}}"
                                           class="btn btn-link m-2">{{__('Go to the platform')}}</a>
                                    @endif
                                </div>
                                <div class="col-md-8">
                                    <div class="card-header">
                                        <h3 class="card-title mb-0">
                                            {{\App\Models\TranslaleModel::getTranslation($platform,'name',$platform->name)}}
                                            @if(\App\Models\User::isSuperAdmin())
                                                <small class="mx-2">
                                                    <a class="link-info"
                                                       href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($platform,'name')])}}">{{__('See or update Translation')}}</a>
                                                </small>
                                            @endif
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text mb-2">
                                            {{\App\Models\TranslaleModel::getTranslation($platform,'description',$platform->description)}}
                                            @if(\App\Models\User::isSuperAdmin())
                                                <small class="mx-2">
                                                    <a class="link-info"
                                                       href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($platform,'description')])}}">{{__('See or update Translation')}}</a>
                                                </small>
                                            @endif
                                        </p>
                                        <div class="text-end">
                                            @if($platform->deals()->where('type', \Core\Enum\DealTypeEnum::coupons->value)->where('start_date', '<=', now())->where('end_date', '>=', now())->count())
                                                <a href="{{route('coupon_buy',['locale'=>app()->getLocale(),'id'=>$platform->id])}}"
                                                   class="btn btn-primary m-1">{{__('Top up your balance')}}</a>
                                            @endif
                                            <a href="{{route('coupon_history',['locale'=>app()->getLocale()])}}"
                                               class="btn btn-primary m-1">{{__('Coupon History')}}</a>
                                        </div>
                                        <p class="card-text"><small
                                                class="text-muted">    {{$platform->created_at}}</small>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @foreach($platform->deals()->get() as $deal)
                                <div class="row ">
                                    @foreach($deal->items()->where('ref', '!=', '#0001')->get() as $item)
                                        <div class="col-sm-12 col-md-4 col-lg-3">
                                            <livewire:items-show :item="$item"/>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
