<?php
/* Template Name: Checkout */
get_header();

if ((strpos($_SERVER['REQUEST_URI'], 'order-received') !== false && !empty($_GET['key']))):
    ?>

    <div class="success-page container" >
        <img class="success-page_img"
             src="<?php echo get_template_directory_uri() ?>/assets/img/icons/success-page.png">
        <h1 class="success-page_title">
            Success! Thank you for order.
        </h1>
        <a href="/"><button class="success-page_button">HOME PAGE</button></a>

    </div>

<?php
elseif (!empty($_GET['order'])):
    global $woocommerce;
    global $wpdb;
    $order = $_GET['order'];

    $query = ("SELECT data FROM wp_custom_order WHERE href = '$order'");
    $data = $wpdb->get_row($query, ARRAY_A);

    $data = json_decode($data['data'], true);

    $user_data = $data['account'];

    $woocommerce->cart->empty_cart();

    WC()->customer->set_billing_email($user_data[0]['value']);
    WC()->customer->set_billing_phone($user_data[1]['value']);
    WC()->customer->set_billing_first_name($user_data[2]['value']);
    WC()->customer->set_billing_last_name($user_data[3]['value']);
    WC()->customer->set_props(
        array(
            'billing_country' => $user_data[7]['value'],
            'billing_state' => $user_data[8]['value'],
            'billing_postcode' => $user_data[6]['value'],
            'billing_city' => $user_data[9]['value'],
            'billing_address_1' => $user_data[4]['value'],
            'billing_address_2' => $user_data[5]['value'],
        )
    );

    WC()->customer->set_shipping_first_name($user_data[11]['value']);
    WC()->customer->set_shipping_last_name($user_data[12]['value']);
    WC()->customer->set_props(
        array(
            'shipping_country' => $user_data[16]['value'],
            'shipping_state' => $user_data[17]['value'],
            'shipping_postcode' => $user_data[15]['value'],
            'shipping_city' => $user_data[18]['value'],
            'shipping_address_1' => $user_data[13]['value'],
            'shipping_address_2' => $user_data[14]['value'],
        )
    );

    WC()->customer->save();

    $products = $data['products'];

    if ($products) {

//        echo "<pre>";
//        print_r($products);
//        echo "</pre>";
//        die;

        foreach ($products as $product_item) {
            $quantity = $product_item['count'];
            $variation_id = $product_item['id'];
            $_POST['product_id'] = $_POST['variation_id'] = $variation_id;
            $_POST['quantity'] = $quantity;

            if (isset($product_item['customize'])) {
                $_POST['custom_variant'] = true;

                if (isset($product_item['customize']['custom_weight'])) {
                    $_POST['custom_weight'] = 1;
                } elseif (isset($_POST['custom_weight'])) {
                    unset($_POST['custom_weight']);
                }

                if (isset($product_item['customize']['width']) && isset($product_item['customize']['height'])) {
                    $_POST['variant'] = $product_item['customize']['width'].'-x-'.$product_item['customize']['height'];
                } elseif (isset($_POST['variant'])) {
                    unset($_POST['variant']);
                }

                if (isset($product_item['customize']['new_name'])) {
                    $_POST['new_name'] = $product_item['customize']['new_name'];
                } elseif (isset($_POST['new_name'])) {
                    unset($_POST['new_name']);
                }

                if (isset($product_item['customize']['new_price'])) {
                    $_POST['new_price'] = $product_item['customize']['new_price'];
                } elseif (isset($_POST['new_price'])) {
                    unset($_POST['new_price']);
                }

            } elseif ( !isset($product_item['customize']) ) {
                if (isset($_POST['custom_variant'])) unset($_POST['custom_variant']);
                if (isset($_POST['custom_weight'])) unset($_POST['custom_weight']);
                if (isset($_POST['variant'])) unset($_POST['variant']);
                if (isset($_POST['new_name'])) unset($_POST['new_name']);
                if (isset($_POST['new_price'])) unset($_POST['new_price']);
            }

//            echo 'Product ID: ' . $_POST['product_id'];
//            echo '<br>';

            if ( $product_item['customize'] ) {
//                echo '1';
                $result = WC()->cart->add_to_cart($_POST['product_id'], $_POST['quantity'], $_POST['variation_id'], $_POST['variant'], $_POST['variant']);
            } else {
//                echo '2';
                $result = WC()->cart->add_to_cart($_POST['product_id'], $_POST['quantity']);
            }

            $product_new_add_cart = [];
            $products_array = [];
            foreach (WC()->cart->get_cart() as $product_key => $product):
                $_product = apply_filters( 'woocommerce_cart_item_product', $product['data'], $product, $product_key );

                $arrtibutes_array = [];
                $attributes = $_product->get_attributes();
                foreach ($attributes as $attribute=>$value):
                    $value = !is_object($value) ? $value : $_product->get_default_attributes()[$attribute];
                    if ($attribute == 'pa_set-size' && isset($product['custom_size'])) {
                        $value = $product['custom_size'];
                    }

                    $arrtibutes_array[] = [
                        'name' => implode(' ', array_map('ucfirst', explode('-', substr($attribute, 3)))),
                        'value' => $value,
                    ];
                endforeach;

                if ($product_key == $result) {
                    $product_new_add_cart = [
                        'id' => $result,
                        'img' => wp_get_attachment_image_url( $_product->get_image_id(), 'full', '$icon' ),
                        'name' => $_product->get_name(),
                        'quantity' => $product['quantity'],
                        'price' => WC()->cart->get_product_subtotal( $_product, $product['quantity'] ),
                        'attributes' => $arrtibutes_array,
                        'action' => [
                            'edit' => apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $product ) : '', $product, $product_key ),
                            'delete' => wc_get_cart_remove_url($product_key),
                        ],
                    ];
                }

                $products_array[$product_key] = [
                    'img' => wp_get_attachment_image_url( $_product->get_image_id(), 'full', '$icon' ),
                    'name' => $_product->get_name(),
                    'quantity' => $product['quantity'],
                    'price' => WC()->cart->get_product_subtotal( $_product, $product['quantity'] ),
                    'attributes' => $arrtibutes_array,
                    'action' => [
                        'edit' => apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $product ) : '', $product, $product_key ),
                        'delete' => wc_get_cart_remove_url($product_key),
                    ],
                ];
            endforeach;
        }
    }

