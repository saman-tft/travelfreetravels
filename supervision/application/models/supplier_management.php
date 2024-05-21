<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
error_reporting(E_ALL);
/**
 *
 * @package Provab - Provab Application
 * @subpackage Travel Portal
 * @author Arjun J<arjunjgowda260389@gmail.com> on 01-06-2015
 * @version V2
 */
class Supplier_management extends CI_Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'domain_management_model' );
		$this->load->model ( 'supplier_management_model' );
		$this->load->library ( 'provab_mailer' );
	}
	public function report($module_type='b2c',$offset = 0)
	{
		$condition = array ();
		$get_data = $this->input->get ();
		if (! (isset ( $get_data ['month'] ) && isset ( $get_data ['year'] ))) 
		{
			$get_data ['created_datetime_from'] = date ( 'd-m-Y' );
			$get_data ['created_datetime_to'] = date ( 'd-m-Y' );

			$get_data ['month'] = date ('m');
			$get_data ['year'] = date ('Y');
		}		
		if (valid_array ( $get_data ) == true) 
		{
			$from_date = trim ( @$get_data ['created_datetime_from'] );
			$to_date = trim ( @$get_data ['created_datetime_to'] );

			$supplier = trim ( @$get_data ['supplier_name'] );

			if (empty ( $from_date ) == false && empty ( $to_date ) == false) {
				$valid_dates = auto_swipe_dates ( $from_date, $to_date );
				$from_date = $valid_dates ['from_date'];
				$to_date = $valid_dates ['to_date'];
			}
			/*if (empty ( $from_date ) == false) {
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
			}*/
			if (empty ( $get_data ['month'] ) == false && strtolower ( $get_data ['month'] ) != 'all')
			{
				$condition [] = array ('Month(BD.created_datetime)', '=', $get_data ['month']);
			}
			if (empty ( $get_data ['year'] ) == false && strtolower ( $get_data ['year'] ) != 'all')
			{
				$condition [] = array ('YEAR(BD.created_datetime)', '=', $get_data ['year']);
			}
			if (empty ( $get_data ['supplier_name'] ) == false && strtolower ( $get_data ['supplier_name'] ) != 'all')
			{
				$condition [] = array ('BD.supplier_id', '=', $supplier);
			}
			
			$page_data ['from_date'] = $from_date;
			$page_data ['to_date'] = $to_date;
			$page_data ['supplier_id'] = $supplier;
			$page_data ['month'] = $get_data ['month'];
			$page_data ['year'] = $get_data ['year'];
		}
		$condition [] = array (
						'BD.status',
						'=',
						"'BOOKING_CONFIRMED'" 
				);
		$offset = intval ( $offset );		
		$this->load->model('tours_model');
		
		$total_records = $this->supplier_management_model->supplier_price_report ( $condition, true );
		$table_data = $this->supplier_management_model->supplier_price_report ( $condition, false, $offset, RECORDS_RANGE_5 );

		$page_data ['table_data'] = $table_data ['data'];
		
		/*$total_payable_Details=$this->supplier_management_model->supplier_payable_details ( $condition, true );
		$payment_status=$this->supplier_management_model->supplier_amount_payment_status ( $get_data);
		$page_data ['supplier_amount_details'] = $total_payable_Details;
		$page_data ['payment_status'] = $payment_status;*/
		
		$x = count ( $table_data );
		$this->load->library ( 'pagination' );
		if (count ( $_GET ) > 0)
			$config ['suffix'] = '?' . http_build_query ( $_GET, '', "&" );
		$config ['base_url'] = base_url () . 'index.php/supplier_management/report/';
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
		$page_data['supplier_list'] = $this->custom_db->single_table_records ( 'user','user_id,first_name,last_name',array('user_type'=>'9') )['data'];		
		$page_data['module_type'] = $module_type;

		$this->template->view ( 'supplier_management/report', $page_data );	
	}
	public function supplier_report($module_type='b2c',$offset = 0)
	{
		$condition = array ();
		$get_data = $this->input->get ();
		if (! (isset ( $get_data ['month'] ) && isset ( $get_data ['year'] ))) 
		{
			$get_data ['created_datetime_from'] = date ( 'd-m-Y' );
			$get_data ['created_datetime_to'] = date ( 'd-m-Y' );

			$get_data ['month'] = date ('m');
			$get_data ['year'] = date ('Y');
		}		
		if (valid_array ( $get_data ) == true) 
		{
			$from_date = trim ( @$get_data ['created_datetime_from'] );
			$to_date = trim ( @$get_data ['created_datetime_to'] );

			$supplier = trim ( @$get_data ['supplier_name'] );

			if (empty ( $from_date ) == false && empty ( $to_date ) == false) {
				$valid_dates = auto_swipe_dates ( $from_date, $to_date );
				$from_date = $valid_dates ['from_date'];
				$to_date = $valid_dates ['to_date'];
			}
			/*if (empty ( $from_date ) == false) {
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
			}*/
			if (empty ( $get_data ['month'] ) == false && strtolower ( $get_data ['month'] ) != 'all')
			{
				$condition [] = array ('Month(BD.created_datetime)', '=', $get_data ['month']);
			}
			if (empty ( $get_data ['year'] ) == false && strtolower ( $get_data ['year'] ) != 'all')
			{
				$condition [] = array ('YEAR(BD.created_datetime)', '=', $get_data ['year']);
			}
			if (empty ( $get_data ['supplier_name'] ) == false && strtolower ( $get_data ['supplier_name'] ) != 'all')
			{
				$condition [] = array ('BD.supplier_id', '=', $supplier);
			}
			
			$page_data ['from_date'] = $from_date;
			$page_data ['to_date'] = $to_date;
			$page_data ['supplier_id'] = $supplier;
			$page_data ['month'] = $get_data ['month'];
			$page_data ['year'] = $get_data ['year'];
		}
		$condition [] = array (
						'BD.status',
						'=',
						"'BOOKING_CONFIRMED'" 
				);
		$offset = intval ( $offset );		
		$this->load->model('tours_model');
		
		$total_records = $this->supplier_management_model->supplier_report ( $condition, true );
		$table_data = $this->supplier_management_model->supplier_report ( $condition, false, $offset, RECORDS_RANGE_5 );
		$page_data ['table_data'] = $table_data ['data'];
		$total_payable_Details=$this->supplier_management_model->supplier_payable_details ( $condition, true );
		$payment_status=$this->supplier_management_model->supplier_amount_payment_status ( $get_data);
		
		$page_data ['supplier_amount_details'] = $total_payable_Details;
		$page_data ['payment_status'] = $payment_status;
		
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
		$page_data['supplier_list'] = $this->custom_db->single_table_records ( 'user','user_id,first_name,last_name',array('user_type'=>'9') )['data'];		
		$page_data['module_type'] = $module_type;

		

		$this->template->view ( 'supplier_management/supplier_report', $page_data );	
	}
	function payment_details($balance_request_type = "Cash") 
	{
		//error_reporting(E_ALL);
		$page_data ['form_data'] = $this->input->post ();
		$get_data = $this->input->get();
		$page_data['help_text'] = '';
		$condition=array();
		if (valid_array ( $page_data ['form_data'] ) == true) 
		{
			
				if ($page_data ['form_data'] ['origin'] == 0) 
				{

					$page_data ['form_data']['status']="1";
					$page_data ['form_data']['attributes']=json_encode($page_data ['form_data']);
					$page_data ['form_data']['created_datetime']=date("Y-m-d");
					$page_data ['form_data']['created_by_id']=$this->entity_user_id;
					unset($page_data ['form_data'] ['origin']);
					$insert_id = $this->supplier_management_model->save_supplier_payment_details ( $page_data ['form_data']);
					
					if($insert_id !="")
					{
						$this->session->set_flashdata('success_msg','successfully Inserted');
						
					}
					else
					{
						$this->session->set_flashdata('error','something went wrong');

					}
					redirect(base_url().'index.php/supplier_management/payment_details');
					/*$email = $this->entity_email;
					$page_data ['entity_agency_name'] = $this->entity_agency_name;
					set_update_message ();
					$mail_template = $this->template->isolated_view ( 'management/balance_email', $page_data );
					$this->provab_mailer->send_mail ( $email, ' Balance Request', $mail_template, '' );*/
				} 
		}
		else if (valid_array ( $get_data ) == true) 
		{
			if (empty ( $get_data ['month'] ) == false && strtolower ( $get_data ['month'] ) != 'all')
			{
				$condition [] = array ('BD.payment_for_month', '=', $get_data ['month']);
			}
			if (empty ( $get_data ['year'] ) == false && strtolower ( $get_data ['year'] ) != 'all')
			{
				$condition [] = array ('BD.payment_for_year', '=', $get_data ['year']);
			}
			if (empty ( $get_data ['supplier_name'] ) == false && strtolower ( $get_data ['supplier_name'] ) != 'all')
			{
				$condition [] = array ('BD.supplier_id', '=', $get_data ['supplier_name']);
			}
			$page_data ['table_data'] = $this->supplier_management_model->supplier_payment_details ($condition);

			$page_data ['supplier_id'] = $get_data ['supplier_name'];
			$page_data ['month'] = $get_data ['month'];
			$page_data ['year'] = $get_data ['year'];
		}
		else
		{
			$page_data ['table_data']="";
		}
		
		if (empty ( $page_data ['form_data'] ['currency_converter_origin'] ) == true) {
			$page_data ['form_data'] ['currency_converter_origin'] = COURSE_LIST_DEFAULT_CURRENCY;
			$page_data ['form_data'] ['conversion_value'] = 1;
		}
		$page_data['supplier_list'] = $this->custom_db->single_table_records ( 'user','user_id,first_name,last_name',array('user_type'=>'9') )['data'];		
		$this->template->view ( 'supplier_management/supplier_payment_details', $page_data );
	}
	function get_supplier_price()
	{
		$post_data=$this->input->post();
		$supplier_id=$post_data['supplier_id'];
		$paying_month=$post_data['paying_month'];
		$paying_year=$post_data['paying_year'];

		$data=$this->supplier_management_model->get_supplier_price($supplier_id,$paying_month,$paying_year);	
		
		if(!empty($data))
		{
			if($data[0]['total_payable'] !="")
			{
				echo $data[0]['total_payable'];
			}
			else
			{
				echo "0";
			}
		}
		else
		{
			echo false;
		}
	}
}