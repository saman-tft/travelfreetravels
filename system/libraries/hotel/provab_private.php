<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
require_once BASEPATH . 'libraries/Common_Api_Grind.php';
/**
 *
 * @package Provab
 * @subpackage API
 * @author Balu A<balu.provab@gmail.com>
 * @version V1
 */
class Provab_private extends Common_Api_Grind {
	private $ClientId;
	private $UserName;
	private $Password;
	private $service_url;
	private $Url;
	public $master_search_data;
	public $search_hash;
	public function __construct() {
		$this->CI = &get_instance ();
		$GLOBALS ['CI']->load->library ( 'Api_Interface' );
		$GLOBALS ['CI']->load->model ( 'hotel_model' );
		$this->TokenId = $GLOBALS ['CI']->session->userdata ( 'tb_auth_token' );
		$this->set_api_credentials ();
	}
	private function get_header() {
		$hotel_engine_system = $this->CI->hotel_engine_system;
		$user_name = $this->CI->hotel_engine_system. '_username';
        $password = $this->CI->hotel_engine_system. '_password';
		$response ['UserName'] = $this->CI->$user_name;
		$response ['Password'] = $this->CI->$password;
		$response ['DomainKey'] = $this->CI->domain_key;
		$response ['system'] = $hotel_engine_system;
		// debug($response);exit;
		return $response;
	}
	private function set_api_credentials() {

		$hotel_engine_system = $this->CI->hotel_engine_system;

        $this->system = $hotel_engine_system;
        $user_name = $this->CI->hotel_engine_system. '_username';
        $password = $this->CI->hotel_engine_system. '_password';
        $this->UserName = $this->CI->$user_name;
        $this->Password = $this->CI->$password;
        $this->Url =  $this->CI->hotel_url;
        $this->ClientId = $this->CI->domain_key;

	}
	function credentials($service) {
		switch ($service) {
			case 'GetHotelResult' :
				$this->service_url = $this->Url . 'Search';
				break;
			case 'GetHotelImages' :
				$this->service_url = $this->Url.'GetHotelImages';
				break;
			case 'GetHotelInfo' :
				$this->service_url = $this->Url . 'HotelDetails';
				break;
			case 'GetHotelRoom' :
				$this->service_url = $this->Url . 'RoomList';
				break;
			case 'BlockRoom' :
				$this->service_url = $this->Url . 'BlockRoom';
				break;
			case 'Book' :
				$this->service_url = $this->Url . 'CommitBooking';
				break;
			case 'GetCancellationCode':
				$this->service_url = $this->Url . 'GetCancellationPolicy';
				break;
			case 'CancelBooking' :
				$this->service_url = $this->Url . 'CancelBooking';
				break;
			case 'CancellationRefundDetails' :
				$this->service_url = $this->Url . 'CancellationRefundDetails';
				break;
			case 'UpdateHoldBooking':
			  $this->service_url = $this->Url .'UpdateHoldBooking';
			  break;
			case 'AgodaBookingList':
				$this->service_url = $this->Url .'AgodaBookingList';
			break;

		}
	}
	
	/**
	 * Balu A
	 *
	 * get hotel search request details
	 * 
	 * @param array $search_params
	 *        	data to be used while searching of hotels
	 */
	private function hotel_search_request($search_params) {


		$response ['status'] = true;
		$response ['data'] = array ();
		$currency_obj = new Currency ( array (
				'module_type' => 'hotel' 
		) );
		/**
		 * Request to be formed for search *
		 */
		$request ['CheckInDate'] = $search_params ['raw_from_date']; // dd/mm/yyyy
		$request ['NoOfNights'] = $search_params ['no_of_nights']; // Min 1
		$request ['CountryCode'] = $search_params ['country_code']; // ISO Country Code of Destination
		$request ['CityId'] = intval ( $search_params ['location_id'] );
		$request ['GuestNationality'] = ISO_INDIA; // ISO Country Code
		$request ['NoOfRooms'] = intval ( $search_params ['room_count'] );
		$request ['search_type'] =  $search_params ['search_type'];
		//$request ['hotel_version'] =  'new';
		if($search_params ['search_type'] == 'location_search'){
			$request ['latitude'] = $search_params ['latitude'];
			$request ['longitude'] = $search_params ['longitude'];
			$request ['radius'] = $search_params ['radius'];
		}
		$room_index = $temp_child_index = 0;
		for($room_index = 0; $room_index < $request ['NoOfRooms']; $room_index ++) {
			$temp_room_config = array();
			$temp_room_config ['NoOfAdults'] = intval ( $search_params ['adult_config'] [$room_index] );
			$temp_room_config ['NoOfChild'] = intval ( $search_params ['child_config'] [$room_index] );
			if ($search_params ['child_config'] [$room_index] > 0) {
				$temp_room_config ['ChildAge'] = array_slice ( $search_params ['child_age'], $temp_child_index, intval ( $search_params ['child_config'] [$room_index] ) );
				$temp_child_index += intval ( $search_params ['child_config'] [$room_index] );
			}
			$request ['RoomGuests'] [] = $temp_room_config;
		}
		// $request ['PreferredHotel'] = '';
		// $request ['MinRating'] = 0;
		// $request ['MaxRating'] = 5;
		// $request ['SortBy'] = 0;
		// $request ['OrderBy'] = 0;
		// debug($request);
		// exit;
		$response ['data'] ['request'] = json_encode ( $request );
		
		$this->credentials ( 'GetHotelResult' );
		$response ['data'] ['service_url'] = $this->service_url;
		
		return $response;
	}
	
	/**
	 * Balu A
	 *
	 * Hotel Details Request
	 * 
	 * @param string $TraceId        	
	 * @param string $ResultIndex        	
	 * @param string $HotelCode        	
	 */
	private function hotel_details_request($ResultToken) {
		$response ['status'] = true;
		$response ['data'] = array ();
		$request ['ResultToken'] = $ResultToken;
		$response ['data'] ['request'] = json_encode ( $request );
		$this->credentials ( 'GetHotelInfo' );
		$response ['data'] ['service_url'] = $this->service_url;
		return $response;
	}
	
	/**
	 * Balu A
	 *
	 * Room Details Request
	 * 
	 * @param string $TraceId        	
	 * @param string $ResultIndex        	
	 * @param string $HotelCode        	
	 */
	private function room_list_request($ResultToken) {
		$response ['status'] = true;
		$response ['data'] = array ();
		$request ['ResultToken'] = $ResultToken;
		$response ['data'] ['request'] = json_encode ( $request );
		$this->credentials ( 'GetHotelRoom' );
		$response ['data'] ['service_url'] = $this->service_url;
		return $response;
	}
	
	/**
	 * Balu A
	 *
	 * get room block request
	 * 
	 * @param array $booking_parameters        	
	 */
	private function get_block_room_request($booking_params)
	{
		
		$number_of_nights = $booking_params ['search_data'] ['no_of_nights'];
		$response ['status'] = true;
		$response ['data'] = array ();
		$request ['ResultToken'] = urldecode($booking_params['ResultIndex']);
		// debug($booking_params);
		// exit;
		foreach ($booking_params['token'] as $tk => $tv){
			$request ['RoomUniqueId'][] = $tv;
		}
		
		$response ['data'] ['request'] = json_encode ( $request );
		$this->credentials ( 'BlockRoom' );
		$response ['data'] ['service_url'] = $this->service_url;
		
		return $response;
	}
	/**
	 * Form Book Request
	 */
	function get_book_request($booking_params, $booking_id)
	{	

		$search_id = $booking_params ['token'] ['search_id'];
		$safe_search_data = $GLOBALS ['CI']->hotel_model->get_search_data ( $search_id );
		$search_data = json_decode ( $safe_search_data ['search_data'], true );
		$number_of_nights = get_date_difference ( date ( 'Y-m-d', strtotime ( $search_data ['hotel_checkin'] ) ), date ( 'Y-m-d', strtotime ( $search_data ['hotel_checkout'] ) ) );
		$NO_OF_ROOMS = $search_data ['rooms'];
		
		$search_params = $this->search_data($search_id);
		$search_params = $search_params['data'];
		
		/*************Re-Assign the Pax Room Wise Strats******************************/
		// debug($booking_params);
		// echo "-----";
		$room_wise_passenger_info = array();
		for($i = 0; $i < $NO_OF_ROOMS; $i ++) {
			
			$room_adult_count = $search_params['adult_config'][$i];
			$room_child_count = $search_params['child_config'][$i];
			
			foreach ($booking_params['name_title'] as $bk => $bv){
				$pax_type = trim($booking_params['passenger_type'][$bk]);
				
				$assigned_pax_type_count = $this->get_assigned_pax_type_count(@$room_wise_passenger_info[$i]['passenger_type'], $pax_type);
				
				if(intval($pax_type) == 1 && intval($assigned_pax_type_count) < intval($room_adult_count)){//Adult
					$room_wise_passenger_info[$i]['name_title'][]			= $booking_params ['name_title'][$bk];
					$room_wise_passenger_info[$i]['first_name'][]			= $booking_params ['first_name'][$bk];
					$room_wise_passenger_info[$i]['middle_name'][]		= $booking_params ['middle_name'][$bk];
					$room_wise_passenger_info[$i]['last_name'][]			= $booking_params ['last_name'][$bk];
					$room_wise_passenger_info[$i]['passenger_contact'][]	= $booking_params ['passenger_contact'];
					$room_wise_passenger_info[$i]['billing_email'][]		= $booking_params ['billing_email'];
					$room_wise_passenger_info[$i]['passenger_type'][]		= $booking_params ['passenger_type'][$bk];
					$room_wise_passenger_info[$i]['date_of_birth'][]		= $booking_params ['date_of_birth'][$bk];
					
					//Remove the pax data from array
					unset($booking_params['name_title'][$bk]);
				
				} else if(intval($pax_type) == 2 && intval($assigned_pax_type_count) < intval($room_child_count)){//Child
					$room_wise_passenger_info[$i]['name_title'][]			= $booking_params ['name_title'][$bk];
					$room_wise_passenger_info[$i]['first_name'][]			= $booking_params ['first_name'][$bk];
					$room_wise_passenger_info[$i]['middle_name'][]		= $booking_params ['middle_name'][$bk];
					$room_wise_passenger_info[$i]['last_name'][]			= $booking_params ['last_name'][$bk];
					$room_wise_passenger_info[$i]['passenger_contact'][]	= $booking_params ['passenger_contact'];
					$room_wise_passenger_info[$i]['billing_email'][]		= $booking_params ['billing_email'];
					$room_wise_passenger_info[$i]['passenger_type'][]		= $booking_params ['passenger_type'][$bk];
					$room_wise_passenger_info[$i]['date_of_birth'][]		= $booking_params ['date_of_birth'][$bk];
					
					//Remove the pax data from array
					unset($booking_params['name_title'][$bk]);
				}
			}
		}
		
		/*************Re-Assign the Pax Room Wise Ends******************************/
		
		
		/* Counting No of adults and childs per room wise */
		for($i = 0; $i < $NO_OF_ROOMS; $i ++) {
			$booking_params ['token'] ['token'] [$i] ['no_of_pax'] = $search_data ['adult'] [$i] + $search_data ['child'] [$i];
		}
		// echo "------";
		
		// echo "-------";
		/* Forming Request */
		$response ['status'] = true;
		$response ['data'] = array ();
		$request ['ResultToken'] = urldecode($booking_params ['token'] ['ResultIndex']);
		$request ['BlockRoomId'] = $booking_params ['token'] ['BlockRoomId'];
		$request ['AppReference'] = trim ( $booking_id ); // Balu A
		$room_details = array ();
		$k = 0;
		for($i = 0; $i < $NO_OF_ROOMS; $i ++) {
			for($j = 0; $j < $booking_params ['token'] ['token'] [$i] ['no_of_pax']; $j ++) {
				
				$pax_list = array (); // Reset Pax List Array
				$pax_title = get_enum_list ( 'title', $room_wise_passenger_info [$i]['name_title'] [$j] );
				$pax_list ['Title'] = $pax_title;
				$pax_list ['FirstName'] = $room_wise_passenger_info [$i] ['first_name'] [$j];
				$pax_list ['MiddleName'] = $room_wise_passenger_info [$i] ['middle_name'] [$j];
				$pax_list ['LastName'] = $room_wise_passenger_info [$i] ['last_name'] [$j];
				$pax_list ['Phoneno'] = $room_wise_passenger_info [$i] ['passenger_contact'][$j];
				$pax_list ['Email'] = $room_wise_passenger_info [$i] ['billing_email'][$j];
				$pax_list ['PaxType'] = $room_wise_passenger_info [$i] ['passenger_type'] [$j];
				
				$pax_lead = false;
				
				if ($j == 0) {
					$pax_lead = true;
				}
				$pax_list ['LeadPassenger'] = $pax_lead;
				/* Age Calculation of Pax */
				$from = new DateTime ( $room_wise_passenger_info [$i]['date_of_birth'] [$j] );
				$to = new DateTime ( 'today' );
				$pax_age = $from->diff ( $to )->y;
				$pax_list ['Age'] = $pax_age;
				$request['RoomDetails'][$i]['PassengerDetails'] [$j] = $pax_list;
				$k ++;
			}
		}
		
		// debug($request);
		// exit;
		$response ['data'] ['request'] = json_encode ( $request );
		$this->credentials ( 'Book' );
		$response ['data'] ['service_url'] = $this->service_url;
		return $response;
	}
	/**
	 * Jagnath
	 * Cancellation Request:SendChangeRequest
	 */
	private function cancel_booking_request_params($app_reference) {
		$response ['status'] = true;
		$response ['data'] = array ();
		$request ['AppReference'] = trim ( $app_reference );
		

		$response ['data'] ['request'] = json_encode ( $request );
		$this->credentials ( 'CancelBooking' );
		$response ['data'] ['service_url'] = $this->service_url;
		// debug($response);
		// exit;
		return $response;
	}
	/**
	 * Jagnath
	 * Cancellation Refund Details
	 */
	private function cancellation_refund_request_params($ChangeRequestId, $app_reference) {
		$response ['status'] = true;
		$response ['data'] = array ();
		$request ['AppReference'] = trim ( $app_reference );
		$request ['ChangeRequestId'] = $ChangeRequestId;
		$response ['data'] ['request'] = json_encode ( $request );
		$this->credentials ( 'CancellationRefundDetails' );
		$response ['data'] ['service_url'] = $this->service_url;
		return $response;
	}
	/**
	* Elavarasi
	*get hotel images
	* @param hotel_code
	*/
	function get_hotel_images($hotel_code){
		$header = $this->get_header ();
		$response ['data'] = array ();
		$response ['status'] = true;
		if($hotel_code!=''){
			
			$this->credentials ( 'GetHotelImages' );
			$url = $this->service_url;
			$request = json_encode(array('hotel_code'=>$hotel_code));

			$image_response = $GLOBALS ['CI']->api_interface->get_json_response ($url, $request, $header );
			if($image_response['Status']==true){
				$response['data'] = $image_response['GetHotelImages'];
			}else{
				$response['status'] = false;
			}

		}else{
			$response['status'] = false;
		}
		return $response;
	}
	/**
	 * Balu A
	 * get search result from tbo
	 * 
	 * @param number $search_id
	 *        	unique id which identifies search details
	 */
	function  cache_merge_search($result=array(),$hotelcount)

