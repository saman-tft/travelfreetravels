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
abstract class Common_Api_Flight extends Common_Api_Grind {
	function __construct($module, $api) {
		parent::__construct ( $module, $api );
	}
	var $DRM = 'special_fare'; // dynamic result match
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
	protected function format_summary_array($journey_number, $origin_code, $destination_code, $departure_dt, $arrival_dt, $operator_code, $operator_name, $flight_number, $no_of_stops, $cabin_class = '', $origine_name = '', $destination_name = '', $duration = '', $is_leg = true, $attr = array(), $org_terminal = '', $des_terminal = '', $carrier_code = '',$fare_class='',& $details, $cabin_bag = '') {
		$CI = & get_instance ();
		
		$departure_dt = preg_replace('/^\p{Z}+|\p{Z}+$/u', '', $departure_dt);
		$arrival_dt = preg_replace('/^\p{Z}+|\p{Z}+$/u', '', $arrival_dt);
		
		$dts = strtotime ( trim($departure_dt) );
		$ats = strtotime ( trim($arrival_dt) );
		
		$departure_date = date ( 'd M Y', $dts );
		$departure_time = date ( 'H:i', $dts );
		
		$arrival_date = date ( 'd M Y', $ats );
		$arrival_time = date ( 'H:i', $ats );
		
		$org_loc_details = $CI->db_cache_api->get_airport_details ( $origin_code, date ( 'm', $dts ) );
		$des_loc_details = $CI->db_cache_api->get_airport_details ( $destination_code, date ( 'm', $ats ) );
		
		if (! isset ( $origine_name ) || empty ( $origine_name )) {
			$origine_name = (empty ( $org_loc_details ['airport_city'] ) ? $origin_code : ($org_loc_details ['airport_city']));
		}
		
		if (! isset ( $destination_name ) || empty ( $destination_name )) {
			$destination_name = (empty ( $des_loc_details ['airport_city'] ) ? $destination_code : ($des_loc_details ['airport_city']));
		}
		
		$departure_dt_tz = $departure_dt;
		$arrival_dt_tz = $arrival_dt . '';
		
		if (empty ( $org_loc_details ['timezone_offset'] ) == false) {
			$departure_dt_tz = $departure_dt.$org_loc_details ['timezone_offset'];
		}
		if (empty ( $des_loc_details ['timezone_offset'] ) == false) {
			$arrival_dt_tz = $arrival_dt.$des_loc_details ['timezone_offset'];
		}
		
		 if (! isset ( $duration ) || empty ( $duration )) {
		 	$duration = calculate_duration ( $departure_dt_tz, $arrival_dt_tz );
		 }
		$summary_array = array ();
		$summary_array ['journey_number'] = $journey_number;
		
		$summary_array ['origin'] = array (
				'loc' => $origin_code,
				'city' => $origine_name,
				'datetime' => $departure_dt,
				'date' => $departure_date, // Derive
				'time' => $departure_time, // Derive H i/h i a
				'fdtv' => $dts 
		); // Derive
		
		$summary_array ['destination'] = array (
				'loc' => $destination_code,
				'city' => $destination_name,
				'datetime' => $arrival_dt,
				'date' => $arrival_date, // Derive
				'time' => $arrival_time, // Derive
				'fatv' => $ats
		); // Derive
		
		if (empty ( $org_terminal ) == false) {
			$summary_array ['origin'] ['terminal'] = $org_terminal;
		}
		if (empty ( $des_terminal ) == false) {
			$summary_array ['destination'] ['terminal'] = $des_terminal;
		}
		
		$summary_array ['operator_code'] = $operator_code; // Airline code 9w
		$summary_array ['display_operator_code'] = $carrier_code;
		$summary_array ['operator_name'] = $operator_name; // Airline name
		$summary_array ['flight_number'] = $flight_number;
		$summary_array ['cabin_class'] = $cabin_class;
		$summary_array ['fare_class'] = @$fare_class;
		$summary_array ['no_of_stops'] = $no_of_stops;
		$summary_array ['is_leg'] = $is_leg;
		if(valid_array($cabin_bag) == true) {
			$cabin_bag = '';
		}
		$summary_array ['cabin_bag'] = $cabin_bag;
		$summary_array ['duration_seconds'] = $duration; // Optional or calculate
		$summary_array ['duration'] = get_time_duration_label ( $duration ); // Optional or calculate
		$summary_array ['attr'] = @$attr;
		
		$layover_time = '';
		if(COUNT($details) >= 1) {
			$detail_cnt = COUNT($details);
			$dts = strtotime ( $summary_array['origin']['datetime'] );
			$ats = strtotime ( $details[$detail_cnt - 1]['destination']['datetime'] );
			$layover_time = $dts - $ats;
			$layover_time = get_time_duration_label($layover_time);
			$details[$detail_cnt - 1]['layover_time'] = $layover_time;
		}
		
		return $summary_array;
	}
	
