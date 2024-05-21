<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @package    Provab
 * @subpackage Bus
 * @author     Balu A<balu.provab@gmail.com>
 * @version    V1
 */
error_reporting(0);
class Voucher extends CI_Controller {
	private $current_module;
	public function __construct()
	{
		parent::__construct();
		$this->load->library('booking_data_formatter');
		$this->load->library('provab_mailer');
		$this->current_module = $this->config->item('current_module');
		//$this->load->library('provab_pdf');
		
		//we need to activate bus api which are active for current domain and load those libraries
		//$this->output->enable_profiler(TRUE);
	}

	/**
	 *
	 */
	function bus($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$email='')
	{
		//error_reporting(E_ALL);

		// debug($app_reference);
		// debug($booking_source);
		// debug($booking_status);
		// debug($operation);
		// debug($email);
		// die;
		//echo 'under working';exit;
		
		$this->load->model('bus_model');
		if (empty($app_reference) == false) {
			$booking_details = $this->bus_model->get_booking_details($app_reference, $booking_source, $booking_status);
			if ($booking_details['status'] == SUCCESS_STATUS) {
				//Assemble Booking Data

				$agent_status=0;
				if(isset($booking_details['data']['booking_details'][0]['created_by_id']) && !empty($booking_details['data']['booking_details'][0]['created_by_id']))
					{
						$get_agent_info = $this->user_model->get_agent_info($booking_details['data']['booking_details'][0]['created_by_id']);
						if(!empty($get_agent_info)){
							$agent_status=1;
							$assembled_booking_details = $this->booking_data_formatter->format_bus_booking_data($booking_details, 'b2b');
						}else{
							$assembled_booking_details = $this->booking_data_formatter->format_bus_booking_data($booking_details, 'b2c');

						}
					}else{
						$assembled_booking_details = $this->booking_data_formatter->format_bus_booking_data($booking_details, 'b2c');
					}
				// $assembled_booking_details = $this->booking_data_formatter->format_bus_booking_data($booking_details, $this->current_module);
				$page_data['data'] = $assembled_booking_details['data'];
				
				if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
					$domain_address = $this->custom_db->single_table_records ('domain_list','address,domain_logo,phone,domain_name',array('origin'=>get_domain_auth_id()));
					$page_data['data']['address'] =$domain_address['data'][0]['address'];
					$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
					$page_data['data']['phone'] = $domain_address['data'][0]['phone'];
					$page_data['data']['domainname'] = $domain_address['data'][0]['domain_name'];

					if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
						$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
						if(!empty($get_agent_info)){
							$page_data['data']['address'] = $get_agent_info[0]['address'];
							$page_data['data']['logo'] = $get_agent_info[0]['logo'];
							$page_data['data']['phone'] = $get_agent_info[0]['phone'];
							$page_data['data']['domainname'] = $get_agent_info[0]['agency_name'];

						}
					}
				
				}

				$agent_info = "select * from corporate_user_details as C join user as U on C.user_oid=U.user_id where U.user_type=".CORPORATE_USER;
		$agent_info=$this->db->query($agent_info)->result_array();
		$page_data['domain_ddta']=$agent_info;

		// debug($page_data);
		// die;
				
				switch ($operation) {
					case 'show_voucher' : $this->template->view('voucher/bus_voucher', $page_data);
					break;
					case 'show_pdf' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$get_view=$this->template->isolated_view('voucher/bus_pdf', $page_data);
						//debug($get_view);die;
						$create_pdf->create_pdf($get_view,'show');					
						break;
						
					case 'email_voucher' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$mail_template = $this->template->isolated_view('voucher/bus_pdf', $page_data);
						$pdf = $create_pdf->create_pdf($mail_template,'');
						$this->provab_mailer->send_mail($email, domain_name().' - Bus Ticket',$mail_template ,$pdf);
						break;
				}
			} else {
				redirect('security/log_event?event=Invalid AppReference');
			}
		} else {
			redirect('security/log_event?event=Invalid AppReference');
		}
	}


