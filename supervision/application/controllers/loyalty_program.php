<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Loyalty_program extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('module_model');
		$this->load->model('loyalty_program_model');
		$this->load->library('provab_sms');
		
	}
	public function update_master_module_status(){
		$get_data = $this->input->post ();
		$this->db->where('id',$get_data['id']);
		$this->db->set('status',$get_data['status']);
		$this->db->update('module_status');
		echo 1;
	}
	public function get_module_range(){
		$get_data = $this->input->post ();
		$get_all_list=$this->loyalty_program_model->get_module_range($get_data['id']);
		$response['data'] = get_compressed_output($this->template->isolated_view('loyalty_pro/get_module_range',
						array('product_list' => $get_all_list
						)));
		echo $response['data'];		
	}
	public function save_range_point(){
		$get_data = $this->input->post ();
		$check_range=$this->loyalty_program_model->check_range_point($get_data);
		if($check_range)
		{

			$get_all_list=$this->loyalty_program_model->save_range_point($get_data);
			echo 1;
		}
		else
		{
			echo 2;
		}
	}
	public function delete_module_range(){
		$get_data = $this->input->post ();
		$this->db->where('id',$get_data['id']);		
		$this->db->delete('loyalty_master_module_range_reward');
		echo 1;
	}
	public function update_loyality_status(){
		$get_data = $this->input->post ();
		$cond['user_id'] = intval($get_data['id']);		
		$data['loyalty_program'] = $get_data['status'];
		
		$this->db->where('user_id',$cond['user_id']);
		$this->db->set('loyalty_program',$data['loyalty_program']);
		$this->db->update('user');

		echo 1;
	}
	public function update_loyality_module(){
		$get_data = $this->input->post ();
		$data="";
		if($get_data['hotel'] !="")
		{
			$data .=$get_data['hotel'].',';
		}
		if($get_data['Transfer'] !="")
		{
			$data .=$get_data['Transfer'].',';
		}
		if($get_data['Holidays'] !="")
		{
			$data .=$get_data['Holidays'].',';
		}
		if($get_data['Activities'] !="")
		{
			$data .=$get_data['Activities'].',';
		}
		if(substr($data, -1)==','){
			$data=substr_replace($data ,"",-1);
		}
		

		$cond['user_id'] = intval($get_data['id']);	
		

		$this->db->where('user_id',$cond['user_id']);
		$this->db->set('loyalty_pro_module',$data);
		$this->db->update('user');
		echo 1;
	}
	public function savehotel(){
		$get_data = $this->input->post ();
		// debug($get_data);exit;
		$user_data = $this->custom_db->single_table_records('user_loyalty_program', '*', array('user_id' => $get_data ['agent_id'],'module_type'=>$get_data ['module_type']));

		/*$agent_reward_details = $this->custom_db->single_table_records('reward_point_total', '*', array('agent_id' => $get_data ['agent_id']));

		if($agent_reward_details['status']==0){
			$dd=array('agent_id'=>$get_data ['agent_id']);
			$this->db->insert('reward_point_total', $dd); 
		}*/

		if($user_data['status']){
			$dd=array('status'=>$get_data['status']);
			// $dd=array('status'=>$get_data['status'],'booking_range'=>$get_data['booking_range'],'time_period'=>$get_data['time_period']);
			$this->db->where('user_id', $get_data ['agent_id']);
			$this->db->where('module_type', $get_data ['module_type']);
			$this->db->update('user_loyalty_program', $dd); 
		}
		else
		{
			$dt=date('Y-m-d');
			$dd=array('user_id'=>$get_data ['agent_id'],'module_type'=>$get_data ['module_type'],'status'=>$get_data['status']);
			// $dd=array('user_id'=>$get_data ['agent_id'],'module_type'=>$get_data ['module_type'],'status'=>$get_data['status'],'booking_range'=>$get_data['booking_range'],'start_date'=>$dt,'time_period'=>$get_data['time_period']);
			$this->db->insert('user_loyalty_program', $dd); 
		}
		redirect('loyalty_program/agent_loyalty_program?agent_id='.$get_data ['agent_id']);
	}
	public function agent_loyalty_program()
	{
		$get_data = $this->input->get ();
		// debug($get_data);die;
		if (valid_array ( $get_data ) == true)
		{
			// validate user and create track log
			$user_details = $this->user_model->get_user_details ( array (
					array (
							'U.user_id',
							'=',
							$get_data ['agent_id'] 
					),
					array (
							'U.user_type' => B2B_USER 
					) 
			) );
			$edit_data = $this->custom_db->single_table_records('user_loyalty_program', '*', array('user_id' => $get_data ['agent_id']));

			$master_module = $this->custom_db->single_table_records('module_status', '*', array('status'=>'ENABLE'));
			$page_data['user_details_lo_pro']=$edit_data;
			$page_data['master_module']=$master_module['data'];
			// debug($page_data['master_module']);die;
			if (valid_array ( $user_details ) == true) 
			{
				
				
				
				 $page_data['user_details']=$user_details;
				$this->template->view('user/edit_agent_loyalty_pro', $page_data);
			}
		}
	}
	public function get_total_reward_agent(){
		// error_reporting(E_ALL);
		$condition = array();
		$get_data = $this->input->get();
		$filter_data = $this->format_basic_search_filters();
		// debug($filter_data);exit;
		$page_data['user_name'] = @$filter_data['user_name'];
		$page_data['agency_name'] = @$filter_data['agency_name'];
		$page_data['phone'] = @$filter_data['phone'];
		$condition = @$filter_data['filter_condition'];
		$page_data['get_all_list']=$this->loyalty_program_model->get_reward_all_list($condition);
		$page_data['total_rows'] = $this->loyalty_program_model->get_reward_all_list_cnt($condition);
		// debug($page_data['total_rows']);exit;
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/loyalty_program/get_total_reward_agent/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $page_data['total_rows'];
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_rows'] = $config['total_rows'];
		// debug($page_data['total_rows']);exit;
		$this->template->view('user/agent_wise_reward_point',$page_data);
	}
	public function hold_reward_point($offset=0){
		// echo "d";exit;
		// error_reporting(E_ALL);
		$condition = array();
		$get_data = $this->input->get();
		$filter_data = $this->format_basic_search_filters();
		// debug($filter_data);exit;
		$page_data['user_name'] = @$filter_data['user_name'];
		$page_data['agency_name'] = @$filter_data['agency_name'];
		$page_data['phone'] = @$filter_data['phone'];
		$condition = @$filter_data['filter_condition'];
		$page_data['get_all_list']=$this->loyalty_program_model->get_hold_transation($condition, false, $offset, RECORDS_RANGE_1);
		// debug($page_data['get_all_list']);exit;
		$page_data['total_rows'] = $this->loyalty_program_model->get_hold_transation_cnt($condition);
		// debug($page_data['total_rows']);exit;
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/loyalty_program/hold_reward_point/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $page_data['total_rows'];
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_rows'] = $config['total_rows'];
		// debug($page_data);exit;
		$this->template->view('loyalty_pro/hold_reward_point',$page_data);
	}
	public function product_list(){
		// error_reporting(E_ALL);
		/*$condition = array();
		$get_data = $this->input->get();
		$filter_data = $this->format_basic_search_filters();
		// debug($filter_data);exit;
		$page_data['user_name'] = $filter_data['user_name'];
		$page_data['agency_name'] = $filter_data['agency_name'];
		$page_data['phone'] = $filter_data['phone'];
		$condition = $filter_data['filter_condition'];*/
		$page_data['get_all_list']=$this->loyalty_program_model->get_product_list();
		$page_data['total_rows'] = $this->loyalty_program_model->get_product_list_cnt();
		// debug($page_data['total_rows']);exit;
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/loyalty_program/product_list/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $page_data['total_rows'];
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_rows'] = $config['total_rows'];
		// debug($page_data['total_rows']);exit;
		$this->template->view('loyalty_pro/product_list',$page_data);
	}
	public function edit_product(){
	   
		$get_data = $this->input->get();

		$page_data['get_edit_product']=$this->loyalty_program_model->get_edit_product($get_data['eid']);	
		$this->template->view('loyalty_pro/edit_product',$page_data);
	}
	public function add_product(){
		

		$page_data['get_edit_product']="";	
		$this->template->view('loyalty_pro/add_product',$page_data);
	}
	public function delete_product(){
		$get_data = $this->input->get();
		$this->db->where('id',$get_data['eid']);
		$this->db->delete('loyalty_product');
		redirect('loyalty_program/product_list');
	}
	public function saveproduct(){
		// echo "dd";exit;
		// error_reporting(E_ALL);
		$get_data = $this->input->post();

		// debug($_FILES);exit;

			




				




		if($get_data['id'] !='')
		{

			$status=$this->loyalty_program_model->check_name($get_data);
			// echo "d";exit;
			if($status){
				redirect('loyalty_program/edit_product?eid='.$get_data['id']);
			}
			else
			{


				if (valid_array ( $_FILES ) == true and $_FILES ['image'] ['error'] == 0 and $_FILES ['image'] ['size'] > 0) {
		
					$img_name = 'Product-'.time();
					if( function_exists( "check_mime_image_type" ) ) {
					    if ( !check_mime_image_type( $_FILES['image']['tmp_name'] ) ) {
					    	echo "Please select the image files only (gif|jpg|png|jpeg)"; exit;
					    }
					}
					$config ['upload_path'] = realpath ( '../extras' ).'/custom/'.CURRENT_DOMAIN_KEY.'/uploads/loyalty_product/';

					// echo $config ['upload_path'];exit;
					$temp_file_name = $_FILES ['image'] ['name'];
					$config['allowed_types'] = 'gif|jpg|png|jpeg';
					$config ['file_name'] ='IMG-'.$img_name;
					$config['max_size'] = 0;
					$config['max_width']  = 0;
					$config['max_height']  = 0;
					$config ['remove_spaces'] = false;
					// UPLOAD IMAGE
					$this->load->library ( 'upload', $config );
					$this->upload->initialize ( $config );
					if (! $this->upload->do_upload ( 'image' )) {
						echo $this->upload->display_errors ();
					} else {
						$image_data = $this->upload->data ();
					}
				}
				$get_data['image'] = (empty($image_data ['file_name']) == false ? $image_data ['file_name'] : '');

				$this->loyalty_program_model->update_product($get_data);	
				redirect('loyalty_program/product_list');
			}
			
		}
		else
		{
			$status=$this->loyalty_program_model->check_name($get_data);
			if($status)
			{
				redirect('loyalty_program/add_product');
			}else
			{

				if (valid_array ( $_FILES ) == true and $_FILES ['image'] ['error'] == 0 and $_FILES ['image'] ['size'] > 0) {
				// echo "ddd";
					$img_name = 'Product-'.time();
					if( function_exists( "check_mime_image_type" ) ) {
					    if ( !check_mime_image_type( $_FILES['image']['tmp_name'] ) ) {
					    	echo "Please select the image files only (gif|jpg|png|jpeg)"; exit;
					    }
					}
					$config ['upload_path'] = realpath ( '../extras' ).'/custom/'.CURRENT_DOMAIN_KEY.'/uploads/loyalty_product/';

					// echo $config ['upload_path'];exit;
					$temp_file_name = $_FILES ['image'] ['name'];
					$config['allowed_types'] = 'gif|jpg|png|jpeg';
					$config ['file_name'] ='IMG-'.$img_name;
					$config['max_size'] = 0;
					$config['max_width']  = 0;
					$config['max_height']  = 0;
					$config ['remove_spaces'] = false;
					// UPLOAD IMAGE
					$this->load->library ( 'upload', $config );
					$this->upload->initialize ( $config );
					if (! $this->upload->do_upload ( 'image' )) {
						echo $this->upload->display_errors ();
					} else {
						$image_data = $this->upload->data ();
					}



				}

				$get_data['image'] = (empty($image_data ['file_name']) == false ? $image_data ['file_name'] : '');

				// echo $get_data['image'];exit;

				$this->loyalty_program_model->update_product($get_data);
				redirect('loyalty_program/product_list');
			}
			
		}
	}
	public function get_total_reward_point(){
		error_reporting(E_ALL);
		// echo "dd";exit;
		$condition = array();
		
		$page_data['get_all_list']=$this->loyalty_program_model->get_total_reward_all_list();
		// debug($page_data['get_all_list']);exit;
		
		$this->template->view('user/total_reward_point',$page_data);
	}
	private function format_basic_search_filters()
	{
		$get_data = $this->input->get();
		// debug($get_data);exit;
		if(valid_array($get_data) == true) {
			$filter_condition = array();
			//From-Date and To-Date
			$agency_name = trim(@$get_data['agency_name']);
			$user_name = trim(@$get_data['user_name']);
			$phone = trim(@$get_data['phone']);
			
			//Auto swipe date
			
			if(empty($user_name) == false) {
				$filter_condition[] = array('user.email', '=', '"'.provab_encrypt($user_name).'"');
			}
			
			//Booking-Status
			if(empty($agency_name) == false) {
				//Confirmed Booking
				$filter_condition[] = array('user.agency_name', '=', '"'.$agency_name.'"');
			}
			 if(empty($phone) == false) {
				//Confirmed Booking
				$filter_condition[] = array('user.phone', '=', '"'.$phone.'"');
			} 
			
			
			return array('filter_condition' => $filter_condition, 'user_name' => $user_name, 'agency_name' => $agency_name, 'phone' => $phone);
		}
		
	}
	public function test_currency(){
		$get_all_list=$this->loyalty_program_model->get_all_curency();
		foreach ($get_all_list as $key => $value) {
			$data=array('currency_id'=>$value['id'],'country'=>$value['country']);
			$this->db->insert('loyalty_currency_rate',$data);
			
		}
	}
	public function currency_rate($offset=0){
		$condition = array();
		$get_data = $this->input->get();
		$filter_data = $this->format_basic_search_filters_curency();
		// debug($filter_data);exit;
		$page_data['country'] = @$filter_data['country'];
		
		$condition = @$filter_data['filter_condition'];

		// debug($condition);exit;
		$page_data['get_all_list']=$this->loyalty_program_model->get_all_currency_list($condition, false, $offset, RECORDS_RANGE_2);
		$page_data['total_rows'] = $this->loyalty_program_model->get_all_currency_list_cnt($condition);
		// debug($page_data['get_all_list']);exit;
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/loyalty_program/currency_rate/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $page_data['total_rows'];
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_rows'] = $config['total_rows'];
		// debug($page_data['total_rows']);exit;
		$page_data['country_list']=$this->loyalty_program_model->get_all_currency_country_list();
		// debug($page_data['country_list']);exit;
		$this->template->view('loyalty_pro/currency_rate_list',$page_data);
	}
	public function update_currency_rate(){
		$post_data = $this->input->post();
		$this->db->where('id',$post_data['id']);
		$this->db->set('rate',$post_data['rate']);
		$this->db->update('loyalty_currency_rate');
		echo 1;

	}
	public function add_save_currency_code(){
		$post_data = $this->input->post();
		// echo $post_data['country_code'];

		$check=$this->loyalty_program_model->check_currency_details($post_data['country_code']);

		if($check)
		{
			$currency_details=$this->loyalty_program_model->get_currency_details($post_data['country_code']);
			if($currency_details)
			{
				$data=array('currency_id'=>$currency_details['id'],'country'=>$currency_details['country'],'rate'=>$post_data['rate']);
				$this->db->insert('loyalty_currency_rate',$data);
			}
			$this->session->set_flashdata('message', UL0014);
		}
		else
		{
			$this->session->set_flashdata('message', UL0102);
		}
		redirect('loyalty_program/currency_rate');
		
	}
	private function format_basic_search_filters_curency()
	{
		$get_data = $this->input->get();
		// debug($get_data);exit;
		if(valid_array($get_data) == true) {
			$filter_condition = array();
			//From-Date and To-Date
			$country = trim(@$get_data['country']);
			
			
			//Auto swipe date
			
			if(empty($country) == false) {
				$filter_condition[] = array('loyalty_currency_rate.country', '=', '"'.$country.'"');
			}
			
			//Booking-Status
			
			
			
			return array('filter_condition' => $filter_condition, 'country' => $country);
		}
		
	}
	function edit_module_status() {
        // error_reporting(E_ALL);
        $post_data = $this->input->post();
        
        $this->db->where('id',$post_data['id']);
        $this->db->set('defult_value',$post_data['defult_value']);
        $this->db->update('module_status');
        echo 1;
    }
    public function redeem_request($offset=0){
		$condition = array();
		$get_data = $this->input->get();
		// $filter_data = $this->format_basic_search_filters_curency();
		// debug($filter_data);exit;
		// $page_data['country'] = @$filter_data['country'];
		
		// $condition = @$filter_data['filter_condition'];
		$page_data['get_all_list']=$this->loyalty_program_model->get_all_recharge_request_pending($condition,false, $offset, RECORDS_RANGE_1);
		$page_data['total_rows'] = $this->loyalty_program_model->get_all_recharge_request_pending_cnt();
		// debug($page_data['get_all_list']);exit;
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/loyalty_program/redeem_request/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $page_data['total_rows'];
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_rows'] = $config['total_rows'];
		// debug($page_data['total_rows']);exit;
		$this->template->view('loyalty_pro/recharge_request',$page_data);
	}
	public function redeem_approved($offset=0){
		$condition = array();
		$get_data = $this->input->get();
		// $filter_data = $this->format_basic_search_filters_curency();
		// debug($filter_data);exit;
		// $page_data['country'] = @$filter_data['country'];
		
		// $condition = @$filter_data['filter_condition'];
		$page_data['get_all_list']=$this->loyalty_program_model->get_all_recharge_approved_pending($condition,false, $offset, RECORDS_RANGE_1);
		$page_data['total_rows'] = $this->loyalty_program_model->get_all_recharge_request_approved_cnt();
		// debug($page_data['get_all_list']);exit;
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/loyalty_program/redeem_request/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $page_data['total_rows'];
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_rows'] = $config['total_rows'];
		// debug($page_data['total_rows']);exit;
		$this->template->view('loyalty_pro/redeem_aproved',$page_data);
	}
	public function redeem_request_approve($id){
			$get_all_list=$this->loyalty_program_model->get_redeem_details($id);
			$agent_id=$get_all_list['agent_id'];

			$proid=json_decode($get_all_list['product_id']);
			$pid=implode(",",$proid);
			$pid1= str_replace(',',"','", $pid);
			$pid2="'".$pid1."'";

			$query="select * from loyalty_product where id IN($pid2)";
				// echo $query;exit;
			$name="";
			$query=$this->db->query($query);
			$result1 = $query->result_array();
			foreach ($result1 as $key => $value) {
				if($value['type']==1){
					$agentcurrentcy=$this->loyalty_program_model->get_agent_currency($agent_id);
					if($agentcurrentcy['rate'] !="")
					{


						$amount=$value['point']*$agentcurrentcy['rate'];
						
					}
					else
					{
						$this->load->model('domain_management_model');
						$currency_obj_m = new Currency(array('module_type' =>'hotel', 'from' => 'AED', 'to' => $agentcurrentcy['country']));
						$strCurrency_m  = $currency_obj_m->get_currency($value['point'], true, false, true, false, 1);
						$amount=$strCurrency_m['default_value'];
					}

					$this->db->where('user_oid',$agent_id);
					// $this->db->set('credit_limit',$agent_id);
					$this->db->set('credit_limit', 'credit_limit+'.$amount, FALSE);
					$this->db->update('b2b_user_details');
				}
			}

			$this->db->where('id',$id);
			$this->db->where('agent_id',$agent_id);
					// $this->db->set('credit_limit',$agent_id);
			$this->db->set('status', '1');
			$this->db->update('loyalty_redeem_request');

			redirect('loyalty_program/redeem_request');
			
			// print_r($get_all_list);exit;
	}
	public function relese_singl_value($id){
		$transaction_details=$this->loyalty_program_model->get_transaction_details($id);
		if($transaction_details)
		{

			$reward_point=$transaction_details['reward_point'];
			$reward_type=$transaction_details['booking_type'];
			if($reward_type=='Hotel')
			{

				$this->db->where('agent_id',$transaction_details['agent_id']);
				$this->db->set('t_redeem','t_redeem+'.$reward_point,FALSE);
				$this->db->set('t_reward','t_reward-'.$reward_point,FALSE);
				$this->db->set('hotel','hotel-'.$reward_point,FALSE);
				$this->db->set('rhotel','rhotel+'.$reward_point,FALSE);
				$this->db->update('reward_point_total');

			}
			if($reward_type=='Transfer')
			{
				$this->db->where('agent_id',$transaction_details['agent_id']);
				$this->db->set('t_redeem','t_redeem+'.$reward_point,FALSE);
				$this->db->set('t_reward','t_reward-'.$reward_point,FALSE);
				$this->db->set('transfer','transfer-'.$reward_point,FALSE);
				$this->db->set('rtransfer','rtransfer+'.$reward_point,FALSE);
				$this->db->update('reward_point_total');
			}
			if($reward_type=='Holiday')
			{
				$this->db->where('agent_id',$transaction_details['agent_id']);
				$this->db->set('t_redeem','t_redeem+'.$reward_point,FALSE);
				$this->db->set('t_reward','t_reward-'.$reward_point,FALSE);
				$this->db->set('holidays','holidays-'.$reward_point,FALSE);
				$this->db->set('rholiday','rholiday+'.$reward_point,FALSE);
				$this->db->update('reward_point_total');
			}
			if($reward_type=='Activities')
			{
				$this->db->where('agent_id',$transaction_details['agent_id']);
				$this->db->set('t_redeem','t_redeem+'.$reward_point,FALSE);
				$this->db->set('t_reward','t_reward-'.$reward_point,FALSE);
				$this->db->set('activities','activities-'.$reward_point,FALSE);
				$this->db->set('ractivities','ractivities+'.$reward_point,FALSE);
				$this->db->update('reward_point_total');
			}
			if($reward_type=='Visa')
			{
				$this->db->where('agent_id',$transaction_details['agent_id']);
				$this->db->set('t_redeem','t_redeem+'.$reward_point,FALSE);
				$this->db->set('t_reward','t_reward-'.$reward_point,FALSE);
				$this->db->set('visa','visa-'.$reward_point,FALSE);
				$this->db->set('rvisa','rvisa+'.$reward_point,FALSE);
				$this->db->update('reward_point_total');
			}
		}

		$this->db->where('id',$id);
		$this->db->set('status',2);
		$this->db->update('reward_point_transaction');

		redirect('loyalty_program/hold_reward_point');
	}
	//hold value
	public function relese_all_value(){
		$get_data = $this->input->post();
		// debug($get_data);exit;
		$dd=implode(',',$get_data['id']);
		// debug($dd);exit;
		$transaction_details=$this->loyalty_program_model->get_transaction_all($dd);
		
		if($transaction_details)
		{

			foreach ($transaction_details as $key => $value)
			{
				

				$reward_point=$value['reward_point'];
				$reward_type=$value['booking_type'];
				if($reward_type=='Hotel')
				{

					$this->db->where('agent_id',$value['agent_id']);
					$this->db->set('t_redeem','t_redeem+'.$reward_point,FALSE);
					$this->db->set('t_reward','t_reward-'.$reward_point,FALSE);
					$this->db->set('hotel','hotel-'.$reward_point,FALSE);
					$this->db->set('rhotel','rhotel+'.$reward_point,FALSE);
					$this->db->update('reward_point_total');

				}
				if($reward_type=='Transfer')
				{
					$this->db->where('agent_id',$value['agent_id']);
					$this->db->set('t_redeem','t_redeem+'.$reward_point,FALSE);
					$this->db->set('t_reward','t_reward-'.$reward_point,FALSE);
					$this->db->set('transfer','transfer-'.$reward_point,FALSE);
					$this->db->set('rtransfer','rtransfer+'.$reward_point,FALSE);
					$this->db->update('reward_point_total');
				}
				if($reward_type=='Holiday')
				{
					$this->db->where('agent_id',$value['agent_id']);
					$this->db->set('t_redeem','t_redeem+'.$reward_point,FALSE);
					$this->db->set('t_reward','t_reward-'.$reward_point,FALSE);
					$this->db->set('holidays','holidays-'.$reward_point,FALSE);
					$this->db->set('rholiday','rholiday+'.$reward_point,FALSE);
					$this->db->update('reward_point_total');
				}
				if($reward_type=='Activities')
				{
					$this->db->where('agent_id',$value['agent_id']);
					$this->db->set('t_redeem','t_redeem+'.$reward_point,FALSE);
					$this->db->set('t_reward','t_reward-'.$reward_point,FALSE);
					$this->db->set('activities','activities-'.$reward_point,FALSE);
					$this->db->set('ractivities','ractivities+'.$reward_point,FALSE);
					$this->db->update('reward_point_total');
				}
				if($reward_type=='Visa')
				{
					$this->db->where('agent_id',$value['agent_id']);
					$this->db->set('t_redeem','t_redeem+'.$reward_point,FALSE);
					$this->db->set('t_reward','t_reward-'.$reward_point,FALSE);
					$this->db->set('visa','visa-'.$reward_point,FALSE);
					$this->db->set('rvisa','rvisa+'.$reward_point,FALSE);
					$this->db->update('reward_point_total');
				}


				$this->db->where('id',$value['id']);
				$this->db->set('status',2);
				$this->db->update('reward_point_transaction');
			}

			
		}

		redirect('loyalty_program/hold_reward_point');
	}
	//cron job for 
	public function cron_relese_all_value(){
		
		$transaction_details=$this->loyalty_program_model->cron_get_transaction_all();
		
		if($transaction_details)
		{

			foreach ($transaction_details as $key => $value)
			{
				

				$reward_point=$value['reward_point'];
				$reward_type=$value['booking_type'];
				$booking_reference=$value['booking_reference'];
				// $agent_id=$value['agent_id'];
				

				// $reward_point_total_details=$this->loyalty_program_model->get_reward_point_total($agent_id);

				if($reward_type=='Hotel')
				{

					$transaction_details=$this->loyalty_program_model->check_hotel_booking_status($booking_reference);
					// debug($transaction_details);exit;
					if($transaction_details)
					{
						/*debug($value['agent_id']);
						debug($reward_point);
						exit;*/

						$this->db->where('agent_id',$value['agent_id']);
						$this->db->set('t_redeem','t_redeem+'.$reward_point,FALSE);

						$this->db->set('t_reward','t_reward-'.$reward_point,FALSE);
						$this->db->set('hotel','hotel-'.$reward_point,FALSE);
						$this->db->set('rhotel','rhotel+'.$reward_point,FALSE);
						$this->db->update('reward_point_total');
					}
					else
					{
						$this->db->where('agent_id',$value['agent_id']);
						$this->db->set('t_reward','t_reward-'.$reward_point,FALSE);
						$this->db->set('hotel','hotel-'.$reward_point,FALSE);
						$this->db->update('reward_point_total');
					}
					

				}
				if($reward_type=='Transfer')
				{
					$transaction_details=$this->loyalty_program_model->check_transfer_booking_status($booking_reference);
					// debug($reward_point);exit;
					if($transaction_details)
					{
						$this->db->where('agent_id',$value['agent_id']);
						$this->db->set('t_redeem','t_redeem+'.$reward_point,FALSE);
						$this->db->set('t_reward','t_reward-'.$reward_point,FALSE);
						$this->db->set('transfer','transfer-'.$reward_point,FALSE);
						$this->db->set('rtransfer','rtransfer+'.$reward_point,FALSE);
						$this->db->update('reward_point_total');
						// debug($this->db->last_query());exit;
					}
					else
					{
						$this->db->where('agent_id',$value['agent_id']);
						$this->db->set('t_reward','t_reward-'.$reward_point,FALSE);
						$this->db->set('transfer','transfer-'.$reward_point,FALSE);
						$this->db->update('reward_point_total');
					}
				}
				if($reward_type=='Holiday')
				{
					$transaction_details=$this->loyalty_program_model->check_holiday_booking_status($booking_reference);
					if($transaction_details)
					{
						$this->db->where('agent_id',$value['agent_id']);
						$this->db->set('t_redeem','t_redeem+'.$reward_point,FALSE);
						$this->db->set('t_reward','t_reward-'.$reward_point,FALSE);
						$this->db->set('holidays','holidays-'.$reward_point,FALSE);
						$this->db->set('rholiday','rholiday+'.$reward_point,FALSE);
						$this->db->update('reward_point_total');
					}
					else
					{
						$this->db->where('agent_id',$value['agent_id']);
						$this->db->set('t_reward','t_reward-'.$reward_point,FALSE);
						$this->db->set('holidays','holidays-'.$reward_point,FALSE);
						$this->db->update('reward_point_total');
					}
				}
				if($reward_type=='Activities')
				{
					$transaction_details=$this->loyalty_program_model->check_activities_booking_status($booking_reference);
					if($transaction_details)
					{
						$this->db->where('agent_id',$value['agent_id']);
						$this->db->set('t_redeem','t_redeem+'.$reward_point,FALSE);
						$this->db->set('t_reward','t_reward-'.$reward_point,FALSE);
						$this->db->set('activities','activities-'.$reward_point,FALSE);
						$this->db->set('ractivities','ractivities+'.$reward_point,FALSE);
						$this->db->update('reward_point_total');
					}
					else
					{
						$this->db->where('agent_id',$value['agent_id']);
						$this->db->set('t_reward','t_reward-'.$reward_point,FALSE);
						$this->db->set('activities','activities-'.$reward_point,FALSE);
						$this->db->update('reward_point_total');
					}
				}
				/*if($reward_type=='Visa')
				{
					$this->db->where('agent_id',$value['agent_id']);
					$this->db->set('t_redeem','t_redeem+'.$reward_point,FALSE);
					$this->db->set('t_reward','t_reward-'.$reward_point,FALSE);
					$this->db->set('visa','visa-'.$reward_point,FALSE);
					$this->db->set('rvisa','rvisa+'.$reward_point,FALSE);
					$this->db->update('reward_point_total');
				}*/


				$this->db->where('id',$value['id']);
				$this->db->set('status',2);
				$this->db->update('reward_point_transaction');
			}

			
		}

		
	}
	

	
}
