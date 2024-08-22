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
        <div class="col-xl-4">
            <div class="card ribbon-box border shadow-none mb-lg-0 material-shadow">
                <div class="card-body">
                    <div class="ribbon ribbon-primary round-shape">[{{$user['idUser']}}]</div>
                    <h5 class="fs-14 text-end">{{__(\Core\Enum\StatusRequest::from($user->status)->name)}}</h5>
                    <div class="text-center">
                        <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                            <img
                                src="@if (file_exists('uploads/profiles/profile-image-' . $user['idUser'] . '.png')) {{ URL::asset('uploads/profiles/profile-image-'.$user['idUser'].'.png') }}?={{Str::random(16)}} @else{{ URL::asset('uploads/profiles/default.png') }} @endif"
                                class="  rounded-circle avatar-xl img-thumbnail user-profile-image"
                                alt="user-profile-image">
                        </div>
                        <h2>
                            {{$dispalyedUserCred}}
                        </h2>
                        <h4>
                            <span class="badge text-bg-secondary"></span>
                        </h4>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-2 text-info">{{ __('National identities cards') }}</h5>
                </div>
                <div class="card-body row">
                    <div class="col-12">
                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                                <td>   @if(file_exists(public_path('/uploads/profiles/front-id-image'.$user->idUser.'.png')))
                                        <img class="img-thumbnail" width="150" height="100" id="front-id-image"
                                             title="{{__('Front id image')}}"
                                             src="{{asset(('/uploads/profiles/front-id-image'.$user->idUser.'.png'))}}?={{Str::random(16)}}">
                                    @else
                                        <div class="alert alert-warning" role="alert">
                                            {{__('No image uploaded')}}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if(file_exists(public_path('/uploads/profiles/back-id-image'.$user->idUser.'.png')))
                                        <img class="img-thumbnail" width="150" height="100" id="back-id-image"
                                             title="{{__('Back id image')}}"
                                             src="{{asset(('/uploads/profiles/back-id-image'.$user->idUser.'.png'))}}?={{Str::random(16)}}">
                                    @else
                                        <div class="alert alert-warning" role="alert">
                                            {{__('No image uploaded')}}
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-2 text-info">{{ __('International identity card') }}</h5>
                </div>
                <div class="card-body row">
                    <div class="col-12">
                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                                <th scope="row">{{ __('Identity card') }}</th>
                                <td>
                                    @if(file_exists(public_path('/uploads/profiles/international-id-image'.$user->idUser.'.png')))
                                        <img class="img-thumbnail" width="150" height="100"
                                             id="international-id-image"
                                             title="{{__('International identity card')}}"
                                             src="{{asset(('/uploads/profiles/international-id-image'.$user->idUser.'.png'))}}?={{Str::random(16)}}">
                                    @else
                                        <div class="alert alert-warning" role="alert">
                                            {{__('No image uploaded')}}
                                        </div>
                                    @endif
                                </td>
                            <tr>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-xl-4">
            <div class="card ribbon-box border shadow-none mb-lg-0 material-shadow">
                <div class="card-body">
                    <div class="ribbon ribbon-primary round-shape">{{__('General data')}}</div>
                    <h5 class="fs-14 text-end">{{__(\Core\Enum\StatusRequest::from($user->status)->name)}}</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            {{__('mobile')}}: {{$user->mobile}}</li>
                        <li class="list-group-item">
                            {{__('email_verified')}}:
                            <span class="badge badge-info">
                                {{$user->email_verified}}?'TRUE':'FALSE'}}</span>
                        </li>
                        <li class="list-group-item">
                            {{__('fullphone_number')}}: {{$user->fullphone_number}}
                        </li>
                        <li class="list-group-item">
                            {{__('iden_notif')}}: {{$user->iden_notif}}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
