<div>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
    <script data-turbolinks-eval="false">
        // $(document).on('ready turbolinks:load', function () {
        var existeUserContact = '<?php echo e(Session::has('existeUserContact')); ?>';

        if (existeUserContact) {
            Swal.fire({
                title: '<?php echo e(trans('user_existe_déja')); ?>',
                text: '<?php echo e(trans('changer_contact')); ?>',
                icon: "warning",
                showCancelButton: true,
                cancelButtonText: '<?php echo e(trans('canceled !')); ?>',
                confirmButtonText: '<?php echo e(trans('Yes')); ?>',
            }).then((result) => {
                if (result.isConfirmed) {
                    const iddd = '<?php echo e(Session::get('sessionIdUserExiste')); ?>';
                    var url = "<?php echo e(route('editContact2', ['locale' =>  app()->getLocale(), 'UserContact'=> Session::get('sessionIdUserExiste')])); ?>";
                    document.location.href = url;
                }
            });
            // window.location.reload();
        }

        var toEditForm = '<?php echo e(Session::has('toEditForm')); ?>';
        if (toEditForm) {

            var someTabTriggerEl = document.querySelector('#pills-AddContact-tab');
            var tab = new bootstrap.Tab(someTabTriggerEl);
            tab.show();
        }

        var existUpdate = '<?php echo e(Session::has('SessionUserUpdated')); ?>';
        if (existUpdate) {

            toastr.success('Succées');
        }

    </script>
    


        <div class="row">
            <div class="col-lg-12">
                <div class="card">

                    <div class="card-body">
                        <div id="customerList">
                            <div class="row g-4 mb-3">
                                <div class="col-sm-auto">
                                    <div>
                                        <button type="" class="btn  btn-secondary " data-bs-toggle="modal"
                                                data-bs-target="#modalcontact" onclick="initNewUserContact()"
                                                id="addcategorie"><i
                                                class="ri-upload-cloud-line label-icon align-middle fs-16 me-2"></i> <?php echo e(__('Add a contact')); ?>

                                        </button>
                                    </div>
                                </div>
                                <div class="col-sm-auto">
                                    <div class="d-flex justify-content-sm-end">
                                        <div class="search-box ms-2">
                                            <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                                                    id="create-btn" data-bs-target="#modalimport"><i
                                                    class="ri-upload-cloud-line label-icon align-middle fs-16 me-2"></i><?php echo e(__('Import Your Contact')); ?>

                                            </button>

                                        </div>
                                    </div>
                                </div>
                            </div>


                            <table class="table table-nowrap dt-responsive table-bordered display" style="width:100%"
                                   id="contacts_table">
                                <thead class="table-light">
                                <tr class="head2earn">
                                    <th><?php echo e(__('Name')); ?></th>
                                    <th><?php echo e(__('Last Name')); ?></th>
                                    <th><?php echo e(__('Phone')); ?></th>
                                    <th><?php echo e(__('Actions')); ?></th>
                                </tr>
                                </thead>
                                <tbody class="list form-check-all">

                                </tbody>
                            </table>


                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div wire:ignore.self class="modal fade" id="modalcontact" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalLabel" data-bs-backdrop="static"
             data-bs-keyboard="false">
            <div wire:ignore class=" modal-dialog modal-dialog-centered " role="document">
                
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"
                            id="ContactsModalLabel"><?php echo e(__('Add a contact')); ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                    </div>
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span
                        class="error alert-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <?php $__errorArgs = ['lastName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span
                        class="error alert-danger  "><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <?php if(Session::has('message')): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo e(Session::get('message')); ?>

                        </div>
                    <?php endif; ?>
                    <div class="modal-body">
                        <form id="basic-form" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label"><?php echo e(__('Name')); ?></label>
                                        <input id="inputNameContact2" wire:model.defer="name"
                                               type="text"
                                               class="form-control" name="name" value=""
                                               placeholder="name ">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label"><?php echo e(__('Last Name')); ?></label>
                                        <input id="inputLastNameContact2"
                                               wire:model.defer="lastName" type="text"
                                               class="form-control" name="lastName"
                                               value="" placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="label_phone">
                                        <label><?php echo e(__('Mobile Number')); ?></label></div>
                                    <div id="ipAdd2Contact" data-turbolinks-permanent
                                         class="input-group signup mb-3">
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="modal-footer">
                                        <button type=" " class="btn btn-secondary"
                                                data-bs-dismiss="modal"><?php echo e(__('Close')); ?></button>
                                        
                                        <button type="button" id="SubmitAdd2Contact"
                                                onclick="saveContactEvent()"

                                                class="btn btn-primary"><?php echo e(__('Save')); ?>

                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div wire:ignore.self class="modal fade" id="deleteModal" tabindex="-1" role="dialog"
             aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"
                            id="deleteModalLabel"><?php echo e(__('Delete_Confirm')); ?> </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true close-btn">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p><?php echo e(__('Are_you_sure_want_to_delete?')); ?></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary close-btn"
                                data-bs-dismiss="modal"><?php echo e(__('Close')); ?></button>
                        <button type="button" wire:click.prevent="delete()"
                                class="btn btn-danger close-modal"
                                data-bs-dismiss="modal"><?php echo e(__('Yes, Delete')); ?>

                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div wire:ignore.self class="modal fade" id="modalimport" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalLabel" data-bs-backdrop="static"
             data-bs-keyboard="false">
            <div wire:ignore class=" modal-dialog modal-dialog-centered " role="document">
                
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"
                            id="ImportModalLabel"><?php echo e(__('Import Your Contact')); ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form id="basic-form" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="row">
                                <div class="alert alert-info" role="alert">
                                    <?php echo e(__('Only Csv files')); ?>

                                </div>
                                <form style="display: flex" action=""
                                      enctype="multipart/form-data">
                                    <?php echo csrf_field(); ?>
                                    <div class="input-group">
                                        <input type="text" name="idUser" value="197604161"
                                               hidden>
                                        <input type="file" class="form-control"
                                               id="inputGroupFile03"
                                               aria-describedby="inputGroupFileAddon03"
                                               aria-label="Upload" accept=".csv"
                                               onchange="uploadFile()">
                                        <button class="btn btn-secondary" type="button"
                                                id="inputGroupFileAddon03"><?php echo e(__('Import')); ?></button>
                                    </div>
                                </form>


                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script>
            // $(document).on('ready turbolinks:load', function () {
            //     //     alert("dddd");
            // });
            function initNewUserContact() {
                window.livewire.emit('initNewUserContact');
            }

            function saveContactEvent() {

                inputphone = document.getElementById("phoneAdd2Contact");
                inputname = document.getElementById("inputLastNameContact2");
                inputlast = document.getElementById("inputNameContact2");
                if (inputphone.value.trim() && inputname.value.trim() && inputlast.value.trim())
                    window.livewire.emit('save', $('#phoneAdd2Contact').val(), $('#ccodeAdd2Contact').val(), $('#outputAdd2Contact').val());
                else
                    alert("erreur number");
            }


            function editContactFunction(dd) {

                window.livewire.emit('inituserContact', dd);
                // var tabliset = document.querySelector('#pills-listeContact-tab');
                // var someTabTriggerEl = document.querySelector('#pills-AddContact-tab');
                // tabliset.classList.remove('active');
                // someTabTriggerEl.classList.add('active');
                // var tab = new bootstrap.Tab(someTabTriggerEl);
                // tab.show() ;
                // alert("dsqd");
                // $('#contacts_table').DataTable().ajax.reload();
            }

            function deleteId(dd) {
                window.livewire.emit('deleteId', dd);
                // $('#contacts_table').DataTable().ajax.reload( );
            }

            function deleteContact(dd) {
                window.livewire.emit('deleteContact', dd);
                // $('#contacts_table').DataTable().ajax.reload( );
            }


        </script>
</div>
<?php /**PATH /var/www/vhosts/2earn.cash/dev.2earn.cash/resources/views/livewire/contacts.blade.php ENDPATH**/ ?>