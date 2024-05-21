<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
ini_set('max_execution_time', 300);
/**
 *
 * @package    Provab
 * @subpackage Flight
 * @author     Arjun J<arjunjgowda260389@gmail.com>
 * @version    V1
 */

class Flight_crs extends CI_Controller {
	private $current_module;
	public function __construct()
	{
		parent::__construct();
		//$this->output->enable_profiler(TRUE);
		$this->load->model('flight_model');
		$this->load->model('flight_crs_model');
		$this->load->model('domain_management_model');
		$this->load->library('provab_mailer');
		$this->current_module = $this->config->item('current_module');
	}

 function add_airline()
	{
		$data = $this->input->post();
		if(!empty($data)){
			// FILE UPLOAD
				$image_data = array();
				// debug($_FILES);
				if (valid_array ( $_FILES ) == true and $_FILES ['image'] ['error'] == 0 and $_FILES ['image'] ['size'] > 0) {
					$img_name = 'Airline_logo-'.time();
					
					$config ['upload_path'] = $this->template->domain_image_upload_path ();
					$temp_file_name = $_FILES ['image'] ['name'];
					$config['allowed_types'] = 'gif|jpg|png|jpeg';
					$config ['file_name'] ='IMG-'.$img_name;
					$config['max_size'] = MAX_DOMAIN_LOGO_SIZE;
					$config['max_width']  = MAX_DOMAIN_LOGO_WIDTH;
					$config['max_height']  = MAX_DOMAIN_LOGO_HEIGHT;
					$config ['remove_spaces'] = false;
					// UPLOAD IMAGE
					$this->load->library ( 'upload', $config );
					$this->upload->initialize ( $config );
					if (! $this->upload->do_upload ( 'image' )) {
						echo $this->upload->display_errors ();
					} else {
						$image_data = $this->upload->data ();
					}
				}
				$data['image'] = (empty($image_data ['file_name']) == false ? $image_data ['file_name'] : '');
// debug($data);
// debug($this->template->domain_image_upload_path ());
			if($data['origin'] != 0){
				$this->custom_db->update_record('flight_crs_airline_list', $data, array('origin' => $data['origin']) );
			} else {
			    unset($data['origin']);
				$this->custom_db->insert_record('flight_crs_airline_list', $data);				
			}
		}
		redirect(base_url().'index.php/flight_crs/flight_crs_airline_list');
	}

	function delete_airline($origin){
		$cond['origin'] = $origin;
		$result = $this->custom_db->delete_record('flight_crs_airline_list', $cond);
		echo json_encode(array('status'=> true));exit;
	}



    function flight_crs_airline_list(){
		$data =  $this->custom_db->single_table_records('flight_crs_airline_list');
		// debug($data);
		if($data['status']) {
			$page_data['airline_list'] = $data['data'];			
		} else {
			$page_data['airline_list'] = array();
		}
		$this->template->view('flight_crs/flight_crs_airline_list', $page_data);
	}

	/*For flight crs airport list */
	function add_airport()
	{
		$data = $this->input->post();
		if(!empty($data)){
			if($data['origin'] != 0){
				$this->custom_db->update_record('flight_crs_airport_list', $data, array('origin' => $data['origin']) );
			} else {
			    unset($data['origin']);
				$this->custom_db->insert_record('flight_crs_airport_list', $data);				
			}
		}
		redirect(base_url().'index.php/flight_crs/flight_crs_airport_list');
	}
	function delete_airport($origin){
		$cond['origin'] = $origin;
		$result = $this->custom_db->delete_record('flight_crs_airport_list', $cond);
		echo json_encode(array('status'=> true));exit;
	}



    function flight_crs_airport_list(){
		$data =  $this->custom_db->single_table_records('flight_crs_airport_list');
		// debug($data);
		if($data['status']) {
			$page_data['airport_list'] = $data['data'];			
		} else {
			$page_data['airport_list'] = array();
		}
		$this->template->view('flight_crs/flight_crs_airport_list', $page_data);
	}

