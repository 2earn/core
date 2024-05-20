<div>
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        <div class="row justify-content-center">
                            <div class="col-lg-9">
                                <h4 class="mt-4 fw-semibold">{{__('KYC_Verification')}}</h4>
                                <p class="text-muted mt-3">
                                    {{__('Txt_KYC_Verification')}}
                                </p>
                                <div class="mt-4">
                                    <button onclick="verifRequest()" type="button" class="btn btn-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target=@if($hasRequest) "#modalRequestExiste" @else
                                        "#exampleModal"
                                    @endif>
                                    {{__('Click_here_for_Verification')}}
                                    </button>
                                </div>
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
                                    <button class="nav-link p-3" id="pills-bill-address-tab" data-bs-toggle="pill"
                                            data-bs-target="#pills-bill-address" type="button" role="tab"
                                            aria-controls="pills-bill-address" aria-selected="false">
                                        {{__('Mail_Details')}}
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link p-3" id="pills-payment-tab" data-bs-toggle="pill"
                                            data-bs-target="#pills-payment" type="button" role="tab"
                                            aria-controls="pills-payment" aria-selected="false">
                                        {{__('Interface_Verification')}}
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
                                    </div>
                                    <div class="col-lg-6">
                                        <div>
                                            <label for="firstName" class="form-label">First Name</label>
                                            <input wire:model.defer="usermetta_info2.enFirstName" type="text"
                                                   class="form-control" id="firstName"
                                                   placeholder="Enter your firstname">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div>
                                            <label for="lastName" class="form-label">Last Name</label>
                                            <input wire:model.defer="usermetta_info2.enLastName" type="text"
                                                   class="form-control" id="lastName"
                                                   placeholder="Enter your lastname">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="JoiningdatInput" class="form-label">
                                                {{__('Date of birth')  }}
                                            </label>
                                            <input wire:model.defer="usermetta_info2.birthday" type="date"
                                                   class="form-control"
                                                   id="dateofBirth"
                                                   placeholder=""/>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="nationalId" class="form-label">{{ __('National ID') }}</label>
                                            <input type="text" class="form-control" minlength="5" maxlength="50"
                                                   wire:model.defer="usermetta_info2.nationalID"
                                                   id="nationalId" placeholder="{{__('National ID')}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-check form-switch   ms-5 me-5 mb-3" dir="ltr">
                                            <input wire:model.defer="notify" type="checkbox"
                                                   class="form-check-input" id="" checked="">
                                            <label class="form-check-label"
                                                   for="customSwitchsizesm">{{ __('I want to receive an SMS when my identification completed successfully') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="d-flex align-items-start gap-3 mt-3">
                                            <button id="btnNextMailAdress" type="button"
                                                    class="btn btn-primary btn-label right ms-auto nexttab"
                                                    data-nexttab="pills-bill-address-tab">
                                                <i class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>
                                                {{__('Next step : Check your email')}}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-bill-address" role="tabpanel"
                                 aria-labelledby="pills-bill-address-tab">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="emailInput"
                                                   class="form-label">{{ __('Your Email') }}</label>
                                            <div class="input-group">
                                                <input id="inputEmailUser" wire:model.defer="userF.email" type="email"
                                                       class="form-control"
                                                       name="email" placeholder="{{__('Enter your email')}}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="alert alert-danger hide" role="alert" id='error-mail'></div>
                                    </div>
                                    <div id="optChecker" class="col-lg-12 invisible">
                                        <div
                                            class="container height-100 d-flex justify-content-center align-items-center">
                                            <div class="position-relative">
                                                <div class="card p-2 text-center">
                                                    <h6>{{__('Opt_verif_mail')}} <br> {{__('To_verif_Account')}}
                                                    </h6>
                                                    <div><span>{{__('Code_send_To')}}</span>
                                                        <small>*******{{ substr(getActifNumber()->fullNumber, -3) }}</small>
                                                    </div>
                                                    <div id="otp"
                                                         class="inputs d-flex flex-row justify-content-center mt-2">
                                                        <input class="m-2 text-center form-control rounded" type="text"
                                                               id="optFirst" maxlength="1"/>
                                                        <input class="m-2 text-center form-control rounded" type="text"
                                                               id="optSecond" maxlength="1"/>
                                                        <input class="m-2 text-center form-control rounded" type="text"
                                                               id="optThird" maxlength="1"/>
                                                        <input class="m-2 text-center form-control rounded" type="text"
                                                               id="optFourth" maxlength="1"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="hstack align-items-start gap-3 mt-4">
                                            <button type="button" class="btn btn-light btn-label previestab"
                                                    data-previous="pills-bill-info-tab"><i
                                                    class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i>
                                                {{__('Back_to_personnel')}}
                                            </button>
                                            <button type="button"
                                                    class="btn btn-primary btn-label right ms-auto nexttab"
                                                    data-nexttab="pills-payment-tab"><i
                                                    class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>
                                                {{__('Next step : Import identity card')}}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-payment" role="tabpanel"
                                 aria-labelledby="pills-payment-tab">
                                <div class="row">
                                    <div class="col-6">
                                        <div>
                                            <label class="form-label">{{ __('Front ID') }}</label>
                                        </div>
                                        <div>
                                            @if(file_exists(public_path('/uploads/profiles/front-id-image'.$userAuth->idUser.'.png')))
                                                <img width="150" height="100"
                                                     src={{asset(('/uploads/profiles/front-id-image'.$userAuth->idUser.'.png'))}} >
                                            @else
                                                <img width="150" height="100"
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
                                            <label class="form-label">{{ __('Back ID') }}</label>
                                        </div>
                                        <div>
                                            @if(file_exists(public_path('/uploads/profiles/back-id-image'.$userAuth->idUser.'.png')))
                                                <img width="150" height="100"
                                                     src={{asset(('/uploads/profiles/back-id-image'.$userAuth->idUser.'.png'))}} >
                                            @else
                                                <img width="150" height="100"
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
                                            data-previous="pills-bill-address-tab">
                                        <i class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i>
                                        {{__('Back_to_mail_verif')}}
                                    </button>
                                    <button onclick="sendIndentificationRequest()" type="button"
                                            class="btn btn-primary btn-label right ms-auto nexttab">
                                        <div wire:loading wire:target="sendIndentificationRequest">
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                      aria-hidden="true"></span>
                                            <span class="sr-only">{{__('Loading')}}...</span>
                                        </div>
                                        {{__('Submit')}}
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

        function OptDisable() {
            $('#optFirst').addClass("disabled");
            $('#optSecond').addClass("disabled");
            $('#optThird').addClass("disabled");
            $('#optFourth').addClass("disabled");
        }

        function OptInputEmpty() {
            $('#optFirst').val("");
            $('#optSecond').val("");
            $('#optThird').val("");
            $('#optFourth').val("");
        }

        function OptInputChangeColor(optColor) {
            $('#optFirst').css('border-color', optColor);
            $('#optSecond').css('border-color', optColor);
            $('#optThird').css('border-color', optColor);
            $('#optFourth').css('border-color', optColor);
        }

        function checkOptVerify() {
            var returnValue = false;
            var opt = $('#optFirst').val() + $('#optSecond').val() + $('#optThird').val() + $('#optFourth').val();
            if (opt.length == 4) {
                $.ajax({
                    method: "GET",
                    url: "{{route('mailVerifOpt')}}",
                    async: false,
                    data: {opt: opt, mail: $("#inputEmailUser").val().trim(),},
                    success: (result) => {
                        if (result == 'no') {
                            returnValue = false;
                            displayMailErrorMessage('{{__('Validation OTP code Failed')}}')
                            OptInputEmpty();
                            OptInputChangeColor('red');
                        } else {
                            errorMail.classList.add("hide");
                            OptInputChangeColor('green');
                            OptDisable();
                            returnValue = true;
                        }
                    },
                    error: (error) => {
                        displayMailErrorMessage('{{__('Invalid OTP code')}}')
                        returnValue = false;
                    }
                });
                return returnValue;
            }
        }

        function checkNewMail() {
            var isNewEmail = false;
            $.ajax({
                method: "GET",
                url: "{{route('mailVerifNew')}}",
                async: false,
                data: {mail: $("#inputEmailUser").val().trim(),},
                success: (result) => {
                    if (result == 'no') {
                        isNewEmail = false;
                    } else {
                        isNewEmail = true;
                    }
                }
            });

            return isNewEmail;
        }

        function doneVerify() {
            window.location.reload();
        }

        function verifRequest() {
            $("#exampleModal").modal("hide");
        }

        function checkRequiredFieldInfo() {
            validRequiredrFieldInfo = true;
            if ($("#firstName").val().trim() === "") {
                $("#firstName").css('border-color', 'red')
                validRequiredrFieldInfo = false;
            } else {
                $("#firstName").css('border-color', 'green')
            }

            if ($("#lastName").val().trim() === "") {
                $("#lastName").css('border-color', 'red')
                validRequiredrFieldInfo = false;
            } else {
                $("#lastName").css('border-color', 'green')
            }

            if ($("#nationalId").val().trim() === "") {
                $("#nationalId").css('border-color', 'red')
                validRequiredrFieldInfo = false;
            } else {
                $("#nationalId").css('border-color', 'green')
            }

            if ($("#dateofBirth").val().trim() === "") {
                $("#dateofBirth").css('border-color', 'red')
                validRequiredrFieldInfo = false;
            } else {
                $("#dateofBirth").css('border-color', 'green')
            }
            return validRequiredrFieldInfo;
        }

        function validateEmail(email) {
            return String(email)
                .toLowerCase()
                .match(
                    /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
                );
        }

        function sendIndentificationRequest() {
            if (checkRequiredFieldInfo() && checkRequiredFieldMail()) {
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

        function sendMailVerifRequest() {
            $.ajax({
                method: "GET",
                url: "{{route('mailVerif')}}",
                async: false,
                data: {mail: $("#inputEmailUser").val().trim(),},
                success: (result) => {
                    if (result == 'no') {
                        displayMailErrorMessage('{{__('mail used')}}')
                        return true;
                    }
                }
            });
            return false;
        }

        function displayMailErrorMessage(message) {
            errorMail.innerHTML = message;
            errorMail.classList.remove("hide");
            $("#inputEmailUser").css('border-color', 'red');
        }

        function checkOptFields() {
            return $('#optFirst').val().trim() != "" && $('#optSecond').val().trim() != "" && $('#optThird').val().trim() != "" && $('#optFourth').val().trim() != "";
        }

        function checkRequiredFieldMail() {
            if ($("#inputEmailUser").val().trim() === "") {
                displayMailErrorMessage('{{__('Required field')}}')
                return false;
            }

            if (!validateEmail($("#inputEmailUser").val().trim())) {
                displayMailErrorMessage('{{__('Invalid Format')}}')
                return false;
            }

            errorMail.classList.add("hide");
            $("#inputEmailUser").css('border-color', 'green');

            canUseEmail = sendMailVerifRequest()

            var optChecker = document.querySelector("#optChecker");
            if (!canUseEmail) {
                if (checkNewMail()) {

                    if (sendEmailNotification) {
                        sendMailNotification();
                    }
                    optChecker.classList.remove("invisible");
                    $("#inputEmailUser").prop('disabled', true);
                    if (checkOptFields()) {
                        if (checkOptVerify()) {
                            checkOpt = true;
                        } else {
                            checkOpt = false;
                        }
                    }
                } else
                    checkOpt = true;
            }
            if (canUseEmail || !checkOpt) return false
            else {
                optChecker.classList.add("invisible");
                OptInputEmpty()
                checkOpt = false;
            }
            return true;
        }

        $("#inputEmailUser").keyup(function () {
            if ($("#inputEmailUser").val().trim() == "") {
                displayMailErrorMessage('{{__('Required field')}}')
                return
            }
            if (!validateEmail($("#inputEmailUser").val().trim())) {
                displayMailErrorMessage('{{__('Invalid Format')}}')
                return
            }
            errorMail.innerHTML = '';
            errorMail.classList.add("hide");
            $("#inputEmailUser").css('border-color', 'green');
        });

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

        $('#btnNextMailAdress').click(function (e) {
            $('#personalInformationMessage').css("display", "none");
            e.preventDefault();
            if (checkRequiredFieldInfo()) {
                $('#personalInformationMessage').css("display", "none");
                $('#myTab   button[id="pills-bill-info-tab"] ').tab('show');
            } else {
                $('#personalInformationMessage').css("display", "block");
            }
        });

        document.getElementById('pills-bill-address-tab').addEventListener('shown.bs.tab', function (event) {
            if (!checkRequiredFieldInfo())
                $('#myTab   button[id="pills-bill-info-tab"] ').tab('show');
        });

        document.getElementById('pills-payment-tab').addEventListener('shown.bs.tab', function (event) {
            if (!checkRequiredFieldInfo())
                $('#myTab   button[id="pills-bill-info-tab"] ').tab('show');
            if (!checkRequiredFieldMail()) {
                $('#myTab   button[id="pills-bill-address-tab"] ').tab('show');
            }
        });

        window.addEventListener('IdentificationRequestMissingInformation', event => {
            console.log('IdentificationRequestMissingInformation');
        })
    </script>
</div>
