<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['master_module_list']	= array(	
	META_ACCOMODATION_COURSE => 'hotel',
	META_AIRLINE_COURSE => 'flight',	
	//META_AIRLINE_PJ_COURSE=>'privatejet',
	//META_BUS_COURSE => 'bus',
	META_PACKAGE_COURSE => 'holiday',
	META_SIGHTSEEING_COURSE=>'activities',
	//META_CAR_COURSE => 'car',
	META_TRANSFERV1_COURSE=>'transfers',
	//	META_TRANSFERCRS_COURSE=>'transfers_crs',
	//META_CAR_PC_COURSE=>'privatecar'

);
/******** Current Module ********/
$config['current_module'] = 'admin';
$config['active_payment_gateway'] = array('Fonepay', 'Connect', 'khalti', 'esewa', 'nica');
