<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 *
 * @package Provab - Provab Application
 * @subpackage Travel Portal
 * @author Balu A<balu.provab@gmail.com> on 01-06-2015
 * @version V2
 */
class Management extends CI_Controller {
	private $current_module;
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'domain_management_model' );
		$this->load->model ( 'bus_model' );
		$this->load->model ( 'hotel_model' );
		$this->load->model ( 'flight_model' );
		$this->load->model('sightseeing_model');

		$this->load->library('booking_data_formatter');
		$this->load->helper('custom/transaction_log');
		$this->current_module = $this->config->item('current_module');
	}
	public function index()
	{
		redirect(base_url());
	}
	/**
	 * Balu A
	 * Manage domain markup for provab - Domain wise and module wise
	 */
	function b2b_airline_markup() {
		$markup_module_type = 'b2b_flight';
		$page_data ['form_data'] = $this->input->post ();
		//debug($page_data ['form_data']);exit;
		if (valid_array ( $page_data ['form_data'] ) == true) {

			switch ($page_data ['form_data'] ['form_values_origin']) {
				case 'generic' :
					$this->domain_management_model->save_markup_data ( $page_data ['form_data'] ['markup_origin'], $page_data ['form_data'] ['form_values_origin'], $markup_module_type, 0, $page_data ['form_data'] ['generic_value'], $page_data ['form_data'] ['value_type'], get_domain_auth_id () );
					break;
				case 'specific' :

					if (valid_array ( $page_data ['form_data'] ['airline_origin'] )) {
						foreach ( $page_data ['form_data'] ['airline_origin'] as $__k => $__domain_origin ) {
							if ($page_data ['form_data'] ['specific_value'] [$__k] != '' && intval ( $page_data ['form_data'] ['specific_value'] [$__k] ) > - 1 && empty ( $page_data ['form_data'] ['value_type_' . $__domain_origin] ) == false) {

								$this->domain_management_model->save_markup_data ( $page_data ['form_data'] ['markup_origin'] [$__k], $page_data ['form_data'] ['form_values_origin'], $markup_module_type, $page_data ['form_data'] ['airline_origin'] [$__k], $page_data ['form_data'] ['specific_value'] [$__k], $page_data ['form_data'] ['value_type_' . $__domain_origin], get_domain_auth_id () );
							}
						}
					}
					break;
				case 'add_airline';//Balu A
					if(isset($page_data['form_data']['airline_code']) == true && empty($page_data['form_data']['airline_code']) == false) {
						$airline_code = trim($page_data['form_data']['airline_code'] = $page_data['form_data']['airline_code']);
						$markup_details = $this->domain_management_model->individual_airline_markup_details($markup_module_type, $airline_code);
						$airline_list_origin= intval($markup_details['airline_list_origin']);
						if(intval($markup_details['markup_list_origin']) > 0) {
							$markup_list_origin = intval($markup_details['markup_list_origin']);
						} else {
							$markup_list_origin = 0;
						}
						$this->domain_management_model->save_markup_data(
								$markup_list_origin, 'specific', $markup_module_type, $airline_list_origin,
								$page_data['form_data']['specific_value'], $page_data['form_data']['value_type'], get_domain_auth_id()
								);
						
					}
			}
			redirect ( base_url () . 'index.php/management/' . __FUNCTION__ );
		}
		// Airline would have All - general and domain wise markup
		$data_list = $this->domain_management_model->get_agent_airline_markup_details ();
		$airline_list = $this->db_cache_api->get_airline_list();
		$data_list['airline_list'] = $airline_list;
		$this->template->view ( 'management/b2b_airline_markup', $data_list );
	}
	
	/**
	 * Balu A
	 * Manage domain markup for provab - Domain wise and module wise
	 */
	function b2b_hotel_markup() {
		// Hotel would have All - general and domain wise markup
		$markup_module_type = 'b2b_hotel';
		$page_data ['form_data'] = $this->input->post ();
		if (valid_array ( $page_data ['form_data'] ) == true) {
			switch ($page_data ['form_data'] ['form_values_origin']) {
				case 'generic' :
					$this->domain_management_model->save_markup_data ( $page_data ['form_data'] ['markup_origin'], $page_data ['form_data'] ['form_values_origin'], $markup_module_type, 0, $page_data ['form_data'] ['generic_value'], $page_data ['form_data'] ['value_type'], get_domain_auth_id () );
					break;
			}
			redirect ( base_url () . 'index.php/management/' . __FUNCTION__ );
		}
		// Hotel would have All - general and domain wise markup
		$data_list = $this->domain_management_model->hotel_markup ();
		$this->template->view ( 'management/b2b_hotel_markup', $data_list );
	}
	
	/**
	 * Elavarasi
	 * Manage domain markup for provab - Domain wise and module wise
	 */
	function b2b_sightseeing_markup() {
		// Sightseeing would have All - general and domain wise markup
		$markup_module_type = 'b2b_sightseeing';
		$page_data ['form_data'] = $this->input->post ();
		if (valid_array ( $page_data ['form_data'] ) == true) {
			switch ($page_data ['form_data'] ['form_values_origin']) {
				case 'generic' :
					$this->domain_management_model->save_markup_data ( $page_data ['form_data'] ['markup_origin'], $page_data ['form_data'] ['form_values_origin'], $markup_module_type, 0, $page_data ['form_data'] ['generic_value'], $page_data ['form_data'] ['value_type'], get_domain_auth_id () );
					break;
			}
			redirect ( base_url () . 'index.php/management/' . __FUNCTION__ );
		}
		// Sightseeing would have All - general and domain wise markup
		$data_list = $this->domain_management_model->sightseeing_markup ();
		$this->template->view ( 'management/b2b_sightseeing_markup', $data_list );
	}

	/**
	 * Elavarasi
	 * Manage domain markup for provab - Domain wise and module wise
	 */
	function b2b_transfer_markup() {
		// Transfers would have All - general and domain wise markup
		$markup_module_type = 'b2b_transferv1';
		$page_data ['form_data'] = $this->input->post ();
		if (valid_array ( $page_data ['form_data'] ) == true) {
			switch ($page_data ['form_data'] ['form_values_origin']) {
				case 'generic' :
					$this->domain_management_model->save_markup_data ( $page_data ['form_data'] ['markup_origin'], $page_data ['form_data'] ['form_values_origin'], $markup_module_type, 0, $page_data ['form_data'] ['generic_value'], $page_data ['form_data'] ['value_type'], get_domain_auth_id () );
					break;
			}
			redirect ( base_url () . 'index.php/management/' . __FUNCTION__ );
		}
		// Transfers would have All - general and domain wise markup
		$data_list = $this->domain_management_model->transfer_markup ();
		$this->template->view ( 'management/b2b_transfer_markup', $data_list );
	}

	/**
	 * Anitha G
	 * Manage domain markup for provab - Domain wise and module wise
	 */
	function b2b_car_markup() {
		// Car would have All - general and domain wise markup
		$markup_module_type = 'b2b_car';
		$page_data ['form_data'] = $this->input->post ();
		if (valid_array ( $page_data ['form_data'] ) == true) {
			switch ($page_data ['form_data'] ['form_values_origin']) {
				case 'generic' :
					$this->domain_management_model->save_markup_data ( $page_data ['form_data'] ['markup_origin'], $page_data ['form_data'] ['form_values_origin'], $markup_module_type, 0, $page_data ['form_data'] ['generic_value'], $page_data ['form_data'] ['value_type'], get_domain_auth_id () );
					break;
			}
			redirect ( base_url () . 'index.php/management/' . __FUNCTION__ );
		}
		// Hotel would have All - general and domain wise markup
		$data_list = $this->domain_management_model->car_markup ();
		$this->template->view ( 'management/b2b_car_markup', $data_list );
	}

	/**
	 * Balu A
	 * Manage domain markup for provab - Domain wise and module wise
	 */
	function b2b_bus_markup() {
		// Bus would have All - general and domain wise markup
		$page_data ['form_data'] = $this->input->post ();
		$markup_module_type = 'b2b_bus';
		if (valid_array ( $page_data ['form_data'] ) == true) {
			switch ($page_data ['form_data'] ['form_values_origin']) {
				case 'generic' :
					$this->domain_management_model->save_markup_data ( $page_data ['form_data'] ['markup_origin'], $page_data ['form_data'] ['form_values_origin'], $markup_module_type, 0, $page_data ['form_data'] ['generic_value'], $page_data ['form_data'] ['value_type'], get_domain_auth_id () );
					break;
			}
			redirect ( base_url () . 'index.php/management/' . __FUNCTION__ );
		}
		// Airline would have All - general and domain wise markup
		$data_list = $this->domain_management_model->bus_markup ();
		$this->template->view ( 'management/b2b_bus_markup', $data_list );
	}
	
	/**
	 * Balu A
	 * Manage Balance history and other details of domain with provab
	 */
	function b2b_balance_manager($balance_request_type = "Cash")
	{	
		$params = $this->input->get();
		$page_data ['form_data'] = $this->input->post ();
		switch (strtoupper ( $balance_request_type )) {
			case 'CHECK___DD' :
				$page_data ['balance_page_obj'] = new Provab_Page_Loader ( 'balance_request_check' );
				break;
			case 'ETRANSFER' :
				$page_data ['balance_page_obj'] = new Provab_Page_Loader ( 'balance_request_e_transfer' );
				break;
			case 'CASH' :
				$page_data ['balance_page_obj'] = new Provab_Page_Loader ( 'balance_request_cash' );
				break;
			default :
				redirect ( base_url () );
		}
		if (valid_array ( $page_data ['form_data'] ) == true) {
			$page_data ['balance_page_obj']->set_auto_validator ();
			if ($this->form_validation->run ()) {
				$page_data ['form_data'] ['transaction_type'] = ($page_data ['form_data'] ['transaction_type']);
				if ($page_data ['form_data'] ['origin'] == 0) {
						
					//get the conversion rate with respect to admin currency
						
					$agent_deposit_currency_details = $this->convert_agent_deposit_currency($page_data ['form_data']['amount']);
					$page_data ['form_data']['currency'] = $agent_deposit_currency_details['currency'];
					$page_data ['form_data']['currency_conversion_rate'] = $agent_deposit_currency_details['currency_conversion_rate'];
					$page_data ['form_data']['amount'] =$agent_deposit_currency_details['amount'];
					//echo debug($page_data);exit;
					// Insert
					$insert_id = $this->domain_management_model->save_master_transaction_details ( $page_data ['form_data'] );
				} elseif (intval ( $page_data ['form_data'] ['origin'] ) > 0) {
					// FIXME :: Update Not Needed As Of Now
				}
				// Slip Upload
				$this->deposit_slip_upload($insert_id);
				redirect ( base_url () . 'index.php/management/' . __FUNCTION__ . '/' . $balance_request_type );
			}
		}
	
		$params = $this->input->get();
		if (isset($params['status']) == false) {
			//$params['status'] = 'PENDING';
		}
		$data_list_filt = array();
		if (isset($params['system_transaction_id']) == true and empty($params['system_transaction_id']) == false) {
			$data_list_filt[] = array('MTD.system_transaction_id', 'like', $this->db->escape('%'.$params['system_transaction_id'].'%'));
		}
		if (isset($params['status']) == true and empty($params['status']) == false && strtolower($params['status']) != 'all') {
			$data_list_filt[] = array('MTD.status', '=', $this->db->escape($params['status']));
		}
		if (isset($params['created_datetime_from']) == true and empty($params['created_datetime_from']) == false) {
			$data_list_filt[] = array('MTD.created_datetime', '>=', $this->db->escape(db_current_datetime($params['created_datetime_from'])));
		}
		if (isset($params['created_datetime_to']) == true and empty($params['created_datetime_to']) == false) {
			$data_list_filt[] = array('MTD.created_datetime', '<=', $this->db->escape(db_current_datetime($params['created_datetime_to'])));
		}
	
		// debug($data_list_filt);exit;
		$page_data ['table_data'] = $this->domain_management_model->master_transaction_request_list($data_list_filt);
		//formated table data
		$page_data ['table_data'] = $this->booking_data_formatter->format_master_transaction_balance($page_data ['table_data'],$this->current_module);
		$page_data ['balance_request_type'] = strtoupper ( $balance_request_type );
		$page_data ['provab_balance_requests'] = get_enum_list ( 'provab_balance_requests' );
		if (empty ( $page_data ['form_data'] ['currency_converter_origin'] ) == true) {
			$page_data ['form_data'] ['currency_converter_origin'] = COURSE_LIST_DEFAULT_CURRENCY;
			$page_data ['form_data'] ['conversion_value'] = 1;
		}
		$page_data['status_options'] = get_enum_list('provab_balance_status');
		$page_data['search_params'] = $params;
		$page_data ['form_data'] ['transaction_type'] = ($balance_request_type);
		//debug($page_data); exit;
		$this->template->view ( 'management/master_balance_manager', $page_data );
	}
	/**Sagar Wakchaure
	 * get conversion rate w.r.t admin
	 * @return string[]|unknown[]
	 */
	function convert_agent_deposit_currency($deposit_amount)
	{
	
	    $response = array();		
		$currency_obj = new Currency ();
		$currency_conversion_rate = $currency_obj->transaction_currency_conversion_rate();
		$response['currency_conversion_rate'] = $currency_conversion_rate;
	    $response['currency'] = agent_base_currency();	
		$response['amount']  = $deposit_amount*$currency_obj->currency_conversion_value(false, agent_base_currency(), admin_base_currency());		
		return $response;
		
	}
	
	function deposit_slip_upload($origin)
	{
		//FILE UPLOAD
		if (valid_array($_FILES) == true and $_FILES['image']['error'] == 0 and $_FILES['image']['size'] > 0) {
			if( function_exists( "check_mime_image_type" ) ) {
			    if ( !check_mime_image_type( $_FILES['image']['tmp_name'] ) ) {
			    	echo "Please select the image files only (gif|jpg|png|jpeg)"; exit;
			    }
			}
			$config['upload_path'] = $this->template->domain_image_upload_path ().'deposit_slips/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['file_name'] = time();
			$config['max_size'] = '1000000';
			$config['max_width']  = '';
			$config['max_height']  = '';
			$config['remove_spaces']  = false;
			//UPDATE
			$temp_record = $this->custom_db->single_table_records('master_transaction_details', 'image', array('origin' => $origin));
			$icon = $temp_record['data'][0]['image'];
			//DELETE OLD FILES
			if (empty($icon) == false) {
				$temp_profile_image = $this->template->domain_image_full_path($icon);//GETTING FILE PATH
				if (file_exists($temp_profile_image)) {
					unlink($temp_profile_image);
				}
			}
			//UPLOAD IMAGE
			$this->load->library('upload', $config);
			if ( ! $this->upload->do_upload('image')) {
				echo $this->upload->display_errors();
			} else {
				$image_data =  $this->upload->data();
			}
			$this->custom_db->update_record('master_transaction_details', array('image' => $image_data['file_name']), array('origin' => $origin));
		}
	}
	/*
	 * Balu A
	 */
	function set_balance_alert() {
		$post_data = $this->input->post ();
		$page_data = array ();
		$page_data ['balance_alert_page_obj'] = new Provab_Page_Loader ( 'set_balance_alert' );
		if (valid_array ( $post_data ) == true) { // UPDATE OR ADD
			$page_data ['balance_alert_page_obj']->set_auto_validator ();
			if ($this->form_validation->run ()) {
				$origin = intval ( $post_data ['origin'] );
				$agent_balance_alert_details = array ();
				$agent_balance_alert_details ['threshold_amount'] = trim ( $post_data ['threshold_amount'] );
				$agent_balance_alert_details ['mobile_number'] = trim ( $post_data ['mobile_number'] );
				$agent_balance_alert_details ['email_id'] = trim ( $post_data ['email_id'] );
				$agent_balance_alert_details ['enable_sms_notification'] = trim ( @$post_data ['enable_sms_notification'] [0] );
				$agent_balance_alert_details ['enable_email_notification'] = trim ( @$post_data ['enable_email_notification'] [0] );
				$agent_balance_alert_details ['created_by_id'] = $this->entity_user_id;
				$agent_balance_alert_details ['created_datetime'] = date ( 'Y-m-d H:i:s' );
				if ($origin > 0) {
					// UPDATE
					$this->custom_db->update_record ( 'agent_balance_alert_details', $agent_balance_alert_details, array (
							'agent_fk' => $this->entity_user_id 
					) );
				} else {
					// ADD
					$agent_balance_alert_details ['agent_fk'] = $this->entity_user_id;
					$this->custom_db->insert_record ( 'agent_balance_alert_details', $agent_balance_alert_details );
				}
				redirect ( 'management/set_balance_alert' );
			}
		}
		$temp_alert_details = $this->custom_db->single_table_records ( 'agent_balance_alert_details', '*', array (
				'agent_fk' => $this->entity_user_id 
		) );
		if ($temp_alert_details ['status'] == true) {
			$page_data ['balance_alert_details'] = $temp_alert_details ['data'] [0];
			$form_data = $temp_alert_details ['data'] [0];
		} else {
			$page_data ['balance_alert_details'] = '';
			$form_data ['origin'] = 0;
		}
		$page_data ['form_data'] = $form_data;
		$this->template->view ( 'management/set_balance_alert', $page_data );
	}
	/**
	 * Sachin
	 * Account Ledger (transactions) search by date
	 */
	function account_ledger($offset=0)
	{
		$get_data = $this->input->get();
		$condition = array();
		$page_data = array();

		$user_id = $this->entity_user_id;

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
			$ymd_from_date = date('Y-m-d', strtotime($from_date));
			$condition[] = array('date(TL.created_datetime)', '>=', $this->db->escape($ymd_from_date));
		}
		if(empty($to_date) == false) {
			$ymd_to_date = date('Y-m-d', strtotime($to_date));
			$condition[] = array('date(TL.created_datetime)', '<=', $this->db->escape($ymd_to_date));
		}
		if (empty($get_data['app_reference']) == false) {
			$condition[] = array('TL.app_reference', ' like ', $this->db->escape('%'.$get_data['app_reference'].'%'));
		}
		if (empty($get_data['transaction_type']) == false) {
			$condition[] = array('TL.transaction_type', ' like ', $this->db->escape('%'.$get_data['transaction_type'].'%'));
		}
		//Transaction Data
		$total_records = $this->domain_management_model->agent_account_ledger($condition, true);
		$total_records = $total_records['total_records'];
		$transaction_logs = $this->domain_management_model->agent_account_ledger($condition, false, $offset, RECORDS_RANGE_3);
		$transaction_logs = format_account_ledger($transaction_logs['data']);
		$page_data['table_data'] = $transaction_logs['data'];
		/** TABLE PAGINATION */
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'management/account_ledger/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_records'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['search_params'] = $get_data;

		$this->template->view ( 'management/account_ledger', $page_data );
	}

	/*Sachin
	*Export Account Ledger details to Excel Format
	*/
	
	//test
	public function export_account_ledger($op=''){
		
		$get_data = $this->input->GET();
		$condition = array();

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
			$ymd_from_date = date('Y-m-d', strtotime($from_date));
			$condition[] = array('date(TL.created_datetime)', '>=', $this->db->escape($ymd_from_date));
		}
		if(empty($to_date) == false) {
			$ymd_to_date = date('Y-m-d', strtotime($to_date));
			$condition[] = array('date(TL.created_datetime)', '<=', $this->db->escape($ymd_to_date));
		}
		if (empty($get_data['app_reference']) == false) {
			$condition[] = array('TL.app_reference', ' like ', $this->db->escape('%'.$get_data['app_reference'].'%'));
		}
		if (empty($get_data['transaction_type']) == false) {
			$condition[] = array('TL.transaction_type', ' like ', $this->db->escape('%'.$get_data['transaction_type'].'%'));
		}

		//Transaction Data
		$transaction_logs = $this->domain_management_model->agent_account_ledger($condition, false);
		$transaction_logs = format_account_ledger($transaction_logs['data']);
		$export_data = $transaction_logs['data'];
