<?php
class WF_Shipping_UPS_Admin
{
	private static $wc_version;
	private $ups_services = array(
		// Domestic
		"12" => "UPS 3 Day Select®",                                      
		"03" => "UPS® Ground",
		"02" => "UPS 2nd Day Air®",
		"59" => "UPS 2nd Day Air A.M.®",
		"01" => "UPS Next Day Air®",
		"13" => "UPS Next Day Air Saver®",
		"14" => "UPS Next Day Air® Early",
		"74" => "UPS Express® 12:00"	,		// Germany Domestic

		// International
		"11" => "UPS® Standard",
		"07" => "UPS Worldwide Express™",
		"54" => "UPS Worldwide Express Plus™",
		"08" => "UPS Worldwide Expedited",
		"65" => "UPS Saver",
		
		// SurePost
		"92" =>	"UPS SurePost® (USPS) < 1lb",
		"93" =>	"UPS SurePost® (USPS) > 1lb",
		"94" =>	"UPS SurePost® (USPS) BPM",
		"95" =>	"UPS SurePost® (USPS) Media",
		
		//New Services
		"M2" => "UPS First Class Mail",
		"M3" => "UPS Priority Mail",
		"M4" => "UPS Expedited Mail Innovations ",
		"M5" => "UPS Priority Mail Innovations ",
		"M6" => "UPS EconomyMail Innovations ",
		"70" => "UPS Access Point® Economy ",
		"96" => "UPS Worldwide Express Freight",
		
		"US48" => "Ground with Freight",
		
	);

	// Shipments Originating in the European Union
	private $euservices = array(
		"07" => "UPS Express",
		"08" => "UPS Expedited",
		"11" => "UPS® Standard",
		"54" => "UPS Worldwide Express Plus™",
		"65" => "UPS Worldwide Express Saver®",
		"70" => "UPS Access Point® Economy",
		"74" => "UPS Express® 12:00",
	);

	private $ukservices = array(
		"07" => "UPS Express",
		"08" => "UPS Expedited",
		"11" => "UPS® Standard",
		"54" => "UPS Worldwide Express Plus™",
		"65" => "UPS Worldwide Express Saver®",
		"70" => "UPS Access Point® Economy",
	);

	// European country
	private $eu_array = array('BE','BG','CZ','DK','DE','EE','IE','GR','ES','FR','HR','IT','CY','LV','LT','LU','HU','MT','NL','AT','PT','RO','SI','SK','FI','PL','SE');

	private $cod_currency_specific_contries = array(
		'BE' => 'EUR',
		'BG' => 'EUR',
		'CZ' => 'EUR',
		'DK' => 'EUR',
		'DE' => 'EUR',
		'EE' => 'EUR',
		'IE' => 'EUR',
		'GR' => 'EUR',
		'ES' => 'EUR',
		'FR' => 'EUR',
		'HR' => 'EUR',
		'IT' => 'EUR',
		'CY' => 'EUR',
		'LV' => 'EUR',
		'LT' => 'EUR',
		'LU' => 'EUR',
		'HU' => 'EUR',
		'MT' => 'EUR',
		'NL' => 'EUR',
		'AT' => 'EUR',
		'PT' => 'EUR',
		'RO' => 'EUR',
		'SI' => 'EUR',
		'SK' => 'EUR',
		'FI' => 'EUR',
		'GB' => 'EUR',
		'PL' => 'EUR',
		'SE' => 'EUR',
	);

	private $specific_character_encoding_html_reference = array(
		'ä' => '&#228;',
		'Ä' => '&#196;',
		'ö' => '&#246;',
		'Ö' => '&#214;',
		'ü' => '&#252;',
		'Ü' => '&#220;',
		'ß' => '&#223;',
	);

	private $cyrillic_characters = [
		'а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п',
		'р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я','Nº',
		'А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П',
		'Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я','№',
	];

	private $latin_characters = [    // Latin characters corresponding to the Cyrillic character list in $cyrillic_characters
            'a','b','v','g','d','e','io','zh','z','i','y','k','l','m','n','o','p',
            'r','s','t','u','f','h','ts','ch','sh','sht','a','i','y','e','yu','ya','No.',
            'A','B','V','G','D','E','Io','Zh','Z','I','Y','K','L','M','N','O','P',
            'R','S','T','U','F','H','Ts','Ch','Sh','Sht','A','I','Y','e','Yu','Ya','No.',
        ];

	private $polandservices = array(
		"07" => "UPS Express",
		"08" => "UPS Expedited",
		"11" => "UPS® Standard",
		"54" => "UPS Express Plus®",
		"65" => "UPS Express Saver",
		"82" => "UPS Today Standard",
		"83" => "UPS Today Dedicated Courier",
		"85" => "UPS Today Express",
		"86" => "UPS Today Express Saver",
		"70" => "UPS Access Point® Economy",
	);

	private $canadaservices = array(
		"01" =>	"UPS Express",
		"02" => "UPS Expedited",
		"07" =>	"UPS Worldwide Express™",
		"08" =>	"UPS Worldwide Expedited®",
		"11" =>	"UPS® Standard",
		"12" => "UPS 3 Day Select®",				// For CA and US48
		"13" => "UPS Express Saver",
		"14" =>	"UPS Express Early",
		"54" => "UPS Worldwide Express Plus™",	//UPS Express Early for CA and US48
		"65" => "UPS Express Saver",
		"70" =>	"UPS Access Point® Economy",
	);

	public $freight_services=array(
		'308'=>'TForce Freight LTL',
		'309'=>'TForce Freight LTL - Guaranteed',
		'334'=>'TForce Freight LTL - Guaranteed A.M.',
		'349'=>'TForce Freight LTL Mexico',
	);
	public $freight_package_type_code_list=array(
		"BAG"=>"Bag",
		"BAL"=>"Bale",
		"BAR"=>"Barrel",
		"BDL"=>"Bundle",
		"BIN"=>"Bin",
		"BOX"=>"Box",
		"BSK"=>"Basket",
		"BUN"=>"Bunch",
		"CAB"=>"Cabinet",
		"CAN"=>"Can",
		"CAR"=>"Carrier",
		"CAS"=>"Case",
		"CBY"=>"Carboy",
		"CON"=>"Container",
		"CRT"=>"Crate",
		"CSK"=>"Cask",
		"CTN"=>"Carton",
		"CYL"=>"Cylinder",
		"DRM"=>"Drum",
		"LOO"=>"Loose",
		"OTH"=>"Other",
		"PAL"=>"Pail",
		"PCS"=>"Pieces",
		"PKG"=>"Package",
		"PLN"=>"Pipe Line",
		"PLT"=>"Pallet",
		"RCK"=>"Rack",
		"REL"=>"Reel",
		"ROL"=>"Roll",
		"SKD"=>"Skid",
		"SPL"=>"Spool",
		"TBE"=>"Tube",
		"TNK"=>"Tank",
		"UNT"=>"Unit",
		"VPK"=>"Van Pack",
		"WRP"=>"Wrapped",
	);
	private $freight_class_list=array(
		"50",
		"55",
		"60",
		"65",
		"70",
		"77.5",
		"85",
		"92.5",
		"100",
		"110",
		"125",
		"150",
		"175",
		"200",
		"250",
		"300",
		"400",
		"500",
	);
	private $freight_endpoint = 'https://wwwcie.ups.com/rest/FreightRate';
	private $ups_surepost_services = array(92, 93, 94, 95);
	private $email_notification_services = array('M2', 'M3', 'M4');
	private $phone_number_services = array('01','13','14');

	// NAFTA Origin Destination Pair
    public $nafta_supported_countries = array(
        'US' => array(
            'CA',
            'MX',
        ),
        'CA' => array(
            'US',
            'PR',
            'MX',
        ),
        'PR' => array(
            'CA',
            'MX',
        ),
    );

    private $satelliteCountries = array(
		'E2'	=> 	'BQ',
		'S1'	=> 	'BQ',
		'IC'	=> 	'ES',
		'XC'	=> 	'ES',
		'XL'	=> 	'ES',
		'AX'	=> 	'FI',
		'KO'	=> 	'FM',
		'PO'	=> 	'FM',
		'TU'	=> 	'FM',
		'YA'	=> 	'FM',
		'EN'	=> 	'GB',
		'NB'	=> 	'GB',
		'SF'	=> 	'GB',
		'WL'	=> 	'GB',
		'SW'	=> 	'KN',
		'RT'	=> 	'MP',
		'SP'	=> 	'MP',
		'TI'	=> 	'MP',
		'HO'	=> 	'NL',
		'TA'	=> 	'PF',
		'A2'	=> 	'PT',
		'M3'	=> 	'PT',
		'UI'	=> 	'VC',
		'VR'	=> 	'VG',
		'ZZ'	=> 	'VG',
		'C3'	=> 	'VI',
		'UV'	=> 	'VI',
		'VL'	=> 	'VI',
	);
	
	/**
	 * For Delivery Confirmation below array of countries will be considered as domestic, Confirmed by UPS.
	 * US to US, CA to CA, PR to PR are considered as domestic, all other shipments are international.
	 * @var array 
	 */
	public $dc_domestic_countries = array( 'US', 'CA', 'PR');
	
	public function __construct(){
		$this->wf_init();

		//Print Shipping Label.
		if ( is_admin() ) {
			$this->init_bulk_printing();
			add_action( 'add_meta_boxes', array( $this, 'wf_add_ups_metabox' ), 15 );
			add_action('admin_notices',array(new wf_admin_notice, 'throw_notices'), 15); // New notice system

			//add a custome field in product page
			// add_action( 'woocommerce_product_options_shipping', array($this,'wf_ups_custome_product_page')  );
			// add_action( 'woocommerce_process_product_meta', array( $this, 'wf_ups_save_custome_product_fields' ) );

			// Add a custome field in product page variation level
			// add_action( 'woocommerce_product_after_variable_attributes', array($this,'wf_variation_settings_fields'), 10, 3 );
			// Save a custome field in product page variation level
			// add_action( 'woocommerce_save_product_variation', array($this,'wf_save_variation_settings_fields'), 10, 2 );
		}
		
		if ( isset( $_GET['wf_ups_shipment_confirm'] ) ) {
			add_action( 'init', array( $this, 'wf_ups_shipment_confirm' ), 15 );
		}
		else if ( isset( $_GET['wf_ups_shipment_accept'] ) ) {
			add_action( 'init', array( $this, 'wf_ups_shipment_accept' ), 15 );
		}
		else if ( isset( $_GET['wf_ups_print_label'] ) ) {
			add_action( 'init', array( $this, 'wf_ups_print_label' ), 15 );
		}
		else if( isset( $_GET['wf_ups_print_commercial_invoice'] ) ){
			add_action( 'init', array( $this, 'wf_ups_print_commercial_invoice' ), 15 );
		}
		else if( isset( $_GET['wf_ups_print_return_commercial_invoice'] ) ){
			add_action( 'init', array( $this, 'wf_ups_print_return_commercial_invoice' ), 15 );
		}
		else if ( isset( $_GET['wf_ups_void_shipment'] ) ) {
			add_action( 'init', array( $this, 'wf_ups_void_shipment' ), 15 );
		}
		else if ( isset( $_GET['phupsgp'] ) ) {
			add_action( 'init', array( $this, 'ph_ups_generate_packages' ), 15 );
		}
		elseif (isset($_GET['wf_ups_generate_packages_rates'])) {				// To get the rates in UPS admin side
			add_action('admin_init', array($this, 'wf_ups_generate_packages_rates'), 15 );
		}
		elseif ( isset( $_GET['xa_generate_return_label']) ) {			// Create Return label after generating the label
			add_action( 'admin_init', array( $this, 'xa_generate_return_label' ) );
		}
		elseif ( isset( $_GET['ph_ups_print_control_log_receipt']) ) {			// Print the control log
			add_action( 'admin_init', array( $this, 'ph_ups_print_control_log_receipt' ) );
		}
		elseif ( isset( $_GET['ph_ups_dgm'] ) ) { 	// Print Dangerous Goods Manifest
			add_action( 'admin_init', array( $this, 'ph_ups_print_dangerous_goods_manifest' ) );
		}
		elseif ( isset( $_GET['ph_ups_dangerous_goods_signatoryinfo'] ) ) { 	//PDS-129
			add_action( 'admin_init', array( $this, 'ph_ups_print_dangerous_goods_signatoryinfo' ) );
		}
	}

	private function wf_init() {
		global $post;
		
		if( empty(self::$wc_version) )	self::$wc_version = WC()->version;

		$shipmentconfirm_requests 			= array();
		// Load UPS Settings.
		$this->settings 					= get_option( 'woocommerce_'.WF_UPS_ID.'_settings', null ); 
		//Print Label Settings.
		$this->isc=isset( $this->settings['international_special_commodities'] ) && !empty($this->settings['international_special_commodities']) && $this->settings['international_special_commodities'] == 'yes' ? 'yes' : 'no';
		$this->disble_ups_print_label		= isset( $this->settings['disble_ups_print_label'] ) ? $this->settings['disble_ups_print_label'] : '';
		$this->disble_shipment_tracking		= isset( $this->settings['disble_shipment_tracking'] ) ? $this->settings['disble_shipment_tracking'] : 'TrueForCustomer';
		$this->show_label_in_browser	    = isset( $this->settings['show_label_in_browser'] ) ? $this->settings['show_label_in_browser'] : 'no';
		$this->box_max_weight			=	isset($this->settings[ 'box_max_weight']) ?  $this->settings[ 'box_max_weight'] : '';
		$this->weight_packing_process	=	isset($this->settings[ 'weight_packing_process']) ? $this->settings[ 'weight_packing_process'] : '';
		$this->enable_freight 			= isset( $this->settings['enable_freight'] ) && $this->settings['enable_freight'] == 'yes' ? true : false;
		// $this->ground_freight 			= isset( $this->settings['ground_freight'] ) && $this->settings['ground_freight'] == 'yes' ? true : false;
		$this->email_notification   	= isset($this->settings['email_notification'])?$this->settings['email_notification']:array();

		$this->xa_show_all 			= isset( $this->settings['xa_show_all'] ) && $this->settings['xa_show_all'] == 'yes' ? true : false;
		
		$this->address_validation	= isset($this->settings['address_validation']) && $this->settings[ 'address_validation']=='yes' ? true : false;
		$this->residential		    = isset( $this->settings['residential'] ) && $this->settings['residential'] == 'yes' ? true : false;
		
		// Units
		$this->units			= isset( $this->settings['units'] ) ? $this->settings['units'] : 'imperial';
		
		//Advanced Settings
		$this->api_mode      				= isset( $this->settings['api_mode'] ) ? $this->settings['api_mode'] : 'Test';
		$this->ssl_verify			= isset( $this->settings['ssl_verify'] ) ? $this->settings['ssl_verify'] : false;
		$this->enable_latin_encoding = isset( $this->settings['latin_encoding'] ) ? $this->settings['latin_encoding'] == 'yes' : false;

		$this->debug      	= isset( $this->settings['debug'] ) && $this->settings['debug'] == 'yes' ? true : false;
		
		if ( $this->units == 'metric' ) {
			$this->weight_unit = 'KGS';
			$this->dim_unit    = 'CM';
		} else {
			$this->weight_unit = 'LBS';
			$this->dim_unit    = 'IN';
		}

		$this->uom = ($this->units == 'imperial')?'LB':'KG';

		if ( ! class_exists( 'WF_Shipping_UPS' ) ) {
			include_once 'class-wf-shipping-ups.php';
		}
		
		$this->commercial_invoice	= isset( $this->settings['commercial_invoice'] ) && !empty($this->settings['commercial_invoice']) && $this->settings['commercial_invoice'] == 'yes' ? true : false;
		//PDS-129
		$this->dangerous_goods_signatoryinfo	= isset( $this->settings['dangerous_goods_signatoryinfo'] ) && !empty($this->settings['dangerous_goods_signatoryinfo']) && $this->settings['dangerous_goods_signatoryinfo'] == 'yes' ? true : false;
		$this->nafta_co_form		= ( isset( $this->settings['nafta_co_form'] ) && !empty($this->settings['nafta_co_form']) && $this->settings['nafta_co_form'] == 'yes' ) ? true : false;
		$this->eei_data				= ( isset( $this->settings['eei_data'] ) && !empty($this->settings['eei_data']) && $this->settings['eei_data'] == 'yes' ) ? true : false;

		$this->edi_on_label				= ( isset( $this->settings['edi_on_label'] ) && !empty($this->settings['edi_on_label']) && $this->settings['edi_on_label'] == 'yes' ) ? true : false;
		//PDS-124
		$this->commercial_invoice_shipping				= ( isset( $this->settings['commercial_invoice_shipping'] ) && !empty($this->settings['commercial_invoice_shipping']) && $this->settings['commercial_invoice_shipping'] == 'yes' ) ? true : false;

		$this->tin_number 			= isset($this->settings[ 'tin_number']) ?  $this->settings[ 'tin_number'] : '';
		$this->recipients_tin 		= ( isset($this->settings['recipients_tin']) && ! empty($this->settings['recipients_tin']) && $this->settings['recipients_tin'] == 'yes' ) ? true : false;

		$this->terms_of_shipment 		=    isset($this->settings[ 'terms_of_shipment']) && !empty($this->settings['terms_of_shipment']) ?  $this->settings[ 'terms_of_shipment'] : '';
		$this->reason_export 			=    isset($this->settings[ 'reason_export']) ?  $this->settings[ 'reason_export'] : '';
		$this->return_reason_export 	=    isset($this->settings[ 'return_reason_export']) && !empty($this->settings['return_reason_export']) ?  $this->settings[ 'return_reason_export'] : 'RETURN';

		$this->accesspoint_locator 	= (isset($this->settings[ 'accesspoint_locator']) && $this->settings[ 'accesspoint_locator']=='yes') ? true : false;

		if( "Live" == $this->api_mode ) {
			$this->endpoint = 'https://onlinetools.ups.com/ups.app/xml/Locator';
		}
		else {
			$this->endpoint = 'https://wwwcie.ups.com/ups.app/xml/Locator';
		}

		$this->countries_with_statecodes	=	array('US','CA','IE');
		
		$this->set_origin_country_state();

		if ( $this->origin_country == 'PL' ) {
			$this->ups_services = $this->polandservices;
		}elseif( $this->origin_country == 'CA' ) {
			$this->ups_services = $this->canadaservices;
		}elseif( $this->origin_country == 'GB' || $this->origin_country == 'UK' ) {
			$this->ups_services = $this->ukservices;
		}elseif ( in_array( $this->origin_country, $this->eu_array ) ) {
			$this->ups_services = $this->euservices;
		}

		$this->mail_innovation_type = ( isset( $this->settings['mail_innovation_type'] ) && !empty($this->settings['mail_innovation_type']) ) ? $this->settings['mail_innovation_type']  : '66';
		$this->usps_endorsement 	= ( isset( $this->settings['usps_endorsement'] ) && !empty($this->settings['usps_endorsement']) ) ? $this->settings['usps_endorsement']  : '5';

		$this->min_order_amount_for_insurance = ! empty($this->settings['min_order_amount_for_insurance']) ? $this->settings['min_order_amount_for_insurance'] : 0;
		$this->skip_products 	= ! empty($this->settings['skip_products']) ? $this->settings['skip_products'] : array();
		$this->min_weight_limit = ! empty($this->settings['min_weight_limit']) ? (float) $this->settings['min_weight_limit'] : null;
		$this->max_weight_limit	= ! empty($this->settings['max_weight_limit']) ? (float) $this->settings['max_weight_limit'] : null;

		$this->billing_address_as_shipper = ( isset($this->settings['billing_address_as_shipper']) && !empty($this->settings['billing_address_as_shipper']) && $this->settings['billing_address_as_shipper'] == 'yes' ) ? true : false;
		$this->ship_from_address_different_from_shipper = ! empty($this->settings['ship_from_address_different_from_shipper']) ? $this->settings['ship_from_address_different_from_shipper'] : 'no';
		
		$this->enable_density_based_rating = ( isset( $this->settings['enable_density_based_rating'] ) && $this->settings['enable_density_based_rating'] == 'yes') ? true : false;
		$this->density_length 	= ( isset( $this->settings['density_length'] ) && !empty( $this->settings['density_length'] ) ) ? $this->settings['density_length'] : 0;
		$this->density_width 	= ( isset( $this->settings['density_width'] ) && !empty( $this->settings['density_width'] ) ) ? $this->settings['density_width'] : 0;
		$this->density_height 	= ( isset( $this->settings['density_height'] ) && !empty( $this->settings['density_height'] ) ) ? $this->settings['density_height'] : 0;

		$this->customandduties = ( isset( $this->settings['duties_and_taxes'] ) && !empty($this->settings['duties_and_taxes']) ) ? $this->settings['duties_and_taxes']  : 'receiver';
		$this->transportation = ( isset( $this->settings['transportation'] ) && !empty($this->settings['transportation']) ) ? $this->settings['transportation']  : 'shipper';

		$this->label_description 	= ( isset( $this->settings['label_description'] ) && !empty( $this->settings['label_description'] ) ) ? $this->settings['label_description'] : 'product_category';
		$this->label_custom_description 	= ( isset( $this->settings['label_custom_description'] ) && !empty( $this->settings['label_custom_description'] ) ) ? $this->settings['label_custom_description'] : '';
		$this->include_order_id	= ( isset($this->settings['include_order_id']) && ! empty($this->settings['include_order_id']) && $this->settings['include_order_id'] == 'yes' ) ? 'yes' : 'no';
		$this->add_product_sku	= ( isset($this->settings['add_product_sku']) && ! empty($this->settings['add_product_sku']) && $this->settings['add_product_sku'] == 'yes' ) ? 'yes' : 'no';
		$this->include_in_commercial_invoice	= ( isset($this->settings['include_in_commercial_invoice']) && ! empty($this->settings['include_in_commercial_invoice']) && $this->settings['include_in_commercial_invoice'] == 'yes' ) ? 'yes' : 'no';
		//PDS-125
		$this->discounted_price	= ( isset($this->settings['discounted_price']) && ! empty($this->settings['discounted_price']) && $this->settings['discounted_price'] == 'yes' ) ? true : false;

		$this->remove_recipients_phno		= ( isset($this->settings['remove_recipients_phno']) && ! empty($this->settings['remove_recipients_phno']) && $this->settings['remove_recipients_phno'] == 'yes' ) ? true : false;
		$this->remove_special_char_product	= ( isset($this->settings['remove_special_char_product']) && ! empty($this->settings['remove_special_char_product']) && $this->settings['remove_special_char_product'] == 'yes' ) ? true : false;
		$this->shipper_release_indicator 	= ( isset($this->settings['shipper_release_indicator']) && ! empty($this->settings['shipper_release_indicator']) && $this->settings['shipper_release_indicator'] == 'yes' ) ? true : false;

		$this->dangerous_goods_manifest 	= ( isset($this->settings['dangerous_goods_manifest']) && ! empty($this->settings['dangerous_goods_manifest']) && $this->settings['dangerous_goods_manifest'] == 'yes' ) ? true : false;

		$this->carbonneutral_indicator 	= ( isset($this->settings['carbonneutral_indicator']) && ! empty($this->settings['carbonneutral_indicator']) && $this->settings['carbonneutral_indicator'] == 'yes' ) ? true : false;

		$this->eu_country_cod_type 	= isset($this->settings['eu_country_cod_type']) && !empty($this->settings['eu_country_cod_type']) ? $this->settings['eu_country_cod_type'] : 9;

		$this->wcsups	=	new WF_Shipping_UPS();
		include_once( 'class-wf-shipping-ups-tracking.php' );
		
		add_filter('wf_ups_filter_label_packages',array($this,'manual_packages'),10,2);

		// Access Point
		if($this->accesspoint_locator ){
			add_action( 'woocommerce_admin_order_data_after_shipping_address', array($this, 'ph_editable_access_point_location'), 15 );
			add_action( 'woocommerce_process_shop_order_meta', array($this, 'ph_save_access_point_location'), 15 );
		}

		// To support Calculate Shipping Cost in Order Page for Freight Rates
		$this->freight_package_type_code 			= 'PLT';
		$this->freight_handling_unit_one_type_code 	= 'PLT';

		$this->freight_class 			= isset( $this->settings['freight_class'] ) && !empty($this->settings['freight_class']) ? $this->settings['freight_class'] : 50;
		$this->freight_packaging_type 	= isset( $this->settings['freight_packaging_type'] ) && !empty($this->settings['freight_packaging_type']) ? $this->settings['freight_packaging_type'] : 'PLT';
		
		$this->user_id		 				= isset( $this->settings['user_id'] ) ? $this->settings['user_id'] : '';
		$this->password						= isset( $this->settings['password'] ) ? $this->settings['password'] : '';
		$this->access_key	  				= isset( $this->settings['access_key'] ) ? $this->settings['access_key'] : '';
		$this->shipper_number  				= isset( $this->settings['shipper_number'] ) ? $this->settings['shipper_number'] : '';
		$this->ups_display_name				= isset( $this->settings['ups_display_name'] ) ? $this->settings['ups_display_name'] : '';
		$this->origin_addressline 			= isset( $this->settings['origin_addressline'] ) ? $this->settings['origin_addressline'] : '';
		$this->origin_addressline_2 		= isset( $this->settings['origin_addressline_2'] ) ? $this->settings['origin_addressline_2'] : '';
		$this->origin_city 					= isset( $this->settings['origin_city'] ) ? $this->settings['origin_city'] : '';
		$this->origin_postcode 				= isset( $this->settings['origin_postcode'] ) ? $this->settings['origin_postcode'] : '';
		$this->show_est_delivery			= ( isset($this->settings['enable_estimated_delivery']) && $this->settings['enable_estimated_delivery'] == 'yes' ) ? true : false;

		$this->freight_holiday_pickup 			= ( isset( $this->settings['freight_holiday_pickup'] ) && $this->settings['freight_holiday_pickup'] == 'yes' ) ? true : false;
		$this->freight_inside_pickup 			= ( isset( $this->settings['freight_inside_pickup'] ) && $this->settings['freight_inside_pickup'] == 'yes' ) ? true : false;
		$this->freight_residential_pickup 		= ( isset( $this->settings['freight_residential_pickup'] ) && $this->settings['freight_residential_pickup'] == 'yes' ) ? true : false;
		$this->freight_weekend_pickup 			= ( isset( $this->settings['freight_weekend_pickup'] ) && $this->settings['freight_weekend_pickup'] == 'yes' ) ? true : false;
		$this->freight_liftgate_pickup 			= ( isset( $this->settings['freight_liftgate_pickup'] ) && $this->settings['freight_liftgate_pickup'] == 'yes' ) ? true : false;
		$this->freight_limitedaccess_pickup 	= ( isset( $this->settings['freight_limitedaccess_pickup'] ) && $this->settings['freight_limitedaccess_pickup'] == 'yes' ) ? true : false;

		$this->freight_holiday_delivery 		= ( isset( $this->settings['freight_holiday_delivery'] ) && $this->settings['freight_holiday_delivery'] == 'yes' ) ? true : false;
		$this->freight_inside_delivery 			= ( isset( $this->settings['freight_inside_delivery'] ) && $this->settings['freight_inside_delivery'] == 'yes' ) ? true : false;
		$this->freight_call_before_delivery 	= ( isset( $this->settings['freight_call_before_delivery'] ) && $this->settings['freight_call_before_delivery'] == 'yes' ) ? true : false;
		$this->freight_weekend_delivery 		= ( isset( $this->settings['freight_weekend_delivery'] ) && $this->settings['freight_weekend_delivery'] == 'yes' ) ? true : false;
		$this->freight_liftgate_delivery 		= ( isset( $this->settings['freight_liftgate_delivery'] ) && $this->settings['freight_liftgate_delivery'] == 'yes' ) ? true : false;
		$this->freight_limitedaccess_delivery 	= ( isset( $this->settings['freight_limitedaccess_delivery'] ) && $this->settings['freight_limitedaccess_delivery'] == 'yes' ) ? true : false;

		$this->freight_pickup_inst 		= isset( $this->settings['freight_pickup_inst'] ) && !empty($this->settings['freight_pickup_inst']) ? $this->settings['freight_pickup_inst'] : '';
		$this->freight_delivery_inst 	= isset( $this->settings['freight_delivery_inst'] ) && !empty($this->settings['freight_delivery_inst']) ? $this->settings['freight_delivery_inst'] : '';

		$this->freight_payment_information 		= isset( $this->settings['freight_payment'] ) && !empty( $this->settings['freight_payment'] ) ? $this->settings['freight_payment'] : '10';
		$this->freight_thirdparty_contact_name	= isset( $this->settings['freight_thirdparty_contact_name'] ) && !empty($this->settings['freight_thirdparty_contact_name']) ? $this->settings['freight_thirdparty_contact_name'] : ' ';
		$this->freight_thirdparty_addressline 	= isset( $this->settings['freight_thirdparty_addressline'] ) && !empty($this->settings['freight_thirdparty_addressline']) ? $this->settings['freight_thirdparty_addressline'] : ' ';
		$this->freight_thirdparty_addressline_2 = isset( $this->settings['freight_thirdparty_addressline_2'] ) && !empty($this->settings['freight_thirdparty_addressline_2']) ? $this->settings['freight_thirdparty_addressline_2'] : ' ';
		$this->freight_thirdparty_city 			= isset( $this->settings['freight_thirdparty_city'] ) && !empty($this->settings['freight_thirdparty_city']) ? $this->settings['freight_thirdparty_city'] : ' ';
		$this->freight_thirdparty_postcode 		= isset( $this->settings['freight_thirdparty_postcode'] ) && !empty($this->settings['freight_thirdparty_postcode']) ? $this->settings['freight_thirdparty_postcode'] : ' ';
		$this->freight_thirdparty_country_state	= isset( $this->settings['freight_thirdparty_country_state'] ) && !empty($this->settings['freight_thirdparty_country_state']) ? $this->settings['freight_thirdparty_country_state'] : ' ';

		if( empty($this->freight_thirdparty_country_state) ) {
			$this->freight_thirdparty_country 	= $this->origin_country_state;		
			$this->freight_thirdparty_state 	= $this->origin_state;				
		}
		else {
			if (strstr($this->freight_thirdparty_country_state, ':')) :
				list( $this->freight_thirdparty_country, $this->freight_thirdparty_state ) = explode(':',$this->freight_thirdparty_country_state);
			else :
				$this->freight_thirdparty_country = $this->freight_thirdparty_country_state;
				$this->freight_thirdparty_state   = '';
			endif;
		}

		$this->freight_thirdparty_state 		= isset( $this->settings['freight_thirdparty_custom_state'] ) && !empty($this->settings['freight_thirdparty_custom_state']) ? $this->settings['freight_thirdparty_custom_state'] : $this->freight_thirdparty_state;

		$this->ph_delivery_confirmation = isset( $this->settings['ph_delivery_confirmation'] ) && !empty($this->settings['ph_delivery_confirmation']) ? $this->settings['ph_delivery_confirmation'] : 0;
	}

	public function ph_editable_access_point_location( $order ){

		$access_point_location = get_post_meta( $order->get_id(), '_shipping_accesspoint', true );
		$selected_accesspoint_locator='';
		if( !empty( $access_point_location ) )
		{
			$decoded_order_formatted_accesspoint = json_decode($access_point_location);

			$accesspoint_name = (isset($decoded_order_formatted_accesspoint->AddressKeyFormat->ConsigneeName)) ? $decoded_order_formatted_accesspoint->AddressKeyFormat->ConsigneeName : '';
			$accesspoint_address = (isset($decoded_order_formatted_accesspoint->AddressKeyFormat->AddressLine)) ? $decoded_order_formatted_accesspoint->AddressKeyFormat->AddressLine : '';
			$accesspoint_city = (isset($decoded_order_formatted_accesspoint->AddressKeyFormat->PoliticalDivision2)) ? $decoded_order_formatted_accesspoint->AddressKeyFormat->PoliticalDivision2 : '';
			$accesspoint_state = (isset($decoded_order_formatted_accesspoint->AddressKeyFormat->PoliticalDivision1)) ? $decoded_order_formatted_accesspoint->AddressKeyFormat->PoliticalDivision1 : '';
			$accesspoint_country = (isset($decoded_order_formatted_accesspoint->AddressKeyFormat->CountryCode)) ? $decoded_order_formatted_accesspoint->AddressKeyFormat->CountryCode : '';
			$accesspoint_postcode = (isset($decoded_order_formatted_accesspoint->AddressKeyFormat->PostcodePrimaryLow)) ? $decoded_order_formatted_accesspoint->AddressKeyFormat->PostcodePrimaryLow : '';

			update_post_meta( $order->get_id() , '_ph_accesspoint_name', $accesspoint_name );
			update_post_meta( $order->get_id() , '_ph_accesspoint_address', $accesspoint_address );
			update_post_meta( $order->get_id() , '_ph_accesspoint_city', $accesspoint_city );
			update_post_meta( $order->get_id() , '_ph_accesspoint_statecode', $accesspoint_state );
			update_post_meta( $order->get_id() , '_ph_accesspoint_countrycode', $accesspoint_country );
			update_post_meta( $order->get_id() , '_ph_accesspoint_postcode', $accesspoint_postcode );

			$order_shipping_accesspoint	=	substr( $accesspoint_name .', '. $accesspoint_address .', '. $accesspoint_city .', '. $accesspoint_postcode , 0, 70 );

			$selected_accesspoint_locator = $order_shipping_accesspoint;
		}

		// Load Shipping Method Settings.
		$settings		= get_option( 'woocommerce_'.WF_UPS_ID.'_settings', null ); 
		$user_id		= !empty( $settings['user_id'] ) ? $settings['user_id'] : '';
		$password       = isset( $settings['password'] ) ? $settings['password'] : '';
		$access_key     = isset( $settings['access_key'] ) ? $settings['access_key'] : '';

		$old_response = $response = null;

		$shipping_address = $order->get_shipping_address_1();
		$shipping_city = $order->get_shipping_city();
		$shipping_postalcode = $order->get_shipping_postcode();
		$shipping_state = $order->get_shipping_state();
		$shipping_country = $order->get_shipping_country();

		if( empty($shipping_country) ){
			return;
		}

		$xmlRequest = '<?xml version="1.0"?>
		<AccessRequest xml:lang="en-US">
		<AccessLicenseNumber>'.$access_key.'</AccessLicenseNumber>
		<UserId>'.$user_id.'</UserId>
		<Password>'.$password.'</Password>
		</AccessRequest>
		<?xml version="1.0"?>
		<LocatorRequest>
		<Request>
		<RequestAction>Locator</RequestAction>
		<RequestOption>1</RequestOption>
		</Request>
		<OriginAddress>
		<PhoneNumber>1234567891</PhoneNumber>
		<AddressKeyFormat>
		<ConsigneeName>yes</ConsigneeName>
		<AddressLine>'.$shipping_address.'</AddressLine>
		<PoliticalDivision2>'.$shipping_city.'</PoliticalDivision2>
		<PoliticalDivision1>'.$shipping_state.'</PoliticalDivision1>
		<PostcodePrimaryLow>'.$shipping_postalcode.'</PostcodePrimaryLow>
		<CountryCode>'.$shipping_country.'</CountryCode>
		</AddressKeyFormat>
		</OriginAddress>
		<Translate>
		<Locale>en_US</Locale>
		</Translate>
		<UnitOfMeasurement>
		<Code>MI</Code>
		</UnitOfMeasurement>
		<LocationSearchCriteria>
		<SearchOption>
		<OptionType>
		<Code>01</Code>
		</OptionType>
		<OptionCode>
		<Code>018</Code>
		</OptionCode>
		</SearchOption>
		<MaximumListSize>6</MaximumListSize>
		<SearchRadius>50</SearchRadius>
		</LocationSearchCriteria>
		</LocatorRequest>';

		$xmlRequest 		= apply_filters( 'ph_ups_access_point_xml_request', $xmlRequest, $settings );
		$transient			= 'ph_ups_access_point' . md5( $xmlRequest );
		$cached_response	= get_transient( $transient );
		$response			= $cached_response;

		if( empty($cached_response) ) {
			try{
				$response = wp_remote_post( $this->endpoint,
					array(
						'timeout'   => 70,
						'sslverify' => $this->ssl_verify,
						'body'      => $xmlRequest
					)
				);
			}catch(Exception $e){
					// do nothing
			}

				// Handle WP Error
			if( is_wp_error($response) ) {
				$wp_error_message = 'Error Code : '.$response->get_error_code().'<br/>Error Message : '. $response->get_error_message();
			}

			if( ! empty($wp_error_message) ) {
				return array();
			}
		}

		$locators = array();
		$full_address = array();
		$drop_locations = array();

		libxml_use_internal_errors(TRUE);
		$xml = simplexml_load_string( '<root>' . preg_replace('/<\?xml.*\?>/','', $response['body'] ) . '</root>' );
		if(isset($xml->LocatorResponse->SearchResults->DropLocation)){
			$drop_locations = ($xml->LocatorResponse->SearchResults->DropLocation);
			if( empty($cached_response) )	set_transient( $transient, $response, 7200 );
		}

		if(!empty($drop_locations)){
			foreach($drop_locations as $drop_location){

				$locator_consignee_name	=	substr( (string)$drop_location->AddressKeyFormat->ConsigneeName .', '. (string)$drop_location->AddressKeyFormat->AddressLine .', '. (string)$drop_location->AddressKeyFormat->PoliticalDivision2 .', '. (string)$drop_location->AddressKeyFormat->PostcodePrimaryLow , 0, 70 );
				$drop_location_data							=	new stdClass();
				$drop_location_data->LocationID				=	$drop_location->LocationID;
				$drop_location_data->AddressKeyFormat		=	$drop_location->AddressKeyFormat;
				$drop_location_data->AccessPointInformation	=	$drop_location->AccessPointInformation;
				$locator_full_address[$locator_consignee_name] = json_encode($drop_location_data);
				$locators[] = $locator_consignee_name;
			}
		}

		$locator='<div class="edit_address"><strong>Access Point Location:</strong><select id="shipping_accesspoint" name="shipping_accesspoint" class="select">';
		$locator .=	"<option value=''>". __('Select Access Point Location', 'ups-woocommerce-shipping') ."</option>";

		if(!empty($locators)){
			foreach ($locators as $access_point_locator){

				if($selected_accesspoint_locator == $access_point_locator){
					$locator .= "<option selected='selected' value='" . $locator_full_address[$access_point_locator] . "'>" .$access_point_locator ."</option>";
				}
				else{
					$locator .= "<option value='" . $locator_full_address[$access_point_locator] . "'>" .$access_point_locator ."</option>";
				}

			}
		}

		$locator .=	'</select></div>';
		$array['#shipping_accesspoint'] = $locator;
		
		echo $array['#shipping_accesspoint'];

	}

	function ph_save_access_point_location( $post_id ){
		if(isset($_POST[ 'shipping_accesspoint' ])){
			update_post_meta( $post_id, '_shipping_accesspoint', wc_clean( $_POST[ 'shipping_accesspoint' ] ) );
		}	
	}

	private function set_origin_country_state(){
		$ups_origin_country_state 		= isset( $this->settings['origin_country_state'] ) ? $this->settings['origin_country_state'] : '';
		if ( strstr( $ups_origin_country_state, ':' ) ) :
			// WF: Following strict php standards.
			$origin_country_state_array 	= explode(':',$ups_origin_country_state);
			$this->origin_country 				= current($origin_country_state_array);
			$origin_country_state_array 	= explode(':',$ups_origin_country_state);
			$origin_state   				= end($origin_country_state_array);
		else :
			$this->origin_country = $ups_origin_country_state;
			$origin_state   = '';
		endif;

		$this->origin_state = ( isset( $origin_state ) && !empty( $origin_state ) ) ? $origin_state : ( isset($this->settings['origin_custom_state']) ? $this->settings['origin_custom_state'] : '' );
	}

	function wf_add_ups_metabox() {

		global $post;

		if ( in_array( $post->post_type, array('shop_order') ) ) {

			if( $this->disble_ups_print_label == 'yes' ) {
				return;
			}

			if ( !$post ) return;

			$this->editingPost 	= $post;

			$order = $this->wf_load_order( $this->editingPost->ID );

			if ( !$order ) return; 
			
			add_meta_box( 'CyDUPS_metabox', __( 'UPS Shipment Label', 'ups-woocommerce-shipping' ), array( $this, 'wf_ups_metabox_content' ), 'shop_order', 'advanced', 'default' );
		}
	}

	function wf_ups_metabox_content() {

		$post = isset($this->editingPost) ? $this->editingPost : '';

		if ( !$post || !in_array( $post->post_type, array('shop_order') ) ) {
			return;
		}
		
		$shipmentId 					= '';
		$order 							= $this->wf_load_order( $post->ID );
		$shipping_service_data			= $this->wf_get_shipping_service_data( $order );
		$default_service_type 			= $shipping_service_data['shipping_service'];
		$multiship 						= get_post_meta( $post->ID, '_multiple_shipping', true);

		$created_shipments_details_array 	= get_post_meta( $post->ID, 'ups_created_shipments_details_array', true );
		if( empty( $created_shipments_details_array ) ) {		
			
			
			$download_url = admin_url( '/?wf_ups_shipment_confirm='.base64_encode( $shipmentId.'|'.$post->ID ) );
			$stored_packages	=	get_post_meta( $post->ID, '_wf_ups_stored_packages', true );
			
			if(empty($stored_packages)	&&	!is_array($stored_packages)){
				echo '<strong>'.__( 'Step 1: Auto generate packages.', 'ups-woocommerce-shipping' ).'</strong></br>';
				?>
				<a class="button button-primary tips ups_generate_packages" href="<?php echo admin_url( '/?phupsgp='.base64_encode( $shipmentId.'|'.$post->ID ) ); ?>" data-tip="<?php _e( 'Generate Packages', 'ups-woocommerce-shipping' ); ?>"><?php _e( 'Generate Packages', 'ups-woocommerce-shipping' ); ?></a><hr style="border-color:#0074a2">
				<?php
			}else{
				echo '<strong>'.__( 'Step 2: Initiate your shipment.', 'ups-woocommerce-shipping' ).'</strong></br>';

				echo '<ul>';
				
				/*if($this->ground_freight){
					echo '<li><label for="ups_gfp_shipment"><input type="checkbox" style="" id="ups_gfp_shipment" name="ups_gfp_shipment" class="">' . __('GFP Shipment', 'ups-woocommerce-shipping') . '</label><img class="help_tip" style="float:none;" data-tip="'.__( 'Ground reight Pricing (GFP)', 'ups-woocommerce-shipping' ).'" src="'.WC()->plugin_url().'/assets/images/help.png" height="16" width="16" /></li>';
				}*/

				// If freight is enabled
				if($this->enable_freight) {
	
					$freight_holiday_pickup			= ($this->freight_holiday_pickup) ? 'checked' : '';
					$freight_residential_pickup		= ($this->freight_residential_pickup) ? 'checked' : '';
					$freight_inside_pickup			= ($this->freight_inside_pickup) ? 'checked' : '';
					$freight_weekend_pickup			= ($this->freight_weekend_pickup) ? 'checked' : '';
					$freight_liftgate_pickup		= ($this->freight_liftgate_pickup) ? 'checked' : '';
					$freight_limitedaccess_pickup	= ($this->freight_limitedaccess_pickup) ? 'checked' : '';
					$freight_holiday_delivery		= ($this->freight_holiday_delivery) ? 'checked' : '';
					$freight_inside_delivery		= ($this->freight_inside_delivery) ? 'checked' : '';
					$freight_call_before_delivery	= ($this->freight_call_before_delivery) ? 'checked' : '';
					$freight_weekend_delivery		= ($this->freight_weekend_delivery) ? 'checked' : '';
					$freight_liftgate_delivery		= ($this->freight_liftgate_delivery) ? 'checked' : '';
					$freight_limitedaccess_delivery	= ($this->freight_limitedaccess_delivery) ? 'checked' : '';

					echo '<h4>'.__( 'UPS Freight Options' , 'ups-woocommerce-shipping').': </h4>';
					echo 	'<li>
								<label for="FreightPackagingType">Freight Packaging Type: </label>';
								echo '<select id="FreightPackagingType" name="FreightPackagingType" class="" style="width:20%">';
									
								foreach($this->freight_package_type_code_list as $pcode=>$pname) {

									if ($this->freight_packaging_type == $pcode) {
										echo "<option value='$pcode' selected> $pname </option>" ;
									} else {
										echo "<option value='$pcode' > $pname </option>" ;
									}
									
								}
								echo '</select>';
								echo '&nbsp;&nbsp;&nbsp;Freight Class: <select id="FreightClass" name="FreightClass" class="" style="width:20%">';
								
								foreach($this->freight_class_list as $fcode) {

									if ( $this->freight_class == $fcode ) {
										echo "<option value='$fcode' selected> $fcode </option>" ;
									} else {
										echo "<option value='$fcode' > $fcode </option>" ;
									}
									
								}
								echo '</select>';
					echo 	'</li>';

					echo '<br/>';

					echo 	'<li style="width: 75%;">
								<span style="text-align:left; display: block; float: left;">
									<label for="HolidayPickupIndicator"><input type="checkbox" style="" id="HolidayPickupIndicator" name="HolidayPickupIndicator" class="" '.$freight_holiday_pickup.'>' . __('Request Holiday Pickup', 'ups-woocommerce-shipping') . '</label><img class="help_tip" style="float:none;" data-tip="This indicates that the shipment requires a holiday pickup." src="'.WC()->plugin_url().'/assets/images/help.png" height="16" width="16" />
								</span>
								<span style="text-align:left; display: block; margin-left: 320px;">
									<label for="HolidayDeliveryIndicator"><input type="checkbox" style="" id="HolidayDeliveryIndicator" name="HolidayDeliveryIndicator" class="" '.$freight_holiday_delivery.'>' . __('Request Holiday Delivery', 'ups-woocommerce-shipping') . '</label><img class="help_tip" style="float:none;" data-tip="This indicates that the shipment requires a holiday delivery." src="'.WC()->plugin_url().'/assets/images/help.png" height="16" width="16" />
								</span>
							</li>';

					echo 	'<li style="width: 75%;">
								<span style="text-align:left; display: block; float: left;">
									<label for="InsidePickupIndicator"><input type="checkbox" style="" id="InsidePickupIndicator" name="InsidePickupIndicator" class="" '.$freight_inside_pickup.'>' . __('Request Inside Pickup', 'ups-woocommerce-shipping') . '</label><img class="help_tip" style="float:none;" data-tip="This indicates that the shipment requires an inside pickup." src="'.WC()->plugin_url().'/assets/images/help.png" height="16" width="16" />
								</span>
								<span style="text-align:left; display: block; margin-left: 320px;">
									<label for="InsideDeliveryIndicator"><input type="checkbox" style="" id="InsideDeliveryIndicator" name="InsideDeliveryIndicator" class="" '.$freight_inside_delivery.'>' . __('Request Inside Delivery', 'ups-woocommerce-shipping') . '</label><img class="help_tip" style="float:none;" data-tip="This indicates that the shipment requires an inside delivery." src="'.WC()->plugin_url().'/assets/images/help.png" height="16" width="16" />
								</span>
							</li>';

					echo 	'<li style="width: 75%;">
								<span style="text-align:left; display: block; float: left;">
									<label for="ResidentialPickupIndicator"><input type="checkbox" style="" id="ResidentialPickupIndicator" name="ResidentialPickupIndicator" class="" '.$freight_residential_pickup.'>' . __('Request Residential Pickup', 'ups-woocommerce-shipping') . '</label><img class="help_tip" style="float:none;" data-tip="This indicates that the shipment requires a residential pickup" src="'.WC()->plugin_url().'/assets/images/help.png" height="16" width="16" />
								</span>
								<span style="text-align:left; display: block; margin-left: 320px;">
									<label for="CallBeforeDeliveryIndicator"><input type="checkbox" style="" id="CallBeforeDeliveryIndicator" name="CallBeforeDeliveryIndicator" class="" '.$freight_call_before_delivery.'>' . __('Call Before Delivery', 'ups-woocommerce-shipping') . '</label><img class="help_tip" style="float:none;" data-tip="This indicates that the shipment is going to be delivered after calling the consignee." src="'.WC()->plugin_url().'/assets/images/help.png" height="16" width="16" />
								</span>
							</li>';

					echo 	'<li style="width: 75%;">
								<span style="text-align:left; display: block; float: left;">
									<label for="WeekendPickupIndicator"><input type="checkbox" style="" id="WeekendPickupIndicator" name="WeekendPickupIndicator" class="" '.$freight_weekend_pickup.'>' . __('Request Weekend Pickup', 'ups-woocommerce-shipping') . '</label><img class="help_tip" style="float:none;" data-tip="This indicates that the shipment requires a weekend pickup." src="'.WC()->plugin_url().'/assets/images/help.png" height="16" width="16" />
								</span>
								<span style="text-align:left; display: block; margin-left: 320px;">
									<label for="WeekendDeliveryIndicator"><input type="checkbox" style="" id="WeekendDeliveryIndicator" name="WeekendDeliveryIndicator" class="" '.$freight_weekend_delivery.'>' . __('Request Weekend Delivery', 'ups-woocommerce-shipping') . '</label><img class="help_tip" style="float:none;" data-tip="This indicates that the shipment requires a weekend delivery." src="'.WC()->plugin_url().'/assets/images/help.png" height="16" width="16" />
								</span>
							</li>';

					echo 	'<li style="width: 75%;">
								<span style="text-align:left; display: block; float: left;">
									<label for="LiftGateRequiredIndicator"><input type="checkbox" style="" id="LiftGateRequiredIndicator" name="LiftGateRequiredIndicator" class="" '.$freight_liftgate_pickup.'>' . __('Request Lift Gate for Pickup', 'ups-woocommerce-shipping') . '</label><img class="help_tip" style="float:none;" data-tip="This indicates that the shipment requires a lift gate for pickup." src="'.WC()->plugin_url().'/assets/images/help.png" height="16" width="16" />
								</span>
								<span style="text-align:left; display: block; margin-left: 320px;">
									<label for="LiftGateDeliveryIndicator"><input type="checkbox" style="" id="LiftGateDeliveryIndicator" name="LiftGateDeliveryIndicator" class="" '.$freight_liftgate_delivery.'>' . __('Request Lift Gate for Delivery', 'ups-woocommerce-shipping') . '</label><img class="help_tip" style="float:none;" data-tip="This indicates that the shipment requires a lift gate for delivery." src="'.WC()->plugin_url().'/assets/images/help.png" height="16" width="16" />
								</span>
							</li>';

					echo 	'<li style="width: 75%;">
								<span style="text-align:left; display: block; float: left;">
									<label for="LimitedAccessPickupIndicator"><input type="checkbox" style="" id="LimitedAccessPickupIndicator" name="LimitedAccessPickupIndicator" class="" '.$freight_limitedaccess_pickup.'>' . __('Notify UPS For Limited Access Pickup', 'ups-woocommerce-shipping') . '</label><img class="help_tip" style="float:none;" data-tip="This indicates that there is limited access for pickups." src="'.WC()->plugin_url().'/assets/images/help.png" height="16" width="16" />
								</span>
								<span style="text-align:left; display: block; margin-left: 320px;">
									<label for="LimitedAccessDeliveryIndicator"><input type="checkbox" style="" id="LimitedAccessDeliveryIndicator" name="LimitedAccessDeliveryIndicator" class="" '.$freight_limitedaccess_delivery.'>' . __('Notify UPS For Limited Access Delivery', 'ups-woocommerce-shipping') . '</label><img class="help_tip" style="float:none;" data-tip="This indicates that there is limited access for deliveries." src="'.WC()->plugin_url().'/assets/images/help.png" height="16" width="16" />
								</span>
							</li>';

					echo 	'<li><br/>
								<label for="PickupInstructions">' . __('Pickup Instructions', 'ups-woocommerce-shipping') . '</label><img class="help_tip" style="float:none;" data-tip="Here you can write some instruction regarding your pickup to UPS" src="'.WC()->plugin_url().'/assets/images/help.png" height="16" width="16" />
								<br/>
								<textarea style="width:70%;" id="PickupInstructions" name="PickupInstructions" class="">'.$this->freight_pickup_inst.'</textarea>
							</li>';

					echo 	'<li>
								<label for="DeliveryInstructions">' . __('Delivery Instructions', 'ups-woocommerce-shipping') . '</label><img class="help_tip" style="float:none;" data-tip="Here you can write some instruction regarding your delivery to UPS" src="'.WC()->plugin_url().'/assets/images/help.png" height="16" width="16" />
								<br/>
								<textarea style="width:70%;" id="DeliveryInstructions" name="DeliveryInstructions" class="">'.$this->freight_delivery_inst.'</textarea>
							</li>';

				}
				echo '<li>';
				echo '<h4>'.__( 'Package(s)' , 'ups-woocommerce-shipping').': </h4>';
				echo '<table id="wf_ups_package_list" class="wf-shipment-package-table" style="display: block">';					
				echo '<tr>';
				echo '<th>'.__('Wt.', 'ups-woocommerce-shipping').'</br>('.$this->weight_unit.')</th>';
				echo '<th>'.__('L', 'ups-woocommerce-shipping').'</br>('.$this->dim_unit.')</th>';
				echo '<th>'.__('W', 'ups-woocommerce-shipping').'</br>('.$this->dim_unit.')</th>';
				echo '<th>'.__('H', 'ups-woocommerce-shipping').'</br>('.$this->dim_unit.')</th>';
				echo '<th>'.__('Insur.', 'ups-woocommerce-shipping');
				echo '<img class="help_tip" style="float:none;" data-tip="'.__( "<div style='text-align :left;'>* Leave as it is if you want to go for Default Insurance Value.<br/>* Enter amount manually if you want to provide Customized Insurance. <br/>* Keep it blank if you do not want insurance for particular package.</div>", "ups-woocommerce-shipping" ).'" src="'.WC()->plugin_url().'/assets/images/help.png" height="16" width="16" />';
				echo '</th>';


				echo '<th>';
				echo __('Service', 'ups-woocommerce-shipping');
				echo '<img class="help_tip" style="float:none;" data-tip="'.__( 'Contact UPS for more info on this services.', 'ups-woocommerce-shipping' ).'" src="'.WC()->plugin_url().'/assets/images/help.png" height="16" width="16" />';
				echo '</th>';
				echo '<th>';
					_e('Remove', 'ups-woocommerce-shipping');
					echo '<img class="help_tip" style="float:none;" data-tip="'.__( 'Remove UPS generated packages.', 'ups-woocommerce-shipping' ).'" src="'.WC()->plugin_url().'/assets/images/help.png" height="16" width="16" />';
				echo '</th>';
				echo '<th>&nbsp;</th>';
				echo '</tr>';
				foreach($stored_packages as $stored_package_key	=>	$stored_package){
					$dimensions	=	$this->get_dimension_from_package($stored_package);
					
					if(is_array($dimensions)){
						?>
						<tr>
							<td><input type="text" id="ups_manual_weight" name="ups_manual_weight[]" size="4" value="<?php echo $dimensions['Weight'];?>" /></td>     
							<td><input type="text" id="ups_manual_length" name="ups_manual_length[]" size="4" value="<?php echo $dimensions['Length'];?>" /></td>
							<td><input type="text" id="ups_manual_width" name="ups_manual_width[]" size="4" value="<?php echo $dimensions['Width'];?>" /></td>
							<td><input type="text" id="ups_manual_height" name="ups_manual_height[]" size="4" value="<?php echo $dimensions['Height'];?>" /></td>
							<td><input type="text" id="ups_manual_insurance" name="ups_manual_insurance[]" size="4" value="<?php echo $dimensions['InsuredValue'];?>" /></td>
							<td>
								<select class="select ups_manual_service" id="ups_manual_service" name="ups_manual_service[]">
									<?php 

									if ( $multiship =='yes' ) {

										$default_service_type = apply_filters( 'ph_ups_modify_shipping_method_service', $default_service_type, $order, $stored_package_key );
									}
									
									if($this->xa_show_all==true) {

										foreach($this->ups_services as $service_code => $service_name){
											echo '<option value="'.$service_code.'" ' . selected($default_service_type, $service_code) . ' >'.$service_name.'</option>';
										}?>
										<?php if($this->enable_freight==true)  foreach($this->freight_services as $service_code => $service_name){
											echo '<option value="'.$service_code.'" ' . selected($default_service_type, $service_code) . ' >'.$service_name.'</option>';
										}
									} else if( isset($this->settings['services']) && !empty($this->settings['services']) ) {

										foreach($this->settings['services'] as $service_code => $sdata){
											if($sdata['enabled']==1){
												$service_name= (isset($this->ups_services[$service_code])) ? $this->ups_services[$service_code] : $this->freight_services[$service_code];
												echo '<option value="'.$service_code.'" ' . selected($default_service_type, $service_code) . ' >'.$service_name.'</option>';

											}
										}
									}?>									
									
								</select>
							</td>
							<td><a class="wf_ups_package_line_remove" id="<?php echo $stored_package_key; ?>">&#x26D4;</a></td>
							<td>&nbsp;</td>
						</tr>
						<?php
					}
				}
				echo '</table>';
				echo '<div id="ret_s" style="display:none">';
				echo '<h4>'.__( 'Return Package' , 'ups-woocommerce-shipping').': </h4>';
				echo '<table id="rt_wf_ups_package_list" class="wf-shipment-package-table">';                                   
				echo '<tr style="line-height: 2;">';
				echo '<th>'.__('Wt.', 'ups-woocommerce-shipping').'</br>('.$this->weight_unit.')</th>';
				echo '<th>'.__('L', 'ups-woocommerce-shipping').'</br>('.$this->dim_unit.')</th>';
				echo '<th>'.__('W', 'ups-woocommerce-shipping').'</br>('.$this->dim_unit.')</th>';
				echo '<th>'.__('H', 'ups-woocommerce-shipping').'</br>('.$this->dim_unit.')</th>';
				echo '<th>'.__('Insur.', 'ups-woocommerce-shipping');
				echo '<img class="help_tip" style="float:none;" data-tip="'.__( "<div style='text-align :left;'>* Leave as it is if you want to go for Default Insurance Value.<br/>* Enter amount manually if you want to provide Customized Insurance. <br/>* Keep it blank if you do not want insurance for particular package.</div>", "ups-woocommerce-shipping" ).'" src="'.WC()->plugin_url().'/assets/images/help.png" height="16" width="16" />';
				echo '</th>';
				echo '<th>';
				echo __('Service', 'ups-woocommerce-shipping');
				echo '<img class="help_tip" style="float:none;" data-tip="'.__( 'Contact UPS for more info on this services.', 'ups-woocommerce-shipping' ).'" src="'.WC()->plugin_url().'/assets/images/help.png" height="16" width="16" />';
				echo '</th>';
				echo '<th>&nbsp;</th>';
				echo '</tr>';

				if(is_array($dimensions)){
					?>
					<tr>
						<td><input type="text" id="rt_ups_manual_weight" name="rt_ups_manual_weight[]" size="4" value="<?php echo $dimensions['Weight'];?>" /></td>     
						<td><input type="text" id="rt_ups_manual_length" name="rt_ups_manual_length[]" size="4" value="<?php echo $dimensions['Length'];?>" /></td>
						<td><input type="text" id="rt_ups_manual_width" name="rt_ups_manual_width[]" size="4" value="<?php echo $dimensions['Width'];?>" /></td>
						<td><input type="text" id="rt_ups_manual_height" name="rt_ups_manual_height[]" size="4" value="<?php echo $dimensions['Height'];?>" /></td>
						<td><input type="text" id="rt_ups_manual_insurance" name="rt_ups_manual_insurance[]" size="4" value="<?php echo $dimensions['InsuredValue'];?>" /></td>
						<td>
							<select class="select rt_ups_manual_service" id="rt_ups_manual_service" name="rt_ups_manual_service[]">
								<?php foreach($this->ups_services as $service_code => $service_name){
									echo '<option value="'.$service_code.'" ' . selected($default_service_type, $service_code) . ' >'.$service_name.'</option>';
								}?>
								<?php if($this->enable_freight==true)  foreach($this->freight_services as $service_code => $service_name){
									echo '<option value="'.$service_code.'" ' . selected($default_service_type, $service_code) . ' >'.$service_name.'</option>';
								}?>                                                                     

							</select>
						</td>
						<td>&nbsp;</td>
					</tr>
					<?php
				} 
				echo '</table>';
				echo '</div>';

				echo '<a class="button wf-action-button wf-add-button" id="wf_ups_add_package"> Add Package</a>';
				?>
				<a class="button tips ups_generate_packages" href="<?php echo admin_url( '/?phupsgp='.base64_encode( $shipmentId.'|'.$post->ID ) ); ?>" data-tip="<?php _e( 'Re-generate all the Packages', 'ups-woocommerce-shipping' ); ?>"><?php _e( 'Generate Packages', 'ups-woocommerce-shipping' ); ?></a>
				<?php
				echo '</li>';
				?>

				<script type="text/javascript">
					jQuery(document).ready(function(){
						jQuery('input[type="checkbox"]').click(function()
						{ 
							if(jQuery('#ups_return').is(':checked'))
							{
								jQuery('#ret_s').show();

							}
							else
							{
								jQuery('#ret_s').hide();
							}
						});

						jQuery('#wf_ups_add_package').on("click", function(){
							var new_row = '<tr>';
							new_row 	+= '<td><input type="text" id="ups_manual_weight" name="ups_manual_weight[]" size="4" value="0"></td>';
							new_row 	+= '<td><input type="text" id="ups_manual_length" name="ups_manual_length[]" size="4" value="0"></td>';								
							new_row 	+= '<td><input type="text" id="ups_manual_width" name="ups_manual_width[]" size="4" value="0"></td>';
							new_row 	+= '<td><input type="text" id="ups_manual_height" name="ups_manual_height[]" size="4" value="0"></td>';
							new_row 	+= '<td><input type="text" id="ups_manual_insurance" name="ups_manual_insurance[]" size="4" value="0"></td>';
							new_row 	+= '<td>';
							new_row 	+= '<select class="select ups_manual_service" id="ups_manual_service">';
							

							<?php 
							if($this->xa_show_all==true){
								foreach($this->ups_services as $service_code => $service_name){?>
									new_row 	+= '<option value="<?php echo $service_code;?>"><?php echo $service_name;?></option>';
									<?php 
								} 
								if($this->enable_freight==true) foreach($this->freight_services as $service_code => $service_name){?>
									new_row 	+= '<option value="<?php echo $service_code;?>"><?php echo $service_name;?></option>';
								<?php }
							}
							else{
								foreach($this->settings['services'] as $service_code => $sdata){
									if($sdata['enabled']==1){
										$service_name=isset($this->ups_services[$service_code]) ? $this->ups_services[$service_code] : $this->freight_services[$service_code];
										?>
										new_row +='<option value="<?php echo $service_code;?>"><?php echo $service_name;?></option>';
										<?php
									}	
								}	
							}
							?>
						new_row 	+= '</select>';
					new_row 	+= '</td>';
					new_row 	+= '<td><a class="wf_ups_package_line_remove">&#x26D4;</a></td>';
				new_row 	+= '</tr>';

				jQuery('#wf_ups_package_list tr:last').after(new_row);
				var rt_new_row = '<tr>';
					rt_new_row      += '<td><input type="text" id="rt_ups_manual_weight" name="rt_ups_manual_weight[]" size="2" value="0"></td>';
					rt_new_row      += '<td><input type="text" id="rt_ups_manual_length" name="rt_ups_manual_length[]" size="2" value="0"></td>';                                                           
					rt_new_row      += '<td><input type="text" id="rt_ups_manual_width" name="rt_ups_manual_width[]" size="2" value="0"></td>';
					rt_new_row      += '<td><input type="text" id="rt_ups_manual_height" name="rt_ups_manual_height[]" size="2" value="0"></td>';
					rt_new_row      += '<td><input type="text" id="rt_ups_manual_insurance" name="rt_ups_manual_insurance[]" size="2" value="0"></td>';
					rt_new_row      += '<td>';
						rt_new_row      += '<select class="select rt_ups_manual_service" id="rt_ups_manual_service">';
							<?php foreach($this->ups_services as $service_code => $service_name){?>
								rt_new_row      += '<option value="<?php echo $service_code;?>"><?php echo $service_name;?></option>';
							<?php }?>
							<?php if($this->enable_freight==true) foreach($this->freight_services as $service_code => $service_name){?>
								rt_new_row      += '<option value="<?php echo $service_code;?>"><?php echo $service_name;?></option>';
							<?php }?>
						rt_new_row      += '</select>';
					rt_new_row      += '</td>';
					rt_new_row      += '<td><a class="wf_ups_package_line_remove">&#x26D4;</a></td>';
				rt_new_row      += '</tr>';
				jQuery('#rt_wf_ups_package_list tr:last').after(rt_new_row);

			});

			jQuery(document).on('click', '.wf_ups_package_line_remove', function(){
			jQuery(this).closest('tr').remove();
		});

		// To create Shipment
		jQuery("a.ups_create_shipment").on("click", function() {
			var manual_weight_arr 	= 	jQuery("input[id='ups_manual_weight']").map(function(){return jQuery(this).val();}).get();
			var manual_weight 		=	JSON.stringify(manual_weight_arr);

			var manual_height_arr 	= 	jQuery("input[id='ups_manual_height']").map(function(){return jQuery(this).val();}).get();
			var manual_height 		=	JSON.stringify(manual_height_arr);

			var manual_width_arr 	= 	jQuery("input[id='ups_manual_width']").map(function(){return jQuery(this).val();}).get();
			var manual_width 		=	JSON.stringify(manual_width_arr);

			var manual_length_arr 	= 	jQuery("input[id='ups_manual_length']").map(function(){return jQuery(this).val();}).get();
			var manual_length 		=	JSON.stringify(manual_length_arr);

			var manual_insurance_arr 	= 	jQuery("input[id='ups_manual_insurance']").map(function(){return jQuery(this).val();}).get();
			var manual_insurance 		=	JSON.stringify(manual_insurance_arr);

			var manual_service_arr	=	[];
				jQuery('.ups_manual_service').each(function(){
				manual_service_arr.push(jQuery(this).val());
			});
			var manual_service 		=	JSON.stringify(manual_service_arr);
			var rt_manual_weight_arr        =       jQuery("input[id='rt_ups_manual_weight']").map(function(){return jQuery(this).val();}).get();
			var rt_manual_weight            =       JSON.stringify(rt_manual_weight_arr);

			var rt_manual_height_arr        =       jQuery("input[id='rt_ups_manual_height']").map(function(){return jQuery(this).val();}).get();
			var rt_manual_height            =       JSON.stringify(rt_manual_height_arr);

			var rt_manual_width_arr         =       jQuery("input[id='rt_ups_manual_width']").map(function(){return jQuery(this).val();}).get();
			var rt_manual_width             =       JSON.stringify(rt_manual_width_arr);

			var rt_manual_length_arr        =       jQuery("input[id='rt_ups_manual_length']").map(function(){return jQuery(this).val();}).get();
			var rt_manual_length            =       JSON.stringify(rt_manual_length_arr);

			var rt_manual_insurance_arr     =       jQuery("input[id='rt_ups_manual_insurance']").map(function(){return jQuery(this).val();}).get();
			var rt_manual_insurance                 =       JSON.stringify(rt_manual_insurance_arr);

			var rt_manual_service_arr       =       [];
				jQuery('.rt_ups_manual_service').each(function(){
				rt_manual_service_arr.push(jQuery(this).val());
			});
			var rt_manual_service           =       JSON.stringify(rt_manual_service_arr);

			if( jQuery("#ph_ups_mrn_compliance").val() == null ){
				is_mrn = false;
			}
			else {
				is_mrn = true;
			}
			
			if( jQuery("#ph_ups_recipients_tin").val() == null ){
				recipients_tin = false;
			}
			else {
				recipients_tin = true;
			}

			if( jQuery("#ph_ups_shipto_recipients_tin").val() == null ){
				shipto_recipients_tin = false;
			}
			else {
				shipto_recipients_tin = true;
			}

			let package_key_arr = [];
			jQuery('.wf_ups_package_line_remove').each(function () {
				package_key_arr.push(this.id);
			});
			let package_key = JSON.stringify(package_key_arr);	
			if(jQuery('#ups_return').is(':checked'))
			{

				var url_location = this.href + '&weight=' + manual_weight +
				'&length=' + manual_length
				+ '&width=' + manual_width
				+ '&height=' + manual_height
				+ '&insurance=' + manual_insurance
				+ '&service=' + manual_service
				+ '&cod=' + jQuery('#ups_cod').is(':checked')
				+ '&sat_delivery=' + jQuery('#ups_sat_delivery').is(':checked')
				+ '&ic=' + jQuery('#ups_import_control').is(':checked')
				+ '&rt_weight=' + rt_manual_weight
				+ '&rt_length=' + rt_manual_length
				+ '&rt_width=' + rt_manual_width
				+ '&rt_height=' + rt_manual_height
				+ '&rt_insurance=' + rt_manual_insurance
				+ '&rt_service=' + rt_manual_service
				+ '&is_gfp_shipment=' + jQuery('#ups_gfp_shipment').is(':checked')
				+ '&is_return_label=' + jQuery('#ups_return').is(':checked')
				+ '&HolidayPickupIndicator=' + jQuery('#HolidayPickupIndicator').is(':checked')
				+ '&InsidePickupIndicator=' + jQuery('#InsidePickupIndicator').is(':checked')
				+ '&ResidentialPickupIndicator=' + jQuery('#ResidentialPickupIndicator').is(':checked')
				+ '&WeekendPickupIndicator=' + jQuery('#WeekendPickupIndicator').is(':checked')
				+ '&LiftGateRequiredIndicator=' + jQuery('#LiftGateRequiredIndicator').is(':checked')
				+ '&LimitedAccessPickupIndicator=' + jQuery('#LimitedAccessPickupIndicator').is(':checked')
				+ '&PickupInstructions=' + jQuery('#PickupInstructions').val()
				+ '&HolidayDeliveryIndicator=' + jQuery('#HolidayDeliveryIndicator').is(':checked')
				+ '&InsideDeliveryIndicator=' + jQuery('#InsideDeliveryIndicator').is(':checked')
				+ '&WeekendDeliveryIndicator=' + jQuery('#WeekendDeliveryIndicator').is(':checked')
				+ '&LiftGateDeliveryIndicator=' + jQuery('#LiftGateDeliveryIndicator').is(':checked')
				+ '&LimitedAccessDeliveryIndicator=' + jQuery('#LimitedAccessDeliveryIndicator').is(':checked')
				+ '&CallBeforeDeliveryIndicator=' + jQuery('#CallBeforeDeliveryIndicator').is(':checked')
				+ '&DeliveryInstructions=' + jQuery('#DeliveryInstructions').val()
				+ '&FreightPackagingType=' + jQuery('#FreightPackagingType').val()
				+ '&FreightClass=' + jQuery('#FreightClass').val()
				+ '&ShipmentTerms=' + jQuery('#terms_of_shipment_service').val()
				+ '&package_key=' + package_key;
				if (is_mrn == true){
					url_location += '&ups_export_compliance=' + jQuery("#ph_ups_mrn_compliance").val();
				}
				if ( recipients_tin )
				{
					url_location += '&ups_recipient_tin=' + jQuery("#ph_ups_recipients_tin").val();
				}
				if ( shipto_recipients_tin )
				{
					url_location += '&ups_shipto_recipient_tin=' + jQuery("#ph_ups_shipto_recipients_tin").val();
				}
				location.href = url_location;
			}
			else
			{

				var url_location = this.href + '&weight=' + manual_weight +
				'&length=' + manual_length
				+ '&width=' + manual_width
				+ '&height=' + manual_height
				+ '&insurance=' + manual_insurance
				+ '&service=' + manual_service
				+ '&cod=' + jQuery('#ups_cod').is(':checked')
				+ '&sat_delivery=' + jQuery('#ups_sat_delivery').is(':checked')
				+ '&ic=' + jQuery('#ups_import_control').is(':checked')
				+ '&is_gfp_shipment=' + jQuery('#ups_gfp_shipment').is(':checked')
				+ '&is_return_label=' + jQuery('#ups_return').is(':checked')
				+ '&HolidayPickupIndicator=' + jQuery('#HolidayPickupIndicator').is(':checked')
				+ '&InsidePickupIndicator=' + jQuery('#InsidePickupIndicator').is(':checked')
				+ '&ResidentialPickupIndicator=' + jQuery('#ResidentialPickupIndicator').is(':checked')
				+ '&WeekendPickupIndicator=' + jQuery('#WeekendPickupIndicator').is(':checked')
				+ '&LiftGateRequiredIndicator=' + jQuery('#LiftGateRequiredIndicator').is(':checked')
				+ '&LimitedAccessPickupIndicator=' + jQuery('#LimitedAccessPickupIndicator').is(':checked')
				+ '&PickupInstructions=' + jQuery('#PickupInstructions').val()
				+ '&HolidayDeliveryIndicator=' + jQuery('#HolidayDeliveryIndicator').is(':checked')
				+ '&InsideDeliveryIndicator=' + jQuery('#InsideDeliveryIndicator').is(':checked')				
				+ '&WeekendDeliveryIndicator=' + jQuery('#WeekendDeliveryIndicator').is(':checked')
				+ '&LiftGateDeliveryIndicator=' + jQuery('#LiftGateDeliveryIndicator').is(':checked')
				+ '&LimitedAccessDeliveryIndicator=' + jQuery('#LimitedAccessDeliveryIndicator').is(':checked')
				+ '&CallBeforeDeliveryIndicator=' + jQuery('#CallBeforeDeliveryIndicator').is(':checked')
				+ '&DeliveryInstructions=' + jQuery('#DeliveryInstructions').val()
				+ '&FreightPackagingType=' + jQuery('#FreightPackagingType').val()
				+ '&FreightClass=' + jQuery('#FreightClass').val()
				+ '&ShipmentTerms=' + jQuery('#terms_of_shipment_service').val()
				+ '&package_key=' + package_key;
				if (is_mrn == true){
					url_location += '&ups_export_compliance=' + jQuery("#ph_ups_mrn_compliance").val();
				}
				if ( recipients_tin )
				{
					url_location += '&ups_recipient_tin=' + jQuery("#ph_ups_recipients_tin").val();
				}
				if ( shipto_recipients_tin )
				{
					url_location += '&ups_shipto_recipient_tin=' + jQuery("#ph_ups_shipto_recipients_tin").val();
				}
				location.href = url_location;
			}
			return false;
		});
});

</script>
<?php

				// Rates on order page
$generate_packages_rates = get_post_meta( $_GET['post'], 'wf_ups_generate_packages_rates_response', true );

echo '<li><table id="wf_ups_service_select" class="wf-shipment-package-table" style="margin-bottom: 5px;margin-top: 15px;box-shadow:.5px .5px 5px lightgrey;">';
echo '<tr>';
echo '<th>Select Service</th>';
echo '<th style="text-align:left;padding:5px; font-size:13px;">'.__('Service Name', 'ups-woocommerce-shipping').'</th>';
if( $this->settings['enable_estimated_delivery'] == 'yes') echo '<th style="text-align:left; font-size:13px;">'.__('Delivery Time', 'ups-woocommerce-shipping').' </th>';
echo '<th style="text-align:left;font-size:13px;">'.__('Cost (', 'ups-woocommerce-shipping').get_woocommerce_currency_symbol().__(')', 'ups-woocommerce-shipping').' </th>';
echo '</tr>';

echo '<tr>';
echo "<td style = 'padding-bottom: 10px; padding-left: 15px; '><input name='wf_ups_service_choosing_radio' id='wf_ups_service_choosing_radio' value='wf_ups_individual_service' type='radio' checked='true'></td>";
echo "<td colspan = '3' style= 'padding-bottom: 10px; text-align:left;'><b>Choose Shipping Methods</b> - Select this option to choose UPS services for each package (Shipping rates will be applied accordingly).</td>";
echo "</tr>";

if( ! empty($generate_packages_rates) ) {
	$wp_date_format = get_option('date_format');
	foreach( $generate_packages_rates as $key => $rates ) {
		$ups_service = explode( ':', $rates['id']);
		echo '<tr style="padding:10px;">';
		echo "<td style = 'padding-left: 15px;'><input name='wf_ups_service_choosing_radio' id='wf_ups_service_choosing_radio' value='".end($ups_service)."' type='radio' ></td>";
		echo "<td>".$rates['label']."</td>";
		if( $this->settings['enable_estimated_delivery'] == 'yes' && isset($rates['meta_data']['Estimated Delivery'])) echo "<td>".date( $wp_date_format, strtotime($rates['meta_data']['Estimated Delivery']) )."</td>";
		echo "<td>".( ! empty($this->settings['conversion_rate']) ? $this->settings['conversion_rate'] * $rates['cost'] : $rates['cost'])."</td>";
		echo "</tr>";
	}
}

echo '</table></li>';
				//End of Rates on order page
?>
<a style="margin: 4px" class="button tips wf_ups_generate_packages_rates button-secondary" href="<?php echo admin_url( '/post.php?wf_ups_generate_packages_rates='.base64_encode($post->ID) ); ?>" data-tip="<?php _e( 'Calculate the shipping rates for UPS services.', 'wf-shipping-ups' ); ?>"><?php _e( 'Calculate Shipping Cost', 'wf-shipping-ups' ); ?></a>
<?php

//If payment method is COD, check COD by default.
$order_payment_method 	= get_post_meta( $post->ID, '_payment_method', true );
$cod_checked 			= ($order_payment_method == 'cod') ? 'checked': '';

echo '<li><label for="ups_cod"><input type="checkbox" style="" id="ups_cod" name="ups_cod" class="" '.$cod_checked.'>' . __('Collect On Delivery', 'ups-woocommerce-shipping') . '</label><img class="help_tip" style="float:none;" data-tip="'.__( 'Collect On Delivery would be applicable only for single package which may contain single or multiple product(s).', 'ups-woocommerce-shipping' ).'" src="'.WC()->plugin_url().'/assets/images/help.png" height="16" width="16" /></li>';
echo '<li><label for="ups_return"><input type="checkbox" style="" id="ups_return" name="ups_return" class="">' . __('Include Return Label', 'ups-woocommerce-shipping') . '</label><img class="help_tip" style="float:none;" data-tip="'.__( 'You can generate the return label only for single package order.', 'ups-woocommerce-shipping' ).'" src="'.WC()->plugin_url().'/assets/images/help.png" height="16" width="16" /></li>';

echo '<li><label for="ups_sat_delivery"><input type="checkbox" style="" id="ups_sat_delivery" name="ups_sat_delivery" class="">' . __('Saturday Delivery', 'ups-woocommerce-shipping') . '</label><img class="help_tip" style="float:none;" data-tip="'.__( 'Saturday Delivery from UPS allows you to stretch your business week to Saturday', 'ups-woocommerce-shipping' ).'" src="'.WC()->plugin_url().'/assets/images/help.png" height="16" width="16" /></li>';

//PDS-79
$import_control_settings 				= (isset($this->settings[ 'import_control_settings']) && $this->settings[ 'import_control_settings']=='yes') ? 'checked': ''; //UPS Import Control Indicator settings check box
$order_data = new WC_Order($post->ID);
if( $order_data->get_shipping_country() !== $this->origin_country ){

	echo '<li><label for="ups_import_control"><input type="checkbox" style="" id="ups_import_control" name="ups_import_control" class="ups_import_control" '.$import_control_settings. '>' . __('UPS Import Control', 'ups-woocommerce-shipping') . '</label><img class="help_tip" style="float:none;" data-tip="'.__( 'UPS Import Control allows you, as the importer, to initiate UPS shipments from another country and have those shipments delivered to your business or to an alternate location.', 'ups-woocommerce-shipping' ).'" src="'.WC()->plugin_url().'/assets/images/help.png" height="16" width="16" /></li>';

	//PDS-130
	$shipment_terms = array(
		''	   		=> __( 'NONE', 	'ups-woocommerce-shipping' ),
		'CFR'	   	=> __( 'Cost and Freight', 	'ups-woocommerce-shipping' ),
		'CIF'	   	=> __( 'Cost Insurance and Freight', 	'ups-woocommerce-shipping' ),
		'CIP'		=> __( 'Carriage and Insurance Paid', 	'ups-woocommerce-shipping' ),
		'CPT'		=> __( 'Carriage Paid To', 	'ups-woocommerce-shipping' ),
		'DAF'		=> __( 'Delivered at Frontier', 	'ups-woocommerce-shipping' ),
		'DDP' 		=> __( 'Delivery Duty Paid', 	'ups-woocommerce-shipping' ),
		'DDU' 		=> __( 'Delivery Duty Unpaid', 	'ups-woocommerce-shipping' ),
		'DEQ' 		=> __( 'Delivered Ex Quay', 	'ups-woocommerce-shipping' ),
		'DES' 		=> __( 'Delivered Ex Ship', 	'ups-woocommerce-shipping' ),
		'EXW' 		=> __( 'Ex Works', 	'ups-woocommerce-shipping' ),
		'FAS' 		=> __( 'Free Alongside Ship', 	'ups-woocommerce-shipping' ),
		'FCA' 		=> __( 'Free Carrier', 	'ups-woocommerce-shipping' ),
		'FOB' 		=> __( 'Free On Board', 	'ups-woocommerce-shipping' ),
	);
	_e('Terms of shipment : ', 'ups-woocommerce-shipping');
	echo '<select id="terms_of_shipment_service">';

	foreach ($shipment_terms as $key => $value) {

		if($key == $this->terms_of_shipment){
			echo "<option value='".$key."' selected>".$value."</option>";
		} else {
			echo "<option value='".$key."'>".$value."</option>";
		}
	}

	echo '</select>';
	echo '<img class="help_tip" style="float:none;" data-tip="'.__( 'Indicates the rights to the seller from the buyer, internationally.', 'ups-woocommerce-shipping' ).'" src="'.WC()->plugin_url().'/assets/images/help.png" height="16" width="16" />';
}

$items_cost = $order_data->get_subtotal();
$order_currency = $order_data->get_currency();
$mrn_post_currency = "EUR";
$woocommerce_currency_conversion_rate = get_option('woocommerce_multicurrency_rates');

if($order_currency != $mrn_post_currency && !empty($woocommerce_currency_conversion_rate)){

	$mrn_currency_rate = $woocommerce_currency_conversion_rate[$mrn_post_currency];
	$order_currency_rate = $woocommerce_currency_conversion_rate[$order_currency];

	$conversion_rate = $mrn_currency_rate / $order_currency_rate;
	$items_cost *= $conversion_rate;
}

$shipping_country = $order_data->get_shipping_country();

$wc_countries = new WC_Countries();

if( $this->origin_country == "DE" && ( $items_cost > 1000 && $shipping_country != "DE" )) {
	$export_declaration_required = 1;
	$mrn_export_compliance = get_post_meta($post->ID, '_ph_ups_export_compliance',true);
	
	?>
	<li>
		<?php echo __( 'Movement Reference Number (MRN)', 'ups-woocommerce-shipping' ); echo '<img class="help_tip" style="float:none;" data-tip="'.__( 'Export Declaration (MRN) for international shippment from Germany', 'ups-woocommerce-shipping' ).'" src="'.WC()->plugin_url().'/assets/images/help.png" height="16" width="16" />';?> :
		<input type="text" name="ph_ups_mrn_compliance" value="<?php echo $mrn_export_compliance;?>" id="ph_ups_mrn_compliance">
	</li>
	<?php
}

if( $this->recipients_tin && $this->commercial_invoice )
{

	$recipients_tin = get_post_meta($post->ID, 'ph_ups_shipping_tax_id_num',true);
	$shipto_recipients_tin 	= get_post_meta($post->ID, 'ph_ups_ship_to_tax_id_num',true);

	// Meta to check Ship To Different Address is enabled or not
	$ship_to_different_address 	= get_post_meta($post->ID, 'ph_ups_ship_to_different_address',true);

	// If Ship To Different Address is not enabled, assign Billing TIN to Shipping TIN
	if( !$ship_to_different_address ) {
		$shipto_recipients_tin = $recipients_tin;
	}

	?>
	<li>
		<?php echo __( 'Recipients Billing Tax Identification Number', 'ups-woocommerce-shipping' ); echo '<img class="help_tip" style="float:none;" data-tip="'.__( 'Recipients Billing Tax Identification Number will be added to International forms', 'ups-woocommerce-shipping' ).'" src="'.WC()->plugin_url().'/assets/images/help.png" height="16" width="16" />';?> :
		<input type="text" name="ph_ups_recipients_tin" value="<?php echo $recipients_tin;?>" id="ph_ups_recipients_tin">
	</li>

	<li>
		<?php echo __( 'Recipients Shipping Tax Identification Number', 'ups-woocommerce-shipping' ); echo '<img class="help_tip" style="float:none;" data-tip="'.__( 'Recipients Shipping Tax Identification Number will be added to International forms', 'ups-woocommerce-shipping' ).'" src="'.WC()->plugin_url().'/assets/images/help.png" height="16" width="16" />';?> :
		<input type="text" name="ph_ups_shipto_recipients_tin" value="<?php echo $shipto_recipients_tin;?>" id="ph_ups_shipto_recipients_tin">
	</li>
	<?php
}
?>
<li>
	<a class="button button-primary tips ups_create_shipment" href="<?php echo $download_url; ?>" data-tip="<?php _e( 'Confirm Shipment', 'ups-woocommerce-shipping' ); ?>"><?php _e( 'Confirm Shipment', 'ups-woocommerce-shipping' ); ?></a><hr style="border-color:#0074a2">
</li>
<?php

}

?>
<script type="text/javascript">

	// jQuery("a.ups_generate_packages").on("click", function() {
	// 	location.href = this.href;
	// });

	// To get rates on order page
	jQuery("a.wf_ups_generate_packages_rates").one("click", function() {

		let package_key_arr = [];

		jQuery('.wf_ups_package_line_remove').each(function () {
			package_key_arr.push(this.id);
		});

		let package_key = JSON.stringify(package_key_arr);

		jQuery(this).click(function () { return false; });
		var manual_weight_arr		= 	jQuery("input[id='ups_manual_weight']").map(function(){return jQuery(this).val();}).get();
		var manual_height_arr		= 	jQuery("input[id='ups_manual_height']").map(function(){return jQuery(this).val();}).get();
		var manual_width_arr		= 	jQuery("input[id='ups_manual_width']").map(function(){return jQuery(this).val();}).get();
		var manual_length_arr		= 	jQuery("input[id='ups_manual_length']").map(function(){return jQuery(this).val();}).get();
		var manual_insurance_arr 	= 	jQuery("input[id='ups_manual_insurance']").map(function(){return jQuery(this).val();}).get();

		location.href = this.href + '&weight=' + manual_weight_arr +
		'&length=' + manual_length_arr
		+ '&width=' + manual_width_arr
		+ '&height=' + manual_height_arr
		+ '&insurance=' + manual_insurance_arr
		+ '&package_key=' + package_key;
		return false;
	});
	//End of jQuery for getting the rates

	//For sitching between the services of get rates and services after every generated packages
	jQuery(document).ready( function() {

		jQuery(document).on("change", "#wf_ups_service_choosing_radio", function(){

			if (jQuery("#wf_ups_service_choosing_radio:checked").val() == 'wf_ups_individual_service') {
				jQuery(".ups_manual_service").prop("disabled", false);
			} else {
				jQuery(".ups_manual_service").val(jQuery("#wf_ups_service_choosing_radio:checked").val()).change();
				jQuery(".ups_manual_service").prop("disabled", true);  
			}
		});
	});
	//End For sitching between the services of get rates and services after every generated packages

</script>
<?php

} else {

	$ups_label_details_array 				= get_post_meta( $post->ID, 'ups_label_details_array', true );
	$ups_commercial_invoice_details 		= get_post_meta( $post->ID, 'ups_commercial_invoice_details', true );
	$ups_return_commercial_invoice_details 	= get_post_meta( $post->ID, 'ups_return_commercial_invoice_details', true );
	$ups_control_log_receipt 				= get_post_meta( $post->ID, 'ups_control_log_receipt', true );
	$ups_dangerous_goods_manifest_required	= get_post_meta( $post->ID, 'ph_ups_dangerous_goods_manifest_required', true );
	$ups_dangerous_goods_manifest_data		= get_post_meta( $post->ID, 'ph_ups_dangerous_goods_manifest_data', true );
	$ups_dangerous_goods_image				= get_post_meta( $post->ID, 'ups_dangerous_goods_image', true );

	if(!empty($ups_label_details_array) && is_array($ups_label_details_array)) {

		//For displaying the products name with label on order page
		$packages = $this->xa_get_meta_key( $order, '_wf_ups_stored_packages', true, 'order');

		foreach ( $created_shipments_details_array as $shipmentId => $created_shipments_details ){

			if( "yes" == $this->show_label_in_browser ) {
				$target_val = "_blank";
			}
			else {
				$target_val = "_self";
			}

			// Multiple labels for each package.
			$index = 0;

			if( !empty($ups_label_details_array[$shipmentId]) ) {

				foreach ( $ups_label_details_array[$shipmentId] as $ups_label_details ) {

					$label_extn_code 	= $ups_label_details["Code"];
					$tracking_number 	= isset( $ups_label_details["TrackingNumber"] ) ? $ups_label_details["TrackingNumber"] : '';
					$download_url 		= admin_url( '/?wf_ups_print_label='.base64_encode( $shipmentId.'|'.$post->ID.'|'.$label_extn_code.'|'.$index.'|'.$tracking_number ) );
					$post_fix_label		= '';

					if( count($ups_label_details_array) > 1 ) {
						$post_fix_label = '#'.( $index + 1 );
					}

					// Stored packages will be in array format only if it has not been messed manually
					if( is_array($packages) ) {
						?>

						<table class="xa_ups_shipment_box_table" style="border:1px solid lightgray;margin: 5px;margin-top: 5px;box-shadow:.5px .5px 5px lightgrey; width:100%;">
							<caption style="font-size: 16px; color:#E74C3C;">Package Details</caption>
							<tr>
								<th style = "font-size:16px;">Weight</th>
								<th style = "font-size:16px;">Length</th>
								<th style = "font-size:16px;">Width</th>
								<th style = "font-size:16px;">Height</th>
								<th style = "font-size:16px;">Products ( Name x Quantity )</th>
							</tr>

							<?php
							$package = array_shift($packages);
							$package_weight = $package['Package']['PackageWeight']['Weight'].' '.(isset($package['Package']['PackageWeight']['UnitOfMeasurement'])?$package['Package']['PackageWeight']['UnitOfMeasurement']['Code']:$this->weight_unit);
							$package_length = (! empty($package['Package']['Dimensions']) && !empty($package['Package']['Dimensions']['Length']) ) ? ( $package['Package']['Dimensions']['Length'].' '.(isset($package['Package']['Dimensions']['UnitOfMeasurement'])?$package['Package']['Dimensions']['UnitOfMeasurement']['Code']:'') ) : 0;
							$package_width = (! empty($package['Package']['Dimensions']) && !empty($package['Package']['Dimensions']['Width']) ) ? ( $package['Package']['Dimensions']['Width'].' '.(isset($package['Package']['Dimensions']['UnitOfMeasurement'])?$package['Package']['Dimensions']['UnitOfMeasurement']['Code']:'') ) : 0;
							$package_height = (! empty($package['Package']['Dimensions']) && !empty($package['Package']['Dimensions']['Height']) ) ? ( $package['Package']['Dimensions']['Height'].' '.(isset($package['Package']['Dimensions']['UnitOfMeasurement'])?$package['Package']['Dimensions']['UnitOfMeasurement']['Code']:'') ) : 0;

							echo "<td style='text-align:center; padding: 5px; font-size:16px;'>".$package_weight."</td>";
							echo "<td style='text-align:center; padding: 5px; font-size:16px;'>".$package_length."</td>";
							echo "<td style='text-align:center; padding: 5px; font-size:16px;'>".$package_width."</td>";
							echo "<td style='text-align:center; padding: 5px; font-size:16px;'>".$package_height."</td>";
							$first_item_in_package = ( isset($package['Package']['items']) && is_array($package['Package']['items']) ) ? current($package['Package']['items']) : null;
									if( ! empty($first_item_in_package) ) { 	// Check whether items are set in packages or not, current has been
										$products_in_package = null;
										$product_quantity	= array();
										$products_name		= array();
										foreach( $package['Package']['items'] as $product) {
											$product_quantity[$product->get_id()] = isset($product_quantity[$product->get_id()]) ? ( $product_quantity[$product->get_id()] +1 ) : 1;
											$products_name[$product->get_id()] = ( WC()->version > '2.7') ? $product->get_name() : $product->post->post_title;
										}
										foreach( $products_name as $product_id => $product_name) {
											if( ! empty($products_in_package) ) {
												$next_product_in_package = '<a style ="text-decoration:none;" href = "'.admin_url("post.php?post=$product_id&action=edit").'" >'.$product_name.'</a> X '.$product_quantity[$product_id];
												$products_in_package = $products_in_package.', '.$next_product_in_package;
											}
											else {
												$products_in_package = '<a style ="text-decoration:none;" href = "'.admin_url("post.php?post=$product_id&action=edit").'" >'.$product_name.'</a> X '.$product_quantity[$product_id];
											}
										}

										echo "<td style='text-align:center; padding: 5px; font-size:16px;'>".$products_in_package."</td>";
									}

									echo "</table>";
								}
								?>
								<br />
								
								<?php
								if( isset($ups_label_details["Type"]) && $ups_label_details["Type"] == 'FREIGHT' )
								{
									?>
									<strong><?php _e( 'Tracking No: ', 'ups-woocommerce-shipping' ); ?></strong><a href="http://wwwapps.ups.com/WebTracking/track?track=yes&trackNums=<?php echo $shipmentId ?>" target="_blank"><?php echo $shipmentId ?></a>
									<?php 
								}else{
									?>
									<strong><?php _e( 'Tracking No: ', 'ups-woocommerce-shipping' ); ?></strong><a href="http://wwwapps.ups.com/WebTracking/track?track=yes&trackNums=<?php echo $ups_label_details["TrackingNumber"] ?>" target="_blank"><?php echo $ups_label_details["TrackingNumber"] ?></a>
									<?php 
								}
								?>

								<br /><a style="margin-top: 7px" class="button button-primary tips" href="<?php echo $download_url; ?>" data-tip="<?php _e( 'Print Label ', 'ups-woocommerce-shipping' );echo $post_fix_label; ?>" target="<?php echo $target_val; ?>"><?php _e( 'Print Label ', 'ups-woocommerce-shipping' );echo $post_fix_label ?></a>
								<br /> <br/>
								<?php						
							// Return Label Link
								if(isset($created_shipments_details['return'])&&!empty($created_shipments_details['return'])){
								$return_shipment_id = current(array_keys($created_shipments_details['return'])); // only one return label is considered now
								$ups_return_label_details_array = get_post_meta( $post->ID, 'ups_return_label_details_array', true );
								if( is_array($ups_return_label_details_array) && isset($ups_return_label_details_array[$return_shipment_id]) ){// check for return label accepted data
									$ups_return_label_details = $ups_return_label_details_array[$return_shipment_id];
									if( is_array($ups_return_label_details) ){
										$ups_return_label_detail = current($ups_return_label_details);
										$label_index=0;// as we took only one label so index is zero
										$return_download_url = admin_url( '/?wf_ups_print_label='.base64_encode( $return_shipment_id.'|'.$post->ID.'|'.$label_extn_code.'|'.$label_index.'|return' ) );
										?>
										<strong><?php _e( 'Tracking No: ', 'ups-woocommerce-shipping' ); ?></strong><a href="http://wwwapps.ups.com/WebTracking/track?track=yes&trackNums=<?php echo $ups_return_label_detail["TrackingNumber"] ?>" target="_blank"><?php echo $ups_return_label_detail["TrackingNumber"] ?></a><br/>
										<a class="button button-primary tips" href="<?php echo $return_download_url; ?>" data-tip="<?php _e( 'Print Return Label ', 'ups-woocommerce-shipping' );echo $post_fix_label; ?>" target="<?php echo $target_val; ?>"><?php _e( 'Print Return Label ', 'ups-woocommerce-shipping' );echo $post_fix_label ?></a><hr style="border-color:#0074a2">
										<?php
									}
								}
							}
							
							// EOF Return Label Link						
							$index = $index + 1;
						}
					}

					if(isset($ups_commercial_invoice_details[$shipmentId])){
						if( $this->nafta_co_form || $this->eei_data )
						{
							echo '<a class="button button-primary tips" target="'.$target_val.'" href="'.admin_url( '/?wf_ups_print_commercial_invoice='.base64_encode($post->ID.'|'.$shipmentId)).'" data-tip="'.__('Downloads International Forms', 'ups-woocommerce-shipping').'">'.__('International Forms', 'ups-woocommerce-shipping').'</a><br><br>';		
						}else{
							echo '<a class="button button-primary tips" target="'.$target_val.'" href="'.admin_url( '/?wf_ups_print_commercial_invoice='.base64_encode($post->ID.'|'.$shipmentId)).'" data-tip="'.__('Downloads Commercial Invoice', 'ups-woocommerce-shipping').'">'.__('Commercial Invoice', 'ups-woocommerce-shipping').'</a><br><br>';
						}
					}

					if( $this->dangerous_goods_manifest && isset($ups_dangerous_goods_manifest_data[$shipmentId]) && !empty($ups_dangerous_goods_manifest_data[$shipmentId]) && $ups_dangerous_goods_manifest_required ) {

						echo '<a class="button button-primary tips" target="_blank" href="'.admin_url( '/?ph_ups_dgm='.base64_encode($post->ID.'|'.$shipmentId)).'" data-tip="'.__('Downloads UPS Dangerous Goods Manifest', 'ups-woocommerce-shipping').'">'.__('Dangerous Goods Manifest', 'ups-woocommerce-shipping').'</a><br><br>';
					}

					//PDS-129
					if( $this->dangerous_goods_signatoryinfo && isset($ups_dangerous_goods_image[$shipmentId]) && !empty($ups_dangerous_goods_image[$shipmentId]) ) {
						
						echo '<a class="button button-primary tips" target="_blank" href="'.admin_url( '/?ph_ups_dangerous_goods_signatoryinfo='.base64_encode($post->ID.'|'.$shipmentId)).'" data-tip="'.__('Downloads UPS Dangerous Goods Signatoryinfo', 'ups-woocommerce-shipping').'">'.__('Dangerous Goods Signatory Info', 'ups-woocommerce-shipping').'</a><br><br>';
					}

					if(isset($ups_control_log_receipt[$shipmentId])){
						echo '<a class="button button-primary tips" target="_blank" href="'.admin_url( '/?ph_ups_print_control_log_receipt='.base64_encode($post->ID.'|'.$shipmentId)).'" data-tip="'.__('Print Control Log Receipt', 'ups-woocommerce-shipping').'">'.__('Control Log Receipt', 'ups-woocommerce-shipping').'</a><br><br>';
					}
					if(isset($created_shipments_details['return'])&&!empty($created_shipments_details['return'])){
						$return_shipment_id = current(array_keys($created_shipments_details['return']));
						
						if(isset($ups_return_commercial_invoice_details[$return_shipment_id])){
							echo '<a class="button button-primary tips" target="'.$target_val.'" href="'.admin_url( '/?wf_ups_print_return_commercial_invoice='.base64_encode($post->ID.'|'.$return_shipment_id)).'" data-tip="'.__('Print Return Commercial Invoice', 'ups-woocommerce-shipping').'">'.__('Return Commercial Invoice', 'ups-woocommerce-shipping').'</a></br><br>';
						}
					}
				}

				// For Create Return label button if it has not been created at the time of label creation
				if( empty($created_shipments_details_array[$shipmentId]['return']) ){
					$services = base64_encode($this->xa_get_meta_key( $order, 'xa_ups_generated_label_services', true));
					echo '<hr style="border-color:#0074a2">';
					//$generate_return_label = !empty($services) ? admin_url( "/?xa_generate_return_label=$post->ID&service=$services&rt_service=$services") : '#';
					$generate_return_label = !empty($services) ? admin_url( "/?xa_generate_return_label=$post->ID&service=$services&rt_service=$services" ) : admin_url( "/?xa_generate_return_label=$post->ID" );
					echo "<strong>";
					_e('Generate Return label : ', 'ups-woocommerce-shipping');
					echo "</strong>";
					echo '<select id="return_label_service">';
					echo	'<option value="">'.__('Select Your service').'</option>';
					foreach ($this->ups_services as $key => $value) {

						if( $key == 'US48' ) {
							continue;
						}

						echo '<option value='.$key.'>'.$value.'</option>';
					}
					echo '</select>';
					echo '<a class="button button-primary tips" data-tip="'.__('Generate Return Label').'" href ="'.$generate_return_label.'" id="generate_return_label">Generate Return label</a>';
					echo '<hr style="border-color:#0074a2">';
				}
				// End of Create Return label button if it has not been created at the time of label creation


				$void_shipment_url = admin_url( '/?wf_ups_void_shipment='.base64_encode( $post->ID ) );
				?>
				<strong><?php _e( 'Cancel the Shipment', 'ups-woocommerce-shipping' ); ?></strong></br>
				<a class="button tips" href="<?php echo $void_shipment_url; ?>" data-tip="<?php _e( 'Void Shipment', 'ups-woocommerce-shipping' ); ?>"><?php _e( 'Void Shipment', 'ups-woocommerce-shipping' ); ?></a><hr style="border-color:#0074a2">
				<?php
			}else{
				$accept_shipment_url = admin_url( '/?wf_ups_shipment_accept='.base64_encode( $post->ID ) );
				?>
				<strong><?php _e( 'Step 3: Accept your shipment.', 'ups-woocommerce-shipping' ); ?></strong></br>
				<a class="button button-primary tips" href="<?php echo $accept_shipment_url; ?>" data-tip="<?php _e('Accept Shipment', 'ups-woocommerce-shipping'); ?>"><?php _e( 'Accept Shipment', 'ups-woocommerce-shipping' ); ?></a><hr style="border-color:#0074a2">
				<?php
			}
			
		}
	}

	/**
	* Get order meta key
	* @param $post int | obj Order id or order object, Or Product id or Product Object.
	* @param $key string Meta key to fetch from order or Product
	* @param $single boolean True to get single meta key or false to get array of meta key. By default false.
	* @param $post_type string Post type order or product.
	* @return mixed Return meta key array or single.
	*/
	public function xa_get_meta_key( $post, $key, $single = false, $post_type='order' ) {
		if( ! is_object($post) ) {
			if( $post_type == 'order' )	{
				$post = wc_get_order($post);
			}
			else {
				$post = wc_get_product($post);
			}
		}

		if( WC()->version < '3.0' ) {
			return get_post_meta( $post->id, $key, $single );
		}
		else{
			return $post->get_meta( $key, $single );
		}
	}


	private function get_shop_address( $order, $ups_settings ){
		$shipper_phone_number 			= isset( $ups_settings['phone_number'] ) ? $ups_settings['phone_number'] : '';
		$attention_name 	=		isset( $ups_settings['ups_display_name'] ) ? preg_replace("/&#?[a-z0-9]+;/i","",$ups_settings['ups_display_name']) : '-';
		$company_name		=		isset( $ups_settings['ups_user_name'] ) ? preg_replace("/&#?[a-z0-9]+;/i","",$ups_settings['ups_user_name']) : '-';
		//Address standard followed in all xadapter plugins. 
		$from_address = array(
			'name'		=> $attention_name,
			'company' 	=> $company_name,
			'phone' 	=> (strlen($shipper_phone_number) < 10) ? '0000000000' :  $shipper_phone_number,
			'email' 	=> isset( $ups_settings['email'] ) ? $ups_settings['email'] : '',

			'address_1' => isset( $ups_settings['origin_addressline'] ) ? $ups_settings['origin_addressline'] : '',
			'address_2' => isset( $ups_settings['origin_addressline_2'] ) ? $ups_settings['origin_addressline_2'] : '',
			'city' 		=> isset( $ups_settings['origin_city'] ) ? $ups_settings['origin_city'] : '',
			'state' 	=> $this->origin_state,
			'country' 	=> $this->origin_country,
			'postcode' 	=> isset( $ups_settings['origin_postcode'] ) ? $ups_settings['origin_postcode'] : '',
		);
		//Filter for shipping common addon
		return apply_filters( 'wf_filter_label_from_address', $from_address , $this->wf_create_package($order) );
	}

	private function get_order_address( $order ){
		//Address standard followed in all xadapter plugins. 
		$billing_address 	= $order->get_address('billing');
		$shipping_address 	= $order->get_address('shipping');
		// Handle the address line one greater than 35 char(UPS Limit)
		$address_line_1_arr	= self::divide_sentence_based_on_char_length( $shipping_address['address_1'],35);
		$address_line_1 	= array_shift($address_line_1_arr);	// Address Line 1
		// Address Line 2
		if( ! empty($address_line_1_arr) ) {
			$address_line_2 = array_shift($address_line_1_arr);
			if( empty($address_line_1_arr) ) {
				$address_line_2 = substr( $address_line_2.' '.$shipping_address['address_2'], 0, 35 );
			}
		}
		else{
			$address_line_2 = substr( $shipping_address['address_2'], 0, 35);
		}

		$phonenummeta 	= get_post_meta( $order->get_id() , '_shipping_phone', 1);
		$phonenum 		= !empty($phonenummeta) ? $phonenummeta : $billing_address['phone'];
		$phone_number 	= ( strlen($phonenum) > 15 ) ? str_replace(' ', '', $phonenum) : $phonenum;

		return array(
			'name'		=> htmlspecialchars($shipping_address['first_name']).' '.htmlspecialchars($shipping_address['last_name']),
			'company' 	=> !empty($shipping_address['company']) ? htmlspecialchars($shipping_address['company']) : '-',
			'phone' 	=> $phone_number,
			'email' 	=> htmlspecialchars($billing_address['email']),
			'address_1'	=> htmlspecialchars($address_line_1),
			'address_2'	=> htmlspecialchars($address_line_2),
			'city' 		=> htmlspecialchars($shipping_address['city']),
			'state' 	=> htmlspecialchars($shipping_address['state']),
			'country' 	=> $shipping_address['country'],
			'postcode' 	=> $shipping_address['postcode'],
		);
	}

	/**
	 * Get the String divided into multiple sentence based on Character Length of sentence.
	 * @param $string String String or Sentence on which the Divide has to be applied.
	 * @param $length Length for the new String.
	 * @return array Array of string or sentence of given length
	 */
	public static function divide_sentence_based_on_char_length( $string, $length ){
		if( strlen($string) <= $length ) {
			return array($string);
		}
		else{
			$temp_string = null;
			$words_instring = explode( ' ', $string );
			$i =0;
			foreach( $words_instring as $word ) {
				$word = substr( $word, 0, $length );			// To handle the word of length longer than given length
				if( ! empty($new_string[$i]) ){
					$new_length = strlen( $new_string[$i].' '.$word);
					if( $new_length <= $length ) {
						$new_string[$i] .= ' '.$word;
					}
					else{
						$new_string[++$i] = $word;
					}
				}
				else{
					$new_string[$i] = $word;
				}
			}
			return $new_string;
		}
	}

	private function get_billing_address( $order ) {
		
		$billing_address 	= $order->get_address('billing');
		
		return array(
			'name'		=> htmlspecialchars($billing_address['first_name']).' '.htmlspecialchars($billing_address['last_name']),
			'company' 	=> !empty($billing_address['company']) ? htmlspecialchars($billing_address['company']) : '-',
			'phone' 	=> ( strlen($billing_address['phone']) > 15 ) ? str_replace(' ', '', $billing_address['phone']) : $billing_address['phone'],
			'email' 	=> htmlspecialchars($billing_address['email']),
			'address_1'	=> htmlspecialchars($billing_address['address_1']),
			'address_2'	=> htmlspecialchars($billing_address['address_2']),
			'city' 		=> htmlspecialchars($billing_address['city']),
			'state' 	=> htmlspecialchars($billing_address['state']),
			'country' 	=> $billing_address['country'],
			'postcode' 	=> $billing_address['postcode'],
		);
	}

	function wf_ups_shipment_confirmrequest_GFP( $order,$shipment=array() ) {
		global $post;
		
		$ups_settings 					= get_option( 'woocommerce_'.WF_UPS_ID.'_settings', null ); 
		
		// Apply filter on settings data
		$ups_settings	=	apply_filters('wf_ups_confirm_shipment_settings', $ups_settings, $order); //For previous version compatibility.
		$ups_settings	=	apply_filters('wf_ups_shipment_settings', $ups_settings, $order);
		
		// Define user set variables
		$ups_enabled					= isset( $ups_settings['enabled'] ) ? $ups_settings['enabled'] : '';
		$ups_title						= isset( $ups_settings['title'] ) ? $ups_settings['title'] : 'UPS';
		$ups_availability    			= isset( $ups_settings['availability'] ) ? $ups_settings['availability'] : 'all';
		$ups_countries       			= isset( $ups_settings['countries'] ) ? $ups_settings['countries'] : array();
		// WF: Print Label Settings.
		$print_label_type     			= isset( $ups_settings['print_label_type'] ) ? $ups_settings['print_label_type'] : 'gif';
		$ship_from_address      		= isset( $ups_settings['ship_from_address'] ) ? $ups_settings['ship_from_address'] : 'origin_address';
		// API Settings
		
		$shipper_email	 				= isset( $ups_settings['email'] ) ? $ups_settings['email'] : '';
		$ups_user_id         			= isset( $ups_settings['user_id'] ) ? $ups_settings['user_id'] : '';
		$ups_password        			= isset( $ups_settings['password'] ) ? $ups_settings['password'] : '';
		$ups_access_key      			= isset( $ups_settings['access_key'] ) ? $ups_settings['access_key'] : '';
		$ups_shipper_number  			= isset( $ups_settings['shipper_number'] ) ? $ups_settings['shipper_number'] : '';
		$ups_negotiated      			= isset( $ups_settings['negotiated'] ) && $ups_settings['negotiated'] == 'yes' ? true : false;
		// $ups_residential		        = isset( $ups_settings['residential'] ) && $ups_settings['residential'] == 'yes' ? true : false;
		
		$this->accesspoint_locator 	= (isset($this->settings[ 'accesspoint_locator']) && $this->settings[ 'accesspoint_locator']=='yes') ? true : false;
		//$this->is_hazmat_product 	= false;

		$cod						= get_post_meta($order->get_id(),'_wf_ups_cod',true);
		$sat_delivery				= get_post_meta($order->get_id(),'_wf_ups_sat_delivery',true);
		$order_total				= $order->get_total();
		$order_object				= wc_get_order($order->get_id());
		$order_sub_total			= (double) is_object($order_object) ? $order_object->get_subtotal() : 0;
		$min_order_amount_for_insurance = ! empty($ups_settings['min_order_amount_for_insurance']) ? $ups_settings['min_order_amount_for_insurance'] : 0;
		$order_currency				= $order->get_order_currency();
		
		$commercial_invoice		        = isset( $ups_settings['commercial_invoice'] ) && $ups_settings['commercial_invoice'] == 'yes' ? true : false;
		
		$billing_address_preference = $this->get_product_address_preference( $order, $ups_settings, false );
		
		if( 'billing_address' == $ship_from_address && $billing_address_preference ) { 
			$from_address 	= $this->get_order_address( $order_object );
			$to_address 	= $this->get_shop_address( $order, $ups_settings );
		}
		else {
			$from_address 	= $this->get_shop_address( $order, $ups_settings );
			$to_address 	= $this->get_order_address( $order_object );
		}

		$shipping_service_data	= $this->wf_get_shipping_service_data( $order ); 
		$shipping_method		= $shipping_service_data['shipping_method'];
		$shipping_service		= $shipping_service_data['shipping_service'];
		$shipping_service_name	= $shipping_service_data['shipping_service_name'];

		if( ($from_address['country'] == $to_address['country']) && in_array( $from_address['country'], $this->dc_domestic_countries) ){ // Delivery confirmation available at package level only for domestic shipments.
			$ship_options['delivery_confirmation_applicable']	= true;
			$ship_options['international_delivery_confirmation_applicable']	= false;
		}
		else {
			$ship_options['international_delivery_confirmation_applicable']	= true;
		}
		$package_data = $this->wf_get_package_data( $order, $ship_options, $to_address);
		if( empty( $package_data ) ) {
			$stored_package = get_post_meta( $order->get_id(), '_wf_ups_stored_packages',true);
			
			if(!isset($stored_package[0]))
				$stored_package=array($stored_package);

			if(is_array($stored_package)) {
				$package_data = $stored_package;
			} else {
				return false;
			}
		}
		
		$package_data		=	apply_filters('wf_ups_filter_label_packages',$package_data, $order);

		update_post_meta( $order->get_id(), '_wf_ups_stored_packages', $package_data);


		$shipment_requests	='';
		$all_var=get_defined_vars();	
		if( is_array($shipment) )
		{
			$contextid=$order->get_id();
			$contextvalue               =apply_filters( 'ph_ups_update_customer_context_value',$contextid);

				$from_address=apply_filters('ph_ups_address_customization', $from_address,$shipment, $ship_from_address,$order->get_id(),'from'); // Support for shipping multiple address
				$to_address=apply_filters('ph_ups_address_customization', $to_address,$shipment, $ship_from_address,$order->get_id(),'to'); // Support for shipping multiple address
				$directdeliveryonlyindicator = null;
				
				$request_arr	=	array();
				$xml_request = '<ShipmentConfirmRequest>';
				$xml_request .= '<Request>';
				$xml_request .= '<TransactionReference>';
				$xml_request .= '<CustomerContext>'.$contextvalue.'</CustomerContext>';
				$xml_request .= '<XpciVersion>1.0001</XpciVersion>';
				$xml_request .= '</TransactionReference>';
				$xml_request .= '<RequestAction>ShipConfirm</RequestAction>';
				$xml_request .= '<RequestOption>nonvalidate</RequestOption>';
				$xml_request .= '</Request>';


				// Taking Confirm Shipment Data Into Array for Better Processing and Filtering
				$request_arr['Shipment']=array();

				//request for access point, not required for return label, confirmed by UPS
				if($this->accesspoint_locator ){// Access Point Addresses Are All Commercial So Overridding ResidentialAddress Condition
					$access_point_node	=	$this->get_confirm_shipment_accesspoint_request($order);
					if(!empty($access_point_node)){
						$this->residential	=	false;
						$request_arr['Shipment'] = array_merge($access_point_node);
					}
				}
				$request_arr['Shipment']['Description']	= $this->wf_get_shipment_description( $order, $shipment );
				
				if( $this->billing_address_as_shipper ) {

					$billing_address 	= $order->get_address('billing');

					$shipper_address_1  = substr( htmlspecialchars($billing_address['address_1']), 0, 34 );
					$shipper_address_2  = substr( htmlspecialchars($billing_address['address_2']), 0, 34 );
					$shipper_address 	= empty($shipper_address_2) ? $shipper_address_1 : array( $shipper_address_1, $shipper_address_2 );

					$billing_as_shipper =  array(
						'name'		=> htmlspecialchars($billing_address['first_name']).' '.htmlspecialchars($billing_address['last_name']),
						'company' 	=> !empty($billing_address['company']) ? htmlspecialchars($billing_address['company']) : '-',
						'phone' 	=> ( strlen($billing_address['phone']) > 15 ) ? str_replace(' ', '', $billing_address['phone']) : $billing_address['phone'],
						'email' 	=> htmlspecialchars($billing_address['email']),
						'address'	=> htmlspecialchars($billing_address['address_1']).' '.htmlspecialchars($billing_address['address_2']),
						'city' 		=> htmlspecialchars($billing_address['city']),
						'state' 	=> htmlspecialchars($billing_address['state']),
						'country' 	=> $billing_address['country'],
						'postcode' 	=> $billing_address['postcode'],
					);

					$request_arr['Shipment']['Shipper']	=	array(
						'Name'			=>	substr( $billing_as_shipper['company'], 0, 34 ),
						'AttentionName'	=>	substr( $billing_as_shipper['name'], 0, 34 ),
						'Phone'			=>	array(
							'Number'		=> preg_replace("/[^0-9]/", "", $billing_as_shipper['phone']),
						),
						'EMailAddress'	=>	$billing_as_shipper['email'],
						'ShipperNumber'	=>	$ups_shipper_number,
						'Address'		=>	array(
							'AddressLine'		=>	$shipper_address,
							'City'				=>	substr( $billing_as_shipper['city'], 0, 29 ),
							'StateProvinceCode'	=>	$billing_as_shipper['state'],
							'CountryCode'		=>	$billing_as_shipper['country'],
							'PostalCode'		=>	$billing_as_shipper['postcode'],
						),
					);

					$shipfrom_address_1  	= substr( $from_address['address_1'], 0, 34 );
					$shipfrom_address_2  	= substr( $from_address['address_2'], 0, 34 );
					$shipfrom_address 		= empty($shipfrom_address_2) ? $shipfrom_address_1 : array( $shipfrom_address_1, $shipfrom_address_2 );

					$request_arr['Shipment']['ShipFrom'] = array(
						'AttentionName'		=>	substr( $from_address['name'], 0, 34 ),
						'Name'				=>	substr( $from_address['company'], 0, 34 ),
						'Address'			=>	array(
							'AddressLine'		=>	$shipfrom_address,
							'City'				=>	substr( $from_address['city'], 0, 29 ),
							'StateProvinceCode'	=>	$from_address['state'],
							'CountryCode'		=>	$from_address['country'],
							'PostalCode'		=>	$from_address['postcode'],
						),						
					);

				}else{

					$shipper_address_1  	= substr( $from_address['address_1'], 0, 34 );
					$shipper_address_2  	= substr( $from_address['address_2'], 0, 34 );
					$shipper_address 		= empty($shipper_address_2) ? $shipper_address_1 : array( $shipper_address_1, $shipper_address_2 );

					$request_arr['Shipment']['Shipper']	=	array(
						'Name'			=>	substr( $from_address['company'], 0, 34 ),
						'AttentionName'	=>	substr( $from_address['name'], 0, 34 ),
						'Phone'			=>	array(
							'Number'		=> preg_replace("/[^0-9]/", "", $from_address['phone']),
						),
						'EMailAddress'	=>	$from_address['email'],
						'ShipperNumber'	=>	$ups_shipper_number,
						'Address'		=>	array(
							'AddressLine'		=>	$shipper_address,
							'City'				=>	substr( $from_address['city'], 0, 29 ),
							'StateProvinceCode'	=>	$from_address['state'],
							'CountryCode'		=>	$from_address['country'],
							'PostalCode'		=>	$from_address['postcode'],
						),
					);
				}

				if( '' == trim( $to_address['company'] ) ) {
					$to_address['company'] = '-';
				}

				$request_arr['Shipment']['ShipTo']	=	array(
					'Name'	=>	substr( $to_address['company'], 0, 34 ),
					'AttentionName'	=>	substr( $to_address['name'], 0, 34 ),
					'Phone'			=>	array(
						'Number'		=> preg_replace("/[^0-9]/", "", $to_address['phone']),
					),
					'EMailAddress'	=>	$to_address['email'],
					'Address'		=>	array(
						'AddressLine'		=>	substr( $to_address['address_1'], 0, 34 ),
						'AddressLine2'		=>	substr( $to_address['address_2'], 0, 34 ),
						'City'				=>	substr( $to_address['city'], 0, 29 ),
						'CountryCode'		=>	$to_address['country'],
						'PostalCode'		=>	$to_address['postcode'],
					)
				);
				if(in_array($to_address['country'], $this->countries_with_statecodes)){ // State Code valid for certain countries only
					$request_arr['Shipment']['ShipTo']['Address']['StateProvinceCode']	=	$to_address['state'];
				}

				$selected_shipment_service = '03';

				if( $this->remove_recipients_phno && !in_array($selected_shipment_service, $this->phone_number_services) && $from_address['country'] == $to_address['country'] )
				{
					if( isset($request_arr['Shipment']['ShipTo']['Phone']['Number']) )
					{
						unset($request_arr['Shipment']['ShipTo']['Phone']['Number']);
					}

				}

				// Negotiated Rates Flag
				if ( $ups_negotiated ) {
					$request_arr['Shipment']['ShipmentRatingOptions']['NegotiatedRatesIndicator']	=	'';
				}
				$request_arr['Shipment']['ShipmentRatingOptions']['FRSShipmentIndicator'] = '';
				$request_arr['Shipment']['FRSPaymentInformation']['Type']['Code'] = '01';
				$request_arr['Shipment']['FRSPaymentInformation']['AccountNumber'] = $ups_shipper_number;


				if( $this->residential ) {
					$request_arr['Shipment']['ShipTo']['Address']['ResidentialAddress']='';
				}
				
				$request_arr['Shipment']['Service']	=	array(
					'Code'			=>	"03",
					'Description'	=>	"GFP",
				);

				//Save service id, Required for pickup 
				update_post_meta( $order->get_id(), 'wf_ups_selected_service', $shipment['shipping_service'] );
				
				$access_point_location = get_post_meta( $order->get_id(), '_shipping_accesspoint', true );
				

				$request_arr['Shipment']['package']['multi_node']	=	1;
				$numofpieces = 0;	//For Worldwide Express Freight Service
				foreach ( $shipment['packages'] as $package ) {

					if(isset($package['destination']))
					{
						unset($package['destination']);
					}

					//Get direct delivery option from package to set in order level
					if( empty($directdeliveryonlyindicator) && !empty($package['Package']['DirectDeliveryOnlyIndicator']) ) {
						$directdeliveryonlyindicator = $package['Package']['DirectDeliveryOnlyIndicator'];
					}
					
					// Unset DirectDeliveryOnlyIndicator, it is not applicable at package level
					if( isset($package['Package']['DirectDeliveryOnlyIndicator']) ) {
						unset($package['Package']['DirectDeliveryOnlyIndicator']);
					}
					
					
					$items_in_packages[] = isset($package['Package']['items']) ? $package['Package']['items'] : null ;	    //Contains product which are being packed together

					$id = 0;
					$product_data = array();

					if( isset($package['Package']['items']) ) {
						foreach($package['Package']['items'] as $item) {

							$product_id = $item->get_id();

							if( isset($product_data[$product_id] ))
							{
								$product_data[$product_id]+=1;
							}
							else
							{
								$product_data[$product_id] = 1;
							}
						}
					}

					unset($package['Package']['items']);
					$package['Package']['Packaging']['Code']="02";
					$package['Package']['Commodity']['FreightClass']=50;
					
					if( !isset($package['Package']['Dimensions']) || !empty($package['Package']['Dimensions']) ) {
						unset($package['Package']['Dimensions']);
					}

					$request_arr['Shipment']['package'][] = $package;
				}

				$shipmentServiceOptions = array();
				

				if(	$this->ship_from_address_different_from_shipper == 'yes' ) {

					$different_ship_from_address = $this->get_ship_from_address($ups_settings);

					$shipfrom_address_1  	= substr( $different_ship_from_address['Address']['AddressLine1'], 0, 34 );
					$shipfrom_address_2  	= isset($different_ship_from_address['Address']['AddressLine2']) ? substr( $from_address['address_2'], 0, 34 ) : '';
					$shipfrom_address 		= empty($shipfrom_address_2) ? $shipfrom_address_1 : array( $shipfrom_address_1, $shipfrom_address_2 );

					// Node differs in case of Ground With Freight
					$ship_from_different_address = array(
						'Name'			=> $different_ship_from_address['CompanyName'],
						'AttentionName'	=> $different_ship_from_address['AttentionName'],
						'Address'		=>	array(
							'AddressLine'	=>	$shipfrom_address,
							'City'			=>	$different_ship_from_address['Address']['City'],
							'PostalCode'	=>	$different_ship_from_address['Address']['PostalCode'],
							'CountryCode'	=>	$different_ship_from_address['Address']['CountryCode'],
						),
					);

					if( isset($different_ship_from_address['Address']['StateProvinceCode']) ) {

						$ship_from_different_address['Address']['StateProvinceCode'] = $different_ship_from_address['Address']['StateProvinceCode'];
					}
					
					if( ! empty($ship_from_address) )	$request_arr['Shipment']['ShipFrom'] = $ship_from_different_address;
				}
				
				if($sat_delivery){
					$shipmentServiceOptions['SaturdayDelivery']	=	'';
				}
					

				if($this->wcsups->cod){
					// Shipment Level COD
					if( $this->wcsups->is_shipment_level_cod_required($to_address['country']) ){
						$codfundscode = in_array( $to_address['country'], array('RU', 'AE') ) ? 1 : $this->eu_country_cod_type;	// 1 for Cash, 9 for Cheque, 1 is available for all the countries
						$cod_value = $this->wcsups->cod_total;
						
						$shipmentServiceOptions['COD']	=	array(
							'CODCode'		=>	3,
							'CODFundsCode'	=>	$codfundscode,
							'CODAmount'		=>	array(
								'MonetaryValue'	=>	(string) $cod_value,
							),
						);
					}
				}
					
				if( $this->tin_number ){
					// $request_arr['Shipment']['ItemizedPaymentInformation']['SplitDutyVATIndicator'] = true;
					$request_arr['Shipment']['Shipper']['TaxIdentificationNumber'] = $this->tin_number;
				}

				
				
				//Set Direct delivery in the actual request
				if( !empty($directdeliveryonlyindicator) ) {
					$shipmentServiceOptions['DirectDeliveryOnlyIndicator'] = $directdeliveryonlyindicator;
				}
				
				if(sizeof($shipmentServiceOptions)){
					$request_arr['Shipment']['ShipmentServiceOptions']	=	empty( $request_arr['Shipment']['ShipmentServiceOptions'] ) ? $shipmentServiceOptions : array_merge($shipmentServiceOptions,$request_arr['Shipment']['ShipmentServiceOptions'] );
				}
				$this->density_unit 	= $this->dim_unit;
				$this->density_length 	= $this->density_length;
				$this->density_width 	= $this->density_width;
				$this->density_height 	= $this->density_height;

				if( $this->density_length == 0 )
				{
					$this->density_length = ( $this->density_unit == 'IN' ) ? 10 : 26;
				}
				
				if( $this->density_width == 0 )
				{
					$this->density_width = ( $this->density_unit == 'IN' ) ? 10 : 26;
				}
				
				if( $this->density_height == 0 )
				{
					$this->density_height = ( $this->density_unit == 'IN' ) ? 10 : 26;
				}	
				if($this->enable_density_based_rating)
				{		
					$request_arr['Shipment']['FreightShipmentInformation']=array(
								'FreightDensityInfo'=>array(
						                    			'HandlingUnits'=>array(
											                        		'Quantity'=>1,
											                        		'Type'=>array(
											                        						'Code'=>'PLT',
											                        						'Description'=> 'Density'
											                        					),
											                        		'Dimensions' => array(
											                        							'UnitOfMeasurement'=> array('Code'	=>$this->density_unit),
										                        								'Description'=>"Dimension unit",
										                        								'Length'=> $this->density_length,
										                        								'Width'=> $this->density_width,
										                        								'Height'=>$this->density_height
										                        							)
							                        	),
							                    		'Description'=> "density rating",
                    								),
								'DensityEligibleIndicator'=>1,
								);
				}

				$request_arr['LabelSpecification']['LabelPrintMethod']	=	$this->get_code_from_label_type( $print_label_type );
				$request_arr['LabelSpecification']['HTTPUserAgent']		=	'Mozilla/4.5';
				
				if( 'zpl' == $print_label_type || 'epl' == $print_label_type || 'png' == $print_label_type ) {
					$request_arr['LabelSpecification']['LabelStockSize']	=	array('Height' => 4, 'Width' => 6);
				}
				$request_arr['LabelSpecification']['LabelImageFormat']	=	$this->get_code_from_label_type( $print_label_type );
				
				$request_arr	=	apply_filters('wf_ups_shipment_confirm_request_data', $request_arr, $order);
				// Converting array data to xml
				$xml_request .= $this->wcsups->wf_array_to_xml($request_arr);
				
				$xml_request .= '</ShipmentConfirmRequest>';

				$xml_request	=	apply_filters('wf_ups_shipment_confirm_request', $xml_request, $order, $shipment );
				$shipment_requests    = $this->modfiy_encoding($xml_request);
				
			}
			return $shipment_requests;
	}

	function get_currency_value( $change_to_currency, $current_currency, $value ) {
		
		$woocommerce_currency_conversion_rates = get_option('woocommerce_multicurrency_rates');
		if( $change_to_currency != $current_currency && !empty($woocommerce_currency_conversion_rates) && isset( $woocommerce_currency_conversion_rates[$change_to_currency] ) &&isset( $woocommerce_currency_conversion_rates[$change_to_currency] ) )
		{
            $current_currency_rate = $woocommerce_currency_conversion_rates[$current_currency];
            $change_to_currency_rate = $woocommerce_currency_conversion_rates[$change_to_currency];

			$conversion_rate = $change_to_currency_rate / $current_currency_rate;
			return round( $conversion_rate*$value,2 );
		}
		return $value;
	}

	function wf_ups_shipment_confirmrequest( $order,$return_label=false ) {
		global $post;
		
		$ups_settings 					= get_option( 'woocommerce_'.WF_UPS_ID.'_settings', null ); 
		
		// Apply filter on settings data
		$ups_settings	=	apply_filters('wf_ups_confirm_shipment_settings', $ups_settings, $order); //For previous version compatibility.
		$ups_settings	=	apply_filters('wf_ups_shipment_settings', $ups_settings, $order);
		
		// Define user set variables
		$ups_enabled					= isset( $ups_settings['enabled'] ) ? $ups_settings['enabled'] : '';
		$ups_title						= isset( $ups_settings['title'] ) ? $ups_settings['title'] : 'UPS';
		$ups_availability    			= isset( $ups_settings['availability'] ) ? $ups_settings['availability'] : 'all';
		$ups_countries       			= isset( $ups_settings['countries'] ) ? $ups_settings['countries'] : array();
		// WF: Print Label Settings.
		$print_label_type     			= isset( $ups_settings['print_label_type'] ) ? $ups_settings['print_label_type'] : 'gif';
		$ship_from_address      		= isset( $ups_settings['ship_from_address'] ) ? $ups_settings['ship_from_address'] : 'origin_address';
		// API Settings
		
		$shipper_email	 				= isset( $ups_settings['email'] ) ? $ups_settings['email'] : '';
		$ups_user_id         			= isset( $ups_settings['user_id'] ) ? $ups_settings['user_id'] : '';
		$ups_password        			= isset( $ups_settings['password'] ) ? $ups_settings['password'] : '';
		$ups_access_key      			= isset( $ups_settings['access_key'] ) ? $ups_settings['access_key'] : '';
		$ups_shipper_number  			= isset( $ups_settings['shipper_number'] ) ? $ups_settings['shipper_number'] : '';
		$ups_negotiated      			= isset( $ups_settings['negotiated'] ) && $ups_settings['negotiated'] == 'yes' ? true : false;
		// $ups_residential		        = isset( $ups_settings['residential'] ) && $ups_settings['residential'] == 'yes' ? true : false;
		
		$this->accesspoint_locator 	= (isset($this->settings[ 'accesspoint_locator']) && $this->settings[ 'accesspoint_locator']=='yes') ? true : false;
		$this->is_hazmat_product 	= false;

		$cod						= get_post_meta($order->get_id(),'_wf_ups_cod',true);
		$sat_delivery				= get_post_meta($order->get_id(),'_wf_ups_sat_delivery',true);
		$order_total				= $order->get_total();
		$order_object				= wc_get_order($order->get_id());
		$order_sub_total			= (double) is_object($order_object) ? $order_object->get_subtotal() : 0;
		$min_order_amount_for_insurance = ! empty($ups_settings['min_order_amount_for_insurance']) ? $ups_settings['min_order_amount_for_insurance'] : 0;
		$order_currency				= $order->get_order_currency();
		
		$this->nafta_producer_option	= ( isset( $ups_settings['nafta_producer_option'] ) && !empty($ups_settings['nafta_producer_option']) ) ? $ups_settings['nafta_producer_option'] : '02';
		$this->blanket_begin_period		= ( isset( $ups_settings['blanket_begin_period'] ) && !empty($ups_settings['blanket_begin_period']) ) ? $ups_settings['blanket_begin_period'] : '';
		$this->blanket_end_period		= ( isset( $ups_settings['blanket_end_period'] ) && !empty($ups_settings['blanket_end_period']) ) ? $ups_settings['blanket_end_period'] : '';

		$this->eei_shipper_filed_option			= ( isset( $ups_settings['eei_shipper_filed_option'] ) && !empty($ups_settings['eei_shipper_filed_option']) ) ? $ups_settings['eei_shipper_filed_option'] : '';
		$this->eei_pre_departure_itn_number		= ( isset( $ups_settings['eei_pre_departure_itn_number'] ) && !empty($ups_settings['eei_pre_departure_itn_number']) ) ? $ups_settings['eei_pre_departure_itn_number'] : '';
		$this->eei_exemption_legend				= ( isset( $ups_settings['eei_exemption_legend'] ) && !empty($ups_settings['eei_exemption_legend']) ) ? $ups_settings['eei_exemption_legend'] : '';
		$this->eei_mode_of_transport			= ( isset( $ups_settings['eei_mode_of_transport'] ) && !empty($ups_settings['eei_mode_of_transport']) ) ? $ups_settings['eei_mode_of_transport'] : '';
		$this->eei_parties_to_transaction		= ( isset( $ups_settings['eei_parties_to_transaction'] ) && !empty($ups_settings['eei_parties_to_transaction']) ) ? $ups_settings['eei_parties_to_transaction'] : '';
		//PDS-79
		$import_control_settings 				= (isset($this->settings[ 'import_control_settings']) && $this->settings[ 'import_control_settings']=='yes') ? true : false; //UPS Import Control Indicator settings check box
        $import_control  						= 'false';

		if( isset($_GET['ic'] )){

			$import_control = isset( $_GET['ic'] ) ? $_GET['ic'] : ''; //UPS Import Control Indicator

		}else if( isset($import_control_settings) && $import_control_settings ) {

			$import_control = 'true';

		}

		//PDS-130
		$shipment_terms   = '' ;

		if( isset( $_GET['ShipmentTerms'] )){

			$shipment_terms = isset( $_GET['ShipmentTerms'] ) ? $_GET['ShipmentTerms'] : ''; 

		}elseif( !empty( $this->terms_of_shipment ) ){

			$shipment_terms = $this->terms_of_shipment ;

		}

		$recipients_tin 				= get_post_meta($order->get_id(), 'ph_ups_shipping_tax_id_num', true);
		$shipto_recipients_tin 			= get_post_meta($order->get_id(), 'ph_ups_ship_to_tax_id_num', true);
		
		$ship_options=array('return_label'=>$return_label); // Array to pass options like return label on the fly.
		
		$billing_address_preference = $this->get_product_address_preference( $order, $ups_settings, $return_label );

		if( 'billing_address' == $ship_from_address && $billing_address_preference ) { 
			$from_address 	= $this->get_order_address( $order_object );
			$to_address 	= $this->get_shop_address( $order, $ups_settings );
		}
		else {
			$from_address 	= $this->get_shop_address( $order, $ups_settings );
			$to_address 	= $this->get_order_address( $order_object );
		}

		if( $this->address_validation && in_array( $to_address['country'], array( 'US', 'PR' ) ) ) {

			if( ! class_exists('Ph_Ups_Address_Validation') ) {
				
				require_once 'class-ph-ups-address-validation.php';
			}

			if( $return_label ) {
				$Ph_Ups_Address_Validation 	= new Ph_Ups_Address_Validation( $from_address, $ups_settings );
				$residential_code			= $Ph_Ups_Address_Validation->residential_check;
			} else {
				$Ph_Ups_Address_Validation 	= new Ph_Ups_Address_Validation( $to_address, $ups_settings );
				$residential_code			= $Ph_Ups_Address_Validation->residential_check;
			}
			

			if( $residential_code == 2 ) {
				$this->residential = true;
			}
		}

		$shipping_service_data	= $this->wf_get_shipping_service_data( $order ); 
		$shipping_method		= $shipping_service_data['shipping_method'];
		$shipping_service		= $shipping_service_data['shipping_service'];
		$shipping_service_name	= $shipping_service_data['shipping_service_name'];

		if( ($from_address['country'] == $to_address['country']) && in_array( $from_address['country'], $this->dc_domestic_countries) ){ // Delivery confirmation available at package level only for domestic shipments.
			$ship_options['delivery_confirmation_applicable']	= true;
			$ship_options['international_delivery_confirmation_applicable']	= false;
		}
		else {
			$ship_options['international_delivery_confirmation_applicable']	= true;
		}
		
		$package_data = array();

		if( !$return_label )
		{
			$package_data = $this->wf_get_package_data( $order, $ship_options, $to_address);
		}

		if( empty( $package_data ) ) {
			$stored_package = get_post_meta( $order->get_id(), '_wf_ups_stored_packages',true);
			
			if(!isset($stored_package[0]))
				$stored_package=array($stored_package);

			if(is_array($stored_package)) {
				$package_data = $stored_package;
			} else {
				return false;
			}
		}
		
		$package_data		=	apply_filters('wf_ups_filter_label_packages',$package_data, $order);

		update_post_meta( $order->get_id(), '_wf_ups_stored_packages', $package_data);


		$shipments          =   $this->split_shipment_by_services($package_data, $order,$return_label);
		
		// Filter to break shipments further, with other business logics, like multi vendor ,Support for shipping multiple address
		$shipments			=	apply_filters('wf_ups_shipment_data', $shipments, $order);

		$shipment_requests	= array();
		$all_var 			= get_defined_vars();

		if( is_array($shipments) ) {

			$service_index	= 0;
			$str 			= isset($_GET['service']) ? str_replace( '\"','', str_replace(']','', str_replace('[','',$_GET['service'])) ) : '';

			// $str will be empty for Automatic Label Generation 
			if( empty($str) && isset($this->auto_label_generation) && $this->auto_label_generation ) {

				// auto_label_services will have the service value from order or from default service setting
				$svc_code 	= $this->auto_label_services;
			} else {
				$svc_code 	= explode(',',$str);
			}

			$include_return	= isset($_GET['rt_service']) ? str_replace( '\"','', str_replace(']','', str_replace('[','',$_GET['rt_service'])) ) : '';

			if( !empty($include_return) ) {

				$return_svc_code 		= explode(',',$include_return);
			}

			foreach($shipments as $shipment) {
				// Support for shipping multiple address
				$from_address=apply_filters('ph_ups_address_customization', $from_address,$shipment, $ship_from_address,$order->get_id(),'from');
				$to_address=apply_filters('ph_ups_address_customization', $to_address,$shipment, $ship_from_address,$order->get_id(),'to');

				$directdeliveryonlyindicator 	= null;
				$alcoholicbeveragesindicator	= 'no';
				$diagnosticspecimensindicator	= 'no';
				$perishablesindicator			= 'no';
				$plantsindicator				= 'no';
				$seedsindicator					= 'no';
				$specialexceptionsindicator		= 'no';
				$tobaccoindicator				= 'no';
				$this->is_hazmat_product 	    = false;

				$shipping_service = $svc_code[$service_index];

				if( in_array($shipping_service,array_keys($this->freight_services)) || in_array($shipment['shipping_service'],array_keys($this->freight_services)) )
				{
					$freight_obj =	new wf_freight_ups($this);

					foreach( $shipment['packages'] as $freight_package ) {

						$freight_package_shipment = array(
							'shipping_service'	=>	$shipment['shipping_service'],
							'packages'			=>	array($freight_package),
						);

						$shipment_requests[]=$freight_obj->create_shipment_request( $freight_package_shipment, $order->get_id() );
					}

				} else if($shipment['shipping_service']=='US48') {

					$shipment_requests[] = array(

						"service"=>'GFP',
						"request"=>$this->wf_ups_shipment_confirmrequest_GFP( $order,$shipment)
					);

				} else {
					$contextid=$order->get_id();
			$contextvalue               =apply_filters( 'ph_ups_update_customer_context_value',$contextid);

					$request_arr	=	array();
					$xml_request = '<?xml version="1.0" encoding="UTF-8"?>';
					$xml_request .= '<AccessRequest xml:lang="en-US">';
					$xml_request .= '<AccessLicenseNumber>'.$ups_access_key.'</AccessLicenseNumber>';
					$xml_request .= '<UserId>'.$ups_user_id.'</UserId>';
					$xml_request .= '<Password>'.$ups_password.'</Password>';
					$xml_request .= '</AccessRequest>';
					$xml_request .= '<?xml version="1.0" ?>';
					$xml_request .= '<ShipmentConfirmRequest>';
					$xml_request .= '<Request>';
					$xml_request .= '<TransactionReference>';
					$xml_request .= '<CustomerContext>'.$contextvalue.'</CustomerContext>';
					$xml_request .= '<XpciVersion>1.0001</XpciVersion>';
					$xml_request .= '</TransactionReference>';
					$xml_request .= '<RequestAction>ShipConfirm</RequestAction>';
					$xml_request .= '<RequestOption>nonvalidate</RequestOption>';
					$xml_request .= '</Request>';


						// Taking Confirm Shipment Data Into Array for Better Processing and Filtering
					$request_arr['Shipment']=array();

						//request for access point, not required for return label, confirmed by UPS
						if($this->accesspoint_locator && ! $return_label){// Access Point Addresses Are All Commercial So Overridding ResidentialAddress Condition
							$access_point_node	=	$this->get_confirm_shipment_accesspoint_request($order);
							if(!empty($access_point_node)){
								$this->residential	=	false;
								$request_arr['Shipment'] = array_merge($access_point_node);
							}
						}
						$request_arr['Shipment']['Description']	= $this->wf_get_shipment_description( $order, $shipment );
						if($return_label){
							$request_arr['Shipment']['ReturnService']	=array('Code'	=>	9);
						}
						
						if( $from_address['country'] != $to_address['country'] || !in_array($from_address['country'],array('US','PR'))){// ReferenceNumber Valid if the origin/destination pai is not US/US or PR/PR
							$request_arr['Shipment']['ReferenceNumber']	=	array(
								'Code'	=>	'PO',
								'Value'	=>	$order->get_id(),
							);
						}
						
						if(!empty( get_post_meta($order->get_id(), '_ph_ups_export_compliance', true) ) ){
							$mrn_number = get_post_meta($order->get_id(), '_ph_ups_export_compliance', true);
							if( isset( $mrn_number )){
								$request_arr['Shipment']['MovementReferenceNumber'] = $mrn_number;
							}
						}
						
						if( in_array( $from_address['country'],array('US') ) &&  in_array( $to_address['country'], array('PR','CA') ) ) {

							if( $order_total < 1 ) {
								$order_total = 1;
							}

							$request_arr['Shipment']['InvoiceLineTotal']['CurrencyCode'] = $order_currency;
							$request_arr['Shipment']['InvoiceLineTotal']['MonetaryValue'] = (int)$order_total;

						// For Return Shipment check condition in reverse order
						} else if( $return_label && in_array( $to_address['country'],array('US') ) &&  in_array( $from_address['country'], array('PR','CA') ) ) {

							if( $order_total < 1 ) {
								$order_total = 1;
							}
							
							$request_arr['Shipment']['InvoiceLineTotal']['CurrencyCode'] = $order_currency;
							$request_arr['Shipment']['InvoiceLineTotal']['MonetaryValue'] = (int)$order_total;
						}


						$is_gfp_shipment	= isset( $_GET['is_gfp_shipment'] ) ? $_GET['is_gfp_shipment'] : '';
						if($is_gfp_shipment=='true'){
							$request_arr['Shipment']['ShipmentRatingOptions']['FRSShipmentIndicator'] = '';
							$request_arr['Shipment']['FRSPaymentInformation']['Type']['Code'] = '01';
							$request_arr['Shipment']['FRSPaymentInformation']['AccountNumber'] = $ups_shipper_number;
						}

						if( $this->billing_address_as_shipper && !$return_label ) {

							$billing_address 	= $order->get_address('billing');

							$billing_as_shipper =  array(
								'name'		=> htmlspecialchars($billing_address['first_name']).' '.htmlspecialchars($billing_address['last_name']),
								'company' 	=> !empty($billing_address['company']) ? htmlspecialchars($billing_address['company']) : '-',
								'phone' 	=> ( strlen($billing_address['phone']) > 15 ) ? str_replace(' ', '', $billing_address['phone']) : $billing_address['phone'],
								'email' 	=> htmlspecialchars($billing_address['email']),
								'address'	=> htmlspecialchars($billing_address['address_1']),
								'city' 		=> htmlspecialchars($billing_address['city']),
								'state' 	=> htmlspecialchars($billing_address['state']),
								'country' 	=> $billing_address['country'],
								'postcode' 	=> $billing_address['postcode'],
							);

							$request_arr['Shipment']['Shipper']	=	array(
								'Name'			=>	substr( $billing_as_shipper['company'], 0, 34 ),
								'AttentionName'	=>	substr( $billing_as_shipper['name'], 0, 34 ),
								'PhoneNumber'	=>	preg_replace("/[^0-9]/", "", $billing_as_shipper['phone']),
								'EMailAddress'	=>	$billing_as_shipper['email'],
								'ShipperNumber'	=>	$ups_shipper_number,
								'Address'		=>	array(
									'AddressLine1'		=>	substr( $billing_as_shipper['address'], 0, 34 ),
									'City'				=>	substr( $billing_as_shipper['city'], 0, 29 ),
									'StateProvinceCode'	=>	$billing_as_shipper['state'],
									'CountryCode'		=>	$billing_as_shipper['country'],
									'PostalCode'		=>	$billing_as_shipper['postcode'],
								),
							);

							if( !empty($billing_address['address_2']) ) {

								$request_arr['Shipment']['Shipper']['Address']['AddressLine2'] = substr( htmlspecialchars($billing_address['address_2']), 0, 34 );
							}

							$request_arr['Shipment']['ShipFrom'] = array(
								'AttentionName'		=>	substr( $from_address['name'], 0, 34 ),
								'CompanyName'		=>	substr( $from_address['company'], 0, 34 ),
								'PhoneNumber'		=>	preg_replace("/[^0-9]/", "", $from_address['phone']),
								'Address'			=>	array(
									'AddressLine1'		=>	substr( $from_address['address_1'], 0, 34 ),
									'City'				=>	substr( $from_address['city'], 0, 29 ),
									'StateProvinceCode'	=>	$from_address['state'],
									'CountryCode'		=>	$from_address['country'],
									'PostalCode'		=>	$from_address['postcode'],
								),
								'TaxIdentificationNumber'	=> $this->tin_number,
							);

							if( !empty($from_address['address_2']) ) {

								$request_arr['Shipment']['ShipFrom']['Address']['AddressLine2'] = substr( $from_address['address_2'], 0, 34 );
							}

						}else{

							$request_arr['Shipment']['Shipper']	=	array(
								'Name'			=>	substr( $from_address['company'], 0, 34 ),
								'AttentionName'	=>	substr( $from_address['name'], 0, 34 ),
								'PhoneNumber'	=>	preg_replace("/[^0-9]/", "", $from_address['phone']),
								'EMailAddress'	=>	$from_address['email'],
								'ShipperNumber'	=>	$ups_shipper_number,
								'Address'		=>	array(
									'AddressLine1'		=>	substr( $from_address['address_1'], 0, 34 ),
									'City'				=>	substr( $from_address['city'], 0, 29 ),
									'StateProvinceCode'	=>	$from_address['state'],
									'CountryCode'		=>	$from_address['country'],
									'PostalCode'		=>	$from_address['postcode'],
								),
							);

							if( !empty($from_address['address_2']) ) {

								$request_arr['Shipment']['Shipper']['Address']['AddressLine2'] = substr( $from_address['address_2'], 0, 34 );
							}
						}
						
						if( $this->eei_data && !$return_label ) {
							
							$request_arr['Shipment']['ShipFrom'] = array(
								'AttentionName'		=>	substr( $from_address['name'], 0, 34 ),
								'CompanyName'		=>	substr( $from_address['company'], 0, 34 ),
								'Address'			=>	array(
									'AddressLine1'		=>	substr( $from_address['address_1'], 0, 34 ),
									'City'				=>	substr( $from_address['city'], 0, 29 ),
									'StateProvinceCode'	=>	$from_address['state'],
									'CountryCode'		=>	$from_address['country'],
									'PostalCode'		=>	$from_address['postcode'],
								),
								'TaxIdentificationNumber'	=> $this->tin_number,
								'TaxIDType'					=>	array(
									'Code'	=>	'EIN',
								),
							);

							if( !empty($from_address['address_2']) ) {

								$request_arr['Shipment']['ShipFrom']['Address']['AddressLine2'] = substr( $from_address['address_2'], 0, 34 );
							}
						}

						if ($return_label) {

							$request_arr['Shipment']['ShipTo']	=	array(
								'CompanyName'	=>	substr( $from_address['company'], 0, 34 ),
								'AttentionName'	=>	substr( $from_address['name'], 0, 34 ),
								'PhoneNumber'	=>	preg_replace("/[^0-9]/", "", $from_address['phone']),
								'EMailAddress'	=>	$from_address['email'],
							);
							$request_arr['Shipment']['ShipTo']['Address'] = $this->get_ship_to_address_in_return_label( $ups_settings, $from_address);

							if( $this->tin_number ) {
								$request_arr['Shipment']['ShipTo']['TaxIdentificationNumber'] = $this->tin_number;
							}

							if( $this->residential ) {
								$request_arr['Shipment']['ShipTo']['Address']['ResidentialAddress']='';
							}

						} else {

							if( '' == trim( $to_address['company'] ) ) {
								$to_address['company'] = '-';
							}

							$request_arr['Shipment']['ShipTo']	=	array(
								'CompanyName'	=>	substr( $to_address['company'], 0, 34 ),
								'AttentionName'	=>	substr( $to_address['name'], 0, 34 ),
								'PhoneNumber'	=>	preg_replace("/[^0-9]/", "", $to_address['phone']),
								'EMailAddress'	=>	$to_address['email'],
								'Address'		=>	array(
									'AddressLine1'		=>	substr( $to_address['address_1'], 0, 34 ),
									'AddressLine2'		=>	substr( $to_address['address_2'], 0, 34 ),
									'City'				=>	substr( $to_address['city'], 0, 29 ),
									'CountryCode'		=>	$to_address['country'],
									'PostalCode'		=>	$to_address['postcode'],
								)
							);
							if(in_array($to_address['country'], $this->countries_with_statecodes)){ // State Code valid for certain countries only
								$request_arr['Shipment']['ShipTo']['Address']['StateProvinceCode']	=	$to_address['state'];
							}

							$selected_shipment_service = $this->get_service_code_for_country( $shipment['shipping_service'], $from_address['country'] );

							if( $this->remove_recipients_phno && !in_array($selected_shipment_service, $this->phone_number_services) && $from_address['country'] == $to_address['country'] )
							{
								if( isset($request_arr['Shipment']['ShipTo']['PhoneNumber']) )
								{
									unset($request_arr['Shipment']['ShipTo']['PhoneNumber']);
								}
								
							}

							if( $this->recipients_tin ) {
								$request_arr['Shipment']['ShipTo']['TaxIdentificationNumber'] = $shipto_recipients_tin;
							}

							if( $this->residential ) {
								$request_arr['Shipment']['ShipTo']['Address']['ResidentialAddress']='';
							}
						}

						if($return_label) {

							if( !empty($_GET['return_label_service']) )
							{
								$request_arr['Shipment']['Service']	=	array(
									'Code'			=>	$_GET['return_label_service'] ,
									'Description'	=>	$this->ups_services[$_GET["return_label_service"]],
								);
							}else{
								$request_arr['Shipment']['Service']	=	array(
									'Code'			=>	$return_svc_code[$service_index],
									'Description'	=>	$this->ups_services[$return_svc_code[$service_index]],
								);
							}

						} else {

							$request_arr['Shipment']['Service']	=	array(
								'Code'			=>	$this->get_service_code_for_country( $shipment['shipping_service'], $from_address['country'] ),
								'Description'	=>	( $this->get_service_code_for_country( $shipment['shipping_service'], $from_address['country'] ) == 96 ) ? 'WorldWide Express Freight' : $shipping_service_name,
							);
						}

						//Save service id, Required for pickup 
						update_post_meta( $order->get_id(), 'wf_ups_selected_service', $shipment['shipping_service'] );
						
						$itemizedpaymentinformation = array();
						$access_point_location 		= get_post_meta( $order->get_id(), '_shipping_accesspoint', true );
						
						if ( $this->transportation == 'shipper' ) {

							$itemizedpaymentinformation[]	=	array(

								'ShipmentCharge'	=>	array(
									'Type'			=>	'01',
									'BillShipper' 	=> array(
										'AccountNumber' => $ups_shipper_number,
									),
								),
							);

						} elseif ( $this->transportation == 'third_party'  ) {

							$pcode 	= ( isset( $ups_settings['transport_payor_post_code'] ) && !empty($ups_settings['transport_payor_post_code']) ) ? $ups_settings['transport_payor_post_code'] : '';
							$ccode 	= ( isset( $ups_settings['transport_payor_country_code'] ) && !empty($ups_settings['transport_payor_country_code']) ) ? $ups_settings['transport_payor_country_code'] : '';
							$anum 	= ( isset( $ups_settings['transport_payor_acc_no'] ) && !empty($ups_settings['transport_payor_acc_no']) ) ? $ups_settings['transport_payor_acc_no'] : '';

							$itemizedpaymentinformation[]	=	array(

								'ShipmentCharge'	=>	array(
									'Type'				=>	'01',
									'BillThirdParty' 	=> array(
										'BillThirdPartyShipper' => array(
											'AccountNumber' => $anum,
											'ThirdParty' 	=> array(
												'Address' => array(
													'PostalCode'  => $pcode,
													'CountryCode' => $ccode,
												),
											),
										),
									),
								),
							);
						}

						if ( $this->customandduties == 'shipper' || ( !empty($access_point_location) && $this->customandduties != 'third_party' ) ) {
							

							$this->admin_diagnostic_report( 'UPS: Duties And Taxes Paid by Shipper' );

							if (!empty($access_point_location)) {
								$this->admin_diagnostic_report( 'UPS: Duties And Taxes Paid by Shipper since Access Point is available' );
							}

							$itemizedpaymentinformation[]	= array(
								'ShipmentCharge'	=>	array(
									'Type'	=>	'02',
									'BillShipper' => array(
										'AccountNumber' => $ups_shipper_number,
									),
								),				
							);

						} elseif ( $this->customandduties == 'third_party' || !empty($access_point_location) ) {
							
							$pcode 	= ( isset( $ups_settings['shipping_payor_post_code'] ) && !empty($ups_settings['shipping_payor_post_code']) ) ? $ups_settings['shipping_payor_post_code'] : '';
							$ccode 	= ( isset( $ups_settings['shipping_payor_country_code'] ) && !empty($ups_settings['shipping_payor_country_code']) ) ? $ups_settings['shipping_payor_country_code'] : '';
							$anum 	= ( isset( $ups_settings['shipping_payor_acc_no'] ) && !empty($ups_settings['shipping_payor_acc_no']) ) ? $ups_settings['shipping_payor_acc_no'] : '';
							
							$this->admin_diagnostic_report( 'UPS: Duties And Taxes Paid by Third Party' );

							$itemizedpaymentinformation[]	=	array(
								'ShipmentCharge'	=>	array(
									'Type'	=>	'02',
									'BillThirdParty' =>array(
										'BillThirdPartyConsignee' => array(
											'AccountNumber' => $anum,
											'ThirdParty' => array(
												'Address' => array(
													'PostalCode'  => $pcode,
													'CountryCode' => $ccode,
												),
											),
										),
									),
								),
							);
						}

						$request_arr['Shipment']['ItemizedPaymentInformation']['ShipmentCharge']	=	array_merge(array('multi_node'=>1), $itemizedpaymentinformation);

						if( isset($itemizedpaymentinformation) ) {
							unset($itemizedpaymentinformation);
						}

						$request_arr['Shipment']['package']['multi_node']	=	1;
						$numofpieces = 0;	//For Worldwide Express Freight Service
						$hazmat_package_id = 0;
						foreach ( $shipment['packages'] as $package_key => $package ) {
							if(isset($package['destination']))
							{
								unset($package['destination']);
							}
							// InsuredValue should not send with Sure post
							if( ($this->wf_is_surepost( $shipment['shipping_service'] ) || $min_order_amount_for_insurance > $order_sub_total) && isset($package['Package']['PackageServiceOptions']) && isset($package['Package']['PackageServiceOptions']['InsuredValue']) ) {
								unset( $package['Package']['PackageServiceOptions']['InsuredValue'] );
							}
							
							// To Set Delivery Confirmation at shipment level for international shipment
							if( $ship_options['international_delivery_confirmation_applicable'] && !$return_label ) {
								if( isset($package['Package']['items']))
								{
									$shipment_delivery_confirmation = $this->wcsups->get_package_signature($package['Package']['items']);
									$shipment_delivery_confirmation = $shipment_delivery_confirmation < $this->ph_delivery_confirmation ? $this->ph_delivery_confirmation : $shipment_delivery_confirmation ;
									$delivery_confirmation = ( isset( $delivery_confirmation) && $delivery_confirmation >= $shipment_delivery_confirmation) ? $delivery_confirmation : $shipment_delivery_confirmation;
								}
							}

							//Get direct delivery option from package to set in order level
							if( empty($directdeliveryonlyindicator) && !empty($package['Package']['DirectDeliveryOnlyIndicator']) ) {
								$directdeliveryonlyindicator = $package['Package']['DirectDeliveryOnlyIndicator'];
							}
							
							// Unset DirectDeliveryOnlyIndicator, it is not applicable at package level
							if( isset($package['Package']['DirectDeliveryOnlyIndicator']) ) {
								unset($package['Package']['DirectDeliveryOnlyIndicator']);
							}
							
							//For Worldwide Express Freight Service
							if( $shipment['shipping_service'] == 96 ) {
								$package['Package']['PackagingType']['Code'] = 30;
								if( isset($package['Package']['items']) ) {
									$numofpieces    += count($package['Package']['items']);
								}
							}

							// Remove Delivery Confirmation for Return Label as UPS doesn't support it
							if( $return_label && isset($package['Package']['PackageServiceOptions']) && isset($package['Package']['PackageServiceOptions']['DeliveryConfirmation']) ) {
								unset($package['Package']['PackageServiceOptions']['DeliveryConfirmation']);
							}

							// Remove COD for Return Label as UPS doesn't support it
							if( $return_label && isset($package['Package']['PackageServiceOptions']) && isset($package['Package']['PackageServiceOptions']['COD']) ) {
								unset($package['Package']['PackageServiceOptions']['COD']);
							}

							if( in_array($shipment['shipping_service'],array('M4','M5','M6') ) ) {

								$package_description = array(
									'57'	=> 'Parcels',
									'62'	=> 'Irregulars',
									'63'	=> 'Parcel Post',
									'64'	=> 'BPM Parcel',
									'65'	=> 'Media Mail',
									'66'	=> 'BPM Flat',
									'67'	=> 'Standard FLat',
								);

								// For International Shipment only supported Packaging Type - 57
								if( $shipment['shipping_service'] == 'M5' || $shipment['shipping_service'] == 'M6' ) {

									$this->mail_innovation_type 	= '57';
								}

								$package['Package']['PackagingType']['Code'] 		= $this->mail_innovation_type;
								$package['Package']['PackagingType']['Description'] = $package_description[$this->mail_innovation_type];

								if( $this->mail_innovation_type == '62' || $this->mail_innovation_type == '67' && isset($package['Package']['PackageWeight']) ) {

									$weight 	= $package['Package']['PackageWeight']['Weight'];
									
									if($this->weight_unit=='LBS') {
										// From LBS to ounces
										$weight	=	$weight*16;
									}else{
										// From KGS to ounces
										$weight	=	$weight*35.274;
									}

									$package['Package']['PackageWeight']['Weight']				= $weight;
									$package['Package']['PackageWeight']['UnitOfMeasurement']	= array( 'Code' => 'OZS' );
								}
								
								if( isset( $package['Package']['PackageServiceOptions'] ) ) {
									unset( $package['Package']['PackageServiceOptions'] );
								}
							}

							//Package level Label description for US to US shipments
							if( $this->add_product_sku == 'yes' || ( $from_address['country'] == $to_address['country'] && in_array($from_address['country'],array('US','PR')) ) ) {

								$description_value = $this->wf_get_shipment_description( $order, $package, true );

								$description_value = ( strlen( $description_value ) >= 35 ) ? substr( $description_value, 0, 32 ).'...' : $description_value;
								
								if( isset($package['Package']) ) {

									$package['Package']['ReferenceNumber'] 	= array();
									$package['Package']['ReferenceNumber']	= array(
										'Code'	=>	'01',
										'Value'	=>	$description_value,
									);
								}
							}

							$items_in_packages[] = isset($package['Package']['items']) ? $package['Package']['items'] : null ;	    //Contains product which are being packed together

							$product_data = array();

							if( isset($package['Package']['items']) ) {
								foreach($package['Package']['items'] as $item) {

									$product_id = 0;

									if( is_object($item) )
									{
										$product_id = $item->get_id();

										if( !empty($product_id) ){

											$product_data[$product_id]['parent_id'] = ( WC()->version > '2.7' ) ? $item->get_parent_id() : ( isset($item->parent->id) ? $item->parent->id : 0 );

											if( isset($product_data[$product_id]['quantity'] ))
											{
												$product_data[$product_id]['quantity']+=1;
											}
											else
											{
												$product_data[$product_id]['quantity'] = 1;
											}	
										}
									}
								}
							}

							foreach ($product_data as $product_id => $product_detail) {		
								$hazmat_product 	= 'no';
								$restricted_product = 'no';

								if( !empty($product_detail['parent_id']) )
								{
									$hazmat_product 	= get_post_meta($product_detail['parent_id'],'_ph_ups_hazardous_materials',1);
									$hazmat_settings 	= get_post_meta($product_detail['parent_id'],'_ph_ups_hazardous_settings',1);

									$restricted_product = get_post_meta($product_detail['parent_id'],'_ph_ups_restricted_article',1);
									$restrictedarticle 	= get_post_meta($product_detail['parent_id'],'_ph_ups_restricted_settings',1);
								}

								if( $hazmat_product != 'yes' )
								{
									$hazmat_product 	= get_post_meta($product_id,'_ph_ups_hazardous_materials',1);
									$hazmat_settings 	= get_post_meta($product_id,'_ph_ups_hazardous_settings',1);
								}
								
								if( $restricted_product != 'yes' )
								{

									$restricted_product = get_post_meta($product_id,'_ph_ups_restricted_article',1);
									$restrictedarticle 	= get_post_meta($product_id,'_ph_ups_restricted_settings',1);
								}

								$transportationmode = array(
									'01' => 'Highway',
									'02' => 'Ground',
									'03' => 'PAX',
									'04' => 'CAO',
								);

								if ( ($this->isc=='yes') && isset($restrictedarticle) && !empty($restrictedarticle)  && ($restricted_product=='yes') ) {
									
									$alcoholicbeveragesindicator 	= ($alcoholicbeveragesindicator=='yes') ? $alcoholicbeveragesindicator : $restrictedarticle['_ph_ups_alcoholic'];
									$diagnosticspecimensindicator 	= ($diagnosticspecimensindicator=='yes') ? $diagnosticspecimensindicator : $restrictedarticle['_ph_ups_diog'];
									$perishablesindicator 			= ($perishablesindicator=='yes') ? $perishablesindicator : $restrictedarticle['_ph_ups_perishable'];
									$plantsindicator 				= ($plantsindicator=='yes') ? $plantsindicator : $restrictedarticle['_ph_ups_plantsindicator'];
									$seedsindicator 				= ($seedsindicator=='yes') ? $seedsindicator : $restrictedarticle['_ph_ups_seedsindicator'];
									$specialexceptionsindicator 	= ($specialexceptionsindicator=='yes') ? $specialexceptionsindicator : $restrictedarticle['_ph_ups_specialindicator'];
									$tobaccoindicator 				= ($tobaccoindicator=='yes') ? $tobaccoindicator : $restrictedarticle['_ph_ups_tobaccoindicator'];
								}

								if($hazmat_product == 'yes'){

									$this->is_hazmat_product = true;
									if( array_key_exists($hazmat_settings['_ph_ups_hm_transportaion_mode'], $transportationmode ))
									{
										$mode = $transportationmode[$hazmat_settings['_ph_ups_hm_transportaion_mode']];
									}

									$req['ChemicalRecordIdentifier'] = !empty( $hazmat_settings['_ph_ups_record_number'] ) ? $hazmat_settings['_ph_ups_record_number'] : ' ';
									$req['ClassDivisionNumber'] = !empty( $hazmat_settings['_ph_ups_class_division_no'] ) ? $hazmat_settings['_ph_ups_class_division_no'] : ' ';
									$req['IDNumber'] = !empty( $hazmat_settings['_ph_ups_commodity_id'] ) ? $hazmat_settings['_ph_ups_commodity_id'] : ' ';
									$req['TransportationMode'] = $mode;
									$req['RegulationSet'] = $hazmat_settings['_ph_ups_hm_regulations'];
									$req['PackagingGroupType'] = !empty( $hazmat_settings['_ph_ups_package_group_type'] ) ? $hazmat_settings['_ph_ups_package_group_type'] : ' ';
									$req['Quantity'] = $product_detail['quantity'];
									$req['UOM'] = ( $this->uom == 'LB' ) ? 'pound' : 'kg' ;
									$req['ProperShippingName'] = !empty( $hazmat_settings['_ph_ups_shipping_name'] ) ? $hazmat_settings['_ph_ups_shipping_name'] : ' ';
									$req['TechnicalName'] = !empty( $hazmat_settings['_ph_ups_technical_name'] ) ? $hazmat_settings['_ph_ups_technical_name'] : ' ';
									$req['AdditionalDescription']= !empty( $hazmat_settings['_ph_ups_additional_description'] ) ? $hazmat_settings['_ph_ups_additional_description'] : ' ';
									$req['PackagingType'] = !empty( $hazmat_settings['_ph_ups_package_type'] ) ? $hazmat_settings['_ph_ups_package_type'] : ' ';
									$req['PackagingTypeQuantity'] = $product_detail['quantity'];
									$req['CommodityRegulatedLevelCode'] = $hazmat_settings['_ph_ups_hm_commodity'];
									$req['EmergencyPhone'] = isset( $ups_settings['phone_number'] ) ? $ups_settings['phone_number'] : '';
									$req['EmergencyContact'] = isset( $ups_settings['ups_display_name'] ) ? $ups_settings['ups_display_name'] : '';

									$new_req_arr[] = array('HazMat'=>$req);
								}	
							}

							if( isset( $package['Package']['items'] ) )
							{
								unset($package['Package']['items']);
							}
							
							if($this->is_hazmat_product && isset($new_req_arr) )
							{	
								$hazmat_package_id +=1;
								$hazmat_array['Package']['PackageServiceOptions']['PackageIdentifier'] = $hazmat_package_id;
								$hazmat_array['Package']['PackageServiceOptions']['HazMat']	=	array_merge(array('multi_node'=>1), $new_req_arr);
								$package = array_merge_recursive($package, $hazmat_array);

								unset($new_req_arr);
							}

							$request_arr['Shipment']['package'][] = apply_filters( 'ph_ups_shipment_confirm_packages', $package, $shipment['packages'][$package_key], $order->get_id() );
						}

						if( $this->shipper_release_indicator && !$return_label && isset( $request_arr['Shipment']['package'] ) && is_array( $request_arr['Shipment']['package'] ) )
						{
							
							$shipper_release_indicator 	= false;
							$shipper_release_countries 	= array('US','PR');

							if( in_array( $from_address['country'], $shipper_release_countries ) && in_array( $to_address['country'], $shipper_release_countries) && $from_address['country'] == $to_address['country'] )
							{
								foreach ($request_arr['Shipment']['package'] as $key => $package_items) {
									
									if( !empty($package_items) && is_array($package_items) && isset($package_items['Package']) && isset($package_items['Package']['PackageServiceOptions']) && ( isset($package_items['Package']['PackageServiceOptions']['DeliveryConfirmation']) || isset($package_items['Package']['PackageServiceOptions']['COD']) ) )
									{

										if( isset($package_items['Package']['PackageServiceOptions']['COD']) ) {

											$this->admin_diagnostic_report( 'UPS: Shipper Release Indicator is not added because it is COD Shipment' );
										}else if(isset($package_items['Package']['PackageServiceOptions']['DeliveryConfirmation'])) {
											
											$this->admin_diagnostic_report( 'UPS: Shipper Release Indicator is not added because Signature required for this Package' );
										}

										$shipper_release_indicator 	= false;
										
									}else{
										$shipper_release_indicator 	= true;
									}

									if( $shipper_release_indicator && !empty($package_items) && is_array($package_items) && isset($package_items['Package']) )
									{
										$request_arr['Shipment']['package'][$key]['Package']['PackageServiceOptions']['ShipperReleaseIndicator'] = '';
									}
								}
							}
						}
						
						$shipmentServiceOptions = array();

						// Set delivery confirmation at shipment level for international shipment
						// UPS doesn't support Delivery Confirmation for Return Label
						if( isset($delivery_confirmation) &&  !empty($delivery_confirmation) && !$return_label ) {
							$shipmentServiceOptions['DeliveryConfirmation']['DCISType'] = ($delivery_confirmation == 3) ? 2 : 1;
						}

						if ( $this->isc=='yes') {

							if ($alcoholicbeveragesindicator=='yes') {
							
								$shipmentServiceOptions['RestrictedArticles']['AlcoholicBeveragesIndicator'] = 1;
							}

							if ($diagnosticspecimensindicator=='yes') {
							
								$shipmentServiceOptions['RestrictedArticles']['DiagnosticSpecimensIndicator'] = 1;
							}

							if ($perishablesindicator=='yes') {
								
								$shipmentServiceOptions['RestrictedArticles']['PerishablesIndicator'] = 1;
							}

							if ($plantsindicator=='yes') {
								
								$shipmentServiceOptions['RestrictedArticles']['PlantsIndicator'] = 1;
							}

							if ($seedsindicator=='yes') {
								
								$shipmentServiceOptions['RestrictedArticles']['SeedsIndicator'] = 1;
							}

							if ($specialexceptionsindicator=='yes') {

								$shipmentServiceOptions['RestrictedArticles']['SpecialExceptionsIndicator'] = 1;
							}

							if ($tobaccoindicator=='yes') {
								
								$shipmentServiceOptions['RestrictedArticles']['TobaccoIndicator'] = 1;
							}
						}

						//For Worldwide Express Freight Service
						if( $shipment['shipping_service'] == 96 ) {
							$request_arr['Shipment']['NumOfPiecesInShipment'] = $numofpieces;
						}
						// Negotiated Rates Flag
						if ( $ups_negotiated ) {
							$request_arr['Shipment']['RateInformation']['NegotiatedRatesIndicator']	=	'';
						}
						
						// For return label, Ship From address will be set as Shipping Address of order.
						if($return_label) {

							$request_arr['Shipment']['ShipFrom']	=	array(
								'CompanyName'	=>	substr( $to_address['name'], 0, 34 ),
								'AttentionName'	=>	substr( $to_address['company'], 0, 34 ),
								'Address'		=>	array(
									'AddressLine1'	=>	substr( $to_address['address_1'], 0, 34 ),
									'City'			=>	substr( $to_address['city'], 0, 29 ),
									'PostalCode'	=>	$to_address['postcode'],
									'CountryCode'	=>	$to_address['country'],
								),
							);
							
							// State Code valid for certain countries only
							if(in_array($to_address['country'], $this->countries_with_statecodes)){
								$request_arr['Shipment']['ShipFrom']['Address']['StateProvinceCode']	=	$to_address['state'];
							}

							if( $this->recipients_tin ) {
								$request_arr['Shipment']['ShipFrom']['TaxIdentificationNumber'] = $shipto_recipients_tin;
							}

							if( !empty($to_address['address_2']) ) {
								$request_arr['Shipment']['ShipFrom']['Address']['AddressLine2']	=	substr( $to_address['address_2'], 0, 34 );
							}
						}
						else{
							if(	$this->ship_from_address_different_from_shipper == 'yes' ) {
								$different_ship_from_address = $this->get_ship_from_address($ups_settings);
								if( ! empty($ship_from_address) )	$request_arr['Shipment']['ShipFrom'] = $different_ship_from_address;
							}
						}
						
						if($sat_delivery){
							$shipmentServiceOptions['SaturdayDelivery']	=	'';
						}
                        
                        //PDS-79
                        //Import control is used to support ship from out side country
						if( $ship_options['international_delivery_confirmation_applicable'] && $import_control == 'true' ) {

							$shipmentServiceOptions['ImportControlIndicator']=	'';
							$shipmentServiceOptions['LabelMethod']	= array(
								'Code' => '05',
							);
						}
                        
                        //Its a UPS service supporting nature
						if($this->carbonneutral_indicator == 'true' ) {

							$shipmentServiceOptions['UPScarbonneutralIndicator']=	'';
						}

						//PDS-129
						if($this->is_hazmat_product){

							$request_arr['Shipment']['DGSignatoryInfo']  = array(
								'Name' => $from_address['name'], 
								'Title' => $from_address['company'], 
								'Place' => $from_address['city'], 
								'Date' => date('Ymd'), 
							);
						}

						if( in_array( $request_arr['Shipment']['Service']['Code'], array('M6', 'M5', 'M4') )  ){

							// For International Shipment only supported USPS Endorsement Value - 5 (No Service)
							if( $shipment['shipping_service'] == 'M5' || $shipment['shipping_service'] == 'M6' ) {
								$this->usps_endorsement = '5';
							}

							$request_arr['Shipment']['USPSEndorsement']= $this->usps_endorsement;
							
							$request_arr['Shipment']['PackageID']=$order->get_id();

							if( isset($request_arr['Shipment']['ReferenceNumber']) )
							{
								unset($request_arr['Shipment']['ReferenceNumber']);
							} 
						}

						if( $this->commercial_invoice && ( $from_address['country'] != $to_address['country'] ) && !(in_array($from_address['country'], $this->eu_array) && in_array($to_address['country'], $this->eu_array) ) ){ // Commercial Invoice is available only for international shipments
							
							if ($return_label) {

								if( array_key_exists($from_address['country'], $this->satelliteCountries) ) {

									$soldToCountry 	= $this->satelliteCountries[$from_address['country']];
								} else {
									$soldToCountry 	= $from_address['country'];
								}

								$soldToPhone	=	(strlen($from_address['phone']) < 10) ? '0000000000' : $from_address['phone'];
								$company_name	=	substr( $from_address['company'], 0, 34 );

								$sold_to_arr	=	array(
									'CompanyName'	=>	! empty($company_name) ? $company_name : '-',
									'AttentionName'	=>	substr( $from_address['name'], 0, 34 ),
									'PhoneNumber'	=>	preg_replace("/[^0-9]/", "", $from_address['phone']),
									'Address'		=>	array(
										'AddressLine1'		=>	substr( $from_address['address_1'], 0, 34 ),
										'City'				=>	substr( $from_address['city'], 0, 29 ),
										'CountryCode'		=>	$soldToCountry,
										'PostalCode'		=>	preg_replace("/[^A-Z0-9]/", "", $from_address['postcode']),
									),
								);

								if ( ! empty($from_address['state']) ) {
									$sold_to_arr['Address']['StateProvinceCode'] = $from_address['state'];
								}

								if ( $this->tin_number ) {
									$sold_to_arr['TaxIdentificationNumber'] = $this->tin_number;
								}

								if ( !empty($from_address['address_2']) ) {
									$sold_to_arr['Address']['AddressLine2'] = substr( $from_address['address_2'], 0, 34 );
								}
							
							} else {
								
								// Sold To address should be Billing Address
								$billing_address 	= $this->get_billing_address( $order );

								$soldToPhone	=	(strlen($billing_address['phone']) < 10) ? '0000000000' : $billing_address['phone'];
								$company_name	=	substr( $billing_address['company'], 0, 34 );

								if( array_key_exists($billing_address['country'], $this->satelliteCountries) ) {

									$soldToCountry 	= $this->satelliteCountries[$billing_address['country']];
								} else {
									$soldToCountry 	= $billing_address['country'];
								}

								$sold_to_arr	=	array(
									'CompanyName'	=>	! empty($company_name) ? $company_name : '-',
									'AttentionName'	=>	substr( $billing_address['name'], 0, 34 ),
									'PhoneNumber'	=>	preg_replace("/[^0-9]/", "", $billing_address['phone']),
									'Address'		=>	array(
										'AddressLine1'	=>	substr( $billing_address['address_1'], 0, 34 ),
										'City'			=>	substr( $billing_address['city'], 0, 29 ),
										'CountryCode'	=>	$soldToCountry,
										'PostalCode'	=>	preg_replace("/[^A-Z0-9]/", "", $billing_address['postcode']),
									),
								);
								
								if(in_array($billing_address['country'], $this->countries_with_statecodes)){ // State Code valid for certain countries only
									$sold_to_arr['Address']['StateProvinceCode']	=	$billing_address['state'];
								}

								if ( $this->recipients_tin ) {
									$sold_to_arr['TaxIdentificationNumber'] = $recipients_tin;
								}

								if ( !empty($billing_address['address_2']) ) {
									$sold_to_arr['Address']['AddressLine2'] = substr( $billing_address['address_2'], 0, 34 );
								}
							}

							$request_arr['Shipment']['SoldTo'] =	$sold_to_arr;
							
							$invoice_products	=	array();

							$total_item_cost 	= 0;

							if( !empty($order) && is_a( $order, 'WC_Order' ) )
							{

								// To support Mix and Match Product
								do_action( 'ph_ups_before_get_items_from_order', $order );

								$order_items 			= $order->get_items();

								if( !empty($order_items) )
								{
									foreach ( $order_items as  $item_key => $item_values ) {
										$total_item_cost += $item_values->get_total();
									}
								}

								// To support Mix and Match Product
								do_action( 'ph_ups_after_get_items_from_order', $order );
								
								$eei_base_currency 		= "USD";
								$wc_conversion_rate 	= get_option('woocommerce_multicurrency_rates');

								if( $order_currency != $eei_base_currency && !empty($wc_conversion_rate) && is_array($wc_conversion_rate) )
								{

									$eei_currency_rate 		= $wc_conversion_rate[$eei_base_currency];
									$order_currency_rate 	= $wc_conversion_rate[$order_currency];

									$conversion_rate 		= $eei_currency_rate / $order_currency_rate;
									$total_item_cost 		*= $conversion_rate;
								}
							}

							$ship_from_country 		= $from_address['country'];
							$ship_to_country 		= $to_address['country'];

							$invoice_products 		= $this->get_ups_invoice_products($shipment,$from_address['country'],$order, $return_label,$ship_to_country,$total_item_cost);

							// Support for shipping multiple address
							$invoice_products= apply_filters( 'ph_ups_shipment_confirm_request_customize_product_details', $invoice_products,$shipment,$this->weight_unit,$from_address['country']);

							$billing_address 	= $this->get_billing_address( $order );

							$shipmentServiceOptions['InternationalForms']	=	array(
								//'FormType'				=>	'01',
								'InvoiceNumber'			=>	'',
								'InvoiceDate'			=>	date("Ymd"),
								'PurchaseOrderNumber'	=>	$order->get_order_number(),
								'Contacts'				=>	array(
									'SoldTo'	=>	array(
										'Name'						=>	substr( $billing_address['company'], 0, 34 ),
										'AttentionName'				=>	substr( $billing_address['name'], 0, 34 ),
										'TaxIdentificationNumber'	=>	$recipients_tin,
										'Phone'						=>	array(
											'Number'	=>	$soldToPhone,
										),
										'Address'					=>	array(
											'AddressLine'	=>	substr( $billing_address['address_1'].' '.$billing_address['address_2'], 0, 34 ),
											'City'			=>	substr( $billing_address['city'], 0, 29 ),
											'PostalCode'	=>	$billing_address['postcode'],
											'CountryCode'	=>	$billing_address['country'],
										)
									)
								),
								'ExportDate'			=>	date('Ymd'),
								'ExportingCarrier'		=>	'UPS',
								'ReasonForExport'		=>	'SALE',
								'CurrencyCode'			=>	$order_currency,
							);

							//PDS-124
							if( $this->commercial_invoice_shipping ) {

								$shipping_total 	= round($order->get_shipping_total(),2);
								$shipmentServiceOptions['InternationalForms']['FreightCharges']['MonetaryValue']=$shipping_total ;

							}

							if( !$this->edi_on_label ) {

								$shipmentServiceOptions['InternationalForms']['AdditionalDocumentIndicator'] = '1';
							}

							$form_types = array();

								// 01 - Commercial Invoice
							$form_types[]	= array(
								'FormType'	=> '01',

							);

							if( $this->nafta_co_form && !$return_label && isset($this->nafta_supported_countries[$ship_from_country]) && in_array($ship_to_country, $this->nafta_supported_countries[$ship_from_country]) )
							{

								// 04 - NAFTA
								$form_types[]	= array(
									'FormType'	=> '04',
								);

								$shipmentServiceOptions['InternationalForms']['AdditionalDocumentIndicator'] = '1';
								$shipmentServiceOptions['InternationalForms']['Contacts']['Producer'] = array(
									'Option' 		=> $this->nafta_producer_option,
									'CompanyName' 	=> substr( $from_address['company'], 0, 34 ),
									'TaxIdentificationNumber' => $this->tin_number,
									'Address'		=> array(
										'AddressLine1'	=>	substr( $from_address['address_1'], 0, 34 ),
										'AddressLine2'	=>	substr( $from_address['address_2'], 0, 34 ),
										'City'			=>	substr( $from_address['city'], 0, 29 ),
										'PostalCode'	=>	$from_address['postcode'],
										'CountryCode'	=>	$from_address['country'],
									),
								);

								$blanket_begin_period = !empty($this->blanket_begin_period) ? str_replace('-', '', $this->blanket_begin_period) : '';
								$blanket_end_period 	= !empty($this->blanket_end_period) ? str_replace('-', '', $this->blanket_end_period) : '';
								
								$shipmentServiceOptions['InternationalForms']['BlanketPeriod'] = array(
									'BeginDate'	=> $blanket_begin_period,
									'EndDate'	=> $blanket_end_period,
								);

							}

							if( $this->eei_data && !$return_label && $total_item_cost >= 2500 && in_array( $ship_from_country, array('US','PR') ) )
							{

								// 11 - EEI
								$form_types[]	= array(
									'FormType'	=> '11',
								);

								$shipmentServiceOptions['InternationalForms']['AdditionalDocumentIndicator'] = '1';
								$shipmentServiceOptions['InternationalForms']['EEIFilingOption'] = array(
									'Code'			=>	'1',	// 1 - Shipper filed, 2 - AES Direct, 3 - UPS filed
									'ShipperFiled'	=>	array(
										'Code'		=> $this->eei_shipper_filed_option,
									),
								);

								if( $this->eei_shipper_filed_option == 'A' ){
									$shipmentServiceOptions['InternationalForms']['EEIFilingOption']['ShipperFiled']['PreDepartureITNNumber'] 	= $this->eei_pre_departure_itn_number;
								}else if( $this->eei_shipper_filed_option == 'B' ){
									$shipmentServiceOptions['InternationalForms']['EEIFilingOption']['ShipperFiled']['ExemptionLegend'] 		= $this->eei_exemption_legend;
								}else{
									$shipmentServiceOptions['InternationalForms']['EEIFilingOption']['ShipperFiled']['EEIShipmentReferenceNumber'] = $order->get_order_number();
								}
								

								$shipmentServiceOptions['InternationalForms']['Contacts']['ForwardAgent'] = array(
									
									'CompanyName'				=>	substr( $from_address['company'], 0, 34 ),
									'TaxIdentificationNumber'	=>	$this->tin_number,
									'Address'					=>	array(
										'AddressLine1'				=>	substr( $from_address['address_1'], 0, 34 ),
										'AddressLine2'				=>	substr( $from_address['address_2'], 0, 34 ),
										'City'						=>	substr( $from_address['city'], 0, 29 ),
										'StateProvinceCode'			=>	$from_address['state'],
										'PostalCode'				=>	$from_address['postcode'],
										'CountryCode'				=>	$from_address['country'],
									)
								);

								$shipmentServiceOptions['InternationalForms']['Contacts']['UltimateConsignee'] = array(
									'CompanyName' 			=>	substr( $to_address['company'], 0, 34 ),
									'Address'				=>	array(
										'AddressLine1'			=>	substr( $to_address['address_1'], 0, 34 ),
										'AddressLine2'			=>	substr( $to_address['address_2'], 0, 34 ),
										'City'					=>	substr( $to_address['city'], 0, 29 ),
										'StateProvinceCode'		=>	$to_address['state'],
										'PostalCode'			=>	$to_address['postcode'],
										'CountryCode'			=>	$to_address['country'],
									),
								);
							
								$shipmentServiceOptions['InternationalForms']['InBondCode'] 			= '70';
								$shipmentServiceOptions['InternationalForms']['PointOfOrigin'] 			= $from_address['state'];
								$shipmentServiceOptions['InternationalForms']['PointOfOriginType'] 		= 'S';		// State Postal Code
								$shipmentServiceOptions['InternationalForms']['ModeOfTransport'] 		= $this->eei_mode_of_transport;
								$shipmentServiceOptions['InternationalForms']['PartiesToTransaction'] 	= $this->eei_parties_to_transaction;
							}

							$shipmentServiceOptions['InternationalForms']['FormType']	=	array_merge(array('multi_node'=>1), $form_types);

							if( $return_label ) {
								$shipmentServiceOptions['InternationalForms']['Contacts']['SoldTo'] = array(
									'Name'						=> substr( $from_address['company'], 0, 34 ),
									'AttentionName'				=> substr( $from_address['name'], 0, 34 ),
									'TaxIdentificationNumber'	=> $this->tin_number,
									'Phone'						=>	array(
										'Number'		=> $soldToPhone,
									),
									'Address'					=>	array(
										'AddressLine'	=>	substr( $from_address['address_1'].' '.$from_address['address_2'], 0, 34 ),
										'City'			=>	substr( $from_address['city'], 0, 29 ),
										'PostalCode'	=>	$from_address['postcode'],
										'CountryCode'	=>	$from_address['country'],
									)
								);
							}
							$declaration_statement = isset($this->settings[ 'declaration_statement']) ?  $this->settings[ 'declaration_statement'] : '';
							if( !empty($declaration_statement) ){
								$shipmentServiceOptions['InternationalForms']['DeclarationStatement'] = $declaration_statement;
							}

							if( !$return_label ) {

								if( !empty($this->reason_export)  && $this->reason_export != 'none' ){
									$shipmentServiceOptions['InternationalForms']['ReasonForExport']	=	$this->reason_export;
								}
							}else{
								
								if( !empty($this->return_reason_export)  && $this->return_reason_export != 'none' ){
									$shipmentServiceOptions['InternationalForms']['ReasonForExport']	=	$this->return_reason_export;
								}
							}
							
							if( isset($shipment_terms) && !empty($shipment_terms) ) {
								$shipmentServiceOptions['InternationalForms']['TermsOfShipment']	=	$shipment_terms;
							}

							if( $return_label && in_array($from_address['country'], $this->countries_with_statecodes) ) {
								$shipmentServiceOptions['InternationalForms']['Contacts']['SoldTo']['Address']['StateProvinceCode']	= $from_address['state'];

							}elseif( ! $return_label && in_array($billing_address['country'], $this->countries_with_statecodes)){

								$shipmentServiceOptions['InternationalForms']['Contacts']['SoldTo']['Address']['StateProvinceCode']	= $billing_address['state'];
							}
							$shipmentServiceOptions['InternationalForms']['Product']	=	array_merge(array('multi_node'=>1), $invoice_products);
						}

						if( $this->wcsups->cod && !$return_label ) {
							// Shipment Level COD
							if( $this->wcsups->is_shipment_level_cod_required($to_address['country']) ){
								$codfundscode = in_array( $to_address['country'], array('RU', 'AE') ) ? 1 : $this->eu_country_cod_type;	// 1 for Cash, 9 for Cheque, 1 is available for all the countries
								$cod_value = $this->wcsups->cod_total;
								
								$shipmentServiceOptions['COD']	=	array(
									'CODCode'		=>	3,
									'CODFundsCode'	=>	$codfundscode,
									'CODAmount'		=>	array(
										'MonetaryValue'	=>	(string) $cod_value,
									),
								);
							}
						}
						
						if( $this->tin_number ){
							// $request_arr['Shipment']['ItemizedPaymentInformation']['SplitDutyVATIndicator'] = true;
							$request_arr['Shipment']['Shipper']['TaxIdentificationNumber'] = $this->tin_number;
						}
                        
                        //PDS-79
						if( $ship_options['international_delivery_confirmation_applicable'] && $import_control == 'true' ){

							$ship = $from_address;
							$dest = $to_address;

							if($ship_from_address == 'billing_address'){

								$ship = $to_address;
								$dest = $from_address;
								
							}
							
							$request_arr['Shipment']['Shipper']	=	array(
								'AttentionName'		=>	substr( $ship['name'], 0, 34 ),
								'Name'				=>	substr( $ship['company'], 0, 34 ),
								'PhoneNumber'		=>	preg_replace("/[^0-9]/", "", $ship['phone']),
								'EMailAddress'	    =>	$ship['email'],
								'ShipperNumber'		=>	$ups_shipper_number,
								'Address'			=>	array(
									'AddressLine1'		=>	substr( $ship['address_1'], 0, 34 ),
									'AddressLine2'		=>	substr( $to_address['address_2'], 0, 34 ),
									'City'				=>	substr( $ship['city'], 0, 29 ),
									'StateProvinceCode'	=>	$ship['state'],
									'CountryCode'		=>	$ship['country'],
									'PostalCode'		=>	$ship['postcode'],
								),
							);

							if( $this->tin_number ) {
								$request_arr['Shipment']['Shipper']['TaxIdentificationNumber'] = $this->tin_number;
							}
							
							$request_arr['Shipment']['ShipFrom'] = array(
								'CompanyName'	=>	substr( $dest['company'], 0, 34 ),
								'AttentionName'	=>	substr( $dest['name'], 0, 34 ),
								'PhoneNumber'	=>	preg_replace("/[^0-9]/", "", $dest['phone']),
								'EMailAddress'	=>	$dest['email'],
								'Address'		=>	array(
									'AddressLine1'		=>	substr( $dest['address_1'], 0, 34 ),
									'City'				=>	substr( $dest['city'], 0, 29 ),
									'CountryCode'		=>	$dest['country'],
									'StateProvinceCode'	=>	$dest['state'],
									'PostalCode'		=>	$dest['postcode'],
								)
							);
                            
							if( $this->recipients_tin ) {
								$request_arr['Shipment']['ShipFrom']['TaxIdentificationNumber'] = $shipto_recipients_tin;
							}

							if( !empty($dest['address_2']) ) {

								$request_arr['Shipment']['ShipFrom']['Address']['AddressLine2'] = substr( $dest['address_2'], 0, 34 );
							}

							$request_arr['Shipment']['ShipTo']	=	array(
								'CompanyName'	=>	substr( $ship['company'], 0, 34 ),
								'AttentionName'	=>	substr( $ship['name'], 0, 34 ),
								'PhoneNumber'	=>	preg_replace("/[^0-9]/", "", $ship['phone']),
								'EMailAddress'	=>	$ship['email'],
								'Address'		=>	array(
									'AddressLine1'		=>	substr( $ship['address_1'], 0, 34 ),
									'AddressLine2'		=>	substr( $ship['address_2'], 0, 34 ),
									'City'				=>	substr( $ship['city'], 0, 29 ),
									'CountryCode'		=>	$ship['country'],
									'PostalCode'		=>	$ship['postcode'],
								)
							);

							if(in_array($ship['country'], $this->countries_with_statecodes)){ // State Code valid for certain countries only
								$request_arr['Shipment']['ShipTo']['Address']['StateProvinceCode']	=	$ship['state'];
							}

							$selected_shipment_service = $this->get_service_code_for_country( $shipment['shipping_service'], $from_address['country'] );

							if( $this->remove_recipients_phno && !in_array($selected_shipment_service, $this->phone_number_services) && $from_address['country'] == $ship['country'] )
							{
								if( isset($request_arr['Shipment']['ShipTo']['PhoneNumber']) )
								{
									unset($request_arr['Shipment']['ShipTo']['PhoneNumber']);
								}
							}

							if( $this->tin_number ) {
								$request_arr['Shipment']['ShipTo']['TaxIdentificationNumber'] = $this->tin_number;
							}

							$request_arr['Shipment']['SoldTo']	=	array(
								'CompanyName'	=>	substr( $ship['company'], 0, 34 ),
								'AttentionName'	=>	substr( $ship['name'], 0, 34 ),
								'PhoneNumber'	=>	preg_replace("/[^0-9]/", "", $ship['phone']),
								'Address'		=>	array(
									'AddressLine1'		=>	substr( $ship['address_1'], 0, 34 ),
									'City'				=>	substr( $ship['city'], 0, 29 ),
									'CountryCode'		=>	$ship['country'],
									'StateProvinceCode' => $ship['state'],
									'PostalCode'		=>	preg_replace("/[^A-Z0-9]/", "", $ship['postcode']),
								),
							);
							if (in_array($ship['country'], $this->countries_with_statecodes)) { // State Code valid for certain countries only
									$request_arr['Shipment']['SoldTo']['Address']['StateProvinceCode']	= $ship['state'];
							}
							
							if( $this->tin_number ) {
								$request_arr['Shipment']['SoldTo']['TaxIdentificationNumber'] = $this->tin_number;
							}

							if ( !empty($ship['address_2']) ) {
									$request_arr['Shipment']['SoldTo']['Address']['AddressLine2'] = substr( $ship['address_2'], 0, 34 );
							}

							if ( in_array($dest['country'], array('US')) &&  in_array($ship['country'], array('PR', 'CA') ) ) {

								if ($order_total < 1) {
									$order_total = 1;
								}

								$request_arr['Shipment']['InvoiceLineTotal']['CurrencyCode'] 	= $order_currency;
								$request_arr['Shipment']['InvoiceLineTotal']['MonetaryValue'] 	= (int)$order_total;
								// For Return Shipment check condition in reverse order
							} else if ($return_label && in_array($ship['country'], array('US')) &&  in_array($dest['country'], array('PR', 'CA'))) {

								if ($order_total < 1) {
									$order_total = 1;
								}

								$request_arr['Shipment']['InvoiceLineTotal']['CurrencyCode'] 	= $order_currency;
								$request_arr['Shipment']['InvoiceLineTotal']['MonetaryValue'] 	= (int)$order_total;
							}
						}

						if( !empty($this->email_notification) && in_array( $request_arr['Shipment']['Service']['Code'], $this->email_notification_services ) ){
							$emails= array();
							foreach ($this->email_notification as $notifier) {
								switch ( $notifier ) {
									// Case 0 and 1 for backward compatibility, remove it after few version release 3.9.16.3
									case 'recipient':
									case 1:
									array_push( $emails, array('EMailAddress' => $order->billing_email) );
									break;
									//sender
									case 'sender':
									case 0:
									array_push( $emails, array('EMailAddress' => $shipper_email) );
									break;
								}
							}
							
							if( !empty($emails) ){

								// $shipmentServiceOptions['Notification']['EMailMessage']['EMailAddress']['multi_node']	=	1;
								$shipmentServiceOptions['Notification']['EMailMessage']['EMailAddress'] = array_merge(array('multi_node'=>1), $emails);
								$shipmentServiceOptions['Notification']['NotificationCode'] = 6;
								$shipmentServiceOptions['Notification']['FromEMailAddress'] = $shipper_email;
								$shipmentServiceOptions['Notification']['EMailMessage']['UndeliverableEMailAddress'] = $shipper_email;
							}
						}
						
						//Set Direct delivery in the actual request
						if( !empty($directdeliveryonlyindicator) ) {
							$shipmentServiceOptions['DirectDeliveryOnlyIndicator'] = $directdeliveryonlyindicator;
						}
						
						if(sizeof($shipmentServiceOptions)){
							$request_arr['Shipment']['ShipmentServiceOptions']	=	empty( $request_arr['Shipment']['ShipmentServiceOptions'] ) ? $shipmentServiceOptions : array_merge($shipmentServiceOptions,$request_arr['Shipment']['ShipmentServiceOptions'] );
						}
						

						$request_arr['LabelSpecification']['LabelPrintMethod']	=	$this->get_code_from_label_type( $print_label_type );
						$request_arr['LabelSpecification']['HTTPUserAgent']		=	'Mozilla/4.5';
						
						if( 'zpl' == $print_label_type || 'epl' == $print_label_type || 'png' == $print_label_type ) {
							$request_arr['LabelSpecification']['LabelStockSize']	=	array('Height' => 4, 'Width' => 6);
						}
						$request_arr['LabelSpecification']['LabelImageFormat']	=	$this->get_code_from_label_type( $print_label_type );
						
						if( $return_label )
						{
							$include_return_weight		= isset($_GET['rt_weight']) ? str_replace( '\"','', str_replace(']','', str_replace('[','',$_GET['rt_weight'])) ) : '';
							$include_return_length		= isset($_GET['rt_length']) ? str_replace( '\"','', str_replace(']','', str_replace('[','',$_GET['rt_length'])) ) : '';
							$include_return_width		= isset($_GET['rt_width']) ? str_replace( '\"','', str_replace(']','', str_replace('[','',$_GET['rt_width'])) ) : '';
							$include_return_height		= isset($_GET['rt_height']) ? str_replace( '\"','', str_replace(']','', str_replace('[','',$_GET['rt_height'])) ) : '';
							$include_return_insurance		= isset($_GET['rt_insurance']) ? str_replace( '\"','', str_replace(']','', str_replace('[','',$_GET['rt_insurance'])) ) : '';

							if( !empty($include_return_weight) )
							{
								$return_weight	= explode(',',$include_return_weight);
							}

							if( !empty($include_return_length) && !empty($include_return_width) && !empty($include_return_height) )
							{
								$return_length	= explode(',',$include_return_length);
								$return_width	= explode(',',$include_return_width);
								$return_height	= explode(',',$include_return_height);
							}

							if( !empty($include_return_insurance) )
							{
								$return_insurance	= explode(',',$include_return_insurance);
							}
							
							if( !empty($return_weight))
							{
								for($i=0; $i < count($return_weight); $i++){

									if( empty($return_weight[$i]) )
										continue;

									$request_arr['Shipment']['package'][$i]['Package']	=	array(
										'PackagingType'	=>	array(
											'Code'				=>	'02',
											'Description'	=>	'Package/customer supplied'
										),
										'Description'	=> 'Rate',
										'PackageWeight' => array(
											'UnitOfMeasurement'	=>	array(
												'Code'	=>	$this->weight_unit,
											),
											'Weight'	=>	$return_weight[$i]
										),
									);

									if( !empty($return_length[$i]) && !empty($return_width[$i]) && !empty($return_height[$i]) )
									{
										$request_arr['Shipment']['package'][$i]['Package']['Dimensions'] = array(
											'UnitOfMeasurement'	=>	array(
												'Code'	=>	$this->dim_unit,
											),
											'Length'	=>	$return_length[$i],
											'Width'		=>	$return_width[$i], 
											'Height'	=>	$return_height[$i],
										);
									}

									if( !empty($return_insurance[$i]) )
									{
										$request_arr['Shipment']['package'][$i]['Package']['PackageServiceOptions'] = array(
											'InsuredValue'	=> array(
												'CurrencyCode'	=>$this->wcsups->get_ups_currency(),
												'MonetaryValue'	=> $return_insurance[$i],
											)
										);
									}

								}
							}
						}

						$request_arr	=	apply_filters('wf_ups_shipment_confirm_request_data', $request_arr, $order);
						// Converting array data to xml
						$xml_request .= $this->wcsups->wf_array_to_xml($request_arr);
						
						$xml_request .= '</ShipmentConfirmRequest>';

						if($this->is_hazmat_product)
						{
							$exploded_request = explode( "</TransactionReference>", $xml_request );
							$xml_request  = $exploded_request[0]."</TransactionReference>";
							$xml_request .= "<SubVersion>1701</SubVersion>";
							$xml_request .= $exploded_request[1];
						}

						$xml_request	=	apply_filters('wf_ups_shipment_confirm_request', $xml_request, $order, $shipment );
						$shipment_requests[]    = $this->modfiy_encoding($xml_request);
					}
					$service_index++;
				}
			}
			return $shipment_requests;
		}
		/*
		* Return products contains in the individual shipment
		*/
		function get_ups_invoice_products($shipment, $from_address, $order=array(), $return_label = false, $ship_to_country = '', $total_item_cost = 0 ) {
			
			$product_details 	=	array();
			$ship_from_country 	=	$from_address;
			$order_item 		=	array();
			$export_info_array	= array("LC", "LV", "SS", "MS", "GS", "DP", "HR", "UG", "IC", "SC", "DD", "HH", "SR", "TE", "TL", "IS", "CR", "GP", "RJ", "TP", "IP", "IR", "DB", "CH", "RS", "OS",
			);

			if( !empty($order)){

				// To support Mix and Match Product
				do_action( 'ph_ups_before_get_items_from_order', $order );

				$orderItems = $order->get_items();

				foreach( $orderItems as $orderItem ) {

					$item_id 			= $orderItem['variation_id'] ? $orderItem['variation_id'] : $orderItem['product_id'];
					$order_item[$item_id] 		= $orderItem;
				}

				// To support Mix and Match Product
				do_action( 'ph_ups_after_get_items_from_order', $order );
			}

			foreach ($shipment['packages'] as $package_key => $package) {
				if(isset($package['Package']['items'])){
					foreach ($package['Package']['items'] as $item_index => $item) {
						$product_id=(wp_get_post_parent_id($item->get_id())==0) ? $item->get_id() : wp_get_post_parent_id($item->get_id());
						$item_id=$item->get_id();
						$product_data 		= ( is_a( $item, 'WC_Product') || is_a( $item, 'wf_product') ) ? $item : wc_get_product( $item_id );
						if(isset($product_details[$item_id]))
						{
							$product_unit_weight	= ( WC()->version < '2.7.0' ) ? woocommerce_get_weight( $product_data->get_weight(), $this->weight_unit ) : wc_get_weight( $product_data->get_weight(), $this->weight_unit );
							$product_details[$item_id]['Unit']['Number']+=1;
							$product_line_weight	=	$product_unit_weight	*	$product_details[$item_id]['Unit']['Number'];
							$product_details[$item_id]['ProductWeight']['Weight']=$product_line_weight;

							if( $this->eei_data && !$return_label && $total_item_cost >= 2500 && in_array( $ship_from_country, array('US','PR') ) )
							{
								$product_details[$item_id]['ScheduleB']['Quantity'] = $product_details[$item_id]['Unit']['Number'];
								$product_details[$item_id]['SEDTotalValue'] 		= round($product_price * $product_details[$item_id]['Unit']['Number'] ,2);
							}

						}
						else
						{
							// Include only those products which require shipping
							if( ( is_a( $product_data, 'WC_Product') || is_a( $product_data, 'wf_product') ) && $product_data->needs_shipping() ) {

								$product_unit_weight	= ( WC()->version < '2.7.0' ) ? woocommerce_get_weight( $product_data->get_weight(), $this->weight_unit ) : wc_get_weight( $product_data->get_weight(), $this->weight_unit );
								$product_quantity		= 1;
								$product_line_weight	= $product_unit_weight	*	$product_quantity;
								$hst 					= get_post_meta( $item_id, '_ph_ups_hst_var', true );

								if (empty($hst)) {

									$hst 				= get_post_meta( $product_id, '_wf_ups_hst', true );
								}

								$product_title 			= ($product_id!=$item_id) ? strip_tags($product_data->get_formatted_name()) : $product_data->get_title();

								if( $this->remove_special_char_product ) {

									$product_title 	= preg_replace('/[^A-Za-z0-9-()# ]/', '', $product_title);
									$product_title 	= htmlspecialchars($product_title);
								} else {
									$product_title 	= htmlspecialchars($product_title);
								}

								$product_title 			= ( strlen( $product_title ) >= 35 ) ? substr( $product_title, 0, 32 ).'...' : $product_title;

								if($this->discounted_price){

									$product_price 			= isset($order_item[$item_id])?($order_item[$item_id]->get_total()/$order_item[$item_id]->get_quantity()):$product_data->get_price();
								}else{
									$product_price = round($product_data->get_price());
								}

								$countryofmanufacture 	= get_post_meta($product_id,'_ph_ups_manufacture_country',true);

								if( empty($countryofmanufacture) ) {
									$countryofmanufacture = $from_address;
								}

								$product_details[$item_id]= array(
									'Description'	=>	$this->ph_get_commercial_invoice_description($product_id, $product_title, $item_id),
									'Unit'			=>	array(
										'Number'	=>	$product_quantity,
										'UnitOfMeasurement'	=>	array('Code'	=>	'EA'), // EA = Each
										'Value'		=>	round($product_price,2),
									),
									'OriginCountryCode'	=>	$countryofmanufacture,
									'NumberOfPackagesPerCommodity'	=>	'1',
									'ProductWeight'	=>	array(
										'UnitOfMeasurement'	=>	array(
											'Code'	=> $this->weight_unit,
										),
										'Weight'			=>	$product_line_weight,
									),
									'CommodityCode' => $hst,
								);

								if( $this->nafta_co_form && !$return_label && isset($this->nafta_supported_countries[$ship_from_country]) && in_array($ship_to_country, $this->nafta_supported_countries[$ship_from_country]))
								{
									$net_cost_code 			= get_post_meta($product_id,'_ph_net_cost_code',true);
									$preference_criteria 	= get_post_meta($product_id,'_ph_preference_criteria',true);
									$producer_info 			= get_post_meta($product_id,'_ph_producer_info',true);

									$begin_date = !empty($this->blanket_begin_period) ? str_replace('-', '', $this->blanket_begin_period) : '';
									$end_date	= !empty($this->blanket_end_period) ? str_replace('-', '', $this->blanket_end_period) : '';

									$product_details[$item_id]['NetCostCode'] = !empty($net_cost_code) ? $net_cost_code : 'NC';
									$product_details[$item_id]['NetCostDateRange'] = array(
										'BeginDate'	=> $begin_date,
										'EndDate'	=> $end_date,
									);
									$product_details[$item_id]['PreferenceCriteria'] = !empty($preference_criteria) ? $preference_criteria : 'A';
									$product_details[$item_id]['ProducerInfo'] = !empty($producer_info) ? $producer_info : 'Yes';
								}

								if( $this->eei_data && !$return_label && $total_item_cost >= 2500 && in_array( $ship_from_country, array('US','PR') ) )
								{

									$export_type		= get_post_meta($product_id,'_ph_eei_export_type',true);
									$export_info		= get_post_meta($product_id,'_ph_eei_export_information',true);
									$scheduleB_num		= get_post_meta($product_id,'_ph_eei_schedule_b_number',true);
									$unit_of_measure	= get_post_meta($product_id,'_ph_eei_unit_of_measure',true);

									$product_details[$item_id]['ScheduleB'] = array(
										'Number'			=> $scheduleB_num,
										'Quantity'			=> $product_quantity,
										'UnitOfMeasurement'	=>	array(
											'Code'	=> !empty($unit_of_measure) ? $unit_of_measure : 'X',
										),
									);

									$product_details[$item_id]['ExportType'] 		= !empty($export_type) ? $export_type : 'D';
									$product_details[$item_id]['SEDTotalValue'] 	= round($product_price * $product_quantity ,2);

									if( !empty($export_info) && in_array($export_info, $export_info_array) )
									{
										$product_details[$item_id]['EEIInformation'] 		= array(
											'ExportInformation'			=> $export_info,
										);
									}

									if( $this->eei_shipper_filed_option == 'A' )
									{
										$itar_exemption_number		= get_post_meta($product_id,'_ph_eei_itar_exemption_number',true);
										$ddtc_information_uom		= get_post_meta($product_id,'_ph_eei_ddtc_information_uom',true);
										$acm_number					= get_post_meta($product_id,'_ph_eei_acm_number',true);

										$product_details[$item_id]['EEIInformation']['License']['LicenseLineValue'] 			= round($product_price,2);
										$product_details[$item_id]['EEIInformation']['DDTCInformation']['ITARExemptionNumber'] 	= $itar_exemption_number;
										$product_details[$item_id]['EEIInformation']['DDTCInformation']['UnitOfMeasurement']['Code'] = $ddtc_information_uom;
										$product_details[$item_id]['EEIInformation']['DDTCInformation']['ACMNumber'] 			= $acm_number;
									}
									
								}

								$product_details[$item_id]= apply_filters( 'wf_ups_shipment_confirm_request_product_details', $product_details[$item_id], $product_data );
							}
						}
					}
				}
			}
			
			$invoice_products 	= array();

			if ( !empty($product_details) ) {

				// To support Bookings Plugin with UPS Compatibility
				$product_details=apply_filters('ph_ups_shipment_confirm_final_product_details',$product_details,$shipment,$order);

				foreach ($product_details as $product_id => $product) {
					$invoice_products[]['Product']=$product;
				}
			}else{
				if( !empty($order)){

					// To support Mix and Match Product
					do_action( 'ph_ups_before_get_items_from_order', $order );

					$orderItems = $order->get_items();

					foreach( $orderItems as $orderItem ) {

						$item_id 			= $orderItem['variation_id'] ? $orderItem['variation_id'] : $orderItem['product_id'];
						$product_data 		= wc_get_product( $item_id );
								// Include only those products which require shipping
						if( is_a( $product_data, 'WC_Product' ) && $product_data->needs_shipping() ) {

							$product_unit_weight	= ( WC()->version < '2.7.0' ) ? woocommerce_get_weight( $product_data->get_weight(), $this->weight_unit ) : wc_get_weight( $product_data->get_weight(), $this->weight_unit );
							$product_quantity		= $orderItem['qty'];
							$product_line_weight	= $product_unit_weight	*	$product_quantity;
							$hst 					= get_post_meta( $orderItem['product_id'], '_wf_ups_hst', true );
							$product_id 			= isset( $orderItem['product_id'] ) ?  $orderItem['product_id'] : $item_id ;
							$product_title 			= ($product_id!=$item_id) ? strip_tags($product_data->get_formatted_name()) : $product_data->get_title();
							$hst 					= get_post_meta( $item_id, '_ph_ups_hst_var', true );

							if (empty($hst)) {

								$hst 				= get_post_meta( $product_id, '_wf_ups_hst', true );
							}

							if( $this->remove_special_char_product ) {

								$product_title 	= preg_replace('/[^A-Za-z0-9-()# ]/', '', $product_title);
								$product_title 	= htmlspecialchars($product_title);
							} else {
								$product_title 	= htmlspecialchars($product_title);
							}

							$product_title 			= ( strlen( $product_title ) >= 35 ) ? substr( $product_title, 0, 32 ).'...' : $product_title;

							if($this->discounted_price){

								$product_price 			= isset($order_item[$item_id])?($order_item[$item_id]->get_total()/$order_item[$item_id]->get_quantity()):$product_data->get_price();
							}else{
								$product_price = round($product_data->get_price());
							}

							$countryofmanufacture 	= get_post_meta($product_id,'_ph_ups_manufacture_country',true);

							if( empty($countryofmanufacture) ) {
								$countryofmanufacture = $from_address;
							}

							$product_details = array(
								'Description'	=>	$this->ph_get_commercial_invoice_description($product_id, $product_title, $item_id),
								'Unit'			=>	array(
									'Number'	=>	$product_quantity,
									'UnitOfMeasurement'	=>	array('Code'	=>	'EA'), // EA = Each
									'Value'		=>	round($product_price,2),
								),
								'OriginCountryCode'	=>	$countryofmanufacture,
								'NumberOfPackagesPerCommodity'	=>	'1',
								'ProductWeight'	=>	array(
									'UnitOfMeasurement'	=>	array(
										'Code'	=> $this->weight_unit,
									),
									'Weight'			=>	$product_line_weight,
								),
								'CommodityCode' => $hst,
							);

							if( $this->nafta_co_form && !$return_label && isset($this->nafta_supported_countries[$ship_from_country]) && in_array($ship_to_country, $this->nafta_supported_countries[$ship_from_country]) )
							{
								$net_cost_code 			= get_post_meta($product_id,'_ph_net_cost_code',true);
								$preference_criteria 	= get_post_meta($product_id,'_ph_preference_criteria',true);
								$producer_info 			= get_post_meta($product_id,'_ph_producer_info',true);

								$begin_date = !empty($this->blanket_begin_period) ? str_replace('-', '', $this->blanket_begin_period) : '';
								$end_date	= !empty($this->blanket_end_period) ? str_replace('-', '', $this->blanket_end_period) : '';

								$product_details['NetCostCode'] = !empty($net_cost_code) ? $net_cost_code : 'NC';
								$product_details['NetCostDateRange'] = array(
									'BeginDate'	=> $begin_date,
									'EndDate'	=> $end_date,
								);
								$product_details['PreferenceCriteria'] = !empty($preference_criteria) ? $preference_criteria : 'A';
								$product_details['ProducerInfo'] = !empty($producer_info) ? $producer_info : 'Yes';
							}

							if( $this->eei_data && !$return_label && $total_item_cost >= 2500 && in_array( $ship_from_country, array('US','PR') ) )
							{
								$export_type		= get_post_meta($product_id,'_ph_eei_export_type',true);
								$export_info		= get_post_meta($product_id,'_ph_eei_export_information',true);
								$scheduleB_num		= get_post_meta($product_id,'_ph_eei_schedule_b_number',true);
								$unit_of_measure	= get_post_meta($product_id,'_ph_eei_unit_of_measure',true);
								
								$product_details['ScheduleB'] = array(
									'Number'			=> $scheduleB_num,
									'Quantity'			=> $product_quantity,
									'UnitOfMeasurement'	=>	array(
										'Code'	=> !empty($unit_of_measure) ? $unit_of_measure : 'X',
									),
								);

								$product_details['ExportType'] 		= !empty($export_type) ? $export_type : 'D';
								$product_details['SEDTotalValue'] 	= round( ($product_data->get_price() / $this->wcsups->conversion_rate) * $product_quantity ,2);

								if( !empty($export_info) && in_array($export_info, $export_info_array) )
								{
									$product_details['EEIInformation'] 		= array(
										'ExportInformation'			=> $export_info,
									);
								}

								if( $this->eei_shipper_filed_option == 'A' )
								{
									$itar_exemption_number		= get_post_meta($product_id,'_ph_eei_itar_exemption_number',true);
									$ddtc_information_uom		= get_post_meta($product_id,'_ph_eei_ddtc_information_uom',true);
									$acm_number					= get_post_meta($product_id,'_ph_eei_acm_number',true);

									$product_details['EEIInformation']['License']['LicenseLineValue'] 				= round(($product_data->get_price() / $this->wcsups->conversion_rate),2);
									$product_details['EEIInformation']['DDTCInformation']['ITARExemptionNumber'] 	= $itar_exemption_number;
									$product_details['EEIInformation']['DDTCInformation']['UnitOfMeasurement']['Code'] = $ddtc_information_uom;
									$product_details['EEIInformation']['DDTCInformation']['ACMNumber'] 				= $acm_number;
								}
							}

							$invoice_products[]['Product'] = apply_filters( 'wf_ups_shipment_confirm_request_product_details', $product_details, $product_data );
						}
					}

					// To support Mix and Match Product
					do_action( 'ph_ups_after_get_items_from_order', $order );
				}
			}

			return $invoice_products;
		}
		/**
		 * Get Ship From Address.
		 */
		private function get_ship_from_address( $settings ) {

			$ship_from_address = null;

			if( ! empty($settings['ship_from_addressline']) ) {

				$ship_from_country_state = $settings['ship_from_country_state'];

				if (strstr($ship_from_country_state, ':')) :
					list( $ship_from_country, $ship_from_state ) = explode(':',$ship_from_country_state);
				else :
					$ship_from_country = $ship_from_country_state;
					$ship_from_state   = '';
				endif;

				$ship_from_custom_state = !empty($settings['ship_from_custom_state']) ? $settings['ship_from_custom_state'] : $ship_from_state;
				$attention_name 		= !empty($settings['ups_display_name']) ? preg_replace("/&#?[a-z0-9]+;/i","",$settings['ups_display_name']) : '-';
				$company_name 			= isset( $settings['ups_user_name'] ) ? preg_replace("/&#?[a-z0-9]+;/i","",$settings['ups_user_name']) : '-';

				$ship_from_address = array(
					'CompanyName'	=>	substr( $company_name, 0, 34 ),
					'AttentionName'	=>	substr( $attention_name, 0, 34 ),
					'Address'		=>	array(
						'AddressLine1'	=>	substr( $settings['ship_from_addressline'], 0, 34 ),
						'City'			=>	substr( $settings['ship_from_city'], 0, 29 ),
						'PostalCode'	=>	$settings['ship_from_postcode'],
						'CountryCode'	=>	$ship_from_country,
					),
				);

				if( ! empty($ship_from_custom_state) )	{

					$ship_from_address['Address']['StateProvinceCode'] = $ship_from_custom_state;
				}

				if( isset($settings['ship_from_addressline_2']) && !empty($settings['ship_from_addressline_2']) ) {

					$ship_from_address['Address']['AddressLine2'] = substr( $settings['ship_from_addressline_2'], 0, 34 );
				}
			}

			return $ship_from_address;
		}

		/**
		 * Get Ship To Address for Return Label.
		 */
		private function get_ship_to_address_in_return_label( $settings, $from_address = array() ) {
			$ship_from_address_different_from_shipper = ! empty($settings['ship_from_address_different_from_shipper']) ? $settings['ship_from_address_different_from_shipper'] : 'no';
			$ship_to_address = array();
			if( $ship_from_address_different_from_shipper == 'yes' ) {

				$ship_from_country_state = $settings['ship_from_country_state'];
				if (strstr($ship_from_country_state, ':')) :
					list( $ship_from_country, $ship_from_state ) = explode(':',$ship_from_country_state);
				else :
					$ship_from_country = $ship_from_country_state;
					$ship_from_state   = '';
				endif;
				$ship_from_custom_state   = ! empty($settings['ship_from_custom_state']) ? $settings['ship_from_custom_state'] : $ship_from_state;

				$ship_to_address = array(
					'AddressLine1'		=>	substr( $settings['ship_from_addressline'], 0, 34 ),
					'City'				=>	substr( $settings['ship_from_city'], 0, 29 ),
					'CountryCode'		=>	$ship_from_country,
					'PostalCode'		=>	$settings['ship_from_postcode'],
				);

				if( ! empty($ship_from_custom_state) ) {

					$ship_to_address['StateProvinceCode'] = $ship_from_custom_state;
				}

				if( isset($settings['ship_from_addressline_2']) && !empty($settings['ship_from_addressline_2']) ) {

					$ship_to_address['AddressLine2'] = substr( $settings['ship_from_addressline_2'], 0, 34 );
				}

			} else {

				$ship_to_address = array(
					'AddressLine1'		=>	substr( $from_address['address_1'], 0, 34 ),
					'City'				=>	substr( $from_address['city'], 0, 29 ),
					'StateProvinceCode'	=>	$from_address['state'],
					'CountryCode'		=>	$from_address['country'],
					'PostalCode'		=>	$from_address['postcode'],
				);

				if( isset($from_address['address_2']) && !empty($from_address['address_2']) ) {

					$ship_to_address['AddressLine2'] = substr( $from_address['address_2'], 0, 34 );
				}
			}

			return $ship_to_address;
		}

		private function wf_is_surepost( $shipping_method ){
			return in_array($shipping_method, $this->ups_surepost_services ) ;
		}

		private function get_service_code_for_country($service, $country){
			$service_for_country = array(
				'CA' => array(
				'07' => '01', // for Canada serivce code of 'UPS Express(07)' is '01'
			),
			);
			if( array_key_exists($country, $service_for_country) ){
				return isset($service_for_country[$country][$service]) ? $service_for_country[$country][$service] : $service;
			}
			return $service;
		}


		private function wf_get_accesspoint_datas( $order_details ){
			if( WC()->version < '2.7.0' ){
				return ( isset($order_details->shipping_accesspoint) ) ? json_decode( stripslashes($order_details->shipping_accesspoint) ) : '';
			}else{
				$order_details	= wc_get_order($order_details->get_id());
				$address_field 	= is_object($order_details) ? $order_details->get_meta('_shipping_accesspoint') : '';
				return json_decode(stripslashes($address_field));
			}
		}

		public function get_confirm_shipment_accesspoint_request($order_details){
			$accesspoint = $this->wf_get_accesspoint_datas( $order_details );

			$confirm_accesspoint_request = array();
			if( isset($accesspoint->AddressKeyFormat) && !empty($accesspoint->AccessPointInformation->PublicAccessPointID) ){
				$access_point_consignee		= $accesspoint->AddressKeyFormat->ConsigneeName;
				$access_point_addressline	= $accesspoint->AddressKeyFormat->AddressLine;
				$access_point_city			= isset($accesspoint->AddressKeyFormat->PoliticalDivision2) ? $accesspoint->AddressKeyFormat->PoliticalDivision2 : '';
				$access_point_state			= isset($accesspoint->AddressKeyFormat->PoliticalDivision1) ? $accesspoint->AddressKeyFormat->PoliticalDivision1 : '';
				$access_point_postalcode	= $accesspoint->AddressKeyFormat->PostcodePrimaryLow;
				$access_point_country		= $accesspoint->AddressKeyFormat->CountryCode;
				$access_point_id			= $accesspoint->AccessPointInformation->PublicAccessPointID;

				if( strlen($access_point_addressline) > 35 ) {
					$address_line_1		=	null;
					$address_line_2		=	null;
					$temp_address		=	null;
					$new_address		= explode( ' ', $access_point_addressline);
					foreach( $new_address as $word ){
						$temp_address = $temp_address.' '.$word;
						if( empty($address_line_2) && strlen($temp_address) <= 35 ) {
							$address_line_1 = $address_line_1.' '.$word;
						}
						else {
							$address_line_2	= $address_line_2.' '.$word;
						}
						$temp_address = empty($address_line_2) ? $address_line_1 : $address_line_2;
					}
				}

				$access_point_consignee 	= preg_replace('/[^A-Za-z0-9-()#, ]/', '', $access_point_consignee);

				$confirm_accesspoint_request	=	array(
					'ShipmentIndicationType'	=>	array('Code'=>'01'),
					'AlternateDeliveryAddress'	=>	array(
						'Name'				=>	$access_point_consignee,
						'AttentionName'		=>	$access_point_consignee,
						'UPSAccessPointID'	=>	$access_point_id,
						'Address'			=>	array(
							'AddressLine1'		=>	! empty($address_line_1) ? $address_line_1 : $access_point_addressline,
							'AddressLine2'		=>	! empty($address_line_2) ? $address_line_2 : '-',
							'City'				=>	$access_point_city,
							'StateProvinceCode'	=>	$access_point_state,
							'PostalCode'		=>	$access_point_postalcode,
							'CountryCode'		=>	$access_point_country,
						),
					),						
				);
				
				$accesspoint_notifications[] = array(
					'Notification' => array(
						'NotificationCode'=>'012',
						'EMailMessage'=> array(
							'EMailAddress' => $order_details->billing_email,
						),
						'Locale'=>array(
							'Language'=>'ENG',
							'Dialect'=>'US',
						),
					),
				);
				$accesspoint_notifications[] = array(
					'Notification' => array(
						'NotificationCode'=>'013',
						'EMailMessage'=> array(
							'EMailAddress' => $order_details->billing_email,
						),
						'Locale'=>array(
							'Language'=>'ENG',
							'Dialect'=>'US',
						),
					),
				);
				$confirm_accesspoint_request['ShipmentServiceOptions']['Notification']	=	array_merge(array('multi_node'=>1), $accesspoint_notifications);
			}

			return $confirm_accesspoint_request;
		}
		private function get_code_from_label_type( $label_type ){
			switch ($label_type) {
				case 'zpl':
				$code_val = 'ZPL';
				break;
				case 'epl':
				$code_val = 'EPL';
				break;
				case 'png':
				$code_val = 'PNG';
				break;
				default:
				$code_val = 'GIF';
				break;
			}
			return array('Code'=>$code_val);
		}

		private function wf_get_shipment_description( $order, $shipment, $package = false ){

			// To support Mix and Match Product
			do_action( 'ph_ups_before_get_items_from_order', $order );

			$order_items 	= $order->get_items();

			$shipment_description 	=	'';
			$categories 			= '';
			$shipment_products 		= array();
			
			if( $package ) {

				if( isset($shipment['Package']) && isset($shipment['Package']['items']) ){

					foreach ($shipment['Package']['items'] as $item) {
						
						if( is_a( $item, 'WC_Product' ) ){

							$item_id 				= ( $item->get_parent_id() == 0 ) ? $item->get_id() : $item->get_parent_id();
							$shipment_products[] 	= $item_id;
						}
					}
				}

			} else {

				if( isset($shipment['packages']) && is_array($shipment['packages']) && count($shipment['packages']) == 1 ) {

					$package = $shipment['packages'][0];

					if( isset($package['Package']) && isset($package['Package']['items']) ){

						foreach ($package['Package']['items'] as $item) {

							if( is_a( $item, 'WC_Product' ) ){

								$item_id 				= ( $item->get_parent_id() == 0 ) ? $item->get_id() : $item->get_parent_id();
								$shipment_products[] 	= $item_id;
							}
						}
					}
				}
			}

			if( $package && $this->add_product_sku == 'yes' ) {

				if( $this->include_order_id == 'yes' ) {
					$shipment_description	= '#'.$order->get_order_number().' - ';
				}

				if(is_array($order_items) && count($order_items)) {

					$product_sku = '';

					foreach( $order_items as $order_item ) {

						$product = $this->get_product_from_order_item($order_item);

						if( is_a( $product, 'WC_Product' ) && $product->needs_shipping() ) {

							$product_id = ( $product->get_parent_id() == 0 ) ? $product->get_id() : $product->get_parent_id();

							if( empty($shipment_products) || in_array($product_id, $shipment_products) ) {

								$product_sku 	.= $product->get_sku().', ';
							}
						}
					}

					$shipment_description .= rtrim($product_sku,', ');
				}

				if( !empty($shipment_description) ) {
					return $shipment_description;
				}

				$shipment_description = '';
			}

			if( $this->include_order_id == 'yes' ) {

				$shipment_description	= 'Order #'.$order->get_order_number().'\n';
			}

			if(is_array($order_items) && count($order_items)){

				$product_categories	= '';

				foreach( $order_items as $order_item ) {

					$product = $this->get_product_from_order_item($order_item);

					if( is_a( $product, 'WC_Product' ) && $product->needs_shipping() ) {

						$product_id = ( $product->get_parent_id() == 0 ) ? $product->get_id() : $product->get_parent_id();

						if( empty($shipment_products) || in_array($product_id, $shipment_products) ) {
							$product_categories	.= (string) strip_tags( wc_get_product_category_list($product_id, ', ', '', '') ).', ';
						}
					}	
				}

				$cat_array = array_unique( explode(', ', rtrim($product_categories, ', ')) );

				if ( ($key = array_search('Uncategorized', $cat_array)) !== false) {
					unset($cat_array[$key]);
				}

				$categories = implode(', ', $cat_array);
			}

			if( $this->label_description == 'custom_description' && !empty($this->label_custom_description) ) {

				$shipment_description .= strip_tags($this->label_custom_description);

			} elseif( ($this->label_description == 'product_category' || $this->label_description == 'custom_description') && !empty($categories) ) {

				$shipment_description .=	$categories;

			} else {

				if(is_array($order_items) && count($order_items)){

					$product_names = '';
					$product_descs = '';

					foreach( $order_items as $order_item ) {

						$product = $this->get_product_from_order_item($order_item);

						if( is_a( $product, 'WC_Product' ) && $product->needs_shipping() ) {

							$product_id = ( $product->get_parent_id() == 0 ) ? $product->get_id() : $product->get_parent_id();
							$var_id 	= $product->get_id();

							if ( empty($shipment_products) || in_array($product_id, $shipment_products) ) {

								$product_names 	.= htmlspecialchars(strip_tags($product->get_formatted_name())).', ';
								$pdt_description = get_post_meta( $var_id, 'ph_ups_invoice_desc_var', true );

								if ( empty($pdt_description) ) {

									$pdt_description = get_post_meta( $product_id, 'ph_ups_invoice_desc', true );
								}

								if ( !empty($pdt_description) ) {

									$product_descs 	.= $pdt_description.', ';
								}
							}
						}
					}

					if ( $this->label_description == 'product_name' || empty($product_descs) ) {

						$shipment_description .= rtrim($product_names,', ');
					} else {

						$shipment_description .= rtrim($product_descs,', ');
					}

				}
			}

			//PDS-153
			if( $this->remove_special_char_product ) {

				$shipment_description 	= preg_replace('/[^A-Za-z0-9-()# ]/', '', $shipment_description);
				$shipment_description 	= htmlspecialchars($shipment_description);

			} else {
				$shipment_description 	= htmlspecialchars($shipment_description);
			}

			$shipment_description = apply_filters('wf_ups_alter_shipment_desription' ,$shipment_description);
			$shipment_description = ( strlen( $shipment_description ) >= 50 ) ? substr( $shipment_description, 0, 45 ).'...' : $shipment_description;

			// To support Mix and Match Product
			do_action( 'ph_ups_after_get_items_from_order', $order );

			return $shipment_description;
		}

		private function ph_get_commercial_invoice_description( $product_id, $invoice_default, $var_id ) {

			$invoice_description 	= '';
			$pro_description 		= get_post_meta( $var_id, 'ph_ups_invoice_desc_var', true );

			if (empty($pro_description) ) {

				$pro_description=get_post_meta( $product_id, 'ph_ups_invoice_desc', true );
			}

			if (isset($pro_description) && !empty($pro_description)) {

				$invoice_description = $pro_description;

				//PDS-153
				if( $this->remove_special_char_product ) {

					$invoice_description 	= preg_replace('/[^A-Za-z0-9-()# ]/', '', $invoice_description);
					$invoice_description 	= htmlspecialchars($invoice_description);

				}

				$invoice_description = ( strlen( $invoice_description ) >= 35 ) ? substr( $invoice_description, 0, 30 ).'...' : $invoice_description;

				return $invoice_description;
			}

			if( $this->include_in_commercial_invoice == 'no' || $this->label_description == 'product_name' ) {

				return $invoice_default;
			}

			$invoice_description =	'';
			$categories = '';

			$product_categories	= (string) strip_tags( wc_get_product_category_list($product_id, ', ', '', '') ).', ';
			$cat_array = array_unique( explode(', ', rtrim($product_categories, ', ')) );
			if ( ($key = array_search('Uncategorized', $cat_array)) !== false)
			{
				unset($cat_array[$key]);
			}

			$categories = implode(', ', $cat_array);

			if( $this->label_description == 'custom_description' && !empty($this->label_custom_description) ) {

				$invoice_description = strip_tags($this->label_custom_description);

			} elseif ( ($this->label_description == 'product_category' || $this->label_description == 'custom_description') && !empty($categories) ) {

				$invoice_description =	$categories;

			} else {
				$invoice_description =	$invoice_default;
			}

			$invoice_description = apply_filters('ph_ups_alter_invoice_desription' ,$invoice_description);

			//PDS-153
			if( $this->remove_special_char_product ) {

				$invoice_description 	= preg_replace('/[^A-Za-z0-9-()# ]/', '', $invoice_description);
				$invoice_description 	= htmlspecialchars($invoice_description);

			} else {
				$invoice_description 	= htmlspecialchars($invoice_description);
			}
			
			$invoice_description = ( strlen( $invoice_description ) >= 35 ) ? substr( $invoice_description, 0, 30 ).'...' : $invoice_description;

			return $invoice_description;
		}

		/**
		 * Get Product from Order Line Item.
		 * @param array|object $line_item Array in less than woocommerce 3.0 else Object
		 * @return object WC_Product|null|false
		 */
		public function get_product_from_order_item( $line_item ) {

			if( self::$wc_version < '3.0.0' ) {
				$product_id = ! empty($line_item['variation_id']) ? $line_item['variation_id'] : $line_item['product_id'];
				$product = wc_get_product($product_id);
			} else {
				$product = $line_item->get_product();
			}

			return $product;
		}

		function wf_get_package_data( $order, $ship_options=array(), $to_address=array() ) {

			$packages	= $this->wf_create_package( $order, $to_address );
			$order_id 	= ( WC()->version < '3.0' ) ? $order->id : $order->get_id();

			if ( ! class_exists( 'WF_Shipping_UPS' ) ) {
				include_once 'class-wf-shipping-ups.php';
			}
			$this->wcsups 			= new WF_Shipping_UPS( $order );
			$package_data_array	= array();		

			// If return label is printing, cod can't be applied
			if(!isset($ship_options['return_label']) || !$ship_options['return_label']) { 
				$this->wcsups->wf_set_cod_details($order);
			}

			$order = wc_get_order($order_id);
			// Set Insurance value false
			$order_subtotal = is_object($order) ? $order->get_subtotal() : 0;
			if( isset($this->min_order_amount_for_insurance) && $order_subtotal < $this->min_order_amount_for_insurance ) {
				$this->wcsups->insuredvalue = false;
			}

			$service_code=get_post_meta($order->get_id(),'wf_ups_selected_service',1);
			if($service_code)
			{
				$this->wcsups->wf_set_service_code($service_code);
				// Insurance value doen't wprk with sure post services
				if(in_array($service_code, array(92,93,94,95))){
					$this->wcsups->insuredvalue = false;
				}
			}

			$package_params	=	array();
			if(isset($ship_options['delivery_confirmation_applicable'])){
				$package_params['delivery_confirmation_applicable']	=	$ship_options['delivery_confirmation_applicable'];
			}

			$packing_method  	= isset( $this->settings['packing_method'] ) ? $this->settings['packing_method'] : 'per_item';
			$package_data = array();
			foreach( $packages as $key => $package ) {
				$package = apply_filters( 'wf_customize_package_on_generate_label', $package, $order_id );		//Filter to customize the package, for example to support bundle product
				$temp_package_data 		= $this->wcsups->wf_get_api_rate_box_data( $package, $packing_method, $package_params);
				$temp_package_data		=	apply_filters( 'ph_ups_customize_package_by_desination',$temp_package_data, $package['destination'] );  // support for woocommerce multi shipping address
				if(is_array($temp_package_data) ) {
					$package_data = array_merge($package_data, $temp_package_data);
				}
			}
			return $package_data;
		}

		function wf_create_package( $order, $to_address=array() ){

			// To support Mix and Match Product
			do_action( 'ph_ups_before_get_items_from_order', $order );

			$orderItems = $order->get_items();

			foreach( $orderItems as $orderItem ) {

				$item_id 			= $orderItem['variation_id'] ? $orderItem['variation_id'] : $orderItem['product_id'];

				if( empty($items[$item_id]) ) {

					// $product_data 		= wc_get_product( $item_id );
					$product_data 		= $this->get_product_from_order_item( $orderItem );

					if( empty($product_data) ) {

						$deleted_products[] = $orderItem->get_name();
						continue;
					}

					if( $product_data->needs_shipping() ) {

						$items[$item_id] 	= array('data' => $product_data , 'quantity' => $orderItem['qty']);
					}

				} else {

					// If a product is in bundle product and it's also ordered individually in same order
					$items[$item_id]['quantity'] += $orderItem['qty'];
				}
			}

			// To support Mix and Match Product
			do_action( 'ph_ups_after_get_items_from_order', $order );

			if( ! empty($deleted_products) && class_exists('WC_Admin_Meta_Boxes') ) {
				WC_Admin_Meta_Boxes::add_error( __( "UPS Warning! One or more Ordered Products have been deleted from the Order. Please check these Products- ", 'ups-woocommerce-shipping' ).implode( ',', $deleted_products ).'.' );
			}

			//If no items exist in order $items won't be set
			$package['contents'] = isset($items) ? apply_filters( 'xa_ups_get_customized_package_items_from_order', $items, $order ) : array();

			$package['destination'] = array (
				'country' 	=> !empty($to_address) ? $to_address['country'] : $order->shipping_country,
				'state' 	=> !empty($to_address) ? $to_address['state'] : $order->shipping_state,
				'postcode' 	=> !empty($to_address) ? $to_address['postcode'] : $order->shipping_postcode,
				'city' 		=> !empty($to_address) ? $to_address['city'] : $order->shipping_city,
				'address' 	=> !empty($to_address) ? $to_address['address_1'] : $order->shipping_address_1,
				'address_2'	=> !empty($to_address) ? $to_address['address_2'] : $order->shipping_address_2
			);

			// Skip Products
			if( ! empty($this->skip_products) ) {
				$package = $this->skip_products($package);
				if( empty($package['contents']) ) {
					return array();
				}
			}

			// Check for Minimum weight and maximum weight
			if( ! empty($this->min_weight_limit) || ! empty($this->max_weight_limit) ) {
				$need_shipping = $this->check_min_weight_and_max_weight( $package, $this->min_weight_limit, $this->max_weight_limit );
				if( ! $need_shipping )	return array();
			}

			$packages	= apply_filters( 'wf_ups_filter_label_from_packages',array($package), $this->settings['ship_from_address'], $order->get_id() );

			return $packages;
		}
		
	/**
	 * Skip the selected products in settings.
	 * @param array $package Cart Package.
	 * @param array
	 */
	public function skip_products( $package ) {
		$skipped_products = null;
		foreach( $package['contents'] as $line_item_key => $line_item ) {
			$line_item_shipping_class = $line_item['data']->get_shipping_class();
			if( in_array( $line_item_shipping_class, $this->skip_products ) ) {
				$skipped_products[] = $line_item['data']->get_id();
				unset( $package['contents'][$line_item_key] );
			}
		}
		if( $this->debug && ! empty($skipped_products) ) {
			$skipped_products = implode( ', ', $skipped_products );
			if( class_exists('WC_Admin_Notices') )
				WC_Admin_Notices::add_custom_notice( 'ups_skipped_products', __('UPS : Skipped Products Id - ', 'ups-woocommerce-shipping'). $skipped_products.' .' );
		}

		if( !empty($skipped_products) ) {
			
			$this->admin_diagnostic_report( 'Skipped Products Ids from Label Generation' );
			$this->admin_diagnostic_report( print_r( $skipped_products, 1) );
		}

		return $package;
	}

	/**
	 * Check for Order Minimum weight and Maximum weight.
	 * @param array $package Cart Package.
	 * @param float $min_weight_limit Minimum Weight.
	 * @param float $max_weight_limit Maximum Weight.
	 * @return boolean
	 */
	public function check_min_weight_and_max_weight( $package, $min_weight_limit= null, $max_weight_limit= null ) {
		$package_weight = 0;
		foreach( $package['contents'] as $line_item ) {

			$quantity 		 = isset($line_item['quantity']) ? $line_item['quantity'] : 1;
			$package_weight += (float) ( $line_item['data']->get_weight() * $quantity );
		}
		if( $package_weight < $min_weight_limit || ( ! empty($max_weight_limit) && $package_weight > $max_weight_limit ) ) {
			if( $this->debug ) {
				if( class_exists('WC_Admin_Notices') )
					WC_Admin_Notices::add_custom_notice( 'ups_package_weight_not_in_range',__('UPS Package Generation stopped. - Package Weight is not in range of Minimum and Maximum Weight Limit (Check UPS Plugin Settings).', 'ups-woocommerce-shipping') );
			}

			$this->admin_diagnostic_report( 'UPS Package Generation stopped. - Package Weight is not in range of Minimum and Maximum Weight Limit (Check UPS Plugin Settings).' );

			return false;
		}
		return true;
	}

	function ph_ups_generate_packages( $order_id = '', $auto_generate = false ){

		// Automatic Package Generation
		if( !empty($order_id) ) {
			$post_id 		=	$order_id;

		} else {
			$query_string 		= 	explode('|', base64_decode($_GET['phupsgp']));
			$post_id 			= 	$query_string[1];
		}
		
		$order				= 	$this->wf_load_order( $post_id );
		$order_items 		=	'';

		if( $order instanceof WC_Order ) {

			// To support Mix and Match Product
			do_action( 'ph_ups_before_get_items_from_order', $order );

			$order_items		=	$order->get_items();

		}

		if( empty($order_items) && class_exists('WC_Admin_Meta_Boxes') && ( is_admin() || !$auto_generate )  ) {

			WC_Admin_Meta_Boxes::add_error(__('UPS - No product Found.'));
			wp_redirect( admin_url( '/post.php?post='.$post_id.'&action=edit') );
			exit();
		}

		// To support Mix and Match Product
		do_action( 'ph_ups_after_get_items_from_order', $order );

		$fromCountry 	= $this->origin_country;
		$toCountry 		= $order->get_shipping_country();
		$ship_options 	= [];

		// Delivery confirmation available at package level only for domestic shipments
		if( ($fromCountry == $toCountry) && in_array( $fromCountry, $this->dc_domestic_countries) ) {

			$ship_options['delivery_confirmation_applicable']	= true;
			$ship_options['international_delivery_confirmation_applicable']	= false;

		} else {
			$ship_options['international_delivery_confirmation_applicable']	= true;
		}

		$package_data		=	$this->wf_get_package_data( $order, $ship_options );

		if(empty($package_data)) {
			
			$package['Package']['PackagingType'] = array(
				'Code' => '02',
				'Description' => 'Package/customer supplied'
			);

			$package['Package']	=	array(
				'PackagingType'	=>	array(
					'Code'				=>	'02',
					'Description'	=>	'Package/customer supplied'
				),
				'Description'	=> 'Rate',
				'Dimensions'	=>	array(
					'UnitOfMeasurement'	=>	array(
						'Code'	=>	$this->dim_unit,
					),
					'Length'	=>	0,
					'Width'		=>	0,
					'Height'	=>	0
				),
				'PackageWeight' => array(
					'UnitOfMeasurement'	=>	array(
						'Code'	=>	$this->weight_unit,
					),
					'Weight'	=>	0
				),
				'PackageServiceOptions' => array(
					'InsuredValue'	=> array(
						'CurrencyCode'	=>$this->wcsups->get_ups_currency(),
						'MonetaryValue'	=> 0
					)
				)
			);
			update_post_meta( $post_id, '_wf_ups_stored_packages', array($package) );
			$package_data=$package;
		} else {
			update_post_meta( $post_id, '_wf_ups_stored_packages', $package_data );
		}

		//Generate label mannually when Automatic label generation is active
		if( ! isset($_GET['phupsgp'])){
			do_action( 'wf_after_package_generation', $post_id,$package_data);
		}

		// Redirect Only if headers has not been already sent
		if( !headers_sent() && ( is_admin() || !$auto_generate ) ) {
			wp_redirect( admin_url( '/post.php?post='.$post_id.'&action=edit#CyDUPS_metabox') );
			exit;
		}

	}

	function wf_ups_shipment_confirm( $order_id = '', $auto_generate = false, $user_check = null ) {

		if( !$this->wf_user_check( $user_check ) ) {
			echo "You don't have admin privileges to view this page.";
			exit;
		}

		// Automatic Label Generation
		if( !empty($order_id) ) {
			$post_id 		=	$order_id;

		} else {
			$query_string 	= explode('|', base64_decode($_GET['wf_ups_shipment_confirm']));
			$post_id 		= $query_string[1];
		}

		// Stop Label generation if label has been already generated
		$old_label_details = get_post_meta( $post_id, 'ups_label_details_array', true );
		if( ! empty($old_label_details) ) {
			WC_Admin_Meta_Boxes::add_error( __( "UPS Label has been already generated.", 'ups-woocommerce-shipping' ) );
			exit;
		}

		// Load UPS Settings.
		$ups_settings 		= get_option( 'woocommerce_'.WF_UPS_ID.'_settings', null ); 
		
		// API Settings
		$api_mode      		= isset( $ups_settings['api_mode'] ) ? $ups_settings['api_mode'] : 'Test';
		$debug_mode      	= isset( $ups_settings['debug'] ) && $ups_settings['debug'] == 'yes' ? true : false;
		$wf_ups_selected_service	= isset( $_GET['wf_ups_selected_service'] ) ? $_GET['wf_ups_selected_service'] : '';

		update_post_meta( $post_id, 'wf_ups_selected_service', $wf_ups_selected_service );

		$cod	= isset( $_GET['cod'] ) ? $_GET['cod'] : '';

		$order_payment_method 	= get_post_meta( $post_id, '_payment_method', true );

		if( $cod != 'true' && $order_payment_method == 'cod' && $auto_generate ) {

			$cod = 'true';
		}	

		if($cod=='true'){
			update_post_meta( $post_id, '_wf_ups_cod', true );
		}else{
			delete_post_meta( $post_id, '_wf_ups_cod');
		}

		$sat_delivery	= isset( $_GET['sat_delivery'] ) ? $_GET['sat_delivery'] : '';
		if($sat_delivery=='true'){
			update_post_meta( $post_id, '_wf_ups_sat_delivery', true );
		}else{
			delete_post_meta( $post_id, '_wf_ups_sat_delivery');
		}

		$is_return_label	= isset( $_GET['is_return_label'] ) ? $_GET['is_return_label'] : '';
		if($is_return_label=='true'){
			$ups_return=true;
		}
		else{
			$ups_return=false;
		}

		if( isset($_GET['ups_export_compliance']) ) {
			update_post_meta( $post_id, '_ph_ups_export_compliance', $_GET['ups_export_compliance'] );
		}
		else {
			delete_post_meta( $post_id, '_ph_ups_export_compliance');
		}

		if( isset($_GET['ups_recipient_tin']) ) {
			update_post_meta( $post_id, 'ph_ups_shipping_tax_id_num', $_GET['ups_recipient_tin'] );
		} else {
			delete_post_meta( $post_id, 'ph_ups_shipping_tax_id_num');
		}

		if( isset($_GET['ups_shipto_recipient_tin']) ) {
			update_post_meta( $post_id, 'ph_ups_ship_to_tax_id_num', $_GET['ups_shipto_recipient_tin'] );
		} else {
			delete_post_meta( $post_id, 'ph_ups_ship_to_tax_id_num');
		}

		$order		= $this->wf_load_order( $post_id );

		$requests 	= $this->wf_ups_shipment_confirmrequest( $order );

		$created_shipments_details_array 	= array();
		$created_shipments_xml_request 		= array();
		$return_package_index 	= 0;

		foreach($requests as $request) {
			if( $debug_mode && !is_array($request)) {
				echo '<div style="background: #eee;overflow: auto;padding: 10px;margin: 10px;">SHIPMENT CONFIRM REQUEST: ';
				echo '<xmp>'.$request.'</xmp></div>'; 

				$this->admin_diagnostic_report( '------------------------ UPS LABEL CONFIRM REQUEST ------------------------' );
				$this->admin_diagnostic_report( $request );
			}

			// Due to some error and request not available, But the error is not catched
			if( !$request && ( is_admin() || !$auto_generate ) ) {

				wf_admin_notice::add_notice('Sorry. Something went wrong: please turn on debug mode to investigate more.');
				$this->wf_redirect( admin_url( '/post.php?post='.$post_id.'&action=edit') );
				exit;
				
			}

			if( "Live" == $api_mode ) {
				$endpoint = 'https://onlinetools.ups.com/ups.app/xml/ShipConfirm';
				$freight_endpoint='https://onlinetools.ups.com/rest/FreightShip';
			}
			else {
				$endpoint = 'https://wwwcie.ups.com/ups.app/xml/ShipConfirm';
				$freight_endpoint='https://wwwcie.ups.com/rest/FreightShip';
			}

			$xml_request = str_replace( array( "\n", "\r" ), '', $request );
			if(!is_array($request) && json_decode( $request ) !==null)
			{	
				$xml_request = str_replace( array( "\n", "\r" ), '', $request );
				$response = wp_remote_post( $freight_endpoint,
					array(
						'timeout'   => 70,
						'sslverify' => $this->ssl_verify,
						'body'      => $xml_request
					)
				);				
			}
			elseif(is_array($request) && isset($request['service']) && $request['service']=='GFP')
			{
				$ups_settings 				= get_option( 'woocommerce_'.WF_UPS_ID.'_settings', null ); 
				$ups_user_id         		= isset( $ups_settings['user_id'] ) ? $ups_settings['user_id'] : '';
				$ups_password        		= isset( $ups_settings['password'] ) ? $ups_settings['password'] : '';
				$ups_access_key      		= isset( $ups_settings['access_key'] ) ? $ups_settings['access_key'] : '';
        		$header=new stdClass();
        		$header->UsernameToken=new stdClass();
				$header->UsernameToken->Username=$ups_settings['user_id'];
				$header->UsernameToken->Password=$ups_settings['password'];
        		$header->ServiceAccessToken=new stdClass();
				$header->ServiceAccessToken->AccessLicenseNumber=$ups_settings['access_key'];
      			$client = $this->wf_create_soap_client( plugin_dir_path( dirname( __FILE__ ) ) . 'wsdl/'.$api_mode.'/shipment/Ship.wsdl', array(
					'trace' =>	true,
					'cache_wsdl' => 0
					) );
				$authvalues = new SoapVar($header, SOAP_ENC_OBJECT);
				$header = new SoapHeader('http://www.ups.com/XMLSchema/XOLTWS/UPSS/v1.0','UPSSecurity',$header,false);
				
  				$client->__setSoapHeaders($header);

				$xml_request = $this->modfiy_encoding($request['request']);

				// Convert XML to array
				$xml_request = simplexml_load_string($xml_request);
				$xml_request = json_encode($xml_request);
				$xml_request = json_decode($xml_request,TRUE);

				// Creating array from XML will create empty array for null values, replace with null
				if( isset($xml_request['Shipment']['ShipmentRatingOptions']) ) {

					if( isset($xml_request['Shipment']['ShipmentRatingOptions']['NegotiatedRatesIndicator']) ) {

						$xml_request['Shipment']['ShipmentRatingOptions']['NegotiatedRatesIndicator'] = '';
					}
					
					if( isset($xml_request['Shipment']['ShipmentRatingOptions']['FRSShipmentIndicator']) ) {
						
						$xml_request['Shipment']['ShipmentRatingOptions']['FRSShipmentIndicator'] = '';
					}
				}

				$response 		= '';
				$error_desc 	= '';

				try {

				   	$response = $client->ProcessShipConfirm( $xml_request );

				} catch (\SoapFault $fault) {
					$error_desc=$fault->faultstring;

				}

				// error_log(print_r('__getLastRequest'.$client->__getLastRequest(), true));
				// error_log(print_r('__getLastResponse'.$client->__getLastResponse(), true));

				if( $debug_mode) {
					echo '<div style="background: #eee;overflow: auto;padding: 10px;margin: 10px;">SHIPMENT CONFIRM REQUEST: ';
					echo '<xmp>'.$client->__getLastRequest().'</xmp></div>'; 
					echo '<div style="background:#ccc;background: #ccc;overflow: auto;padding: 10px;margin: 10px 10px 50px 10px;">SHIPMENT CONFIRM RESPONSE: ';
					echo '<xmp>'.print_r($client->__getLastResponse(),1).'</xmp></div>'; 

					$log = wc_get_logger();
					$log->debug( print_r( __('------------------------UPS GFP Request -------------------------------', 'ups-woocommerce-shipping').PHP_EOL.PHP_EOL.htmlspecialchars($client->__getLastRequest()).PHP_EOL.PHP_EOL,true), array('source' => 'PluginHive-UPS-Plugin GFP'));
					$log->debug( print_r( __('------------------------UPS GFP Response -------------------------------', 'ups-woocommerce-shipping').PHP_EOL.PHP_EOL.htmlspecialchars($client->__getLastResponse()).PHP_EOL.PHP_EOL,true), array('source' => 'PluginHive-UPS-Plugin GFP'));

					$this->admin_diagnostic_report( '------------------------ UPS GFP LABEL CONFIRM REQUEST ------------------------' );
					$this->admin_diagnostic_report( htmlspecialchars($client->__getLastRequest()) );
					$this->admin_diagnostic_report( '------------------------ UPS GFP LABEL CONFIRM RESPONSE ------------------------' );
					$this->admin_diagnostic_report( htmlspecialchars($client->__getLastResponse()) );
				}
				$created_shipments_details = array();
				if(!empty($response) && isset($response->Response) && isset($response->Response->ResponseStatus) && isset($response->Response->ResponseStatus->Description)  && $response->Response->ResponseStatus->Description=='Success' )
				{
					$shipment_id = (string)$response->ShipmentResults->ShipmentIdentificationNumber;
					
					$created_shipments_details["ShipmentDigest"] 			= (string)$response->ShipmentResults->ShipmentDigest;
					$created_shipments_details['GFP']=true;
					$created_shipments_details_array[$shipment_id] = $created_shipments_details;
					$created_shipments_xml_request[$shipment_id]		= $request;
				}
				else{

					if( is_admin() || !$auto_generate ) {
						wf_admin_notice::add_notice($error_desc);
						$this->wf_redirect( admin_url( '/post.php?post='.$post_id.'&action=edit') );
						exit;
					}
				}
				continue;
			}
			else{
				$xml_request = str_replace( array( "\n", "\r" ), '', $request );
				$response = wp_remote_post( $endpoint,
					array(
						'timeout'   => 70,
						'sslverify' => $this->ssl_verify,
						'body'      => $xml_request
					)
				);
			}
			if( $debug_mode && is_array($response)) {
				echo '<div style="background:#ccc;background: #ccc;overflow: auto;padding: 10px;margin: 10px 10px 50px 10px;">SHIPMENT CONFIRM RESPONSE: ';
				echo '<xmp>'.print_r($response['body'],1).'</xmp></div>'; 

				$this->admin_diagnostic_report( '------------------------ UPS SHIPMENT LABEL CONFIRM RESPONSE ------------------------' );
				$this->admin_diagnostic_report( $response['body'] );
			}
			
			if ( is_wp_error( $response ) ) {
				$error_message = $response->get_error_message();

				if( is_admin() || !$auto_generate ) {
					wf_admin_notice::add_notice('Sorry. Something went wrong: '.$error_message);			
					$this->wf_redirect( admin_url( '/post.php?post='.$post_id.'&action=edit') );
					exit;
				}

			}		
			$req_arr=array();
			if(!is_array($request))
			{
				$req_arr=json_decode($request);
			}

			// For Freight Shipments  as it is JSON not Array
			if(!is_array($request) && isset($req_arr->FreightShipRequest)) {
				try{

					$var=json_decode($response['body']);
					if( ! empty($var->Fault ) ) {

						$this->admin_diagnostic_report( '------------------------ UPS FREIGHT LABEL ERROR ------------------------' );

						if(is_array($var->Fault->detail->Errors->ErrorDetail))
                        {    
                            foreach ($var->Fault->detail->Errors->ErrorDetail as $index => $error_details) {
                                WC_Admin_Meta_Boxes::add_error($error_details->PrimaryErrorCode->Description);

                                $this->admin_diagnostic_report( $error_details->PrimaryErrorCode->Description );
                            }
                        }
                        else
                        {
                            WC_Admin_Meta_Boxes::add_error($var->Fault->detail->Errors->ErrorDetail->PrimaryErrorCode->Description);

                            $this->admin_diagnostic_report( $var->Fault->detail->Errors->ErrorDetail->PrimaryErrorCode->Description );
                        }
                        
                        if( is_admin() || !$auto_generate ) {
							$this->wf_redirect( admin_url( '/post.php?post='.$post_id.'&action=edit') );
							exit;
						}
					}
				}
				catch(Exception $e)
				{
					if( is_admin() || !$auto_generate ) {
						$this->wf_redirect( admin_url( '/post.php?post='.$post_id.'&action=edit') );
						exit;
					}
				}
				$created_shipments_details = array();
				$shipment_id = (string)$var->FreightShipResponse->ShipmentResults->ShipmentNumber;

				$created_shipments_details["ShipmentDigest"] = (string)$var->FreightShipResponse->ShipmentResults->ShipmentNumber;
				$created_shipments_details["BOLID"] = (string)$var->FreightShipResponse->ShipmentResults->BOLID;
				$created_shipments_details["type"] = "freight";
				try{
					$img=(string)$var->FreightShipResponse->ShipmentResults->Documents->Image->GraphicImage;
				}catch(Exception $ex){$img='';}

				$created_shipments_details_array[$shipment_id] = $created_shipments_details;
				$this->wf_ups_freight_accept_shipment($img,$shipment_id,$created_shipments_details["BOLID"],$post_id);

			}else{

				// 403 Access Forbidden
				if( ! empty($response['response']['code']) && $response['response']['code'] == 403 ) {

					if( is_admin() || !$auto_generate ) {
						wf_admin_notice::add_notice( $response['response']['message']." You don't have permission to access http://www.ups.com/ups.app/xml/ShipConfirm on this server [Error Code: 403]");
						$this->wf_redirect( admin_url( '/post.php?post='.$post_id.'&action=edit') );
						exit;
					}
				}

				$response_obj = simplexml_load_string( $response['body'] );
				
				$response_code = (string)$response_obj->Response->ResponseStatusCode;
				if( '0' == $response_code ) {
					$error_code = (string)$response_obj->Response->Error->ErrorCode;
					$error_desc = (string)$response_obj->Response->Error->ErrorDescription;

					$this->admin_diagnostic_report( '------------------------ UPS SHIPMENT LABEL ERROR ------------------------' );
					$this->admin_diagnostic_report( $error_desc );
					
					if( is_admin() || !$auto_generate ) {
						wf_admin_notice::add_notice($error_desc.' [Error Code: '.$error_code.']');
						$this->wf_redirect( admin_url( '/post.php?post='.$post_id.'&action=edit') );
						exit;
					}
				}			
				
				$created_shipments_details 	= array();
				$shipment_id 				= isset($response_obj->ShipmentIdentificationNumber) ? (string)$response_obj->ShipmentIdentificationNumber : '';
				
				if( !empty($shipment_id) ) {

					$created_shipments_details["ShipmentDigest"] 	= (string)$response_obj->ShipmentDigest;
					$created_shipments_details_array[$shipment_id] 	= $created_shipments_details;
					$created_shipments_xml_request[$shipment_id] 	= $request;

					// Add the Insurance in Order Note
					if( strstr( $request, 'InsuredValue') != false ){
						$request=explode('</AccessRequest>', $request);
						$xml = simplexml_load_string($request[1]);

						$insuredvalue = "";

						if( isset($xml->Shipment->Package->PackageServiceOptions) && isset($xml->Shipment->Package->PackageServiceOptions->InsuredValue) && isset($xml->Shipment->Package->PackageServiceOptions->InsuredValue->MonetaryValue) )
						{
							$insuredvalue=(string)$xml->Shipment->Package->PackageServiceOptions->InsuredValue->MonetaryValue;
						}

						if(!empty($insuredvalue))
						{		
							$order->add_order_note( __("UPS Package with Tracking Id #$shipment_id is Insured.", "ups-woocommerce-shipping"));
						}
					}

					// Creating Return Label
					if($ups_return){
						$return_label = $this->wf_ups_return_shipment_confirm($shipment_id,$return_package_index);
						if( !empty($return_label) ){
							$created_shipments_details_array[$shipment_id]['return'] = $return_label;
						}
					}
				}
			}
			$return_package_index++;
		}
		update_post_meta( $post_id, 'ups_created_shipments_xml_request_array', $created_shipments_xml_request );
		update_post_meta( $post_id, 'ups_created_shipments_details_array', $created_shipments_details_array );
		$this->ups_accept_shipment($post_id);

		if( is_admin() || !$auto_generate ) {
			$this->wf_redirect( admin_url( '/post.php?post='.$post_id.'&action=edit') );
			exit;
		}
	}

	private function wf_create_soap_client( $wsdl, $options ) {
		
			$soapclient = new SoapClient( $wsdl, $options);
		return $soapclient;
	}

	function wf_ups_freight_accept_shipment($img,$shipment_id,$BOLID,$order_id)
	{
		// Since their is no accept shipment method for freigth we will skip it 
		$ups_label_details["TrackingNumber"]		= $BOLID;
		$ups_label_details["Code"] 					= "PDF";
		$ups_label_details["GraphicImage"] 			= $img;	
		$ups_label_details["Type"] 					= "FREIGHT";		
		$ups_label_details_array[$shipment_id][]	= $ups_label_details;
		do_action('wf_label_generated_successfully',$shipment_id,$order_id,$ups_label_details["Code"],"0",$ups_label_details["TrackingNumber"],$ups_label_details);
		$old_ups_label_details_array 	= get_post_meta( $order_id, 'ups_label_details_array', true );
		if(empty($old_ups_label_details_array))
		{
			$old_ups_label_details_array=$ups_label_details_array;
		}
		else{
			$old_ups_label_details_array[$shipment_id][]=$ups_label_details;
		}
		
		update_post_meta( $order_id, 'ups_label_details_array', $old_ups_label_details_array );
		wf_admin_notice::add_notice('Order #'. $order_id.': Shipment accepted successfully. Labels are ready for printing.','notice');
		return true;
	}	
	function wf_ups_return_shipment_confirm($parent_shipment_id,$return_package_index){
		if( !$this->wf_user_check() ) {
			echo "You don't have admin privileges to view this page.";
			exit;
		}
		
		// Load UPS Settings.
		$ups_settings 		= get_option( 'woocommerce_'.WF_UPS_ID.'_settings', null ); 
		// API Settings
		$api_mode      		= isset( $ups_settings['api_mode'] ) ? $ups_settings['api_mode'] : 'Test';
		$debug_mode      	= isset( $ups_settings['debug'] ) && $ups_settings['debug'] == 'yes' ? true : false;
		
		$query_string 		= isset( $_GET['wf_ups_shipment_confirm'] ) ? explode('|', base64_decode($_GET['wf_ups_shipment_confirm'])) : '' ;
		//xa_generate_return_label is set when return label is generated after generating the label, contain order/post id
		$post_id 			= ! empty($_GET['xa_generate_return_label']) ? $_GET['xa_generate_return_label'] : $query_string[1];
		$wf_ups_selected_service	= isset( $_GET['wf_ups_selected_service'] ) ? $_GET['wf_ups_selected_service'] : '';	

		$order				= $this->wf_load_order( $post_id );
		$requests = $this->wf_ups_shipment_confirmrequest( $order,true);//true for return label, false for general shipment, default is false	



		if( !$requests ) return;

		if( "Live" == $api_mode ) {
			$endpoint = 'https://onlinetools.ups.com/ups.app/xml/ShipConfirm';
		}
		else {
			$endpoint = 'https://wwwcie.ups.com/ups.app/xml/ShipConfirm';
		}

		$created_shipments_details_array = array();
		foreach ($requests as $key => $request) {
			if($key!==$return_package_index) continue; 			
			if( $debug_mode ) {
				echo '<div style="background: #eee;overflow: auto;padding: 10px;margin: 10px;">RETURN SHIPMENT CONFIRM REQUEST: ';
				echo '<xmp>'.print_r($request,1).'</xmp></div>'; 

				$this->admin_diagnostic_report( '------------------------ UPS RETURN SHIPMENT CONFIRM REQUEST ------------------------' );
				$this->admin_diagnostic_report( $request );
			}
			$xml_request = str_replace( array( "\n", "\r" ), '', $request );
			$xml_request = $this->modfiy_encoding($xml_request);
			$response = wp_remote_post( $endpoint,
				array(
					'timeout'   => 70,
					'sslverify' => $this->ssl_verify,
					'body'      => $xml_request
				)
			);
			if( $debug_mode ) {
				echo '<div style="background:#ccc;background: #ccc;overflow: auto;padding: 10px;margin: 10px 10px 50px 10px;">RETURN SHIPMENT CONFIRM RESPONSE: ';
				echo '<xmp>'.print_r($response['body'],1).'</xmp></div>'; 

				$this->admin_diagnostic_report( '------------------------ UPS RETURN SHIPMENT CONFIRM RESPONSE ------------------------' );
				$this->admin_diagnostic_report( $response['body'] );
			}

			if ( is_wp_error( $response ) ) {
				$error_message = $response->get_error_message();
				$error_message='Return Label - '.$error_message;
				wf_admin_notice::add_notice('Sorry. Something went wrong: '.$error_message);
				$this->wf_redirect( admin_url( '/post.php?post='.$post_id.'&action=edit' ) );
				exit;
			}
			
			$response_obj = simplexml_load_string( $response['body'] );
			
			$response_code = (string)$response_obj->Response->ResponseStatusCode;
			if( '0' == $response_code ) {
				$error_code = (string)$response_obj->Response->Error->ErrorCode;
				$error_desc = (string)$response_obj->Response->Error->ErrorDescription;

				$this->admin_diagnostic_report( '------------------------ UPS RETURN SHIPMENT ERROR ------------------------' );
				$this->admin_diagnostic_report( $error_desc );

				$error_desc='Return Label - '.$error_desc;
				wf_admin_notice::add_notice($error_desc.' [Error Code: '.$error_code.']');
				$this->wf_redirect( admin_url( '/post.php?post='.$post_id.'&action=edit') );
				exit;
			}
			$created_shipments_details = array();
			$shipment_id = (string)$response_obj->ShipmentIdentificationNumber;
			
			$created_shipments_details["ShipmentDigest"] 			= (string)$response_obj->ShipmentDigest;
			$created_shipments_details_array[$shipment_id] = $created_shipments_details;
		}
		return $created_shipments_details_array;
	}

	private function wf_redirect($url=''){
		if(!$url){
			return false;
		}
		if( !$this->debug ){
			wp_redirect( $url );
		}
		exit();
	}
	
	function wf_ups_shipment_accept(){
		if( !$this->wf_user_check(isset($_GET['auto_generate'])?$_GET['auto_generate']:null)  ) {
			echo "You don't have admin privileges to view this page.";
			exit;
		}
		$query_string		= explode('|', base64_decode($_GET['wf_ups_shipment_accept']));
		$post_id 			= $query_string[0];
		
		
		// Load UPS Settings.
		$ups_settings 				= get_option( 'woocommerce_'.WF_UPS_ID.'_settings', null ); 
		// API Settings
		$api_mode      				= isset( $ups_settings['api_mode'] ) ? $ups_settings['api_mode'] : 'Test';
		$ups_user_id         		= isset( $ups_settings['user_id'] ) ? $ups_settings['user_id'] : '';
		$ups_password        		= isset( $ups_settings['password'] ) ? $ups_settings['password'] : '';
		$ups_access_key      		= isset( $ups_settings['access_key'] ) ? $ups_settings['access_key'] : '';
		$ups_shipper_number  		= isset( $ups_settings['shipper_number'] ) ? $ups_settings['shipper_number'] : '';
		$disble_shipment_tracking	= isset( $ups_settings['disble_shipment_tracking'] ) ? $ups_settings['disble_shipment_tracking'] : 'TrueForCustomer';
		$debug_mode      	        = isset( $ups_settings['debug'] ) && $ups_settings['debug']  == 'yes' ? true : false;
		$DGPaper_image 				=array();
		
		if( "Live" == $api_mode ) {
			$endpoint = 'https://onlinetools.ups.com/ups.app/xml/ShipAccept';
		}
		else {
			$endpoint = 'https://wwwcie.ups.com/ups.app/xml/ShipAccept';
		}		
		
		$created_shipments_details_array	= get_post_meta($post_id, 'ups_created_shipments_details_array', true);	

		$shipment_accept_requests	=	array();
		if(is_array($created_shipments_details_array)){
			
			$ups_label_details_array	= array();
			$shipment_id_cs 			= '';
			$contextvalue               =apply_filters( 'ph_ups_update_customer_context_value',$post_id);
			
			foreach($created_shipments_details_array as $shipmentId => $created_shipments_details){
				if(isset($created_shipments_details['ShipmentDigest']) && !(isset($created_shipments_details['type']) && $created_shipments_details['type']=='freight')){
					$xml_request = '<?xml version="1.0" encoding="UTF-8" ?>';
					$xml_request .= '<AccessRequest xml:lang="en-US">';
					$xml_request .= '<AccessLicenseNumber>'.$ups_access_key.'</AccessLicenseNumber>';
					$xml_request .= '<UserId>'.$ups_user_id.'</UserId>';
					$xml_request .= '<Password>'.$ups_password.'</Password>';
					$xml_request .= '</AccessRequest>'; 
					$xml_request .= '<?xml version="1.0" ?>';
					$xml_request .= '<ShipmentAcceptRequest>';
					$xml_request .= '<Request>';
					$xml_request .= '<TransactionReference>';
					$xml_request .= '<CustomerContext>'.$contextvalue.'</CustomerContext>';
					$xml_request .= '<XpciVersion>1.0001</XpciVersion>';
					$xml_request .= '</TransactionReference>';
					$xml_request .= '<RequestAction>ShipAccept</RequestAction>';
					$xml_request .= '</Request>';
					$xml_request .= '<ShipmentDigest>'.$created_shipments_details["ShipmentDigest"].'</ShipmentDigest>';
					$xml_request .= '</ShipmentAcceptRequest>';
					
					$xml_request = $this->modfiy_encoding($xml_request);

					if( $debug_mode ) {
						echo '<div style="background: #eee;overflow: auto;padding: 10px;margin: 10px;">SHIPMENT ACCEPT REQUEST: ';
						echo '<xmp>'.$xml_request.'</xmp></div>'; 

						$this->admin_diagnostic_report( '------------------------ UPS LABEL ACCEPT REQUEST ------------------------' );
						$this->admin_diagnostic_report( $xml_request );

					}
					$response = wp_remote_post( $endpoint,
						array(
							'timeout'   => 70,
							'sslverify' => $this->ssl_verify,
							'body'      => $xml_request
						)
					);
					
					if( $debug_mode ) {
						echo '<div style="background:#ccc;background: #ccc;overflow: auto;padding: 10px;margin: 10px 10px 50px 10px;">SHIPMENT ACCEPT RESPONSE: ';
						echo '<xmp>'.print_r($response['body'],1).'</xmp></div>'; 

						$this->admin_diagnostic_report( '------------------------ UPS LABEL ACCEPT RESPONSE ------------------------' );
						$this->admin_diagnostic_report( $response['body'] );
					}
					
					if ( is_wp_error( $response ) ) {
						$error_message = $response->get_error_message();
						wf_admin_notice::add_notice(__('Order # '.$post_id.' Shipment # '.$shipmentId.' - Sorry. Something went wrong: '.$error_message));
						continue;
					}

					$response_obj = simplexml_load_string( '<root>' . preg_replace('/<\?xml.*\?>/','', $response['body'] ) . '</root>' );	

					$response_code = (string)$response_obj->ShipmentAcceptResponse->Response->ResponseStatusCode;
					
					if('0' == $response_code) {
						$error_code = (string)$response_obj->ShipmentAcceptResponse->Response->Error->ErrorCode;
						$error_desc = (string)$response_obj->ShipmentAcceptResponse->Response->Error->ErrorDescription;

						$this->admin_diagnostic_report( '------------------------ UPS LABEL ACCEPT ERROR ------------------------' );
						$this->admin_diagnostic_report( $error_desc );

						$empty_array = array();
						update_post_meta( $post_id, 'ups_created_shipments_details_array', $empty_array );
						update_post_meta( $post_id, 'ups_label_details_array', $empty_array );
						update_post_meta( $post_id, 'ups_commercial_invoice_details', $empty_array );
						update_post_meta( $post_id, 'ups_dangerous_goods_image', $empty_array );//PDS-129
						update_post_meta( $post_id, 'ups_control_log_receipt', $empty_array );
						update_post_meta( $post_id, 'ph_ups_dangerous_goods_manifest_data', $empty_array );
						update_post_meta( $post_id, 'ph_ups_dangerous_goods_manifest_required', '' );
						update_post_meta( $post_id, 'wf_ups_selected_service', '' );
						update_post_meta( $post_id, 'ups_return_label_details_array', $empty_array );
						delete_post_meta( $post_id, 'ups_shipment_ids');
						
						wf_admin_notice::add_notice(__('Order # '.$post_id.' Shipment # '.$shipmentId.' - '.$error_desc.' [Error Code: '.$error_code.']'));
						continue;
					}

					$package_results 			= $response_obj->ShipmentAcceptResponse->ShipmentResults->PackageResults;
					$ups_label_details			= array();
					
					if(isset($response_obj->ShipmentAcceptResponse->ShipmentResults->Form->Image)){
						$international_forms[$shipmentId]	=	array(
							'ImageFormat'	=>	(string)$response_obj->ShipmentAcceptResponse->ShipmentResults->Form->Image->ImageFormat->Code,
							'GraphicImage'	=>	(string)$response_obj->ShipmentAcceptResponse->ShipmentResults->Form->Image->GraphicImage,
						);
					}

					//PDS-129
					if(isset($response_obj->ShipmentAcceptResponse->ShipmentResults) && isset($response_obj->ShipmentAcceptResponse->ShipmentResults->DGPaperImage)){ 

						$DGPaper_image[$shipment_id]	=	array(
							'DGPaperImage'	=>	(string)$response_obj->ShipmentAcceptResponse->ShipmentResults->DGPaperImage,
						);
					}

					// Labels for each package.
					foreach ( $package_results as $package_result ) {

						$trackingNum 								= (string) $package_result->TrackingNumber;
						$ups_label_details["TrackingNumber"]		= ( isset($package_result->USPSPICNumber) && ctype_digit( $trackingNum ) ) ? (string) $package_result->USPSPICNumber : $trackingNum;
						$ups_label_details["Code"] 					= (string)$package_result->LabelImage->LabelImageFormat->Code;
						$ups_label_details["GraphicImage"] 			= (string)$package_result->LabelImage->GraphicImage;
						if( ! empty($package_result->LabelImage->HTMLImage) ) {
							$ups_label_details["HTMLImage"] 			= (string)$package_result->LabelImage->HTMLImage;
						}
						$ups_label_details_array[$shipmentId][]		= $ups_label_details;
						$shipment_id_cs 							.= $ups_label_details["TrackingNumber"].',';
					}

					if(isset ( $response_obj->ShipmentAcceptResponse->ShipmentResults->ControlLogReceipt->ImageFormat->Code ) ){
						$control_log_image_format = $response_obj->ShipmentAcceptResponse->ShipmentResults->ControlLogReceipt->ImageFormat->Code;
						if( $control_log_image_format == "HTML") {
							$control_log_receipt[$shipmentId] = base64_decode( $response_obj->ShipmentAcceptResponse->ShipmentResults->ControlLogReceipt->GraphicImage );
						}
					}
				}
			}
			$shipment_id_cs = rtrim( $shipment_id_cs, ',' );

			if( empty($ups_label_details_array) ) {
				wf_admin_notice::add_notice('Please create the shipment again.');
				$this->wf_redirect( admin_url( '/post.php?post='.$post_id.'&action=edit') );
				exit;
			}
			else {
				update_post_meta( $post_id, 'ups_label_details_array', $ups_label_details_array );

				if( $this->dangerous_goods_manifest ) {

					$this->ph_create_dangerous_goods_manifest($post_id);
				}
				
				if(isset($international_forms)){
					update_post_meta( $post_id, 'ups_commercial_invoice_details', $international_forms );
				}

				//PDS-129
				if(isset($DGPaper_image)){
					update_post_meta( $order_id, 'ups_dangerous_goods_image', $DGPaper_image );
				}
				
				if( isset($created_shipments_details['return']) && $created_shipments_details['return'] ){// creating return label
					$return_label_ids = $this->wf_ups_return_shipment_accept($post_id,$created_shipments_details['return']);
					if($return_label_ids&&$shipment_id_cs){
						$shipment_id_cs=$shipment_id_cs.','.$return_label_ids;
					}
				}
				
				if( isset($control_log_receipt) && !empty($control_log_receipt) ) {
					update_post_meta( $post_id, 'ups_control_log_receipt', $control_log_receipt );
				}

			}			
			if( 'True' != $disble_shipment_tracking) {
				$this->wf_redirect( admin_url( '/post.php?post='.$post_id.'&wf_ups_track_shipment='.$shipment_id_cs ) );
				exit;
			}
			wf_admin_notice::add_notice('UPS: Shipment accepted successfully. Labels are ready for printing.','notice');
			$this->wf_redirect( admin_url( '/post.php?post='.$post_id.'&action=edit') );
			exit;
		}
	}
	
	function wf_ups_return_shipment_accept($post_id,$shipment_data){
		if( !$this->wf_user_check() ) {
			echo "You don't have admin privileges to view this page.";
			exit;
		}
		
		// Load UPS Settings.
		$ups_settings 				= get_option( 'woocommerce_'.WF_UPS_ID.'_settings', null ); 
		// API Settings
		$api_mode      				= isset( $ups_settings['api_mode'] ) ? $ups_settings['api_mode'] : 'Test';
		$ups_user_id         		= isset( $ups_settings['user_id'] ) ? $ups_settings['user_id'] : '';
		$ups_password        		= isset( $ups_settings['password'] ) ? $ups_settings['password'] : '';
		$ups_access_key      		= isset( $ups_settings['access_key'] ) ? $ups_settings['access_key'] : '';
		$ups_shipper_number  		= isset( $ups_settings['shipper_number'] ) ? $ups_settings['shipper_number'] : '';
		$disble_shipment_tracking	= isset( $ups_settings['disble_shipment_tracking'] ) ? $ups_settings['disble_shipment_tracking'] : 'TrueForCustomer';
		$debug_mode      	        = isset( $ups_settings['debug'] ) && $ups_settings['debug'] == 'yes' ? true : false;
		
		if( "Live" == $api_mode ) {
			$endpoint = 'https://onlinetools.ups.com/ups.app/xml/ShipAccept';
		}
		else {
			$endpoint = 'https://wwwcie.ups.com/ups.app/xml/ShipAccept';
		}
		foreach($shipment_data as $shipment_id=>$created_shipments_details){	
			$contextvalue               =apply_filters( 'ph_ups_update_customer_context_value',$post_id);
			$created_shipments_details=current($shipment_data);// only one shipment is allowed
			$xml_request = '<?xml version="1.0"?>';
			$xml_request .= '<AccessRequest xml:lang="en-US">';
			$xml_request .= '<AccessLicenseNumber>'.$ups_access_key.'</AccessLicenseNumber>';
			$xml_request .= '<UserId>'.$ups_user_id.'</UserId>';
			$xml_request .= '<Password>'.$ups_password.'</Password>';
			$xml_request .= '</AccessRequest>'; 
			$xml_request .= '<?xml version="1.0"?>';
			$xml_request .= '<ShipmentAcceptRequest>';
			$xml_request .= '<Request>';
			$xml_request .= '<TransactionReference>';
			$xml_request .= '<CustomerContext>'.$contextvalue.'</CustomerContext>';
			$xml_request .= '<XpciVersion>1.0001</XpciVersion>';
			$xml_request .= '</TransactionReference>';
			$xml_request .= '<RequestAction>ShipAccept</RequestAction>';
			$xml_request .= '</Request>';
			$xml_request .= '<ShipmentDigest>'.$created_shipments_details["ShipmentDigest"].'</ShipmentDigest>';
			$xml_request .= '</ShipmentAcceptRequest>';
			
			$xml_request = $this->modfiy_encoding($xml_request);

			if( $debug_mode ) {
				echo '<div style="background: #eee;overflow: auto;padding: 10px;margin: 10px;">RETURN SHIPMENT ACCEPT REQUEST: ';
				echo '<xmp>'.$xml_request.'</xmp></div>'; 
			}
			$response = wp_remote_post( $endpoint,
				array(
					'timeout'   => 70,
					'sslverify' => $this->ssl_verify,
					'body'      => $xml_request
				)
			);
			
			if( $debug_mode ) {
				echo '<div style="background:#ccc;background: #ccc;overflow: auto;padding: 10px;margin: 10px 10px 50px 10px;">RETURN SHIPMENT ACCEPT RESPONSE: ';
				echo '<xmp>'.print_r($response['body'],1).'</xmp></div>'; 
			}	
			$response_obj = simplexml_load_string( '<root>' . preg_replace('/<\?xml.*\?>/','', $response['body'] ) . '</root>' );	
			$response_code = (string)$response_obj->ShipmentAcceptResponse->Response->ResponseStatusCode;
			if('0' == $response_code) {
				$error_code = (string)$response_obj->ShipmentAcceptResponse->Response->Error->ErrorCode;
				$error_desc = (string)$response_obj->ShipmentAcceptResponse->Response->Error->ErrorDescription;
				
				wf_admin_notice::add_notice($error_desc.' [Error Code: '.$error_code.']');
				return false;
			}
			$package_results 			= $response_obj->ShipmentAcceptResponse->ShipmentResults->PackageResults;		
			
			$shipment_id_cs = '';
			// Labels for each package.
			$ups_label_details_array=get_post_meta( $post_id, 'ups_return_label_details_array',true );
			if(empty($ups_label_details_array))
			{
				$ups_label_details_array=array();
			}
			if(isset($response_obj->ShipmentAcceptResponse->ShipmentResults->Form->Image)){
				$international_forms[$shipment_id]	=	array(
					'ImageFormat'	=>	(string)$response_obj->ShipmentAcceptResponse->ShipmentResults->Form->Image->ImageFormat->Code,
					'GraphicImage'	=>	(string)$response_obj->ShipmentAcceptResponse->ShipmentResults->Form->Image->GraphicImage,
				);
			}
			foreach ( $package_results as $package_result ) {
				$ups_label_details["TrackingNumber"]		= (string)$package_result->TrackingNumber;
				$ups_label_details["Code"] 					= (string)$package_result->LabelImage->LabelImageFormat->Code;
				$ups_label_details["GraphicImage"] 			= (string)$package_result->LabelImage->GraphicImage;
				if( ! empty($package_result->LabelImage->HTMLImage) ) {
					$ups_label_details["HTMLImage"] 			= (string)$package_result->LabelImage->HTMLImage;
				}
				$ups_label_details_array[$shipment_id][]	= $ups_label_details;
				$shipment_id_cs 							.= $ups_label_details["TrackingNumber"].',';
			}
			$shipment_id_cs = rtrim( $shipment_id_cs, ',' );			
			if( empty($ups_label_details_array) ) {				
				wf_admin_notice::add_notice('UPS: Sorry, An unexpected error occurred while creating return label.');
				return false;
			}
			else {
				update_post_meta( $post_id, 'ups_return_label_details_array', $ups_label_details_array );
				if(isset($international_forms)){
					update_post_meta( $post_id, 'ups_return_commercial_invoice_details', $international_forms );
				}
				return $shipment_id_cs;
			}
			break; // Only one return shipment is allowed
			return false;
		}
	}

	function wf_ups_print_label(){
		if( !$this->wf_user_check(isset($_GET['auto_generate'])?$_GET['auto_generate']:null)  ) {
			echo "You don't have admin privileges to view this page.";
			exit;
		}
		
		$ups_settings 				= get_option( 'woocommerce_'.WF_UPS_ID.'_settings', null ); 
		$print_label_type			= isset( $ups_settings['print_label_type'] ) ? $ups_settings['print_label_type'] : 'gif';

		$query_string		= explode('|', base64_decode($_GET['wf_ups_print_label']));
		$shipmentId 		= $query_string[0];
		$post_id 			= $query_string[1];
		$label_extn_code 	= $query_string[2];
		$index			 	= $query_string[3];
		$tracking_number    = $query_string[4];
		
		$label_meta_name='ups_label_details_array';
		if(isset($query_string[4])){
			$return			= $query_string[4];
			if($return=='return'){
				$label_meta_name='ups_return_label_details_array';
			}
		}
		
		$ups_label_details_array = get_post_meta( $post_id, $label_meta_name, true );
		if(empty($ups_label_details_array))
		{
			$ups_label_details_array=array();
		}

		$show_label_in_browser      = isset( $ups_settings['show_label_in_browser'] ) ? $ups_settings['show_label_in_browser'] : 'no';
		$label_in_browser_zoom 		= isset( $ups_settings['label_in_browser_zoom'] ) && !empty( $ups_settings['label_in_browser_zoom'] ) ? $ups_settings['label_in_browser_zoom'] : '100';
		$rotate_label               = isset( $ups_settings['rotate_label'] ) && !empty( $ups_settings['rotate_label'] ) ? $ups_settings['rotate_label'] : 'no';
		$label_format				= ! empty($ups_settings['label_format']) ? $ups_settings['label_format'] : null;
        $margin 					= isset( $ups_settings['label_margin'] ) && !empty( $ups_settings['label_margin'] ) ? $ups_settings['label_margin'] : '0';
		$vertical 					= isset( $ups_settings['label_vertical_align'] ) && !empty( $ups_settings['label_vertical_align'] ) ? $ups_settings['label_vertical_align'] : 'center';
		$horizontal 				= isset( $ups_settings['label_horizontal_align'] ) && !empty( $ups_settings['label_horizontal_align'] ) ? $ups_settings['label_horizontal_align'] : 'center';
		$body_css 					= "margin: ".$margin."px; display: flex; flex-direction: column; justify-content: ".$vertical.";";
		$div_css 					= "text-align: ".$horizontal.";";

		if( empty($ups_label_details_array) ) {
			wf_admin_notice::add_notice('UPS: Sorry, An unexpected error occurred.');
			wp_redirect( admin_url( '/post.php?post='.$post_id.'&action=edit') );
			exit;
		}

		$graphic_image 	= $ups_label_details_array[$shipmentId][$index]["GraphicImage"];
		$html_image		= ! empty($ups_label_details_array[$shipmentId][$index]["HTMLImage"]) ? base64_decode($ups_label_details_array[$shipmentId][$index]["HTMLImage"]) : null;
		
		$file_name_without_extension = 'UPS-ShippingLabel-' . 'Label-'.$post_id.'-'.$tracking_number;

		if( "GIF" == $label_extn_code && $print_label_type == 'gif') {

			if( "yes" == $show_label_in_browser ) {

				$html_before_image 	= "<html><body style='".$body_css."'><div style='".$div_css."'>";
				$html_after_image 	= "</div></body></html>";
				$image_style 		= "style='max-width: ". $label_in_browser_zoom ."%;'";

				if ( $label_format == 'laser_8_5_by_11' && !empty($html_image) ) {

					// For Mail Innovation Labels, Image Source is different
					if( ctype_digit($shipmentId) && ( strlen($tracking_number) > 18 || ctype_digit($tracking_number) ) ) {

						$html_image = str_replace( '<IMG SRC="'.$shipmentId.'_1.GIF"', '<IMG SRC="data:image/gif;base64,' . $graphic_image. '"', $html_image ); 
					} else {

						$html_image = str_replace( '<IMG SRC="./label'.$tracking_number.'.gif"', '<IMG SRC="data:image/gif;base64,' . $graphic_image. '"', $html_image ); 
					}

					echo $html_image;

				}else{

					$binary_label 	= base64_decode(chunk_split($graphic_image));
					$final_image 	= $binary_label;

					$source 		= imagecreatefromstring($final_image);
					 //PDS-73
					if($rotate_label =='yes'){
						$final_image 	= imagerotate($source, 0, 0);
					}
					else
					{
						$final_image 	= imagerotate($source, -90, 0);
					}

					ob_start();
					imagegif($final_image);
					$contents =  ob_get_contents();
					ob_end_clean();

					echo $html_before_image."<img ".$image_style." src='data:image/gif;base64,".base64_encode($contents)."'/>".$html_after_image;
				}

				exit;
			}

			$binary_label 	= base64_decode(chunk_split($graphic_image));
			$final_image 	= $binary_label;
			$extn_code		= 'gif';
			
			if( $extn_code == 'gif' && $show_label_in_browser != 'yes' && $label_format == 'laser_8_5_by_11' ) {

				$upload_dir_details = wp_get_upload_dir();

				if( class_exists( 'ZipArchive' ) )
				{
					$zip 			= new ZipArchive();
					$zip_file_name 	= $upload_dir_details['basedir'].'/'.$file_name_without_extension.'zip';

					if( $zip->open( $zip_file_name, ZipArchive::CREATE) ) {

						// For Mail Innovation Labels, Image Source is different
						if( ctype_digit($shipmentId) && ( strlen($tracking_number) > 18 || ctype_digit($tracking_number) ) ) {
							
							$html_data = str_replace( $shipmentId.'_1', $file_name_without_extension, $html_image);
						} else {

							$html_data = str_replace( 'label'.$tracking_number, $file_name_without_extension, $html_image);
						}

						$zip->addFromString( $file_name_without_extension.'.html', $html_data );
						$zip->addFromString( $file_name_without_extension.'.gif', $final_image );
						$zip->close();
						header('Content-Description: File Transfer');
						header('Content-Type: application/zip');
						header('Content-disposition: attachment; filename="'.$file_name_without_extension.'.zip"');
						readfile($zip_file_name);
						unlink($zip_file_name);

					}else{

						_e( 'Unable to Create Zip file. Please check permission of WP-Upload directory.', 'ups-woocommerce-shipping' );
					}

				}else{

					_e( 'Error - Unable to download Shipping Labels<br/> Reason - ZipArchive class is not enabled for your site<br/> Solution - Please contact your Hosting Provider to enable ZipArchive class for your site and try again', 'ups-woocommerce-shipping' );
				}

			}else{

				header('Content-Description: File Transfer');
				header('Content-Type: image/'.$extn_code.'');
				header('Content-disposition: attachment; filename="UPS-ShippingLabel-' . 'Label-'.$post_id.'-'.$tracking_number.'.'.$extn_code.'"');

				if ( ob_get_contents() ) {
					ob_clean();
				}

				flush();
				echo $final_image;
			}

			exit;

		} elseif("PNG" == $label_extn_code && $print_label_type == 'png') {

			if( "yes" == $show_label_in_browser ) {

				$html_before_image 	= "<html><body style='".$body_css."'><div style='".$div_css."'>";
				$html_after_image 	= "</div></body></html>";
				$image_style 		= "style='max-width: ". $label_in_browser_zoom ."%;'";

				if ( $label_format == 'laser_8_5_by_11' && ! empty($html_image) ) {

					// For Mail Innovation Labels, Image Source is different
					if( ctype_digit($shipmentId) && ( strlen($tracking_number) > 18 || ctype_digit($tracking_number) ) ) {

						$html_image = str_replace( '<IMG SRC="'.$shipmentId.'_1.PNG"', '<IMG SRC="data:image/png;base64,' . $graphic_image. '"', $html_image );
					} else {

						$html_image = str_replace( '<IMG SRC="./label'.$tracking_number.'.png"', '<IMG SRC="data:image/png;base64,' . $graphic_image. '"', $html_image );
					}

					echo $html_image;

				}else{

					$binary_label 	= base64_decode(chunk_split($graphic_image));
					$final_image 	= $binary_label;
					$extn_code		= 'png';

					$source 		= imagecreatefromstring($final_image);
					 //PDS-73
					if($rotate_label =='yes'){
						$final_image 	= imagerotate($source, 0, 0);
					}
					else
					{
						$final_image 	= imagerotate($source, -90, 0);
					}

					ob_start();
					imagepng($final_image);
					$contents =  ob_get_contents();
					ob_end_clean();

					echo $html_before_image."<img ".$image_style." src='data:image/png;base64,".base64_encode($contents)."'/>".$html_after_image;

					imagedestroy($final_image);
				}

				exit;

			}

			$binary_label 	= base64_decode(chunk_split($graphic_image));
			$final_image 	= $binary_label;
			$extn_code		= 'png';

			if( $extn_code == 'png' && $show_label_in_browser != 'yes' && $label_format == 'laser_8_5_by_11' ) {

				$upload_dir_details = wp_get_upload_dir();

				if( class_exists( 'ZipArchive' ) )
				{
					$zip  			= new ZipArchive();
					$zip_file_name 	= $upload_dir_details['basedir'].'/'.$file_name_without_extension.'zip';
					
					if( $zip->open( $zip_file_name, ZipArchive::CREATE) ) {
						
						// For Mail Innovation Labels, Image Source is different
						if( ctype_digit($shipmentId) && ( strlen($tracking_number) > 18 || ctype_digit($tracking_number) ) ) {
						
							$html_data = str_replace( $shipmentId.'_1', $file_name_without_extension, $html_image);
						} else {
							
							$html_data = str_replace( 'label'.$tracking_number, $file_name_without_extension, $html_image);
						}
						
						$zip->addFromString( $file_name_without_extension.'.html', $html_data );
						$zip->addFromString( $file_name_without_extension.'.png', $final_image );
						$zip->close();
						header('Content-Description: File Transfer');
						header('Content-Type: application/zip');
						header('Content-disposition: attachment; filename="'.$file_name_without_extension.'.zip"');
						readfile($zip_file_name);
						unlink($zip_file_name);

					}else{

						_e( 'Unable to Create Zip file. Please check permission of WP-Upload directory.', 'ups-woocommerce-shipping' );
					}

				}else{

					_e( 'Error - Unable to download Shipping Labels<br/> Reason - ZipArchive class is not enabled for your site<br/> Solution - Please contact your Hosting Provider to enable ZipArchive class for your site and try again', 'ups-woocommerce-shipping' );
				}

			}else{
				$source = imagecreatefromstring($final_image);

				// Rotate
				$final_image = imagerotate($source, -90, 0);

				if ($final_image !== false) {
					header('Content-Description: File Transfer');
					header('Content-Type: image/png');
					header('Content-disposition: attachment; filename="UPS-ShippingLabel-' . 'Label-'.$post_id.'-'.$tracking_number.'.'.$extn_code.'"');
					
					if ( ob_get_contents() ) {
						ob_clean();
					}

					flush();
					imagepng($final_image);
					imagedestroy($final_image);
				}
				else {
					echo 'An error occurred.';
				}
			}
			exit;
		}

        // ZPL which will be converted to PNG.
		elseif("ZPL" == $label_extn_code && $print_label_type == 'zpl') {
			$binary_label = base64_decode(chunk_split($graphic_image));

            // By default zpl code returned by UPS has ^POI command, which will invert the label because
            // of some reason. Removing it so that label will not be inverted.
			$zpl_label_inverted = str_replace( "^POI", "", $binary_label);
			
			$file_name = 'UPS-ShippingLabel-Label-'.$post_id.'-'.$tracking_number.'.zpl';
			$this->wf_generate_document_file($zpl_label_inverted, $file_name);
			exit;
		}
		elseif("EPL" == $label_extn_code && $print_label_type == 'epl') {
			$binary_label = base64_decode(chunk_split($graphic_image));

			$file_name = 'UPS-ShippingLabel-Label-'.$post_id.'-'.$tracking_number.'.epl';
			$this->wf_generate_document_file($binary_label, $file_name);
			exit;
		}
		elseif("PDF" == $label_extn_code ) {
			$binary_label = base64_decode(chunk_split($graphic_image));

			$file_name = 'UPS-BOL-'.$post_id.'-'.$tracking_number.'.pdf';
			$final_image=$binary_label;
			$extn_code='pdf';

            header('Content-Description: File Transfer');
            header('Content-Type: image/'.$extn_code.'');
            header('Content-disposition: attachment; filename="'. $file_name .'"');
            
            echo $final_image;

            exit;
		}
		
		$this->admin_diagnostic_report( "------------------- Label Printing Error -------------------" );
		$this->admin_diagnostic_report( "Label(s) generated using ".$label_extn_code." format." );
		$this->admin_diagnostic_report( "Trying print label in ".$print_label_type." format." );
		$this->admin_diagnostic_report( "Change the Print Label Type setting back to ".$label_extn_code." format and try again." );

		wf_admin_notice::add_notice('Label(s) generated using '.$label_extn_code.' format.');
		wf_admin_notice::add_notice('Trying print label in '.$print_label_type.' format.');
		wf_admin_notice::add_notice('Change the Print Label Type setting back to '.$label_extn_code.' format and try again.' );

		wp_redirect( admin_url( '/post.php?post='.$post_id.'&action=edit') );
		exit;
	}
	
	function wf_ups_print_commercial_invoice(){
		$req_data	= explode('|',base64_decode($_GET['wf_ups_print_commercial_invoice']));
		
		$post_id		=	$req_data[0];
		$shipment_id	=	$req_data[1];
		
		$invoice_details = get_post_meta( $post_id, 'ups_commercial_invoice_details', true );
		$graphic_image = $invoice_details[$shipment_id]["GraphicImage"];
		
		$extn_code	=	$invoice_details[$shipment_id]["ImageFormat"];
		
		header('Content-Description: File Transfer');
		header('Content-Type: image/'.$extn_code.'');
		header('Content-disposition: attachment; filename="UPS-Commercial-Invoice-'.$post_id.'.'.$extn_code.'"');
		echo base64_decode($graphic_image);
		exit;
	}

	//PDS-129
	function ph_ups_print_dangerous_goods_signatoryinfo(){
		
		$req_data		 = explode('|',base64_decode($_GET['ph_ups_dangerous_goods_signatoryinfo']));
		$post_id		 =	$req_data[0];
		$shipment_id	 =	$req_data[1];
		$invoice_details = get_post_meta( $post_id, 'ups_dangerous_goods_image', true );
		$graphic_image   = $invoice_details[$shipment_id]["DGPaperImage"];
		$extn_code		 =	'PDF';
		
		header('Content-Description: File Transfer');
		header('Content-Type: image/'.$extn_code.'');
		header('Content-disposition: attachment; filename="UPS-Dangerous-Goods-Paper-'.$post_id.'.'.$extn_code.'"');
		echo base64_decode($graphic_image);
		exit;
	}

	function wf_ups_print_return_commercial_invoice(){
		$req_data	= explode('|',base64_decode($_GET['wf_ups_print_return_commercial_invoice']));
		
		$post_id		=	$req_data[0];
		$shipment_id	=	$req_data[1];
		
		$invoice_details = get_post_meta( $post_id, 'ups_return_commercial_invoice_details', true );
		$graphic_image = $invoice_details[$shipment_id]["GraphicImage"];
		
		$extn_code	=	$invoice_details[$shipment_id]["ImageFormat"];
		
		header('Content-Description: File Transfer');
		header('Content-Type: image/'.$extn_code.'');
		header('Content-disposition: attachment; filename="UPS-Return-Commercial-Invoice-'.$post_id.'.'.$extn_code.'"');
		echo base64_decode($graphic_image);
		exit;
	}

	function ph_ups_print_control_log_receipt(){ 
		$req_data	= explode('|',base64_decode($_GET['ph_ups_print_control_log_receipt']));
		$post_id		=	$req_data[0];
		$shipment_id	=	$req_data[1];

		$control_log_receipts = get_post_meta( $post_id, 'ups_control_log_receipt', true );
		$control_log_receipt = $control_log_receipts[$shipment_id];
		echo '
				<html>
				<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
				<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
				<script>
				$(document).ready(function(){
					$(document).on("click", "#print_all", function(){
						PrintElem("content");
					})
				});
				function PrintElem(elem)
				{
					var mywindow = window.open("", "PRINT", "height=400,width=600");

					mywindow.document.write("<html><head><title>" + document.title  + "</title>");
					mywindow.document.write("</head><body >");
					mywindow.document.write("<style >");
					mywindow.document.write(`
					@page {
						// size: auto;
						// margin :10px;
					}`);
					mywindow.document.write("</style >");

					mywindow.document.write("<h1>" + document.title  + "</h1>");
					mywindow.document.write(document.getElementById("content").innerHTML);
					mywindow.document.write("</body></html>");

					mywindow.document.close(); // necessary for IE >= 10
					mywindow.focus(); // necessary for IE >= 10*/

					mywindow.print();
					mywindow.close();

					return true;
				}
				</script>
				</head>
				<body>
				<style>
				#print_all{
				    text-decoration: none;
				    display: inline-block;
				    width: 75px;
				    margin: 20px auto;
				    background: linear-gradient(#e3647e, #DC143C);
				    text-align: center;
				    color: #fff;
				    padding: 3px 6px;
				    border-radius: 3px;
				    border: 1px solid #e3647e;
				}
				#print_all:hover{
					background: linear-gradient(#e3647e, #dc143c73);
					cursor: pointer;
				}
				</style>
				<div style="text-align: center;padding: 30px;background: #f3f3f3;margin: 0px 10px 10px 10px;">
					<a id="print_all">Print</a><br/>
				</div>
				<div id="content" style="text-align: center;">
				<table align="center">
				<tr><td>';
				echo $control_log_receipt;
				echo '
				<tr><td>
				</table>
				</div>
				</body>
				</html>';
		
		exit;
	}

	function ph_ups_print_dangerous_goods_manifest() {

		$req_data		 = explode('|',base64_decode($_GET['ph_ups_dgm']));
		$order_id		 =	$req_data[0];
		$shipment_id	 =	$req_data[1];
		$order			 = $this->wf_load_order( $order_id );
		$order_object 	 = wc_get_order($order_id);
		$manifest_data	 = get_post_meta( $order_id, 'ph_ups_dangerous_goods_manifest_data', true );
		$manifest_data   = isset($manifest_data[$shipment_id]) ? $manifest_data[$shipment_id] : array() ;

		$ups_settings 			= get_option( 'woocommerce_'.WF_UPS_ID.'_settings', null ); 
		$ups_shipper_number  	= isset( $ups_settings['shipper_number'] ) ? $ups_settings['shipper_number'] : '';
		$ship_from_address      = isset( $ups_settings['ship_from_address'] ) ? $ups_settings['ship_from_address'] : 'origin_address';

		$billing_address_preference = $this->get_product_address_preference( $order, $ups_settings, false );

		if( 'billing_address' == $ship_from_address && $billing_address_preference ) {

			$to_address 	= $this->get_shop_address( $order, $ups_settings );
		} else {

			$to_address 	= $this->get_order_address( $order_object );
		}

		if ( !class_exists( 'PH_DGM_Table' ) ) {
			include_once( 'FPDF/ph_dgm_table.php' );
		}

		$pdf = new PH_DGM_Table();
		
		// Add a new page
		$pdf->AddPage();

		// Add Heading
		// Add a new font - Font Family, Font Style, Font Size
		$pdf->SetFont( 'Helvetica', '', 25 );
		// Prints a cell - Cell Width, Cell Height, Text, Border,  Position, Align
		$pdf->Cell( 0, 15, 'UPS Dangerous Goods Manifest', 0, 0, 'C' );
		// Line Break
		$pdf->Ln(20);

		// Add Account Number and Destination Address
		$pdf->SetFont( 'Helvetica', 'U', 14 );
		$pdf->Cell( 0, 10, $ups_shipper_number, 0, 0, 'L' );
		$pdf->Ln();

		$pdf->SetFont( 'Helvetica', '', 14 );
		$pdf->Cell( 0, 6, $to_address['name'], 0, 0, 'L' );
		$pdf->Ln();

		$pdf->Cell( 0, 6, $to_address['address_1'].', '.$to_address['address_2'], 0, 0, 'L' );
		$pdf->Ln();

		$pdf->Cell( 0, 6, $to_address['city'].', '.$to_address['state'].' '.$to_address['postcode'].', '.$to_address['country'], 0, 0, 'L' );
		$pdf->Ln(15);

		// Add Dangerous Goods Manifest Data as Table

		// Table Column Headings
		$header = array(
			'Hazardous Materials Description and Quantity',
			'Regulation Set',
			'The Shipment is within the limitations prescribed for:',
			'Emergency Contact Information',
			'Tracking Number',
		);
		
		// Set Table Heading Font
		$pdf->SetFont( 'Helvetica', 'B', 13 );
		
		//Table with 5 columns
		$pdf->PH_SetWidths( array( 50, 30, 40, 35, 35 ) );

		$pdf->PH_Row( $header, true );

		// Set Table Body Font
		$pdf->SetFont( 'Helvetica', '', 12 );

		// Set Table Body Alignment
		$pdf->PH_SetAligns( array( 'L', 'C', 'C', 'L', 'L' ) );

		foreach ( $manifest_data as $productId => $hazmatData ) {
			
			$descAndQuantity 	= $hazmatData['commodityId'].', '.strtoupper($hazmatData['properShippingName']).', '.$hazmatData['classDivisionNumber'].', '.$hazmatData['packagingGroupType'].', '.$hazmatData['quantity'].' '.strtoupper($hazmatData['packagingType']).' x '.$hazmatData['productWeight'].' '.strtoupper($hazmatData['uom']);
			$regulationSet 		= $hazmatData['regulationSet'];
			$transportationMode = $hazmatData['transportationMode'];
			$emergencyNum 		= isset( $ups_settings['phone_number'] ) ? $ups_settings['phone_number'] : '';
			$trackingNumber 	= $hazmatData['trackingNumber'];

			// Add Table Rows
			$pdf->PH_Row( array( $descAndQuantity, $regulationSet, $transportationMode, $emergencyNum, $trackingNumber ) );
		}
		
		$pdf->Output( 'I', 'UPS-Dangerous-Goods-Manifest-'.$order_id.'.pdf' );

		die;
		
	}

	private function wf_generate_document_file($content, $file_name){
		
		$uploads_dir_info		=	wp_upload_dir();
		$file_name_with_path	=	$uploads_dir_info['basedir'].$file_name;
		$handle = fopen($file_name_with_path, "w");
		fwrite($handle, $content);
		fclose($handle);

		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename='.basename($file_name));
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($file_name_with_path));
		
		if ( ob_get_contents() ) {
			ob_clean();
		}
		
		flush();
		readfile($file_name_with_path);
		unlink($file_name_with_path);
		return;
	}

	function wf_ups_void_shipment(){
		if( !$this->wf_user_check() ) {
			echo "You don't have admin privileges to view this page.";
			exit;
		}

		$query_string		= explode( '|', base64_decode( $_GET['wf_ups_void_shipment'] ) );
		$post_id 			= $query_string[0];
		$ups_label_details_array 	= get_post_meta( $post_id, 'ups_label_details_array', true );
		
		// Load UPS Settings.
		$ups_settings 				= get_option( 'woocommerce_'.WF_UPS_ID.'_settings', null ); 
		// API Settings
		$api_mode		      		= isset( $ups_settings['api_mode'] ) ? $ups_settings['api_mode'] : 'Test';
		$ups_user_id         		= isset( $ups_settings['user_id'] ) ? $ups_settings['user_id'] : '';
		$ups_password        		= isset( $ups_settings['password'] ) ? $ups_settings['password'] : '';
		$ups_access_key      		= isset( $ups_settings['access_key'] ) ? $ups_settings['access_key'] : '';
		$ups_shipper_number  		= isset( $ups_settings['shipper_number'] ) ? $ups_settings['shipper_number'] : '';
		
		
		
		$client_side_reset = false;
		if( isset( $_GET['client_reset'] ) ) {
			$client_side_reset = true;
		}
		
		if( "Live" == $api_mode ) {
			$endpoint = 'https://onlinetools.ups.com/ups.app/xml/Void';
		}
		else {
			$endpoint = 'https://wwwcie.ups.com/ups.app/xml/Void';
		}	

		if( !empty( $ups_label_details_array ) && !$client_side_reset ) {
			foreach($ups_label_details_array as $shipmentId => $ups_label_detail_arr){
				if(isset($ups_label_detail_arr[0]['GFP']) && $ups_label_detail_arr[0]['GFP'])
				{
					$contextvalue               =apply_filters( 'ph_ups_update_customer_context_value',$post_id);
					$xml_request  = '<VoidShipmentRequest>';
					$xml_request .= '<Request>';
					$xml_request .= '<TransactionReference>';
					$xml_request .= '<CustomerContext>'.$contextvalue.'</CustomerContext>';
					$xml_request .= '</TransactionReference>';
					$xml_request .= '<RequestAction>Void</RequestAction>';
					$xml_request .= '<RequestOption />';
					$xml_request .= '</Request>';
					$xml_request .= '<VoidShipment>';
					$xml_request .= '<ShipmentIdentificationNumber>'.$shipmentId.'</ShipmentIdentificationNumber>';
					foreach ( $ups_label_detail_arr as $ups_label_details ) {
						$xml_request .= '<TrackingNumber>'.$ups_label_details["TrackingNumber"].'</TrackingNumber>';
					}
					$xml_request .= '</VoidShipment>';
					$xml_request .= '</VoidShipmentRequest>';
					
					$xml_request	= apply_filters( 'ph_ups_void_shipment_xml_request_gfp', $xml_request, $shipmentId, $post_id );	// To support vendor addon

					$xml_request = $this->modfiy_encoding($xml_request);

					$ups_user_id         		= isset( $ups_settings['user_id'] ) ? $ups_settings['user_id'] : '';
					$ups_password        		= isset( $ups_settings['password'] ) ? $ups_settings['password'] : '';
					$ups_access_key      		= isset( $ups_settings['access_key'] ) ? $ups_settings['access_key'] : '';
		    		$header=new stdClass();
		    		$header->UsernameToken=new stdClass();
					$header->UsernameToken->Username=$ups_settings['user_id'];
					$header->UsernameToken->Password=$ups_settings['password'];
		    		$header->ServiceAccessToken=new stdClass();
					$header->ServiceAccessToken->AccessLicenseNumber=$ups_settings['access_key'];
					$client = $this->wf_create_soap_client( plugin_dir_path( dirname( __FILE__ ) ) . 'wsdl/'.$api_mode.'/shipment/Void.wsdl', array(
								'trace' =>	true,
								'cache_wsdl' => 0
								) );
					$authvalues = new SoapVar($header, SOAP_ENC_OBJECT);
					$header = new SoapHeader('http://www.ups.com/XMLSchema/XOLTWS/UPSS/v1.0','UPSSecurity',$header,false);
					
					$client->__setSoapHeaders($header);

					$client->__setSoapHeaders($header);
					$xml_request = simplexml_load_string($xml_request);
					$response=array();
					$error_message='';
					try {

					   	$response = $client->ProcessVoid( $xml_request );


					} catch (\SoapFault $fault) {
						$error_message=$fault->faultstring;

						$message = '<strong>'.$error_message.' </strong>';

						$current_page_uri	= $_SERVER['REQUEST_URI'];
						$href_url 			= $current_page_uri.'&client_reset';
						$message .= 'Please contact UPS to void/cancel this shipment. <br/>';
						$message .= 'If you have already cancelled this shipment by calling UPS customer care, and you would like to create shipment again then click <a class="button button-primary tips" href="'.$href_url.'" data-tip="Client Side Reset">Client Side Reset</a>';
						$message .= '<p style="color:red"><strong>Note: </strong>Previous shipment details and label will be removed from Order page.</p>';
						
						if( "Test" == $api_mode ) {
							$message .= "<strong>Also, noticed that you have enabled 'Test' mode.<br/>Please note that void is not possible in 'Test' mode, as there is no real shipment is created with UPS. </strong><br/>";
						}
						wf_admin_notice::add_notice($message);
						$log = wc_get_logger();
						$log->debug( print_r( __('------------------------UPS GFP Void Request -------------------------------', 'ups-woocommerce-shipping').PHP_EOL.PHP_EOL.htmlspecialchars($client->__getLastRequest()).PHP_EOL.PHP_EOL,true), array('source' => 'PluginHive-UPS-Plugin GFP Void'));
						$log->debug( print_r( __('------------------------UPS GFP void Response -------------------------------', 'ups-woocommerce-shipping').PHP_EOL.PHP_EOL.htmlspecialchars($client->__getLastResponse()).PHP_EOL.PHP_EOL,true), array('source' => 'PluginHive-UPS-Plugin GFP Void'));
						
						wp_redirect( admin_url( '/post.php?post='.$post_id.'&action=edit') );
						exit;
					    //trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);

					}
					$log = wc_get_logger();
					$log->debug( print_r( __('------------------------UPS GFP Void Request -------------------------------', 'ups-woocommerce-shipping').PHP_EOL.PHP_EOL.htmlspecialchars($client->__getLastRequest()).PHP_EOL.PHP_EOL,true), array('source' => 'PluginHive-UPS-Plugin GFP Void'));
					$log->debug( print_r( __('------------------------UPS GFP void Response -------------------------------', 'ups-woocommerce-shipping').PHP_EOL.PHP_EOL.htmlspecialchars($client->__getLastResponse()).PHP_EOL.PHP_EOL,true), array('source' => 'PluginHive-UPS-Plugin GFP Void'));
					continue;
				}
				else
				{
					$contextvalue               =apply_filters( 'ph_ups_update_customer_context_value',$post_id);
					$xml_request = '<?xml version="1.0" ?>';
					$xml_request .= '<AccessRequest xml:lang="en-US">';
					$xml_request .= '<AccessLicenseNumber>'.$ups_access_key.'</AccessLicenseNumber>';
					$xml_request .= '<UserId>'.$ups_user_id.'</UserId>';
					$xml_request .= '<Password>'.$ups_password.'</Password>';
					$xml_request .= '</AccessRequest>';
					$xml_request .= '<?xml version="1.0" encoding="UTF-8" ?>';
					$xml_request .= '<VoidShipmentRequest>';
					$xml_request .= '<Request>';
					$xml_request .= '<TransactionReference>';
					$xml_request .= '<CustomerContext>'.$contextvalue.'</CustomerContext>';
					$xml_request .= '<XpciVersion>1.0001</XpciVersion>';
					$xml_request .= '</TransactionReference>';
					$xml_request .= '<RequestAction>Void</RequestAction>';
					$xml_request .= '<RequestOption />';
					$xml_request .= '</Request>';
					$xml_request .= '<ExpandedVoidShipment>';
					$xml_request .= '<ShipmentIdentificationNumber>'.$shipmentId.'</ShipmentIdentificationNumber>';
					foreach ( $ups_label_detail_arr as $ups_label_details ) {
						$xml_request .= '<TrackingNumber>'.$ups_label_details["TrackingNumber"].'</TrackingNumber>';
					}
					$xml_request .= '</ExpandedVoidShipment>';
					$xml_request .= '</VoidShipmentRequest>';
					
					$xml_request	= apply_filters( 'xa_ups_void_shipment_xml_request', $xml_request, $shipmentId, $post_id );	// To support vendor addon

					$xml_request = $this->modfiy_encoding($xml_request);
					$response = wp_remote_post( $endpoint,
						array(
							'timeout'   => 70,
							'sslverify' => $this->ssl_verify,
							'body'      => $xml_request
						)
					);
					$log = wc_get_logger();
					$log->debug( print_r( __('------------------------void request -------------------------------', 'ups-woocommerce-shipping').PHP_EOL.PHP_EOL.print_r($xml_request,1).PHP_EOL.PHP_EOL,true), array('source' => 'PluginHive-UPS-Plugin void'));
					$log->debug( print_r( __('------------------------voide Response -------------------------------', 'ups-woocommerce-shipping').PHP_EOL.PHP_EOL.print_r($response,1).PHP_EOL.PHP_EOL,true), array('source' => 'PluginHive-UPS-Plugin void'));
		
					// In case of any issues with remote post.
					if ( is_wp_error( $response ) ) {
						wf_admin_notice::add_notice('Sorry. Something went wrong: '.$error_message);
						wp_redirect( admin_url( '/post.php?post='.$post_id.'&action=edit') );
						exit;
					}
					
					$response_obj 	= simplexml_load_string( '<root>' . preg_replace('/<\?xml.*\?>/','', $response['body'] ) . '</root>' );
					$response_code 	= (string)$response_obj->VoidShipmentResponse->Response->ResponseStatusCode;

					// It is an error response.
					if( '0' == $response_code ) {
						$error_code = (string)$response_obj->VoidShipmentResponse->Response->Error->ErrorCode;
						$error_desc = (string)$response_obj->VoidShipmentResponse->Response->Error->ErrorDescription;
						
						$message = '<strong>'.$error_desc.' [Error Code: '.$error_code.']'.'. </strong>';

						$current_page_uri	= $_SERVER['REQUEST_URI'];
						$href_url 			= $current_page_uri.'&client_reset';
						
						$message .= 'Please contact UPS to void/cancel this shipment. <br/>';
						$message .= 'If you have already cancelled this shipment by calling UPS customer care, and you would like to create shipment again then click <a class="button button-primary tips" href="'.$href_url.'" data-tip="Client Side Reset">Client Side Reset</a>';
						$message .= '<p style="color:red"><strong>Note: </strong>Previous shipment details and label will be removed from Order page.</p>';
						
						if( "Test" == $api_mode ) {
							$message .= "<strong>Also, noticed that you have enabled 'Test' mode.<br/>Please note that void is not possible in 'Test' mode, as there is no real shipment is created with UPS. </strong><br/>";
						}
						wf_admin_notice::add_notice($message);
						wp_redirect( admin_url( '/post.php?post='.$post_id.'&action=edit') );
						exit;
					}
					
					$this->wf_ups_void_return_shipment($post_id,$shipmentId);
				}
			}			
		}
		$empty_array = array();
		update_post_meta( $post_id, 'ups_created_shipments_details_array', $empty_array );
		update_post_meta( $post_id, 'ups_label_details_array', $empty_array );
		update_post_meta( $post_id, 'ups_commercial_invoice_details', $empty_array );
		update_post_meta( $post_id, 'ups_dangerous_goods_image', $empty_array );//PDS-129
		update_post_meta( $post_id, 'ups_control_log_receipt', $empty_array );
		update_post_meta( $post_id, 'ph_ups_dangerous_goods_manifest_data', $empty_array );
		update_post_meta( $post_id, 'ph_ups_dangerous_goods_manifest_required', '' );

		update_post_meta( $post_id, 'wf_ups_selected_service', '' );
		delete_post_meta( $post_id, 'ups_shipment_ids');
		update_post_meta( $post_id, 'ups_return_label_details_array', $empty_array );
		
		// Reset of stored meta elements done. Back to admin order page. 
		if( $client_side_reset ){
			wf_admin_notice::add_notice('UPS: Client side reset of labels and shipment completed. You can re-initiate shipment now.','notice');
			wp_redirect( admin_url( '/post.php?post='.$post_id.'&action=edit') );
			exit;
		}
		wf_admin_notice::add_notice('UPS: Cancellation of shipment completed successfully. You can re-initiate shipment.','notice');
		wp_redirect( admin_url( '/post.php?post='.$post_id.'&action=edit') );
		exit;
	}
	
	function wf_ups_void_return_shipment($post_id,$shipmentId){
		$ups_created_shipments_details_array=get_post_meta($post_id,'ups_created_shipments_details_array',1);
		if(is_array($ups_created_shipments_details_array)&&isset($ups_created_shipments_details_array[$shipmentId]['return'])){
			$return_shipment_id=current(array_keys($ups_created_shipments_details_array[$shipmentId]['return']));
			if($return_shipment_id){
				// Load UPS Settings.
				$ups_settings 				= get_option( 'woocommerce_'.WF_UPS_ID.'_settings', null ); 
				// API Settings
				$api_mode		      		= isset( $ups_settings['api_mode'] ) ? $ups_settings['api_mode'] : 'Test';
				$ups_user_id         		= isset( $ups_settings['user_id'] ) ? $ups_settings['user_id'] : '';
				$ups_password        		= isset( $ups_settings['password'] ) ? $ups_settings['password'] : '';
				$ups_access_key      		= isset( $ups_settings['access_key'] ) ? $ups_settings['access_key'] : '';
				$ups_shipper_number  		= isset( $ups_settings['shipper_number'] ) ? $ups_settings['shipper_number'] : '';
				
				$ups_return_label_details_array 	= get_post_meta( $post_id, 'ups_return_label_details_array', true );
				
				$client_side_reset = false;
				if( isset( $_GET['client_reset'] ) ) {
					$client_side_reset = true;
				}
				
				if( "Live" == $api_mode ) {
					$endpoint = 'https://onlinetools.ups.com/ups.app/xml/Void';
				}
				else {
					$endpoint = 'https://wwwcie.ups.com/ups.app/xml/Void';
				}
				$contextvalue               =apply_filters( 'ph_ups_update_customer_context_value',$post_id);
				
				if( !empty( $ups_return_label_details_array ) && $return_shipment_id) {
					$xml_request = '<?xml version="1.0" ?>';
					$xml_request .= '<AccessRequest xml:lang="en-US">';
					$xml_request .= '<AccessLicenseNumber>'.$ups_access_key.'</AccessLicenseNumber>';
					$xml_request .= '<UserId>'.$ups_user_id.'</UserId>';
					$xml_request .= '<Password>'.$ups_password.'</Password>';
					$xml_request .= '</AccessRequest>';
					$xml_request .= '<?xml version="1.0" encoding="UTF-8" ?>';
					$xml_request .= '<VoidShipmentRequest>';
					$xml_request .= '<Request>';
					$xml_request .= '<TransactionReference>';
					$xml_request .= '<CustomerContext>'.$contextvalue.'</CustomerContext>';
					$xml_request .= '<XpciVersion>1.0001</XpciVersion>';
					$xml_request .= '</TransactionReference>';
					$xml_request .= '<RequestAction>Void</RequestAction>';
					$xml_request .= '<RequestOption />';
					$xml_request .= '</Request>';
					$xml_request .= '<ExpandedVoidShipment>';
					$xml_request .= '<ShipmentIdentificationNumber>'.$return_shipment_id.'</ShipmentIdentificationNumber>';
					foreach ( $ups_return_label_details_array[$return_shipment_id] as $ups_return_label_details ) {
						$xml_request .= '<TrackingNumber>'.$ups_return_label_details["TrackingNumber"].'</TrackingNumber>';
					}
					$xml_request .= '</ExpandedVoidShipment>';
					$xml_request .= '</VoidShipmentRequest>';
					$xml_request = $this->modfiy_encoding($xml_request);
					$response = wp_remote_post( $endpoint,
						array(
							'timeout'   => 70,
							'sslverify' => $this->ssl_verify,
							'body'      => $xml_request
						)
					);
					
					// In case of any issues with remote post.
					if ( is_wp_error( $response ) ) {
						wf_admin_notice::add_notice('Sorry. Something went wrong: '.$error_message);
						return;
					}
					
					$response_obj 	= simplexml_load_string( '<root>' . preg_replace('/<\?xml.*\?>/','', $response['body'] ) . '</root>' );
					$response_code 	= (string)$response_obj->VoidShipmentResponse->Response->ResponseStatusCode;

					// It is an error response.
					if( '0' == $response_code ) {
						$error_code = (string)$response_obj->VoidShipmentResponse->Response->Error->ErrorCode;
						$error_desc = (string)$response_obj->VoidShipmentResponse->Response->Error->ErrorDescription;
						
						$message = '<strong>'.$error_desc.' [Error Code: '.$error_code.']'.'. </strong>';

						$current_page_uri	= $_SERVER['REQUEST_URI'];
						$href_url 			= $current_page_uri.'&client_reset';
						
						$message .= 'Please contact UPS to void/cancel this shipment. <br/>';
						$message .= 'If you have already cancelled this shipment by calling UPS customer care, and you would like to create shipment again then click <a class="button button-primary tips" href="'.$href_url.'" data-tip="Client Side Reset">Client Side Reset</a>';
						$message .= '<p style="color:red"><strong>Note: </strong>Previous shipment details and label will be removed from Order page.</p>';
						
						if( "Test" == $api_mode ) {
							$message .= "<strong>Also, noticed that you have enabled 'Test' mode.<br/>Please note that void is not possible in 'Test' mode, as there is no real shipment is created with UPS. </strong><br/>";
						}
						
						wf_admin_notice::add_notice($message);
						return;
					}
				}
				$empty_array = array();
				update_post_meta( $post_id, 'ups_return_label_details_array', $empty_array );
			}
		}
		
	}

	function wf_load_order( $orderId ){
		if( !$orderId ){
			return false;
		}
		if(!class_exists('wf_order')){
			include_once('class-wf-legacy.php');
		}
		$order = ( WC()->version < '2.7.0' ) ? wc_get_order($orderId) : new wf_order( $orderId );
		return $order;
	}
	
	function wf_user_check($auto_generate=null) {
		$current_minute=(integer)date('i');
		if(!empty($auto_generate) && ($auto_generate==md5($current_minute) || $auto_generate==md5($current_minute+1) ))
		{
			return true;
		}
		if ( is_admin() ) {
			return true;
		}
		return false;
	}
	
	function wf_get_shipping_service_data($order) {

		//TODO: Take the first shipping method. The use case of multiple shipping method for single order is not handled.
		
		$shipping_methods 			= $order->get_shipping_methods();
		$wf_ups_selected_service 	= get_post_meta( $order->get_id(), 'wf_ups_selected_service', true );
		$shipping_service_tmp_data 	= array();

		if ( !$shipping_methods ) {

			$return_array = apply_filters( 'ph_shipping_method_array_filter', false, $order, $this->ups_services, $this->settings, $this->origin_country );
			
			if ( $return_array ) {

				return $return_array;
			}
		}

		if( !empty($shipping_methods) && is_array($shipping_methods) ) {

			$shipping_method			= array_shift( $shipping_methods );

			if(self::$wc_version >= '3.0.0') $shipping_method_ups_meta 	= $shipping_method->get_meta('_xa_ups_method');

			$selected_service 			= ! empty($shipping_method_ups_meta) ? $shipping_method_ups_meta['id'] : $shipping_method['method_id'];
			$shipping_service_tmp_data	= explode( ':', $selected_service );

		}
		
		// If already tried to generate the label with any service
		if( '' != $wf_ups_selected_service ) {

			$shipping_service_data['shipping_method'] 		= WF_UPS_ID;
			$shipping_service_data['shipping_service'] 		= $wf_ups_selected_service;
			$shipping_service_data['shipping_service_name']	= isset( $this->ups_services[$wf_ups_selected_service] ) ? $this->ups_services[$wf_ups_selected_service] : '';

		// Customer Selected Service if UPS
		} elseif( ! empty($shipping_service_tmp_data) && $shipping_service_tmp_data[0] == WF_UPS_ID && isset($shipping_service_tmp_data[1]) ) {

			$shipping_service_data = array(
				'shipping_method'		=>	WF_UPS_ID,
				'shipping_service_name'	=>	$shipping_service_tmp_data[0],
				'shipping_service'		=>	$shipping_service_tmp_data[1],
			);

		} elseif ( $this->is_domestic($order) && !empty($this->settings['default_dom_service']) ) {

			$service_code = $this->settings['default_dom_service'];

			$shipping_service_data = array(
				'shipping_method' 		=> WF_UPS_ID,
				'shipping_service' 		=> $service_code,
				'shipping_service_name'	=> isset( $this->ups_services[$service_code] ) ? $this->ups_services[$service_code] : '',
			);

		} elseif ( !$this->is_domestic($order) && !empty($this->settings['default_int_service']) ) {

			$service_code = $this->settings['default_int_service'];

			$shipping_service_data = array(
				'shipping_method' 		=> WF_UPS_ID,
				'shipping_service' 		=> $service_code,
				'shipping_service_name'	=> isset( $this->ups_services[$service_code] ) ? $this->ups_services[$service_code] : '',
			);

		} else {

			$shipping_service_data['shipping_method'] 		= WF_UPS_ID;
			$shipping_service_data['shipping_service'] 		= '';
			$shipping_service_data['shipping_service_name']	= '';
		}

		return $shipping_service_data;
	}

	private function is_domestic( $order){
		return ( $order->shipping_country == $this->origin_country );
	}

	public function get_dimension_from_package($package){

		$dimensions	=	array(
			'Length'		=>	null,
			'Width'			=>	null,
			'Height'		=>	null,
			'Weight'		=>	null,
			'InsuredValue'	=>	null,
		);

		if(!isset($package['Package'])){
			return $dimensions;
		}
		if(isset($package['Package']['Dimensions'])){
			$dimensions['Length']	=	$package['Package']['Dimensions']['Length'];
			$dimensions['Width']	=	$package['Package']['Dimensions']['Width'];
			$dimensions['Height']	=	$package['Package']['Dimensions']['Height'];
		}

		$weight					=	$package['Package']['PackageWeight']['Weight'];

		if($package['Package']['PackageWeight']['UnitOfMeasurement']['Code']=='OZS'){
		if($this->weight_unit=='LBS'){ // make weight in pounds
			$weight	=	$weight/16;
		}else{
			$weight	=	$weight/35.274; // To KG
		}
	}
	//PackageServiceOptions
	if(isset($package['Package']['PackageServiceOptions']['InsuredValue'])){
		$dimensions['InsuredValue']	=	$package['Package']['PackageServiceOptions']['InsuredValue']['MonetaryValue'];
	}
	$dimensions['Weight']	=	$weight;
	return $dimensions;
}

public function manual_packages($packages , $order) {

	if( isset($_GET["package_key"]) ) {

		$package_indexes	= json_decode(stripslashes(html_entity_decode($_GET["package_key"])));

		if(!empty($package_indexes) && is_array($package_indexes))
		{
			$final_packages=[];
			foreach( $package_indexes as $packages_index ) {
				if(isset($packages[$packages_index]))
				{		
					$final_packages[]=$packages[$packages_index];
				}
			}
			$packages = $final_packages;
		}
	}

	if(!isset($_GET['weight'])){
		return $packages;
	}

	$post_id 		= 	$order->get_id();
	$length_arr		=	json_decode(stripslashes(html_entity_decode($_GET["length"])));
	$width_arr		=	json_decode(stripslashes(html_entity_decode($_GET["width"])));
	$height_arr		=	json_decode(stripslashes(html_entity_decode($_GET["height"])));
	$weight_arr		=	json_decode(stripslashes(html_entity_decode($_GET["weight"])));		
	$insurance_arr	=	json_decode(stripslashes(html_entity_decode($_GET["insurance"])));
	$service_arr	=	json_decode(stripslashes(html_entity_decode($_GET["service"])));

	$no_of_package_entered	=	count($weight_arr);
	$no_of_packages			=	count($packages);

	// Populate extra packages, if entered manual values
	if($no_of_package_entered > $no_of_packages) {

		// Get first package to clone default data
		$package_clone	= current($packages);
		$package_unit 	= ( isset($package_clone['Package']) && isset($package_clone['Package']['PackageWeight']) && isset($package_clone['Package']['PackageWeight']['UnitOfMeasurement']) ) ? $package_clone['Package']['PackageWeight']['UnitOfMeasurement']['Code'] : $this->weight_unit;

		for($i=$no_of_packages; $i<$no_of_package_entered; $i++) {

			$packages[$i]	=	array(
				'Package'	=>	array(
					'PackagingType'	=>	array(
						'Code'	=>	'02',
						'Description'	=>	'Package/customer supplied',
					),
					'Description'	=>	'Rate',
					'PackageWeight'	=>	array(
						'UnitOfMeasurement'	=>	array(
							'Code'	=>	$package_unit,
						),
						'Weight'	=> '',
					),
				),
			);
		}
	}

	// Overridding package values
	foreach($packages as $key => $package) {

		if( !isset($packages[$key]['Package']['Dimensions']) && isset($length_arr[$key]) && $length_arr[$key] !== "" ) {
			$packages[$key]['Package']['Dimensions'] = array();
		}
		// If not available in GET then don't overwrite.
		if( isset($length_arr[$key]) && $length_arr[$key] !== "" ) {
			$packages[$key]['Package']['Dimensions']['Length']	=	$length_arr[$key];
		}
		// If not available in GET then don't overwrite.
		if( isset($width_arr[$key]) && $width_arr[$key] !== "" ) {
			$packages[$key]['Package']['Dimensions']['Width']	=	$width_arr[$key];
		}
		// If not available in GET then don't overwrite.
		if( isset($height_arr[$key]) && $height_arr[$key] !== "" ) {
			$packages[$key]['Package']['Dimensions']['Height']	=	$height_arr[$key];
		}

		// If not available in GET then don't overwrite.
		if(isset($weight_arr[$key])) {

			$weight	=	$weight_arr[$key];
			// Surepost Less Than 1 LBS
			if(isset($service_arr[$key]) && $service_arr[$key]==92) {
				$packages[$key]['Package']['PackageWeight']['UnitOfMeasurement']['Code']	=	'OZS';
			}

			if($packages[$key]['Package']['PackageWeight']['UnitOfMeasurement']['Code']=='OZS') {

				// make sure weight from pounds to ounces
				if($this->weight_unit=='LBS') {
					$weight	=	$weight*16;
				}else{
					// From KG to ounces
					$weight	=	$weight*35.274;
				}
			}
			$packages[$key]['Package']['PackageWeight']['Weight']	=	$weight;
		}
		// If not available in GET then don't overwrite.
		if( isset($insurance_arr[$key]) && $insurance_arr[$key] !== "" ) {

			if( !isset($packages[$key]['Package']['PackageServiceOptions']) ) {
				$packages[$key]['Package']['PackageServiceOptions'] = array();
			}
			$packages[$key]['Package']['PackageServiceOptions']['InsuredValue'] = array();
			$packages[$key]['Package']['PackageServiceOptions']['InsuredValue']	= array(
				'CurrencyCode'	=>	$this->wcsups->get_ups_currency(),
				'MonetaryValue'	=>	round($insurance_arr[$key],2),
			);
		}
	}
	update_post_meta( $post_id, '_wf_ups_stored_packages', $packages );
	return $packages;
}


function split_shipment_by_services($ship_packages, $order, $return_label=false){

	$shipments	=	array();

	if (!isset($_GET['service'])) {

		$order_id 	= ( WC()->version < '3.0' ) ? $order->id : $order->get_id();

		if( isset($this->auto_label_generation) && $this->auto_label_generation && !empty($this->auto_label_services) ) {

			foreach($this->auto_label_services as $count => $service_code) {

				if(isset($ship_packages[$count])) {
					$shipment_arr[$service_code][]	=	$ship_packages[$count];
				}
			}

			foreach($shipment_arr as $service_code => $packages) {

				$shipments[]	=	array(
					'shipping_service'	=>	$service_code,
					'packages'			=>	$packages,
				);
			}

		}else{

			$shipping_service_data	= $this->wf_get_shipping_service_data( $order );
			$default_service_type 	= $shipping_service_data['shipping_service'];

			$default_service_code = '["'.$default_service_type.'"]';

			update_post_meta( $order_id, 'xa_ups_generated_label_services',$default_service_code);

			$shipments[]	=	array(
				'shipping_service'	=>	$default_service_type,
				'packages'			=>	$ship_packages,
			);
		}

	}else{

		$order_id = ( WC()->version < '3.0' ) ? $order->id : $order->get_id();
		
		// Services for return label if label has been generated previously
		if( ! empty($_GET['xa_generate_return_label'] ) ) {
			
			$service_arr = json_decode(html_entity_decode(base64_decode($_GET["rt_service"])));

		}else {	// Services for label

			$service_arr            =       json_decode(stripslashes(html_entity_decode($_GET["service"])));
			
			update_post_meta( $order_id, 'xa_ups_generated_label_services',$_GET["service"]);

			// Services for return label if it is being generated at the time of label creation only
			if ($return_label) {

				$service_arr 	= json_decode(stripslashes(html_entity_decode($_GET["rt_service"])));

			}
		}

		foreach($service_arr as $count => $service_code){
			if(isset($ship_packages[$count] ) ) {
				$shipment_arr[$service_code][]	=	$ship_packages[$count];
			}
		}

		foreach($shipment_arr as $service_code => $packages){
			$shipments[]	=	array(
				'shipping_service'	=>	$service_code,
				'packages'			=>	$packages,
			);
		}
	}
	return $shipments;
}

		function array2XML($obj, $array)
		{
			foreach ($array as $key => $value)
			{
				if(is_numeric($key))
					$key = 'item' . $key;

				if (is_array($value))
				{
					$node = $obj->addChild($key);
					$this->array2XML($node, $value);
				}
				else
				{
					$obj->addChild($key, htmlspecialchars($value));
				}
			}
		}

	// Bulk Label Printing

		function init_bulk_printing(){
			add_action('admin_footer', 	array($this, 'add_bulk_print_option'));
			add_action('load-edit.php',	array($this, 'perform_bulk_label_actions'));
	// Add Print Label option to Order list page Make Sure Screen Options->Actions is checked
			add_action('woocommerce_admin_order_actions_end', array($this, 'label_printing_buttons'));
		}

		function add_bulk_print_option(){
			global $post_type;

			if($post_type == 'shop_order' && $this->disble_ups_print_label != 'yes' ) {
				?>
				<script type="text/javascript">
					jQuery(document).ready(function() {
						jQuery('<option>').val('ups_generate_label').text("<?php _e('Generate UPS Label', 'ups-woocommerce-shipping');?>").appendTo("select[name='action']");
						jQuery('<option>').val('ups_generate_label').text("<?php _e('Generate UPS Label', 'ups-woocommerce-shipping');?>").appendTo("select[name='action2']");

						jQuery('<option>').val('ups_void_shipment').text("<?php _e('Void UPS Shipment', 'ups-woocommerce-shipping');?>").appendTo("select[name='action']");
						jQuery('<option>').val('ups_void_shipment').text("<?php _e('Void UPS Shipment', 'ups-woocommerce-shipping');?>").appendTo("select[name='action2']");

						// Bulk label printing in pdf
						jQuery('<option>').val('xa_ups_print_label_pdf').text("<?php _e('Print UPS Label (PDF)', 'ups-woocommerce-shipping');?>").appendTo("select[name='action']");
						jQuery('<option>').val('xa_ups_print_label_pdf').text("<?php _e('Print UPS Label (PDF)', 'ups-woocommerce-shipping');?>").appendTo("select[name='action2']");

						// Bulk label printing in PNG
						jQuery('<option>').val('xa_ups_print_label_image').text("<?php _e('Print UPS Label (Image)', 'ups-woocommerce-shipping');?>").appendTo("select[name='action']");
						jQuery('<option>').val('xa_ups_print_label_image').text("<?php _e('Print UPS Label (Image)', 'ups-woocommerce-shipping');?>").appendTo("select[name='action2']");
					
					
					});
				</script>
				<?php
			}
		}

function perform_bulk_label_actions(){
	$wp_list_table = _get_list_table('WP_Posts_List_Table');
	$action = $wp_list_table->current_action();

	if($action == 'ups_generate_label'){			
		if(isset($_REQUEST['post']) && is_array($_REQUEST['post'])){
			foreach($_REQUEST['post'] as $order_id){

				if($this->ups_confirm_shipment($order_id)){
					$this->ups_accept_shipment($order_id);
				}					
			}
			wp_redirect( admin_url( '/edit.php?post_type=shop_order') );
			exit;
		}
		else{
			wf_admin_notice::add_notice(__('Please select atleast one order', 'ups-woocommerce-shipping'));
		}
	}else if($action == 'ups_void_shipment'){
		if(isset($_REQUEST['post']) && is_array($_REQUEST['post'])){
			foreach($_REQUEST['post'] as $order_id){					
				$this->ups_void_shipment($order_id);				
			}
			wp_redirect( admin_url( '/edit.php?post_type=shop_order') );
			exit;
		}
		else{
			wf_admin_notice::add_notice(__('Please select atleast one order', 'ups-woocommerce-shipping'));
		}
	}
	// Bulk label print in PDF format
	elseif( $action == "xa_ups_print_label_pdf") {
		if( is_array($_REQUEST['post']) ) {
			$ups_labels = $this->print_labels_in_bulk($_REQUEST['post']);
			$this->print_bulk_labels_as_pdf( $ups_labels );
		}
	}
	// Bulk label print as IMAGE
	elseif( $action == "xa_ups_print_label_image") {
		if( is_array($_REQUEST['post']) ) {
			$ups_labels = $this->print_labels_in_bulk($_REQUEST['post']);
			$this->print_labels_in_bulk_as_image($ups_labels);
		}
	}

}

	/**
	 * Print Labels in Bulk .
	 * @param $order_ids array Array of Order Ids.
	 */
	protected function print_labels_in_bulk( $order_ids ) {
		$count 			= null;
		foreach( $order_ids as $order_id ) {

			$ups_labels_arr = get_post_meta($order_id, 'ups_label_details_array', true);
			// If label is available then only proceed further
			if( is_array($ups_labels_arr) ) {
				foreach( $ups_labels_arr as $ups_labels ) {
					foreach( $ups_labels as $ups_label ) {
						if( strtolower($ups_label['Code']) != 'epl') {
							if( strtolower($ups_label['Code']) == 'zpl') {
								$zpl_label = base64_decode(chunk_split($ups_label['GraphicImage']));
						        // By default zpl code returned by UPS has ^POI command, which will invert the label because
						        // of some reason. Removing it so that label will not be inverted.
								$zpl_label_inverted = str_replace( "^POI", "", $zpl_label);

								$response 		= wp_remote_post( "http://api.labelary.com/v1/printers/8dpmm/labels/4x8/0/",
									array(
										'timeout'   => 70,
										'sslverify' => $this->ssl_verify,
										'body'      => $zpl_label_inverted
									)
								);

								// 250000 Microseconds - 0.25 Seconds. Hit 4 API Requests per Second.
								// Labelary API has a restriction of 5 requests per second per client. 
								usleep(250000);

								$final_image 				= $response["body"];		// In PNG format already decoded
							}
							else {
								$final_image = base64_decode(chunk_split($ups_label['GraphicImage']));
							}

							$all_ups_labels[] = array(
								'order_id'	=> $order_id,
								'type'	=>	strtolower($ups_label['Code']),
								'label'	=>	$final_image,
							);
							$count++;
						}
					}
				}
			}
		}

		return !empty($all_ups_labels) ? $all_ups_labels : array();
	}

	/**
	 * Bulk label print in PDF format. Supports PNG, GIF, ZPL.
	 * @param $labels array Array of UPS labels.
	 */
	function print_bulk_labels_as_pdf( $labels ) {

		if ( !class_exists( 'FPDF' ) ) {
			include_once( 'FPDF/fpdf.php' );
		}

		$count 	= 0;
		$pdf 	= new FPDF();
		$path 	= wp_upload_dir();

		if( ! empty($labels) ) {

			$failed_labels = [];

			foreach( $labels as $label ) {
				$file = $path['path']."/ups_bulk_image_$count.png";		// Can't use same name FPDF limitation of same name
				if( $label['type'] == 'gif' ) {
					$file = $path['path']."/ups_bulk_image_$count.gif";
					header('Content-type: image/jpeg');
					$label = imagecreatefromstring($label['label']);
					$label = imagerotate($label, -90, 0);
					imagegif($label, $path['path']."/ups_bulk_image_$count.gif");
					$file = $path['path']."/ups_bulk_image_$count.gif";
				}
				else if( $label['type'] == 'png' ) {
					$file = $path['path']."/ups_bulk_image_$count.png";
					header('Content-type: image/png');
					$label = imagecreatefromstring($label['label']);
					$label = imagerotate($label, -90, 0);
					imagepng($label, $path['path']."/ups_bulk_image_$count.png");
					$file = $path['path']."/ups_bulk_image_$count.png";
				}
				else{
					file_put_contents($file, $label['label']);
				}
				
				try{
					$pdf->AddPage();
					$pdf->Image($file,0,0,0,0);
					$count++;
				}catch(Exception $e){
					$failed_labels[] = $label;
				}

				unlink($file);
			}
			
			if( isset($failed_labels) && !empty($failed_labels) ){
				foreach( $failed_labels as $label ) {
					$file = $path['path']."/ups_bulk_image_$count.png";		// Can't use same name FPDF limitation of same name
					if( $label['type'] == 'gif' ) {
						$file = $path['path']."/ups_bulk_image_$count.gif";
						header('Content-type: image/jpeg');
						$label = imagecreatefromstring($label['label']);
						$label = imagerotate($label, -90, 0);
						imagegif($label, $path['path']."/ups_bulk_image_$count.gif");
						$file = $path['path']."/ups_bulk_image_$count.gif";
					}
					else if( $label['type'] == 'png' ) {
						$file = $path['path']."/ups_bulk_image_$count.png";
						header('Content-type: image/png');
						$label = imagecreatefromstring($label['label']);
						$label = imagerotate($label, -90, 0);
						imagepng($label, $path['path']."/ups_bulk_image_$count.png");
						$file = $path['path']."/ups_bulk_image_$count.png";
					}
					else{
						file_put_contents($file, $label['label']);
					}

					try{
						$pdf->AddPage();
						$pdf->Image($file,0,0,0,0);
						$count++;
					}catch(Exception $e){
						wf_admin_notice::add_notice('Order #'. $label['order_id'].': Sorry. Something went wrong: '.$e->getMessage());		
					}

					unlink($file);
				}
			}
			$pdf->Output();
			die;
		}
	}

	/**
	 * Bulk label print in PNG format. Supports only PNG, GIF Format.
	 * @param $ups_labels array Array of shipping labels
	 */
	protected function print_labels_in_bulk_as_image( $shipping_labels ) {				

		$ups_settings 				= get_option( 'woocommerce_'.WF_UPS_ID.'_settings', null ); 
		$label_in_browser_zoom 		= isset( $ups_settings['label_in_browser_zoom'] ) && !empty( $ups_settings['label_in_browser_zoom'] ) ? $ups_settings['label_in_browser_zoom'] : '90';
		$rotate_label 				= isset( $ups_settings['rotate_label'] ) && !empty( $ups_settings['rotate_label'] ) ? $ups_settings['rotate_label'] : 'no';	

		if( ! empty($shipping_labels) ) {

			echo "<html>
					<body style='margin: 0; display: flex; flex-direction: column; justify-content: center;'>
						<div style='text-align: center;'>";
			
						foreach ($shipping_labels as $key => $label) {

							if( $label['type'] == 'gif' ) {

								$source 		= imagecreatefromstring($label['label']);
								
								if($rotate_label =='yes') {
									$final_image 	= imagerotate($source, 0, 0);
								} else {
									$final_image 	= imagerotate($source, -90, 0);
								}

								ob_start();
								imagegif($final_image);
								$contents =  ob_get_contents();
								ob_end_clean();

							} else if ( $label['type'] == 'png' ) {

								$source 		= imagecreatefromstring($label['label']);
								
								if($rotate_label =='yes') {
									$final_image 	= imagerotate($source, 0, 0);
								} else {
									$final_image 	= imagerotate($source, -90, 0);
								}

								ob_start();
								imagepng($final_image);
								$contents =  ob_get_contents();
								ob_end_clean();

							} else {

								$contents =  $label['label'];
							}

							echo "<div>";

								echo "<img style='max-width: ".$label_in_browser_zoom."%;' src='data:image/png;base64,". base64_encode($contents). "'/>";

							echo "</div>";
						}

			echo "		</div>
					</body>
				 </html>";

			exit();
		}
	}

function ups_void_shipment($order_id){

	$ups_label_details_array	=	$this->get_order_label_details($order_id);
	if(!$ups_label_details_array){
		wf_admin_notice::add_notice('Order #'. $order_id.': Shipment is not available.');			
		return false;
	}

		// Load UPS Settings.
	$ups_settings 				= get_option( 'woocommerce_'.WF_UPS_ID.'_settings', null ); 
		// API Settings
	$api_mode		      		= isset( $ups_settings['api_mode'] ) ? $ups_settings['api_mode'] : 'Test';
	$ups_user_id         		= isset( $ups_settings['user_id'] ) ? $ups_settings['user_id'] : '';
	$ups_password        		= isset( $ups_settings['password'] ) ? $ups_settings['password'] : '';
	$ups_access_key      		= isset( $ups_settings['access_key'] ) ? $ups_settings['access_key'] : '';
	$ups_shipper_number  		= isset( $ups_settings['shipper_number'] ) ? $ups_settings['shipper_number'] : '';

	if( "Live" == $api_mode ) {
		$endpoint = 'https://onlinetools.ups.com/ups.app/xml/Void';
	}
	else {
		$endpoint = 'https://wwwcie.ups.com/ups.app/xml/Void';
	}

	foreach($ups_label_details_array as $shipmentId => $ups_label_detail_arr){
		$contextvalue               =apply_filters( 'ph_ups_update_customer_context_value',$order_id);

		$xml_request = '<?xml version="1.0" ?>';
		$xml_request .= '<AccessRequest xml:lang="en-US">';
		$xml_request .= '<AccessLicenseNumber>'.$ups_access_key.'</AccessLicenseNumber>';
		$xml_request .= '<UserId>'.$ups_user_id.'</UserId>';
		$xml_request .= '<Password>'.$ups_password.'</Password>';
		$xml_request .= '</AccessRequest>';
		$xml_request .= '<?xml version="1.0" encoding="UTF-8" ?>';
		$xml_request .= '<VoidShipmentRequest>';
		$xml_request .= '<Request>';
		$xml_request .= '<TransactionReference>';
		$xml_request .= '<CustomerContext>'.$contextvalue.'</CustomerContext>';
		$xml_request .= '<XpciVersion>1.0001</XpciVersion>';
		$xml_request .= '</TransactionReference>';
		$xml_request .= '<RequestAction>Void</RequestAction>';
		$xml_request .= '<RequestOption />';
		$xml_request .= '</Request>';
		$xml_request .= '<ExpandedVoidShipment>';
		$xml_request .= '<ShipmentIdentificationNumber>'.$shipmentId.'</ShipmentIdentificationNumber>';
		foreach ( $ups_label_detail_arr as $ups_label_details ) {
			$xml_request .= '<TrackingNumber>'.$ups_label_details["TrackingNumber"].'</TrackingNumber>';
		}
		$xml_request .= '</ExpandedVoidShipment>';
		$xml_request .= '</VoidShipmentRequest>';
		$xml_request = $this->modfiy_encoding($xml_request);
		$response = wp_remote_post( $endpoint,
			array(
				'timeout'   => 70,
				'sslverify' => $this->ssl_verify,
				'body'      => $xml_request
			)
		);

			// In case of any issues with remote post.
		if ( is_wp_error( $response ) ) {
			wf_admin_notice::add_notice('Order #'. $order_id.': Sorry. Something went wrong: '.$error_message);
			continue;
		}

		$response_obj 	= simplexml_load_string( '<root>' . preg_replace('/<\?xml.*\?>/','', $response['body'] ) . '</root>' );
		$response_code 	= (string)$response_obj->VoidShipmentResponse->Response->ResponseStatusCode;

			// It is an error response.
		if( '0' == $response_code ) {
			$error_code = (string)$response_obj->VoidShipmentResponse->Response->Error->ErrorCode;
			$error_desc = (string)$response_obj->VoidShipmentResponse->Response->Error->ErrorDescription;

			$message = '<strong>'.$error_desc.' [Error Code: '.$error_code.']'.'. </strong>';


			$void_shipment_url = admin_url( '/?wf_ups_void_shipment='.base64_encode( $order_id ).'&client_reset');
			$message .= 'Please contact UPS to void/cancel this shipment. <br/>';

				// For bulk void shipment we are clearing the data autometically

			$message .= 'If you have already cancelled this shipment by calling UPS customer care, and you would like to create shipment again then click <a class="button button-primary tips" href="'.$void_shipment_url.'" data-tip="Client Side Reset">Client Side Reset</a>';
			$message .= '<p style="color:red"><strong>Note: </strong>Previous shipment details and label will be removed from Order page.</p>';

			if( "Test" == $api_mode ) {
				$message .= "<strong>Also, noticed that you have enabled 'Test' mode.<br/>Please note that void is not possible in 'Test' mode, as there is no real shipment is created with UPS. </strong><br/>";
			}

			wf_admin_notice::add_notice('Order #'. $order_id.': '.$message);
			return false;
		}

		$this->wf_ups_void_return_shipment($order_id,$shipmentId);
	}

	delete_post_meta( $order_id, 'ups_created_shipments_details_array');
	delete_post_meta( $order_id, 'ups_label_details_array');
	delete_post_meta( $order_id, 'ups_commercial_invoice_details' );
	delete_post_meta( $order_id, 'ups_dangerous_goods_image' );//PDS-129
	delete_post_meta( $order_id, 'wf_ups_selected_service');

	wf_admin_notice::add_notice('Order #'. $order_id.': Cancellation of shipment completed successfully. You can re-initiate shipment.','notice');
	return true;
}

function get_order_label_details($order_id){
	$ups_label_details_array	=	get_post_meta( $order_id, 'ups_label_details_array', true );
	if(!empty($ups_label_details_array) && is_array($ups_label_details_array)){
		return $ups_label_details_array;
	}
	return false;
}

function ups_confirm_shipment($order_id){

	// Check if shipment created already
	if($this->get_order_label_details($order_id)){
		wf_admin_notice::add_notice('Order #'. $order_id.': Shipment is already created.','warning');			
		return false;
	}

	// Load UPS Settings.
	$ups_settings 		= 	get_option( 'woocommerce_'.WF_UPS_ID.'_settings', null ); 
	// API Settings
	$api_mode      		= 	isset( $ups_settings['api_mode'] ) ? $ups_settings['api_mode'] : 'Test';

	$endpoints	=	array(
		'Live'	=>	'https://onlinetools.ups.com/ups.app/xml/ShipConfirm',
		'Test'	=>	'https://wwwcie.ups.com/ups.app/xml/ShipConfirm',
	);

	$freight_endpoints	=	array(
		'Live'	=>	'https://onlinetools.ups.com/rest/FreightShip',
		'Test'	=>	'https://wwwcie.ups.com/rest/FreightShip',
	);

	$endpoint	=	$endpoints[$api_mode];
	$freight_endpoint=$freight_endpoints[$api_mode];
	$order		=	$this->wf_load_order( $order_id );
	$requests 	= 	$this->wf_ups_shipment_confirmrequest($order);

	$created_shipments_details_array = array();

	foreach($requests as $request) {

		$xml_request = str_replace( array( "\n", "\r" ), '', $request );

		if ( !is_array($request) && json_decode( $request ) !== null ) {

			$response = wp_remote_post( $freight_endpoint,
				array(
					'timeout'   => 70,
					'sslverify' => $this->ssl_verify,
					'body'      => $xml_request
				)
			);

		} else {

			$xml_request = $this->modfiy_encoding($xml_request);

			$response = wp_remote_post( $endpoint,
				array(
					'timeout'   => 70,
					'sslverify' => $this->ssl_verify,
					'body'      => $xml_request
				)
			);
		}
		
		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			wf_admin_notice::add_notice('Order #'. $order_id.': Sorry. Something went wrong: '.$error_message);			
			return false;
		}
		$req_arr=array();
		if(!is_array($request))
		{
			$req_arr=json_decode($request);
		}
		if(!is_array($request) && isset($req_arr->FreightShipRequest) && isset($req_arr->FreightShipRequest->Shipment->Service->Code)
			&& in_array($req_arr->FreightShipRequest->Shipment->Service->Code,array_keys($this->freight_services))
			   )				// For Freight Shipments  as it is JSON not Array
			{	try{
				$var=json_decode($response['body']);
				$pdf=$var->FreightShipResponse->ShipmentResults->Documents->Image->GraphicImage;

			}
			catch(Exception $e)
			{
				$this->wf_redirect( admin_url( '/post.php?post='.$post_id.'&action=edit') );
				exit;
			}
			$created_shipments_details = array();
			$shipment_id = (string)$var->FreightShipResponse->ShipmentResults->ShipmentNumber;

			$created_shipments_details["ShipmentDigest"] 	= (string)$var->FreightShipResponse->ShipmentResults->ShipmentNumber;
			$created_shipments_details["BOLID"] 			= (string)$var->FreightShipResponse->ShipmentResults->BOLID;
			$created_shipments_details["type"] 				= "freight";

			try{
				$img=(string)$var->FreightShipResponse->ShipmentResults->Documents->Image->GraphicImage;
			}catch(Exception $ex){$img='';}

			$created_shipments_details_array[$shipment_id] = $created_shipments_details;

			$this->wf_ups_freight_accept_shipment($img,$shipment_id,$created_shipments_details["BOLID"],$order_id);

		} else {

			$response_obj = simplexml_load_string( $response['body'] );

			$response_code = (string)$response_obj->Response->ResponseStatusCode;
			if( '0' == $response_code ) {
				$error_code = (string)$response_obj->Response->Error->ErrorCode;
				$error_desc = (string)$response_obj->Response->Error->ErrorDescription;


				wf_admin_notice::add_notice('Order #'. $order_id.': '.$error_desc.' [Error Code: '.$error_code.']');
				return false;
			}

			$created_shipments_details = array();
			$shipment_id = (string)$response_obj->ShipmentIdentificationNumber;

			$created_shipments_details["ShipmentDigest"] 			= (string)$response_obj->ShipmentDigest;

			$created_shipments_details_array[$shipment_id] = $created_shipments_details;
		}
	}
	update_post_meta( $order_id, 'ups_created_shipments_details_array', $created_shipments_details_array );	
	return true;
}

function ups_accept_shipment($order_id){
	$created_shipments_details_array	= get_post_meta($order_id, 'ups_created_shipments_details_array', true);
	if(empty($created_shipments_details_array) && !is_array($created_shipments_details_array)){
		return false;
	}				

		// Load UPS Settings.
	$ups_settings 				= get_option( 'woocommerce_'.WF_UPS_ID.'_settings', null ); 
		// API Settings
	$api_mode      				= isset( $ups_settings['api_mode'] ) ? $ups_settings['api_mode'] : 'Test';
	$ups_user_id         		= isset( $ups_settings['user_id'] ) ? $ups_settings['user_id'] : '';
	$ups_password        		= isset( $ups_settings['password'] ) ? $ups_settings['password'] : '';
	$ups_access_key      		= isset( $ups_settings['access_key'] ) ? $ups_settings['access_key'] : '';
	$ups_shipper_number  		= isset( $ups_settings['shipper_number'] ) ? $ups_settings['shipper_number'] : '';
	$disble_shipment_tracking	= isset( $ups_settings['disble_shipment_tracking'] ) ? $ups_settings['disble_shipment_tracking'] : 'TrueForCustomer';
	$debug_mode      	        = isset( $ups_settings['debug'] ) && $ups_settings['debug'] == 'yes' ? true : false;


	$endpoints			=	array(
		'Live'				=>	'https://onlinetools.ups.com/ups.app/xml/ShipAccept',
		'Test'				=>	'https://wwwcie.ups.com/ups.app/xml/ShipAccept',
	);
	$ups_label_details_array = array();
	$endpoint	=	$endpoints[$api_mode];
	foreach($created_shipments_details_array as $shipment_id	=>	$created_shipments_details){
		if(isset($created_shipments_details['type']) && $created_shipments_details['type']=='freight'){
			continue;
		}		
		if(isset($created_shipments_details['GFP']) && $created_shipments_details['GFP'])
		{	
			$contextvalue               =apply_filters( 'ph_ups_update_customer_context_value',$order_id);
			$xml_request = '<ShipmentAcceptRequest>';
			$xml_request .= '<Request>';
			$xml_request .= '<TransactionReference>';
			$xml_request .= '<CustomerContext>'.$contextvalue.'</CustomerContext>';
			$xml_request .= '<XpciVersion>1.0001</XpciVersion>';
			$xml_request .= '</TransactionReference>';
			$xml_request .= '<RequestAction>ShipAccept</RequestAction>';
			$xml_request .= '</Request>';
			$xml_request .= '<ShipmentDigest>'.$created_shipments_details["ShipmentDigest"].'</ShipmentDigest>';
			$xml_request .= '</ShipmentAcceptRequest>';

			$xml_request	= apply_filters( 'xa_ups_accept_shipment_xml_request', $xml_request, $shipment_id, $order_id );	// To support vendor addon
			
			$xml_request = $this->modfiy_encoding($xml_request);

			$ups_settings 				= get_option( 'woocommerce_'.WF_UPS_ID.'_settings', null ); 
			$ups_user_id         		= isset( $ups_settings['user_id'] ) ? $ups_settings['user_id'] : '';
			$ups_password        		= isset( $ups_settings['password'] ) ? $ups_settings['password'] : '';
			$ups_access_key      		= isset( $ups_settings['access_key'] ) ? $ups_settings['access_key'] : '';
    		$header=new stdClass();
    		$header->UsernameToken=new stdClass();
			$header->UsernameToken->Username=$ups_settings['user_id'];
			$header->UsernameToken->Password=$ups_settings['password'];
    		$header->ServiceAccessToken=new stdClass();
			$header->ServiceAccessToken->AccessLicenseNumber=$ups_settings['access_key'];
			$client = $this->wf_create_soap_client( plugin_dir_path( dirname( __FILE__ ) ) . 'wsdl/'.$api_mode.'/shipment/Ship.wsdl', array(
						'trace' =>	true,
						'cache_wsdl' => 0
						) );
			$authvalues = new SoapVar($header, SOAP_ENC_OBJECT);
			$header = new SoapHeader('http://www.ups.com/XMLSchema/XOLTWS/UPSS/v1.0','UPSSecurity',$header,false);
			
			$client->__setSoapHeaders($header);

			$client->__setSoapHeaders($header);
			$xml_request = simplexml_load_string($xml_request);
			$response=array();
			$error_message='';
			try {

			   	$response = $client->ProcessShipAccept( $xml_request );


			} catch (\SoapFault $fault) {
				$error_message=$fault->faultstring;
			    //trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);

			}
			if( $debug_mode ) {
				echo '<div style="background: #eee;overflow: auto;padding: 10px;margin: 10px;">SHIPMENT ACCEPT REQUEST: ';
				echo '<xmp>'.$client->__getLastRequest().'</xmp></div>'; 
				echo '<div style="background:#ccc;background: #ccc;overflow: auto;padding: 10px;margin: 10px 10px 50px 10px;">SHIPMENT ACCEPT RESPONSE: ';
				echo '<xmp>'.print_r($client->__getLastResponse(),1).'</xmp></div>'; 
				$log = wc_get_logger();
				$log->debug( print_r( __('------------------------UPS GFP Accept Request -------------------------------', 'ups-woocommerce-shipping').PHP_EOL.PHP_EOL.htmlspecialchars($client->__getLastRequest()).PHP_EOL.PHP_EOL,true), array('source' => 'PluginHive-UPS-Plugin GFP accept'));
				$log->debug( print_r( __('------------------------UPS GFP Accept Response -------------------------------', 'ups-woocommerce-shipping').PHP_EOL.PHP_EOL.htmlspecialchars($client->__getLastResponse()).PHP_EOL.PHP_EOL,true), array('source' => 'PluginHive-UPS-Plugin GFP accept'));

				$this->admin_diagnostic_report( '------------------------ UPS GFP ACCEPT SHIPMENT REQUEST ------------------------' );
				$this->admin_diagnostic_report( htmlspecialchars($client->__getLastRequest()) );

				$this->admin_diagnostic_report( '------------------------ UPS GFP ACCEPT SHIPMENT RESPONSE ------------------------' );
				$this->admin_diagnostic_report( htmlspecialchars($client->__getLastResponse()) );
			}


			if(!empty($response) && isset($response->Response) && isset($response->Response->ResponseStatus) && isset($response->Response->ResponseStatus->Description)  && $response->Response->ResponseStatus->Description=='Success' && $response->Response->ResponseStatus->Description=='Success' )
			{
				$index 				= 0;
				$shipment_id_cs 	= '';
				$package_results 	= $response->ShipmentResults->PackageResults;
				
				if( !empty($package_results) && is_array($package_results) )
				{
					foreach ($package_results as $key => $package_result) {

						$ups_label_details			= array();
						$ups_label_details["TrackingNumber"]		= (string)$package_result->TrackingNumber;
						$ups_label_details["Code"] 					= (string)$package_result->ShippingLabel->ImageFormat->Code;
						$ups_label_details["GraphicImage"] 			= (string)$package_result->ShippingLabel->GraphicImage;

						if( isset($package_result->ShippingLabel->HTMLImage) ) {
							$ups_label_details["HTMLImage"] 			= (string)$package_result->ShippingLabel->HTMLImage;
						}

						$ups_label_details["GFP"]					= true;
						$ups_label_details_array[$shipment_id][]	= $ups_label_details;
						$shipment_id_cs 							.= $ups_label_details["TrackingNumber"].',';

						do_action('wf_label_generated_successfully',$shipment_id,$order_id,$ups_label_details["Code"],(string)$index,$ups_label_details["TrackingNumber"], $ups_label_details );
					}

				} else {
					$ups_label_details			= array();
					$shipment_id_cs 			= '';
					$ups_label_details["TrackingNumber"]		= (string)$package_results->TrackingNumber;
					$ups_label_details["Code"] 					= (string)$package_results->ShippingLabel->ImageFormat->Code;
					$ups_label_details["GraphicImage"] 			= (string)$package_results->ShippingLabel->GraphicImage;

					if( isset($package_results->ShippingLabel->HTMLImage) ) {
						$ups_label_details["HTMLImage"] 			= (string)$package_results->ShippingLabel->HTMLImage;
					}

					$ups_label_details["GFP"]					= true;
					$ups_label_details_array[$shipment_id][]	= $ups_label_details;
					$shipment_id_cs 							.= $ups_label_details["TrackingNumber"].',';
					
					do_action('wf_label_generated_successfully',$shipment_id,$order_id,$ups_label_details["Code"],(string)$index,$ups_label_details["TrackingNumber"], $ups_label_details );
				}
			}
			else
			{
				wf_admin_notice::add_notice('Order #'. $order_id.': Sorry. Something went wrong: '.$error_message);
				return false;
			}


		}
		else
		{
			$contextvalue =apply_filters( 'ph_ups_update_customer_context_value',$order_id);
			$xml_request = '<?xml version="1.0" encoding="UTF-8" ?>';
			$xml_request .= '<AccessRequest xml:lang="en-US">';
			$xml_request .= '<AccessLicenseNumber>'.$ups_access_key.'</AccessLicenseNumber>';
			$xml_request .= '<UserId>'.$ups_user_id.'</UserId>';
			$xml_request .= '<Password>'.$ups_password.'</Password>';
			$xml_request .= '</AccessRequest>'; 
			$xml_request .= '<?xml version="1.0" ?>';
			$xml_request .= '<ShipmentAcceptRequest>';
			$xml_request .= '<Request>';
			$xml_request .= '<TransactionReference>';
			$xml_request .= '<CustomerContext>'.$contextvalue.'</CustomerContext>';
			$xml_request .= '<XpciVersion>1.0001</XpciVersion>';
			$xml_request .= '</TransactionReference>';
			$xml_request .= '<RequestAction>ShipAccept</RequestAction>';
			$xml_request .= '</Request>';
			$xml_request .= '<ShipmentDigest>'.$created_shipments_details["ShipmentDigest"].'</ShipmentDigest>';
			$xml_request .= '</ShipmentAcceptRequest>';

			$xml_request	= apply_filters( 'xa_ups_accept_shipment_xml_request', $xml_request, $shipment_id, $order_id );	// To support vendor addon
			
			$xml_request = $this->modfiy_encoding($xml_request);

			if( $debug_mode ) {
				echo '<div style="background: #eee;overflow: auto;padding: 10px;margin: 10px;">SHIPMENT ACCEPT REQUEST: ';
				echo '<xmp>'.$xml_request.'</xmp></div>';

				$this->admin_diagnostic_report( '------------------------ UPS ACCEPT SHIPMENT RESPONSE ------------------------' );
				$this->admin_diagnostic_report( $xml_request ); 
			}
			$response = wp_remote_post( $endpoint,
				array(
					'timeout'   => 70,
					'sslverify' => $this->ssl_verify,
					'body'      => $xml_request
				)
			);

			if( $debug_mode ) {
				echo '<div style="background:#ccc;background: #ccc;overflow: auto;padding: 10px;margin: 10px 10px 50px 10px;">SHIPMENT ACCEPT RESPONSE: ';
				echo '<xmp>'.print_r($response['body'],1).'</xmp></div>'; 

				$this->admin_diagnostic_report( '------------------------ UPS ACCEPT SHIPMENT RESPONSE ------------------------' );
				$this->admin_diagnostic_report( $response['body'] );
			}

			if ( is_wp_error( $response ) ) {
				$error_message = $response->get_error_message();
				wf_admin_notice::add_notice('Order #'. $order_id.': Sorry. Something went wrong: '.$error_message);
				return false;
			}

			$response_obj = simplexml_load_string( '<root>' . preg_replace('/<\?xml.*\?>/','', $response['body'] ) . '</root>' );	

			$response_code = (string)$response_obj->ShipmentAcceptResponse->Response->ResponseStatusCode;
			if('0' == $response_code) {
				$error_code = (string)$response_obj->ShipmentAcceptResponse->Response->Error->ErrorCode;
				$error_desc = (string)$response_obj->ShipmentAcceptResponse->Response->Error->ErrorDescription;

				$this->admin_diagnostic_report( '------------------------ UPS ACCEPT SHIPMENT ERROR ------------------------' );
				$this->admin_diagnostic_report( $error_desc );

				wf_admin_notice::add_notice($error_desc.' [Error Code: '.$error_code.']');
				return false;
			}

			$package_results 			= $response_obj->ShipmentAcceptResponse->ShipmentResults->PackageResults;
			$ups_label_details			= array();
			$shipment_id_cs 			= '';

			if(isset($response_obj->ShipmentAcceptResponse->ShipmentResults->Form->Image)){
				$international_forms[$shipment_id]	=	array(
					'ImageFormat'	=>	(string)$response_obj->ShipmentAcceptResponse->ShipmentResults->Form->Image->ImageFormat->Code,
					'GraphicImage'	=>	(string)$response_obj->ShipmentAcceptResponse->ShipmentResults->Form->Image->GraphicImage,
				);
			}

			//PDS-129
			if(isset($response_obj->ShipmentAcceptResponse->ShipmentResults) && isset($response_obj->ShipmentAcceptResponse->ShipmentResults->DGPaperImage)){ 

				$DGPaper_image[$shipment_id]	=	array(
					'DGPaperImage'	=>	(string)$response_obj->ShipmentAcceptResponse->ShipmentResults->DGPaperImage,
				);
			}

				// Labels for each package.
			$index=0;
			foreach ( $package_results as $package_result ) {

				$trackingNum 								= (string) $package_result->TrackingNumber;				
				$ups_label_details["TrackingNumber"]		= ( isset($package_result->USPSPICNumber) && ctype_digit( $trackingNum ) ) ? (string) $package_result->USPSPICNumber : $trackingNum;
				$ups_label_details["Code"] 					= (string)$package_result->LabelImage->LabelImageFormat->Code;
				$ups_label_details["GraphicImage"] 			= (string)$package_result->LabelImage->GraphicImage;
				if( ! empty($package_result->LabelImage->HTMLImage) ) {
					$ups_label_details["HTMLImage"] 			= (string)$package_result->LabelImage->HTMLImage;
				}
				$ups_label_details_array[$shipment_id][]	= $ups_label_details;
				$shipment_id_cs 							.= $ups_label_details["TrackingNumber"].',';
				do_action('wf_label_generated_successfully',$shipment_id,$order_id,$ups_label_details["Code"],(string)$index,$ups_label_details["TrackingNumber"], $ups_label_details );
				$index=$index+1;
			}
		}
		$shipment_id_cs = rtrim( $shipment_id_cs, ',' );

		if( empty($ups_label_details_array) ) {
			wf_admin_notice::add_notice('Order #'. $order_id.': Sorry, An unexpected error occurred.');
			return false;
		}
		else {
			
			$old_ups_label_details_array     = get_post_meta( $order_id, 'ups_label_details_array', true );
			if(empty($old_ups_label_details_array))
			{
				$old_ups_label_details_array=$ups_label_details_array;
			}
			else{
				foreach ($ups_label_details_array as $shipment_id => $ups_label_details) {
					$old_ups_label_details_array[$shipment_id]=$ups_label_details;
				}
			}
			update_post_meta( $order_id, 'ups_label_details_array', $old_ups_label_details_array );

			if( $this->dangerous_goods_manifest ) {
				
				$this->ph_create_dangerous_goods_manifest($order_id);
			}

			if(isset($international_forms)){
				update_post_meta( $order_id, 'ups_commercial_invoice_details', $international_forms );
			}

			//PDS-129
			if(isset($DGPaper_image)){
				update_post_meta( $order_id, 'ups_dangerous_goods_image', $DGPaper_image );
			}

			if( isset($created_shipments_details['return']) && $created_shipments_details['return'] ){// creating return label
				$return_label_ids = $this->wf_ups_return_shipment_accept($order_id, $created_shipments_details['return']);
				if( $return_label_ids && $shipment_id_cs ){
					$shipment_id_cs = $shipment_id_cs.','.$return_label_ids;
				}
			}
			}
			
			if(isset ( $response_obj->ShipmentAcceptResponse->ShipmentResults->ControlLogReceipt->ImageFormat->Code ) ){
				$control_log_image_format = $response_obj->ShipmentAcceptResponse->ShipmentResults->ControlLogReceipt->ImageFormat->Code;
				if( $control_log_image_format == "HTML") {
					$control_log_receipt[$shipment_id] = base64_decode( $response_obj->ShipmentAcceptResponse->ShipmentResults->ControlLogReceipt->GraphicImage );
					update_post_meta( $order_id, 'ups_control_log_receipt', $control_log_receipt );
				}
			}

			if( 'True' != $disble_shipment_tracking) {

				// To support UPS Integration with Shipment Tracking
				do_action('ph_ups_shipment_tracking_detail_ids',$shipment_id_cs, $order_id );

				// Update Tracking Info
				$ups_tarcking	=	new WF_Shipping_UPS_Tracking();
				$ups_tarcking->get_shipment_info( $order_id, $shipment_id_cs );
			}
			wf_admin_notice::add_notice('Order #'. $order_id.': Shipment accepted successfully. Labels are ready for printing.','notice');
			
		}
		return true;
	}

	public function ph_create_dangerous_goods_manifest( $order_id ) {

		$ups_label_details_array 			= get_post_meta( $order_id, 'ups_label_details_array', true );
		$packages 							= get_post_meta( $order_id, '_wf_ups_stored_packages', true );
		$created_shipments_details_array 	= get_post_meta( $order_id, 'ups_created_shipments_details_array', true );

		$hazmat_products = array();
		$hazmat_package  = array();

		if( !empty($ups_label_details_array) && is_array($ups_label_details_array) ) {
			
			foreach ( $created_shipments_details_array as $shipmentId => $created_shipments_details ) {

				$hazmat_products = array();

				if( !empty($ups_label_details_array[$shipmentId]) ) {

					foreach ( $ups_label_details_array[$shipmentId] as $ups_label_details ) {

						$tracking_number 	= isset( $ups_label_details["TrackingNumber"] ) ? $ups_label_details["TrackingNumber"] : '';

						if( is_array($packages) ) {
							
							$package = array_shift($packages);

							$first_item_in_package = ( isset($package['Package']['items']) && is_array($package['Package']['items']) ) ? current($package['Package']['items']) : null;

							if( !empty($first_item_in_package) ) {

								foreach( $package['Package']['items'] as $product) {

									$product_id 		= $product->get_id();
									$product_weight 	= wc_get_weight( $product->get_weight(), $this->weight_unit );
									$hazmat_product 	= 'no';
									$hazmat_product 	= get_post_meta($product_id,'_ph_ups_hazardous_materials',1);
									$hazmat_settings 	= get_post_meta($product_id,'_ph_ups_hazardous_settings',1);

									if( $hazmat_product == 'yes' && !empty($hazmat_settings) && is_array($hazmat_settings) ) {

										if( isset($hazmat_products[$product_id]) ) {
											
											$hazmat_products[$product_id]['quantity']++;

											if( $hazmat_products[$product_id]['trackingNumber'] != $tracking_number ) {

												$hazmat_products[$product_id]['trackingNumber'] 	.= ', '.$tracking_number;
											}
											continue;
										}

										$transportationmode = array(
											'01' => 'Highway',
											'02' => 'Ground',
											'03' => 'PAX',
											'04' => 'CAO',
										);

										if( isset($hazmat_settings['_ph_ups_hm_transportaion_mode']) && array_key_exists($hazmat_settings['_ph_ups_hm_transportaion_mode'], $transportationmode )) {
											$mode = $transportationmode[$hazmat_settings['_ph_ups_hm_transportaion_mode']];
										}

										$idNumber 				= !empty( $hazmat_settings['_ph_ups_commodity_id'] ) ? $hazmat_settings['_ph_ups_commodity_id'] : '';
										$properShippingName 	= !empty( $hazmat_settings['_ph_ups_shipping_name'] ) ? $hazmat_settings['_ph_ups_shipping_name'] : '';
										$classDivisionNumber 	= !empty( $hazmat_settings['_ph_ups_class_division_no'] ) ? $hazmat_settings['_ph_ups_class_division_no'] : '';
										$packagingGroupType 	= !empty( $hazmat_settings['_ph_ups_package_group_type'] ) ? $hazmat_settings['_ph_ups_package_group_type'] : '';
										$packagingType 			= !empty( $hazmat_settings['_ph_ups_package_type'] ) ? $hazmat_settings['_ph_ups_package_type'] : '';
										$regulationSet 			= !empty( $hazmat_settings['_ph_ups_hm_regulations'] ) ? $hazmat_settings['_ph_ups_hm_regulations'] : '';
										$transportationMode 	= $mode;
										$uom 					= ( $this->uom == 'LB' ) ? 'pound' : 'kg';
									
										$hazmat_products[$product_id] = array(
											'productName'			=> $product->get_name(),
											'productWeight'			=> $product_weight,
											'trackingNumber'		=> $tracking_number,
											'commodityId'			=> $idNumber,
											'properShippingName'	=> $properShippingName,
											'classDivisionNumber'	=> $classDivisionNumber,
											'packagingGroupType'	=> $packagingGroupType,
											'packagingType'			=> $packagingType,
											'regulationSet'			=> $regulationSet,
											'transportationMode'	=> $transportationMode,
											'uom'					=> $uom,
											'quantity' 				=> 1,
										);
									}
								}
							}
						}
					}
				}
				$hazmat_package[$shipmentId] = $hazmat_products ;
			}
		}

		if( !empty($hazmat_package) ) {

			update_post_meta( $order_id, 'ph_ups_dangerous_goods_manifest_required', true );
			update_post_meta( $order_id, 'ph_ups_dangerous_goods_manifest_data', $hazmat_package );
		}

		return;

	}
	
	function get_order_label_links($order_id){
		$links	=	array();
		$created_shipments_details_array 	= get_post_meta( $order_id, 'ups_created_shipments_details_array', true );
		if(!empty($created_shipments_details_array)){
			$ups_label_details_array = $this->get_order_label_details($order_id);
			$ups_commercial_invoice_details = get_post_meta( $order_id, 'ups_commercial_invoice_details', true );
			
			foreach($created_shipments_details_array as $shipmentId => $created_shipments_details){
				$index = 0;
				if( isset($ups_label_details_array[$shipmentId]) && is_array( $ups_label_details_array[$shipmentId] ) ){
					foreach ( $ups_label_details_array[$shipmentId] as $ups_label_details ) {
						$label_extn_code 	= $ups_label_details["Code"];
						$tracking_number 	= isset( $ups_label_details["TrackingNumber"] ) ? $ups_label_details["TrackingNumber"] : '';
						$links[] 			= admin_url( '/?wf_ups_print_label='.base64_encode( $shipmentId.'|'.$order_id.'|'.$label_extn_code.'|'.$index.'|'.$tracking_number ) );
						
						
						// Return Label Link
						if(isset($created_shipments_details['return'])&&!empty($created_shipments_details['return'])){
							$return_shipment_id=current(array_keys($created_shipments_details['return'])); // only one return label is considered now
							$ups_return_label_details_array = get_post_meta( $order_id, 'ups_return_label_details_array', true );
							if(is_array($ups_return_label_details_array)&&isset($ups_return_label_details_array[$return_shipment_id])){// check for return label accepted data
								$ups_return_label_details=$ups_return_label_details_array[$return_shipment_id];
								if(is_array($ups_return_label_details)){
									$ups_return_label_detail=current($ups_return_label_details);
									$label_index=0;// as we took only one label so index is zero
									$links[] = admin_url( '/?wf_ups_print_label='.base64_encode( $return_shipment_id.'|'.$order_id.'|'.$label_extn_code.'|'.$label_index.'|return' ) );
									
								}
							}
						}
						$index = $index + 1;
					}
				}
				
				if(isset($ups_commercial_invoice_details[$shipmentId])){
					$links[]	=	admin_url( '/?wf_ups_print_commercial_invoice='.base64_encode($order_id.'|'.$shipmentId));
				}
			}
		}
		return $links;
	}
	
	function label_printing_buttons($order){
		$order = $this->wf_load_order( $order );

		$actions	=	array();
		$labels	=	$this->get_order_label_links($order->get_id());
		$commercial_invoice_image_link 	= plugin_dir_url(__DIR__).'images/ups-commercial-invoice.png';
		$normal_label_image_link		= plugin_dir_url(__DIR__).'images/ups-logo-16x16.png';
		if(is_array($labels)){
			foreach($labels as $label_no => $label_link){
				$commercial_label = (strpos( $label_link, 'commercial' ) === false ) ? false : true;
				$actions['print_label'.$label_no]	=	array(
					'url'			=>	$label_link,
					'name'			=>	$commercial_label  ?  __( 'Print UPS Commercial Invoice', 'ups-woocommerce-shipping' ) : __( 'Print UPS Label', 'ups-woocommerce-shipping' ),
					'action'		=>	'wf-print-label',
					'image_link'	=>	$commercial_label ? $commercial_invoice_image_link : $normal_label_image_link
				);
			}
		}
		
		foreach ( $actions as $action ) {
			printf( '<a class="button tips %s" href="%s" data-tip="%s" target="_blank"><img class="wf-print-label-on-order-list-image" src="'.$action['image_link'].'"></a>', esc_attr( $action['action'] ), esc_url( $action['url'] ), esc_attr( $action['name'] ), esc_attr( $action['name'] ) );
		}
		
	}
	
	/*
	 * function to convert encoding of xml request
	 * 
	 * @ since 3.2.7
	 * @ access private
	 * @ param xmlrequest
	 * @ return xmlrequest
	 */
	private function modfiy_encoding($xmlrequest)
	{	
		$latin_encoded_xmlrequest = '';
		if($this->enable_latin_encoding) {
			foreach( $this->specific_character_encoding_html_reference as $char => $html_reference ){
				$xmlrequest= str_replace($char, $html_reference, $xmlrequest);
			}

			$xmlrequest = str_replace($this->cyrillic_characters, $this->latin_characters, $xmlrequest); // For Cyrillic characters

			$latin_encoded_xmlrequest = iconv('UTF-8', 'ISO-8859-5//TRANSLIT', $xmlrequest);
			if($latin_encoded_xmlrequest) {
				return str_replace("UTF-8","ISO-8859-5",$latin_encoded_xmlrequest);
			}
		}
		return $xmlrequest;
	}
	
	/**
	 * To calculate the shipping cost on order page.
	 */
	public function wf_ups_generate_packages_rates() {
		if( ! $this->wf_user_check() ) {
			echo "You don't have admin privileges to view this page.";
			exit;
		}
		
		$post_id				= base64_decode($_GET['wf_ups_generate_packages_rates']);
		$length_arr				= explode(',',$_GET['length']);
		$width_arr				= explode(',',$_GET['width']);
		$height_arr				= explode(',',$_GET['height']);
		$weight_arr				= explode(',',$_GET['weight']);
		$insurance_arr			= explode(',',$_GET['insurance']);
		$get_stored_packages	= get_post_meta( $post_id, '_wf_ups_stored_packages', true );

		if ( !isset($get_stored_packages[0]) ) {
			  $get_stored_packages = array($get_stored_packages);
		   }


		if( isset($_GET["package_key"]) ) {
	    	$package_indexes	= json_decode(stripslashes(html_entity_decode($_GET["package_key"])));
	    	if(!empty($package_indexes) && is_array($package_indexes))
	    	{
	    		$final_packages=[];
		    	foreach( $package_indexes as $packages_index ) {
		    		if(isset($get_stored_packages[$packages_index]))
		    		{		
						$final_packages[]=$get_stored_packages[$packages_index];
		    		}
				}
				$get_stored_packages = $final_packages;
				update_post_meta( $post_id, '_wf_ups_stored_packages', $get_stored_packages );	// Update the packages in database
	    	}
		}
		$package_data			= $get_stored_packages;
		$rates 					= array();
		
		$no_of_package_entered	=	count($weight_arr);
		$no_of_packages			=	count($get_stored_packages);
		
		// Populate extra packages, if entered manual values
		if($no_of_package_entered > $no_of_packages){ 
			$package_clone	=	current($get_stored_packages); //get first package to clone default data
			for($i=$no_of_packages; $i<$no_of_package_entered; $i++){
				$get_stored_packages[$i]	=	array(
					'Package'	=>	array(
						'PackagingType'	=>	array(
							'Code'	=>	'02',
							'Description'	=>	'Package/customer supplied',
						),
						'Description'	=>	'Rate',
						'PackageWeight'	=>	array(
							'UnitOfMeasurement'	=>	array(
								'Code'	=>	$package_clone['Package']['PackageWeight']['UnitOfMeasurement']['Code'],
							),
						),
					),
				);
			}
		}

		$shipping_obj	    	= new WF_Shipping_UPS();
		$freight_ups_obj 		= new wf_freight_ups($this);
		$order		    		= wc_get_order($post_id);
		$shipping_address		= $order->get_address('shipping');
		$contents_cost			= null;
		$product_quantity 		= array();
		$content_quantity 		= array();

		// To support Mix and Match Product
		do_action( 'ph_ups_before_get_items_from_order', $order );

		$order_items 			= $order->get_items();

		// Get Contents Cost from Order
		foreach( $order_items as $order_item ) {

			$product_quantity['quantity']	= $order_item->get_quantity();
			$content_quantity[] = $product_quantity;

			$product = $this->get_product_from_order_item($order_item);

			if( is_a( $product, 'WC_Product') && $product->needs_shipping() ) {
				$contents_cost += (double) ( $product->get_price() * $product_quantity['quantity'] );
			}
		}

		// To support Mix and Match Product
		do_action( 'ph_ups_after_get_items_from_order', $order );

		$address_package    = array(
			'contents_cost'	=> $contents_cost,
			'destination'	=> array(
				'address'	=>	$shipping_address['address_1'].' '.$shipping_address['address_2'],
				'country'	=>	$shipping_address['country'],
				'state'		=>	$shipping_address['state'],
				'postcode'	=>	$shipping_address['postcode'],
				'city'		=>	$shipping_address['city'],

			),
		);

		foreach ($get_stored_packages as $package_key => $package) {

			if (!empty($package)) {

				foreach ($package as $key => $value) {

					$package_data[$package_key][$key] = array(

						'PackagingType'	=>	array(
							'Code'	=>	'02',
							'Description'	=>	'Package/customer supplied',
						),
						'Description'	=>	'Rate',
						'PackageWeight'	=>	array(
							'UnitOfMeasurement'	=>	array(
								'Code'	=>	$shipping_obj->weight_unit,
							),
						),
					);

					if( ! empty($weight_arr[$package_key] ) ) {

						$package_data[$package_key][$key]['PackageWeight']['Weight']			= $weight_arr[$package_key];
						$package_data[$package_key][$key]['PackageWeight']['UnitOfMeasurement']['Code']	= $shipping_obj->weight_unit;

					} else {
						wf_admin_notice::add_notice( sprintf( __( 'UPS rate request failed - Weight is missing. Aborting.', 'ups-woocommerce-shipping' ) ), 'error' );
						// Redirect to same order page
						wp_redirect( admin_url( '/post.php?post='.$post_id.'&action=edit') );
						exit;	    //To stay on same order page
					}

					if( ! empty($length_arr[$package_key]) && ! empty($width_arr[$package_key]) && ! empty($height_arr[$package_key]) ) {

						$package_data[$package_key][$key]['Dimensions'] = array(

							'UnitOfMeasurement'	=> array( 'Code' => $shipping_obj->dim_unit ),
							'Length'		=>  $length_arr[$package_key],
							'Width'			=>  $width_arr[$package_key],
							'Height'		=>  $height_arr[$package_key],
						);

					} else {
						unset($package_data[$package_key][$key]['Dimensions']);
					}
					
					if( ! empty($insurance_arr[$package_key]) ) {
						$package_data[$package_key][$key]['PackageServiceOptions']['InsuredValue'] = array(
							'CurrencyCode'	=>  $shipping_obj->get_ups_currency(),
							'MonetaryValue'	=>  $insurance_arr[$package_key],
						);
					}

					if( isset($value['PackageServiceOptions']) && isset($value['PackageServiceOptions']['DeliveryConfirmation']) ) {

						$package_data[$package_key][$key]['PackageServiceOptions']['DeliveryConfirmation'] = $value['PackageServiceOptions']['DeliveryConfirmation'];
					}

					if( isset($value['items']) && !empty($value['items']) ) {

						$package_data[$package_key][$key]['items'] = $value['items'];
					}

				}
			}
		}
		
		if( $get_stored_packages != $package_data) {
			update_post_meta( $post_id, '_wf_ups_stored_packages', $package_data );	// Update the packages in database
		}

		if( $this->address_validation && in_array( $address_package['destination']['country'], array( 'US', 'PR' ) ) ) {

			if( ! class_exists('Ph_Ups_Address_Validation') ) {
				
				require_once 'class-ph-ups-address-validation.php';
			}

			$ups_settings 				= get_option( 'woocommerce_'.WF_UPS_ID.'_settings', null ); 
			$Ph_Ups_Address_Validation 	= new Ph_Ups_Address_Validation( $address_package['destination'], $ups_settings );
			$residential_code			= $Ph_Ups_Address_Validation->residential_check;

			if( $residential_code == 2 ) {
				$shipping_obj->residential 	= true;
				$this->residential 			= true;
			}
		}

		$rate_request = $shipping_obj->get_rate_requests( $package_data, $address_package );
		$rates =  $shipping_obj->process_result( $shipping_obj->get_result($rate_request) );

		$custom_services = $shipping_obj->custom_services;

		// Get rates for surepost services only
		foreach ( $this->ups_surepost_services as $service_code ) {
			if( ! empty($custom_services[$service_code]['enabled']) ) {			// If surepost service code enabled
				$rate_requests	= $shipping_obj->get_rate_requests( $package_data, $address_package, 'surepost', $service_code );
				$rates			= array_merge( $rates, $shipping_obj->process_result( $shipping_obj->get_result($rate_requests, 'surepost') ) );
			}
		}

		// Get rates for Freight services
		if( $this->enable_freight )
		{
			foreach ($this->freight_services as $service_code => $value) {
				if( ! empty($this->settings['services'][$service_code]['enabled']) ) {

					$freight_rate_requests = $freight_ups_obj->get_rate_request( $address_package, $service_code, $package_data );

					$rates = array_merge( $rates, $shipping_obj->process_result( $shipping_obj->get_result($freight_rate_requests, 'freight'), 'json' ) );
				}
			}
		}

		// Get rates for GFP
		if( isset($shipping_obj->settings['services']['US48']['enabled']) && $shipping_obj->settings['services']['US48']['enabled'] ) {
			
			// Add quantity array to contents, required for GFP
			$address_package['contents'] = $content_quantity;

			$rate_requests	= $shipping_obj->get_rate_requests_gfp( $package_data, $address_package );
			$rates			= array_merge( $rates, $shipping_obj->process_result_gfp( $shipping_obj->get_result_gfp($rate_requests,'UPS GFP')) );
		}

		update_post_meta( $post_id, 'wf_ups_generate_packages_rates_response', $rates );
		// Redirect to same order page
		wp_redirect( admin_url( '/post.php?post='.$post_id.'&action=edit#CyDUPS_metabox') );
		exit;	    //To stay on same order page
	}

	/**
	*  Generate return label if label has been created previously
	*/
	public function xa_generate_return_label(){
		
		$order_id 				= $_GET['xa_generate_return_label'];
		$return_package_index 	= 0;
		$order 					= $this->wf_load_order($order_id);
		$shipment_id_cs 		= $this->xa_get_meta_key( $order, 'ups_shipment_ids', true, 'order');
		$shipments 				= $this->xa_get_meta_key( $order, 'ups_created_shipments_details_array', true, 'order');

		// Confirm return shipment
		foreach( $shipments as $shipment_id => $shipment ) {
			$return_label = $this->wf_ups_return_shipment_confirm($shipment_id,$return_package_index);
			if( !empty($return_label) ){
				$created_shipments_details_array[$shipment_id]['return'] = $return_label;
			}
			$return_package_index++;
		}
		update_post_meta( $order_id, 'ups_created_shipments_details_array', $created_shipments_details_array );

		// Accept Return Shipment
		foreach( $created_shipments_details_array as $shipment_id => $created_shipments_details ) {
			if( ! empty($created_shipments_details['return']) ) {
				$return_label_ids = $this->wf_ups_return_shipment_accept( $order_id, $created_shipments_details['return'] );
				if( $return_label_ids ) {
					$shipment_id_cs = $shipment_id_cs.','.$return_label_ids;
				}
			}
		}

		// To support UPS Integration with Shipment Tracking
		do_action('ph_ups_shipment_tracking_detail_ids',$shipment_id_cs, $order_id );
		
		// Update tracking info
		$ups_tarcking	=	new WF_Shipping_UPS_Tracking();
		$ups_tarcking->get_shipment_info( $order_id, $shipment_id_cs );
		if( $this->debug ) {
			exit();
		}
		wp_redirect( admin_url( '/post.php?post='.$order_id.'&action=edit#CyDUPS_metabox') );
	}

	// Check for any Product has Origin Address Preference, If 'yes' use Origin Address irrespective of Product and Settings
	public function get_product_address_preference( $order, $ups_settings, $return_label = false ) {

		$billing_address 	= true;

		if( $order instanceof WC_Order ) {

			// To support Mix and Match Product
			do_action( 'ph_ups_before_get_items_from_order', $order );

			$order_items = $order->get_items();

			if( !empty($order_items) ) {

				foreach ( $order_items as  $item_key => $item_values ) {

					$order_item_id = $item_values->get_variation_id();

					$product_id  = wp_get_post_parent_id($order_item_id);

					if( empty($product_id) ) {

						$product_id = $item_values->get_product_id();
					}

					$default_to_origin  = get_post_meta($product_id , '_ph_ups_product_address_preference', 1);

					if( $default_to_origin == 'yes' ) {

						$billing_address = false;
						break;
					}
				}
			}

			// To support Mix and Match Product
			do_action( 'ph_ups_after_get_items_from_order', $order );
		}
		
		return $billing_address;
	}

	// Automatic Package Generation
	public function ph_ups_auto_generate_packages( $order_id, $ups_settings, $minute = '') {
		
		// Check current time (minute) in Thank You Page for Automatic Package generation
		if( !$this->wf_user_check( $minute ) )
		{
			return;
		}
		
		$post_id 	=	base64_decode( $order_id );
		$order 		=	$this->wf_load_order( $post_id );

		if ( !($order instanceof WC_Order) ) return;
		
		$this->ph_ups_generate_packages( $post_id, true );
		
	}

	// Automatic Label Generation
	public function ph_ups_auto_create_shipment( $order_id, $ups_settings, $weight_arr, $length_arr, $width_arr, $height_arr, $service_arr, $insurance, $minute = '' ) {

		// Check current time (minute) in Thank You Page for Automatic Label generation
		$allowed_user = $this->wf_user_check( $minute );

		if( !$allowed_user ) {
			return;
		} 			

		$order 	= $this->wf_load_order( $order_id );
		$debug 	= ( $bool = $ups_settings[ 'debug' ] ) && $bool == 'yes' ? true : false;
		
		if ( !($order instanceof WC_Order) ) return;
		
		$shipment_ids = get_post_meta( $order_id, 'ups_label_details_array', true );

		if( empty($shipment_ids) ) {

			if( !empty($service_arr) && is_array($service_arr) ) {
				$this->auto_label_generation 	= true;
				$this->auto_label_services 		= $service_arr;
			}
			
			$this->wf_ups_shipment_confirm( $order_id, true, $minute );

		}else{

			if( $debug ) {
				_e( 'UPS label generation Suspended. Label has been already generated.', 'ups-woocommerce-shipping' );
			}
			if( class_exists('WC_Admin_Meta_Boxes') ) {
				WC_Admin_Meta_Boxes::add_error( 'UPS label generation Suspended. Label has been already generated.', 'ups-woocommerce-shipping' );
			}
		}
	}

	public function admin_diagnostic_report( $data ) {
	
		if( function_exists("wc_get_logger") ) {

			$log = wc_get_logger();
			$log->debug( ($data).PHP_EOL.PHP_EOL, array('source' => 'PluginHive-UPS-Error-Debug-Log'));
		}
	}
}
new WF_Shipping_UPS_Admin();