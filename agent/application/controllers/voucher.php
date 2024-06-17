<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @package    Provab
 * @subpackage Bus
 * @author     Balu A<balu.provab@gmail.com>
 * @version    V1
 */

class Voucher extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		//$this->load->library("provab_pdf");
		$this->load->library('provab_mailer');
		$this->load->library('booking_data_formatter');
		$this->load->model('insurance_model');
		//we need to activate bus api which are active for current domain and load those libraries
		//$this->output->enable_profiler(TRUE);
	}

	/**
	 *
	 */
	function bus($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$email='')
	{

		$this->load->model('bus_model');
		if (empty($app_reference) == false) {
			$booking_details = $this->bus_model->get_booking_details($app_reference, $booking_source, $booking_status);
			$terms_conditions = $this->custom_db->single_table_records('terms_conditions','description', array('module' =>'bus'));
			
			if ($booking_details['status'] == SUCCESS_STATUS) {
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_bus_booking_data($booking_details, 'b2b');
				
				$page_data['data'] = $assembled_booking_details['data'];
				// debug($page_data['data']);exit;
				if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher		if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
						$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);						
						// debug($get_agent_info);exit;
						if(!empty($get_agent_info)){
							// debug($get_agent_info[0]['image']);exit;
							$page_data['data']['address'] = $get_agent_info[0]['address'];
							if(empty($get_agent_info[0]['image']) == false){
								$page_data['data']['logo'] = $get_agent_info[0]['image'];
							}
							else{
								// debug($page_data['data']['booking_details'][0]);exit;
								$page_data['data']['logo'] = $page_data['data']['booking_details'][0]['domain_logo'];
							}
							$page_data['data']['phone'] = $get_agent_info[0]['phone'];
							$page_data['data']['domainname'] = $get_agent_info[0]['agency_name'];
						}
					
					}
					$page_data['data']['terms_conditions'] = '';
					if($terms_conditions['status'] == SUCCESS_STATUS){
						$page_data['data']['terms_conditions'] = $terms_conditions['data'][0]['description'];
					}
					// debug($page_data);exit;
					$page_data['email_send_check']=FALSE;
				switch ($operation) {
					case 'show_voucher' :
						$page_data['button'] = ACTIVE;
						$page_datap['image'] = ACTIVE;
						$this->template->view('voucher/bus_voucher', $page_data);
						$email=$booking_details['data']['booking_details'][0]['email'];
							if(empty($email)==false) {
								$page_data['email_send_check']=TRUE;
							     $mail_template = $this->template->isolated_view('voucher/bus_voucher', $page_data);
							     $this->provab_mailer->send_mail($email, domain_name().' - Bus Ticket',$mail_template);
				
							   }
					break;
					case 'show_pdf' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$get_view=$this->template->isolated_view('voucher/bus_pdf', $page_data);
						$create_pdf->create_pdf_investor($get_view,'show');
					case 'email_voucher' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$mail_template = $this->template->isolated_view('voucher/bus_pdf', $page_data);
						$pdf = $create_pdf->create_pdf($mail_template,'F');
						$this->provab_mailer->send_mail($email, domain_name().' - Bus Ticket',$mail_template ,$pdf);
						break;
					
				}
			}
		}
	}
	function transfer_crs($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$email=''){

	
	// echo phpinfo();exit();
     // error_reporting(E_ALL);
// debug($app_reference.'__'.$booking_source.'__'.$booking_status.'__'.$operation);exit;
	if($this->input->post('email')){
			$email=$this->input->post('email');	
		}
		$this->load->model('transfer_model');

		if (empty($app_reference) == false) {

			// debug($app_reference);
			// debug($booking_source);exit;
			// debug($booking_status);exit;
			$booking_source='PROVAB_TRANSFER_SOURCE_CRS';
			$booking_details = $this->transfer_model->get_booking_details_transfer($app_reference, $booking_source, $booking_status);
			
  
        // $package_details = $GLOBALS['CI']->transfer_model->transfersearch($safe_search_data,$weekday,$price_id);
        $package_details = $GLOBALS['CI']->transfer_model->get_transfer_details($app_reference);
        $transfer_id = $booking_details['data']['booking_details'][0]['tours_id'];
		$transfer_details = $GLOBALS['CI']->transfer_model->get_transfer_data($transfer_id);
			//debug($booking_details);exit;
		
			if ($booking_details['status'] == SUCCESS_STATUS) {
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_sightseeing_booking_data_hb($booking_details, 'b2b');
               // debug($assembled_booking_details);exit;
				$get_agent_info = $this->user_model->get_agent_info($this->entity_user_id)[0];
			  	$get_agent_image = $this->user_model->get_agent_image()[0];
    			$page_data ['agent_info'] = $get_agent_info;
    			$page_data ['agent_image'] = $get_agent_image;
				$page_data['transfer_details'] = $transfer_details;
				$page_data['package_details'] = $package_details;
				$page_data['data'] = $assembled_booking_details['data'];
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
					case 'show_invoice' : $this->template->view('voucher/transfer_invoice', $page_data);
					break;
					case 'show_vat_invoice' : $this->template->view('voucher/transfer_vat_invoice', $page_data);
					break;
					case 'show_view' : $this->template->view('voucher/transfer_view', $page_data);
					break;
					case 'show_pdf' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();

						$get_view=$this->template->isolated_view('voucher/activity_pdf', $page_data);
						$create_pdf->create_pdf($get_view,'show');
						break;
					case 'email_voucher' :
						$this->load->library('provab_pdf');
						$this->load->library ( 'provab_mailer' ); 
						$create_pdf = new Provab_Pdf();
						$mail_template = $this->template->isolated_view('voucher/activity_pdf', $page_data);
						$pdf = $create_pdf->create_pdf($mail_template,'F');
						$this->provab_mailer->send_mail($email, domain_name().' - Transfers Ticket',$mail_template ,$pdf);
						break;
				}
			}
		}
	}
	   function holiday($app_reference , $booking_status='', $operation='show_voucher',$mail = 'mail') {
  $this->load->model(array('tours_model','custom_db'));
 
  
  if (empty($app_reference) == false) {
   $condition[]=array(
    'app_reference','=','"'.$app_reference.'"'
    );
  
    $booking_details = $this->tours_model->booking($condition);
   // debug($booking_details);die;
    $tours_id = $booking_details['data'][$app_reference]['booking_details']['tours_id'];
    	
   if ($booking_details['status'] == SUCCESS_STATUS) {
    foreach ($booking_details['data'] as $key => $data) {
     $enquiry_reference_no=$key;
    }
    $voucher_data = $data;
    	$hc=json_decode($assembled_booking_details['data']['booking_details'][0]['attributes']);
			$voucher_data['supplier_details'] = $this->tours_model->get_supplier_details($tours_id);
//debug($voucher_data['supplier_details'][0]['user_id']);die;
			 $this->tours_model->update_touring_details($app_reference,$voucher_data['supplier_details'][0]['user_id']);
    $domain_address = $this->custom_db->single_table_records ('domain_list','address,domain_logo,phone,domain_name,email',array('origin'=>get_domain_auth_id()));
					$voucher_data['data']['address'] =$domain_address['data'][0]['address'];
					$voucher_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
					$voucher_data['data']['phone'] = $domain_address['data'][0]['phone'];
					$voucher_data['data']['email'] = $domain_address['data'][0]['email'];
					$voucher_data['data']['domainname'] = $domain_address['data'][0]['domain_name'];

    
    $enquiry_reference_no = $voucher_data['booking_details']['enquiry_reference_no'];
    $where = ['enquiry_reference_no'=>$enquiry_reference_no];
    $voucher_data['holiday_booking_user_details'] = $this->tours_model->holiday_en_user_name('tours_enquiry',$where);

    
    $attributes = json_decode($data['booking_details']['attributes'],true);
    
    $user_attributes = json_decode($data['booking_details']['user_attributes'],true);
   
    $voucher_data ['tours_itinerary_dw']   = $this->tours_model->tours_itinerary_dw($tours_id);

     $voucher_data['holiday_details'] = $this->tours_model->tours_details($tours_id);
     
    if(isset($mail) && $mail == 'mail') { 
     if ($this->input->post('email')) {
      $email = $this->input->post('email');
     }else{
      $email = $user_attributes['billing_email'];
     } 
   
     $voucher_data['menu'] = false;
    
     $voucher_data['email_status']=1;
     
     $mail_template_pdf =$this->template->isolated_view('voucher/holiday_pdf',$voucher_data);
     
    
    $mail_template= $this->template->isolated_view('voucher/holiday', $voucher_data);

     
     $this->load->library ( 'provab_pdf' );
      //$pdf = $this->provab_pdf->create_pdf($mail_template_pdf,'F', $app_reference);
     
    $mail_trigger = $this->session->userdata($app_reference);
	
    if($mail_trigger == '1'){
   $send= $this->provab_mailer->send_mail($email, domain_name().' - Holiday Voucher', $mail_template,$pdf);
   $this->session->unset_userdata($app_reference);
	}
	

    }

    $domain_address = $this->custom_db->single_table_records ('domain_list','address,domain_logo,phone,domain_name,email',array('origin'=>get_domain_auth_id()));
					$voucher_data['data']['address'] =$domain_address['data'][0]['address'];
					$voucher_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
					$voucher_data['data']['phone'] = $domain_address['data'][0]['phone'];
					$voucher_data['data']['email'] = $domain_address['data'][0]['email'];
					$voucher_data['data']['domainname'] = $domain_address['data'][0]['domain_name'];

				
 
     $voucher_data['email_send_check']=TRUE;
     $voucher_details = $this->custom_db->single_table_records(' voucher_details','note,phone,email', array('id'=>get_domain_auth_id()));

				$voucher_data['data']['note'] = $voucher_details['data'][0]['note'];
				$voucher_data['data']['voucher_phone'] = $voucher_details['data'][0]['phone'];
				$voucher_data['data']['voucher_email'] = $voucher_details['data'][0]['email'];
    switch ($operation) {
     case 'show_voucher' : 
     $voucher_data['menu'] = true;
     $this->template->view('voucher/holiday', $voucher_data);
     break;
     case 'show_pdf' :
     $this->load->library('provab_pdf');
     $get_view = $this->template->isolated_view ( 'voucher/holiday_pdf', $voucher_data );
    
     $this->provab_pdf->create_pdf ( $get_view, 'D', $app_reference); 
     break;
     case 'send_data' :
	    $voucher_data['menu'] = true;
	    debug($voucher_data);die;
    	 return  $voucher_data;
     break;
    }
   }
  }
 }
	function holidayoldsagent($app_reference , $booking_status='', $operation='show_voucher',$mail = 'mail') {
  
  
  $this->load->model(array('tours_model','custom_db'));
  if (empty($app_reference) == false) {
   $condition[]=array(
    'app_reference','=','"'.$app_reference.'"'
    );
   $booking_details = $this->tours_model->booking($condition);

  // debug($booking_details);exit();
   if ($booking_details['status'] == SUCCESS_STATUS) {
    foreach ($booking_details['data'] as $key => $data) {
     $enquiry_reference_no=$key;
    }
    $voucher_data = $data;
    
		
    $attributes = json_decode($data['booking_details']['attributes'],true);
    $user_attributes = json_decode($data['booking_details']['user_attributes'],true);
    //	$voucher_data['supplier_details'] = $this->tours_model->get_supplier_details($attributes['tour_id']);
	
			 $this->tours_model->update_touring_details($app_reference,$voucher_data['supplier_details'][0]['user_id']);
    $voucher_data ['tours_itinerary_dw']   = $this->tours_model->tours_itinerary_dw($attributes['tour_id'],$attributes['departure_date']);
    
$domain_address = $this->custom_db->single_table_records ( 'domain_list','address,domain_logo,phone,domain_name,email',array('origin'=>get_domain_auth_id()));
		 $voucher_data['address'] =$domain_address['data'][0]['address'];
          $voucher_data['logo'] = $domain_address['data'][0]['domain_logo'];
          $voucher_data['phone'] = $domain_address['data'][0]['phone'];
          $voucher_data['email'] = $domain_address['data'][0]['email'];
          $voucher_data['domainname'] = $domain_address['data'][0]['domain_name'];

     $email=$user_attributes['billing_email'];
    // debug($voucher_data);exit();
     $this->load->library ( 'provab_pdf' );
     // debug($voucher_data);exit();
     $mail_template =$this->template->isolated_view('voucher/holiday',$voucher_data);
     $mail_template_pdf =$this->template->isolated_view('voucher/holiday_pdf',$voucher_data);
   // debug($mail_template);exit();
     $this->load->library ( 'provab_pdf' );
     $pdf = $this->provab_pdf->create_pdf($mail_template_pdf,'F',$app_reference);
     //debug($pdf);exit();
  	$mail_trigger = $this->session->userdata($app_reference);   
    if($mail_trigger == '1'){
    $send= $this->provab_mailer->send_mail($email, 'Tour Voucher', $mail_template,$pdf);
	$this->session->unset_userdata($app_reference);
	}
   //debug($send);exit();
	$voucher_details = $this->custom_db->single_table_records(' voucher_details','note,phone,email', array('id'=>get_domain_auth_id()));

				$page_data['data']['note'] = $voucher_details['data'][0]['note'];
				$page_data['data']['voucher_phone'] = $voucher_details['data'][0]['phone'];
				$page_data['data']['voucher_email'] = $voucher_details['data'][0]['email'];
    switch ($operation) {
     case 'show_voucher' : 
     $voucher_data['menu'] = TRUE;
     $this->template->view('voucher/holiday', $voucher_data);
     break;
     case 'show_pdf' :
     $this->load->library('provab_pdf');
     $get_view = $this->template->isolated_view ( 'voucher/holiday_pdf', $voucher_data );
    // debug($get_view);exit();
     $this->provab_pdf->create_pdf_investor ( $get_view, 'D', $app_reference,'show');
     break;
     case 'email_voucher' :
     $voucher_data['menu'] = 0;
     $email = $this->input->post('email');
     // $email=$user_attributes['billing_email'];
    // debug($email);exit();
     $this->load->library ( 'provab_pdf' );
     // debug($voucher_data);exit();
     $mail_template =$this->template->isolated_view('voucher/holiday',$voucher_data);
     $mail_template_pdf =$this->template->isolated_view('voucher/holiday_pdf',$voucher_data);
    // debug($mail_template_pdf);exit();
     $this->load->library ( 'provab_pdf' );
     $pdf = $this->provab_pdf->create_pdf($mail_template_pdf,'F',$app_reference);
    $send= $this->provab_mailer->send_mail($email, 'Tour Voucher', $mail_template,$pdf);
   //debug($send);exit();
     set_update_message('UL00101');
     redirect ( 'voucher/holiday/'.$app_reference."/".$booking_status."/"."show_voucher");
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
        // debug($booking_details);exit;
			$pack_id=$booking_details['data']['booking_details'][0]['package_type'];
			//debug($pack_id);exit;

			$page_data['pack_details'] = $this->activity_model->getPackage($pack_id);
			// debug($page_data['pack_details']->image);exit;
			if ($booking_details['status'] == SUCCESS_STATUS) {
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_sightseeing_booking_data_hb_crs($booking_details, 'b2b');
               // debug($assembled_booking_details);exit;
				

				$page_data['data'] = $assembled_booking_details['data'];
				if(isset($this->entity_user_id))
				{
				 $get_agent_info = $this->user_model->get_agent_info($this->entity_user_id)[0];
				}
			 	 $get_agent_image = $this->user_model->get_agent_image()[0];
    			$page_data ['agent_info'] = $get_agent_info;
    			$page_data ['agent_image'] = $get_agent_image;
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
 	function activity($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$email_id='')
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
 	function activityold($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$email_id='')
	{
	    
		// echo "string";exit;
	
		$this->load->model('package_model');
		if (empty($app_reference) == false) {
			$booking_details = $this->package_model->get_activity_booking_details($app_reference, $booking_source, $booking_status);
	//	 debug($booking_details);die;
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
		/*For Sightseeing*/
	function activities($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$email=''){
		$this->load->model('sightseeing_model');

		if (empty($app_reference) == false) {
			$booking_details = $this->sightseeing_model->get_booking_details($app_reference, $booking_source, $booking_status);
			$terms_conditions = $this->custom_db->single_table_records('terms_conditions','description', array('module' =>'activity'));
			
			if ($booking_details['status'] == SUCCESS_STATUS) {
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_sightseeing_booking_data($booking_details, 'b2b');
				

				$page_data['data'] = $assembled_booking_details['data'];
                if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
						$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
						
						if(!empty($get_agent_info)){
							$page_data['data']['address'] = $get_agent_info[0]['address'];
							if(empty($get_agent_info[0]['image']) == false){
								$page_data['data']['logo'] = $get_agent_info[0]['image'];
							}
							else{
								$page_data['data']['logo'] = $page_data['data']['booking_details'][0]['domain_logo'];
							}
							$page_data['data']['phone'] = $get_agent_info[0]['phone'];
							$page_data['data']['email'] = provab_decrypt($get_agent_info[0]['email']);
							$page_data['data']['domainname'] = $get_agent_info[0]['agency_name'];
							

						}
				}
				$page_data['data']['terms_conditions'] = '';
					if($terms_conditions['status'] == SUCCESS_STATUS){
						$page_data['data']['terms_conditions'] = $terms_conditions['data'][0]['description'];
					}
					 $page_data['email_send_check']=FALSE;

					 $mail_trigger = $this->session->userdata($app_reference);
				switch ($operation) {
					case 'show_voucher' : $this->template->view('voucher/sightseeing_voucher', $page_data);

							$email=$booking_details['data']['booking_details'][0]['email'];
							if(empty($email)==false && ($mail_trigger =='1')) {
								$page_data['email_send_check']=TRUE;
							     $mail_template = $this->template->isolated_view('voucher/sightseeing_voucher', $page_data);
							     $this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$get_view=$this->template->isolated_view('voucher/sightseeing_pdf', $page_data);
						$pdf = $create_pdf->create_pdf_investor($get_view,'F');
							     $this->provab_mailer->send_mail($email, domain_name().' - Activity Voucher',$mail_template,$pdf);
							     $this->session->unset_userdata($app_reference);
							   }

					break;
					case 'show_pdf' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$get_view=$this->template->isolated_view('voucher/sightseeing_pdf', $page_data);
						$create_pdf->create_pdf_investor($get_view,'show');
						break;
					case 'email_voucher' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$mail_template = $this->template->isolated_view('voucher/sightseeing_pdf', $page_data);
						$pdf = $create_pdf->create_pdf($mail_template,'F');
						$this->provab_mailer->send_mail($email, domain_name().' - Acitivity Ticket',$mail_template ,$pdf);
						break;
				}
			}
		}
	}
		/*For Transfers*/
	function transfers($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$email=''){
		$this->load->model('transferv1_model');

		if (empty($app_reference) == false) {
			$booking_details = $this->transferv1_model->get_booking_details($app_reference, $booking_source, $booking_status);
			$terms_conditions = $this->custom_db->single_table_records('terms_conditions','description', array('module' =>'transfer'));
			
			if ($booking_details['status'] == SUCCESS_STATUS) {
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_transferv1_booking_data($booking_details, 'b2b');
				

				$page_data['data'] = $assembled_booking_details['data'];
				// debug($page_data['data']);exit;
                if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
						$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
						
						if(!empty($get_agent_info)){
							$page_data['data']['address'] = $get_agent_info[0]['address'];
							if(empty($get_agent_info[0]['image']) == false){
								$page_data['data']['logo'] = $get_agent_info[0]['image'];
							}
							else{
								$page_data['data']['logo'] = $page_data['data']['booking_details'][0]['domain_logo'];
							}
							$page_data['data']['phone'] = $get_agent_info[0]['phone'];
							$page_data['data']['email'] = provab_decrypt($get_agent_info[0]['email']);
							$page_data['data']['domainname'] = $get_agent_info[0]['agency_name'];
							

						}
				}
				$page_data['data']['terms_conditions'] = '';
					if($terms_conditions['status'] == SUCCESS_STATUS){
						$page_data['data']['terms_conditions'] = $terms_conditions['data'][0]['description'];
					}
					
						$page_data['data']['country_code'] = $get_agent_info[0]['country_code'];
							$res=$this->custom_db->single_table_records('api_country_list','*', array('origin' =>$get_agent_info[0]['country_code']));
                            //debug($res['data'][0]['country_code']);die;
                            $page_data['data']['country_code']=$res['data'][0]['country_code'];

				$mail_trigger = $this->session->userdata($app_reference);	
				$voucher_details = $this->custom_db->single_table_records(' voucher_details','note,phone,email', array('id'=>get_domain_auth_id()));

				$page_data['data']['note'] = $voucher_details['data'][0]['note'];
				$page_data['data']['voucher_phone'] = $voucher_details['data'][0]['phone'];
				$page_data['data']['voucher_email'] = $voucher_details['data'][0]['email'];
				switch ($operation) {
					case 'show_voucher' : $this->template->view('voucher/transferv1_voucher', $page_data);
						$email=$booking_details['data']['booking_details'][0]['email'];
							if(empty($email)==false && ($mail_trigger =='1')) {
								$page_data['email_send_check']=TRUE;
							     $mail_template = $this->template->isolated_view('voucher/transferv1_voucher', $page_data);
							     $this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$get_view=$this->template->isolated_view('voucher/transferv1_pdf', $page_data);
						$pdf = $create_pdf->create_pdf_investor($get_view,'F');
							     $this->provab_mailer->send_mail($email, domain_name().' - Transfer Voucher',$mail_template,$pdf);
							     $this->session->unset_userdata($app_reference);
							   }
					break;
					case 'show_pdf' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$get_view=$this->template->isolated_view('voucher/transferv1_pdf', $page_data);
						$create_pdf->create_pdf_investor($get_view,'show');
						break;
					case 'email_voucher' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$mail_template = $this->template->isolated_view('voucher/transferv1_pdf', $page_data);
						$pdf = $create_pdf->create_pdf($mail_template,'F');
						$this->provab_mailer->send_mail($email, domain_name().' - Transfers Ticket',$mail_template ,$pdf);
						break;
				}
			}
		}
	}

	function hotel($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$email='')
	{
		$this->load->model('hotel_model');
		if (empty($app_reference) == false) {
			$booking_details = $this->hotel_model->get_booking_details($app_reference, $booking_source, $booking_status);
			$terms_conditions = $this->custom_db->single_table_records('terms_conditions','description', array('module' =>'hotel'));
			if ($booking_details['status'] == SUCCESS_STATUS) {
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_hotel_booking_data($booking_details, 'b2b');
			
					$hc=json_decode($assembled_booking_details['data']['booking_details'][0]['attributes']);
			$page_data['supplier_details'] = $this->hotel_model->get_supplier_details($hc->HotelCode);
	
	
	
		 $this->hotel_model->update_hot_details($assembled_booking_details['data']['booking_details'][0]['app_reference'],$page_data['supplier_details'][0]['user_id']);
				$page_data['data'] = $assembled_booking_details['data'];
				if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
					if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
						$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);

						// debug($page_data['data']);exit;
						if(!empty($get_agent_info)){
							$page_data['data']['address'] = $get_agent_info[0]['address'];
							if(empty($get_agent_info[0]['image']) == false){
								$page_data['data']['logo'] = $get_agent_info[0]['image'];
							}
							else{
								$page_data['data']['logo'] = $page_data['data']['booking_details'][0]['domain_logo'];
							}
							
							$page_data['data']['country_code'] = $get_agent_info[0]['country_code'];
							$res=$this->custom_db->single_table_records('api_country_list','*', array('origin' =>$get_agent_info[0]['country_code']));
                            //debug($res['data'][0]['country_code']);die;
                            $page_data['data']['country_code']=$res['data'][0]['country_code'];
							$page_data['data']['phone'] = $get_agent_info[0]['phone'];
							$page_data['data']['email'] = provab_decrypt($get_agent_info[0]['email']);
							$page_data['data']['domainname'] = $get_agent_info[0]['agency_name'];
						}
					}
					$page_data['data']['terms_conditions'] = '';
					if($terms_conditions['status'] == SUCCESS_STATUS){
						$page_data['data']['terms_conditions'] = $terms_conditions['data'][0]['description'];
					}
				
				}
				$page_data['email_send_check']=FALSE;
				$mail_trigger = $this->session->userdata($app_reference);
				$voucher_details = $this->custom_db->single_table_records(' voucher_details','note,phone,email', array('id'=>get_domain_auth_id()));

				$page_data['data']['note'] = $voucher_details['data'][0]['note'];
				$page_data['data']['voucher_phone'] = $voucher_details['data'][0]['phone'];
				$page_data['data']['voucher_email'] = $voucher_details['data'][0]['email'];
				switch ($operation) {
					case 'show_voucher' : 
					    
					    
					    
					   
					    
					    
					    	$email=$booking_details['data']['booking_details'][0]['email'];
					    $semail=$page_data['supplier_details'][0]['suppemail'];
					         $mail_template = $this->template->isolated_view('voucher/hotel_voucher', $page_data);
							     $this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$get_view=$this->template->isolated_view('voucher/hotel_pdf', $page_data);
						$pdf = $create_pdf->create_pdf_investor($get_view,'F');
					$this->provab_mailer->send_mail($semail, domain_name().' - Hotel Voucher',$mail_template,$pdf);
					$this->provab_mailer->send_mail($email, domain_name().' - Hotel Voucher',$mail_template,$pdf);
					    $this->template->view('voucher/hotel_voucher', $page_data);
					    
					    
				
							if(empty($email)==false && ($mail_trigger =='1')) {
								$page_data['email_send_check']=TRUE;
							     $mail_template = $this->template->isolated_view('voucher/hotel_voucher', $page_data);
							     $this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$get_view=$this->template->isolated_view('voucher/hotel_pdf', $page_data);
						$pdf = $create_pdf->create_pdf_investor($get_view,'F');
						$this->provab_mailer->send_mail($email, domain_name().' - Hotel Voucher',$mail_template,$pdf);
						
						
					//	debug($semail);die;
						
						
						
                         if($semail!="")
                         {
                             $this->provab_mailer->send_mail($semail, domain_name().' - Hotel Voucher',$mail_template,$pdf);
                         }
						$this->session->unset_userdata($app_reference);
							   }
					break;
					case 'show_pdf' :
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$get_view=$this->template->isolated_view('voucher/hotel_pdf', $page_data);
						$create_pdf->create_pdf_investor($get_view,'show');
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

	/**
	 *
	 */
	function flight($app_reference, $booking_source='', $booking_status='', $operation='show_voucher',$email='', $insuranceStatus = '')
	{
		if($email == 'insurance'){
			$email = '';
		}


		error_reporting(0);
		$this->load->model('flight_model');
		if (empty($app_reference) == false) {
			$booking_details = $this->flight_model->get_booking_details($app_reference, $booking_source, $booking_status);
			
			$terms_conditions = $this->custom_db->single_table_records('terms_conditions','description', array('module' =>'flight'));
			if ($booking_details['status'] == SUCCESS_STATUS) {
				load_flight_lib(PROVAB_FLIGHT_BOOKING_SOURCE);
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_flight_booking_data($booking_details, 'b2b');
				
				$page_data['data'] = $assembled_booking_details['data'];
				if(isset($assembled_booking_details['data']['booking_details'][0])){
					//get agent address & logo for b2b voucher
					if($assembled_booking_details['data']['booking_details'][0]['created_by_id'] > 0){
						$get_agent_info = $this->user_model->get_agent_info($assembled_booking_details['data']['booking_details'][0]['created_by_id']);
						
						if(!empty($get_agent_info)){
							$page_data['data']['address'] = $get_agent_info[0]['address'];
							if(empty($get_agent_info[0]['image']) == false){
								$page_data['data']['logo'] = $get_agent_info[0]['image'];
							}
							else{
								$page_data['data']['logo'] = $page_data['data']['booking_details_app'][$app_reference]['domain_logo'];
							}
							$page_data['data']['phone'] = $get_agent_info[0]['phone'];
							$page_data['data']['email'] = provab_decrypt($get_agent_info[0]['email']);
							$page_data['data']['country_code'] = $get_agent_info[0]['country_code'];
							$page_data['data']['domainname'] = $get_agent_info[0]['agency_name'];
						}
					}
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
				// debug($page_data);exit;
				//get the address
				if(isset($assembled_booking_details['data']['booking_details'][0]['created_by_id'])){
					 $get_address= $this->custom_db->single_table_records ( 'user','address',array('user_id'=>$assembled_booking_details['data']['booking_details'][0]['created_by_id']));
					 //debug($get_address);exit;
					 $page_data['data']['address'] = $get_address['data'][0]['address'];
					 
				}
               $email=$booking_details['data']['booking_details'][0]['email'];
               $page_data['email_send_check']=FALSE;

               $mail_trigger = $this->session->userdata($app_reference);
               $voucher_details = $this->custom_db->single_table_records(' voucher_details','note,phone,email', array('id'=>get_domain_auth_id()));

				$page_data['data']['note'] = $voucher_details['data'][0]['note'];
				$page_data['data']['voucher_phone'] = $voucher_details['data'][0]['phone'];
				$page_data['data']['voucher_email'] = $voucher_details['data'][0]['email'];
				//debug($page_data['data']['phone_code']);exit;
				if($insuranceStatus != ''){
					$insuranceDetails = $this->insurance_model->getInsuranceDetails(array('app_reference'=>$app_reference));
					if($insuranceDetails['status'] == 1){
						$page_data['isInsured'] = 1;
					$page_data['insuranceStatus'] = $insuranceStatus;
					$page_data['policyUrl'] = $insuranceDetails['data'][0]['bankname'];
			
					}else{
						$page_data['isInsured'] = 0;
						$page_data['insuranceStatus'] = 'none';
					}
					}
				switch ($operation) {
					case 'show_voucher' : 
				//	debug($page_data);die;
					$this->template->view('voucher/flight_voucher', $page_data);
					if(empty($email)==false && ($mail_trigger=='1')) {
												$page_data['email_send_check']=TRUE;
                                                 $mail_template = $this->template->isolated_view('voucher/flight_voucher', $page_data);
                                                 $this->load->library('provab_pdf');
                                                 $create_pdf = new Provab_Pdf();
						$mail_template_pdf = $this->template->isolated_view('voucher/flight_pdf', $page_data);
                         
						$pdf = $create_pdf->create_pdf_investor($mail_template_pdf,'F');
                                                 $this->provab_mailer->send_mail($email, domain_name().' - Flight Ticket',$mail_template,$pdf);
												 if($insuranceStatus == 'CONFIRMED' && $page_data['isInsured']== 1){
													$pdfurl = $page_data['policyUrl'];
													$contents = file_get_contents($pdfurl);
													$pdf_path = BASEPATH . 'policy.pdf';
													file_put_contents($pdf_path, $contents);
													$mail_template = 'Please find your insurance certificate attached with this email';
													$ss = $this->provab_mailer->send_mail($email, domain_name() . ' - Insurance Certificate', $mail_template, $pdf_path);
													unlink($pdf_path);
												 }
                                                 $this->session->unset_userdata($app_reference);
                                                 
                                               }
                                        break;
					case 'show_pdf' :
					
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$get_view=$this->template->isolated_view('voucher/flight_pdf', $page_data);
                                                
						$create_pdf->create_pdf_investor($get_view,'show');
						 break;
					case 'email_voucher':
					
						$this->load->library('provab_pdf');
						$create_pdf = new Provab_Pdf();
						$mail_template = $this->template->isolated_view('voucher/flight_pdf', $page_data);
						$pdf = $create_pdf->create_pdf($mail_template,'F');
						$this->provab_mailer->send_mail($email, domain_name().' - Flight Ticket',$mail_template ,$pdf);
						if($insuranceStatus == 'CONFIRMED' && $page_data['isInsured']== 1){
							$pdfurl = $page_data['policyUrl'];
							$contents = file_get_contents($pdfurl);
							$pdf_path = BASEPATH . 'policy.pdf';
							file_put_contents($pdf_path, $contents);
							$mail_template = 'Please find your insurance certificate attached with this email';
							$ss = $this->provab_mailer->send_mail($email, domain_name() . ' - Insurance Certificate', $mail_template, $pdf_path);
							unlink($pdf_path);
						 }
						break;
				}
			}
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
                $assembled_booking_details = $this->booking_data_formatter->format_car_booking_datas($booking_details, 'b2b');
                // debug($assembled_booking_details);exit;
                $page_data['data'] = $assembled_booking_details['data'];
                if (isset($assembled_booking_details['data']['booking_details'][0])) {
                    //get agent address & logo for b2b voucher

                    $domain_address = $this->custom_db->single_table_records('domain_list', 'address,domain_logo,phone,email', array('origin' => get_domain_auth_id()));
                    $page_data['data']['address'] = $domain_address['data'][0]['address'];
                    $page_data['data']['phone'] = $get_agent_info[0]['phone'];
					$page_data['data']['email'] = provab_decrypt($get_agent_info[0]['email']);
                    $page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
                   
                }
                // debug($page_data);exit;
                $page_data['email_send_check']=FALSE;
               $mail_trigger = $this->session->userdata($app_reference);

                switch ($operation) {
                    case 'show_voucher' : $this->template->view('voucher/car_voucher', $page_data);
                    		$email=$booking_details['data']['booking_details'][0]['email'];
							if(empty($email)==false && $mail_trigger='1') {
								$page_data['email_send_check']=TRUE;
							     $mail_template = $this->template->isolated_view('voucher/car_voucher', $page_data);
							     $this->load->library('provab_pdf');
                        $create_pdf = new Provab_Pdf();
                        $get_view = $this->template->isolated_view('voucher/car_pdf', $page_data);
                        $pdf = $create_pdf->create_pdf($get_view, 'F');
							     $this->provab_mailer->send_mail($email, domain_name().' - Car Voucher',$mail_template,$pdf);
                                $this->session->unset_userdata($app_reference);

							   }
                        break;
                    case 'show_pdf' :
                        $this->load->library('provab_pdf');
                        $create_pdf = new Provab_Pdf();
                        $get_view = $this->template->isolated_view('voucher/car_pdf', $page_data);
                        $create_pdf->create_pdf($get_view, 'show');

                        break;
                    case 'email_voucher' :
                        $email = $this->load->library('provab_pdf');
                        $email = @$booking_details['data']['booking_details'][0]['email'];
                        $create_pdf = new Provab_Pdf();
                        $mail_template = $this->template->isolated_view('voucher/car_pdf', $page_data);
                        $pdf = $create_pdf->create_pdf_investor($mail_template, 'F');
                        $this->provab_mailer->send_mail($email, domain_name() . ' - Car Ticket', $mail_template, $pdf);
                        break;
                }
            }
        }
    }
}