	{
		$this->CI->load->driver ( 'cache' );
		$header = $this->get_header ();
		$cache_search = $this->CI->config->item ( 'cache_hotel_search' );
		$search_hash = $this->search_hash;
		$cache_contents = '';
		if ($cache_search) {
			$cache_contents = $this->CI->cache->file->get ( $search_hash );
		}	
		if ($cache_search === false || ($cache_search === true && empty ( $cache_contents ) == true)) {
		
			if($cache_search) {
				$cache_exp = $this->CI->config->item ('cache_hotel_search_ttl');
				$this->CI->cache->file->save ( $search_hash,$result, $cache_exp );
			}
			$this->cache_result_hotel_count ( $hotelcount );
			return false;
		}
		else
		{
			return $cache_contents;
		}
	}
	function get_hotel_list($search_id = '') {
		$this->CI->load->driver ( 'cache' );
		$header = $this->get_header ();
		$response ['data'] = array ();
		$response ['status'] = true;
		$search_data = $this->search_data ( $search_id );
		
		$cache_search = $this->CI->config->item ( 'cache_hotel_search' );
		$search_hash = $this->search_hash;
		$cache_contents = '';
		if ($cache_search) {
			$cache_contents = $this->CI->cache->file->get ( $search_hash );
		}		
		if ($search_data ['status'] == true) {
			if ($cache_search === false || ($cache_search === true && empty ( $cache_contents ) == true)) {
				//echo "not-cahce";

				$search_request = $this->hotel_search_request ( $search_data ['data'] );

				$GLOBALS['CI']->custom_db->generate_static_response(json_encode($search_request));

				if ($search_request ['status']) {					

					$search_response = $GLOBALS['CI']->api_interface->get_json_response ( $search_request ['data'] ['service_url'], $search_request ['data'] ['request'], $header );
					// debug($search_response);exit;
					// exit;
					$GLOBALS['CI']->custom_db->generate_static_response(json_encode($search_response));
					if ($this->valid_search_result ( $search_response )) {
						$response ['data'] = $search_response['Search'];
						// debug($response ['data']);exit;
						if ($cache_search) {
							$cache_exp = $this->CI->config->item ( 'cache_hotel_search_ttl' );
							$this->CI->cache->file->save ( $search_hash, $response ['data'], $cache_exp );
						}
						// Log Hotels Count
						$this->cache_result_hotel_count ( $search_response );
					} else {
						$response ['status'] = false;
					}
				} else {
					$response ['status'] = false;
				}
			} else {
				// read from cache
				//echo "cahce";
				$response ['data'] = $cache_contents;
			}
		} else {
			$response ['status'] = false;
		}
		// debug($response ['data']);exit;
		return $response;
	}
	function get_hotel_list_amenities($search_id = '') {
		$this->CI->load->driver ( 'cache' );
		$header = $this->get_header ();
		$response ['data'] = array ();
		$response ['status'] = true;
		$search_data = $this->search_data ( $search_id );
		
		$cache_search = $this->CI->config->item ( 'cache_hotel_search' );
		$search_hash = $this->search_hash;
		$cache_contents = '';
		if ($cache_search) {
			$cache_contents = $this->CI->cache->file->get ( $search_hash );
		}		
		if ($search_data ['status'] == true) {
			if ($cache_search === false || ($cache_search === true && empty ( $cache_contents ) == true))
			{
				
			}
			else 
			{
				
				$response ['data'] = $cache_contents;
			}
		} else {
			$response ['status'] = false;
		}
		
		return $response;
	}
	/**
	 * Elavarasi
	 * get search result from tbo
	 * 
	 * @param number $search_id
	 *        	unique id which identifies search details
	 */
	function get_hotel_image_list($city_code,$country_code) {
		
		$header = $this->get_header ();
		$response ['data'] = array ();
		$response ['status'] = true;
		//$search_data = $this->search_data ( $search_id );
		
		//$search_request = $this->hotel_search_request ( $search_data ['data'] );
		$search_request['status'] = true;
		$request_arr = [];
		$request_arr['destination_code'] = $city_code;
		$request_arr['checkin'] = '2018-02-01';
		$request_arr['checkout'] = '2018-02-02';
		
		$request_arr['client_nationality'] = $country_code;
		$request_arr['hotel_info'] = false;
		$request_arr['rooms'] =array(array('adults'=>1,'children_ages'=>array()));
		
		$search_request['data']['request'] = json_encode($request_arr);
		
		$search_request['data']['service_url'] = 'https://v3-api.grnconnect.com/api/v3/hotels/availability';
		if ($search_request ['status']) {
			
			$search_response = $GLOBALS ['CI']->api_interface->get_json_image_response( $search_request ['data'] ['service_url'], $search_request ['data'] ['request'], $header ,'post');
			
			
			if (!isset($search_response['errors'])) {
				$response ['data'] = $search_response['hotels'];
				$response ['status'] = true;
				
			} else {
				$response ['status'] = false;
				$response ['data'] = $search_response['errors'];
			}
		
		}
		
		return $response;
	}
	/**
	*Get Hotel Booking Status
	*/
	public function get_hotel_booking_status($app_reference){
		$header = $this->get_header ();
		$response ['data'] = array ();
		$response ['status'] = true;
		//UpdateHoldBooking
		$this->credentials('UpdateHoldBooking');
		 $service_url = $this->service_url;
		 if($app_reference !=''){
		 	$get_hold_booking_request = array('app_reference'=>$app_reference);
		 	$request = json_encode($get_hold_booking_request);
		 	$GLOBALS ['CI']->custom_db->generate_static_response ($request); // release this

		 	$get_hb_status = $GLOBALS['CI']->api_interface->get_json_response ( $service_url,$request, $header );
		 	
		 	$GLOBALS ['CI']->custom_db->generate_static_response ( json_encode ( $get_hb_status ) );
		 	if($get_hb_status['Status']==true){
		 		
		 		//update booking status
		 		$booking_id = $get_hb_status['UpdateHoldBooking']['booking_id'];
		 		$update_data['status'] = 'BOOKING_CONFIRMED';
		 		$update_data['booking_id'] = $booking_id;
		 		$this->CI->custom_db->update_record('hotel_booking_details',$update_data,array('app_reference'=>$app_reference));
		 		$update_ite_data['status'] = 'BOOKING_CONFIRMED';
		 		$this->CI->custom_db->update_record('hotel_booking_itinerary_details',$update_ite_data,array('app_reference'=>$app_reference));
		 		$this->CI->custom_db->update_record('hotel_booking_pax_details',$update_ite_data,array('app_reference'=>$app_reference));
		 		$response ['data'] = array('booking_id'=>$booking_id);
		 		$response['status'] = true;
		 		
		 	}else{
		 		$response['status'] = false;
		 	}
		 }
		 return $response;

	}
	/**
	 * Converts API data currency to preferred currency
	 * Balu A
	 * 
	 * @param unknown_type $search_result        	
	 * @param unknown_type $currency_obj        	
	 */
	public function search_data_in_preferred_currency($search_result, $currency_obj,$search_id) {
		$hotels = $search_result ['data'] ['HotelSearchResult'] ['HotelResults'];
		$hotel_list = array ();
		foreach ( $hotels as $hk => $hv ) {
			$hotel_list [$hk] = $hv;
			
			//Update Markup price in search result			
			
			//$Price =  $this->update_search_markup_currency ($hv ['Price'], $currency_obj, $search_id, false, true );	

			$hotel_list [$hk] ['Price'] = $this->preferred_currency_fare_object ($hv ['Price'], $currency_obj );	
			

		}
		$search_result ['data'] ['HotelSearchResult'] ['PreferredCurrency'] = get_application_currency_preference ();
		$search_result ['data'] ['HotelSearchResult'] ['HotelResults'] = $hotel_list;
		
		return $search_result;
	}
	/**
	 * Balu A
	 * 
	 * @param unknown_type $fare_details        	
	 * @param unknown_type $currency_obj        	
	 */
	private function preferred_currency_fare_object($fare_details, $currency_obj, $default_currency = '') {
		$price_details = array ();
		
//debug($fare_details);
		$price_details ['CurrencyCode'] = empty ( $default_currency ) == false ? $default_currency : get_application_currency_preference ();

		$price_details ['RoomPrice'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['RoomPrice'] ) );

