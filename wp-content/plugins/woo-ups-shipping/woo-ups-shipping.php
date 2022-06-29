<?php
/**
 * Plugin Name: UPS Shipping 
 * Plugin URI: https://wordpress.org/plugins/woo-ups-shipping
 * Description: UPS Shipping Method for WooCommerce
 * Version: 2.1.1
 * Author: Mingocommerce
 * Author URI: http://www.mingocommerce.com
 * License: GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Domain Path: /i18n
 * Text Domain: mingocommerce
 */
 
if ( !defined( 'WPINC' ) ) { 
    die;
}
 
/*
 * Check if WooCommerce is active
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
 
    function mingo_ups_shipping_shipping_method_basic() {
		
		// Extra classes 
		class MC_Countries extends WC_Countries{
			
			// Legacy function for 3.1.1
			function get_base_address(){
				if( method_exists( new self(), 'get_base_address' ) ){
					return parent::get_base_address();
				}else{
					return '';
				}
			}
			
			function get_base_address_2(){
				if( method_exists( new self(), 'get_base_address_2' ) ){
					return parent::get_base_address_2();
				}else{
					return '';
				}
			}
		}
		
        if ( ! class_exists( 'Mingo_UPS_Shipping_Method_Basic' ) ) {
            class Mingo_UPS_Shipping_Method_Basic extends WC_Shipping_Method {
                /**
                 * Constructor for your shipping class
                 *
                 * @access public
                 * @return void
                 */
				 
				var $services	=	array(
					// Domestic
					'14'	=>	'UPS Next Day Air Early',
					'01'	=>	'UPS Next Day Air',
					'13'	=>	'UPS Next Day Air Saver',
					'59'	=>	'UPS 2nd Day Air A.M.',
					'02'	=>	'UPS 2nd Day Air',
					'12'	=>	'UPS 3 Day Select',
					'03'	=>	'UPS Ground',
				);
				
                public function __construct() {
                    $this->id                 = 'mng-ups-shipping'; 
                    $this->method_title       = __( 'UPS Shipping', 'mingocommerce' );  
                    $this->method_description = __( 'Live rate ups shipping method', 'mingocommerce' ); 
 
                    $this->init();
 
                    $this->enabled 				= 	isset( $this->settings['enabled'] ) ? $this->settings['enabled'] : 'yes';
                    $this->title 				= 	isset( $this->settings['title'] ) ? $this->settings['title'] : __( 'UPS Shipping', 'mingocommerce' );
					
					$this->user_id         		= 	isset( $this->settings['user_id'] ) ? $this->settings['user_id'] : '';
					$this->password        		= 	isset( $this->settings['password'] ) ? $this->settings['password'] : '';
					$this->access_key      		= 	isset( $this->settings['access_key'] ) ? $this->settings['access_key'] : '';
					$this->shipper_number  		= 	isset( $this->settings['shipper_number'] ) ? $this->settings['shipper_number'] : '';
					
					
					$this->origin_postcode  	= 	isset( $this->settings['origin_postcode'] ) ? $this->settings['origin_postcode'] : '';
					$this->negotiated_rates  	= 	( isset( $this->settings['negotiated_rates'] ) && $this->settings['negotiated_rates'] == 'yes' ) ? true : false;
					
					$this->fallback_rate  	= 	isset( $this->settings['fallback_rate'] ) ? $this->settings['fallback_rate'] : 0;
					
					$this->service_settings		= 	$this->load_service_settings();
                }
 
                /**
                 * Init your settings
                 *
                 * @access public
                 * @return void
                 */
                function init() {
                    // Load the settings API
                    $this->init_form_fields(); 
                    $this->init_settings(); 
 
                    // Save settings in admin if you have any defined
                    add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
					add_action( 'admin_notices', array( $this, 'configuration_notice') );
                }
				
				function configuration_notice(){
					
					if( !$this->user_id || !$this->password || !$this->access_key ){
						echo '<div class="error is-dismissable"><p>'.sprintf(__('UPS shipping method is installed but it is not configured properly. Please <a href="%s">configure</a> the plugin setting with mandatory details to get API rates.', 'mingocommerce'), admin_url( 'admin.php?page=wc-settings&tab=shipping&section=mng-ups-shipping' )).'</p></div>';
					}
					
				}
 
                /**
                 * Define settings field for this shipping
                 * @return void 
                 */
                function init_form_fields() {
					global $woocommerce;
 
                    // We will add our settings here
					$this->form_fields	=	array(
						'user_id'            => array(
							'title'          => __( 'UPS Login ID', 'mingocommerce' ),
							'type'           => 'text',
						),
						'password'           => array(
							'title'          => __( 'Password', 'mingocommerce' ),
							'type'           => 'text',
						),
						'access_key'         => array(
							'title'          => __( 'Access Key', 'mingocommerce' ),
							'type'           => 'text',
						),
						'shipper_number'     => array(
							'title'          => __( 'UPS Account Number', 'mingocommerce' ),
							'type'           => 'text',
						),
						'origin_postcode'	=>	array(
							'title'	=>	__('Store ZIP Code', 'mingocommerce'),
							'type'	=>	'text',
						),
						'services'			=> array(
							'type'			=> 'services'
						),
						'negotiated_rates'	=>	array(
							'title'	=>	__('Negotiated Rates', 'mingocommerce'),
							'type'	=>	'checkbox',
							'label'	=>	__('Enable', 'mingocommerce'),
							'description'	=>	__('Enable this if your account have negotiated rates', 'mingocommerce'),
						),
						'fallback_rate'	=>	array(
							'title'	=>	__('Fallback Rate', 'mingocommerce'),
							'type'	=>	'text',
						),
					);
                }
 
                /**
                 * This function is used to calculate the shipping cost. Within this function we can check for weights, dimensions and other parameters.
                 *
                 * @access public
                 * @param mixed $package
                 * @return void
                 */
                public function calculate_shipping( $package = array() ) {
                    //print '<pre>';
					//print_r($package['contents']);
					
					
					if(is_array($package)){
						
						$package_details	=	array();					
						$total_weight		=	0;
						foreach($package['contents'] as $item_key => $item){
							
							if( !$item['data']->needs_shipping() ){ // It's a virtual product
								continue;
							}
							
							if( !$item['data']->get_weight() ){
								continue; // Weight was not provided
							}
							
							$item_weight		=	round( wc_get_weight($item['data']->get_weight(), 'lbs'), 2);
							$item_line_weight	=	$item_weight * $item['quantity'];
							$total_weight	=	$total_weight + $item_line_weight;						
						}
						
						$package_details['weight']			=	$total_weight;
						$package_details['destination']		=	$package['destination'];
						$package_details['origin']			=	$this->get_store_origin();
						
						
						//print_r($package_details);
						// We will add the cost, rate and logics in here
						
						$services	=	$this->services;
						// Later on apply filter
						
						$rates	=	array();
						foreach( $services as $service_code => $service_name ){
							
							$service_settings	=	$this->service_settings[$service_code];
							
							if( isset($service_settings['active']) && $service_settings['active'] == true ){
								
								$retrived_rate		=	$this->get_ups_rates( $service_code, $package_details);	
								
								if( $retrived_rate !== false ){
									
									$rate	=	array(
										'code'	=>	$service_code,
										'title'	=>	$service_name,
										'cost'	=>	$retrived_rate,
									);
									
									// Adjust rate 
									$rate	=	apply_filters( 'mingo_ups_shipping_rate', $rate, $package_details );
									
									$rates[$service_code]	=	$rate;
								}
							}
						}
						
						if( !count( $rates ) && $this->fallback_rate != 0 ){
							// No rates returned and fallback is set
							$rates['00']	=	array(
								'code'	=>	'00',
								'title'	=>	$this->method_title,
								'cost'	=>	$this->fallback_rate,
							);
						}
						
						foreach($rates as $rate){
							$this->add_ship_rate( $rate );
						}
					}
                }
				
				function add_ship_rate( $r ){
					
					$rate = array(
						'id' 	=>	$this->id.':'.$r['code'],
						'label'	=>	$r['title'],
						'cost' 	=>	$r['cost']
					);
					 
					$this->add_rate( $rate );
					
				}
				
				/*	get woocommerce store address	*/
				function get_store_origin(){
					$origin_postcode	=	$this->origin_postcode;
					$wcc	=	new MC_Countries();
					
					$store_origin	=	array(
						'address'	=>	$wcc->get_base_address(),
						'address_2'	=>	$wcc->get_base_address_2(),
						'country'	=>	$wcc->get_base_country(),
						'state'		=>	$wcc->get_base_state(),
						'city'		=>	$wcc->get_base_city(),
						'postcode'	=>	$origin_postcode ? $origin_postcode : $wcc->get_base_postcode(),
					);
					
					return $store_origin;
				}
				
				function get_ups_rates( $service_code, $package_details){
					$rate	=	false;
					
					$xml	=	'<?xml version="1.0" ?>
									<AccessRequest xml:lang=\'en-US\'>
										<AccessLicenseNumber>'.$this->access_key.'</AccessLicenseNumber>
										<UserId>'.$this->user_id.'</UserId>
										<Password>'.$this->password.'</Password>
									</AccessRequest>
									<?xml version="1.0" ?>
										<RatingServiceSelectionRequest>
											<Request>
												<TransactionReference>
													<CustomerContext>Rating and Service</CustomerContext>
													<XpciVersion>1.0</XpciVersion>
												</TransactionReference>
												<RequestAction>Rate</RequestAction>
												<RequestOption>Rate</RequestOption>
											</Request>
											<PickupType>
												<Code>01</Code>
												<Description>Daily Pickup</Description>
											</PickupType>
											<Shipment>
												<Description>WooCommerce Rate Request</Description>
												<Shipper>
													<ShipperNumber></ShipperNumber>
													<Address>
														<AddressLine>'.$package_details['origin']['address'].'</AddressLine>
														<PostalCode>'.$package_details['origin']['postcode'].'</PostalCode>
														<CountryCode>'.$package_details['origin']['country'].'</CountryCode>
													</Address>
												</Shipper>
												<ShipTo>
													<Address>
														<StateProvinceCode>'.$package_details['destination']['state'].'</StateProvinceCode>
														<PostalCode>'.$package_details['destination']['postcode'].'</PostalCode>
														<CountryCode>'.$package_details['destination']['country'].'</CountryCode>
													</Address>
												</ShipTo>
												<ShipFrom>
													<Address>
														<AddressLine>'.$package_details['origin']['address'].'</AddressLine>
														<PostalCode>'.$package_details['origin']['postcode'].'</PostalCode>
														<CountryCode>'.$package_details['origin']['country'].'</CountryCode>
													</Address>
												</ShipFrom>
												<Service>
													<Code>'.$service_code.'</Code>
												</Service>
												<Package>
													<PackagingType>
														<Code>02</Code>
														<Description>Package/customer supplied</Description>
													</PackagingType>
													<Description>Rate</Description>
													<PackageWeight>
														<UnitOfMeasurement>
															<Code>LBS</Code>
														</UnitOfMeasurement>
														<Weight>'.$package_details['weight'].'</Weight>
													</PackageWeight>
												</Package>
											</Shipment>
										</RatingServiceSelectionRequest>';
					
					$this->endpoint = 'https://wwwcie.ups.com/ups.app/xml/Rate';
					
					$response = wp_remote_post( $this->endpoint,
						array(
							'timeout'   => 70,
							'sslverify' => 0,
							'body'      => $xml
						)
					);
					if( is_wp_error( $response ) ){
						$this->throw_error( htmlspecialchars( $response->get_error_message() ) );
						return $rate;
					}
					if( $response['response']['code'] != 200 ){ // If some issues with API call
						return $rate;
					}
					
					$response_body	=	$response['body'];					
					$res_xml	=	simplexml_load_string( preg_replace('/<\?xml.*\?>/','', $response_body ) );
					
					if( $res_xml->Response->ResponseStatusCode != 1){
						$this->throw_error( (string) $res_xml->Response->Error->ErrorDescription );  // Do error handeling later on
						return $rate;
					}
					
					$rate	=	(float) $res_xml->RatedShipment->TotalCharges->MonetaryValue;
					
					// Account negotiated rates
					if( $this->negotiated_rates && property_exists( $res_xml->RatedShipment, 'NegotiatedRates') ){
						$rate	=	(float) $res_xml->RatedShipment->NegotiatedRates->NetSummaryCharges->GrandTotal->MonetaryValue;
					}
					
					return $rate;
				}
				
				/*
				Generate services table
				*/				
				function generate_services_html(){
					ob_start();
					$services			=	$this->services;
					$service_settings	=	$this->service_settings;
					?>
					<tr valign="top">
						<td colspan="2">
							<h2>Services</h2>
							<table class="widefat wc_gateways" cellspacing="0">
								<thead>
									<tr>
										<th>Code</th>
										<th>Service Name</th>
										<th>Active</th>
									</tr>
								<thead>
								<tbody>
									<?php foreach( $services as $service_code => $service_name ){?>
									<tr>
										<td><?php echo $service_code; ?></td>
										<td><?php echo $service_name; ?></td>
										<td>
											<input type="checkbox" name="ups_service[<?php echo $service_code; ?>][active]" 
											<?php if( isset($service_settings[$service_code]['active'] ) && $service_settings[$service_code]['active'] == true ){ echo " checked=\"checked\"";}?>
											>
										</td>
									</tr>
									<?php }?>
								</tbody>
							</table>
						</td>
					</tr>
					<?php
					return ob_get_clean();
				}
				
				/*
				Evaluate services field before saving
				*/
				function validate_services_field( $key ) {
					
					$services_to_be_saved	=	array();
					
					$services_data	=	$_POST['ups_service'];
					if( isset($services_data) && is_array($services_data) ){
						
						foreach( $services_data as $code => $service_data){
							$services_to_be_saved[$code]	=	array(
								'active'	=>	isset($service_data[$code]) ? true : false ,
							);
						}
						
					}
					
					return $services_to_be_saved;
				}
				
				function load_service_settings(){
					$service_settings	=	array();
					
					if( !isset($this->settings['services']) ){ // Load Default
						foreach( $this->services as $code => $name ){
							$service_settings[$code]['active']	=	true;
						}
					}else{
						foreach( $this->services as $code => $service ){
							$service_settings[$code]['active']	=	isset($this->settings['services'][$code]['active']) ? true : false; // make value 'on' to true
						}
						//$service_settings	=	$this->settings['services'];
					}
					return $service_settings;
				}
				
				function throw_error( $message ){
					wc_add_notice( $message, 'error' );
				}
				
				function admin_options(){
					parent::admin_options();
				}
            }
        }
    }
 
    add_action( 'woocommerce_shipping_init', 'mingo_ups_shipping_shipping_method_basic' );
	add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'mingo_ups_shipping_basic_action_links');
	
 
    function add_mingo_ups_shipping_shipping_method_basic( $methods ) {
        $methods[] = 'Mingo_UPS_Shipping_Method_Basic';
        return $methods;
    }
	function mingo_ups_shipping_basic_action_links( $links ){
		$new_links[]	=	'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=shipping&section=mng-ups-shipping' ) . '">'.__('Settings', 'mingocommerce').'</a>';
		$links	=	array_merge( $new_links, $links);
return $links;
	}
 
    add_filter( 'woocommerce_shipping_methods', 'add_mingo_ups_shipping_shipping_method_basic' );
	
	
	function mingo_ups_basic_woocommerce_version_check( $version = '2.1' ) {
	  if ( function_exists( 'is_woocommerce_active' ) && is_woocommerce_active() ) {
		global $woocommerce;
		if( version_compare( $woocommerce->version, $version, ">=" ) ) {
		  return true;
		}
	  }
	  return false;
	}
	
}