//    echo '<pre>';
//    print_r(WC()->cart->get_cart());
//    echo '</pre>';
//    die();

    if (!empty($user_data[4]['value']) && !empty($user_data[5]['value']) && !empty($user_data[7]['value']) && !empty($user_data[8]['value']) && !empty($user_data[9]['value'])) {
        WC()->cart->calculate_shipping();
        WC()->cart->calculate_totals();

        ob_start();
        woocommerce_order_review();
        $woocommerce_order_review = ob_get_clean();

        ob_start();
        woocommerce_checkout_payment();
        $woocommerce_checkout_payment = ob_get_clean();

        $reload_checkout = isset(WC()->session->reload_checkout) ? true : false;
        if (!$reload_checkout) {
            $messages = wc_print_notices(true);
        } else {
            $messages = '';
        }

        $shipments = '';
        WC()->cart->calculate_totals();
        $packages = WC()->shipping()->get_packages();

        $index = 0;
        foreach ($packages as $i => $package):
            foreach ($package['rates'] as $method):

                $shipments .= '<div class="total__prioity-box shipping_method_js"><input id="shipping_method_';
                $shipments .= $index;
                $shipments .= '_';
                $shipments .= esc_attr(sanitize_title($method->id));
                $shipments .= '" name="shipping_method[';
                $shipments .= $index;
                $shipments .= ']" data-index="';
                $shipments .= $index;
                $shipments .= '" class="total__point" type="radio" value="';
                $shipments .= esc_attr($method->id);
                $shipments .= '" data-price="';
                $shipments .= WC()->cart->get_cart_contents_total() + $method->cost;
                $shipments .= '" ';
                $shipments .= '><label for="shipping_method_';
                $shipments .= $index;
                $shipments .= '_';
                $shipments .= esc_attr(sanitize_title($method->id));
                $shipments .= '" class="total__prioity-wrap"><span class="total__prioity-title">';
                $shipments .= $method->label;
                $shipments .= '</span></label><div class="total__prioity-price">';
                $shipments .= ($method->cost != '0.00') ? $method->cost : 'free';
                $shipments .= '</div></div>';

            endforeach;
            $index++;
        endforeach;

        unset(WC()->session->refresh_totals, WC()->session->reload_checkout);

        $result = [
            'result' => empty($messages) ? 'success' : 'failure',
            'messages' => $messages,
            'reload' => $reload_checkout,
            'fragments' => apply_filters(
                'woocommerce_update_order_review_fragments',
                array(
                    '.woocommerce-checkout-review-order-table' => $woocommerce_order_review,
                    '.woocommerce-checkout-payment' => $woocommerce_checkout_payment,
                )
            ),
            'shippments' => $shipments,
            'packages' => $packages,
            'error' => WC()->session->get('wc_notices', array()),
            'tax_totals' => array_values(WC()->cart->get_tax_totals())
        ];

        $result = json_encode($result, JSON_HEX_QUOT);
        wp_redirect('https://bannerprintingphoenix.com/cart/');
    } else {
        wp_redirect('https://bannerprintingphoenix.com/cart/');
    }
?>
    <script>
        let dataArr = JSON.stringify(<?php echo $result; ?>)
        localStorage.setItem('dataArr', dataArr)
        location.href = 'https://bannerprintingphoenix.com/cart/'
    </script>
<?php
else:
    $products = WC()->cart->get_cart();

    if (empty($products)) {
        wp_redirect('/');
    }

    $WC_Checkout = new WC_Checkout();
    $WC_Order = new WC_Order();
    $fields = $WC_Checkout->get_checkout_fields('billing');
    $fieldsShipping = $WC_Checkout->get_checkout_fields('shipping');

    $countrie = array_column($fields, 'shipping_country');

    $countries = (count($countrie)) ? WC()->countries->get_shipping_countries() : WC()->countries->get_allowed_countries();

    if (!is_ajax()) {
        do_action('woocommerce_review_order_before_payment');
    }

    ?>

