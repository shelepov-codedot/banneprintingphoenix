<?php
/**
 * Output a single payment method
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment-method.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="checkout__payment-item wc_payment_method payment_method_<?php echo esc_attr( $gateway->id ); ?><?= (esc_attr($gateway->id) == 'paypal') ? ' is-tablet"':'" style="width: 100%;"' ?>">
    <div class="checkout__payment-header">
        <input id="payment_method_<?php echo esc_attr( $gateway->id ); ?>" type="radio" class="total__point is-tablet" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />

        <label for="payment_method_<?php echo esc_attr( $gateway->id ); ?>" class="checkout__payment-text">
            <?php echo $gateway->get_title(); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?> <?php /*echo $gateway->get_icon(); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?>
        </label>
    </div>
    <?php $gateway->payment_fields(); ?>
</div>
