<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Report extends CI_Controller

{

	public function __construct()

	{

		parent::__construct();

		$this->load->model('bus_model');

		$this->load->model('hotel_model');

		$this->load->model('flight_model');

		$this->load->model('car_model');

		$this->load->model('sightseeing_model');

		$this->load->model('transferv1_model');
		$this->load->model('transfers_model');
			$this->load->model('transfer_model');
		$this->load->model('activity_model');

		$this->load->model('tours_model');
		
		$this->load->model('package_model');

		$this->load->library('booking_data_formatter');

	}

	function index()

	{

		$this->flight($offset=0);

	}

	function bookings()

	{

		$this->template->view('report/bookings');

	}
	function transfers($offset=0){
	   
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
		//	debug($get_data);
			$page_data['package_details'] = $this->transfer_model->get_transfer_details($get_data['app_reference']);
			// debug(	$page_data['package_details']);exit;
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
		$config['base_url'] = base_url().'index.php/report/transfers/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		 //debug($page_data);exit;
		$this->template->view('report/transferv1', $page_data);
	}
	function activities($offset=0)
	{
		

error_reporting(0);
	//	echo '<h4>Under Working</h4>';
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
		$config['base_url'] = base_url().'index.php/report/activities/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');
		// debug($page_data['table_data']); exit;
		$this->template->view('report/sightseeing', $page_data);
	}
	/************************************** HOTEL REPORT STARTS ***********************************/

	/**

	 * Hotel Report

	 * @param $offset

	 */

	function hotels($offset=0)

	{

		validate_user_login();

		$condition = array();

		$total_records = $this->hotel_model->booking($condition, true);

		$table_data = $this->hotel_model->booking($condition, false, $offset, RECORDS_RANGE_2);

		$table_data = $this->booking_data_formatter->format_hotel_booking_data($table_data, 'b2c');

		$page_data['table_data'] = $table_data['data'];

		/** TABLE PAGINATION */

		$this->load->library('pagination');

		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");

		$config['base_url'] = base_url().'index.php/report/hotel/';

		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);

		$page_data['total_rows'] = $config['total_rows'] = $total_records;

		$config['per_page'] = RECORDS_RANGE_2;

		$this->pagination->initialize($config);

		/** TABLE PAGINATION */

		$page_data['total_records'] = $config['total_rows'];

		$page_data['customer_email'] = $this->entity_email;

		$this->template->view('report/hotel', $page_data);

	}
	function privatetransfers()
	{ 
		// echo "string";exit;

		// echo '<h4>Under Working</h4>';
		$current_user_id = $GLOBALS['CI']->entity_user_id;
		$get_data = $this->input->get();
		// debug($get_data); exit();
		//debug($get_data); die;
		$condition = array();

	//	$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];
		// $condition[] = array('U.user_type', '=', B2C_USER, ' OR ', 'BD.created_by_id');
		$condition[] = array('BD.module_type', '=', '"transfers"');

		// debug($condition);exit();
		// debug(RECORDS_RANGE_2);
		$total_records = $this->package_model->b2b_holiday_report($condition, true);		
		// debug($total_records);exit();
		$table_data = $this->package_model->b2b_holiday_report($condition, false, $offset, RECORDS_RANGE_2);
		// debug($table_data);exit();
		$table_data = $this->booking_data_formatter->format_flight_booking_data($table_data, 'b2c', false);
		// debug($table_data); exit;


		$page_data['table_data'] = $table_data['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2c_transfers_crs_report/';
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
		$this->template->view('report/b2b_report_package', $page_data);
	}
function hotelscrs($offset=0)

	{

		validate_user_login();

		$condition = array();

		$total_records = $this->hotel_model->bookingcrs($condition, true);

		$table_data = $this->hotel_model->bookingcrs($condition, false, $offset, RECORDS_RANGE_2);

		$table_data = $this->booking_data_formatter->format_hotel_booking_data($table_data, 'b2c');

		$page_data['table_data'] = $table_data['data'];

		/** TABLE PAGINATION */

		$this->load->library('pagination');

		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");

		$config['base_url'] = base_url().'index.php/report/hotelscrs/';

		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);

		$page_data['total_rows'] = $config['total_rows'] = $total_records;

		$config['per_page'] = RECORDS_RANGE_2;

		$this->pagination->initialize($config);

		/** TABLE PAGINATION */

		$page_data['total_records'] = $config['total_rows'];

		$page_data['customer_email'] = $this->entity_email;

		$this->template->view('report/hotel', $page_data);

	}

	/************************************** CAR REPORT STARTS ***********************************/

	/**

	 * Cae Report

	 * @param $offset

	 */
