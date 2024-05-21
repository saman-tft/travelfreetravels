<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// error_reporting(E_ALL);
/**
 *
 * @package    Provab
 * @subpackage Hotel
 * @author     Arjun J<arjunjgowda260389@gmail.com>
 * @version    V1
 */

class Villas_apartment extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		//we need to activate hotel api which are active for current domain and load those libraries
		//$this->load->library('rewards');
		$this->load->model('hotel_model');
		$this->load->model('hotels_model');
		$this->load->model('module_model');
		$this->load->model('tours_model');
		$this->load->model('private_management_model');
		$this->load->model('domain_management_model');
		$this->load->model('transaction');
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
	    // error_reporting(E_ALL);

		// debug($search_id);exit();
		//error_reporting(0);	
		$safe_search_data = $this->hotel_model->get_safe_search_data($search_id);
	//	debug($safe_search_data);
		$nationality_name = $this->hotel_model->get_nationality_name($safe_search_data['data']['nationality_fk']);
		$nationality_name=$nationality_name[0]['selected_nationality'];
	//	debug($nationality_name);exit;
	//	debug($nationality_name[0]['selected_nationality']);exit;
	//	debug($safe_search_data['data']['nationality_fk']);exit;
		// Get all the hotels bookings source which are active
		
		$active_booking_source = $this->hotel_model->active_booking_source();
		
		// debug($active_booking_source);exit;
		// debug($safe_search_data);die;
		if ($safe_search_data['status'] == true and valid_array($active_booking_source) == true) {
			
			$safe_search_data['data']['search_id'] = abs($search_id);
			//debug($safe_search_data);exit;
		$room_type=array();
		$room_type	= $this->hotel_model->get_hotel_types_list("", $hotels);
	//	debug(	$hotels['hotel_types_list'] );exit;
		$country_nationality = $this->hotels_model->get_country_list(); 
			
			$this->template->view('hotel/search_result_page', array('hotel_search_params' => $safe_search_data['data'], 'active_booking_source' => $active_booking_source,'hotel_types_list'=>$room_type,'country_nationality'=>$country_nationality,'nationality_name'=>$nationality_name));
		} else {
			$this->template->view ( 'general/popup_redirect');
		}
	}
// HOTEL CRS SEARCH RESULT

