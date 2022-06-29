<?php

/**
 * Tracker
 *
 * @package WPDesk\WooCommerceShipping\Ups
 */
namespace UpsFreeVendor\WPDesk\WooCommerceShipping\Ups;

use WC_Shipping_Method;
use WC_Shipping_Zone;
use WC_Shipping_Zones;
use WP_Screen;
use UpsFreeVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition;
use UpsFreeVendor\WPDesk\WooCommerceShipping\FreeShipping\FreeShippingFields;
/**
 * Handles tracker actions.
 */
class Tracker implements \UpsFreeVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    const OPTION_VALUE_NO = 'no';
    const OPTION_VALUE_YES = 'yes';
    /**
     * Should add actions links?
     *
     * @var bool
     */
    private $add_action_links;
    /**
     * Tracker constructor.
     *
     * @param bool $add_action_links .
     */
    public function __construct($add_action_links = \false)
    {
        $this->add_action_links = $add_action_links;
    }
    /**
     * Hooks.
     */
    public function hooks()
    {
        \add_filter('wpdesk_tracker_data', array($this, 'wpdesk_tracker_data_ups'), 11);
        \add_filter('wpdesk_tracker_notice_screens', array($this, 'wpdesk_tracker_notice_screens'));
        if ($this->add_action_links) {
            \add_filter('plugin_action_links_flexible-shipping-ups/flexible-shipping-ups.php', array($this, 'plugin_action_links'), 9);
        }
    }
    /**
     * Prepare default plugin data.
     *
     * @param UpsShippingMethod $flexible_shipping_ups Shipping method.
     *
     * @return array
     */
    private function prepare_default_plugin_data(\UpsFreeVendor\WPDesk\WooCommerceShipping\Ups\UpsShippingMethod $flexible_shipping_ups)
    {
        if ('yes' === $flexible_shipping_ups->get_option(\UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::CUSTOM_ORIGIN, 'no')) {
            $ups_origin_country = \explode(':', $flexible_shipping_ups->get_option(\UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::ORIGIN_COUNTRY, ''));
        } else {
            $ups_origin_country = \explode(':', \get_option('woocommerce_default_country', ''));
        }
        if (!empty($ups_origin_country[0])) {
            $origin_country = $ups_origin_country[0];
        } else {
            $origin_country = 'not set';
        }
        $plugin_data = array('custom_origin' => $flexible_shipping_ups->get_option(\UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::CUSTOM_ORIGIN, self::OPTION_VALUE_NO), 'shipping_methods' => 0, 'custom_services' => 0, 'negotiated_rates' => 0, 'insurance_option' => 0, 'fallback' => 0, 'free_shipping' => 0, 'access_point' => 0, 'access_point_only' => 0, 'origin_country' => $origin_country, 'shipping_zones' => array(), 'ups_services' => array());
        return $plugin_data;
    }
    /**
     * Append data for shipping method.
     *
     * @param array             $plugin_data     Plugin data.
     * @param WC_Shipping_Zone  $zone            Shipping zone.
     * @param UpsShippingMethod $shipping_method Shipping method.
     *
     * @return array
     */
    private function append_data_for_shipping_method(array $plugin_data, \WC_Shipping_Zone $zone, \UpsFreeVendor\WPDesk\WooCommerceShipping\Ups\UpsShippingMethod $shipping_method)
    {
        $plugin_data['shipping_zones'][] = $zone->get_zone_name();
        $plugin_data['shipping_methods']++;
        if (self::OPTION_VALUE_YES === $shipping_method->get_instance_option('custom_services', self::OPTION_VALUE_NO)) {
            $plugin_data['custom_services']++;
            $enabled_services = $shipping_method->get_enabled_services();
            foreach ($enabled_services as $enabled_service_code => $enabled_service) {
                if (empty($plugin_data['ups_services'][$enabled_service_code])) {
                    $plugin_data['ups_services'][$enabled_service_code] = 0;
                }
                $plugin_data['ups_services'][$enabled_service_code]++;
            }
        }
        if (self::OPTION_VALUE_YES === $shipping_method->get_instance_option(\UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::NEGOTIATED_RATES, self::OPTION_VALUE_NO)) {
            $plugin_data['negotiated_rates']++;
        }
        if (self::OPTION_VALUE_YES === $shipping_method->get_instance_option(\UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::INSURANCE, self::OPTION_VALUE_NO)) {
            $plugin_data['insurance_option']++;
        }
        if (self::OPTION_VALUE_YES === $shipping_method->get_instance_option(\UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::FALLBACK, self::OPTION_VALUE_NO)) {
            $plugin_data['fallback']++;
        }
        $access_point_option = $shipping_method->get_instance_option(\UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::ACCESS_POINT, \UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::DO_NOT_ADD_ACCESS_POINTS_TO_RATES);
        if (\UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::ADD_ACCESS_POINTS_TO_RATES === $access_point_option) {
            $plugin_data['access_point']++;
        }
        if (\UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::ADD_ONLY_ACCESS_POINTS_TO_RATES === $access_point_option) {
            $plugin_data['access_point_only']++;
        }
        //Count free shipping uses.
        if (self::OPTION_VALUE_YES === $shipping_method->get_instance_option(\UpsFreeVendor\WPDesk\WooCommerceShipping\FreeShipping\FreeShippingFields::FIELD_STATUS, self::OPTION_VALUE_NO)) {
            $plugin_data['free_shipping']++;
        }
        return $plugin_data;
    }
    /**
     * Add plugin data tracker.
     *
     * @param array $data Data.
     *
     * @return array
     */
    public function wpdesk_tracker_data_ups(array $data)
    {
        $shipping_methods = \WC()->shipping()->get_shipping_methods();
        if (isset($shipping_methods['flexible_shipping_ups'])) {
            /**
             * IDE type hint.
             *
             * @var UpsShippingMethod $flexible_shipping_ups
             */
            $flexible_shipping_ups = $shipping_methods['flexible_shipping_ups'];
            $plugin_data = $this->prepare_default_plugin_data($flexible_shipping_ups);
            $shipping_zones = \WC_Shipping_Zones::get_zones();
            $shipping_zones[0] = array('zone_id' => 0);
            /**
             * IDE type hint.
             *
             * @var WC_Shipping_Zone $zone_data
             */
            foreach ($shipping_zones as $zone_data) {
                $zone = new \WC_Shipping_Zone($zone_data['zone_id']);
                $shipping_methods = $zone->get_shipping_methods(\true);
                /**
                 * IDE type hint.
                 *
                 * @var WC_Shipping_Method $shipping_method
                 */
                foreach ($shipping_methods as $shipping_method) {
                    if ('flexible_shipping_ups' === $shipping_method->id && $shipping_method instanceof \UpsFreeVendor\WPDesk\WooCommerceShipping\Ups\UpsShippingMethod) {
                        $plugin_data = $this->append_data_for_shipping_method($plugin_data, $zone, $shipping_method);
                    }
                }
            }
            $data['flexible_shipping_ups'] = $plugin_data;
        }
        return $data;
    }
    /**
     * Add UPS settings to tracker screens.
     *
     * @param array $screens .
     *
     * @return array
     */
    public function wpdesk_tracker_notice_screens($screens)
    {
        $current_screen = \get_current_screen();
        if ($current_screen instanceof \WP_Screen) {
            if ('woocommerce_page_wc-settings' === $current_screen->id) {
                if (isset($_GET['tab']) && 'shipping' === $_GET['tab'] && isset($_GET['section']) && 'flexible_shipping_ups' === $_GET['section']) {
                    // WPCS: Input var okay. CSRF ok.
                    $screens[] = $current_screen->id;
                }
            }
        }
        return $screens;
    }
    /**
     * Opt in/opt out action links.
     *
     * @param array $links .
     *
     * @return array
     */
    public function plugin_action_links($links)
    {
        if (!$this->is_tracker_enabled() || \apply_filters('wpdesk_tracker_do_not_ask', \false)) {
            return $links;
        }
        $options = \get_option('wpdesk_helper_options', array());
        if (!\is_array($options)) {
            $options = array();
        }
        if (empty($options['wpdesk_tracker_agree'])) {
            $options['wpdesk_tracker_agree'] = '0';
        }
        $plugin_links = array();
        if (0 === \intval($options['wpdesk_tracker_agree'])) {
            $opt_in_link = \admin_url('admin.php?page=wpdesk_tracker&plugin=flexible-shipping-ups/flexible-shipping-ups.php');
            $plugin_links[] = '<a href="' . $opt_in_link . '">' . \__('Opt-in', 'flexible-shipping-ups') . '</a>';
        } else {
            $opt_in_link = \admin_url('plugins.php?wpdesk_tracker_opt_out=1&plugin=flexible-shipping-ups/flexible-shipping-ups.php');
            $plugin_links[] = '<a href="' . $opt_in_link . '">' . \__('Opt-out', 'flexible-shipping-ups') . '</a>';
        }
        return \array_merge($plugin_links, $links);
    }
    /**
     * Is WPDesk Tracker enabled?
     *
     * @return bool
     */
    private function is_tracker_enabled()
    {
        $tracker_enabled = \true;
        if (!empty($_SERVER['SERVER_ADDR']) && '127.0.0.1' === $_SERVER['SERVER_ADDR']) {
            // WPCS: Input var okay.
            $tracker_enabled = \false;
        }
        return \apply_filters('wpdesk_tracker_enabled', $tracker_enabled);
        // add_filter( 'wpdesk_tracker_enabled', '__return_true' ); // WPCS: ignore.
        // add_filter( 'wpdesk_tracker_do_not_ask', '__return_true' ); // WPCS: ignore.
    }
}
