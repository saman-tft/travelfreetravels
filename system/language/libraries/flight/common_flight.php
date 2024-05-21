<?php
require_once BASEPATH . 'libraries/flight/Common_api_flight.php';
class Common_Flight {
	/**
	 * Url to be used for combined flight booking - only for domestic round way
	 *
	 * @param number $search_id
	 */
	static function combined_booking_url($search_id) {
		return Common_Api_Flight::pre_booking_url ( $search_id );
	}

	/**
	 * Data gets saved in list so remember to use correct source value
	 *
	 * @param string $source
	 *        	source of the data - will be used as key while saving
	 * @param string $value
	 *        	value which has to be cached - pass json
	 */
	static function insert_record($key, $value) {
		$ci = & get_instance ();

		$index = $ci->redis_server->store_list ( $key, $value );
		return array (
				'access_key' => $key . DB_SAFE_SEPARATOR . $index . DB_SAFE_SEPARATOR . random_string () . random_string (),
				'index' => $index 
		);
	}

	/**
	 */
	static function read_record($key, $offset = -1, $limit = -1) {
		$ci = & get_instance ();
		return $ci->redis_server->read_list ( $key, $offset, $limit );
	}

	/**
	 * Cache the data
	 *
	 * @param string $key
	 * @param value $value
	 * @return array[]
	 */
	static function insert_string($key, $value) {
		$ci = & get_instance ();
		$ci->redis_server->store_string ( $key, $value );
	}

	/**
	 * read data from cache
	 *
	 * @param string $key
	 * @param number $offset
	 * @param number $limit
	 */
	static function read_string($key) {
		$ci = & get_instance ();
		return $ci->redis_server->read_string ( $key );
	}
	static function domestic_roundway_data($onward, $return) {
		return Common_Api_Flight::form_flight_combination ( $onward, $return ) [0];
	}

