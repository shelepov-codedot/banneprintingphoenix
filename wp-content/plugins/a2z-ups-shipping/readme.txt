=== Automated UPS Shipping for WooCommerce ===
Contributors: hitstacks
Tags: UPS, UPS Shipping, UPS Shipping Method, UPS WooCommerce, UPS Plugin
Requires at least: 4.0.1
Tested up to: 5.7
Requires PHP: 5.6
Stable tag: 3.1.1
License: GPLv3 or later License
URI: http://www.gnu.org/licenses/gpl-3.0.html

UPS shipping plugin, integrate seamlessly with UPS for real-time shipping rates, label printing, automatic tracking number email generation, shipping rate previews on product pages, and much more.

== Description ==

UPS shipping plugin, integrate seamlessly with UPS for real-time shipping rates, label printing, automatic tracking number email generation, shipping rate previews on product pages, and much more.

= What this product does for you = 

> Provides a shipping method suitable to your customers

The most popular UPS shipping Plugin for WooCommerce that offers label printing (Premium), a custom boxing algorithm (Premium), shipping rate preview (no login needed), and more, you can be sure that your customers always pay just the right amount for delivery and you'll save enough time to focus on what really matters. 

Our highly customizeable and powerful shipping modules provide consistent, easy-to-use and flexible shipping for any shop, including shipping rate previews on product pages and much more.

= Features =

* Display UPS shipping rates on the product page without requiring the customer to log-in.
 
* Get real time shipping rates directly from the UPS systems based on your company's UPS account.
 
* (Premium) Generate & print labels directly from the backoffice order page, and automatically send tracking number email.
 
* Dimensional weight and negotiated (discounted) rates are supported.
 
* Shipping rates are calculated by weight and dimensions or one of the UPS boxes:
* Single Box - Assign one box size which will be used for all products.
* (Premium) Multiple Boxes (Fixed Size) - Define how many products can fit in a box (number), or calculate only by weight.
* (Premium) Multiple Boxes (Product Dimensions) - Define all the box sizes you use for shipping, assign dimensions to your products, and the module will automatically calculate (using an algorithm we developed) how many boxes are needed to fit all the products (always trying to use the smallest / lowest number of packages).
 
* Option to set free shipping by Product, Category, Manufacturer or Supplier.
 
* All UPS Services and package types are supported, and you can select which shipping options should be available per Zone. 
 
* Each shipping method can have its own Free Shipping Limit, Additional Fee, and Insurance.
 
* Smart caching system is used for maximum speed.

* Enable/disable testing mode in module configuration.


Plugin Tags: <blockquote>UPS, UPS Shipping, UPS Shipping Method, UPS WooCommerce, ups Ground, UPS Saver, Domestic UPS, uos for woocommerce, ups for worldwide shiping, ups plugin, create shipment, ups shipping, ups shipping rates</blockquote>


= About UPS =

United Parcel Service is an American multinational package delivery and supply chain management company. The global logistics company is headquartered in the U.S. city of Sandy Springs, Georgia, which is a part of the Greater Atlanta metropolitan area. 

= About [HITShipo](https://hitstacks.com/hitshipo.php) =

We are Web Development Company in France. We are planning for High Quality WordPress, Woocommerce, Edd Downloads Plugins. We are launched on 4th Nov 2018. 

= What a2Z Plugins Group Tell to Customers? =

> "Make Your Shop With Smile"

Useful filters:

1) Customs Rates

>function ups_shipping_cost_conversion($ship_cost, $pack_weight = 0, $to_country = "", $rate_code = ""){
>    $sample_flat_rates = array("GB"=>array( //Use ISO 3166-1 alpha-2 as country code
>								"weight_from" => 10,
>								"weight_upto" => 30,
>								"rate" => 2000,
>								"rate_code" => "ups_12", //You can add UPS service type and use it based on your's need. Get this from our plugin's configuration (services tab).
>							),
>								"US"=>array(
>								"weight_from" => 1,
>								"weight_upto" => 30,
>								"rate" => 5000,
>								),
>							);
>
>		if(!empty($to_country) && !empty($sample_flat_rates)){
>			if(isset($sample_flat_rates[$to_country]) && ($pack_weight >= $sample_flat_rates[$to_country]['weight_from']) && ($pack_weight <= $sample_flat_rates[$to_country]['weight_upto'])){
>				$flat_rate = $sample_flat_rates[$to_country]['rate'];
>				return $flat_rate;
>			}else{
>				return $ship_cost;
>			}
>		}else{
>				return $ship_cost;
>		}
>
>    }
>    add_filter('hitstacks_ups_shipping_cost_conversion','ups_shipping_cost_conversion',10,4);

