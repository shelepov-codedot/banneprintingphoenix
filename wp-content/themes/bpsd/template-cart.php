<?php
/* Template Name: Cart */
get_header();
/*echo '<pre>';
print_r(WC()->cart->get_cart());
echo '</pre>';*/
?>

<div class="container cart">
    <div class="cart__link">
        <a href="#">
            Back </a>
    </div>
    <div class="cart__header">
        <h2 class="cart__title">SHOPPING CART</h2>
        <p class="cart__quantity"><?= WC()->cart->get_cart_contents_count() ?> items</p>
        <div class="user-nav__icon">
            <svg class="icon ">
                <use xlink:href="<?php echo get_template_directory_uri() ?>/assets/img/stack/sprite.svg#shopping-cart">
                </use>
            </svg>
        </div>
    </div>
    <div class="cart__box">
        <div class="cart__box-label">
            <div class="cart__label-wrap">
                <p class="cart__label">Order</p>
                <p class="cart__label">Qty</p>
                <p class="cart__label">Price</p>
            </div>
            <?php
            $products = WC()->cart->get_cart();

//            echo '<pre>';
//            print_r($products);
//            echo '</pre>';
            ?>

            <?php
                $customer_id = WC()->session->get_customer_id();
                $total = 0;
                foreach ($products as $product_key => $product):

                    $_product = apply_filters( 'woocommerce_cart_item_product', $product['data'], $product, $product_key );

                    $get_product = wc_get_product($product['product_id']);
//            echo '<pre>';
//            print_r($get_product);
//            echo '</pre>';
//            echo '<br>';
//            echo '<pre>';
//            print_r($product);
//            echo '</pre>';
            ?>
            <div class="cart__wrap">
                <ul class="cart__list">
                    <li>
                        <div class="cart__item">
                            <img class="cart__item-img"
                                src="<?= wp_get_attachment_image_url( $_product->get_image_id(), 'full', '$icon' ) ?>">
                            <?php
