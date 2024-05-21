<?php
/**
 * Library which has generic functions to get data
 *
 * @package    Provab Application
 * @subpackage Hotel Model
 * @author     Arjun J<arjunjgowda260389@gmail.com>
 * @version    V2
 */
Class Hotel_Model_V3 extends CI_Model
{
	private $master_search_data;
	/*
	 *
	 * Get Airport List
	 *
	 */
	function get_hotel_city_list($query)
	{
		$this->db->like('country_name', $query);
		$this->db->or_like('city_name', $query);
		$this->db->or_like('country_code', $query);
		$this->db->limit(10);
		return $this->db->get('hotels_city')->result_array();
	}
	/*
	*Get Hotel city List
	*/
	function get_hotel_city_list_v3(){
		$city_data = $this->custom_db->single_table_records('all_api_city_master','origin as city_code,city_name,country_name,country_code');

		return $city_data['data'];
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
		//$condition = $this->custom_db->get_custom_condition($condition);
		//BT, CD, ID
		if ($count) {
			$query = 'select count(*) as total_records from hotel_booking_details BD where domain_origin='.get_domain_auth_id().' AND BD.created_by_id ='.$GLOBALS['CI']->entity_user_id;
			$data = $this->db->query($query)->row_array();
			return $data['total_records'];
		} else {
			$cols = '
				BD.status, BD.app_reference, BD.booking_source, BD.booking_id, BD.booking_reference, BD.confirmation_reference, BD.total_fare,
				BD.domain_markup, BD.level_one_markup, BD.currency, BD.hotel_name, BD.star_rating, BD.phone_number, BD.hotel_check_in,
				BD.hotel_check_out, BD.payment_mode, BD.created_by_id, BD.created_datetime,
				count(distinct(CD.origin)) as total_passengers, count(distinct(ID.origin)) as total_rooms,
				concat(CD.title, " ", CD.first_name, " ", CD.middle_name, " ",CD.last_name) name, CD.email,
				POL.name as payment_name';
			$query = 'select '.$cols.' from hotel_booking_details AS BD, hotel_booking_pax_details AS CD, hotel_booking_itinerary_details AS ID
				,payment_option_list as POL where POL.payment_category_code=BD.payment_mode AND BD.app_reference=CD.app_reference AND BD.app_reference=ID.app_reference AND BD.domain_origin='.get_domain_auth_id().'
				AND BD.created_by_id ='.$GLOBALS['CI']->entity_user_id.' group by BD.app_reference, CD.app_reference, ID.app_reference order by BD.origin desc, ID.origin, CD.origin limit '.$offset.', '.$limit;;
			return $this->db->query($query)->result_array();
		}
	}

	/**
	 * get search data and validate it
	 */
	function get_safe_search_data($search_id)
	{
		$search_data = $this->get_search_data($search_id);
		//debug($search_data); exit;
		$success = true;
		$clean_search = array();
		if ($search_data != false) {
			//validate
			$temp_search_data = json_decode($search_data['search_data'], true);
			//debug($temp_search_data);
			//make sure dates are correct
			if (strtotime($temp_search_data['hotel_checkin']) > time() && strtotime($temp_search_data['hotel_checkout']) > time()) {
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
				//echo "ni";
				$success = false;
			}
			//city name and country name
			if (isset($temp_search_data['city']) == true) {
				$clean_search['location'] = $temp_search_data['city'];
				$temp_location = explode('(', $temp_search_data['city']);
				$clean_search['city_name'] = trim($temp_location[0]);
				if (isset($temp_location[1]) == true) {
					//$clean_search['country_name'] = trim($temp_location[1], '() ');
                    $clean_search['country_name'] = $temp_search_data['country_name'];
				} else {
					$clean_search['country_name'] = $temp_search_data['country_name'];
				}
			} else {
				if(!(isset($temp_search_data['latitude']) && isset($temp_search_data['longitude']))){
					$success = false;
				}
					
			}
			if(isset($temp_search_data['search_type'])){
				if($temp_search_data['search_type'] == 'location_search'){
					$clean_search['CountryCode'] = $temp_search_data['CountryCode'];
				}
				else{
					$clean_search['CountryCode'] = $temp_search_data['CountryCode'];
					$clean_search['city_code'] = $temp_search_data['city_code'];
					$clean_search['destination_code'] = $temp_search_data['destination_code'];
					$clean_search['agoda_city_id'] = $temp_search_data['agoda_city_id'];
					$clean_search['fab_city_id'] = $temp_search_data['fab_city_id'];
					$clean_search['hb_city_id'] = $temp_search_data['hb_city_id'];
					$clean_search['fab_state'] = $temp_search_data['fab_state'];
					$clean_search['hotel_origin'] = $temp_search_data['hotel_origin'];
					$clean_search['location_id'] = $temp_search_data['hotel_destination'];
				}
				
			}
			else{
				$clean_search['CountryCode'] = $temp_search_data['CountryCode'];
				$clean_search['city_code'] = $temp_search_data['city_code'];
				$clean_search['destination_code'] = @$temp_search_data['destination_code'];
				$clean_search['agoda_city_id'] = @$temp_search_data['agoda_city_id'];
				$clean_search['fab_city_id'] = $temp_search_data['fab_city_id'];
				$clean_search['hb_city_id'] = $temp_search_data['hb_city_id'];
				$clean_search['fab_state'] = $temp_search_data['fab_state'];
				$clean_search['hotel_origin'] = $temp_search_data['hotel_origin'];
				$clean_search['location_id'] = $temp_search_data['hotel_destination'];
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
			// $clean_search['location_id'] = $temp_search_data['hotel_destination'];
			$clean_search['room_config'] = $temp_search_data['room_config'];
			if(isset($temp_search_data['search_type'])){
				if($temp_search_data['search_type'] == 'location_search'){
					$clean_search['latitude'] = $temp_search_data['latitude'];
					$clean_search['longitude'] = $temp_search_data['longitude'];
					$clean_search['radius'] = $temp_search_data['radius'];
				}
				$clean_search['search_type'] = $temp_search_data['search_type'];
			}
			
		} else {
			$success = false;
		}
		//echo "success".$success;
         //debug($clean_search);exit;
		return array('status' => $success, 'data' => $clean_search);
	}
	function get_safe_search_data_old($search_id)
	{
		$search_data = $this->get_search_data($search_id);

		$success = true;
		$clean_search = array();
		if ($search_data != false) {
			//validate
			$temp_search_data = json_decode($search_data['search_data'], true);
			//debug($temp_search_data);
			//make sure dates are correct
			if (strtotime($temp_search_data['hotel_checkin']) > time() && strtotime($temp_search_data['hotel_checkout']) > time()) {
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
				//echo "ni";
				$success = false;
			}
			//city name and country name
			if (isset($temp_search_data['city']) == true) {
				$clean_search['location'] = $temp_search_data['city'];
				$temp_location = explode('(', $temp_search_data['city']);
				$clean_search['city_name'] = trim($temp_location[0]);
				if (isset($temp_location[1]) == true) {
					//$clean_search['country_name'] = trim($temp_location[1], '() ');
                    $clean_search['country_name'] = $temp_search_data['country_name'];
				} else {
					$clean_search['country_name'] = $temp_search_data['country_name'];
				}
			} else {
				if(!(isset($temp_search_data['latitude']) && isset($temp_search_data['longitude']))){
					$success = false;
				}
					
			}
			if(isset($temp_search_data['search_type'])){
				if($temp_search_data['search_type'] == 'location_search'){
					$clean_search['CountryCode'] = $temp_search_data['CountryCode'];
				}
				else{
					$clean_search['CountryCode'] = $temp_search_data['CountryCode'];
					$clean_search['city_code'] = $temp_search_data['city_code'];
					$clean_search['destination_code'] = $temp_search_data['destination_code'];
					$clean_search['agoda_city_id'] = $temp_search_data['agoda_city_id'];
					$clean_search['fab_city_id'] = $temp_search_data['fab_city_id'];
					$clean_search['fab_state'] = $temp_search_data['fab_state'];
					$clean_search['hotel_origin'] = $temp_search_data['hotel_origin'];
					$clean_search['location_id'] = $temp_search_data['hotel_destination'];
				}
				
			}
			else{
				$clean_search['CountryCode'] = $temp_search_data['CountryCode'];
				$clean_search['city_code'] = $temp_search_data['city_code'];
				$clean_search['destination_code'] = $temp_search_data['destination_code'];
				$clean_search['agoda_city_id'] = $temp_search_data['agoda_city_id'];
				$clean_search['fab_city_id'] = $temp_search_data['fab_city_id'];
				$clean_search['fab_state'] = $temp_search_data['fab_state'];
				$clean_search['hotel_origin'] = $temp_search_data['hotel_origin'];
				$clean_search['location_id'] = $temp_search_data['hotel_destination'];
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
			// $clean_search['location_id'] = $temp_search_data['hotel_destination'];
			$clean_search['room_config'] = $temp_search_data['room_config'];
			if(isset($temp_search_data['search_type'])){
				if($temp_search_data['search_type'] == 'location_search'){
					$clean_search['latitude'] = $temp_search_data['latitude'];
					$clean_search['longitude'] = $temp_search_data['longitude'];
					$clean_search['radius'] = $temp_search_data['radius'];
				}
				$clean_search['search_type'] = $temp_search_data['search_type'];
			}
			
		} else {
			$success = false;
		}
		//echo "success".$success;
         //debug($clean_search);exit;
		return array('status' => $success, 'data' => $clean_search);
	}
        
        
        
        function get_safe_search_data_grn($search_id)
	{
		$search_data = $this->get_search_data($search_id);

		$success = true;
		$clean_search = array();
                
		if ($search_data != false) {
			//validate
			$temp_search_data = json_decode($search_data['search_data'], true);
			//make sure dates are correct
			/*if (strtotime($temp_search_data['hotel_checkin']) > time() && strtotime($temp_search_data['hotel_checkout']) > time()) {
				
				$clean_search['from_date'] = $temp_search_data['hotel_checkin'];
				$clean_search['to_date'] = $temp_search_data['hotel_checkout'];
				
				$clean_search['no_of_nights'] = abs(get_date_difference($clean_search['from_date'], $clean_search['to_date']));
			} else {
				//echo "ni";
				$success = false;
			}*/
                        
                        
                $clean_search['from_date'] = $temp_search_data['hotel_checkin'];
				$clean_search['to_date'] = $temp_search_data['hotel_checkout'];
				
				$clean_search['no_of_nights'] = abs(get_date_difference($clean_search['from_date'], $clean_search['to_date']));
                        
			//city name and country name
			if (isset($temp_search_data['city']) == true ) {
				$clean_search['location'] = $temp_search_data['city'];
				$temp_location = explode('(', $temp_search_data['city']);
				$clean_search['city_name'] = trim($temp_location[0]);
				if (isset($temp_location[1]) == true) {
					//$clean_search['country_name'] = trim($temp_location[1], '() ');
                    $clean_search['country_name'] = $temp_search_data['country_name'];
				} else {
					$clean_search['country_name'] = $temp_search_data['country_name'];
				}
			} else {
				if(!(isset($temp_search_data['latitude']) && isset($temp_search_data['longitude']))){
					$success = false;
				}
				
				//echo "ciyy";
				
			}
			if(isset($temp_search_data['search_type'])){
				if($temp_search_data['search_type'] == 'location_search'){
					$clean_search['CountryCode'] = $temp_search_data['CountryCode'];
				}
				else{
					$clean_search['CountryCode'] = $temp_search_data['CountryCode'];
					$clean_search['city_code'] = $temp_search_data['city_code'];
					$clean_search['destination_code'] = $temp_search_data['destination_code'];
					$clean_search['agoda_city_id'] = $temp_search_data['agoda_city_id'];
					$clean_search['fab_city_id'] = $temp_search_data['fab_city_id'];
					$clean_search['fab_state'] = $temp_search_data['fab_state'];
					$clean_search['hotel_origin'] = $temp_search_data['hotel_origin'];
					$clean_search['location_id'] = $temp_search_data['hotel_destination'];
				}
			}
			else{
				$clean_search['CountryCode'] = $temp_search_data['CountryCode'];
				$clean_search['city_code'] = $temp_search_data['city_code'];
				$clean_search['destination_code'] = @$temp_search_data['destination_code'];
				$clean_search['agoda_city_id'] = @$temp_search_data['agoda_city_id'];
				$clean_search['fab_city_id'] = @$temp_search_data['fab_city_id'];
				$clean_search['fab_state'] = $temp_search_data['fab_state'];
				$clean_search['hotel_origin'] = $temp_search_data['hotel_origin'];
				$clean_search['location_id'] = $temp_search_data['hotel_destination'];
			}
			
			
			if(empty($temp_search_data['api_occurance']) == false){
				$clean_search['api_occurance'] = $temp_search_data['api_occurance'];
			}
			
			//if hotel code present
			if(isset($temp_search_data['hotel_code'])){
				if($temp_search_data['hotel_code']){
					$clean_search['hotel_code'] = $temp_search_data['hotel_code'];	
				}
				
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
			
			
			$clean_search['room_config'] = $temp_search_data['room_config'];
			if(isset($temp_search_data['search_type'])){
				if($temp_search_data['search_type'] == 'location_search'){
					$clean_search['latitude'] = $temp_search_data['latitude'];
					$clean_search['longitude'] = $temp_search_data['longitude'];
					$clean_search['radius'] = $temp_search_data['radius'];
				}
				$clean_search['search_type'] = $temp_search_data['search_type'];
			}
			$clean_search['room_config'] = $temp_search_data['room_config'];
		} else {
			$success = false;
		}
		
		return array('status' => $success, 'data' => $clean_search);
	}

	/**
	 * get search data without doing any validation
	 * @param $search_id
	 */
	function get_search_data($search_id)
	{
		if (empty($this->master_search_data)) {
			$search_data = $this->custom_db->single_table_records('search_history', '*', array('search_type' => META_ACCOMODATION_COURSE, 'origin' => $search_id));
			// debug($search_data);exit;
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
	$total_fare, $domain_markup, $level_one_markup, $currency, $hotel_name, $star_rating, $hotel_code, $phone_number, $alternate_number, $email,
	$hotel_check_in, $hotel_check_out, $payment_mode,	$attributes, $created_by_id, $currency_conversion_rate=1, $hotel_version=HOTEL_VERSION_1,$gst=0,$hotel_markup_price=0,$admin_markup_gst=0)
	{
		$data['domain_origin'] = $domain_origin;
		$data['status'] = $status;
		$data['app_reference'] = $app_reference;
		$data['booking_source'] = $booking_source;
		$data['booking_id'] = $booking_id;
		$data['booking_reference'] = $booking_reference;
		$data['confirmation_reference'] = $confirmation_reference;
		$data['total_fare'] = $total_fare;
		$data['domain_markup'] = $domain_markup;
		$data['level_one_markup'] = $level_one_markup;
		$data['currency'] = $currency;
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
		$data['currency_conversion_rate'] = $currency_conversion_rate;
		$data['version'] = $hotel_version;
		/*store gst GRN*/
		$data['domain_gst'] = $gst;
		$data['hotel_markup_price'] = $hotel_markup_price;
		$data['admin_markup_gst'] = $admin_markup_gst;
		/*end*/
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
	function save_booking_itinerary_details($app_reference, $location, $check_in, $check_out, $room_type_name, $bed_type_code, $status, $smoking_preference, $total_fare, $domain_markup, $level_one_markup, $currency, $attributes, $RoomPrice, $Tax, $ExtraGuestCharge, $ChildCharge, $OtherCharges, $Discount, $ServiceTax, $AgentCommission, $AgentMarkUp, $TDS)
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
		$data['domain_markup'] = $domain_markup;
		$data['level_one_markup'] = $level_one_markup;
		$data['currency'] = $currency;
		$data['attributes'] = $attributes;
		$data['RoomPrice'] = $RoomPrice;
		$data['Tax'] = $Tax;
		$data['ExtraGuestCharge'] = $ExtraGuestCharge;
		$data['ChildCharge'] = $ChildCharge;
		$data['OtherCharges'] = $OtherCharges;
		$data['Discount'] = $Discount;
		$data['ServiceTax'] = $ServiceTax;
		$data['AgentCommission'] = $AgentCommission;
		$data['AgentMarkUp'] = $AgentMarkUp;
		$data['TDS'] = $TDS;
		
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
		
		$data['app_reference'] = $app_reference;
		$data['title'] = $title;
		$data['first_name'] = $first_name;
		$data['middle_name'] = $middle_name;
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
	 * Return Booking Details based on the app_reference passed
	 * @param $app_reference
	 * @param $booking_source
	 * @param $booking_status
	 */
	function get_booking_details($app_reference, $booking_source, $booking_status='')
	{
		//hotel_booking_details
		//hotel_booking_itinerary_details
		//hotel_booking_customer_details
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
		$response['data']['booking_details']			= $this->db->query($bd_query)->row_array();
		$response['data']['booking_itinerary_details']	= $this->db->query($id_query)->result_array();
		$response['data']['booking_pax_details']	= $this->db->query($cd_query)->result_array();
		if (valid_array($response['data']['booking_details']) == true and valid_array($response['data']['booking_itinerary_details']) == true and valid_array($response['data']['booking_pax_details']) == true) {
			$response['status'] = SUCCESS_STATUS;
		}
		return $response;
	}
	/**
	 * Returns Hotel Cancellation details
	 * @param unknown_type $app_reference
	 * @param unknown_type $ChangeRequestId
	 */
	function get_hotel_cancellation_details($app_reference, $ChangeRequestId, $domain_id)
	{
		$query = 'select HCD.* from hotel_booking_details HB
					join hotel_cancellation_details HCD on HCD.app_reference=HB.app_reference
					where HB.app_reference='.$this->db->escape($app_reference).' and HB.domain_origin='.intval($domain_id).' and HCD.ChangeRequestId='.$this->db->escape($ChangeRequestId);
		$details = $this->db->query($query)->result_array();
		return $details;
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
	 * SAve search data for future use - Analytics
	 * @param array $params
	 */
	function save_search_history_data($search_request)
	{	
		// debug($search_request);exit;
		$data['status'] = SUCCESS_STATUS;
		
		$cache_key = $this->redis_server->generate_cache_key();
		$hotel_city_id = intval($search_request['CityId']);
		//$hotel_city_id = $search_request['CityId'];
		$number_of_nights = intval($search_request['NoOfNights']);
		$number_of_adults = 0;
		$number_of_childs = 0;
		foreach ($search_request['RoomGuests'] as $k => $v){
			$number_of_adults += $v['NoOfAdults'];
			if(isset($v['NoOfChild']) == true){
				$number_of_childs += $v['NoOfChild'];
			}
		}	
		$hotel_city_details = $this->db->query('select * from all_api_city_master where origin='.$hotel_city_id)->row_array();
		$hotel_check_in_date = date('d-m-Y', strtotime($search_request['CheckInDate']));
		$hotel_check_out_date = date('d-m-Y', strtotime("+".$number_of_nights." days", strtotime($hotel_check_in_date)));
		
		$request = array();
		
		$request['hotel_checkin'] = $hotel_check_in_date;
		$request['hotel_checkout'] = $hotel_check_out_date;
		$request['rooms'] = $search_request['NoOfRooms'];
		$request['adult'] = $number_of_adults;
		$request['child'] = $number_of_childs;
		
		if(isset($search_request['search_type'])){
			if($search_request['search_type'] == 'location_search'){
				$request['latitude'] = $search_request['latitude'];
				$request['longitude'] = $search_request['longitude'];
				$request['radius'] = $search_request['radius'];
				$request['CountryCode'] = $search_request['CountryCode'];
			}
			else{
				$request['city'] = $hotel_city_details['city_name'].'('.$hotel_city_details['country_name'].')';
		        $request['CountryCode'] = $hotel_city_details['country_code'];
		        //$request['country_name'] = 'India';
		        $request['country_name'] = $hotel_city_details['country_name'];
		        $request['city_code'] = $hotel_city_details['grn_city_id'];
		        $request['destination_code'] = $hotel_city_details['grn_destination_id'];
		        $request['agoda_city_id'] = $hotel_city_details['agoda_city_id'];
		        $request['fab_city_id'] = $hotel_city_details['fab_city_id'];
		        $request['fab_state'] = $hotel_city_details['fab_state'];
				//below code comment by ela
				//$request['hotel_destination'] = $hotel_city_id;
				$request['hotel_destination'] = $hotel_city_details['tbo_city_id'];
			    $request['hotel_origin'] = $hotel_city_details['origin'];
			    $request['hb_city_id'] = $hotel_city_details['hb_city_id'];
			}
			$request['search_type'] = $search_request['search_type'];
		}
		else{
			$request['city'] = $hotel_city_details['city_name'].'('.$hotel_city_details['country_name'].')';
	        $request['CountryCode'] = $hotel_city_details['country_code'];
	        //$request['country_name'] = 'India';
	        $request['country_name'] = $hotel_city_details['country_name'];
	        $request['city_code'] = $hotel_city_details['grn_city_id'];
	        //$request['destination_code'] = $hotel_city_details['grn_destination_id'];
	        //$request['agoda_city_id'] = $hotel_city_details['agoda_city_id'];
	        $request['fab_city_id'] = @$hotel_city_details['fab_city_id'];
	        $request['fab_state'] = @$hotel_city_details['fab_state'];
			//below code comment by ela
			//$request['hotel_destination'] = $hotel_city_id;
			$request['hotel_destination'] = $hotel_city_details['tbo_city_id'];
		    $request['hotel_origin'] = $hotel_city_details['origin'];
		    $request['hb_city_id'] = $hotel_city_details['hb_city_id'];
		}
		$request['room_config'] = $search_request['RoomGuests'];
		if(empty($search_request['api_occurance']) == false){
			$request['api_occurance'] = $search_request['api_occurance'];
		}
		
		//If hotel code present
		if(isset($search_request['HotelCode'])){
			if($search_request['HotelCode']){
				$request['hotel_code'] = $search_request['HotelCode'];
		
			}
		}
		$search_history_data = array();
		$search_history_data['domain_origin'] = 	get_domain_auth_id();
		$search_history_data['cache_key'] = 		$cache_key;
		$search_history_data['search_type'] = 		META_ACCOMODATION_COURSE;
		$search_history_data['search_data'] = 		json_encode($request);
		$search_history_data['created_datetime'] =	db_current_datetime();
		$insert_data = $this->custom_db->insert_record('search_history', $search_history_data);
		if($insert_data['status'] == QUERY_SUCCESS){
			$data['cache_key'] = $cache_key;
			$data['search_id'] = $insert_data['insert_id'];
		} else {
			$data['status'] = FAILURE_STATUS;
		}
		return $data;
	}
	function save_search_history_data_old($search_request)
	{
		
		// debug($search_request);exit;
		$data['status'] = SUCCESS_STATUS;
		
		$cache_key = $this->redis_server->generate_cache_key();
		$hotel_city_id = intval($search_request['CityId']);
		//$hotel_city_id = $search_request['CityId'];
		$number_of_nights = intval($search_request['NoOfNights']);
		$number_of_adults = 0;
		$number_of_childs = 0;
		foreach ($search_request['RoomGuests'] as $k => $v){
			$number_of_adults += $v['NoOfAdults'];
			if(isset($v['NoOfChild']) == true){
				$number_of_childs += $v['NoOfChild'];
			}
		}	

		$hotel_city_details = $this->db->query('select * from all_api_city_master where origin='.$hotel_city_id)->row_array();
		
		$hotel_check_in_date = date('d-m-Y', strtotime($search_request['CheckInDate']));
		$hotel_check_out_date = date('d-m-Y', strtotime("+".$number_of_nights." days", strtotime($hotel_check_in_date)));
		
		$request = array();
		
		$request['hotel_checkin'] = $hotel_check_in_date;
		$request['hotel_checkout'] = $hotel_check_out_date;
		$request['rooms'] = $search_request['NoOfRooms'];
		$request['adult'] = $number_of_adults;
		$request['child'] = $number_of_childs;
		
		if(isset($search_request['search_type'])){
			if($search_request['search_type'] == 'location_search'){
				$request['latitude'] = $search_request['latitude'];
				$request['longitude'] = $search_request['longitude'];
				$request['radius'] = $search_request['radius'];
				$request['CountryCode'] = $search_request['CountryCode'];
			}
			else{
				$request['city'] = $hotel_city_details['city_name'].'('.$hotel_city_details['country_name'].')';
		        $request['CountryCode'] = $hotel_city_details['country_code'];
		        //$request['country_name'] = 'India';
		        $request['country_name'] = $hotel_city_details['country_name'];
		        $request['city_code'] = $hotel_city_details['grn_city_id'];
		        $request['destination_code'] = $hotel_city_details['grn_destination_id'];
		        $request['agoda_city_id'] = $hotel_city_details['agoda_city_id'];
		        $request['fab_city_id'] = $hotel_city_details['fab_city_id'];
		        $request['fab_state'] = $hotel_city_details['fab_state'];
				//below code comment by ela
				//$request['hotel_destination'] = $hotel_city_id;
				$request['hotel_destination'] = $hotel_city_details['tbo_city_id'];
			    $request['hotel_origin'] = $hotel_city_details['origin'];
			}
			$request['search_type'] = $search_request['search_type'];
		}
		else{
			$request['city'] = $hotel_city_details['city_name'].'('.$hotel_city_details['country_name'].')';
	        $request['CountryCode'] = $hotel_city_details['country_code'];
	        //$request['country_name'] = 'India';
	        $request['country_name'] = $hotel_city_details['country_name'];
	        $request['city_code'] = $hotel_city_details['grn_city_id'];
	        $request['destination_code'] = $hotel_city_details['grn_destination_id'];
	        $request['agoda_city_id'] = $hotel_city_details['agoda_city_id'];
	        $request['fab_city_id'] = @$hotel_city_details['fab_city_id'];
	        $request['fab_state'] = @$hotel_city_details['fab_state'];
			//below code comment by ela
			//$request['hotel_destination'] = $hotel_city_id;
			$request['hotel_destination'] = $hotel_city_details['tbo_city_id'];
		    $request['hotel_origin'] = $hotel_city_details['origin'];
		}
		$request['room_config'] = $search_request['RoomGuests'];
		if(empty($search_request['api_occurance']) == false){
			$request['api_occurance'] = $search_request['api_occurance'];
		}
		
		//If hotel code present
		if(isset($search_request['HotelCode'])){
			if($search_request['HotelCode']){
				$request['hotel_code'] = $search_request['HotelCode'];
		
			}
		}
		$search_history_data = array();
		$search_history_data['domain_origin'] = 	get_domain_auth_id();
		$search_history_data['cache_key'] = 		$cache_key;
		$search_history_data['search_type'] = 		META_ACCOMODATION_COURSE;
		$search_history_data['search_data'] = 		json_encode($request);
		$search_history_data['created_datetime'] =	db_current_datetime();
		// debug($search_history_data);exit;
		$insert_data = $this->custom_db->insert_record('search_history', $search_history_data);
		if($insert_data['status'] == QUERY_SUCCESS){
			$data['cache_key'] = $cache_key;
			$data['search_id'] = $insert_data['insert_id'];
		} else {
			$data['status'] = FAILURE_STATUS;
		}
		return $data;
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
		$this->update_cancellation_refund_details($AppReference, $cancellation_details);
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
		$cancellation_details = $cancellation_details['HotelChangeRequestStatusResult'];
		$hotel_cancellation_details = array();
		$hotel_cancellation_details['app_reference'] = 				$AppReference;
		$hotel_cancellation_details['ChangeRequestId'] = 			$cancellation_details['ChangeRequestId'];
		$hotel_cancellation_details['ChangeRequestStatus'] = 		$cancellation_details['ChangeRequestStatus'];
		$hotel_cancellation_details['status_description'] = 		$cancellation_details['StatusDescription'];
		$hotel_cancellation_details['API_RefundedAmount'] = 		$cancellation_details['RefundedAmount'];
		$hotel_cancellation_details['API_CancellationCharge'] = 	$cancellation_details['CancellationCharge'];
		if($cancellation_details['ChangeRequestStatus'] == 3){
			$hotel_cancellation_details['cancellation_processed_on'] =	date('Y-m-d H:i:s');
			$attributes = array();
			$attributes['CreditNoteNo'] = 								@$cancellation_details['CreditNoteNo'];
			$attributes['CreditNoteCreatedOn'] = 						@$cancellation_details['CreditNoteCreatedOn'];
			$hotel_cancellation_details['attributes'] = json_encode($attributes);
		}
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
	 * Update the Refund details
	 * @param unknown_type $app_reference
	 * @param unknown_type $refund_status
	 * @param unknown_type $refund_amount
	 * @param unknown_type $currency
	 * @param unknown_type $currency_conversion_rate
	 */
	function update_refund_details($app_reference, $refund_status, $refund_amount,$cancellation_charge, $currency, $currency_conversion_rate)
	{
		$refund_details = array();
		$refund_details['refund_amount'] =				floatval($refund_amount);
		$refund_details['cancellation_charge'] =		floatval($cancellation_charge);
		$refund_details['refund_status'] = 				$refund_status;
		$refund_details['refund_payment_mode'] = 		'online';
		$refund_details['currency'] = 					$currency;
		$refund_details['currency_conversion_rate'] = 	$currency_conversion_rate;
		$refund_details['refund_date'] = 				date('Y-m-d H:i:s');
		$this->custom_db->update_record('hotel_cancellation_details', $refund_details, array('app_reference'=> $app_reference));
	}
	/**
	 * Update the Old Booking App Reference
	 */
	public function update_old_booking_app_reference($new_app_reference, $booking_id, $domain_origin)
	{
		$new_booking_data = $this->db->query('select * from hotel_booking_details where app_reference='.$this->db->escape($new_app_reference))->row_array();
		if(valid_array($new_booking_data)==true){
			$update_app_reference = false;
		} else{
			$update_app_reference = true;
		}
		if($update_app_reference == true){//If its old booking update AppReference
			//Get master Details
			$master_booking_details = $this->db->query('select HBD.app_reference,HBD.booking_source from hotel_booking_details HBD
								where HBD.domain_origin='.intval($domain_origin).' and HBD.booking_id='.$this->db->escape($booking_id))->row_array();
			$old_app_reference = trim($master_booking_details['app_reference']);
			$booking_source = trim($master_booking_details['booking_source']);
			//$booking_details = $this->get_booking_details($master_app_reference, $booking_source);
			//UPDATE DATA
			$update_data['app_reference'] = $new_app_reference;
			//UPDATE CONDITIOn
			$update_condition['app_reference'] = $old_app_reference;
			//1.update hotel_booking_details
			$this->custom_db->update_record('hotel_booking_details', $update_data, $update_condition);
			//2.update hotel_booking_itinerary_details
			$this->custom_db->update_record('hotel_booking_itinerary_details', $update_data, $update_condition);
			//3.update hotel_booking_pax_details
			$this->custom_db->update_record('hotel_booking_pax_details', $update_data, $update_condition);
			//4.update transaction_log
			$this->custom_db->update_record('transaction_log', $update_data, $update_condition);
		}
	}
	public function get_GRN_country_code($country_name){
		//echo "country_name".$country_name;
		$get_grn_city_list = $this->db->query('select * from api_country_master where country_name = "'.$country_name.'"')->row_array();

		return $get_grn_city_list['iso_country_code'];
	}
	public function get_hotel_list_code($country_code){
		//$get_grn_hotel_code = $this->db->query('select * from api_hotel_master where city_code = "'.$city_code.'"')->result_array();

		$get_grn_hotel_code = $this->db->query('select * from api_hotel_master where country_code = "'.$country_code.'"')->result_array();

		$hotel_codes = array();
		if($get_grn_hotel_code){
			foreach ($get_grn_hotel_code as $key => $value) {
				if($key <3000){
					$hotel_codes []=$value['hotel_code'];	
				}
				
			}
		}
		return $hotel_codes;
	}
	/**
	*Getting city_code from grn connect
	*/
	public function get_grn_city_code($city_name,$country_name){
		//	$get_city_list = $this->custom_db->single_table_records('hotels_city','*',array('origin'=>$city_id));
		if($city_name!=''){
			
			$get_iso_code = $this->get_GRN_country_code($country_name);
			//get grn city_code
			$get_grn_city_list = $this->db->query('select * from api_city_master where country_code ="'.$get_iso_code.'" AND city_name LIKE "%'.$city_name.'%"')->row_array();	
		 	// echo 'select * from api_city_master where country_code ="'.$get_iso_code.'" AND city_name LIKE "%'.$city_name.'%"';
		 	// debug($get_grn_city_list);

			return array('status'=>1,'city_code'=>$get_grn_city_list['city_code'],'destination_code'=>$get_grn_city_list['destination_code'],'country_code'=>$get_grn_city_list['country_code']);
		}else{
			return array('status'=>0);
		}
		
	}
	public function get_grn_hotel_area_code($city_name,$country_name){
		$get_iso_code = $this->get_GRN_country_code($country_name);
		$get_grn_area_list = $this->db->query('select * from api_area_master where country ="'.$get_iso_code.'" AND country_name LIKE "%'.$country_name.'%" AND  area_name LIKE "%'.$city_name.'%"')->row_array();
		// debug($get_grn_area_list);
		// exit;
		if($get_grn_area_list){
			return array('status'=>1,'area_code'=>$get_grn_area_list['area_code']);
		}
		return array('status'=>0);

	}
	public function get_grn_destination_code($city_name,$country_name){
		if($city_name !=''){
			$get_iso_code = $this->get_GRN_country_code($country_name);
			$get_grn_city_list = $this->db->query('select * from api_city_master where country_code ="'.$get_iso_code.'" AND city_name LIKE "%'.$city_name.'%"')->row_array();

			//	echo 'select * from api_city_master where country_code ="'.$get_iso_code.'" AND city_name LIKE "%'.$city_name.'%"';
			
			$iso_country_code = $get_grn_city_list['country_code'];

			$get_destination_list = $this->custom_db->single_table_records('api_city_master','*',array('country_code'=>$iso_country_code));
			// debug($get_destination_list);
			// exit;
			$destination_count = 0;
			if($get_destination_list['status']==1){
				if($get_destination_list['data']){
					$destination_count = count($get_destination_list['data']);
					$get_destination_list = $get_destination_list['data'][0];
					
					if($destination_count == 1){
						return array('status'=>1,'city_code'=>$get_destination_list['city_code'],'destination_code'=>$get_destination_list['destination_code']);
					}else{
						return array('status'=>0,'city_code'=>$get_grn_city_list['city_code']);
					}
				}
				
			}else{
				return array('status'=>0,'city_code'=>$get_grn_city_list['city_code']);
			}
			
			
		}
	}
	//getting static trip advisior rating from table
	public function get_trip_advisor_data($hotel_code,$city_code,$country_code){

		$result_data = $this->db->query('SELECT hotel_code,city_code,tri_adv_hotel from grn_trip_advisor where hotel_code="'.$hotel_code.'" and country_code="'.$country_code.'" and city_code ="'.$city_code.'" ')->result_array();
		return $result_data;

	}
	public function get_trip_advisor_data_country($country_code){
		$result_data = $this->db->query('SELECT * from grn_trip_advisor where country_code="'.$country_code.'"')->result_array();
		
		return $result_data;
	}
	public function set_grn_room_boarding_details($hotel_code,$boarding_details,$city_code,$country_code){
		$check_if_exists = $this->custom_db->single_table_records('grn_room_boarding_details','*',array('hotel_code'=>$hotel_code,'city_code'=>$city_code,'country_code'=>$country_code));

		if($check_if_exists['status']==true){
			$this->custom_db->update_record('grn_room_boarding_details',array('boarding_details'=>$boarding_details),array('hotel_code'=>$hotel_code,'city_code'=>$city_code,'country_code'=>$country_code));
			
		}else{
			$insert_data['hotel_code'] = $hotel_code;
			$insert_data['boarding_details'] = $boarding_details;
			$insert_data['city_code'] = $city_code;
			$insert_data['country_code']=$country_code;
			$this->custom_db->insert_record('grn_room_boarding_details',$insert_data);
		}
	}
	//get grn static image from database
	public function get_grn_master_image($hotel_code){
		$image_data = $this->db->query('SELECT path_name from api_grn_master_image  USE INDEX (hotel_code) where hotel_code="'.$hotel_code.'"')->result_array();
		return $image_data;
	}
	// get agoda hotel code based on destination
	public function get_agoda_hotel_code($destination_code){
		$result_data = $this->db->query('SELECT distinct(hotel_id) from agoda_hotel_master where city_id="'.$destination_code.'"' )->result_array();
		return $result_data;
	}
	//get payment details
	public function get_payment_details(){
		$payment_data = $this->db->query('SELECT * from payment_details')->row_array();
		return $payment_data;
	}
	// get offline hotel api's based on domain
	public function get_offline_hotel_api($domain_origin){
		$result_data = $this->db->query('SELECT booking_source_origin from offline_hotelapi_list where domain_origin="'.$domain_origin.'" and status = 1' )->result_array();
		// debug($result_data);exit;
		return $result_data;
	}
	public function check_hotel_name($hotel_name){
		// echo 'select * from oyo_hotel_details_live where hotel_name LIKE "%'.$hotel_name.'%"';exit;
		$result_data = $this->db->query('select * from oyo_hotel_details_live where hotel_name LIKE "%'.$hotel_name.'%"')->num_rows();	
		
		if($result_data > 0){
			return true;
		}
		else{
			return false;
		}
		
	}
	public function get_HB_hotel_code($destination_code){
		$result_data = $this->db->query('SELECT hotel_code from hb_hotel_details where destination_code="'.$destination_code.'"' )->result_array();
		return $result_data;
	}
}