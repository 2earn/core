<div data-turbolinks="false">
    <div class="row justify-content-center ">
        <div class="col-12 col-md-4">
            <div class="card cardPhone " id="phone-form">
                <div class="card-header">
                    <h4 class="card-title"><?php echo e(__('Update Phone Number')); ?></h4>
                </div>
                <div wire:ignore class="card-body">
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    <div class="text-center mb-3" dir="ltr">
                        <label><?php echo e(__('Your new phone number')); ?></label>
                        <div id="inputPhoneUpdate" data-turbolinks-permanent class="input-group signup mb-3"
                             style="justify-content:center;">

                            

                        </div>
                    </div>
                    <div class="text-center" style="margin-top: 20px;">
                        <button type="submit" id="submit_phone" class="btn btn-success ps-5 pe-5"
                                onclick="ConfirmChangePhone()"
                        ><?php echo e(__('send')); ?></button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function ConfirmChangePhone() {
            if ($('#phoneUpPhone').val() == "") {
                Swal.fire({
                    title: '<?php echo e(__('Required_phone_number')); ?>',

                    text: '',
                    icon: "warning",
                    // showDenyButton: true,
                    showCancelButton: false,
                    confirmButtonText: 'Ok',
                    denyButtonText: 'No',
                    customClass: {
                        actions: 'my-actions',
                        cancelButton: 'order-1 right-gap',
                        confirmButton: 'order-2',
                        denyButton: 'order-3',
                    }
                });
                return;
            }
            Swal.fire({
                title: '<?php echo e(__('Are you sure to change Phone')); ?>',
                text: '',
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
                    window.livewire.emit('PreChangePhone', $('#phoneUpPhone').val(), $('#outputUpPhone').val(), 'mail');
                } else if (result.isDenied) {
                    Swal.fire('Changes are not saved', '', 'info')
                }
            });
        }

        window.addEventListener('PreChPhone', event => {
            var textHtmlSend = '<?php echo e(__('We_will_send')); ?>';
            if (event.detail.methodeVerification === 'mail'){
                textHtmlSend = '<?php echo e(__('We_will_send_mail')); ?>'
            }
            Swal.fire({
                title: '<?php echo e(__('Your verification code')); ?>',
                html: textHtmlSend + '<br> ' + event.detail.FullNumber + '<br>' + '<?php echo e(__('Your OTP Code')); ?>',
                allowOutsideClick: false,
                timer: '<?php echo e(env('timeOPT')); ?>',
                timerProgressBar: true,
                showCancelButton: true,
                cancelButtonText: '<?php echo e(trans('canceled !')); ?>',
                confirmButtonText: '<?php echo e(trans('ok')); ?>',
                footer: '<div class="footerOpt"></div></br> <i></i>',
                didOpen: () => {
                    // Swal.showLoading()
                    const b = Swal.getFooter().querySelector('i')
                    const p22 = Swal.getFooter().querySelector('div')

                    timerInterval = setInterval(() => {
                        p22.innerHTML = '<?php echo e(trans('It will close in')); ?>' + ' ' + (Swal.getTimerLeft() / 1000).toFixed(0) + ' '  + '<?php echo e(trans('secondes')); ?>' + '</br>' +  '<?php echo e(trans('Dont get code?')); ?>' + ' <a>' + '<?php echo e(trans('Resend')); ?>' + '</a> '
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
                    if (event.detail.methodeVerification === 'mail')
                    {
                        window.livewire.emit('PreChangePhone', $('#phoneUpPhone').val(), $('#outputUpPhone').val(),'phone');
                    }
                    else
                    {
                        window.livewire.emit('UpdatePhoneNumber', resultat.value, $('#phoneUpPhone').val(), $('#outputUpPhone').val(), $('#ccodeUpPhone').val(),$('#isoUpPhone').val());
                    }
                }
                if (resultat.isDismissed) {
                }
            })
        })

    </script>
</div>
<?php /**PATH C:\Users\ghazi\Documents\GitHub\2earnprod\resources\views/livewire/edit-phone-number.blade.php ENDPATH**/ ?>