function transfer_crs($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$email=''){


	// echo phpinfo();exit();
     // error_reporting(E_ALL);
// debug($app_reference.'__'.$booking_source.'__'.$booking_status.'__'.$operation);exit;
		$this->load->model('transfers_model');

		if (empty($app_reference) == false) {

			// debug($app_reference);
			// debug($booking_source);exit;
			// debug($booking_status);exit;
			$booking_details = $this->transfers_model->get_booking_details_transfer($app_reference, $booking_source, $booking_status);
			// debug($booking_details);exit;
			$agent_id = $booking_details['data']['booking_details'][0]['created_by_id'];
			$get_agent_info = $this->user_model->get_agent_info($agent_id);
			//$get_agent_info = $this->user_model->get_agent_info($page_data['data']['booking_details'][0]['created_by_id']);
			//$get_admin_info = $this->user_model->get_admin_info($agent_id);
		//	$get_staff_info = $this->user_model->get_staff_info($agent_id);
			
			// debug($get_agent_info);exit;
  
			// debug($this->db->last_query());exit;
        // $package_details = $GLOBALS['CI']->transfer_model->transfersearch($safe_search_data,$weekday,$price_id);
		$transfer_id = $booking_details['data']['booking_details'][0]['tours_id'];
		$transfer_details = $GLOBALS['CI']->transfers_model->get_transfer_data($transfer_id);
        $package_details = $GLOBALS['CI']->transfers_model->get_transfer_details($app_reference);

			if ($booking_details['status'] == SUCCESS_STATUS) {
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_sightseeing_booking_data_hb($booking_details, 'b2b');
               // debug($assembled_booking_details);exit;
				
				$page_data['package_details'] = $package_details;
				$page_data['transfer_details'] = $transfer_details;
				$page_data['data'] = $assembled_booking_details['data'];
				$page_data['data']['agent_info'] = $get_agent_info[0];

				//$page_data['data']['get_agent_info'] = $get_agent_info;
					$page_data['data']['get_staff_info'] = $get_staff_info;
					$page_data['data']['get_admin_info'] = $get_admin_info;
                if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
				
					$domain_address = $this->custom_db->single_table_records ('domain_list','address,domain_logo,phone,domain_name',array('origin'=>get_domain_auth_id()));
					$page_data['data']['address'] =$domain_address['data'][0]['address'];
					$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
					$page_data['data']['phone'] = $domain_address['data'][0]['phone'];
					$page_data['data']['domainname'] = $domain_address['data'][0]['domain_name'];
				}

				// debug($page_data); die;

				switch ($operation) {

					case 'show_voucher' : $this->template->view('voucher/transfer_voucher_crs', $page_data);
					break;
					case 'show_transfer_details' : $this->template->view('voucher/transfer_booking_view', $page_data);
					break;
					case 'show_invoice' : $this->template->view('voucher/transfer_invoice_crs', $page_data);  
					break;
					case 'show_vat_invoice' : $this->template->view('voucher/transfer_vat_invoice_crs', $page_data);  
					break;
					case 'show_amendment' : $this->template->view('transfers/transfer_amendment', $page_data);
					break;
					case 'show_details' : $this->template->view('transfers/transfer_view', $page_data);
					break;
					case 'show_pdf' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();

						$get_view=$this->template->isolated_view('voucher/transfer_voucher_crs_pdf', $page_data);
						$create_pdf->create_pdf($get_view,'show');
						break;
					case 'email_voucher' :
						$page_data['menu'] = 0;
						//$email = $this->input->post('email');
						 //debug($email);exit();
						$this->load->library ( 'provab_pdf' );
						// debug($voucher_data);exit();
						$mail_template =$this->template->isolated_view('voucher/transfer_voucher_crs_pdf',$page_data);
						$mail_template_pdf =$this->template->isolated_view('voucher/transfer_voucher_crs_pdf',$page_data);
						$this->load->library ( 'provab_pdf' );
						$pdf = $this->provab_pdf->create_pdf($mail_template_pdf,'F',$app_reference);
						$this->provab_mailer->send_mail($email, 'Transfer Ticket', $mail_template,$pdf);
						// set_update_message('UL00101');
						// redirect ( 'voucher/holiday/'.$app_reference."/".$booking_status."/"."show_voucher");
				    break;
				}
			}
		}
	}

	function b2e_bus_voucher($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$email='')
	{
		$this->load->model('bus_model');
		if (empty($app_reference) == false) 
		{
			$booking_details = $this->bus_model->get_booking_details($app_reference, $booking_source, $booking_status);
			if ($booking_details['status'] == SUCCESS_STATUS || $booking_details['status']==0) 
			{
				// debug($booking_details);die;
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_bus_booking_data($booking_details, 'corporate');
				$page_data['data'] = $assembled_booking_details['data'];
				$booked_by = $assembled_booking_details['data']['booking_details'][0]['created_by_id'];
				$user_data=$this->db->get_where('user',array('user_id'=>$booked_by))->row();
				$user_id=($user_data->user_type==CORPORATE_USER || $user_data->user_type==SUB_CORPORATE)?$user_data->user_id:$user_data->corporate_id;		
				$corporate_info = $this->user_model->get_corporate_detail($user_id);
				if(!empty($corporate_info))
				{
					$page_data['data']['address'] = $corporate_info['address'];
					$page_data['data']['logo'] = $corporate_info['logo'];
					$page_data['data']['phone'] = $corporate_info['phone'];
					$page_data['data']['domainname'] = $corporate_info['agency_name'];
				}
				// debug($page_data);die;
				switch ($operation) {
					case 'show_voucher' :
						$page_data['button'] = ACTIVE;
						$page_datap['image'] = ACTIVE;
						$this->template->view('voucher/b2e_bus_voucher', $page_data);
					break;
					case 'show_pdf' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$get_view=$this->template->isolated_view('voucher/b2e_bus_pdf', $page_data);
						$create_pdf->create_pdf($get_view,'show');
					case 'email_voucher' :
					//die("email_voucher");
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$mail_template = $this->template->isolated_view('voucher/b2e_bus_pdf', $page_data);
						$pdf = $create_pdf->create_pdf($mail_template,'');
						//die("23");
						$this->provab_mailer->send_mail($email, domain_name().' - Bus Ticket',$mail_template ,$pdf);
						//die("gg");
						break;
						case 'show_email_voucher':
						$email_id=$page_data['data']['booking_details'][0]['email'];
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();

						$mail_template = $this->template->isolated_view('voucher/b2e_bus_pdf', $page_data);
						
						$pdf = $create_pdf->create_pdf($mail_template,'');
						// debug($pdf);exit;
						$this->provab_mailer->send_mail($email_id, domain_name().' - Bus Ticket',$mail_template ,$pdf);

						$this->template->view('voucher/b2e_bus_voucher', $page_data);
						break;
					
				}
			}
		}
	}

	

 
	/**
	 *
	 */
	function hotel($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$email='')
	{
		$this->load->model('hotel_model');
		if (empty($app_reference) == false) {
			$booking_details = $this->hotel_model->get_booking_details($app_reference, $booking_source, $booking_status);
			//debug($booking_details)
			if ($booking_details['status'] == SUCCESS_STATUS) {
				//Assemble Booking Data
				$agent_status=0;
				if(isset($booking_details['data']['booking_details'][0]['created_by_id']) && !empty($booking_details['data']['booking_details'][0]['created_by_id']))
					{
						$get_agent_info = $this->user_model->get_agent_info($booking_details['data']['booking_details'][0]['created_by_id']);
						if(!empty($get_agent_info)){
							$agent_status=1;
							$assembled_booking_details = $this->booking_data_formatter->format_hotel_booking_data($booking_details, 'b2b');
						}else{
							$assembled_booking_details = $this->booking_data_formatter->format_hotel_booking_data($booking_details, 'b2c');

						}
					}else{
						$assembled_booking_details = $this->booking_data_formatter->format_hotel_booking_data($booking_details, 'b2c');
					}
$hc=json_decode($assembled_booking_details['data']['booking_details'][0]['attributes']);
			$page_data['supplier_details'] = $this->hotel_model->get_supplier_details($hc->HotelCode);
				// $assembled_booking_details = $this->booking_data_formatter->format_hotel_booking_data($booking_details, $this->current_module);
				$page_data['data'] = $assembled_booking_details['data'];
				if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
				
					$domain_address = $this->custom_db->single_table_records ('domain_list','address,domain_logo,phone,domain_name',array('origin'=>get_domain_auth_id()));
					$page_data['data']['address'] =$domain_address['data'][0]['address'];
					$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
					$page_data['data']['phone'] = $domain_address['data'][0]['phone'];
					$page_data['data']['domainname'] = $domain_address['data'][0]['domain_name'];

					if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
						$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
						if(!empty($get_agent_info)){
							$page_data['data']['address'] = $get_agent_info[0]['address'];
							$page_data['data']['logo'] = $get_agent_info[0]['logo'];
							$page_data['data']['phone'] = $get_agent_info[0]['phone'];
							$page_data['data']['domainname'] = $get_agent_info[0]['agency_name'];

						}
					}
				
				}
				//debug($page_data);exit;
				switch ($operation) {
					case 'show_voucher' : $this->template->view('voucher/hotel_voucher', $page_data);
					break;
					case 'show_pdf' :						
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$get_view=$this->template->isolated_view('voucher/hotel_pdf', $page_data);						
						$create_pdf->create_pdf($get_view,'show');
						
					break;
					case 'email_voucher' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$mail_template = $this->template->isolated_view('voucher/hotel_pdf', $page_data);
						$pdf = $create_pdf->create_pdf($mail_template,'');
						$this->provab_mailer->send_mail($email, domain_name().' - Hotel Ticket',$mail_template ,$pdf);
						break;
				}
			}
		}
	}
	function b2e_hotel_voucher($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$email='')
	{
		$this->load->model('hotel_model');
		if (empty($app_reference) == false) {
			$booking_details = $this->hotel_model->get_booking_details($app_reference, $booking_source, $booking_status);
			if ($booking_details['status'] == SUCCESS_STATUS) {
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_hotel_booking_data($booking_details, 'corporate');
				$page_data['data'] = $assembled_booking_details['data'];
				$booked_by = $assembled_booking_details['data']['booking_details'][0]['created_by_id'];
				$user_data=$this->db->get_where('user',array('user_id'=>$booked_by))->row();
				$user_id=($user_data->user_type==CORPORATE_USER || $user_data->user_type==SUB_CORPORATE)?$user_data->user_id:$user_data->corporate_id;		
				$corporate_info = $this->user_model->get_corporate_detail($user_id);
				if(!empty($corporate_info))
				{
					$page_data['data']['address'] = $corporate_info['address'];
					$page_data['data']['logo'] = $corporate_info['logo'];
					$page_data['data']['phone'] = $corporate_info['phone'];
					$page_data['data']['domainname'] = $corporate_info['agency_name'];
				}
				//debug($page_data);die;
				switch ($operation) {
					case 'show_voucher' : $this->template->view('voucher/b2e_hotel_voucher', $page_data);
					break;
					case 'show_pdf' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$get_view=$this->template->isolated_view('voucher/b2e_hotel_pdf', $page_data);
						$create_pdf->create_pdf($get_view,'show');
					case 'email_voucher' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$mail_template = $this->template->isolated_view('voucher/b2e_hotel_pdf', $page_data);
						$pdf = $create_pdf->create_pdf($mail_template,'');
						$this->provab_mailer->send_mail($email, domain_name().' - Hotel Ticket',$mail_template ,$pdf);
						break;
					case 'show_email_voucher':
						$email_id=$page_data['data']['booking_details'][0]['email'];
						// debug($email_id);exit();
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();

						$mail_template = $this->template->isolated_view('voucher/b2e_hotel_pdf', $page_data);
						// debug($mail_template);exit("df");
						$pdf = $create_pdf->create_pdf($mail_template,'');
						// debug($pdf);exit;
						$this->provab_mailer->send_mail($email_id, domain_name().' - Hotel Ticket',$mail_template ,$pdf);

						$this->template->view('voucher/b2e_hotel_voucher', $page_data);
					break;
							
				}
			}
		}
	}
	/**
	 *Sightseeing Voucher
	 */
	function sightseeing($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$email='')
	{	
		
		$this->load->model('sightseeing_model');
		
		if (empty($app_reference) == false) {
			$booking_details = $this->sightseeing_model->get_booking_details($app_reference, $booking_source, $booking_status);
			
			if ($booking_details['status'] == SUCCESS_STATUS) {
				//Assemble Booking Data

				$agent_status=0;
				if(isset($booking_details['data']['booking_details'][0]['created_by_id']) && !empty($booking_details['data']['booking_details'][0]['created_by_id']))
					{
						$get_agent_info = $this->user_model->get_agent_info($booking_details['data']['booking_details'][0]['created_by_id']);
						if(!empty($get_agent_info)){
							$agent_status=1;
							$assembled_booking_details = $this->booking_data_formatter->format_sightseeing_booking_data($booking_details, 'b2b');
						}else{
							$assembled_booking_details = $this->booking_data_formatter->format_sightseeing_booking_data($booking_details, 'b2c');

						}
					}else{
						$assembled_booking_details = $this->booking_data_formatter->format_sightseeing_booking_data($booking_details, 'b2c');
					}
				// $assembled_booking_details = $this->booking_data_formatter->format_sightseeing_booking_data($booking_details, $this->current_module);

				$page_data['data'] = $assembled_booking_details['data'];
				if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
				
					$domain_address = $this->custom_db->single_table_records ('domain_list','address,domain_logo,phone,domain_name',array('origin'=>get_domain_auth_id()));
					$page_data['data']['address'] =$domain_address['data'][0]['address'];
					$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
					$page_data['data']['phone'] = $domain_address['data'][0]['phone'];
					$page_data['data']['domainname'] = $domain_address['data'][0]['domain_name'];
					if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
						$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
						if(!empty($get_agent_info)){
							$page_data['data']['address'] = $get_agent_info[0]['address'];
							$page_data['data']['logo'] = $get_agent_info[0]['logo'];
							$page_data['data']['phone'] = $get_agent_info[0]['phone'];
							$page_data['data']['domainname'] = $get_agent_info[0]['agency_name'];

						}
					}
				
				}
				//debug($page_data);exit;
				switch ($operation) {
					case 'show_voucher' : $this->template->view('voucher/sightseeing_voucher', $page_data);
					break;
					case 'show_pdf' :						
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$get_view=$this->template->isolated_view('voucher/sightseeing_pdf', $page_data);						
						$create_pdf->create_pdf($get_view,'show');
						
					break;
					case 'email_voucher' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$mail_template = $this->template->isolated_view('voucher/sightseeing_pdf', $page_data);
						$pdf = $create_pdf->create_pdf($mail_template,'');
						$this->provab_mailer->send_mail($email, domain_name().' - Activity Ticket',$mail_template ,$pdf);
						break;
				}
			}
		}
	}
	function b2e_sightseeing_voucher($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$email='')
	{
		//error_reporting(E_ALL);
		$this->load->model('sightseeing_model');

		if (empty($app_reference) == false) 
		{
			$booking_details = $this->sightseeing_model->get_booking_details($app_reference, $booking_source, $booking_status);

			
			if ($booking_details['status'] == SUCCESS_STATUS) 
			{
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_sightseeing_booking_data($booking_details, 'corporate');
				$page_data['data'] = $assembled_booking_details['data'];
              	$booked_by = $assembled_booking_details['data']['booking_details'][0]['created_by_id'];
				$user_data=$this->db->get_where('user',array('user_id'=>$booked_by))->row();
				$user_id=($user_data->user_type==CORPORATE_USER || $user_data->user_type==SUB_CORPORATE)?$user_data->user_id:$user_data->corporate_id;		
				$corporate_info = $this->user_model->get_corporate_detail($user_id);
				if(!empty($corporate_info))
				{
					$page_data['data']['address'] = $corporate_info['address'];
					$page_data['data']['logo'] = $corporate_info['logo'];
					$page_data['data']['phone'] = $corporate_info['phone'];
					$page_data['data']['domainname'] = $corporate_info['agency_name'];
				}
				switch ($operation) 
				{
					case 'show_voucher' : $this->template->view('voucher/b2e_sightseeing_voucher', $page_data);
					break;
					case 'show_pdf' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$get_view=$this->template->isolated_view('voucher/b2e_sightseeing_pdf', $page_data);
						$create_pdf->create_pdf($get_view,'show');
						break;
					case 'email_voucher' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$mail_template = $this->template->isolated_view('voucher/b2e_sightseeing_pdf', $page_data);
						$pdf = $create_pdf->create_pdf($mail_template,'');
						$this->provab_mailer->send_mail($email, domain_name().' - Acitivity Ticket',$mail_template ,$pdf);
						break;
					case 'show_email_voucher':
						$email_id=$page_data['data']['booking_details'][0]['email'];
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();

						$mail_template = $this->template->isolated_view('voucher/b2e_sightseeing_pdf', $page_data);
						
						$pdf = $create_pdf->create_pdf($mail_template,'');
						// debug($pdf);exit;
						$this->provab_mailer->send_mail($email_id, domain_name().' - Sightseeing Ticket',$mail_template ,$pdf);

						$this->template->view('voucher/b2e_sightseeing_voucher', $page_data);
						break;
				}
			}
		}	
	}
	/**
	 *Sightseeing Voucher
	 */
	function transfers($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$email='')
	{	
		
		$this->load->model('transferv1_model');
		
		if (empty($app_reference) == false) {
			$booking_details = $this->transferv1_model->get_booking_details($app_reference, $booking_source, $booking_status);
			
			if ($booking_details['status'] == SUCCESS_STATUS) {
				//Assemble Booking Data
				$agent_status=0;
				if(isset($booking_details['data']['booking_details'][0]['created_by_id']) && !empty($booking_details['data']['booking_details'][0]['created_by_id']))
					{
						$get_agent_info = $this->user_model->get_agent_info($booking_details['data']['booking_details'][0]['created_by_id']);
						if(!empty($get_agent_info)){
							$agent_status=1;
							$assembled_booking_details = $this->booking_data_formatter->format_transferv1_booking_data($booking_details, 'b2b');
						}else{
							$assembled_booking_details = $this->booking_data_formatter->format_transferv1_booking_data($booking_details, 'b2c');

						}
					}else{
						$assembled_booking_details = $this->booking_data_formatter->format_transferv1_booking_data($booking_details, 'b2c');
					}
				// $assembled_booking_details = $this->booking_data_formatter->format_transferv1_booking_data($booking_details, $this->current_module);

				$page_data['data'] = $assembled_booking_details['data'];
				if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
				
					$domain_address = $this->custom_db->single_table_records ('domain_list','address,domain_logo,phone,domain_name',array('origin'=>get_domain_auth_id()));
					$page_data['data']['address'] =$domain_address['data'][0]['address'];
					$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
					$page_data['data']['phone'] = $domain_address['data'][0]['phone'];
					$page_data['data']['domainname'] = $domain_address['data'][0]['domain_name'];

					if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
						$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
						if(!empty($get_agent_info)){
							$page_data['data']['address'] = $get_agent_info[0]['address'];
							$page_data['data']['logo'] = $get_agent_info[0]['logo'];
							$page_data['data']['phone'] = $get_agent_info[0]['phone'];
							$page_data['data']['domainname'] = $get_agent_info[0]['agency_name'];
						}
					}
				
				}
				//debug($page_data);exit;
				switch ($operation) {
					case 'show_voucher' : $this->template->view('voucher/transfer_voucher', $page_data);
					break;
					case 'show_pdf' :						
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$get_view=$this->template->isolated_view('voucher/transfer_pdf', $page_data);						
						$create_pdf->create_pdf($get_view,'show');
						
					break;
					case 'email_voucher' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$mail_template = $this->template->isolated_view('voucher/transfer_pdf', $page_data);
						$pdf = $create_pdf->create_pdf($mail_template,'');
						$this->provab_mailer->send_mail($email, domain_name().' - Transfers Ticket',$mail_template ,$pdf);
						break;
				}
			}
		}
	}
	
	function flight($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$email='')
	{
		
		$this->load->model('flight_model');
		if (empty($app_reference) == false) {
			$booking_details = $this->flight_model->get_booking_details($app_reference, $booking_source, $booking_status);
			if ($booking_details['status'] == SUCCESS_STATUS) {
				load_flight_lib(PROVAB_FLIGHT_BOOKING_SOURCE);
				//Assemble Booking Data
				$agent_status=0;
				if(isset($booking_details['data']['booking_details'][0]['created_by_id']) && !empty($booking_details['data']['booking_details'][0]['created_by_id']))
					{
						$get_agent_info = $this->user_model->get_agent_info($booking_details['data']['booking_details'][0]['created_by_id']);
						if(!empty($get_agent_info)){
							$agent_status=1;
							$assembled_booking_details = $this->booking_data_formatter->format_flight_booking_data($booking_details, 'b2b');
						}else{
							$assembled_booking_details = $this->booking_data_formatter->format_flight_booking_data($booking_details, 'b2c');

						}
					}else{
						$assembled_booking_details = $this->booking_data_formatter->format_flight_booking_data($booking_details, 'b2c');
					}	

				// $assembled_booking_details = $this->booking_data_formatter->format_flight_booking_data($booking_details, 'b2c', false);
				//$assembled_booking_details = $this->booking_data_formatter->format_flight_booking_data($booking_details, $this->current_module);				
				$page_data['data'] = $assembled_booking_details['data'];
				
			if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
					
					$domain_address = $this->custom_db->single_table_records ( 'domain_list','address,domain_logo,phone,domain_name',array('origin'=>get_domain_auth_id()));
					$page_data['data']['address'] =$domain_address['data'][0]['address'];
					$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
					$page_data['data']['phone'] =$domain_address['data'][0]['phone'];
					$page_data['data']['domainname'] =$domain_address['data'][0]['domain_name'];
					if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
						$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
						// debug($get_agent_info);exit;
						if(!empty($get_agent_info)){
							$page_data['data']['address'] = $get_agent_info[0]['address'];
							$page_data['data']['logo'] = $get_agent_info[0]['logo'];
							$page_data['data']['phone'] = $get_agent_info[0]['phone'];
							$page_data['data']['domainname'] = $get_agent_info[0]['agency_name'];
						}
					}
			
				}
				//debug($page_data);exit;
				switch ($operation) {
					case 'show_voucher' : $this->template->view('voucher/flight_voucher', $page_data);
					break;
					case 'show_pdf' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						if($get_agent_info['0']['user_type']==B2B_USER)
						{
							$get_view = $this->template->isolated_view('voucher/b2bflight_pdf', $page_data);
						}else{

							$get_view=$this->template->isolated_view('voucher/flight_pdf', $page_data);
						}
						//debug($get_view);exit;
						$create_pdf->create_pdf($get_view,'show');
						break;
				   case 'email_voucher':
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						if($get_agent_info['0']['user_type']==3)
						{
							$mail_template = $this->template->isolated_view('voucher/b2bflight_pdf', $page_data);
						}else{

							$mail_template = $this->template->isolated_view('voucher/flight_pdf', $page_data);
						}
						$pdf = $create_pdf->create_pdf($mail_template,'');
						$this->provab_mailer->send_mail($email, domain_name().' - Flight Ticket',$mail_template ,$pdf);
						break;
				}
			}
		}
	}

		function b2e_flight_voucher($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$email='')
	{
		
		$this->load->model('flight_model');
		if (empty($app_reference) == false) {
			$booking_details = $this->flight_model->get_booking_details($app_reference, $booking_source, $booking_status);
			if ($booking_details['status'] == SUCCESS_STATUS) {
				load_flight_lib(PROVAB_FLIGHT_BOOKING_SOURCE);
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_flight_booking_data($booking_details, 'corporate');	
				$page_data['data'] = $assembled_booking_details['data'];
				$booked_by = $assembled_booking_details['data']['booking_details'][0]['created_by_id'];
				$user_data=$this->db->get_where('user',array('user_id'=>$booked_by))->row();
				$user_id=($user_data->user_type==CORPORATE_USER || $user_data->user_type==SUB_CORPORATE)?$user_data->user_id:$user_data->corporate_id;		
				$corporate_info = $this->user_model->get_corporate_detail($user_id);
		// debug($corporate_info);die;
				if(!empty($corporate_info))
				{
					$page_data['data']['address'] = $corporate_info['address'];
					$page_data['data']['logo'] = 	$corporate_info['logo'];
					$page_data['data']['phone'] = $corporate_info['phone'];
					$page_data['data']['domainname'] = $corporate_info['agency_name'];
				}
				//debug($page_data);exit;
				switch ($operation) {
					case 'show_voucher' : $this->template->view('voucher/b2e_flight_voucher', $page_data);
					break;
					case 'show_pdf' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$get_view=$this->template->isolated_view('voucher/b2e_flight_pdf', $page_data);
						$create_pdf->create_pdf($get_view,'show');
						break;
					case 'email_voucher':
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$mail_template = $this->template->isolated_view('voucher/b2e_flight_pdf', $page_data);
						$pdf = $create_pdf->create_pdf($mail_template,'');
						$this->provab_mailer->send_mail($email, domain_name().' - Flight Ticket',$mail_template ,$pdf);
						break;
					case 'show_email_voucher':
						$email_id=$page_data['data']['booking_details_app'][$app_reference]['email'];
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						//debug($email_id);exit;

						$mail_template = $this->template->isolated_view('voucher/b2e_flight_pdf', $page_data);
						//debug($mail_template);exit;
						$pdf = $create_pdf->create_pdf($mail_template,'');
						//debug($pdf);
						//exit;
						$a=$this->provab_mailer->send_mail($email_id, domain_name().' - Flight Ticket',$mail_template ,$pdf);
						// debug($a);exit;
						$this->template->view('voucher/b2e_flight_voucher', $page_data);
						break;
				}
			}
		}
	}

	function b2c_flight_voucher($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$email='')
	{
		$this->load->model('flight_model');
		if (empty($app_reference) == false) {
			$booking_details = $this->flight_model->get_booking_details($app_reference, $booking_source, $booking_status);
			
			if ($booking_details['status'] == SUCCESS_STATUS) {
				load_flight_lib(PROVAB_FLIGHT_BOOKING_SOURCE);
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_flight_booking_data($booking_details, 'b2c', false);				
				$page_data['data'] = $assembled_booking_details['data'];
				
			if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
					
					$domain_address = $this->custom_db->single_table_records ( 'domain_list','address,domain_logo,domain_name,phone',array('origin'=>get_domain_auth_id()));
					$page_data['data']['address'] =$domain_address['data'][0]['address'];
					$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
					// $page_data['data']['address'] =$domain_address['data'][0]['address'];
					$page_data['data']['phone'] =$domain_address['data'][0]['phone'];
					$page_data['data']['domainname'] =$domain_address['data'][0]['domain_name'];
					// debug($assembled_booking_details);exit;
					if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
						$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
						// debug($get_agent_info);exit;
						if(!empty($get_agent_info)){
						$page_data['data']['address'] = $get_agent_info[0]['address'];
						$page_data['data']['logo'] = $get_agent_info[0]['logo'];
						}
					}
			
				}
				// debug($page_data);exit;
				switch ($operation) {
					case 'show_voucher' : $this->template->view('voucher/flight_voucher', $page_data);
					break;
					case 'show_pdf' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$get_view=$this->template->isolated_view('voucher/flight_pdf', $page_data);
						$create_pdf->create_pdf($get_view,'show');
						break;
				   case 'email_voucher':
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$mail_template = $this->template->isolated_view('voucher/flight_pdf', $page_data);
						$pdf = $create_pdf->create_pdf($mail_template,'');
						$this->provab_mailer->send_mail($email, domain_name().' - Flight Ticket',$mail_template ,$pdf);
						break;
				}
			}
		}
	}
	function b2b_flight_voucher($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$email='')
	{
		$this->load->model('flight_model');
		if (empty($app_reference) == false) {
			$booking_details = $this->flight_model->get_booking_details($app_reference, $booking_source, $booking_status);
			if ($booking_details['status'] == SUCCESS_STATUS) {
				load_flight_lib(PROVAB_FLIGHT_BOOKING_SOURCE);
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_flight_booking_data($booking_details, $this->current_module);				
				$page_data['data'] = $assembled_booking_details['data'];
				
			if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
					
					$domain_address = $this->custom_db->single_table_records ( 'domain_list','address,domain_logo,domain_name,phone',array('origin'=>get_domain_auth_id()));
					$page_data['data']['address'] =$domain_address['data'][0]['address'];
					$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
					$page_data['data']['phone'] =$domain_address['data'][0]['phone'];
					$page_data['data']['domainname'] =$domain_address['data'][0]['domain_name'];
					if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
						$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
						// debug($get_agent_info);exit;
						if(!empty($get_agent_info)){
						$page_data['data']['address'] = $get_agent_info[0]['address'];
						$page_data['data']['logo'] = $get_agent_info[0]['logo'];
						$page_data['data']['phone'] = $get_agent_info[0]['phone'];
						$page_data['data']['domainname'] = $get_agent_info[0]['agency_name'];
						}
					}
			
				}
				switch ($operation) {
					case 'show_voucher' : $this->template->view('voucher/b2bflight_voucher', $page_data);
					break;
					case 'show_pdf' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$get_view=$this->template->isolated_view('voucher/b2bflight_pdf', $page_data);
						$create_pdf->create_pdf($get_view,'show');
						break;
				   case 'email_voucher':
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$mail_template = $this->template->isolated_view('voucher/b2bflight_pdf', $page_data);
						$pdf = $create_pdf->create_pdf($mail_template,'');
						$this->provab_mailer->send_mail($email, domain_name().' - Flight Ticket',$mail_template ,$pdf);
						break;
				}
			}
		}
	}
	function b2c_hotel_voucher($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$email='')
	{
		$this->load->model('hotel_model');
		if (empty($app_reference) == false) {
			$booking_details = $this->hotel_model->get_booking_details($app_reference, $booking_source, $booking_status);
			//debug($booking_details);die;
			if ($booking_details['status'] == SUCCESS_STATUS) {
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_hotel_booking_data($booking_details, 'b2c');
			
	$hc=json_decode($assembled_booking_details['data']['booking_details'][0]['attributes']);
			$page_data['supplier_details'] = $this->hotel_model->get_supplier_details($hc->HotelCode);
				$page_data['data'] = $assembled_booking_details['data'];
				
				if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
				
					$domain_address = $this->custom_db->single_table_records ('domain_list','address,domain_logo,domain_name,phone',array('origin'=>get_domain_auth_id()));
				
					$page_data['data']['address'] =$domain_address['data'][0]['address'];
					$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];

					$page_data['data']['phone'] = $domain_address['data'][0]['phone'];
					
					$page_data['data']['domainname'] = $domain_address['data'][0]['domain_name'];
					

					if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
						$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
						if(!empty($get_agent_info)){
							$page_data['data']['address'] ='Sahamati Marga, House no. 17 Gairidhara, Kathmandu-02,Nepal';
							$page_data['data']['logo'] = $get_agent_info[0]['logo'];
							$page_data['data']['phone'] =' 9860000111 ( Viber/WhatsApp)';
							$page_data['data']['domainname'] = 'www.travelfreetravels.com';

						}
					}
				
				}
						//debug($assembled_booking_details);die;
				//debug($page_data);die;
				switch ($operation) {
					case 'show_voucher' : $this->template->view('voucher/hotel_voucher', $page_data);
					break;
					case 'show_pdf' :						
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$get_view=$this->template->isolated_view('voucher/hotel_pdf', $page_data);						
						$create_pdf->create_pdf($get_view,'show');
						
					break;
					case 'email_voucher' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$mail_template = $this->template->isolated_view('voucher/hotel_pdf', $page_data);
						$pdf = $create_pdf->create_pdf($mail_template,'');
						$this->provab_mailer->send_mail($email, domain_name().' - Hotel Ticket',$mail_template ,$pdf);
						break;
				}
			}
		}
	}
	function b2b_hotel_voucher($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$email='')
	{
		$this->load->model('hotel_model');
		if (empty($app_reference) == false) {
			$booking_details = $this->hotel_model->get_booking_details($app_reference, $booking_source, $booking_status);
			if ($booking_details['status'] == SUCCESS_STATUS) {
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_hotel_booking_data($booking_details, $this->current_module);
				$page_data['data'] = $assembled_booking_details['data'];
				if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
				
					$domain_address = $this->custom_db->single_table_records ('domain_list','address,domain_logo,phone,domain_name',array('origin'=>get_domain_auth_id()));
					$page_data['data']['address'] =$domain_address['data'][0]['address'];
					$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
					$page_data['data']['phone'] = $domain_address['data'][0]['phone'];
					$page_data['data']['domainname'] = $domain_address['data'][0]['domain_name'];

					if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
						$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
						if(!empty($get_agent_info)){
							$page_data['data']['address'] = $get_agent_info[0]['address'];
							$page_data['data']['logo'] = $get_agent_info[0]['logo'];
							$page_data['data']['phone'] = $get_agent_info[0]['phone'];
							$page_data['data']['domainname'] = $get_agent_info[0]['agency_name'];

						}
					}
				
				}
				switch ($operation) {
					case 'show_voucher' : $this->template->view('voucher/hotel_voucher', $page_data);
					break;
					case 'show_pdf' :						
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$get_view=$this->template->isolated_view('voucher/hotel_pdf', $page_data);						
						$create_pdf->create_pdf($get_view,'show');
						
					break;
					case 'email_voucher' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$mail_template = $this->template->isolated_view('voucher/hotel_pdf', $page_data);
						$pdf = $create_pdf->create_pdf($mail_template,'');
						$this->provab_mailer->send_mail($email, domain_name().' - Hotel Ticket',$mail_template ,$pdf);
						break;
				}
			}
		}
	}
	function b2c_bus_voucher($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$email='')
	{
		//echo 'under working';exit;
		$this->load->model('bus_model');
		if (empty($app_reference) == false) {
			$booking_details = $this->bus_model->get_booking_details($app_reference, $booking_source, $booking_status);
			if ($booking_details['status'] == SUCCESS_STATUS) {
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_bus_booking_data($booking_details, 'b2c');
				$page_data['data'] = $assembled_booking_details['data'];
				
				if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
					$domain_address = $this->custom_db->single_table_records ( 'domain_list','address,domain_logo',array('origin'=>get_domain_auth_id()));
					$page_data['data']['address'] =$domain_address['data'][0]['address'];
					$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
					if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
						$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
						if(!empty($get_agent_info)){
							$page_data['data']['address'] = $get_agent_info[0]['address'];
							$page_data['data']['logo'] = $get_agent_info[0]['logo'];
						}
					}
				
				}
				switch ($operation) {
					case 'show_voucher' : $this->template->view('voucher/bus_voucher', $page_data);
					break;
					case 'show_pdf' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$get_view=$this->template->isolated_view('voucher/bus_pdf', $page_data);
						$create_pdf->create_pdf($get_view,'show');					
						break;
						
					case 'email_voucher' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$mail_template = $this->template->isolated_view('voucher/bus_pdf', $page_data);
						$pdf = $create_pdf->create_pdf($mail_template,'');
						$this->provab_mailer->send_mail($email, domain_name().' - Bus Ticket',$mail_template ,$pdf);
						break;
				}
			} else {
				redirect('security/log_event?event=Invalid AppReference');
			}
		} else {
			redirect('security/log_event?event=Invalid AppReference');
		}
	}
	
	function b2b_bus_voucher($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$email='')
	{
		//echo 'under working';exit;
		$this->load->model('bus_model');
		if (empty($app_reference) == false) {
			$booking_details = $this->bus_model->get_booking_details($app_reference, $booking_source, $booking_status);
			if ($booking_details['status'] == SUCCESS_STATUS) {
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_bus_booking_data($booking_details, $this->current_module);
				$page_data['data'] = $assembled_booking_details['data'];
				
				if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
					$domain_address = $this->custom_db->single_table_records ( 'domain_list','address,domain_logo',array('origin'=>get_domain_auth_id()));
					$page_data['data']['address'] =$domain_address['data'][0]['address'];
					$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
					if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
						$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
						if(!empty($get_agent_info)){
							$page_data['data']['address'] = $get_agent_info[0]['address'];
							$page_data['data']['logo'] = $get_agent_info[0]['logo'];
						}
					}
				
				}
				switch ($operation) {
					case 'show_voucher' : $this->template->view('voucher/bus_voucher', $page_data);
					break;
					case 'show_pdf' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$get_view=$this->template->isolated_view('voucher/bus_pdf', $page_data);
						$create_pdf->create_pdf($get_view,'show');					
						break;
						
					case 'email_voucher' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$mail_template = $this->template->isolated_view('voucher/bus_pdf', $page_data);
						$pdf = $create_pdf->create_pdf($mail_template,'');
						$this->provab_mailer->send_mail($email, domain_name().' - Bus Ticket',$mail_template ,$pdf);
						break;
				}
			} else {
				redirect('security/log_event?event=Invalid AppReference');
			}
		} else {
			redirect('security/log_event?event=Invalid AppReference');
		}
	}
	  /**
     * Car Vocuher
     */
    function car($app_reference, $booking_source = '', $booking_status = '', $operation = 'show_voucher', $email ='') {
        $this->load->model('car_model');
        if (empty($app_reference) == false) {
            $booking_details = $this->car_model->get_booking_details($app_reference, $booking_source, $booking_status);
            // debug($booking_details);exit;
            if ($booking_details['status'] == SUCCESS_STATUS) {
                //Assemble Booking Data
                $assembled_booking_details = $this->booking_data_formatter->format_car_booking_datas($booking_details, 'b2c');
                // debug($assembled_booking_details);exit;
                $page_data['data'] = $assembled_booking_details['data'];
                if (isset($assembled_booking_details['data']['booking_details'][0])) {
                    //get agent address & logo for b2b voucher

                    $domain_address = $this->custom_db->single_table_records('domain_list', 'address,domain_logo', array('origin' => get_domain_auth_id()));
                    $page_data['data']['address'] = $domain_address['data'][0]['address'];
                    $page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
                   
                }
                switch ($operation) {
                    case 'show_voucher' : $this->template->view('voucher/car_voucher', $page_data);
                        break;
                    case 'show_pdf' :
                        $this->load->library('provab_pdf');
                        $create_pdf = new Provab_Pdf();
                        $get_view = $this->template->isolated_view('voucher/car_pdf', $page_data);
                        // debug($get_view);exit;
                        $create_pdf->create_pdf($get_view, 'show');

                        break;
                    case 'email_voucher' :
                        $email = $this->load->library('provab_pdf');
                        $email = @$booking_details['data']['booking_details'][0]['email'];
                        $create_pdf = new Provab_Pdf();
                        $mail_template = $this->template->isolated_view('voucher/car_pdf', $page_data);
                        $pdf = $create_pdf->create_pdf($mail_template, '');
                        $this->provab_mailer->send_mail($email, domain_name() . ' - Car Ticket', $mail_template, $pdf);
                        break;
                }
            }
        }
    }
	function flight_invoice($app_reference, $booking_source='', $booking_status='', $operation='show_voucher')
	{
		$this->load->model('flight_model');
		if (empty($app_reference) == false) {
			$data = $this->flight_model->get_booking_details($app_reference, $booking_source, $booking_status);
			//debug($data);exit;
			if ($data['status'] == SUCCESS_STATUS) {
				//depending on booking source we need to convert to view array
				load_flight_lib($data['data']['booking_details']['booking_source']);
				$page_data = $this->flight_lib->parse_voucher_data($data['data']);
				$domain_details = $this->custom_db->single_table_records('domain_list', '*', array('origin' => $page_data['booking_details']['domain_origin']));
				$page_data['domain_details'] = $domain_details['data'][0];
				switch ($operation) {
					case 'show_voucher' : $this->template->view('voucher/flight_invoice', $page_data);
					break;
				}
			}
		}
	}
	function b2c_sightseeing_voucher($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$email='')
	{
		$this->load->model('sightseeing_model');
		if (empty($app_reference) == false) {
			$booking_details = $this->sightseeing_model->get_booking_details($app_reference, $booking_source, $booking_status);
			

			// debug($booking_details);exit();
			if ($booking_details['status'] == SUCCESS_STATUS) {
				load_sightseen_lib(PROVAB_SIGHTSEEN_BOOKING_SOURCE);
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_sightseeing_booking_data($booking_details, 'b2c', false);				
				$page_data['data'] = $assembled_booking_details['data'];
				
			if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
					
					$domain_address = $this->custom_db->single_table_records ( 'domain_list','address,domain_logo,domain_name,phone',array('origin'=>get_domain_auth_id()));
					$page_data['data']['address'] =$domain_address['data'][0]['address'];
					$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
					$page_data['data']['phone'] = $domain_address['data'][0]['phone'];
					
					$page_data['data']['domainname'] = $domain_address['data'][0]['domain_name'];

					if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
						$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
						if(!empty($get_agent_info)){
							$page_data['data']['address'] = $get_agent_info[0]['address'];
							$page_data['data']['logo'] = $get_agent_info[0]['logo'];
							$page_data['data']['phone'] = $get_agent_info[0]['phone'];
							$page_data['data']['domainname'] = $get_agent_info[0]['domain_name'];
						}
					}
			
				}
				// debug($page_data);exit;
				switch ($operation) {
					case 'show_voucher' : $this->template->view('voucher/sightseeing_voucher', $page_data);
					break;
					case 'show_pdf' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();

						$get_view=$this->template->isolated_view('voucher/sightseeing_pdf', $page_data);
						
						// debug($get_view);
						$create_pdf->create_pdf($get_view,'show');
						break;
				   case 'email_voucher':
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$mail_template = $this->template->isolated_view('voucher/sightseeing_pdf', $page_data);
						$pdf = $create_pdf->create_pdf($mail_template,'');
						$this->provab_mailer->send_mail($email, domain_name().' - Activity Ticket',$mail_template ,$pdf);
						break;
				}
			}
		}
	}
	function b2b_sightseeing_voucher($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$email='')
	{
		$this->load->model('sightseeing_model');
		if (empty($app_reference) == false) {
			$booking_details = $this->sightseeing_model->get_booking_details($app_reference, $booking_source, $booking_status);
			if ($booking_details['status'] == SUCCESS_STATUS) {
				load_sightseen_lib(PROVAB_SIGHTSEEN_BOOKING_SOURCE);
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_sightseeing_booking_data($booking_details,'b2b');				
				$page_data['data'] = $assembled_booking_details['data'];
				
			if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
					
					$domain_address = $this->custom_db->single_table_records ( 'domain_list','address,domain_logo,phone,domain_name',array('origin'=>get_domain_auth_id()));
					$page_data['data']['address'] =$domain_address['data'][0]['address'];
					$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
					$page_data['data']['phone'] = $domain_address['data'][0]['phone'];
					$page_data['data']['domainname'] = $domain_address['data'][0]['domain_name'];
					if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
						$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
						if(!empty($get_agent_info)){
						$page_data['data']['address'] = $get_agent_info[0]['address'];
						$page_data['data']['logo'] = $get_agent_info[0]['logo'];
						$page_data['data']['phone'] = $get_agent_info[0]['phone'];
						$page_data['data']['domainname'] = $get_agent_info[0]['agency_name'];
						}
					}
			
				}
				switch ($operation) {
					case 'show_voucher' : $this->template->view('voucher/sightseeing_voucher', $page_data);
					break;
					case 'show_pdf' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$get_view=$this->template->isolated_view('voucher/sightseeing_pdf', $page_data);
						$create_pdf->create_pdf($get_view,'show');
						break;
				   case 'email_voucher':
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$mail_template = $this->template->isolated_view('voucher/sightseeing_pdf', $page_data);
						$pdf = $create_pdf->create_pdf($mail_template,'');
						$this->provab_mailer->send_mail($email, domain_name().' - Activity Ticket',$mail_template ,$pdf);
						break;
				}
			}
		}
	}
	function b2c_transfers_voucher($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$email='')
	{
		$this->load->model('transferv1_model');
		if (empty($app_reference) == false) {
			$booking_details = $this->transferv1_model->get_booking_details($app_reference, $booking_source, $booking_status);
			
			if ($booking_details['status'] == SUCCESS_STATUS) {
				load_transferv1_lib(PROVAB_TRANSFERV1_BOOKING_SOURCE);
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_transferv1_booking_data($booking_details, 'b2c', false);				
				$page_data['data'] = $assembled_booking_details['data'];
				
			if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
					
					$domain_address = $this->custom_db->single_table_records ( 'domain_list','address,domain_logo,phone,domain_name',array('origin'=>get_domain_auth_id()));
					$page_data['data']['address'] =$domain_address['data'][0]['address'];
					$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
					$page_data['data']['phone'] = $domain_address['data'][0]['phone'];
					
					$page_data['data']['domainname'] = $domain_address['data'][0]['domain_name'];

					if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
						$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
						if(!empty($get_agent_info)){
							$page_data['data']['address'] = $get_agent_info[0]['address'];
							$page_data['data']['logo'] = $get_agent_info[0]['logo'];
							$page_data['data']['phone'] = $get_agent_info[0]['phone'];
							$page_data['data']['domainname'] = $get_agent_info[0]['domain_name'];

						}
					}
			
				}
				//debug($page_data);exit;
				switch ($operation) {
					case 'show_voucher' : $this->template->view('voucher/transfer_voucher', $page_data);
					break;
					case 'show_pdf' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$get_view=$this->template->isolated_view('voucher/transfer_pdf', $page_data);
						$create_pdf->create_pdf($get_view,'show');
						break;
				   case 'email_voucher':
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$mail_template = $this->template->isolated_view('voucher/transfer_pdf', $page_data);
						$pdf = $create_pdf->create_pdf($mail_template,'');
						$this->provab_mailer->send_mail($email, domain_name().' - Transfers Ticket',$mail_template ,$pdf);
						break;
				}
			}
		}
	}
	function b2e_transfers_voucher($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$email='')
	{
		// error_reporting(E_ALL);
		$this->load->model('transferv1_model');
		if (empty($app_reference) == false) 
		{
			$booking_details = $this->transferv1_model->get_booking_details($app_reference, $booking_source, $booking_status);
				// debug($booking_details);die;
			
			if ($booking_details['status'] == SUCCESS_STATUS || $booking_details['status']==0) 
			{
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_transferv1_booking_data($booking_details, 'corporate');
				

				$page_data['data'] = $assembled_booking_details['data'];
				$booked_by = $assembled_booking_details['data']['booking_details'][0]['created_by_id'];
				$user_data=$this->db->get_where('user',array('user_id'=>$booked_by))->row();
				$user_id=($user_data->user_type==CORPORATE_USER || $user_data->user_type==SUB_CORPORATE)?$user_data->user_id:$user_data->corporate_id;		
				$corporate_info = $this->user_model->get_corporate_detail($user_id);
				if(!empty($corporate_info))
				{
					$page_data['data']['address'] = $corporate_info['address'];
					$page_data['data']['logo'] = $corporate_info['logo'];
					$page_data['data']['phone'] = $corporate_info['phone'];
					$page_data['data']['domainname'] = $corporate_info['agency_name'];
				}
      
				switch ($operation) {
					case 'show_voucher' : $this->template->view('voucher/b2e_transferv1_voucher', $page_data);
					break;
					case 'show_pdf' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$get_view=$this->template->isolated_view('voucher/b2e_transferv1_pdf', $page_data);
						$create_pdf->create_pdf($get_view,'show');
						break;
					case 'email_voucher' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$mail_template = $this->template->isolated_view('voucher/b2e_transferv1_pdf', $page_data);
						$pdf = $create_pdf->create_pdf($mail_template,'');
						$this->provab_mailer->send_mail($email, domain_name().' - Transfers Ticket',$mail_template ,$pdf);
						break;
					case 'show_email_voucher':
						$email_id=$page_data['data']['booking_details'][0]['email'];
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();

						$mail_template = $this->template->isolated_view('voucher/b2e_transferv1_pdf', $page_data);
						
						$pdf = $create_pdf->create_pdf($mail_template,'');
						// debug($pdf);exit;
						$this->provab_mailer->send_mail($email_id, domain_name().' - Transfers Ticket',$mail_template ,$pdf);

						$this->template->view('voucher/b2e_transferv1_voucher', $page_data);
						break;
				}
			}
		}
	}
	function b2b_transfers_voucher($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$email='')
	{
		$this->load->model('transferv1_model');
		if (empty($app_reference) == false) {
			$booking_details = $this->transferv1_model->get_booking_details($app_reference, $booking_source, $booking_status);
			if ($booking_details['status'] == SUCCESS_STATUS) {
				load_transferv1_lib(PROVAB_TRANSFERV1_BOOKING_SOURCE);
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_transferv1_booking_data($booking_details, $this->current_module);				
				$page_data['data'] = $assembled_booking_details['data'];
				
			if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
					
					$domain_address = $this->custom_db->single_table_records ( 'domain_list','address,domain_logo,phone,domain_name',array('origin'=>get_domain_auth_id()));
					$page_data['data']['address'] =$domain_address['data'][0]['address'];
					$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
					$page_data['data']['phone'] = $domain_address['data'][0]['phone'];
					$page_data['data']['domainname'] = $domain_address['data'][0]['domain_name'];

					if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
						$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
						if(!empty($get_agent_info)){
							$page_data['data']['address'] = $get_agent_info[0]['address'];
							$page_data['data']['logo'] = $get_agent_info[0]['logo'];
							$page_data['data']['phone'] = $get_agent_info[0]['phone'];
							$page_data['data']['domainname'] = $get_agent_info[0]['agency_name'];
						}
					}
			
				}
				switch ($operation) {
					case 'show_voucher' : $this->template->view('voucher/transfer_voucher', $page_data);
					break;
					case 'show_pdf' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$get_view=$this->template->isolated_view('voucher/transfer_pdf', $page_data);
						$create_pdf->create_pdf($get_view,'show');
						break;
				   case 'email_voucher':
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$mail_template = $this->template->isolated_view('voucher/transfer_pdf', $page_data);
						$pdf = $create_pdf->create_pdf($mail_template,'');
						$this->provab_mailer->send_mail($email, domain_name().' - Transfers Ticket',$mail_template ,$pdf);
						break;
				}
			}
		}
	}


