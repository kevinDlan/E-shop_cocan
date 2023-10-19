<?php $__env->startSection('content'); ?>

<div class="aiz-titlebar text-left mt-2 mb-3">
		<div class="text-md-right">
			<a href="<?php echo e(route('digitalproducts.create')); ?>" class="btn btn-circle btn-info">
				<span><?php echo e(translate('Add New Digital Product')); ?></span>
			</a>
		</div>
	</div>
</div>

<div class="col-lg-12">
    <div class="card">
        <div class="card-header row gutters-5">
            <div class="col text-center text-md-left">
                <h5 class="mb-md-0 h6"><?php echo e(translate('Digital Products')); ?></h5>
            </div>
            <div class="col-md-4">
                <form class="" id="sort_digital_products" action="" method="GET">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" id="search" name="search"<?php if(isset($sort_search)): ?> value="<?php echo e($sort_search); ?>" <?php endif; ?> placeholder="<?php echo e(translate('Type name & Enter')); ?>">
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th data-breakpoints="lg">#</th>
                        <th width="30%"><?php echo e(translate('Name')); ?></th>
                        <th data-breakpoints="lg"><?php echo e(translate('Added By')); ?></th>
                        <th data-breakpoints="lg"><?php echo e(translate('Photo')); ?></th>
                        <th data-breakpoints="lg"><?php echo e(translate('Base Price')); ?></th>
                        <th data-breakpoints="lg"><?php echo e(translate('Todays Deal')); ?></th>
                        <th data-breakpoints="lg"><?php echo e(translate('Published')); ?></th>
                        <th data-breakpoints="lg"><?php echo e(translate('Featured')); ?></th>
                        <th data-breakpoints="lg"><?php echo e(translate('Options')); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e(($key+1) + ($products->currentPage() - 1)*$products->perPage()); ?></td>
                            <td><a href="<?php echo e(route('product', $product->slug)); ?>" class="text-muted" target="_blank"><b><?php echo e($product->getTranslation('name')); ?></b></a></td>
                            <td><?php echo e(ucfirst($product->added_by)); ?></td>
                            <td>
								<img src="<?php echo e(uploaded_asset($product->thumbnail_img)); ?>" alt="Image" class="w-50px">
							</td>
                            <td><?php echo e(number_format($product->unit_price,2)); ?></td>
                            <td>
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input onchange="update_todays_deal(this)" value="<?php echo e($product->id); ?>" type="checkbox" <?php if($product->todays_deal == 1) echo "checked";?> >
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td>
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input onchange="update_published(this)" value="<?php echo e($product->id); ?>" type="checkbox" <?php if($product->published == 1) echo "checked";?> >
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td>
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input onchange="update_featured(this)" value="<?php echo e($product->id); ?>" type="checkbox" <?php if($product->featured == 1) echo "checked";?> >
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td class="text-right">
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="<?php echo e(route('digitalproducts.edit', ['id'=>$product->id, 'lang'=>env('DEFAULT_LANGUAGE')] )); ?>" title="<?php echo e(translate('Edit')); ?>">
                                    <i class="las la-edit"></i>
                                </a>
                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="<?php echo e(route('digitalproducts.destroy', $product->id)); ?>" title="<?php echo e(translate('Delete')); ?>">
                                    <i class="las la-trash"></i>
                                </a>
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="<?php echo e(route('digitalproducts.download', encrypt($product->id))); ?>" title="<?php echo e(translate('Download')); ?>">
                                    <i class="las la-download"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            <div class="aiz-pagination">
                <?php echo e($products->appends(request()->input())->links()); ?>

            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('modal'); ?>
    <?php echo $__env->make('modals.delete_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script type="text/javascript">

        $(document).ready(function(){
            //$('#container').removeClass('mainnav-lg').addClass('mainnav-sm');
        });

        function update_todays_deal(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('<?php echo e(route('products.todays_deal')); ?>', {_token:'<?php echo e(csrf_token()); ?>', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '<?php echo e(translate('Todays Deal updated successfully')); ?>');
                }
                else{
                    AIZ.plugins.notify('danger', '<?php echo e(translate('Something went wrong')); ?>');
                }
            });
        }

        function update_published(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('<?php echo e(route('products.published')); ?>', {_token:'<?php echo e(csrf_token()); ?>', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '<?php echo e(translate('Published products updated successfully')); ?>');
                }
                else{
                    AIZ.plugins.notify('danger', '<?php echo e(translate('Something went wrong')); ?>');
                }
            });
        }

        function update_featured(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('<?php echo e(route('products.featured')); ?>', {_token:'<?php echo e(csrf_token()); ?>', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '<?php echo e(translate('Featured products updated successfully')); ?>');
                }
                else{
                    AIZ.plugins.notify('danger', '<?php echo e(translate('Something went wrong')); ?>');
                }
            });
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/clients/client264/web300/web/resources/views/backend/product/digital_products/index.blade.php ENDPATH**/ ?>