<div class="message"></div>

    <div class="container checkout">
        <input type="hidden" value='<span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span><?= WC()->cart->get_subtotal() ?></bdi></span>' name="subtotalinfo">
    <a class="checkout__link-back is-tablet back_checkout">Back</a>
    <div class="checkout__breadcrumb-wrap is-only-desktop">
        <ol class="checkout__breadcrumb">
            <li class="checkout__breadcrumb-item"><a href="<?php echo esc_url( wc_get_cart_url() ); ?>">Cart</a></li>
            <li class="checkout__breadcrumb-item"><a href="#">Shipping Addres</a></li>
            <li class="checkout__breadcrumb-item"><a href="#">Shipping Method</a></li>
        </ol>
    </div>

    <h2 class="checkout__title">CHECKOUT</h2>
    <div class="checkout__box">
        <style>
            .checkout__inputs.is-active {
                display: block;
            }
        </style>

        <div class="checkout__inputs d-flex" id="checkout">
            <div class="checkout__header">
                <span class="checkout__text">1. Shipping and Billing Address</span>
                <span class="checkout__description is-mobile">Please fill the forms below</span>
            </div>

           <!-- <div class="checkout__header-contact">
        <span class="checkout__text">
          <span>ADD CONTACT INFORMATION</span>
            <span class="checkout__text-account">Already have an account? <a href="#" class="checkout__link-account">Log in</a></span>
        </span>
            </div> -->
            <!-- <span class="checkout__subtitle">EXPRESS CHECKOUT</span>
            <div class="checkout__paypal">
                <button class="checkout__paypal-button">
                    <img src="<?php echo get_template_directory_uri() ?>/assets/img/stack/sprite.svg#paypal-big" alt="">
                </button>
            </div> -->
            <?php if (isset($fields['billing_email'])): ?>
                <div class="checkout__wrap checkout__wrap-maildesktop">
                    <div class="checkout__field-wrap">
                    <label class="checkout__field-text checkout__field-bigtext"
                           for="billing_email"><?= $fields['billing_email']['label'] ?><?= ($fields['billing_email']['required']) ? ' *' : '' ?></label>
                    <input class="checkout__field checkout__field-big" id="billing_email"
                           name="billing_email"<?php $value = $WC_Checkout->get_value('billing_email');
                    if (isset($value) && !empty($value)): ?> value="<?= $value ?>"<?php endif; ?>
                           type="text" <?= ($fields['billing_email']['autocomplete']) ? 'autocomplete="' . $fields['billing_email']['autocomplete'] . '"' : '' ?>
                        <?= ($fields['billing_email']['required']) ? ' data-required="true" data-error="'.$fields['billing_email']['label'].' required" data-prav="\S+@\S+\.\S+"' : '' ?>>
                    </div>
                    <div class="checkout__field-box checkout__field-reminde">
                        <input id="checked-3" class="checkout__field-checkbox" name="checked" type="checkbox">
                        <label class="checkout__field-label" for="checked-3">Keep me up to date on news and exclusive offers</label>
                    </div>
                </div>
            <?php endif ?>

            <div class="checkout__wrapper">
                <?php if (isset($fieldsShipping['shipping_first_name'])): ?>
                    <div class="checkout__field-wrap">
                        <label class="checkout__field-text"
                               for="shipping_first_name"><?= $fieldsShipping['shipping_first_name']['label'] ?><?= ($fieldsShipping['shipping_first_name']['required']) ? ' *' : '' ?></label>
                        <input class="checkout__field" id="shipping_first_name"
                               name="shipping_first_name"<?php $value = $WC_Checkout->get_value('shipping_first_name');
                        if (isset($value) && !empty($value)): ?> value="<?= $value ?>"<?php endif; ?>
                               type="text"<?= ($fieldsShipping['shipping_first_name']['autocomplete']) ? ' autocomplete="' . $fieldsShipping['shipping_first_name']['autocomplete'] . '"' : '' ?>
                            <?= ($fieldsShipping['shipping_first_name']['required']) ? ' data-required="true" data-error="'.$fieldsShipping['shipping_first_name']['label'].' required"' : '' ?>>
                    </div>
                <?php endif ?>
                <?php if (isset($fieldsShipping['shipping_last_name'])): ?>
                    <div class="checkout__field-wrap">
                        <label class="checkout__field-text"
                               for="shipping_last_name"><?= $fieldsShipping['shipping_last_name']['label'] ?><?= ($fieldsShipping['shipping_last_name']['required']) ? ' *' : '' ?></label>
                        <input class="checkout__field" id="shipping_last_name"
                               name="shipping_last_name"<?php $value = $WC_Checkout->get_value('shipping_last_name');
                        if (isset($value) && !empty($value)): ?> value="<?= $value ?>"<?php endif; ?>
                               type="text"<?= ($fieldsShipping['shipping_last_name']['autocomplete']) ? 'autocomplete="' . $fieldsShipping['shipping_last_name']['autocomplete'] . '"' : '' ?>
                            <?= ($fieldsShipping['shipping_last_name']['required']) ? ' data-required="true" data-error="'.$fieldsShipping['shipping_last_name']['label'].' required"' : '' ?>>
                    </div>
                <?php endif ?>
            </div>

            <?php if (isset($fieldsShipping['shipping_address_1'])): ?>
                <div class="checkout__field-wrap">

                    <label class="checkout__field-text checkout__field-bigtext"
                           for="shipping_address_1"><?= $fieldsShipping['shipping_address_1']['label'] ?><?= ($fieldsShipping['shipping_address_1']['required']) ? ' *' : '' ?></label>
                    <input class="checkout__field checkout__field-big" id="shipping_address_1"
                           name="shipping_address_1"<?php $value = $WC_Checkout->get_value('shipping_address_1');
                    if (isset($value) && !empty($value)): ?> value="<?= $value ?>"<?php endif; ?>
                           type="text"<?= ($fieldsShipping['shipping_address_1']['autocomplete']) ? 'autocomplete="' . $fieldsShipping['shipping_address_1']['autocomplete'] . '"' : '' ?>
                        <?= ($fieldsShipping['shipping_address_1']['required']) ? ' data-required="true" data-error="'.$fieldsShipping['shipping_address_1']['label'].' required"' : '' ?>>
                </div>
            <?php endif ?>

            <?php if (isset($fieldsShipping['shipping_address_2'])): ?>
                <div class="checkout__field-wrap">
                    <label class="checkout__field-text checkout__field-bigtext"
                           for="shipping_address_2"><?= $fieldsShipping['shipping_address_2']['label'] ?><?= ($fieldsShipping['shipping_address_2']['required']) ? ' *' : '' ?></label>
                    <input class="checkout__field checkout__field-big" id="shipping_address_2"
                           name="shipping_address_2"<?php $value = $WC_Checkout->get_value('shipping_address_2');
                    if (isset($value) && !empty($value)): ?> value="<?= $value ?>"<?php endif; ?>
                           type="text"<?= ($fieldsShipping['shipping_address_2']['autocomplete']) ? 'autocomplete="' . $fieldsShipping['shipping_address_2']['autocomplete'] . '"' : '' ?>
                        <?= ($fieldsShipping['shipping_address_2']['required']) ? ' data-required="true" data-error="'.$fieldsShipping['shipping_address_2']['label'].' required"' : '' ?>>
                </div>
            <?php endif ?>

            <div class="checkout__field-box">
                <?php if (isset($fieldsShipping['shipping_postcode'])): ?>
                    <div class="checkout__field-wrap">
                        <label class="checkout__field-text"
                               for="shipping_postcode"><?= $fieldsShipping['shipping_postcode']['label'] ?><?= ($fieldsShipping['shipping_postcode']['required']) ? ' *' : '' ?></label>
                        <input class="checkout__field checkout__field-small checkout__field-zip" id="shipping_postcode"
                               name="shipping_postcode"
                            <?php $value = $WC_Checkout->get_value('shipping_postcode');
                            if (isset($value) && !empty($value)): ?> value="<?= $value ?>"<?php endif; ?>
                               type="text"
                            <?= ($fieldsShipping['shipping_postcode']['autocomplete']) ? 'autocomplete="' . $fieldsShipping['shipping_postcode']['autocomplete'] . '"' : '' ?>
                            <?= ($fieldsShipping['shipping_postcode']['required']) ? ' data-required="true" data-error="'.$fieldsShipping['shipping_postcode']['label'].' required"' : '' ?>>
                    </div>
                <?php endif ?>
                <?php if (isset($fieldsShipping['shipping_country'])): ?>
                    <div class="checkout__field-wrap">
                        <label class="checkout__field-text"
                               for="shipping_country"><?= $fieldsShipping['shipping_country']['label'] ?><?= ($fieldsShipping['shipping_country']['required']) ? ' *' : '' ?></label>
                        <select class="checkout__field checkout__field-small" id="shipping_country"
                                name="shipping_country"
                                type="text">
                            <?php foreach ($countries as $ckey => $cvalue): ?>
                                <option value="<?= $ckey ?>"<?php $value = $WC_Checkout->get_value('shipping_country');
                                if (isset($value) && !empty($value) && $ckey == $value): ?> selected="selected"<?php endif; ?>><?= $cvalue ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif ?>
            </div>

            <?php
            $for_country = $WC_Checkout->get_value('shipping_country');
            $states = WC()->countries->get_states( $for_country );
            ?>

            <?php
            if (isset($fieldsShipping['shipping_state'])): ?>
                <div class="checkout__field-wrap bil_ship_state">
                    <?php $value = $WC_Checkout->get_value('shipping_state'); ?>
                    <label class="checkout__field-text checkout__field-bigtext"
                           for="shipping_state"><?= $fieldsShipping['shipping_state']['label'] ?><?= ($fieldsShipping['shipping_state']['required']) ? ' *' : '' ?></label>
                    <select class="checkout__field checkout__field-big" name="shipping_state" type="text" id="shipping_state">
                        <?php
                        if (!empty($states)):
                            foreach ($states as $key=>$state):
                                ?>
                                <option value="<?= $key ?>"<?php if ($key == $value): ?> selected="selected"<?php endif; ?>><?= $state ?></option>
                            <?php
                            endforeach;
                        else:
                            ?>
                            <option value="<?= $value ?>" selected="selected"><?= $countries[$value] ?></option>
                        <?php
                        endif;
                        ?>
                    </select>
                </div>
            <?php endif ?>
            <?php if (isset($fieldsShipping['shipping_city'])): ?>
                <div class="checkout__field-wrap">
                    <label class="checkout__field-text checkout__field-bigtext"
                           for="shipping_city"><?= $fieldsShipping['shipping_city']['label'] ?><?= ($fieldsShipping['shipping_city']['required']) ? ' *' : '' ?></label>
                    <input class="checkout__field checkout__field-big" id="shipping_city"
                           name="shipping_city"<?php $value = $WC_Checkout->get_value('shipping_city');
                    if (isset($value) && !empty($value)): ?> value="<?= $value ?>"<?php endif; ?>
                           type="text"<?= ($fieldsShipping['shipping_city']['autocomplete']) ? 'autocomplete="' . $fieldsShipping['shipping_city']['autocomplete'] . '"' : '' ?>
                        <?= ($fieldsShipping['shipping_city']['required']) ? ' data-required="true" data-error="'.$fieldsShipping['shipping_city']['label'].' required"' : '' ?>>
                </div>
            <?php endif ?>

            <?php if (isset($fields['billing_phone'])): ?>
            <div class="checkout__field-wrap">
                <label class="checkout__field-text checkout__field-bigtext " for="billing_phone">
                    <?= $fields['billing_phone']['label'] ?><?= ($fields['billing_phone']['required']) ? ' *' : '' ?>
                </label>
                <input class="checkout__field checkout__field-big checkout__field-number" id="billing_phone"
                       name="billing_phone"
                    <?php $value = $WC_Checkout->get_value('billing_phone');
                    if (isset($value) && !empty($value)): ?> value="<?= $value ?>"<?php endif; ?>
                       type="text"
                    <?= ($fields['billing_phone']['autocomplete']) ? 'autocomplete="' . $fields['billing_phone']['autocomplete'] . '"' : '' ?>
                    <?= ($fields['billing_phone']['required']) ? ' data-required="true" data-error="Enter your phone number in the form +1ZZZYYYXXXX"' : '' ?>> <!-- data-prav="^\+?[1][2-9][0-8][0-9][2-9][\d]{6}$"-->
            <?php endif ?>
            </div>

            <?php if (isset($fields['billing_email'])): ?>
                <div class="checkout__field-mail">
                    <label class="checkout__field-text checkout__field-bigtext"
                           for="billing_email1"><?= $fields['billing_email']['label'] ?><?= ($fields['billing_email']['required']) ? ' *' : '' ?></label>
                    <input class="checkout__field checkout__field-big" id="billing_email1"
                           name="billing_email1"<?php $value = $WC_Checkout->get_value('billing_email');
                    if (isset($value) && !empty($value)): ?> value="<?= $value ?>"<?php endif; ?>
                           type="email" <?= ($fields['billing_email']['autocomplete']) ? 'autocomplete="' . $fields['billing_email']['autocomplete'] . '"' : '' ?>
                        <?= ($fields['billing_email']['required']) ? ' data-required="true" data-error="'.$fields['billing_email']['label'].' required" data-prav="\S+@\S+\.\S+"' : '' ?>>
                </div>
            <?php endif ?>


            <div class="checkout__shipping-address-toggle">
                <input class="checkout__field-checkbox" id="checked-billing-address-off" name="checked_billing_address" type="checkbox" value="off" checked>
                <label class="checkout__field-label" for="checked-billing-address-off">Keep billing and shipping address the same</label>
            </div>
            <div class="checkout__shipping-address-toggle">
                <input class="checkout__field-checkbox" id="checked-billing-address-on" name="checked_billing_address" type="checkbox" value="on">
                <label class="checkout__field-label" for="checked-billing-address-on">Use different billing address</label>
            </div>

            <div class="checkout__shipping-address" style="display: none;">


                <div class="checkout__wrapper">
                    <?php if (isset($fields['billing_first_name'])): ?>
                        <div class="checkout__field-wrap">
                            <label class="checkout__field-text"
                                   for="billing_first_name"><?= $fields['billing_first_name']['label'] ?><?= ($fields['billing_first_name']['required']) ? ' *' : '' ?></label>
                            <input class="checkout__field" id="billing_first_name"
                                   name="billing_first_name"<?php $value = $WC_Checkout->get_value('billing_first_name');
                            if (isset($value) && !empty($value)): ?> value="<?= $value ?>"<?php endif; ?>
                                   type="text"<?= ($fields['billing_first_name']['autocomplete']) ? ' autocomplete="' . $fields['billing_first_name']['autocomplete'] . '"' : '' ?>
                                <?= ($fields['billing_first_name']['required']) ? ' data-required="true" data-error="'.$fields['billing_first_name']['label'].' required"' : '' ?>>
                        </div>
                    <?php endif ?>
                    <?php if (isset($fields['billing_last_name'])): ?>
                        <div class="checkout__field-wrap">
                            <label class="checkout__field-text"
                                   for="billing_last_name"><?= $fields['billing_last_name']['label'] ?><?= ($fields['billing_last_name']['required']) ? ' *' : '' ?></label>
                            <input class="checkout__field" id="billing_last_name"
                                   name="billing_last_name"<?php $value = $WC_Checkout->get_value('billing_last_name');
                            if (isset($value) && !empty($value)): ?> value="<?= $value ?>"<?php endif; ?>
                                   type="text"<?= ($fields['billing_last_name']['autocomplete']) ? 'autocomplete="' . $fields['billing_last_name']['autocomplete'] . '"' : '' ?>
                                <?= ($fields['billing_last_name']['required']) ? ' data-required="true" data-error="'.$fields['billing_last_name']['label'].' required"' : '' ?>>
                        </div>
                    <?php endif ?>
                </div>

                <?php if (isset($fields['billing_address_1'])): ?>
                    <div class="checkout__field-wrap">

                        <label class="checkout__field-text checkout__field-bigtext"
                               for="billing_address_1"><?= $fields['billing_address_1']['label'] ?><?= ($fields['billing_address_1']['required']) ? ' *' : '' ?></label>
                        <input class="checkout__field checkout__field-big" id="billing_address_1"
                               name="billing_address_1"<?php $value = $WC_Checkout->get_value('billing_address_1');
                        if (isset($value) && !empty($value)): ?> value="<?= $value ?>"<?php endif; ?>
                               type="text"<?= ($fields['billing_address_1']['autocomplete']) ? 'autocomplete="' . $fields['billing_address_1']['autocomplete'] . '"' : '' ?>
                            <?= ($fields['billing_address_1']['required']) ? ' data-required="true" data-error="'.$fields['billing_address_1']['label'].' required"' : '' ?>>
                    </div>
                <?php endif ?>

                <?php if (isset($fields['billing_address_2'])): ?>
                    <div class="checkout__field-wrap">
                        <label class="checkout__field-text checkout__field-bigtext"
                               for="billing_address_2"><?= $fields['billing_address_2']['label'] ?><?= ($fields['billing_address_2']['required']) ? ' *' : '' ?></label>
                        <input class="checkout__field checkout__field-big" id="billing_address_2"
                               name="billing_address_2"<?php $value = $WC_Checkout->get_value('billing_address_2');
                        if (isset($value) && !empty($value)): ?> value="<?= $value ?>"<?php endif; ?>
                               type="text"<?= ($fields['billing_address_2']['autocomplete']) ? 'autocomplete="' . $fields['billing_address_2']['autocomplete'] . '"' : '' ?>
                            <?= ($fields['billing_address_2']['required']) ? ' data-required="true" data-error="'.$fields['billing_address_2']['label'].' required"' : '' ?>>
                    </div>
                <?php endif ?>

                <div class="checkout__field-box">
                    <?php if (isset($fields['billing_postcode'])): ?>
                        <div class="checkout__field-wrap">
                            <label class="checkout__field-text"
                                   for="billing_postcode"><?= $fields['billing_postcode']['label'] ?><?= ($fields['billing_postcode']['required']) ? ' *' : '' ?></label>
                            <input class="checkout__field checkout__field-small checkout__field-zip" id="billing_postcode"
                                   name="billing_postcode"
                                <?php $value = $WC_Checkout->get_value('billing_postcode');
                                if (isset($value) && !empty($value)): ?> value="<?= $value ?>"<?php endif; ?>
                                   type="text"
                                <?= ($fields['billing_postcode']['autocomplete']) ? 'autocomplete="' . $fields['billing_postcode']['autocomplete'] . '"' : '' ?>
                                <?= ($fields['billing_postcode']['required']) ? ' data-required="true" data-error="'.$fields['billing_postcode']['label'].' required"' : '' ?>>
                        </div>
                    <?php endif ?>
                    <?php if (isset($fields['billing_country'])): ?>
                        <div class="checkout__field-wrap">
                            <label class="checkout__field-text"
                                   for="billing_country"><?= $fields['billing_country']['label'] ?><?= ($fields['billing_country']['required']) ? ' *' : '' ?></label>
                            <select class="checkout__field checkout__field-small" id="billing_country"
                                    name="billing_country"
                                    type="text">
                                <?php foreach ($countries as $ckey => $cvalue): ?>
                                    <option value="<?= $ckey ?>"<?php $value = $WC_Checkout->get_value('billing_country');
                                    if (isset($value) && !empty($value) && $ckey == $value): ?> selected="selected"<?php endif; ?>><?= $cvalue ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif ?>
                </div>

                <?php
                $for_country = $WC_Checkout->get_value('billing_country');
                $states = WC()->countries->get_states( $for_country );
                ?>

                <?php
                if (isset($fields['billing_state'])): ?>
                    <div class="checkout__field-wrap bil_state">
                        <?php $value = $WC_Checkout->get_value('billing_state'); ?>
                        <label class="checkout__field-text checkout__field-bigtext"
                               for="billing_state"><?= $fields['billing_state']['label'] ?><?= ($fields['billing_state']['required']) ? ' *' : '' ?></label>
                        <select class="checkout__field checkout__field-big" name="billing_state" type="text" id="billing_state">
                            <?php
                            if (!empty($states)):
                                foreach ($states as $key=>$state):
                                    ?>
                                    <option value="<?= $key ?>"<?php if ($key == $value): ?> selected="selected"<?php endif; ?>><?= $state ?></option>
                                <?php
                                endforeach;
                            else:
                                ?>
                                <option value="<?= $value ?>" selected="selected"><?= $countries[$value] ?></option>
                            <?php
                            endif;
                            ?>
                        </select>
                    </div>
                <?php endif ?>

                <?php if (isset($fields['billing_city'])): ?>
                    <div class="checkout__field-wrap">
                        <label class="checkout__field-text checkout__field-bigtext"
                               for="billing_city"><?= $fields['billing_city']['label'] ?><?= ($fields['billing_city']['required']) ? ' *' : '' ?></label>
                        <input class="checkout__field checkout__field-big" id="billing_city"
                               name="billing_city"<?php $value = $WC_Checkout->get_value('billing_city');
                        if (isset($value) && !empty($value)): ?> value="<?= $value ?>"<?php endif; ?>
                               type="text"<?= ($fields['billing_city']['autocomplete']) ? 'autocomplete="' . $fields['billing_city']['autocomplete'] . '"' : '' ?>
                            <?= ($fields['billing_city']['required']) ? ' data-required="true" data-error="'.$fields['billing_city']['label'].' required"' : '' ?>>
                    </div>
                <?php endif ?>
            </div>