//Chalapathi

	function b2c_holiday_voucher($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$email='')
	{
		
		$this->load->model('package_model');
		if (empty($app_reference) == false) {
			$booking_details = $this->package_model->get_booking_details($app_reference, $booking_source, $booking_status);
			if ($booking_details['status'] == SUCCESS_STATUS) {
				// load_flight_lib(PROVAB_FLIGHT_BOOKING_SOURCE);
				// //Assemble Booking Data
				// $assembled_booking_details = $this->booking_data_formatter->format_flight_booking_data($booking_details, 'b2c');	
				// $page_data['data'] = $assembled_booking_details['data'];
				 // if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
					
					$domain_address = $this->custom_db->single_table_records ( 'domain_list','address,domain_logo',array('origin'=>get_domain_auth_id()));
					$page_data['address'] =$domain_address['data'][0]['address'];
					$page_data['logo'] = $domain_address['data'][0]['domain_logo'];

					$vc_module = '';
					$page_data['details'] = $booking_details;
					if($page_data['details']['booking_details'][0]['module_type'] == 'activity'){
						$vc_module = 'Activity';
					}else if($page_data['details']['booking_details'][0]['module_type'] == 'transfers'){

						$vc_module = 'Transfer';
					}

					$page_data['details'] = $booking_details;

					$terms_condition = $this->custom_db->single_table_records ( 'terms_n_condition','terms_n_conditions,cancellation_policy',array('module_name'=>$vc_module));
					// echo $this->db->last_query();die;
				$page_data['terms_condition'] = $terms_condition['data'][0];
						
			
				// }
				//debug($page_data);exit;
				switch ($operation) {
					case 'show_voucher' : $this->template->view('voucher/package_voucher', $page_data);
					break;
					case 'show_pdf' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$get_view=$this->template->isolated_view('voucher/package_voucher', $page_data);
						//debug($get_view);exit();
						//debug($create_pdf->create_pdf($get_view,'show')); exit;
						$create_pdf->create_pdf($get_view,'show');
						break;
					case 'email_voucher':
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$mail_template = $this->template->isolated_view('voucher/package_voucher', $page_data);
						$mail_template1 = $this->template->isolated_view('voucher/package_pdf', $page_data);
						//debug($mail_template);die;
						$pdf = $create_pdf->create_pdf($mail_template1,'');
						//debug($pdf);exit();
						$this->provab_mailer->send_mail($email, domain_name().' - '.$vc_module.''.'Ticket',$mail_template ,$pdf);
						break;
				}
			}
		}
	}