	/**
	 * return booking url to be used in the application for all the modules
	 *
	 * @param number $search_id        	
	 */
	function pre_booking_url($search_id) {

		$booking_url = base_url () . 'index.php/flight/pre_process_booking/' . $search_id;
		// debug($booking_url);exit();
		return $booking_url;
	}
	function pre_booking_crs_url($search_id) {
		
		$booking_url = base_url () . 'index.php/flight_crs/pre_process_booking/' . $search_id;
		// debug($booking_url);exit();
		return $booking_url;
	}
	static function combined_booking_url($search_id) {
		return $this->pre_booking_url ( $search_id );
	}
	
	/**
	 * Things to do before going to booking page
	 *
	 * @param array $token        	
	 * @param string $transaction_id        	
	 * @param number $search_id        	
	 * @return boolean
	 */
	function pre_booking_process($unique_onward_return_booking_source, $source_index, $token, $transaction_id, $search_id, & $flight_data) {
		return false;
	}
	public function sell_ssr($app_reference, $temp_booking = "") {
		return true;
	}
	public function seat_map_details($temp_booking,$passenger="") {
		return true;
	}
	
	/**
	 * Read Baggage options for passenger
	 *
	 * @param array $passenger_obj        	
	 */
	function read_baggage($passenger_obj, $attr = array()) {
		return false;
	}
	
	/**
	 * Read meals option for passenger
	 *
	 * @param array $passenger_obj        	
	 */
	function read_meals($passenger_obj, $attr = array()) {
		return false;
	}
	function post_passenger_booking($book_id, $temp_booking) {
		return array (
				'status' => SUCCESS_STATUS 
		);
	}
	/*function seat_map_details($temp_booking) {
		return false;
	}*/
	
	/**
	 *
	 * @param array $passenger        	
	 * @param array $pax_iti_map        	
	 */
	function read_ssr($passenger, $temp_booking, & $pax_iti_map) {
		$attributes = array ();
		
		$meal_list = $this->read_meals ( $passenger, $attributes );
		$baggage_list = $this->read_baggage ( $passenger, $attributes );
		// $seat_map = $this->seat_map_details ( $temp_booking );
		
		if ($baggage_list ['status'] == SUCCESS_STATUS) {
			$passenger ['baggage'] = $baggage_list ['baggage'];
		} else {
			$passenger ['baggage'] = false;
		}
		if ($meal_list ['status'] == SUCCESS_STATUS) {
			$passenger ['meals'] = $meal_list ['meal'];
		} else {
			$passenger ['meals'] = false;
		}
		// if ($seat_map['status'] == SUCCESS_STATUS) {
		// $passenger ['seats'] = $seat_map ['data'];
		// } else {
		// $passenger ['seats'] = false;
		// }
		$pax_iti_map [$passenger ['pax_index']] [] = $passenger;
	}
	
