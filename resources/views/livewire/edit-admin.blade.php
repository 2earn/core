<div>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
    <div wire:loading>
    </div>
    <script data-turbolinks-eval="false">
        var exisUpdateRole = '{{Session::has('SuccesUpdateRole')}}';
        if (exisUpdateRole) {
            toastr.success('{{Session::get('SuccesUpdateRole')}}');
        }
    </script>
    @component('components.breadcrumb')
        @slot('li_1')  @endslot
        @slot('title')
            {{ __('Gestion des Administrateurs') }}
        @endslot
    @endcomponent
    <div class="row card">
        <div class="card-header border-info">
            <div class="d-flex align-items-center">
                <h6 class="card-title mb-0 flex-grow-1">{{ __('Gestion des Administrateurs') }}</h6>
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
                                           wire:model="search"/>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-striped table-bordered tableEditAdmin">
                                <thead>
                                <tr>
                                    <th scope="Id">Id</th>
                                    <th scope="Name">{{ __('Name') }}</th>
                                    <th scope="Francais">{{ __('Mobile Number') }}</th>
                                    <th scope="Arabe">id Countrie</th>
                                    <th scope="Francais">id Role</th>
                                    <th scope="Francais">{{ __('Role') }}</th>
                                    <th scope="Francais">{{ __('Countrie') }}</th>
                                    <th scope=" "></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($translates as $value)
                                    <tr>
                                        <td>{{$value->id}}</td>
                                        <td>{{$value->name}}</td>
                                        <td>{{$value->mobile}}</td>
                                        <td> {{$value->idCountry}}</td>
                                        <td>{{$value->idrole}}</td>
                                        <td>{{$value->role}}</td>
                                        <td>{{$value->countrie}}</td>
                                        <td>
                                            <button type="button" data-bs-toggle="modal"
                                                    data-bs-target="#editAdminModal" wire:click="edit({{$value->id }})"
                                                    class="btn btn-secondary">
                                                {{ __('Edit') }}
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{$translates->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="editAdminModal" tabindex="-1" role="dialog"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAdminModalLabel">{{ __('User_managment') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @php $role=\Illuminate\Support\Facades\Lang::get("userRole") @endphp
                    <div><label><span>{{$role}}:</span> <span>{{$name}}</span> </label></div>
                    <p>{{ __('Mobile_Number') }}: {{$mobile}}</p>
                    <label>{{ __('Role') }}</label>
                    <select class="form-control" id="Country" name="country" wire:model.defer="userRole">
                        <option value="">{{ __('Choose') }}</option>
                        @foreach($allRoles as $role)
                            @php
                                $cn = \Illuminate\Support\Facades\Lang::get($role->name) ;
                            @endphp
                            <option value="{{$role->name}}">{{$cn}}</option>
                        @endforeach
                    </select>
                    <div class="scheduler-border">
                        <div class="boxplatforms  d-flex">
                            @foreach($platformes   as $key => $setting)
                                <div class="">
                                    <label style="margin: 20px">
                                        <input class="toggle-checkbox" type="checkbox" role="switch"
                                               id="flexSwitchCheckDefault"
                                               wire:model.defer="platformes.{{$key}}.selected">
                                        <div class="toggle-switch"></div>
                                        <span class="toggle-label"> {{ __( $setting->name ) }}  </span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button wire:click="changeRole({{$currentId}})" type="button"
                            class="btn btn-primary">{{ __('Save_changes') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