function b2c_holiday_voucher_old($app_reference , $booking_status='', $operation='show_voucher',$mail = 'no-mail') 
	{

		// echo "in voucher";
		// die;
 	// error_reporting(E_ALL);
		// debug($app_reference);
		// debug($booking_status);
		// debug($operation);
		// debug($mail);
		// die;
  $this->load->model('tours_model');
  $this->load->model('custom_db');
  if (empty($app_reference) == false) {
   $condition[]=array(
    'app_reference','=','"'.$app_reference.'"'
    );
   // debug($condition);
   // die;
    $booking_details = $this->tours_model->booking($condition);
    // debug($booking_details);
    // die;
    $domain_address = $this->custom_db->single_table_records ( 'domain_list','address,domain_logo,phone,domain_name',array('origin'=>get_domain_auth_id()));
						// debug($domain_address);exit;
						$voucher_data['data']['address'] =$domain_address['data'][0]['address'];
						$voucher_data['data']['phone'] =$domain_address['data'][0]['phone'];
						$voucher_data['data']['domainname'] =$domain_address['data'][0]['domain_name'];
						$voucher_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
    $voucher_data['voucher_data'] = $booking_details;
     // debug($voucher_data);exit;
    $pack_attr=json_decode($voucher_data['voucher_data']['data'][0]['attributes'],1);

    $package_id=$pack_attr['package_id'];
    $select="Select * from package as P1 join package_itinerary as P2 on P1.package_id=P2.package_id where P1.package_id=".$package_id;
    $ddata=$this->db->query($select)->result_array();
    $voucher_data['package_details']=$ddata;
    $select="Select * from package_cancellation where package_id=".$package_id;
    
    $ddata=$this->db->query($select)->result_array();
    $voucher_data['cancel_details']=$ddata;
    // debug($operation);
    // die;
    switch ($operation) {
     case 'show_voucher' : 
     $voucher_data['menu'] = true;
     $this->template->view('voucher/holiday', $voucher_data);
     break;
     case 'show_pdf' :
     $this->load->library('provab_pdf');
     $create_pdf = new Provab_Pdf();
     $get_view = $this->template->isolated_view ( 'voucher/b2c_holiday_pdf', $voucher_data );
     //debug($get_view);exit();
     $create_pdf->create_pdf($get_view,'show');
     break;
     case 'send_data' :
	    $voucher_data['menu'] = true;
    	 return  $voucher_data;
     break;
     
     case 'email_voucher':
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$mail_template = $this->template->isolated_view('voucher/b2c_holiday_pdf', $page_data);
						$pdf = $create_pdf->create_pdf($mail_template,'');
						$this->provab_mailer->send_mail($email, domain_name().' - Transfers Ticket',$mail_template ,$pdf);
						break;
    }
   }
}



