<?php $__env->startSection('content'); ?>
	<div class="aiz-titlebar text-left mt-2 mb-3">
	    <h5 class="mb-0 h5"><?php echo e(translate('Add New Digital Product')); ?></h5>
	</div>
	<div class="row">
		<div class="col-lg-10 mx-auto">
			<form class="form form-horizontal mar-top" action="<?php echo e(route('digitalproducts.store')); ?>" method="POST" enctype="multipart/form-data" id="choice_form">
				<?php echo csrf_field(); ?>
				<input type="hidden" name="added_by" value="admin">
				<div class="card">
					<div class="card-header">
						<h5 class="mb-0 h6"><?php echo e(translate('General')); ?></h5>
					</div>

					<div class="card-body">
						<div class="form-group row">
							<label class="col-lg-2 col-from-label"><?php echo e(translate('Product Name')); ?></label>
							<div class="col-lg-8">
								<input type="text" class="form-control" name="name" placeholder="<?php echo e(translate('Product Name')); ?>" required>
							</div>
						</div>
						<div class="form-group row" id="category">
							<label class="col-lg-2 col-from-label"><?php echo e(translate('Category')); ?></label>
							<div class="col-lg-8">
								<select class="form-control aiz-selectpicker" name="category_id" id="category_id" data-live-search="true" required>
		                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		                                <option value="<?php echo e($category->id); ?>"><?php echo e($category->getTranslation('name')); ?></option>
		                                <?php $__currentLoopData = $category->childrenCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $childCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		                                    <?php echo $__env->make('categories.child_category', ['child_category' => $childCategory], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
		                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								</select>
							</div>
						</div>
						<div class="form-group row">
		                    <label class="col-lg-2 col-from-label"><?php echo e(translate('Product File')); ?></label>
		                    <div class="col-lg-8">
				                <div class="input-group" data-toggle="aizuploader" data-multiple="false">
				                    <div class="input-group-prepend">
				                        <div class="input-group-text bg-soft-secondary font-weight-medium"><?php echo e(translate('Browse')); ?></div>
				                    </div>
				                    <div class="form-control file-amount"><?php echo e(translate('Choose File')); ?></div>
				                    <input type="hidden" name="file" class="selected-files">
				                </div>
				                <div class="file-preview box sm">
				                </div>
		                    </div>
		                </div>
						<div class="form-group row">
							<label class="col-lg-2 col-from-label"><?php echo e(translate('Tags')); ?></label>
							<div class="col-lg-8">
								<input type="text" class="form-control aiz-tag-input" name="tags[]" placeholder="<?php echo e(translate('Type to add a tag')); ?>">
							</div>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-header">
						<h5 class="mb-0 h6"><?php echo e(translate('Images')); ?></h5>
					</div>
					<div class="card-body">
						<div class="form-group row">
				            <label class="col-md-2 col-form-label" for="signinSrEmail"><?php echo e(translate('Main Images')); ?></label>
				            <div class="col-md-8">
				                <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
				                    <div class="input-group-prepend">
				                        <div class="input-group-text bg-soft-secondary font-weight-medium"><?php echo e(translate('Browse')); ?></div>
				                    </div>
				                    <div class="form-control file-amount"><?php echo e(translate('Choose File')); ?></div>
				                    <input type="hidden" name="photos" class="selected-files">
				                </div>
				                <div class="file-preview box sm">
				                </div>
				            </div>
				        </div>

						<div class="form-group row">
				            <label class="col-md-2 col-form-label" for="signinSrEmail"><?php echo e(translate('Thumbnail Image')); ?> <small>(290x300)</small></label>
				            <div class="col-md-8">
				                <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
				                    <div class="input-group-prepend">
				                        <div class="input-group-text bg-soft-secondary font-weight-medium"><?php echo e(translate('Browse')); ?></div>
				                    </div>
				                    <div class="form-control file-amount"><?php echo e(translate('Choose File')); ?></div>
				                    <input type="hidden" name="thumbnail_img" class="selected-files">
				                </div>
				                <div class="file-preview box sm">
				                </div>
				            </div>
				        </div>
					</div>
				</div>
				<div class="card">
					<div class="card-header">
						<h5 class="mb-0 h6"><?php echo e(translate('Meta Tags')); ?></h5>
					</div>
					<div class="card-body">
						<div class="form-group row">
							<label class="col-lg-2 col-from-label"><?php echo e(translate('Meta Title')); ?></label>
							<div class="col-lg-8">
								<input type="text" class="form-control" name="meta_title" placeholder="<?php echo e(translate('Meta Title')); ?>">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-2 col-from-label"><?php echo e(translate('Description')); ?></label>
							<div class="col-lg-8">
								<textarea name="meta_description" rows="5" class="form-control"></textarea>
							</div>
						</div>
						<div class="form-group row">
		                    <label class="col-md-2 col-form-label" for="signinSrEmail"><?php echo e(translate('Meta Image')); ?></label>
		                    <div class="col-md-8">
		                        <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
		                            <div class="input-group-prepend">
		                                <div class="input-group-text bg-soft-secondary font-weight-medium"><?php echo e(translate('Browse')); ?></div>
		                            </div>
		                            <div class="form-control file-amount"><?php echo e(translate('Choose File')); ?></div>
		                            <input type="hidden" name="meta_img" class="selected-files">
		                        </div>
		                        <div class="file-preview box sm">
		                        </div>
		                    </div>
		                </div>
					</div>
				</div>

				<div class="card">
					<div class="card-header">
						<h5 class="mb-0 h6"><?php echo e(translate('Price')); ?></h5>
					</div>
					<div class="card-body">
						<div class="form-group row">
							<label class="col-lg-2 col-from-label"><?php echo e(translate('Unit price')); ?></label>
							<div class="col-lg-8">
								<input type="number" lang="en" min="0" value="0" step="0.01" placeholder="<?php echo e(translate('Unit price')); ?>" name="unit_price" class="form-control" required>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-2 col-from-label"><?php echo e(translate('Purchase price')); ?></label>
							<div class="col-lg-8">
								<input type="number" lang="en" min="0" value="0" step="0.01" placeholder="<?php echo e(translate('Purchase price')); ?>" name="purchase_price" class="form-control" required>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-2 col-from-label"><?php echo e(translate('Tax')); ?></label>
							<div class="col-lg-6">
								<input type="number" lang="en" min="0" value="0" step="0.01" placeholder="<?php echo e(translate('Tax')); ?>" name="tax" class="form-control" required>
							</div>
							<div class="col-md-2">
								<select class="form-control aiz-selectpicker" name="tax_type">
									<option value="amount"><?php echo e(translate('Flat')); ?></option>
									<option value="percent"><?php echo e(translate('Percent')); ?></option>
								</select>
							</div>
						</div>
						<div class="form-group row">
	                        <label class="col-sm-3 control-label" for="start_date"><?php echo e(translate('Discount Date Range')); ?></label>
	                        <div class="col-sm-9">
	                          <input type="text" class="form-control aiz-date-range" name="date_range" placeholder="Select Date" data-time-picker="true" data-format="DD-MM-Y HH:mm:ss" data-separator=" to " autocomplete="off">
	                        </div>
	                    </div>
						<div class="form-group row">
							<label class="col-lg-2 col-from-label"><?php echo e(translate('Discount')); ?></label>
							<div class="col-lg-6">
								<input type="number" lang="en" min="0" value="0" step="0.01" placeholder="<?php echo e(translate('Discount')); ?>" name="discount" class="form-control" required>
							</div>
							<div class="col-md-2">
								<select class="form-control aiz-selectpicker" name="discount_type">
									<option value="amount"><?php echo e(translate('Flat')); ?></option>
									<option value="percent"><?php echo e(translate('Percent')); ?></option>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-header">
						<h5 class="mb-0 h6"><?php echo e(translate('Product Information')); ?></h5>
					</div>
					<div class="card-body">
						<div class="form-group row">
							<label class="col-lg-2 col-from-label"><?php echo e(translate('Description')); ?></label>
							<div class="col-lg-9">
								<textarea class="aiz-text-editor" name="description"></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="mb-3 text-right">
					<button type="submit" name="button" class="btn btn-primary"><?php echo e(translate('Save Product')); ?></button>
				</div>
			</form>
		</div>
	</div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/clients/client264/web300/web/resources/views/backend/product/digital_products/create.blade.php ENDPATH**/ ?>