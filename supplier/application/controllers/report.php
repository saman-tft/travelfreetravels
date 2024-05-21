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
		$this->load->model('sightseeing_model');
		$this->load->model('transferv1_model');
		$this->load->model('transfers_model');
		$this->load->model('activity_model');
		$this->load->model('car_model');
		$this->load->model('domain_management_model');
		$this->load->model('user_model');
		$this->load->library('booking_data_formatter');
		$this->current_module = $this->config->item('current_module');
		$this->load->model('hotels_model');
		$this->load->model('package_model');
		$this->load->model('transaction_model');
		$this->load->model('tours_model');
//		$this->load->library('export');

	}
	function index()
	{
		redirect('general');
	}
function b2b_activities_report($offset=0)
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
		$config['base_url'] = base_url().'index.php/report/b2b_activities_report/';
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
	function monthly_booking_report()
	{
		$this->template->view('report/monthly_booking_report');
	}
		function b2b_transfers_report($offset=0){
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
		$config['base_url'] = base_url().'index.php/report/b2b_transfers_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		 //debug($page_data);exit;
		$this->template->view('report/b2c_transfer_crs', $page_data);
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
		$table_data = $this->bus_model->booking($condition, false, $offset, RECORDS_RANGE_2);
		$table_data = $this->booking_data_formatter->format_bus_booking_data($table_data,$this->current_module);
		$page_data['table_data'] = $table_data['data'];
		/** TABLE PAGINATION */
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/bus/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$page_data['customer_email'] = $this->entity_email;
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');
		$this->template->view('report/bus', $page_data);
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
		function b2b_hotelcrs_report($offset=0)
	{
	  
		$condition = array();
		$get_data = $this->input->get();

		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];
		
		$total_records = $this->hotel_model->b2b_hotelcrs_report($condition, true);
		$table_data = $this->hotel_model->b2b_hotelcrs_report($condition, false, $offset, RECORDS_RANGE_2);
		$table_data = $this->booking_data_formatter->format_hotel_booking_data($table_data, $this->current_module);
		$page_data['table_data'] = $table_data['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2b_hotel_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
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
		$table_data = $this->hotel_model->b2c_hotelcrs_report($condition, false, $offset, RECORDS_RANGE_2);
			//debug($table_data['data']); exit;
		$table_data = $this->booking_data_formatter->format_hotel_booking_data($table_data,$this->current_module);
		$page_data['table_data'] = $table_data['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2c_hotel_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		//debug($page_data);exit;
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');
		$this->template->view('report/b2c_report_hotel', $page_data);
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
		$table_data = $this->hotel_model->booking($condition, false, $offset, RECORDS_RANGE_2);
		$table_data = $this->booking_data_formatter->format_hotel_booking_data($table_data,$this->current_module);
		$page_data['table_data'] = $table_data['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/hotel/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
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
		$table_data = $this->bus_model->b2c_bus_report($condition, false, $offset, RECORDS_RANGE_2);
		$table_data = $this->booking_data_formatter->format_bus_booking_data($table_data,$this->current_module);
		
		$page_data['table_data'] = $table_data['data'];

		//debug($table_data); exit;

		/** TABLE PAGINATION */
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2c_bus_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
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
		$table_data = $this->bus_model->b2b_bus_report($condition, false, $offset, RECORDS_RANGE_2);
		$table_data = $this->booking_data_formatter->format_bus_booking_data($table_data,'b2b');
		// debug($table_data);die;
		$page_data['table_data'] = $table_data['data'];
		/** TABLE PAGINATION */
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2b_bus_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$page_data['customer_email'] = $this->entity_email;
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');

		$agent_info = $this->custom_db->single_table_records('user','*',array('user_type'=>B2B_USER,'domain_list_fk'=>get_domain_auth_id()));
		
		$page_data['agent_details'] = magical_converter(array('k' => 'user_id', 'v' => 'agency_name'), $agent_info);
		//debug($page_data);die;
		
		$this->template->view('report/b2b_report_bus', $page_data);
	}
	function b2b2b_bus_report($offset=0)
	{

		$get_data = $this->input->get();
		$condition = array();
		$page_data = array();

		$filter_data = $this->format_basic_search_filters('bus');
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];

		//debug($condition); die;
		$total_records = $this->bus_model->b2b2b_bus_report($condition, true);
		$table_data = $this->bus_model->b2b2b_bus_report($condition, false, $offset, RECORDS_RANGE_2);
		$table_data = $this->booking_data_formatter->format_bus_booking_data($table_data,'b2b');
		$page_data['table_data'] = $table_data['data'];
		/** TABLE PAGINATION */
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2b2b_bus_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$page_data['customer_email'] = $this->entity_email;
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');

		$agent_info = $this->custom_db->single_table_records('user','*',array('user_type'=>SUB_AGENT,'domain_list_fk'=>get_domain_auth_id()));
		
		$page_data['agent_details'] = magical_converter(array('k' => 'user_id', 'v' => 'first_name'), $agent_info);
		//debug($page_data);die;
		
		$this->template->view('report/b2b2b_report_bus', $page_data);
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
		$table_data = $this->hotel_model->b2b_hotel_report($condition, false, $offset, RECORDS_RANGE_2);
		$table_data = $this->booking_data_formatter->format_hotel_booking_data($table_data, $this->current_module);
		$page_data['table_data'] = $table_data['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2b_hotel_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
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

	function b2b2b_hotel_report($offset=0)
	{
		$condition = array();
		$get_data = $this->input->get();

		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];
		
		$total_records = $this->hotel_model->b2b2b_hotel_report($condition, true);
		$table_data = $this->hotel_model->b2b2b_hotel_report($condition, false, $offset, RECORDS_RANGE_2);
		$table_data = $this->booking_data_formatter->format_hotel_booking_data($table_data, $this->current_module);
		$page_data['table_data'] = $table_data['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2b2b_hotel_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		//debug($page_data);exit;
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');
		
		$agent_info = $this->custom_db->single_table_records('user','*',array('user_type'=>SUB_AGENT,'domain_list_fk'=>get_domain_auth_id()));
		
		$page_data['agent_details'] = magical_converter(array('k' => 'user_id', 'v' => 'first_name'), $agent_info);
		$this->template->view('report/b2b2b_report_hotel', $page_data);
	}
	
	
	function b2c_hotel_report($offset=0)
	{
		redirect(base_url('report/b2c_crs_hotel_report'));die;
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
		$table_data = $this->hotel_model->b2c_hotel_report($condition, false, $offset, RECORDS_RANGE_2);
			//debug($table_data['data']); exit;
		$table_data = $this->booking_data_formatter->format_hotel_booking_data($table_data,$this->current_module);
		$page_data['table_data'] = $table_data['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2c_hotel_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
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
		$table_data = $this->sightseeing_model->b2c_sightseeing_report($condition, false, $offset, RECORDS_RANGE_2);
			//debug($table_data['data']); exit;
		$table_data = $this->booking_data_formatter->format_sightseeing_booking_data($table_data,$this->current_module);
		$page_data['table_data'] = $table_data['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2c_sightseeing_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
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
	function b2b_activities_reportold($offset=0)
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
		$table_data = $this->sightseeing_model->b2b_sightseeing_report($condition, false, $offset, RECORDS_RANGE_2);
		$table_data = $this->booking_data_formatter->format_sightseeing_booking_data($table_data, $this->current_module);
		// debug($table_data);
		// exit;
		$page_data['table_data'] = $table_data['data'];
		
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2b_sightseeing_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
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
	function b2b2b_activities_report($offset=0)
	{
		$current_user_id = $GLOBALS['CI']->entity_user_id;
		$get_data = $this->input->get();
		$condition = array();
		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];

		$total_records = $this->sightseeing_model->b2b2b_sightseeing_report($condition, true);
		//echo '<pre>'; print_r($page_data); die;
		$table_data = $this->sightseeing_model->b2b2b_sightseeing_report($condition, false, $offset, RECORDS_RANGE_2);
		$table_data = $this->booking_data_formatter->format_sightseeing_booking_data($table_data, $this->current_module);
		// debug($table_data);
		// exit;
		$page_data['table_data'] = $table_data['data'];
		
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2b2b_activities_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');

		$user_cond = [];
		$user_cond [] = array('U.user_type','=',' (', B2B_USER, ')');
		$user_cond [] = array('U.domain_list_fk' , '=' ,get_domain_auth_id());

		//$agent_info['data'] = $this->user_model->b2b_user_list($user_cond,false);

		$agent_info = $this->custom_db->single_table_records('user','*',array('user_type'=>SUB_AGENT,'domain_list_fk'=>get_domain_auth_id()));

		$page_data['agent_details'] = magical_converter(array('k' => 'user_id', 'v' => 'first_name'), $agent_info);		
		
		$this->template->view('report/b2b2b_sightseeing', $page_data);
	}
	/*B2B Transfer Report*/
	function b2b_transfers_reportold($offset=0){
		$current_user_id = $GLOBALS['CI']->entity_user_id;
		$get_data = $this->input->get();
		$condition = array();
		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];

		$total_records = $this->transferv1_model->b2b_transferv1_report($condition, true);
		//echo '<pre>'; print_r($page_data); die;
		$table_data = $this->transferv1_model->b2b_transferv1_report($condition, false, $offset, RECORDS_RANGE_2);
		$table_data = $this->booking_data_formatter->format_transferv1_booking_data($table_data, $this->current_module);
		// debug($table_data);
		// exit;
		$page_data['table_data'] = $table_data['data'];
		
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2b_transfers_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
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
	function b2b2b_transfers_report($offset=0){
		$current_user_id = $GLOBALS['CI']->entity_user_id;
		$get_data = $this->input->get();
		$condition = array();
		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];

		$total_records = $this->transferv1_model->b2b2b_transferv1_report($condition, true);
		//echo '<pre>'; print_r($page_data); die;
		$table_data = $this->transferv1_model->b2b2b_transferv1_report($condition, false, $offset, RECORDS_RANGE_2);
		$table_data = $this->booking_data_formatter->format_transferv1_booking_data($table_data, $this->current_module);
		// debug($table_data);
		// exit;
		$page_data['table_data'] = $table_data['data'];
		
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2b2b_transfers_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');

		$user_cond = [];
		$user_cond [] = array('U.user_type','=',' (', B2B_USER, ')');
		$user_cond [] = array('U.domain_list_fk' , '=' ,get_domain_auth_id());

		//$agent_info['data'] = $this->user_model->b2b_user_list($user_cond,false);

		$agent_info = $this->custom_db->single_table_records('user','*',array('user_type'=>SUB_AGENT,'domain_list_fk'=>get_domain_auth_id()));

		$page_data['agent_details'] = magical_converter(array('k' => 'user_id', 'v' => 'first_name'), $agent_info);		
		
		$this->template->view('report/b2b2b_transfer', $page_data);
	}
	/*B2c Transfer Report*/
	
		function cance_book($app_reference,$url)
	{
	    $this->load->model('package_model');
		$status = ['status'=>'CANCELLED'];
		$where=['app_reference'=>$app_reference]; 
		$data = $this->package_model->activity_cancelation('package_booking_transaction_details', $status,  $where);
		$data = $this->package_model->activity_book_cancelation('package_booking_details', $status,  $where);
		redirect(base64_decode($url));
	}
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
		$table_data = $this->transferv1_model->b2c_transferv1_report($condition, false, $offset, RECORDS_RANGE_2);
			//debug($table_data['data']); exit;
		$table_data = $this->booking_data_formatter->format_transferv1_booking_data($table_data,$this->current_module);
		$page_data['table_data'] = $table_data['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2c_transfers_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
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
   
        $table_data = $this->car_model->b2c_car_report($condition, false, $offset, RECORDS_RANGE_2);
       
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
        $config['per_page'] = RECORDS_RANGE_2;
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
   
        $table_data = $this->car_model->b2b_car_report($condition, false, $offset, RECORDS_RANGE_2);
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
        $config['per_page'] = RECORDS_RANGE_2;
        $this->pagination->initialize($config);
        /** TABLE PAGINATION */
        $page_data['total_records'] = $config['total_rows'];
        $page_data['customer_email'] = $this->entity_email;
       $page_data['search_params'] = $get_data;
        $page_data['status_options'] = get_enum_list('booking_status_options');
        $this->template->view('report/b2c_car_report', $page_data);
        

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
		$table_data = $this->flight_model->booking($condition, false, $offset, RECORDS_RANGE_2);
		$table_data = $this->booking_data_formatter->format_flight_booking_data($table_data,$this->current_module);
		$page_data['table_data'] = $table_data['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/flight/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');
		$this->template->view('report/airline', $page_data);
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
		
		$table_data = $this->flight_model->b2c_flight_report($condition, false, $offset, RECORDS_RANGE_2);
		$table_data = $this->booking_data_formatter->format_flight_booking_data($table_data, 'b2c', false);
		
		//Export report


		$page_data['table_data'] = $table_data['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2c_flight_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');
		$this->template->view('report/b2c_report_airline', $page_data);
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
		$table_data = $this->flight_model->b2b_flight_report($condition, false, $offset, RECORDS_RANGE_2);
		$table_data = $this->booking_data_formatter->format_flight_booking_data($table_data, $this->current_module);
		$page_data['table_data'] = $table_data['data'];
		
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2b_flight_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
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
		// debug($page_data);die;	
		
		$this->template->view('report/b2b_report_airline', $page_data);
	}

	function b2b2b_flight_report($offset=0)
	{
		$current_user_id = $GLOBALS['CI']->entity_user_id;
		$get_data = $this->input->get();
		$condition = array();
		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];

		$total_records = $this->flight_model->b2b2b_flight_report($condition, true);
		//echo '<pre>'; print_r($page_data); die;
		$table_data = $this->flight_model->b2b2b_flight_report($condition, false, $offset, RECORDS_RANGE_2);
		$table_data = $this->booking_data_formatter->format_flight_booking_data($table_data, $this->current_module);
		$page_data['table_data'] = $table_data['data'];
		
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2b2b_flight_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');

		$user_cond = [];
		$user_cond [] = array('U.user_type','=',' (', SUB_AGENT, ')');
		$user_cond [] = array('U.domain_list_fk' , '=' ,get_domain_auth_id());

		//$agent_info['data'] = $this->user_model->b2b_user_list($user_cond,false);

		$agent_info = $this->custom_db->single_table_records('user','*',array('user_type'=>SUB_AGENT,'domain_list_fk'=>get_domain_auth_id()));

		// debug($agent_info);die;

		$page_data['agent_details'] = magical_converter(array('k' => 'user_id', 'v' => 'first_name'), $agent_info);
		
		$this->template->view('report/b2b2b_report_airline', $page_data);
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
			if (empty($get_data['sub_agent_id']) == false && strtolower($get_data['sub_agent_id'])!='all') {
				$filter_condition[] = array('BD.sub_agent_id', '=', $this->db->escape($get_data['sub_agent_id']));
			}
	
			if (empty($get_data['status']) == false && strtolower($get_data['status']) != 'all') {
				$filter_condition[] = array('BD.status', '=', $this->db->escape($get_data['status']));
			}

			if (empty($get_data['type']) == false && strtolower($get_data['type']) != 'all') {
				$filter_condition[] = array('U.user_type', '=', $this->db->escape($get_data['type']));
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

	function b2c_holiday_report($offset=0)
	{
		// error_reporting(E_ALL);
		$condition = array();
		$get_data = $this->input->get();

		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];

		$this->load->model('tours_model');
		$total_records = $this->tours_model->booking($condition, true);	
		// debug($total_records); die;
		$table_data = $this->tours_model->booking($condition, false, $offset, RECORDS_RANGE_2);
			// debug($table_data); exit;
		
		$page_data['table_data'] = $table_data;
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2c_holiday_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		//debug($page_data);exit;
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');
		// debug($page_data);
		// die;
		$this->template->view('report/b2c_report_holiday', $page_data);
	}



	public function b2b_holiday($module_type='b2c',$offset = 0)
	{
		$condition = array ();
		$get_data = $this->input->get ();
		
		if (! (isset ( $get_data ['created_datetime_from'] ) || isset ( $get_data ['created_datetime_to'] ))) {
			$get_data ['created_datetime_from'] = date ( 'd-m-Y' );
			$get_data ['created_datetime_to'] = date ( 'd-m-Y' );
		}		
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
		$table_data = $this->tours_model->b2b_booking ( $condition, false, $offset, RECORDS_RANGE_5 );
		$page_data ['table_data'] = $table_data ['data'];
		$x = count ( $table_data );
		$this->load->library ( 'pagination' );
		if (count ( $_GET ) > 0)
			$config ['suffix'] = '?' . http_build_query ( $_GET, '', "&" );
		$config ['base_url'] = base_url () . 'index.php/report/b2b_holiday/';
		$config ['first_url'] = $config ['base_url'] . '?' . http_build_query ( $_GET );
		$page_data ['total_rows'] = $config ['total_rows'] = $total_records;
		$config ['per_page'] = RECORDS_RANGE_5;
		$this->pagination->initialize ( $config );
		/**
		 * TABLE PAGINATION
		 */
		$page_data ['total_records'] = $config ['total_rows'];
		$page_data ['search_params'] = $get_data;
		$page_data ['status_options'] = get_enum_list ( 'booking_status_options' );
		$page_data['active_column_list'] = $this->custom_db->single_table_records ( 'report_column_setting','column_name',array('module_name'=>'holiday','module_type'=>$module_type) )['data'];		
		$page_data['module_type'] =$module_type;
		//debug($page_data['module_type']);die;
		$page_data['user_module_type']=array('module' => 'B2B');
		$page_data['user_type']=3;
		$this->template->view ( 'report/holiday', $page_data );	
	}

	

	function b2b_holiday_old($offset=0)
	{
		// error_reporting(E_ALL);
		$condition = array();
		$get_data = $this->input->get();

		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];

		$this->load->model('tours_model');

		// debug($get_data);
		// die;
		$total_records = $this->tours_model->booking($condition, true);	
		// debug($total_records); die;
		$table_data = $this->tours_model->booking($condition, false, $offset, RECORDS_RANGE_2);
			// debug($table_data); exit;
		
		$page_data['table_data'] = $table_data;
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2b_holiday/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		//debug($page_data);exit;
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');
		// debug($page_data);
		// die;
		$this->template->view('report/b2b_report_holiday', $page_data);
	}

     function sub_corporate_flight_report($offset=0)
	{
		//error_reporting(E_ALL);
		$current_user_id = $GLOBALS['CI']->entity_user_id;
		$get_data = $this->input->get();
		$condition = array();
		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];

		$total_records = $this->flight_model->sub_corporate_flight_report($condition, true);
		//echo '<pre>'; print_r($page_data); die;
		$table_data = $this->flight_model->sub_corporate_flight_report($condition, false, $offset, RECORDS_RANGE_2);
		//echo $this->db->last_query();die;
		$table_data = $this->booking_data_formatter->format_flight_booking_data($table_data, $this->current_module);
		$page_data['table_data'] = $table_data['data'];
		
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/sub_corporate_flight_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');

		$user_cond = [];
		$user_cond [] = array('U.user_type','=',' (', SUB_CORPORATE, ')');
		$user_cond [] = array('U.domain_list_fk' , '=' ,get_domain_auth_id());

		//$agent_info['data'] = $this->user_model->b2b_user_list($user_cond,false);

		$agent_info = $this->custom_db->single_table_records('user','*',array('user_type'=>SUB_CORPORATE,'domain_list_fk'=>get_domain_auth_id()));

		$page_data['agent_details'] = magical_converter(array('k' => 'user_id', 'v' => 'agency_name'), $agent_info);		
		

		// debug($page_data);
		// die;
		$this->template->view('report/corporate_report_airline', $page_data);
	}


	function sub_corporate_hotel_report($offset=0)
	{
		$condition = array();
		$get_data = $this->input->get();

		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];
		
		$total_records = $this->hotel_model->sub_corporate_hotel_report($condition, true);
		$table_data = $this->hotel_model->sub_corporate_hotel_report($condition, false, $offset, RECORDS_RANGE_2);
		$table_data = $this->booking_data_formatter->format_hotel_booking_data($table_data, $this->current_module);
		$page_data['table_data'] = $table_data['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/sub_corporate_hotel_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		//debug($page_data);exit;
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');
		
		$agent_info = $this->custom_db->single_table_records('user','*',array('user_type'=>SUB_CORPORATE,'domain_list_fk'=>get_domain_auth_id()));
		
		$page_data['agent_details'] = magical_converter(array('k' => 'user_id', 'v' => 'agency_name'), $agent_info);
		$this->template->view('report/corporate_report_hotel', $page_data);
	}

	function sub_corporate_bus_report($offset=0)
	{
		// error_reporting(E_ALL);
		//echo "hi";exit();
		$get_data = $this->input->get();
		$condition = array();
		$page_data = array();

		$filter_data = $this->format_basic_search_filters('bus');
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];
		if ($this->check_operation($offset)) {
            $op_data = $this->bus_model->sub_corporate_bus_report($condition);
         //debug( $op_data );exit();
            $x = $op_data['data']['booking_details'];
            foreach ($op_data['data']['booking_itinerary_details'] as $key => $value) {
             $val=json_decode($op_data['data']['booking_itinerary_details'][$key]['attributes'],true);
             
             $x[$key]['api_total_basic_fare']=$val['api_total_basic_fare'];
			 $x[$key]['api_total_tax']=$val['api_total_tax'];
			 $x[$key]['admin_markup']=$val['admin_markup'];
			 $x[$key]['tds']=$val['tds'];
			 $x[$key]['service_tax']=$val['service_tax'];
			 $x[$key]['agent_buying_price']=$val['agent_buying_price'];	
			 $x[$key]['commission']=$val['commission'];
           	 $x[$key]['operator']=$value['operator'];
           	 $x[$key]['journey_datetime']=$value['journey_datetime'];
           	 $x[$key]['departure_from']=$value['departure_from'];
           	 $x[$key]['arrival_to']=$value['arrival_to'];
           	 $x[$key]['bus_type']=$value['bus_type'];
            }
           // debug($val);exit();
            $app_references=[];
            $i=0;

            foreach ($op_data['data']['booking_customer_details'] as $akey => $value) {
            	//debug($x);
            	//debug($op_data['data']['booking_customer_details']);exit;
            		$a_ref=$value['app_reference'];
            		if($app_references[$a_ref]!='true')
            		{

                     $x[$i]['name']=$op_data['data']['booking_customer_details'][$akey]['name'];
            		 $app_references[$a_ref]='true';
            		$i++;
            		}	  

                }
          
                // debug($x);exit;
       
            foreach ($x as $k => $v) {
				//$get_count= $this->custom_db->single_table_records ( 'flight_booking_passenger_details','count(*)',array('app_reference'=> $v['app_reference']));
				//$passenger_count = $get_count['data'][0]['count(*)'];
				//$profit = (($v['conv_fee'] + $v['admin_markup'] + $v['net_commission'])-$v['net_commission_tds']);
				//coomison+convfee-tds
				//$profit = ($v['grand_total']-$v['agent_buying_price']);
				$x[$k]['corporate_profit'] = $profit;
				$x[$k]['pnr'] =$v['pnr'];
				$x[$k]['phone_number']=$v['phone_number'];
				if($v['payment_mode']==PNHB1)
				{
					$pmode = 'Online';
				}
				else if($v['payment_mode']==PABHB3){
					$pmode = 'Wallet';
				}
				else {
				   $pmode = $v['payment_mode'];
				}
				$x[$k]['pmode'] = $pmode;
				$x[$k]['pcount'] = $passenger_count;
			}
$col = array(
					
				'app_reference' => 'Application Reference',
                'operator' => 'Operator',
                'phone_number' => 'Phone',
                'agency_name' => 'Agency',
                'status' => 'Status',
                'pnr' => 'PNR',
                'name' => 'Passenger Name',
                'journey_datetime' => 'Travel Date',
                'api_total_basic_fare' => 'Base Fare',
                'api_total_tax' => 'Tax',
                'admin_markup' => 'Markup',
                //'ag_markup' => 'Agent Markup',
                'commission' => 'Agent Commission',
                'tds' => 'Agent TDS',
                //'AgentNetRate' => 'Agent Rate',
                'service_tax' => 'Service Tax',
                'agent_buying_price' => 'Total',
                'created_datetime' => 'BookedOn',
                'loc' => array(
                    'title' => 'Trip Location',
                    'cols' => array(
                        'departure_from',
                        'arrival_to'
                    ),
                    'sep' => '-'
                ),
                'bus_type' => 'Bus Type'
			);
//debug($col);exit;
            $this->perform_operation($offset, $x, $col, 'Bus Booking report');
        }
        $offset = intval($offset);
        $this->load->library('pagination');

		//debug($condition); die;
		$total_records = $this->bus_model->sub_corporate_bus_report($condition, true);
		$table_data = $this->bus_model->sub_corporate_bus_report($condition, false, $offset, RECORDS_RANGE_2);
		// debug($table_data);exit;
		$table_data = $this->booking_data_formatter->format_bus_booking_data($table_data,'corporate');
		$page_data['table_data'] = $table_data['data'];
		/** TABLE PAGINATION */
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/sub_corporate_bus_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$page_data['customer_email'] = $this->entity_email;
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');

		$agent_info = $this->custom_db->single_table_records('user','*',array('user_type'=>SUB_CORPORATE,'domain_list_fk'=>get_domain_auth_id()));
		
		$page_data['agent_details'] = magical_converter(array('k' => 'user_id', 'v' => 'agency_name'), $agent_info);
        // debug($page_data);exit;
		$this->template->view('report/corporate_report_bus', $page_data);
	}

	function sub_corporate_transfers_report($offset=0,$module_type)
	{
		// error_reporting(E_ALL);
		$this->load->model('transferv1_model');
		$get_data = $this->input->get();
		$page_data = array();
		$condition = array();
		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];

		if(isset($get_data['branch_name']) && isset($get_data['manager_name']) && isset($get_data['executive_name']))
			{

				$total_records = $this->transferv1_model->filter_ex_booking_report($search_filter_condition, true,0,100000000000,$get_data['branch_name'],$get_data['manager_name'],$get_data['executive_name']);
				$table_data = $this->transferv1_model->filter_ex_booking_report($search_filter_condition,false,0,100000000000,$get_data['branch_name'],$get_data['manager_name'],$get_data['executive_name']);
			}
			else if(isset($get_data['app_reference']))  {
				$filter_report_data = trim($get_data['app_reference']);
				//debug($filter_report_data);exit();
				$search_filter_condition = '(TD.app_reference like "%'.$filter_report_data.'%" OR TD.pnr like "%'.$filter_report_data.'%")';
				$total_records = $this->transferv1_model->filter_ex_booking_report($search_filter_condition, true);
				$table_data = $this->transferv1_model->filter_ex_booking_report($search_filter_condition);
			
			}else if(isset($get_data['branch_name']) && isset($get_data['manager_name']) )
			{
				$total_records = $this->transferv1_model->filter_ex_booking_report($search_filter_condition, true,0,100000000000,$get_data['branch_name'],$get_data['manager_name']);
				$table_data = $this->transferv1_model->filter_ex_booking_report($search_filter_condition,false,0,100000000000,$get_data['branch_name'],$get_data['manager_name']);
			}else if(isset($get_data['branch_name']))
			{
				$total_records = $this->transferv1_model->filter_ex_booking_report($search_filter_condition, true,0,100000000000,$get_data['branch_name']);
				$table_data = $this->transferv1_model->filter_ex_booking_report($search_filter_condition,false,0,100000000000,$get_data['branch_name']);
			}else if((isset($get_data['filter_report_data']) == true && empty($get_data['filter_report_data']) == false))  {
				$filter_report_data = trim($get_data['filter_report_data']);
				//debug($filter_report_data);exit();
				$search_filter_condition = '(TD.app_reference like "%'.$filter_report_data.'%" OR TD.pnr like "%'.$filter_report_data.'%")';
				$total_records = $this->transferv1_model->filter_ex_booking_report($search_filter_condition, true);
				$table_data = $this->transferv1_model->filter_ex_booking_report($search_filter_condition);
			
			}  else {
				$total_records = $this->transferv1_model->booking($condition, true,0,RECORDS_RANGE_2,$module_type);
				// echo $this->db->last_query();die;
				$table_data = $this->transferv1_model->booking($condition, false,0,RECORDS_RANGE_2,$module_type);
				// echo $this->db->last_query();die;
			}
			
			$table_data = $this->booking_data_formatter->format_transferv1_booking_data($table_data, 'corporate');
			
			foreach ($table_data['data']['booking_details'] as $bkey => $bvalue){
					$approval_status=$this->transferv1_model->get_approval_status($bvalue['app_reference']);
					// if($bvalue['app_reference']=='FB24-164406-143832')
					// {
					// 	// debug($approval_status);exit;
					// }
					$table_data['data']['booking_details'][$bkey]['approval_status']=$approval_status;
				}
			$user_id=$this->entity_user_id;
			$branch_record = $this->custom_db->get_tbranch_list($user_id,MANAGER);
			// echo $this->db->last_query();die;
			$page_data['branch_name'] =$branch_record;
			// debug($page_data['branch_name']);exit;
			$page_data['table_data'] = $table_data['data'];
			$this->load->library('pagination');
			if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
			$config['base_url'] = base_url().'index.php/report/executive_transferv1/';
			$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
			$page_data['total_rows'] = $config['total_rows'] = $total_records;
			$config['per_page'] = RECORDS_RANGE_2;
			$this->pagination->initialize($config);
			/** TABLE PAGINATION */
			$page_data['total_records'] = $config['total_rows'];

			// debug($page_data);
			// die;
	        $this->template->view('report/corporate_transferv1', $page_data);
	}


	function sub_corporate_sightseeing_report($offset=0)
	{
		$current_user_id = $GLOBALS['CI']->entity_user_id;
		$get_data = $this->input->get();
		$condition = array();
		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];

		$total_records = $this->sightseeing_model->sub_corporate_sightseeing_report($condition, true);
		//echo '<pre>'; print_r($page_data); die;
		$table_data = $this->sightseeing_model->sub_corporate_sightseeing_report($condition, false, $offset, RECORDS_RANGE_2);
		$table_data = $this->booking_data_formatter->format_sightseeing_booking_data($table_data, $this->current_module);
		// debug($table_data);
		// exit;
		$page_data['table_data'] = $table_data['data'];
		
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/sub_corporate_sightseeing_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');

		$user_cond = [];
		$user_cond [] = array('U.user_type','=',' (', SUB_CORPORATE, ')');
		$user_cond [] = array('U.domain_list_fk' , '=' ,get_domain_auth_id());

		//$agent_info['data'] = $this->user_model->b2b_user_list($user_cond,false);

		$agent_info = $this->custom_db->single_table_records('user','*',array('user_type'=>SUB_CORPORATE,'domain_list_fk'=>get_domain_auth_id()));

		$page_data['agent_details'] = magical_converter(array('k' => 'user_id', 'v' => 'agency_name'), $agent_info);		
		
		$this->template->view('report/corporate_report_sightseeing', $page_data);
	}


	/**
	   Jebeen M
	 * Flight Report for corporate flight
	 * @param $offset
	 */
	function corporate_flight_report($offset=0)
	{

		$current_user_id = $GLOBALS['CI']->entity_user_id;
		$get_data = $this->input->get();
		$condition = array();
		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];

		$total_records = $this->flight_model->corporate_flight_report($condition, true);
		//echo '<pre>'; print_r($page_data); die;
		$table_data = $this->flight_model->corporate_flight_report($condition, false, $offset, RECORDS_RANGE_2);
		//echo $this->db->last_query();die;
		$table_data = $this->booking_data_formatter->format_flight_booking_data($table_data, $this->current_module);
		$page_data['table_data'] = $table_data['data'];
		
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/corporate_flight_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');

		$user_cond = [];
		$user_cond [] = array('U.user_type','=',' (', CORPORATE_USER, ')');
		$user_cond [] = array('U.domain_list_fk' , '=' ,get_domain_auth_id());

		//$agent_info['data'] = $this->user_model->b2b_user_list($user_cond,false);

		$agent_info = $this->custom_db->single_table_records('user','*',array('user_type'=>CORPORATE_USER,'domain_list_fk'=>get_domain_auth_id()));

		$page_data['agent_details'] = magical_converter(array('k' => 'user_id', 'v' => 'agency_name'), $agent_info);		
		

		// debug($page_data);
		// die;
		$this->template->view('report/corporate_report_airline', $page_data);
	}

