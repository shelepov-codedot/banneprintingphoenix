<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
// delete_option('hit_ups_auto_main_settings');
wp_enqueue_script("jquery");
$error = $success =  '';

global $woocommerce;
$_carriers = array(
							//domestic
							'ups_12'                    => '3 Day Select',
							'ups_03'                    => 'Ground',
							'ups_02'                    => '2nd Day Air',
							'ups_59'                    => '2nd Day Air AM',
							'ups_01'                    => 'Next Day Air',
							'ups_13'                    => 'Next Day Air Saver',
							'ups_14'                    => 'Next Day Air Early AM',

							//international		
							'ups_11'                    => 'UPS Standard',
							'ups_07'                    => 'UPS Express',
							'ups_08'                    => 'UPS Expedited',
							'ups_54'                    => 'UPS Express Plus',
							'ups_65'                    => 'UPS Saver',
							'ups_96'                    => 'Worldwide Express Freight',
							'ups_08'                    => 'UPS ExpeditedSM',

							//Other services
							'ups_92'                    => 'SurePost Less than 1 lb',
							'ups_93'                    => 'SurePost 1 lb or Greater',
							'ups_94'                    => 'SurePost BPM',
							'ups_95'                    => 'SurePost Media',
							'ups_82'                    => 'Today Standard',
							"ups_83"					=> "UPS Today Dedicated Courier",
							"ups_84"					=> "UPS Today Intercity",
							"ups_85"				    => "UPS Today Express",
							"ups_86" 					=> "UPS Today Express Saver",
							'ups_M2'                    => 'First Class Mail',
							'ups_M3'                    => 'Priority Mail',
							'ups_M4'                    => 'Expedited Mail Innovations',
							'ups_M5'                    => 'Priority Mail Innovations',
							'ups_M6'                    => 'EconomyMail Innovations',
							'ups_70'                    => 'Access Point Economy',
							
						);
$print_size = array('8X4_A4_PDF'=>'8X4_A4_PDF','8X4_thermal'=>'8X4_thermal','8X4_A4_TC_PDF'=>'8X4_A4_TC_PDF','8X4_CI_PDF'=>'8X4_CI_PDF','8X4_CI_thermal'=>'8X4_CI_thermal','8X4_RU_A4_PDF'=>'8X4_RU_A4_PDF','8X4_PDF'=>'8X4_PDF','8X4_CustBarCode_PDF'=>'8X4_CustBarCode_PDF','8X4_CustBarCode_thermal'=>'8X4_CustBarCode_thermal','6X4_A4_PDF'=>'6X4_A4_PDF','6X4_thermal'=>'6X4_thermal','6X4_PDF'=>'6X4_PDF');
$countires =  array(
			'AF' => 'Afghanistan',
			'AX' => 'Aland Islands',
			'AL' => 'Albania',
			'DZ' => 'Algeria',
			'AS' => 'American Samoa',
			'AD' => 'Andorra',
			'AO' => 'Angola',
			'AI' => 'Anguilla',
			'AQ' => 'Antarctica',
			'AG' => 'Antigua and Barbuda',
			'AR' => 'Argentina',
			'AM' => 'Armenia',
			'AW' => 'Aruba',
			'AU' => 'Australia',
			'AT' => 'Austria',
			'AZ' => 'Azerbaijan',
			'BS' => 'Bahamas',
			'BH' => 'Bahrain',
			'BD' => 'Bangladesh',
			'BB' => 'Barbados',
			'BY' => 'Belarus',
			'BE' => 'Belgium',
			'BZ' => 'Belize',
			'BJ' => 'Benin',
			'BM' => 'Bermuda',
			'BT' => 'Bhutan',
			'BO' => 'Bolivia',
			'BQ' => 'Bonaire, Saint Eustatius and Saba',
			'BA' => 'Bosnia and Herzegovina',
			'BW' => 'Botswana',
			'BV' => 'Bouvet Island',
			'BR' => 'Brazil',
			'IO' => 'British Indian Ocean Territory',
			'VG' => 'British Virgin Islands',
			'BN' => 'Brunei',
			'BG' => 'Bulgaria',
			'BF' => 'Burkina Faso',
			'BI' => 'Burundi',
			'KH' => 'Cambodia',
			'CM' => 'Cameroon',
			'CA' => 'Canada',
			'CV' => 'Cape Verde',
			'KY' => 'Cayman Islands',
			'CF' => 'Central African Republic',
			'TD' => 'Chad',
			'CL' => 'Chile',
			'CN' => 'China',
			'CX' => 'Christmas Island',
			'CC' => 'Cocos Islands',
			'CO' => 'Colombia',
			'KM' => 'Comoros',
			'CK' => 'Cook Islands',
			'CR' => 'Costa Rica',
			'HR' => 'Croatia',
			'CU' => 'Cuba',
			'CW' => 'Curacao',
			'CY' => 'Cyprus',
			'CZ' => 'Czech Republic',
			'CD' => 'Democratic Republic of the Congo',
			'DK' => 'Denmark',
			'DJ' => 'Djibouti',
			'DM' => 'Dominica',
			'DO' => 'Dominican Republic',
			'TL' => 'East Timor',
			'EC' => 'Ecuador',
			'EG' => 'Egypt',
			'SV' => 'El Salvador',
			'GQ' => 'Equatorial Guinea',
			'ER' => 'Eritrea',
			'EE' => 'Estonia',
			'ET' => 'Ethiopia',
			'FK' => 'Falkland Islands',
			'FO' => 'Faroe Islands',
			'FJ' => 'Fiji',
			'FI' => 'Finland',
			'FR' => 'France',
			'GF' => 'French Guiana',
			'PF' => 'French Polynesia',
			'TF' => 'French Southern Territories',
			'GA' => 'Gabon',
			'GM' => 'Gambia',
			'GE' => 'Georgia',
			'DE' => 'Germany',
			'GH' => 'Ghana',
			'GI' => 'Gibraltar',
			'GR' => 'Greece',
			'GL' => 'Greenland',
			'GD' => 'Grenada',
			'GP' => 'Guadeloupe',
			'GU' => 'Guam',
			'GT' => 'Guatemala',
			'GG' => 'Guernsey',
			'GN' => 'Guinea',
			'GW' => 'Guinea-Bissau',
			'GY' => 'Guyana',
			'HT' => 'Haiti',
			'HM' => 'Heard Island and McDonald Islands',
			'HN' => 'Honduras',
			'HK' => 'Hong Kong',
			'HU' => 'Hungary',
			'IS' => 'Iceland',
			'IN' => 'India',
			'ID' => 'Indonesia',
			'IR' => 'Iran',
			'IQ' => 'Iraq',
			'IE' => 'Ireland',
			'IM' => 'Isle of Man',
			'IL' => 'Israel',
			'IT' => 'Italy',
			'CI' => 'Ivory Coast',
			'JM' => 'Jamaica',
			'JP' => 'Japan',
			'JE' => 'Jersey',
			'JO' => 'Jordan',
			'KZ' => 'Kazakhstan',
			'KE' => 'Kenya',
			'KI' => 'Kiribati',
			'XK' => 'Kosovo',
			'KW' => 'Kuwait',
			'KG' => 'Kyrgyzstan',
			'LA' => 'Laos',
			'LV' => 'Latvia',
			'LB' => 'Lebanon',
			'LS' => 'Lesotho',
			'LR' => 'Liberia',
			'LY' => 'Libya',
			'LI' => 'Liechtenstein',
			'LT' => 'Lithuania',
			'LU' => 'Luxembourg',
			'MO' => 'Macao',
			'MK' => 'Macedonia',
			'MG' => 'Madagascar',
			'MW' => 'Malawi',
			'MY' => 'Malaysia',
			'MV' => 'Maldives',
			'ML' => 'Mali',
			'MT' => 'Malta',
			'MH' => 'Marshall Islands',
			'MQ' => 'Martinique',
			'MR' => 'Mauritania',
			'MU' => 'Mauritius',
			'YT' => 'Mayotte',
			'MX' => 'Mexico',
			'FM' => 'Micronesia',
			'MD' => 'Moldova',
			'MC' => 'Monaco',
			'MN' => 'Mongolia',
			'ME' => 'Montenegro',
			'MS' => 'Montserrat',
			'MA' => 'Morocco',
			'MZ' => 'Mozambique',
			'MM' => 'Myanmar',
			'NA' => 'Namibia',
			'NR' => 'Nauru',
			'NP' => 'Nepal',
			'NL' => 'Netherlands',
			'NC' => 'New Caledonia',
			'NZ' => 'New Zealand',
			'NI' => 'Nicaragua',
			'NE' => 'Niger',
			'NG' => 'Nigeria',
			'NU' => 'Niue',
			'NF' => 'Norfolk Island',
			'KP' => 'North Korea',
			'MP' => 'Northern Mariana Islands',
			'NO' => 'Norway',
			'OM' => 'Oman',
			'PK' => 'Pakistan',
			'PW' => 'Palau',
			'PS' => 'Palestinian Territory',
			'PA' => 'Panama',
			'PG' => 'Papua New Guinea',
			'PY' => 'Paraguay',
			'PE' => 'Peru',
			'PH' => 'Philippines',
			'PN' => 'Pitcairn',
			'PL' => 'Poland',
			'PT' => 'Portugal',
			'PR' => 'Puerto Rico',
			'QA' => 'Qatar',
			'CG' => 'Republic of the Congo',
			'RE' => 'Reunion',
			'RO' => 'Romania',
			'RU' => 'Russia',
			'RW' => 'Rwanda',
			'BL' => 'Saint Barthelemy',
			'SH' => 'Saint Helena',
			'KN' => 'Saint Kitts and Nevis',
			'LC' => 'Saint Lucia',
			'MF' => 'Saint Martin',
			'PM' => 'Saint Pierre and Miquelon',
			'VC' => 'Saint Vincent and the Grenadines',
			'WS' => 'Samoa',
			'SM' => 'San Marino',
			'ST' => 'Sao Tome and Principe',
			'SA' => 'Saudi Arabia',
			'SN' => 'Senegal',
			'RS' => 'Serbia',
			'SC' => 'Seychelles',
			'SL' => 'Sierra Leone',
			'SG' => 'Singapore',
			'SX' => 'Sint Maarten',
			'SK' => 'Slovakia',
			'SI' => 'Slovenia',
			'SB' => 'Solomon Islands',
			'SO' => 'Somalia',
			'ZA' => 'South Africa',
			'GS' => 'South Georgia and the South Sandwich Islands',
			'KR' => 'South Korea',
			'SS' => 'South Sudan',
			'ES' => 'Spain',
			'LK' => 'Sri Lanka',
			'SD' => 'Sudan',
			'SR' => 'Suriname',
			'SJ' => 'Svalbard and Jan Mayen',
			'SZ' => 'Swaziland',
			'SE' => 'Sweden',
			'CH' => 'Switzerland',
			'SY' => 'Syria',
			'TW' => 'Taiwan',
			'TJ' => 'Tajikistan',
			'TZ' => 'Tanzania',
			'TH' => 'Thailand',
			'TG' => 'Togo',
			'TK' => 'Tokelau',
			'TO' => 'Tonga',
			'TT' => 'Trinidad and Tobago',
			'TN' => 'Tunisia',
			'TR' => 'Turkey',
			'TM' => 'Turkmenistan',
			'TC' => 'Turks and Caicos Islands',
			'TV' => 'Tuvalu',
			'VI' => 'U.S. Virgin Islands',
			'UG' => 'Uganda',
			'UA' => 'Ukraine',
			'AE' => 'United Arab Emirates',
			'GB' => 'United Kingdom',
			'US' => 'United States',
			'UM' => 'United States Minor Outlying Islands',
			'UY' => 'Uruguay',
			'UZ' => 'Uzbekistan',
			'VU' => 'Vanuatu',
			'VA' => 'Vatican',
			'VE' => 'Venezuela',
			'VN' => 'Vietnam',
			'WF' => 'Wallis and Futuna',
			'EH' => 'Western Sahara',
			'YE' => 'Yemen',
			'ZM' => 'Zambia',
			'ZW' => 'Zimbabwe',
		);
