<div>
    <div class="row">
            <div class="card">

                <div class="card-body pt-0">
                    <div class="transaction-table">
                        
                        <div class="table-responsive ">
                            <table class=" mb-0 table-responsive-sm stripe table2earn flex-table" id="countries_table" style="width: 100%">
                                <thead>
                                <tr class="head2earn">
                                    <th style="display: none ; border: none "><?php echo e(__('idCountry')); ?></th>
                                    <th style=" border: none "><?php echo e(__('CountryName')); ?></th>
                                    <th style=" border: none "><?php echo e(__('PhoneCode')); ?></th>
                                    <th style=" border: none "><?php echo e(__('Language')); ?></th>
                                    <th style=" border: none "><?php echo e(__('Actions')); ?></th>
                                </tr>

                                </thead>
                                <tbody class="body2earn">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        <div wire:ignore.self class="modal fade" id="editCountriesModal" tabindex="" role="dialog"
             aria-labelledby="editCountriesModal">
            <div class=" modal-dialog modal-dialog-centered " role="document">
                
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id=" "><?php echo e(__('Edit country')); ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>







                    <div class="modal-body">
                        <form wire:submit.prevent="save" id="basic-formdd" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="row">
                                <div class="mb-3 col-xl-6">
                                    <label class="me-sm-2"><?php echo e(__('CountryName')); ?></label>
                                    <input type="text" wire:model.defer="name" class="form-control" name="name"   disabled>
                                </div>
                                <div class="mb-3 col-xl-6">
                                    <label class="me-sm-2"><?php echo e(__('Phone Code')); ?></label>
                                    <input type="text" class="form-control"  wire:model.defer="phonecode" name="phonecode"   disabled>
                                </div>
                                <div class="mb-3 col-xl-6">
                                    <label class="me-sm-2"><?php echo e(__('ISO')); ?></label>
                                    <input type="text"  wire:model.defer="ISO" class="form-control" name="iso"  disabled>
                                </div>
                                <div class="mb-3 col-xl-6">
                                    <label class="me-sm-2"><?php echo e(__('Language')); ?></label>
                                    <select class="form-control" id="langueCountrie" name=" "
                                            wire:model.defer="langue">

                                        <?php $__currentLoopData = $allLanguage; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($language->name); ?>"><?php echo e($language->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="text-center" style="margin-top: 20px;">
                                    <button type="submit" class="btn btn-success ps-5 pe-5"><?php echo e(__('Save')); ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <style>
        .card-header:first-child {
            border-radius: calc(0.25rem - 1px) calc(0.25rem - 1px) 0 0;
        }
        ::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
            color: #cbd5e0;
            opacity: 1; /* Firefox */
            font-size: 12px;
        }
        input{
            border: 1px solid #cbd5e0 ;
        }
    </style>

    <script>
        function getEditCountrie(id) {
            window.livewire.emit('initCountrie', id);
            // $('#countries_table').DataTable().ajax.reload( );
        }
        $("#editCountriesModal").on('hidden.bs.modal', function () {
            window.location.href = "<?php echo e(route('countries_management', app()->getLocale())); ?>";
            // location.reload();
        });
    </script>

</div>


<?php /**PATH C:\wamp64\www\2earn\resources\views/livewire/countries-management.blade.php ENDPATH**/ ?>