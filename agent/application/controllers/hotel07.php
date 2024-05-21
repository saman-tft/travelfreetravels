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
		$this->index();
		$this->load->model('hotel_model');
		//$this->output->enable_profiler(TRUE);
		$this->current_module = $this->config->item('current_module');
	}

	/**
	 * index page of application will be loaded here
	 */
	function index()
	{

	}
	/**
	 * Jaganaath
	 */
	function add_days_todate()
	{
		$get_data = $this->input->get();
		if(isset($get_data['search_id']) == true && intval($get_data['search_id']) > 0 && isset($get_data['new_date']) == true && empty($get_data['new_date']) == false) {
			$search_id = intval($get_data['search_id']);
			$new_date = trim($get_data['new_date']);
			$safe_search_data = $this->hotel_model->get_safe_search_data ( $search_id );
			$day_diff = get_date_difference($safe_search_data['data']['from_date'], $new_date);
			if(valid_array($safe_search_data) == true && $safe_search_data['status'] == true) {
				$safe_search_data = $safe_search_data['data'];
				$search_params = array();
				$search_params['city'] = trim($safe_search_data['location']);
				$search_params['hotel_destination'] = '';
				$search_params['hotel_checkin'] = date('d-m-Y', strtotime($new_date));//Adding new Date
				$search_params['hotel_checkout'] = add_days_to_date($day_diff, $safe_search_data['to_date']);
				$search_params['rooms'] = intval($safe_search_data['room_count']);
				$search_params['adult'] = $safe_search_data['adult_config'];
				$search_params['child'] = $safe_search_data['child_config'];
				$search_params['childAge_1'] = $safe_search_data['child_config'];
				redirect(base_url().'index.php/general/pre_hotel_search/?'.http_build_query($search_params));
			} else {
				$this->template->view ( 'general/popup_redirect');
			}
		} else {
			$this->template->view ( 'general/popup_redirect');
		}
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
	 *  Balu A
	 * Load hotel details based on booking source
	 */
	function hotel_details($search_id)
	{
		$params = $this->input->get();
		$safe_search_data = $this->hotel_model->get_safe_search_data($search_id);
		$safe_search_data['data']['search_id'] = abs($search_id);
		
		//$currency_obj = new Currency(array('module_type' => 'hotel', 'from' => get_application_default_currency(), 'to' => get_application_currency_preference()));
		$currency_obj = new Currency(array('module_type' => 'hotel','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
		if (isset($params['booking_source']) == true) {

			//We will load different page for different API providers... As we have dependency on API for hotel details page
			load_hotel_lib($params['booking_source']);
			if ($params['booking_source'] == PROVAB_HOTEL_BOOKING_SOURCE && isset($params['ResultIndex']) == true
			and isset($params['op']) == true and
			$params['op'] == 'get_details' and $safe_search_data['status'] == true) {

				$params['ResultIndex']	= urldecode($params['ResultIndex']);
				$raw_hotel_details = $this->hotel_lib->get_hotel_details($params['ResultIndex']);

				if ($raw_hotel_details['status']) {

					if($raw_hotel_details['data']['HotelInfoResult']['HotelDetails']['first_room_details']['Price']){
						 $HotelCode=$raw_hotel_details['data']['HotelInfoResult']['HotelDetails']['HotelCode'];                            
						//calculation Markup for first room 
						$raw_hotel_details['data']['HotelInfoResult']['HotelDetails']['first_room_details']['Price'] = $this->hotel_lib->update_booking_markup_currency($raw_hotel_details['data']['HotelInfoResult']['HotelDetails']['first_room_details']['Price'],$currency_obj,$search_id,true,true);
						 $image_mask=$this->hotel_model->add_hotel_images($search_id,$raw_hotel_details['data']['HotelInfoResult']['HotelDetails']['Images'],$HotelCode);
					}
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

	/**
	 *  Balu A
	 * Passenger Details page for final bookings
	 * Here we need to run booking based on api
	 */
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


					//debug($room_block_details); exit;
					if ($room_block_details['status'] == false) {
						redirect(base_url().'index.php/hotel/exception?op='.$room_block_details['data']['msg']);
					}
					
					//Converting API currency data to preferred currency
					$currency_obj = new Currency(array('module_type' => 'hotel','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
					$room_block_details = $this->hotel_lib->roomblock_data_in_preferred_currency($room_block_details, $currency_obj,$search_id,'b2b');
					

					//Display
					$currency_obj = new Currency(array('module_type' => 'hotel', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));
					
					$cancel_currency_obj = new Currency(array('module_type' => 'hotel','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));

					$pre_booking_params = $this->hotel_lib->update_block_details($room_block_details['data']['response']['BlockRoomResult'], $pre_booking_params,$cancel_currency_obj);
					
					/*
					 * Update Markup
					 */
					$pre_booking_params['markup_price_summary'] = $this->hotel_lib->update_booking_markup_currency($pre_booking_params['price_summary'], $currency_obj, $safe_search_data['data']['search_id'], true, true);
					$phone_code_record = $this->custom_db->single_table_records('user', '*');

					if ($room_block_details['status'] == SUCCESS_STATUS) {
						if(!empty($this->entity_country_code)){
							$page_data['user_country_code'] = $this->entity_country_code;
						}
						else{
							//$page_data['user_country_code'] = '';	
							$page_data['user_country_code'] = $phone_code_record['data'][0]['country_code'];
						}
						//debug($page_data['user_country_code']);exit;
						$page_data['booking_source'] = $pre_booking_params['booking_source'];
						$page_data['pre_booking_params'] = $pre_booking_params;
						$page_data['pre_booking_params']['default_currency'] = get_application_default_currency();
						$page_data['iso_country_list']	= $this->db_cache_api->get_iso_country_list();
						$page_data['country_list']		= $this->db_cache_api->get_country_list();
						$page_data['currency_obj']		= $currency_obj;
						$page_data['total_price']		= $this->hotel_lib->total_price($pre_booking_params['markup_price_summary']);
						$page_data['convenience_fees']  = ceil($currency_obj->convenience_fees($page_data['total_price'], $page_data['search_data']['search_id']));
						$page_data['tax_service_sum']	=  $this->hotel_lib->tax_service_sum($pre_booking_params['markup_price_summary'], $pre_booking_params['price_summary']);
						//debug($page_data);exit;
						$Domain_record = $this->custom_db->single_table_records('domain_list', '*');
					$page_data['active_data'] =$Domain_record['data'][0];
					$temp_record = $this->custom_db->single_table_records('api_country_list', '*');
					//debug($temp_record);exit;
					$page_data['phone_code'] =$temp_record['data'];
						$this->template->view('hotel/tbo/tbo_booking_page', $page_data);
					}
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
	function pre_booking($search_id=2310, $static_search_result_id=255)
	{
		if(CURRENT_DOMAIN_KEY == 'TMX1004111597231027')
        {
             debug("blocked");exit;
        }
		// debug("blocked");exit;
		// redirect(base_url().'index.php/general/booking_not_allowed');		
		// exit;
		// exit;
		$post_params = $this->input->post();
		//Setting Static Data - Balu A
		$post_params['billing_city'] = 'Hyderabad';
		$post_params['billing_zipcode'] = '500033';
		$post_params['billing_address_1'] = 'Plot NO 131, 3rd Floor, Dwaraka Icon, Kavuri Hills, Gutlabegumpet Village, Hyderabad, Telangana';
		//$this->custom_db->generate_static_response(json_encode($post_params));
		//Insert To temp_booking and proceed
		/*$post_params = $this->hotel_model->get_static_response($static_search_result_id);*/

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
				//debug($amount);exit;
				$currency = $temp_token['default_currency'];
			}
			$currency_obj = new Currency ( array (
						'module_type' => 'hotel',
						'from' => admin_base_currency (),
						'to' => admin_base_currency () 
			) );
			/********* Convinence Fees Start ********/
			$convenience_fees = $currency_obj->convenience_fees($amount, $search_id);
			/********* Convinence Fees End ********/
			 	
			/********* Promocode Start ********/
			$promocode_discount = 0;
			/********* Promocode End ********/

			//details for PGI
			$email = $post_params ['billing_email'];
			$phone = $post_params ['passenger_contact'];
			$verification_amount = ceil($amount+$convenience_fees-$promocode_discount);
			$firstname = $post_params ['first_name'] ['0'];
			$productinfo = META_ACCOMODATION_COURSE;
			//check current balance before proceeding further
			$agent_paybleamount = $currency_obj->get_agent_paybleamount($verification_amount);
			$domain_balance_status = $this->domain_management_model->verify_current_balance($agent_paybleamount['amount'], $agent_paybleamount['currency']);
			
			if ($domain_balance_status == true) {
				switch($post_params['payment_method']) {
					case PAY_NOW :
						$this->load->model('transaction');
						$pg_currency_conversion_rate = $currency_obj->payment_gateway_currency_conversion_rate();
						$this->transaction->create_payment_record($book_id, $amount, $firstname, $email, $phone, $productinfo, $convenience_fees, $promocode_discount, $pg_currency_conversion_rate);
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
		if(CURRENT_DOMAIN_KEY == 'TMX1004111597231027')
        {
             debug("blocked");exit;
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
		if(CURRENT_DOMAIN_KEY == 'TMX1004111597231027')
        {
             debug("blocked");exit;
        }
		
		$post_data = $this->input->post();
		if(valid_array($post_data) == true && isset($post_data['book_id']) == true && isset($post_data['temp_book_origin']) == true &&
			empty($post_data['book_id']) == false && intval($post_data['temp_book_origin']) > 0){
			//verify payment status and continue
			$book_id = trim($post_data['book_id']);
			$temp_book_origin = intval($post_data['temp_book_origin']);
		} else{
			redirect(base_url().'index.php/hotel/exception?op=InvalidBooking&notification=invalid');
		}
		
		//run booking request and do booking
		$temp_booking = $this->module_model->unserialize_temp_booking_record($book_id, $temp_book_origin);
		
		//Delete the temp_booking record, after accessing
		$this->module_model->delete_temp_booking_record ($book_id, $temp_book_origin);
		
		load_hotel_lib($temp_booking['booking_source']);
		//verify payment status and continue
		$total_booking_price = $this->hotel_lib->total_price($temp_booking['book_attributes']['token']['markup_price_summary']);
		$currency = $temp_booking['book_attributes']['token']['default_currency'];
		$currency_obj = new Currency(array('module_type' => 'hotel', 'from' => admin_base_currency(), 'to' => admin_base_currency()));
		//also verify provab balance
		//check current balance before proceeding further
		$agent_paybleamount = $currency_obj->get_agent_paybleamount($total_booking_price);
		$domain_balance_status = $this->domain_management_model->verify_current_balance($agent_paybleamount['amount'], $agent_paybleamount['currency']);
		//debug($temp_booking);exit;
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
					$booking['data']['currency_obj'] = $currency_obj;
					//Save booking based on booking status and book id
					$data = $this->hotel_lib->save_booking($book_id, $booking['data'], $this->current_module);
					$this->domain_management_model->update_transaction_details('hotel', $book_id, $data['fare'], $data['admin_markup'], $data['agent_markup'], $data['convinence'], $data['discount'],$data['transaction_currency'], $data['currency_conversion_rate'] );
					//deduct balance and continue
					$this->session->set_userdata(array($book_id=>'1'));
					
					redirect(base_url().'index.php/voucher/hotel/'.$book_id.'/'.$temp_booking['booking_source'].'/'.$data['booking_status'].'/show_voucher');
				} else {
					redirect(base_url().'index.php/hotel/exception?op=booking_exception&notification='.$booking['msg']);
				}
			}
			//release table lock
		} else {
			redirect(base_url().'index.php/hotel/exception?op=Remote IO error @ Insufficient&notification=validation');
		}
	}

	function test(){
		$currency_obj = new Currency(array('module_type' => 'hotel', 'from' => get_application_default_currency(), 'to' => get_application_default_currency()));
		debug($currency_obj);
	}

	/**
	 *  Balu A
	 *Process booking on hold - pay at bank
	 */
	function booking_on_hold($book_id)
	{

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
				$assembled_booking_details = $this->booking_data_formatter->format_hotel_booking_data($booking_details, 'b2b');
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
				$master_booking_details = $this->booking_data_formatter->format_hotel_booking_data($master_booking_details, 'b2b');
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
				$master_booking_details = $this->booking_data_formatter->format_hotel_booking_data($master_booking_details, 'b2b');
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
		echo $this->template->isolated_view('hotel/location_map', $geo_codes);
	}
	/**
	 * Balu A
	 * Displays Cancellation Refund Details
	 * @param unknown_type $app_reference
	 * @param unknown_type $status
	 */
	public function cancellation_refund_details()
	{
		$get_data = $this->input->get();
		if(isset($get_data['app_reference']) == true && isset($get_data['booking_source']) == true && isset($get_data['status']) == true && $get_data['status'] == 'BOOKING_CANCELLED'){
			$app_reference = trim($get_data['app_reference']);
			$booking_source = trim($get_data['booking_source']);
			$status = trim($get_data['status']);
			$booking_details = $this->hotel_model->get_booking_details($app_reference, $booking_source, $status);
			if($booking_details['status'] == SUCCESS_STATUS){
				$page_data = array();
				$page_data['booking_data'] = 		$booking_details['data'];
				$this->template->view('hotel/cancellation_refund_details', $page_data);
			} else {
				redirect(base_url());
			}
		} else {
			redirect(base_url());
		}
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
		// debug($exception);exit;
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

	//update country name in api_hotel_master table
	function update_country_name(){
		ini_set('memory_limit',-1);
		ini_set('max_execution_time', 0);
		// $destinatio_code = $this->db->query('select * from api_city_master where country_code like "%D!%"')->result_array();
		// //error_reporting(E_ALL);
		// foreach ($destinatio_code as $key => $value) {
			
		// 	$update_arr = [];
		// 	$update_arr['city_name'] = $value['city_name'].' '.$value['destination_code'];
		// 	$update_arr['destination_code'] = $value['country_code'];
		// 	$country_code = $this->custom_db->single_table_records('api_destination_master','*',array('destination_code'=>$value['country_code']));
		// 	if($country_code['status']==1){
		// 		$update_arr['country_code'] = $country_code['data'][0]['country'];
		// 	}
		// 	$condition = array('origin'=>$value['origin']);
		// 	$this->db->update('api_city_master', $update_arr, $condition);
			
		// 	//$update = $this->custom_db->update_record('api_city_master',$update_arr,array('country_code'=>$value['origin']));
			
			
		// }
		// echo "success";exit;
		$select_country = $this->custom_db->single_table_records('api_country_master','*',array());
		
		ini_set('memory_limit',-1);
		ini_set('max_execution_time', 0);
		foreach ($select_country['data'] as $key => $value) {
			$select_city_country = $this->custom_db->single_table_records('api_city_master','*',array('country_code'=>$value['iso_country_code']));
			$update_record['country_name'] = $value['country_name'];
			$this->custom_db->update_record('api_city_master',$update_record,array('country_code'=>$value['iso_country_code']));
			
		}
		 echo "success";
		 exit;
	}
	/**
	*Get Hotel HOLD Booking status (GRN)
	*/
	function get_pending_booking_status($app_reference,$booking_source,$status){
		$status = 0;	
		if($status=='BOOKING_HOLD'){
			$booking_source = $booking_source;
			$app_reference = $app_reference;
			$status = $status;
			$booking_details = $this->hotel_model->get_booking_details($app_reference, $booking_source, $status);
			if($booking_details['status']==1){
				$booking_reference = $booking_details['data']['booking_details'][0]['booking_reference'];
				
				load_hotel_lib($booking_source);
				$hold_booking_status = $this->hotel_lib->get_hotel_booking_status($app_reference);
				if($hold_booking_status['status']==true){
					$status = 1;
				}
			}
		}	
		echo  $status;
	}
	    function image_cdn($index,$search_id,$HotelCode)
	{
            $HotelCode= base64_decode($HotelCode);
         $image_url= $this->custom_db->single_table_records('hotel_image_url','image_url',array('search_id'=>$search_id,'ResultIndex'=>$index,'hotel_code'=>$HotelCode));
         //debug($image_url);exit;
         $image_url=$image_url['data'][0]['image_url'];
         
         header("Content-type: image/gif");
          echo  file_get_contents($image_url);
	}
    function image_details_cdn($HotelCode,$images_index)
	{
         $HotelCode= base64_decode($HotelCode);
         $image_url= $this->custom_db->single_table_records('hotel_image_url','image_url',array('hotel_code'=>$HotelCode,'ResultIndex'=>$images_index));
           //debug($images_url);die;
         $image_url=$image_url['data'][0]['image_url'];
  
         header("Content-type: image/gif");
         echo  file_get_contents($image_url);
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
               	//debug($ss);
               	//debug($message);die;
            	}
        }

        // HOTEL CRS
       function hotel_crs_list()
	{ 
		//$hotels 						= $this->hotel_model->getHomePageSettings();
		$hotels['hotels_list'] 	   		= $this->hotel_model->get_all_hotel_crs_list();
	    $this->template->view('hotel/hotel_crs_list',$hotels);
	}
	function inactive_hotel($hotel_id1){
		$hotel_id 	= json_decode(base64_decode($hotel_id1));
		if($hotel_id != ''){
			$this->hotel_model->inactive_hotel($hotel_id);
		}
		redirect('hotel/hotel_crs_list','refresh');
	}
	function active_hotel($hotel_id1){
		$hotel_id 	= json_decode(base64_decode($hotel_id1));
		if($hotel_id != ''){
			$this->hotel_model->active_hotel($hotel_id);
		}
		redirect('hotel/hotel_crs_list','refresh');
	}
	function edit_hotel($hotel_id1)
	{ 
			// debug($hotel_id1);exit;
		// error_reporting(E_ALL);
		$hotel_id 	= json_decode(base64_decode($hotel_id1));
		if($hotel_id != '')
		{
			// $hotels 						= $this->hotels_model->getHomePageSettings();
			$hotels['hotel_id'] = $hotel_id; 
			$hotels['hotels_data'] 			= $this->hotel_model->get_hotel_data($hotel_id)->row();
			$hotels['hotel_types_list'] 	= $this->hotel_model->get_hotel_types_list();
			$hotels['country'] = $this->hotel_model->get_country_details_hotel();
			$hotels['hotel_amenities_list'] 	= $this->hotel_model->get_ammenities_list();
// 			debug($hotels['hotels_data']);die;
			$this->template->view('hotel/edit_hotel',$hotels);
		}else{
			redirect('hotel/hotel_crs_list','refresh');
		}
	}

	function add_hotel()
	{ 
		// error_reporting(E_ALL);
			//$hotels							= $this->hotels_model->getHomePageSettings();
			$hotels['hotel_types_list'] 	= $this->hotel_model->get_hotel_types_list();
			$hotels['hotel_amenities_list'] 	= $this->hotel_model->get_ammenities_list();
			$hotels['settings'] 			= $this->hotel_model->get_hotel_settings_list();
			$hotels['country'] = $this->hotel_model->get_country_details_hotel();	
			$this->template->view('hotel/add_hotel',$hotels);
	}
	function season_list($hotel_id=0,$room_id=0)
	{
		$data['hotel_id']=$hotel_id;
		$data['room_id']=$room_id;
		$data['seasons_list'] 		= $this->hotel_model->season_list($hotel_id)->result();
		
		$this->template->view('hotel/seasons/seasons_list',$data);	
	}

	function inactive_seasons($id="")
    	{
    		$this->hotel_model->inactive_seasons($id);
    		$this->session->set_flashdata('success_message','Season Inactivated');
    		redirect($_SERVER['HTTP_REFERER']);
    	}
    function active_seasons($id="")
    	{
    		$this->hotel_model->active_seasons($id);
    		$this->session->set_flashdata('success_message','Season Activated');
    		redirect($_SERVER['HTTP_REFERER']);
    	}
    function edit_seasons($id)
	{
	    $data['data'] 		= $this->hotel_model->season_data($id)->row();
	   // $data['latest_season_date'] = $this->hotels_model->season_date($data['data']->hotel_details_id);
		$this->template->view('hotel/seasons/edit_seasons',$data);	
	}
	function delete_seasons($id="")
    	{
    		$this->hotel_model->delete_seasons($id);
    		$this->session->set_flashdata('success_message','Season deleted');
    		redirect($_SERVER['HTTP_REFERER']);
    	}
    function add_season($hotel_id=0)
	{
		$data['hotel_id']=$hotel_id;
// 		$data['room_id']=$room_id;
// 		$data['latest_season_date'] = $this->hotels_model->season_date($hotel_id);
		$this->template->view('hotel/seasons/add_seasons',$data);	
	}
	function room_crs_list($hotel_id=0)
	{
	
			$hotels['rooms_list'] 	   		= $this->hotel_model->get_crs_room_list($hotel_id);
			$hotels['seasons_list'] 		= count($this->hotel_model->season_list($hotel_id)->result());
			$hotels['hotel_id'] 	   		= $hotel_id;
			// debug($hotels);die;
			
			$this->template->view('hotel/rooms/room_list',$hotels);
	}
	function inactive_room($hotel_id=0,$room_id)
	{
	
	$this->hotel_model->inactive_room($room_id);
	redirect($_SERVER['HTTP_REFERER']);

	}
	function active_room($hotel_id=0,$room_id)
	{
	
	$this->hotel_model->active_room($room_id);
	redirect($_SERVER['HTTP_REFERER']);

	}
	function edit_room($hotel_id=0,$room_id)
	{
		  //  echo $hotel_id;
		// error_reporting(E_ALL);
			$hotels['room_types_list'] 	= $this->hotel_model->get_room_types_list();
			$hotels['ammenities_list'] 		= $this->hotel_model->get_room_ammenities_list();
			$hotels['data'] 	   		= $this->hotel_model->get_crs_room($room_id)->row();
// 			debug($hotels['data']);die;
			$hotels['hotel_id'] 	   		= $hotel_id;
			$this->template->view('hotel/rooms/edit_room',$hotels);
	}
	function room_price_list($hotel_id=0,$room_id=0)
	{
		$data['hotel_id']=$hotel_id;
		$data['room_id']=$room_id;
		$data['price_list'] 		= $this->hotel_model->room_price_list($hotel_id,$room_id)->result();
// 		echo $this->db->last_query();die;
// 		debug($data['price_list']);die;
		$this->template->view('hotel/rooms/room_price_list',$data);	
	}
	function inactive_room_price($hotel_id="",$id="")
	{
		$this->hotel_model->inactive_room_price($id);
		$this->session->set_flashdata('success_message','Room Price Deactivated');
		redirect($_SERVER['HTTP_REFERER']);
	}
	function active_room_price($hotel_id="",$id="")
	{
		$this->hotel_model->active_room_price($id);
		$this->session->set_flashdata('success_message','Room Price Activated');
		redirect($_SERVER['HTTP_REFERER']);
	}
	function edit_room_price($id="")
	{
	    $data['data'] = $this->hotel_model->room_price_single($id)->row();
	   // echo $this->db->last_query();
	   // debug($data['data']);die;
	   $data['seasons'] 		= $this->hotel_model->season_list($data['data']->hotel_id)->result();
		$this->template->view('hotel/rooms/edit_price',$data);	
	}
	function add_room_price($hotel_id=0,$room_id=0)
	{
		$data['hotel_id']=$hotel_id;
		$data['room_id']=$room_id;
		$data['seasons'] 		= $this->hotel_model->season_list($hotel_id)->result();
		$this->template->view('hotel/rooms/add_price_info',$data);	
	}
	function room_cancellation_list($hotel_id=0,$room_id=0)
	{
		$data['hotel_id']=$hotel_id;
		$data['room_id']=$room_id;
		$data['price_list'] 		= $this->hotel_model->room_cancellation_list($hotel_id,$room_id)->result();
// 		debug($data['price_list']);die;
		$this->template->view('hotel/rooms/room_cancellation_list',$data);	
	}
	function inactive_room_cancel($id="")
	{
		$this->hotel_model->inactive_room_cancellation($id);
		$this->session->set_flashdata('success_message','Room Cancellation Deactivated');
		redirect($_SERVER['HTTP_REFERER']);
	}
	function active_room_cancel($id="")
	{
		$this->hotel_model->active_room_cancellation($id);
		$this->session->set_flashdata('success_message','Room Cancellation Activated');
		redirect($_SERVER['HTTP_REFERER']);
	}
	function edit_cancellation_policy($id)
	{
	    $data['data'] 		= $this->hotel_model->room_cancellation_data($id)->row();
		$this->template->view('hotel/rooms/edit_cancellation_policy',$data);	
	}
	function save_hotel_data()
	{
		try 
		{
			// debug($_POST);die;
			if(empty($_POST))
			{
				throw new Exception("Hotel data required", 1);				
			}
			// if(empty($_FILES))
			// {
			// 	throw new Exception("Hotel data required", 1);				
			// }
			$this->form_validation->set_rules('hotel_type', 'Hotel Type', 'required');
			$this->form_validation->set_rules('hotel_name', 'Hotel Name', 'required');
			$this->form_validation->set_rules('star_rating', 'Star Rating', 'required');
			$this->form_validation->set_rules('hotel_description', 'Hotel Description', 'required');
			$this->form_validation->set_rules('country', 'Country', 'required');
			$this->form_validation->set_rules('city_name', 'City', 'required');
			$this->form_validation->set_rules('ammenities', 'Hotel Amenities', 'required');
			$this->form_validation->set_rules('hotel_address', 'Hotel Address', 'required');
			$this->form_validation->set_rules('latitude', 'Lattitude', 'required');
			$this->form_validation->set_rules('longitude', 'Longtitude', 'required');
			$this->form_validation->set_rules('postal_code', 'Postal code', 'required');
			$this->form_validation->set_rules('phone_number', 'Phone number', 'required');
			// $this->form_validation->set_rules('fax_number', 'Fax number', 'required');
			$this->form_validation->set_rules('email', 'Hotel Email', 'required');
			if ($this->form_validation->run() == FALSE)
			{
				throw new 	Exception(validation_errors(), 1);
			}
			$data['hotel_type_id']=$this->input->post('hotel_type');
			$data['hotel_name']=$this->input->post('hotel_name');
			$data['star_rating']=$this->input->post('star_rating');
			$data['contract_expires_in']=$this->input->post('exclude_checkout_date');
			$data['hotel_description']=$this->input->post('hotel_description');
			$data['country']=$this->input->post('country');
			$data['city']=$this->input->post('city_name');
			$data['amenities']=implode(',',$this->input->post('ammenities'));
			$data['hotel_address']=$this->input->post('hotel_address');
			$data['lattitude']=$this->input->post('latitude');
			$data['longtitude']=$this->input->post('longitude');
			$data['postal_code']=$this->input->post('postal_code');
			$data['phone_number']=$this->input->post('phone_number');
			$data['fax_number']=$this->input->post('fax_number');
			$data['email']=$this->input->post('email');
			$data['created_by']=$this->entity_user_id;


			if(isset($_FILES['image'])==false)
	 		{
	 			throw new 	Exception("Please select image", 1);	 			
	 		}
			$config['upload_path']          = DOMAIN_HOTEL_UPLOAD_DIR;
	        $config['allowed_types']        = 'gif|jpg|png';
	        //$config['max_size']             = 1024;
	        //$config['max_width']            = 1024;
	        //$config['max_width']            = 1024;
	        $config['encrypt_name']         = TRUE;
//debug($config);die;
	        $this->load->library('upload', $config); 

	        if (!$this->upload->do_upload('image'))
	        {
	        	throw new 	Exception($this->upload->display_errors(), 1);
	        }

			$image=$this->upload->data();
	        $data['image']=$image['file_name'];
	        // debug($data['image']);die;




			$result= $this->hotel_model->save_hotel_data($data);
			if($result==false)
			{
					throw new 	Exception("Hotel details adding failed", 1);					
			}
			if($_POST['submit']=='Save')
			{
				$this->session->set_flashdata('success_message','Hotel Details Added Successfully');
					redirect('hotel/hotel_crs_list','refresh');
			}
			if($_POST['submit']=='Continue')
			{
				redirect('hotel/hotel_crs_images/'.$result,'refresh');
			}

			// debug($data);die;
		} 
		catch (Exception $e) 
		{
			echo $e->getMessage();die;
			$this->session->set_flashdata('error_message',$e->getMessage());
			redirect('hotel/add_hotel','refresh');
		}
		// debug($_POST);die;
	}
	function get_city_name($country_id = "",$selected_city = ""){
	

        if (($country_id) != "") {
            $result = $this->hotel_model->get_active_city_list_hotel($country_id);
            if ($result != "") {
                foreach ($result as $row) {
                    ?>
                    <option value="<?=$row->city_name?>" <?=$row->city_name == urldecode($selected_city) ? "selected" : "" ?>><?=$row->city_name?></option>
                    <?php

                    //$options .= '<option value="' . $row->city_name . '" >' . $row->city_name . '</option>';
                }//for
            }//if 
        }
       // echo $options;
	}
	function hotel_crs_images($hotel_id=0)
	{
		if($hotel_id=="")
		{
			redirect('hotel/hotel_crs_list','refresh');
		}
		$hotel=$this->hotel_model->get_hotel_data($hotel_id);
		if($hotel->num_rows()<1)
		{
			redirect('hotels/hotel_crs_list','refresh');			
		}
		$images['hotel_data']=$hotel->row();
		$images['images'] = $this->hotel_model->get_hotel_images($hotel_id);	
		$this->template->view('hotel/add_images',$images);
	}
	 function upload_hotel_image()
	 {
	 	try 
	 	{
	 		$hotel_id = $_POST['hotel_id'];
	 		if(isset($_FILES['hotel_image'])==false)
	 		{
	 			throw new 	Exception("Please select image", 1);	 			
	 		}
           

            $dataInfo = array();
            $files = $_FILES;
            $count = count($_FILES['hotel_image']['name']);
 			for($i=0;$i<$count;$i++)
 			{
 			$_FILES['file']['name']       = $files['hotel_image']['name'][$i];
            $_FILES['file']['type']       = $files['hotel_image']['type'][$i];
            $_FILES['file']['tmp_name']   = $files['hotel_image']['tmp_name'][$i];
            $_FILES['file']['error']      = $files['hotel_image']['error'][$i];
            $_FILES['file']['size']       = $files['hotel_image']['size'][$i];

			$config['upload_path']          = DOMAIN_HOTEL_UPLOAD_DIR;
	        $config['allowed_types']        = 'gif|jpg|png';
	        //$config['max_size']             = 1024;
	        //$config['max_width']            = 1024;
	        //$config['max_width']            = 1024;
	        $config['encrypt_name']         = TRUE;

	        $this->load->library('upload', $config);

	        if (!$this->upload->do_upload('file'))
	        {
	        	throw new 	Exception($this->upload->display_errors(), 1);
	        }
			$image=$this->upload->data();
	        $data['hotel_id']=$hotel_id;
	        $data['image']=$image['file_name'];
	        if($this->hotel_model->insert_hotel_image($data)==false)
	        {
	        	throw new 	Exception("Please select image", 1);        	
	        }
	        unset($data,$config,$_FILES);
	        }



        $this->session->set_flashdata('success_message','Image upload successfully');		

	 	} catch (Exception $e) 
	 	{
	 		echo $e->getMessage();die;
	 		$this->session->set_flashdata('error_message',$e->getMessage());	
	 	}
        
		redirect('hotel/hotel_crs_images/'.$hotel_id,'refresh');
	 }
	 function add_room_details($hotel_id=0)
	{
		  //  echo $hotel_id;
		// error_reporting(E_ALL);
			$hotels['room_types_list'] 	= $this->hotel_model->get_room_types_list();
			$hotels['ammenities_list'] 		= $this->hotel_model->get_room_ammenities_list();
			$hotels['hotel_id'] 	   		= $hotel_id;
			$this->template->view('hotel/rooms/add_room',$hotels);
	}
	function save_room_details_data()
	{
		try 
		{
			$hotel_id=$this->input->post('hotel_id');
			$data['hotel_id']=$this->input->post('hotel_id');
			$data['room_type_id']=$this->input->post('room_type_id');
			$data['max_stay']=$this->input->post('max_stay');
			$data['status']=$this->input->post('status');
			$data['max_adult_capacity']=$this->input->post('max_adult_capacity');
			$data['max_child_capacity']=$this->input->post('max_child_capacity');
			$data['extra_bed']=$this->input->post('extra_bed');
			$data['room_policy']=$this->input->post('room_policy');
			$data['room_description']=$this->input->post('room_description');
			$data['room_amenities']=implode(",",$this->input->post('room_amenities'));
			if($this->hotel_model->save_room_details_data($data)==false)
			{
				throw new 	Exception("Room details adding failed", 1);				
			}
			$this->session->set_flashdata('success_message','Room Details Added Successfully');	
		} 
		catch (Exception $e) 
		{
			$this->session->set_flashdata('error_message',$e->getMessage());	
		}
		redirect("hotel/room_crs_list/".$hotel_id,'refresh');
	}
	function update_room($id="")
	{
		try 
		{
			$hotel_id=$this->input->post('hotel_id');
			$data['hotel_id']=$this->input->post('hotel_id');
			$data['room_type_id']=$this->input->post('room_type_id');
			$data['max_stay']=$this->input->post('max_stay');
			$data['status']=$this->input->post('status');
			$data['max_adult_capacity']=$this->input->post('max_adult_capacity');
			$data['max_child_capacity']=$this->input->post('max_child_capacity');
			$data['extra_bed']=$this->input->post('extra_bed');
			$data['room_policy']=$this->input->post('room_policy');
			$data['room_description']=$this->input->post('room_description');
			$data['room_amenities']=implode(",",$this->input->post('room_amenities'));
			if($this->hotel_model->update_room_details_data($data,$id)==false)
			{
				throw new 	Exception("Room details Updating failed", 1);				
			}
			$this->session->set_flashdata('success_message','Room Details Updated Successfully');	
		} 
		catch (Exception $e) 
		{
			$this->session->set_flashdata('error_message',$e->getMessage());	
		}
		redirect("hotel/room_crs_list/".$hotel_id,'refresh');
	}
	function add_cancellation_policy($hotel_id=0,$room_id=0)
	{
		$data['hotel_id']=$hotel_id;
		$data['room_id']=$room_id;
		$this->template->view('hotel/rooms/add_cancellation_policy',$data);	
	}
	function save_cancellation_policy_data()
	{
		try 
		{
			// debug($_POST);die;
			$hotel_id=$this->input->post('hotel_id');
			$room_id=$this->input->post('room_id');
			if(empty($_POST))
			{
				throw new Exception("Cancellation policy required", 1);				
			}

			$this->form_validation->set_rules('cancel_before', 'Cancel before days', 'required');
			$this->form_validation->set_rules('penality', 'Penality', 'required');
			if ($this->form_validation->run() == FALSE)
			{
				throw new 	Exception(validation_errors(), 1);
			}
			$data['hotel_id']=$this->input->post('hotel_id');
			$data['room_id']=$this->input->post('room_id');
			$data['cancel_before']=$this->input->post('cancel_before');
			$data['cancel_to']=$this->input->post('cancel_to');
			$data['penality']=$this->input->post('penality');
			$result= $this->hotel_model->save_cancellation_policy_data($data);
			if($result==false)
			{
					throw new 	Exception("Cancellation policy details adding failed", 1);					
			}
			$this->session->set_flashdata('success_message','Cancellation policy Details Added Successfully');
			redirect('hotel/room_cancellation_list/'.$hotel_id.'/'.$room_id,'refresh');
		} 
		catch (Exception $e) 
		{
			$this->session->set_flashdata('error_message',$e->getMessage());
		}
		redirect('hotel/room_cancellation_list/'.$hotel_id.'/'.$room_id,'refresh');
	}
	function update_cancellation_policy_data($id="")
	{
		try 
		{
			// debug($_POST);die;
			$hotel_id=$this->input->post('hotel_id');
			$room_id=$this->input->post('hotel_id');
			if(empty($_POST))
			{
				throw new Exception("Cancellation policy required", 1);				
			}

			$this->form_validation->set_rules('cancel_before', 'Cancel before days', 'required');
			$this->form_validation->set_rules('penality', 'Penality', 'required');
			if ($this->form_validation->run() == FALSE)
			{
				throw new 	Exception(validation_errors(), 1);
			}
			$data['hotel_id']=$this->input->post('hotel_id');
			$data['room_id']=$this->input->post('room_id');
			$data['cancel_before']=$this->input->post('cancel_before');
			$data['cancel_to']=$this->input->post('cancel_to');
			$data['penality']=$this->input->post('penality');
			$data['status']=$this->input->post('status');
			$result= $this->hotel_model->update_cancellation_policy_data($data,$id);
			if($result==false)
			{
					throw new 	Exception("Cancellation policy details Updating failed", 1);					
			}
			$this->session->set_flashdata('success_message','Cancellation policy Details Updated Successfully');
			
		} 
		catch (Exception $e) 
		{
			$this->session->set_flashdata('error_message',$e->getMessage());
		}
		redirect('hotel/room_cancellation_list/'.$data['hotel_id'].'/'.$data['room_id'],'refresh');
	}
	function add_seasons()
	{
		try 
		{
			$hotel_id=$this->input->post('hotel_details_id');
			if(empty($_POST))
			{
				throw new Exception("Data required", 1);				
			}
			$this->form_validation->set_rules('seasons_from_date', 'From Date', 'required');
			$this->form_validation->set_rules('seasons_to_date', 'To Date', 'required');
			$this->form_validation->set_rules('hotel_details_id', 'hotel details_id', 'required|numeric');
			$this->form_validation->set_rules('seasons_name', 'seasons name', 'required');

			if ($this->form_validation->run() == FALSE)
			{
				throw new 	Exception(validation_errors(), 1);
			}
		
			$data['seasons_from_date']=date ('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('seasons_from_date'))));
			$data['seasons_to_date']=date ('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('seasons_to_date'))));
			$data['seasons_name']=$this->input->post('seasons_name');
			$data['hotel_details_id']=$this->input->post('hotel_details_id');

			// debug($data);die;
			$result= $this->hotel_model->insert_season($data);
			if($result==false)
			{
					throw new 	Exception("Data adding failed", 1);					
			}
			$this->session->set_flashdata('success_message','Data Added Successfully');
		
		} 
		catch (Exception $e) 
		{
			$this->session->set_flashdata('error_message',$e->getMessage());
		}
			redirect('hotel/season_list/'.$hotel_id,'refresh');
	}
	function update_seasons($id=null)
	{
		try 
		{
			$hotel_id=$this->input->post('hotel_details_id');
			if(empty($_POST))
			{
				throw new Exception("Data required", 1);				
			}
			$this->form_validation->set_rules('seasons_from_date', 'From Date', 'required');
			$this->form_validation->set_rules('seasons_to_date', 'To Date', 'required');
			$this->form_validation->set_rules('hotel_details_id', 'hotel details_id', 'required|numeric');
			$this->form_validation->set_rules('seasons_name', 'seasons name', 'required');

			if ($this->form_validation->run() == FALSE)
			{
				throw new 	Exception(validation_errors(), 1);
			}
		
			$data['seasons_from_date']=date ('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('seasons_from_date'))));
			$data['seasons_to_date']=date ('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('seasons_to_date'))));
			$data['seasons_name']=$this->input->post('seasons_name');

			// debug($data);die;
			$result= $this->hotel_model->update_season($data,$id);
			if($result==false)
			{
					throw new 	Exception("Data adding failed", 1);					
			}
			$this->session->set_flashdata('success_message','Data Added Successfully');
		
		}
		catch (Exception $e) 
		{
			$this->session->set_flashdata('error_message',$e->getMessage());
		}
			redirect('hotel/season_list/'.$hotel_id,'refresh');
	}
	function save_room_price_data()
	{
		try 
		{
			$hotel_id=$this->input->post('hotel_id');
			$room_id=$this->input->post('room_id');
			if(empty($_POST))
			{
				throw new Exception("Room price required", 1);				
			}
// 			debug($_POST);die;
			//$this->form_validation->set_rules('date_from', 'From Date', 'required');
			$this->form_validation->set_rules('season', 'Season', 'required');
			$this->form_validation->set_rules('one_adult', 'Single Adult Price', 'required');
			$this->form_validation->set_rules('two_adult', 'Double Adult Price', 'required');
			$this->form_validation->set_rules('three_adult', 'Triple Adult Price', 'required');
			$this->form_validation->set_rules('child_price', 'Child Price', 'required');
			$this->form_validation->set_rules('min_stay', 'min_stay', 'required');
			$this->form_validation->set_rules('extrabed', 'extrabed', 'required');

			if ($this->form_validation->run() == FALSE)
			{
				throw new 	Exception(validation_errors(), 1);
			}
			$data['hotel_id']=$this->input->post('hotel_id');
			$data['room_id']=$this->input->post('room_id');
			//$data['date_from']=date ('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('date_from'))));
			//$data['date_to']=date ('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('date_to'))));
			$data['one_adult']=$this->input->post('one_adult');
			$data['season']=$this->input->post('season');
			$data['two_adult']=$this->input->post('two_adult');
			$data['three_adult']=$this->input->post('three_adult');
			$data['child_price']=$this->input->post('child_price');
			$data['status']=$this->input->post('status');
			$data['extrabed']=$this->input->post('extrabed');
			$data['extrabed_price']=$this->input->post('extrabed_price');
			$data['one_adult_breakfast']=$this->input->post('one_adult_breakfast');
			$data['two_adult_breakfast']=$this->input->post('two_adult_breakfast');
			$data['three_adult_breakfast']=$this->input->post('three_adult_breakfast');
			$data['child_breakfast']=$this->input->post('child_breakfast');
			$data['child_breakfast_age']=$this->input->post('child_breakfast_age');
			$data['min_stay']=$this->input->post('min_stay');
			$data['vat']=$this->input->post('vat');
			$data['service_charge']=$this->input->post('service_charge');
			// debug($data);die;
			$result= $this->hotel_model->save_room_price_data($data);
			if($result==false)
			{
					throw new 	Exception("Room price details adding failed", 1);					
			}
			$this->session->set_flashdata('success_message','Room Price Details Added Successfully');
			redirect('hotel/room_price_list/'.$hotel_id.'/'.$room_id,'refresh');
		} 
		catch (Exception $e) 
		{
			$this->session->set_flashdata('error_message',$e->getMessage());
			redirect('hotel/add_room_price/'.$hotel_id.'/'.$room_id,'refresh');
		}
		// debug($_POST);die;
	}
	function update_room_price_data($id="")
	{
		try 
		{
			$hotel_id=$this->input->post('hotel_id');
			$room_id=$this->input->post('room_id');
			if(empty($_POST))
			{
				throw new Exception("Room price required", 1);				
			}
			$this->form_validation->set_rules('season', 'Season', 'required');
			$this->form_validation->set_rules('one_adult', 'Single Adult Price', 'required');
			$this->form_validation->set_rules('two_adult', 'Double Adult Price', 'required');
			$this->form_validation->set_rules('three_adult', 'Triple Adult Price', 'required');
			$this->form_validation->set_rules('child_price', 'Child Price', 'required');
			$this->form_validation->set_rules('min_stay', 'min_stay', 'required');
			$this->form_validation->set_rules('extrabed', 'extrabed', 'required');

			if ($this->form_validation->run() == FALSE)
			{
				throw new 	Exception(validation_errors(), 1);
			}
		
			$data['hotel_id']=$this->input->post('hotel_id');
			$data['room_id']=$this->input->post('room_id');
			//$data['date_from']=date ('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('date_from'))));
			//$data['date_to']=date ('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('date_to'))));
			$data['one_adult']=$this->input->post('one_adult');
			$data['season']=$this->input->post('season');
			$data['two_adult']=$this->input->post('two_adult');
			$data['three_adult']=$this->input->post('three_adult');
			$data['child_price']=$this->input->post('child_price');
			$data['status']=$this->input->post('status');
			$data['extrabed']=$this->input->post('extrabed');
			$data['extrabed_price']=$this->input->post('extrabed_price');
			$data['one_adult_breakfast']=$this->input->post('one_adult_breakfast');
			$data['two_adult_breakfast']=$this->input->post('two_adult_breakfast');
			$data['three_adult_breakfast']=$this->input->post('three_adult_breakfast');
			$data['child_breakfast']=$this->input->post('child_breakfast');
			$data['child_breakfast_age']=$this->input->post('child_breakfast_age');
			$data['min_stay']=$this->input->post('min_stay');
			$data['vat']=$this->input->post('vat');
			$data['service_charge']=$this->input->post('service_charge');
			// debug($data);die;
			$result= $this->hotel_model->update_room_price_data($data,$id);
			if($result==false)
			{
					throw new 	Exception("Room price details adding failed", 1);					
			}
			$this->session->set_flashdata('success_message','Room Price Details Added Successfully');
		
		} 
		catch (Exception $e) 
		{
			$this->session->set_flashdata('error_message',$e->getMessage());
		}
			redirect('hotel/room_price_list/'.$hotel_id.'/'.$room_id,'refresh');
	}
	function update_hotel_datas($update_id="")
	{
		try 
		{//debug($_POST);die;
			
			if(empty($_POST))
			{
				throw new Exception("Hotel data required", 1);				
			}
			// if(empty($_FILES))
			// {
			// 	throw new Exception("Hotel data required", 1);				
			// }
			$this->form_validation->set_rules('hotel_type', 'Hotel Type', 'required');
			$this->form_validation->set_rules('hotel_name', 'Hotel Name', 'required');
			$this->form_validation->set_rules('star_rating', 'Star Rating', 'required');
			$this->form_validation->set_rules('hotel_description', 'Hotel Description', 'required');
			$this->form_validation->set_rules('country', 'Country', 'required');
			$this->form_validation->set_rules('city_name', 'City', 'required');
			$this->form_validation->set_rules('ammenities', 'Hotel Amenities', 'required');
			$this->form_validation->set_rules('hotel_address', 'Hotel Address', 'required');
			$this->form_validation->set_rules('latitude', 'Lattitude', 'required');
			$this->form_validation->set_rules('longitude', 'Longtitude', 'required');
			$this->form_validation->set_rules('postal_code', 'Postal code', 'required');
			$this->form_validation->set_rules('phone_number', 'Phone number', 'required');
			//$this->form_validation->set_rules('fax_number', 'Fax number', 'required');
			$this->form_validation->set_rules('email', 'Hotel Email', 'required');
			if ($this->form_validation->run() == FALSE)
			{
				throw new 	Exception(validation_errors(), 1);
			}
			$data['hotel_type_id']=$this->input->post('hotel_type');
			$data['hotel_name']=$this->input->post('hotel_name');
			$data['star_rating']=$this->input->post('star_rating');
			$data['contract_expires_in']=$this->input->post('exclude_checkout_date');
			$data['hotel_description']=$this->input->post('hotel_description');
			$data['country']=$this->input->post('country');
			$data['city']=$this->input->post('city_name');
			$data['amenities']=implode(',',$this->input->post('ammenities'));
			$data['hotel_address']=$this->input->post('hotel_address');
			$data['lattitude']=$this->input->post('latitude');
			$data['longtitude']=$this->input->post('longitude');
			$data['postal_code']=$this->input->post('postal_code');
			$data['phone_number']=$this->input->post('phone_number');
			$data['fax_number']=$this->input->post('fax_number');
			$data['email']=$this->input->post('email');
		
			if($_FILES['image']['size']>0)
	 		{
				//echo "test3";die;
			$config['upload_path']          = DOMAIN_HOTEL_UPLOAD_DIR;
	        $config['allowed_types']        = 'gif|jpg|png';
	        // $config['max_size']             = 1024;
	        // $config['max_width']            = 1024;
	        // $config['max_width']            = 1024;
	        $config['encrypt_name']         = TRUE;

	        $this->load->library('upload', $config);

	        if (!$this->upload->do_upload('image'))
	        {
	        	throw new 	Exception($this->upload->display_errors(), 1);
	        }

			$image=$this->upload->data();
	        $data['image']=$image['file_name'];
	       }
		//echo "asdf";die;
			if(true)
			{
			//	echo "asdf";die;
				//debug($data);debug($update_id);exit;
			    $result= $this->hotel_model->update_hotel_data($data,$update_id);
			    //debug($result);exit;
			    if($result==false)
    			{
    			 throw new 	Exception("Hotel details updating failed", 1);					
    			}
				$this->session->set_flashdata('success_message','Hotel Details Updated Successfully');
					redirect('hotel/hotel_crs_list','refresh');
			}
	

			// debug($data);die;
		} 
		catch (Exception $e) 
		{
			 //echo $e->getMessage();die;
			$this->session->set_flashdata('error_message',$e->getMessage());
			$update_id = json_encode(base64_encode($update_id));
			redirect("hotel/edit_hotel/{}",'refresh');
		}
		// debug($_POST);die;
	}
	function hotel_types()
	{
		// if (!check_user_previlege('106')) 
		// {
  //           set_update_message("You Don't have permission to do this action.", WARNING_MESSAGE, array(
  //               'override_app_msg' => true
  //           ));
  //           redirect(base_url());
  //       }
		//$hotels	= $this->hotels_model->getHomePageSettings();
		// debug($hotels);die;
		$hotels['hotel_types_list'] 	= $this->hotel_model->get_hotel_types_list();
		$this->template->view('hotel/hotel_types/hotel_types_list',$hotels);
	}
	function add_hotel_type()
	{
	   $data=array();
		$this->template->view('hotel/hotel_types/add_hotel_type',$data);	
	}
	function save_hotel_type_data()
	{
		try 
		{
			if(isset($_POST['hotel_type_name'])==FALSE || empty($_POST['hotel_type_name'])==TRUE)
			{
				throw new Exception("Error Processing Request", 1);				
			}
			$data['name']=$_POST['hotel_type_name'];
			// $data['created_by']=$_POST['user_id'];
			$data['created_by']=$this->entity_user_id;
			$data['status']=$_POST['status'];

			if($this->hotel_model->add_hotel_type_details($data)==false)
			{
				throw new Exception('Hotel Type Adding Failed', 1);				
			}
			$this->session->set_flashdata('success_message', 'Inserted Successfully!!');
			redirect('hotel/hotel_types','refresh');				
		} 
		catch (Exception $e) 
		{
			$this->session->set_flashdata('error_message', $e->getMessage());
			redirect('hotel/add_hotel_type','refresh');				
		}
	}
	function inactive_hotel_type($hotel_type_id1)
	{
		$hotel_type_id 	= json_decode(base64_decode($hotel_type_id1));
		if($hotel_type_id != '')
		{
			$this->hotel_model->inactive_hotel_type($hotel_type_id);
		}
		$this->session->set_flashdata('success_message', 'Deactivated Successfully!!');
		redirect('hotel/hotel_types','refresh');
	}
	function active_hotel_type($hotel_type_id1){
		$hotel_type_id 	= json_decode(base64_decode($hotel_type_id1));
		if($hotel_type_id != ''){
			$this->hotel_model->active_hotel_type($hotel_type_id);
		}
		  $this->session->set_flashdata('success_message', 'Updated Successfully!!');
		redirect('hotel/hotel_types','refresh');
	}
	function edit_hotel_type($hotel_type_id1)
	{
		$hotel_type_id 	= json_decode(base64_decode($hotel_type_id1));
		if($hotel_type_id != ''){
			
			$hotel_types['hotel_types_list'] 	= $this->hotel_model->get_hotel_types_list($hotel_type_id);
			$this->template->view('hotel/hotel_types/edit_hotel_type',$hotel_types);
		}else{
			redirect('hotel/hotel_types','refresh');
		}
	}
	function update_hotel_type($hotel_type_id1)
	{
		$hotel_type_id 	= json_decode(base64_decode($hotel_type_id1));
		if($hotel_type_id != '')
		{
			if(count($_POST) > 0){
				$this->hotel_model->update_hotel_type($_POST,$hotel_type_id);
				  $this->session->set_flashdata('success_message', 'Updated Successfully!!');
				redirect('hotel/hotel_types','refresh');
			}else if($hotel_type_id!=''){
			      $this->session->set_flashdata('success_message', 'Updated Successfully!!');
				redirect('hotel/edit_hotel_type/'.$hotel_type_id,'refresh');
			}else{
				redirect('hotel/hotel_types','refresh');
			}
		}else{
			redirect('hotel/hotel_types','refresh');
		}
	}
	function room_types()
	{
		// if (!check_user_previlege('106')) {
  //           set_update_message("You Don't have permission to do this action.", WARNING_MESSAGE, array(
  //               'override_app_msg' => true
  //           ));
  //           redirect(base_url());
  //       }
		//$hotels 						= $this->hotels_model->getHomePageSettings();
		//echo '<pre>'; print_r($hotels); exit();
		$hotels['room_types_list'] 	= $this->hotel_model->get_room_types_list();
		$this->template->view('hotel/room_type/room_types_list',$hotels);
	}
	function inactive_room_types($room_type_id1){
		$room_type_id 	= json_decode(base64_decode($room_type_id1));
		if($room_type_id != ''){
			$this->hotel_model->inactive_room_types($room_type_id);
		}
		$this->session->set_flashdata('success_message', 'Updated Successfully!!');
		redirect('hotel/room_types','refresh');
	}
	function active_room_types($room_type_id1){
		$room_type_id 	= json_decode(base64_decode($room_type_id1));
		if($room_type_id != ''){
			$this->hotel_model->active_room_types($room_type_id);
		}
		$this->session->set_flashdata('success_message', 'Updated Successfully!!');
		redirect('hotel/room_types','refresh');
	}
	function edit_room_types($room_type_id1){
		$room_type_id 	= json_decode(base64_decode($room_type_id1));
		if($room_type_id != ''){
			
			$room_types['room_types_list'] 	= $this->hotel_model->get_room_types_list($room_type_id);
			$this->template->view('hotel/room_type/edit_room_type',$room_types);
		}else{
			redirect('hotel/room_types','refresh');
		}
	}
	function update_room_types($room_type_id1){
		$room_type_id 	= json_decode(base64_decode($room_type_id1));
		if($room_type_id != ''){
			if(count($_POST) > 0){
				$this->hotel_model->update_room_types($_POST,$room_type_id);
				$this->session->set_flashdata('success_message', 'Updated Successfully!!');
				redirect('hotel/room_types','refresh');
			}else if($hotel_type_id!=''){
				redirect('hotel/edit_room_type/'.$room_type_id,'refresh');
			}else{
				redirect('hotel/room_types','refresh');
			}
		}else{
			redirect('hotel/room_types','refresh');
		}
	}
	function add_room_types()
	{
		
		$data=array();
		$this->template->view('hotel/room_type/add_room_type',$data);
	}
	function save_room_type_data()
	{
		try 
		{
			if(isset($_POST['room_type_name'])==FALSE || empty($_POST['room_type_name'])==TRUE)
			{
				throw new Exception("Error Processing Request", 1);				
			}
			$data['name']=$_POST['room_type_name'];
			// $data['created_by']=$_POST['user_id'];
			$data['created_by']=$this->entity_user_id;
			$data['status']=$_POST['status'];

			if($this->hotel_model->add_room_type_details($data)==false)
			{
				throw new Exception('Room Type Adding Failed', 1);				
			}
			$this->session->set_flashdata('success_message', 'Inserted Successfully!!');
			redirect('hotel/room_types','refresh');				
		} 
		catch (Exception $e) 
		{
			$this->session->set_flashdata('error_message', $e->getMessage());
			redirect('hotel/add_room_types','refresh');				
		}
	}
	function hotel_ammenities(){
		// if (!check_user_previlege('106')) {
  //           set_update_message("You Don't have permission to do this action.", WARNING_MESSAGE, array(
  //               'override_app_msg' => true
  //           ));
  //           redirect(base_url());
  //       }
		
		$ammenities['ammenities_list'] 		= $this->hotel_model->get_ammenities_list();
		//debug($ammenities);die;
		$this->template->view('hotel/hotel_ammenities/ammenities_list',$ammenities);
	}
	function add_hotel_ammenties()
	{	
		
		$data=array();	
		$this->template->view('hotel/hotel_ammenities/add_hotel_ammenity',$data);
	}
	function save_hotel_amenities_data()
	{
		// debug($_POST);die;
		try 
		{
			if(isset($_POST['hotel_ammenity_name'])==FALSE || empty($_POST['hotel_ammenity_name'])==TRUE)
			{
				throw new Exception("Error Processing Request", 1);				
			}
			$data['name']=$_POST['hotel_ammenity_name'];
			// $data['created_by']=$_POST['user_id'];
			$data['created_by']=$this->entity_user_id;
			$data['status']=$_POST['status'];
			if($data['status']=="")
			{
				$data['status']="INACTIVE";
			}
// debug($data);die;
			if($this->hotel_model->add_hotel_ammenity_details($data)==false)
			{
				throw new Exception('Hotel Amenities  Adding Failed', 1);				
			}
			$this->session->set_flashdata('success_message', 'Inserted Successfully!!');
			redirect('hotel/hotel_ammenities','refresh');				
		} 
		catch (Exception $e) 
		{
			$this->session->set_flashdata('error_message', $e->getMessage());
			redirect('hotel/add_hotel_ammenties','refresh');				
		}	
	}

	function inactive_hotel_ammenity($ammenity_id1)
	{
		$ammenity_id 	= json_decode(base64_decode($ammenity_id1));
		if($ammenity_id != ''){
			$this->hotel_model->inactive_hotel_ammenity($ammenity_id);
			$this->session->set_flashdata('success_message', 'Updated Successfully!!');
		}	
		redirect('hotel/hotel_ammenities','refresh');
	}
	function active_hotel_ammenity($ammenity_id1){
		$ammenity_id 	= json_decode(base64_decode($ammenity_id1));
		if($ammenity_id != ''){
			$this->hotel_model->active_hotel_ammenity($ammenity_id);
				$this->session->set_flashdata('success_message', 'Updated Successfully!!');
		}
		redirect('hotel/hotel_ammenities','refresh');
	}
	function edit_hotel_ammenity($ammenity_id1){
		$ammenity_id 	= json_decode(base64_decode($ammenity_id1));
		if($ammenity_id != ''){
			
			$hotel_ammenities['ammenities_list'] 		= $this->hotel_model->get_ammenities_list($ammenity_id);
			$this->template->view('hotel/hotel_ammenities/edit_hotel_ammenity',$hotel_ammenities);
		}else{
			redirect('hotel/hotel_ammenities','refresh');
		}
	}
	function update_hotel_ammenity($ammenity_id1){
		$ammenity_id 	= json_decode(base64_decode($ammenity_id1));
		if($ammenity_id != ''){
			if(count($_POST) > 0){
				$this->hotel_model->update_hotel_ammenity($_POST,$ammenity_id);
					$this->session->set_flashdata('success_message', 'Updated Successfully!!');
					//debug($this->session->userdata);exit;
				redirect('hotel/hotel_ammenities','refresh');
			}else if($hotel_type_id!=''){
				redirect('hotel/edit_hotel_ammenity/'.$ammenity_id,'refresh');
			}else{
				redirect('hotel/hotel_ammenities','refresh');
			}
		}else{
			redirect('hotel/hotel_ammenities','refresh');
		}
	}
	function room_ammenities(){
		// if (!check_user_previlege('106')) {
  //           set_update_message("You Don't have permission to do this action.", WARNING_MESSAGE, array(
  //               'override_app_msg' => true
  //           ));
  //           redirect(base_url());
  //       }
		
		$ammenities['ammenities_list'] 		= $this->hotel_model->get_room_ammenities_list();
		$this->template->view('hotel/room_ammenities/ammenities_list',$ammenities);
	}
	function add_room_ammenties()
	{
		$data=array();
		$this->template->view('hotel/room_ammenities/add_hotel_ammenity',$data);
	}
	function save_room_amenities_data()
	{
		// debug($_POST);die;
		try 
		{
			if(isset($_POST['hotel_ammenity_name'])==FALSE || empty($_POST['hotel_ammenity_name'])==TRUE)
			{
				throw new Exception("Error Processing Request", 1);				
			}
			$data['name']=$_POST['hotel_ammenity_name'];
			// $data['created_by']=$_POST['user_id'];
			$data['created_by']=$this->entity_user_id;
			$data['status']=$_POST['status'];
// debug($data);die;
			if($this->hotel_model->add_room_ammenity_details($data)==false)
			{
				throw new Exception('Room Amenities  Adding Failed', 1);				
			}

			$this->session->set_flashdata('success_message', 'Inserted Successfully!!');
			redirect('hotel/room_ammenities','refresh');				
		} 
		catch (Exception $e) 
		{
			$this->session->set_flashdata('error_message', $e->getMessage());
			redirect('hotel/save_room_amenities_data','refresh');				
		}	
	}
	function edit_room_ammenity($ammenity_id1){
		$ammenity_id 	= json_decode(base64_decode($ammenity_id1));
		if($ammenity_id != ''){
			
			$hotel_ammenities['ammenities_list'] 		= $this->hotel_model->get_room_ammenities_list($ammenity_id);
			$this->template->view('hotel/room_ammenities/edit_hotel_ammenity',$hotel_ammenities);
		}else{
			redirect('hotel/room_ammenities','refresh');
		}
	}
	function update_room_ammenity($ammenity_id1)
	{
		$ammenity_id 	= json_decode(base64_decode($ammenity_id1));
		if($ammenity_id != ''){
			if(count($_POST) > 0){
				$this->hotel_model->update_room_ammenity($_POST,$ammenity_id);
					$this->session->set_flashdata('success_message', 'Updated Successfully!!');
				redirect('hotel/room_ammenities','refresh');
			}else if($hotel_type_id!=''){
				redirect('hotel/edit_room_ammenity/'.$ammenity_id,'refresh');
			}else{
				redirect('hotel/room_ammenities','refresh');
			}
		}else{
			redirect('hotel/room_ammenities','refresh');
		}
	}
	function inactive_room_ammenity($ammenity_id1){
		$ammenity_id 	= json_decode(base64_decode($ammenity_id1));
		if($ammenity_id != ''){
			$this->hotel_model->inactive_room_ammenity($ammenity_id);
			$this->session->set_flashdata('success_message', 'Updated Successfully!!');
		}
		redirect('hotel/room_ammenities','refresh');
	}
	function active_room_ammenity($ammenity_id1){
		$ammenity_id 	= json_decode(base64_decode($ammenity_id1));
		if($ammenity_id != ''){
			$this->hotel_model->active_room_ammenity($ammenity_id);
				$this->session->set_flashdata('success_message', 'Updated Successfully!!');
		}
		redirect('hotel/room_ammenities','refresh');
	}
}