function b2b_holiday_voucher($app_reference , $booking_status='', $operation='show_voucher',$mail = 'no-mail') 
	{

		// echo "in voucher";
		// die;
 	// error_reporting(E_ALL);
		// debug($app_reference);
  $this->load->model(array('package_model','tours_model','custom_db'));
  if (empty($app_reference) == false) {
   $condition[]=array(
    'app_reference','=','"'.$app_reference.'"'
    );
    $booking_details = $this->tours_model->booking($condition);
    $domain_address = $this->custom_db->single_table_records ( 'domain_list','address,domain_logo,phone,domain_name',array('origin'=>get_domain_auth_id()));
						// debug($domain_address);exit;
						$voucher_data['data']['address'] =$domain_address['data'][0]['address'];
						$voucher_data['data']['phone'] =$domain_address['data'][0]['phone'];
						$voucher_data['data']['domainname'] =$domain_address['data'][0]['domain_name'];
						$voucher_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
    $voucher_data['voucher_data'] = $booking_details;
     // debug($voucher_data);exit;
    $pack_attr=json_decode($voucher_data['voucher_data']['data'][0]['attributes'],1);

    $package_id=$pack_attr['package_id'];
    $select="Select * from package as P1 join package_itinerary as P2 on P1.package_id=P2.package_id where P1.package_id=".$package_id;
    $ddata=$this->db->query($select)->result_array();
    $voucher_data['package_details']=$ddata;
    $select="Select * from package_cancellation where package_id=".$package_id;
    
    $ddata=$this->db->query($select)->result_array();
    $voucher_data['cancel_details']=$ddata;
    // debug($operation);
    // die;
    switch ($operation) {
     case 'show_voucher' : 
     $voucher_data['menu'] = true;
     $this->template->view('voucher/b2b_holiday', $voucher_data);
     break;
     case 'show_pdf' :
     // $this->load->library('provab_pdf');
     // $get_view = $this->template->isolated_view ( 'voucher/holiday_pdf', $voucher_data );
     // //debug($get_view);exit();
     // $this->provab_pdf->create_pdf ( $get_view, 'D', $app_reference); 

     $this->load->library('provab_pdf');
                        $create_pdf = new Provab_Pdf();
                        $get_view = $this->template->isolated_view('voucher/b2bholiday_pdf', $voucher_data);
                        $create_pdf->create_pdf($get_view, 'show');
     break;
     case 'send_data' :
	    $voucher_data['menu'] = true;
    	 return  $voucher_data;
     break;
    }
   }
}

