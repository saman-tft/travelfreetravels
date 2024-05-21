<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 * @package    Provab
 * @subpackage Hotel
 * @author     Balu A<balu.provab@gmail.com>
 * @version    V1
 */

class Hotel extends CI_Controller {
	private $current_module;
	public function __construct()
	{
		parent::__construct();
		//we need to activate hotel api which are active for current domain and load those libraries
		$this->load->model('hotel_model');
		$this->load->library('social_network/facebook');//Facebook Library to enable login button		
		
		$this->current_module = $this->config->item('current_module');
	}

	/**
	 * index page of application will be loaded here
	 */
	function index()
	{
		
	}

	/**
	 *  Balu A
	 * Load Hotel Search Result
	 * @param number $search_id unique number which identifies search criteria given by user at the time of searching
	 */
	function search($search_id)
	{	
		$safe_search_data = $this->hotel_model->get_safe_search_data($search_id);
		
		// Get all the hotels bookings source which are active
		$active_booking_source = $this->hotel_model->active_booking_source();
		
		if ($safe_search_data['status'] == true and valid_array($active_booking_source) == true) {
			$safe_search_data['data']['search_id'] = abs($search_id);
			$this->template->view('hotel/search_result_page', array('hotel_search_params' => $safe_search_data['data'], 'active_booking_source' => $active_booking_source));
		} else {
			$this->template->view ( 'general/popup_redirect');
		}
	}

