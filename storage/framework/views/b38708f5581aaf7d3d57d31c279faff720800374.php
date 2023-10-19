<?php $__env->startSection('content'); ?>

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="h3"><?php echo e(translate('All Collection List')); ?></h1>
        </div>
    </div>
</div>


<div class="card">
    <div class="card-header d-block d-lg-flex">
        <h5 class="mb-0 h6"><?php echo e(translate('Collection List')); ?></h5>
        <div class="">
<!--            <form class="" id="sort_delivery_boys" action="" method="GET">
                <div class="box-inline pad-rgt pull-left">
                    <div class="" style="min-width: 250px;">
                        <input type="text" class="form-control" id="search" name="search"<?php if(isset($sort_search)): ?> value="<?php echo e($sort_search); ?>" <?php endif; ?> placeholder="<?php echo e(translate('Type email or name & Enter')); ?>">
                    </div>
                </div>
            </form>-->
        </div>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th><?php echo e(translate('Delivery Boy')); ?></th>
                    <th class="text-center"><?php echo e(translate('Collected Amount')); ?></th>
                    <th class="text-right"><?php echo e(translate('Created At')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $delivery_boy_collections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $delivery_boy_collection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <tr>
                    <td><?php echo e(($key+1) + ($delivery_boy_collections->currentPage() - 1) * $delivery_boy_collections->perPage()); ?></td>
                    <td>
                        <?php echo e($delivery_boy_collection->user->name); ?>

                    </td>
                    <td class="text-center">
                        <?php echo e($delivery_boy_collection->collection_amount); ?>

                    </td>
                    <td class="text-right">
                        <?php echo e($delivery_boy_collection->created_at); ?>

                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <div class="aiz-pagination">
            <?php echo e($delivery_boy_collections->appends(request()->input())->links()); ?>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/clients/client264/web300/web/resources/views/delivery_boys/delivery_boys_collection_list.blade.php ENDPATH**/ ?>