function corp_hotel_voucher($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$email='')
	{
		$this->load->model('hotel_model');
		if (empty($app_reference) == false) {
			$booking_details = $this->hotel_model->get_booking_details($app_reference, $booking_source, $booking_status);
			if ($booking_details['status'] == SUCCESS_STATUS) {
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_hotel_booking_data($booking_details, $this->current_module);
				$page_data['data'] = $assembled_booking_details['data'];
				if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
				
					$domain_address = $this->custom_db->single_table_records ('domain_list','address,domain_logo,phone,domain_name',array('origin'=>get_domain_auth_id()));
					$page_data['data']['address'] =$domain_address['data'][0]['address'];
					$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
					$page_data['data']['phone'] = $domain_address['data'][0]['phone'];
					$page_data['data']['domainname'] = $domain_address['data'][0]['domain_name'];

					if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
						$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
						if(!empty($get_agent_info)){
							$page_data['data']['address'] = $get_agent_info[0]['address'];
							$page_data['data']['logo'] = $get_agent_info[0]['logo'];
							$page_data['data']['phone'] = $get_agent_info[0]['phone'];
							$page_data['data']['domainname'] = $get_agent_info[0]['agency_name'];

						}
					}
				
				}
				switch ($operation) {
					case 'show_voucher' : $this->template->view('voucher/corphotel_voucher', $page_data);
					break;
					case 'show_pdf' :						
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$get_view=$this->template->isolated_view('voucher/corphotel_pdf', $page_data);						
						$create_pdf->create_pdf($get_view,'show');
						
					break;
					case 'email_voucher' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$mail_template = $this->template->isolated_view('voucher/hotel_pdf', $page_data);
						$pdf = $create_pdf->create_pdf($mail_template,'');
						$this->provab_mailer->send_mail($email, domain_name().' - Hotel Ticket',$mail_template ,$pdf);
						break;
				}
			}
		}
	}




	function hotel_crs($app_reference, $booking_status='', $operation='show_voucher',$email='')
	{
		// echo $booking_status;exit();
		$this->load->model('hotels_model');
		if (empty($app_reference) == false) {
			$booking_details = $this->hotels_model->get_booking_crs_details($app_reference,$booking_status);
			   // debug($booking_details);exit();
			    if ($booking_details['status'] == SUCCESS_STATUS) {
				//Assemble Booking Data
				
			    //get agent address & logo for b2b voucher
			    	$page_data['data'] = $booking_details['data'];
				    $domain_address = $this->custom_db->single_table_records ('domain_list','address,domain_logo,phone,domain_name',array('origin'=>get_domain_auth_id()));
					$page_data['data']['address'] = $domain_address['data'][0]['address'];
					$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
					$page_data['data']['phone'] = $domain_address['data'][0]['phone'];
					$page_data['data']['domainname'] = $domain_address['data'][0]['domain_name'];
					$get_agent_info = $this->user_model->get_agent_info($booking_details['data']['booking_details'][0]['user_id']);

						if(!empty($get_agent_info)) {
							$page_data['data']['address'] = !empty($get_agent_info[0]['address']) ? $get_agent_info[0]['address'] : $domain_address['data'][0]['address'];
							$page_data['data']['domainname'] = (!empty($get_agent_info[0]['agency_name']) ? $get_agent_info[0]['agency_name'] : $domain_address['data'][0]['domain_name']);
							$page_data['data']['logo'] = (!empty($get_agent_info[0]['logo']) ? $get_agent_info[0]['logo'] : $domain_address['data'][0]['domain_logo']);
						}
					
				    switch ($operation) {
					case 'show_voucher' : 
                    $this->template->view('voucher/hotel_crs_new', $page_data);
					break;
					case 'show_pdf' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						// debug($page_data);exit();
						$get_view = $this->template->isolated_view('voucher/hotel_crs_pdf', $page_data);
						$create_pdf->create_pdf($get_view,'show');
						
					break;
					case 'email_voucher' :
						//debug($page_data);exit;
						/*$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$mail_template = $this->template->isolated_view('voucher/hotel_pdf', $page_data);
						$pdf = $create_pdf->create_pdf($mail_template,'');
						$this->provab_mailer->send_mail($email, domain_name().' - Hotel Ticket',$mail_template ,$pdf);*/
						if($email == ''){
		    				$email = $voucher_details['other'][$b]->check_in_date->email_id;
		    			}
						$this->load->model ( 'hotels_model' );
						$this->load->library ( 'provab_mailer' );
						$mail_template = $this->template->isolated_view('voucher/hotel_voucher_crs', $page_data);
						$this->session->set_flashdata('email_message', 'Email sent successfully');
		                $email_subject = "Hotel Booking Confirmation-".$page_data['data']['booking_details'][0]['parent_pnr'];

						$mail_status = $this->provab_mailer->send_mail($email, $email_subject, $mail_template, "");
						break;
				}
			}
		}
	}

	


	function holiday($app_reference , $booking_status='', $operation='show_voucher',$mail = 'no-mail') {
 	// error_reporting(E_ALL);
  $this->load->model(array('tours_model','custom_db','domain_management_model'));
  if (empty($app_reference) == false) {
   $condition[]=array(
    'app_reference','=','"'.$app_reference.'"'
    );
   // debug($app_reference);die;
   $booking_details = $this->tours_model->booking($condition);
   // debug($booking_details);die;
   if ($booking_details['status'] == SUCCESS_STATUS) {
    foreach ($booking_details['data'] as $key => $data) {
     $enquiry_reference_no=$key;
    }
    $voucher_data = $data;
    $attributes = json_decode($data['booking_details']['attributes'],true);
    $user_attributes = json_decode($data['booking_details']['user_attributes'],true);
    	$voucher_data['supplier_details'] = $this->tours_model->get_supplier_details($attributes['tour_id']);
    $voucher_data ['tours_itinerary_dw']   = $this->tours_model->tours_itinerary_dw($attributes['tour_id'],$attributes['departure_date']);
    // debug($voucher_data); exit('');
    if(isset($mail) && $mail == 'mail') { 
     if ($this->input->post('email')) {
      $email = $this->input->post('email');
     }else{
      $email = $user_attributes['email'];
     } 
     $voucher_data['menu'] = false;
     $mail_template =$this->template->isolated_view('voucher/holiday',$voucher_data);
     $this->load->library ( 'provab_pdf' );
     $pdf = $this->provab_pdf->create_pdf($mail_template,'F',$app_reference);
     $this->provab_mailer->send_mail(18, $email, 'Holiday Booking', $mail_template,$pdf);
    }
    switch ($operation) {
     case 'show_voucher' : 
     $voucher_data['menu'] = true;
     // debug($voucher_data); exit('');
     $this->template->view('voucher/holiday', $voucher_data);
     break;
     case 'show_pdf' :
     $this->load->library('provab_pdf');
     $get_view = $this->template->isolated_view ( 'voucher/holiday_pdf', $voucher_data );
     $this->provab_pdf->create_pdf ( $get_view, 'D', $app_reference);
     break;
    }
   }
  }
 }


 function package($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$email='')
	{
		$this->load->model('package_model');
		if (empty($app_reference) == false) {
			$booking_details = $this->package_model->get_booking_details($app_reference, $booking_source, $booking_status);
			if ($booking_details['status'] == SUCCESS_STATUS) {
				// load_flight_lib(PROVAB_FLIGHT_BOOKING_SOURCE);
				// //Assemble Booking Data
				// $assembled_booking_details = $this->booking_data_formatter->format_flight_booking_data($booking_details, 'b2c');	
				// $page_data['data'] = $assembled_booking_details['data'];
				 // if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
					
					$domain_address = $this->custom_db->single_table_records ( 'domain_list','address,domain_logo,domain_name,phone',array('origin'=>get_domain_auth_id()));
					$page_data['data']['address'] =$domain_address['data'][0]['address'];
					$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
					// $page_data['data']['address'] =$domain_address['data'][0]['address'];
					$page_data['data']['phone'] =$domain_address['data'][0]['phone'];
					$page_data['data']['domainname'] =$domain_address['data'][0]['domain_name'];


					if($booking_details['booking_details'][0]['created_by_id'] > 0){
						$get_agent_info = $this->user_model->get_agent_info($booking_details['booking_details'][0]['created_by_id']);
						if(!empty($get_agent_info)){
							$page_data['address'] = $get_agent_info[0]['address'];
							$page_data['logo'] = $get_agent_info[0]['logo'];
						}
					}

					$page_data['details'] = $booking_details;
						
					$module_type="Package";
				 if(!empty($booking_details['package_details'][0]['module_type']))
				 {
				 	$module_type=ucfirst($booking_details['package_details'][0]['module_type']);
				 }
				// debug($booking_details['package_details'][0]['module_type']);exit;
				 debug($page_data);exit();
				switch ($operation) {
					case 'show_voucher' : $this->template->view('voucher/package_voucher', $page_data);
					break;
					case 'show_pdf' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$get_view=$this->template->isolated_view('voucher/package_voucher', $page_data);
						//debug($get_view);exit;
						$create_pdf->create_pdf($get_view,'show');
						break;
					case 'email_voucher':
					// debug($booking_details['passenger_details'][0]['app_reference']);die;
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$mail_template = $this->template->isolated_view('voucher/package_voucher', $page_data);
						// debug($booking_details['passenger_details'][0]['app_reference']);exit();
						 // debug($mail_template);die;
					$pdf = $create_pdf->create_pdf($mail_template,'');
							//debug(DOMAIN_PDF_DIR);exit();
						
						//debug($pdf);exit();
						$this->provab_mailer->send_mail($email, domain_name().' - '.$module_type.' Ticket',$mail_template ,$pdf);
						break;
				}
			}
		}
	}


