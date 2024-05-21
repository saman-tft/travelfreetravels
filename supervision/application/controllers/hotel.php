<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @package    Provab
 * @subpackage Hotel
 * @author     Balu A<balu.provab@gmail.com>
 * @version    V1
 */

class Hotel extends CI_Controller 
{
	private $current_module;
	public function __construct()
	{
		parent::__construct();
		//we need to activate hotel api which are active for current domain and load those libraries
		$this->index();
		$this->load->model('hotel_model');
			$this->load->model('hotels_model');
	//	$this->load->model('Package_Model');
		    $this->load->model(array('tours_model','custom_db','Package_Model'));

		$this->load->model('domain_management_model');
		$this->current_module = $this->config->item('current_module');
	}

	/**
	 * index page of application will be loaded here
	 */
	function index()
	{

	}
	 function inactive_hotel_image($hotel_id = 0, $image_id = 0) {
        if ($image_id != '') {
            $this->hotels_model->inactive_hotel_image($image_id);
        }
        $this->session->set_flashdata('success_message', 'Updated Successfully!!');
        redirect('hotel/hotel_crs_images/' . $hotel_id, 'refresh');
    }

    function active_hotel_image($hotel_id = 0, $image_id = 0) {
        if ($image_id != '') {
            $this->hotels_model->active_hotel_images($image_id);
        }
        $this->session->set_flashdata('success_message', 'Updated Successfully!!');
        redirect('hotel/hotel_crs_images/' . $hotel_id, 'refresh');
    }
  function delete_hotel_images($post = 0, $id = 0) {
        $this->hotels_model->delete_hotel_images($id);
        redirect("hotel/hotel_crs_images/{$post}", 'refresh');
    }

