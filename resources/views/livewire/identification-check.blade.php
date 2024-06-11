<div>
    <div class="row">
        @include('layouts.flash-messages')
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        <div class="row justify-content-center">
                            <div class="col-lg-12">
                                <h4 class="mt-4 fw-semibold">{{__('KYC_Verification')}}</h4>
                                <p class="text-muted mt-3">
                                    {{__('Txt_KYC_Verification')}}
                                </p>
                                <div class="mt-4">
                                    <button onclick="hideIdentificationModal()" type="button"
                                            class="btn btn-primary"
                                            data-bs-toggle="modal"
                                            @if(!$usermetta_info2['enFirstName'] || !$usermetta_info2['enLastName'] || !$usermetta_info2['birthday'] || !$usermetta_info2['nationalID'] || !$userF['email'])
                                                disabled
                                            @endif
                                            data-bs-target=@if($hasRequest) "#modalRequestExiste" @else
                                        "#exampleModal"
                                    @endif>
                                    {{__('Click_here_for_Verification')}}
                                    </button>
                                </div>
                                @if(!empty($errors_array))
                                    <ul class="list-group list-group-flush">
                                        @foreach ($errors_array as $error)
                                            <li class="list-group-item text-danger">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                        <div class="row justify-content-center mt-5 mb-2">
                            <div class="col-sm-7 col-8">
                                <img src="{{ URL::asset('assets/images/verification-img.png') }}" alt=""
                                     class="img-fluid"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalRequestExiste" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{__('Validation Compte')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{__('voter_demande_déja_en_cours')}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header p-3">
                    <h5 class="modal-title text-uppercase" id="exampleModalLabel">
                        {{__('Verify_your_Account')}}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" class="checkout-tab">
                    <div class="modal-body p-0">
                        <div class="step-arrow-nav">
                            <ul class="nav nav-pills nav-justified custom-nav" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link p-3 active" id="pills-bill-info-tab" data-bs-toggle="pill"
                                            data-bs-target="#pills-bill-info" type="button" role="tab"
                                            aria-controls="pills-bill-info" aria-selected="true">
                                        {{__('Personal_Info')}}
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link p-3" id="pills-identities-card-tab" data-bs-toggle="pill"
                                            data-bs-target="#pills-identities-card" type="button" role="tab"
                                            aria-controls="pills-identities-card" aria-selected="false">
                                        {{__('Import your National identity card')}}
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link p-3" id="pills-inter-identities-card-tab"
                                            data-bs-toggle="pill"
                                            data-bs-target="#pills-inter-identities-card" type="button" role="tab"
                                            aria-controls="pills-inter-identities-card" aria-selected="false">
                                        {{__('Import your international identity card')}}
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="pills-bill-info" role="tabpanel"
                                 aria-labelledby="pills-bill-info-tab">
                                <div class="row g-3">
                                    <div id="personalInformationMessage" class="alert alert-danger" role="alert"
                                         style="display: none">
                                        {{__('Please check form data')}}
                                        <a class="btn btn-outline-primary ml-2 mr-2"
                                           href="{{route('account',app()->getLocale())}}"
                                           class="badge badge-dark" role="button"
                                           aria-pressed="true">
                                            {{__('Go to form data')}}
                                        </a>

                                    </div>
                                    <div class="col-lg-12">
                                        <table class="table table-striped">
                                            <tr>
                                                <th scope="row">
                                                    {{__('First name identificatdion modal')}}
                                                </th>
                                                <td>
                                                    {{$usermetta_info2['enFirstName']}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">
                                                    {{__('Last name  identificatdion modal')}}
                                                </th>
                                                <td>
                                                    {{$usermetta_info2['enLastName']}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">
                                                    {{__('Date of birth identificatdion modal')}}
                                                    /th>
                                                <td>
                                                    {{$usermetta_info2['birthday']}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">
                                                    {{__('National ID identificatdion modal')}}
                                                </th>
                                                <td>
                                                    {{$usermetta_info2['nationalID']}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">
                                                    {{__('Email identificatdion modal')}}
                                                </th>
                                                <td>
                                                    {{$userF['email']}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row" colspan="2">
                                                    <div class="form-check form-switch mb-3 mt-3" dir="ltr">
                                                        <input wire:model.defer="notify" type="checkbox"
                                                               class="form-check-input" id="" checked="">
                                                        <label class="form-check-label"
                                                               for="customSwitchsizesm">{{ __('I want to receive an SMS when my identification completed successfully') }}</label>
                                                    </div>
                                                </th>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="d-flex align-items-start gap-3 mt-3">
                                            <button id="btn-next-identities-card" type="button"
                                                    class="btn btn-primary btn-label right ms-auto nexttab"
                                                    data-nexttab="pills-bill-address-tab">
                                                <i class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>
                                                {{__('Next step :  check your identities card')}}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-identities-card" role="tabpanel"
                                 aria-labelledby="pills-identities-card-tab">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="alert border-0 alert-primary material-shadow" role="alert">
                                            <strong>{{__('Note')}}
                                                : </strong>{{__('The photo must be in PNG format, and must not exceed 2 Mb in size')}}
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div>
                                            <label class="form-label">
                                                {{ __('Front ID') }}
                                                <span class="text-danger">*</span>
                                            </label>
                                        </div>
                                        <div>
                                            @if(file_exists(public_path('/uploads/profiles/front-id-image'.$userAuth->idUser.'.png')))
                                                <img class="img-thumbnail" width="150" height="100"
                                                     src={{asset(('/uploads/profiles/front-id-image'.$userAuth->idUser.'.png'))}} >
                                            @else
                                                <img class="img-thumbnail" width="150" height="100"
                                                     src={{asset(('/uploads/profiles/default.png'))}} >
                                            @endif
                                        </div>

                                        <div class="wrap-custom-file" style="margin-top: 10px">
                                            <input wire:model.defer="photoFront" type="file" name="photoFront"
                                                   id="photoFront"
                                                   accept=".png"/>
                                            <label for="photoFront">
                                                <lord-icon src="https://cdn.lordicon.com/vixtkkbk.json" trigger="loop"
                                                           delay="1000" style="width:100px;height:100px">
                                                </lord-icon>
                                                <span> <i class="ri-camera-fill"></i> </span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div>
                                            <label class="form-label">
                                                {{ __('Back ID') }}
                                                <span class="text-danger">*</span>
                                            </label>
                                        </div>
                                        <div>
                                            @if(file_exists(public_path('/uploads/profiles/back-id-image'.$userAuth->idUser.'.png')))
                                                <img class="img-thumbnail" width="150" height="100"
                                                     src={{asset(('/uploads/profiles/back-id-image'.$userAuth->idUser.'.png'))}} >
                                            @else
                                                <img class="img-thumbnail" width="150" height="100"
                                                     src={{asset(('/uploads/profiles/default.png'))}} >
                                            @endif
                                        </div>
                                        <div class="wrap-custom-file ml-2">
                                            <input wire:model.defer="photoBack" type="file" name="photoBack"
                                                   id="photoBack" accept=".png"/>
                                            <label for="photoBack">
                                                <lord-icon src="https://cdn.lordicon.com/vixtkkbk.json" trigger="loop"
                                                           delay="1000" style="width:100px;height:100px">

                                                </lord-icon>
                                                <span> <i class="ri-camera-fill"></i> </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-start gap-3 mt-4">
                                    <button type="button" class="btn btn-light btn-label previestab"
                                            data-previous="pills-bill-info-tab">
                                        <i class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i>
                                        {{__('back to personal info')}}
                                    </button>
                                    <button id="btn-next-identities-card" type="button"
                                            class="btn btn-primary btn-label right ms-auto nexttab"
                                            data-nexttab="pills-inter-identities-card-tab">
                                        <i class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>
                                        {{__('Next Step: Check your International Identiies card')}}
                                    </button>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-inter-identities-card" role="tabpanel"
                                 aria-labelledby="pills-inter-identities-card-tab">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-check form-switch form-switch-right form-switch-md">
                                            <input class="form-check-input" wire:model="internationalCard"
                                                   type="checkbox"
                                                   id="international-card">
                                            <label for="international-card"
                                                   class="form-label text-muted">{{__('I want to submit my international identitie card')}}</label>
                                        </div>
                                    </div>
                                </div>
                                <div id="international-card-block" class="row mt-3 d-none">
                                    <div class="col-12">
                                        <div class="alert border-0 alert-primary material-shadow" role="alert">
                                            <strong>{{__('Note')}}
                                                : </strong>{{__('The photo must be in PNG format, and must not exceed 2 Mb in size')}}
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div>
                                            <label class="form-label">
                                                {{ __('International ID') }}
                                                <span class="text-danger">*</span>
                                            </label>
                                        </div>
                                        <div>
                                            @if(file_exists(public_path('/uploads/profiles/international-id-image'.$userAuth->idUser.'.png')))
                                                <img class="img-thumbnail" width="150" height="100"
                                                     src={{asset(('/uploads/profiles/international-id-image'.$userAuth->idUser.'.png'))}} >
                                            @else
                                                <img class="img-thumbnail" width="150" height="100"
                                                     src={{asset(('/uploads/profiles/default.png'))}} >
                                            @endif
                                        </div>
                                        <div class="wrap-custom-file">
                                            <input wire:model.defer="photoInternational" type="file"
                                                   name="photoInternational"
                                                   id="photoInternational"
                                                   accept=".png"/>
                                            <label for="photoInternational">
                                                <lord-icon src="https://cdn.lordicon.com/vixtkkbk.json" trigger="loop"
                                                           delay="1000" style="width:100px;height:100px">
                                                </lord-icon>
                                                <span> <i class="ri-camera-fill"></i> </span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <div class="mb-3">
                                            <label for="internationalId" class="form-label">
                                                {{ __('InternationalId ID identificatdion modal') }}
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" minlength="5" maxlength="50"
                                                   wire:model.defer="userF.internationalID"
                                                   id="internationalId" placeholder="{{__('InternationalId ID')}}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="expiryDate" class="form-label">
                                                {{__('Expiry date identificatdion modal')  }}
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input wire:model.defer="userF.expiryDate" type="date"
                                                   min="{{ now()->format('Y-m-d') }}"
                                                   class="form-control" id="expiryDate"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-start gap-3 mt-4">
                                    <button type="button" class="btn btn-light btn-label previestab"
                                            data-previous="pills-identities-card-tab">
                                        <i class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i>
                                        {{__('back to identity card')}}
                                    </button>
                                    <button onclick="sendIndentificationRequest()" type="button"
                                            class="btn btn-primary btn-label right ms-auto nexttab">
                                        <div wire:loading wire:target="sendIndentificationRequest">
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                      aria-hidden="true"></span>
                                            <span class="sr-only">{{__('Loading')}}...</span>
                                        </div>
                                        {{__('Send Identification request')}}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        var errorMail = document.querySelector("#error-mail");
        var checkOpt = false;
        var canUseEmail = false;
        var sendEmailNotification = false;


        function doneVerify() {
            window.location.reload();
        }

        function hideIdentificationModal() {
            $("#exampleModal").modal("hide");
        }

        function checkRequiredFieldInfo(idInput) {
            console.log($("#" + idInput));
            if ($("#" + idInput).val().trim() === "") {
                $("#" + idInput).css('border-color', 'red');
                return false;
            } else {
                $("#" + idInput).css('border-color', 'green');
            }
            return true;
        }

        function checkRequiredFieldsInfo() {

            if ($('#international-card').is(":checked")) {
                return checkRequiredFieldInfo('internationalId') &&
                    checkRequiredFieldInfo('expiryDate');
            } else {
                return true;
            }

        }

        function validateEmail(email) {
            return String(email)
                .toLowerCase()
                .match(
                    /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
                );
        }

        function sendIndentificationRequest(event) {
            if (checkRequiredFieldsInfo()) {
                window.livewire.emit('sendIndentificationRequest');
            }
        }

        function sendMailNotification() {
            $("#inputEmailUser").css('border-color', 'green');
            $.ajax({
                method: "GET",
                url: "/sendMailNotification",
                async: false,
                success: (result) => {
                    $("#inputEmailUser").css('border-color', 'green');
                    sendEmailNotification = true;
                }
            });
        }

        $('input[type="file"]').each(function () {
            var $file = $(this),
                $label = $file.next('label'),
                $labelText = $label.find('span'),
                labelDefault = $labelText.text();
            $file.on('change', function (event) {
                var fileName = $file.val().split('\\').pop(),
                    tmppath = URL.createObjectURL(event.target.files[0]);
                if (fileName) {
                    $label.addClass('file-ok').css('background-image', 'url(' + tmppath + ')');
                    $labelText.text(fileName);
                } else {
                    $label.removeClass('file-ok');
                    $labelText.text(labelDefault);
                }
            });
        });

        $('#international-card').change(function () {
            if (this.checked) {
                $('#international-card-block').removeClass("d-none")
                $('#internationalId').val('');
                $('#expiryDate').val('');
                $('#photoInternational').val('');
            } else {
                $('#international-card-block').addClass("d-none")
            }
        });


        $('#btn-next-identities-card').click(function (e) {
            $('#myTab button[id="pills-identities-card-tab"]').tab('show');
        });

        document.getElementById('pills-identities-card-tab').addEventListener('shown.bs.tab', function (event) {

        });

        window.addEventListener('IdentificationRequestMissingInformation', event => {
            Swal.fire({
                title: event.detail.title,
                text: event.detail.text,
                icon: 'error',
                confirmButtonText: "{{__('ok')}}"
            })
        })


    </script>
</div>
