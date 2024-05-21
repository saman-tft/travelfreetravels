<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
//error_reporting(E_ALL);
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
	
	function payment_details($balance_request_type = "Cash") 
	{
		$page_data ['form_data'] = $this->input->post ();
		$get_data = $this->input->get();
		$page_data['help_text'] = '';
		$condition=array();
		
		if (valid_array ( $get_data ) == true) 
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
				$condition [] = array ('BD.supplier_id', '=', $this->entity_user_id);
			}
			

			$page_data ['supplier_id'] = $get_data ['supplier_name'];
			$page_data ['month'] = $get_data ['month'];
			$page_data ['year'] = $get_data ['year'];
		}

		$condition [] = array ('BD.supplier_id', '=', $this->entity_user_id);
		$page_data ['table_data'] = $this->supplier_management_model->supplier_payment_details ($condition);

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
		if($data !="failed" && valid_array($data))
		{
			echo $data[0]['total_payable'];
		}
		else
		{
			echo false;
		}
	}
}