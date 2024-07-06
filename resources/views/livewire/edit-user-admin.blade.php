<div>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
    <script data-turbolinks-eval="false">
        var exist = '{{Session::has('ErrorUpdatePhone')}}';
        if (exist){
            Swal.fire({
                title:'{{trans('user_exsit')}}',
                text: '{{Session::get('ErrorUpdatePhone')}}',
                icon: 'error',
                confirmButtonText: '{{trans('ok')}}'
            }).then(okay => {
                if (okay) {
                    var tabChangePhone= document.querySelector('#pills-telchange-tab');
                    var tab = new bootstrap.Tab(tabChangePhone);
                    tab.show();
                }
            });
        }
        var SuccesUpdateProfil = '{{ Session::has('SuccesUpdateProfil')}}'
        if (SuccesUpdateProfil) {
            toastr.success('{{Session::get('SuccesUpdateProfil')}}');
        }

    </script>
    <div class="row">
        <div class="col">
            <div class="card">
                <ul class="nav nav-pills d-flex justify-content-start " id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a style="border: solid 1px #3595f6" href="#pills-profil tabBorder"
                           class="nav-link active"
                           id="pills-profil-tab"
                           data-bs-toggle="pill"
                           data-bs-target="#pills-profil"
                           type="button">{{__('Edit_Profile')}}</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a style="border: solid 1px #3595f6" href="#pills-ident tabBorder" class="nav-link " id="pills-ident-tab" data-bs-toggle="pill"
                           data-bs-target="#pills-ident" type="button"
                        >تحديد الهوية</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a style="border: solid 1px #3595f6" href="#pills-sec tabBorder" class="nav-link " id="pills-sec-tab" data-bs-toggle="pill"
                           data-bs-target="#pills-sec" type="button" role="tab" aria-controls="pills-sec"
                           aria-selected="false">الأمن</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a style="border: solid 1px #3595f6" href="#pills-telchange tabBorder" class="nav-link " id="pills-telchange-tab" data-bs-toggle="pill"
                           data-bs-target="#pills-telchange" type="button" role="tab"
                           aria-controls="pills-telchange"
                           aria-selected="false">تحديث رقم الهاتف</a>
                    </li>
                </ul>
                <div wire:ignore class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-profil" role="tabpanel"
                         aria-labelledby="pills-profil-tab">
                        <form action="" wire:submit.prevent="saveUser" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-12" style="">
                                    <div class="row mb-3">
                                        <div class="col-xl-6">
                                            <div class="field field_v2">
                                                <label for="first-name" class="ha-screen-reader">اللقب</label>
                                                <input id="first-name" class="field__input" placeholder=" "
                                                       wire:model.defer="userInfo.UserLast">

                                                <span class="field__label-wrap" aria-hidden="true">
                                    <span class="field__label">اللقب</span>
                                </span>
                                            </div>
                                        </div>
                                        <div class=" col-xl-6">
                                            <div class="field field_v2">
                                                <label for="first-name" class="ha-screen-reader">الاسم</label>
                                                <input id="first-name" class="field__input" placeholder=" "
                                                       wire:model.defer="userInfo.UserFirst">
                                                <span class="field__label-wrap" aria-hidden="true">
                                    <span class="field__label">الاسم</span>
                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-3 col-xl-6">
                                            <div class="field field_v2">
                                                <label for="first-name" class="ha-screen-reader">Your last name</label>
                                                <input id="first-name" class="field__input" placeholder=" "
                                                       wire:model.defer="userInfo.UserLA">
                                                <span class="field__label-wrap" aria-hidden="true">
                                    <span class="field__label">Your last name</span>
                                </span>
                                            </div>
                                        </div>
                                        <div class=" col-xl-6">
                                            <div class="field field_v2">
                                                <label for="first-name" class="ha-screen-reader">Your first name</label>
                                                <input id="first-name" class="field__input" placeholder=""
                                                       wire:model.defer="userInfo.User">
                                                <span class="field__label-wrap" aria-hidden="true">
                                    <span class="field__label">Your first name</span>
                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3 col-xl-6 mt-3">
                                    @if( isset($userearn) &&  $userearn->email_verified == 0)
                                        <label class="me-sm-2">{{ __('Your Email') }}</label><span id="span_verif"
                                                                                                   style="color:#FF788E !important">{{ __('EmailNotVerified')}}</span>
                                        <div class="row">
                                            <div class="col-xl-10">
                                                <input type="email" class="form-control" placeholder="Email"
                                                       wire:model.defer="userInfo.Email" name="">
                                                {{--                                                       <?php if($user['status'] == -1){ ?> disabled <?php }?> >--}}
                                            </div>
                                            <div class="col-xl-2" style="padding: 0px;margin-top: 2%;">
                                                <a id="validate" style="cursor: pointer;">
                                                    <span class="badge bg-success"
                                                          style="border: none; min-width: 30px !important;">Validate</span>
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <label class="me-sm-2">{{ __('Your Email') }}</label><span
                                                style="color:#89e8ba !important">{{__('EmailVerified')}}</span>
                                        <div class="row">
                                            <div class="col-xl-10">
                                                <input type="email" class="form-control" placeholder="Email"
                                                       name="email"
                                                       wire:model.defer="userInfo.Email"

                                                       <?php if(1 == 1){ ?> disabled <?php }?> >
                                            </div>
                                            <div class="col-xl-2" style="padding: 0px;margin-top: 2%;">
                                                <a id="validate" style="cursor: pointer;">
                                                    <span class="badge badge-warning"
                                                          style="border: none; min-width: 30px !important;">{{__('Change')}}</span>
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="mb-3 col-xl-6 mt-3">
                                    <label class="me-sm-2">{{ __('Your Contact number') }}</label>
                                    <div class="row">
                                        <div class="col-xl-10">
                                            <input type="text" class="form-control" placeholder="Contact number"
                                                   name="secondEmail"
                                                   disabled>
                                        </div>
                                        @if(1 == 1)
                                            <div class="col-xl-2" style="padding: 0px;margin-top: 2%;">
                                                <a id="update_tel" style="cursor: pointer;" data-toggle="modal"
                                                   data-target="#modalupdate_tel">
                                                <span class="badge bg-success"
                                                      style="border: none; min-width: 30px !important;">add</span>
                                                </a>
                                            </div>
                                        @else
                                            <div class="col-xl-2" style="padding: 0px;margin-top: 2%;">
                                                <a id="update_tel" style="cursor: pointer;" data-toggle="modal"
                                                   data-target="#modalupdate_tel">
                                                    <span class="badge badge-warning"
                                                          style="border: none; min-width: 30px !important;">{{__('Change')}}</span>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="mb-3 col-xl-6">
                                    <div class="field field_v2">
                                        <label for="first-name"
                                               class="ha-screen-reader">{{ __('Date of birth') }}</label>
                                        <input id="first-name" class="field__input" placeholder=" "
                                               wire:model.defer="userInfo.Datebirth" type="date"

                                        >
                                        <span class="field__label-wrap" aria-hidden="true">
      <span class="field__label">
                 {{__('Date of birth')  }}
               </span>
    </span>
                                    </div>
                                </div>
                                <div class="mb-3 col-xl-6">
                                    <div class="field field_v2">
                                        <label for="adresse" class="ha-screen-reader">{{ __('Address') }}</label>
                                        <input id="adresse" class="field__input" placeholder=" "
                                               wire:model.defer="userInfo.Adresse"
                                        >
                                        <span class="field__label-wrap" aria-hidden="true">
      <span class="field__label">
          {{ __('Address') }}
           </span>
    </span>
                                    </div>
                                </div>
                                <div class="mb-3 col-xl-6">

                                    <label class="me-sm-2">{{ __('Country') }}</label>
                                    <select class="form-control" id="Country" name="country"
                                            wire:model.defer="userInfo.Country">
                                        <option value="">Choose</option>
                                        @foreach($countries as $country)
                                            <?php
                                            $cn = \Illuminate\Support\Facades\Lang::get($country->name);
                                            ?>
                                            <option value="{{$country->id}}">{{$cn}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3 col-xl-6">
                                    <label class="me-sm-2">{{ __('State') }}</label>
                                    <select class="form-control" id="mySelect" name="state"
                                            wire:model.defer="userInfo.State">
                                        <option value="">Choose</option>
                                        @foreach($states as $state)
                                            <option value="{{$state->id}}">{{$state->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3 col-xl-6">
                                    <label class="me-sm-2">{{ __('Personal Title') }}</label>
                                    <select class="form-control" name="personaltitle"
                                            wire:model.defer="userInfo.Personaltitle">
                                        <option value="">-------</option>
                                        <?php  if(isset($personaltitles)){
                                        foreach($personaltitles as $personaltitle){
                                        ?>
                                        <option value="{{$personaltitle->id}}">{{$personaltitle->name}}</option>
                                        <?php  }} ?>
                                    </select>
                                </div>
                                <div class="mb-3 col-xl-6">
                                    <div class="field field_v2">
                                        <label for="first-name"
                                               class="ha-screen-reader">{{ __('Number Of Children') }}</label>
                                        <input type="number" id="first-name" class="field__input"
                                               wire:model.defer="userInfo.ChildrenCount">
                                        <span class="field__label-wrap" aria-hidden="true">
      <span class="field__label">
                {{ __('Number Of Children') }}
               </span>
    </span>
                                    </div>
                                </div>
                                <div class="mb-3 col-xl-6">
                                    <label class="me-sm-2">{{ __('Gender') }}</label>
                                    <select class="form-control" name="gender" wire:model.defer="userInfo.Gender">
                                        <option value="">-------</option>
                                        <?php  if(isset($genders)){
                                        foreach($genders as $gender){
                                        ?>
                                        <option value="{{$gender->id}}">{{$gender->name}}</option>
                                        <?php } }?>
                                    </select>
                                </div>
                                <div class="mb-3 col-xl-6">
                                    <label class="me-sm-2">{{ __('Your Preferred Language') }}</label>
                                    <select class="form-control" name="language"
                                            wire:model.defer="userInfo.Language">
                                        <option value="" selected>-------</option>
                                        <?php  if(isset($languages)){?>
                                        <?php
                                        foreach($languages as $language){
                                        ?>
                                        <option value="{{$language->name}}">{{$language->name}}</option>
                                        <?php } }  ?>
                                    </select>
                                </div>
                                <div class="mb-3 col-xl-6">
                                    <div class="field field_v2">
                                        <label for="first-name" class="ha-screen-reader">{{ __('National ID') }}</label>
                                        <input type="number" id="first-name" class="field__input"
                                               wire:model.defer="userInfo.nationalID">
                                        <span class="field__label-wrap" aria-hidden="true">
      <span class="field__label">
             {{ __('National ID') }}
               </span>
    </span>
                                    </div>
                                </div>
                                @if( 1==1)
                                @endif
                                <div class="text-center" style="margin-top: 20px;">
                                    @if( 1==1)
                                        <button class="btn btn-success ps-5 pe-5 btn2earnNew">{{ __('Save') }}</button>
                                    @else
                                        <div x-data="{ open: false }">
                                            <button x-show="!open" type="button" @click="open = true"
                                                    class="btn btn-secondary ps-5 pe-5"
                                                    id="reject">{{ __('Reject') }}</button>
                                            <button x-show="!open" class="btn btn-success ps-5 pe-5"
                                                    id="validate">{{ __('validate') }}</button>
                                            </br>
                                            <label x-show="open">Note</label>
                                            </br>
                                            <textarea wire:model.defer="noteReject" name="Text1" cols="80" rows="5"
                                                      x-show="open"></textarea>
                                            </br>
                                            <button type="button" x-show="open" wire:click="reject"
                                                    class="btn btn-secondary ps-5 pe-5"
                                                    id="">{{ __('Reject') }}</button>
                                            <button type="button" x-show="open" class="btn btn-danger ps-5 pe-5" id=""
                                                    @click="open = false">Annuler
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </form>


                    </div>
                    <div class="tab-pane fade" id="pills-ident" role="tabpanel"
                         aria-labelledby="pills-ident-tab">
                        <div>
                            <div class="card" id="security-form">
                                <div class="card-header">
                                    <h4 class="card-title">{{ __('Identifications') }}</h4>
                                </div>
                                <div class="card-body">
                                    <div class="container">
                                        <div class="row" style=" ">
                                            <div class="col">
                                                @if(!empty($errors_array))
                                                    @foreach ($errors_array as $error)
                                                        <p class="text-danger">{{ $error }}</p>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <form wire:submit.prevent="">
                                            <div class="row" style="margin: 20px">
                                                <div class="col-6">
                                                    <label class="form-label">{{ __('Front ID') }}</label>
                                                    <div>
                                                        @if(file_exists(public_path('/uploads/profiles/front-id-image'.$userId.'.png')))
                                                            <img width="150" height="100"
                                                                 src={{asset(('/uploads/profiles/front-id-image'.$userId.'.png'))}} >
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label">{{ __('Back ID') }}</label>
                                                    <div>
                                                        @if(file_exists(public_path('/uploads/profiles/back-id-image'. $userId.'.png')))
                                                            <img width="150" height="100"
                                                                 src={{asset(('/uploads/profiles/back-id-image'.$userId.'.png'))}} >
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-sec" role="tabpanel"
                         aria-labelledby="pills-sec-tab">
                        <div class="card-header">
                            <h4 class="card-title">{{ __('Security') }}</h4>
                        </div>
                        <div class="form-group mb-3 row">
                            <div class="form-group mb-3 row">
                                <label for="password"
                                       class="col-md-4 col-form-label text-md-right me-sm-2">{{ __('New Password') }}</label>

                                <div class="col-md-6">
                                    <input id="new_password" type="password"
                                           class="form-control" name="new_password"
                                           autocomplete="current-password" placeholder="********"
                                           wire:model.defer="newPassword"
                                           style="display:inline-block;"><i class="bi bi-eye-slash"
                                                                            id="togglePassword2"
                                                                            style="display: inline-block; margin-left: -2rem;"></i>
                                    <span style="color: red" id='error-msgNewPass'
                                          class='hide'>required field</span>
                                </div>
                            </div>
                            <div class="form-group mb-3 row">
                                <label for="password"
                                       class="col-md-4 col-form-label text-md-right me-sm-2">{{ __('New Confirm Password') }}</label>

                                <div class="col-md-6">
                                    <input id="new_confirm_password" type="password" class="form-control"
                                           name="new_confirm_password"
                                           autocomplete="current-password" placeholder="********"
                                           wire:model.defer="confirmedPassword"
                                           style="display:inline-block;"><i class="bi bi-eye-slash"
                                                                            id="togglePassword3"
                                                                            style="display: inline-block; margin-left: -2rem;"></i>
                                    <span style="color: red" id='error-msgConfirmNewPass'
                                          class='hide'>required field</span>
                                </div>
                            </div>
                            <div class="text-center" style="margin-top: 20px;">
                                <button type="submit" class="btn btn-success ps-5 pe-5 btn2earnNew"
                                        wire:click="updatePassWord"
                                        id="update_security">{{ __('Save') }}</button>
                            </div>

                        </div>


                    </div>
                    <div wire:ignore class="tab-pane fade" id="pills-telchange" role="tabpanel"
                         aria-labelledby="pills-telchange-tab">
                        <div class="card text-center cardPhone">
                            <div class="card-header">
                                <h4 class="card-title">{{ __('Update Phone Number') }}</h4>
                            </div>
                            <div wire:ignore class="card-body ">
                                <div class="col-12 col-md-3">
                                    {{--                                    <input type="text" wire:model="updatePhoneAdmin">--}}
                                    <div class="text-center mb-3" dir="ltr">
                                        <label>{{ __('Your new phone number') }}</label>
                                        <div id="inputPhoneUpdateAd" data-turbolinks-permanent
                                             class="input-group signup mb-3" style="justify-content:center;">
                                        </div>
                                    </div>
                                    <div class="text-center" style="margin-top: 20px;">
                                        <button type="submit" id="submitPhoneUpAd" class="btn btn-success ps-5 pe-5"
                                                onclick="ConfirmChangePhoneAdmin()"
                                        >{{ __('send') }}</button>
                                    </div>
                                </div>

                            </div>
                        </div>


                    </div>
                </div>
            </div>
            <script data-turbolinks-eval="false">
                function ConfirmChangePhoneAdmin(){
                    window.Livewire.emit('updatePhone514',$('#phoneUpdateAdmin').val(),$('#ccodephoneUpdateAdmin').val(),$('#outputphoneUpdateAdmin').val())
                }
                window.addEventListener('updatePhone', event => {
                    toastr.success(event.detail.text);
                })
            </script>

        </div>
    </div>
</div>
