<div class="container-fluid">
@section('title')
        {{ __('Platform Profile') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Platform Profile') }}
        @endslot
    @endcomponent
    <div class="card">
        <div class="card-body">
            <div class="mb-3 row">
                <div class="bg-success-subtle text-success">
                    <a href="{{route('platform_index',['locale'=>app()->getLocale()])}}">
                            @if ($platform?->logoImage)
                                <img src="{{ asset('uploads/' . $platform->logoImage->url) }}"
                                     class="img-fluid d-block avatar-md rounded-circle" >
                            @else
                                <img src="{{Vite::asset(\Core\Models\Platform::DEFAULT_IMAGE_TYPE_LOGO)}}"
                                     class="img-fluid d-block avatar-md rounded-circle" >
                            @endif
                    </a>
                </div>

            </div>
            <h4 title="{{$platform->id}}" class="card-title">
                {{\App\Models\TranslaleModel::getTranslation($platform,'name',$platform->name)}}
                @if(\App\Models\User::isSuperAdmin())
                    <small class="mx-2">
                        <a class="link-info"
                           href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($platform,'name')])}}">{{__('See or update Translation')}}</a>
                    </small>
                @endif
            </h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-10">
                    <p class="card-text text-muted">
                        {!! \App\Models\TranslaleModel::getTranslation($platform,'description',$platform->name) !!}
                        @if(\App\Models\User::isSuperAdmin())
                            <small class="mx-2">
                                <a class="link-info"
                                   href="{{route('translate_model_data',['locale'=>app()->getLocale(),'search'=> \App\Models\TranslaleModel::getTranslateName($platform,'description')])}}">{{__('See or update Translation')}}</a>
                            </small>
                        @endif
                    </p>
                </div>
                <div class="col-2">
                    <div class="float-end">
                        @if($platform->financial_manager_id)
                            <span title="{{$platform->financial_manager_id}}"
                                  class="badge text-info">{{__(\Core\Enum\Promotion::Financial->name)}}</span>
                        @endif
                        @if($platform->financial_manager_id)
                            <span title="{{$platform->financial_manager_id}}"
                                  class="badge text-info">{{__(\Core\Enum\Promotion::Financial->name)}}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <a href="{{$platform->link}}" class="btn btn-outline-info">
                {{__('Visit ')}} {{$platform->name}}</a>
        </div>
    </div>
</div>
