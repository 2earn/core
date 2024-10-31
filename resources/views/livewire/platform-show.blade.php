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
        <div class="card card-body">
            <div class="avatar-sm mb-3">
                <div class="avatar-title bg-success-subtle text-success fs-17 rounded">
                    <a href="{{route('platform_index',['locale'=>app()->getLocale()])}}">
                        <i class=" ri-git-repository-private-fill"></i>
                    </a>
                </div>
            </div>
            <h4 title="{{$platform->id}}" class="card-title">
                {{$platform->name}}
            </h4>
            <p class="card-text text-muted">{{$platform->description}}</p>
            <a href="{{$platform->link}}" class="btn btn-outline-info">
                {{__('Visit ')}} {{$platform->name}}</a>
        </div>
    </div>
</div>