<!--            <div class="checkout__field-box checkout__field-boxsmall">-->
<!--                <input class="checkout__field-checkbox" id="checked-1" name="checked" type="checkbox">-->
<!--                <label class="checkout__field-label" for="checked-1">Keep me up to date on news and exclusive offers</label>-->
<!--            </div>-->

            <div class="checkout__box-bottom">
<!--                <div class="checkout__bottom">By creating an account, you agree to-->
<!--                    our <a href="#">Terms and Conditions</a> and <a href="#">Privacy Policy</a>.-->
<!--                </div>-->

                <div>
<!--                    <div class="checkout__text-error is-desktop">Please enter Zip Code</div>-->
                    <button class="checkout__button">CONTINUE TO SHIPPING</button>
                </div>
            </div>

            <div class="checkout__shipping">
            </div>
        </div>

        <div class="checkout__inputs checkout__step-two">
            <div class="checkout__header checkout__step-header">
                <span class="checkout__text checkout__step shipping_title_one">
                  1. Shipping and Billing Address
                </span>
            </div>
            <div class="checkout__frame shipping" style="display: none;">
                <span class="checkout__name">Anna K</span>
                <span class="checkout__address">1st steet 3245, Middleport, Ohio, US, 45760</span>
                <span class="checkout__email">emailaddres@mail.com</span>
            </div>
            <div class="checkout__header checkout__step-header" style="display: none;">
                <span class="checkout__text checkout__step shipping_title_two">
                  Billing Address
                </span>
            </div>
            <div class="checkout__frame billing" style="display: none;">
                <span class="checkout__name">Anna K</span>
                <span class="checkout__address">1st steet 3245, Middleport, Ohio, US, 45760</span>
            </div>
            <a href="#" class="checkout__edit-button editchekoutdata">Edit shipping address</a>
            <div class="checkout__header checkout__step-header">
                <span class="checkout__text checkout__step ">2. Shipping METHOD</span>
            </div>

            <input type="hidden" value="" name="dataArr">
            <div class="total__prioity">
                <?php
                $data = [];
                WC()->cart->calculate_totals();
                $packages = WC()->shipping()->get_packages();

                $index = 0;
                foreach ($packages as $i => $package):
                    $chosen_method = isset(WC()->session->chosen_shipping_methods[$i]) ? WC()->session->chosen_shipping_methods[$i] : '';
                    foreach ($package['rates'] as $method_key => $method):
                        ?>
                        <div class="total__prioity-box shipping_method_js">
                            <input id="shipping_method_<?= $index ?>_<?= esc_attr(sanitize_title($method->id)) ?>"
                                   name="shipping_method[<?= $index ?>]" data-index="<?= $index ?>" class="total__point"
                                   type="radio" value="<?= esc_attr($method->id) ?>"
                                   data-price="<?= WC()->cart->get_cart_contents_total() + $method->cost ?>">
                            <label for="shipping_method_<?= $index ?>_<?= esc_attr(sanitize_title($method->id)) ?>"
                                   class="total__prioity-wrap">
                                <span class="total__prioity-title"><?= $method->label ?></span>
                            </label>
                            <div class="total__prioity-price"><?= ($method->cost != '0.00') ? $method->cost : 'free' ?></div>
                        </div>
                    <?php
                    endforeach;
                    $index++;
                endforeach; ?>
            </div>
            <button class="checkout__button">CONTINUE TO PAYMENT</button>
        </div>

        <div class="checkout__inputs checkout__step-tree">
            <!--    <form class="checkout__inputs checkout__step-tree woocommerce-checkout" method="post" enctype="multipart/form-data" novalidate="novalidate" name="checkout" action="https://bannerprintingsandiego.com/checkout/">-->
            <div class="checkout__header checkout__step-header">
                <span class="checkout__text shipping_title_one">
                  1. Shipping and Billing Address