function activity_crs($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$email=''){
     // error_reporting(E_ALL);

		if($this->input->post('email')){
			$email=$this->input->post('email');	
		}
		$this->load->model('activity_model');

		if (empty($app_reference) == false) {

			/*debug($app_reference);
			debug($booking_source);
			debug($booking_status);exit;*/
			$booking_details = $this->activity_model->get_booking_details_activity($app_reference, $booking_source, $booking_status);
			// debug($booking_details['data']['booking_details'][0]['pickup_location']);die;
			//$booking_details = $this->activity_model->get_booking_details($app_reference, $booking_source, $booking_status);
      // debug($booking_details['data']);
			$pack_id=$booking_details['data']['booking_details'][0]['package_type'];
		//	debug($pack_id);exit;

			$page_data['pack_details'] = $this->activity_model->getPackage($pack_id);

			$tour_booking_details = array(
											
'supplier_id'=>$page_data['pack_details']->supplier_id,
			
						);
			$this->custom_db->update_record('activity_booking_details',$tour_booking_details,array('app_reference'=>$app_reference));
			// debug($page_data['pack_details']->image);exit;
			if ($booking_details['status'] == SUCCESS_STATUS) {
				//Assemble Booking Data

				$assembled_booking_details = $this->booking_data_formatter->format_sightseeing_booking_data_hb_crs($booking_details, 'b2b');
               // debug($assembled_booking_details);exit;
				

				$page_data['data'] = $assembled_booking_details['data'];
				if(isset($this->entity_user_id))
				{
				// $get_agent_info = $this->user_model->get_agent_info($this->entity_user_id)[0];
				}
			 	// $get_agent_image = $this->user_model->get_agent_image()[0];
    			//$page_data ['agent_info'] = $get_agent_info;
    			//$page_data ['agent_image'] = $get_agent_image;
                if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
				
					$domain_address = $this->custom_db->single_table_records ('domain_list','address,domain_logo,phone,domain_name',array('origin'=>get_domain_auth_id()));
					$page_data['data']['address'] =$domain_address['data'][0]['address'];
					$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
					$page_data['data']['phone'] = $domain_address['data'][0]['phone'];
					$page_data['data']['domainname'] = $domain_address['data'][0]['domain_name'];
				}
				$voucher_details = $this->custom_db->single_table_records(' voucher_details','note,phone,email', array('id'=>get_domain_auth_id()));

				$page_data['data']['note'] = $voucher_details['data'][0]['note'];
				$page_data['data']['voucher_phone'] = $voucher_details['data'][0]['phone'];
				$page_data['data']['voucher_email'] = $voucher_details['data'][0]['email'];
                $page_data['pickup_location'] = $booking_details['data']['booking_details'][0]['pickup_location'];
				switch ($operation) {

					case 'show_voucher' : $this->template->view('voucher/activity_voucher_crs', $page_data);
					break;
					case 'show_activity_details' : $this->template->view('voucher/activity_booking_view', $page_data);
					break;
					case 'show_invoice' : $this->template->view('voucher/activity_invoice', $page_data);
					break;
					case 'show_vat_invoice' : $this->template->view('voucher/activity_vat_invoice', $page_data);
					break;
					case 'show_pdf' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();

						$get_view=$this->template->isolated_view('voucher/activity_voucher_crs', $page_data);
						$create_pdf->create_pdf($get_view,'show');
					break;
					case 'email_voucher' :

						$this->load->library('provab_pdf');
						$this->load->library ( 'provab_mailer' );
						$create_pdf = new Provab_Pdf();
						$mail_template = $this->template->isolated_view('voucher/activity_voucher_crs', $page_data);

						$pdf = $create_pdf->create_pdf($mail_template,'F');
						$res=$this->provab_mailer->send_mail($email, domain_name().' - Excursion Ticket',$mail_template ,$pdf); 

					//debug($res); die;

					break;
				}
			}
		}
	}

 function activity_crs_pdf($app_reference , $booking_status='', $operation='show_voucher',$mail = 'no-mail') {
 	// error_reporting(E_ALL);
  $this->load->model(array('tours_model','custom_db'));
  if (empty($app_reference) == false) {
   $condition[]=array(
    'app_reference','=','"'.$app_reference.'"'
    );
   // debug($app_reference);
   // debug($condition);
   // die;
   $booking_details = $this->tours_model->booking($condition);
   debug($booking_details);die;
   if ($booking_details['status'] == SUCCESS_STATUS) {
    foreach ($booking_details['data'] as $key => $data) {
     $enquiry_reference_no=$key;
    }
    $voucher_data = $data;
    $attributes = json_decode($data['booking_details']['attributes'],true);
    $user_attributes = json_decode($data['booking_details']['user_attributes'],true);
    $voucher_data ['tours_itinerary_dw']   = $this->tours_model->tours_itinerary_dw($attributes['tour_id'],$attributes['departure_date']);
    // debug($voucher_data); exit('');
    if(isset($mail) && $mail == 'mail') { 
     if ($this->input->post('email')) {
      $email = $this->input->post('email');
     }else{
      $email = $user_attributes['email'];
     } 
     $voucher_data['menu'] = false;
     $mail_template =$this->template->isolated_view('voucher/holiday',$voucher_data);
     $this->load->library ( 'provab_pdf' );
     $pdf = $this->provab_pdf->create_pdf($mail_template,'F',$app_reference);
     $this->provab_mailer->send_mail(18, $email, 'Holiday Booking', $mail_template,$pdf);
    }
    switch ($operation) {
     case 'show_voucher' : 
     $voucher_data['menu'] = true;
     // debug($voucher_data); exit('');
     $this->template->view('voucher/holiday', $voucher_data);
     break;
     case 'show_pdf' :
     $this->load->library('provab_pdf');
     $get_view = $this->template->isolated_view ( 'voucher/holiday_pdf', $voucher_data );
     $this->provab_pdf->create_pdf ( $get_view, 'D', $app_reference);
     break;
    }
   }
  }
 }


}