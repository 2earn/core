<div>

    <script>
        var ErrorSecurityCodeRequest= '<?php echo e(Session::has('ErrorSecurityCodeRequest')); ?>';
        if (ErrorSecurityCodeRequest) {
            Swal.fire({
                icon: 'error',

                title:'<?php echo e(trans('désolé')); ?>',
                text: '<?php echo e(Session::get('ErrorSecurityCodeRequest')); ?>',
                confirmButtonText: '<?php echo e(trans('Yes')); ?>',
                // footer: '<a href="">Why do I have this issue?</a>'
            })
        }

    </script>
    <div class="col-3 card">
        <div class="card-header">
            <?php echo e(__('fiche_crédit')); ?>

        </div>
        <div class="card-body">
            <h5 class="card-title"><?php echo e(__('Transfert_crédit')); ?></h5>
            <p><strong><?php echo e(__('Solde')); ?>:</strong> <?php echo e($soldeUser->soldeBFS); ?> $</p>
            <div class="col-12">
                <div><strong><span><?php echo e(__('Opération')); ?>:</span> </strong><span><?php echo e(__('BFS to BFS')); ?></span></div>
                <div><strong><span><?php echo e(__('vers')); ?>:</span> </strong><span>  <?php echo e($financialRequest->name); ?>  </span></div>
                <div><strong><span><?php echo e(__('Mobile Number')); ?>:</span></strong> <span>  <?php echo e($financialRequest->mobile); ?>  </span></div>
                
                
                
                
                
                
            </div>
            <p class="card-text"><strong><?php echo e(__('Montant_envoyer')); ?></strong> <?php echo e($financialRequest->amount); ?> $</p>
            <button  onclick="ConfirmTransacction()" class=" btn btn-primary btn2earn  "><?php echo e(__('Confirmer')); ?></button>
            <a style="padding: 5px;border-radius: 5px;text-decoration: none!important;color: #f02602!important;"
               href="<?php echo e(route('financial_transaction',app()->getLocale())); ?>" class="btn-danger"><?php echo e(__('Cancel')); ?></a>
        </div>
        <div class="card-footer text-muted">

        </div>
    </div>
    <script>
        function ConfirmTransacction() {
            if ($('#RequstCompte').val() != 0) {
                Swal.fire({
                    title: '<?php echo e(__('Submit your security code')); ?>',
                    input: 'text',
                    inputAttributes: {
                        autocapitalize: 'off'
                    },
                    showCancelButton: true,
                    cancelButtonText: '<?php echo e(trans('canceled !')); ?>',
                    confirmButtonText: '<?php echo e(trans('ok')); ?>',
                    denyButtonText: 'No',
                    // preConfirm: (login) => {
                    //     return fetch(`//api.github.com/users/${login}`)
                    //         .then(response => {
                    //             if (!response.ok) {
                    //                 throw new Error(response.statusText)
                    //             }
                    //             return response.json()
                    //         })
                    //         .catch(error => {
                    //             Swal.showValidationMessage(
                    //                 `Request failed: ${error}`
                    //             )
                    //         })
                    // },
                    // allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.livewire.emit('Confirmrequest', 2, <?php echo e($financialRequest->numeroReq); ?>, result.value);
                    }
                })
                
            }
        }
    </script>
</div>
<?php /**PATH /var/www/vhosts/2earn.cash/demo.2earn.cash/resources/views/livewire/accept-financial-request.blade.php ENDPATH**/ ?>