<div class="container">
    @section('title')
        {{ __('Partners') }}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Partners') }}
        @endslot
    @endcomponent
    <div class="row">
        @include('layouts.flash-messages')
        <div class="col-12 card">
            <div class="card-body">
                <div class="card-header border-info">
                    <div class="row">
                        <div class="float-end col-sm-12 col-md-6 col-lg-6">
                            <form class="items-center">
                                <label for="simple-search" class="sr-only">{{__('Search')}}</label>
                                <div class="w-full">
                                    <input wire:model.live="search" type="text" id="simple-search"
                                           class="form-control float-end"
                                           placeholder="{{__('Search partner')}}">
                                </div>
                            </form>
                        </div>
                        @if(\App\Models\User::isSuperAdmin())
                            <div class="col-sm-12 col-md-3  col-lg-6">
                                <a href="{{route('partner_create', app()->getLocale())}}"
                                   class="btn btn-outline-info add-btn float-end"
                                   id="create-btn">
                                    {{__('Create new partner')}}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card-body row">
                    <div class="col-12 mb-2 d-flex justify-content-between align-items-center">
                        <div class="small text-muted">{{ __('Showing') }} {{ $partners->count() }}
                            / {{ $partners->total() }} {{ __('partners') }}</div>
                        <div></div>
                    </div>

                    @if($partners->count())
                        <div class="col-12">
                            <div class="row g-3">
                                @foreach($partners as $partner)
                                    <div class="col-12">
                                        <div class="card border mb-0 shadow-sm hover-shadow">
                                            <div class="card-body">
                                                <div class="row align-items-center">
                                                    <div class="col-lg-8">
                                                        <div class="d-flex align-items-start">
                                                            <div class="flex-shrink-0">
                                                                <div class="avatar-sm">
                                                                    <div class="avatar-title bg-soft-primary text-primary rounded">
                                                                        <i class="ri-building-line fs-20"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="flex-grow-1 ms-3">
                                                                <h5 class="fs-16 mb-1">
                                                                    <a href="{{route('partner_show',['locale'=> app()->getLocale(),'id'=>$partner->id])}}"
                                                                       class="text-dark">
                                                                        {{ $partner->company_name }}
                                                                    </a>
                                                                </h5>
                                                                <div class="d-flex flex-wrap gap-2 align-items-center mb-2">
                                                                    @if($partner->businessSector)
                                                                        <span class="badge bg-soft-info text-info">
                                                                            <i class="ri-price-tag-3-line me-1"></i>
                                                                            {{ $partner->businessSector->name }}
                                                                        </span>
                                                                    @endif
                                                                    <span class="text-muted">
                                                                        <i class="ri-calendar-line me-1"></i>
                                                                        {{ $partner->created_at->format('Y-m-d H:i') }}
                                                                    </span>
                                                                </div>
                                                                @if($partner->platform_url)
                                                                    <div class="text-muted">
                                                                        <i class="ri-links-line me-1"></i>
                                                                        <a href="{{ $partner->platform_url }}" target="_blank"
                                                                           class="text-primary" title="{{__('Visit website')}}">
                                                                            {{ Str::limit($partner->platform_url, 50) }}
                                                                            <i class="ri-external-link-line ms-1"></i>
                                                                        </a>
                                                                    </div>
                                                                @endif
                                                                @if($partner->platform_description)
                                                                    <p class="text-muted mb-0 mt-2">
                                                                        {{ Str::limit($partner->platform_description, 150) }}
                                                                    </p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if(\App\Models\User::isSuperAdmin())
                                                        <div class="col-lg-4">
                                                            <div class="d-flex flex-wrap gap-2 justify-content-lg-end mt-3 mt-lg-0">
                                                                <a href="{{route('partner_show',['locale'=> app()->getLocale(),'id'=>$partner->id])}}"
                                                                   class="btn btn-soft-info btn-sm"
                                                                   title="{{__('View Details')}}">
                                                                    <i class="ri-eye-line me-1"></i>
                                                                    {{__('View')}}
                                                                </a>
                                                                <a href="{{route('partner_roles',['locale'=> app()->getLocale(),'partnerId'=>$partner->id])}}"
                                                                   class="btn btn-soft-primary btn-sm"
                                                                   title="{{__('Manage Roles')}}">
                                                                    <i class="ri-shield-user-line me-1"></i>
                                                                    {{__('Manage Roles')}}
                                                                </a>
                                                                <a href="{{route('partner_update',['locale'=> app()->getLocale(),'id'=>$partner->id])}}"
                                                                   class="btn btn-soft-primary btn-sm"
                                                                   title="{{__('Edit')}}">
                                                                    <i class="ri-edit-line me-1"></i>
                                                                    {{__('Edit')}}
                                                                </a>
                                                                <button wire:click="deletePartner('{{ $partner->id }}')"
                                                                        class="btn btn-soft-danger btn-sm"
                                                                        title="{{__('Delete Partner')}}"
                                                                        onclick="return confirm('{{__('Are you sure you want to delete this partner?')}}')">
                                                                    <i class="ri-delete-bin-line me-1"></i>
                                                                    {{__('Delete')}}
                                                                    <span wire:loading wire:target="deletePartner('{{ $partner->id }}')"
                                                                          class="spinner-border spinner-border-sm ms-1"
                                                                          role="status" aria-hidden="true"></span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-12 mt-3">{{ $partners->links() }}</div>
                    @else
                        <div class="col-12 py-5 text-center">
                            <i class="ri-team-line display-4 text-muted mb-3"></i>
                            <h5 class="text-muted">{{ __('No partners') }}</h5>
                            <p class="text-muted">{{ __('There are no partners yet.') }}</p>
                            @if(\App\Models\User::isSuperAdmin())
                                <p class="text-muted">{{ __('Use the "Create new partner" button above to add partners.') }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
