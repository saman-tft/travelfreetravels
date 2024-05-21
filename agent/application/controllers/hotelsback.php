<?php 
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
// session_start();
class Hotels extends CI_Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->library ( 'Api_Interface' );
		$this->load->model ( 'hotels_model' );
				$this->load->model ( 'hotel_model' );
		$this->load->model ( 'Validation_Model' );

	}

	//Hotel Type
	function hotel_types()
	{
		if (!check_user_previlege('106')) 
		{
            set_update_message("You Don't have permission to do this action.", WARNING_MESSAGE, array(
                'override_app_msg' => true
            ));
            redirect(base_url());
        }
		$hotels	= $this->hotels_model->getHomePageSettings();
		// debug($hotels);die;
		$hotels['hotel_types_list'] 	= $this->hotels_model->get_hotel_types_list();
		$this->template->view('hotel/hotel_types/hotel_types_list',$hotels);
	}
	function add_hotel_type()
	{
	   $data=array();
		$this->template->view('hotel/hotel_types/add_hotel_type',$data);	
	}

	
	function save_hotel_type_data()
	{
		try 
		{
			if(isset($_POST['hotel_type_name'])==FALSE || empty($_POST['hotel_type_name'])==TRUE)
			{
				throw new Exception("Error Processing Request", 1);				
			}
			$data['name']=$_POST['hotel_type_name'];
			$data['created_by']=$_POST['user_id'];
			$data['status']=$_POST['status'];
            if($data['status']=="")
			{
				$data['status']="INACTIVE";
			}
			
			if($this->hotels_model->add_hotel_type_details($data)==false)
			{
				throw new Exception('Hotel Type Adding Failed', 1);				
			}
			$this->session->set_flashdata('success_message', 'Inserted Successfully!!');
			redirect('hotels/hotel_types','refresh');				
		} 
		catch (Exception $e) 
		{
			$this->session->set_flashdata('error_message', $e->getMessage());
			redirect('hotels/add_hotel_type','refresh');				
		}
	}
	
	function inactive_hotel_type($hotel_type_id1)
	{
		$hotel_type_id 	= json_decode(base64_decode($hotel_type_id1));
		if($hotel_type_id != '')
		{
			$this->hotels_model->inactive_hotel_type($hotel_type_id);
		}
		$this->session->set_flashdata('success_message', 'Deactivated Successfully!!');
		redirect('hotels/hotel_types','refresh');
	}
	
	function active_hotel_type($hotel_type_id1){
		$hotel_type_id 	= json_decode(base64_decode($hotel_type_id1));
		if($hotel_type_id != ''){
			$this->hotels_model->active_hotel_type($hotel_type_id);
		}
		  $this->session->set_flashdata('success_message', 'Updated Successfully!!');
		redirect('hotels/hotel_types','refresh');
	}

