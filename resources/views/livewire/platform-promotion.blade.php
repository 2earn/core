<div class="{{getContainerType()}}">
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

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <img src="{{ URL::asset($userProfileImage) }}?={{Str::random(16)}}"
                         class="rounded-circle avatar-xl img-thumbnail shadow-sm"
                         alt="{{getUserDisplayedName($user->idUser)}}">
                    <div>
                        <h4 class="mb-1 fw-semibold">{{getUserDisplayedName($user->idUser)}}</h4>
                        <p class="text-muted mb-0">
                            <i class="ri-user-line align-middle me-1"></i>
                            {{__('User ID')}}: {{$user->id}}
                        </p>
                    </div>
                </div>
                <a href="{{route('user_details', ['locale' => app()->getLocale(), 'idUser' => $user->id]) }}"
                   class="btn btn-soft-primary"
                   target="_blank">
                    <i class="ri-information-line align-middle me-1"></i>
                    {{__('More Details')}}
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        @forelse($platforms as $platform)
            <div class="col-12 mb-3">
                <div class="card border shadow-none">
                    <div class="card-body">
                        {{-- Platform Header --}}
                        <div class="d-flex align-items-start mb-3">
                            <div class="flex-shrink-0">
                                @if ($platform?->logoImage)
                                    <img src="{{ asset('uploads/' . $platform->logoImage->url) }}"
                                         class="avatar-lg rounded-circle shadow-sm"
                                         alt="{{ $platform->name }}">
                                @else
                                    <img src="{{Vite::asset(\Core\Models\Platform::DEFAULT_IMAGE_TYPE_LOGO)}}"
                                         class="avatar-lg rounded-circle shadow-sm"
                                         alt="{{ $platform->name }}">
                                @endif
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="card-title mb-1 text-uppercase">
                                    <a href="{{$platform->link}}"
                                       class="text-decoration-none text-dark"
                                       target="_blank">
                                        {{ $platform->name }}
                                        <i class="ri-external-link-line fs-6 align-middle ms-1"></i>
                                    </a>
                                </h5>
                                <p class="text-muted mb-0 fs-6">{{__('Platform Management')}}</p>
                            </div>
                        </div>

                        {{-- Grant Roles Section --}}
                        @if($user->id!=$platform->marketing_manager_id||$user->id!=$platform->financial_manager_id||$user->id!=$platform->owner_id)
                            <div class="mb-3">
                                <p class="text-primary fs-6 fw-semibold mb-2">
                                    <i class="ri-user-add-line me-1"></i>{{__('Grant Role')}}
                                </p>
                                <div class="d-flex flex-wrap gap-2">
                                    @if($user->id!=$platform->marketing_manager_id)
                                        <button type="button"
                                                class="btn btn-outline-secondary btn-sm"
                                                wire:click="grantRole({{$user->id}},{{$platform->id}},{{\Core\Enum\Promotion::Marketing->value}})">
                                            <i class="ri-megaphone-line align-middle me-1"></i>
                                            {{__('Administrative')}}
                                        </button>
                                    @endif
                                    @if($user->id!=$platform->financial_manager_id)
                                        <button type="button"
                                                class="btn btn-outline-info btn-sm"
                                                wire:click="grantRole({{$user->id}},{{$platform->id}},{{\Core\Enum\Promotion::Financial->value}})">
                                            <i class="ri-money-dollar-circle-line align-middle me-1"></i>
                                            {{__('Financial')}}
                                        </button>
                                    @endif
                                    @if($user->id!=$platform->owner_id)
                                        <button type="button"
                                                class="btn btn-outline-primary btn-sm"
                                                wire:click="grantRole({{$user->id}},{{$platform->id}},{{\Core\Enum\Promotion::Owner->value}})">
                                            <i class="ri-vip-crown-line align-middle me-1"></i>
                                            {{__('Owner')}}
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{-- Current Roles Section --}}
                        @if($user->id==$platform->marketing_manager_id||$user->id==$platform->financial_manager_id||$user->id==$platform->owner_id)
                            <div>
                                <p class="text-primary fs-6 fw-semibold mb-2">
                                    <i class="ri-shield-user-line me-1"></i>{{__('Current Roles')}}
                                </p>
                                <div class="row g-2">
                                    @if($platform->marketing_manager_id)
                                        <div class="col-md-4">
                                            <div class="p-3 bg-secondary-subtle rounded">
                                                <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <span class="badge bg-secondary text-white fs-6">
                                                        <i class="ri-megaphone-line me-1"></i>
                                                        {{__(\Core\Enum\Promotion::Marketing->name)}}
                                                    </span>
                                                    @if($user->id==$platform->marketing_manager_id)
                                                        <span class="badge bg-success-subtle text-success">
                                                            <i class="ri-checkbox-circle-line me-1"></i>{{__('You')}}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-light text-muted">{{__('Other user')}}</span>
                                                    @endif
                                                </div>
                                                @if($user->id==$platform->marketing_manager_id)
                                                    <button type="button"
                                                            class="btn btn-soft-danger btn-sm w-100"
                                                            wire:click="revokeRole({{$platform->id}},{{\Core\Enum\Promotion::Marketing->value}})">
                                                        <i class="ri-close-circle-line me-1"></i>
                                                        {{__('Revoke')}}
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    @if($platform->financial_manager_id)
                                        <div class="col-md-4">
                                            <div class="p-3 bg-info-subtle rounded">
                                                <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <span class="badge bg-info text-white fs-6">
                                                        <i class="ri-money-dollar-circle-line me-1"></i>
                                                        {{__(\Core\Enum\Promotion::Financial->name)}}
                                                    </span>
                                                    @if($user->id==$platform->financial_manager_id)
                                                        <span class="badge bg-success-subtle text-success">
                                                            <i class="ri-checkbox-circle-line me-1"></i>{{__('You')}}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-light text-muted">{{__('Other user')}}</span>
                                                    @endif
                                                </div>
                                                @if($user->id==$platform->financial_manager_id)
                                                    <button type="button"
                                                            class="btn btn-soft-danger btn-sm w-100"
                                                            wire:click="revokeRole({{$platform->id}},{{\Core\Enum\Promotion::Financial->value}})">
                                                        <i class="ri-close-circle-line me-1"></i>
                                                        {{__('Revoke')}}
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    @if($platform->owner_id)
                                        <div class="col-md-4">
                                            <div class="p-3 bg-primary-subtle rounded">
                                                <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <span class="badge bg-primary text-white fs-6">
                                                        <i class="ri-vip-crown-line me-1"></i>
                                                        {{__(\Core\Enum\Promotion::Owner->name)}}
                                                    </span>
                                                    @if($user->id==$platform->owner_id)
                                                        <span class="badge bg-success-subtle text-success">
                                                            <i class="ri-checkbox-circle-line me-1"></i>{{__('You')}}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-light text-muted">{{__('Other user')}}</span>
                                                    @endif
                                                </div>
                                                @if($user->id==$platform->owner_id)
                                                    <button type="button"
                                                            class="btn btn-soft-danger btn-sm w-100"
                                                            wire:click="revokeRole({{$platform->id}},{{\Core\Enum\Promotion::Owner->value}})">
                                                        <i class="ri-close-circle-line me-1"></i>
                                                        {{__('Revoke')}}
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="ri-apps-line fs-1 text-muted"></i>
                    <p class="text-muted mt-3">{{__('No platforms available')}}</p>
                </div>
            </div>
        @endforelse
    </div>

</div>