    function delete_hotel_image($hotel_id = 0, $image_id = 0) {
        if ($image_id != '') {
            $result = $this->hotels_model->delete_hotel_image($image_id);
        }
        redirect('hotel/hotel_crs_images/' . $hotel_id, 'refresh');
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
				$assembled_booking_details = $this->booking_data_formatter->format_hotel_booking_data($booking_details,$this->current_module);
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
				$booked_user_id = intval($booking_details['data']['booking_details'][0]['created_by_id']);
				$booked_user_details = array();
				$is_agent = false;
				$user_condition[] = array('U.user_id' ,'=', $booked_user_id);
				$booked_user_details = $this->user_model->get_user_details($user_condition);
				if(valid_array($booked_user_details) == true){
					$booked_user_details = $booked_user_details[0];
					if($booked_user_details['user_type'] == B2B_USER){
						$is_agent = true;
					}
				}
				$page_data = array();
				$page_data['booking_data'] = 		$booking_details['data'];
				$page_data['booked_user_details'] =	$booked_user_details;
				$page_data['is_agent'] = 			$is_agent;
				$this->template->view('hotel/cancellation_refund_details', $page_data);
			} else {
				redirect(base_url());
			}
		} else {
			redirect(base_url());
		}
	}
	/**
	 * Updates Cancellation Refund Details
	 */
	public function update_refund_details()
	{
		$post_data = $this->input->post();
		$redirect_url_params = array();
		$this->form_validation->set_rules('app_reference', 'app_reference', 'trim|required|xss_clean');
		$this->form_validation->set_rules('status', 'passenger_status', 'trim|required|xss_clean');
		$this->form_validation->set_rules('status', 'passenger_status', 'trim|required|xss_clean');
		$this->form_validation->set_rules('refund_payment_mode', 'refund_payment_mode', 'trim|required|xss_clean');
		$this->form_validation->set_rules('refund_amount', 'refund_amount', 'trim|numeric');
		$this->form_validation->set_rules('cancellation_charge', 'cancellation_charge', 'trim|numeric');
		$this->form_validation->set_rules('refund_status', 'refund_status', 'trim|required|xss_clean');
		$this->form_validation->set_rules('refund_comments', 'refund_comments', 'trim|required');
		if ($this->form_validation->run()) {
			$app_reference = 				trim($post_data['app_reference']);
			$booking_source = 				trim($post_data['booking_source']);
			$status = 						trim($post_data['status']);
			$refund_payment_mode = 			trim($post_data['refund_payment_mode']);
			$refund_amount = 				floatval($post_data['refund_amount']);
			$cancellation_charge = 			floatval($post_data['cancellation_charge']);
			$refund_status = 				trim($post_data['refund_status']);
			$refund_comments = 				trim($post_data['refund_comments']);
			//Get Booking Details
			$booking_details = $this->hotel_model->get_booking_details($app_reference, $booking_source, $status);
			if($booking_details['status'] == SUCCESS_STATUS){
				$master_booking_details = $booking_details['data']['booking_details'][0];
				$booking_currency = $master_booking_details['currency'];//booking currency
				$booked_user_id = intval($master_booking_details['created_by_id']);
				$user_condition[] = array('U.user_id' ,'=', $booked_user_id);
				$booked_user_details = $this->user_model->get_user_details($user_condition);
				$is_agent = false;
				if(valid_array($booked_user_details) == true && $booked_user_details[0]['user_type'] == B2B_USER){
					$is_agent = true;
				}
				//REFUND AMOUNT TO AGENT
				$currency_obj = new Currency(array('from' => get_application_default_currency() , 'to' => $booking_currency));
				$currency_conversion_rate = $currency_obj->currency_conversion_value(true, get_application_default_currency(), $booking_currency);
				if($refund_status == 'PROCESSED' && floatval($refund_amount) > 0 && $is_agent == true){
					//1.Crdeit the Refund Amount to Respective Agent
					$agent_refund_amount = ($currency_conversion_rate*$refund_amount);//converting to agent currency

					//2.Add Transaction Log for the Refund
					$fare = -($refund_amount);//dont remove: converting to negative
					$domain_markup=0;
					$level_one_markup=0;
					$convinence = 0;
					$discount = 0;
					$remarks = 'hotel Refund was Successfully done';
					$this->domain_management_model->save_transaction_details('hotel', $app_reference, $fare, $domain_markup, $level_one_markup, $remarks, $convinence, $discount, $booking_currency, $currency_conversion_rate, $booked_user_id);

					// update agent balance
					$this->domain_management_model->update_agent_balance($agent_refund_amount, $booked_user_id);
				}
				//UPDATE THE REFUND DETAILS
				//Update Condition
				$update_refund_condition = array();
				$update_refund_condition['app_reference'] =	$app_reference;
				//Update Data
				$update_refund_details = array();
				$update_refund_details['refund_payment_mode'] = 			$refund_payment_mode;
				$update_refund_details['refund_amount'] =					$refund_amount;
				$update_refund_details['cancellation_charge'] = 			$cancellation_charge;
				$update_refund_details['refund_status'] = 					$refund_status;
				$update_refund_details['refund_comments'] = 				$refund_comments;
				$update_refund_details['currency'] = 						$booking_currency;
				$update_refund_details['currency_conversion_rate'] = 		$currency_conversion_rate;
				if($refund_status == 'PROCESSED'){
					$update_refund_details['refund_date'] = 				date('Y-m-d H:i:s');
				}
				$this->custom_db->update_record('hotel_cancellation_details', $update_refund_details, $update_refund_condition);
				
				$redirect_url_params['app_reference'] = $app_reference;
				$redirect_url_params['booking_source'] = $master_booking_details['booking_source'];
				$redirect_url_params['status'] = $status;
			}
		}
		redirect('hotel/cancellation_refund_details?'.http_build_query($redirect_url_params));
	}
	/**
	 * Balu A
	 * Get supplier cancellation status
	 */
	public function update_supplier_cancellation_status_details()
	{
		$get_data = $this->input->get();
		if(isset($get_data['app_reference']) == true && isset($get_data['booking_source']) == true && isset($get_data['status']) == true && $get_data['status'] == 'BOOKING_CANCELLED'){
			$app_reference = trim($get_data['app_reference']);
			$booking_source = trim($get_data['booking_source']);
			$status = trim($get_data['status']);
			$booking_details = $this->hotel_model->get_booking_details($app_reference, $booking_source, $status);
			if($booking_details['status'] == SUCCESS_STATUS){
				$master_booking_details = $booking_details['data']['booking_details'];
				$booking_customer_details = $booking_details['data']['booking_customer_details'][0];
				$cancellation_details = $booking_details['data']['cancellation_details'][0];
				$ChangeRequestId =		$cancellation_details['ChangeRequestId'];
				load_hotel_lib($booking_source);
				$response = $this->hotel_lib->get_cancellation_refund_details($ChangeRequestId, $app_reference);
				if($response['status'] == SUCCESS_STATUS){
					$cancellation_details = $response['data'];
					$this->hotel_model->update_cancellation_refund_details($app_reference, $cancellation_details);
				}
			}
		}
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
	function hotel_crs_list()
	{ 
		//$hotels 						= $this->hotel_model->getHomePageSettings();
		$hotels['hotels_list'] 	   		= $this->hotel_model->get_all_hotel_crs_list(0,10000000);
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
			// $hotels['settings'] 			= $this->hotel_model->get_hotel_settings_list();
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
			$get_data = $this->input->get();
			//debug($get_data);exit;
			$hotels['rooms_list'] 	   		= $this->hotel_model->get_crs_room_list($hotel_id,$get_data['room_type_id'],$get_data['board_type']);
			//echo $this->db->last_query();exit;
			$hotels['seasons_list'] 		= count($this->hotel_model->season_list($hotel_id)->result());
			$hotels['hotel_id'] 	   		= $hotel_id;
			$hotels['room_types_list'] 	= $this->hotel_model->get_room_types_list();
			$hotels['board_types_list'] 	= $this->hotel_model->board_types_list();
			 //debug($hotels);die;
			
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
			$hotels['board_types_list'] 	= $this->hotel_model->board_types_list();
			$hotels['countries'] = $this->Package_Model->get_api_country_list();
			    $hotels['meal_list'] = $this->hotels_model->get_room_meal_list();
// 			debug($hotels['data']);die;
			$hotels['hotel_id'] 	   		= $hotel_id;
			$this->template->view('hotel/rooms/edit_room',$hotels);
	}
	function room_price_list($hotel_id=0,$room_id=0)
	{
		$data['hotel_id']=$hotel_id;
		$data['room_id']=$room_id;
		//$data['price_list'] 		= $this->hotel_model->room_price_list($hotel_id,$room_id)->result();
		$data['price_list'] 		= $this->hotel_model->room_price_list($hotel_id,$room_id)->result();
		//echo $this->db->last_query();die;
		$data['room_data'] 	   		= $this->hotel_model->get_crs_room($room_id)->row();
		$data['room_types_list'] 	= $this->hotel_model->get_room_types_list();
		$data['board_types_list'] 	= $this->hotel_model->get_board_types_list();
// 		echo $this->db->last_query();die;
 		//debug($data);die;
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
	   $currency  = $this->Package_Model->get_currency_list(); 
	   $data['currency'] = $currency;
	   $data['seasons'] 		= $this->hotel_model->season_list($data['data']->hotel_id)->result();
	   $data ['nationality_group'] = $this->hotel_model->nationality_group_data ();
	   	$data["country"] = $this->custom_db->single_table_records('tours_country', '*')[data];
	//	   	debug($data ['nationality_group']);die;
            	 $data['holiday_data'] = $data; 
		$this->template->view('hotel/rooms/edit_price',$data);	
	}
	function add_room_price($hotel_id=0,$room_id=0)
	{
	    
	    
		$data['hotel_id']=$hotel_id;
		$data['room_id']=$room_id;
		$data['seasons'] 		= $this->hotel_model->season_list($hotel_id)->result();
	$data ['nationality_group'] = $this->hotel_model->nationality_group_data ();
		$currency  = $this->Package_Model->get_currency_list(); 
  		$data['currency'] = $currency;
  			$data["country"] = $this->custom_db->single_table_records('tours_country', '*')[data];



            	 $data['holiday_data'] =$data; 
		$this->template->view('hotel/rooms/add_price_info',$data);	
	}
	function room_cancellation_list($hotel_id=0,$room_id=0)
	{
		$data['hotel_id']=$hotel_id;
		$data['room_id']=$room_id;
		$data['price_list'] 		= $this->hotel_model->room_cancellation_list($hotel_id,$room_id)->result();
 	//	debug($data['price_list']);die;
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
	        $config['allowed_types']        = 'gif|jpg|png|jpeg';
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
			$hotels['board_types_list'] 	= $this->hotel_model->board_types_list();
			$hotels['countries'] = $this->Package_Model->get_api_country_list();
			$hotels['ammenities_list'] 		= $this->hotel_model->get_room_ammenities_list();
			
			
		$hotels['meal_list'] = $this->hotels_model->get_room_meal_list();
			$hotels['hotel_id'] 	   		= $hotel_id;
			$this->template->view('hotel/rooms/add_room',$hotels);
	}
	  function nationality_region()
  {
      
      
         $nationality_region = $this->hotel_model->nationality_region();
         $page_data['nationality_region'] = $nationality_region;
         $this->template->view('hotel/nationality/nationality_region',$page_data);
  }
  function region_save()
  {
     $data = $this->input->post();
      $tour_region   = sql_injection($data['tour_region']);
      $check_availibility = $this->hotel_model->check_region_exist_all($tour_region);
      if(!$check_availibility)
      {
        $query = "insert into all_nationality_region set name='$tour_region', status=1, module='hotel', created_by=".$this->entity_user_id." ";        
            //echo $query; //exit;
        $return = $this->hotel_model->query_run($query);
        if($return)
          {   $this->session->set_flashdata('message', UL0014);
        redirect('hotel/nationality_region'); }
        else
          { echo $return; exit; } 
      }
      else
      {
       $this->session->set_flashdata('region_msg','Region is already exist');
       redirect('hotel/nationality_region');
     }

  }
  public function activation_nationality_region($id,$status) {
    $return = $this->hotel_model->record_activation('all_nationality_region',$id,$status);
    // debug($return);exit();
    if($return){redirect('hotel/nationality_region');} 
    else { echo $return;} 
  }
  public function edit_nationality_region($id)
  {
     $region_details = $this->hotel_model->table_record_details('all_nationality_region',$id);

      $page_data['region_details'] = $region_details;
        // debug($page_data); exit;
      $this->template->view('hotel/nationality/edit_nationality_region',$page_data);
  }


  public function edit_nationality_region_save() {
    $data = $this->input->post();
      //debug($data); exit;
    $id             = $data['id'];
    $tour_region  = sql_injection($data['tour_region']);
    $query = "update all_nationality_region set name='$tour_region' where id='$id'";        
          //echo $query; //exit;
    $return = $this->hotel_model->query_run($query);
    if($return)
      {   
      $this->session->set_flashdata('message', UL0013);
      redirect('hotel/edit_nationality_region/'.$id); }
    else
      { echo $return; exit; }              
  }


  public function delete_nationality_region($id) {
    $return = $this->hotel_model->record_delete('all_nationality_region',$id);
    if($return){
      $this->session->set_flashdata('message', UL0099);
      redirect('hotel/nationality_region');} 
    else { echo $return;} 
  }
   public function delete_nationality_region2($id) {
    $return = $this->hotel_model->record_delete2('all_nationality_country',$id);
    if($return){
      $this->session->set_flashdata('message', UL0099);
      redirect('hotel/view_notionality_country');} 
    else { echo $return;} 
  }
  public function view_notionality_country() 
    {
        
      $page_data ['notionality_country'] = $this->hotel_model->get_nationalityCountryList();
       // debug($page_data);exit;
      $this->template->view ('hotel/nationality/view_notionality_country', $page_data );
    }


  public function nationality_country($id = '') {
      //error_reporting(E_All);
      $page_data ['nationality_regions'] = $this->hotel_model->get_nationality_regions();
      $page_data['country_list']=$this->hotel_model->get_hb_country_list();
       $currency  = $this->tours_model->get_currency_list(); 
  $currency_nat_price  = $this->Package_Model->get_currency_list(); 
  $page_data['currency'] = $currency;
  $page_data['currency_nat_price'] = $currency_nat_price;
    $page_data ['edit_notionality_country']='';
      // debug($data ['tours_continent']);exit();
      if ($id != '') 
      {
        $page_data ['id']=$id;
        $page_data ['edit_notionality_country'] = $this->hotel_model->get_nationalityCountryList($id);
        // debug($page_data ['edit_notionality_country']);exit;
        $this->template->view ( 'hotel/nationality/notionality_country', $page_data );
      } else {
        $this->template->view ( 'hotel/nationality/notionality_country',$page_data );
      }
    }

 
    public function save_nationalityCountries() 
    {
     
     ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
         $data = $this->input->post();
        
         $pack_id=$data['pack_id'];
         $hb_country_list=$this->hotel_model->get_hb_country_list();

         $except_countryIds =array();
         $except_countryCodes =array();
         $except_countryNames  =array();

         $include_countryIds =array();
         $include_countryCodes =array();
         $include_countryNames =array();
         // debug($data['tours_country']);
         $raw_except_countries=[];
         foreach ($hb_country_list as $value)
         {
            foreach ($data['tours_country'] as $key => $val)
            {
              if($val == $value['origin']) 
              {               
                array_push($include_countryIds,$value['origin']);
                array_push($include_countryCodes,$value['country_code']);
                array_push($include_countryNames ,$value['country_name']);                 
              }
              else
              { 
                $raw_except_countries [$value['origin']] ['origin']=$value['origin'];
                $raw_except_countries [$value['origin']] ['country_code']=$value['country_code'];
                $raw_except_countries [$value['origin']] ['country_name']=$value['country_name'];

              }
            }
         }

         if(count($data['tours_country']>0)){
              foreach ($data['tours_country'] as  $value) {
                if(isset($raw_except_countries [$value])){
                  unset($raw_except_countries [$value]);
                }
              }
         }
        $except_countryIds   = array_column($raw_except_countries, 'origin');
        $except_countryCodes = array_column($raw_except_countries, 'country_code');
        $except_countryNames = array_column($raw_except_countries, 'country_name');        

         
     $except_countryIds      = implode(',',array_unique($except_countryIds));
     $except_countryCodes      = implode(',',array_unique($except_countryCodes));
     $except_countryNames      = implode(',',array_unique($except_countryNames));   
 
       $include_countryIds      = implode(',',array_unique($include_countryIds));
     $include_countryCodes      = implode(',',array_unique($include_countryCodes));
     $include_countryNames      = implode(',',array_unique($include_countryNames));
    
 
 
       $tours_continent = $this->input->post ( 'tours_continent' );
       $package_name = $this->input->post ( 'name' );
  $currency = $this->input->post ( 'currency_sel' );
     
       $data_ins=array(
           'name' => $package_name,
           'module' => 'hotel', 
           'currency' =>$currency, 
           'continent' =>$tours_continent, 
           'except_countryIds' => $except_countryIds, 
           'except_countryCodes' => $except_countryCodes, 
           'except_countryNames' =>$except_countryNames,
           'include_countryIds' => $include_countryIds, 
           'include_countryCodes' => $include_countryCodes, 
           'include_countryNames' =>$include_countryNames,
           'created_by' =>$this->entity_user_id,      
           'created_datetime'=>date('Y-m-d H:m:s'),
           'status'=>1
       );
       

       // debug($data_ins);exit();
        $repeat_nationality = $this->hotel_model->check_nationality_duplicate($tours_continent,$package_name);
       if(empty($repeat_nationality)){
       if($pack_id>0)
       {
         $price_cat = $this->hotel_model->update_price_cat($data_ins,$pack_id);
       }
       else
       {
         $price_cat = $this->hotel_model->add_price_cat($data_ins);

       }
      // debug($this->db->last_query());exit;

       if($price_cat)
       {
           $this->session->set_flashdata(array('message' => 'UL0014', 'type' => SUCCESS_MESSAGE));
           redirect ( 'hotel/view_notionality_country' );
       }
       else
       {
           $this->session->set_flashdata(array('message' => 'UL0098', 'type' => ERROR_MESSAGE));
       }
     }else{
      $this->session->set_flashdata(array('message' => 'UL0098', 'type' => ERROR_MESSAGE));redirect ( 'hotel/view_notionality_country' );
     }
 }

	function save_room_details_data()
	{
		try 
		{
			$hotel_id=$this->input->post('hotel_id');
			$data['hotel_id']=$this->input->post('hotel_id');
			$data['nationality']=$this->input->post('nationality');
			$data['board_type']=$this->input->post('board_type');
			$data['room_type_id']=$this->input->post('room_type_id');
			$data['max_stay']=$this->input->post('max_stay');
			$data['status']=$this->input->post('status');
			$data['max_adult_capacity']=$this->input->post('max_adult_capacity');
			$data['max_child_capacity']=$this->input->post('max_child_capacity');
			$data['extra_bed']=$this->input->post('extra_bed');
			$data['room_policy']=$this->input->post('room_policy');
			$data['room_description']=$this->input->post('room_description');
			$data['room_amenities']=implode(",",$this->input->post('room_amenities'));
			 $data['room_meal_type'] = $this->input->post('room_meal');
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
			$data['nationality']=$this->input->post('nationality');
			$data['board_type']=$this->input->post('board_type');
			$data['room_type_id']=$this->input->post('room_type_id');
			$data['max_stay']=$this->input->post('max_stay');
			$data['status']=$this->input->post('status');
			$data['max_adult_capacity']=$this->input->post('max_adult_capacity');
			$data['max_child_capacity']=$this->input->post('max_child_capacity');
			$data['extra_bed']=$this->input->post('extra_bed');
			$data['room_policy']=$this->input->post('room_policy');
			$data['room_description']=$this->input->post('room_description');
			$data['room_meal_type'] = $this->input->post('room_meal');
			$data['room_amenities']=implode(",",$this->input->post('room_amenities'));
		//	debug($data);die;
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
				$data['penality_type']=$this->input->post('penality_type');
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
			$data['currency']=$this->input->post('currency');
			//$data['date_from']=date ('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('date_from'))));
			//$data['date_to']=date ('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('date_to'))));
			$data['one_adult']=$this->input->post('one_adult');
			$data['season']=$this->input->post('season');
			$data['two_adult']=$this->input->post('two_adult');
			$data['three_adult']=$this->input->post('three_adult');
			$data['child_price']=$this->input->post('child_price');

//--------currency---------//
			$currency_obj = new Currency(array('module_type' => 'hotel','from' => $data['currency'], 'to' => 'NPR')); 
	       $get_currency_symbol = ($this->session->userdata('currency') != '') ? $this->CI->session->userdata('currency') : 'NPR';
	      		//debug($get_currency_symbol);exit;
		     $current_currency_symbol = $currency_obj->get_currency_symbol($get_currency_symbol);
		     $converted_currency_rate = $currency_obj->getConversionRate(false);
	    	 //debug($converted_currency_rate);exit;
		      $converted_one_adult = $converted_currency_rate*$data['one_adult'];
		      $converted_two_adult = $converted_currency_rate*$data['two_adult'];
		      $converted_three_adult = $converted_currency_rate*$data['three_adult'];
		      $converted_child_price = $converted_currency_rate*$data['child_price'];
//--------currency---------//
			$data['converted_one_adult']=$converted_one_adult;
			$data['converted_two_adult']=$converted_two_adult;
			$data['converted_three_adult']=$converted_three_adult;
			$data['converted_child_price']=$converted_child_price;

			$data['status']=$this->input->post('status');
			$data['extrabed']=$this->input->post('extrabed');
			$data['extrabed_price']=$this->input->post('extrabed_price');

			$converted_extrabed_price = $converted_currency_rate*$data['extrabed_price'];
			$data['converted_extrabed_price']=$converted_extrabed_price;

			$data['one_adult_breakfast']=$this->input->post('one_adult_breakfast');
			$data['two_adult_breakfast']=$this->input->post('two_adult_breakfast');
			$data['three_adult_breakfast']=$this->input->post('three_adult_breakfast');
			$data['child_breakfast']=$this->input->post('child_breakfast');
			$data['child_breakfast_age']=$this->input->post('child_breakfast_age');
			$data['min_stay']=$this->input->post('min_stay');
			$data['nationality']=$this->input->post('nationality');
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
			$data['currency']=$this->input->post('currency');
			//$data['date_from']=date ('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('date_from'))));
			//$data['date_to']=date ('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('date_to'))));
			$data['one_adult']=$this->input->post('one_adult');
			$data['season']=$this->input->post('season');
			$data['two_adult']=$this->input->post('two_adult');
			$data['three_adult']=$this->input->post('three_adult');
			$data['child_price']=$this->input->post('child_price');
			//--------currency---------//
			$currency_obj = new Currency(array('module_type' => 'hotel','from' => $data['currency'], 'to' => 'NPR')); 
	       $get_currency_symbol = ($this->session->userdata('currency') != '') ? $this->CI->session->userdata('currency') : 'NPR';
	      		//debug($get_currency_symbol);exit;
		     $current_currency_symbol = $currency_obj->get_currency_symbol($get_currency_symbol);
		     $converted_currency_rate = $currency_obj->getConversionRate(false);
	    	 //debug($converted_currency_rate);exit;
		      $converted_one_adult = $converted_currency_rate*$data['one_adult'];
		      $converted_two_adult = $converted_currency_rate*$data['two_adult'];
		      $converted_three_adult = $converted_currency_rate*$data['three_adult'];
		      $converted_child_price = $converted_currency_rate*$data['child_price'];
//--------currency---------//
			$data['converted_one_adult']=$converted_one_adult;
			$data['converted_two_adult']=$converted_two_adult;
			$data['converted_three_adult']=$converted_three_adult;
			$data['converted_child_price']=$converted_child_price;
			$data['status']=$this->input->post('status');
			$data['extrabed']=$this->input->post('extrabed');
			$data['extrabed_price']=$this->input->post('extrabed_price');
			$converted_extrabed_price = $converted_currency_rate*$data['extrabed_price'];
			$data['converted_extrabed_price']=$converted_extrabed_price;
			$data['one_adult_breakfast']=$this->input->post('one_adult_breakfast');
			$data['two_adult_breakfast']=$this->input->post('two_adult_breakfast');
			$data['three_adult_breakfast']=$this->input->post('three_adult_breakfast');
			$data['child_breakfast']=$this->input->post('child_breakfast');
			$data['child_breakfast_age']=$this->input->post('child_breakfast_age');
			$data['min_stay']=$this->input->post('min_stay');
			$data['nationality']=$this->input->post('nationality');
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
			$data['created_by']=$_POST['user_id'];
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
		
		//$hotels 						= $this->hotels_model->getHomePageSettings();
		//echo '<pre>'; print_r($hotels); exit();
		$hotels['room_types_list'] 	= $this->hotel_model->get_room_types_list();
		$this->template->view('hotel/room_type/room_types_list',$hotels);
	}
	function board_types()
	{
	
	
		//$hotels 						= $this->hotels_model->getHomePageSettings();
		//echo '<pre>'; print_r($hotels); exit();
		$hotels['room_board_list'] 	= $this->hotel_model->get_board_types_list();
		$this->template->view('hotel/board_type/board_types_list',$hotels);
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
	function inactive_board_types($board_type_id1){
		$board_type_id 	= json_decode(base64_decode($board_type_id1));
		if($board_type_id != ''){
			$this->hotel_model->inactive_board_types($board_type_id);
		}
		$this->session->set_flashdata('success_message', 'Updated Successfully!!');
		redirect('hotel/board_types','refresh');
	}
	function active_board_types($board_type_id1){
		$board_type_id 	= json_decode(base64_decode($board_type_id1));
		if($board_type_id != ''){
			$this->hotel_model->active_board_types($board_type_id);
		}
		$this->session->set_flashdata('success_message', 'Updated Successfully!!');
		redirect('hotel/board_types','refresh');
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
	function edit_board_types($board_type_id1){
		$board_type_id 	= json_decode(base64_decode($board_type_id1));
		if($board_type_id != ''){
			
			$board_types['board_types_list'] 	= $this->hotel_model->get_board_types_list($board_type_id);
			$this->template->view('hotel/board_type/edit_board_type',$board_types);
		}else{
			redirect('hotel/board_types','refresh');
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
	function update_board_types($board_type_id1){
		$board_type_id 	= json_decode(base64_decode($board_type_id1));
		if($board_type_id != ''){
			if(count($_POST) > 0){
				$this->hotel_model->update_board_types($_POST,$board_type_id);
				$this->session->set_flashdata('success_message', 'Updated Successfully!!');
				redirect('hotel/board_types','refresh');
			}else if($hotel_type_id!=''){
				redirect('hotel/edit_board_type/'.$board_type_id,'refresh');
			}else{
				redirect('hotel/board_types','refresh');
			}
		}else{
			redirect('hotel/board_types','refresh');
		}
	}
	function add_room_types()
	{
		
		$data=array();
		$this->template->view('hotel/room_type/add_room_type',$data);
	}
	function add_board_types()
	{
		
		$data=array();
		$this->template->view('hotel/board_type/add_board_type',$data);
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
			$data['created_by']=$_POST['user_id'];
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
	function save_board_type_data()
	{
		try 
		{
			if(isset($_POST['board_type_name'])==FALSE || empty($_POST['board_type_name'])==TRUE)
			{
				throw new Exception("Error Processing Request", 1);				
			}
			$data['name']=$_POST['board_type_name'];
			$data['created_by']=$_POST['user_id'];
			$data['status']=$_POST['status'];

			if($this->hotel_model->add_board_type_details($data)==false)
			{
				throw new Exception('Board Type Adding Failed', 1);				
			}
			$this->session->set_flashdata('success_message', 'Inserted Successfully!!');
			redirect('hotel/board_types','refresh');				
		} 
		catch (Exception $e) 
		{
			$this->session->set_flashdata('error_message', $e->getMessage());
			redirect('hotel/add_board_types','refresh');				
		}
	}
	function hotel_ammenities(){
		
		
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
			$data['created_by']=$_POST['user_id'];
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
			$data['created_by']=$_POST['user_id'];
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

	public function ajax_hotel_home_publish() {
  $data = $this->input->post();
  // debug($data); exit('');
  $hotel_id        = sql_injection($data['hotel_id']);
  $name       = sql_injection($data['name']);
  $publish_status = sql_injection($data['publish_status']);
  // debug($hotel_id);
  // debug($name);
  // debug($publish_status);exit;
  $select_details  = "select * from crs_hotel_details where id='$hotel_id'";
  $ajax_hotel_details = $this->hotel_model->ajax_hotel_details($select_details);
  if($ajax_hotel_details[0]['image'] != '')
  	{
		if($ajax_hotel_details[0]['status'] == 'ACTIVE')
		{
			  $select_query  = "select * from crs_room_price where hotel_id='$hotel_id'";
			  $num_ajax_tour_publish_1 = $this->hotel_model->ajax_hotel_publish_1($select_query);
			  if($num_ajax_tour_publish_1 == 1)
			  {

					 if($name == 'homepagecrs'){
					 	$query  = "update crs_hotel_details set displayhp_crs_status='$publish_status' where id='$hotel_id'";
					 }elseif($name == 'villa'){
					 	$query  = "update crs_hotel_details set displaytopvilla_status='$publish_status' where id='$hotel_id'";
					 }elseif($name == 'hotel'){
					 	$query  = "update crs_hotel_details set displaytophotel_status='$publish_status' where id='$hotel_id'";
					 }elseif($name == 'homepagecrsApts'){
					 	$query  = "update crs_hotel_details set displayhptop_crs_status='$publish_status' where id='$hotel_id'";
					 }elseif($name == 'Apts'){
					    // echo "tes";die;
					 	$query  = "update crs_hotel_details set displaytopApts_status='$publish_status' where id='$hotel_id'";
					 }elseif($name == 'resort'){
					    // echo "tes";die;
					 	$query  = "update crs_hotel_details set displaytopResort_status='$publish_status' where id='$hotel_id'";
					 }elseif($name == 'cabin'){
					    // echo "tes";die;
					 	$query  = "update crs_hotel_details set displaytopCabin_status='$publish_status' where id='$hotel_id'";
					 }
			 
			 		//echo $query; exit;
					 $return = $this->hotel_model->query_run($query);
					 if($return)
					 {
					  $message['sec'][]= "Thanks! Changes has been made.";
					}else{
					  $message['sec'][]= "Sorry|| some techinal .";
					}
				}else{
					$message['sec'][]= "Sorry! Please enter room price.";
				}

		}else{
		$message['sec'][]= "Sorry! Hotel is INACTIVE.";
	}
}else{
	$message['sec'][]= "Sorry! Image is empty.";
}
//}
echo json_encode($message); exit(); 
}

}