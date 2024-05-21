<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
require_once BASEPATH . 'libraries/Common_Api_Grind.php';
/**
 *
 * @package Provab
 * @subpackage API
 * @author Arjun J<arjunjgowda260389@gmail.com>
 * @version V1
 */
class Provab_hotelcrs extends Common_Api_Grind {
	private $ClientId;
	private $UserName;
	private $Password;
	private $service_url;
	private $Url;
	private $LoginType = '2'; // (For API Users, value should be ‘2’)
	private $EndUserIp = '127.0.0.1';
	private $TokenId; // Token ID that needs to be echoed back in every subsequent request
	public $master_search_data;
	public $search_hash;
	public function __construct() {
		$this->CI = &get_instance ();
		$GLOBALS ['CI']->load->library ( 'Api_Interface' );
		$GLOBALS ['CI']->load->model ( 'hotels_model' );
		$this->TokenId = $GLOBALS ['CI']->session->userdata ( 'tb_auth_token' );
		$this->set_api_credentials ();
		// if found in session then token will not be replaced
		// $this->set_authenticate_token();
	}
	private function get_header() {
		$hotel_engine_system = $this->CI->config->item ( 'hotel_engine_system' ); 
		$response ['UserName'] = 'test';
		$response ['Password'] = 'test';
		$response ['DomainKey'] = $this->CI->config->item ( 'domain_key' );
		$response ['system'] = $hotel_engine_system;
		
		return $response;
	}
	private function set_api_credentials() {
		$hotel_engine_system = $this->CI->config->item ( 'hotel_engine_system' );
		$this->system = $hotel_engine_system;
		$this->UserName = $this->CI->config->item ( $hotel_engine_system . '_username' );
		$this->Password = $this->CI->config->item ( $hotel_engine_system . '_password' );
		$this->Url = $this->CI->config->item ( 'hotel_url' );
		$this->ClientId = $this->CI->config->item ( 'domain_key' );
		// $this->UserName = 'test';
		// $this->Password = 'password'; // miles@123 for b2b
	}

	
	public function get_authenticate_token() {
		return $GLOBALS ['CI']->session->userdata ( 'tb_auth_token' );
	}

	private function hotel_search_request($search_params) {
		//$this->set_authenticate_token ( true );
		$response ['status'] = true;
		$response ['data'] = array ();
		$currency_obj = new Currency ( array (
				'module_type' => 'hotel' 
		) );
		/**
		 * Request to be formed for search *
		 */
		$request ['EndUserIp'] = '127.0.0.1';
		$request ['TokenId'] = sha1(time().uniqid().'crs'.'#%#sumeru');
		$request ['BookingMode'] = 5; // value to be 5 for api users
		$request ['CheckInDate'] = $search_params ['from_date']; // dd/mm/yyyy
		$request ['NoOfNights'] = $search_params ['no_of_nights']; // Min 1
		$request ['CountryCode'] = $search_params ['country_code']; // ISO Country Code of Destination
		$request ['CityId'] = intval ( $search_params ['location_id'] );
		$request ['PreferredCurrency'] = 'INR'; // INR only
		$request ['GuestNationality'] = ISO_INDIA; // ISO Country Code
		$request ['NoOfRooms'] = intval ( $search_params ['room_count'] );
		
		$room_index = $temp_child_index = 0;
		for($room_index = 0; $room_index < $request ['NoOfRooms']; $room_index ++) {
			$temp_room_config = '';
			$temp_room_config ['NoOfAdults'] = intval ( $search_params ['adult_config'] [$room_index] );
			$temp_room_config ['NoOfChild'] = intval ( $search_params ['child_config'] [$room_index] );
			if ($search_params ['child_config'] [$room_index] > 0) {
				$temp_room_config ['ChildAge'] = array_slice ( $search_params ['child_age'], $temp_child_index, intval ( $search_params ['child_config'] [$room_index] ) );
				$temp_child_index += intval ( $search_params ['child_config'] [$room_index] );
			}
			$request ['RoomGuests'] [] = $temp_room_config;
		}
		$request ['PreferredHotel'] = '';
		$request ['MinRating'] = 0;
		$request ['MaxRating'] = 5;
		$request ['IsNearBySearchAllowed'] = true;
		$request ['SortBy'] = 0;
		$request ['OrderBy'] = 0;
		$request ['ResultCount'] = 0;
		$request ['ReviewScore'] = 0;
		
		$response ['data'] ['request'] = json_encode ( $request );
		//$this->credentials ( 'GetHotelResult' );
		//$response ['data'] ['service_url'] = $this->service_url;
		
		return $response;
	}

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
				$cache_exp = $this->CI->config->item ( 'cache_hotel_search_ttl' );
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
	function get_hotel_list($search_id = '') 
	{ 
		$this->CI->load->driver ( 'cache' );
		$header = $this->get_header (); 
		$response ['data'] = array ();
		$response ['status'] = true;
		
		$search_data = $this->search_data ( $search_id );
		
		// debug($search_data);exit();
		$cache_search = $this->CI->config->item ( 'cache_hotel_search' );
		
		// $search_hash = $this->search_hash;
		// debug($search_hash);exit;
		if ($cache_search) {
			$cache_contents = $this->CI->cache->file->get ( $search_hash );
			
		}

		$response['search_request'] = $this->hotel_search_request ( $search_data ['data'] );

		// debug($response['search_request']);exit();

		$cidate = explode('/', $search_data['data']['from_date']);
		$CIDate = $cidate[2].'-'. $cidate[1].'-'. $cidate[0];

		$codate = explode('/', $search_data['data']['to_date']);
        $CODate = $codate[2].'-'. $codate[1].'-'. $codate[0];

        $datetime1 = new DateTime($CIDate);
        $datetime2 = new DateTime($CODate);

        //$oDiff = $datetime1->diff($datetime2); 
        //$stay_days = intval($oDiff->d); 
        $stay_days = $search_data['data']['no_of_nights'];

        $s_max_adult = max($search_data['data']['adult_config']);
        $s_max_child = max($search_data['data']['child_config']);
        if($s_max_child){
            $s_max_child = $s_max_child;
        }else{
            $s_max_child = 0;
        }
        $checkin_date = date('Y-m-d',strtotime($CIDate));
        $checkout_date = date('Y-m-d',strtotime($CODate));

        $room_count = $search_data['data']['room_count'];
        $city_id = $search_data['data']['city_name'];
       // debug($city_id);exit;
        //added nationality_fk on 31-3-2020
         $nationality_fk = $search_data['data']['nationality_fk'];

        $safe_search_data = $GLOBALS ['CI']->hotels_model->get_crs_search_data ( $datetime1, $datetime2,$stay_days,$s_max_adult,$s_max_child,$checkin_date,$checkout_date,$room_count,$city_id,$nationality_fk);
       	$response ['data'] = $safe_search_data;

        return $response;      
	}

