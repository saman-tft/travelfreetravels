<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


if(ENVIRONMENT == 'testing')
{
	 $config['master_module_list']	= array(
		META_AIRLINE_COURSE => 'flights',
		META_TRANSFER_COURSE => 'transfershotelbed',
		META_ACCOMODATION_COURSE => 'hotels',
		
	//	META_TRANSFERV1_COURSE=>'transfers',

	//	META_SIGHTSEEING_COURSE=>'activities',
		META_CAR_COURSE=>'car',
		/*META_BUS_COURSE => 'buses',*/
		META_PACKAGE_COURSE => 'holidays',
		META_ACCOMODATION_CRS => 'Villas & Apts',

		);
}
else
{
	//changes removed broken modules for now
	 $config['master_module_list']	= array(
		META_AIRLINE_COURSE => 'flights',
		// META_TRANSFER_COURSE => 'transfershotelbed',
		META_ACCOMODATION_COURSE => 'hotels',
		
		// META_TRANSFERV1_COURSE=>'transfers',
		/*META_BUS_COURSE => 'buses',	*/	
		//META_CAR_COURSE=>'car',
		// META_SIGHTSEEING_COURSE=>'activities',
		// META_PACKAGE_COURSE => 'holidays',
		// META_ACCOMODATION_CRS => 'Villas & Apts',

		);
}

/******** Current Module ********/
$config['current_module'] = 'b2c';

$config['load_minified'] = false;

$config['verify_domain_balance'] = false;

/******** PAYMENT GATEWAY START ********/
//To enable/disable PG
$config['enable_payment_gateway'] = true;
// $config['active_payment_gateway'] = 'Razorpay';
$config['active_payment_gateway'] = array('Fonepay', 'Connect', 'khalti', 'esewa', 'nica');
//$config['active_payment_gateway_sec'] = 'connectpay';
//$config['mpesa_active_payment_gateway'] = 'MPESA';
//if(ENVIRONMENT == 'production')

//test/live
 $config['active_payment_system'] = 'live';//test/live
$config['payment_gateway_currency'] = 'NPR';//INR
//$config['mpesa_payment_gateway_currency'] = 'USD';//INR




/******** PAYMENT GATEWAY END ********/

/**
 * 
 * Enable/Disable caching for search result
 */
$config['cache_hotel_search'] = true;//right now not needed
$config['cache_flight_search'] = false;
$config['cache_bus_search'] = true;
$config['cache_car_search'] = false;
$config['cache_sightseeing_search'] = true;
$config['cache_transferv1_search'] = true;

/**
 * Number of seconds results should be cached in the system
 */
$config['cache_hotel_search_ttl'] = 300;
$config['cache_flight_search_ttl'] = 300;
$config['cache_bus_search_ttl'] = 600;
$config['cache_car_search_ttl'] = 300;
$config['cache_sightseeing_search_ttl'] = 300;
$config['cache_transferv1_search_ttl'] = 300;

/*$config['lazy_load_hotel_search'] = true;*/
$config['hotel_per_page_limit'] = 20;
$config['car_per_page_limit'] = 200;
$config['sightseeing_page_limit'] = 50;
$config['transferv1_page_limit'] = 50;

// changes start session: set constants for session expiry
/*
	search session expiry period in seconds
*/
	// 660
$config['flight_search_session_expiry_period'] = 600;//600
$config['flight_search_session_expiry_alert_period'] = 300;//300
// the values in the below set config allows a user total of 7 minutes, flight_search_session_expiry_period + payent_gateway_selection_page_add_sess_time = 300+120 seconds
// session timeout change 1: Added all the below lines and commented above lines
// $config['flight_search_session_expiry_period'] = 500; // actual session expiry time limit
// $config['flight_search_session_expiry_alert_period'] = 240; // remaining session expiry time to alert users
// $config['search_result_page_sub_sess_time'] = 100; // time to subtract for search_result_page
// $config['booking_page_add_sess_time'] = 0; // add extra time to provide user for booking in booking page
// $config['payent_gateway_selection_page_add_sess_time'] = 120; // add extra time + booking_page_add_sess_time for payment gateway selection page, this value should be minimum $config['booking_page_add_sess_time']
// // changes start session: added this config item
// $config['flight_search_session_sub_start_time'] = 7000; //time to delay the check session expiry to start
// // changes end session: set constants for session expiry