$duty_payment_type = array('S' =>'Shipper','R' =>'Recipient');
		$value = array();
		$value['AD'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$value['AE'] = array('region' => 'AP', 'currency' =>'AED', 'weight' => 'KG_CM');
		$value['AF'] = array('region' => 'AP', 'currency' =>'AFN', 'weight' => 'KG_CM');
		$value['AG'] = array('region' => 'AM', 'currency' =>'XCD', 'weight' => 'LB_IN');
		$value['AI'] = array('region' => 'AM', 'currency' =>'XCD', 'weight' => 'LB_IN');
		$value['AL'] = array('region' => 'AP', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$value['AM'] = array('region' => 'AP', 'currency' =>'AMD', 'weight' => 'KG_CM');
		$value['AN'] = array('region' => 'AM', 'currency' =>'ANG', 'weight' => 'KG_CM');
		$value['AO'] = array('region' => 'AP', 'currency' =>'AOA', 'weight' => 'KG_CM');
		$value['AR'] = array('region' => 'AM', 'currency' =>'ARS', 'weight' => 'KG_CM');
		$value['AS'] = array('region' => 'AM', 'currency' =>'USD', 'weight' => 'LB_IN');
		$value['AT'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$value['AU'] = array('region' => 'AP', 'currency' =>'AUD', 'weight' => 'KG_CM');
		$value['AW'] = array('region' => 'AM', 'currency' =>'AWG', 'weight' => 'LB_IN');
		$value['AZ'] = array('region' => 'AM', 'currency' =>'AZN', 'weight' => 'KG_CM');
		$value['AZ'] = array('region' => 'AM', 'currency' =>'AZN', 'weight' => 'KG_CM');
		$value['GB'] = array('region' => 'EU', 'currency' =>'GBP', 'weight' => 'KG_CM');
		$value['BA'] = array('region' => 'AP', 'currency' =>'BAM', 'weight' => 'KG_CM');
		$value['BB'] = array('region' => 'AM', 'currency' =>'BBD', 'weight' => 'LB_IN');
		$value['BD'] = array('region' => 'AP', 'currency' =>'BDT', 'weight' => 'KG_CM');
		$value['BE'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$value['BF'] = array('region' => 'AP', 'currency' =>'XOF', 'weight' => 'KG_CM');
		$value['BG'] = array('region' => 'EU', 'currency' =>'BGN', 'weight' => 'KG_CM');
		$value['BH'] = array('region' => 'AP', 'currency' =>'BHD', 'weight' => 'KG_CM');
		$value['BI'] = array('region' => 'AP', 'currency' =>'BIF', 'weight' => 'KG_CM');
		$value['BJ'] = array('region' => 'AP', 'currency' =>'XOF', 'weight' => 'KG_CM');
		$value['BM'] = array('region' => 'AM', 'currency' =>'BMD', 'weight' => 'LB_IN');
		$value['BN'] = array('region' => 'AP', 'currency' =>'BND', 'weight' => 'KG_CM');
		$value['BO'] = array('region' => 'AM', 'currency' =>'BOB', 'weight' => 'KG_CM');
		$value['BR'] = array('region' => 'AM', 'currency' =>'BRL', 'weight' => 'KG_CM');
		$value['BS'] = array('region' => 'AM', 'currency' =>'BSD', 'weight' => 'LB_IN');
		$value['BT'] = array('region' => 'AP', 'currency' =>'BTN', 'weight' => 'KG_CM');
		$value['BW'] = array('region' => 'AP', 'currency' =>'BWP', 'weight' => 'KG_CM');
		$value['BY'] = array('region' => 'AP', 'currency' =>'BYR', 'weight' => 'KG_CM');
		$value['BZ'] = array('region' => 'AM', 'currency' =>'BZD', 'weight' => 'KG_CM');
		$value['CA'] = array('region' => 'AM', 'currency' =>'CAD', 'weight' => 'LB_IN');
		$value['CF'] = array('region' => 'AP', 'currency' =>'XAF', 'weight' => 'KG_CM');
		$value['CG'] = array('region' => 'AP', 'currency' =>'XAF', 'weight' => 'KG_CM');
		$value['CH'] = array('region' => 'EU', 'currency' =>'CHF', 'weight' => 'KG_CM');
		$value['CI'] = array('region' => 'AP', 'currency' =>'XOF', 'weight' => 'KG_CM');
		$value['CK'] = array('region' => 'AP', 'currency' =>'NZD', 'weight' => 'KG_CM');
		$value['CL'] = array('region' => 'AM', 'currency' =>'CLP', 'weight' => 'KG_CM');
		$value['CM'] = array('region' => 'AP', 'currency' =>'XAF', 'weight' => 'KG_CM');
		$value['CN'] = array('region' => 'AP', 'currency' =>'CNY', 'weight' => 'KG_CM');
		$value['CO'] = array('region' => 'AM', 'currency' =>'COP', 'weight' => 'KG_CM');
		$value['CR'] = array('region' => 'AM', 'currency' =>'CRC', 'weight' => 'KG_CM');
		$value['CU'] = array('region' => 'AM', 'currency' =>'CUC', 'weight' => 'KG_CM');
		$value['CV'] = array('region' => 'AP', 'currency' =>'CVE', 'weight' => 'KG_CM');
		$value['CY'] = array('region' => 'AP', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$value['CZ'] = array('region' => 'EU', 'currency' =>'CZF', 'weight' => 'KG_CM');
		$value['DE'] = array('region' => 'AP', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$value['DJ'] = array('region' => 'EU', 'currency' =>'DJF', 'weight' => 'KG_CM');
		$value['DK'] = array('region' => 'AM', 'currency' =>'DKK', 'weight' => 'KG_CM');
		$value['DM'] = array('region' => 'AM', 'currency' =>'XCD', 'weight' => 'LB_IN');
		$value['DO'] = array('region' => 'AP', 'currency' =>'DOP', 'weight' => 'LB_IN');
		$value['DZ'] = array('region' => 'AM', 'currency' =>'DZD', 'weight' => 'KG_CM');
		$value['EC'] = array('region' => 'EU', 'currency' =>'USD', 'weight' => 'KG_CM');
		$value['EE'] = array('region' => 'AP', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$value['EG'] = array('region' => 'AP', 'currency' =>'EGP', 'weight' => 'KG_CM');
		$value['ER'] = array('region' => 'EU', 'currency' =>'ERN', 'weight' => 'KG_CM');
		$value['ES'] = array('region' => 'AP', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$value['ET'] = array('region' => 'AU', 'currency' =>'ETB', 'weight' => 'KG_CM');
		$value['FI'] = array('region' => 'AP', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$value['FJ'] = array('region' => 'AP', 'currency' =>'FJD', 'weight' => 'KG_CM');
		$value['FK'] = array('region' => 'AM', 'currency' =>'GBP', 'weight' => 'KG_CM');
		$value['FM'] = array('region' => 'AM', 'currency' =>'USD', 'weight' => 'LB_IN');
		$value['FO'] = array('region' => 'AM', 'currency' =>'DKK', 'weight' => 'KG_CM');
		$value['FR'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$value['GA'] = array('region' => 'AP', 'currency' =>'XAF', 'weight' => 'KG_CM');
		$value['GB'] = array('region' => 'EU', 'currency' =>'GBP', 'weight' => 'KG_CM');
		$value['GD'] = array('region' => 'AM', 'currency' =>'XCD', 'weight' => 'LB_IN');
		$value['GE'] = array('region' => 'AM', 'currency' =>'GEL', 'weight' => 'KG_CM');
		$value['GF'] = array('region' => 'AM', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$value['GG'] = array('region' => 'AM', 'currency' =>'GBP', 'weight' => 'KG_CM');
		$value['GH'] = array('region' => 'AP', 'currency' =>'GBS', 'weight' => 'KG_CM');
		$value['GI'] = array('region' => 'AM', 'currency' =>'GBP', 'weight' => 'KG_CM');
		$value['GL'] = array('region' => 'AM', 'currency' =>'DKK', 'weight' => 'KG_CM');
		$value['GM'] = array('region' => 'AP', 'currency' =>'GMD', 'weight' => 'KG_CM');
		$value['GN'] = array('region' => 'AP', 'currency' =>'GNF', 'weight' => 'KG_CM');
		$value['GP'] = array('region' => 'AM', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$value['GQ'] = array('region' => 'AP', 'currency' =>'XAF', 'weight' => 'KG_CM');
		$value['GR'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$value['GT'] = array('region' => 'AM', 'currency' =>'GTQ', 'weight' => 'KG_CM');
		$value['GU'] = array('region' => 'AM', 'currency' =>'USD', 'weight' => 'LB_IN');
		$value['GW'] = array('region' => 'AP', 'currency' =>'XOF', 'weight' => 'KG_CM');
		$value['GY'] = array('region' => 'AP', 'currency' =>'GYD', 'weight' => 'LB_IN');
		$value['HK'] = array('region' => 'AM', 'currency' =>'HKD', 'weight' => 'KG_CM');
		$value['HN'] = array('region' => 'AM', 'currency' =>'HNL', 'weight' => 'KG_CM');
		$value['HR'] = array('region' => 'AP', 'currency' =>'HRK', 'weight' => 'KG_CM');
		$value['HT'] = array('region' => 'AM', 'currency' =>'HTG', 'weight' => 'LB_IN');
		$value['HU'] = array('region' => 'EU', 'currency' =>'HUF', 'weight' => 'KG_CM');
		$value['IC'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$value['ID'] = array('region' => 'AP', 'currency' =>'IDR', 'weight' => 'KG_CM');
		$value['IE'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$value['IL'] = array('region' => 'AP', 'currency' =>'ILS', 'weight' => 'KG_CM');
		$value['IN'] = array('region' => 'AP', 'currency' =>'INR', 'weight' => 'KG_CM');
		$value['IQ'] = array('region' => 'AP', 'currency' =>'IQD', 'weight' => 'KG_CM');
		$value['IR'] = array('region' => 'AP', 'currency' =>'IRR', 'weight' => 'KG_CM');
		$value['IS'] = array('region' => 'EU', 'currency' =>'ISK', 'weight' => 'KG_CM');
		$value['IT'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$value['JE'] = array('region' => 'AM', 'currency' =>'GBP', 'weight' => 'KG_CM');
		$value['JM'] = array('region' => 'AM', 'currency' =>'JMD', 'weight' => 'KG_CM');
		$value['JO'] = array('region' => 'AP', 'currency' =>'JOD', 'weight' => 'KG_CM');
		$value['JP'] = array('region' => 'AP', 'currency' =>'JPY', 'weight' => 'KG_CM');
		$value['KE'] = array('region' => 'AP', 'currency' =>'KES', 'weight' => 'KG_CM');
		$value['KG'] = array('region' => 'AP', 'currency' =>'KGS', 'weight' => 'KG_CM');
		$value['KH'] = array('region' => 'AP', 'currency' =>'KHR', 'weight' => 'KG_CM');
		$value['KI'] = array('region' => 'AP', 'currency' =>'AUD', 'weight' => 'KG_CM');
		$value['KM'] = array('region' => 'AP', 'currency' =>'KMF', 'weight' => 'KG_CM');
		$value['KN'] = array('region' => 'AM', 'currency' =>'XCD', 'weight' => 'LB_IN');
		$value['KP'] = array('region' => 'AP', 'currency' =>'KPW', 'weight' => 'LB_IN');
		$value['KR'] = array('region' => 'AP', 'currency' =>'KRW', 'weight' => 'KG_CM');
		$value['KV'] = array('region' => 'AM', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$value['KW'] = array('region' => 'AP', 'currency' =>'KWD', 'weight' => 'KG_CM');
		$value['KY'] = array('region' => 'AM', 'currency' =>'KYD', 'weight' => 'KG_CM');
		$value['KZ'] = array('region' => 'AP', 'currency' =>'KZF', 'weight' => 'LB_IN');
		$value['LA'] = array('region' => 'AP', 'currency' =>'LAK', 'weight' => 'KG_CM');
		$value['LB'] = array('region' => 'AP', 'currency' =>'USD', 'weight' => 'KG_CM');
		$value['LC'] = array('region' => 'AM', 'currency' =>'XCD', 'weight' => 'KG_CM');
		$value['LI'] = array('region' => 'AM', 'currency' =>'CHF', 'weight' => 'LB_IN');
		$value['LK'] = array('region' => 'AP', 'currency' =>'LKR', 'weight' => 'KG_CM');
		$value['LR'] = array('region' => 'AP', 'currency' =>'LRD', 'weight' => 'KG_CM');
		$value['LS'] = array('region' => 'AP', 'currency' =>'LSL', 'weight' => 'KG_CM');
		$value['LT'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$value['LU'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$value['LV'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$value['LY'] = array('region' => 'AP', 'currency' =>'LYD', 'weight' => 'KG_CM');
		$value['MA'] = array('region' => 'AP', 'currency' =>'MAD', 'weight' => 'KG_CM');
		$value['MC'] = array('region' => 'AM', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$value['MD'] = array('region' => 'AP', 'currency' =>'MDL', 'weight' => 'KG_CM');
		$value['ME'] = array('region' => 'AM', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$value['MG'] = array('region' => 'AP', 'currency' =>'MGA', 'weight' => 'KG_CM');
		$value['MH'] = array('region' => 'AM', 'currency' =>'USD', 'weight' => 'LB_IN');
		$value['MK'] = array('region' => 'AP', 'currency' =>'MKD', 'weight' => 'KG_CM');
		$value['ML'] = array('region' => 'AP', 'currency' =>'COF', 'weight' => 'KG_CM');
		$value['MM'] = array('region' => 'AP', 'currency' =>'USD', 'weight' => 'KG_CM');
		$value['MN'] = array('region' => 'AP', 'currency' =>'MNT', 'weight' => 'KG_CM');
		$value['MO'] = array('region' => 'AP', 'currency' =>'MOP', 'weight' => 'KG_CM');
		$value['MP'] = array('region' => 'AM', 'currency' =>'USD', 'weight' => 'LB_IN');
		$value['MQ'] = array('region' => 'AM', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$value['MR'] = array('region' => 'AP', 'currency' =>'MRO', 'weight' => 'KG_CM');
		$value['MS'] = array('region' => 'AM', 'currency' =>'XCD', 'weight' => 'LB_IN');
		$value['MT'] = array('region' => 'AP', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$value['MU'] = array('region' => 'AP', 'currency' =>'MUR', 'weight' => 'KG_CM');
		$value['MV'] = array('region' => 'AP', 'currency' =>'MVR', 'weight' => 'KG_CM');
		$value['MW'] = array('region' => 'AP', 'currency' =>'MWK', 'weight' => 'KG_CM');
		$value['MX'] = array('region' => 'AM', 'currency' =>'MXN', 'weight' => 'KG_CM');
		$value['MY'] = array('region' => 'AP', 'currency' =>'MYR', 'weight' => 'KG_CM');
		$value['MZ'] = array('region' => 'AP', 'currency' =>'MZN', 'weight' => 'KG_CM');
		$value['NA'] = array('region' => 'AP', 'currency' =>'NAD', 'weight' => 'KG_CM');
		$value['NC'] = array('region' => 'AP', 'currency' =>'XPF', 'weight' => 'KG_CM');
		$value['NE'] = array('region' => 'AP', 'currency' =>'XOF', 'weight' => 'KG_CM');
		$value['NG'] = array('region' => 'AP', 'currency' =>'NGN', 'weight' => 'KG_CM');
		$value['NI'] = array('region' => 'AM', 'currency' =>'NIO', 'weight' => 'KG_CM');
		$value['NL'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$value['NO'] = array('region' => 'EU', 'currency' =>'NOK', 'weight' => 'KG_CM');
		$value['NP'] = array('region' => 'AP', 'currency' =>'NPR', 'weight' => 'KG_CM');
		$value['NR'] = array('region' => 'AP', 'currency' =>'AUD', 'weight' => 'KG_CM');
		$value['NU'] = array('region' => 'AP', 'currency' =>'NZD', 'weight' => 'KG_CM');
		$value['NZ'] = array('region' => 'AP', 'currency' =>'NZD', 'weight' => 'KG_CM');
		$value['OM'] = array('region' => 'AP', 'currency' =>'OMR', 'weight' => 'KG_CM');
		$value['PA'] = array('region' => 'AM', 'currency' =>'USD', 'weight' => 'KG_CM');
		$value['PE'] = array('region' => 'AM', 'currency' =>'PEN', 'weight' => 'KG_CM');
		$value['PF'] = array('region' => 'AP', 'currency' =>'XPF', 'weight' => 'KG_CM');
		$value['PG'] = array('region' => 'AP', 'currency' =>'PGK', 'weight' => 'KG_CM');
		$value['PH'] = array('region' => 'AP', 'currency' =>'PHP', 'weight' => 'KG_CM');
		$value['PK'] = array('region' => 'AP', 'currency' =>'PKR', 'weight' => 'KG_CM');
		$value['PL'] = array('region' => 'EU', 'currency' =>'PLN', 'weight' => 'KG_CM');
		$value['PR'] = array('region' => 'AM', 'currency' =>'USD', 'weight' => 'LB_IN');
		$value['PT'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$value['PW'] = array('region' => 'AM', 'currency' =>'USD', 'weight' => 'KG_CM');
		$value['PY'] = array('region' => 'AM', 'currency' =>'PYG', 'weight' => 'KG_CM');
		$value['QA'] = array('region' => 'AP', 'currency' =>'QAR', 'weight' => 'KG_CM');
		$value['RE'] = array('region' => 'AP', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$value['RO'] = array('region' => 'EU', 'currency' =>'RON', 'weight' => 'KG_CM');
		$value['RS'] = array('region' => 'AP', 'currency' =>'RSD', 'weight' => 'KG_CM');
		$value['RU'] = array('region' => 'AP', 'currency' =>'RUB', 'weight' => 'KG_CM');
		$value['RW'] = array('region' => 'AP', 'currency' =>'RWF', 'weight' => 'KG_CM');
		$value['SA'] = array('region' => 'AP', 'currency' =>'SAR', 'weight' => 'KG_CM');
		$value['SB'] = array('region' => 'AP', 'currency' =>'SBD', 'weight' => 'KG_CM');
		$value['SC'] = array('region' => 'AP', 'currency' =>'SCR', 'weight' => 'KG_CM');
		$value['SD'] = array('region' => 'AP', 'currency' =>'SDG', 'weight' => 'KG_CM');
		$value['SE'] = array('region' => 'EU', 'currency' =>'SEK', 'weight' => 'KG_CM');
		$value['SG'] = array('region' => 'AP', 'currency' =>'SGD', 'weight' => 'KG_CM');
		$value['SH'] = array('region' => 'AP', 'currency' =>'SHP', 'weight' => 'KG_CM');
		$value['SI'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$value['SK'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$value['SL'] = array('region' => 'AP', 'currency' =>'SLL', 'weight' => 'KG_CM');
		$value['SM'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$value['SN'] = array('region' => 'AP', 'currency' =>'XOF', 'weight' => 'KG_CM');
		$value['SO'] = array('region' => 'AM', 'currency' =>'SOS', 'weight' => 'KG_CM');
		$value['SR'] = array('region' => 'AM', 'currency' =>'SRD', 'weight' => 'KG_CM');
		$value['SS'] = array('region' => 'AP', 'currency' =>'SSP', 'weight' => 'KG_CM');
		$value['ST'] = array('region' => 'AP', 'currency' =>'STD', 'weight' => 'KG_CM');
		$value['SV'] = array('region' => 'AM', 'currency' =>'USD', 'weight' => 'KG_CM');
		$value['SY'] = array('region' => 'AP', 'currency' =>'SYP', 'weight' => 'KG_CM');
		$value['SZ'] = array('region' => 'AP', 'currency' =>'SZL', 'weight' => 'KG_CM');
		$value['TC'] = array('region' => 'AM', 'currency' =>'USD', 'weight' => 'LB_IN');
		$value['TD'] = array('region' => 'AP', 'currency' =>'XAF', 'weight' => 'KG_CM');
		$value['TG'] = array('region' => 'AP', 'currency' =>'XOF', 'weight' => 'KG_CM');
		$value['TH'] = array('region' => 'AP', 'currency' =>'THB', 'weight' => 'KG_CM');
		$value['TJ'] = array('region' => 'AP', 'currency' =>'TJS', 'weight' => 'KG_CM');
		$value['TL'] = array('region' => 'AP', 'currency' =>'USD', 'weight' => 'KG_CM');
		$value['TN'] = array('region' => 'AP', 'currency' =>'TND', 'weight' => 'KG_CM');
		$value['TO'] = array('region' => 'AP', 'currency' =>'TOP', 'weight' => 'KG_CM');
		$value['TR'] = array('region' => 'AP', 'currency' =>'TRY', 'weight' => 'KG_CM');
		$value['TT'] = array('region' => 'AM', 'currency' =>'TTD', 'weight' => 'LB_IN');
		$value['TV'] = array('region' => 'AP', 'currency' =>'AUD', 'weight' => 'KG_CM');
		$value['TW'] = array('region' => 'AP', 'currency' =>'TWD', 'weight' => 'KG_CM');
		$value['TZ'] = array('region' => 'AP', 'currency' =>'TZS', 'weight' => 'KG_CM');
		$value['UA'] = array('region' => 'AP', 'currency' =>'UAH', 'weight' => 'KG_CM');
		$value['UG'] = array('region' => 'AP', 'currency' =>'USD', 'weight' => 'KG_CM');
		$value['US'] = array('region' => 'AM', 'currency' =>'USD', 'weight' => 'LB_IN');
		$value['UY'] = array('region' => 'AM', 'currency' =>'UYU', 'weight' => 'KG_CM');
		$value['UZ'] = array('region' => 'AP', 'currency' =>'UZS', 'weight' => 'KG_CM');
		$value['VC'] = array('region' => 'AM', 'currency' =>'XCD', 'weight' => 'LB_IN');
		$value['VE'] = array('region' => 'AM', 'currency' =>'VEF', 'weight' => 'KG_CM');
		$value['VG'] = array('region' => 'AM', 'currency' =>'USD', 'weight' => 'LB_IN');
		$value['VI'] = array('region' => 'AM', 'currency' =>'USD', 'weight' => 'LB_IN');
		$value['VN'] = array('region' => 'AP', 'currency' =>'VND', 'weight' => 'KG_CM');
		$value['VU'] = array('region' => 'AP', 'currency' =>'VUV', 'weight' => 'KG_CM');
		$value['WS'] = array('region' => 'AP', 'currency' =>'WST', 'weight' => 'KG_CM');
		$value['XB'] = array('region' => 'AM', 'currency' =>'EUR', 'weight' => 'LB_IN');
		$value['XC'] = array('region' => 'AM', 'currency' =>'EUR', 'weight' => 'LB_IN');
		$value['XE'] = array('region' => 'AM', 'currency' =>'ANG', 'weight' => 'LB_IN');
		$value['XM'] = array('region' => 'AM', 'currency' =>'EUR', 'weight' => 'LB_IN');
		$value['XN'] = array('region' => 'AM', 'currency' =>'XCD', 'weight' => 'LB_IN');
		$value['XS'] = array('region' => 'AP', 'currency' =>'SIS', 'weight' => 'KG_CM');
		$value['XY'] = array('region' => 'AM', 'currency' =>'ANG', 'weight' => 'LB_IN');
		$value['YE'] = array('region' => 'AP', 'currency' =>'YER', 'weight' => 'KG_CM');
		$value['YT'] = array('region' => 'AP', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$value['ZA'] = array('region' => 'AP', 'currency' =>'ZAR', 'weight' => 'KG_CM');
		$value['ZM'] = array('region' => 'AP', 'currency' =>'ZMW', 'weight' => 'KG_CM');
		$value['ZW'] = array('region' => 'AP', 'currency' =>'USD', 'weight' => 'KG_CM');
	$packing_type = array("per_item" => "Pack Items Induviually", "weight_based" => "Weight Based Packing");
	$classification = array("00" => "Rate associated with account number", "00" => "Rate Type based on the shipper's
	country or territory","01"=>"Daily Rates","04"=>"Retail Rates","53"=>"Standard List Rates","06"=>"General List Rates","07"=>"Alternative Zoning","08"=>"General List Rates II","09"=>"SMB Loyalty","10"=>"All Inclusive","11"=>"Value Bundle I");
	$weight_dim_unit = array("KG_CM" => "KG_CM", "LB_IN" => "LB_IN");
	$currencys = $value; 
	$general_settings = get_option('hit_ups_auto_main_settings');
	$general_settings = empty($general_settings) ? array() : $general_settings;
	
	$mail_temp = empty($mail_temp) ? array() : $mail_temp;
	if(isset($_POST['save']))
	{
		if(isset($_POST['hit_ups_auto_site_id'])){
		$general_settings['hit_ups_auto_site_id'] = sanitize_text_field(isset($_POST['hit_ups_auto_site_id']) ? $_POST['hit_ups_auto_site_id'] : '');
		$general_settings['hit_ups_auto_site_pwd'] = sanitize_text_field(isset($_POST['hit_ups_auto_site_pwd']) ? $_POST['hit_ups_auto_site_pwd'] : '');
		$general_settings['hit_ups_auto_acc_no'] = sanitize_text_field(isset($_POST['hit_ups_auto_acc_no']) ? $_POST['hit_ups_auto_acc_no'] : '');
		$general_settings['hit_ups_auto_access_key'] = sanitize_text_field(isset($_POST['hit_ups_auto_access_key']) ? $_POST['hit_ups_auto_access_key'] : '');
		$general_settings['hit_ups_auto_test'] = sanitize_text_field(isset($_POST['hit_ups_auto_test']) ? 'yes' : 'no');
		$general_settings['hit_ups_auto_rates'] = sanitize_text_field(isset($_POST['hit_ups_auto_rates']) ? 'yes' : 'no');
		$general_settings['hit_ups_auto_shipper_name'] = sanitize_text_field(isset($_POST['hit_ups_auto_shipper_name']) ? $_POST['hit_ups_auto_shipper_name'] : '');
		$general_settings['hit_ups_auto_company'] = sanitize_text_field(isset($_POST['hit_ups_auto_company']) ? $_POST['hit_ups_auto_company'] : '');
		$general_settings['hit_ups_auto_mob_num'] = sanitize_text_field(isset($_POST['hit_ups_auto_mob_num']) ? $_POST['hit_ups_auto_mob_num'] : '');
		$general_settings['hit_ups_auto_email'] = sanitize_text_field(isset($_POST['hit_ups_auto_email']) ? $_POST['hit_ups_auto_email'] : '');
		$general_settings['hit_ups_auto_address1'] = sanitize_text_field(isset($_POST['hit_ups_auto_address1']) ? $_POST['hit_ups_auto_address1'] : '');
		$general_settings['hit_ups_auto_address2'] = sanitize_text_field(isset($_POST['hit_ups_auto_address2']) ? $_POST['hit_ups_auto_address2'] : '');
		$general_settings['hit_ups_auto_city'] = sanitize_text_field(isset($_POST['hit_ups_auto_city']) ? $_POST['hit_ups_auto_city'] : '');
		$general_settings['hit_ups_auto_state'] = sanitize_text_field(isset($_POST['hit_ups_auto_state']) ? $_POST['hit_ups_auto_state'] : '');
		$general_settings['hit_ups_auto_zip'] = sanitize_text_field(isset($_POST['hit_ups_auto_zip']) ? $_POST['hit_ups_auto_zip'] : '');
		$general_settings['hit_ups_auto_country'] = sanitize_text_field(isset($_POST['hit_ups_auto_country']) ? $_POST['hit_ups_auto_country'] : '');
		$general_settings['hit_ups_auto_gstin'] = sanitize_text_field(isset($_POST['hit_ups_auto_gstin']) ? $_POST['hit_ups_auto_gstin'] : '');
		$general_settings['hit_ups_auto_carrier'] = !empty($_POST['hit_ups_auto_carrier']) ? $_POST['hit_ups_auto_carrier'] : array();
		$general_settings['hit_ups_auto_carrier_name'] = !empty($_POST['hit_ups_auto_carrier_name']) ? $_POST['hit_ups_auto_carrier_name'] : array();
		$general_settings['hit_ups_auto_carrier_adj'] = !empty($_POST['hit_ups_auto_carrier_adj']) ? $_POST['hit_ups_auto_carrier_adj'] : array();
		$general_settings['hit_ups_auto_carrier_adj_percentage'] = !empty($_POST['hit_ups_auto_carrier_adj_percentage']) ? $_POST['hit_ups_auto_carrier_adj_percentage'] : array();
		$general_settings['hit_ups_auto_account_rates'] = sanitize_text_field(isset($_POST['hit_ups_auto_account_rates']) ? 'yes' : 'no');
		$general_settings['hit_ups_auto_developer_rate'] = sanitize_text_field(isset($_POST['hit_ups_auto_developer_rate']) ? 'yes' :'no');
		$general_settings['hit_ups_auto_insure'] = sanitize_text_field(isset($_POST['hit_ups_auto_insure']) ? 'yes' :'no');
		$general_settings['hit_ups_auto_exclude_countries'] = !empty($_POST['hit_ups_auto_exclude_countries']) ? $_POST['hit_ups_auto_exclude_countries'] : array();
		
		$general_settings['hit_ups_auto_uostatus'] = sanitize_text_field(isset($_POST['hit_ups_auto_uostatus']) ? 'yes' :'no');
		$general_settings['hit_ups_auto_trk_status_cus'] = sanitize_text_field(isset($_POST['hit_ups_auto_trk_status_cus']) ? 'yes' :'no');
		$general_settings['hit_ups_auto_aabill'] = sanitize_text_field(isset($_POST['hit_ups_auto_aabill']) ? 'yes' :'no');
		$general_settings['hit_ups_auto_cod'] = sanitize_text_field(isset($_POST['hit_ups_auto_cod']) ? 'yes' :'no');
		$general_settings['hit_ups_auto_sat'] = sanitize_text_field(isset($_POST['hit_ups_auto_sat']) ? 'yes' :'no');
		$general_settings['hit_ups_auto_ppt'] = sanitize_text_field(isset($_POST['hit_ups_auto_ppt']) ? 'yes' :'no');
		$general_settings['hit_ups_auto_label_automation'] = sanitize_text_field(isset($_POST['hit_ups_auto_label_automation']) ? 'yes' :'no');
		
		$general_settings['hit_ups_auto_packing_type'] = sanitize_text_field(isset($_POST['hit_ups_auto_packing_type']) ? $_POST['hit_ups_auto_packing_type'] : 'per_item');
		$general_settings['hit_ups_auto_max_weight'] = sanitize_text_field(isset($_POST['hit_ups_auto_max_weight']) ? $_POST['hit_ups_auto_max_weight'] : '100');
		$general_settings['hit_ups_auto_customer_classification'] = sanitize_text_field(isset($_POST['hit_ups_auto_customer_classification']) ? $_POST['hit_ups_auto_customer_classification'] : '00');
		// $general_settings['hit_ups_auto_integration_key'] = sanitize_text_field(isset($_POST['hit_ups_auto_integration_key']) ? $_POST['hit_ups_auto_integration_key'] : '');
		$general_settings['hit_ups_auto_label_email'] = sanitize_text_field(isset($_POST['hit_ups_auto_label_email']) ? $_POST['hit_ups_auto_label_email'] : '');
		$general_settings['hit_ups_auto_ship_content'] = sanitize_text_field(isset($_POST['hit_ups_auto_ship_content']) ? $_POST['hit_ups_auto_ship_content'] : 'No shipment content');
		$general_settings['hit_ups_auto_mail_sub'] = sanitize_text_field(isset($_POST['hit_ups_auto_mail_sub']) ? $_POST['hit_ups_auto_mail_sub'] : 'No shipment content');
		$general_settings['hit_ups_auto_print_size'] = sanitize_text_field(isset($_POST['hit_ups_auto_print_size']) ? $_POST['hit_ups_auto_print_size'] : '6X4_PDF');
		$general_settings['hit_ups_auto_sign_req'] = sanitize_text_field(isset($_POST['hit_ups_auto_sign_req']) ? $_POST['hit_ups_auto_sign_req'] : 'no');
		$general_settings['hit_ups_auto_send_trac'] = sanitize_text_field(isset($_POST['hit_ups_auto_send_trac']) ? $_POST['hit_ups_auto_send_trac'] : 'no');
		$general_settings['hit_ups_auto_duty_payment'] = sanitize_text_field(isset($_POST['hit_ups_auto_duty_payment']) ? $_POST['hit_ups_auto_duty_payment'] : 'none');
		$general_settings['hit_ups_auto_weight_unit'] = sanitize_text_field(isset($_POST['hit_ups_auto_weight_unit']) ? $_POST['hit_ups_auto_weight_unit'] : 'KG_CM');
		$general_settings['hit_ups_auto_con_rate'] = sanitize_text_field(isset($_POST['hit_ups_auto_con_rate']) ? $_POST['hit_ups_auto_con_rate'] : '');
		$mail_temp['hit_ups_auto_mail_temp'] = isset($_POST['hit_ups_auto_mail_temp']) ? $_POST['hit_ups_auto_mail_temp'] : '';
		// Multi Vendor Settings

		$general_settings['hit_ups_auto_v_enable'] = sanitize_text_field(isset($_POST['hit_ups_auto_v_enable']) ? 'yes' : 'no');
		$general_settings['hit_ups_auto_v_rates'] = sanitize_text_field(isset($_POST['hit_ups_auto_v_rates']) ? 'yes' : 'no');
		$general_settings['hit_ups_auto_v_labels'] = sanitize_text_field(isset($_POST['hit_ups_auto_v_labels']) ? 'yes' : 'no');
		$general_settings['hit_ups_auto_v_roles'] = !empty($_POST['hit_ups_auto_v_roles']) ? $_POST['hit_ups_auto_v_roles'] : array();
		$general_settings['hit_ups_auto_v_email'] = sanitize_text_field(isset($_POST['hit_ups_auto_v_email']) ? 'yes' : 'no');
		$general_settings['hit_ups_auto_currency'] = isset($value[(isset($general_settings['hit_ups_auto_country']) ? $general_settings['hit_ups_auto_country'] : 'HIT')]) ? $value[$general_settings['hit_ups_auto_country']]['currency'] : '';
		
		

		update_option('hit_ups_auto_mail_temp', $mail_temp);
		update_option('hit_ups_auto_main_settings', $general_settings);
		// echo "<pre>";print_r(get_option('hit_ups_auto_main_settings'));die();
		$success = 'Settings Saved Successfully.';
		// delete_option('hit_ups_auto_main_settings', $general_settings);
	}
	
	// $ups_currency = isset($general_settings['hit_ups_auto_currency'])?$general_settings['hit_ups_auto_currency']:'';
	// $wc_currency = isset($general_settings['hit_ups_auto_woo_currency'])?$general_settings['hit_ups_auto_woo_currency']:'';
		// echo "<pre>";print_r($general_settings);die();
if(!isset($general_settings['hit_ups_auto_integration_key']) || empty($general_settings['hit_ups_auto_integration_key'])){
			$random_nonce = wp_generate_password(16, false);
			set_transient( 'hitshipo_ups_nonce_temp', $random_nonce, HOUR_IN_SECONDS );
			
			$general_settings['hitshippo_ups_track_audit'] = sanitize_text_field(isset($_POST['hitshippo_ups_track_audit']) ? 'yes' : 'no');
			$general_settings['hitshippo_ups_daily_report'] = sanitize_text_field(isset($_POST['hitshippo_ups_daily_report']) ? 'yes' : 'no');
			$general_settings['hitshippo_ups_monthly_report'] = sanitize_text_field(isset($_POST['hitshippo_ups_monthly_report']) ? 'yes' : 'no');
			$general_settings['hitshippo_ups_shipo_signup'] = sanitize_text_field(isset($_POST['hitshippo_ups_shipo_signup']) ? $_POST['hitshippo_ups_shipo_signup'] : '');
			update_option('hit_ups_auto_main_settings', $general_settings);

			$link_hitshipo_request = json_encode(array('site_url' => site_url(),
				'site_name' => get_bloginfo('name'),
				'email_address' => $general_settings['hitshippo_ups_shipo_signup'],
				'nonce' => $random_nonce,
				'audit' => $general_settings['hitshippo_ups_track_audit'],
				'd_report' => $general_settings['hitshippo_ups_daily_report'],
				'm_report' => $general_settings['hitshippo_ups_monthly_report']
			));
			
			$link_site_url = "https://app.hitshipo.com/api/link-site.php";
			// $link_site_url = "http://localhost/hitshipov2/api/link-site.php";
			$link_site_response = wp_remote_post( $link_site_url , array(
					'method'      => 'POST',
					'timeout'     => 45,
					'redirection' => 5,
					'httpversion' => '1.0',
					'blocking'    => true,
					'headers'     => array('Content-Type' => 'application/json; charset=utf-8'),
					'body'        => $link_hitshipo_request,
					)
				);
				
				$link_site_response = ( is_array($link_site_response) && isset($link_site_response['body'])) ? json_decode($link_site_response['body'], true) : array();
				if($link_site_response){
					
				if($link_site_response['status'] != 'error'){
						$general_settings['hit_ups_auto_integration_key'] = sanitize_text_field($link_site_response['integration_key']);
						update_option('hit_ups_auto_main_settings', $general_settings);
						
						update_option('hitshipo_ups_working_status', 'start_working');
						$success = 'Site Linked Successfully.<br><br> It\'s great to have you here. ' . (isset($link_site_response['trail']) ? 'Your 60days Trail period is started. To know about this more, please check your inbox.' : '' ) . '<br><br><button class="button" type="submit">Back to Settings</button>';
					}else{
						$error = '<p style="color:red;">'. $link_site_response['message'] .'</p>';
						$success = '';
					}
				}else{
					$error = '<p style="color:red;">Failed to connect with HITShipo</p>';
					$success = '';
				}
				
		
		}
		
	}
	
	$general_settings['hit_ups_auto_currency'] = isset($value[(isset($general_settings['hit_ups_auto_country']) ? $general_settings['hit_ups_auto_country'] : 'HIT')]) ? $value[$general_settings['hit_ups_auto_country']]['currency'] : '';
	$general_settings['hit_ups_auto_woo_currency'] = get_option('woocommerce_currency');
	
	// $ups_currency = isset($general_settings['hit_ups_auto_currency'])?$general_settings['hit_ups_auto_currency']:'';
	// $wc_currency = isset($general_settings['hit_ups_auto_woo_currency'])?$general_settings['hit_ups_auto_woo_currency']:'';
?>

<style>
.notice{display:none;}
#multistepsform {
  width: 80%;
  margin: 50px auto;
  text-align: center;
  position: relative;
}
#multistepsform fieldset {
  background: white;
  text-align:left;
  border: 0 none;
  border-radius: 5px;
  box-shadow: 0 0 15px 1px rgba(0, 0, 0, 0.4);
  padding: 20px 30px;
  box-sizing: border-box;
  position: relative;
}
#multistepsform fieldset:not(:first-of-type) {
  display: none;
}
#multistepsform input[type=text], #multistepsform input[type=password], #multistepsform input[type=number], #multistepsform input[type=email], 
#multistepsform textarea {
  padding: 5px;
  width: 95%;
}
#multistepsform input:focus,
#multistepsform textarea:focus {
  border-color: #679b9b;
  outline: none;
  color: #637373;
}
#multistepsform .action-button {
  width: 100px;
  background: #341b14;
  font-weight: bold;
  color: #fab52c;
  transition: 150ms;
  border: 0 none;
  float:right;
  border-radius: 1px;
  cursor: pointer;
  padding: 10px 5px;
  margin: 10px 5px;
}
#multistepsform .action-button:hover,
#multistepsform .action-button:focus {
  box-shadow: 0 0 0 2px #f08a5d, 0 0 0 3px #ff976;
  color: #fff;
}
#multistepsform .fs-title {
  font-size: 15px;
  text-transform: uppercase;
  color: #2c3e50;
  margin-bottom: 10px;
}
#multistepsform .fs-subtitle {
  font-weight: normal;
  font-size: 13px;
  color: #666;
  margin-bottom: 20px;
}
#multistepsform #progressbar {
  margin-bottom: 30px;
  overflow: hidden;
  counter-reset: step;
}
#multistepsform #progressbar li {
  list-style-type: none;
  color: #ffb406;
  text-transform: uppercase;
  font-size: 9px;
  width: 16.5%;
  float: left;
  position: relative;
}
#multistepsform #progressbar li:before {
  content: counter(step);
  counter-increment: step;
  width: 20px;
  line-height: 20px;
  display: block;
  font-size: 10px;
  color: #fff;
  background: #ffb406;
  border-radius: 3px;
  margin: 0 auto 5px auto;
}
#multistepsform #progressbar li:after {
  content: "";
  width: 100%;
  height: 2px;
  background: #ffb406;
  position: absolute;
  left: -50%;
  top: 9px;
  z-index: -1;
}
#multistepsform #progressbar li:first-child:after {
  content: none;
}
#multistepsform #progressbar li.active {
  color: #341B14;
}
#multistepsform #progressbar li.active:before, #multistepsform #progressbar li.active:after {
  background: #341B14;
  color: white;
}
		</style>
<div style="text-align:center;margin-top:20px;"><img src="<?php echo plugin_dir_url(__FILE__); ?>ups_logo.png" style="width:150px;"></div>

<?php if($success != ''){
	echo '<form id="multistepsform" method="post"><fieldset>
    <center><h2 class="fs-title" style="line-height:27px;">'. $success .'</h2>
	</center></form>';
}else{
	?>
<!-- multistep form -->
<form id="multistepsform" method="post">
	
  <!-- progressbar -->
  <ul id="progressbar">
    <li class="active">Integration</li>
    <li>Setup</li>
    <li>Packing</li>
    <li>Rates</li>
    <li>Shipping Label</li>
    <li>HITShipo</li>

  </ul>
  <?php if($error == ''){

  ?>
  <!-- fieldsets -->
	<fieldset>
		<center><h2 class="fs-title">UPS Account Information</h2>
		
		<table style="padding-left:10px;padding-right:10px;">
		<td><span style="float:left;padding-right:10px;"><input type="checkbox" name="hit_ups_auto_test" <?php echo (isset($general_settings['hit_ups_auto_test']) && $general_settings['hit_ups_auto_test'] == 'yes') ? 'checked="true"' : ''; ?> value="yes" ><small style="color:gray"> Enable Test Mode.</small></span></td>
		<td><span style="float:right;padding-right:10px;"><input type="checkbox" name="hit_ups_auto_rates" <?php echo (isset($general_settings['hit_ups_auto_rates']) && $general_settings['hit_ups_auto_rates'] == 'yes') ? 'checked="true"' : ''; ?> value="yes" ><small style="color:gray"> Enable Live Shipping Rates.</small></span></td>
		<td><span style="float:right;padding-right:10px;"><input type="checkbox" name="hit_ups_auto_label_automation" <?php echo (isset($general_settings['hit_ups_auto_label_automation']) && $general_settings['hit_ups_auto_label_automation'] == 'yes') ? 'checked="true"' : ''; ?> value="yes" ><small style="color:gray"> Create Label automatically.</small></span></td>
		<td><span style="float:right;padding-right:10px;"><input type="checkbox" name="hit_ups_auto_developer_rate" <?php echo (isset($general_settings['hit_ups_auto_developer_rate']) && $general_settings['hit_ups_auto_developer_rate'] == 'yes') ? 'checked="true"' : ''; ?> value="yes" ><small style="color:gray"> Enable Debug Mode.</small></span></td>
		</table>
		</center>
		<table style="width:100%;">
		<tr><td colspan="2" style="padding:10px;"><hr></td></tr>
			<tr>
				<td style=" width: 50%;padding:10px;">
					<?php _e('UPS Login User ID','hit_ups_auto') ?>
					<input type="text" class="input-text regular-input" name="hit_ups_auto_site_id" value="<?php echo (isset($general_settings['hit_ups_auto_site_id'])) ? $general_settings['hit_ups_auto_site_id'] : ''; ?>">
				</td>
				<td style="padding:10px;">
				<?php _e('UPS Login Password','hit_ups_auto') ?>
				<input type="text" name="hit_ups_auto_site_pwd" value="<?php echo (isset($general_settings['hit_ups_auto_site_pwd'])) ? $general_settings['hit_ups_auto_site_pwd'] : ''; ?>">			
			</td>
			</tr>
			<tr>
				<td style=" width: 50%;padding:10px;">
					<?php _e('UPS Account number','hit_ups_auto') ?>
					<input type="text" class="input-text regular-input" name="hit_ups_auto_acc_no" value="<?php echo (isset($general_settings['hit_ups_auto_acc_no'])) ? $general_settings['hit_ups_auto_acc_no'] : ''; ?>">
				</td>
				<td style="padding:10px;">
				<?php _e('UPS Access Key','hit_ups_auto') ?>
				<input type="text" name="hit_ups_auto_access_key" value="<?php echo (isset($general_settings['hit_ups_auto_access_key'])) ? $general_settings['hit_ups_auto_access_key'] : ''; ?>">			
			</td>
			</tr>
			<tr>
				<td style="padding:10px;">
				<?php _e('UPS Weight Unit','hit_ups_auto') ?><br>
					<select name="hit_ups_auto_weight_unit" class="wc-enhanced-select" style="width:95%;padding:5px;">
						<option value="LB_IN" <?php echo (isset($general_settings['hit_ups_auto_weight_unit']) && $general_settings['hit_ups_auto_weight_unit'] == 'LB_IN') ? 'Selected="true"' : ''; ?>> LB & IN </option>
						<option value="KG_CM" <?php echo (isset($general_settings['hit_ups_auto_weight_unit']) && $general_settings['hit_ups_auto_weight_unit'] == 'KG_CM') ? 'Selected="true"' : ''; ?>> KG & CM </option>
					</select>
				</td>
				<td style="padding:10px;">
					<?php _e('Change UPS currency','hit_ups_auto') ?>
					<select name="hit_ups_auto_currency" style="width:95%;padding:5px;">
							
						<?php foreach($currencys as  $currency)
						{
							if(isset($general_settings['hit_ups_auto_currency']) && ($general_settings['hit_ups_auto_currency'] == $currency['currency']))
							{
								echo "<option value=".$currency['currency']." selected='true'>".$currency['currency']."</option>";
							}
							else
							{
								echo "<option value=".$currency['currency'].">".$currency['currency']."</option>";
							}
						}

						if (!isset($general_settings['hit_ups_auto_currency']) || ($general_settings['hit_ups_auto_currency'] != "NMP")) {
								echo "<option value=NMP>NMP</option>";
						}elseif (isset($general_settings['hit_ups_auto_currency']) && ($general_settings['hit_ups_auto_currency'] == "NMP")) {
								echo "<option value=NMP selected='true'>NMP</option>";
						} ?>
					</select>
				</td>
			</tr>
			<tr><td colspan="2" style="padding:10px;"><hr></td></tr>
			<?php if (isset($general_settings['hit_ups_auto_currency']) && $general_settings['hit_ups_auto_woo_currency'] != $general_settings['hit_ups_auto_currency'] ){
				?>
					<tr><td colspan="2" style="text-align:center;"><small><?php _e(' Your Website Currency is ','hit_ups_auto') ?> <b><?php echo $general_settings['hit_ups_auto_woo_currency'];?></b> and your UPS currency is <b><?php echo (isset($general_settings['hit_ups_auto_currency'])) ? $general_settings['hit_ups_auto_currency'] : '(Choose country)'; ?></b>. <?php echo ($general_settings['hit_ups_auto_woo_currency'] != $general_settings['hit_ups_auto_currency'] ) ? 'So you have to consider the converstion rate.' : '' ?></small>
						</td>
					</tr>
					
					<tr><td colspan="2" style="text-align:center;">
					<input type="checkbox" id="auto_con" name="hit_ups_auto_auto_con_rate" <?php echo (isset($general_settings['hit_ups_auto_auto_con_rate']) && $general_settings['hit_ups_auto_auto_con_rate'] == 'yes') ? 'checked="true"' : ''; ?> value="yes" ><?php _e('Auto Currency Conversion ','hit_ups_auto') ?>
						
					</td>
					</tr>
					
					<tr>
						<td style="padding:10px;text-align:center;" colspan="2" class="con_rate" >
							<?php _e('Exchange Rate','hit_ups_auto') ?><font style="color:red;">*</font> <?php echo "( ".$general_settings['hit_ups_auto_woo_currency']."->".$general_settings['hit_ups_auto_currency']." )"; ?>
							<br><input type="text" style="width:240px;" name="hit_ups_auto_con_rate" value="<?php echo (isset($general_settings['hit_ups_auto_con_rate'])) ? $general_settings['hit_ups_auto_con_rate'] : ''; ?>">
							<br><small style="color:gray;"><?php _e('Enter conversion rate.','hit_ups_auto') ?></small>
						</td>
					</tr>
					<tr><td colspan="2" style="padding:10px;"><hr></td></tr>
				<?php
			}
			?>
			
		</table>
		<?php if(isset($general_settings['hit_ups_auto_integration_key']) && $general_settings['hit_ups_auto_integration_key'] !=''){
			echo '<input type="submit" name="save" class="action-button" style="width:auto;float:left;" value="Save Changes" />';
		}

		?>
		<input type="button" name="next" class="next action-button" value="Next" />
    </fieldset>
	<fieldset>
		<center><h2 class="fs-title">Shipping Address Information</h2></center>
		
		<table style="width:100%;">
			<tr><td colspan="2" style="padding:10px;"><hr></td></tr>
			<tr>
				<td style=" width: 50%;padding:10px;">
					<?php _e('Shipper Name','hit_ups_auto') ?><font style="color:red;">*</font>
					<input type="text" name="hit_ups_auto_shipper_name" value="<?php echo (isset($general_settings['hit_ups_auto_shipper_name'])) ? $general_settings['hit_ups_auto_shipper_name'] : ''; ?>">
				</td>
				<td style="padding:10px;">
				<?php _e('Company Name','hit_ups_auto') ?><font style="color:red;">*</font>
				<input type="text" name="hit_ups_auto_company" value="<?php echo (isset($general_settings['hit_ups_auto_company'])) ? $general_settings['hit_ups_auto_company'] : ''; ?>">
				</td>
			</tr>
			<tr>
				<td style=" width: 50%;padding:10px;">
					<?php _e('Shipper Mobile / Contact Number','hit_ups_auto') ?><font style="color:red;">*</font>
					<input type="text" name="hit_ups_auto_mob_num" value="<?php echo (isset($general_settings['hit_ups_auto_mob_num'])) ? $general_settings['hit_ups_auto_mob_num'] : ''; ?>">
				</td>
				<td style="padding:10px;">
				<?php _e('Email Address of the Shipper','hit_ups_auto') ?><font style="color:red;">*</font>
				<input type="text" name="hit_ups_auto_email" value="<?php echo (isset($general_settings['hit_ups_auto_email'])) ? $general_settings['hit_ups_auto_email'] : ''; ?>">
				</td>
			</tr>
			<tr>
				<td style=" width: 50%;padding:10px;">
					<?php _e('Address Line 1','hit_ups_auto') ?><font style="color:red;">*</font>
					<input type="text" name="hit_ups_auto_address1" value="<?php echo (isset($general_settings['hit_ups_auto_address1'])) ? $general_settings['hit_ups_auto_address1'] : ''; ?>">
				</td>
				<td style="padding:10px;">
				<?php _e('Address Line 2','hit_ups_auto') ?>
				<input type="text" name="hit_ups_auto_address2" value="<?php echo (isset($general_settings['hit_ups_auto_address2'])) ? $general_settings['hit_ups_auto_address2'] : ''; ?>">
				</td>
			</tr>
			<tr>
				<td style=" width: 50%;padding:10px;">
					<?php _e('City of the Shipper from address','hit_ups_auto') ?><font style="color:red;">*</font>
					<input type="text" name="hit_ups_auto_city" value="<?php echo (isset($general_settings['hit_ups_auto_city'])) ? $general_settings['hit_ups_auto_city'] : ''; ?>">
				</td>
				<td style="padding:10px;">
				<?php _e('State (Two digit ISO code accepted.)','hit_ups_auto') ?><font style="color:red;">*</font>
				<input type="text" name="hit_ups_auto_state" value="<?php echo (isset($general_settings['hit_ups_auto_state'])) ? $general_settings['hit_ups_auto_state'] : ''; ?>">
				</td>
			</tr>
			<tr>
				<td style=" width: 50%;padding:10px;">
					<?php _e('Postal/Zip Code','hit_ups_auto') ?><font style="color:red;">*</font>
					<input type="text" name="hit_ups_auto_zip" value="<?php echo (isset($general_settings['hit_ups_auto_zip'])) ? $general_settings['hit_ups_auto_zip'] : ''; ?>">
				</td>
				<td style="padding:10px;">
				<?php _e('Country of the Shipper from Address','hit_ups_auto') ?><font style="color:red;">*</font>
				<select name="hit_ups_auto_country" class="wc-enhanced-select" style="width:95%;padding:5px;">
						<?php foreach($countires as $key => $value)
						{
							if(isset($general_settings['hit_ups_auto_country']) && ($general_settings['hit_ups_auto_country'] == $key))
							{
								echo "<option value=".$key." selected='true'>".$value."</option>";
							}
							else
							{
								echo "<option value=".$key.">".$value."</option>";
							}
						} ?>
					</select>
				</td>
			</tr>

			<tr>
				<td style=" width: 50%;padding:10px;">
					<?php _e('GSTIN','hit_ups_auto') ?><font style="color:red;">*</font>
					<input type="text" name="hit_ups_auto_gstin" value="<?php echo (isset($general_settings['hit_ups_auto_gstin'])) ? $general_settings['hit_ups_auto_gstin'] : ''; ?>">
				</td>
				<td style="padding:10px;">
				
				</td>
			</tr>
			
			<tr><td colspan="2" style="padding:10px;"><hr></td></tr>
		</table>
		<center><h2 class="fs-title">Are you gonna use Multi Vendor?</h2></center><br>
		<table style="padding-left:10px;padding-right:10px;">
			<td><span style="float:left;padding-right:10px;"><input type="checkbox" name="hit_ups_auto_v_enable" <?php echo (isset($general_settings['hit_ups_auto_v_enable']) && $general_settings['hit_ups_auto_v_enable'] == 'yes') ? 'checked="true"' : ''; ?> value="yes" ><small style="color:gray"> Use Multi-Vendor.</small></span></td>
			<td><span style="float:right;padding-right:10px;"><input type="checkbox" name="hit_ups_auto_v_rates" <?php echo (isset($general_settings['hit_ups_auto_v_rates']) && $general_settings['hit_ups_auto_v_rates'] == 'yes') ? 'checked="true"' : ''; ?> value="yes" ><small style="color:gray"> Get rates from vendor address.</small></span></td>
			<td><span style="float:right;padding-right:10px;"><input type="checkbox" name="hit_ups_auto_v_labels" <?php echo (isset($general_settings['hit_ups_auto_v_labels']) && $general_settings['hit_ups_auto_v_labels'] == 'yes') ? 'checked="true"' : ''; ?> value="yes" ><small style="color:gray"> Create Label from vendor address.</small></span></td>
			<td><span style="float:right;padding-right:10px;"><input type="checkbox" name="hit_ups_auto_v_email" <?php echo (isset($general_settings['hit_ups_auto_v_email']) && $general_settings['hit_ups_auto_v_email'] == 'yes') ? 'checked="true"' : ''; ?> value="yes" ><small style="color:gray"> Email the shipping labels to vendors.</small></span></td>
			</table>
		<table style="width:100%">
							
							
							<tr>
								<td style=" width: 50%;padding:10px;text-align:center;">
									<?php _e('Vendor role','hit_ups_auto') ?></h4><br>
									<select name="hit_ups_auto_v_roles[]" style="padding:5px;width:240px;">

										<?php foreach (get_editable_roles() as $role_name => $role_info){
											if(isset($general_settings['hit_ups_auto_v_roles']) && in_array($role_name, $general_settings['hit_ups_auto_v_roles'])){
												echo "<option value=".$role_name." selected='true'>".$role_info['name']."</option>";
											}else{
												echo "<option value=".$role_name.">".$role_info['name']."</option>";	
											}
											
										}
									?>

									</select><br>
									<small style="color:gray;"> To this role users edit page, you can find the new<br>fields to enter the ship from address.</small>
									
								</td>
							</tr>
							<tr><td style="padding:10px;"><hr></td></tr>
						</table>
		<?php if(isset($general_settings['hit_ups_auto_integration_key']) && $general_settings['hit_ups_auto_integration_key'] !=''){
			echo '<input type="submit" name="save" class="action-button" style="width:auto;float:left;" value="Save Changes" />';
		}

		?>
			<input type="button" name="next" class="next action-button" value="Next" />
			<input type="button" name="previous" class="previous action-button" value="Previous" />

    </fieldset>
	<fieldset>
		<center><h2 class="fs-title">Choose Packing ALGORITHM</h2></center><br/>
		<table style="width:100%">
	
			<tr>
				<td style=" width: 50%;padding:10px;">
					<?php _e('Select Package Type','hit_ups_auto') ?>
				</td>
				<td style="padding:10px;">
					<select name="hit_ups_auto_packing_type" style="padding:5px; width:95%;" id = "hit_ups_auto_packing_type" class="wc-enhanced-select" style="width:153px;" onchange="changepacktype(this)">
						<?php foreach($packing_type as $key => $value)
						{
							if(isset($general_settings['hit_ups_auto_packing_type']) && ($general_settings['hit_ups_auto_packing_type'] == $key))
							{
								echo "<option value=".$key." selected='true'>".$value."</option>";
							}
							else
							{
								echo "<option value=".$key.">".$value."</option>";
							}
						} ?>
					</select>
				</td>
			</tr>
			<tr>
				<td style=" width: 50%;padding:10px;">
				<?php _e('What is the Maximum weight to one package? (Weight based shipping only)','hit_ups_auto') ?><font style="color:red;">*</font>
				</td>
				<td style="padding:10px;">
					<input type="number" name="hit_ups_auto_max_weight" placeholder="" value="<?php echo (isset($general_settings['hit_ups_auto_max_weight'])) ? $general_settings['hit_ups_auto_max_weight'] : ''; ?>">
				</td>
			</tr>
		</table>
		
	<?php if(isset($general_settings['hit_ups_auto_integration_key']) && $general_settings['hit_ups_auto_integration_key'] !=''){
		echo '<input type="submit" name="save" class="action-button" style="width:auto;float:left;" value="Save Changes" />';
	}

	?>
	<input type="button" name="next" class="next action-button" value="Next" />
	<input type="button" name="previous" class="previous action-button" value="Previous" />

</fieldset>
<fieldset>
  <center><h2 class="fs-title">Rates</h2><br/>
  	<table style="padding-left:10px;padding-right:10px;">
	  <td><span style="float:left;padding-right:10px;"><input type="checkbox" name="hit_ups_auto_insure" <?php echo (isset($general_settings['hit_ups_auto_insure']) && $general_settings['hit_ups_auto_insure'] == 'yes') ? 'checked="true"' : ''; ?> value="yes" ><small style="color:gray"> UPS Insurance</small></span></td>
		<td><span style="float:right;padding-right:10px;"><input type="checkbox" name="hit_ups_auto_account_rates" <?php echo (isset($general_settings['hit_ups_auto_account_rates']) && $general_settings['hit_ups_auto_account_rates'] == 'yes') ? 'checked="true"' : ''; ?> value="yes" ><small style="color:gray"> Fetch UPS account rates.</small></span></td>
		
		</table></center>

  	<table style="width:100%">
			
			<tr><td colspan="2" style="padding:10px;"><hr></td></tr>
			<tr><td colspan="2" style="padding:10px;"><center><h2 class="fs-title">Do you wants to exclude countries?</h2></center></td></tr>
				
			<tr>
				<td colspan="2" style="text-align:center;padding:10px;">
					<?php _e('Exclude Countries','hit_ups_auto') ?><br>
					<select name="hit_ups_auto_exclude_countries[]" multiple="true" style="padding:5px;width:270px;">

					<?php
					$general_settings['hit_ups_auto_exclude_countries'] = empty($general_settings['hit_ups_auto_exclude_countries'])? array() : $general_settings['hit_ups_auto_exclude_countries'];
					foreach ($countires as $key => $county){
						if(isset($general_settings['hit_ups_auto_exclude_countries']) && in_array($key,$general_settings['hit_ups_auto_exclude_countries'])){
							echo "<option value=".$key." selected='true'>".$county."</option>";
						}else{
							echo "<option value=".$key.">".$county."</option>";	
						}
						
					}
					?>

					</select>
				</td>
				<tr><td colspan="2" style="padding:10px;"><hr></td></tr>
				
			</tr>
			<tr>
				<td style=" width: 50%;padding:10px;">
					<?php _e('Customer Classification','hit_ups_auto') ?>
				</td>
				<td style="padding:10px;">
					<select name="hit_ups_auto_customer_classification" style="padding:5px; width:95%;" id = "hit_ups_auto_customer_classification" class="wc-enhanced-select" style="width:153px;">
						<?php foreach($classification as $key => $value)
						{
							if(isset($general_settings['hit_ups_auto_customer_classification']) && ($general_settings['hit_ups_auto_customer_classification'] == $key))
							{
								echo "<option value=".$key." selected='true'>".$value."</option>";
							}
							else
							{
								echo "<option value=".$key.">".$value."</option>";
							}
						} ?>
					</select>
				</td>
			</tr>			
			<tr><td colspan="2" style="padding:10px;"><hr></td></tr>
		</table>
				<center><h2 class="fs-title">Shipping Services & Price adjustment</h2></center>
				<table style="width:100%;">
				
					<tr>
						<td>
							<h3 style="font-size: 1.10em;"><?php _e('Carries','hit_ups_auto') ?></h3>
						</td>
						<td>
							<h3 style="font-size: 1.10em;"><?php _e('Alternate Name for Carrier','hit_ups_auto') ?></h3>
						</td>
						
					</tr>
							<?php foreach($_carriers as $key => $value)
							{
								if($key == 'ups_11'){
									echo ' <tr><td colspan="4" style="padding:10px;"><hr></td></tr><tr ><td colspan="4" style="text-align:center;"><div style="padding:10px;border:1px solid gray;"><b><u>INTERNATIONAL SERVICES</u><br>
									This all are the services provided by UPS to ship internationally.<br>
									
								</b></div></td></tr> <tr><td colspan="4" style="padding:10px;"><hr></td></tr>';
								}else if($key == "ups_12"){
									echo ' <tr><td colspan="4" style="padding:10px;"><hr></td></tr><tr ><td colspan="4" style="text-align:center;"><div style="padding:10px;border:1px solid gray;"><b><u>DOMESTIC SERVICES</u><br>
										This all are the services provided by UPS to ship domestic.<br>
									</b></div>
									</td></tr> <tr><td colspan="4" style="padding:10px;"><hr></td></tr>';
								}else if ($key == 'ups_92'){
									echo ' <tr><td colspan="4" style="padding:10px;"><hr></td></tr><tr ><td colspan="4" style="text-align:center;"><b><u>OTHER SERVICES</u><br>
										
									</b>
									</td></tr> <tr><td colspan="4" style="padding:10px;"><hr></td></tr>';
								}
								
								echo '	<tr>
										<td>
										<input type="checkbox" value="yes" name="hit_ups_auto_carrier['.$key.']" '. ((isset($general_settings['hit_ups_auto_carrier'][$key]) && $general_settings['hit_ups_auto_carrier'][$key] == 'yes') ? 'checked="true"' : '') .' > <small>'.__($value,"hit_ups_auto").' - [ '.$key.' ]</small>
										</td>
										<td>
											<input type="text" name="hit_ups_auto_carrier_name['.$key.']" value="'.((isset($general_settings['hit_ups_auto_carrier_name'][$key])) ? __($general_settings['hit_ups_auto_carrier_name'][$key],"hit_ups_auto") : '').'">
										</td>
										</tr>';
							} ?>
							 <tr><td colspan="4" style="padding:10px;"><hr></td></tr>
				</table>
				<?php if(isset($general_settings['hit_ups_auto_integration_key']) && $general_settings['hit_ups_auto_integration_key'] !=''){
					echo '<input type="submit" name="save" class="action-button" style="width:auto;float:left;" value="Save Changes" />';
				}

				?>
			    <input type="button" name="next" class="next action-button" value="Next" />

  			<input type="button" name="previous" class="previous action-button" value="Previous" />

	
 </fieldset>
 <fieldset>
 <center><h2 class="fs-title">Configure Shipping Label</h2><br/>
 <table style="padding-left:10px;padding-right:10px;">

		
		<td><span style="float:right;padding-right:10px;"><input type="checkbox" name="hit_ups_auto_uostatus" <?php echo (isset($general_settings['hit_ups_auto_uostatus']) && $general_settings['hit_ups_auto_uostatus'] == 'yes') ? 'checked="true"' : ''; ?> value="yes" ><small style="color:gray"> Update order status by tracking</small></span></td>
		<td><span style="float:right;padding-right:10px;"><input type="checkbox" name="hit_ups_auto_trk_status_cus" <?php echo (isset($general_settings['hit_ups_auto_trk_status_cus']) && $general_settings['hit_ups_auto_trk_status_cus'] == 'yes') ? 'checked="true"' : ''; ?> value="yes" ><small style="color:gray"> UPS tracking informations to Customers</small></span></td>
        
		<td><span style="float:right;padding-right:10px;"><input type="checkbox" name="hit_ups_auto_send_trac" <?php echo (isset($general_settings['hit_ups_auto_send_trac']) && $general_settings['hit_ups_auto_send_trac'] == 'yes') ? 'checked="true"' : ''; ?> value="yes" ><small style="color:gray"> Send Tracking Number to Customer</small></span></td>
		</table>
		
		</center>
  <table style="width:100%">
  	<tr><td colspan="2" style="padding:10px;"><hr></td></tr>
		
	  <tr>
	  		<td style=" width: 50%;padding:10px;">
				<?php _e('Shipment Content','hit_ups_auto') ?><font style="color:red;">*</font>
				<input type="text" name="hit_ups_auto_ship_content" placeholder="" value="<?php echo (isset($general_settings['hit_ups_auto_ship_content'])) ? $general_settings['hit_ups_auto_ship_content'] : ''; ?>">
			</td>
			<td style="padding:10px;">
				<?php _e('Shipping Label Format (GIF)','hit_ups_auto') ?><font style="color:red;">*</font>
				<select name="hit_ups_auto_print_size" style="width:95%;padding:5px;">
					<?php foreach($print_size as $key => $value)
					{
						if(isset($general_settings['hit_ups_auto_print_size']) && ($general_settings['hit_ups_auto_print_size'] == $key))
						{
							echo "<option value=".$key." selected='true'>".$value."</option>";
						}
						else
						{
							echo "<option value=".$key.">".$value."</option>";
						}
					} ?>
				</select>
			</td>
		</tr>
		
		<tr>
			<td style=" width: 50%;padding:10px;">
			<?php _e('Email address to sent Shipping label','hit_ups_auto') ?><font style="color:red;">*</font>
			<input type="text" name="hit_ups_auto_label_email" placeholder="" value="<?php echo (isset($general_settings['hit_ups_auto_label_email'])) ? $general_settings['hit_ups_auto_label_email'] : ''; ?>"><br>
			<small style="color:gray;"> While creating the shipping label at the HITSHIPO, It will sent the label, invoice to the given e-mail. If you don't need this then leave this field empty.</small>
			</td>
		
			<td style="padding:10px;">
				<?php _e('Who will pay the duty payment','hit_ups_auto') ?><font style="color:red;">*</font>
				<select name="hit_ups_auto_duty_payment" style="width:95%;padding:5px;">
					<?php foreach($duty_payment_type as $key => $value)
					{
						if(isset($general_settings['hit_ups_auto_duty_payment']) && ($general_settings['hit_ups_auto_duty_payment'] == $key))
						{
							echo "<option value=".$key." selected='true'>".$value."</option>";
						}
						else
						{
							echo "<option value=".$key.">".$value."</option>";
						}
					} ?>
				</select><br>
			</td>
		</tr>
	
			<tr>
				<td style=" width: 50%;padding:10px;">
					<?php _e('Email Subject','hit_ups_auto') ?><font style="color:red;">*</font>
					<input type="text" name="hit_ups_auto_mail_sub" value="<?php echo (isset($general_settings['hit_ups_auto_mail_sub'])) ? $general_settings['hit_ups_auto_mail_sub'] : ''; ?>">
				</td>
				<td style="padding:10px;">
				<?php _e('Email Template','hit_ups_auto') ?><font style="color:red;">*</font>
				<p><label for="hit_ups_auto_mail_temp" class="screen-reader-text"></label></p>
<p><textarea class="widefat" rows="4" cols="4" style="width:90%;height:230px;" id="hit_ups_auto_mail_temp"  name="hit_ups_auto_mail_temp"><?php echo (isset($mail_temp['hit_ups_auto_mail_temp'])) ? $mail_temp['hit_ups_auto_mail_temp'] : 'Default'; ?></textarea></p>
				</td>
			</tr>

		<tr><td colspan="2" style="padding:10px;"><hr></td></tr>
		</table>
		
		
		<?php if(isset($general_settings['hit_ups_auto_integration_key']) && $general_settings['hit_ups_auto_integration_key'] !=''){
			echo '<input type="submit" name="save" class="action-button" style="width:auto;float:left;" value="Save Changes" />';
		}

		?>
		<input type="button" name="next" class="next action-button" value="Next" />

	<input type="button" name="previous" class="previous action-button" value="Previous" />

	
 </fieldset>
  <?php } 
?>
<fieldset>
    <center><h2 class="fs-title">LINK HITSHIPO</h2><br>
	<img src="<?php echo plugin_dir_url(__FILE__); ?>hups.png">
	<h3 class="fs-subtitle">HITShipo is performing all the operations in its own server. So it won't affect your page speed or server usage.</h3>
	<?php 
		if(!isset($general_settings['hit_ups_auto_integration_key']) || empty($general_settings['hit_ups_auto_integration_key'])){
		?>
	<table style="padding-left:10px;padding-right:10px;">
		<td><span style="float:left;padding-right:10px;"><input type="checkbox" name="hitshippo_ups_track_audit" <?php echo (isset($general_settings['hitshippo_ups_track_audit']) && $general_settings['hitshippo_ups_track_audit'] == 'yes') ? 'checked="true"' : ''; ?> value="yes" ><small style="color:gray"> Track shipments everyday & Update the order status with Audit shipments.</small></span></td>
		<td><span style="float:right;padding-right:10px;"><input type="checkbox" name="hitshippo_ups_daily_report" <?php echo (isset($general_settings['hitshippo_ups_daily_report']) && $general_settings['hitshippo_ups_daily_report'] == 'yes') ? 'checked="true"' : ''; ?> value="yes" ><small style="color:gray"> Daily Report.</small></span></td>
		<td><span style="float:right;padding-right:10px;"><input type="checkbox" name="hitshippo_ups_monthly_report" <?php echo (isset($general_settings['hitshippo_ups_monthly_report']) && $general_settings['hitshippo_ups_monthly_report'] == 'yes') ? 'checked="true"' : ''; ?> value="yes" ><small style="color:gray"> Monthly Report.</small></span></td>
		</table></center>
    <table style="width:100%;text-align:center;">
	<tr><td style="padding:10px;"><hr></td></tr>
	
	<tr>
		<td style=" width: 50%;padding:10px;">
			<?php _e('Email address to signup / check the registered email.','hit_ups_auto') ?><font style="color:red;">*</font><br>
			<input type="email" style="width:330px;" placeholder="Enter email address" name="hitshippo_ups_shipo_signup" required placeholder="" value="<?php echo (isset($general_settings['hitshippo_ups_shipo_signup'])) ? $general_settings['hitshippo_ups_shipo_signup'] : ''; ?>">
		</td>
		
	</tr>
	
	<tr><td style="padding:10px;"><hr></td></tr>
	
	</table>

	<?php }else{
		?>
		<p style="font-size:14px;line-height:24px;">
			Site Linked Successfully. <br><br>
		It's great to have you here. Your account has been linked successfully with HITSHIPO. <br><br>
Make your customers happier by reacting faster and handling their service requests in a timely manner, meaning higher store reviews and more revenue.</p>
		<?php
		echo '</center>';
	}
	?>
	<?php echo '<center>' . $error . '</center>'; ?>
	
	<?php if(!isset($general_settings['hit_ups_auto_integration_key']) || empty($general_settings['hit_ups_auto_integration_key'])){
					echo '<input type="submit" name="save" class="action-button" style="width:auto;" value="SAVE & START 60-DAY TRAIL" />';
					echo '<input type="button" name="previous" class="previous action-button" value="Previous" />';
				 }else{
					 echo'<input type="submit" name="save" class="action-button" style="width:auto;" value="Save Changes" />';
					 echo '<input type="button" name="previous" class="previous action-button" value="Previous" />';
				
				
  }
  ?>
  
  </fieldset>
<?php
} ?>
</form>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script type="text/javascript">
var current_fs, next_fs, previous_fs;
var left, opacity, scale;
var animating;
jQuery(".next").click(function () {
  if (animating) return false;
  animating = true;

  current_fs = jQuery(this).parent();
  next_fs = jQuery(this).parent().next();
  jQuery("#progressbar li").eq(jQuery("fieldset").index(next_fs)).addClass("active");
  next_fs.show();
  document.body.scrollTop = 0; // For Safari
  document.documentElement.scrollTop = 0; 
  current_fs.animate(
    { opacity: 0 },
    {
      step: function (now, mx) {
        scale = 1 - (1 - now) * 0.2;
        left = now * 50 + "%";
        opacity = 1 - now;
        current_fs.css({
          transform: "scale(" + scale + ")"});
        next_fs.css({ left: left, opacity: opacity });
      },
      duration: 0,
      complete: function () {
        current_fs.hide();
        animating = false;
      },
      //easing: "easeInOutBack"
    }
  );
});

jQuery(".previous").click(function () {
  if (animating) return false;
  animating = true;

  current_fs = jQuery(this).parent();
  previous_fs = jQuery(this).parent().prev();
  jQuery("#progressbar li")
    .eq(jQuery("fieldset").index(current_fs))
    .removeClass("active");

  previous_fs.show();
  current_fs.animate(
    { opacity: 0 },
    {
      step: function (now, mx) {
        scale = 0.8 + (1 - now) * 0.2;
        left = (1 - now) * 50 + "%";
        opacity = 1 - now;
        current_fs.css({ left: left });
        previous_fs.css({
          transform: "scale(" + scale + ")",
          opacity: opacity
        });
      },
      duration: 0,
      complete: function () {
        current_fs.hide();
        animating = false;
      },
      //easing: "easeInOutBack"
    }
  );
});

jQuery(".submit").click(function () {
  return false;
});
jQuery(document).ready(function(){
	

    if('#checkAll'){
    	jQuery('#checkAll').on('click',function(){
            jQuery('.ups_auto_service').each(function(){
                this.checked = true;
            });
    	});
    }
    if('#uncheckAll'){
		jQuery('#uncheckAll').on('click',function(){
            jQuery('.ups_auto_service').each(function(){
                this.checked = false;
            });
    	});
	}

	// if (ups_curr != null && ups_curr == woo_curr) {
	// 	jQuery('.con_rate').each(function(){
	// 	jQuery('.con_rate').hide();
	//     });
	// }else{
	// 	if($("#auto_con").prop('checked') == true){
	// 		jQuery('.con_rate').hide();
	// 	}else{
	// 		jQuery('.con_rate').each(function(){
	// 		jQuery('.con_rate').show();
	// 	    });
	// 	}
	// }

	// jQuery("#auto_con").change(function() {
	//     if(this.checked) {
	//         jQuery('.con_rate').hide();
	//     }else{
	//     	if (ups_curr != woo_curr) {
	//     		jQuery('.con_rate').show();
	//     	}
	//     }
	// });

	jQuery("#hit_ups_auto_cod").change(function() {
		if(this.checked) {
	        jQuery('#col_type').show();
	    }else{
	    	jQuery('#col_type').hide();
	    }
	});

	// if (ups_cod != "yes") {
	// 	jQuery('#col_type').hide();
	// }

});


</script>