//debug($export_data);die;

		if($op == 'excel'){ // excel export

			$headings = array( 'a1' => 'Sl. No.', 
        				   'b1' => 'Date', 
        				   'c1' => 'Reference Number',
        				   'd1' => 'Description',
        				   'e1' => 'Debit', 
        				   'f1' => 'Credit', 
        				   'g1' => 'Opening Balance', 
        				   'h1' => 'Closing Balance'
        				  );
	        // field names in data set 
	        $fields = array( 'a' => '', // empty for sl. no.
	        				 'b' => 'transaction_date', 
	        				 'c' => 'reference_number', 
	        				 'd' => 'full_description',
	        				 'e' => 'debit_amount',
	        				 'f' => 'credit_amount',
	        				 'g' => 'opening_balance',
	        				 'h' => 'closing_balance');    

	        $excel_sheet_properties = array(
	        				'title' => 'Account_Ledger_'.date('d-M-Y'), 
	        				'creator' => 'Provab', 
	        				'description' => 'Account Ledger', 
	        				'sheet_title' => 'Account Ledger'
	        	);   
	       		
	        $this->load->library ( 'provab_excel' ); // we need this provab_excel library to export excel.
	        $this->provab_excel->excel_export ( $headings, $fields, $export_data, $excel_sheet_properties);

		}else{ // pdf export 

			//debug($export_data); die();

			$col =array(
					'transaction_date'=>'Date',
					'reference_number'=>'Reference Number',
					'full_description'=>'Description',
					'debit_amount'=>'Debit',
					'credit_amount'=>'Credit',
					'opening_balance'=>'Opening Balance',
					'closing_balance'=>'Closing Balance'
			);

			$pdf_data = format_pdf_data($export_data, $col);
			$this->load->library ( 'provab_pdf' );			
			$get_view = $this->template->isolated_view ('report/table', $pdf_data);
			$this->provab_pdf->create_pdf ( $get_view, 'D' , 'Account_Ledger');			
			exit();
		}
       
	}

	/**
	 * Pravinkumar
	 * PNR/Transaction Search
	 */
	
	function pnr_search() 
	{
		$get_data = $this->input->get ();
		if ($get_data['filter_report_data'] != '' && $get_data['module'] !='') {
			$filter_report_data = $get_data ['filter_report_data'];
			$module = $get_data['module'];
			//Based on the Module data are loaded to page_data
			switch($module){
				case PROVAB_FLIGHT_BOOKING_SOURCE:
					redirect('report/flight?module='.$module.'&filter_report_data='.$filter_report_data);
					break;
				case PROVAB_HOTEL_BOOKING_SOURCE:
					redirect('report/hotel?module='.$module.'&filter_report_data='.$filter_report_data);
					break;
				case PROVAB_BUS_BOOKING_SOURCE:
					redirect('report/bus?module='.$module.'&filter_report_data='.$filter_report_data);
					break;
				case PROVAB_TRANSFERV1_BOOKING_SOURCE:
					redirect('report/transfers?module='.$module.'&filter_report_data='.$filter_report_data);
					break;
				case PROVAB_SIGHTSEEN_BOOKING_SOURCE:
					redirect('report/activities?module='.$module.'&filter_report_data='.$filter_report_data);
					break;

				default:
					refresh();
			}
			//$page_data depends on Module
			$this->template->view ( 'management/pnr_search', $page_data );
		}else{
		$this->template->view ( 'management/pnr_search' );
		}
	}
	/*
	 * Balu A
	 * Flight Commission for Agent
	 */
	function flight_commission() 
	{
		$flight_commission_details = $this->domain_management_model->flight_commission_details ();
		$page_data ['commission_details'] = $flight_commission_details ['data'];
		$this->template->view ( 'management/flight_commission', $page_data );
	}
	/*
	 * Balu A
	 * Bus Commission for Agent
	 */
	function bus_commission() 
	{
		$bus_commission_details = $this->domain_management_model->bus_commission_details ();
		$page_data ['commission_details'] = $bus_commission_details ['data'];
		$this->template->view ( 'management/bus_commission', $page_data );
	}
	/*
	 * Elavarasi
	 * Sightseeing Commission for Agent
	 */
	function sightseeing_commission() 
	{
		$sightseeing_commission_details = $this->domain_management_model->sightseeing_commission_details ();
		$page_data ['commission_details'] = $sightseeing_commission_details ['data'];
		$this->template->view ( 'management/sightseeing_commission', $page_data );
	}

	/*
	*Elavarasi
	*Transfers Commission for Agent
	*/
	function transfer_commission(){
		$transfer_commission_details = $this->domain_management_model->transfer_commission_details ();
		$page_data ['commission_details'] = $transfer_commission_details ['data'];
		$this->template->view ( 'management/transfer_commission', $page_data );
	}

	/**
	 * Balu A
	 * Bank Account Details
	 */
	function bank_account_details()
	{
		$temp_data=$this->domain_management_model->bank_account_details();
		if($temp_data['status']) {
			$page_data['table_data'] = $temp_data['data'];
		} else {
			$page_data['table_data'] = '';
		}
		$this->template->view('management/bank_account_details',$page_data);
	}
	/**
	 * Anitha G
	 * Credit Limit
	 */
	function b2b_credit_limit(){

		$page_data ['form_data'] = $this->input->post ();
		$page_data ['balance_page_obj'] = new Provab_Page_Loader ( 'credit_manager' );
		// debug($page_data ['balance_page_obj']);exit;
		if (valid_array ( $page_data ['form_data'] ) == true) {
			$page_data ['balance_page_obj']->set_auto_validator ();
			if ($this->form_validation->run ()) {
				$page_data ['form_data'] ['transaction_type'] = 'Credit';
				$page_data ['form_data'] ['bank'] = 'Credit';
				$page_data ['form_data'] ['branch'] = 'Credit';
				$page_data ['form_data'] ['date_of_transaction'] = date('Y-m-d');
				$page_data ['form_data'] ['deposited_branch'] = 'Credit';
				//get the conversion rate with respect to admin currency
				$agent_deposit_currency_details = $this->convert_agent_deposit_currency($page_data ['form_data']['amount']);
				$page_data ['form_data']['currency'] = $agent_deposit_currency_details['currency'];
				$page_data ['form_data']['currency_conversion_rate'] = $agent_deposit_currency_details['currency_conversion_rate'];
				$page_data ['form_data']['amount'] =$agent_deposit_currency_details['amount'];
				
				// Insert
				$insert_id = $this->domain_management_model->save_master_transaction_details ( $page_data ['form_data'],'Credit' );
				redirect ( base_url () . 'index.php/management/' . __FUNCTION__ . '/' . $balance_request_type );
			}
		}
		// debug($page_data);exit;
		$params = $this->input->get();
		// debug($params);exit;
		if (isset($params['status']) == false) {
			//$params['status'] = 'PENDING';
		}
		$data_list_filt = array();
		if (isset($params['system_transaction_id']) == true and empty($params['system_transaction_id']) == false) {
			$data_list_filt[] = array('MTD.system_transaction_id', 'like', $this->db->escape('%'.$params['system_transaction_id'].'%'));
		}
		if (isset($params['status']) == true and empty($params['status']) == false && strtolower($params['status']) != 'all') {
			$data_list_filt[] = array('MTD.status', '=', $this->db->escape($params['status']));
		}
		if (isset($params['created_datetime_from']) == true and empty($params['created_datetime_from']) == false) {
			$data_list_filt[] = array('MTD.created_datetime', '>=', $this->db->escape(db_current_datetime($params['created_datetime_from'])));
		}
		if (isset($params['created_datetime_to']) == true and empty($params['created_datetime_to']) == false) {
			$data_list_filt[] = array('MTD.created_datetime', '<=', $this->db->escape(db_current_datetime($params['created_datetime_to'])));
		}
		if (empty ( $page_data ['form_data'] ['currency_converter_origin'] ) == true) {
			$page_data ['form_data'] ['currency_converter_origin'] = COURSE_LIST_DEFAULT_CURRENCY;
			$page_data ['form_data'] ['conversion_value'] = 1;
		}
		$page_data ['table_data'] = $this->domain_management_model->master_transaction_request_list($data_list_filt,'Credit');
		//formated table data
		$page_data ['table_data'] = $this->booking_data_formatter->format_master_transaction_balance($page_data ['table_data'],$this->current_module);
		$page_data['search_params'] = $params;
		$page_data['status_options'] = get_enum_list('provab_balance_status');
	
		$this->template->view ( 'management/b2b_credit_limit', $page_data );
	}
}
