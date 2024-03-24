<div>
<div class="row">
        <div class="col-lg-12">
            <div class="card" id="leadsList">
                <div class="card-header border-0">

                    <div class="row g-4 align-items-center">
                        <div class="col-sm-3">
                            <div class="search-box">
                                <input type="text" class="form-control search" placeholder="Search for...">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>
                        <div class="col-sm-auto ms-auto">
                            <div class="hstack gap-2">
                                <button class="btn btn-danger" onClick="deleteMultiple()"><i class="ri-delete-bin-2-line"></i></button>
                                <button type="button" class="btn btn-secondary add-btn" data-bs-toggle="modal" id="create-btn" data-bs-target="#showModal"><i class="ri-add-line align-bottom me-1"></i> Add Leads</button>

                            </div>
                        </div>
                    </div>
                </div>
                <div   wire:ignore class="card-body ">
                    <div>
                        <div class="table-responsive table-card">
                            <table class="table align-middle" id="customerTable">
                                <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width:50px;">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="checkAll" value="option">
                                        </div>
                                    </th>

                                    <th class="sort" data-sort="name">Name</th>
                                    <th class="sort" data-sort="lastName">lastName</th>
                                    <th class="sort" data-sort="mobile">mobile</th>

                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody class="list form-check-all">

                                <tr>
                                    <th scope="row">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="chk_child" value="option1">
                                        </div>
                                    </th>
                                    <td style="display:none;" class="id" ><a href="javascript:void(0);" class="fw-medium link-primary">#VZ2101</a></td>
                                    <td class="name"></td>
                                    <td class="lastName"></td>
                                    <td class="mobile"></td>
                                    <td>
                                        <ul class="list-inline hstack gap-2 mb-0">
                                            <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                                <a class="edit-item-btn" href="#showModal" data-bs-toggle="modal"><i class="ri-pencil-fill align-bottom text-muted"></i></a>
                                            </li>
                                            <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Delete">
                                                <a class="remove-item-btn" data-bs-toggle="modal" href="#deleteRecordModal">
                                                    <i class="ri-delete-bin-fill align-bottom text-muted"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>


                                </tbody>
                            </table>
                            <div class="noresult" style="display: none">
                                <div class="text-center">
                                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px">
                                    </lord-icon>
                                    <h5 class="mt-2">Sorry! No Result Found</h5>
                                    <p class="text-muted mb-0">We've searched more than 150+ leads We
                                        did not find any
                                        leads for you search.</p>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <div class="pagination-wrap hstack gap-2">
                                <a class="page-item pagination-prev disabled" href="#">
                                    Previous
                                </a>
                                <ul class="pagination listjs-pagination mb-0"></ul>
                                <a class="page-item pagination-next" href="#">
                                    Next
                                </a>
                            </div>
                        </div>
                    </div>





                </div>
            </div>

        </div>
        <!--end col-->
    </div>


    <!-- Modal -->
    <div   wire:ignore.self  class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1" aria-labelledby="deleteRecordLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="btn-close"></button>
                </div>
                <div class="modal-body p-5 text-center">
                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#405189,secondary:#f06548" style="width:90px;height:90px"></lord-icon>
                    <div class="mt-4 text-center">
                        <h4 class="fs-semibold"><?php echo e(__('Delete_Confirm')); ?> </h4>
                        <p class="text-muted fs-14 mb-4 pt-1"><?php echo e(__('Are_you_sure_want_to_delete?')); ?></p>
                        <div class="hstack gap-2 justify-content-center remove">
                            <button class="btn btn-link link-success fw-medium text-decoration-none" id="deleteRecord-close" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i>
                                <?php echo e(__('Close')); ?></button>
                            <button class="btn btn-danger" onclick="deleteId()" id="delete-record"><?php echo e(__('Yes, Delete')); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end modal -->
   <div  wire:ignore.self class="modal fade" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div    class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
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
                <form action="">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <input id="id-field" style="display: none"
                               type="text"
                               class="form-control" name="name"
                               placeholder="name ">
                        <div class="row g-3">
                            <div class="col-lg-12">

                                <div>
                                    <label for="nameField" class="form-label"><?php echo e(__('Name')); ?></label>
                                    <input id="nameField"
                                           type="text"
                                           class="form-control" wire:model.defer="name" name="nameField"
                                           required>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-12">
                                <div>
                                    <label for="lastNameField" class="form-label"><?php echo e(__('Last Name')); ?></label>
                                    <input id="lastNameField"
                                           type="text"
                                           class="form-control"   wire:model.defer="lastName" name="lastNameField"
                                           required>
                                </div>
                            </div>
                            <div class=" col-lg-12">
                                <div class="mb-3">
                                    <label for="username"
                                           class="form-label"><?php echo e(__('Mobile Number')); ?></label><br>
                                    <input type="tel" name="mobile" id="ipAdd2Contact"
                                           class="form-control"
                                           value=""
                                           placeholder="<?php echo e(__('PH_MobileNumber')); ?>">

                                    <input type=' ' name='fullnumber' id='outputAdd2Contact' class='form-control'>
                                    <input type=' ' name='ccodeAdd2Contact' id='ccodeAdd2Contact'>
                                </div>
                            </div>
                        </div>
                        <!--end row-->
                    </div>
                    <div class="modal-footer">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="button"   onclick="saveContactEvent()" class="btn btn-success" id="add-btn"><?php echo e(__('Add')); ?></button>
                            <button type="button"    onclick="editContactFunction()" class="btn btn-success" id="edit-btn"><?php echo e(__('Save')); ?></button>
                        </div>
                    </div>

            </form>
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

    function initNewUserContact() {
        window.livewire.emit('initNewUserContact');
    }

    function saveContactEvent() {

        inputphone = document.getElementById("ipAdd2Contact");
        inputname = document.getElementById("ccodeAdd2Contact");
        inputlast = document.getElementById("outputAdd2Contact");

        if (inputphone.value.trim()  && inputname.value.trim() && inputlast.value.trim())
            window.livewire.emit('save', $('#ipAdd2Contact').val(), $('#ccodeAdd2Contact').val(), $('#outputAdd2Contact').val());
        else
            alert("erreur number");
    }


    function editContactFunction() {

       // window.livewire.emit('inituserContact', dd);
        inputphone = document.getElementById("mobileField").value;
        inputid=document.getElementById("id-field").value ;
        inputname = document.getElementById("nameField").value;
        inputlast = document.getElementById("lastNameField").value;
        window.livewire.emit('edit',inputid,inputname,inputlast,inputphone);

    }

    function deleteId() {

        console.log(itemId) ;
        window.livewire.emit('deleteId',itemId);


    }

    function deleteContact(dd) {
        window.livewire.emit('deleteContact', dd);
        // $('#contacts_table').DataTable().ajax.reload( );
    }



</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/list.js/1.0.2/list.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/list.pagination.js/0.1.1/list.pagination.min.js"></script>

<!-- Sweet Alerts js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<script data-turbolinks-eval="false"  >

</script>
</div>
<?php /**PATH C:\xampp\htdocs\modern\resources\views/livewire/contacts.blade.php ENDPATH**/ ?>