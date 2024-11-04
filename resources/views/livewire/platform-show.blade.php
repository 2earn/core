<div>
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
            <div class="avatar-sm mb-3 row">
                <div class="bg-success-subtle text-success fs-24">
                    <a href="{{route('platform_index',['locale'=>app()->getLocale()])}}">
                        <div class="flex-shrink-0">
                            <img src="{{$platform->image_link}}" alt="" class="avatar-xs rounded-circle">
                        </div>
                    </a>
                </div>

            </div>
            <h4 title="{{$platform->id}}" class="card-title">
                {{$platform->name}}
            </h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-10">
                    <p class="card-text text-muted">{{$platform->description}}</p>
                </div>
                <div class="col-2">
                    <div class="float-end">
                        @if($platform->administrative_manager_id)
                            <span title="{{$platform->administrative_manager_id}}"
                                  class="badge text-info">{{__(\Core\Enum\Promotion::Administrative->name)}}</span>
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
