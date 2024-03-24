<div>











    <div wire:loading>
        <div style="display: flex;justify-content: center;
align-items: center;background-color: black;position: fixed;top: 0px;left: 0px;z-index: 9999;width: 100%;height: 100%;opacity: 0.75">
            <div class="la-ball-pulse-rise">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    </div>
    <script data-turbolinks-eval="false">
        var ErrorOptAddNumber = '<?php echo e(Session::has('ErrorOptAddNumber')); ?>';
        if (ErrorOptAddNumber) {
            Swal.fire({
                title: '<?php echo e(Session::get('ErrorOptAddNumber')); ?>',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            });
        }

        var numberPhoneexiste = '<?php echo e(Session::has('numberPhoneexiste')); ?>';
        if (numberPhoneexiste) {
            Swal.fire({
                title: '<?php echo e(Session::get('numberPhoneexiste')); ?>',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            });
        }

        var succesUpdateNumber = '<?php echo e(Session::has('succesUpdate')); ?>';
        if (succesUpdateNumber) {
            location.reload();
        }

        var failedDeleteIDNumber = '<?php echo e(Session::has('failedDeleteIDNumber')); ?>';
        if (failedDeleteIDNumber) {
            Swal.fire({
                title: '<?php echo e(Session::get('failedDeleteIDNumber')); ?>',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            });
        }
        //
    </script>
    
    
    
    
    
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">






                    <div class="">
                        <button style="border-radius: 20px; padding: 5px 20px;" data-bs-toggle="modal"
                                data-bs-target="#AddContactNumberModel" type="button"
                                class="btn btn-primary"><?php echo e(__('Add_contact-number')); ?>

                        </button>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle"
                           style="width:100%">
                        <thead class="table-light">
                        <tr class="tabHeader2earn">

                            <th scope="mobile"><?php echo e(__('ID_Number')); ?></th>
                            <th scope="mobile"><?php echo e(__('Mobile Number')); ?></th>
                            <th scope="Active"><?php echo e(__('Active')); ?></th>
                            <th><?php echo e(__('Country')); ?></th>
                            <th scope="">#</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $__currentLoopData = $userContactNumber; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>

                                <td style="text-align: center">

                                    <?php if($value->isID ==1): ?>
                                        <span>
                                        <i style="color: #51A351;font-size: 20px" class="ri-checkbox-circle-line"></i>
                                    </span>
                                    <?php else: ?>
                                        <span>
                                        <i style="color: #f02602;font-size: 20px" class="ri-close-circle-line"></i>
                                    </span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($value->fullNumber); ?></td>
                                <td <?php if($value->active != 1){ ?> onclick="setActiveNumber(<?php echo e($value->id); ?>)" <?php }?> >
                                    <div class="form-check form-switch" dir="ltr">
                                        <input <?php if($value->active == 1){ ?> checked disabled
                                               style="background-color: #3595f6!important; opacity: 6"
                                               <?php }?>     type="checkbox" class="form-check-input"
                                               id="customSwitchsizesm">
                                        
                                        
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center fw-medium">
                                        <img src="<?php echo e(URL::asset('assets/images/flags/'.$value->isoP.'.svg')); ?>" alt="" class="avatar-xxs me-2">
                                        <a href="javascript:void(0);" class="currency_name"> <?php echo e(getCountryByIso($value->isoP)); ?></a>
                                    </div>
                                </td>

                                <td>
                                    <?php if($value->active!=1): ?>
                                        <a style="cursor: pointer" onclick="deleteContactNUmber(<?php echo e($value->id); ?>)"><span
                                                class="badge rounded-pill text-bg-danger"><?php echo e(__('Delete')); ?></span></a>
                                        <a style="cursor: pointer" onclick="setActiveNumber(<?php echo e($value->id); ?>)"><span
                                                class="badge rounded-pill text-bg-primary"><?php echo e(__('Active')); ?></span></a>
                                    <?php else: ?>
                                        <a style="cursor: pointer"><span
                                                class="badge rounded-pill text-bg-success"><?php echo e(__('Activated_number')); ?></span></a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </tbody>
                    </table>

                </div>
            </div>
        </div>


    </div>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="AddContactNumberModel" tabindex="-1" style="z-index: 9000000"
         aria-labelledby="AddContactNumberModel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><?php echo e(__('Add_User_Number')); ?></h5>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3" dir="ltr">
                        <label><?php echo e(__('Your new phone number')); ?></label>
                        <div id="inputNumberContact" data-turbolinks-permanent class="input-group signup mb-3"
                             style="justify-content:center;">

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal"><?php echo e(__('Close')); ?></button>
                    <button onclick="saveContactNumber()" type="button" id="saveAddContactNumber"
                            class="btn btn-primary"><?php echo e(__('Save_changes')); ?>

                    </button>
                </div>
            </div>
        </div>
    </div>
    <script data-tubolinks-eval=false>
        function saveContactNumber() {
            $('#AddContactNumberModel').modal('hide');
            window.livewire.emit('preSaveContact', $("#outputphoneContactNumber").val(), $("#isoContactNumber").val(), $("#phoneContactNumber").val());
            // window.livewire.emit('saveContactNumber',$("#phoneContactNumber").val(),$("#isoContactNumber").val()) ;
        }

        function setActiveNumber($id) {
            try {
                $('#modalCeckContactNumber').modal('show');
                $('#modalCeckContactNumber').modal('hide');
            } catch (e) {

            }

            Swal.fire({
                title: '<?php echo e(__('activate_number')); ?>',
                
                icon: "warning",
                // showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: '<?php echo e(trans('ok')); ?>',
                cancelButtonText: '<?php echo e(trans('canceled !')); ?>',
                denyButtonText: '<?php echo e(trans('No')); ?>',
                customClass: {
                    actions: 'my-actions',
                    cancelButton: 'order-1 right-gap',
                    confirmButton: 'order-2',
                    denyButton: 'order-3',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.livewire.emit('setActiveNumber', 1, $id);
                }
            });
            // alert($id);
            // alert($('#activeNumber').is(":checked"));
        }

        function deleteContactNUmber($id) {
            Swal.fire({
                title: '<?php echo e(__('delete_contact')); ?>',
                
                icon: "warning",
                // showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: '<?php echo e(trans('ok')); ?>',
                cancelButtonText: '<?php echo e(trans('canceled !')); ?>',
                denyButtonText: '<?php echo e(trans('No')); ?>',
                customClass: {
                    actions: 'my-actions',
                    cancelButton: 'order-1 right-gap',
                    confirmButton: 'order-2',
                    denyButton: 'order-3',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.livewire.emit('deleteContact', $id);
                }
            });
        }

        window.addEventListener('PreAddNumber', event => {
            Swal.fire({
                title: '<?php echo e(__('Your verification code')); ?>',
                html: event.detail.msgSend   + ' ' + '<br>' + event.detail.FullNumber + '<br>'  + event.detail.userMail + '<br>' + '<?php echo e(__('Your OTP Code')); ?>',
                allowOutsideClick: false,
                timer: '<?php echo e(env('timeOPT')); ?>',
                timerProgressBar: true,
                showCancelButton: true,
                cancelButtonText: '<?php echo e(trans('canceled !')); ?>',
                confirmButtonText: '<?php echo e(trans('ok')); ?>',
                footer: ' <i></i><div class="footerOpt"></div>',
                didOpen: () => {
                    // Swal.showLoading()
                    const b = Swal.getFooter().querySelector('i')
                    const p22 = Swal.getFooter().querySelector('div')
                    p22.innerHTML = '<?php echo e(trans('Dont get code?')); ?>' + ' <a>' + '<?php echo e(trans('Resend')); ?>' + '</a>';
                    timerInterval = setInterval(() => {
                        b.textContent = '<?php echo e(trans('It will close in')); ?>' + (Swal.getTimerLeft() / 1000).toFixed(0) + '<?php echo e(trans('secondes')); ?>'
                    }, 100)
                },
                willClose: () => {
                    clearInterval(timerInterval)
                },
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
            }).then((resultat) => {
                if (resultat.value) {
                    window.livewire.emit('saveContactNumber', resultat.value, event.detail.isoP, event.detail.mobile, event.detail.FullNumberNew);
                }
                if (resultat.isDismissed) {

                    window.location.reload();
                }
            })
        })
    </script>
    <script data-turbolinks-eval="false">
        var lan = "<?php echo e(config('app.available_locales')[app()->getLocale()]['tabLang']); ?>";
        var urlLang = "//cdn.datatables.net/plug-ins/1.12.1/i18n/" + lan + ".json";
        $('#example').DataTable(
            {
                retrieve: true,
                "colReorder": true,
                "orderCellsTop": true,
                "fixedHeader": true,
                "language": {
                    "url": urlLang
                }
            }
        );
    </script>
</div>
<?php /**PATH /var/www/vhosts/2earn.cash/demo.2earn.cash/resources/views/livewire/contact-number.blade.php ENDPATH**/ ?>