//                            echo kama_thumb_img([
//                                'width' => 140,
//                                'height'=> 100,
//                                'class' => 'cart__item-img',
//                                'src'   => wp_get_attachment_image_url( $_product->get_image_id(), 'full', '$icon' ),
//                            ]);
                            ?>
                            <div class="cart__item-wrap">
                                <div class="cart__item-box">
                                    <div class="cart__item-text cart__item-title"><?= (!empty($product['custom_name'])) ? $product['custom_name'] : $_product->get_name() ?></div>
                                    <div class="cart__item-text cart__item-count"><?= $product['quantity'] ?></div>
                                    <div class="cart__item-text cart__item-price">
                                    <?php
                                        if (!empty($product['custom_price'])) {
                                            $price = $product['custom_price'] * $product['quantity'];
                                            $total += $price;
                                            echo wc_price($price);
                                        } else {
                                            $price = $product['line_subtotal'];
                                            $total += $price;
                                            echo wc_price($price);
                                        }
                                    ?>
                                    </div>
                                </div>
                                <?php
                                    $attributes = $_product->get_attributes();
                                    foreach ($attributes as $attribute=>$value) {
                                        if (!empty($_product->get_default_attributes()[$attribute]) || !is_object($value)) {
                                ?>
                                        <div class="cart__item-box">
                                            <div class="cart__item-parameters"><?= implode(' ', array_map('ucfirst', explode('-', substr($attribute, 3)))) ?>:
                                                <?php if (isset($product['custom_size']) && $attribute == 'pa_set-size'): ?>
                                                    <?= $product['custom_size'] ?>
                                                <?php else: ?>
                                                    <?= !is_object($value) ? $value : $_product->get_default_attributes()[$attribute]; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                <?php
                                        } elseif (is_object($value)) {
                                            $attribute_name = $value->get_taxonomy(); // The taxonomy slug name
                                            $attribute_terms = $value->get_terms(); // The terms
                                            $attribute_slugs = $value->get_slugs(); // The term slugs
                                            //
                                            echo '<div class="cart__item-box">';
                                            echo '<div class="cart__item-parameters">'.implode(' ', array_map('ucfirst', explode('-', substr($value->get_name(), 3)))).': '.$attribute_slugs[0].'</div>';
                                            echo '</div>';
                                        }
                                       }
                                ?>
                                <div class="cart__file-list">
                                <?php
                                $customer_folder = WC()->session->get( 'customer_folder' );
                                $dir = $_SERVER['DOCUMENT_ROOT'] . '/user_print/'.$customer_folder.'/';
                                $dh = opendir($dir);
                                $file = false;

                                if (file_exists($dir)) {
                                    $filelist = scandir($dir);
                                    foreach($filelist as $i => $filename) {
                                        if($filename !== '.' && $filename !== '..') {
                                            $str = explode('_', $filename);
                                            $name = $str[array_key_last($str)];

                                            if (array_key_exists(array_search($product['product_id'], $str), $str) == true) {
                                                $new_name = $customer_id.'_'.$product['key'].'_'.$product['variation_id'] . '_' . $name;
                                                rename($dir.$filename, $dir.$new_name);
                                            }
                                        }
                                    }


                                    while (false !== ($filename = readdir($dh))):
                                        if ($filename !== '.' && $filename !== '..'):
                                            $file = true;

                                            $str_file = explode('_', $filename);

                                            if (isset($customer_folder)) {
                                                if (array_key_exists(array_search($product['key'], $str_file), $str_file) == true):?>

                                                    <div class="cart__file-item">
                                                        <img class="cart__file-img" src="<?php
                                                        $img = explode('.', $filename);
                                                        echo get_template_directory_uri() . '/assets/img/icons/' . end($img) . '.svg';
                                                        ?>">
                                                        <span class="cart__file-title"><?= str_replace($customer_id.'_'.$product_key.'_'.$product['variation_id'] . '_', '', $filename); ?></span>
                                                        <div class="cart__file-buttons">
                                                            <div class="cart__file-btn-del" data-file="<?= $filename ?>">
                                                                <svg class="icon">
                                                                    <use
                                                                            xlink:href="<?php echo get_template_directory_uri() ?>/assets/img/stack/sprite.svg#trash">
                                                                    </use>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                    </div>

                                                <?php
                                                endif;
                                            }

                                        endif;
                                    endwhile;
                                }

                                if (!$file) {
                                    echo '
                                    <div class="cart__file-without-items">
                                        <span>NO DOCUMENT ATTACHED TO PRODUCT</span>
                                    </div>
                                ';
                                }

                                ?>
                                </div>
                            </div>
                        </div>
                        <input name="files[]" id="uploadFiles" type="file" style="visibility: hidden; max-width: 280px" multiple="multiple" accept=".jpeg, .png, .pdf, .ai, .eps" data-product-id="<?php echo $product_key.'_'.$product['variation_id']; ?>" />
                        <div class="cart__item-upload" for="uploadFiles">upload file</div>
                        <div class="cart__item-button">
                            <?php $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $product ) : '', $product, $product_key ); ?>
                            <a class="cart__item-edit" href="<?= $product_permalink ?>">
                                <span>Edit PRODUCT</span>
                            </a>
                            <a class="cart__item-delete" href="<?= wc_get_cart_remove_url($product_key)?>">
                                <span>DELETE</span>
                            </a>
                        </div>
                    </li>

                </ul>
            </div>
            <?php endforeach; ?>

            <?php
//            echo '<pre>';
//            print_r(WC()->cart->get_cart());
//            echo '</pre>';
            WC()->cart->set_subtotal($total);
            ?>
        </div>


        <div class="total is-desktop ">
            <div class="total__text-tablet">SUBTOTAL <span class="total__sum">
                    <?= '$'.WC()->cart->get_subtotal() ?></span></div>
