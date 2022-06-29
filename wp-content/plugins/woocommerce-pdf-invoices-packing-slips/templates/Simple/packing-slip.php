<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php do_action( 'wpo_wcpdf_before_document', $this->get_type(), $this->order ); ?>

    <table class="head container">
        <tr>
            <td class="header">
                <?php do_action( 'wpo_wcpdf_before_shop_name', $this->get_type(), $this->order ); ?>
                <div class="shop-name"><h3><?php $this->shop_name(); ?></h3></div>
            </td>
            <td class="shop-info">
                <div class="shop-info__order-number"><?php _e( 'Order', 'woocommerce-pdf-invoices-packing-slips' ); ?>#<?php $this->order_number(); ?></div>
                <div class="shop-info__order-date"><?php $this->order_date(); ?></div>
            </td>
        </tr>
    </table>

<?php do_action( 'wpo_wcpdf_before_document_label', $this->get_type(), $this->order ); ?>

    <h1 class="document-type-label">
        <?php if ( $this->has_header_logo() ) echo $this->get_title(); ?>
    </h1>

<?php do_action( 'wpo_wcpdf_after_document_label', $this->get_type(), $this->order ); ?>

    <table class="order-data-addresses">
        <tr>
            <td class="address shipping-address">
                <h3><?php _e( 'SHIP TO:', 'woocommerce-pdf-invoices-packing-slips' ); ?></h3>
                <?php do_action( 'wpo_wcpdf_before_shipping_address', $this->get_type(), $this->order ); ?>
                <?php $this->shipping_address(); ?>
                <?php do_action( 'wpo_wcpdf_after_shipping_address', $this->get_type(), $this->order ); ?>
                <?php if ( isset( $this->settings['display_email'] ) ) : ?>
                    <div class="billing-email"><?php $this->billing_email(); ?></div>
                <?php endif; ?>
                <?php if ( isset( $this->settings['display_phone'] ) ) : ?>
                    <div class="shipping-phone"><?php $this->shipping_phone( ! $this->show_billing_address() ); ?></div>
                <?php endif; ?>
            </td>
            <td class="address billing-address">
                <?php if ( $this->show_billing_address() ) : ?>
                    <h3><?php _e( 'BILL TO:', 'woocommerce-pdf-invoices-packing-slips' ); ?></h3>
                    <?php do_action( 'wpo_wcpdf_before_billing_address', $this->get_type(), $this->order ); ?>
                    <?php $this->billing_address(); ?>
                    <?php do_action( 'wpo_wcpdf_after_billing_address', $this->get_type(), $this->order ); ?>
                    <?php if ( isset( $this->settings['display_phone'] ) && ! empty( $this->get_billing_phone() ) ) : ?>
                        <div class="billing-phone"><?php $this->billing_phone(); ?></div>
                    <?php endif; ?>
                <?php endif; ?>
            </td>
        </tr>
    </table>

<?php do_action( 'wpo_wcpdf_before_order_details', $this->get_type(), $this->order ); ?>

    <table class="order-details">
        <thead>
        <tr>
            <th class="product"><?php _e( 'Product', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
            <th class="quantity"><?php _e( 'Quantity', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ( $this->get_order_items() as $item_id => $item ) : ?>
            <tr class="<?php echo apply_filters( 'wpo_wcpdf_item_row_class', 'item-'.$item_id, $this->get_type(), $this->order, $item_id ); ?>">
                <td class="product">
                    <?php $description_label = __( 'Description', 'woocommerce-pdf-invoices-packing-slips' ); // registering alternate label translation ?>
                    <span class="item-name"><?php echo $item['name']; ?></span>
                    <?php do_action( 'wpo_wcpdf_before_item_meta', $this->get_type(), $item, $this->order  ); ?>
                    <span class="item-meta"><?php echo $item['meta']; ?></span>
                    <dl class="meta">
                        <?php $description_label = __( 'SKU', 'woocommerce-pdf-invoices-packing-slips' ); // registering alternate label translation ?>
                        <?php if ( ! empty( $item['sku'] ) ) : ?><dt class="sku"><?php _e( 'SKU:', 'woocommerce-pdf-invoices-packing-slips' ); ?></dt><dd class="sku"><?php echo $item['sku']; ?></dd><?php endif; ?>
                        <?php if ( ! empty( $item['weight'] ) ) : ?><dt class="weight"><?php _e( 'Weight:', 'woocommerce-pdf-invoices-packing-slips' ); ?></dt><dd class="weight"><?php echo $item['weight']; ?><?php echo get_option( 'woocommerce_weight_unit' ); ?></dd><?php endif; ?>
                    </dl>
                    <?php do_action( 'wpo_wcpdf_after_item_meta', $this->get_type(), $item, $this->order  ); ?>
                </td>
                <td class="quantity"><?php echo $item['quantity']; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

<?php do_action( 'wpo_wcpdf_after_order_details', $this->get_type(), $this->order ); ?>

<?php do_action( 'wpo_wcpdf_before_customer_notes', $this->get_type(), $this->order ); ?>

<?php if ( $this->get_shipping_notes() ) : ?>
    <div class="customer-notes">
        <h3><?php _e('Customer Notes', 'woocommerce-pdf-invoices-packing-slips'); ?></h3>
        <?php $this->shipping_notes(); ?>
    </div>
<?php endif; ?>

<?php do_action( 'wpo_wcpdf_after_customer_notes', $this->get_type(), $this->order ); ?>

    <div class="footer">
        <div class="footer-thankyou">Thank you for shopping with us!</div>
        <div class="footer-name">Banner Printing Phoenix</div>
        <div class="footer-address">522 N Central Ave Lbby, Phoenix, AZ 85004</div>
        <div class="footer-email">contact@bannerprintingphoenix.com</div>
        <div class="footer-site">bannerprintingphoenix.com</div>
    </div>

<?php do_action( 'wpo_wcpdf_after_document', $this->get_type(), $this->order ); ?>