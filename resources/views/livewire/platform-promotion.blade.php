<div>
    @section('title')
        {{ __('Platform promotion') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Platform promotion') }}
        @endslot
    @endcomponent
    <div class="row">
        @include('layouts.flash-messages')
    </div>
    <div class="card">
        <div class="card-body row">
            <div class="col">
                <img src="{{ URL::asset($userProfileImage) }}?={{Str::random(16)}}"
                     class="rounded-circle avatar-xl img-thumbnail user-profile-image"
                     alt="user-profile-image">
            </div>
            <div class="col">
                {{getUserDisplayedName($user->idUser)}}
            </div>
            <div class="col">
                <a href="{{route('user_details', ['locale' => app()->getLocale(), 'idUser' => $user->id]) }}"
                   class=" float-end" target="_blank">{{__('More Details')}}</a>
            </div>
        </div>
        <div class="card-body">
            <ul class="list-group">
                @foreach($platforms as $platform)
                    <li class="list-group-item">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <img src="{{$platform->image_link}}" alt="" class="avatar-xs rounded-circle">
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <a href="{{$platform->link}}">{{$platform->name}}</a>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                @if($platform->administrative_manager_id)
                                    <span title="{{$platform->administrative_manager_id}}"
                                          class="btn btn-outline-secondary">{{__(\Core\Enum\Promotion::Administrative->name)}}</span>
                                @endif          @if($platform->financial_manager_id)
                                    <span title="{{$platform->financial_manager_id}}"
                                        class="btn btn-outline-info">{{__(\Core\Enum\Promotion::Financial->name)}}</span>
                                @endif
                            </div>
                            <div class="d-flex align-items-start gap-3 mt-4">
                                <span class="text-muted">{{__('Promote as')}} :</span>
                                <button type="button"
                                        class="btn btn-outline-secondary btn-label right ms-auto nexttab nexttab"
                                        wire:click="promote({{$user->id}},{{$platform->id}},{{\Core\Enum\Promotion::Administrative->value}})"
                                        data-nexttab="pills-info-desc-tab">
                                    <i class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>
                                    {{__('Administrative manager')}}
                                </button>
                                <button type="button"
                                        class="btn btn-outline-info btn-label right ms-auto nexttab nexttab"
                                        wire:click="promote({{$user->id}},{{$platform->id}},{{\Core\Enum\Promotion::Financial->value}})"
                                        data-nexttab="pills-info-desc-tab">
                                    <i class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>
                                    {{__('Financial manager')}}
                                </button>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

</div>