		$price_details ['Tax'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['Tax'] ) );

		$price_details ['ExtraGuestCharge'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['ExtraGuestCharge'] ) );

		$price_details ['ChildCharge'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['ChildCharge'] ) );
		$price_details ['OtherCharges'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['OtherCharges'] ) );
		$price_details ['Discount'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['Discount'] ) );
		$price_details ['PublishedPrice'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['PublishedPrice'] ) );
		$price_details ['PublishedPriceRoundedOff'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['PublishedPriceRoundedOff'] ) );
		$price_details ['OfferedPrice'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['OfferedPrice'] ) );
		$price_details ['OfferedPriceRoundedOff'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['OfferedPriceRoundedOff'] ) );
		$price_details ['AgentCommission'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['AgentCommission'] ) );
		$price_details ['AgentMarkUp'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['AgentMarkUp'] ) );
		$price_details ['ServiceTax'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['ServiceTax'] ) );
		$price_details ['TDS'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['TDS'] ) );
		//debug($price_details);exit;
		return $price_details;
	}
	/**
	 * Balu A
	 * Converts Display currency to application currency
	 * 
	 * @param unknown_type $fare_details        	
	 * @param unknown_type $currency_obj        	
	 * @param unknown_type $module        	
	 */
	public function convert_token_to_application_currency($token, $currency_obj, $module) {
		$master_token = array ();
		$price_token = array ();
		$price_summary = array ();
		$markup_price_summary = array ();
		// Price Token
		foreach ( $token ['price_token'] as $ptk => $ptv ) {
			$price_token [$ptk] = $this->preferred_currency_fare_object ( $ptv, $currency_obj, admin_base_currency () );
		}
		// Price Summary
		$price_summary = $this->preferred_currency_price_summary ( $token ['price_summary'], $currency_obj );
		// Markup Price Summary
		$markup_price_summary = $this->preferred_currency_price_summary ( $token ['markup_price_summary'], $currency_obj );
		// Assigning the Converted Data
		$master_token = $token;
		$master_token ['price_token'] = $price_token;
		$master_token ['price_summary'] = $price_summary;
		$master_token ['markup_price_summary'] = $markup_price_summary;
		$master_token ['default_currency'] = admin_base_currency ();
		$master_token ['convenience_fees'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $token ['convenience_fees'] ) ); // check this
		return $master_token;
	}
	/**
	 * Balu A
	 * Converts Price summary to application curency
	 * 
	 * @param unknown_type $fare_details        	
	 * @param unknown_type $currency_obj        	
	 */
	private function preferred_currency_price_summary($fare_details, $currency_obj) {
		$price_details = array ();
		$price_details ['RoomPrice'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['RoomPrice'] ) );
		$price_details ['PublishedPrice'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['PublishedPrice'] ) );
		$price_details ['PublishedPriceRoundedOff'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['PublishedPriceRoundedOff'] ) );
		$price_details ['OfferedPrice'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['OfferedPrice'] ) );
		$price_details ['OfferedPriceRoundedOff'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['OfferedPriceRoundedOff'] ) );
		$price_details ['ServiceTax'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['ServiceTax'] ) );
		$price_details ['Tax'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['Tax'] ) );
		$price_details ['ExtraGuestCharge'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['ExtraGuestCharge'] ) );
		$price_details ['ChildCharge'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['ChildCharge'] ) );
		$price_details ['OtherCharges'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['OtherCharges'] ) );
		$price_details ['TDS'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['TDS'] ) );
		return $price_details;
	}

	function cache_result_hotel_count($response) {
		$CI = & get_instance ();
		$city_id = @$response['Search']['HotelSearchResult'] ['CityId'];
		$hotel_count = intval ( count ( @$response['Search']['HotelSearchResult'] ['HotelResults'] ) );
		if ($hotel_count > 0 && $city_id !='') {			
			$CI->custom_db->update_record ('all_api_city_master', array (
					'cache_hotels_count' => $hotel_count 
			), array (
					'origin' => $city_id 
			) );
		}
	}
	/**
	*Get cancellation details by cancellation policy code (GRN CONNECT)
	*/
	public function get_cancellation_details($get_params){
		if($get_params){
			
			$request_rate_data = json_encode($get_params);
			//echo $request_rate_data;
			$header = $this->get_header ();
			$this->credentials('GetCancellationCode');		
			$response['data'] = array();			
			$cancel_url = $this->service_url;			
			$cancel_data_arr = array();
			$cancellation_details = array();			
			$cancellation_policy = $GLOBALS ['CI']->api_interface->get_json_response ( $cancel_url, $request_rate_data, $header );
			return $cancellation_policy;
		}
	}
	/**
	 * Balu A
	 * get Room List for selected hotel
	 * 
	 * @param string $TraceId        	
	 * @param number $ResultIndex        	
	 * @param string $HotelCode        	
	 */
	function get_room_list($ResultToken) {
		$header = $this->get_header ();
		$response ['data'] = array ();
		$response ['status'] = false;
		$hotel_room_request = $this->room_list_request ($ResultToken);
		if ($hotel_room_request ['status']) {
			// get the response for hotel details
			$hotel_room_list_response = $GLOBALS ['CI']->api_interface->get_json_response ( $hotel_room_request ['data'] ['service_url'], $hotel_room_request ['data'] ['request'], $header );
			$GLOBALS ['CI']->custom_db->generate_static_response ( json_encode ( $hotel_room_list_response ) );
			// debug($hotel_room_list_response);exit;
			/*
			 * $static_search_result_id = 813;//106;//68;//52;
			 * $hotel_room_list_response = $GLOBALS['CI']->hotel_model->get_static_response($static_search_result_id);
			 */
			if ($this->valid_room_details_details ( $hotel_room_list_response )) {
				$response ['data'] = $hotel_room_list_response['RoomList'];
				$response ['status'] = true;
			} else {
				$response ['data'] = $hotel_room_list_response;
			}
		}
		return $response;
	}
	/**
	 * Balu A
	 * 
	 * @param unknown_type $room_list        	
	 * @param unknown_type $currency_obj        	
	 */
	public function roomlist_in_preferred_currency($room_list, $currency_obj,$search_id,$module='b2c') {

		$level_one = true;
		$current_domain = true;
		if ($module == 'b2c') {
			$level_one = false;
			$current_domain = true;
		} else if ($module == 'b2b') {
			$level_one = true;
			$current_domain = true;
		}

		$application_currency_preference = get_application_currency_preference ();
		$hotel_room_details = $room_list ['data'] ['GetHotelRoomResult'] ['HotelRoomsDetails'];
		$hotel_room_result = array ();
		foreach ( $hotel_room_details as $hr_k => $hr_v ) {
			$hotel_room_result [$hr_k] = $hr_v;
			// Price
			$API_raw_price = $hr_v ['Price'];
			
			$Price = $this->preferred_currency_fare_object ( $hr_v ['Price'], $currency_obj );
			// CancellationPolicies
			$CancellationPolicies = array ();
			foreach ( $hr_v ['CancellationPolicies'] as $ck => $cv ) {
				//add cancellation charge in markup
				
				$Charge = $this->update_cancellation_markup_currency($cv['Charge'],$currency_obj,$search_id,$level_one,$current_domain);
				
				$CancellationPolicies [$ck] = $cv;
				$CancellationPolicies [$ck] ['Currency'] = $application_currency_preference;
				//$CancellationPolicies [$ck] ['Charge'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $Charge ) );
				$CancellationPolicies [$ck] ['Charge'] = $Charge;

			}
			$hotel_room_result [$hr_k] ['API_raw_price'] = $API_raw_price;
			$hotel_room_result [$hr_k] ['Price'] = $Price;
			$hotel_room_result [$hr_k] ['CancellationPolicies'] = $CancellationPolicies;
			// CancellationPolicy:FIXME: convert the INR price to preferred currency
		}
		$room_list ['data'] ['GetHotelRoomResult'] ['HotelRoomsDetails'] = $hotel_room_result;
		return $room_list;
	}
	/**
	 * Balu A
	 * 
	 * @param unknown_type $block_room_data        	
	 * @param unknown_type $currency_obj        	
	 */
	public function roomblock_data_in_preferred_currency($block_room_data, $currency_obj,$search_id,$module='b2c') {
		$level_one = true;
		$current_domain = true;
		if ($module == 'b2c') {
			$level_one = false;
			$current_domain = true;
		} else if ($module == 'b2b') {
			$level_one = true;
			$current_domain = true;
		}
		$application_currency_preference = get_application_currency_preference ();
		$hotel_room_details = $block_room_data ['data'] ['response'] ['BlockRoomResult'] ['HotelRoomsDetails'];
		$hotel_room_result = array ();
		foreach ( $hotel_room_details as $hr_k => $hr_v ) {
			$hotel_room_result [$hr_k] = $hr_v;
			
			// Price
			$API_raw_price = $hr_v ['Price'];
			$Price = $this->preferred_currency_fare_object ( $hr_v ['Price'], $currency_obj );
			// CancellationPolicies
			$CancellationPolicies = array ();
			foreach ( $hr_v ['CancellationPolicies'] as $ck => $cv ) {

				$Charge = $this->update_cancellation_markup_currency($cv['Charge'],$currency_obj,$search_id,$level_one,$current_domain);
				

				$CancellationPolicies [$ck] = $cv;
				$CancellationPolicies [$ck] ['Currency'] = $application_currency_preference;
				$CancellationPolicies [$ck] ['Charge'] = $Charge ;
				//$CancellationPolicies [$ck] ['Charge'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $Charge ) );
			}
			$hotel_room_result [$hr_k] ['API_raw_price'] = $API_raw_price;
			$hotel_room_result [$hr_k] ['Price'] = $Price;
			$hotel_room_result [$hr_k] ['CancellationPolicies'] = $CancellationPolicies;
			// CancellationPolicy:FIXME: convert the INR price to preferred currency
		}
		$block_room_data ['data'] ['response'] ['BlockRoomResult'] ['HotelRoomsDetails'] = $hotel_room_result;
		
		return $block_room_data;
	}
	/**
	 * Balu A
	 * Load Hotel Details
	 *
	 * @param string $TraceId
	 *        	Trace ID of hotel found in search result response
	 * @param number $ResultIndex
	 *        	Result index generated for each hotel by hotel search
	 * @param string $HotelCode
	 *        	unique id which identifies hotel
	 *        	
	 * @return array having status of the operation and resulting data in case if operaiton is successfull
	 */
	function get_hotel_details($ResultIndex) {
		$header = $this->get_header ();
		$response ['data'] = array ();
		$response ['status'] = false;
		$hotel_details_request = $this->hotel_details_request ($ResultIndex);
		// debug($hotel_details_request);exit;
		if ($hotel_details_request ['status']) {
			// get the response for hotel details
			$hotel_details_response = $GLOBALS ['CI']->api_interface->get_json_response ( $hotel_details_request ['data'] ['service_url'], $hotel_details_request ['data'] ['request'], $header );
			// debug($hotel_details_response);exit;
			$GLOBALS ['CI']->custom_db->generate_static_response ( json_encode ( $hotel_details_response ) );
		
			/*
			 * $static_search_result_id = 812;//105;//67;//49;
			 * $hotel_details_response = $GLOBALS['CI']->hotel_model->get_static_response($static_search_result_id);
			 */
			if ($this->valid_hotel_details ( $hotel_details_response )) {
				$response ['data'] = $hotel_details_response['HotelDetails'];
				$response ['status'] = true;
			} else {
				$response ['data'] = $hotel_details_response;
			}
		}
		return $response;
	}
	
	/**
	 * Balu A
	 * Block Room Before Going for payment and showing final booking page to user - TBO rule
	 * 
	 * @param array $pre_booking_params
	 *        	All the necessary data required in block room request - fetched from roomList and hotelDetails Request
	 */
	function block_room($pre_booking_params) {
		$header = $this->get_header ();
		$response ['status'] = false;
		$response ['data'] = array ();
		$search_data = $this->search_data ( $pre_booking_params ['search_id'] );
		$run_block_room_request = true;
		$block_room_request_count = 0;
		$pre_booking_params ['search_data'] = $search_data ['data'];
		$block_room_request = $this->get_block_room_request ( $pre_booking_params );
		$application_default_currency = admin_base_currency ();
		if ($block_room_request ['status'] == ACTIVE) {
			while ( $run_block_room_request ) {
				$GLOBALS ['CI']->custom_db->generate_static_response ( json_encode ( $block_room_request ['data'] ['request'] ) );
				$block_room_response = $GLOBALS ['CI']->api_interface->get_json_response ( $block_room_request ['data'] ['service_url'], $block_room_request ['data'] ['request'], $header );
				// debug($block_room_response);
				// exit;
				$GLOBALS ['CI']->custom_db->generate_static_response ( json_encode ( $block_room_response ) );
				
				$api_block_room_response_status = $block_room_response['Status'];
				$block_room_response = $block_room_response['BlockRoom'];
				
				if ($this->valid_response ($api_block_room_response_status) == false) {
					$run_block_room_request = false;
					$response ['status'] = false; // Indication for room block
					$response ['data'] ['msg'] = 'Some Problem Occured. Please Search Again to continue';
				} elseif ($this->is_room_blocked ( $block_room_response ) == true) {
					$run_block_room_request = false;
					$response ['status'] = true; // Indication for room block
				} else {
					
					// UPDATE RECURSSION
					// Reset pre booking params token and get new values
					$dynamic_params_url = '';
					// FIXME: Do All currency conversion after API call
					// Converting API currency data to preferred currency
					$temp_block_room_details ['data'] ['response'] = $block_room_response;
					$currency_obj = new Currency ( array (
							'module_type' => 'hotel',
							'from' => get_api_data_currency (),
							'to' => get_application_currency_preference () 
					) );

					$temp_block_room_details = $this->roomblock_data_in_preferred_currency ( $temp_block_room_details, $currency_obj );
					$temp_block_room_details = $temp_block_room_details['BlockRoomResult'] ['HotelRoomsDetails'];
					$_HotelRoomsDetails = get_room_index_list ( $temp_block_room_details );
					
					// $_HotelRoomsDetails = get_room_index_list($block_room_response['BlockRoomResult']['HotelRoomsDetails']);
					foreach ( $_HotelRoomsDetails as $___tk => $___tv ) {
						$dynamic_params_url [] = get_dynamic_booking_parameters ( $___tk, $___tv, $application_default_currency );
					}
					// update token key
					$pre_booking_params ['token'] = $dynamic_params_url;
					$pre_booking_params ['token_key'] = md5 ( serialized_data ( $dynamic_params_url ) );
					$block_room_request = $this->get_block_room_request ( $pre_booking_params );
				}
				$block_room_request_count ++; // Increment number of times request is run
				if ($block_room_request_count == 3 && $run_block_room_request == true) {
					// try max 3times to block the room
					$run_block_room_request = false;
				}
			}
			$response ['data'] ['response'] = $block_room_response;
		}
		//debug($response);exit;
		return $response;
	}
	public function update_booking_details($book_id, $book_params, $ticket_details,$currency_obj,$module = 'b2c') {
		$app_reference = $book_id;
        $master_search_id = $book_params['booking_params']['token']['search_id'];
        //Setting Master Booking Status
        $master_transaction_status = $this->status_code_value($ticket_details['master_booking_status']);
        if (isset($ticket_details['TicketDetails']) == true && valid_array($ticket_details['TicketDetails']) == true) {
            $ticket_details = $ticket_details['TicketDetails'];
        } else {
            $ticket_details = array();
        }
        $saved_booking_data = $GLOBALS['CI']->hotel_model->get_booking_details($book_id);
        //debug($saved_booking_data);exit;
        if ($saved_booking_data['status'] == false) {
            $response['status'] = BOOKING_ERROR;
            $response['msg'] = 'No Data Found';
            return $response;
        }
        $s_master_data = $saved_booking_data['data']['booking_details'][0];
        $s_booking_itinerary_details = $saved_booking_data['data']['booking_itinerary_details'];
        $s_booking_customer_details = $saved_booking_data['data']['booking_customer_details'];
        $passenger_origins = group_array_column($s_booking_customer_details, 'origin');
        $itinerary_origins = group_array_column($s_booking_itinerary_details, 'origin');
        $hotel_master_booking_status = $master_transaction_status;
        $transaction_currency = get_application_currency_preference();
        $application_currency = admin_base_currency();
        $currency_conversion_rate = $currency_obj->transaction_currency_conversion_rate();
        $booking_id = $book_params['book_response']['BookResult']['BookingId'];
        $BookingRefNo = $book_params['book_response']['BookResult']['BookingRefNo'];
        $ConfirmationNo = $book_params['book_response']['BookResult']['ConfirmationNo'];
        $GLOBALS['CI']->custom_db->update_record('hotel_booking_details', array('status' => $master_transaction_status,'booking_id' => $booking_id,'booking_reference' => $BookingRefNo,'confirmation_reference' => $ConfirmationNo), array('app_reference' => $app_reference));
        //debug('hiiiiiiii');exit;
        $total_pax_count = count($book_params['booking_params']['passenger_type']);
        $pax_count = $total_pax_count;
        $search_data = $this->search_data ( $master_search_id );
        $no_of_nights = intval ( $search_data ['data'] ['no_of_nights'] );
        $HotelRoomsDetails = force_multple_data_format ( $book_params['booking_params'] ['token']['token'] );
        $total_room_count = count ( $HotelRoomsDetails );
        //debug($total_room_count);exit;
        $book_total_fare = $book_params['booking_params'] ['token'] ['price_token'] [0] ['OfferedPriceRoundedOff']; // (TAX+ROOM PRICE)
        $room_price = $book_params['booking_params'] ['token'] ['price_token'] [0] ['RoomPrice'];
        $deduction_cur_obj = clone $currency_obj;

        if ($module == 'b2c') {
			$markup_total_fare = $currency_obj->get_currency ( $book_total_fare, true, false, true, $no_of_nights * $total_room_count ); // (ON Total PRICE ONLY)
			$ded_total_fare = $deduction_cur_obj->get_currency ( $book_total_fare, true, true, false, $no_of_nights * $total_room_count ); // (ON Total PRICE ONLY)
			$admin_markup = sprintf ( "%.2f", $markup_total_fare ['default_value'] - $ded_total_fare ['default_value'] );
			$agent_markup = sprintf ( "%.2f", $ded_total_fare ['default_value'] - $book_total_fare );
		} else {
			// B2B Calculation
			$markup_total_fare = $currency_obj->get_currency ( $book_total_fare, true, true, false, $no_of_nights * $total_room_count ); // (ON Total PRICE ONLY)
			$ded_total_fare = $deduction_cur_obj->get_currency ( $book_total_fare, true, false, true, $no_of_nights * $total_room_count ); // (ON Total PRICE ONLY)
			$admin_markup = sprintf ( "%.2f", $markup_total_fare ['default_value'] - $ded_total_fare ['default_value'] );
			$agent_markup = sprintf ( "%.2f", $ded_total_fare ['default_value'] - $book_total_fare );
		}
		//debug($markup_total_fare);exit;
		foreach ( $HotelRoomsDetails as $k => $v ){
			$room_type_name = $v ['RoomTypeName'];
			$bed_type_code = $v ['RoomTypeCode'];
			$smoking_preference = get_smoking_preference ( $v ['SmokingPreference'] );
			$smoking_preference = $smoking_preference ['label'];
			$total_fare = $v ['OfferedPriceRoundedOff'];
			$room_price = $v ['RoomPrice'];
			$gst_value = 0;
			if ($module == 'b2c') {
				$markup_total_fare = $currency_obj->get_currency ( $total_fare, true, false, true, $no_of_nights ); // (ON Total PRICE ONLY)
				$ded_total_fare = $deduction_cur_obj->get_currency ( $total_fare, true, true, false, $no_of_nights ); // (ON Total PRICE ONLY)
				$admin_markup = sprintf ( "%.2f", $markup_total_fare ['default_value'] - $ded_total_fare ['default_value'] );
				$agent_markup = sprintf ( "%.2f", $ded_total_fare ['default_value'] - $total_fare );
				//adding gst
		        if($admin_markup > 0 ){
		            $gst_details = $GLOBALS['CI']->custom_db->single_table_records('gst_master', '*', array('module' => 'hotel'));
		            if($gst_details['status'] == true){
		                if($gst_details['data'][0]['gst'] > 0){
		                    $gst_value = ($admin_markup/100) * $gst_details['data'][0]['gst'];
		                	$gst_value  = roundoff_number($gst_value);
		                }
		            }
		        }
			}else {
				// B2B Calculation - Room wise price
                            //echo 'total_fare',debug($total_fare);
				$markup_total_fare = $currency_obj->get_currency ( $total_fare, true, true, false, $no_of_nights ); // (ON Total PRICE ONLY)
                                $ded_total_fare = $deduction_cur_obj->get_currency(($markup_total_fare ['default_value']), true, false, true, $no_of_nights ); // (ON Total PRICE ONLY)
                $admin_markup = sprintf ( "%.2f", $markup_total_fare ['default_value'] -  $total_fare);
				$agent_markup = sprintf ( "%.2f", $ded_total_fare ['default_value'] - $markup_total_fare ['default_value']);
                $markup = $admin_markup+$agent_markup;             
				//adding gst
		        if($markup > 0 ){
		            $gst_details = $GLOBALS['CI']->custom_db->single_table_records('gst_master', '*', array('module' => 'hotel'));
		            if($gst_details['status'] == true){
		                if($gst_details['data'][0]['gst'] > 0){
		                    $gst_value = ($markup/100) * $gst_details['data'][0]['gst'];
		                	$gst_value  = roundoff_number($gst_value);
		                }
		            }
		        }
			}
			$total_fare_markup = round($book_total_fare+$admin_markup+$gst_value);
			$GLOBALS['CI']->custom_db->update_record('hotel_booking_itinerary_details', array('status' => $master_transaction_status), array('app_reference' => $app_reference));
		$GLOBALS['CI']->custom_db->update_record('hotel_booking_pax_details', array('status' => $master_transaction_status), array('app_reference' => $app_reference));       
		}
		$convinence = 0;
		$discount = 0;
		$convinence_value = 0;
		$convinence_type = 0;
		$convinence_per_pax = 0;
		if ($module == 'b2c') {
			$convinence = $currency_obj->convenience_fees ( $total_fare_markup, $master_search_id );
			$convinence_row = $currency_obj->get_convenience_fees ();
			$convinence_value = $convinence_row ['value'];
			$convinence_type = $convinence_row ['type'];
			$convinence_per_pax = $convinence_row ['per_pax']; 
			if($book_params['booking_params']['promo_actual_value']){
				$discount = get_converted_currency_value ( $promo_currency_obj->force_currency_conversion ( $book_params['booking_params']['promo_actual_value']) );
			}			
			//$discount = @$params ['booking_params'] ['promo_code_discount_val'];
			$promo_code = @$book_params ['booking_params'] ['promo_code'];
		} elseif ($module == 'b2b') {
			$discount = 0;
		}
		$response ['fare'] = $book_total_fare;
		$response ['admin_markup'] = $admin_markup;
		$response ['agent_markup'] = $agent_markup;
		$response ['convinence'] = $convinence;
		$response ['discount'] = $discount;
		$response ['transaction_currency'] = $transaction_currency;
		$response ['currency_conversion_rate'] = $currency_conversion_rate;
		//booking_status
		$response['booking_status'] = $master_transaction_status;
		return $response;
	}

	private function status_code_value($status_code) {
        switch ($status_code) {
            case BOOKING_CONFIRMED:
            case SUCCESS_STATUS:
                $status_value = 'BOOKING_CONFIRMED';
                break;
            case BOOKING_HOLD:
                $status_value = 'BOOKING_HOLD';
                break;
            default:
                $status_value = 'BOOKING_FAILED';
        }
        return $status_value;
    }

	/**
	 *
	 * @param array $booking_params        	
	 */
	function process_booking($book_id, $booking_params)
	{
		// debug($booking_params);exit;
		$header = $this->get_header ();
		$response ['status'] = FAILURE_STATUS;
		$response ['data'] = array ();	
		$book_request = $this->get_book_request ( $booking_params, $book_id );		
		// booking request
		$GLOBALS ['CI']->custom_db->generate_static_response ( $book_request ['data'] ['request'] ); // release this
	

     	$book_response = $GLOBALS ['CI']->api_interface->get_json_response ( $book_request ['data'] ['service_url'], $book_request ['data'] ['request'], $header );
     

		$GLOBALS ['CI']->custom_db->generate_static_response ( json_encode ( $book_response ) );		
		
		$api_book_response_status = $book_response['Status'];
		$book_response['BookResult'] = @$book_response['CommitBooking']['BookingDetails'];
		/**
		 * PROVAB LOGGER *
		 */
		$GLOBALS ['CI']->private_management_model->provab_xml_logger ( 'Book_Room', $book_id, 'hotel', $book_request ['data'] ['request'], json_encode ( $book_response ) );
		// validate response
		if ($this->valid_response ( $api_book_response_status )) {
			$response ['status'] = SUCCESS_STATUS;
			$response ['data'] ['book_response'] = $book_response;
			$response ['data'] ['booking_params'] = $booking_params;
			// $response['data']['room_book_data'] = json_decode($block_data_array, true);
			// Convert Room Book Data in Application Currency
			$block_data_array = $book_request ['data'] ['request'];
			$room_book_data = json_decode ( $block_data_array, true );
			$room_book_data['HotelRoomsDetails'] = $this->formate_hotel_room_details($booking_params);
			
			$response ['data'] ['room_book_data'] = $this->convert_roombook_data_to_application_currency ( $room_book_data );
		}
		else{
			$response ['data']['message'] = $book_response['Message'];
		}
		// debug($response);exit;
		return $response;
	}
	/**
	 * Formates Hotel Room Details
	 * @param unknown_type $booking_params
	 */
	private function formate_hotel_room_details($booking_params)
	{
		// debug($booking_params);exit;
		$search_id = $booking_params ['token'] ['search_id'];
		$safe_search_data = $GLOBALS ['CI']->hotel_model->get_search_data ( $search_id );
		$search_data = json_decode ( $safe_search_data ['search_data'], true );
		$number_of_nights = get_date_difference ( date ( 'Y-m-d', strtotime ( $search_data ['hotel_checkin'] ) ), date ( 'Y-m-d', strtotime ( $search_data ['hotel_checkout'] ) ) );
		$NO_OF_ROOMS = $search_data ['rooms'];
		$k = 0;
	
		
		$HotelRoomsDetails = array();
		/* Counting No of adults and childs per room wise */
		for($i = 0; $i < $NO_OF_ROOMS; $i ++) {
			$booking_params ['token'] ['token'] [$i] ['no_of_pax'] = $search_data ['adult'] [$i] + $search_data ['child'] [$i];
		}
		for($i = 0; $i < $NO_OF_ROOMS; $i ++) {
			$room_detail = array ();
			$room_detail ['RoomIndex'] = $booking_params ['token'] ['token'] [$i] ['RoomIndex'];
			$room_detail ['RatePlanCode'] = $booking_params ['token'] ['token'] [$i] ['RatePlanCode'];
			$room_detail ['RatePlanName'] = $booking_params ['token'] ['token'] [$i] ['RatePlanName'];
			$room_detail ['RoomTypeCode'] = $booking_params ['token'] ['token'] [$i] ['RoomTypeCode'];
			$room_detail ['RoomTypeName'] = $booking_params ['token'] ['token'] [$i] ['RoomTypeName'];
			$room_detail ['SmokingPreference'] = 0;
			
			$room_detail ['Price'] ['CurrencyCode'] = $booking_params ['token'] ['token'] [$i] ['CurrencyCode'];
			$room_detail ['Price'] ['RoomPrice'] = $booking_params ['token'] ['token'] [$i] ['RoomPrice'];
			$room_detail ['Price'] ['Tax'] = $booking_params ['token'] ['token'] [$i] ['Tax'];
			$room_detail ['Price'] ['ExtraGuestCharge'] = $booking_params ['token'] ['token'] [$i] ['ExtraGuestCharge'];
			$room_detail ['Price'] ['ChildCharge'] = $booking_params ['token'] ['token'] [$i] ['ChildCharge'];
			$room_detail ['Price'] ['OtherCharges'] = $booking_params ['token'] ['token'] [$i] ['OtherCharges'];
			$room_detail ['Price'] ['Discount'] = $booking_params ['token'] ['token'] [$i] ['Discount'];
			$room_detail ['Price'] ['PublishedPrice'] = $booking_params ['token'] ['token'] [$i] ['PublishedPrice'];
			$room_detail ['Price'] ['PublishedPriceRoundedOff'] = $booking_params ['token'] ['token'] [$i] ['PublishedPriceRoundedOff'];
			$room_detail ['Price'] ['OfferedPrice'] = $booking_params ['token'] ['token'] [$i] ['OfferedPrice'];
			$room_detail ['Price'] ['OfferedPriceRoundedOff'] = $booking_params ['token'] ['token'] [$i] ['OfferedPriceRoundedOff'];
			$room_detail ['Price'] ['SmokingPreference'] = $booking_params ['token'] ['token'] [$i] ['SmokingPreference'];
			$room_detail ['Price'] ['ServiceTax'] = $booking_params ['token'] ['token'] [$i] ['ServiceTax'];
			$room_detail ['Price'] ['Tax'] = $booking_params ['token'] ['token'] [$i] ['Tax'];
			$room_detail ['Price'] ['ExtraGuestCharge'] = $booking_params ['token'] ['token'] [$i] ['ExtraGuestCharge'];
			$room_detail ['Price'] ['ChildCharge'] = $booking_params ['token'] ['token'] [$i] ['ChildCharge'];
			$room_detail ['Price'] ['OtherCharges'] = $booking_params ['token'] ['token'] [$i] ['OtherCharges'];
			$room_detail ['Price'] ['Discount'] = $booking_params ['token'] ['token'] [$i] ['Discount'];
			$room_detail ['Price'] ['AgentCommission'] = $booking_params ['token'] ['token'] [$i] ['AgentCommission'];
			$room_detail ['Price'] ['AgentMarkUp'] = $booking_params ['token'] ['token'] [$i] ['AgentMarkUp'];
			$room_detail ['Price'] ['TDS'] = $booking_params ['token'] ['token'] [$i] ['TDS'];
			$HotelRoomsDetails[$i] = $room_detail;
			
			for($j = 0; $j < $booking_params ['token'] ['token'] [$i] ['no_of_pax']; $j ++) {
				$pax_list = array (); // Reset Pax List Array
				$pax_title = get_enum_list ( 'title', $booking_params ['name_title'] [$k] );
				$pax_list ['Title'] = $pax_title;
				$pax_list ['FirstName'] = $booking_params ['first_name'] [$k];
				$pax_list ['MiddleName'] = $booking_params ['middle_name'] [$k];
				$pax_list ['LastName'] = $booking_params ['last_name'] [$k];
				$pax_list ['Phoneno'] = $booking_params ['passenger_contact'];
				$pax_list ['Email'] = $booking_params ['billing_email'];
				$pax_list ['PaxType'] = $booking_params ['passenger_type'] [$k];
				
				$pax_lead = false;
				// temp
				if ($j == 0) {
					$pax_lead = true;
				}
				$pax_list ['LeadPassenger'] = $pax_lead;
				/* Age Calculation of Pax */
				$from = new DateTime ( $booking_params ['date_of_birth'] [$k] );
				$to = new DateTime ( 'today' );
				$pax_age = $from->diff ( $to )->y;
				$pax_list ['Age'] = $pax_age;
				$HotelRoomsDetails[$i] ['HotelPassenger'] [$j] = $pax_list;
				$k ++;
			}
		}
		return $HotelRoomsDetails;
	}
	/**
	 * Reference number generated for booking from application
	 * 
	 * @param
	 *        	$app_booking_id
	 * @param
	 *        	$params
	 */	
	function save_booking_new($app_booking_id, $book_params, $currency_obj, $module = 'b2c'){
		error_reporting(0);
		$response ['fare'] = $response ['domain_markup'] = $response ['level_one_markup'] = 0;
		$book_total_fare = array();
        $book_domain_markup = array();
        $book_level_one_markup = array();
        $status = 'BOOKING_INPROGRESS';
        $master_search_id = $book_params['search_id'];

        $domain_origin = get_domain_auth_id();
        $app_reference = $app_booking_id;
        $booking_source = $book_params['token']['booking_source'];
        $deduction_cur_obj = clone $currency_obj;
        $total_pax_count = count($book_params['passenger_type']);
        $pax_count = $total_pax_count;
        //debug($pax_count);exit;
        //PREFERRED TRANSACTION CURRENCY AND CURRENCY CONVERSION RATE 
        $transaction_currency = get_application_currency_preference();
        $application_currency = admin_base_currency();
        $currency_conversion_rate = $currency_obj->transaction_currency_conversion_rate();
        $search_data = $this->search_data ( $master_search_id );
        $no_of_nights = intval ( $search_data ['data'] ['no_of_nights'] );
        //debug($search_data);exit;
        $HotelRoomsDetails = force_multple_data_format ( $book_params ['token']['token'] );
        $total_room_count = count ( $HotelRoomsDetails );
        //debug($total_room_count);exit;
        $book_total_fare = $book_params ['token'] ['price_token'] [0] ['OfferedPriceRoundedOff']; // (TAX+ROOM PRICE)
        $room_price = $book_params ['token'] ['price_token'] [0] ['RoomPrice'];

        if ($module == 'b2c') {
			$markup_total_fare = $currency_obj->get_currency ( $book_total_fare, true, false, true, $no_of_nights * $total_room_count ); // (ON Total PRICE ONLY)
			$ded_total_fare = $deduction_cur_obj->get_currency ( $book_total_fare, true, true, false, $no_of_nights * $total_room_count ); // (ON Total PRICE ONLY)
			$admin_markup = sprintf ( "%.2f", $markup_total_fare ['default_value'] - $ded_total_fare ['default_value'] );
			$agent_markup = sprintf ( "%.2f", $ded_total_fare ['default_value'] - $book_total_fare );
		} else {
			// B2B Calculation
			$markup_total_fare = $currency_obj->get_currency ( $book_total_fare, true, true, false, $no_of_nights * $total_room_count ); // (ON Total PRICE ONLY)
			$ded_total_fare = $deduction_cur_obj->get_currency ( $book_total_fare, true, false, true, $no_of_nights * $total_room_count ); // (ON Total PRICE ONLY)
			$admin_markup = sprintf ( "%.2f", $markup_total_fare ['default_value'] - $ded_total_fare ['default_value'] );
			$agent_markup = sprintf ( "%.2f", $ded_total_fare ['default_value'] - $book_total_fare );
		}
		//debug($markup_total_fare);exit;
		$currency = $book_params ['token'] ['default_currency'];
		$hotel_name = $book_params ['token'] ['HotelName'];
		$star_rating = $book_params ['token'] ['StarRating'];
		$hotel_code = '';
		$phone_number = $book_params ['passenger_contact'];
		$phone_code = @$book_params ['phone_country_code'];
		$alternate_number = 'NA';
		$email = $book_params ['billing_email'];
		//debug($email);exit;
		$hotel_check_in = db_current_datetime ( str_replace ( '/', '-', $search_data ['data'] ['from_date'] ) );
		$hotel_check_out = db_current_datetime ( str_replace ( '/', '-', $search_data ['data'] ['to_date'] ) );
		$payment_mode = $book_params ['payment_method'];
		$country_name = $GLOBALS ['CI']->db_cache_api->get_country_list ( array (
				'k' => 'origin',
				'v' => 'name' 
		), array (
				'origin' => $book_params ['billing_country'] 
		) );
		$booking_id = '';
		$booking_reference= '';
		$confirmation_reference='';

		$attributes = array (
				'address' => @$book_params ['billing_address_1'],
				'billing_country' => @$country_name [$book_params ['billing_country']],
				// 'billing_city' => $city_name[$params['booking_params']['billing_city']],
				'billing_city' => @$book_params ['billing_city'],
				'billing_zipcode' => @$book_params ['billing_zipcode'],
				'HotelCode' => @$book_params ['token'] ['HotelCode'],
				'search_id' => @$book_params ['token'] ['search_id'],
				'TraceId' => @$book_params ['token'] ['TraceId'],
				'HotelName' => @$book_params ['token'] ['HotelName'],
				'StarRating' => @$book_params ['token'] ['StarRating'],
				'HotelImage' => @$book_params ['token'] ['HotelImage'],
				'HotelAddress' => @$book_params ['token'] ['HotelAddress'],
				'CancellationPolicy' => @$book_params ['token'] ['CancellationPolicy'],
				'Boarding_details' => @$book_params ['token'] ['Boarding_details']
		);
		//debug($attributes);exit;
		$created_by_id = intval ( @$GLOBALS ['CI']->entity_user_id );
		$GLOBALS ['CI']->hotel_model->save_booking_details ( $domain_origin, $status, $app_reference, $booking_source, $booking_id, $booking_reference, $confirmation_reference, $hotel_name, $star_rating, $hotel_code, $phone_number, $alternate_number, $email, $hotel_check_in, $hotel_check_out, $payment_mode, json_encode ( $attributes ), $created_by_id, $transaction_currency, $currency_conversion_rate, $phone_code );
		
		$check_in = db_current_datetime ( str_replace ( '/', '-', $search_data ['data'] ['from_date'] ) );
		$check_out = db_current_datetime ( str_replace ( '/', '-', $search_data ['data'] ['to_date'] ) );
		$location = $search_data ['data'] ['location'];
		foreach ( $HotelRoomsDetails as $k => $v ) {
			$room_type_name = $v ['RoomTypeName'];
			$bed_type_code = $v ['RoomTypeCode'];
			$smoking_preference = get_smoking_preference ( $v ['SmokingPreference'] );
			$smoking_preference = $smoking_preference ['label'];
			$total_fare = $v ['OfferedPriceRoundedOff'];
			$room_price = $v ['RoomPrice'];
			$gst_value = 0;
			//debug($total_fare);exit;
			if ($module == 'b2c') {
				$markup_total_fare = $currency_obj->get_currency ( $total_fare, true, false, true, $no_of_nights ); // (ON Total PRICE ONLY)
				$ded_total_fare = $deduction_cur_obj->get_currency ( $total_fare, true, true, false, $no_of_nights ); // (ON Total PRICE ONLY)
				$admin_markup = sprintf ( "%.2f", $markup_total_fare ['default_value'] - $ded_total_fare ['default_value'] );
				$agent_markup = sprintf ( "%.2f", $ded_total_fare ['default_value'] - $total_fare );
				//adding gst
		        if($admin_markup > 0 ){
		            $gst_details = $GLOBALS['CI']->custom_db->single_table_records('gst_master', '*', array('module' => 'hotel'));
		            if($gst_details['status'] == true){
		                if($gst_details['data'][0]['gst'] > 0){
		                    $gst_value = ($admin_markup/100) * $gst_details['data'][0]['gst'];
		                	$gst_value  = roundoff_number($gst_value);
		                }
		            }
		        }
			} else {
				// B2B Calculation - Room wise price
                            //echo 'total_fare',debug($total_fare);
				$markup_total_fare = $currency_obj->get_currency ( $total_fare, true, true, false, $no_of_nights ); // (ON Total PRICE ONLY)
                                $ded_total_fare = $deduction_cur_obj->get_currency(($markup_total_fare ['default_value']), true, false, true, $no_of_nights ); // (ON Total PRICE ONLY)
                $admin_markup = sprintf ( "%.2f", $markup_total_fare ['default_value'] -  $total_fare);
				$agent_markup = sprintf ( "%.2f", $ded_total_fare ['default_value'] - $markup_total_fare ['default_value']);
                $markup = $admin_markup+$agent_markup;             
				//adding gst
		        if($markup > 0 ){
		            $gst_details = $GLOBALS['CI']->custom_db->single_table_records('gst_master', '*', array('module' => 'hotel'));
		            if($gst_details['status'] == true){
		                if($gst_details['data'][0]['gst'] > 0){
		                    $gst_value = ($markup/100) * $gst_details['data'][0]['gst'];
		                	$gst_value  = roundoff_number($gst_value);
		                }
		            }
		        }
			}
			$total_fare_markup = round($book_total_fare+$admin_markup+$gst_value);
			$attributes = '';
			$GLOBALS ['CI']->hotel_model->save_booking_itinerary_details ( $app_reference, $location, $check_in, $check_out, $room_type_name, $bed_type_code, $status, $smoking_preference, $total_fare, $admin_markup, $agent_markup, $currency, $attributes, @$v ['RoomPrice'], @$v ['Tax'], @$v ['ExtraGuestCharge'], @$v ['ChildCharge'], @$v ['OtherCharges'], @$v ['Discount'], @$v ['ServiceTax'], @$v ['AgentCommission'], @$v ['AgentMarkUp'], @$v ['TDS'], $gst_value );
			$i = 0;
			//Saving Passenger Details
			for ($i = 0; $i < $total_pax_count; $i++){
				$passenger_type = $book_params['passenger_type'][$i];
                $is_lead = $book_params['lead_passenger'][$i];
                if($is_lead==''){
                    $is_lead = 0;
                }
                $title = get_enum_list('title', $book_params['name_title'][$i]);
                $first_name = $book_params['first_name'][$i];
                //debug($first_name);exit;
                $middle_name = ''; //$book_params['middle_name'][$i];
                $last_name = $book_params['last_name'][$i];
                $date_of_birth = @$book_params['date_of_birth'][$i];
                $gender = get_enum_list('gender', $book_params['gender'][$i]);
                $passenger_nationality_id = intval($book_params['passenger_nationality'][$i]);
                $passport_issuing_country_id = intval($book_params['passenger_passport_issuing_country'][$i]);
                $passenger_nationality = $GLOBALS['CI']->db_cache_api->get_country_list(array('k' => 'origin', 'v' => 'name'), array('origin' => $passenger_nationality_id));
                $passport_issuing_country = $GLOBALS['CI']->db_cache_api->get_country_list(array('k' => 'origin', 'v' => 'name'), array('origin' => $passport_issuing_country_id));
                $passenger_nationality = $passenger_nationality [$passenger_nationality_id];
				$passport_issuing_country = $passport_issuing_country [$passport_issuing_country_id];
				$passport_number = $book_params['passenger_passport_number'][$i];
                	$passport_expiry_date = $book_params['passenger_passport_expiry_year'][$i] . '-' . $book_params['passenger_passport_expiry_month'][$i] . '-' . $book_params['passenger_passport_expiry_day'][$i];
				$phone = 0;
				$attributes = array ();
				// SAVE Booking Pax details
				$GLOBALS ['CI']->hotel_model->save_booking_pax_details ( $app_reference, $title, $first_name, $middle_name, $last_name,$phone, $email, $passenger_type, $date_of_birth, $passenger_nationality, $passport_number, $passport_issuing_country, $passport_expiry_date, $status, serialize ( $attributes ) );
			}


		}
		// Convinence_fees to be stored and discount
		$convinence = 0;
		$discount = 0;
		$convinence_value = 0;
		$convinence_type = 0;
		$convinence_per_pax = 0;
		if ($module == 'b2c') {
			$convinence = $currency_obj->convenience_fees ( $total_fare_markup, $master_search_id );
			$convinence_row = $currency_obj->get_convenience_fees ();
			$convinence_value = $convinence_row ['value'];
			$convinence_type = $convinence_row ['type'];
			$convinence_per_pax = $convinence_row ['per_pax']; 
			if($book_params['booking_params']['promo_actual_value']){
				$discount = get_converted_currency_value ( $promo_currency_obj->force_currency_conversion ( $book_params['booking_params']['promo_actual_value']) );
			}			
			//$discount = @$params ['booking_params'] ['promo_code_discount_val'];
			$promo_code = @$book_params ['booking_params'] ['promo_code'];
		} elseif ($module == 'b2b') {
			$discount = 0;
		}
		$GLOBALS ['CI']->load->model ( 'transaction' );

		// SAVE Booking convinence_discount_details details
		$GLOBALS ['CI']->transaction->update_convinence_discount_details ( 'hotel_booking_details', $app_reference, $discount, $promo_code, $convinence, $convinence_value, $convinence_type, $convinence_per_pax );
		/**
		 * ************ Update Convinence Fees And Other Details End *****************
		 */
		
		$response ['fare'] = $book_total_fare;
		$response ['admin_markup'] = $admin_markup;
		$response ['agent_markup'] = $agent_markup;
		$response ['convinence'] = $convinence;
		$response ['discount'] = $discount;
		$response ['transaction_currency'] = $transaction_currency;
		$response ['currency_conversion_rate'] = $currency_conversion_rate;
		//booking_status
		$response['booking_status'] = $status;
		return $response;

	}
	function save_booking($app_booking_id, $params, $module = 'b2c') {
		//debug($params);
		// Need to return following data as this is needed to save the booking fare in the transaction details
		$response ['fare'] = $response ['domain_markup'] = $response ['level_one_markup'] = 0;

		$domain_origin = get_domain_auth_id ();
		$master_search_id = $params ['booking_params'] ['token'] ['search_id'];
		$search_data = $this->search_data ( $master_search_id );
		//$status = BOOKING_CONFIRMED;
		 $status = 'BOOKING_INPROGRESS';
		$app_reference = $app_booking_id;
		$booking_source = $params ['booking_params'] ['token'] ['booking_source'];
		
		$currency_obj = $params ['currency_obj'];
		$deduction_cur_obj = clone $currency_obj;
		$promo_currency_obj = $params['promo_currency_obj'];
		// PREFERRED TRANSACTION CURRENCY AND CURRENCY CONVERSION RATE
		$transaction_currency = get_application_currency_preference ();
		$application_currency = admin_base_currency ();
		$currency_conversion_rate = $currency_obj->transaction_currency_conversion_rate ();
		
		$booking_id = $params ['book_response'] ['BookResult'] ['BookingId'];
		$booking_reference = $params ['book_response'] ['BookResult'] ['BookingRefNo'];

		$confirmation_reference = $params ['book_response'] ['BookResult'] ['ConfirmationNo'];
		$status =  $params ['book_response'] ['BookResult'] ['booking_status'];
		$no_of_nights = intval ( $search_data ['data'] ['no_of_nights'] );
		$HotelRoomsDetails = force_multple_data_format ( $params ['room_book_data'] ['HotelRoomsDetails'] );
		$total_room_count = count ( $HotelRoomsDetails );
		$book_total_fare = $params ['booking_params'] ['token'] ['price_summary'] ['OfferedPriceRoundedOff']; // (TAX+ROOM PRICE)
		$room_price = $params ['booking_params'] ['token'] ['price_summary'] ['RoomPrice'];
		
		if ($module == 'b2c') {
			$markup_total_fare = $currency_obj->get_currency ( $book_total_fare, true, false, true, $no_of_nights * $total_room_count ); // (ON Total PRICE ONLY)
			$ded_total_fare = $deduction_cur_obj->get_currency ( $book_total_fare, true, true, false, $no_of_nights * $total_room_count ); // (ON Total PRICE ONLY)
			$admin_markup = sprintf ( "%.2f", $markup_total_fare ['default_value'] - $ded_total_fare ['default_value'] );
			$agent_markup = sprintf ( "%.2f", $ded_total_fare ['default_value'] - $book_total_fare );
		} else {
			// B2B Calculation
			$markup_total_fare = $currency_obj->get_currency ( $book_total_fare, true, true, false, $no_of_nights * $total_room_count ); // (ON Total PRICE ONLY)
			$ded_total_fare = $deduction_cur_obj->get_currency ( $book_total_fare, true, false, true, $no_of_nights * $total_room_count ); // (ON Total PRICE ONLY)
			$admin_markup = sprintf ( "%.2f", $markup_total_fare ['default_value'] - $ded_total_fare ['default_value'] );
			$agent_markup = sprintf ( "%.2f", $ded_total_fare ['default_value'] - $book_total_fare );
		}
		

		
		$currency = $params ['booking_params'] ['token'] ['default_currency'];
		$hotel_name = $params ['booking_params'] ['token'] ['HotelName'];
		$star_rating = $params ['booking_params'] ['token'] ['StarRating'];
		$hotel_code = '';
		$phone_number = $params ['booking_params'] ['passenger_contact'];
		$phone_code = $params ['booking_params'] ['phone_country_code'];
		$alternate_number = 'NA';
		$email = $params ['booking_params'] ['billing_email'];
		$hotel_check_in = db_current_datetime ( str_replace ( '/', '-', $search_data ['data'] ['from_date'] ) );
		$hotel_check_out = db_current_datetime ( str_replace ( '/', '-', $search_data ['data'] ['to_date'] ) );
		$payment_mode = $params ['booking_params'] ['payment_method'];
		
		$country_name = $GLOBALS ['CI']->db_cache_api->get_country_list ( array (
				'k' => 'origin',
				'v' => 'name' 
		), array (
				'origin' => $params ['booking_params'] ['billing_country'] 
		) );
		// $city_name = $GLOBALS['CI']->db_cache_api->get_city_list(array('k' => 'origin', 'v' => 'destination'), array('origin' => $params['booking_params']['billing_city']));
		$attributes = array (
				'address' => @$params ['booking_params'] ['billing_address_1'],
				'billing_country' => @$country_name [$params ['booking_params'] ['billing_country']],
				// 'billing_city' => $city_name[$params['booking_params']['billing_city']],
				'billing_city' => @$params ['booking_params'] ['billing_city'],
				'billing_zipcode' => @$params ['booking_params'] ['billing_zipcode'],
				'HotelCode' => @$params ['booking_params'] ['token'] ['HotelCode'],
				'search_id' => @$params ['booking_params'] ['token'] ['search_id'],
				'TraceId' => @$params ['booking_params'] ['token'] ['TraceId'],
				'HotelName' => @$params ['booking_params'] ['token'] ['HotelName'],
				'StarRating' => @$params ['booking_params'] ['token'] ['StarRating'],
				'HotelImage' => @$params ['booking_params'] ['token'] ['HotelImage'],
				'HotelAddress' => @$params ['booking_params'] ['token'] ['HotelAddress'],
				'CancellationPolicy' => @$params ['booking_params'] ['token'] ['CancellationPolicy'],
				'Boarding_details' => @$params ['booking_params'] ['token'] ['Boarding_details']
		);
		$created_by_id = intval ( @$GLOBALS ['CI']->entity_user_id );
		// SAVE Booking details
		$GLOBALS ['CI']->hotel_model->save_booking_details ( $domain_origin, $status, $app_reference, $booking_source, $booking_id, $booking_reference, $confirmation_reference, $hotel_name, $star_rating, $hotel_code, $phone_number, $alternate_number, $email, $hotel_check_in, $hotel_check_out, $payment_mode, json_encode ( $attributes ), $created_by_id, $transaction_currency, $currency_conversion_rate, $phone_code );
		
		$check_in = db_current_datetime ( str_replace ( '/', '-', $search_data ['data'] ['from_date'] ) );
		$check_out = db_current_datetime ( str_replace ( '/', '-', $search_data ['data'] ['to_date'] ) );
		
		$location = $search_data ['data'] ['location'];
		// loop token of token
		foreach ( $HotelRoomsDetails as $k => $v ) {
			$room_type_name = $v ['RoomTypeName'];
			$bed_type_code = $v ['RoomTypeCode'];
			$smoking_preference = get_smoking_preference ( $v ['SmokingPreference'] );
			$smoking_preference = $smoking_preference ['label'];
			$total_fare = $v ['Price'] ['OfferedPriceRoundedOff'];
			$room_price = $v ['Price'] ['RoomPrice'];
			$gst_value = 0;
			$convinence = 0;			
			$convinence_value = 0;
			if ($module == 'b2c') {

				$convinence = $currency_obj->convenience_fees ( $total_fare, $master_search_id );
				$convinence_row = $currency_obj->get_convenience_fees ();
				$convinence_value = $convinence_row ['value'];


				$markup_total_fare = $currency_obj->get_currency ( $total_fare, true, false, true, $no_of_nights ); // (ON Total PRICE ONLY)
				$ded_total_fare = $deduction_cur_obj->get_currency ( $total_fare, true, true, false, $no_of_nights ); // (ON Total PRICE ONLY)
				$admin_markup = sprintf ( "%.2f", $markup_total_fare ['default_value'] - $ded_total_fare ['default_value'] );
				$agent_markup = sprintf ( "%.2f", $ded_total_fare ['default_value'] - $total_fare );
				//adding gst
		        if($admin_markup > 0 ){
		            $gst_details = $GLOBALS['CI']->custom_db->single_table_records('gst_master', '*', array('module' => 'hotel'));
		            if($gst_details['status'] == true){
		                if($gst_details['data'][0]['gst'] > 0){
		                    $gst_value = ($admin_markup/100) * $gst_details['data'][0]['gst'];
		                	$gst_value  = roundoff_number($gst_value);
		                }
		            }
		        }
		        $gst_value_con=0;
		        if($convinence > 0 ){
		            $gst_details = $GLOBALS['CI']->custom_db->single_table_records('gst_master', '*', array('module' => 'hotel'));
		            if($gst_details['status'] == true){
		                if($gst_details['data'][0]['gst'] > 0){
		                    $gst_value_con = ($convinence/100) * $gst_details['data'][0]['gst'];
		                	$gst_value_con  = round($gst_value_con);
		                }
		            }
		        }
		        /*if($_SERVER['REMOTE_ADDR']=="103.92.103.129")
		        {
		        	debug($convinence);exit;
		        }*/
		        /*if($gst_value_con >0){

		        	$gst_value +=$gst_value_con;
		        }*/


		        $total_fare_markup = round($book_total_fare+$admin_markup+$gst_value);
			$attributes = '';
			// SAVE Booking Itinerary details
			$GLOBALS ['CI']->hotel_model->save_booking_itinerary_details ( $app_reference, $location, $check_in, $check_out, $room_type_name, $bed_type_code, $status, $smoking_preference, $total_fare, $admin_markup+$gst_value, $agent_markup, $currency, $attributes, @$v ['RoomPrice'], @$v ['Tax'], @$v ['ExtraGuestCharge'], @$v ['ChildCharge'], @$v ['OtherCharges'], @$v ['Discount'], @$v ['ServiceTax'], @$v ['AgentCommission'], @$v ['AgentMarkUp'], @$v ['TDS'], $gst_value_con );




			} else {
				// B2B Calculation - Room wise price
                            //echo 'total_fare',debug($total_fare);
				$markup_total_fare = $currency_obj->get_currency ( $total_fare, true, true, false, $no_of_nights ); // (ON Total PRICE ONLY)
                                $ded_total_fare = $deduction_cur_obj->get_currency(($markup_total_fare ['default_value']), true, false, true, $no_of_nights ); // (ON Total PRICE ONLY)
                $admin_markup = sprintf ( "%.2f", $markup_total_fare ['default_value'] -  $total_fare);
				$agent_markup = sprintf ( "%.2f", $ded_total_fare ['default_value'] - $markup_total_fare ['default_value']);
                $markup = $admin_markup+$agent_markup;             
				//adding gst
		        if($markup > 0 ){
		            $gst_details = $GLOBALS['CI']->custom_db->single_table_records('gst_master', '*', array('module' => 'hotel'));
		            if($gst_details['status'] == true){
		                if($gst_details['data'][0]['gst'] > 0){
		                    // $gst_value = ($markup/100) * $gst_details['data'][0]['gst'];
		                    $gst_value = ($admin_markup/100) * $gst_details['data'][0]['gst'];
		                	$gst_value  = roundoff_number($gst_value);
		                }
		            }
		        }

		        $total_fare_markup = round($book_total_fare+$admin_markup+$gst_value);
				$attributes = '';
				// SAVE Booking Itinerary details
				$GLOBALS ['CI']->hotel_model->save_booking_itinerary_details ( $app_reference, $location, $check_in, $check_out, $room_type_name, $bed_type_code, $status, $smoking_preference, $total_fare, $admin_markup, $agent_markup, $currency, $attributes, @$v ['RoomPrice'], @$v ['Tax'], @$v ['ExtraGuestCharge'], @$v ['ChildCharge'], @$v ['OtherCharges'], @$v ['Discount'], @$v ['ServiceTax'], @$v ['AgentCommission'], @$v ['AgentMarkUp'], @$v ['TDS'], $gst_value );
			}
			
			
			$passengers = force_multple_data_format ( $v ['HotelPassenger'] );
			if (valid_array ( $passengers ) == true) {
				foreach ( $passengers as $passenger ) {
					$title = $passenger ['Title'];
					$first_name = $passenger ['FirstName'];
					$middle_name = $passenger ['MiddleName'];
					$last_name = $passenger ['LastName'];
					$phone = $passenger ['Phoneno'];
					$email = $passenger ['Email'];
					$pax_type = $passenger ['PaxType'];
					//$age = $passenger['Age'];
					$date_of_birth = array_shift ( $params ['booking_params'] ['date_of_birth'] ); //
					
					$passenger_nationality_id = array_shift ( $params ['booking_params'] ['passenger_nationality'] ); //
					$passport_issuing_country_id = array_shift ( $params ['booking_params'] ['passenger_passport_issuing_country'] ); //
					
					$passenger_nationality = $GLOBALS ['CI']->db_cache_api->get_country_list ( array (
							'k' => 'origin',
							'v' => 'name' 
					), array (
							'origin' => $passenger_nationality_id 
					) );
					$passport_issuing_country = $GLOBALS ['CI']->db_cache_api->get_country_list ( array (
							'k' => 'origin',
							'v' => 'name' 
					), array (
							'origin' => $passport_issuing_country_id 
					) );
					
					$passenger_nationality = $passenger_nationality [$passenger_nationality_id];
					$passport_issuing_country = $passport_issuing_country [$passport_issuing_country_id];
					$passport_number = array_shift ( $params ['booking_params'] ['passenger_passport_number'] ); //
					$passport_expiry_date = array_shift ( $params ['booking_params'] ['passenger_passport_expiry_year'] ) . '-' . array_shift ( $params ['booking_params'] ['passenger_passport_expiry_month'] ) . '-' . array_shift ( $params ['booking_params'] ['passenger_passport_expiry_day'] ); //
					$attributes = array ();
					
					// SAVE Booking Pax details
					$GLOBALS ['CI']->hotel_model->save_booking_pax_details ( $app_reference, $title, $first_name, $middle_name, $last_name,$phone, $email, $pax_type, $date_of_birth, $passenger_nationality, $passport_number, $passport_issuing_country, $passport_expiry_date, $status, serialize ( $attributes ) );
				}
			}
		}
		
		/**
		 * ************ Update Convinence Fees And Other Details Start *****************
		 */
		// Convinence_fees to be stored and discount
		$convinence = 0;
		$discount = 0;
		$convinence_value = 0;
		$convinence_type = 0;
		$convinence_per_pax = 0;
		if ($module == 'b2c') {
			$convinence = $currency_obj->convenience_fees ( $total_fare_markup, $master_search_id );
			$convinence_row = $currency_obj->get_convenience_fees ();
			$convinence_value = $convinence_row ['value'];
			$convinence_type = $convinence_row ['type'];
			$convinence_per_pax = $convinence_row ['per_pax']; 
			if($params['booking_params']['promo_actual_value']){
				$discount = get_converted_currency_value ( $promo_currency_obj->force_currency_conversion ( $params['booking_params']['promo_actual_value']) );
			}			
			//$discount = @$params ['booking_params'] ['promo_code_discount_val'];
			$promo_code = @$params ['booking_params'] ['promo_code'];
		} elseif ($module == 'b2b') {
			$discount = 0;
		}
		$GLOBALS ['CI']->load->model ( 'transaction' );

		// SAVE Booking convinence_discount_details details
		$GLOBALS ['CI']->transaction->update_convinence_discount_details ( 'hotel_booking_details', $app_reference, $discount, $promo_code, $convinence, $convinence_value, $convinence_type, $convinence_per_pax );
		/**
		 * ************ Update Convinence Fees And Other Details End *****************
		 */
		
		$response ['fare'] = $book_total_fare;
		$response ['admin_markup'] = $admin_markup;
		$response ['agent_markup'] = $agent_markup;
		$response ['convinence'] = $convinence;
		$response ['discount'] = $discount;
		$response ['transaction_currency'] = $transaction_currency;
		$response ['currency_conversion_rate'] = $currency_conversion_rate;
		//booking_status
		$response['booking_status'] = $status;
		return $response;
	}
	/**
	 * Balu A
	 * Convert Room Book Data in Application Currency
	 * 
	 * @param
	 *        	$currency_obj
	 */
	private function convert_roombook_data_to_application_currency($room_book_data) {
		$application_default_currency = admin_base_currency ();
		$currency_obj = new Currency ( array (
				'module_type' => 'hotel',
				'from' => get_api_data_currency (),
				'to' => admin_base_currency () 
		) );
		$master_room_book_data = array ();
		$HotelRoomsDetails = array ();
		foreach ( $room_book_data ['HotelRoomsDetails'] as $hrk => $hrv ) {
			$HotelRoomsDetails [$hrk] = $hrv;
			$HotelRoomsDetails [$hrk] ['Price'] = $this->preferred_currency_fare_object ( $hrv ['Price'], $currency_obj, $application_default_currency );
		}
		$master_room_book_data = $room_book_data;
		$master_room_book_data ['HotelRoomsDetails'] = $HotelRoomsDetails;
		return $master_room_book_data;
	}
	/**
	 * Balu A
	 * Cancel Booking
	 */
	function cancel_booking($booking_details)
	{
		$header = $this->get_header ();
		$response ['data'] = array ();
		$response ['status'] = FAILURE_STATUS;
		$resposne ['msg'] = 'Remote IO Error';
		$BookingId = $booking_details ['booking_id'];
		$app_reference = $booking_details ['app_reference'];
		$cancel_booking_request = $this->cancel_booking_request_params($app_reference );
		if ($cancel_booking_request ['status']) {
			// 1.SendChangeRequest
			$GLOBALS ['CI']->custom_db->generate_static_response ( json_encode ( $cancel_booking_request ) );
			
			$cancel_booking_response = $GLOBALS ['CI']->api_interface->get_json_response ( $cancel_booking_request ['data'] ['service_url'], $cancel_booking_request ['data'] ['request'], $header );
			$GLOBALS ['CI']->custom_db->generate_static_response ( json_encode ( $cancel_booking_response ) );
			
			// $cancel_booking_response = $GLOBALS['CI']->hotel_model->get_static_response(3317);
			if (valid_array ( $cancel_booking_response ) == true && $cancel_booking_response ['Status'] == SUCCESS_STATUS) {
				
				// Save Cancellation Details
				$hotel_cancellation_details = $cancel_booking_response ['CancelBooking']['CancellationDetails'];
				$GLOBALS ['CI']->hotel_model->update_cancellation_details ( $app_reference, $hotel_cancellation_details );
				$response ['status'] = SUCCESS_STATUS;
				
			} else {
				$response ['msg'] = $cancel_booking_response['Message'];
			}
		}
		return $response;
	}
	/**
	 * Balu A
	 * Cancellation Request Status
	 */
	function get_cancellation_refund_details($ChangeRequestId, $app_reference) {
		$header = $this->get_header ();
		$response ['data'] = array ();
		$response ['status'] = FAILURE_STATUS;
		$resposne ['msg'] = 'Remote IO Error';
		$api_request = $this->cancellation_refund_request_params ( $ChangeRequestId, $app_reference );
		if ($api_request ['status']) {
			$api_response = $GLOBALS ['CI']->api_interface->get_json_response ( $api_request ['data'] ['service_url'], $api_request ['data'] ['request'], $header );
			if (valid_array ( $api_response ) == true && isset ( $api_response ['Status'] ) == true && $api_response ['Status'] == SUCCESS_STATUS) {
				$response ['data'] = $api_response ['RefundDetails'];
				$response ['status'] = SUCCESS_STATUS;
			} else {
				$resposne ['msg'] = @$api_response ['Message'];
			}
		}
		return $response;
	}
	/**
	 * Sawood
	 * check and return status is success or not
	 * 
	 * @param unknown_type $response_status        	
	 */
	function valid_book_response($response_status) {
		$status = false;
		if (is_array ( $response_status ) and ! empty ( $response_status ) and is_array ( $response_status ['BookResult'] ) and ! empty ( $response_status ['BookResult'] ) and $response_status ['BookResult'] ['ResponseStatus'] == SUCCESS_STATUS and isset ( $response_status ['BookResult'] ['HotelBookingStatus'] ) and $response_status ['BookResult'] ['HotelBookingStatus'] != '' and ($response_status ['BookResult'] ['HotelBookingStatus'] != 'Pending' || $response_status ['BookResult'] ['HotelBookingStatus'] != 'Vouchered' || $response_status ['BookResult'] ['HotelBookingStatus'] != 'Confirmed')) {
			$status = true;
		}
		return $status;
	}
	
	/**
	 * Balu A
	 * check and return status is success or not
	 * 
	 * @param unknown_type $response_status        	
	 */
	function valid_response($response_status) {
		$status = true;
		if ($response_status != SUCCESS_STATUS) {
			$status = false;
		}
		return $status;
	}
	
	/**
	 * Balu A
	 *
	 * Check if the room was blocked successfully
	 * 
	 * @param array $block_room_response
	 *        	block room response
	 */
	private function is_room_blocked($block_room_response) {
		$room_blocked = false;
		if (isset ( $block_room_response ['BlockRoomResult'] ) == true and $block_room_response ['BlockRoomResult'] ['IsPriceChanged'] == false and $block_room_response ['BlockRoomResult'] ['IsCancellationPolicyChanged'] == false) {
			$room_blocked = true;
		}
		
		return $room_blocked;
	}
	
	/**
	 * Balu A
	 * check if the room list is valid or not
	 * 
	 * @param
	 *        	$room_list
	 */
	private function valid_room_details_details($room_list) {
		$status = false;
		if (valid_array ( $room_list ) == true and isset ( $room_list ['Status'] ) == true and $room_list ['Status']  == SUCCESS_STATUS) {
			$status = true;
		}
		return $status;
	}
	
	/**
	 * Balu A
	 * check if the hotel response which is received from server is valid or not
	 * 
	 * @param
	 *        	$hotel_details
	 */
	private function valid_hotel_details($hotel_details) {
		$status = false;
		if (valid_array ( $hotel_details ) == true and isset ( $hotel_details ['Status'] ) == true and $hotel_details ['Status']  == SUCCESS_STATUS) {
			$status = true;
		}
		return $status;
	}
	
	/**
	 * Balu A
	 * check if the search response is valid or not
	 * 
	 * @param array $search_result
	 *        	search result response to be validated
	 */
	private function valid_search_result($search_result) {
		if (valid_array ( $search_result ) == true and isset ( $search_result ['Status'] ) == true and $search_result ['Status']  == SUCCESS_STATUS) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Balu A
	 * Update and return price details
	 */
	public function update_block_details($room_details, $booking_parameters,$cancel_currency_obj) {
		#debug($room_details);exit;
		$Surcharge_total ='';
		foreach ($room_details['HotelRoomsDetails'] as $key => $value) {
			$Surcharge_total += @$value['Surcharge_total'];
		}
		
		$booking_parameters ['BlockRoomId'] = $room_details ['BlockRoomId'];
		$room_details ['HotelRoomsDetails'] = get_room_index_list ( $room_details ['HotelRoomsDetails'] );
		//debug($room_details ['HotelRoomsDetails']);
		//echo "-----";
		$booking_parameters ['token'] = array(); // Remove all the token details
		$total_OfferedPriceRoundedOff = $Tax = '';
		
		foreach ( $room_details ['HotelRoomsDetails'] as $__rc_key => $__rc_value ) {
			
			$booking_parameters ['token'] [] = get_dynamic_booking_parameters ( $__rc_key, $__rc_value, get_application_currency_preference () );
			$booking_parameters ['price_token'] [] = $__rc_value ['Price'];
			$booking_parameters['HotelCode'] = $__rc_value['HotelCode'];
		}
		
		$policy_string ='';
		$cancel_string='';

		$last_cancellation_date=$room_details['HotelRoomsDetails'][0]['LastCancellationDate'];
		
		$cancellation_details = array_reverse($room_details['HotelRoomsDetails'][0]['CancellationPolicies']);
		
		$cancellation_rev_details =  array_reverse($room_details['HotelRoomsDetails'][0]['CancellationPolicies']);
		$room_price = 0;
		foreach ($room_details['HotelRoomsDetails'] as $p_key => $p_value) {
			$room_price +=$p_value['Price']['RoomPrice'];
		}
		
		$cancel_count = count($cancellation_details);
		$cancellation_rev_details = $this->php_arrayUnique($cancellation_rev_details,'Charge');			
		$cancellation_details =  $this->php_arrayUnique($cancellation_details,'Charge');
					
		if($cancellation_details && !empty($last_cancellation_date)){
				foreach ($cancellation_details as $key => $value) {
					$amount = 0;
					$policy_string ='';

					if($value['Charge']==0){
						 $policy_string .='No cancellation charges, if cancelled before '.date('d M Y',strtotime($value['ToDate']));
						$last_cancellation_date = $value['ToDate'];
					}else{
						
						if(isset($cancellation_rev_details[$key+1])){
							if($value['ChargeType']==1){
								$amount =  $cancel_currency_obj->get_currency_symbol($cancel_currency_obj->to_currency)." ".$value['Charge'];
							}elseif($value['ChargeType']==2){
								$amount =  $cancel_currency_obj->get_currency_symbol($cancel_currency_obj->to_currency)." ".$room_price;
							}
							
							$current_date = date('Y-m-d');
							$cancell_date = date('Y-m-d',strtotime($value['FromDate']));
							if($cancell_date >$current_date){
								//$value['FromDate'] = date('Y-m-d');
									$policy_string .='Cancellations made after '.date('d M Y',strtotime($value['FromDate'])).' to '.date('d M Y',strtotime($value['ToDate'])).', would be charged '.$amount;
							}
							//$policy_string .='Cancellations made after '.date('d M Y',strtotime($value['FromDate'])).' to '.date('d M Y',strtotime($value['ToDate'])).', would be charged '.$amount;
						}else{
							if($value['ChargeType']==1){
								$amount =  $cancel_currency_obj->get_currency_symbol($cancel_currency_obj->to_currency)." ".$value['Charge'];
							}elseif ($value['ChargeType']==2) {
								$amount =  $cancel_currency_obj->get_currency_symbol($cancel_currency_obj->to_currency)." ".$room_price;
							}
							
							$current_date = date('Y-m-d');
							$cancell_date = date('Y-m-d',strtotime($value['FromDate']));
							if($cancell_date >$current_date){
								$value['FromDate'] = $value['FromDate'];
								$policy_string .='Cancellations made after '.date('d M Y',strtotime($value['FromDate'])).', or no-show, would be charged '.$amount;
							}else{
								$value['FromDate'] = date('Y-m-d');
								$policy_string .='This rate is non-refundable. If you cancel this booking you will not be refunded any of the payment.';
							}
							
						}
					}
					
					$cancel_string .= $policy_string.'<br/>';
					/*if($value['ChargeType']==1){
						if($value['Charge']!=0){
							$amount =  $cancel_currency_obj->get_currency_symbol($cancel_currency_obj->to_currency)." ".$value['Charge'];
						}else{
							$last_cancellation_date = $value['ToDate'];
						}
							
					}elseif($value['ChargeType']==2){
						$amount = '100%';
					}
					$policy_string = ' '.$amount.' will be charged, If cancelled between '.$value['FromDate'].' and '.$value['ToDate'];
					$cancel_string .= $policy_string.' #!# ';*/
						
				}
				
		}else{
			$cancel_string ='This rate is non-refundable. If you cancel this booking you will not be refunded any of the payment.';
		}		
		if(isset($room_details['HotelRoomsDetails'][0]['RoomTypeName'])){
			$booking_parameters['RoomTypeName'] = $room_details['HotelRoomsDetails'][0]['RoomTypeName'];
		}
                
		if(isset($room_details['HotelRoomsDetails'][0]['Boarding_details'])){
			$booking_parameters['Boarding_details1'][] = $room_details['HotelRoomsDetails'][0]['Boarding_details'];	
		}
             
                
                
		$booking_parameters['CancellationPolicy'] = array($cancel_string);
		$booking_parameters['LastCancellationDate'] = $last_cancellation_date;
		$booking_parameters['CancellationPolicy_API'] =  array($room_details['HotelRoomsDetails'][0]['CancellationPolicy']);
		$booking_parameters['TM_Cancellation_Charge'] = $cancellation_details;

		$booking_parameters['Boarding_details'] = $room_details['HotelRoomsDetails'][0]['Boarding_details'];
		$booking_parameters['Surcharge_total'] = @$Surcharge_total;
		$booking_parameters['sur_Charge_exclude'] = @$room_details['HotelRoomsDetails'][0]['surCharge_exclude'];
		$booking_parameters['surCharge_exclude_name'] = @$room_details['HotelRoomsDetails'][0]['surCharge_exclude_name'];
		 // debug('ff');exit;  
		$booking_parameters ['price_summary'] = tbo_summary_room_combination ( $room_details ['HotelRoomsDetails'] );
		// debug($booking_parameters);
		// exit;
		return $booking_parameters;
	}
		/*php check array unique*/
	/**/
	function php_arrayUnique($array,$key){
		 $temp_array = array(); 
		    $i = 0; 
		    $key_array = array(); 
		    
		    foreach($array as $val) { 
		        if (!in_array($val[$key], $key_array)) { 
		            $key_array[$i] = $val[$key]; 
		            $temp_array[$i] = $val; 
		        } 
		        $i++; 
		    } 
		    return $temp_array; 
	}
	/**
	 * parse data according to voucher needs
	 * 
	 * @param array $data        	
	 */
	function parse_voucher_data($data) {
		$response = $data;
		return $response;
	}
	
	/**
	 * Balu A
	 * convert search params to format
	 */
	public function search_data($search_id) {
		$response ['status'] = true;
		$response ['data'] = array ();
		if (empty ( $this->master_search_data ) == true and valid_array ( $this->master_search_data ) == false) {
			$clean_search_details = $GLOBALS ['CI']->hotel_model->get_safe_search_data ( $search_id );
			
			if ($clean_search_details ['status'] == true) {
				$response ['status'] = true;
				$response ['data'] = $clean_search_details ['data'];
				// 28/12/2014 00:00:00 - date format
				$response ['data'] ['from_date'] = date ( 'd/m/Y', strtotime ( $clean_search_details ['data'] ['from_date'] ) );
				$response ['data'] ['to_date'] = date ( 'd/m/Y', strtotime ( $clean_search_details ['data'] ['to_date'] ) );
				
				$response ['data'] ['raw_from_date'] = $clean_search_details ['data'] ['from_date'];
				$response ['data'] ['raw_to_date'] = $clean_search_details ['data'] ['to_date'];
				$response ['data'] ['location_id'] = $clean_search_details ['data'] ['hotel_destination'];
				$response ['data'] ['CityId'] =  $clean_search_details ['data'] ['hotel_destination'];
				//get countrycode 
				$get_country_code = $GLOBALS['CI']->custom_db->single_table_records('all_api_city_master','*',array('origin'=>$clean_search_details ['data'] ['hotel_destination']));
				// debug($clean_search_details);exit;
				if($clean_search_details['data']['search_type'] == 'location_search'){
					$response ['data'] ['country_code'] = $clean_search_details['data']['countrycode'];
				}
				else{
					$response ['data'] ['country_code'] = $get_country_code['data'][0]['country_code'];
				}
				
				//debug($response);
				//$response ['data'] ['country_code']
				//below code comment by ela
				// get city id based
				// $location_details = $GLOBALS ['CI']->hotel_model->tbo_hotel_city_id ( $clean_search_details ['data'] ['city_name'], $clean_search_details ['data'] ['country_name'] );
				// if ($location_details ['status']) {
				// 	$response ['data'] ['country_code'] = $location_details ['data'] ['country_code'];
				// 	$response ['data'] ['location_id'] = $location_details ['data'] ['origin'];
				// } else {
				// 	$response ['data'] ['country_code'] = $response ['data'] ['location_id'] = '';
				// }
				$this->master_search_data = $response ['data'];
			} else {
				$response ['status'] = false;
			}
		} else {
			$response ['data'] = $this->master_search_data;
		}
		
		$this->search_hash = md5 ( serialized_data ( $response ['data'] ) );
		return $response;
	}
	
	/**
	 * Markup for search result
	 * 
	 * @param array $price_summary        	
	 * @param object $currency_obj        	
	 * @param number $search_id        	
	 */
	function update_search_markup_currency(& $price_summary, & $currency_obj, $search_id, $level_one_markup = false, $current_domain_markup = true) {
		$search_data = $this->search_data ( $search_id );
		$no_of_nights = $this->master_search_data ['no_of_nights'];
		$no_of_rooms = $this->master_search_data ['room_count'];
		//$multiplier = ($no_of_nights * $no_of_rooms);
		$multiplier = $no_of_nights;
		return $this->update_markup_currency ( $price_summary, $currency_obj, $multiplier, $level_one_markup, $current_domain_markup,$search_id);
	}
	/**
	 * Markup for search result
	 * 
	 * @param array $price_summary        	
	 * @param object $currency_obj        	
	 * @param number $search_id        	
	 */
	function update_search_markup_currency_one_night(& $price_summary, & $currency_obj, $search_id, $level_one_markup = false, $current_domain_markup = true) {
		$search_data = $this->search_data ( $search_id );
		$no_of_nights = $this->master_search_data ['no_of_nights'];
		$no_of_rooms = $this->master_search_data ['room_count'];
		//$multiplier = ($no_of_nights * $no_of_rooms);
		$multiplier = 1;
		return $this->update_markup_currency ( $price_summary, $currency_obj, $multiplier, $level_one_markup, $current_domain_markup,$search_id);
	}

	/**
	 * Markup for Room List
	 * 
	 * @param array $price_summary        	
	 * @param object $currency_obj        	
	 * @param number $search_id        	
	 */
	function update_room_markup_currency(& $price_summary, & $currency_obj, $search_id, $level_one_markup = false, $current_domain_markup = true) {
		
		$search_data = $this->search_data ( $search_id );
		$no_of_nights = $this->master_search_data ['no_of_nights'];
		$no_of_rooms = 1;
		//$multiplier = ($no_of_nights * $no_of_rooms);
		$multiplier = $no_of_nights;
		return $this->update_markup_currency ( $price_summary, $currency_obj, $multiplier, $level_one_markup, $current_domain_markup,$search_id);
	}
	
	/**
	 * Markup for Booking Page List
	 * 
	 * @param array $price_summary        	
	 * @param object $currency_obj        	
	 * @param number $search_id        	
	 */
	function update_booking_markup_currency(& $price_summary, & $currency_obj, $search_id, $level_one_markup = false, $current_domain_markup = true) {
		
		return $this->update_search_markup_currency ( $price_summary, $currency_obj, $search_id, $level_one_markup, $current_domain_markup );
	}
	/**
	*Update Markup currency for Cancellation Charge
	*/
	function update_cancellation_markup_currency(&$cancel_charge,&$currency_obj,$search_id,$level_one_markup=false,$current_domain_markup=true){
		$search_data = $this->search_data ( $search_id );
		
		$no_of_nights = $this->master_search_data ['no_of_nights'];
		$temp_price = $currency_obj->get_currency ( $cancel_charge, true, $level_one_markup, $current_domain_markup, $no_of_nights );
				
		return round($temp_price['default_value']);
	}

	/**
	 * update markup currency and return summary
	 * $attr needed to calculate number of nights markup when its plus based markup
	 */
	function update_markup_currency(& $price_summary, & $currency_obj, $no_of_nights = 1, $level_one_markup = false, $current_domain_markup = true,$search_id='') {
		
		$tax_service_sum = 0;
		$tax_removal_list = array ();
		$markup_list = array (
				'RoomPrice',
				'PublishedPrice',
				'PublishedPriceRoundedOff',
				'OfferedPrice',
				'OfferedPriceRoundedOff' 
		);

		$markup_summary = array ();
		foreach ( $price_summary as $__k => $__v ) {
			
			$ref_cur = $currency_obj->force_currency_conversion ( $__v ); // Passing Value By Reference so dont remove it!!!
			$price_summary [$__k] = $ref_cur ['default_value']; // If you dont understand then go and study "Passing value by reference"
			
			if (in_array ( $__k, $markup_list )) {
				$temp_price = $currency_obj->get_currency ( $__v, true, $level_one_markup, $current_domain_markup, $no_of_nights );
				
			} else {
				$temp_price = $currency_obj->force_currency_conversion ( $__v );
			}
			// echo 'herre';
			// debug($temp_price);exit;
			// adding service tax and tax to total

			if (in_array ( $__k, $tax_removal_list )) {
				$markup_summary [$__k] = round($temp_price ['default_value'] + $tax_service_sum);
			} else {

				$markup_summary [$__k] = round($temp_price ['default_value']);
			}

			if($__k=="PublishedPrice")
			{
				$markup_summary ['admin_markup'] = round($temp_price ['admin_markup']);
			}
			
		}
	
		$Markup = 0;
		$Admin_Markup = 0;
		if (isset($markup_summary['PublishedPrice'])) {
            $Markup = $markup_summary['PublishedPrice'] - $price_summary['PublishedPrice'];
        }
        if (isset($markup_summary['admin_markup'])) {
            $Admin_Markup = $markup_summary['admin_markup'];
        }

        


        	
        $gst_value = 0;
        $convience_value = 0;
        //adding gst
        // debug($Markup);exit;
	    if($Admin_Markup > 0 ){
	    	/*if (intval($search_id) > 0) {
                $search_data = $GLOBALS['CI']->private_management_model->hotel_convinence_fees($search_id);
                // debug($search_data);exit;
                if($search_data !="")
                {
                    $convience_value=$search_data['value'];
                }
            }*/

	        $gst_details = $GLOBALS['CI']->custom_db->single_table_records('gst_master', '*', array('module' => 'hotel'));
	        if($gst_details['status'] == true){
	            if($gst_details['data'][0]['gst'] > 0){
	                $gst_value = ($Admin_Markup/100) * $gst_details['data'][0]['gst'];
	            }
	        }
	     }
	     // debug($gst_value);exit;
	  	$markup_summary['_GST'] = $gst_value;
        $markup_summary['PublishedPrice'] =  round($markup_summary['PublishedPrice'] + $markup_summary['_GST']);
        $markup_summary['PublishedPriceRoundedOff'] =  round($markup_summary['PublishedPriceRoundedOff'] + $markup_summary['_GST']);
        $markup_summary['OfferedPrice'] =  round($markup_summary['OfferedPrice'] + $markup_summary['_GST']);
        $markup_summary['OfferedPriceRoundedOff'] =  round($markup_summary['OfferedPriceRoundedOff'] + $markup_summary['_GST']);
        $markup_summary['RoomPrice'] =  round($markup_summary['RoomPrice'] + $markup_summary['_GST']);
        $markup_summary['_Markup'] = $Markup;
       
      
		// debug($markup_summary);exit;
		return $markup_summary;
	}
	
	/**
	 * Tax price is the price for which markup should not be added
	 */
	function tax_service_sum($markup_price_summary, $api_price_summary) {
		// sum of tax and service ;
		//return ($api_price_summary ['ServiceTax'] + $api_price_summary ['Tax'] + ($markup_price_summary ['PublishedPrice'] - $api_price_summary ['PublishedPrice']));
		return (($api_price_summary ['Tax']+$markup_price_summary ['PublishedPrice'] - $api_price_summary ['PublishedPrice']));
	}
	
	/**
	 * calculate and return total price details
	 */
	function total_price($price_summary) {
		return ($price_summary ['OfferedPriceRoundedOff']);
		
	}
	function booking_url($search_id) {
		return base_url () . 'index.php/hotel/booking/' . intval ( $search_id );
	}
	/**
	 * Balu A
	 * 
	 * @param
	 *        	$ChangeRequestStatus
	 */
	private function ChangeRequestStatusDescription($ChangeRequestStatus) {
		$status_description = '';
		switch ($ChangeRequestStatus) {
			case 0 :
				$status_description = 'NotSet';
				break;
			case 1 :
				$status_description = 'Pending';
				break;
			case 2 :
				$status_description = 'InProgress';
				break;
			case 3 :
				$status_description = 'Processed';
				break;
			case 4 :
				$status_description = 'Rejected';
				break;
		}
		return $status_description;
	}
        
        
        function display_image($HotelPicture,$ResultIndex)
        {
                            header("Content-type: image/gif");
                            echo file_get_contents($HotelPicture);
        }
	
	/**
	 * Get Filter Params - fliter_params
	 */
	function format_search_response($hl, $cobj, $sid, $module = 'b2c', $fltr = array()) {
         
		$level_one = true;
		$current_domain = true;
		if ($module == 'b2c') {
			$level_one = false;
			$current_domain = true;
		} else if ($module == 'b2b') {
			$level_one = true;
			$current_domain = true;
		}

		// debug($fltr);
		// exit;
		$h_count = 0;
		$HotelResults = array ();
		if (isset ( $fltr ['hl'] ) == true) {
			foreach ( $fltr ['hl'] as $tk => $tv ) {
				$fltr ['hl'] [urldecode ( $tk )] = strtolower ( urldecode ( $tv ) );
			}
		}
				// Creating closures to filter data
		// error_reporting(E_ALL);
		// debug($hd);exit;
		$check_filters = function ($hd) use ($fltr) {
			
			$wifi_count = 0;
			if((string)$fltr['wifi']=='true'){

				if(isset($hd['HotelAmenities'])&&valid_array($hd['HotelAmenities'])){		
						 $wi_fi_searchparmas = 'Wi';
						 $wi_search = ucwords('wi-');
						 $wi_fi_small = 'wifi';
						if($this->searchParams($hd['HotelAmenities'],$wi_fi_searchparmas)){
							$wifi_count++;
						}elseif ($this->searchParams($hd['HotelAmenities'],$wi_search)) {
							$wifi_count++;
						}elseif ($this->searchParams($hd['HotelAmenities'],$wi_fi_small)) {
							$wifi_count++;
						}	 
				}
			}
			
			$break_fast_count = 0;
			if((string)$fltr['breakfast']=='true'){
				if(isset($hd['HotelAmenities'])&&valid_array($hd['HotelAmenities'])){		
						 $breakfast_smal = 'breakfast';						 
						 $breakfast = 'Breakfast';
						if($this->searchParams($hd['HotelAmenities'],$breakfast_smal)){
							$break_fast_count++;
						}elseif ($this->searchParams($hd['HotelAmenities'],$breakfast)) {
							$break_fast_count++;
						} 
				}
			}
			
			$parking_count = 0;
			if((string)$fltr['parking']=='true'){
				if(isset($hd['HotelAmenities'])&&valid_array($hd['HotelAmenities'])){		
						 $parking = 'parking';						 
						 $park = 'park';
						if($this->searchParams($hd['HotelAmenities'],$parking)){
							$parking_count++;
						}elseif ($this->searchParams($hd['HotelAmenities'],$park)) {
							$parking_count++;
						} 
				}
			}
			$swim_pool = 0;
			if((string)$fltr['swim_pool']=='true'){
				if(isset($hd['HotelAmenities'])&&valid_array($hd['HotelAmenities'])){		
						 $pool = 'pool';						 
						 $swim = 'Swim';
						if($this->searchParams($hd['HotelAmenities'],$pool)){
							$swim_pool++;
						}elseif ($this->searchParams($hd['HotelAmenities'],$swim)) {
							$swim_pool++;
						} 
				}
			} 
			
			//echo $swim_pool.'<br/>';

			if (($wifi_count >0 || (string)$fltr['wifi']=='false')&&($break_fast_count >0 || (string)$fltr['breakfast']=='false')&&($parking_count >0 || (string)$fltr['parking']=='false')&&($swim_pool >0 || (string)$fltr['swim_pool']=='false')&&(valid_array ( @$fltr ['hl'] ) == false || (valid_array ( @$fltr ['hl'] ) == true && in_array ( strtolower ( $hd ['HotelLocation'] ), $fltr ['hl'] ))) && (valid_array ( @$fltr ['_sf'] ) == false || (valid_array ( @$fltr ['_sf'] ) == true && in_array ( $hd ['StarRating'], $fltr ['_sf'] ))) && (@$fltr ['min_price'] <= ceil ( $hd ['Price'] ['RoomPrice'] ) && (@$fltr ['max_price'] != 0 && @$fltr ['max_price'] >= floor ( $hd ['Price'] ['RoomPrice'] ))) && (( string ) $fltr ['dealf'] == 'false' || empty ( $hd ['HotelPromotion'] ) == false)&& (( string ) $fltr ['free_cancel'] == 'false' || empty ( $hd ['Free_cancel_date'] ) == false)  && (empty ( $fltr ['hn_val'] ) == true || (empty ( $fltr ['hn_val'] ) == false && stripos ( strtolower ( $hd ['HotelName'] ), (urldecode ( $fltr ['hn_val'] )) ) > - 1))) {
				return true;
			} else {
				return false;
			}
		};
		
		
		$hc = 0;
		$frc = 0;

		//echo "filter".$frc.'<br>';
		foreach ( $hl ['HotelSearchResult'] ['HotelResults'] as $hr => $hd ) {

			$hc ++;
			// default values
			$hd ['StarRating'] = intval ( $hd ['StarRating'] );
			if (empty ( $hd ['HotelLocation'] ) == true) {
				$hd ['HotelLocation'] = 'Others';
			}

			if (isset ( $hd ['Latitude'] ) == false) {
				$hd ['Latitude'] = 0;
			}
			
			if (isset ( $hd ['Longitude'] ) == false) {
				$hd ['Longitude'] = 0;
			}
                        
            if(isset($hd ['HotelPicture']) == true)
            { 
                //comment by ela
              //$GLOBALS['CI']->hotel_model->add_hotel_images($sid,$hd ['HotelPicture'],$hd ['ResultIndex'],$hd ['HotelCode']);
            }
			// markup
			//debug( $hd ['Price']);
			$hd ['Price'] = $this->update_search_markup_currency_one_night ( $hd ['Price'], $cobj, $sid, $level_one, $current_domain );

		
			// filter after initializing default data and adding markup
			if (valid_array ( $fltr ) == true && $check_filters ( $hd ) == false) {
				continue;
			}
			$HotelResults [$hr] = $hd;
			$frc ++;
			//echo 'count'.$frc;
		}

		// SORTING STARTS
		if (isset ( $fltr ['sort_item'] ) == true && empty ( $fltr ['sort_item'] ) == false && isset ( $fltr ['sort_type'] ) == true && empty ( $fltr ['sort_type'] ) == false) {
			$sort_item = array ();
			foreach ( $HotelResults as $key => $row ) {
				if ($fltr ['sort_item'] == 'price') {
					$sort_item [$key] = floatval ( $row ['Price'] ['RoomPrice'] );
				} else if ($fltr ['sort_item'] == 'star') {
					$sort_item [$key] = floatval ( $row ['StarRating'] );
				} else if ($fltr ['sort_item'] == 'name') {
					$sort_item [$key] = trim ( $row ['HotelName'] );
				}
			}
			if ($fltr ['sort_type'] == 'asc') {
				$sort_type = SORT_ASC;
			} else if ($fltr ['sort_type'] == 'desc') {
				$sort_type = SORT_DESC;
			}
			if (valid_array ( $sort_item ) == true && empty ( $sort_type ) == false) {
				array_multisort ( $sort_item, $sort_type, $HotelResults );
			}
		} // SORTING ENDS


		$hl ['HotelSearchResult'] ['HotelResults'] = $HotelResults;
		$hl ['source_result_count'] = $hc;
		$hl ['filter_result_count'] = $frc;
		
		return $hl;
	}
	/**
	*format Amenities search like mysql like query
	*/
	public function searchParams($array,$needle){
		$search_count = 0;
		if($array){
			foreach($array as $key => $question)
	        {		        	
	            if (strpos($question,"".$needle."" ) !== false) {
	               $search_count++;
	            }elseif (strpos($question,"".$needle."" ) !== false) {
	               $search_count++;
	            }elseif (strpos($question,"".$needle."" ) !== false) {
	               $search_count++;
	            }         
	        }
		}
		return $search_count;
	}
	/**
	 * Break data into pages
	 * 
	 * @param
	 *        	$data
	 * @param
	 *        	$offset
	 * @param
	 *        	$limit
	 */
	function get_page_data($hl, $offset, $limit) {
		$hl ['HotelSearchResult'] ['HotelResults'] = array_slice ( $hl ['HotelSearchResult'] ['HotelResults'], $offset, $limit );
		return $hl;
	}

	
	/**
	 * Get Filter Summary of the data list
	 * 
	 * @param array $hl        	
	 */
	function filter_summary($hl) {

		$h_count = 0;
		$filt ['p'] ['max'] = false;
		$filt ['p'] ['min'] = false;
		$filt ['loc'] = array ();
		$filt ['star'] = array ();
		$filters = array ();
		foreach ( $hl ['HotelSearchResult'] ['HotelResults'] as $hr => $hd ) {
			// filters
			$StarRating = intval ( @$hd ['StarRating'] );
			$HotelLocation = empty ( $hd ['HotelLocation'] ) == true ? 'Others' : $hd ['HotelLocation'];
			
			if (isset ( $filt ['star'] [$StarRating] ) == false) {
				$filt ['star'] [$StarRating] ['c'] = 1;
				$filt ['star'] [$StarRating] ['v'] = $StarRating;
			} else {
				$filt ['star'] [$StarRating] ['c'] ++;
			}
			
			if (($filt ['p'] ['max'] != false && $filt ['p'] ['max'] < $hd ['Price'] ['RoomPrice']) || $filt ['p'] ['max'] == false) {
				$filt ['p'] ['max'] = roundoff_number ( $hd ['Price'] ['RoomPrice'] );
			}
			if (($filt ['p'] ['min'] != false && $filt ['p'] ['min'] > $hd ['Price'] ['RoomPrice']) || $filt ['p'] ['min'] == false) {
				$filt ['p'] ['min'] = roundoff_number ( $hd ['Price'] ['RoomPrice'] );
			}
			
			if (($filt ['p'] ['min'] != false && $filt ['p'] ['min'] > $hd ['Price'] ['RoomPrice']) || $filt ['p'] ['min'] == false) {
				$filt ['p'] ['min'] = $hd ['Price'] ['RoomPrice'];
			}
			$hloc = ucfirst ( strtolower ( $HotelLocation ) );
			if (isset ( $filt ['loc'] [$hloc] ) == false) {
				$filt ['loc'] [$hloc] ['c'] = 1;
				$filt ['loc'] [$hloc] ['v'] = $hloc;
			} else {
				$filt ['loc'] [$hloc] ['c'] ++;
			}
			
			$filters ['data'] = $filt;
			$h_count ++;
		}
		ksort ( $filters ['data'] ['loc'] );
		$filters ['hotel_count'] = $h_count;
		return $filters;
	}
	/**
	 * Roomwise Assigned Passenger Count
	 * @param unknown_type $pax_type_arr
	 * @param unknown_type $pax_type
	 */
	function get_assigned_pax_type_count($pax_type_arr, $pax_type)
	{
		$pax_type_count = 0;
		if(valid_array($pax_type_arr) == true){
			foreach ($pax_type_arr as $k => $v){
				if($pax_type == $v){
					$pax_type_count++;
				}
			}
		}
		return $pax_type_count;
	}
	function get_agoda_bookings_list(){
		$header = $this->get_header ();
		$this->credentials ( 'AgodaBookingList' );
		// echo $this->service_url;exit;
		$url = $this->service_url;
		$request1['from_date'] = '2018-03-10';
		$request1['to_date'] = '2018-05-20';
		$request = json_encode($request1);
		$get_hotel_list_response = $GLOBALS ['CI']->api_interface->get_json_response ($url, $request, $header );
		// debug($get_hotel_list_response);exit;
		
		return $response;
	}
	
}	

