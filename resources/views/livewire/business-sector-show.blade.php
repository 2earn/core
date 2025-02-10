<div>
    @section('title')
        {{ __('Busines sSector') }} :     {{$businessSector->name}}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Busines sSector') }} :     {{$businessSector->name}}
        @endslot
    @endcomponent

    <div class="card">
        <div class="card-header">
            <h3>{{$businessSector->id}} - {{$businessSector->name}}</h3>
        </div>
        <div class="card-body">
            <h5>{{__('Description')}}</h5>
            <blockquote class="text-muted">
                {{$businessSector->description}}
            </blockquote>
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
