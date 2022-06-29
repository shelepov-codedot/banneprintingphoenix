<?php

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('WC_Email_Custom_Customer_Invoice')) :

    class WC_Email_Custom_Customer_Invoice extends WC_Email
    {

        public function __construct()
        {
            $this->id = 'admin_customer_custom_invoice';
            $this->title = __('Customer custom invoice / Order details', 'woocommerce');
            $this->description = __('Customer custom invoice emails can be sent to customers containing their order information and payment links.', 'woocommerce');
            $this->template_html = 'emails/admin-customer-custom-invoice.php';
            $this->placeholders = array(
                '{order_date}' => '',
                '{order_number}' => '',
            );

            // Call parent constructor.
            parent::__construct();
        }

        public function get_default_heading($paid = false)
        {
            if ($paid) {
                return __('Invoice for order #test', 'woocommerce');
            } else {
                return __('Your invoice for order #test', 'woocommerce');
            }
        }

        public function get_default_subject($paid = false)
        {
            if ($paid) {
                return __('Invoice for order #test on bannerprintingsanfrancisco.com', 'woocommerce');
            } else {
                return __('Your latest bannerprintingsanfrancisco.com invoice', 'woocommerce');
            }
        }


        public function trigger($data, $url_order)
        {
            $this->setup_locale();

            $this->object = $data;
            $this->description = $url_order;

            $this->send('contact@bannerprintingsanfrancisco.com', $this->get_default_subject(), $this->get_content_html(), $this->get_default_heading(), ['123' => '123']);

            $this->restore_locale();
        }

        public function get_content_html()
        {
            return wc_get_template_html(
                $this->template_html,
                array(
                    'order' => $this->object,
                    'order_href' => $this->description
                )
            );
        }
    }
endif;

return new WC_Email_Custom_Customer_Invoice();
