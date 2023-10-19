<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-lg-6 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6"><?php echo e(translate('Paytm Credential')); ?></h5>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo e(route('paytm.update_credentials')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="PAYTM_ENVIRONMENT">
                        <div class="col-lg-4">
                            <label class="col-from-label"><?php echo e(translate('PAYTM ENVIRONMENT')); ?></label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="PAYTM_ENVIRONMENT" value="<?php echo e(env('PAYTM_ENVIRONMENT')); ?>" placeholder="local or production" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="PAYTM_MERCHANT_ID">
                        <div class="col-lg-4">
                            <label class="col-from-label"><?php echo e(translate('PAYTM MERCHANT ID')); ?></label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="PAYTM_MERCHANT_ID" value="<?php echo e(env('PAYTM_MERCHANT_ID')); ?>" placeholder="PAYTM MERCHANT ID" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="PAYTM_MERCHANT_KEY">
                        <div class="col-lg-4">
                            <label class="col-from-label"><?php echo e(translate('PAYTM MERCHANT KEY')); ?></label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="PAYTM_MERCHANT_KEY" value="<?php echo e(env('PAYTM_MERCHANT_KEY')); ?>" placeholder="PAYTM MERCHANT KEY" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="PAYTM_MERCHANT_WEBSITE">
                        <div class="col-lg-4">
                            <label class="col-from-label"><?php echo e(translate('PAYTM MERCHANT WEBSITE')); ?></label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="PAYTM_MERCHANT_WEBSITE" value="<?php echo e(env('PAYTM_MERCHANT_WEBSITE')); ?>" placeholder="PAYTM MERCHANT WEBSITE" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="PAYTM_CHANNEL">
                        <div class="col-lg-4">
                            <label class="col-from-label"><?php echo e(translate('PAYTM CHANNEL')); ?></label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="PAYTM_CHANNEL" value="<?php echo e(env('PAYTM_CHANNEL')); ?>" placeholder="PAYTM CHANNEL" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="PAYTM_INDUSTRY_TYPE">
                        <div class="col-lg-4">
                            <label class="col-from-label"><?php echo e(translate('PAYTM INDUSTRY TYPE')); ?></label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="PAYTM_INDUSTRY_TYPE" value="<?php echo e(env('PAYTM_INDUSTRY_TYPE')); ?>" placeholder="PAYTM INDUSTRY TYPE" >
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary"><?php echo e(translate('Save')); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/clients/client264/web300/web/resources/views/paytm/index.blade.php ENDPATH**/ ?>