<?php
require_once BASEPATH . 'libraries/Common_Api_Grind.php';
/**
 * Provab Common Functionality For API Class
 *
 *
 * @package Provab
 * @subpackage provab
 * @category Libraries
 * @author Arjun J<arjun.provab@gmail.com>
 * @link http://www.provab.com
 */
abstract class Common_Api_Transferv1 extends Common_Api_Grind {
	function __construct($module, $api) {
		parent::__construct ( $module, $api );
	}
	static $app_reference = false;
	
	/**
	 *
	 * @param string $journey_number
	 *        	//onward, return ,multi-city
	 * @param string $origin_code
	 *        	origin airport code
	 * @param string $destination_code
	 *        	destination airport code
	 * @param
	 *        	date string $departure_dt Y-m-d H:i:s
	 * @param
	 *        	date string $arrival_dt Y-m-d H:i:s
	 * @param string $operator_code
	 *        	//operator code like 9W for Jet Airways
	 * @param string $operator_name
	 *        	//flight operator name like Jet Airways
	 * @param string $flight_number
	 *        	//flight number
	 * @param number $no_of_stops
	 *        	// no of stops in journey
	 * @param string $cabin_class
	 *        	//class of journey like ECONOMY
	 * @param string $origine_name
	 *        	// origin city airport name
	 * @param string $destination_name
	 *        	// destination city airport name
	 * @param number $duration
	 *        	// journey duration in seconds
	 */
	/**
	 * Checks Booking Source is active or not
	 */
	protected  function is_active_booking_source()
	{
		$data['status'] = SUCCESS_STATUS;
		$data['message'] = '';
       
		if(valid_array($this->config) == false){
			$data['status'] = FAILURE_STATUS;
			$data['message'] = $this->booking_source.': Booking Source is not active';
		}

		return $data;
	}
}