	/**
	 * Generate Combination of flights
	 *
	 * @param array $onward        	
	 * @param array $return        	
	 */
	static function form_flight_combination($onward, $return, $trip_type = 'oneway') {
		$merge_array = array ();
		$combined_array = array ();
		$onward = force_multple_data_format ( $onward );
		$return = force_multple_data_format ( $return );
		for($i = 0; $i < count ( $onward ); $i ++) {
			
			// if($trip_type == 'multicity') {
			// if(valid_array($onward [$i]['flight_details']['details'])) {
			// foreach($onward [$i]['flight_details']['details'] as $d_k => $det) {
			// if(valid_array($det) && COUNT($det)) {
			// $i++;
			// }
			// }
			// }
			// }
			$token_onward = unserialized_data ( $onward [$i] ['token'] );
			for($j = 0; $j < count ( $return ); $j ++) {
				// if($trip_type == 'multicity') {
				// if(valid_array($return [$j]['flight_details']['details'])) {
				// foreach($return [$j]['flight_details']['details'] as $d_k => $det) {
				// if(valid_array($det) && COUNT($det)) {
				// $j++;
				// }
				// }
				// }
				// }
				$price_array = false;
				if (isset ( $onward [$i] ['price'] ) == true && valid_array ( $onward [$i] ['price'] ) == true) {
					$price_array = Common_Api_Flight::combine_price_arr ( $onward [$i], $return [$j] );
				}
				
				$passenger_breakup = array ();
				if (isset ( $onward [$i] ['passenger_breakup'] ) && valid_array ( $onward [$i] ['passenger_breakup'] )) {
					$passenger_breakup = Common_Api_Flight::combine_passenger_brekup_arr ( $onward [$i], $return [$j] );
					// if ($_SERVER ['REMOTE_ADDR'] == '192.168.0.26') {
					// debug($passenger_breakup);debug($onward[$i]);debug($return[$j]);exit;
					// }
				}
				
				$merge_array ['price'] = $price_array;
				
				if (isset ( $passenger_breakup ) && valid_array ( $passenger_breakup )) {
					$merge_array ['passenger_breakup'] = $passenger_breakup;
					$onward [$i] ['price'] ['passenger_breakup'] = $onward [$i] ['passenger_breakup'];
					$return [$j] ['price'] ['passenger_breakup'] = $return [$j] ['passenger_breakup'];
				}
				
				$merge_array ['fare'] = array (
						$onward [$i] ['price'],
						$return [$j] ['price'] 
				);
				$merge_array ['flight_details'] ['summary'] = array_merge ( $onward [$i] ['flight_details'] ['summary'], $return [$j] ['flight_details'] ['summary'] );
				$merge_array ['flight_details'] ['details'] = array_merge ( $onward [$i] ['flight_details'] ['details'], $return [$j] ['flight_details'] ['details'] );
				
				$token_return = unserialized_data ( $return [$j] ['token'] );
				$merge_array ['token'] = serialized_data ( array_merge_recursive ( $token_onward, $token_return ) );
				$merge_array ['token_key'] = md5 ( $merge_array ['token'] );
				$combined_array [] = $merge_array;
			}
		}
		// debug($combined_array);exit;
		return $combined_array;
	}
	static function combine_price_arr($onward, $return) {
		$price_array = array ();
		$price_array ['api_currency'] = $onward ['price'] ['api_currency'];
		$price_array ['api_total_display_fare'] = $onward ['price'] ['api_total_display_fare'] + $return ['price'] ['api_total_display_fare'];
		$price_array ['total_breakup'] = array (
				'api_total_tax' => $onward ['price'] ['total_breakup'] ['api_total_tax'] + $return ['price'] ['total_breakup'] ['api_total_tax'],
				'api_total_fare' => $onward ['price'] ['total_breakup'] ['api_total_fare'] + $return ['price'] ['total_breakup'] ['api_total_fare'] 
		);
		
		if (isset ( $onward ['price'] ['pax_breakup'] )) {
			$price_array ['pax_breakup'] ['adult'] ['api_currency'] = @$onward ['price'] ['pax_breakup'] ['adult'] ['api_currency'];
			$price_array ['pax_breakup'] ['adult'] ['api_total_display_fare'] = @$onward ['price'] ['pax_breakup'] ['adult'] ['api_total_display_fare'] + @$return ['price'] ['pax_breakup'] ['adult'] ['api_total_display_fare'];
			$price_array ['pax_breakup'] ['adult'] ['total_breakup'] = array (
					'api_total_tax' => @$onward ['price'] ['pax_breakup'] ['adult'] ['total_breakup'] ['api_total_tax'] + @$return ['price'] ['pax_breakup'] ['adult'] ['total_breakup'] ['api_total_tax'],
					'api_total_fare' => @$onward ['price'] ['pax_breakup'] ['adult'] ['total_breakup'] ['api_total_fare'] + @$return ['price'] ['pax_breakup'] ['adult'] ['total_breakup'] ['api_total_fare'] 
			);
		}
		
		if (isset ( $onward ['price'] ['price_breakup'] ) && isset ( $return ['price'] ['price_breakup'] )) {
			$price_breakup = array ();
			foreach ( $onward ['price'] ['price_breakup'] as $pk => $pv ) {
				$price_breakup [$pk] = $pv + @$return ['price'] ['price_breakup'] [$pk];
			}
			$price_array ['price_breakup'] = $price_breakup;
		}
		
		return $price_array;
	}
	static function combine_passenger_brekup_arr($onward, $return) {
		$passenger_array = array ();
		foreach ( $onward ['passenger_breakup'] as $p_k => $pass ) {
			$passenger_array [$p_k] = array (
					'base_price' => ($onward ['passenger_breakup'] [$p_k] ['base_price'] + $return ['passenger_breakup'] [$p_k] ['base_price']),
					'total_price' => ($onward ['passenger_breakup'] [$p_k] ['total_price'] + $return ['passenger_breakup'] [$p_k] ['total_price']),
					'tax' => ($onward ['passenger_breakup'] [$p_k] ['tax'] + $return ['passenger_breakup'] [$p_k] ['tax']),
					'pass_no' => $onward ['passenger_breakup'] [$p_k] ['pass_no'] 
			);
		}
		return $passenger_array;
	}
	
	/**
	 * Form combination
	 *
	 * @param array $onward        	
	 * @param array $return        	
	 */
	static function domestic_roundway_data($onward, $return) {
		return $this->form_flight_combination ( $onward, $return );
	}
	