function privatecar($offset=0)

	{
	   // error_reporting(E_ALL);

		validate_user_login();

		$condition = array();

		$total_records = $this->car_model->privatebooking($condition, true);

		$table_data = $this->car_model->privatebooking($condition, false, $offset, RECORDS_RANGE_2);

		$table_data = $this->booking_data_formatter->format_car_booking_datas($table_data, 'b2c');

		$page_data['table_data'] = $table_data['data'];

		/** TABLE PAGINATION */

		$this->load->library('pagination');

		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");

		$config['base_url'] = base_url().'index.php/report/privatecar/';

		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);

		$page_data['total_rows'] = $config['total_rows'] = $total_records;

		$config['per_page'] = RECORDS_RANGE_2;

		$this->pagination->initialize($config);

		/** TABLE PAGINATION */

		$page_data['total_records'] = $config['total_rows'];

		$page_data['customer_email'] = $this->entity_email;

		$this->template->view('report/car', $page_data);

	}
	
	function car($offset=0)

	{

		validate_user_login();

		$condition = array();

		$total_records = $this->car_model->booking($condition, true);

		$table_data = $this->car_model->booking($condition, false, $offset, RECORDS_RANGE_2);

		$table_data = $this->booking_data_formatter->format_car_booking_datas($table_data, 'b2c');

		$page_data['table_data'] = $table_data['data'];

		/** TABLE PAGINATION */

		$this->load->library('pagination');

		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");

		$config['base_url'] = base_url().'index.php/report/car/';

		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);

		$page_data['total_rows'] = $config['total_rows'] = $total_records;

		$config['per_page'] = RECORDS_RANGE_2;

		$this->pagination->initialize($config);

		/** TABLE PAGINATION */

		$page_data['total_records'] = $config['total_rows'];

		$page_data['customer_email'] = $this->entity_email;

		$this->template->view('report/car', $page_data);

	}
