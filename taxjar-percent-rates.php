<?php
/*
Plugin Name: TaxJar Percent Rates
Plugin URI: http://patternsinthecloud.com
Description: Calculate TaxJar taxes using standard WooCommerce percent rates
Version: 1.0
Author: Patterns in the Cloud
Author URI: http://patternsinthecloud.com
License: Single-site
*/

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active( 'woocommerce/woocommerce.php' ) && is_plugin_active( 'taxjar-simplified-taxes-for-woocommerce/taxjar-woocommerce.php' ) ) {
	
	function taxjar_percent_rates_install() {

	}
	register_activation_hook( __FILE__, 'taxjar_percent_rates_install' );
	
	function taxjar_percent_rates_deactivate() {
	}
	register_deactivation_hook( __FILE__, 'taxjar_percent_rates_deactivate' );
	
	function taxjar_percent_rates_uninstall() {

	}
	register_uninstall_hook( __FILE__, 'taxjar_percent_rates_uninstall' );
	
	function taxjar_percent_rates_calc_tax( $taxjar_taxes, $price, $taxjar_rates, $price_includes_tax, $suppress_rounding ) {
		// Convert TaxJar rates to percent
		$rates = array();
		foreach ( $taxjar_rates as $key => $rate ) {
			$rates[ $key ] = $rate;
			if ( $rate['rate'] < 1 ) {
				$rates[ $key ]['rate'] = 100*$rate['rate'];
			}
		}
		
		// Remove this filter
		remove_filter( 'woocommerce_calc_tax', 'taxjar_percent_rates_calc_tax', 100 );
		// Calculate taxes
		$taxes = WC_Tax::calc_tax( $price, $rates, $price_includes_tax, $suppress_rounding );
		// Re-apply this filter
		add_filter( 'woocommerce_calc_tax', 'taxjar_percent_rates_calc_tax', 100, 5 );
		return $taxes;
	}
	add_filter( 'woocommerce_calc_tax', 'taxjar_percent_rates_calc_tax', 100, 5 );
}
