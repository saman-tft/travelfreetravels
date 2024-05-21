<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// $config['master_module_list']	= array(
// META_AIRLINE_COURSE => 'flight',
// META_TRANSFERS_COURSE => 'transferHotel',
// META_ACCOMODATION_COURSE => 'hotel',
// /*META_BUS_COURSE => 'bus',
// */
// META_TRANSFERV1_COURSE=>'transfers',

// //META_CAR_COURSE=>'car',
// META_SIGHTSEEING_COURSE=>'activities',
// META_PACKAGE_COURSE => 'package',
// META_ACCOMODATION_CRS => 'villasapartment'
// );

$config['master_module_list']	= array(
META_AIRLINE_COURSE => 'flight',
// META_TRANSFERS_COURSE => 'transferHotel',
META_ACCOMODATION_COURSE => 'hotel',
/*META_BUS_COURSE => 'bus',
*/
// META_TRANSFERV1_COURSE=>'transfers',

//META_CAR_COURSE=>'car',
// META_SIGHTSEEING_COURSE=>'activities',
// META_PACKAGE_COURSE => 'package',
// META_ACCOMODATION_CRS => 'villasapartment'
);
/******** Current Module ********/
$config['current_module'] = 'b2b';

$config['verify_domain_balance'] = true;

/******** PAYMENT GATEWAY START ********/
//To enable/disable PG
$config['enable_payment_gateway'] = false;
$config['agent_pay'] = 'Razorpay';
$config['active_payment_gateway'] =  array(
	'Fonepay',  'Connect', 'khalti', 'esewa', 'nica', 'khalti', 'esewa'
);
$config['active_payment_system'] = 'test';//test/live
$config['payment_gateway_currency'] = 'NPR';//INR
/******** PAYMENT GATEWAY END ********/

/**
 * 
 * Enable/Disable caching for search result
 */
$config['cache_hotel_search'] = true;
$config['cache_sightseeing_search'] = true;
$config['cache_flight_search'] = false;
$config['cache_bus_search'] = true;
$config['cache_car_search'] = false;

/**
 * Number of seconds results should be cached in the system
 */
$config['cache_hotel_search_ttl'] = 300;
$config['cache_flight_search_ttl'] = 1900;
$config['cache_bus_search_ttl'] = 300;
$config['cache_car_search_ttl'] = 300;
$config['cache_sightseeing_search_ttl'] = 300;



/*$config['lazy_load_hotel_search'] = true;*/
$config['hotel_per_page_limit'] = 20;
$config['car_per_page_limit'] = 200;

/*
	search session expiry period in seconds
*/
$config['flight_search_session_expiry_period'] = 600;
$config['flight_search_session_expiry_alert_period'] = 300;

// configure the test and live creds for protect insurance api
$config['protect_api_mode'] = 'test';
