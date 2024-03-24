<div>
    <div class="container">
        <div class="row">
            <div class="col-xl-12 col-md-8">
                <div class="row">
                    <div class="card" id="funding_form" style="margin-left:1rem;">
                        <div class="card-header">
                            <h4 class="card-title"></h4>
                        </div>
                        <div class="card-body">
                            <table class=" table table-responsive tableEditAdmin">
                                <div>
                                    <div style="color: black" class="col-sm"><label
                                            class="me-sm-2"><?php echo e(__('Demande_alimentation_BFS')); ?></label>
                                    </div>
                                    <thead>
                                    <tr>
                                        <th scope="Id"><?php echo e(__('idUser')); ?></th>
                                        <th scope="Name"><?php echo e(__('Name')); ?></th>
                                        <th scope="Francais"><?php echo e(__('Email')); ?></th>
                                        <th scope="Arabe"><?php echo e(__('Mobile Number')); ?></th>
                                        <th scope="Francais"><?php echo e(__('idCountry')); ?></th>
                                        <th scope=" "></th>
                                    </tr>

                                    </thead>
                                    <tbody>
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                    <?php $__currentLoopData = $pub_users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><span> <?php echo e($value->idUser); ?></span></td>
                                            <td><span><?php echo e($value->name); ?></span></td>
                                            <td><span><?php echo e($value->email); ?></span></td>
                                            <td><span> <?php echo e($value->mobile); ?></span></td>
                                            <td><span><?php echo e($value->idCountry); ?></span></td>
                                            <td>
                                                <input type="checkbox" wire:model="selectedUsers"
                                                       value="<?php echo e($value->idUser); ?>">

                                            </td>

                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </div>
                            </table>

                            <div class="text-center mb-4" style="margin-top: 20px;">

                                <button type="button" onclick="sendReq()" id="pay"
                                        class="btn btn-success pl-5 pr-5"><?php echo e(__('backand.Fund')); ?></button>
                            </div>
                            <div class="label">
                                <div style="color: red" class="col-sm"><label
                                        class="me-sm-2"><?php echo e(__('vous devez cocher ou mois un utilisateur')); ?></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function sendReq() {
                window.livewire.emit('sendReques');
            }
        </script>
    </div>
</div>
<?php /**PATH /var/www/vhosts/2earn.cash/demo.2earn.cash/resources/views/livewire/request-public-user.blade.php ENDPATH**/ ?>