function privatecarold($offset=0)

	{

		validate_user_login();

		$condition = array();

		$total_records = $this->car_model->privatebooking($condition, true);

		$table_data = $this->car_model->privatebooking($condition, false, $offset, RECORDS_RANGE_2);

		$table_data = $this->booking_data_formatter->format_car_booking_datas($table_data, 'b2c');

		$page_data['table_data'] = $table_data['data'];

		/** TABLE PAGINATION */

		$this->load->library('pagination');

		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");

		$config['base_url'] = base_url().'index.php/report/car/';

		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);

		$page_data['total_rows'] = $config['total_rows'] = $total_records;

		$config['per_page'] = RECORDS_RANGE_2;

		$this->pagination->initialize($config);

		/** TABLE PAGINATION */

		$page_data['total_records'] = $config['total_rows'];

		$page_data['customer_email'] = $this->entity_email;

		$this->template->view('report/car', $page_data);

	}
	/**

	*Sightseeing Booking Details

	*/

	function activitiesold($offset=0){

		validate_user_login();

		$condition = array();

		$total_records = $this->sightseeing_model->booking($condition, true);

		$table_data = $this->sightseeing_model->booking($condition, false, $offset, RECORDS_RANGE_2);

		$table_data = $this->booking_data_formatter->format_sightseeing_booking_data($table_data, 'b2c');

		$page_data['table_data'] = $table_data['data'];

		/** TABLE PAGINATION */

		$this->load->library('pagination');

		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");

		$config['base_url'] = base_url().'index.php/report/activities/';

		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);

		$page_data['total_rows'] = $config['total_rows'] = $total_records;

		$config['per_page'] = RECORDS_RANGE_2;

		$this->pagination->initialize($config);

		/** TABLE PAGINATION */

		$page_data['total_records'] = $config['total_rows'];

		$page_data['customer_email'] = $this->entity_email;

		$this->template->view('report/sightseeing', $page_data);

	}

	/**

	*Transfers Booking Details

	*/

	function transfersold($offset=0){

		

		validate_user_login();

		$condition = array();

		$total_records = $this->transferv1_model->booking($condition, true);

		$table_data = $this->transferv1_model->booking($condition, false, $offset, RECORDS_RANGE_2);

		$table_data = $this->booking_data_formatter->format_transferv1_booking_data($table_data, 'b2c');

		$page_data['table_data'] = $table_data['data'];

		/** TABLE PAGINATION */

		$this->load->library('pagination');

		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");

		$config['base_url'] = base_url().'index.php/report/transferv1/';

		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);

		$page_data['total_rows'] = $config['total_rows'] = $total_records;

		$config['per_page'] = RECORDS_RANGE_2;

		$this->pagination->initialize($config);

		/** TABLE PAGINATION */

		$page_data['total_records'] = $config['total_rows'];

		$page_data['customer_email'] = $this->entity_email;

		$this->template->view('report/transferv1', $page_data);

	}



	/**

	 * Hotel Booking Dettails

	 */

	function hotel_booking_details()

	{

		$get_data = $this->input->get();

		if(valid_array($get_data) == true && empty($get_data['status']) == false &&

		empty($get_data['reference_id']) == false &&  empty($get_data['app_reference']) == false) {

			$booking_id = trim($get_data['reference_id']);

			$status = trim($get_data['status']);

			$app_reference = trim($get_data['app_reference']);

			$booking_details = $this->hotel_model->get_booking_details($app_reference,$booking_id,$status);

			if(valid_array($booking_details) == true && $booking_details['status'] == SUCCESS_STATUS){	

				//Assemble Booking Details

				$assembled_booking_details = $this->booking_data_formatter->format_hotel_booking_data($booking_details, 'b2c');

				$page_data['data'] = $assembled_booking_details['data'];

				$this->template->view('hotel/booking_details', $page_data);

			} else {

				redirect('general/index/bus?event=Invalid Booking ID');

			}

		} else {

			redirect('general/index/bus?event=Invalid Booking Details');

		}

	}

	/**

	 * Hotel Voucher

	 */

	function get_hotel_voucher()

	{

		$get_data = $this->input->get();

		if(valid_array($get_data) == true && empty($get_data['reference_id']) == false && empty($get_data['app_reference']) == false) {

			$booking_id = trim($get_data['reference_id']);

			$status = trim($get_data['status']);

			$app_reference = trim($get_data['app_reference']);

			$booking_details = $this->hotel_model->get_booking_details($app_reference,$booking_id,$status);

			if(valid_array($booking_details) == true && $booking_details['status'] == SUCCESS_STATUS) {

				//Assemble Booking Details

				$assembled_booking_details = $this->booking_data_formatter->format_hotel_booking_data($booking_details, 'b2c');

				$page_data['data'] = $assembled_booking_details['data'];

				$data = $this->template->isolated_view('hotel/get_voucher', $page_data);

				header('Content-Type:application/json');

				echo json_encode(array('ticket' => get_compressed_output($data)));

				exit;

			} else {

				redirect('general/index/bus?event=Invalid Deatils');

			}

		} else {

			redirect('general/index/bus?event=Invalid Booking Details');

		}

	}

	/**

	 * Hotel Invoice

	 */

	function get_hotel_invoice()

	{

		$get_data = $this->input->get();

		if(valid_array($get_data) == true && empty($get_data['reference_id']) == false &&  empty($get_data['app_reference']) == false) {

			$booking_id = trim($get_data['reference_id']);

			$status = trim($get_data['status']);

			$app_reference = trim($get_data['app_reference']);

			$booking_details = $this->hotel_model->get_booking_details($app_reference,$booking_id,$status);

			if(valid_array($booking_details) == true && $booking_details['status'] == SUCCESS_STATUS) {

				//Assemble Booking Details

				$assembled_booking_details = $this->booking_data_formatter->format_hotel_booking_data($booking_details, 'b2c');

				$page_data['data'] = $assembled_booking_details['data'];

				$data = $this->template->isolated_view('hotel/get_invoice', $page_data);

				header('Content-Type:application/json');

				echo json_encode(array('invoice' => get_compressed_output($data)));

				exit;

			} else {

				redirect('general/index/bus?event=Invalid Deatils');

			}

		} else {

			redirect('general/index/bus?event=Invalid Booking Details');

		}

	}

	/**

	 * Mail Hotel Voucher

	 * @param $app_reference

	 * @param $booking_source

	 * @param $booking_status

	 * @param $user_email_id

	 * @param $operation

	 */



	function email_hotel_voucher($app_reference, $booking_source='', $booking_status='', $user_email_id='', $operation='show_voucher')

	{

		if (empty($app_reference) == false) {																																

			$booking_details = $this->hotel_model->get_booking_details($app_reference,$booking_source,$booking_status);

			if ($booking_details['status'] == SUCCESS_STATUS) {

				$this->load->library("provab_pdf");

				$this->load->library('provab_mailer');

				//Assemble Booking Details

				$assembled_booking_details = $this->booking_data_formatter->format_hotel_booking_data($booking_details, 'b2c');

				$page_data['data'] = $assembled_booking_details['data'];

				$mail_template = $this->template->isolated_view('hotel/get_voucher', $page_data);

				$pdf = $this->provab_pdf->create_pdf($mail_template);

				$user_email_id = trim($user_email_id);

				$this->provab_mailer->send_mail($user_email_id, 'ProApp - Hotel Ticket', $mail_template,$pdf);

				header('Content-Type:application/json');

				echo json_encode(array('status' => SUCCESS_STATUS));

				exit;

			}else{

				header('Content-Type:application/json');

				echo json_encode(array('status' => "failed"));

				exit;

			}

		}else{

			redirect('general/index/bus?event=Invalid Deatils');

		}

	}



	function email_sightseeing_voucher($app_reference, $booking_source='', $booking_status='', $user_email_id='', $operation='show_voucher')

	{

		if (empty($app_reference) == false) {																																

			$booking_details = $this->sightseeing_model->get_booking_details($app_reference,$booking_source,$booking_status);

			if ($booking_details['status'] == SUCCESS_STATUS) {

				$this->load->library("provab_pdf");

				$this->load->library('provab_mailer');

				//Assemble Booking Details

				$assembled_booking_details = $this->booking_data_formatter->format_sightseeing_booking_data($booking_details, 'b2c');

				$page_data['data'] = $assembled_booking_details['data'];

				$mail_template = $this->template->isolated_view('sightseeing/get_voucher', $page_data);

				$pdf = $this->provab_pdf->create_pdf($mail_template);

				$user_email_id = trim($user_email_id);

				$this->provab_mailer->send_mail($user_email_id, 'ProApp - Sightseeing Ticket', $mail_template,$pdf);

				header('Content-Type:application/json');

				echo json_encode(array('status' => SUCCESS_STATUS));

				exit;

			}else{

				header('Content-Type:application/json');

				echo json_encode(array('status' => "failed"));

				exit;

			}

		}else{

			redirect('general/index/sightseeing?event=Invalid Deatils');

		}

	}





	/************************************** FLIGHT REPORT STARTS ***********************************/

	/**

	 * Flight Report

	 * @param $offset

	 */

	function flights($offset=0)

	{

		validate_user_login();

		$current_user_id = $GLOBALS['CI']->entity_user_id;

		$condition = array();

		$total_records = $this->flight_model->booking($condition, true);

		$table_data = $this->flight_model->booking($condition, false, $offset, RECORDS_RANGE_2);

		$table_data = $this->booking_data_formatter->format_flight_booking_data($table_data, 'b2c');

		$page_data['table_data'] = $table_data['data'];

		$this->load->library('pagination');

		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");

		$config['base_url'] = base_url().'index.php/report/flights/';

		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);

		$page_data['total_rows'] = $config['total_rows'] = $total_records;

		$config['per_page'] = RECORDS_RANGE_2;

		$this->pagination->initialize($config);

		/** TABLE PAGINATION */

		$page_data['total_records'] = $config['total_rows'];

		$this->template->view('report/airline', $page_data);

	}

	/**

	 * Returns Flight Total Fare

	 * @param $fare_details

	 */

	private function flight_total_fare($fare_details)

	{

		$total_fare = array();

		foreach($fare_details as $k => $v) {

			$total_fare[$k] = roundoff_number($v['fare']+$v['admin_markup']+$v['agent_markup']);

		}

		return $total_fare;

	}

	/*

	 * Flight Ticket

	 */

	function get_flight_ticket()

	{

		$get_data = $this->input->get();

		$booking_id = trim($get_data['reference_id']);

		$status = trim($get_data['status']);

		$app_reference = trim($get_data['app_reference']);

		if(valid_array($get_data) == true && empty($get_data['reference_id']) == false && empty($get_data['app_reference']) == false) {

			$booking_details = $this->flight_model->get_booking_details($app_reference,$booking_id,$status);

			if(valid_array($booking_details) == true && $booking_details['status'] == SUCCESS_STATUS) {

				$page_data['booking_details'] = $booking_details;

				$data = $this->template->isolated_view('flight/get_eticket', $page_data);

				header('Content-Type:application/json');

				echo json_encode(array('ticket' => get_compressed_output($data)));

				exit;

			} else {

				redirect('general/index/bus?event=Invalid Deatils');

			}

		} else {

			redirect('general/index/bus?event=Invalid Booking Details');

		}

	}

	/**

	 * Flight Invoice

	 */

	function get_flight_invoice()

	{

		$get_data = $this->input->get();

		$booking_id = trim($get_data['reference_id']);

		$status = trim($get_data['status']);

		$app_reference = trim($get_data['app_reference']);

		if(valid_array($get_data) == true && empty($get_data['reference_id']) == false &&  empty($get_data['app_reference']) == false) {

			$booking_id = trim($get_data['reference_id']);

			$booking_details = $this->flight_model->get_booking_details($app_reference,$booking_id,$status);

			if(valid_array($booking_details) == true && $booking_details['status'] == SUCCESS_STATUS) {

				$page_data['booking_details'] = $booking_details;

				$data = $this->template->isolated_view('flight/get_invoice', $page_data);

				header('Content-Type:application/json');

				echo json_encode(array('invoice' => get_compressed_output($data)));

				exit;

			} else {

				redirect('general/index/bus?event=Invalid Deatils');

			}

		} else {

			redirect('general/index/bus?event=Invalid Booking Details');

		}

	}


	function holidays($offset=0)

	{

		validate_user_login();

		$current_user_id = $GLOBALS['CI']->entity_user_id;

		$condition = array();

		$total_records = $this->tours_model->booking($condition, true);

		$table_data = $this->tours_model->booking($condition, false, $offset, RECORDS_RANGE_2);

		$table_data = $this->booking_data_formatter->format_holiday_user_booking_data($table_data, 'b2c');

		$page_data['table_data'] = $table_data['data'];

		$this->load->library('pagination');

		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");

		$config['base_url'] = base_url().'index.php/report/holidays/';

		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);

		$page_data['total_rows'] = $config['total_rows'] = $total_records;

		$config['per_page'] = RECORDS_RANGE_2;

		$this->pagination->initialize($config);

		/** TABLE PAGINATION */

		$page_data['total_records'] = $config['total_rows'];

		$this->template->view('report/holidays', $page_data);

	}

	function monthly_booking_report()

	{

		$this->template->view('report/monthly_booking_report');

	}

	/* print the voucher for all modules for B2C*/

	function print_voucher(){

	    $post_data = $this->input->post();

	    $error_data=array(); 

	    if(isset($post_data) && valid_array($post_data)){

	    	if (empty($post_data['pnr_number']) == false) {

	    		$this->load->library('pagination');

	    		$page_data['total_rows'] = $config['total_rows'] = 1;

				$config['per_page'] = RECORDS_RANGE_2;

				$this->pagination->initialize($config);

				/** TABLE PAGINATION */

				$page_data['print_voucher'] = 'yes';

				$page_data['total_records'] = $config['total_rows'];


				$booking_status = 'BOOKING_CONFIRMED';

	    		if($post_data['module'] == PROVAB_FLIGHT_BOOKING_SOURCE){

	    			$booking_details = $this->flight_model->booking_guest_user($post_data['pnr_number'], $post_data['module'], $booking_status);

	    			if ($booking_details['status'] == SUCCESS_STATUS) {

	    				$assembled_booking_details = $this->booking_data_formatter->format_flight_booking_data($booking_details, 'b2c');	

						$page_data['table_data'] = $assembled_booking_details['data'];

						$page_data['page_name']="print_voucher";

						$this->template->view('report/airline_print_voucher', $page_data);

					}

					else {

						$error_data['message']="Please Enter Valid PNR";

						 $this->template->view('report/print_voucher', $error_data);

					}


	    		}

	    		if($post_data['module'] == PROVAB_HOTEL_BOOKING_SOURCE){

	    			$booking_details = $this->hotel_model->booking_guest_user($post_data['pnr_number'], $post_data['module'], $booking_status);

	    			if ($booking_details['status'] == SUCCESS_STATUS) {

	    				$assembled_booking_details = $this->booking_data_formatter->format_hotel_booking_data($booking_details, 'b2c');	

						

						$page_data['table_data'] = $assembled_booking_details['data'];

						$this->template->view('report/hotel', $page_data);

					}
    			

	    		}

	    		if($post_data['module'] == PROVAB_BUS_BOOKING_SOURCE){

	    			$booking_details = $this->bus_model->booking_guest_user($post_data['pnr_number'], $post_data['module'], $booking_status);

	    			if ($booking_details['status'] == SUCCESS_STATUS) {

	    				$assembled_booking_details = $this->booking_data_formatter->format_bus_booking_data($booking_details, 'b2c');	
	

						$page_data['table_data'] = $assembled_booking_details['data'];

						$this->template->view('report/bus', $page_data);

					}

	    			

	    		}

	    		if($post_data['module'] == PROVAB_TRANSFERV1_BOOKING_SOURCE){

	    			$booking_details = $this->transferv1_model->booking_guest_user($post_data['pnr_number'], $post_data['module'], $booking_status);

	    			if ($booking_details['status'] == SUCCESS_STATUS) {

	    				$assembled_booking_details = $this->booking_data_formatter->format_transferv1_booking_data($booking_details, 'b2c');	

						$page_data['table_data'] = $assembled_booking_details['data'];

						$this->template->view('report/transferv1', $page_data);

					}  			

	    		}

	    		if($post_data['module'] == PROVAB_SIGHTSEEN_BOOKING_SOURCE){

	    			$booking_details = $this->sightseeing_model->booking_guest_user($post_data['pnr_number'], $post_data['module'], $booking_status);

	    			if ($booking_details['status'] == SUCCESS_STATUS) {

	    				$assembled_booking_details = $this->booking_data_formatter->format_sightseeing_booking_data($booking_details, 'b2c');	

						

						$page_data['table_data'] = $assembled_booking_details['data'];

						$this->template->view('report/sightseeing', $page_data);

					}

	    			

	    		}

	    		

	        }

	    }

	    else{

	      $this->template->view('report/print_voucher', $error_data);

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

} 


?>