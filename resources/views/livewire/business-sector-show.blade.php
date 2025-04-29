<div class="container-fluid">
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
            <h2 class="card-title mb-2">
                @if(\App\Models\User::isSuperAdmin())
                    {{$businessSector->id}} -
                @endif
                {{\App\Models\TranslaleModel::getTranslation($businessSector,'name',$businessSector->name)}}
                @if(\App\Models\User::isSuperAdmin())
                    <small class="mx-2">
                        <a class="link-info"
                           href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($businessSector,'name')])}}">{{__('See or update Translation')}}</a>
                    </small>
                @endif
            </h2>
            <div class="card-body row my-2">

                <div class="col-sm-12 col-md-3 col-md-2">
                    @if ($businessSector?->logoImage)
                        <img src="{{ asset('uploads/' . $businessSector->logoImage->url) }}"
                             alt="Business Sector logoImage" class="avatar-xl">
                    @else
                        <img src="{{Vite::asset(\App\Models\BusinessSector::DEFAULT_IMAGE_TYPE_LOGO)}}"
                             class="avatar-xl">
                    @endif
                </div>
                <div class="col-sm-12 col-md-9 col-md-9">
                    <h5>{{__('Description')}}</h5>
                    <blockquote class="text-muted">
                        {{\App\Models\TranslaleModel::getTranslation($businessSector,'description',$businessSector->description)}}
                        @if(\App\Models\User::isSuperAdmin())
                            <small class="mx-2">
                                <a class="link-info"
                                   href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($businessSector,'description')])}}">{{__('See or update Translation')}}</a>
                            </small>
                        @endif
                    </blockquote>
                </div>
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
            <span class="text-muted float-end">
                {{__('Created at')}}: {{$businessSector->created_at}}
            </span>
        </div>
    </div>

    @if(count($platforms))
        <div class="card card-height-100">
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
                                             class="avatar-xl">
                                    @else
                                        <img src="{{Vite::asset(\Core\Models\Platform::DEFAULT_IMAGE_TYPE_LOGO)}}"
                                             class="avatar-xl">
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
                                            @if($platform->deals()->count())
                                                <a href="{{route('coupon_buy',['locale'=>app()->getLocale(),'id'=>$platform->id])}}"
                                                   class="btn btn-primary">{{__('Top up your balance')}}</a>
                                            @endif
                                            <a href="{{route('coupon_history',['locale'=>app()->getLocale()])}}"
                                               class="btn btn-primary">{{__('Coupon History')}}</a>
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