	/**
	 *  Elavarasi
	 * Load hotel details based on booking source
	 */
	function hotel_details($search_id)
	{
		$params = $this->input->get();
		
		$safe_search_data = $this->hotel_model->get_safe_search_data($search_id);		
		
		$safe_search_data['data']['search_id'] = abs($search_id);
		
		$currency_obj = new Currency(array('module_type' => 'hotel','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
		

		if (isset($params['booking_source']) == true) {

			//We will load different page for different API providers... As we have dependency on API for hotel details page
			load_hotel_lib($params['booking_source']);
			if ($params['booking_source'] == PROVAB_HOTEL_BOOKING_SOURCE && isset($params['ResultIndex']) == true and isset($params['op']) == true and
			$params['op'] == 'get_details' and $safe_search_data['status'] == true) {
				$params['ResultIndex']	= urldecode($params['ResultIndex']);
                                
				$raw_hotel_details = $this->hotel_lib->get_hotel_details($params['ResultIndex']);             
                                
                          
				if ($raw_hotel_details['status']) {
					$HotelCode=$raw_hotel_details['data']['HotelInfoResult']['HotelDetails']['HotelCode'];
                             
                    $image_mask=$this->hotel_model->add_hotel_images($search_id,$raw_hotel_details['data']['HotelInfoResult']['HotelDetails']['Images'],$HotelCode);

					if($raw_hotel_details['data']['HotelInfoResult']['HotelDetails']['first_room_details']['Price']){
						//calculation Markup for first room 
						$raw_hotel_details['data']['HotelInfoResult']['HotelDetails']['first_room_details']['Price'] = $this->hotel_lib->update_booking_markup_currency($raw_hotel_details['data']['HotelInfoResult']['HotelDetails']['first_room_details']['Price'],$currency_obj,$search_id);	

					}
					
					$this->template->view('hotel/tbo/tbo_hotel_details_page', array('currency_obj' => $currency_obj, 'hotel_details' => $raw_hotel_details['data'], 'hotel_search_params' => $safe_search_data['data'], 'active_booking_source' => $params['booking_source'], 'params' => $params));
				} else {
					$message = $raw_hotel_details['data']['Message'];

					redirect(base_url().'index.php/hotel/exception?op='.$message.'&notification=session');
				}
			} else {
				redirect(base_url());
			}
		} else {
			redirect(base_url());
		}
	}


	/**
	 *  Balu A
	 * Passenger Details page for final bookings
	 * Here we need to run booking based on api
	 */
	public function get_post_review(){
		
	}
	function booking($search_id)
	{
		$pre_booking_params = $this->input->post();
		
		$safe_search_data = $this->hotel_model->get_safe_search_data($search_id);
		
		$safe_search_data['data']['search_id'] = abs($search_id);
		$page_data['active_payment_options'] = $this->module_model->get_active_payment_module_list();

		if (isset($pre_booking_params['booking_source']) == true) {
			//We will load different page for different API providers... As we have dependency on API for hotel details page
			$page_data['search_data'] = $safe_search_data['data'];
			load_hotel_lib($pre_booking_params['booking_source']);
			//Need to fill pax details by default if user has already logged in
			$this->load->model('user_model');
			$page_data['pax_details'] = $this->user_model->get_current_user_details();
			// debug($page_data['pax_details']);exit;
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
					
					//Display
					$currency_obj = new Currency(array('module_type' => 'hotel', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));

					$cancel_currency_obj = new Currency(array('module_type' => 'hotel','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
					
					$pre_booking_params = $this->hotel_lib->update_block_details($room_block_details['data']['response']['BlockRoomResult'], $pre_booking_params,$cancel_currency_obj);
					
					/*
					 * Update Markup
					 */
					
					$pre_booking_params['markup_price_summary'] = $this->hotel_lib->update_booking_markup_currency($pre_booking_params['price_summary'], $currency_obj, $safe_search_data['data']['search_id']);
					$Domain_record = $this->custom_db->single_table_records('domain_list', '*');
					if ($room_block_details['status'] == SUCCESS_STATUS) {
						if(!empty($this->entity_country_code)){
							
							$page_data['user_country_code'] = $this->entity_country_code;
						}
						else{
							$page_data['user_country_code'] = $Domain_record['data'][0]['phone_code'];	
						}
						
						$page_data['booking_source'] = $pre_booking_params['booking_source'];
						$page_data['pre_booking_params'] = $pre_booking_params;
						$page_data['pre_booking_params']['default_currency'] = get_application_currency_preference();
						$page_data['iso_country_list']	= $this->db_cache_api->get_iso_country_list();
						$page_data['country_list']		= $this->db_cache_api->get_country_list();
						$page_data['currency_obj']		= $currency_obj;
						
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
						
						$this->template->view('hotel/tbo/tbo_booking_page', $page_data);
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
	 *  Balu A
	 * Secure Booking of hotel
	 * 255 single adult static booking request 2310
	 * 261 double room static booking request 2308
	 */
	function pre_booking($search_id)
	{
		if($_SERVER['HTTP_HOST']=="bookings-dev.quaqua.com")
        {
            if(CURRENT_DOMAIN_KEY=="TMX1004111597231027"){
                debug("blocked");exit;
            }
            
        }
		
		$post_params = $this->input->post();
		
		//Setting Static Data - Balu A
		$post_params['billing_city'] = 'Hyderabad';
		$post_params['billing_zipcode'] = '500033';
		$post_params['billing_address_1'] = 'Plot NO 131, 3rd Floor, Dwaraka Icon, Kavuri Hills, Gutlabegumpet Village, Hyderabad, Telangana';
		

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
			//Insert To temp_booking and proceed
			$temp_booking = $this->module_model->serialize_temp_booking_record($post_params, HOTEL_BOOKING);
			$book_id = $temp_booking['book_id'];
			$book_origin = $temp_booking['temp_booking_origin'];
		
			if ($post_params['booking_source'] == PROVAB_HOTEL_BOOKING_SOURCE) {
				$amount	  = $this->hotel_lib->total_price($temp_token['markup_price_summary']);
				$agent_comission=@$temp_token['price_token'][0]['AgentCommission'];
				
				$currency = $temp_token['default_currency'];
			}
			$currency_obj = new Currency ( array (
						'module_type' => 'hotel',
						'from' => admin_base_currency (),
						'to' => admin_base_currency () 
			) );
			/********* Convinence Fees Start ********/
			 $convenience_fees = $currency_obj->convenience_fees($amount, $search_id);
			
			$gst_conv_fee=0;
            if($convenience_fees >0){
                $gst_details = $GLOBALS['CI']->custom_db->single_table_records('gst_master', '*', array('module' => 'hotel'));
               
                if($gst_details['status'] == true){
                    if($gst_details['data'][0]['gst'] > 0){
         
                         $gst_conv_fee = ($convenience_fees/100) * $gst_details['data'][0]['gst'];
                    }
                }
            }
            if($gst_conv_fee >0)
            {

            	$amount +=$gst_conv_fee;
            }
			
 $promocode_discount = $post_params['promo_code_discount_val'];
			/********* Promocode End ********/

			//details for PGI
			
			$email = $post_params ['billing_email'];
			$phone = $post_params ['passenger_contact'];

			$verification_amount = roundoff_number($amount);
			$firstname = $post_params ['first_name'] ['0'];
			$productinfo = META_ACCOMODATION_COURSE;
			//check current balance before proceeding further
			$domain_balance_status = $this->domain_management_model->verify_current_balance($verification_amount, $currency);			
			if ($domain_balance_status == true) {
				switch($post_params['payment_method']) {

					case PAY_NOW :
						$this->load->model('transaction');
						$pg_currency_conversion_rate = $currency_obj->payment_gateway_currency_conversion_rate();
						$this->transaction->create_payment_record($book_id, $verification_amount, $firstname, $email, $phone, $productinfo, $convenience_fees, $promocode_discount, $pg_currency_conversion_rate);

						redirect(base_url().'index.php/payment_gateway/payment/'.$book_id.'/'.$book_origin);																	
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


	/*
		process booking in backend until show loader 
	*/
	function process_booking($book_id, $temp_book_origin){
		if($_SERVER['HTTP_HOST']=="bookings-dev.quaqua.com")
        {
            if(CURRENT_DOMAIN_KEY=="TMX1004111597231027"){
                debug("blocked");exit;
            }
            
        }
		
		if($book_id != '' && $temp_book_origin != '' && intval($temp_book_origin) > 0){

			$page_data ['form_url'] = base_url () . 'index.php/hotel/secure_booking';
			$page_data ['form_method'] = 'POST';
			$page_data ['form_params'] ['book_id'] = $book_id;
			$page_data ['form_params'] ['temp_book_origin'] = $temp_book_origin;

			$this->template->view('share/loader/booking_process_loader', $page_data);	

		}else{
			redirect(base_url().'index.php/hotel/exception?op=Invalid request&notification=validation');
		}
		
	}

	/**
	 *  Balu A
	 *Do booking once payment is successfull - Payment Gateway
	 *and issue voucher
	 *HB11-152109-443266/1
	 *HB11-154107-854480/2
	 */
	function secure_booking()
	{
		if($_SERVER['HTTP_HOST']=="bookings-dev.quaqua.com")
        {
            if(CURRENT_DOMAIN_KEY=="TMX1004111597231027"){
                debug("blocked");exit;
            }
            
        }
		
		//echo 'Activating Live crdetails booking will not do';exit;
		$post_data = $this->input->post();
		
		if(valid_array($post_data) == true && isset($post_data['book_id']) == true && isset($post_data['temp_book_origin']) == true &&
			empty($post_data['book_id']) == false && intval($post_data['temp_book_origin']) > 0){
			//verify payment status and continue
			$book_id = trim($post_data['book_id']);
			$temp_book_origin = intval($post_data['temp_book_origin']);
			$this->load->model('transaction');
			$booking_status = $this->transaction->get_payment_status($book_id);			
		        if($booking_status['status'] !== 'accepted'){
			 	redirect(base_url().'index.php/hotel/exception?op=Payment Not Done&notification=validation');
			 }
		} else{
			redirect(base_url().'index.php/hotel/exception?op=InvalidBooking&notification=invalid');
		}	
                 
		//run booking request and do booking
		$temp_booking = $this->module_model->unserialize_temp_booking_record($book_id, $temp_book_origin);
		
		//Delete the temp_booking record, after accessing
		load_hotel_lib($temp_booking['booking_source']);
		//verify payment status and continue
		$total_booking_price = $this->hotel_lib->total_price($temp_booking['book_attributes']['token']['markup_price_summary']);
		$currency = $temp_booking['book_attributes']['token']['default_currency'];
		//also verify provab balance
		//check current balance before proceeding further
		$domain_balance_status = $this->domain_management_model->verify_current_balance($total_booking_price, $currency);
	
		
		if ($domain_balance_status) {
			//lock table
			if ($temp_booking != false) {
				switch ($temp_booking['booking_source']) {
					case PROVAB_HOTEL_BOOKING_SOURCE :
					
						//FIXME : COntinue from here - Booking request
						$booking = $this->hotel_lib->process_booking($book_id, $temp_booking['book_attributes']);
                                            
						//Save booking based on booking status and book id
						break;
				}
				
				if ($booking['status'] == SUCCESS_STATUS) {
					$currency_obj = new Currency(array('module_type' => 'hotel', 'from' => admin_base_currency(), 'to' => admin_base_currency()));
					$promo_currency_obj = new Currency(array('module_type' => 'sightseeing', 'from' => get_application_currency_preference(), 'to' => admin_base_currency()));
					$booking['data']['currency_obj'] = $currency_obj;
					$booking['data']['promo_currency_obj'] = $promo_currency_obj;
					//Save booking based on booking status and book id
					$data = $this->hotel_lib->save_booking($book_id, $booking['data']);
					
					$this->domain_management_model->update_transaction_details('hotel', $book_id, $data['fare'], $data['admin_markup'], $data['agent_markup'], $data['convinence'], $data['discount'],$data['transaction_currency'], $data['currency_conversion_rate'] );

					$this->session->set_userdata(array($book_id=>'1'));
					
					redirect(base_url().'index.php/voucher/hotel/'.$book_id.'/'.$temp_booking['booking_source'].'/'.$data['booking_status'].'/show_voucher');
				} else {
					redirect(base_url().'index.php/hotel/exception?op=booking_exception&notification='.$booking['data']['message']);
				}
			}
			//release table lock
		} else {
			redirect(base_url().'index.php/hotel/exception?op=Remote IO error @ Insufficient&notification=validation');
		}
		
	}

	/*Anitha.G
		Review passenger page for hotel
	*/
	public function review($app_reference,$temp_book_origin,$search_id){

		
        $temp_booking = $this->module_model->unserialize_temp_booking_record($app_reference, $temp_book_origin);
        $safe_search_data = $this->hotel_model->get_safe_search_data($search_id);
      	
      	/* Booking Hotel Data*/
        $page_data['hotel_data']['HotelName'] = $temp_booking['book_attributes']['token']['HotelName'];
        $page_data['hotel_data']['HotelAddress'] = $temp_booking['book_attributes']['token']['HotelAddress'];
        $page_data['hotel_data']['HotelName'] = $temp_booking['book_attributes']['token']['HotelName'];
        $page_data['hotel_data']['RoomTypeName'] = $temp_booking['book_attributes']['token']['RoomTypeName'];
        $page_data['hotel_data']['adult_config'] = array_sum($safe_search_data['data']['adult_config']);
        $page_data['hotel_data']['child_config'] = array_sum($safe_search_data['data']['child_config']);
       	
        $page_data['hotel_data']['room_count'] = $safe_search_data['data']['room_count'];
        $page_data['hotel_data']['checkin_date'] = $safe_search_data['data']['from_date'];
        $page_data['hotel_data']['checkout_date'] = $safe_search_data['data']['to_date'];

        /*Guest Details*/
		$page_data['guest_data']['first_name'] = $temp_booking['book_attributes']['first_name'];
        $page_data['guest_data']['last_name'] = $temp_booking['book_attributes']['last_name'];
        $page_data['guest_data']['last_name'] = $temp_booking['book_attributes']['last_name'];

        /*Price Details */
        $page_data['price_details']['total_amount_val'] = $total_amount_val = $temp_booking['book_attributes']['total_amount_val'];
        $page_data['price_details']['convenience_amount'] = $convenience_amount = $temp_booking['book_attributes']['convenience_amount'];
        $page_data['price_details']['discount'] = $discount = $temp_booking['book_attributes']['promo_code_discount_val'];
        $page_data['price_details']['grand_total'] = $total_amount_val+$convenience_amount-$discount;

        /*Lead Customer Details */
        $page_data['lead_pax']['email'] = $temp_booking['book_attributes']['billing_email']; 
        $page_data['lead_pax']['contact'] = $temp_booking['book_attributes']['passenger_contact'];
        $page_data['lead_pax']['phone_country_code'] = $temp_booking['book_attributes']['phone_country_code'];
        $page_data['currency_symbol'] = $temp_booking['book_attributes']['currency_symbol'];
        $page_data['book_id'] = $app_reference;
        $page_data['book_origin'] = $temp_book_origin;
        
        $this->template->view('hotel/review_page',$page_data);

	}

	/**
	 * Balu A
	 */
	function pre_cancellation($app_reference, $booking_source)
	{
		if (empty($app_reference) == false && empty($booking_source) == false) {
			$page_data = array();
			$booking_details = $this->hotel_model->get_booking_details($app_reference, $booking_source);
			if ($booking_details['status'] == SUCCESS_STATUS) {
				$this->load->library('booking_data_formatter');
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_hotel_booking_data($booking_details, 'b2c');
				$page_data['data'] = $assembled_booking_details['data'];
				$this->template->view('hotel/pre_cancellation', $page_data);
			} else {
				redirect('security/log_event?event=Invalid Details');
			}
		} else {
			redirect('security/log_event?event=Invalid Details');
		}
	}
	/*
	 * Balu A
	 * Process the Booking Cancellation
	 * Full Booking Cancellation
	 *
	 */
	function cancel_booking($app_reference, $booking_source)
	{
		if(empty($app_reference) == false) {
			$master_booking_details = $this->hotel_model->get_booking_details($app_reference, $booking_source);
			if ($master_booking_details['status'] == SUCCESS_STATUS) {
				$this->load->library('booking_data_formatter');
				$master_booking_details = $this->booking_data_formatter->format_hotel_booking_data($master_booking_details, 'b2c');
				$master_booking_details = $master_booking_details['data']['booking_details'][0];
				load_hotel_lib($booking_source);
				$cancellation_details = $this->hotel_lib->cancel_booking($master_booking_details);//Invoke Cancellation Methods
				if($cancellation_details['status'] == false) {
					$query_string = '?error_msg='.$cancellation_details['msg'];
				} else {
					$query_string = '';
				}
				redirect('hotel/cancellation_details/'.$app_reference.'/'.$booking_source.$query_string);
			} else {
				redirect('security/log_event?event=Invalid Details');
			}
		} else {
			redirect('security/log_event?event=Invalid Details');
		}
	}
	/**
	 * Balu A
	 * Cancellation Details
	 * @param $app_reference
	 * @param $booking_source
	 */
	function cancellation_details($app_reference, $booking_source)
	{
		if (empty($app_reference) == false && empty($booking_source) == false) {
			$master_booking_details = $GLOBALS['CI']->hotel_model->get_booking_details($app_reference, $booking_source);
			if ($master_booking_details['status'] == SUCCESS_STATUS) {
				$page_data = array();
				$this->load->library('booking_data_formatter');
				$master_booking_details = $this->booking_data_formatter->format_hotel_booking_data($master_booking_details, 'b2c');
				$page_data['data'] = $master_booking_details['data'];
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
		$geo_codes['data']['latitude'] = $details['lat'];
		$geo_codes['data']['longtitude'] = $details['lon'];
		$geo_codes['data']['hotel_name'] = urldecode($details['hn']);
		$geo_codes['data']['star_rating'] = $details['sr'];
		$geo_codes['data']['city'] = urldecode($details['c']);
		$geo_codes['data']['hotel_image'] = urldecode($details['img']);
		$geo_codes['data']['price'] = $details['price'];
		echo $this->template->isolated_view('hotel/location_map', $geo_codes);
	}


	/**
	 * Balu A
	 */
	function exception()
	{
		$module = META_ACCOMODATION_COURSE;
		$op = (empty($_GET['op']) == true ? '' : $_GET['op']);
		$notification = (empty($_GET['notification']) == true ? '' : $_GET['notification']);
		
		if($op == 'Some Problem Occured. Please Search Again to continue'){
			$op = 'Some Problem Occured. ';
		}
		if($notification == 'Invalid CommitBooking Request'){
			$message = 'Session is Expired';
		}
		else if($notification == 'Some Problem Occured. Please Search Again to continue' ){
			$message = 'Some Problem Occured';
		}
		else{
			$message = $notification;
		}
		$exception = $this->module_model->flight_log_exception($module, $op, $message);
		$exception = base64_encode(json_encode($exception));
		
		//set ip log session before redirection
		$this->session->set_flashdata(array('log_ip_info' => true));
		$is_session = false;
		
		if($notification=='session'){
			$is_session =true;
		}
		
		redirect(base_url().'index.php/hotel/event_logger/'.$exception.'/'.$is_session.'/'.$op);
	}

	function event_logger($exception='',$is_session='',$op='')
	{
		
		$log_ip_info = $this->session->flashdata('log_ip_info');
		if(strtolower(urldecode($op))=='not available'){
			$op='';
		}
		$this->template->view('hotel/exception', array('log_ip_info' => $log_ip_info, 'exception' => $exception,'is_session'=>$is_session ,'message'=>$op));
	}

	function get_hotel_images(){

		$post_params['hotel_code'] = 'H!0634455';
		if($post_params['hotel_code']){

			switch (PROVAB_HOTEL_BOOKING_SOURCE) {

				case PROVAB_HOTEL_BOOKING_SOURCE:
					load_hotel_lib(PROVAB_HOTEL_BOOKING_SOURCE);
					$raw_hotel_images = $this->hotel_lib->get_hotel_images($post_params['hotel_code']);	
					debug($raw_hotel_images);exit;
					break;
			}
			 
		
		}
		exit;
	}
        function image_cdn($index,$search_id,$HotelCode)
	{
            $HotelCode= base64_decode($HotelCode);
         $image_url= $this->custom_db->single_table_records('hotel_image_url','image_url',array('search_id'=>$search_id,'ResultIndex'=>$index,'hotel_code'=>$HotelCode));
         
         $image_url=$image_url['data'][0]['image_url'];
         
         header("Content-type: image/gif");
          echo  file_get_contents($image_url);
	}
        function image_details_cdn($HotelCode,$images_index)
	{
         $HotelCode= base64_decode($HotelCode);
         $image_url= $this->custom_db->single_table_records('hotel_image_url','image_url',array('hotel_code'=>$HotelCode,'ResultIndex'=>$images_index));
         $image_url=$image_url['data'][0]['image_url'];
         header("Content-type: image/gif");
         echo  file_get_contents($image_url);
	}
	//Agoda BookingList
	function get_agoda_hotel_bookings(){
		load_hotel_lib(PROVAB_HOTEL_BOOKING_SOURCE);
		$this->hotel_lib->get_agoda_bookings_list();
	}
	
	function sendmail($app_reference='',$booking_source='',$booking_status='')
    {
        
        //$app_reference='HB21-175331-702255';$booking_source='PTBSID0000000001';$booking_status='BOOKING_CONFIRMED';
                 $this->load->library('provab_mailer');
                 $this->load->model('hotel_model');
                 $this->load->library('booking_data_formatter');
            $booking_details = $this->hotel_model->get_booking_details($app_reference, $booking_source, $booking_status);
            //debug($booking_details);die;
        if (in_array($booking ['status'], array(SUCCESS_STATUS, BOOKING_CONFIRMED, BOOKING_PENDING, BOOKING_FAILED, BOOKING_ERROR, BOOKING_HOLD, FAILURE_STATUS,BOOKING_FAILED)) == true) {
                load_hotel_lib(PROVAB_HOTEL_BOOKING_SOURCE);
                $assembled_booking_details = $this->booking_data_formatter->format_hotel_booking_data($booking_details, 'b2c');
                $page_data['data'] = $assembled_booking_details['data'];
                $address = json_decode($booking_details['data']['booking_details']['0']['attributes'],true);
                $page_data['data']['address'] = $address['address'];
                $page_data['data']['logo'] = $assembled_booking_details['data']['booking_details']['0']['domain_logo'];
                $email = $booking_details['data']['booking_details']['0']['email'];
                $email = 'avinash2058.provab@gmail.com';
                $mail_template = $this->template->isolated_view('voucher/hotel_voucher', $page_data);
                //debug($mail_template);die;
                $this->load->library('provab_pdf');
                $create_pdf = new Provab_Pdf();
				$mail_template_pdf = $this->template->isolated_view('voucher/hotel_pdf', $page_data);
                $pdf = $create_pdf->create_pdf_investor($mail_template_pdf,'F');
                //debug($pdf);die;
               	$ss=$this->provab_mailer->send_mail($email, domain_name().' - Hotel Ticket',$mail_template,$pdf);
               //	$message = $this->CI->email->print_debugger();
               	debug($ss);
               	debug($message);die;
            	}
        }
}
