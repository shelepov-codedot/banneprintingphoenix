<?php

namespace UpsFreeVendor;

/**
 * Template for delivary date.
 *
 * @package WPDesk\WooCommerceShipping\OrderMetaData;
 *
 * @var int $delivery_date
 */
?>
<div class="ups-delivery-time">
	<?php 
// Translators: delivery date.
echo \sprintf(\__('(Delivery Date: %1$s)', 'flexible-shipping-ups'), $delivery_date);
// WPCS: XSS ok.
?>
</div>
<?php 
