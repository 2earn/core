<div>
    <style>
        .btnTrans{
            background-color: #3595f6;
            padding: 5px;
            color: #FFFFFF;
            border-radius: 5px;
        }
        .loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-content: center;
            background-color: #0d6efd;
            transition: opacity 0.75s, visibility 0.75s;
        }
        .loader-hidden {
            opacity: 0;
            visibility: hidden;
        }

        .loader::after {
            content: "";
            width: 100px;
            height: 100px;
            border: 15px solid #DDDDDD;
            border-top-color: #7449f5;
            border-radius: 50%;
            animation: loading 0.75s ease infinite;
        }

        @keyframes loading {
            from {
                transform: rotate(0turn);
            }
            to {
                transform: rotate(1turn);
            }
        }

        /*.section {*/
        /*    background-color: #0000FF;*/
        /*    height: 300px;*/
        /*    line-height: 300px;*/
        /*}*/

        /*.section-2 { background-color: #00FF00;}*/
        /*.section-3 { background-color: #8D38C9;}*/
        /*.section-4 { background-color: #FF0000;}*/

        /*.loader {*/
        /*    position: relative;*/
        /*    display: inline-block;*/
        /*    width: 50px;*/
        /*    height: 50px;*/
        /*    vertical-align: middle;*/
        /*}*/

        /*!*	$Loader Quadrant*/
        /*    ========================================================================== *!*/

        /*.loader-quart {*/
        /*    border-radius: 50px;*/
        /*    border: 6px solid rgba(255,255,255,0.4);*/
        /*}*/

        /*.loader-quart:after {*/
        /*    content: '';*/
        /*    position: absolute;*/
        /*    top: -6px;*/
        /*    left: -6px;*/
        /*    bottom: -6px;*/
        /*    right: -6px;*/
        /*    border-radius: 50px;*/
        /*    border: 6px solid transparent;*/
        /*    border-top-color: #fff;*/
        /*    -webkit-animation: spin 1s linear infinite;*/
        /*    animation: spin 1s linear infinite;*/
        /*}*/

        /*!*	$Loader Double circle*/
        /*    ========================================================================== *!*/

        /*.loader-double {*/
        /*    border-radius: 50px;*/
        /*    border: 6px solid transparent;*/
        /*    border-top-color: #fff;*/
        /*    border-bottom-color: #fff;*/
        /*    -webkit-animation: spin 1.5s linear infinite;*/
        /*    animation: spin 1.5s linear infinite;*/
        /*}*/

        /*.loader-double:before,*/
        /*.loader-double:after {*/
        /*    content: '';*/
        /*    position: absolute;*/
        /*    top: 5px;*/
        /*    left: 5px;*/
        /*    bottom: 5px;*/
        /*    right: 5px;*/
        /*    border-radius: 50px;*/
        /*    border: 6px solid transparent;*/
        /*    border-top-color: #fff;*/
        /*    border-bottom-color: #fff;*/
        /*    filter: alpha(opacity=6);*/
        /*    -khtml-opacity: .6;*/
        /*    -moz-opacity: .6;*/
        /*    opacity: .6;*/
        /*    -webkit-animation: spinreverse 2s linear infinite;*/
        /*    animation: spinreverse 2s linear infinite;*/
        /*}*/

        /*.loader-double:before {*/
        /*    top: 15px;*/
        /*    left: 15px;*/
        /*    bottom: 15px;*/
        /*    right: 15px;*/
        /*    -webkit-animation: spinreverse 3s linear infinite;*/
        /*    animation: spinreverse 3s linear infinite;*/
        /*}*/

        /*!*	$Loader Multiple circle*/
        /*    ========================================================================== *!*/

        /*.loader-circles {*/
        /*    border-radius: 50px;*/
        /*    border: 3px solid transparent;*/
        /*    border-top-color: #fff;*/
        /*    -webkit-animation: spin 1s linear infinite;*/
        /*    animation: spin 1s linear infinite;*/
        /*}*/

        /*.loader-circles:before,*/
        /*.loader-circles:after {*/
        /*    content: '';*/
        /*    position: absolute;*/
        /*    top: 5px;*/
        /*    left: 5px;*/
        /*    bottom: 5px;*/
        /*    right: 5px;*/
        /*    border-radius: 50px;*/
        /*    border: 3px solid transparent;*/
        /*    border-top-color: #fff;*/
        /*    filter: alpha(opacity=8);*/
        /*    -khtml-opacity: .8;*/
        /*    -moz-opacity: .8;*/
        /*    opacity: .8;*/
        /*    -webkit-animation: spinreverse 5s linear infinite;*/
        /*    animation: spinreverse 5s linear infinite;*/
        /*}*/

        /*.loader-circles:before {*/
        /*    top: 12px;*/
        /*    left: 12px;*/
        /*    bottom: 12px;*/
        /*    right: 12px;*/
        /*    -webkit-animation: spinreverse 10s linear infinite;*/
        /*    animation: spinreverse 10s linear infinite;*/
        /*}*/

        /*!*	$Loader Bars*/
        /*    ========================================================================== *!*/

        /*.loader-bars:before,*/
        /*.loader-bars:after,*/
        /*.loader-bars span {*/
        /*    content: '';*/
        /*    display: block;*/
        /*    position: absolute;*/
        /*    left: 0;*/
        /*    top: 0;*/
        /*    width: 10px;*/
        /*    height: 30px;*/
        /*    background-color: #fff;*/
        /*    -webkit-animation: grow 1.5s linear infinite;*/
        /*    animation: grow 1.5s linear infinite;*/
        /*}*/

        /*.loader-bars:after {*/
        /*    left: 15px;*/
        /*    -webkit-animation: grow 1.5s linear -.5s infinite;*/
        /*    animation: grow 1.5s linear -.5s infinite;*/
        /*}*/

        /*.loader-bars span {*/
        /*    left: 30px;*/
        /*    -webkit-animation: grow 1.5s linear -1s infinite;*/
        /*    animation: grow 1.5s linear -1s infinite;*/
        /*}*/

        /*@-webkit-keyframes grow {*/
        /*    0% { -webkit-transform: scaleY(0); transform: scaleY(0); opacity: 0;}*/
        /*    50% { -webkit-transform: scaleY(1); transform: scaleY(1); opacity: 1;}*/
        /*    100% { -webkit-transform: scaleY(0); transform: scaleY(0); opacity: 0;}*/
        /*}*/

        /*@keyframes grow {*/
        /*    0% { -webkit-transform: scaleY(0); transform: scaleY(0); opacity: 0;}*/
        /*    50% { -webkit-transform: scaleY(1); transform: scaleY(1); opacity: 1;}*/
        /*    100% { -webkit-transform: scaleY(0); transform: scaleY(0); opacity: 0;}*/
        /*}*/

        /*@-webkit-keyframes spin {*/
        /*    0%{ -webkit-transform: rotate(0deg); tranform: rotate(0deg);}*/
        /*    100%{ -webkit-transform: rotate(360deg); tranform: rotate(360deg);}*/
        /*}*/

        /*@keyframes spin {*/
        /*    0%{ -webkit-transform: rotate(0deg); transform: rotate(0deg);}*/
        /*    100%{ -webkit-transform: rotate(360deg); transform: rotate(360deg);}*/
        /*}*/

        /*@-webkit-keyframes spinreverse {*/
        /*    0%{ -webkit-transform: rotate(0deg); tranform: rotate(0deg);}*/
        /*    100%{ -webkit-transform: rotate(-360deg); tranform: rotate(-360deg);}*/
        /*}*/

        /*@keyframes spinreverse {*/
        /*    0%{ -webkit-transform: rotate(0deg); transform: rotate(0deg);}*/
        /*    100%{ -webkit-transform: rotate(-360deg); transform: rotate(-360deg);}*/
        /*}*/
        nav svg {
            max-height: 20px;
        }
    </style>
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

    
    <div class="row">
        
        
        
        
        <div class="col">
            <div class="row">
                <div>
                    <a href="<?php echo e(route('home',app()->getLocale())); ?>" class=" btnTrans" type=" ">home</a>
                    
                    <a class="btnTrans " type="button" wire:click="PreImport('arToData')">Arabic field To
                        base
                    </a>
                    <a class=" btnTrans" type="button" wire:click="PreImport('enToData')">English field To
                        base
                    </a>
                    <a class=" btnTrans" type="button" wire:click="PreImport('mergeToData')">merge field To
                        base
                    </a>
                    <a class="btnTrans " type="button" wire:click="PreImport('databaseToFile')">Database To
                        file
                    </a>
                </div>
            </div>
            <div style="margin-top: 10px" class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header headerTranslate">
                            <div style="margin-bottom: 10px">
                                <label>Show </label>
                                <select  wire:model="nbrPagibation" id="cars">
                                    <?php for($i=5;$i<=50;$i+=5): ?>
                                        <option value="<?php echo e($i); ?>"><?php echo e($i); ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <a class="btnTrans" type="" wire:click="PreAjout">Ajouter</a>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" placeholder="Search..."
                                           wire:model="search"/>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class=" table table-responsive tableEditAdmin">
                                <thead>
                                <tr>
                                    <th scope="Id">Id</th>
                                    <th scope="Name">Name</th>
                                    <th scope="Francais">english</th>
                                    <th scope="Arabe">Arabe</th>
                                    <th scope="Francais">Francais</th>
                                    <th scope=" "></th>
                                </tr>
                                </thead>
                                <tbody>
                                
                                
                                
                                
                                
                                
                                
                                
                                <?php $__currentLoopData = $translates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><span> <?php echo e($value->id); ?></span></td>
                                        <td><span><?php echo e($value->name); ?></span></td>
                                        <td><span><?php echo e($value->valueEn); ?></span></td>
                                        <td><span> <?php echo e($value->value); ?></span></td>
                                        <td><span><?php echo e($value->valueFr); ?></span></td>
                                        <td>
                                            <a type=" " wire:click="initTranslate(<?php echo e($value->id); ?>)"
                                               data-bs-toggle="modal" data-bs-target="#exampleModal"
                                               class="">Edit
                                            </a>
                                        </td>
                                        
                                        
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            <?php echo e($translates->links()); ?>

                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div wire:ignore.self class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit field</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="mb-3">
                                <label for="recipient-name" class="col-form-label">Arabe:</label>
                                <input type="text" class="form-control" wire:model.defer="arabicValue">
                            </div>
                            <div class="mb-3">
                                <label for="message-text" class="col-form-label">Francais:</label>
                                <input type="text" class="form-control" wire:model.defer="frenchValue">
                                
                            </div>
                            <div class="mb-3">
                                <label for="message-text" class="col-form-label">English:</label>
                                <input type="text" class="form-control" wire:model.defer="englishValue">
                                
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" wire:click="saveTranslate" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>


        window.addEventListener('closeModal', event => {

            $("#exampleModal").modal('hide');
        })

        window.addEventListener('PassEnter', event => {
            Swal.fire({
                title: '<?php echo e(__('Pass')); ?>',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Confirm',
            }).then((resultat) => {
                if (resultat.value) {
                    if (event.detail.ev == 'arToData')
                        window.livewire.emit('addArabicField', resultat.value);
                    else if (event.detail.ev == 'enToData')
                        window.livewire.emit('addEnglishField', resultat.value);
                    else if (event.detail.ev == 'mergeToData')
                        window.livewire.emit('mergeTransaction', resultat.value);
                    // window.livewire.emit('sendSms',resultat.value,$("#outputforget").val());
                    else if (event.detail.ev == 'databaseToFile')
                        window.livewire.emit('databaseToFile', resultat.value);
                    //
                }
                if (resultat.isDismissed) {
                    location.reload();
                }
            })
        })
        window.addEventListener('PreAjoutTrans', event => {
            Swal.fire({
                title: 'Enter field name',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Confirm',
            }).then((resultat) => {
                if (resultat.value) {
                    window.livewire.emit('AddFieldTranslate', resultat.value);
                }
                if (resultat.isDismissed) {
                    location.reload();
                }
            })
        })
    </script>
</div>
<?php /**PATH /var/www/vhosts/2earn.cash/demo.2earn.cash/resources/views/livewire/translate-view.blade.php ENDPATH**/ ?>