	/**
	 *
	 * @param string $key
	 *        	source_code
	 * @param string $value
	 *        	value of session - login id
	 * @param number $exp_in_secs
	 *        	time for session exp
	 */
	function save_session_id($value, $key = '', $exp_in_secs = 3600) {
		if (empty ( $key ) == true) {
			$key = $this->source_code;
		}
		
		$cookie = array (
				'name' => $key,
				'value' => $value,
				'expire' => '1200',
				'path' => PROJECT_COOKIE_PATH 
		);
		$ci = & get_instance ();
		$ci->input->set_cookie ( $cookie );
	}
	
	/**
	 * Read session
	 *
	 * @param string $key
	 *        	source_code
	 */
	function read_session_id($key = '') {
		if (empty ( $key ) == true) {
			$key = $this->source_code;
		}
		
		$ci = & get_instance ();
		$value = $ci->input->cookie ( $key, true );
		return $value;
	}
	
	/*
	 * Delete session
	 */
	function remove_session($key = '') {
		if (empty ( $key ) == true) {
			$key = $this->source_code;
		}
		delete_cookie ( $key );
	}
	
	/**
	 * Read train booking record
	 *
	 * @param string $app_reference        	
	 */
	function get_flight_book_record($app_reference, $train_filter = array(), $customer_filter = array()) {
		$ci = & get_instance ();
		
		if (valid_array ( $customer_filter ) == true) {
			$customer_filter = $ci->custom_db->get_custom_condition ( $customer_filter );
		} else {
			$customer_filter = '';
		}
		
		if (valid_array ( $train_filter ) == true) {
			$train_filter = $ci->custom_db->get_custom_condition ( $train_filter );
		} else {
			$train_filter = '';
		}
		$flight_query = 'select ID.* from flight_booking_itinerary_details ID
				WHERE ID.app_reference = ' . $ci->db->escape ( $app_reference );
		$flight_data = $ci->db->query ( $flight_query )->result_array ();
		
		$passenger_query = 'select CD.*,
				ACL.iso_country_code AS iso_country_code, ACL.country_code, ACL.name as country_name from flight_booking_passenger_details CD LEFT JOIN api_country_list AS ACL ON CD.passenger_nationality=ACL.iso_country_code where CD.app_reference = ' . $ci->db->escape ( $app_reference ) . ' ' . $customer_filter . ' GROUP BY pax_index';
		$passenger_data = $ci->db->query ( $passenger_query )->result_array ();
		
		$book_query = 'select * from flight_booking_details BD where BD.app_reference = ' . $ci->db->escape ( $app_reference );
		$book_data = $ci->db->query ( $book_query )->row_array ();
		
		$booking_data = '';
		return array (
				'passenger' => $passenger_data,
				'flight' => $flight_data,
				'booking' => $book_data 
		);
	}
	
	/**
	 * processing before going to paymwnt gateway
	 *
	 * @param unknown_type $app_reference        	
	 * @param unknown_type $book_attributes        	
	 * @param unknown_type $source        	
	 */
	public function pre_confirmation_process($app_reference, $book_attributes, $source, $booking_src_array = array(),$b_source = " ") {
		error_reporting(0);
		return array (
				'status' => SUCCESS_STATUS 
		);
	}
	
	/**
	 *
	 * @param string $access_key        	
	 * @return string[]
	 */
	function get_fare_details($access_key,$search_session_key) {
		$response ['data'] = array ();
		$response ['status'] = FAILURE_STATUS;
		return $response;
	}
	
	/**
	 * update passenger booking status
	 *
	 * @param string $book_id        	
	 * @param number $sindex        	
	 * @param string $status        	
	 */
	function update_passenger_record($book_id, $sindex,$ticket_number,$status) {
		$cond ['app_reference'] = $book_id;
		$cond ['segment_indicator'] = $sindex;
		$data ['status'] = $status;
		$CI = & get_instance ();
		
		$CI->custom_db->update_record ( 'flight_booking_passenger_details', $data, $cond );
	}
	function update_payment_record($book_id, $payment) {
		$cond ['app_reference'] = $book_id;
		
		$data ['customer_payment_details'] = $payment;
		$CI = & get_instance ();
		
		$CI->custom_db->update_record ( 'flight_booking_transaction_details', $data, $cond );
	}
	/**
	 *
	 * @param string $book_id        	
	 * @param string $booking_source        	
	 */
	function update_screen_scraping_booking($book_id, $booking_source) {
		$CI = & get_instance ();
		//
		$CI->custom_db->update_record ( 'flight_booking_transaction_details', array (
				'status' => BOOKING_PENDING,
				'status_description' => 'Booking Need to be updated by call center' 
		), array (
				'app_reference' => $book_id,
				'booking_source' => $booking_source 
		) );
	}
}