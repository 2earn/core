<div>
    @section('title')
        {{ __('User Details') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('User Details') }}
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-xxl-4">
            <div class="card  ">
                <div class="card-body p-4">
                    <div class="text-center">
                        <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                            <div wire:loading wire:target="imageProfil">{{__('Uploading')}}...</div>
                            <img
                                src="@if (file_exists('uploads/profiles/profile-image-' . $user['idUser'] . '.png')) {{ URL::asset('uploads/profiles/profile-image-'.$user['idUser'].'.png') }}?={{Str::random(16)}} @else{{ URL::asset('uploads/profiles/default.png') }} @endif"
                                class="  rounded-circle avatar-xl img-thumbnail user-profile-image"
                                alt="user-profile-image">
                            <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                <input id="profile-img-file-input" type="file" class="profile-img-file-input"
                                       accept="image/png"
                                       wire:model="imageProfil">
                                <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                    <span class="avatar-title rounded-circle bg-light text-body">
                                        <i class="ri-camera-fill"></i>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <h2>
                            {{$dispalyedUserCred}}
                        </h2>
                        <h4>
                            <span class="badge text-bg-secondary">[{{$user['idUser']}}]</span>
                        </h4>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-xl-12">
            <table class="table">
                <thead class="thead-light">
                <tr>
                    <th scope="col">{{__('property')}}</th>
                    <th scope="col">{{__('value')}}</th>

                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>{{__('mobile')}}</td>
                    <td>{{$user->mobile}}</td>
                </tr>
                <tr>
                    <td>{{__('status')}}</td>
                    <td>{{$user->status}}</td>
                </tr>
                <tr>
                    <td>{{__('mobile')}}</td>
                    <td>{{$user->mobile}}</td>
                </tr>
                <tr>
                    <td>{{__('email_verified')}}</td>
                    <td>{{$user->email_verified}}</td>
                </tr>
                <tr>
                    <td>{{__('fullphone_number')}}</td>
                    <td>{{$user->fullphone_number}}</td>
                </tr>
                <tr>
                    <td>{{__('iden_notif')}}</td>
                    <td>{{$user->iden_notif}}</td>
                </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>