(Note: Flat rate filter example code will set flat rate for all UPS carriers. Have to add code to check and alter rate for specific carrier. While copy paste the code from worpress plugin page may throw error "Undefined constant". It can be fixed by replacing backtick (`) to apostrophe (') )

2) To Sort the rates from Lowest to Highest

> add_filter( 'woocommerce_package_rates' , 'hitshipo_sort_shipping_methods', 10, 2 );
> function hitshipo_sort_shipping_methods( $rates, $package ) {
>   if ( empty( $rates ) ) return;
>       if ( ! is_array( $rates ) ) return;
> uasort( $rates, function ( $a, $b ) { 
>   if ( $a == $b ) return 0;
>       return ( $a->cost < $b->cost ) ? -1 : 1; 
>  } );
>       return $rates;
> }


== Screenshots ==
1. Configuration - UPS Details.
2. Configuration - UPS Shipper Address.
3. Configuration - UPS Rate Section.
4. Configuration - UPS Available Services.
5. Output - UPS Shipping Rates in Shop.
6. Output - My Account Page Shipping Section.
5. Output - Edit Order Page Shipping Section.


== Changelog ==

= 3.1.1
*Release Date - 05 June 2021*
	> Minor bug fix

= 3.1.0
*Release Date - 08 may 2021*
	> Added Customer Classification For US

= 3.0.5
*Release Date - 06 may 2021*
	> Minor Bug Fixes

= 3.0.4
*Release Date - 13 Apr 2021*
	> Minor Email label value Bug Fix
	
= 3.0.3
*Release Date - 31 Mar 2021*
	> Added Save & Start 60-day trail button

= 3.0.2
*Release Date - 27 Mar 2021*
	> Minor Bug Fix

= 3.0.1
*Release Date - 24 Mar 2021*
	> Minor Bug Fix

= 3.0.0
*Release Date - 20 Mar 2021*
	> New UI for UPS

= 2.3.9
*Release Date - 19 Jan 2021*
	> Bugfixes.

= 2.3.8
*Release Date - 23 December 2020*
	> Fixed order data not sending to Shipo while changing carrier name.

= 2.3.7
*Release Date - 19 December 2020*
	> Added surcharge flag to rate filter.

= 2.3.6
*Release Date - 12 December 2020*
	> Minor bug fixes.

= 2.3.5
*Release Date - 28 November 2020*
	> Added custom rates filter.

= 2.3.4
*Release Date - 27 November 2020*
	> Minor bug Fixes.

= 2.3.3
*Release Date - 24 November 2020*
	> Minor bug Fixes.

= 2.3.2
*Release Date - 28 October 2020*
	> Minor bug Fixes.

= 2.3.1
*Release Date - 27 October 2020*
	> Minor bug Fixes.

= 2.3.0
*Release Date - 17 October 2020*
	> Exclude Country for Rates.

= 2.2.8
*Release Date - 01 Aug 2020*
	> fixes some minor bug.

= 2.2.7
*Release Date - 22 Jul 2020*
	> fixes some minor bug.

= 2.2.6
*Release Date - 16 Jul 2020*
	> fixes for multivendor.

= 2.2.5
*Release Date - 16 Jul 2020*
	> includes Bugfixes.

= 2.2.4
*Release Date - 11 Jul 2020*
	> includes Bugfixes.

= 2.2.3
*Release Date - 4 Jul 2020*
	> includes Bugfixes.

= 2.2.2
*Release Date - 3 Jul 2020*
	> includes Bugfixes.

= 2.2.1
*Release Date - 28 Jun 2020*
	> variable product weight issue fixed.

= 2.2.0
*Release Date - 17 Jun 2020*
	> Added Feature Sending tracking number to Customer.

= 2.1.2
*Release Date - 13 Jun 2020*
	> Bugfixes.

= 2.1.1
*Release Date - 13 Jun 2020*
	> Bugfixes.

= 2.1.0
*Release Date - 5 Jun 2020*
	> Added tracking in front office.

= 2.0.6 =
*Release Date - 2 Jun 2020*
	> Multi vendor released.
	
= 2.0.5 =
*Release Date - 9 May 2020*
	> sent shiping price to shipo & acc_rates

= 2.0.4 =
*Release Date - 22 April 2020*
	> sent shiping price to shipo

= 2.0.3 =
*Release Date - 21 April 2020*
	> Minor Bug fixes

= 2.0.2 =
*Release Date - 17 April 2020*
	> changed weight and dim conversion

= 2.0.1 =
*Release Date - 11 March 2020*
	> changed service pack type default set to customer supplied pack

= 2.0.0 =
*Release Date - 07 March 2020*
	> Initial Version compatibility with HITShipo

= 1.0.0 =
*Release Date - 11 November 2018*
	> Initial Version
