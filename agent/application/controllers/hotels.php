<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @package    Provab
 * @subpackage Hotel
 * @author     Arjun J<arjunjgowda260389@gmail.com>
 * @version    V1
 */

class Hotels extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		//we need to activate hotel api which are active for current domain and load those libraries
		$this->load->model('hotel_model');
		$this->load->model('hotels_model');
		$this->load->model('module_model');
		$this->load->model('tours_model');

		$this->load->library('social_network/facebook');//Facebook Library to enable login button
		//$this->output->enable_profiler(TRUE);
		$this->load->library ( 'converter' );
		$this->load->model ('flight_model');
		//$this->load->library ( 'provab_mailer' );
		$this->load->model(array('Custom_Db','car_model'));
	}

	/**
	 * index page of application will be loaded here
	 */
	function index()
	{
		$page_data['top_destination_hotel'] = $this->hotel_model->hotel_top_destinations();
		//	echo number_format(0, 2, '.', '');
		$xdata['slider_module'] = 2;
		$xdata['slider_status'] = 1;
		$page_data['banner_title'] = $this->flight_model->get_flight_details('banner_title',$xdata)->result();
		$page_data['slider_images'] = $this->flight_model->get_flight_details('slider_images',$xdata)->result();
		$xdata['position'] = 2;
		$page_data['footer_Advertisement_images'] = $this->flight_model->get_flight_details('Advertisement_images',$xdata)->result();
		$xdata['position'] = 1;
		$page_data['header_Advertisement_images'] = $this->flight_model->get_flight_details('Advertisement_images',$xdata)->result();
		/*
		$page_data['hotel_deals'] = $this->flight_model->get_flight_details('destination_details',array('destination_status' => 'ACTIVE'))->result();
		*/
		// get city
		$page_data['hotel_deals'] = $this->hotel_model->get_top_deals(4);
        
		$this->template->view('hotel/hotel_index',$page_data);
	}

	/**
	 *  Arjun J Gowda
	 * Load Hotel Search Result
	 * @param number $search_id unique number which identifies search criteria given by user at the time of searching
	 */
	function search($search_id)
	{	
		//error_reporting(0);	
		$safe_search_data = $this->hotel_model->get_safe_search_data($search_id);
		// Get all the hotels bookings source which are active
		
		$active_booking_source = $this->hotel_model->active_booking_source();
		
		// debug($active_booking_source);exit;
		// debug($safe_search_data);die;
		if ($safe_search_data['status'] == true and valid_array($active_booking_source) == true) {
			
			$safe_search_data['data']['search_id'] = abs($search_id);
			$room_type=array();
			$room_type	= $this->hotel_model->get_hotel_types_list("", $hotels);	
			$country_nationality = $this->hotels_model->get_country_list();		
			$this->template->view('hotel/search_result_page', array('hotel_search_params' => $safe_search_data['data'], 'active_booking_source' => $active_booking_source,'hotel_types_list'=>$room_type,'country_nationality'=>$country_nationality));
		} else {
			$this->template->view ( 'general/popup_redirect');
		}
	}

	function test_details($origin = 1609)
	{
		//1598//1609
		$data = $this->custom_db->get_static_response($origin);
		//debug($data);exit;
		//$this->template->view('hotel/hotelbeds/delete_hotelbeds_hotel_details_page');
		$this->template->view('hotel/hotelbeds/delete_hotelbeds_booking_page');
	}
	function xml_test_data($origin = 2030)
	{
		//1598//1609//2030
		$data = $this->custom_db->get_static_response($origin, false);
		$data = simplexml_load_string($data, "SimpleXMLElement", LIBXML_NOCDATA);
		$data =  json_encode($data);
		//debug(json_decode($data, true));exit;
		$data = Converter::createArray($data);
		//debug($data);exit;
		//$this->template->view('hotel/hotelbeds/delete_hotelbeds_hotel_details_page');
		$this->template->view('hotel/hotelbeds/delete_hotelbeds_booking_page');
	}
	function hotel_details($search_id)
	{
		$params = $this->input->get();
		// debug($params);die;
		$safe_search_data = $this->hotel_model->get_safe_search_data($search_id);
		$safe_search_data['data']['search_id'] = abs($search_id);
		
		//$currency_obj = new Currency(array('module_type' => 'hotel', 'from' => get_application_default_currency(), 'to' => get_application_currency_preference()));
		
		if (isset($params['booking_source']) == true) {

			//We will load different page for different API providers... As we have dependency on API for hotel details page
			load_hotel_lib($params['booking_source']);
			if ($params['booking_source'] == PROVAB_HOTEL_BOOKING_SOURCE && isset($params['ResultIndex']) == true
			and isset($params['op']) == true and
			$params['op'] == 'get_details' and $safe_search_data['status'] == true) 
			{
				$currency_obj = new Currency(array('module_type' => 'hotel','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));

				$params['ResultIndex']	= urldecode($params['ResultIndex']);
				$raw_hotel_details = $this->hotel_lib->get_hotel_details($params['ResultIndex']);
				// debug($raw_hotel_details);exit;

				if ($raw_hotel_details['status']) {

					if($raw_hotel_details['data']['HotelInfoResult']['HotelDetails']['first_room_details']['Price']){
						 $HotelCode=$raw_hotel_details['data']['HotelInfoResult']['HotelDetails']['HotelCode'];                            
						//calculation Markup for first room 
						$raw_hotel_details['data']['HotelInfoResult']['HotelDetails']['first_room_details']['Price'] = $this->hotel_lib->update_booking_markup_currency($raw_hotel_details['data']['HotelInfoResult']['HotelDetails']['first_room_details']['Price'],$currency_obj,$search_id,true,true);
						 $image_mask=$this->hotel_model->add_hotel_images($search_id,$raw_hotel_details['data']['HotelInfoResult']['HotelDetails']['Images'],$HotelCode);
					}
					//debug($raw_hotel_details['data']);die("api");
					$this->template->view('hotel/tbo/tbo_hotel_details_page', array('currency_obj' => $currency_obj, 'hotel_details' => $raw_hotel_details['data'], 'hotel_search_params' => $safe_search_data['data'], 'active_booking_source' => $params['booking_source'], 'params' => $params));
				} else {
					redirect(base_url().'index.php/hotel/exception?op=Remote IO error @ Session Expiry&notification=session');
				}
			}
			elseif ($params['booking_source'] == CRS_HOTEL_BOOKING_SOURCE && isset($params['ResultIndex']) == true
			and isset($params['op']) == true and
			$params['op'] == 'get_details' and $safe_search_data['status'] == true) 
			{
				$currency_obj = new Currency(array('module_type' => 'hotel','from' => ADMIN_BASE_CURRENCY_STATIC, 'to' => get_application_currency_preference()));

				$params['ResultIndex']	= urldecode($params['ResultIndex']);
				// debug($params);die;
				$raw_hotel_details = $this->hotel_lib->get_hotel_details($params['ResultIndex'],$search_id);

				if ($raw_hotel_details['status']) {

					if($raw_hotel_details['data']['HotelInfoResult']['HotelDetails']['first_room_details']['Price']){
						 $HotelCode=$raw_hotel_details['data']['HotelInfoResult']['HotelDetails']['HotelCode'];   
						$specific_markup_config=array();
						 $specific_markup_config=$this->hotel_lib->get_hotel_specific_markup_config($raw_hotel_details['data']['HotelInfoResult']['HotelDetails']);                   
						//calculation Markup for first room 
						$raw_hotel_details['data']['HotelInfoResult']['HotelDetails']['first_room_details']['Price'] = $this->hotel_lib->update_booking_markup_currency($raw_hotel_details['data']['HotelInfoResult']['HotelDetails']['first_room_details']['Price'],$currency_obj,$search_id,true,true,$specific_markup_config);
						 $image_mask=$this->hotel_model->add_hotel_images($search_id,$raw_hotel_details['data']['HotelInfoResult']['HotelDetails']['Images'],$HotelCode);
					}
					//debug($raw_hotel_details['data']);die("api");
					$this->template->view('hotel/tbo/tbo_hotel_details_page', array('currency_obj' => $currency_obj, 'hotel_details' => $raw_hotel_details['data'], 'hotel_search_params' => $safe_search_data['data'], 'active_booking_source' => $params['booking_source'], 'params' => $params));
				} else {
					redirect(base_url().'index.php/hotel/exception?op=Remote IO error @ Session Expiry&notification=session');
				}
			} else {
				redirect(base_url());
			}
		} else {
			redirect(base_url());
		}
	}

	function hotel_detailsold($search_id) {

		if(is_logged_in_user()) {
			$user_details = array(
				'user_name' => $this->entity_name,
				'user_email' => $this->entity_email,
				'user_mobile' => $this->entity_phone,
				);
			//$this->get_post_review();
			$review_field = array(
				'exist_user1' => 'checked="checked"',
				'exist_user0' => '',
				'previously_booked1'=>'',
				'previously_booked0'=>'checked="checked"',
				'exist_user_field'=>array(
					'open_tag' => '<fieldset disabled="disabled">',
					'close_tag' => '</fieldset>'
					),
			);
			$created_by = $this->entity_user_id;
		}else{
			$review_field = array(
				'exist_user1' => '',
				'exist_user0' => 'checked="checked"',
				'previously_booked1'=>'',
				'previously_booked0'=>'checked="checked"',
				'exist_user_field'=>array(
					'open_tag' => '',
					'close_tag' => ''
					),
			);
			$created_by = NULL;
		}
		$all_post['review_field'] = $review_field;
		$all_post['user_details'] = $user_details;

		$params = $this->input->get (); //debug($params['TraceId']); exit();
		$safe_search_data = $this->hotel_model->get_safe_search_data ( $search_id );

		$safe_search_data ['data'] ['search_id'] = abs ( $search_id );
		$currency_obj = new Currency ( array (
				'module_type' => 'hotel',
				'from' => get_application_default_currency (),
				'to' => get_application_currency_preference () 
		) );
		// debug($params);exit;
		if (isset ( $params ['booking_source'] ) == true) {
			load_hotel_lib ( $params ['booking_source'] );
			 //echo  "helllo";exit;
			if ($params ['booking_source'] == CRS_HOTEL_BOOKING_SOURCE && isset ( $params ['hotel_id'] ) == true) {

				//hotel_lib is hotels_expo.php
				$hotel_crs_markup = $this->private_management_model->get_markup('hotel');
				// debug($hotel_crs_markup);  die;

				/*Get Admin markup given to agent*/
				$hotel_crs_markup_admin = $this->private_management_model->get_markup_admin('hotel_admin');		
				// debug($hotel_crs_markup_admin);  die;
				$params ['TraceId'] = urldecode ( @$params ['TraceId'] );
				$params ['ResultIndex'] = urldecode ( @$params ['ResultIndex'] );
				$params ['HotelCode'] = urldecode ( $params ['hotel_id'] );
				$hotel_data = $this->hotels_model->get_crsHotels_byHotelId($params['hotel_id']);
				//echo $this->db->last_query();
				//debug($hotel_data->num_rows()); exit;	
				$requ =  $this->hotel_lib->search_data ( $search_id );
				//debug($requ);exit;
				 
				$checkin = explode('/', $requ['data']['from_date']);
				$checkin = $checkin[2].'-'.$checkin[1].'-'.$checkin[0];
				$checkout = explode('/', $requ['data']['to_date']);
				$checkout = $checkout[2].'-'.$checkout[1].'-'.$checkout[0];
    			$rooms_required = intval($requ['data']['room_count']);
    			
				if($hotel_data->num_rows() > 0){
   					$rooms_list = $this->hotels_model->get_crs_allRooms($params['hotel_id'],$requ); 
   					// echo $this->db->last_query();die;
   					//debug($rooms_list);exit;
   				    if($rooms_list->num_rows()==0)
	                {
	                     $data['room_list'] = false;
	                } else if($rooms_list->num_rows()>=1)
                	{
                       $rooms_lists = array();
                       //debug($rooms_list);exit;
                       foreach($rooms_list->result_array() as $key => $each_rooms)
                   		{ 
                            $hotel_rooms_count_info = $this->hotels_model->getAvailableRoomsHotel($checkin,$checkout,$each_rooms['hotel_room_type_id'],$each_rooms['seasons_details_id']);
                        //debug($hotel_rooms_count_info);exit;
                       if($hotel_rooms_count_info->num_rows()>0){
                        //My Code 
                         $hotel_rooms_count = $this->hotels_model->getAvailableRooms($checkin,$checkout,$each_rooms['hotel_room_type_id'],$each_rooms['seasons_details_id']); 
                         
                         $room_booked = $hotel_rooms_count->rooms_booked_count;

                         $roombooked = $this->hotels_model->getBookedRooms($each_rooms['hotel_room_type_id'],$each_rooms['seasons_details_id']);
                         $numbers_of_room = $roombooked->no_of_room;

                       //End Of My Code
                        /*$hotel_rooms_count_info_a = $hotel_rooms_count_info->result_array()[0];
                        $room_booked = $hotel_rooms_count_info_a['no_of_room_booked'];
                        $numbers_of_room =  intval($hotel_rooms_count_info_a['no_of_room']);*/
                        $available_rooms = $numbers_of_room - $room_booked;
                        //echo '<pre>'; print_r($available_rooms); exit();
                        
                        if(is_null($room_booked) || $room_booked=='NULL')
                        {
                            $room_booked=0;
                        } else{
                            $room_booked=intval($room_booked);
                        }
                        $room_booked = is_numeric($room_booked) ? $room_booked : 0;
                        
                        if($each_rooms['season_type']=='3') // On requet
                        {
                            $rooms_available = true;
                        } else {

                        	//cahnge here to for room availability
                             $rooms_available = ($rooms_required <= $available_rooms ) ? true : false;
                        }
                        
                        if($rooms_available)
                        {
                        $package_available= check_seasonPackage_availability($each_rooms,$requ);

                        if($package_available)
                        {

                            $price_data = $this->hotels_model->get_crs_topPrice_room($each_rooms['hotel_details_id'],$each_rooms['seasons_details_id'],$each_rooms['hotel_room_type_id']);
                            // debug($price_data->result_array());exit;
                           
                            $restrict_promotion_rate = false;
                           /* $pd = $price_data->result_array()[0];
                            $rate_type = $pd['rate_type'];
                           // $hotel_room_rate_info_id = $price_data->result_array()[0]['hotel_room_rate_info_id'];*/
                        
                         if($price_data->num_rows()>0)
                         {
                         	
                            foreach($each_rooms as $each_keys => $each_data)
                            {
                                $rooms_lists[$key][$each_keys] = $each_data;
                            }
                           // echo "<pre/>sanjay";print_r($each_rooms);exit;

                            $currency_obj = new Currency(array('module_type' => 'hotel','from' => ADMIN_BASE_CURRENCY_STATIC, 'to' =>get_application_currency_preference()));
                            // debug($currency_obj);die;

                            $rooms_lists[$key]['price_data'] = ($price_data->num_rows==0) ? 'false' : $price_data->result_array()[0];
                             $rooms_lists[$key]['price']      = is_array($rooms_lists[$key]['price_data']) ? calculate_crs_roomPrice($price_data->result_array()[0],$requ,$hotel_data->result_array()[0],'',$currency_obj) : 'false';
                            // debug($rooms_lists[$key]['price']);die;
                            $total_price_a= $rooms_lists[$key]['price']['child_price'] + $rooms_lists[$key]['price']['adult_price'];
                            
                            // debug($total_price_a);die;
                            $nationality = 'IN';
                               
                            $total_price_a = $total_price_a;
                            $nation = 'India';
                             
                             $total_price_a = $total_price_a;


							$agent_markup_hotelcrs = $this->domain_management_model->addHotelCrsMarkup($total_price_a, $hotel_crs_markup,$currency_obj);

							$agent_markup_hotelcrs_admin = $this->domain_management_model->addHotelCrsMarkupAdmin($total_price_a, $hotel_crs_markup_admin,$currency_obj);
							/*Convert admin mrkup currency to agent currency*/

							 $currency_obj_conv = new Currency(array('module_type' => 'hotel','from' => ADMIN_BASE_CURRENCY_STATIC, 'to' =>get_application_currency_preference()));
							
							
							$agent_markup_hotelcrs_admin_conv =get_converted_currency_value ( $currency_obj_conv->force_currency_conversion ( $agent_markup_hotelcrs_admin ) );

							//debug($agent_markup_hotelcrs_admin_conv);die;
                            
                            $rooms_lists[$key]['total_price'] = number_format($total_price_a, 2, '.', '');//number_format(floatval($rooms_lists[$key]['price']['child_price']) + floatval($rooms_lists[$key]['price']['adult_price']), 2, '.', '');
                            
                            $room_amenity = $this->hotels_model->get_hotel_room_details($each_rooms['hotel_details_id'],$each_rooms['hotel_room_type_id']);    
                           if($room_amenity->num_rows()>=1)
                            {
                                $room_amenity=$room_amenity->result_array()[0]['room_amenities'];
                                
                            } else {
                               $room_amenity = 'false'; 
                            }
                            $amenity = $room_amenity;

                            if(($room_amenity!='false'))
                               {
                                    if(strpos($amenity, ','))
                                    { 
                                                   
                                        $amenities = explode(',', $amenity);
                                       // echo '<pre>sanjay'; print_r($amenities); exit();
                                        foreach($amenities as $keys => $each_amenity)
                                        {
                                            if(is_numeric($each_amenity))
                                            {  
                                                $amenitiess = $this->hotels_model->get_hotel_amenities_byamenityId($each_amenity);
                                                
                                                if($amenitiess->num_rows()>=1)
                                                {
                                                    $am[$keys] = $amenitiess->result_array()[0];
                                                }

                                            }
                                        }
                                        $amenities = $am;
                                        //echo '<pre>sanjay'; print_r($amenities);
                                    } else {
                                        if(is_numeric($amenity))
                                        {
                                            //echo "<pre/>";print_r($amenity);exit;
                                            $amenitiess = $this->hotels_model->get_hotel_amenities_byamenityId($amenity);
                                            
                                            if($amenitiess->num_rows()>=1)
                                            {
                                                
                                                $amenities[0] = $amenitiess->result_array()[0];
                                            }
                                        }
                                    } 
                               } else {
                                    $amenities = 'false';
                               }
                            $rooms_lists[$key]['room_amenity'] = $amenities;
                            $rooms_lists[$key]['room_amenity_number'] = $room_amenity;
                        
                     } // end of price count
                     //} // end stop sale
                     } // end of pax combination and extra bed combination check 
                     } // count info room available count check 
                     } // count info num rows
                    }

                     sort_array_of_array($rooms_lists,'total_price');
                     $data['room_list'] = $rooms_lists;

                     $data['result'] = array('success' => 'true'); 

                }
           		}
           		// debug($rooms_lists); exit();
				if (!empty($rooms_lists)) {

					/*$this->template->view ( 'hotel/hotel_crs/tbo_hotel_details_page_crs', array (
							'currency_obj' => $currency_obj,
							'hotel_details' => $hotel_data->result_array()[0],
							'hotel_search_params' => $safe_search_data ['data'],
							'active_booking_source' => $params ['booking_source'],
							'hotel_code_id' => @$hotel_code_id,
							'requests'      => $requ,
							'rooms_lists'   => $rooms_lists,
							'params' => $params 
					) );*/
					$hotel_data = $hotel_data->result_array()[0];
					$amenityIds = $hotel_data['hotel_amenities'];
					$amenityIds_array = explode(',',$amenityIds);
					$ams = array();
					foreach ($amenityIds_array as $value) {
						$ams[]= $this->hotels_model->get_hotel_amenities($value);
						//debug($ams);exit;
					}
					$hotel_data['hotel_amenities_name'] = $ams;
					$CancellationDetails = $this->hotels_model->getCancellationDetails($params ['hotel_id']);

					// debug($rooms_lists);exit;
					// $hotel_data['first_room_details']=$rooms_lists;
					// debug($hotel_data);exit;	
					// debug($rooms_lists);die("crs");
					$this->template->view ( 'hotel/hotel_crs/hotel_details_page', array (
							'currency_obj' => $currency_obj,
							'hotel_details' => $hotel_data,
							'hotel_search_params' => $safe_search_data ['data'],
							'active_booking_source' => $params ['booking_source'],
							'hotel_code_id' => @$hotel_code_id,
							'requests'      => $requ,
							'rooms_lists'   => $rooms_lists,
							'params' => $params,
							'cancellation_details' => $CancellationDetails,
                            'country_code' => $this->db_cache_api->get_country_code_list()
					) );
				} else {
					redirect ( base_url () . 'index.php/hotel/exception?op=Remote IO error @ Session Expiry&notification=session' );
				}
			} else {
				exit('1');
				redirect ( base_url () );
			}
		} else {
			exit('2');
			redirect ( base_url () );
		}
	}

	function bookings($search_id) {
		$pre_booking_params = $this->input->post ();
		debug($pre_booking_params);exit;
		$safe_search_data = $this->hotel_model->get_safe_search_data ( $search_id );
		//debug($safe_search_data);exit();
		$safe_search_data ['data'] ['search_id'] = abs ( $search_id );
		$page_data ['active_payment_options'] = $this->module_model->get_active_payment_module_list ();
		if (isset ( $pre_booking_params ['booking_source'] ) == true) {
			// We will load different page for different API providers... As we have dependency on API for hotel details page
			$page_data ['search_data'] = $safe_search_data ['data'];
			load_hotel_lib ( $pre_booking_params ['booking_source'] );
			// Need to fill pax details by default if user has already logged in
			$this->load->model ( 'user_model' );
			$page_data ['pax_details'] = $this->user_model->get_current_user_details ();
			
			header ( "Cache-Control: no-store, no-cache, must-revalidate, max-age=0" );
			header ( "Cache-Control: post-check=0, pre-check=0", false );
			header ( "Pragma: no-cache" );
			
			if ($pre_booking_params ['booking_source'] == CRS_HOTEL_BOOKING_SOURCE &&  isset ( $pre_booking_params ['HotelCode'] ) == true and isset ( $pre_booking_params ['op'] ) == true and $pre_booking_params ['op'] == 'block_room' and $safe_search_data ['status'] == true) {
				//$pre_booking_params ['token'] = unserialized_data ( $pre_booking_params ['token'], $pre_booking_params ['token_key'] );
				$pre_booking_params ['token'] = 'sasasasasa';
				if ($pre_booking_params ['token'] != false) {
					$hotel_id = $pre_booking_params['HotelCode'];
					$season_type_id = $pre_booking_params['season_id'];
           			$room_type_id   = $pre_booking_params['room_id'];
           			$price_data = $this->hotels_model->get_crs_topPrice($hotel_id,$season_type_id,$room_type_id);
            		$hotel_data = $this->hotels_model->get_crsHotels_byHotelId($pre_booking_params['HotelCode']);
					$hotel_data = $hotel_data->row();
			
					$price_data = $price_data->row();

					$page_data ['price_data'] = $price_data;
					$page_data ['hotel_data'] = $hotel_data;

					$page_data['hotel_id'] = $hotel_id;
					$page_data['season_type_id'] = $season_type_id;
           			$page_data['room_type_id']   = $room_type_id;
					$currency_obj = new Currency ( array (
							'module_type' => 'hotel',
							'from' => get_api_data_currency (),
							'to' => get_application_currency_preference () 
					) );
					$pre_booking_params['CancellationDetails'] = $this->hotels_model->getCancellationDetails($hotel_id);
					 
					if (!empty($hotel_data)) {
						$page_data ['booking_source'] = $pre_booking_params ['booking_source'];

						$pre_booking_params['HotelName'] = $hotel_data->hotel_name;
						$pre_booking_params['HotelAddress'] = $hotel_data->hotel_address;
						
						$page_data ['pre_booking_params'] = $pre_booking_params;
						$page_data ['pre_booking_params'] ['default_currency'] = get_application_currency_preference ();
						$page_data ['iso_country_list'] = $this->db_cache_api->get_iso_country_list ();
						$page_data ['country_list'] = $this->db_cache_api->get_country_list ();
						$page_data['country_code'] = $this->db_cache_api->get_country_code_list();
						$page_data ['currency_obj'] = $currency_obj;
						$page_data ['total_price'] = $pre_booking_params['price'];
						$page_data ['hotel_data'] =$hotel_data;
						$page_data ['convenience_fees'] = '';
						$page_data ['tax_service_sum'] = '';
						// Traveller Details
						$page_data ['traveller_details'] = $this->user_model->get_user_traveller_details ();
						$Domain_record = $this->custom_db->single_table_records('domain_list', '*');
						$page_data['active_data'] =$Domain_record['data'][0];
						$temp_record = $this->custom_db->single_table_records('api_country_list', '*');
						$page_data['phone_code'] =$temp_record['data'];
						$page_data['room_data'] = $this->hotels_model->get_roomType_data($room_type_id,$hotel_id)->result_array();
						// debug($page_data['room_data']);exit;
						//$this->template->view ( 'hotel/hotel_crs/tbo_booking_page_crs', $page_data );
						// debug($page_data);exit;
						// $comb_status=$this->hotel_model->get_comb_status( $search_id);
						// $page_data['comb_status']=$comb_status;
						//debug($page_data);die;
						$this->template->view ( 'hotel/hotel_crs/booking_page', $page_data );
					}
				} else {
					redirect ( base_url () . 'index.php/hotel/exception?op=Data Modification&notification=Data modified while transfer(Invalid Data received while validating tokens)' );
				}
			} else {
				redirect ( base_url () );
			}
		} else {
			redirect ( base_url () );
		}
	}

	function pre_bookings($search_id , $static_search_result_id = "") {
		//error_reporting(E_ALL);
		ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
		$post_params = $this->input->post ();
		// debug($post_params);
		$pkg_added=$post_params['pkg_added'];
		// debug($post_params);exit;
		$post_params['tours_id'] = '';
		//$promocode = @$post_params['promocode_val'];
		//$promo_data = $this->domain_management_model->promocode_details($promocode);
		//$promocode_discount_val = @$promo_data['value'];
		//$promocode_discount_type = @$promo_data['value_type'];

		//debug($post_params);exit;
		/*$post_params ['billing_city'] = 'pasay';
		$post_params ['billing_zipcode'] = '2410-2432';
		$post_params ['billing_address_1'] = '3rd floor welcome plaza mall 2410-2432 taft ave .cor libertad st pasay city metro manila 1300 philippines';*/
		$safe_search_data = $this->hotel_model->get_safe_search_data ( $search_id );
		// Make sure token and temp token matches
		$valid_temp_token = unserialized_data ( $post_params ['token'], $post_params ['token_key'] );
		// debug($valid_temp_token); exit;
		if ($valid_temp_token != false) {
			load_hotel_lib ( $post_params ['booking_source'] );
			// After converting to default currency, storing in temp_booking
			$post_params ['token'] = unserialized_data ( $post_params ['token'] );
			$currency_obj = new Currency ( array (
					'module_type' => 'hotel',
					'from' => get_application_currency_preference (),
					'to' => admin_base_currency () 
			) );
			$post_params ['token'] = serialized_data ( $post_params ['token'] );
			//debug($post_params['token']);exit;
			//$temp_token = unserialized_data ( $temp_token );
			$temp_booking = $this->module_model->serialize_temp_booking_record ( $post_params, HOTEL_BOOKING );
			$book_id = $temp_booking ['book_id'];
			$book_origin = $temp_booking ['temp_booking_origin'];
			if ($post_params ['booking_source'] == CRS_HOTEL_BOOKING_SOURCE) {
				$amount = $valid_temp_token['price'];
				$currency = $valid_temp_token ['default_currency'];
			}
			$currency_obj = new Currency ( array (
					'module_type' => 'hotel',
					'from' => admin_base_currency (),
					'to' => admin_base_currency () 
			) );
			$convenience_fees = 0;
			$promocode_discount = 0;
			// details for PGI
			$email = $post_params ['billing_email'];
			$phone = $post_params ['passenger_contact'];
			// $verification_amount = roundoff_number ( $amount + $convenience_fees - $promocode_discount );
			$verification_amount = $amount;
			$firstname = $post_params ['first_name'] ['0'];
			$productinfo = META_ACCOMODATION_COURSE;
			
			$pay_data ['email'] = $email;
			$pay_data ['phone'] = $phone;
			$pay_data ['name'] = $firstname;
			$pay_data ['reference_number'] = $book_id;
			$pay_data ['amount'] = $amount;
			$pay_data ['description'] = $productinfo;
			$pay_data ['amount'] = $verification_amount;
			
			$this->load->model ( 'user_model' );
			$user_details= $this->user_model->get_current_user_details ();
			if(!empty($user_details)){
				$user_id = $user_details[0]['user_id'];
				$user_type = $user_details[0]['user_type'];
			}else{
				$user_id = '';
				$user_type = 0;
			}
			$travel_date = '';
			$booking_hotel_insert = $this->hotels_model->insert_globel_data($post_params,$search_id,$book_id,$user_id,$temp_booking,$valid_temp_token,$safe_search_data);
			$booking_global = array(				'module'         => 'HOTEL',
	                                                'ref_id'              => $booking_hotel_insert,
	                                                'api_details_id'	  => $post_params ['booking_source'],
	                                                'hotel_details_id'    => $valid_temp_token['HotelCode'],
	                                                'parent_pnr'          => $book_id,
	                                                'pnr_no'              => '',
	                                                'user_id'             => $user_id,
	                                                'user_type'           => $user_type,
	                                                'branch_id'			  => '',
	                                                'amount'              => $valid_temp_token['price'],
	                                                'ip'                  => $this->input->ip_address(),
	                                                'payment_method'      => 'Payment Gateway',
	                                                'payment_status'      => 'PROCESS',
	                                                'travel_date'         => date('Y-m-d',strtotime($travel_date )),
	                                                'refund_date'         => date('Y-m-d',strtotime($travel_date )),
	                                                'api_status'          => 'Live',
	                                                'booking_status'      => 'PROCESS',
	                                                'api_booking_status'  => 'PROCESS',
	                                                'assistance_alloted'  => 'NO' ,
	                                                'total_room_price'    => $valid_temp_token['price'],
	                                                'admin_markup_perc'   => '',
	                                                'supplier_markup_perc'=> '',
	                                                'agent_markup_perc'   => '',
	                                                'ovr_all_markup_perc' => '',
	                                                'admin_agent_mark_up' => '',
	                                                'agent_markup_only'   => '',
	                                                'payment_currency'    => 'MYR',
	                                                'payment_charge'      => '',
	                                                'net_rate'            => '',
	                                                'my_markup'           => '',
	                                                'admin_markup'        => '',
	                                                'markup_price'        => $valid_temp_token['price'],
	                                                'service_charge'	  => '',
	                                                'discount_amount'     => '',
	                                                'promo_code'		  => '',
	                                                'admin_currency'      => 'MYR',
	                                                'booking_form_data'   => base64_encode(json_encode($post_params)),
	                                                'last_name'			  => $post_params['last_name'][0],
	                                                'email_id'			  => $post_params['billing_email'],
	                                                'added_by'			  => '',
	                                                'added_type'		  => '',
	                                                'payment_type'		  => '',
	                                                'leadpax'             => $post_params['first_name'][0]);

			
		$booking_global_insert = $this->hotels_model->hotel_add_bookingGlobalData($booking_global);
		$pnr_no = 'CH'.date('m').'T'.date('dHi').$booking_global_insert;
		$update_booking = array(
		                  'pnr_no'              => $pnr_no,
		                  'booking_status'      => 'PROCESS',
		                  'transaction_status'  => 'PROCESS',
		                  'payment_status'      => 'PROCESS'
		                );
        $update_global_booking = $this->hotels_model->Update_Booking_Global($booking_global_insert, $update_booking, 'HOTEL');
		//$domain_balance_status = $this->domain_management_model->verify_current_balance ( $verification_amount, $currency );
		//if ($domain_balance_status == true) {
				switch($post_params['payment_method']) {
						case PAY_NOW :
						// redirect('hotel/secure_booking/'.$book_id.'/'.$book_origin);
						// break;
						 $this->load->model('transaction');
						 $this->transaction->create_payment_record($book_id, $verification_amount, $firstname, $email, $phone, $productinfo, $convenience_fees, $promocode_discount,$promocode);

						/*if($pkg_added=='1')
						{
							$temp_booking = $this->module_model->serialize_temp_booking_record($post_params, HOLIDAY_BOOKING);
							// debug($temp_booking);exit;
							$date =  date('Y');
				            $rand = rand(1,99);
				            $app_reference = $temp_booking['book_id'];

							$tour_details = array('app_reference'=>$app_reference,
										  'status'=>"PROCESSING",
										  'remarks'=>'',
										  'basic_fare'=>$post_params['tour_amount'],
										  'currency_code'=>get_application_display_currency_preference(),
										  'service_tax'=>'',
										  'discount'=>0,
										  'promocode'=>'',
										  'payment_status'=>'unpaid',
										  'created_by_id' =>2,
										  'created_datetime'=>date('Y-m-d H:i:s'),
										  'booked_datetime'=>date('Y-m-d H:i:s'),
										  'booked_by_id'=>2,
										  'attributes'=>json_encode($post_params),
										  'user_attributes'=>"",
										  'email'=>'nikhildas.provab@gmail.com',
										  'tours_id'=>$post_params['tour_id'],
							);
							if($this->db->insert('tour_booking_details',$tour_details)){
								// debug($app_reference);exit;
									$return_data=$this->tours_model->insert_pax_details_trvl($app_reference,$post_params);
									// debug($return_data);exit;
							}
							$combi_data['hotel_book_id']=$book_id;
							$combi_data['tour_book_id']=$app_reference;
							$combi_data['hotel_book_amout']=$amount;
							$combi_data['tour_book_amount']=$post_params['tour_amount'];
							$this->db->insert('comb_booking_details',$combi_data);
							// echo $this->db->last_query();exit;
						}*/
						 
						redirect(base_url().'index.php/hotels/process_booking/'.$book_id.'/'.$book_origin);
						// redirect(base_url().'index.php/payment_gateway/payment/'.$book_id.'/'.$book_origin);
						 break;
						case PAY_AT_BANK : echo 'Under Construction - Remote IO Error';exit;
						break;
					}
			/*} else {
				redirect ( base_url () . 'index.php/hotel/exception?op=Amount Hotel Booking&notification=insufficient_balance' );
			}*/
		} else {
			redirect ( base_url () . 'index.php/hotel/exception?op=Remote IO error @ Hotel Booking&notification=validation' );
		}
	}

	function process_booking($book_id, $temp_book_origin){
		$temp_booking = $this->module_model->unserialize_temp_booking_record($book_id, $temp_book_origin);
		//debug($temp_booking);exit;
		$booking_source = $temp_booking['book_attributes']['token']['booking_source'];
		if($book_id != '' && $temp_book_origin != '' && intval($temp_book_origin) > 0 && $booking_source==CRS_HOTEL_BOOKING_SOURCE){
			$book_origin = $temp_book_origin;
			$room_type_id = $temp_booking['book_attributes']['token']['room_id'];
			$hotel_id = $temp_booking['book_attributes']['token']['HotelCode'];
			$season_type_id = $temp_booking['book_attributes']['token']['season_id'];
			redirect ( 'hotels/secure_bookings/' . $book_id . '/' . $book_origin.'/'.$hotel_id.'/'.$season_type_id.'/'.$season_type_id.'/'.$booking_source);
		}
		else{
			redirect(base_url().'index.php/hotel/exception?op=Invalid request&notification=validation');
		}
		
	}
	function secure_bookings($book_id, $temp_book_origin,$hotelid,$season_id,$room_id,$source) {

		$booking = $this->hotels_model->getBookingDetails($book_id);
		//debug($booking);exit;
		$tour_data=$this->tours_model->search_combi($book_id);
		//echo $this->db->last_query();
		 //debug($tour_data);exit;
		$fl=0;
    	for ($k=0; $k < count($booking); $k++) { 
    		if ($booking[$k]->module == 'HOTEL') {
				if($booking[$k]->payment_status == 'PROCESS'){
					$data = $this->booking_complete_hotel($booking[$k]->id,$book_id,$hotelid,$room_id);
					
					//$data['booking_status']     = 'CONFIRM';
					$data['booking_status'] = $data['booking_status'];
					$this->hotels_model->update_globel_booking_status($booking[$k]->id,$data);
					if($tour_data['status']==1)
						{
					// debug($tour_data['status']);exit;
							$tour_booking_details = array(
							'payment_status'=>'paid',
							'status'=>'BOOKING_CONFIRMED',
							

							);
							$app_reference=$tour_data['app_reference'];
							if($this->custom_db->update_record('tour_booking_details',$tour_booking_details,array('app_reference'=>$app_reference))){
							$booking['status'] =SUCCESS_STATUS;

							


						    }
						     echo "1";exit;
							redirect(base_url().'index.php/voucher/hotel_crs_tours/'.$book_id.'/'.$source.'/'.$data['booking_status'].'/show_voucher');
						}

						

						//Balance deduction
					$currency_obj = new currency();
					$transaction_currency = get_application_currency_preference ();
					$application_currency = admin_base_currency ();
					$currency_conversion_rate = $currency_obj->transaction_currency_conversion_rate ();

					//debug($currency_conversion_rate);die("cur");

							$data['fare'] = $booking[0]->total_room_price;
							$data['admin_markup'] = $booking[0]->admin_markup;
							$data['agent_markup'] = $booking[0]->agent_markup_only;
							$data['convinence'] = 0;
							$data['discount'] = $booking[0]->discount_amount;
							$data['transaction_currency'] = $transaction_currency;
							$data['currency_conversion_rate'] = $currency_conversion_rate;
							//echo "2";exit;


				$agent_paybleamount = $currency_obj->get_agent_paybleamount($booking[0]->total_room_price);
				//debug($agent_paybleamount);
				
				$domain_balance_status = $this->domain_management_model->verify_current_balance($agent_paybleamount['amount'], $agent_paybleamount['currency']);

				//debug($domain_balance_status);die("---721");

				if ($domain_balance_status) {

							$this->domain_management_model->update_transaction_details('hotel', $book_id, $data['fare'], $data['admin_markup'], $data['agent_markup'], $data['convinence'], $data['discount'],$data['transaction_currency'], $data['currency_conversion_rate'],'crs_booking');
					//$this->sendmail($book_id,$source,$data['booking_status'],'email_voucher','');
							//echo "2";exit;
					redirect ( base_url () . 'index.php/voucher/hotels/' . $book_id . '/' . $source . '/'.$data['booking_status'].'/'.'show_voucher');
				}else{

					redirect(base_url().'index.php/hotel/exception?op=Remote IO error @ Insufficient&notification=validation');
				}

				}else{
					$data['booking_status']     = 'FAILED';
					$this->hotels_model->update_globel_booking_status($booking[$k]->id,$data);
					redirect(base_url().'index.php/hotel/exception?op=Remote IO error @ Hotel Secure Booking&notification=validation');
				}
			}
    	}
	}
	function booking_complete_hotel($global_id,$parent_pnr,$hotelid,$room_id){ 
       $hotel_details = $this->hotels_model->getHotelDetailsId($global_id);
	   $booking = $this->hotels_model->getBookingDetails($parent_pnr);
       if(isset($hotel_details->ref_id)){
            $room_nm    = $this->hotels_model->getHotelRoomCount($hotel_details->ref_id);
            if(isset($room_nm->room_count)){
		   		$room_count = '0';
                $hotel_room_type_id = $room_nm->hotel_room_type_id;
                $hotel_details_id   = $hotel_details->hotel_details_id;
                $room_book_details = $this->hotels_model->check_room_count_details($room_count,$hotel_room_type_id,$hotel_details_id);
                if(isset($room_book_details->no_of_room_available)){
                    $no_of_room_available = $room_book_details->no_of_room_available; 
                    $no_of_room           = $room_book_details->no_of_room;
                    $no_of_room_booked    = $room_book_details->no_of_room_booked;
                    $room_count_update    = ($no_of_room_available-$no_of_room_booked); 
                    if($room_count_update >= $room_count){
                        $room_count = $no_of_room_booked+$room_count; 
                        $this->hotels_model->update_hotel_room_count_info($room_count,$hotel_room_type_id,$hotel_details_id);
                        $data['booking_status'] = 'CONFIRM';
                        return $data;
                    }else{
                        $data['booking_status']     = 'FAILED';
                        return $data;
                    }
                }else{
                    $data['booking_status']     = 'FAILED';
                    return $data;
                }
           }else{
              $data['booking_status']     = 'FAILED';
             return $data;      
           }
        }else{
            $data['booking_status']     = 'FAILED';
            return $data;
        }
    }
	function booking($search_id)
	{
		// error_reporting(E_ALL);
		$pre_booking_params = $this->input->post();
		$safe_search_data = $this->hotel_model->get_safe_search_data($search_id);
		// debug($pre_booking_params);die;
		$safe_search_data['data']['search_id'] = abs($search_id);
		$page_data['active_payment_options'] = $this->module_model->get_active_payment_module_list();
		if (isset($pre_booking_params['booking_source']) == true) {
			//We will load different page for different API providers... As we have dependency on API for hotel details page
			$page_data['search_data'] = $safe_search_data['data'];
			load_hotel_lib($pre_booking_params['booking_source']);
			//Need to fill pax details by default if user has already logged in
			$this->load->model('user_model');
			$page_data['pax_details'] = array();
			$agent_details = $this->user_model->get_current_user_details();
			$page_data['agent_address'] = $agent_details[0]['address'];

			header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
			header("Cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");

			if ($pre_booking_params['booking_source'] == PROVAB_HOTEL_BOOKING_SOURCE and
			isset($pre_booking_params['op']) == true and $pre_booking_params['op'] == 'block_room' and $safe_search_data['status'] == true)
			{
				$pre_booking_params['token'] = unserialized_data($pre_booking_params['token'], $pre_booking_params['token_key']);
				if ($pre_booking_params['token'] != false) {

					$room_block_details = $this->hotel_lib->block_room($pre_booking_params);


					// debug($room_block_details); exit;
					if ($room_block_details['status'] == false) {
						redirect(base_url().'index.php/hotel/exception?op='.$room_block_details['data']['msg']);
					}
					
					//Converting API currency data to preferred currency
					$currency_obj = new Currency(array('module_type' => 'hotel','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
					$room_block_details = $this->hotel_lib->roomblock_data_in_preferred_currency($room_block_details, $currency_obj,$search_id,'b2b');
					

		// debug($room_block_details);die;
					//Display
					$currency_obj = new Currency(array('module_type' => 'hotel', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));
					
					$cancel_currency_obj = new Currency(array('module_type' => 'hotel','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));

					$pre_booking_params = $this->hotel_lib->update_block_details($room_block_details['data']['response']['BlockRoomResult'], $pre_booking_params,$cancel_currency_obj);
					/*
					 * Update Markup
					 */
					$pre_booking_params['markup_price_summary'] = $this->hotel_lib->update_booking_markup_currency($pre_booking_params['price_summary'], $currency_obj, $safe_search_data['data']['search_id'], true, true);

					if ($room_block_details['status'] == SUCCESS_STATUS) {
						if(!empty($this->entity_country_code)){
							$page_data['user_country_code'] = $this->entity_country_code;
						}
						else{
							$page_data['user_country_code'] = '';	
						}
						$page_data['booking_source'] = $pre_booking_params['booking_source'];
						$page_data['pre_booking_params'] = $pre_booking_params;
						$page_data['pre_booking_params']['default_currency'] = get_application_default_currency();
						$page_data['iso_country_list']	= $this->db_cache_api->get_iso_country_list();
						$page_data['country_list']		= $this->db_cache_api->get_country_list();
						$page_data['currency_obj']		= $currency_obj;
						$page_data['total_price']		= $this->hotel_lib->total_price($pre_booking_params['markup_price_summary']);
						$page_data['convenience_fees']  = roundoff_price($currency_obj->convenience_fees($page_data['total_price'], $page_data['search_data']['search_id']));
						$page_data['tax_service_sum']	=  $this->hotel_lib->tax_service_sum($pre_booking_params['markup_price_summary'], $pre_booking_params['price_summary']);
						//debug($page_data);exit;
						$Domain_record = $this->custom_db->single_table_records('domain_list', '*');
					$page_data['active_data'] =$Domain_record['data'][0];
					$temp_record = $this->custom_db->single_table_records('api_country_list', '*','',0,100000000,array('name'=>'asc'));
					$page_data['phone_code'] =$temp_record['data'];
					// debug($page_data);
						$this->template->view('hotel/tbo/tbo_booking_page', $page_data);
					}
				}
			}elseif($pre_booking_params['booking_source'] == CRS_HOTEL_BOOKING_SOURCE and
			isset($pre_booking_params['op']) == true and $pre_booking_params['op'] == 'block_room' and $safe_search_data['status'] == true)
			{
				$pre_booking_params['token'] = unserialized_data($pre_booking_params['token'], $pre_booking_params['token_key']);
				if ($pre_booking_params['token'] != false) {

					$room_block_details = $this->hotel_lib->block_room($pre_booking_params);
					// debug($pre_booking_params); exit;


					if ($room_block_details['status'] == false) {
						redirect(base_url().'index.php/hotel/exception?op='.$room_block_details['data']['msg']);
					}
					
					//Converting API currency data to preferred currency
					$currency_obj = new Currency(array('module_type' => 'hotel','from' => admin_base_currency(), 'to' => get_application_currency_preference()));
					$room_block_details = $this->hotel_lib->roomblock_data_in_preferred_currency($room_block_details, $currency_obj,$search_id,'b2b');
				// debug($currency_obj);die;			


		// debug($room_block_details);die;
					//Display
					$currency_obj = new Currency(array('module_type' => 'hotel', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));
					
					$cancel_currency_obj = new Currency(array('module_type' => 'hotel','from' => admin_base_currency(), 'to' => get_application_currency_preference()));

					$pre_booking_params = $this->hotel_lib->update_block_details($room_block_details['data']['response']['BlockRoomResult'], $pre_booking_params,$cancel_currency_obj);

					$specific_markup_config=array();
	$specific_markup_config[]=array('ref_id'=>$pre_booking_params['HotelCode'],'category'=>'hotel_wise');
					/*
					 * Update Markup
					 */
					$pre_booking_params['markup_price_summary'] = $this->hotel_lib->update_booking_markup_currency($pre_booking_params['price_summary'], $currency_obj, $safe_search_data['data']['search_id'], true, true,$specific_markup_config);
					if ($room_block_details['status'] == SUCCESS_STATUS) {
						if(!empty($this->entity_country_code)){
							$page_data['user_country_code'] = $this->entity_country_code;
						}
						else{
							$page_data['user_country_code'] = '';	
						}
						$page_data['booking_source'] = $pre_booking_params['booking_source'];
						$page_data['pre_booking_params'] = $pre_booking_params;
						$page_data['pre_booking_params']['default_currency'] = get_application_default_currency();
						$page_data['iso_country_list']	= $this->db_cache_api->get_iso_country_list();
						$page_data['country_list']		= $this->db_cache_api->get_country_list();
						$page_data['currency_obj']		= $currency_obj;
						$page_data['total_price']		= $this->hotel_lib->total_price($pre_booking_params['markup_price_summary']);
						$page_data['convenience_fees']  =0; //$currency_obj->convenience_fees($page_data['total_price'], $page_data['search_data']['search_id']);
					// debug($page_data);die;
						$page_data['tax_service_sum']	=  $this->hotel_lib->tax_service_sum($pre_booking_params['markup_price_summary'], $pre_booking_params['price_summary']);
						//debug($page_data);exit;
						$Domain_record = $this->custom_db->single_table_records('domain_list', '*');
					$page_data['active_data'] =$Domain_record['data'][0];
					$temp_record = $this->custom_db->single_table_records('api_country_list', '*','',0,100000000,array('name'=>'asc'));
					$page_data['phone_code'] =$temp_record['data'];
					// debug($page_data);die;
						$this->template->view('hotel/tbo/crs_booking_page', $page_data);
					}
				}		
			}
			else {
				redirect(base_url());
			}
		} else {
			redirect(base_url());
		}
	}
	/**
	 *  Arjun J Gowda
	 * Passenger Details page for final bookings
	 * Here we need to run booking based on api
	 */
	function bookingold($search_id)
	{				
		//error_reporting(E_ALL); ini_set('display_error', 'on');
		$pre_booking_params = $this->input->post();

		$safe_search_data = $this->hotel_model->get_safe_search_data($search_id);

		$safe_search_data['data']['search_id'] = abs($search_id);
		$page_data['active_payment_options'] = $this->module_model->get_active_payment_module_list();
		
		//echo $pre_booking_params['booking_source']; exit();
		if (isset($pre_booking_params['booking_source']) == true) {
			
			//debug($currency_obj);

			//exit;
			//We will load different page for different API providers... As we have dependency on API for hotel details page
			$page_data['search_data'] = $safe_search_data['data'];
			load_hotel_lib($pre_booking_params['booking_source']);
			//Need to fill pax details by default if user has already logged in
			$this->load->model('user_model');
			$page_data['pax_details'] = $this->user_model->get_current_user_details();

			header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
			header("Cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
			/*echo 'Booking ';
			debug($pre_booking_params); exit;*/

			 if ($pre_booking_params['booking_source'] == CLEAR_TRIP_HOTEL_API && isset($pre_booking_params['rateKey']) == true and
			valid_array($pre_booking_params['rateKey']) == true and	$safe_search_data['status'] == true &&
			valid_array($pre_booking_params['rooms']) == true && valid_array($pre_booking_params['adults']) == true) {
				//Storing the Rooms and Pax Details in Room Configuration
				$room_configuration = array();
			//debug($pre_booking_params);exit;
				$room_configuration['rooms'] = $pre_booking_params['rooms'];
				$room_configuration['adults'] = $pre_booking_params['adults'];
				$room_configuration['childs'] = $pre_booking_params['childs'];
				//Unset the Rooms and Pax details
				unset($pre_booking_params['rooms'], $pre_booking_params['adults'], $pre_booking_params['childs']);

				$raw_hotel_details = $this->hotel_lib->read_token($pre_booking_params['token']);

				//debug($raw_hotel_details);exit;

				$previous_page_currency = ($raw_hotel_details['currency']?$raw_hotel_details['currency']:get_application_default_currency());

				$currency_obj = new Currency(array('module_type' => 'hotel', 'from' => $previous_page_currency, 'to' => get_application_display_currency_preference()));


				if (valid_array($raw_hotel_details) == true) {
					$room_rate_Keys = $pre_booking_params['rateKey'];
					$roomCommentId = array();
					$roomCommentId_arr = array();
					/*get ratecomment id of selected rooms*/
					
					//Run Check Rates Request
					$room_paxes_detials = array();
					$room_paxes_cnt = 0;//die;
					//$updated_room_details = $this->hotel_lib->check_room_rate($room_rate_Keys,$search_id,'b2c');
					
					$search_data_res = $this->hotel_model->get_search_data ( $search_id );
					//debug($search_data_res);exit;
					$search_data = json_decode($search_data_res['search_data'],true);					
					// echo 'Raw Data ';					
					// debug($raw_hotel_details);
					// echo 'Pre Booking';
					// debug($pre_booking_params);exit;
					//debug($currency_obj);

					if(isset($raw_hotel_details['rooms']) && valid_array($raw_hotel_details['rooms'])) 
					{
						$room_list = $raw_hotel_details['rooms'];
						//debug($room_list);exit;
						for($rl=0; $rl < count($room_list['room_type']); $rl++) {

							//echo 'Booking Code '.($pre_booking_params['rateKey'][0] == $room_list['booking_code'][$rl]).'<br>';
							if(isset($room_list['booking_code'][$rl]) && ($pre_booking_params['rateKey'][0] == $room_list['booking_code'][$rl])) {
								
								$room_name = $room_list['room_type'][$rl]['room-description'];
								$room_code = $room_list['room_type'][$rl]['room-type-code'];

								$room_rate = $room_list['room_rate'][$rl];
								$room_paxes_detials[$room_paxes_cnt]['rateKey'] = $room_list['booking_code'][$rl];
								$room_paxes_detials[$room_paxes_cnt]['room_code_type'] = $room_code;

								$j=1;
								
								if($search_data['rooms'] > 0)
								{ 
									for($rc=0; $rc < $search_data['rooms']; $rc++)
									{	
										$room_paxes_detials[$room_paxes_cnt]['rooms'][$rc]['no_of_adults'] = $search_data['adult'][$rc];
										$room_paxes_detials[$room_paxes_cnt]['rooms'][$rc]['no_of_children'] = $search_data['child'][$rc];
										$room_paxes_detials[$room_paxes_cnt]['rooms'][$rc]['childrenAges'] = implode(',',$search_data['childAge_'.$j]);	
							
										$j+=1;		
									}	
								}
							
								$room_paxes_detials[$room_paxes_cnt]['no_of_rooms'] = $search_data['rooms'];											
								$room_paxes_detials[$room_paxes_cnt]['base_fare'] = $room_rate['base_fare'];	
								$room_paxes_detials[$room_paxes_cnt]['tax_fare'] = $room_rate['tax_fare'];	

								$room_paxes_detials[$room_paxes_cnt]['discount_fare'] = $room_rate['discount_fare'];

								//$room_paxes_detials[$room_paxes_cnt]['net'] = $room_rate['net_fare']['default_value'];

								$room_paxes_detials[$room_paxes_cnt]['original_value'] = $room_rate['net_fare'];

								//$room_paxes_detials[$room_paxes_cnt]['net']=$room_rate['net_fare'];

								//updating markup currency
								
								$conversion_price = $room_rate['net_fare'];
								//echo $conversion_price;
								$price = $this->hotel_lib->update_booking_markup_currency($conversion_price,$currency_obj,$search_id);

								// debug($price);
								// exit;
								//$room_paxes_detials[$room_paxes_cnt]['net'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ($room_rate['net_fare']) );

								$room_paxes_detials[$room_paxes_cnt]['net']
								 =ceil($price['value']);

								

								//$room_paxes_detials[$room_paxes_cnt]['net'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ($room_rate['net_fare']['default_value']) );


								$room_paxes_detials[$room_paxes_cnt]['currency'] = $price['currency'];

								//$room_paxes_detials[$room_paxes_cnt]['boardName'] = $rates_details['boardName'];
								
								$room_paxes_detials[$room_paxes_cnt]['room_name'] = $room_name;
								
							}
						}
					}
				
					//debug($room_paxes_detials); exit;
					$total_price = $room_paxes_detials[0]['net'];
					$published_price = $room_paxes_detials[0]['original_value'];
					
					$page_data['booking_source'] = $pre_booking_params['booking_source'];
					$page_data['pre_booking_params'] = $pre_booking_params;
					$page_data['pre_booking_params']['search_id'] = $search_id;

					/*$page_data['accomodation_type_code'] = $accomodation_type_code;
					$page_data['category_code'] = $category_code;
					$page_data['categoryName'] = $categoryName;*/
					$page_data['pre_booking_params']['room_configuration'] = $room_configuration;
					$page_data['room_paxes_details'] = $room_paxes_detials;
					$page_data['pre_booking_params']['default_currency'] = get_application_default_currency();
					$page_data['iso_country_list']	= $this->db_cache_api->get_iso_country_list();
					$page_data['country_list']		= $this->db_cache_api->get_country_list();
					$page_data['country_code'] = $this->db_cache_api->get_country_code_list();
					$page_data['currency_obj']		= $currency_obj;
					//debug($page_data); exit;
					$page_data['converted_currency_rate'] = $currency_obj->getConversionRate(false);
					$page_data['total_price']		= $total_price; //@$updated_room_details['data']['total_price'];
					$page_data['Published_Price']  = $published_price;
					$page_data['convenience_fees']  = number_format($currency_obj->convenience_fees($page_data['total_price'], $page_data['search_data']['search_id']),2);

					$search_hash = md5(serialized_data($search_id));
					$page_data['session_expiry_details'] = $this->set_hotel_search_session_expiry(true, $search_hash);
					//debug($page_data);exit;
					$page_data['term_policy'] = $this->custom_db->get_result_by_query('SELECT * FROM terms_n_condition WHERE module_name="hotel"');
					
					$raw_hotel_details['convenience_fees'] = $page_data['convenience_fees'];
					$page_data['pre_booking_params']['token'] = $raw_hotel_details;
					//$page_data['tax_service_sum']	=  $this->hotel_lib->tax_service_sum($pre_booking_params['markup_price_summary'], $pre_booking_params['price_summary']);
					$page_data['tax_service_sum']	=  '';////FIXME
					//Traveller Details
					$page_data['traveller_details'] = $this->user_model->get_current_user_details();
					/*$page_data['hotel_address_inf'] = $raw_hotel_details['address'].$raw_hotel_details['postal'];*/
					$page_data['hotel_address_inf'] = $raw_hotel_details['address'].' '.$raw_hotel_details['zone_code'].','.$raw_hotel_details['destination'].','.$safe_search_data['data']['country_name'];
					/*$page_data['getrateComment'] = $getrateComment;
					$page_data['base_en_rate'] = $base_en_rate;*/
					$page_data['hotel_total_price'] = $total_price; //$updated_room_details['data']['rooms'][0]['rates'][0]['net'] * $updated_room_details['data']['rooms'][0]['rates'][0]['rooms'];
					$page_data['app_supported_currency'] = $this->db_cache_api->get_currency_booking ( array (
												'k' => 'country',
												'v' => array (
														'value',
														'country',
														'currency_name'
												) 
										));
					$page_data['hotel_search_params'] = $safe_search_data['data'];
					
					// debug($page_data['pre_booking_params']);
					// exit;
					$this->template->view('hotel/cleartrip/booking_page', $page_data);

				} else {
					redirect(base_url().'index.php/hotel/exception?op=Data Modification&notification=Data modified while transfer(Invalid Data received while validating tokens)');
				}
			}
		}
		else {
			redirect(base_url().'index.php/hotel/exception?op=Booking Source Not Available&notification=Booking Source Not Available');
		}			
	
	}
	function booking_old($search_id)
	{
		//error_reporting(E_ALL); ini_set('display_error', 'on');
		$pre_booking_params = $this->input->post();

		$safe_search_data = $this->hotel_model->get_safe_search_data($search_id);
		$safe_search_data['data']['search_id'] = abs($search_id);
		$page_data['active_payment_options'] = $this->module_model->get_active_payment_module_list();
		// echo $pre_booking_params['booking_source']; exit();
		if (isset($pre_booking_params['booking_source']) == true) {
			$currency_obj = new Currency(array('module_type' => 'hotel', 'from' => get_application_default_currency(), 'to' => get_application_default_currency()));
			//We will load different page for different API providers... As we have dependency on API for hotel details page
			$page_data['search_data'] = $safe_search_data['data'];
			load_hotel_lib($pre_booking_params['booking_source']);
			//Need to fill pax details by default if user has already logged in
			$this->load->model('user_model');
			$page_data['pax_details'] = $this->user_model->get_current_user_details();

			header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
			header("Cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");

			/*echo 'Booking ';
			debug($pre_booking_params); exit;*/

			if ($pre_booking_params['booking_source'] == HB_HOTEL_BOOKING_SOURCE && isset($pre_booking_params['rateKey']) == true and
			valid_array($pre_booking_params['rateKey']) == true and	$safe_search_data['status'] == true &&
			valid_array($pre_booking_params['rooms']) == true && valid_array($pre_booking_params['adults']) == true) {
				//Storing the Rooms and Pax Details in Room Configuration
				$room_configuration = array();
				$room_configuration['rooms'] = $pre_booking_params['rooms'];
				$room_configuration['adults'] = $pre_booking_params['adults'];
				$room_configuration['childs'] = $pre_booking_params['childs'];
				//Unset the Rooms and Pax details
				unset($pre_booking_params['rooms'], $pre_booking_params['adults'], $pre_booking_params['childs']);

				$raw_hotel_details = $this->hotel_lib->read_token($pre_booking_params['token']);
				//debug($raw_hotel_details); exit;
				$accomodation_type_code = $raw_hotel_details['accomodation_type_code'];
				$category_code = $raw_hotel_details['category_code'];
				$categoryName = $raw_hotel_details['categoryName'];
				if (valid_array($raw_hotel_details) == true) {
					$room_rate_Keys = $pre_booking_params['rateKey'];
					$roomCommentId = array();
					$roomCommentId_arr = array();
					/*get ratecomment id of selected rooms*/
					foreach($raw_hotel_details['rooms'] as $k => $val) {
						foreach($val as $rKey => $rate) {
							if(!empty($rate['rateCommentsId']) && in_array($rate['rateKey'],$room_rate_Keys) && !in_array($rate['rateCommentsId'],$roomCommentId_arr)) {
								$roomCommentId_arr[] = $rate['rateCommentsId'];
								$roomCommentId[] = array(
									'rateKey' => $rate['rateKey'],
									'rateCommentsId' => $rate['rateCommentsId']
								);
							}
						}
					}
						
					$getrateComment = '';

					/*get rate comment details*/
					//debug($roomCommentId);exit;
					if(isset($roomCommentId) && valid_array($roomCommentId)) {
						$getrateComment = $this->hotel_lib->get_rate_comment_details($roomCommentId,$safe_search_data['data']['from_date'],$safe_search_data['data']['to_date']);
						$getrateComment_arr['status'] = 1;
						$getrateComment_arr['comment'] = $getrateComment;
					}else{
						$getrateComment_arr['status'] = 0;
					}
					
					$base_en_rate = base64_encode(json_encode($getrateComment_arr));
					
					//Run Check Rates Request
					$room_paxes_detials = array();
					$room_paxes_cnt = 0;//die;
					$updated_room_details = $this->hotel_lib->check_room_rate($room_rate_Keys,$search_id,'b2c');
					// debug($updated_room_details);die;
					if(isset($updated_room_details['data']['rooms']) && valid_array($updated_room_details['data']['rooms'])) {
						foreach($updated_room_details['data']['rooms'] as $room_key_d => $room_detials) {
							if(isset($room_detials['rates']) && valid_array($room_detials['rates'])) {
								$room_name = $room_detials['room_name'];
								
								foreach($room_detials['rates'] as $rate_key => $rates_details) {
									$room_paxes_detials[$room_paxes_cnt]['room_name'] = $room_detials['room_name'];
									$room_paxes_detials[$room_paxes_cnt]['rateKey'] = $rates_details['rateKey'];
									$room_paxes_detials[$room_paxes_cnt]['no_of_rooms'] = $rates_details['rooms'];
									$room_paxes_detials[$room_paxes_cnt]['no_of_adults'] = $rates_details['adults'];
									$room_paxes_detials[$room_paxes_cnt]['no_of_children'] = $rates_details['children'];
									$room_paxes_detials[$room_paxes_cnt]['net'] = $rates_details['net'];
									$room_paxes_detials[$room_paxes_cnt]['currency'] = $updated_room_details['data']['currency'];
									$room_paxes_detials[$room_paxes_cnt]['boardName'] = $rates_details['boardName'];
									$room_paxes_detials[$room_paxes_cnt]['childrenAges'] = $rates_details['childrenAges'];
									$room_paxes_detials[$room_paxes_cnt]['room_name'] = $room_name;
									$room_paxes_cnt++;
								}
							}
						}
					}
						
					if ($updated_room_details['status'] == false) {
						redirect(base_url().'index.php/hotel/exception?op='.$room_block_details['data']['msg']);
					}
					/*
					 * Update Markup: FIXME
					 */
					//$pre_booking_params['markup_price_summary'] = $this->hotel_lib->update_booking_markup_currency($pre_booking_params['price_summary'], $currency_obj, $safe_search_data['data']['search_id']);
					$page_data['booking_source'] = $pre_booking_params['booking_source'];
					$page_data['pre_booking_params'] = $pre_booking_params;
					$page_data['pre_booking_params']['search_id'] = $search_id;
					$page_data['accomodation_type_code'] = $accomodation_type_code;
					$page_data['category_code'] = $category_code;
					$page_data['categoryName'] = $categoryName;
					$page_data['pre_booking_params']['room_configuration'] = $room_configuration;

					$page_data['room_paxes_details'] = $room_paxes_detials;

					$page_data['pre_booking_params']['default_currency'] = get_application_default_currency();
					$page_data['iso_country_list']	= $this->db_cache_api->get_iso_country_list();
					$page_data['country_list']		= $this->db_cache_api->get_country_list();
					$page_data['country_code'] = $this->db_cache_api->get_country_code_list();
					$page_data['currency_obj']		= $currency_obj;
					$page_data['total_price']		= @$updated_room_details['data']['total_price'];
					$page_data['convenience_fees']  = number_format($currency_obj->convenience_fees($page_data['total_price'], $page_data['search_data']['search_id']),2);
					$page_data['term_policy'] = $this->custom_db->get_result_by_query('SELECT * FROM terms_n_condition WHERE module_name="hotel"');
					

					$updated_room_details['convenience_fees'] = $page_data['convenience_fees'];
					$page_data['pre_booking_params']['token'] = $updated_room_details;
					//$page_data['tax_service_sum']	=  $this->hotel_lib->tax_service_sum($pre_booking_params['markup_price_summary'], $pre_booking_params['price_summary']);
					$page_data['tax_service_sum']	=  '';////FIXME
					//Traveller Details
					$page_data['traveller_details'] = $this->user_model->get_current_user_details();
					/*$page_data['hotel_address_inf'] = $raw_hotel_details['address'].$raw_hotel_details['postal'];*/
					$page_data['hotel_address_inf'] = $raw_hotel_details['address'].' '.$raw_hotel_details['postal'].','.$raw_hotel_details['destination'].','.$safe_search_data['data']['country_name'];
					$page_data['getrateComment'] = $getrateComment;
					$page_data['base_en_rate'] = $base_en_rate;
					$page_data['hotel_total_price'] = $updated_room_details['data']['rooms'][0]['rates'][0]['net'] * $updated_room_details['data']['rooms'][0]['rates'][0]['rooms'];
					$page_data['app_supported_currency'] = $this->db_cache_api->get_currency_booking ( array (
												'k' => 'country',
												'v' => array (
														'value',
														'country',
														'currency_name'
												) 
										));
					//debug($page_data); exit();
					$this->template->view('hotel/hotelbeds/hotelbeds_booking_page', $page_data);

				} else {
					redirect(base_url().'index.php/hotel/exception?op=Data Modification&notification=Data modified while transfer(Invalid Data received while validating tokens)');
				}
			} else if($pre_booking_params['booking_source'] == GTA_HOTEL_BOOKING_SOURCE) {
				$room_configuration = array();
				$room_configuration['rooms'] = $pre_booking_params['rooms'];
				$room_configuration['adults'] = $pre_booking_params['adults'];
				$room_configuration['childs'] = $pre_booking_params['childs'];
				$rate_keys = $pre_booking_params['rateKey'];
				//Unset the Rooms and Pax details
				//unset($pre_booking_params['rooms'], $pre_booking_params['adults'], $pre_booking_params['childs']);

				$raw_hotel_details = $this->hotel_lib->read_token($pre_booking_params['token']);
				if (valid_array($raw_hotel_details) == true) {
					$hotel_code = $raw_hotel_details['hotel_code'];
						
					//Run Check Rates Request
					$room_paxes_detials = array();
					$room_paxes_cnt = 0;
					$updated_room_details = $this->hotel_lib->check_room_rate($hotel_code,$rate_keys,$search_id,'b2c');
					//debug($safe_search_data);debug($updated_room_details);exit;
					if(isset($updated_room_details['data']['rooms']) && valid_array($updated_room_details['data']['rooms'])) {
						$total_room = $updated_room_details['data']['rooms']['rooms'];
						for($i=0;$i<$total_room;$i++) {
							$room_paxes_detials[$room_paxes_cnt]['room_name'] =  $updated_room_details['data']['rooms']['name'];
							$room_paxes_detials[$room_paxes_cnt]['rateKey'] = $updated_room_details['data']['rooms']['rate_key'];
							$room_paxes_detials[$room_paxes_cnt]['no_of_rooms'] = 1;
							$room_paxes_detials[$room_paxes_cnt]['no_of_adults'] = $safe_search_data['data']['adult_config'][$i];
							$room_paxes_detials[$room_paxes_cnt]['no_of_children'] = $safe_search_data['data']['child_config'][$i];
							$room_paxes_detials[$room_paxes_cnt]['net'] = $updated_room_details['data']['rooms']['total_price'];
							$room_paxes_detials[$room_paxes_cnt]['currency'] = $updated_room_details['data']['currency'];
							$room_paxes_cnt++;
						}
						//$updated_room_details['data']['rooms'] = force_multple_data_format($updated_room_details['data']['rooms']);
						//						foreach($updated_room_details['data']['rooms'] as $room_key_d => $room_detials) {
						//							$room_paxes_detials[$room_paxes_cnt]['room_name'] = $room_detials['name'];
						//							$room_paxes_detials[$room_paxes_cnt]['rateKey'] = $room_detials['rate_key'];
						//							$room_paxes_detials[$room_paxes_cnt]['no_of_rooms'] = $room_detials['rooms'];
						//							$room_paxes_detials[$room_paxes_cnt]['no_of_adults'] = $room_detials['adults'];
						//							$room_paxes_detials[$room_paxes_cnt]['no_of_children'] = $room_detials['children'];
						//							$room_paxes_cnt++;
						//						}
						}
						//debug($room_paxes_detials);exit;
						if ($updated_room_details['status'] == false) {
							redirect(base_url().'index.php/hotel/exception?op=error');
						}
						$page_data['booking_source'] = $pre_booking_params['booking_source'];
						$page_data['pre_booking_params'] = $pre_booking_params;
						$page_data['pre_booking_params']['search_id'] = $search_id;
							
						$page_data['pre_booking_params']['room_configuration'] = $room_configuration;
						$page_data['room_paxes_details'] = $room_paxes_detials;
						$page_data['pre_booking_params']['default_currency'] = get_application_default_currency();
						$page_data['iso_country_list']	= $this->db_cache_api->get_iso_country_list();
						$page_data['country_list']		= $this->db_cache_api->get_country_list();
						$page_data['country_code'] = $this->db_cache_api->get_country_code_list();
						$page_data['currency_obj']		= $currency_obj;
						$page_data['total_price']		= @$updated_room_details['data']['total_price'];
						$page_data['convenience_fees']  = number_format($currency_obj->convenience_fees($page_data['total_price'], $page_data['search_data']['search_id']),2);
							
						$updated_room_details['convenience_fees'] = $page_data['convenience_fees'];
						$page_data['pre_booking_params']['token'] = $updated_room_details;
						//$page_data['tax_service_sum']	=  $this->hotel_lib->tax_service_sum($pre_booking_params['markup_price_summary'], $pre_booking_params['price_summary']);
						$page_data['tax_service_sum']	=  '';////FIXME
						//Traveller Details
						$page_data['traveller_details'] = $this->user_model->get_current_user_details();
						$page_data['hotel_address_inf'] = $raw_hotel_details['address'];
						$page_data['hotel_total_price'] = @$updated_room_details['data']['total_price'];
						$page_data['app_supported_currency'] = $this->db_cache_api->get_currency_booking ( array (		
												'k' => 'country',		
												'v' => array (		
														'value',		
														'country',		
														'currency_name'		
												) 		
										));
						//debug($page_data); exit;
						$this->template->view('hotel/gta/gta_booking_page', $page_data);
					} else {
						redirect(base_url().'index.php/hotel/exception?op=Data Modification&notification=Data modified while transfer(Invalid Data received while validating tokens)');
					}
				}
				else if ($pre_booking_params['booking_source'] == CLEAR_TRIP_HOTEL_API && isset($pre_booking_params['rateKey']) == true and
			valid_array($pre_booking_params['rateKey']) == true and	$safe_search_data['status'] == true &&
			valid_array($pre_booking_params['rooms']) == true && valid_array($pre_booking_params['adults']) == true) {
				//Storing the Rooms and Pax Details in Room Configuration
				$room_configuration = array();
				$room_configuration['rooms'] = $pre_booking_params['rooms'];
				$room_configuration['adults'] = $pre_booking_params['adults'];
				$room_configuration['childs'] = $pre_booking_params['childs'];
				//Unset the Rooms and Pax details
				

				unset($pre_booking_params['rooms'], $pre_booking_params['adults'], $pre_booking_params['childs']);


				$raw_hotel_details = $this->hotel_lib->read_token($pre_booking_params['token']);
				
				//debug($raw_hotel_details); exit;
				//$accomodation_type_code = $raw_hotel_details['accomodation_type_code'];
				//$category_code = $raw_hotel_details['category_code'];
				//$categoryName = $raw_hotel_details['categoryName'];
				if (valid_array($raw_hotel_details) == true) {
					$room_rate_Keys = $pre_booking_params['rateKey'];
					$roomCommentId = array();
					$roomCommentId_arr = array();
					/*get ratecomment id of selected rooms*/
					foreach($raw_hotel_details['rooms'] as $k => $val) {

						/*foreach($val as $rKey => $rate) {
							if(!empty($rate['rateCommentsId']) && in_array($rate['rateKey'],$room_rate_Keys) && !in_array($rate['rateCommentsId'],$roomCommentId_arr)) {
								$roomCommentId_arr[] = $rate['rateCommentsId'];
								$roomCommentId[] = array(
									'rateKey' => $rate['rateKey'],
									'rateCommentsId' => $rate['rateCommentsId']
								);
							}
						}*/
					}
						
					$getrateComment = '';

					/*get rate comment details*/
					//debug($roomCommentId);exit;
					/*if(isset($roomCommentId) && valid_array($roomCommentId)) {
						$getrateComment = $this->hotel_lib->get_rate_comment_details($roomCommentId,$safe_search_data['data']['from_date'],$safe_search_data['data']['to_date']);
						$getrateComment_arr['status'] = 1;
						$getrateComment_arr['comment'] = $getrateComment;
					}else{
						$getrateComment_arr['status'] = 0;
					}*/
					
					$base_en_rate = base64_encode(json_encode($getrateComment_arr));
					
					//Run Check Rates Request
					$room_paxes_detials = array();
					$room_paxes_cnt = 0;//die;
					$updated_room_details = $this->hotel_lib->check_room_rate($room_rate_Keys,$search_id,'b2c');
					// debug($updated_room_details);die;
					if(isset($updated_room_details['data']['rooms']) && valid_array($updated_room_details['data']['rooms'])) {
						foreach($updated_room_details['data']['rooms'] as $room_key_d => $room_detials) {
							if(isset($room_detials['rates']) && valid_array($room_detials['rates'])) {
								$room_name = $room_detials['room_name'];
								foreach($room_detials['rates'] as $rate_key => $rates_details) {
									$room_paxes_detials[$room_paxes_cnt]['room_name'] = $room_detials['room_name'];
									$room_paxes_detials[$room_paxes_cnt]['rateKey'] = $rates_details['rateKey'];
									$room_paxes_detials[$room_paxes_cnt]['no_of_rooms'] = $rates_details['rooms'];
									$room_paxes_detials[$room_paxes_cnt]['no_of_adults'] = $rates_details['adults'];
									$room_paxes_detials[$room_paxes_cnt]['no_of_children'] = $rates_details['children'];
									$room_paxes_detials[$room_paxes_cnt]['net'] = $rates_details['net'];
									$room_paxes_detials[$room_paxes_cnt]['currency'] = $updated_room_details['data']['currency'];
									$room_paxes_detials[$room_paxes_cnt]['boardName'] = $rates_details['boardName'];
									$room_paxes_detials[$room_paxes_cnt]['childrenAges'] = $rates_details['childrenAges'];
									$room_paxes_detials[$room_paxes_cnt]['room_name'] = $room_name;
									$room_paxes_cnt++;
								}
							}
						}
					}
						
					if ($updated_room_details['status'] == false) {
						redirect(base_url().'index.php/hotel/exception?op='.$room_block_details['data']['msg']);
					}
					/*
					 * Update Markup: FIXME
					 */
					//$pre_booking_params['markup_price_summary'] = $this->hotel_lib->update_booking_markup_currency($pre_booking_params['price_summary'], $currency_obj, $safe_search_data['data']['search_id']);
					$page_data['booking_source'] = $pre_booking_params['booking_source'];
					$page_data['pre_booking_params'] = $pre_booking_params;
					$page_data['pre_booking_params']['search_id'] = $search_id;
					$page_data['accomodation_type_code'] = $accomodation_type_code;
					$page_data['category_code'] = $category_code;
					$page_data['categoryName'] = $categoryName;
					$page_data['pre_booking_params']['room_configuration'] = $room_configuration;
					$page_data['room_paxes_details'] = $room_paxes_detials;
					$page_data['pre_booking_params']['default_currency'] = get_application_default_currency();
					$page_data['iso_country_list']	= $this->db_cache_api->get_iso_country_list();
					$page_data['country_list']		= $this->db_cache_api->get_country_list();
					$page_data['country_code'] = $this->db_cache_api->get_country_code_list();
					$page_data['currency_obj']		= $currency_obj;
					$page_data['total_price']		= @$updated_room_details['data']['total_price'];
					$page_data['convenience_fees']  = number_format($currency_obj->convenience_fees($page_data['total_price'], $page_data['search_data']['search_id']),2);
					$page_data['term_policy'] = $this->custom_db->get_result_by_query('SELECT * FROM terms_n_condition WHERE module_name="hotel"');
						
					$updated_room_details['convenience_fees'] = $page_data['convenience_fees'];
					$page_data['pre_booking_params']['token'] = $updated_room_details;
					//$page_data['tax_service_sum']	=  $this->hotel_lib->tax_service_sum($pre_booking_params['markup_price_summary'], $pre_booking_params['price_summary']);
					$page_data['tax_service_sum']	=  '';////FIXME
					//Traveller Details
					$page_data['traveller_details'] = $this->user_model->get_current_user_details();
					/*$page_data['hotel_address_inf'] = $raw_hotel_details['address'].$raw_hotel_details['postal'];*/
					$page_data['hotel_address_inf'] = $raw_hotel_details['address'].' '.$raw_hotel_details['postal'].','.$raw_hotel_details['destination'].','.$safe_search_data['data']['country_name'];
					$page_data['getrateComment'] = $getrateComment;
					$page_data['base_en_rate'] = $base_en_rate;
					$page_data['hotel_total_price'] = $updated_room_details['data']['rooms'][0]['rates'][0]['net'] * $updated_room_details['data']['rooms'][0]['rates'][0]['rooms'];
					$page_data['app_supported_currency'] = $this->db_cache_api->get_currency_booking ( array (
												'k' => 'country',
												'v' => array (
														'value',
														'country',
														'currency_name'
												) 
										));
					//debug($page_data); exit();
					$this->template->view('hotel/hotelbeds/hotelbeds_booking_page', $page_data);

				} else {
					redirect(base_url().'index.php/hotel/exception?op=Data Modification&notification=Data modified while transfer(Invalid Data received while validating tokens)');
				}
			}
			} else {
				redirect(base_url());
			}
		}
		function pre_booking($search_id='')
	{
	    ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
		// redirect(base_url().'index.php/general/booking_not_allowed');		
		// exit;
		$post_params = $this->input->post();
		if($this->entity_status==LOCK && ($post_params ['selected_pm']=="WALLET"))
		{
			redirect(base_url().'index.php/flight/exception?op=locked_user&notification=locked_user');
			exit;
		}

		$post_params['billing_city'] = 'Bangalore';
		$post_params['billing_zipcode'] = '560100';
		$selected_pm=$post_params ['selected_pm'];
		//$this->custom_db->generate_static_response(json_encode($post_params));
		//Insert To temp_booking and proceed
		/*$post_params = $this->hotel_model->get_static_response($static_search_result_id);*/

		//Make sure token and temp token matches
		$valid_temp_token = unserialized_data($post_params['token'], $post_params['token_key']);
		//debug($valid_temp_token);die('56');
		if ($valid_temp_token != false) {
			load_hotel_lib($post_params['booking_source']);
			/****Convert Display currency to Application default currency***/
			//After converting to default currency, storing in temp_booking
			$post_params['token'] = unserialized_data($post_params['token']);
			$post_params['token']['selected_pm'] = $selected_pm;
			$currency_obj = new Currency ( array (
						'module_type' => 'hotel',
						'from' => get_application_currency_preference (),
						'to' => admin_base_currency () 
				));
			
			$post_params['token'] = $this->hotel_lib->convert_token_to_application_currency($post_params['token'], $currency_obj, $this->current_module);
			$post_params['token'] = serialized_data($post_params['token']);
			$temp_token = unserialized_data($post_params['token']);

			//Insert To temp_booking and proceed
			$temp_booking = $this->module_model->serialize_temp_booking_record($post_params, HOTEL_BOOKING);
			//debug($temp_token); exit;
			//debug($temp_booking);die('45');
			$book_id = $temp_booking['book_id'];
			$book_origin = $temp_booking['temp_booking_origin'];
			
			if ($post_params['booking_source'] == PROVAB_HOTEL_BOOKING_SOURCE || $post_params['booking_source'] == REZLIVE_HOTEL) {
				$amount	  = $this->hotel_lib->total_price($temp_token['markup_price_summary']);
				//debug($amount);exit;
				$currency = $temp_token['default_currency'];
			}
			$currency_obj = new Currency ( array (
						'module_type' => 'hotel',
						'from' => admin_base_currency (),
						'to' => admin_base_currency () 
			) );
			/********* Convinence Fees End ********/
			 	
			/********* Promocode Start ********/
			$promocode_discount = 0;
			/********* Promocode End ********/
			if($post_params['markup']>0)
			{
              $amount+=$post_params['markup'];
			}
			//details for PGI
			$email = $post_params ['billing_email'];
			$phone = $post_params ['passenger_contact'];
			$verification_amount = round($amount+$convenience_fees-$promocode_discount);
			$firstname = $post_params ['first_name'] ['0'];
			$productinfo = META_ACCOMODATION_COURSE;
			//check current balance before proceeding further
			$agent_paybleamount = $currency_obj->get_agent_paybleamount($verification_amount);
			$domain_balance_status = $this->domain_management_model->verify_current_balance($agent_paybleamount['amount']+$post_params['markup'], $agent_paybleamount['currency']);
			$selected_pm=$post_params ['selected_pm'];
            if(isset($post_params ['bank_code']) && !empty($post_params ['bank_code'])){
                $bank_code = $post_params ['bank_code'];
            }
            else
                $bank_code = 0;
            $selected_pm_array = explode("_", $selected_pm);
            $selected_pm = $selected_pm_array[0];
            $method = $selected_pm_array[1];
            if($selected_pm == "WALLET")
            	$method = "wallet";
            if($method=="CC")
                $payment_mode = "credit_card";
            else if($method=="DC")
                $payment_mode = "debit_card";
            else if($method=="PPI")
                $payment_mode = "paytm_wallet";
            else if($selected_pm=="TECHP")
                $payment_mode = "net_banking";
            else
                $payment_mode = "wallet";
            $con_row = $this->master_currency->get_instant_recharge_convenience_fees($amount, $method, $bank_code);
            // debug($domain_balance_status); exit;
			if ($domain_balance_status == true || $selected_pm) {
				switch($post_params['payment_method']) {
					case PAY_NOW :
						$this->load->model('transaction');
						$pg_currency_conversion_rate = $currency_obj->payment_gateway_currency_conversion_rate();
						$this->transaction->create_payment_record($book_id, $amount, $firstname, $email, $phone, $productinfo, $con_row['cf'], $promocode_discount, $pg_currency_conversion_rate, $selected_pm, $payment_mode);
						redirect(base_url().'index.php/hotel/process_booking/'.$book_id.'/'.$book_origin.'/'.$selected_pm);
						// redirect(base_url().'index.php/payment_gateway/payment/'.$book_id.'/'.$book_origin.'/'.$selected_pm);
						break;
					case PAY_AT_BANK : echo 'Under Construction - Remote IO Error';exit;
					break;
				}
			} else {
				redirect(base_url().'index.php/hotel/exception?op=Amount Hotel Booking&notification=insufficient_balance');
			}
		} else {
			redirect(base_url().'index.php/hotel/exception?op=Remote IO error @ Hotel Booking&notification=validation');
		}
	}

		function pre_bookingold($search_id=2310, $static_search_result_id=255)
		{
			// redirect(base_url().'index.php/general/booking_not_allowed');		
			// exit;
			$post_params = $this->input->post();
			//debug($post_params);die;
			if($this->entity_status==LOCK && ($post_params ['selected_pm']=="WALLET"))
			{
				redirect(base_url().'index.php/flight/exception?op=locked_user&notification=locked_user');
				exit;
			}
	
			$post_params['billing_city'] = 'Bangalore';
			$post_params['billing_zipcode'] = '560100';
			$selected_pm=$post_params ['selected_pm'];
			//$this->custom_db->generate_static_response(json_encode($post_params));
			//Insert To temp_booking and proceed
			/*$post_params = $this->hotel_model->get_static_response($static_search_result_id);*/
	
			//Make sure token and temp token matches
			$valid_temp_token = unserialized_data($post_params['token'], $post_params['token_key']);
			//debug($valid_temp_token);die('56');
			if ($valid_temp_token != false) {
				load_hotel_lib($post_params['booking_source']);
				/****Convert Display currency to Application default currency***/
				//After converting to default currency, storing in temp_booking
				$post_params['token'] = unserialized_data($post_params['token']);
				$post_params['token']['selected_pm'] = $selected_pm;
				$currency_obj = new Currency ( array (
							'module_type' => 'hotel',
							'from' => get_application_currency_preference (),
							'to' => admin_base_currency () 
					));
				
				$post_params['token'] = $this->hotel_lib->convert_token_to_application_currency($post_params['token'], $currency_obj, $this->current_module);
				$post_params['token'] = serialized_data($post_params['token']);
				$temp_token = unserialized_data($post_params['token']);
	
				//Insert To temp_booking and proceed
				$temp_booking = $this->module_model->serialize_temp_booking_record($post_params, HOTEL_BOOKING);
				
				//debug($temp_booking);die('45');
				$book_id = $temp_booking['book_id'];
				$book_origin = $temp_booking['temp_booking_origin'];
				
				if ($post_params['booking_source'] == CRS_HOTEL_BOOKING_SOURCE || $post_params['booking_source'] == REZLIVE_HOTEL) {
					$amount	  = $this->hotel_lib->total_price($temp_token['markup_price_summary']);
					//debug($amount);exit;
					$currency = $temp_token['default_currency'];
				}
				$currency_obj = new Currency ( array (
							'module_type' => 'hotel',
							'from' => admin_base_currency (),
							'to' => admin_base_currency () 
				) );
				/********* Convinence Fees End ********/
					 
				/********* Promocode Start ********/
				$promocode_discount = 0;
				/********* Promocode End ********/
				if($post_params['markup']>0)
				{
				  $amount+=$post_params['markup'];
				}
				else
				{
					//$amount=total_amount_val;
				}
				//details for PGI
				$email = $post_params ['billing_email'];
				$phone = $post_params ['passenger_contact'];
				$verification_amount = round($amount+$convenience_fees-$promocode_discount);
				$firstname = $post_params ['first_name'] ['0'];
				$productinfo = META_ACCOMODATION_COURSE;
				//check current balance before proceeding further
				$agent_paybleamount = $currency_obj->get_agent_paybleamount($verification_amount);
				$domain_balance_status = $this->domain_management_model->verify_current_balance($agent_paybleamount['amount']+$post_params['markup'], $agent_paybleamount['currency']);
				$selected_pm=$post_params ['selected_pm'];
				if(isset($post_params ['bank_code']) && !empty($post_params ['bank_code'])){
					$bank_code = $post_params ['bank_code'];
				}
				else
					$bank_code = 0;
				$selected_pm_array = explode("_", $selected_pm);
				$selected_pm = $selected_pm_array[0];
				$method = $selected_pm_array[1];
				if($selected_pm == "WALLET")
					$method = "wallet";
				if($method=="CC")
					$payment_mode = "credit_card";
				else if($method=="DC")
					$payment_mode = "debit_card";
				else if($method=="PPI")
					$payment_mode = "paytm_wallet";
				else if($selected_pm=="TECHP")
					$payment_mode = "net_banking";
				else
					$payment_mode = "wallet";
				$con_row = $this->master_currency->get_instant_recharge_convenience_fees($amount, $method, $bank_code);
				// debug($domain_balance_status); exit;
				if ($domain_balance_status == true || $selected_pm) {
					switch($post_params['payment_method']) {
						case PAY_NOW :
							$this->load->model('transaction');
							$pg_currency_conversion_rate = $currency_obj->payment_gateway_currency_conversion_rate();
							$this->transaction->create_payment_record($book_id, $amount, $firstname, $email, $phone, $productinfo, $con_row['cf'], $promocode_discount, $pg_currency_conversion_rate, $selected_pm, $payment_mode);
							redirect(base_url().'index.php/hotel/process_booking/'.$book_id.'/'.$book_origin.'/'.$selected_pm);
							// redirect(base_url().'index.php/payment_gateway/payment/'.$book_id.'/'.$book_origin.'/'.$selected_pm);
							break;
						case PAY_AT_BANK : echo 'Under Construction - Remote IO Error';exit;
						break;
					}
				} else {
					redirect(base_url().'index.php/hotel/exception?op=Amount Hotel Booking&notification=insufficient_balance');
				}
			} else {
				redirect(base_url().'index.php/hotel/exception?op=Remote IO error @ Hotel Booking&notification=validation');
			}
		}
	
		/**
		 *  Arjun J Gowda
		 * Secure Booking of hotel
		 * 255 single adult static booking request 2310
		 * 261 double room static booking request 2308
		 */
		function pre_bookingold($search_id=2310, $static_search_result_id=255)
		{
			$post_params = $this->input->post();			 
			//debug($post_params);exit;
			//echo 'Search Id :'.$search_id;

			$promocode = @$post_params['promocode_val'];
			$promo_data = $this->domain_management_model->promocode_details($promocode);
			$promocode_discount_val = @$promo_data['value'];
			$promocode_discount_type = @$promo_data['value_type'];

			//debug($post_params); 

			$token_data = unserialized_data($post_params['token']);


			//$token_key = unserialized_data($post_params['token_key']);
			//debug($token_data); exit;
			$convenience_fees = str_replace(',', '', $token_data['token']['convenience_fees']);

			$currency_obj = new Currency(array('module_type' => 'hotel', 'from' => get_application_default_currency(), 'to' => get_application_display_currency_preference()));
			$currency_obj->getConversionRate(false,get_application_display_currency_preference(),get_application_default_currency());
			//debug($currency_obj);

			$convenience_fees_con = $currency_obj->get_currency ( $convenience_fees, false, false, true, 1 ); // (ON Total PRICE ONLY)
			//debug($new_price); exit;
			$post_params['convenience_fees'] = $convenience_fees_con['default_value'];
			//$post_params['convenience_fees'] = @$token_data['token']['convenience_fees'];

			$post_params['amount'] = $post_params['base_en_rate'];
			$base_en_rate = $post_params['base_en_rate'];
			$post_params['currency'] = $token_data['token']['currency'];
			//		$post_params['billing_city'] = 'Bangalore';
			
			
			// debug($post_params);
			// exit;
			$temp_booking = $this->module_model->serialize_temp_booking_record($post_params, HOTEL_BOOKING);

			$book_id = $temp_booking['book_id'];
			$book_origin = $temp_booking['temp_booking_origin'];
			//Make sure token and temp token matches

			if (isset($post_params['token']) == true) {
				load_hotel_lib($post_params['booking_source']);
				//			if ($post_params['booking_source'] == HB_HOTEL_BOOKING_SOURCE) {

				/*$amount	  = $this->hotel_lib->total_price($temp_token['markup_price_summary']);
				 $currency = $temp_token['default_currency'];*/
				$amount	  = $base_en_rate;
				$currency = @$token_data['token']['currency'];

				//echo $amount;

				//			}
				$currency_obj = new Currency ( array (
						'module_type' => 'hotel',
						'from' => get_application_default_currency (),
						'to' => get_application_default_currency () 
				) );

				/********* Convinence Fees Start ********/
				//$convenience_fees = ceil($currency_obj->convenience_fees($amount, $search_id));
				$convenience_fees = @$token_data['token']['convenience_fees'];
				
				/********* Convinence Fees End ********/

				/********* Promocode Start ********/
				//$promocode_discount = 0;
				/********* Promocode End ********/
				//details for PGI
					
				if($promocode_discount_type == 'percentage'){
					$promocode_discount = round($amount*($promocode_discount_val/100),2);
				}else{
					$promocode_discount = $promocode_discount_val;
				}
					
				$email = $post_params ['billing_email'];
				$phone = $post_params ['passenger_contact'];
				$verification_amount = number_format(($amount+$convenience_fees-$promocode_discount),2);
				// echo "verification_amount".$verification_amount;
				// exit;
				$firstname = $post_params ['first_name'] ['0'];
				$productinfo = META_ACCOMODATION_COURSE;
				//check current balance before proceeding further
				$domain_balance_status = $this->domain_management_model->verify_current_balance($verification_amount, $currency);
				
				if ($domain_balance_status == true) {
					switch($post_params['payment_method']) {
						case PAY_NOW :
						// redirect('hotel/secure_booking/'.$book_id.'/'.$book_origin);
						// break;
						 $this->load->model('transaction');
						 $this->transaction->create_payment_record($book_id, $verification_amount, $firstname, $email, $phone, $productinfo, $convenience_fees, $promocode_discount,$promocode);
						 
						//redirect(base_url().'index.php/hotel/secure_booking/'.$book_id.'/'.$book_origin);
						redirect(base_url().'index.php/payment_gateway/payment/'.$book_id.'/'.$book_origin);
						 break;
						case PAY_AT_BANK : echo 'Under Construction - Remote IO Error';exit;
						break;
					}
				} else {exit;
				redirect(base_url().'index.php/hotel/exception?op=Amount Hotel Booking&notification=insufficient_balance');
				}
			} else {exit;
			redirect(base_url().'index.php/hotel/exception?op=Remote IO error @ Hotel Booking&notification=validation');
			}
		}

		/**
		 *  Arjun J Gowda
		 *Do booking once payment is successfull - Payment Gateway
		 *and issue voucher
		 *HB11-152109-443266/1
		 *HB11-154107-854480/2
		 */
		 function secure_booking()
	{	
		
		
		$post_data = $this->input->post();
		//debug($post_data);die('22');
		if(valid_array($post_data) == true && isset($post_data['book_id']) == true && isset($post_data['temp_book_origin']) == true &&
			empty($post_data['book_id']) == false && intval($post_data['temp_book_origin']) > 0){
			//verify payment status and continue
			$book_id = trim($post_data['book_id']);
			$temp_book_origin = intval($post_data['temp_book_origin']);
		} else{
			redirect(base_url().'index.php/hotel/exception?op=InvalidBooking&notification=invalid');
		}
		
		//Check whether amount is paid through PG
		$is_paid_by_pg=0;
		// $is_paid_by_pg=$post_data['is_paid_by_pg'];
		//run booking request and do booking
		$temp_booking = $this->module_model->unserialize_temp_booking_record($book_id, $temp_book_origin);

		// debug($temp_booking);die('222');
		//Delete the temp_booking record, after accessing
		//$this->module_model->delete_temp_booking_record ($book_id, $temp_book_origin);
		
		load_hotel_lib($temp_booking['booking_source']);
		//verify payment status and continue
		$total_booking_price = $this->hotel_lib->total_price($temp_booking['book_attributes']['token']['markup_price_summary']);
		// debug($total_booking_price);exit();
		$api_amount = $temp_booking['book_attributes']['token']['price_summary']['PublishedPrice'];

		$currency = $temp_booking['book_attributes']['token']['default_currency'];
		$currency_obj = new Currency(array('module_type' => 'hotel', 'from' => admin_base_currency(), 'to' => admin_base_currency()));
		//also verify provab balance
		//check current balance before proceeding further
		$agent_paybleamount = $currency_obj->get_agent_paybleamount($total_booking_price);
		$domain_balance_status = $this->domain_management_model->verify_current_balance($agent_paybleamount['amount'], $agent_paybleamount['currency']);
		// debug($domain_balance_status);exit('12');
		$selected_pm = $temp_booking['book_attributes']['selected_pm'];
       // debug($selected_pm); exit;
        $selected_pm_array = explode("_", $selected_pm);
        $selected_pm = $selected_pm_array[0];
        $method = $selected_pm_array[1];
        //debug($selected_pm_array); exit;
        if($method=="CC"){
            $temp_booking['book_attributes']['payment_method'] = "credit_card";
            $temp_booking['book_attributes']['bank_code'] = 0;
            $temp_booking['book_attributes']['selected_pm'] = $selected_pm;
        }
        else if($method=="DC"){
            $temp_booking['book_attributes']['payment_method'] = "debit_card";
            $temp_booking['book_attributes']['bank_code'] = 0;
            $temp_booking['book_attributes']['bank_code'] = 0;
            $temp_booking['book_attributes']['selected_pm'] = $selected_pm;
        }
        else if($method=="PPI"){
            $temp_booking['book_attributes']['payment_method'] = "paytm_wallet";
            $temp_booking['book_attributes']['bank_code'] = 0;
            $temp_booking['book_attributes']['selected_pm'] = $selected_pm;
        }
        else if($selected_pm == "TECHP"){
            $temp_booking['book_attributes']['payment_method'] = "net_banking";
        }
        else
        {
        	$temp_booking['book_attributes']['payment_method'] = "wallet";
        }
		if ($domain_balance_status || $is_paid_by_pg) {
			//lock table
		$safe_search_data = $this->hotel_model->get_safe_search_data($temp_booking['book_attributes']['token']['search_id']);
		//debug($safe_search_data);exit();
		$temp_booking['book_attributes']=array_merge($temp_booking['book_attributes'],$safe_search_data['data']);
		//debug($temp_booking['book_attributes']);exit();
			if ($temp_booking != false) {
				switch ($temp_booking['booking_source']) {
					case PROVAB_HOTEL_BOOKING_SOURCE :
					case REZLIVE_HOTEL :
						//FIXME : COntinue from here - Booking request
						$booking = $this->hotel_lib->process_booking($book_id, $temp_booking['book_attributes']);
						//Save booking based on booking status and book id
						//debug($booking);exit();
						break;
						case CRS_HOTEL_BOOKING_SOURCE :
							$booking = $this->hotel_lib->process_booking($book_id, $temp_booking['book_attributes']);
							break;
				}
				//load_hotel_lib(PROVAB_HOTEL_BOOKING_SOURCE);
				if ($booking['status'] == SUCCESS_STATUS) {
					$booking['data']['currency_obj'] = $currency_obj;
					//debug($booking['data']); exit;
					//Save booking based on booking status and book id					
                	$api_amount = 0 - $api_amount;
					$this->api_balance_manager->update_api_balance($temp_booking['booking_source'], $api_amount);

					$data = $this->hotel_lib->save_booking($book_id, $booking['data']);
					if($is_paid_by_pg)
					{
						$agent_earning = $data["agent_markup"];
						$remarks = "Your ernings on Hotel booking credited to wallet";
						$crdit_towards = "Hotel booking";
						$this->notification->credit_balance($this->entity_user_id, $book_id, $crdit_towards, $agent_earning, 0, $remarks);
					}
				// debug($data);
					// exit;
					$this->domain_management_model->update_transaction_details('hotel', $book_id, $data['fare'], $data['admin_markup'], $data['agent_markup'], $data['convinence'], $data['discount'],$data['transaction_currency'], $data['currency_conversion_rate'],
						$is_paid_by_pg);
					//deduct balance and continue

					//save to accounting software
                    /*$this->load->library('xlpro');
                    $this->xlpro->get_hotel_booking_details($booking,$temp_booking);*/

					redirect(base_url().'index.php/voucher/hotel/'.$book_id.'/'.$temp_booking['booking_source'].'/'.$data['booking_status'].'/show_voucher');
				}
				if ($is_paid_by_pg && in_array($booking['status'], array(BOOKING_ERROR, FAILURE_STATUS))){
					$pg_name = $temp_booking['book_attributes']['selected_pm'];
					redirect ( base_url () . 'index.php/payment_gateway/refund/'.$book_id.'/'.$pg_name);
					//exit;
				}
				else {
					redirect(base_url().'index.php/hotel/exception?op=booking_exception&notification='.$booking['msg']);
				}
			}
			//release table lock
		} else {
			redirect(base_url().'index.php/hotel/exception?op=Remote IO error @ Insufficient&notification=validation');
		}
	}

		function secure_bookingold($book_id, $temp_book_origin)
		{
			error_reporting(0);
			//die('secure_booking please do remove contact pankaj');
			//run booking request and do booking
			$temp_booking = $this->module_model->unserialize_temp_booking_record($book_id, $temp_book_origin);
				$app_reference_arr = explode('-', $book_id);

			//echo $book_id;exit;
			//debug($temp_booking);exit;
			load_hotel_lib($temp_booking['booking_source']);
			//verify payment status and continue
			$total_booking_price = $temp_booking['book_attributes']['base_en_rate'] + @$temp_booking['book_attributes']['token']['convenience_fees'];
			$currency = $temp_booking['book_attributes']['currency'];
			//also verify provab balance
			//check current balance before proceeding further
			$domain_balance_status = $this->domain_management_model->verify_current_balance($total_booking_price, $currency);
			if ($domain_balance_status) {
				//lock table
				if ($temp_booking != false) {
					switch ($temp_booking['booking_source']) {
						case CLEAR_TRIP_HOTEL_API :
							$booking = $this->hotel_lib->process_booking($book_id, $temp_booking['book_attributes']);
							break;
					}
					//debug($booking); exit;
					if ($booking['status'] == SUCCESS_STATUS) {
						$currency_obj = new Currency(array('module_type' => 'hotel', 'from' => get_application_default_currency(), 'to' => get_application_default_currency()));
						$booking['data']['currency_obj'] = $currency_obj;
						//Save booking based on booking status and book id
						//debug($booking['data']['book_response']['data']['booking_details']['hotel']['rooms']);die;
						// debug($booking['data']['booking_params']['token']['token']['data']['address']);die;
						$data = $this->hotel_lib->save_booking($book_id, $booking['data']);
						$this->domain_management_model->update_transaction_details('hotel', $book_id, $data['fare'], $data['admin_markup'], $data['agent_markup'], $data['convinence'], $data['discount'] );
						//deduct balance and continue

						// Send Email Voucher
						booking_email('hotel',$book_id,$temp_booking['booking_source'],'BOOKING_CONFIRMED');
						
						redirect(base_url().'index.php/voucher/hotel/'.$book_id.'/'.$temp_booking['booking_source'].'/BOOKING_CONFIRMED/show_voucher/mail');
					} else {
						$booking['msg'] = 'failed';
						redirect(base_url().'index.php/hotel/exception?op=booking_exception&notification='.$booking['msg']);
					}
				}
				//release table lock
			} else {
			redirect(base_url().'index.php/hotel/exception?op=Remote IO error @ Insufficient&notification=validation');
			}
			//redirect(base_url().'index.php/hotel/exception?op=Remote IO error @ Hotel Secure Booking&notification=validation');
		}

		function test(){
			/*$pass_room_com[1] = 1;
			$pass_room_com[2] = 1;
			$pass_room_com[3] = 1;
			$pass_room_com[4] = 2;

			$pax_id = 4;
			if(array_key_exists($pax_id,$pass_room_com)) {
				echo 'himani';
			}
			exit;*/

			$currency_obj = new Currency(array('module_type' => 'hotel', 'from' => get_application_default_currency(), 'to' => get_application_default_currency()));
			//debug($currency_obj);
			$markup_min_fare = $currency_obj->get_currency (1, true, true, true, 1 );
			debug($markup_min_fare); exit;
		}

		/**
		 *  Arjun J Gowda
		 *Process booking on hold - pay at bank
		 */
		function booking_on_hold($book_id)
		{

		}
		/**
		 * Jaganath
		 */
		function pre_cancellation($app_reference, $booking_source, $status = '')
	{
		// echo "ok";exit();
		if (empty($app_reference) == false && empty($booking_source) == false) {
			$page_data = array();
			if($booking_source==CRS_HOTEL_BOOKING_SOURCE){
				// debug($app_reference);exit();
					if($app_reference!=''){
						$resp = $this->hotels_model->hotel_crs_cancel_request($app_reference);
						 // debug($resp);exit;
						// $resp2 = json_decode($resp,true);
						// debug($resp2);exit;
						if($resp['status']==1){
							/*sending mail to customer*/
							$this->load->model ( 'hotels_model' );
							$booking_details = $this->hotels_model->getBookingDetails($app_reference);
							$email = $booking_details[0]->email_id;
							$voucher_details['other'] = $this->hotels_model->get_voucher_details($app_reference);
							// $this->load->library('provab_pdf');
							// $this->load->library('provab_mailer');
							// $create_pdf = new Provab_Pdf();
							// $mail_template = $this->template->isolated_view('voucher/hotels_voucher_cancel', $voucher_details);
							//$pdf = $create_pdf->create_pdf($mail_template,'');
							// $this->provab_mailer->send_mail($email, domain_name().' - Hotel Voucher',$mail_template);
							/*$this->load->library('provab_mailgun');
							$mail_status = $this->provab_mailgun->send_mail($email, 'Reservation Services -- Hotel Voucher',$mail_template );*/
							// echo '<script>alert("Hotel Booking Cancelled");window.close();</script>';
							set_cancel_message();
							redirect('report/hotel');
						}
					}else{
						 $response = ['status'=>0,"msg"=>"Error while cancelling room!!"];
						 echo json_encode($response); 
					}
					
			}else{
				redirect('security/log_event?event=Invalid Details');
			}
			
		} else {
			redirect('security/log_event?event=Invalid Details');
		}
	}
		/*
		 * Jaganath
		 * Process the Booking Cancellation
		 * Full Booking Cancellation
		 *
		 */
		function cancel_booking($app_reference, $booking_source)
		{
			//error_reporting(E_ALL);
			// echo $app_reference;
			// exit;
			if(empty($app_reference) == false) {
				$master_booking_details = $this->hotel_model->get_booking_details($app_reference, $booking_source);
				//debug($master_booking_details); exit;
				if ($master_booking_details['status'] == SUCCESS_STATUS) {
					$this->load->library('booking_data_formatter');
					$master_booking_details = $this->booking_data_formatter->format_hotel_booking_data($master_booking_details, 'b2c');
					$master_booking_details = $master_booking_details['data']['booking_details'][0];

					$currency_obj = new Currency ( array (
								'module_type' => 'hotel',
								'from' => CLEAR_TRIP_API_CURRENCY,
								'to' => get_application_default_currency() 
						) );

					//$total_amount = $master_booking_details['total_amount'] + @$master_booking_details['admin_markup'] + @$master_booking_details['agent_markup'];
					
					load_hotel_lib($booking_source);
					
					$cancellation_details = $this->hotel_lib->cancel_booking ( $master_booking_details,'b2c',$currency_obj,CLEAR_TRIP_API_CURRENCY,get_application_default_currency()); // Invoke Cancellation Methods
					//debug($cancellation_details); exit;
					if ($cancellation_details ['status'] == false) {
						$query_string = '?error_msg=' . $cancellation_details ['msg'];
					} else {
						$query_string = '';
					}
					redirect ( 'hotel/cancellation_details/' . $app_reference . '/' . $booking_source . $query_string );
				} else {
					redirect('security/log_event?event=Invalid Details');
				}
			} else {
				redirect('security/log_event?event=Invalid Details');
			}
		}
		/**
		 * Jaganath
		 * Cancellation Details
		 * @param $app_reference
		 * @param $booking_source
		 */
		function cancellation_details($app_reference, $booking_source)
		{
			if (empty($app_reference) == false && empty($booking_source) == false) {
				$master_booking_details = $GLOBALS['CI']->hotel_model->get_booking_details($app_reference, $booking_source);
				//debug($master_booking_details);exit;
				$cancel_status = $master_booking_details['data']['booking_details'][0]['status'];
				//echo $cancel_status;exit;
				//$email = $master_booking_details['data']['booking_details'][0]['email'];
				$email = $master_booking_details['data']['booking_details'][0]['email'];
			//	echo $email;exit;
				$airline_ref = $master_booking_details['data']['booking_details'][0]['airl_app_reference']; 
				$name = $master_booking_details['data']['booking_customer_details'][0]['first_name']." ".$master_booking_details['data']['booking_customer_details'][0]['last_name']; 
				$page_data['b2c_name'] = $name;
				$page_data['airline_ref'] = $airline_ref;
				$mail_template = $this->template->isolated_view('hotel/pre_cancellation_temp', $page_data);
				//echo $email;
				//echo "<br/>";
				//echo $mail_template;exit;
				//$this->provab_mailgun->send_mail($email, domain_name() . ' - Hotel Ticket', $mail_template, "");

				if ($cancel_status == 'BOOKING_CANCELLED') {
					$page_data = array();
					$this->load->library('booking_data_formatter');
					$master_booking_details = $this->booking_data_formatter->format_hotel_booking_data($master_booking_details, 'b2c');
					$page_data['data'] = $master_booking_details['data'];
					//debug($page_data);exit;
					$this->template->view('hotel/cancellation_details', $page_data);
				} else {
					redirect('security/log_event?event=Invalid Details');
				}
			} else {
				redirect('security/log_event?event=Invalid Details');
			}

		}
		function map()
		{
			$details = $this->input->get();
			//debug($details);exit;
			$geo_codes['data']['latitude'] = $details['lat'];
			$geo_codes['data']['longtitude'] = $details['lon'];
			$geo_codes['data']['hotel_name'] = urldecode($details['hn']);
			$geo_codes['data']['star_rating'] = $details['sr'];
			$geo_codes['data']['city'] = urldecode($details['c']);
			$geo_codes['data']['hotel_image'] = urldecode($details['img']);
			echo $this->template->isolated_view('hotel/hotels_location_map', $geo_codes);
		}

		function hotel_feedback($id) {
			$uid = unserialized_data($id);
			$input = $this->input->post();
			if(isset($input) && !empty($input)) {
				$rating = isset($input['rating']) && !empty($input['rating']) ? $input['rating'] : 0;
				$comment = $input['comment'];
				$user_id = $uid['uid'];
				$app_reference = $uid['app_reference'];
				$booking_source = $uid['booking_source'];
				//insert rating in table
				$rating = array(
				'user_id' => $user_id,
				'app_reference' => $app_reference,
				'rating' => $rating,
				'comment'=>$comment,
				'booking_source' => $booking_source,
				'created_datetime' => date('Y-m-d H:i:s')
				);
				$this->db->insert('user_rating',$rating);
				redirect(base_url());
			}
			$this->template->view('hotel/hotel_feedback');
		}


		/**
		 * Arjun J Gowda
		 */
		function exception()
		{
			$module = META_ACCOMODATION_COURSE;
			$op = (empty($_GET['op']) == true ? '' : $_GET['op']);
			$notification = (empty($_GET['notification']) == true ? '' : $_GET['notification']);
			$eid = $this->module_model->log_exception($module, $op, $notification);
			//set ip log session before redirection
			$this->session->set_flashdata(array('log_ip_info' => true));
			redirect(base_url().'index.php/hotel/event_logger/'.$eid);
		}

		function event_logger($eid='')
		{
			$log_ip_info = $this->session->flashdata('log_ip_info');
			$this->template->view('hotel/exception', array('log_ip_info' => $log_ip_info, 'eid' => $eid));
		}
		/*Bishnu*/
		public function get_city_list_by_country($country_code)
		{			 
			if($country_code=='+1')
			{
				$query="SELECT city_name FROM hotels_city WHERE country_code IN ('CA','US')";
				$city_list = $this->Custom_Db->get_result_by_query($query);
				echo json_encode($city_list);
			}else{
				$country_ISO=$this->get_iso_by_code($country_code);
				$query="SELECT city_name FROM hotels_city WHERE country_code='".$country_ISO."'";
				$city_list = $this->Custom_Db->get_result_by_query($query);
				echo json_encode($city_list);
			}
		}
		public function get_state_list_by_country($country_code)
		{
			if($country_code=='+1')
			{
				$query="SELECT abbreviation,name FROM province WHERE country IN ('CA','US')";
				$state_list = $this->Custom_Db->get_result_by_query($query);
				echo json_encode($state_list);
			}else{
				$country_ISO=$this->get_iso_by_code($country_code);
				$query="SELECT abbreviation,name FROM province WHERE country='".$country_ISO."'";
				$state_list = $this->Custom_Db->get_result_by_query($query);
				echo json_encode($state_list);
			}			 
		}
		public function get_iso_by_code($country_code)
		{

			$query="SELECT iso_country_code FROM api_country_list WHERE country_code='".$country_code."'";
			$country_ISO = $this->Custom_Db->get_result_by_query($query);
			$country_ISO = $country_ISO[0]->iso_country_code; 
			return $country_ISO;
		}
		public function get_code_by_iso($country_ISO)
		{
			$query="SELECT country_code FROM api_country_list WHERE iso_country_code='".$country_ISO."'";
			$country_code = $this->Custom_Db->get_result_by_query($query);
			$country_code = $country_code[0]->country_code; 
			return $country_code; 
		}

		public function get_post_review()
		{
			if($this->input->post())
			{
				$all_post = $this->input->post();
				
				if($all_post['created_by'] == "" || $all_post['created_by'] == NULL)
				{
					$all_post1 = $all_post;
					unset($all_post1['created_by']);
					$result = $this->Custom_Db->single_table_records('user_review','*', $all_post1);
					if($result['status']==0){
						echo 'Be the first person to write the Review.';
					}
				}else
				{
					$all_post2 = $all_post;
					unset($all_post2['created_by']);
					$result = $this->Custom_Db->single_table_records('user_review','*', $all_post2);
					$result2 = $this->Custom_Db->single_table_records('user_review','*', $all_post);
					if($result2['status']==0 && $result['status']==0){
						echo 'Be the first person to write the Review.';
					}elseif($result2['status']==1){
						echo "You have already reviewed. Please review again.";
					}
				}
			}
		}

		public function set_post_review()
		{
			//debug(error_reporting(E_ALL)); exit;
			if($this->input->post())
			{
				$all_post = $this->input->post(); //debug($all_post); exit;
				$all_post['created'] = date('Y-m-d H:i');
				$all_post['user_IP'] = $_SERVER['REMOTE_ADDR'];
				//debug($all_post); exit(); 
				if($all_post['module'] == 'holiday'){
					$condition = array('id' => $all_post['tour_id']);
					$tours = $this->Custom_Db->single_table_records('tours','*',$condition);
					$all_post['title'] = $tours['data'][0]['package_name'];
					$all_post['address'] = "N/A";
					//$all_post['title'] = $tours['data'][0]['package_name'];
					//debug(); exit;
				}
				/*$this->provab_mailer->send_mail($email, domain_name() . ' - Hotel Ticket', $mail_template, "");*/
				$mail_template = $this->template->isolated_view ( 'user/post_reveiw_registration_template', $all_post );
				//$this->provab_mailgun->send_mail ( $all_post['user_email'], ucfirst($all_post['module']).' Review', $mail_template );
				$result = $this->Custom_Db->insert_record('user_review',$all_post);
				echo json_encode($result);
			}
			
		}
		public function reviews() {
		$page_data['reviews'] = $this->hotel_model->reviews();
		//debug($page_data); exit;
		$this->template->view('hotel/reviews',$page_data);          
	}

	public function update_trip_adv(){

			$hotel_list = $this->user_model->get_hotel_details();

			foreach ($hotel_list['result'] as $key => $hd) {

				$this->user_model->update_hotelcode($hd['hotel_code']);

				$data = array (
						'status' => QUERY_SUCCESS,
						'insert_id' => $this->db->insert_id () 
				);

				$hotel_code = $hd['hotel_code'];
				$pssLatitude = $hd['latitude'];

			    $pssLongitude = $hd['longitude'];
			    $psshotelname = $hd['hotel_name'];

			   	$psskey = '0731cf5df59141869f672680686624d2'; 
			   	//$psskey = '431c997e7d6441e7a01390264a26604c';
				$url = 'http://api.tripadvisor.com/api/partner/2.0/map/'.$pssLatitude.','.$pssLongitude.'?key='.$psskey.'&q='.$psshotelname;

				$tripadvisor_data = file_get_contents($url);
				$tripadvisor_data_jsondecoded = json_decode($tripadvisor_data,1);
				debug($tripadvisor_data); exit;

				$trip_result = array();
				$trip_location_id = '';

				foreach ($tripadvisor_data_jsondecoded['data'] as $key => $tripAdvisorlist) {

					$tripAdvisorlist_name = '';
					$tripAdvisorlist_name = '_'.str_replace(' ', '_', $tripAdvisorlist['name']);
					$hd_name = explode(' ', $hd['hotel_name']);
					$hd_name_mt = $hd_name[0].'_'.$hd_name[1];
					$pos = strpos($tripAdvisorlist_name, $hd_name_mt); 
					if($pos > 0){
						$trip_result['response'] = $tripAdvisorlist; 
						$trip_result['status'] = true;
						break;	
					}else{
						$trip_result['status'] = false;
					}
				}

				if(($trip_result['status'] == 1)){
					$triAdvEnc = json_encode($tripAdvisorlist);
					$all_post['created'] = date('Y-m-d H:i');
					$all_post['tri_adv_hotel'] = $triAdvEnc;
					$all_post['hotel_code'] = $hotel_code;
					$result = $this->Custom_Db->insert_record('trip_advisor',$all_post);
				}
			}
		}

		function set_hotel_search_session_expiry($from_cache = false, $search_hash){

			$response = array();

			if($from_cache == false){

				$GLOBALS['CI']->session->set_userdata(array($search_hash => date("Y-m-d H:i:s")));
				$response['session_start_time'] = $GLOBALS ['CI']->config->item ('flight_search_session_expiry_period');
			}else{

				$start_time = $GLOBALS['CI']->session->userdata($search_hash);
				$current_time = date("Y-m-d H:i:s");
				$diff = strtotime($current_time) - strtotime($start_time);
				$response['session_start_time'] = $GLOBALS ['CI']->config->item ('flight_search_session_expiry_period') - $diff;
			}

			$response['search_hash'] = $search_hash;

			return $response;
		}

	function sendmail($app_reference, $booking_source = '', $booking_status = '', $operation = 'show_voucher', $email = '') {

		$this->load->model ( 'hotels_model' );
		//$this->load->library ( 'provab_mailgun' );
	    if (empty($app_reference) == false) {
	    	$booking_details = $this->hotels_model->getBookingDetails($app_reference);
	    	// debug($booking_details);exit;
	    	for ($b=0; $b < count($booking_details); $b++) { 
	    		if ($booking_details[$b]->booking_status == "CONFIRM") {
	    			if($email == ''){
	    				$email = $voucher_details['other'][$b]->check_in_date->email_id;
	    			}
	    			$voucher_details['other'] = $this->hotels_model->get_voucher_details($app_reference);
	    			// debug($voucher_details);exit;
	    			/*$this->load->library('provab_pdf');
					$create_pdf = new Provab_Pdf();*/
					$mail_template = $this->template->isolated_view('voucher/hotels_voucher', $voucher_details);
					$this->session->set_flashdata('email_message', 'Email sent successfully');
	                $email_subject = "Hotel Booking Confirmation-".$voucher_details['other'][$b]->parent_pnr."(".humanDateFormat($voucher_details['other'][$b]->check_in_date).")";

					//$mail_status = $this->provab_mailgun->send_mail($email, $email_subject, $mail_template, "");
					//debug($mail_status);exit;


	    		}

	    	}
	    	
	    }
	}

    public function ajax_enquiry() 
    {
    	error_reporting(E_ALL);
        $data = $this->input->post();

        // debug( $data);exit();

        $enquiry_reference_no = generate_holiday_reference_number('ZHI');

        // debug($enquiry_reference_no);exit();
        $ename    = $data['ename'];
        $elname   = $data['elname'];
        $emobile  = $data['emobile'];
        $eemail   = $data['eemail'];
        $ecomment = $data['ecomment'];
        $hotel_id  = $data['hotel_id'];
        $pn_country_code  = $data['pn_country_code'];
        $no_of_nights  = $data['no_of_nights'];
        $room_count  = $data['room_count'];
        $adult_count = $data['adult_count'];
        if(isset($data['child_count']) && !empty($data['child_count']))
            $child_count = $data['child_count'];

        $hotels_enquiry_data=array(
            'title'=>$data['title'],
            'name'=>$ename,
            'lname'=>$elname,
            'enquiry_reference_no'=>$enquiry_reference_no,
            'email'=>$eemail,
            'pn_country_code'=>$pn_country_code,
            'phone'=>$emobile,
            'message'=>$ecomment,
            'date'=>date('Y-m-d H:i:s'),
            'child_count'=>isset($child_count)?$child_count:'',
            'status'=>0,
            'hotel_id'=>$hotel_id,
            'no_of_nights'=>$no_of_nights,
            'room_count'=>$room_count,
            'adult_count'=>$adult_count);
        if(is_logged_in_user()){
            $hotels_enquiry_data['created_by_id']=$this->entity_user_id;
        }
        $this->load->model('custom_db');

        // debug($hotels_enquiry_data);exit;
        $return = $this->custom_db->insert_record('hotels_enquiry',$hotels_enquiry_data);
        if($return)
        {
            $hotels_enquiry_data['first_name'] = $hotels_enquiry_data['name'];
            $hotels_enquiry_data['last_name'] = $hotels_enquiry_data['lname'];
            $mail_template = $this->template->isolated_view ( 'holiday/inquery_template', $hotels_enquiry_data);
            $this->load->library ( 'provab_mailer' );
            $s = $this->provab_mailer->send_mail (4, $eemail, 'Good Day! Your Hotel Enquiry with Tripmia', $mail_template );
            $status = true;
            echo 'success';
        }
        else
        {
            echo $return;
        }
    }
}
