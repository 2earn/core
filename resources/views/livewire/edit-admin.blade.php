<div class="{{getContainerType()}}">
    <div>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
        <div wire:loading>
        </div>

        @component('components.breadcrumb')
            @slot('li_1')  @endslot
            @slot('title')
                {{ __('Role assign') }}
            @endslot
        @endcomponent
        <div class="row">
            @include('layouts.flash-messages')
        </div>
        <div class="row">
            <div class="col-12 card">
                <div class="card-header border-info">
                    <div class="d-flex align-items-center">
                        <h6 class="card-title mb-0 flex-grow-1">{{ __('Role assign') }}</h6>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="card ra-tazzy-border shadow-sm">
                                <div class="card-header bg-light border-bottom">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="input-group">
                                                <span class="input-group-text bg-white border-end-0">
                                                    <i class="ri-search-line"></i>
                                                </span>
                                                <input type="text" class="form-control border-start-0 ps-0"
                                                       placeholder="{{ __('PH_search') }}"
                                                       wire:model.live="search"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <!-- Header Row -->
                                    <div class="d-none d-md-block bg-light border-bottom">
                                        <div class="row fw-semibold text-muted py-3 mx-0 text-uppercase small">
                                            <div class="col-md-1 ps-4">{{__('Id')}}</div>
                                            <div class="col-md-2">{{ __('Name') }}</div>
                                            <div class="col-md-2">{{ __('Mobile Number') }}</div>
                                            <div class="col-md-2">{{ __('Role') }}</div>
                                            <div class="col-md-2">{{ __('Countrie') }}</div>
                                            <div class="col-md-3">{{ __('Action') }}</div>
                                        </div>
                                    </div>

                                    <!-- Data Rows -->
                                    @foreach ($userRoles as $userRole)
                                        <div class="row border-bottom py-3 mx-0 align-items-center user-row-hover">
                                            <div class="col-md-1 col-12 ps-4">
                                                <span class="d-md-none fw-bold text-muted small">{{__('Id')}}: </span>
                                                <span class="badge bg-soft-primary text-primary rounded-pill">
                                                    #{{$userRole->id}}
                                                </span>
                                            </div>
                                            <div class="col-md-2 col-12 mt-2 mt-md-0">
                                                <span class="d-md-none fw-bold text-muted small d-block mb-1">{{ __('Name') }}: </span>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-xs me-2">
                                                        <div class="avatar-title bg-soft-danger text-danger rounded-circle">
                                                            {{strtoupper(substr(getUserDisplayedName($userRole->idUser), 0, 1))}}
                                                        </div>
                                                    </div>
                                                    <span class="fw-medium">{{getUserDisplayedName($userRole->idUser)}}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-12 mt-2 mt-md-0">
                                                <span class="d-md-none fw-bold text-muted small d-block mb-1">{{ __('Mobile Number') }}: </span>
                                                <span class="text-muted">
                                                    <i class="ri-phone-line me-1"></i>{{$userRole->mobile}}
                                                </span>
                                            </div>
                                            <div class="col-md-2 col-12 mt-2 mt-md-0">
                                                <span class="d-md-none fw-bold text-muted small d-block mb-1">{{ __('Role') }}: </span>
                                                <span class="badge bg-soft-info text-info px-3 py-2 rounded-3">
                                                    {{__($userRole->role)}}
                                                </span>
                                            </div>
                                            <div class="col-md-2 col-12 mt-2 mt-md-0">
                                                <span class="d-md-none fw-bold text-muted small d-block mb-1">{{ __('Countrie') }}: </span>
                                                <div class="d-flex align-items-center">
                                                    <img class="avatar-xxs me-2 rounded" style="width: 20px; height: 15px; object-fit: cover;"
                                                         src="{{ Vite::asset("resources/images/flags/" . strtolower($userRole->apha2) . ".svg")}}"
                                                         alt="{{$userRole->countrie}}">
                                                    <span class="text-muted">{{__($userRole->countrie)}}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-12 mt-3 mt-md-0">
                                                <button type="button" data-bs-toggle="modal"
                                                        data-bs-target="#editAdminModal"
                                                        wire:click="edit({{$userRole->id }})"
                                                        class="btn btn-soft-primary btn-sm w-100 w-md-auto rounded-3">
                                                    <i class="ri-edit-2-line me-1"></i>{{ __('Edit') }}
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach

                                    <!-- Empty State -->
                                    @if($userRoles->isEmpty())
                                        <div class="text-center py-5">
                                            <i class="ri-user-search-line display-4 text-muted"></i>
                                            <p class="text-muted mt-3">{{ __('No users found') }}</p>
                                        </div>
                                    @endif

                                    <!-- Pagination -->
                                    <div class="p-3 bg-light border-top">
                                        {{$userRoles->links()}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div wire:ignore.self class="modal fade bd-example-modal-lg" id="editAdminModal" tabindex="-1" role="dialog"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered  modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editAdminModalLabel">{{ __('Role assign') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div><label><span>{{__('User')}}:</span> <span>{{$name}}</span> </label></div>
                            <p>{{ __('Mobile_Number') }}: {{$mobile}}</p>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Role') }}</label>
                            <select class="form-control" id="Country" name="country" wire:model="userRole">
                                <option value="">{{ __('Choose') }}</option>
                                @foreach($allRoles as $role)
                                    <option value="{{$role->name}}">{{__($role->name)}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button wire:click="changeRole({{$currentId}})" type="button"
                                class="btn btn-primary">{{ __('Save') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