function hotel_crs_search($search_id)
	{	$search_params = $this->input->get();
		$current_booking_source=$search_params['booking_source'];
	//	debug($current_booking_source);exit;
		$safe_search_data = $this->hotel_model->get_safe_search_data($search_id);
	
		// Get all the hotels bookings source which are active
		$active_booking_source = $this->hotel_model->active_booking_hotel_crs_source();
	//	debug($active_booking_source );die;
	$safe_search_data['status'] = true;
		if ($safe_search_data['status'] == true and valid_array($active_booking_source) == true) {
			$safe_search_data['data']['search_id'] = abs($search_id);
		
			$this->template->view('hotel/search_result_page', array('hotel_search_params' => $safe_search_data['data'], 'active_booking_source' => $active_booking_source,'current_booking_source'=>$current_booking_source));
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
	/**
	 *  Elavarasi
	 * Load hotel details based on booking source
	 */
	function hotel_details($search_id)
	{
		$params = $this->input->get();
		$safe_search_data = $this->hotel_model->get_safe_search_data($search_id);		
		// debug($params);die;
		
		// debug($safe_search_data);exit;
		
		$safe_search_data['data']['search_id'] = abs($search_id);
		//$currency_obj = new Currency(array('module_type' => 'hotel', 'from' => get_application_default_currency(), 'to' => get_application_currency_preference()));
		
		if (isset($params['booking_source']) == true) 
		{
			load_hotel_lib($params['booking_source']);
			if ($params['booking_source'] == PROVAB_HOTEL_BOOKING_SOURCE && isset($params['ResultIndex']) == true
			and isset($params['op']) == true and
			$params['op'] == 'get_details' and $safe_search_data['status'] == true) 
			{
				$currency_obj = new Currency(array('module_type' => 'hotel','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
				$params['ResultIndex']	= urldecode($params['ResultIndex']);
				$raw_hotel_details = $this->hotel_lib->get_hotel_details($params['ResultIndex']);  
			
				if ($raw_hotel_details['status']) 
				{
					$HotelCode=$raw_hotel_details['data']['HotelInfoResult']['HotelDetails']['HotelCode'];             
                    $image_mask=$this->hotel_model->add_hotel_images($search_id,$raw_hotel_details['data']['HotelInfoResult']['HotelDetails']['Images'],$HotelCode);
					if($raw_hotel_details['data']['HotelInfoResult']['HotelDetails']['first_room_details']['Price'])
					{
						//calculation Markup for first room 
						$raw_hotel_details['data']['HotelInfoResult']['HotelDetails']['first_room_details']['Price'] = $this->hotel_lib->update_booking_markup_currency($raw_hotel_details['data']['HotelInfoResult']['HotelDetails']['first_room_details']['Price'],$currency_obj,$search_id);	
					}					
					$this->template->view('hotel/tbo/tbo_hotel_details_page', array('currency_obj' => $currency_obj, 'hotel_details' => $raw_hotel_details['data'], 'hotel_search_params' => $safe_search_data['data'], 'active_booking_source' => $params['booking_source'], 'params' => $params));
				} else {
					$message = $raw_hotel_details['data']['Message'];

					redirect(base_url().'index.php/hotel/exception?op='.$message.'&notification=session');
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
						//calculation Markup for first room 
						 $specific_markup_config=array();
						 $specific_markup_config=$this->hotel_lib->get_hotel_specific_markup_config($raw_hotel_details['data']['HotelInfoResult']['HotelDetails']);
				// debug($specific_markup_config);die;
						$raw_hotel_details['data']['HotelInfoResult']['HotelDetails']['first_room_details']['Price'] = $this->hotel_lib->update_booking_markup_currency($raw_hotel_details['data']['HotelInfoResult']['HotelDetails']['first_room_details']['Price'],$currency_obj,$search_id,true,true,$specific_markup_config);
						 $image_mask=$this->hotel_model->add_hotel_images($search_id,$raw_hotel_details['data']['HotelInfoResult']['HotelDetails']['Images'],$HotelCode);
					}
					//debug($raw_hotel_details['data']);die("api");
					$this->template->view('hotel/tbo/tbo_hotel_details_page', array('currency_obj' => $currency_obj, 'hotel_details' => $raw_hotel_details['data'], 'hotel_search_params' => $safe_search_data['data'], 'active_booking_source' => $params['booking_source'], 'params' => $params));
				} else {
					redirect(base_url().'index.php/hotel/exception?op=Remote IO error @ Session Expiry&notification=session');
				}

			}
			else {
				redirect(base_url());
			}

		}
		 else {
			redirect(base_url());
		}		
	}

	function hotel_detailsold($search_id,$nationality_fk="") {
	   // debug($nationality_fk);exit;
	   ini_set('display_errors', '1');
	   ini_set('display_startup_errors', '1');
	   error_reporting(E_ALL);
		if(is_logged_in_user()) {
			$user_details = array(
				'user_name' => $this->entity_name,
				'user_email' => $this->entity_email,
				'user_mobile' => $this->entity_phone,
				'user_country_code' => $this->entity_country_code,
				'user_phone_code' => $this->entity_phone_code
				);
			//debug($user_details);exit;
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


		$params = $this->input->get (); debug($params); exit();
		$safe_search_data = $this->hotel_model->get_safe_search_data ( $search_id );
		//debug($safe_search_data);exit;



		// debug($safe_search_data);
		// exit;
		$safe_search_data ['data'] ['search_id'] = abs ( $search_id );
		
		
		//added
	//	debug($safe_search_data);exit;
			$nationality_name = $this->hotel_model->get_nationality_name($safe_search_data['data']['nationality_fk']);
		$nationality_name=$nationality_name[0]['selected_nationality'];
		$country_nationality = $this->hotels_model->get_country_list(); 
		/*$currency_obj = new Currency ( array (
				'module_type' => 'hotel',
				'from' => get_application_default_currency (),
				'to' => get_application_currency_preference () 
		) );*/
	/*	$currency_obj = new Currency ( array (
				'module_type' => 'hotel',
				'from' => get_api_data_currency (),
				'to' => get_application_currency_preference () 
		) );*/
			$currency_obj = new Currency ( array (
				'module_type' => 'hotel',
				'from' => ADMIN_BASE_CURRENCY_STATIC,
				'to' => get_application_currency_preference () 
		) );
		 // debug($params);exit;
		if (isset ( $params ['booking_source'] ) == true) {
		    
			load_hotel_lib ( $params ['booking_source'] );
			// echo  "he";exit;
			if ($params ['booking_source'] == CRS_HOTEL_BOOKING_SOURCE && isset ( $params ['hotel_id'] ) == true) {
				
				$params ['TraceId'] = urldecode ( @$params ['TraceId'] );
				$params ['ResultIndex'] = urldecode ( @$params ['ResultIndex'] );
				$params ['HotelCode'] = urldecode ( $params ['hotel_id'] );
				$hotel_data = $this->hotels_model->get_crsHotels_byHotelId($params['hotel_id']);
				// debug($hotel_data->result_array()); exit;	
				$requ =  $this->hotel_lib->search_data ( $search_id );
				// debug($requ);exit;
				 
				$checkin = explode('/', $requ['data']['from_date']);
				$checkin = $checkin[2].'-'.$checkin[1].'-'.$checkin[0];
				$checkout = explode('/', $requ['data']['to_date']);
				$checkout = $checkout[2].'-'.$checkout[1].'-'.$checkout[0];
    			$rooms_required = intval($requ['data']['room_count']);
    			// debug($hotel_data);exit;
    			 
				if($hotel_data->num_rows() > 0){
				    
   					$rooms_list = $this->hotels_model->get_crs_allRooms($params['hotel_id'],$requ); 
   					 //debug($rooms_list->result_array());exit("dfgdfg");
   				    if($rooms_list->num_rows()==0)
	                {
	                     $data['room_list'] = false;
	                } else if($rooms_list->num_rows()>=1)
                	{
                       $rooms_lists = array();
                       foreach($rooms_list->result_array() as $key => $each_rooms)
                   		{ 
                   		   
                   			// debug($each_rooms);
                   			// exit("185");
                            $hotel_rooms_count_info = $this->hotels_model->getAvailableRoomsHotel($checkin,$checkout,$each_rooms['hotel_room_type_id'],$each_rooms['seasons_details_id']);
                        // debug($hotel_rooms_count_info->num_rows());exit();
                     
                       if($hotel_rooms_count_info->num_rows()>0){
                        //My Code 

                         $hotel_rooms_count = $this->hotels_model->getAvailableRooms($checkin,$checkout,$each_rooms['hotel_room_type_id'],$each_rooms['seasons_details_id']); 
                         
                         $room_booked = $hotel_rooms_count->rooms_booked_count;

 						// debug($hotel_rooms_count);exit();
                         $roombooked = $this->hotels_model->getBookedRooms($each_rooms['hotel_room_type_id'],$each_rooms['seasons_details_id']);
                         $numbers_of_room = $roombooked->no_of_room;

                       //End Of My Code
                        /*$hotel_rooms_count_info_a = $hotel_rooms_count_info->result_array()[0];
                        $room_booked = $hotel_rooms_count_info_a['no_of_room_booked'];
                        $numbers_of_room =  intval($hotel_rooms_count_info_a['no_of_room']);*/
                        $available_rooms = $numbers_of_room - $room_booked;
                        // echo '<pre>'; print_r($available_rooms); exit();
                        
                        // debug($available_rooms);exit();

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
                             $rooms_available = ($rooms_required <= $available_rooms ) ? true : false;
                        }
                      
                       
                        if($rooms_available)
                        {
                             
                        $package_available= check_seasonPackage_availability($each_rooms,$requ);

                        if($package_available)
                        {
 
                          /*  $price_data = $this->hotels_model->get_crs_topPrice_room($each_rooms['hotel_details_id'],$each_rooms['seasons_details_id'],$each_rooms['hotel_room_type_id']);*/
                            $price_data = $this->hotels_model->get_crs_topPrice_room($each_rooms['hotel_details_id'],$each_rooms['seasons_details_id'],$each_rooms['hotel_room_type_id'],$nationality_fk);
                           //debug($price_data->result_array());exit;
                           
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
                            //echo "<pre/>sanjay";print_r($each_rooms);exit;

                            $rooms_lists[$key]['price_data'] = ($price_data->num_rows==0) ? 'false' : $price_data->result_array()[0];
                             $rooms_lists[$key]['price']      = is_array($rooms_lists[$key]['price_data']) ? calculate_crs_roomPrice($price_data->result_array()[0],$requ,$hotel_data->result_array()[0],'','',$currency_obj) : 'false';

                             /* debug($rooms_lists);
                             exit;
                            */
                            $total_price_a= $rooms_lists[$key]['price']['child_price'] + $rooms_lists[$key]['price']['adult_price'];
                           // debug($total_price_a);exit;
                            //echo "total_price";echo $total_price_a;
                            //$hotel_crs_markup = $this->private_management_model->get_markup('hotel');
                            //added on 22-3-2020
                            $hotel_crs_markup = $this->domain_management_model->get_markup('hotel');
                            // debug($hotel_crs_markup);exit;
                          //  $admin_markup_hotelcrs = $this->domain_management_model->addHotelCrsMarkup($total_price_a, $hotel_crs_markup,$currency_obj);
                            //added on 24-3-2020



//added on 24-3-2020
                           $each_crs_hotels= $hotel_data->result_array()[0];
                           $hotel_specific_markup=0;
                           $hotel_specific_markup= $each_crs_hotels['markup_value'];
                           //debug($hotel_specific_markup);exit;
                             if($hotel_specific_markup>0)
                                            {
                                                $hotel_specific_crs_markup=array();
                                                $hotel_specific_crs_markup['generic_markup_list'][0]['markup_origin']=$each_crs_hotels['markup_origin'];
                                                $hotel_specific_crs_markup['generic_markup_list'][0]['markup_type']=$each_crs_hotels['markup_type'];
                                                $hotel_specific_crs_markup['generic_markup_list'][0]['reference_id']=$each_crs_hotels['reference_id'];
                                                $hotel_specific_crs_markup['generic_markup_list'][0]['value']=$each_crs_hotels['markup_value'];
                                                $hotel_specific_crs_markup['generic_markup_list'][0]['value_type']=$each_crs_hotels['markup_value_type'];
                                                $hotel_specific_crs_markup['generic_markup_list'][0]['markup_currency']=$each_crs_hotels['markup_currency'];

                                                //echo "if";
                                                //debug($hotel_specific_crs_markup);exit;
                                                  $admin_markup_hotelcrs = $this->domain_management_model->addHotelCrsMarkup($total_price_a, $hotel_specific_crs_markup,$currency_obj);
                                            }
                                            else{
                                                //echo "else";
                                                  $admin_markup_hotelcrs = $this->domain_management_model->addHotelCrsMarkup($total_price_a, $hotel_crs_markup,$currency_obj);

                                            }




                            // debug($admin_markup_hotelcrs);exit;
                            
                            $nationality = 'IN';
                               
                            $total_price_a = $total_price_a;
                            $nation = 'India';
                             
                             $total_price_a = $total_price_a;
                             //debug($total_price_a);exit;

                            
                            $rooms_lists[$key]['total_price'] = $total_price_a;
                            $rooms_lists[$key]['admin_markup'] = $admin_markup_hotelcrs;
                            //number_format(floatval($rooms_lists[$key]['price']['child_price']) + floatval($rooms_lists[$key]['price']['adult_price']), 2, '.', '');
                            
                            $room_amenity = $this->hotels_model->get_hotel_room_details($each_rooms['hotel_details_id'],$each_rooms['hotel_room_type_id']); 
                          //  debug( $room_amenity->result_array());exit;   
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
                                        //echo '<pre>sanjay'; print_r($amenities); exit();
                                        foreach($amenities as $keys => $each_amenity)
                                        {
                                            if(is_numeric($each_amenity))
                                            {  
                                                $amenitiess = $this->hotels_model->get_hotel_amenities_byamenityId($each_amenity);
                                                 //echo "<pre/>";print_r($amenitiess);exit;
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
                  //  debug($rooms_lists);exit;

                     sort_array_of_array($rooms_lists,'total_price');
                     $data['room_list'] = $rooms_lists;

                     $data['result'] = array('success' => 'true'); 

                     // debug($rooms_lists);
                     // exit("321");

                }
           		}
           		//debug($rooms_lists); exit();
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


//hotel room details


					//$hotel_room_details = $this->hotels_model->get_hotel_room_details($params ['hotel_id'],)

					// debug($rooms_lists);exit;
					// $hotel_data['first_room_details']=$rooms_lists;
					 //debug($hotel_data);exit;	
				/*debug($hotel_data);
				debug($safe_search_data ['data']);
				exit;*/

				/*	$this->template->view ( 'hotel/hotel_crs/hotel_details_page', array (
							'currency_obj' => $currency_obj,
							'hotel_details' => $hotel_data,
							'hotel_amenities_name' => $hotel_data['hotel_amenities_name'],
							'hotel_search_params' => $safe_search_data ['data'],
							'active_booking_source' => $params ['booking_source'],
							'hotel_code_id' => @$hotel_code_id,
							'requests'      => $requ,
							'rooms_lists'   => $rooms_lists,
							'params' => $params,
							'cancellation_details' => $CancellationDetails,
                            'country_code' => $this->db_cache_api->get_country_code_list()
					) );
					
*/

//debug($rooms_lists);exit;

	$this->template->view ( 'hotel/hotel_crs/hotel_details_page', array (
	                        'nationality_fk'=>$nationality_fk,
							'currency_obj' => $currency_obj,
							'hotel_details' => $hotel_data,
							'hotel_amenities_name' => $hotel_data['hotel_amenities_name'],
							'hotel_search_params' => $safe_search_data ['data'],
							'active_booking_source' => $params ['booking_source'],
							'hotel_code_id' => @$hotel_code_id,
							'requests'      => $requ,
							'rooms_lists'   => $rooms_lists,
							'params' => $params,
							'cancellation_details' => $CancellationDetails,
                            'country_code' => $this->db_cache_api->get_country_code_list(),
                            'nationality_name'=>$nationality_name,
                            'country_nationality'=>$country_nationality
                            
					) );

					// $this->template->view ( 'hotel/hotel_crs/CRS_hotel_details_page', array (
					// 		'currency_obj' => $currency_obj,
					// 		'hotel_details' => $hotel_data,
					// 		'hotel_amenities_name' => $hotel_data['hotel_amenities_name'],
					// 		'hotel_search_params' => $safe_search_data ['data'],
					// 		'active_booking_source' => $params ['booking_source'],
					// 		'hotel_code_id' => @$hotel_code_id,
					// 		'requests'      => $requ,
					// 		'rooms_lists'   => $rooms_lists,
					// 		'params' => $params,
					// 		'cancellation_details' => $CancellationDetails,
     //                        'country_code' => $this->db_cache_api->get_country_code_list()
					// ) );
				} else {
					redirect ( base_url () . 'index.php/hotel/exception?op=Remote IO error @ Session Expiry&notification=session' );
				}
			} else {
				//exit('1');
				redirect ( base_url () );
			}
		} else {
			//exit('2');
			redirect ( base_url () );
		}
	}

	function bookings($search_id) {
	   
		// error_reporting(1);
		$pre_booking_params = $this->input->post();
		// debug($pre_booking_params );exit();
		$safe_search_data = $this->hotel_model->get_safe_search_data ( $search_id );
	//	debug($safe_search_data);exit();
	//added:
	$page_data ['from_date_display'] = $safe_search_data['data']['from_date'];
				$page_data ['to_date_display'] = $safe_search_data['data']['to_date'];
		$nationality = $this->hotels_model->get_nationality( $safe_search_data['data']['nationality_fk'] );
	//	debug($nationality[0]['country_list']);
			$page_data ['nationality_id'] = $nationality[0]['country_list'];
				$page_data ['nationality_name'] = $nationality[0]['country_name'];
		
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
			
           			// debug($price_data);exit();
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
					 
					 // debug($hotel_data);exit();
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
						$page_data ['total_price'] = ceil($pre_booking_params['price']);


						// debug($page_data);
						//for rewards points
						$page_data['reward_usable'] = 0;
						$page_data['reward_earned'] = 0;
						$page_data ['total_price_with_rewards'] = 0;

						 // debug(META_ACCOMODATION_COURSE);exit();

						
						// if(is_logged_in_user()){
						// 		    $data =  $this->rewards->page_data_reward_details(META_ACCOMODATION_COURSE,$page_data ['total_price']);
						// 		    $page_data= array_merge($page_data,$data);
						// 			debug($data);exit(); 
						// }


						//end rewards system
						$page_data ['admin_markup'] = $pre_booking_params['admin_markup'];
						$page_data ['hotel_data'] =$hotel_data;
						$page_data ['convenience_fees'] = '';


//added on 22-3-2020
						$page_data['convenience_fees']  = $currency_obj->convenience_fees($page_data['total_price'], $page_data['search_data']['search_id']);

//debug($page_data['convenience_fees']);exit;
						$page_data ['tax_service_sum'] = '';
						// Traveller Details
						$page_data ['traveller_details'] = $this->user_model->get_user_traveller_details ();
						$Domain_record = $this->custom_db->single_table_records('domain_list', '*');
						$page_data['active_data'] =$Domain_record['data'][0];
						$condition=array();
						$order_by=array('name'=>' ASC');
						$temp_record = $this->custom_db->single_table_records('api_country_list', '*',$condition,0,100000000,$order_by);
						//echo $this->db->last_query();die;
						$page_data['phone_code'] =$temp_record['data'];
						$page_data['room_data'] = $this->hotels_model->get_roomType_data($room_type_id,$hotel_id)->result_array();
						// debug($page_data['room_data']);exit;
						//$this->template->view ( 'hotel/hotel_crs/tbo_booking_page_crs', $page_data );
						// debug($page_data);exit;
						// debug($page_data);exit();
						$comb_status=$this->hotel_model->get_comb_status( $search_id);
						 /*debug($page_data);
						 exit("fdg");*/
						$page_data['comb_status']=$comb_status;
					  $this->load->model('hotel_model');
        	$page_data["residency"] = $this->hotels_model->get_country_list();
					//	debug($page_data["residency"]);exit;
					  if($this->entity_country_code)
        {
             $page_data['user_country_code']=$this->entity_country_code;
        }
						$this->template->view ( 'hotel/hotel_crs/tbo_booking_page_crs', $page_data );
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

		// debug($search_id);exit();
		$post_params = $this->input->post ();
	//	debug($post_params);exit;
		$pkg_added=$post_params['pkg_added'];
		$post_params['tours_id'] = '';
		$post_params['payment_method'] = 'Ringgitpay';
		$promocode = @$post_params['promo_code'];
		$promo_data = $this->domain_management_model->promocode_details($promocode);
		// debug($promo_data);exit();
	
		/*$post_params ['billing_city'] = 'pasay';
		$post_params ['billing_zipcode'] = '2410-2432';
		$post_params ['billing_address_1'] = '3rd floor welcome plaza mall 2410-2432 taft ave .cor libertad st pasay city metro manila 1300 philippines';
		*/
// debug($post_params);exit();

		$safe_search_data = $this->hotel_model->get_safe_search_data ( $search_id );
		// Make sure token and temp token matches
		$valid_temp_token = unserialized_data ( $post_params ['token'], $post_params ['token_key'] );
			
		

			$Markup=$valid_temp_token['admin_markup'];
  		$gst_value = 0; 
        //adding gst
        // debug($Markup); 
	    if($Markup > 0 ){
	        $gst_details = $GLOBALS['CI']->custom_db->single_table_records('gst_master', '*', array('module' => 'hotel'));
  
	        if($gst_details['status'] == true){
	            if($gst_details['data'][0]['gst'] > 0){
	                $gst_value = ($Markup/100) * $gst_details['data'][0]['gst'];
	            }
	        }

	     }

	     // debug($gst_value); 
	     $valid_temp_token['price']=$valid_temp_token['price']+$Markup+$gst_value;
	     $valid_temp_token['gst_value']=$gst_value;
	     // debug($valid_temp_token); 
		// exit("519-controller");
		$promocode_discount_type = @$promo_data['value_type'];
		if(isset($promo_data) && $promo_data['value'] != 0){
			if($promocode_discount_type == "percentage"){
				$promocode_discount_val = $valid_temp_token['price']/$promo_data['value'];
				}else{
					$promocode_discount_val = @$promo_data['value'];
				}
		}else{
		$promocode_discount_val = 0;	
		}
		

		// debug($promocode_discount_val);exit;

		 
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
		    
			//$temp_token = unserialized_data ( $temp_token );
			$temp_booking = $this->module_model->serialize_temp_booking_record ( $post_params, HOTEL_BOOKING );

			// debug($temp_booking);exit();
			$book_id = $temp_booking ['book_id'];
			$book_origin = $temp_booking ['temp_booking_origin'];
			if ($post_params ['booking_source'] == CRS_HOTEL_BOOKING_SOURCE) {
				$amount = $valid_temp_token['price'] -$promocode_discount_val;
				$currency = $valid_temp_token ['default_currency'];

				// debug($amount);
				// debug($amount_used);exit();

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
			// debug($verification_amount);exit();
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

			//16-10-18 added
			//promocode discount adding in valid_temp_token
			if($promocode_discount_val != 0 ){
			$valid_temp_token['promocode_value'] = $promocode_discount_val;	
			}

			// if($promocode_discount_val != 0 ){
			// $valid_temp_token['promocode_value'] = $promocode_discount_val;	
			// }
			// debug($valid_temp_token);
			// exit("576-controllee");
			$booking_hotel_insert = $this->hotels_model->insert_globel_data($post_params,$search_id,$book_id,$user_id,$temp_booking,$valid_temp_token,$safe_search_data);

			// debug($booking_hotel_insert);exit();
			$booking_global = array(				'module'         => 'HOTEL',
	                                                'ref_id'              => $booking_hotel_insert,
	                                                'api_details_id'	  => $post_params ['booking_source'],
	                                                'hotel_details_id'    => $valid_temp_token['HotelCode'],
	                                                'parent_pnr'          => $book_id,
	                                                'pnr_no'              => '',
	                                                'user_id'             => $user_id,
	                                                'user_type'           => $user_type,
	                                                'branch_id'			  => '',
	                                                'reward_amount'       => $post_params['reward_amount'],
	                                                'amount'              => ($valid_temp_token['price'] - $promocode_discount_val-$post_params['reward_amount']),
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
	                                                'payment_currency'    => get_application_currency_preference(),
	                                                'payment_charge'      => '',
	                                                'net_rate'            => '',
	                                                'my_markup'           => '',
	                                                'admin_markup'        => $valid_temp_token['admin_markup'],
	                                                'markup_price'        => $valid_temp_token['price']-$valid_temp_token['gst_value'],
	                                                 'gst_value'        => $valid_temp_token['gst_value'],
	                                                'service_charge'	  => '',
	                                                'discount_amount'     => $promocode_discount_val,
	                                                'promo_code'		  => $post_params['promocode_val'],
	                                                'admin_currency'      => get_application_currency_preference(),
	                                                'booking_form_data'   => base64_encode(json_encode($post_params)),
	                                                'last_name'			  => $post_params['last_name'][0],
	                                                'email_id'			  => $post_params['billing_email'],
	                                                'added_by'			  => '',
	                                                'added_type'		  => '',
	                                                'payment_type'		  => '',
	                                                'leadpax'             => $post_params['first_name'][0]);

	    // debug($booking_global);exit();	
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

        // debug($post_params['payment_method']);
        // debug(PAY_NOW_RING);
        // exit();
				switch($post_params['payment_method']) {
						case PAY_NOW_RING :
						// redirect('hotel/secure_booking/'.$book_id.'/'.$book_origin);
						// break;
						 $this->load->model('transaction');

						 $pg_currency_conversion_rate = $currency_obj->payment_gateway_currency_conversion_rate();

						 // debug($pg_currency_conversion_rate);exit();
						 $this->transaction->create_payment_record($book_id, $verification_amount, $firstname, $email, $phone, $productinfo, $convenience_fees, $promocode_discount,$pg_currency_conversion_rate);

						 
						 redirect(base_url().'index.php/hotels/process_booking/'.$book_id.'/'.$book_origin);
						/*redirect(base_url().'index.php/payment_gateway/payment/'.$book_id.'/'.$book_origin);*/
						 // debug($book_origin);exit();
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
		$temp_booking = $this->module_model->unserialize_temp_booking_record($book_id, $temp_book_origin);
		                    ///to update the rewards point
						/*	if(is_logged_in_user()){
								if($temp_booking['book_attributes']['total_price_with_rewards']){
								$this->rewards->update_after_booking($temp_booking);
								}
							}*/
					        //end
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
					        $tour_booking_details = array(
							'payment_status'=>'paid',
							'status'=>'BOOKING_CONFIRMED',
							

							);
					        $app_reference=$tour_data['app_reference'];
							if($this->custom_db->update_record('tour_booking_details',$tour_booking_details,array('app_reference'=>$app_reference))){
							$booking['status'] =SUCCESS_STATUS;
							
						    }
						    redirect(base_url().'index.php/voucher/hotel_crs_tours/'.$book_id.'/'.$source.'/'.$data['booking_status'].'/show_voucher');
						}
					//$this->sendmail($book_id,$source,$data['booking_status'],'email_voucher','');
					redirect ( base_url () . 'index.php/voucher/hotels/' . $book_id . '/' . $source . '/'.$data['booking_status'].'/'.'show_voucher');

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
	
		$pre_booking_params = $this->input->post();
		$safe_search_data = $this->hotel_model->get_safe_search_data($search_id);
	//	debug($safe_search_data);die;
		$safe_search_data['data']['search_id'] = abs($search_id);
		$page_data['active_payment_options'] = $this->module_model->get_active_payment_module_list();

		if (isset($pre_booking_params['booking_source']) == true) 
		{
			//We will load different page for different API providers... As we have dependency on API for hotel details page
			$page_data['search_data'] = $safe_search_data['data'];
			load_hotel_lib($pre_booking_params['booking_source']);
			//Need to fill pax details by default if user has already logged in
			$this->load->model('user_model');
			$page_data['pax_details'] = $this->user_model->get_current_user_details();

			header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
			header("Cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");

			if ($pre_booking_params['booking_source'] == PROVAB_HOTEL_BOOKING_SOURCE && isset($pre_booking_params['token']) == true and
			isset($pre_booking_params['op']) == true and $pre_booking_params['op'] == 'block_room' and $safe_search_data['status'] == true)
			{
				
				$pre_booking_params['token'] = unserialized_data($pre_booking_params['token'], $pre_booking_params['token_key']);
				
				if ($pre_booking_params['token'] != false) {


					$room_block_details = $this->hotel_lib->block_room($pre_booking_params);
					
					if ($room_block_details['status'] == false) {
						redirect(base_url().'index.php/hotel/exception?op='.$room_block_details['data']['msg']);
					}
					//Converting API currency data to preferred currency
					$currency_obj = new Currency(array('module_type' => 'hotel','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
					
					$room_block_details = $this->hotel_lib->roomblock_data_in_preferred_currency($room_block_details, $currency_obj,$search_id);
					
					//debug($room_block_details);exit;
					//Display
					$currency_obj = new Currency(array('module_type' => 'hotel', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));

					$cancel_currency_obj = new Currency(array('module_type' => 'hotel','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));

					$pre_booking_params = $this->hotel_lib->update_block_details($room_block_details['data']['response']['BlockRoomResult'], $pre_booking_params,$cancel_currency_obj);
					
					/*
					 * Update Markup
					 */

					$pre_booking_params['markup_price_summary'] = $this->hotel_lib->update_booking_markup_currency($pre_booking_params['price_summary'], $currency_obj, $safe_search_data['data']['search_id']);
				
					if ($room_block_details['status'] == SUCCESS_STATUS) {


						if(!empty($this->entity_country_code)){
							$page_data['user_country_code'] = $this->entity_country_code;
						}
						else{
							$page_data['user_country_code'] = '';	
						}
						$page_data['booking_source'] = $pre_booking_params['booking_source'];
						$page_data['pre_booking_params'] = $pre_booking_params;
						$page_data['pre_booking_params']['default_currency'] = get_application_currency_preference();
						$page_data['iso_country_list']	= $this->db_cache_api->get_iso_country_list();
						$page_data['country_list']		= $this->db_cache_api->get_country_list();
						$page_data['currency_obj']		= $currency_obj;
						// debug($pre_booking_params['markup_price_summary']);
						// exit;
						$page_data['total_price']		= $this->hotel_lib->total_price($pre_booking_params['markup_price_summary']);
						$page_data['convenience_fees']  = $currency_obj->convenience_fees($page_data['total_price'], $page_data['search_data']['search_id']);
						$page_data['tax_service_sum']	=  $this->hotel_lib->tax_service_sum($pre_booking_params['markup_price_summary'], $pre_booking_params['price_summary']);
						//Traveller Details
						$page_data['traveller_details'] = $this->user_model->get_user_traveller_details();
						//Get the country phone code 
						$Domain_record = $this->custom_db->single_table_records('domain_list', '*');
						$page_data['active_data'] =$Domain_record['data'][0];
						$temp_record = $this->custom_db->single_table_records('api_country_list', '*');
						$page_data['phone_code'] =$temp_record['data'];
						// debug($page_data);exit;
						$this->template->view('hotel/tbo/crs_booking_page', $page_data);
					}
				} else {

					
					redirect(base_url().'index.php/hotel/exception?op=Data Modification&notification=Data modified while transfer(Invalid Data received while validating tokens)');
				}
			}
			elseif ($pre_booking_params['booking_source'] == CRS_HOTEL_BOOKING_SOURCE && isset($pre_booking_params['token']) == true and
			isset($pre_booking_params['op']) == true and $pre_booking_params['op'] == 'block_room' and $safe_search_data['status'] == true)
			{
				
				$pre_booking_params['token'] = unserialized_data($pre_booking_params['token'], $pre_booking_params['token_key']);
					// debug($pre_booking_params); exit;

				if ($pre_booking_params['token'] != false) {


					$room_block_details = $this->hotel_lib->block_room($pre_booking_params);
					 //debug($room_block_details);die;
					if ($room_block_details['status'] == false) {
						redirect(base_url().'index.php/hotel/exception?op='.$room_block_details['data']['msg']);
					}
					//Converting API currency data to preferred currency
					//$currency_obj = new Currency(array('module_type' => 'hotel','from' => admin_base_currency(), 'to' => get_application_currency_preference()));
					 $currency_obj = new Currency(array('module_type' => 'hotel', 'from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
					$room_block_details = $this->hotel_lib->roomblock_data_in_preferred_currency($room_block_details, $currency_obj,$search_id);
					
					// debug($currency_obj);exit;
					//Display
					$currency_obj = new Currency(array('module_type' => 'hotel', 'from' => admin_base_currency(), 'to' => get_application_currency_preference()));

					$cancel_currency_obj = new Currency(array('module_type' => 'hotel','from' => admin_base_currency(), 'to' => get_application_currency_preference()));

					$pre_booking_params = $this->hotel_lib->update_block_details($room_block_details['data']['response']['BlockRoomResult'], $pre_booking_params,$cancel_currency_obj);
				
					/*
					 * Update Markup
					 */
					$specific_markup_config=array();
					$specific_markup_config[]=array('ref_id'=>$pre_booking_params['HotelCode'],'category'=>'hotel_wise');
					// debug($specific_markup_config);die;

					
					$pre_booking_params['markup_price_summary'] = $this->hotel_lib->update_booking_markup_currency($pre_booking_params['price_summary'], $currency_obj, $safe_search_data['data']['search_id'],true,true,$specific_markup_config);
					// debug($pre_booking_params);die;
				
					if ($room_block_details['status'] == SUCCESS_STATUS) {


						if(!empty($this->entity_country_code)){
							$page_data['user_country_code'] = $this->entity_country_code;
						}
						else{
							$page_data['user_country_code'] = '';	
						}
						$page_data['booking_source'] = $pre_booking_params['booking_source'];
						$page_data['pre_booking_params'] = $pre_booking_params;
					//	debug($page_data['pre_booking_params']);die;
						$page_data['pre_booking_params']['default_currency'] = get_application_currency_preference();
						$page_data['iso_country_list']	= $this->db_cache_api->get_iso_country_list();
						$page_data['country_list']		= $this->db_cache_api->get_country_list();
						$page_data['currency_obj']		= $currency_obj;
						// debug($pre_booking_params['markup_price_summary']);
						// exit;
						$page_data['total_price']		= $this->hotel_lib->total_price($pre_booking_params['markup_price_summary']);
						$page_data['convenience_fees']  = $currency_obj->convenience_fees($page_data['total_price'], $page_data['search_data']['search_id']);
						$page_data['tax_service_sum']	=  $this->hotel_lib->tax_service_sum($pre_booking_params['markup_price_summary'], $pre_booking_params['price_summary']);
						//Traveller Details
						$page_data['traveller_details'] = $this->user_model->get_user_traveller_details();
						//Get the country phone code 
						$Domain_record = $this->custom_db->single_table_records('domain_list', '*');
						$page_data['active_data'] =$Domain_record['data'][0];
						$temp_record = $this->custom_db->single_table_records('api_country_list', '*');
						$page_data['phone_code'] =$temp_record['data'];
						// debug($page_data);exit;
						$this->template->view('hotel/tbo/crs_booking_page', $page_data);
					}
				} else {

					
					redirect(base_url().'index.php/hotel/exception?op=Data Modification&notification=Data modified while transfer(Invalid Data received while validating tokens)');
				}
			} else {
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
		function pre_booking($search_id)
	{//echo 'Under Construction - Remote IO Error';exit;
	// debug("booking blocked"); exit;
		$post_params = $this->input->post();
	
		//Setting Static Data - Balu A
		$post_params['billing_city'] = 'Bangalore';
		$post_params['billing_zipcode'] = '560100';
		$post_params['billing_address_1'] = '2nd Floor, Venkatadri IT Park, HP Avenue,, Konnappana Agrahara, Electronic city';
		

		//Make sure token and temp token matches
		$valid_temp_token = unserialized_data($post_params['token'], $post_params['token_key']);
	
		if ($valid_temp_token != false) {

			load_hotel_lib($post_params['booking_source']);
			/****Convert Display currency to Application default currency***/
			//After converting to default currency, storing in temp_booking
			$post_params['token'] = unserialized_data($post_params['token']);
			$currency_obj = new Currency ( array (
						'module_type' => 'hotel',
						'from' => get_application_currency_preference (),
						'to' => admin_base_currency () 
				));
			$post_params['token'] = $this->hotel_lib->convert_token_to_application_currency($post_params['token'], $currency_obj, $this->current_module);
			$post_params['token'] = serialized_data($post_params['token']);
			$temp_token = unserialized_data($post_params['token']);
		//	 debug($temp_token); exit;
			//Insert To temp_booking and proceed
			$temp_booking = $this->module_model->serialize_temp_booking_record($post_params, HOTEL_BOOKING);
			$book_id = $temp_booking['book_id'];
			$book_origin = $temp_booking['temp_booking_origin'];
	//	debug($temp_token['markup_price_summary']);
		
			if ($post_params['booking_source'] == CRS_HOTEL_BOOKING_SOURCE || $post_params['booking_source'] == REZLIVE_HOTEL) {
				$amount	  = $this->hotel_lib->total_price($temp_token['markup_price_summary']);
				$currency = $temp_token['default_currency'];
			}
		
			$currency_obj = new Currency ( array (
						'module_type' => 'hotel',
						'from' => admin_base_currency (),
						'to' => admin_base_currency () 
			) );
			/********* Convinence Fees Start ********/
		
			$convenience_fees = $post_params['convenience_fee'];
			/********* Convinence Fees End ********/
			 	
			/********* Promocode Start ********/
			$promocode_discount = $post_params['promo_code_discount_val'];
			/********* Promocode End ********/

			//details for PGI
			
			$email = $post_params ['billing_email'];
			$phone = $post_params ['passenger_contact'];
			//debug();die;
			$verification_amount = roundoff_number($post_params['total_amount_val']+$post_params['convenience_fee']-$promocode_discount);
			//$verification_amount = roundoff_number($amount);
			$firstname = $post_params ['first_name'] ['0'];
			$productinfo = META_ACCOMODATION_COURSE;
			//check current balance before proceeding further
			$domain_balance_status = $this->domain_management_model->verify_current_balance($verification_amount, $currency);
			$gst_value = $temp_token['gst_value'];
			//debug($gst_value);exit();
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
			//	debug($amount);die;
         //   $con_row = $this->master_currency->get_instant_recharge_convenience_fees($amount, $method, $bank_code);
			$selected_pm=$this->config->item('active_payment_gateway');
			if ($domain_balance_status == true) {
				switch($post_params['payment_method']) {

					case PAY_NOW :
						$this->load->model('transaction');
						$pg_currency_conversion_rate = $currency_obj->payment_gateway_currency_conversion_rate();
						$this->transaction->create_payment_record($book_id, $verification_amount, $firstname, $email, $phone, $productinfo, $con_row['cf'], $promocode_discount, $pg_currency_conversion_rate,0,$gst_value,$selected_pm, $payment_mode);
//redirect(base_url().'index.php/hotel/process_booking/'.$book_id.'/'.$book_origin);
						// redirect(base_url().'index.php/hotel/review/'.$book_id.'/'.$book_origin.'/'.$search_id);	
						 redirect(base_url().'index.php/payment_gateway/payment/'.$book_id.'/'.$book_origin.'/'.$selected_pm);	
						 //for demo
						 if($this->config->item('enable_payment_gateway')==true)
	                     {
	     			           redirect(base_url() . 'index.php/payment_gateway/payment/' . $book_id . '/' . $book_origin. '/' . $selected_pm);
	     	              }
	     //                else
	     //                {
	                        //redirect(base_url().'index.php/hotel/process_booking/'.$book_id.'/'.$book_origin);	 
	                   // }
						
																
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
		function secure_booking($book_id, $temp_book_origin)
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

	 //debug($app_reference);exit();
		if (empty($app_reference) == false && empty($booking_source) == false) {
			$page_data = array();
			if($booking_source==CRS_HOTEL_BOOKING_SOURCE){

					if($app_reference!=''){
						$resp = $this->hotels_model->hotel_crs_cancel_request($app_reference);
						//debug($resp);exit;
						// $resp2 = json_decode($resp,true);
						// debug($resp2);exit;
						if($resp['status']==1){
							/*sending mail to customer*/
							$this->load->model ( 'hotels_model' );
							$booking_details = $this->hotels_model->getBookingDetails($app_reference);
							$email = $booking_details[0]->email_id;
							$voucher_details['other'] = $this->hotels_model->get_voucher_details($app_reference);
							// debug($voucher_details['other'] );exit;
							
							// $this->load->library('provab_pdf');
							// $this->load->library('provab_mailer');
							// $create_pdf = new Provab_Pdf();
							// $mail_template = $this->template->isolated_view('voucher/hotels_voucher_cancel', $voucher_details);
							//$pdf = $create_pdf->create_pdf($mail_template,'');
							// $this->provab_mailer->send_mail($email, domain_name().' - Hotel Voucher',$mail_template);
							/*$this->load->library('provab_mailgun');
							$mail_status = $this->provab_mailgun->send_mail($email, 'Reservation Services -- Hotel Voucher',$mail_template );*/
							// echo '<script>alert("Hotel Booking Cancelled");window.close();</script>';
							redirect('report/hotels');
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
	//for villa
			function pre_cancellation_villa($app_reference, $booking_source, $status = '')
	{

	 //debug($app_reference);exit();
		if (empty($app_reference) == false && empty($booking_source) == false) {
			$page_data = array();
			if($booking_source==CRS_HOTEL_BOOKING_SOURCE){

					if($app_reference!=''){
						$resp = $this->hotels_model->hotel_crs_cancel_request($app_reference);
						//debug($resp);exit;
						// $resp2 = json_decode($resp,true);
						// debug($resp2);exit;
						if($resp['status']==1){
							/*sending mail to customer*/
							$this->load->model ( 'hotels_model' );
							$booking_details = $this->hotels_model->getBookingDetails($app_reference);
							$email = $booking_details[0]->email_id;
							$voucher_details['other'] = $this->hotels_model->get_voucher_details($app_reference);
							// debug($voucher_details['other'] );exit;
							
							// $this->load->library('provab_pdf');
							// $this->load->library('provab_mailer');
							// $create_pdf = new Provab_Pdf();
							// $mail_template = $this->template->isolated_view('voucher/hotels_voucher_cancel', $voucher_details);
							//$pdf = $create_pdf->create_pdf($mail_template,'');
							// $this->provab_mailer->send_mail($email, domain_name().' - Hotel Voucher',$mail_template);
							/*$this->load->library('provab_mailgun');
							$mail_status = $this->provab_mailgun->send_mail($email, 'Reservation Services -- Hotel Voucher',$mail_template );*/
							// echo '<script>alert("Hotel Booking Cancelled");window.close();</script>';
							redirect('report/villa');
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
			$notification = @$_GET ['notification'];
			$book_id = @$_GET ['book_id']; 
			$op = (empty($_GET['op']) == true ? '' : $_GET['op']);
			$notification = (empty($_GET['notification']) == true ? '' : $_GET['notification']);

			if($notification == "Payment Unsuccessful, Please try again."){

			$notification = $notification.'*'.$book_id;  


			}

			$eid = $this->module_model->log_exception($module, $op, $notification);
			//set ip log session before redirection
			$this->session->set_flashdata(array('log_ip_info' => true));
			redirect(base_url().'index.php/hotels/event_logger/'.$eid);
		}

		function event_logger($eid='')
		{
			$log_ip_info = $this->session->flashdata('log_ip_info');
			$exception_data  = $this->custom_db->single_table_records('exception_logger','*',array('exception_id'=>$eid),0,1);
			// debug($exception_data);die;
		$exception=$exception_data['data'][0];
			$this->template->view('hotel/exception', array('log_ip_info' => $log_ip_info, 'eid' => $eid,'exception'=>$exception));
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

				// debug($all_post);exit();
				// $mail_template = $this->template->isolated_view ( 'user/post_reveiw_registration_template', $all_post );
				// $this->provab_mailgun->send_mail ( $all_post['user_email'], ucfirst($all_post['module']).' Review', $mail_template );
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

    public function ajax_enquiry() {
        $data = $this->input->post();

        $enquiry_reference_no = generate_holiday_reference_number('ZHI');
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

//Function : To calculate the reward amount
//Return   : The amount after deduct
//Param    : Reward range,totalamount,pending rewards,reward conversion 
public function find_reward_amount($reward_range,$amount,$pending_rewards,$rewards){

	$final_range_amount_complete = end($reward_range);
	$final_range_amount = $final_range_amount_complete['reward_to'];
	$final_reward_value = $final_range_amount_complete['reward_value'];
	foreach ($reward_range as $key => $value) {
		$reward_point_usable = ($value['reward_value']*$pending_rewards)/100;
	    $reward_point_value = $rewards[0]['currency_value']/$rewards[0]['reward_point'];

	    $page_data['remaing_rewards'] = $pending_rewards-$reward_point_usable;
				if($page_data['remaing_rewards']==0){
					$page_data['remaing_rewards']="NO_VALUE";
				}
			//debug($amount);exit();	
		    if($amount>$final_range_amount){
		    	
		    	$reward_point_usable = ($final_reward_value*$pending_rewards)/100;
		    	$reward_amount = $reward_point_value*$reward_point_usable;
			    $page_data['used_rewards']=$reward_point_usable;
			    $page_data['amount']=$amount-$reward_amount;
				return $page_data;
	        }else{
			   if($value['reward_from']<$amount && $value['reward_to']>$amount){
			  
			   $reward_amount = $reward_point_value*$reward_point_usable;
				    if($amount>$reward_amount){
				    $page_data['amount'] = $amount-$reward_amount;
				    $page_data['remaing_rewards'] = $pending_rewards-$reward_point_usable;
					$page_data['used_rewards']=$reward_point_usable;
					return $page_data;
	                break;
				    }
		    	}
	   }
	   }
	}//End

	public function amount_to_rewards($amount_used){
		$reward_details = $this->transaction->get_reward_details();
		$convert_rate = $reward_details[0]['reward_point']/$reward_details[0]['currency_value'];
		return $amount_used*$convert_rate;
	}
}