	/*END*/
	function get_booking_details($app_reference)
	{
		//
		$condition[] = array('BD.app_reference', '=', $this->db->escape($app_reference));
		$details = $this->flight_crs_model->get_booking_details($app_reference);
		if ($details['status'] == SUCCESS_STATUS) {
			$booking_source = $details['data']['booking_details']['booking_source'];
			load_flight_lib($booking_source);
			$this->flight_lib->get_booking_details($details['data']['booking_details'], $details['data']['booking_transaction_details']);
		}
	}
	/**
	 * Cancellation
	 * Jaganath
	 */
	function pre_cancellation($app_reference, $booking_source)
	{
		if (empty($app_reference) == false && empty($booking_source) == false) {
			$page_data = array();
			$booking_details = $this->flight_crs_model->get_booking_details($app_reference, $booking_source);
			if ($booking_details['status'] == SUCCESS_STATUS) {
				$this->load->library('booking_data_formatter');
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_flight_booking_data($booking_details,$this->current_module);
				$page_data['data'] = $assembled_booking_details['data'];
				$this->template->view('flight_crs/pre_cancellation', $page_data);
			} else {
				redirect('security/log_event?event=Invalid Details');
			}
		} else {
			redirect('security/log_event?event=Invalid Details');
		}
	}
	/**
	 * Jaganath
	 * @param $app_reference
	 */
	function cancel_booking()
	{
		$post_data = $this->input->post();
		if (isset($post_data['app_reference']) == true && isset($post_data['booking_source']) == true && isset($post_data['transaction_origin']) == true &&
			valid_array($post_data['transaction_origin']) == true && isset($post_data['passenger_origin']) == true && valid_array($post_data['passenger_origin']) == true) {
			$app_reference = trim($post_data['app_reference']);
			$booking_source = trim($post_data['booking_source']);
			$transaction_origin = $post_data['transaction_origin'];
			$passenger_origin = $post_data['passenger_origin'];
			$booking_details = $GLOBALS['CI']->flight_crs_model->get_booking_details($app_reference, $booking_source);
                        
			if ($booking_details['status'] == SUCCESS_STATUS) {
				load_flight_lib($booking_source);
				//Formatting the Data
				$this->load->library('booking_data_formatter');
				$booking_details = $this->booking_data_formatter->format_flight_booking_data($booking_details, $this->current_module);
				$booking_details = $booking_details['data'];
                                
				//Grouping the Passenger Ticket Ids
				$grouped_passenger_ticket_details = $this->flight_lib->group_cancellation_passenger_ticket_id($booking_details, $passenger_origin);
                                
				$passenger_origin = $grouped_passenger_ticket_details['passenger_origin'];
				$passenger_ticket_id = $grouped_passenger_ticket_details['passenger_ticket_id'];
                                
				$cancellation_details = $this->flight_lib->cancel_booking($booking_details, $passenger_origin, $passenger_ticket_id);
				
				redirect('flight_crs/cancellation_details/'.$app_reference.'/'.$booking_source.'/'.$cancellation_details['status']);
			} else {
				redirect('security/log_event?event=Invalid Details');
			}
		} else {
			redirect('security/log_event?event=Invalid Details');
		}
	}
	/**
	 *
	 * @param $app_reference
	 * @param $booking_source
	 */
	function cancellation_details($app_reference, $booking_source, $cancellation_status)
	{
		if (empty($app_reference) == false && empty($booking_source) == false) {
			$master_booking_details = $GLOBALS['CI']->flight_crs_model->get_booking_details($app_reference, $booking_source);
			if ($master_booking_details['status'] == SUCCESS_STATUS) {
				$page_data = array();
				$this->load->library('booking_data_formatter');
				$master_booking_details = $this->booking_data_formatter->format_flight_booking_data($master_booking_details, 'b2c');
                                
				$page_data['data'] = $master_booking_details['data'];
				$page_data['cancellation_status'] = $cancellation_status;
				$this->template->view('flight/cancellation_details', $page_data);
			} else {
				redirect('security/log_event?event=Invalid Details');
			}
		} else {
			redirect('security/log_event?event=Invalid Details');
		}

	}
	/**
	 * Jaganath
	 * Get supplier cancellation status
	 */
	public function update_supplier_cancellation_status_details()
	{
		$get_data = $this->input->get();
		if(isset($get_data['app_reference']) == true && isset($get_data['booking_source']) == true && isset($get_data['passenger_status']) == true && $get_data['passenger_status'] == 'BOOKING_CANCELLED' && isset($get_data['passenger_origin']) == true && intval($get_data['passenger_origin']) > 0){
			$app_reference = trim($get_data['app_reference']);
			$booking_source = trim($get_data['booking_source']);
			$passenger_origin = trim($get_data['passenger_origin']);
			$passenger_status = trim($get_data['passenger_status']);
			$booking_details = $this->flight_crs_model->get_passenger_ticket_info($app_reference, $passenger_origin, $passenger_status);
			if($booking_details['status'] == SUCCESS_STATUS){
				$master_booking_details = $booking_details['data']['booking_details'][0];
				$booking_customer_details = $booking_details['data']['booking_customer_details'][0];
				$cancellation_details = $booking_details['data']['cancellation_details'][0];
				$booking_source = $master_booking_details['booking_source'];
				$request_data = array();
				$request_data['AppReference'] = 		$booking_customer_details['app_reference'];
				$request_data['SequenceNumber'] =		$booking_customer_details['sequence_number'];
				$request_data['BookingId'] = 			$booking_customer_details['book_id'];
				$request_data['PNR'] = 					$booking_customer_details['pnr'];
				$request_data['TicketId'] = 			$booking_customer_details['TicketId'];
				$request_data['ChangeRequestId'] =	$cancellation_details['RequestId'];
				load_flight_lib($booking_source);
				$supplier_ticket_refund_details = $this->flight_lib->get_supplier_ticket_refund_details($request_data);
				if($supplier_ticket_refund_details['status'] == SUCCESS_STATUS){
					$this->flight_crs_model->update_supplier_ticket_refund_details($passenger_origin, $supplier_ticket_refund_details['data']);
				}
			}
		}
	}
	/**
	 * Jaganath
	 * Displays Cancellation Ticket Details
	 */
	public function ticket_cancellation_details()
	{
		$get_data = $this->input->get();
		if(isset($get_data['app_reference']) == true && isset($get_data['booking_source']) == true && isset($get_data['status']) == true){
			$app_reference = trim($get_data['app_reference']);
			$booking_source = trim($get_data['booking_source']);
			$status = trim($get_data['status']);
			$booking_details = $this->flight_crs_model->get_booking_details($app_reference, $booking_source, $status);
			if($booking_details['status'] == SUCCESS_STATUS){
				$this->load->library('booking_data_formatter');
				$booking_details = $this->booking_data_formatter->format_flight_booking_data($booking_details, $this->config->item('current_module'));
				$page_data = array();
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
				$page_data['booking_data'] = $booking_details['data'];
//debug($page_data['booking_data']); die;
				$page_data['booked_user_details'] =	$booked_user_details;
				$page_data['is_agent'] = 			$is_agent;
				$this->template->view('flight_crs/ticket_cancellation_details', $page_data);
			} else {
				redirect(base_url());
			}
		} else {
			redirect(base_url());
		}
	}
	/**
	 * Jaganath
	 * Displays Ticket cancellation Refund details
	 */
	public function cancellation_refund_details()
	{
		$get_data = $this->input->get();
		if(isset($get_data['app_reference']) == true && isset($get_data['booking_source']) == true && isset($get_data['passenger_status']) == true && $get_data['passenger_status'] == 'BOOKING_CANCELLED' && isset($get_data['passenger_origin']) == true && intval($get_data['passenger_origin']) > 0){
			$app_reference = trim($get_data['app_reference']);
			$booking_source = trim($get_data['booking_source']);
			$passenger_origin = trim($get_data['passenger_origin']);
			$passenger_status = trim($get_data['passenger_status']);
			$booking_details = $this->flight_crs_model->get_passenger_ticket_info($app_reference, $passenger_origin, $passenger_status);
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
				$page_data['booking_data'] = $booking_details['data'];
				$page_data['booked_user_details'] =	$booked_user_details;
				$page_data['is_agent'] = 			$is_agent;
				$this->template->view('flight_crs/cancellation_refund_details', $page_data);
			} else {
				redirect(base_url());
			}
		} else {
			redirect(base_url());
		}
	}
	/**
	 * Jaganath
	 * Update Ticket Refund Details
	 */
	public function update_ticket_refund_details()
	{
		$post_data = $this->input->post();
		$redirect_url_params = array();
		$this->form_validation->set_rules('app_reference', 'app_reference', 'trim|required|xss_clean');
		$this->form_validation->set_rules('passenger_origin', 'passenger_origin', 'trim|required|min_length[1]|numeric');
		$this->form_validation->set_rules('passenger_status', 'passenger_status', 'trim|required|xss_clean');
		$this->form_validation->set_rules('refund_payment_mode', 'refund_payment_mode', 'trim|required|xss_clean');
		$this->form_validation->set_rules('refund_amount', 'refund_amount', 'trim|numeric');
		$this->form_validation->set_rules('cancellation_charge', 'cancellation_charge', 'trim|numeric');
		$this->form_validation->set_rules('service_tax_on_refund_amount', 'service_tax_on_refund_amount', 'trim|numeric');
		$this->form_validation->set_rules('swachh_bharat_cess', 'swachh_bharat_cess', 'trim|numeric');
		$this->form_validation->set_rules('refund_status', 'refund_status', 'trim|required|xss_clean');
		$this->form_validation->set_rules('refund_comments', 'UserId', 'trim|required');
		if ($this->form_validation->run()) {
			$app_reference = 				trim($post_data['app_reference']);
			$passenger_origin = 			intval($post_data['passenger_origin']);
			$passenger_status = 			trim($post_data['passenger_status']);
			$refund_payment_mode = 			trim($post_data['refund_payment_mode']);
			$refund_amount = 				floatval($post_data['refund_amount']);
			$cancellation_charge = 			floatval($post_data['cancellation_charge']);
			$service_tax_on_refund_amount =	floatval($post_data['service_tax_on_refund_amount']);
			$swachh_bharat_cess = 			floatval($post_data['swachh_bharat_cess']);
			$refund_status = 				trim($post_data['refund_status']);
			$refund_comments = 				trim($post_data['refund_comments']);
			//Get Ticket Details
			$booking_details = $this->flight_crs_model->get_passenger_ticket_info($app_reference, $passenger_origin, $passenger_status);
			if($booking_details['status'] == SUCCESS_STATUS){
				$master_booking_details = $booking_details['data']['booking_details'][0];
				$booking_customer_details = $booking_details['data']['booking_customer_details'][0];
				$cancellation_details = $booking_details['data']['cancellation_details'][0];
				$booking_currency = $master_booking_details['currency'];//booking currency
				$booked_user_id = intval($master_booking_details['created_by_id']);
				$user_condition[] = array('U.user_id' ,'=', $booked_user_id);
				$booked_user_details = $this->user_model->get_user_details($user_condition);
				$is_agent = false;
				if(valid_array($booked_user_details) == true && $booked_user_details[0]['user_type'] == B2B_USER){
					$is_agent = true;
				}
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
					$remarks = 'flight Refund was Successfully done';
					$this->domain_management_model->save_transaction_details('flight', $app_reference, $fare, $domain_markup, $level_one_markup, $remarks, $convinence, $discount, $booking_currency, $currency_conversion_rate, $booked_user_id);

					//update agent balance
					$this->domain_management_model->update_agent_balance($agent_refund_amount, $booked_user_id);
				}
				//UPDATE THE REFUND DETAILS
				//Update Condition
				$update_refund_condition = array();
				$update_refund_condition['passenger_fk'] =	$passenger_origin;
				//Update Data
				$update_refund_details = array();
				$update_refund_details['refund_payment_mode'] = 			$refund_payment_mode;
				$update_refund_details['refund_amount'] =					$refund_amount;
				$update_refund_details['cancellation_charge'] = 			$cancellation_charge;
				$update_refund_details['service_tax_on_refund_amount'] =	$service_tax_on_refund_amount;
				$update_refund_details['swachh_bharat_cess'] = 				$swachh_bharat_cess;
				$update_refund_details['refund_status'] = 					$refund_status;
				$update_refund_details['refund_comments'] = 				$refund_comments;
				$update_refund_details['currency'] = 						$booking_currency;
				$update_refund_details['currency_conversion_rate'] = 		$currency_conversion_rate;
				if($refund_status == 'PROCESSED'){
					$update_refund_details['refund_date'] = 				date('Y-m-d H:i:s');
				}
				$this->custom_db->update_record('flight_cancellation_details', $update_refund_details, $update_refund_condition);
				
				$redirect_url_params['app_reference'] = $app_reference;
				$redirect_url_params['booking_source'] = $master_booking_details['booking_source'];
				$redirect_url_params['passenger_status'] = $passenger_status;
				$redirect_url_params['passenger_origin'] = $passenger_origin;
			}
		}
		redirect('flight_crs/cancellation_refund_details?'.http_build_query($redirect_url_params));
	}
	/** 
	 ** Issue hold ticket 
	 **	Jeevanandam K
	**/
	function run_ticketing_method($app_reference,$booking_source)
	{	
		$response ['data'] = array ();
		$response ['Status'] = FAILURE_STATUS;
		$response ['Message'] = '';	

		load_flight_lib($booking_source);
		$this->load->library('booking_data_formatter');
		$token_detail = $GLOBALS['CI']->custom_db->single_table_records('flight_booking_transaction_details','*',array('app_reference'=>$app_reference,'status'=>"BOOKING_HOLD"));

		if(valid_array($token_detail) && $token_detail['status'] == SUCCESS_STATUS)
		{
			$token_details = $token_detail['data']['0'];
			if($token_details['hold_ticket_req_status'] == INACTIVE )
			{
				$sequence_number = $token_details['sequence_number'];
				$pnr = $token_details['pnr'];
				$booking_id = $token_details['book_id'];

				$booked_user_details = $this->flight_crs_model->get_booked_user_details($app_reference);
				if($booked_user_details[0]['user_type'] == B2B_USER){
					$agent_id = $booked_user_details[0]['created_by_id'];
					$agent_details = $this->domain_management_model->get_agent_details($agent_id);
					
					$page_data['agent_details'] = $agent_details;
					$agent_base_currency = $agent_details['agent_base_currency'];
					
					$currency_obj = new Currency();
					$currency_conversion_rate = $currency_obj->getConversionRate(false, get_application_default_currency(), $agent_base_currency);//Currency conversion rate of the domain currency
									
					
					if(valid_array($agent_details) == false){//Invalid Agent ID
						redirect(base_url());
					}
					
					$page_data['agent_id'] = $agent_id;
					$amount = $this->booking_data_formatter->agent_buying_price($token_detail['data']);
					$post_data['amount'] = -abs($amount[0]);
					
					$debit_amount = ($post_data['amount']*$currency_conversion_rate);					
					
					
					$post_data['app_reference'] = $app_reference;
					$post_data['agent_list_fk'] = $agent_id;
					$post_data['remarks'] = "Flight transaction successfully done";
					$post_data['amount'] = $debit_amount;
					$post_data['currency'] = $agent_details['agent_base_currency'];
					$post_data['currency_conversion_rate'] = $currency_conversion_rate;
					$post_data['issued_for'] = 'Debited Towards: Flight ';
					$this->domain_management_model->process_direct_credit_debit_transaction($post_data);
					
					//Update Issue Hold Ticket Status In Booking Transaction Details
					$update_issue_ticket_req_status = $this->custom_db->update_record('flight_booking_transaction_details',array('hold_ticket_req_status'=>ACTIVE),array('app_reference'=>$app_reference,'pnr' => $pnr));

					$ticket_response = $this->flight_lib->issue_hold_ticket($app_reference,$sequence_number,$pnr,$booking_id);

					if($ticket_response['status'] == SUCCESS_STATUS)
					{

						$response['Status'] = SUCCESS_STATUS;
						$response['Message'] = "Request Sent Successfully !!";
					}else{
						$response['Status'] = FAILURE_STATUS;
						$response['Message'] = "Failed to send request !!";	
					}
				}else{
					$response['Status'] = FAILURE_STATUS;
					$response['Message'] = "Booking Details Not Found !!";
				}
			}else{
				$response['Status'] = FAILURE_STATUS;
				$response['Message'] = "Request Already Sent !!";
			}
			
		}else{
			$response['Status'] = FAILURE_STATUS;
			$response['Message'] = "Booking Details Not Found !!";
		}

		
		echo json_encode($response);
	}
	/**
	 * Arjun J Gowda
	 */
	function exception()
	{
		$module = META_AIRLINE_COURSE;
		$op = @$_GET['op'];
		$notification = @$_GET['notification'];
		$eid = $this->module_model->log_exception($module, $op, $notification);
		//set ip log session before redirection
		$this->session->set_flashdata(array('log_ip_info' => true));
		redirect(base_url().'index.php/flight_crs/event_logger/'.$eid);
	}

	function event_logger($eid='')
	{
		$log_ip_info = $this->session->flashdata('log_ip_info');
		$this->template->view('flight_crs/exception', array('log_ip_info' => $log_ip_info, 'eid' => $eid));
	}

	////////// Added By Jagannath
	function add_flight(){
		error_reporting(E_ALL);
		//debug('ok');exit()
		$page_data = array();
		//if($_SERVER['REMOTE_ADDR']=="192.168.0.40"){			
			// $page_data['airport_list_l'] = $this->flight_crs_model->get_airport_list_for_crs();
			$page_data['airport_list_l'] = array();
			$page_data['airline_list']   = $this->flight_crs_model->get_airline_list('','');
			//debug($page_data);
			//debug($__airline);
			//exit;
		//}
		$this->template->view( 'flight_crs/add_flight', $page_data );
	}

	function flight_list(){
		//debug('ok');exit();
		$get_data = $this->input->get();
		//debug($get_data);exit();

		if(!empty($get_data['dep_origin']) && isset($get_data['dep_origin'])){
			$search_data['dep_origin'] = $this->getAirportCode($get_data['dep_origin']);
		}
		if(!empty($get_data['arival_origin']) && isset($get_data['arival_origin'])){
			$search_data['arrival_origin'] = $this->getAirportCode($get_data['arival_origin']);
		}

		if(!empty($get_data['month']) && isset($get_data['month'])){
			$search_data['month'] = $get_data['month'];
		}
		if(!empty($get_data['year']) && isset($get_data['year'])){
			$search_data['year'] = $get_data['year'];
		}
		if($get_data!=""){
		$flight_data['data'] = $this->flight_crs_model->all_flight_list ( $search_data );
	}
	else{
		$flight_data['data'] = $this->flight_crs_model->flight_list ();
	}
	//debug($flight_data);exit();

		$fsid = $this->flight_crs_model->get_booking_count ();
		if(!empty($fsid)){
			$fsids = '';
			foreach($fsid as $f__key => $f__val){
				$fsids .= $f__val['fsid'].','; 
			}
			$fsids = rtrim($fsids,',');
		}
		$flight_data['fsid'] = explode(',',$fsids);
		$this->template->view ( 'flight_crs/flight_list',$flight_data);
	}

	function delete_flight_details($id){
		$flihgt_crs_query = 'DELETE FROM flight_crs_details WHERE fsid = "'.$id.'"';
		$this->db->query($flihgt_crs_query);
		$query = 'DELETE FROM flight_crs_segment_details WHERE fsid = "'.$id.'"';
		$this->db->query($query);
		$query = 'DELETE FROM crs_update_flight_details WHERE fsid = "'.$id.'"';
		$this->db->query($query);
		
		redirect(base_url().'index.php/flight_crs/flight_list');
	}

	function get_flight_status($fsid,$status){
		$result = $this->flight_crs_model->flight_status ($fsid,$status);
//		echo $result;
		echo json_encode(array('state'=>true));
	}

	function flight_fare_rules(){
		$data =  $this->custom_db->single_table_records('flight_crs_fare_rules');
		if($data['status']) {
			$page_data['fare_rule_list'] = $data['data'];
		} else {
			$page_data['fare_rule_list'] = array();
		}
		$this->template->view('flight_crs/flight_fare_rules', $page_data);
	}

	function update_per_date_flight_status($origin,$status,$status_type){

		if(!$this->flight_crs_model->check_pnr_flight_crs($origin)){
			echo json_encode(array('active_state'=>0,'msg'=>'Please update PNR number.'));
			exit;
		}

		$result = $this->flight_crs_model->update_per_date_flight_status ($origin,$status,$status_type);
//		echo $result;
		echo json_encode(array('active_state'=>1,'msg'=>'Please update PNR number.'));
	}

	function get_flight_details($fsid){
		$result = $this->flight_crs_model->flight_details ( $fsid );
		echo json_encode($result); 
	}

	function update_flight_details($id,$filter_data=array())
	{
	    $filter_data=array();
		//$filter_data =  json_decode( base64_decode($filter_data),true); 

		$result['flight_details'] = $this->flight_crs_model->update_flight_details ($id);
		$result['fsid'] = $id;
		$res = $this->flight_crs_model->initial_update_flight_details ($id,$result);
		//$res = $this->flight_model->initial_update_flight_details ($id);

		$fsid_list = implode(',',$res['fsid_list']);
		$result['update_flight_details'] = $this->flight_crs_model->crs_update_flight_details ($fsid_list,$filter_data);
// 		debug($result);exit();
		$this->template->view ( 'flight_crs/update_flight_details',$result);
	}

	function seat_details(){
		
	$data = $this->input->post();

	$con['origin'] = $data['id'];
	check_pnr_flight_crs($data['id']);
	if(!check_pnr_flight_crs($data['id'])){
		echo "<tr>                        
	           <td colspan='12' class='err_msg'>Please update PNR number.</td>
	           </tr>";
		exit;
	}
	$select_date_data = $this->custom_db->single_table_records('crs_update_flight_details','*',$con);

	$condition_for_seat_details = array();
	
	$details = array();
	$details['select_date_data'] = $select_date_data['data'][0];

	$condition_for_seat_details['journey_start'] = $details['select_date_data']['avail_date'];

	$seat_details['data'] = $this->offline_flight_booking_report_per_PNR($details);
	$total_seat = $details['select_date_data']['avail_seat'];
	$str_seat = '';
	$infant_str_seat = '';
	$sl_no = 1;
	$infant_sl_no = 1;

	$bok_seat_count = 0;
	foreach ($seat_details['data'] as $key => $value) {
		$agentcy_name_and_type = '';
		//debug($value); exit;
		if($value['user_type']=='0'){
			$agentcy_name_and_type = $value['agency_first_name'].' '.$value['agency_last_name'].'(Guest)';
		}else if($value['user_type']=='4'){
			$agentcy_name_and_type = $value['agency_first_name'].' '.$value['agency_last_name'].'(B2C)';
		}else{
			$agentcy_name_and_type = $value['agency_name'].'(B2B)';
		}
		$price_details = json_decode($value['attributes'],true);

		/*if(isset($price_details['price_breakup']['pax_per_price']))
		{
			$price = $price_details['price_breakup']['pax_per_price'];
		}else{
			$price = '--';
		}*/
		if(isset($price_details['price_breakup']['pax_per_price']))
		{
			$price = $price_details['price_breakup']['pax_per_price'];
		}else{
			$price = '--';
		}
		$pax_update_btn = '';
		$mail_status_update_btn = '';
		$pxa_class = 'success';
		if(isset($value['mailing_status']) && $value['mailing_status']=='PENDING'){
			$pax_update_btn = '<button type="button" class="btn btn-primary st_book update_passenger_details" data-pax_id='.$value['pax_id'].' data-first_name="'.$value['first_name'].'" data-last_name="'.$value['last_name'].'" data-mailing_status="'.$value['mailing_status'].'" data-title='.$value['user_title_id'].' data-passenger_type='.$value['passenger_type'].' >Update</button>';
			$pxa_class = 'danger';
			$mail_status_update_btn = '<div class="checkbox mail_st col-xs-3 nopad"><label><input type="checkbox"  value='.$value['pax_id'].' class="childCheckBox"></label></div>';
		}
		if($value['passenger_type']=="Adult" || $value['passenger_type']=="Child"){
			$bok_seat_count++;
			$str = '> 12 year';
            if($value['passenger_type']=="Child"){
                $str = '< 12 year';
            
            }
			$str_seat .= ' <tr>                        
	           <td>'.$sl_no++.'</td>
	           <td>'.$value['title'].'</td>
	           <td class="text-uppercase">'.$value['first_name'].'</td>
	           <td class="text-uppercase">'.$value['last_name'].'</td>
	           <td class="text-uppercase">'.$value['passenger_type'].'</td>
	           <td class="text-uppercase">'.$value['app_reference'].'</td>
	           <td class="text-uppercase">'.$value['airline_pnr'].'</td>
	           <td class="text-uppercase">'.$agentcy_name_and_type.'</td>
	           <td class="text-uppercase">'.$str.'</td>
	           <td class="text-uppercase">'.'<span class="label label-success">'.$value['status'].'</span>'.'</td>
	           <td>'.$mail_status_update_btn.'<div class="col-xs-9 nopad m10"><span class="label label-'.$pxa_class.'">'.$value['mailing_status'].'</span></div>'.'</td>
	           <td>'.$pax_update_btn.'</td>
           </tr>';
		}else{
			if($infant_sl_no==1){
				$infant_str_seat .= ' <tr>  
	           <td colspan="12" style="background: #ddd;"><h4 class="infnt_det">Infant Details</h4></td>
	          
           </tr>';
			}
			$infant_str_seat .= ' <tr>                        
	           <td>'.$infant_sl_no++.'</td>
	           <td>'.$value['title'].'</td>
	           <td>'.$value['first_name'].'</td>
	           <td>'.$value['last_name'].'</td>
	           <td>'.$value['passenger_type'].'</td>
	           <td>'.$value['app_reference'].'</td>
	           <td class="text-uppercase">'.$value['airline_pnr'].'</td>
	           <td>'.$agentcy_name_and_type.'</td>
	           <td>'.$value['date_of_birth'].'</td>
	           <td>'.'<span class="label label-success">'.$value['status'].'</span>'.'</td>
	           <td>'.'<div class="checkbox mail_st col-xs-3 nopad "><label><input type="checkbox" value="'.$value['pax_id'].'" name="mailing_status" class="childCheckBox"></label></div><div class="col-xs-9 nopad m10"><span class="label label-'.$pxa_class.'">'.$value['mailing_status'].'</span></div>'.'</td>
	           <td>'.$pax_update_btn.'</td>
           </tr>';
		}
		
		
           //<a class="btn btn-primary st_book" href="#">Send Mail</a>
	}
/*	echo date('d-m-Y',strtotime($details['select_date_data']['avail_date']));
//echo strtotime($details['select_date_data']['avail_date']);
echo date('d_m_Y');

if(date('d-m-Y',strtotime($details['select_date_data']['avail_date']))>=(date('d-m-Y'))){
	           echo "dfdfdf";
	           }
exit;*/

	for($s=0;$s<$total_seat-$bok_seat_count;$s++){
		
		$str_seat .= ' <tr>                        
	           <td>'.$sl_no++.'</td>
	           <td>--</td>
	           <td>--</td>
	           <td>--</td>
	           <td>--</td>
	           <td>--</td>
	           <td>--</td>
	           <td>--</td>
	           <td>--</td>
	           <td>--</td>
	           <td>--</td>
	           <td>
	           ';
	         /*   debug(get_date_difference(date('d-m-Y',strtotime($details['select_date_data']['avail_date'])),(date('d-m-Y'))); exit;*/
	          if(strtotime($details['select_date_data']['avail_date'])>=strtotime(date('d-m-Y'))){
	           //	$str_seat .= '<a class="btn btn-primary st_book" href="'.base_url().'index.php/flight_crs/offline_flight_book/'.$con['origin'].'">Book</a>
	           //';
	           }
	           /*
	           if(date('d-m-Y',strtotime($details['select_date_data']['avail_date']))>=(date('d-m-Y'))){
	           	$str_seat .= '<a class="btn btn-primary st_book" href="'.base_url().'index.php/flight/offline_flight_book/'.$con['origin'].'">Book</a>
	           ';
	           }
	           */
	           $str_seat .= '
	           </td>
           </tr>';

	}
	echo $str_seat.$infant_str_seat;
	}

	//Dinesh

	function update_passenger_details($origin=""){
		$data = $this->input->post();
		//debug($data);exit;
		$passenger_update_result = $this->flight_crs_model->update_passenger_details($origin,$data);
		/*if ($passenger_update_result == true) {
			echo "Successfully Updated";
		} else {
			echo "Sorry,Something Went Wrong";
		}*/
		echo '1';
	}
	//Dinesh End



	function offline_flight_booking_report_per_PNR($details)
	{
		$origin = $details['select_date_data']['origin'];
		//debug($origin);exit;
		$query = 'select FBID.airline_pnr,FPD.title,FPD.origin as pax_id, FPD.first_name ,FPD.last_name,FPD.passenger_type,FPD.status,U.agency_name,U.user_type,U.first_name as agency_first_name,U.last_name as agency_last_name,FPD.app_reference,FPD.passenger_type,FPD.gender,FPD.date_of_birth,FPD.mailing_status,FPD.attributes,FPD.title AS user_title_id
				  from crs_update_flight_details CFD
				  		join flight_crs_booking_details CFBD on(CFD.origin = CFBD.fudid)
				  		join flight_booking_passenger_details FPD on (CFBD.app_reference = FPD.app_reference)
				  		join flight_booking_itinerary_details FBID on (CFBD.app_reference = FBID.app_reference)
				  		join user U on(CFBD.agent_id = U.user_id)
				  	where CFD.origin='.$origin;
		if(isset($details['gender']) and $details['gender']=='Infant'){

			$query .=' AND FPD.passenger_type!="Infant"';
		}
				//  	debug($query);exit;
		$res = $this->db->query($query)->result_array();
		return $res;
	}