<!--            <div class="total__options">-->
<!--                <div class="total__options-question">?</div>-->
<!--                <div>Estimate Shipping</div>-->
<!--                <div class="total__arrow"></div>-->
<!--            </div>-->
<!--            <label class="total__deliver" for="deliver">Deliver to </label>-->
<!--            <select name="deliver" class="total__deliver-box"></select>-->
<!--            <div class="total__zip-box">-->
<!--                <div class="total__zip-wrap">-->
<!--                    <label class="total__zip" for="zip">Zip Code*</label>-->
<!--                    <input name="zip" class="total__zip-text" type="text">-->
<!--                </div>-->
<!--                <button class="total__zip-button">CHECK</button>-->
<!--            </div>-->
            <!-- <div class="total__options">
                <div class="total__options-question">?</div>
                <div>Priority options</div>
                <div class="total__arrow"></div>
            </div> -->
            <!-- <div class="total__prioity-box">
                <div class="total__point is-active"></div>
                <div class="total__prioity-wrap">
                    <span class="total__prioity-title">High prioity</span>
                    <span class="total__prioity-text">Delivery by Wed, Sep 2nd</span>
                </div>
                <div class="total__prioity-price">
                    16.01$
                </div>
            </div>
            <div class="total__prioity-box">
                <div class="total__point"></div>
                <div class="total__prioity-wrap">
                    <span class="total__prioity-title">Standard</span>
                    <span class="total__prioity-text">Delivery by Wed, Sep 8th</span>
                </div>
                <div class="total__prioity-price">
                    8.24$
                </div>
            </div>
            <div class="total__prioity-box">
                <div class="total__point"></div>
                <div class="total__prioity-wrap">
                    <span class="total__prioity-title">Saver</span>
                    <span class="total__prioity-text">Delivery by Wed, Sep 10th</span>
                </div>
                <div class="total__prioity-price">
                    0.00$
                </div>
            </div> -->


            <div class="total__box">
                <input class="total__discount" placeholder="Discount Code"<?php if (isset(WC()->cart->applied_coupons[0])): ?> value="<?= WC()->cart->applied_coupons[0]; ?>"<?php endif ?>>
                <div class="total__apply">APPLY</div>
            </div>
<!--            <div class="total__grand">-->
<!--                <span class="total__grand-text">GRAND TOTAL</span>-->
<!--                <span class="total__grand-price">$--><?//= WC()->cart->get_subtotal() ?><!--</span>-->
<!--            </div>-->
            <a href="<?php
            $products = WC()->cart->get_cart();

            if (!empty($products)) {
                echo esc_url(wc_get_checkout_url());
            } else {
                echo '#';
            }
            ?>">
                <div class="total__button">Proceed to checkout</div>
            </a>

            <div class="total__customer">
                <div class="total__customer-text">
                    Special instructions for seller
                </div>
                <textarea class="total__customer-textarea" name="cart-customer-note" rows="10"></textarea>
            </div>
        </div>
    </div>
</div>
<div class="total is-mobile">
    <div class="total__text">TOTAL <span class="total__sum"><?= '$'.WC()->cart->get_subtotal() ?></span></div>
    <div class="total__text-tablet">SUBTOTAL <span class="total__sum">25,98$</span></div>
    <div class="total__box">
        <input class="total__discount" placeholder="Discount Code">
        <div class="total__apply">APPLY</div>
    </div>

    <a href="<?php
    $products = WC()->cart->get_cart();

    if (!empty($products)) {
        echo esc_url(wc_get_checkout_url());
    } else {
        echo '#';
    }
    ?>">
    <div class="total__button">Proceed to checkout</div>
    </a>

    <div class="total__customer-mobile">
        <div class="total__customer-text">
            Special instructions for seller
        </div>
        <textarea class="total__customer-textarea" name="cart-mobile-customer-note" rows="10"></textarea>
    </div>
</div>
<?php
get_footer();
?>
