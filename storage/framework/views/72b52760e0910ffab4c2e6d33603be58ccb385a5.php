
<div>
     <?php $__env->startSection('title'); ?><?php echo e(__('history')); ?><?php $__env->stopSection(); ?>
     <?php $__env->startSection('content'); ?>

          <?php $__env->startComponent('components.breadcrumb'); ?>
               <?php $__env->slot('li_1'); ?><?php $__env->endSlot(); ?>
               <?php $__env->slot('title'); ?> <?php echo e(__('history')); ?> <?php $__env->endSlot(); ?>
          <?php echo $__env->renderComponent(); ?>






          <div class="row">
               <div class="col-lg-12">
                    <div class="card">
                         <div class="card-header ">
                              <!--    <h5 class="card-title mb-0">Alternative Pagination</h5>-->
                         </div>
                         <div class="card-body table-responsive">
                              <table id="HistoryNotificationTable" class="table nowrap dt-responsive align-middle table-hover table-bordered" style="width:100%">
                                   <thead  class="table-light">
                                   <tr class="head2earn  tabHeader2earn" >
                                        <th><?php echo e(__('reference')); ?></th>
                                        <th><?php echo e(__('send')); ?></th>
                                        <th><?php echo e(__('receiver')); ?></th>
                                        <th><?php echo e(__('Actions')); ?></th>
                                        <th><?php echo e(__('Date')); ?></th>
                                        <th><?php echo e(__('Type')); ?></th>
                                        <th><?php echo e(__('reponce')); ?></th>
                                   </tr>
                                   </thead>
                                   <tbody class="body2earn">
                                   </tbody>
                              </table>
                         </div>
                    </div>
               </div>
               <!--end col-->
          </div>
          <!--end row-->


</div>
<?php /**PATH /var/www/vhosts/2earn.cash/httpdocs/resources/views/livewire/notification-history.blade.php ENDPATH**/ ?>