function cancel_booking($app_reference, $booking_source)
	{
		if(empty($app_reference) == false) {
			$master_booking_details = $this->hotel_model->get_booking_details($app_reference, $booking_source);
		
			if ($master_booking_details['status'] == SUCCESS_STATUS) {
					
				$this->load->library('booking_data_formatter');
				$master_booking_details = $this->booking_data_formatter->format_hotel_booking_data($master_booking_details, 'b2c');
				$master_booking_details = $master_booking_details['data']['booking_details'][0];
				//debug($booking_source );die;
				load_hotel_lib($booking_source);
				
				$cancellation_details = $this->hotel_lib->cancel_booking($master_booking_details);//Invoke Cancellation Methods
				if($cancellation_details['status'] == false) {
					$query_string = '?error_msg='.$cancellation_details['msg'];
				} else {
					$query_string = '';
				}
				redirect('hotel/cancellation_details/'.$app_reference.'/'.$booking_source.$query_string);
			} else {
				redirect('security/log_event?event=Invalid Details');
			}
		} else {
			redirect('security/log_event?event=Invalid Details');
		}
	}
	//Hotel Drop down room type
	function room_types()
	{
		if (!check_user_previlege('106')) {
            set_update_message("You Don't have permission to do this action.", WARNING_MESSAGE, array(
                'override_app_msg' => true
            ));
            redirect(base_url());
        }
		$hotels 						= $this->hotels_model->getHomePageSettings();
		//echo '<pre>'; print_r($hotels); exit();
		$hotels['room_types_list'] 	= $this->hotels_model->get_room_types_list();
		$this->template->view('hotel/room_type/room_types_list',$hotels);
	}

	function add_room_types()
	{
		
		$data=array();
		$this->template->view('hotel/room_type/add_room_type',$data);
	}
	
	function inactive_room_types($room_type_id1){
		$room_type_id 	= json_decode(base64_decode($room_type_id1));
		if($room_type_id != ''){
			$this->hotels_model->inactive_room_types($room_type_id);
		}
		$this->session->set_flashdata('success_message', 'Updated Successfully!!');
		redirect('hotels/room_types','refresh');
	}
	
	function active_room_types($room_type_id1){
		$room_type_id 	= json_decode(base64_decode($room_type_id1));
		if($room_type_id != ''){
			$this->hotels_model->active_room_types($room_type_id);
		}
		$this->session->set_flashdata('success_message', 'Updated Successfully!!');
		redirect('hotels/room_types','refresh');
	}
	
	function edit_room_types($room_type_id1){
		$room_type_id 	= json_decode(base64_decode($room_type_id1));
		if($room_type_id != ''){
			
			$room_types['room_types_list'] 	= $this->hotels_model->get_room_types_list($room_type_id);
			$this->template->view('hotel/room_type/edit_room_type',$room_types);
		}else{
			redirect('hotels/room_types','refresh');
		}
	}
	
	function update_room_types($room_type_id1){
		$room_type_id 	= json_decode(base64_decode($room_type_id1));
		if($room_type_id != ''){
			if(count($_POST) > 0){
				$this->hotels_model->update_room_types($_POST,$room_type_id);
				$this->session->set_flashdata('success_message', 'Updated Successfully!!');
				redirect('hotels/room_types','refresh');
			}else if($hotel_type_id!=''){
				redirect('hotels/edit_room_type/'.$room_type_id,'refresh');
			}else{
				redirect('hotels/room_types','refresh');
			}
		}else{
			redirect('hotels/room_types','refresh');
		}
	}
	
	function delete_room_types($room_type_id1){
		$room_type_id 	= json_decode(base64_decode($room_type_id1));
		if($room_type_id != ''){
			$this->hotels_model->delete_room_types($room_type_id);
		}
		$this->session->set_flashdata('success_message', 'Deleted Successfully!!');
		redirect('hotels/room_types','refresh');
	}
	function save_room_type_data()
	{
		try 
		{
			if(isset($_POST['room_type_name'])==FALSE || empty($_POST['room_type_name'])==TRUE)
			{
				throw new Exception("Error Processing Request", 1);				
			}
			$data['name']=$_POST['room_type_name'];
			$data['created_by']=$_POST['user_id'];
			$data['status']=$_POST['status'];

			if($this->hotels_model->add_room_type_details($data)==false)
			{
				throw new Exception('Room Type Adding Failed', 1);				
			}
			$this->session->set_flashdata('success_message', 'Inserted Successfully!!');
			redirect('hotels/room_types','refresh');				
		} 
		catch (Exception $e) 
		{
			$this->session->set_flashdata('error_message', $e->getMessage());
			redirect('hotels/add_room_types','refresh');				
		}
	}

	// ---------------------------------------------------------------------------------------------------
	//Hotel-CRS

	function hotel_ammenities(){
		if (!check_user_previlege('106')) {
            set_update_message("You Don't have permission to do this action.", WARNING_MESSAGE, array(
                'override_app_msg' => true
            ));
            redirect(base_url());
        }
		
		$ammenities['ammenities_list'] 		= $this->hotels_model->get_ammenities_list();
		//debug($ammenities);die;
		$this->template->view('hotel/hotel_ammenities/ammenities_list',$ammenities);
	}

	function add_hotel_ammenties()
	{	
		
		$data=array();	
		$this->template->view('hotel/hotel_ammenities/add_hotel_ammenity',$data);
	}
	function save_hotel_amenities_data()
	{
		// debug($_POST);die;
		try 
		{
			if(isset($_POST['hotel_ammenity_name'])==FALSE || empty($_POST['hotel_ammenity_name'])==TRUE)
			{
				throw new Exception("Error Processing Request", 1);				
			}
			$data['name']=$_POST['hotel_ammenity_name'];
			$data['created_by']=$_POST['user_id'];
			$data['status']=$_POST['status'];
			if($data['status']=="")
			{
				$data['status']="INACTIVE";
			}
// debug($data);die;
			if($this->hotels_model->add_hotel_ammenity_details($data)==false)
			{
				throw new Exception('Hotel Amenities  Adding Failed', 1);				
			}
			$this->session->set_flashdata('success_message', 'Inserted Successfully!!');
			redirect('hotels/hotel_ammenities','refresh');				
		} 
		catch (Exception $e) 
		{
			$this->session->set_flashdata('error_message', $e->getMessage());
			redirect('hotels/add_hotel_ammenties','refresh');				
		}	
	}

	function inactive_hotel_ammenity($ammenity_id1)
	{
		$ammenity_id 	= json_decode(base64_decode($ammenity_id1));
		if($ammenity_id != ''){
			$this->hotels_model->inactive_hotel_ammenity($ammenity_id);
			$this->session->set_flashdata('success_message', 'Updated Successfully!!');
		}	
		redirect('hotels/hotel_ammenities','refresh');
	}
	
	function active_hotel_ammenity($ammenity_id1){
		$ammenity_id 	= json_decode(base64_decode($ammenity_id1));
		if($ammenity_id != ''){
			$this->hotels_model->active_hotel_ammenity($ammenity_id);
				$this->session->set_flashdata('success_message', 'Updated Successfully!!');
		}
		redirect('hotels/hotel_ammenities','refresh');
	}

	function edit_hotel_ammenity($ammenity_id1){
		$ammenity_id 	= json_decode(base64_decode($ammenity_id1));
		if($ammenity_id != ''){
			
			$hotel_ammenities['ammenities_list'] 		= $this->hotels_model->get_ammenities_list($ammenity_id);
			$this->template->view('hotel/hotel_ammenities/edit_hotel_ammenity',$hotel_ammenities);
		}else{
			redirect('hotels/hotel_ammenities','refresh');
		}
	}
	
	function update_hotel_ammenity($ammenity_id1){
		$ammenity_id 	= json_decode(base64_decode($ammenity_id1));
		if($ammenity_id != ''){
			if(count($_POST) > 0){
				$this->hotels_model->update_hotel_ammenity($_POST,$ammenity_id);
					$this->session->set_flashdata('success_message', 'Updated Successfully!!');
				redirect('hotels/hotel_ammenities','refresh');
			}else if($hotel_type_id!=''){
				redirect('hotels/edit_hotel_ammenity/'.$ammenity_id,'refresh');
			}else{
				redirect('hotels/hotel_ammenities','refresh');
			}
		}else{
			redirect('hotels/hotel_ammenities','refresh');
		}
	}
	
	function delete_hotel_ammenity($ammenity_id1){
		$ammenity_id 	= json_decode(base64_decode($ammenity_id1));
		if($ammenity_id != ''){
			$this->hotels_model->delete_hotel_ammenity($ammenity_id);
				//$this->session->set_flashdata('success_message', 'Deleted Successfully!!');
			set_delete_message();
		}
		redirect('hotels/hotel_ammenities','refresh');
	}





	/**
	 */
	function deal_sheets() {
		$bus_deals_result = json_decode ( $this->api_interface->rest_service ( 'bus_deal_sheet' ), true );
		$flight_deals_result = json_decode ( $this->api_interface->rest_service ( 'airline_deal_sheet' ), true );
		
		$page_data ['bus_deals'] = $bus_deals_result ['data'];
		$page_data ['flight_deals'] = $flight_deals_result ['data'];
		$this->template->view ( 'utilities/deal_sheets', $page_data );
	}
	
	/**
	 * Update Convenience Fees in application
	 */
	function convenience_fees() {
		$page_data ['post_data'] = $this->input->post ();
		$this->load->model ( 'transaction_model' );
		if (valid_array ( $page_data ['post_data'] ) == true) {
			$this->transaction_model->update_convenience_fees ( $page_data ['post_data'] );
			set_update_message ();
			redirect ( base_url () . 'index.php/utilities/convenience_fees' );
		}
		$convenience_fees = $this->transaction_model->get_convenience_fees ();
		$page_data ['convenience_fees'] = $this->format_convenience_fees ( $convenience_fees );
		$this->template->view ( 'utilities/convenience_fees', $page_data );
	}
	
	/**
	 * Format Convenience Fees As Per View
	 */
	private function format_convenience_fees($convenience_fees) {
		$data = array ();
		foreach ( $convenience_fees as $k => $v ) {
			$data [$k] ['origin'] = $v ['origin'];
			$data [$k] ['module'] = strtoupper ( $v ['module'] );
			$fees = '';
			if ($v ['value_type'] == 'plus') {
				$fees = '+' . floatval ( $v ['value'] );
			} else {
				$fees = floatval ( $v ['value'] ) . '%';
			}
			$data [$k] ['fees'] = $fees;
			$data [$k] ['value'] = $v ['value'];
			$data [$k] ['value_type'] = $v ['value_type'];
			$data [$k] ['per_pax'] = $v ['per_pax'];
		}
		return $data;
	}
	
	/**
	 * Manage booking source in the application
	 */
	function manage_source() {
		if(check_user_previlege('106') === FALSE){
			redirect(base_url());
		}
		$page_data ['list_data'] = $this->module_model->get_course_list ();
		$this->template->view ( 'utilities/manage_source', $page_data );
	}
	/**
	 * Manage sms status in sms_checkpoint table
	 */
	function sms_checkpoint() {
		$sms_checkpoint_data = $this->module_model->get_sms_checkpoint ();
		$data ['sms_data'] = $sms_checkpoint_data;
		$this->template->view ( 'utilities/sms_checkpoint', $data );
	}
	/**
	 * Activate sms_checkpoint
	 */
	function activate_sms_checkpoint($condition) {
		$status = ACTIVE;
		$this->module_model->update_sms_checkpoint_status ( $status, $condition );
		redirect ( base_url () . 'index.php/utilities/sms_checkpoint' );
	}
	
	/**
	 * Deactiavte sms_checkpoint
	 */
	function deactivate_sms_checkpoint($condition) {
		$status = INACTIVE;
		$info = $this->module_model->update_sms_checkpoint_status ( $status, $condition );
		redirect ( base_url () . 'index.php/utilities/sms_checkpoint' );
	}
	/**
	 * Module Activation
	 */
	function module() {
		$domain_list = $this->module_model->get_module_list ();
		$data ['domain_list'] = $domain_list;
		$this->template->view ( 'utilities/module_list', $data );
	}
	/**
	 * Activate sms_checkpoint
	 */
	function activate_module($condition) {
		$status = ACTIVE;
		$this->module_model->update_module_status ( $status, $condition );
		redirect ( base_url () . 'index.php/utilities/module' );
	}
	
	/**
	 * Deactiavte sms_checkpoint
	 */
	function deactivate_module($condition) {
		$status = INACTIVE;
		$info = $this->module_model->update_module_status ( $status, $condition );
		redirect ( base_url () . 'index.php/utilities/module' );
	}
	/**
	 * Activate social_link
	 */
	function activate_social_link($condition) {
		$status = ACTIVE;
		$this->module_model->update_social_link_status ( $status, $condition );
		redirect ( base_url () . 'index.php/utilities/social_network' );
	}
	
	/**
	 * Deactiavte social_link
	 */
	function deactivate_social_link($condition) {
		$status = INACTIVE;
		$info = $this->module_model->update_social_link_status ( $status, $condition );
		redirect ( base_url () . 'index.php/utilities/social_network' );
	}
	/*
	 * SOcial Network Url Management
	 */
	function social_network() {
		$temp = $this->custom_db->single_table_records ( 'social_links' );
		$data ['social_links'] = $temp ['data'];
		$this->template->view ( 'utilities/social_network', $data );
	}
	/**
	 * Update_Social URL
	 */
	function edit_social_url($id) {
		$post_data = $this->input->post ();
		$url = $post_data ['social_url'];
		$info = $this->module_model->update_social_url ( $url, $id );
		redirect ( base_url () . 'index.php/utilities/social_network' );
	}
	
	/**
	 * Activate social_login
	 */
	function activate_social_login($condition) {
		$status = ACTIVE;
		$this->module_model->update_social_login_status ( $status, $condition );
		redirect ( base_url () . 'index.php/utilities/social_login' );
	}
	
	/**
	 * Deactiavte social_login
	 */
	function deactivate_social_login($condition) {
		$status = INACTIVE;
		$info = $this->module_model->update_social_login_status ( $status, $condition );
		redirect ( base_url () . 'index.php/utilities/social_login' );
	}
	/*
	 * SOcial Network Url Management
	 */
	function social_login() {
		$temp = $this->custom_db->single_table_records ( 'social_login' );
		$data ['social_login'] = $temp ['data'];
		$this->template->view ( 'utilities/social_login', $data );
	}
	/**
	 * Update social_login
	 */
	function edit_social_login($id) {
		$post_data = $this->input->post ();
		$url = $post_data ['social_login'];
		$info = $this->module_model->update_social_login_name ( $url, $id );
		redirect ( base_url () . 'index.php/utilities/social_login' );
	}
	function toggle_asm_status($bs_id, $mc_id, $status = false) {
		$list_data = $this->module_model->get_course_list ( array (
				array (
						'BS.origin',
						'=',
						$bs_id 
				),
				array (
						'MCL.origin',
						'=',
						$mc_id 
				) 
		) );
		if (valid_array ( $list_data ) == true) {
			$api_code = $list_data [0] ['booking_source_id'];
			$api_name = $list_data [0] ['booking_source'];
			$module_name = $list_data [0] ['name'];
			if ($status == 'false') {
				$status = 'inactive';
				$logger_msg = $this->entity_name . ' Deactivated ' . $module_name . ' (' . $api_code . '-' . $api_name . ') API';
			} else {
				$status = 'active';
				$logger_msg = $this->entity_name . ' Activated ' . $module_name . ' (' . $api_code . '-' . $api_name . ') API';
			}
			$this->custom_db->update_record ( 'activity_source_map', array (
					'status' => $status 
			), array (
					'booking_source_fk' => $bs_id,
					'meta_course_list_fk' => $mc_id,
					'domain_origin' => get_domain_auth_id () 
			) );
			$this->application_logger->api_status ( $logger_msg );
		}
	}
	
	/**
	 * Currency Converter Settings!!!
	 *
	 * @param float $value        	
	 * @param int $id        	
	 */
	function currency_converter($value = 0, $id = 0) {
		if (intval ( $id ) > 0 && intval ( $value ) > - 1) {
			$data ['value'] = $value;
			$this->custom_db->update_record ( 'currency_converter', $data, array (
					'id' => $id 
			) );
		} else {
			$currency_data = $this->custom_db->single_table_records ( 'currency_converter' );
			$data ['converter'] = $currency_data ['data'];
			$this->template->view ( 'utilities/currency_converter', $data );
		}
	}
	
	/**
	 * Currency Converter Status Update!!!
	 *
	 * @param float $value        	
	 * @param int $id        	
	 */
	function currency_status_toggle($id = 0, $status = ACTIVE) {
		if (intval ( $id ) > 0) {
			$data ['status'] = $status;
			$this->custom_db->update_record ( 'currency_converter', $data, array (
					'id' => $id 
			) );
		}
	}
	
	/**
	 * Update Currency Converter Values Automatically Using Live Rates
	 * Keeping COURSE_LIST_DEFAULT_CURRENCY_VALUE AS Base Currency
	 */
	function auto_currency_converter() {
		$data_set = $this->custom_db->single_table_records ( 'currency_converter' );
		if ($data_set ['status'] == true) {
			$from = COURSE_LIST_DEFAULT_CURRENCY_VALUE;
			$data ['date_time'] = date ( 'Y-m-d H:i:s' );
			foreach ( $data_set ['data'] as $k => $v ) {
				$url = 'http://download.finance.yahoo.com/d/quotes.csv?s=' . $v ['country'] . $from . '=X&f=nl1';
				$handle = fopen ( $url, 'r' );
				if ($handle) {
					$currency_data = fgetcsv ( $handle );
					fclose ( $handle );
				}
				if ($currency_data != '') {
					if (isset ( $currency_data [0] ) == true and empty ( $currency_data [0] ) == false and isset ( $currency_data [1] ) == true and empty ( $currency_data [1] ) == false) {
						$data ['value'] = $currency_data [1];
						$this->custom_db->update_record ( 'currency_converter', $data, array (
								'id' => $v ['id'] 
						) );
					}
				}
			}
		}
		redirect ( 'utilities/currency_converter' );
	}
	
	/**
	 * Load All Events Of Trip Calendar
	 */
	function trip_calendar() {
		$this->template->view ( 'utilities/trip_calendar' );
	}
	function app_settings() {
		$this->template->view ( 'utilities/app_settings' );
	}
	
	/**
	 * Show time line to user previous one month - Load Last one month by default
	 */
	function timeline() {
		$this->template->view ( 'utilities/timeline' );
	}
	
	/**
	 * Get All The Events Between Two Dates
	 */
	function timeline_rack() {
		$response ['status'] = FAILURE_STATUS;
		$response ['data'] = array ();
		$response ['msg'] = '';
		$params = $this->input->get ();
		$oe_start = intval ( $params ['oe_start'] );
		$event_limit = intval ( $params ['oe_limit'] );
		if ($oe_start > - 1 and $event_limit > - 1) {
			// Older Events
			$oe_list = $this->application_logger->get_events ( $oe_start, $event_limit );
			if (valid_array ( $oe_list ) == true) {
				$response ['oe_list'] = get_compressed_output ( $this->template->isolated_view ( 'utilities/core_timeline', array (
						'list' => $oe_list 
				) ) );
				$response ['status'] = SUCCESS_STATUS;
			}
		}
		header ( 'Content-type:application/json' );
		echo json_encode ( $response );
		exit ();
	}
	
	/**
	 * Get All The Events Between Two Dates
	 */
	function latest_timeline_events() {
		session_write_close (); // This is needed as it helps remove session locks
		$response ['status'] = FAILURE_STATUS;
		$response ['data'] = array ();
		$response ['msg'] = '';
		$waiting_for_new_event = true;
		$params = $this->input->get ();
		$last_event_id = intval ( $params ['last_event_id'] );
		if ($last_event_id > - 1) {
			$cond = array (
					array (
							'TL.origin',
							'>',
							$last_event_id 
					) 
			);
			// Older Events
			while ( $response ['status'] == false ) {
				$os_list = $this->application_logger->get_events ( 0, 10000000000, $cond );
				if (valid_array ( $os_list ) == true) {
					$response ['oa_list'] = get_compressed_output ( $this->template->isolated_view ( 'utilities/core_timeline', array (
							'list' => $os_list 
					) ) );
					$response ['status'] = SUCCESS_STATUS;
				} else {
					sleep ( 3 );
				}
			}
		}
		header ( 'Content-type:application/json' );
		echo json_encode ( $response );
		exit ();
	}
	/**
	 * Jaganath
	 * Manage Promo Codes
	 */
	function manage_promo_code($offset = 0) {
		$post_data = $this->input->post ();
		$get_data = $this->input->get ();
		$page_data = array ();
		$page_data ['from_data'] ['origin'] = 0;
		$condition = array ();
		$page_data ['promo_code_page_obj'] = new Provab_Page_Loader ( 'manage_promo_code' );
		if (isset ( $get_data ['eid'] ) == true && intval ( $get_data ['eid'] ) > 0 && valid_array ( $post_data ) == false) {
			$edit_data = $this->custom_db->single_table_records ( 'promo_code_list', '*', array (
					'origin' => intval ( $get_data ['eid'] ) 
			) );
			if ($edit_data ['status'] == true) {
				if (strtotime ( $edit_data ['data'] [0] ['expiry_date'] ) <= 0) {
					$edit_data ['data'] [0] ['expiry_date'] = ''; // If its Unlimited, setting the Expiry Date to empty
				}
				$page_data ['from_data'] = $edit_data ['data'] [0];
			} else {
				redirect ( 'security/log_event?event=InvalidID' );
			}
		} else if (valid_array ( $post_data ) == true) { // ADD
			$page_data ['promo_code_page_obj']->set_auto_validator ();
			if ($this->form_validation->run ()) {
				$origin = intval ( $post_data ['origin'] );
				unset ( $post_data ['FID'] );
				unset ( $post_data ['origin'] );
				$promo_code_list = array ();
				$promo_code_list ['module'] = trim ( $post_data ['module'] );
				$promo_code_list ['promo_code'] = trim ( $post_data ['promo_code'] );
				$promo_code_list ['description'] = trim ( $post_data ['description'] );
				$promo_code_list ['value_type'] = trim ( $post_data ['value_type'] );
				$promo_code_list ['value'] = trim ( $post_data ['value'] );
				$expiry_date = trim ( $post_data ['expiry_date'] );
				if (empty ( $expiry_date ) == false && valid_date_value ( $expiry_date )) {
					$promo_code_list ['expiry_date'] = date ( 'Y-m-d', strtotime ( $expiry_date ) );
				} else {
					$promo_code_list ['expiry_date'] = date ( '0000-00-00' );
				}
				$promo_code_list ['status'] = trim ( $post_data ['status'] );
				set_update_message ();
				if ($origin > 0) { // Update
					$this->custom_db->update_record ( 'promo_code_list', $promo_code_list, array (
							'origin' => $origin 
					) );
				} else if ($origin == 0) { // Add
					$promo_code_list ['created_by_id'] = $this->entity_user_id;
					$promo_code_list ['created_datetime'] = db_current_datetime ();
					$this->custom_db->insert_record ( 'promo_code_list', $promo_code_list );
					set_insert_message ();
				}
				redirect ( 'utilities/manage_promo_code' );
			}
		}
		// ***********FILTERS***********//
		if (isset ( $get_data ['promo_code'] ) == true) {
			$filter_promo_code = trim ( $get_data ['promo_code'] );
			if (empty ( $filter_promo_code ) == false) {
				$condition [] = array (
						'promo_code',
						'=',
						'"' . $filter_promo_code . '"' 
				);
			}
		}
		if (isset ( $get_data ['module'] ) == true) {
			$filter_module = trim ( $get_data ['module'] );
			if (empty ( $filter_module ) == false) {
				$condition [] = array (
						'module',
						'=',
						'"' . $filter_module . '"' 
				);
			}
		}
		// ***********FILTERS***********//
		$total_records = $this->module_model->promo_code_list ( $condition, true );
		$promo_code_list = $this->module_model->promo_code_list ( $condition, false, $offset, RECORDS_RANGE_2 );
		/**
		 * TABLE PAGINATION
		 */
		$this->load->library ( 'pagination' );
		if (count ( $_GET ) > 0)
			$config ['suffix'] = '?' . http_build_query ( $_GET, '', "&" );
		$config ['base_url'] = base_url () . 'index.php/utilities/manage_promo_code/';
		$config ['first_url'] = $config ['base_url'] . '?' . http_build_query ( $_GET );
		$config ['total_rows'] = $total_records;
		$config ['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize ( $config );
		/**
		 * TABLE PAGINATION
		 */
		$page_data ['promocode_module_options'] = $this->module_model->promocode_module_options ();
		$page_data ['promo_code_list'] = $promo_code_list;
		$this->template->view ( 'utilities/manage_promo_code', $page_data );
	}


	//room ammenities
	function room_ammenities(){
		if (!check_user_previlege('106')) {
            set_update_message("You Don't have permission to do this action.", WARNING_MESSAGE, array(
                'override_app_msg' => true
            ));
            redirect(base_url());
        }
		
		$ammenities['ammenities_list'] 		= $this->hotels_model->get_room_ammenities_list();
		$this->template->view('hotel/room_ammenities/ammenities_list',$ammenities);
	}

	function add_room_ammenties()
	{
		$data=array();
		$this->template->view('hotel/room_ammenities/add_hotel_ammenity',$data);
	}
	function save_room_amenities_data()
	{
		// debug($_POST);die;
		try 
		{
			if(isset($_POST['hotel_ammenity_name'])==FALSE || empty($_POST['hotel_ammenity_name'])==TRUE)
			{
				throw new Exception("Error Processing Request", 1);				
			}
			$data['name']=$_POST['hotel_ammenity_name'];
			$data['created_by']=$_POST['user_id'];
			$data['status']=$_POST['status'];
// debug($data);die;
			if($this->hotels_model->add_room_ammenity_details($data)==false)
			{
				throw new Exception('Room Amenities  Adding Failed', 1);				
			}
			$this->session->set_flashdata('success_message', 'Inserted Successfully!!');
			redirect('hotels/room_ammenities','refresh');				
		} 
		catch (Exception $e) 
		{
			$this->session->set_flashdata('error_message', $e->getMessage());
			redirect('hotels/save_room_amenities_data','refresh');				
		}	
	}

	function inactive_room_ammenity($ammenity_id1){
		$ammenity_id 	= json_decode(base64_decode($ammenity_id1));
		if($ammenity_id != ''){
			$this->hotels_model->inactive_room_ammenity($ammenity_id);
			$this->session->set_flashdata('success_message', 'Updated Successfully!!');
		}
		redirect('hotels/room_ammenities','refresh');
	}
	
	function active_room_ammenity($ammenity_id1){
		$ammenity_id 	= json_decode(base64_decode($ammenity_id1));
		if($ammenity_id != ''){
			$this->hotels_model->active_room_ammenity($ammenity_id);
				$this->session->set_flashdata('success_message', 'Updated Successfully!!');
		}
		redirect('hotels/room_ammenities','refresh');
	}

	function edit_room_ammenity($ammenity_id1){
		$ammenity_id 	= json_decode(base64_decode($ammenity_id1));
		if($ammenity_id != ''){
			
			$hotel_ammenities['ammenities_list'] 		= $this->hotels_model->get_room_ammenities_list($ammenity_id);
			$this->template->view('hotel/room_ammenities/edit_hotel_ammenity',$hotel_ammenities);
		}else{
			redirect('hotels/room_ammenities','refresh');
		}
	}
	
	function update_room_ammenity($ammenity_id1)
	{
		$ammenity_id 	= json_decode(base64_decode($ammenity_id1));
		if($ammenity_id != ''){
			if(count($_POST) > 0){
				$this->hotels_model->update_room_ammenity($_POST,$ammenity_id);
					$this->session->set_flashdata('success_message', 'Updated Successfully!!');
				redirect('hotels/room_ammenities','refresh');
			}else if($hotel_type_id!=''){
				redirect('hotels/edit_room_ammenity/'.$ammenity_id,'refresh');
			}else{
				redirect('hotels/room_ammenities','refresh');
			}
		}else{
			redirect('hotels/room_ammenities','refresh');
		}
	}
	
	function delete_room_ammenity($ammenity_id1){
		$ammenity_id 	= json_decode(base64_decode($ammenity_id1));
		if($ammenity_id != ''){
			$this->hotels_model->delete_room_ammenity($ammenity_id);
				$this->session->set_flashdata('success_message', 'Deleted Successfully!!');
		}
		redirect('hotels/room_ammenities','refresh');
	}

	//end room ammenities

	
	function get_child_group($hotel_id ,$child_price1 = "") {		
		if($child_price1 != ""){			
			$child_price = explode("_",$child_price1);			
		}

		$child_group = $this->hotels_model->get_child_group($hotel_id); 
	    $childs_div ='';
	    
	    if($child_group[0]->child_group_a == "" && $child_group->child_group_b == "" && $child_group->child_group_c =="" && $child_group[0]->child_group_d == ""){
			$child_group     = $this->hotels_model->get_hotel_settings_list(); 
	    }
	  
	    if($child_group[0]->child_group_a != ""){	    	
	    	if(isset($child_price[0])){  
	    		$zero_child = $child_price[0]; 	    		
	    	}
	    	else{
	    		$zero_child="";
	    	}
		$childs_div .='<div class="form-group">	
											<label for="field-1" class="col-sm-3 control-label">Infant Price/ Person ('.$child_group[0]->child_group_a.')</label>		<div class="col-sm-5">							
											<input type="text" class="form-control" value="'.$zero_child .'" maxlength="7" id="child_price_a" name="child_price_a" data-message-required="Please Enter the Child Price" />
										</div></div>';
		}
		
		if($child_group[0]->child_group_b != ""){
			if(isset($child_price[1])){  
	    		$zero_child = $child_price[1]; 
	    	}
	    	else{
	    		$zero_child="";
	    	}
		$childs_div .='<div class="form-group">	
											<label for="field-1" class="col-sm-3 control-label">Child Price/ Person ('.$child_group[0]->child_group_b.')</label>					<div class="col-sm-5">				
											<input type="text" class="form-control" maxlength="7" value="'.$zero_child .'" id="child_price_b" name="child_price_b" data-message-required="Please Enter the Child Price" />
										</div></div>';
		}
		if($child_group[0]->child_group_c != ""){
			if(isset($child_price[2])){  
	    		$zero_child = $child_price[2]; 
	    	}
	    	else{
	    		$zero_child="";
	    	}
		$childs_div .='<div class="form-group">
											<label for="field-1" class="col-sm-3 control-label">Child Price/ Person ('.$child_group[0]->child_group_c.')</label>		
											<div class="col-sm-5">			
											<input type="text" class="form-control" maxlength="7" value="'.$zero_child .'" id="child_price_c" name="child_price_c" data-message-required="Please Enter the Child Price" />
										</div></div>';
		}
		if($child_group[0]->child_group_d != ""){
			if(isset($child_price[3])){  
	    		$zero_child = $child_price[3]; 
	    	}
	    	else{
	    		$zero_child="";
	    	}
		$childs_div .='<div class="form-group"> 
											<label for="field-1" class="col-sm-3 control-label">Child Price/ Person ('.$child_group[0]->child_group_d.')</label>					<div class="col-sm-5">				
											<input type="text" class="form-control" maxlength="7" value="'.$zero_child .'" id="child_price_d" name="child_price_d" data-message-required="Please Enter the Child Price" />
										</div></div>';
		}
		
		if($child_group[0]->child_group_e != ""){
			if(isset($child_price[4])){  
	    		$zero_child = $child_price[4]; 
	    	}
	    	else{
	    		$zero_child="";
	    	}
		$childs_div .='<div class="form-group"> 
											<label for="field-1" class="col-sm-3 control-label">Child Price/ Person ('.$child_group[0]->child_group_e.')</label>					<div class="col-sm-5">				
											<input type="text" class="form-control" maxlength="7" value="'.$zero_child .'" id="child_price_e" name="child_price_e" data-message-required="Please Enter the Child Price" />
										</div></div>';
		}
		//debug($childs_div);exit;
		
		echo $childs_div; exit;
	}
	
	function edit_hotel_type($hotel_type_id1)
	{
		$hotel_type_id 	= json_decode(base64_decode($hotel_type_id1));
		if($hotel_type_id != ''){
			
			$hotel_types['hotel_types_list'] 	= $this->hotels_model->get_hotel_types_list($hotel_type_id);
			$this->template->view('hotel/hotel_types/edit_hotel_type',$hotel_types);
		}else{
			redirect('hotels/hotel_types','refresh');
		}
	}
	
	function update_hotel_type($hotel_type_id1)
	{
		$hotel_type_id 	= json_decode(base64_decode($hotel_type_id1));
		if($hotel_type_id != '')
		{
			if(count($_POST) > 0){
				$this->hotels_model->update_hotel_type($_POST,$hotel_type_id);
				  $this->session->set_flashdata('success_message', 'Updated Successfully!!');
				redirect('hotels/hotel_types','refresh');
			}else if($hotel_type_id!=''){
			      $this->session->set_flashdata('success_message', 'Updated Successfully!!');
				redirect('hotels/edit_hotel_type/'.$hotel_type_id,'refresh');
			}else{
				redirect('hotels/hotel_types','refresh');
			}
		}else{
			redirect('hotels/hotel_types','refresh');
		}
	}
	function delete_hotel_type($hotel_type_id1){
		$hotel_type_id 	= json_decode(base64_decode($hotel_type_id1));
		if($hotel_type_id != ''){
			$this->hotels_model->delete_hotel_type($hotel_type_id);
		}
		  //$this->session->set_flashdata('success_message', 'Deleted Successfully!!');
		set_delete_message();
		redirect('hotels/hotel_types','refresh');
	}

	function hotel_crs_list()
	{ 
		$hotels 						= $this->hotels_model->getHomePageSettings();
		$hotels['hotels_list'] 	   		= $this->hotels_model->get_all_hotel_crs_list();
	    $this->template->view('hotel/hotel_crs/hotel_crs_list',$hotels);
	}

	function inactive_hotel($hotel_id1){
		$hotel_id 	= json_decode(base64_decode($hotel_id1));
		if($hotel_id != ''){
			$this->hotels_model->inactive_hotel($hotel_id);
		}
		redirect('hotels/hotel_crs_list','refresh');
	}
	
	function active_hotel($hotel_id1){
		$hotel_id 	= json_decode(base64_decode($hotel_id1));
		if($hotel_id != ''){
			$this->hotels_model->active_hotel($hotel_id);
		}
		redirect('hotels/hotel_crs_list','refresh');
	}

	function edit_hotel($hotel_id1)
	{ 
			// debug($hotel_id1);exit;
		// error_reporting(E_ALL);
		$hotel_id 	= json_decode(base64_decode($hotel_id1));
		if($hotel_id != '')
		{
			// $hotels 						= $this->hotels_model->getHomePageSettings();
			$hotels['hotel_id'] = $hotel_id; 
			$hotels['hotels_data'] 			= $this->hotels_model->get_hotel_data($hotel_id)->row();
			$hotels['hotel_types_list'] 	= $this->hotels_model->get_hotel_types_list();
			$hotels['country'] = $this->hotels_model->get_country_details_hotel();
			$hotels['hotel_amenities_list'] 	= $this->hotels_model->get_ammenities_list();
// 			debug($hotels['hotels_data']);die;
			$this->template->view('hotel/hotel_crs/edit_hotel',$hotels);
		}else{
			redirect('hotels/hotel_crs_list','refresh');
		}
	}
	
	function update_hotel($hotel_id1)
	{
		debug($_POST);die;

		$hotel_id 	= $hotel_id1; 
		if($hotel_id != ''){
			if(count($_POST) > 0){
			  //  debug($_FILES);exit;
				$thumb_image  = $_REQUEST['thumb_image_old']; 
				$thumb_image = $this->hotels_model->upload_image_lgm($_FILES, 'hotel_images', $_REQUEST['thumb_image_old']);
				//debug("stop");exit;
				//$thumb_image = $this->General_Model->upload_image_lgm($_FILES, 'hotel_images','thumb_image',$_REQUEST['thumb_image_old']);
				//echo '<pre>'; print_r($thumb_image); exit();
				$hotel_image = ''; 
				$hotel_image1 = explode(',',$_REQUEST['hotel_image_old']);
				foreach($hotel_image1 as $hotelimage){
					$hotel_image .= $hotelimage.',';
				}
				 //echo '<pre/>';print_r($hotel_image);exit;
				for($i=0; $i< count($_FILES['hotel_image']['name']); $i++){
					$hotel_image .= $this->hotels_model->upload_images_all($_FILES, 'hotel_images','hotel_image',$hotel_image1[$i],$i);
					$hotel_image .= ",";
				}
				$hotel_image = rtrim($hotel_image,",");
			
				$this->hotels_model->update_hotel($_POST,$hotel_id,$thumb_image,$hotel_image);
				//	debug($_POST);exit;
				$this->hotels_model->update_hotel_contact_details($_POST,$hotel_id);
				redirect('hotels/hotel_crs_list','refresh');
			}else if($hotel_id!=''){
				redirect('hotels/edit_hotel'.$hotel_id,'refresh');
			}else{
				redirect('hotels/hotel_crs_list','refresh');
			}
		}else{
			redirect('hotels/hotel_crs_list','refresh');
		}
	}

	function delete_hotel($hotel_id1){
		$hotel_id 	= json_decode(base64_decode($hotel_id1));
		if($hotel_id != ''){
			$this->hotels_model->delete_hotel($hotel_id);
			set_delete_message();
		}
		redirect('hotels/hotel_crs_list','refresh');
	}

	function manage_hotelchildgroup($hotel_id1){
		// debug('ok');exit();
		$hotel_id = json_decode(base64_decode($hotel_id1));
		// debug($_POST);
		
		if($hotel_id != ''){
			if(count($_POST) > 0) {
				if(isset($_POST['child_group_a']) && $_POST['child_group_a'] == ''){
		          $data['error']['child_group_a-error'] = "Enter Valid Age Group";
		        }
		        if(isset($_POST['child_group_b']) && $_POST['child_group_b'] == ''){
		          $data['error']['child_group_b-error'] = "Enter Valid Age Group";
		        }
		        // if(isset($_POST['child_group_c']) && $_POST['child_group_c'] == ''){
		        //   $data['error']['child_group_c-error'] = "Enter Valid Age Group";
		        // }
		        // if(isset($_POST['child_group_d']) && $_POST['child_group_d'] == ''){
		        //   $data['error']['child_group_d-error'] = "Enter Valid Age Group";
		        // }
		        // if(isset($_POST['child_group_e']) && $_POST['child_group_e'] == ''){
		        //   $data['error']['child_group_e-error'] = "Enter Valid Age Group";
		        // }
				if(isset($_POST['child_group_a']) && $_POST['child_group_a'] != ''){
					// debug($_POST['child_group_a']);exit();
			   $agegroup_a = $this->Validation_Model->numberWithHyphen($_POST['child_group_a']);
			   // debug($agegroup_a);exit();
			   		if(!$agegroup_a){
						   $data['error']['child_group_a-error'] = "Enter Valid Age Group";
					 }
			}

			if(isset($_POST['child_group_b']) && $_POST['child_group_b'] != ''){
			   $agegroup_a = $this->Validation_Model->numberWithHyphen($_POST['child_group_b']);	
			    if(!$agegroup_a){
				   $data['error']['child_group_b-error'] = "Enter Valid Age Group";
			 }
			}

			/*if(isset($_POST['child_group_c']) && $_POST['child_group_c'] != ''){
			   $agegroup_a = $this->Validation_Model->numberWithHyphen($_POST['child_group_c']);	
			    if(!$agegroup_a){
				   $data['error']['child_group_c-error'] = "Enter Valid Age Group";
			 }
			}

			if(isset($_POST['child_group_d']) && $_POST['child_group_d'] != ''){
			   $agegroup_a = $this->Validation_Model->numberWithHyphen($_POST['child_group_d']);	
			    if(!$agegroup_a){
				   $data['error']['child_group_d-error'] = "Enter Valid Age Group";
			 }
			}

			if(isset($_POST['child_group_e']) && $_POST['child_group_e'] != ''){
			   $agegroup_a = $this->Validation_Model->numberWithHyphen($_POST['child_group_e']);	
			    if(!$agegroup_a){
				   $data['error']['child_group_e-error'] = "Enter Valid Age Group";
			 }
			}*/

			
			if(isset($data['error']) && count($data['error']) > 0) {
				 $data['status']['status'] = 3;
				print  json_encode(array_merge($data['error'], $data['status']));
				return ;
			}
				
				$this->hotels_model->manage_child_group($_POST, $hotel_id);
				$this->hotels_model->update_wizard_status($hotel_id,'step2');
				$data['status'] = 1;
				
				$data['success_url'] = site_url()."/hotels/hotel_room_types/".base64_encode(json_encode($hotel_id));

				// debug($data);
				// exit("dsf");
				
				// print  json_encode($data);
				// return;
				echo json_encode($data);
				die;
				//redirect('hotels/hotel_crs_list', 'referesh');
			}
			$hotels 					= $this->hotels_model->getHomePageSettings();
			
			$hotels['hotels_list'] 		= $this->hotels_model->get_hotel_crs_list($hotel_id, $hotels);
			$wizard_status=$this->hotels_model->get_wizard($hotel_id);
			$wizard_status = $wizard_status[0]['wizard_status'] ;
			$wiz=explode(',', $wizard_status);
				foreach ($wiz as $wkey => $wvalue) {
					$w[]=$wvalue;
				}
			$hotels['wizard_status']=$w;
			$hotel_details=json_decode(json_encode($hotels['hotels_list'][0]),true);
			$hotels['hotel_name']=$hotel_details['hotel_name'];
			// debug($hotels);exit;
		    $this->template->view('hotel/hotel_crs/manage_childgroup', $hotels);
			
		}else {
			redirect('hotels/hotel_crs_list', 'referesh');
		}
	}

	//Adding Hotel

	//Hotel Room Types
	function hotel_room_types($hotel_id1 = "")
	{
		$hotel_id = "";
		if($hotel_id1 != "")
	 	  $hotel_id 	= json_decode(base64_decode($hotel_id1));		

		$room_types 						= $this->hotels_model->getHomePageSettings();
		$room_types['room_types_list'] 		= $this->hotels_model->get_hotel_room_types_list($hotel_id, $room_types);
		$room_types['hotel_id'] = $hotel_id1;
		// debug($hotel_id);exit;
		$wizard_status=$this->hotels_model->get_wizard($hotel_id);
		$wizard_status = $wizard_status[0]['wizard_status'] ;
		$wiz=explode(',', $wizard_status);
			foreach ($wiz as $wkey => $wvalue) {
				$w[]=$wvalue;
			}
		$room_types['wizard_status']=$w;
		$hotels 					= $this->hotels_model->getHomePageSettings();
			
		$hotels['hotels_list'] 		= $this->hotels_model->get_hotel_crs_list($hotel_id, $hotels);
		
		$hotel_details=json_decode(json_encode($hotels['hotels_list'][0]),true);
		$room_types['hotel_name']=$hotel_details['hotel_name'];
		// debug($room_types);exit;
		$this->template->view('hotel/room_types/room_types_list',$room_types);		
	}
	
	function inactive_room_type($room_type_id1,$hotel_id1 = ""){
		$room_type_id 	= json_decode(base64_decode($room_type_id1));
		if($room_type_id != ''){
			$this->hotels_model->inactive_room_type($room_type_id);
		}

		if($hotel_id1 == ""){	 	  		
			redirect('hotels/room_types','refresh');
		}
		else{	
			redirect('hotels/hotel_room_types/'.$hotel_id1,'refresh');
		}	
	}
	
	function active_room_type($room_type_id1,$hotel_id1 = ""){
		$room_type_id 	= json_decode(base64_decode($room_type_id1));
		if($room_type_id != ''){
			$this->hotels_model->active_room_type($room_type_id);
		}
		if($hotel_id1 == ""){	 	  		
			redirect('hotels/room_types','refresh');
		}
		else{	
			redirect('hotels/hotel_room_types/'.$hotel_id1,'refresh');
		}	
	}
	
	function edit_room_type($room_type_id1, $hotel_id1 = ""){
		$room_type_id 	= json_decode(base64_decode($room_type_id1));	
		if($room_type_id != ''){
			$room_types 						= $this->hotels_model->getHomePageSettings();
			$room_types['hotels_list'] 		    = $this->hotels_model->get_hotel_crs_list('', $room_types);
			//$room_types['room_types_list'] 		= $this->hotels_model->get_room_types_list($room_type_id);
			$room_types['room_types_list'] 		= $this->hotels_model->get_room_type_detail_count($room_type_id);
			$room_types['room_types_booked'] = $this->hotels_model->get_room_type_detail_booked_count($room_type_id);
			// echo '<pre>'; print_r($room_types['hotels_list']); exit();
			$room_types['additional_meals']		= $this->hotels_model->get_additional_meal_roomtype($room_type_id);				
		// echo $room_type_id;	 exit();
			if($hotel_id1 != ""){	 	  		
	 	  		$room_types['hotel_id'] = str_ireplace('"', '', base64_decode($hotel_id1));
	 	  	}	 	  	
	 	  	$room_types['hotel_id_rec'] = $this->hotels_model->get_hotel_id_roomtype($room_type_id); 		 	  	
	 	  	$room_types['room_type_id_from'] = $room_type_id;
	 	  	//$room_types['hotel_types_list'] 	= $this->hotels_model->get_hotel_types_list();		
	 	  	$room_types['room_types'] 	= $this->hotels_model->get_active_room_types_list();		 	  	
	 	  	//echo $room_type_id."<pre>"; print_r($room_types['hotel_id_rec']);exit();
	 	  	// debug($room_types);die;
			$this->template->view('hotel/room_types/edit_room_type',$room_types);
		}else{
			
		}
	}
	
	function update_room_type($room_type_id1,$hotel_id1 = ""){		
		//echo '<pre>'; print_r($_POST['rooms_tot_units_booked']); exit();
		$room_type_id 	= json_decode(base64_decode($room_type_id1));		
		if($room_type_id != ''){
			if(count($_POST) > 0){				
			//print_r($_POST);exit();
				//debug(base64_decode($hotel_id1));exit;
			$this->form_validation->set_rules('room_type_name', 'Room Name', 'required|min_length[3]|max_length[50]');
			$this->form_validation->set_rules('rooms_tot_units', 'Total number of room', 'required|numeric|min_length[1]|max_length[3]');
			$this->form_validation->set_rules('adult', 'Adult', 'required|numeric|min_length[1]|max_length[2]|callback_check_adult_value');
			$this->form_validation->set_rules('child', 'Child', 'required|numeric|min_length[1]|max_length[2]');
			$this->form_validation->set_rules('max_pax', 'Capacity', 'required|min_length[1]|max_length[2]');

			if($this->input->post('extra_bed') == ""){
			 $this->form_validation->set_rules('extra_bed_count', 'Extra bed count', 'required|numeric|min_length[1]|max_length[2]|callback_check_extrabed');
		    }	
			$this->form_validation->set_rules('room_info', 'Room Description', 'required|min_length[10]');
			//$this->form_validation->set_rules('cancellation_policy', 'Policy', 'required|min_length[10]');

			/*if($this->input->post('chk_breakfast') != ""){				
			 $this->form_validation->set_rules('break_fast_p', 'Break Fast Price', 'required|numeric|min_length[1]|max_length[5]|callback_check_price');
		    }

		    if($this->input->post('chk_half') != ""){
			 $this->form_validation->set_rules('half_board_p', 'Half Board Price', 'required|numeric|min_length[1]|max_length[5]|callback_check_price');
		    }

		    if($this->input->post('chk_full') != ""){
			 $this->form_validation->set_rules('full_board_p', 'Full Board Price', 'required|numeric|min_length[1]|max_length[5]|callback_check_price');
		    }

		    if($this->input->post('chk_dinner') != ""){
			 $this->form_validation->set_rules('dinner_p', 'Dinner Price', 'required|numeric|min_length[1]|max_length[5]|callback_check_price');
		    }

		    if($this->input->post('chk_lunch') != ""){
			 $this->form_validation->set_rules('lunch_p', 'Lunch Price', 'required|numeric|min_length[1]|max_length[5]|callback_check_price');
		    }

		    if($this->input->post('oth_meals_flag') != ""){
		     $this->form_validation->set_rules('mealtype_name', 'Meals Name', 'callback_check_mealsname');		 
			 $this->form_validation->set_rules('mealtype_price', 'Meals Price', 'callback_check_price');
		    }*/

			
			    $return_data = $this->hotels_model->get_table_data('room_uploaded_image', 'hotel_room_type', 'hotel_room_type_id', $room_type_id);
			    // debug($return_data);exit();
         		$image = "";
          		if ($return_data != "") {
            		foreach ($return_data as $row) {
                		$image = $row->room_uploaded_image;
            		}
          		}          		

          		if(strlen($image) > 0){
          			$hotel_image = $image.",";
          		}
          		else{
          		  $hotel_image = $image;
          		}  
					for($i=0; $i<count($_FILES['hotel_image']['name']); $i++){
						if(!empty($_FILES['hotel_image']['name'][$i]))
						{	
							if(is_uploaded_file($_FILES['hotel_image']['tmp_name'][$i])) 
							{
								if(isset($hotel_image1[$i]) && $hotel_image1[$i]!=''){
									$oldImage = "uploads/room/".$hotel_image1[$i];
									//unlink($oldImage);
								}
								$sourcePath = $_FILES['hotel_image']['tmp_name'][$i];
								$img_Name	= time().$_FILES['hotel_image']['name'][$i];
								$targetPath = "uploads/room/".$img_Name;
								if(move_uploaded_file($sourcePath,$targetPath)){
									$hotel_image .= $img_Name.",";
								}
							}				
						}
					}

				if(strlen($hotel_image) > 0){	
			 	  $hotel_image = rtrim($hotel_image,",");			 	  
			 	}			 	  
				$this->hotels_model->update_room_type($_POST,$room_type_id,$hotel_image);
			 	// debug($_POST);die;
				//$this->hotels_model->update_room_type_count($_POST['rooms_tot_units_booked'], $room_type_id);
				if($hotel_id1 == ""){	 	  		
					redirect('hotels/room_types','refresh');
				}
				else{		
					redirect('hotels/hotel_room_types/'.base64_encode(json_encode($hotel_id1)),'refresh');
				}					   
			  //}//else 		
			}//if count			
			else if($room_type_id !=''){
				redirect('hotels/edit_room_type/'.$room_type_id1."/".$hotel_id1,'refresh');
			}
			else{
				redirect();
			}
		}//ifroom_type_id
		else{		
			if($hotel_id1 == ""){	 	  		
				redirect('hotels/room_types','refresh');
			}
			else{	
				redirect('hotels/hotel_room_types/'.$hotel_id1,'refresh');
			}
		}	
					
	}
	
	function delete_room_type($room_type_id1){
		$room_type_id 	= json_decode(base64_decode($room_type_id1));
		if($room_type_id != ''){
			$this->hotels_model->delete_room_type($room_type_id);
		}
		redirect('hotels/room_types','refresh');
	}
	//End Hotel Room Types
	function add_hotel()
	{ 
		// error_reporting(E_ALL);
			$hotels							= $this->hotels_model->getHomePageSettings();
			$hotels['hotel_types_list'] 	= $this->hotels_model->get_hotel_types_list();
			$hotels['hotel_amenities_list'] 	= $this->hotels_model->get_ammenities_list();
			$hotels['settings'] 			= $this->hotels_model->get_hotel_settings_list();
			$hotels['country'] = $this->hotels_model->get_country_details_hotel();	
			$this->template->view('hotel/hotel_crs/add_hotel',$hotels);
	}
	function save_hotel_data()
	{
		try 
		{
			// debug($_POST);die;
			if(empty($_POST))
			{
				throw new Exception("Hotel data required", 1);				
			}
			// if(empty($_FILES))
			// {
			// 	throw new Exception("Hotel data required", 1);				
			// }
			$this->form_validation->set_rules('hotel_type', 'Hotel Type', 'required');
			$this->form_validation->set_rules('hotel_name', 'Hotel Name', 'required');
			$this->form_validation->set_rules('star_rating', 'Star Rating', 'required');
			$this->form_validation->set_rules('hotel_description', 'Hotel Description', 'required');
			$this->form_validation->set_rules('country', 'Country', 'required');
			$this->form_validation->set_rules('city_name', 'City', 'required');
			$this->form_validation->set_rules('ammenities', 'Hotel Amenities', 'required');
			$this->form_validation->set_rules('hotel_address', 'Hotel Address', 'required');
			$this->form_validation->set_rules('latitude', 'Lattitude', 'required');
			$this->form_validation->set_rules('longitude', 'Longtitude', 'required');
			$this->form_validation->set_rules('postal_code', 'Postal code', 'required');
			$this->form_validation->set_rules('phone_number', 'Phone number', 'required');
			// $this->form_validation->set_rules('fax_number', 'Fax number', 'required');
			$this->form_validation->set_rules('email', 'Hotel Email', 'required');
			if ($this->form_validation->run() == FALSE)
			{
				throw new 	Exception(validation_errors(), 1);
			}
			$data['hotel_type_id']=$this->input->post('hotel_type');
			$data['hotel_name']=$this->input->post('hotel_name');
			$data['star_rating']=$this->input->post('star_rating');
			$data['contract_expires_in']=$this->input->post('exclude_checkout_date');
			$data['hotel_description']=$this->input->post('hotel_description');
			$data['country']=$this->input->post('country');
			$data['city']=$this->input->post('city_name');
			$data['amenities']=implode(',',$this->input->post('ammenities'));
			$data['hotel_address']=$this->input->post('hotel_address');
			$data['lattitude']=$this->input->post('latitude');
			$data['longtitude']=$this->input->post('longitude');
			$data['postal_code']=$this->input->post('postal_code');
			$data['phone_number']=$this->input->post('phone_number');
			$data['fax_number']=$this->input->post('fax_number');
			$data['email']=$this->input->post('email');
			$data['created_by']=$this->entity_user_id;


			if(isset($_FILES['image'])==false)
	 		{
	 			throw new 	Exception("Please select image", 1);	 			
	 		}
			$config['upload_path']          = DOMAIN_HOTEL_UPLOAD_DIR;
	        $config['allowed_types']        = 'gif|jpg|png';
	        //$config['max_size']             = 1024;
	        //$config['max_width']            = 1024;
	        //$config['max_width']            = 1024;
	        $config['encrypt_name']         = TRUE;
//debug($config);die;
	        $this->load->library('upload', $config); 

	        if (!$this->upload->do_upload('image'))
	        {
	        	throw new 	Exception($this->upload->display_errors(), 1);
	        }

			$image=$this->upload->data();
	        $data['image']=$image['file_name'];
	        // debug($data['image']);die;




			$result= $this->hotels_model->save_hotel_data($data);
			if($result==false)
			{
					throw new 	Exception("Hotel details adding failed", 1);					
			}
			if($_POST['submit']=='Save')
			{
				$this->session->set_flashdata('success_message','Hotel Details Added Successfully');
					redirect('hotels/hotel_crs_list','refresh');
			}
			if($_POST['submit']=='Continue')
			{
				redirect('hotels/hotel_crs_images/'.$result,'refresh');
			}

			// debug($data);die;
		} 
		catch (Exception $e) 
		{
			echo $e->getMessage();die;
			$this->session->set_flashdata('error_message',$e->getMessage());
			redirect('hotels/add_hotel','refresh');
		}
		// debug($_POST);die;
	}
	
	function update_hotel_datas($update_id="")
	{
		try 
		{
			
			if(empty($_POST))
			{
				throw new Exception("Hotel data required", 1);				
			}
			// if(empty($_FILES))
			// {
			// 	throw new Exception("Hotel data required", 1);				
			// }
			$this->form_validation->set_rules('hotel_type', 'Hotel Type', 'required');
			$this->form_validation->set_rules('hotel_name', 'Hotel Name', 'required');
			$this->form_validation->set_rules('star_rating', 'Star Rating', 'required');
			$this->form_validation->set_rules('hotel_description', 'Hotel Description', 'required');
			$this->form_validation->set_rules('country', 'Country', 'required');
			$this->form_validation->set_rules('city_name', 'City', 'required');
			$this->form_validation->set_rules('ammenities', 'Hotel Amenities', 'required');
			$this->form_validation->set_rules('hotel_address', 'Hotel Address', 'required');
			$this->form_validation->set_rules('latitude', 'Lattitude', 'required');
			$this->form_validation->set_rules('longitude', 'Longtitude', 'required');
			$this->form_validation->set_rules('postal_code', 'Postal code', 'required');
			$this->form_validation->set_rules('phone_number', 'Phone number', 'required');
			//$this->form_validation->set_rules('fax_number', 'Fax number', 'required');
			$this->form_validation->set_rules('email', 'Hotel Email', 'required');
			if ($this->form_validation->run() == FALSE)
			{
				throw new 	Exception(validation_errors(), 1);
			}
			$data['hotel_type_id']=$this->input->post('hotel_type');
			$data['hotel_name']=$this->input->post('hotel_name');
			$data['star_rating']=$this->input->post('star_rating');
			$data['contract_expires_in']=$this->input->post('exclude_checkout_date');
			$data['hotel_description']=$this->input->post('hotel_description');
			$data['country']=$this->input->post('country');
			$data['city']=$this->input->post('city_name');
			$data['amenities']=implode(',',$this->input->post('ammenities'));
			$data['hotel_address']=$this->input->post('hotel_address');
			$data['lattitude']=$this->input->post('latitude');
			$data['longtitude']=$this->input->post('longitude');
			$data['postal_code']=$this->input->post('postal_code');
			$data['phone_number']=$this->input->post('phone_number');
			$data['fax_number']=$this->input->post('fax_number');
			$data['email']=$this->input->post('email');
		
			if($_FILES['image']['size']>0)
	 		{
				//echo "test3";die;
			$config['upload_path']          = DOMAIN_HOTEL_UPLOAD_DIR;
	        $config['allowed_types']        = 'gif|jpg|png';
	        $config['max_size']             = 1024;
	        $config['max_width']            = 1024;
	        $config['max_width']            = 1024;
	        $config['encrypt_name']         = TRUE;

	        $this->load->library('upload', $config);

	        if (!$this->upload->do_upload('image'))
	        {
	        	throw new 	Exception($this->upload->display_errors(), 1);
	        }

			$image=$this->upload->data();
	        $data['image']=$image['file_name'];
	       }
		//echo "asdf";die;
			if(true)
			{
			//	echo "asdf";die;
			    $result= $this->hotels_model->update_hotel_data($data,$update_id);
			    if($result==false)
    			{
    			 throw new 	Exception("Hotel details updating failed", 1);					
    			}
				$this->session->set_flashdata('success_message','Hotel Details Updated Successfully');
					redirect('hotels/hotel_crs_list','refresh');
			}
	

			// debug($data);die;
		} 
		catch (Exception $e) 
		{
			// echo $e->getMessage();die;
			$this->session->set_flashdata('error_message',$e->getMessage());
			$update_id = json_encode(base64_encode($update_id));
			redirect("hotels/edit_hotel/{}",'refresh');
		}
		// debug($_POST);die;
	}
	function hotel_crs_images($hotel_id=0)
	{
		if($hotel_id=="")
		{
			redirect('hotels/hotel_crs_list','refresh');
		}
		$hotel=$this->hotels_model->get_hotel_data($hotel_id);
		if($hotel->num_rows()<1)
		{
			redirect('hotels/hotel_crs_list','refresh');			
		}
		$images['hotel_data']=$hotel->row();
		$images['images'] = $this->hotels_model->get_hotel_images($hotel_id);	
		$this->template->view('hotel/hotel_crs/add_images',$images);
	}
	function delete_hotel_images($post=0,$id=0)
	{
		$this->hotels_model->delete_hotel_images($id);	
		redirect("hotels/hotel_crs_images/{$post}",'refresh');
	}

	function get_city_name($country_id = "",$selected_city = ""){
	

        if (($country_id) != "") {
            $result = $this->hotels_model->get_active_city_list_hotel($country_id);
            if ($result != "") {
                foreach ($result as $row) {
                    ?>
                    <option value="<?=$row->city_name?>" <?=$row->city_name == urldecode($selected_city) ? "selected" : "" ?>><?=$row->city_name?></option>
                    <?php

                    //$options .= '<option value="' . $row->city_name . '" >' . $row->city_name . '</option>';
                }//for
            }//if 
        }
       // echo $options;
	}

	function show_hotel_details($hotel_id1){ //error_reporting(E_ALL);
	    $hotel_id 	= json_decode(base64_decode($hotel_id1));
		if($hotel_id != ''){
			$hotels 					= $this->hotels_model->getHomePageSettings();
			$hotels['hotels_list'] 		= $this->hotels_model->get_hotel_crs_list($hotel_id, $hotels);
			$hotels['contatc_list'] 	= $this->hotels_model->get_contatc_list($hotel_id);
			$hotels['room_list'] 		= $this->hotels_model->get_room_list($hotel_id);
			$hotels['room_count_info'] 	= $this->hotels_model->get_room_count_info($hotel_id);
			$hotels['room_rate_info'] 	= $this->hotels_model->get_room_rate_info($hotel_id);
			$hotels['hotel_id'] 		= $hotel_id;
			$hotels['country'] = $this->hotels_model->get_country_details_hotel("");			
			//echo 'ddddddddd<pre/>';print_r($hotels['room_list']);exit;
			$this->template->view('hotel/hotel_crs/show_hotel',$hotels);
		}else{
			redirect('hotels/hotel_crs_list','refresh');
		}
	}
	function activate_multiple_hotels(){
		if(isset($_POST) && isset($_POST['data'])){
			
			$sql = "update hotel_details set status = 'ACTIVE' where hotel_details_id in (".$_POST['data'].")";
			$this->db->query($sql);
			$data['status'] = 1;
			$data['success_url'] = site_url()."/hotels/hotel_crs_list";
			print  json_encode($data);
			return;
		}
	}
	function inactivate_multiple_hotels(){
		if(isset($_POST) && isset($_POST['data'])){
			
			$sql = "update hotel_details set status = 'INACTIVE' where hotel_details_id in (".$_POST['data'].")";
			$this->db->query($sql);
			$data['status'] = 1;
			$data['success_url'] = site_url()."/hotels/hotel_crs_list";
			print  json_encode($data);
			return;
		}
	}

	function pre_cancellation($app_reference, $booking_source, $status = '')
	{
		if (empty($app_reference) == false && empty($booking_source) == false) {
			$page_data = array();


			// debug($booking_source);exit();
			// debug(CRS_HOTEL_BOOKING_SOURCE);exit();
			if($booking_source==CRS_HOTEL_BOOKING_SOURCE){

					if($app_reference!=''){

						// debug($app_reference);exit();
						$resp = $this->hotels_model->hotel_crs_cancel($app_reference);
						// debug($resp);exit;
						$resp2 = json_decode($resp,true);
						//debug($resp2);exit;
						if($resp2['status']==1){
							/*sending mail to customer*/
							$this->load->model ( 'hotel_model' );
							$booking_details = $this->hotels_model->getBookingDetails($app_reference);
							$email = $booking_details[0]->email_id;
							$voucher_details['other'] = $this->hotels_model->get_voucher_details($app_reference);
							// $this->load->library('provab_pdf');
							// $this->load->library('provab_mailer');
							// $create_pdf = new Provab_Pdf();
							// $mail_template = $this->template->isolated_view('voucher/hotels_voucher_cancel', $voucher_details);
							// $pdf = $create_pdf->create_pdf($mail_template,'');
							// $this->provab_mailer->send_mail($email, domain_name().' - Hotel Voucher',$mail_template ,$pdf);
							/*$this->load->library('provab_mailgun');
							$mail_status = $this->provab_mailgun->send_mail($email, 'Reservation Services -- Hotel Voucher',$mail_template );*/
							// echo '<script>alert("Hotel Booking Cancelled");window.close();</script>';
							redirect(base_url().'index.php/report/b2c_villa_report');

						}
					}else{
						 $response = ['status'=>0,"msg"=>"Error while cancelling room!!"];
						 echo json_encode($response); 
					}
					
			}else{
				// echo 1;
				redirect('security/log_event?event=Invalid Details');
			}
			
		} else {
			// echo 2;
			redirect('security/log_event?event=Invalid Details');
		}
	}

	function test_wizard()
	{
		$hotel_id='11';
		$status='step5';
		$this->hotels_model->update_wizard_status($hotel_id,$status);
		echo $this->db->last_query(); exit;
	}

	//End Of Adding Hotel

    public function quotation_list()
    {
        if (!check_user_previlege('106')) {
            set_update_message("You Don't have permission to do this action.", WARNING_MESSAGE, array(
                'override_app_msg' => true
            ));
            redirect(base_url());
        }
        // $order_by = array('id' => 'DESC');
        // $quotation_list = $this->custom_db->single_table_records('tours_quotation_log', $cols = '*', $condition = array(), $offset = 0, $limit = 100000000,$order_by);
        // $page_data['quotation_list'] = $quotation_list['data'];
        $query = 'SELECT tql.*, u.title as a_title,u.first_name as a_f_name,u.last_name as a_l_name,t.package_name FROM tours_quotation_log AS tql LEFT JOIN tours AS t ON tql.tour_id = t.id  LEFT JOIN user AS u ON tql.created_by_id = u.user_id ORDER BY tql.id DESC';
        $quotation_list = $this->custom_db->get_result_by_query($query);
        $page_data['quotation_list'] = json_decode(json_encode($quotation_list),true);
        $this->template->view('tours/quotation_list',$page_data);
        $array = array(
            'back_link' => base_url().$this->router->fetch_class().'/'.$this->router->fetch_method()
        );
        $this->session->set_userdata( $array );
    }

    public function hotels_enquiry() 
    {
    	//error_reporting(E_ALL);
//debug("test");exit;
        if (!check_user_previlege('106')) {
            set_update_message("You Don't have permission to do this action.", WARNING_MESSAGE, array(
                'override_app_msg' => true
            ));
            redirect(base_url());
        }
       // debug("test");exit;
		$this->load->model('hotels_model');
        /*$total_records = $this->hotels_model->hotels_enquiry();*/
        $condition=array();
        $total_records = $this->hotels_model->hotels_enquiry($condition);
       // debug($total_records);exit;
        //echo "in";
        // $tours_enquiry = $this->hotels_model->hotels_enquiry();
        // debug($total_records);die;

        // $page_data['tours_enquiry'] = $tours_enquiry['tours_enquiry'];
        // $page_data['tour_list']          = $this->tours_model->tour_list();
        // $page_data['tours_itinerary']    = $this->tours_model->tours_itinerary_all();
        // $page_data['tours_country_name'] = $this->tours_model->tours_country_name();
        $this->template->view('hotel/hotel_crs/hotels_enquiry',$total_records);
        // $array = array(
        //   'back_link' => base_url().$this->router->fetch_class().'/'.$this->router->fetch_method()
        //   );
//        $this->session->set_userdata( $page_data );
    }


     //for adding agent remark
  //for adding agent remark
  
       public function add_agent_remark(){
	 	// $id = $this->input->post('r_id');
	 	$update_data = $this->input->post('agent_remark');
	 	$this->load->model('hotels_model');
	 	$where = ['id'=>$this->input->post('r_id')];
	 	$data = ['agent_remark'=> $update_data,'status'=>1];
	 	// echo json_encode($where);
	 	$status = $this->hotels_model->update_remark('hotels_enquiry', $data, $where);
	 	if ($status) {
	 		echo json_encode('success');
	 	}
	 }
	 function upload_hotel_image()
	 {
	 	try 
	 	{
	 		$hotel_id = $_POST['hotel_id'];
	 		if(isset($_FILES['hotel_image'])==false)
	 		{
	 			throw new 	Exception("Please select image", 1);	 			
	 		}
           

            $dataInfo = array();
            $files = $_FILES;
            $count = count($_FILES['hotel_image']['name']);
 			for($i=0;$i<$count;$i++)
 			{
 			$_FILES['file']['name']       = $files['hotel_image']['name'][$i];
            $_FILES['file']['type']       = $files['hotel_image']['type'][$i];
            $_FILES['file']['tmp_name']   = $files['hotel_image']['tmp_name'][$i];
            $_FILES['file']['error']      = $files['hotel_image']['error'][$i];
            $_FILES['file']['size']       = $files['hotel_image']['size'][$i];

			$config['upload_path']          = DOMAIN_HOTEL_UPLOAD_DIR;
	        $config['allowed_types']        = 'gif|jpg|png';
	        //$config['max_size']             = 1024;
	        //$config['max_width']            = 1024;
	        //$config['max_width']            = 1024;
	        $config['encrypt_name']         = TRUE;

	        $this->load->library('upload', $config);

	        if (!$this->upload->do_upload('file'))
	        {
	        	throw new 	Exception($this->upload->display_errors(), 1);
	        }
			$image=$this->upload->data();
	        $data['hotel_id']=$hotel_id;
	        $data['image']=$image['file_name'];
	        if($this->hotels_model->insert_hotel_image($data)==false)
	        {
	        	throw new 	Exception("Please select image", 1);        	
	        }
	        unset($data,$config,$_FILES);
	        }



        $this->session->set_flashdata('success_message','Image upload successfully');		

	 	} catch (Exception $e) 
	 	{
	 		echo $e->getMessage();die;
	 		$this->session->set_flashdata('error_message',$e->getMessage());	
	 	}
        
		redirect('hotels/hotel_crs_images/'.$hotel_id,'refresh');
	 }
	 function inactive_hotel_image($hotel_id=0,$image_id=0)
	 {	
		if($image_id != '')
		{
			$this->hotels_model->inactive_hotel_image($image_id);
		}
		$this->session->set_flashdata('success_message', 'Updated Successfully!!');
		redirect('hotels/hotel_crs_images/'.$hotel_id,'refresh');
	}
	function active_hotel_image($hotel_id=0,$image_id=0)
	{	
		if($image_id != '')
		{
			$this->hotels_model->active_hotel_images($image_id);
		}
		$this->session->set_flashdata('success_message', 'Updated Successfully!!');
		redirect('hotels/hotel_crs_images/'.$hotel_id,'refresh');
	}
	function delete_hotel_image($hotel_id=0,$image_id=0)
	{
		if($image_id!='')
		{
			$result = $this->hotels_model->delete_hotel_image($image_id);
		}
		redirect('hotels/hotel_crs_images/'.$hotel_id,'refresh');
	}
	function room_crs_list($hotel_id=0)
	{
	
			$hotels['rooms_list'] 	   		= $this->hotels_model->get_crs_room_list($hotel_id);
			$hotels['seasons_list'] 		= count($this->hotels_model->season_list($hotel_id)->result());
			$hotels['hotel_id'] 	   		= $hotel_id;
			// debug($hotels);die;
			
			$this->template->view('hotel/rooms/room_list',$hotels);
	}
	function active_room($hotel_id=0,$room_id)
	{
	
	$this->hotels_model->active_room($room_id);
	redirect($_SERVER['HTTP_REFERER']);

	}
		function inactive_room($hotel_id=0,$room_id)
	{
	
	$this->hotels_model->inactive_room($room_id);
	redirect($_SERVER['HTTP_REFERER']);

	}
	function add_room_details($hotel_id=0)
	{
		  //  echo $hotel_id;
		// error_reporting(E_ALL);
			$hotels['room_types_list'] 	= $this->hotels_model->get_room_types_list();
			$hotels['ammenities_list'] 		= $this->hotels_model->get_room_ammenities_list();
			$hotels['hotel_id'] 	   		= $hotel_id;
			$this->template->view('hotel/rooms/add_room',$hotels);
	}
	function edit_room($hotel_id=0,$room_id)
	{
		  //  echo $hotel_id;
		// error_reporting(E_ALL);
			$hotels['room_types_list'] 	= $this->hotels_model->get_room_types_list();
			$hotels['ammenities_list'] 		= $this->hotels_model->get_room_ammenities_list();
			$hotels['data'] 	   		= $this->hotels_model->get_crs_room($room_id)->row();
// 			debug($hotels['data']);die;
			$hotels['hotel_id'] 	   		= $hotel_id;
			$this->template->view('hotel/rooms/edit_room',$hotels);
	}
	
	function save_room_details_data()
	{
		try 
		{
			$hotel_id=$this->input->post('hotel_id');
			$data['hotel_id']=$this->input->post('hotel_id');
			$data['room_type_id']=$this->input->post('room_type_id');
			$data['max_stay']=$this->input->post('max_stay');
			$data['status']=$this->input->post('status');
			$data['max_adult_capacity']=$this->input->post('max_adult_capacity');
			$data['max_child_capacity']=$this->input->post('max_child_capacity');
			$data['extra_bed']=$this->input->post('extra_bed');
			$data['room_policy']=$this->input->post('room_policy');
			$data['room_description']=$this->input->post('room_description');
			$data['room_amenities']=implode(",",$this->input->post('room_amenities'));
			if($this->hotels_model->save_room_details_data($data)==false)
			{
				throw new 	Exception("Room details adding failed", 1);				
			}
			$this->session->set_flashdata('success_message','Room Details Added Successfully');	
		} 
		catch (Exception $e) 
		{
			$this->session->set_flashdata('error_message',$e->getMessage());	
		}
		redirect("hotels/room_crs_list/".$hotel_id,'refresh');
	}	
	function update_room($id="")
	{
		try 
		{
			$hotel_id=$this->input->post('hotel_id');
			$data['hotel_id']=$this->input->post('hotel_id');
			$data['room_type_id']=$this->input->post('room_type_id');
			$data['max_stay']=$this->input->post('max_stay');
			$data['status']=$this->input->post('status');
			$data['max_adult_capacity']=$this->input->post('max_adult_capacity');
			$data['max_child_capacity']=$this->input->post('max_child_capacity');
			$data['extra_bed']=$this->input->post('extra_bed');
			$data['room_policy']=$this->input->post('room_policy');
			$data['room_description']=$this->input->post('room_description');
			$data['room_amenities']=implode(",",$this->input->post('room_amenities'));
			if($this->hotels_model->update_room_details_data($data,$id)==false)
			{
				throw new 	Exception("Room details Updating failed", 1);				
			}
			$this->session->set_flashdata('success_message','Room Details Updated Successfully');	
		} 
		catch (Exception $e) 
		{
			$this->session->set_flashdata('error_message',$e->getMessage());	
		}
		redirect("hotels/room_crs_list/".$hotel_id,'refresh');
	}
	function room_price_list($hotel_id=0,$room_id=0)
	{
		$data['hotel_id']=$hotel_id;
		$data['room_id']=$room_id;
		$data['price_list'] 		= $this->hotels_model->room_price_list($hotel_id,$room_id)->result();
// 		echo $this->db->last_query();die;
// 		debug($data['price_list']);die;
		$this->template->view('hotel/rooms/room_price_list',$data);	
	}
	function add_room_price($hotel_id=0,$room_id=0)
	{
		$data['hotel_id']=$hotel_id;
		$data['room_id']=$room_id;
		$data['seasons'] 		= $this->hotels_model->season_list($hotel_id)->result();
		$this->template->view('hotel/rooms/add_price_info',$data);	
	}
		function active_room_price($hotel_id="",$id="")
	{
		$this->hotels_model->active_room_price($id);
		$this->session->set_flashdata('success_message','Room Price Activated');
		redirect($_SERVER['HTTP_REFERER']);
	}
	
	function inactive_room_price($hotel_id="",$id="")
	{
		$this->hotels_model->inactive_room_price($id);
		$this->session->set_flashdata('success_message','Room Price Deactivated');
		redirect($_SERVER['HTTP_REFERER']);
	}
	
	
	function save_room_price_data()
	{
		try 
		{
			$hotel_id=$this->input->post('hotel_id');
			$room_id=$this->input->post('room_id');
			if(empty($_POST))
			{
				throw new Exception("Room price required", 1);				
			}
// 			debug($_POST);die;
			//$this->form_validation->set_rules('date_from', 'From Date', 'required');
			$this->form_validation->set_rules('season', 'Season', 'required');
			$this->form_validation->set_rules('one_adult', 'Single Adult Price', 'required');
			$this->form_validation->set_rules('two_adult', 'Double Adult Price', 'required');
			$this->form_validation->set_rules('three_adult', 'Triple Adult Price', 'required');
			$this->form_validation->set_rules('child_price', 'Child Price', 'required');
			$this->form_validation->set_rules('min_stay', 'min_stay', 'required');
			$this->form_validation->set_rules('extrabed', 'extrabed', 'required');

			if ($this->form_validation->run() == FALSE)
			{
				throw new 	Exception(validation_errors(), 1);
			}
			$data['hotel_id']=$this->input->post('hotel_id');
			$data['room_id']=$this->input->post('room_id');
			//$data['date_from']=date ('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('date_from'))));
			//$data['date_to']=date ('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('date_to'))));
			$data['one_adult']=$this->input->post('one_adult');
			$data['season']=$this->input->post('season');
			$data['two_adult']=$this->input->post('two_adult');
			$data['three_adult']=$this->input->post('three_adult');
			$data['child_price']=$this->input->post('child_price');
			$data['status']=$this->input->post('status');
			$data['extrabed']=$this->input->post('extrabed');
			$data['extrabed_price']=$this->input->post('extrabed_price');
			$data['one_adult_breakfast']=$this->input->post('one_adult_breakfast');
			$data['two_adult_breakfast']=$this->input->post('two_adult_breakfast');
			$data['three_adult_breakfast']=$this->input->post('three_adult_breakfast');
			$data['child_breakfast']=$this->input->post('child_breakfast');
			$data['child_breakfast_age']=$this->input->post('child_breakfast_age');
			$data['min_stay']=$this->input->post('min_stay');
			$data['vat']=$this->input->post('vat');
			$data['service_charge']=$this->input->post('service_charge');
			// debug($data);die;
			$result= $this->hotels_model->save_room_price_data($data);
			if($result==false)
			{
					throw new 	Exception("Room price details adding failed", 1);					
			}
			$this->session->set_flashdata('success_message','Room Price Details Added Successfully');
			redirect('hotels/room_price_list/'.$hotel_id.'/'.$room_id,'refresh');
		} 
		catch (Exception $e) 
		{
			$this->session->set_flashdata('error_message',$e->getMessage());
			redirect('hotels/add_room_price/'.$hotel_id.'/'.$room_id,'refresh');
		}
		// debug($_POST);die;
	}

	function add_cancellation_policy($hotel_id=0,$room_id=0)
	{
		$data['hotel_id']=$hotel_id;
		$data['room_id']=$room_id;
		$this->template->view('hotel/rooms/add_cancellation_policy',$data);	
	}
	function save_cancellation_policy_data()
	{
		try 
		{
			// debug($_POST);die;
			$hotel_id=$this->input->post('hotel_id');
			$room_id=$this->input->post('room_id');
			if(empty($_POST))
			{
				throw new Exception("Cancellation policy required", 1);				
			}

			$this->form_validation->set_rules('cancel_before', 'Cancel before days', 'required');
			$this->form_validation->set_rules('penality', 'Penality', 'required');
			if ($this->form_validation->run() == FALSE)
			{
				throw new 	Exception(validation_errors(), 1);
			}
			$data['hotel_id']=$this->input->post('hotel_id');
			$data['room_id']=$this->input->post('room_id');
			$data['cancel_before']=$this->input->post('cancel_before');
			$data['cancel_to']=$this->input->post('cancel_to');
			$data['penality']=$this->input->post('penality');
			$result= $this->hotels_model->save_cancellation_policy_data($data);
			if($result==false)
			{
					throw new 	Exception("Cancellation policy details adding failed", 1);					
			}
			$this->session->set_flashdata('success_message','Cancellation policy Details Added Successfully');
			redirect('hotels/room_cancellation_list/'.$hotel_id.'/'.$room_id,'refresh');
		} 
		catch (Exception $e) 
		{
			$this->session->set_flashdata('error_message',$e->getMessage());
		}
		redirect('hotels/room_cancellation_list/'.$hotel_id.'/'.$room_id,'refresh');
	}
	function edit_room_price($id="")
	{
	    $data['data'] = $this->hotels_model->room_price_single($id)->row();
	   // echo $this->db->last_query();
	   // debug($data['data']);die;
	   $data['seasons'] 		= $this->hotels_model->season_list($data['data']->hotel_id)->result();
		$this->template->view('hotel/rooms/edit_price',$data);	
	}
		
	function update_room_price_data($id="")
	{
		try 
		{
			$hotel_id=$this->input->post('hotel_id');
			$room_id=$this->input->post('room_id');
			if(empty($_POST))
			{
				throw new Exception("Room price required", 1);				
			}
			$this->form_validation->set_rules('season', 'Season', 'required');
			$this->form_validation->set_rules('one_adult', 'Single Adult Price', 'required');
			$this->form_validation->set_rules('two_adult', 'Double Adult Price', 'required');
			$this->form_validation->set_rules('three_adult', 'Triple Adult Price', 'required');
			$this->form_validation->set_rules('child_price', 'Child Price', 'required');
			$this->form_validation->set_rules('min_stay', 'min_stay', 'required');
			$this->form_validation->set_rules('extrabed', 'extrabed', 'required');

			if ($this->form_validation->run() == FALSE)
			{
				throw new 	Exception(validation_errors(), 1);
			}
		
			$data['hotel_id']=$this->input->post('hotel_id');
			$data['room_id']=$this->input->post('room_id');
			//$data['date_from']=date ('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('date_from'))));
			//$data['date_to']=date ('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('date_to'))));
			$data['one_adult']=$this->input->post('one_adult');
			$data['season']=$this->input->post('season');
			$data['two_adult']=$this->input->post('two_adult');
			$data['three_adult']=$this->input->post('three_adult');
			$data['child_price']=$this->input->post('child_price');
			$data['status']=$this->input->post('status');
			$data['extrabed']=$this->input->post('extrabed');
			$data['extrabed_price']=$this->input->post('extrabed_price');
			$data['one_adult_breakfast']=$this->input->post('one_adult_breakfast');
			$data['two_adult_breakfast']=$this->input->post('two_adult_breakfast');
			$data['three_adult_breakfast']=$this->input->post('three_adult_breakfast');
			$data['child_breakfast']=$this->input->post('child_breakfast');
			$data['child_breakfast_age']=$this->input->post('child_breakfast_age');
			$data['min_stay']=$this->input->post('min_stay');
			$data['vat']=$this->input->post('vat');
			$data['service_charge']=$this->input->post('service_charge');
			// debug($data);die;
			$result= $this->hotels_model->update_room_price_data($data,$id);
			if($result==false)
			{
					throw new 	Exception("Room price details adding failed", 1);					
			}
			$this->session->set_flashdata('success_message','Room Price Details Added Successfully');
		
		} 
		catch (Exception $e) 
		{
			$this->session->set_flashdata('error_message',$e->getMessage());
		}
			redirect('hotels/room_price_list/'.$hotel_id.'/'.$room_id,'refresh');
	}
	
	
	
	
	
	
	
	
		function room_cancellation_list($hotel_id=0,$room_id=0)
	{
		$data['hotel_id']=$hotel_id;
		$data['room_id']=$room_id;
		$data['price_list'] 		= $this->hotels_model->room_cancellation_list($hotel_id,$room_id)->result();
// 		debug($data['price_list']);die;
		$this->template->view('hotel/rooms/room_cancellation_list',$data);	
	}
	function edit_cancellation_policy($id)
	{
	    $data['data'] 		= $this->hotels_model->room_cancellation_data($id)->row();
		$this->template->view('hotel/rooms/edit_cancellation_policy',$data);	
	}
	
	function active_room_cancel($id="")
	{
		$this->hotels_model->active_room_cancellation($id);
		$this->session->set_flashdata('success_message','Room Cancellation Activated');
		redirect($_SERVER['HTTP_REFERER']);
	}
	
	function inactive_room_cancel($id="")
	{
		$this->hotels_model->inactive_room_cancellation($id);
		$this->session->set_flashdata('success_message','Room Cancellation Deactivated');
		redirect($_SERVER['HTTP_REFERER']);
	}

	function update_cancellation_policy_data($id="")
	{
		try 
		{
			// debug($_POST);die;
			$hotel_id=$this->input->post('hotel_id');
			$room_id=$this->input->post('hotel_id');
			if(empty($_POST))
			{
				throw new Exception("Cancellation policy required", 1);				
			}

			$this->form_validation->set_rules('cancel_before', 'Cancel before days', 'required');
			$this->form_validation->set_rules('penality', 'Penality', 'required');
			if ($this->form_validation->run() == FALSE)
			{
				throw new 	Exception(validation_errors(), 1);
			}
			$data['hotel_id']=$this->input->post('hotel_id');
			$data['room_id']=$this->input->post('room_id');
			$data['cancel_before']=$this->input->post('cancel_before');
			$data['cancel_to']=$this->input->post('cancel_to');
			$data['penality']=$this->input->post('penality');
			$result= $this->hotels_model->update_cancellation_policy_data($data,$id);
			if($result==false)
			{
					throw new 	Exception("Cancellation policy details Updating failed", 1);					
			}
			$this->session->set_flashdata('success_message','Cancellation policy Details Updated Successfully');
			
		} 
		catch (Exception $e) 
		{
			$this->session->set_flashdata('error_message',$e->getMessage());
		}
		redirect('hotels/room_cancellation_list/'.$data['hotel_id'].'/'.$data['room_id'],'refresh');
	}
	
	//---------------------------------------------------------------------------------------//
	function season_list($hotel_id=0,$room_id=0)
	{
		$data['hotel_id']=$hotel_id;
		$data['room_id']=$room_id;
		$data['seasons_list'] 		= $this->hotels_model->season_list($hotel_id)->result();
		
		$this->template->view('hotel/seasons/seasons_list',$data);	
	}
	
	function add_season($hotel_id=0)
	{
		$data['hotel_id']=$hotel_id;
// 		$data['room_id']=$room_id;
// 		$data['latest_season_date'] = $this->hotels_model->season_date($hotel_id);
		$this->template->view('hotel/seasons/add_seasons',$data);	
	}
		function add_seasons()
	{
		try 
		{
			$hotel_id=$this->input->post('hotel_details_id');
			if(empty($_POST))
			{
				throw new Exception("Data required", 1);				
			}
			$this->form_validation->set_rules('seasons_from_date', 'From Date', 'required');
			$this->form_validation->set_rules('seasons_to_date', 'To Date', 'required');
			$this->form_validation->set_rules('hotel_details_id', 'hotel details_id', 'required|numeric');
			$this->form_validation->set_rules('seasons_name', 'seasons name', 'required');

			if ($this->form_validation->run() == FALSE)
			{
				throw new 	Exception(validation_errors(), 1);
			}
		
			$data['seasons_from_date']=date ('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('seasons_from_date'))));
			$data['seasons_to_date']=date ('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('seasons_to_date'))));
			$data['seasons_name']=$this->input->post('seasons_name');
			$data['hotel_details_id']=$this->input->post('hotel_details_id');

			// debug($data);die;
			$result= $this->hotels_model->insert_season($data);
			if($result==false)
			{
					throw new 	Exception("Data adding failed", 1);					
			}
			$this->session->set_flashdata('success_message','Data Added Successfully');
		
		} 
		catch (Exception $e) 
		{
			$this->session->set_flashdata('error_message',$e->getMessage());
		}
			redirect('hotels/season_list/'.$hotel_id,'refresh');
	}
	
		function update_seasons($id=null)
	{
		try 
		{
			$hotel_id=$this->input->post('hotel_details_id');
			if(empty($_POST))
			{
				throw new Exception("Data required", 1);				
			}
			$this->form_validation->set_rules('seasons_from_date', 'From Date', 'required');
			$this->form_validation->set_rules('seasons_to_date', 'To Date', 'required');
			$this->form_validation->set_rules('hotel_details_id', 'hotel details_id', 'required|numeric');
			$this->form_validation->set_rules('seasons_name', 'seasons name', 'required');

			if ($this->form_validation->run() == FALSE)
			{
				throw new 	Exception(validation_errors(), 1);
			}
		
			$data['seasons_from_date']=date ('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('seasons_from_date'))));
			$data['seasons_to_date']=date ('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('seasons_to_date'))));
			$data['seasons_name']=$this->input->post('seasons_name');

			// debug($data);die;
			$result= $this->hotels_model->update_season($data,$id);
			if($result==false)
			{
					throw new 	Exception("Data adding failed", 1);					
			}
			$this->session->set_flashdata('success_message','Data Added Successfully');
		
		}
		catch (Exception $e) 
		{
			$this->session->set_flashdata('error_message',$e->getMessage());
		}
			redirect('hotels/season_list/'.$hotel_id,'refresh');
	}
	
	function edit_seasons($id)
	{
	    $data['data'] 		= $this->hotels_model->season_data($id)->row();
	   // $data['latest_season_date'] = $this->hotels_model->season_date($data['data']->hotel_details_id);
		$this->template->view('hotel/seasons/edit_seasons',$data);	
	}
	
		
	function check_availability()
	{
		
	  $start = date("Y-d-m",strtotime($this->input->post('start')));
	  $end = date("Y-m-d",strtotime($this->input->post('end')));
	  $hotel_id = $this->input->post('hotel_id');
	  $id = $this->input->post('id');
	 $str=explode("/",$this->input->post('end'));
	 $str2=explode("/",$this->input->post('start'));
	// debug($str);die;
	  $count = $this->hotels_model->check_availability($str2[2]."-".$str2[1]."-".$str2[0],$str[2]."-".$str[1]."-".$str[0],$hotel_id,$id);
	  echo $count;
	}
    
    
    function active_seasons($id="")
    	{
    		$this->hotels_model->active_seasons($id);
    		$this->session->set_flashdata('success_message','Season Activated');
    		redirect($_SERVER['HTTP_REFERER']);
    	}
    
    function inactive_seasons($id="")
    	{
    		$this->hotels_model->inactive_seasons($id);
    		$this->session->set_flashdata('success_message','Season Inactivated');
    		redirect($_SERVER['HTTP_REFERER']);
    	}
    
    function delete_seasons($id="")
    	{
    		$this->hotels_model->delete_seasons($id);
    		$this->session->set_flashdata('success_message','Season deleted');
    		redirect($_SERVER['HTTP_REFERER']);
    	}




}
