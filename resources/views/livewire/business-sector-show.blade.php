<div>
    @section('title')
        {{ __('Busines sSector') }} :     {{$businessSector->name}}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Busines sSector') }} :     {{$businessSector->name}}
        @endslot
    @endcomponent
    <div class="card">
        <div class="card-header">
            <h3>
                @if(\App\Models\User::isSuperAdmin())
                    {{$businessSector->id}} -
                @endif
                {{$businessSector->name}}
            </h3>
        </div>
        <div class="card-body row my-2">
           <div class="col-md-7">
               <h5>{{__('Description')}}</h5>
               <blockquote class="text-muted">
                   {{$businessSector->description}}
               </blockquote>
           </div>
            @if ($businessSector?->logoImage)
                <div class="col-md-2">
                <div class="mt-3">
                    <img src="{{ asset('uploads/' . $businessSector->logoImage->url) }}" alt="Business Sector logoImage" class="img-thumbnail">
                </div>
                </div>
            @endif

            @if ($businessSector?->thumbnailsImage)
                <div class="col-md-3">
                <div class="mt-3">
                    <img src="{{ asset('uploads/' . $businessSector->thumbnailsImage->url) }}" alt="Business Sector thumbnailsImage" class="img-thumbnail">
                </div>
                </div>
            @endif
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
                <a
                    href="{{route('business_sector_create_update',['locale'=> app()->getLocale(),'id'=>$businessSector->id])}}"
                    title="{{__('Edit business sector')}}"
                    class="btn btn-soft-primary material-shadow-none mx-1">
                    {{__('Edit')}}
                </a>
            @endif

            <span class="float-end">

    {{__('Created at')}}: {{$businessSector->created_at}}
</span>
        </div>
    </div>

</div>