/**
	   Jebeen M
	 * Flight Report for corporate hotel
	 * @param $offset
	 */

	function corporate_hotel_report($offset=0)
	{
		$condition = array();
		$get_data = $this->input->get();

		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];
		
		$total_records = $this->hotel_model->corporate_hotel_report($condition, true);
		$table_data = $this->hotel_model->corporate_hotel_report($condition, false, $offset, RECORDS_RANGE_2);
		$table_data = $this->booking_data_formatter->format_hotel_booking_data($table_data, $this->current_module);
		$page_data['table_data'] = $table_data['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/corporate_hotel_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		//debug($page_data);exit;
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');
		
		$agent_info = $this->custom_db->single_table_records('user','*',array('user_type'=>CORPORATE_USER,'domain_list_fk'=>get_domain_auth_id()));
		
		$page_data['agent_details'] = magical_converter(array('k' => 'user_id', 'v' => 'agency_name'), $agent_info);
		$this->template->view('report/corporate_report_hotel', $page_data);
	}


/**
	   Jebeen M
	 * Flight Report for corporate transfers
	 * @param $offset
	 */

	// function corporate_transfers_report($offset=0,$module_type)
	// {
	// 	// error_reporting(E_ALL);
	// 	$this->load->model('transferv1_model');
	// 	$get_data = $this->input->get();
	// 	$page_data = array();
	// 	$condition = array();
	// 	$filter_data = $this->format_basic_search_filters();
	// 	$page_data['from_date'] = $filter_data['from_date'];
	// 	$page_data['to_date'] = $filter_data['to_date'];
	// 	$condition = $filter_data['filter_condition'];

	// 	if(isset($get_data['branch_name']) && isset($get_data['manager_name']) && isset($get_data['executive_name']))
	// 		{

	// 			$total_records = $this->transferv1_model->filter_ex_booking_report($search_filter_condition, true,0,100000000000,$get_data['branch_name'],$get_data['manager_name'],$get_data['executive_name']);
	// 			$table_data = $this->transferv1_model->filter_ex_booking_report($search_filter_condition,false,0,100000000000,$get_data['branch_name'],$get_data['manager_name'],$get_data['executive_name']);
	// 		}
	// 		else if(isset($get_data['app_reference']))  {
	// 			$filter_report_data = trim($get_data['app_reference']);
	// 			//debug($filter_report_data);exit();
	// 			$search_filter_condition = '(TD.app_reference like "%'.$filter_report_data.'%" OR TD.pnr like "%'.$filter_report_data.'%")';
	// 			$total_records = $this->transferv1_model->filter_ex_booking_report($search_filter_condition, true);
	// 			$table_data = $this->transferv1_model->filter_ex_booking_report($search_filter_condition);
			
	// 		}else if(isset($get_data['branch_name']) && isset($get_data['manager_name']) )
	// 		{
	// 			$total_records = $this->transferv1_model->filter_ex_booking_report($search_filter_condition, true,0,100000000000,$get_data['branch_name'],$get_data['manager_name']);
	// 			$table_data = $this->transferv1_model->filter_ex_booking_report($search_filter_condition,false,0,100000000000,$get_data['branch_name'],$get_data['manager_name']);
	// 		}else if(isset($get_data['branch_name']))
	// 		{
	// 			$total_records = $this->transferv1_model->filter_ex_booking_report($search_filter_condition, true,0,100000000000,$get_data['branch_name']);
	// 			$table_data = $this->transferv1_model->filter_ex_booking_report($search_filter_condition,false,0,100000000000,$get_data['branch_name']);
	// 		}else if((isset($get_data['filter_report_data']) == true && empty($get_data['filter_report_data']) == false))  {
	// 			$filter_report_data = trim($get_data['filter_report_data']);
	// 			//debug($filter_report_data);exit();
	// 			$search_filter_condition = '(TD.app_reference like "%'.$filter_report_data.'%" OR TD.pnr like "%'.$filter_report_data.'%")';
	// 			$total_records = $this->transferv1_model->filter_ex_booking_report($search_filter_condition, true);
	// 			$table_data = $this->transferv1_model->filter_ex_booking_report($search_filter_condition);
			
	// 		}  else {
	// 			$total_records = $this->transferv1_model->booking($condition, true,0,RECORDS_RANGE_2,$module_type);
	// 			// echo $this->db->last_query();die;
	// 			$table_data = $this->transferv1_model->booking($condition, false,0,RECORDS_RANGE_2,$module_type);
	// 			// echo $this->db->last_query();die;
	// 		}
			
	// 		$table_data = $this->booking_data_formatter->format_transferv1_booking_data($table_data, 'corporate');
			
	// 		foreach ($table_data['data']['booking_details'] as $bkey => $bvalue){
	// 				$approval_status=$this->transferv1_model->get_approval_status($bvalue['app_reference']);
	// 				// if($bvalue['app_reference']=='FB24-164406-143832')
	// 				// {
	// 				// 	// debug($approval_status);exit;
	// 				// }
	// 				$table_data['data']['booking_details'][$bkey]['approval_status']=$approval_status;
	// 			}
	// 		$user_id=$this->entity_user_id;
	// 		$branch_record = $this->custom_db->get_tbranch_list($user_id,MANAGER);
	// 		// echo $this->db->last_query();die;
	// 		$page_data['branch_name'] =$branch_record;
	// 		// debug($page_data['branch_name']);exit;
	// 		$page_data['table_data'] = $table_data['data'];
	// 		$this->load->library('pagination');
	// 		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
	// 		$config['base_url'] = base_url().'index.php/report/executive_transferv1/';
	// 		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
	// 		$page_data['total_rows'] = $config['total_rows'] = $total_records;
	// 		$config['per_page'] = RECORDS_RANGE_2;
	// 		$this->pagination->initialize($config);
	// 		/** TABLE PAGINATION */
	// 		$page_data['total_records'] = $config['total_rows'];

	// 		// debug($page_data);
	// 		// die;
	//         $this->template->view('report/corporate_transferv1', $page_data);
	// }

	function corporate_transfers_report($offset=0)
	{
		$current_user_id = $GLOBALS['CI']->entity_user_id;
		$get_data = $this->input->get();
		$condition = array();
		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];

		$total_records = $this->transferv1_model->corporate_transfers_report($condition, true);
		//echo '<pre>'; print_r($page_data); die;
		$table_data = $this->transferv1_model->corporate_transfers_report($condition, false, $offset, RECORDS_RANGE_2);
		// echo $this->db->last_query();die;
		$table_data = $this->booking_data_formatter->format_transferv1_booking_data($table_data, $this->current_module);
		// debug($table_data);
		// exit;
		$page_data['table_data'] = $table_data['data'];
		
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/corporate_transfers_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');

		$user_cond = [];
		$user_cond [] = array('U.user_type','=',' (', CORPORATE_USER, ')');
		$user_cond [] = array('U.domain_list_fk' , '=' ,get_domain_auth_id());

		//$agent_info['data'] = $this->user_model->b2b_user_list($user_cond,false);

		$agent_info = $this->custom_db->single_table_records('user','*',array('user_type'=>CORPORATE_USER,'domain_list_fk'=>get_domain_auth_id()));

		$page_data['agent_details'] = magical_converter(array('k' => 'user_id', 'v' => 'agency_name'), $agent_info);		
		
		$this->template->view('report/corporate_transferv1', $page_data);
	}



	function corporate_sightseeing_report($offset=0)
	{
		$current_user_id = $GLOBALS['CI']->entity_user_id;
		$get_data = $this->input->get();
		$condition = array();
		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];

		$total_records = $this->sightseeing_model->corporate_sightseeing_report($condition, true);
		//echo '<pre>'; print_r($page_data); die;
		$table_data = $this->sightseeing_model->corporate_sightseeing_report($condition, false, $offset, RECORDS_RANGE_2);
		$table_data = $this->booking_data_formatter->format_sightseeing_booking_data($table_data, $this->current_module);
		// debug($table_data);
		// exit;
		$page_data['table_data'] = $table_data['data'];
		
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/corporate_sightseeing_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');

		$user_cond = [];
		$user_cond [] = array('U.user_type','=',' (', CORPORATE_USER, ')');
		$user_cond [] = array('U.domain_list_fk' , '=' ,get_domain_auth_id());

		//$agent_info['data'] = $this->user_model->b2b_user_list($user_cond,false);

		$agent_info = $this->custom_db->single_table_records('user','*',array('user_type'=>CORPORATE_USER,'domain_list_fk'=>get_domain_auth_id()));

		$page_data['agent_details'] = magical_converter(array('k' => 'user_id', 'v' => 'agency_name'), $agent_info);		
		
		$this->template->view('report/corporate_report_sightseeing', $page_data);
	}

	function corporate_bus_report($offset=0)
	{
		$get_data = $this->input->get();
		$condition = array();
		$page_data = array();

		$filter_data = $this->format_basic_search_filters('bus');
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];
		
		if ($this->check_operation($offset)) {
            $op_data = $this->bus_model->corporate_bus_report($condition);

            $x = $op_data['data']['booking_details'];
            foreach ($op_data['data']['booking_itinerary_details'] as $key => $value) {
             $val=json_decode($op_data['data']['booking_itinerary_details'][$key]['attributes'],true);
             
             $x[$key]['api_total_basic_fare']=$val['api_total_basic_fare'];
			 $x[$key]['api_total_tax']=$val['api_total_tax'];
			 $x[$key]['admin_markup']=$val['admin_markup'];
			 $x[$key]['tds']=$val['tds'];
			 $x[$key]['service_tax']=$val['service_tax'];
			 $x[$key]['agent_buying_price']=$val['agent_buying_price'];	
			 $x[$key]['commission']=$val['commission'];
           	 $x[$key]['operator']=$value['operator'];
           	 $x[$key]['journey_datetime']=$value['journey_datetime'];
           	 $x[$key]['departure_from']=$value['departure_from'];
           	 $x[$key]['arrival_to']=$value['arrival_to'];
           	 $x[$key]['bus_type']=$value['bus_type'];
            }
           // debug($val);exit();
            $app_references=[];
            $i=0;

            foreach ($op_data['data']['booking_customer_details'] as $akey => $value) {
            	//debug($x);
            	//debug($op_data['data']['booking_customer_details']);exit;
            		$a_ref=$value['app_reference'];
            		if($app_references[$a_ref]!='true')
            		{

                     $x[$i]['name']=$op_data['data']['booking_customer_details'][$akey]['name'];
            		 $app_references[$a_ref]='true';
            		$i++;
            		}	  

                }
          
                // debug($x);exit;
       
            foreach ($x as $k => $v) {
				//$get_count= $this->custom_db->single_table_records ( 'flight_booking_passenger_details','count(*)',array('app_reference'=> $v['app_reference']));
				//$passenger_count = $get_count['data'][0]['count(*)'];
				//$profit = (($v['conv_fee'] + $v['admin_markup'] + $v['net_commission'])-$v['net_commission_tds']);
				//coomison+convfee-tds
				//$profit = ($v['grand_total']-$v['agent_buying_price']);
				$x[$k]['corporate_profit'] = $profit;
				$x[$k]['pnr'] =$v['pnr'];
				$x[$k]['phone_number']=$v['phone_number'];
				if($v['payment_mode']==PNHB1)
				{
					$pmode = 'Online';
				}
				else if($v['payment_mode']==PABHB3){
					$pmode = 'Wallet';
				}
				else {
				   $pmode = $v['payment_mode'];
				}
				$x[$k]['pmode'] = $pmode;
				$x[$k]['pcount'] = $passenger_count;
			}
			$col = array(
					
				'app_reference' => 'Application Reference',
                'operator' => 'Operator',
                'phone_number' => 'Phone',
                'agency_name' => 'Agency',
                'status' => 'Status',
                'pnr' => 'PNR',
                'name' => 'Passenger Name',
                'journey_datetime' => 'Travel Date',
                'api_total_basic_fare' => 'Base Fare',
                'api_total_tax' => 'Tax',
                'admin_markup' => 'Markup',
                //'ag_markup' => 'Agent Markup',
                'commission' => 'Agent Commission',
                'tds' => 'Agent TDS',
                //'AgentNetRate' => 'Agent Rate',
                'service_tax' => 'Service Tax',
                'agent_buying_price' => 'Total',
                'created_datetime' => 'BookedOn',
                'loc' => array(
                    'title' => 'Trip Location',
                    'cols' => array(
                        'departure_from',
                        'arrival_to'
                    ),
                    'sep' => '-'
                ),
                'bus_type' => 'Bus Type'
			);
//debug($col);exit;
            $this->perform_operation($offset, $x, $col, 'Bus Booking report');
        }
        $offset = intval($offset);
        $this->load->library('pagination');


		$total_records = $this->bus_model->corporate_bus_report($condition, true);
		$table_data = $this->bus_model->corporate_bus_report($condition, false, $offset, RECORDS_RANGE_2);
		//debug($table_data);die;
		$table_data = $this->booking_data_formatter->format_bus_booking_data($table_data,'corporate');
		// debug($table_data);die;
		$page_data['table_data'] = $table_data['data'];
		/** TABLE PAGINATION */
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/corporate_bus_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$page_data['customer_email'] = $this->entity_email;
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');

		$agent_info = $this->custom_db->single_table_records('user','*',array('user_type'=>CORPORATE_USER,'domain_list_fk'=>get_domain_auth_id()));
		
		$page_data['agent_details'] = magical_converter(array('k' => 'user_id', 'v' => 'agency_name'), $agent_info);
        // debug($page_data);exit;
		$this->template->view('report/corporate_report_bus', $page_data);
	}

	private function check_operation($op) {
		return (strcmp($op, 'excel') == 0 || strcmp($op, 'pdf') == 0);
	}




	//Chala Coding

	private function format_basic_search_filters_crs($module='')
	{
		$get_data = $this->input->get();

		$filter_condition = array();
		$from_date = '';
		$to_date = '';
		$search_arr = array();
		
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
				$filter_condition[] = array('BH.book_date', '>=', $this->db->escape(db_current_datetime($from_date)));
			}
			if(empty($to_date) == false) {
				$filter_condition[] = array('BH.book_date', '<=', $this->db->escape(db_current_datetime($to_date)));
			}
	
			/*if (empty($get_data['created_by_id']) == false) {
				$filter_condition[] = array('BD.created_by_id', '=', $this->db->escape($get_data['created_by_id']));
			}*/
			
			if (empty($get_data['created_by_id']) == false && strtolower($get_data['created_by_id'])!='all') {
				$filter_condition[] = array('BG.user_id', '=', $this->db->escape($get_data['created_by_id']));
			}
	
			if (empty($get_data['status']) == false && strtolower($get_data['status']) != 'all') {
				$filter_condition[] = array('BG.booking_status', '=', $this->db->escape($get_data['status']));
			}
		
			/*if (empty($get_data['phone']) == false) {
				$filter_condition[] = array('BD.phone', ' like ', $this->db->escape('%'.$get_data['phone'].'%'));
			}
	
			if (empty($get_data['email']) == false) {
				$filter_condition[] = array('BD.email', ' like ', $this->db->escape('%'.$get_data['email'].'%'));
			}*/
			
			
				if (empty($get_data['pnr']) == false) {
					$filter_condition[] = array('BG.pnr_no', ' like ', $this->db->escape('%'.$get_data['pnr'].'%'));
				}
			
			
	
			if (empty($get_data['app_reference']) == false) {
				$filter_condition[] = array('BG.parent_pnr', ' like ', $this->db->escape('%'.$get_data['app_reference'].'%'));
			}
			
			$page_data['from_date'] = $from_date;
			$page_data['to_date'] = $to_date;

			//Today's Booking Data
			if(isset($get_data['today_booking_data']) == true && empty($get_data['today_booking_data']) == false) {
				$filter_condition[] = array('DATE(BH.book_date)', '=', '"'.date('Y-m-d').'"');
			}
			//Previous Booking Data: last 3 days, 7 days, 15 days, 1 month and 3 month
			if(isset($get_data['prev_booking_data']) == true && empty($get_data['prev_booking_data']) == false) {
				$filter_condition[] = array('DATE(BH.book_date)', '>=', '"'.trim($get_data['prev_booking_data']).'"');
			}
			
			
		}	
		$search_arr = array(
				'filter_condition' => $filter_condition,
				'from_date' => $from_date,
				'to_date' => $to_date
		);	
		return $search_arr;
	}






	function b2c_crs_hotel_report($offset=0)
	{
		echo $this->entity_user_id;exit();
		$condition = array();
		$get_data = $this->input->get();

		// print_r($get_data);
		// exit("2095-report");


		$filter_data = $this->format_basic_search_filters_crs();
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];
		// debug($condition);exit;
		$status_option_array = Array
        (
            'CONFIRM'=>"BOOKING_CONFIRMED",
            'PROCESS'=>"BOOKING_INPROGRESS" ,
            'HOLD'=>"BOOKING_HOLD",
            'CANCELLED'=>"BOOKING_CANCELLED",
            'PENDING'=>"BOOKING_PENDING",
            'FAILED'=>"BOOKING_FAILED" 
        );

		// debug($status_option_array);
		// debug($this->session->userdata('id'));die;
		$total_records = $this->hotels_model->b2c_crs_hotel_report($condition, true);	
	    // debug($total_records); die;
		$table_data = $this->hotels_model->b2c_crs_hotel_report($condition, false, $offset, RECORDS_RANGE_1);
		 //debug($table_data['data']); exit;
		
		$page_data['table_data'] = $table_data['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2c_crs_hotel_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_1;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		//debug($page_data);exit;
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = $status_option_array;

		$page_data['payment_currency']=$page_data['table_data']['booking_details'][0];
		//debug($page_data['payment_currency']);exit;

								 // $curr=json_decode($page_data['payment_currency'],1);
								 // debug($curr);die;
								  $page_data['payment_currency']=$page_data['payment_currency']['admin_currency'];
								  //debug($page_data['payment_currency']);die;
		
		$this->template->view('report/b2c_crs_hotel_report', $page_data);
	}

		function b2b_crs_hotel_report($offset=0)
	{
		$condition = array();
		$get_data = $this->input->get();

		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];
		$condition[] = array('BD.booking_source', '=', $this->db->escape(CRS_HOTEL_BOOKING_SOURCE));

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
		$table_data = $this->hotel_model->b2c_hotel_report($condition, false, $offset, RECORDS_RANGE_2);
			//debug($table_data['data']); exit;
		$table_data = $this->booking_data_formatter->format_hotel_booking_data($table_data,$this->current_module);
		$page_data['table_data'] = $table_data['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2c_hotel_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');
		$this->template->view('report/b2c_crs_hotel_report', $page_data);
	}




	function b2c_package_report()
	{
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
		
		//$condition[] = array('U.user_type', '=', B2C_USER, ' OR ', 'BD.created_by_id');

		$condition[] = array('BD.module_type', '=', '"activity"');


		// debug(RECORDS_RANGE_2);
		$total_records = $this->package_model->b2c_holiday_report($condition, true);		
		$table_data = $this->package_model->b2c_holiday_report($condition, false, $offset, RECORDS_RANGE_2);
		// debug($table_data);exit();
		$table_data = $this->booking_data_formatter->format_flight_booking_data($table_data, 'b2c', false);
		// debug($table_data); exit;


		$page_data['table_data'] = $table_data['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2c_package_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');
		$page_data['module_type']="Activity";
		// debug($page_data['table_data']); exit;
		$this->template->view('report/b2c_report_package', $page_data);
	}



	public function holiday($module_type='b2c',$offset = 0)
	{
		$condition = array ();
		$get_data = $this->input->get ();
		
		if (! (isset ( $get_data ['created_datetime_from'] ) || isset ( $get_data ['created_datetime_to'] ))) {
			$get_data ['created_datetime_from'] = date ( 'd-m-Y' );
			$get_data ['created_datetime_to'] = date ( 'd-m-Y' );
		}		
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
		$total_records = $this->tours_model->booking ( $condition, true );
		$table_data = $this->tours_model->booking ( $condition, false, $offset, RECORDS_RANGE_5 );


		// debug(RECORDS_RANGE_5);exit();
		$page_data ['table_data'] = $table_data ['data'];
		$x = count ( $table_data );
		$this->load->library ( 'pagination' );
		if (count ( $_GET ) > 0)
			$config ['suffix'] = '?' . http_build_query ( $_GET, '', "&" );
		$config ['base_url'] = base_url () . 'index.php/report/holiday/';
		$config ['first_url'] = $config ['base_url'] . '?' . http_build_query ( $_GET );
		$page_data ['total_rows'] = $config ['total_rows'] = $total_records;
		$config ['per_page'] = RECORDS_RANGE_5;
		$this->pagination->initialize ( $config );
		/**
		 * TABLE PAGINATION
		 */
		$page_data ['total_records'] = $config ['total_rows'];
		$page_data ['search_params'] = $get_data;
		$page_data ['status_options'] = get_enum_list ( 'booking_status_options' );
		$page_data['active_column_list'] = $this->custom_db->single_table_records ( 'report_column_setting','column_name',array('module_name'=>'holiday','module_type'=>$module_type) )['data'];		
		$page_data['module_type'] = $module_type;
		$page_data['user_type']=4;
		// debug($page_data);die;
		$this->template->view ( 'report/holiday', $page_data );	
	}




	function b2b_package_report()
	{	
		// error_reporting(0);
		$current_user_id = $GLOBALS['CI']->entity_user_id;
		$get_data = $this->input->get();
		$condition = array();
		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];
        $condition[] = array('BD.module_type', '=', '"activity"');


		// debug($condition);exit();
		$total_records = $this->package_model->b2b_package_report($condition, true);
		// echo '<pre>'; print_r($page_data); die;
		$table_data = $this->package_model->b2b_package_report($condition, false, $offset, RECORDS_RANGE_2);
		$table_data = $this->booking_data_formatter->format_flight_booking_data($table_data, $this->current_module);
		$page_data['table_data'] = $table_data['data'];
		// debug($page_data['table_data']); exit;
		
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2b_package_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
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
		//debug($page_data);exit();
		
		$this->template->view('report/b2c_report_package', $page_data);
	}


	function b2c_transfers_crs_report()
	{ 
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
		// debug(RECORDS_RANGE_2);
		$total_records = $this->package_model->b2c_holiday_report($condition, true);		
		// debug($total_records);exit();
		$table_data = $this->package_model->b2c_holiday_report($condition, false, $offset, RECORDS_RANGE_2);
		// debug($table_data);exit();
		$table_data = $this->booking_data_formatter->format_flight_booking_data($table_data, 'b2c', false);
		// debug($table_data); exit;


		$page_data['table_data'] = $table_data['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2c_package_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
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
		// debug(RECORDS_RANGE_2);
		$total_records = $this->package_model->b2b_transfer_crs_report($condition, true);		
		// debug($total_records);exit();
		$table_data = $this->package_model->b2b_transfer_crs_report($condition, false, $offset, RECORDS_RANGE_2);
		// debug($table_data);exit();
		$table_data = $this->booking_data_formatter->format_flight_booking_data($table_data, 'b2b', false);
		// debug($table_data); exit;


		$page_data['table_data'] = $table_data['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2b_transfer_crs_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');
		$page_data['module_type']="Transfer";
		// debug($page_data); exit;
		$this->template->view('report/b2c_report_package', $page_data);
	}


}


