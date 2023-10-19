<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-lg-7 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6"><?php echo e(translate('Install/Update Addon')); ?></h5>
            </div>
            <form class="form-horizontal" action="<?php echo e(route('addons.store')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="purchase_code"><?php echo e(translate('Purchase code')); ?></label>
                        <div class="col-sm-9">
                            <input type="text" id="purchase_code" name="purchase_code" value="8eee753c-8b70-e194-5771-577f3fdce34f" class="form-control" autocomplete="off" required readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="addon_zip"><?php echo e(translate('Zip File')); ?></label>
                        <div class="col-sm-9">
                            <div class="custom-file">
                                <label class="custom-file-label">
                                    <input type="file" id="addon_zip" name="addon_zip"  class="custom-file-input" required>
                                    <span class="custom-file-name"><?php echo e(translate('Choose file')); ?></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary"><?php echo e(translate('Install/Update')); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/clients/client264/web300/web/resources/views/backend/addons/create.blade.php ENDPATH**/ ?>