	/**
	 *
	 * @param string $temp_booking_id
	 */
	public function locate_temp_booking_id($temp_booking_id) {
		$ci = & get_instance ();

		$data = $ci->custom_db->single_table_records ( 'tmp_flight_pre_booking_details', 'origin', array (
				'reference_id' => $ci->db->escape ( $temp_booking_id ) 
		) );
		if ($data ['status'] == FAILURE_STATUS) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 *
	 * @param
	 *        	$app_reference
	 * @param
	 *        	$booking_source
	 */
	public function validate_temp_booking_id($app_reference, $token, $booking_source, $search_id) {
		// check app reference creation and booking source combination
		$token = unserialized_data ( $token );
		$booking_source = unserialized_data ( $booking_source );
		echo $app_reference;
		exit ();
		debug ( $booking_source );
		debug ( $token );
		exit ();
	}

	/**
	 * Save complete master transaction details of flight
	 *
	 * @param
	 *        	$domain_origin
	 * @param
	 *        	$status
	 * @param
	 *        	$app_reference
	 * @param
	 *        	$booking_source
	 * @param
	 *        	$is_lcc
	 * @param
	 *        	$total_fare
	 * @param
	 *        	$domain_markup
	 * @param
	 *        	$level_one_markup
	 * @param
	 *        	$currency
	 * @param
	 *        	$phone
	 * @param
	 *        	$alternate_number
	 * @param
	 *        	$email
	 * @param
	 *        	$journey_start
	 * @param
	 *        	$journey_end
	 * @param
	 *        	$journey_from
	 * @param
	 *        	$journey_to
	 * @param
	 *        	$payment_mode
	 * @param
	 *        	$attributes
	 * @param
	 *        	$created_by_id
	 */
	function save_flight_booking_details($domain_origin, $status, $app_reference, $booking_source, $is_lcc, $phone, $alternate_number, $email, $journey_start, $journey_end, $journey_from, $journey_to, $payment_mode, $attributes, $created_by_id, $from_loc, $to_loc, $from_to_trip_type, $total_price_attributes = '', $api_token = '', $discount,$reward_amount,$reward_points,$reward_earned,$convenience_fees) {
		$data ['domain_origin'] = $domain_origin;
		$data ['app_reference'] = $app_reference;
		$data ['booking_source'] = $booking_source;
		$data ['is_lcc'] = $is_lcc;
		$data ['phone'] = $phone;
		$data ['alternate_number'] = $alternate_number;
		$data ['email'] = $email;
		$data ['journey_start'] = $journey_start;
		$data ['journey_end'] = $journey_end;
		$data ['journey_from'] = $journey_from;
		$data ['journey_to'] = $journey_to;
		$data ['payment_mode'] = $payment_mode;
		$data ['attributes'] = $attributes;
		$data ['created_by_id'] = $created_by_id;
		$data ['created_datetime'] = date ( 'Y-m-d H:i:s' );

		$data ['from_loc'] = $from_loc;
		$data ['to_loc'] = $to_loc;
		$data ['trip_type'] = $from_to_trip_type;
		$data ['total_price_attributes'] = $total_price_attributes;
		$data ['api_token'] = $api_token;
		$data ['discount'] = $discount;
		$data ['reward_amount'] = $reward_amount;
		$data ['reward_points'] = $reward_points;
		$data ['reward_earned'] = $reward_earned;
		$data ['convinence_amount'] = $convenience_fees;
		//debug($data);exit;
		$ci = & get_instance ();

		
			
    	$condition = array('app_reference'=>$app_reference);
      	$previous_record =$ci->custom_db->single_table_records("flight_booking_details", 'app_reference', $condition);
        if($previous_record['status'] != 0){
   
        	
        	$ci->custom_db->delete_record('flight_booking_details', $condition);
        	
        }
        return $ci->custom_db->insert_record ( 'flight_booking_details', $data );
		
		
			
	
		
	}
	function update_booking_table($table_name, $data_array, $condition) {
	}
	/**
	 * Save Passenger details of Flight Booking
	 *
	 * @param
	 *        	$app_reference
	 * @param
	 *        	$passenger_type
	 * @param
	 *        	$is_lead
	 * @param
	 *        	$title
	 * @param
	 *        	$first_name
	 * @param
	 *        	$middle_name
	 * @param
	 *        	$last_name
	 * @param
	 *        	$date_of_birth
	 * @param
	 *        	$gender
	 * @param
	 *        	$passenger_nationality
	 * @param
	 *        	$passport_number
	 * @param
	 *        	$passport_issuing_country
	 * @param
	 *        	$passport_expiry_date
	 * @param
	 *        	$status
	 * @param
	 *        	$attributes
	 */
	function save_flight_booking_passenger_details($pax_index, $app_reference, $passenger_type, $is_lead, $title, $first_name, $middle_name, $last_name, $date_of_birth, $gender, $passenger_nationality, $passport_number, $passport_issuing_country, $passport_expiry_date, $status, $attributes, $segment_indicator, $ff_no = '',$ticket_no = '', $attributesp) {
		$data ['app_reference'] = $app_reference;
		$data ['passenger_type'] = $passenger_type;
		$data ['is_lead'] = $is_lead;
		$data ['title'] = $title;
		$data ['first_name'] = $first_name;
		$data ['middle_name'] = $middle_name;
		$data ['last_name'] = $last_name;
		$data ['date_of_birth'] = $date_of_birth;
		$data ['gender'] = $gender;
		$data ['passenger_nationality'] = $passenger_nationality;
		$data ['passport_number'] = $passport_number;
		$data ['passport_issuing_country'] = $passport_issuing_country;
		$data ['passport_expiry_date'] = $passport_expiry_date;
		$data ['status'] = $status;
		$data ['attributes'] = $attributes;
		$data ['segment_indicator'] = $segment_indicator;
		$data ['pax_index'] = $pax_index;
		$data ['ff_no'] = json_encode($ff_no);
		//$data ['ticket_no'] = $ticket_no;
		$data ['attributes'] = $ticket_no;
		//debug($data);exit;
		$ci = & get_instance ();
		// need to check this back option //-------------------------
		// $condition = array('app_reference'=>$app_reference);
      	// $previous_record =$ci->custom_db->single_table_records("flight_booking_passenger_details", 'app_reference', $condition);
       //  if($previous_record['status'] != 0){
   
       //  	$ci->custom_db->delete_record('flight_booking_passenger_details', $condition);
        	
       //  }
		//---------------------------------
        
		return $ci->custom_db->insert_record ( 'flight_booking_passenger_details', $data );
	}
	/**
	 * Jaganath
	 *
	 * @param
	 *        	$passenger_fk
	 * @param
	 *        	$TicketId
	 * @param
	 *        	$TicketNumber
	 * @param
	 *        	$IssueDate
	 * @param
	 *        	$Fare
	 * @param
	 *        	$SegmentAdditionalInfo
	 * @param
	 *        	$ValidatingAirline
	 * @param
	 *        	$CorporateCode
	 * @param
	 *        	$TourCode
	 * @param
	 *        	$Endorsement
	 * @param
	 *        	$Remarks
	 * @param
	 *        	$ServiceFeeDisplayType
	 */
	function save_passenger_ticket_info($passenger_fk, $TicketId, $TicketNumber, $IssueDate, $Fare, $SegmentAdditionalInfo, $ValidatingAirline, $CorporateCode, $TourCode, $Endorsement, $Remarks, $ServiceFeeDisplayType) {
		$data ['passenger_fk'] = $passenger_fk;
		$data ['TicketId'] = $TicketId;
		$data ['TicketNumber'] = $TicketNumber;
		$data ['IssueDate'] = $IssueDate;
		$data ['Fare'] = $Fare;
		$data ['SegmentAdditionalInfo'] = $SegmentAdditionalInfo;
		$data ['ValidatingAirline'] = $ValidatingAirline;
		$data ['CorporateCode'] = $CorporateCode;
		$data ['TourCode'] = $TourCode;
		$data ['Endorsement'] = $Endorsement;
		$data ['Remarks'] = $Remarks;
		$data ['ServiceFeeDisplayType'] = $ServiceFeeDisplayType;
		$ci = & get_instance ();
		$ci->custom_db->insert_record ( 'flight_passenger_ticket_info', $data );
	}
	/**
	 * Save Individual booking details of a transaction
	 *
	 * @param
	 *        	$app_reference
	 * @param
	 *        	$transaction_status
	 * @param
	 *        	$status_description
	 * @param
	 *        	$pnr
	 * @param
	 *        	$book_id
	 * @param
	 *        	$source
	 * @param
	 *        	$ref_id
	 * @param
	 *        	$attributes
	 * @param
	 *        	$sequence_number
	 */
	function save_flight_booking_transaction_details($booking_source, $app_reference, $transaction_status, $status_description, $pnr, $book_id, $source, $ref_id, $attributes, $sequence_number, $currency, $total_fare, $getbooking_StatusCode, $getbooking_Description, $getbooking_Category, $is_dom, $fare_attributes, $airline ='', $discount) {
		$data ['booking_source'] = $booking_source;
		$data ['app_reference'] = $app_reference;
		$data ['status'] = $transaction_status;
		$data ['status_description'] = $status_description;
		$data ['pnr'] = $pnr;
		$data ['book_id'] = $book_id;
		$data ['source'] = $source;
		$data ['ref_id'] = $ref_id;
		$data ['attributes'] = $attributes;
		$data ['sequence_number'] = $sequence_number;
		$data ['airline'] = $airline;

		$data ['total_fare'] = $total_fare - $discount;
		// $data ['admin_commission'] = $admin_commission;
		// $data ['agent_commission'] = $agent_commission;
		// $data ['admin_markup'] = $admin_markup;
		// $data ['agent_markup'] = $agent_markup;
		$data ['currency'] = $currency;

		$data ['getbooking_StatusCode'] = $getbooking_StatusCode;
		$data ['getbooking_Description'] = $getbooking_Description;
		$data ['getbooking_Category'] = $getbooking_Category;
		$data ['is_dom'] = $is_dom;
		$data ['fare_attributes'] = $fare_attributes;

		$fare_attributes = json_decode ( $fare_attributes, TRUE );
		// debug( $fare_attributes); exit;
		$handling_charge = 0;
		if (isset ( $fare_attributes ['price_breakup'] ['handling_charge'] )) {
			$handling_charge = $fare_attributes ['price_breakup'] ['handling_charge'];
		}
		$data ['handling_charge'] = $handling_charge;

		$service_tax = 0;
		if (isset ( $fare_attributes ['price_breakup'] ['service_tax'] )) {
			$service_tax = $fare_attributes ['price_breakup'] ['service_tax'];
		}
		$data ['service_tax'] = $service_tax;

		$agent_tds_on_commision = 0;
		if (isset ( $fare_attributes ['price_breakup'] ['agent_tds_on_commision'] )) {
			$agent_tds_on_commision = $fare_attributes ['price_breakup'] ['agent_tds_on_commision'];
		}
		$data ['agent_tds_on_commission'] = $agent_tds_on_commision;

		$dist_tds_on_commision = 0;
		if (isset ( $fare_attributes ['price_breakup'] ['dist_tds_on_commision'] )) {
			$dist_tds_on_commision = $fare_attributes ['price_breakup'] ['dist_tds_on_commision'];
		}
		$data ['dist_tds_on_commission'] = $dist_tds_on_commision;

		$admin_tds_on_commission = 0;
		if (isset ( $fare_attributes ['price_breakup'] ['admin_tds_on_commission'] )) {
			$admin_tds_on_commission = $fare_attributes ['price_breakup'] ['admin_tds_on_commission'];
		}
		$data ['admin_tds_on_commission'] = $admin_tds_on_commission;

		$admin_markup = 0;
		if (isset ( $fare_attributes ['price_breakup'] ['admin_markup'] )) {
			$admin_markup = $fare_attributes ['price_breakup'] ['admin_markup'];
		}
		$data ['admin_markup'] = $admin_markup;

		$agent_markup = 0;
		if (isset ( $fare_attributes ['price_breakup'] ['agent_markup'] )) {
			$agent_markup = $fare_attributes ['price_breakup'] ['agent_markup'];
		}
		$data ['agent_markup'] = $agent_markup;

		$admin_commission = 0;
		if (isset ( $fare_attributes ['price_breakup'] ['admin_commission'] )) {
			$admin_commission = $fare_attributes ['price_breakup'] ['admin_commission'];
		}
		$data ['admin_commission'] = $admin_commission;

		$agent_commission = 0;
		if (isset ( $fare_attributes ['price_breakup'] ['agent_commission'] )) {
			$agent_commission = $fare_attributes ['price_breakup'] ['agent_commission'];
		}
		$data ['agent_commission'] = $agent_commission;

		$dist_commission = 0;
		if (isset ( $fare_attributes ['price_breakup'] ['dist_commission'] )) {
			$dist_commission = $fare_attributes ['price_breakup'] ['dist_commission'];
		}
		$data ['dist_commission'] = $dist_commission;

		$dist_markup = 0;
		if (isset ( $fare_attributes ['price_breakup'] ['dist_markup'] )) {
			$dist_markup = $fare_attributes ['price_breakup'] ['dist_markup'];
		}
		$data ['dist_markup'] = $dist_markup;
// debug($data);die;
		$ci = & get_instance ();
		$condition = array('app_reference'=>$app_reference);
      	$previous_record =$ci->custom_db->single_table_records("flight_booking_transaction_details", 'app_reference', $condition);
        if($previous_record['status'] != 0){
   
        	$ci->custom_db->delete_record('flight_booking_transaction_details', $condition);
        	
        }
        
		return $ci->custom_db->insert_record ( 'flight_booking_transaction_details', $data );
	}

	/**
	 * Save Individual booking flight details
	 *
	 * @param
	 *        	$app_reference
	 * @param
	 *        	$segment_indicator
	 * @param
	 *        	$airline_code
	 * @param
	 *        	$airline_name
	 * @param
	 *        	$flight_number
	 * @param
	 *        	$fare_class
	 * @param
	 *        	$from_airport_code
	 * @param
	 *        	$from_airport_name
	 * @param
	 *        	$to_airport_code
	 * @param
	 *        	$to_airport_name
	 * @param
	 *        	$departure_datetime
	 * @param
	 *        	$arrival_datetime
	 * @param
	 *        	$status
	 * @param
	 *        	$operating_carrier
	 * @param
	 *        	$attributes
	 */
	function save_flight_booking_itinerary_details($ResBookDesigCode, $app_reference, $segment_indicator, $airline_code, $airline_name, $flight_number, $fare_class, $from_airport_code, $from_airport_name, $to_airport_code, $to_airport_name, $departure_datetime, $arrival_datetime, $status, $operating_carrier, $attributes, $FareRestriction, $FareBasisCode, $FareRuleDetail, $airline_pnr, $booking_source, $is_leg, $flight_booking_transaction_details_fk, $departure_index, $origin_terminal = '', $destination_terminal = '', $cabin_class = '') {
		$data ['ResBookDesigCode'] = $ResBookDesigCode;
		$data ['app_reference'] = $app_reference;
		$data ['booking_source'] = $booking_source;
		$data ['segment_indicator'] = $segment_indicator;
		$data ['airline_code'] = $airline_code;
		$data ['airline_name'] = $airline_name;
		$data ['flight_number'] = $flight_number;
		$data ['fare_class'] = $fare_class;
		$data ['from_airport_code'] = $from_airport_code;
		$data ['from_airport_name'] = $from_airport_name;
		$data ['to_airport_code'] = $to_airport_code;
		$data ['to_airport_name'] = $to_airport_name;
		$data ['departure_datetime'] = $departure_datetime;
		$data ['arrival_datetime'] = $arrival_datetime;
		$data ['status'] = $status;
		$data ['operating_carrier'] = $operating_carrier;
		$data ['attributes'] = $attributes;
		$data ['FareRestriction'] = $FareRestriction;
		$data ['FareBasisCode'] = $FareBasisCode;
		$data ['FareRuleDetail'] = $FareRuleDetail;
		$data ['airline_pnr'] = $airline_pnr;
		$data ['is_leg'] = $is_leg;
		$data ['departure_index'] = $departure_index;
		$data ['flight_booking_transaction_details_fk'] = $flight_booking_transaction_details_fk;

		if (empty ( $origin_terminal ) == false) {
			$data ['origin_terminal'] = $origin_terminal;
		}

		if (empty ( $destination_terminal ) == false) {
			$data ['destination_terminal'] = $destination_terminal;
		}

		if (empty ( $cabin_class ) == false) {
			$data ['cabin_class'] = $cabin_class;
		}
		// debug($data);exit;
		$ci = & get_instance ();
		// $condition = array('app_reference'=>$app_reference);
  //     	$previous_record =$ci->custom_db->single_table_records("flight_booking_itinerary_details", 'app_reference', $condition);
  //       if($previous_record['status'] != 0){
   
  //       	$ci->custom_db->delete_record('flight_booking_itinerary_details', $condition);
        	
  //       }
        
		
		return $ci->custom_db->insert_record ( 'flight_booking_itinerary_details', $data );
	}

	/**
	 * Read data and create ssr listing
	 *
	 * @param string $app_reference
	 *        	unique application reference
	 */
	function read_ssr_details($app_reference) {
		//error_reporting(E_ALL);
		$response ['status'] = FAILURE_STATUS;
		$response ['msg'] = '';
		$response ['data'] = array ();

		$ci = & get_instance ();
		$details = $ci->flight_model->get_pax_itinerary ( $app_reference );
		// can be used by all the api
		Common_Api_Flight::$app_reference = $app_reference;
		if (valid_array ( $details ) == true) {
			$response ['status'] = SUCCESS_STATUS;
			$temp_booking = $this->module_model->unserialize_temp_booking_record ( $app_reference );
			$seat_map = array();
			$seat_map['data']['seat_map'] = array();
			// get ssr details and baggage details
			$pax_iti_map = array ();
			$booking_source_type = '';
			foreach ( $details as $k => $passenger ) {
				$lib = load_flight_lib ( $passenger ['booking_source'], '', true );
				$ci->$lib->read_ssr ( $passenger, $temp_booking, $pax_iti_map );
				
				if($booking_source_type != $passenger ['booking_source']){//If booking source not there, then run seat layout request
					$booking_source_type = $passenger ['booking_source'];
					$pax_itinerary_details = $ci->flight_model->get_pax_itinerary ( $app_reference, $passenger ['booking_source']);
					$tmp_seat_map = $ci->$lib->seat_map_details ( $temp_booking, $pax_itinerary_details );
					if($tmp_seat_map['status'] == SUCCESS_STATUS){
						$seat_map['data']['seat_map'] = array_merge($seat_map['data']['seat_map'], $tmp_seat_map['data']['seat_map']);
					}
				}
			}
			//Indexing with Booking Source
			$seat_arr = array();
			if(isset($seat_map['data']['seat_map']) == true && valid_array($seat_map['data']['seat_map']) == true){
				foreach ($seat_map['data']['seat_map'] as $s_key => $seat) {
					$seat_arr[$seat['booking_source']][$s_key] = $seat;
				}
			}
			
			$response ['data'] = $pax_iti_map;
// 			$response ['seatmap'] = @$seat_map ['data'];
			$response ['seatmap'] = $seat_arr;
		}
		return $response;
	}
	function push_to_booking_report($app_reference, $status=BOOKING_INPROGRESS) {
		// flight_booking_transaction_details
		// flight_booking_passenger_details
		$ci = & get_instance ();
		$ci->custom_db->update_record ( 'flight_booking_passenger_details', array (
				'status' => $status 
		), array (
				'app_reference' => $app_reference 
		) );
		$ci->custom_db->update_record ( 'flight_booking_transaction_details', array (
				'status' => $status 
		), array (
				'app_reference' => $app_reference 
		) );
	}
	/*
	 * save flight details
	 */
	function save_flight_booking($flight_data, $search_data, $params, $booking_source) {
		// ini_set('display_errors', 1);
		// error_reporting(E_ALL);
		error_reporting(0);
		// 		 debug($booking_source);
		// 		 debug();
				 
		//debug($flight_data);die('save_flight_booking');
		if (isset ( $flight_data ) && valid_array ( $flight_data ) && isset ( $search_data ) && valid_array ( $search_data ) && isset ( $params ) && valid_array ( $params )) {
			$CI = &get_instance ();

			$app_reference = $params ['tmp_flight_pre_booking_id'];
			$contact_no = $params ['passenger_contact'];
			$email = $params ['billing_email'];

			// Journey details
			if ($params['booking_source'] != TRAVELPORT_FLIGHT) {
				$flight_data = json_decode($flight_data[0],true);
			}
			
			// debug($flight_data[0]['ResBookDesigCode']);die;
			$first_flight = current($flight_data[0]['flight_details']['summary']);
			// debug($flight_data);die;
			$journey_start = $first_flight ['origin'] ['datetime'];
			$journey_from = $first_flight ['origin'] ['city'];

			$from_loc = valid_array ( $search_data ['data'] ['from'] ) ? $search_data ['data'] ['from'] : array (
			$search_data ['data'] ['from']
			);
			$from_loc = reset ( $from_loc );
			$to_loc = valid_array ( $search_data ['data'] ['to'] ) ? $search_data ['data'] ['to'] : array (
			$search_data ['data'] ['to']
			);
			$to_loc = end ( $to_loc );

			$journey_to = $first_flight ['destination'] ['city'];
			$last_flight = end ( $flight_data [0]['flight_details'] ['summary'] );
			$journey_end = $last_flight ['destination'] ['datetime'];

			$flight_details_array = $flight_data [0]['flight_details'] ['details'];
			//debug($flight_data);die;
			$fare_array = $flight_data [0]['fare'];
			$price = $flight_data [0]['price'];

			$currency = $price ['api_currency'];
			$created_by_id = intval ( @$GLOBALS ['CI']->entity_user_id );
			$is_lcc = '';
			$alternate_number = '';
			$attributes = '';
			$domain_origin = 1;
			$status = BOOKING_INITIALIZED;
			$from_to_trip_type = @$search_data ['data'] ['trip_type'];
			$phone = $contact_no;
			$payment_mode = @$params ['payment_method'];

			//Only for flydubai
			if ($params['booking_source'] == FLYDUBAI_BOOKING_SOURCE) {
				$baggageAmount =0;
				$mealAmount =0;
				if(isset($params['ADT_BAGGAGE_0']) && valid_array($params['ADT_BAGGAGE_0'])){
					foreach ($params['ADT_BAGGAGE_0'] as $B0key => $B0value) {
						$baggage0 = unserialized_data($B0value);
						$baggageAmount += $baggage0['Amount'];
					}
				}
				if(isset($params['ADT_BAGGAGE_1']) && valid_array($params['ADT_BAGGAGE_1'])){
					foreach ($params['ADT_BAGGAGE_1'] as $B1key => $B1value) {
						$baggage1 = unserialized_data($B1value);
						$baggageAmount += $baggage1['Amount'];
					}	
				}
				if(isset($params['ADT_MEAL_0']) && valid_array($params['ADT_MEAL_0'])){
					foreach ($params['ADT_MEAL_0'] as $M0key => $M0value) {
						$meal0 = unserialized_data($M0value);
						$mealAmount += $meal0['Amount'];
					}	
				}
				if(isset($params['ADT_MEAL_1']) && valid_array($params['ADT_MEAL_1'])){
					foreach ($params['ADT_MEAL_1'] as $M1key => $M1value) {
						$meal1 = unserialized_data($M1value);
						$mealAmount += $meal1['Amount'];
					}	
				}

				$price['total_breakup']['meal_fare']=(double)($mealAmount);
				$price['total_breakup']['baggage_fare']=(double)($baggageAmount);
				$price['total_breakup']['meal_and_baggage_fare'] = $mealAmount + $baggageAmount;
				
			}


			$total_price_attributes = json_encode ( $price );
			//$api_token = $flight_data [0]['token'];
			unset($flight_data [0]['token']);
			$api_token = serialized_data($flight_data);
			


			if($params['reward_amount']){
				
				$reward_amount = $params['reward_amount'];
				$reward_points = $params['reward_used'];
				$reward_earned = $params['earned_reward'];
				$discount = $reward_amount;
			}else{
				$discount = $params['promocode_val'];
			}
			
			// start transction
			$CI->db->trans_start ();
			//die('save_flight_booking_details');
			$CI->common_flight->save_flight_booking_details ( $domain_origin, $status, $app_reference, $params['booking_source'], $is_lcc, $phone, $alternate_number, $email, $journey_start, $journey_end, $journey_from, $journey_to, $payment_mode, $attributes, $created_by_id, $from_loc, $to_loc, $from_to_trip_type, $total_price_attributes, $api_token,$discount,$reward_amount,$reward_points,$reward_earned,$params['convenience_fees']);

			// if domestic round trip then multiple entry in transction
			$booking_source_cnt = COUNT ( $booking_source );
			$l_cnt = 1;
			$booking_source_arr = $booking_source;
			foreach ( $booking_source as $b_k => $b_v ) {
				//debug($b_v);die;
				$booking_source = $b_v;
				$pnr = '';
				$transaction_status = BOOKING_INITIALIZED;
				$status_description = 'In Payment';
				$book_id = '';
				$source = $b_v;
				$ref_id = '';
				// $admin_commission = 0;
				// $agent_commission = 0;
				// $admin_markup = 0;
				// $agent_markup = 0;
				$getbooking_StatusCode = '';
				$getbooking_Description = '';
				$getbooking_Category = '';
				$attributes = '';
				$sequence_number = '';

				if ($booking_source_cnt == 1) {
					$total_fare = $price ['api_total_display_fare'];
					$fare_attributes = $price;
				} else if (isset ( $flight_data ['fare'] [$b_k] )) {
					$total_fare = $flight_data ['fare'] [$b_k] ['api_total_display_fare'];
					$fare_attributes = @($flight_data ['fare'] [$b_k]);
				} else {
					$total_fare = 0;
					$fare_attributes = '';
				}
				if (valid_array ( $fare_attributes )) {
					$fare_attributes = json_encode ( $fare_attributes );
				}
				//				$CI->common_flight->save_flight_booking_transaction_details ( $booking_source, $app_reference, $transaction_status, $status_description, $pnr, $book_id, $source, $ref_id, $attributes, $sequence_number, $currency, $total_fare, $getbooking_StatusCode, $getbooking_Description, $getbooking_Category, $search_data ['data'] ['is_domestic'], $fare_attributes );
				//				$flight_booking_transaction_details_fk = $CI->db->insert_id ();

				$save_cnt = 0;
				//debug($flight_details_array);die;
				foreach ( $flight_details_array as $segment_indicator => $f_journey ) {
					$segment_fare = @$fare_array [$segment_indicator];

					//if domestic round trip same booking source different airline
					if($search_data['data']['is_domestic'] == 1 && $search_data['data']['trip_type'] == 'circle' && COUNT(array_unique($booking_source_arr)) == 1) {
						// 						debug($fare_attributes);
						// 						debug($flight_data ['fare']);
						// 						exit;
						$airline_1 = $flight_data['flight_details']['summary'][0]['operator_code'];
						$airline_2 = $flight_data['flight_details']['summary'][1]['operator_code'];

						if($airline_1 != $airline_2) {
							if($save_cnt == 0) {
								$alirline_cde_r = $airline_1;
							} else if($save_cnt == 1) {
								$alirline_cde_r = $airline_2;
							}

							$fare_attributes = json_encode($flight_data ['fare'] [$save_cnt]);
							$total_fare = $flight_data ['fare'] [$save_cnt] ['api_total_display_fare'];
							$CI->common_flight->save_flight_booking_transaction_details ( $booking_source, $app_reference, $transaction_status, $status_description, 
									$pnr, $book_id, $source, $ref_id, $attributes, $sequence_number, $currency, $total_fare, $getbooking_StatusCode,
									$getbooking_Description, $getbooking_Category, $search_data ['data'] ['is_domestic'], $fare_attributes, $alirline_cde_r , $params['promocode_val']);
							$flight_booking_transaction_details_fk = $CI->db->insert_id ();
							$save_cnt++;
						} else if($save_cnt == 0) {
							$save_cnt++;
							// 							$airline_code = $f_journey[0]['operator_code'];
							$CI->common_flight->save_flight_booking_transaction_details ( $booking_source, $app_reference, $transaction_status, $status_description, $pnr, $book_id, $source, $ref_id, $attributes, $sequence_number, $currency, $total_fare, $getbooking_StatusCode, $getbooking_Description, $getbooking_Category, $search_data ['data'] ['is_domestic'], $fare_attributes, $params['promocode_val']);
							$flight_booking_transaction_details_fk = $CI->db->insert_id ();
							$save_cnt++;
						}
					} else if($save_cnt == 0) {
												$airline_code = $f_journey[0]['operator_code'];

						$CI->common_flight->save_flight_booking_transaction_details ( $booking_source, $app_reference, $transaction_status, $status_description, $pnr, $book_id, $source, $ref_id, $attributes, $sequence_number, $currency, $total_fare, $getbooking_StatusCode, $getbooking_Description, $getbooking_Category, $search_data ['data'] ['is_domestic'], $fare_attributes,$airline_code,$params['promocode_val']);
						$flight_booking_transaction_details_fk = $CI->db->insert_id ();
						$save_cnt++;
					}
					// ends here
					// save with respect to booking source
					if (($booking_source_cnt == 1 && $l_cnt == 1) || ($b_k == $segment_indicator && $booking_source_cnt > 1)) {
						$new_segment = true;
						$departure_index = 0;
						$lay_over = array ();
						//debug($f_journey);die;
						foreach ( $f_journey as $itenary ) {
							if (isset ( $itenary ['flight_number'] ) && ! empty ( $itenary ['flight_number'] )) {
								$is_layover = 0;
								$airline_pnr = '';
								$airline_code = @$itenary ['operator_code'];
								$airline_name = @$itenary ['operator_name'];
								$flight_number = @$itenary ['flight_number'];
								$fare_class = @$itenary ['cabin_class'];
								$air_class = @$itenary ['cabin_class'];
								$from_airport_code = @$itenary ['origin'] ['loc'];
								$from_airport_name = @$itenary ['origin'] ['city'];
								$to_airport_code = @$itenary ['destination'] ['loc'];
								$to_airport_name = @$itenary ['destination'] ['city'];
								$departure_datetime = @$itenary ['origin'] ['datetime'];
								$arrival_datetime = @$itenary ['destination'] ['datetime'];

								$origin_terminal = @$itenary ['origin'] ['terminal'];
								$destination_terminal = @$itenary ['destination'] ['terminal'];

								$status = '';
								$operating_carrier = @$itenary ['operator_code'];
								$FareRestriction = '';
								$FareBasisCode = '';
								$FareRuleDetail = '';
								$attributes = serialize ( @$itenary ['attr'] );
								//$is_leg = $itenary ['is_leg'];
								$is_leg = $segment_indicator;
								$departure_index ++;
								$ResBookDesigCode_index = $departure_index - 1;
								$ResBookDesigCode = (isset($flight_data[$segment_indicator]['ResBookDesigCode'][$ResBookDesigCode_index])) ? $flight_data[$segment_indicator]['ResBookDesigCode'][$ResBookDesigCode_index] : "";
								$CI->common_flight->save_flight_booking_itinerary_details ($ResBookDesigCode, $app_reference, $segment_indicator, $airline_code, $airline_name, $flight_number, $fare_class, $from_airport_code, $from_airport_name, $to_airport_code, $to_airport_name, $departure_datetime, $arrival_datetime, $status, $operating_carrier, $attributes, $FareRestriction, $FareBasisCode, $FareRuleDetail, $airline_pnr, $b_v, $is_leg, $flight_booking_transaction_details_fk, $departure_index, $origin_terminal, $destination_terminal, $air_class );
								$last_inserted_id = $CI->db->insert_id ();
							} else {
								// layover FIXME save layovr details
								$is_layover = 1;
								$lay_over [] = array (
										'loc' => @$itenary ['origin'] ['loc'],
										'city' => @$itenary ['origin'] ['city'] 
								);
							}
						}

						if (isset ( $lay_over ) && valid_array ( $lay_over ) && isset ( $last_inserted_id )) {
							$lay_over = json_encode ( $lay_over );

							$lay_data = array (
									'is_layover' => 1,
									'layover' => $lay_over 
							);
							$CI->db->where ( 'origin', $last_inserted_id );
							$CI->db->update ( 'flight_booking_itinerary_details', $lay_data );
						}

						if ($new_segment == true) {
							// $new_segment
							$new_segment = false;
							// passenger per transaction
							
						}
					}
				}
				for($i = 0; $i < COUNT ( $params ['passenger_type'] ); $i ++) {
								$passport_issuing_country = $GLOBALS ['CI']->db_cache_api->get_country_list ( array (
										'k' => 'origin',
										'v' => 'iso_country_code' 
										), array (
										'origin' => $params ['passenger_passport_issuing_country'] [$i] 
										) );
										$passport_issuing_country = @$passport_issuing_country [$params ['passenger_passport_issuing_country'] [$i]];

										$passenger_nationality = $GLOBALS ['CI']->db_cache_api->get_country_list ( array (
										'k' => 'origin',
										'v' => 'iso_country_code' 
										), array (
										'origin' => $params ['passenger_nationality'] [$i] 
										) );
										$passenger_nationality = @$passenger_nationality [$params ['passenger_nationality'] [$i]];

										$passenger [$i] ['passenger_type'] = $params ['passenger_type'] [$i];

										$gender = get_enum_list ( 'gender', $params ['gender'] [$i] );

										$name_title = get_enum_list ( 'title', $params ['name_title'] [$i] );
										$date_of_birth = date ( 'Y-m-d', strtotime ( $params ['date_of_birth'] [$i] ) );
										$passport_expiry_date = $params ['passenger_passport_expiry_year'] [$i] . '-' . $params ['passenger_passport_expiry_month'] [$i] . '-' . $params ['passenger_passport_expiry_day'] [$i];

										$passenger_type = @$params ['passenger_type'] [$i];
										$passenger_type_code = $this->get_passenger_type_code ( $passenger_type );

										$pax_price_breakup = @$segment_fare ['passenger_breakup'] [$passenger_type_code];
										$pax_price = array ();
										if (isset ( $pax_price_breakup ) && valid_array ( $pax_price_breakup )) {
											$pas_no = isset ( $pax_price_breakup ['pass_no'] ) ? $pax_price_breakup ['pass_no'] : 1;
											$pax_price = array (
											'base_price' => ($pax_price_breakup ['base_price'] / $pas_no),
											'total_price' => ($pax_price_breakup ['total_price'] / $pas_no),
											'tax' => ($pax_price_breakup ['tax'] / $pas_no) 
											);
										}
										$pass_attr = json_encode ( array (
										'price_breakup' => $pax_price 
										) );

										$is_lead = $params ['lead_passenger'] [$i];
										$first_name = $params ['first_name'] [$i];
										$middle_name = $params ['middle_name'] [$i];;
										$last_name = $params ['last_name'] [$i];
										$gender = $gender;
										$passport_number = $params ['passenger_passport_number'] [$i];
										$ff_no = @$params ['ff_no'] [$i];

										$status = BOOKING_INITIALIZED;
										$attributes = $pass_attr;
										$ticket_no='';
										$CI->common_flight->save_flight_booking_passenger_details ( $i, $app_reference, $passenger_type, $is_lead, $name_title, $first_name, $middle_name, $last_name, $date_of_birth, $gender, $passenger_nationality, $passport_number, $passport_issuing_country, $passport_expiry_date, $status, $attributes, $segment_indicator, $ff_no,$ticket_no,json_encode($params));
							}
				$l_cnt ++;
			}
			for($i = 0; $i < COUNT ( $params ['passenger_type'] ); $i ++) {
				$passenger_ticket_attributes ['app_reference'] = $app_reference;
				$passenger_ticket_attributes ['pax_index'] = $i;
				$CI->custom_db->insert_record ( 'flight_booking_passenger_index_attributes', $passenger_ticket_attributes );
			}
			$CI->db->trans_complete ();
		}
	}
	
	
	function get_passenger_type_code($type) {
		$code = '';
		$type = strtolower ( $type );
		switch ($type) {
			case 'adult' :
				$code = 'ADT';
				break;
			case 'child' :
				$code = 'CNN';
				break;
			case 'infant' :
				$code = 'INF';
				break;
			default :
				$code = 'ADT';
				break;
		}
		return $code;
	}
	
	/**
	 * Get pax type string based on pax code
	 * 
	 * @param string $code
	 * 
	 * @return string
	 */
	function get_passenger_type_text($code) {
		$code = strtoupper($code);
		$type = '';
		switch ($code) {
			case 'ADT' :
				$type = 'Adult';
				break;
			case 'CNN' :
			case 'CHD' :
				$type = 'Child';
				break;
			case 'INF' :
				$type = 'Infant';
				break;
			default :
				$type = 'Adult';
				break;
		}
		return $type;
	}

	/**
	 * Save
	 *
	 * @param number $app_refedrence
	 * @param array $data
	 */
	function save_ssr_details($app_refedrence, $data) {
		// flight_booking_baggage_details
		// flight_booking_meals_details

		// get flight itenary
		// $biorigin = $data['bi_origin'];
		// $biorigin = implode(',',$biorigin);
		// $query = "SELECT * FROM `flight_booking_itinerary_details` WHERE `origin` IN (".$biorigin.")";
		$ci = & get_instance ();

		// flight wise passenger
		$passenger_list = array ();
		if (isset ( $data ['passenger'] ) && valid_array ( $data ['passenger'] )) {
			foreach ( $data ['passenger'] as $p_key => $pass ) {
				$passenger_list [$pass ['flight_no']] [$pass['p_origin']] = $pass;
			}
		}
		// save seat details
		if (isset ( $data ['seat'] ) && valid_array ( $data ['seat'] )) {
			foreach ( $data ['seat'] as $s_key => $seat_arr ) {
				$seat_p_origin = $s_key;
				foreach ( $seat_arr as $st_k => $seat ) {
					$pass_seat = $passenger_list [$s_key] [$st_k];
					
					if($pass_seat['p_origin'] == $st_k) {
						$seat_no = explode ( ',', $seat );
						$fare = @$seat_no [1];
						$seat_no = $seat_no [0];
						$p_origin = $pass_seat ['p_origin'];
						$i_origin = $pass_seat ['i_origin'];
						
						$seat_array = array (
								'p_origin' => $p_origin,
								'i_origin' => $i_origin,
								'seat' => $seat_no,
								'fare' => $fare
						);
						
						$ci->custom_db->insert_record ( 'flight_booking_seat_details', $seat_array );
					}
				}
			}
		}
		foreach ( $data ['meal_code'] as $k => $v ) {
			if ($v != 'INVALIDIP') {
				$meal ['i_origin'] = $data ['mi_origin'] [$k];
				$meal ['p_origin'] = $data ['mp_origin'] [$k];
				$value = str_replace ( "'", "", $v );

				$value = explode ( DB_SAFE_SEPARATOR, $value );
				$meal ['value'] = $value [0];
				$meal ['description'] = $value [1];
				$meal ['fare'] = $value [2];
				$ci->custom_db->insert_record ( 'flight_booking_meals_details', $meal );
			}
		}
		// $combine = array_combine($data['bi_origin'],$data['baggage_code']);
		$prev_code = '';

		foreach ( $data ['baggage_code'] as $k => $v ) {
			if ($v != 'INVALIDIP') {
				$baggage ['i_origin'] = $data ['bi_origin'] [$k];
				$baggage ['p_origin'] = $data ['bp_origin'] [$k];
				$value = str_replace ( "'", "", $v );
				$value = explode ( DB_SAFE_SEPARATOR, $value );
				$baggage ['value'] = $value [0];
				$baggage ['description'] = $value [1];
				$baggage ['fare'] = $value [2];
				$baggage ['is_selected'] = 1;

				$prev_code = $v;
				$ci->custom_db->insert_record ( 'flight_booking_baggage_details', $baggage );
			}
			/*else if ($prev_code != '') {
				$baggage ['i_origin'] = $data ['bi_origin'] [$k];
				$baggage ['p_origin'] = $data ['bp_origin'] [$k];

				$value = str_replace ( "'", "", $prev_code );
				$value = explode ( DB_SAFE_SEPARATOR, $value );
				$baggage ['value'] = $value [0];
				$baggage ['description'] = $value [1];
				$baggage ['fare'] = $value [2];
				$baggage ['is_selected'] = 0;

				$ci->custom_db->insert_record ( 'flight_booking_baggage_details', $baggage );
				}*/
		}
	}

	/**
	 * add price of multiple fare array
	 *
	 * @param array $flight_data
	 */
	static function add_fare_details(& $flight_data, $currency_symbol, $conversionrate, $markup_control) {
		

		$condition_check = $flight_data['price']['api_total_display_fare_normal'];
		if (isset ( $flight_data ['fare'] ) && valid_array ( $flight_data ['fare'] )) {
			$api_total_display_fare = 0;
			$api_total_tax = 0;
			$api_total_fare = 0;
			$api_total_display_fare_publish = 0;
			$api_total_tax_publish = 0;
			$api_total_fare_publish = 0;
			$total_meal_and_baggage = 0;
			$price_breakup = array ();

			foreach ( $flight_data ['fare'] as $_f_key => $__fare ) {
				//debug($__fare);exit('__fare');
				$api_total_display_fare += $__fare ['api_total_display_fare'];
				$api_total_tax += $__fare ['total_breakup'] ['api_total_tax'];
				$api_total_fare += $__fare ['total_breakup'] ['api_total_fare'];
				/*private fare start*/
				$api_total_display_fare_publish += $__fare ['api_total_display_fare_publish'];
				$api_total_tax_publish += $__fare ['total_breakup'] ['api_total_tax_publish'];
				$api_total_fare_publish += $__fare ['total_breakup'] ['api_total_fare_publish'];
				/*private fare end*/

				if (isset ( $__fare ['total_breakup'] ['meal_and_baggage_fare'] )) {
					$total_meal_and_baggage += $__fare ['total_breakup'] ['meal_and_baggage_fare'];
				}

				foreach ( $__fare ['price_breakup'] as $p_key => $breakup ) {
					if (isset ( $price_breakup [$p_key] ) == false) {
						$price_breakup [$p_key] = $breakup;
					} else {
						$price_breakup [$p_key] += $breakup;
					}
				}
			}
			$flight_data ['price'] ['api_currency'] = $currency_symbol;
			$flight_data ['price'] ['api_total_display_fare'] = ($api_total_display_fare * $conversionrate);
			$flight_data ['price'] ['total_breakup'] ['api_total_tax'] = ($api_total_tax * $conversionrate);
			$flight_data ['price'] ['total_breakup'] ['api_total_fare'] = ($api_total_fare * $conversionrate);
			if(floor($flight_data ['price'] ['api_total_display_fare_normal']) > 0){
					$flight_data ['price'] ['api_currency'] = $currency_symbol;
					$flight_data ['price'] ['api_total_display_fare_normal'] = ($api_total_display_fare_publish * $conversionrate);
					$flight_data ['price'] ['total_breakup'] ['api_total_tax_publish'] = ($api_total_tax_publish * $conversionrate);
					$flight_data ['price'] ['total_breakup'] ['api_total_fare_publish'] = ($api_total_fare_publish * $conversionrate);
			}
			//debug($flight_data['price']['api_total_display_fare_normal_withoutmarkup']);die;
			$flight_data ['price']['booking_fare_type'] = "Publish";
			//if (($flight_data['price']['api_total_display_fare_normal_withoutmarkup'] != 0) && ($flight_data ['price']['api_total_display_fare'] > $flight_data ['price']['api_total_display_fare_normal']) && ($condition_check > 0)) {
			if (($flight_data['price']['api_total_display_fare_normal_withoutmarkup'] != 0) && ($flight_data ['price']['api_total_display_fare'] > $flight_data ['price']['api_total_display_fare_normal'])) { //debug($flight_data);die('dd');
				$flight_data ['price']['booking_fare_type'] = "Private";
					$app_user_buying_priceps = $flight_data ['price'] ['api_total_display_fare'] = $flight_data ['price']['api_total_display_fare_normal'];
					$flight_data ['price'] ['api_total_display_fare_withoutmarkup'] = $flight_data ['price']['api_total_display_fare_normal_withoutmarkup'];
					$flight_data ['price'] ['total_breakup'] ['api_total_tax'] = $flight_data ['price'] ['total_breakup'] ['api_total_tax_publish'];
					$flight_data ['price'] ['total_breakup'] ['api_total_fare'] = $flight_data ['price'] ['total_breakup'] ['api_total_tax_publish'];
			$flight_data ['fare'][0]['price_breakup'] ['admin_markup'] = $flight_data['fare'][0]['price_breakup'] ['private_admin_markup'];
			$flight_data ['fare'][0]['total_breakup'] ['api_total_fare'] = $flight_data ['fare'][0]['total_breakup'] ['api_total_fare_publish'];
			$flight_data ['fare'][0]['total_breakup'] ['api_total_tax'] = $flight_data ['fare'][0]['total_breakup'] ['api_total_tax_publish'];
			/*-------------------------------------*/
			$flight_data ['fare'][0]['api_total_display_fare'] = $flight_data ['fare'][0]['api_total_display_fare_publish'];

			$flight_data ['fare'][0]['api_total_display_fare_withoutmarkup'] = $flight_data ['fare'][0]['api_total_display_fare_publish_withoutmarkup'];

			$flight_data ['fare'][0]['total_breakup'] ['api_total_tax'] = $flight_data ['fare'][0]['total_breakup'] ['api_total_tax_publish'];
			$flight_data ['fare'][0]['total_breakup'] ['api_total_fare'] = $flight_data ['fare'][0]['total_breakup'] ['api_total_fare_publish'];
			$flight_data ['fare'][0]['price_breakup'] ['app_user_buying_price'] = $flight_data ['fare'][0]['api_total_display_fare'];
			$price_breakup['admin_markup'] = $flight_data ['price']['private_admin_markup'];

			$price_breakup['app_user_buying_price'] = $app_user_buying_priceps;
			//debug($price_breakup);
			//echo $app_user_buying_priceps;
			/*---------------------------------------*/
			//debug($flight_data);die;
			$flight_data['PTotalFare_org'] = $flight_data['Private_PTotalFare_org'];
			$flight_data['PEquivFare_org'] = $flight_data['Private_PEquivFare_org'];
			$flight_data['PTaxFare'] = $flight_data['Private_PTaxFare'];
			}
			$flight_data ['price'] ['total_breakup'] ['meal_and_baggage_fare'] = $total_meal_and_baggage;
			$flight_data ['price'] ['price_breakup'] = $price_breakup;
		}
		 // debug($flight_data);die('1111');
	}
	public function format_neptune_structure($flight_list)
	{
		$pflight_list = $flight_list['data']['flight_data_list']['journey_list'][0];
		//debug($pflight_list);die('format_neptune_structure');
		for ($flight_count=0; $flight_count < count($pflight_list) ; $flight_count++) { 
			/*$xpid = 0;
			if (isset($pflight_list[$flight_count][1])) {
				$xpid = 1;
			}*/
			$xpid = 0;
			for ($dx=0; $dx < count($pflight_list[$flight_count]); $dx++) { 
				$xpid = $dx;
			$pflight_list[$flight_count][0]['flight_details']['summary'][$xpid]['origin']['loc'] = $pflight_list[$flight_count][$xpid]['OriginLocation'][0];
			$pflight_list[$flight_count][0]['flight_details']['summary'][$xpid]['origin']['city'] = $pflight_list[$flight_count][$xpid]['Origin'][0];
			$pflight_list[$flight_count][0]['flight_details']['summary'][$xpid]['origin']['datetime'] = $pflight_list[$flight_count][$xpid]['DepartureDateTime_r'][0];
			$pflight_list[$flight_count][0]['flight_details']['summary'][$xpid]['origin']['date'] = date('d M Y',strtotime($pflight_list[$flight_count][$xpid]['DepartureDateTime_r'][0]));
			$pflight_list[$flight_count][0]['flight_details']['summary'][$xpid]['origin']['time'] = date('H:i',strtotime($pflight_list[$flight_count][$xpid]['DepartureDateTime_r'][0]));
			$pflight_list[$flight_count][0]['flight_details']['summary'][$xpid]['origin']['fdtv'] = strtotime($pflight_list[$flight_count][$xpid]['DepartureDateTime_r'][0]);
			$mycount = count($pflight_list[$flight_count][$xpid]['Origin']) - 1;
			$pflight_list[$flight_count][0]['flight_details']['summary'][$xpid]['destination']['loc'] = $pflight_list[$flight_count][$xpid]['DestinationLocation'][$mycount];
			$pflight_list[$flight_count][0]['flight_details']['summary'][$xpid]['destination']['city'] = $pflight_list[$flight_count][$xpid]['Destination'][$mycount];
			$pflight_list[$flight_count][0]['flight_details']['summary'][$xpid]['destination']['datetime'] = $pflight_list[$flight_count][$xpid]['ArrivalDateTime_r'][$mycount];
			$pflight_list[$flight_count][0]['flight_details']['summary'][$xpid]['destination']['date'] = date('d M Y', strtotime($pflight_list[$flight_count][$xpid]['ArrivalDateTime_r'][$mycount]));
			$pflight_list[$flight_count][0]['flight_details']['summary'][$xpid]['destination']['time'] = date('H:i', strtotime($pflight_list[$flight_count][$xpid]['ArrivalDateTime_r'][$mycount]));
			$pflight_list[$flight_count][0]['flight_details']['summary'][$xpid]['destination']['fatv'] = strtotime($pflight_list[$flight_count][$xpid]['ArrivalDateTime_r'][$mycount]);
			$pflight_list[$flight_count][0]['flight_details']['summary'][$xpid]['operator_code'] = $pflight_list[$flight_count][$xpid]['MarketingAirline'][0];
			$pflight_list[$flight_count][0]['flight_details']['summary'][$xpid]['display_operator_code'] = $pflight_list[$flight_count][$xpid]['MarketingAirline'][0];
			$pflight_list[$flight_count][0]['flight_details']['summary'][$xpid]['operator_name'] = $pflight_list[$flight_count][$xpid]['Airline_name'][0];
			$economy = 'Economy';
			if ($pflight_list[$flight_count][$xpid]['Cabin'][0] == 'Y') {
				$economy = 'Economy';
			  }
			  if ($pflight_list[$flight_count][$xpid]['Cabin'][0] == 'S') {
			  	$economy = 'PremiumEconomy';
			  }
			  if ($pflight_list[$flight_count][$xpid]['Cabin'][0] == 'C') {
			  	$economy = 'Business';
			  }
			  if ($pflight_list[$flight_count][$xpid]['Cabin'][0] == 'J') {
			  	$economy = 'PremiumBusiness';
			  }
			  if ($pflight_list[$flight_count][$xpid]['Cabin'][0] == 'F') {
			  	$economy = 'First';
			  }
			  if ($pflight_list[$flight_count][$xpid]['Cabin'][0] == 'P') {
			  	$economy = 'PremiumFirst';
			  }
			  $pflight_list[$flight_count][0]['flight_details']['summary'][$xpid]['cabin_class'] = $economy;
			  $pflight_list[$flight_count][0]['flight_details']['summary'][$xpid]['flight_number'] = $pflight_list[$flight_count][$xpid]['FlighvgtNumber_no'][0];
			  $pflight_list[$flight_count][0]['flight_details']['summary'][$xpid]['no_of_stops'] = $pflight_list[$flight_count][$xpid]['stops'];
			  $total_journey_duration = $pflight_list[$flight_count][$xpid]['final_duration'];
			  $explode_hours = explode('h', $total_journey_duration);
			  $explode_hours1 = explode('m', $explode_hours[1]);
			  $pflight_list[$flight_count][0]['flight_details']['summary'][$xpid]['duration_seconds'] = (($explode_hours[0] * 60) + $explode_hours1[0]);
			  $pflight_list[$flight_count][0]['flight_details']['summary'][$xpid]['duration'] = $total_journey_duration;
			  $pflight_list[$flight_count][0]['flight_details']['summary'][$xpid]['Meal'] = $pflight_list[$flight_count][$xpid]['Meal'][0];
			  $pflight_list[$flight_count][0]['flight_details']['summary'][$xpid]['Meal_description'] = $this->get_sabre_meal($pflight_list[$flight_count][$xpid]['Meal'][0]);
			  $pflight_list[$flight_count][0]['flight_details']['summary'][$xpid]['SeatsRemaining'] = $pflight_list[$flight_count][$xpid]['SeatsRemaining'][0];
			  $pflight_list[$flight_count][0]['flight_details']['summary'][$xpid]['Weight_Allowance'] = $pflight_list[$flight_count][$xpid]['Weight_Allowance'][0];
			/*flight details start here*/
			
			}
			$xspid = 0;
			for ($tript_count=0; $tript_count < count($pflight_list[$flight_count]); $tript_count++) { 
				
					$xspid = $tript_count;
				for ($mydetail_count=0; $mydetail_count < count($pflight_list[$flight_count][$tript_count]['Origin']); $mydetail_count++) { 
					$mypstrip_tpy = ($tript_count == 1) ? 'return' : 'onward';
					$pflight_list[$flight_count][0]['flight_details']['details'][$xspid][$mydetail_count]['journey_number'] = $mypstrip_tpy;
					$pflight_list[$flight_count][0]['flight_details']['details'][$xspid][$mydetail_count]['origin']['loc'] = $pflight_list[$flight_count][$tript_count]['OriginLocation'][$mydetail_count];
					$pflight_list[$flight_count][0]['flight_details']['details'][$xspid][$mydetail_count]['origin']['city'] = $pflight_list[$flight_count][$tript_count]['Origin'][$mydetail_count];
					$pflight_list[$flight_count][0]['flight_details']['details'][$xspid][$mydetail_count]['origin']['datetime'] = $pflight_list[$flight_count][$tript_count]['DepartureDateTime_r'][$mydetail_count];
					$pflight_list[$flight_count][0]['flight_details']['details'][$xspid][$mydetail_count]['origin']['date'] = date('d M Y', strtotime($pflight_list[$flight_count][$tript_count]['DepartureDateTime_r'][$mydetail_count]));
					$pflight_list[$flight_count][0]['flight_details']['details'][$xspid][$mydetail_count]['origin']['time'] = date('H:i', strtotime($pflight_list[$flight_count][$tript_count]['DepartureDateTime_r'][$mydetail_count]));
					$pflight_list[$flight_count][0]['flight_details']['details'][$xspid][$mydetail_count]['origin']['fdtv'] = strtotime($pflight_list[$flight_count][$tript_count]['DepartureDateTime_r'][$mydetail_count]);
					/*for destination start here*/
					$pflight_list[$flight_count][0]['flight_details']['details'][$xspid][$mydetail_count]['destination']['loc'] = $pflight_list[$flight_count][$tript_count]['DestinationLocation'][$mydetail_count];
					$pflight_list[$flight_count][0]['flight_details']['details'][$xspid][$mydetail_count]['destination']['city'] = $pflight_list[$flight_count][$tript_count]['Destination'][$mydetail_count];
					$pflight_list[$flight_count][0]['flight_details']['details'][$xspid][$mydetail_count]['destination']['datetime'] = $pflight_list[$flight_count][$tript_count]['ArrivalDateTime_r'][$mydetail_count];
					$pflight_list[$flight_count][0]['flight_details']['details'][$xspid][$mydetail_count]['destination']['date'] = date('d M Y', strtotime($pflight_list[$flight_count][$tript_count]['ArrivalDateTime_r'][$mydetail_count]));
					$pflight_list[$flight_count][0]['flight_details']['details'][$xspid][$mydetail_count]['destination']['time'] = date('H:i', strtotime($pflight_list[$flight_count][$tript_count]['ArrivalDateTime_r'][$mydetail_count]));
					$pflight_list[$flight_count][0]['flight_details']['details'][$xspid][$mydetail_count]['destination']['fdtv'] = strtotime($pflight_list[$flight_count][$tript_count]['ArrivalDateTime_r'][$mydetail_count]);
					/*for destination ends here*/
					$pflight_list[$flight_count][0]['flight_details']['details'][$xspid][$mydetail_count]['duration'] = $pflight_list[$flight_count][$tript_count]['segment_duration'][$mydetail_count];
					$segp_duration = $pflight_list[$flight_count][$tript_count]['segment_duration'][$mydetail_count];
					$seg_explode = explode('h', $segp_duration);
					$seg_explode1 = explode('m', $seg_explode[1]);
					$pflight_list[$flight_count][0]['flight_details']['details'][$xspid][$mydetail_count]['duration_seconds'] = (($seg_explode[0] * 60) + $seg_explode1[0]);
					$pflight_list[$flight_count][0]['flight_details']['details'][$xspid][$mydetail_count]['operator_code'] = $pflight_list[$flight_count][$tript_count]['MarketingAirline'][$mydetail_count];
					$pflight_list[$flight_count][0]['flight_details']['details'][$xspid][$mydetail_count]['operator_name'] = $pflight_list[$flight_count][$tript_count]['Airline_name'][$mydetail_count];
					$pflight_list[$flight_count][0]['flight_details']['details'][$xspid][$mydetail_count]['flight_number'] = $pflight_list[$flight_count][$tript_count]['FlighvgtNumber_no'][$mydetail_count];
					$seg_class = $pflight_list[$flight_count][$tript_count]['Cabin'][$mydetail_count];
					$economy = 'Economy';
						if ($seg_class == 'Y') {
							$economy = 'Economy';
						  }
						  if ($seg_class == 'S') {
						  	$economy = 'PremiumEconomy';
						  }
						  if ($seg_class == 'C') {
						  	$economy = 'Business';
						  }
						  if ($seg_class == 'J') {
						  	$economy = 'PremiumBusiness';
						  }
						  if ($seg_class == 'F') {
						  	$economy = 'First';
						  }
						  if ($seg_class == 'P') {
						  	$economy = 'PremiumFirst';
						  }
					$pflight_list[$flight_count][0]['flight_details']['details'][$xspid][$mydetail_count]['cabin_class'] = $economy;
					$pflight_list[$flight_count][0]['flight_details']['details'][$xspid][$mydetail_count]['class']['name'] = $economy;
					$pflight_list[$flight_count][0]['flight_details']['details'][$xspid][$mydetail_count]['class']['description'] = '';
					$pflight_list[$flight_count][0]['flight_details']['details'][$xspid][$mydetail_count]['booking_code'] =  $pflight_list[$flight_count][$tript_count]['ResBookDesigCode'][$mydetail_count];
					$pflight_list[$flight_count][0]['flight_details']['details'][$xspid][$mydetail_count]['SeatsRemaining'] =  $pflight_list[$flight_count][$tript_count]['SeatsRemaining'][$mydetail_count];
					$pflight_list[$flight_count][0]['flight_details']['details'][$xspid][$mydetail_count]['Meal'] =  $pflight_list[$flight_count][$tript_count]['Meal'][$mydetail_count];
					$pflight_list[$flight_count][0]['flight_details']['details'][$xspid][$mydetail_count]['Meal_description'] =  $this->get_sabre_meal($pflight_list[$flight_count][$tript_count]['Meal'][$mydetail_count]);
					if (isset($pflight_list[$flight_count][$tript_count]['Weight_Allowance'][$mydetail_count])) {
						$pflight_list[$flight_count][0]['flight_details']['details'][$xspid][$mydetail_count]['Weight_Allowance'] =  $pflight_list[$flight_count][$tript_count]['Weight_Allowance'][$mydetail_count];
					}
					

				}
				
			}
			
			
			/*flight details ends here*/
		}
	
		//debug($pflight_list);exit('format_neptune_structure1');
		$psflight_list['data']['flight_data_list']['journey_list'][0] = $pflight_list;
		return $psflight_list;
	}
	public function get_sabre_meal($value)
	{
		$meal_value = '';
		if ($value != "") {
			 if($value == "L")
              {
                $meal_value ="LUNCH";
              }
              elseif($value == "B"){
                 $meal_value ="BREAK FAST";
                }
                 elseif($value == "D"){
                 $meal_value ="DINNER";
                }
                elseif($value == "R"){
                 $meal_value ="Refreshment";
                   }
                elseif($value == "S"){
                 $meal_value ="SNACK";
                }
                 elseif($value == "M"){
                 $meal_value ="MEALS";
                }	
		}
		return $meal_value;
		
	}
	/**
	 * update cache key by saving data in cache to be accessed in next page and get markup up update
	 *
	 * @param array $flight_list
	 */
	public function update_markup_and_insert_cache_key_to_token($flight_list, $carry_cache_key, $search_id) {
		$ci = &get_instance ();
		 


        if(isset($flight_list['booking_source']) and $flight_list['booking_source']==FLYDUBAI_KBL_BOOKING_SOURCE){		
            $currency_obj = new Currency(array('module_type' => 'flight','from' => 'USD', 'to' => get_application_display_currency_preference())); 
        }else{
            $currency_obj = new Currency(array('module_type' => 'flight','from' => get_application_default_currency(), 'to' => get_application_display_currency_preference())); 
        }	
        $get_currency_symbol = get_application_display_currency_preference();
		$current_currency_symbol = $currency_obj->get_currency_symbol($get_currency_symbol);
		$converted_currency_rate = $currency_obj->getConversionRate(false);
		// debug($converted_currency_rate);die;

		$markup_commission_details = $this->get_markup_commission_details ( $search_id );
	
		//debug($markup_commission_details);die;
		$search_data = $ci->flight_model->get_safe_search_data ( $search_id );
		$search_data = $search_data ['data'];

		$multiplier = $search_data ['total_pax'];

		if ($search_data ['trip_type'] == 'circle' && $search_data ['is_domestic'] != 1 
		|| $search_data ['trip_type'] == 'gdsspecial' && $search_data ['is_domestic'] == 1) {
			$multiplier = $multiplier * 2; // 2 for roundway (2 trip)
		} else if ($search_data ['trip_type'] == 'multicity') {
			$multiplier = $multiplier * count ( $search_data ['depature'] );
		}
		// debug($markup_commission_details);exit;
		extract ( $markup_commission_details );
		$dist_commission = $dist_commission ['commission'];

		if (isset($flight_list ['flight_data_list'] ['journey_list'][0][0][0])) {
			// debug($flight_list ['flight_data_list'] ['journey_list']);die('radis');
			$pix = 0;
			foreach ( $flight_list ['flight_data_list'] ['journey_list'][0] as $j_flight => & $v ) {
				//$j_flight = 20;
				//$v = $flight_list ['flight_data_list'] ['journey_list'][0][$j_flight];
				//debug($v);die;
			//foreach ( $j_flight_list as $k => & $v ) {
				//debug($v);die('before');
				$p = $v[0];
				// debug($markup_control);echo('before');die;
				 // if($_SERVER['REMOTE_ADDR'] =='42.109.145.23'){

				$this->markup_commission ( $p, $commission, $admin_markup, $user_markup, $user_markup_generic, $multiplier, $tds_tax_details, $dist_commission, $dist_markup, $admin_markup_generic, $get_currency_symbol, $converted_currency_rate, $markup_control,$admin_markup_custom,$admin_markup_custom_flag);
		//	}
				
				$v[0] = $p;
				// debug($p);die('after');
				$access_data = Common_Flight::insert_record ( $carry_cache_key, json_encode ( $v ) );
				
				
				//debug($flight_list ['flight_data_list'] ['journey_list'][0][$pix]);echo "<br/>pankaj".$j_flight;
				
				if (is_array($flight_list ['flight_data_list'] ['journey_list'][0][$j_flight][0])) {
					//echo "test";
					//debug($flight_list ['flight_data_list'] ['journey_list'][0][$j_flight][0]);
					$flight_list ['flight_data_list'] ['journey_list'][0][$j_flight][0]['access_key'] = $access_data ['access_key'];
				}
				

				$pix = $pix + 1;
			//}
		}

		
		//die;
		//debug($flight_list);echo "<br/>pankaj".$j_flight;
		//die;
		} /*else {
			debug($flight_list);die;
			foreach ( $flight_list ['flight_data_list'] ['journey_list'] as $j_flight => & $j_flight_list ) {
			foreach ( $j_flight_list as $k => & $v ) {
				//$this->markup_commission ( $v, $commission, $admin_markup, $user_markup, $multiplier, $tds_tax_details, $dist_commission, $dist_markup );
				$access_data = Common_Flight::insert_record ( $carry_cache_key, json_encode ( $v ) );
				$flight_list ['flight_data_list'] ['journey_list'] [$j_flight] [$k][0] ['access_key'] = $access_data ['access_key'];
			}
		}
		}*/
		
		//debug($flight_list);exit;
		return $flight_list;
	}
	
	public function inerst_myradix($flight_list, $carry_cache_key, $search_id)
	{ 
		foreach ( $flight_list as $j_flight => & $j_flight_list ) {
			foreach ( $j_flight_list as $k => & $v ) { //debug($v);die;
				//$this->markup_commission ( $v, $commission, $admin_markup, $user_markup, $multiplier, $tds_tax_details, $dist_commission, $dist_markup );
				$access_data = Common_Flight::insert_record ( $carry_cache_key, json_encode ( $v ) );
				$flight_list[$j_flight] [$k] ['access_key'] = $access_data ['access_key'];
			}
		}
		return $flight_list;
	}
	/**
	 *
	 * @param string $booking_id
	 * @param string $b_source
	 * @param float $api_fare
	 * @param array $fare_attributes
	 */
	function update_markup_fare($booking_id, $b_source, $api_fare, & $fare_attributes) {
		if (valid_array ( $fare_attributes )) {
			$api_total_fare = $api_fare;
			$prev_fare = $fare_attributes ['api_total_display_fare'];
			$prev_fare = $prev_fare - ($fare_attributes ['price_breakup'] ['handling_charge'] + $fare_attributes ['price_breakup'] ['service_tax'] + $fare_attributes ['price_breakup'] ['agent_markup'] + $fare_attributes ['price_breakup'] ['admin_markup']);
			if((isset($fare_attributes ['total_breakup'] ['meal_and_baggage_fare']) == false
			&& empty($fare_attributes ['total_breakup'] ['meal_and_baggage_fare']) == true)
			|| $fare_attributes ['total_breakup'] ['meal_and_baggage_fare'] == 0) {
				$meal_and_baggage = $api_fare - $prev_fare;
			} else {
				$meal_and_baggage = $fare_attributes ['total_breakup'] ['meal_and_baggage_fare'] + ($api_fare - $prev_fare);
			}
			
			$api_fare = $api_fare + $fare_attributes ['price_breakup'] ['handling_charge'] + $fare_attributes ['price_breakup'] ['service_tax'] + $fare_attributes ['price_breakup'] ['agent_markup'] + $fare_attributes ['price_breakup'] ['admin_markup'];

			$fare_attributes ['total_breakup'] ['meal_and_baggage_fare'] = $meal_and_baggage;
			$fare_attributes ['api_total_display_fare'] = $api_fare;
			$fare_attributes ['price_breakup'] ['app_user_buying_price'] += $meal_and_baggage;

			$data = array (
					'total_fare' => $api_fare,
					'fare_attributes' => json_encode ( $fare_attributes ),
					'api_total_fare' => $api_total_fare 
			);

			$CI = &get_instance ();
			$CI->db->where ( 'app_reference', $booking_id );
			$CI->db->where ( 'booking_source', $b_source );
			$CI->db->update ( 'flight_booking_transaction_details', $data );

			// update flight_booking_details
			$flight_booking_details = $CI->db->get_where ( 'flight_booking_details', array (
					'app_reference' => $booking_id 
			) )->result_array ();
			if (valid_array ( $flight_booking_details )) {
				$flight_booking_details = $flight_booking_details [0];
				$total_price_attributes = $flight_booking_details ['total_price_attributes'];
				$total_price_attributes = json_decode ( $total_price_attributes, TRUE );

				if (isset ( $total_price_attributes ['total_breakup'] ['meal_and_baggage_fare'] )) {
					$total_price_attributes ['total_breakup'] ['meal_and_baggage_fare'] += $meal_and_baggage;
				} else {
					$total_price_attributes ['total_breakup'] ['meal_and_baggage_fare'] = $meal_and_baggage;
				}
				$total_price_attributes = json_encode ( $total_price_attributes );
				$update_data = array (
						'total_price_attributes' => $total_price_attributes 
				);
				$CI->db->where ( 'app_reference', $booking_id );
				$CI->db->update ( 'flight_booking_details', $update_data );
			}
		}
	}

	/**
	 *
	 * @param int $search_id
	 */
	function get_markup_commission_details($search_id) {
		$ci = & get_instance ();
		$response = array ();

		$search_data = $ci->flight_model->get_safe_search_data ( $search_id );

		$response ['commission'] = $ci->flight_model->get_flight_commission_details (); 
		$response ['tds_tax_details'] = $response ['commission'] ['tds_tax'];
		$response ['commission'] = $response ['commission'] ['commission'];
		$response ['user_markup'] = $ci->flight_model->get_flight_user_markup_details ();



		$response ['user_markup_generic'] = $ci->flight_model->get_flight_user_markup_details_generic ();
			

		$response ['admin_markup'] = $ci->flight_model->get_flight_admin_markup_details ();
		// debug($response);die('debug');
		
                            if($_GET['booking_source']==PROVAB_FLIGHT_CRS_BOOKING_SOURCE && @$GLOBALS ['CI']->entity_user_type==B2B_USER )
                             {
$response ['admin_markup_generic'] =array();
                             } else 
                             {
$response ['admin_markup_generic'] = $ci->flight_model->get_flight_admin_markup_detail_generic();
                             }

                      
		
		// debug($response ['admin_markup_generic']);die;
		$response ['dist_commission'] = $ci->flight_model->get_flight_dist_commission_details ();
		$response ['dist_markup'] = $ci->flight_model->get_flight_dist_markup_details ();
		$response ['markup_control'] = $ci->flight_model->get_flight_markup_control ();



		
		// 07/07/2021 added by  murukan


				$search_data = $ci->flight_model->get_safe_search_data ( $search_id );
                
				$flight_markup_nst=$ci->flight_model->get_flight_markup($search_data);


				$custom_flight_markup=array();
				$check_markup_enable=0;
				if(valid_array($flight_markup_nst))
				{
					foreach ($flight_markup_nst as $f_key => $f_value) 
					{
					$custom_flight_markup[$f_value ['airline'].'_'.$f_value['airline_number']]=$f_value;
					}
                
				$check_markup_enable = get_flight_markup_flag($search_data['data']);
                }

				$response ['admin_markup_custom']=$custom_flight_markup;

				$response ['admin_markup_custom_flag']=$check_markup_enable;
         		

				// $response['check_markup_enable'] = $check_markup_enable;
				$response ['search_data'] = $search_data ['data'];
				$response ['multiplier'] = $response ['search_data'] ['adult_config'] + $response ['search_data'] ['child_config'] + $response ['search_data'] ['infant_config'];
		// debug($response);exit;
		return $response;
	}

	/**
	 * reset price and commission
	 *
	 * @param unknown $v
	 * @param unknown $commission
	 * @param unknown $admin_markup
	 * @param unknown $user_markup
	 * @param unknown $multiplier
	 * @param unknown $tds_tax_details
	 */
	function markup_commission(& $flight, $commission, $admin_markup, $user_markup, $user_markup_generic, $multiplier, $tds_tax_details, $dist_commission = '', $dist_markup = '', $admin_markup_generic,$currency_symbol, $conversionrate, $markup_control,$admin_markup_custom=array(),$admin_markup_custom_flag=0) {
  // if ($_SERVER['REMOTE_ADDR'] == '42.109.129.170')
  //                       { 
  //                           debug($admin_markup_generic);exit();
                             

  //                       }
		//debug($user_markup);//die;
		if (valid_array($flight)) {
		$flight ['price'] ['api_total_display_fare'] = floatval ( @$flight ['price'] ['total_breakup'] ['api_total_tax'] ) + floatval ( @$flight ['price'] ['total_breakup'] ['api_total_fare'] );
		$flight ['price'] ['price_breakup'] ['app_user_buying_price'] = @$flight ['price'] ['api_total_display_fare'];

		if(isset($flight ['fare']) && valid_array($flight ['fare'])) {
			foreach ( $flight ['fare'] as $__f_k => & $fare_key__ ) {
				$fare_key__ ['api_total_display_fare'] = $fare_key__ ['total_breakup'] ['api_total_tax'] + $fare_key__ ['total_breakup'] ['api_total_fare'];
				$fare_key__ ['price_breakup'] ['app_user_buying_price'] = $fare_key__ ['api_total_display_fare'];
			}
		}
		

		// Dist Commission
		// dist_commission
//		 debug($dist_commission);exit;

		if (valid_array ( $dist_commission )) {
		// if (valid_array()) {
			$this->update_dist_commission ( $flight, $dist_commission, $tds_tax_details, $multiplier );
		}
		// if (valid_array()) {
		if (valid_array ( $commission )) {
			$this->update_user_commission ( $flight, $commission, $tds_tax_details, $multiplier );
		}
		/*
		 * debug($commission);
		 * debug($flight);exit;
		 */
		// echo "before";debug($flight);//exit;
		// echo $multiplier."<br/>";
		// echo "<br/>admin_markup";debug($admin_markup);
		// echo "<br/>currency_symbol";debug($currency_symbol);
		// echo "<br/>conversionrate";debug($conversionrate);
		if (valid_array ( $admin_markup )) {
			$this->update_admin_markup ( $flight, $multiplier, $admin_markup, $admin_markup_generic, $currency_symbol, 1, $markup_control,$admin_markup_custom,$admin_markup_custom_flag);
		}

		// echo "<br/>after";debug($flight);exit;
		
		/*if (valid_array ( $dist_markup )) {
			$this->update_dist_markup ( $flight, $multiplier, $dist_markup );
		}*/

		
	
		if (valid_array ( $user_markup ) || valid_array ( $user_markup_generic )) {

			
			$this->update_user_markup ( $flight, $multiplier, $user_markup, $user_markup_generic, $currency_symbol, $conversionrate, $markup_control);
		}

		// debug($flight);
		// exit;
		// update screen pricing break up
		// Adjust markup and other portal charges in other charges
		$this->update_screen_pricing ( $flight );
		//debug($flight);exit;
		}
	}

	/**
	 * update markup value of user in other charges
	 *
	 * @param array $flight
	 *        	Flight object
	 */
	function update_screen_pricing(& $flight_record) {
		$ci = &get_instance ();

		$fare_array = & $flight_record ['fare'];
		$price_screening = array ();
		$screening_summary = array ();
		if (isset ( $fare_array [0] ['screen_price_breakup'] ) == true) {
			foreach ( $fare_array as $f_key => & $fare ) {
				if (isset ( $fare ['screen_price_breakup'] ['other charges'] ) == false) {
					$fare ['screen_price_breakup'] ['other charges'] = 0;
				}
				$admin_charges = $fare ['price_breakup'] ['admin_markup'] + $fare ['price_breakup'] ['handling_charge'] + $fare ['price_breakup'] ['service_tax'];
				$fare ['screen_price_breakup'] ['other charges'] += $admin_charges;
			}
			if (count ( $fare_array ) == 1) {
				$price_screening = $fare_array [0] ['screen_price_breakup'];
			} else if (count ( $fare_array ) == 2) {
				$sums = array ();
				$array1 = $fare_array [0] ['screen_price_breakup'];
				$array2 = $fare_array [1] ['screen_price_breakup'];
				foreach ( array_keys ( $array1 + $array2 ) as $key ) {
					$price_screening [$key] = (isset ( $array1 [$key] ) ? $array1 [$key] : 0) + (isset ( $array2 [$key] ) ? $array2 [$key] : 0);
				}
			} else if (count ( $fare_array ) > 2) {
				exit ();
			}
		}
		$flight_record ['price'] ['screen_price_breakup'] = $price_screening;
	}

	/**
	 * Update screening price
	 */
	function customer_payable_price() {
	}

	/**
	 * add admin markup
	 *
	 * @param array $flight_record
	 * @param array $search_id
	 */
	function fareBasicCondition($strFare, $strType, $strFCode)
	{ //debug($strFare);die('strFare');
		$strDiv  = ceil(strlen($strFare)/3);
		$arrData['start']  = substr($strFare,0,$strDiv);
		$arrData['middle'] = substr($strFare,$strDiv,$strDiv);
		$arrData['end']    = substr($strFare,-$strDiv);
	    return ($arrData[$strType] == $strFCode)? 1: 0;
	}
	function update_admin_markup(& $flight_record, $multiplier, $markup, $admin_markup_generic, $currency_symbol, $conversionrate, $markup_control,$admin_markup_custom,$admin_markup_custom_flag) {
		error_reporting(0); ini_set('display_error', 'on');
		$ci = &get_instance ();
	
		// debug($admin_markup_custom);die('update_admin_markup');


		$calculate_status = "true";
		$Makup_add_status = "true";
		if (valid_array($markup_control)) {
			$calculate_status = "true";
			$Makup_add_status = "true";
			 switch ($markup_control['markup_calculation']) {
			 	case '1':
			 		$calculate_status = "true";
			 		break;
			 	case '0':
			 		$calculate_status = "false";
			 		break;
			 	
			 	default:
			 		$calculate_status = "true";
			 		break;
			 }
			 switch ($markup_control['Adding_Markup']) {
			 	case '1':
			 		$Makup_add_status = "true";
			 		break;
			 	case '0':
			 		$Makup_add_status = "false";
			 		break;
			 	
			 	default:
			 		$Makup_add_status = "true";
			 		break;
			 }
		}



		if ($markup ['status'] == SUCCESS_STATUS) {
			
			$markup = $markup ['markup'];
			$fare_array = & $flight_record ['fare'];
			// debug($markup);die('update_admin_markup');
			if (is_array($fare_array)) {
				
				$flight_departure_strtotime = isset($flight_record['DepartureDateTime_r'][0]) ? strtotime($flight_record['DepartureDateTime_r'][0]) : strtotime($flight_record ['flight_details'] ['summary'] [0] ['origin']['datetime']);
				foreach ( $fare_array as $f_key => & $fare ) {
				$airline_code = isset($flight_record ['flight_details'] ['summary'] [$f_key] ['operator_code']) ? $flight_record ['flight_details'] ['summary'] [$f_key] ['operator_code'] : $flight_record ['flight_details'] ['summary'] [0] ['operator_code'];
				// debug($markup);die;

			
				if (isset ( $markup [$airline_code] ) ) {
					//debug($markup [$airline_code]);die;
					$sdate = strtotime($markup [$airline_code]['start_date']);
					$edate = strtotime($markup [$airline_code]['end_date']);
					$sprice = $markup [$airline_code]['start_price'];
					$eprice = $markup [$airline_code]['end_price'];
					if (($markup [$airline_code]['calendar_public_value'] != "") 
					&& (($markup [$airline_code]['calendar_public_value'] != 0.00) || $markup [$airline_code]['calendar_private_value'] != 0.00) 
					&& (($sdate <= $flight_departure_strtotime) 
					&& ($edate >= $flight_departure_strtotime))) {
						$markup_Arr = $markup [$airline_code];
					$total_fare = $fare['total_breakup']['api_total_fare'];
					$private_total_fare = $fare['total_breakup']['api_total_fare_publish'];
					//debug($markup_Arr);die('calendar_public_value');
					if (($markup_Arr['calendar_public_value'] != "") && ($markup_Arr['calendar_public_value'] != 0.00)) {
						$this->calculate_paxwise_markup($flight_record, $markup_Arr ['calendar_value_type'], $markup_Arr ['calendar_public_value'], $currency_symbol, $conversionrate, "public", $markup_control);
						$fare ['price_breakup'] ['admin_markup'] = $this->calculate_markup ( $markup_Arr ['calendar_value_type'], $markup_Arr ['calendar_public_value'], $total_fare, $multiplier, $markup_control);
							    $fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['admin_markup'];
							    $fare ['api_total_display_fare'] += $fare ['price_breakup'] ['admin_markup'];

							    $fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['admin_markup'];
							    $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_public_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
					} else if (($markup_Arr ['airline_public_value'] != 0.00) && ($markup_Arr ['airline_public_value'] != "")) {
						$this->calculate_paxwise_markup($flight_record, $markup_Arr ['airline_value_type'], $markup_Arr ['airline_public_value'], $currency_symbol, $conversionrate, 'public', $markup_control);
								$fare ['price_breakup'] ['admin_markup'] = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_public_value'], $total_fare, $multiplier, $markup_control);
							    $fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['admin_markup'];
							    $fare ['api_total_display_fare'] += $fare ['price_breakup'] ['admin_markup'];

							    $fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['admin_markup'];
							    $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_public_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
							} else {
			

								$markup_Arr1 = $admin_markup_generic['markup'];
						
								$this->calculate_paxwise_markup($flight_record, $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_public_value'], $currency_symbol, $conversionrate, 'public', $markup_control);
								$fare ['price_breakup'] ['admin_markup'] = $this->calculate_markup ( $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_public_value'], $total_fare, $multiplier, $markup_control);
								$fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['admin_markup'];
					            $fare ['api_total_display_fare'] += $fare ['price_breakup'] ['admin_markup'];
					            $fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['admin_markup'];
					            $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['generic_public_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
							}
							if (($markup_Arr ['calendar_private_value'] != 0.00) && ($markup_Arr ['calendar_private_value'] != "")) {
								$this->calculate_paxwise_markup($flight_record, $markup_Arr ['calendar_value_type'], $markup_Arr ['calendar_private_value'], $currency_symbol, $conversionrate, 'private', $markup_control);
								$fare ['price_breakup'] ['private_admin_markup'] = $this->calculate_markup ( $markup_Arr ['calendar_value_type'], $markup_Arr ['calendar_private_value'], $private_total_fare, $multiplier, $markup_control);
							      $fare ['total_breakup'] ['api_total_tax_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					              $fare ['api_total_display_fare_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					              $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
							} else if (($markup_Arr ['airline_private_value'] != 0.00) && ($markup_Arr ['airline_private_value'] != "")) { //die('aa');
								  $this->calculate_paxwise_markup($flight_record, $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $currency_symbol, $conversionrate, 'private', $markup_control);
								  $fare ['price_breakup'] ['private_admin_markup'] = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $private_total_fare, $multiplier, $markup_control);
							      $fare ['total_breakup'] ['api_total_tax_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					              $fare ['api_total_display_fare_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					              $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
							} else {
								  $markup_Arr1 = $admin_markup_generic['markup'];
								  $this->calculate_paxwise_markup($flight_record, $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_private_value'], $currency_symbol, $conversionrate, 'private', $markup_control);
									$fare ['price_breakup'] ['private_admin_markup'] = $this->calculate_markup ( $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_private_value'], $private_total_fare, $multiplier, $markup_control);
									$fare ['total_breakup'] ['api_total_tax_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					                $fare ['api_total_display_fare_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					                $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['generic_private_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
							}
						//debug($fare); die;
						//debug($fare_array[$f_key]);
					} else if ((($markup [$airline_code]['fare_public_value'] != "") && ($markup [$airline_code]['fare_public_value'] != 0.00) || ($markup [$airline_code]['fare_private_value'] != "") && ($markup [$airline_code]['fare_private_value'] != 0.00))) {
						$markup_Arr = $markup [$airline_code];
						$total_fare = $fare['total_breakup']['api_total_fare'];
					    $private_total_fare = $fare['total_breakup']['api_total_fare_publish'];
						//debug($fare);die('fare');
						$patternfareBasicfare_code = $markup_Arr['fare_code'];
						$fareBasicfare_fare_base_type = $markup_Arr['fare_base_type'];
						$searchfareBasicfare_fare_base_type = $fare['publicFareBasisCodes'][0]['publicFareBasisCodes_value'];
						$myflag = $this->fareBasicCondition($searchfareBasicfare_fare_base_type, $fareBasicfare_fare_base_type, $patternfareBasicfare_code);
						if (($markup_Arr['fare_public_value'] != "") && ($markup_Arr['fare_public_value'] != 0.00) && ($myflag == 1)) {
							$this->calculate_paxwise_markup($flight_record, $markup_Arr ['fare_value_type'], $markup_Arr ['fare_public_value'], $currency_symbol, $conversionrate, 'public', $markup_control);
						$fare ['price_breakup'] ['admin_markup'] = $this->calculate_markup ( $markup_Arr ['fare_value_type'], $markup_Arr ['fare_public_value'], $total_fare, $multiplier, $markup_control);
							    $fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['admin_markup'];
							    $fare ['api_total_display_fare'] += $fare ['price_breakup'] ['admin_markup'];

							    $fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['admin_markup'];
							    $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_public_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
					} else if (($markup_Arr ['airline_public_value'] != 0.00) && ($markup_Arr ['airline_public_value'] != "")) {

								$this->calculate_paxwise_markup($flight_record, $markup_Arr ['airline_value_type'], $markup_Arr ['airline_public_value'], $currency_symbol, $conversionrate, 'public', $markup_control);
								$fare ['price_breakup'] ['admin_markup'] = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_public_value'], $total_fare, $multiplier, $markup_control);
							    $fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['admin_markup'];
							    $fare ['api_total_display_fare'] += $fare ['price_breakup'] ['admin_markup'];

							    $fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['admin_markup'];
							    $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_public_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
							} else {
								$markup_Arr1 = $admin_markup_generic['markup'];
								$this->calculate_paxwise_markup($flight_record, $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_public_value'], $currency_symbol, $conversionrate, 'public', $markup_control);
								$fare ['price_breakup'] ['admin_markup'] = $this->calculate_markup ( $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_public_value'], $total_fare, $multiplier, $markup_control);
								$fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['admin_markup'];
					            $fare ['api_total_display_fare'] += $fare ['price_breakup'] ['admin_markup'];
					            $fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['admin_markup'];
					            $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['generic_public_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
							}
							if (($markup_Arr ['fare_private_value'] != 0.00) && ($markup_Arr ['fare_private_value'] != "")  && ($myflag == 1)) {
								$this->calculate_paxwise_markup($flight_record, $markup_Arr ['fare_value_type'], $markup_Arr ['fare_private_value'], $currency_symbol, $conversionrate, 'private', $markup_control);
								$fare ['price_breakup'] ['private_admin_markup'] = $this->calculate_markup ( $markup_Arr ['fare_value_type'], $markup_Arr ['fare_private_value'], $private_total_fare, $multiplier, $markup_control);
							      $fare ['total_breakup'] ['api_total_tax_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					              $fare ['api_total_display_fare_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					              $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
							} else if (($markup_Arr ['airline_private_value'] != 0.00) && ($markup_Arr ['airline_private_value'] != "")) { //die('aa');
								  $this->calculate_paxwise_markup($flight_record, $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $currency_symbol, $conversionrate, 'private', $markup_control);
								  $fare ['price_breakup'] ['private_admin_markup'] = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $private_total_fare, $multiplier, $markup_control);
							      $fare ['total_breakup'] ['api_total_tax_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					              $fare ['api_total_display_fare_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					              $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
							} else {
								  $markup_Arr1 = $admin_markup_generic['markup'];
								  $this->calculate_paxwise_markup($flight_record, $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_private_value'], $currency_symbol, $conversionrate, 'private', $markup_control);
									$fare ['price_breakup'] ['private_admin_markup'] = $this->calculate_markup ( $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_private_value'], $private_total_fare, $multiplier, $markup_control);
									$fare ['total_breakup'] ['api_total_tax_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					                $fare ['api_total_display_fare_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					                $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['generic_private_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
							}
					} else {
						$markup_Arr = $markup [$airline_code];
						// debug($markup_Arr);die;
						$total_fare = $fare['total_breakup']['api_total_fare'];
						$private_total_fare = $fare['total_breakup']['api_total_fare_publish'];; 
						if (($markup_Arr ['airline_public_value'] != 0.00) || ($markup_Arr ['airline_private_value'] != 0.00)) {
							
							 //debug($markup_Arr);//exit;
							if (($markup_Arr ['airline_public_value'] != 0.00) && ($markup_Arr ['airline_public_value'] != "")) {
								// echo $total_fare;
								// echo 'pankaj';debug($markup_Arr);die;
								$this->calculate_paxwise_markup($flight_record, $markup_Arr ['airline_value_type'], $markup_Arr ['airline_public_value'], $currency_symbol, $conversionrate, 'public', $markup_control);
								$this->calculate_paxwise_markup($flight_record, $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $currency_symbol, $conversionrate, 'private', $markup_control);
								$fare ['price_breakup'] ['admin_markup'] = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_public_value'], $total_fare, $multiplier, $markup_control);
								//echo 'mark';debug($fare ['price_breakup'] ['admin_markup']);
							    $fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['admin_markup'];
							    $fare ['api_total_display_fare'] += $fare ['price_breakup'] ['admin_markup'];

							    $fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['admin_markup'];
							    $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_public_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
							} else {
								$markup_Arr1 = $admin_markup_generic['markup'];
								$this->calculate_paxwise_markup($flight_record, $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_public_value'], $currency_symbol, $conversionrate, 'public', $markup_control);
								$fare ['price_breakup'] ['admin_markup'] = $this->calculate_markup ( $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_public_value'], $total_fare, $multiplier, $markup_control);
								$fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['admin_markup'];
					            $fare ['api_total_display_fare'] += $fare ['price_breakup'] ['admin_markup'];
					            $fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['admin_markup'];
					            $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['generic_public_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
							}
							
							if (($markup_Arr ['airline_private_value'] != 0.00) && ($markup_Arr ['airline_private_value'] != "")) { //die('aa');
								 // $this->calculate_paxwise_markup($flight_record, $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $currency_symbol, $conversionrate, 'private');
								  $fare ['price_breakup'] ['private_admin_markup'] = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $total_fare_private, $multiplier, $markup_control);
								 // debug($fare);exit;
							      $fare ['total_breakup'] ['api_total_tax_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					              $fare ['api_total_display_fare_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					              $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
							} else {
								  $markup_Arr1 = $admin_markup_generic['markup'];
								  $this->calculate_paxwise_markup($flight_record, $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_private_value'], $currency_symbol, $conversionrate, 'private', $markup_control);
									$fare ['price_breakup'] ['private_admin_markup'] = $this->calculate_markup ( $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_private_value'], $private_total_fare, $multiplier, $markup_control);
									$fare ['total_breakup'] ['api_total_tax_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					                $fare ['api_total_display_fare_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					                $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['generic_private_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
							}
							
							 //debug($fare);//exit;
							// update breakup also
						} else {

	
							/*$markup_Arr = $admin_markup_generic['markup'];

							$total_fare = $fare ['api_total_display_fare'];
							// debug($markup_Arr);
							$fare ['price_breakup'] ['admin_markup'] = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['value'], $total_fare, $multiplier );
							$fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['admin_markup'];
							$fare ['api_total_display_fare'] += $fare ['price_breakup'] ['admin_markup'];

							$fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['admin_markup'];*/
							$markup_Arr = $admin_markup_generic['markup'];
					//debug($markup_Arr);die("markup_Arr");
					$total_fare = $fare['total_breakup']['api_total_fare'];
					$private_total_fare = $fare['total_breakup']['api_total_fare_publish'];
					// debug($markup_Arr);
					$this->calculate_paxwise_markup($flight_record, $markup_Arr ['value_type'], $markup_Arr ['generic_public_value'], $currency_symbol, $conversionrate, 'public', $markup_control);
					$this->calculate_paxwise_markup($flight_record, $markup_Arr ['value_type'], $markup_Arr ['generic_private_value'], $currency_symbol, $conversionrate, 'private', $markup_control);
					$fare ['price_breakup'] ['admin_markup'] = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['generic_public_value'], $total_fare, $multiplier, $markup_control);
					$fare ['price_breakup'] ['private_admin_markup'] = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['generic_private_value'], $private_total_fare, $multiplier, $markup_control);
					$fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['admin_markup'];
					$fare ['api_total_display_fare'] += $fare ['price_breakup'] ['admin_markup'];

					$fare ['total_breakup'] ['api_total_tax_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					$fare ['api_total_display_fare_publish'] += $fare ['price_breakup'] ['private_admin_markup'];

					$fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['admin_markup'];
						}
					}
					
				} 


elseif(isset($admin_markup_custom_flag) && valid_array($admin_markup_custom))
				{
					
					//@$GLOBALS ['CI']->entity_user_type==B2B_USER && 


	               // foreach ($flight_record['flight_details'] as $flight_key => $flight_value) {
	               	// if(isset($flight_record['onward']['segments'][0])){

	                // $flight_value=$flight_record['onward']['segments'][0];
	               	// }
	               	
	               	$flight_value=	$flight_record['flight_details']['summary'][0];
                  
	               	if(isset($admin_markup_custom[$flight_value['operator_code'].'_'.$flight_value['flight_number']]))

		               	{
		               		$flight_num=$admin_markup_custom[$flight_value['operator_code'].'_'.$flight_value['flight_number']];
                              // debug($flight_num);
                              // debug($flight_value);exit();

		               		 // if($flight_value['Origin_loc']==$flight_num['from_loc'] && $flight_value['Destination_loc']==$flight_num['to_loc'])
			               		//  {

			               		//  }

		            $total_fare = $fare['total_breakup']['api_total_fare'];

					$private_total_fare = $fare['total_breakup']['api_total_fare_publish'];
					// debug($markup_Arr);die;
					$this->calculate_paxwise_markup($flight_record, $flight_num ['value_type'], $flight_num ['value'], $currency_symbol, $conversionrate, "public", 'public', $markup_control);
					// $this->calculate_paxwise_markup($flight_record, $markup_Arr ['value_type'], $markup_Arr ['generic_private_value'], $currency_symbol, $conversionrate, "private", $markup_control);
					$fare ['price_breakup'] ['admin_markup'] = $this->calculate_markup ( $flight_num ['value_type'], $flight_num ['value'], $total_fare, $multiplier, $markup_control);
					// $fare ['price_breakup'] ['private_admin_markup'] = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['generic_private_value'], $private_total_fare, $multiplier, $markup_control);

					// $fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['admin_markup']; // commented for adding markup in basefare by murukan 

					$fare ['total_breakup'] ['api_total_fare'] += $fare ['price_breakup'] ['admin_markup'];
					$fare ['api_total_display_fare'] += $fare ['price_breakup'] ['admin_markup'];

					// $fare ['total_breakup'] ['api_total_tax_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					// $fare ['api_total_display_fare_publish'] += @$fare ['price_breakup'] ['private_admin_markup'];

					$fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['admin_markup'];
					
		               	}
	               // }
		              // 	debug($fare);exit;

				}

				else{
//custom markup 07/07/2021

	


					//debug($markup);die;
					$markup_Arr = $admin_markup_generic['markup'];
				
					// $total_fare = $fare ['api_total_display_fare'];
					$total_fare = $fare['total_breakup']['api_total_fare'];

					$private_total_fare = $fare['total_breakup']['api_total_fare_publish'];
					// debug($markup_Arr);die;
					$this->calculate_paxwise_markup($flight_record, $markup_Arr ['value_type'], $markup_Arr ['generic_public_value'], $currency_symbol, $conversionrate, "public", 'public', $markup_control);
					$this->calculate_paxwise_markup($flight_record, $markup_Arr ['value_type'], $markup_Arr ['generic_private_value'], $currency_symbol, $conversionrate, "private", $markup_control);
					$fare ['price_breakup'] ['admin_markup'] = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['generic_public_value'], $total_fare, $multiplier, $markup_control);
					$fare ['price_breakup'] ['private_admin_markup'] = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['generic_private_value'], $private_total_fare, $multiplier, $markup_control);
					$fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['admin_markup'];
					$fare ['api_total_display_fare'] += $fare ['price_breakup'] ['admin_markup'];

					$fare ['total_breakup'] ['api_total_tax_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					$fare ['api_total_display_fare_publish'] += @$fare ['price_breakup'] ['private_admin_markup'];

					$fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['admin_markup'];
					//debug($fare);die;
					// debug($fare);exit;
					// update breakup also
					/*$pax_breakup = & $flight_record ['passenger_breakup'];
					foreach ( $pax_breakup as $px_key => & $pax ) {
						$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['value'], $pax ['total_price'], $pax ['pass_no'] );
						$pax ['tax'] += $pax_ad_markup;
						$pax ['total_price'] += $pax_ad_markup;
					}*/
				}
			}
			// debug($flight_record);echo 'last';//die;
			$this->add_fare_details ( $flight_record, $currency_symbol, $conversionrate, $markup_control);
			// debug($flight_record);echo 'last';die;
			}
			
		}
	}
	

	/**
	 * add user markup
	 *
	 * @param array $flight_record
	 * @param array $search_id
	 */
	function update_user_markup(& $flight_record, $multiplier, $markup, $agent_markup_generic, $currency_symbol, $conversionrate, $markup_control) {
		error_reporting(0); ini_set('display_error', 'on');
		$ci = &get_instance ();
	
	/*if($_SERVER['REMOTE_ADDR']=="157.49.83.134")
				{
					echo 'markup_control',debug($markup_control);
					echo 'MARKUP',debug($markup);
					echo 'VIJAY',debug($agent_markup_generic);exit;
				}*/
				
		// debug($markup);die('update_agent_markup');
		$calculate_status = "true";
		$Makup_add_status = "true";
		
		if (valid_array($markup_control)) {
			$calculate_status = "true";
			$Makup_add_status = "true";
			 switch ($markup_control['markup_calculation']) {
			 	case '1':
			 		$calculate_status = "true";
			 		break;
			 	case '0':
			 		$calculate_status = "false";
			 		break;
			 	
			 	default:
			 		$calculate_status = "true";
			 		break;
			 }
			 switch ($markup_control['Adding_Markup']) {
			 	case '1':
			 		$Makup_add_status = "true";
			 		break;
			 	case '0':
			 		$Makup_add_status = "false";
			 		break;
			 	
			 	default:
			 		$Makup_add_status = "true";
			 		break;
			 }
		}


		if ($markup ['status'] == SUCCESS_STATUS) {
			
			$markup = $markup ['markup'];
			$fare_array = & $flight_record ['fare'];

			if (is_array($fare_array)) {

				$flight_departure_strtotime = isset($flight_record['DepartureDateTime_r'][0]) ? strtotime($flight_record['DepartureDateTime_r'][0]) : strtotime($flight_record ['flight_details'] ['summary'] [0] ['origin']['datetime']);
				foreach ( $fare_array as $f_key => & $fare ) { 
				$airline_code = isset($flight_record ['flight_details'] ['summary'] [$f_key] ['operator_code']) ? $flight_record ['flight_details'] ['summary'] [$f_key] ['operator_code'] : $flight_record ['flight_details'] ['summary'] [0] ['operator_code'];
				// debug($markup);die;
				
				if (isset ( $markup [$airline_code] ) ) {
					//debug($markup [$airline_code]);die;
					$sdate = strtotime($markup [$airline_code]['start_date']);
					$edate = strtotime($markup [$airline_code]['end_date']);
					$sprice = $markup [$airline_code]['start_price'];
					$eprice = $markup [$airline_code]['end_price'];
					if (($markup [$airline_code]['calendar_public_value'] != "") 
					&& (($markup [$airline_code]['calendar_public_value'] != 0.00) || $markup [$airline_code]['calendar_private_value'] != 0.00) 
					&& (($sdate <= $flight_departure_strtotime) 
					&& ($edate >= $flight_departure_strtotime))) {

/* __________________________*/

						$markup_Arr = $markup [$airline_code];
					$total_fare = $fare['total_breakup']['api_total_fare'];
					$private_total_fare = $fare['total_breakup']['api_total_fare_publish'];
					//debug($markup_Arr);die('calendar_public_value');
					if (($markup_Arr['calendar_public_value'] != "") && ($markup_Arr['calendar_public_value'] != 0.00)) {
						$this->calculate_paxwise_markup($flight_record, $markup_Arr ['calendar_value_type'], $markup_Arr ['calendar_public_value'], $currency_symbol, $conversionrate, "public", $markup_control);
						$fare ['price_breakup'] ['agent_markup'] = $this->calculate_markup ( $markup_Arr ['calendar_value_type'], $markup_Arr ['calendar_public_value'], $total_fare, $multiplier, $markup_control);
							    $fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['agent_markup'];
							    $fare ['api_total_display_fare'] += $fare ['price_breakup'] ['agent_markup'];

							    $fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['agent_markup'];
							    $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_public_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}

					} else if (($markup_Arr ['airline_public_value'] != 0.00) && ($markup_Arr ['airline_public_value'] != "")) {
						$this->calculate_paxwise_markup($flight_record, $markup_Arr ['airline_value_type'], $markup_Arr ['airline_public_value'], $currency_symbol, $conversionrate, 'public', $markup_control);
								$fare ['price_breakup'] ['agent_markup'] = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_public_value'], $total_fare, $multiplier, $markup_control);
							    $fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['agent_markup'];
							    $fare ['api_total_display_fare'] += $fare ['price_breakup'] ['agent_markup'];

							    $fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['agent_markup'];
							    $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_public_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}

							} else {

								$markup_Arr1 = $agent_markup_generic['markup'];
								$this->calculate_paxwise_markup($flight_record, $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_public_value'], $currency_symbol, $conversionrate, 'public', $markup_control);
								$fare ['price_breakup'] ['agent_markup'] = $this->calculate_markup ( $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_public_value'], $total_fare, $multiplier, $markup_control);
								$fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['agent_markup'];
					            $fare ['api_total_display_fare'] += $fare ['price_breakup'] ['agent_markup'];
					            $fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['agent_markup'];
					            $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['generic_public_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
							}

							if (($markup_Arr ['calendar_private_value'] != 0.00) && ($markup_Arr ['calendar_private_value'] != "")) {
								$this->calculate_paxwise_markup($flight_record, $markup_Arr ['calendar_value_type'], $markup_Arr ['calendar_private_value'], $currency_symbol, $conversionrate, 'private', $markup_control);
								$fare ['price_breakup'] ['private_agent_markup'] = $this->calculate_markup ( $markup_Arr ['calendar_value_type'], $markup_Arr ['calendar_private_value'], $private_total_fare, $multiplier, $markup_control);
							      $fare ['total_breakup'] ['api_total_tax_publish'] += $fare ['price_breakup'] ['private_agent_markup'];
					              $fare ['api_total_display_fare_publish'] += $fare ['price_breakup'] ['private_agent_markup'];
					              $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}

									//echo 'ALLOW1',debug($fare ['price_breakup']);exit;
							} else if (($markup_Arr ['airline_private_value'] != 0.00) && ($markup_Arr ['airline_private_value'] != "")) { //die('aa');
								  $this->calculate_paxwise_markup($flight_record, $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $currency_symbol, $conversionrate, 'private', $markup_control);
								  $fare ['price_breakup'] ['private_agent_markup'] = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $private_total_fare, $multiplier, $markup_control);
							      $fare ['total_breakup'] ['api_total_tax_publish'] += $fare ['price_breakup'] ['private_agent_markup'];
					              $fare ['api_total_display_fare_publish'] += $fare ['price_breakup'] ['private_agent_markup'];
					              $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}

									//echo 'ALLOW2',debug($fare ['price_breakup']);exit;
							} else {
								  $markup_Arr1 = $agent_markup_generic['markup'];
								  $this->calculate_paxwise_markup($flight_record, $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_private_value'], $currency_symbol, $conversionrate, 'private', $markup_control);
									$fare ['price_breakup'] ['private_agent_markup'] = $this->calculate_markup ( $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_private_value'], $private_total_fare, $multiplier, $markup_control);
									$fare ['total_breakup'] ['api_total_tax_publish'] += $fare ['price_breakup'] ['private_agent_markup'];
					                $fare ['api_total_display_fare_publish'] += $fare ['price_breakup'] ['private_agent_markup'];
					                $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['generic_private_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
									//echo 'ALLOW3',debug($fare ['price_breakup']);exit;
							}
						//debug($fare); die;
						//debug($fare_array[$f_key]);

					} 

	
         else if ((($markup [$airline_code]['fare_public_value'] != "") && ($markup [$airline_code]['fare_public_value'] != 0.00) || ($markup [$airline_code]['fare_private_value'] != "") && ($markup [$airline_code]['fare_private_value'] != 0.00))) 

               {


						$markup_Arr = $markup [$airline_code];
						$total_fare = $fare['total_breakup']['api_total_fare'];
					    $private_total_fare = $fare['total_breakup']['api_total_fare_publish'];
						//debug($fare);die('fare');
						$patternfareBasicfare_code = $markup_Arr['fare_code'];
						$fareBasicfare_fare_base_type = $markup_Arr['fare_base_type'];
						$searchfareBasicfare_fare_base_type = $fare['publicFareBasisCodes'][0]['publicFareBasisCodes_value'];
						$myflag = $this->fareBasicCondition($searchfareBasicfare_fare_base_type, $fareBasicfare_fare_base_type, $patternfareBasicfare_code);
						if (($markup_Arr['fare_public_value'] != "") && ($markup_Arr['fare_public_value'] != 0.00) && ($myflag == 1)) {
							$this->calculate_paxwise_markup($flight_record, $markup_Arr ['fare_value_type'], $markup_Arr ['fare_public_value'], $currency_symbol, $conversionrate, 'public', $markup_control);
						$fare ['price_breakup'] ['agent_markup'] = $this->calculate_markup ( $markup_Arr ['fare_value_type'], $markup_Arr ['fare_public_value'], $total_fare, $multiplier, $markup_control);
							    $fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['agent_markup'];
							    $fare ['api_total_display_fare'] += $fare ['price_breakup'] ['agent_markup'];

							    $fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['agent_markup'];
							    $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_public_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}

									//echo 'ALLOW4',debug($fare ['price_breakup']);exit;
					} else if (($markup_Arr ['airline_public_value'] != 0.00) && ($markup_Arr ['airline_public_value'] != "")) {

								$this->calculate_paxwise_markup($flight_record, $markup_Arr ['airline_value_type'], $markup_Arr ['airline_public_value'], $currency_symbol, $conversionrate, 'public', $markup_control);
								$fare ['price_breakup'] ['agent_markup'] = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_public_value'], $total_fare, $multiplier, $markup_control);
							    $fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['agent_markup'];
							    $fare ['api_total_display_fare'] += $fare ['price_breakup'] ['agent_markup'];

							    $fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['agent_markup'];
							    $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_public_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}


									//echo 'ALLOW5',debug($fare ['price_breakup']);exit;
							} else {
								$markup_Arr1 = $agent_markup_generic['markup'];
								$this->calculate_paxwise_markup($flight_record, $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_public_value'], $currency_symbol, $conversionrate, 'public', $markup_control);
								$fare ['price_breakup'] ['agent_markup'] = $this->calculate_markup ( $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_public_value'], $total_fare, $multiplier, $markup_control);
								$fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['agent_markup'];
					            $fare ['api_total_display_fare'] += $fare ['price_breakup'] ['agent_markup'];
					            $fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['agent_markup'];
					            $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['generic_public_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}

									//echo 'ALLOW6',debug($fare ['price_breakup']);exit;
							}
							if (($markup_Arr ['fare_private_value'] != 0.00) && ($markup_Arr ['fare_private_value'] != "")  && ($myflag == 1)) {
								$this->calculate_paxwise_markup($flight_record, $markup_Arr ['fare_value_type'], $markup_Arr ['fare_private_value'], $currency_symbol, $conversionrate, 'private', $markup_control);
								$fare ['price_breakup'] ['private_agent_markup'] = $this->calculate_markup ( $markup_Arr ['fare_value_type'], $markup_Arr ['fare_private_value'], $private_total_fare, $multiplier, $markup_control);
							      $fare ['total_breakup'] ['api_total_tax_publish'] += $fare ['price_breakup'] ['private_agent_markup'];
					              $fare ['api_total_display_fare_publish'] += $fare ['price_breakup'] ['private_agent_markup'];
					              $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}

									//echo 'ALLOW7',debug($fare ['price_breakup']);exit;
							} else if (($markup_Arr ['airline_private_value'] != 0.00) && ($markup_Arr ['airline_private_value'] != "")) { //die('aa');
								  $this->calculate_paxwise_markup($flight_record, $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $currency_symbol, $conversionrate, 'private', $markup_control);
								  $fare ['price_breakup'] ['private_agent_markup'] = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $private_total_fare, $multiplier, $markup_control);
							      $fare ['total_breakup'] ['api_total_tax_publish'] += $fare ['price_breakup'] ['private_agent_markup'];
					              $fare ['api_total_display_fare_publish'] += $fare ['price_breakup'] ['private_agent_markup'];
					              $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}

									//echo 'ALLOW8',debug($fare ['price_breakup']);exit;
							} else {
								  $markup_Arr1 = $agent_markup_generic['markup'];
								  $this->calculate_paxwise_markup($flight_record, $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_private_value'], $currency_symbol, $conversionrate, 'private', $markup_control);
									$fare ['price_breakup'] ['private_agent_markup'] = $this->calculate_markup ( $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_private_value'], $private_total_fare, $multiplier, $markup_control);
									$fare ['total_breakup'] ['api_total_tax_publish'] += $fare ['price_breakup'] ['private_agent_markup'];
					                $fare ['api_total_display_fare_publish'] += $fare ['price_breakup'] ['private_agent_markup'];
					                $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['generic_private_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
							}

							//echo 'ALLOW10',debug($fare ['price_breakup']);exit;
					} else {

						/*if($_SERVER['REMOTE_ADDR']=="106.198.113.246")
				{
					echo 'VIJAY',debug($markup_Arr);exit;
				}*/
						$markup_Arr = $markup [$airline_code];
						
						$total_fare = $fare['total_breakup']['api_total_fare'];
						$private_total_fare = $fare['total_breakup']['api_total_fare_publish'];; 
						if (($markup_Arr ['airline_public_value'] != 0.00) || ($markup_Arr ['airline_private_value'] != 0.00)) {
							
							 //debug($markup_Arr);//exit;
							if (($markup_Arr ['airline_public_value'] != 0.00) && ($markup_Arr ['airline_public_value'] != "")) {
								// echo $total_fare;
								// echo 'pankaj';debug($markup_Arr);die;
								$this->calculate_paxwise_markup($flight_record, $markup_Arr ['airline_value_type'], $markup_Arr ['airline_public_value'], $currency_symbol, $conversionrate, 'public', $markup_control);
								$this->calculate_paxwise_markup($flight_record, $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $currency_symbol, $conversionrate, 'private', $markup_control);
								$fare ['price_breakup'] ['agent_markup'] = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_public_value'], $total_fare, $multiplier, $markup_control);
								//echo 'mark';debug($fare ['price_breakup'] ['agent_markup']);
							    $fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['agent_markup'];
							    $fare ['api_total_display_fare'] += $fare ['price_breakup'] ['agent_markup'];

							    $fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['agent_markup'];
							    $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_public_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}

									//echo 'ALLOW11',debug($fare ['price_breakup']);exit;
							} else {
								$markup_Arr1 = $agent_markup_generic['markup'];
								$this->calculate_paxwise_markup($flight_record, $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_public_value'], $currency_symbol, $conversionrate, 'public', $markup_control);
								$fare ['price_breakup'] ['agent_markup'] = $this->calculate_markup ( $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_public_value'], $total_fare, $multiplier, $markup_control);
								$fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['agent_markup'];
					            $fare ['api_total_display_fare'] += $fare ['price_breakup'] ['agent_markup'];
					            $fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['agent_markup'];
					            $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['generic_public_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
									//echo 'ALLOW12',debug($fare ['price_breakup']);exit;
							}
							

							if (($markup_Arr ['airline_private_value'] != 0.00) && ($markup_Arr ['airline_private_value'] != "")) { //die('aa');
								 // $this->calculate_paxwise_markup($flight_record, $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $currency_symbol, $conversionrate, 'private');
								  $fare ['price_breakup'] ['private_agent_markup'] = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $total_fare_private, $multiplier, $markup_control);
								 // debug($fare);exit;
							      $fare ['total_breakup'] ['api_total_tax_publish'] += $fare ['price_breakup'] ['private_agent_markup'];
					              $fare ['api_total_display_fare_publish'] += $fare ['price_breakup'] ['private_agent_markup'];
					              $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}

									//echo 'ALLOW13',debug($fare ['price_breakup']);exit;
							} else {
								  $markup_Arr1 = $agent_markup_generic['markup'];
								  $this->calculate_paxwise_markup($flight_record, $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_private_value'], $currency_symbol, $conversionrate, 'private', $markup_control);
									$fare ['price_breakup'] ['private_agent_markup'] = $this->calculate_markup ( $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_private_value'], $private_total_fare, $multiplier, $markup_control);
									$fare ['total_breakup'] ['api_total_tax_publish'] += $fare ['price_breakup'] ['private_agent_markup'];
					                $fare ['api_total_display_fare_publish'] += $fare ['price_breakup'] ['private_agent_markup'];
					                $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['generic_private_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
									//echo 'ALLOW14',debug($fare ['price_breakup']);exit;
							}

							
						} else {

	
							/*$markup_Arr = $agent_markup_generic['markup'];

							$total_fare = $fare ['api_total_display_fare'];
							// debug($markup_Arr);
							$fare ['price_breakup'] ['agent_markup'] = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['value'], $total_fare, $multiplier );
							$fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['agent_markup'];
							$fare ['api_total_display_fare'] += $fare ['price_breakup'] ['agent_markup'];

							$fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['agent_markup'];*/
							$markup_Arr = $agent_markup_generic['markup'];
					//debug($markup_Arr);die("markup_Arr");
					$total_fare = $fare['total_breakup']['api_total_fare'];
					$private_total_fare = $fare['total_breakup']['api_total_fare_publish'];
					// debug($markup_Arr);
					/*if($_SERVER['REMOTE_ADDR']=="157.49.64.86")
				{
					echo 'BALU',debug($markup_Arr);exit;
				}*/
					$this->calculate_paxwise_markup($flight_record, $markup_Arr ['value_type'], $markup_Arr ['generic_public_value'], $currency_symbol, $conversionrate, 'public', $markup_control);
					$this->calculate_paxwise_markup($flight_record, $markup_Arr ['value_type'], $markup_Arr ['generic_private_value'], $currency_symbol, $conversionrate, 'private', $markup_control);
					$fare ['price_breakup'] ['agent_markup'] = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['generic_public_value'], $total_fare, $multiplier, $markup_control);
					$fare ['price_breakup'] ['private_agent_markup'] = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['generic_private_value'], $private_total_fare, $multiplier, $markup_control);
					$fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['agent_markup'];
					$fare ['api_total_display_fare'] += $fare ['price_breakup'] ['agent_markup'];

					$fare ['total_breakup'] ['api_total_tax_publish'] += $fare ['price_breakup'] ['private_agent_markup'];
					$fare ['api_total_display_fare_publish'] += $fare ['price_breakup'] ['private_agent_markup'];

					$fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['agent_markup'];

					
						}
					}
					//echo 'ALLOW15',debug($fare ['price_breakup']);exit;
				} else{
				

	
					//debug($markup);die;

					// $markup_Arr = $agent_markup_generic['markup']; //Commented on 07-07-2021 by Karthick (Reason its giving different user id markup details)

					$markup_Arr =$markup;//Added on 07-07-2021 by Karthick
if($ci->entity_usertype=="sub_agent") { 
					
					
					$markup_Arr ['generic_public_value']=$agent_markup_generic['markup']['generic_public_value'];
					$markup_Arr ['value_type']=$agent_markup_generic['markup']['value_type'];
				    $markup_control='public';
					

				
			}

					// debug($fare['total_breakup']);die("markup_Arr");
					// $total_fare = $fare ['api_total_display_fare'];
					$total_fare = $fare['total_breakup']['api_total_fare'];
					$private_total_fare = $fare['total_breakup']['api_total_fare_publish'];
					// debug($markup_Arr);die;
					$this->calculate_paxwise_markup($flight_record, $markup_Arr ['value_type'], $markup_Arr ['generic_public_value'], $currency_symbol, $conversionrate, "public", 'public', $markup_control);
					$this->calculate_paxwise_markup($flight_record, $markup_Arr ['value_type'], $markup_Arr ['generic_private_value'], $currency_symbol, $conversionrate, "private", $markup_control);
					$fare ['price_breakup'] ['agent_markup'] = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['generic_public_value'], $total_fare, $multiplier, $markup_control);
					$fare ['price_breakup'] ['private_agent_markup'] = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['generic_private_value'], $private_total_fare, $multiplier, $markup_control);
					$fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['agent_markup'];
					$fare ['api_total_display_fare'] += $fare ['price_breakup'] ['agent_markup'];

					$fare ['total_breakup'] ['api_total_tax_publish'] += $fare ['price_breakup'] ['private_agent_markup'];
					$fare ['api_total_display_fare_publish'] += @$fare ['price_breakup'] ['private_agent_markup'];

					$fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['agent_markup'];
				//echo 'ALLOW16',debug($fare ['price_breakup']);exit;
					// debug($fare);exit;
					// update breakup also
					/*$pax_breakup = & $flight_record ['passenger_breakup'];
					foreach ( $pax_breakup as $px_key => & $pax ) {
						$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['value'], $pax ['total_price'], $pax ['pass_no'] );
						$pax ['tax'] += $pax_ad_markup;
						$pax ['total_price'] += $pax_ad_markup;
					}*/
				}
			}

			// debug($flight_record);echo 'last';//die;


			$this->add_fare_details ( $flight_record, $currency_symbol, $conversionrate, $markup_control);
			// debug($flight_record);echo 'last';die;
			}
			
		}
	}
	function update_user_markup06122018(& $flight_record, $multiplier, $markup, $admin_markup_generic, $currency_symbol, $conversionrate, $markup_control) {
		error_reporting(0); ini_set('display_error', 'on');
		$ci = &get_instance ();
		// debug($markup);die('update_admin_markup');
		$calculate_status = "true";
		$Makup_add_status = "true";
		if (valid_array($markup_control)) {
			$calculate_status = "true";
			$Makup_add_status = "true";
			 switch ($markup_control['markup_calculation']) {
			 	case '1':
			 		$calculate_status = "true";
			 		break;
			 	case '0':
			 		$calculate_status = "false";
			 		break;
			 	
			 	default:
			 		$calculate_status = "true";
			 		break;
			 }
			 switch ($markup_control['Adding_Markup']) {
			 	case '1':
			 		$Makup_add_status = "true";
			 		break;
			 	case '0':
			 		$Makup_add_status = "false";
			 		break;
			 	
			 	default:
			 		$Makup_add_status = "true";
			 		break;
			 }
		}
		if ($markup ['status'] == SUCCESS_STATUS) {
			
			$markup = $markup ['markup'];
			$fare_array = & $flight_record ['fare'];
			// debug($markup);die('update_admin_markup');
			if (is_array($fare_array)) {
				
				$flight_departure_strtotime = isset($flight_record['DepartureDateTime_r'][0]) ? strtotime($flight_record['DepartureDateTime_r'][0]) : strtotime($flight_record ['flight_details'] ['summary'] [0] ['origin']['datetime']);
				foreach ( $fare_array as $f_key => & $fare ) {
				$airline_code = isset($flight_record ['flight_details'] ['summary'] [$f_key] ['operator_code']) ? $flight_record ['flight_details'] ['summary'] [$f_key] ['operator_code'] : $flight_record ['flight_details'] ['summary'] [0] ['operator_code'];
				// debug($markup);die;
				if (isset ( $markup [$airline_code] ) ) {
					//debug($markup [$airline_code]);die;
					$sdate = strtotime($markup [$airline_code]['start_date']);
					$edate = strtotime($markup [$airline_code]['end_date']);
					$sprice = $markup [$airline_code]['start_price'];
					$eprice = $markup [$airline_code]['end_price'];
					if (($markup [$airline_code]['calendar_public_value'] != "") 
					&& (($markup [$airline_code]['calendar_public_value'] != 0.00) || $markup [$airline_code]['calendar_private_value'] != 0.00) 
					&& (($sdate <= $flight_departure_strtotime) 
					&& ($edate >= $flight_departure_strtotime))) {
						$markup_Arr = $markup [$airline_code];
					$total_fare = $fare['total_breakup']['api_total_fare'];
					$private_total_fare = $fare['total_breakup']['api_total_fare_publish'];
					//debug($markup_Arr);die('calendar_public_value');
					if (($markup_Arr['calendar_public_value'] != "") && ($markup_Arr['calendar_public_value'] != 0.00)) {
						$this->calculate_paxwise_markup($flight_record, $markup_Arr ['calendar_value_type'], $markup_Arr ['calendar_public_value'], $currency_symbol, $conversionrate, "public", $markup_control);
						$fare ['price_breakup'] ['admin_markup'] = $this->calculate_markup ( $markup_Arr ['calendar_value_type'], $markup_Arr ['calendar_public_value'], $total_fare, $multiplier, $markup_control);
							    $fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['admin_markup'];
							    $fare ['api_total_display_fare'] += $fare ['price_breakup'] ['admin_markup'];

							    $fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['admin_markup'];
							    $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_public_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
					} else if (($markup_Arr ['airline_public_value'] != 0.00) && ($markup_Arr ['airline_public_value'] != "")) {
						$this->calculate_paxwise_markup($flight_record, $markup_Arr ['airline_value_type'], $markup_Arr ['airline_public_value'], $currency_symbol, $conversionrate, 'public', $markup_control);
								$fare ['price_breakup'] ['admin_markup'] = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_public_value'], $total_fare, $multiplier, $markup_control);
							    $fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['admin_markup'];
							    $fare ['api_total_display_fare'] += $fare ['price_breakup'] ['admin_markup'];

							    $fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['admin_markup'];
							    $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_public_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
							} else {
								$markup_Arr1 = $admin_markup_generic['markup'];
								$this->calculate_paxwise_markup($flight_record, $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_public_value'], $currency_symbol, $conversionrate, 'public', $markup_control);
								$fare ['price_breakup'] ['admin_markup'] = $this->calculate_markup ( $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_public_value'], $total_fare, $multiplier, $markup_control);
								$fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['admin_markup'];
					            $fare ['api_total_display_fare'] += $fare ['price_breakup'] ['admin_markup'];
					            $fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['admin_markup'];
					            $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['generic_public_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
							}
							if (($markup_Arr ['calendar_private_value'] != 0.00) && ($markup_Arr ['calendar_private_value'] != "")) {
								$this->calculate_paxwise_markup($flight_record, $markup_Arr ['calendar_value_type'], $markup_Arr ['calendar_private_value'], $currency_symbol, $conversionrate, 'private', $markup_control);
								$fare ['price_breakup'] ['private_admin_markup'] = $this->calculate_markup ( $markup_Arr ['calendar_value_type'], $markup_Arr ['calendar_private_value'], $private_total_fare, $multiplier, $markup_control);
							      $fare ['total_breakup'] ['api_total_tax_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					              $fare ['api_total_display_fare_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					              $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
							} else if (($markup_Arr ['airline_private_value'] != 0.00) && ($markup_Arr ['airline_private_value'] != "")) { //die('aa');
								  $this->calculate_paxwise_markup($flight_record, $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $currency_symbol, $conversionrate, 'private', $markup_control);
								  $fare ['price_breakup'] ['private_admin_markup'] = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $private_total_fare, $multiplier, $markup_control);
							      $fare ['total_breakup'] ['api_total_tax_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					              $fare ['api_total_display_fare_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					              $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
							} else {
								  $markup_Arr1 = $admin_markup_generic['markup'];
								  $this->calculate_paxwise_markup($flight_record, $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_private_value'], $currency_symbol, $conversionrate, 'private', $markup_control);
									$fare ['price_breakup'] ['private_admin_markup'] = $this->calculate_markup ( $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_private_value'], $private_total_fare, $multiplier, $markup_control);
									$fare ['total_breakup'] ['api_total_tax_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					                $fare ['api_total_display_fare_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					                $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['generic_private_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
							}
						//debug($fare); die;
						//debug($fare_array[$f_key]);
					} else if ((($markup [$airline_code]['fare_public_value'] != "") && ($markup [$airline_code]['fare_public_value'] != 0.00) || ($markup [$airline_code]['fare_private_value'] != "") && ($markup [$airline_code]['fare_private_value'] != 0.00))) {
						$markup_Arr = $markup [$airline_code];
						$total_fare = $fare['total_breakup']['api_total_fare'];
					    $private_total_fare = $fare['total_breakup']['api_total_fare_publish'];
						//debug($fare);die('fare');
						$patternfareBasicfare_code = $markup_Arr['fare_code'];
						$fareBasicfare_fare_base_type = $markup_Arr['fare_base_type'];
						$searchfareBasicfare_fare_base_type = $fare['publicFareBasisCodes'][0]['publicFareBasisCodes_value'];
						$myflag = $this->fareBasicCondition($searchfareBasicfare_fare_base_type, $fareBasicfare_fare_base_type, $patternfareBasicfare_code);
						if (($markup_Arr['fare_public_value'] != "") && ($markup_Arr['fare_public_value'] != 0.00) && ($myflag == 1)) {
							$this->calculate_paxwise_markup($flight_record, $markup_Arr ['fare_value_type'], $markup_Arr ['fare_public_value'], $currency_symbol, $conversionrate, 'public', $markup_control);
						$fare ['price_breakup'] ['admin_markup'] = $this->calculate_markup ( $markup_Arr ['fare_value_type'], $markup_Arr ['fare_public_value'], $total_fare, $multiplier, $markup_control);
							    $fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['admin_markup'];
							    $fare ['api_total_display_fare'] += $fare ['price_breakup'] ['admin_markup'];

							    $fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['admin_markup'];
							    $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_public_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
					} else if (($markup_Arr ['airline_public_value'] != 0.00) && ($markup_Arr ['airline_public_value'] != "")) {
								$this->calculate_paxwise_markup($flight_record, $markup_Arr ['airline_value_type'], $markup_Arr ['airline_public_value'], $currency_symbol, $conversionrate, 'public', $markup_control);
								$fare ['price_breakup'] ['admin_markup'] = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_public_value'], $total_fare, $multiplier, $markup_control);
							    $fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['admin_markup'];
							    $fare ['api_total_display_fare'] += $fare ['price_breakup'] ['admin_markup'];

							    $fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['admin_markup'];
							    $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_public_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
							} else {
								$markup_Arr1 = $admin_markup_generic['markup'];
								$this->calculate_paxwise_markup($flight_record, $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_public_value'], $currency_symbol, $conversionrate, 'public', $markup_control);
								$fare ['price_breakup'] ['admin_markup'] = $this->calculate_markup ( $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_public_value'], $total_fare, $multiplier, $markup_control);
								$fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['admin_markup'];
					            $fare ['api_total_display_fare'] += $fare ['price_breakup'] ['admin_markup'];
					            $fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['admin_markup'];
					            $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['generic_public_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
							}
							if (($markup_Arr ['fare_private_value'] != 0.00) && ($markup_Arr ['fare_private_value'] != "")  && ($myflag == 1)) {
								$this->calculate_paxwise_markup($flight_record, $markup_Arr ['fare_value_type'], $markup_Arr ['fare_private_value'], $currency_symbol, $conversionrate, 'private', $markup_control);
								$fare ['price_breakup'] ['private_admin_markup'] = $this->calculate_markup ( $markup_Arr ['fare_value_type'], $markup_Arr ['fare_private_value'], $private_total_fare, $multiplier, $markup_control);
							      $fare ['total_breakup'] ['api_total_tax_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					              $fare ['api_total_display_fare_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					              $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
							} else if (($markup_Arr ['airline_private_value'] != 0.00) && ($markup_Arr ['airline_private_value'] != "")) { //die('aa');
								  $this->calculate_paxwise_markup($flight_record, $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $currency_symbol, $conversionrate, 'private', $markup_control);
								  $fare ['price_breakup'] ['private_admin_markup'] = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $private_total_fare, $multiplier, $markup_control);
							      $fare ['total_breakup'] ['api_total_tax_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					              $fare ['api_total_display_fare_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					              $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
							} else {
								  $markup_Arr1 = $admin_markup_generic['markup'];
								  $this->calculate_paxwise_markup($flight_record, $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_private_value'], $currency_symbol, $conversionrate, 'private', $markup_control);
									$fare ['price_breakup'] ['private_admin_markup'] = $this->calculate_markup ( $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_private_value'], $private_total_fare, $multiplier, $markup_control);
									$fare ['total_breakup'] ['api_total_tax_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					                $fare ['api_total_display_fare_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					                $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['generic_private_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
							}
					} else {
						$markup_Arr = $markup [$airline_code];
						// debug($markup_Arr);die;
						$total_fare = $fare['total_breakup']['api_total_fare'];
						$private_total_fare = $fare['total_breakup']['api_total_fare_publish'];; 
						if (($markup_Arr ['airline_public_value'] != 0.00) || ($markup_Arr ['airline_private_value'] != 0.00)) {
							
							 //debug($markup_Arr);//exit;
							if (($markup_Arr ['airline_public_value'] != 0.00) && ($markup_Arr ['airline_public_value'] != "")) {
								// echo $total_fare;
								// echo 'pankaj';debug($markup_Arr);die;
								$this->calculate_paxwise_markup($flight_record, $markup_Arr ['airline_value_type'], $markup_Arr ['airline_public_value'], $currency_symbol, $conversionrate, 'public', $markup_control);
								$this->calculate_paxwise_markup($flight_record, $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $currency_symbol, $conversionrate, 'private', $markup_control);
								$fare ['price_breakup'] ['admin_markup'] = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_public_value'], $total_fare, $multiplier, $markup_control);
								//echo 'mark';debug($fare ['price_breakup'] ['admin_markup']);
							    $fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['admin_markup'];
							    $fare ['api_total_display_fare'] += $fare ['price_breakup'] ['admin_markup'];

							    $fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['admin_markup'];
							    $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_public_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
							} else {
								$markup_Arr1 = $admin_markup_generic['markup'];
								$this->calculate_paxwise_markup($flight_record, $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_public_value'], $currency_symbol, $conversionrate, 'public', $markup_control);
								$fare ['price_breakup'] ['admin_markup'] = $this->calculate_markup ( $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_public_value'], $total_fare, $multiplier, $markup_control);
								$fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['admin_markup'];
					            $fare ['api_total_display_fare'] += $fare ['price_breakup'] ['admin_markup'];
					            $fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['admin_markup'];
					            $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['generic_public_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
							}
							
							if (($markup_Arr ['airline_private_value'] != 0.00) && ($markup_Arr ['airline_private_value'] != "")) { //die('aa');
								 // $this->calculate_paxwise_markup($flight_record, $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $currency_symbol, $conversionrate, 'private');
								  $fare ['price_breakup'] ['private_admin_markup'] = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $total_fare_private, $multiplier, $markup_control);
								 // debug($fare);exit;
							      $fare ['total_breakup'] ['api_total_tax_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					              $fare ['api_total_display_fare_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					              $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
							} else {
								  $markup_Arr1 = $admin_markup_generic['markup'];
								  $this->calculate_paxwise_markup($flight_record, $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_private_value'], $currency_symbol, $conversionrate, 'private', $markup_control);
									$fare ['price_breakup'] ['private_admin_markup'] = $this->calculate_markup ( $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_private_value'], $private_total_fare, $multiplier, $markup_control);
									$fare ['total_breakup'] ['api_total_tax_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					                $fare ['api_total_display_fare_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					                $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['generic_private_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
							}
							
							 //debug($fare);//exit;
							// update breakup also
						} else {
							/*$markup_Arr = $admin_markup_generic['markup'];

							$total_fare = $fare ['api_total_display_fare'];
							// debug($markup_Arr);
							$fare ['price_breakup'] ['admin_markup'] = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['value'], $total_fare, $multiplier );
							$fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['admin_markup'];
							$fare ['api_total_display_fare'] += $fare ['price_breakup'] ['admin_markup'];

							$fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['admin_markup'];*/
							$markup_Arr = $admin_markup_generic['markup'];
					//debug($markup_Arr);die("markup_Arr");
					$total_fare = $fare['total_breakup']['api_total_fare'];
					$private_total_fare = $fare['total_breakup']['api_total_fare_publish'];
					// debug($markup_Arr);
					$this->calculate_paxwise_markup($flight_record, $markup_Arr ['value_type'], $markup_Arr ['generic_public_value'], $currency_symbol, $conversionrate, 'public', $markup_control);
					$this->calculate_paxwise_markup($flight_record, $markup_Arr ['value_type'], $markup_Arr ['generic_private_value'], $currency_symbol, $conversionrate, 'private', $markup_control);
					$fare ['price_breakup'] ['admin_markup'] = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['generic_public_value'], $total_fare, $multiplier, $markup_control);
					$fare ['price_breakup'] ['private_admin_markup'] = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['generic_private_value'], $private_total_fare, $multiplier, $markup_control);
					$fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['admin_markup'];
					$fare ['api_total_display_fare'] += $fare ['price_breakup'] ['admin_markup'];

					$fare ['total_breakup'] ['api_total_tax_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					$fare ['api_total_display_fare_publish'] += $fare ['price_breakup'] ['private_admin_markup'];

					$fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['admin_markup'];
						}
					}
					
				} else{
					//debug($markup);die;
					$markup_Arr = $admin_markup_generic['markup'];
					// debug($fare['total_breakup']);die("markup_Arr");
					// $total_fare = $fare ['api_total_display_fare'];
					$total_fare = $fare['total_breakup']['api_total_fare'];
					$private_total_fare = $fare['total_breakup']['api_total_fare_publish'];
					// debug($markup_Arr);die;
					$this->calculate_paxwise_markup($flight_record, $markup_Arr ['value_type'], $markup_Arr ['generic_public_value'], $currency_symbol, $conversionrate, "public", 'public', $markup_control);
					$this->calculate_paxwise_markup($flight_record, $markup_Arr ['value_type'], $markup_Arr ['generic_private_value'], $currency_symbol, $conversionrate, "private", $markup_control);
					$fare ['price_breakup'] ['admin_markup'] = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['generic_public_value'], $total_fare, $multiplier, $markup_control);
					$fare ['price_breakup'] ['private_admin_markup'] = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['generic_private_value'], $private_total_fare, $multiplier, $markup_control);
					$fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['admin_markup'];
					$fare ['api_total_display_fare'] += $fare ['price_breakup'] ['admin_markup'];

					$fare ['total_breakup'] ['api_total_tax_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					$fare ['api_total_display_fare_publish'] += @$fare ['price_breakup'] ['private_admin_markup'];

					$fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['admin_markup'];
					//debug($fare);die;
					// debug($fare);exit;
					// update breakup also
					/*$pax_breakup = & $flight_record ['passenger_breakup'];
					foreach ( $pax_breakup as $px_key => & $pax ) {
						$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['value'], $pax ['total_price'], $pax ['pass_no'] );
						$pax ['tax'] += $pax_ad_markup;
						$pax ['total_price'] += $pax_ad_markup;
					}*/
				}
			}
			// debug($flight_record);echo 'last';//die;
			$this->add_fare_details ( $flight_record, $currency_symbol, $conversionrate, $markup_control);
			// debug($flight_record);echo 'last';die;
			}
			
		}
	}
	function update_user_markup03122018(& $flight_record, $multiplier, $markup, $currency_symbol, $conversionrate, $markup_control) { 
		error_reporting(0); ini_set('display_error', 'on');
		$ci = &get_instance ();
		// debug($markup);die;
		if ($markup ['status'] == SUCCESS_STATUS) {
		// if ($markup ['status'] == SUCCESS_STATUS) {
			
			$markup = $markup ['markup'];
			$fare_array = & $flight_record ['fare'];
			// debug($markup);die('update_admin_markup');
			if (is_array($fare_array)) {
				
				$flight_departure_strtotime = isset($flight_record['DepartureDateTime_r'][0]) ? strtotime($flight_record['DepartureDateTime_r'][0]) : strtotime($flight_record ['flight_details'] ['summary'] [0] ['origin']['datetime']);
				foreach ( $fare_array as $f_key => & $fare ) {
				$airline_code = isset($flight_record ['flight_details'] ['summary'] [$f_key] ['operator_code']) ? $flight_record ['flight_details'] ['summary'] [$f_key] ['operator_code'] : $flight_record ['flight_details'] ['summary'] [0] ['operator_code'];
				//debug($markup [$airline_code]);die;
				if (isset ( $markup [$airline_code] ) ) {
					// debug($markup [$airline_code]);die;
					$sdate = strtotime($markup [$airline_code]['start_date']);
					$edate = strtotime($markup [$airline_code]['end_date']);
					$sprice = $markup [$airline_code]['start_price'];
					$eprice = $markup [$airline_code]['end_price'];
					if (($markup [$airline_code]['calendar_public_value'] != "") 
					&& (($markup [$airline_code]['calendar_public_value'] != 0.00) || $markup [$airline_code]['calendar_private_value'] != 0.00) 
					&& (($sdate <= $flight_departure_strtotime) 
					&& ($edate >= $flight_departure_strtotime))) {
						$markup_Arr = $markup [$airline_code];
					$total_fare = $fare['total_breakup']['api_total_fare'];
					$private_total_fare = $fare['total_breakup']['api_total_fare_publish'];
					//debug($markup_Arr);die('calendar_public_value');
					if (($markup_Arr['calendar_public_value'] != "") && ($markup_Arr['calendar_public_value'] != 0.00)) {
						$this->calculate_paxwise_markup($flight_record, $markup_Arr ['calendar_value_type'], $markup_Arr ['calendar_public_value'], $currency_symbol, $conversionrate, "public", $markup_control);
						$fare ['price_breakup'] ['admin_markup'] = $this->calculate_markup ( $markup_Arr ['calendar_value_type'], $markup_Arr ['calendar_public_value'], $total_fare, $multiplier, $markup_control);
							    $fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['admin_markup'];
							    $fare ['api_total_display_fare'] += $fare ['price_breakup'] ['admin_markup'];

							    $fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['admin_markup'];
							    $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_public_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
					} else if (($markup_Arr ['airline_public_value'] != 0.00) && ($markup_Arr ['airline_public_value'] != "")) {
						$this->calculate_paxwise_markup($flight_record, $markup_Arr ['airline_value_type'], $markup_Arr ['airline_public_value'], $currency_symbol, $conversionrate, 'public', $markup_control);
								$fare ['price_breakup'] ['admin_markup'] = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_public_value'], $total_fare, $multiplier, $markup_control);
							    $fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['admin_markup'];
							    $fare ['api_total_display_fare'] += $fare ['price_breakup'] ['admin_markup'];

							    $fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['admin_markup'];
							    $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_public_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
							} else {
								$markup_Arr1 = $admin_markup_generic['markup'];
								$this->calculate_paxwise_markup($flight_record, $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_public_value'], $currency_symbol, $conversionrate, 'public', $markup_control);
								$fare ['price_breakup'] ['admin_markup'] = $this->calculate_markup ( $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_public_value'], $total_fare, $multiplier, $markup_control);
								$fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['admin_markup'];
					            $fare ['api_total_display_fare'] += $fare ['price_breakup'] ['admin_markup'];
					            $fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['admin_markup'];
					            $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['generic_public_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
							}
							if (($markup_Arr ['calendar_private_value'] != 0.00) && ($markup_Arr ['calendar_private_value'] != "")) {
								$this->calculate_paxwise_markup($flight_record, $markup_Arr ['calendar_value_type'], $markup_Arr ['calendar_private_value'], $currency_symbol, $conversionrate, 'private', $markup_control);
								$fare ['price_breakup'] ['private_admin_markup'] = $this->calculate_markup ( $markup_Arr ['calendar_value_type'], $markup_Arr ['calendar_private_value'], $private_total_fare, $multiplier, $markup_control);
							      $fare ['total_breakup'] ['api_total_tax_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					              $fare ['api_total_display_fare_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					              $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
							} else if (($markup_Arr ['airline_private_value'] != 0.00) && ($markup_Arr ['airline_private_value'] != "")) { //die('aa');
								  $this->calculate_paxwise_markup($flight_record, $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $currency_symbol, $conversionrate, 'private', $markup_control);
								  $fare ['price_breakup'] ['private_admin_markup'] = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $private_total_fare, $multiplier, $markup_control);
							      $fare ['total_breakup'] ['api_total_tax_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					              $fare ['api_total_display_fare_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					              $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
							} else {
								  $markup_Arr1 = $admin_markup_generic['markup'];
								  $this->calculate_paxwise_markup($flight_record, $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_private_value'], $currency_symbol, $conversionrate, 'private', $markup_control);
									$fare ['price_breakup'] ['private_admin_markup'] = $this->calculate_markup ( $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_private_value'], $private_total_fare, $multiplier, $markup_control);
									$fare ['total_breakup'] ['api_total_tax_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					                $fare ['api_total_display_fare_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					                $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['generic_private_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
							}
						//debug($fare); die;
						//debug($fare_array[$f_key]);
					} else if ((($markup [$airline_code]['fare_public_value'] != "") && ($markup [$airline_code]['fare_public_value'] != 0.00) || ($markup [$airline_code]['fare_private_value'] != "") && ($markup [$airline_code]['fare_private_value'] != 0.00))) {
						$markup_Arr = $markup [$airline_code];
						$total_fare = $fare['total_breakup']['api_total_fare'];
					    $private_total_fare = $fare['total_breakup']['api_total_fare_publish'];
						//debug($fare);die('fare');
						$patternfareBasicfare_code = $markup_Arr['fare_code'];
						$fareBasicfare_fare_base_type = $markup_Arr['fare_base_type'];
						$searchfareBasicfare_fare_base_type = $fare['publicFareBasisCodes'][0]['publicFareBasisCodes_value'];
						$myflag = $this->fareBasicCondition($searchfareBasicfare_fare_base_type, $fareBasicfare_fare_base_type, $patternfareBasicfare_code);
						if (($markup_Arr['fare_public_value'] != "") && ($markup_Arr['fare_public_value'] != 0.00) && ($myflag == 1)) {
							$this->calculate_paxwise_markup($flight_record, $markup_Arr ['fare_value_type'], $markup_Arr ['fare_public_value'], $currency_symbol, $conversionrate, 'public', $markup_control);
						$fare ['price_breakup'] ['admin_markup'] = $this->calculate_markup ( $markup_Arr ['fare_value_type'], $markup_Arr ['fare_public_value'], $total_fare, $multiplier, $markup_control);
							    $fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['admin_markup'];
							    $fare ['api_total_display_fare'] += $fare ['price_breakup'] ['admin_markup'];

							    $fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['admin_markup'];
							    $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_public_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
					} else if (($markup_Arr ['airline_public_value'] != 0.00) && ($markup_Arr ['airline_public_value'] != "")) {
								$this->calculate_paxwise_markup($flight_record, $markup_Arr ['airline_value_type'], $markup_Arr ['airline_public_value'], $currency_symbol, $conversionrate, 'public', $markup_control);
								$fare ['price_breakup'] ['admin_markup'] = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_public_value'], $total_fare, $multiplier, $markup_control);
							    $fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['admin_markup'];
							    $fare ['api_total_display_fare'] += $fare ['price_breakup'] ['admin_markup'];

							    $fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['admin_markup'];
							    $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_public_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
							} else {
								$markup_Arr1 = $admin_markup_generic['markup'];
								$this->calculate_paxwise_markup($flight_record, $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_public_value'], $currency_symbol, $conversionrate, 'public', $markup_control);
								$fare ['price_breakup'] ['admin_markup'] = $this->calculate_markup ( $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_public_value'], $total_fare, $multiplier, $markup_control);
								$fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['admin_markup'];
					            $fare ['api_total_display_fare'] += $fare ['price_breakup'] ['admin_markup'];
					            $fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['admin_markup'];
					            $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['generic_public_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
							}
							if (($markup_Arr ['fare_private_value'] != 0.00) && ($markup_Arr ['fare_private_value'] != "")  && ($myflag == 1)) {
								$this->calculate_paxwise_markup($flight_record, $markup_Arr ['fare_value_type'], $markup_Arr ['fare_private_value'], $currency_symbol, $conversionrate, 'private', $markup_control);
								$fare ['price_breakup'] ['private_admin_markup'] = $this->calculate_markup ( $markup_Arr ['fare_value_type'], $markup_Arr ['fare_private_value'], $private_total_fare, $multiplier, $markup_control);
							      $fare ['total_breakup'] ['api_total_tax_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					              $fare ['api_total_display_fare_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					              $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
							} else if (($markup_Arr ['airline_private_value'] != 0.00) && ($markup_Arr ['airline_private_value'] != "")) { //die('aa');
								  $this->calculate_paxwise_markup($flight_record, $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $currency_symbol, $conversionrate, 'private', $markup_control);
								  $fare ['price_breakup'] ['private_admin_markup'] = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $private_total_fare, $multiplier, $markup_control);
							      $fare ['total_breakup'] ['api_total_tax_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					              $fare ['api_total_display_fare_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					              $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
							} else {
								  $markup_Arr1 = $admin_markup_generic['markup'];
								  $this->calculate_paxwise_markup($flight_record, $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_private_value'], $currency_symbol, $conversionrate, 'private', $markup_control);
									$fare ['price_breakup'] ['private_admin_markup'] = $this->calculate_markup ( $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_private_value'], $private_total_fare, $multiplier, $markup_control);
									$fare ['total_breakup'] ['api_total_tax_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					                $fare ['api_total_display_fare_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					                $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['generic_private_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
							}
					} else {
						$markup_Arr = $markup [$airline_code];
						// debug($markup_Arr);die;
						$total_fare = $fare['total_breakup']['api_total_fare'];
						$private_total_fare = $fare['total_breakup']['api_total_fare_publish'];; 
						if (($markup_Arr ['airline_public_value'] != 0.00) || ($markup_Arr ['airline_private_value'] != 0.00)) {
							
							// echo "pankaj";debug($markup_Arr);exit;
							if (($markup_Arr ['airline_public_value'] != 0.00) && ($markup_Arr ['airline_public_value'] != "")) {
								// echo 'pankaj22';debug($markup_Arr);die;
								$this->calculate_paxwise_markup($flight_record, $markup_Arr ['airline_value_type'], $markup_Arr ['airline_public_value'], $currency_symbol, $conversionrate, 'public', $markup_control);
								$this->calculate_paxwise_markup($flight_record, $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $currency_symbol, $conversionrate, 'private', $markup_control);
								// echo $total_fare;
								// echo 'markb';debug($markup_Arr);//die;
								// echo 'markb';debug($fare);//die;
								$fare ['price_breakup'] ['admin_markup'] = $this->calculate_markup ($markup_Arr ['airline_value_type'], $markup_Arr ['airline_public_value'], $total_fare, $multiplier, $markup_control);
								// echo 'marka';debug($fare);die;
							    $fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['admin_markup'];
							    $fare ['api_total_display_fare'] += $fare ['price_breakup'] ['admin_markup'];

							    $fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['admin_markup'];
							    $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_public_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
							} else {
								$markup_Arr1 = $admin_markup_generic['markup'];
								$this->calculate_paxwise_markup($flight_record, $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_public_value'], $currency_symbol, $conversionrate, 'public', $markup_control);
								$fare ['price_breakup'] ['admin_markup'] = $this->calculate_markup ( $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_public_value'], $total_fare, $multiplier, $markup_control);
								$fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['admin_markup'];
					            $fare ['api_total_display_fare'] += $fare ['price_breakup'] ['admin_markup'];
					            $fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['admin_markup'];
					            $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['generic_public_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
							}
							
							if (($markup_Arr ['airline_private_value'] != 0.00) && ($markup_Arr ['airline_private_value'] != "")) { //die('aa');
								 // $this->calculate_paxwise_markup($flight_record, $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $currency_symbol, $conversionrate, 'private');
								  $fare ['price_breakup'] ['private_admin_markup'] = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $total_fare_private, $multiplier, $markup_control);
								 // debug($fare);exit;
							      $fare ['total_breakup'] ['api_total_tax_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					              $fare ['api_total_display_fare_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					              $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['airline_value_type'], $markup_Arr ['airline_private_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
							} else {
								  $markup_Arr1 = $admin_markup_generic['markup'];
								  $this->calculate_paxwise_markup($flight_record, $markup_Arr1 ['value_type'], $markup_Arr1 ['generic_private_value'], $currency_symbol, $conversionrate, 'private', $markup_control);
									$fare ['price_breakup'] ['private_admin_markup'] = $this->calculate_markup ($markup_Arr1 ['value_type'], $markup_Arr1 ['generic_private_value'], $private_total_fare, $multiplier, $markup_control);
									$fare ['total_breakup'] ['api_total_tax_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					                $fare ['api_total_display_fare_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					                $pax_breakup = & $flight_record ['passenger_breakup'];
									if (valid_array($pax_breakup)) {
										foreach ( $pax_breakup as $px_key => & $pax ) {
										$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['generic_private_value'], $pax ['total_price'], $pax ['pass_no'], $markup_control);
										$pax ['tax'] += $pax_ad_markup;
										$pax ['total_price'] += $pax_ad_markup;
									}
									}
							}
							
							 //debug($fare);//exit;
							// update breakup also
						} else {
							/*$markup_Arr = $admin_markup_generic['markup'];

							$total_fare = $fare ['api_total_display_fare'];
							// debug($markup_Arr);
							$fare ['price_breakup'] ['admin_markup'] = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['value'], $total_fare, $multiplier );
							$fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['admin_markup'];
							$fare ['api_total_display_fare'] += $fare ['price_breakup'] ['admin_markup'];

							$fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['admin_markup'];*/
							$markup_Arr = $admin_markup_generic['markup'];
					//debug($markup_Arr);die("markup_Arr");
					$total_fare = $fare['total_breakup']['api_total_fare'];
					$private_total_fare = $fare['total_breakup']['api_total_fare_publish'];
					// debug($markup_Arr);
					$this->calculate_paxwise_markup($flight_record, $markup_Arr ['value_type'], $markup_Arr ['generic_public_value'], $currency_symbol, $conversionrate, 'public', $markup_control);
					$this->calculate_paxwise_markup($flight_record, $markup_Arr ['value_type'], $markup_Arr ['generic_private_value'], $currency_symbol, $conversionrate, 'private', $markup_control);
					$fare ['price_breakup'] ['admin_markup'] = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['generic_public_value'], $total_fare, $multiplier, $markup_control);
					$fare ['price_breakup'] ['private_admin_markup'] = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['generic_private_value'], $private_total_fare, $multiplier, $markup_control);
					$fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['admin_markup'];
					$fare ['api_total_display_fare'] += $fare ['price_breakup'] ['admin_markup'];

					$fare ['total_breakup'] ['api_total_tax_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					$fare ['api_total_display_fare_publish'] += $fare ['price_breakup'] ['private_admin_markup'];

					$fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['admin_markup'];
						}
					}
					
				} else{
					//debug($markup);die;
					$markup_Arr = $markup;
					// debug($fare['total_breakup']);die("markup_Arr");
					// $total_fare = $fare ['api_total_display_fare'];
					$total_fare = $fare['total_breakup']['api_total_fare'];
					$private_total_fare = $fare['total_breakup']['api_total_fare_publish'];
					// debug($markup_Arr);die;
					$this->calculate_paxwise_markup($flight_record, $markup_Arr ['value_type'], $markup_Arr ['generic_public_value'], $currency_symbol, $conversionrate, "public", 'public', $markup_control);
					$this->calculate_paxwise_markup($flight_record, $markup_Arr ['value_type'], $markup_Arr ['generic_private_value'], $currency_symbol, $conversionrate, "private", $markup_control);
					$fare ['price_breakup'] ['admin_markup'] = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['generic_public_value'], $total_fare, $multiplier, $markup_control);
					$fare ['price_breakup'] ['private_admin_markup'] = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['generic_private_value'], $private_total_fare, $multiplier, $markup_control);
					$fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['admin_markup'];
					$fare ['api_total_display_fare'] += $fare ['price_breakup'] ['admin_markup'];

					$fare ['total_breakup'] ['api_total_tax_publish'] += $fare ['price_breakup'] ['private_admin_markup'];
					$fare ['api_total_display_fare_publish'] += @$fare ['price_breakup'] ['private_admin_markup'];

					$fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['admin_markup'];
					//debug($fare);die;
					// debug($fare);exit;
					// update breakup also
					/*$pax_breakup = & $flight_record ['passenger_breakup'];
					foreach ( $pax_breakup as $px_key => & $pax ) {
						$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['value'], $pax ['total_price'], $pax ['pass_no'] );
						$pax ['tax'] += $pax_ad_markup;
						$pax ['total_price'] += $pax_ad_markup;
					}*/
				}
			}
			// debug($flight_record);echo 'last';die;
			// echo $conversionrate;
			// echo $currency_symbol;die;
			$this->add_fare_details( $flight_record, $currency_symbol, $conversionrate, $markup_control);
			// debug($flight_record);echo 'last';die;
			}
			
		}
	}
	function update_dist_markup(& $flight_record, $multiplier, $markup) {
		$ci = &get_instance ();
		if ($markup ['status'] == SUCCESS_STATUS) {
			$markup = $markup ['dist_markup'];
			$fare_array = & $flight_record ['fare'];

			foreach ( $fare_array as $f_key => & $fare ) {
				$airline_code = $flight_record ['flight_details'] ['summary'] [$f_key] ['operator_code'];

				if (isset ( $markup [$airline_code] )) {
					$markup_Arr = $markup [$airline_code];
					$total_fare = $fare ['api_total_display_fare'];

					$fare ['price_breakup'] ['dist_markup'] = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['value'], $total_fare, $multiplier );
					$fare ['total_breakup'] ['api_total_tax'] += $fare ['price_breakup'] ['dist_markup'];
					$fare ['api_total_display_fare'] += $fare ['price_breakup'] ['dist_markup'];

					$fare ['price_breakup'] ['app_user_buying_price'] += $fare ['price_breakup'] ['dist_markup'];

					// update breakup also
					$pax_breakup = & $flight_record ['passenger_breakup'];
					foreach ( $pax_breakup as $px_key => & $pax ) {
						$pax_ad_markup = $this->calculate_markup ( $markup_Arr ['value_type'], $markup_Arr ['value'], $pax ['total_price'], $pax ['pass_no'] );
						$pax ['tax'] += $pax_ad_markup;
						$pax ['total_price'] += $pax_ad_markup;
					}
				}
			}
			$this->add_fare_details ( $flight_record );
		}
	}

	/**
	 * calculate markup
	 *
	 * @param string $markup_type
	 * @param float $markup_val
	 * @param float $total_fare
	 */
	private function calculate_paxwise_markup(& $flight_record, $markup_type, $markup_val, $currency_symbol, $conversionrate, $ptype, $markup_control) {


	
		//echo $ptype;//die;
		
		if ( valid_array($markup_control)) {
			// debug($markup_control);
			 //debug($flight_record);die('calculate_paxwise_markup');
			$calculate_status = "true";
			$Makup_add_status = "true";
			

			 switch ($markup_control['markup_calculation']) {
			 	case '1':
			 		$calculate_status = "true";
			 		break;
			 	case '0':
			 		$calculate_status = "false";
			 		break;
			 	
			 	default:
			 		$calculate_status = "true";
			 		break;
			 }
			 switch ($markup_control['Adding_Markup']) {
			 	case '1':
			 		$Makup_add_status = "true";
			 		break;
			 	case '0':
			 		$Makup_add_status = "false";
			 		break;
			 	
			 	default:
			 		$Makup_add_status = "true";
			 		break;
			 }

			 if($ptype == "public"){
				if (isset($flight_record['Adults_Base_Price'])) {
					$total_fare = $flight_record['Adults_Base_Price'];
					if ($calculate_status) {
					$total_fare = $flight_record['Adults_Base_Price'];
					} else {
						$total_fare = $flight_record['Adults_Base_Price'] + $flight_record['Adults_Tax_Price'];
					}
						if ($markup_type == 'percentage') {
						$markup_amount = (floatval ( $total_fare ) * floatval ( $markup_val )) / 100;
						$markup_amount = number_format ( $markup_amount, 3, '.', '' );
						} else {
							$markup_amount = floatval ( $markup_val );
							$markup_amount = number_format ( $markup_amount, 3, '.', '' );
						}
						// echo "markup_amount".$markup_amount;
					if ($Makup_add_status) {
						$flight_record['Adults_Base_Price'] += $markup_amount;
					} else {
						$flight_record['Adults_Tax_Price'] += $markup_amount;
					} 
					$flight_record['Adults_Base_Price'] = $flight_record['Adults_Base_Price'] * $conversionrate;
					$flight_record['Adults_Tax_Price'] = ($flight_record['Adults_Tax_Price'] * $conversionrate);
					if (isset($flight_record['Childs_Base_Price'])) {
						$total_fare = $flight_record['Childs_Base_Price'];
					if ($calculate_status) {
						$total_fare = $flight_record['Childs_Base_Price'];
					} else {
						$total_fare = $flight_record['Childs_Base_Price'] + $flight_record['Childs_Tax_Price'];
					}
						if ($markup_type == 'percentage') {
						$markup_amount = (floatval ( $total_fare ) * floatval ( $markup_val )) / 100;
						$markup_amount = number_format ( $markup_amount, 3, '.', '' );
						} else {
							$markup_amount = floatval ( $markup_val );
							$markup_amount = number_format ( $markup_amount, 3, '.', '' );
						}
						if ($Makup_add_status) {
							$flight_record['Childs_Base_Price'] += $markup_amount;
						} else {
							$flight_record['Childs_Tax_Price'] += $markup_amount;
						}
						
						//$flight_record['Childs_Base_Price'] += $markup_amount;
						$flight_record['Childs_Base_Price'] = $flight_record['Childs_Base_Price'] * $conversionrate;
						$flight_record['Childs_Tax_Price'] = ($flight_record['Childs_Tax_Price'] * $conversionrate);
					}
					
					if (isset($flight_record['Infants_Base_Price'])) {
						$total_fare = $flight_record['Infants_Base_Price'];
						if ($calculate_status) {
							$total_fare = $flight_record['Infants_Base_Price'];
						} else {
							$total_fare = $flight_record['Infants_Base_Price'] + $flight_record['Infants_Tax_Price'];
						}
						if ($markup_type == 'percentage') {
						$markup_amount = (floatval ( $total_fare ) * floatval ( $markup_val )) / 100;
						$markup_amount = number_format ( $markup_amount, 3, '.', '' );
						} else {
							$markup_amount = floatval ( $markup_val );
							$markup_amount = number_format ( $markup_amount, 3, '.', '' );
						}
						if ($Makup_add_status) {
							$flight_record['Infants_Base_Price'] += $markup_amount;
						} else {
							$flight_record['Infants_Tax_Price'] += $markup_amount;
						}
						$flight_record['Infants_Base_Price'] = $flight_record['Infants_Base_Price'] * $conversionrate;
						$flight_record['Infants_Tax_Price'] = ($flight_record['Infants_Tax_Price'] * $conversionrate);
					}

					 // echo "After";debug($flight_record);die;
				} else {
					  // debug($flight_record);echo('calculate_paxwise_markup sabre');
					 if (isset($flight_record['PCode'])) {
					 	for ($i=0; $i < count($flight_record['PCode']); $i++) { 
					 		// debug($flight_record);die;
					 		$total_fare = $flight_record['PEquivFare_org'][$i];
					 		if ($calculate_status) {
							$total_fare = $flight_record['PEquivFare_org'][$i];
							} else {
								$total_fare = $flight_record['PTotalFare_org'][$i];
							}
						 	if ($markup_type == 'percentage') {
								$markup_amount = (floatval ( $total_fare ) * floatval ( $markup_val )) / 100;
								$markup_amount = number_format ( $markup_amount, 3, '.', '' );
								} else {
									$markup_amount = (floatval ( $markup_val ));
									$markup_amount = number_format ( $markup_amount, 3, '.', '' );
								}
							$flight_record['PTotalFare_org'][$i] += $markup_amount;
							if ($Makup_add_status) {
								$flight_record['PEquivFare_org'][$i] += $markup_amount;
							} else {
								$flight_record['PTaxFare'][$i] += $markup_amount;
							}
							$flight_record['PEquivFare_org'][$i] = $flight_record['PEquivFare_org'][$i] * $conversionrate;
							$flight_record['PTaxFare'][$i] = $flight_record['PTaxFare'][$i] * $conversionrate;
							$flight_record['PTotalFare_org'][$i] = $flight_record['PTotalFare_org'][$i] * $conversionrate;
							//echo $flight_record['price']['api_total_display_fare_normal']."<br/>".$flight_record['PTotalFare_org'][$i]."<br/>".$flight_record['Private_PTotalFare_org'][$i]."<br/>";
							// debug($flight_record);die('dfdfds');
							/*if(($flight_record['price']['api_total_display_fare_normal'] != 0) && ($flight_record['price']['api_total_display_fare'] > $flight_record['price']['api_total_display_fare_normal'])){
								//debug('dddd');die;
								$flight_record['PTotalFare_org'] = array();
								$flight_record['PTotalFare_org'] = $flight_record['Private_PTotalFare_org'];
								$flight_record['PEquivFare_org'] = $flight_record['Private_PEquivFare_org'];
								$flight_record['PTaxFare'] = $flight_record['Private_PTaxFare'];

						 	}*/
						 	//debug($flight_record);die('dfdfds');
						 }
					 }

					//  debug($flight_record);die;
				}
			} else{

				// debug($flight_record);die('public else');
				if (isset($flight_record['PCode'])) {
					 	for ($i=0; $i < count($flight_record['PCode']); $i++) { 
					 		$total_fare = $flight_record['Private_PEquivFare_org'][$i];
					 		if ($calculate_status) {
							$total_fare = $flight_record['Private_PEquivFare_org'][$i];
							} else {
								$total_fare = $flight_record['Private_PEquivFare_org'][$i] + $flight_record['Private_PTaxFare'][$i];
							}
					 		//echo $total_fare;
						 	if ($markup_type == 'percentage') {
								$private_markup_amount = (floatval ( $total_fare ) * floatval ( $markup_val )) / 100;
								$private_markup_amount = number_format ( $private_markup_amount, 3, '.', '' );
								} else {
									$private_markup_amount = (floatval ( $markup_val ));
									$private_markup_amount = number_format ( $private_markup_amount, 3, '.', '' );
								}
								//echo "<br./>pankaj".$private_markup_amount."<br/>".$i;
								// debug($flight_record['Private_PTotalFare_org'][$i]);//die;
							$flight_record['Private_PTotalFare_org'][$i] += $private_markup_amount;
							// debug($flight_record['Private_PTotalFare_org'][$i]);
							if ($Makup_add_status) {
								$flight_record['Private_PEquivFare_org'][$i] += $private_markup_amount;
							} else {
								$flight_record['Private_PTaxFare'][$i] += $private_markup_amount;
							}
							$flight_record['Private_PEquivFare_org'][$i] = $flight_record['Private_PEquivFare_org'][$i] * $conversionrate;
							$flight_record['Private_PTaxFare'][$i] = $flight_record['PTaxFare'][$i] * $conversionrate;
							$flight_record['Private_PTotalFare_org'][$i] = $flight_record['Private_PTotalFare_org'][$i] * $conversionrate;
							// debug($flight_record['Private_PTotalFare_org']);//die;
							//echo $flight_record['price']['api_total_display_fare_normal']."<br/>".$flight_record['PTotalFare_org'][$i]."<br/>".$flight_record['Private_PTotalFare_org'][$i]."<br/>";
							// debug($flight_record);die('dfdfds');
						 	// echo 'Next';debug($flight_record);die('dfdfds');
						 }
					 }
			}
		} else {


				if($ptype == "public"){
				if (isset($flight_record['Adults_Base_Price'])) {
					$total_fare = $flight_record['Adults_Base_Price'];
						if ($markup_type == 'percentage') {
						$markup_amount = (floatval ( $total_fare ) * floatval ( $markup_val )) / 100;
						$markup_amount = number_format ( $markup_amount, 3, '.', '' );
						} else {
							$markup_amount = floatval ( $markup_val );
							$markup_amount = number_format ( $markup_amount, 3, '.', '' );
						}

						// echo "markup_amount".$markup_amount;
					$flight_record['Adults_Base_Price'] += $markup_amount; 
					$flight_record['Adults_Base_Price'] = $flight_record['Adults_Base_Price'] * $conversionrate;
					$flight_record['Adults_Tax_Price'] = ($flight_record['Adults_Tax_Price'] * $conversionrate);
					if (isset($flight_record['Childs_Base_Price'])) {
						$total_fare = $flight_record['Childs_Base_Price'];
						if ($markup_type == 'percentage') {
						$markup_amount = (floatval ( $total_fare ) * floatval ( $markup_val )) / 100;
						$markup_amount = number_format ( $markup_amount, 3, '.', '' );
						} else {
							$markup_amount = floatval ( $markup_val );
							$markup_amount = number_format ( $markup_amount, 3, '.', '' );
						}
						$flight_record['Childs_Base_Price'] += $markup_amount;
						$flight_record['Childs_Base_Price'] = $flight_record['Childs_Base_Price'] * $conversionrate;
						$flight_record['Childs_Tax_Price'] = ($flight_record['Childs_Tax_Price'] * $conversionrate);
					}

					if (isset($flight_record['Infants_Base_Price'])) {
						$total_fare = $flight_record['Infants_Base_Price'];
						if ($markup_type == 'percentage') {
						$markup_amount = (floatval ( $total_fare ) * floatval ( $markup_val )) / 100;
						$markup_amount = number_format ( $markup_amount, 3, '.', '' );
						} else {
							$markup_amount = floatval ( $markup_val );
							$markup_amount = number_format ( $markup_amount, 3, '.', '' );
						}
						$flight_record['Infants_Base_Price'] += $markup_amount;
						$flight_record['Infants_Base_Price'] = $flight_record['Infants_Base_Price'] * $conversionrate;
						$flight_record['Infants_Tax_Price'] = ($flight_record['Infants_Tax_Price'] * $conversionrate);
					}

				/*	if($_SERVER['REMOTE_ADDR']=="157.51.101.245") {
			 debug($flight_record);exit;
			 
			}*/
			
					 // echo "After";debug($flight_record);die;
				} else {


					  // debug($flight_record);echo('calculate_paxwise_markup sabre');
					 if (isset($flight_record['PCode'])) {
					 	for ($i=0; $i < count($flight_record['PCode']); $i++) { 
					 		// debug($flight_record);die;
					 		$total_fare = $flight_record['PEquivFare_org'][$i];
						 	if ($markup_type == 'percentage') {
								$markup_amount = (floatval ( $total_fare ) * floatval ( $markup_val )) / 100;
								$markup_amount = number_format ( $markup_amount, 3, '.', '' );
								} else {
									$markup_amount = (floatval ( $markup_val ));
									$markup_amount = number_format ( $markup_amount, 3, '.', '' );
								}
							$flight_record['PTotalFare_org'][$i] += $markup_amount;
							$flight_record['PEquivFare_org'][$i] += $markup_amount;
							$flight_record['PEquivFare_org'][$i] = $flight_record['PEquivFare_org'][$i] * $conversionrate;
							$flight_record['PTaxFare'][$i] = $flight_record['PTaxFare'][$i] * $conversionrate;
							$flight_record['PTotalFare_org'][$i] = $flight_record['PTotalFare_org'][$i] * $conversionrate;
							//echo $flight_record['price']['api_total_display_fare_normal']."<br/>".$flight_record['PTotalFare_org'][$i]."<br/>".$flight_record['Private_PTotalFare_org'][$i]."<br/>";
							// debug($flight_record);die('dfdfds');
							/*if(($flight_record['price']['api_total_display_fare_normal'] != 0) && ($flight_record['price']['api_total_display_fare'] > $flight_record['price']['api_total_display_fare_normal'])){
								//debug('dddd');die;
								$flight_record['PTotalFare_org'] = array();
								$flight_record['PTotalFare_org'] = $flight_record['Private_PTotalFare_org'];
								$flight_record['PEquivFare_org'] = $flight_record['Private_PEquivFare_org'];
								$flight_record['PTaxFare'] = $flight_record['Private_PTaxFare'];

						 	}*/
						 	//debug($flight_record);die('dfdfds');
						 }
					 }
					//  debug($flight_record);die;
				}
			} else{

				// debug($flight_record);die('public else');
				if (isset($flight_record['PCode'])) {
					 	for ($i=0; $i < count($flight_record['PCode']); $i++) { 
					 		$total_fare = $flight_record['Private_PEquivFare_org'][$i];
					 		//echo $total_fare;
						 	if ($markup_type == 'percentage') {
								$private_markup_amount = (floatval ( $total_fare ) * floatval ( $markup_val )) / 100;
								$private_markup_amount = number_format ( $private_markup_amount, 3, '.', '' );
								} else {
									$private_markup_amount = (floatval ( $markup_val ));
									$private_markup_amount = number_format ( $private_markup_amount, 3, '.', '' );
								}
								//echo "<br./>pankaj".$private_markup_amount."<br/>".$i;
								// debug($flight_record['Private_PTotalFare_org'][$i]);//die;
							$flight_record['Private_PTotalFare_org'][$i] += $private_markup_amount;
							// debug($flight_record['Private_PTotalFare_org'][$i]);
							$flight_record['Private_PEquivFare_org'][$i] += $private_markup_amount;
							$flight_record['Private_PEquivFare_org'][$i] = $flight_record['Private_PEquivFare_org'][$i] * $conversionrate;
							$flight_record['Private_PTaxFare'][$i] = $flight_record['PTaxFare'][$i] * $conversionrate;
							$flight_record['Private_PTotalFare_org'][$i] = $flight_record['Private_PTotalFare_org'][$i] * $conversionrate;
							// debug($flight_record['Private_PTotalFare_org']);//die;
							//echo $flight_record['price']['api_total_display_fare_normal']."<br/>".$flight_record['PTotalFare_org'][$i]."<br/>".$flight_record['Private_PTotalFare_org'][$i]."<br/>";
							// debug($flight_record);die('dfdfds');
						 	// echo 'Next';debug($flight_record);die('dfdfds');
						 }
					 }
			}
		}
		

	}
	/**
	 * calculate markup
	 *
	 * @param string $markup_type
	 * @param float $markup_val
	 * @param float $total_fare
	 * @param int $multiplier
	 */
	private function calculate_markup($markup_type, $markup_val, $total_fare, $multiplier, $markup_control) {
		if ($markup_type == 'percentage') {
			$markup_amount = (floatval ( $total_fare ) * floatval ( $markup_val )) / 100;
			$markup_amount = number_format ( $markup_amount, 3, '.', '' );
		} else {
			$markup_amount = ($multiplier * floatval ( $markup_val ));
			$markup_amount = number_format ( $markup_amount, 3, '.', '' );
		}
		return $markup_amount;
	}

	/**
	 *
	 * @param array $flight_record
	 * @param array $commission
	 * @param array $tds_tax_details
	 */
	function update_user_commission(& $flight_record, $commission, $tds_tax_details, $multiplier) {
		$fare_array = & $flight_record ['fare'];
		// debug($fare_array);exit;
		foreach ( $fare_array as $f_key => & $fare ) {
			$airline_code = $flight_record ['flight_details'] ['summary'] [$f_key] ['operator_code'];
			if (isset ( $commission [$airline_code] )) {
				$commission_air = $commission [$airline_code];
				// debug($commission);
				$basic_comm_per = $commission_air ['basic'];
				$fuel_commi_per = $commission_air ['fuel'];
				$handling_charge = $commission_air ['handling_charge'] * $multiplier;

				$basic_fare = $fare ['price_breakup'] ['basic_fare'];
				$fuel_charge = $fare ['price_breakup'] ['fuel_charge'];

				// debug($basic_fare);echo '$basic_fare';
				// debug($basic_comm_per);echo '$basic_comm_per';

				$base_fare_commission = (floatval ( $basic_fare ) * floatval ( $basic_comm_per )) / 100;
				// debug($base_fare_commission);exit;
				$fuel_charge_commission = (floatval ( $fuel_charge ) * floatval ( $fuel_commi_per )) / 100;
				$agent_commission = $base_fare_commission + $fuel_charge_commission;

				if ($tds_tax_details ['tds'] > 0) {
					$agent_tds_on_commision = ($agent_commission * $tds_tax_details ['tds']) / 100;
				} else {
					$agent_tds_on_commision = 0;
				}

				if ($tds_tax_details ['service_tax'] > 0) {
					$service_tax = ($handling_charge * $tds_tax_details ['service_tax']) / 100;
				} else {
					$service_tax = 0;
				}

				$fare ['price_breakup'] ['handling_charge'] = number_format ( $handling_charge, 3, '.', '' );
				$fare ['price_breakup'] ['service_tax'] = number_format ( $service_tax, 3, '.', '' );
				$handling_charge_and_tax = $fare ['price_breakup'] ['handling_charge'] + $fare ['price_breakup'] ['service_tax'];
				$fare ['api_total_display_fare'] += $handling_charge_and_tax;
				$fare ['total_breakup'] ['api_total_tax'] += $handling_charge_and_tax;

				$fare ['price_breakup'] ['agent_tds_on_commision'] = number_format ( $agent_tds_on_commision, 3, '.', '' );
				$fare ['price_breakup'] ['agent_commission'] = number_format ( $agent_commission, 3, '.', '' );

				$app_user_buying_price = $fare ['api_total_display_fare'] - $fare ['price_breakup'] ['agent_commission'] + $fare ['price_breakup'] ['agent_tds_on_commision'];
				$fare ['price_breakup'] ['app_user_buying_price'] = $app_user_buying_price;
			}
		}
		$this->add_fare_details ( $flight_record );
	}

	/**
	 *
	 * @param array $flight_record
	 * @param array $commission
	 * @param array $tds_tax_details
	 */
	function update_dist_commission(& $flight_record, $commission, $tds_tax_details, $multiplier) {
		$fare_array = & $flight_record ['fare'];
		foreach ( $fare_array as $f_key => & $fare ) {
			$airline_code = $flight_record ['flight_details'] ['summary'] [$f_key] ['operator_code'];
			if (isset ( $commission [$airline_code] )) {
				$commission_air = $commission [$airline_code];

				$basic_comm_per = $commission_air ['basic'];
				$fuel_commi_per = $commission_air ['fuel'];
				// $handling_charge = $commission_air ['handling_charge']*$multiplier;

				$basic_fare = $fare ['price_breakup'] ['basic_fare'];
				$fuel_charge = $fare ['price_breakup'] ['fuel_charge'];

				$base_fare_commission = (floatval ( $basic_fare ) * floatval ( $basic_comm_per )) / 100;
				$fuel_charge_commission = (floatval ( $fuel_charge ) * floatval ( $fuel_commi_per )) / 100;
				$dist_commission = $base_fare_commission + $fuel_charge_commission;

				if ($tds_tax_details ['tds'] > 0) {
					$dist_tds_on_commision = ($dist_commission * $tds_tax_details ['tds']) / 100;
				} else {
					$dist_tds_on_commision = 0;
				}

				/*
				 * if ($tds_tax_details ['service_tax'] > 0) {
				 * $service_tax = ($handling_charge * $tds_tax_details ['service_tax']) / 100;
				 * } else {
				 * $service_tax = 0;
				 * }
				 *
				 *
				 * $fare ['price_breakup'] ['handling_charge'] = number_format ( $handling_charge, 3, '.', '' );
				 * $fare ['price_breakup'] ['service_tax'] = number_format ( $service_tax, 3, '.', '' );
				 * $handling_charge_and_tax = $fare ['price_breakup'] ['handling_charge'] + $fare ['price_breakup'] ['service_tax'];
				 * $fare ['api_total_display_fare'] += $handling_charge_and_tax;
				 * $fare ['total_breakup'] ['api_total_tax'] += $handling_charge_and_tax;
				 */
				$fare ['price_breakup'] ['dist_tds_on_commision'] = number_format ( $dist_tds_on_commision, 3, '.', '' );
				$fare ['price_breakup'] ['dist_commission'] = number_format ( $dist_commission, 3, '.', '' );

				// $app_user_buying_price = $fare ['api_total_display_fare'] - $fare ['price_breakup'] ['agent_commission'] + $fare ['price_breakup'] ['agent_tds_on_commision'];
				// $fare ['price_breakup'] ['app_user_buying_price'] = $app_user_buying_price;
			}
		}
		$this->add_fare_details ( $flight_record );
	}

	/**
	 * get airline list
	 *
	 * @return boolean|mixed
	 */
	function get_airline_list() {
		$CI = & get_instance ();
		$code_list = get_cache_data ( 'airline_list' );
		if (valid_array ( $code_list ) == false) {
			$code_list = $CI->db_cache_api->set_airline_code_list ();
			$code_list = $code_list ['data'];
			set_cache_data ( 'airline_list', $code_list, SCHEDULER_RELOAD_TIME_LIMIT );
		}
		return $code_list;
	}

	/**
	 * get airline class name based on class code
	 *
	 * @param
	 *        	$class_name
	 */
	function get_airline_class_label($class_name, $source = '', $operator = '') {
		$label = 'Economy';
		if (isset ( $class_name )) {
			switch ($class_name) {
				case 'R' :
					$label = 'Premium First Class';
					break;
				case 'F' :
					$label = 'First Class';
					break;
				case 'B' :
				case 'C' :
					$label = 'Business Class';
					break;
				case 'W' :
					$label = 'Premium Economy Class';
					break;
				case 'E' :
				case 'Y' :
					$label = 'Economy Class';
					break;
			}
		}
		/*
		 * if (isset ( $class_name )) {
		 * switch ($class_name) {
		 * case 'I' :
		 * case 'Z' :
		 * case 'J' :
		 * case 'C' :
		 * case 'D' :$label = 'Business Class';
		 * break;
		 * case 'G' :
		 * $label = ($operator == 'AI') ? 'Economy' : 'Business';
		 * break;
		 * case 'W' :
		 * $label = ($operator == 'LH' || $operator == 'AF') ? 'Premium Economy' : ($operator == 'EY') ? 'Business':'Economy';
		 * break;
		 * case 'B' :
		 * case 'O' : if($operator == 'EK') {$label = 'Business Class';} else {$label = 'Economy';}// emirates business
		 * break;
		 * case 'V' :
		 * case 'H' :
		 * case 'K' :
		 * case 'S' :
		 * case 'Q' :
		 * case 'L' :
		 * case 'N' :
		 * case 'M' :
		 * case 'Y' :
		 * $label = 'Economy';
		 * break;
		 * case 'P' :
		 * $label = $operator == 'UK' ? 'Premium Economy' : 'Business Class';
		 * break;
		 * case 'S' : // FIXME
		 * $label = ($operator == 'LH' || $operator == 'AF' || $operator == 'UK') ? 'Premium Economy' : 'Economy';
		 * break;
		 * case 'R' ://qatar business domestic emonomy
		 * if($operator == 'QR' || $operator == 'BA') {$label = 'Business Class';} else {$label = 'Economy';}
		 * break;
		 * case 'T' :
		 * case 'U' :
		 * $label = $operator == 'UK' ? 'Premium Economy' : 'Economy';
		 * break;
		 * case 'A' : // FIXME
		 * $label = ($operator == 'LH' || $operator == 'AF') ? 'Premium Economy' : 'First Class';
		 * break;
		 * case 'F' :
		 * $label = 'First Class';
		 * break;
		 * default :
		 * $label = 'Economy';
		 * break;
		 * }
		 * }
		 */

		// // && strcasecmp ( $source, TRAVELPORT_FLIGHT ) == 0
		// if (empty ( $label ) == true) {
		// $label = 'Economy';
		// }
		return $label;
	}

	/*
	 * Returns the next highest integer value by rounding up value
	 */
	static function get_round_price($price) {
		$price_val = ceil ( $price );
		return $price_val;
	}

	/**
	 *
	 * @param array $active_booking_source_list
	 * @param array $safe_search_data
	 */
	function filter_booking_source($active_booking_source_list, $safe_search_data) {
		$active_source = array ();
		$booking_source = array ();
		// debug($active_booking_source_list);exit();
		if (isset ( $safe_search_data ['i_class'] ) && $safe_search_data ['is_domestic'] == 1) {
			// domestic flight
			$class = trim($safe_search_data ['i_class']);
		} else {
			if(trim($safe_search_data ['v_class'])=='Economy/Coach'){
				$safe_search_data ['v_class']='Economy';
			}
			$class = trim($safe_search_data ['v_class']);

		}

		if (isset ( $safe_search_data ['carrier'] [0] ) && ! empty ( $safe_search_data ['carrier'] [0] )) {
			$carrier = $safe_search_data ['carrier'] [0];
		} else {
			$carrier = 'all';
		}
		if (valid_array ( $active_booking_source_list )) {
			foreach ( $active_booking_source_list as $a_key => $source ) {
				// FIXME
				if (TRAVARENA_FLIGHT == $source ['source_id'] && $safe_search_data['search_type'] != 'A') {
					$booking_source [] ['source_id'] = TRAVARENA_FLIGHT;
				}

				// Indigo
				if ((in_array ( $class, array ('Economy','Premium', 'Business','First','PremiumFirst','PremiumEconomy'
						) ))) {
							if (INDIGO_FLIGHT == $source ['source_id']) {
								$booking_source [] ['source_id'] = INDIGO_FLIGHT;
							}
							if (INDIGO_SCRAP_2 == $source ['source_id']) {
								$booking_source [] ['source_id'] = INDIGO_SCRAP_2;
							}
							if (INDIGO_SCRAP_3 == $source ['source_id']) {
								$booking_source [] ['source_id'] = INDIGO_SCRAP_3;
							}
						}
						if ((in_array ( $class, array ('Economy','Premium', 'Business','First','PremiumFirst','PremiumEconomy'
						) )) ) {
							if (SPICEJET_FLIGHT == $source ['source_id']) {
								$booking_source [] ['source_id'] = SPICEJET_FLIGHT;
							}
							if (SPICEJET_SCRAP_1 == $source ['source_id']) {
								$booking_source [] ['source_id'] = SPICEJET_SCRAP_1;
							}
							if (SPICEJET_SCRAP_3 == $source ['source_id']) {
								$booking_source [] ['source_id'] = SPICEJET_SCRAP_3;
							}
						}

						if ((in_array ( $class, array ('Economy','Premium', 'Business','First','PremiumFirst','PremiumEconomy'
						) ))) {
							if (GOAIR_FLIGHT == $source ['source_id']) {
								$booking_source [] ['source_id'] = GOAIR_FLIGHT;
							}
						}
						
						if ((in_array ( $class, array ('Economy','Premium', 'Business','First','PremiumFirst','PremiumEconomy'
						) ))) {
							if (AIRCOSTA_FLIGHT == $source ['source_id']) {
								$booking_source [] ['source_id'] = AIRCOSTA_FLIGHT;
							}
						}

						
						if (in_array ( $class, array ('Economy','Premium', 'Business','First','PremiumFirst','PremiumEconomy'
						) )) {
							if (PROVAB_FLIGHT_BOOKING_SOURCE == $source ['source_id']) {
								$booking_source [] ['source_id'] = PROVAB_FLIGHT_BOOKING_SOURCE;
							}
						}

						if (in_array ( $class, array ('Economy','Premium', 'Business','First','PremiumFirst','PremiumEconomy'
						) )) {
							
							if (AMADEUS_FLIGHT_BOOKING_SOURCE == $source ['source_id']) {
								$booking_source [] ['source_id'] = AMADEUS_FLIGHT_BOOKING_SOURCE;
							}
						}

						/////for pk fare
						// debug($class);exit();
						if (in_array ( $class, array ('ALL','Economy','Premium', 'Business','First','PremiumFirst','PremiumEconomy'
						) )) {

							
							if (PK_FARE_BOOKING_SOURCE == $source ['source_id']) {
								$booking_source [] ['source_id'] = PK_FARE_BOOKING_SOURCE;
							}
						}

						/////for flydubai
						if (in_array ( $class, array ('Economy','Premium', 'Business','First','PremiumFirst','PremiumEconomy'
						) )) {
							
							if (FLYDUBAI_BOOKING_SOURCE == $source ['source_id']) {
								$booking_source [] ['source_id'] = FLYDUBAI_BOOKING_SOURCE;
							}
						}
						
						if (in_array ( $class, array ('Economy','Premium', 'Business','First','PremiumFirst','PremiumEconomy'
						) )) {
							
							if (FLYDUBAI_KBL_BOOKING_SOURCE == $source ['source_id']) {
								$booking_source [] ['source_id'] = FLYDUBAI_KBL_BOOKING_SOURCE;
							}
						}


						//for flight crs
						if (in_array ( $class, array ('Economy','Premium', 'Business','First','PremiumFirst','PremiumEconomy'
						) )) {
							
							if (PROVAB_FLIGHT_CRS_BOOKING_SOURCE == $source ['source_id']) {
								$booking_source [] ['source_id'] = PROVAB_FLIGHT_CRS_BOOKING_SOURCE;
							}
						} 

						// include booking source for GDS
						if ($carrier != 'LCC') {
							if (TRAVELPORT_FLIGHT == $source ['source_id']) {
								$booking_source [] ['source_id'] = TRAVELPORT_FLIGHT;
							}

							if (TRAVELPORT_FLIGHT_UAPI == $source ['source_id']) {
								$booking_source [] ['source_id'] = TRAVELPORT_FLIGHT_UAPI;
							}

							if (AIRARABIA_FLIGHT == $source ['source_id'] && $safe_search_data['search_type'] != 'A') {
								$booking_source [] ['source_id'] = AIRARABIA_FLIGHT;
							}
						}
			}
		}
		return $booking_source;
	}

	/**
	 * update customer info
	 *
	 * @param array $data
	 * @param string $app_reference
	 */
	function update_passenger_update($data, $app_reference) {
		$ci = & get_instance();
		if (isset ( $data ['pax_index'] ) == true and valid_array ( $data ['pax_index'] ) == true) {
			$cond['app_reference'] = $app_reference;
			foreach ( $data ['pax_index'] as $k => $v ) {
				$cond['pax_index'] = $v;
				$customer_data = array();
				if (isset($data['title'][$k]) == true) {
					$customer_data['title'] = $data['title'][$k];
				}

				if (isset($data['first_name'][$k]) == true) {
					$customer_data['first_name'] = $data['first_name'][$k];
				}

				if (isset($data['last_name'][$k]) == true) {
					$customer_data['last_name'] = $data['last_name'][$k];
				}

				if (isset($data['date_of_birth'][$k]) == true) {
					$customer_data['date_of_birth'] = $data['date_of_birth'][$k];
				}

				if (isset($data['ff_no'][$k]) == true) {
					$customer_data['ff_no'] = $data['ff_no'][$k];
				}

				if (isset($data['passport_number'][$k]) == true) {
					$customer_data['passport_number'] = $data['passport_number'][$k];
				}

				if (isset($data['passport_expiry_date'][$k]) == true) {
					$customer_data['passport_expiry_date'] = $data['passport_expiry_date'][$k];
				}

				if (valid_array($customer_data) == true) {
					$ci->custom_db->update_record('flight_booking_passenger_details', $customer_data, $cond);
				}
			}

		}

		$contact_data = array();
		if (isset($data['phone']) == true) {
			$contact_data['phone'] = $data['phone'];
		}
		if (isset($data['email']) == true) {
			$contact_data['email'] = $data['email'];
		}
			
		if (valid_array($contact_data) == true) {
			$cond1['app_reference'] = $app_reference;
			$ci->custom_db->update_record('flight_booking_details', $contact_data, $cond1);
		}
	}

	/**
	 *
	 * @param unknown_type $ini_price
	 * @param unknown_type $final_price
	 * @param unknown_type $flight_data
	 */
	function price_change_msg($ini_price, $final_price, & $flight_data)
	{
		if ($ini_price != $final_price) {
			$change_val = abs($ini_price - $final_price);
			if ($final_price > $ini_price) {
				$msg = 'Price Has Increased By '.COURSE_LIST_DEFAULT_CURRENCY_VALUE.' '.($change_val);
				$alert_class = ERROR_MESSAGE;
			} else if ($final_price < $ini_price) {
				$msg = 'Price Has Decreased By '.COURSE_LIST_DEFAULT_CURRENCY_VALUE.' '.($change_val);
				$alert_class = SUCCESS_MESSAGE;
			} else {
				$msg = 'No Change In Price.';
				$alert_class = INFO_MESSAGE;
			}
			$flight_data['msg']['price']['change_txt'] = $msg;
			$flight_data['msg']['price']['class'] = $alert_class;
		}
	}
	/**
	 * group the passenger itinerarywise
	 * Enter description here ...
	 * @param string $app_reference
	 */
	public static function group_itinerary_wise_pax_details($app_reference)
	{
		$ci = & get_instance ();
		$pax_flight_list = $ci->flight_model->get_pax_itinerary ( $app_reference);
		$itinerary_wise_pax_flight_list = array();
		foreach ($pax_flight_list as $k => $v){
			$itinerary_wise_pax_flight_list[$v['airline_code'].','.$v['flight_number']][] = $v;
		}
		return $itinerary_wise_pax_flight_list;
	}
}