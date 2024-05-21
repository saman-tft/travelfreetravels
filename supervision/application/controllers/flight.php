<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
ini_set('max_execution_time', 300);
/**
 *
 * @package    Provab
 * @subpackage Flight
 * @author     Balu A<balu.provab@gmail.com>
 * @version    V1
 */

class Flight extends CI_Controller {
	private $current_module;
	public function __construct()
	{
		parent::__construct();
		//$this->output->enable_profiler(TRUE);
		$this->load->model('flight_model');
		$this->load->model('domain_management_model');
		$this->current_module = $this->config->item('current_module');
	}

	function get_booking_details($app_reference)
	{
		//
		$condition[] = array('BD.app_reference', '=', $this->db->escape($app_reference));
		$details = $this->flight_model->get_booking_details($app_reference);
		if ($details['status'] == SUCCESS_STATUS) {
			$booking_source = $details['data']['booking_details']['booking_source'];
			load_flight_lib($booking_source);
			$this->flight_lib->get_booking_details($details['data']['booking_details'], $details['data']['booking_transaction_details']);
		}
	}
	/**
	 * Cancellation
	 * Balu A
	 */
	function pre_cancellation($app_reference, $booking_source)
	{
		if (empty($app_reference) == false && empty($booking_source) == false) {
			$page_data = array();
			$booking_details = $this->flight_model->get_booking_details($app_reference, $booking_source);
			if ($booking_details['status'] == SUCCESS_STATUS) {
				$this->load->library('booking_data_formatter');
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_flight_booking_data($booking_details,$this->current_module);
				$page_data['data'] = $assembled_booking_details['data'];
				$this->template->view('flight/pre_cancellation', $page_data);
			} else {
				redirect('security/log_event?event=Invalid Details');
			}
		} else {
			redirect('security/log_event?event=Invalid Details');
		}
	}
	/**
	 * Balu A
	 * @param $app_reference
	 */
	function cancel_booking()
	{
		//error_reporting(E_ALL);
		$post_data = $this->input->post();
		if (isset($post_data['app_reference']) == true && isset($post_data['booking_source']) == true && isset($post_data['transaction_origin']) == true &&
			valid_array($post_data['transaction_origin']) == true && isset($post_data['passenger_origin']) == true && valid_array($post_data['passenger_origin']) == true) {
			$app_reference = trim($post_data['app_reference']);
			$booking_source = trim($post_data['booking_source']);
			$transaction_origin = $post_data['transaction_origin'];
			$passenger_origin = $post_data['passenger_origin'];
			$booking_details = $GLOBALS['CI']->flight_model->get_booking_details($app_reference, $booking_source);
                        
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

				$cancellation_details = base64_encode(json_encode($cancellation_details));

				redirect('flight/cancellation_details/'.$app_reference.'/'.$booking_source.'/'.$cancellation_details);
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
	function cancellation_details($app_reference, $booking_source, $cancellation_details)
	{
		$cancellation_details = json_decode(base64_decode($cancellation_details), true);
		if (empty($app_reference) == false && empty($booking_source) == false) {
			$master_booking_details = $GLOBALS['CI']->flight_model->get_booking_details($app_reference, $booking_source);
			if ($master_booking_details['status'] == SUCCESS_STATUS) {
				$page_data = array();
				$this->load->library('booking_data_formatter');
				$master_booking_details = $this->booking_data_formatter->format_flight_booking_data($master_booking_details, 'b2c');

                                
				$page_data['data'] = $master_booking_details['data'];
				$page_data['cancellation_status'] = $cancellation_details['status'];
				$page_data['cancellation_message'] = $cancellation_details['message'];
				$this->template->view('flight/cancellation_details', $page_data);
			} else {
				redirect('security/log_event?event=Invalid Details');
			}
		} else {
			redirect('security/log_event?event=Invalid Details');
		}

	}
	/**
	 * Balu A
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
			$booking_details = $this->flight_model->get_passenger_ticket_info($app_reference, $passenger_origin, $passenger_status);
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
					$this->flight_model->update_supplier_ticket_refund_details($passenger_origin, $supplier_ticket_refund_details['data']);
				}
			}
		}
	}
	/**
	 * Balu A
	 * Displays Cancellation Ticket Details
	 */
	public function ticket_cancellation_details()
	{
		$get_data = $this->input->get();
		if(isset($get_data['app_reference']) == true && isset($get_data['booking_source']) == true && isset($get_data['status']) == true){
			$app_reference = trim($get_data['app_reference']);
			$booking_source = trim($get_data['booking_source']);
			$status = trim($get_data['status']);
			$booking_details = $this->flight_model->get_booking_details($app_reference, $booking_source, $status);
			 // debug($booking_details);exit;
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
				$this->template->view('flight/ticket_cancellation_details', $page_data);
			} else {
				redirect(base_url());
			}
		} else {
			redirect(base_url());
		}
	}
	/**
	 * Balu A
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
			$booking_details = $this->flight_model->get_passenger_ticket_info($app_reference, $passenger_origin, $passenger_status);
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
				$this->template->view('flight/cancellation_refund_details', $page_data);
			} else {
				redirect(base_url());
			}
		} else {
			redirect(base_url());
		}
	}
	/**
	 * Balu A
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
			$booking_details = $this->flight_model->get_passenger_ticket_info($app_reference, $passenger_origin, $passenger_status);
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
		redirect('flight/cancellation_refund_details?'.http_build_query($redirect_url_params));
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

				$booked_user_details = $this->flight_model->get_booked_user_details($app_reference);
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
	 * Balu A
	 */
	function exception()
	{
		$module = META_AIRLINE_COURSE;
		$op = @$_GET['op'];
		$notification = @$_GET['notification'];
		$eid = $this->module_model->log_exception($module, $op, $notification);
		//set ip log session before redirection
		$this->session->set_flashdata(array('log_ip_info' => true));
		redirect(base_url().'index.php/flight/event_logger/'.$eid);
	}

	function event_logger($eid='')
	{
		$log_ip_info = $this->session->flashdata('log_ip_info');
		$this->template->view('flight/exception', array('log_ip_info' => $log_ip_info, 'eid' => $eid));
	}
    function exception_log_details() {
        $get_data = $this->input->get();
        
        $result=$this->flight_model->exception_log_details($get_data);
        if($result=="null")
        {   $res['Status']=0;
            $res['Message']='Booking may confirmed, Please contact API support team';
            echo json_encode($res);
        }else {
        echo $result;exit;
        }
    }
     /*
     *
     * Flight(Airport) auto suggest
     *
     */
function get_airport_transfer_code_list() {

        $result = array();
        $this->load->model('hotel_model');
        $this->load->model('transfer_model');
        $term = $this->input->get('term'); //retrieve the search term that autocomplete sends
        $term = trim(strip_tags($term));
        
        $airport_data_list = $this->transfer_model->get_airport_list($term)->result();

        if (valid_array($airport_data_list) == false) {
            $airport_data_list = $this->transfer_model->get_airport_list('$term')->result();
        }

        foreach ($airport_data_list as $airport) {
            $airport_result['label'] = $airport->airport_name . ' (' . $airport->airport_city . ')';
            $airport_result['id'] = $airport->airport_code;

            $airport_result['transfer_type'] = "ProductTransferTerminal";

            $airport_result ['category'] = array();
            $airport_result ['type'] = array();
            array_push($result, $airport_result);
        }

        $this->output_compressed_data($result);
    }
    function get_airport_code_list() {

        $term = $this->input->get('term'); //retrieve the search term that autocomplete sends
        $term = trim(strip_tags($term));
        $result = array();
        
        $__airports = $this->flight_model->get_airport_list($term)->result();
        if (valid_array($__airports) == false) {
            $__airports = $this->flight_model->get_airport_list('')->result();
        }
       
        $airports = array();
        foreach ($__airports as $airport) {
         	$airports['label'] = $airport->airport_city . ', ' . $airport->country . ' (' . $airport->airport_code . ')';
            $airports['value'] = $airport->airport_city . ' (' . $airport->airport_code . ')';
            $airports['id'] = $airport->origin;
            
            // if (empty($airport->top_destination) == false) {
            //     $airports['category'] = 'Top cities';
            //     $airports['type'] = 'Top cities';
            // } else {
            //     $airports['category'] = 'Search Results';
            //     $airports['type'] = 'Search Results';
            // }
            $airports['category'] = 'Search Results';
            $airports['type'] = 'Search Results';
            array_push($result, $airports);
        }
        $this->output_compressed_data($result);
    }
     /**
     * Compress and output data
     * @param array $data
     */
    private function output_compressed_data($data) {


        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        ob_start("ob_gzhandler");
        header('Content-type:application/json');
        echo json_encode($data);
        ob_end_flush();
        exit;
    }
}
