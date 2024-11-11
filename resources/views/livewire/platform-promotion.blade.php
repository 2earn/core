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
                        <div class="row">
                            <div class="col-sm-12 col-md-3 col-lg-2">
                                <img src="{{$platform->image_link}}" alt="" class="avatar-xs rounded-circle">

                                <a href="{{$platform->link}}"><h5>{{ strtoupper($platform->name) }}</h5></a>
                            </div>
                            <div class="col-sm-12 col-md-3 col-lg-3" title="{{$platform->administrative_manager_id}}">
                                @if($platform->administrative_manager_id)
                                    <span class="badge bg-primary-subtle text-primary badge-border">
                                        @if($user->id==$platform->administrative_manager_id)
                                            {{__('This is')}}
                                        @else
                                            {{__('An other user user is the')}}
                                        @endif
                                        {{__(\Core\Enum\Promotion::Administrative->name)}}
                                    </span>
                                    @if($user->id==$platform->administrative_manager_id)
                                        <button type="button"
                                                class="btn btn-outline-danger btn-label right ms-auto"
                                                wire:click="revoqueRole({{$platform->id}},{{\Core\Enum\Promotion::Administrative->value}})">
                                            {{__('Revoque')}}
                                        </button>
                                    @endif
                                @endif
                            </div>
                            <div class="col-sm-12 col-md-3 col-lg-3" title="{{$platform->financial_manager_id}}">

                                @if($platform->financial_manager_id)
                                    <span class="badge bg-info-subtle text-info badge-border">
                                        @if($user->id==$platform->financial_manager_id)
                                            {{__('This is')}}
                                        @else
                                            {{__('An other user user is the')}}
                                        @endif
                                        {{__(\Core\Enum\Promotion::Financial->name)}}
                                    </span>
                                    @if($user->id==$platform->financial_manager_id)
                                        <button type="button"
                                                class="btn btn-outline-danger btn-label right ms-auto"
                                                wire:click="revoqueRole({{$platform->id}},{{\Core\Enum\Promotion::Financial->value}})">
                                            {{__('Revoque')}}
                                        </button>
                                    @endif
                                @endif
                            </div>
                            <div class="col-sm-12 col-md-5 col-lg-3">
                                <span
                                    class="text-muted">{{__('Promote as Manager')}} :</span>
                                <br>
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    @if($user->id!=$platform->administrative_manager_id)
                                        <button type="button"
                                                class="btn btn-outline-secondary btn-label right ms-auto"
                                                wire:click="grantRole({{$user->id}},{{$platform->id}},{{\Core\Enum\Promotion::Administrative->value}})"
                                        >
                                            {{__('Administrative')}}
                                        </button>
                                    @endif

                                    @if($user->id!=$platform->financial_manager_id)
                                        <button type="button"
                                                class="btn btn-outline-info btn-label right ms-auto"
                                                wire:click="grantRole({{$user->id}},{{$platform->id}},{{\Core\Enum\Promotion::Financial->value}})"
                                        >
                                            {{__('Financial')}}
                                        </button>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

</div>
