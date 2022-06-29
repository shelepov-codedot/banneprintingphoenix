<?php

/**
 * UPS implementation: UpsSettingsDefinition class.
 *
 * @package WPDesk\UpsShippingService
 */
namespace UpsFreeVendor\WPDesk\UpsShippingService;

use UpsFreeVendor\Ups\Entity\PickupType;
use UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsDefinition;
use UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsValues;
use UpsFreeVendor\WPDesk\WooCommerceShipping\FreeShipping\FreeShippingFields;
use UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingMethod\RateMethod\Fallback\FallbackRateMethod;
use UpsFreeVendor\WPDesk\WooCommerceShipping\ShopSettings;
use UpsFreeVendor\WPDesk\WooCommerceShipping\WooCommerceNotInitializedException;
/**
 * A class that defines the basic settings for the shipping method.
 *
 * @package WPDesk\UpsShippingService
 */
class UpsSettingsDefinition extends \UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsDefinition
{
    const CUSTOM_SERVICES_CHECKBOX_CLASS = 'wpdesk_wc_shipping_custom_service_checkbox';
    /**
     * Services table field name.
     */
    const FIELD_SERVICES_TABLE = 'services';
    /**
     * Enable custom services checkbox name.
     */
    const SHIPPING_METHOD_TITLE = 'shipping_method_title';
    const API_SETTINGS_TITLE = 'api_settings_title';
    const USER_ID = 'user_id';
    const PASSWORD = 'password';
    const ACCESS_KEY = 'access_key';
    const ACCOUNT_NUMBER = 'account_number';
    const TESTING = 'testing';
    const ORIGIN_SETTINGS_TITLE = 'origin_settings_title';
    const CUSTOM_ORIGIN = 'custom_origin';
    const ORIGIN_ADDRESS = 'origin_address';
    const ORIGIN_CITY = 'origin_city';
    const ORIGIN_POSTCODE = 'origin_postcode';
    const ORIGIN_COUNTRY = 'origin_country';
    const ADVANCED_OPTIONS_TITLE = 'advanced_options_title';
    const UNITS = 'units';
    const DEBUG_MODE = 'debug_mode';
    const API_STATUS = 'api_status';
    const METHOD_SETTINGS_TITLE = 'method_settings_title';
    const TITLE = 'title';
    const ACCESS_POINT = 'access_point';
    const FALLBACK = 'fallback';
    const CUSTOM_SERVICES = 'custom_services';
    const SERVICES = 'services';
    const RATE_ADJUSTMENTS_TITLE = 'rate_adjustments_title';
    const NEGOTIATED_RATES = 'negotiated_rates';
    const INSURANCE = 'insurance';
    const PICKUP_TYPE = 'pickup_type';
    const FREE_SHIPPING = 'free_shipping';
    /**
     * Default field values.
     */
    const DO_NOT_ADD_ACCESS_POINTS_TO_RATES = 'no';
    const ADD_ACCESS_POINTS_TO_RATES = 'yes';
    const ADD_ONLY_ACCESS_POINTS_TO_RATES = 'only';
    const UNITS_IMPERIAL = 'imperial';
    const UNITS_METRIC = 'metric';
    const NOT_SET = 'not_set';
    const DEFAULT_PICKUP_TYPE = self::NOT_SET;
    /**
     * Shop settings.
     *
     * @var ShopSettings
     */
    private $shop_settings;
    /**
     * UpsSettingsDefinition constructor.
     *
     * @param ShopSettings $shop_settings Shop settings.
     */
    public function __construct(\UpsFreeVendor\WPDesk\WooCommerceShipping\ShopSettings $shop_settings)
    {
        $this->shop_settings = $shop_settings;
    }
    /**
     * Validate settings.
     *
     * @param SettingsValues $settings Settings.
     *
     * @return bool
     */
    public function validate_settings(\UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings)
    {
        return \true;
    }
    /**
     * Prepare country state options.
     *
     * @return array
     */
    private function prepare_country_state_options()
    {
        try {
            $countries = $this->shop_settings->get_countries();
        } catch (\UpsFreeVendor\WPDesk\WooCommerceShipping\WooCommerceNotInitializedException $e) {
            $countries = array();
        }
        $country_state_options = $countries;
        foreach ($country_state_options as $country_code => $country) {
            $states = $this->shop_settings->get_states($country_code);
            if ($states) {
                unset($country_state_options[$country_code]);
                foreach ($states as $state_code => $state_name) {
                    $country_state_options[$country_code . ':' . $state_code] = $country . ' &mdash; ' . $state_name;
                }
            }
        }
        return $country_state_options;
    }
    /**
     * Get units default.
     *
     * @return string
     */
    private function get_units_default()
    {
        $weight_unit = $this->shop_settings->get_weight_unit();
        if (\in_array($weight_unit, array('g', 'kg'), \true)) {
            return self::UNITS_METRIC;
        }
        return self::UNITS_IMPERIAL;
    }
    /**
     * Initialise Settings Form Fields.
     */
    public function get_form_fields()
    {
        $locale = $this->shop_settings->get_locale();
        $country_state_options = $this->prepare_country_state_options();
        $docs_link = 'pl_PL' === $locale ? 'https://www.wpdesk.pl/docs/ups-woocommerce-docs/' : 'https://docs.flexibleshipping.com/category/122-ups/';
        $docs_link .= '?utm_source=ups&utm_medium=link&utm_campaign=settings-docs-link';
        $connection_fields = array(self::SHIPPING_METHOD_TITLE => array('title' => \__('UPS', 'flexible-shipping-ups'), 'type' => 'title', 'description' => \sprintf(
            // Translators: docs link.
            \__('UPS integrations with live rates and Access Points. Refer to the %1$sinstruction manual →%2$s', 'flexible-shipping-ups'),
            '<a href="' . $docs_link . '" target="_blank">',
            '</a>'
        )), self::API_SETTINGS_TITLE => array(
            'title' => \__('API Settings', 'flexible-shipping-ups'),
            'type' => 'title',
            // Translators: link.
            'description' => \sprintf(\__('You need to obtain UPS account credentials by registering on their %1$swebsite →%2$s', 'flexible-shipping-ups'), '<a href="https://www.ups.com/upsdeveloperkit" target="_blank">', '</a>'),
        ), self::USER_ID => array('title' => \__('UPS User ID', 'flexible-shipping-ups'), 'type' => 'text', 'custom_attributes' => array('required' => 'required'), 'description' => \__('Provide your UPS account details.', 'flexible-shipping-ups'), 'desc_tip' => \true, 'default' => ''), self::PASSWORD => array('title' => \__('UPS Password', 'flexible-shipping-ups'), 'type' => 'password', 'custom_attributes' => array('required' => 'required', 'autocomplete' => 'new-password'), 'description' => \__('Provide your UPS account details.', 'flexible-shipping-ups'), 'desc_tip' => \true, 'default' => ''), self::ACCESS_KEY => array('title' => \__('UPS Access Key', 'flexible-shipping-ups'), 'type' => 'text', 'custom_attributes' => array('required' => 'required'), 'description' => \__('Provide your UPS account details.', 'flexible-shipping-ups'), 'desc_tip' => \true, 'default' => ''), self::ACCOUNT_NUMBER => array('title' => \__('UPS Account Number', 'flexible-shipping-ups'), 'type' => 'text', 'custom_attributes' => array('required' => 'required'), 'description' => \__('Provide your UPS account details.', 'flexible-shipping-ups'), 'desc_tip' => \true, 'default' => ''));
        if ($this->shop_settings->is_testing()) {
            $connection_fields[self::TESTING] = ['title' => \__('Test Credentials', 'fedex-shipping-service'), 'type' => 'checkbox', 'label' => \__('Enable to use test credentials', 'fedex-shipping-service'), 'desc_tip' => \true, 'default' => 'no'];
        }
        $fields = array(self::ORIGIN_SETTINGS_TITLE => array('title' => \__('Origin Settings', 'flexible-shipping-ups'), 'type' => 'title'), self::CUSTOM_ORIGIN => array('title' => \__('Custom Origin', 'flexible-shipping-ups'), 'label' => \__('Enable custom origin', 'flexible-shipping-ups'), 'type' => 'checkbox', 'description' => \__('By default store address data from the WooCommerce settings are used as the origin.', 'flexible-shipping-ups'), 'desc_tip' => \true, 'default' => 'no'), self::ORIGIN_ADDRESS => array('title' => \__('Origin Address', 'flexible-shipping-ups'), 'type' => 'text', 'custom_attributes' => array('required' => 'required'), 'default' => ''), self::ORIGIN_CITY => array('title' => \__('Origin City', 'flexible-shipping-ups'), 'type' => 'text', 'custom_attributes' => array('required' => 'required'), 'default' => ''), self::ORIGIN_POSTCODE => array('title' => \__('Origin Postcode', 'flexible-shipping-ups'), 'type' => 'text', 'custom_attributes' => array('required' => 'required'), 'default' => ''), self::ORIGIN_COUNTRY => array('title' => \__('Origin Country/State', 'flexible-shipping-ups'), 'type' => 'select', 'options' => $country_state_options, 'custom_attributes' => array('required' => 'required'), 'default' => ''), self::ADVANCED_OPTIONS_TITLE => array('title' => \__('Advanced Options', 'flexible-shipping-ups'), 'type' => 'title'), self::UNITS => array('title' => \__('Measurement Units', 'flexible-shipping-ups'), 'type' => 'select', 'options' => array(self::UNITS_IMPERIAL => \__('LBS/IN', 'flexible-shipping-ups'), self::UNITS_METRIC => \__('KG/CM', 'flexible-shipping-ups')), 'description' => \__('By default store settings are used. If you see "This measurement system is not valid for the selected country" errors, switch units. Units in the store settings will be converted to units required by UPS.', 'flexible-shipping-ups'), 'desc_tip' => \true, 'default' => $this->get_units_default()), self::DEBUG_MODE => array('title' => \__('Debug Mode', 'flexible-shipping-ups'), 'label' => \__('Enable debug mode', 'flexible-shipping-ups'), 'type' => 'checkbox', 'description' => \__('Enable debug mode to display messages in the cart/checkout. Admins and shop managers will see all messages and data sent to UPS. The customer will only see messages from the UPS API.', 'flexible-shipping-ups'), 'desc_tip' => \true, 'default' => 'no'));
        $instance_fields = array(self::METHOD_SETTINGS_TITLE => array('title' => \__('Method Settings', 'flexible-shipping-ups'), 'description' => \__('Set how UPS services are displayed.', 'flexible-shipping-ups'), 'type' => 'title'), self::TITLE => array('title' => \__('Method Title', 'flexible-shipping-ups'), 'type' => 'text', 'description' => \__('This controls the title which the user sees during checkout when fallback is used.', 'flexible-shipping-ups'), 'default' => \__('UPS', 'flexible-shipping-ups'), 'desc_tip' => \true), self::ACCESS_POINT => array('title' => \__('Access Points', 'flexible-shipping-ups'), 'label' => \__('Turn on Access Point delivery', 'flexible-shipping-ups'), 'type' => 'select', 'description' => \__('Select an option to display UPS Access Points. The list of points will be available in the shop checkout and you will check the selected point in the order edit.', 'flexible-shipping-ups'), 'default' => self::DO_NOT_ADD_ACCESS_POINTS_TO_RATES, 'options' => array(self::DO_NOT_ADD_ACCESS_POINTS_TO_RATES => \__('Disable access points', 'flexible-shipping-ups'), self::ADD_ACCESS_POINTS_TO_RATES => \__('All services and access points', 'flexible-shipping-ups'), self::ADD_ONLY_ACCESS_POINTS_TO_RATES => \__('Only access points', 'flexible-shipping-ups')), 'desc_tip' => \true), self::FALLBACK => array('type' => \UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingMethod\RateMethod\Fallback\FallbackRateMethod::FIELD_TYPE_FALLBACK, 'default' => ''), self::FREE_SHIPPING => array('title' => \__('Free Shipping', 'flexible-shipping-ups'), 'type' => \UpsFreeVendor\WPDesk\WooCommerceShipping\FreeShipping\FreeShippingFields::FIELD_TYPE_FREE_SHIPPING, 'default' => ''), self::CUSTOM_SERVICES => array('title' => \__('Services', 'flexible-shipping-ups'), 'label' => \__('Enable services custom settings', 'flexible-shipping-ups'), 'type' => 'checkbox', 'description' => \__('Enable if you want to select available services. By enabling a service, it does not guarantee that it will be offered, as the plugin will only offer the available rates based on the package weight, the origin and the destination.', 'flexible-shipping-ups'), 'desc_tip' => \true, 'class' => self::CUSTOM_SERVICES_CHECKBOX_CLASS, 'default' => 'no'), self::SERVICES => array('title' => \__('Services Table', 'flexible-shipping-ups'), 'type' => 'services', 'default' => ''), self::RATE_ADJUSTMENTS_TITLE => array('title' => \__('Rates Adjustments', 'flexible-shipping-ups'), 'description' => \sprintf(\__('Adjust these settings to get more accurate rates. Read %swhat affects the UPS rates in UPS WooCommerce plugin →%s', 'flexible-shipping-ups'), \sprintf('<a href="%s" target="_blank">', \__('https://wpde.sk/ups-free-rates-eng/', 'flexible-shipping-ups')), '</a>'), 'type' => 'title'), self::NEGOTIATED_RATES => array('title' => \__('Negotiated Rates', 'flexible-shipping-ups'), 'label' => \__('Enable negotiated rates', 'flexible-shipping-ups'), 'type' => 'checkbox', 'description' => \__('Enable this option only if your shipping account has negotiated rates available.', 'flexible-shipping-ups'), 'desc_tip' => \true, 'default' => 'no'), self::INSURANCE => array('title' => \__('Insurance', 'flexible-shipping-ups'), 'label' => \__('Request insurance to be included in UPS rates', 'flexible-shipping-ups'), 'type' => 'checkbox', 'description' => \__('Enable if you want to include insurance in UPS rates when it is available.', 'flexible-shipping-ups'), 'desc_tip' => \true, 'default' => 'no'), self::PICKUP_TYPE => array('title' => \__('Pickup Type', 'flexible-shipping-ups'), 'type' => 'select', 'description' => \__('\'Pickup Type\' may affect the live rates. In most cases selecting the \'Customer Counter\' or \'One Time Pickup\' grants the most accurate rates. If the \'Not set\' option has been chosen, the \'Pickup Type\' value will not be sent in the UPS API request.', 'flexible-shipping-ups'), 'desc_tip' => \true, 'default' => self::DEFAULT_PICKUP_TYPE, 'options' => array(self::NOT_SET => \__('Not set', 'flexible-shipping-ups'), \UpsFreeVendor\Ups\Entity\PickupType::PKT_DAILY => \__('Daily Pickup', 'flexible-shipping-ups'), \UpsFreeVendor\Ups\Entity\PickupType::PKT_CUSTOMERCOUNTER => \__('Customer Counter', 'flexible-shipping-ups'), \UpsFreeVendor\Ups\Entity\PickupType::PKT_ONETIME => \__('One Time Pickup', 'flexible-shipping-ups'), \UpsFreeVendor\Ups\Entity\PickupType::PKT_AIR_ONCALL => \__('Air Call', 'flexible-shipping-ups'), \UpsFreeVendor\Ups\Entity\PickupType::PKT_LETTERCENTER => \__('Letter Center', 'flexible-shipping-ups'), \UpsFreeVendor\Ups\Entity\PickupType::PKT_AIR_SERVICECENTER => \__('Air Service Center', 'flexible-shipping-ups'))));
        return $connection_fields + $fields + $instance_fields;
    }
}