<!--                  <span class="checkout__editor is-only-tablet">EDIT</span>-->
                </span>
<!--                <span class="checkout__description is-only-mobile">Ordered product goes to</span>-->
            </div>
            <div class="checkout__frame shipping" style="display: none;">
                <span class="checkout__name">Anna K</span>
                <span class="checkout__address">1st steet 3245, Middleport, Ohio, US, 45760</span>
                <span class="checkout__email">emailaddres@mail.com</span>
            </div>

            <div class="checkout__header checkout__step-header" style="display: none;">
                <span class="checkout__text checkout__step shipping_title_two">
                  Billing Address
                </span>
            </div>
            <div class="checkout__frame billing" style="display: none;">
                <span class="checkout__name">Anna K</span>
                <span class="checkout__address">1st steet 3245, Middleport, Ohio, US, 45760</span>
            </div>
            <a href="#" class="checkout__edit-button editchekoutdata">Edit shipping address</a>
            <div class="checkout__header checkout__step-header">
        <span class="checkout__text checkout__step ">
          2. Shipping METHOD
          <span class="checkout__editor is-only-tablet">EDIT</span>
        </span>
            </div>

            <div class="checkout__frame is-only-desktop">
        <span class="checkout__method">
          <div class="total__prioity-wrap">
            <span class="total__prioity-title">Standard</span>
<!--            <span class="total__prioity-text">Delivery by Wed, Sep 8th</span>-->
          </div>

        </span>
            </div>

            <div class="total__prioity total__prioity-payment is-tablet">
                <div class="total__prioity-box">
                    <input id="shippingMethod" name="shippingMethod-active" class="total__point" type="radio" value=""
                           checked>
                    <label for="shippingMethod" class="total__prioity-wrap">
                        <span class="total__prioity-title">Standard</span>
<!--                        <span class="total__prioity-text">Delivery by Wed, Sep 8th</span>-->
                    </label>