	/**
	 * Converts API data currency to preferred currency
	 * Balu A
	 * 
	 * @param unknown_type $search_result        	
	 * @param unknown_type $currency_obj        	
	 */
	public function search_data_in_preferred_currency($search_result, $currency_obj,$search_id="") {
		$hotels = $search_result ['data'] ['HotelSearchResult'] ['HotelResults'];
		$hotel_list = array ();
			
		foreach ( $hotels as $hk => $hv ) {
			$hotel_list [$hk] = $hv;
			//Update Markup price in search result			
		
			// $Price =  $this->update_search_markup_currency ($hv ['Price'], $currency_obj, $search_id, false, true );	

			$hotel_list [$hk] ['Price'] = $this->preferred_currency_fare_object ($hv ['Price'], $currency_obj );	
			$hotel_list [$hk] ['booking_source'] = CRS_HOTEL_BOOKING_SOURCE;
				// debug($hotel_list [$hk] ['Price']);

		}
		$search_result ['data'] ['HotelSearchResult'] ['PreferredCurrency'] = get_application_currency_preference ();
		$search_result ['data'] ['HotelSearchResult'] ['HotelResults'] = $hotel_list;
		//exit;
		return $search_result;
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
	 * Markup for Booking Page List
	 * 
	 * @param array $price_summary        	
	 * @param object $currency_obj        	
	 * @param number $search_id        	
	 */
	function update_booking_markup_currency(& $price_summary, & $currency_obj, $search_id, $level_one_markup = false, $current_domain_markup = true,$specific_markup_config=array()) {
		
		return $this->update_search_markup_currency ( $price_summary, $currency_obj, $search_id, $level_one_markup, $current_domain_markup,$specific_markup_config);
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
	function get_hotel_details($ResultIndex,$search_id) 
	{
		// $header = $this->get_header ();
		$response ['data'] = array ();
		$response ['status'] = false;
		$search_data = $this->search_data ( $search_id );
		$cidate = explode('/', $search_data['data']['from_date']);
		$CIDate = $cidate[2].'-'. $cidate[1].'-'. $cidate[0];
		$codate = explode('/', $search_data['data']['to_date']);
        $CODate = $codate[2].'-'. $codate[1].'-'. $codate[0];
        $search_data['data']['from_date'] = date('Y-m-d',strtotime($CIDate));
        $search_data['data']['to_date'] = date('Y-m-d',strtotime($CODate));
        $hotel_details_response = $GLOBALS ['CI']->hotels_model->get_crs_hotel_data($ResultIndex,$search_data);
		// debug($hotel_details_response);die;
	
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
		return $response;
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
	
	/* Boopathi G
	* 
	*/
	public function get_agent_hotel_list($search_id='')
	{
	    //echo "asd";die;
		$this->CI->load->driver ( 'cache' );
		$response ['data'] = array ();
		$response ['status'] = true;
		$search_data = $this->search_data ( $search_id );
//	debug($search_data);die;
		$cache_search = false;//$this->CI->config->item ( 'cache_hotel_search' );
		$search_hash = $this->search_hash;
		$cache_contents = '';
		if ($cache_search) {
			$cache_contents = $this->CI->cache->file->get ( $search_hash );
		}		
		if ($search_data ['status'] == true) 
		{
			if ($cache_search === false || ($cache_search === true && empty ( $cache_contents ) == true)) 
			{
				$cidate = explode('/', $search_data['data']['from_date']);
				$CIDate = $cidate[2].'-'. $cidate[1].'-'. $cidate[0];
				$codate = explode('/', $search_data['data']['to_date']);
		        $CODate = $codate[2].'-'. $codate[1].'-'. $codate[0];
		        $checkin_date = date('Y-m-d',strtotime($CIDate));
		        $checkout_date = date('Y-m-d',strtotime($CODate));
		        $search_data['data']['from_date']=$checkin_date;
		        $search_data['data']['to_date']=$checkout_date;
		       // debug($search_data);die;
		        $search_response = $GLOBALS ['CI']->hotels_model->get_crs_data($search_data);
		        // debug($search_response);die('sudheer');

				if ($this->valid_search_result ( $search_response )) 
				{
						$response ['data'] = $search_response['Search'];
						if ($cache_search) {
							$cache_exp = $this->CI->config->item ( 'cache_hotel_search_ttl' );
							$this->CI->cache->file->save ( $search_hash, $response ['data'], $cache_exp );
						}
						// Log Hotels Count
						//$this->cache_result_hotel_count ( $search_response );
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
		// debug($response);die;
		return $response;
	}
	/**
	*format Amenities search like mysql like query
	*/
	private function searchParams($array,$needle){
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
	 * Markup for search result
	 * 
	 * @param array $price_summary        	
	 * @param object $currency_obj        	
	 * @param number $search_id        	
	 */
	function update_search_markup_currency_one_night(& $price_summary, & $currency_obj, $search_id, $level_one_markup = false, $current_domain_markup = true,$specific_markup_config=array()) {
		$search_data = $this->search_data ( $search_id );
		$no_of_nights = $this->master_search_data ['no_of_nights'];
		$no_of_rooms = $this->master_search_data ['room_count'];
		//$multiplier = ($no_of_nights * $no_of_rooms);
		$multiplier = 1;
		return $this->update_markup_currency ( $price_summary, $currency_obj, $multiplier, $level_one_markup, $current_domain_markup,$specific_markup_config);
	}
	/**
	 * Markup for search result
	 * 
	 * @param array $price_summary        	
	 * @param object $currency_obj        	
	 * @param number $search_id        	
	 */
	function update_search_markup_currency(& $price_summary, & $currency_obj, $search_id, $level_one_markup = false, $current_domain_markup = true,$specific_markup_config=array()) {

		// debug($price_summary);
		// debug($level_one_markup);debug($current_domain_markup);
		// die;

		$search_data = $this->search_data ( $search_id );
		$no_of_nights = $this->master_search_data ['no_of_nights'];
		$no_of_rooms = $this->master_search_data ['room_count'];
		//$multiplier = ($no_of_nights * $no_of_rooms);
		$multiplier = $no_of_nights;
		return $this->update_markup_currency ( $price_summary, $currency_obj, $multiplier, $level_one_markup, $current_domain_markup,$specific_markup_config);
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
	 * Get Filter Params - fliter_params
	 */
	function format_search_response($hl, $cobj, $sid, $module = 'b2c', $fltr = array()) {


          //   debug("gg");exit;
		$level_one = true;
		$current_domain = true;
		if ($module == 'b2c') {
			$level_one = false;
			$current_domain = true;
		} 
		else if ($module == 'b2b') {
			$level_one = true;
			$current_domain = true;
		}
		else if ($module == 'corporate') {
			$level_one = true;
			$current_domain = false;
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
		//debug($hl ['HotelSearchResult'] ['HotelResults']);die;
		foreach ( $hl ['HotelSearchResult'] ['HotelResults'] as $hr => $hd ) 
		{
			$hc ++;
			// debug($hd);die;
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
            $specific_markup_config = array();
            $specific_markup_config = $this->get_hotel_specific_markup_config($hd);
			// markup
			$hd ['Price'] = $this->update_search_markup_currency_one_night ( $hd ['Price'], $cobj, $sid, $level_one, $current_domain,$specific_markup_config);
			
			// debug( $hd ['Price']);die;
			// debug($specific_markup_config);die;
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
					$sort_item [$key] = roundoff_number ( $row ['Price'] ['RoomPrice'] );
				} else if ($fltr ['sort_item'] == 'star') {
					$sort_item [$key] = roundoff_number ( $row ['StarRating'] );
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
	 * Sanjay Polisetty
	 * convert search params to format
	 */
	public function search_data($search_id) {

	//	debug($search_id);exit();
		$response ['status'] = true;
		$response ['data'] = array ();
		if (empty ( $this->master_search_data ) == true and valid_array ( $this->master_search_data ) == false) {
			$clean_search_details = $GLOBALS ['CI']->hotel_model->get_safe_search_data ( $search_id );
		//	debug($clean_search_details);die;
			$clean_search_details ['status'] = true;
			if ($clean_search_details ['status'] == true) {
				$response ['status'] = true;
				$response ['data'] = $clean_search_details ['data'];
				// 28/12/2014 00:00:00 - date format
				$response ['data'] ['from_date'] = date ( 'd/m/Y', strtotime ( $clean_search_details ['data'] ['from_date'] ) );
				$response ['data'] ['to_date'] = date ( 'd/m/Y', strtotime ( $clean_search_details ['data'] ['to_date'] ) );
				// get city id based
				$location_details = $GLOBALS ['CI']->hotels_model->tbo_hotel_city_id ( $clean_search_details ['data'] ['city_name'], $clean_search_details ['data'] ['country_name'] );

					// debug($location_details);exit();
				if ($location_details ['status']) {
					$response ['data'] ['country_code'] = $location_details ['data'] ['country_code'];
					$response ['data'] ['location_id'] = $location_details ['data'] ['origin'];
				} else {
					$response ['data'] ['country_code'] = $response ['data'] ['location_id'] = '';
				}
				$this->master_search_data = $response ['data'];
			} else {
				$response ['status'] = false;
			}
		} else {
			$response ['data'] = $this->master_search_data;
		}
		$this->search_hash = md5 ( serialized_data ( $response ['data'] ).'CRS');
		return $response;
	}
		/**
	 * Balu A
	 * Block Room Before Going for payment and showing final booking page to user - TBO rule
	 * 
	 * @param array $pre_booking_params
	 *        	All the necessary data required in block room request - fetched from roomList and hotelDetails Request
	 */
	function block_room($pre_booking_params) 
	{
		// error_reporting(E_ALL);
		$response ['status'] = true;
		$response ['data'] = array ();
		$search_id=$pre_booking_params ['search_id'];
		$search_data = $this->search_data ( $pre_booking_params ['search_id'] );
		$run_block_room_request = true;
		$block_room_request_count = 0;
		$pre_booking_params ['search_data'] = $search_data ['data'];
		$cidate = explode('/', $search_data['data']['from_date']);
		$CIDate = $cidate[2].'-'. $cidate[1].'-'. $cidate[0];
		$codate = explode('/', $search_data['data']['to_date']);
        $CODate = $codate[2].'-'. $codate[1].'-'. $codate[0];
        $search_data['data']['from_date'] = date('Y-m-d',strtotime($CIDate));
        $search_data['data']['to_date'] = date('Y-m-d',strtotime($CODate));
        $price_id=$pre_booking_params['token'][0];
        $block_room_response = $GLOBALS ['CI']->hotels_model->get_crs_block_rooms_data($price_id,$search_data);
        // debug($block_room_response);die;
		// debug($block_room_response);die;
		$application_default_currency = admin_base_currency ();		
		$api_block_room_response_status = $block_room_response['Status'];
		$block_room_response = $block_room_response['BlockRoom'];
		
		if ($this->valid_response ($api_block_room_response_status) == false) {
			$run_block_room_request = false;
			$response ['status'] = false; // Indication for room block
			$response ['data'] ['msg'] = 'Some Problem Occured. Please Search Again to continue';
		}else 
		{
			
			// UPDATE RECURSSION
			// Reset pre booking params token and get new values
			$dynamic_params_url = array();
			// FIXME: Do All currency conversion after API call
			// Converting API currency data to preferred currency
			$temp_block_room_details ['data'] ['response'] = $block_room_response;
			$currency_obj = new Currency ( array (
					'module_type' => 'hotel',
					'from' => admin_base_currency (),
					'to' => get_application_currency_preference () 
			) );

			$temp_block_room_details = $this->roomblock_data_in_preferred_currency ( $temp_block_room_details, $currency_obj,$search_id);
			// debug($temp_block_room_details);die;
			$temp_block_room_details = $temp_block_room_details['data'] ['response']['BlockRoomResult'] ['HotelRoomsDetails'];
			$_HotelRoomsDetails = get_room_index_list ( $temp_block_room_details );
			
			// $_HotelRoomsDetails = get_room_index_list($block_room_response['BlockRoomResult']['HotelRoomsDetails']);
			foreach ( $_HotelRoomsDetails as $___tk => $___tv ) {
				$dynamic_params_url [] = get_dynamic_booking_parameters ( $___tk, $___tv, $application_default_currency );
			}
			// update token key
			$pre_booking_params ['token'] = $dynamic_params_url;
			$pre_booking_params ['token_key'] = md5 ( serialized_data ( $dynamic_params_url ) );
			// $block_room_request = $this->get_block_room_request ( $pre_booking_params );
		}
			// }
			$response ['data'] ['response'] = $block_room_response;
		// debug($response);exit;
			// debug($pre_booking_params);die;
		return $response;
	}
	/**
	 * Balu A
	 * 
	 * @param unknown_type $block_room_data        	
	 * @param unknown_type $currency_obj        	
	 */
	public function roomblock_data_in_preferred_currency($block_room_data, $currency_obj,$search_id,$module='b2c') 
	{
		// debug($block_room_data);die;
		$level_one = true;
		$current_domain = true;
		if ($module == 'b2c') {
			$level_one = false;
			$current_domain = true;
		} else if ($module == 'b2b') {
			$level_one = true;
			$current_domain = true;
		}
		$application_currency_preference = get_application_currency_preference();
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

				//$Charge = $this->update_cancellation_markup_currency($cv['Charge'],$currency_obj,$search_id,$level_one,$current_domain);
				$Charge=round($cv['Charge']);
				$CancellationPolicies [$ck] = $cv;
				$CancellationPolicies [$ck] ['Currency'] =get_application_currency_preference();
				$CancellationPolicies [$ck] ['Charge'] = $Charge ;
			}
			$hotel_room_result [$hr_k] ['API_raw_price'] = $API_raw_price;
			$hotel_room_result [$hr_k] ['Price'] = $Price;
			$hotel_room_result [$hr_k] ['CancellationPolicies'] = $CancellationPolicies;
			// CancellationPolicy:FIXME: convert the INR price to preferred currency
		}
		$block_room_data ['data'] ['response'] ['BlockRoomResult'] ['HotelRoomsDetails'] = $hotel_room_result;
		// debug($block_room_data);die;
		return $block_room_data;
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
	 * Balu A
	 * Update and return price details
	 */
	public function update_block_details($room_details, $booking_parameters,$cancel_currency_obj) {
		$booking_parameters ['token']=array();
		// debug($room_details);exit;
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
		//debug($room_details);exit;			
		if($cancellation_details && !empty($last_cancellation_date)){
				foreach ($cancellation_details as $key => $value) {
					$amount = 0;
					$policy_string ='';

					if($value['Charge']==0){
						 $policy_string .='No cancellation charges, if cancelled before '.date('d M Y',strtotime($value['ToDate']));
						$last_cancellation_date = $value['FromDate'];
					}else{
						
						if(isset($cancellation_rev_details[$key+1])){
							if($value['ChargeType']==1){
								$amount =  $cancel_currency_obj->get_currency_symbol($cancel_currency_obj->to_currency)." ";
							}elseif($value['ChargeType']==2){
								$amount =  $cancel_currency_obj->get_currency_symbol($cancel_currency_obj->to_currency)." ".$room_price;
							}
							
							$current_date = date('Y-m-d');
							$cancell_date = date('Y-m-d',strtotime($value['FromDate']));
							if($cancell_date >$current_date){
								
										  $amount = $currency_obj->to_currency . "  " . $value['Charge'];
										  $policy_string .= 'Cancellation made between ' .$value['fdays'] . ' to ' .$value['tdays'] . ' days would be charged '.$value['Charge'].'%';
									 //  }
								//$value['FromDate'] = date('Y-m-d');
									//$policy_string .='Cancellations made after '.date('d M Y',strtotime($value['FromDate'])).' to '.date('d M Y',strtotime($value['ToDate'])).', would be charged '.$amount;
							}
							//$policy_string .='Cancellations made after '.date('d M Y',strtotime($value['FromDate'])).' to '.date('d M Y',strtotime($value['ToDate'])).', would be charged '.$amount;
						
						   $last_cancellation_date = $value['FromDate'];
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
								
										  //$amount = $currency_obj->to_currency . "  " . $value['Charge'];
										  $policy_string .= 'Cancellation made between ' .$value['fdays'] . ' to ' .$value['tdays'] . ' days  would be charged '.$value['Charge'].'%';
									//}
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
			$booking_parameters['Boarding_details'] = $room_details['HotelRoomsDetails'][0]['Boarding_details'];	
		}	
		$booking_parameters['CancellationPolicy'] = array($cancel_string);
		$booking_parameters['LastCancellationDate'] = $last_cancellation_date;
		$booking_parameters['CancellationPolicy_API'] =  array($room_details['HotelRoomsDetails'][0]['CancellationPolicy']);
		$booking_parameters['TM_Cancellation_Charge'] = $cancellation_details;

		$booking_parameters['Boarding_details'] = $room_details['HotelRoomsDetails'][0]['Boarding_details'];
		$booking_parameters['Surcharge_total'] = @$Surcharge_total;
		$booking_parameters['sur_Charge_exclude'] = @$room_details['HotelRoomsDetails'][0]['surCharge_exclude'];
		$booking_parameters['surCharge_exclude_name'] = @$room_details['HotelRoomsDetails'][0]['surCharge_exclude_name'];
		$booking_parameters ['price_summary'] = tbo_summary_room_combination ( $room_details ['HotelRoomsDetails'] );
		// debug($booking_parameters);
		// exit;
		return $booking_parameters;
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
	

	function booking_url($search_id) {
		return base_url () . 'index.php/hotelcrs/booking/' . intval ( $search_id );
	}
	function total_price($price_summary) {
		return ($price_summary ['OfferedPriceRoundedOff']);
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
	 * get Room List for selected hotel
	 * 
	 * @param string $TraceId        	
	 * @param number $ResultIndex        	
	 * @param string $HotelCode        	
	 */
	function get_room_list($ResultToken,$search_id=0) 
	{
		$response ['data'] = array ();
		$response ['status'] = false;
		$search_data = $this->search_data ( $search_id );
		$cidate = explode('/', $search_data['data']['from_date']);
		$CIDate = $cidate[2].'-'. $cidate[1].'-'. $cidate[0];
		$codate = explode('/', $search_data['data']['to_date']);
        $CODate = $codate[2].'-'. $codate[1].'-'. $codate[0];
        $search_data['data']['from_date'] = date('Y-m-d',strtotime($CIDate));
        $search_data['data']['to_date'] = date('Y-m-d',strtotime($CODate));
        
        $hotel_room_list_response = $GLOBALS ['CI']->hotels_model->get_crs_hotel_rooms_data($ResultToken,$search_data);
		// debug($hotel_room_list_response);die;
	
			/*
			 * $static_search_result_id = 813;//106;//68;//52;
			 * $hotel_room_list_response = $GLOBALS['CI']->hotel_model->get_static_response($static_search_result_id);
			 */
			if ($this->valid_room_details_details($hotel_room_list_response)) 
			{
				$response ['data'] = $hotel_room_list_response['RoomList'];
				$response ['status'] = true;
			} else {
				$response ['data'] = $hotel_room_list_response;
			}
		return $response;
	}

	/**
	 * update markup currency and return summary
	 * $attr needed to calculate number of nights markup when its plus based markup
	 */
		function update_markup_currency(& $price_summary, & $currency_obj, $no_of_nights = 1, $level_one_markup = false, $current_domain_markup = true,$search_id='') {
		
	//	debug($price_summary);
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
			//	debug($_v);die;
				$temp_price = $currency_obj->get_currency ( $__v, true, $level_one_markup, $current_domain_markup, $no_of_nights );
	//	debug($temp_price);exit;

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
      //  debug($markup_summary);
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
	     //	$agent_markup=$temp_price['original_markup']*$no_of_nights;

	     // debug($gst_value);exit;
	  	$markup_summary['_GST'] = $gst_value;
        $markup_summary['PublishedPrice'] =  round($markup_summary['PublishedPrice']);
        $markup_summary['PublishedPriceRoundedOff'] =  round($markup_summary['PublishedPriceRoundedOff'] + $markup_summary['_GST']);
        $markup_summary['OfferedPrice'] =  round($markup_summary['OfferedPrice'] + $markup_summary['_GST']);
        $markup_summary['OfferedPriceRoundedOff'] =  round($markup_summary['OfferedPrice'] + $markup_summary['_GST']);
        $markup_summary['RoomPrice'] =  round($markup_summary['RoomPrice'] + $markup_summary['_GST']);
        $markup_summary['_Markup'] = $Markup;
       
      // debug($markup_summary);exit;
		return $markup_summary;
	}
	function update_markup_currencycrs(& $price_summary, & $currency_obj, $no_of_nights = 1, $level_one_markup = false, $current_domain_markup = true,$specific_markup_config=array()) {
		
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
		

			if($price_summary['TBO_RoomPrice']!="")
	{
		$realprice=$price_summary ['TBO_RoomPrice'];
	}
	else
	{
	    $realprice=$price_summary ['PublishedPrice'];
	}
//	debug($realprice);
//	debug($price_summary);
		foreach ( $price_summary as $__k => $__v ) {
			
			$ref_cur = $currency_obj->force_currency_conversion ( $__v ); // Passing Value By Reference so dont remove it!!!
			$price_summary [$__k] = $ref_cur ['default_value']; // If you dont understand then go and study "Passing value by reference"
			
			if (in_array ( $__k, $markup_list )) {
			    
			   
			  //    echo 'herrea1';
				$temp_price = $currency_obj->get_currency ( $__v, true, $level_one_markup, $current_domain_markup, $no_of_nights,$specific_markup_config);
			
			if($temp_price['markup_type']=="Pecentage")
			{

				$temp_price['default_value']=$temp_price['default_value']-$realprice;
				$temp_price['original_markup']=$temp_price['default_value'];
		//	debug($temp_price['default_value']);die;
			}
				$agent_markup=$temp_price['original_markup']*$no_of_nights;

				$admin_markup=$temp_price['default_value']*$no_of_nights;
				$temp_price['default_value'] =$realprice+$agent_markup+$admin_markup;
	
	//debug($agent_markup);die;

	
	
			} else {
			    
			   // echo 'herrea2';
				$temp_price = $currency_obj->force_currency_conversion ( $__v );
			}
			
			// adding service tax and tax to total

			if (in_array ( $__k, $tax_removal_list )) {
				$markup_summary [$__k] = roundoff_number($realprice+$agent_markup+$admin_markup);
			} else {

				$markup_summary [$__k] = roundoff_number($realprice+$agent_markup+$admin_markup);
			}
			
		}

	
	
		if (isset($markup_summary['PublishedPrice'])) {
         $Markup = $agent_markup;
        }
		$get_data=$GLOBALS ['CI']->input->get();
		$master_search_id=$get_data['search_id'];
		
		$search_data = $this->search_data ( $master_search_id );
		//debug($search_data);die;
		debug( $Markup);
			debug($markup_summary);

		 $markup_summary['PublishedPrice']=$price_summary ['TBO_RoomPrice']+$markup_summary['RoomPrice'];
		$convinence = $currency_obj->convenience_fees ( $markup_summary['PublishedPrice'], $master_search_id );
		$convinence_row = $currency_obj->get_convenience_fees ();
		$convinence_amount=$convinence_row['value'];
        $gst_value = 0;
        //adding gst
	   
	  	/*$markup_summary['_GST'] = $gst_value;
        $markup_summary['PublishedPrice'] =  $markup_summary['PublishedPrice'] + $markup_summary['_GST'];
        $markup_summary['PublishedPriceRoundedOff'] =  $markup_summary['PublishedPriceRoundedOff'] + $markup_summary['_GST'];
        $markup_summary['OfferedPrice'] =  $markup_summary['OfferedPrice'] + $markup_summary['_GST'];
        $markup_summary['OfferedPriceRoundedOff'] = $markup_summary['OfferedPriceRoundedOff'] + $markup_summary['_GST'];
        $markup_summary['RoomPrice'] =  $markup_summary['RoomPrice'] + $markup_summary['_GST'];
        $markup_summary['_Markup'] = $Markup;
       */
       
       
     
	 //   debug($price_summary);
	    
	    
	     $markup_summary['PublishedPrice']=$realprice+$Markup;
	     $markup_summary['OfferedPriceRoundedOff']=$realprice+$Markup;
	     $markup_summary['RoomPrice']=$realprice+$Markup;
	     
		 $guestcount=$search_data['data']['adult_config'][0]+$search_data['data']['child_config'][0];
	  	$markup_summary['_GST'] = $gst_value*$guestcount;
      //  $markup_summary['PublishedPrice'] =  round($markup_summary['PublishedPrice'] );
       // debug($markup_summary['PublishedPrice']);
        //$markup_summary['PublishedPriceRoundedOff'] =  round($markup_summary['PublishedPriceRoundedOff'] );
        $markup_summary['OfferedPrice'] =  round($realprice+$Markup);
       // debug($markup_summary['OfferedPrice']);exit();
        //$markup_summary['OfferedPriceRoundedOff'] =  round($markup_summary['OfferedPriceRoundedOff']);
      // $markup_summary['RoomPrice'] =  round($markup_summary['RoomPrice'] );
        $markup_summary['AgentMarkUp'] = $agent_markup;
        $markup_summary['_Markup'] = $Markup;
        debug($markup_summary);die;
return $markup_summary;
	}
	
	/**
	 * Tax price is the price for which markup should not be added
	 */
	function tax_service_sum($markup_price_summary, $api_price_summary) {
		// sum of tax and service ;
		return ($api_price_summary ['ServiceTax'] + $api_price_summary ['Tax'] + ($markup_price_summary ['PublishedPrice'] - $api_price_summary ['PublishedPrice']));
	}
	// function cancel_booking($booking_source,$app_reference)
	// {
	// 	if(empty($app_reference) == false &&  $booking_source ==PROVAB_HOTEL_CRS_){
	// 		$result = $GLOBALS ['CI']->hotels_model->hotel_crs_cancel_request($app_reference,$booking_source);
	// 	}else{
	// 		$result = ['msg'=>'Some thing went wrong Try again!','status'=>0];
			
	// 	}
	// 	return $result;	
	// }
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
		// $cancel_booking_request = $this->cancel_booking_request_params($app_reference );
		$cancel_booking_response ['Status'] = true;
		$cancel_booking_response ['CancelBooking']['CancellationDetails'] = array(
			'ChangeRequestId'=>CRS.time(),
			'ChangeRequestStatus'=>3,
			'StatusDescription'=>'Booking has been Canceled',
			'RefundedAmount'=>0,
			'CancellationCharge'=>0);
		// debug($cancel_booking_response);die;
		
			// 1.SendChangeRequest
			// $cancel_booking_response = $GLOBALS ['CI']->api_interface->get_json_response ( $cancel_booking_request ['data'] ['service_url'], $cancel_booking_request ['data'] ['request'], $header );
			//
			
			// $cancel_booking_response = $GLOBALS['CI']->hotel_model->get_static_response(3317);
			if (valid_array ( $cancel_booking_response ) == true && $cancel_booking_response ['Status'] == SUCCESS_STATUS) {
				
				// Save Cancellation Details
				$hotel_cancellation_details = $cancel_booking_response ['CancelBooking']['CancellationDetails'];
				$GLOBALS ['CI']->hotel_model->update_cancellation_details ( $app_reference, $hotel_cancellation_details );
				$response ['status'] = SUCCESS_STATUS;
				
			} else {
				$response ['msg'] = $cancel_booking_response['Message'];
			}
	
		return $response;
	}

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

			$specific_markup_config=array();
			$specific_markup_config[][$room_list ['data'] ['GetHotelRoomResult'] ['HotelRoomsDetails'][0]['HOTEL_CODE']]=array('ref_id'=>$room_list ['data'] ['GetHotelRoomResult'] ['HotelRoomsDetails'][0]['HOTEL_CODE'],'category'=>'hotel_wise');
			// debug($specific_markup_config);die;
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
					//$Charge = $this->update_cancellation_markup_currency($cv['Charge'],$currency_obj,$search_id,$level_one,$current_domain);
				//$Charge = $this->update_cancellation_markup_currency($cv['Charge'],$currency_obj,$search_id,$level_one,$current_domain,$specific_markup_config);
			$Charge =round($cv['Charge']);
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
	*Update Markup currency for Cancellation Charge
	*/
		function update_cancellation_markup_currency(&$cancel_charge,&$currency_obj,$search_id,$level_one_markup=false,$current_domain_markup=true){
		$search_data = $this->search_data ( $search_id );
		
		$no_of_nights = $this->master_search_data ['no_of_nights'];
		$temp_price = $currency_obj->get_currency ( $cancel_charge, true, $level_one_markup, $current_domain_markup, $no_of_nights );
		//debug($temp_price);die;	
		return round($temp_price['original_markup']+$cancel_charge);
	}


	/**
	 * update markup currency and return summary
	 * $attr needed to calculate number of nights markup when its plus based markup
	 */
	private function preferred_currency_fare_object($fare_details, $currency_obj, $default_currency = '') {
		$price_details = array ();
		

		$price_details ['CurrencyCode'] = empty ( $default_currency ) == false ? $default_currency : get_application_currency_preference ();

		$price_details ['RoomPrice'] =$fare_details ['RoomPrice'];

		$price_details ['Tax'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['Tax'] ) );

		$price_details ['ExtraGuestCharge'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['ExtraGuestCharge'] ) );

		$price_details ['ChildCharge'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['ChildCharge'] ) );
		$price_details ['OtherCharges'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['OtherCharges'] ) );
		$price_details ['Discount'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['Discount'] ) );
		$price_details ['PublishedPrice'] = $fare_details ['PublishedPrice'];
		$price_details ['PublishedPriceRoundedOff'] = $fare_details ['PublishedPriceRoundedOff'];
		$price_details ['OfferedPrice'] =$fare_details ['OfferedPrice'];
		$price_details ['OfferedPriceRoundedOff'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['OfferedPriceRoundedOff'] ) );
		$price_details ['AgentCommission'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['AgentCommission'] ) );
		$price_details ['AgentMarkUp'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['AgentMarkUp'] ) );
		$price_details ['ServiceTax'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['ServiceTax'] ) );
		$price_details ['TDS'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['TDS'] ) );
		
		return $price_details;
	}

	function update_room_markup_currency(& $price_summary, & $currency_obj, $search_id, $level_one_markup = false, $current_domain_markup = true,$specific_markup_config=array()) {
		$search_data = $this->search_data ( $search_id );
		$no_of_nights = $this->master_search_data ['no_of_nights'];
		$no_of_rooms = 1;
		$multiplier = ($no_of_nights * $no_of_rooms);
		return $this->update_markup_currency ( $price_summary, $currency_obj, $multiplier, $level_one_markup, $current_domain_markup,$specific_markup_config);
	}

		public function convert_token_to_application_currency($token, $currency_obj, $module) {


			// debug($token);
			// exit("364-provab_hotel_crs");
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

	function save_booking($app_booking_id, $params, $module = 'b2c') {
		// Need to return following data as this is needed to save the booking fare in the transaction details
		$response ['fare'] = $response ['domain_markup'] = $response ['level_one_markup'] = 0;

		$domain_origin = get_domain_auth_id ();
		$master_search_id = $params ['booking_params'] ['token'] ['search_id'];
		$search_data = $this->search_data ( $master_search_id );
		//$status = BOOKING_CONFIRMED;
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

		// debug($params);die;
		$specific_markup_config=array();
	$specific_markup_config[]=array('ref_id'=>$params ['booking_params'] ['token']['HotelCode'],'category'=>'hotel_wise');
		
		if ($module == 'b2c') {
			$markup_total_fare = $currency_obj->get_currency ( $book_total_fare, true, false, true, $no_of_nights * $total_room_count,$specific_markup_config); // (ON Total PRICE ONLY)
			$ded_total_fare = $deduction_cur_obj->get_currency ( $book_total_fare, true, true, false, $no_of_nights * $total_room_count,$specific_markup_config); // (ON Total PRICE ONLY)
			$admin_markup = sprintf ( "%.2f", $markup_total_fare ['default_value'] - $ded_total_fare ['default_value'] );
			$agent_markup = sprintf ( "%.2f", $ded_total_fare ['default_value'] - $book_total_fare );
		} else {
			// B2B Calculation
			$markup_total_fare = $currency_obj->get_currency ( $book_total_fare, true, true, false, $no_of_nights * $total_room_count,$specific_markup_config); // (ON Total PRICE ONLY)
			$ded_total_fare = $deduction_cur_obj->get_currency ( $book_total_fare, true, false, true, $no_of_nights * $total_room_count,$specific_markup_config); // (ON Total PRICE ONLY)
			$admin_markup = sprintf ( "%.2f", $markup_total_fare ['default_value'] - $ded_total_fare ['default_value'] );
			$agent_markup = sprintf ( "%.2f", $ded_total_fare ['default_value'] - $book_total_fare );
		}
		

		
		$currency = $params ['booking_params'] ['token'] ['default_currency'];
		$hotel_name = $params ['booking_params'] ['token'] ['HotelName'];
		$star_rating = $params ['booking_params'] ['token'] ['StarRating'];
		$hotel_code = '';
		$phone_number = $params ['booking_params'] ['passenger_contact'];
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

		$GLOBALS ['CI']->hotel_model->save_booking_detailscrs ( $domain_origin, $status, $app_reference, $booking_source, $booking_id, $booking_reference, $confirmation_reference, $hotel_name, $star_rating, $hotel_code, $phone_number, $alternate_number, $email, $hotel_check_in, $hotel_check_out, $payment_mode, json_encode ( $attributes ), $created_by_id, $transaction_currency, $currency_conversion_rate );
		
		$check_in = db_current_datetime ( str_replace ( '/', '-', $search_data ['data'] ['from_date'] ) );
		$check_out = db_current_datetime ( str_replace ( '/', '-', $search_data ['data'] ['to_date'] ) );
		
		$location = $search_data ['data'] ['location'];
		// loop token of token
		foreach ( $HotelRoomsDetails as $k => $v ) {
			$room_type_name = $params ['booking_params'] ['token'] ['RoomTypeName'];
		//	debug($v );die;
			$bed_type_code = $v ['RoomTypeCode'];
			$smoking_preference = get_smoking_preference ( $v ['SmokingPreference'] );
			$smoking_preference = $smoking_preference ['label'];
			$total_fare = $v ['Price'] ['OfferedPriceRoundedOff'];
			$room_price = $v ['Price'] ['RoomPrice'];
			$gst_value = 0;
			if ($module == 'b2c') {
				$markup_total_fare = $currency_obj->get_currency ( $total_fare, true, false, true, $no_of_nights,$specific_markup_config); // (ON Total PRICE ONLY)
				$ded_total_fare = $deduction_cur_obj->get_currency ( $total_fare, true, true, false, $no_of_nights,$specific_markup_config); // (ON Total PRICE ONLY)
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
				$markup_total_fare = $currency_obj->get_currency ( $total_fare, true, true, false, $no_of_nights,$specific_markup_config); // (ON Total PRICE ONLY)
                                $ded_total_fare = $deduction_cur_obj->get_currency(($markup_total_fare ['default_value']), true, false, true, $no_of_nights,$specific_markup_config); // (ON Total PRICE ONLY)
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
			
			$total_fare_markup = roundoff_number($book_total_fare+$admin_markup+$gst_value);
			$attributes = '';
			//debug($v);
			//debug($params);die;
			// SAVE Booking Itinerary details
			$GLOBALS ['CI']->hotel_model->save_booking_itinerary_details ( $app_reference, $location, $check_in, $check_out, $room_type_name, $bed_type_code, $status, $smoking_preference,$room_price, $admin_markup, $agent_markup, $currency, $attributes, @$v ['RoomPrice'], @$v ['Tax'], @$v ['ExtraGuestCharge'], @$v ['ChildCharge'], @$v ['OtherCharges'], @$v ['Discount'], @$v ['ServiceTax'], @$v ['AgentCommission'], @$v ['AgentMarkUp'], @$v ['TDS'],@$params ['booking_params'] ['token'] ['GST']);
			$passengers = force_multple_data_format ( $v ['HotelPassenger'] );
			if (valid_array ( $passengers ) == true) {
				$pax_cnt=0;
				foreach ( $passengers as $passenger ) {
					$title = $passenger ['Title'];
					$first_name = $passenger ['FirstName'];
					if($pax_cnt==0)
					{
						$return_name=$first_name;
					}
					$pax_cnt=$pax_cnt+1;
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
				// $discount = get_converted_currency_value ( $promo_currency_obj->force_currency_conversion ( $params['booking_params']['promo_actual_value']) );
				$discount = get_converted_currency_value ($params['booking_params']['promo_actual_value']);
			$discount = @$params ['booking_params'] ['promo_code_discount_val'];
			}			
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
		$response['name'] = $return_name;
		$response['phone'] = $phone;	
		$response ['gst'] = $gst_value;			
		$response ['fare'] = $book_total_fare;
		$response ['admin_markup'] = $admin_markup;
		$response ['agent_markup'] = $agent_markup;
		$response ['convinence'] = $convinence;
		$response ['discount'] = $discount;
		$response ['transaction_currency'] = $transaction_currency;
		$response ['currency_conversion_rate'] = $currency_conversion_rate;
		$response['booking_status'] = $status;
		return $response;
	}
	/**
	*Villa List
	**/

	function get_villa_list($search_id = '') { 

		$this->CI->load->driver ( 'cache' );
		$header = $this->get_header (); 
		$response ['data'] = array ();
		$response ['status'] = true;
		
		$search_data = $this->search_data ( $search_id );
		// debug($search_data);exit();
		$cache_search = $this->CI->config->item ( 'cache_hotel_search' );
		
		$search_hash = $this->search_hash;
		// debug($search_hash);exit;
		if ($cache_search) {
			$cache_contents = $this->CI->cache->file->get ( $search_hash );
			
		}

		$response['search_request'] = $this->hotel_search_request ( $search_data ['data'] );
		// debug($response['search_request']);exit();
		$cidate = explode('/', $search_data['data']['from_date']);
		$CIDate = $cidate[2].'-'. $cidate[1].'-'. $cidate[0];

		$codate = explode('/', $search_data['data']['to_date']);
        $CODate = $codate[2].'-'. $codate[1].'-'. $codate[0];

        $datetime1 = new DateTime($CIDate);
        $datetime2 = new DateTime($CODate);

        //$oDiff = $datetime1->diff($datetime2); 
        //$stay_days = intval($oDiff->d); 
        $stay_days = $search_data['data']['no_of_nights'];

        $s_max_adult = max($search_data['data']['adult_config']);
        $s_max_child = max($search_data['data']['child_config']);
        if($s_max_child){
            $s_max_child = $s_max_child;
        }else{
            $s_max_child = 0;
        }
        $checkin_date = date('Y-m-d',strtotime($CIDate));
        $checkout_date = date('Y-m-d',strtotime($CODate));

        $room_count = $search_data['data']['room_count'];
        $city_id = $search_data['data']['city_name'];

        $safe_search_data = $GLOBALS ['CI']->hotels_model->get_villa_search_data ( $datetime1, $datetime2,$stay_days,$s_max_adult,$s_max_child,$checkin_date,$checkout_date,$room_count,$city_id);

        // debug($safe_search_data);exit();
       	$response ['data'] = $safe_search_data;
        return $response;      
	}
	/**
	 *
	 * @param array $booking_params        	
	 */
	function process_booking($book_id, $booking_params)
	{
		// $header = $this->get_header ();
		$response ['status'] = SUCCESS_STATUS;
		$response ['data'] = array ();
		$book_request = $this->get_book_request ( $booking_params, $book_id );		
		// debug($booking_params);die;
		$booking_id=rand();
		$book_response=array('Status'=>true,
			'Message'=>'',
			'CommitBooking'=>array('BookingDetails'=>array(
				'ConfirmationNo'=>$booking_id,
				'BookingRefNo'=>$booking_id,
				'BookingId'=>$booking_id,
				'SupplierCode'=>'',
				'SupplierVatId'=>'',
				'booking_status'=>'BOOKING_CONFIRMED'
				)));
		// debug($book_response);die;
		$api_book_response_status = $book_response['Status'];
		$book_response['BookResult'] = @$book_response['CommitBooking']['BookingDetails'];
		
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
			// debug($room_book_data);die;
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
				'from' => admin_base_currency (),
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
		
		$response ['data'] ['service_url'] = '';
		return $response;
	}
	function get_hotel_specific_markup_config($hotelDetails=array())
	{
		$specific_markup_config = array();
        if (isset($hotelDetails['HotelCode'])) {
            $hotel_code = $hotelDetails['HotelCode'];
        } else {
            $hotel_code = $hotelDetails['OrginalHotelCode'];
        }
        $category = 'hotel_wise';
        $specific_markup_config[] = array('category' => $category, 'ref_id' => $hotel_code);
        return $specific_markup_config;
	}


}

