<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @package    Provab
 * @subpackage Bus
 * @author     Balu A<balu.provab@gmail.com>
 * @version    V1
 */

class Bus extends CI_Controller {
	private $current_module;
	public function __construct()
	{
		parent::__construct();
		//we need to activate bus api which are active for current domain and load those libraries
		$this->load->model('bus_model');
		$this->load->model('domain_management_model');
		$this->current_module = $this->config->item('current_module');
		//$this->output->enable_profiler(TRUE);
	}
	/**
	 * Balu A
	 */
	function pre_cancellation($app_reference, $booking_source)
	{
		if (empty($app_reference) == false && empty($booking_source) == false) {
			$page_data = array();
			$booking_details = $this->bus_model->get_booking_details($app_reference, $booking_source);
			if ($booking_details['status'] == SUCCESS_STATUS) {
				$this->load->library('booking_data_formatter');
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_bus_booking_data($booking_details,$this->current_module);
				$page_data['data'] = $assembled_booking_details['data'];
				$this->template->view('bus/pre_cancellation', $page_data);
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
		error_reporting(0);
		if(empty($app_reference) == false) {
			$master_booking_details = $this->bus_model->get_booking_details($app_reference, $booking_source);
			if ($master_booking_details['status'] == SUCCESS_STATUS) {
				$this->load->library('booking_data_formatter');
				$master_booking_details = $this->booking_data_formatter->format_bus_booking_data($master_booking_details, 'b2c');
				$master_booking_details = $master_booking_details['data']['booking_details'][0];
				$PNRNo = trim($master_booking_details['pnr']);
				$TicketNo = trim($master_booking_details['ticket']);
				$SetaNos = $master_booking_details['seat_numbers'];
				$booking_details = array();
				$booking_details['PNRNo'] = $PNRNo;
				$booking_details['TicketNo'] = $TicketNo;
				$booking_details['SeatNos'] = $SetaNos;
				$booking_details['booking_source'] = $booking_source;
				load_bus_lib($booking_source);
				$cancellation_details = $this->bus_lib->cancel_full_booking($booking_details, $app_reference);//Invoke Cancellation Methods
				// debug($cancellation_details);exit;

				
				if($cancellation_details['status'] == true) {//IF Cancellation is Success
					$cancellation_details = $this->bus_lib->save_cancellation_data($app_reference, $cancellation_details);//Save Cancellation Data
				}
				redirect('bus/cancellation_details/'.$app_reference.'/'.$booking_source);
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
		$master_booking_details = $GLOBALS['CI']->bus_model->get_booking_details($app_reference, $booking_source);
		if ($master_booking_details['status'] == SUCCESS_STATUS) {
			$page_data = array();
			$this->load->library('booking_data_formatter');
			$master_booking_details = $this->booking_data_formatter->format_bus_booking_data($master_booking_details, 'b2c');
			$page_data['data'] = $master_booking_details['data'];
			$this->template->view('bus/cancellation_details', $page_data);
		} else {
			redirect('security/log_event?event=Invalid Details');
		}
		} else {
			redirect('security/log_event?event=Invalid Details');
		}
		
	}
	/**
	 * Balu A
	 */
	function exception()
	{
		$module = META_BUS_COURSE;
		$op = @$_GET['op'];
		$notification = @$_GET['notification'];
		$eid = $this->module_model->log_exception($module, $op, $notification);
		//set ip log session before redirection
		$this->session->set_flashdata(array('log_ip_info' => true));
		redirect(base_url().'index.php/bus/event_logger/'.$eid);
	}
	
	/**
	 * Log Events 
	 * @param number $eid
	 */
	function event_logger($eid='')
	{
		$log_ip_info = $this->session->flashdata('log_ip_info');
		$this->template->view('bus/exception', array('log_ip_info' => $log_ip_info, 'eid' => $eid));
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
			$booking_details = $this->bus_model->get_booking_details($app_reference, $booking_source, $status);
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
				$this->template->view('bus/cancellation_refund_details', $page_data);
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
		$this->form_validation->set_rules('cancel_charge_percentage', 'cancel_charge_percentage', 'trim|numeric');
		$this->form_validation->set_rules('refund_status', 'refund_status', 'trim|required|xss_clean');
		$this->form_validation->set_rules('refund_comments', 'refund_comments', 'trim|required');
		if ($this->form_validation->run()) {
			$app_reference = 				trim($post_data['app_reference']);
			$booking_source = 				trim($post_data['booking_source']);
			$status = 						trim($post_data['status']);
			$refund_payment_mode = 			trim($post_data['refund_payment_mode']);
			$refund_amount = 				floatval($post_data['refund_amount']);
			$cancel_charge_percentage = 	floatval($post_data['cancel_charge_percentage']);
			$refund_status = 				trim($post_data['refund_status']);
			$refund_comments = 				trim($post_data['refund_comments']);
			//Get Booking Details
			$booking_details = $this->bus_model->get_booking_details($app_reference, $booking_source, $status);
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
					$remarks = 'bus Refund was Successfully done';
					$this->domain_management_model->save_transaction_details('bus', $app_reference, $fare, $domain_markup, $level_one_markup, $remarks, $convinence, $discount, $booking_currency, $currency_conversion_rate, $booked_user_id);
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
				$update_refund_details['cancel_charge_percentage'] = 		$cancel_charge_percentage;
				$update_refund_details['refund_status'] = 					$refund_status;
				$update_refund_details['refund_comments'] = 				$refund_comments;
				$update_refund_details['currency'] = 						$booking_currency;
				$update_refund_details['currency_conversion_rate'] = 		$currency_conversion_rate;
				if($refund_status == 'PROCESSED'){
					$update_refund_details['refund_date'] = 				date('Y-m-d H:i:s');
				}
				$this->custom_db->update_record('bus_cancellation_details', $update_refund_details, $update_refund_condition);
				
				$redirect_url_params['app_reference'] = $app_reference;
				$redirect_url_params['booking_source'] = $master_booking_details['booking_source'];
				$redirect_url_params['status'] = $status;
			}
		}
		redirect('bus/cancellation_refund_details?'.http_build_query($redirect_url_params));
	}
}