<!--                    <div class="total__prioity-price">-->
<!--                        8.24$-->
<!--                    </div>-->
                </div>
            </div>


            <a href="#step-2" class="checkout__edit-button editchekoutdata">Edit shipping method</a>
            <div class="checkout__header checkout__step-header">
                <span class="checkout__text checkout__step ">3. PAYMENT METHOD</span>
            </div>

            <div class="checkout__payment-wrapper">
                <div class="checkout__payment-wrap">


                    <?
                    $available_gateways = WC()->payment_gateways()->get_available_payment_gateways();
                    WC()->payment_gateways()->set_current_gateway($available_gateways);

                    //                    echo '<ul class="wc_payment_methods payment_methods methods">';
                    //                    if ( ! empty( $available_gateways ) ) {
                    //                        foreach ( $available_gateways as $gateway ) {
                    //                            wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
                    //                        }
                    //                    } else {
                    //                        echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) : esc_html__( 'Please fill in your details above to see available payment methods.', 'woocommerce' ) ) . '</li>'; // @codingStandardsIgnoreLine
                    //                    }
                    //                    echo '</ul>';

                    wc_get_template('checkout/payment-method.php', array('gateway' => $available_gateways['stripe']));

                    wc_get_template('checkout/payment-method.php', array('gateway' => $available_gateways['paypal']));
                    ?>


                    <div class="checkout__payment-item is-only-desktop">
                        <div class="checkout__payment-header checkout__payment-images is-only-desktop">
                            <?php
//                            echo kama_thumb_img([
//                                'width' => 40,
//                                'height'=> 25,
//                                'src'   => get_template_directory_uri() . '/assets/img/icons/visa-color.svg'
//                            ]);
//                            echo kama_thumb_img([
//                                'width' => 40,
//                                'height'=> 25,
//                                'src'   => get_template_directory_uri() . '/assets/img/icons/mc-color.svg'
//                            ]);
//                            echo kama_thumb_img([
//                                'width' => 40,
//                                'height'=> 25,
//                                'src'   => get_template_directory_uri() . '/assets/img/icons/ae-color.svg'
//                            ]);
//                            echo kama_thumb_img([
//                                'width' => 40,
//                                'height'=> 25,
//                                'src'   => get_template_directory_uri() . '/assets/img/icons/dc-color.svg'
//                            ]);
                            ?>
                            <img src="<?php echo get_template_directory_uri() ?>/assets/img/icons/visa-color.svg" alt="">
                            <img src="<?php echo get_template_directory_uri() ?>/assets/img/icons/mc-color.svg" alt="">
                            <img src="<?php echo get_template_directory_uri() ?>/assets/img/icons/ae-color.svg" alt="">
                            <img src="<?php echo get_template_directory_uri() ?>/assets/img/icons/dc-color.svg" alt="">
                        </div>
                    </div>
                </div>
                <button class="checkout__button checkout__button-payment">ALL DONE</button>
                <!--    </form>-->
            </div>
            <div class="checkout__button checkout__button-paypal">
                <img src="<?php echo get_template_directory_uri() ?>/assets/img/stack/sprite.svg#paypal-big" alt="">
                <?php
