<div>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
    <style>
        .swal2-footer {
            display: flex !important;
            flex-direction: column;
            align-items: center;
        }

        .footerOpt {
            align-self: flex-start;
        }

        .footerOpt a {
            cursor: pointer;
            font-weight: bold;
        }

        .input-container {
            display: block;
            position: relative;
            padding-left: 25px;
            margin-bottom: 12px;
            cursor: pointer;
            font-size: 18px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .input-container input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }

        .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            height: 20px;
            width: 20px;
            background-color: #eee;
            border-radius: 50%;
        }

        /* On mouse-over, add a grey background color */
        .input-container:hover input ~ .checkmark {
            background-color: #ccc;
        }

        /* When the radio button is checked, add a blue background */
        .input-container input:checked ~ .checkmark {
            background-color: #2196F3;
        }

        /* Create the indicator (the dot/circle - hidden when not checked) */
        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        /* Show the indicator (dot/circle) when checked */
        .input-container input:checked ~ .checkmark:after {
            display: block;
        }

        /* Style the indicator (dot/circle) */
        .input-container .checkmark:after {
            top: 7px;
            left: 6px;
            width: 8px;
            height: 8px;
            border-radius: 0%;
            background: white;
        }

        .payment-options-container {
            display: flex;
            justify-content: space-around;
        }

        img.funding-icon {
            width: 32px;
            height: 32px;
            margin: 0 2px;
        }

        .center {
            display: flex;
            justify-content: start;
            align-items: center;
            /*border: 3px solid green;*/
        }
    </style>
    <script data-turbolinks-eval="false">
        // $(document).on('ready turbolinks:load', function () {


        var SuccesExchange = '<?php echo e(Session::has('SuccesExchange')); ?>';
        if (SuccesExchange) {
            toastr.success('<?php echo e(Session::get('SuccesExchange')); ?>');
        }

        var exist = '<?php echo e(Session::has('ErrorOptCodeForget')); ?>';
        var msg = "your Opt code is incorrect !";
        if (exist) {
            var local = '<?php echo e(Session::has('locale')); ?>';
            if (local == 'ar') {
                msg = "هاتفك أو كلمة المرور الخاصة بك غير صحيحة !";
            }
            Swal.fire({
                title: ' ',
                text: msg,
                icon: 'error',
                confirmButtonText: <?php echo e(trans('ok')); ?>

            }).then(okay => {
                if (okay) {
                    window.location.reload();
                }
            });
        }
        var existvalide = '<?php echo e(Session::has('succesOpttSms')); ?>';
        if (existvalide) {
            var someTabTriggerEl = document.querySelector('#pills-UpdatePhone-tab');
            var tab = new bootstrap.Tab(someTabTriggerEl);
            tab.show();
            toastr.success('Succées');
        }
        var SuccesRequstAccepted = '<?php echo e(Session::has('SuccesRequstAccepted')); ?>';
        if (SuccesRequstAccepted) {
            var someTabTriggerEl = document.querySelector('#pills-contact-tab');
            var tab = new bootstrap.Tab(someTabTriggerEl);
            tab.show();
            toastr.success('Succées');
        }
        var ErreurSoldeReqBFS2 = '<?php echo e(Session::has('ErreurSoldeReqBFS2')); ?>';
        if (ErreurSoldeReqBFS2) {
            var someTabTriggerEl = document.querySelector('#pills-contact-tab');
            var tab = new bootstrap.Tab(someTabTriggerEl);
            tab.show();
            Swal.fire({
                title: '<?php echo e(trans('SureTransfertCashBFS')); ?>'  ,
                text: '<?php echo e(trans('SoldeRequestInsuffisant')); ?> ' ,
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText:'<?php echo e(trans('Cancel')); ?>',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '<?php echo e(trans('Yes')); ?>'
            }).then((result) => {
                if (result.isConfirmed) {
                    var url_string = window.location.href;
                    var url = new URL(url_string);
                    var paramValue = url.searchParams.get("FinRequestN");

                    window.livewire.emit('redirectToTransfertCash', '<?php echo e(Session::get('ErreurSoldeReqBFS2')); ?>', paramValue);
                }
            })
            
            
            
            
            
            
        }
        var ErreurSoldeReqCash = '<?php echo e(Session::has('ErreurSoldeReqCash')); ?>';
        if (ErreurSoldeReqCash) {
            var someTabTriggerEl = document.querySelector('#pills-contact-tab');
            var tab = new bootstrap.Tab(someTabTriggerEl);
            tab.show();
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '<?php echo e(Session::get('ErreurSoldeReqCash')); ?>',
                footer: '<a href="">Why do I have this issue?</a>'
            })
        }
        var SuccesSendPublicRequest = '<?php echo e(Session::has('SuccesSendPublicRequest')); ?>';
        if (SuccesSendPublicRequest) {
            var someTabTriggerEl = document.querySelector('#pills-profile-tab');
            var tab = new bootstrap.Tab(someTabTriggerEl);
            tab.show();
            Swal.fire({
                icon: 'success',
                title: '<?php echo e(trans('success send public request')); ?>',
                html: '<span style ="color : red;" > <?php echo e(trans('verification_code')); ?> </span>' + '<?php echo e(Session::get('SuccesSendPublicRequest')); ?>',
                footer: '<?php echo e(trans('save security code')); ?>',
                confirmButtonText: '<?php echo e(trans('ok')); ?>',
            })
        }
        var tabRequest = '<?php echo e(Session::has('tabRequest')); ?>';
        if (tabRequest) {
            var someTabTriggerEl = document.querySelector('#pills-profile-tab');
            var tab = new bootstrap.Tab(someTabTriggerEl);
            tab.show();

        }
    </script>
    
    
    
    
    
    
    
    
    

    

    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?> <?php echo e(__('Financial_Transaction')); ?> <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>
    <div class="row">
        <div class="col-xxl-12">

            <div class="card">
                <div class="card-body">

                    <!-- Nav tabs -->
                    <ul id="pills-tab" class="nav nav-tabs nav-justified mb-3" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" data-bs-toggle="tab" href="#cash_bfs" role="tab"
                               aria-selected="false" tabindex="-1">
                                <?php echo __('Cash >> BFS'); ?>

                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link align-middle" data-bs-toggle="tab" href="#bfs_funding" role="tab"
                               aria-selected="false" tabindex="-1">
                                <?php echo e(__('BFS Funding')); ?>

                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link align-middle" data-bs-toggle="tab" href="#bfs_sms" role="tab"
                               aria-selected="false" tabindex="-1">
                                <?php echo __('BFS >> SMS'); ?>

                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a id="pills-profile-tab" class="nav-link" data-bs-toggle="tab" href="#me_others" role="tab"
                               aria-selected="true">
                                <?php echo __('Requests: Me >> Others'); ?>

                                <?php if($requestOutAccepted>0): ?>
                                    <button id="btnNotRequestOutAcccepted" type="button"
                                            class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle">
                                        <i class=" ri-user-follow-line text-success fs-22"></i>
                                        <span id="pOutAccepted"
                                              class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-success"> <?php echo e($requestOutAccepted); ?></span>
                                    </button>

                                <?php endif; ?>
                                <?php if($requestOutRefused>0): ?>
                                    <button id="btnNotRequestOutRefused" type="button"
                                            class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle">
                                        <i class=" ri-user-unfollow-line text-danger fs-22"></i>
                                        <span id="pOutRefused"
                                              class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-danger"><?php echo e($requestOutRefused); ?></span>
                                    </button>
                                <?php endif; ?>

                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a id="pills-contact-tab" class="nav-link" data-bs-toggle="tab" href="#others_me" role="tab"
                               aria-selected="true">
                                <?php echo __('Requests: Others >> Me'); ?>

                                <?php if($requestInOpen>0): ?>
                                    <button id="btnNotRequestInOpen" type="button"
                                            class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle">
                                        <i class="  ri-user-received-2-line text-primary fs-22"></i>
                                        <span id="pIn"
                                              class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-primary">  <?php echo e($requestInOpen); ?></span>
                                    </button>
                                <?php endif; ?>


                            </a>
                        </li>
                    </ul>
                    <!-- Nav tabs -->
                    <div class="tab-content text-muted">
                        <div class="tab-pane active show" id="cash_bfs" role="tabpanel">

                            <div class="card">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1"><?php echo e(__('BFS_Transaction')); ?></h4>

                                </div><!-- end card header -->
                                <div class="card-body">


                                    <div class="row gy-4">
                                        <div class="col-xxl-4 mx-auto ">
                                            <div class="input-group">
                                                <span class="input-group-text"
                                                      id="inputGroup-sizing-default"><?php echo e(__('Enter your amount')); ?></span>
                                                <input type="number"
                                                       name="montantExchange"
                                                       id="montantExchange"
                                                       onpaste="handlePaste(event)"
                                                       class="form-control text-center "
                                                       placeholder="<?php echo e(__('Enter your amount')); ?>"
                                                       onpaste="handlePaste(event)" wire:model.defer="soldeExchange">
                                            </div>
                                        </div>

                                        <?php if(config('app.available_locales')[app()->getLocale()]['direction']=='ltr'): ?>
                                        <div class="col-1 mx-auto d-none d-xxl-block ">
                                            <i class="ri-arrow-right-s-line fs-3 me-n3 text-primary"></i>
                                            <i class="ri-arrow-right-s-line fs-3 ms-n1  text-primary"></i>
                                        </div>
                                        <?php else: ?>
                                            <div class="col-1 mx-auto d-none d-xxl-block ">
                                                <i class="ri-arrow-left-s-line fs-3 me-n3 text-primary"></i>
                                                <i class="ri-arrow-left-s-line fs-3 ms-n1  text-primary"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div class="col-1 mx-auto d-xxl-none">
                                            <i class=" ri-download-line fs-3 mt-n3 text-primary"></i>

                                        </div>

                                        <!--end col-->
                                        <div class="col-xxl-4 mx-auto ">
                                            <div class="input-group">
                                                <span class="input-group-text"
                                                      id="inputGroup-sizing-default"><?php echo e(__('Balance For Shopping')); ?></span>
                                                <input type="number"
                                                       name="soldeBFS" id="soldeBFS" class="form-control text-center"
                                                       value="" disabled
                                                       onpaste="handlePaste(event)">
                                            </div>
                                        </div>
                                        <div class="col-6 mx-auto ">
                                            <button class="btn btn-primary w-100 mt-3 btn2earn" onclick="ConfirmExchange()"
                                                    id="exchange"><?php echo e(__('Exchange Now')); ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="bfs_funding" role="tabpanel">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title"><?php echo e(__('backand_BFS_Account_Funding')); ?></h4>
                                </div>
                                <div class="card-body">


                                    <div class="row gy-4">
                                        <div class="col-xxl-8 mx-auto ">
                                            <div class="input-group">
                                                <span class="input-group-text"
                                                      id="inputGroup-sizing-default"><?php echo e(__('backand_Amount_to_Fund_in_DCP')); ?></span>
                                                <input style="color: #939393" type="number" name="fundAmountTXT"
                                                       id="amount"
                                                       class="form-control text-center"
                                                       placeholder="<?php echo e(__('backand_Enter_the_funding_amount')); ?>"
                                                       onpaste="handlePaste(event)">
                                            </div>
                                        </div>


                                        <div class="col-xxl-8 mx-auto text-center ">
                                            <h5 class="mb-5 text-center "> <?php echo e(__('backand_Choose_payment_option')); ?></h5>
                                            <div class="form-check form-check-inline ">

                                                <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                                       value="paypal" id="paypal" onclick="setPaymentFormTarget(0)">
                                                <label class="form-check-label fs-5 text-primary"
                                                       for="flexSwitchCheckChecked"><i
                                                        class="ri-paypal-fill me-2 "></i><?php echo e(__('Paypal')); ?></label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                                       value="creditCard" id="creditCard"
                                                       onclick="setPaymentFormTarget(1)">
                                                <label class="form-check-label fs-5 text-success "
                                                       for="flexSwitchCheckChecked"><i
                                                        class="ri-bank-card-fill me-2 "></i><?php echo e(__('Creditcard')); ?></label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                                       value="publicUser" id="publicUser"
                                                       onclick="setPaymentFormTarget(3)">
                                                <label class="form-check-label fs-5 text-danger "
                                                       for="flexSwitchCheckChecked"><i
                                                        class="ri-team-fill me-2"></i><?php echo e(__('PublicUsers')); ?></label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                                       value="upline" id="upline" onclick="setPaymentFormTarget(2)">
                                                <label class="form-check-label fs-5 text-warning"
                                                       for="flexSwitchCheckChecked"><i
                                                        class="ri-user-2-fill me-2 "></i><?php echo e(__('requstAdmin')); ?></label>
                                            </div>
                                        </div>
                                        <div class="col-xxl-8 mx-auto ">
                                            <button class="btn btn-primary w-100 mt-3 btn2earn"
                                                    id="pay"><?php echo e(__('backand.Fund')); ?></button>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="tab-pane" id="bfs_sms" role="tabpanel">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title"><?php echo e(__('backand_BFS_Account_Funding')); ?></h4>
                                </div>
                                <div class="card-body">
                                    <div
                                        class="alert alert-info border-0 rounded-top rounded-0 m-0 d-flex align-items-center mb-3">
                                        <div class="flex-grow-1 text-truncate ">
                                            <?php echo e(__('SMS price')); ?> <b><?php echo e($prix_sms); ?> </b> <?php echo e(__('DPC')); ?>

                                        </div>
                                    </div>

                                    <div class="row gy-4">
                                        <div class="col-xxl-8 mx-auto ">
                                            <div class="input-group">
                                                <span class="input-group-text"
                                                      id="inputGroup-sizing-default"><?php echo e(__('Enter number of SMS')); ?></span>
                                                <input type="number"
                                                       oninput="this.value = this.value.replace(/[^0-9]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                                       name="NSMS" id="NSMS"
                                                       class="form-control text-center" placeholder=""
                                                       onpaste="handlePaste(event)">
                                                <span class="input-group-text"
                                                      id="inputGroup-sizing-default"><?php echo e(__('Enter your amount')); ?></span>
                                                <input type="number" name="soldeSMS" id="soldeSMS"
                                                       class="form-control text-center"
                                                       placeholder="<?php echo e(__('Enter your amount')); ?>"
                                                       onpaste="handlePaste(event)">
                                            </div>
                                        </div>


                                        <div class="col-xxl-8 mx-auto text-center ">
                                            <div class="input-group">
                                                <span class="input-group-text"
                                                      id="inputGroup-sizing-default"><?php echo e(__('Balance For Shopping')); ?></span>
                                                <input type="number" name="soldeBFSSMS" id="soldeBFSSMS"
                                                       class="form-control text-center" disabled>

                                            </div>


                                        </div>
                                        <div class="col-xxl-8 mx-auto ">
                                            <button class="btn btn-primary w-100 mt-3 btn2earn" id="submitExchangeSms"
                                                    onclick="ConfirmExchangeSms()"> <?php echo e(__('Exchange Now')); ?></button>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="tab-pane " id="me_others" role="tabpanel">
                            <div class="card-header">
                                <div class="form-check">
                                    <input onclick="ShowCanceledRequest()" class="form-check-input" type="checkbox"
                                           <?php if($showCanceled == 1): ?> checked <?php endif; ?> value="" id="ShowCanceled">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        <?php echo e(__('show_canceled')); ?>

                                    </label>
                                </div>
                            </div>

                            <div class="card-body pt-0">

                                <div class="table-responsive ">
                                    <table class=" table table-responsive tableEditAdmin"
                                           id="ReqFromMe_table2"
                                           style="width: 100%">
                                        <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th><?php echo e(__('numeroReq')); ?></th>
                                            <th><?php echo e(__('date')); ?></th>
                                            <th><?php echo e(__('Amount')); ?></th>
                                            <th><?php echo e(__('Status')); ?></th>
                                            

                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $__currentLoopData = $requestFromMee; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                
                                                
                                                
                                                
                                                <td onclick="hiddenTr(<?php echo e($value->numeroReq); ?>)"><i style="color: #51A351"
                                                                                                 class="fas fa-plus-circle"></i>
                                                </td>
                                                <td onclick="hiddenTr(<?php echo e($value->numeroReq); ?>)">
                                                    <span><?php echo e($value->numeroReq); ?></span></td>
                                                <td onclick="hiddenTr(<?php echo e($value->numeroReq); ?>)">
                                                    <span> <?php echo e($value->date); ?></span>
                                                </td>
                                                
                                                
                                                <td onclick="hiddenTr(<?php echo e($value->numeroReq); ?>)">
                                                    <span><?php echo e($value->amount); ?></span></td>
                                                <td><span>
                                                    <?php if($value->FStatus == 0): ?>
                                                            <a style="background-color: #F89406;color: #FFFFFF;border-color: transparent;border-radius: 3px;padding:<?php if(app()->getLocale()=="ar"): ?> 1px <?php else: ?> 5px <?php endif; ?> ; "><?php echo e(__('Opened')); ?></a>
                                                            <a onclick="cancelRequestF('<?php echo e($value->numeroReq); ?>')"
                                                               style="background-color: #3595f6;color: #FFFFFF;border-color: transparent;border-radius: 3px;padding: <?php if(app()->getLocale()=="ar"): ?> 1px <?php else: ?> 5px <?php endif; ?> ; "><?php echo e(__('Cancel')); ?></a>
                                                        <?php elseif($value->FStatus == 1): ?>
                                                            <a style="background-color: #51A351;color: #FFFFFF;border-color: transparent;border-radius: 3px;padding: <?php if(app()->getLocale()=="ar"): ?> 1px <?php else: ?> 5px <?php endif; ?> ; "><?php echo e(__('Accepted')); ?></a>
                                                        <?php elseif($value->FStatus == 3): ?>
                                                            <a style="background-color: #f02602;color: #FFFFFF;border-color: transparent;border-radius: 3px;padding: <?php if(app()->getLocale()=="ar"): ?> 1px <?php else: ?> 5px <?php endif; ?> ; "><?php echo e(__('Canceled')); ?></a>
                                                        <?php else: ?>
                                                            <a style="background-color: #BD362F;color: #FFFFFF;border-color: transparent;border-radius: 3px;padding: <?php if(app()->getLocale()=="ar"): ?> 1px <?php else: ?> 5px <?php endif; ?> ; "><?php echo e(__('Rejected')); ?></a>
                                                        <?php endif; ?>
                                                </span></td>


                                                
                                            </tr>
                                            <tr hidden="true" id=<?php echo e($value->numeroReq); ?>>
                                                <td colspan="12">
                                                    <table class=" table table-responsive table2earn "
                                                           style="width: 100%"
                                                    >
                                                        <thead>
                                                        <tr>
                                                            <th><?php echo e(__('User')); ?></th>
                                                            <th><?php echo e(__('Mobile Number')); ?></th>
                                                            <th><?php echo e(__('response')); ?></th>
                                                            <th><?php echo e(__('dateResponse')); ?></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php $__currentLoopData = $value->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $valueD): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php if($valueD->user !=null ): ?>
                                                                <tr>
                                                                    <td><span> <?php echo e($valueD->User->name); ?></span></td>
                                                                    <td><span> <?php echo e($valueD->User->mobile); ?></span></td>
                                                                    <td><span>
                                                    <?php if($valueD->response == "" ||$valueD->response == null ): ?>
                                                                                <a style="background-color: #F89406;color: #FFFFFF;border-color: transparent;border-radius: 3px;padding: 5px"><?php echo e(__('No Response')); ?></a>
                                                                            <?php elseif($valueD->response == 1): ?>
                                                                                <a style="background-color: #51A351;color: #FFFFFF;border-color: transparent;border-radius: 3px;padding: 5px"><?php echo e(__('Accepted')); ?></a>
                                                                            <?php elseif($valueD->response == "2"): ?>
                                                                                <a style="background-color: #BD362F;color: #FFFFFF;border-color: transparent;border-radius: 3px;padding: 5px"><?php echo e(__('Rejected')); ?></a>
                                                                            <?php elseif($valueD->response == "3"): ?>
                                                                                <a style="background-color: #BD362F;color: #FFFFFF;border-color: transparent;border-radius: 3px;padding: 5px"><?php echo e(__('Canceled')); ?></a>
                                                                            <?php endif; ?>
                                                </span></td>

                                                                    <td><span> <?php echo e($valueD->dateResponse); ?></span></td>
                                                                </tr>

                                                            <?php endif; ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                </div>
                            </div>

                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                                 aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            ...
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                Close
                                            </button>
                                            <button type="button" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="tab-pane" id="others_me" role="tabpanel">
                            <div class="card-body table-responsive ">

                                <table class="table align-middle dt-responsive nowrap" id="customerTable2"
                                       style="width: 100%">
                                    <thead class="table-light">
                                    <tr class="tabHeader2earn">

                                        <th><?php echo e(__('Request')); ?></th>
                                        <th><?php echo e(__('Date')); ?></th>
                                        <th><?php echo e(__('User')); ?></th>
                                        <th><?php echo e(__('UserPhone')); ?></th>
                                        <th><?php echo e(__('Amount')); ?></th>
                                        <th><?php echo e(__('Status')); ?></th>
                                        <th><?php echo e(__('Actions')); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody class="list form-check-all">
                                    <?php $__currentLoopData = $requestToMee; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><span><?php echo e($value->numeroReq); ?></span></td>
                                            <td><span> <?php echo e($value->date); ?></span></td>
                                            <td><span> <?php echo e($value->name); ?></span></td>
                                            <td><span><?php echo e($value->mobile); ?></span></td>
                                            <td><span><?php echo e(number_format((float)$value->amount, 2, '.', ' ')); ?> </span>
                                            </td>

                                            <td><span>
                                                    <?php if($value->status == 0): ?>
                                                        <a style="background-color: #51A351;color: #FFFFFF;border-color: transparent;border-radius: 3px;padding: 5px"><?php echo e(__('Opened')); ?></a>
                                                    <?php else: ?>
                                                        <a style="background-color: #BD362F;color: #FFFFFF;border-color: transparent;border-radius: 3px;padding: 5px"><?php echo e(__('Closed')); ?></a>
                                                    <?php endif; ?>

                                                </span></td>

                                            <td>
                                                <?php if($value->status == 0): ?>
                                                    <i onclick="acceptRequst('<?php echo e($value->numeroReq); ?>')"
                                                       style="cursor:pointer; color: #51A351;font-size: 20px;margin: 5px 5px"
                                                       class="fa-regular fa-circle-check"></i>

                                                    <i onclick="rejectRequst('<?php echo e($value->numeroReq); ?>')"
                                                       style="cursor:pointer; color: #BD362F;font-size: 20px;margin: 5px 5px"
                                                       class="fa-regular fa-circle-xmark"></i>

                                                <?php endif; ?>
                                            </td>

                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                                    </tbody>
                                </table>

                            </div>

                        </div>
                    </div>
                </div><!-- end card-body -->
            </div>
            <!--end card-->
        </div>
    </div>

    <script>
        // financial transaction
        var mnt = <?php echo e($testprop); ?>;

        var prixSms = <?php echo e($prix_sms); ?>;
        var soldeBFS = <?php echo e($soldeBFS); ?>;

        var inputMontantSms = $("#soldeSMS");
        var inputSms = $("#NSMS");
        var inputsoldeBFSSMS = $("#soldeBFSSMS");

        var inputsoldeBFS = $("#soldeBFS");

        var Mymnt = <?php echo e($soldeExchange); ?>;

        var newmntBFS = soldeBFS + Mymnt;
        inputsoldeBFS.val(newmntBFS.toFixed(2));
        $("#montantExchange").keyup(function () {
            var tt = parseFloat(soldeBFS) + parseFloat($(this).val());
            inputsoldeBFS.val(tt);
            // var soldeBFS=$(this).val() + montantExchange ;
            // inputsoldeBFS.text(soldeBFS);

        })
        inputSms.val(mnt);
        var mntSms = mnt * prixSms;
        var newsoldeBFS = soldeBFS - mntSms
        inputMontantSms.val(mntSms.toFixed(2));
        inputsoldeBFSSMS.val(newsoldeBFS.toFixed(2));
        $("#NSMS").keyup(function () {
            var montantSms = $(this).val() * prixSms;
            // inputMontantSms.text(montantSms);
            inputMontantSms.val(montantSms.toFixed(2));
            var newsolde = soldeBFS - montantSms;
            newsoldeBFS = soldeBFS - montantSms;
            inputsoldeBFSSMS.val(newsolde.toFixed(2));
            if (montantSms == 0) {
                $("#submitExchangeSms").prop('disabled', true);
            } else {
                $("#submitExchangeSms").prop('disabled', false);
            }

        });

        $("#NSMS").keyup(function () {
            var montantSms = $(this).val() * prixSms;
            // inputMontantSms.text(montantSms);
            inputMontantSms.val(montantSms.toFixed(2));
            var newsolde = soldeBFS - montantSms;
            newsoldeBFS = soldeBFS - montantSms;
            inputsoldeBFSSMS.val(newsolde.toFixed(2));
            if (montantSms == 0) {
                $("#submitExchangeSms").prop('disabled', true);
            } else {
                $("#submitExchangeSms").prop('disabled', false);
            }

        });
        $("#soldeSMS").focusout(function () {
            var sms = $("#NSMS").val();
            var mnt = sms * prixSms;
            inputMontantSms.val(mnt.toFixed(2));
            var newsolde = soldeBFS - mnt;
            newsoldeBFS = soldeBFS - mnt;
            inputsoldeBFSSMS.val(newsolde.toFixed(2));
        });
        $("#submitExchangeSms").prop('disabled', true);
        window.addEventListener('OptExBFSCash', event => {
            Swal.fire({
                title: '<?php echo e(__('Your verification code')); ?>',
                html: '<?php echo e(__('We_will_send')); ?><br>' + event.detail.FullNumber + '<br>' + '<?php echo e(__('Your OTP Code')); ?>',
                input: 'text',
                allowOutsideClick: false,
                timer: '<?php echo e(env('timeOPT')); ?>',
                timerProgressBar: true,
                confirmButtonText: '<?php echo e(trans('ok')); ?>',
                showCancelButton: true,
                cancelButtonText: '<?php echo e(trans('canceled !')); ?>',
                footer: ' <i></i><div class="footerOpt"></div>',
                didOpen: () => {
                    // Swal.showLoading()
                    const b = Swal.getFooter().querySelector('i')
                    const p22 = Swal.getFooter().querySelector('div')
                
                    timerInterval = setInterval(() => {
                        p22.innerHTML = '<?php echo e(trans('It will close in')); ?>' + ' ' + (Swal.getTimerLeft() / 1000).toFixed(0) + ' ' + '<?php echo e(trans('secondes')); ?>' + '</br> '  + '<?php echo e(trans('Dont get code?')); ?>' + ' <a>' + '<?php echo e(trans('Resend')); ?>' + '</a> '
                    }, 100)
                },

                willClose: () => {
                    clearInterval(timerInterval)
                },
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                inputAttributes: {
                    autocapitalize: 'off'
                },


            }).then((resultat) => {
                if (resultat.value) {
                    window.livewire.emit('ExchangeCashToBFS', resultat.value);
                }
                if (resultat.isDismissed) {
                    location.reload();
                }
            })
        })

        function ConfirmExchange() {
            var soldecashB = <?php echo e($soldecashB); ?>;
            var soldeExchange = $("#montantExchange").val();
            if (Number.isNaN(soldecashB) || Number.isNaN(soldeExchange)) return;
            if (soldeExchange < 0) return;
            if (soldeExchange == 0) {
                Swal.fire({
                    title: '<?php echo e(trans('Please enter the transfer amount!')); ?>',
                    icon: "warning",
                    showCancelButton: false,
                    confirmButtonText: '<?php echo e(trans('ok')); ?>',
                })
                return;
            }
            var newSolde = soldecashB - soldeExchange;
            if (newSolde < 0) {
                Swal.fire({
                    title: '<?php echo e(trans('Your_cash_balance')); ?>',
                    icon: "warning",
                    showCancelButton: false,
                    confirmButtonText: '<?php echo e(trans('ok')); ?>',

                })
                return;
            }

            Swal.fire({
                title: '<?php echo e(trans('Are you sure to exchange ?')); ?>' + " " + '<br>' + soldeExchange + "$ " + '<?php echo e(trans('?')); ?>',
                text: '<?php echo e(trans('operation_irreversible')); ?>',
                icon: "warning",
                // showDenyButton: true,
                showCancelButton: true,
                cancelButtonText: '<?php echo e(trans('canceled !')); ?>',
                confirmButtonText: '<?php echo e(trans('ok')); ?>',
                denyButtonText: 'No',
                customClass: {
                    actions: 'my-actions',
                    cancelButton: 'order-1 right-gap',
                    confirmButton: 'order-2',
                    denyButton: 'order-3',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.livewire.emit('PreExchange');
                } else if (result.isDenied) {
                    Swal.fire('Changes are not saved', '', 'info')
                }
            })
        }

        function ConfirmExchangeSms() {
            var soldeBFS = <?php echo e($soldeBFS); ?>;
            var nbSMS = $("#NSMS").val();
            var soldeExchange = $("#soldeSMS").val();
            // alert(soldeExchange) ;
            if (Number.isNaN(nbSMS) || Number.isNaN(soldeExchange)) return;
            if (soldeExchange < 0) return;
            if (soldeExchange == 0) {
                Swal.fire({
                    title: '<?php echo e(trans('Please enter the transfer amount!')); ?>',
                    icon: "warning",
                    showCancelButton: false,
                    confirmButtonText: '<?php echo e(trans('ok')); ?>',
                })
                return;
            }
            var newSolde = soldeBFS - soldeExchange;
            if (newSolde < 0) {
                Swal.fire({
                    title: '<?php echo e(trans('BFS_not_allow')); ?>',
                    icon: "warning",
                    showCancelButton: false,
                    confirmButtonText: '<?php echo e(trans('ok')); ?>',
                })
                return;

            }
            Swal.fire({
                title: '<?php echo e(trans('Are you sure to exchange ?')); ?>' + '<br>' + " " + soldeExchange + "$ " + '<?php echo e(trans('?')); ?>',
                text: '<?php echo e(trans('operation_irreversible')); ?>',
                icon: "warning",
                // showDenyButton: true,
                showCancelButton: true,
                cancelButtonText: '<?php echo e(trans('canceled !')); ?>',
                confirmButtonText: '<?php echo e(trans('ok')); ?>',
                denyButtonText: 'No',
                customClass: {
                    actions: 'my-actions',
                    cancelButton: 'order-1 right-gap',
                    confirmButton: 'order-2',
                    denyButton: 'order-3',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.livewire.emit('PreExchangeSMS');
                } else if (result.isDenied) {
                    Swal.fire('Changes are not saved', '', 'info')
                }
            })
        }

        window.addEventListener('confirmSms', event => {
            Swal.fire({
                title: '<?php echo e(__('Your verification code')); ?>',
                html: '<?php echo e(__('We_will_send')); ?><br> ',
                html: '<?php echo e(__('We_will_send')); ?><br>' + event.detail.FullNumber + '<br>' + '<?php echo e(__('Your OTP Code')); ?>',
                input: 'text',
                allowOutsideClick: false,
                timer: '<?php echo e(env('timeOPT')); ?>',
                timerProgressBar: true,
                confirmButtonText: '<?php echo e(trans('ok')); ?>',
                showCancelButton: true,
                cancelButtonText: '<?php echo e(trans('canceled !')); ?>',
                footer: '<div class="footerOpt"></div>',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                didOpen: () => {
                    // Swal.showLoading()
                    const b = Swal.getFooter().querySelector('i')
                    const p22 = Swal.getFooter().querySelector('div')


                    timerInterval = setInterval(() => {
                        p22.innerHTML = '<?php echo e(trans('It will close in')); ?>' + (Swal.getTimerLeft() / 1000).toFixed(0) + '<?php echo e(trans('secondes')); ?>' + '</br>'+'<?php echo e(trans('Dont get code?')); ?>' + ' <a>' + '<?php echo e(trans('Resend')); ?>' + '</a>'
                    }, 100)
                },
                willClose: () => {
                    clearInterval(timerInterval)
                },
            }).then((resultat) => {
                if (resultat.value) {
                    window.livewire.emit('exchangeSms', resultat.value, $("#NSMS").val());
                }
                if (resultat.isDismissed) {
                    location.reload();
                }
            })
        })

        var theUrl = '';

        function setPaymentFormTarget(gate) {
            console.log('gate', gate);
            if (gate == 0) {
                theUrl = "paymentpaypal";
            } else if (gate == 1) {
                theUrl = "paymentcreditcard";
            } else if (gate == 2) {
                theUrl = "paymentviaadmin";
            } else if (gate == 3) {
                theUrl = "req_public_user";
            }
        }

        function acceptRequst(numeroRequest) {
            window.livewire.emit('AcceptRequest', numeroRequest);
        }

        function rejectRequst(numeroRequest) {
            Swal.fire({
                title: `<?php echo e(trans('reject_request')); ?>`,
                confirmButtonText: '<?php echo e(trans('Yes')); ?>',
                showCancelButton: true,
                cancelButtonText: '<?php echo e(trans('No')); ?>',
                customClass: {
                    actions: 'my-actions',
                    confirmButton: 'order-2',
                    denyButton: 'order-3',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.livewire.emit('RejectRequest', numeroRequest);
                }
            })

        }

        function hiddenTr(num) {
            $("#" + num).prop("hidden", !$("#" + num).prop("hidden"));
        }

        function cancelRequestF(numReq) {


            Swal.fire({
                title: `<?php echo e(trans('cancel_request')); ?>`,
                confirmButtonText: '<?php echo e(trans('Yes')); ?>',
                showCancelButton: true,
                cancelButtonText: '<?php echo e(trans('No')); ?>',
                denyButtonText: 'No',
                customClass: {
                    actions: 'my-actions',
                    confirmButton: 'order-2',
                    denyButton: 'order-3',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.livewire.emit('DeleteRequest', numReq);
                }
            })

        }

        function ShowCanceledRequest() {

            if (document.getElementById('ShowCanceled').checked) {
                window.livewire.emit('ShowCanceled', 1)
            } else {
                window.livewire.emit('ShowCanceled', 0)
            }

        }

    </script>
    <script data-turbolinks-eval="false">
        var triggerTabList = [].slice.call(document.querySelectorAll('#pills-tab a'))
        triggerTabList.forEach(function (triggerEl) {
            // alert('edr')
            var tabTrigger = new bootstrap.Tab(triggerEl)
            triggerEl.addEventListener('click', function (event) {
                var x = triggerEl.id;

                if (triggerEl.id === "pills-contact-tab") {

                    $.ajax({
                        url: "<?php echo e(route('resetInComingNotification')); ?>",
                        // data: {"id":id},
                        type: 'get',
                        success: function (result) {
                            try {
                                document.getElementById('pIn').innerHTML = "";
                                document.getElementById('btnNotRequestInOpen').remove();
                            } catch (e) {
                            }
                            try {
                                document.getElementById('sideNotIn').innerHTML = "";
                                document.getElementById('sideNotIn').remove();
                            } catch (e) {
                            }


                            // console.log(result)
                        }
                    });

                    // window.livewire.emit('resetInComingNotification');
                    // alert('not 2') ;
                }
                if (triggerEl.id === "pills-profile-tab") {
                    $.ajax({
                        url: "<?php echo e(route('resetOutGoingNotification')); ?>",
                        // data: {"id":12},
                        type: 'get',
                        success: function (result) {
                            try {
                                document.getElementById('pOutAccepted').innerHTML = "";
                                document.getElementById('btnNotRequestOutAcccepted').remove();

                            } catch (e) {
                            }
                            try {
                                document.getElementById('pOutRefused').innerHTML = "";
                                document.getElementById('btnNotRequestOutRefused').remove();


                            } catch (e) {
                            }
                            try {
                                document.getElementById('sideNotOutRefused').innerHTML = "";
                                document.getElementById('sideNotOutRefused').remove();
                            } catch (e) {
                            }
                            try {
                                document.getElementById('sideNotOutAccepted').innerHTML = "";
                                document.getElementById('sideNotOutAccepted').remove();

                            } catch (e) {
                            }

                            // $("#pOut").
                            // console.log(result)
                        }
                    });
                    // window.livewire.emit('resetOutGoingNotification');
                    // alert('not 1') ;
                }
            })
        })
        $("#pay").click(function () {
            var amount = $("#amount").val();
            console.log('amount', amount);
            if (!(amount) || (amount == 0)) {
                swal.fire({
                    title: `<?php echo e(trans('the funding amount field can not be empty or 0! Please enter a valid amount!')); ?>`,
                    icon: "error",
                    confirmButtonText: '<?php echo e(trans('Yes')); ?>'
                });
                return;
            }
            if ((!$("#paypal").is(':checked')) && (!$("#creditCard").is(':checked')) && (!$("#upline").is(':checked')) && (!$("#publicUser").is(':checked'))) {
                swal.fire({
                    title: `<?php echo e(trans('choose_payment_option')); ?>`,
                    icon: "error",
                    confirmButtonText: '<?php echo e(trans('Yes')); ?>'
                });
                return;
            }
            window.livewire.emit('redirectPay', theUrl, amount);
        });
        var lan = "<?php echo e(config('app.available_locales')[app()->getLocale()]['tabLang']); ?>";
        var urlLang = "//cdn.datatables.net/plug-ins/1.12.1/i18n/" + lan + ".json";
        $('#customerTable2').DataTable({
            order: [[1, 'desc']],
            "language": {
                "url": urlLang
            }

        });
    </script>
</div>
<?php /**PATH C:\wamp64\www\2earn\resources\views/livewire/financial-transaction.blade.php ENDPATH**/ ?>