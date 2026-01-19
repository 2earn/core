<div class="container">
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
    <div class="row">
        <div class="col-12 card shadow-sm border-0 mb-4">
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
    </div>
    <div class="row">
        @forelse($platforms as $platform)
            <div class="col-12 card border shadow-none">
                <div class="card-body">

                    <div class="d-flex align-items-start mb-3">
                        <div class="flex-shrink-0">
                            @if ($platform?->logoImage)
                                <img src="{{ asset('uploads/' . $platform->logoImage->url) }}"
                                     class="avatar-lg rounded-circle shadow-sm"
                                     alt="{{ $platform->name }}">
                            @else
                                <img src="{{Vite::asset(\App\Models\Platform::DEFAULT_IMAGE_TYPE_LOGO)}}"
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


                    @php
                        $hasMarketingRole = isset($platform->entityRoles['marketing_manager']) && $platform->entityRoles['marketing_manager']->user_id == $user->id;
                        $hasFinancialRole = isset($platform->entityRoles['financial_manager']) && $platform->entityRoles['financial_manager']->user_id == $user->id;
                        $hasOwnerRole = isset($platform->entityRoles['owner']) && $platform->entityRoles['owner']->user_id == $user->id;
                        $hasAnyRole = $hasMarketingRole || $hasFinancialRole || $hasOwnerRole;
                    @endphp

                    @if(!$hasMarketingRole || !$hasFinancialRole || !$hasOwnerRole)
                        <div class="mb-3">
                            <p class="text-primary fs-6 fw-semibold mb-2">
                                <i class="ri-user-add-line me-1"></i>{{__('Grant Role')}}
                            </p>
                            <div class="d-flex flex-wrap gap-2">
                                @if(!$hasMarketingRole)
                                    <button type="button"
                                            class="btn btn-outline-secondary btn-sm"
                                            wire:click="grantRole({{$user->id}},{{$platform->id}},{{\App\Enums\Promotion::Marketing->value}})">
                                        <i class="ri-megaphone-line align-middle me-1"></i>
                                        {{__('Administrative')}}
                                    </button>
                                @endif
                                @if(!$hasFinancialRole)
                                    <button type="button"
                                            class="btn btn-outline-info btn-sm"
                                            wire:click="grantRole({{$user->id}},{{$platform->id}},{{\App\Enums\Promotion::Financial->value}})">
                                        <i class="ri-money-dollar-circle-line align-middle me-1"></i>
                                        {{__('Financial')}}
                                    </button>
                                @endif
                                @if(!$hasOwnerRole)
                                    <button type="button"
                                            class="btn btn-outline-primary btn-sm"
                                            wire:click="grantRole({{$user->id}},{{$platform->id}},{{\App\Enums\Promotion::Owner->value}})">
                                        <i class="ri-vip-crown-line align-middle me-1"></i>
                                        {{__('Owner')}}
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endif


                    @if($hasAnyRole)
                        <div>
                            <p class="text-primary fs-6 fw-semibold mb-2">
                                <i class="ri-shield-user-line me-1"></i>{{__('Current Roles')}}
                            </p>
                            <div class="row g-2">
                                @if(isset($platform->entityRoles['marketing_manager']))
                                    @php
                                        $marketingManagerId = $platform->entityRoles['marketing_manager']->user_id;
                                    @endphp
                                    <div class="col-md-4">
                                        <div class="p-3 bg-secondary-subtle rounded">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <span class="badge bg-secondary text-white fs-6">
                                                        <i class="ri-megaphone-line me-1"></i>
                                                        {{__(\App\Enums\Promotion::Marketing->name)}}
                                                    </span>
                                                @if($user->id == $marketingManagerId)
                                                    <span class="badge bg-success-subtle text-success">
                                                            <i class="ri-checkbox-circle-line me-1"></i>{{__('You')}}
                                                        </span>
                                                @else
                                                    <span
                                                        class="badge bg-light text-muted">{{__('Other user')}}</span>
                                                @endif
                                            </div>
                                            @if($user->id == $marketingManagerId)
                                                <button type="button"
                                                        class="btn btn-soft-danger btn-sm w-100"
                                                        wire:click="revokeRole({{$platform->id}},{{\App\Enums\Promotion::Marketing->value}})">
                                                    <i class="ri-close-circle-line me-1"></i>
                                                    {{__('Revoke')}}
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                @if(isset($platform->entityRoles['financial_manager']))
                                    @php
                                        $financialManagerId = $platform->entityRoles['financial_manager']->user_id;
                                    @endphp
                                    <div class="col-md-4">
                                        <div class="p-3 bg-info-subtle rounded">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <span class="badge bg-info text-white fs-6">
                                                        <i class="ri-money-dollar-circle-line me-1"></i>
                                                        {{__(\App\Enums\Promotion::Financial->name)}}
                                                    </span>
                                                @if($user->id == $financialManagerId)
                                                    <span class="badge bg-success-subtle text-success">
                                                            <i class="ri-checkbox-circle-line me-1"></i>{{__('You')}}
                                                        </span>
                                                @else
                                                    <span
                                                        class="badge bg-light text-muted">{{__('Other user')}}</span>
                                                @endif
                                            </div>
                                            @if($user->id == $financialManagerId)
                                                <button type="button"
                                                        class="btn btn-soft-danger btn-sm w-100"
                                                        wire:click="revokeRole({{$platform->id}},{{\App\Enums\Promotion::Financial->value}})">
                                                    <i class="ri-close-circle-line me-1"></i>
                                                    {{__('Revoke')}}
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                @if(isset($platform->entityRoles['owner']))
                                    @php
                                        $ownerId = $platform->entityRoles['owner']->user_id;
                                    @endphp
                                    <div class="col-md-4">
                                        <div class="p-3 bg-primary-subtle rounded">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <span class="badge bg-primary text-white fs-6">
                                                        <i class="ri-vip-crown-line me-1"></i>
                                                        {{__(\App\Enums\Promotion::Owner->name)}}
                                                    </span>
                                                @if($user->id == $ownerId)
                                                    <span class="badge bg-success-subtle text-success">
                                                            <i class="ri-checkbox-circle-line me-1"></i>{{__('You')}}
                                                        </span>
                                                @else
                                                    <span
                                                        class="badge bg-light text-muted">{{__('Other user')}}</span>
                                                @endif
                                            </div>
                                            @if($user->id == $ownerId)
                                                <button type="button"
                                                        class="btn btn-soft-danger btn-sm w-100"
                                                        wire:click="revokeRole({{$platform->id}},{{\App\Enums\Promotion::Owner->value}})">
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
