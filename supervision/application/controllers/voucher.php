<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @package    Provab
 * @subpackage Bus
 * @author     Balu A<balu.provab@gmail.com>
 * @version    V1
 */
//error_reporting(E_ALL);
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
		//echo 'under working';exit;
		$this->load->model('bus_model');
		if (empty($app_reference) == false) {
			$booking_details = $this->bus_model->get_booking_details($app_reference, $booking_source, $booking_status);
			$terms_conditions = $this->custom_db->single_table_records('terms_conditions','description', array('module' =>'bus'));
			if ($booking_details['status'] == SUCCESS_STATUS) {
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_bus_booking_data($booking_details, $this->current_module);
				$page_data['data'] = $assembled_booking_details['data'];
				// debug($assembled_booking_details);exit;
				if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
					$domain_address = $this->custom_db->single_table_records ('domain_list','address,domain_logo,phone,domain_name,phone_code',array('origin'=>get_domain_auth_id()));
					$page_data['data']['address'] =$domain_address['data'][0]['address'];
					$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
					$page_data['data']['phone'] = $domain_address['data'][0]['phone'];
					$page_data['data']['phone_code'] = $domain_address['data'][0]['phone_code'];
					$page_data['data']['domainname'] = $domain_address['data'][0]['domain_name'];
					$page_data['data']['terms_conditions'] = '';
					if($terms_conditions['status'] == SUCCESS_STATUS){
						$page_data['data']['terms_conditions'] = $terms_conditions['data'][0]['description'];
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
						$pdf = $create_pdf->create_pdf($mail_template,'F');
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

	function activity_crs($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$email=''){
     // error_reporting(E_ALL);

		$this->load->model('activity_model');

		if (empty($app_reference) == false) {

			
			$booking_details = $this->activity_model->get_booking_details_activity($app_reference, $booking_source, $booking_status);
			// debug($this->db->last_query());exit;
			$pack_id=$booking_details['data']['booking_details'][0]['package_type'];
			//debug($pack_id);exit;

			$page_data['pack_details'] = $this->activity_model->getPackage($pack_id);
			//debug($booking_details);exit;
			if ($booking_details['status'] == SUCCESS_STATUS) {
				//Assemble Booking Data
				//debug("here");exit;
				$assembled_booking_details = $this->booking_data_formatter->format_sightseeing_booking_data_hb_crs($booking_details, 'b2c');
               // debug($assembled_booking_details);exit;

				$page_data['data'] = $assembled_booking_details['data'];
				//  debug($page_data['data']['booking_details'][0]['created_by_id']);exit;
				$get_agent_info = $this->user_model->get_agent_info($page_data['data']['booking_details'][0]['created_by_id']);
				$get_admin_info = $this->user_model->get_admin_info($page_data['data']['booking_details'][0]['created_by_id']);
				$get_staff_info = $this->user_model->get_staff_info($page_data['data']['booking_details'][0]['created_by_id']);
				$page_data['data']['get_agent_info'] = $get_agent_info;
				$page_data['data']['get_staff_info'] = $get_staff_info;
				$page_data['data']['get_admin_info'] = $get_admin_info;
				 // debug($get_agent_info);exit;
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

					case 'show_voucher' : $this->template->view('voucher/activity_voucher_crs', $page_data);
					break;
					case 'show_invoice' : $this->template->view('voucher/activity_invoice', $page_data);
					break;
					case 'show_activity_details' : $this->template->view('voucher/activity_booking_view', $page_data);
					break;
					case 'show_vat_invoice' : $this->template->view('voucher/activity_crs_vat_invoice', $page_data);
					break;
					case 'show_pdf' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();

						$get_view=$this->template->isolated_view('voucher/activity_pdf', $page_data);
						$create_pdf->create_pdf($get_view,'show');
						break;
					case 'email_voucher' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$mail_template = $this->template->isolated_view('voucher/activity_pdf', $page_data);
						$pdf = $create_pdf->create_pdf($mail_template,'');
						$this->provab_mailer->send_mail($email, domain_name().' - Excursion Ticket',$mail_template ,$pdf);
						break;
				}
			}
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
			$get_admin_info = $this->user_model->get_admin_info($agent_id);
			$get_staff_info = $this->user_model->get_staff_info($agent_id);
			
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
 
	/**
	 *
	 */
	function hotel($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$email='')
	{
		$this->load->model('hotel_model');
		if (empty($app_reference) == false) {
			$booking_details = $this->hotel_model->get_booking_details($app_reference, $booking_source, $booking_status);
			$terms_conditions = $this->custom_db->single_table_records('terms_conditions','description', array('module' =>'hotel'));
			//debug($booking_details)
			if ($booking_details['status'] == SUCCESS_STATUS) {
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_hotel_booking_data($booking_details, $this->current_module);
			//	$assembled_booking_details = $this->booking_data_formatter->format_hotel_booking_data($booking_details, 'b2c');
			//	$supplier_details = $this->hotel_model->get_supplier_details($assembled_booking_details[data] );
			$hc=json_decode($assembled_booking_details['data']['booking_details'][0]['attributes']);
			
			$page_data['supplier_details'] = $this->hotel_model->get_supplier_details($hc->HotelCode);
				$page_data['data'] = $assembled_booking_details['data'];
				if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
				
					$domain_address = $this->custom_db->single_table_records ('domain_list','address,domain_logo,phone,domain_name,phone_code,email',array('origin'=>get_domain_auth_id()));
					$page_data['data']['address'] =$domain_address['data'][0]['address'];
					$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
					$page_data['data']['phone'] = $domain_address['data'][0]['phone'];
					$page_data['data']['domainname'] = $domain_address['data'][0]['domain_name'];
					$page_data['data']['email'] = $domain_address['data'][0]['email'];

					if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
						$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
						if(!empty($get_agent_info)){
							$page_data['data']['address'] = $get_agent_info[0]['address'];
							if($get_agent_info[0]['logo'] !=""){

								$page_data['data']['logo'] =$get_agent_info[0]['logo'];
							}
							else
							{
								$page_data['data']['logo'] =$domain_address['data'][0]['domain_logo'];
							}
							$page_data['data']['phone'] = $get_agent_info[0]['phone'];
							$page_data['data']['domainname'] = $get_agent_info[0]['agency_name'];

						}
					}
					$page_data['data']['terms_conditions'] = '';
					if($terms_conditions['status'] == SUCCESS_STATUS){
						$page_data['data']['terms_conditions'] = $terms_conditions['data'][0]['description'];
					}
				
				}
				//debug($page_data);exit;
				$voucher_details = $this->custom_db->single_table_records(' voucher_details','note,phone,email', array('id'=>get_domain_auth_id()));

				$page_data['data']['note'] = $voucher_details['data'][0]['note'];
				$page_data['data']['voucher_phone'] = $voucher_details['data'][0]['phone'];
				$page_data['data']['voucher_email'] = $voucher_details['data'][0]['email'];
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
					//	$pdf = $create_pdf->create_pdf($mail_template,'F');
						$this->provab_mailer->send_mail($email, domain_name().' - Hotel Ticket',$mail_template ,$pdf);
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
			$terms_conditions = $this->custom_db->single_table_records('terms_conditions','description', array('module' =>'activity'));
			$booking_details['status']=1;
			if ($booking_details['status'] == SUCCESS_STATUS) {
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_sightseeing_booking_data($booking_details, $this->current_module);

				$page_data['data'] = $assembled_booking_details['data'];
				if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
				
					$domain_address = $this->custom_db->single_table_records ('domain_list','address,domain_logo,phone,domain_name,email',array('origin'=>get_domain_auth_id()));
					$page_data['data']['address'] =$domain_address['data'][0]['address'];
					$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
					$page_data['data']['phone'] = $domain_address['data'][0]['phone'];
					$page_data['data']['domainname'] = $domain_address['data'][0]['domain_name'];
					$page_data['data']['email'] = $domain_address['data'][0]['email'];
					if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
						$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
						if(!empty($get_agent_info)){
							$page_data['data']['address'] = $get_agent_info[0]['address'];
							if($get_agent_info[0]['logo'] !=""){

								$page_data['data']['logo'] =$get_agent_info[0]['logo'];
							}
							else
							{
								$page_data['data']['logo'] =$domain_address['data'][0]['domain_logo'];
							}
							$page_data['data']['phone'] = $get_agent_info[0]['phone'];
							$page_data['data']['domainname'] = $get_agent_info[0]['agency_name'];

						}
					}
					$page_data['data']['terms_conditions'] = '';
					if($terms_conditions['status'] == SUCCESS_STATUS){
						$page_data['data']['terms_conditions'] = $terms_conditions['data'][0]['description'];
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
						$pdf = $create_pdf->create_pdf($mail_template,'F');
						$this->provab_mailer->send_mail($email, domain_name().' - Sightseeing Ticket',$mail_template ,$pdf);
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
		// debug($email);exit;
		$this->load->model('transferv1_model');
		
		if (empty($app_reference) == false) {
			$booking_details = $this->transferv1_model->get_booking_details($app_reference, $booking_source, $booking_status);
			$terms_conditions = $this->custom_db->single_table_records('terms_conditions','description', array('module' =>'transfer'));
			// debug($booking_details);exit;
			if ($booking_details['status'] == SUCCESS_STATUS) {
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_transferv1_booking_data($booking_details, $this->current_module);

				$page_data['data'] = $assembled_booking_details['data'];
				if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
				
					$domain_address = $this->custom_db->single_table_records ('domain_list','address,domain_logo,phone,domain_name,phone_code,email',array('origin'=>get_domain_auth_id()));
					$page_data['data']['address'] =$domain_address['data'][0]['address'];
					$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
					$page_data['data']['phone'] = $domain_address['data'][0]['phone'];
					$page_data['data']['phone_code'] = $domain_address['data'][0]['phone_code'];
					$page_data['data']['domainname'] = $domain_address['data'][0]['domain_name'];
					$page_data['data']['email'] = $domain_address['data'][0]['email'];

					if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
						$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
						if(!empty($get_agent_info)){
							$page_data['data']['address'] = $get_agent_info[0]['address'];
							if($get_agent_info[0]['logo'] !=""){

								$page_data['data']['logo'] =$get_agent_info[0]['logo'];
							}
							else
							{
								$page_data['data']['logo'] =$domain_address['data'][0]['domain_logo'];
							}
							$page_data['data']['phone'] = $get_agent_info[0]['phone'];
							$page_data['data']['domainname'] = $get_agent_info[0]['agency_name'];
						}
					}

					// debug($page_data['data']);exit;
					$page_data['data']['terms_conditions'] = '';
					if($terms_conditions['status'] == SUCCESS_STATUS){
						$page_data['data']['terms_conditions'] = $terms_conditions['data'][0]['description'];
					}
				
				
				}
				// debug($page_data);exit;
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
						// debug($page_data);exit;
						$mail_template = $this->template->isolated_view('voucher/transfer_pdf', $page_data);
						// debug($mail_template);exit;
						$pdf = $create_pdf->create_pdf($mail_template,'F');
						// debug($pdf);exit;
						$this->provab_mailer->send_mail($email, domain_name().' - Transfers Ticket',$mail_template ,$pdf);
						break;
				}
			}
		}
	}
		function b2c_holiday_voucher($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$email='')
	{
			// echo "string";exit;
	
		$this->load->model('package_model');
		if (empty($app_reference) == false) {
			$booking_details = $this->package_model->get_activity_booking_details($app_reference, $booking_source, $booking_status);
			//debug($booking_details);die;
						$page_data['supplier_details'] = $this->package_model->get_supplier_details($booking_details['package_details'][0]['package_id']);
						//	debug($page_data);die;
			 $this->package_model->update_pack_details($assembled_booking_details['data']['booking_details'][0]['app_reference'],$page_data['supplier_details'][0]['user_id']);
		// debug($booking_details);die;
						// echo "string";exit;
						$booking_details['status']=true;
			if (!empty($booking_details['status'])) {

						$domain_address = $this->custom_db->single_table_records ( 'domain_list','address,domain_logo',array('origin'=>get_domain_auth_id()));
						$page_data['address'] =$domain_address['data'][0]['address'];
						$page_data['logo'] = $domain_address['data'][0]['domain_logo'];
						$page_data['details'] = $booking_details;
						$domain_address = $this->custom_db->single_table_records ('domain_list','address,domain_logo,phone,domain_name',array('origin'=>get_domain_auth_id()));
						$page_data['data']['address'] =$domain_address['data'][0]['address'];
						$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
						$page_data['data']['phone'] = $domain_address['data'][0]['phone'];
						$page_data['data']['domainname'] = $domain_address['data'][0]['domain_name'];
						
// debug($page_data);exit();
						$this->load->library('provab_pdf');
						$this->load->library('provab_mailer');
						$create_pdf = new Provab_Pdf();
						$mail_template=$this->template->isolated_view('voucher/activity_voucher', $page_data);
						// $mail_template_pdf = $this->template->isolated_view('voucher/package_pdf', $page_data);
					//debug($mail_template_pdf);exit();
							$email=$booking_details['booking_details'][0]['email'];
						//debug($email);exit();
						// $pdf = $create_pdf->create_pdf($mail_template_pdf,'');
						//debug($pdf);exit();
						//$send=	$this->provab_mailer->send_mail($email, domain_name().' - Activity Ticket',$mail_template ,$pdf);
					//debug ($send);exit();
						$page_data['email_status']=TRUE;
						if($booking_status){
							$page_data['booking_status'] = FALSE;
						}else{
							$page_data['booking_status'] = TRUE;
						}
						// debug($page_data);exit();
			    switch ($operation) {
					case 'show_voucher' : $this->template->view('voucher/activity_voucher', $page_data);
					break;
					case 'show_pdf' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$get_view=$this->template->isolated_view('voucher/package_pdf', $page_data);
						$create_pdf->create_pdf($get_view,'show');
						break;
					case 'email_voucher':
						// error_reporting(E_ALL);
						$this->load->library('provab_pdf');
						$this->load->library('provab_mailer');
						$create_pdf = new Provab_Pdf();
                        $mail_template = $this->template->view('voucher/activity_voucher', $page_data);
						$mail_template_pdf = $this->template->isolated_view('voucher/package_pdf', $page_data);
						$pdf = $create_pdf->create_pdf($mail_template_pdf,'');
						$type = $page_data['details']['booking_details'][0]['module_type'] == "transfers" ? "Transfer" : "Activity";
						$res = $this->provab_mailer->send_mail($email, domain_name().' - '.$type.' Ticket',$mail_template ,$pdf);
						debug($res);
						break;
				}
			}
		}
	}
		function b2b_holiday_voucher($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$email='')
	{
		// echo "string";exit;
	
		$this->load->model('package_model');
		if (empty($app_reference) == false) {
			$booking_details = $this->package_model->get_activity_booking_details($app_reference, $booking_source, $booking_status);
			//debug($booking_details);die;
						$page_data['supplier_details'] = $this->package_model->get_supplier_details($booking_details['package_details'][0]['package_id']);
						//	debug($page_data);die;
			 $this->package_model->update_pack_details($assembled_booking_details['data']['booking_details'][0]['app_reference'],$page_data['supplier_details'][0]['user_id']);
		// debug($booking_details);die;
						// echo "string";exit;
						$booking_details['status']=true;
			if (!empty($booking_details['status'])) {

						$domain_address = $this->custom_db->single_table_records ( 'domain_list','address,domain_logo',array('origin'=>get_domain_auth_id()));
						$page_data['address'] =$domain_address['data'][0]['address'];
						$page_data['logo'] = $domain_address['data'][0]['domain_logo'];
						$page_data['details'] = $booking_details;
						$domain_address = $this->custom_db->single_table_records ('domain_list','address,domain_logo,phone,domain_name',array('origin'=>get_domain_auth_id()));
						$page_data['data']['address'] =$domain_address['data'][0]['address'];
						$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
						$page_data['data']['phone'] = $domain_address['data'][0]['phone'];
						$page_data['data']['domainname'] = $domain_address['data'][0]['domain_name'];
						
// debug($page_data);exit();
						$this->load->library('provab_pdf');
						$this->load->library('provab_mailer');
						$create_pdf = new Provab_Pdf();
						$mail_template=$this->template->isolated_view('voucher/activity_voucher', $page_data);
						// $mail_template_pdf = $this->template->isolated_view('voucher/package_pdf', $page_data);
					//debug($mail_template_pdf);exit();
							$email=$booking_details['booking_details'][0]['email'];
						//debug($email);exit();
						// $pdf = $create_pdf->create_pdf($mail_template_pdf,'');
						//debug($pdf);exit();
						//$send=	$this->provab_mailer->send_mail($email, domain_name().' - Activity Ticket',$mail_template ,$pdf);
					//debug ($send);exit();
						$page_data['email_status']=TRUE;
						if($booking_status){
							$page_data['booking_status'] = FALSE;
						}else{
							$page_data['booking_status'] = TRUE;
						}
						// debug($page_data);exit();
			    switch ($operation) {
					case 'show_voucher' : $this->template->view('voucher/activity_voucher', $page_data);
					break;
					case 'show_pdf' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$get_view=$this->template->isolated_view('voucher/package_pdf', $page_data);
						$create_pdf->create_pdf($get_view,'show');
						break;
					case 'email_voucher':
						// error_reporting(E_ALL);
						$this->load->library('provab_pdf');
						$this->load->library('provab_mailer');
						$create_pdf = new Provab_Pdf();
                        $mail_template = $this->template->view('voucher/activity_voucher', $page_data);
						$mail_template_pdf = $this->template->isolated_view('voucher/package_pdf', $page_data);
						$pdf = $create_pdf->create_pdf($mail_template_pdf,'');
						$type = $page_data['details']['booking_details'][0]['module_type'] == "transfers" ? "Transfer" : "Activity";
						$res = $this->provab_mailer->send_mail($email, domain_name().' - '.$type.' Ticket',$mail_template ,$pdf);
						debug($res);
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
			// debug($booking_details);exit;
			if ($booking_details['status'] == SUCCESS_STATUS) {
				load_flight_lib(PROVAB_FLIGHT_BOOKING_SOURCE);
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_flight_booking_data($booking_details, 'b2c', false);
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
						if(!empty($get_agent_info)){
							$page_data['data']['address'] = $get_agent_info[0]['address'];
							// $page_data['data']['logo'] = $get_agent_info[0]['logo'];
							if($get_agent_info[0]['logo'] !=""){

								$page_data['data']['logo'] =$get_agent_info[0]['logo'];
							}
							else
							{
								$page_data['data']['logo'] =$domain_address['data'][0]['domain_logo'];
							}
							$page_data['data']['phone'] = $get_agent_info[0]['phone'];
							$page_data['data']['domainname'] = $get_agent_info[0]['agency_name'];
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
						//debug($get_view);exit;
						$create_pdf->create_pdf($get_view,'show');
						break;
				   case 'email_voucher':
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$mail_template = $this->template->isolated_view('voucher/flight_pdf', $page_data);
						$pdf = $create_pdf->create_pdf($mail_template,'F');
					   // debug($pdf);exit;

						$res  = $this->provab_mailer->send_mail($email, domain_name().' - Flight Ticket',$mail_template ,$pdf);
				// 		debug($res);exit;
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
			//debug($booking_details);die;
			$terms_conditions = $this->custom_db->single_table_records('terms_conditions','description', array('module' =>'flight'));
			if ($booking_details['status'] == SUCCESS_STATUS) {
				load_flight_lib(PROVAB_FLIGHT_BOOKING_SOURCE);
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_flight_booking_data($booking_details, 'b2c', false);				
				$page_data['data'] = $assembled_booking_details['data'];
				
			if(isset($assembled_booking_details['data']['booking_details'][0])){
													//changes start added for convenience fee print in voucher
			
					$convDetailsQuery = $this->custom_db->single_table_records('flight_booking_transaction_details', 'status_description', array('app_reference' => $assembled_booking_details['data']['booking_details'][0]['app_reference']));
		
					$convDetailsValue = (float)$convDetailsQuery['data'][0]['status_description'];
			

					$assembled_booking_details['data']['booking_details'][0]['pg_convenience'] = $convDetailsValue;
			
					$page_data['data'] = $assembled_booking_details['data'];
					//changes end added for convenience fee print in voucher
					//get agent address & logo for b2b voucher
					
					$domain_address = $this->custom_db->single_table_records ( 'domain_list','address,domain_logo,domain_name,phone,phone_code,email',array('origin'=>get_domain_auth_id()));
					
					$page_data['data']['address'] =$domain_address['data'][0]['address'];
					$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
					$page_data['data']['phone_code'] =$domain_address['data'][0]['phone_code'];
					$page_data['data']['phone'] =$domain_address['data'][0]['phone'];
					$page_data['data']['email'] =$domain_address['data'][0]['email'];
					$page_data['data']['domainname'] =$domain_address['data'][0]['domain_name'];
					// debug($assembled_booking_details);exit;
					// if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
					// 	$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
					// 	// debug($get_agent_info);exit;
					// 	if(!empty($get_agent_info)){
					// 	$page_data['data']['address'] = $get_agent_info[0]['address'];
					// 	$page_data['data']['logo'] = $get_agent_info[0]['logo'];
					// 	}

					// }
					$page_data['data']['terms_conditions'] = '';
					if($terms_conditions['status'] == SUCCESS_STATUS){
						$page_data['data']['terms_conditions'] = $terms_conditions['data'][0]['description'];
						$itinerary_details = $page_data['data']['booking_details'] [0]['booking_itinerary_details'];
				$trip_type = $page_data['data']['booking_details'] [0]['trip_type'];
				 
				$BaggageSegment_checkin ='';
				foreach ($itinerary_details as $segment_details_k => $segment_details_v) {
					$seg_array [] = $segment_details_v['segment_indicator'];

        	if ($trip_type != 'multicity') {
            

            if (is_int($segment_details_v['checkin_baggage'])) {

                                        $BaggageSegment_checkin .= 'Checkin Baggage : ' . @$segment_details_v['from_airport_code'] . ' To ' . @$segment_details_v['to_airport_code'] . ' (Adult :' . (int) $segment_details_v['checkin_baggage'] . ' Kg & Child :'.(int) $segment_details_v['checkin_baggage'] .' Kg)<br />';

                                        $TotalBaggageCheckIN = (int) $segment_details_v['checkin_baggage'];

                                    } else {

                                        $BaggageSegment_checkin .= 'Checkin Baggage : ' . @$segment_details_v['from_airport_code'] . ' To ' . @$segment_details_v['to_airport_code'] . ' (Adult :' . $segment_details_v['checkin_baggage'] . ' & Child :'.$segment_details_v['checkin_baggage'] .')<br />';

                                        $TotalBaggageCheckIN = $segment_details_v['checkin_baggage'];

                                    }
			}
				}

				if($BaggageSegment_checkin !=""){
					
					$page_data['data']['terms_conditions'] =str_replace("Free Baggage Allowance:",'Free Baggage Allowance<br><span>'.$BaggageSegment_checkin.'</span>',$terms_conditions['data'][0]['description']);
					
				}
					}	
			
				}
				$voucher_details = $this->custom_db->single_table_records(' voucher_details','note,phone,email', array('id'=>get_domain_auth_id()));

				$page_data['data']['note'] = $voucher_details['data'][0]['note'];
				$page_data['data']['voucher_phone'] = $voucher_details['data'][0]['phone'];
				$page_data['data']['voucher_email'] = $voucher_details['data'][0]['email'];

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

						$pdf = $create_pdf->create_pdf($mail_template,'F');
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
			//debug($booking_details);die;
			$terms_conditions = $this->custom_db->single_table_records('terms_conditions','description', array('module' =>'flight'));
			if ($booking_details['status'] == SUCCESS_STATUS) {
				load_flight_lib(PROVAB_FLIGHT_BOOKING_SOURCE);
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_flight_booking_data($booking_details, $this->current_module);				
				$page_data['data'] = $assembled_booking_details['data'];
				
			if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
					
					$domain_address = $this->custom_db->single_table_records ( 'domain_list','address,domain_logo,domain_name,phone,phone_code',array('origin'=>get_domain_auth_id()));
					$page_data['data']['address'] =$domain_address['data'][0]['address'];
					$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
					$page_data['data']['phone'] =$domain_address['data'][0]['phone'];
					$page_data['data']['phone_code'] =$domain_address['data'][0]['phone_code'];
					$page_data['data']['domainname'] =$domain_address['data'][0]['domain_name'];
					// if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
					// 	$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
					// 	// debug($get_agent_info);exit;
					// 	if(!empty($get_agent_info)){
					// 	$page_data['data']['address'] = $get_agent_info[0]['address'];
					// 	$page_data['data']['logo'] = $get_agent_info[0]['logo'];
					// 	$page_data['data']['phone'] = $get_agent_info[0]['phone'];
					// 	$page_data['data']['domainname'] = $get_agent_info[0]['agency_name'];
					// 	}
					// }
					$page_data['data']['terms_conditions'] = '';
					if($terms_conditions['status'] == SUCCESS_STATUS){
						$page_data['data']['terms_conditions'] = $terms_conditions['data'][0]['description'];
						$itinerary_details = $page_data['data']['booking_details'] [0]['booking_itinerary_details'];
				$trip_type = $page_data['data']['booking_details'] [0]['trip_type'];
				 
				$BaggageSegment_checkin ='';
				foreach ($itinerary_details as $segment_details_k => $segment_details_v) {
					$seg_array [] = $segment_details_v['segment_indicator'];

        	if ($trip_type != 'multicity') {
            

            if (is_int($segment_details_v['checkin_baggage'])) {

                                        $BaggageSegment_checkin .= 'Checkin Baggage : ' . @$segment_details_v['from_airport_code'] . ' To ' . @$segment_details_v['to_airport_code'] . ' (Adult :' . (int) $segment_details_v['checkin_baggage'] . ' Kg & Child :'.(int) $segment_details_v['checkin_baggage'] .' Kg)<br />';

                                        $TotalBaggageCheckIN = (int) $segment_details_v['checkin_baggage'];

                                    } else {

                                        $BaggageSegment_checkin .= 'Checkin Baggage : ' . @$segment_details_v['from_airport_code'] . ' To ' . @$segment_details_v['to_airport_code'] . ' (Adult :' . $segment_details_v['checkin_baggage'] . ' & Child :'.$segment_details_v['checkin_baggage'] .')<br />';

                                        $TotalBaggageCheckIN = $segment_details_v['checkin_baggage'];

                                    }
			}
				}

				if($BaggageSegment_checkin !=""){
					
					$page_data['data']['terms_conditions'] =str_replace("Free Baggage Allowance:",'Free Baggage Allowance<br><span>'.$BaggageSegment_checkin.'</span>',$terms_conditions['data'][0]['description']);
					
				}
					}
			
				}
				$voucher_details = $this->custom_db->single_table_records(' voucher_details','note,phone,email', array('id'=>get_domain_auth_id()));

				$page_data['data']['note'] = $voucher_details['data'][0]['note'];
				$page_data['data']['voucher_phone'] = $voucher_details['data'][0]['phone'];
				$page_data['data']['voucher_email'] = $voucher_details['data'][0]['email'];
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
						$pdf = $create_pdf->create_pdf($mail_template,'F');
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
			$terms_conditions = $this->custom_db->single_table_records('terms_conditions','description', array('module' =>'hotel'));
					//echo $this->db->last_query();exit;
			if ($booking_details['status'] == SUCCESS_STATUS) {
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_hotel_booking_data($booking_details, 'b2c');
			//	$supplier_details = $this->hotel_model->get_supplier_details($assembled_booking_details[data] );
			$hc=json_decode($assembled_booking_details['data']['booking_details'][0]['attributes']);
			$page_data['supplier_details'] = $this->hotel_model->get_supplier_details($hc->HotelCode);
		//	debug($page_data['supplier_details'] );die;
				//$hc->HotelCode
				$page_data['data'] = $assembled_booking_details['data'];
				if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
				
					$domain_address = $this->custom_db->single_table_records ('domain_list','address,domain_logo,domain_name,phone,phone_code',array('origin'=>get_domain_auth_id()));
					$page_data['data']['address'] =$domain_address['data'][0]['address'];
					$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];

					$page_data['data']['phone'] = $domain_address['data'][0]['phone'];
					$page_data['data']['phone_code'] = $domain_address['data'][0]['phone_code'];
					
					$page_data['data']['domainname'] = $domain_address['data'][0]['domain_name'];
					

					// if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
					// 	$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
					// 	if(!empty($get_agent_info)){
					// 		$page_data['data']['address'] = $get_agent_info[0]['address'];
					// 		$page_data['data']['logo'] = $get_agent_info[0]['logo'];
					// 		$page_data['data']['phone'] = $get_agent_info[0]['phone'];
					// 		$page_data['data']['domainname'] = $get_agent_info[0]['domain_name'];

					// 	}
					$page_data['data']['terms_conditions'] = '';
					if($terms_conditions['status'] == SUCCESS_STATUS){
						$page_data['data']['terms_conditions'] = $terms_conditions['data'][0]['description'];
					}
			
					
				}
				$voucher_details = $this->custom_db->single_table_records(' voucher_details','note,phone,email', array('id'=>get_domain_auth_id()));

				$page_data['data']['note'] = $voucher_details['data'][0]['note'];
				$page_data['data']['voucher_phone'] = $voucher_details['data'][0]['phone'];
				$page_data['data']['voucher_email'] = $voucher_details['data'][0]['email'];
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
						$pdf = $create_pdf->create_pdf($mail_template,'F');
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
					$hc=json_decode($assembled_booking_details['data']['booking_details'][0]['attributes']);
			$page_data['supplier_details'] = $this->hotel_model->get_supplier_details($hc->HotelCode);
				$page_data['data'] = $assembled_booking_details['data'];
				if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
				
					$domain_address = $this->custom_db->single_table_records ('domain_list','address,domain_logo,phone,domain_name,email',array('origin'=>get_domain_auth_id()));
					$page_data['data']['address'] =$domain_address['data'][0]['address'];
					$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
					$page_data['data']['phone'] = $domain_address['data'][0]['phone'];
					$page_data['data']['domainname'] = $domain_address['data'][0]['domain_name'];
					$page_data['data']['email'] = $domain_address['data'][0]['email'];

					// if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
					// 	$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
					// 	if(!empty($get_agent_info)){
					// 		$page_data['data']['address'] = $get_agent_info[0]['address'];
					// 		$page_data['data']['logo'] = $get_agent_info[0]['logo'];
					// 		$page_data['data']['phone'] = $get_agent_info[0]['phone'];
					// 		$page_data['data']['domainname'] = $get_agent_info[0]['agency_name'];

					// 	}
					// }
				
				}
				$voucher_details = $this->custom_db->single_table_records(' voucher_details','note,phone,email', array('id'=>get_domain_auth_id()));

				$page_data['data']['note'] = $voucher_details['data'][0]['note'];
				$page_data['data']['voucher_phone'] = $voucher_details['data'][0]['phone'];
				$page_data['data']['voucher_email'] = $voucher_details['data'][0]['email'];
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
						$pdf = $create_pdf->create_pdf($mail_template,'F');
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
					$domain_address = $this->custom_db->single_table_records ( 'domain_list','address,domain_logo,phone_code',array('origin'=>get_domain_auth_id()));
					//print_r($domain_address);exit;//
					$page_data['data']['address'] =$domain_address['data'][0]['address'];
					$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
					$page_data['data']['phone_code'] =$domain_address['data'][0]['phone_code'];
					// if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
					// 	$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
					// 	if(!empty($get_agent_info)){
					// 		$page_data['data']['address'] = $get_agent_info[0]['address'];
					// 		$page_data['data']['logo'] = $get_agent_info[0]['logo'];
					// 	}
					// }
				
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
						$pdf = $create_pdf->create_pdf($mail_template,'F');
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
					// if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
					// 	$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
					// 	if(!empty($get_agent_info)){
					// 		$page_data['data']['address'] = $get_agent_info[0]['address'];
					// 		$page_data['data']['logo'] = $get_agent_info[0]['logo'];
					// 	}
					// }
				
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
						$pdf = $create_pdf->create_pdf($mail_template,'F');
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

                    $domain_address = $this->custom_db->single_table_records ( 'domain_list','address,domain_logo,phone,domain_name,email',array('origin'=>get_domain_auth_id()));
                    $page_data['data']['address'] =$domain_address['data'][0]['address'];
					$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
					$page_data['data']['phone'] =$domain_address['data'][0]['phone'];
					$page_data['data']['domainname'] =$domain_address['data'][0]['domain_name'];
					$page_data['data']['email'] =$domain_address['data'][0]['email'];
					if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
						$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
						if(!empty($get_agent_info)){
							$page_data['data']['address'] = $get_agent_info[0]['address'];
							// $page_data['data']['logo'] = $get_agent_info[0]['logo'];
							if($get_agent_info[0]['logo'] !=""){

								$page_data['data']['logo'] =$get_agent_info[0]['logo'];
							}
							else
							{
								$page_data['data']['logo'] =$domain_address['data'][0]['domain_logo'];
							}
							$page_data['data']['phone'] = $get_agent_info[0]['phone'];
							$page_data['data']['domainname'] = $get_agent_info[0]['agency_name'];
						}
					}
                   
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
                        $pdf = $create_pdf->create_pdf($mail_template, 'F');
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
			// debug($data);exit;
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
        
         function flight_invoice_GST($app_reference, $booking_source='', $booking_status='', $module='')
	{
        error_reporting(0);
        $this->load->model('flight_model');
		if (empty($app_reference) == false) {
			$booking_details = $this->flight_model->get_booking_details($app_reference, $booking_source, $booking_status);
			// debug($booking_details);exit;
			if ($booking_details['status'] == SUCCESS_STATUS) {
				load_flight_lib(PROVAB_FLIGHT_BOOKING_SOURCE);
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_flight_booking_data($booking_details, $this->current_module);				
				$page_data['data'] = $assembled_booking_details['data'];
				
			               if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
					$domain_address = $this->custom_db->single_table_records ( 'domain_list','address,domain_logo,domain_name,phone',array('origin'=>get_domain_auth_id()));

					$page_data['admin_details']['address'] =$domain_address['data'][0]['address'];
					$page_data['admin_details']['logo'] = $domain_address['data'][0]['domain_logo'];
					$page_data['admin_details']['phone'] =$domain_address['data'][0]['phone'];
					$page_data['admin_details']['domainname'] =$domain_address['data'][0]['domain_name'];
					
						if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
							$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
							
							if(!empty($get_agent_info)){
							$page_data['data']['address'] = $get_agent_info[0]['address'];
							if($get_agent_info[0]['logo'] !=""){

								$page_data['data']['logo'] =$get_agent_info[0]['logo'];
							}
							else
							{
								$page_data['data']['logo'] =$domain_address['data'][0]['domain_logo'];
							}
							$page_data['data']['phone'] = $get_agent_info[0]['phone'];
							$page_data['data']['domainname'] = $get_agent_info[0]['agency_name'];
							}
						}else{
							$page_data['data']['address'] = $assembled_booking_details['data']['booking_details'][0]['cutomer_address'];
						
							$page_data['data']['phone'] = $assembled_booking_details['data']['booking_details'][0]['lead_pax_phone_number'];
							$page_data['data']['domainname'] = $assembled_booking_details['data']['booking_details'][0]['lead_pax_name'];

							$page_data['data']['domaincountry']= $assembled_booking_details['data']['booking_details'][0]['cutomer_country'];
						}
			        }
                     
                    // debug($page_data);
                    // exit;
                    $page_data['module'] =$module;
                    $this->template->view('voucher/flight_invoice_new', $page_data);
			        }
                                
		}
    }
    public function all_flight_invoice_GSTold($user_type='') {
    	// debug($op);exit;
    	// error_reporting(E_ALL);
        $this->load->model('flight_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('flight');
        $condition = $filter_data['filter_condition'];
        $flight_booking_data = $this->flight_model->b2c_flight_report_invoice($user_type,$condition, false, 0, 30);
        // debug(count($flight_booking_data));exit;
       if(count($flight_booking_data) > EXPORT_CHUNK_COUNT_INVOICE_FLIGHT) {
            $this->flight_report_invoice_export_pdf_zip($flight_booking_data, 'b2c', 'b2c_flight_report_invoice_'.$from_date.'_'.$to_date);
        } else {
            $this->flight_report_invoice_export_pdf($flight_booking_data, 'b2c', 'b2c_flight_report_invoice_'.$from_date.'_'.$to_date);
        }

        
    }
    private function format_basic_search_filters($module='')
	{
		$get_data = $this->input->get();


		if(valid_array($get_data) == true) {
			$filter_condition = array();
			//From-Date and To-Date
			$from_date = trim(@$get_data['created_datetime_from']);
			$to_date = trim(@$get_data['created_datetime_to']);
			//Auto swipe date
			if(empty($from_date) == false && empty($to_date) == false)
			{
				$valid_dates = auto_swipe_dates($from_date, $to_date);
				$from_date = $valid_dates['from_date'];
				$to_date = $valid_dates['to_date'];
			}
			if(empty($from_date) == false) {
				$filter_condition[] = array('BD.created_datetime', '>=', $this->db->escape(db_current_datetime($from_date)));
			}
			if(empty($to_date) == false) {
				$filter_condition[] = array('BD.created_datetime', '<=', $this->db->escape(db_current_datetime($to_date)));
			}
	
			/*if (empty($get_data['created_by_id']) == false) {
				$filter_condition[] = array('BD.created_by_id', '=', $this->db->escape($get_data['created_by_id']));
			}*/
			
			if (empty($get_data['created_by_id']) == false && strtolower($get_data['created_by_id'])!='all') {
				$filter_condition[] = array('BD.created_by_id', '=', $this->db->escape($get_data['created_by_id']));
			}
	
			if (empty($get_data['status']) == false && strtolower($get_data['status']) != 'all') {
				$filter_condition[] = array('BD.status', '=', $this->db->escape($get_data['status']));
			}
		
			/*if (empty($get_data['phone']) == false) {
				$filter_condition[] = array('BD.phone', ' like ', $this->db->escape('%'.$get_data['phone'].'%'));
			}
	
			if (empty($get_data['email']) == false) {
				$filter_condition[] = array('BD.email', ' like ', $this->db->escape('%'.$get_data['email'].'%'));
			}*/
			
			if($module == 'bus'){
					if (empty($get_data['pnr']) == false) {
					$filter_condition[] = array('BD.pnr', ' like ', $this->db->escape('%'.$get_data['pnr'].'%'));
				}
			}else{
				if (empty($get_data['pnr']) == false) {
					$filter_condition[] = array('BT.pnr', ' like ', $this->db->escape('%'.$get_data['pnr'].'%'));
				}
			}
			
	
			if (empty($get_data['app_reference']) == false) {
				$filter_condition[] = array('BD.app_reference', ' like ', $this->db->escape('%'.$get_data['app_reference'].'%'));
			}
			
			$page_data['from_date'] = $from_date;
			$page_data['to_date'] = $to_date;

			//Today's Booking Data
			if(isset($get_data['today_booking_data']) == true && empty($get_data['today_booking_data']) == false) {
				$filter_condition[] = array('DATE(BD.created_datetime)', '=', '"'.date('Y-m-d').'"');
			}
			//Last day Booking Data
			if(isset($get_data['last_day_booking_data']) == true && empty($get_data['last_day_booking_data']) == false) {
				$filter_condition[] = array('DATE(BD.created_datetime)', '=', '"'.trim($get_data['last_day_booking_data']).'"');
			}
			//Previous Booking Data: last 3 days, 7 days, 15 days, 1 month and 3 month
			if(isset($get_data['prev_booking_data']) == true && empty($get_data['prev_booking_data']) == false) {
				$filter_condition[] = array('DATE(BD.created_datetime)', '>=', '"'.trim($get_data['prev_booking_data']).'"');
			}
			
			return array('filter_condition' => $filter_condition, 'from_date' => $from_date, 'to_date' => $to_date);
		}
	}
        
      
    function hotel_invoice_GST($app_reference, $booking_source='', $booking_status='', $module='')
	{
        error_reporting(0);
        $this->load->model('hotel_model');
		if (empty($app_reference) == false) {
			$booking_details = $this->hotel_model->get_booking_details($app_reference, $booking_source, $booking_status);
			if ($booking_details['status'] == SUCCESS_STATUS) {
				load_flight_lib(PROVAB_FLIGHT_BOOKING_SOURCE);
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_hotel_booking_data($booking_details, $this->current_module);	
				
				$page_data['data'] = $assembled_booking_details['data'];
				
			               if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
					$domain_address = $this->custom_db->single_table_records ( 'domain_list','address,domain_logo,domain_name,phone,email',array('origin'=>get_domain_auth_id()));

					$page_data['admin_details']['address'] =$domain_address['data'][0]['address'];
					$page_data['admin_details']['logo'] = $domain_address['data'][0]['domain_logo'];
					$page_data['admin_details']['phone'] =$domain_address['data'][0]['phone'];
					$page_data['admin_details']['domainname'] =$domain_address['data'][0]['domain_name'];
					$page_data['admin_details']['email'] =$domain_address['data'][0]['email'];
					
						if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
							$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
							
							if(!empty($get_agent_info)){
							$page_data['data']['address'] = $get_agent_info[0]['address'];
							if($get_agent_info[0]['logo'] !=""){

								$page_data['data']['logo'] =$get_agent_info[0]['logo'];
							}
							else
							{
								$page_data['data']['logo'] =$domain_address['data'][0]['domain_logo'];
							}
							$page_data['data']['phone'] = $get_agent_info[0]['phone'];
							$page_data['data']['domainname'] = $get_agent_info[0]['agency_name'];
							}
						}else{
							$page_data['data']['address'] = $assembled_booking_details['data']['booking_details'][0]['cutomer_address'];
						
							$page_data['data']['phone'] = $assembled_booking_details['data']['booking_details'][0]['lead_pax_phone_number'];
							$page_data['data']['domainname'] = $assembled_booking_details['data']['booking_details'][0]['lead_pax_name'];

							$page_data['data']['domaincountry']= $assembled_booking_details['data']['booking_details'][0]['cutomer_country'];
						}
			        }
                     
                    // debug($page_data);
                    // exit;
                    $page_data['module'] =$module;
                    $this->template->view('voucher/hotel_invoice', $page_data);
			        }
                                
		}
    }


    function all_hotel_invoice_GST($user_type='')
	{
        $this->load->model('hotel_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('flight');
        $condition = $filter_data['filter_condition'];
        $flight_booking_data = $this->hotel_model->b2c_hotel_report_all_invoice($user_type,$condition, false, 0, 30);
        // debug(count($flight_booking_data));exit;
        foreach ($flight_booking_data as $key => $value) {
        	
        
        $app_reference=$value['app_reference'];
        $booking_source=$value['booking_source'];
        $booking_status=$value['status'];
        // debug($booking_source);exit;
        
		if (empty($app_reference) == false) {
			$booking_details = $this->hotel_model->get_booking_details($app_reference, $booking_source, $booking_status);
			if ($booking_details['status'] == SUCCESS_STATUS) {
				load_flight_lib(PROVAB_FLIGHT_BOOKING_SOURCE);
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_hotel_booking_data($booking_details, $this->current_module);				
				$page_data['data'][$key] = $assembled_booking_details['data'];
				
			               if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
					$domain_address = $this->custom_db->single_table_records ( 'domain_list','address,domain_logo,domain_name,phone',array('origin'=>get_domain_auth_id()));

					$page_data['admin_details'][$key]['address'] =$domain_address['data'][0]['address'];
					$page_data['admin_details'][$key]['logo'] = $domain_address['data'][0]['domain_logo'];
					$page_data['admin_details'][$key]['phone'] =$domain_address['data'][0]['phone'];
					$page_data['admin_details'][$key]['domainname'] =$domain_address['data'][0]['domain_name'];
					
						if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
							$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
							
							if(!empty($get_agent_info)){
							$page_data['data'][$key]['address'] = $get_agent_info[0]['address'];
							if($get_agent_info[0]['logo'] !=""){

								$page_data['data']['logo'] =$get_agent_info[0]['logo'];
							}
							else
							{
								$page_data['data']['logo'] =$domain_address['data'][0]['domain_logo'];
							}
							$page_data['data'][$key]['phone'] = $get_agent_info[0]['phone'];
							$page_data['data'][$key]['domainname'] = $get_agent_info[0]['agency_name'];
							}
						}else{
							$page_data['data'][$key]['address'] = $assembled_booking_details['data']['booking_details'][0]['cutomer_address'];
						
							$page_data['data'][$key]['phone'] = $assembled_booking_details['data']['booking_details'][0]['lead_pax_phone_number'];
							$page_data['data'][$key]['domainname'] = $assembled_booking_details['data']['booking_details'][0]['lead_pax_name'];

							$page_data['data'][$key]['domaincountry']= $assembled_booking_details['data']['booking_details'][0]['cutomer_country'];
						}
			        }
                     
                    
			        }
                                
		}

        
        
       

        
        	
  		}

  			// debug($page_data);exit;

  			 $mail_template =$this->template->isolated_view('voucher/hotel_invoice_new_all_pdf', $page_data);
  			$this->load->library ( 'provab_pdf' );
  			$this->load->library ( 'provab_mailer' ); 
  			$create_pdf = new Provab_Pdf();
  			// $pdf_data['export_data']=$flight_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			// $mail_template =$this->template->isolated_view('voucher/flight_invoice_new_all_pdf',$pdf_data);
  			$this->provab_pdf->create_pdf($mail_template);
    }
    function all_bus_invoice_GST($user_type='')
	{
        $this->load->model('bus_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('flight');
        $condition = $filter_data['filter_condition'];
        $flight_booking_data = $this->bus_model->b2c_bus_report_all_invoice($user_type,$condition, false, 0, 30);
        // debug(count($flight_booking_data));exit;
        foreach ($flight_booking_data as $key => $value) {
        	
        
        $app_reference=$value['app_reference'];
        $booking_source=$value['booking_source'];
        $booking_status=$value['status'];
        // debug($booking_source);exit;
        
		if (empty($app_reference) == false) {
			$booking_details = $this->bus_model->get_booking_details($app_reference, $booking_source, $booking_status);
			if ($booking_details['status'] == SUCCESS_STATUS) {
				load_flight_lib(PROVAB_FLIGHT_BOOKING_SOURCE);
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_bus_booking_data($booking_details, $this->current_module);				
				$page_data['data'][$key] = $assembled_booking_details['data'];
				
                if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
					$domain_address = $this->custom_db->single_table_records ( 'domain_list','address,domain_logo,domain_name,phone',array('origin'=>get_domain_auth_id()));

					$page_data['admin_details'][$key]['address'] =$domain_address['data'][0]['address'];
					$page_data['admin_details'][$key]['logo'] = $domain_address['data'][0]['domain_logo'];
					$page_data['admin_details'][$key]['phone'] =$domain_address['data'][0]['phone'];
					$page_data['admin_details'][$key]['domainname'] =$domain_address['data'][0]['domain_name'];
					
						if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
							$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
							
							if(!empty($get_agent_info)){
							$page_data['data'][$key]['address'] = $get_agent_info[0]['address'];
							if($get_agent_info[0]['logo'] !=""){

								$page_data['data']['logo'] =$get_agent_info[0]['logo'];
							}
							else
							{
								$page_data['data']['logo'] =$domain_address['data'][0]['domain_logo'];
							}
							$page_data['data'][$key]['phone'] = $get_agent_info[0]['phone'];
							$page_data['data'][$key]['domainname'] = $get_agent_info[0]['agency_name'];
							}
						}else{
							$page_data['data'][$key]['address'] = $assembled_booking_details['data']['booking_details'][0]['cutomer_address'];
						
							$page_data['data'][$key]['phone'] = $assembled_booking_details['data']['booking_details'][0]['lead_pax_phone_number'];
							$page_data['data'][$key]['domainname'] = $assembled_booking_details['data']['booking_details'][0]['lead_pax_name'];

							$page_data['data'][$key]['domaincountry']= $assembled_booking_details['data']['booking_details'][0]['cutomer_country'];
						}
			        }
                     
                    // debug($page_data);
                    // exit;
                    
			        }
                                
		}

        
        
       

        
        	
  		}

  			// debug($page_data);exit;

  			 $mail_template =$this->template->isolated_view('voucher/bus_invoice_new_all_pdf', $page_data);
  			$this->load->library ( 'provab_pdf' );
  			$this->load->library ( 'provab_mailer' ); 
  			$create_pdf = new Provab_Pdf();
  			// $pdf_data['export_data']=$flight_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			// $mail_template =$this->template->isolated_view('voucher/flight_invoice_new_all_pdf',$pdf_data);
  			$this->provab_pdf->create_pdf($mail_template);
    }  

     function bus_invoice_GST($app_reference, $booking_source='', $booking_status='', $module='')
	{
            error_reporting(0);
            $this->load->model('bus_model');
		if (empty($app_reference) == false) {
			$booking_details = $this->bus_model->get_booking_details($app_reference, $booking_source, $booking_status);
			if ($booking_details['status'] == SUCCESS_STATUS) {
				load_flight_lib(PROVAB_FLIGHT_BOOKING_SOURCE);
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_bus_booking_data($booking_details, $this->current_module);				
				$page_data['data'] = $assembled_booking_details['data'];
				
                if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
					$domain_address = $this->custom_db->single_table_records ( 'domain_list','address,domain_logo,domain_name,phone',array('origin'=>get_domain_auth_id()));

					$page_data['admin_details']['address'] =$domain_address['data'][0]['address'];
					$page_data['admin_details']['logo'] = $domain_address['data'][0]['domain_logo'];
					$page_data['admin_details']['phone'] =$domain_address['data'][0]['phone'];
					$page_data['admin_details']['domainname'] =$domain_address['data'][0]['domain_name'];
					
						if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
							$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
							
							if(!empty($get_agent_info)){
							$page_data['data']['address'] = $get_agent_info[0]['address'];
							if($get_agent_info[0]['logo'] !=""){

								$page_data['data']['logo'] =$get_agent_info[0]['logo'];
							}
							else
							{
								$page_data['data']['logo'] =$domain_address['data'][0]['domain_logo'];
							}
							$page_data['data']['phone'] = $get_agent_info[0]['phone'];
							$page_data['data']['domainname'] = $get_agent_info[0]['agency_name'];
							}
						}else{
							$page_data['data']['address'] = $assembled_booking_details['data']['booking_details'][0]['cutomer_address'];
						
							$page_data['data']['phone'] = $assembled_booking_details['data']['booking_details'][0]['lead_pax_phone_number'];
							$page_data['data']['domainname'] = $assembled_booking_details['data']['booking_details'][0]['lead_pax_name'];

							$page_data['data']['domaincountry']= $assembled_booking_details['data']['booking_details'][0]['cutomer_country'];
						}
			        }
                     
                    // debug($page_data);
                    // exit;
                    $page_data['module'] =$module;
                    $this->template->view('voucher/bus_invoice', $page_data);
			        }
                                
		}
    }  
    function activity_invoice_GST($app_reference, $booking_source='', $booking_status='', $module='')
	{
            error_reporting(0);
            $this->load->model('sightseeing_model');
		if (empty($app_reference) == false) {
			$booking_details = $this->sightseeing_model->get_booking_details($app_reference, $booking_source, $booking_status);
			if ($booking_details['status'] == SUCCESS_STATUS) {
				load_flight_lib(PROVAB_FLIGHT_BOOKING_SOURCE);
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_sightseeing_booking_data($booking_details, $this->current_module);				
				$page_data['data'] = $assembled_booking_details['data'];
				
			               if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
					$domain_address = $this->custom_db->single_table_records ( 'domain_list','address,domain_logo,domain_name,phone,email',array('origin'=>get_domain_auth_id()));

					$page_data['admin_details']['address'] =$domain_address['data'][0]['address'];
					$page_data['admin_details']['logo'] = $domain_address['data'][0]['domain_logo'];
					$page_data['admin_details']['phone'] =$domain_address['data'][0]['phone'];
					$page_data['admin_details']['domainname'] =$domain_address['data'][0]['domain_name'];
					$page_data['admin_details']['email'] =$domain_address['data'][0]['email'];
					
						if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
							$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
							
							if(!empty($get_agent_info)){
							$page_data['data']['address'] = $get_agent_info[0]['address'];
							$page_data['data']['logo'] = $get_agent_info[0]['logo'];
							$page_data['data']['phone'] = $get_agent_info[0]['phone'];
							$page_data['data']['domainname'] = $get_agent_info[0]['agency_name'];
							}
						}else{
							$page_data['data']['address'] = $assembled_booking_details['data']['booking_details'][0]['cutomer_address'];
						
							$page_data['data']['phone'] = $assembled_booking_details['data']['booking_details'][0]['lead_pax_phone_number'];
							$page_data['data']['domainname'] = $assembled_booking_details['data']['booking_details'][0]['lead_pax_name'];

							$page_data['data']['domaincountry']= $assembled_booking_details['data']['booking_details'][0]['cutomer_country'];
						}
			        }
                     
                    // debug($page_data);
                    // exit;
                    $page_data['module'] =$module;
                    $this->template->view('voucher/activity_invoice', $page_data);
			        }
                                
		}
    }
    function all_activities_invoice_GST($user_type='')
	{
        $this->load->model('sightseeing_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('flight');
        $condition = $filter_data['filter_condition'];
        $flight_booking_data = $this->sightseeing_model->b2c_sightseeing_report_all_invoice($user_type,$condition, false, 0, 30);
        // debug(count($flight_booking_data));exit;
        foreach ($flight_booking_data as $key => $value) {
        	
        
        $app_reference=$value['app_reference'];
        $booking_source=$value['booking_source'];
        $booking_status=$value['status'];
        // debug($booking_source);exit;
        
		if (empty($app_reference) == false) {
			$booking_details = $this->sightseeing_model->get_booking_details($app_reference, $booking_source, $booking_status);
			if ($booking_details['status'] == SUCCESS_STATUS) {
				load_flight_lib(PROVAB_FLIGHT_BOOKING_SOURCE);
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_sightseeing_booking_data($booking_details, $this->current_module);				
				$page_data['data'][$key] = $assembled_booking_details['data'];
				
			               if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
					$domain_address = $this->custom_db->single_table_records ( 'domain_list','address,domain_logo,domain_name,phone',array('origin'=>get_domain_auth_id()));

					$page_data['admin_details'][$key]['address'] =$domain_address['data'][0]['address'];
					$page_data['admin_details'][$key]['logo'] = $domain_address['data'][0]['domain_logo'];
					$page_data['admin_details'][$key]['phone'] =$domain_address['data'][0]['phone'];
					$page_data['admin_details'][$key]['domainname'] =$domain_address['data'][0]['domain_name'];
					
						if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
							$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
							
							if(!empty($get_agent_info)){
							$page_data['data'][$key]['address'] = $get_agent_info[0]['address'];
							$page_data['data'][$key]['logo'] = $get_agent_info[0]['logo'];
							$page_data['data'][$key]['phone'] = $get_agent_info[0]['phone'];
							$page_data['data'][$key]['domainname'] = $get_agent_info[0]['agency_name'];
							}
						}else{
							$page_data['data'][$key]['address'] = $assembled_booking_details['data']['booking_details'][0]['cutomer_address'];
						
							$page_data['data'][$key]['phone'] = $assembled_booking_details['data']['booking_details'][0]['lead_pax_phone_number'];
							$page_data['data'][$key]['domainname'] = $assembled_booking_details['data']['booking_details'][0]['lead_pax_name'];

							$page_data['data'][$key]['domaincountry']= $assembled_booking_details['data']['booking_details'][0]['cutomer_country'];
						}
			        }
                     
                    // debug($page_data);
                    // exit;
                    
			        }
                                
		}

        
        
       

        
        	
  		}

  			// debug($page_data);exit;

  			 $mail_template =$this->template->isolated_view('voucher/activity_invoice_new_all_pdf', $page_data);
  			$this->load->library ( 'provab_pdf' );
  			$this->load->library ( 'provab_mailer' ); 
  			$create_pdf = new Provab_Pdf();
  			// $pdf_data['export_data']=$flight_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			// $mail_template =$this->template->isolated_view('voucher/flight_invoice_new_all_pdf',$pdf_data);
  			$this->provab_pdf->create_pdf($mail_template);
    }
    function transfer_invoice_GST($app_reference, $booking_source='', $booking_status='', $module='')
	{
            error_reporting(0);
        $this->load->model('transferv1_model');
		if (empty($app_reference) == false) {
			$booking_details = $this->transferv1_model->get_booking_details($app_reference, $booking_source, $booking_status);
			if ($booking_details['status'] == SUCCESS_STATUS) {
				load_flight_lib(PROVAB_FLIGHT_BOOKING_SOURCE);
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_transferv1_booking_data($booking_details, $this->current_module);				
				$page_data['data'] = $assembled_booking_details['data'];
				
               if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
					$domain_address = $this->custom_db->single_table_records ( 'domain_list','address,domain_logo,domain_name,phone,email',array('origin'=>get_domain_auth_id()));

					$page_data['admin_details']['address'] =$domain_address['data'][0]['address'];
					$page_data['admin_details']['logo'] = $domain_address['data'][0]['domain_logo'];
					$page_data['admin_details']['phone'] =$domain_address['data'][0]['phone'];
					$page_data['admin_details']['domainname'] =$domain_address['data'][0]['domain_name'];
					$page_data['admin_details']['email'] =$domain_address['data'][0]['email'];
					
						if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
							$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
							
							if(!empty($get_agent_info)){
							$page_data['data']['address'] = $get_agent_info[0]['address'];
							$page_data['data']['logo'] = $get_agent_info[0]['logo'];
							$page_data['data']['phone'] = $get_agent_info[0]['phone'];
							$page_data['data']['domainname'] = $get_agent_info[0]['agency_name'];
							}
						}else{
							$page_data['data']['address'] = $assembled_booking_details['data']['booking_details'][0]['cutomer_address'];
						
							$page_data['data']['phone'] = $assembled_booking_details['data']['booking_details'][0]['lead_pax_phone_number'];
							$page_data['data']['domainname'] = $assembled_booking_details['data']['booking_details'][0]['lead_pax_name'];

							$page_data['data']['domaincountry']= $assembled_booking_details['data']['booking_details'][0]['cutomer_country'];
						}
			        }
                     
                    // debug($page_data);
                    // exit;
                    $page_data['module'] =$module;
                    $this->template->view('voucher/transfer_invoice', $page_data);
			        }
                                
		}
    }
    function all_transfer_invoice_GST($user_type='')
	{
        $this->load->model('transferv1_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('flight');
        $condition = $filter_data['filter_condition'];
        $flight_booking_data = $this->transferv1_model->b2c_transferv1_report_all_invoice($user_type,$condition, false, 0, 30);
        // debug(count($flight_booking_data));exit;
        foreach ($flight_booking_data as $key => $value) {
        	
        
        $app_reference=$value['app_reference'];
        $booking_source=$value['booking_source'];
        $booking_status=$value['status'];
        // debug($booking_source);exit;
        
		
        	if (empty($app_reference) == false) {
			$booking_details = $this->transferv1_model->get_booking_details($app_reference, $booking_source, $booking_status);
			if ($booking_details['status'] == SUCCESS_STATUS) {
				load_flight_lib(PROVAB_FLIGHT_BOOKING_SOURCE);
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_transferv1_booking_data($booking_details, $this->current_module);				
				$page_data['data'][$key] = $assembled_booking_details['data'];
				
               if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
					$domain_address = $this->custom_db->single_table_records ( 'domain_list','address,domain_logo,domain_name,phone',array('origin'=>get_domain_auth_id()));

					$page_data['admin_details'][$key]['address'] =$domain_address['data'][0]['address'];
					$page_data['admin_details'][$key]['logo'] = $domain_address['data'][0]['domain_logo'];
					$page_data['admin_details'][$key]['phone'] =$domain_address['data'][0]['phone'];
					$page_data['admin_details'][$key]['domainname'] =$domain_address['data'][0]['domain_name'];
					
						if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
							$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
							
							if(!empty($get_agent_info)){
							$page_data['data'][$key]['address'] = $get_agent_info[0]['address'];
							$page_data['data'][$key]['logo'] = $get_agent_info[0]['logo'];
							$page_data['data'][$key]['phone'] = $get_agent_info[0]['phone'];
							$page_data['data'][$key]['domainname'] = $get_agent_info[0]['agency_name'];
							}
						}else{
							$page_data['data'][$key]['address'] = $assembled_booking_details['data']['booking_details'][0]['cutomer_address'];
						
							$page_data['data'][$key]['phone'] = $assembled_booking_details['data']['booking_details'][0]['lead_pax_phone_number'];
							$page_data['data'][$key]['domainname'] = $assembled_booking_details['data']['booking_details'][0]['lead_pax_name'];

							$page_data['data'][$key]['domaincountry']= $assembled_booking_details['data']['booking_details'][0]['cutomer_country'];
						}
			        }
                     
                    // debug($page_data);
                    // exit;
                    
			        }
                                
		}
        
        
       

        
        	
  		}

  			// debug($page_data);exit;

  			 $mail_template =$this->template->isolated_view('voucher/transfer_invoice_new_all_pdf', $page_data);
  			$this->load->library ( 'provab_pdf' );
  			$this->load->library ( 'provab_mailer' ); 
  			$create_pdf = new Provab_Pdf();
  			// $pdf_data['export_data']=$flight_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			// $mail_template =$this->template->isolated_view('voucher/flight_invoice_new_all_pdf',$pdf_data);
  			$this->provab_pdf->create_pdf($mail_template);
    }
        
	function b2c_sightseeing_voucher($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$email='')
	{
		$this->load->model('sightseeing_model');
		if (empty($app_reference) == false) {
			$booking_details = $this->sightseeing_model->get_booking_details($app_reference, $booking_source, $booking_status);
			$terms_conditions = $this->custom_db->single_table_records('terms_conditions','description', array('module' =>'activity'));
			
			if ($booking_details['status'] == SUCCESS_STATUS) {
				load_sightseen_lib(PROVAB_SIGHTSEEN_BOOKING_SOURCE);
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_sightseeing_booking_data($booking_details, 'b2c', false);				
				$page_data['data'] = $assembled_booking_details['data'];
				
			if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
					
					$domain_address = $this->custom_db->single_table_records ( 'domain_list','address,domain_logo,domain_name,phone,phone_code',array('origin'=>get_domain_auth_id()));
					$page_data['data']['address'] =$domain_address['data'][0]['address'];
					$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
					$page_data['data']['phone'] = $domain_address['data'][0]['phone'];
					$page_data['data']['phone_code'] = $domain_address['data'][0]['phone_code'];
					
					$page_data['data']['domainname'] = $domain_address['data'][0]['domain_name'];

					// if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
					// 	$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
					// 	if(!empty($get_agent_info)){
					// 		$page_data['data']['address'] = $get_agent_info[0]['address'];
					// 		$page_data['data']['logo'] = $get_agent_info[0]['logo'];
					// 		$page_data['data']['phone'] = $get_agent_info[0]['phone'];
					// 		$page_data['data']['domainname'] = $get_agent_info[0]['domain_name'];
					// 	}
					// }
					$page_data['data']['terms_conditions'] = '';
					if($terms_conditions['status'] == SUCCESS_STATUS){
						$page_data['data']['terms_conditions'] = $terms_conditions['data'][0]['description'];
					}
			
				}
				$voucher_details = $this->custom_db->single_table_records(' voucher_details','note,phone,email', array('id'=>get_domain_auth_id()));

				$page_data['data']['note'] = $voucher_details['data'][0]['note'];
				$page_data['data']['voucher_phone'] = $voucher_details['data'][0]['phone'];
				$page_data['data']['voucher_email'] = $voucher_details['data'][0]['email'];
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
				   case 'email_voucher':
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$mail_template = $this->template->isolated_view('voucher/sightseeing_pdf', $page_data);
						$pdf = $create_pdf->create_pdf($mail_template,'');
						$this->provab_mailer->send_mail($email, domain_name().' - Sightseeing Ticket',$mail_template ,$pdf);
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
			$terms_conditions = $this->custom_db->single_table_records('terms_conditions','description', array('module' =>'activity'));
			if ($booking_details['status'] == SUCCESS_STATUS) {
				load_sightseen_lib(PROVAB_SIGHTSEEN_BOOKING_SOURCE);
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_sightseeing_booking_data($booking_details,'b2b');				
				$page_data['data'] = $assembled_booking_details['data'];
				
			if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
					
					$domain_address = $this->custom_db->single_table_records ( 'domain_list','address,domain_logo,phone,domain_name,phone_code,email',array('origin'=>get_domain_auth_id()));
					$page_data['data']['address'] =$domain_address['data'][0]['address'];
					$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
					$page_data['data']['phone'] = $domain_address['data'][0]['phone'];
					$page_data['data']['phone_code'] = $domain_address['data'][0]['phone_code'];
					$page_data['data']['domainname'] = $domain_address['data'][0]['domain_name'];
					$page_data['data']['email'] = $domain_address['data'][0]['email'];
					// if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
					// 	$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
					// 	if(!empty($get_agent_info)){
					// 	$page_data['data']['address'] = $get_agent_info[0]['address'];
					// 	$page_data['data']['logo'] = $get_agent_info[0]['logo'];
					// 	$page_data['data']['phone'] = $get_agent_info[0]['phone'];
					// 	$page_data['data']['domainname'] = $get_agent_info[0]['agency_name'];
					// 	}
					// }
					$page_data['data']['terms_conditions'] = '';
					if($terms_conditions['status'] == SUCCESS_STATUS){
						$page_data['data']['terms_conditions'] = $terms_conditions['data'][0]['description'];
					}
			
				}
				$voucher_details = $this->custom_db->single_table_records(' voucher_details','note,phone,email', array('id'=>get_domain_auth_id()));

				$page_data['data']['note'] = $voucher_details['data'][0]['note'];
				$page_data['data']['voucher_phone'] = $voucher_details['data'][0]['phone'];
				$page_data['data']['voucher_email'] = $voucher_details['data'][0]['email'];
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
						$this->provab_mailer->send_mail($email, domain_name().' - Sightseeing Ticket',$mail_template ,$pdf);
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
			$terms_conditions = $this->custom_db->single_table_records('terms_conditions','description', array('module' =>'transfers'));
			if ($booking_details['status'] == SUCCESS_STATUS) {
				load_transferv1_lib(PROVAB_TRANSFERV1_BOOKING_SOURCE);
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_transferv1_booking_data($booking_details, 'b2c', false);				
				$page_data['data'] = $assembled_booking_details['data'];
				
			if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
					
					$domain_address = $this->custom_db->single_table_records ( 'domain_list','address,domain_logo,phone,domain_name,phone_code,email',array('origin'=>get_domain_auth_id()));
					$page_data['data']['address'] =$domain_address['data'][0]['address'];
					$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
					$page_data['data']['phone'] = $domain_address['data'][0]['phone'];
					$page_data['data']['phone_code'] = $domain_address['data'][0]['phone_code'];
					$page_data['data']['domainname'] = $domain_address['data'][0]['domain_name'];
					$page_data['data']['email'] = $domain_address['data'][0]['email'];

					// if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
					// 	$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
					// 	if(!empty($get_agent_info)){
					// 		$page_data['data']['address'] = $get_agent_info[0]['address'];
					// 		$page_data['data']['logo'] = $get_agent_info[0]['logo'];
					// 		$page_data['data']['phone'] = $get_agent_info[0]['phone'];
					// 		$page_data['data']['domainname'] = $get_agent_info[0]['domain_name'];

					// 	}
					// }
					$page_data['data']['terms_conditions'] = '';
					if($terms_conditions['status'] == SUCCESS_STATUS){
						$page_data['data']['terms_conditions'] = $terms_conditions['data'][0]['description'];
					}
			
				}
				$voucher_details = $this->custom_db->single_table_records(' voucher_details','note,phone,email', array('id'=>get_domain_auth_id()));

				$page_data['data']['note'] = $voucher_details['data'][0]['note'];
				$page_data['data']['voucher_phone'] = $voucher_details['data'][0]['phone'];
				$page_data['data']['voucher_email'] = $voucher_details['data'][0]['email'];
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
	function b2b_transfers_voucher($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$email='')
	{
		$this->load->model('transferv1_model');
		if (empty($app_reference) == false) {
			$booking_details = $this->transferv1_model->get_booking_details($app_reference, $booking_source, $booking_status);
			$terms_conditions = $this->custom_db->single_table_records('terms_conditions','description', array('module' =>'transfers'));
			if ($booking_details['status'] == SUCCESS_STATUS) {
				load_transferv1_lib(PROVAB_TRANSFERV1_BOOKING_SOURCE);
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_transferv1_booking_data($booking_details, $this->current_module);				
				$page_data['data'] = $assembled_booking_details['data'];
				
			if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
					
					$domain_address = $this->custom_db->single_table_records ( 'domain_list','address,domain_logo,phone,domain_name,phone_code,email',array('origin'=>get_domain_auth_id()));
					$page_data['data']['address'] =$domain_address['data'][0]['address'];
					$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
					$page_data['data']['phone'] = $domain_address['data'][0]['phone'];
					$page_data['data']['phone_code'] = $domain_address['data'][0]['phone_code'];
					$page_data['data']['domainname'] = $domain_address['data'][0]['domain_name'];
					$page_data['data']['email'] = $domain_address['data'][0]['email'];

					// if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
					// 	$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
					// 	if(!empty($get_agent_info)){
					// 		$page_data['data']['address'] = $get_agent_info[0]['address'];
					// 		$page_data['data']['logo'] = $get_agent_info[0]['logo'];
					// 		$page_data['data']['phone'] = $get_agent_info[0]['phone'];
					// 		$page_data['data']['domainname'] = $get_agent_info[0]['agency_name'];
					// 	}
					// }
					$page_data['data']['terms_conditions'] = '';
					if($terms_conditions['status'] == SUCCESS_STATUS){
						$page_data['data']['terms_conditions'] = $terms_conditions['data'][0]['description'];
					}
			
				}
				$voucher_details = $this->custom_db->single_table_records(' voucher_details','note,phone,email', array('id'=>get_domain_auth_id()));

				$page_data['data']['note'] = $voucher_details['data'][0]['note'];
				$page_data['data']['voucher_phone'] = $voucher_details['data'][0]['phone'];
				$page_data['data']['voucher_email'] = $voucher_details['data'][0]['email'];
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




	function holiday($app_reference , $booking_status='', $operation='show_voucher',$email ='') 
	{
 	// error_reporting(E_ALL);
  $this->load->model(array('tours_model','custom_db','domain_management_model'));
  if (empty($app_reference) == false) {
   $condition[]=array(
    'app_reference','=','"'.$app_reference.'"'
    );
   // debug($app_reference);die;
   $booking_details = $this->tours_model->booking($condition);
    
   if ($booking_details['status'] == SUCCESS_STATUS) {
    foreach ($booking_details['data'] as $key => $data) {
     $enquiry_reference_no=$key;
    }
    $voucher_data = $data;
    $attributes = json_decode($data['booking_details']['attributes'],true);
    $user_attributes = json_decode($data['booking_details']['user_attributes'],true);
    $voucher_data ['tours_itinerary_dw']   = $this->tours_model->tours_itinerary_dw($attributes['tour_id'],$attributes['departure_date']);

					//get agent address & logo for b2b voucher
				
					$domain_address = $this->custom_db->single_table_records ('domain_list','address,domain_logo,phone,domain_name,phone_code,email',array('origin'=>get_domain_auth_id()));

					$voucher_data['data']['address'] =$domain_address['data'][0]['address'];
					$voucher_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
					$voucher_data['data']['phone'] = $domain_address['data'][0]['phone'];
					$voucher_data['data']['domainname'] = $domain_address['data'][0]['domain_name'];
					$voucher_data['data']['email'] = $domain_address['data'][0]['email'];
// echo $booking_details['data'][$app_reference]['booking_details']['created_by_id'];exit;
					if($booking_details['data'][$app_reference]['booking_details']['created_by_id'] > 0){
						$get_agent_info = $this->user_model->get_agent_info($booking_details['data'][$app_reference]['booking_details']['created_by_id']);
						if(!empty($get_agent_info)){
							$voucher_data['data']['address'] = $get_agent_info[0]['address'];
							if($get_agent_info[0]['logo'] !=""){

								$voucher_data['data']['logo'] =$get_agent_info[0]['logo'];
							}
							else
							{
								$voucher_data['data']['logo'] =$domain_address['data'][0]['domain_logo'];
							}
							$voucher_data['data']['phone'] = $get_agent_info[0]['phone'];
							$voucher_data['data']['domainname'] = $get_agent_info[0]['agency_name'];

						}
					}
					
				
				
		$voucher_details = $this->custom_db->single_table_records(' voucher_details','note,phone,email', array('id'=>get_domain_auth_id()));

				$voucher_data['data']['note'] = $voucher_details['data'][0]['note'];
				$voucher_data['data']['voucher_phone'] = $voucher_details['data'][0]['phone'];
				$voucher_data['data']['voucher_email'] = $voucher_details['data'][0]['email'];			
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
   //debug($voucher_data);exit;
    switch ($operation) {
     case 'show_voucher' : 
     $voucher_data['menu'] = true;
     // debug($voucher_data); exit('1234');
     $this->template->view('voucher/holiday', $voucher_data);
     break;
     case 'show_pdf' :
     $this->load->library('provab_pdf');
     // debug($voucher_data);exit;
     // $get_view = $this->template->isolated_view ( 'voucher/b2c_holiday_pdf', $voucher_data );
     $get_view = $this->template->isolated_view ( 'voucher/holiday_pdf', $voucher_data );
     $this->provab_pdf->create_pdf ( $get_view, 'show');
     break;
	 case 'email_voucher' :
		//debug($page_data);exit;
		/*$this->load->library('provab_pdf');
		$create_pdf = new Provab_Pdf();
		$mail_template = $this->template->isolated_view('voucher/hotel_pdf', $page_data);
		$pdf = $create_pdf->create_pdf($mail_template,'');
		$this->provab_mailer->send_mail($email, domain_name().' - Hotel Ticket',$mail_template ,$pdf);*/
		// $email=$user_attributes['billing_email'];
		//$email=$user_attributes['billing_email'];
		//debug($email);exit();
		$this->load->library ( 'provab_mailer' );
		$this->load->library('provab_pdf');
		$mail_template = $this->template->isolated_view('voucher/holiday', $voucher_data);
		$mail_template_pdf=$this->template->isolated_view ( 'voucher/holiday_pdf', $voucher_data );
		// debug($mail_template_pdf);exit;
		$pdf= $this->provab_pdf->create_pdf( $mail_template_pdf,'F');
		$email_subject = "Tours Ticket";

		$mail_status = $this->provab_mailer->send_mail($email, $email_subject, $mail_template,$pdf);
		// debug($mail_status);exit();
	 break;
	 case 'download_pdf' :
     $this->load->library('provab_pdf');
     // debug($voucher_data);exit;
     // $get_view = $this->template->isolated_view ( 'voucher/b2c_holiday_pdf', $voucher_data );
     $get_view = $this->template->isolated_view ( 'voucher/holiday_pdf', $voucher_data );
     $this->provab_pdf->create_pdf ( $get_view, 'D');
     break;
    }	
   }
  }
 }
function b2b_holiday($app_reference , $booking_status='', $operation='show_voucher',$email = '') {
 	// error_reporting(E_ALL);
  $this->load->model(array('tours_model','custom_db','domain_management_model'));
  if (empty($app_reference) == false) {
   $condition[]=array(
    'app_reference','=','"'.$app_reference.'"'
    );
   // debug($app_reference);die;
   $booking_details = $this->tours_model->b2b_booking($condition);
   // debug($booking_details);die;
   if ($booking_details['status'] == SUCCESS_STATUS) {
    foreach ($booking_details['data'] as $key => $data) {
     $enquiry_reference_no=$key;
    }
    $voucher_data = $data;
    $attributes = json_decode($data['booking_details']['attributes'],true);
    $user_attributes = json_decode($data['booking_details']['user_attributes'],true);
    $voucher_data ['tours_itinerary_dw']   = $this->tours_model->tours_itinerary_dw($attributes['tour_id'],$attributes['departure_date']);
    $domain_details_package=$this->custom_db->single_table_records('domain_list','*');
    // debug($domain_details_package);exit;
    $voucher_data['package_domain']['domain_name'] = $domain_details_package['data'][0]['domain_name'];
    $voucher_data['package_domain']['email'] = $domain_details_package['data'][0]['email'];
    $voucher_data['package_domain']['phone'] = $domain_details_package['data'][0]['phone'];
    // debug($voucher_data); exit();
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
    $voucher_details = $this->custom_db->single_table_records(' voucher_details','note,phone,email', array('id'=>get_domain_auth_id()));

				$voucher_data['data']['note'] = $voucher_details['data'][0]['note'];
				$voucher_data['data']['voucher_phone'] = $voucher_details['data'][0]['phone'];
				$voucher_data['data']['voucher_email'] = $voucher_details['data'][0]['email'];
    switch ($operation) {
     case 'show_voucher' : 
     $voucher_data['menu'] = true;
     //debug($voucher_data); exit('');
     $this->template->view('voucher/holiday', $voucher_data);

     break;
     case 'show_pdf' :
     $this->load->library('provab_pdf');
     $get_view = $this->template->isolated_view ( 'voucher/holiday_pdf', $voucher_data );
     $this->provab_pdf->create_pdf ( $get_view, 'show', $app_reference);
     break;
     case 'download_pdf' :
     $this->load->library('provab_pdf');
     $get_view = $this->template->isolated_view ( 'voucher/holiday_pdf', $voucher_data );
     $this->provab_pdf->create_pdf ( $get_view, 'D', $app_reference);
     break;
	case 'email_voucher':
	// debug($booking_details['passenger_details'][0]['app_reference']);die;
   //debug($email) ;exit();

 //debug($voucher_data['b2b_logo']);exit();
		$this->load->library ( 'provab_mailer' );
		$this->load->library('provab_pdf');
		$mail_template = $this->template->isolated_view('voucher/holiday', $voucher_data);
		$mail_template_pdf=$this->template->isolated_view ( 'voucher/holiday_pdf', $voucher_data );
		// debug($mail_template_pdf);exit;
		$pdf= $this->provab_pdf->create_pdf ( $mail_template_pdf,'F');
		$email_subject = "Tours Ticket";

		$mail_status = $this->provab_mailer->send_mail($email, $email_subject, $mail_template,$pdf);
	break;
    }
   }
  }
 }
 //new function for invoice export
  function format_flight_boooking_data_invoice_export($flight_booking_data)
    {
        foreach ($flight_booking_data as $key => $value) {
            $app_reference = $value['app_reference'];
            $booking_source = $value['booking_source'];
            $booking_status = $value['status'];
            $this->load->model('flight_model');
            $this->load->model('user_model');
            if (empty($app_reference) == false) {
                $booking_details = $this->flight_model->get_booking_details($app_reference, $booking_source, $booking_status);
                if ($booking_details['status'] == SUCCESS_STATUS) {
                    load_flight_lib(PROVAB_FLIGHT_BOOKING_SOURCE);
                    //Assemble Booking Data
                    $assembled_booking_details = $this->booking_data_formatter->format_flight_booking_data($booking_details, $this->current_module);
                    $page_data['data'][$key] = $assembled_booking_details['data'];

                    if (isset($assembled_booking_details['data']['booking_details'][0])) {
                        //get agent address & logo for b2b voucher
                        $domain_address = $this->custom_db->single_table_records('domain_list', 'address,domain_logo,domain_name,phone', array('origin' => get_domain_auth_id()));

                        $page_data['admin_details'][$key]['address'] = $domain_address['data'][0]['address'];
                        $page_data['admin_details'][$key]['logo'] = $domain_address['data'][0]['domain_logo'];
                        $page_data['admin_details'][$key]['phone'] = $domain_address['data'][0]['phone'];
                        $page_data['admin_details'][$key]['domainname'] = $domain_address['data'][0]['domain_name'];

                        if ($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0) {
                            $get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);

                            if (!empty($get_agent_info)) {
                                $page_data['data'][$key]['address'] = $get_agent_info[0]['address'];
                                if ($get_agent_info[0]['logo'] != "") {

                                    $page_data['data']['logo'] = $get_agent_info[0]['logo'];
                                } else {
                                    $page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
                                }
                                $page_data['data'][$key]['phone'] = $get_agent_info[0]['phone'];
                                $page_data['data'][$key]['domainname'] = $get_agent_info[0]['agency_name'];
                            }
                        } else {
                            $page_data['data'][$key]['address'] = $assembled_booking_details['data']['booking_details'][0]['cutomer_address'];
                            $page_data['data'][$key]['phone'] = $assembled_booking_details['data']['booking_details'][0]['lead_pax_phone_number'];
                            $page_data['data'][$key]['domainname'] = $assembled_booking_details['data']['booking_details'][0]['lead_pax_name'];
                            $page_data['data'][$key]['domaincountry'] = $assembled_booking_details['data']['booking_details'][0]['cutomer_country'];
                        }
                    }
                    // $page_data['module'] = $module;
                }
            }
        }
        return $page_data;
    }

    //new function for invoice export
    function flight_report_invoice_export_pdf_zip($data_list, $module, $title)
    {
        $this->load->library('provab_pdf');
        // Split the array into chunks of EXPORT_CHUNK_COUNT
        $chunks = array_chunk($data_list, EXPORT_CHUNK_COUNT_INVOICE_FLIGHT);

        // Get the number of chunks
        $numChunks = count($chunks);

        // Handle the remaining elements (less than EXPORT_CHUNK_COUNT)
        $remaining = count($data_list) % EXPORT_CHUNK_COUNT_INVOICE_FLIGHT;
        if ($remaining > 0) {
            // Add the remaining elements to the last chunk
            $chunks[$numChunks - 1] = array_slice($data_list, - ($remaining));
        }

        // Initialize array to store file paths of generated PDFs
        $pdfFiles = [];
        $filenameArr = [];

        foreach ($chunks as $index => $flight_booking_data) {
            // Generate a unique filename for the PDF
            $filename = $title . '_' . uniqid() . date("Y-m-d") . '.pdf';
            $filenameArr[] = $filename;

            // Set the output name for the PDF
            $outputName = $filename;
            $page_data = $this->format_flight_boooking_data_invoice_export($flight_booking_data);
            $mail_template = $this->template->isolated_view('voucher/flight_invoice_new_all_pdf', $page_data);
            
            // Generate PDF with the dynamically generated output path
            $pdf = $this->provab_pdf->create_pdf($mail_template, "F", $outputName, "P");
            
            // Store the file path in the array
            $pdfFiles[] = $pdf;
        }

        // Set appropriate headers for multiple file download
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename=' . $title . date("Y-m-d") . '.zip');

        // Define the directory where you want to save the zip file
        $storageDirectory = DOMAIN_ZIP_DIR;

        // Specify the full path for the zip file
        $zipFilePath = $storageDirectory . $title . date("Y-m-d") . '.zip';

        // Create a zip archive
        $zip = new ZipArchive();
        if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
            // Add each PDF to the zip file with a unique filename
            foreach ($pdfFiles as $index => $pdf) {
                $i = $index + 1;
                $zip->addFile($pdf, "$i" . "_" . "$filenameArr[$index]");
            }
            // Close the zip file
            $zip->close();

            // Read the zip file and output its contents
            readfile($zipFilePath);

            // delete the temporary zip file
            unlink($zipFilePath);
        } else {
            die("Failed to create zip file");
        }

        // Delete the temporary PDF files
        foreach ($pdfFiles as $pdf) {
            // unlink($pdf);
        }
        exit;
    }

    //new function for invoice export
    function flight_report_invoice_export_pdf($data_list, $module, $title)
    {
        $this->load->library('provab_pdf');
        $page_data = $this->format_flight_boooking_data_invoice_export($data_list);
        $mail_template = $this->template->isolated_view('voucher/flight_invoice_new_all_pdf', $page_data);
        $pdf = $this->provab_pdf->create_pdf($mail_template, "F", "", "P");
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename=' . $title . date("Y-m-d") . '.pdf');
        readfile($pdf);
        unlink($pdf);
        exit;
    }

    //new function for invoice download with date filter
    public function all_flight_invoice_GST($user_type = '')
    {
        $this->load->model('flight_model');
        $post_data = $this->input->post();
        if(valid_array($post_data) == true){
            $filter_condition = array();
            if($post_data['filter_type'] == 'datewise') {
                $from_date = trim(@$post_data['from_date']);
                $to_date = trim(@$post_data['to_date']);
                if (empty($from_date) == false && empty($to_date) == false) {
                    $valid_dates = auto_swipe_dates($from_date, $to_date);
                    $from_date = $valid_dates['from_date'];
                    $to_date = $valid_dates['to_date'];
                } else {
                    $this->session->set_flashdata(array('message' => 'Something went wrong at server side.', 'type' => SUCCESS_MESSAGE));
                    redirect($_SERVER['HTTP_REFERER']);
                }
                if (empty($from_date) == false) {
                    $filter_condition[] = array('BD.created_datetime', '>=', $this->db->escape(db_current_datetime($from_date)));
                }
                if (empty($to_date) == false) {
                    $filter_condition[] = array('BD.created_datetime', '<=', $this->db->escape(db_current_datetime($to_date)));
                }
                $limit = 100000000000;
                // return array('filter_condition' => $filter_condition, 'from_date' => $from_date, 'to_date' => $to_date);
            } elseif($post_data['filter_type'] == 'lengthwise') {
                $limit = $post_data['inv_number'];
            } else {
                $this->session->set_flashdata(array('message' => 'Please input correct data', 'type' => SUCCESS_MESSAGE));
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
        $condition = array();
        $condition = $filter_condition;
        $flight_booking_data = $this->flight_model->b2c_flight_report_invoice($user_type, $condition, false, 0, $limit);
        foreach ($flight_booking_data as $key => $value) {
            $app_reference = $value['app_reference'];
            $booking_source = $value['booking_source'];
            $booking_status = $value['status'];
            $this->load->model('flight_model');
            if (empty($app_reference) == false) {
                $booking_details = $this->flight_model->get_booking_details($app_reference, $booking_source, $booking_status);
                if ($booking_details['status'] == SUCCESS_STATUS) {
                    load_flight_lib(PROVAB_FLIGHT_BOOKING_SOURCE);
                    //Assemble Booking Data
                    $assembled_booking_details = $this->booking_data_formatter->format_flight_booking_data($booking_details, $this->current_module);
                    $page_data['data'][$key] = $assembled_booking_details['data'];

                    if (isset($assembled_booking_details['data']['booking_details'][0])) {
                        //get agent address & logo for b2b voucher
                        $domain_address = $this->custom_db->single_table_records('domain_list', 'address,domain_logo,domain_name,phone', array('origin' => get_domain_auth_id()));

                        $page_data['admin_details'][$key]['address'] = $domain_address['data'][0]['address'];
                        $page_data['admin_details'][$key]['logo'] = $domain_address['data'][0]['domain_logo'];
                        $page_data['admin_details'][$key]['phone'] = $domain_address['data'][0]['phone'];
                        $page_data['admin_details'][$key]['domainname'] = $domain_address['data'][0]['domain_name'];

                        if ($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0) {
                            $get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);

                            if (!empty($get_agent_info)) {
                                $page_data['data'][$key]['address'] = $get_agent_info[0]['address'];
                                if ($get_agent_info[0]['logo'] != "") {

                                    $page_data['data']['logo'] = $get_agent_info[0]['logo'];
                                } else {
                                    $page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
                                }
                                $page_data['data'][$key]['phone'] = $get_agent_info[0]['phone'];
                                $page_data['data'][$key]['domainname'] = $get_agent_info[0]['agency_name'];
                            }
                        } else {
                            $page_data['data'][$key]['address'] = $assembled_booking_details['data']['booking_details'][0]['cutomer_address'];
                            $page_data['data'][$key]['phone'] = $assembled_booking_details['data']['booking_details'][0]['lead_pax_phone_number'];
                            $page_data['data'][$key]['domainname'] = $assembled_booking_details['data']['booking_details'][0]['lead_pax_name'];
                            $page_data['data'][$key]['domaincountry'] = $assembled_booking_details['data']['booking_details'][0]['cutomer_country'];
                        }
                    }
                    // $page_data['module'] = $module;
                }
            }
        }
        $mail_template = $this->template->isolated_view('voucher/flight_invoice_new_all_pdf', $page_data);
        $this->load->library('provab_pdf');
        $this->load->library('provab_mailer');
        $create_pdf = new Provab_Pdf();
        $this->provab_pdf->create_pdf($mail_template);
    }

}