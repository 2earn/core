<div >
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>

    <div wire:loading>

    </div>
    <script data-turbolinks-eval="false">
        var exisUpdateRole = '<?php echo e(Session::has('SuccesUpdateRole')); ?>';
        if (exisUpdateRole) {
            toastr.success('<?php echo e(Session::get('SuccesUpdateRole')); ?>');
        }
    </script>

    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('li_1'); ?>  <?php $__env->endSlot(); ?>
        <?php $__env->slot('title'); ?>  <?php echo e(__('Gestion des Administrateurs')); ?><?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>
    <div   class="row">
        <div class="col-12">
            <div  class="card">
                <div class="card-header">
                    <div class="row">
                        <div>
                            <input type="text" class="form-control" placeholder="<?php echo e(__('PH_search')); ?>"
                                   wire:model="search"/>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table  class="table table-responsive tableEditAdmin">
                        <thead>
                        <tr>
                            <th scope="Id">Id</th>
                            <th scope="Name"><?php echo e(__('Name')); ?></th>
                            <th scope="Francais"><?php echo e(__('Mobile Number')); ?></th>
                            <th scope="Arabe">id Countrie</th>
                            <th scope="Francais">id Role</th>
                            <th scope="Francais"><?php echo e(__('Role')); ?></th>
                            <th scope="Francais"><?php echo e(__('Countrie')); ?></th>
                            <th scope=" "></th>
                        </tr>
                        </thead>
                        <tbody>
                        
                        
                        
                        
                        
                        
                        
                        
                        <?php $__currentLoopData = $translates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><span> <?php echo e($value->id); ?></span></td>
                                <td><span><?php echo e($value->name); ?></span></td>
                                <td><span><?php echo e($value->mobile); ?></span></td>
                                <td><span> <?php echo e($value->idCountry); ?></span></td>
                                <td><span><?php echo e($value->idrole); ?></span></td>
                                <td><span><?php echo e($value->role); ?></span></td>
                                <td><span><?php echo e($value->countrie); ?></span></td>
                                <td>
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModal"   wire:click="edit(<?php echo e($value->id); ?>)"  class="btn rounded-pill btn-secondary waves-effect"><?php echo e(__('Edit')); ?></button>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                    <?php echo e($translates->links()); ?>

                </div>
            </div>
        </div>
        <!-- Modal -->
        <div  wire:ignore.self class="modal fade" id="exampleModal"   tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel"  aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><?php echo e(__('User_managment')); ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php $role=\Illuminate\Support\Facades\Lang::get("userRole") ?>

                        <div> <label><span><?php echo e($role); ?>:</span> <span><?php echo e($name); ?></span> </label></div>
                        <p><?php echo e(__('Mobile_Number')); ?>: <?php echo e($mobile); ?></p>
                        <label><?php echo e(__('Role')); ?></label>
                        <select class="form-control" id="Country" name="country" wire:model.defer="userRole">
                            <option value=""><?php echo e(__('Choose')); ?></option>
                            <?php $__currentLoopData = $allRoles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                $cn = \Illuminate\Support\Facades\Lang::get($role->name) ;
                                ?>
                                <option value="<?php echo e($role->name); ?>"><?php echo e($cn); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <div style="margin: 10px"  class="scheduler-border">
                            <div  class="boxplatforms  d-flex">
                                <?php $__currentLoopData = $platformes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="">
                                        <label style="margin: 20px">
                                            <input  class="toggle-checkbox" type="checkbox" role="switch"
                                                    id="flexSwitchCheckDefault"
                                                    wire:model.defer="platformes.<?php echo e($key); ?>.selected">
                                            <div class="toggle-switch"></div>
                                            <span class="toggle-label"> <?php echo e(__( $setting->name )); ?>  </span>
                                        </label>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>



                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo e(__('Close')); ?></button>
                        <button wire:click = "changeRole(<?php echo e($currentId); ?>)" type="button" class="btn btn-primary"><?php echo e(__('Save_changes')); ?></button>
                    </div>
                </div>
            </div>
        </div>



    </div>
</div>
<?php /**PATH C:\wamp64\www\2earn\resources\views/livewire/edit-admin.blade.php ENDPATH**/ ?>