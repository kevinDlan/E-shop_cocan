<?php $__env->startSection('content'); ?>

<section class="gry-bg py-4 profile">
    <div class="container-fluid">
        <form class="" action="" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="row gutters-10">
                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-header d-block">
                            <div class="form-group">
                                <input class="form-control form-control-sm" type="text" name="keyword" placeholder="<?php echo e(translate('Search by Product Name/Barcode')); ?>" onkeyup="filterProducts()">
                            </div>
                            <div class="row gutters-5">
                                <div class="col-md-6">
                                    <select name="poscategory" class="form-control form-control-sm aiz-selectpicker" data-live-search="true" onchange="filterProducts()">
                                        <option value=""><?php echo e(translate('All Categories')); ?></option>
                                        <?php $__currentLoopData = \App\Models\Category::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="category-<?php echo e($category->id); ?>"><?php echo e($category->getTranslation('name')); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <select name="brand"  class="form-control form-control-sm aiz-selectpicker" data-live-search="true" onchange="filterProducts()">
                                        <option value=""><?php echo e(translate('All Brands')); ?></option>
                                        <?php $__currentLoopData = \App\Models\Brand::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($brand->id); ?>"><?php echo e($brand->getTranslation('name')); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="aiz-pos-product-list c-scrollbar-light">
                                <div class="row gutters-5" id="product-list">

                                </div>
                                <div id="load-more">
                                    <p class="text-center fs-14 fw-600 p-2 bg-soft-primary c-pointer" onclick="loadMoreProduct()"><?php echo e(translate('Load More')); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <select name="user_id" class="form-control form-control-sm aiz-selectpicker pos-customer" data-live-search="true" onchange="getShippingAddress()">
                                        <option value=""><?php echo e(translate('Walk In Customer')); ?></option>
                                        <?php $__currentLoopData = \App\Models\Customer::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($customer->user): ?>
                                                <option value="<?php echo e($customer->user->id); ?>" data-contact="<?php echo e($customer->user->email); ?>"><?php echo e($customer->user->name); ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <button type="button" class="btn btn-icon btn-soft-dark ml-3" data-target="#new-customer" data-toggle="modal">
									<i class="las la-truck"></i>
								</button>
                            </div>
                        </div>
                    </div>
                    <div class="card mar-btm" id="cart-details">
                        <div class="card-body">
                            <div class="aiz-pos-cart-list c-scrollbar-light">
                                <table class="table aiz-table mb-0 mar-no" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="50%"><?php echo e(translate('Product')); ?></th>
                                            <th width="15%"><?php echo e(translate('QTY')); ?></th>
                                            <th><?php echo e(translate('Price')); ?></th>
                                            <th><?php echo e(translate('Subtotal')); ?></th>
                                            <th class="text-right"><?php echo e(translate('Remove')); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $subtotal = 0;
                                            $tax = 0;
                                            $shipping = 0;
                                        ?>
                                        <?php if(Session::has('posCart')): ?>
                                            <?php $__empty_1 = true; $__currentLoopData = Session::get('posCart'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $cartItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <?php
                                                    $subtotal += $cartItem['price']*$cartItem['quantity'];
                                                    $tax += $cartItem['tax']*$cartItem['quantity'];
                                                    $shipping += $cartItem['shipping']*$cartItem['quantity'];
                                                    if(Session::get('shipping', 0) == 0){
                                                        $shipping = 0;
                                                    }
                                                ?>
                                                <tr>
                                                    <td>
                                                        <span class="media">
                                                            <div class="media-left">
                                                                <img class="mr-3" height="60" src="<?php echo e(uploaded_asset(\App\Models\Product::find($cartItem['id'])->thumbnail_img)); ?>" >
                                                            </div>
                                                            <div class="media-body">
                                                                <?php echo e(\App\Models\Product::find($cartItem['id'])->name); ?> (<?php echo e($cartItem['variant']); ?>)
                                                            </div>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="">
                                                            <input type="number" class="form-control px-0 text-center" placeholder="1" id="qty-<?php echo e($key); ?>" value="<?php echo e($cartItem['quantity']); ?>" onchange="updateQuantity(<?php echo e($key); ?>)" min="1">
                                                        </div>
                                                    </td>
                                                    <td><?php echo e(single_price($cartItem['price'])); ?></td>
                                                    <td><?php echo e(single_price($cartItem['price']*$cartItem['quantity'])); ?></td>
                                                    <td class="text-right">
                                                        <button type="button" class="btn btn-circle btn-icon btn-sm btn-danger" onclick="removeFromCart(<?php echo e($key); ?>)">
                                                            <i class="las la-trash-alt"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <tr>
                                                    <td colspan="5" class="text-center">
                                                        <i class="las la-frown la-3x opacity-50"></i>
                                                        <p><?php echo e(translate('No Product Added')); ?></p>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bord-top">
                            <table class="table mb-0 mar-no" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center"><?php echo e(translate('Sub Total')); ?></th>
                                        <th class="text-center"><?php echo e(translate('Total Tax')); ?></th>
                                        <th class="text-center"><?php echo e(translate('Total Shipping')); ?></th>
                                        <th class="text-center"><?php echo e(translate('Discount')); ?></th>
                                        <th class="text-center"><?php echo e(translate('Total')); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center"><?php echo e(single_price($subtotal)); ?></td>
                                        <td class="text-center"><?php echo e(single_price($tax)); ?></td>
                                        <td class="text-center"><?php echo e(single_price($shipping)); ?></td>
                                        <td class="text-center"><?php echo e(single_price(Session::get('pos_discount', 0))); ?></td>
                                        <td class="text-center"><?php echo e(single_price($subtotal+$tax+$shipping - Session::get('pos_discount', 0))); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="pos-footer mar-btm">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex">
                                <div class="dropdown mr-3 dropup">
                                    <button class="btn btn-outline-dark btn-styled dropdown-toggle" type="button" data-toggle="dropdown">
                                        <?php echo e(translate('Shipping')); ?>

                                    </button>
                                    <div class="dropdown-menu p-3 dropdown-menu-lg">
                                        <div class="radio radio-inline">
                                            <input type="radio" name="shipping" id="radioExample_2a" value="0" checked onchange="setShipping()">
                                            <label for="radioExample_2a"><?php echo e(translate('Without Shipping Charge')); ?></label>
                                        </div>

                                        <div class="radio radio-inline">
                                            <input type="radio" name="shipping" id="radioExample_2b" value="1" onchange="setShipping()">
                                            <label for="radioExample_2b"><?php echo e(translate('With Shipping Charge')); ?></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdown dropup">
                                    <button class="btn btn-outline-dark btn-styled dropdown-toggle" type="button" data-toggle="dropdown">
                                        <?php echo e(translate('Discount')); ?>

                                    </button>
                                    <div class="dropdown-menu p-3 dropdown-menu-lg">
                                        <div class="input-group">
                                            <input type="number" min="0" placeholder="Amount" name="discount" class="form-control" value="<?php echo e(Session::get('pos_discount', 0)); ?>" required onchange="setDiscount()">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><?php echo e(translate('Flat')); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <button type="button" class="btn btn-primary" data-target="#order-confirm" data-toggle="modal"><?php echo e(translate('Pay With Cash')); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('modal'); ?>
    <!-- Address Modal -->
    <div id="new-customer" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom" role="document">
            <div class="modal-content">
                <div class="modal-header bord-btm">
                    <h4 class="modal-title h6"><?php echo e(translate('Shipping Address')); ?></h4>
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body" id="shipping_address">


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-styled btn-base-3" data-dismiss="modal" id="close-button"><?php echo e(translate('Close')); ?></button>
                    <button type="button" class="btn btn-primary btn-styled btn-base-1" data-dismiss="modal"><?php echo e(translate('Confirm')); ?></button>
                </div>
            </div>
        </div>
    </div>

    <!-- new address modal -->
    <div id="new-address-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom" role="document">
            <div class="modal-content">
                <div class="modal-header bord-btm">
                    <h4 class="modal-title h6"><?php echo e(translate('Shipping Address')); ?></h4>
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                </div>
                <form class="form-horizontal" action="<?php echo e(route('addresses.store')); ?>" method="POST" enctype="multipart/form-data">
                	<?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <input type="hidden" name="customer_id" id="set_customer_id" value="">
                        <div class="form-group">
                            <div class=" row">
                                <label class="col-sm-2 control-label" for="address"><?php echo e(translate('Address')); ?></label>
                                <div class="col-sm-10">
                                    <textarea placeholder="<?php echo e(translate('Address')); ?>" id="address" name="address" class="form-control" required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class=" row">
                                <label class="col-sm-2 control-label" for="email"><?php echo e(translate('Country')); ?></label>
                                <div class="col-sm-10">
                                    <select name="country" id="country" class="form-control aiz-selectpicker" required data-placeholder="<?php echo e(translate('Select country')); ?>">
                                        <?php $__currentLoopData = \App\Models\Country::where('status',1)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($country->name); ?>"><?php echo e($country->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class=" row">
                                <label class="col-sm-2 control-label" for="city"><?php echo e(translate('City')); ?></label>
                                <div class="col-sm-10">
                                    <input type="text" placeholder="<?php echo e(translate('City')); ?>" id="city" name="city" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class=" row">
                                <label class="col-sm-2 control-label" for="postal_code"><?php echo e(translate('Postal code')); ?></label>
                                <div class="col-sm-10">
                                    <input type="number" min="0" placeholder="<?php echo e(translate('Postal code')); ?>" id="postal_code" name="postal_code" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class=" row">
                                <label class="col-sm-2 control-label" for="phone"><?php echo e(translate('Phone')); ?></label>
                                <div class="col-sm-10">
                                    <input type="number" min="0" placeholder="<?php echo e(translate('Phone')); ?>" id="phone" name="phone" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-styled btn-base-3" data-dismiss="modal"><?php echo e(translate('Close')); ?></button>
                        <button type="submit" class="btn btn-primary btn-styled btn-base-1"><?php echo e(translate('Save')); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="product-variation" class="modal fade">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom modal-lg">
            <div class="modal-content" id="variants">

            </div>
        </div>
    </div>

    <div id="order-confirm" class="modal fade">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom">
            <div class="modal-content" id="variants">
                <div class="modal-header bord-btm">
                    <h4 class="modal-title h6"><?php echo e(translate('Order Confirmation')); ?></h4>
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <p><?php echo e(translate('Are you sure to confirm this order?')); ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-styled btn-base-3" data-dismiss="modal"><?php echo e(translate('Close')); ?></button>
                    <button type="button" onclick="submitOrder('cash')" class="btn btn-styled btn-base-1 btn-primary"><?php echo e(translate('Comfirm Order')); ?></button>
                </div>
            </div>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
<?php $__env->stopSection(); ?>


<?php $__env->startSection('script'); ?>
    <script type="text/javascript">

        var products = null;

        $(document).ready(function(){
            $('#container').removeClass('mainnav-lg').addClass('mainnav-sm');
            $('#product-list').on('click','.product-card',function(){
                var id = $(this).data('id');
                $.get('<?php echo e(route('variants')); ?>', {id:id}, function(data){
                    if (data == 0) {
                        addToCart(id, null, 1);
                    }
                    else {
                        $('#variants').html(data);
                        $('#product-variation').modal('show');
                    }
                });
            });
            filterProducts();
            getShippingAddress();
        });

        function filterProducts(){
            var keyword = $('input[name=keyword]').val();
            var category = $('select[name=poscategory]').val();
            var brand = $('select[name=brand]').val();
            $.get('<?php echo e(route('pos.search_product')); ?>',{keyword:keyword, category:category, brand:brand}, function(data){
                products = data;
                $('#product-list').html(null);
                setProductList(data);
            });
        }

        function loadMoreProduct(){
            if(products != null && products.links.next != null){
                $.get(products.links.next,{}, function(data){
                    products = data;
                    setProductList(data);
                });
            }
        }

        function setProductList(data){
            for (var i = 0; i < data.data.length; i++) {
                $('#product-list').append('<div class="col-3">' +
                    '<div class="card bg-light c-pointer mb-2 product-card" data-id="'+data.data[i].id+'" >'+
                        '<span class="absolute-top-left bg-dark text-white px-1">'+data.data[i].price +'</span>'+
                        '<img src="'+ data.data[i].thumbnail_image +'" class="card-img-top img-fit h-100px mw-100 mx-auto" >'+
                        '<div class="card-body p-2">'+
                            '<div class="text-truncate-2 small">'+ data.data[i].name +'</div>'+
                        '</div>'+
                    '</div>'+
                '</div>');
            }
            if (data.links.next != null) {
                $('#load-more').find('.text-center').html('Load More');
            }
            else {
                $('#load-more').find('.text-center').html('Nothing more found');
            }
            $('[data-toggle="tooltip"]').tooltip();
        }

        function removeFromCart(key){
            $.post('<?php echo e(route('pos.removeFromCart')); ?>', {_token:'<?php echo e(csrf_token()); ?>', key:key}, function(data){
                $('#cart-details').html(data);
                $('#product-variation').modal('hide');
            });
        }

        function addToCart(product_id, variant, quantity){
            $.post('<?php echo e(route('pos.addToCart')); ?>',{_token:'<?php echo e(csrf_token()); ?>', product_id:product_id, variant:variant, quantity, quantity}, function(data){
                $('#cart-details').html(data);
                $('#product-variation').modal('hide');
            });
        }

        function addVariantProductToCart(id){
            var variant = $('input[name=variant]:checked').val();
            addToCart(id, variant, 1);
        }

        function updateQuantity(key){
            $.post('<?php echo e(route('pos.updateQuantity')); ?>',{_token:'<?php echo e(csrf_token()); ?>', key:key, quantity: $('#qty-'+key).val()}, function(data){
                $('#cart-details').html(data);
                $('#product-variation').modal('hide');
            });
        }

        function setDiscount(){
            var discount = $('input[name=discount]').val();
            $.post('<?php echo e(route('pos.setDiscount')); ?>',{_token:'<?php echo e(csrf_token()); ?>', discount:discount}, function(data){
                $('#cart-details').html(data);
                $('#product-variation').modal('hide');
            });
        }

        function setShipping(){
            var shipping = $('input[name=shipping]:checked').val();
            $.post('<?php echo e(route('pos.setShipping')); ?>',{_token:'<?php echo e(csrf_token()); ?>', shipping:shipping}, function(data){
                $('#cart-details').html(data);
                $('#product-variation').modal('hide');
            });
        }

        function getShippingAddress(){

            $.post('<?php echo e(route('pos.getShippingAddress')); ?>',{_token:'<?php echo e(csrf_token()); ?>', id:$('select[name=user_id]').val()}, function(data){
                $('#shipping_address').html(data);
            });
        }

        function add_new_address(){
             var customer_id = $('#customer_id').val();
            $('#set_customer_id').val(customer_id);
            $('#new-address-modal').modal('show');
            $("#close-button").click();
        }

        function submitOrder(payment_type){
            var user_id = $('select[name=user_id]').val();
            var name = $('input[name=name]').val();
            var email = $('input[name=email]').val();
            var address = $('textarea[name=address]').val();
            var country = $('select[name=country]').val();
            var city = $('input[name=city]').val();
            var postal_code = $('input[name=postal_code]').val();
            var phone = $('input[name=phone]').val();
            var shipping = $('input[name=shipping]:checked').val();
            var discount = $('input[name=discount]').val();
            var address = $('input[name=address_id]:checked').val();

            $.post('<?php echo e(route('pos.order_place')); ?>',{_token:'<?php echo e(csrf_token()); ?>', user_id:user_id, name:name, email:email, address:address, country:country, city:city, postal_code:postal_code, phone:phone, shipping_address:address, payment_type:payment_type, shipping:shipping, discount:discount}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '<?php echo e(translate('Order Completed Successfully.')); ?>');
                    location.reload();
                }
                else{
                    AIZ.plugins.notify('danger', '<?php echo e(translate('Something went wrong')); ?>');
                }
            });
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/clients/client264/web300/web/resources/views/pos/index.blade.php ENDPATH**/ ?>