//                echo kama_thumb_img([
//                    'width' => 120,
//                    'height'=> 50,
//                    'src'   => get_template_directory_uri() . '/assets/img/stack/sprite.svg#paypal-big'
//                ]);
                ?>
            </div>
            <div class="checkout__payment-bottom is-only-desktop">
                <div class="checkout__bottom">By clicking Pay Now, you agree to our
                    <a href="/terms-of-service/">Terms of Service</a>
                    and
                    <a href="/privacy-policy-2/">Privacy Policy</a>.
                </div>
            </div>

            <div class="form-row place-order">
                <noscript>
                    <?php
                    /* translators: $1 and $2 opening and closing emphasis tags respectively */
                    printf(esc_html__('Since your browser does not support JavaScript, or it is disabled, please ensure you click the %1$sUpdate Totals%2$s button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce'), '<em>', '</em>');
                    ?>
                    <br/>
                    <button type="submit" class="button alt" name="woocommerce_checkout_update_totals"
                            value="<?php esc_attr_e('Update totals', 'woocommerce'); ?>"><?php esc_html_e('Update totals', 'woocommerce'); ?></button>
                </noscript>

                <?php /*wc_get_template('checkout/terms.php');*/ ?>

                <?php do_action('woocommerce_review_order_before_submit'); ?>
                <?php echo apply_filters('woocommerce_order_button_html', '<button type="submit" class="checkout__button checkout__step-3" name="woocommerce_checkout_place_order" id="place_order" value="Pay" data-value="Pay">Pay now</button>'); // @codingStandardsIgnoreLine
                ?>

                <?php do_action('woocommerce_review_order_after_submit'); ?>

                <?php wp_nonce_field('woocommerce-process_checkout', 'woocommerce-process-checkout-nonce'); ?>
            </div>
            <!--            <div class="checkout__button checkout__step-3">PAY NOW</div>-->
        </div>

        <div class="checkout__inputs checkout__step-four">
            <style>
                .windows8 {
                    position: relative;
                    width: 78px;
                    height:78px;
                    margin:auto;
                }

                .windows8 .wBall {
                    position: absolute;
                    width: 74px;
                    height: 74px;
                    opacity: 0;
                    transform: rotate(225deg);
                    -o-transform: rotate(225deg);
                    -ms-transform: rotate(225deg);
                    -webkit-transform: rotate(225deg);
                    -moz-transform: rotate(225deg);
                    animation: orbit 6.96s infinite;
                    -o-animation: orbit 6.96s infinite;
                    -ms-animation: orbit 6.96s infinite;
                    -webkit-animation: orbit 6.96s infinite;
                    -moz-animation: orbit 6.96s infinite;
                }

                .windows8 .wBall .wInnerBall{
                    position: absolute;
                    width: 10px;
                    height: 10px;
                    background: rgb(0,0,0);
                    left:0px;
                    top:0px;
                    border-radius: 10px;
                }

                .windows8 #wBall_1 {
                    animation-delay: 1.52s;
                    -o-animation-delay: 1.52s;
                    -ms-animation-delay: 1.52s;
                    -webkit-animation-delay: 1.52s;
                    -moz-animation-delay: 1.52s;
                }

                .windows8 #wBall_2 {
                    animation-delay: 0.3s;
                    -o-animation-delay: 0.3s;
                    -ms-animation-delay: 0.3s;
                    -webkit-animation-delay: 0.3s;
                    -moz-animation-delay: 0.3s;
                }

                .windows8 #wBall_3 {
                    animation-delay: 0.61s;
                    -o-animation-delay: 0.61s;
                    -ms-animation-delay: 0.61s;
                    -webkit-animation-delay: 0.61s;
                    -moz-animation-delay: 0.61s;
                }

                .windows8 #wBall_4 {
                    animation-delay: 0.91s;
                    -o-animation-delay: 0.91s;
                    -ms-animation-delay: 0.91s;
                    -webkit-animation-delay: 0.91s;
                    -moz-animation-delay: 0.91s;
                }

                .windows8 #wBall_5 {
                    animation-delay: 1.22s;
                    -o-animation-delay: 1.22s;
                    -ms-animation-delay: 1.22s;
                    -webkit-animation-delay: 1.22s;
                    -moz-animation-delay: 1.22s;
                }



                @keyframes orbit {
                    0% {
                        opacity: 1;
                        z-index:99;
                        transform: rotate(180deg);
                        animation-timing-function: ease-out;
                    }

                    7% {
                        opacity: 1;
                        transform: rotate(300deg);
                        animation-timing-function: linear;
                        origin:0%;
                    }

                    30% {
                        opacity: 1;
                        transform:rotate(410deg);
                        animation-timing-function: ease-in-out;
                        origin:7%;
                    }

                    39% {
                        opacity: 1;
                        transform: rotate(645deg);
                        animation-timing-function: linear;
                        origin:30%;
                    }

                    70% {
                        opacity: 1;
                        transform: rotate(770deg);
                        animation-timing-function: ease-out;
                        origin:39%;
                    }

                    75% {
                        opacity: 1;
                        transform: rotate(900deg);
                        animation-timing-function: ease-out;
                        origin:70%;
                    }

                    76% {
                        opacity: 0;
                        transform:rotate(900deg);
                    }

                    100% {
                        opacity: 0;
                        transform: rotate(900deg);
                    }
                }

                @-o-keyframes orbit {
                    0% {
                        opacity: 1;
                        z-index:99;
                        -o-transform: rotate(180deg);
                        -o-animation-timing-function: ease-out;
                    }

                    7% {
                        opacity: 1;
                        -o-transform: rotate(300deg);
                        -o-animation-timing-function: linear;
                        -o-origin:0%;
                    }

                    30% {
                        opacity: 1;
                        -o-transform:rotate(410deg);
                        -o-animation-timing-function: ease-in-out;
                        -o-origin:7%;
                    }

                    39% {
                        opacity: 1;
                        -o-transform: rotate(645deg);
                        -o-animation-timing-function: linear;
                        -o-origin:30%;
                    }

                    70% {
                        opacity: 1;
                        -o-transform: rotate(770deg);
                        -o-animation-timing-function: ease-out;
                        -o-origin:39%;
                    }

                    75% {
                        opacity: 1;
                        -o-transform: rotate(900deg);
                        -o-animation-timing-function: ease-out;
                        -o-origin:70%;
                    }

                    76% {
                        opacity: 0;
                        -o-transform:rotate(900deg);
                    }

                    100% {
                        opacity: 0;
                        -o-transform: rotate(900deg);
                    }
                }

                @-ms-keyframes orbit {
                    0% {
                        opacity: 1;
                        z-index:99;
                        -ms-transform: rotate(180deg);
                        -ms-animation-timing-function: ease-out;
                    }

                    7% {
                        opacity: 1;
                        -ms-transform: rotate(300deg);
                        -ms-animation-timing-function: linear;
                        -ms-origin:0%;
                    }

                    30% {
                        opacity: 1;
                        -ms-transform:rotate(410deg);
                        -ms-animation-timing-function: ease-in-out;
                        -ms-origin:7%;
                    }

                    39% {
                        opacity: 1;
                        -ms-transform: rotate(645deg);
                        -ms-animation-timing-function: linear;
                        -ms-origin:30%;
                    }

                    70% {
                        opacity: 1;
                        -ms-transform: rotate(770deg);
                        -ms-animation-timing-function: ease-out;
                        -ms-origin:39%;
                    }

                    75% {
                        opacity: 1;
                        -ms-transform: rotate(900deg);
                        -ms-animation-timing-function: ease-out;
                        -ms-origin:70%;
                    }

                    76% {
                        opacity: 0;
                        -ms-transform:rotate(900deg);
                    }

                    100% {
                        opacity: 0;
                        -ms-transform: rotate(900deg);
                    }
                }

                @-webkit-keyframes orbit {
                    0% {
                        opacity: 1;
                        z-index:99;
                        -webkit-transform: rotate(180deg);
                        -webkit-animation-timing-function: ease-out;
                    }

                    7% {
                        opacity: 1;
                        -webkit-transform: rotate(300deg);
                        -webkit-animation-timing-function: linear;
                        -webkit-origin:0%;
                    }

                    30% {
                        opacity: 1;
                        -webkit-transform:rotate(410deg);
                        -webkit-animation-timing-function: ease-in-out;
                        -webkit-origin:7%;
                    }

                    39% {
                        opacity: 1;
                        -webkit-transform: rotate(645deg);
                        -webkit-animation-timing-function: linear;
                        -webkit-origin:30%;
                    }

                    70% {
                        opacity: 1;
                        -webkit-transform: rotate(770deg);
                        -webkit-animation-timing-function: ease-out;
                        -webkit-origin:39%;
                    }

                    75% {
                        opacity: 1;
                        -webkit-transform: rotate(900deg);
                        -webkit-animation-timing-function: ease-out;
                        -webkit-origin:70%;
                    }

                    76% {
                        opacity: 0;
                        -webkit-transform:rotate(900deg);
                    }

                    100% {
                        opacity: 0;
                        -webkit-transform: rotate(900deg);
                    }
                }

                @-moz-keyframes orbit {
                    0% {
                        opacity: 1;
                        z-index:99;
                        -moz-transform: rotate(180deg);
                        -moz-animation-timing-function: ease-out;
                    }

                    7% {
                        opacity: 1;
                        -moz-transform: rotate(300deg);
                        -moz-animation-timing-function: linear;
                        -moz-origin:0%;
                    }

                    30% {
                        opacity: 1;
                        -moz-transform:rotate(410deg);
                        -moz-animation-timing-function: ease-in-out;
                        -moz-origin:7%;
                    }

                    39% {
                        opacity: 1;
                        -moz-transform: rotate(645deg);
                        -moz-animation-timing-function: linear;
                        -moz-origin:30%;
                    }

                    70% {
                        opacity: 1;
                        -moz-transform: rotate(770deg);
                        -moz-animation-timing-function: ease-out;
                        -moz-origin:39%;
                    }

                    75% {
                        opacity: 1;
                        -moz-transform: rotate(900deg);
                        -moz-animation-timing-function: ease-out;
                        -moz-origin:70%;
                    }

                    76% {
                        opacity: 0;
                        -moz-transform:rotate(900deg);
                    }

                    100% {
                        opacity: 0;
                        -moz-transform: rotate(900deg);
                    }
                }
            </style>
            <div class="windows8">
                <div class="wBall" id="wBall_1">
                    <div class="wInnerBall"></div>
                </div>
                <div class="wBall" id="wBall_2">
                    <div class="wInnerBall"></div>
                </div>
                <div class="wBall" id="wBall_3">
                    <div class="wInnerBall"></div>
                </div>
                <div class="wBall" id="wBall_4">
                    <div class="wInnerBall"></div>
                </div>
                <div class="wBall" id="wBall_5">
                    <div class="wInnerBall"></div>
                </div>
            </div>
        </div>
        <div class="cart__box-label">
            <?php
            $products = WC()->cart->get_cart();
            $one = true;
            $customer_id = WC()->session->get_customer_id();
            foreach ($products as $product_key => $product):
                $_product = apply_filters('woocommerce_cart_item_product', $product['data'], $product, $product_key);
                ?>
                <div class="cart__wrap">
                    <?php if ($one): ?>
                        <div class="cart__label-wrap">
                            <p class="cart__label">Order</p>
                            <p class="cart__label">Qty</p>
                            <p class="cart__label">Price</p>
                        </div>
                    <?php endif; ?>
                    <?php if ($one) {
                        $one = false;
                    } ?>
                    <ul class="cart__list">
                        <li>
                            <div class="cart__item">
                                <img class="cart__item-img" src="<?/*= wp_get_attachment_image_url($_product->get_image_id(), 'full', '$icon') */?>">
                                <?php
