<?php


class WC_Email_Customer_Custom_Invoice extends WC_Email
{

    public function __construct() {
        $this->id             = 'customer_custom_invoice';
        $this->customer_email = true;
        $this->title          = __( 'Customer custom invoice / Order details', 'woocommerce' );
        $this->description    = __( 'Customer custom invoice emails can be sent to customers containing their order information and payment links.', 'woocommerce' );
        $this->template_html  = 'emails/customer-custom-invoice.php';
        $this->template_plain = 'emails/plain/customer-custom-invoice.php';
        $this->placeholders   = array(
            '{order_date}'   => '',
            '{order_number}' => '',
        );

        parent::__construct();

        $this->manual = true;
    }

    public function get_default_heading( $paid = false ) {
        if ( $paid ) {
            return __( 'Invoice for order #test', 'woocommerce' );
        } else {
            return __( 'Your invoice for order #test', 'woocommerce' );
        }
    }

    public function get_default_subject( $paid = false ) {
        if ( $paid ) {
            return __( 'Invoice for order #test on bannerprintingphoenix.com', 'woocommerce' );
        } else {
            return __( 'Your latest bannerprintingphoenix.com invoice', 'woocommerce' );
        }
    }

    public function trigger($customer_email, $data, $url_order)
    {
        $this->setup_locale();

        if (isset($customer_email)) {
            $this->object                         = $data;
            $this->recipient                      = $customer_email;
            $this->description                    = $url_order;
        }

        $this->send($this->recipient, $this->get_default_subject(), $this->get_content_html(), $this->get_default_heading(), ['123' => '123']);

        $this->restore_locale();
    }

    public function get_content_html() {
        return wc_get_template_html(
            $this->template_html,
            array(
                'order'              => $this->object,
                'order_href'         => $this->description
            )
        );
    }
}


return new WC_Email_Customer_Custom_Invoice();
