<?php
/**
 * Library which has generic functions to get data
 *
 * @package    Provab Application
 * @subpackage Hotel Model
 * @author     Arjun J<arjunjgowda260389@gmail.com>
 * @version    V2
 */
Class Hotels_Model extends CI_Model
{
	private $master_search_data;

	/**
	 * return top destinations in hotel
	 */
	function hotel_top_destinations()
	{
		$query = 'Select CT.*, CN.country_name AS country from all_api_city_master CT, api_country_master CN where CT.country_code=CN.iso_country_code AND top_destination = '.ACTIVE;
		$data = $this->db->query($query)->result_array();
		return $data;
	}
		public function get_nationality($id) {
		    if($id)
		    {
		        	$query = 'Select *
		from country_list_nationality 
		where country_list='.$id ;
		$data = $this->db->query($query)->result_array();
	//	debug($data);exit;
		return $data;
		    }
		    else{
		        	$query = 'Select *
		from country_list_nationality ';
	
		$data = $this->db->query($query)->result_array();
	//	debug($data);exit;
		return $data;
		    }
	
	}
	
	public function holiday_crs_cancel_request ($app_reference,$booking_source){
	    debug("hjgh");exit;
		$data = array('status'=>'CANCELLED');
		$this->db->set($data);
        $this->db->where('app_reference',$app_reference);
        // $this->db->update('booking_global');
        $this->db->update('tour_booking_details');
        debug($this->db->affected_rows());exit;
        if( $this->db->affected_rows() > 0){
        	$result = ['status'=>1,"msg"=>"Booking Cancelled Successfully."];
        }else{
        	$result = ['status'=>0,"msg"=>"Cancel Request Sent Already."];
        }
        return $result;
	}
	
		public function get_country_list() {
		$this->db->limit ( 1000 );
		$this->db->order_by ( "country_name", "asc" );
		
		$qur = $this->db->get ( "country_list_nationality" );
	//	debug($qur->result ());exit;
		return $qur->result ();
	}
	/*
	 *
	 * Get Airport List
	 *
	 */
	function get_hotel_city_list($search_chars)
	{
		$raw_search_chars = $this->db->escape($search_chars);
		if(empty($search_chars)==false){
			$r_search_chars = $this->db->escape($search_chars.'%');
			$search_chars = $this->db->escape($search_chars.'%');
		}else{
			$r_search_chars = $this->db->escape($search_chars);
			$search_chars = $this->db->escape($search_chars);
		}
		
		$query = 'Select cm.country_name,cm.city_name,cm.origin,cm.country_code from all_api_city_master as cm where  cm.city_name like '.$search_chars.' or cm.country_name like '.$search_chars.' 
				ORDER BY cm.top_city_having_hotel desc, CASE
			WHEN	cm.city_name	LIKE	'.$raw_search_chars.'	THEN 1
			WHEN	cm.city_name	LIKE	'.$r_search_chars.'	THEN 2	
			WHEN	cm.city_name	LIKE	'.$search_chars.'	THEN 3
			ELSE 4 END, cm.top_city_having_hotel desc LIMIT 0, 30
		';
		/*$query='Select cm.country_name,cm.city_name,cm.origin,cm.country_code,vi.hotel_count from all_api_city_master as cm,hotel_count vi where cm.grn_city_id=vi.city_code and cm.city_name like '.$search_chars.' or cm.country_name like '.$search_chars.' ORDER BY vi.hotel_count desc, CASE WHEN cm.city_name LIKE '.$raw_search_chars.' THEN 1 WHEN cm.city_name LIKE '.$r_search_chars.' THEN 2 WHEN cm.city_name LIKE '.$search_chars.' THEN 3 ELSE 4 END, cm.cache_hotels_count desc LIMIT 0, 30';*/
		//debug($query);exit;
		//Select cm.country_name,cm.city_name,cm.origin,cm.country_code from all_api_city_master as cm where  cm.city_name like '.$search_chars.' 
				//ORDER BY cm.origin ASC, CASE
		//echo $query;exit;
		return $this->db->query($query)->result_array();
	}
	function get_hotel_city_list_base($search_chars)
	{
		$raw_search_chars = $this->db->escape($search_chars);
		$r_search_chars = $this->db->escape($search_chars.'%');
		$search_chars = $this->db->escape('%'.$search_chars.'%');
		$query = 'Select * from hotels_city where city_name like '.$search_chars.'
		OR country_name like '.$search_chars.' OR country_code like '.$search_chars.'
		ORDER BY top_destination DESC, CASE
			WHEN	city_name	LIKE	'.$raw_search_chars.'	THEN 1
			WHEN	country_name	LIKE	'.$raw_search_chars.'	THEN 2
			WHEN	country_code			LIKE	'.$raw_search_chars.'	THEN 3
			
			WHEN	city_name	LIKE	'.$r_search_chars.'	THEN 4
			WHEN	country_name	LIKE	'.$r_search_chars.'	THEN 5
			WHEN	country_code			LIKE	'.$r_search_chars.'	THEN 6
			
			WHEN	city_name	LIKE	'.$search_chars.'	THEN 7
			WHEN	country_name	LIKE	'.$search_chars.'	THEN 8
			WHEN	country_code			LIKE	'.$search_chars.'	THEN 9
			ELSE 10 END, 
			cache_hotels_count DESC
		LIMIT 0, 20';
		return $this->db->query($query)->result_array();
	}

	/**
	 * get all the booking source which are active for current domain
	 */
	function active_booking_source()
	{
		$query = 'select BS.source_id, BS.origin from meta_course_list AS MCL, booking_source AS BS, activity_source_map AS ASM WHERE
		MCL.origin=ASM.meta_course_list_fk and ASM.booking_source_fk=BS.origin and MCL.course_id='.$this->db->escape(META_ACCOMODATION_COURSE).'
		and BS.booking_engine_status='.ACTIVE.' AND MCL.status='.ACTIVE.' AND ASM.status="active"';
		return $this->db->query($query)->result_array();
	}
	/**
	 * return booking list
	 */
	function booking($condition=array(), $count=false, $offset=0, $limit=100000000000)
	{
		$condition = $this->custom_db->get_custom_condition($condition);
		//BT, CD, ID
		if ($count) {
			$query = 'select count(distinct(BD.app_reference)) as total_records 
					from hotel_booking_details BD
					join hotel_booking_itinerary_details AS HBID on BD.app_reference=HBID.app_reference
					join payment_option_list AS POL on BD.payment_mode=POL.payment_category_code 
					where BD.domain_origin='.get_domain_auth_id().' and BD.created_by_id ='.$GLOBALS['CI']->entity_user_id.''.$condition;

			$data = $this->db->query($query)->row_array();

			//echo $this->db->last_query();exit;
			return $data['total_records'];
		} else {
			$this->load->library('booking_data_formatter');
			$response['status'] = SUCCESS_STATUS;
			$response['data'] = array();
			$booking_itinerary_details	= array();
			$booking_customer_details	= array();
			$cancellation_details = array();
			$bd_query = 'select * from hotel_booking_details AS BD 
						WHERE BD.domain_origin='.get_domain_auth_id().' and BD.created_by_id ='.$GLOBALS['CI']->entity_user_id.''.$condition.'
						order by BD.origin desc limit '.$offset.', '.$limit;
			$booking_details = $this->db->query($bd_query)->result_array();
			$app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
			if(empty($app_reference_ids) == false) {
				$id_query = 'select * from hotel_booking_itinerary_details AS ID 
							WHERE ID.app_reference IN ('.$app_reference_ids.')';
				$cd_query = 'select * from hotel_booking_pax_details AS CD 
							WHERE CD.app_reference IN ('.$app_reference_ids.')';
				$cancellation_details_query = 'select * from hotel_cancellation_details AS HCD 
							WHERE HCD.app_reference IN ('.$app_reference_ids.')';
				$booking_itinerary_details	= $this->db->query($id_query)->result_array();
				$booking_customer_details	= $this->db->query($cd_query)->result_array();
				$cancellation_details	= $this->db->query($cancellation_details_query)->result_array();
			}
			$response['data']['booking_details']			= $booking_details;
			$response['data']['booking_itinerary_details']	= $booking_itinerary_details;
			$response['data']['booking_customer_details']	= $booking_customer_details;
			$response['data']['cancellation_details']	= $cancellation_details;
			return $response;
		}
	}
	/**
	 * Return Booking Details based on the app_reference passed
	 * @param $app_reference
	 * @param $booking_source
	 * @param $booking_status
	 */
	function get_booking_details($app_reference, $booking_source, $booking_status='')
	{
		$response['status'] = FAILURE_STATUS;
		$response['data'] = array();
		$bd_query = 'select * from hotel_booking_details AS BD WHERE BD.app_reference like '.$this->db->escape($app_reference);
		if (empty($booking_source) == false) {
			$bd_query .= '	AND BD.booking_source = '.$this->db->escape($booking_source);
		}
		if (empty($booking_status) == false) {
			$bd_query .= ' AND BD.status = '.$this->db->escape($booking_status);
		}
		$id_query = 'select * from hotel_booking_itinerary_details AS ID WHERE ID.app_reference='.$this->db->escape($app_reference);
		$cd_query = 'select * from hotel_booking_pax_details AS CD WHERE CD.app_reference='.$this->db->escape($app_reference);
		$cancellation_details_query = 'select HCD.* from hotel_cancellation_details AS HCD WHERE HCD.app_reference='.$this->db->escape($app_reference);
		$response['data']['booking_details']			= $this->db->query($bd_query)->result_array();
		$response['data']['booking_itinerary_details']	= $this->db->query($id_query)->result_array();
		$response['data']['booking_customer_details']	= $this->db->query($cd_query)->result_array();
		$response['data']['cancellation_details']	= $this->db->query($cancellation_details_query)->result_array();
		if (valid_array($response['data']['booking_details']) == true and valid_array($response['data']['booking_itinerary_details']) == true and valid_array($response['data']['booking_customer_details']) == true) {
			$response['status'] = SUCCESS_STATUS;
		}
		return $response;
	}

	/**
	 * get search data and validate it
	 */
	function get_safe_search_data($search_id)
	{
		$search_data = $this->get_search_data($search_id);
		$success = true;
		$clean_search = '';
		if ($search_data != false) {
			//validate
			//debug($search_data);exit;
			$temp_search_data = json_decode($search_data['search_data'], true);
			//debug($temp_search_data);exit;
			$clean_search = $this->clean_search_data($temp_search_data);
			$success = $clean_search['status'];
			$clean_search = $clean_search['data'];
		} else {
			$success = false;
		}
		return array('status' => $success, 'data' => $clean_search);
	}

	/**
	 * Clean up search data
	 */
	function clean_search_data($temp_search_data)
	{
		$success = true;
		//make sure dates are correct
		if ((strtotime($temp_search_data['hotel_checkin']) > time() && strtotime($temp_search_data['hotel_checkout']) > time()) || date('Y-m-d', strtotime($temp_search_data['hotel_checkin'])) == date('Y-m-d')) {
			//	if (strtotime($temp_search_data['hotel_checkin']) > strtotime($temp_search_data['hotel_checkout'])) {
			//Swap dates if not correctly set
			$clean_search['from_date'] = $temp_search_data['hotel_checkin'];
			$clean_search['to_date'] = $temp_search_data['hotel_checkout'];
			/*} else {
			 $clean_search['from_date'] = $temp_search_data['hotel_checkout'];
			 $clean_search['to_date'] = $temp_search_data['hotel_checkin'];
			 }*/
			$clean_search['no_of_nights'] = abs(get_date_difference($clean_search['from_date'], $clean_search['to_date']));
		} else {
			$success = false;
		}
		//city name and country name
			if ((strtotime($temp_search_data['hotel_checkin_1']) > time() && strtotime($temp_search_data['hotel_checkout_1']) > time()) || date('Y-m-d', strtotime($temp_search_data['hotel_checkin_1'])) == date('Y-m-d')) {

		

			//Swap dates if not correctly set

			$clean_search['from_date'] = $temp_search_data['hotel_checkin_1'];
		

			$clean_search['to_date'] = $temp_search_data['hotel_checkout_1'];

			
			$clean_search['no_of_nights'] = abs(get_date_difference($clean_search['from_date'], $clean_search['to_date']));

		} else {

			$success = false;

		}
		if (isset($temp_search_data['hotel_destination']) == true) {
			$clean_search['hotel_destination'] = $temp_search_data['hotel_destination'];
		}
		if (isset($temp_search_data['city']) == true) {
			$clean_search['location'] = $temp_search_data['city'];
			$temp_location = explode('(', $temp_search_data['city']);
			$clean_search['city_name'] = trim($temp_location[0]);
			if (isset($temp_location[1]) == true) {
				//Pop will get last element in the array since element patterns can repeat
				$clean_search['country_name'] = trim(array_pop($temp_location), '() ');
			} else {
				$clean_search['country_name'] = '';
			}
		} else {
			$success = false;
		}

		//Occupancy
		if (isset($temp_search_data['rooms']) == true) {
			$clean_search['room_count'] = abs($temp_search_data['rooms']);
		} else {
			$success = false;
		}
		if (isset($temp_search_data['adult']) == true) {
			$clean_search['adult_config'] = $temp_search_data['adult'];
		} else {
			$success = false;
		}

		if (isset($temp_search_data['child']) == true) {
			$clean_search['child_config'] = $temp_search_data['child'];
		}

		if (valid_array($temp_search_data['child'])) {
			foreach ($temp_search_data['child'] as $tc_k => $tc_v) {
				if (intval($tc_v) > 0) {
					$child_age_index = $tc_v;
					foreach($temp_search_data['childAge_'.($tc_k+1)] as $ic_k => $ic_v) {
						$clean_search['child_age'][] = $ic_v;
					}
				}
			}
		}
		if (strtolower($clean_search['country_name']) == 'india') {
			$clean_search['is_domestic'] = true;
		} else {
			$clean_search['is_domestic'] = false;
		}
		return array('data' => $clean_search, 'status' => $success);
	}

	/**
	 * get search data without doing any validation
	 * @param $search_id
	 */
	function get_search_data($search_id)
	{
		if (empty($this->master_search_data)) {
			$search_data = $this->custom_db->single_table_records('search_history', '*', array('search_type' => META_ACCOMODATION_COURSE, 'origin' => $search_id));
			if ($search_data['status'] == true) {
				$this->master_search_data = $search_data['data'][0];
			} else {
				return false;
			}
		}
		return $this->master_search_data;
	}

	/**
	 * get hotel city id of tbo from tbo hotel city list
	 * @param string $city	  city name for which id has to be searched
	 * @param string $country country name in which the city is present
	 */
	function tbo_hotel_city_id($city, $country)
	{

			// debug($city);
			// debug($country);exit();
		$response['status'] = true;
		$response['data'] = array();
		$location_details = $this->custom_db->single_table_records('hotels_city', 'country_code, origin', array('city_name like' => $city, 'country_name like' => $country));
		if ($location_details['status']) {
			$response['data'] = $location_details['data'][0];
		} else {
			$response['status'] = false;
		}
		return $response;
	}

	/**
	 *
	 * @param number $domain_origin
	 * @param string $status
	 * @param string $app_reference
	 * @param string $booking_source
	 * @param string $booking_id
	 * @param string $booking_reference
	 * @param string $confirmation_reference
	 * @param number $total_fare
	 * @param number $domain_markup
	 * @param number $level_one_markup
	 * @param string $currency
	 * @param string $hotel_name
	 * @param number $star_rating
	 * @param string $hotel_code
	 * @param number $phone_number
	 * @param string $alternate_number
	 * @param string $email
	 * @param string $payment_mode
	 * @param string $attributes
	 * @param number $created_by_id
	 */
	function save_booking_details($domain_origin, $status, $app_reference, $booking_source, $booking_id, $booking_reference, $confirmation_reference,
	$hotel_name, $star_rating, $hotel_code, $phone_number, $alternate_number, $email,
	$hotel_check_in, $hotel_check_out, $payment_mode,	$attributes, $created_by_id, $transaction_currency, $currency_conversion_rate)
	{
		$data['domain_origin'] = $domain_origin;
		$data['status'] = $status;
		$data['app_reference'] = $app_reference;
		$data['booking_source'] = $booking_source;
		$data['booking_id'] = $booking_id;
		$data['booking_reference'] = $booking_reference;
		$data['confirmation_reference'] = $confirmation_reference;
		$data['hotel_name'] = $hotel_name;
		$data['star_rating'] = $star_rating;
		$data['hotel_code'] = $hotel_code;
		$data['phone_number'] = $phone_number;
		$data['alternate_number'] = $alternate_number;
		$data['email'] = $email;
		$data['hotel_check_in'] = $hotel_check_in;
		$data['hotel_check_out'] = $hotel_check_out;
		$data['payment_mode'] = $payment_mode;
		$data['attributes'] = $attributes;
		$data['created_by_id'] = $created_by_id;
		$data['created_datetime'] = date('Y-m-d H:i:s');
		
		$data['currency'] = $transaction_currency;
		$data['currency_conversion_rate'] = $currency_conversion_rate;
		
		$status = $this->custom_db->insert_record('hotel_booking_details', $data);
		return $status;
	}

	/**
	 *
	 * @param string $app_reference
	 * @param string $location
	 * @param date	 $check_in
	 * @param date	 $check_out
	 * @param string $room_type_name
	 * @param string $bed_type_code
	 * @param string $status
	 * @param string $smoking_preference
	 * @param string $attributes
	 */
	function save_booking_itinerary_details($app_reference, $location, $check_in, $check_out, $room_type_name, $bed_type_code,
	$status, $smoking_preference, $total_fare, $admin_markup, $agent_markup, $currency, $attributes,
	$RoomPrice, $Tax, $ExtraGuestCharge, $ChildCharge, $OtherCharges,
	$Discount, $ServiceTax, $AgentCommission, $AgentMarkUp, $TDS)
	{
		$data['app_reference'] = $app_reference;
		$data['location'] = $location;
		$data['check_in'] = $check_in;
		$data['check_out'] = $check_out;
		$data['room_type_name'] = $room_type_name;
		$data['bed_type_code'] = $bed_type_code;
		$data['status'] = $status;
		$data['smoking_preference'] = $smoking_preference;
		$data['total_fare'] = $total_fare;
		$data['admin_markup'] = $admin_markup;
		$data['agent_markup'] = $agent_markup;
		$data['currency'] = $currency;
		$data['attributes'] = $attributes;

		$data['RoomPrice'] = floatval($RoomPrice);
		$data['Tax'] = floatval($Tax);
		$data['ExtraGuestCharge'] = floatval($ExtraGuestCharge);
		$data['ChildCharge'] = floatval($ChildCharge);
		$data['OtherCharges'] = floatval($OtherCharges);
		$data['Discount'] = floatval($Discount);
		$data['ServiceTax'] = floatval($ServiceTax);
		$data['AgentCommission'] = floatval($AgentCommission);
		$data['AgentMarkUp'] = floatval($AgentMarkUp);
		$data['TDS'] = floatval($TDS);
		
		$status = $this->custom_db->insert_record('hotel_booking_itinerary_details', $data);
		return $status;
	}

	/**
	 *
	 * @param $app_reference
	 * @param $title
	 * @param $first_name
	 * @param $middle_name
	 * @param $last_name
	 * @param $phone
	 * @param $email
	 * @param $pax_type
	 * @param $date_of_birth
	 * @param $passenger_nationality
	 * @param $passport_number
	 * @param $passport_issuing_country
	 * @param $passport_expiry_date
	 * @param $status
	 * @param $attributes
	 */
	function save_booking_pax_details($app_reference, $title, $first_name, $middle_name, $last_name, $phone, $email, $pax_type, $date_of_birth,
	$passenger_nationality, $passport_number, $passport_issuing_country, $passport_expiry_date, $status, $attributes)
	{
		//echo $date_of_birth;
		$data['app_reference'] = $app_reference;
		$data['title'] = $title;
		$data['first_name'] = $first_name;
		$data['middle_name'] = (empty($middle_name) == true ?  $last_name: $middle_name);
		$data['last_name'] = $last_name;
		$data['phone'] = $phone;
		$data['email'] = $email;
		$data['pax_type'] = $pax_type;
		$data['date_of_birth'] = $date_of_birth;
		$data['passenger_nationality'] = $passenger_nationality;
		$data['passport_number'] = $passport_number;
		$data['passport_issuing_country'] = $passport_issuing_country;
		$data['passport_expiry_date'] = $passport_expiry_date;
		$data['status'] = $status;
		$data['attributes'] = $attributes;
		
		$status = $this->custom_db->insert_record('hotel_booking_pax_details', $data);
		return $status;
	}
	/**
	 *
	 */
	function get_static_response($token_id)
	{
		$static_response = $this->custom_db->single_table_records('test', '*', array('origin' => intval($token_id)));
		return json_decode($static_response['data'][0]['test'], true);
	}

	/**
	 * SAve search data for future use - Analytics
	 * @param array $params
	 */
	function save_search_data($search_data, $type)
	{
		$data['domain_origin'] = get_domain_auth_id();
		$data['search_type'] = $type;
		$data['created_by_id'] = intval(@$this->entity_user_id);
		$data['created_datetime'] = date('Y-m-d H:i:s');

		$temp_location = explode('(', $search_data['city']);
		$data['city'] = trim($temp_location[0]);
		if (isset($temp_location[1]) == true) {
			$data['country'] = trim($temp_location[1], '() ');
		} else {
			$data['country'] = '';
		}
		$data['check_in'] = date('Y-m-d', strtotime($search_data['hotel_checkin']));
		$data['nights'] = abs(get_date_difference($search_data['hotel_checkin'], $search_data['hotel_checkout']));
		$data['rooms'] = $search_data['rooms'];
		$data['total_pax'] = array_sum($search_data['adult']) + array_sum($search_data['child']);
		$this->custom_db->insert_record('search_hotel_history', $data);
	}
	/**
	 * Jaganath
	 * Update Cancellation details and Status
	 * @param $AppReference
	 * @param $cancellation_details
	 */
	public function update_cancellation_details($AppReference, $cancellation_details)
	{
		$AppReference = trim($AppReference);
		$booking_status = 'BOOKING_CANCELLED';
		//1. Add Cancellation details
		$this->update_cancellation_refund_detailsupdate_cancellation_refund_details($AppReference, $cancellation_details);
		//2. Update Master Booking Status
		$this->custom_db->update_record('hotel_booking_details', array('status' => $booking_status), array('app_reference' => $AppReference));//later
		//3.Update Itinerary Status
		$this->custom_db->update_record('hotel_booking_itinerary_details', array('status' => $booking_status), array('app_reference' => $AppReference));//later
	}
	/**
	 * Add Cancellation details
	 * @param unknown_type $AppReference
	 * @param unknown_type $cancellation_details
	 */
	private function update_cancellation_refund_details($AppReference, $cancellation_details)
	{
		$hotel_cancellation_details = array();
		$hotel_cancellation_details['app_reference'] = 				$AppReference;
		$hotel_cancellation_details['ChangeRequestId'] = 			$cancellation_details['ChangeRequestId'];
		$hotel_cancellation_details['ChangeRequestStatus'] = 		$cancellation_details['ChangeRequestStatus'];
		$hotel_cancellation_details['status_description'] = 		$cancellation_details['StatusDescription'];
		$hotel_cancellation_details['API_RefundedAmount'] = 		@$cancellation_details['RefundedAmount'];
		$hotel_cancellation_details['API_CancellationCharge'] = 	@$cancellation_details['CancellationCharge'];
		if($cancellation_details['ChangeRequestStatus'] == 3){
			$hotel_cancellation_details['cancellation_processed_on'] =	date('Y-m-d H:i:s');
		}
		debug($hotel_cancellation_details);die;
		$cancel_details_exists = $this->custom_db->single_table_records('hotel_cancellation_details', '*', array('app_reference' => $AppReference));
		if($cancel_details_exists['status'] == true) {
			//Update the Data
			unset($hotel_cancellation_details['app_reference']);
			$this->custom_db->update_record('hotel_cancellation_details', $hotel_cancellation_details, array('app_reference' => $AppReference));
		} else {
			//Insert Data
			$hotel_cancellation_details['created_by_id'] = 				(int)@$this->entity_user_id;
			$hotel_cancellation_details['created_datetime'] = 			date('Y-m-d H:i:s');
			$data['cancellation_requested_on'] = date('Y-m-d H:i:s');
			$this->custom_db->insert_record('hotel_cancellation_details',$hotel_cancellation_details);
		}
	}
	/**
	*Image masking
	*/
	function setImgDownload($imagePath){
		$image = imagecreatefromjpeg($imagePath);
	    header('Content-Type: image/jpeg');
	    imagejpeg($image);
	}
    function add_hotel_images($sid,$HotelPicture,$HotelCode) {
         
        $image_url= $this->custom_db->single_table_records('hotel_image_url','image_url',array('hotel_code'=>$HotelCode));            
     
        if($image_url['status']==0) {
            foreach($HotelPicture as $key=>$value) {
			$data['image_url'] = $value;
			$data['ResultIndex'] = $key;
	                $data['hotel_code'] = $HotelCode;
			$this->custom_db->insert_record('hotel_image_url', $data);
            }
        }
    }

     function getCancellationDetails($hotel_id){
     	$this->db->select('*');
     	$this->db->from('hotel_cancellation');
     	$this->db->where('hotel_details_id',$hotel_id);
     	$query = $this->db->get();
     	if($query->num_rows > 0){
     		return $query->result();
     	}else{
     		return false;
     	}
     }//Hotel-CRS

   	function get_crs_search_data($datetime1, $datetime2,$stay_days,$s_max_adult,$s_max_child,$checkin_date,$checkout_date,$room_count,$city_id,$nationality_fk="")
     {
             //added condition on 16-4-2020 for widget_search
                //debug($nationality_fk);exit;
                if($nationality_fk!="")
                {
            
                  //added on 31-3-2020
                  $q2 = "
                SELECT 
                    hd.hotel_details_id,hd.country_id,hd.city_details_id,hd.hotel_type_id,hd.hotel_address,
                    hd.hotel_name,hd.hotel_code,hd.hotel_price,currency_type,hd.min_stay_day,hd.hotel_amenities,hd.latitude,hd.longitude,
                    hd.max_stay_day,hd.star_rating,hd.hotel_info,hd.thumb_image,
                    hd.hotel_images,hd.hotel_image_url,
                    hd.child_group_a,hd.child_group_b,hd.child_group_c,hd.child_group_d,hd.child_group_e,
                    ml.value as markup_value,ml.value_type as markup_value_type,ml.markup_currency,ml.origin as markup_origin, ml.type as markup_type,
                    ml.reference_id as reference_id

                FROM 
                    hotel_details hd 
                    LEFT OUTER JOIN markup_list ml on hd.hotel_details_id=ml.reference_id 
                    AND ml.type='specific'
                    AND ml.module_type='b2c_hotel'
                WHERE 
                    hd.city_details_id like '%".$city_id."%'
                AND hd.hotel_type_id !='".VILLA."' 
                AND hd.status='ACTIVE' 
                AND hd.contract_expire_date >= CURDATE()
                AND hd.hotel_details_id IN (
                    SELECT  
                        DISTINCT hotel_details_id 
                    FROM
                        `seasons_details` hsd 
                    WHERE 
                        hotel_details_id = hd.hotel_details_id 
                   
                     AND 
                        '".$checkin_date."' >= hsd.seasons_from_date AND '".$checkout_date."' <= hsd.seasons_to_date
                     AND '".$checkin_date."' >= '".Date('Y-m-d', strtotime("+1 days"))."'
                     AND 
                        ".$stay_days." >= hsd.minimum_stays AND hsd.status='ACTIVE'
                     AND hotel_room_type_id IN  
                        (
                            SELECT 
                                DISTINCT hrci.hotel_room_type_id 
                            FROM 
                                hotel_room_type ht 
                            LEFT JOIN
                                hotel_season_room_count_info hrci 
                                ON (
                                        (
                                            ht.hotel_room_type_id = hrci.hotel_room_type_id
                                        )
                                    AND 
                                        ht.hotel_details_id = hrci.hotel_details_id
                                   )
                            LEFT JOIN
                                 hotel_room_rate_info rate
                                 ON (
                                        rate.seasons_details_id = hrci.hotel_season_id
                                        AND 
                                        rate.hotel_details_id = hrci.hotel_details_id
                                        AND 
                                        rate.hotel_room_type_id = hrci.hotel_room_type_id
                                        
                                    )
                     
                            WHERE 
                                ht.status='ACTIVE'
                            AND
                                hrci.status='ACTIVE' 
                            AND 
                                rate.hotel_room_rate_info_id IS NOT NULL    
                            AND
                                ht.hotel_details_id = hd.hotel_details_id
                            AND 
                                hrci.hotel_room_type_id IS NOT NULL
                            AND 
                                rate.seasons_details_id IS NOT NULL
                            AND 
                                (
                                    hrci.no_of_room_available > hrci.no_of_room_booked AND  (hrci.no_of_room_available - hrci.no_of_room_booked >= ".$room_count.")
                                )
                            
                            AND 
                                (  rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<=ht.adult AND ".$s_max_child."<=ht.child)
                                OR
                                (  rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<= ht.max_pax AND ".$s_max_child."<=ht.child AND ht.extra_bed ='Available')
                                OR
                                (  rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<= ht.max_pax AND ".$s_max_child."<=ht.child + 1 AND ht.extra_bed ='Available')
                                OR
                                (   rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<= ht.adult AND ".$s_max_child."<=ht.child + 1 AND ht.extra_bed ='Available')
                        )
                       
                )
                ORDER BY hd.creation_date DESC";
                $overall_data=$this->db->query($q2);
                 /*	debug($q2);
                 	debug($overall_data->num_rows);exit();*/
                 
                 	return $this->db->query($q2);
                }
                else{
                   // debug("else");exit;
                   //debug($city_id);exit;
                       $nationality="default";
                 	      $q3 = "
                SELECT 
                    hd.hotel_details_id,hd.country_id,hd.city_details_id,hd.hotel_type_id,hd.hotel_address,
                    hd.hotel_name,hd.hotel_code,hd.hotel_price,currency_type,hd.min_stay_day,hd.hotel_amenities,hd.latitude,hd.longitude,
                    hd.max_stay_day,hd.star_rating,hd.hotel_info,hd.thumb_image,
                    hd.hotel_images,hd.hotel_image_url,
                    hd.child_group_a,hd.child_group_b,hd.child_group_c,hd.child_group_d,hd.child_group_e,
                    ml.value as markup_value,ml.value_type as markup_value_type,ml.markup_currency,ml.origin as markup_origin, ml.type as markup_type,
                    ml.reference_id as reference_id

                FROM 
                    hotel_details hd 
                    LEFT OUTER JOIN markup_list ml on hd.hotel_details_id=ml.reference_id 
                    AND ml.type='specific'
                    AND ml.module_type='b2c_hotel'
                WHERE 
                    hd.city_details_id like '%".$city_id."%'
                AND hd.hotel_type_id !='".VILLA."' 
                AND hd.status='ACTIVE' 
                AND hd.contract_expire_date >= CURDATE()
                AND hd.hotel_details_id IN (
                    SELECT  
                        DISTINCT hotel_details_id 
                    FROM
                        `seasons_details` hsd 
                    WHERE 
                        hotel_details_id = hd.hotel_details_id 
                   
                     AND 
                        '".$checkin_date."' >= hsd.seasons_from_date AND '".$checkout_date."' <= hsd.seasons_to_date
                     AND '".$checkin_date."' >= '".Date('Y-m-d', strtotime("+1 days"))."'
                     AND 
                        ".$stay_days." >= hsd.minimum_stays AND hsd.status='ACTIVE'
                     AND hotel_room_type_id IN  
                        (
                            SELECT 
                                DISTINCT hrci.hotel_room_type_id 
                            FROM 
                                hotel_room_type ht 
                            LEFT JOIN
                                hotel_season_room_count_info hrci 
                                ON (
                                        (
                                            ht.hotel_room_type_id = hrci.hotel_room_type_id
                                        )
                                    AND 
                                        ht.hotel_details_id = hrci.hotel_details_id
                                   )
                            LEFT JOIN
                                 hotel_room_rate_info rate
                                 ON (
                                        rate.seasons_details_id = hrci.hotel_season_id
                                        AND 
                                        rate.hotel_details_id = hrci.hotel_details_id
                                        AND 
                                        rate.hotel_room_type_id = hrci.hotel_room_type_id
                                        AND  rate.hotel_room_rate_info_id IN(
                                               SELECT hrr.hotel_rome_rate_info_id 
                           				 FROM 
                                		hotel_room_rate hrr 
                                            WHERE hrr.hotel_rome_rate_info_id=rate.hotel_room_rate_info_id
                                            
                                           AND hrr.default_nationality='default'

                                        )  
                                    )
                     
                            WHERE 
                                ht.status='ACTIVE'
                            AND
                                hrci.status='ACTIVE' 
                            AND 
                                rate.hotel_room_rate_info_id IS NOT NULL    
                            AND
                                ht.hotel_details_id = hd.hotel_details_id
                            AND 
                                hrci.hotel_room_type_id IS NOT NULL
                            AND 
                                rate.seasons_details_id IS NOT NULL
                            AND 
                                (
                                    hrci.no_of_room_available > hrci.no_of_room_booked AND  (hrci.no_of_room_available - hrci.no_of_room_booked >= ".$room_count.")
                                )
                            
                            AND 
                                (  rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<=ht.adult AND ".$s_max_child."<=ht.child)
                                OR
                                (  rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<= ht.max_pax AND ".$s_max_child."<=ht.child AND ht.extra_bed ='Available')
                                OR
                                (  rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<= ht.max_pax AND ".$s_max_child."<=ht.child + 1 AND ht.extra_bed ='Available')
                                OR
                                (   rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<= ht.adult AND ".$s_max_child."<=ht.child + 1 AND ht.extra_bed ='Available')
                        )
                       
                )
                ORDER BY hd.creation_date DESC";
             //debug($q3);exit;
               
                
                 return $this->db->query($q3);
                }
       
	}

function get_search_dataold($stay_days,$s_max_adult,$s_max_child,$checkin_date,$checkout_date,$room_count,$city_id,$nationality_fk=0)
     {
             //added condition on 16-4-2020 for widget_search
                debug($nationality_fk);exit;
                if($nationality_fk!="")
                {
            
                  //added on 31-3-2020
                  $q2 = "
                SELECT 
                    hd.hotel_details_id,hd.country_id,hd.city_details_id,hd.hotel_type_id,hd.hotel_address,
                    hd.hotel_name,hd.hotel_code,hd.hotel_price,currency_type,hd.min_stay_day,hd.hotel_amenities,hd.latitude,hd.longitude,
                    hd.max_stay_day,hd.star_rating,hd.hotel_info,hd.thumb_image,
                    hd.hotel_images,hd.hotel_image_url,
                    hd.child_group_a,hd.child_group_b,hd.child_group_c,hd.child_group_d,hd.child_group_e,
                    ml.value as markup_value,ml.value_type as markup_value_type,ml.markup_currency,ml.origin as markup_origin, ml.type as markup_type,
                    ml.reference_id as reference_id

                FROM 
                    hotel_details hd 
                    LEFT OUTER JOIN markup_list ml on hd.hotel_details_id=ml.reference_id 
                    AND ml.type='specific'
                    AND ml.module_type='b2c_hotel'
                WHERE 
                    hd.city_details_id like '%".$city_id."%'
                AND hd.hotel_type_id !='".VILLA."' 
                AND hd.status='ACTIVE' 
                AND hd.contract_expire_date >= CURDATE()
                AND hd.hotel_details_id IN (
                    SELECT  
                        DISTINCT hotel_details_id 
                    FROM
                        `seasons_details` hsd 
                    WHERE 
                        hotel_details_id = hd.hotel_details_id 
                   
                     AND 
                        '".$checkin_date."' >= hsd.seasons_from_date AND '".$checkout_date."' <= hsd.seasons_to_date
                     AND '".$checkin_date."' >= '".Date('Y-m-d', strtotime("+1 days"))."'
                     AND 
                        ".$stay_days." >= hsd.minimum_stays AND hsd.status='ACTIVE'
                     AND hotel_room_type_id IN  
                        (
                            SELECT 
                                DISTINCT hrci.hotel_room_type_id 
                            FROM 
                                hotel_room_type ht 
                            LEFT JOIN
                                hotel_season_room_count_info hrci 
                                ON (
                                        (
                                            ht.hotel_room_type_id = hrci.hotel_room_type_id
                                        )
                                    AND 
                                        ht.hotel_details_id = hrci.hotel_details_id
                                   )
                            LEFT JOIN
                                 hotel_room_rate_info rate
                                 ON (
                                        rate.seasons_details_id = hrci.hotel_season_id
                                        AND 
                                        rate.hotel_details_id = hrci.hotel_details_id
                                        AND 
                                        rate.hotel_room_type_id = hrci.hotel_room_type_id
                                        
                                    )
                     
                            WHERE 
                                ht.status='ACTIVE'
                            AND
                                hrci.status='ACTIVE' 
                            AND 
                                rate.hotel_room_rate_info_id IS NOT NULL    
                            AND
                                ht.hotel_details_id = hd.hotel_details_id
                            AND 
                                hrci.hotel_room_type_id IS NOT NULL
                            AND 
                                rate.seasons_details_id IS NOT NULL
                            AND 
                                (
                                    hrci.no_of_room_available > hrci.no_of_room_booked AND  (hrci.no_of_room_available - hrci.no_of_room_booked >= ".$room_count.")
                                )
                            
                            AND 
                                (  rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<=ht.adult AND ".$s_max_child."<=ht.child)
                                OR
                                (  rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<= ht.max_pax AND ".$s_max_child."<=ht.child AND ht.extra_bed ='Available')
                                OR
                                (  rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<= ht.max_pax AND ".$s_max_child."<=ht.child + 1 AND ht.extra_bed ='Available')
                                OR
                                (   rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<= ht.adult AND ".$s_max_child."<=ht.child + 1 AND ht.extra_bed ='Available')
                        )
                       
                )
                ORDER BY hd.creation_date DESC";
                $overall_data=$this->db->query($q2);
                 /*	debug($q2);
                 	debug($overall_data->num_rows);exit();*/
                 
                 	return $this->db->query($q2);
                }
                else{
                   // debug("else");exit;
                   //debug($city_id);exit;
                       $nationality="default";
                 	      $q3 = "
                SELECT 
                    hd.hotel_details_id,hd.country_id,hd.city_details_id,hd.hotel_type_id,hd.hotel_address,
                    hd.hotel_name,hd.hotel_code,hd.hotel_price,currency_type,hd.min_stay_day,hd.hotel_amenities,hd.latitude,hd.longitude,
                    hd.max_stay_day,hd.star_rating,hd.hotel_info,hd.thumb_image,
                    hd.hotel_images,hd.hotel_image_url,
                    hd.child_group_a,hd.child_group_b,hd.child_group_c,hd.child_group_d,hd.child_group_e,
                    ml.value as markup_value,ml.value_type as markup_value_type,ml.markup_currency,ml.origin as markup_origin, ml.type as markup_type,
                    ml.reference_id as reference_id

                FROM 
                    hotel_details hd 
                    LEFT OUTER JOIN markup_list ml on hd.hotel_details_id=ml.reference_id 
                    AND ml.type='specific'
                    AND ml.module_type='b2c_hotel'
                WHERE 
                    hd.city_details_id like '%".$city_id."%'
                AND hd.hotel_type_id !='".VILLA."' 
                AND hd.status='ACTIVE' 
                AND hd.contract_expire_date >= CURDATE()
                AND hd.hotel_details_id IN (
                    SELECT  
                        DISTINCT hotel_details_id 
                    FROM
                        `seasons_details` hsd 
                    WHERE 
                        hotel_details_id = hd.hotel_details_id 
                   
                     AND 
                        '".$checkin_date."' >= hsd.seasons_from_date AND '".$checkout_date."' <= hsd.seasons_to_date
                     AND '".$checkin_date."' >= '".Date('Y-m-d', strtotime("+1 days"))."'
                     AND 
                        ".$stay_days." >= hsd.minimum_stays AND hsd.status='ACTIVE'
                     AND hotel_room_type_id IN  
                        (
                            SELECT 
                                DISTINCT hrci.hotel_room_type_id 
                            FROM 
                                hotel_room_type ht 
                            LEFT JOIN
                                hotel_season_room_count_info hrci 
                                ON (
                                        (
                                            ht.hotel_room_type_id = hrci.hotel_room_type_id
                                        )
                                    AND 
                                        ht.hotel_details_id = hrci.hotel_details_id
                                   )
                            LEFT JOIN
                                 hotel_room_rate_info rate
                                 ON (
                                        rate.seasons_details_id = hrci.hotel_season_id
                                        AND 
                                        rate.hotel_details_id = hrci.hotel_details_id
                                        AND 
                                        rate.hotel_room_type_id = hrci.hotel_room_type_id
                                        AND  rate.hotel_room_rate_info_id IN(
                                               SELECT hrr.hotel_rome_rate_info_id 
                           				 FROM 
                                		hotel_room_rate hrr 
                                            WHERE hrr.hotel_rome_rate_info_id=rate.hotel_room_rate_info_id
                                            
                                           AND hrr.default_nationality='default'

                                        )  
                                    )
                     
                            WHERE 
                                ht.status='ACTIVE'
                            AND
                                hrci.status='ACTIVE' 
                            AND 
                                rate.hotel_room_rate_info_id IS NOT NULL    
                            AND
                                ht.hotel_details_id = hd.hotel_details_id
                            AND 
                                hrci.hotel_room_type_id IS NOT NULL
                            AND 
                                rate.seasons_details_id IS NOT NULL
                            AND 
                                (
                                    hrci.no_of_room_available > hrci.no_of_room_booked AND  (hrci.no_of_room_available - hrci.no_of_room_booked >= ".$room_count.")
                                )
                            
                            AND 
                                (  rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<=ht.adult AND ".$s_max_child."<=ht.child)
                                OR
                                (  rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<= ht.max_pax AND ".$s_max_child."<=ht.child AND ht.extra_bed ='Available')
                                OR
                                (  rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<= ht.max_pax AND ".$s_max_child."<=ht.child + 1 AND ht.extra_bed ='Available')
                                OR
                                (   rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<= ht.adult AND ".$s_max_child."<=ht.child + 1 AND ht.extra_bed ='Available')
                        )
                       
                )
                ORDER BY hd.creation_date DESC";
               // debug($q3);exit;
               
                
                 return $this->db->query($q3);
                }
       
	}

//$nationality_fk added on 31-3-2020
     function get_crs_search_data_olddd($datetime1, $datetime2,$stay_days,$s_max_adult,$s_max_child,$checkin_date,$checkout_date,$room_count,$city_id,$nationality_fk="")
     {
     	//debug($nationality_fk);exit;
     	// error_reporting(1);
     //	debug($city_id);exit;

		// debug(CURDATE());exit();

		// $stay_days=2;
		 /*$q2 = "
                SELECT 
                    hd.hotel_details_id,hd.country_id,hd.city_details_id,hd.hotel_type_id,hd.hotel_address,
                    hd.hotel_name,hd.hotel_code,hd.hotel_price,currency_type,hd.min_stay_day,hd.hotel_amenities,hd.latitude,hd.longitude,
                    hd.max_stay_day,hd.star_rating,hd.hotel_info,hd.thumb_image,
                    hd.hotel_images,hd.hotel_image_url,
                    hd.child_group_a,hd.child_group_b,hd.child_group_c,hd.child_group_d,hd.child_group_e

                FROM 
                    hotel_details hd 
                WHERE 
                    hd.city_details_id like '%".$city_id."%'
                AND hd.hotel_type_id !='".VILLA."' 
                AND hd.status='ACTIVE' 
                AND hd.contract_expire_date >= CURDATE()
                AND hd.hotel_details_id IN (
                    SELECT  
                        DISTINCT hotel_details_id 
                    FROM
                        `seasons_details` hsd 
                    WHERE 
                        hotel_details_id = hd.hotel_details_id 
                   
                     AND 
                        '".$checkin_date."' >= hsd.seasons_from_date AND '".$checkout_date."' <= hsd.seasons_to_date
                     AND '".$checkin_date."' >= '".Date('Y-m-d', strtotime("+1 days"))."'
                     AND 
                        ".$stay_days." >= hsd.minimum_stays AND hsd.status='ACTIVE'
                     AND hotel_room_type_id IN  
                        (
                            SELECT 
                                DISTINCT hrci.hotel_room_type_id 
                            FROM 
                                hotel_room_type ht 
                            LEFT JOIN
                                hotel_season_room_count_info hrci 
                                ON (
                                        (
                                            ht.hotel_room_type_id = hrci.hotel_room_type_id
                                        )
                                    AND 
                                        ht.hotel_details_id = hrci.hotel_details_id
                                   )
                            LEFT JOIN
                                 hotel_room_rate_info rate
                                 ON (
                                        rate.seasons_details_id = hrci.hotel_season_id
                                        AND 
                                        rate.hotel_details_id = hrci.hotel_details_id
                                        AND 
                                        rate.hotel_room_type_id = hrci.hotel_room_type_id
                                    )
                            WHERE 
                                ht.status='ACTIVE'
                            AND
                                hrci.status='ACTIVE' 
                            AND 
                                rate.hotel_room_rate_info_id IS NOT NULL    
                            AND
                                ht.hotel_details_id = hd.hotel_details_id
                            AND 
                                hrci.hotel_room_type_id IS NOT NULL
                            AND 
                                rate.seasons_details_id IS NOT NULL
                            AND 
                                (
                                    hrci.no_of_room_available > hrci.no_of_room_booked AND  (hrci.no_of_room_available - hrci.no_of_room_booked >= ".$room_count.")
                                )
                            
                            AND 
                                (  rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<=ht.adult AND ".$s_max_child."<=ht.child)
                                OR
                                (  rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<= ht.max_pax AND ".$s_max_child."<=ht.child AND ht.extra_bed ='Available')
                                OR
                                (  rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<= ht.max_pax AND ".$s_max_child."<=ht.child + 1 AND ht.extra_bed ='Available')
                                OR
                                (   rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<= ht.adult AND ".$s_max_child."<=ht.child + 1 AND ht.extra_bed ='Available')
                        )
                )
                ORDER BY hd.creation_date DESC";
                */


//added on 24-3-2020 for hotel wise markup

                /* $q2 = "
                SELECT 
                    hd.hotel_details_id,hd.country_id,hd.city_details_id,hd.hotel_type_id,hd.hotel_address,
                    hd.hotel_name,hd.hotel_code,hd.hotel_price,currency_type,hd.min_stay_day,hd.hotel_amenities,hd.latitude,hd.longitude,
                    hd.max_stay_day,hd.star_rating,hd.hotel_info,hd.thumb_image,
                    hd.hotel_images,hd.hotel_image_url,
                    hd.child_group_a,hd.child_group_b,hd.child_group_c,hd.child_group_d,hd.child_group_e,
                    ml.value as markup_value,ml.value_type as markup_value_type,ml.markup_currency,ml.origin as markup_origin, ml.type as markup_type,
                    ml.reference_id as reference_id

                FROM 
                    hotel_details hd 
                    LEFT OUTER JOIN markup_list ml on hd.hotel_details_id=ml.reference_id 
                    AND ml.type='specific'
                    AND ml.module_type='b2c_hotel'
                WHERE 
                    hd.city_details_id like '%".$city_id."%'
                AND hd.hotel_type_id !='".VILLA."' 
                AND hd.status='ACTIVE' 
                AND hd.contract_expire_date >= CURDATE()
                AND hd.hotel_details_id IN (
                    SELECT  
                        DISTINCT hotel_details_id 
                    FROM
                        `seasons_details` hsd 
                    WHERE 
                        hotel_details_id = hd.hotel_details_id 
                   
                     AND 
                        '".$checkin_date."' >= hsd.seasons_from_date AND '".$checkout_date."' <= hsd.seasons_to_date
                     AND '".$checkin_date."' >= '".Date('Y-m-d', strtotime("+1 days"))."'
                     AND 
                        ".$stay_days." >= hsd.minimum_stays AND hsd.status='ACTIVE'
                     AND hotel_room_type_id IN  
                        (
                            SELECT 
                                DISTINCT hrci.hotel_room_type_id 
                            FROM 
                                hotel_room_type ht 
                            LEFT JOIN
                                hotel_season_room_count_info hrci 
                                ON (
                                        (
                                            ht.hotel_room_type_id = hrci.hotel_room_type_id
                                        )
                                    AND 
                                        ht.hotel_details_id = hrci.hotel_details_id
                                   )
                            LEFT JOIN
                                 hotel_room_rate_info rate
                                 ON (
                                        rate.seasons_details_id = hrci.hotel_season_id
                                        AND 
                                        rate.hotel_details_id = hrci.hotel_details_id
                                        AND 
                                        rate.hotel_room_type_id = hrci.hotel_room_type_id
                                    )
                            WHERE 
                                ht.status='ACTIVE'
                            AND
                                hrci.status='ACTIVE' 
                            AND 
                                rate.hotel_room_rate_info_id IS NOT NULL    
                            AND
                                ht.hotel_details_id = hd.hotel_details_id
                            AND 
                                hrci.hotel_room_type_id IS NOT NULL
                            AND 
                                rate.seasons_details_id IS NOT NULL
                            AND 
                                (
                                    hrci.no_of_room_available > hrci.no_of_room_booked AND  (hrci.no_of_room_available - hrci.no_of_room_booked >= ".$room_count.")
                                )
                            
                            AND 
                                (  rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<=ht.adult AND ".$s_max_child."<=ht.child)
                                OR
                                (  rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<= ht.max_pax AND ".$s_max_child."<=ht.child AND ht.extra_bed ='Available')
                                OR
                                (  rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<= ht.max_pax AND ".$s_max_child."<=ht.child + 1 AND ht.extra_bed ='Available')
                                OR
                                (   rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<= ht.adult AND ".$s_max_child."<=ht.child + 1 AND ht.extra_bed ='Available')
                        )
                       
                )
                ORDER BY hd.creation_date DESC";*/
                
                
                //added condition on 16-4-2020 for widget_search
                //debug($nationality_fk);exit;
                if($nationality_fk!="")
                {
                 //  debug("if");exit;
                    
             
                
                
                  //added on 31-3-2020
                  $q2 = "
                SELECT 
                    hd.hotel_details_id,hd.country_id,hd.city_details_id,hd.hotel_type_id,hd.hotel_address,
                    hd.hotel_name,hd.hotel_code,hd.hotel_price,currency_type,hd.min_stay_day,hd.hotel_amenities,hd.latitude,hd.longitude,
                    hd.max_stay_day,hd.star_rating,hd.hotel_info,hd.thumb_image,
                    hd.hotel_images,hd.hotel_image_url,
                    hd.child_group_a,hd.child_group_b,hd.child_group_c,hd.child_group_d,hd.child_group_e,
                    ml.value as markup_value,ml.value_type as markup_value_type,ml.markup_currency,ml.origin as markup_origin, ml.type as markup_type,
                    ml.reference_id as reference_id

                FROM 
                    hotel_details hd 
                    LEFT OUTER JOIN markup_list ml on hd.hotel_details_id=ml.reference_id 
                    AND ml.type='specific'
                    AND ml.module_type='b2c_hotel'
                WHERE 
                    hd.city_details_id like '%".$city_id."%'
                AND hd.hotel_type_id !='".VILLA."' 
                AND hd.status='ACTIVE' 
                AND hd.contract_expire_date >= CURDATE()
                AND hd.hotel_details_id IN (
                    SELECT  
                        DISTINCT hotel_details_id 
                    FROM
                        `seasons_details` hsd 
                    WHERE 
                        hotel_details_id = hd.hotel_details_id 
                   
                     AND 
                        '".$checkin_date."' >= hsd.seasons_from_date AND '".$checkout_date."' <= hsd.seasons_to_date
                     AND '".$checkin_date."' >= '".Date('Y-m-d', strtotime("+1 days"))."'
                     AND 
                        ".$stay_days." >= hsd.minimum_stays AND hsd.status='ACTIVE'
                     AND hotel_room_type_id IN  
                        (
                            SELECT 
                                DISTINCT hrci.hotel_room_type_id 
                            FROM 
                                hotel_room_type ht 
                            LEFT JOIN
                                hotel_season_room_count_info hrci 
                                ON (
                                        (
                                            ht.hotel_room_type_id = hrci.hotel_room_type_id
                                        )
                                    AND 
                                        ht.hotel_details_id = hrci.hotel_details_id
                                   )
                            LEFT JOIN
                                 hotel_room_rate_info rate
                                 ON (
                                        rate.seasons_details_id = hrci.hotel_season_id
                                        AND 
                                        rate.hotel_details_id = hrci.hotel_details_id
                                        AND 
                                        rate.hotel_room_type_id = hrci.hotel_room_type_id
                                        AND  rate.hotel_room_rate_info_id IN(
                                               SELECT hrr.hotel_rome_rate_info_id 
                           				 FROM 
                                		hotel_room_rate hrr 
                                            WHERE hrr.hotel_rome_rate_info_id=rate.hotel_room_rate_info_id
                                            AND hrr.country_list_nationality_id=".$nationality_fk."




                                	 
                                        )  
                                    )
                     
                            WHERE 
                                ht.status='ACTIVE'
                            AND
                                hrci.status='ACTIVE' 
                            AND 
                                rate.hotel_room_rate_info_id IS NOT NULL    
                            AND
                                ht.hotel_details_id = hd.hotel_details_id
                            AND 
                                hrci.hotel_room_type_id IS NOT NULL
                            AND 
                                rate.seasons_details_id IS NOT NULL
                            AND 
                                (
                                    hrci.no_of_room_available > hrci.no_of_room_booked AND  (hrci.no_of_room_available - hrci.no_of_room_booked >= ".$room_count.")
                                )
                            
                            AND 
                                (  rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<=ht.adult AND ".$s_max_child."<=ht.child)
                                OR
                                (  rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<= ht.max_pax AND ".$s_max_child."<=ht.child AND ht.extra_bed ='Available')
                                OR
                                (  rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<= ht.max_pax AND ".$s_max_child."<=ht.child + 1 AND ht.extra_bed ='Available')
                                OR
                                (   rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<= ht.adult AND ".$s_max_child."<=ht.child + 1 AND ht.extra_bed ='Available')
                        )
                       
                )
                ORDER BY hd.creation_date DESC";
                $overall_data=$this->db->query($q2);
                 /*	debug($q2);
                 	debug($overall_data->num_rows);exit();*/
                 	if($overall_data->num_rows==0)
                 	{
                 	    //debug("sdfs");exit;
                 	    //$nationality_fk=0;
                 	    $nationality="default";
                 	      $q3 = "
                SELECT 
                    hd.hotel_details_id,hd.country_id,hd.city_details_id,hd.hotel_type_id,hd.hotel_address,
                    hd.hotel_name,hd.hotel_code,hd.hotel_price,currency_type,hd.min_stay_day,hd.hotel_amenities,hd.latitude,hd.longitude,
                    hd.max_stay_day,hd.star_rating,hd.hotel_info,hd.thumb_image,
                    hd.hotel_images,hd.hotel_image_url,
                    hd.child_group_a,hd.child_group_b,hd.child_group_c,hd.child_group_d,hd.child_group_e,
                    ml.value as markup_value,ml.value_type as markup_value_type,ml.markup_currency,ml.origin as markup_origin, ml.type as markup_type,
                    ml.reference_id as reference_id

                FROM 
                    hotel_details hd 
                    LEFT OUTER JOIN markup_list ml on hd.hotel_details_id=ml.reference_id 
                    AND ml.type='specific'
                    AND ml.module_type='b2c_hotel'
                WHERE 
                    hd.city_details_id like '%".$city_id."%'
                AND hd.hotel_type_id !='".VILLA."' 
                AND hd.status='ACTIVE' 
                AND hd.contract_expire_date >= CURDATE()
                AND hd.hotel_details_id IN (
                    SELECT  
                        DISTINCT hotel_details_id 
                    FROM
                        `seasons_details` hsd 
                    WHERE 
                        hotel_details_id = hd.hotel_details_id 
                   
                     AND 
                        '".$checkin_date."' >= hsd.seasons_from_date AND '".$checkout_date."' <= hsd.seasons_to_date
                     AND '".$checkin_date."' >= '".Date('Y-m-d', strtotime("+1 days"))."'
                     AND 
                        ".$stay_days." >= hsd.minimum_stays AND hsd.status='ACTIVE'
                     AND hotel_room_type_id IN  
                        (
                            SELECT 
                                DISTINCT hrci.hotel_room_type_id 
                            FROM 
                                hotel_room_type ht 
                            LEFT JOIN
                                hotel_season_room_count_info hrci 
                                ON (
                                        (
                                            ht.hotel_room_type_id = hrci.hotel_room_type_id
                                        )
                                    AND 
                                        ht.hotel_details_id = hrci.hotel_details_id
                                   )
                            LEFT JOIN
                                 hotel_room_rate_info rate
                                 ON (
                                        rate.seasons_details_id = hrci.hotel_season_id
                                        AND 
                                        rate.hotel_details_id = hrci.hotel_details_id
                                        AND 
                                        rate.hotel_room_type_id = hrci.hotel_room_type_id
                                        AND  rate.hotel_room_rate_info_id IN(
                                               SELECT hrr.hotel_rome_rate_info_id 
                           				 FROM 
                                		hotel_room_rate hrr 
                                            WHERE hrr.hotel_rome_rate_info_id=rate.hotel_room_rate_info_id
                                            
                                           AND hrr.default_nationality='default'

                                        )  
                                    )
                     
                            WHERE 
                                ht.status='ACTIVE'
                            AND
                                hrci.status='ACTIVE' 
                            AND 
                                rate.hotel_room_rate_info_id IS NOT NULL    
                            AND
                                ht.hotel_details_id = hd.hotel_details_id
                            AND 
                                hrci.hotel_room_type_id IS NOT NULL
                            AND 
                                rate.seasons_details_id IS NOT NULL
                            AND 
                                (
                                    hrci.no_of_room_available > hrci.no_of_room_booked AND  (hrci.no_of_room_available - hrci.no_of_room_booked >= ".$room_count.")
                                )
                            
                            AND 
                                (  rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<=ht.adult AND ".$s_max_child."<=ht.child)
                                OR
                                (  rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<= ht.max_pax AND ".$s_max_child."<=ht.child AND ht.extra_bed ='Available')
                                OR
                                (  rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<= ht.max_pax AND ".$s_max_child."<=ht.child + 1 AND ht.extra_bed ='Available')
                                OR
                                (   rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<= ht.adult AND ".$s_max_child."<=ht.child + 1 AND ht.extra_bed ='Available')
                        )
                       
                )
                ORDER BY hd.creation_date DESC";
                //echo "if";
                //debug("dfgfg");exit;
                //debug($nationality_fk);
                //debug($q3);
                //debug($this->db->last_query($q3));
               // exit;
                
                 return $this->db->query($q3);
                 	}
                 	else{
                 	     return $this->db->query($q2);
                 	}
                }
                else{
                   // debug("else");exit;
                   //debug($city_id);exit;
                       $nationality="default";
                 	      $q3 = "
                SELECT 
                    hd.hotel_details_id,hd.country_id,hd.city_details_id,hd.hotel_type_id,hd.hotel_address,
                    hd.hotel_name,hd.hotel_code,hd.hotel_price,currency_type,hd.min_stay_day,hd.hotel_amenities,hd.latitude,hd.longitude,
                    hd.max_stay_day,hd.star_rating,hd.hotel_info,hd.thumb_image,
                    hd.hotel_images,hd.hotel_image_url,
                    hd.child_group_a,hd.child_group_b,hd.child_group_c,hd.child_group_d,hd.child_group_e,
                    ml.value as markup_value,ml.value_type as markup_value_type,ml.markup_currency,ml.origin as markup_origin, ml.type as markup_type,
                    ml.reference_id as reference_id

                FROM 
                    hotel_details hd 
                    LEFT OUTER JOIN markup_list ml on hd.hotel_details_id=ml.reference_id 
                    AND ml.type='specific'
                    AND ml.module_type='b2c_hotel'
                WHERE 
                    hd.city_details_id like '%".$city_id."%'
                AND hd.hotel_type_id !='".VILLA."' 
                AND hd.status='ACTIVE' 
                AND hd.contract_expire_date >= CURDATE()
                AND hd.hotel_details_id IN (
                    SELECT  
                        DISTINCT hotel_details_id 
                    FROM
                        `seasons_details` hsd 
                    WHERE 
                        hotel_details_id = hd.hotel_details_id 
                   
                     AND 
                        '".$checkin_date."' >= hsd.seasons_from_date AND '".$checkout_date."' <= hsd.seasons_to_date
                     AND '".$checkin_date."' >= '".Date('Y-m-d', strtotime("+1 days"))."'
                     AND 
                        ".$stay_days." >= hsd.minimum_stays AND hsd.status='ACTIVE'
                     AND hotel_room_type_id IN  
                        (
                            SELECT 
                                DISTINCT hrci.hotel_room_type_id 
                            FROM 
                                hotel_room_type ht 
                            LEFT JOIN
                                hotel_season_room_count_info hrci 
                                ON (
                                        (
                                            ht.hotel_room_type_id = hrci.hotel_room_type_id
                                        )
                                    AND 
                                        ht.hotel_details_id = hrci.hotel_details_id
                                   )
                            LEFT JOIN
                                 hotel_room_rate_info rate
                                 ON (
                                        rate.seasons_details_id = hrci.hotel_season_id
                                        AND 
                                        rate.hotel_details_id = hrci.hotel_details_id
                                        AND 
                                        rate.hotel_room_type_id = hrci.hotel_room_type_id
                                        AND  rate.hotel_room_rate_info_id IN(
                                               SELECT hrr.hotel_rome_rate_info_id 
                           				 FROM 
                                		hotel_room_rate hrr 
                                            WHERE hrr.hotel_rome_rate_info_id=rate.hotel_room_rate_info_id
                                            
                                           AND hrr.default_nationality='default'

                                        )  
                                    )
                     
                            WHERE 
                                ht.status='ACTIVE'
                            AND
                                hrci.status='ACTIVE' 
                            AND 
                                rate.hotel_room_rate_info_id IS NOT NULL    
                            AND
                                ht.hotel_details_id = hd.hotel_details_id
                            AND 
                                hrci.hotel_room_type_id IS NOT NULL
                            AND 
                                rate.seasons_details_id IS NOT NULL
                            AND 
                                (
                                    hrci.no_of_room_available > hrci.no_of_room_booked AND  (hrci.no_of_room_available - hrci.no_of_room_booked >= ".$room_count.")
                                )
                            
                            AND 
                                (  rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<=ht.adult AND ".$s_max_child."<=ht.child)
                                OR
                                (  rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<= ht.max_pax AND ".$s_max_child."<=ht.child AND ht.extra_bed ='Available')
                                OR
                                (  rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<= ht.max_pax AND ".$s_max_child."<=ht.child + 1 AND ht.extra_bed ='Available')
                                OR
                                (   rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<= ht.adult AND ".$s_max_child."<=ht.child + 1 AND ht.extra_bed ='Available')
                        )
                       
                )
                ORDER BY hd.creation_date DESC";
               // debug($q3);exit;
               
                
                 return $this->db->query($q3);
                }
       
	}


	function get_crs_search_data_old($datetime1, $datetime2,$stay_days,$s_max_adult,$s_max_child,$checkin_date,$checkout_date,$room_count,$city_id){


		// debug(CURDATE());exit();
		 $q2 = "
                SELECT 
                    hd.hotel_details_id,hd.country_id,hd.city_details_id,hd.hotel_type_id,hd.hotel_address,
                    hd.hotel_name,hd.hotel_code,hd.hotel_price,currency_type,hd.min_stay_day,hd.hotel_amenities,hd.latitude,hd.longitude,
                    hd.max_stay_day,hd.star_rating,hd.hotel_info,hd.thumb_image,
                    hd.hotel_images,hd.hotel_image_url,
                    hd.child_group_a,hd.child_group_b,hd.child_group_c,hd.child_group_d,hd.child_group_e

                FROM 
                    hotel_details hd 
                WHERE 
                    hd.city_details_id like '%".$city_id."%'
                AND hd.status='ACTIVE' 
                AND hd.contract_expire_date >= CURDATE()
                AND hd.hotel_details_id IN (
                    SELECT  
                        DISTINCT hotel_details_id 
                    FROM
                        `seasons_details` hsd 
                    WHERE 
                        hotel_details_id = hd.hotel_details_id 
                   
                     AND 
                        '".$checkin_date."' >= hsd.seasons_from_date AND '".$checkout_date."' <= hsd.seasons_to_date
                     AND '".$checkin_date."' >= '".Date('Y-m-d', strtotime("+3 days"))."'
                     AND 
                        ".$stay_days." >= hsd.minimum_stays AND hsd.status='ACTIVE'
                     AND hotel_room_type_id IN  
                        (
                            SELECT 
                                DISTINCT hrci.hotel_room_type_id 
                            FROM 
                                hotel_room_type ht 
                            LEFT JOIN
                                hotel_season_room_count_info hrci 
                                ON (
                                        (
                                            ht.hotel_room_type_id = hrci.hotel_room_type_id
                                        )
                                    AND 
                                        ht.hotel_details_id = hrci.hotel_details_id
                                   )
                            LEFT JOIN
                                 hotel_room_rate_info rate
                                 ON (
                                        rate.seasons_details_id = hrci.hotel_season_id
                                        AND 
                                        rate.hotel_details_id = hrci.hotel_details_id
                                        AND 
                                        rate.hotel_room_type_id = hrci.hotel_room_type_id
                                    )
                            WHERE 
                                ht.status='ACTIVE'
                            AND
                                hrci.status='ACTIVE' 
                            AND 
                                rate.hotel_room_rate_info_id IS NOT NULL    
                            AND
                                ht.hotel_details_id = hd.hotel_details_id
                            AND 
                                hrci.hotel_room_type_id IS NOT NULL
                            AND 
                                rate.seasons_details_id IS NOT NULL
                            AND 
                                (
                                    hrci.no_of_room_available > hrci.no_of_room_booked AND  (hrci.no_of_room_available - hrci.no_of_room_booked >= ".$room_count.")
                                )
                            
                            AND 
                                (  rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<=ht.adult AND ".$s_max_child."<=ht.child)
                                OR
                                (  rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<= ht.max_pax AND ".$s_max_child."<=ht.child AND ht.extra_bed ='Available')
                                OR
                                (  rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<= ht.max_pax AND ".$s_max_child."<=ht.child + 1 AND ht.extra_bed ='Available')
                                OR
                                (   rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<= ht.adult AND ".$s_max_child."<=ht.child + 1 AND ht.extra_bed ='Available')
                        )
                )
                ORDER BY hd.creation_date DESC";
              //  debug($q2);exit;
                // debug($row->result());
       /* debug($this->db->last_query($q2));
         exit();*/
        return $this->db->query($q2);
	}
	
	
	
    public function gets_crs_topPrice_forSearch($hotel_id,$hotel_room_type_id='',$nationality_fk='')
   { 
       if($nationality_fk!='')
       {
          
               $q = "SELECT hri.*,hr.*,ht.* FROM hotel_room_rate_info hri 
              LEFT JOIN hotel_room_rate hr ON (hri.hotel_room_rate_info_id=hr.hotel_rome_rate_info_id)
              LEFT JOIN hotel_room_type ht ON (hri.hotel_room_type_id = ht.hotel_room_type_id)
              WHERE hri.hotel_details_id=".$hotel_id."
               AND hri.roomrate_status='ACTIVE'
 				AND hr.country_list_nationality_id=".$nationality_fk."
               "
              ;
               
                if($hotel_room_type_id!='')
                {
                    $q.=" AND hri.hotel_room_type_id=".$hotel_room_type_id;  
                }  
                $q.=" AND hri.seasons_details_id IS NOT NULL AND hr.hotel_room_rate_id IS NOT NULL";
                $q.=" ORDER BY hr.single_room_price,hr.weekend_single_room_price,hr.double_room_price,hr.weekend_double_room_price,hr.triple_room_price,hr.weekend_triple_room_price,hr.quad_room_price,hr.weekend_quad_room_price,hr.hex_room_price,hr.weekend_hex_room_price LIMIT 1";
                
                $overall_data=$this->db->query($q);
               /* debug($q);
                debug($overall_data);exit;*/
               
                if($overall_data->num_rows==0)
                {
                   
                      $nationality_fk_new=0;
                         $q = "SELECT hri.*,hr.*,ht.* FROM hotel_room_rate_info hri 
                      LEFT JOIN hotel_room_rate hr ON (hri.hotel_room_rate_info_id=hr.hotel_rome_rate_info_id)
                      LEFT JOIN hotel_room_type ht ON (hri.hotel_room_type_id = ht.hotel_room_type_id)
                      WHERE hri.hotel_details_id=".$hotel_id."
                       AND hri.roomrate_status='ACTIVE'
         				AND hr.default_nationality='default'
                       "
                      ;
               
                if($hotel_room_type_id!='')
                {
                    $q.=" AND hri.hotel_room_type_id=".$hotel_room_type_id;  
                }  
                $q.=" AND hri.seasons_details_id IS NOT NULL AND hr.hotel_room_rate_id IS NOT NULL";
                $q.=" ORDER BY hr.single_room_price,hr.weekend_single_room_price,hr.double_room_price,hr.weekend_double_room_price,hr.triple_room_price,hr.weekend_triple_room_price,hr.quad_room_price,hr.weekend_quad_room_price,hr.hex_room_price,hr.weekend_hex_room_price LIMIT 1";
                 
                // debug($q);exit;
                    return $this->db->query($q); 
                }
                else{
                      return $this->db->query($q);
                } 
          }
       else{
                 $nationality_fk_new=0;
                 $q = "SELECT hri.*,hr.*,ht.* FROM hotel_room_rate_info hri 
              LEFT JOIN hotel_room_rate hr ON (hri.hotel_room_rate_info_id=hr.hotel_rome_rate_info_id)
              LEFT JOIN hotel_room_type ht ON (hri.hotel_room_type_id = ht.hotel_room_type_id)
              WHERE hri.hotel_details_id=".$hotel_id."
               AND hri.roomrate_status='ACTIVE'
 				AND hr.default_nationality='default'
               "
              ;
       
        if($hotel_room_type_id!='')
        {
            $q.=" AND hri.hotel_room_type_id=".$hotel_room_type_id;  
        }  
        $q.=" AND hri.seasons_details_id IS NOT NULL AND hr.hotel_room_rate_id IS NOT NULL";
        $q.=" ORDER BY hr.single_room_price,hr.weekend_single_room_price,hr.double_room_price,hr.weekend_double_room_price,hr.triple_room_price,hr.weekend_triple_room_price,hr.quad_room_price,hr.weekend_quad_room_price,hr.hex_room_price,hr.weekend_hex_room_price LIMIT 1";
         
            return $this->db->query($q); 
       }
   
        
        
      
   }
	

	 public function gets_crs_topPrice_forSearch_16_4($hotel_id,$hotel_room_type_id='',$nationality_fk='')
   { 
       //debug($nationality_fk);exit;
       /* $q = "SELECT hri.*,hr.*,ht.* FROM hotel_room_rate_info hri 
              LEFT JOIN hotel_room_rate hr ON (hri.hotel_room_rate_info_id=hr.hotel_rome_rate_info_id)
              LEFT JOIN hotel_room_type ht ON (hri.hotel_room_type_id = ht.hotel_room_type_id)
              WHERE hri.hotel_details_id=".$hotel_id." AND hri.roomrate_status='ACTIVE'";*/
               $q = "SELECT hri.*,hr.*,ht.* FROM hotel_room_rate_info hri 
              LEFT JOIN hotel_room_rate hr ON (hri.hotel_room_rate_info_id=hr.hotel_rome_rate_info_id)
              LEFT JOIN hotel_room_type ht ON (hri.hotel_room_type_id = ht.hotel_room_type_id)
              WHERE hri.hotel_details_id=".$hotel_id."
               AND hri.roomrate_status='ACTIVE'
 				AND hr.country_list_nationality_id=".$nationality_fk."
               "
              ;
       
        /*if($seasons_details_id!='')
        {
            $q.=" AND hri.seasons_details_id=".$seasons_details_id; 
        }*/
        
        if($hotel_room_type_id!='')
        {
            $q.=" AND hri.hotel_room_type_id=".$hotel_room_type_id;  
        }  
        $q.=" AND hri.seasons_details_id IS NOT NULL AND hr.hotel_room_rate_id IS NOT NULL";
        $q.=" ORDER BY hr.single_room_price,hr.weekend_single_room_price,hr.double_room_price,hr.weekend_double_room_price,hr.triple_room_price,hr.weekend_triple_room_price,hr.quad_room_price,hr.weekend_quad_room_price,hr.hex_room_price,hr.weekend_hex_room_price LIMIT 1";
         //print_r($q);exit;
        // debug($this->db->query($q));exit;
        $overall_data=$this->db->query($q);
        //debug($overall_data->num_rows);
        if($overall_data->num_rows==0)
        {
            /*$nationality_fk_new=0;
                 $q = "SELECT hri.*,hr.*,ht.* FROM hotel_room_rate_info hri 
              LEFT JOIN hotel_room_rate hr ON (hri.hotel_room_rate_info_id=hr.hotel_rome_rate_info_id)
              LEFT JOIN hotel_room_type ht ON (hri.hotel_room_type_id = ht.hotel_room_type_id)
              WHERE hri.hotel_details_id=".$hotel_id."
               AND hri.roomrate_status='ACTIVE'
 				AND hr.country_list_nationality_id=".$nationality_fk_new."
               "
              ;*/
              $nationality_fk_new=0;
                 $q = "SELECT hri.*,hr.*,ht.* FROM hotel_room_rate_info hri 
              LEFT JOIN hotel_room_rate hr ON (hri.hotel_room_rate_info_id=hr.hotel_rome_rate_info_id)
              LEFT JOIN hotel_room_type ht ON (hri.hotel_room_type_id = ht.hotel_room_type_id)
              WHERE hri.hotel_details_id=".$hotel_id."
               AND hri.roomrate_status='ACTIVE'
 				AND hr.default_nationality='default'
               "
              ;
       
        if($hotel_room_type_id!='')
        {
            $q.=" AND hri.hotel_room_type_id=".$hotel_room_type_id;  
        }  
        $q.=" AND hri.seasons_details_id IS NOT NULL AND hr.hotel_room_rate_id IS NOT NULL";
        $q.=" ORDER BY hr.single_room_price,hr.weekend_single_room_price,hr.double_room_price,hr.weekend_double_room_price,hr.triple_room_price,hr.weekend_triple_room_price,hr.quad_room_price,hr.weekend_quad_room_price,hr.hex_room_price,hr.weekend_hex_room_price LIMIT 1";
         
            return $this->db->query($q); 
        }
        else{
              return $this->db->query($q);
        }
        
        
      
   }

   public function get_roomType_data($room_type_id,$hotel_details_id)
   {
        $q="SELECT * FROM hotel_room_type WHERE status='ACTIVE' AND hotel_details_id=".$hotel_details_id." AND hotel_room_type_id=".$room_type_id." ORDER BY hotel_room_type_id DESC LIMIT 1";
        //echo $q;exit;
        return $this->db->query($q);
   }

   function get_general_settings($id){
        $this->db->select('*');
        $this->db->where('hotel_details_id',$id);
        return $this->db->get('hotel_details');
        //echo $this->db->last_query(); exit();
        /*if($query->num_rows() > 0){
            return $query->row();
        }else{
            
        }*/
    }

    public function get_crsHotels_byHotelId($hotel_id)
    {
       /* $q = "SELECT h.*,hc.city_name,hc.country_name 
        FROM hotel_details h 
        INNER JOIN api_hotel_city_list as hc ON (hc.city_name = h.city_details_id) WHERE h.hotel_details_id=".$hotel_id." AND h.status='ACTIVE' AND h.contract_expire_date >= CURDATE() ORDER BY h.creation_date DESC";
        //echo $q; exit;
        return $this->db->query($q);*/

         $q = "SELECT h.*,hc.city_name,hc.country_name,
         
                    ml.value as markup_value,ml.value_type as markup_value_type,ml.markup_currency,ml.origin as markup_origin, ml.type as markup_type,
                    ml.reference_id as reference_id 
        FROM hotel_details h 
        INNER JOIN api_hotel_city_list as hc ON (hc.city_name = h.city_details_id) 
         LEFT OUTER JOIN markup_list ml on h.hotel_details_id=ml.reference_id 
                    AND ml.type='specific'
                    AND ml.module_type='b2c_hotel'
                    WHERE h.hotel_details_id=".$hotel_id." AND h.status='ACTIVE' AND h.contract_expire_date >= CURDATE() ORDER BY h.creation_date DESC";
        //debug($q); exit;
        return $this->db->query($q);
    }

    public function get_crs_allRooms($hotel_id,$GET)
   {    
        $adult_count = max($GET['data']['adult_config']);
        $children_count = max($GET['data']['child_config']);
        $total_pax = $adult_count + $children_count;
        $checkin = explode('/', $GET['data']['from_date']);
        $checkin = $checkin[2].'-'.$checkin[1].'-'.$checkin[0];
        $datetime1 = new DateTime($checkin);
        $checkout = explode('/', $GET['data']['to_date']);
        $checkout = $checkout[2].'-'.$checkout[1].'-'.$checkout[0];
        $datetime2 = new DateTime($checkout); 
        //$oDiff = $datetime1->diff($datetime2);
        $stay_days = $GET['data']['no_of_nights'];
        $checkin_date = date('Y-m-d',strtotime($checkin));
        $checkout_date = date('Y-m-d',strtotime($checkout));
        $room_count = $GET['data']['room_count'];


         $q="
            SELECT 
                sd.*,h.* 
            FROM  
                seasons_details sd
            LEFT JOIN 
                hotel_room_type h ON (sd.hotel_room_type_id=h.hotel_room_type_id)
            WHERE 
                sd.hotel_details_id=".$hotel_id." 
            AND sd.status='ACTIVE' 
            AND '".$checkin_date."' >= sd.seasons_from_date 
            AND '".$checkout_date."' <= sd.seasons_to_date 
            AND ".$stay_days." >= sd.minimum_stays
            AND h.status='ACTIVE' 
            
            AND 
                h.hotel_details_id=".$hotel_id."
            ORDER BY 
                sd.seasons_from_date";
               // echo $q; exit("hotel_model");
         return $this->db->query($q);
   }

    function getAvailableRoomsHotel($checkin,$checkout,$roomid,$season){
       
        //$sql = "select sum(`no_of_room_available`) as rooms from hotel_room_count_info where `hotel_details_id` = $hotelid";

          $sql = "select sum(confirmed_room_count) as 'rooms_booked_count' from booking_global,booking_hotel, hotel_season_room_count_info
               where  booking_hotel.hotel_room_type_id = hotel_season_room_count_info.hotel_room_type_id 
            and booking_global.ref_id = booking_hotel.id and booking_global.booking_status = 'CONFIRM' 
            and booking_hotel.actual_check_in_date <='$checkin' and  booking_hotel.actual_check_out_date >= '$checkout' 
            and hotel_season_room_count_info.hotel_room_type_id  = '$roomid' and hotel_season_room_count_info.hotel_season_id = '$season' ";
        /*$query =  $this->db->query($sql);
        //echo $this->db->last_query(); exit();
         if($query->num_rows() > 0){
            return $query->row();
        }else{
            return 0;
        }*/

        // debug($sql);exit();
        return $this->db->query($sql);
       
    }

    function getAvailableRooms($checkin,$checkout,$roomid,$season){
       
        //$sql = "select sum(`no_of_room_available`) as rooms from hotel_room_count_info where `hotel_details_id` = $hotelid";

          $sql = "select sum(confirmed_room_count) as 'rooms_booked_count' from booking_global,booking_hotel, hotel_season_room_count_info
               where  booking_hotel.hotel_room_type_id = hotel_season_room_count_info.hotel_room_type_id 
            and booking_global.ref_id = booking_hotel.id and booking_global.booking_status = 'CONFIRM' 
            and booking_hotel.actual_check_in_date <='$checkin' and  booking_hotel.actual_check_out_date >= '$checkout' 
            and hotel_season_room_count_info.hotel_room_type_id  = '$roomid' and hotel_season_room_count_info.hotel_season_id = '$season' ";
       
        $query =  $this->db->query($sql);
        //echo $this->db->last_query(); exit();
         if($query->num_rows() > 0){
            return $query->row();
        }else{
            return 0;
        }
       
    }

    public function getBookedRooms($roomid,$season){
        $this->db->select('*');
        $this->db->from('hotel_season_room_count_info');
        $this->db->where('hotel_room_type_id',$roomid);
        $this->db->where('hotel_season_id',$season);
        $query = $this->db->get();
       // echo $this->db->last_query(); 
        if($query->num_rows > 0){
            return $query->row();
        }else{
            return false;
        }

    }
         public function get_crs_topPrice_room($hotel_id,$seasons_details_id='',$hotel_room_type_id='',$nationality_fk='')
   { 
      // debug($nationality_fk);exit;
      if($nationality_fk!='')
      {
             $q = "SELECT hri.*,hr.* FROM hotel_room_rate_info hri 
              LEFT JOIN hotel_room_rate hr ON (hri.hotel_room_rate_info_id=hr.hotel_rome_rate_info_id)
              WHERE hri.hotel_details_id=".$hotel_id." 
              AND hri.roomrate_status='ACTIVE'
              AND hr.country_list_nationality_id=".$nationality_fk."
              ";
       
       /* if($seasons_details_id!='')
        {
            $q.=" AND hri.seasons_details_id=".$seasons_details_id; 
        }
        
        if($hotel_room_type_id!='')
        {
            $q.=" AND hri.hotel_room_type_id=".$hotel_room_type_id;  
        }  
        $q.=" LIMIT 1";*/
         $q.="  ORDER BY hr.single_room_price,hr.weekend_single_room_price,hr.double_room_price,hr.weekend_double_room_price,hr.triple_room_price,hr.weekend_triple_room_price,hr.quad_room_price,hr.weekend_quad_room_price,hr.hex_room_price,hr.weekend_hex_room_price LIMIT 1";
       /* debug($q);
        debug($this->db->query($q)->result());exit;*/
        
        $overall_data=$this->db->query($q);
      	if($overall_data->num_rows==0)
        {
            $nationality_fk=0;
              $q = "SELECT hri.*,hr.* FROM hotel_room_rate_info hri 
              LEFT JOIN hotel_room_rate hr ON (hri.hotel_room_rate_info_id=hr.hotel_rome_rate_info_id)
              WHERE hri.hotel_details_id=".$hotel_id." 
              AND hri.roomrate_status='ACTIVE'
              AND hr.default_nationality='default'
              ";
       
        if($seasons_details_id!='')
        {
            $q.=" AND hri.seasons_details_id=".$seasons_details_id; 
        }
        
        if($hotel_room_type_id!='')
        {
            $q.=" AND hri.hotel_room_type_id=".$hotel_room_type_id;  
        }  
        $q.=" LIMIT 1";
            
             return $this->db->query($q);
        }
        else{
             return $this->db->query($q);
        }
        
      }
      else{
                $nationality_fk=0;
              $q = "SELECT hri.*,hr.* FROM hotel_room_rate_info hri 
              LEFT JOIN hotel_room_rate hr ON (hri.hotel_room_rate_info_id=hr.hotel_rome_rate_info_id)
              WHERE hri.hotel_details_id=".$hotel_id." 
              AND hri.roomrate_status='ACTIVE'
              AND hr.default_nationality='default'
              ";
       
        if($seasons_details_id!='')
        {
            $q.=" AND hri.seasons_details_id=".$seasons_details_id; 
        }
        
        if($hotel_room_type_id!='')
        {
            $q.=" AND hri.hotel_room_type_id=".$hotel_room_type_id;  
        }  
        $q.=" LIMIT 1";
            
             return $this->db->query($q);
      }
     
        
       
   }
     public function get_crs_topPrice_room_oldd($hotel_id,$seasons_details_id='',$hotel_room_type_id='',$nationality_fk='')
   { 
      // debug($nationality_fk);exit;
      if($nationality_fk!='')
      {
             $q = "SELECT hri.*,hr.* FROM hotel_room_rate_info hri 
              LEFT JOIN hotel_room_rate hr ON (hri.hotel_room_rate_info_id=hr.hotel_rome_rate_info_id)
              WHERE hri.hotel_details_id=".$hotel_id." 
              AND hri.roomrate_status='ACTIVE'
              AND hr.country_list_nationality_id=".$nationality_fk."
              ";
       
        if($seasons_details_id!='')
        {
            $q.=" AND hri.seasons_details_id=".$seasons_details_id; 
        }
        
        if($hotel_room_type_id!='')
        {
            $q.=" AND hri.hotel_room_type_id=".$hotel_room_type_id;  
        }  
        $q.=" LIMIT 1";
        debug($q);
        debug($this->db->query($q)->result());exit;
        
        $overall_data=$this->db->query($q);
      	if($overall_data->num_rows==0)
        {
            $nationality_fk=0;
              $q = "SELECT hri.*,hr.* FROM hotel_room_rate_info hri 
              LEFT JOIN hotel_room_rate hr ON (hri.hotel_room_rate_info_id=hr.hotel_rome_rate_info_id)
              WHERE hri.hotel_details_id=".$hotel_id." 
              AND hri.roomrate_status='ACTIVE'
              AND hr.default_nationality='default'
              ";
       
        if($seasons_details_id!='')
        {
            $q.=" AND hri.seasons_details_id=".$seasons_details_id; 
        }
        
        if($hotel_room_type_id!='')
        {
            $q.=" AND hri.hotel_room_type_id=".$hotel_room_type_id;  
        }  
        $q.=" LIMIT 1";
            
             return $this->db->query($q);
        }
        else{
             return $this->db->query($q);
        }
        
      }
      else{
                $nationality_fk=0;
              $q = "SELECT hri.*,hr.* FROM hotel_room_rate_info hri 
              LEFT JOIN hotel_room_rate hr ON (hri.hotel_room_rate_info_id=hr.hotel_rome_rate_info_id)
              WHERE hri.hotel_details_id=".$hotel_id." 
              AND hri.roomrate_status='ACTIVE'
              AND hr.default_nationality='default'
              ";
       
        if($seasons_details_id!='')
        {
            $q.=" AND hri.seasons_details_id=".$seasons_details_id; 
        }
        
        if($hotel_room_type_id!='')
        {
            $q.=" AND hri.hotel_room_type_id=".$hotel_room_type_id;  
        }  
        $q.=" LIMIT 1";
            
             return $this->db->query($q);
      }
     
        
       
   }


    public function get_crs_topPrice_room_16_7($hotel_id,$seasons_details_id='',$hotel_room_type_id='',$nationality_fk='')
   { 
      // debug($nationality_fk);exit;
        $q = "SELECT hri.*,hr.* FROM hotel_room_rate_info hri 
              LEFT JOIN hotel_room_rate hr ON (hri.hotel_room_rate_info_id=hr.hotel_rome_rate_info_id)
              WHERE hri.hotel_details_id=".$hotel_id." 
              AND hri.roomrate_status='ACTIVE'
              AND hr.country_list_nationality_id=".$nationality_fk."
              ";
       
        if($seasons_details_id!='')
        {
            $q.=" AND hri.seasons_details_id=".$seasons_details_id; 
        }
        
        if($hotel_room_type_id!='')
        {
            $q.=" AND hri.hotel_room_type_id=".$hotel_room_type_id;  
        }  
        $q.=" LIMIT 1";
       // debug($this->db->query($q)->result());exit;
        
        $overall_data=$this->db->query($q);
      	if($overall_data->num_rows==0)
        {
            $nationality_fk=0;
              $q = "SELECT hri.*,hr.* FROM hotel_room_rate_info hri 
              LEFT JOIN hotel_room_rate hr ON (hri.hotel_room_rate_info_id=hr.hotel_rome_rate_info_id)
              WHERE hri.hotel_details_id=".$hotel_id." 
              AND hri.roomrate_status='ACTIVE'
              AND hr.default_nationality='default'
              ";
       
        if($seasons_details_id!='')
        {
            $q.=" AND hri.seasons_details_id=".$seasons_details_id; 
        }
        
        if($hotel_room_type_id!='')
        {
            $q.=" AND hri.hotel_room_type_id=".$hotel_room_type_id;  
        }  
        $q.=" LIMIT 1";
            
             return $this->db->query($q);
        }
        else{
             return $this->db->query($q);
        }
        
        
       
   }

    public function get_crs_topPrice($hotel_id,$seasons_details_id='',$hotel_room_type_id='')
   { //echo $hotel_id."---".$seasons_details_id."--".$hotel_room_type_id;exit;
        $q = "SELECT hri.*,hr.* FROM hotel_room_rate_info hri 
              LEFT JOIN hotel_room_rate hr ON (hri.hotel_room_rate_info_id=hr.hotel_rome_rate_info_id)
              WHERE hri.hotel_details_id=".$hotel_id." AND hri.roomrate_status='ACTIVE'";
       
        if($seasons_details_id!='')
        {
            $q.=" AND hri.seasons_details_id=".$seasons_details_id; 
        }
        
        if($hotel_room_type_id!='')
        {
            $q.=" AND hri.hotel_room_type_id=".$hotel_room_type_id;  
        }  
        $q.=" LIMIT 1";
        //echo $q;exit;
        return $this->db->query($q);
   }

   public function get_hotel_room_details($hotel_details_id,$hotel_room_type_id)
   {
        $q="SELECT * FROM hotel_room_details WHERE hotel_details_id=".$hotel_details_id." AND hotel_room_type_id=".$hotel_room_type_id;
        return $this->db->query($q);
   }

   public function get_hotel_amenities_byamenityId($amenityId)
   {
        $q="SELECT * FROM hotel_amenities WHERE hotel_amenities_id=".$amenityId;
        return $this->db->query($q);
   }

   public function get_hotel_amenities_name_byamenityId($amenityId)
   {
        $q="SELECT amenities_name FROM hotel_amenities WHERE hotel_amenities_id=".$amenityId;
        return $this->db->query($q);
   }

   public function get_room_details($hotelid,$roomid){ 
   	$q="SELECT * FROM hotel_room_type WHERE hotel_details_id=".$hotelid." AND hotel_room_type_id=".$roomid;
   	// print_r($q); exit();
        return $this->db->query($q);
   }



   public function insert_globel_data($post_params,$search_id,$book_id,$user_id,$temp_booking,$valid_temp_token,$safe_search_data){
   	// error_reporting(E_ALL);
       //echo '<pre>sajay'; print_r($valid_temp_token['price']); exit();
        
        //16-10-18 added for promocode

        // if(isset($valid_temp_token['promocode_value']) && $valid_temp_token['promocode_value'] != 0){
        // $valid_temp_token['price'] = $valid_temp_token['price'] - $valid_temp_token['promocode_value'];	
        // }
		//
        $booking_status     = 'PROCESS';
        
         
        $contact_email_id   = $post_params['billing_email'];
        $room_type_data     = $this->get_room_type_data($valid_temp_token['HotelCode'],$valid_temp_token['room_id']);
         
        $room_type_data     = ($room_type_data->num_rows()>=1) ? $room_type_data->result_array()[0] : false;

        $hotel_rooms_count_info     = $this->get_hotel_rooms_count_info($valid_temp_token['HotelCode'],$valid_temp_token['room_id'],$valid_temp_token['season_id'])->row_array();

        $hotel_room_count_info_id   = intval($hotel_rooms_count_info['hotel_season_room_count_info_id']);

        // debug($post_params);
        // exit("858");
       
            $booking_hotel = array(
                                'booking_hotel_code'                => '',
                                'request'                           => json_encode($valid_temp_token),
                                'response'                          => '',
                                'cart_data'                         => base64_encode(json_encode($post_params)),
                                'hotel_name'                        => $valid_temp_token['HotelName'],
                                'hotel_data'                        => '',
                                'book_no'                           => '0',
                                'bh_booking_status'                 => $booking_status,
                                'nationality'                       => 'India',
                                'bh_parent_pnr'                        => '',
                                'parent_booking_number'             => '',
                                'ip_address'                        => $this->input->ip_address(),
                                'actual_check_in_date'              => date('Y-m-d',strtotime($safe_search_data['data']['from_date'])),
                                'actual_check_out_date'             => date('Y-m-d',strtotime($safe_search_data['data']['to_date'])),
                                'check_in_date'                     => $safe_search_data['data']['from_date'],
                                'check_out_date'                    => $safe_search_data['data']['to_date'],
                                'contact_email'                     => $post_params['billing_email'],
                                'contact_fname'                     => $post_params['first_name'][0],
                                'contact_mname'                     => '',
                                'contact_sur_name'                  => $post_params['last_name'][0],
                                'contact_company_name'              => '',
                                'contact_address_line1'             => $post_params['billing_address_1'],
                                'contact_country_mobile_code'       => '',
                                'contact_mobile_number'             => $post_params['passenger_contact'],
                                'contact_country'                   => '',
                                'visit_type'                        => '',
                                'passenger_details'                 => json_encode($post_params),
                                'user_details_id'                   => $user_id,
                                //'user_type'                         => '',
                                'book_date'                         => date('y-m-d h:i:s'),
                                'api_temp_hotel_id'                 => '',
                                'shopping_cart_hotel_id'            => '',
                                'session_id'                        => $book_id,
                                'api'                               => 'CRS',
                                'hotel_code'                        => $valid_temp_token['HotelCode'],
                                'room_code'                         => $valid_temp_token['room_id'],
                                'hotel_room_details_id'             => $valid_temp_token['room_id'],
                                'hotel_details_id'                  => $valid_temp_token['HotelCode'],
                                'hotel_room_type_id'                => $valid_temp_token['room_id'],
                                'confirmed_room_count'              => $safe_search_data['data']['room_count'],
                                'onrequest_room_count'              => $safe_search_data['data']['room_count'],
                                'room_name'                         => ($room_type_data == false) ? '' : $room_type_data['room_type_name'],
                                'room_info'                         => '',
                                'hotel_room_count_info_id'          => $hotel_room_count_info_id,
                                'room_count'                        => $safe_search_data['data']['room_count'],
                                'cancellation_policy'               => '',
                                'room_amenities'                    => '',
                                'room_type_name'                    => ($room_type_data == false) ? '' : $room_type_data['room_type_name'],
                                'room_type_description'             => '',
                                'adult'                             => max($safe_search_data['data']['adult_config']),
                                'child'                             => max($safe_search_data['data']['child_config']),
                                'max_pax'                           => ($room_type_data == false) ? '' : $room_type_data['max_pax'],
                                'extra_bed'                         => '',
                                'extra_bed_count'                   => '',
                                'hotel_room_rate_info_id'           => '',
                                'tax_rate_info_id'                  => '',
                                'TotalPrice'                        => $valid_temp_token['price'],
                                'breakfast_price'                   => '',
                                'lunch_price'                       => '',
                                'dinner_price'                      => '',
                                'breakfast_price_flag'              => '0',
                                'lunch_price_flag'                  => '0',
                                'dinner_price_flag'                 => '0',
                                'adult_price'                       => '',
                                'child_price_a'                     => '',
                                'child_price_b'                     => '',
                                'child_price_c'                     => '',
                                'child_price_d'                     => '',
                                'child_price_e'                     => '',
                                'sgl_price'                         => '',
                                'dbl_price'                         => '',
                                'tpl_price'                         => '',
                                'quad_price'                        => '',
                                'hex_price'                         => '',
                                'extra_bed_price'                   => '',
                                'extra_bed_price_total'             => '',
                                'ad_markup'                         => '',
                                'cancel_policy'                     => '',
                                'cancel_till_date'                  => '',
                                'cancel_amount'                     => '',
                                'purchase_token'                    => '',
                                'service_val'                       => '',
                                'cancel_date_from'                  => '',
                                'cancel_date_to'                    => '',
                                'comment_remarks'                   => '',
                                'special_request'                   => '',
                                'transfer_details'                  => '',
                                'flight_details'                    => '',
                                'arr_flight_name'                   => '',
                                'arr_flight_no'                     => '',
                                'arr_flight_date'                   => '',
                                'arr_flight_time'                   => '',
                                'dpt_flight_name'                   => '',
                                'dpt_flight_no'                     => '',
                                'dpt_flight_date'                   => '',
                                'dpt_flight_time'                   => '',
                                'child_age'                         => '',
                                'booking_secret_key'                => $book_id,
                                'booking_challenge_key'             => $search_id,
                                'amended_date'                      => '',
                                'service_charge'                    =>'',
                                'payment_type'                      => '',
                                'unit_type'							=>'',
                               // 'unit_type'							=> $post_params['unit_type'],
                                'street_type'						=> '',
                                //'street_type'						=> $post_params['street_type'],
                                'HotelAddress'                      => $valid_temp_token['HotelAddress'],
                                
                                  'nationality_id'                     => $post_params['nationality_id'],
                                    'residency_id'                     => $post_params['residency']
                                                    );
            
			// debug($booking_hotel);
                       
                       $this->db->insert('booking_hotel',$booking_hotel);
                       // echo $this->db->last_query();exit("end");
                        return $this->db->insert_id();
    }



   public function insert_globel_data_old($post_params,$search_id,$book_id,$user_id,$temp_booking,$valid_temp_token,$safe_search_data){

       //echo '<pre>sajay'; print_r($valid_temp_token['price']); exit();
        
        //16-10-18 added for promocode

        // if(isset($valid_temp_token['promocode_value']) && $valid_temp_token['promocode_value'] != 0){
        // $valid_temp_token['price'] = $valid_temp_token['price'] - $valid_temp_token['promocode_value'];	
        // }
		//
        $booking_status     = 'PROCESS';
        
         
        $contact_email_id   = $post_params['booking_email_id'];
        $room_type_data     = $this->get_room_type_data($valid_temp_token['HotelCode'],$valid_temp_token['room_id']);
         // debug($room_type_data);exit();
        $room_type_data     = ($room_type_data->num_rows()>=1) ? $room_type_data->result_array()[0] : false;

        $hotel_rooms_count_info     = $this->get_hotel_rooms_count_info($valid_temp_token['HotelCode'],$valid_temp_token['room_id'],$valid_temp_token['season_id'])->row_array();
         // debug($hotel_rooms_count_info);exit();

        $hotel_room_count_info_id   = intval($hotel_rooms_count_info['hotel_season_room_count_info_id']);

        // debug($post_params);
        // exit("858");
       
            $booking_hotel = array(
                                'booking_hotel_code'                => '',
                                'request'                           => json_encode($valid_temp_token),
                                'response'                          => '',
                                'cart_data'                         => base64_encode(json_encode($post_params)),
                                'hotel_name'                        => $valid_temp_token['HotelName'],
                                'hotel_data'                        => '',
                                'book_no'                           => '0',
                                'bh_booking_status'                 => $booking_status,
                                'nationality'                       => 'India',
                                'bh_parent_pnr'                        => '',
                                'parent_booking_number'             => '',
                                'ip_address'                        => $this->input->ip_address(),
                                'actual_check_in_date'              => date('Y-m-d',strtotime($safe_search_data['data']['from_date'])),
                                'actual_check_out_date'             => date('Y-m-d',strtotime($safe_search_data['data']['to_date'])),
                                'check_in_date'                     => $safe_search_data['data']['from_date'],
                                'check_out_date'                    => $safe_search_data['data']['to_date'],
                                'contact_email'                     => $post_params['billing_email'],
                                'contact_fname'                     => $post_params['first_name'][0],
                                'contact_mname'                     => '',
                                'contact_sur_name'                  => $post_params['last_name'][0],
                                'contact_company_name'              => '',
                                'contact_address_line1'             => $post_params['billing_address_1'],
                                'contact_country_mobile_code'       => '',
                                'contact_mobile_number'             => $post_params['passenger_contact'],
                                'contact_country'                   => '',
                                'visit_type'                        => '',
                                'passenger_details'                 => json_encode($post_params),
                                'user_details_id'                   => $user_id,
                                //'user_type'                         => '',
                                'book_date'                         => date('y-m-d h:i:s'),
                                'api_temp_hotel_id'                 => '',
                                'shopping_cart_hotel_id'            => '',
                                'session_id'                        => $book_id,
                                'api'                               => 'CRS',
                                'hotel_code'                        => $valid_temp_token['HotelCode'],
                                'room_code'                         => $valid_temp_token['room_id'],
                                'hotel_room_details_id'             => $valid_temp_token['room_id'],
                                'hotel_details_id'                  => $valid_temp_token['HotelCode'],
                                'hotel_room_type_id'                => $valid_temp_token['room_id'],
                                'confirmed_room_count'              => $safe_search_data['data']['room_count'],
                                'onrequest_room_count'              => $safe_search_data['data']['room_count'],
                                'room_name'                         => ($room_type_data == false) ? '' : $room_type_data['room_type_name'],
                                'room_info'                         => '',
                                'hotel_room_count_info_id'          => $hotel_room_count_info_id,
                                'room_count'                        => $safe_search_data['data']['room_count'],
                                'cancellation_policy'               => '',
                                'room_amenities'                    => '',
                                'room_type_name'                    => ($room_type_data == false) ? '' : $room_type_data['room_type_name'],
                                'room_type_description'             => '',
                                'adult'                             => max($safe_search_data['data']['adult_config']),
                                'child'                             => max($safe_search_data['data']['child_config']),
                                'max_pax'                           => ($room_type_data == false) ? '' : $room_type_data['max_pax'],
                                'extra_bed'                         => '',
                                'extra_bed_count'                   => '',
                                'hotel_room_rate_info_id'           => '',
                                'tax_rate_info_id'                  => '',
                                'TotalPrice'                        => $valid_temp_token['price'],
                                'breakfast_price'                   => '',
                                'lunch_price'                       => '',
                                'dinner_price'                      => '',
                                'breakfast_price_flag'              => '0',
                                'lunch_price_flag'                  => '0',
                                'dinner_price_flag'                 => '0',
                                'adult_price'                       => '',
                                'child_price_a'                     => '',
                                'child_price_b'                     => '',
                                'child_price_c'                     => '',
                                'child_price_d'                     => '',
                                'child_price_e'                     => '',
                                'sgl_price'                         => '',
                                'dbl_price'                         => '',
                                'tpl_price'                         => '',
                                'quad_price'                        => '',
                                'hex_price'                         => '',
                                'extra_bed_price'                   => '',
                                'extra_bed_price_total'             => '',
                                'ad_markup'                         => '',
                                'cancel_policy'                     => '',
                                'cancel_till_date'                  => '',
                                'cancel_amount'                     => '',
                                'purchase_token'                    => '',
                                'service_val'                       => '',
                                'cancel_date_from'                  => '',
                                'cancel_date_to'                    => '',
                                'comment_remarks'                   => '',
                                'special_request'                   => '',
                                'transfer_details'                  => '',
                                'flight_details'                    => '',
                                'arr_flight_name'                   => '',
                                'arr_flight_no'                     => '',
                                'arr_flight_date'                   => '',
                                'arr_flight_time'                   => '',
                                'dpt_flight_name'                   => '',
                                'dpt_flight_no'                     => '',
                                'dpt_flight_date'                   => '',
                                'dpt_flight_time'                   => '',
                                'child_age'                         => '',
                                'booking_secret_key'                => $book_id,
                                'booking_challenge_key'             => $search_id,
                                'amended_date'                      => '',
                                'service_charge'                    =>'',
                                'payment_type'                      => '',
                                'unit_type'							=> $post_params['unit_type'],
                                'street_type'						=> $post_params['street_type'],
                                'HotelAddress'                      => $valid_temp_token['HotelAddress']
                                                    );
            
debug($booking_hotel);exit();
                        $this->db->insert('booking_hotel',$booking_hotel);
                        debug($this->db->last_query());exit();
                        return $this->db->insert_id();
    }

    public function get_room_type_data($hotel_details_id,$hotel_room_type_id)
   {
        $q="SELECT * FROM hotel_room_type WHERE status='ACTIVE' AND hotel_room_type_id=".$hotel_room_type_id." AND hotel_details_id=".$hotel_details_id." ORDER BY hotel_room_type_id DESC LIMIT 1";
        return $this->db->query($q);
   }

   public function get_hotel_rooms_count_info($hotel_details_id,$room_type_id,$seasons_details_id)
   {
   $q=" SELECT ht.*,
        hi.hotel_season_room_count_info_id,hi.hotel_season_id,hi.hotel_room_count_info_id,
        hi.no_of_room,hi.no_of_room_available,hi.no_of_room_booked
        FROM 
            hotel_room_type ht
        LEFT JOIN 
            hotel_season_room_count_info hi ON(ht.hotel_room_type_id=hi.hotel_room_type_id) 
        WHERE 
            ht.hotel_room_type_id=".$room_type_id." AND ht.hotel_details_id=".$hotel_details_id."
        AND 
            hi.hotel_details_id=".$hotel_details_id." AND hi.hotel_details_id=".$hotel_details_id."
        AND 
            ht.status='ACTIVE' AND hi.status='ACTIVE' AND hi.hotel_season_id=".$seasons_details_id."
      ";

      //echo $q; exit();
      return $this->db->query($q);

   }

    public function hotel_add_bookingGlobalData($insert)
    {
        if($this->db->insert('booking_global', $insert)){
            return $this->db->insert_id() ;
        } else {
            return 0;
        }
    }

     public function Update_Booking_Global($booking_temp_id, $update_booking, $module){
        $this->db->where('id',$booking_temp_id);
        $this->db->where('module',$module);
        $this->db->update('booking_global', $update_booking);
    }

    public function getBookingDetails($parent_pnr){
        $this->db->select('*');
        $this->db->where('parent_pnr',$parent_pnr);
        $query = $this->db->get('booking_global');
        if($query->num_rows() > 0){
        return $query->result();
        }
    }

      public function getHotelDetailsId($id){
        $this->db->select('hotel_details_id,ref_id');
        $this->db->from('booking_global');
        $this->db->where('id',$id);
        $this->db->where('module','HOTEL');
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
        if($query->num_rows() > 0){
            return $query->row();
        }else{
            return false;
        }
    }

    public function getHotelRoomCount($id){
        $sql    = "select room_count,hotel_room_type_id,hotel_details_id from booking_hotel where id ='$id'";
        $query  = $this->db->query($sql);
        if($query->num_rows() > 0){
            return $query->row();
        }else{
            return 0;
        }
    }

    public function check_room_count_details($room_count,$hotel_room_type_id,$hotel_details_id){
        $sql = "SELECT * FROM hotel_season_room_count_info WHERE (hotel_room_type_id = '$hotel_room_type_id' AND hotel_details_id = '$hotel_details_id') ";
        $query  = $this->db->query($sql);
        //echo $this->db->last_query();exit;
        if($query->num_rows() > 0){
            return $query->row();
        }else{
            return 0;
        }
    }

    public function update_hotel_room_count_info($room_count,$hotel_room_type_id,$hotel_details_id){
        $sql = "UPDATE hotel_season_room_count_info SET no_of_room_booked = '$room_count' WHERE(hotel_room_type_id = '$hotel_room_type_id' AND hotel_details_id = '$hotel_details_id')";
      $this->db->query($sql);
    }

    public function update_globel_booking_status($id,$data){
        $this->db->set($data);
        $this->db->where('id',$id);
        $this->db->update('booking_global');
    }

    public function get_bookingHotel($id,$module){
        //echo $id;exit;
        $q = 'SELECT * FROM booking_hotel bh LEFT JOIN booking_global bg ON(bh.id=bg.ref_id) WHERE bg.id=\''.$id.'\'';
        
        if($module!='')
        {
           $q.=' AND bg.module=\''.$module.'\'';
        } //echo $q;exit;
        return $this->db->query($q);
    }

    function get_voucher_details($parent_pnr){
        $this->db->select('*');
        $this->db->from('booking_global');
        $this->db->join('booking_hotel', 'booking_hotel.id = booking_global.ref_id');
        $this->db->where('booking_global.parent_pnr',$parent_pnr);
        $this->db->where('booking_global.module','HOTEL');
        return $this->db->get()->result();
        //$this->db->where('')
    }
    	/**Promo code checking ***/
	function get_promo($promo){
		$query = "SELECT * FROM `promo_code_list` WHERE `promo_code` = '$promo' ";
		return($this->db->query($query));
	}
     
    public function booking_crs_details($condition){
     	$user_id  = $this->entity_user_id;
		$this->db->select('bg.*,bh.*');
        $this->db->from('booking_global bg');
        $this->db->join('booking_hotel bh','bg.ref_id=bh.id');
        $this->db->where('bg.user_id',$user_id);
        $this->db->where('bg.module','HOTEL');
        if(!empty($condition)){
        	$booking_reference = $condition['app_reference']; 
        	 $this->db->where('bg.parent_pnr',$booking_reference);
        }

        $query = $this->db->get();

            $new_array = ['status'=>0,'data'=>''];
        //echo $this->db->last_query();exit;
        if($query->num_rows() > 0){
        	$new_array2 = [];
            $response_array =  $query->result_array();
            $i=0;$j=0;
					
            foreach($response_array as $key => $val){
            	$new_array = ['status'=>1];
            	$request = json_decode($val['request'],true);
            	//debug($val);exit;
            	$passenger_details = json_decode($val['passenger_details'],true);
            	$billing_country  = $this->get_country_name( $passenger_details['billing_country']);

            	$hotel_id  = $passenger_details['hotel_id'];
            	$hotel_image = $this->get_hotel_crs_image($hotel_id);
            	$hotel_image = base_url().'/supervision/uploads/hotel_images/'.$hotel_image;

            	/*booking_details*/
				$new_array2['booking_details'][$i]['origin'] = $val['id'];
            	$new_array2['booking_details'][$i]['domain_origin'] = 1;
            	$new_array2['booking_details'][$i]['status'] = $val['booking_status'];
            	$new_array2['booking_details'][$i]['app_reference'] = $val['parent_pnr'];
            	$new_array2['booking_details'][$i]['booking_source'] = PROVAB_HOTEL_CRS_;
            	$new_array2['booking_details'][$i]['booking_id'] =  '';
            	$new_array2['booking_details'][$i]['booking_reference'] = $val['parent_pnr'];
            	$new_array2['booking_details'][$i]['confirmation_reference'] = $val['parent_pnr'];
            	$new_array2['booking_details'][$i]['hotel_name'] = $val['hotel_name'];
                $new_array2['booking_details'][$i]['star_rating'] = $request['StarRating']; 
                $new_array2['booking_details'][$i]['hotel_code'] = $val['hotel_code'];
                $new_array2['booking_details'][$i]['phone_number'] = $passenger_details['passenger_contact'];
                $new_array2['booking_details'][$i]['alternate_number'] = 'NA';
                $new_array2['booking_details'][$i]['email'] = $val['email_id'];
                $new_array2['booking_details'][$i]['hotel_check_in'] =  date('Y-m-d',strtotime($val['check_in_date']));
                $new_array2['booking_details'][$i]['hotel_check_out'] = date('Y-m-d',strtotime($val['check_out_date']));
                $new_array2['booking_details'][$i]['payment_mode'] = $val['payment_method'];
                $new_array2['booking_details'][$i]['convinence_value'] = $request['convenience_fees'];
                $new_array2['booking_details'][$i]['convinence_value_type'] = 'fixed';
                $new_array2['booking_details'][$i]['convinence_per_pax'] = 1;
                $new_array2['booking_details'][$i]['convinence_amount'] = $request['convenience_fees'];
                $new_array2['booking_details'][$i]['discount'] = 0;
                $new_array2['booking_details'][$i]['currency'] = $request['default_currency'];
                $new_array2['booking_details'][$i]['currency_conversion_rate'] = 1;
                $new_array2['booking_details'][$i]['attributes'] = json_encode(array("address"=>$passenger_details['billing_address_1'],"billing_country"=>$billing_country,'billing_city'=>$passenger_details['billing_city'],'billing_zipcode'=>$passenger_details['billing_zipcode'],'HotelCode'=>$request['HotelCode'],'search_id'=>$request['search_id'],'TraceId'=>$request['TraceId'],'HotelName'=>$request['HotelName'],'StarRating'=>$request['StarRating'],'HotelImage'=>$hotel_image,'HotelAddress'=>$request['HotelAddress'],'CancellationPolicy'=>$request['CancellationPolicy']));
                $new_array2['booking_details'][$i]['created_by_id'] = $val['user_id'];
                $new_array2['booking_details'][$i]['created_datetime'] = $val['voucher_date'];

                /*booking_itinerary_details*/
				$new_array2['booking_itinerary_details'][$i]['origin'] = $val['id'];
				$new_array2['booking_itinerary_details'][$i]['app_reference'] = $val['parent_pnr'];
				$new_array2['booking_itinerary_details'][$i]['location'] = '';
				$new_array2['booking_itinerary_details'][$i]['check_in'] = date('Y-m-d H:i:s',strtotime($val['check_in_date']));
				$new_array2['booking_itinerary_details'][$i]['check_out'] = date('Y-m-d H:i:s',strtotime($val['check_out_date']));
				$new_array2['booking_itinerary_details'][$i]['room_type_name'] = $val['room_type_name'];
				$new_array2['booking_itinerary_details'][$i]['bed_type_code'] = '';
				$new_array2['booking_itinerary_details'][$i]['smoking_preference'] = 0;
				$new_array2['booking_itinerary_details'][$i]['total_fare'] = $val['TotalPrice'];
				$new_array2['booking_itinerary_details'][$i]['admin_markup'] = 0;
				$new_array2['booking_itinerary_details'][$i]['agent_markup'] = 0;
				$new_array2['booking_itinerary_details'][$i]['currency'] = $val['payment_currency'];
				$new_array2['booking_itinerary_details'][$i]['attributes'] = '';
				$new_array2['booking_itinerary_details'][$i]['RoomPrice'] = 0;
				$new_array2['booking_itinerary_details'][$i]['Tax'] = 0;
				$new_array2['booking_itinerary_details'][$i]['ExtraGuestCharge'] = 0;
				$new_array2['booking_itinerary_details'][$i]['ChildCharge'] = 0;
				$new_array2['booking_itinerary_details'][$i]['OtherCharges'] = 0;
				$new_array2['booking_itinerary_details'][$i]['Discount'] = 0;
				$new_array2['booking_itinerary_details'][$i]['ServiceTax'] = 0;
				$new_array2['booking_itinerary_details'][$i]['AgentCommission'] = 0;
				$new_array2['booking_itinerary_details'][$i]['AgentMarkUp'] = 0;
				$new_array2['booking_itinerary_details'][$i]['TDS'] = 0;
				/*booking_customer_details*/
				foreach($passenger_details['first_name'] as $pk => $val2){
					$array_passenger_type = ["1"=>"Adult","2"=>"Child"];
					$array_passenger_title = ["1"=>"MR","2"=>"MS","3"=>"MISS","5"=>"MRS"];
					$raw_title =  $passenger_details['name_title'][$pk];
					$psngr_title = (isset($array_passenger_title[$raw_title])) ? $array_passenger_title[$raw_title] : '';
					$raw_type =  $passenger_details['passenger_type'][$pk];
					$psngr_type = (isset($array_passenger_type[$raw_type])) ? $array_passenger_type[$raw_type] : '';
					$new_array2['booking_customer_details'][$j] ['origin']= $val['id'];
					$new_array2['booking_customer_details'][$j] ['app_reference'] = $val['parent_pnr'];
					$new_array2['booking_customer_details'][$j]['title'] = $psngr_title;
					$new_array2['booking_customer_details'][$j]['first_name'] = $passenger_details['first_name'][$pk];
                    $new_array2['booking_customer_details'][$j]['middle_name'] = $passenger_details['middle_name'][$pk];
                    $new_array2['booking_customer_details'][$j]['last_name'] = $passenger_details['last_name'][$pk];
                    $new_array2['booking_customer_details'][$j]['phone'] =  $passenger_details['passenger_contact'];
                    $new_array2['booking_customer_details'][$j]['email'] =  $passenger_details['billing_email'];
                    $new_array2['booking_customer_details'][$j]['pax_type'] =  $psngr_type;
                    $new_array2['booking_customer_details'][$j]['date_of_birth'] = $passenger_details['date_of_birth'][$pk];
                    $new_array2['booking_customer_details'][$j]['passenger_nationality'] = $this->get_country_name($passenger_details['passenger_nationality'][$pk]);
                    $new_array2['booking_customer_details'][$j]['passport_number'] = $passenger_details['passenger_passport_number'][$pk];
                    $new_array2['booking_customer_details'][$j]['passport_issuing_country'] = $this->get_country_name($passenger_details['passenger_passport_issuing_country'][$pk]);
                    $new_array2['booking_customer_details'][$j]['passport_expiry_date'] = ($passenger_details['passenger_passport_expiry_year'][$pk].'-'.$passenger_details['passenger_passport_expiry_month'][$pk].'-'.$passenger_details['passenger_passport_expiry_day'][$pk]);
                    $new_array2['booking_customer_details'][$j]['status'] = $val['booking_status'];
                    $new_array2['booking_customer_details'][$j]['attributes'] = '';
                    $j++;
				}
				/*cancellation_details*/
				$new_array2['cancellation_details'] = [];/*
				$new_array2['cancellation_details'][0]['origin'] = 1;
				$new_array2['cancellation_details'][0]['origin'] = 1;
				$new_array2['cancellation_details'][0]['origin'] = 1;
				$new_array2['cancellation_details'][0]['origin'] = 1;
				$new_array2['cancellation_details'][0]['origin'] = 1;
				$new_array2['cancellation_details'][0]['origin'] = 1;*/

                $i++;
            }
            $new_array['data'] = $new_array2;
	return $new_array;
        }else{
            return $new_array;
        }
    }
    public function get_country_name($country_id){
    	$result = '';
		if($country_id!=''){
			$this->db->select('name');
	        $this->db->from('api_country_list');
	        $this->db->where('origin',$country_id);
	        $query = $this->db->get();
	        if($query->num_rows() > 0){
	            $data =  $query->row();
	            $result  = $data->name;
	    	}
	    }
	    return $result;
	}
	public function get_hotel_crs_image($hotel_id){
		$result = '';
		if($hotel_id!=''){
			$this->db->select('hotel_images');
	        $this->db->from('hotel_details');
	        $this->db->where('hotel_details_id',$hotel_id);
	        $query = $this->db->get();
	        if($query->num_rows() > 0){
	            $data =  $query->row();
	            $single_image = explode(',',$data->hotel_images);
	            if(!empty($single_image)){
	            	$result  = $single_image[0];	
	            }
	       }
	    }
	    return $result;
	}
	public function hotel_crs_cancel_request ($app_reference,$booking_source){
		$data = array('booking_status'=>'CANCELLED');
		$this->db->set($data);
        $this->db->where('parent_pnr',$app_reference);
        $this->db->update('booking_global');
        if( $this->db->affected_rows() > 0){
        	$result = ['status'=>1,"msg"=>"Booking Cancelled Successfully."];
        }else{
        	$result = ['status'=>0,"msg"=>"Cancel Request Sent Already."];
        }
        return $result;
	}
	public function format_crs_and_tbo($table_data,$crs_data){

		if(isset($table_data['status']) &&  $table_data['status']==1){

			$crs_data_raw = $crs_data['data'];
			foreach($crs_data_raw['booking_details'] as $key => $val){
				if(!empty($val)){
					array_push($table_data['data']['booking_details'],$val);	
				}
			}
			foreach($crs_data_raw['booking_itinerary_details'] as $key => $val){
				if(!empty($val)){
					array_push($table_data['data']['booking_itinerary_details'],$val);	
				}
			}
			foreach($crs_data_raw['booking_customer_details'] as $key => $val){
				if(!empty($val)){
					array_push($table_data['data']['booking_customer_details'],$val);	
				}
			}
			foreach($crs_data_raw['cancellation_details'] as $key => $val){
				if(!empty($val)){
					array_push($table_data['data']['cancellation_details'],$val);	
				}
			}
		}
		return $table_data;
	}
	public function hotel_crs_booking_count_user(){
		$user_id  = $this->entity_user_id;
		$this->db->select('bg.*');
        $this->db->from('booking_global bg');
        $this->db->where('bg.user_id',$user_id);
        $this->db->where('bg.module','HOTEL');
        $query = $this->db->get();
        $crs_count = $query->num_rows;
        return $crs_count;
	}

	public function get_hotel_amenities_name($amenityId)
   {
        $q="SELECT amenities_name FROM hotel_amenities WHERE hotel_amenities_id=".$amenityId;
        $am = $this->db->query($q)->row_array();
        return $am['amenities_name'];
   }
   public function get_hotel_amenities($amenityId='')
   {
   		// debug($amenityId);exit;
   		if(isset($amenityId) && !empty($amenityId))
   		{

		    $q="SELECT * FROM hotel_amenities WHERE hotel_amenities_id=".$amenityId;
		    $am = $this->db->query($q)->row_array();
   		}else{
   			return true;
   		}
        //echo $this->db->last_query();exit;
        //debug($am);
        return $am;
   }
   function get_hotel_list($search_data){
      // debug("xfhfgh");exit;

		
		 $q2 = "SELECT 
                    hd.hotel_id,hd.country_id,hd.city_id,hd.address_info,
                    hd.hotel_name,hd.hotel_code,hd.hotel_price,hd.minimum_days,hd.latitude,hd.longitude,
                    hd.maximum_days,hd.star_rating,hd.hotel_description,
                    hd.child_group_a,hd.child_group_b,hd.child_group_c,hd.child_group_d,hd.child_group_e

                FROM 
                    hotel_details hd
                WHERE 
                    hd.city_details_id='".$city_id."'
                  AND hd.hotel_type_id !='".VILLA."' 
                AND hd.status='1' 
                AND hd.hotel_id IN (
                    SELECT  
                        DISTINCT hotel_details_id 
                    FROM
                        `seasons_details` hsd 
                    WHERE 
                        hotel_details_id = hd.hotel_details_id 
                   
                     AND 
                        '".$checkin_date."' >= hsd.seasons_from_date AND '".$checkout_date."' <= hsd.seasons_to_date
                     AND 
                        ".$stay_days." >= hsd.minimum_stays AND hsd.status='ACTIVE'
                     AND hotel_room_type_id IN  
                        (
                            SELECT 
                                DISTINCT hrci.hotel_room_type_id 
                            FROM 
                                hotel_room_type ht 
                            LEFT JOIN
                                hotel_season_room_count_info hrci 
                                ON (
                                        (
                                            ht.hotel_room_type_id = hrci.hotel_room_type_id
                                        )
                                    AND 
                                        ht.hotel_details_id = hrci.hotel_details_id
                                   )
                            LEFT JOIN
                                 hotel_room_rate_info rate
                                 ON (
                                        rate.seasons_details_id = hrci.hotel_season_id
                                        AND 
                                        rate.hotel_details_id = hrci.hotel_details_id
                                        AND 
                                        rate.hotel_room_type_id = hrci.hotel_room_type_id
                                    )
                            WHERE 
                                ht.status='ACTIVE'
                            AND
                                hrci.status='ACTIVE' 
                            AND 
                                rate.hotel_room_rate_info_id IS NOT NULL    
                            AND
                                ht.hotel_details_id = hd.hotel_details_id
                            AND 
                                hrci.hotel_room_type_id IS NOT NULL
                            AND 
                                rate.seasons_details_id IS NOT NULL
                            AND 
                                (
                                    hrci.no_of_room_available > hrci.no_of_room_booked AND  (hrci.no_of_room_available - hrci.no_of_room_booked >= ".$room_count.")
                                )
                            
                            AND 
                                (  rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<=ht.adult AND ".$s_max_child."<=ht.child)
                                OR
                                (  rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<= ht.max_pax AND ".$s_max_child."<=ht.child AND ht.extra_bed ='Available')
                                OR
                                (  rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<= ht.max_pax AND ".$s_max_child."<=ht.child + 1 AND ht.extra_bed ='Available')
                                OR
                                (   rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<= ht.adult AND ".$s_max_child."<=ht.child + 1 AND ht.extra_bed ='Available')
                        )
                )
                ORDER BY hd.creation_date DESC";

               
        //$q2 = trim($q2);print_r($q2); exit();
        //echo $q2;exit;
        return $this->db->query($q2);
	}
	function get_trip_adv_not_exists(){

		// $items = array('1'=>1,'2'=>2,'7'=>7,'8'=>8,'9'=>9,'10'=>10,'11'=>11,'12'=>12,'15'=>15,'16'=>16,'18'=>18,'22'=>22,'25'=>25,'26'=>26,'40'=>40,'41'=>41,'42'=>42,'43'=>43,'44'=>44,'46'=>46);
		// $this->db->select('*');
		// $this->db->from('trip_advisor');
	 //    $this->db->where('hotel_code', array_rand($items));
		// $query = $this->db->get();
		// $num = $query->num_rows();
		// //echo $this->db->last_query(); exit;
		// if($num){
		// 	$data['result'] = $query->row_array();
		// 	$data['status'] = true;
		// }else{
		// 	$data['status'] = false;
		// }

		return false;
	}
	function b2c_crs_hotel_report($condition=array(), $count=false, $offset=0, $limit=100000000000)
	  {
	      //debug("dfg");exit;
	  	//debug($condition);exit;
		$condition = $this->custom_db->get_custom_condition($condition);
		// debug($condition);exit;

		//BT, CD, ID
		if ($count) {
			$query = 'select count(distinct(BG.ref_id)) as total_records
					from booking_global BG
					join booking_hotel AS BH on BG.ref_id=BH.id
					left join user as U on BG.user_id = U.user_id 
					where (U.user_type='.B2C_USER.' OR BG.user_type = 0)'.' '.$condition.'';
				//	 echo $query;die;
				debug($query);exit;
			$data = $this->db->query($query)->row_array();
			//echo $this->db->last_query();
			return $data['total_records'];
		} else {
			$this->load->library('booking_data_formatter');
			$response['status'] = SUCCESS_STATUS;
			$response['data'] = array();
			$booking_itinerary_details	= array();
			$booking_customer_details	= array();
			$cancellation_details = array();
			
			$bd_query = 'select BG.*,BH.*,HD.city_details_id as hotel_location,U.user_name,U.first_name,U.last_name,BG.ref_id as app_reference from booking_global AS BG 
			             join booking_hotel AS BH on BG.ref_id=BH.id 
			             join hotel_details AS HD on BG.hotel_details_id= HD.hotel_details_id  
					     left join user U on BG.user_id =U.user_id 					     
						 WHERE  (U.user_type='.B2C_USER.' OR BG.user_id = 0)'.$condition.'						 
						 order by BH.book_date desc, BH.id desc limit '.$offset.', '.$limit.'';
						// $this->db->last_query();exit;

			$booking_details = $this->db->query($bd_query)->result_array();
			$app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
			/*if(empty($app_reference_ids) == false) {
				$id_query = 'select * from hotel_booking_itinerary_details AS ID 
							WHERE ID.app_reference IN ('.$app_reference_ids.')';
				$cd_query = 'select * from hotel_booking_pax_details AS CD 
							WHERE CD.app_reference IN ('.$app_reference_ids.')';
				$cancellation_details_query = 'select * from hotel_cancellation_details AS HCD 
							WHERE HCD.app_reference IN ('.$app_reference_ids.')';
				$booking_itinerary_details	= $this->db->query($id_query)->result_array();
				$booking_customer_details	= $this->db->query($cd_query)->result_array();
				$cancellation_details	= $this->db->query($cancellation_details_query)->result_array();
			}*/
			$response['data']['booking_details']			= $booking_details;
			$response['data']['booking_itinerary_details']	= $booking_itinerary_details;
			$response['data']['booking_customer_details']	= $booking_customer_details;
			$response['data']['cancellation_details']	= $cancellation_details;
			return $response;
		}
	}
	function b2c_crs_hotel_report_info($condition=array(), $count=false, $offset=0, $limit=100000000000)
	  {
	  	// debug($condition);exit;
		$condition = $this->custom_db->get_custom_condition($condition);
		// debug($condition);exit;

		//BT, CD, ID
		if ($count) {
			$query = 'select count(distinct(BG.ref_id)) as total_records
					from booking_global BG
					join booking_hotel AS BH on BG.ref_id=BH.id
					left join user as U on BG.user_id = U.user_id 
					 join hotel_details AS HD on BG.hotel_details_id= HD.hotel_details_id 
					where 
					HD.hotel_type_id !='."'".VILLA."'".' AND
					(U.user_type='.B2C_USER.' OR BG.user_type = 0)'.' '.$condition.'';
					// echo $query;die;
			$data = $this->db->query($query)->row_array();
			//echo $this->db->last_query();
			return $data['total_records'];
		} else {
			$this->load->library('booking_data_formatter');
			$response['status'] = SUCCESS_STATUS;
			$response['data'] = array();
			$booking_itinerary_details	= array();
			$booking_customer_details	= array();
			$cancellation_details = array();
			
			$bd_query = 'select BG.*,BH.*,HD.city_details_id as hotel_location,U.user_name,U.first_name,U.last_name,BG.ref_id as app_reference from booking_global AS BG 
			             join booking_hotel AS BH on BG.ref_id=BH.id 
			             join hotel_details AS HD on BG.hotel_details_id= HD.hotel_details_id  
					     left join user U on BG.user_id =U.user_id 					     
						 WHERE  
							HD.hotel_type_id !='."'".VILLA."'".' AND
						 (U.user_type='.B2C_USER.' OR BG.user_id = 0)'.$condition.'						 
						 order by BH.book_date desc, BH.id desc limit '.$offset.', '.$limit.'';
						// $this->db->last_query();exit;

			$booking_details = $this->db->query($bd_query)->result_array();
			$app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
			/*if(empty($app_reference_ids) == false) {
				$id_query = 'select * from hotel_booking_itinerary_details AS ID 
							WHERE ID.app_reference IN ('.$app_reference_ids.')';
				$cd_query = 'select * from hotel_booking_pax_details AS CD 
							WHERE CD.app_reference IN ('.$app_reference_ids.')';
				$cancellation_details_query = 'select * from hotel_cancellation_details AS HCD 
							WHERE HCD.app_reference IN ('.$app_reference_ids.')';
				$booking_itinerary_details	= $this->db->query($id_query)->result_array();
				$booking_customer_details	= $this->db->query($cd_query)->result_array();
				$cancellation_details	= $this->db->query($cancellation_details_query)->result_array();
			}*/
			$response['data']['booking_details']			= $booking_details;
			$response['data']['booking_itinerary_details']	= $booking_itinerary_details;
			$response['data']['booking_customer_details']	= $booking_customer_details;
			$response['data']['cancellation_details']	= $cancellation_details;
			return $response;
		}
	}

	/**
	* Villa List
	**/
	function get_villa_search_data($datetime1, $datetime2,$stay_days,$s_max_adult,$s_max_child,$checkin_date,$checkout_date,$room_count,$city_id){
		 $q2 = "
                SELECT 
                    hd.hotel_details_id,hd.country_id,hd.city_details_id,hd.hotel_type_id,hd.hotel_address,
                    hd.hotel_name,hd.hotel_code,hd.hotel_price,currency_type,hd.min_stay_day,hd.hotel_amenities,hd.latitude,hd.longitude,
                    hd.max_stay_day,hd.star_rating,hd.hotel_info,hd.thumb_image,
                    hd.hotel_images,hd.hotel_image_url,
                    hd.child_group_a,hd.child_group_b,hd.child_group_c,hd.child_group_d,hd.child_group_e

                FROM 
                    hotel_details hd 
                WHERE 
                    hd.city_details_id like '%".$city_id."%'
                AND hd.hotel_type_id='".VILLA."' 
                AND hd.status='ACTIVE' 
                AND hd.contract_expire_date >= CURDATE()
                AND hd.hotel_details_id IN (
                    SELECT  
                        DISTINCT hotel_details_id 
                    FROM
                        `seasons_details` hsd 
                    WHERE 
                        hotel_details_id = hd.hotel_details_id 
                   
                     AND 
                        '".$checkin_date."' >= hsd.seasons_from_date AND '".$checkout_date."' <= hsd.seasons_to_date
                     AND '".$checkin_date."' >= '".Date('Y-m-d', strtotime("+3 days"))."'
                     AND 
                        ".$stay_days." >= hsd.minimum_stays AND hsd.status='ACTIVE'
                     AND hd.hotel_type_id = '31' 
                     AND hotel_room_type_id IN  
                        (
                            SELECT 
                                DISTINCT hrci.hotel_room_type_id 
                            FROM 
                                hotel_room_type ht 
                            LEFT JOIN
                                hotel_season_room_count_info hrci 
                                ON (
                                        (
                                            ht.hotel_room_type_id = hrci.hotel_room_type_id
                                        )
                                    AND 
                                        ht.hotel_details_id = hrci.hotel_details_id
                                   )
                            LEFT JOIN
                                 hotel_room_rate_info rate
                                 ON (
                                        rate.seasons_details_id = hrci.hotel_season_id
                                        AND 
                                        rate.hotel_details_id = hrci.hotel_details_id
                                        AND 
                                        rate.hotel_room_type_id = hrci.hotel_room_type_id
                                    )
                            WHERE 
                                ht.status='ACTIVE'
                            AND
                                hrci.status='ACTIVE' 
                            AND 
                                rate.hotel_room_rate_info_id IS NOT NULL    
                            AND
                                ht.hotel_details_id = hd.hotel_details_id
                            AND 
                                hrci.hotel_room_type_id IS NOT NULL
                            AND 
                                rate.seasons_details_id IS NOT NULL
                            AND 
                                (
                                    hrci.no_of_room_available > hrci.no_of_room_booked AND  (hrci.no_of_room_available - hrci.no_of_room_booked >= ".$room_count.")
                                )
                            
                            AND 
                                (  rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<=ht.adult AND ".$s_max_child."<=ht.child)
                                OR
                                (  rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<= ht.max_pax AND ".$s_max_child."<=ht.child AND ht.extra_bed ='Available')
                                OR
                                (  rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<= ht.max_pax AND ".$s_max_child."<=ht.child + 1 AND ht.extra_bed ='Available')
                                OR
                                (   rate.hotel_room_rate_info_id IS NOT NULL AND ht.hotel_details_id = hd.hotel_details_id AND ".$s_max_adult."<= ht.adult AND ".$s_max_child."<=ht.child + 1 AND ht.extra_bed ='Available')
                        )
                )
                ORDER BY hd.creation_date DESC";
               // debug($q2);exit;
        return $this->db->query($q2);
	}
	
function get_all_hotel_crs_list($hotel_id='', $hotel='')
	{		
	   // debug("fgfg");exit;
		$this->db->select('h.*,hrr.country_list_nationality_id');
		
		$this->db->from('hotel_details h');
		
		if($hotel_id !='')
			$this->db->where('hotel_details_id', $hotel_id);

		$this->db->join('hotel_room_rate_info hrri','h.hotel_details_id=hrri.hotel_details_id','left');
		$this->db->join('hotel_room_rate hrr','hrri.hotel_room_rate_info_id=hrr.hotel_rome_rate_info_id','left');
		$this->db->where('hrr.default_nationality','default');

		$this->db->limit(8);
		// $this->db->order_by("origin","desc");
		$query=$this->db->get();
	//	debug( $this->db->last_query());exit;
		if($query->num_rows() ==''){
			return '';
		}else{
			$data['all_hotels'] =  $query->result_array();
			
		}
		
		if($data['all_hotels'] != ''){
		   for($i=0; $i< count($data['all_hotels']); $i++) {
		   
		   }
		}
		//debug($data);exit;
		return $data;
	}


	function get_all_hotel_crs_list_old($hotel_id='', $hotel='')
	{	
	    //debug("sdfsdf");exit;
		$this->db->select('h.*');
		//$this->db->select('h.*,destination_name');
		$this->db->from('hotel_details h');
		//$this->db->join('destination_details ds','ds.destination_id = h.country_id');
		//$this->db->join('country_details c','h.country_id = c.country_id');
		//$this->db->join('city_details d','h.city_details_id = d.city_details_id');
		
		/*if($hotel['supplier_rights'] == 1) {
			$this->db->where('h.hotel_added_by_supplier', $hotel['admin_id'] );	
			}*/

		// $this->db->distinct('h.city_details_id');
		if($hotel_id !='')
			$this->db->where('hotel_details_id', $hotel_id);

		$this->db->limit(8);
		$this->db->order_by("hotel_details_id","desc");
		$query=$this->db->get();
		// echo $this->db->last_query();exit;
		if($query->num_rows() ==''){
			return '';
		}else{
			$data['all_hotels'] =  $query->result_array();
			// echo $this->db->last_query();exit;
		}
		// echo"<pre>";print_r($data['all_hotels']);exit;
		if($data['all_hotels'] != ''){
		   for($i=0; $i< count($data['all_hotels']); $i++) {
		   	// /echo $data['all_hotels'][$i]->hotel_details_id;exit;
			   //$data['offer_list'][$i] 	= $this->Seasonaloffers_Model->get_offers_list("","",$data['all_hotels'][$i]->hotel_details_id);
		   }
		}
		return $data;
	}


function b2c_villa_report($condition=array(), $count=false, $offset=0, $limit=100000000000)
	  {
	  	//debug($condition);exit;
		$condition = $this->custom_db->get_custom_condition($condition);
		
		$user_id=0;
		if(is_logged_in_user())
		{			
			$user_id=$this->entity_user_id;
			// $condition[]=array(
		 //    		'BG.user_id','=','"'.$user_id.'"'
		 //    );
		}
		$condition = $condition_static.$this->custom_db->get_custom_condition ( $condition );
			// debug($condition);exit;
		//BT, CD, ID
		if ($count) {
			$query = 'select count(distinct(BG.ref_id)) as total_records
					from booking_global BG
					join booking_hotel AS BH on BG.ref_id=BH.id
					 join hotel_details AS HD on BG.hotel_details_id= HD.hotel_details_id  
					left join user as U on BG.user_id = U.user_id 
					where BG.user_id ='."'".$user_id."'".' AND HD.hotel_type_id ='."'".VILLA."'".' AND (U.user_type='.B2C_USER.' OR BG.user_type = 0)'.' '.$condition.'';
					// debug($query);exit();
			$data = $this->db->query($query)->row_array();

			// debug($data);exit();
			return $data['total_records'];
		} else {
			$this->load->library('booking_data_formatter');
			$response['status'] = SUCCESS_STATUS;
			$response['data'] = array();
			$booking_itinerary_details	= array();
			$booking_customer_details	= array();
			$cancellation_details = array();
			
			$bd_query = 'select BG.*,BH.*,HD.city_details_id as hotel_location,U.email,U.first_name,U.last_name,BG.ref_id as app_reference from booking_global AS BG 
			             join booking_hotel AS BH on BG.ref_id=BH.id 
			             join hotel_details AS HD on BG.hotel_details_id= HD.hotel_details_id  
					     left join user U on BG.user_id =U.user_id 					     
						 WHERE BG.user_id ='."'".$user_id."'".' AND HD.hotel_type_id ='."'".VILLA."'".' AND (U.user_type='.B2C_USER.' OR BG.user_id = 0)'.$condition.'						 
						 order by BH.book_date desc, BH.id desc limit '.$offset.', '.$limit.'';

			$booking_details = $this->db->query($bd_query)->result_array();
			$app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
			/*if(empty($app_reference_ids) == false) {
				$id_query = 'select * from hotel_booking_itinerary_details AS ID 
							WHERE ID.app_reference IN ('.$app_reference_ids.')';
				$cd_query = 'select * from hotel_booking_pax_details AS CD 
							WHERE CD.app_reference IN ('.$app_reference_ids.')';
				$cancellation_details_query = 'select * from hotel_cancellation_details AS HCD 
							WHERE HCD.app_reference IN ('.$app_reference_ids.')';
				$booking_itinerary_details	= $this->db->query($id_query)->result_array();
				$booking_customer_details	= $this->db->query($cd_query)->result_array();
				$cancellation_details	= $this->db->query($cancellation_details_query)->result_array();
			}*/
			$response['data']['booking_details']			= $booking_details;
			$response['data']['booking_itinerary_details']	= $booking_itinerary_details;
			$response['data']['booking_customer_details']	= $booking_customer_details;
			$response['data']['cancellation_details']	= $cancellation_details;
			return $response;
		}
	}
	//Hotel-CRS
    function get_crs_data($search_data=array())
    {
    	$response['Status']=true;
    	//debug($search_data);die;
    	$amenities=$this->get_amenities();
    	$images=$this->get_images();
    	$hotel_list=array();
    	$this->db->select('
    						h.id,
    						h.country,
    						h.city,			
    						h.hotel_type_id,
    						h.hotel_name,
    						h.star_rating,
    						h.hotel_description,
    						h.amenities,
    						h.hotel_address,
    						h.postal_code,
    						h.phone_number,
    						h.image,
    						h.email,
    						h.lattitude,
    						h.longtitude,
    						s.id As price_id
    					');
    	$this->db->from('crs_hotel_details h');
    	$this->db->join('seasons_details c','h.id=c.hotel_details_id');
    	$this->db->join('crs_room_price s','c.seasons_details_id=s.season and s.hotel_id=h.id');
        $this->db->where('h.status','ACTIVE');
        $this->db->where('s.status','ACTIVE');
    	$this->db->where('h.city',$search_data['data']['city_name']);

    	$this->db->where('c.seasons_to_date >=', $search_data['data']['to_date']);
		$this->db->where('c.seasons_from_date <=', $search_data['data']['from_date']);
		$this->db->group_by('s.hotel_id');
    	$query=$this->db->get();
    // echo $this->db->last_query();die();
     
     
     
    	if($query->num_rows()>0)
    	{
    		$hotel_data=array();
    		foreach ($query->result() as $key => $value) 
    		{
				if($this->get_crs_hotel_rooms_datacount($value->id,$search_data))
				{
				
    			$RoomPrice= $this->get_first_room_search_price($value->id,$value->price_id,$search_data);
    		// debug($RoomPrice);die;
    			if ($RoomPrice==false) {
    				continue;
    			}

    			$room_amenities=array();
    			$hotel_amenities=explode(',', $value->amenities);
    			foreach ($hotel_amenities as $k => $amenity) 
    			{
    					$room_amenities[]=@$amenities[$amenity];
    			}
    					//debug($value);die;
    			$server_name = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'];
    			$hotel_data['ResultIndex']='CRS'.$value->id;
    			$hotel_data['HotelCode']=$value->id;
    			$hotel_data['HotelName']=$value->hotel_name;
    			$hotel_data['HotelCategory']=$value->hotel_type_id;
    			$hotel_data['StarRating']=$value->star_rating;
    			$hotel_data['HotelDescription']=$value->hotel_description;
    			$hotel_data['HotelPromotion']='';
    			$hotel_data['HotelPolicy']='';
    			$hotel_data['Price']=$RoomPrice;
    		//	$hotel_data['HotelPicture']=$images[$value->id];
    			$hotel_data['HotelPicture']=$server_name.DOMAIN_HOTEL_IMAGE_DIR.$value->image;
    			$hotel_data['HotelAddress']=$value->hotel_address;
    			$hotel_data['HotelContactNo']=$value->phone_number;
    			$hotel_data['HotelMap']='';
    			$hotel_data['Latitude']=$value->lattitude;
    			$hotel_data['Longitude']=$value->longtitude;
    			$hotel_data['HotelLocation']=$value->city;
    			$hotel_data['SupplierPrice']='';
    			$hotel_data['RoomDetails']=array();
    			$hotel_data['OrginalHotelCode']=$value->id;
    			$hotel_data['HotelPromotionContent']='';
    			$hotel_data['PhoneNumber']=$value->phone_number;
    			$hotel_data['HotelAmenities']=$room_amenities;
    			$hotel_data['Free_cancel_date']='';
    			$hotel_data['trip_adv_url']='';
    			$hotel_data['trip_rating']='';
    			$hotel_data['ResultToken']=$value->id.'-'.$value->price_id.'-'.$RoomPrice['RoomPriceWoGST'];
    			$hotel_list[]=$hotel_data;  
			}			
			
			if($_SERVER['REMOTE_ADDR']=="106.203.7.126")
			{
			//	debug($hotel_list);die;	
			}
			
    	//	debug($hotel_list);die;			
    		}
    	}
    	else
    	{
    		// echo $this->db->last_query();die;
    		$response['Status']=false;
    	}
    //	echo $this->db->last_query();
    //	 debug($hotel_list);die;
    	$response['Search']['HotelSearchResult']['HotelResults']=$hotel_list;
    	return $response;
	}
		public function get_first_room_search_price($hotel_code=0,$season_id=0,$search_data=array(),$details=false)
	{
	    $sql = "SELECT *
FROM all_nationality_country
WHERE find_in_set('".$search_data['data']['nationality']."',all_nationality_country.include_countryCodes) and module='hotel'";
   $query = $this->db->query($sql);
    $natprice = $query->result();
		$q3=$this->db->get_where('crs_room_price',array('hotel_id'=>$hotel_code,'nationality'=>$natprice[0]->name));
		
		if($q3->num_rows()<1)
		{
			return	false;
		}
		$roomprice = $q3->row();
		
//debug($roomprice);'id'=>$season_id,

  
		/*	$n3=$this->db->get_where('all_nationality_country',array('name'=>$roomprice->nationality,'module'=>'hotel'));
		$natprice = $n3->row();*/
		$currency_obj = new Currency(array('module_type' => 'hotel', 'from' =>$natprice[0]->currency, 'to' => get_application_currency_preference()));
		
	//	debug($currency_obj);die;
	//	debug($this->db->last_query());
	$nps=explode(',',$natprice->include_countryCodes);

		$adult_price=0;
		$child_price=0;
	
if(true)
{
		$RoomPrice=0;
		foreach ($search_data['data']['adult_config']	 as $a => $adt) 
		{
			switch ($adt) {
				case 1:
					$adult_price+= get_converted_currency_value($currency_obj->force_currency_conversion($roomprice->one_adult));
				break;
				case 2:
					$adult_price+= get_converted_currency_value($currency_obj->force_currency_conversion($roomprice->two_adult));
				break;
				case 3:
					$adult_price+= get_converted_currency_value($currency_obj->force_currency_conversion($roomprice->three_adult));
				break;
				case 4:
					$adult_price+= get_converted_currency_value($currency_obj->force_currency_conversion(($roomprice->three_adult+$roomprice->extrabed_price)));
				break;				
				default:
					$adult_price+=0;
				break;
			}
		}
		
		
		
		for($i=0;$i<count($search_data['data']['child_config']);$i++) 
		{
			
			switch ($search_data['data']['child_config'][$i]) {
				case 1:
				    if($search_data['data']['child_age'][$i]<=2)
				    {
				        $child_price+= get_converted_currency_value($currency_obj->force_currency_conversion($roomprice->infant_price*1));
				    }
				    else
				    {
				     	$child_price+= get_converted_currency_value($currency_obj->force_currency_conversion($roomprice->child_price*1));
				    }
				break;
				case 2:
					if($search_data['data']['child_age'][$i]<=2)
				    {
				        $child_price+= get_converted_currency_value($currency_obj->force_currency_conversion($roomprice->infant_price*2));
				    }
				    else
				    {
				     	$child_price+= get_converted_currency_value($currency_obj->force_currency_conversion($roomprice->child_price*2));
				    }
				break;
				default:
					$child_price+=0;
				break;
			}
		}
//	debug($search_data['data']['no_of_nights']);die;
		// debug($child_price);die;
		// $RoomPrice=($roomprice->one_adult*$s_max_adult)+($roomprice->room_child_price_b*$s_max_child);
		$RoomPrice = ($adult_price+$child_price);
		//debug($RoomPrice);die;
		$price=array();
		$price['TBO_RoomPrice'] =$RoomPrice;
		$price['TBO_OfferedPriceRoundedOff'] =$RoomPrice;
		$price['TBO_PublishedPrice'] =$RoomPrice;
		$price['TBO_PublishedPriceRoundedOff'] =$RoomPrice;
		$price['Tax'] =0;
		$price['ExtraGuestCharge'] =0;
		$price['ChildCharge'] =0;
		$price['OtherCharges'] =0;
		$price['Discount'] =0;
		$price['PublishedPrice'] =$RoomPrice;
		$price['RoomPrice'] =$RoomPrice;
		$price['PublishedPriceRoundedOff'] =$RoomPrice;
		$price['OfferedPrice'] =$RoomPrice;
		$price['OfferedPriceRoundedOff'] =$RoomPrice;
		$price['AgentCommission'] =0;
		$price['AgentMarkUp'] =0;
		$price['ServiceTax'] =0;
		$price['TDS'] =0;
		$price['ServiceCharge'] =0;
		$price['TotalGSTAmount'] =0;
		$price['RoomPriceWoGST'] =$roomprice->room_id;
		$price['GSTPrice'] =0;
		$price['CurrencyCode'] =$natprice->currency;
		$data['Price']=$price;
		$data['room_name']='First Room';
		$data['Room_data']=array(	'RoomUniqueId'=>$roomprice->room_id,
									'rate_key'=>$roomprice->id,
									'group_code'=>$roomprice->room_id);
	if($details==false)
		{
			return $price;
		}
		return $data;
		
}
else
{
return false;
}
	}
public function get_first_room_price($hotel_code=0,$season_id=0,$search_data=array(),$details=false)
	{
	    $sql = "SELECT *
FROM all_nationality_country
WHERE find_in_set('".$search_data['data']['nationality']."',all_nationality_country.include_countryCodes) and module='hotel'";
   $query = $this->db->query($sql);
    $natprice = $query->result();
		$q3=$this->db->get_where('crs_room_price',array('hotel_id'=>$hotel_code,'nationality'=>$natprice[0]->name));
		
		if($q3->num_rows()<1)
		{
			return	false;
		}
		$roomprice = $q3->row();
		
//debug($roomprice);'id'=>$season_id,

  
		/*	$n3=$this->db->get_where('all_nationality_country',array('name'=>$roomprice->nationality,'module'=>'hotel'));
		$natprice = $n3->row();*/
		$currency_obj = new Currency(array('module_type' => 'hotel', 'from' =>$natprice[0]->currency, 'to' => get_application_currency_preference()));
		
	//	debug($currency_obj);die;
	//	debug($this->db->last_query());
	$nps=explode(',',$natprice->include_countryCodes);

		$adult_price=0;
		$child_price=0;
	
if(true)
{
		$RoomPrice=0;
		foreach ($search_data['data']['adult_config']	 as $a => $adt) 
		{
			switch ($adt) {
				case 1:
					$adult_price+= get_converted_currency_value($currency_obj->force_currency_conversion($roomprice->one_adult));
				break;
				case 2:
					$adult_price+= get_converted_currency_value($currency_obj->force_currency_conversion($roomprice->two_adult));
				break;
				case 3:
					$adult_price+= get_converted_currency_value($currency_obj->force_currency_conversion($roomprice->three_adult));
				break;
				case 4:
					$adult_price+= get_converted_currency_value($currency_obj->force_currency_conversion(($roomprice->three_adult+$roomprice->extrabed_price)));
				break;				
				default:
					$adult_price+=0;
				break;
			}
		}
		
		
		
		for($i=0;$i<count($search_data['data']['child_config']);$i++) 
		{
			
			switch ($search_data['data']['child_config'][$i]) {
				case 1:
				    if($search_data['data']['child_age'][$i]<=2)
				    {
				        $child_price+= get_converted_currency_value($currency_obj->force_currency_conversion($roomprice->infant_price*1));
				    }
				    else
				    {
				     	$child_price+= get_converted_currency_value($currency_obj->force_currency_conversion($roomprice->child_price*1));
				    }
				break;
				case 2:
					if($search_data['data']['child_age'][$i]<=2)
				    {
				        $child_price+= get_converted_currency_value($currency_obj->force_currency_conversion($roomprice->infant_price*2));
				    }
				    else
				    {
				     	$child_price+= get_converted_currency_value($currency_obj->force_currency_conversion($roomprice->child_price*2));
				    }
				break;
				default:
					$child_price+=0;
				break;
			}
		}
//	debug($search_data['data']['no_of_nights']);die;
		// debug($child_price);die;
		// $RoomPrice=($roomprice->one_adult*$s_max_adult)+($roomprice->room_child_price_b*$s_max_child);
		$RoomPrice = ($adult_price+$child_price)*$search_data['data']['no_of_nights'];
		//debug($RoomPrice);die;
		$price=array();
		$price['TBO_RoomPrice'] =$RoomPrice;
		$price['TBO_OfferedPriceRoundedOff'] =$RoomPrice;
		$price['TBO_PublishedPrice'] =$RoomPrice;
		$price['TBO_PublishedPriceRoundedOff'] =$RoomPrice;
		$price['Tax'] =0;
		$price['ExtraGuestCharge'] =0;
		$price['ChildCharge'] =0;
		$price['OtherCharges'] =0;
		$price['Discount'] =0;
		$price['PublishedPrice'] =$RoomPrice;
		$price['RoomPrice'] =$RoomPrice;
		$price['PublishedPriceRoundedOff'] =$RoomPrice;
		$price['OfferedPrice'] =$RoomPrice;
		$price['OfferedPriceRoundedOff'] =$RoomPrice;
		$price['AgentCommission'] =0;
		$price['AgentMarkUp'] =0;
		$price['ServiceTax'] =0;
		$price['TDS'] =0;
		$price['ServiceCharge'] =0;
		$price['TotalGSTAmount'] =0;
		$price['RoomPriceWoGST'] =$roomprice->room_id;
		$price['GSTPrice'] =0;
		$price['CurrencyCode'] =$natprice->currency;
		$data['Price']=$price;
		$data['room_name']='First Room';
		$data['Room_data']=array(	'RoomUniqueId'=>$roomprice->room_id,
									'rate_key'=>$roomprice->id,
									'group_code'=>$roomprice->room_id);
	if($details==false)
		{
			return $price;
		}
		return $data;
		
}
else
{
return false;
}
	}


	public function get_amenities()
	{
		$amenities=array();
		$data=$this->db->get('crs_hotel_amenities')->result();	
		foreach ($data as $key => $value) 
		{
			$amenities[$value->id]=$value->name;
		}
		return $amenities;
	}
	public function get_allowed()
	{
		$amenities=array();
		$data=$this->db->get('hotel_allowed')->result();	
		foreach ($data as $key => $value) 
		{
			$amenities[$value->allowID]=$value->name;
		}
		return $amenities;
	}
	public function get_room_amenities()
	{
		$amenities=array();
		$data=$this->db->get('crs_room_amenities')->result();	
		foreach ($data as $key => $value) 
		{
			$amenities[$value->id]=$value->name;
		}
		return $amenities;
	}
	public function get_images()
	{
		$server_name = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'];	
		$images=array();
		$this->db->select('hotel_id,image');
		$this->db->group_by('hotel_id');
		$data=$this->db->get('crs_hotel_images')->result();	
		// debug($data);die;
		foreach ($data as $key => $value) 
		{
			$images[$value->hotel_id]=$server_name.DOMAIN_HOTEL_IMAGE_DIR.$value->image;
		}
		return $images;
	}
	public function get_hotel_images($hotel_id)
	{
		$server_name = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'];
		$images=array();
		$this->db->select('hotel_id,image');
		$this->db->where('hotel_id',$hotel_id);
		$data=$this->db->get('crs_hotel_images')->result();	
		// debug($data);die;
		foreach ($data as $key => $value) 
		{
			$images[]=$server_name.DOMAIN_HOTEL_IMAGE_DIR.$value->image;
		}
		return $images;
	}
	//Hotel-CRS
     function get_crs_hotel_data($ResultIndex,$search_data=array())
    {
    	$input_data=explode('-',$ResultIndex);
    	$response['Status']=true;
    	$amenities=$this->get_amenities();
    	$allowed=$this->get_allowed();
    	$images=$this->get_hotel_images($input_data[0]);
    	// debug($images);die;
    	$hotel_list=array();
    	$this->db->select('*');
    	$this->db->from('crs_hotel_details');
    	$this->db->where('id',$input_data[0]);
    	$query=$this->db->get();
    	if($query->num_rows()>0)
    	{
			$value=  $query->row();  		
    		$hotel_data=array();
  // debug($value);die;
    			$room_amenities=array();
    			$hotel_amenities=explode(',', $value->amenities);
    			foreach ($hotel_amenities as $k => $amenity) 
    			{
    					$room_amenities[]=$amenities[$amenity];
    			}
    			$allowed_list=array();
    			$allowed_list_arr=explode(',', $value->allowed);
    		
    			foreach ($allowed_list_arr as $k => $amenity) 
    			{
    					$allowed_list[]=$allowed[$amenity];
    			}
    		
    			$notallowed_list=array();
    			$notallowed_list_arr=explode(',', $value->not_allowed);
    			foreach ($notallowed_list_arr as $k => $amenity) 
    			{
    					$notallowed_list[]=$allowed[$amenity];
    			}
    			
    			$RoomPrice= $this->get_first_room_price($value->id,$input_data[1],$search_data,true);
    			// debug($RoomPrice);die;
    			$hotel_data['HotelCode']=$value->id;
    			$hotel_data['HotelName']=$value->hotel_name;
    			$hotel_data['StarRating']=$value->star_rating;
    			$hotel_data['HotelURL']='';
    			$hotel_data['Description']=$value->hotel_description;
    			$hotel_data['Attractions']=array();
    			$hotel_data['HotelFacilities']=$room_amenities;
    			$hotel_data['allowed_list']=$allowed_list;
    			$hotel_data['not_allowed']=$notallowed_list;
    			$hotel_data['HotelPolicy']='';
    			$hotel_data['SpecialInstructions']='';
    			$hotel_data['HotelPicture']=$images[0];
    			$hotel_data['Images']=$images;
    			$hotel_data['Address']=$value->hotel_address;
    			$hotel_data['CountryName']=$value->country;
    			$hotel_data['PinCode']=$value->postal_code;
    			$hotel_data['HotelContactNo']=$value->phone_number;
    			$hotel_data['FaxNumber']=$value->fax_number;
    			$hotel_data['allowed']=$value->phone_number;
    			$hotel_data['FaxNumber']=$value->fax_number;
    			$hotel_data['Email']=$value->email;
    			$hotel_data['Latitude']=$value->lattitude;
    			$hotel_data['Longitude']=$value->longtitude;
    			$hotel_data['RoomData']='';
    			$hotel_data['RoomFacilities']='';
    			$hotel_data['Services']='';
    			$hotel_data['checkin']=$search_data['data']['from_date'];
    			$hotel_data['checkout']=$search_data['data']['to_date'];
					$canelation=$this->get_room_cancellation_policy2($value->id,$search_data);
//	debug($hotel_data);die;

    			$hotel_data['first_room_details']=$RoomPrice;
				if(isset($canelation[1]['FromDate']))
				{
						$hotel_data['first_rm_cancel_date']=$canelation[1]['FromDate'];
				}
				else
				{
						$hotel_data['first_rm_cancel_date']=$canelation[0]['FromDate'];
				}
    		
    			$hotel_data['Amenities']=$room_amenities;
    			$hotel_data['trip_adv_url']='';
    			$hotel_data['trip_rating']='';
    			// $hotel_data['booking_source']=CRS_HOTEL_BOOKING_SOURCE;
    			$hotel_list['HotelInfoResult']['HotelDetails']=$hotel_data;    			
    		
    	}
    	else
    	{
    		$response['Status']=false;
    	}
    	$response['Message']='';
    	$response['HotelDetails']=$hotel_list;
    	
    	return $response;
	}
	 //Hotel-CRS
    function get_crs_hotel_rooms_datacount($hotelid,$search_data=array())
    {
		$response['Status']=true;
    	$amenities=$this->get_room_amenities();
    	// $ChildCount = max(search_data)
    	// debug($search_data);die;
    	$hotel_list=array();
    	$hotel_combination=array();
    	$this->db->select('a.*,b.name as room_name,c.id as price_id');
    	$this->db->from('crs_room_details a');
    	$this->db->join('crs_room_type b','b.id=a.room_type_id');
    	$this->db->join('crs_room_price c','c.room_id=a.id');
    	$this->db->from('seasons_details s','s.seasons_details_id=c.season');
    	$this->db->where('a.hotel_id',$hotelid);
        $this->db->where('a.status','ACTIVE');
       // $this->db->where('c.nationality',$search_data['data']['nationality']);
    	$this->db->where('s.seasons_to_date >=', $search_data['data']['to_date']);
		$this->db->where('s.seasons_from_date <=', $search_data['data']['from_date']);
		$this->db->group_by('a.id');
    	$query=$this->db->get();
    	// echo $this->db->last_query();die;

    	if($query->num_rows()>0)
    	{
			return true;
		}
		else
		{
			return false;
		}
	}
     function get_crs_hotel_rooms_data($ResultIndex,$search_data=array())
    {


    	  $sql = "SELECT *
FROM all_nationality_country
WHERE find_in_set('".$search_data['data']['nationality']."',all_nationality_country.include_countryCodes) and module='hotel'";
   $nquery = $this->db->query($sql);
    $natprice = $nquery->result();
    	$input_data=explode('-',$ResultIndex);
    	$response['Status']=true;
    	$amenities=$this->get_room_amenities();
    	$hotel_list=array();
    	$hotel_combination=array();
    	$this->db->select('a.*,b.name as room_name,c.id as price_id');
    	$this->db->from('crs_room_details a');
    	$this->db->join('crs_room_type b','b.id=a.room_type_id');
    	$this->db->join('crs_room_price c','c.room_id=a.id');
    	$this->db->from('seasons_details s','s.seasons_details_id=c.season');
    	$this->db->where('a.hotel_id',$input_data[0]);
        $this->db->where('a.status','ACTIVE');
       $this->db->where('c.nationality',$natprice[0]->name);
    	$this->db->where('s.seasons_to_date >=', $search_data['data']['to_date']);
		$this->db->where('s.seasons_from_date <=', $search_data['data']['from_date']);
		$this->db->group_by('a.id');
    	$query=$this->db->get();
    	
   
   
    	if($query->num_rows()>0)
    	{
   // 	 debug($query->result());die;
    		foreach ($query->result() as $key => $value) 
    		{
    			$hotel_data=array();
    			$room_amenities=array();
    			$HotelRoomsDetails=array();
    			$hotel_amenities=explode(',', $value->room_amenities);
    			foreach ($hotel_amenities as $k => $amenity) 
    			{
    					$room_amenities[]=$amenities[$amenity];
    			}
    			$hotel_data['RoomIndex']=$key;
    			$hotel_data['ChildCount']=0;
    			$hotel_data['RoomTypeName']=$value->room_name;
    			$hotel_data['Price']=$this->get_room_price($value->hotel_id,$value->price_id,$search_data);
    			$hotel_data['SmokingPreference']='';
    			$hotel_data['RatePlanCode']=$value->price_id;
    			$hotel_data['RoomTypeCode']=$value->price_id;
    			$hotel_data['Amenities']=$room_amenities;
    			$hotel_data['OtherAmennities']=array();
    			$hotel_data['room_only']='room only';
    			$hotel_data['cancellation_policy_code']='';
    		//	$hotel_data['LastCancellationDate']='2020-09-12';
				$hotel_data['CancellationPolicies']=$canelation=$this->get_room_cancellation_policy($value->id,$search_data);

$hotel_data['LastCancellationDate']=$canelation[0]['FromDate'];
    			//$hotel_data['CancellationPolicies']=$this->get_room_cancellation_policy($value->id,$search_data);
    			//$hotel_data['CancellationPolicy']='';
    			$hotel_data['rate_key']=$value->price_id;
    			$hotel_data['group_code']=$value->price_id;
    			$hotel_data['room_code']=$value->id;
    			$hotel_data['HOTEL_CODE']=$value->hotel_id;
    			$hotel_data['SEARCH_ID']=$value->price_id;
    			$hotel_data['RoomUniqueId']=$value->price_id;
    			if($hotel_data['Price']!="")
    			{
    			$hotel_list[]=$hotel_data; 
    			}
    			$hotel_combination[]=array('RoomIndex'=>array($key));    			
    		}	
    	}
    	else
    	{
    		$response['Status']=false;
    	}
    	$response['Message']='';
    	$response['RoomList']['GetHotelRoomResult']['HotelRoomsDetails']=$hotel_list;
    	$response['RoomList']['GetHotelRoomResult']['RoomCombinations']=array(
    																	'InfoSource'=>'FixedCombination',
    																	'IsPolicyPerStay'=>'',
    																	'RoomCombination'=>$hotel_combination);
    	//debug($response);die;
    	return $response;
    	// debug($hotel_list);die;
	}
		public function get_room_price($hotel_code=0,$price_id=0,$search_data=array())
	{
 //debug($search_data);die;
		  $sql = "SELECT *
FROM all_nationality_country
WHERE find_in_set('".$search_data['data']['nationality']."',all_nationality_country.include_countryCodes) and module='hotel'";
   $query = $this->db->query($sql);
    $natprice = $query->result();
	//	$q3=$this->db->get_where('crs_room_price',array('id'=>$season_id,'nationality'=>$natprice[0]->name));
		
	$q3=$this->db->get_where('crs_room_price',array('id'=>$price_id,'nationality'=>$natprice[0]->name));
	
	
	//debug($this->db->last_query());
		if($q3->num_rows()<1)
		{
//return	false;
		}
		$roomprice = $q3->row();
	//	debug($hotel_code);
	//	debug($price_id);
	//	debug($roomprice);die;


	$nps=explode(',',$natprice[0]->include_countryCodes);
	  $currency_obj = new Currency(array('module_type' => 'hotel', 'from' =>$natprice[0]->currency, 'to' => get_application_currency_preference()));
if(true)
{
		$adult_price=0;
		$child_price=0;
		$RoomPrice=0;
		foreach ($search_data['data']['adult_config']	 as $a => $adt) 
		{
			switch ($adt) {
				case 1:
					$adult_price+=get_converted_currency_value($currency_obj->force_currency_conversion($roomprice->one_adult));
				break;
				case 2:
					$adult_price+=get_converted_currency_value($currency_obj->force_currency_conversion($roomprice->two_adult));
				break;
				case 3:
					$adult_price+=get_converted_currency_value($currency_obj->force_currency_conversion($roomprice->three_adult));
				break;
				case 4:
					$adult_price+=get_converted_currency_value($currency_obj->force_currency_conversion($roomprice->four_adult));
				break;				
				default:
					$adult_price+=0;
				break;
			}
		}
		/*	foreach ($search_data['data']['adult_config']	 as $a => $adt) 
		{
			switch ($adt) {
				case 1:
					$adult_price+=$roomprice->one_adult;
				break;
				case 2:
					$adult_price+=$roomprice->two_adult;
				break;
				case 3:
					$adult_price+=$roomprice->three_adult;
				break;
				case 4:
					$adult_price+=($roomprice->three_adult+$roomprice->extrabed_price);
				break;				
				default:
					$adult_price+=0;
				break;
			}
		}*/
	//	debug($search_data['data']);die;
		for($i=0;$i<count($search_data['data']['child_config']);$i++) 
		{
			
			switch ($search_data['data']['child_config'][$i]) {
				case 1:
				    if($search_data['data']['child_age'][$i]<=2)
				    {
				        $child_price+=get_converted_currency_value($currency_obj->force_currency_conversion($roomprice->infant_price*1));
				    }
				    else
				    {
				     	$child_price+=get_converted_currency_value($currency_obj->force_currency_conversion($roomprice->child_price*1));
				    }
				break;
				case 2:
					if($search_data['data']['child_age'][$i]<=2)
				    {
				        $child_price+=get_converted_currency_value($currency_obj->force_currency_conversion($roomprice->infant_price*2));
				    }
				    else
				    {
				     	$child_price+=get_converted_currency_value($currency_obj->force_currency_conversion($roomprice->child_price*2));
				    }
				break;
				default:
					$child_price+=0;
				break;
			}
		}
				
		// $RoomPrice=($roomprice->one_adult*$s_max_adult)+($roomprice->room_child_price_b*$s_max_child);
		$RoomPrice = ($adult_price+$child_price)*$search_data['data']['no_of_nights'];
		
		$price=array();
		$price['TBO_RoomPrice'] =$RoomPrice;
		$price['TBO_OfferedPriceRoundedOff'] =$RoomPrice;
		$price['TBO_PublishedPrice'] =$RoomPrice;
		$price['TBO_PublishedPriceRoundedOff'] =$RoomPrice;
		$price['Tax'] =0;
		$price['ExtraGuestCharge'] =0;
		$price['ChildCharge'] =0;
		$price['OtherCharges'] =0;
		$price['Discount'] =0;
		$price['PublishedPrice'] =$RoomPrice;
		$price['RoomPrice'] =$RoomPrice;
		$price['PublishedPriceRoundedOff'] =$RoomPrice;
		$price['OfferedPrice'] =$RoomPrice;
		$price['OfferedPriceRoundedOff'] =$RoomPrice;
		$price['AgentCommission'] =0;
		$price['AgentMarkUp'] =0;
		$price['ServiceTax'] =0;
		$price['TDS'] =0;
		$price['ServiceCharge'] =0;
		$price['TotalGSTAmount'] =0;
		$price['RoomPriceWoGST'] =0;
		$price['GSTPrice'] =0;
		$price['CurrencyCode'] =$natprice->currency;
		return $price;
}
else
{
    return false;
}
	}
	
	public function get_room_cancellation_policy2($roomid='',$search_data=array())
	{

		$days =get_date_difference(date('Y-m-d'),$search_data['data']['from_date']);
		$q3=$this->db->get_where('crs_cancellation_policy',array('hotel_id'=>$roomid,'status'=>'ACTIVE'));
		if($q3->num_rows()<1)
		{
			return	array();
		}
		$data=array();
		//debug($this->db->last_query());die;
		foreach ($q3->result() as $key => $value) 
		{
			
			
			//$cancel_date = add_days($value->cancel_before);
			$cancel_date = date('Y-m-d', strtotime($search_data['data']['from_date'].' -'.$value->cancel_before.' day'));
			$cancel_date2 = date('Y-m-d', strtotime($search_data['data']['to_date'].' -'.$value->cancel_to.' day'));
			$data[]=array(
					'Charge'=>$value->penality,
					'ChargeType'=>2,
							'FromDate'=>date('Y-m-d', strtotime($search_data['data']['from_date'] . '-'.$value->cancel_before.'days')),
					'ToDate'=>date('Y-m-d', strtotime($search_data['data']['from_date'] . " -1 days")),
							'fdays'=>$value->cancel_to,
					'tdays'=>$value->cancel_before,
					'Currency'=>admin_base_currency()
					);
		}

	//	debug($data);die;
		return $data;
	}
	public function get_room_cancellation_policy($roomid='',$search_data=array())
	{

		$days =get_date_difference(date('Y-m-d'),$search_data['data']['from_date']);
		$q3=$this->db->get_where('crs_cancellation_policy',array('room_id'=>$roomid,'status'=>'ACTIVE'));
		if($q3->num_rows()<1)
		{
			return	array();
		}
		$data=array();
		//debug($this->db->last_query());die;
		foreach ($q3->result() as $key => $value) 
		{

			$cancel_date = date('Y-m-d', strtotime($search_data['data']['from_date'].' -'.$value->cancel_before.' day'));
			$cancel_date2 = date('Y-m-d', strtotime($search_data['data']['to_date'].' -'.$value->cancel_to.' day'));
			$data[]=array(
					'Charge'=>$value->penality,
					'ChargeType'=>2,
							'FromDate'=>date('Y-m-d', strtotime($search_data['data']['from_date'] . '-'.$value->cancel_before.'days')),
					'ToDate'=>date('Y-m-d', strtotime($search_data['data']['from_date'] . " -1 days")),
							'fdays'=>$value->cancel_to,
					'tdays'=>$value->cancel_before,
					'Currency'=>admin_base_currency()
					);
		}

		//debug($data);die;
		return $data;
	}
	
	public function get_room_meal_type() {
        $meal = array();
        $data = $this->db->get('crs_room_mealtype')->result();
        foreach ($data as $key => $value) {
            $meal[$value->id] = $value->name;
        }
        return $meal;
    }
	
	 //Hotel-CRS
    function get_crs_block_rooms_data($price_id, $search_data = array()) {
        // debug($s_max_adult);
        // debug($search_data);die;
        $response['Status'] = true;
        $amenities = $this->get_room_amenities();
        $meal_type = $this->get_room_meal_type();
        //debug($meal_type);exit;
        $hotel_list = array();
        $hotel_combination = array();
        $this->db->select('a.*,b.room_amenities,b.room_meal_type,c.name as room_name');
        $this->db->from('crs_room_price a');
        $this->db->join('crs_room_details b', 'a.room_id=b.id');
        $this->db->join('crs_room_type c', 'c.id=b.room_type_id');
        $this->db->where('a.id', $price_id);
        $query = $this->db->get();
    ///    echo $this->db->last_query();die;
        if ($query->num_rows() > 0) {
            // debug($query->result());die;
            foreach ($query->result() as $key => $value) {
                $hotel_data = array();
                $room_amenities = array();
                $HotelRoomsDetails = array();
                $room_meal = array();
                $hotel_amenities = explode(',', $value->room_amenities);
                foreach ($hotel_amenities as $k => $amenity) {
                    $room_amenities[] = $amenities[$amenity];
                }


                // $images=array();
                // $hotel_images=explode(',', $value->hotel_images);
                // foreach ($hotel_images as $j => $img) 
                // {
                // 		$images[]='/development/supervision/uploads/hotel_images/'.$img;
                // }
                $hotel_data['AvailabilityType'] = 'Confirm';
                $hotel_data['ChildCount'] = 0;
                $hotel_data['RequireAllPaxDetails'] = '';
                $hotel_data['RoomId'] = $value->room_id;
                $hotel_data['RoomStatus'] = $key;
                $hotel_data['RoomIndex'] = $key;
                $hotel_data['RoomTypeCode'] = $value->room_id;
                $hotel_data['RoomDescription'] = '';
                $hotel_data['RoomTypeName'] = $value->room_name;
                $hotel_data['RatePlanCode'] = $value->id;
                $hotel_data['RatePlan'] = $value->id;
                $hotel_data['InfoSource'] = 'FixedCombination';
                $hotel_data['SequenceNo'] = $key;
                $hotel_data['IsPerStay'] = '';
                $hotel_data['SupplierPrice'] = '';
                $hotel_data['Price'] = $this->get_room_price($value->hotel_id, $value->id, $search_data);
                
               // debug($hotel_data['Price']);die;
                $hotel_data['RoomPromotion'] = '';
                $hotel_data['Amenities'] = $room_amenities;
                $hotel_data['meal_type'] = $meal_type[$value->room_meal_type];
                $hotel_data['Amenity'] = $room_amenities;
                $hotel_data['SmokingPreference'] = '';
                $hotel_data['BedTypes'] = array();
                $hotel_data['HotelSupplements'] = array();
                
                $hotel_data['CancellationPolicies'] = $canelation = $this->get_room_cancellation_policy($value->room_id, $search_data);
                $hotel_data['LastCancellationDate'] = $hotel_data['CancellationPolicies'][0]['FromDate'];
              //  debug($hotel_data['CancellationPolicies']);die;
               // $hotel_data['LastVoucherDate'] = '2020-11-15T23:59:59';
                $hotel_data['CancellationPolicy'] = '';
                $hotel_data['Inclusion'] = $room_amenities;
                $hotel_data['IsPassportMandatory'] = '';
                $hotel_data['IsPANMandatory'] = '';
                $hotel_data['TBO_RoomIndex'] = $key;
                $hotel_data['TBO_RoomTypeName'] = $value->room_name;
                $hotel_data['HotelCode'] = $value->hotel_id;
                $hotel_data['SEARCH_ID'] = 1;
                $hotel_data['API_raw_price'] = 1;
                $hotel_data['AccessKey'] = 'xya';
                $hotel_data['Boarding_details'] = $room_amenities;
                $hotel_data['TM_Cancellation_Charge'] = $canelation;
                $hotel_data['IsPackageFare'] = 1;
                $hotel_data['IsPackageDetailsMandatory'] = 1;
                $hotel_list[] = $hotel_data;
            }
        } else {
            $response['Status'] = false;
        }
        $response['Message'] = '';
        $response['BlockRoom']['BlockRoomResult']['HotelRoomsDetails'] = $hotel_list;
        $response['BlockRoom']['BlockRoomResult']['BlockRoomId'] = $hotel_room_details_id;
        $response['BlockRoom']['BlockRoomResult']['IsPriceChanged'] = 1;
        $response['BlockRoom']['BlockRoomResult']['IsCancellationPolicyChanged'] = '';
        // debug($response);die;
        return $response;
        // debug($hotel_list);die;
    }
    
    
	//Hotel-CRS
     function get_crs_block_rooms_dataold($price_id,$search_data=array())
    {
    	// debug($s_max_adult);
    	// debug($search_data);die;
    	$response['Status']=true;
    	$amenities=$this->get_room_amenities();
    	$hotel_list=array();
    	$hotel_combination=array();
    	$this->db->select('a.*,b.room_amenities,c.name as room_name');
    	$this->db->from('crs_room_price a');
    	$this->db->join('crs_room_details b','a.room_id=b.id');
    	$this->db->join('crs_room_type c','c.id=b.room_type_id');
    	$this->db->where('a.id',$price_id);
    		$this->db->where('a.nationality',$search_data['data']['nationality']);
    	$query=$this->db->get();
    	if($query->num_rows()>0)
    	{
    			
    		foreach ($query->result() as $key => $value) 
    		{
    			$hotel_data=array();
    			$room_amenities=array();
    			$HotelRoomsDetails=array();
    			$hotel_amenities=explode(',', $value->room_amenities);
    			foreach ($hotel_amenities as $k => $amenity) 
    			{
    					$room_amenities[]=$amenities[$amenity];
    			}
    			// $images=array();
    			// $hotel_images=explode(',', $value->hotel_images);
    			// foreach ($hotel_images as $j => $img) 
    			// {
    			// 		$images[]='/development/supervision/uploads/hotel_images/'.$img;
    			// }
				// debug($room_amenities);die;
    			$hotel_data['AvailabilityType']='Confirm';
    			$hotel_data['ChildCount']=0;
    			$hotel_data['RequireAllPaxDetails']='';
    			$hotel_data['RoomId']=$value->room_id;
    			$hotel_data['RoomStatus']=$key;
    			$hotel_data['RoomIndex']=$key;
    			$hotel_data['RoomTypeCode']=$value->room_id;
    			$hotel_data['RoomDescription']='';
    			$hotel_data['RoomTypeName']=$value->room_name;
    			$hotel_data['RatePlanCode']=$value->id;
    			$hotel_data['RatePlan']=$value->id;
    			$hotel_data['InfoSource']='FixedCombination';
    			$hotel_data['SequenceNo']=$key;
    			$hotel_data['IsPerStay']='';
    			$hotel_data['SupplierPrice']='';
    			$hotel_data['Price']=$this->get_room_price($value->hotel_id,$value->id,$search_data);
    			$hotel_data['RoomPromotion']='';
    			$hotel_data['Amenities']=$room_amenities;
    			$hotel_data['Amenity']=$room_amenities;
    			$hotel_data['SmokingPreference']='';
    			$hotel_data['BedTypes']=array();
    			$hotel_data['HotelSupplements']=array();
    			
    			$hotel_data['CancellationPolicies']=$canelation=$this->get_room_cancellation_policy($value->room_id,$search_data);

$hotel_data['LastCancellationDate']=$canelation[0]['FromDate'];    		
			$hotel_data['LastVoucherDate']=$canelation[0]['FromDate'];
    			$hotel_data['CancellationPolicy']='';
    			$hotel_data['Inclusion']=$room_amenities;
    			$hotel_data['IsPassportMandatory']='';
    			$hotel_data['IsPANMandatory']='';
    			$hotel_data['TBO_RoomIndex']=$key;
    			$hotel_data['TBO_RoomTypeName']=$value->room_name;
    			$hotel_data['HotelCode']=$value->hotel_id;
    			$hotel_data['SEARCH_ID']=1;
    			$hotel_data['API_raw_price']=1;
    			$hotel_data['AccessKey']='xya';
    			$hotel_data['Boarding_details']=$room_amenities;
    			$hotel_data['TM_Cancellation_Charge']=$canelation;
    			$hotel_data['IsPackageFare']=1;
    			$hotel_data['IsPackageDetailsMandatory']=1;
    			$hotel_list[]=$hotel_data;    			   			
    		}	
    	}
    	else
    	{
    		$response['Status']=false;
    	}
    	$response['Message']='';
    	$response['BlockRoom']['BlockRoomResult']['HotelRoomsDetails']=$hotel_list;
    	$response['BlockRoom']['BlockRoomResult']['BlockRoomId']=$hotel_room_details_id;
    	$response['BlockRoom']['BlockRoomResult']['IsPriceChanged']=1;
    	$response['BlockRoom']['BlockRoomResult']['IsCancellationPolicyChanged']='';
    	// debug($response);die;
    	return $response;
    	// debug($hotel_list);die;
	}
}