private function format_basic_search_filters($module='',$get_data)
	{
		//$get_data = $condition_for_seat_details;


		if(valid_array($get_data) == true) {
			$filter_condition = array();
			//From-Date and To-Date
			$from_date = trim(@$get_data['journey_start']);
		
			if(empty($from_date) == false) {
				$filter_condition[] = array('BD.journey_start', ' like ', $this->db->escape('%'.date('Y-m-d', strtotime($from_date))).'%');
			}
		
			if (empty($get_data['status']) == false && strtolower($get_data['status']) != 'all') {
				$filter_condition[] = array('BD.status', '=', $this->db->escape($get_data['status']));
			}
		
			if (empty($get_data['pnr']) == false) {
				$filter_condition[] = array('BT.pnr', ' like ', $this->db->escape('%'.$get_data['pnr'].'%'));
			}
			
	
			if (empty($get_data['app_reference']) == false) {
				$filter_condition[] = array('BD.app_reference', ' like ', $this->db->escape('%'.$get_data['app_reference'].'%'));
			}
			
			return array('filter_condition' => $filter_condition);
		}
	}


	// function test()
	// {
	// 	$this->template->view ('flight/test');	
	// }

	function save_crs_flight_details(){
		$data = $this->input->post();
		// debug($data);exit;
		$strType = $data['is_triptype'];
		$strCnt  = (($strType==1)?2:1);
		// debug($strCnt);exit();
		$strFSId = 0;
		for($i=0; $i<$strCnt; $i++){
			$arrInsData = array();
			$strIsDomestic = $data['is_domestic'];
			$strIsTripType = $data['is_triptype'];
			$arrInsData['is_domestic'] 	= $strIsDomestic;
			$arrInsData['is_triptype'] 	= $strIsTripType;	
			//debug($i);exit();
			if($i>0){
				//echo "string";exit();

				if(isset($data['no_of_stop_1']) && $data['no_of_stop_1'] > -1){
					$arrInsData['no_of_stop']  		= $data['no_of_stop_1'];
					$arrInsData['origin']  			= $data['origin_1'];
					$arrInsData['destination']  	= $data['destination_1'];
					$arrInsData['arr_date']  		= $data['arr_date_1'];
					$arrInsData['dep_date']  		= $data['dep_date_1'];
					$arrInsData['departure_time']   = $data['departure_time_1'];
					$arrInsData['arrival_time']     = $data['arrival_time_1'];
					$arrInsData['flight_num']     	= $data['flight_num_1'];
					$arrInsData['carrier_code']     = $data['carrier_code_1'];
					$arrInsData['class_type']     	= $data['class_type_1'];
					$arrInsData['fare_rule']     	= $data['fare_rule_1'];
					$arrInsData['trip_type']     	= $i;				
				}
			}else{
				$arrInsData['no_of_stop']  	= $data['no_of_stop'];
				$arrInsData['origin']  	= $data['origin'];
				$arrInsData['destination']  	= $data['destination'];
				$arrInsData['arr_date']  	= $data['arr_date'];
				$arrInsData['dep_date']  	= $data['dep_date'];
				$arrInsData['departure_time']  = $data['departure_time'];
				$arrInsData['arrival_time']    = $data['arrival_time'];
				$arrInsData['flight_num']      = $data['flight_num'];
				$arrInsData['carrier_code']    = $data['carrier_code'];
				$arrInsData['class_type']      = $data['class_type'];
				$arrInsData['fare_rule']     	= $data['fare_rule'];
				$arrInsData['seats']     	= $data['seats'];
				$arrInsData['baggage']     	= $data['baggage'];
				$arrInsData['checkin_baggage'] = $data['checkin_baggage'];
				$arrInsData['meals']     	= $data['meals'];
				$arrInsData['extra']     	= $data['extra'];
				$arrInsData['dep_terminal'] = $data['dep_terminal'];
				$arrInsData['arr_terminal'] = $data['arr_terminal'];
				

				if(isset($data['pnr'])){
					$arrInsData['pnr']     			= $data['pnr'];
				}else{
					$arrInsData['pnr']     			= '';
				}
				
				$arrInsData['adult_basefare']      = $data['adult_basefare'];
				$arrInsData['adult_selling_fare']  = $data['adult_selling_fare'];
				$arrInsData['adult_tax']     	   = $data['adult_tax'];
				$arrInsData['child_basefare']      = $data['child_basefare'];
				$arrInsData['child_selling_fare']  = $data['child_selling_fare'];
				$arrInsData['child_tax']     	   = $data['child_tax'];
				$arrInsData['infant_basefare']     = $data['infant_basefare'];
			    	$arrInsData['infant_selling_fare']     = $data['infant_selling_fare'];
				$arrInsData['infant_tax']          = $data['infant_tax'];
				$arrInsData['trip_type']     	   = 0;
			}
			if($strIsDomestic == 1 && $strIsTripType == 1){
				$arrInsData['arrival_date']     	= $data['arr_date_1'];
			}
			//echo "<br>--------<br/>";
			$strFSId = $this->save_crs_flight_details_ins($arrInsData,$strFSId);
		}
		//die("end");
		redirect ( base_url () . 'flight_crs/flight_list/');
	}
