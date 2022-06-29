<?php

/**
 * Connection checker.
 *
 * @package WPDesk\UpsShippingService\UpsApi
 */
namespace UpsFreeVendor\WPDesk\UpsShippingService\UpsApi;

use Psr\Log\LoggerInterface;
use UpsFreeVendor\Ups\Entity\Address;
use UpsFreeVendor\Ups\SimpleAddressValidation;
use UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsValues;
use UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsValuesAsArray;
use UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition;
use UpsFreeVendor\WPDesk\UpsShippingService\UpsShippingService;
/**
 * Can check connection.
 */
class ConnectionChecker
{
    /**
     * Shipping service.
     *
     * @var UpsShippingService
     */
    private $shipping_service;
    /**
     * Settings.
     *
     * @var SettingsValuesAsArray
     */
    private $settings;
    /**
     * Logger.
     *
     * @var LoggerInterface
     */
    private $logger;
    /**
     * ConnectionChecker constructor.
     *
     * @param UpsShippingService $shipping_service .
     * @param SettingsValues     $settings .
     * @param LoggerInterface    $logger .
     */
    public function __construct(\UpsFreeVendor\WPDesk\UpsShippingService\UpsShippingService $shipping_service, \UpsFreeVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings, $logger)
    {
        $this->shipping_service = $shipping_service;
        $this->settings = $settings;
        $this->logger = $logger;
    }
    /**
     * Pings API.
     *
     * @throws \Exception .
     */
    public function check_connection()
    {
        $address = new \UpsFreeVendor\Ups\Entity\Address();
        $address->setStateProvinceCode('NY');
        $address->setCity('New York');
        $address->setCountryCode('US');
        $address->setPostalCode('10000');
        $av = new \UpsFreeVendor\Ups\SimpleAddressValidation($this->settings->get_value(\UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::ACCESS_KEY), $this->settings->get_value(\UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::USER_ID), $this->settings->get_value(\UpsFreeVendor\WPDesk\UpsShippingService\UpsSettingsDefinition::PASSWORD), $this->shipping_service->is_testing($this->settings), null, $this->logger);
        $response = $av->validate($address);
    }
}