//                                echo kama_thumb_img([
//                                    'width' => 50,
//                                    'height'=> 40,
//                                    'class' => 'cart__item-img',
//                                    'src'   => wp_get_attachment_image_url($_product->get_image_id(), 'full', '$icon'),
//                                ]);
                                ?>
                                <div class="cart__item-wrap">
                                    <div class="cart__item-box indent-bottom">
                                        <div class="cart__item-text cart__item-title"><?= $_product->get_name() ?></div>
                                        <div class="cart__item-text cart__item-count"><?= $product['quantity'] ?></div>
                                        <div class="cart__item-text cart__item-price"><?= WC()->cart->get_product_subtotal($_product, $product['quantity']) ?></div>
                                    </div>
                                    <?php
                                    $attributes = $_product->get_attributes();
                                    foreach ($attributes as $attribute => $value):
                                        if (!empty($_product->get_default_attributes()[$attribute]) || !is_object($value)):
                                        ?>
                                        <div class="cart__item-box">
                                            <div class="cart__item-parameters"><?= implode(' ', array_map('ucfirst', explode('-', substr($attribute, 3)))) ?>
                                                :

                                                <?php if (isset($product['custom_size']) && $attribute == 'pa_set-size'): ?>
                                                    <?= $product['custom_size'] ?>
                                                <?php else: ?>
                                                    <?= !is_object($value) ? $value : $_product->get_default_attributes()[$attribute]; ?>
                                                <?php endif; ?>

                                            </div>
                                        </div>
                                            <?php elseif (is_object($value)): ?>

                                            <?php
                                            $attribute_name = $value->get_taxonomy(); // The taxonomy slug name
                                            $attribute_terms = $value->get_terms(); // The terms
                                            $attribute_slugs = $value->get_slugs(); // The term slugs
                                            //
                                            echo '<div class="cart__item-box">';
                                            echo '<div class="cart__item-parameters">'.implode(' ', array_map('ucfirst', explode('-', substr($value->get_name(), 3)))).': '.$attribute_slugs[0].'</div>';
                                            echo '</div>';
                                            ?>

                                        <?php endif ?>
                                    <?php endforeach; ?>
                                    <div class="cart__file-list">
                                    <?php
                                    $customer_folder = WC()->session->get( 'customer_folder' );
                                    $dir = $_SERVER['DOCUMENT_ROOT'] . '/user_print/'.$customer_folder.'/';
                                    $dh = opendir($dir);

                                    if (file_exists($dir)) :
                                    while (false !== ($filename = readdir($dh))):
                                        if (isset($customer_folder)):
                                            if ($filename !== '.' && $filename !== '..'):
                                                $str = explode('_', $filename);
                                                $name = $str[array_key_last($str)];
                                            ?>

                                                <div class="cart__file-item">
                                                    <img class="cart__file-img" src="<?php
                                                    $img = explode('.', $filename);
                                                    echo get_template_directory_uri() . '/assets/img/icons/' . end($img) . '.svg';
                                                    ?>">
                                                    <span class="cart__file-title"><?= $name ?></span>
                                                    <div class="cart__file-buttons">
                                                        <!--<div class="cart__file-btn-edit">
                        <svg class="icon ">
                          <use
                            xlink:href="<?php echo get_template_directory_uri() ?>/assets/img/stack/sprite.svg#edit-2">
                          </use>
                        </svg>
                      </div>-->
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
                                        endif;
                                    endwhile;
                                    endif;
                                    ?>
                                    </div>
                                </div>
                            </div>
                            <div class="cart__item-button">
                                <?php $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($product) : '', $product, $product_key); ?>
                                <a class="cart__item-edit" href="<?= $product_permalink ?>">
                                    <span>Edit PRODUCT</span>
                                    <div class="user-nav__icon is-only-desktop">
                                        <svg class="icon ">
                                            <use
                                                    xlink:href="<?php echo get_template_directory_uri() ?>/assets/img/stack/sprite.svg#pencil">
                                            </use>
                                        </svg>
                                    </div>
                                </a>
                                <a class="cart__item-delete" href="<?= wc_get_cart_remove_url($product_key) ?>">
                                    <span>DELETE</span>
                                    <div class="user-nav__icon is-only-desktop">
                                        <svg class="icon ">
                                            <use
                                                    xlink:href="<?php echo get_template_directory_uri() ?>/assets/img/stack/sprite.svg#trash">
                                            </use>
                                        </svg>
                                    </div>
                                </a>
                            </div>
                        </li>

                    </ul>
                </div>
            <?php endforeach; ?>
            <div class="total-checkout is-desktop">
                <div class="total-checkout__header">
                    <h2 class="total-checkout__title">CHECK THE CART</h2>
                    <p class="total-checkout__quantity"><?= WC()->cart->get_cart_contents_count() ?> items</p>
                    <div class="user-nav__icon">
                        <svg class="icon ">
                            <use
                                    xlink:href="<?php echo get_template_directory_uri() ?>/assets/img/stack/sprite.svg#shopping-cart">
                            </use>
                        </svg>
                    </div>
                </div>
                <div class="total-checkout__box">
                    <input class="total-checkout__discount"
                           placeholder="Discount Code"<?php if (isset(WC()->cart->applied_coupons[0])): ?> value="<?= WC()->cart->applied_coupons[0]; ?>"<?php endif ?>>
                    <button class="total-checkout__apply">APPLY</button>
                </div>
                <div class="total__customer">
                    <div class="total__customer-text">
                        Special instructions for seller
                    </div>
                    <textarea class="total__customer-textarea" name="checkout-customer-note" rows="10"></textarea>
                </div>
                <div class="total-checkout__wrap">
                    <div><span class="total-checkout__subtotal">Subtotal</span><span
                                class="total-checkout__price-small"><?= '$' . WC()->cart->get_subtotal() ?></span></div>
                    <div style="display: none"><span class="total-checkout__subtotal shipping_price_right">Shipping</span><span
                      class="total-checkout__price-small">$0</span></div>
                    <div style="display: none" class="tax_cart">
                        <div>
                            <span class="total-checkout__subtotal">Tax </span>
                            <span class="total-checkout__price-small">0$</span>
                        </div>
                    </div>
                    <!--<div><span class="total-checkout__subtotal">Handling Charges</span><span
                      class="total-checkout__price-small">$0</span></div>-->
                </div>
                <div class="total-checkout__grand" style="display: none">
                    <span class="total-checkout__grand-text">GRAND TOTAL</span>
                    <span class="total-checkout__grand-price"><?= WC()->cart->get_total() ?></span>
                </div>


            </div>
        </div>
    </div>

    <div class="total-checkout is-mobile">
        <div class="total-checkout__header">
            <h2 class="total-checkout__title">CHECK THE CART</h2>
            <p class="total-checkout__quantity"><?= WC()->cart->get_cart_contents_count() ?> items</p>
            <div class="user-nav__icon">
                <svg class="icon ">
                    <use
                            xlink:href="<?php echo get_template_directory_uri() ?>/assets/img/stack/sprite.svg#shopping-cart">
                    </use>
                </svg>
            </div>
        </div>
        <div class="total-checkout__box">
            <input class="total-checkout__discount" placeholder="Discount Code">
            <button class="total-checkout__apply">APPLY</button>
        </div>
        <div class="total-checkout__wrap">
            <div><span class="total-checkout__subtotal">Subtotal</span><span
                        class="total-checkout__price-small"><?= '$' . WC()->cart->get_subtotal() ?></span></div>
            <div style="display: none">
                <span class="total-checkout__subtotal shipping_price_right">Shipping</span><span
                        class="total-checkout__price-small">8,24$</span>
            </div>
            <div style="display: none" class="tax_cart">
                <div>
                    <span class="total-checkout__subtotal">Tax </span>
                    <span class="total-checkout__price-small">0$</span>
                </div>
            </div>
        </div>
        <div class="total-checkout__grand" style="display: none">
            <span class="total-checkout__grand-text">GRAND TOTAL</span>
            <span class="total-checkout__grand-price"><?= WC()->cart->get_total() ?></span>
        </div>
        <div class="total__customer-mobile">
            <div class="total__customer-text">
                Special instructions for seller
            </div>
            <textarea class="total__customer-textarea" name="checkout-mobile-customer-note" rows="10"></textarea>
        </div>
    </div>

    </div>
<?php
endif;
get_footer();
?>