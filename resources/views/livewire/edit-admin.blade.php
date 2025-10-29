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
        <div class="row card">
            <div class="card-header border-info">
                <div class="d-flex align-items-center">
                    <h6 class="card-title mb-0 flex-grow-1">{{ __('Role assign') }}</h6>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-12">
                                        <input type="text" class="form-control" placeholder="{{ __('PH_search') }}"
                                               wire:model.live="search"/>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-striped table-bordered tableEditAdmin">
                                    <thead>
                                    <tr>
                                        <th scope="Id">{{__('Id')}}</th>
                                        <th scope="Name">{{ __('Name') }}</th>
                                        <th scope="Number">{{ __('Mobile Number') }}</th>
                                        <th scope="role">{{ __('Role') }}</th>
                                        <th scope="Countrie">{{ __('Countrie') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($userRoles as $userRole)
                                        <tr>
                                            <td>{{$userRole->id}}</td>
                                            <td>
                                                @if($userRole->name)
                                                    {{$userRole->name}}
                                                @else
                                                    <span class="text-muted">{{__('Not filled')}}</span>
                                                @endif
                                            </td>
                                            <td>{{$userRole->mobile}}</td>
                                            <td>{{__($userRole->role)}}</td>
                                            <td>
                                                <img class="avatar-xxs me-2"
                                                     src="{{ Vite::asset("resources/images/flags/" . strtolower($userRole->apha2) . ".svg")}}">
                                                {{__($userRole->countrie)}}
                                            </td>
                                            <td>
                                                <button type="button" data-bs-toggle="modal"
                                                        data-bs-target="#editAdminModal"
                                                        wire:click="edit({{$userRole->id }})"
                                                        class="btn btn-outline-secondary">
                                                    {{ __('Edit') }}
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                {{$userRoles->links()}}
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
                            <div><label><span>{{__('userRole')}}:</span> <span>{{$name}}</span> </label></div>
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
                        <div class="form-group row mt-2 p-2">
                            <label>{{ __('Platforms') }}</label>
                            @foreach($platformes   as $key => $platform)
                                <div class="col-4 form-check form-switch form-switch-custom form-switch-primary mb-3 ">
                                    <input class="form-check-input" type="checkbox" role="switch"
                                           id="flexSwitchCheckDefault"
                                           wire:model="platformes.{{$key}}.is_selected">

                                    <label
                                        class="form-check-label font-weight-bold"> {{ __( $platform->name ) }}  </label>
                                </div>
                            @endforeach
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