function save_crs_flight_details_bk(){
		$data = $this->input->post();
		 //debug($data);exit();
		$strIsDomestic = $data['is_domestic'];
		$strIsTripType = $data['is_triptype'];
		$stop=$data['no_of_stop'];
		  $origin=count($data['origin']);
		 	if(isset($data['pnr'])){
					$pnr    			= $data['pnr'];
				}else{
					$pnr     			= '';
				}
		  for ($i=0; $i <$origin ; $i++) { 
		  	$page_data = array(

		  		'origin' => $data['origin'][$i],
		  		'destination' => $data['destination'][$i],
		  		'arr_date' => $data['arr_date'][$i],
		  		'dep_date' => $data['dep_date'][$i],
		  		'departure_time' => $data['departure_time'][$i],
		  		'arrival_time' => $data['arrival_time'][$i],
		  		'flight_num' => $data['flight_num'][$i],
		  		'carrier_code' => $data['carrier_code'][$i],
		  		'class_type' => $data['class_type'][$i],
		  		'fare_rule' => $data['fare_rule'][$i],
		  		'origin' => $data['origin'][$i],

		  		 );


		  }

	}
	function save_crs_flight_details_ins($data_ins,$strFSId){
		$flight_segment_details = array();
		$data = $data_ins;
		//debug($data_ins);exit;
		$temp_carrier_code = explode ( '(', $data['carrier_code'][0]);
		$carrier_code = trim ( $temp_carrier_code[1] );
		if (isset ( $carrier_code) == true) {
			$carrier_code = trim ( $carrier_code, '() ' );
		} else {
			$carrier_code = '';
		}
		
		$alrline_name = trim ( $temp_carrier_code[0] );
		if (isset ( $alrline_name) == true) {
			$alrline_name = trim ( $alrline_name, '() ' );
		} else {
			$alrline_name = $carrier_code;
		}
		//debug($data['origin']);
		$total_flight = count($data['origin']);
		$flight_segment_details['is_domestic'] 	= $data['is_domestic'];
		$flight_segment_details['origin'] 		= $this->getAirportCode($data['origin'][0]);
		$strDestination = !empty($data['destination'][$total_flight-1])?$data['destination'][$total_flight-1]:$data['destination'][0];
		$flight_segment_details['destination'] 	= $this->getAirportCode($strDestination);
		//debug($data['destination']);
		//exit;
		for($r=0;$r<count($data['departure_time']);$r++){
			$data['departure_time'][$r] = implode(':',explode(' : ',$data['departure_time'][$r]));
			$data['arrival_time'][$r] 	= implode(':',explode(' : ',$data['arrival_time'][$r]));
		}
		/*$data['departure_time'][0] = implode(':',explode(' : ',$data['departure_time'][0]));
		$data['arrival_time'][$total_flight-1] = implode(':',explode(' : ',$data['arrival_time'][$total_flight-1]));
		*/
		$departure_dt = $data['dep_date'][0].' '.$data['departure_time'][0];
		
		$arrival_dt   = $data['arr_date'][$total_flight-1].' '.$data['arrival_time'][$total_flight-1];
		
		$flight_segment_details['dep_from_date'] 	= date('Y-m-d',strtotime($data['dep_date'][0]));
		
		if(isset($data['arr_date'])){
			$flight_segment_details['dep_to_date'] 		= date('Y-m-d',strtotime($data['arr_date'][0]));
		}else{
			$flight_segment_details['dep_to_date'] 		= date('Y-m-d',strtotime($data['arr_date'][$total_flight-1]));
		}
		/*debug($data['arrival_time']); 
		debug($total_flight);
		exit;*/
		$flight_segment_details['departure_time'] 	= date('H:i',strtotime($data['departure_time'][0]));
		$flight_segment_details['arrival_time'] 	= !empty($data['arrival_time'][$total_flight-1])?date('H:i',strtotime($data['arrival_time'][$total_flight-1])):date('H:i',strtotime($data['arrival_time'][0]));
		// date('H:i',strtotime($data['arrival_time'][$total_flight-1]))
		 
		$flight_segment_details['flight_num'] 		= $data['flight_num'][0];
		$flight_segment_details['carrier_code'] 	= $carrier_code;
		$flight_segment_details['airline_name'] 	= $alrline_name;
		
		$flight_segment_details['class_type'] 		= $data['class_type'][0];
		//$flight_segment_details['actual_basefare'] = $data['actual_basefare'];
		$flight_segment_details['adult_basefare'] 	= $data['adult_basefare'];
		$flight_segment_details['adult_selling_fare'] 	= $data['adult_selling_fare'];
		$flight_segment_details['adult_tax'] 		= $data['adult_tax'];
		$flight_segment_details['child_basefare'] 	= $data['child_basefare'];
		$flight_segment_details['child_selling_fare'] 	= $data['child_selling_fare'];
		$flight_segment_details['child_tax'] 		= $data['child_tax'];
		$flight_segment_details['infant_basefare'] 	= $data['infant_basefare'];
		$flight_segment_details['infant_selling_fare'] 	= $data['infant_selling_fare'];
		$flight_segment_details['infant_tax'] 		= $data['infant_tax'];
		$flight_segment_details['fare_rules'] 		= $data['fare_rule'][0];
		$flight_segment_details['trip_type']  		= $data['is_triptype'];
		$flight_segment_details['baggage']  		= $data['baggage'][0];
		$flight_segment_details['checkin_baggage']  		= $data['checkin_baggage'][0];
		$flight_segment_details['meals']  		= $data['meals'][0];
		$flight_segment_details['extra']  		= $data['extra'][0];
		$flight_segment_details['dep_terminal'] = $data['dep_terminal'][0];
		$flight_segment_details['arr_terminal'] = $data['arr_terminal'][0];
		
		$flight_segment_details['crs_currency'] = 'AED';
		$flight_segment_details['no_of_stops'] 	= $data['no_of_stop'];
		
		$flight_segment_details['seats'] 		= $data['seats'];
		$flight_segment_details['pnr'] 		= $data['pnr'];
		$flight_segment_details['active'] 		= '0';
		$flight_segment_details['update_time'] 	= date('Y-m-d H:i:s');
		// debug($flight_segment_details);exit;
		 // if ($_SERVER['REMOTE_ADDR'] == '42.109.146.219')
   //                      { 
   //                          debug($flight_segment_details);exit();
                             

   //                      } 
		if($strFSId == 0){
			$flight_segment = $this->custom_db->insert_record( 'flight_crs_segment_details', $flight_segment_details);
			$fsid = $flight_segment['insert_id'];
		}else{
			$fsid = $strFSId;
		}
		//echo $this->db->last_query();exit;
		for($i=0;$i<$total_flight;$i++){
			if(!empty($data['origin'][$i])){

				$temp_carrier_code_1 = explode ( '(', $data['carrier_code'][$i]);
				$carrier_code_1      = trim ( $temp_carrier_code_1[1] );
				if (isset ( $carrier_code_1) == true) {
					$carrier_code_1 = trim ( $carrier_code_1, '() ' );
				} else {
					$carrier_code_1 = '';
				}
				
				$alrline_name_1 = trim ( $temp_carrier_code_1[0] );
				if (isset ( $alrline_name_1) == true) {
					$alrline_name_1	= trim ( $alrline_name_1, '() ' );
				} else {
					$alrline_name_1	= '';
				}
				
				
				$departure_from_dt_d 	= $data['dep_date'][$i];
				$departure_to_dt_d 		= $data['arr_date'][$i];
				
				$departure_dt_t 		= $data['departure_time'][$i];
				$arrival_dt_t 			= $data['arrival_time'][$i];
				
				$flight_details 		= array();
				$flight_data 			= array();
				
				$flight_details['fsid'] = $fsid;
				if(isset($data['origin'][$i])){
					$flight_details['origin'] 				= $this->getAirportCode($data['origin'][$i]);
					$flight_details['destination'] 			= $this->getAirportCode($data['destination'][$i]);
					
					$flight_details['departure_from_date'] 	= date('Y-m-d',strtotime($departure_from_dt_d));
					$flight_details['departure_to_date'] 	= date('Y-m-d',strtotime($departure_to_dt_d));
					$flight_details['departure_time'] 		= date('H:i',strtotime($departure_dt_t));
					$flight_details['arrival_time'] 		= date('H:i',strtotime($arrival_dt_t));

					$flight_details['flight_num'] 			= $data['flight_num'][$i];
					$flight_details['carrier_code'] 		= $carrier_code_1;
					$flight_details['airline_name'] 		= $alrline_name_1; 
					$flight_details['class_type'] 			= $data['class_type'][$i];
					$flight_details['fare_rule'] 			= $data['fare_rule'][$i];
					$flight_details['trip_type'] 			= $data['trip_type'];
					$flight_details['update_time'] 			= date('Y-m-d H:i:s');
					$flight_data = $this->custom_db->insert_record( 'flight_crs_details', $flight_details);
					//echo $this->db->last_query();
					$num_of_days = get_date_difference(date('Y-m-d',strtotime($departure_dt)), date('Y-m-d',strtotime($departure_to_dt_d)));
					
					$start_date = $departure_from_dt_d;
				}
				//die("end loop");
				/*for($j=0;$j<=$num_of_days;$j++){
					$fdid = $flight_data['insert_id'];
					$dep_dat = date('Y-m-d',strtotime("+".$j." day",strtotime($start_date)));
					$flight_available_date = array();
					
					$flight_available_date['fdid'] = $fdid;
					$flight_available_date['dep_datetime'] = date('Y-m-d H:i',strtotime($dep_dat.' '.$departure_dt_t));
					$flight_available_date['arr_datetime'] = get_nextDateTime_by_time($departure_dt_t,$arrival_dt_t,$dep_dat);
					$flight_avl = $this->custom_db->insert_record( 'flight_crs_available_dates', $flight_available_date);
				}*/
			}
		}
		return $fsid;
		//redirect ( base_url () . 'flight/flight_list/');
	}

	function getAirportCode($strPlace){
		preg_match('#\((.*?)\)#', $strPlace, $match);
		$strRetData = $match[1];
		return $strRetData;
	}

	function get_flight_suggestions(){
		
        ini_set('memory_limit', '-1');
        $term = $this->input->get('term'); //retrieve the search term that autocomplete sends
        $term = trim(strip_tags($term));
        $flights = $this->flight_crs_model->get_airport_list($term)->result_array();
		//debug('ok');exit();
        // debug($flights);exit;
        $result = array();
        foreach ($flights as $val) {
            $apts['label'] = $val['airport_name'].' - ('. $val['airport_code'].')' ;
            $apts['value'] = $val['airport_name'].' ('. $val['airport_code'].')' ;
            $apts['id'] = $val['airport_code'];
            $result[] = $apts;
        }
		//debug(json_encode($result));exit();
        echo json_encode($result);		
	}

	function get_airline_suggestions(){
		
        ini_set('memory_limit', '-1');
        $term = $this->input->get('term'); //retrieve the search term that autocomplete sends
        $term = trim(strip_tags($term));
        $flights = $this->flight_crs_model->get_crs_airline_list($term)->result_array();
		//debug('ok');exit();
        // debug($flights);exit;
        $result = array();
        foreach ($flights as $val) {
            $apts['label'] = $val['airline_code'];
            $apts['value'] = $val['airline_code'];
            $apts['id'] = $val['airline_code'];
            $result[] = $apts;
        }
		//debug(json_encode($result));exit();
        echo json_encode($result);		
	}

	function save_update_flight_details()
	{
		$post_params = $this->input->post();
		// echo "<pre>";
		// print_r($post_params);
		$status = $this->flight_crs_model->save_update_flight_details($post_params);
		if($status)
		{
			redirect ( base_url () . 'flight_crs/flight_list/');
		}
	}

	function delete_update_flight_details()
	{
		$id = $_POST['id'];
		
		$status = $this->flight_crs_model->delete_update_flight_details($id);

		if($status == true){
			echo "Done";
		}
	}

	function update_flight_data_per_date()
	{
		$data = $this->input->post();

		$status = $this->flight_crs_model->update_flight_data_per_date($data);
		if($status == true){
			echo true;
		}else{
			echo false;
		}
	}



	function update_seat_details()
	{
		$id = $_POST['id'];
		$seat = $_POST['seat'];
		$pnr = $_POST['pnr'];
		$abasefare = $_POST['abasefare'];
		$atax = $_POST['atax'];
		$ibasefare = $_POST['ibasefare'];
		$itax = $_POST['itax'];
		// echo $itax;
		// exit;
		$status = $this->flight_crs_model->update_seats_details($id,$seat,$pnr,$abasefare,$atax,$ibasefare,$itax);
		if($status == true){
			echo "Done";
		}
	}


	function offline_flight_book($cufid='') {
		// echo "string";exit();
		$page_data = $this->input->post();
		//debug($page_data);exit();
		if(!empty($cufid)){
			// echo "string";exit();
			$con['origin'] = $cufid;
			$select_date_data = $this->custom_db->single_table_records('crs_update_flight_details','*',$con)['data'][0];

			$page_data['flight_no'] = $select_date_data['fsid'];
			$page_data['dep_date_1'][0] = $select_date_data['avail_date'];
				//debug($page_data);exit();
		}
		
        $this->load->model('domain_management_model');
        if(valid_array($page_data)){
        	//echo "string";exit();
			if(isset($page_data['save'])){
			//echo "string";exit();	
				
				$user_type    = $page_data['hid_user_type'];
				$total_amount = $page_data['api_total_selling_price'];
				$agent_id    = $page_data['agent_id'];

				 $domain_balance_status = FAILURE_STATUS;
				 if($user_type==B2B_USER){
				 	$domain_balance_status = $this->domain_management_model->verify_current_balance ( $total_amount, $agent_id );
				 }else{
				 	$domain_balance_status = SUCCESS_STATUS;
				 }
		//debug($domain_balance_status); exit;
				if ($domain_balance_status) {
					
					$check_seat_avalibality = 0;
					$seat_no = 0;
					$check_seat_avalibality = $page_data['adult_count'];
					$con_for_seat_chk = array();
					$con_for_seat_chk['origin'] = $page_data['fdid'];
					$seat_avalibality = $this->custom_db->single_table_records('crs_update_flight_details','*',$con_for_seat_chk)['data'][0];

					$seat_no = $seat_avalibality['avail_seat']-$seat_avalibality['booked_seat'];
                   
					$booked_seat_old = $seat_avalibality['booked_seat'];
					if($seat_no>=$check_seat_avalibality){

						$page_data['booked_seat_new'] = $booked_seat_old + $check_seat_avalibality;
						//echo $seat_no.'==='.$check_seat_avalibality.'==='.$booked_seat_old; exit;
						$app_reference = generate_app_transaction_reference(FLIGHT_BOOKING);
						//debug($app_reference);exit();
						$this->domain_management_model->create_track_log($app_reference, 'Offline Booking Start- Flight');
						$this->flight_crs_model->offline_flight_book($page_data, $app_reference);
						
						$agency_name = 'TRAVEL IMPRESSION';
						$lead_pax_name = $page_data['pax_first_name'][0].' '. $page_data['pax_last_name'][0];
						$pax_no = count($page_data['pax_first_name']);
						$pnr = $page_data['airline_pnr_onward'][0];
						$airline_code = $page_data['career_onward'][0];
						$flight_number = $page_data['flight_num_onward'][0];
						$departure_datetime = $page_data['dep_date_onward'][0].' '.$page_data['dep_time_onward'][0];
						$departure_datetime=date_create($departure_datetime);
						$departure_datetime = date_format($departure_datetime,"d-M-Y H:i A");

						$arrival_datetime = $page_data['arr_date_onward'][0].' '.$page_data['arr_time_onward'][0];
						$arrival_datetime=date_create($arrival_datetime);
						$arrival_datetime = date_format($arrival_datetime,"d-M-Y H:i A");
						$from_airport = $page_data['origin_city'];
						$to_airport = $page_data['destination_city'];

						$msg = flight_booking_sms_template($agency_name,$lead_pax_name,$pax_no,$pnr,$airline_code,$flight_number,$departure_datetime,$arrival_datetime,$from_airport,$to_airport);
						$data['phone'] = $page_data['passenger_phone'];
						//$msg = "Dear " . $data ['name'] . " Thank you for Booking your ticket with us.Ticket Details will be sent to your email id";
						$msg = urlencode ( $msg );
						$this->load->library ( 'provab_sms' );
						$sms_status = $this->provab_sms->send_msg ( $data ['phone'], $msg );

						redirect('voucher/flight/' . $app_reference, $page_data);
						
					}else{
						$page_data ['low_balance_alert'] = get_message('Seat not avalible. Can Not Proceed.', ERROR_MESSAGE, true, true);
					}

				} else {
					$page_data ['low_balance_alert'] = get_message('Balance Is Low. Can Not Proceed.', ERROR_MESSAGE, true, true);
				}	
			}

			
			if(isset($page_data['flight_no'])){
				//echo "string";exit();
				$page_data['flight_info']=$this->domain_management_model->specific_airline_availabiity($page_data['flight_no'],$page_data['dep_date_1'][0],$cufid);
				//debug($page_data);exit();
				$page_data['empty']=true;

				$total_seat  = 0;
				$booked_seat = 0;
				$avail_seat  = 0;
				
				if(valid_array($page_data['flight_info'])){
					$total_seat = $page_data['flight_info'][0]['avail_seat'];
					$booked_seat = $page_data['flight_info'][0]['booked_seat'];
					$avail_seat = $total_seat - $booked_seat;
					$page_data['avail_seat'] = $avail_seat;
					$page_data['fsid'] = $page_data['flight_info'][0]['fsid'];
					$page_data['fdid'] = $cufid;
				}
				$page_data['flight_crs_list']=$this->domain_management_model->airline_availabiity($page_data);
				//debug($page_data);exit();
				}else{

				$page_data['flight_crs_list']=$this->domain_management_model->airline_availabiity($page_data);
				$page_data['flight_info']=$this->domain_management_model->specific_airline_availabiity($page_data['flight_no'],$page_data['dep_date_1'][0],$cufid);
				if(empty($page_data['flight_crs_list'])){
					$page_data['error']="Check the flight list for more information.";
					}	
				}
				//debug($page_data);die;
		}

		//echo "string";exit();
        /*  $page_data['sect_num_onward'] = 1;
            $page_data['sect_num_return'] = 0;
            $page_data['adult_count'] = 1;
            $page_data['child_count'] = 0;
            $page_data['infant_count'] = 0;
            $page_data['pax_type_count_onward'][0] = $page_data['adult_count'];
            $page_data['pax_type_count_return'][0] = $page_data['adult_count'];
            $page_data['trip_type'] = 'oneway';
        }*/
			$page_data['adult_count']=1;
			$page_data['child_count']=0;
			$page_data['infant_count']=0;
			$page_data['airport_list_l'] = array();
			$page_data['airline_list']   = $this->flight_crs_model->get_airline_list('','');
        $page_data['supliers_list'] = $this->domain_management_model->get_flight_suplier_source();
//$page_data['empty']=true;
        //debug($page_data); exit;
        $this->template->view('flight_crs/offline_flight_book', $page_data);
    }

    function offline_flight_book_new() {
        $page_data = $this->input->post();
        
        if (valid_array($page_data)) {
        	
            $this->load->model('domain_management_model');
            
            $domain_id = 1;
            
        	$user_type    = $page_data['hid_user_type'];
        	$total_amount = $page_data['agent_buying_price'];
			$agent_id    = $page_data['agent_id'];

			 $domain_balance_status = FAILURE_STATUS;
			 if($user_type==B2B_USER){
			 	$domain_balance_status = $this->domain_management_model->verify_current_balance ( $total_amount, $agent_id );
			 }else{
			 	$domain_balance_status = SUCCESS_STATUS;
			 }
            # Get domain Key using domain id

            $domain_details = $this->domain_management_model->domain_currency_key($domain_id);

            $get_currency = '';
            if (isset($domain_details['currency_converter_fk'])) {
                $get_currency_detail = $this->domain_management_model->get_currency($domain_details['currency_converter_fk']);
                $get_currency = $get_currency_detail['currency'];
            }
            
           // $this->load->library('domain_management');
         //   $flight_engine_system = $this->config->item('flight_engine_system');
          //Verify Domain Balance 	
            //$this->domain_management->verify_domain_balance_offline_bookings($total_amount, $flight_engine_system, $domain_details, $get_currency)
           	 if ($domain_balance_status) {
            //	 debug($domain_balance_status);exit;
                $app_reference = generate_app_transaction_reference(FLIGHT_BOOKING);

                $this->domain_management_model->create_track_log($app_reference, 'Offline Booking Start- Flight');

                $this->flight_crs_model->offline_flight_book_new($page_data, $app_reference);
                //echo "Coming Down";exit;


				$agency_name = 'TRAVEL IMPRESSION';
				$lead_pax_name = $page_data['pax_first_name'][0].' '. $page_data['pax_last_name'][0];
				$pax_no = count($page_data['pax_first_name']);
				$pnr = $page_data['airline_pnr_onward'][0];
				$airline_code = $page_data['career_onward'][0];
				$flight_number = $page_data['flight_num_onward'][0];
				$departure_datetime = $page_data['dep_date_onward'][0].' '.$page_data['dep_time_onward'][0];
				$departure_datetime=date_create($departure_datetime);
				$departure_datetime = date_format($departure_datetime,"d-M-Y H:i A");

				$arrival_datetime = $page_data['arr_date_onward'][0].' '.$page_data['arr_time_onward'][0];
				$arrival_datetime=date_create($arrival_datetime);
				$arrival_datetime = date_format($arrival_datetime,"d-M-Y H:i A");
				$from_airport = $page_data['origin_city'];
				$to_airport = $page_data['destination_city'];

				$msg = flight_booking_sms_template($agency_name,$lead_pax_name,$pax_no,$pnr,$airline_code,$flight_number,$departure_datetime,$arrival_datetime,$from_airport,$to_airport);
				$data['phone'] = $page_data['passenger_phone'];
				//$msg = "Dear " . $data ['name'] . " Thank you for Booking your ticket with us.Ticket Details will be sent to your email id";
				$msg = urlencode ( $msg );
				$this->load->library ( 'provab_sms' );
				$sms_status = $this->provab_sms->send_msg ( $data ['phone'], $msg );



                redirect('voucher/flight/' . $app_reference, $page_data);
            } else {
                $page_data ['low_balance_alert'] = get_message('Balance Is Low. Can Not Proceed.', ERROR_MESSAGE, true, true);
            }
        } else {
            $page_data['sect_num_onward'] = 1;
            $page_data['sect_num_return'] = 0;
            $page_data['adult_count'] = 1;
            $page_data['child_count'] = 0;
            $page_data['infant_count'] = 0;
            $page_data['pax_type_count_onward'][0] = $page_data['adult_count'];
            $page_data['pax_type_count_return'][0] = $page_data['adult_count'];
            $page_data['trip_type'] = 'oneway';
        }
        //debug($page_data);exit;

        $supliers_list = array();
        $supplier = $this->domain_management_model->get_suplier_source();
        if(isset($supplier['PTBSID0000000005'])){
			unset($supplier['PTBSID0000000005']);	
		}
       	$page_data['supliers_list'] = array_merge($supliers_list,$supplier);
        $this->template->view('flight_crs/offline_flight_book_new', $page_data);
    }



    function old_offline_flight_book() {
		
        $page_data = $this->input->post();
        $this->load->model('domain_management_model');
        if(valid_array($page_data)){
			if(isset($page_data['save'])){	
				#debug($page_data);die;
					$total_amount = $page_data['api_total_selling_price'];
					$domain_id = $page_data['agent_id'];
					# Get domain Key using domain id 
					$domain_details = $this->domain_management_model->domain_list_ajax($page_data['agent_id']);
					$get_currency = '';
					/*if (isset($domain_details['currency_converter_fk'])) {
						$get_currency_detail = $this->domain_management_model->get_currency($domain_details['currency_converter_fk']);
						$get_currency = $get_currency_detail['currency'];
					}*/
					//$this->load->library('domain_management');
					if ($total_amount > 0 && $domain_details>$total_amount) {
						$app_reference = generate_app_transaction_reference(FLIGHT_BOOKING);
						$this->domain_management_model->create_track_log($app_reference, 'Offline Booking Start- Flight');
						$this->flight_crs_model->offline_flight_book($page_data, $app_reference);
						redirect('voucher/flight/' . $app_reference, $page_data);
					} else {
						$page_data ['low_balance_alert'] = get_message('Balance Is Low. Can Not Proceed.', ERROR_MESSAGE, true, true);
					}	
				}
			
			if(isset($page_data['flight_no'])){
				$page_data['flight_info']=$this->domain_management_model->specific_airline_availabiity($page_data['flight_no'],$page_data['dep_date_1'][0]);
				$page_data['empty']=true;
				$page_data['flight_crs_list']=$this->domain_management_model->airline_availabiity($page_data);
				}else{
				$page_data['flight_crs_list']=$this->domain_management_model->airline_availabiity($page_data);
				$page_data['flight_info']=$this->domain_management_model->specific_airline_availabiity($page_data['flight_no'],$page_data['dep_date_1'][0]);
				if(empty($page_data['flight_crs_list'])){
					$page_data['error']="Check the flight list for more information.";
					}	
				}
				#debug($page_data);die;
		}
        /*  $page_data['sect_num_onward'] = 1;
            $page_data['sect_num_return'] = 0;
            $page_data['adult_count'] = 1;
            $page_data['child_count'] = 0;
            $page_data['infant_count'] = 0;
            $page_data['pax_type_count_onward'][0] = $page_data['adult_count'];
            $page_data['pax_type_count_return'][0] = $page_data['adult_count'];
            $page_data['trip_type'] = 'oneway';
        }*/
			$page_data['adult_count']=1;
			$page_data['child_count']=0;
			$page_data['infant_count']=0;
			$page_data['airport_list_l'] = array();
			$page_data['airline_list']   = $this->flight_crs_model->get_airline_list('','');
        $page_data['supliers_list'] = $this->domain_management_model->get_flight_suplier_source();
        $this->template->view('flight_crs/offline_flight_book', $page_data);
    }
    function get_offline_flight_row($type) {
        $page_data = $this->input->get();
        $page_data['trip_type'] = $type;
        echo $this->template->isolated_view('flight_crs/offline_flight_row', $page_data);
    }
    function get_offline_flight($type) {
        $page_data = $this->input->get();
        $this->load->model('domain_management_model');
        $data=$this->domain_management_model->airline_availabiity($page_data);
        debug($data);die;
    }
    /* 
	 * Add Suppliers
    */
    function add_suppliers(){
    	$post_params = $this->input->post();
    	if(valid_array($post_params) && !empty($post_params)){  
			$this->form_validation->set_rules('supplier_name', 'Supplier Name', 'trim|required|is_unique[supplier.supplier_name]');
			$this->form_validation->set_rules('booking_source', 'Booking Source', 'trim|required|is_unique[supplier.booking_source]',array(
                'required'      => 'You have not provided %s.',
                'is_unique'     => 'This %s already exists.'
        	));
			if ($this->form_validation->run()) {    	  		
				$this->custom_db->insert_record('supplier', $post_params);
				//set_update_message ( "Successfully Added.");
				redirect(base_url().'index.php/flight/add_suppliers');
			}
    	}
    	$con = array('status' => 1);
    	$supplier = $this->custom_db->single_table_records('supplier','*',$con);
    	$page_data['supplier'] = $supplier['data'];
    	$this->template->view('flight_crs/add_suppliers',$page_data);
    }
    function delete_suppliers($id){
    	$this->flight_crs_model->delete_suppliers($id);
    	redirect(base_url().'index.php/flight_crs/add_suppliers');
    }

    /*
     * 
     *
     * Offline Pax row
     *
     * */

    function get_offline_pax_row($type,$price) {
        $page_data = $this->input->get();
        $page_data['pax_type'] = $type;
        $page_data['price'] = $price;
        echo $this->template->isolated_view('flight_crs/offline_pax_row', $page_data);
    }
    function get_offline_pax_row_new($type,$price) {
        $page_data = $this->input->get();
        $page_data['pax_type'] = $type;
        $page_data['price'] = $price;
        echo $this->template->isolated_view('flight_crs/offline_pax_row_new', $page_data);
    }
    function offline_fare_calculate() {

        $flight_data = $this->input->post();
		//debug($flight_data);exit; 
        $pax_fare = array();
        $c = 0;
        $agent_id = $flight_data['agent_id'];
		
        /*$price['api_total_tax'] = 0;
        $price['api_total_basic_fare'] = 0;
        $price['api_total_yq'] = 0;
        $price['service_tax'] = 0;
        $price['meal_and_baggage_fare'] = 0;
        $price['commission'] = 0;
        $price['tds'] = 0;
        $price['agent_buying_price'] = 0;
        $price['api_total_selling_price'] = 0;

        
        $price['commission'] = round($price['commission']);
        $price['tds'] = round($price['tds']);
        $price['agent_buying_price'] = round($price['agent_buying_price']);
        $price['api_total_selling_price'] = round($price['api_total_selling_price']);*/
		
		$price['api_total_basic_fare']=$flight_data['adult_count']*$flight_data['adt_base_fare']+$flight_data['infant_count']*$flight_data['inf_base_fare'];

		$price['api_total_tax']=$flight_data['adult_count']*$flight_data['adt_tax_fare']+$flight_data['infant_count']*$flight_data['inf_tax_fare'];

		$price['api_total_selling_price']=$price['api_total_basic_fare']+$price['api_total_tax'];
		$total_price = array_sum($flight_data['pax_base_fare']);
		$price['total_basic_fare'] = $total_price;
        echo json_encode($price);
        //debug($page_data);
    }

    //Dinesh Kumar Behera 7-Feb-2018
    function export_passenger_details($op=''){

    	$get_data = $this->input->GET();
		$condition = array();

		$con['origin'] = $get_data['fl_details_id'];
		$select_date_data = $this->custom_db->single_table_records('crs_update_flight_details','*',$con);

		$condition_for_seat_details = array();
		
		$details = array();
		$details['select_date_data'] = $select_date_data['data'][0];
//debug($details);exit();
		$condition_for_seat_details['journey_start'] = $details['select_date_data']['avail_date'];


		$details['gender'] = 'Infant';
		$seat_details['data'] = $this->offline_flight_booking_report_per_PNR($details);
		

		$export_data = $seat_details['data'];
	//debug($seat_details);exit;

		if($op == 'excel'){ // excel export

			$headings = array( 'a1' => 'Sl. No.', 
        				   'b1' => 'Pax Type', 
        				   'c1' => 'Title',
        				   'd1' => 'Gender', 
        				   'e1' => 'First Name',
        				   'f1' => 'Last Name',
        				   'g1' => 'Date of Birth(DD-MMM-YYYY)'
        				  // 'f1' => 'Application Reference', 
        				  // 'g1' => 'Travel Agent', 
        				  // 'f1' => 'Status'
        				  );
	        // field names in data set 
	        $fields = array( 'a' => '', // empty for sl. no.
	        				 'b' => 'passenger_type',
	        				 'c' => 'title', 
	        				 'd' => 'gender',
	        				 'e' => 'first_name', 
	        				 'f' => 'last_name',
	        				 'g' => ''
	        				// 'f' => 'app_reference',
	        				 //'g' => 'agency_name',
	        				// 'f' => 'status'
	        				 );   

	        $excel_sheet_properties = array(
	        				'title' => 'Passenger_Details_'.date('d-M-Y'), 
	        				'creator' => 'Provab', 
	        				'description' => 'Passenger Details of Travel Impression', 
	        				'sheet_title' => 'Passenger Details'
	        	);   
	       		
	        $this->load->library ( 'provab_excel' ); // we need this provab_excel library to export excel.
	        $this->provab_excel->excel_export ( $headings, $fields, $export_data, $excel_sheet_properties);

		}
    }

		function flight_passenger_details($origin)
		{

			$response['status'] = 0;
			$response['msg'] = '';	
			$mail_template = '';
			$get_data = $this->input->post();
			//debug($get_data);exit;
			$con['origin'] = $get_data['fuid'];

			$origin = $get_data['fuid'];
			$select_date_data = $this->custom_db->single_table_records('crs_update_flight_details','*',$con);

		
			$details = array();
			$details['select_date_data'] = $select_date_data['data'][0];
			$seat_details['updated_flight_details'] = $details['select_date_data'];

			$query = "select *	from flight_crs_segment_details where fsid=".$details['select_date_data']['fsid'];
			$seat_details['segment_details'] = $this->db->query($query)->result_array()[0];
		

			$details['gender'] = 'Infant';
			$seat_details['data'] = $this->offline_flight_booking_report_per_PNR($details);
			$email=$get_data['email'];

			if ($get_data['send_html'] == true) {
				$mail_template .= $this->template->isolated_view('flight_crs/passenger_list_email_template', $seat_details);
			} else {
				$mail_template .= "<p>Dear ".$email.",</p>";
				if (!empty($_FILES)) {
					$mail_template .= "<p>Please find below attachment of passenger list. </p>";
				} else {
					$mail_template .= "<p>Sorry,You have not attach any file .Try Again</p>";
				}				
				$mail_template .= "<p>Thankyou</p>";
			}

			

			$attach_file_path = "";
			
			/*if(isset($_FILES['file'])){
				$config['upload_path']          = DOMAIN_PNR_PAX_XLS_UPLOAD_DIR;
	            $config['allowed_types']        = '*';
	            $config['max_size']             = 100000;
	            $config['overwrite'] = TRUE;
	            //$config['max_width']            = 1024;
	            //$config['max_height']           = 768;

	            $this->load->library('upload', $config);
	            
	            if ( ! $this->upload->do_upload('file'))
	            {
	                $error = array('error' => $this->upload->display_errors());
	                $response['msg'] = $error;	
	                echo json_encode($response);
	                exit;
	            }
	            else
	            {
	                $data = array('upload_data' => $this->upload->data());
	                $attach_file_path = $data['upload_data']['full_path'];
	                $response['status'] = 1;
	            }
			}*/

			if(!empty($_FILES['file']['name'])){
		            $filesCount = count($_FILES['file']['name']);
		                $uploadPath = DOMAIN_PNR_PAX_XLS_UPLOAD_DIR;
		                $config['file_name'] = "Passenger_Details_list_".date('Y-m-d')."_".time();
		                $config['upload_path'] = $uploadPath;
		                $config['allowed_types'] = '*';
		                
		                $this->load->library('upload', $config);
		                $this->upload->initialize($config);
		                if($this->upload->do_upload('file')){
		                    $fileData = $this->upload->data();
		                    $uploadData['file_name'] = $fileData['file_name'];
		                    $response['status'] = 1;
	                		$attach_file_path = $fileData['full_path'];
		                } else {
		                	$error = array('error' => $this->upload->display_errors());
			                $response['msg'] = $error;	
			                echo json_encode($response);
			                exit;
		                }
		            
		        } else {
		        	$response['status'] = 1;
		        }

			
			$subject=$get_data['email_subject'];
			$attachment=$attach_file_path;
			$cc=explode(',', $get_data['cc_email']);
			if(!empty($attachment)){
				//debug($attachment);exit;
	        	$this->provab_mailer->send_mail($email, $subject ,$mail_template,$attachment ,$cc);
	        }else{
	        	$this->provab_mailer->send_mail($email, $subject,$mail_template ,"",$cc);
	        }
			$response['msg'] = 'Email sent  Successfully!!!';	
			echo json_encode($response);
                exit;

		}

    //End

	/*
	 * Badri Nath Nayak
	 *
	 * Offline Flight Cancellation requests
	 *
	 * */
		
	function offline_cancel_request($cancellation_id='') {
		error_reporting(0);
	/*	if (! check_user_previlege ( 'p63' )) {
			set_update_message ( "You Don't have permission to do this action.", WARNING_MESSAGE, array (
					'override_app_msg' => true
			) );
			redirect ( base_url () );
		}*/
		$condition = $page_data = array ();
		
		if (empty ( $cancellation_id ) == false) {	
                    
			$booking_details = $this->flight_crs_model->get_cancelled_booking_details ( $cancellation_id);
		
			if ($booking_details ['status'] == SUCCESS_STATUS) {
				$offline_cancel  = $booking_details['data'] ['offline_cancel'];
				$this->load->library ( 'booking_data_formatter' );
				$assembled_booking_details = $this->booking_data_formatter->format_flight_booking_data ( $booking_details, 'b2c' );
			#debug($assembled_booking_details);exit;
				$page_data ['data'] = $assembled_booking_details ['data'];
				$page_data ['data']['offline_cancel'] =	$offline_cancel;			
			}
		}
		$get_data = $this->input->get();

		if(valid_array($get_data)){
			if($get_data['created_by_id']){
				$condition[] = array('U.user_id','=',$get_data['created_by_id']);
			}
		} 	//debug($condition ); exit;
		$page_data ['offline_cancel_requests'] = $this->flight_crs_model->flight_offline_cancel_request_details ($condition);
		//debug($page_data);exit;
		$this->template->view ( 'flight_crs/offline_cancel_request', $page_data );
	}
	/**
	Dinesh 13 Feb 2018
	*/
	function add_fare_rule()
	{
		$data = $this->input->post();
		if(!empty($data)){
			if($data['origin'] != 0){
				$carrier = explode('(', $data['carrier_code'] );
				$data['carrier_name'] = $carrier[0];
				$data['carrier_code'] =  explode(")", $carrier[1])[0];
				$this->custom_db->update_record('flight_crs_fare_rules', $data, array('origin' => $data['origin']) );
			} else {
				$carrier = explode('(', $data['carrier_code'] );
				$data['carrier_name'] = $carrier[0];
				$data['carrier_code'] =  explode(")", $carrier[1])[0];
				$this->custom_db->insert_record('flight_crs_fare_rules', $data);
			}
		}
		redirect(base_url().'index.php/flight_crs/flight_fare_rules');
	}
	function check_fare_rule()
	{
		$data = $this->input->post();
		$cond['is_domestic'] = $data['is_domestic'];
		$carrier = explode('(', $data['carrier_code'] );		
		$carrier_code =  explode(")", $carrier[1])[0];
		$cond['carrier_code'] = $carrier_code;
		$result = $this->custom_db->single_table_records('flight_crs_fare_rules', '*', $cond);
		if($result['status'] == true){
			echo json_encode(array('status' => false, 'msg' => "Fare rule for this setting already exists."));
		} else {
			echo json_encode(array('status' => true, 'msg' => "save successfully"));
		}	
		exit;
	}

	function delete_fare_rule($origin){
		$cond['origin'] = $origin;
		$result = $this->custom_db->delete_record('flight_crs_fare_rules', $cond);
		echo json_encode(array('status'=> true));exit;
	}


	function flight_meal_details(){
		$data =  $this->custom_db->single_table_records('flight_crs_meal_details');
		if($data['status']) {
			$page_data['meal_detail_list'] = $data['data'];			
		} else {
			$page_data['meal_detail_list'] = array();
		}
		$this->template->view('flight_crs/flight_meal_details', $page_data);
	}

	function add_meal_details()
	{
		$data = $this->input->post();
		if(!empty($data)){
			if($data['origin'] != 0){
				$carrier = explode('(', $data['carrier_code'] );
				$data['carrier_name'] = $carrier[0];
				$data['carrier_code'] =  explode(")", $carrier[1])[0];
				$data['currency'] =  'INR';
				$this->custom_db->update_record('flight_crs_meal_details', $data, array('origin' => $data['origin']) );
			} else {
				$carrier = explode('(', $data['carrier_code'] );
				$data['carrier_name'] = $carrier[0];
				$data['carrier_code'] =  explode(")", $carrier[1])[0];
				$data['currency'] =  'INR';
				$this->custom_db->insert_record('flight_crs_meal_details', $data);				
			}
		}
		redirect(base_url().'index.php/flight_crs/flight_meal_details');
	}
	function delete_meal_details($origin){
		$cond['origin'] = $origin;
		$result = $this->custom_db->delete_record('flight_crs_meal_details', $cond);
		echo json_encode(array('status'=> true));exit;
	}

	function get_flight_fair_rule($carrier_code="",$flight_is_domestic=0){
		$data =  $this->custom_db->single_table_records('flight_crs_fare_rules',$cols='*', $condition=array('carrier_code'=>$carrier_code,'is_domestic'=>$flight_is_domestic));
		if($data['status']) {
			$fare_rule = $data['data'][0]['fare_rule'];
				echo json_encode(array('status'=>true,'fare_rule' => $fare_rule));	
		} else {
			echo json_encode(array('status' => false));	
		}
	}

	function update_mailing_status(){
		$get_data = $this->input->post();
		//debug($get_data);exit;
		$mailing_status = $get_data['mailing_status'];
		$book_id = $get_data['book_id'];
		$data = $this->flight_crs_model->update_mailing_status($book_id, $mailing_status);
		if ($data['status'] == true) {
			echo json_encode(array('status'=>true,'msg'=>'Successfully Updated'));
		} else {
			echo json_encode(array('status'=>false,'msg'=>'Sorry,Something went wrong'));
		}

	}
	/**
	Dinesh 13 Feb 2018 End
	*/



	public function smppsmshub() {
		
$number = '8917621457';
			$message = urlencode('test JUNAID');
$this->load->library('provab_sms');
$res = $this->provab_sms->send_msg($number,$message);
debug($res);
exit;



	}



	/*
	 * 
	 * Offline Flight Cancellation process request
	 *
	 * */
	
	function update_offline_cancel_request($cancellation_id){
		$page_data ['form_data'] = $this->input->post();
	//	debug($page_data ['form_data'] );exit;
		if (empty ( $cancellation_id ) == false && valid_array ( $page_data ['form_data'] )) {
			$page_data['form_data']['cancellation_id'] = $cancellation_id;
		
			$data = $this->flight_crs_model->update_offline_cancel_request_data ( $page_data ['form_data'] );
			$this->load->model ( 'domain_management_model' );			
		}
		
		redirect ('flight_crs/offline_cancel_request/');
	}
			
  function flight_group_booking_request_list() {
      /*  if (!check_user_previlege('p83')) {
            set_update_message("You Don't have permission to do this action.", WARNING_MESSAGE, array(
                'override_app_msg' => true
            ));
            redirect(base_url());
        }*/
        $flight_group = array();
        $flight_group_booking_request_list = $this->flight_crs_model->flight_request_b2c_details();
        $page_data = $flight_group_booking_request_list;
        $flight_group ['page_data'] = $page_data;

        $this->template->view('flight_crs/flight_b2c_group_booking_request_list', $flight_group);
    }


}
