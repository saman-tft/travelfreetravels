<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @package    Provab - Provab Application
 * @subpackage Travel Portal
 * @author     Balu A<balu.provab@gmail.com>
 * @version    V2
 */

class Report extends CI_Controller {
	private $current_module;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('bus_model');
		$this->load->model('hotel_model');
		$this->load->model('flight_model');
		//$this->load->model('flight_crs_report_model');
		$this->load->model('sightseeing_model');
		$this->load->model('transferv1_model');
		$this->load->model('transfers_model');
		$this->load->model('activity_model');
			$this->load->model('transaction_model');
		$this->load->model('car_model');
	$this->load->model('package_model');
		$this->load->model('user_model');
		$this->load->library('booking_data_formatter');
	
		$this->load->model('domain_management_model');
		$this->current_module = $this->config->item('current_module');
		//$this->load->library('export');


	}
	function index()
	{
		redirect('general');
	}
	function b2c_activitiescrs_report($offset=0)
	{
		
		
		// error_reporting(E_ALL);
		// echo '<h4>Under Working</h4>';
		$current_user_id = $GLOBALS['CI']->entity_user_id;
		$get_data = $this->input->get();
		// debug($get_data); exit();
		//debug($get_data); die;
		$condition = array();

		$filter_data = $this->format_basic_search_filters('activity');
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];
		
		//$condition[] = array('U.user_type', '=', B2C_USER, ' OR ', 'BD.created_by_id');

	//	$condition[] = array('BD.module_type', '=', '"activity"');


		// debug(RECORDS_RANGE_2);
		//if(isset($get_data['filter_report_data']) == true && empty($get_data['filter_report_data']) == false) {
			//debug($get_data['filter_report_data']);exit;
		//	$filter_report_data = trim($get_data['filter_report_data']);
//$search_filter_condition = '(BD.app_reference like "%'.$filter_report_data.'%" )';
			//debug($search_filter_condition);exit;
			//$total_records = $this->activity_model->b2c_holiday_report_filter($search_filter_condition, true);
			//$table_data = $this->activity_model->b2c_holiday_report_filter($search_filter_condition);
		//}
		//
		//{
			$total_records = $this->activity_model->b2c_holidaycrs_report($condition, true);	
		//debug($total_records);exit();
		$table_data = $this->activity_model->b2c_holidaycrs_report($condition, false, $offset, RECORDS_RANGE_2);
		//}

		
		// debug($table_data);exit();
		$table_data = $this->booking_data_formatter->format_activity_booking_data($table_data, 'b2b', false);
		//debug($table_data); exit;
		$emulate_data = $table_data['data']['booking_details'];
		// debug($emulate_data); exit;
		foreach($emulate_data as $data_em => $data_emulate) {

			if($data_emulate['emulate_booking'] == 1){
				$user_id = $data_emulate['emulate_user'];
				$userDetails = $this->activity_model->emulate_user($user_id);
				$table_data['data']['booking_details'][$data_em]['emulate_user_name'] = $userDetails['data']['emulate_details']['first_name']." ".$userDetails['data']['emulate_details']['last_name'];
			}else{
				$table_data['data']['booking_details'][$data_em]['emulate_user_name'] = "-";
			}
			if($data_emulate['agent_staff'] == 1){
				$user_id = $data_emulate['agent_staff_id'];
				$userDetails = $this->activity_model->emulate_user($user_id);
				$table_data['data']['booking_details'][$data_em]['agent_agency_name'] = $userDetails['data']['emulate_details']['agency_name'];
			}else{
				$table_data['data']['booking_details'][$data_em]['agent_agency_name'] =$data_emulate['agency_name'];
			}
			
		}


		$page_data['table_data'] = $table_data['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2c_activitiescrs_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');
		// debug($page_data['table_data']); exit;
		$this->template->view('report/b2c_report_activity', $page_data);
	}
function b2b_activitiescrs_report($offset=0)
	{
		// error_reporting(E_ALL);
		// echo '<h4>Under Working</h4>';
		$current_user_id = $GLOBALS['CI']->entity_user_id;
		$get_data = $this->input->get();
		// debug($get_data); exit();
		//debug($get_data); die;
		$condition = array();

		$filter_data = $this->format_basic_search_filters('activity');
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];
		
		//$condition[] = array('U.user_type', '=', B2C_USER, ' OR ', 'BD.created_by_id');

	//	$condition[] = array('BD.module_type', '=', '"activity"');


		// debug(RECORDS_RANGE_2);
		//if(isset($get_data['filter_report_data']) == true && empty($get_data['filter_report_data']) == false) {
			//debug($get_data['filter_report_data']);exit;
		//	$filter_report_data = trim($get_data['filter_report_data']);
//$search_filter_condition = '(BD.app_reference like "%'.$filter_report_data.'%" )';
			//debug($search_filter_condition);exit;
			//$total_records = $this->activity_model->b2c_holiday_report_filter($search_filter_condition, true);
			//$table_data = $this->activity_model->b2c_holiday_report_filter($search_filter_condition);
		//}
		//
		//{
			$total_records = $this->activity_model->b2c_holiday_report($condition, true);	
		//debug($total_records);exit();
		$table_data = $this->activity_model->b2c_holiday_report($condition, false, $offset, RECORDS_RANGE_2);
		//}

		
		// debug($table_data);exit();
		$table_data = $this->booking_data_formatter->format_activity_booking_data($table_data, 'b2b', false);
		//debug($table_data); exit;
		$emulate_data = $table_data['data']['booking_details'];
		// debug($emulate_data); exit;
		foreach($emulate_data as $data_em => $data_emulate) {

			if($data_emulate['emulate_booking'] == 1){
				$user_id = $data_emulate['emulate_user'];
				$userDetails = $this->activity_model->emulate_user($user_id);
				$table_data['data']['booking_details'][$data_em]['emulate_user_name'] = $userDetails['data']['emulate_details']['first_name']." ".$userDetails['data']['emulate_details']['last_name'];
			}else{
				$table_data['data']['booking_details'][$data_em]['emulate_user_name'] = "-";
			}
			if($data_emulate['agent_staff'] == 1){
				$user_id = $data_emulate['agent_staff_id'];
				$userDetails = $this->activity_model->emulate_user($user_id);
				$table_data['data']['booking_details'][$data_em]['agent_agency_name'] = $userDetails['data']['emulate_details']['agency_name'];
			}else{
				$table_data['data']['booking_details'][$data_em]['agent_agency_name'] =$data_emulate['agency_name'];
			}
			
		}


		$page_data['table_data'] = $table_data['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2b_activitiescrs_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');
		// debug($page_data['table_data']); exit;
		$this->template->view('report/b2b_report_activity', $page_data);
	}
	function monthly_booking_report()
	{
		$this->template->view('report/monthly_booking_report');
	}
	function b2c_transfers_report_crs($offset=0){
		$get_data = $this->input->get();
		// debug($get_data);exit;
		$this->load->model('transfers_model');
		$page_data = array();
		$condition = array();
		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		if(!empty($get_data)){
			$page_data['app_reference'] = $get_data['app_reference'];
		}
		$condition = $filter_data['filter_condition'];
		if(isset($get_data['filter_report_data']) == true && empty($get_data['filter_report_data']) == false) {
			$filter_report_data = trim($get_data['filter_report_data']);
			$search_filter_condition = '(BD.app_reference like "%'.$filter_report_data.'%")';
			$total_records = $this->transfers_model->filter_booking_reportcrs($search_filter_condition, true);
			$table_data = $this->transfers_model->filter_booking_reportcrs($search_filter_condition);
		} else {
	//debug($condition);die;
			$total_records = $this->transfers_model->bookingcrs($condition, true);
			// echo $this->db->last_query();exit;
			$table_data = $this->transfers_model->bookingcrs($condition, false, $offset, RECORDS_RANGE_2);
			// debug($table_data);exit;
		}
		$table_data = $this->booking_data_formatter->format_transferv1_booking_data($table_data, 'b2b');
		$emulate_data = $table_data['data']['booking_details'];
		foreach($emulate_data as $data_em => $data_emulate) {

			if($data_emulate['emulate_booking'] == 1){
				$user_id = $data_emulate['emulate_user'];
				$userDetails = $this->transfers_model->emulate_user($user_id);
				$table_data['data']['booking_details'][$data_em]['emulate_user_name'] = $userDetails['data']['emulate_details']['first_name']." ".$userDetails['data']['emulate_details']['last_name'];
			}else{
				$table_data['data']['booking_details'][$data_em]['emulate_user_name'] = "-";
			}
			if($data_emulate['agent_staff'] == 1){
				$user_id = $data_emulate['agent_staff_id'];
				$userDetails = $this->transfers_model->emulate_user($user_id);
				$table_data['data']['booking_details'][$data_em]['agent_agency_name'] = $userDetails['data']['emulate_details']['agency_name'];
			}else{
				$table_data['data']['booking_details'][$data_em]['agent_agency_name'] =$data_emulate['agency_name'];
			}
			
		}
		// debug($table_data);exit;
		$page_data['table_data'] = $table_data['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2b_transfers_report_crs/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		 //debug($page_data);exit;
		$this->template->view('report/b2c_transfer_crs', $page_data);
	}
function b2b_transfers_report_crs($offset=0){
	
		$get_data = $this->input->get();
		// debug($get_data);exit;
		$this->load->model('transfers_model');
		$page_data = array();
		$condition = array();
		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		if(!empty($get_data)){
			$page_data['app_reference'] = $get_data['app_reference'];
		}
		$condition = $filter_data['filter_condition'];
		if(isset($get_data['filter_report_data']) == true && empty($get_data['filter_report_data']) == false) {
			$filter_report_data = trim($get_data['filter_report_data']);
			$search_filter_condition = '(BD.app_reference like "%'.$filter_report_data.'%")';
			$total_records = $this->transfers_model->filter_booking_report($search_filter_condition, true);
			$table_data = $this->transfers_model->filter_booking_report($search_filter_condition);
		} else {
			$total_records = $this->transfers_model->booking($condition, true);
			// echo $this->db->last_query();exit;
			$table_data = $this->transfers_model->booking($condition, false, $offset, RECORDS_RANGE_2);
			// debug($table_data);exit;
		}
		$table_data = $this->booking_data_formatter->format_transferv1_booking_data($table_data, 'b2b');
		$emulate_data = $table_data['data']['booking_details'];
		foreach($emulate_data as $data_em => $data_emulate) {

			if($data_emulate['emulate_booking'] == 1){
				$user_id = $data_emulate['emulate_user'];
				$userDetails = $this->transfers_model->emulate_user($user_id);
				$table_data['data']['booking_details'][$data_em]['emulate_user_name'] = $userDetails['data']['emulate_details']['first_name']." ".$userDetails['data']['emulate_details']['last_name'];
			}else{
				$table_data['data']['booking_details'][$data_em]['emulate_user_name'] = "-";
			}
			if($data_emulate['agent_staff'] == 1){
				$user_id = $data_emulate['agent_staff_id'];
				$userDetails = $this->transfers_model->emulate_user($user_id);
				$table_data['data']['booking_details'][$data_em]['agent_agency_name'] = $userDetails['data']['emulate_details']['agency_name'];
			}else{
				$table_data['data']['booking_details'][$data_em]['agent_agency_name'] =$data_emulate['agency_name'];
			}
			
		}
		// debug($table_data);exit;
		$page_data['table_data'] = $table_data['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2b_transfers_report_crs/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		 //debug($page_data);exit;
		$this->template->view('report/b2b_transfer_crs', $page_data);
	}

function viewpaymentmode($app_reference)
   {
       $data ['payment_data'] = $this->transaction_model->get_payment_mode_app($app_reference)->result ();
	  $data['app_ref']=$app_reference;
	  $this->template->view ( 'report/paymode', $data );
   }
	function bus($offset=0)
	{
		$get_data = $this->input->get();
		$condition = array();
		$page_data = array();
		if(valid_array($get_data) == true) {
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
				$condition[] = array('BD.created_datetime', '>=', $this->db->escape(db_current_datetime($from_date)));
			}
			if(empty($to_date) == false) {
				$condition[] = array('BD.created_datetime', '<=', $this->db->escape(db_current_datetime($to_date)));
			}

			if (empty($get_data['created_by_id']) == false) {
				$condition[] = array('BD.created_by_id', '=', $this->db->escape($get_data['created_by_id']));
			}

			if (empty($get_data['status']) == false && strtolower($get_data['status']) != 'all') {
				$condition[] = array('BD.status', '=', $this->db->escape($get_data['status']));
			}

			if (empty($get_data['phone']) == false) {
				$condition[] = array('BD.phone_number', ' like ', $this->db->escape('%'.$get_data['phone'].'%'));
			}

			if (empty($get_data['email']) == false) {
				$condition[] = array('BD.email', ' like ', $this->db->escape('%'.$get_data['email'].'%'));
			}

			if (empty($get_data['app_reference']) == false) {
				$condition[] = array('BD.app_reference', ' like ', $this->db->escape('%'.$get_data['app_reference'].'%'));
			}
			$page_data['from_date'] = $from_date;
			$page_data['to_date'] = $to_date;
		}
		$total_records = $this->bus_model->booking($condition, true);
		$table_data = $this->bus_model->booking($condition, false, $offset, RECORDS_RANGE_1);
		$table_data = $this->booking_data_formatter->format_bus_booking_data($table_data,$this->current_module);
		$page_data['table_data'] = $table_data['data'];
		/** TABLE PAGINATION */
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/bus/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_1;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$page_data['customer_email'] = $this->entity_email;
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');
		$this->template->view('report/bus', $page_data);
	}
	function b2c_transfers_crs_report()
	{ 
		// echo "string";exit;
		// error_reporting(E_ALL);
		// echo '<h4>Under Working</h4>';
		$current_user_id = $GLOBALS['CI']->entity_user_id;
		$get_data = $this->input->get();
		// debug($get_data); exit();
		//debug($get_data); die;
		$condition = array();

		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];
		// $condition[] = array('U.user_type', '=', B2C_USER, ' OR ', 'BD.created_by_id');
		$condition[] = array('BD.module_type', '=', '"transfers"');

		// debug($condition);exit();
		// debug(RECORDS_RANGE_1);
		$total_records = $this->package_model->b2c_holiday_report($condition, true);		
		// debug($total_records);exit();
		$table_data = $this->package_model->b2c_holiday_report($condition, false, $offset, RECORDS_RANGE_1);
		// debug($table_data);exit();
		$table_data = $this->booking_data_formatter->format_flight_booking_data($table_data, 'b2c', false);
		// debug($table_data); exit;


		$page_data['table_data'] = $table_data['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2c_transfers_crs_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_1;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');
		$page_data['module_type']="Transfer";
		// debug($page_data['table_data']); exit;
		$this->template->view('report/b2c_report_package', $page_data);
	}
	function b2b_transfers_crs_report()
	{ 
		// echo "string";exit;
		// error_reporting(E_ALL);
		// echo '<h4>Under Working</h4>';
		$current_user_id = $GLOBALS['CI']->entity_user_id;
		$get_data = $this->input->get();
		// debug($get_data); exit();
		//debug($get_data); die;
		$condition = array();

		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];
		// $condition[] = array('U.user_type', '=', B2C_USER, ' OR ', 'BD.created_by_id');
		$condition[] = array('BD.module_type', '=', '"transfers"');

		// debug($condition);exit();
		// debug(RECORDS_RANGE_1);
		$total_records = $this->package_model->b2b_holiday_report($condition, true);		
		// debug($total_records);exit();
		$table_data = $this->package_model->b2b_holiday_report($condition, false, $offset, RECORDS_RANGE_1);
		// debug($table_data);exit();
		$table_data = $this->booking_data_formatter->format_flight_booking_data($table_data, 'b2c', false);
		// debug($table_data); exit;


		$page_data['table_data'] = $table_data['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2c_transfers_crs_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_1;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');
		$page_data['module_type']="Transfer";
		// debug($page_data['table_data']); exit;
		$this->template->view('report/b2b_report_package', $page_data);
	}

	function hotel($offset=0)
	{
		$condition = array();
		$get_data = $this->input->get();
		if(valid_array($get_data) == true) {
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
				$condition[] = array('BD.created_datetime', '>=', $this->db->escape(db_current_datetime($from_date)));
			}
			if(empty($to_date) == false) {
				$condition[] = array('BD.created_datetime', '<=', $this->db->escape(db_current_datetime($to_date)));
			}

			if (empty($get_data['created_by_id']) == false) {
				$condition[] = array('BD.created_by_id', '=', $this->db->escape($get_data['created_by_id']));
			}

			if (empty($get_data['status']) == false && strtolower($get_data['status']) != 'all') {
				$condition[] = array('BD.status', '=', $this->db->escape($get_data['status']));
			}

			if (empty($get_data['phone']) == false) {
				$condition[] = array('BD.phone_number', ' like ', $this->db->escape('%'.$get_data['phone'].'%'));
			}

			if (empty($get_data['email']) == false) {
				$condition[] = array('BD.email', ' like ', $this->db->escape('%'.$get_data['email'].'%'));
			}

			if (empty($get_data['app_reference']) == false) {
				$condition[] = array('BD.app_reference', ' like ', $this->db->escape('%'.$get_data['app_reference'].'%'));
			}
			$page_data['from_date'] = $from_date;
			$page_data['to_date'] = $to_date;
		}
		$total_records = $this->hotel_model->booking($condition, true);
		$table_data = $this->hotel_model->booking($condition, false, $offset, RECORDS_RANGE_1);
		$table_data = $this->booking_data_formatter->format_hotel_booking_data($table_data,$this->current_module);
		$page_data['table_data'] = $table_data['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/hotel/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_1;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		//debug($page_data);exit;
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');
		$this->template->view('report/hotel', $page_data);
	}

	
	
	
	function b2c_bus_report($offset=0)
	{
		$get_data = $this->input->get();
		$condition = array();
		$page_data = array();

		$filter_data = $this->format_basic_search_filters('bus');
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];

		//debug($get_data); die;
		/*if(valid_array($get_data) == true) {
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
				$condition[] = array('BD.created_datetime', '>=', $this->db->escape(db_current_datetime($from_date)));
			}
			if(empty($to_date) == false) {
				$condition[] = array('BD.created_datetime', '<=', $this->db->escape(db_current_datetime($to_date)));
			}
	
			if (empty($get_data['created_by_id']) == false) {
				$condition[] = array('BD.created_by_id', '=', $this->db->escape($get_data['created_by_id']));
			}
	
			if (empty($get_data['status']) == false && strtolower($get_data['status']) != 'all') {
				$condition[] = array('BD.status', '=', $this->db->escape($get_data['status']));
			}
	
			// if (empty($get_data['phone']) == false) {
			// 	$condition[] = array('BD.phone_number', ' like ', $this->db->escape('%'.$get_data['phone'].'%'));
			// }
	
			// if (empty($get_data['email']) == false) {
			// 	$condition[] = array('BD.email', ' like ', $this->db->escape('%'.$get_data['email'].'%'));
			// }
	
			if (empty($get_data['app_reference']) == false) {
				$condition[] = array('BD.app_reference', ' like ', $this->db->escape('%'.$get_data['app_reference'].'%'));
			}
			if (empty($get_data['pnr']) == false) {
				$condition[] = array('BD.pnr', ' like ', $this->db->escape('%'.$get_data['pnr'].'%'));
			}
			$page_data['from_date'] = $from_date;
			$page_data['to_date'] = $to_date;
		}*/
	
		$total_records = $this->bus_model->b2c_bus_report($condition, true);
		$table_data = $this->bus_model->b2c_bus_report($condition, false, $offset, RECORDS_RANGE_1);
		// debug($table_data); exit;
		$table_data = $this->booking_data_formatter->format_bus_booking_data($table_data,$this->current_module);
		
		$page_data['table_data'] = $table_data['data'];

		// debug($table_data); exit;

		/** TABLE PAGINATION */
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2c_bus_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_1;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$page_data['customer_email'] = $this->entity_email;
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');
		//debug($page_data); die;
		$this->template->view('report/b2c_report_bus', $page_data);
	}
	
	
	function b2b_bus_report($offset=0)
	{
		$get_data = $this->input->get();
		$condition = array();
		$page_data = array();

		$filter_data = $this->format_basic_search_filters('bus');
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];

		//debug($condition); die;
		$total_records = $this->bus_model->b2b_bus_report($condition, true);
		$table_data = $this->bus_model->b2b_bus_report($condition, false, $offset, RECORDS_RANGE_1);
		$table_data = $this->booking_data_formatter->format_bus_booking_data($table_data,'b2b');
		$page_data['table_data'] = $table_data['data'];
		/** TABLE PAGINATION */
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2b_bus_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_1;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$page_data['customer_email'] = $this->entity_email;
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');

		$agent_info = $this->custom_db->single_table_records('user','*',array('user_type'=>B2B_USER,'domain_list_fk'=>get_domain_auth_id()));
		
		$page_data['agent_details'] = magical_converter(array('k' => 'user_id', 'v' => 'agency_name'), $agent_info);

		$this->template->view('report/b2b_report_bus', $page_data);
	}
	
	
	
	function b2b_hotel_report($offset=0)
	{
		$condition = array();
		$get_data = $this->input->get();

		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];
		
		$total_records = $this->hotel_model->b2b_hotel_report($condition, true);
		$table_data = $this->hotel_model->b2b_hotel_report($condition, false, $offset, RECORDS_RANGE_1);
		$table_data = $this->booking_data_formatter->format_hotel_booking_data($table_data, $this->current_module);
		$page_data['table_data'] = $table_data['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2b_hotel_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_1;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		//debug($page_data);exit;
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');
		
		$agent_info = $this->custom_db->single_table_records('user','*',array('user_type'=>B2B_USER,'domain_list_fk'=>get_domain_auth_id()));
		
		$page_data['agent_details'] = magical_converter(array('k' => 'user_id', 'v' => 'agency_name'), $agent_info);
		$this->template->view('report/b2b_report_hotel', $page_data);
	}
		function b2b_hotelcrs_report($offset=0)
	{
	  
		$condition = array();
		$get_data = $this->input->get();

		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];
		
		$total_records = $this->hotel_model->b2b_hotelcrs_report($condition, true);
		$table_data = $this->hotel_model->b2b_hotelcrs_report($condition, false, $offset, RECORDS_RANGE_1);
		$table_data = $this->booking_data_formatter->format_hotel_booking_data($table_data, $this->current_module);
		$page_data['table_data'] = $table_data['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2b_hotel_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_1;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		//debug($page_data);exit;
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');
		
		$agent_info = $this->custom_db->single_table_records('user','*',array('user_type'=>B2B_USER,'domain_list_fk'=>get_domain_auth_id()));
		
		$page_data['agent_details'] = magical_converter(array('k' => 'user_id', 'v' => 'agency_name'), $agent_info);
		$page_data["supplier_list"] = $this->domain_management_model->get_all_suplliers(META_ACCOMODATION_COURSE);
		$this->template->view('report/b2b_report_hotel', $page_data);
	}
	
		function b2c_hotelcrs_report($offset=0)
	{
		
		$condition = array();
		$get_data = $this->input->get();

		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];

		/*if(valid_array($get_data) == true) {
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
				$condition[] = array('BD.created_datetime', '>=', $this->db->escape(db_current_datetime($from_date)));
			}
			if(empty($to_date) == false) {
				$condition[] = array('BD.created_datetime', '<=', $this->db->escape(db_current_datetime($to_date)));
			}
	
			if (empty($get_data['created_by_id']) == false) {
				$condition[] = array('BD.created_by_id', '=', $this->db->escape($get_data['created_by_id']));
			}
	
			if (empty($get_data['status']) == false && strtolower($get_data['status']) != 'all') {
				$condition[] = array('BD.status', '=', $this->db->escape($get_data['status']));
			}
	
			// if (empty($get_data['phone']) == false) {
			// 	$condition[] = array('BD.phone_number', ' like ', $this->db->escape('%'.$get_data['phone'].'%'));
			// }
	
			// if (empty($get_data['email']) == false) {
			// 	$condition[] = array('BD.email', ' like ', $this->db->escape('%'.$get_data['email'].'%'));
			// }
	
			if (empty($get_data['app_reference']) == false) {
				$condition[] = array('BD.app_reference', 'like',$this->db->escape('%'.$get_data['app_reference'].'%'));
			}
			$page_data['from_date'] = $from_date;
			$page_data['to_date'] = $to_date;
		}*/
		//debug($this->session->userdata('id'));die;
		$total_records = $this->hotel_model->b2c_hotelcrs_report($condition, true);	
	//	debug($total_records); die;
		$table_data = $this->hotel_model->b2c_hotelcrs_report($condition, false, $offset, RECORDS_RANGE_1);
			//debug($table_data['data']); exit;
		$table_data = $this->booking_data_formatter->format_hotel_booking_data($table_data,$this->current_module);
		$page_data['table_data'] = $table_data['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2c_hotel_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_1;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		//debug($page_data);exit;
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');
		$this->template->view('report/b2c_report_hotel', $page_data);
	}
	
	function b2c_hotel_report($offset=0)
	{
		$condition = array();
		$get_data = $this->input->get();

		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];

		/*if(valid_array($get_data) == true) {
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
				$condition[] = array('BD.created_datetime', '>=', $this->db->escape(db_current_datetime($from_date)));
			}
			if(empty($to_date) == false) {
				$condition[] = array('BD.created_datetime', '<=', $this->db->escape(db_current_datetime($to_date)));
			}
	
			if (empty($get_data['created_by_id']) == false) {
				$condition[] = array('BD.created_by_id', '=', $this->db->escape($get_data['created_by_id']));
			}
	
			if (empty($get_data['status']) == false && strtolower($get_data['status']) != 'all') {
				$condition[] = array('BD.status', '=', $this->db->escape($get_data['status']));
			}
	
			// if (empty($get_data['phone']) == false) {
			// 	$condition[] = array('BD.phone_number', ' like ', $this->db->escape('%'.$get_data['phone'].'%'));
			// }
	
			// if (empty($get_data['email']) == false) {
			// 	$condition[] = array('BD.email', ' like ', $this->db->escape('%'.$get_data['email'].'%'));
			// }
	
			if (empty($get_data['app_reference']) == false) {
				$condition[] = array('BD.app_reference', 'like',$this->db->escape('%'.$get_data['app_reference'].'%'));
			}
			$page_data['from_date'] = $from_date;
			$page_data['to_date'] = $to_date;
		}*/
		//debug($this->session->userdata('id'));die;
		$total_records = $this->hotel_model->b2c_hotel_report($condition, true);	
		//	debug($total_records); die;
		$table_data = $this->hotel_model->b2c_hotel_report($condition, false, $offset, RECORDS_RANGE_1);
			//debug($table_data['data']); exit;
		$table_data = $this->booking_data_formatter->format_hotel_booking_data($table_data,$this->current_module);
		$page_data['table_data'] = $table_data['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2c_hotel_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_1;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		//debug($page_data);exit;
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');
		$this->template->view('report/b2c_report_hotel', $page_data);
	}
	/*B2c sightseeing Report*/
	function b2c_activities_report($offset=0)
	{
		$condition = array();
		$get_data = $this->input->get();

		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];

		$total_records = $this->sightseeing_model->b2c_sightseeing_report($condition, true);	
		
		//	debug($total_records); die;
		$table_data = $this->sightseeing_model->b2c_sightseeing_report($condition, false, $offset, RECORDS_RANGE_1);
			//debug($table_data['data']); exit;
		$table_data = $this->booking_data_formatter->format_sightseeing_booking_data($table_data,$this->current_module);
		$page_data['table_data'] = $table_data['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2c_sightseeing_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_1;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		//debug($page_data);exit;
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');
		$this->template->view('report/b2c_report_sightseeing', $page_data);
	}
		/**
	 * Sightseeing Report for b2b flight
	 * @param $offset
	 */
	function b2b_activities_report($offset=0)
	{
		$current_user_id = $GLOBALS['CI']->entity_user_id;
		$get_data = $this->input->get();
		$condition = array();
		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];

		$total_records = $this->sightseeing_model->b2b_sightseeing_report($condition, true);
		//echo '<pre>'; print_r($page_data); die;
		$table_data = $this->sightseeing_model->b2b_sightseeing_report($condition, false, $offset, RECORDS_RANGE_1);
		$table_data = $this->booking_data_formatter->format_sightseeing_booking_data($table_data, $this->current_module);
		// debug($table_data);
		// exit;
		$page_data['table_data'] = $table_data['data'];
		
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2b_sightseeing_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_1;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');

		$user_cond = [];
		$user_cond [] = array('U.user_type','=',' (', B2B_USER, ')');
		$user_cond [] = array('U.domain_list_fk' , '=' ,get_domain_auth_id());

		//$agent_info['data'] = $this->user_model->b2b_user_list($user_cond,false);

		$agent_info = $this->custom_db->single_table_records('user','*',array('user_type'=>B2B_USER,'domain_list_fk'=>get_domain_auth_id()));

		$page_data['agent_details'] = magical_converter(array('k' => 'user_id', 'v' => 'agency_name'), $agent_info);		
		
		$this->template->view('report/b2b_sightseeing', $page_data);
	}
	/*B2B Transfer Report*/
	function b2b_transfers_report($offset=0){
		$current_user_id = $GLOBALS['CI']->entity_user_id;
		$get_data = $this->input->get();
		$condition = array();
		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];

		$total_records = $this->transferv1_model->b2b_transferv1_report($condition, true);
		//echo '<pre>'; print_r($page_data); die;
		$table_data = $this->transferv1_model->b2b_transferv1_report($condition, false, $offset, RECORDS_RANGE_1);
		$table_data = $this->booking_data_formatter->format_transferv1_booking_data($table_data, $this->current_module);
		// debug($table_data);
		// exit;
		$page_data['table_data'] = $table_data['data'];
		
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2b_transfers_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_1;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');

		$user_cond = [];
		$user_cond [] = array('U.user_type','=',' (', B2B_USER, ')');
		$user_cond [] = array('U.domain_list_fk' , '=' ,get_domain_auth_id());

		//$agent_info['data'] = $this->user_model->b2b_user_list($user_cond,false);

		$agent_info = $this->custom_db->single_table_records('user','*',array('user_type'=>B2B_USER,'domain_list_fk'=>get_domain_auth_id()));

		$page_data['agent_details'] = magical_converter(array('k' => 'user_id', 'v' => 'agency_name'), $agent_info);		
		
		$this->template->view('report/b2b_transfer', $page_data);
	}
	/*B2c Transfer Report*/
	function b2c_transfers_report($offset=0)
	{
		$condition = array();
		$get_data = $this->input->get();

		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];

		$total_records = $this->transferv1_model->b2c_transferv1_report($condition, true);	
		
		//	debug($total_records); die;
		$table_data = $this->transferv1_model->b2c_transferv1_report($condition, false, $offset, RECORDS_RANGE_1);
			//debug($table_data['data']); exit;
		$table_data = $this->booking_data_formatter->format_transferv1_booking_data($table_data,$this->current_module);
		$page_data['table_data'] = $table_data['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2c_transfers_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_1;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		//debug($page_data);exit;
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');
		$this->template->view('report/b2c_transferv1_report', $page_data);
	}
	/* car reports */
	
	function b2c_car_report($offset=0)
	{
		$get_data = $this->input->get();
        $condition = array();
        $page_data = array();
        $filter_data = $this->format_basic_search_filters('bus');
        $page_data['from_date'] = $filter_data['from_date'];
        $page_data['to_date'] = $filter_data['to_date'];
        $condition = $filter_data['filter_condition'];

        $total_records = $this->car_model->b2c_car_report($condition, true);
   
        $table_data = $this->car_model->b2c_car_report($condition, false, $offset, RECORDS_RANGE_1);
       
        $table_data = $this->booking_data_formatter->format_car_booking_datas($table_data , $this->current_module);
       	// debug($table_data);exit;
       	$page_data['table_data'] = $table_data['data'];
        
        /** TABLE PAGINATION */
        $this->load->library('pagination');
        if (count($_GET) > 0)
            $config['suffix'] = '?' . http_build_query($_GET, '', "&");
        $config['base_url'] = base_url() . 'index.php/report/car/';
        $config['first_url'] = $config['base_url'] . '?' . http_build_query($_GET);
        $page_data['total_rows'] = $config['total_rows'] = $total_records;
        $config['per_page'] = RECORDS_RANGE_1;
        $this->pagination->initialize($config);
        /** TABLE PAGINATION */
        $page_data['total_records'] = $config['total_rows'];
        $page_data['customer_email'] = $this->entity_email;
       $page_data['search_params'] = $get_data;
        $page_data['status_options'] = get_enum_list('booking_status_options');
        $this->template->view('report/b2c_car_report', $page_data);
        

	}
		function b2c_carcrs_report($offset=0)
	{
		$get_data = $this->input->get();
        $condition = array();
        $page_data = array();
        $filter_data = $this->format_basic_search_filters('bus');
        $page_data['from_date'] = $filter_data['from_date'];
        $page_data['to_date'] = $filter_data['to_date'];
        $condition = $filter_data['filter_condition'];

        $total_records = $this->car_model->b2c_carcrs_report($condition, true);
   
        $table_data = $this->car_model->b2c_carcrs_report($condition, false, $offset, RECORDS_RANGE_1);
       
        $table_data = $this->booking_data_formatter->format_car_booking_datas($table_data , $this->current_module);
       	// debug($table_data);exit;
       	$page_data['table_data'] = $table_data['data'];
        
        /** TABLE PAGINATION */
        $this->load->library('pagination');
        if (count($_GET) > 0)
            $config['suffix'] = '?' . http_build_query($_GET, '', "&");
        $config['base_url'] = base_url() . 'index.php/report/car/';
        $config['first_url'] = $config['base_url'] . '?' . http_build_query($_GET);
        $page_data['total_rows'] = $config['total_rows'] = $total_records;
        $config['per_page'] = RECORDS_RANGE_1;
        $this->pagination->initialize($config);
        /** TABLE PAGINATION */
        $page_data['total_records'] = $config['total_rows'];
        $page_data['customer_email'] = $this->entity_email;
       $page_data['search_params'] = $get_data;
        $page_data['status_options'] = get_enum_list('booking_status_options');
        $this->template->view('report/b2c_car_report', $page_data);
        

	}
	/* car reports  for B2B*/
	
	function b2b_car_report($offset=0)
	{
		$get_data = $this->input->get();
        $condition = array();
        $page_data = array();
        $filter_data = $this->format_basic_search_filters('bus');
        $page_data['from_date'] = $filter_data['from_date'];
        $page_data['to_date'] = $filter_data['to_date'];
        $condition = $filter_data['filter_condition'];

        $total_records = $this->car_model->b2b_car_report($condition, true);
   
        $table_data = $this->car_model->b2b_car_report($condition, false, $offset, RECORDS_RANGE_1);
       	// echo $this->current_module;exit;
        $table_data = $this->booking_data_formatter->format_car_booking_datas($table_data , $this->current_module);
       	// debug($table_data);exit;
       	$page_data['table_data'] = $table_data['data'];
        
        /** TABLE PAGINATION */
        $this->load->library('pagination');
        if (count($_GET) > 0)
            $config['suffix'] = '?' . http_build_query($_GET, '', "&");
        $config['base_url'] = base_url() . 'index.php/report/car/';
        $config['first_url'] = $config['base_url'] . '?' . http_build_query($_GET);
        $page_data['total_rows'] = $config['total_rows'] = $total_records;
        $config['per_page'] = RECORDS_RANGE_1;
        $this->pagination->initialize($config);
        /** TABLE PAGINATION */
        $page_data['total_records'] = $config['total_rows'];
        $page_data['customer_email'] = $this->entity_email;
       $page_data['search_params'] = $get_data;
        $page_data['status_options'] = get_enum_list('booking_status_options');
        $this->template->view('report/b2b_car_report', $page_data);
        

	}
	function b2b_carcrs_report($offset=0)
	{
		$get_data = $this->input->get();
        $condition = array();
        $page_data = array();
        $filter_data = $this->format_basic_search_filters('bus');
        $page_data['from_date'] = $filter_data['from_date'];
        $page_data['to_date'] = $filter_data['to_date'];
        $condition = $filter_data['filter_condition'];

        $total_records = $this->car_model->b2b_carcrs_report($condition, true);
   
        $table_data = $this->car_model->b2b_carcrs_report($condition, false, $offset, RECORDS_RANGE_1);
       	// echo $this->current_module;exit;
        $table_data = $this->booking_data_formatter->format_car_booking_datas($table_data , $this->current_module);
       	// debug($table_data);exit;
       	$page_data['table_data'] = $table_data['data'];
        
        /** TABLE PAGINATION */
        $this->load->library('pagination');
        if (count($_GET) > 0)
            $config['suffix'] = '?' . http_build_query($_GET, '', "&");
        $config['base_url'] = base_url() . 'index.php/report/car/';
        $config['first_url'] = $config['base_url'] . '?' . http_build_query($_GET);
        $page_data['total_rows'] = $config['total_rows'] = $total_records;
        $config['per_page'] = RECORDS_RANGE_1;
        $this->pagination->initialize($config);
        /** TABLE PAGINATION */
        $page_data['total_records'] = $config['total_rows'];
        $page_data['customer_email'] = $this->entity_email;
       $page_data['search_params'] = $get_data;
        $page_data['status_options'] = get_enum_list('booking_status_options');
        $this->template->view('report/b2b_car_report', $page_data);
        

	}
	/**
	 * Flight Report
	 * @param $offset
	 */
	function flight($offset=0)
	{
		$current_user_id = $GLOBALS['CI']->entity_user_id;
		$get_data = $this->input->get();
		$condition = array();
		if(valid_array($get_data) == true) {
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
				$condition[] = array('BD.created_datetime', '>=', $this->db->escape(db_current_datetime($from_date)));
			}
			if(empty($to_date) == false) {
				$condition[] = array('BD.created_datetime', '<=', $this->db->escape(db_current_datetime($to_date)));
			}

			if (empty($get_data['created_by_id']) == false) {
				$condition[] = array('BD.created_by_id', '=', $this->db->escape($get_data['created_by_id']));
			}

			if (empty($get_data['status']) == false && strtolower($get_data['status']) != 'all') {
				$condition[] = array('BD.status', '=', $this->db->escape($get_data['status']));
			}

			if (empty($get_data['phone']) == false) {
				$condition[] = array('BD.phone', ' like ', $this->db->escape('%'.$get_data['phone'].'%'));
			}

			if (empty($get_data['email']) == false) {
				$condition[] = array('BD.email', ' like ', $this->db->escape('%'.$get_data['email'].'%'));
			}

			if (empty($get_data['app_reference']) == false) {
				$condition[] = array('BD.app_reference', ' like ', $this->db->escape('%'.$get_data['app_reference'].'%'));
			}
			$page_data['from_date'] = $from_date;
			$page_data['to_date'] = $to_date;
		}
		$total_records = $this->flight_model->booking($condition, true);
		$table_data = $this->flight_model->booking($condition, false, $offset, RECORDS_RANGE_1);
		$table_data = $this->booking_data_formatter->format_flight_booking_data($table_data,$this->current_module);
		$page_data['table_data'] = $table_data['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/flight/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_1;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');
		$this->template->view('report/airline', $page_data);
	}
    
    function modify_remarks_old()
    {
        $post_data = $this->input->post();
        $fin_remarks = $post_data['fin_remarks'];
        $oth_remarks = $post_data['oth_remarks'];
        $report_origin = $post_data['report_origin'];
        $page_no = $post_data['page_no'];
        $module = $post_data['module'];
        $report_org = $this->custom_db->single_table_records('flight_booking_details', '*', array('origin' => $report_origin));
        $attributes = json_decode($report_org['data'][0]['attributes']);
        if ($fin_remarks != '') {
            $attributes->fin_remarks = $fin_remarks;
        }
        if ($oth_remarks != '') {
            $attributes->oth_remarks = $oth_remarks;
        }
        $attributes->remarks_updated = date('Y-m-d H:i:s');
        $attributes = json_encode($attributes);
        $query = 'UPDATE `flight_booking_details` SET `attributes` = ' . "'" . $attributes . "'" . ' WHERE `origin` = ' . $report_origin;
        $this->db->query($query);
        if ($module == 'b2c') {
            redirect(base_url() . 'index.php/report/b2c_flight_report/' . $page_no);
        } else {
            redirect(base_url() . 'index.php/report/b2b_flight_report/' . $page_no);
        }
    }
	/**
	 * Flight Report for b2c flight
	 * @param $offset
	 */
	function b2c_flight_report($offset=0)
	{
		$current_user_id = $GLOBALS['CI']->entity_user_id;
		$get_data = $this->input->get();
		//debug($get_data); die;
		$condition = array();

		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];
		
		//$condition[] = array('U.user_type', '=', B2C_USER, ' OR ', 'BD.created_by_id');
		$total_records = $this->flight_model->b2c_flight_report($condition, true);		
		
		$table_data = $this->flight_model->b2c_flight_report($condition, false, $offset, RECORDS_RANGE_1);
		
		$table_data = $this->booking_data_formatter->format_flight_booking_data($table_data, 'b2c', false);
		
		//Export report


		$page_data['table_data'] = $table_data['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2c_flight_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_1;
		$this->pagination->initialize($config);
                
               
                
                
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');
		/*debug($page_data);
		exit;	*/
		$this->template->view('report/b2c_report_airline', $page_data);
	}
	
function b2c_flight_crs_report($offset=0)
	{

		$current_user_id = $GLOBALS['CI']->entity_user_id;
		$get_data = $this->input->get();
		//debug($get_data); die;
		$condition = array();

		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];
		
		//$condition[] = array('U.user_type', '=', B2C_USER, ' OR ', 'BD.created_by_id');
		$total_records = $this->flight_crs_report_model->b2c_flight_report($condition, true);		
		
		$table_data = $this->flight_crs_report_model->b2c_flight_report($condition, false, $offset, RECORDS_RANGE_1);

		$table_data = $this->booking_data_formatter->format_flight_booking_data($table_data, 'b2c', false);
		
		//Export report


		$page_data['table_data'] = $table_data['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2c_flight_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_1;
		$this->pagination->initialize($config);
                
               
                
                
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');
		
		$this->template->view('report/crs_b2c_report_airline', $page_data);
	}
	
	/**
	 * Flight Report for b2b flight
	 * @param $offset
	 */
	function b2b_flight_report($offset=0)
	{
		$current_user_id = $GLOBALS['CI']->entity_user_id;
		$get_data = $this->input->get();
		$condition = array();
		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];

		$total_records = $this->flight_model->b2b_flight_report($condition, true);
		//echo '<pre>'; print_r($page_data); die;
		$table_data = $this->flight_model->b2b_flight_report($condition, false, $offset, RECORDS_RANGE_1);
		$table_data = $this->booking_data_formatter->format_flight_booking_data($table_data, $this->current_module);
		$page_data['table_data'] = $table_data['data'];
		
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2b_flight_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_1;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');

		$user_cond = [];
		$user_cond [] = array('U.user_type','=',' (', B2B_USER, ')');
		$user_cond [] = array('U.domain_list_fk' , '=' ,get_domain_auth_id());

		//$agent_info['data'] = $this->user_model->b2b_user_list($user_cond,false);

		$agent_info = $this->custom_db->single_table_records('user','*',array('user_type'=>B2B_USER,'domain_list_fk'=>get_domain_auth_id()));

		$page_data['agent_details'] = magical_converter(array('k' => 'user_id', 'v' => 'agency_name'), $agent_info);		
		
		$this->template->view('report/b2b_report_airline', $page_data);
	}
	
	
function b2b_flight_crs_report($offset=0)
	{
		$current_user_id = $GLOBALS['CI']->entity_user_id;
		$get_data = $this->input->get();
		$condition = array();
		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];

		$total_records = $this->flight_crs_report_model->b2b_flight_report($condition, true);
		//echo '<pre>'; print_r($page_data); die;
		$table_data = $this->flight_crs_report_model->b2b_flight_report($condition, false, $offset, RECORDS_RANGE_1);
		$table_data = $this->booking_data_formatter->format_flight_booking_data($table_data, $this->current_module);
		$page_data['table_data'] = $table_data['data'];
		
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2b_flight_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_1;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');

		$user_cond = [];
		$user_cond [] = array('U.user_type','=',' (', B2B_USER, ')');
		$user_cond [] = array('U.domain_list_fk' , '=' ,get_domain_auth_id());

		//$agent_info['data'] = $this->user_model->b2b_user_list($user_cond,false);

		$agent_info = $this->custom_db->single_table_records('user','*',array('user_type'=>B2B_USER,'domain_list_fk'=>get_domain_auth_id()));

		$page_data['agent_details'] = magical_converter(array('k' => 'user_id', 'v' => 'agency_name'), $agent_info);		
		
		$this->template->view('report/crs_b2b_report_airline', $page_data);
	}

	function update_flight_booking_details($app_reference, $booking_source)
	{
		load_flight_lib($booking_source);
		$this->flight_lib->update_flight_booking_details($app_reference);
		//FIXME: Return the status
	}
	
	/**
	 * Sagar Wakchaure
	 *Update pnr Details 
	 * @param unknown $app_reference
	 * @param unknown $booking_source
	 * @param unknown $booking_status
	 */
	function update_pnr_details($app_reference, $booking_source,$booking_status)
	{
              
		load_flight_lib($booking_source);
		$response = $this->flight_lib->update_pnr_details($app_reference);
              
		$get_pnr_updated_status = $this->flight_model->update_pnr_details($response,$app_reference, $booking_source,$booking_status);
		echo $get_pnr_updated_status;
	}
	
	function package()
	{
		echo '<h4>Under Working</h4>';
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
	# Get Pending Refund
    function cancellation_queue($offset = 0) {
    	error_reporting(0);
    	$this->load->model('flight_model');
        $get_data = $this->input->get();
        $condition = array();
        $cancel_data=array();
        $CancelQueue=array();
        $status="BOOKING_CANCELLED";
        $from_date ="2017-12-01";
        $to_date = date("Y-m-d");
        if (empty($from_date) == false) {
            $filter_condition[] = array('DATE(BD.created_datetime)', '>=', $this->db->escape(db_current_datetime($from_date)));
        }
        if (empty($to_date) == false) {
            $filter_condition[] = array('DATE(BD.created_datetime)', '<=', $this->db->escape(db_current_datetime($to_date)));
        }
        $filter_data=  array('filter_condition' => $filter_condition);
       	$condition = $filter_data['filter_condition'];
        
        $page_data['table_data'] = $this->flight_model->booking_cancel($condition, false, $offset, 5000);
        // debug($page_data['table_data']);exit;
        $cancellation_details = $this->booking_data_formatter->format_flight_booking_data($page_data['table_data'], $this->current_module);
      	// debug($cancellation_details);exit;
        $transaction_Details=array();
        $Appreference=array();
        foreach($cancellation_details['data']['booking_details_app'] as $key=>$value)
        {  
            foreach($value['booking_transaction_details'] as $k=>$val) {
             
                foreach($val['booking_customer_details'] as $j=>$data)
                {

                	
                	if(isset($data['cancellation_details'])){
                	
                		if($data['cancellation_details']['refund_status']=="INPROGRESS")
                  		{
                        	$Appreference[]=$data['app_reference'];
                  		}
                	}
                
                }
             }
          
        } 
       $result = array_unique($Appreference);
      
       foreach($result as $finalkey=>$final_data)
       {
          $CancelQueue[]= $cancellation_details['data']['booking_details_app'][$final_data];
       }
       // debug($CancelQueue);exit;
       $cancel_data['CancelQueue']=$CancelQueue;
       $this->template->view('report/cancellation_queue', $cancel_data);
    }
	public function get_customer_details($app_reference,$booking_source,$booking_status, $module)
	{

        if($module == 'flight'){
        	$booking_details = $this->flight_model->get_booking_details($app_reference, $booking_source, $booking_status);
		}
		else if($module == 'hotel'){
			$booking_details = $this->hotel_model->get_booking_details($app_reference, $booking_source, $booking_status);
	
		}
		else if($module == 'bus'){
			$booking_details = $this->bus_model->get_booking_details($app_reference, $booking_source, $booking_status);
	
		}
		// debug($booking_details);exit;
		$booking_details['module'] = $module;
       
            if($booking_details['status'] == SUCCESS_STATUS && valid_array($booking_details['data']) ==true){
				$response['data'] = get_compressed_output(
				$this->template->isolated_view('report/customer_details',
						array('customer_details' => $booking_details,)));
			}

        $this->output_compressed_data($response); 
        
	}
	private function output_compressed_data($data)
	{	
	
        ini_set('always_populate_raw_post_data', '-1');
            
	   	while (ob_get_level() > 0) { ob_end_clean() ; }
	   	ob_start("ob_gzhandler");
	   	ini_set("memory_limit", "-1");set_time_limit(0);
	   	header('Content-type:application/json');
	   
		echo json_encode($data);
	    ob_end_flush();
	   	exit;
	}
	 /*
     * For Confirmed Booking
     * Export AirlineReport details to Excel Format or PDF
     */


    public function export_confirmed_booking_airline_report($op = '') {
        $this->load->model('flight_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('flight');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CONFIRMED'));

        $flight_booking_data = $this->flight_model->b2c_flight_report($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $flight_booking_data = $this->booking_data_formatter->format_flight_booking_data($flight_booking_data, $this->current_module);
        $flight_booking_data = $flight_booking_data['data']['booking_details'];



        $export_data = array();
        // debug($flight_booking_data);exit;
        $i=1;
        foreach ($flight_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
            $export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['gds_pnr'] = $v['pnr'];
            $export_data[$k]['airline_pnr'] = $v['booking_itinerary_details'][0]['airline_pnr'];
            $export_data[$k]['airline_code'] = $v['booking_itinerary_details'][0]['airline_code'];
            $export_data[$k]['journey_from'] = $v['journey_from'];
           	$export_data[$k]['journey_to'] = $v['journey_to'];
           	$export_data[$k]['journey_start'] = $v['journey_start'];
           	$export_data[$k]['journey_end'] = $v['journey_end'];
           	$export_data[$k]['commission_fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['net_commission'];
           	$export_data[$k]['tds'] = $v['net_commission_tds'];
           	$export_data[$k]['net_fare'] = $v['net_fare'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['discount'] = $v['discount'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	$export_data[$k]['booked_date'] = date('d-m-Y', strtotime($v['booked_date']));
           	
        }

        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'Appreference',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'GDS PNR',
                    'g1' => 'Airline PNR',
                    'h1' => 'Airline Code',
                    'i1' => 'From',
                    'j1' => 'To',
                    'k1' => 'Form Date',
                    'l1' => 'To Date',
                    'm1' => 'Commission Fare',
					'n1' => 'Commission',
                    'o1' => 'TDS',
                    'p1' => 'Net Fare',
                    'q1' => 'GST',
                    'r1' => 'Convinence Amount',
                    's1' => 'Discount',
                    't1' => 'Customer Paid',
                    'u1' => 'Booked Date',
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'gds_pnr',
                    'g' => 'airline_pnr',
                    'h' => 'airline_code',
                    'i' => 'journey_from',
                    'j' => 'journey_to',
                    'k' => 'journey_start',
                    'l' => 'journey_end',
                    'm' => 'commission_fare',
                    'n' => 'commission',
                    'o' => 'tds',
                    'p' => 'net_fare',
                    'q' => 'gst',
                    'r' => 'convinence_amount',
                    's' => 'discount',
                    't' => 'grand_total',
                    'u' => 'booked_date',
                    
                );
           
            $excel_sheet_properties = array(
                'title' => 'Confirmed_Booking_AirlineReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Confirmed_Booking_AirlineReport',
                'sheet_title' => 'Confirmed_Booking_AirlineReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            
               $headings = array("Sl. No.","Appreference","Lead Pax Name","Lead Pax Email","Lead Pax Phone","GDS PNR","Airline PNR","Airline Code","From","To","Form Date","To Date","Commission Fare","Commission","TDS","Net Fare","GST","Convinence Amount","Discount","Customer Paid","Booked Date"); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
            $this->provab_csv->csv_export($headings,'Airline Confirmed Booking Report', $export_data);
           
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			$this->load->library ( 'provab_mailer' ); 
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$flight_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2c_report_airline_pdf',$pdf_data);
  			$this->provab_pdf->create_pdf($mail_template);


        }
    }




    public function export_all_booking_airline_report($op = '') {
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

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        // $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CONFIRMED'));
        $condition[] = array();

        $flight_booking_data = $this->flight_model->b2c_flight_report($condition, false, 0, 2000);
        // debug($flight_booking_data);exit;
         //Maximum 500 Data Can be exported at time
        $flight_booking_data = $this->booking_data_formatter->format_flight_booking_data($flight_booking_data, $this->current_module);
        // debug($flight_booking_data);exit;
        $flight_booking_data = $flight_booking_data['data']['booking_details'];



        $export_data = array();
        // debug($flight_booking_data);exit;
        $i=1;
        foreach ($flight_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
             // debug($v['status']);exit;
			$export_data[$k]['app_reference'] = $v['app_reference'];
            $export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['gds_pnr'] = $v['pnr'];
            $export_data[$k]['airline_pnr'] = $v['booking_itinerary_details'][0]['airline_pnr'];
            $export_data[$k]['airline_code'] = $v['booking_itinerary_details'][0]['airline_code'];
            $export_data[$k]['journey_from'] = $v['journey_from'];
           	$export_data[$k]['journey_to'] = $v['journey_to'];
           	$export_data[$k]['journey_start'] = $v['journey_start'];
           	$export_data[$k]['journey_end'] = $v['journey_end'];
           	$export_data[$k]['commission_fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['net_commission'];
           	$export_data[$k]['tds'] = $v['net_commission_tds'];
           	$export_data[$k]['net_fare'] = $v['net_fare'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['discount'] = $v['discount'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	$export_data[$k]['status'] = $v['status'];
           	$export_data[$k]['booked_date'] = date('d-m-Y', strtotime($v['booked_date']));
           	
        }

        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'Appreference',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'GDS PNR',
                    'g1' => 'Airline PNR',
                    'h1' => 'Airline Code',
                    'i1' => 'From',
                    'j1' => 'To',
                    'k1' => 'Form Date',
                    'l1' => 'To Date',
                    'm1' => 'Commission Fare',
					'n1' => 'Commission',
                    'o1' => 'TDS',
                    'p1' => 'Net Fare',
                    'q1' => 'GST',
                    'r1' => 'Convinence Amount',
                    's1' => 'Discount',
                    't1' => 'Customer Paid',
                    'u1' => 'Status',
                    'v1' => 'Booked Date',
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'gds_pnr',
                    'g' => 'airline_pnr',
                    'h' => 'airline_code',
                    'i' => 'journey_from',
                    'j' => 'journey_to',
                    'k' => 'journey_start',
                    'l' => 'journey_end',
                    'm' => 'commission_fare',
                    'n' => 'commission',
                    'o' => 'tds',
                    'p' => 'net_fare',
                    'q' => 'gst',
                    'r' => 'convinence_amount',
                    's' => 'discount',
                    't' => 'grand_total',
                    'u' => 'status',
                    'v' => 'booked_date',
                    
                );
           
            $excel_sheet_properties = array(
                'title' => 'All_Booking_AirlineReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'All_Booking_AirlineReport',
                'sheet_title' => 'All_Booking_AirlineReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            
               $headings = array("Sl. No.","Appreference","Lead Pax Name","Lead Pax Email","Lead Pax Phone","GDS PNR","Airline PNR","Airline Code","From","To","Form Date","To Date","Commission Fare","Commission","TDS","Net Fare","GST","Convinence Amount","Discount","Customer Paid","Status","Booked Date"); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
            $this->provab_csv->csv_export($headings,'Airline All Booking Report', $export_data);
           
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			$this->load->library ( 'provab_mailer' ); 
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$flight_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2c_report_airline_all_pdf',$pdf_data);
  			$this->provab_pdf->create_pdf($mail_template);


        }
    }
    public function export_all_booking_airline_report_email($op = '') {
        $this->load->model('flight_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('flight');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        // $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CONFIRMED'));
        $condition[] = array();

        $flight_booking_data = $this->flight_model->b2c_flight_report($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $flight_booking_data = $this->booking_data_formatter->format_flight_booking_data($flight_booking_data, $this->current_module);
        $flight_booking_data = $flight_booking_data['data']['booking_details'];



        $export_data = array();
        // debug($flight_booking_data);exit;
        $i=1;
        foreach ($flight_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
            $export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['gds_pnr'] = $v['pnr'];
            $export_data[$k]['airline_pnr'] = $v['booking_itinerary_details'][0]['airline_pnr'];
            $export_data[$k]['airline_code'] = $v['booking_itinerary_details'][0]['airline_code'];
            $export_data[$k]['journey_from'] = $v['journey_from'];
           	$export_data[$k]['journey_to'] = $v['journey_to'];
           	$export_data[$k]['journey_start'] = $v['journey_start'];
           	$export_data[$k]['journey_end'] = $v['journey_end'];
           	$export_data[$k]['commission_fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['net_commission'];
           	$export_data[$k]['tds'] = $v['net_commission_tds'];
           	$export_data[$k]['net_fare'] = $v['net_fare'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['discount'] = $v['discount'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	$export_data[$k]['status'] = $v['status'];
           	$export_data[$k]['booked_date'] = date('d-m-Y', strtotime($v['booked_date']));
           	
        }

        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'Appreference',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'GDS PNR',
                    'g1' => 'Airline PNR',
                    'h1' => 'Airline Code',
                    'i1' => 'From',
                    'j1' => 'To',
                    'k1' => 'Form Date',
                    'l1' => 'To Date',
                    'm1' => 'Commission Fare',
					'n1' => 'Commission',
                    'o1' => 'TDS',
                    'p1' => 'Net Fare',
                    'q1' => 'GST',
                    'r1' => 'Convinence Amount',
                    's1' => 'Discount',
                    't1' => 'Customer Paid',
                    'u1' => 'Status',
                    'v1' => 'Booked Date',
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'gds_pnr',
                    'g' => 'airline_pnr',
                    'h' => 'airline_code',
                    'i' => 'journey_from',
                    'j' => 'journey_to',
                    'k' => 'journey_start',
                    'l' => 'journey_end',
                    'm' => 'commission_fare',
                    'n' => 'commission',
                    'o' => 'tds',
                    'p' => 'net_fare',
                    'q' => 'gst',
                    'r' => 'convinence_amount',
                    's' => 'discount',
                    't' => 'grand_total',
                    'u' => 'status',
                    'v' => 'booked_date',
                    
                );
           
            $excel_sheet_properties = array(
                'title' => 'All_Booking_AirlineReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'All_Booking_AirlineReport',
                'sheet_title' => 'All_Booking_AirlineReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $excel=$this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties,'F');
            // echo BASEPATH;exit;
            $file_path=BASEPATH.'reportexcel/'.$excel;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
			  // $mail_template="vvvvvxdxd";
			       
			   $message = '
			   <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
			  
			  $emil=$this->input->post('email');
    	
        	$emails=explode(",",$emil);
    	 
        	foreach ($emails as $key => $value) {
        		
			$res = $this->provab_mailer->send_mail($value, 'Airline All Booking Report', $message,$file_path);
			
        	}
        	unlink($file_path);
        	redirect($_SERVER['HTTP_REFERER']);
        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            
               $headings = array("Sl. No.","Appreference","Lead Pax Name","Lead Pax Email","Lead Pax Phone","GDS PNR","Airline PNR","Airline Code","From","To","Form Date","To Date","Commission Fare","Commission","TDS","Net Fare","GST","Convinence Amount","Discount","Customer Paid","Status","Booked Date"); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
            $csv= $this->provab_csv->csv_export($headings,'Airline_All_Booking_Report', $export_data,'F');
           $file_path=BASEPATH.'reportcsv/'.$csv;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
			  // $mail_template="vvvvvxdxd";
			       
			   $message = '
			   <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
			  
			  $emil=$this->input->post('email');
    	
        	$emails=explode(",",$emil);
    	 
        	foreach ($emails as $key => $value) {
        		
			$res = $this->provab_mailer->send_mail($value, 'Airline All Booking Report', $message,$file_path);
			
        	}
        	unlink($file_path);
        	redirect($_SERVER['HTTP_REFERER']);
           
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			$this->load->library ( 'provab_mailer' ); 
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$flight_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2c_report_airline_all_pdf',$pdf_data);
  			$pdf=$this->provab_pdf->create_pdf($mail_template,'F');
  			 $message="";
			  // $mail_template="vvvvvxdxd";
			       
			   $message = '
			   <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
			  
			  $emil=$this->input->post('email');
    	
        	$emails=explode(",",$emil);
    	 
        	foreach ($emails as $key => $value) {
        		
			$res = $this->provab_mailer->send_mail($value, 'Airline All Booking Report', $message,$pdf);
			
        	}
        	// unlink($file_path);
        	redirect($_SERVER['HTTP_REFERER']);


        }
    }
     /*
     * For Cancelled Booking
     * Export AirlineReport details to Excel Format or PDF
     */

    public function export_cancelled_booking_airline_report($op = '') {
        $this->load->model('flight_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('flight');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CANCELLED'));

        $flight_booking_data = $this->flight_model->b2c_flight_report($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $flight_booking_data = $this->booking_data_formatter->format_flight_booking_data($flight_booking_data, $this->current_module);
        $flight_booking_data = $flight_booking_data['data']['booking_details'];



        $export_data = array();
        // debug($flight_booking_data);exit;
        $i=1;
        foreach ($flight_booking_data as $k => $v) {
           	if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
            $export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['gds_pnr'] = $v['pnr'];
            $export_data[$k]['airline_pnr'] = $v['booking_itinerary_details'][0]['airline_pnr'];
            $export_data[$k]['airline_code'] = $v['booking_itinerary_details'][0]['airline_code'];
            $export_data[$k]['journey_from'] = $v['journey_from'];
           	$export_data[$k]['journey_to'] = $v['journey_to'];
           	$export_data[$k]['journey_start'] = $v['journey_start'];
           	$export_data[$k]['journey_end'] = $v['journey_end'];
           	$export_data[$k]['commission_fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['net_commission'];
           	$export_data[$k]['tds'] = $v['net_commission_tds'];
           	$export_data[$k]['net_fare'] = $v['net_fare'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['discount'] = $v['discount'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	$export_data[$k]['booked_date'] = date('d-m-Y', strtotime($v['booked_date']));
           	
        }

        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'Appreference',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'GDS PNR',
                    'g1' => 'Airline PNR',
                    'h1' => 'Airline Code',
                    'i1' => 'From',
                    'j1' => 'To',
                    'k1' => 'Form Date',
                    'l1' => 'To Date',
                    'm1' => 'Commission Fare',
					'n1' => 'Commission',
                    'o1' => 'TDS',
                    'p1' => 'Net Fare',
                    'q1' => 'GST',
                    'r1' => 'Convinence Amount',
                    's1' => 'Discount',
                    't1' => 'Customer Paid',
                    'u1' => 'Booked Date',
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'gds_pnr',
                    'g' => 'airline_pnr',
                    'h' => 'airline_code',
                    'i' => 'journey_from',
                    'j' => 'journey_to',
                    'k' => 'journey_start',
                    'l' => 'journey_end',
                    'm' => 'commission_fare',
                    'n' => 'commission',
                    'o' => 'tds',
                    'p' => 'net_fare',
                    'q' => 'gst',
                    'r' => 'convinence_amount',
                    's' => 'discount',
                    't' => 'grand_total',
                    'u' => 'booked_date',
                    
                );
           
            $excel_sheet_properties = array(
                'title' => 'Confirmed_Booking_AirlineReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Confirmed_Booking_AirlineReport',
                'sheet_title' => 'Confirmed_Booking_AirlineReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            
               $headings = array("Sl. No.","Appreference","Lead Pax Name","Lead Pax Email","Lead Pax Phone","GDS PNR","Airline PNR","Airline Code","From","To","Form Date","To Date","Commission Fare","Commission","TDS","Net Fare","GST","Convinence Amount","Discount","Customer Paid","Booked Date"); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
            $this->provab_csv->csv_export($headings,'Airline Cancelled Booking Report', $export_data);
           
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			$this->load->library ( 'provab_mailer' ); 
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$flight_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2c_report_airline_pdf',$pdf_data);
  			$this->provab_pdf->create_pdf($mail_template);


        }
    }


    public function export_confirmed_booking_airline_report_email($op = '') {
        $this->load->model('flight_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('flight');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CONFIRMED'));

        $flight_booking_data = $this->flight_model->b2c_flight_report($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $flight_booking_data = $this->booking_data_formatter->format_flight_booking_data($flight_booking_data, $this->current_module);
        $flight_booking_data = $flight_booking_data['data']['booking_details'];



        $export_data = array();
        // debug($flight_booking_data);exit;
        $i=1;
        foreach ($flight_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
            $export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['gds_pnr'] = $v['pnr'];
            $export_data[$k]['airline_pnr'] = $v['booking_itinerary_details'][0]['airline_pnr'];
            $export_data[$k]['airline_code'] = $v['booking_itinerary_details'][0]['airline_code'];
            $export_data[$k]['journey_from'] = $v['journey_from'];
           	$export_data[$k]['journey_to'] = $v['journey_to'];
           	$export_data[$k]['journey_start'] = $v['journey_start'];
           	$export_data[$k]['journey_end'] = $v['journey_end'];
           	$export_data[$k]['commission_fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['net_commission'];
           	$export_data[$k]['tds'] = $v['net_commission_tds'];
           	$export_data[$k]['net_fare'] = $v['net_fare'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['discount'] = $v['discount'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	$export_data[$k]['booked_date'] = date('d-m-Y', strtotime($v['booked_date']));
           	
        }

        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'Appreference',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'GDS PNR',
                    'g1' => 'Airline PNR',
                    'h1' => 'Airline Code',
                    'i1' => 'From',
                    'j1' => 'To',
                    'k1' => 'Form Date',
                    'l1' => 'To Date',
                    'm1' => 'Commission Fare',
					'n1' => 'Commission',
                    'o1' => 'TDS',
                    'p1' => 'Net Fare',
                    'q1' => 'GST',
                    'r1' => 'Convinence Amount',
                    's1' => 'Discount',
                    't1' => 'Customer Paid',
                    'u1' => 'Booked Date',
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'gds_pnr',
                    'g' => 'airline_pnr',
                    'h' => 'airline_code',
                    'i' => 'journey_from',
                    'j' => 'journey_to',
                    'k' => 'journey_start',
                    'l' => 'journey_end',
                    'm' => 'commission_fare',
                    'n' => 'commission',
                    'o' => 'tds',
                    'p' => 'net_fare',
                    'q' => 'gst',
                    'r' => 'convinence_amount',
                    's' => 'discount',
                    't' => 'grand_total',
                    'u' => 'booked_date',
                    
                );
           
            $excel_sheet_properties = array(
                'title' => 'Confirmed_Booking_AirlineReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Cancelled_Booking_AirlineReport',
                'sheet_title' => 'Cancelled_Booking_AirlineReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $excel=$this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties,'F');
            // echo BASEPATH;exit;
            $file_path=BASEPATH.'reportexcel/'.$excel;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
			  // $mail_template="vvvvvxdxd";
			       
			   $message = '
			   <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
			  
			  $emil=$this->input->post('email');
    	
        	$emails=explode(",",$emil);
    	 
        	foreach ($emails as $key => $value) {
        		
			$res = $this->provab_mailer->send_mail($value, 'Airline Cancelled Booking Report', $message,$file_path);
			
        	}
        	unlink($file_path);
        	redirect($_SERVER['HTTP_REFERER']);
        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            
               $headings = array("Sl. No.","Appreference","Lead Pax Name","Lead Pax Email","Lead Pax Phone","GDS PNR","Airline PNR","Airline Code","From","To","Form Date","To Date","Commission Fare","Commission","TDS","Net Fare","GST","Convinence Amount","Discount","Customer Paid","Booked Date"); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
            $csv= $this->provab_csv->csv_export($headings,'Airline_Cancelled_Booking_Report', $export_data,'F');
           $file_path=BASEPATH.'reportcsv/'.$csv;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
			  // $mail_template="vvvvvxdxd";
			       
			   $message = '
			   <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
			  
			  $emil=$this->input->post('email');
    	
        	$emails=explode(",",$emil);
    	 
        	foreach ($emails as $key => $value) {
        		
			$res = $this->provab_mailer->send_mail($value, 'Airline Cancelled Booking Report', $message,$file_path);
			
        	}
        	unlink($file_path);
        	redirect($_SERVER['HTTP_REFERER']);
           
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			$this->load->library ( 'provab_mailer' ); 
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$flight_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2c_report_airline_pdf',$pdf_data);
  			$pdf=$this->provab_pdf->create_pdf($mail_template,'F');
  			 $message="";
			  // $mail_template="vvvvvxdxd";
			       
			   $message = '
			   <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
			  
			  $emil=$this->input->post('email');
    	
        	$emails=explode(",",$emil);
    	 
        	foreach ($emails as $key => $value) {
        		
			$res = $this->provab_mailer->send_mail($value, 'Airline Cancelled Booking Report', $message,$pdf);
			
        	}
        	// unlink($file_path);
        	redirect($_SERVER['HTTP_REFERER']);


        }
    }
     /*
     * For Cancelled Booking
     * Export AirlineReport details to Excel Format or PDF
     */

    public function export_cancelled_booking_airline_report_email($op = '') {
        $this->load->model('flight_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('flight');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CANCELLED'));

        $flight_booking_data = $this->flight_model->b2c_flight_report($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $flight_booking_data = $this->booking_data_formatter->format_flight_booking_data($flight_booking_data, $this->current_module);
        $flight_booking_data = $flight_booking_data['data']['booking_details'];



        $export_data = array();
        // debug($flight_booking_data);exit;
        $i=1;
        foreach ($flight_booking_data as $k => $v) {
           	if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
            $export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['gds_pnr'] = $v['pnr'];
            $export_data[$k]['airline_pnr'] = $v['booking_itinerary_details'][0]['airline_pnr'];
            $export_data[$k]['airline_code'] = $v['booking_itinerary_details'][0]['airline_code'];
            $export_data[$k]['journey_from'] = $v['journey_from'];
           	$export_data[$k]['journey_to'] = $v['journey_to'];
           	$export_data[$k]['journey_start'] = $v['journey_start'];
           	$export_data[$k]['journey_end'] = $v['journey_end'];
           	$export_data[$k]['commission_fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['net_commission'];
           	$export_data[$k]['tds'] = $v['net_commission_tds'];
           	$export_data[$k]['net_fare'] = $v['net_fare'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['discount'] = $v['discount'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	$export_data[$k]['booked_date'] = date('d-m-Y', strtotime($v['booked_date']));
           	
        }

        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'Appreference',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'GDS PNR',
                    'g1' => 'Airline PNR',
                    'h1' => 'Airline Code',
                    'i1' => 'From',
                    'j1' => 'To',
                    'k1' => 'Form Date',
                    'l1' => 'To Date',
                    'm1' => 'Commission Fare',
					'n1' => 'Commission',
                    'o1' => 'TDS',
                    'p1' => 'Net Fare',
                    'q1' => 'GST',
                    'r1' => 'Convinence Amount',
                    's1' => 'Discount',
                    't1' => 'Customer Paid',
                    'u1' => 'Booked Date',
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'gds_pnr',
                    'g' => 'airline_pnr',
                    'h' => 'airline_code',
                    'i' => 'journey_from',
                    'j' => 'journey_to',
                    'k' => 'journey_start',
                    'l' => 'journey_end',
                    'm' => 'commission_fare',
                    'n' => 'commission',
                    'o' => 'tds',
                    'p' => 'net_fare',
                    'q' => 'gst',
                    'r' => 'convinence_amount',
                    's' => 'discount',
                    't' => 'grand_total',
                    'u' => 'booked_date',
                    
                );
           
            $excel_sheet_properties = array(
                'title' => 'Confirmed_Booking_AirlineReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Cancelled_Booking_AirlineReport',
                'sheet_title' => 'Cancelled_Booking_AirlineReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $excel=$this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties,'F');
            // echo BASEPATH;exit;
            $file_path=BASEPATH.'reportexcel/'.$excel;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
			  // $mail_template="vvvvvxdxd";
			       
			   $message = '
			   <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
			  
			  $emil=$this->input->post('email');
    	
        	$emails=explode(",",$emil);
    	 
        	foreach ($emails as $key => $value) {
        		
			$res = $this->provab_mailer->send_mail($value, 'Airline Cancelled Booking Report', $message,$file_path);
			
        	}
        	unlink($file_path);
        	redirect($_SERVER['HTTP_REFERER']);
        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            
               $headings = array("Sl. No.","Appreference","Lead Pax Name","Lead Pax Email","Lead Pax Phone","GDS PNR","Airline PNR","Airline Code","From","To","Form Date","To Date","Commission Fare","Commission","TDS","Net Fare","GST","Convinence Amount","Discount","Customer Paid","Booked Date"); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
            $csv= $this->provab_csv->csv_export($headings,'Airline_Cancelled_Booking_Report', $export_data,'F');
           $file_path=BASEPATH.'reportcsv/'.$csv;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
			  // $mail_template="vvvvvxdxd";
			       
			   $message = '
			   <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
			  
			  $emil=$this->input->post('email');
    	
        	$emails=explode(",",$emil);
    	 
        	foreach ($emails as $key => $value) {
        		
			$res = $this->provab_mailer->send_mail($value, 'Airline Cancelled Booking Report', $message,$file_path);
			
        	}
        	unlink($file_path);
        	redirect($_SERVER['HTTP_REFERER']);
           
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			$this->load->library ( 'provab_mailer' ); 
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$flight_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2c_report_airline_pdf',$pdf_data);
  			$pdf=$this->provab_pdf->create_pdf($mail_template,'F');
  			 $message="";
			  // $mail_template="vvvvvxdxd";
			       
			   $message = '
			   <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
			  
			  $emil=$this->input->post('email');
    	
        	$emails=explode(",",$emil);
    	 
        	foreach ($emails as $key => $value) {
        		
			$res = $this->provab_mailer->send_mail($value, 'Airline Cancelled Booking Report', $message,$pdf);
			
        	}
        	// unlink($file_path);
        	redirect($_SERVER['HTTP_REFERER']);


        }
    }

    public function export_confirmed_booking_airline_report_b2b($op = '',$all) {
        $this->load->model('flight_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('flight');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        if($all="all")
        {

        	$condition[] = array();
        }
        else
        {

        	$condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CONFIRMED'));
        }

        $flight_booking_data = $this->flight_model->b2b_flight_report($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $flight_booking_data = $this->booking_data_formatter->format_flight_booking_data($flight_booking_data, $this->current_module);
        $flight_booking_data = $flight_booking_data['data']['booking_details'];



        $export_data = array();
         //debug($flight_booking_data);exit;
        $i=1;
        foreach ($flight_booking_data as $k => $v) {
           	if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
			$export_data[$k]['status'] = $v['status'];
			$export_data[$k]['agency_name'] = $v['agency_name'];
            $export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['gds_pnr'] = $v['pnr'];
            $export_data[$k]['airline_pnr'] = $v['booking_itinerary_details'][0]['airline_pnr'];
            $export_data[$k]['airline_code'] = $v['booking_itinerary_details'][0]['airline_code'];
            $export_data[$k]['journey_from'] = $v['journey_from'];
           	$export_data[$k]['journey_to'] = $v['journey_to'];
           	$export_data[$k]['journey_start'] = $v['journey_start'];
           	$export_data[$k]['journey_end'] = $v['journey_end'];
           	//$export_data[$k]['trip_type_label'] = $v['trip_type_label'];
           	$export_data[$k]['commission_fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['net_commission'];
           	$export_data[$k]['tds'] = $v['net_commission_tds'];
           	$export_data[$k]['net_fare'] = $v['net_fare'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['admin_markup'] = $v['admin_markup'];
           	$export_data[$k]['agent_markup'] = $v['agent_markup'];
           	$export_data[$k]['agent_commission'] = $v['agent_commission'];
           	$export_data[$k]['agent_tds'] = $v['agent_tds'];
           	$export_data[$k]['agent_buying_price'] = $v['agent_buying_price'];
           	$export_data[$k]['admin_buying_price'] = $v['admin_buying_price'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	if($all="all")
           	{

           	$export_data[$k]['status'] = $v['status'];
           	}
           	$export_data[$k]['booked_date'] = date('d-m-Y', strtotime($v['booked_date']));
           	
        }

        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'Appreference',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'GDS PNR',
                    'g1' => 'Airline PNR',
                    'h1' => 'Airline Code',
                    'i1' => 'From',
                    'j1' => 'To',
                    'k1' => 'Form Date',
                    'l1' => 'To Date',
                    'm1' => 'Commission Fare',
					'n1' => 'Commission',
                    'o1' => 'TDS',
                    'p1' => 'Net Fare',
                    'q1' => 'GST',
                    'r1' => 'Admin Markup',
                    's1' => 'Agent Mark up',
                    't1' => 'Agent Commission',
                    'u1' => 'Agent Tds',
                    'v1' => 'Agent NetFare',
                    'x1' =>'Admin Netfare',
                    'y1' =>'Customer Paid',
                    
                );
           		if($all="all")
           		{
           			$headings['z1']='status';
           			$headings['aa1']='Booked Date';
           		}
           		else
           		{
           			$headings['z1']='Booked Date';
           		}
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'gds_pnr',
                    'g' => 'airline_pnr',
                    'h' => 'airline_code',
                    'i' => 'journey_from',
                    'j' => 'journey_to',
                    'k' => 'journey_start',
                    'l' => 'journey_end',
                    'm' => 'commission_fare',
                    'n' => 'commission',
                    'o' => 'tds',
                    'p' => 'net_fare',
                    'q' => 'gst',
                    'r' => 'admin_markup',
                    's' => 'agent_markup',
                  	't' => 'agent_commission',
                    'u' => 'agent_tds',
                    'v' => 'agent_buying_price',
                    'x' =>'admin_buying_price',
                    'y' =>'grand_total',
                    
                    
                );
                if($all="all")
           		{
           			$fields['z1']='status';
           			$fields['aa']='booked_date';
           			$excel_sheet_properties = array(
		                'title' => 'All_Booking_AirlineReport_' . date('d-M-Y'),
		                'creator' => 'Accentria Solutions',
		                'description' => 'All_Booking_AirlineReport',
		                'sheet_title' => 'All_Booking_AirlineReport'
		            );
           		}
           		else
           		{
           			$headings['z1']='booked_date';
           			$excel_sheet_properties = array(
		                'title' => 'Confirmed_Booking_AirlineReport_' . date('d-M-Y'),
		                'creator' => 'Accentria Solutions',
		                'description' => 'Confirmed_Booking_AirlineReport',
		                'sheet_title' => 'Confirmed_Booking_AirlineReport'
		            );
           		}
           
            

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            	if($all="all")
           		{
           			$headings = array("Sl. No.","Appreference","Lead Pax Name","Lead Pax Email","Lead Pax Phone","GDS PNR","Airline PNR","Airline Code","From","To","Form Date","To Date","Commission Fare","Commission","TDS","Net Fare","GST","Admin Markup","Agent Mark up","Agent Commission","Agent Tds","Agent NetFare","Admin Netfare","Customer Paid","Status","Booked Date");
           		}
           		else
           		{
           			$headings = array("Sl. No.","Appreference","Lead Pax Name","Lead Pax Email","Lead Pax Phone","GDS PNR","Airline PNR","Airline Code","From","To","Form Date","To Date","Commission Fare","Commission","TDS","Net Fare","GST","Admin Markup","Agent Mark up","Agent Commission","Agent Tds","Agent NetFare","Admin Netfare","Customer Paid","Booked Date");
           		}
                
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
            $this->provab_csv->csv_export($headings,'Airline Confirmed Booking Report', $export_data);
           
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			$this->load->library ( 'provab_mailer' ); 
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$flight_booking_data;
  			$pdf_data['record_type']=$all;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2b_report_airline_pdf',$pdf_data);
  			$this->provab_pdf->create_pdf($mail_template);


        } 
    }
    public function export_confirmed_booking_airline_report_b2b_email($op = '',$all='') {
        $this->load->model('flight_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('flight');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        if($all=="all")
        {

        $condition[] = array();
        }
        else
        {

        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CONFIRMED'));
        }

        $flight_booking_data = $this->flight_model->b2b_flight_report($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $flight_booking_data = $this->booking_data_formatter->format_flight_booking_data($flight_booking_data, $this->current_module);
        $flight_booking_data = $flight_booking_data['data']['booking_details'];



        $export_data = array();
         //debug($flight_booking_data);exit;
        $i=1;
        foreach ($flight_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
			$export_data[$k]['status'] = $v['status'];
			$export_data[$k]['agency_name'] = $v['agency_name'];
            $export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['gds_pnr'] = $v['pnr'];
            $export_data[$k]['airline_pnr'] = $v['booking_itinerary_details'][0]['airline_pnr'];
            $export_data[$k]['airline_code'] = $v['booking_itinerary_details'][0]['airline_code'];
            $export_data[$k]['journey_from'] = $v['journey_from'];
           	$export_data[$k]['journey_to'] = $v['journey_to'];
           	$export_data[$k]['journey_start'] = $v['journey_start'];
           	$export_data[$k]['journey_end'] = $v['journey_end'];
           	//$export_data[$k]['trip_type_label'] = $v['trip_type_label'];
           	$export_data[$k]['commission_fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['net_commission'];
           	$export_data[$k]['tds'] = $v['net_commission_tds'];
           	$export_data[$k]['net_fare'] = $v['net_fare'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['admin_markup'] = $v['admin_markup'];
           	$export_data[$k]['agent_markup'] = $v['agent_markup'];
           	$export_data[$k]['agent_commission'] = $v['agent_commission'];
           	$export_data[$k]['agent_tds'] = $v['agent_tds'];
           	$export_data[$k]['agent_buying_price'] = $v['agent_buying_price'];
           	$export_data[$k]['admin_buying_price'] = $v['admin_buying_price'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	if($all=="all")
	        {

	        	$export_data[$k]['status'] = $v['status'];
	        }
           	
           	$export_data[$k]['booked_date'] = date('d-m-Y', strtotime($v['booked_date']));
           	
        }

        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'Appreference',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'GDS PNR',
                    'g1' => 'Airline PNR',
                    'h1' => 'Airline Code',
                    'i1' => 'From',
                    'j1' => 'To',
                    'k1' => 'Form Date',
                    'l1' => 'To Date',
                    'm1' => 'Commission Fare',
					'n1' => 'Commission',
                    'o1' => 'TDS',
                    'p1' => 'Net Fare',
                    'q1' => 'GST',
                    'r1' => 'Admin Markup',
                    's1' => 'Agent Mark up',
                    't1' => 'Agent Commission',
                    'u1' => 'Agent Tds',
                    'v1' => 'Agent NetFare',
                    'x1' =>'Admin Netfare',
                    'y1' =>'Customer Paid',
                    
                );
           		if($all="all")
           		{
           			$headings['z1']='status';
           			$headings['aa1']='Booked Date';
           		}
           		else
           		{
           			$headings['z1']='Booked Date';
           		}
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'gds_pnr',
                    'g' => 'airline_pnr',
                    'h' => 'airline_code',
                    'i' => 'journey_from',
                    'j' => 'journey_to',
                    'k' => 'journey_start',
                    'l' => 'journey_end',
                    'm' => 'commission_fare',
                    'n' => 'commission',
                    'o' => 'tds',
                    'p' => 'net_fare',
                    'q' => 'gst',
                    'r' => 'admin_markup',
                    's' => 'agent_markup',
                  	't' => 'agent_commission',
                    'u' => 'agent_tds',
                    'v' => 'agent_buying_price',
                    'x' =>'admin_buying_price',
                    'y' =>'grand_total',
                    
                    
                );

           
             if($all="all")
           		{
           			$fields['z1']='status';
           			$fields['aa']='booked_date';
           			$excel_sheet_properties = array(
		                'title' => 'All_Booking_AirlineReport_' . date('d-M-Y'),
		                'creator' => 'Accentria Solutions',
		                'description' => 'All_Booking_AirlineReport',
		                'sheet_title' => 'All_Booking_AirlineReport'
		            );
           		}
           		else
           		{
           			$headings['z1']='booked_date';
           			$excel_sheet_properties = array(
		                'title' => 'Confirmed_Booking_AirlineReport_' . date('d-M-Y'),
		                'creator' => 'Accentria Solutions',
		                'description' => 'Confirmed_Booking_AirlineReport',
		                'sheet_title' => 'Confirmed_Booking_AirlineReport'
		            );
           		}

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $excel=$this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties,'F');
            // echo BASEPATH;exit;
            $file_path=BASEPATH.'reportexcel/'.$excel;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
			  // $mail_template="vvvvvxdxd";
			       
			   $message = '
			   <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
			  
			  $emil=$this->input->post('email');
    	
        	$emails=explode(",",$emil);
    	 
        	foreach ($emails as $key => $value) {
        		if($all="all")
           		{
           			$res = $this->provab_mailer->send_mail($value, 'Airline All Booking Report', $message,$file_path);
           		}
           		else
           		{
           			$res = $this->provab_mailer->send_mail($value, 'Airline Confirmed Booking Report', $message,$file_path);
           		}
        		
			
			
        	}
        	unlink($file_path);
        	redirect($_SERVER['HTTP_REFERER']);
        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            	if($all="all")
           		{
           			 $headings = array("Sl. No.","Appreference","Lead Pax Name","Lead Pax Email","Lead Pax Phone","GDS PNR","Airline PNR","Airline Code","From","To","Form Date","To Date","Commission Fare","Commission","TDS","Net Fare","GST","Admin Markup","Agent Mark up","Agent Commission","Agent Tds","Agent NetFare","Admin Netfare","Customer Paid","Status","Booked Date");
           		}
           		else
           		{
           			 $headings = array("Sl. No.","Appreference","Lead Pax Name","Lead Pax Email","Lead Pax Phone","GDS PNR","Airline PNR","Airline Code","From","To","Form Date","To Date","Commission Fare","Commission","TDS","Net Fare","GST","Admin Markup","Agent Mark up","Agent Commission","Agent Tds","Agent NetFare","Admin Netfare","Customer Paid","Booked Date");
           		}
               
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
           $csv= $this->provab_csv->csv_export($headings,'Airline_Confirmed_Booking_Report', $export_data,'F');
           $file_path=BASEPATH.'reportcsv/'.$csv;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
            	if($all="all")
           		{
           			 $res = $this->provab_mailer->send_mail($value, 'Airline All Booking Report', $message,$file_path);
           		}
           		else
           		{
           			 $res = $this->provab_mailer->send_mail($value, 'Airline Confirmed Booking Report', $message,$file_path);
           		}
                
            
            
            }
            unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);
           
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			$this->load->library ( 'provab_mailer' ); 
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$flight_booking_data;
  			$pdf_data['record_type']=$all;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2b_report_airline_pdf',$pdf_data);
  			$pdf=$this->provab_pdf->create_pdf($mail_template,'F');
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                if($all="all")
           		{
           			  $res = $this->provab_mailer->send_mail($value, 'Airline Confirmed Booking Report', $message,$pdf);
           		}
           		else
           		{
           			  $res = $this->provab_mailer->send_mail($value, 'Airline Confirmed Booking Report', $message,$pdf);
           		}
           
            
            }
            // unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);


        } 
    }
    public function export_cancelled_booking_airline_report_b2b($op = '') {
        $this->load->model('flight_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('flight');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CANCELLED'));

        $flight_booking_data = $this->flight_model->b2b_flight_report($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $flight_booking_data = $this->booking_data_formatter->format_flight_booking_data($flight_booking_data, $this->current_module);
        $flight_booking_data = $flight_booking_data['data']['booking_details'];



        $export_data = array();
        // debug($flight_booking_data);exit;
        $i=1;
        foreach ($flight_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
            $export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['gds_pnr'] = $v['pnr'];
            $export_data[$k]['airline_pnr'] = $v['booking_itinerary_details'][0]['airline_pnr'];
            $export_data[$k]['airline_code'] = $v['booking_itinerary_details'][0]['airline_code'];
            $export_data[$k]['journey_from'] = $v['journey_from'];
           	$export_data[$k]['journey_to'] = $v['journey_to'];
           	$export_data[$k]['journey_start'] = $v['journey_start'];
           	$export_data[$k]['journey_end'] = $v['journey_end'];
           	$export_data[$k]['commission_fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['net_commission'];
           	$export_data[$k]['tds'] = $v['net_commission_tds'];
           	$export_data[$k]['net_fare'] = $v['net_fare'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['admin_markup'] = $v['admin_markup'];
           	$export_data[$k]['agent_markup'] = $v['agent_markup'];
           	$export_data[$k]['agent_tds'] = $v['agent_tds'];
           	$export_data[$k]['agent_buying_price'] = $v['agent_buying_price'];
           	$export_data[$k]['admin_buying_price'] = $v['admin_buying_price'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	$export_data[$k]['booked_date'] = date('d-m-Y', strtotime($v['booked_date']));
           	
        }

        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'Appreference',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'GDS PNR',
                    'g1' => 'Airline PNR',
                    'h1' => 'Airline Code',
                    'i1' => 'From',
                    'j1' => 'To',
                    'k1' => 'Form Date',
                    'l1' => 'To Date',
                    'm1' => 'Commission Fare',
					'n1' => 'Commission',
                    'o1' => 'TDS',
                    'p1' => 'Net Fare',
                    'q1' => 'GST',
                    'r1' => 'Admin Markup',
                    's1' => 'Agent Mark up',
                    't1' => 'Agent Commission',
                    'u1' => 'Agent Tds',
                    'v1' => 'Agent NetFare',
                    'x1' =>'Admin Netfare',
                    'y1' =>'Customer Paid',
                    'z1' =>'Booked Date',
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'gds_pnr',
                    'g' => 'airline_pnr',
                    'h' => 'airline_code',
                    'i' => 'journey_from',
                    'j' => 'journey_to',
                    'k' => 'journey_start',
                    'l' => 'journey_end',
                    'm' => 'commission_fare',
                    'n' => 'commission',
                    'o' => 'tds',
                    'p' => 'net_fare',
                    'q' => 'gst',
                   'r' => 'admin_markup',
                    's' => 'agent_markup',
                  	't' => 'agent_commission',
                    'u' => 'agent_tds',
                    'v' => 'agent_buying_price',
                    'x' =>'admin_buying_price',
                    'y' =>'grand_total',
                    'z' =>'booked_date',
                    
                );
           
            $excel_sheet_properties = array(
                'title' => 'Confirmed_Booking_AirlineReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Confirmed_Booking_AirlineReport',
                'sheet_title' => 'Confirmed_Booking_AirlineReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            
               $headings = array("Sl. No.","Appreference","Lead Pax Name","Lead Pax Email","Lead Pax Phone","GDS PNR","Airline PNR","Airline Code","From","To","Form Date","To Date","Commission Fare","Commission","TDS","Net Fare","GST","Admin Markup","Agent Mark up","Agent Commission","Agent Tds","Agent NetFare","Admin Netfare","Customer Paid","Booked Date"); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
            $this->provab_csv->csv_export($headings,'Airline Cancelled Booking Report', $export_data);
           
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			$this->load->library ( 'provab_mailer' ); 
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$flight_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2b_report_airline_pdf',$pdf_data);
  			$this->provab_pdf->create_pdf($mail_template);


        }
    }
    public function export_cancelled_booking_airline_report_b2b_email($op = '') {
        $this->load->model('flight_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('flight');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CANCELLED'));

        $flight_booking_data = $this->flight_model->b2b_flight_report($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $flight_booking_data = $this->booking_data_formatter->format_flight_booking_data($flight_booking_data, $this->current_module);
        $flight_booking_data = $flight_booking_data['data']['booking_details'];



        $export_data = array();
        // debug($flight_booking_data);exit;
        $i=1;
        foreach ($flight_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
            $export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['gds_pnr'] = $v['pnr'];
            $export_data[$k]['airline_pnr'] = $v['booking_itinerary_details'][0]['airline_pnr'];
            $export_data[$k]['airline_code'] = $v['booking_itinerary_details'][0]['airline_code'];
            $export_data[$k]['journey_from'] = $v['journey_from'];
           	$export_data[$k]['journey_to'] = $v['journey_to'];
           	$export_data[$k]['journey_start'] = $v['journey_start'];
           	$export_data[$k]['journey_end'] = $v['journey_end'];
           	$export_data[$k]['commission_fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['net_commission'];
           	$export_data[$k]['tds'] = $v['net_commission_tds'];
           	$export_data[$k]['net_fare'] = $v['net_fare'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['admin_markup'] = $v['admin_markup'];
           	$export_data[$k]['agent_markup'] = $v['agent_markup'];
           	$export_data[$k]['agent_tds'] = $v['agent_tds'];
           	$export_data[$k]['agent_buying_price'] = $v['agent_buying_price'];
           	$export_data[$k]['admin_buying_price'] = $v['admin_buying_price'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	$export_data[$k]['booked_date'] = date('d-m-Y', strtotime($v['booked_date']));
           	
        }

        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'Appreference',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'GDS PNR',
                    'g1' => 'Airline PNR',
                    'h1' => 'Airline Code',
                    'i1' => 'From',
                    'j1' => 'To',
                    'k1' => 'Form Date',
                    'l1' => 'To Date',
                    'm1' => 'Commission Fare',
					'n1' => 'Commission',
                    'o1' => 'TDS',
                    'p1' => 'Net Fare',
                    'q1' => 'GST',
                    'r1' => 'Admin Markup',
                    's1' => 'Agent Mark up',
                    't1' => 'Agent Commission',
                    'u1' => 'Agent Tds',
                    'v1' => 'Agent NetFare',
                    'x1' =>'Admin Netfare',
                    'y1' =>'Customer Paid',
                    'z1' =>'Booked Date',
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'gds_pnr',
                    'g' => 'airline_pnr',
                    'h' => 'airline_code',
                    'i' => 'journey_from',
                    'j' => 'journey_to',
                    'k' => 'journey_start',
                    'l' => 'journey_end',
                    'm' => 'commission_fare',
                    'n' => 'commission',
                    'o' => 'tds',
                    'p' => 'net_fare',
                    'q' => 'gst',
                   'r' => 'admin_markup',
                    's' => 'agent_markup',
                  	't' => 'agent_commission',
                    'u' => 'agent_tds',
                    'v' => 'agent_buying_price',
                    'x' =>'admin_buying_price',
                    'y' =>'grand_total',
                    'z' =>'booked_date',
                    
                );
           
            $excel_sheet_properties = array(
                'title' => 'Confirmed_Booking_AirlineReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Confirmed_Booking_AirlineReport',
                'sheet_title' => 'Confirmed_Booking_AirlineReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $excel=$this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties,'F');
            // echo BASEPATH;exit;
            $file_path=BASEPATH.'reportexcel/'.$excel;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
			  // $mail_template="vvvvvxdxd";
			       
			   $message = '
			   <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
			  
			  $emil=$this->input->post('email');
    	
        	$emails=explode(",",$emil);
    	 
        	foreach ($emails as $key => $value) {
        		
			$res = $this->provab_mailer->send_mail($value, 'Airline Cancelled Booking Report', $message,$file_path);
			
        	}
        	unlink($file_path);
        	redirect($_SERVER['HTTP_REFERER']);
        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            
               $headings = array("Sl. No.","Appreference","Lead Pax Name","Lead Pax Email","Lead Pax Phone","GDS PNR","Airline PNR","Airline Code","From","To","Form Date","To Date","Commission Fare","Commission","TDS","Net Fare","GST","Admin Markup","Agent Mark up","Agent Commission","Agent Tds","Agent NetFare","Admin Netfare","Customer Paid","Booked Date"); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
            $csv= $this->provab_csv->csv_export($headings,'Airline_Cancelled_Booking_Report', $export_data,'F');
           $file_path=BASEPATH.'reportcsv/'.$csv;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                
            $res = $this->provab_mailer->send_mail($value, 'Airline Cancelled Booking Report', $message,$file_path);
            
            }
            unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);
           
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			$this->load->library ( 'provab_mailer' ); 
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$flight_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2b_report_airline_pdf',$pdf_data);
  			 $pdf=$this->provab_pdf->create_pdf($mail_template,'F');
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                
            $res = $this->provab_mailer->send_mail($value, 'Airline Cancelled Booking Report', $message,$pdf);
            
            }
            // unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);


        }
    }
    public function export_confirmed_booking_hotel_report($op = '') {
        $this->load->model('hotel_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('hotel');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CONFIRMED'));

        $hotel_booking_data = $this->hotel_model->b2c_hotel_report($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $hotel_booking_data = $this->booking_data_formatter->format_hotel_booking_data($hotel_booking_data, $this->current_module);
        $hotel_booking_data = $hotel_booking_data['data']['booking_details'];



        $export_data = array();
        // debug($hotel_booking_data);exit;
        $i=1;
        foreach ($hotel_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['Reference No'] = $v['app_reference'];
			$export_data[$k]['Confirmation_Reference'] = $v['confirmation_reference'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['Hotel Name'] = $v['hotel_name'];
            $export_data[$k]['No.of rooms'] = $v['total_rooms'];
            $export_data[$k]['No.of Adult'] = $v['adult_count'];
            $export_data[$k]['No.of Child'] = $v['child_count'];
           	$export_data[$k]['city'] = $v['hotel_location'];
           	$export_data[$k]['check_in'] = $v['hotel_check_in'];
           	$export_data[$k]['check_out'] = $v['hotel_check_out'];
           	$export_data[$k]['commission_fare'] = $v['fare'];
           	$export_data[$k]['TDS'] = $v['TDS'];
           	$export_data[$k]['Admin_markup'] = $v['admin_markup'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['Discount'] = $v['discount'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	$export_data[$k]['booked_on'] = date('d-m-Y', strtotime($v['voucher_date']));
           	        		
           	
        }
        // debug($hotel_booking_data);exit;
		//debug($export_data[$k]['Payment Status']);exit;
        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'Reference No',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'Confirmation_Reference',
                    'g1' => 'Hotel Name',
                    'h1' => 'No.of rooms',
                    'i1' => 'No.of Adult',
                    'j1' => 'No.of Child',
                    'k1' => 'city',
                    'l1' => 'check_in',
                    'm1' => 'check_out',
					'n1' => 'Commission Fare',
                    'o1' => 'TDS',
                    'p1' => 'Admin Markup',
                    'q1' => 'GST',
                    'r1' => 'convinence Fee',
                    's1' => 'Discount',
                    't1' => 'Grand Total',
                    'u1' => 'Booked On',
                   
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'Reference No',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'Confirmation_Reference',
                    'g' => 'Hotel Name',
                    'h' => 'No.of rooms',
                    'i' => 'No.of Adult',
                    'j' => 'No.of Child',
                    'k' => 'city',
                    'l' => 'check_in',
                    'm' => 'check_out',
                    'n' => 'commission_fare',
                    'o' => 'TDS',
                    'p' => 'Admin_markup',
                    'q' => 'convinence_amount',
                    'r' => 'gst',
                    's' => 'Discount',
                  	't' => 'grand_total',
                    'u' => 'booked_on',
                                        
                );
           
            $excel_sheet_properties = array(
                'title' => 'Confirmed_Booking_HotelReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Confirmed_Booking_HotelReport',
                'sheet_title' => 'Confirmed_Booking_HotelReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            
               $headings = array("Sl. No.","Reference No","Lead Pax Name","Lead Pax Email","Lead Pax Phone","Confirmation_Reference","Hotel Name","No.of rooms","No.of Adult","No.of Child","city","check_in","check_out","Commission Fare","TDS","Admin Markup","GST","convinence Fee","Discount","Grand Total","Booked On"); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
            $this->provab_csv->csv_export($headings,'Hotel Confirmed Booking Report', $export_data);
           
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			$this->load->library ( 'provab_mailer' ); 
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$hotel_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2c_report_hotel_pdf',$pdf_data);
  			$this->provab_pdf->create_pdf($mail_template);


        } 
    }
    public function export_all_booking_hotel_report($op = '') {
        $this->load->model('hotel_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('hotel');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array();

        $hotel_booking_data = $this->hotel_model->b2c_hotel_report($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $hotel_booking_data = $this->booking_data_formatter->format_hotel_booking_data($hotel_booking_data, $this->current_module);
        $hotel_booking_data = $hotel_booking_data['data']['booking_details'];



        $export_data = array();
        // debug($hotel_booking_data);exit;
        $i=1;
        foreach ($hotel_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['Reference No'] = $v['app_reference'];
			$export_data[$k]['Confirmation_Reference'] = $v['confirmation_reference'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['Hotel Name'] = $v['hotel_name'];
            $export_data[$k]['No.of rooms'] = $v['total_rooms'];
            $export_data[$k]['No.of Adult'] = $v['adult_count'];
            $export_data[$k]['No.of Child'] = $v['child_count'];
           	$export_data[$k]['city'] = $v['hotel_location'];
           	$export_data[$k]['check_in'] = $v['hotel_check_in'];
           	$export_data[$k]['check_out'] = $v['hotel_check_out'];
           	$export_data[$k]['commission_fare'] = $v['fare'];
           	$export_data[$k]['TDS'] = $v['TDS'];
           	$export_data[$k]['Admin_markup'] = $v['admin_markup'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['Discount'] = $v['discount'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	$export_data[$k]['status'] = $v['status'];
           	$export_data[$k]['booked_on'] = date('d-m-Y', strtotime($v['voucher_date']));
           	        		
           	
        }
        // debug($hotel_booking_data);exit;
		//debug($export_data[$k]['Payment Status']);exit;
        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'Reference No',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'Confirmation_Reference',
                    'g1' => 'Hotel Name',
                    'h1' => 'No.of rooms',
                    'i1' => 'No.of Adult',
                    'j1' => 'No.of Child',
                    'k1' => 'city',
                    'l1' => 'check_in',
                    'm1' => 'check_out',
					'n1' => 'Commission Fare',
                    'o1' => 'TDS',
                    'p1' => 'Admin Markup',
                    'q1' => 'GST',
                    'r1' => 'convinence Fee',
                    's1' => 'Discount',
                    't1' => 'Grand Total',
                    'u1' => 'Status',
                    'v1' => 'Booked On',
                   
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'Reference No',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'Confirmation_Reference',
                    'g' => 'Hotel Name',
                    'h' => 'No.of rooms',
                    'i' => 'No.of Adult',
                    'j' => 'No.of Child',
                    'k' => 'city',
                    'l' => 'check_in',
                    'm' => 'check_out',
                    'n' => 'commission_fare',
                    'o' => 'TDS',
                    'p' => 'Admin_markup',
                    'q' => 'convinence_amount',
                    'r' => 'gst',
                    's' => 'Discount',
                  	't' => 'grand_total',
                    'u' => 'status',
                    'v' => 'booked_on',
                                        
                );
           
            $excel_sheet_properties = array(
                'title' => 'All_Booking_HotelReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'All_Booking_HotelReport',
                'sheet_title' => 'All_Booking_HotelReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            
               $headings = array("Sl. No.","Reference No","Lead Pax Name","Lead Pax Email","Lead Pax Phone","Confirmation_Reference","Hotel Name","No.of rooms","No.of Adult","No.of Child","city","check_in","check_out","Commission Fare","TDS","Admin Markup","GST","convinence Fee","Discount","Grand Total","Status","Booked On"); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
            $this->provab_csv->csv_export($headings,'Hotel All Booking Report', $export_data);
           
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			$this->load->library ( 'provab_mailer' ); 
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$hotel_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2c_report_hotel_all_pdf',$pdf_data);
  			$this->provab_pdf->create_pdf($mail_template);


        } 
    }  
    public function export_all_booking_hotel_report_email($op = '') {
    	 


        $this->load->model('hotel_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('hotel');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array();

        $hotel_booking_data = $this->hotel_model->b2c_hotel_report($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $hotel_booking_data = $this->booking_data_formatter->format_hotel_booking_data($hotel_booking_data, $this->current_module);
        $hotel_booking_data = $hotel_booking_data['data']['booking_details'];



        $export_data = array();
        //debug($hotel_booking_data);exit;
        $i=1;
        foreach ($hotel_booking_data as $k => $v) {
           	if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['Reference No'] = $v['app_reference'];
			$export_data[$k]['Confirmation_Reference'] = $v['confirmation_reference'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['Hotel Name'] = $v['hotel_name'];
            $export_data[$k]['No.of rooms'] = $v['total_rooms'];
            $export_data[$k]['No.of Adult'] = $v['adult_count'];
            $export_data[$k]['No.of Child'] = $v['child_count'];
           	$export_data[$k]['city'] = $v['hotel_location'];
           	$export_data[$k]['check_in'] = $v['hotel_check_in'];
           	$export_data[$k]['check_out'] = $v['hotel_check_out'];
           	$export_data[$k]['commission_fare'] = $v['fare'];
           	$export_data[$k]['TDS'] = $v['TDS'];
           	$export_data[$k]['Admin_markup'] = $v['admin_markup'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['Discount'] = $v['discount'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	$export_data[$k]['status'] = $v['status'];
           	$export_data[$k]['booked_on'] = date('d-m-Y', strtotime($v['voucher_date']));
           	        		
           	
        }
		//debug($export_data[$k]['Payment Status']);exit;
        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'Reference No',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'Confirmation_Reference',
                    'g1' => 'Hotel Name',
                    'h1' => 'No.of rooms',
                    'i1' => 'No.of Adult',
                    'j1' => 'No.of Child',
                    'k1' => 'city',
                    'l1' => 'check_in',
                    'm1' => 'check_out',
					'n1' => 'Commission Fare',
                    'o1' => 'TDS',
                    'p1' => 'Admin Markup',
                    'q1' => 'GST',
                    'r1' => 'convinence Fee',
                    's1' => 'Discount',
                    't1' => 'Grand Total',
                    'u1' => 'Status',
                    'v1' => 'Booked On',
                   
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'Reference No',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'Confirmation_Reference',
                    'g' => 'Hotel Name',
                    'h' => 'No.of rooms',
                    'i' => 'No.of Adult',
                    'j' => 'No.of Child',
                    'k' => 'city',
                    'l' => 'check_in',
                    'm' => 'check_out',
                    'n' => 'commission_fare',
                    'o' => 'TDS',
                    'p' => 'Admin_markup',
                    'q' => 'convinence_amount',
                    'r' => 'gst',
                    's' => 'Discount',
                  	't' => 'grand_total',
                    'u' => 'status',
                    'v' => 'booked_on',
                                        
                );
           
            $excel_sheet_properties = array(
                'title' => 'All_Booking_HotelReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'All_Booking_HotelReport',
                'sheet_title' => 'All_Booking_HotelReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $excel=$this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties,'F');
            // echo BASEPATH;exit;
            $file_path=BASEPATH.'reportexcel/'.$excel;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
			  // $mail_template="vvvvvxdxd";
			       
			   $message = '
			   <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
			  
			  $emil=$this->input->post('email');
    	
        	$emails=explode(",",$emil);
    	 
        	foreach ($emails as $key => $value) {
        		
			$res = $this->provab_mailer->send_mail($value, 'Hotel All Booking Report', $message,$file_path);
			
        	}
        	unlink($file_path);
        	redirect($_SERVER['HTTP_REFERER']);
			
			
        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            
               $headings = array("Sl. No.","Reference No","Lead Pax Name","Lead Pax Email","Lead Pax Phone","Confirmation_Reference","Hotel Name","No.of rooms","No.of Adult","No.of Child","city","check_in","check_out","Commission Fare","TDS","Admin Markup","GST","convinence Fee","Discount","Grand Total","Status","Booked On"); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
           $csv= $this->provab_csv->csv_export($headings,'Hotel_All_Booking_Report', $export_data,'F');
           $file_path=BASEPATH.'reportcsv/'.$csv;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
			  // $mail_template="vvvvvxdxd";
			       
			   $message = '
			   <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
			  
			  $emil=$this->input->post('email');
    	
        	$emails=explode(",",$emil);
    	 
        	foreach ($emails as $key => $value) {
        		
			$res = $this->provab_mailer->send_mail($value, 'Hotel All Booking Report', $message,$file_path);
			
        	}
        	unlink($file_path);
        	redirect($_SERVER['HTTP_REFERER']);
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			$this->load->library ( 'provab_mailer' ); 
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$hotel_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2c_report_hotel_all_pdf',$pdf_data);
  			$pdf=$this->provab_pdf->create_pdf($mail_template,'F');
  			 $message="";
			  // $mail_template="vvvvvxdxd";
			       
			   $message = '
			   <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
			  
			  $emil=$this->input->post('email');
    	
        	$emails=explode(",",$emil);
    	 
        	foreach ($emails as $key => $value) {
        		
			$res = $this->provab_mailer->send_mail($value, 'Hotel All Booking Report', $message,$pdf);
			
        	}
        	// unlink($file_path);
        	redirect($_SERVER['HTTP_REFERER']);


        }  
    }
    public function export_confirmed_booking_hotel_report_email($op = '') {
    	 


        $this->load->model('hotel_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('hotel');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CONFIRMED'));

        $hotel_booking_data = $this->hotel_model->b2c_hotel_report($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $hotel_booking_data = $this->booking_data_formatter->format_hotel_booking_data($hotel_booking_data, $this->current_module);
        $hotel_booking_data = $hotel_booking_data['data']['booking_details'];



        $export_data = array();
        //debug($hotel_booking_data);exit;
        $i=1;
        foreach ($hotel_booking_data as $k => $v) {
           	if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['Reference No'] = $v['app_reference'];
			$export_data[$k]['Confirmation_Reference'] = $v['confirmation_reference'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['Hotel Name'] = $v['hotel_name'];
            $export_data[$k]['No.of rooms'] = $v['total_rooms'];
            $export_data[$k]['No.of Adult'] = $v['adult_count'];
            $export_data[$k]['No.of Child'] = $v['child_count'];
           	$export_data[$k]['city'] = $v['hotel_location'];
           	$export_data[$k]['check_in'] = $v['hotel_check_in'];
           	$export_data[$k]['check_out'] = $v['hotel_check_out'];
           	$export_data[$k]['commission_fare'] = $v['fare'];
           	$export_data[$k]['TDS'] = $v['TDS'];
           	$export_data[$k]['Admin_markup'] = $v['admin_markup'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['Discount'] = $v['discount'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	$export_data[$k]['booked_on'] = date('d-m-Y', strtotime($v['voucher_date']));
           	        		
           	
        }
		//debug($export_data[$k]['Payment Status']);exit;
        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'Reference No',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'Confirmation_Reference',
                    'g1' => 'Hotel Name',
                    'h1' => 'No.of rooms',
                    'i1' => 'No.of Adult',
                    'j1' => 'No.of Child',
                    'k1' => 'city',
                    'l1' => 'check_in',
                    'm1' => 'check_out',
					'n1' => 'Commission Fare',
                    'o1' => 'TDS',
                    'p1' => 'Admin Markup',
                    'q1' => 'GST',
                    'r1' => 'convinence Fee',
                    's1' => 'Discount',
                    't1' => 'Grand Total',
                    'u1' => 'Booked On',
                   
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'Reference No',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'Confirmation_Reference',
                    'g' => 'Hotel Name',
                    'h' => 'No.of rooms',
                    'i' => 'No.of Adult',
                    'j' => 'No.of Child',
                    'k' => 'city',
                    'l' => 'check_in',
                    'm' => 'check_out',
                    'n' => 'commission_fare',
                    'o' => 'TDS',
                    'p' => 'Admin_markup',
                    'q' => 'convinence_amount',
                    'r' => 'gst',
                    's' => 'Discount',
                  	't' => 'grand_total',
                    'u' => 'booked_on',
                                        
                );
           
            $excel_sheet_properties = array(
                'title' => 'Confirmed_Booking_HotelReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Confirmed_Booking_HotelReport',
                'sheet_title' => 'Confirmed_Booking_HotelReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $excel=$this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties,'F');
            // echo BASEPATH;exit;
            $file_path=BASEPATH.'reportexcel/'.$excel;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
			  // $mail_template="vvvvvxdxd";
			       
			   $message = '
			   <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
			  
			  $emil=$this->input->post('email');
    	
        	$emails=explode(",",$emil);
    	 
        	foreach ($emails as $key => $value) {
        		
			$res = $this->provab_mailer->send_mail($value, 'Hotel Confirmed Booking Report', $message,$file_path);
			
        	}
        	unlink($file_path);
        	redirect($_SERVER['HTTP_REFERER']);
			
			
        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            
               $headings = array("Sl. No.","Reference No","Lead Pax Name","Lead Pax Email","Lead Pax Phone","Confirmation_Reference","Hotel Name","No.of rooms","No.of Adult","No.of Child","city","check_in","check_out","Commission Fare","TDS","Admin Markup","GST","convinence Fee","Discount","Grand Total","Booked On"); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
           $csv= $this->provab_csv->csv_export($headings,'Hotel_Confirmed_Booking_Report', $export_data,'F');
           $file_path=BASEPATH.'reportcsv/'.$csv;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
			  // $mail_template="vvvvvxdxd";
			       
			   $message = '
			   <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
			  
			  $emil=$this->input->post('email');
    	
        	$emails=explode(",",$emil);
    	 
        	foreach ($emails as $key => $value) {
        		
			$res = $this->provab_mailer->send_mail($value, 'Hotel Confirmed Booking Report', $message,$file_path);
			
        	}
        	unlink($file_path);
        	redirect($_SERVER['HTTP_REFERER']);
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			$this->load->library ( 'provab_mailer' ); 
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$hotel_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2c_report_hotel_pdf',$pdf_data);
  			$pdf=$this->provab_pdf->create_pdf($mail_template,'F');
  			 $message="";
			  // $mail_template="vvvvvxdxd";
			       
			   $message = '
			   <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
			  
			  $emil=$this->input->post('email');
    	
        	$emails=explode(",",$emil);
    	 
        	foreach ($emails as $key => $value) {
        		
			$res = $this->provab_mailer->send_mail($value, 'Hotel Confirmed Booking Report', $message,$pdf);
			
        	}
        	// unlink($file_path);
        	redirect($_SERVER['HTTP_REFERER']);


        }  
    }
    public function export_cancelled_booking_hotel_report($op = '') {
        $this->load->model('hotel_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('hotel');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CANCELLED'));

        $hotel_booking_data = $this->hotel_model->b2c_hotel_report($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $hotel_booking_data = $this->booking_data_formatter->format_hotel_booking_data($hotel_booking_data, $this->current_module);
        $hotel_booking_data = $hotel_booking_data['data']['booking_details'];



        $export_data = array();
        //debug($hotel_booking_data);exit;
        $i=1;
        foreach ($hotel_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['Reference No'] = $v['app_reference'];
			$export_data[$k]['Confirmation_Reference'] = $v['confirmation_reference'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['Hotel Name'] = $v['hotel_name'];
            $export_data[$k]['No.of rooms'] = $v['total_rooms'];
            $export_data[$k]['No.of Adult'] = $v['adult_count'];
            $export_data[$k]['No.of Child'] = $v['child_count'];
           	$export_data[$k]['city'] = $v['hotel_location'];
           	$export_data[$k]['check_in'] = $v['hotel_check_in'];
           	$export_data[$k]['check_out'] = $v['hotel_check_out'];
           	$export_data[$k]['commission_fare'] = $v['fare'];
           	$export_data[$k]['TDS'] = $v['TDS'];
           	$export_data[$k]['Admin_markup'] = $v['admin_markup'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['Discount'] = $v['discount'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	$export_data[$k]['booked_on'] = date('d-m-Y', strtotime($v['voucher_date']));
           	        		
           	
        }
		//debug($export_data[$k]['Payment Status']);exit;
        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'Reference No',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'Confirmation_Reference',
                    'g1' => 'Hotel Name',
                    'h1' => 'No.of rooms',
                    'i1' => 'No.of Adult',
                    'j1' => 'No.of Child',
                    'k1' => 'city',
                    'l1' => 'check_in',
                    'm1' => 'check_out',
					'n1' => 'Commission Fare',
                    'o1' => 'TDS',
                    'p1' => 'Admin Markup',
                    'q1' => 'GST',
                    'r1' => 'convinence Fee',
                    's1' => 'Discount',
                    't1' => 'Grand Total',
                    'u1' => 'Booked On',
                   
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'Reference No',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'Confirmation_Reference',
                    'g' => 'Hotel Name',
                    'h' => 'No.of rooms',
                    'i' => 'No.of Adult',
                    'j' => 'No.of Child',
                    'k' => 'city',
                    'l' => 'check_in',
                    'm' => 'check_out',
                    'n' => 'commission_fare',
                    'o' => 'TDS',
                    'p' => 'Admin_markup',
                    'q' => 'convinence_amount',
                    'r' => 'gst',
                    's' => 'Discount',
                  	't' => 'grand_total',
                    'u' => 'booked_on',
                                        
                );
           
            $excel_sheet_properties = array(
                'title' => 'Cancelled_Booking_HotelReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Cancelled_Booking_HotelReport',
                'sheet_title' => 'Cancelled_Booking_HotelReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
         else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            
               $headings = array("Sl. No.","Reference No","Lead Pax Name","Lead Pax Email","Lead Pax Phone","Confirmation_Reference","Hotel Name","No.of rooms","No.of Adult","No.of Child","city","check_in","check_out","Commission Fare","TDS","Admin Markup","GST","convinence Fee","Discount","Grand Total","Booked On"); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
            $this->provab_csv->csv_export($headings,'Hotel cancelled Booking Report', $export_data);
           
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			$this->load->library ( 'provab_mailer' ); 
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$hotel_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2c_report_hotel_pdf',$pdf_data);
  			$this->provab_pdf->create_pdf($mail_template);


        }
    }
    public function export_cancelled_booking_hotel_report_email($op = '') {
        $this->load->model('hotel_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('hotel');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CANCELLED'));

        $hotel_booking_data = $this->hotel_model->b2c_hotel_report($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $hotel_booking_data = $this->booking_data_formatter->format_hotel_booking_data($hotel_booking_data, $this->current_module);
        $hotel_booking_data = $hotel_booking_data['data']['booking_details'];



        $export_data = array();
        //debug($hotel_booking_data);exit;
        $i=1;
        foreach ($hotel_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['Reference No'] = $v['app_reference'];
			$export_data[$k]['Confirmation_Reference'] = $v['confirmation_reference'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['Hotel Name'] = $v['hotel_name'];
            $export_data[$k]['No.of rooms'] = $v['total_rooms'];
            $export_data[$k]['No.of Adult'] = $v['adult_count'];
            $export_data[$k]['No.of Child'] = $v['child_count'];
           	$export_data[$k]['city'] = $v['hotel_location'];
           	$export_data[$k]['check_in'] = $v['hotel_check_in'];
           	$export_data[$k]['check_out'] = $v['hotel_check_out'];
           	$export_data[$k]['commission_fare'] = $v['fare'];
           	$export_data[$k]['TDS'] = $v['TDS'];
           	$export_data[$k]['Admin_markup'] = $v['admin_markup'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['Discount'] = $v['discount'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	$export_data[$k]['booked_on'] = date('d-m-Y', strtotime($v['voucher_date']));
           	        		
           	
        }
		//debug($export_data[$k]['Payment Status']);exit;
        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'Reference No',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'Confirmation_Reference',
                    'g1' => 'Hotel Name',
                    'h1' => 'No.of rooms',
                    'i1' => 'No.of Adult',
                    'j1' => 'No.of Child',
                    'k1' => 'city',
                    'l1' => 'check_in',
                    'm1' => 'check_out',
					'n1' => 'Commission Fare',
                    'o1' => 'TDS',
                    'p1' => 'Admin Markup',
                    'q1' => 'GST',
                    'r1' => 'convinence Fee',
                    's1' => 'Discount',
                    't1' => 'Grand Total',
                    'u1' => 'Booked On',
                   
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'Reference No',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'Confirmation_Reference',
                    'g' => 'Hotel Name',
                    'h' => 'No.of rooms',
                    'i' => 'No.of Adult',
                    'j' => 'No.of Child',
                    'k' => 'city',
                    'l' => 'check_in',
                    'm' => 'check_out',
                    'n' => 'commission_fare',
                    'o' => 'TDS',
                    'p' => 'Admin_markup',
                    'q' => 'convinence_amount',
                    'r' => 'gst',
                    's' => 'Discount',
                  	't' => 'grand_total',
                    'u' => 'booked_on',
                                        
                );
           
            $excel_sheet_properties = array(
                'title' => 'Cancelled_Booking_HotelReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Cancelled_Booking_HotelReport',
                'sheet_title' => 'Cancelled_Booking_HotelReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
           $excel=$this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties,'F');
            // echo BASEPATH;exit;
            $file_path=BASEPATH.'reportexcel/'.$excel;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
			  // $mail_template="vvvvvxdxd";
			       
			   $message = '
			   <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
			  
			  $emil=$this->input->post('email');
    	
        	$emails=explode(",",$emil);
    	 
        	foreach ($emails as $key => $value) {
        		
			$res = $this->provab_mailer->send_mail($value, 'Hotel Cancelled Booking Report', $message,$file_path);
        	}
			unlink($file_path);
        	redirect($_SERVER['HTTP_REFERER']);
        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            
               $headings = array("Sl. No.","Reference No","Lead Pax Name","Lead Pax Email","Lead Pax Phone","Confirmation_Reference","Hotel Name","No.of rooms","No.of Adult","No.of Child","city","check_in","check_out","Commission Fare","TDS","Admin Markup","GST","convinence Fee","Discount","Grand Total","Booked On"); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
           $csv= $this->provab_csv->csv_export($headings,'Hotel_Cancelled_Booking_Report', $export_data,'F');
           $file_path=BASEPATH.'reportcsv/'.$csv;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
			  // $mail_template="vvvvvxdxd";
			       
			   $message = '
			   <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
			  
			  $emil=$this->input->post('email');
    	
        	$emails=explode(",",$emil);
    	 
        	foreach ($emails as $key => $value) {
        		
			$res = $this->provab_mailer->send_mail($value, 'Hotel Cancelled Booking Report', $message,$file_path);
			
        	}
        	unlink($file_path);
        	redirect($_SERVER['HTTP_REFERER']);
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			$this->load->library ( 'provab_mailer' ); 
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$hotel_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2c_report_hotel_pdf',$pdf_data);
  			$pdf=$this->provab_pdf->create_pdf($mail_template,'F');
  			 $message="";
			  // $mail_template="vvvvvxdxd";
			       
			   $message = '
			   <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
			  
			  $emil=$this->input->post('email');
    	
        	$emails=explode(",",$emil);
    	 
        	foreach ($emails as $key => $value) {
        		
			$res = $this->provab_mailer->send_mail($value, 'Hotel Cancelled Booking Report', $message,$pdf);
			
        	}
        	// unlink($file_path);
        	redirect($_SERVER['HTTP_REFERER']);


        } 
    }
 	public function export_confirmed_booking_hotel_report_b2b($op = '',$all='') {
 		// error_reporting(E_ALL);
 		// debug("cc");exit;
        $this->load->model('hotel_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('hotel');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        // debug($all);exit;
        if($all=="all")
        {

        	$condition[] = array();
        }
        else
        {

        	$condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CONFIRMED'));
        }

        $hotel_booking_data = $this->hotel_model->b2b_hotel_report($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $hotel_booking_data = $this->booking_data_formatter->format_hotel_booking_data($hotel_booking_data, $this->current_module);
        $hotel_booking_data = $hotel_booking_data['data']['booking_details'];



        $export_data = array();
        //debug($hotel_booking_data);exit;
        $i=1;
        foreach ($hotel_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['Reference No'] = $v['app_reference'];
			$export_data[$k]['Confirmation_Reference'] = $v['confirmation_reference'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['Hotel Name'] = $v['hotel_name'];
            $export_data[$k]['No.of rooms'] = $v['total_rooms'];
            $export_data[$k]['No.of Adult'] = $v['adult_count'];
            $export_data[$k]['No.of Child'] = $v['child_count'];
           	$export_data[$k]['city'] = $v['hotel_location'];
           	$export_data[$k]['check_in'] = $v['hotel_check_in'];
           	$export_data[$k]['check_out'] = $v['hotel_check_out'];
           	$export_data[$k]['commission_fare'] = $v['fare'];
           	$export_data[$k]['TDS'] = $v['TDS'];
           	$export_data[$k]['Admin_markup'] = $v['admin_markup'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['Discount'] = $v['discount'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	if($all=="all")
        	{
           		$export_data[$k]['status'] = $v['status'];
           	}
           	$export_data[$k]['booked_on'] = date('d-m-Y', strtotime($v['voucher_date']));
           	        		
           	
        }
		//debug($export_data[$k]['Payment Status']);exit;
        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'Reference No',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'Confirmation_Reference',
                    'g1' => 'Hotel Name',
                    'h1' => 'No.of rooms',
                    'i1' => 'No.of Adult',
                    'j1' => 'No.of Child',
                    'k1' => 'city',
                    'l1' => 'check_in',
                    'm1' => 'check_out',
					'n1' => 'Commission Fare',
                    'o1' => 'TDS',
                    'p1' => 'Admin Markup',
                    'q1' => 'GST',
                    'r1' => 'convinence Fee',
                    's1' => 'Discount',
                    't1' => 'Grand Total',                  
                );
           if($all=="all")
		        	{
		           		$headings['u1'] = 'Status';
                    	$headings['v1'] = 'Booked On';
		           	}
		           	else
		           	{

                    	$headings['u1'] = 'Booked On';
		           	}

                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'Reference No',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'Confirmation_Reference',
                    'g' => 'Hotel Name',
                    'h' => 'No.of rooms',
                    'i' => 'No.of Adult',
                    'j' => 'No.of Child',
                    'k' => 'city',
                    'l' => 'check_in',
                    'm' => 'check_out',
                    'n' => 'commission_fare',
                    'o' => 'TDS',
                    'p' => 'Admin_markup',
                    'q' => 'convinence_amount',
                    'r' => 'gst',
                    's' => 'Discount',
                  	't' => 'grand_total',
                    
                                        
                );
                if($all=="all")
	        	{
	           		$fields['u'] = 'Status';
	            	$fields['v'] = 'Booked On';
	           	}
	           	else
	           	{

	            	$fields['u'] = 'Booked On';
	           	}
           
		           	if($all=="all")
		        	{
		           		 $excel_sheet_properties = array(
			                'title' => 'All_Booking_HotelReport_' . date('d-M-Y'),
			                'creator' => 'Accentria Solutions',
			                'description' => 'All_Booking_HotelReport',
			                'sheet_title' => 'All_Booking_HotelReport'
			            );
		           	}
		           	else
		           	{

		            	 $excel_sheet_properties = array(
				                'title' => 'Confirmed_Booking_HotelReport_' . date('d-M-Y'),
				                'creator' => 'Accentria Solutions',
				                'description' => 'Confirmed_Booking_HotelReport',
				                'sheet_title' => 'Confirmed_Booking_HotelReport'
				            );
		           	}
           

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            	
            	if($all=="all")
		        	{
		           		$headings = array("Sl. No.","Reference No","Lead Pax Name","Lead Pax Email","Lead Pax Phone","Confirmation_Reference","Hotel Name","No.of rooms","No.of Adult","No.of Child","city","check_in","check_out","Commission Fare","TDS","Admin Markup","GST","convinence Fee","Discount","Grand Total","Status","Booked On");
		           	}
		           	else
		           	{

                    	 $headings = array("Sl. No.","Reference No","Lead Pax Name","Lead Pax Email","Lead Pax Phone","Confirmation_Reference","Hotel Name","No.of rooms","No.of Adult","No.of Child","city","check_in","check_out","Commission Fare","TDS","Admin Markup","GST","convinence Fee","Discount","Grand Total","Booked On");
		           	}
                
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
            if($all=="all")
		        	{
		           		 $this->provab_csv->csv_export($headings,'Hotel All Booking Report', $export_data);
		           	}
		           	else
		           	{

                    	 $this->provab_csv->csv_export($headings,'Hotel Confirmed Booking Report', $export_data);
		           	}
           
           
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			$this->load->library ( 'provab_mailer' ); 
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$hotel_booking_data;
  			$pdf_data['record_type']=$all;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2b_report_hotel_pdf',$pdf_data);
  			$this->provab_pdf->create_pdf($mail_template);


        } 
    }
    public function export_confirmed_booking_hotel_report_b2b_email($op = '',$all='') {
        $this->load->model('hotel_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('hotel');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        if($all="all"){

        	$condition[] = array();
        }
        else
        {

        	$condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CONFIRMED'));
        }

        $hotel_booking_data = $this->hotel_model->b2b_hotel_report($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $hotel_booking_data = $this->booking_data_formatter->format_hotel_booking_data($hotel_booking_data, $this->current_module);
        $hotel_booking_data = $hotel_booking_data['data']['booking_details'];



        $export_data = array();
        //debug($hotel_booking_data);exit;
        $i=1;
        foreach ($hotel_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['Reference No'] = $v['app_reference'];
			$export_data[$k]['Confirmation_Reference'] = $v['confirmation_reference'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['Hotel Name'] = $v['hotel_name'];
            $export_data[$k]['No.of rooms'] = $v['total_rooms'];
            $export_data[$k]['No.of Adult'] = $v['adult_count'];
            $export_data[$k]['No.of Child'] = $v['child_count'];
           	$export_data[$k]['city'] = $v['hotel_location'];
           	$export_data[$k]['check_in'] = $v['hotel_check_in'];
           	$export_data[$k]['check_out'] = $v['hotel_check_out'];
           	$export_data[$k]['commission_fare'] = $v['fare'];
           	$export_data[$k]['TDS'] = $v['TDS'];
           	$export_data[$k]['Admin_markup'] = $v['admin_markup'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['Discount'] = $v['discount'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	if($all="all"){

	        	$export_data[$k]['status'] = $v['status'];
	        }
           	$export_data[$k]['booked_on'] = date('d-m-Y', strtotime($v['voucher_date']));
           	        		
           	
        }
		//debug($export_data[$k]['Payment Status']);exit;
        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'Reference No',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'Confirmation_Reference',
                    'g1' => 'Hotel Name',
                    'h1' => 'No.of rooms',
                    'i1' => 'No.of Adult',
                    'j1' => 'No.of Child',
                    'k1' => 'city',
                    'l1' => 'check_in',
                    'm1' => 'check_out',
					'n1' => 'Commission Fare',
                    'o1' => 'TDS',
                    'p1' => 'Admin Markup',
                    'q1' => 'GST',
                    'r1' => 'convinence Fee',
                    's1' => 'Discount',
                    't1' => 'Grand Total',
                    
                   
                );
           			if($all="all"){

			        	$headings['u1'] = $v['status'];
			        	$headings['v1'] = $v['Booked On'];

			        }
			        else
			        {
			        	$headings['u1'] = $v['Booked On'];
			        }
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'Reference No',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'Confirmation_Reference',
                    'g' => 'Hotel Name',
                    'h' => 'No.of rooms',
                    'i' => 'No.of Adult',
                    'j' => 'No.of Child',
                    'k' => 'city',
                    'l' => 'check_in',
                    'm' => 'check_out',
                    'n' => 'commission_fare',
                    'o' => 'TDS',
                    'p' => 'Admin_markup',
                    'q' => 'convinence_amount',
                    'r' => 'gst',
                    's' => 'Discount',
                  	't' => 'grand_total',
                    
                                        
                );
                if($all="all"){

			        	$fields['u'] = $v['status'];
			        	$fields['v'] = $v['Booked On'];
			        	$excel_sheet_properties = array(
			                'title' => 'All_Booking_HotelReport_' . date('d-M-Y'),
			                'creator' => 'Accentria Solutions',
			                'description' => 'All_Booking_HotelReport',
			                'sheet_title' => 'All_Booking_HotelReport'
			            );

			        }
			        else
			        {
			        	$fields['u'] = $v['Booked On'];
			        	$excel_sheet_properties = array(
			                'title' => 'Confirmed_Booking_HotelReport_' . date('d-M-Y'),
			                'creator' => 'Accentria Solutions',
			                'description' => 'Confirmed_Booking_HotelReport',
			                'sheet_title' => 'Confirmed_Booking_HotelReport'
			            );
			        }
           
            

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $excel=$this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties,'F');
            // echo BASEPATH;exit;
            $file_path=BASEPATH.'reportexcel/'.$excel;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                
                if($all="all"){

			        	$res = $this->provab_mailer->send_mail($value, 'Hotel All Booking Report', $message,$file_path);

			        }
			        else
			        {
			        	$res = $this->provab_mailer->send_mail($value, 'Hotel Confirmed Booking Report', $message,$file_path);
			        }
            
            
            }
            unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);
        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            
               
           	if($all="all"){

			        	$headings = array("Sl. No.","Reference No","Lead Pax Name","Lead Pax Email","Lead Pax Phone","Confirmation_Reference","Hotel Name","No.of rooms","No.of Adult","No.of Child","city","check_in","check_out","Commission Fare","TDS","Admin Markup","GST","convinence Fee","Discount","Grand Total","Status","Booked On"); 

			        }
			        else
			        {
			        	$headings = array("Sl. No.","Reference No","Lead Pax Name","Lead Pax Email","Lead Pax Phone","Confirmation_Reference","Hotel Name","No.of rooms","No.of Adult","No.of Child","city","check_in","check_out","Commission Fare","TDS","Admin Markup","GST","convinence Fee","Discount","Grand Total","Booked On"); 
			        }
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
            if($all="all"){

			        	$csv= $this->provab_csv->csv_export($headings,'Hotel_All_Booking_Report', $export_data,'F'); 

			        }
			        else
			        {
			        	$csv= $this->provab_csv->csv_export($headings,'Hotel_Confirmed_Booking_Report', $export_data,'F');
			        }
            
           $file_path=BASEPATH.'reportcsv/'.$csv;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
        // $mail_template="vvvvvxdxd";
             
         $message = '
         <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
        
        $emil=$this->input->post('email');
      
          $emails=explode(",",$emil);
       
          foreach ($emails as $key => $value) {
            if($all="all"){

			        	$res = $this->provab_mailer->send_mail($value, 'Hotel All Booking Report', $message,$file_path);

			        }
			        else
			        {
			        	$res = $this->provab_mailer->send_mail($value, 'Hotel Confirmed Booking Report', $message,$file_path);
			        }
      
      
          }
          unlink($file_path);
          redirect($_SERVER['HTTP_REFERER']);
           
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			$this->load->library ( 'provab_mailer' ); 
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$hotel_booking_data;
  			$pdf_data['record_type']=$all;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2b_report_hotel_pdf',$pdf_data);
  			$pdf=$this->provab_pdf->create_pdf($mail_template,'F');
  			 $message="";
			  // $mail_template="vvvvvxdxd";
			       
			   $message = '
			   <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
			  
			  $emil=$this->input->post('email');
    	
        	$emails=explode(",",$emil);
    	 
        	foreach ($emails as $key => $value) {
        	if($all="all"){

			        	$res = $this->provab_mailer->send_mail($value, 'Hotel All Booking Report', $message,$pdf);

			        }
			        else
			        {
			        	$res = $this->provab_mailer->send_mail($value, 'Hotel Confirmed Booking Report', $message,$pdf);
			        }	
			
			
        	}
        	// unlink($file_path);
        	redirect($_SERVER['HTTP_REFERER']);


        } 
    }
    public function export_cancelled_booking_hotel_report_b2b($op = '') {
        $this->load->model('hotel_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('hotel');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CANCELLED'));

        $hotel_booking_data = $this->hotel_model->b2b_hotel_report($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $hotel_booking_data = $this->booking_data_formatter->format_hotel_booking_data($hotel_booking_data, $this->current_module);
        $hotel_booking_data = $hotel_booking_data['data']['booking_details'];



        $export_data = array();
        //debug($hotel_booking_data);exit;
        $i=1;
        foreach ($hotel_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['Reference No'] = $v['app_reference'];
			$export_data[$k]['Confirmation_Reference'] = $v['confirmation_reference'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['Hotel Name'] = $v['hotel_name'];
            $export_data[$k]['No.of rooms'] = $v['total_rooms'];
            $export_data[$k]['No.of Adult'] = $v['adult_count'];
            $export_data[$k]['No.of Child'] = $v['child_count'];
           	$export_data[$k]['city'] = $v['hotel_location'];
           	$export_data[$k]['check_in'] = $v['hotel_check_in'];
           	$export_data[$k]['check_out'] = $v['hotel_check_out'];
           	$export_data[$k]['commission_fare'] = $v['fare'];
           	$export_data[$k]['TDS'] = $v['TDS'];
           	$export_data[$k]['Admin_markup'] = $v['admin_markup'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['Discount'] = $v['discount'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	$export_data[$k]['booked_on'] = date('d-m-Y', strtotime($v['voucher_date']));
           	        		
           	
        }
        //debug($export_data[$k]['Payment Status']);exit;
        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'Reference No',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'Confirmation_Reference',
                    'g1' => 'Hotel Name',
                    'h1' => 'No.of rooms',
                    'i1' => 'No.of Adult',
                    'j1' => 'No.of Child',
                    'k1' => 'city',
                    'l1' => 'check_in',
                    'm1' => 'check_out',
					'n1' => 'Commission Fare',
                    'o1' => 'TDS',
                    'p1' => 'Admin Markup',
                    'q1' => 'GST',
                    'r1' => 'convinence Fee',
                    's1' => 'Discount',
                    't1' => 'Grand Total',
                    'u1' => 'Booked On',
                   
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'Reference No',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'Confirmation_Reference',
                    'g' => 'Hotel Name',
                    'h' => 'No.of rooms',
                    'i' => 'No.of Adult',
                    'j' => 'No.of Child',
                    'k' => 'city',
                    'l' => 'check_in',
                    'm' => 'check_out',
                    'n' => 'commission_fare',
                    'o' => 'TDS',
                    'p' => 'Admin_markup',
                    'q' => 'convinence_amount',
                    'r' => 'gst',
                    's' => 'Discount',
                  	't' => 'grand_total',
                    'u' => 'booked_on',
                                        
                );
           
            $excel_sheet_properties = array(
                'title' => 'Cancelled_Booking_HotelReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Cancelled_Booking_HotelReport',
                'sheet_title' => 'Cancelled_Booking_HotelReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            
               $headings = array("Sl. No.","Reference No","Lead Pax Name","Lead Pax Email","Lead Pax Phone","Confirmation_Reference","Hotel Name","No.of rooms","No.of Adult","No.of Child","city","check_in","check_out","Commission Fare","TDS","Admin Markup","GST","convinence Fee","Discount","Grand Total","Booked On"); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
            $this->provab_csv->csv_export($headings,'Hotel Cancelled Booking Report', $export_data);
           
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			$this->load->library ( 'provab_mailer' ); 
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$hotel_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2b_report_hotel_pdf',$pdf_data);
  			$this->provab_pdf->create_pdf($mail_template);


        } 
    }
    public function export_cancelled_booking_hotel_report_b2b_email($op = '') {
        $this->load->model('hotel_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('hotel');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CANCELLED'));

        $hotel_booking_data = $this->hotel_model->b2b_hotel_report($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $hotel_booking_data = $this->booking_data_formatter->format_hotel_booking_data($hotel_booking_data, $this->current_module);
        $hotel_booking_data = $hotel_booking_data['data']['booking_details'];



        $export_data = array();
        //debug($hotel_booking_data);exit;
        $i=1;
        foreach ($hotel_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['Reference No'] = $v['app_reference'];
			$export_data[$k]['Confirmation_Reference'] = $v['confirmation_reference'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['Hotel Name'] = $v['hotel_name'];
            $export_data[$k]['No.of rooms'] = $v['total_rooms'];
            $export_data[$k]['No.of Adult'] = $v['adult_count'];
            $export_data[$k]['No.of Child'] = $v['child_count'];
           	$export_data[$k]['city'] = $v['hotel_location'];
           	$export_data[$k]['check_in'] = $v['hotel_check_in'];
           	$export_data[$k]['check_out'] = $v['hotel_check_out'];
           	$export_data[$k]['commission_fare'] = $v['fare'];
           	$export_data[$k]['TDS'] = $v['TDS'];
           	$export_data[$k]['Admin_markup'] = $v['admin_markup'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['Discount'] = $v['discount'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	$export_data[$k]['booked_on'] = date('d-m-Y', strtotime($v['voucher_date']));
           	        		
           	
        }
        //debug($export_data[$k]['Payment Status']);exit;
        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'Reference No',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'Confirmation_Reference',
                    'g1' => 'Hotel Name',
                    'h1' => 'No.of rooms',
                    'i1' => 'No.of Adult',
                    'j1' => 'No.of Child',
                    'k1' => 'city',
                    'l1' => 'check_in',
                    'm1' => 'check_out',
					'n1' => 'Commission Fare',
                    'o1' => 'TDS',
                    'p1' => 'Admin Markup',
                    'q1' => 'GST',
                    'r1' => 'convinence Fee',
                    's1' => 'Discount',
                    't1' => 'Grand Total',
                    'u1' => 'Booked On',
                   
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'Reference No',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'Confirmation_Reference',
                    'g' => 'Hotel Name',
                    'h' => 'No.of rooms',
                    'i' => 'No.of Adult',
                    'j' => 'No.of Child',
                    'k' => 'city',
                    'l' => 'check_in',
                    'm' => 'check_out',
                    'n' => 'commission_fare',
                    'o' => 'TDS',
                    'p' => 'Admin_markup',
                    'q' => 'convinence_amount',
                    'r' => 'gst',
                    's' => 'Discount',
                  	't' => 'grand_total',
                    'u' => 'booked_on',
                                        
                );
           
            $excel_sheet_properties = array(
                'title' => 'Cancelled_Booking_HotelReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Cancelled_Booking_HotelReport',
                'sheet_title' => 'Cancelled_Booking_HotelReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $excel=$this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties,'F');
            // echo BASEPATH;exit;
            $file_path=BASEPATH.'reportexcel/'.$excel;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
			  // $mail_template="vvvvvxdxd";
			       
			   $message = '
			   <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
			  
			  $emil=$this->input->post('email');
    	
        	$emails=explode(",",$emil);
    	 
        	foreach ($emails as $key => $value) {
        		
			$res = $this->provab_mailer->send_mail($value, 'Hotel Cancelled Booking Report', $message,$file_path);
        	}
			unlink($file_path);
        	redirect($_SERVER['HTTP_REFERER']);
        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            
               $headings = array("Sl. No.","Reference No","Lead Pax Name","Lead Pax Email","Lead Pax Phone","Confirmation_Reference","Hotel Name","No.of rooms","No.of Adult","No.of Child","city","check_in","check_out","Commission Fare","TDS","Admin Markup","GST","convinence Fee","Discount","Grand Total","Booked On"); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
             $csv= $this->provab_csv->csv_export($headings,'Hotel_Cancelled_Booking_Report', $export_data,'F');
           $file_path=BASEPATH.'reportcsv/'.$csv;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                
            $res = $this->provab_mailer->send_mail($value, 'Hotel Cancelled Booking Report', $message,$file_path);
            
            }
            unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);
           
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			$this->load->library ( 'provab_mailer' ); 
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$hotel_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2b_report_hotel_pdf',$pdf_data);
  			$pdf=$this->provab_pdf->create_pdf($mail_template,'F');
  			 $message="";
			  // $mail_template="vvvvvxdxd";
			       
			   $message = '
			   <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
			  
			  $emil=$this->input->post('email');
    	
        	$emails=explode(",",$emil);
    	 
        	foreach ($emails as $key => $value) {
        		
			$res = $this->provab_mailer->send_mail($value, 'Hotel Cancelled Booking Report', $message,$pdf);
			
        	}
        	// unlink($file_path);
        	redirect($_SERVER['HTTP_REFERER']);


        } 
    }
    public function export_confirmed_booking_bus_report($op = '') {
        $this->load->model('bus_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('bus');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CONFIRMED'));

        $bus_booking_data = $this->bus_model->b2c_bus_report($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $bus_booking_data = $this->booking_data_formatter->format_bus_booking_data($bus_booking_data, $this->current_module);
        $bus_booking_data = $bus_booking_data['data']['booking_details'];



        $export_data = array();
        //debug($bus_booking_data);exit;
        $i=1;
        foreach ($bus_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['Pnr'] = $v['pnr'];
            $export_data[$k]['operator'] = $v['operator'];
            $export_data[$k]['from'] = $v['departure_from'];
            $export_data[$k]['to'] = $v['arrival_to'];
           	$export_data[$k]['bus_type'] = $v['bus_type'];
           	$export_data[$k]['Comm.Fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['admin_commission'];
           	$export_data[$k]['TDS'] = $v['admin_tds'];
           	$export_data[$k]['NetFare'] = $v['admin_buying_price'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['Markup'] = $v['admin_markup'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['Discount'] = $v['discount'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	$export_data[$k]['Travel date'] = $v['journey_datetime'];
           	$export_data[$k]['booked_date'] = date('d-m-Y', strtotime($v['booked_date']));
           	        		
           	
        }
        // debug($export_data);exit;
        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'app_reference',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'Pnr',
                    'g1' => 'operator',
                    'h1' => 'From',
                    'i1' => 'To',
                    'j1' => 'Seat Type',
                    'k1' => 'commision Fare',
                    'l1' => 'commission',
                    'm1' => 'Tds',
					'n1' => 'Net Fare',
                    'o1' => 'Conivence Fee',
                    'p1' => 'Markup',
                    'q1' => 'GST',
                    'r1' => 'Discount',
                    's1' => 'Total Fare',
                    't1' => 'Travel date',
                    'u1' => 'Booked On',
                   
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'Pnr',
                    'g' => 'operator',
                    'h' => 'from',
                    'i' => 'to',
                    'j' => 'bus_type',
                    'k' => 'Comm.Fare',
                    'l' => 'commission',
                    'm' => 'TDS',
                    'n' => 'NetFare',
                    'o' => 'convinence_amount',
                    'p' => 'Markup',
                    'q' => 'gst',
                    'r' => 'Discount',
                    's' => 'grand_total',
                  	't' => 'Travel date',
                    'u' => 'booked_date',
                                        
                );
           
            $excel_sheet_properties = array(
                'title' => 'Confirmed_Booking_BusReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Confirmed_Booking_BusReport',
                'sheet_title' => 'Confirmed_Booking_BusReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            
               $headings = array("Sl. No.","app_reference","Lead Pax Name","Lead Pax Email","Lead Pax Phone","Pnr","operator","From","To","Seat Type","commision Fare","commission","Tds","NetFare","convinence_amount","Markup","GST","Discount","Total Fare","Travel date","Booked On"); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
           $this->provab_csv->csv_export($headings,'Confirmed_Booking_BusReport', $export_data);
           
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$bus_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2c_report_bus_pdf',$pdf_data);
  			$this->provab_pdf->create_pdf($mail_template);
  			


        } 
    }
    public function export_all_booking_bus_report($op = '') {
        $this->load->model('bus_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('bus');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array();

        $bus_booking_data = $this->bus_model->b2c_bus_report($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $bus_booking_data = $this->booking_data_formatter->format_bus_booking_data($bus_booking_data, $this->current_module);
        $bus_booking_data = $bus_booking_data['data']['booking_details'];



        $export_data = array();
        //debug($bus_booking_data);exit;
        $i=1;
        foreach ($bus_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['Pnr'] = $v['pnr'];
            $export_data[$k]['operator'] = $v['operator'];
            $export_data[$k]['from'] = $v['departure_from'];
            $export_data[$k]['to'] = $v['arrival_to'];
           	$export_data[$k]['bus_type'] = $v['bus_type'];
           	$export_data[$k]['Comm.Fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['admin_commission'];
           	$export_data[$k]['TDS'] = $v['admin_tds'];
           	$export_data[$k]['NetFare'] = $v['admin_buying_price'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['Markup'] = $v['admin_markup'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['Discount'] = $v['discount'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	$export_data[$k]['Travel date'] = $v['journey_datetime'];
           	$export_data[$k]['status'] = $v['status'];
           	$export_data[$k]['booked_date'] = date('d-m-Y', strtotime($v['booked_date']));
           	        		
           	
        }
        //debug($export_data[$k]['Payment Status']);exit;
        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'app_reference',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'Pnr',
                    'g1' => 'operator',
                    'h1' => 'From',
                    'i1' => 'To',
                    'j1' => 'Seat Type',
                    'k1' => 'commision Fare',
                    'l1' => 'commission',
                    'm1' => 'Tds',
					'n1' => 'Net Fare',
                    'o1' => 'Conivence Fee',
                    'p1' => 'Markup',
                    'q1' => 'GST',
                    'r1' => 'Discount',
                    's1' => 'Total Fare',
                    't1' => 'Travel date',
                    'u1' => 'Status',
                    'v1' => 'Booked On',
                   
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'Pnr',
                    'g' => 'operator',
                    'h' => 'from',
                    'i' => 'to',
                    'j' => 'bus_type',
                    'k' => 'Comm.Fare',
                    'l' => 'commission',
                    'm' => 'TDS',
                    'n' => 'NetFare',
                    'o' => 'convinence_amount',
                    'p' => 'Markup',
                    'q' => 'gst',
                    'r' => 'Discount',
                    's' => 'grand_total',
                  	't' => 'Travel date',
                    'u' => 'status',
                    'v' => 'booked_date',
                                        
                );
           
            $excel_sheet_properties = array(
                'title' => 'ALl_Booking_BusReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'All_Booking_BusReport',
                'sheet_title' => 'All_Booking_BusReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            
               $headings = array("Sl. No.","app_reference","Lead Pax Name","Lead Pax Email","Lead Pax Phone","Pnr","operator","From","To","Seat Type","commision Fare","commission","Tds","NetFare","convinence_amount","Markup","GST","Discount","Total Fare","Travel date","Status","Booked On"); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
           $this->provab_csv->csv_export($headings,'All_Booking_BusReport', $export_data);
           
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$bus_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2c_report_bus_all_pdf',$pdf_data);
  			$this->provab_pdf->create_pdf($mail_template);
  			


        } 
    }
    public function export_all_booking_bus_report_email($op = '') {
        $this->load->model('bus_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('bus');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array();

        $bus_booking_data = $this->bus_model->b2c_bus_report($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $bus_booking_data = $this->booking_data_formatter->format_bus_booking_data($bus_booking_data, $this->current_module);
        $bus_booking_data = $bus_booking_data['data']['booking_details'];



        $export_data = array();
        //debug($bus_booking_data);exit;
        $i=1;
        foreach ($bus_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['Pnr'] = $v['pnr'];
            $export_data[$k]['operator'] = $v['operator'];
            $export_data[$k]['from'] = $v['departure_from'];
            $export_data[$k]['to'] = $v['arrival_to'];
           	$export_data[$k]['bus_type'] = $v['bus_type'];
           	$export_data[$k]['Comm.Fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['admin_commission'];
           	$export_data[$k]['TDS'] = $v['admin_tds'];
           	$export_data[$k]['NetFare'] = $v['admin_buying_price'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['Markup'] = $v['admin_markup'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['Discount'] = $v['discount'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	$export_data[$k]['Travel date'] = $v['journey_datetime'];
           	$export_data[$k]['status'] = $v['status'];
           	$export_data[$k]['booked_date'] = date('d-m-Y', strtotime($v['booked_date']));
           	        		
           	
        }
        //debug($export_data[$k]['Payment Status']);exit;
        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'app_reference',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'Pnr',
                    'g1' => 'operator',
                    'h1' => 'From',
                    'i1' => 'To',
                    'j1' => 'Seat Type',
                    'k1' => 'commision Fare',
                    'l1' => 'commission',
                    'm1' => 'Tds',
					'n1' => 'Net Fare',
                    'o1' => 'Conivence Fee',
                    'p1' => 'Markup',
                    'q1' => 'GST',
                    'r1' => 'Discount',
                    's1' => 'Total Fare',
                    't1' => 'Travel date',
                    'u1' => 'Status',
                    'v1' => 'Booked On',
                   
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'Pnr',
                    'g' => 'operator',
                    'h' => 'from',
                    'i' => 'to',
                    'j' => 'bus_type',
                    'k' => 'Comm.Fare',
                    'l' => 'commission',
                    'm' => 'TDS',
                    'n' => 'NetFare',
                    'o' => 'convinence_amount',
                    'p' => 'Markup',
                    'q' => 'gst',
                    'r' => 'Discount',
                    's' => 'grand_total',
                  	't' => 'Travel date',
                    'u' => 'status',
                    'v' => 'booked_date',
                                        
                );
           
            $excel_sheet_properties = array(
                'title' => 'All_Booking_BusReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'All_Booking_BusReport',
                'sheet_title' => 'All_Booking_BusReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $excel=$this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties,'F');
            // echo BASEPATH;exit;
            $file_path=BASEPATH.'reportexcel/'.$excel;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
			  // $mail_template="vvvvvxdxd";
			       
			   $message = '
			   <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
			  
			  $emil=$this->input->post('email');
    	
        	$emails=explode(",",$emil);
    	 
        	foreach ($emails as $key => $value) {
        		
			$res = $this->provab_mailer->send_mail($value, 'Bus All Booking Report', $message,$file_path);
			
        	}
        	unlink($file_path);
        	redirect($_SERVER['HTTP_REFERER']);




        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            
               $headings = array("Sl. No.","app_reference","Lead Pax Name","Lead Pax Email","Lead Pax Phone","Pnr","operator","From","To","Seat Type","commision Fare","commission","Tds","NetFare","convinence_amount","Markup","GST","Discount","Total Fare","Travel date","Status","Booked On"); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
           $csv= $this->provab_csv->csv_export($headings,'All_Booking_BusReport', $export_data,'F');
           $file_path=BASEPATH.'reportcsv/'.$csv;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
			  // $mail_template="vvvvvxdxd";
			       
			   $message = '
			   <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
			  
			  $emil=$this->input->post('email');
    	
        	$emails=explode(",",$emil);
    	 
        	foreach ($emails as $key => $value) {
        		
			$res = $this->provab_mailer->send_mail($value, 'All_Booking_BusReport', $message,$file_path);
			
        	}
        	unlink($file_path);
        	redirect($_SERVER['HTTP_REFERER']);
           
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$bus_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2c_report_bus_all_pdf',$pdf_data);
  			$pdf=$this->provab_pdf->create_pdf($mail_template,'F');
  			 $message="";
			  // $mail_template="vvvvvxdxd";
			       
			   $message = '
			   <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
			  
			  $emil=$this->input->post('email');
    	
        	$emails=explode(",",$emil);
    	 
        	foreach ($emails as $key => $value) {
        		
			$res = $this->provab_mailer->send_mail($value, 'Bus All Booking Report', $message,$pdf);
			
        	}
        	// unlink($file_path);
        	redirect($_SERVER['HTTP_REFERER']);
  			


        } 
    }
    public function export_confirmed_booking_bus_report_email($op = '') {
        $this->load->model('bus_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('bus');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CONFIRMED'));

        $bus_booking_data = $this->bus_model->b2c_bus_report($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $bus_booking_data = $this->booking_data_formatter->format_bus_booking_data($bus_booking_data, $this->current_module);
        $bus_booking_data = $bus_booking_data['data']['booking_details'];



        $export_data = array();
        //debug($bus_booking_data);exit;
        $i=1;
        foreach ($bus_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['Pnr'] = $v['pnr'];
            $export_data[$k]['operator'] = $v['operator'];
            $export_data[$k]['from'] = $v['departure_from'];
            $export_data[$k]['to'] = $v['arrival_to'];
           	$export_data[$k]['bus_type'] = $v['bus_type'];
           	$export_data[$k]['Comm.Fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['admin_commission'];
           	$export_data[$k]['TDS'] = $v['admin_tds'];
           	$export_data[$k]['NetFare'] = $v['admin_buying_price'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['Markup'] = $v['admin_markup'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['Discount'] = $v['discount'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	$export_data[$k]['Travel date'] = $v['journey_datetime'];
           	$export_data[$k]['booked_date'] = date('d-m-Y', strtotime($v['booked_date']));
           	        		
           	
        }
        //debug($export_data[$k]['Payment Status']);exit;
        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'app_reference',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'Pnr',
                    'g1' => 'operator',
                    'h1' => 'From',
                    'i1' => 'To',
                    'j1' => 'Seat Type',
                    'k1' => 'commision Fare',
                    'l1' => 'commission',
                    'm1' => 'Tds',
					'n1' => 'Net Fare',
                    'o1' => 'Conivence Fee',
                    'p1' => 'Markup',
                    'q1' => 'GST',
                    'r1' => 'Discount',
                    's1' => 'Total Fare',
                    't1' => 'Travel date',
                    'u1' => 'Booked On',
                   
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'Pnr',
                    'g' => 'operator',
                    'h' => 'from',
                    'i' => 'to',
                    'j' => 'bus_type',
                    'k' => 'Comm.Fare',
                    'l' => 'commission',
                    'm' => 'TDS',
                    'n' => 'NetFare',
                    'o' => 'convinence_amount',
                    'p' => 'Markup',
                    'q' => 'gst',
                    'r' => 'Discount',
                    's' => 'grand_total',
                  	't' => 'Travel date',
                    'u' => 'booked_date',
                                        
                );
           
            $excel_sheet_properties = array(
                'title' => 'Confirmed_Booking_BusReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Confirmed_Booking_BusReport',
                'sheet_title' => 'Confirmed_Booking_BusReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $excel=$this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties,'F');
            // echo BASEPATH;exit;
            $file_path=BASEPATH.'reportexcel/'.$excel;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
			  // $mail_template="vvvvvxdxd";
			       
			   $message = '
			   <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
			  
			  $emil=$this->input->post('email');
    	
        	$emails=explode(",",$emil);
    	 
        	foreach ($emails as $key => $value) {
        		
			$res = $this->provab_mailer->send_mail($value, 'Bus Confirmed Booking Report', $message,$file_path);
			
        	}
        	unlink($file_path);
        	redirect($_SERVER['HTTP_REFERER']);




        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            
               $headings = array("Sl. No.","app_reference","Lead Pax Name","Lead Pax Email","Lead Pax Phone","Pnr","operator","From","To","Seat Type","commision Fare","commission","Tds","NetFare","convinence_amount","Markup","GST","Discount","Total Fare","Travel date","Booked On"); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
           $csv= $this->provab_csv->csv_export($headings,'Confirmed_Booking_BusReport', $export_data,'F');
           $file_path=BASEPATH.'reportcsv/'.$csv;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
			  // $mail_template="vvvvvxdxd";
			       
			   $message = '
			   <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
			  
			  $emil=$this->input->post('email');
    	
        	$emails=explode(",",$emil);
    	 
        	foreach ($emails as $key => $value) {
        		
			$res = $this->provab_mailer->send_mail($value, 'Confirmed_Booking_BusReport', $message,$file_path);
			
        	}
        	unlink($file_path);
        	redirect($_SERVER['HTTP_REFERER']);
           
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$bus_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2c_report_bus_pdf',$pdf_data);
  			$pdf=$this->provab_pdf->create_pdf($mail_template,'F');
  			 $message="";
			  // $mail_template="vvvvvxdxd";
			       
			   $message = '
			   <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
			  
			  $emil=$this->input->post('email');
    	
        	$emails=explode(",",$emil);
    	 
        	foreach ($emails as $key => $value) {
        		
			$res = $this->provab_mailer->send_mail($value, 'Bus Confirmed Booking Report', $message,$pdf);
			
        	}
        	// unlink($file_path);
        	redirect($_SERVER['HTTP_REFERER']);
  			


        } 
    }
    public function export_cancelled_booking_bus_report($op = '') {
        $this->load->model('bus_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('bus');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CANCELLED'));

        $bus_booking_data = $this->bus_model->b2c_bus_report($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $bus_booking_data = $this->booking_data_formatter->format_bus_booking_data($bus_booking_data, $this->current_module);
        $bus_booking_data = $bus_booking_data['data']['booking_details'];



        $export_data = array();
        //debug($bus_booking_data);exit;
        $i=1;
        foreach ($bus_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['Pnr'] = $v['pnr'];
            $export_data[$k]['operator'] = $v['operator'];
            $export_data[$k]['from'] = $v['departure_from'];
            $export_data[$k]['to'] = $v['arrival_to'];
           	$export_data[$k]['bus_type'] = $v['bus_type'];
           	$export_data[$k]['Comm.Fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['admin_commission'];
           	$export_data[$k]['TDS'] = $v['admin_tds'];
           	$export_data[$k]['NetFare'] = $v['admin_buying_price'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['Markup'] = $v['admin_markup'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['Discount'] = $v['discount'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	$export_data[$k]['Travel date'] = $v['journey_datetime'];
           	$export_data[$k]['booked_date'] = date('d-m-Y', strtotime($v['booked_date']));
           	        		
           	
        }
        //debug($export_data[$k]['Payment Status']);exit;
        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'app_reference',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'Pnr',
                    'g1' => 'operator',
                    'h1' => 'From',
                    'i1' => 'To',
                    'j1' => 'Seat Type',
                    'k1' => 'commision Fare',
                    'l1' => 'commission',
                    'm1' => 'Tds',
					'n1' => 'Net Fare',
                    'o1' => 'Conivence Fee',
                    'p1' => 'Markup',
                    'q1' => 'GST',
                    'r1' => 'Discount',
                    's1' => 'Total Fare',
                    't1' => 'Travel date',
                    'u1' => 'Booked On',
                   
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'Pnr',
                    'g' => 'operator',
                    'h' => 'from',
                    'i' => 'to',
                    'j' => 'bus_type',
                    'k' => 'Comm.Fare',
                    'l' => 'commission',
                    'm' => 'TDS',
                    'n' => 'NetFare',
                    'o' => 'convinence_amount',
                    'p' => 'Markup',
                    'q' => 'gst',
                    'r' => 'Discount',
                    's' => 'grand_total',
                  	't' => 'Travel date',
                    'u' => 'booked_date',
                                        
                );
           
            $excel_sheet_properties = array(
                'title' => 'Cancelled_Booking_BusReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Cancelled_Booking_BusReport',
                'sheet_title' => 'Cancelled_Booking_BusReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            
               $headings = array("Sl. No.","app_reference","Lead Pax Name","Lead Pax Email","Lead Pax Phone","Pnr","operator","From","To","Seat Type","commision Fare","commission","Tds","NetFare","convinence_amount","Markup","GST","Discount","Total Fare","Travel date","Booked On"); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
           $this->provab_csv->csv_export($headings,'Cancelled_Booking_BusReport', $export_data);
           
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$bus_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2c_report_bus_pdf',$pdf_data);
  			$this->provab_pdf->create_pdf($mail_template);
  			


        }
    }
    public function export_cancelled_booking_bus_report_email($op = '') {
        $this->load->model('bus_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('bus');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CANCELLED'));

        $bus_booking_data = $this->bus_model->b2c_bus_report($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $bus_booking_data = $this->booking_data_formatter->format_bus_booking_data($bus_booking_data, $this->current_module);
        $bus_booking_data = $bus_booking_data['data']['booking_details'];



        $export_data = array();
        //debug($bus_booking_data);exit;
        $i=1;
        foreach ($bus_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['Pnr'] = $v['pnr'];
            $export_data[$k]['operator'] = $v['operator'];
            $export_data[$k]['from'] = $v['departure_from'];
            $export_data[$k]['to'] = $v['arrival_to'];
           	$export_data[$k]['bus_type'] = $v['bus_type'];
           	$export_data[$k]['Comm.Fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['admin_commission'];
           	$export_data[$k]['TDS'] = $v['admin_tds'];
           	$export_data[$k]['NetFare'] = $v['admin_buying_price'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['Markup'] = $v['admin_markup'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['Discount'] = $v['discount'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	$export_data[$k]['Travel date'] = $v['journey_datetime'];
           	$export_data[$k]['booked_date'] = date('d-m-Y', strtotime($v['booked_date']));
           	        		
           	
        }
        //debug($export_data[$k]['Payment Status']);exit;
       if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'app_reference',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'Pnr',
                    'g1' => 'operator',
                    'h1' => 'From',
                    'i1' => 'To',
                    'j1' => 'Seat Type',
                    'k1' => 'commision Fare',
                    'l1' => 'commission',
                    'm1' => 'Tds',
					'n1' => 'Net Fare',
                    'o1' => 'Conivence Fee',
                    'p1' => 'Markup',
                    'q1' => 'GST',
                    'r1' => 'Discount',
                    's1' => 'Total Fare',
                    't1' => 'Travel date',
                    'u1' => 'Booked On',
                   
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'Pnr',
                    'g' => 'operator',
                    'h' => 'from',
                    'i' => 'to',
                    'j' => 'bus_type',
                    'k' => 'Comm.Fare',
                    'l' => 'commission',
                    'm' => 'TDS',
                    'n' => 'NetFare',
                    'o' => 'convinence_amount',
                    'p' => 'Markup',
                    'q' => 'gst',
                    'r' => 'Discount',
                    's' => 'grand_total',
                  	't' => 'Travel date',
                    'u' => 'booked_date',
                                        
                );
           
            $excel_sheet_properties = array(
                'title' => 'Confirmed_Booking_BusReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Cancelled_Booking_BusReport',
                'sheet_title' => 'Cancelled_Booking_BusReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $excel=$this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties,'F');
            // echo BASEPATH;exit;
            $file_path=BASEPATH.'reportexcel/'.$excel;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
			  // $mail_template="vvvvvxdxd";
			       
			   $message = '
			   <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
			  
			  $emil=$this->input->post('email');
    	
        	$emails=explode(",",$emil);
    	 
        	foreach ($emails as $key => $value) {
        		
			$res = $this->provab_mailer->send_mail($value, 'Bus Cancelled Booking Report', $message,$file_path);
			
        	}
        	unlink($file_path);
        	redirect($_SERVER['HTTP_REFERER']);




        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            
               $headings = array("Sl. No.","app_reference","Lead Pax Name","Lead Pax Email","Lead Pax Phone","Pnr","operator","From","To","Seat Type","commision Fare","commission","Tds","NetFare","convinence_amount","Markup","GST","Discount","Total Fare","Travel date","Booked On"); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
           $csv= $this->provab_csv->csv_export($headings,'Cancelled_Booking_BusReport', $export_data,'F');
           $file_path=BASEPATH.'reportcsv/'.$csv;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
			  // $mail_template="vvvvvxdxd";
			       
			   $message = '
			   <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
			  
			  $emil=$this->input->post('email');
    	
        	$emails=explode(",",$emil);
    	 
        	foreach ($emails as $key => $value) {
        		
			$res = $this->provab_mailer->send_mail($value, 'Cancelled_Booking_BusReport', $message,$file_path);
			
        	}
        	unlink($file_path);
        	redirect($_SERVER['HTTP_REFERER']);
           
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$bus_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2c_report_bus_pdf',$pdf_data);
  			$pdf=$this->provab_pdf->create_pdf($mail_template,'F');
  			 $message="";
			  // $mail_template="vvvvvxdxd";
			       
			   $message = '
			   <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
			  
			  $emil=$this->input->post('email');
    	
        	$emails=explode(",",$emil);
    	 
        	foreach ($emails as $key => $value) {
        		
			$res = $this->provab_mailer->send_mail($value, 'Bus Cancelled Booking Report', $message,$pdf);
			
        	}
        	// unlink($file_path);
        	redirect($_SERVER['HTTP_REFERER']);
  			


        }
    }
	public function export_confirmed_booking_bus_report_b2b($op = '',$all='') {
        $this->load->model('bus_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('bus');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        if($all=="all"){

        	$condition[] = array();
        }
        else
        {

        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CONFIRMED'));
        }

        $bus_booking_data = $this->bus_model->b2b_bus_report($condition, false, 0, 2000);
         //Maximum 500 Data Can be exported at time
        $bus_booking_data = $this->booking_data_formatter->format_bus_booking_data($bus_booking_data, $this->current_module);
        $bus_booking_data = $bus_booking_data['data']['booking_details'];



        $export_data = array();
        // debug($bus_booking_data);exit;
        $i=1;
        foreach ($bus_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['Pnr'] = $v['pnr'];
            $export_data[$k]['operator'] = $v['operator'];
            $export_data[$k]['from'] = $v['departure_from'];
            $export_data[$k]['to'] = $v['arrival_to'];
           	$export_data[$k]['bus_type'] = $v['bus_type'];
           	$export_data[$k]['Comm.Fare'] = $v['fare'];
           	$export_data[$k]['Netfare'] = $v['admin_buying_price'];
           	$export_data[$k]['admin_markup'] = $v['admin_markup'];
           	$export_data[$k]['agent_markup'] = $v['agent_markup'];
           	$export_data[$k]['admin_commission'] = $v['admin_commission'];
           	$export_data[$k]['agent_commission'] = $v['agent_commission'];
           	$export_data[$k]['admin_tds'] = $v['admin_tds'];
           	$export_data[$k]['agent_tds'] = $v['agent_tds'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['Price Deducted From Agent'] = $v['agent_buying_price'];
           	$export_data[$k]['grand_total'] = $v['grand_total']; 
           	if($all=="all"){

	        	$export_data[$k]['status'] = $v['status']; 
	        }         
           	$export_data[$k]['booked_date'] = date('d-m-Y', strtotime($v['booked_date']));
           	        		
           	
        }
        //debug($export_data[$k]['Payment Status']);exit;
        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'app_reference',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'Pnr',
                    'g1' => 'operator',
                    'h1' => 'From',
                    'i1' => 'To',
                    'j1' => 'Seat Type',
                    'k1' => 'commision Fare',
                    'l1' => 'Netfare',
                    'm1' => 'Admin_markup',
					'n1' => 'Agent_markup',
                    'o1' => 'Admin_tds',
                    'p1' => 'Agent_tds',
                    'q1' => 'Admin_commission',
                    'r1' => 'Agent_commission',
                    's1' => 'Gst',
                    't1' => 'Price Deducted From Agent',
                    'u1' => 'Total Price',
                   
                );
           		if($all=="all"){

		        	$headings['v1'] = 'status'; 
		        	$headings['x1'] = 'Booked On'; 
		        }
		        else{
		        	$headings['v1'] = 'Booked On'; 
		        } 
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'Pnr',
                    'g' => 'operator',
                    'h' => 'from',
                    'i' => 'to',
                    'j' => 'bus_type',
                    'k' => 'Comm.Fare',
                    'l' => 'Netfare',
                    'm' => 'admin_markup',
                    'n' => 'agent_markup',
                    'o' => 'admin_tds',
                    'p' => 'agent_tds',
                    'q' => 'admin_commission',
                    'r' => 'agent_commission',
                    's' => 'gst',
                  	't' => 'Price Deducted From Agent',
                    'u' => 'grand_total',
                    
                                        
                );
           		if($all=="all"){

		        	$fields['v'] = 'status'; 
		        	$fields['x'] = 'Booked On'; 
		        	 $excel_sheet_properties = array(
		                'title' => 'All_Booking_BusReport_' . date('d-M-Y'),
		                'creator' => 'Accentria Solutions',
		                'description' => 'All_Booking_BusReport',
		                'sheet_title' => 'All_Booking_BusReport'
		            );
		        }
		        else{
		        	$fields['v'] = 'Booked On'; 
		        	 $excel_sheet_properties = array(
		                'title' => 'Confirmed_Booking_BusReport_' . date('d-M-Y'),
		                'creator' => 'Accentria Solutions',
		                'description' => 'Confirmed_Booking_BusReport',
		                'sheet_title' => 'Confirmed_Booking_BusReport'
		            );
		        }
           

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            	if($all=="all"){

		        	$headings = array("Sl. No.","app_reference","Lead Pax Name","Lead Pax Email","Lead Pax Phone","Pnr","operator","From","To","Seat Type","commision Fare","Netfare","Admin_markup","Agent_markup","Admin_tds","Agent_tds","Admin_commission","Agent_commission","Gst","Price Deducted From Agent","Total Price","Status","Booked On");
		        }
		        else{
		        	$headings = array("Sl. No.","app_reference","Lead Pax Name","Lead Pax Email","Lead Pax Phone","Pnr","operator","From","To","Seat Type","commision Fare","Netfare","Admin_markup","Agent_markup","Admin_tds","Agent_tds","Admin_commission","Agent_commission","Gst","Price Deducted From Agent","Total Price","Booked On");
		        }
                
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
            if($all=="all"){

		        	 $this->provab_csv->csv_export($headings,'All_Booking_BusReport', $export_data);
		        }
		        else{
		        	 $this->provab_csv->csv_export($headings,'Confirmed_Booking_BusReport', $export_data);
		        }
          
           
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$bus_booking_data;
  			$pdf_data['record_type']=$all;
  			// debug($pdf_data['export_data']);exit;

  			$mail_template =$this->template->isolated_view('report/b2b_report_bus_pdf',$pdf_data);
  			$this->provab_pdf->create_pdf($mail_template);
  			


        }
        
        
        

    }
    public function export_confirmed_booking_bus_report_b2b_email($op = '',$all='') {
        $this->load->model('bus_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('bus');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        if($all=="all"){

		        	 $condition[] = array();
		        }
		        else{
		        	 $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CONFIRMED'));
		        }
        

        $bus_booking_data = $this->bus_model->b2b_bus_report($condition, false, 0, 2000);
         //Maximum 500 Data Can be exported at time
        $bus_booking_data = $this->booking_data_formatter->format_bus_booking_data($bus_booking_data, $this->current_module);
        $bus_booking_data = $bus_booking_data['data']['booking_details'];



        $export_data = array();
        //debug($bus_booking_data);exit;
        $i=1;
        foreach ($bus_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['Pnr'] = $v['pnr'];
            $export_data[$k]['operator'] = $v['operator'];
            $export_data[$k]['from'] = $v['departure_from'];
            $export_data[$k]['to'] = $v['arrival_to'];
           	$export_data[$k]['bus_type'] = $v['bus_type'];
           	$export_data[$k]['Comm.Fare'] = $v['fare'];
           	$export_data[$k]['Netfare'] = $v['admin_buying_price'];
           	$export_data[$k]['admin_markup'] = $v['admin_markup'];
           	$export_data[$k]['agent_markup'] = $v['agent_markup'];
           	$export_data[$k]['admin_commission'] = $v['admin_commission'];
           	$export_data[$k]['agent_commission'] = $v['agent_commission'];
           	$export_data[$k]['admin_tds'] = $v['admin_tds'];
           	$export_data[$k]['agent_tds'] = $v['agent_tds'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['Price Deducted From Agent'] = $v['agent_buying_price'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	if($all=="all"){

		        	$export_data[$k]['status'] = $v['status'];
		        }          
           	          
           	$export_data[$k]['booked_date'] = date('d-m-Y', strtotime($v['booked_date']));
           	        		
           	
        }
        //debug($export_data[$k]['Payment Status']);exit;
        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'app_reference',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'Pnr',
                    'g1' => 'operator',
                    'h1' => 'From',
                    'i1' => 'To',
                    'j1' => 'Seat Type',
                    'k1' => 'commision Fare',
                    'l1' => 'Netfare',
                    'm1' => 'Admin_markup',
					'n1' => 'Agent_markup',
                    'o1' => 'Admin_tds',
                    'p1' => 'Agent_tds',
                    'q1' => 'Admin_commission',
                    'r1' => 'Agent_commission',
                    's1' => 'Gst',
                    't1' => 'Price Deducted From Agent',
                    'u1' => 'Total Price',
                   
                );
           if($all=="all"){

		        	$headings['v1'] = 'status'; 
		        	$headings['x1'] = 'Booked On'; 
		        }
		        else{
		        	$headings['v1'] = 'Booked On'; 
		        }

                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'Pnr',
                    'g' => 'operator',
                    'h' => 'from',
                    'i' => 'to',
                    'j' => 'bus_type',
                    'k' => 'Comm.Fare',
                    'l' => 'Netfare',
                    'm' => 'admin_markup',
                    'n' => 'agent_markup',
                    'o' => 'admin_tds',
                    'p' => 'agent_tds',
                    'q' => 'admin_commission',
                    'r' => 'agent_commission',
                    's' => 'gst',
                  	't' => 'Price Deducted From Agent',
                    'u' => 'grand_total',
                    
                                        
                );
           
            if($all=="all"){

		        	$fields['v'] = 'status'; 
		        	$fields['x'] = 'Booked On'; 
		        	 $excel_sheet_properties = array(
		                'title' => 'All_Booking_BusReport_' . date('d-M-Y'),
		                'creator' => 'Accentria Solutions',
		                'description' => 'All_Booking_BusReport',
		                'sheet_title' => 'All_Booking_BusReport'
		            );
		        }
		        else{
		        	$fields['v'] = 'Booked On'; 
		        	 $excel_sheet_properties = array(
		                'title' => 'Confirmed_Booking_BusReport_' . date('d-M-Y'),
		                'creator' => 'Accentria Solutions',
		                'description' => 'Confirmed_Booking_BusReport',
		                'sheet_title' => 'Confirmed_Booking_BusReport'
		            );
		        }

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $excel=$this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties,'F');
            // echo BASEPATH;exit;
            $file_path=BASEPATH.'reportexcel/'.$excel;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
			  // $mail_template="vvvvvxdxd";
			       
			   $message = '
			   <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
			  
			  $emil=$this->input->post('email');
    	
        	$emails=explode(",",$emil);
    	 
        	foreach ($emails as $key => $value) {
        		if($all=="all"){

		        	$res = $this->provab_mailer->send_mail($value, 'Bus All Booking Report', $message,$file_path);
		        }
		        else{
		        	$res = $this->provab_mailer->send_mail($value, 'Bus Confirmed Booking Report', $message,$file_path);
		        }
			
			
        	}
        	unlink($file_path);
        	redirect($_SERVER['HTTP_REFERER']);
        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            
               if($all=="all"){

		        	$headings = array("Sl. No.","app_reference","Lead Pax Name","Lead Pax Email","Lead Pax Phone","Pnr","operator","From","To","Seat Type","commision Fare","Netfare","Admin_markup","Agent_markup","Admin_tds","Agent_tds","Admin_commission","Agent_commission","Gst","Price Deducted From Agent","Total Price","Status","Booked On");
		        }
		        else{
		        	$headings = array("Sl. No.","app_reference","Lead Pax Name","Lead Pax Email","Lead Pax Phone","Pnr","operator","From","To","Seat Type","commision Fare","Netfare","Admin_markup","Agent_markup","Admin_tds","Agent_tds","Admin_commission","Agent_commission","Gst","Price Deducted From Agent","Total Price","Booked On");
		        }
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
          $csv= $this->provab_csv->csv_export($headings,'Confirmed_Booking_BusReport', $export_data,'F');
           $file_path=BASEPATH.'reportcsv/'.$csv;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
			  // $mail_template="vvvvvxdxd";
			       
			   $message = '
			   <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
			  
			  $emil=$this->input->post('email');
    	
        	$emails=explode(",",$emil);
    	 
        	foreach ($emails as $key => $value) {
        		if($all=="all")
        		{

		        	$res = $this->provab_mailer->send_mail($value, 'All_Booking_BusReport', $message,$file_path);
		        }
		        else
		        {
		        	$res = $this->provab_mailer->send_mail($value, 'All_Booking_BusReport', $message,$file_path);
		        }
			
			
        	}
        	unlink($file_path);
        	redirect($_SERVER['HTTP_REFERER']);
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$bus_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2b_report_bus_pdf',$pdf_data);
  			$pdf=$this->provab_pdf->create_pdf($mail_template,'F');
  			 $message="";
			  // $mail_template="vvvvvxdxd";
			       
			   $message = '
			   <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
			  
			  $emil=$this->input->post('email');
    	
        	$emails=explode(",",$emil);
    	 
        	foreach ($emails as $key => $value) {
        		
			
				if($all=="all")
        		{

		        	$res = $this->provab_mailer->send_mail($value, 'Bus All Booking Report', $message,$pdf);
		        }
		        else
		        {
		        	$res = $this->provab_mailer->send_mail($value, 'Bus Confirmed Booking Report', $message,$pdf);
		        }
        	}
        	// unlink($file_path);
        	redirect($_SERVER['HTTP_REFERER']);
  			


        }
        
        
        

    }
    public function export_cancelled_booking_bus_report_b2b($op = '') {
        $this->load->model('bus_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('bus');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CANCELLED'));

        $bus_booking_data = $this->bus_model->b2b_bus_report($condition, false, 0, 2000);
         //Maximum 500 Data Can be exported at time
        $bus_booking_data = $this->booking_data_formatter->format_bus_booking_data($bus_booking_data, $this->current_module);
        $bus_booking_data = $bus_booking_data['data']['booking_details'];



        $export_data = array();
        //debug($bus_booking_data);exit;
        $i=1;
        foreach ($bus_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['Pnr'] = $v['pnr'];
            $export_data[$k]['operator'] = $v['operator'];
            $export_data[$k]['from'] = $v['departure_from'];
            $export_data[$k]['to'] = $v['arrival_to'];
           	$export_data[$k]['bus_type'] = $v['bus_type'];
           	$export_data[$k]['Comm.Fare'] = $v['fare'];
           	$export_data[$k]['Netfare'] = $v['admin_buying_price'];
           	$export_data[$k]['admin_markup'] = $v['admin_markup'];
           	$export_data[$k]['agent_markup'] = $v['agent_markup'];
           	$export_data[$k]['admin_commission'] = $v['admin_commission'];
           	$export_data[$k]['agent_commission'] = $v['agent_commission'];
           	$export_data[$k]['admin_tds'] = $v['admin_tds'];
           	$export_data[$k]['agent_tds'] = $v['agent_tds'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['Price Deducted From Agent'] = $v['agent_buying_price'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];          
           	$export_data[$k]['booked_date'] = date('d-m-Y', strtotime($v['booked_date']));
           	        		
           	
        }
      	//debug($export_data[$k]['Payment Status']);exit;
        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'app_reference',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'Pnr',
                    'g1' => 'operator',
                    'h1' => 'From',
                    'i1' => 'To',
                    'j1' => 'Seat Type',
                    'k1' => 'commision Fare',
                    'l1' => 'Netfare',
                    'm1' => 'Admin_markup',
					'n1' => 'Agent_markup',
                    'o1' => 'Admin_tds',
                    'p1' => 'Agent_tds',
                    'q1' => 'Admin_commission',
                    'r1' => 'Agent_commission',
                    's1' => 'Gst',
                    't1' => 'Price Deducted From Agent',
                    'u1' => 'Total Price',
                   'v1' => 'Booked On',
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'Pnr',
                    'g' => 'operator',
                    'h' => 'from',
                    'i' => 'to',
                    'j' => 'bus_type',
                    'k' => 'Comm.Fare',
                    'l' => 'Netfare',
                    'm' => 'admin_markup',
                    'n' => 'agent_markup',
                    'o' => 'admin_tds',
                    'p' => 'agent_tds',
                    'q' => 'admin_commission',
                    'r' => 'agent_commission',
                    's' => 'gst',
                  	't' => 'Price Deducted From Agent',
                    'u' => 'grand_total',
                    'v' => 'booked_date',
                                        
                );
           
            $excel_sheet_properties = array(
                'title' => 'Cancelled_Booking_BusReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Cancelled_Booking_BusReport',
                'sheet_title' => 'Cancelled_Booking_BusReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            
               $headings = array("Sl. No.","app_reference","Lead Pax Name","Lead Pax Email","Lead Pax Phone","Pnr","operator","From","To","Seat Type","commision Fare","Netfare","Admin_markup","Agent_markup","Admin_tds","Agent_tds","Admin_commission","Agent_commission","Gst","Price Deducted From Agent","Total Price","Booked On"); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
           $this->provab_csv->csv_export($headings,'Cancelled_Booking_BusReport', $export_data);
           
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$bus_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2b_report_bus_pdf',$pdf_data);
  			$this->provab_pdf->create_pdf($mail_template);
  			


        }
    }
    public function export_cancelled_booking_bus_report_b2b_email($op = '') {
        $this->load->model('bus_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('bus');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CANCELLED'));

        $bus_booking_data = $this->bus_model->b2b_bus_report($condition, false, 0, 2000);
         //Maximum 500 Data Can be exported at time
        $bus_booking_data = $this->booking_data_formatter->format_bus_booking_data($bus_booking_data, $this->current_module);
        $bus_booking_data = $bus_booking_data['data']['booking_details'];



        $export_data = array();
        //debug($bus_booking_data);exit;
        $i=1;
        foreach ($bus_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['Pnr'] = $v['pnr'];
            $export_data[$k]['operator'] = $v['operator'];
            $export_data[$k]['from'] = $v['departure_from'];
            $export_data[$k]['to'] = $v['arrival_to'];
           	$export_data[$k]['bus_type'] = $v['bus_type'];
           	$export_data[$k]['Comm.Fare'] = $v['fare'];
           	$export_data[$k]['Netfare'] = $v['admin_buying_price'];
           	$export_data[$k]['admin_markup'] = $v['admin_markup'];
           	$export_data[$k]['agent_markup'] = $v['agent_markup'];
           	$export_data[$k]['admin_commission'] = $v['admin_commission'];
           	$export_data[$k]['agent_commission'] = $v['agent_commission'];
           	$export_data[$k]['admin_tds'] = $v['admin_tds'];
           	$export_data[$k]['agent_tds'] = $v['agent_tds'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['Price Deducted From Agent'] = $v['agent_buying_price'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];          
           	$export_data[$k]['booked_date'] = date('d-m-Y', strtotime($v['booked_date']));
           	        		
           	
        }
      	//debug($export_data[$k]['Payment Status']);exit;
        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'app_reference',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'Pnr',
                    'g1' => 'operator',
                    'h1' => 'From',
                    'i1' => 'To',
                    'j1' => 'Seat Type',
                    'k1' => 'commision Fare',
                    'l1' => 'Netfare',
                    'm1' => 'Admin_markup',
					'n1' => 'Agent_markup',
                    'o1' => 'Admin_tds',
                    'p1' => 'Agent_tds',
                    'q1' => 'Admin_commission',
                    'r1' => 'Agent_commission',
                    's1' => 'Gst',
                    't1' => 'Price Deducted From Agent',
                    'u1' => 'Total Price',
                   'v1' => 'Booked On',
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'Pnr',
                    'g' => 'operator',
                    'h' => 'from',
                    'i' => 'to',
                    'j' => 'bus_type',
                    'k' => 'Comm.Fare',
                    'l' => 'Netfare',
                    'm' => 'admin_markup',
                    'n' => 'agent_markup',
                    'o' => 'admin_tds',
                    'p' => 'agent_tds',
                    'q' => 'admin_commission',
                    'r' => 'agent_commission',
                    's' => 'gst',
                  	't' => 'Price Deducted From Agent',
                    'u' => 'grand_total',
                    'v' => 'booked_date',
                                        
                );
           
            $excel_sheet_properties = array(
                'title' => 'Cancelled_Booking_BusReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Cancelled_Booking_BusReport',
                'sheet_title' => 'Cancelled_Booking_BusReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
             $excel=$this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties,'F');
            // echo BASEPATH;exit;
            $file_path=BASEPATH.'reportexcel/'.$excel;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
			  // $mail_template="vvvvvxdxd";
			       
			   $message = '
			   <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
			  
			  $emil=$this->input->post('email');
    	
        	$emails=explode(",",$emil);
    	 
        	foreach ($emails as $key => $value) {
        		
			$res = $this->provab_mailer->send_mail($value, 'Bus Cancelled Booking Report', $message,$file_path);
			
        	}
        	unlink($file_path);
        	redirect($_SERVER['HTTP_REFERER']);
        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            
               $headings = array("Sl. No.","app_reference","Lead Pax Name","Lead Pax Email","Lead Pax Phone","Pnr","operator","From","To","Seat Type","commision Fare","Netfare","Admin_markup","Agent_markup","Admin_tds","Agent_tds","Admin_commission","Agent_commission","Gst","Price Deducted From Agent","Total Price","Booked On"); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
           $csv= $this->provab_csv->csv_export($headings,'Cancelled_Booking_BusReport', $export_data,'F');
           $file_path=BASEPATH.'reportcsv/'.$csv;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
			  // $mail_template="vvvvvxdxd";
			       
			   $message = '
			   <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
			  
			  $emil=$this->input->post('email');
    	
        	$emails=explode(",",$emil);
    	 
        	foreach ($emails as $key => $value) {
        		
			$res = $this->provab_mailer->send_mail($value, 'Cancelled_Booking_BusReport', $message,$file_path);
			
        	}
        	unlink($file_path);
        	redirect($_SERVER['HTTP_REFERER']);
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$bus_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2b_report_bus_pdf',$pdf_data);
  			$pdf=$this->provab_pdf->create_pdf($mail_template,'F');
  			 $message="";
			  // $mail_template="vvvvvxdxd";
			       
			   $message = '
			   <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
			  
			  $emil=$this->input->post('email');
    	
        	$emails=explode(",",$emil);
    	 
        	foreach ($emails as $key => $value) {
        		
			$res = $this->provab_mailer->send_mail($value, 'Bus Cancelled Booking Report', $message,$pdf);
			
        	}
        	// unlink($file_path);
        	redirect($_SERVER['HTTP_REFERER']);
  			


        }
    }
	function b2c_holiday_report($offset=0)
	{
		// error_reporting(E_ALL);
		// debug($module_type);exit;
		
		$condition = array ();
		$get_data = $this->input->get ();
		
		/*if (! (isset ( $get_data ['created_datetime_from'] ) || isset ( $get_data ['created_datetime_to'] ))) {
			$get_data ['created_datetime_from'] = date ( 'd-m-Y' );
			$get_data ['created_datetime_to'] = date ( 'd-m-Y' );
		}*/		
		if (valid_array ( $get_data ) == true) {
			$from_date = trim ( @$get_data ['created_datetime_from'] );
			$to_date = trim ( @$get_data ['created_datetime_to'] );
			if (empty ( $from_date ) == false && empty ( $to_date ) == false) {
				$valid_dates = auto_swipe_dates ( $from_date, $to_date );
				$from_date = $valid_dates ['from_date'];
				$to_date = $valid_dates ['to_date'];
			}
			if (empty ( $from_date ) == false) {
				$condition [] = array (
						'BD.created_datetime',
						'>=',
						$this->db->escape ( db_current_datetime ( $from_date . ' 00:00:00' ) ) 
				);
			}
			if (empty ( $to_date ) == false) {
				$condition [] = array (
						'BD.created_datetime',
						'<=',
						$this->db->escape ( db_current_datetime ( $to_date . ' 23:59:59' ) ) 
				);
			}
			if (empty ( $get_data ['status'] ) == false && strtolower ( $get_data ['status'] ) != 'all') {
				$condition [] = array (
						'BD.status',
						'=',
						$this->db->escape ( $get_data ['status'] ) 
				);
			}			
			if (empty ( $get_data ['phone'] ) == false) {
				$condition [] = array (
						'BD.phone_number',
						' like ',
						$this->db->escape ( '%' . trim ( $get_data ['phone'] ) . '%' ) 
				);
			}			
			if (empty ( $get_data ['email'] ) == false) {
				$condition [] = array (
						'BD.email',
						' like ',
						$this->db->escape ( '%' . trim ( $get_data ['email'] ) . '%' ) 
				);
			}			
			if (empty ( $get_data ['app_reference'] ) == false) {
				$condition [] = array (
						'BD.app_reference',
						' like ',
						$this->db->escape ( '%' . trim ( $get_data ['app_reference'] ) . '%' ) 
				);
			}
			$page_data ['from_date'] = $from_date;
			$page_data ['to_date'] = $to_date;
		}
		

		$condition [] = array(
			'BD.status ',
			'IN ',
			'("BOOKING_CONFIRMED","CANCELLED","CANCELLATION_IN_PROCESS")',
			);
		//	debug($condition);exit;
		/*if ($this->check_operation ( $offset )) {
			$op_data = $this->hotel_model->booking ( $condition );
			$op_data = $this->booking_data_formatter->format_hotel_booking_data ( $op_data, 'b2c' );
			$col = array (
					'app_reference' => 'Application Reference',
					'status' => 'Status',
					'confirmation_reference' => 'Confirmation Reference',
					'fare' => 'Fare',
					'grand_total' => 'Total Fare',
					'payment_mode' => 'Payment Mode',
					'voucher_date' => 'BookedOn' 
			);			
			$this->perform_operation ( $offset, $op_data ['data'] ['booking_details'], $col, 'Hotel Booking Report' );
		}*/

		$offset = intval ( $offset );		
		$this->load->model('tours_model');
		// debug($condition);exit;
		// error_reporting(E_ALL);
		$total_records = $this->tours_model->booking ( $condition, true );
		$table_data = $this->tours_model->booking ( $condition, false, $offset, RECORDS_RANGE_1 );
		
		// debug($table_data);exit;
		// echo "string";exit();

		 //debug(RECORDS_RANGE_5);exit();
		$page_data ['table_data'] = $table_data ['data'];
		$x = count ( $table_data );
		$this->load->library ( 'pagination' );
	
		if (count ( $_GET ) > 0)
			$config ['suffix'] = '?' . http_build_query ( $_GET, '', "&" );
		$config ['base_url'] = base_url () . 'index.php/report/b2c_holiday_report/';
		$config ['first_url'] = $config ['base_url'] . '?' . http_build_query ( $_GET );
		$page_data ['total_rows'] = $config ['total_rows'] = $total_records;
		$config ['per_page'] = RECORDS_RANGE_1;
		$this->pagination->initialize ( $config );

		/**
		 * TABLE PAGINATION
		 */
		$page_data ['total_records'] = $config ['total_rows'];
		$page_data ['search_params'] = $get_data;
		$page_data ['status_options'] = get_enum_list ( 'booking_status_options' );
		// echo "string";exit();
		// $page_data['active_column_list'] = $this->custom_db->single_table_records ( 'report_column_setting','column_name',array('module_name'=>'holiday','module_type'=>$module_type) )['data'];		
		$page_data['module_type'] = $module_type;
		// debug($page_data);die;
		// debug($page_data);exit;
		$this->template->view ( 'report/holiday', $page_data );
	}
	public function export_confirmed_booking_holiday_report($op = '',$all='') {
        $this->load->model('tours_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('flight');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        if($all=="all")
        {

        $condition[] = array();
        }
        else
        {

        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CONFIRMED'));
        }

		$holiday_booking_data = $this->tours_model->booking ( $condition, false, 0, 2000);
        // debug($holiday_booking_data);exit;
       
        // $flight_booking_data = $flight_booking_data['data']['booking_details'];

		$holiday_booking_data=$holiday_booking_data['data'];

        $export_data = array();
        // debug($holiday_booking_data);exit;
        $i=1;
        foreach ($holiday_booking_data as $k => $v) {
           // debug($v);exit;
           $pax_name="";
           	if($v['pax_details']){
                       foreach ($v['pax_details'] as  $value){ ?>

                        <?php

                         
                         $pax_name .= $value['pax_last_name']." ".$value['pax_first_name'];
                         
                      		}
                          
                         }

            $book_attr = json_decode($v['booking_details']['attributes'],TRUE);     
            $attributes=$v['booking_details']['attributes'];

            $attributes=json_decode($attributes,true);

            $aed_attributes=json_decode($v['booking_details']['aed_array']);
                         // debug($aed_attributes);exit;
                $base_fare=0.00;
                if(isset($aed_attributes->aed_basic_price))
                {
                    $base_fare=($aed_attributes->aed_basic_price);
                    $base_fare=str_replace(",", "", $base_fare);
                }
                

                 $markup=0.00;
                if(isset($aed_attributes->aed_markup))
                {
                    $markup=($aed_attributes->aed_markup);
                    $markup=str_replace(",", "", $markup);
                }

                $gst_value=0.00;
                if(isset($aed_attributes->aed_gst_value))
                {
                    $gst_value=($aed_attributes->aed_gst_value);
                    $gst_value=str_replace(",", "", $gst_value);
                }
                $discount_value=0.00;
                if(isset($aed_attributes->aed_discount))
                {
                    $discount_value=($aed_attributes->aed_discount);
                    $discount_value=str_replace(",", "", $discount_value);
                }
                $total_fare=($base_fare+$aed_attributes->aed_convenience_fee)-$discount_value;
            	$package_price=$base_fare-$markup-$gst_value;
            	$package_price=round($package_price);
                $package_price=isset($package_price)? number_format($package_price , 2):0;
                if($v['booking_details']['discount']){
                      // echo number_format($data['booking_details']['discount'],2);
                     // echo isset($data['booking_details']['discount'])? number_format(get_converted_currency_value ( $currency_obj->force_currency_conversion ( $data['booking_details']['discount'] ) ), 2):0;
                      $discount=round($discount_value);  
                    }else{
                      $discount= "NA";
                    }
                     $markup=round($markup);
                    $markup=number_format($markup,2);

                    $conveince_fee=round($aed_attributes->aed_convenience_fee);
                    $conveince_fee=isset($conveince_fee)? number_format($conveince_fee,2):0;
                     $gst_value=round($gst_value);
                    $gst_value= number_format($gst_value,2);
                    $base_fare=round($base_fare);
                	$base_fare=number_format($base_fare, 2);
                	$total=round($total_fare);
                	if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
             // debug($v);exit;
			$export_data[$k]['app_reference'] = $v['booking_details']['app_reference'];
			$export_data[$k]['package_name'] = $v['tours_details']['package_name'];
			$export_data[$k]['billing_email'] = $book_attr['billing_email'];
			$export_data[$k]['passenger_contact'] = $book_attr['passenger_contact'];
            $export_data[$k]['lead_pax_name'] = $pax_name;
            $export_data[$k]['departure_date'] =$attributes['departure_date'];
            $export_data[$k]['duration'] = $v['tours_details']['duration'];
            $export_data[$k]['package_price'] = $package_price;
            $export_data[$k]['discount'] = $discount;
            $export_data[$k]['markup'] = $markup;
            $export_data[$k]['conveince_fee'] = $conveince_fee;
           	$export_data[$k]['gst_value'] = $gst_value;
           	$export_data[$k]['base_fare'] = $base_fare;
           	$export_data[$k]['total'] = number_format($total,2);
           	
           	$export_data[$k]['booked_datetime'] = changeDateFormat($v['booking_details']['booked_datetime']);
           	if($all=="all")
	        {

	        $export_data[$k]['status'] = $v['booking_details']['status'];
	        }
           	$export_data[$k]['Online'] = "Online";
           
           	
        }
        // debug($export_data);exit;
        ob_end_clean();
        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'Reservation Code',
                    'c1' => 'Package Name',
                    'd1' => 'Email',
                    'e1' => 'Phone',
                    'f1' => 'Passanger Name',
                    'g1' => 'Departure Date',
                    'h1' => 'Number Of Days',
                    'i1' => 'Package Price',
                    'j1' => 'Promocode Amount',
                    'k1' => 'Markup',
                    'l1' => 'Convenience Fee',
                    'm1' => 'VAT',
					'n1' => 'Total Fare',
                    'o1' => 'Grand Total',                    
                    'p1' => 'BookedOn',
                    
                    
                );
           		if($all=="all")
		        {

		        $headings['q1'] = 'Status';
		        $headings['r1'] = 'Billing Type';
		        }
		        else{
		        	$headings['q1'] = 'Billing Type';
		        }
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'package_name',
                    'd' => 'billing_email',
                    'e' => 'passenger_contact',
                    'f' => 'lead_pax_name',
                    'g' => 'departure_date',
                    'h' => 'duration',
                    'i' => 'package_price',
                    'j' => 'discount',
                    'k' => 'markup',
                    'l' => 'conveince_fee',
                    'm' => 'gst_value',
                    'n' => 'base_fare',
                    'o' => 'total',                    
                    'p' => 'booked_datetime',
                    'q' => 'Online',
                    
                    
                );
                if($all=="all")
		        {

		        $fields['q'] = 'Status';
		        $fields['r'] = 'Online';
		        $excel_sheet_properties = array(
                'title' => 'All_Booking_holidayReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'All_Booking_holidayReport',
                'sheet_title' => 'All_Booking_holidayReport'
            );
		        }
		        else{
		        	$fields['q'] = 'Online';
		        	$excel_sheet_properties = array(
                'title' => 'Confirmed_Booking_holidayReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Confirmed_Booking_holidayReport',
                'sheet_title' => 'Confirmed_Booking_holidayReport'
            );
		        }
           
            

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.

            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            // debug($export_data);exit;
        	if($all=="all")
		        {

		        $headings = array('Sl. No.','Reservation Code','Package Name','Email','Phone','Passanger Name','Departure Date','Number Of Days','Package Price','Promocode Amount','Markup','Convenience Fee','VAT','Total Fare','Grand Total','BookedOn','Status','Billing Type'); 
		        }
		        else{
		        	$headings = array('Sl. No.','Reservation Code','Package Name','Email','Phone','Passanger Name','Departure Date','Number Of Days','Package Price','Promocode Amount','Markup','Convenience Fee','VAT','Total Fare','Grand Total','BookedOn','Billing Type'); 
		        }
        	

           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
            if($all=="all")
		        {

		        $this->provab_csv->csv_export($headings,'holiday All Booking Report', $export_data);
		        }
		        else{
		        	$this->provab_csv->csv_export($headings,'holiday Confirmed Booking Report', $export_data); 
		        }
            
           
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			$this->load->library ( 'provab_mailer' ); 
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$holiday_booking_data;
  			$pdf_data['record_type']=$all;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2c_report_holiday_pdf',$pdf_data);
  			$this->provab_pdf->create_pdf($mail_template);


        }
    }
    public function export_all_booking_holiday_report($op = '') {
        $this->load->model('tours_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('flight');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array();

		$holiday_booking_data = $this->tours_model->booking ( $condition, false, 0, 2000);
        // debug($holiday_booking_data);exit;
       
        // $flight_booking_data = $flight_booking_data['data']['booking_details'];

		$holiday_booking_data=$holiday_booking_data['data'];

        $export_data = array();
        // debug($holiday_booking_data);exit;
        $i=1;
        foreach ($holiday_booking_data as $k => $v) {
           // debug($v);exit;
           $pax_name="";
           	if($v['pax_details']){
                       foreach ($v['pax_details'] as  $value){ ?>

                        <?php

                         
                         $pax_name .= $value['pax_last_name']." ".$value['pax_first_name'];
                         
                      		}
                          
                         }

            $book_attr = json_decode($v['booking_details']['attributes'],TRUE);     
            $attributes=$v['booking_details']['attributes'];

            $attributes=json_decode($attributes,true);

            $aed_attributes=json_decode($v['booking_details']['aed_array']);
                         // debug($aed_attributes);exit;
                $base_fare=0.00;
                if(isset($aed_attributes->aed_basic_price))
                {
                    $base_fare=($aed_attributes->aed_basic_price);
                    $base_fare=str_replace(",", "", $base_fare);
                }
                

                 $markup=0.00;
                if(isset($aed_attributes->aed_markup))
                {
                    $markup=($aed_attributes->aed_markup);
                    $markup=str_replace(",", "", $markup);
                }

                $gst_value=0.00;
                if(isset($aed_attributes->aed_gst_value))
                {
                    $gst_value=($aed_attributes->aed_gst_value);
                    $gst_value=str_replace(",", "", $gst_value);
                }
                $discount_value=0.00;
                if(isset($aed_attributes->aed_discount))
                {
                    $discount_value=($aed_attributes->aed_discount);
                    $discount_value=str_replace(",", "", $discount_value);
                }
                $total_fare=($base_fare+$aed_attributes->aed_convenience_fee)-$discount_value;
            	$package_price=$base_fare-$markup-$gst_value;
            	$package_price=round($package_price);
                $package_price=isset($package_price)? number_format($package_price , 2):0;
                if($v['booking_details']['discount']){
                      // echo number_format($data['booking_details']['discount'],2);
                     // echo isset($data['booking_details']['discount'])? number_format(get_converted_currency_value ( $currency_obj->force_currency_conversion ( $data['booking_details']['discount'] ) ), 2):0;
                      $discount=round($discount_value);  
                    }else{
                      $discount= "NA";
                    }
                     $markup=round($markup);
                    $markup=number_format($markup,2);

                    $conveince_fee=round($aed_attributes->aed_convenience_fee);
                    
                    $conveince_fee=isset($conveince_fee)? number_format($conveince_fee,2):0;
                     $gst_value=round($gst_value);
                    $gst_value= number_format($gst_value,2);
                    $base_fare=round($base_fare);
                	$base_fare=number_format($base_fare, 2);
                	$total=round($total_fare);
                	if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
             // debug($v);exit;
			$export_data[$k]['app_reference'] = $v['booking_details']['app_reference'];
			$export_data[$k]['package_name'] = $v['tours_details']['package_name'];
			$export_data[$k]['billing_email'] = $book_attr['billing_email'];
			$export_data[$k]['passenger_contact'] = $book_attr['passenger_contact'];
            $export_data[$k]['lead_pax_name'] = $pax_name;
            $export_data[$k]['departure_date'] =$attributes['departure_date'];
            $export_data[$k]['duration'] = $v['tours_details']['duration'];
            $export_data[$k]['package_price'] = $package_price;
            $export_data[$k]['discount'] = $discount;
            $export_data[$k]['markup'] = $markup;
            $export_data[$k]['conveince_fee'] = $conveince_fee;
           	$export_data[$k]['gst_value'] = $gst_value;
           	$export_data[$k]['base_fare'] = $base_fare;
           	$export_data[$k]['total'] = number_format($total,2);
           	
           	$export_data[$k]['booked_datetime'] = changeDateFormat($v['booking_details']['booked_datetime']);
           	$export_data[$k]['status'] = $v['booking_details']['status'];
           	$export_data[$k]['Online'] = "Online";
           
           	
        }
        // debug($export_data);exit;
        ob_end_clean();
        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'Reservation Code',
                    'c1' => 'Package Name',
                    'd1' => 'Email',
                    'e1' => 'Phone',
                    'f1' => 'Passanger Name',
                    'g1' => 'Departure Date',
                    'h1' => 'Number Of Days',
                    'i1' => 'Package Price',
                    'j1' => 'Promocode Amount',
                    'k1' => 'Markup',
                    'l1' => 'Convenience Fee',
                    'm1' => 'VAT',
					'n1' => 'Total Fare',
                    'o1' => 'Grand Total',                    
                    'p1' => 'BookedOn',
                    'q1' => 'Status',
                    'r1' => 'Billing Type',
                    
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'package_name',
                    'd' => 'billing_email',
                    'e' => 'passenger_contact',
                    'f' => 'lead_pax_name',
                    'g' => 'departure_date',
                    'h' => 'duration',
                    'i' => 'package_price',
                    'j' => 'discount',
                    'k' => 'markup',
                    'l' => 'conveince_fee',
                    'm' => 'gst_value',
                    'n' => 'base_fare',
                    'o' => 'total',                    
                    'p' => 'booked_datetime',
                    'q' => 'status',
                    'r' => 'Online',
                    
                    
                );
           
            $excel_sheet_properties = array(
                'title' => 'All_Booking_holidayReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'All_Booking_holidayReport',
                'sheet_title' => 'All_Booking_holidayReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.

            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            // debug($export_data);exit;

        	$headings = array('Sl. No.','Reservation Code','Package Name','Email','Phone','Passanger Name','Departure Date','Number Of Days','Package Price','Promocode Amount','Markup','Convenience Fee','VAT','Total Fare','Grand Total','BookedOn','Status','Billing Type'); 

           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
            $this->provab_csv->csv_export($headings,'holiday All Booking Report', $export_data);
           
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			$this->load->library ( 'provab_mailer' ); 
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$holiday_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2c_report_holiday_all_pdf',$pdf_data);
  			$this->provab_pdf->create_pdf($mail_template);


        }
    }
    public function export_all_booking_holiday_report_email($op = '') {
        $this->load->model('flight_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('flight');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array();

        $holiday_booking_data = $this->tours_model->booking ( $condition, false, 0, 2000);
        // debug($holiday_booking_data);exit;
       
        // $flight_booking_data = $flight_booking_data['data']['booking_details'];

		$holiday_booking_data=$holiday_booking_data['data'];

        $export_data = array();
        // debug($holiday_booking_data['data']);exit;
        $i=1;
        foreach ($holiday_booking_data as $k => $v) {
           // debug($v);exit;
           $pax_name="";
           	if($v['pax_details']){
                       foreach ($v['pax_details'] as  $value){ ?>

                        <?php

                         
                         $pax_name .= $value['pax_last_name']." ".$value['pax_first_name'].",";
                         
                      		}
                          
                         }

            $book_attr = json_decode($v['booking_details']['attributes'],TRUE);     
            $attributes=$v['booking_details']['attributes'];

            $attributes=json_decode($attributes,true);

            $aed_attributes=json_decode($v['booking_details']['aed_array']);
                         // debug($aed_attributes);exit;
                $base_fare=0.00;
                if(isset($aed_attributes->aed_basic_price))
                {
                    $base_fare=($aed_attributes->aed_basic_price);
                    $base_fare=str_replace(",", "", $base_fare);
                }
                

                 $markup=0.00;
                if(isset($aed_attributes->aed_markup))
                {
                    $markup=($aed_attributes->aed_markup);
                    $markup=str_replace(",", "", $markup);
                }

                $gst_value=0.00;
                if(isset($aed_attributes->aed_gst_value))
                {
                    $gst_value=($aed_attributes->aed_gst_value);
                    $gst_value=str_replace(",", "", $gst_value);
                }
                $discount_value=0.00;
                if(isset($aed_attributes->aed_discount))
                {
                    $discount_value=($aed_attributes->aed_discount);
                    $discount_value=str_replace(",", "", $discount_value);
                }
                $total_fare=($base_fare+$aed_attributes->aed_convenience_fee)-$discount_value;
            	$package_price=$base_fare-$markup-$gst_value;
            	$package_price=round($package_price);
                $package_price=isset($package_price)? number_format($package_price , 2):0;
                if($v['booking_details']['discount']){
                      // echo number_format($data['booking_details']['discount'],2);
                     // echo isset($data['booking_details']['discount'])? number_format(get_converted_currency_value ( $currency_obj->force_currency_conversion ( $data['booking_details']['discount'] ) ), 2):0;
                      $discount=round($discount_value);  
                    }else{
                      $discount= "NA";
                    }
                     $markup=round($markup);
                    $markup=number_format($markup,2);

                    $conveince_fee=round($aed_attributes->aed_convenience_fee);
                    $conveince_fee=isset($conveince_fee)? number_format($conveince_fee,2):0;
                     $gst_value=round($gst_value);
                    $gst_value= number_format($gst_value,2);
                    $base_fare=round($base_fare);
                	$base_fare=number_format($base_fare, 2);
                	$total=round($total_fare);
                	if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['booking_details']['app_reference'];
			$export_data[$k]['package_name'] = $v['tours_details']['package_name'];
			$export_data[$k]['billing_email'] = $book_attr['billing_email'];
			$export_data[$k]['passenger_contact'] = $book_attr['passenger_contact'];
            $export_data[$k]['lead_pax_name'] = $pax_name;
            $export_data[$k]['departure_date'] =$attributes['departure_date'];
            $export_data[$k]['duration'] = $v['tours_details']['duration'];
            $export_data[$k]['package_price'] = $package_price;
            $export_data[$k]['discount'] = $discount;
            $export_data[$k]['markup'] = $markup;
            $export_data[$k]['conveince_fee'] = $conveince_fee;
           	$export_data[$k]['gst_value'] = $gst_value;
           	$export_data[$k]['base_fare'] = $base_fare;
           	$export_data[$k]['total'] = number_format($total,2);
           	
           	$export_data[$k]['booked_datetime'] = changeDateFormat($v['booking_details']['booked_datetime']);
           	$export_data[$k]['status'] =$v['booking_details']['status'];
           	$export_data[$k]['Online'] = "Online";
           
           	
        }
        // debug($export_data);exit;
        ob_end_clean();
        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'Reservation Code',
                    'c1' => 'Package Name',
                    'd1' => 'Email',
                    'e1' => 'Phone',
                    'f1' => 'Passanger Name',
                    'g1' => 'Departure Date',
                    'h1' => 'Number Of Days',
                    'i1' => 'Package Price',
                    'j1' => 'Promocode Amount',
                    'k1' => 'Markup',
                    'l1' => 'Convenience Fee',
                    'm1' => 'VAT',
					'n1' => 'Total Fare',
                    'o1' => 'Grand Total',                    
                    'p1' => 'BookedOn',
                    'q1' => 'Status',
                    'r1' => 'Billing Type',
                    
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'package_name',
                    'd' => 'billing_email',
                    'e' => 'passenger_contact',
                    'f' => 'lead_pax_name',
                    'g' => 'departure_date',
                    'h' => 'duration',
                    'i' => 'package_price',
                    'j' => 'discount',
                    'k' => 'markup',
                    'l' => 'conveince_fee',
                    'm' => 'gst_value',
                    'n' => 'base_fare',
                    'o' => 'total',                    
                    'p' => 'booked_datetime',
                    'q' => 'status',
                    'r' => 'Online',
                    
                    
                );
           
            $excel_sheet_properties = array(
                'title' => 'All_Booking_holidayReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'All_Booking_holidayReport',
                'sheet_title' => 'All_Booking_holidayReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
             $excel=$this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties,'F');
            // echo BASEPATH;exit;
            $file_path=BASEPATH.'reportexcel/'.$excel;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                
            $res = $this->provab_mailer->send_mail($value, 'Holiday All Booking Report', $message,$file_path);
            
            }
            unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);
        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            // debug($export_data);exit;

        	$headings = array("Sl. No.",'Reservation Code','Package Name','Email','Phone','Passanger Name','Departure Date','Number Of Days','Package Price','Promocode Amount','Markup','Convenience Fee','VAT','Total Fare','Grand Total','BookedOn',"Status",'Billing Type'); 

           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
            $csv= $this->provab_csv->csv_export($headings,'All_Booking_holidayReport', $export_data,'F');
           $file_path=BASEPATH.'reportcsv/'.$csv;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                
            $res = $this->provab_mailer->send_mail($value, 'All_Booking_holidayReport', $message,$file_path);
            
            }
            unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);
           
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			$this->load->library ( 'provab_mailer' ); 
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$holiday_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2c_report_holiday_all_pdf',$pdf_data);
  			$pdf=$this->provab_pdf->create_pdf($mail_template,'F');
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                
            $res = $this->provab_mailer->send_mail($value, 'All_Booking_transferReport', $message,$pdf);
            
            }
            // unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);


        }
    }

     /*
     * For Cancelled Booking
     * Export AirlineReport details to Excel Format or PDF
     */

    public function export_cancelled_booking_holiday_report($op = '') {
        $this->load->model('flight_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('flight');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CANCELLED'));

        $holiday_booking_data = $this->tours_model->booking ( $condition, false, 0, 2000);
        // debug($holiday_booking_data);exit;
       
        // $flight_booking_data = $flight_booking_data['data']['booking_details'];

		$holiday_booking_data=$holiday_booking_data['data'];

        $export_data = array();
        // debug($holiday_booking_data['data']);exit;
        $i=1;
        foreach ($holiday_booking_data as $k => $v) {
           // debug($v);exit;
           $pax_name="";
           	if($v['pax_details']){
                       foreach ($v['pax_details'] as  $value){ ?>

                        <?php

                         
                         $pax_name .= $value['pax_last_name']." ".$value['pax_first_name'].",";
                         
                      		}
                          
                         }

            $book_attr = json_decode($v['booking_details']['attributes'],TRUE);     
            $attributes=$v['booking_details']['attributes'];

            $attributes=json_decode($attributes,true);

            $aed_attributes=json_decode($v['booking_details']['aed_array']);
                         // debug($aed_attributes);exit;
                $base_fare=0.00;
                if(isset($aed_attributes->aed_basic_price))
                {
                    $base_fare=($aed_attributes->aed_basic_price);
                    $base_fare=str_replace(",", "", $base_fare);
                }
                

                 $markup=0.00;
                if(isset($aed_attributes->aed_markup))
                {
                    $markup=($aed_attributes->aed_markup);
                    $markup=str_replace(",", "", $markup);
                }

                $gst_value=0.00;
                if(isset($aed_attributes->aed_gst_value))
                {
                    $gst_value=($aed_attributes->aed_gst_value);
                    $gst_value=str_replace(",", "", $gst_value);
                }
                $discount_value=0.00;
                if(isset($aed_attributes->aed_discount))
                {
                    $discount_value=($aed_attributes->aed_discount);
                    $discount_value=str_replace(",", "", $discount_value);
                }
                $total_fare=($base_fare+$aed_attributes->aed_convenience_fee)-$discount_value;
            	$package_price=$base_fare-$markup-$gst_value;
            	$package_price=round($package_price);
                $package_price=isset($package_price)? number_format($package_price , 2):0;
                if($v['booking_details']['discount']){
                      // echo number_format($data['booking_details']['discount'],2);
                     // echo isset($data['booking_details']['discount'])? number_format(get_converted_currency_value ( $currency_obj->force_currency_conversion ( $data['booking_details']['discount'] ) ), 2):0;
                      $discount=round($discount_value);  
                    }else{
                      $discount= "NA";
                    }
                     $markup=round($markup);
                    $markup=number_format($markup,2);

                    $conveince_fee=round($aed_attributes->aed_convenience_fee);
                    $conveince_fee=isset($conveince_fee)? number_format($conveince_fee,2):0;
                     $gst_value=round($gst_value);
                    $gst_value= number_format($gst_value,2);
                    $base_fare=round($base_fare);
                	$base_fare=number_format($base_fare, 2);
                	$total=round($total_fare);
                	if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['booking_details']['app_reference'];
			$export_data[$k]['package_name'] = $v['tours_details']['package_name'];
			$export_data[$k]['billing_email'] = $book_attr['billing_email'];
			$export_data[$k]['passenger_contact'] = $book_attr['passenger_contact'];
            $export_data[$k]['lead_pax_name'] = $pax_name;
            $export_data[$k]['departure_date'] =$attributes['departure_date'];
            $export_data[$k]['duration'] = $v['tours_details']['duration'];
            $export_data[$k]['package_price'] = $package_price;
            $export_data[$k]['discount'] = $discount;
            $export_data[$k]['markup'] = $markup;
            $export_data[$k]['conveince_fee'] = $conveince_fee;
           	$export_data[$k]['gst_value'] = $gst_value;
           	$export_data[$k]['base_fare'] = $base_fare;
           	$export_data[$k]['total'] = number_format($total,2);
           	
           	$export_data[$k]['booked_datetime'] = changeDateFormat($v['booking_details']['booked_datetime']);
           	$export_data[$k]['Online'] = "Online";
           
           	
        }
        ob_end_clean();
        // debug($export_data);exit;
        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'Reservation Code',
                    'c1' => 'Package Name',
                    'd1' => 'Email',
                    'e1' => 'Phone',
                    'f1' => 'Passanger Name',
                    'g1' => 'Departure Date',
                    'h1' => 'Number Of Days',
                    'i1' => 'Package Price',
                    'j1' => 'Promocode Amount',
                    'k1' => 'Markup',
                    'l1' => 'Convenience Fee',
                    'm1' => 'VAT',
					'n1' => 'Total Fare',
                    'o1' => 'Grand Total',                    
                    'p1' => 'BookedOn',
                    'q1' => 'Billing Type',
                    
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'package_name',
                    'd' => 'billing_email',
                    'e' => 'passenger_contact',
                    'f' => 'lead_pax_name',
                    'g' => 'departure_date',
                    'h' => 'duration',
                    'i' => 'package_price',
                    'j' => 'discount',
                    'k' => 'markup',
                    'l' => 'conveince_fee',
                    'm' => 'gst_value',
                    'n' => 'base_fare',
                    'o' => 'total',                    
                    'p' => 'booked_datetime',
                    'q' => 'Online',
                    
                    
                );
           
            $excel_sheet_properties = array(
                'title' => 'Cancelled_Booking_holidayReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Cancelled_Booking_holidayReport',
                'sheet_title' => 'Cancelled_Booking_holidayReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            // debug($export_data);exit;

        	$headings = array("Sl. No.",'Reservation Code','Package Name','Email','Phone','Passanger Name','Departure Date','Number Of Days','Package Price','Promocode Amount','Markup','Convenience Fee','VAT','Total Fare','Grand Total','BookedOn','Billing Type'); 

           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
            $this->provab_csv->csv_export($headings,'holiday Cancelled Booking Report', $export_data);
           
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			$this->load->library ( 'provab_mailer' ); 
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$holiday_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2c_report_holiday_pdf',$pdf_data);
  			$this->provab_pdf->create_pdf($mail_template);


        }
    }



    public function export_confirmed_booking_holiday_report_email($op = '') {
        $this->load->model('flight_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('flight');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CONFIRMED'));

        $holiday_booking_data = $this->tours_model->booking ( $condition, false, 0, 2000);
        // debug($holiday_booking_data);exit;
       
        // $flight_booking_data = $flight_booking_data['data']['booking_details'];

		$holiday_booking_data=$holiday_booking_data['data'];

        $export_data = array();
        // debug($holiday_booking_data['data']);exit;
        $i=1;
        foreach ($holiday_booking_data as $k => $v) {
           // debug($v);exit;
           $pax_name="";
           	if($v['pax_details']){
                       foreach ($v['pax_details'] as  $value){ ?>

                        <?php

                         
                         $pax_name .= $value['pax_last_name']." ".$value['pax_first_name'].",";
                         
                      		}
                          
                         }

            $book_attr = json_decode($v['booking_details']['attributes'],TRUE);     
            $attributes=$v['booking_details']['attributes'];

            $attributes=json_decode($attributes,true);

            $aed_attributes=json_decode($v['booking_details']['aed_array']);
                         // debug($aed_attributes);exit;
                $base_fare=0.00;
                if(isset($aed_attributes->aed_basic_price))
                {
                    $base_fare=($aed_attributes->aed_basic_price);
                    $base_fare=str_replace(",", "", $base_fare);
                }
                

                 $markup=0.00;
                if(isset($aed_attributes->aed_markup))
                {
                    $markup=($aed_attributes->aed_markup);
                    $markup=str_replace(",", "", $markup);
                }

                $gst_value=0.00;
                if(isset($aed_attributes->aed_gst_value))
                {
                    $gst_value=($aed_attributes->aed_gst_value);
                    $gst_value=str_replace(",", "", $gst_value);
                }
                $discount_value=0.00;
                if(isset($aed_attributes->aed_discount))
                {
                    $discount_value=($aed_attributes->aed_discount);
                    $discount_value=str_replace(",", "", $discount_value);
                }
                $total_fare=($base_fare+$aed_attributes->aed_convenience_fee)-$discount_value;
            	$package_price=$base_fare-$markup-$gst_value;
            	$package_price=round($package_price);
                $package_price=isset($package_price)? number_format($package_price , 2):0;
                if($v['booking_details']['discount']){
                      // echo number_format($data['booking_details']['discount'],2);
                     // echo isset($data['booking_details']['discount'])? number_format(get_converted_currency_value ( $currency_obj->force_currency_conversion ( $data['booking_details']['discount'] ) ), 2):0;
                      $discount=round($discount_value);  
                    }else{
                      $discount= "NA";
                    }
                     $markup=round($markup);
                    $markup=number_format($markup,2);

                    $conveince_fee=round($aed_attributes->aed_convenience_fee);
                    $conveince_fee=isset($conveince_fee)? number_format($conveince_fee,2):0;
                     $gst_value=round($gst_value);
                    $gst_value= number_format($gst_value,2);
                    $base_fare=round($base_fare);
                	$base_fare=number_format($base_fare, 2);
                	$total=round($total_fare);
                	if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['booking_details']['app_reference'];
			$export_data[$k]['package_name'] = $v['tours_details']['package_name'];
			$export_data[$k]['billing_email'] = $book_attr['billing_email'];
			$export_data[$k]['passenger_contact'] = $book_attr['passenger_contact'];
            $export_data[$k]['lead_pax_name'] = $pax_name;
            $export_data[$k]['departure_date'] =$attributes['departure_date'];
            $export_data[$k]['duration'] = $v['tours_details']['duration'];
            $export_data[$k]['package_price'] = $package_price;
            $export_data[$k]['discount'] = $discount;
            $export_data[$k]['markup'] = $markup;
            $export_data[$k]['conveince_fee'] = $conveince_fee;
           	$export_data[$k]['gst_value'] = $gst_value;
           	$export_data[$k]['base_fare'] = $base_fare;
           	$export_data[$k]['total'] = number_format($total,2);
           	
           	$export_data[$k]['booked_datetime'] = changeDateFormat($v['booking_details']['booked_datetime']);
           	$export_data[$k]['Online'] = "Online";
           
           	
        }
        // debug($export_data);exit;
        ob_end_clean();
        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'Reservation Code',
                    'c1' => 'Package Name',
                    'd1' => 'Email',
                    'e1' => 'Phone',
                    'f1' => 'Passanger Name',
                    'g1' => 'Departure Date',
                    'h1' => 'Number Of Days',
                    'i1' => 'Package Price',
                    'j1' => 'Promocode Amount',
                    'k1' => 'Markup',
                    'l1' => 'Convenience Fee',
                    'm1' => 'VAT',
					'n1' => 'Total Fare',
                    'o1' => 'Grand Total',                    
                    'p1' => 'BookedOn',
                    'q1' => 'Billing Type',
                    
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'package_name',
                    'd' => 'billing_email',
                    'e' => 'passenger_contact',
                    'f' => 'lead_pax_name',
                    'g' => 'departure_date',
                    'h' => 'duration',
                    'i' => 'package_price',
                    'j' => 'discount',
                    'k' => 'markup',
                    'l' => 'conveince_fee',
                    'm' => 'gst_value',
                    'n' => 'base_fare',
                    'o' => 'total',                    
                    'p' => 'booked_datetime',
                    'q' => 'Online',
                    
                    
                );
           
            $excel_sheet_properties = array(
                'title' => 'Confirmed_Booking_holidayReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Confirmed_Booking_holidayReport',
                'sheet_title' => 'Confirmed_Booking_holidayReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
             $excel=$this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties,'F');
            // echo BASEPATH;exit;
            $file_path=BASEPATH.'reportexcel/'.$excel;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                
            $res = $this->provab_mailer->send_mail($value, 'Holiday Confirmed Booking Report', $message,$file_path);
            
            }
            unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);
        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            // debug($export_data);exit;

        	$headings = array("Sl. No.",'Reservation Code','Package Name','Email','Phone','Passanger Name','Departure Date','Number Of Days','Package Price','Promocode Amount','Markup','Convenience Fee','VAT','Total Fare','Grand Total','BookedOn','Billing Type'); 

           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
            $csv= $this->provab_csv->csv_export($headings,'Confirmed_Booking_holidayReport', $export_data,'F');
           $file_path=BASEPATH.'reportcsv/'.$csv;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                
            $res = $this->provab_mailer->send_mail($value, 'Confirmed_Booking_holidayReport', $message,$file_path);
            
            }
            unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);
           
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			$this->load->library ( 'provab_mailer' ); 
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$holiday_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2c_report_holiday_pdf',$pdf_data);
  			$pdf=$this->provab_pdf->create_pdf($mail_template,'F');
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                
            $res = $this->provab_mailer->send_mail($value, 'Confirmed_Booking_transferReport', $message,$pdf);
            
            }
            // unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);


        }
    }
     /*
     * For Cancelled Booking
     * Export AirlineReport details to Excel Format or PDF
     */

    public function export_cancelled_booking_holiday_report_email($op = '') {
        $this->load->model('flight_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('flight');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CANCELLED'));

        $holiday_booking_data = $this->tours_model->booking ( $condition, false, 0, 2000);
        // debug($holiday_booking_data);exit;
       
        // $flight_booking_data = $flight_booking_data['data']['booking_details'];

		$holiday_booking_data=$holiday_booking_data['data'];

        $export_data = array();
        // debug($holiday_booking_data['data']);exit;
        $i=1;
        foreach ($holiday_booking_data as $k => $v) {
           // debug($v);exit;
           $pax_name="";
           	if($v['pax_details']){
                       foreach ($v['pax_details'] as  $value){ ?>

                        <?php

                         
                         $pax_name .= $value['pax_last_name']." ".$value['pax_first_name'].",";
                         
                      		}
                          
                         }

            $book_attr = json_decode($v['booking_details']['attributes'],TRUE);     
            $attributes=$v['booking_details']['attributes'];

            $attributes=json_decode($attributes,true);

            $aed_attributes=json_decode($v['booking_details']['aed_array']);
                         // debug($aed_attributes);exit;
                $base_fare=0.00;
                if(isset($aed_attributes->aed_basic_price))
                {
                    $base_fare=($aed_attributes->aed_basic_price);
                    $base_fare=str_replace(",", "", $base_fare);
                }
                

                 $markup=0.00;
                if(isset($aed_attributes->aed_markup))
                {
                    $markup=($aed_attributes->aed_markup);
                    $markup=str_replace(",", "", $markup);
                }

                $gst_value=0.00;
                if(isset($aed_attributes->aed_gst_value))
                {
                    $gst_value=($aed_attributes->aed_gst_value);
                    $gst_value=str_replace(",", "", $gst_value);
                }
                $discount_value=0.00;
                if(isset($aed_attributes->aed_discount))
                {
                    $discount_value=($aed_attributes->aed_discount);
                    $discount_value=str_replace(",", "", $discount_value);
                }
                $total_fare=($base_fare+$aed_attributes->aed_convenience_fee)-$discount_value;
            	$package_price=$base_fare-$markup-$gst_value;
            	$package_price=round($package_price);
                $package_price=isset($package_price)? number_format($package_price , 2):0;
                if($v['booking_details']['discount']){
                      // echo number_format($data['booking_details']['discount'],2);
                     // echo isset($data['booking_details']['discount'])? number_format(get_converted_currency_value ( $currency_obj->force_currency_conversion ( $data['booking_details']['discount'] ) ), 2):0;
                      $discount=round($discount_value);  
                    }else{
                      $discount= "NA";
                    }
                     $markup=round($markup);
                    $markup=number_format($markup,2);

                    $conveince_fee=round($aed_attributes->aed_convenience_fee);
                    $conveince_fee=isset($conveince_fee)? number_format($conveince_fee,2):0;
                     $gst_value=round($gst_value);
                    $gst_value= number_format($gst_value,2);
                    $base_fare=round($base_fare);
                	$base_fare=number_format($base_fare, 2);
                	$total=round($total_fare);
                	if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['booking_details']['app_reference'];
			$export_data[$k]['package_name'] = $v['tours_details']['package_name'];
			$export_data[$k]['billing_email'] = $book_attr['billing_email'];
			$export_data[$k]['passenger_contact'] = $book_attr['passenger_contact'];
            $export_data[$k]['lead_pax_name'] = $pax_name;
            $export_data[$k]['departure_date'] =$attributes['departure_date'];
            $export_data[$k]['duration'] = $v['tours_details']['duration'];
            $export_data[$k]['package_price'] = $package_price;
            $export_data[$k]['discount'] = $discount;
            $export_data[$k]['markup'] = $markup;
            $export_data[$k]['conveince_fee'] = $conveince_fee;
           	$export_data[$k]['gst_value'] = $gst_value;
           	$export_data[$k]['base_fare'] = $base_fare;
           	$export_data[$k]['total'] = number_format($total,2);
           	
           	$export_data[$k]['booked_datetime'] = changeDateFormat($v['booking_details']['booked_datetime']);
           	$export_data[$k]['Online'] = "Online";
           
           	
        }
        // debug($export_data);exit;
        ob_end_clean();
        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'Reservation Code',
                    'c1' => 'Package Name',
                    'd1' => 'Email',
                    'e1' => 'Phone',
                    'f1' => 'Passanger Name',
                    'g1' => 'Departure Date',
                    'h1' => 'Number Of Days',
                    'i1' => 'Package Price',
                    'j1' => 'Promocode Amount',
                    'k1' => 'Markup',
                    'l1' => 'Convenience Fee',
                    'm1' => 'VAT',
					'n1' => 'Total Fare',
                    'o1' => 'Grand Total',                    
                    'p1' => 'BookedOn',
                    'q1' => 'Billing Type',
                    
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'package_name',
                    'd' => 'billing_email',
                    'e' => 'passenger_contact',
                    'f' => 'lead_pax_name',
                    'g' => 'departure_date',
                    'h' => 'duration',
                    'i' => 'package_price',
                    'j' => 'discount',
                    'k' => 'markup',
                    'l' => 'conveince_fee',
                    'm' => 'gst_value',
                    'n' => 'base_fare',
                    'o' => 'total',                    
                    'p' => 'booked_datetime',
                    'q' => 'Online',
                    
                    
                );
           
            $excel_sheet_properties = array(
                'title' => 'Cancelled_Booking_holidayReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Cancelled_Booking_holidayReport',
                'sheet_title' => 'Cancelled_Booking_holidayReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
             $excel=$this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties,'F');
            // echo BASEPATH;exit;
            $file_path=BASEPATH.'reportexcel/'.$excel;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                
            $res = $this->provab_mailer->send_mail($value, 'Holiday Cancelled Booking Report', $message,$file_path);
            
            }
            unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);
        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            // debug($export_data);exit;

        	$headings = array("Sl. No.",'Reservation Code','Package Name','Email','Phone','Passanger Name','Departure Date','Number Of Days','Package Price','Promocode Amount','Markup','Convenience Fee','VAT','Total Fare','Grand Total','BookedOn','Billing Type'); 

           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
            $csv= $this->provab_csv->csv_export($headings,'Cancelled_Booking_holidayReport', $export_data,'F');
           $file_path=BASEPATH.'reportcsv/'.$csv;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                
            $res = $this->provab_mailer->send_mail($value, 'Cancelled_Booking_HolidayReport', $message,$file_path);
            
            }
            unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);
           
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			$this->load->library ( 'provab_mailer' ); 
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$holiday_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2c_report_holiday_pdf',$pdf_data);
  			$pdf=$this->provab_pdf->create_pdf($mail_template,'F');
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                
            $res = $this->provab_mailer->send_mail($value, 'Cancelled_Booking_HolidayReport', $message,$pdf);
            
            }
            // unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);


        }
    }
    public function export_confirmed_booking_holiday_report_b2b($op = '',$all='') {
        $this->load->model('tours_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('flight');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        if($all="all"){

        $condition[] = array();
        }
        else
        {

        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CONFIRMED'));
        }
    	// echo "string";exit;
    	// debug($condition);exit('sudheer');

		$holiday_booking_data = $this->tours_model->b2b_booking ( $condition, false, 0, 2000);
        // debug($holiday_booking_data);exit;
       
        // $flight_booking_data = $flight_booking_data['data']['booking_details'];

		$holiday_booking_data=$holiday_booking_data['data'];

        $export_data = array();
        // debug($holiday_booking_data['data']);exit;
        $i=1;
        // debug($holiday_booking_data);exit;
        foreach ($holiday_booking_data as $k => $v) {

           // debug($v);exit;
           $pax_name="";
           	if($v['pax_details']){
                       foreach ($v['pax_details'] as  $value){ ?>

                        <?php

                         
                         $pax_name .= $value['pax_last_name']." ".$value['pax_first_name'].",";
                         
                      		}
                          
                         }

            $book_attr = json_decode($v['booking_details']['attributes'],TRUE);     
            $attributes=$v['booking_details']['attributes'];

            $attributes=json_decode($attributes,true);

            $aed_attributes=json_decode($v['booking_details']['aed_array']);
                         // debug($aed_attributes);exit;
                $base_fare=0.00;
                if(isset($aed_attributes->aed_basic_price))
                {
                    $base_fare=($aed_attributes->aed_basic_price);
                    $base_fare=str_replace(",", "", $base_fare);
                }
                

                 $markup=0.00;
                if(isset($aed_attributes->aed_markup))
                {
                    $markup=($aed_attributes->aed_markup);
                    $markup=str_replace(",", "", $markup);
                }

                $gst_value=0.00;
                if(isset($aed_attributes->aed_gst_value))
                {
                    $gst_value=($aed_attributes->aed_gst_value);
                    $gst_value=str_replace(",", "", $gst_value);
                }
                $discount_value=0.00;
                if(isset($aed_attributes->aed_discount))
                {
                    $discount_value=($aed_attributes->aed_discount);
                    $discount_value=str_replace(",", "", $discount_value);
                }
                $total_fare=($base_fare+$aed_attributes->aed_convenience_fee)-$discount_value;
            	$package_price=$base_fare-$markup-$gst_value;
            	$package_price=round($package_price);
                $package_price=isset($package_price)? number_format($package_price , 2):0;
                if($v['booking_details']['discount']){
                      // echo number_format($data['booking_details']['discount'],2);
                     // echo isset($data['booking_details']['discount'])? number_format(get_converted_currency_value ( $currency_obj->force_currency_conversion ( $data['booking_details']['discount'] ) ), 2):0;
                      $discount=round($discount_value);  
                    }else{
                      $discount= "NA";
                    }
                     $markup=round($markup);
                    $markup=number_format($markup,2);

                    $conveince_fee=round($aed_attributes->aed_convenience_fee);
                    $conveince_fee=isset($conveince_fee)? number_format($conveince_fee,2):0;
                     $gst_value=round($gst_value);
                    $gst_value= number_format($gst_value,2);
                    $base_fare=round($base_fare);
                	$base_fare=number_format($base_fare, 2);
                	$total=round($total_fare);
                	if($book_attr['agent_markup']!="")
                      {
                        $agent_markup=$book_attr['agent_markup'];
                      }
                      else{
                      $agent_markup="0.00";
                      }
                      $admin_markup=$markup-$book_attr['agent_markup'];
                      if($admin_markup!="")
	                   {
	                        $admin_markup= $admin_markup;
	                    }
	                  else {
	                    $admin_markup="0.00";
	                  }
	                  if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['booking_details']['app_reference'];
			$export_data[$k]['package_name'] = $v['tours_details']['package_name'];
			$export_data[$k]['billing_email'] = $book_attr['billing_email'];
			$export_data[$k]['passenger_contact'] = $book_attr['passenger_contact'];
            $export_data[$k]['lead_pax_name'] = $pax_name;
            $export_data[$k]['departure_date'] =$attributes['departure_date'];
            $export_data[$k]['duration'] = $v['tours_details']['duration'];
            $export_data[$k]['package_price'] = $package_price;            
            $export_data[$k]['admin_markup'] = $admin_markup;
            $export_data[$k]['agent_markup'] = $agent_markup;
           	$export_data[$k]['vat'] = $gst_value;
           	$export_data[$k]['total'] = number_format($total,2);
           	
           	$export_data[$k]['booked_datetime'] = changeDateFormat($v['booking_details']['booked_datetime']);
           	if($all="all"){

        $export_data[$k]['status'] = $v['booking_details']['status'];
        }
           	$export_data[$k]['Online'] = "Online";
           
           	
        }
        // debug($export_data);exit;
        ob_end_clean();
        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'Reservation Code',
                    'c1' => 'Package Name',
                    'd1' => 'Email',
                    'e1' => 'Phone',
                    'f1' => 'Passanger Name',
                    'g1' => 'Departure Date',
                    'h1' => 'Number Of Days',
                    'i1' => 'Package Price',
                    'j1' => 'Admin Markup',
                    'k1' => 'Agent Markup',
                    'l1' => 'VAT',
                    'm1' => 'Grand Total',
					'n1' => 'BookedOn',
                                        
                    
                    
                );
           		if($all="all"){

		        $headings['o1'] = 'status';
		        $headings['p1'] = 'Billing Type';
		        }
		        else
		        {
		        	$headings['o1'] = 'Billing Type';
		        }
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'package_name',
                    'd' => 'billing_email',
                    'e' => 'passenger_contact',
                    'f' => 'lead_pax_name',
                    'g' => 'departure_date',
                    'h' => 'duration',
                    'i' => 'package_price',
                    'j' => 'admin_markup',
                    'k' => 'agent_markup',
                    'l' => 'vat',
                    'm' => 'total',
                    'n' => 'booked_datetime',
                     
                    
                    
                );
           if($all="all"){

		        $fields['o'] = 'status';
		        $fields['p'] = 'Online';
			        $excel_sheet_properties = array(
	                'title' => 'All_Booking_holidayReport_' . date('d-M-Y'),
	                'creator' => 'Accentria Solutions',
	                'description' => 'All_Booking_holidayReport',
	                'sheet_title' => 'All_Booking_holidayReport'
	            );
		        }
		        else
		        {
		        	$fields['o'] = 'Online';
		        	$excel_sheet_properties = array(
                'title' => 'Confirmed_Booking_holidayReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Confirmed_Booking_holidayReport',
                'sheet_title' => 'Confirmed_Booking_holidayReport'
            );
		        }
            

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            // debug($export_data);exit;
        	if($all="all"){

		        $headings = array("Sl. No.",'Reservation Code','Package Name','Email','Phone','Passanger Name','Departure Date','Number Of Days','Package Price','Admin Markup','Agent Markup','VAT','Grand Total','BookedOn','Status','Billing Type'); 
		        }
		        else
		        {
		        	$headings = array("Sl. No.",'Reservation Code','Package Name','Email','Phone','Passanger Name','Departure Date','Number Of Days','Package Price','Admin Markup','Agent Markup','VAT','Grand Total','BookedOn','Billing Type'); 
		        }
        	

           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
            
           if($all="all"){

		        $this->provab_csv->csv_export($headings,'holiday All Booking Report', $export_data);
		        }
		        else
		        {
		        	$this->provab_csv->csv_export($headings,'holiday Confirmed Booking Report', $export_data);
		        }
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			$this->load->library ( 'provab_mailer' ); 
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$holiday_booking_data;
  			$pdf_data['record_type']=$all;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2b_report_holiday_pdf',$pdf_data);
  			$this->provab_pdf->create_pdf($mail_template);


        }
    }
    public function export_confirmed_booking_holiday_report_b2b_email($op = '',$all='') {
        $this->load->model('tours_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('flight');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        if($all=="all")
        {

        $condition[] = array();
        }
        else
        {

        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CONFIRMED'));
        }

		$holiday_booking_data = $this->tours_model->b2b_booking ( $condition, false, 0, 2000);
        // debug($holiday_booking_data);exit;
       
        // $flight_booking_data = $flight_booking_data['data']['booking_details'];

		$holiday_booking_data=$holiday_booking_data['data'];

        $export_data = array();
        // debug($holiday_booking_data['data']);exit;
        $i=1;
        foreach ($holiday_booking_data as $k => $v) {
           // debug($v);exit;
           $pax_name="";
           	if($v['pax_details']){
                       foreach ($v['pax_details'] as  $value){ ?>

                        <?php

                         
                         $pax_name .= $value['pax_last_name']." ".$value['pax_first_name'].",";
                         
                      		}
                          
                         }

            $book_attr = json_decode($v['booking_details']['attributes'],TRUE);     
            $attributes=$v['booking_details']['attributes'];

            $attributes=json_decode($attributes,true);

            $aed_attributes=json_decode($v['booking_details']['aed_array']);
                         // debug($aed_attributes);exit;
                $base_fare=0.00;
                if(isset($aed_attributes->aed_basic_price))
                {
                    $base_fare=($aed_attributes->aed_basic_price);
                    $base_fare=str_replace(",", "", $base_fare);
                }
                

                 $markup=0.00;
                if(isset($aed_attributes->aed_markup))
                {
                    $markup=($aed_attributes->aed_markup);
                    $markup=str_replace(",", "", $markup);
                }

                $gst_value=0.00;
                if(isset($aed_attributes->aed_gst_value))
                {
                    $gst_value=($aed_attributes->aed_gst_value);
                    $gst_value=str_replace(",", "", $gst_value);
                }
                $discount_value=0.00;
                if(isset($aed_attributes->aed_discount))
                {
                    $discount_value=($aed_attributes->aed_discount);
                    $discount_value=str_replace(",", "", $discount_value);
                }
                $total_fare=($base_fare+$aed_attributes->aed_convenience_fee)-$discount_value;
            	$package_price=$base_fare-$markup-$gst_value;
            	$package_price=round($package_price);
                $package_price=isset($package_price)? number_format($package_price , 2):0;
                if($v['booking_details']['discount']){
                      // echo number_format($data['booking_details']['discount'],2);
                     // echo isset($data['booking_details']['discount'])? number_format(get_converted_currency_value ( $currency_obj->force_currency_conversion ( $data['booking_details']['discount'] ) ), 2):0;
                      $discount=round($discount_value);  
                    }else{
                      $discount= "NA";
                    }
                     $markup=round($markup);
                    $markup=number_format($markup,2);

                    $conveince_fee=round($aed_attributes->aed_convenience_fee);
                    $conveince_fee=isset($conveince_fee)? number_format($conveince_fee,2):0;
                     $gst_value=round($gst_value);
                    $gst_value= number_format($gst_value,2);
                    $base_fare=round($base_fare);
                	$base_fare=number_format($base_fare, 2);
                	$total=round($total_fare);
                	if($book_attr['agent_markup']!="")
                      {
                        $agent_markup=$book_attr['agent_markup'];
                      }
                      else{
                      $agent_markup="0.00";
                      }
                      $admin_markup=$markup-$book_attr['agent_markup'];
                      if($admin_markup!="")
	                   {
	                        $admin_markup= $admin_markup;
	                    }
	                  else {
	                    $admin_markup="0.00";
	                  }
	                  if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['booking_details']['app_reference'];
			$export_data[$k]['package_name'] = $v['tours_details']['package_name'];
			$export_data[$k]['billing_email'] = $book_attr['billing_email'];
			$export_data[$k]['passenger_contact'] = $book_attr['passenger_contact'];
            $export_data[$k]['lead_pax_name'] = $pax_name;
            $export_data[$k]['departure_date'] =$attributes['departure_date'];
            $export_data[$k]['duration'] = $v['tours_details']['duration'];
            $export_data[$k]['package_price'] = $package_price;            
            $export_data[$k]['admin_markup'] = $admin_markup;
            $export_data[$k]['agent_markup'] = $agent_markup;
           	$export_data[$k]['vat'] = $gst_value;
           	$export_data[$k]['total'] = number_format($total,2);
           	
           	$export_data[$k]['booked_datetime'] = changeDateFormat($v['booking_details']['booked_datetime']);
           	if($all=="all")
           	{
           		$export_data[$k]['status'] =$v['booking_details']['status'];
           	}
           	$export_data[$k]['Online'] = "Online";
           
           	
        }
        // debug($export_data);exit;
        ob_end_clean();
        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'Reservation Code',
                    'c1' => 'Package Name',
                    'd1' => 'Email',
                    'e1' => 'Phone',
                    'f1' => 'Passanger Name',
                    'g1' => 'Departure Date',
                    'h1' => 'Number Of Days',
                    'i1' => 'Package Price',
                    'j1' => 'Admin Markup',
                    'k1' => 'Agent Markup',
                    'l1' => 'VAT',
                    'm1' => 'Grand Total',
					'n1' => 'BookedOn',
                                       
                    
                    
                );
           		if($all=="all"){
           			$headings['o1']='status';
           			$headings['p1']='Billing Type';
           		}
           		else
           		{
           			$headings['o1']='Billing Type';
           		}

                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'package_name',
                    'd' => 'billing_email',
                    'e' => 'passenger_contact',
                    'f' => 'lead_pax_name',
                    'g' => 'departure_date',
                    'h' => 'duration',
                    'i' => 'package_price',
                    'j' => 'admin_markup',
                    'k' => 'agent_markup',
                    'l' => 'vat',
                    'm' => 'total',
                    'n' => 'booked_datetime',
                     
                    
                    
                );
                if($all=="all"){
           			$fields['o1']='status';
           			$fields['p1']='Online';
           			$excel_sheet_properties = array(
		                'title' => 'All_Booking_holidayReport_' . date('d-M-Y'),
		                'creator' => 'Accentria Solutions',
		                'description' => 'All_Booking_holidayReport',
		                'sheet_title' => 'All_Booking_holidayReport'
		            );
           		}
           		else
           		{
           			$fields['o1']='Online';
           			$excel_sheet_properties = array(
                'title' => 'Confirmed_Booking_holidayReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Confirmed_Booking_holidayReport',
                'sheet_title' => 'Confirmed_Booking_holidayReport'
            );
           		}
           
            

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $excel=$this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties,'F');
            // echo BASEPATH;exit;
            $file_path=BASEPATH.'reportexcel/'.$excel;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                
            
            if($all=="all"){
           			$res = $this->provab_mailer->send_mail($value, 'Holiday All Booking Report', $message,$file_path);
           		}
           		else
           		{
           			$res = $this->provab_mailer->send_mail($value, 'Holiday Confirmed Booking Report', $message,$file_path);
           		}
            }
            unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);
        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            // debug($export_data);exit;

        		if($all=="all"){
           			$headings = array("Sl. No.",'Reservation Code','Package Name','Email','Phone','Passanger Name','Departure Date','Number Of Days','Package Price','Admin Markup','Agent Markup','VAT','Grand Total','BookedOn','Status','Billing Type'); 
           		}
           		else
           		{
           			$headings = array("Sl. No.",'Reservation Code','Package Name','Email','Phone','Passanger Name','Departure Date','Number Of Days','Package Price','Admin Markup','Agent Markup','VAT','Grand Total','BookedOn','Billing Type'); 
           		}
               

           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
            $csv= $this->provab_csv->csv_export($headings,'Confirmed_Booking_holidayReport', $export_data,'F');
           $file_path=BASEPATH.'reportcsv/'.$csv;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                
            
            if($all=="all"){
           			$res = $this->provab_mailer->send_mail($value, 'All_Booking_holidayReport', $message,$file_path);
           		}
           		else
           		{
           			$res = $this->provab_mailer->send_mail($value, 'Confirmed_Booking_holidayReport', $message,$file_path);
           		}
            
            }
            unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);
           
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			$this->load->library ( 'provab_mailer' ); 
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$holiday_booking_data;
  			$pdf_data['record_type']=$all;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2b_report_holiday_pdf',$pdf_data);
  			$pdf=$this->provab_pdf->create_pdf($mail_template,'F');
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                
            $res = $this->provab_mailer->send_mail($value, 'Confirmed_Booking_transferReport', $message,$pdf);
            if($all=="all"){
           			$res = $this->provab_mailer->send_mail($value, 'All_Booking_transferReport', $message,$pdf);
           		}
           		else
           		{
           			$res = $this->provab_mailer->send_mail($value, 'Confirmed_Booking_transferReport', $message,$pdf);
           		}
            
            }
            // unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);


        }
    }
    public function export_cancelled_booking_holiday_report_b2b($op = '') {
        $this->load->model('flight_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('flight');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CANCELLED'));

        $holiday_booking_data = $this->tours_model->b2b_booking ( $condition, false, 0, 2000);
        // debug($holiday_booking_data);exit;
       
        // $flight_booking_data = $flight_booking_data['data']['booking_details'];

		$holiday_booking_data=$holiday_booking_data['data'];

        $export_data = array();
        // debug($holiday_booking_data['data']);exit;
        $i=1;
        foreach ($holiday_booking_data as $k => $v) {
           // debug($v);exit;
           $pax_name="";
           	if($v['pax_details']){
                       foreach ($v['pax_details'] as  $value){ ?>

                        <?php

                         
                         $pax_name .= $value['pax_last_name']." ".$value['pax_first_name'].",";
                         
                      		}
                          
                         }

            $book_attr = json_decode($v['booking_details']['attributes'],TRUE);     
            $attributes=$v['booking_details']['attributes'];

            $attributes=json_decode($attributes,true);

            $aed_attributes=json_decode($v['booking_details']['aed_array']);
                         // debug($aed_attributes);exit;
                $base_fare=0.00;
                if(isset($aed_attributes->aed_basic_price))
                {
                    $base_fare=($aed_attributes->aed_basic_price);
                    $base_fare=str_replace(",", "", $base_fare);
                }
                

                 $markup=0.00;
                if(isset($aed_attributes->aed_markup))
                {
                    $markup=($aed_attributes->aed_markup);
                    $markup=str_replace(",", "", $markup);
                }

                $gst_value=0.00;
                if(isset($aed_attributes->aed_gst_value))
                {
                    $gst_value=($aed_attributes->aed_gst_value);
                    $gst_value=str_replace(",", "", $gst_value);
                }
                $discount_value=0.00;
                if(isset($aed_attributes->aed_discount))
                {
                    $discount_value=($aed_attributes->aed_discount);
                    $discount_value=str_replace(",", "", $discount_value);
                }
                $total_fare=($base_fare+$aed_attributes->aed_convenience_fee)-$discount_value;
            	$package_price=$base_fare-$markup-$gst_value;
            	$package_price=round($package_price);
                $package_price=isset($package_price)? number_format($package_price , 2):0;
                if($v['booking_details']['discount']){
                      // echo number_format($data['booking_details']['discount'],2);
                     // echo isset($data['booking_details']['discount'])? number_format(get_converted_currency_value ( $currency_obj->force_currency_conversion ( $data['booking_details']['discount'] ) ), 2):0;
                      $discount=round($discount_value);  
                    }else{
                      $discount= "NA";
                    }
                     $markup=round($markup);
                    $markup=number_format($markup,2);

                    $conveince_fee=round($aed_attributes->aed_convenience_fee);
                    $conveince_fee=isset($conveince_fee)? number_format($conveince_fee,2):0;
                     $gst_value=round($gst_value);
                    $gst_value= number_format($gst_value,2);
                    $base_fare=round($base_fare);
                	$base_fare=number_format($base_fare, 2);
                	$total=round($total_fare);
                	if($book_attr['agent_markup']!="")
                      {
                        $agent_markup=$book_attr['agent_markup'];
                      }
                      else{
                      $agent_markup="0.00";
                      }
                      $admin_markup=$markup-$book_attr['agent_markup'];
                      if($admin_markup!="")
	                   {
	                        $admin_markup= $admin_markup;
	                    }
	                  else {
	                    $admin_markup="0.00";
	                  }
	                  if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['booking_details']['app_reference'];
			$export_data[$k]['package_name'] = $v['tours_details']['package_name'];
			$export_data[$k]['billing_email'] = $book_attr['billing_email'];
			$export_data[$k]['passenger_contact'] = $book_attr['passenger_contact'];
            $export_data[$k]['lead_pax_name'] = $pax_name;
            $export_data[$k]['departure_date'] =$attributes['departure_date'];
            $export_data[$k]['duration'] = $v['tours_details']['duration'];
            $export_data[$k]['package_price'] = $package_price;            
            $export_data[$k]['admin_markup'] = $admin_markup;
            $export_data[$k]['agent_markup'] = $agent_markup;
           	$export_data[$k]['vat'] = $gst_value;
           	$export_data[$k]['total'] = number_format($total,2);
           	
           	$export_data[$k]['booked_datetime'] = changeDateFormat($v['booking_details']['booked_datetime']);
           	$export_data[$k]['Online'] = "Online";
           
           	
        }
        // debug($export_data);exit;
        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'Reservation Code',
                    'c1' => 'Package Name',
                    'd1' => 'Email',
                    'e1' => 'Phone',
                    'f1' => 'Passanger Name',
                    'g1' => 'Departure Date',
                    'h1' => 'Number Of Days',
                    'i1' => 'Package Price',
                    'j1' => 'Admin Markup',
                    'k1' => 'Agent Markup',
                    'l1' => 'VAT',
                    'm1' => 'Grand Total',
					'n1' => 'BookedOn',
                    'o1' => 'Billing Type',                    
                    
                    
                );

                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'package_name',
                    'd' => 'billing_email',
                    'e' => 'passenger_contact',
                    'f' => 'lead_pax_name',
                    'g' => 'departure_date',
                    'h' => 'duration',
                    'i' => 'package_price',
                    'j' => 'admin_markup',
                    'k' => 'agent_markup',
                    'l' => 'vat',
                    'm' => 'total',
                    'n' => 'booked_datetime',
                    'o' => 'Online', 
                    
                    
                );
           
            $excel_sheet_properties = array(
                'title' => 'Cancelled_Booking_holidayReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Cancelled_Booking_holidayReport',
                'sheet_title' => 'Cancelled_Booking_holidayReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            // debug($export_data);exit;

        	
               $headings = array("Sl. No.",'Reservation Code','Package Name','Email','Phone','Passanger Name','Departure Date','Number Of Days','Package Price','Admin Markup','Agent Markup','VAT','Grand Total','BookedOn','Billing Type'); 

           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
            $this->provab_csv->csv_export($headings,'holiday Cancelled Booking Report', $export_data);
           
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			$this->load->library ( 'provab_mailer' ); 
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$holiday_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2b_report_holiday_pdf',$pdf_data);
  			$this->provab_pdf->create_pdf($mail_template);


        }
    }
    public function export_cancelled_booking_holiday_report_b2b_email($op = '') {
        $this->load->model('flight_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('flight');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CANCELLED'));

        $holiday_booking_data = $this->tours_model->b2b_booking ( $condition, false, 0, 2000);
        // debug($holiday_booking_data);exit;
       
        // $flight_booking_data = $flight_booking_data['data']['booking_details'];

		$holiday_booking_data=$holiday_booking_data['data'];

        $export_data = array();
        // debug($holiday_booking_data['data']);exit;
        $i=1;
        foreach ($holiday_booking_data as $k => $v) {
           // debug($v);exit;
           $pax_name="";
           	if($v['pax_details']){
                       foreach ($v['pax_details'] as  $value){ ?>

                        <?php

                         
                         $pax_name .= $value['pax_last_name']." ".$value['pax_first_name'].",";
                         
                      		}
                          
                         }

            $book_attr = json_decode($v['booking_details']['attributes'],TRUE);     
            $attributes=$v['booking_details']['attributes'];

            $attributes=json_decode($attributes,true);

            $aed_attributes=json_decode($v['booking_details']['aed_array']);
                         // debug($aed_attributes);exit;
                $base_fare=0.00;
                if(isset($aed_attributes->aed_basic_price))
                {
                    $base_fare=($aed_attributes->aed_basic_price);
                    $base_fare=str_replace(",", "", $base_fare);
                }
                

                 $markup=0.00;
                if(isset($aed_attributes->aed_markup))
                {
                    $markup=($aed_attributes->aed_markup);
                    $markup=str_replace(",", "", $markup);
                }

                $gst_value=0.00;
                if(isset($aed_attributes->aed_gst_value))
                {
                    $gst_value=($aed_attributes->aed_gst_value);
                    $gst_value=str_replace(",", "", $gst_value);
                }
                $discount_value=0.00;
                if(isset($aed_attributes->aed_discount))
                {
                    $discount_value=($aed_attributes->aed_discount);
                    $discount_value=str_replace(",", "", $discount_value);
                }
                $total_fare=($base_fare+$aed_attributes->aed_convenience_fee)-$discount_value;
            	$package_price=$base_fare-$markup-$gst_value;
            	$package_price=round($package_price);
                $package_price=isset($package_price)? number_format($package_price , 2):0;
                if($v['booking_details']['discount']){
                      // echo number_format($data['booking_details']['discount'],2);
                     // echo isset($data['booking_details']['discount'])? number_format(get_converted_currency_value ( $currency_obj->force_currency_conversion ( $data['booking_details']['discount'] ) ), 2):0;
                      $discount=round($discount_value);  
                    }else{
                      $discount= "NA";
                    }
                     $markup=round($markup);
                    $markup=number_format($markup,2);

                    $conveince_fee=round($aed_attributes->aed_convenience_fee);
                    $conveince_fee=isset($conveince_fee)? number_format($conveince_fee,2):0;
                     $gst_value=round($gst_value);
                    $gst_value= number_format($gst_value,2);
                    $base_fare=round($base_fare);
                	$base_fare=number_format($base_fare, 2);
                	$total=round($total_fare);
                	if($book_attr['agent_markup']!="")
                      {
                        $agent_markup=$book_attr['agent_markup'];
                      }
                      else{
                      $agent_markup="0.00";
                      }
                      $admin_markup=$markup-$book_attr['agent_markup'];
                      if($admin_markup!="")
	                   {
	                        $admin_markup= $admin_markup;
	                    }
	                  else {
	                    $admin_markup="0.00";
	                  }
	                  if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['booking_details']['app_reference'];
			$export_data[$k]['package_name'] = $v['tours_details']['package_name'];
			$export_data[$k]['billing_email'] = $book_attr['billing_email'];
			$export_data[$k]['passenger_contact'] = $book_attr['passenger_contact'];
            $export_data[$k]['lead_pax_name'] = $pax_name;
            $export_data[$k]['departure_date'] =$attributes['departure_date'];
            $export_data[$k]['duration'] = $v['tours_details']['duration'];
            $export_data[$k]['package_price'] = $package_price;            
            $export_data[$k]['admin_markup'] = $admin_markup;
            $export_data[$k]['agent_markup'] = $agent_markup;
           	$export_data[$k]['vat'] = $gst_value;
           	$export_data[$k]['total'] = number_format($total,2);
           	
           	$export_data[$k]['booked_datetime'] = changeDateFormat($v['booking_details']['booked_datetime']);
           	$export_data[$k]['Online'] = "Online";
           
           	
        }
        // debug($export_data);exit;
        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'Reservation Code',
                    'c1' => 'Package Name',
                    'd1' => 'Email',
                    'e1' => 'Phone',
                    'f1' => 'Passanger Name',
                    'g1' => 'Departure Date',
                    'h1' => 'Number Of Days',
                    'i1' => 'Package Price',
                    'j1' => 'Admin Markup',
                    'k1' => 'Agent Markup',
                    'l1' => 'VAT',
                    'm1' => 'Grand Total',
					'n1' => 'BookedOn',
                    'o1' => 'Billing Type',                    
                    
                    
                );

                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'package_name',
                    'd' => 'billing_email',
                    'e' => 'passenger_contact',
                    'f' => 'lead_pax_name',
                    'g' => 'departure_date',
                    'h' => 'duration',
                    'i' => 'package_price',
                    'j' => 'admin_markup',
                    'k' => 'agent_markup',
                    'l' => 'vat',
                    'm' => 'total',
                    'n' => 'booked_datetime',
                    'o' => 'Online', 
                    
                    
                );
           
            $excel_sheet_properties = array(
                'title' => 'Cancelled_Booking_holidayReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Cancelled_Booking_holidayReport',
                'sheet_title' => 'Cancelled_Booking_holidayReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
             $excel=$this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties,'F');
            // echo BASEPATH;exit;
            $file_path=BASEPATH.'reportexcel/'.$excel;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                
            $res = $this->provab_mailer->send_mail($value, 'Holiday Cancelled Booking Report', $message,$file_path);
            
            }
            unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);
        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            // debug($export_data);exit;

        	
               $headings = array("Sl. No.",'Reservation Code','Package Name','Email','Phone','Passanger Name','Departure Date','Number Of Days','Package Price','Admin Markup','Agent Markup','VAT','Grand Total','BookedOn','Billing Type'); 

           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
             $csv= $this->provab_csv->csv_export($headings,'Cancelled_Booking_holidayReport', $export_data,'F');
           $file_path=BASEPATH.'reportcsv/'.$csv;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                
            $res = $this->provab_mailer->send_mail($value, 'Cancelled_Booking_HolidayReport', $message,$file_path);
            
            }
            unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);
           
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			$this->load->library ( 'provab_mailer' ); 
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$holiday_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2b_report_holiday_pdf',$pdf_data);
  			$pdf=$this->provab_pdf->create_pdf($mail_template,'F');
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                
            $res = $this->provab_mailer->send_mail($value, 'Cancelled_Booking_holidayReport', $message,$pdf);
            
            }
            // unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);


        }
    }

	public function b2b_holiday_report($offset = 0)
	{
$module_type='b2c';




		$condition = array ();
		$get_data = $this->input->get ();
		
		/*if (! (isset ( $get_data ['created_datetime_from'] ) || isset ( $get_data ['created_datetime_to'] ))) {
			$get_data ['created_datetime_from'] = date ( 'd-m-Y' );
			$get_data ['created_datetime_to'] = date ( 'd-m-Y' );
		}	*/	
		if (valid_array ( $get_data ) == true) {
			$from_date = trim ( @$get_data ['created_datetime_from'] );
			$to_date = trim ( @$get_data ['created_datetime_to'] );
			if (empty ( $from_date ) == false && empty ( $to_date ) == false) {
				$valid_dates = auto_swipe_dates ( $from_date, $to_date );
				$from_date = $valid_dates ['from_date'];
				$to_date = $valid_dates ['to_date'];
			}
			if (empty ( $from_date ) == false) {
				$condition [] = array (
						'BD.created_datetime',
						'>=',
						$this->db->escape ( db_current_datetime ( $from_date . ' 00:00:00' ) ) 
				);
			}
			if (empty ( $to_date ) == false) {
				$condition [] = array (
						'BD.created_datetime',
						'<=',
						$this->db->escape ( db_current_datetime ( $to_date . ' 23:59:59' ) ) 
				);
			}
			if (empty ( $get_data ['status'] ) == false && strtolower ( $get_data ['status'] ) != 'all') {
				$condition [] = array (
						'BD.status',
						'=',
						$this->db->escape ( $get_data ['status'] ) 
				);
			}			
			if (empty ( $get_data ['phone'] ) == false) {
				$condition [] = array (
						'BD.phone_number',
						' like ',
						$this->db->escape ( '%' . trim ( $get_data ['phone'] ) . '%' ) 
				);
			}			
			if (empty ( $get_data ['email'] ) == false) {
				$condition [] = array (
						'BD.email',
						' like ',
						$this->db->escape ( '%' . trim ( $get_data ['email'] ) . '%' ) 
				);
			}			
			if (empty ( $get_data ['app_reference'] ) == false) {
				$condition [] = array (
						'BD.app_reference',
						' like ',
						$this->db->escape ( '%' . trim ( $get_data ['app_reference'] ) . '%' ) 
				);
			}
			$page_data ['from_date'] = $from_date;
			$page_data ['to_date'] = $to_date;
		}
		$condition [] = array(
			'BD.status ',
			'IN ',
			'("BOOKING_CONFIRMED","CANCELLED","CANCELLATION_IN_PROCESS")',
			);
		/*if ($this->check_operation ( $offset )) {
			$op_data = $this->hotel_model->booking ( $condition );
			$op_data = $this->booking_data_formatter->format_hotel_booking_data ( $op_data, 'b2c' );
			$col = array (
					'app_reference' => 'Application Reference',
					'status' => 'Status',
					'confirmation_reference' => 'Confirmation Reference',
					'fare' => 'Fare',
					'grand_total' => 'Total Fare',
					'payment_mode' => 'Payment Mode',
					'voucher_date' => 'BookedOn' 
			);			
			$this->perform_operation ( $offset, $op_data ['data'] ['booking_details'], $col, 'Hotel Booking Report' );
		}*/
		$offset = intval ( $offset );		
		$this->load->model('tours_model');
		$total_records = $this->tours_model->b2b_booking ( $condition, true );
		$table_data = $this->tours_model->b2b_booking ( $condition, false, $offset, RECORDS_RANGE_1 );
		$page_data ['table_data'] = $table_data ['data'];
		$x = count ( $table_data );
		$this->load->library ( 'pagination' );
		if (count ( $_GET ) > 0)
			$config ['suffix'] = '?' . http_build_query ( $_GET, '', "&" );
			
			
			
		$config ['base_url'] = base_url () . 'index.php/report/b2b_holiday_report/';
		$config ['first_url'] = $config ['base_url'] . '?' . http_build_query ( $_GET );
		$page_data ['total_rows'] = $config ['total_rows'] = $total_records;
		$config ['per_page'] = RECORDS_RANGE_1;
		$this->pagination->initialize ( $config );
		/**
		 * TABLE PAGINATION
		 */
		$page_data ['total_records'] = $config ['total_rows'];
		$page_data ['search_params'] = $get_data;
		$page_data ['status_options'] = get_enum_list ( 'booking_status_options' );
		// $page_data['active_column_list'] = $this->custom_db->single_table_records ( 'report_column_setting','column_name',array('module_name'=>'holiday','module_type'=>$module_type) )['data'];		
		$page_data['module_type'] = $module_type;
		 // debug($page_data);die;
		$this->template->view ( 'report/b2b_holiday', $page_data );	
	}
	public function export_confirmed_booking_transfer_report($op = '') {
        $this->load->model('transferv1_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('transfers');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CONFIRMED'));

        $transfer_booking_data = $this->transferv1_model->b2c_transferv1_report($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $transfer_booking_data = $this->booking_data_formatter->format_transferv1_booking_data($transfer_booking_data, $this->current_module);
        $transfer_booking_data = $transfer_booking_data['data']['booking_details'];



        $export_data = array();
        //debug($transfer_booking_data);exit;
        $i=1;
        foreach ($transfer_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
			$export_data[$k]['confirmation_reference'] = $v['confirmation_reference'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['product_name'] = $v['product_name'];
            $export_data[$k]['grade_desc'] = $v['grade_desc'];
            $export_data[$k]['travel_date'] = $v['travel_date'];
           	$export_data[$k]['NO of adult_count'] = $v['adult_count'];
           	$export_data[$k]['NO of child_count'] = $v['child_count'];
           	$export_data[$k]['NO of youth_count'] = $v['youth_count'];
           	$export_data[$k]['NO of senior_count'] = $v['senior_count'];
           	$export_data[$k]['NO of infant_count'] = $v['infant_count'];
           	$export_data[$k]['Comm.Fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['admin_commission'];
           	$export_data[$k]['tds'] = $v['net_commission_tds'];
           	$export_data[$k]['admin_net_fare'] = $v['admin_net_fare'];
           	$export_data[$k]['admin_markup'] = $v['admin_markup'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['Discount'] = $v['discount'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	$export_data[$k]['Travel date'] = $v['journey_datetime'];
           	$export_data[$k]['booked_date'] = date('d-m-Y', strtotime($v['voucher_date']));
           	        		
           	
        }
			//debug($export_data[$k]['booked_date']);exit;
        if ($op == 'excel') { // excel export
        	//error_reporting(E_ALL);
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'APP reference',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'confirmation reference',
                    'g1' => 'product name',
                    'h1' => 'No of Adult',
                    'i1' => 'No of Child',
                    'j1' => 'No of youth',
                    'k1' => 'No of senior',
                    'l1' => 'No of infant',
                    'm1' => 'City',
					'n1' => 'Travel Date',
                    'o1' => 'Commission Fare',
                    'p1' => 'Commission',
                    'q1' => 'TDS',
                    'r1' => 'Admin NetFare',
                    's1' => 'Admin Markup',
                    't1' => 'GST',
                    'u1' => 'Discount',
                    'v1' => 'Total Fare',
                    'w1' =>'Convinence Fee',
                    'x1'=> 'Booked On',
                    
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'confirmation_reference',
                    'g' => 'product_name',
                    'h' => 'NO of adult_count',
                    'i' => 'NO of child_count',
                    'j' => 'NO of youth_count',
                    'k' => 'NO of senior_count',
                    'l' => 'NO of infant_count',
                    'm' => 'grade_desc',
                    'n' => 'travel_date',
                    'o' => 'Comm.Fare',
                    'p' => 'commission',
                    'q' => 'tds',
                    'r' => 'admin_net_fare',
                    's' => 'admin_markup',
                  	't' => 'gst',
                    'u' => 'Discount',
                    'v' => 'grand_total',
                    'w' => 'convinence_amount',
                    'x' => 'booked_date',
                                        
                );
           
            $excel_sheet_properties = array(
                'title' => 'Confirmed_Booking_transferReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Confirmed_Booking_transferReport',
                'sheet_title' => 'Confirmed_Booking_transferReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
        else if ($op == 'csv') { // excel export
            // echo "dd";exit;
            
               $headings = array('Sl. No.','APP reference','Lead Pax Name','Lead Pax Email','Lead Pax Phone','confirmation reference','product name','No of Adult','No of Child','No of youth','No of senior','No of infant','City','Travel Date','Commission Fare','Commission','TDS','Admin NetFare','Admin Markup','GST','Discount','Total Fare','Convinence Fee','Booked On'); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
           $this->provab_csv->csv_export($headings,'Confirmed_Booking_transferReport', $export_data);
           
           
            
            
        }
        else if($op == 'pdf')
        {
            $this->load->library ( 'provab_pdf' );
            
            $create_pdf = new Provab_Pdf();
            $pdf_data['export_data']=$transfer_booking_data;
            // debug($pdf_data['export_data']);exit;
            $mail_template =$this->template->isolated_view('report/b2c_report_transferReport_pdf',$pdf_data);
            $this->provab_pdf->create_pdf($mail_template);
            


        } 
    }
    public function export_all_booking_transfer_report($op = '') {
        $this->load->model('transferv1_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('transfers');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array();

        $transfer_booking_data = $this->transferv1_model->b2c_transferv1_report($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $transfer_booking_data = $this->booking_data_formatter->format_transferv1_booking_data($transfer_booking_data, $this->current_module);
        $transfer_booking_data = $transfer_booking_data['data']['booking_details'];



        $export_data = array();
        //debug($transfer_booking_data);exit;
        $i=1;
        foreach ($transfer_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
			$export_data[$k]['confirmation_reference'] = $v['confirmation_reference'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['product_name'] = $v['product_name'];
            $export_data[$k]['grade_desc'] = $v['grade_desc'];
            $export_data[$k]['travel_date'] = $v['travel_date'];
           	$export_data[$k]['NO of adult_count'] = $v['adult_count'];
           	$export_data[$k]['NO of child_count'] = $v['child_count'];
           	$export_data[$k]['NO of youth_count'] = $v['youth_count'];
           	$export_data[$k]['NO of senior_count'] = $v['senior_count'];
           	$export_data[$k]['NO of infant_count'] = $v['infant_count'];
           	$export_data[$k]['Comm.Fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['admin_commission'];
           	$export_data[$k]['tds'] = $v['net_commission_tds'];
           	$export_data[$k]['admin_net_fare'] = $v['admin_net_fare'];
           	$export_data[$k]['admin_markup'] = $v['admin_markup'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['Discount'] = $v['discount'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	$export_data[$k]['Travel date'] = $v['journey_datetime'];
           	$export_data[$k]['status'] = $v['status'];
           	$export_data[$k]['booked_date'] = date('d-m-Y', strtotime($v['voucher_date']));
           	        		
           	
        }
			//debug($export_data[$k]['booked_date']);exit;
        if ($op == 'excel') { // excel export
        	//error_reporting(E_ALL);
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'APP reference',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'confirmation reference',
                    'g1' => 'product name',
                    'h1' => 'No of Adult',
                    'i1' => 'No of Child',
                    'j1' => 'No of youth',
                    'k1' => 'No of senior',
                    'l1' => 'No of infant',
                    'm1' => 'City',
					'n1' => 'Travel Date',
                    'o1' => 'Commission Fare',
                    'p1' => 'Commission',
                    'q1' => 'TDS',
                    'r1' => 'Admin NetFare',
                    's1' => 'Admin Markup',
                    't1' => 'GST',
                    'u1' => 'Discount',
                    'v1' => 'Total Fare',
                    'w1' =>'Convinence Fee',
                    'x1'=> 'Status',
                    'y1'=> 'Booked On',
                    
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'confirmation_reference',
                    'g' => 'product_name',
                    'h' => 'NO of adult_count',
                    'i' => 'NO of child_count',
                    'j' => 'NO of youth_count',
                    'k' => 'NO of senior_count',
                    'l' => 'NO of infant_count',
                    'm' => 'grade_desc',
                    'n' => 'travel_date',
                    'o' => 'Comm.Fare',
                    'p' => 'commission',
                    'q' => 'tds',
                    'r' => 'admin_net_fare',
                    's' => 'admin_markup',
                  	't' => 'gst',
                    'u' => 'Discount',
                    'v' => 'grand_total',
                    'w' => 'convinence_amount',
                    'x' => 'status',
                    'y' => 'booked_date',
                                        
                );
           
            $excel_sheet_properties = array(
                'title' => 'All_Booking_transferReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'All_Booking_transferReport',
                'sheet_title' => 'All_Booking_transferReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
        else if ($op == 'csv') { // excel export
            // echo "dd";exit;
            
               $headings = array('Sl. No.','APP reference','Lead Pax Name','Lead Pax Email','Lead Pax Phone','confirmation reference','product name','No of Adult','No of Child','No of youth','No of senior','No of infant','City','Travel Date','Commission Fare','Commission','TDS','Admin NetFare','Admin Markup','GST','Discount','Total Fare','Convinence Fee','Status','Booked On'); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
           $this->provab_csv->csv_export($headings,'All_Booking_transferReport', $export_data);
           
           
            
            
        }
        else if($op == 'pdf')
        {
            $this->load->library ( 'provab_pdf' );
            
            $create_pdf = new Provab_Pdf();
            $pdf_data['export_data']=$transfer_booking_data;
            // debug($pdf_data['export_data']);exit;
            $mail_template =$this->template->isolated_view('report/b2c_report_transferReport_all_pdf',$pdf_data);
            $this->provab_pdf->create_pdf($mail_template);
            


        } 
    }
    public function export_confirmed_booking_transfer_report_email($op = '') {
        $this->load->model('transferv1_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('transfers');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CONFIRMED'));

        $transfer_booking_data = $this->transferv1_model->b2c_transferv1_report($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $transfer_booking_data = $this->booking_data_formatter->format_transferv1_booking_data($transfer_booking_data, $this->current_module);
        $transfer_booking_data = $transfer_booking_data['data']['booking_details'];



        $export_data = array();
        //debug($transfer_booking_data);exit;
        $i=1;
        foreach ($transfer_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
			$export_data[$k]['confirmation_reference'] = $v['confirmation_reference'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['product_name'] = $v['product_name'];
            $export_data[$k]['grade_desc'] = $v['grade_desc'];
            $export_data[$k]['travel_date'] = $v['travel_date'];
           	$export_data[$k]['NO of adult_count'] = $v['adult_count'];
           	$export_data[$k]['NO of child_count'] = $v['child_count'];
           	$export_data[$k]['NO of youth_count'] = $v['youth_count'];
           	$export_data[$k]['NO of senior_count'] = $v['senior_count'];
           	$export_data[$k]['NO of infant_count'] = $v['infant_count'];
           	$export_data[$k]['Comm.Fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['admin_commission'];
           	$export_data[$k]['tds'] = $v['net_commission_tds'];
           	$export_data[$k]['admin_net_fare'] = $v['admin_net_fare'];
           	$export_data[$k]['admin_markup'] = $v['admin_markup'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['Discount'] = $v['discount'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	$export_data[$k]['Travel date'] = $v['journey_datetime'];
           	$export_data[$k]['booked_date'] = date('d-m-Y', strtotime($v['voucher_date']));
           	        		
           	
        }
			//debug($export_data[$k]['booked_date']);exit;
        if ($op == 'excel') { // excel export
        	//error_reporting(E_ALL);
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'APP reference',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'confirmation reference',
                    'g1' => 'product name',
                    'h1' => 'No of Adult',
                    'i1' => 'No of Child',
                    'j1' => 'No of youth',
                    'k1' => 'No of senior',
                    'l1' => 'No of infant',
                    'm1' => 'City',
					'n1' => 'Travel Date',
                    'o1' => 'Commission Fare',
                    'p1' => 'Commission',
                    'q1' => 'TDS',
                    'r1' => 'Admin NetFare',
                    's1' => 'Admin Markup',
                    't1' => 'GST',
                    'u1' => 'Discount',
                    'v1' => 'Total Fare',
                    'w1' =>'Convinence Fee',
                    'x1'=> 'Booked On',
                    
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'confirmation_reference',
                    'g' => 'product_name',
                    'h' => 'NO of adult_count',
                    'i' => 'NO of child_count',
                    'j' => 'NO of youth_count',
                    'k' => 'NO of senior_count',
                    'l' => 'NO of infant_count',
                    'm' => 'grade_desc',
                    'n' => 'travel_date',
                    'o' => 'Comm.Fare',
                    'p' => 'commission',
                    'q' => 'tds',
                    'r' => 'admin_net_fare',
                    's' => 'admin_markup',
                  	't' => 'gst',
                    'u' => 'Discount',
                    'v' => 'grand_total',
                    'w' => 'convinence_amount',
                    'x' => 'booked_date',
                                        
                );
           
            $excel_sheet_properties = array(
                'title' => 'Confirmed_Booking_transferReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Confirmed_Booking_transferReport',
                'sheet_title' => 'Confirmed_Booking_transferReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $excel=$this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties,'F');
            // echo BASEPATH;exit;
            $file_path=BASEPATH.'reportexcel/'.$excel;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                
            $res = $this->provab_mailer->send_mail($value, 'Transfer Confirmed Booking Report', $message,$file_path);
            
            }
            unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);
        }
        else if ($op == 'csv') { // excel export
            // echo "dd";exit;
            
               $headings = array('Sl. No.','APP reference','Lead Pax Name','Lead Pax Email','Lead Pax Phone','confirmation reference','product name','No of Adult','No of Child','No of youth','No of senior','No of infant','City','Travel Date','Commission Fare','Commission','TDS','Admin NetFare','Admin Markup','GST','Discount','Total Fare','Convinence Fee','Booked On'); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
           $csv= $this->provab_csv->csv_export($headings,'Confirmed_Booking_transferReport', $export_data,'F');
           $file_path=BASEPATH.'reportcsv/'.$csv;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                
            $res = $this->provab_mailer->send_mail($value, 'Confirmed_Booking_transferReport', $message,$file_path);
            
            }
            unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);
           
           
            
            
        }
        else if($op == 'pdf')
        {
            $this->load->library ( 'provab_pdf' );
            
            $create_pdf = new Provab_Pdf();
            $pdf_data['export_data']=$transfer_booking_data;
            // debug($pdf_data['export_data']);exit;
            $mail_template =$this->template->isolated_view('report/b2c_report_transferReport_pdf',$pdf_data);
             $pdf=$this->provab_pdf->create_pdf($mail_template,'F');
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                
            $res = $this->provab_mailer->send_mail($value, 'Confirmed_Booking_transferReport', $message,$pdf);
            
            }
            // unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);
            


        } 
    }
    public function export_all_booking_transfer_report_email($op = '') {
        $this->load->model('transferv1_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('transfers');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array();

        $transfer_booking_data = $this->transferv1_model->b2c_transferv1_report($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $transfer_booking_data = $this->booking_data_formatter->format_transferv1_booking_data($transfer_booking_data, $this->current_module);
        $transfer_booking_data = $transfer_booking_data['data']['booking_details'];



        $export_data = array();
        //debug($transfer_booking_data);exit;
        $i=1;
        foreach ($transfer_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
			$export_data[$k]['confirmation_reference'] = $v['confirmation_reference'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['product_name'] = $v['product_name'];
            $export_data[$k]['grade_desc'] = $v['grade_desc'];
            $export_data[$k]['travel_date'] = $v['travel_date'];
           	$export_data[$k]['NO of adult_count'] = $v['adult_count'];
           	$export_data[$k]['NO of child_count'] = $v['child_count'];
           	$export_data[$k]['NO of youth_count'] = $v['youth_count'];
           	$export_data[$k]['NO of senior_count'] = $v['senior_count'];
           	$export_data[$k]['NO of infant_count'] = $v['infant_count'];
           	$export_data[$k]['Comm.Fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['admin_commission'];
           	$export_data[$k]['tds'] = $v['net_commission_tds'];
           	$export_data[$k]['admin_net_fare'] = $v['admin_net_fare'];
           	$export_data[$k]['admin_markup'] = $v['admin_markup'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['Discount'] = $v['discount'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	$export_data[$k]['Travel date'] = $v['journey_datetime'];
           	$export_data[$k]['status'] = $v['status'];
           	$export_data[$k]['booked_date'] = date('d-m-Y', strtotime($v['voucher_date']));
           	        		
           	
        }
			//debug($export_data[$k]['booked_date']);exit;
        if ($op == 'excel') { // excel export
        	//error_reporting(E_ALL);
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'APP reference',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'confirmation reference',
                    'g1' => 'product name',
                    'h1' => 'No of Adult',
                    'i1' => 'No of Child',
                    'j1' => 'No of youth',
                    'k1' => 'No of senior',
                    'l1' => 'No of infant',
                    'm1' => 'City',
					'n1' => 'Travel Date',
                    'o1' => 'Commission Fare',
                    'p1' => 'Commission',
                    'q1' => 'TDS',
                    'r1' => 'Admin NetFare',
                    's1' => 'Admin Markup',
                    't1' => 'GST',
                    'u1' => 'Discount',
                    'v1' => 'Total Fare',
                    'w1' =>'Convinence Fee',
                    'x1'=> 'status',
                    'y1'=> 'Booked On',
                    
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'confirmation_reference',
                    'g' => 'product_name',
                    'h' => 'NO of adult_count',
                    'i' => 'NO of child_count',
                    'j' => 'NO of youth_count',
                    'k' => 'NO of senior_count',
                    'l' => 'NO of infant_count',
                    'm' => 'grade_desc',
                    'n' => 'travel_date',
                    'o' => 'Comm.Fare',
                    'p' => 'commission',
                    'q' => 'tds',
                    'r' => 'admin_net_fare',
                    's' => 'admin_markup',
                  	't' => 'gst',
                    'u' => 'Discount',
                    'v' => 'grand_total',
                    'w' => 'convinence_amount',
                    'x' => 'status',
                    'y' => 'booked_date',
                                        
                );
           
            $excel_sheet_properties = array(
                'title' => 'All_Booking_transferReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'All_Booking_transferReport',
                'sheet_title' => 'All_Booking_transferReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $excel=$this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties,'F');
            // echo BASEPATH;exit;
            $file_path=BASEPATH.'reportexcel/'.$excel;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                
            $res = $this->provab_mailer->send_mail($value, 'Transfer All Booking Report', $message,$file_path);
            
            }
            unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);
        }
        else if ($op == 'csv') { // excel export
            // echo "dd";exit;
            
               $headings = array('Sl. No.','APP reference','Lead Pax Name','Lead Pax Email','Lead Pax Phone','confirmation reference','product name','No of Adult','No of Child','No of youth','No of senior','No of infant','City','Travel Date','Commission Fare','Commission','TDS','Admin NetFare','Admin Markup','GST','Discount','Total Fare','Convinence Fee','Status','Booked On'); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
           $csv= $this->provab_csv->csv_export($headings,'All_Booking_transferReport', $export_data,'F');
           $file_path=BASEPATH.'reportcsv/'.$csv;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                
            $res = $this->provab_mailer->send_mail($value, 'All_Booking_transferReport', $message,$file_path);
            
            }
            unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);
           
           
            
            
        }
        else if($op == 'pdf')
        {
            $this->load->library ( 'provab_pdf' );
            
            $create_pdf = new Provab_Pdf();
            $pdf_data['export_data']=$transfer_booking_data;
            // debug($pdf_data['export_data']);exit;
            $mail_template =$this->template->isolated_view('report/b2c_report_transferReport_all_pdf',$pdf_data);
             $pdf=$this->provab_pdf->create_pdf($mail_template,'F');
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                
            $res = $this->provab_mailer->send_mail($value, 'All_Booking_transferReport', $message,$pdf);
            
            }
            // unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);
            


        } 
    }
    public function export_cancelled_booking_transfer_report($op = '') {
        $this->load->model('transferv1_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('transfers');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CANCELLED'));

        $transfer_booking_data = $this->transferv1_model->b2c_transferv1_report($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $transfer_booking_data = $this->booking_data_formatter->format_transferv1_booking_data($transfer_booking_data, $this->current_module);
        $transfer_booking_data = $transfer_booking_data['data']['booking_details'];



        $export_data = array();
        //debug($transfer_booking_data);exit;
        $i=1;
        foreach ($transfer_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
			$export_data[$k]['confirmation_reference'] = $v['confirmation_reference'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['product_name'] = $v['product_name'];
            $export_data[$k]['grade_desc'] = $v['grade_desc'];
            $export_data[$k]['travel_date'] = $v['travel_date'];
           	$export_data[$k]['NO of adult_count'] = $v['adult_count'];
           	$export_data[$k]['NO of child_count'] = $v['child_count'];
           	$export_data[$k]['NO of youth_count'] = $v['youth_count'];
           	$export_data[$k]['NO of senior_count'] = $v['senior_count'];
           	$export_data[$k]['NO of infant_count'] = $v['infant_count'];
           	$export_data[$k]['Comm.Fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['admin_commission'];
           	$export_data[$k]['tds'] = $v['net_commission_tds'];
           	$export_data[$k]['admin_net_fare'] = $v['admin_net_fare'];
           	$export_data[$k]['admin_markup'] = $v['admin_markup'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['Discount'] = $v['discount'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	$export_data[$k]['Travel date'] = $v['journey_datetime'];
           	$export_data[$k]['booked_date'] = date('d-m-Y', strtotime($v['voucher_date']));
           	        		
           	
        }
		//debug($export_data[$k]['booked_date']);exit;
        if ($op == 'excel') { // excel export
        	//error_reporting(E_ALL);
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'APP reference',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'confirmation reference',
                    'g1' => 'product name',
                    'h1' => 'No of Adult',
                    'i1' => 'No of Child',
                    'j1' => 'No of youth',
                    'k1' => 'No of senior',
                    'l1' => 'No of infant',
                    'm1' => 'City',
					'n1' => 'Travel Date',
                    'o1' => 'Commission Fare',
                    'p1' => 'Commission',
                    'q1' => 'TDS',
                    'r1' => 'Admin NetFare',
                    's1' => 'Admin Markup',
                    't1' => 'GST',
                    'u1' => 'Discount',
                    'v1' => 'Total Fare',
                    'w1' =>'Convinence Fee',
                    'x1'=> 'Booked On',
                    );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'confirmation_reference',
                    'g' => 'product_name',
                    'h' => 'NO of adult_count',
                    'i' => 'NO of child_count',
                    'j' => 'NO of youth_count',
                    'k' => 'NO of senior_count',
                    'l' => 'NO of infant_count',
                    'm' => 'grade_desc',
                    'n' => 'travel_date',
                    'o' => 'Comm.Fare',
                    'p' => 'commission',
                    'q' => 'tds',
                    'r' => 'admin_net_fare',
                    's' => 'admin_markup',
                  	't' => 'gst',
                    'u' => 'Discount',
                    'v' => 'grand_total',
                    'w' => 'convinence_amount',
                    'x' => 'booked_date',
                                        
                );
           
           
            $excel_sheet_properties = array(
                'title' => 'Cancelled_Booking_transferReport' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Cancelled_Booking_transferReport',
                'sheet_title' => 'Cancelled_Booking_transferReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
        else if ($op == 'csv') { // excel export
            // echo "dd";exit;
            
               $headings = array('Sl. No.','APP reference','Lead Pax Name','Lead Pax Email','Lead Pax Phone','confirmation reference','product name','No of Adult','No of Child','No of youth','No of senior','No of infant','City','Travel Date','Commission Fare','Commission','TDS','Admin NetFare','Admin Markup','GST','Discount','Total Fare','Convinence Fee','Booked On'); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
           $this->provab_csv->csv_export($headings,'Cancelled_Booking_transferReport', $export_data);
           
           
            
            
        }
        else if($op == 'pdf')
        {
            $this->load->library ( 'provab_pdf' );
            
            $create_pdf = new Provab_Pdf();
            $pdf_data['export_data']=$transfer_booking_data;
            // debug($pdf_data['export_data']);exit;
            $mail_template =$this->template->isolated_view('report/b2c_report_transferReport_pdf',$pdf_data);
            $this->provab_pdf->create_pdf($mail_template);
            


        } 
    }
    public function export_cancelled_booking_transfer_report_email($op = '') {
        $this->load->model('transferv1_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('transfers');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CANCELLED'));

        $transfer_booking_data = $this->transferv1_model->b2c_transferv1_report($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $transfer_booking_data = $this->booking_data_formatter->format_transferv1_booking_data($transfer_booking_data, $this->current_module);
        $transfer_booking_data = $transfer_booking_data['data']['booking_details'];



        $export_data = array();
        //debug($transfer_booking_data);exit;
        $i=1;
        foreach ($transfer_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
			$export_data[$k]['confirmation_reference'] = $v['confirmation_reference'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['product_name'] = $v['product_name'];
            $export_data[$k]['grade_desc'] = $v['grade_desc'];
            $export_data[$k]['travel_date'] = $v['travel_date'];
           	$export_data[$k]['NO of adult_count'] = $v['adult_count'];
           	$export_data[$k]['NO of child_count'] = $v['child_count'];
           	$export_data[$k]['NO of youth_count'] = $v['youth_count'];
           	$export_data[$k]['NO of senior_count'] = $v['senior_count'];
           	$export_data[$k]['NO of infant_count'] = $v['infant_count'];
           	$export_data[$k]['Comm.Fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['admin_commission'];
           	$export_data[$k]['tds'] = $v['net_commission_tds'];
           	$export_data[$k]['admin_net_fare'] = $v['admin_net_fare'];
           	$export_data[$k]['admin_markup'] = $v['admin_markup'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['Discount'] = $v['discount'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	$export_data[$k]['Travel date'] = $v['journey_datetime'];
           	$export_data[$k]['booked_date'] = date('d-m-Y', strtotime($v['voucher_date']));
           	        		
           	
        }
		//debug($export_data[$k]['booked_date']);exit;
        if ($op == 'excel') { // excel export
        	//error_reporting(E_ALL);
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'APP reference',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'confirmation reference',
                    'g1' => 'product name',
                    'h1' => 'No of Adult',
                    'i1' => 'No of Child',
                    'j1' => 'No of youth',
                    'k1' => 'No of senior',
                    'l1' => 'No of infant',
                    'm1' => 'City',
					'n1' => 'Travel Date',
                    'o1' => 'Commission Fare',
                    'p1' => 'Commission',
                    'q1' => 'TDS',
                    'r1' => 'Admin NetFare',
                    's1' => 'Admin Markup',
                    't1' => 'GST',
                    'u1' => 'Discount',
                    'v1' => 'Total Fare',
                    'w1' =>'Convinence Fee',
                    'x1'=> 'Booked On',
                    );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'confirmation_reference',
                    'g' => 'product_name',
                    'h' => 'NO of adult_count',
                    'i' => 'NO of child_count',
                    'j' => 'NO of youth_count',
                    'k' => 'NO of senior_count',
                    'l' => 'NO of infant_count',
                    'm' => 'grade_desc',
                    'n' => 'travel_date',
                    'o' => 'Comm.Fare',
                    'p' => 'commission',
                    'q' => 'tds',
                    'r' => 'admin_net_fare',
                    's' => 'admin_markup',
                  	't' => 'gst',
                    'u' => 'Discount',
                    'v' => 'grand_total',
                    'w' => 'convinence_amount',
                    'x' => 'booked_date',
                                        
                );
           
           
            $excel_sheet_properties = array(
                'title' => 'Cancelled_Booking_transferReport' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Cancelled_Booking_transferReport',
                'sheet_title' => 'Cancelled_Booking_transferReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $excel=$this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties,'F');
            // echo BASEPATH;exit;
            $file_path=BASEPATH.'reportexcel/'.$excel;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                
            $res = $this->provab_mailer->send_mail($value, 'Transfer Cancelled Booking Report', $message,$file_path);
            
            }
            unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);
        }
        else if ($op == 'csv') { // excel export
            // echo "dd";exit;
            
               $headings = array('Sl. No.','APP reference','Lead Pax Name','Lead Pax Email','Lead Pax Phone','confirmation reference','product name','No of Adult','No of Child','No of youth','No of senior','No of infant','City','Travel Date','Commission Fare','Commission','TDS','Admin NetFare','Admin Markup','GST','Discount','Total Fare','Convinence Fee','Booked On'); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
           $csv= $this->provab_csv->csv_export($headings,'Cancelled_Booking_transferReport', $export_data,'F');
           $file_path=BASEPATH.'reportcsv/'.$csv;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                
            $res = $this->provab_mailer->send_mail($value, 'Cancelled_Booking_transferReport', $message,$file_path);
            
            }
            unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);
           
           
            
            
        }
        else if($op == 'pdf')
        {
            $this->load->library ( 'provab_pdf' );
            
            $create_pdf = new Provab_Pdf();
            $pdf_data['export_data']=$transfer_booking_data;
            // debug($pdf_data['export_data']);exit;
            $mail_template =$this->template->isolated_view('report/b2c_report_transferReport_pdf',$pdf_data);
             $pdf=$this->provab_pdf->create_pdf($mail_template,'F');
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                
            $res = $this->provab_mailer->send_mail($value, 'Cancelled_Booking_transferReport', $message,$pdf);
            
            }
            // unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);
            


        } 
    }
    public function export_confirmed_booking_transfer_report_b2b($op = '',$all='') {
        $this->load->model('transferv1_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('transfers');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        if($all=="all"){

        $condition[] = array();
        }
        else
        {

        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CONFIRMED'));
        }

        $transfer_booking_data = $this->transferv1_model->b2b_transferv1_report($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $transfer_booking_data = $this->booking_data_formatter->format_transferv1_booking_data($transfer_booking_data, $this->current_module);
        $transfer_booking_data = $transfer_booking_data['data']['booking_details'];



        $export_data = array();
        //debug($transfer_booking_data);exit;
        $i=1;
        foreach ($transfer_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
			$export_data[$k]['agency_name'] = $v['agency_name'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['product_name'] = $v['product_name'];
            $export_data[$k]['Destination'] = $v['Destination'];
            $export_data[$k]['created_datetime'] = $v['created_datetime'];
            $export_data[$k]['travel_date'] = $v['travel_date'];
           	$export_data[$k]['confirmation_reference'] = $v['confirmation_reference'];
           	$export_data[$k]['Comm_Fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['admin_commission'];
           	$export_data[$k]['admin_tds'] = $v['admin_tds'];
           	$export_data[$k]['net_fare'] = $v['net_fare'];
           	$export_data[$k]['admin_profit'] = $v['admin_commission'];
           	$export_data[$k]['admin_markup'] = $v['admin_markup'];
           	$export_data[$k]['agent_commission'] = $v['agent_commission'];
           	$export_data[$k]['agent_tds'] = $v['agent_tds'];
           	$export_data[$k]['agent_netfare'] = $v['agent_buying_price'];
           	$export_data[$k]['agent_markup'] = $v['agent_markup'];
           	$export_data[$k]['gst'] = $v['gst'];
           	if($all=="all"){
           		$export_data[$k]['status'] = $v['status'];
           	}
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	        		
           	
        }
			//debug($export_data[$k]['booked_date']);exit;
        if ($op == 'excel') { // excel export
        	//error_reporting(E_ALL);
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'APP reference',
                    'c1' => 'Agency name',
                    'd1' => 'Lead Pax Name',
                    'e1' => 'Lead Pax Email',
                    'f1' => 'Lead Pax Phone Number',
                    'g1' => 'Activity Name',
                    'h1' => 'Acitvity Location',
                    'i1' => 'Booked On',
                    'j1' => 'Journey Date',
                    'k1' => 'Confirmation Reference',
                    'l1' => 'Commission Fare',
                    'm1' => 'Commission',
					'n1' => 'TDS',
                    'o1' => 'Admin NetFare',
                    'p1' => 'Admin Profit',
                    'q1' => 'Admin Markup',
                    'r1' => 'Agent Commission',
                    's1' => 'Agent TDS',
                    't1' => 'Agent Net Fare',
                    'u1' => 'Agent Markup',
                    'v1' => 'GST',
                    
                   
                );
           		 if($all=="all"){
           		$headings['x1'] = 'status';
           		$headings['y1'] = 'TotalFare';
	           	} 
	           	else
	           	{

           			$headings['x1'] = 'TotalFare';
	           	}
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'agency_name',
                    'd' => 'lead_pax_name',
                    'e' => 'lead_pax_email',
                    'f' => 'lead_pax_phone_number',
                    'g' => 'product_name',
                    'h' => 'Destination',
                    'i' => 'created_datetime',
                    'j' => 'travel_date',
                    'k' => 'confirmation_reference',
                    'l' => 'Comm_Fare',
                    'm' => 'commission',
                    'n' => 'admin_tds',
                    'o' => 'net_fare',
                    'p' => 'admin_profit',
                    'q' => 'admin_markup',
                    'r' => 'agent_commission',
                    's' => 'agent_tds',
                  	't' => 'agent_netfare',
                    'u' => 'agent_markup',
                    'v' => 'gst',
                   
                                        
                );
                 if($all=="all"){
           		$fields['x'] = 'status';
           		$fields['y'] = 'grand_total';
           		$excel_sheet_properties = array(
                'title' => 'All_Booking_transferReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'All_Booking_transferReport',
                'sheet_title' => 'All_Booking_transferReport'
            );
	           	} 
	           	else
	           	{

           			$fields['x'] = 'grand_total';
           			$excel_sheet_properties = array(
                'title' => 'Confirmed_Booking_transferReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Confirmed_Booking_transferReport',
                'sheet_title' => 'Confirmed_Booking_transferReport'
            );
	           	}

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
        else if ($op == 'csv') { // excel export
            // echo "dd";exit;
            
              
            if($all=="all"){
           		 $headings = array('Sl. No.','APP reference','Agency name','Lead Pax Name','Lead Pax Email','Lead Pax Phone','Activity Name','Acitvity Location','Booked On','Journey Date','Confirmation Reference','Commission Fare','Commission','TDS','Admin NetFare','Admin Profit','Admin Markup','Agent Commission','Agent TDS','Agent Net Fare','Agent Markup','GST','status','TotalFare'); 
	           	} 
	           	else
	           	{

           			 $headings = array('Sl. No.','APP reference','Agency name','Lead Pax Name','Lead Pax Email','Lead Pax Phone','Activity Name','Acitvity Location','Booked On','Journey Date','Confirmation Reference','Commission Fare','Commission','TDS','Admin NetFare','Admin Profit','Admin Markup','Agent Commission','Agent TDS','Agent Net Fare','Agent Markup','GST','TotalFare'); 
	           	}
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
           
           if($all=="all")
           {
           		$this->provab_csv->csv_export($headings,'All_Booking_transferReport', $export_data);
           }
           else
           {
           		$this->provab_csv->csv_export($headings,'Confirmed_Booking_transferReport', $export_data);
           }
           
            
            
        }
        else if($op == 'pdf')
        {
            $this->load->library ( 'provab_pdf' );
            
            $create_pdf = new Provab_Pdf();
            $pdf_data['export_data']=$transfer_booking_data;
            $pdf_data['record_type']=$all;
            // debug($pdf_data['export_data']);exit;
            $mail_template =$this->template->isolated_view('report/b2b_report_transferReport_pdf',$pdf_data);
            $this->provab_pdf->create_pdf($mail_template);
            


        } 
    }
    public function export_confirmed_booking_transfer_report_b2b_email($op = '',$all='') {
        $this->load->model('transferv1_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('transfers');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        if($all=="all"){

        $condition[] = array();
        }
        else
        {

        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CONFIRMED'));
        }

        $transfer_booking_data = $this->transferv1_model->b2b_transferv1_report($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $transfer_booking_data = $this->booking_data_formatter->format_transferv1_booking_data($transfer_booking_data, $this->current_module);
        $transfer_booking_data = $transfer_booking_data['data']['booking_details'];



        $export_data = array();
        //debug($transfer_booking_data);exit;
        $i=1;
        foreach ($transfer_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
			$export_data[$k]['agency_name'] = $v['agency_name'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['product_name'] = $v['product_name'];
            $export_data[$k]['Destination'] = $v['Destination'];
            $export_data[$k]['created_datetime'] = $v['created_datetime'];
            $export_data[$k]['travel_date'] = $v['travel_date'];
           	$export_data[$k]['confirmation_reference'] = $v['confirmation_reference'];
           	$export_data[$k]['Comm_Fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['admin_commission'];
           	$export_data[$k]['admin_tds'] = $v['admin_tds'];
           	$export_data[$k]['net_fare'] = $v['net_fare'];
           	$export_data[$k]['admin_profit'] = $v['admin_commission'];
           	$export_data[$k]['admin_markup'] = $v['admin_markup'];
           	$export_data[$k]['agent_commission'] = $v['agent_commission'];
           	$export_data[$k]['agent_tds'] = $v['agent_tds'];
           	$export_data[$k]['agent_netfare'] = $v['agent_buying_price'];
           	$export_data[$k]['agent_markup'] = $v['agent_markup'];
           	$export_data[$k]['gst'] = $v['gst'];
           	if($all=="all"){
           		$export_data[$k]['status'] = $v['status'];
           	}
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	        		
           	
        }
			//debug($export_data[$k]['booked_date']);exit;
        if ($op == 'excel') { // excel export
        	//error_reporting(E_ALL);
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'APP reference',
                    'c1' => 'Agency name',
                    'd1' => 'Lead Pax Name',
                    'e1' => 'Lead Pax Email',
                    'f1' => 'Lead Pax Phone Number',
                    'g1' => 'Activity Name',
                    'h1' => 'Acitvity Location',
                    'i1' => 'Booked On',
                    'j1' => 'Journey Date',
                    'k1' => 'Confirmation Reference',
                    'l1' => 'Commission Fare',
                    'm1' => 'Commission',
					'n1' => 'TDS',
                    'o1' => 'Admin NetFare',
                    'p1' => 'Admin Profit',
                    'q1' => 'Admin Markup',
                    'r1' => 'Agent Commission',
                    's1' => 'Agent TDS',
                    't1' => 'Agent Net Fare',
                    'u1' => 'Agent Markup',
                    'v1' => 'GST',
                   
                   
                );
           		 if($all=="all"){
           		$headings['x1'] = 'status';
           		$headings['y1'] = 'TotalFare';
	           	} 
	           	else
	           	{

           			$headings['x1'] = 'TotalFare';
	           	}
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'agency_name',
                    'd' => 'lead_pax_name',
                    'e' => 'lead_pax_email',
                    'f' => 'lead_pax_phone_number',
                    'g' => 'product_name',
                    'h' => 'Destination',
                    'i' => 'created_datetime',
                    'j' => 'travel_date',
                    'k' => 'confirmation_reference',
                    'l' => 'Comm_Fare',
                    'm' => 'commission',
                    'n' => 'admin_tds',
                    'o' => 'net_fare',
                    'p' => 'admin_profit',
                    'q' => 'admin_markup',
                    'r' => 'agent_commission',
                    's' => 'agent_tds',
                  	't' => 'agent_netfare',
                    'u' => 'agent_markup',
                    'v' => 'gst',
                    
                                        
                );
                 if($all=="all"){
           		$fields['x'] = 'status';
           		$fields['y'] = 'grand_total';
           		$excel_sheet_properties = array(
                'title' => 'All_Booking_transferReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'All_Booking_transferReport',
                'sheet_title' => 'All_Booking_transferReport'
            );
	           	} 
	           	else
	           	{

           			$fields['x'] = 'grand_total';
           			$excel_sheet_properties = array(
                'title' => 'Confirmed_Booking_transferReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Confirmed_Booking_transferReport',
                'sheet_title' => 'Confirmed_Booking_transferReport'
            );
	           	}
           
            

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $excel=$this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties,'F');
            // echo BASEPATH;exit;
            $file_path=BASEPATH.'reportexcel/'.$excel;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                
            $res = $this->provab_mailer->send_mail($value, 'Transfer Confirmed Booking Report', $message,$file_path);
            
            }
            unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);
        }
        else if ($op == 'csv') { // excel export
            // echo "dd";exit;
            
              if($all=="all"){
           		 $headings = array('Sl. No.','APP reference','Agency name','Lead Pax Name','Lead Pax Email','Lead Pax Phone','Activity Name','Acitvity Location','Booked On','Journey Date','Confirmation Reference','Commission Fare','Commission','TDS','Admin NetFare','Admin Profit','Admin Markup','Agent Commission','Agent TDS','Agent Net Fare','Agent Markup','GST','status','TotalFare'); 
	           	} 
	           	else
	           	{

           			 $headings = array('Sl. No.','APP reference','Agency name','Lead Pax Name','Lead Pax Email','Lead Pax Phone','Activity Name','Acitvity Location','Booked On','Journey Date','Confirmation Reference','Commission Fare','Commission','TDS','Admin NetFare','Admin Profit','Admin Markup','Agent Commission','Agent TDS','Agent Net Fare','Agent Markup','GST','TotalFare'); 
	           	}
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
            if($all=="all"){
           		 $csv= $this->provab_csv->csv_export($headings,'All_Booking_transferReport', $export_data,'F');
	           	} 
	           	else
	           	{

           			 $csv= $this->provab_csv->csv_export($headings,'Confirmed_Booking_transferReport', $export_data,'F');
	           	}
            
           $file_path=BASEPATH.'reportcsv/'.$csv;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                if($all=="all"){
           		 $res = $this->provab_mailer->send_mail($value, 'All_Booking_transferReport', $message,$file_path);
	           	} 
	           	else
	           	{

           			 $res = $this->provab_mailer->send_mail($value, 'Confirmed_Booking_transferReport', $message,$file_path);
	           	}
            
            
            }
            unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);
           
           
            
            
        }
        else if($op == 'pdf')
        {
            $this->load->library ( 'provab_pdf' );
            
            $create_pdf = new Provab_Pdf();
            $pdf_data['export_data']=$transfer_booking_data;
            $pdf_data['record_type']=$all;
            // debug($pdf_data['export_data']);exit;
            $mail_template =$this->template->isolated_view('report/b2b_report_transferReport_pdf',$pdf_data);
             $pdf=$this->provab_pdf->create_pdf($mail_template,'F');
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                if($all=="all")
                {
           		 	$res = $this->provab_mailer->send_mail($value, 'All_Booking_transferReport', $message,$pdf);
	           	} 
	           	else
	           	{

           			$res = $this->provab_mailer->send_mail($value, 'Confirmed_Booking_transferReport', $message,$pdf);
	           	}
            
            
            }
            // unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);


            


        } 
    }
    public function export_cancelled_booking_transfer_report_b2b($op = '') {
        $this->load->model('transferv1_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('transfers');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CANCELLED'));

        $transfer_booking_data = $this->transferv1_model->b2b_transferv1_report($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $transfer_booking_data = $this->booking_data_formatter->format_transferv1_booking_data($transfer_booking_data, $this->current_module);
        $transfer_booking_data = $transfer_booking_data['data']['booking_details'];



        $export_data = array();
        //debug($transfer_booking_data);exit;
        $i=1;
        foreach ($transfer_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
			$export_data[$k]['agency_name'] = $v['agency_name'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['product_name'] = $v['product_name'];
            $export_data[$k]['Destination'] = $v['Destination'];
            $export_data[$k]['created_datetime'] = $v['created_datetime'];
            $export_data[$k]['travel_date'] = $v['travel_date'];
           	$export_data[$k]['confirmation_reference'] = $v['confirmation_reference'];
           	$export_data[$k]['Comm_Fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['admin_commission'];
           	$export_data[$k]['admin_tds'] = $v['admin_tds'];
           	$export_data[$k]['net_fare'] = $v['net_fare'];
           	$export_data[$k]['admin_profit'] = $v['admin_commission'];
           	$export_data[$k]['admin_markup'] = $v['admin_markup'];
           	$export_data[$k]['agent_commission'] = $v['agent_commission'];
           	$export_data[$k]['agent_tds'] = $v['agent_tds'];
           	$export_data[$k]['agent_netfare'] = $v['agent_buying_price'];
           	$export_data[$k]['agent_markup'] = $v['agent_markup'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	        		
           	
        }
		//debug($export_data[$k]['booked_date']);exit;
        if ($op == 'excel') { // excel export
        	//error_reporting(E_ALL);
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'APP reference',
                    'c1' => 'Agency name',
                    'd1' => 'Lead Pax Name',
                    'e1' => 'Lead Pax Email',
                    'f1' => 'Lead Pax Phone Number',
                    'g1' => 'Activity Name',
                    'h1' => 'Acitvity Location',
                    'i1' => 'Booked On',
                    'j1' => 'Journey Date',
                    'k1' => 'Confirmation Reference',
                    'l1' => 'Commission Fare',
                    'm1' => 'Commission',
					'n1' => 'TDS',
                    'o1' => 'Admin NetFare',
                    'p1' => 'Admin Profit',
                    'q1' => 'Admin Markup',
                    'r1' => 'Agent Commission',
                    's1' => 'Agent TDS',
                    't1' => 'Agent Net Fare',
                    'u1' => 'Agent Markup',
                    'v1' => 'GST',
                    'z1' => 'TotalFare',
                   
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'agency_name',
                    'd' => 'lead_pax_name',
                    'e' => 'lead_pax_email',
                    'f' => 'lead_pax_phone_number',
                    'g' => 'product_name',
                    'h' => 'Destination',
                    'i' => 'created_datetime',
                    'j' => 'travel_date',
                    'k' => 'confirmation_reference',
                    'l' => 'Comm_Fare',
                    'm' => 'commission',
                    'n' => 'admin_tds',
                    'o' => 'net_fare',
                    'p' => 'admin_profit',
                    'q' => 'admin_markup',
                    'r' => 'agent_commission',
                    's' => 'agent_tds',
                  	't' => 'agent_netfare',
                    'u' => 'agent_markup',
                    'v' => 'gst',
                    'z' => 'grand_total',
                                        
                );
           
            $excel_sheet_properties = array(
                'title' => 'Cancelled_Booking_transferReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Cancelled_Booking_transferReport',
                'sheet_title' => 'Cancelled_Booking_transferReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
        else if ($op == 'csv') { // excel export
            // echo "dd";exit;
            
               $headings = array('Sl. No.','APP reference','Agency name','Lead Pax Name','Lead Pax Email','Lead Pax Phone','Activity Name','Acitvity Location','Booked On','Journey Date','Confirmation Reference','Commission Fare','Commission','TDS','Admin NetFare','Admin Profit','Admin Markup','Agent Commission','Agent TDS','Agent Net Fare','Agent Markup','GST','TotalFare'); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
           $this->provab_csv->csv_export($headings,'Cancelled_Booking_transferReport', $export_data);
           
           
            
            
        }
        else if($op == 'pdf')
        {
            $this->load->library ( 'provab_pdf' );
            
            $create_pdf = new Provab_Pdf();
            $pdf_data['export_data']=$transfer_booking_data;
            // debug($pdf_data['export_data']);exit;
            $mail_template =$this->template->isolated_view('report/b2b_report_transferReport_pdf',$pdf_data);
            $this->provab_pdf->create_pdf($mail_template);
            


        } 
    }
    public function export_cancelled_booking_transfer_report_b2b_email($op = '') {
        $this->load->model('transferv1_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('transfers');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CANCELLED'));

        $transfer_booking_data = $this->transferv1_model->b2b_transferv1_report($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $transfer_booking_data = $this->booking_data_formatter->format_transferv1_booking_data($transfer_booking_data, $this->current_module);
        $transfer_booking_data = $transfer_booking_data['data']['booking_details'];



        $export_data = array();
        //debug($transfer_booking_data);exit;
        $i=1;
        foreach ($transfer_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
			$export_data[$k]['agency_name'] = $v['agency_name'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['product_name'] = $v['product_name'];
            $export_data[$k]['Destination'] = $v['Destination'];
            $export_data[$k]['created_datetime'] = $v['created_datetime'];
            $export_data[$k]['travel_date'] = $v['travel_date'];
           	$export_data[$k]['confirmation_reference'] = $v['confirmation_reference'];
           	$export_data[$k]['Comm_Fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['admin_commission'];
           	$export_data[$k]['admin_tds'] = $v['admin_tds'];
           	$export_data[$k]['net_fare'] = $v['net_fare'];
           	$export_data[$k]['admin_profit'] = $v['admin_commission'];
           	$export_data[$k]['admin_markup'] = $v['admin_markup'];
           	$export_data[$k]['agent_commission'] = $v['agent_commission'];
           	$export_data[$k]['agent_tds'] = $v['agent_tds'];
           	$export_data[$k]['agent_netfare'] = $v['agent_buying_price'];
           	$export_data[$k]['agent_markup'] = $v['agent_markup'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	        		
           	
        }
		//debug($export_data[$k]['booked_date']);exit;
        if ($op == 'excel') { // excel export
        	//error_reporting(E_ALL);
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'APP reference',
                    'c1' => 'Agency name',
                    'd1' => 'Lead Pax Name',
                    'e1' => 'Lead Pax Email',
                    'f1' => 'Lead Pax Phone Number',
                    'g1' => 'Activity Name',
                    'h1' => 'Acitvity Location',
                    'i1' => 'Booked On',
                    'j1' => 'Journey Date',
                    'k1' => 'Confirmation Reference',
                    'l1' => 'Commission Fare',
                    'm1' => 'Commission',
					'n1' => 'TDS',
                    'o1' => 'Admin NetFare',
                    'p1' => 'Admin Profit',
                    'q1' => 'Admin Markup',
                    'r1' => 'Agent Commission',
                    's1' => 'Agent TDS',
                    't1' => 'Agent Net Fare',
                    'u1' => 'Agent Markup',
                    'v1' => 'GST',
                    'z1' => 'TotalFare',
                   
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'agency_name',
                    'd' => 'lead_pax_name',
                    'e' => 'lead_pax_email',
                    'f' => 'lead_pax_phone_number',
                    'g' => 'product_name',
                    'h' => 'Destination',
                    'i' => 'created_datetime',
                    'j' => 'travel_date',
                    'k' => 'confirmation_reference',
                    'l' => 'Comm_Fare',
                    'm' => 'commission',
                    'n' => 'admin_tds',
                    'o' => 'net_fare',
                    'p' => 'admin_profit',
                    'q' => 'admin_markup',
                    'r' => 'agent_commission',
                    's' => 'agent_tds',
                  	't' => 'agent_netfare',
                    'u' => 'agent_markup',
                    'v' => 'gst',
                    'z' => 'grand_total',
                                        
                );
           
            $excel_sheet_properties = array(
                'title' => 'Cancelled_Booking_transferReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Cancelled_Booking_transferReport',
                'sheet_title' => 'Cancelled_Booking_transferReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $excel=$this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties,'F');
            // echo BASEPATH;exit;
            $file_path=BASEPATH.'reportexcel/'.$excel;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                
            $res = $this->provab_mailer->send_mail($value, 'Transfer Cancelled Booking Report', $message,$file_path);
            
            }
            unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);
        }
        else if ($op == 'csv') { // excel export
            // echo "dd";exit;
            
                $headings = array('Sl. No.','APP reference','Agency name','Lead Pax Name','Lead Pax Email','Lead Pax Phone','Activity Name','Acitvity Location','Booked On','Journey Date','Confirmation Reference','Commission Fare','Commission','TDS','Admin NetFare','Admin Profit','Admin Markup','Agent Commission','Agent TDS','Agent Net Fare','Agent Markup','GST','TotalFare');  
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
            $csv= $this->provab_csv->csv_export($headings,'Cancelled_Booking_transferReport', $export_data,'F');
           $file_path=BASEPATH.'reportcsv/'.$csv;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                
            $res = $this->provab_mailer->send_mail($value, 'Cancelled_Booking_transferReport', $message,$file_path);
            
            }
            unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);
           
           
            
            
        }
        else if($op == 'pdf')
        {
            $this->load->library ( 'provab_pdf' );
            
            $create_pdf = new Provab_Pdf();
            $pdf_data['export_data']=$transfer_booking_data;
            // debug($pdf_data['export_data']);exit;
            $mail_template =$this->template->isolated_view('report/b2b_report_transferReport_pdf',$pdf_data);
           $pdf=$this->provab_pdf->create_pdf($mail_template,'F');
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                
            $res = $this->provab_mailer->send_mail($value, 'Cancelled_Booking_transferReport', $message,$pdf);
            
            }
            // unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);
            


        } 
    }


    public function export_confirmed_booking_activities_report($op = '') {
        $this->load->model('sightseeing_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('activities');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CONFIRMED'));

        $activites_booking_data = $this->sightseeing_model->b2c_sightseeing_report($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $activites_booking_data = $this->booking_data_formatter->format_sightseeing_booking_data($activites_booking_data, $this->current_module);
        $activites_booking_data = $activites_booking_data['data']['booking_details'];



        $export_data = array();
        //debug($activites_booking_data);exit;
        $i=1;
        foreach ($activites_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
			$export_data[$k]['confirmation_reference'] = $v['confirmation_reference'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['product_name'] = $v['product_name'];
            $export_data[$k]['No of Adults'] = $v['adult_count'];
            $export_data[$k]['No of Child'] = $v['child_count'];
            $export_data[$k]['No of youth'] = $v['youth_count'];
            $export_data[$k]['No of Senior'] = $v['senior_count'];
            $export_data[$k]['No of infant'] = $v['infant_count'];
            $export_data[$k]['location'] = $v['cutomer_city'];
            //$export_data[$k]['created_datetime'] = $v['created_datetime'];
            $export_data[$k]['travel_date'] = $v['travel_date'];
           	//$export_data[$k]['currency'] = $v['currency'];
           	$export_data[$k]['Comm_Fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['admin_commission'];
           	$export_data[$k]['admin_tds'] = $v['admin_tds'];
           	$export_data[$k]['net_fare'] = $v['admin_net_fare'];
           //	$export_data[$k]['admin_profit'] = $v['admin_commission'];
           	$export_data[$k]['admin_markup'] = $v['admin_markup'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['Discount'] = $v['discount'];
           	$export_data[$k]['amount'] = $v['grand_total'];
           	$export_data[$k]['Booked_on'] = $v['voucher_date'];
           //	$export_data[$k]['grand_total'] = $v['grand_total'];
           	        		
           	
        }
		//debug($export_data[$k]['booked_date']);exit;
        if ($op == 'excel') { // excel export
        	//error_reporting(E_ALL);
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'APP reference',
                    'c1' => 'Confirmation_Reference',
                    'd1' => 'Lead Pax Name',
                    'e1' => 'Lead Pax Email',
                    'f1' => 'Lead Pax Phone Number',
                    'g1' => 'Product Name',
                    'h1' => 'No of Adults',
                    'i1' => 'No of Child',
                    'j1' => 'No of youth',
                    'k1' => 'No of Senior',
                    'l1' => 'No of infant',
                    'm1' => 'City',
					'n1' => 'Travel Date',
                   //'o1' => 'Currency',
                    'p1' => 'Commission Fare',
                    'q1' => 'Commission',
                    'r1' => 'Tds',
                    's1' => 'Admin NetFare',
                    't1' => 'Admin Markup',
                    'u1' => 'Convinence amount',
                    'v1' => 'GST',
                    'w1' => 'Discount',
                   'x1' => 'Customer Paid amount',
                    'y1' => 'Booked On',
                   
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'confirmation_reference',
                    'd' => 'lead_pax_name',
                    'e' => 'lead_pax_email',
                    'f' => 'lead_pax_phone_number',
                    'g' => 'product_name',
                    'h' => 'No of Adults',
                    'i' => 'No of Child',
                    'j' => 'No of youth',
                    'k' => 'No of Senior',
                    'l' => 'No of infant',
                    'm' => 'location',
                    'n' => 'travel_date',
                   // 'o' => 'currency',
                    'p' => 'Comm_Fare',
                    'q' => 'commission',
                    'r' => 'admin_tds',
                    's' => 'net_fare',
                  	't' => 'admin_markup',
                    'u' => 'convinence_amount',
                    'v' => 'gst',
                    'w' => 'Discount',
                   'x' => 'amount',
                   'y' => 'Booked_on',
                                        
                );
           
            $excel_sheet_properties = array(
                'title' => 'Confirmed_Booking_activitesReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Confirmed_Booking_activitesReport',
                'sheet_title' => 'Confirmed_Booking_activitesReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
        else if ($op == 'csv') { // excel export
            // echo "dd";exit;
            
               $headings = array("Sl. No.","APP reference","Confirmation_Reference","Lead Pax Name","Lead Pax Email","Lead Pax Phone","Product Name","No of Adults","No of Child","No of youth","No of Senior","No of infant","City","Travel Date","Commission Fare","Commission",'Tds','Admin NetFare','Admin Markup','Convinence amount','GST','Discount','Customer Paid amount','Booked On'); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
           $this->provab_csv->csv_export($headings,'Confirmed_Booking_activitesReport', $export_data);
           
           
            
            
        }
        else if($op == 'pdf')
        {
            $this->load->library ( 'provab_pdf' );
            
            $create_pdf = new Provab_Pdf();
            $pdf_data['export_data']=$activites_booking_data;
            // debug($pdf_data['export_data']);exit;
            $mail_template =$this->template->isolated_view('report/b2c_report_activities_pdf',$pdf_data);
            $this->provab_pdf->create_pdf($mail_template);
            


        } 
    }
    public function export_all_booking_activities_report($op = '') {
        $this->load->model('sightseeing_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('activities');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array();

        $activites_booking_data = $this->sightseeing_model->b2c_sightseeing_report($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $activites_booking_data = $this->booking_data_formatter->format_sightseeing_booking_data($activites_booking_data, $this->current_module);
        $activites_booking_data = $activites_booking_data['data']['booking_details'];



        $export_data = array();
        //debug($activites_booking_data);exit;
        $i=1;
        foreach ($activites_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
			$export_data[$k]['confirmation_reference'] = $v['confirmation_reference'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['product_name'] = $v['product_name'];
            $export_data[$k]['No of Adults'] = $v['adult_count'];
            $export_data[$k]['No of Child'] = $v['child_count'];
            $export_data[$k]['No of youth'] = $v['youth_count'];
            $export_data[$k]['No of Senior'] = $v['senior_count'];
            $export_data[$k]['No of infant'] = $v['infant_count'];
            $export_data[$k]['location'] = $v['cutomer_city'];
            //$export_data[$k]['created_datetime'] = $v['created_datetime'];
            $export_data[$k]['travel_date'] = $v['travel_date'];
           	//$export_data[$k]['currency'] = $v['currency'];
           	$export_data[$k]['Comm_Fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['admin_commission'];
           	$export_data[$k]['admin_tds'] = $v['admin_tds'];
           	$export_data[$k]['net_fare'] = $v['admin_net_fare'];
           //	$export_data[$k]['admin_profit'] = $v['admin_commission'];
           	$export_data[$k]['admin_markup'] = $v['admin_markup'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['Discount'] = $v['discount'];
           	$export_data[$k]['amount'] = $v['grand_total'];
           	$export_data[$k]['status'] = $v['status'];
           	$export_data[$k]['Booked_on'] = $v['voucher_date'];
           //	$export_data[$k]['grand_total'] = $v['grand_total'];
           	        		
           	
        }
		//debug($export_data[$k]['booked_date']);exit;
        if ($op == 'excel') { // excel export
        	//error_reporting(E_ALL);
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'APP reference',
                    'c1' => 'Confirmation_Reference',
                    'd1' => 'Lead Pax Name',
                    'e1' => 'Lead Pax Email',
                    'f1' => 'Lead Pax Phone Number',
                    'g1' => 'Product Name',
                    'h1' => 'No of Adults',
                    'i1' => 'No of Child',
                    'j1' => 'No of youth',
                    'k1' => 'No of Senior',
                    'l1' => 'No of infant',
                    'm1' => 'City',
					'n1' => 'Travel Date',
                   //'o1' => 'Currency',
                    'p1' => 'Commission Fare',
                    'q1' => 'Commission',
                    'r1' => 'Tds',
                    's1' => 'Admin NetFare',
                    't1' => 'Admin Markup',
                    'u1' => 'Convinence amount',
                    'v1' => 'GST',
                    'w1' => 'Discount',
                   'x1' => 'Customer Paid amount',
                    'y1' => 'Status',
                    'z1' => 'Booked On',
                   
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'confirmation_reference',
                    'd' => 'lead_pax_name',
                    'e' => 'lead_pax_email',
                    'f' => 'lead_pax_phone_number',
                    'g' => 'product_name',
                    'h' => 'No of Adults',
                    'i' => 'No of Child',
                    'j' => 'No of youth',
                    'k' => 'No of Senior',
                    'l' => 'No of infant',
                    'm' => 'location',
                    'n' => 'travel_date',
                   // 'o' => 'currency',
                    'p' => 'Comm_Fare',
                    'q' => 'commission',
                    'r' => 'admin_tds',
                    's' => 'net_fare',
                  	't' => 'admin_markup',
                    'u' => 'convinence_amount',
                    'v' => 'gst',
                    'w' => 'Discount',
                   'x' => 'amount',
                   'y' => 'status',
                   'z' => 'Booked_on',
                                        
                );
           
            $excel_sheet_properties = array(
                'title' => 'All_Booking_activitesReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'All_Booking_activitesReport',
                'sheet_title' => 'All_Booking_activitesReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
        else if ($op == 'csv') { // excel export
            // echo "dd";exit;
            
               $headings = array("Sl. No.","APP reference","Confirmation_Reference","Lead Pax Name","Lead Pax Email","Lead Pax Phone","Product Name","No of Adults","No of Child","No of youth","No of Senior","No of infant","City","Travel Date","Commission Fare","Commission",'Tds','Admin NetFare','Admin Markup','Convinence amount','GST','Discount','Customer Paid amount','Status','Booked On'); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
           $this->provab_csv->csv_export($headings,'All_Booking_activitesReport', $export_data);
           
           
            
            
        }
        else if($op == 'pdf')
        {
            $this->load->library ( 'provab_pdf' );
            
            $create_pdf = new Provab_Pdf();
            $pdf_data['export_data']=$activites_booking_data;
            // debug($pdf_data['export_data']);exit;
            $mail_template =$this->template->isolated_view('report/b2c_report_activities_all_pdf',$pdf_data);
            $this->provab_pdf->create_pdf($mail_template);
            


        } 
    }
    public function export_confirmed_booking_activities_report_email($op = '') {
        $this->load->model('sightseeing_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('activities');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CONFIRMED'));

        $activites_booking_data = $this->sightseeing_model->b2c_sightseeing_report($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $activites_booking_data = $this->booking_data_formatter->format_sightseeing_booking_data($activites_booking_data, $this->current_module);
        $activites_booking_data = $activites_booking_data['data']['booking_details'];



        $export_data = array();
        //debug($activites_booking_data);exit;
        $i=1;
        foreach ($activites_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
			$export_data[$k]['confirmation_reference'] = $v['confirmation_reference'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['product_name'] = $v['product_name'];
            $export_data[$k]['No of Adults'] = $v['adult_count'];
            $export_data[$k]['No of Child'] = $v['child_count'];
            $export_data[$k]['No of youth'] = $v['youth_count'];
            $export_data[$k]['No of Senior'] = $v['senior_count'];
            $export_data[$k]['No of infant'] = $v['infant_count'];
            $export_data[$k]['location'] = $v['cutomer_city'];
            //$export_data[$k]['created_datetime'] = $v['created_datetime'];
            $export_data[$k]['travel_date'] = $v['travel_date'];
           	//$export_data[$k]['currency'] = $v['currency'];
           	$export_data[$k]['Comm_Fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['admin_commission'];
           	$export_data[$k]['admin_tds'] = $v['admin_tds'];
           	$export_data[$k]['net_fare'] = $v['admin_net_fare'];
           //	$export_data[$k]['admin_profit'] = $v['admin_commission'];
           	$export_data[$k]['admin_markup'] = $v['admin_markup'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['Discount'] = $v['discount'];
           	$export_data[$k]['amount'] = $v['grand_total'];
           	$export_data[$k]['Booked_on'] = $v['voucher_date'];
           //	$export_data[$k]['grand_total'] = $v['grand_total'];
           	        		
           	
        }
		//debug($export_data[$k]['booked_date']);exit;
        if ($op == 'excel') { // excel export
        	//error_reporting(E_ALL);
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'APP reference',
                    'c1' => 'Confirmation_Reference',
                    'd1' => 'Lead Pax Name',
                    'e1' => 'Lead Pax Email',
                    'f1' => 'Lead Pax Phone Number',
                    'g1' => 'Product Name',
                    'h1' => 'No of Adults',
                    'i1' => 'No of Child',
                    'j1' => 'No of youth',
                    'k1' => 'No of Senior',
                    'l1' => 'No of infant',
                    'm1' => 'City',
					'n1' => 'Travel Date',
                   //'o1' => 'Currency',
                    'p1' => 'Commission Fare',
                    'q1' => 'Commission',
                    'r1' => 'Tds',
                    's1' => 'Admin NetFare',
                    't1' => 'Admin Markup',
                    'u1' => 'Convinence amount',
                    'v1' => 'GST',
                    'w1' => 'Discount',
                   'x1' => 'Customer Paid amount',
                    'y1' => 'Booked On',
                   
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'confirmation_reference',
                    'd' => 'lead_pax_name',
                    'e' => 'lead_pax_email',
                    'f' => 'lead_pax_phone_number',
                    'g' => 'product_name',
                    'h' => 'No of Adults',
                    'i' => 'No of Child',
                    'j' => 'No of youth',
                    'k' => 'No of Senior',
                    'l' => 'No of infant',
                    'm' => 'location',
                    'n' => 'travel_date',
                   // 'o' => 'currency',
                    'p' => 'Comm_Fare',
                    'q' => 'commission',
                    'r' => 'admin_tds',
                    's' => 'net_fare',
                  	't' => 'admin_markup',
                    'u' => 'convinence_amount',
                    'v' => 'gst',
                    'w' => 'Discount',
                   'x' => 'amount',
                   'y' => 'Booked_on',
                                        
                );
           
            $excel_sheet_properties = array(
                'title' => 'Confirmed_Booking_activitesReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Confirmed_Booking_activitesReport',
                'sheet_title' => 'Confirmed_Booking_activitesReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $excel=$this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties,'F');
            // echo BASEPATH;exit;
            $file_path=BASEPATH.'reportexcel/'.$excel;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                
            $res = $this->provab_mailer->send_mail($value, 'Activity Confirmed Booking Report', $message,$file_path);
            
            }
            unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);
        }
        else if ($op == 'csv') { // excel export
            // echo "dd";exit;
            
               $headings = array("Sl. No.","APP reference","Confirmation_Reference","Lead Pax Name","Lead Pax Email","Lead Pax Phone","Product Name","No of Adults","No of Child","No of youth","No of Senior","No of infant","City","Travel Date","Commission Fare","Commission",'Tds','Admin NetFare','Admin Markup','Convinence amount','GST','Discount','Customer Paid amount','Booked On'); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
           $csv= $this->provab_csv->csv_export($headings,'Confirmed_Booking_activitesReport', $export_data,'F');
           $file_path=BASEPATH.'reportcsv/'.$csv;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                
            $res = $this->provab_mailer->send_mail($value, 'Cancelled_Booking_BusReport', $message,$file_path);
            
            }
            unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);
           
           
            
            
        }
        else if($op == 'pdf')
        {
            $this->load->library ( 'provab_pdf' );
            
            $create_pdf = new Provab_Pdf();
            $pdf_data['export_data']=$activites_booking_data;
            // debug($pdf_data['export_data']);exit;
            $mail_template =$this->template->isolated_view('report/b2c_report_activities_pdf',$pdf_data);
             $pdf=$this->provab_pdf->create_pdf($mail_template,'F');
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                
            $res = $this->provab_mailer->send_mail($value, 'Activity Confirmed Booking Report', $message,$pdf);
            
            }
            // unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);
            


        } 
    }
    public function export_all_booking_activities_report_email($op = '') {
        $this->load->model('sightseeing_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('activities');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array();

        $activites_booking_data = $this->sightseeing_model->b2c_sightseeing_report($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $activites_booking_data = $this->booking_data_formatter->format_sightseeing_booking_data($activites_booking_data, $this->current_module);
        $activites_booking_data = $activites_booking_data['data']['booking_details'];



        $export_data = array();
        //debug($activites_booking_data);exit;
        $i=1;
        foreach ($activites_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
			$export_data[$k]['confirmation_reference'] = $v['confirmation_reference'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['product_name'] = $v['product_name'];
            $export_data[$k]['No of Adults'] = $v['adult_count'];
            $export_data[$k]['No of Child'] = $v['child_count'];
            $export_data[$k]['No of youth'] = $v['youth_count'];
            $export_data[$k]['No of Senior'] = $v['senior_count'];
            $export_data[$k]['No of infant'] = $v['infant_count'];
            $export_data[$k]['location'] = $v['cutomer_city'];
            //$export_data[$k]['created_datetime'] = $v['created_datetime'];
            $export_data[$k]['travel_date'] = $v['travel_date'];
           	//$export_data[$k]['currency'] = $v['currency'];
           	$export_data[$k]['Comm_Fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['admin_commission'];
           	$export_data[$k]['admin_tds'] = $v['admin_tds'];
           	$export_data[$k]['net_fare'] = $v['admin_net_fare'];
           //	$export_data[$k]['admin_profit'] = $v['admin_commission'];
           	$export_data[$k]['admin_markup'] = $v['admin_markup'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['Discount'] = $v['discount'];
           	$export_data[$k]['amount'] = $v['grand_total'];
           	$export_data[$k]['status'] = $v['status'];
           	$export_data[$k]['Booked_on'] = $v['voucher_date'];
           //	$export_data[$k]['grand_total'] = $v['grand_total'];
           	        		
           	
        }
		//debug($export_data[$k]['booked_date']);exit;
        if ($op == 'excel') { // excel export
        	//error_reporting(E_ALL);
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'APP reference',
                    'c1' => 'Confirmation_Reference',
                    'd1' => 'Lead Pax Name',
                    'e1' => 'Lead Pax Email',
                    'f1' => 'Lead Pax Phone Number',
                    'g1' => 'Product Name',
                    'h1' => 'No of Adults',
                    'i1' => 'No of Child',
                    'j1' => 'No of youth',
                    'k1' => 'No of Senior',
                    'l1' => 'No of infant',
                    'm1' => 'City',
					'n1' => 'Travel Date',
                   //'o1' => 'Currency',
                    'p1' => 'Commission Fare',
                    'q1' => 'Commission',
                    'r1' => 'Tds',
                    's1' => 'Admin NetFare',
                    't1' => 'Admin Markup',
                    'u1' => 'Convinence amount',
                    'v1' => 'GST',
                    'w1' => 'Discount',
                   'x1' => 'Customer Paid amount',
                    'y1' => 'Status',
                    'z1' => 'Booked On',
                   
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'confirmation_reference',
                    'd' => 'lead_pax_name',
                    'e' => 'lead_pax_email',
                    'f' => 'lead_pax_phone_number',
                    'g' => 'product_name',
                    'h' => 'No of Adults',
                    'i' => 'No of Child',
                    'j' => 'No of youth',
                    'k' => 'No of Senior',
                    'l' => 'No of infant',
                    'm' => 'location',
                    'n' => 'travel_date',
                   // 'o' => 'currency',
                    'p' => 'Comm_Fare',
                    'q' => 'commission',
                    'r' => 'admin_tds',
                    's' => 'net_fare',
                  	't' => 'admin_markup',
                    'u' => 'convinence_amount',
                    'v' => 'gst',
                    'w' => 'Discount',
                   'x' => 'amount',
                   'y' => 'status',
                   'z' => 'Booked_on',
                                        
                );
           
            $excel_sheet_properties = array(
                'title' => 'All_Booking_activitesReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'All_Booking_activitesReport',
                'sheet_title' => 'All_Booking_activitesReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $excel=$this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties,'F');
            // echo BASEPATH;exit;
            $file_path=BASEPATH.'reportexcel/'.$excel;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                
            $res = $this->provab_mailer->send_mail($value, 'Activity All Booking Report', $message,$file_path);
            
            }
            unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);
        }
        else if ($op == 'csv') { // excel export
            // echo "dd";exit;
            
               $headings = array("Sl. No.","APP reference","Confirmation_Reference","Lead Pax Name","Lead Pax Email","Lead Pax Phone","Product Name","No of Adults","No of Child","No of youth","No of Senior","No of infant","City","Travel Date","Commission Fare","Commission",'Tds','Admin NetFare','Admin Markup','Convinence amount','GST','Discount','Customer Paid amount','Status','Booked On'); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
           $csv= $this->provab_csv->csv_export($headings,'All_Booking_activitesReport', $export_data,'F');
           $file_path=BASEPATH.'reportcsv/'.$csv;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                
            $res = $this->provab_mailer->send_mail($value, 'All_Booking_BusReport', $message,$file_path);
            
            }
            unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);
           
           
            
            
        }
        else if($op == 'pdf')
        {
            $this->load->library ( 'provab_pdf' );
            
            $create_pdf = new Provab_Pdf();
            $pdf_data['export_data']=$activites_booking_data;
            // debug($pdf_data['export_data']);exit;
            $mail_template =$this->template->isolated_view('report/b2c_report_activities_all_pdf',$pdf_data);
             $pdf=$this->provab_pdf->create_pdf($mail_template,'F');
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                
            $res = $this->provab_mailer->send_mail($value, 'Activity All Booking Report', $message,$pdf);
            
            }
            // unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);
            


        } 
    }
    public function export_cancelled_booking_activities_report($op = '') {
        $this->load->model('sightseeing_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('activities');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CANCELLED'));

        $activites_booking_data = $this->sightseeing_model->b2c_sightseeing_report($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $activites_booking_data = $this->booking_data_formatter->format_sightseeing_booking_data($activites_booking_data, $this->current_module);
        $activites_booking_data = $activites_booking_data['data']['booking_details'];



        $export_data = array();
        //debug($activites_booking_data);exit;
        $i=1;
        foreach ($activites_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
			$export_data[$k]['confirmation_reference'] = $v['confirmation_reference'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['product_name'] = $v['product_name'];
            $export_data[$k]['No of Adults'] = $v['adult_count'];
            $export_data[$k]['No of Child'] = $v['child_count'];
            $export_data[$k]['No of youth'] = $v['youth_count'];
            $export_data[$k]['No of Senior'] = $v['senior_count'];
            $export_data[$k]['No of infant'] = $v['infant_count'];
            $export_data[$k]['location'] = $v['cutomer_city'];
            //$export_data[$k]['created_datetime'] = $v['created_datetime'];
            $export_data[$k]['travel_date'] = $v['travel_date'];
           //	$export_data[$k]['currency'] = $v['currency'];
           	$export_data[$k]['Comm_Fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['admin_commission'];
           	$export_data[$k]['admin_tds'] = $v['admin_tds'];
           	$export_data[$k]['net_fare'] = $v['admin_net_fare'];
           //	$export_data[$k]['admin_profit'] = $v['admin_commission'];
           	$export_data[$k]['admin_markup'] = $v['admin_markup'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['Discount'] = $v['discount'];
           	$export_data[$k]['amount'] = $v['grand_total'];
           	$export_data[$k]['Booked_on'] = $v['voucher_date'];
           //	$export_data[$k]['grand_total'] = $v['grand_total'];
           	        		
           	
        }
		//debug($export_data[$k]['booked_date']);exit;
        if ($op == 'excel') { // excel export
        	//error_reporting(E_ALL);
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'APP reference',
                    'c1' => 'Confirmation_Reference',
                    'd1' => 'Lead Pax Name',
                    'e1' => 'Lead Pax Email',
                    'f1' => 'Lead Pax Phone Number',
                    'g1' => 'Product Name',
                    'h1' => 'No of Adults',
                    'i1' => 'No of Child',
                    'j1' => 'No of youth',
                    'k1' => 'No of Senior',
                    'l1' => 'No of infant',
                    'm1' => 'City',
					'n1' => 'Travel Date',
                   // 'o1' => 'Currency',
                    'p1' => 'Commission Fare',
                    'q1' => 'Commission',
                    'r1' => 'Tds',
                    's1' => 'Admin NetFare',
                    't1' => 'Admin Markup',
                    'u1' => 'Convinence amount',
                    'v1' => 'GST',
                    'w1' => 'Discount',
                   'x1' => 'Customer Paid amount',
                    'y1' => 'Booked On',
                   
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'confirmation_reference',
                    'd' => 'lead_pax_name',
                    'e' => 'lead_pax_email',
                    'f' => 'lead_pax_phone_number',
                    'g' => 'product_name',
                    'h' => 'No of Adults',
                    'i' => 'No of Child',
                    'j' => 'No of youth',
                    'k' => 'No of Senior',
                    'l' => 'No of infant',
                    'm' => 'location',
                    'n' => 'travel_date',
                    //'o' => 'currency',
                    'p' => 'Comm_Fare',
                    'q' => 'commission',
                    'r' => 'admin_tds',
                    's' => 'net_fare',
                  	't' => 'admin_markup',
                    'u' => 'convinence_amount',
                    'v' => 'gst',
                    'w' => 'Discount',
                   'x' => 'amount',
                   'y' => 'Booked_on',
                                        
                );
           
            $excel_sheet_properties = array(
                'title' => 'Cancelled_Booking_activitesReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Cancelled_Booking_activitesReport',
                'sheet_title' => 'Cancelled_Booking_activitesReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
        else if ($op == 'csv') { // excel export
            // echo "dd";exit;
            
               $headings = array("Sl. No.","APP reference","Confirmation_Reference","Lead Pax Name","Lead Pax Email","Lead Pax Phone","Product Name","No of Adults","No of Child","No of youth","No of Senior","No of infant","City","Travel Date","Commission Fare","Commission",'Tds','Admin NetFare','Admin Markup','Convinence amount','GST','Discount','Customer Paid amount','Booked On'); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
           $this->provab_csv->csv_export($headings,'Cancelled_Booking_activitesReport', $export_data);
           
           
            
            
        }
        else if($op == 'pdf')
        {
            $this->load->library ( 'provab_pdf' );
            
            $create_pdf = new Provab_Pdf();
            $pdf_data['export_data']=$activites_booking_data;
            // debug($pdf_data['export_data']);exit;
            $mail_template =$this->template->isolated_view('report/b2c_report_activities_pdf',$pdf_data);
            $this->provab_pdf->create_pdf($mail_template);
            


        } 
    }
    public function export_cancelled_booking_activities_report_email($op = '') {
        $this->load->model('sightseeing_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('activities');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CANCELLED'));

        $activites_booking_data = $this->sightseeing_model->b2c_sightseeing_report($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $activites_booking_data = $this->booking_data_formatter->format_sightseeing_booking_data($activites_booking_data, $this->current_module);
        $activites_booking_data = $activites_booking_data['data']['booking_details'];



        $export_data = array();
        //debug($activites_booking_data);exit;
        $i=1;
        foreach ($activites_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
			$export_data[$k]['confirmation_reference'] = $v['confirmation_reference'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['product_name'] = $v['product_name'];
            $export_data[$k]['No of Adults'] = $v['adult_count'];
            $export_data[$k]['No of Child'] = $v['child_count'];
            $export_data[$k]['No of youth'] = $v['youth_count'];
            $export_data[$k]['No of Senior'] = $v['senior_count'];
            $export_data[$k]['No of infant'] = $v['infant_count'];
            $export_data[$k]['location'] = $v['cutomer_city'];
            //$export_data[$k]['created_datetime'] = $v['created_datetime'];
            $export_data[$k]['travel_date'] = $v['travel_date'];
           //	$export_data[$k]['currency'] = $v['currency'];
           	$export_data[$k]['Comm_Fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['admin_commission'];
           	$export_data[$k]['admin_tds'] = $v['admin_tds'];
           	$export_data[$k]['net_fare'] = $v['admin_net_fare'];
           //	$export_data[$k]['admin_profit'] = $v['admin_commission'];
           	$export_data[$k]['admin_markup'] = $v['admin_markup'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['Discount'] = $v['discount'];
           	$export_data[$k]['amount'] = $v['grand_total'];
           	$export_data[$k]['Booked_on'] = $v['voucher_date'];
           //	$export_data[$k]['grand_total'] = $v['grand_total'];
           	        		
           	
        }
		//debug($export_data[$k]['booked_date']);exit;
        if ($op == 'excel') { // excel export
        	//error_reporting(E_ALL);
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'APP reference',
                    'c1' => 'Confirmation_Reference',
                    'd1' => 'Lead Pax Name',
                    'e1' => 'Lead Pax Email',
                    'f1' => 'Lead Pax Phone Number',
                    'g1' => 'Product Name',
                    'h1' => 'No of Adults',
                    'i1' => 'No of Child',
                    'j1' => 'No of youth',
                    'k1' => 'No of Senior',
                    'l1' => 'No of infant',
                    'm1' => 'City',
					'n1' => 'Travel Date',
                   // 'o1' => 'Currency',
                    'p1' => 'Commission Fare',
                    'q1' => 'Commission',
                    'r1' => 'Tds',
                    's1' => 'Admin NetFare',
                    't1' => 'Admin Markup',
                    'u1' => 'Convinence amount',
                    'v1' => 'GST',
                    'w1' => 'Discount',
                   'x1' => 'Customer Paid amount',
                    'y1' => 'Booked On',
                   
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'confirmation_reference',
                    'd' => 'lead_pax_name',
                    'e' => 'lead_pax_email',
                    'f' => 'lead_pax_phone_number',
                    'g' => 'product_name',
                    'h' => 'No of Adults',
                    'i' => 'No of Child',
                    'j' => 'No of youth',
                    'k' => 'No of Senior',
                    'l' => 'No of infant',
                    'm' => 'location',
                    'n' => 'travel_date',
                    //'o' => 'currency',
                    'p' => 'Comm_Fare',
                    'q' => 'commission',
                    'r' => 'admin_tds',
                    's' => 'net_fare',
                  	't' => 'admin_markup',
                    'u' => 'convinence_amount',
                    'v' => 'gst',
                    'w' => 'Discount',
                   'x' => 'amount',
                   'y' => 'Booked_on',
                                        
                );
           
            $excel_sheet_properties = array(
                'title' => 'Cancelled_Booking_activitesReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Cancelled_Booking_activitesReport',
                'sheet_title' => 'Cancelled_Booking_activitesReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $excel=$this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties,'F');
            // echo BASEPATH;exit;
            $file_path=BASEPATH.'reportexcel/'.$excel;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                
            $res = $this->provab_mailer->send_mail($value, 'Activity Cancelled Booking Report', $message,$file_path);
            
            }
            unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);
        }
        else if ($op == 'csv') { // excel export
            // echo "dd";exit;
            
               $headings = array("Sl. No.","APP reference","Confirmation_Reference","Lead Pax Name","Lead Pax Email","Lead Pax Phone","Product Name","No of Adults","No of Child","No of youth","No of Senior","No of infant","City","Travel Date","Commission Fare","Commission",'Tds','Admin NetFare','Admin Markup','Convinence amount','GST','Discount','Customer Paid amount','Booked On'); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
           $csv= $this->provab_csv->csv_export($headings,'Cancelled_Booking_activitesReport', $export_data,'F');
           $file_path=BASEPATH.'reportcsv/'.$csv;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                
            $res = $this->provab_mailer->send_mail($value, 'Cancelled_Booking_ActivityReport', $message,$file_path);
            
            }
            unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);
           
           
            
            
        }
        else if($op == 'pdf')
        {
            $this->load->library ( 'provab_pdf' );
            
            $create_pdf = new Provab_Pdf();
            $pdf_data['export_data']=$activites_booking_data;
            // debug($pdf_data['export_data']);exit;
            $mail_template =$this->template->isolated_view('report/b2c_report_activities_pdf',$pdf_data);
            $pdf=$this->provab_pdf->create_pdf($mail_template,'F');
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                
            $res = $this->provab_mailer->send_mail($value, 'Activity Cancelled Booking Report', $message,$pdf);
            
            }
            // unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);
            


        } 
    }
    public function export_confirmed_booking_activities_report_b2b($op = '',$all='') {
        $this->load->model('sightseeing_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('activities');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        if($all=="all")
        {

        $condition[] = array();
        }
        else
        {

        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CONFIRMED'));
        }

        $activites_booking_data = $this->sightseeing_model->b2b_sightseeing_report($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $activites_booking_data = $this->booking_data_formatter->format_sightseeing_booking_data($activites_booking_data, $this->current_module);
        $activites_booking_data = $activites_booking_data['data']['booking_details'];



        $export_data = array();
       // debug($activites_booking_data);exit;
        $i=1;
        foreach ($activites_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
			$export_data[$k]['agency_name'] = $v['agency_name'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['product_name'] = $v['product_name'];
            $export_data[$k]['location'] = $v['destination_name'];
           	$export_data[$k]['Booked_on'] = $v['voucher_date'];
            $export_data[$k]['travel_date'] = $v['travel_date'];
           	$export_data[$k]['confirmation_reference'] = $v['confirmation_reference'];
           	$export_data[$k]['Comm_Fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['net_commission'];
           	$export_data[$k]['tds'] = $v['net_commission_tds'];
           	$export_data[$k]['net_fare'] = $v['net_fare'];
           	$export_data[$k]['admin_commission'] = $v['admin_commission'];
           	$export_data[$k]['admin_markup'] = $v['admin_markup'];
           	$export_data[$k]['agent_commission'] = $v['agent_commission'];
           	$export_data[$k]['agent_tds'] = $v['agent_tds'];
           	$export_data[$k]['agent_buying_price'] = $v['agent_buying_price'];
           	$export_data[$k]['agent_markup'] = $v['agent_markup'];
           	$export_data[$k]['gst'] = $v['gst'];
           	if($all=="all")
           	{
           		$export_data[$k]['status'] = $v['status'];
           	}
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	        		
           	
        }
        //debug($export_data[$k]['booked_date']);exit;
        if ($op == 'excel') { // excel export
        	//error_reporting(E_ALL);
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'APP reference',
                    'c1' => 'Agency Name',
                    'd1' => 'Lead Pax Name',
                    'e1' => 'Lead Pax Email',
                    'f1' => 'Lead Pax Phone Number',
                    'g1' => 'Activity Name',
                    'h1' => 'Acitvity Location',
                    'i1' => 'BookedOn',
                    'j1' => 'JourneyDate',
                    'k1' => 'Confirmation Reference',
                    'l1' => 'Commission Fare',
                    'm1' => 'Commission',
					'n1' => 'TDS',
                    'o1' => 'Admin NetFare',
                    'p1' => 'Admin Profit',
                    'q1' => 'Admin Markup',
                    'r1' => 'Agent Commission',
                    's1' => 'Agent TDS',
                    't1' => 'Agent Net Fare',
                    'u1' => 'Agent Markup',
                    'v1' => 'GST',
                    
                   
                   
                );

           if($all=="all")
           	{
           		$headings['w1'] = 'Status';
           		$headings['x1'] = 'TotalFare';
           	}
           	else
           	{
           		$headings['w1'] = 'TotalFare';
           	}
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'agency_name',
                    'd' => 'lead_pax_name',
                    'e' => 'lead_pax_email',
                    'f' => 'lead_pax_phone_number',
                    'g' => 'product_name',
                    'h' => 'location',
                    'i' => 'Booked_on',
                    'j' => 'travel_date',
                    'k' => 'confirmation_reference',
                    'l' => 'Comm_Fare',
                    'm' => 'commission',
                    'n' => 'tds',
                    'o' => 'net_fare',
                    'p' => 'admin_commission',
                    'q' => 'admin_markup',
                    'r' => 'agent_commission',
                    's' => 'agent_tds',
                  	't' => 'agent_buying_price',
                    'u' => 'agent_markup',
                    'v' => 'gst',
                   
                   
                                        
                );
                if($all=="all")
	           	{
	           		$fields['w'] = 'status';
	           		$fields['x'] = 'grand_total';
	           		 $excel_sheet_properties = array(
                'title' => 'All_Booking_activitesReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'All_Booking_activitesReport',
                'sheet_title' => 'All_Booking_activitesReport'
            );
	           	}
	           	else
	           	{
	           		$fields['w'] = 'grand_total';
	           		 $excel_sheet_properties = array(
                'title' => 'Confirmed_Booking_activitesReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Confirmed_Booking_activitesReport',
                'sheet_title' => 'Confirmed_Booking_activitesReport'
            );
	           	}
           
           

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
        else if ($op == 'csv') { // excel export
            // echo "dd";exit;
            
               if($all=="all")
	           	{
	           		$headings = array("Sl. No.","APP reference","Agency Name","Lead Pax Name","Lead Pax Email","Lead Pax Phone Number","Activity Name","Acitvity Location","BookedOn","JourneyDate","Confirmation Reference","No of infant","City","Travel Date","Commission Fare",'Commission','TDS','Admin NetFare','Admin Profit','Admin Markup','Agent Commission','Agent TDS','Agent Net Fare','Agent Markup','GST','Status','TotalFare'); 
	           	}
	           	else
	           	{
	           		$headings = array("Sl. No.","APP reference","Agency Name","Lead Pax Name","Lead Pax Email","Lead Pax Phone Number","Activity Name","Acitvity Location","BookedOn","JourneyDate","Confirmation Reference","No of infant","City","Travel Date","Commission Fare",'Commission','TDS','Admin NetFare','Admin Profit','Admin Markup','Agent Commission','Agent TDS','Agent Net Fare','Agent Markup','GST','TotalFare'); 
	           	}
            
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
            if($all=="all")
	           	{
	           		 $this->provab_csv->csv_export($headings,'All_Booking_activitesReport', $export_data);
	           	}
	           	else
	           	{
	           		 $this->provab_csv->csv_export($headings,'Confirmed_Booking_activitesReport', $export_data);
	           	}
          
           
           
            
            
        }
        else if($op == 'pdf')
        {
            $this->load->library ( 'provab_pdf' );
            
            $create_pdf = new Provab_Pdf();
            $pdf_data['export_data']=$activites_booking_data;
            $pdf_data['record_type']=$all;
            // debug($pdf_data['export_data']);exit;
            $mail_template =$this->template->isolated_view('report/b2b_report_activities_pdf',$pdf_data);

            $this->provab_pdf->create_pdf($mail_template);
            


        } 
    }
    public function export_confirmed_booking_activities_report_b2b_email($op = '',$all='') {
        $this->load->model('sightseeing_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('activities');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
       if($all=="all")
        {

        $condition[] = array();
        }
        else
        {

        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CONFIRMED'));
        }

        $activites_booking_data = $this->sightseeing_model->b2b_sightseeing_report($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $activites_booking_data = $this->booking_data_formatter->format_sightseeing_booking_data($activites_booking_data, $this->current_module);
        $activites_booking_data = $activites_booking_data['data']['booking_details'];



        $export_data = array();
       // debug($activites_booking_data);exit;
        $i=1;
        foreach ($activites_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
			$export_data[$k]['agency_name'] = $v['agency_name'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['product_name'] = $v['product_name'];
            $export_data[$k]['location'] = $v['destination_name'];
           	$export_data[$k]['Booked_on'] = $v['voucher_date'];
            $export_data[$k]['travel_date'] = $v['travel_date'];
           	$export_data[$k]['confirmation_reference'] = $v['confirmation_reference'];
           	$export_data[$k]['Comm_Fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['net_commission'];
           	$export_data[$k]['tds'] = $v['net_commission_tds'];
           	$export_data[$k]['net_fare'] = $v['net_fare'];
           	$export_data[$k]['admin_commission'] = $v['admin_commission'];
           	$export_data[$k]['admin_markup'] = $v['admin_markup'];
           	$export_data[$k]['agent_commission'] = $v['agent_commission'];
           	$export_data[$k]['agent_tds'] = $v['agent_tds'];
           	$export_data[$k]['agent_buying_price'] = $v['agent_buying_price'];
           	$export_data[$k]['agent_markup'] = $v['agent_markup'];
           	$export_data[$k]['gst'] = $v['gst'];
           	 if($all=="all")
           	{
           		$export_data[$k]['status'] = $v['status'];
           	}
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	        		
           	
        }
        //debug($export_data[$k]['booked_date']);exit;
        if ($op == 'excel') { // excel export
        	//error_reporting(E_ALL);
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'APP reference',
                    'c1' => 'Agency Name',
                    'd1' => 'Lead Pax Name',
                    'e1' => 'Lead Pax Email',
                    'f1' => 'Lead Pax Phone Number',
                    'g1' => 'Activity Name',
                    'h1' => 'Acitvity Location',
                    'i1' => 'BookedOn',
                    'j1' => 'JourneyDate',
                    'k1' => 'Confirmation Reference',
                    'l1' => 'Commission Fare	',
                    'm1' => 'Commission',
					'n1' => 'TDS',
                    'o1' => 'Admin NetFare',
                    'p1' => 'Admin Profit',
                    'q1' => 'Admin Markup',
                    'r1' => 'Agent Commission',
                    's1' => 'Agent TDS',
                    't1' => 'Agent Net Fare',
                    'u1' => 'Agent Markup',
                    'v1' => 'GST',
                    
                   
                   
                );
           if($all=="all")
           	{
           		$headings['w1'] = 'Status';
           		$headings['x1'] = 'TotalFare';
           	}
           	else
           	{
           		$headings['w1'] = 'TotalFare';
           	}
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'agency_name',
                    'd' => 'lead_pax_name',
                    'e' => 'lead_pax_email',
                    'f' => 'lead_pax_phone_number',
                    'g' => 'product_name',
                    'h' => 'location',
                    'i' => 'Booked_on',
                    'j' => 'travel_date',
                    'k' => 'confirmation_reference',
                    'l' => 'Comm_Fare',
                    'm' => 'commission',
                    'n' => 'tds',
                    'o' => 'net_fare',
                    'p' => 'admin_commission',
                    'q' => 'admin_markup',
                    'r' => 'agent_commission',
                    's' => 'agent_tds',
                  	't' => 'agent_buying_price',
                    'u' => 'agent_markup',
                    'v' => 'gst',
                    
                   
                                        
                );
           if($all=="all")
	           	{
	           		$fields['w'] = 'status';
	           		$fields['x'] = 'grand_total';
	           		 $excel_sheet_properties = array(
                'title' => 'All_Booking_activitesReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'All_Booking_activitesReport',
                'sheet_title' => 'All_Booking_activitesReport'
            );
	           	}
	           	else
	           	{
	           		$fields['w'] = 'grand_total';
	           		 $excel_sheet_properties = array(
                'title' => 'Confirmed_Booking_activitesReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Confirmed_Booking_activitesReport',
                'sheet_title' => 'Confirmed_Booking_activitesReport'
            );
	           	}

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $excel=$this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties,'F');
            // echo BASEPATH;exit;
            $file_path=BASEPATH.'reportexcel/'.$excel;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                if($all=="all")
	           	{
	           		$res = $this->provab_mailer->send_mail($value, 'Activity All Booking Report', $message,$file_path); 
	           	}
	           	else
	           	{
	           		$res = $this->provab_mailer->send_mail($value, 'Activity Confirmed Booking Report', $message,$file_path);
	           	}
            
            
            }
            unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);
        }
        else if ($op == 'csv') { // excel export
            // echo "dd";exit;
            
               
           if($all=="all")
	           	{
	           		$headings = array("Sl. No.","APP reference","Agency Name","Lead Pax Name","Lead Pax Email","Lead Pax Phone Number","Activity Name","Acitvity Location","BookedOn","JourneyDate","Confirmation Reference","No of infant","City","Travel Date","Commission Fare",'Commission','TDS','Admin NetFare','Admin Profit','Admin Markup','Agent Commission','Agent TDS','Agent Net Fare','Agent Markup','GST','Status','TotalFare'); 
	           	}
	           	else
	           	{
	           		$headings = array("Sl. No.","APP reference","Agency Name","Lead Pax Name","Lead Pax Email","Lead Pax Phone Number","Activity Name","Acitvity Location","BookedOn","JourneyDate","Confirmation Reference","No of infant","City","Travel Date","Commission Fare",'Commission','TDS','Admin NetFare','Admin Profit','Admin Markup','Agent Commission','Agent TDS','Agent Net Fare','Agent Markup','GST','TotalFare'); 
	           	}
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
            if($all=="all")
	           	{
	           		$csv= $this->provab_csv->csv_export($headings,'All_Booking_activitesReport', $export_data,'F');
	           	}
	           	else
	           	{
	           		$csv= $this->provab_csv->csv_export($headings,'Confirmed_Booking_activitesReport', $export_data,'F');
	           	}
           
           $file_path=BASEPATH.'reportcsv/'.$csv;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                 if($all=="all")
	           	{
	           		$res = $this->provab_mailer->send_mail($value, 'All_Booking_BusReport', $message,$file_path);
	           	}
	           	else
	           	{
	           		$res = $this->provab_mailer->send_mail($value, 'Confirm_Booking_BusReport', $message,$file_path);
	           	}
            
            
            }
            unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);
           
           
            
            
        }
        else if($op == 'pdf')
        {
            $this->load->library ( 'provab_pdf' );
            
            $create_pdf = new Provab_Pdf();
            $pdf_data['export_data']=$activites_booking_data;
            $pdf_data['record_type']=$all;
            // debug($pdf_data['export_data']);exit;
            $mail_template =$this->template->isolated_view('report/b2b_report_activities_pdf',$pdf_data);
             $pdf=$this->provab_pdf->create_pdf($mail_template,'F');
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                 if($all=="all")
	           	{
	           		$res = $this->provab_mailer->send_mail($value, 'Activity All Booking Report', $message,$pdf);
	           	}
	           	else
	           	{
	           		$res = $this->provab_mailer->send_mail($value, 'Activity Confirmed Booking Report', $message,$pdf);
	           	}
            
            
            }
            // unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);
            


        } 
    }

    public function export_cancelled_booking_activities_report_b2b($op = '') {
        $this->load->model('sightseeing_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('activities');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CANCELLED'));

        $activites_booking_data = $this->sightseeing_model->b2b_sightseeing_report($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $activites_booking_data = $this->booking_data_formatter->format_sightseeing_booking_data($activites_booking_data, $this->current_module);
        $activites_booking_data = $activites_booking_data['data']['booking_details'];



        $export_data = array();
       // debug($activites_booking_data);exit;
        $i=1;
        foreach ($activites_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
			$export_data[$k]['agency_name'] = $v['agency_name'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['product_name'] = $v['product_name'];
            $export_data[$k]['location'] = $v['destination_name'];
           	$export_data[$k]['Booked_on'] = $v['voucher_date'];
            $export_data[$k]['travel_date'] = $v['travel_date'];
           	$export_data[$k]['confirmation_reference'] = $v['confirmation_reference'];
           	$export_data[$k]['Comm_Fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['net_commission'];
           	$export_data[$k]['tds'] = $v['net_commission_tds'];
           	$export_data[$k]['net_fare'] = $v['net_fare'];
           	$export_data[$k]['admin_commission'] = $v['admin_commission'];
           	$export_data[$k]['admin_markup'] = $v['admin_markup'];
           	$export_data[$k]['agent_commission'] = $v['agent_commission'];
           	$export_data[$k]['agent_tds'] = $v['agent_tds'];
           	$export_data[$k]['agent_buying_price'] = $v['agent_buying_price'];
           	$export_data[$k]['agent_markup'] = $v['agent_markup'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	        		
           	
        }
        //debug($export_data[$k]['booked_date']);exit;
        if ($op == 'excel') { // excel export
        	//error_reporting(E_ALL);
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'APP reference',
                    'c1' => 'Agency Name',
                    'd1' => 'Lead Pax Name',
                    'e1' => 'Lead Pax Email',
                    'f1' => 'Lead Pax Phone Number',
                    'g1' => 'Activity Name',
                    'h1' => 'Acitvity Location',
                    'i1' => 'BookedOn',
                    'j1' => 'JourneyDate',
                    'k1' => 'Confirmation Reference',
                    'l1' => 'Commission Fare',
                    'm1' => 'Commission',
					'n1' => 'TDS',
                    'o1' => 'Admin NetFare',
                    'p1' => 'Admin Profit',
                    'q1' => 'Admin Markup',
                    'r1' => 'Agent Commission',
                    's1' => 'Agent TDS',
                    't1' => 'Agent Net Fare',
                    'u1' => 'Agent Markup',
                    'v1' => 'GST',
                    'w1' => 'TotalFare',
                   
                   
                );
          
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'agency_name',
                    'd' => 'lead_pax_name',
                    'e' => 'lead_pax_email',
                    'f' => 'lead_pax_phone_number',
                    'g' => 'product_name',
                    'h' => 'location',
                    'i' => 'Booked_on',
                    'j' => 'travel_date',
                    'k' => 'confirmation_reference',
                    'l' => 'Comm_Fare',
                    'm' => 'commission',
                    'n' => 'tds',
                    'o' => 'net_fare',
                    'p' => 'admin_commission',
                    'q' => 'admin_markup',
                    'r' => 'agent_commission',
                    's' => 'agent_tds',
                  	't' => 'agent_buying_price',
                    'u' => 'agent_markup',
                    'v' => 'gst',
                    'w' => 'grand_total',
                   
                                        
                );
           
            $excel_sheet_properties = array(
                'title' => 'Cancelled_Booking_activitesReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Cancelled_Booking_activitesReport',
                'sheet_title' => 'Cancelled_Booking_activitesReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
        else if ($op == 'csv') { // excel export
            // echo "dd";exit;
            
               
            $headings = array("Sl. No.","APP reference","Agency Name","Lead Pax Name","Lead Pax Email","Lead Pax Phone Number","Activity Name","Acitvity Location","BookedOn","JourneyDate","Confirmation Reference","No of infant","City","Travel Date","Commission Fare",'Commission','TDS','Admin NetFare','Admin Profit','Admin Markup','Agent Commission','Agent TDS','Agent Net Fare','Agent Markup','GST','TotalFare'); 
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
           $this->provab_csv->csv_export($headings,'Cancelled_Booking_activitesReport', $export_data);
           
           
            
            
        }
        else if($op == 'pdf')
        {
            $this->load->library ( 'provab_pdf' );
            
            $create_pdf = new Provab_Pdf();
            $pdf_data['export_data']=$activites_booking_data;
            // debug($pdf_data['export_data']);exit;
            $mail_template =$this->template->isolated_view('report/b2b_report_activities_pdf',$pdf_data);
            $this->provab_pdf->create_pdf($mail_template);
            


        }


    }
    public function export_cancelled_booking_activities_report_b2b_email($op = '') {
        $this->load->model('sightseeing_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('activities');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CANCELLED'));

        $activites_booking_data = $this->sightseeing_model->b2b_sightseeing_report($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $activites_booking_data = $this->booking_data_formatter->format_sightseeing_booking_data($activites_booking_data, $this->current_module);
        $activites_booking_data = $activites_booking_data['data']['booking_details'];



        $export_data = array();
       // debug($activites_booking_data);exit;
        $i=1;
        foreach ($activites_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
			$export_data[$k]['agency_name'] = $v['agency_name'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['product_name'] = $v['product_name'];
            $export_data[$k]['location'] = $v['destination_name'];
           	$export_data[$k]['Booked_on'] = $v['voucher_date'];
            $export_data[$k]['travel_date'] = $v['travel_date'];
           	$export_data[$k]['confirmation_reference'] = $v['confirmation_reference'];
           	$export_data[$k]['Comm_Fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['net_commission'];
           	$export_data[$k]['tds'] = $v['net_commission_tds'];
           	$export_data[$k]['net_fare'] = $v['net_fare'];
           	$export_data[$k]['admin_commission'] = $v['admin_commission'];
           	$export_data[$k]['admin_markup'] = $v['admin_markup'];
           	$export_data[$k]['agent_commission'] = $v['agent_commission'];
           	$export_data[$k]['agent_tds'] = $v['agent_tds'];
           	$export_data[$k]['agent_buying_price'] = $v['agent_buying_price'];
           	$export_data[$k]['agent_markup'] = $v['agent_markup'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	        		
           	
        }
        //debug($export_data[$k]['booked_date']);exit;
        if ($op == 'excel') { // excel export
        	//error_reporting(E_ALL);
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'APP reference',
                    'c1' => 'Agency Name',
                    'd1' => 'Lead Pax Name',
                    'e1' => 'Lead Pax Email',
                    'f1' => 'Lead Pax Phone Number',
                    'g1' => 'Activity Name',
                    'h1' => 'Acitvity Location',
                    'i1' => 'BookedOn',
                    'j1' => 'JourneyDate',
                    'k1' => 'Confirmation Reference',
                    'l1' => 'Commission Fare',
                    'm1' => 'Commission',
					'n1' => 'TDS',
                    'o1' => 'Admin NetFare',
                    'p1' => 'Admin Profit',
                    'q1' => 'Admin Markup',
                    'r1' => 'Agent Commission',
                    's1' => 'Agent TDS',
                    't1' => 'Agent Net Fare',
                    'u1' => 'Agent Markup',
                    'v1' => 'GST',
                    'w1' => 'TotalFare',
                   
                   
                );
          
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'agency_name',
                    'd' => 'lead_pax_name',
                    'e' => 'lead_pax_email',
                    'f' => 'lead_pax_phone_number',
                    'g' => 'product_name',
                    'h' => 'location',
                    'i' => 'Booked_on',
                    'j' => 'travel_date',
                    'k' => 'confirmation_reference',
                    'l' => 'Comm_Fare',
                    'm' => 'commission',
                    'n' => 'tds',
                    'o' => 'net_fare',
                    'p' => 'admin_commission',
                    'q' => 'admin_markup',
                    'r' => 'agent_commission',
                    's' => 'agent_tds',
                  	't' => 'agent_buying_price',
                    'u' => 'agent_markup',
                    'v' => 'gst',
                    'w' => 'grand_total',
                   
                                        
                );
           
            $excel_sheet_properties = array(
                'title' => 'Cancelled_Booking_activitesReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Cancelled_Booking_activitesReport',
                'sheet_title' => 'Cancelled_Booking_activitesReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
              $excel=$this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties,'F');
            // echo BASEPATH;exit;
            $file_path=BASEPATH.'reportexcel/'.$excel;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                
            $res = $this->provab_mailer->send_mail($value, 'Activity Cancelled Booking Report', $message,$file_path);
            
            }
            unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);
        }
        else if ($op == 'csv') { // excel export
            // echo "dd";exit;
            
               
            $headings = array("Sl. No.","APP reference","Agency Name","Lead Pax Name","Lead Pax Email","Lead Pax Phone Number","Activity Name","Acitvity Location","BookedOn","JourneyDate","Confirmation Reference","No of infant","City","Travel Date","Commission Fare",'Commission','TDS','Admin NetFare','Admin Profit','Admin Markup','Agent Commission','Agent TDS','Agent Net Fare','Agent Markup','GST','TotalFare'); 
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
          $csv= $this->provab_csv->csv_export($headings,'Cancelled_Booking_activitesReport', $export_data,'F');
           $file_path=BASEPATH.'reportcsv/'.$csv;
            // debug($file_path);exit;
            $this->load->library ( 'provab_mailer' ); 
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                
            $res = $this->provab_mailer->send_mail($value, 'Cancelled_Booking_ActivityReport', $message,$file_path);
            
            }
            unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);
           
           
            
            
        }
        else if($op == 'pdf')
        {
            $this->load->library ( 'provab_pdf' );
            
            $create_pdf = new Provab_Pdf();
            $pdf_data['export_data']=$activites_booking_data;
            // debug($pdf_data['export_data']);exit;
            $mail_template =$this->template->isolated_view('report/b2b_report_activities_pdf',$pdf_data);
            $pdf=$this->provab_pdf->create_pdf($mail_template,'F');
             $message="";
              // $mail_template="vvvvvxdxd";
                   
               $message = '
               <span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
              
              $emil=$this->input->post('email');
        
            $emails=explode(",",$emil);
         
            foreach ($emails as $key => $value) {
                
            $res = $this->provab_mailer->send_mail($value, 'Activity Cancelled Booking Report', $message,$pdf);
            
            }
            // unlink($file_path);
            redirect($_SERVER['HTTP_REFERER']);
            


        }


    }
    public function export_all_log($op ='') {
    	// error_reporting(E_ALL);
        $get_data = $this->input->get();
		$condition = array();
		$page_data = array();
		//From-Date and To-Date
		$from_date = trim(@$get_data['created_datetime_from']);
		$to_date = trim(@$get_data['created_datetime_to']);
		//Auto swipe date
		if(empty($from_date) == false && empty($to_date) == false){

			$valid_dates = auto_swipe_dates($from_date, $to_date);
			$from_date = $valid_dates['from_date'];
			$to_date = $valid_dates['to_date'];
		}
		
		if (intval(@$get_data['agent_id']) > 0) {
			$condition[] = array('U.user_id', '=', intval($get_data['agent_id']));
		}
		
		if(empty($from_date) == false) {
			$ymd_from_date = date('Y-m-d', strtotime($from_date));
			$condition[] = array('TL.created_datetime', '>=', $this->db->escape($ymd_from_date));
		}

		if(empty($to_date) == false) {
			$ymd_to_date = date('Y-m-d', strtotime($to_date));
			$condition[] = array('TL.created_datetime', '<=', $this->db->escape($ymd_to_date));
		}

		if (trim(@$get_data['transaction_type']) != '') {
			$condition[] = array('TL.transaction_type', '=', $this->db->escape($get_data['transaction_type']));
		}

		if (trim(@$get_data['app_reference']) != '') {
			$condition[] = array('TL.app_reference', '=', $this->db->escape($get_data['app_reference']));
		}
		$this->load->model('transaction_model');
		$this->load->library('booking_data_formatter');
		// $total_records = $this->transaction_model->logs($condition, true);
		$transaction_details = $this->transaction_model->logs($condition, false, 0, 2000);
		$transaction_details = $this->booking_data_formatter->format_recent_transactions($transaction_details, 'b2c');
		$table_data = $transaction_details['data']['transaction_details'];


        $i=1;
        // debug($table_data);exit;
        foreach ($table_data as $k => $v) {
           if ($v['transaction_owner_id'] == 0) {
						$user_info = 'Guest';
					} else {
						$user_info = $v['username'];
					}
             $i++;
			$export_data[$k]['username'] = $user_info;
            $export_data[$k]['created_datetime'] = app_friendly_date($v['created_datetime']).' '.date('g:i A',strtotime($v['created_datetime']));
            $export_data[$k]['app_reference'] = $v['app_reference'];
            $export_data[$k]['transaction_type'] = ucfirst($v['transaction_type']);
            $export_data[$k]['grand_total'] =(abs($v['grand_total'])).'-'.$v['currency'];
            $export_data[$k]['remarks'] = $v['remarks'];
           	
        }

        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'username',
                    'c1' => 'created datetime',
                    'd1' => 'app reference',
                    'e1' => 'transaction type',
                    'f1' => 'grand total',
                    'g1' => 'remarks',
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'username',
                    'c' => 'created_datetime',
                    'd' => 'app_reference',
                    'e' => 'transaction_type',
                    'f' => 'grand_total',
                    'g' => 'remarks',
                    
                );
           
            $excel_sheet_properties = array(
                'title' => 'All_transaction_log' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'All_transaction_log',
                'sheet_title' => 'All_transaction_log'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
        
    }
    //change start added the new function for supervision remarks 1
    function modify_remarks()
    {
        $post_data = $this->input->post();
        $fin_remarks = $post_data['fin_remarks'];
        $oth_remarks = $post_data['oth_remarks'];
        $report_origin = $post_data['report_origin'];
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $requestUri = $post_data['requested_uri'];
        $redirectUrl = $protocol . '://' . $host . $requestUri;
        $module = $post_data['module'];
        $report_org = $this->custom_db->single_table_records('flight_booking_details', '*', array('origin' => $report_origin));
        $attributes = json_decode($report_org['data'][0]['attributes']);
        $current_user_id = $GLOBALS['CI']->entity_user_id;
        $fin_remarks_counter = 0;
        $oth_remarks_counter = 0;
        if ($fin_remarks != '') {
            $attributes->fin_remarks = $fin_remarks;
            $attributes->fin_remarks_user = $current_user_id;
            $attributes->fin_remarks_user_name = $this->user_model->get_user($current_user_id)[0]['full_name'];
            $fin_remarks_counter++;
            $attributes->fin_remarks_counter = $fin_remarks_counter;
        }
        if ($oth_remarks != '') {
            $attributes->oth_remarks = $oth_remarks;
            $attributes->oth_remarks_user = $current_user_id;
            $attributes->oth_remarks_user_name = $this->user_model->get_user($current_user_id)[0]['full_name'];
            $oth_remarks_counter++;
            $attributes->oth_remarks_counter = $oth_remarks_counter;
        }
        $attributes->remarks_updated = date('Y-m-d H:i:s');
        $attributes = json_encode($attributes);
        $query = 'UPDATE `flight_booking_details` SET `attributes` = ' . "'" . $attributes . "'" . ' WHERE `origin` = ' . $report_origin;
        $this->db->query($query);
        redirect($redirectUrl);
    }
//change end added the new function for supervision remarks 1
    
//change 1 for exporting booking data: added the following function
public function export_booking_airline_report($op = '', $type = '', $action = '', $module = '')
    {
      
        $this->load->model('flight_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);
        $filter_data = $this->format_basic_search_filters('flight');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }
        switch ($type) {
            case 'confirmed':
                $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CONFIRMED'));
                $title = 'Confirmed_Booking_AirlineReport_';
                $description = 'Confirmed_Booking_AirlineReport';
                $sheet_title = 'Confirmed_Booking_AirlineReport';
                $email_title = 'Airline Confirmed Booking Report';
                break;
            case 'cancelled':
                $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CANCELLED'));
                $title = 'Cancelled_Booking_AirlineReport_';
                $description = 'Cancelled_Booking_AirlineReport';
                $sheet_title = 'Cancelled_Booking_AirlineReport';
                $email_title = 'Airline Cancelled Booking Report';
                break;
            case 'all':
                 
                $condition[] = array();
                $title = 'All_Booking_AirlineReport_';
                $description = 'All_Booking_AirlineReport';
                $sheet_title = 'All_Booking_AirlineReport';
                $email_title = 'Airline All Booking Report';
                break;
            default:
                $condition[] = array();
                break;
        }
        if ($module == 'b2c') {
            // changes start booking export: removed limit from this line
            // $flight_booking_data = $this->flight_model->b2c_flight_report($condition, false, 0, 500); //Maximum 500 Data Can be exported at time
            $flight_booking_data = $this->flight_model->b2c_flight_report($condition, false, 0);
            // changes end booking export: removed limit from this line
            $flight_booking_data = $this->booking_data_formatter->format_flight_booking_data($flight_booking_data, $this->current_module);
            $flight_booking_data = $flight_booking_data['data']['booking_details'];
            $export_data = array();
            $i = 1;
            foreach ($flight_booking_data as $parent_k => $parent_v) {
                $totalAmount = $parent_v['grand_total'];
                $convDetailsValue = 0;
                if ($parent_v['booking_transaction_details'][0]['status_description'] != "") {
                    $convDetailsArray = explode('*', $parent_v['booking_transaction_details'][0]['status_description']);
                    $convDetailsValue = (float) $convDetailsArray[0];
                    $convDetailsType = $convDetailsArray[1];
                    if ($convDetailsType == 'percentage') {
                        $convDetailsValue = ($convDetailsValue / (float) 100) * $totalAmount;
                    }
                }
                extract($parent_v);
                $booking_attributes_remarks = json_decode($attributes);
                $attributes = json_decode($parent_v['booking_transaction_details'][0]['attributes'], true);
                if ($op == 'csv') {
                    $export_data[$parent_k]['sr_no'] = $i;
                }
                $i++;
                $export_data[$parent_k]['app_reference'] = $app_reference;
                $export_data[$parent_k]['status'] = @$status;
                $export_data[$parent_k]['lead_pax_details'] = $lead_pax_name . ', ' . $email . ", " . $phone;
                $export_data[$parent_k]['pnr'] = @$pnr;
                $export_data[$parent_k]['from'] = $from_loc;
                $export_data[$parent_k]['to'] = $to_loc;
                $export_data[$parent_k]['booked_via'] = @flight_supplier_name($booking_source);
                $export_data[$parent_k]['payment_status'] = @$booking_payment_details[0]['status'];
                $export_data[$parent_k]['payment_mode'] = @$booking_payment_details[0]['payment_mode'];
                $transaction_id = @$booking_payment_details[0]['transaction_id'];
                if ($booking_payment_details[0]['payment_mode'] == 'connect') {
                    $transaction_idArray = json_decode($transaction_id, true);
                    $transaction_id =  $transaction_idArray['referenceId'];
                }
                $export_data[$parent_k]['transaction_id'] = @$transaction_id;
                $export_data[$parent_k]['financial_remarks'] = @$booking_attributes_remarks->fin_remarks;
                $export_data[$parent_k]['other_remarks'] = @$booking_attributes_remarks->oth_remarks;
                $export_data[$parent_k]['remarks_updated'] = 'Added At: ' . @$booking_attributes_remarks->remarks_updated . ', Financial Comment By ' . @$booking_attributes_remarks->fin_remarks_user_name . ', Remarks By ' . @$booking_attributes_remarks->oth_remarks_user_name;
                $export_data[$parent_k]['supplier_name'] = @flight_supplier_name($booking_source);
                $export_data[$parent_k]['trip_type'] = $trip_type_label;
                $export_data[$parent_k]['booked_on'] = date('d-m-Y H:i:s A', strtotime($created_datetime));
                $export_data[$parent_k]['travel_date'] = date('d-m-Y', strtotime($journey_start));
                $export_data[$parent_k]['comm_fare'] = $fare;
                $export_data[$parent_k]['commission'] = $net_commission;
                if ($booking_source == "PTBSID0000000021") {
                    $trans_attributes = json_decode($booking_transaction_details[0]['attributes'], true);
                    $currency_obj = new Currency(array(
                        'module_type' => 'flight',
                        'from' => $trans_attributes['Fare']['Currency'],
                        'to' => 'NPR',
                    ));
                    $export_data[$parent_k]['tax'] = round(get_converted_currency_value($currency_obj->force_currency_conversion($attributes['Fare']['Tax'])));
                } else {
                    if ($booking_source == "PTBSID0000000002") {
                        $trans_attributes = json_decode($booking_transaction_details[0]['attributes'], true);
                        $currency_obj = new Currency(array(
                            'module_type' => 'flight',
                            'from' => $trans_attributes['Fare']['Currency'],
                            'to' => 'NPR',
                        ));
                        $export_data[$parent_k]['tax'] = round(get_converted_currency_value($currency_obj->force_currency_conversion($attributes['Fare']['Tax'])));
                    } else {
                        $export_data[$parent_k]['tax'] = $attributes['Fare']['Tax'];
                    }
                }
                $export_data[$parent_k]['tds'] = $net_commission_tds;
                $export_data[$parent_k]['net_fare'] = $net_fare;
                $export_data[$parent_k]['admin_markup'] = $admin_markup;
                $export_data[$parent_k]['gst'] = $gst;
                $export_data[$parent_k]['conveince_fee'] = @$convinence_amount + @$convDetailsValue;
                $export_data[$parent_k]['promocode_used'] = @$promo_code;
                $export_data[$parent_k]['discount'] = $discount + $reward_amount;
                $export_data[$parent_k]['segment_discount'] = $segment_discount;
                $export_data[$parent_k]['customer_paid_amount'] = ($grand_total - ($segment_discount + $reward_amount));
            }
        } elseif ($module == 'b2b') {
                // changes start booking export: removed limit from this line
            // $flight_booking_data = $this->flight_model->b2b_flight_report($condition, false, 0, 500); //Maximum 500 Data Can be exported at time
            $flight_booking_data = $this->flight_model->b2b_flight_report($condition, false, 0);
            // changes end booking export: removed limit from this line
            $flight_booking_data = $this->booking_data_formatter->format_flight_booking_data($flight_booking_data, $this->current_module);
            $flight_booking_data = $flight_booking_data['data']['booking_details'];
            $export_data = array();
            $i = 1;
            foreach ($flight_booking_data as $parent_k => $parent_v) {
                extract($parent_v);
                $booking_attributes_remarks = json_decode($attributes);
                $attributes = json_decode($parent_v['booking_transaction_details'][0]['attributes'], true);
                if ($op == 'csv') {
                    $export_data[$parent_k]['sr_no'] = $i;
                }
                $i++;
                $export_data[$parent_k]['app_reference'] = $app_reference;
                $export_data[$parent_k]['status'] = @$status;
                $export_data[$parent_k]['lead_pax_details'] = $lead_pax_name . ', ' . $email . ", " . $phone;
                $export_data[$parent_k]['pnr'] = @$pnr;
                $export_data[$parent_k]['from'] = $from_loc;
                $export_data[$parent_k]['to'] = $to_loc;
                $export_data[$parent_k]['agency_name'] = $agency_name;
                $export_data[$parent_k]['finantial_remarks'] = @$booking_attributes_remarks->fin_remarks;
                $export_data[$parent_k]['other_remarks'] = @$booking_attributes_remarks->oth_remarks;
                $export_data[$parent_k]['remarks_updated'] = 'Added At: ' . @$booking_attributes_remarks->remarks_updated . ', Financial Comment By ' . @$booking_attributes_remarks->fin_remarks_user_name . ', Remarks By ' . @$booking_attributes_remarks->oth_remarks_user_name;
                $export_data[$parent_k]['supplier_name'] = @flight_supplier_name($booking_source);
                $export_data[$parent_k]['trip_type'] = $trip_type_label;
                $export_data[$parent_k]['booked_on'] = date('d-m-Y H:i:s A', strtotime($created_datetime));
                $export_data[$parent_k]['travel_date'] = date('d-m-Y', strtotime($journey_start));
                $export_data[$parent_k]['comm_fare'] = $fare;
                $export_data[$parent_k]['commission'] = $net_commission;
                if ($booking_source == "PTBSID0000000021") {
                    $trans_attributes = json_decode($booking_transaction_details[0]['attributes'], true);
                    $currency_obj = new Currency(array(
                        'module_type' => 'flight',
                        'from' => $trans_attributes['Fare']['Currency'],
                        'to' => 'NPR',
                    ));
                    $export_data[$parent_k]['tax'] = round(get_converted_currency_value($currency_obj->force_currency_conversion($attributes['Fare']['Tax'])));
                } else {
                    if ($booking_source == "PTBSID0000000002") {
                        $trans_attributes = json_decode($booking_transaction_details[0]['attributes'], true);
                        $currency_obj = new Currency(array(
                            'module_type' => 'flight',
                            'from' => $trans_attributes['Fare']['Currency'],
                            'to' => 'NPR',
                        ));
                        $export_data[$parent_k]['tax'] = round(get_converted_currency_value($currency_obj->force_currency_conversion($attributes['Fare']['Tax'])));
                    } else {
                        $export_data[$parent_k]['tax'] = $attributes['Fare']['Tax'];
                    }
                }
                $export_data[$parent_k]['segment_discount'] = $segment_discount;
                $export_data[$parent_k]['net_commission_tds'] = $net_commission_tds;
                $export_data[$parent_k]['net_fare'] = $net_fare;
                $export_data[$parent_k]['admin_markup'] = $admin_markup;
                $export_data[$parent_k]['gst'] = $gst;
                $export_data[$parent_k]['agent_commission'] = $agent_commission;
                $export_data[$parent_k]['agent_tds'] = $agent_tds;
                $export_data[$parent_k]['agent_net_fare'] = $agent_buying_price - $agent_markup;
                $export_data[$parent_k]['agent_markup'] = $agent_markup;
                $export_data[$parent_k]['total_fare'] = ($grand_total - ($segment_discount + $reward_amount));
            }
        }
        if ($op == 'excel') { // excel export
  
            if ($module == 'b2c') {
                $headings = array(
                    'a1' => 'Sno',
                    'b1' => 'Reference No',
                    'c1' => 'Status',
                    'd1' => 'Lead Pax Details',
                    'e1' => 'PNR',
                    'f1' => 'From',
                    'g1' => 'To',
                    'h1' => 'Booked Via',
                    'i1' => 'Payment Status',
                    'j1' => 'Payment mode',
                    'k1' => 'Transaction id',
                    'l1' => 'Financial Remarks',
                    'm1' => ' Other Remarks',
                    'n1' => 'Creation Details',
                    'o1' => 'Supplier Name',
                    'p1' => 'Type',
                    'q1' => 'BookedOn',
                    'r1' => 'Travel date',
                    's1' => 'Comm.Fare',
                    't1' => 'Commission',
                    'u1' => 'Tax',
                    'v1' => 'TDS',
                    'w1' => 'NetFare',
                    'x1' => 'Admin Markup',
                    'y1' => 'GST',
                    'z1' => 'Convenience Fee',
                    'aa1' => 'Promocode Used',
                    'ab1' => 'Discount',
                    'ac1' => 'Segment Discount',
                    'ad1' => ' Customer paid amount'
                );
                $fields = array(
                    'a' => '',
                    'b' => 'app_reference',
                    'c' => 'status',
                    'd' => 'lead_pax_details',
                    'e' => 'pnr',
                    'f' => 'from',
                    'g' => 'to',
                    'h' => 'booked_via',
                    'i' => 'payment_status',
                    'j' => 'payment_mode',
                    'k' => 'transaction_id',
                    'l' => 'financial_remarks',
                    'm' => 'other_remarks',
                    'n' => 'remarks_updated',
                    'o' => 'supplier_name',
                    'p' => 'trip_type',
                    'q' => 'booked_on',
                    'r' => 'travel_date',
                    's' => 'comm_fare',
                    't' => 'commission',
                    'u' => 'tax',
                    'v' => 'tds',
                    'w' => 'net_fare',
                    'x' => 'admin_markup',
                    'y' => 'gst',
                    'z' => 'conveince_fee',
                    'aa' => 'promocode_used',
                    'ab' => 'discount',
                    'ac' => 'segment_discount',
                    'ad' => 'customer_paid_amount',
                );
            } elseif ($module == 'b2b') {
                $headings = array(
                    'a1' => 'Sno',
                    'b1' => 'Reference No',
                    'c1' => 'Status',
                    'd1' => 'Lead Pax Details',
                    'e1' => 'PNR',
                    'f1' => 'From',
                    'g1' => 'To',
                    'h1' => 'Booked Via',
                    'i1' => 'Financial Remarks',
                    'j1' => 'Other Remarks',
                    'k1' => 'Creation Details',
                    'l1' => 'Supplier Name',
                    'm1' => 'Type',
                    'n1' => 'BookedOn',
                    'o1' => 'Travel  date',
                    'p1' => 'Comm.Fare',
                    'q1' => 'Commission',
                    'r1' => 'Tax',
                    's1' => 'Segment Discount',
                    't1' => 'TDS',
                    'u1' => 'Admin NetFare',
                    'v1' => 'Admin Markup',
                    'w1' => 'GST',
                    'x1' => 'Agent Commission',
                    'y1' => 'Agent TDS',
                    'z1' => 'Agent Net Fare',
                    'aa1' => 'Agent Markup',
                    'ab1' => 'TotalFare',
                );
                $fields = array(
                    'a' => '',
                    'b' => 'app_reference',
                    'c' => 'status',
                    'd' => 'lead_pax_details',
                    'e' => 'pnr',
                    'f' => 'from',
                    'g' => 'to',
                    'h' => 'agency_name',
                    'i' => 'finantial_remarks',
                    'j' => 'other_remarks',
                    'k' => 'remarks_updated',
                    'l' => 'supplier_name',
                    'm' => 'trip_type',
                    'n' => 'booked_on',
                    'o' => 'travel_date',
                    'p' => 'comm_fare',
                    'q' => 'commission',
                    'r' => 'tax',
                    's' => 'segment_discount',
                    't' => 'net_commission_tds',
                    'u' => 'net_fare',
                    'v' => 'admin_markup',
                    'w' => 'gst',
                    'x' => 'agent_commission',
                    'y' => 'agent_tds',
                    'z' => 'agent_net_fare',
                    'aa' => 'agent_markup',
                    'ab' => 'total_fare',
                );
            }
     
            $excel_sheet_properties = array(
                'title' => $title . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => $description,
                'sheet_title' => $sheet_title
            );
            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
           
            if ($action == 'mail') {
              
                $excel = $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties, 'F');
            
                $file_path = BASEPATH . 'reportexcel/' . $excel;
                $this->load->library('provab_mailer');
                $message = "";
                $message = '<span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
                $emil = $this->input->post('email');
                $emails = explode(",", $emil);
            
                foreach ($emails as $key => $value) {
                    $res = $this->provab_mailer->send_mail($value, $email_title, $message, $file_path);
                }
                unlink($file_path);
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
            }
        } else if ($op == 'csv') { // excel export
            if ($module == 'b2c') {
                $headings = array("Sno", "Reference No", "Status", "Lead Pax Details", "PNR", "From", "To", "Booked Via", "Payment Status", "Payment mode", "Transaction id", "Financial Remarks", " Other Remarks", "Creation Details", "Supplier Name", "Type", "BookedOn", "Travel date", "Comm.Fare", "Commission", "Tax", "TDS", "NetFare", "Admin Markup", "GST", "Convenience Fee", "Promocode Used", "Discount", "Segment Discount", " Customer paid amount");
            } elseif ($module == 'b2b') {
                $headings = array("Sno", "Reference No", "Status", "Lead Pax Details", "PNR", "From", "To", "Booked Via", "Financial Remarks", "Other Remarks", "Creation Details", "Supplier Name", "Type", "BookedOn", "Travel  date", "Comm.Fare", "Commission", "Tax", "Segment Discount", "TDS", "Admin NetFare", "Admin Markup", "GST", "Agent Commission", "Agent TDS", "Agent Net Fare", "Agent Markup", "TotalFare");
            }
            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            if ($action == 'mail') {
                $csv = $this->provab_csv->csv_export($headings, $email_title, $export_data, 'F');
                $file_path = BASEPATH . 'reportcsv/' . $csv;
                $this->load->library('provab_mailer');
                $message = "";
                $message = '<span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
                $emil = $this->input->post('email');
                $emails = explode(",", $emil);
                foreach ($emails as $key => $value) {
                    $res = $this->provab_mailer->send_mail($value, $title, $message, $file_path);
                }
                unlink($file_path);
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                $this->provab_csv->csv_export($headings, $title, $export_data);
            }
            // changes start booking export: commented and added with modified code inside this else if
        } else if ($op == 'pdf') {
            // $this->load->library('provab_pdf');
            // $this->load->library('provab_mailer');
            // $create_pdf = new Provab_Pdf();
            // $pdf_data['export_data'] = $flight_booking_data;
            // $mail_template = $this->template->isolated_view('report/' . $module . '_report_airline_pdf', $pdf_data);
            // if ($action == 'mail') {
            //     $pdf = $this->provab_pdf->create_pdf($mail_template, 'F');
            //     $message = "";
            //     $message = '<span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
            //     $emil = $this->input->post('email');
            //     $emails = explode(",", $emil);
            //     foreach ($emails as $key => $value) {
            //         $res = $this->provab_mailer->send_mail($value, $email_title, $message, $pdf);
            //     }
            //     unlink($pdf);
            //     redirect($_SERVER['HTTP_REFERER']);
            // } else {
            //     $this->provab_pdf->create_pdf($mail_template);
            // }
            if(count($flight_booking_data) > EXPORT_CHUNK_COUNT_BOOKING_REPORT_FLIGHT) {
                $this->export_booking_airline_report_pdf_zip($flight_booking_data, $module, $title, $email_title, $action);
            } else {
                $this->export_booking_airline_report_pdf($flight_booking_data, $module, $title, $email_title, $action);
            }
    }
        // changes end booking export: commented and added with modified code inside this else if
}

 // changes start booking export: added function export_booking_airline_report_pdf
 function export_booking_airline_report_pdf($data_list, $module, $title, $email_title, $action) {
    $this->load->library('provab_pdf');
    $this->load->library('provab_mailer');
    $pdf_data['export_data'] = $data_list;
        $mail_template = $this->template->isolated_view('report/' . $module . '_report_airline_pdf', $pdf_data);
    $pdf = $this->provab_pdf->create_pdf($mail_template, "F", "", "L");
    if($action == 'mail') {
        $message = "";
        $message = '<span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
        $emil = $this->input->post('email');
        $emails = explode(",", $emil);
        $this->provab_mailer->send_mail($value, $email_title, $message, $zipFilePath);
        unlink($pdf);
        redirect($_SERVER['HTTP_REFERER']);
    } else {
        // Set appropriate headers for file download
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename=' . $title . date("Y-m-d") . '.pdf');
        readfile($pdf);
        unlink($pdf);
        exit;
    }
}
// changes end booking export: added function export_booking_airline_report_pdf

// changes start booking export: added function export_booking_airline_report_pdf_zip
function export_booking_airline_report_pdf_zip($data_list, $module, $title, $email_title, $action) {
    $this->load->library('provab_pdf');
    $this->load->library('provab_mailer');
    // Split the array into chunks of EXPORT_CHUNK_COUNT
    $chunks = array_chunk($data_list, EXPORT_CHUNK_COUNT_BOOKING_REPORT_FLIGHT);
    
    // Get the number of chunks
    $numChunks = count($chunks);
    
    // Handle the remaining elements (less than EXPORT_CHUNK_COUNT)
    $remaining = count($data_list) % EXPORT_CHUNK_COUNT_BOOKING_REPORT_FLIGHT;
    if ($remaining > 0) {
        // Add the remaining elements to the last chunk
        $chunks[$numChunks - 1] = array_slice($data_list, -($remaining));
    }

    // Initialize array to store file paths of generated PDFs
    $pdfFiles = [];
    $filenameArr = [];

    foreach ($chunks as $index => $chunk) {
        // Generate a unique filename for the PDF
        $filename = $title . '_' . uniqid() . date("Y-m-d") . '.pdf';
        $filenameArr[] = $filename;
        
        // Set the output name for the PDF
        $outputName = $filename;

        $pdf_data['export_data'] = $chunk;
        $mail_template = $this->template->isolated_view('report/' . $module . '_report_airline_pdf', $pdf_data);
        
        // Generate PDF with the dynamically generated output path
        $pdf = $this->provab_pdf->create_pdf($mail_template, "F", $outputName, "L");
        
        // Store the file path in the array
        $pdfFiles[] = $pdf;
    }


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

        if($action == 'mail') {
                $message = "";
                $message = '<span style="line-height:25px; font-size:15px;">Please find the Booking Report below. </span>';
                $emil = $this->input->post('email');
                $emails = explode(",", $emil);
                foreach ($emails as $key => $value) {
                    $this->provab_mailer->send_mail($value, $email_title, $message, $zipFilePath);
                }
                // delete the temporary zip file
                unlink($zipFilePath);
                // Delete the temporary PDF files
                foreach ($pdfFiles as $pdf) {
                    unlink($pdf);
                }
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                // Set appropriate headers for file download
                header('Content-Type: application/zip');
                header('Content-Disposition: attachment; filename=' . $title . date("Y-m-d") . '.zip');
                // Read the zip file and output its contents
                readfile($zipFilePath);
                // delete the temporary zip file
                unlink($zipFilePath);
                // Delete the temporary PDF files
                foreach ($pdfFiles as $pdf) {
                    unlink($pdf);
                }
                exit;
            }
        } else {
            die("Failed to create zip file");
        }
    }
// changes end booking export: added function export_booking_airline_report_pdf_zip


}


