<?php

/**
 * UPS implementation: UpsServices class.
 *
 * @package WPDesk\UpsShippingService
 */
namespace UpsFreeVendor\WPDesk\UpsShippingService;

use UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsDefinition;
use UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsValues;
use UpsFreeVendor\WPDesk\WooCommerceShipping\ShopSettings;
/**
 * A class that defines UPS services.
 *
 * @package WPDesk\UpsShippingService
 */
class UpsServices
{
    /**
     * EU countries.
     *
     * @var array
     */
    private static $eu_countries = array();
    /**
     * Services.
     *
     * @var array
     */
    private static $services = null;
    /**
     * Get services.
     *
     * @return array
     */
    public static function get_services()
    {
        if (empty(self::$services)) {
            self::$services = array('all' => array('96' => \__('UPS Worldwide Express Freight', 'flexible-shipping-ups'), '71' => \__('UPS Worldwide Express Freight Midday', 'flexible-shipping-ups')), 'other' => array('07' => \__('UPS Express', 'flexible-shipping-ups'), '11' => \__('UPS Standard', 'flexible-shipping-ups'), '08' => \__('UPS Worldwide Expedited', 'flexible-shipping-ups'), '54' => \__('UPS Worldwide Express Plus', 'flexible-shipping-ups'), '65' => \__('UPS Worldwide Saver', 'flexible-shipping-ups')), 'PR' => array(
                // Puerto Rico.
                '02' => \__('UPS 2nd Day Air', 'flexible-shipping-ups'),
                '03' => \__('UPS Ground', 'flexible-shipping-ups'),
                '01' => \__('UPS Next Day Air', 'flexible-shipping-ups'),
                '14' => \__('UPS Next Day Air Early', 'flexible-shipping-ups'),
                '08' => \__('UPS Worldwide Expedited', 'flexible-shipping-ups'),
                '07' => \__('UPS Worldwide Express', 'flexible-shipping-ups'),
                '54' => \__('UPS Worldwide Express Plus', 'flexible-shipping-ups'),
                '65' => \__('UPS Worldwide Saver', 'flexible-shipping-ups'),
            ), 'PL' => array(
                // Poland.
                '70' => \__('UPS Access Point Economy', 'flexible-shipping-ups'),
                '83' => \__('UPS Today Dedicated Courrier', 'flexible-shipping-ups'),
                '85' => \__('UPS Today Express', 'flexible-shipping-ups'),
                '86' => \__('UPS Today Express Saver', 'flexible-shipping-ups'),
                '82' => \__('UPS Today Standard', 'flexible-shipping-ups'),
                '08' => \__('UPS Expedited', 'flexible-shipping-ups'),
                '07' => \__('UPS Express', 'flexible-shipping-ups'),
                '54' => \__('UPS Express Plus', 'flexible-shipping-ups'),
                '65' => \__('UPS Express Saver', 'flexible-shipping-ups'),
                '11' => \__('UPS Standard', 'flexible-shipping-ups'),
            ), 'MX' => array(
                // Mexico.
                '70' => \__('UPS Access Point Economy', 'flexible-shipping-ups'),
                '08' => \__('UPS Expedited', 'flexible-shipping-ups'),
                '07' => \__('UPS Express', 'flexible-shipping-ups'),
                '11' => \__('UPS Standard', 'flexible-shipping-ups'),
                '54' => \__('UPS Worldwide Express Plus', 'flexible-shipping-ups'),
                '65' => \__('UPS Worldwide Saver', 'flexible-shipping-ups'),
            ), 'EU' => array(
                // European Union.
                '70' => \__('UPS Access Point Economy', 'flexible-shipping-ups'),
                '08' => \__('UPS Expedited', 'flexible-shipping-ups'),
                '07' => \__('UPS Express', 'flexible-shipping-ups'),
                '11' => \__('UPS Standard', 'flexible-shipping-ups'),
                '54' => \__('UPS Worldwide Express Plus', 'flexible-shipping-ups'),
                '65' => \__('UPS Worldwide Saver', 'flexible-shipping-ups'),
            ), 'CA' => array(
                // Canada.
                '02' => \__('UPS Expedited', 'flexible-shipping-ups'),
                '13' => \__('UPS Express Saver', 'flexible-shipping-ups'),
                '12' => \__('UPS 3 Day Select', 'flexible-shipping-ups'),
                '70' => \__('UPS Access Point Economy', 'flexible-shipping-ups'),
                '01' => \__('UPS Express', 'flexible-shipping-ups'),
                '14' => \__('UPS Express Early', 'flexible-shipping-ups'),
                '65' => \__('UPS Express Saver', 'flexible-shipping-ups'),
                '11' => \__('UPS Standard', 'flexible-shipping-ups'),
                '08' => \__('UPS Worldwide Expedited', 'flexible-shipping-ups'),
                '07' => \__('UPS Worldwide Express', 'flexible-shipping-ups'),
                '54' => \__('UPS Worldwide Express Plus', 'flexible-shipping-ups'),
            ), 'US' => array(
                // USA.
                '11' => \__('UPS Standard', 'flexible-shipping-ups'),
                '07' => \__('UPS Worldwide Express', 'flexible-shipping-ups'),
                '08' => \__('UPS Worldwide Expedited', 'flexible-shipping-ups'),
                '54' => \__('UPS Worldwide Express Plus', 'flexible-shipping-ups'),
                '65' => \__('UPS Worldwide Saver', 'flexible-shipping-ups'),
                '02' => \__('UPS 2nd Day Air', 'flexible-shipping-ups'),
                '59' => \__('UPS 2nd Day Air A.M.', 'flexible-shipping-ups'),
                '12' => \__('UPS 3 Day Select', 'flexible-shipping-ups'),
                '03' => \__('UPS Ground', 'flexible-shipping-ups'),
                '01' => \__('UPS Next Day Air', 'flexible-shipping-ups'),
                '14' => \__('UPS Next Day Air Early', 'flexible-shipping-ups'),
                '13' => \__('UPS Next Day Air Saver', 'flexible-shipping-ups'),
            ));
        }
        return self::$services;
    }
    /**
     * Set EU countries.
     *
     * @param array $eu_countries .
     */
    public static function set_eu_countries(array $eu_countries)
    {
        self::$eu_countries = $eu_countries;
    }
    /**
     * Get services for country.
     *
     * @param string $country_code .
     *
     * @return array
     */
    public static function get_services_for_country($country_code)
    {
        $services = self::get_services();
        $services_for_country = array();
        if (isset($services[$country_code])) {
            $services_for_country = $services[$country_code];
        }
        if ('PL' !== $country_code && \in_array($country_code, self::$eu_countries, \true)) {
            $services_for_country = $services['EU'];
        }
        if (0 === \count($services_for_country)) {
            $services_for_country = $services['other'];
        }
        foreach ($services['all'] as $key => $value) {
            $services_for_country[$key] = $value;
        }
        return $services_for_country;
    }
}
