<div class="container-fluid">
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
    <div class="card">
        <div class="card-body">
            <div class="card-header border-info">
                <div class="row">
                    <div class="float-end col-sm-12 col-md-6 col-lg-6">
                        <form class="items-center">
                            <label for="simple-search" class="sr-only">{{__('Search')}}</label>
                            <div class="w-full">
                                <input wire:model.live="search" type="text" id="simple-search"
                                       class="form-control float-end"
                                       placeholder="{{__('Search business sector')}}">
                            </div>
                        </form>
                    </div>
                    @if(\App\Models\User::isSuperAdmin())
                        <div class="col-sm-12 col-md-3  col-lg-6">
                            <a href="{{route('business_sector_create_update', app()->getLocale())}}"
                               class="btn btn-soft-info material-shadow-none mt-1 float-end"
                               id="create-btn">
                                <i class="ri-add-line align-bottom me-1 ml-2"></i>
                                {{__('Create new business sector')}}
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card-body row">
                @forelse($business_sectors as $business_sector)
                    <div class="col-sm-12 col-lg-12">
                        <div class="card border card-border-light">
                            <div class="card-header">
                                <h3 class="card-title mb-1">
                                    {{$business_sector->id}} -
                                    {{\App\Models\TranslaleModel::getTranslation($business_sector,'name',$business_sector->name)}}
                                    @if(\App\Models\User::isSuperAdmin())
                                        <small class="mx-2">
                                            <a class="link-info"
                                               href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($business_sector,'name')])}}">{{__('See or update Translation')}}</a>
                                        </small>
                                    @endif
                                </h3>
                            </div>
                            <div class="card-body row my-2">
                                <div class="col-md-6">
                                    <h4>
                                        {{__('Description')}}
                                    </h4>
                                    <blockquote class="blockquote card-text">
                                        {{\App\Models\TranslaleModel::getTranslation($business_sector,'description',$business_sector->description)}}
                                        @if(\App\Models\User::isSuperAdmin())
                                            <small class="mx-2">
                                                <a class="link-info"
                                                   href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($business_sector,'description')])}}">{{__('See or update Translation')}}</a>
                                            </small>
                                        @endif
                                    </blockquote>
                                </div>
                                <div class="col-md-3">
                                    @if ($business_sector->logoImage)
                                        <img src="{{ asset('uploads/' . $business_sector->logoImage->url) }}"
                                             alt="Business Sector logo Image"
                                             class="d-block img-fluid img-business-square mx-auto rounded float-left">
                                    @else
                                        <img src="{{Vite::asset(\App\Models\BusinessSector::DEFAULT_IMAGE_TYPE_LOGO)}}"
                                             class="d-block img-fluid img-business-square mx-auto rounded float-left">
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    @if ($business_sector->thumbnailsImage)
                                        <img src="{{ asset('uploads/' . $business_sector->thumbnailsImage->url) }}"
                                             alt="Business Sector Image"
                                             class="d-block img-fluid img-business mx-auto rounded float-left">
                                    @else
                                        <img src="{{Vite::asset(\App\Models\BusinessSector::DEFAULT_IMAGE_TYPE_THUMB)}}"
                                             class="d-block img-fluid img-business mx-auto rounded float-left">
                                    @endif
                                </div>


                                @if(\App\Models\User::isSuperAdmin())
                                    <div class="col-auto my-2">
                                        <a wire:click="deletebusinessSector('{{$business_sector->id}}')"
                                           title="{{__('Delete business_sector')}}"
                                           class="btn btn-soft-danger material-shadow-none float-end">
                                            {{__('Delete')}}
                                            <div wire:loading
                                                 wire:target="deletebusinessSector('{{$business_sector->id}}')">
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                      aria-hidden="true"></span>
                                                <span class="sr-only">{{__('Loading')}}...</span>
                                            </div>
                                        </a>
                                        <a
                                            href="{{route('business_sector_create_update',['locale'=> app()->getLocale(),'id'=>$business_sector->id])}}"
                                            title="{{__('Edit business sector')}}"
                                            class="btn btn-soft-primary material-shadow-none mx-1 float-end">
                                            {{__('Edit')}}
                                        </a>
                                        <a
                                            href="{{route('business_sector_show',['locale'=> app()->getLocale(),'id'=>$business_sector->id])}}"
                                            title="{{__('Show busines sector')}}"
                                            class="btn btn-soft-success material-shadow-none float-end">
                                            {{__('Show')}}
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col">
                                        <p class="card-text float-end"><strong>{{__('Created at')}}:</strong> <small
                                                class="text-muted">{{$business_sector->created_at}}</small>
                                        </p>
                                    </div>
                                    <div class="col">
                                        @if(\App\Models\User::isSuperAdmin())
                                            <p class="card-text  float-end"><strong>{{__('Updated at')}}
                                                    : </strong><small
                                                    class="text-muted">{{$business_sector->updated_at}}</small></p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">{{__('No business sectors')}}</p>
                @endforelse
                {{ $business_sectors->links() }}
            </div>
        </div>
    </div>
</div>
