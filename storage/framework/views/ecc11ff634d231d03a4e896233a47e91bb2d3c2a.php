<div>
    <style>
        .wrap-custom-file {
            position: relative;
            display: inline-block;
            width: 150px;
            height: 150px;
            margin: 0 0.5rem 1rem;
            text-align: center;
        }

        .wrap-custom-file input[type="file"] {
            position: absolute;
            top: 0;
            left: 0;
            width: 2px;
            height: 2px;
            overflow: hidden;
            opacity: 0;
        }

        .wrap-custom-file label {
            z-index: 1;
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            right: 0;
            width: 100%;
            overflow: hidden;
            padding: 0 0.5rem;
            cursor: pointer;
            background-color: #fff;
            border-radius: 4px;
            -webkit-transition: -webkit-transform 0.4s;
            transition: -webkit-transform 0.4s;
            transition: transform 0.4s;
            transition: transform 0.4s, -webkit-transform 0.4s;
        }

        .wrap-custom-file label span {
            display: block;
            margin-top: 2rem;
            font-size: 1.4rem;
            color: #777;
            -webkit-transition: color 0.4s;
            transition: color 0.4s;
        }

        .wrap-custom-file label:hover {
            -webkit-transform: translateY(-1rem);
            transform: translateY(-1rem);
        }

        .wrap-custom-file label:hover span {
            color: #333;
        }

        .wrap-custom-file label.file-ok {
            background-size: cover;
            background-position: center;
        }

        .wrap-custom-file label.file-ok span {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 0.3rem;
            font-size: 1.1rem;
            color: #000;
            background-color: rgba(255, 255, 255, 0.7);
        }
    </style>
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        <div class="row justify-content-center">
                            <div class="col-lg-9">
                                <h4 class="mt-4 fw-semibold">KYC Verification</h4>
                                <p class="text-muted mt-3">When you get your KYC verification process
                                    done, you have given the crypto exchange in this case, information.
                                </p>
                                <div class="mt-4">
                                    <button onclick="verifRequest()" type="button" class="btn btn-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target=<?php if($hasRequest): ?> "#modalRequestExiste" <?php else: ?>
                                        "#exampleModal"  <?php endif; ?>>
                                    Click here for Verification
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center mt-5 mb-2">
                            <div class="col-sm-7 col-8">
                                <img src="<?php echo e(URL::asset('assets/images/verification-img.png')); ?>"   alt=""
                                     class="img-fluid"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end card-->
        </div>

        <!--end col-->
    </div>
    <div class="modal fade" id="modalRequestExiste" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Validation COmpte</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Compte déja validé
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header p-3">
                    <h5 class="modal-title text-uppercase" id="exampleModalLabel">Verify your Account
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
                                            aria-controls="pills-bill-info" aria-selected="true">Personal Info
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link p-3" id="pills-bill-address-tab" data-bs-toggle="pill"
                                            data-bs-target="#pills-bill-address" type="button" role="tab"
                                            aria-controls="pills-bill-address" aria-selected="false">Bank Details
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link p-3" id="pills-payment-tab" data-bs-toggle="pill"
                                            data-bs-target="#pills-payment" type="button" role="tab"
                                            aria-controls="pills-payment" aria-selected="false">Document Verification
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link p-3" id="pills-finish-tab" data-bs-toggle="pill"
                                            data-bs-target="#pills-finish" type="button" role="tab"
                                            aria-controls="pills-finish"
                                            aria-selected="false">Verified
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!--end modal-body-->
                    <div class="modal-body">
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="pills-bill-info" role="tabpanel"
                                 aria-labelledby="pills-bill-info-tab">
                                <div class="row g-3">
                                    <div class="col-lg-6">
                                        <div>
                                            <label for="firstName" class="form-label">First Name</label>
                                            <input wire:model.defer="usermetta_info2.enFirstName" type="text"
                                                   class="form-control" id="firstName"
                                                   placeholder="Enter your firstname">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-6">
                                        <div>
                                            <label for="lastName" class="form-label">Last Name</label>
                                            <input wire:model.defer="usermetta_info2.enLastName" type="text"
                                                   class="form-control" id="lastName"
                                                   placeholder="Enter your lastname">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-6">
                                        <div>
                                            <label for="phoneNumber" class="form-label">الاسم</label>
                                            <input wire:model.defer="usermetta_info2.arFirstName" type="text"
                                                   class="form-control" id="phoneNumber"
                                                   placeholder="Enter your phone number">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-6">
                                        <div>
                                            <label for="dateofBirth" class="form-label">اللقب</label>
                                            <input wire:model.defer="usermetta_info2.arLastName" type="text"
                                                   class="form-control" id="dateofBirth"
                                                   data-provider="flatpickr" placeholder="Enter your date of birth">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="d-flex align-items-start gap-3 mt-3">
                                            <button id="btnNextAdresse" type="button"
                                                    class="btn btn-primary btn-label right ms-auto nexttab"
                                                    data-nexttab="pills-bill-address-tab"><i
                                                    class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>
                                                Next Step
                                            </button>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </div>
                            <!-- end tab pane -->

                            <div class="tab-pane fade" id="pills-bill-address" role="tabpanel"
                                 aria-labelledby="pills-bill-address-tab">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="emailInput"
                                                   class="form-label"><?php echo e(__('Your Email')); ?></label>
                                            <div class="input-group">
                                                <input id="inputEmailUser" wire:model.defer="userF.email" type="email"
                                                       class="form-control"
                                                       name="email" placeholder="Enter your email" required>


                                            </div>
                                            <span id='error-mail' class='hide'></span>
                                            
                                        </div>
                                    </div>
                                    <div id="optChecker" class="col-lg-6 invisible">
                                        <div class=''
                                             class="container height-100 d-flex justify-content-center align-items-center">
                                            <div class="position-relative">
                                                <div class="card p-2 text-center">
                                                    <h6>Please enter the one time password <br> to verify your account
                                                    </h6>
                                                    <div><span>A code has been sent to</span> <small>*******9897</small>
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
                                    <!--end col-->
                                
                                
                                
                                
                                
                                
                                
                                <!--end col-->
                                
                                
                                
                                
                                
                                
                                
                                
                                <!--end col-->
                                
                                
                                
                                
                                
                                
                                
                                
                                <!--end col-->
                                
                                
                                
                                
                                
                                
                                <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="hstack align-items-start gap-3 mt-4">
                                            <button type="button" class="btn btn-light btn-label previestab"
                                                    data-previous="pills-bill-info-tab"><i
                                                    class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i>Back
                                                to Personal Info
                                            </button>
                                            <button type="button"
                                                    class="btn btn-primary btn-label right ms-auto nexttab"
                                                    data-nexttab="pills-payment-tab"><i
                                                    class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>Next
                                                Step
                                            </button>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                            </div>
                            <!-- end tab pane -->
                            <div class="tab-pane fade" id="pills-payment" role="tabpanel"
                                 aria-labelledby="pills-payment-tab">
                                <h5 class="mb-3">Choose Document Type</h5>
                                <div class="row" style="margin: 20px">
                                    <div class="col-6">
                                        <div>
                                            <label class="form-label"><?php echo e(__('Front ID')); ?></label>
                                        </div>
                                        <div>
                                            <?php if(file_exists(public_path('/uploads/profiles/front-id-image'.$userAuth->idUser.'.png'))): ?>
                                                <img width="150" height="100"
                                                     src=<?php echo e(asset(('/uploads/profiles/front-id-image'.$userAuth->idUser.'.png'))); ?> >
                                            <?php endif; ?>
                                        </div>
                                        <div class="wrap-custom-file" style="margin-top: 10px">
                                            <input wire:model.defer="photoFront" type="file" name="image1" id="image1"
                                                   accept=".gif, .jpg, .png"/>
                                            <label for="image1">
                                                <lord-icon
                                                    src="https://cdn.lordicon.com/vixtkkbk.json"
                                                    trigger="loop" delay="1000"
                                                    style="width:100px;height:100px">
                                                </lord-icon>
                                                <span> <i class="ri-camera-fill"></i> </span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div>
                                            <label class="form-label"><?php echo e(__('Front ID')); ?></label>
                                        </div>
                                        <div>
                                            <?php if(file_exists(public_path('/uploads/profiles/back-id-image'.$userAuth->idUser.'.png'))): ?>
                                                <img width="150" height="100"
                                                     src=<?php echo e(asset(('/uploads/profiles/back-id-image'.$userAuth->idUser.'.png'))); ?> >
                                            <?php endif; ?>
                                        </div>
                                        <div class="wrap-custom-file" style="margin-top: 10px">
                                            <input wire:model.defer="backback" type="file" name="image33" id="image33"
                                                   accept=".gif, .jpg, .png"/>
                                            <label for="image33">
                                                <lord-icon
                                                    src="https://cdn.lordicon.com/vixtkkbk.json"
                                                    trigger="loop" delay="1000"
                                                    style="width:100px;height:100px">
                                                </lord-icon>
                                                <span> <i class="ri-camera-fill"></i> </span>
                                            </label>
                                        </div>

                                    </div>
                                    

                                </div>
                                <!-- end dropzon-preview -->
                                <div class="d-flex align-items-start gap-3 mt-4">
                                    <button type="button" class="btn btn-light btn-label previestab"
                                            data-previous="pills-bill-address-tab"><i
                                            class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i>Back
                                        to Bank Details
                                    </button>
                                    <button onclick="saveimg()" type="button"
                                            class="btn btn-primary btn-label right ms-auto nexttab"
                                            data-nexttab="pills-finish-tab"><i
                                            class="ri-save-line label-icon align-middle fs-16 ms-2"></i>Submit
                                    </button>
                                </div>
                            </div>
                            <!-- end tab pane -->

                            <div class="tab-pane fade" id="pills-finish" role="tabpanel"
                                 aria-labelledby="pills-finish-tab">
                                <div class="row text-center justify-content-center py-4">
                                    <div class="col-lg-11">
                                        <div class="mb-4">
                                            <lord-icon src="https://cdn.lordicon.com/lupuorrc.json" trigger="loop"
                                                       colors="primary:#0ab39c,secondary:#405189"
                                                       style="width:120px;height:120px">
                                            </lord-icon>
                                        </div>
                                        <h5>Verification Completed</h5>
                                        <input readonly style="border: none;width: 100%" wire:model="messageVerif">
                                        <p type="text" wire:model="messageVerif"><?php echo e($messageVerif); ?></p>
                                        <h6><?php echo e($messageVerif); ?></h6>
                                        <p class="text-muted mb-4">To stay verified, don't remove the
                                            meta tag form your site's home page. To avoid losing
                                            verification, you may want to add multiple methods form the
                                            <span class="fw-medium">Crypto > KYC Application.</span>
                                        </p>

                                        <div class="hstack justify-content-center gap-2">
                                            <button onclick="doneVerify()" type="button" class="btn btn-ghost-success"
                                                    data-bs-dismiss="modal">Done <i
                                                    class="ri-thumb-up-fill align-bottom me-1"></i></button>
                                            
                                            
                                            
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end tab pane -->
                        </div>
                        <!-- end tab content -->
                    </div>
                    <!--end modal-body-->
                </form>
            </div>
        </div>
    </div>
    <script>
        var errorMail2 = document.querySelector("#error-mail");
        $("#inputEmailUser").keyup(function () {
            if ($("#inputEmailUser").val().trim() == "") {
                errorMail2.innerHTML = 'required field';
                errorMail2.classList.remove("hide");
            } else {
                errorMail2.innerHTML = '';
                errorMail2.classList.add("hide");
            }
        });
        // $("#inputEmailUser").addEventListener('keyup', function () {
        //     // resetUpPhone();
        //     alert('fsdf');
        //     if ($("#inputEmailUser").value.trim()!="") {
        //         errorMail.innerHTML = '';
        //         errorMail.classList.add("hide");
        //     }
        // });
        var nextTomail = false;
        $('input[type="file"]').each(function () {
            var $file = $(this),
                $label = $file.next('label'),
                $labelText = $label.find('span'),
                labelDefault = $labelText.text();
            $file.on('change', function (event) {

                var fileName = $file.val().split('\\').pop(),

                    tmppath = URL.createObjectURL(event.target.files[0]);

                if (fileName) {

                    $label

                        .addClass('file-ok')

                        .css('background-image', 'url(' + tmppath + ')');

                    $labelText.text(fileName);

                } else {

                    $label.removeClass('file-ok');

                    $labelText.text(labelDefault);

                }

            });

        });

        function saveimg() {
            if (checkRequiredrFieldInfo() && checkRequiredrFieldMail())
                window.livewire.emit('saveimg');
        }

        document.getElementById('pills-bill-address-tab').addEventListener('shown.bs.tab', function (event) {
            if (!checkRequiredrFieldInfo())
                $('#myTab   button[id="pills-bill-info-tab"] ').tab('show');
        });
        document.getElementById('pills-payment-tab').addEventListener('shown.bs.tab', function (event) {
            if (!checkRequiredrFieldInfo())
                $('#myTab   button[id="pills-bill-info-tab"] ').tab('show');
            if (!checkRequiredrFieldMail())
                $('#myTab   button[id="pills-bill-address-tab"] ').tab('show');
        });
        document.getElementById('pills-finish-tab').addEventListener('shown.bs.tab', function (event) {
            if (!checkRequiredrFieldInfo())
                $('#myTab   button[id="pills-bill-info-tab"] ').tab('show');
            else if (!checkRequiredrFieldMail()) {
                if ($("#inputEmailUser").val().trim() === "")
                    $("inputEmailUser").attr('required', true);
                $('#myTab   button[id="pills-bill-address-tab"] ').tab('show');
            } else {
                saveimg();
            }
        });

        function checkRequiredrFieldInfo() {
            if ($("#firstName").val().trim() === "" || $("#lastName").val().trim() === "" || $("#phoneNumber").val().trim() === "" || $("#dateofBirth").val().trim() === "")
                return false;
            return true;
        }

        function checkRequiredrFieldMail() {
            var checkOpt = false;
            if ($("#inputEmailUser").val().trim() === "") {
                var errorMail = document.querySelector("#error-mail");
                errorMail.innerHTML = 'required field';
                errorMail.classList.remove("hide");
                return false;
            }
            var failed = false;
            $.ajax({
                method: "GET",
                url: "/mailVerif",
                async: false,
                data: {
                    mail: $("#inputEmailUser").val().trim(),
                },
                success: (result) => {
                    if (result == 'no') {
                        failed = true;
                        var errorMail = document.querySelector("#error-mail");
                        errorMail.innerHTML = 'mail used';
                        errorMail.classList.remove("hide");
                        alert('mail used ');
                    }
                },
                error: (error) => {
                    alert('Something went wrong to fetch datas...');
                }
            });
            var optChecker = document.querySelector("#optChecker");
            if (!failed) {
                if (checkNewMail()) {
                    if ($('#optFirst').val().trim() == "" && $('#optSecond').val().trim() == "" && $('#optThird').val().trim() == "" && $('#optFourth').val().trim() == "") {
                        $.ajax({
                            method: "GET",
                            url: "/sendMailNotification",
                            async: false,
                            success: (result) => {
                                if (result == 'ok') {


                                }
                            },
                            error: (error) => {
                                alert('Something went wrong to send datas...');
                            }
                        });

                    }

                    optChecker.classList.remove("invisible");
                    if (checkOptVerify())
                        checkOpt = true;
                    else
                        checkOpt = false;
                } else
                    checkOpt = true;
            }
            if (failed || !checkOpt) return false
            else {
                optChecker.classList.add("invisible");
                $('#optFirst').val("");
                $('#optSecond').val("");
                $('#optThird').val("");
                $('#optFourth').val("");
                checkOpt = false;
            }
            return true;
        }

        $(function () {
            $('#btnNextAdresse').click(function (e) {
                if ($("#firstName").val() == "") {
                    e.preventDefault();
                    $('#myTab   button[id="pills-bill-info-tab"] ').tab('show');
                }
            })
        })

        function checkOptVerify() {
            var valreturn = false;
            var opt = $('#optFirst').val() + $('#optSecond').val() + $('#optThird').val() + $('#optFourth').val();

            $.ajax({
                method: "GET",
                url: "/mailVerifOpt",
                async: false,
                data: {
                    opt: opt,
                    mail: $("#inputEmailUser").val().trim(),
                },
                success: (result) => {
                    if (result == 'no') {
                        valreturn = false;
                    } else {
                        valreturn = true;
                    }
                },
                error: (error) => {
                    alert('Something went wrong to check datas...');
                }
            });
            return valreturn;
        }

        function checkNewMail() {
            var valreturn = false;
            $.ajax({
                method: "GET",
                url: "/mailVerifNew",
                async: false,
                data: {
                    mail: $("#inputEmailUser").val().trim(),
                },
                success: (result) => {
                    if (result == 'no') {
                        valreturn = false;
                    } else {
                        valreturn = true;
                    }
                },
                error: (error) => {
                    alert('Something went wrong to fetch datas...');
                }
            });
            return valreturn;
        }

        function doneVerify() {
            window.location.reload();
        }

        function verifRequest() {
            // swal.fire('fdsfsdfsdfsd');
            $("#exampleModal").modal("hide");

        }

        // $('#exampleModal').on('show.bs.modal', function (e) {
        //
        //     $("#exampleModal").modal("hide");
        // })
    </script>
</div>
<?php /**PATH C:\xampp\htdocs\modern\resources\views/livewire/identification-check.blade.php ENDPATH**/ ?>