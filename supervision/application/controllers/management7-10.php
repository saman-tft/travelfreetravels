<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @package    Provab - Provab Application
 * @subpackage Travel Portal
 * @author     Balu A<balu.provab@gmail.com> on 01-06-2015
 * @version    V2
 */
error_reporting(0);
class Management extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('domain_management_model');
		$this->load->helper('custom/transaction_log');
		$this->load->helper('url');
		//$this->load->helper('download');
		//$this->load->library('excel');
		//$this->output->enable_profiler(TRUE);

	}

	/**
	 * Balu A
	 * Manage domain markup for provab - Domain wise and module wise
	 */
	function b2c_airline_markup()
	{
		$markup_module_type = 'b2c_flight';
		$page_data['form_data'] = $this->input->post();
		if (valid_array($page_data['form_data']) == true) {
			switch($page_data['form_data']['form_values_origin']) {
				case 'generic':
					$this->domain_management_model->save_markup_data(
					$page_data['form_data']['markup_origin'], $page_data['form_data']['form_values_origin'], $markup_module_type, 0,
					$page_data['form_data']['generic_value'], $page_data['form_data']['value_type'], get_domain_auth_id()
					);
					break;
				case 'specific':
					if (valid_array($page_data['form_data']['airline_origin'])) {
						foreach($page_data['form_data']['airline_origin'] as $__k => $__domain_origin) {
							if ($page_data['form_data']['specific_value'][$__k] != '' && intval($page_data['form_data']['specific_value'][$__k]) > -1
							&& empty($page_data['form_data']['value_type_'.$__domain_origin]) == false
							) {
								$this->domain_management_model->save_markup_data(
								$page_data['form_data']['markup_origin'][$__k], $page_data['form_data']['form_values_origin'], $markup_module_type, $page_data['form_data']['airline_origin'][$__k],
								$page_data['form_data']['specific_value'][$__k], $page_data['form_data']['value_type_'.$__domain_origin], get_domain_auth_id()
								);
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
						//debug($markup_details);exit;
						$this->domain_management_model->save_markup_data(
								$markup_list_origin, 'specific', $markup_module_type, $airline_list_origin,
								$page_data['form_data']['specific_value'], $page_data['form_data']['value_type'], get_domain_auth_id()
								);
						
					}
					break;
			}
			set_update_message();
			redirect(base_url().'index.php/management/'.__FUNCTION__);
		}
		$view_data = array();
		//Airline would have All - general and domain wise markup
		$data_list = $this->domain_management_model->b2c_airline_markup();
		$airline_list = $this->db_cache_api->get_airline_list();
		$data_list['data']['airline_list'] = $airline_list;
		// debug($data_list['data']);exit;
		$this->template->view('management/b2c_airline_markup', $data_list['data']);
	}

	/**
	 * Balu A
	 * Manage domain markup for provab - Domain wise and module wise
	 */
	function b2c_hotel_markup()
	{
		
		//Hotel would have All - general and domain wise markup
		$markup_module_type = 'b2c_hotel';
		$page_data['form_data'] = $this->input->post();
		if (valid_array($page_data['form_data']) == true) {
			switch($page_data['form_data']['form_values_origin']) {
				case 'generic':
					$this->domain_management_model->save_markup_data(
					$page_data['form_data']['markup_origin'], $page_data['form_data']['form_values_origin'], $markup_module_type, 0,
					$page_data['form_data']['generic_value'], $page_data['form_data']['value_type'], get_domain_auth_id()
					);
					break;
			}
			set_update_message();
			redirect(base_url().'index.php/management/'.__FUNCTION__);
		}
		//Airline would have All - general and domain wise markup
		$data_list = $this->domain_management_model->b2c_hotel_markup();
		$this->template->view('management/b2c_hotel_markup', $data_list['data']);
	}
	/**
	 * Anitha G
	 * Manage domain markup for provab - Domain wise and module wise
	 */
	function b2c_car_markup()
	{
		
		//Car would have All - general and domain wise markup
		$markup_module_type = 'b2c_car';
		$page_data['form_data'] = $this->input->post();
		if (valid_array($page_data['form_data']) == true) {
			// debug($page_data);exit;
			switch($page_data['form_data']['form_values_origin']) {
				case 'generic':
					$this->domain_management_model->save_markup_data(
					$page_data['form_data']['markup_origin'], $page_data['form_data']['form_values_origin'], $markup_module_type, 0,
					$page_data['form_data']['generic_value'], $page_data['form_data']['value_type'], get_domain_auth_id()
					);
					break;
			}
			set_update_message();
			redirect(base_url().'index.php/management/'.__FUNCTION__);
		}
		//Airline would have All - general and domain wise markup
		$data_list = $this->domain_management_model->b2c_car_markup();
		$this->template->view('management/b2c_car_markup', $data_list['data']);
	}
	public function testdatain()
	{
		$dd=array('privilege_category'=>'all_users','privilege_key'=>'p130','parent_key'=>'p36','description'=>'B2B Tour Markup','p_no'=>'139','url'=>'management/b2bpackage_domain_markup');
		$this->db->insert('privilege_list_new',$dd);
	}
	/**
	 * Elavarasi
	 * Manage domain markup for provab - Domain wise and module wise
	 */
	function b2c_sightseeing_markup()
	{
		
		//Hotel would have All - general and domain wise markup
		$markup_module_type = 'b2c_sightseeing';
		$page_data['form_data'] = $this->input->post();
		if (valid_array($page_data['form_data']) == true) {
			switch($page_data['form_data']['form_values_origin']) {
				case 'generic':
					$this->domain_management_model->save_markup_data(
					$page_data['form_data']['markup_origin'], $page_data['form_data']['form_values_origin'], $markup_module_type, 0,
					$page_data['form_data']['generic_value'], $page_data['form_data']['value_type'], get_domain_auth_id()
					);
					break;
			}
			set_update_message();
			redirect(base_url().'index.php/management/'.__FUNCTION__);
		}
		//Airline would have All - general and domain wise markup
		$data_list = $this->domain_management_model->b2c_sightseeing_markup();
		$this->template->view('management/b2c_sightseeing_markup', $data_list['data']);
	}
		/**
	 * Elavarasi
	 * Manage domain markup for provab - Domain wise and module wise
	 */
	function b2c_transfer_markup()
	{
		
		//Hotel would have All - general and domain wise markup
		$markup_module_type = 'b2c_transferv1';
		$page_data['form_data'] = $this->input->post();
		if (valid_array($page_data['form_data']) == true) {
			switch($page_data['form_data']['form_values_origin']) {
				case 'generic':
					$this->domain_management_model->save_markup_data(
					$page_data['form_data']['markup_origin'], $page_data['form_data']['form_values_origin'], $markup_module_type, 0,
					$page_data['form_data']['generic_value'], $page_data['form_data']['value_type'], get_domain_auth_id()
					);
					break;
			}
			set_update_message();
			redirect(base_url().'index.php/management/'.__FUNCTION__);
		}
		//Airline would have All - general and domain wise markup
		$data_list = $this->domain_management_model->b2c_transferv1_markup();
		$this->template->view('management/b2c_transferv1_markup', $data_list['data']);
	}
	/**
	 * Balu A
	 * Manage domain markup for provab - Domain wise and module wise
	 */
	function b2c_bus_markup()
	{
		//Bus would have All - general and domain wise markup
		$page_data['form_data'] = $this->input->post();
		$markup_module_type = 'b2c_bus';
		if (valid_array($page_data['form_data']) == true) {
			switch($page_data['form_data']['form_values_origin']) {
				case 'generic':
					$this->domain_management_model->save_markup_data(
					$page_data['form_data']['markup_origin'], $page_data['form_data']['form_values_origin'], $markup_module_type, 0,
					$page_data['form_data']['generic_value'], $page_data['form_data']['value_type'], get_domain_auth_id()
					);
					break;
			}
			set_update_message();
			redirect(base_url().'index.php/management/'.__FUNCTION__);
		}
		//Airline would have All - general and domain wise markup
		$data_list = $this->domain_management_model->b2c_bus_markup();
		$this->template->view('management/b2c_bus_markup', $data_list['data']);
	}

	/**
	 * Balu A
	 * Manage domain markup for B2B - Domain wise and module wise
	 */
	function b2b_airline_markup()
	{
		$user_oid = 0;//defining general only as of now
		$this->domain_management_model->markup_level = 'level_3';
		//FIXME : Airline Markup - agent wise and general markup
		$markup_module_type = 'b2b_flight';
		$page_data['form_data'] = $this->input->post();
		if (valid_array($page_data['form_data']) == true) {
			switch($page_data['form_data']['form_values_origin']) {
				case 'generic':
					$this->domain_management_model->save_markup_data(
					$page_data['form_data']['markup_origin'], $page_data['form_data']['form_values_origin'], $markup_module_type, 0,
					$page_data['form_data']['generic_value'], $page_data['form_data']['value_type'], get_domain_auth_id(), $user_oid
					);
					break;
				case 'specific':
					if (valid_array($page_data['form_data']['airline_origin'])) {
						foreach($page_data['form_data']['airline_origin'] as $__k => $__domain_origin) {
							if ($page_data['form_data']['specific_value'][$__k] != '' && intval($page_data['form_data']['specific_value'][$__k]) > -1
							&& empty($page_data['form_data']['value_type_'.$__domain_origin]) == false
							) {
								$this->domain_management_model->save_markup_data(
								$page_data['form_data']['markup_origin'][$__k], $page_data['form_data']['form_values_origin'], $markup_module_type, $page_data['form_data']['airline_origin'][$__k],
								$page_data['form_data']['specific_value'][$__k], $page_data['form_data']['value_type_'.$__domain_origin], get_domain_auth_id(), $user_oid
								);
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
					break;
			}
			set_update_message();
			redirect(base_url().'index.php/management/'.__FUNCTION__);
		}
		//Airline would have All - general and Agent wise markup
		$data_list = $this->domain_management_model->b2b_airline_markup();
		$airline_list = $this->db_cache_api->get_airline_list();
		$data_list['airline_list'] = $airline_list;
		$this->template->view('management/b2b_airline_markup', $data_list);
	}

	/**
	 * Balu A
	 * Manage domain markup for B2B - Domain wise and module wise
	 */
	function b2b_hotel_markup()
	{
		$user_oid = 0;//defining general only as of now
		$this->domain_management_model->markup_level = 'level_3';
		//Hotel would have All - general and domain wise markup
		$markup_module_type = 'b2b_hotel';
		$page_data['form_data'] = $this->input->post();
		if (valid_array($page_data['form_data']) == true) {
			switch($page_data['form_data']['form_values_origin']) {
				case 'generic':
					$this->domain_management_model->save_markup_data(
					$page_data['form_data']['markup_origin'], $page_data['form_data']['form_values_origin'], $markup_module_type, 0,
					$page_data['form_data']['generic_value'], $page_data['form_data']['value_type'], get_domain_auth_id(), $user_oid
					);
					break;
			}
			set_update_message();
			redirect(base_url().'index.php/management/'.__FUNCTION__);
		}
		//Hotel would have All - general and domain wise markup
		$data_list = $this->domain_management_model->b2b_hotel_markup();
		$this->template->view('management/b2b_hotel_markup', $data_list);
	}
	/**
	 * Elavarasi
	 * Manage domain markup for B2B - Domain wise and module wise
	 */
	function b2b_sightseeing_markup(){
		$user_oid = 0;//defining general only as of now
		$this->domain_management_model->markup_level = 'level_3';
		//Sightseeing would have All - general and domain wise markup
		$markup_module_type = 'b2b_sightseeing';
		$page_data['form_data'] = $this->input->post();
		if (valid_array($page_data['form_data']) == true) {
			switch($page_data['form_data']['form_values_origin']) {
				case 'generic':
					$this->domain_management_model->save_markup_data(
					$page_data['form_data']['markup_origin'], $page_data['form_data']['form_values_origin'], $markup_module_type, 0,
					$page_data['form_data']['generic_value'], $page_data['form_data']['value_type'], get_domain_auth_id(), $user_oid
					);
					break;
			}
			set_update_message();
			redirect(base_url().'index.php/management/'.__FUNCTION__);
		}
		//Sightseeing would have All - general and domain wise markup
		$data_list = $this->domain_management_model->b2b_sightseeing_markup();
		$this->template->view('management/b2b_sightseeing_markup', $data_list);
	}
	/**
	 * Elavarasi
	 * Manage domain markup for B2B - Domain wise and module wise
	 */
	function b2b_transfer_markup(){
		$user_oid = 0;//defining general only as of now
		$this->domain_management_model->markup_level = 'level_3';
		//Sightseeing would have All - general and domain wise markup
		$markup_module_type = 'b2b_transferv1';
		$page_data['form_data'] = $this->input->post();
		if (valid_array($page_data['form_data']) == true) {
			switch($page_data['form_data']['form_values_origin']) {
				case 'generic':
					$this->domain_management_model->save_markup_data(
					$page_data['form_data']['markup_origin'], $page_data['form_data']['form_values_origin'], $markup_module_type, 0,
					$page_data['form_data']['generic_value'], $page_data['form_data']['value_type'], get_domain_auth_id(), $user_oid
					);
					break;
			}
			set_update_message();
			redirect(base_url().'index.php/management/'.__FUNCTION__);
		}
		//Sightseeing would have All - general and domain wise markup
		$data_list = $this->domain_management_model->b2b_transferv1_markup();
		$this->template->view('management/b2b_transfers_markup', $data_list);
	}

	/**
	 * Anitha G
	 * Manage domain markup for B2B - Domain wise and module wise
	 */
	function b2b_car_markup(){
		$user_oid = 0;//defining general only as of now
		$this->domain_management_model->markup_level = 'level_3';
		//Sightseeing would have All - general and domain wise markup
		$markup_module_type = 'b2b_car';
		$page_data['form_data'] = $this->input->post();
		if (valid_array($page_data['form_data']) == true) {
			// debug($page_data);exit;
			switch($page_data['form_data']['form_values_origin']) {
				case 'generic':
					$this->domain_management_model->save_markup_data(
					$page_data['form_data']['markup_origin'], $page_data['form_data']['form_values_origin'], $markup_module_type, 0,
					$page_data['form_data']['generic_value'], $page_data['form_data']['value_type'], get_domain_auth_id(), $user_oid
					);
					break;
			}
			set_update_message();
			redirect(base_url().'index.php/management/'.__FUNCTION__);
		}
		//Sightseeing would have All - general and domain wise markup
		$data_list = $this->domain_management_model->b2b_car_markup();
		$this->template->view('management/b2b_car_markup', $data_list);
	}
	
	/**
	 * Balu A
	 * Manage domain markup for B2B - Domain wise and module wise
	 */
	function b2b_bus_markup()
	{
		$user_oid = 0;//defining general only as of now
		$this->domain_management_model->markup_level = 'level_3';
		//Bus would have All - general and domain wise markup
		$page_data['form_data'] = $this->input->post();
		$markup_module_type = 'b2b_bus';
		if (valid_array($page_data['form_data']) == true) {
			switch($page_data['form_data']['form_values_origin']) {
				case 'generic':
					$this->domain_management_model->save_markup_data(
					$page_data['form_data']['markup_origin'], $page_data['form_data']['form_values_origin'], $markup_module_type, 0,
					$page_data['form_data']['generic_value'], $page_data['form_data']['value_type'], get_domain_auth_id(), $user_oid
					);
					break;
			}
			set_update_message();
			redirect(base_url().'index.php/management/'.__FUNCTION__);
		}
		//Airline would have All - general and domain wise markup
		$data_list = $this->domain_management_model->b2b_bus_markup();
		$this->template->view('management/b2b_bus_markup', $data_list);
	}

	/**
	 * Balu A
	 * Manage Balance history and other details of domain with provab
	 */
	public function export_all_balance_manager($op='')
	{
		$table_data = $this->domain_management_model->master_transaction_request_list('b2b');

		$export_data = array();
        // debug($table_data);exit;
        $i=1;
        foreach ($table_data as $k => $v) {
           
             $i++;
			$export_data[$k]['system_transaction_id'] = $v['system_transaction_id'];
            $export_data[$k]['requested_from'] = $v['requested_from'];
            $export_data[$k]['transaction_type'] = $v['transaction_type'];
            $export_data[$k]['amount'] = $v['lead_pax_phone_number'];
            $export_data[$k]['status'] = $v['status'];
            $export_data[$k]['created_datetime'] =app_friendly_absolute_date($v['created_datetime']);
            $export_data[$k]['remarks'] = $v['remarks'];
            $export_data[$k]['update_remarks'] = $v['update_remarks'];
           	
        }

        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'System Transaction',
                    'c1' => 'Request From',
                    'd1' => 'Mode Of Payment',
                    'e1' => 'Amount',
                    'f1' => 'Status',
                    'g1' => 'Request Sent On',
                    'h1' => 'User Remarks',
                    'i1' => 'Update Remarks',
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'system_transaction_id',
                    'c' => 'requested_from',
                    'd' => 'transaction_type',
                    'e' => 'amount',
                    'f' => 'status',
                    'g' => 'created_datetime',
                    'h' => 'remarks',
                    'i' => 'update_remarks',
                    
                );
           
            $excel_sheet_properties = array(
                'title' => 'All_balance_manager' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'All_balance_manager',
                'sheet_title' => 'All_balance_manager'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
	}
	public function export_all_credit_request($op='')
	{
		$table_data = $this->domain_management_model->master_transaction_request_list('b2b', $data_list_filt=array(),'Credit');

		$export_data = array();
        // debug($table_data);exit;
        $i=1;
        foreach ($table_data as $k => $v) {
           
             $i++;
			$export_data[$k]['system_transaction_id'] = $v['system_transaction_id'];
            $export_data[$k]['requested_from'] = $v['requested_from'];
            $export_data[$k]['transaction_type'] = $v['transaction_type'];
            $export_data[$k]['amount'] = $v['lead_pax_phone_number'];
            $export_data[$k]['status'] = $v['status'];
            $export_data[$k]['created_datetime'] =app_friendly_absolute_date($v['created_datetime']);
            $export_data[$k]['remarks'] = $v['remarks'];
            $export_data[$k]['update_remarks'] = $v['update_remarks'];
           	
        }

        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'System Transaction',
                    'c1' => 'Request From',
                    'd1' => 'Mode Of Payment',
                    'e1' => 'Amount',
                    'f1' => 'Status',
                    'g1' => 'Request Sent On',
                    'h1' => 'User Remarks',
                    'i1' => 'Update Remarks',
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'system_transaction_id',
                    'c' => 'requested_from',
                    'd' => 'transaction_type',
                    'e' => 'amount',
                    'f' => 'status',
                    'g' => 'created_datetime',
                    'h' => 'remarks',
                    'i' => 'update_remarks',
                    
                );
           
            $excel_sheet_properties = array(
                'title' => 'All_balance_manager' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'All_balance_manager',
                'sheet_title' => 'All_balance_manager'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
	}
	function master_balance_manager($balance_request_type="Cash")
	{
		echo 'Under Construction';
		exit;
		$page_data['form_data'] = $this->input->post();

		switch(strtoupper($balance_request_type)) {
			case 'CHECK___DD'	:
				$page_data['balance_page_obj'] = new Provab_Page_Loader('balance_request_check');
				break;
			case 'ETRANSFER'	:
				$page_data['balance_page_obj'] = new Provab_Page_Loader('balance_request_e_transfer');
				break;
			case 'CASH'			:
				$page_data['balance_page_obj'] = new Provab_Page_Loader('balance_request_cash');
				break;
			default : redirect(base_url());
		}
		if (valid_array($page_data['form_data']) == true) {
			$page_data['balance_page_obj']->set_auto_validator();
			if ($this->form_validation->run()) {
				$page_data['form_data']['transaction_type'] = unserialized_data($page_data['form_data']['transaction_type']);
				if ($page_data['form_data']['origin'] == 0) {
					//Insert
					$status = $this->domain_management_model->save_master_transaction_details($page_data['form_data']);
				} elseif (intval($page_data['form_data']['origin']) > 0) {
					//FIXME :: Update Not Needed As Of Now
				}
				if ($status['status'] == SUCCESS_STATUS) {
					set_update_message();
				} else {
					set_error_message();
				}
				redirect(base_url().'index.php/management/'.__FUNCTION__.'/'.$balance_request_type);
			}

		}
		$page_data['table_data'] = $this->domain_management_model->master_transaction_request_list();
		$page_data['balance_request_type'] = strtoupper($balance_request_type);
		$page_data['provab_balance_requests'] = get_enum_list('provab_balance_requests');
		if (empty($page_data['form_data']['currency_converter_origin']) == true) {
			$page_data['form_data']['currency_converter_origin']	= COURSE_LIST_DEFAULT_CURRENCY;
			$page_data['form_data']['conversion_value']				= 1;
		}
		$page_data['form_data']['transaction_type'] = serialized_data($balance_request_type);
		$this->template->view('management/master_balance_manager', $page_data);
	}

	// Managing Balance of B2B users.
	public function b2b_balance_manager($balance_request_type="Cash")
	{
		$page_data['form_data'] = $this->input->post();
		
		if (valid_array($page_data['form_data']) == true) {
			if (intval($page_data['form_data']['request_origin']) > 0) {
				//echo debug($page_data['form_data']);exit;

				$process_details = $this->domain_management_model->process_balance_request($page_data['form_data']['request_origin'], $page_data['form_data']['system_request_id'], $page_data['form_data']['status_id'], $page_data['form_data']['update_remarks']);
				// debug($page_data);exit;
			} else {
				
				$page_data['balance_page_obj']->set_auto_validator();
				if ($this->form_validation->run()) {
					$page_data['form_data']['transaction_type'] = unserialized_data($page_data['form_data']['transaction_type']);
					if ($page_data['form_data']['request_origin'] == 0) {
						//Insert
						//$this->domain_management_model->save_master_transaction_details($page_data['form_data']);
					}
				}
			}

			if($page_data['form_data']['status_id']=="REJECTED" && valid_array($process_details) == true)
			{
			        $mail_template = $this->template->isolated_view('user/deposit_rejected_template',$process_details['data']);
					$this->load->library('provab_mailer');
				    $email=provab_decrypt($page_data['form_data']['request_user_email']);
				    $status = $this->provab_mailer->send_mail($email,'Account Request Rejected', $mail_template);
			}
			// echo 'herrere';exit;

			if ($process_details['status'] == SUCCESS_STATUS) {
				$data_list_filt = array();
				$data_list_filt[] = array('MTD.origin', '=',trim($page_data['form_data']['request_origin']));
				$data = $this->domain_management_model->master_transaction_request_list('b2b', $data_list_filt);
				if(!empty($data) && isset($data[0])){
					$master_transaction['master_transaction'] = $data[0];
					$email = $page_data['form_data']['request_user_email'];
					//$email=  "sagar@mailinator.com";
					$mail_template = $this->template->isolated_view('user/deposit_confirmation_template',$master_transaction);
					$this->load->library('provab_mailer');
					$status = $this->provab_mailer->send_mail($email,'Account Deposit', $mail_template);
				}
				set_update_message();
			} else {
				set_error_message();
			}
			redirect(base_url().'index.php/management/'.__FUNCTION__.'?'.$_SERVER['QUERY_STRING']);
		}
		$params = $this->input->get();
		if (isset($params['status']) == false) {
			//$params['status'] = 'PENDING';
		}
		$data_list_filt = array();
		if (isset($params['agency_name']) == true and empty($params['agency_name']) == false) {
			$data_list_filt[] = array('U.agency_name', 'like', $this->db->escape('%'.$params['agency_name'].'%'));
		}
		if (isset($params['uuid']) == true and empty($params['uuid']) == false) {
			$data_list_filt[] = array('U.uuid', 'like', $this->db->escape('%'.$params['uuid'].'%'));
		}
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
		$page_data['table_data'] = $this->domain_management_model->master_transaction_request_list('b2b', $data_list_filt);
		//echo debug($page_data['table_data']);exit;
		$page_data['provab_balance_requests'] = get_enum_list('provab_balance_requests');
		$page_data['provab_balance_status'] = get_enum_list('provab_balance_status');
		if (empty($page_data['form_data']['currency_converter_origin']) == true) {
			$page_data['form_data']['currency_converter_origin']	= COURSE_LIST_DEFAULT_CURRENCY;
			$page_data['form_data']['conversion_value']				= 1;
		}
		$page_data['status_options'] = get_enum_list('provab_balance_status');
		$page_data['heading'] = 'B2B Balance Request';
		$page_data['search_params'] = $params;
		$this->template->view('management/b2b_balance_manager', $page_data);

	}
	public function b2b_credit_request($balance_request_type="Cash")
	{
		$page_data['form_data'] = $this->input->post();
		//debug($page_data['form_data']);exit;



		if (valid_array($page_data['form_data']) == true) {
			if (intval($page_data['form_data']['request_origin']) > 0) {
				//echo debug($page_data['form_data']);exit;
				$process_details = $this->domain_management_model->process_credit_limit_request($page_data['form_data']['request_origin'], $page_data['form_data']['system_request_id'], $page_data['form_data']['status_id'], $page_data['form_data']['update_remarks']);
			} else {
				$page_data['balance_page_obj']->set_auto_validator();
				if ($this->form_validation->run()) {
					$page_data['form_data']['transaction_type'] = unserialized_data($page_data['form_data']['transaction_type']);
					if ($page_data['form_data']['request_origin'] == 0) {
						//Insert
						//$this->domain_management_model->save_master_transaction_details($page_data['form_data']);
					}
				}
			}

			if($page_data['form_data']['status_id']=="REJECTED" && valid_array($process_details) == true)
			{
			        $mail_template = $this->template->isolated_view('user/deposit_rejected_template',$process_details['data']);
					$this->load->library('provab_mailer');
				    $email=provab_decrypt($page_data['form_data']['request_user_email']);
				    $status = $this->provab_mailer->send_mail($email,'Account Request Rejected', $mail_template);
			}

			if ($process_details['status'] == SUCCESS_STATUS) {
				$data_list_filt = array();
				$data_list_filt[] = array('MTD.origin', '=',trim($page_data['form_data']['request_origin']));
				$data = $this->domain_management_model->master_transaction_request_list('b2b', $data_list_filt);

				if(!empty($data) && isset($data[0])){
					
					$master_transaction['master_transaction'] = $data[0];
					$email = $page_data['form_data']['request_user_email'];
					//$email=  "sagar@mailinator.com";
					$mail_template = $this->template->isolated_view('user/deposit_confirmation_template',$master_transaction);
					$this->load->library('provab_mailer');
					$status = $this->provab_mailer->send_mail($email,'Account Deposit', $mail_template);
				}
				
				set_update_message();
			} else {
				set_error_message();
			}
			redirect(base_url().'index.php/management/'.__FUNCTION__.'?'.$_SERVER['QUERY_STRING']);
		}
		$params = $this->input->get();
		if (isset($params['status']) == false) {
			//$params['status'] = 'PENDING';
		}
		$data_list_filt = array();
		if (isset($params['agency_name']) == true and empty($params['agency_name']) == false) {
			$data_list_filt[] = array('U.agency_name', 'like', $this->db->escape('%'.$params['agency_name'].'%'));
		}
		if (isset($params['uuid']) == true and empty($params['uuid']) == false) {
			$data_list_filt[] = array('U.uuid', 'like', $this->db->escape('%'.$params['uuid'].'%'));
		}
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
		$page_data['table_data'] = $this->domain_management_model->master_transaction_request_list('b2b', $data_list_filt,'Credit');
		//echo debug($page_data['table_data']);exit;
		$page_data['provab_balance_requests'] = get_enum_list('provab_balance_requests');
		$page_data['provab_balance_status'] = get_enum_list('provab_balance_status');
		if (empty($page_data['form_data']['currency_converter_origin']) == true) {
			$page_data['form_data']['currency_converter_origin']	= COURSE_LIST_DEFAULT_CURRENCY;
			$page_data['form_data']['conversion_value']				= 1;
		}
		$page_data['heading'] = 'B2B Credit Limit Request';
		$page_data['status_options'] = get_enum_list('provab_balance_status');
		$page_data['search_params'] = $params;
		$this->template->view('management/b2b_balance_manager', $page_data);

	}

	/**
	 * Event logging
	 * @param number $offset
	 */
	function event_logs($offset=0)
	{
		$condition = array();
		$page_data['table_data'] = $this->domain_management_model->event_logs($condition, false, $offset, RECORDS_RANGE_3);
		$total_records = $this->domain_management_model->event_logs($condition, true);
		$this->load->library('pagination');
		$config['base_url'] = base_url().'index.php/management/event_logs/';
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_3;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$this->template->view('management/event_logs', $page_data);
	}
	/**
	 * Balu A
	 * Update B2B Agent Commission
	 */
	function agent_commission($offset=0)
	{
		$get_data = $this->input->get();
		$post_data = $this->input->post();
		// debug($post_data);exit;
		$page_data = array();
		$this->load->library('Api_Interface');
		if(isset($get_data['agent_ref_id']) == true && empty($get_data['agent_ref_id']) == false && valid_array($post_data) == false) {
			//Get Data
			$agent_ref_id = base64_decode(trim($get_data['agent_ref_id']));
			$page_data['agent_ref_id'] = $agent_ref_id;
			$agent_commission_details = $this->domain_management_model->get_commission_details($agent_ref_id);
			if($agent_commission_details['status'] == true) {
				$page_data['commission_details'] = $agent_commission_details['data'];
			} else {
				//Invalid CRUD
				redirect('security/log_event?event=InvalidAgent');
			}
		} else if(valid_array($post_data) == true && isset($post_data['module']) == true && empty($post_data['module']) == false) {
			foreach($post_data['module'] as $module_k => $module_v) {
				$module = trim($module_v);
				$module = trim($module_v);
				switch ($module) {
					case META_AIRLINE_COURSE://Airline Commission
						$update_flight_commission_data['module'] = $post_data['module'][$module_k];
						$update_flight_commission_data['agent_ref_id'] = $post_data['agent_ref_id'][$module_k];
						$update_flight_commission_data['flight_commission_origin'] = $post_data['commission_origin'][$module_k];
						$update_flight_commission_data['flight_commission'] = $post_data['commission'][$module_k];
						$update_flight_commission_data['api_value'] = $post_data['api_value'][$module_k];
						$this->update_b2b_flight_commission($update_flight_commission_data);
						break;
					case META_BUS_COURSE://Bus Commission
						$update_bus_commission_data['module'] = $post_data['module'][$module_k];
						$update_bus_commission_data['agent_ref_id'] = $post_data['agent_ref_id'][$module_k];
						$update_bus_commission_data['bus_commission_origin'] = $post_data['commission_origin'][$module_k];
						$update_bus_commission_data['bus_commission'] = $post_data['commission'][$module_k];
						$update_bus_commission_data['api_value'] = $post_data['api_value'][$module_k];
						$this->update_b2b_bus_commission($update_bus_commission_data);
						break;
					case META_SIGHTSEEING_COURSE://Sightseeing Commission

						$update_sightseeing_commission_data['module'] = $post_data['module'][$module_k];
						$update_sightseeing_commission_data['agent_ref_id'] = $post_data['agent_ref_id'][$module_k];
						$update_sightseeing_commission_data['sightseeing_commission_origin'] = $post_data['commission_origin'][$module_k];
						$update_sightseeing_commission_data['sightseeing_commission'] = $post_data['commission'][$module_k];
						$update_sightseeing_commission_data['api_value'] = $post_data['api_value'][$module_k];
						$this->update_b2b_sightseeing_commission($update_sightseeing_commission_data);
						break;
					case META_TRANSFERV1_COURSE://Sightseeing Commission

						$update_transfer_commission_data['module'] = $post_data['module'][$module_k];
						$update_transfer_commission_data['agent_ref_id'] = $post_data['agent_ref_id'][$module_k];
						$update_transfer_commission_data['transfer_commission_origin'] = $post_data['commission_origin'][$module_k];
						$update_transfer_commission_data['transfer_commission'] = $post_data['commission'][$module_k];
						$update_transfer_commission_data['api_value'] = $post_data['api_value'][$module_k];
						$this->update_b2b_transfer_commission($update_transfer_commission_data);
						break;
						case META_PACKAGE_COURSE://Sightseeing Commission

						$update_tour_commission_data['module'] = $post_data['module'][$module_k];
						$update_tour_commission_data['agent_ref_id'] = $post_data['agent_ref_id'][$module_k];
						$update_tour_commission_data['tour_commission_origin'] = $post_data['commission_origin'][$module_k];
						$update_tour_commission_data['tour_commission'] = $post_data['commission'][$module_k];
						$update_tour_commission_data['api_value'] = $post_data['api_value'][$module_k];
						$this->update_b2b_tour_commission($update_tour_commission_data);
						break;

				}
			}
			set_update_message();
			if(empty($_SERVER['QUERY_STRING']) == false) {
				$query_string = '?'.$_SERVER['QUERY_STRING'];
			} else {
				$query_string = '';
			}
			redirect('management/agent_commission'.$query_string);
		}
		if(isset($get_data['default_commission']) == true && $get_data['default_commission'] == ACTIVE) {
			//Default Commission
			$page_data['default_commission'] = ACTIVE;
			$commission_details = $this->domain_management_model->default_commission_details();//Default Commission Details
			$page_data['commission_details'] = $commission_details['data'];
		} else {
			
			//Agent's List
			if(isset($get_data['filter']) == true && $get_data['filter'] == 'search_agent' &&
			isset($get_data['filter_agency']) == true && empty($get_data['filter_agency']) == false) {
				$filter_agency = trim($get_data['filter_agency']);
				//Search Filter
				$search_filter_condition = '(U.uuid like "%'.$filter_agency.'%" OR U.agency_name like "%'.$filter_agency.'%" OR U.first_name like "%'.$filter_agency.'%" OR U.last_name like "%'.$filter_agency.'%" OR U.email like "%'.$filter_agency.'%" OR U.phone like "%'.$filter_agency.'%")';
				$total_records = $this->domain_management_model->filter_agent_commission_details($search_filter_condition, true);
				$agent_list = $this->domain_management_model->filter_agent_commission_details($search_filter_condition, false, $offset, RECORDS_RANGE_1);
			} else {
				/** TABLE PAGINATION */
				$condition[] = array('U.user_type', ' IN', '('.B2B_USER.')');
				//princess added (agent list only active)
				$condition[]= array('U.status', 'IN', '(1)');
				//$page_data['agent_list'] = $this->user_model->get_domain_user_list($condition, false, $offset, RECORDS_RANGE_1);
	
				$total_records = $this->domain_management_model->agent_commission_details($condition, true);
				$agent_list = $this->domain_management_model->agent_commission_details($condition, false, $offset, RECORDS_RANGE_1);
			}
			$page_data['agent_list'] = $agent_list['data']['agent_commission_details'] ;
			$this->load->library('pagination');
			if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
			$config['base_url'] = base_url().'index.php/management/agent_commission/';
			$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
			$config['total_rows'] = $total_records->total;
			$config['per_page'] = RECORDS_RANGE_1;
			$this->pagination->initialize($config);
			/** TABLE PAGINATION */
		}
		/****************Super Admin Commission**********************/
		$sa_bus_commission = json_decode($this->api_interface->rest_service('bus_commission_details'),true);
		//Bus
		if($sa_bus_commission['status'] == true) {
			$super_admin_bus_commission['value'] = $sa_bus_commission['data'][0]['value'];
			$super_admin_bus_commission['api_value'] = $sa_bus_commission['data'][0]['api_value'];
		} else {
			$super_admin_bus_commission['value'] = 0;
			$super_admin_bus_commission['api_value'] = 0;
		}
		//Flight
		$sa_admin_flight_commission = json_decode($this->api_interface->rest_service('airline_commission_details'),true);
		//debug($sa_admin_flight_commission); die;
		if($sa_admin_flight_commission['status'] == true) {
			$super_admin_flight_commission['value'] = $sa_admin_flight_commission['data'][0]['value'];
			$super_admin_flight_commission['api_value'] = $sa_admin_flight_commission['data'][0]['api_value'];
		} else {
			$super_admin_flight_commission['value'] = 0;
			$super_admin_flight_commission['api_value'] = 0;
		}

		$sa_sightseeing_commission = json_decode($this->api_interface->rest_service('sightseeing_commission_details'),true);
		//Bus
		if($sa_sightseeing_commission['status'] == true) {
			$super_admin_sightseeing_commission['value'] = $sa_sightseeing_commission['data'][0]['value'];
			$super_admin_sightseeing_commission['api_value'] = $sa_sightseeing_commission['data'][0]['api_value'];
		} else {
			$super_admin_sightseeing_commission['value'] = 0;
			$super_admin_sightseeing_commission['api_value'] = 0;
		}
		$sa_transfer_commission = json_decode($this->api_interface->rest_service('transfer_commission_details'),true);
		//Transfers
		if($sa_transfer_commission['status'] == true) {
			$super_admin_transfer_commission['value'] = $sa_transfer_commission['data'][0]['value'];
			$super_admin_transfer_commission['api_value'] = $sa_transfer_commission['data'][0]['api_value'];
		} else {
			$super_admin_transfer_commission['value'] = 0;
			$super_admin_transfer_commission['api_value'] = 0;
		}

		$page_data['super_admin_bus_commission'] = $super_admin_bus_commission;
		$page_data['super_admin_flight_commission'] = $super_admin_flight_commission;
		$page_data['super_admin_sightseeing_commission'] = $super_admin_sightseeing_commission;
		$page_data['super_admin_transfer_commission'] = $super_admin_transfer_commission;
		
		// debug($page_data);
		// exit;
		/****************Super Admin Commission**********************/

		$this->template->view('management/agent_commission', $page_data);
	}



/**
	 * Balu A
	 * Update B2B Agent Commission
	 */
	function set_b2c_commission($offset=0)
	{
		$page_data=array();
		$this->template->view('management/b2c_commission', $page_data);
	}

	/**
	 * Balu A
	 * Update Flight Commission Details
	 * @param $commission_details
	 */
	function update_b2b_flight_commission($commission_details)
	{
		if(isset($commission_details['module']) == true && empty($commission_details['module']) == false &&
		isset($commission_details['agent_ref_id']) == true && empty($commission_details['agent_ref_id']) == false &&
		isset($commission_details['flight_commission_origin']) == true && isset($commission_details['flight_commission']) == true) {
			$origin = trim($commission_details['flight_commission_origin']);
			$agent_ref_id = base64_decode(trim($commission_details['agent_ref_id']));
			$commission_value = floatval(trim($commission_details['flight_commission']));
			$api_value = floatval(trim($commission_details['api_value']));
			$b2b_flight_commission_details = array();
			if(intval($agent_ref_id) > 0) {
				$b2b_flight_commission_details['type'] = SPECIFIC;
			} else {
				$b2b_flight_commission_details['type'] = GENERIC;
			}
			$b2b_flight_commission_details['value'] = $commission_value;
			$b2b_flight_commission_details['api_value'] = $api_value;
			$b2b_flight_commission_details['value_type'] = MARKUP_VALUE_PERCENTAGE;
			$b2b_flight_commission_details['commission_currency'] = MARKUP_CURRENCY;
			$b2b_flight_commission_details['created_by_id'] = $this->entity_user_id;
			$b2b_flight_commission_details['created_datetime'] = date('Y-m-d H:i:s');
			if($origin >0) {
				//UPDATE
				if(intval($agent_ref_id) > 0) {//Specific Agent Commission
					$update_condition['agent_fk'] = $agent_ref_id;
				} else {//Default Commission
					$update_condition['type'] = GENERIC;
				}
				$this->custom_db->update_record('b2b_flight_commission_details', $b2b_flight_commission_details, $update_condition);
			} else {
				//ADD
				$b2b_flight_commission_details['agent_fk'] = $agent_ref_id;
				$b2b_flight_commission_details['domain_list_fk'] = get_domain_auth_id();
				if(intval($agent_ref_id) > 0) {//Specific Agent Commission
					$delete_condition['agent_fk'] = $agent_ref_id;
				} else {//Default Commission
					$delete_condition['type'] = GENERIC;
				}
				$this->custom_db->delete_record('b2b_flight_commission_details', $delete_condition);
				$this->custom_db->insert_record('b2b_flight_commission_details', $b2b_flight_commission_details);
			}
		} else {
			redirect('security/log_event?event=InvalidFlightCommissionDetails');
		}
	}
	/**
	 * Balu A
	 * Update Bus Commission Details
	 * @param $commission_details
	 */
	function update_b2b_bus_commission($commission_details)
	{
		if(isset($commission_details['module']) == true && empty($commission_details['module']) == false &&
		isset($commission_details['agent_ref_id']) == true && empty($commission_details['agent_ref_id']) == false &&
		isset($commission_details['bus_commission_origin']) == true && isset($commission_details['bus_commission']) == true) {
			$origin = trim($commission_details['bus_commission_origin']);
			$agent_ref_id = base64_decode(trim($commission_details['agent_ref_id']));
			$commission_value = floatval(trim($commission_details['bus_commission']));
			$api_value = floatval(trim($commission_details['api_value']));
			$b2b_bus_commission_details = array();
			if(intval($agent_ref_id) > 0) {
				$b2b_bus_commission_details['type'] = SPECIFIC;
			} else {
				$b2b_bus_commission_details['type'] = GENERIC;
			}
			$b2b_bus_commission_details['value'] = $commission_value;
			$b2b_bus_commission_details['api_value'] = $api_value;
			$b2b_bus_commission_details['value_type'] = MARKUP_VALUE_PERCENTAGE;
			$b2b_bus_commission_details['commission_currency'] = MARKUP_CURRENCY;
			$b2b_bus_commission_details['created_by_id'] = $this->entity_user_id;
			$b2b_bus_commission_details['created_datetime'] = date('Y-m-d H:i:s');
			if($origin >0) {
				//UPDATE
				if(intval($agent_ref_id) > 0) {//Specific Agent Commission
					$update_condition['agent_fk'] = $agent_ref_id;
				} else {//Default Commission
					$update_condition['type'] = GENERIC;
				}
				$this->custom_db->update_record('b2b_bus_commission_details', $b2b_bus_commission_details, $update_condition);
			} else {
				//ADD
				$b2b_bus_commission_details['agent_fk'] = $agent_ref_id;
				$b2b_bus_commission_details['domain_list_fk'] = get_domain_auth_id();
				if(intval($agent_ref_id) > 0) {//Specific Agent Commission
					$delete_condition['agent_fk'] = $agent_ref_id;
				} else {//Default Commission
					$delete_condition['type'] = GENERIC;
				}
				$this->custom_db->delete_record('b2b_bus_commission_details', $delete_condition);
				$this->custom_db->insert_record('b2b_bus_commission_details', $b2b_bus_commission_details);
			}
		} else {
			redirect('security/log_event?event=InvalidBusCommissionDetails');
		}
	}
	/**
	 * Elavarasi
	 * Update Sightseeing Commission Details
	 * @param $commission_details
	 */
	function update_b2b_sightseeing_commission($commission_details)
	{
		if(isset($commission_details['module']) == true && empty($commission_details['module']) == false &&
		isset($commission_details['agent_ref_id']) == true && empty($commission_details['agent_ref_id']) == false &&
		isset($commission_details['sightseeing_commission_origin']) == true && isset($commission_details['sightseeing_commission']) == true) {
			$origin = trim($commission_details['sightseeing_commission_origin']);
			$agent_ref_id = base64_decode(trim($commission_details['agent_ref_id']));
			$commission_value = floatval(trim($commission_details['sightseeing_commission']));
			$api_value = floatval(trim($commission_details['api_value']));
			$b2b_sightseeing_commission_details = array();
			if(intval($agent_ref_id) > 0) {
				$b2b_sightseeing_commission_details['type'] = SPECIFIC;
			} else {
				$b2b_sightseeing_commission_details['type'] = GENERIC;
			}
			$b2b_sightseeing_commission_details['value'] = $commission_value;
			$b2b_sightseeing_commission_details['api_value'] = $api_value;
			$b2b_sightseeing_commission_details['value_type'] = MARKUP_VALUE_PERCENTAGE;
			$b2b_sightseeing_commission_details['commission_currency'] = MARKUP_CURRENCY;
			$b2b_sightseeing_commission_details['created_by_id'] = $this->entity_user_id;
			$b2b_sightseeing_commission_details['created_datetime'] = date('Y-m-d H:i:s');
			if($origin >0) {
				//UPDATE
				if(intval($agent_ref_id) > 0) {//Specific Agent Commission
					$update_condition['agent_fk'] = $agent_ref_id;
				} else {//Default Commission
					$update_condition['type'] = GENERIC;
				}
				$this->custom_db->update_record('b2b_sightseeing_commission_details', $b2b_sightseeing_commission_details, $update_condition);
			} else {
				//ADD
				$b2b_sightseeing_commission_details['agent_fk'] = $agent_ref_id;
				$b2b_sightseeing_commission_details['domain_list_fk'] = get_domain_auth_id();
				if(intval($agent_ref_id) > 0) {//Specific Agent Commission
					$delete_condition['agent_fk'] = $agent_ref_id;
				} else {//Default Commission
					$delete_condition['type'] = GENERIC;
				}
				$this->custom_db->delete_record('b2b_sightseeing_commission_details', $delete_condition);
				$this->custom_db->insert_record('b2b_sightseeing_commission_details', $b2b_sightseeing_commission_details);
			}
		} else {
			redirect('security/log_event?event=InvalidBusCommissionDetails');
		}
	}
	/**
	 * Elavarasi
	 * Update Transfer Commission Details
	 * @param $commission_details
	 */
	function update_b2b_transfer_commission($commission_details)
	{
		if(isset($commission_details['module']) == true && empty($commission_details['module']) == false &&
		isset($commission_details['agent_ref_id']) == true && empty($commission_details['agent_ref_id']) == false &&
		isset($commission_details['transfer_commission_origin']) == true && isset($commission_details['transfer_commission']) == true) {
			$origin = trim($commission_details['transfer_commission_origin']);
			$agent_ref_id = base64_decode(trim($commission_details['agent_ref_id']));
			$commission_value = floatval(trim($commission_details['transfer_commission']));
			$api_value = floatval(trim($commission_details['api_value']));
			$b2b_transfer_commission_details = array();
			if(intval($agent_ref_id) > 0) {
				$b2b_transfer_commission_details['type'] = SPECIFIC;
			} else {
				$b2b_transfer_commission_details['type'] = GENERIC;
			}
			$b2b_transfer_commission_details['value'] = $commission_value;
			$b2b_transfer_commission_details['api_value'] = $api_value;
			$b2b_transfer_commission_details['value_type'] = MARKUP_VALUE_PERCENTAGE;
			$b2b_transfer_commission_details['commission_currency'] = MARKUP_CURRENCY;
			$b2b_transfer_commission_details['created_by_id'] = $this->entity_user_id;
			$b2b_transfer_commission_details['created_datetime'] = date('Y-m-d H:i:s');
			if($origin >0) {
				//UPDATE
				if(intval($agent_ref_id) > 0) {//Specific Agent Commission
					$update_condition['agent_fk'] = $agent_ref_id;
				} else {//Default Commission
					$update_condition['type'] = GENERIC;
				}
				$this->custom_db->update_record('b2b_transfer_commission_details', $b2b_transfer_commission_details, $update_condition);
			} else {
				//ADD
				$b2b_transfer_commission_details['agent_fk'] = $agent_ref_id;
				$b2b_transfer_commission_details['domain_list_fk'] = get_domain_auth_id();
				if(intval($agent_ref_id) > 0) {//Specific Agent Commission
					$delete_condition['agent_fk'] = $agent_ref_id;
				} else {//Default Commission
					$delete_condition['type'] = GENERIC;
				}
				$this->custom_db->delete_record('b2b_transfer_commission_details', $delete_condition);
				$this->custom_db->insert_record('b2b_transfer_commission_details', $b2b_transfer_commission_details);
			}
		} else {
			redirect('security/log_event?event=InvalidBusCommissionDetails');
		}
	}

	function update_b2b_tour_commission($commission_details)
	{
		if(isset($commission_details['module']) == true && empty($commission_details['module']) == false &&
		isset($commission_details['agent_ref_id']) == true && empty($commission_details['agent_ref_id']) == false &&
		isset($commission_details['tour_commission_origin']) == true && isset($commission_details['tour_commission']) == true) {
			$origin = trim($commission_details['tour_commission_origin']);
			$agent_ref_id = base64_decode(trim($commission_details['agent_ref_id']));
			$commission_value = floatval(trim($commission_details['tour_commission']));
			$api_value = floatval(trim($commission_details['api_value']));
			$b2b_tour_commission_details = array();
			if(intval($agent_ref_id) > 0) {
				$b2b_tour_commission_details['type'] = SPECIFIC;
			} else {
				$b2b_tour_commission_details['type'] = GENERIC;
			}
			$b2b_tour_commission_details['value'] = $commission_value;
			$b2b_tour_commission_details['api_value'] = $api_value;
			$b2b_tour_commission_details['value_type'] = MARKUP_VALUE_PERCENTAGE;
			$b2b_tour_commission_details['commission_currency'] = MARKUP_CURRENCY;
			$b2b_tour_commission_details['created_by_id'] = $this->entity_user_id;
			$b2b_tour_commission_details['created_datetime'] = date('Y-m-d H:i:s');
			if($origin >0) {
				//UPDATE
				if(intval($agent_ref_id) > 0) {//Specific Agent Commission
					$update_condition['agent_fk'] = $agent_ref_id;
				} else {//Default Commission
					$update_condition['type'] = GENERIC;
				}
				$this->custom_db->update_record('b2b_tour_commission_details', $b2b_tour_commission_details, $update_condition);
			} else {
				//ADD
				$b2b_tour_commission_details['agent_fk'] = $agent_ref_id;
				$b2b_tour_commission_details['domain_list_fk'] = get_domain_auth_id();
				if(intval($agent_ref_id) > 0) {//Specific Agent Commission
					$delete_condition['agent_fk'] = $agent_ref_id;
				} else {//Default Commission
					$delete_condition['type'] = GENERIC;
				}
				$this->custom_db->delete_record('b2b_tour_commission_details', $delete_condition);
				$this->custom_db->insert_record('b2b_tour_commission_details', $b2b_tour_commission_details);
			}
		} else {
			redirect('security/log_event?event=InvalidBusCommissionDetails');
		}
	}
	/**
	 * Balu A
	 * Manages Bank Account Details
	 */
	function bank_account_details()
	{
		if(!$_POST['eid']) { 
		$post_data['form_data'] = $this->input->post();
	    } else { 
		$get_data = $this->input->post();
	     }

		$page_data['form_data'] = array();
		if(valid_array($post_data['form_data']) == false && isset($get_data['eid']) && intval($get_data['eid'])>0) {

			$temp_data=$this->custom_db->single_table_records('bank_account_details', '*', array('origin' => $get_data['eid']));

			$page_data['form_data']['origin']=$temp_data['data'][0]['origin'];
			$page_data['form_data']['en_account_name']=$temp_data['data'][0]['en_account_name'];
			$page_data['form_data']['en_bank_name']=$temp_data['data'][0]['en_bank_name'];
			$page_data['form_data']['en_branch_name']=$temp_data['data'][0]['en_branch_name'];
			$page_data['form_data']['bank_icon']=$temp_data['data'][0]['bank_icon'];
			$page_data['form_data']['account_number']=$temp_data['data'][0]['account_number'];
			$page_data['form_data']['ifsc_code']=$temp_data['data'][0]['ifsc_code'];
			$page_data['form_data']['pan_number']=$temp_data['data'][0]['pan_number'];
			$page_data['form_data']['status']=$temp_data['data'][0]['status'];
		} else if( valid_array($post_data['form_data']) ) {
			$this->current_page->set_auto_validator();
			if ($this->form_validation->run()) {
				$origin = intval($post_data['form_data']['origin']);
				unset($post_data['form_data']['FID']);
				unset($post_data['form_data']['origin']);
				if($origin > 0) {
					/** UPDATE **/
					$post_data['form_data']['updated_by_id'] = $this->entity_user_id;
					$post_data['form_data']['updated_datetime'] = date('Y-m-d H:i:s');
					$this->custom_db->update_record('bank_account_details', $post_data['form_data'],array('origin' => $origin) );
					set_update_message();
				} elseif($origin == 0){
					/** INSERT **/
					$post_data['form_data']['domain_list_fk'] = get_domain_auth_id();;
					$post_data['form_data']['created_by_id'] = $this->entity_user_id;
					$post_data['form_data']['created_datetime'] = date('Y-m-d H:i:s');
					$insert_id=$this->custom_db->insert_record('bank_account_details',$post_data['form_data']);
					set_insert_message();
				}
				//FILE UPLOAD
				if (valid_array($_FILES) == true and $_FILES['bank_icon']['error'] == 0 and $_FILES['bank_icon']['size'] > 0) {
					if( function_exists( "check_mime_image_type" ) ) {
						    if ( !check_mime_image_type( $_FILES['bank_icon']['tmp_name'] ) ) {
						    	echo "Please select the image files only (gif|jpg|png|jpeg)"; exit;
						    }
					}
						$config['upload_path']='../extras/custom/TMX9604421616070986/images//bank_logo/';
					$config['allowed_types'] = 'gif|jpg|png|jpeg';
					$config['file_name'] = time();
					$config['max_size'] = MAX_DOMAIN_LOGO_SIZE;
					$config['max_width']  = MAX_DOMAIN_LOGO_WIDTH;
					$config['max_height']  = MAX_DOMAIN_LOGO_HEIGHT;
					$config['remove_spaces']  = false;
					//debug(	$config['upload_path']);die;
					if (empty($insert_id) == true) {
						//UPDATE
						$temp_record = $this->custom_db->single_table_records('bank_account_details', 'bank_icon', array('origin' => $origin));
						$icon = $temp_record['data'][0]['bank_icon'];
						//DELETE OLD FILES
						if (empty($icon) == false) {
							if (file_exists($config['upload_path'].$icon)) {
								unlink($config['upload_path'].$icon);
							}
						}
					} else {
						$origin = $insert_id['insert_id'];
					}
					//UPLOAD IMAGE
					$this->load->library('upload', $config);
					if ( ! $this->upload->do_upload('bank_icon')) {
						echo $this->upload->display_errors();
					} else {
						$image_data =  $this->upload->data();
					}
					$this->custom_db->update_record('bank_account_details', array('bank_icon' => $image_data['file_name']), array('origin' => $origin));
				}
				redirect('management/bank_account_details');
			}
		} else {
			$page_data['form_data']['origin']=0;
		}
		/** Table Data **/
		$temp_data=$this->domain_management_model->bank_account_details();
		if($temp_data['status']) {
			$page_data['table_data'] = $temp_data['data'];
		} else {
			$page_data['table_data'] = "";
		}

		$this->template->view('management/bank_account_details',$page_data);
	}
	public function bank_account_details_supplier(){
	    $temp_data=$this->domain_management_model->bank_account_details_supplier();
	    //debug($temp_data);die;
		if($temp_data['status']) {
			$page_data['table_data'] = $temp_data['data'];
		} else {
			$page_data['table_data'] = '';
		}
	//	debug($page_data);die;
		$this->template->view('management/bank_account_details_supplier',$page_data);
	}


	/*
	 *Admin Account Ledger
	 *
	*/

	public function account_ledger($offset=0)
	{
		$get_data = $this->input->get();
		$condition = array();
		$page_data = array();
		
		$agent_details = array();

		if(isset($get_data['agent_id']) == true && intval($get_data['agent_id']) >0 ){
			$agent_id = intval($get_data['agent_id']);
		} else{
			$agent_id = 0;
		}

		$condition[] = array('U.user_id', '=', $agent_id);
		$complete_agent_details = $this->domain_management_model->get_agent_details($agent_id);
		if(valid_array($complete_agent_details) == true){
			$agent_details['agency_name'] = $complete_agent_details['agency_name'];
			$agent_details['agent_balance'] = $complete_agent_details['balance'];
			$agent_details['agent_currency'] = $complete_agent_details['agent_base_currency'];
		}
		$page_data['agent_details'] = $agent_details;

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
		//echo $transaction_logs; die();
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
		// get active agent list
		$agent_list['data'] = $this->domain_management_model->agent_list();
		$page_data['agent_list'] = magical_converter(array('k' => 'user_id', 'v' => 'agency_name'), $agent_list);

		$this->template->view ( 'management/account_ledger', $page_data );
	}

	/*
	*Export Account Ledger details to Excel Format
	*/
	public function export_account_ledger($op=''){
		
		$get_data = $this->input->GET();
		$condition = array();

		if(isset($get_data['agent_id']) == true && intval($get_data['agent_id']) >0 ){
			$agent_id = intval($get_data['agent_id']);
		} else{
			$agent_id = 0;
		}
		$condition[] = array('U.user_id', '=', $agent_id);
		
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
	        				'description' => 'Account Ledger of All Clients', 
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

	/*
	*Getting Admin Balance
	*/
	public function get_travelomatix_balance()
	{

		$balance = current_application_balance(); 
                
		$json_arr = array('face_value'=>$balance['face_value'],'credit_limit'=>$balance['credit_limit'],'due_amount'=>$balance['due_amount']);				
		echo json_encode($json_arr);
		exit;
	}
	//GST Details
	function gst_master(){
		$post_data = $this->input->post ();
	    $condition = array(); 
	    if (isset($post_data) && valid_array($post_data)) {
 			for( $i=0;$i < COUNT($post_data['gst_origin']); $i++) {
	            $data = array(
	                'tds' => $post_data['tds'][$i],
	                'gst' => $post_data['gst'][$i],
	                'modified_date' => date('Y-m-d H:i:s')
	            );
	            
	            $update_origin = $post_data['gst_origin'][$i];
	            $condition ['origin'] = $update_origin;
	            $group_data = $this->db->update ( 'gst_master', $data, $condition );
	        }
	        redirect ( base_url () . 'index.php/management/' . __FUNCTION__ );
	    }
		$page_data['details'] = $this->db->get ( 'gst_master' )->result_array ();
		// debug($page_data['details']);exit;
		$this->template->view('management/gst_master', $page_data);
	}
	 /** Anitha G Update Credit Limit ** /
     * 
     */
    public function credit_balance_show() {

        $get_data = $this->input->get();

    	// debug($get_data);exit;
        if (valid_array($get_data) == true && empty($get_data['agent_id']) == false ) {
			$user_details = $this->user_model->get_agent_info($get_data['agent_id']);
			// debug($user_details);exit;
            if (valid_array($user_details) == false) {//Invalid Domain ID
                redirect(base_url());
            }
            $user_details = $user_details[0];
            $page_data['user_details'] = $user_details;
        } 
		$this->template->view('management/credit_limit', $page_data);
    }

     public function credit_balance_update() {

        $get_data = $this->input->post();
        // debug($get_data);exit;
        if (valid_array($get_data) == true && empty($get_data['origin']) == false) {
            $page_data['credit_limit']=$get_data['amount'];
            $page_data['user_id']=$get_data['user_id'];
            $page_data['origin']=$get_data['origin'];
            $Update_info = $this->user_model->update_credit_limit($page_data);
            $user_details = $this->user_model->get_agent_info($get_data['user_id']);
         	$user_details = $user_details[0];
         	$page_data['user_details'] = $user_details;
	    }
        
        $this->template->view('management/credit_limit', $page_data);
    }
    function package_domain_markup()
	{
	   error_reporting(E_ALL);
	   // debug("dsfgsdfgdfg");exit;
		//Hotel would have All - general and domain wise markup
		$page_data['form_data'] = $this->input->post();
		// debug($page_data);
		// die;
		if (valid_array($page_data['form_data']) == true) {
			switch($page_data['form_data']['form_values_origin']) {
				case 'generic':
					$this->domain_management_model->save_markup_data(
					$page_data['form_data']['markup_origin'], $page_data['form_data']['form_values_origin'], 'b2c_holiday', 0,
					$page_data['form_data']['generic_value'], $page_data['form_data']['value_type'], 
					//$page_data['form_data']['domain_origin']
					get_domain_auth_id()
					);
					break;
				case 'specific':
					if (valid_array($page_data['form_data']['domain_origin'])) {
						foreach($page_data['form_data']['domain_origin'] as $__k => $__domain_origin) {
							if ($page_data['form_data']['specific_value'][$__k] != '' && intval($page_data['form_data']['specific_value'][$__k]) > -1
							&& empty($page_data['form_data']['value_type_'.$__domain_origin]) == false
							) {
								$this->domain_management_model->save_markup_data(
								$page_data['form_data']['markup_origin'][$__k], $page_data['form_data']['form_values_origin'], 'b2c_holiday', $page_data['form_data']['domain_origin'][$__k],
								$page_data['form_data']['specific_value'][$__k], $page_data['form_data']['value_type_'.$__domain_origin], $page_data['form_data']['domain_origin'][$__k]
								);
							}
						}
					}
					break;
			}
			redirect(base_url().'index.php/management/'.__FUNCTION__);
		}
		//Airline would have All - general and domain wise markup
		$data_list = $this->domain_management_model->package_domain_markup();
		// debug($data_list);exit;
		$this->template->view('management/package_domain_markup', $data_list['data']);
	}
	function b2bpackage_domain_markup()
	{
		//Hotel would have All - general and domain wise markup
		$page_data['form_data'] = $this->input->post();
		// debug($page_data);
		// die;
		if (valid_array($page_data['form_data']) == true) {
			switch($page_data['form_data']['form_values_origin']) {
				case 'generic':
					$this->domain_management_model->save_markup_data(
					$page_data['form_data']['markup_origin'], $page_data['form_data']['form_values_origin'], 'b2b_holiday', 0,
					$page_data['form_data']['generic_value'], $page_data['form_data']['value_type'],
					//$page_data['form_data']['domain_origin']
					get_domain_auth_id()
					
					);
					break;
				case 'specific':
					if (valid_array($page_data['form_data']['domain_origin'])) {
						foreach($page_data['form_data']['domain_origin'] as $__k => $__domain_origin) {
							if ($page_data['form_data']['specific_value'][$__k] != '' && intval($page_data['form_data']['specific_value'][$__k]) > -1
							&& empty($page_data['form_data']['value_type_'.$__domain_origin]) == false
							) {
								$this->domain_management_model->save_markup_data(
								$page_data['form_data']['markup_origin'][$__k], $page_data['form_data']['form_values_origin'], 'b2b_holiday', $page_data['form_data']['domain_origin'][$__k],
								$page_data['form_data']['specific_value'][$__k], $page_data['form_data']['value_type_'.$__domain_origin], $page_data['form_data']['domain_origin'][$__k]
								);
							}
						}
					}
					break;
			}
			redirect(base_url().'index.php/management/'.__FUNCTION__);
		}
		//Airline would have All - general and domain wise markup
		$data_list = $this->domain_management_model->b2bpackage_domain_markup();
		$this->template->view('management/b2bpackage_domain_markup', $data_list['data']);
	}
}
