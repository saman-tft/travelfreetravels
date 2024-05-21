<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 *
 * @package Provab - Provab Application
 * @subpackage Travel Portal
 * @author Arjun J<arjunjgowda260389@gmail.com> on 01-06-2015
 * @version V2
 */
class Car_Supplier extends CI_Controller {
	public function __construct() {
		parent::__construct ();		
		$this->load->model('car_model');
	}
	
	public function login(){
		echo $this->template->view('general/login');
	}


	/*************car supplier details******/
	public function add_car_supplier(){

		$data ["country"] = $this->Supplierpackage_Model->get_countries ();
		
		$this->template->view ( 'car/add_supplier', $data );
	}

	public function add_supplier($id=0){
		$post_params = $this->input->post();		

		if(valid_array($post_params)){
			$insert_arr = array();
			$insert_arr['supplier_name'] = $post_params['supplier_name'];
			$insert_arr['company_reg_no'] = $post_params['reg_no'];
			$insert_arr['email'] = $post_params['email'];
			$insert_arr['phone_number'] = $post_params['phone_no'];
			$insert_arr['pan_no'] = $post_params['pan_no'];
			$insert_arr['aadhar_no'] = $post_params['adhar_no'];
			$insert_arr['country'] = $post_params['country'];
			$insert_arr['city_name'] = $post_params['cityname'];
			$insert_arr['city_name_old'] = $post_params['cityname_old'];
			$insert_arr['location'] = $post_params['location'];
			$insert_arr['address'] = $post_params['address'];
			$insert_arr['password'] = base64_encode($post_params['password']);

			$photo = $_FILES;
			$cpt1 = count ( $_FILES ['photo'] ['name'] );
			if ($_FILES ['photo'] ['name'] != '') {
				$_FILES ['photo'] ['name'] = $photo ['photo'] ['name'];
				$_FILES ['photo'] ['type'] = $photo ['photo'] ['type'];
				$_FILES ['photo'] ['tmp_name'] = $photo ['photo'] ['tmp_name'];
				$_FILES ['photo'] ['error'] = $photo ['photo'] ['error'];
				$_FILES ['photo'] ['size'] = $photo ['photo'] ['size'];
				
				move_uploaded_file ( $_FILES ['photo'] ['tmp_name'], $this->template->domain_image_full_path ( $_FILES ['photo'] ['name'] ) );

				
				$photo = $_FILES ['photo'] ['name'];
			}
			$insert_arr['id_proff_image_path'] = $photo;
			$this->custom_db->insert_record('car_supplier_list',$insert_arr);

			$data ["country"] = $this->Supplierpackage_Model->get_countries ();
		
			redirect(base_url().'supplier/car_supplier_list');
		}
	}
	
	public function car_supplier_list(){

		//$supplier_list = $this->custom_db->single_table_records('car_supplier_list','*');
		$supplier_list = $this->car_model->get_supplier_list();		
		$total_row_count = 0;
		if($supplier_list){

		 	$data['table_data'] = $supplier_list;
		 	$total_row_count = count($data['table_data']);
		}else{
			$data['table_data'] = array();
		}	
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/supplier/car_supplier_list/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$config['total_rows'] = $total_row_count;
		$config['per_page'] = RECORDS_RANGE_1;
		$this->pagination->initialize($config);

		$data ["country"] = $this->Supplierpackage_Model->get_countries ();

		$this->template->view ( 'car/supplier_list', $data );

	}
		/**
	 * Activate User Account
	 */
	function activate_today($car_id,$driver_id)
	{	
		$con = array();
		$data = array();
		$con['car_id'] = intval($car_id);		
		$data['available_status'] = ACTIVE;
		$data['updated_datetime'] =date('Y-m-d H:i:s');	
		$data['current_status'] = ACTIVE;
		$check_driver_exists = $this->custom_db->single_table_records('map_car_driver_list','driver_id',array('driver_id'=>$driver_id,'available_status'=>1));

		if($check_driver_exists['status']==0){
			if ($this->custom_db->update_record('map_car_driver_list',$data,$con)) {			
				echo "1";
				
			}else{
				echo "0";
			}
		}else{
			echo "2";
		}
		
		exit;
		/*redirect(base_url().'user/user_management?filter=user_type&q='.$info['data']['user_type']);*/
	}

	/**
	 * Deactiavte User Account
	 */
	function deactivate_today($car_id)
	{	
		$cond = array();
		$data = array();
		$cond['car_id'] = intval($car_id);
		$data['available_status'] = INACTIVE;
		$data['current_status'] = INACTIVE;
		$data['updated_datetime'] =date('Y-m-d H:i:s');		
		if ($this->custom_db->update_record('map_car_driver_list',$data,$cond)){
			
			echo "1";
			
		}else{
			
			echo "0";
		}
		exit;
		
	}
	public function active_car_driver($table,$id){
		$cond = array();
		$data = array();
		if(trim($table)=='car_details'){
			$cond['id'] = intval($id);
			$data['status'] = ACTIVE;
			$data['updated_datetime'] =date('Y-m-d H:i:s');	
			if($this->custom_db->update_record(trim($table),$data,$cond)){
				echo "1";
			}else{
				echo "0";
			}
		}elseif (trim($table)=='driver_list') {
			$cond['driver_fk_id'] = $id;
			$user_cond['user_id'] = $id;
			$data['status'] = ACTIVE;
			$car_data['updated_datetime'] =date('Y-m-d H:i:s');	
			$car_data['status'] = ACTIVE;
			if($this->custom_db->update_record('driver_list',$car_data,$cond) && $this->custom_db->update_record('user',$data,$user_cond)){
				echo "1";
			}else{
				echo "0";
			}
			//echo $this->db->last_query();

		}
		exit;

	}
	public function deactive_car_driver($table,$id){
		$cond = array();
		$data = array();
		
		if(trim($table)=='car_details'){
			$cond['id'] = intval($id);
			$data['status'] = INACTIVE;
			if($this->custom_db->update_record(trim($table),$data,$cond)){
				echo "1";
			}else{
				echo "0";
			}
		}elseif (trim($table) =='driver_list') {
			$cond['driver_fk_id'] = $id;
			$user_cond['user_id'] = $id;		
			$data['status'] = INACTIVE;
			$car_data['updated_datetime'] =date('Y-m-d H:i:s');	
			$car_data['status'] = INACTIVE;
			if($this->custom_db->update_record('driver_list',$car_data,$cond) && $this->custom_db->update_record('user',$data,$user_cond)){
				echo "1";
			}else{
				echo "0";
			}
		}	
		exit;

	}
	public function car_list($id=0){

		$post_data = $this->input->post();


		$supplier_id= $this->entity_user_id;
		// debug($this->template->domain_uploads_car_images ());exit;
// debug($supplier_id);die;
		$default_form = 0;

		if (valid_array($post_data) == false) {
			// echo "in1";die;
			if (intval($id) > 0) {
				// echo "in4";die;
				//edit data
				$tmp_data = $this->car_model->car_list($id,$this->entity_user_id);
				// echo $this->db->last_query();die;
				// echo "in";
				// debug($tmp_data);die;
				if ($tmp_data['status'] == true) {
					$default_form = 1;
					$list = end($tmp_data['data']['list']);
					$feature_id = end($tmp_data['data']['feature']);
					$page_data['id'] = $list['id'];
					$page_data['name'] = $list['name'];
					$page_data['car_make_id'] = $list['car_make_id'];
					$page_data['car_class_id'] = $list['car_class_id'];
					$page_data['car_transmission_id'] = $list['car_transmission_id'];
					$page_data['no_of_seats'] = $list['no_of_seats'];
					$page_data['status'] = $list['status'];
					$page_data['fuel_type'] = $list['fuel_type'];
					$page_data['fuel_capacity'] = $list['fuel_capacity'];
					$page_data['icon'] = $list['icon'];
					$page_data['feature_id'] = $feature_id;
					$page_data['vehicle_no'] = $list['vehicle_no'];
					$page_data['no_of_car'] = $list['no_of_car'];
					$page_data['available_car'] = $list['no_of_car'];
					$page_data['policy_type'] = $list['policy_type'];
					$page_data['cancellation_day'] = $list['cancellation_day'];
					$page_data['cancel_percentage'] = $list['cancel_percentage'];
                    $page_data['localuse'] = $list['localuse'];
                    $page_data['transfers'] = $list['transfers'];
                    $page_data['outsource'] = $list['outsource'];
$page_data['hp_policy']=$tmp_data['data']['hp_policy'];
					// debug($page_data);exit;
				} else {
					redirect('car_supplier/car_list');
				}
			}
		} elseif (valid_array($post_data)) {
// echo "in3";die;
			$this->form_validation->set_rules('name', 'Name', 'required');
			$this->form_validation->set_rules('car_make_id', 'Make', 'required|greater_than[0]');
			$this->form_validation->set_rules('car_class_id', 'Class', 'required|greater_than[0]');
			$this->form_validation->set_rules('car_transmission_id', 'Transmission', 'required|greater_than[0]');
			$this->form_validation->set_rules('no_of_seats', 'Seats', 'required');
			$this->form_validation->set_rules('fuel_type', 'Fuel Type', 'required');
			$this->form_validation->set_rules('fuel_capacity', 'Fuel Capacity', 'required');

			$this->form_validation->set_rules('vehicle_no','Vehicle Number','required|is_unique[car_details.vehicle_no]');

			$this->form_validation->set_rules('policy_type','Policy Type','required');


			$data['name'] = $this->input->post('name');
			$data['car_make_id'] = $this->input->post('car_make_id');
			$data['car_class_id'] = $this->input->post('car_class_id');
			$data['car_transmission_id'] = $this->input->post('car_transmission_id');
			$data['no_of_seats'] = $this->input->post('no_of_seats');
			$data['status'] = $this->input->post('status');
			$data['fuel_type'] = $this->input->post('fuel_type');
			$data['fuel_capacity'] = $this->input->post('fuel_capacity');
			$data['vehicle_no'] = $this->input->post('vehicle_no');
			$data['no_of_car'] = $this->input->post('no_of_car');
			$data['available_car'] = $this->input->post('no_of_car');
			$data['policy_type'] = $this->input->post('policy_type');
			$data['cancellation_day'] = $this->input->post('c_day');
			$data['cancel_percentage'] = $this->input->post('c_percentage');
			$data['localuse'] = $this->input->post('localuse');
            $data['transfers'] = $this->input->post('transfers');
            $data['outsource'] = $this->input->post('outsource');
 
			$photo = $_FILES;
				//debug($_FILES);
				$cpt1 = count ( $_FILES ['photo'] ['name'] );
				if ($_FILES ['photo'] ['name'] != '') {
					$_FILES ['photo'] ['name'] = $photo ['photo'] ['name'];
					$_FILES ['photo'] ['type'] = $photo ['photo'] ['type'];
					$_FILES ['photo'] ['tmp_name'] = $photo ['photo'] ['tmp_name'];
					$_FILES ['photo'] ['error'] = $photo ['photo'] ['error'];
					$_FILES ['photo'] ['size'] = $photo ['photo'] ['size'];
					// error_reporting(E_ALL);
					if(move_uploaded_file ( $_FILES ['photo'] ['tmp_name'], $this->template->domain_uploads_car_images ( $_FILES ['photo'] ['name'] ) )){

						echo "uploaded";
					}else{
						echo "Not uploaded";exit;
					}


					/*if(move_uploaded_file ( $_FILES ['photo'] ['tmp_name'], $this->template->domain_car_real_upload_path().$_FILES ['photo'] ['name'])){
						$photo = $_FILES ['photo'] ['name'];
						$data['icon'] = $photo;
					}else{
						//$photo = $_FILES ['photo'] ['name'];
						$data['icon'] = '';
					}*/


					//move_uploaded_file ( $_FILES ['photo'] ['tmp_name'], $this->template->domain_car_real_upload_path().$_FILES ['photo'] ['name'])
					//domain_uploads_car
					$photo = $_FILES ['photo'] ['name'];
					$data['icon'] = $photo;
				}		
				
				if (intval($id) > 0) {	
					//echo "in1"; exit();

					$cond['id'] = $id;
					// debug($data);
					// debug($cond);
					// die;
					$this->custom_db->update_record('car_details',$data,$cond);
				} else {
					//echo "out1"; exit();
					$data['supplier_id'] = $supplier_id;
					$data['created_datetime'] = date('Y-m-d H:i:s');
					$insert_id = $this->custom_db->insert_record('car_details', $data);
					//echo $this->db->last_query(); exit();
				}
				if (intval($id) > 0) {
					//echo "in2"; exit();
					$att_data['car_details_id'] = $id;
					$this->custom_db->delete_record('car_attributes', $att_data);
				} else {
					//echo "out2"; exit();
					$att_data['car_details_id'] = $insert_id['insert_id'];
				}
				if (isset($post_data['feature_id']) && is_array($post_data['feature_id']) == true) {
					foreach ($post_data['feature_id'] as $k => $v) {
						$att_data['car_features_id'] = $v;
						$this->custom_db->insert_record('car_attributes', $att_data);
					}
				}	
				redirect('car_supplier/car_list');			
		}
		$car_list = $this->car_model->car_list(0,$supplier_id);
		$page_data['car_type'] = $this->car_model->car_type_list();
		$page_data['car_class'] = $this->car_model->car_class();
		$page_data['car_features'] = $this->car_model->car_features();
		$page_data['car_transmission'] = $this->car_model->car_transmission();	
		$car_list = $car_list['data'];
		$total_row_count = 0;
		if($car_list['list']){
			$total_row_count = count($car_list['list']);

		}
		$page_data['ID'] = $id;
		$page_data['table_data'] = $car_list['list'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/car_supplier/car_list/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$config['total_rows'] = $total_row_count;
		$config['per_page'] = RECORDS_RANGE_1;
		// $this->pagination->initialize($config);
// debug($page_data);die;
		$this->template->view('car/car_list',$page_data);

	}
	public function car_make($id=0){
		$this->form_validation->set_message('required', 'Required.');
		$post_data = $this->input->post();		
		if (valid_array($post_data) == false) {
			if (intval($id) > 0) {				//edit data
				
				$tmp_data = $this->custom_db->single_table_records('car_make','*', array('id' => $id));
				if (valid_array($tmp_data['data'][0])) {
					$page_data['make_name'] = $tmp_data['data'][0]['make_name'];
				} else {
					redirect('car_supplier/car_make');
				}
			}
		} elseif (valid_array($post_data)) {
			
			$this->form_validation->set_rules('make_name', 'Type', 'required|max_length[50]|min_length[1]|is_unique[car_make.make_name]');
			$page_data['make_name'] = trim($this->input->post('make_name'));
			if ($this->form_validation->run()) {
				//add / update data
				if (intval($id) > 0) {
					$this->custom_db->update_record('car_make', $page_data, array('id' => $id));
				} else {
					$page_data['created_datetime'] = date('Y-m-d H:i:s');
					$this->custom_db->insert_record('car_make', $page_data);
				}
				redirect('car_supplier/car_make');
			}
			
		}
		$page_data['ID'] = $id;
		$page_data['data_list'] = $this->car_model->car_type_list();
		$total_row_count = 0;
		if($page_data['data_list']){
			$total_row_count = count($total_row_count);
		}
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/car_supplier/car_make/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$config['total_rows'] = $total_row_count;
		$config['per_page'] = RECORDS_RANGE_1;
		$this->pagination->initialize($config);
		
		$this->template->view('car/car_make',$page_data);
	}
	public function delete_record($table_name='',$id=0,$col=''){
		$success = false;
		if($id>0&&$table_name!=''){
			$condition_arr = array('id'=>$id);
			if($col!=''){
				$condition_arr = array($col=>$id);
			}
			if($this->custom_db->delete_record(trim($table_name),$condition_arr)){
				$success = true;
			}else{
				
			}
			if($col=='driver_fk_id'){
				if($this->custom_db->delete_record('user',array('user_id'=>$id))){
					$success = true;
				}else{
					
				}
			}
		}
		echo $success;
		exit;
	}
	/**
	 * Activate Car Assets
	 */
	function activate_car_assets($id,$table)
	{
		$cond['id'] =intval($id);
		$table = $table;
		$data['status'] = ACTIVE;
		if ($this->custom_db->update_record($table,$data,$cond)) {
			echo "1";		
		}else{
			echo "0";
		}
		exit;	
	}

	/**
	 * Deactiavte Car Assets
	 */
	function deactivate_car_assets($id,$table)
	{
		
		$data['status'] = INACTIVE;
		$cond['id'] =intval($id);
		$table = $table;
		
		if ($this->custom_db->update_record($table,$data,$cond)) {
			echo "1";		
		}else{
			echo "0";
		}
		exit;
		
	}
	public function car_transmission($id=0){
		$this->form_validation->set_message('required', 'Required.');
		$post_data = $this->input->post();		
		if (valid_array($post_data) == false) {
			if (intval($id) > 0) {				//edit data
				
				$tmp_data = $this->custom_db->single_table_records('car_transmission','*', array('id' => $id));
				if (valid_array($tmp_data['data'][0])) {
					$page_data['transmission_name'] = $tmp_data['data'][0]['transmission_name'];
				} else {
					redirect('car_supplier/car_transmission');
				}
			}
		} elseif (valid_array($post_data)) {
			
			$this->form_validation->set_rules('transmission_name', 'Type', 'required|max_length[50]|min_length[1]|is_unique[car_transmission.transmission_name]');
			$page_data['transmission_name'] = trim($this->input->post('transmission_name'));
			if ($this->form_validation->run()) {
				//add / update data
				if (intval($id) > 0) {
					$this->custom_db->update_record('car_transmission', $page_data, array('id' => $id));
				} else {
					$page_data['created_datetime'] = date('Y-m-d H:i:s');
					$page_data['status'] = ACTIVE;
					$this->custom_db->insert_record('car_transmission', $page_data);
				}
				redirect('car_supplier/car_transmission');
			}
			
		}
		$page_data['ID'] = $id;
		$page_data['data_list'] = $this->car_model->car_transmission();
		$total_row_count = 0;
		if($page_data['data_list']){
			$total_row_count = count($total_row_count);
		}
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/car_supplier/car_transmission/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$config['total_rows'] = $total_row_count;
		$config['per_page'] = RECORDS_RANGE_1;
		$this->pagination->initialize($config);
		
		$this->template->view('car/car_transmission',$page_data);
	}
	public function car_class($id=0){
		$this->form_validation->set_message('required', 'Required.');
		$post_data = $this->input->post();	
		// debug($post_data);die;	
		if (valid_array($post_data) == false) {
			if (intval($id) > 0) {				//edit data
				
				$tmp_data = $this->custom_db->single_table_records('car_class','*', array('id' => $id));
				if (valid_array($tmp_data['data'][0])) {
					$page_data['class_name'] = $tmp_data['data'][0]['class_name'];
				} else {
					redirect('car_supplier/car_class');
				}
			}
		} else if (valid_array($post_data)) {
			
			$this->form_validation->set_rules('class_name', 'Type', 'required|max_length[50]|min_length[1]|is_unique[car_class.class_name]');
			$page_data['class_name'] = trim($this->input->post('class_name'));
			// debug($page_data);die;
			if ($this->form_validation->run()) {
				// echo "in";die;
				//add / update data
				if (intval($id) > 0) {
					$this->custom_db->update_record('car_class', $page_data, array('id' => $id));
				} else {
					$page_data['created_datetime'] = date('Y-m-d H:i:s');
					$page_data['status'] = ACTIVE;
					$this->custom_db->insert_record('car_class', $page_data);
				}
				redirect('car_supplier/car_class');
			}
			else
			{
				// echo "out";die;
				$datam['msg']='Duplicate class name not allowed';
				// debug($datam);die;
				// redirect('car_supplier/car_class');
			}
			
		}
		$page_data['ID'] = $id;
		$page_data['data_list'] = $this->car_model->car_class();
		$total_row_count = 0;
		if($page_data['data_list']){
			$total_row_count = count($total_row_count);
		}
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/car_supplier/car_class/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$config['total_rows'] = $total_row_count;
		$config['per_page'] = RECORDS_RANGE_1;
		$this->pagination->initialize($config);
		$page_data['msg']=$datam['msg'];
		// debug($page_data);die;
		$this->template->view('car/car_class',$page_data);
	}
	public function car_features($id=0){
		$this->form_validation->set_message('required', 'Required.');
		$post_data = $this->input->post();		
		if (valid_array($post_data) == false) {
			if (intval($id) > 0) {				//edit data
				
				$tmp_data = $this->custom_db->single_table_records('car_features','*', array('id' => $id));
				if (valid_array($tmp_data['data'][0])) {
					$page_data['feature_name'] = $tmp_data['data'][0]['feature_name'];
				} else {
					redirect('car_supplier/car_features');
				}
			}
		} elseif (valid_array($post_data)) {
			
			$this->form_validation->set_rules('feature_name', 'Type', 'required|max_length[50]|min_length[1]|is_unique[car_features.feature_name]');
			$page_data['feature_name'] = trim($this->input->post('feature_name'));
			if ($this->form_validation->run()) {
				//add / update data
				if (intval($id) > 0) {
					$this->custom_db->update_record('car_features', $page_data, array('id' => $id));
				} else {
					$page_data['created_datetime'] = date('Y-m-d H:i:s');
					$page_data['status'] = ACTIVE;
					$this->custom_db->insert_record('car_features', $page_data);
				}
				redirect('car_supplier/car_features');
			}
			
		}
		$page_data['ID'] = $id;
		$page_data['data_list'] = $this->car_model->car_features();
		$total_row_count = 0;
		if($page_data['data_list']){
			$total_row_count = count($total_row_count);
		}
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/car_supplier/car_features/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$config['total_rows'] = $total_row_count;
		$config['per_page'] = RECORDS_RANGE_1;
		$this->pagination->initialize($config);
		
		$this->template->view('car/car_feature',$page_data);
	}
	public function vehical_list($id=0,$car_id=0){
		// debug($id);die;
		$this->form_validation->set_message('required', 'Required.');
		$post_data=$this->input->post();
// debug($post_data);die;
		if (valid_array($post_data) == false) {
			//edit data
			if($id>0)
			{
				// echo $this->entity_user_id;die;
				$tmp_data = $this->car_model->get_vehicle($id,$this->entity_user_id);
// debug($tmp_data);die;
				if(valid_array($tmp_data['data'])){
					foreach($tmp_data['data'] as $k=>$v)
		{
			$carid=$tmp_data['data'][$k]['car_id'];
			if($carid==$car_id)
			{
$data=$tmp_data['data'][$k];
					// $data['CAR_ID'] = $data['car_id'];
					$data['CAR_ID']=$car_id;
					$data['ID']=$tmp_data['data'][$k]['id'];
					// debug($data['CAR_ID']);die;
			}

		}
					
				}					
				else{
					$data['ID']=0;
					$data['CAR_ID'] =0;
				}
				

			}


		}
		else{
// echo "else";die;
			$this->form_validation->set_rules('car_id','Vehicle List','required');
			$this->form_validation->set_rules('door_type', 'Door Type', 'required');
			
		//	$data['vehicle_no'] = $this->input->post('vehicle_no');						
			$data['vendor_id'] = $this->entity_user_id;
			$data['car_id'] = $this->input->post('car_id');
			$data['branch_id'] = $this->entity_branch_id;
			$data['door_type'] = $this->input->post('door_type');
			$data['description'] = $this->input->post('description');
			$data['car_reg_no'] = $this->input->post('car_reg_no');
			$data['engine_no'] = $this->input->post('engine_no');
			
			$data['color'] = $this->input->post('color');
			$data['mileage'] = $this->input->post('mileage');
			$data['luggage']=$this->input->post('luggage');
			$data['pickup_information'] = $this->input->post('pick_up_information');
			$data['no_of_doors'] = $this->input->post('no_of_doors');
			$data['emission'] = date("Y-m-d",strtotime($this->input->post('emission')));
			$data['authorization'] = date("Y-m-d",strtotime($this->input->post('authorization')));
			$data['fitness'] = date("Y-m-d",strtotime($this->input->post('fitness')));
			$data['insurance_company'] = $this->input->post('insurance_company');
			$data['insurance_begin'] =  date("Y-m-d",strtotime($this->input->post('insurance_begin')));
			$data['insurance_end'] =  date("Y-m-d",strtotime($this->input->post('insurance_end')));
			$data['purchase_from'] = $this->input->post('purchase_from');
			$data['purchase_amount'] = $this->input->post('purchase_amount');
			$data['purchase_date'] =  date("Y-m-d",strtotime($this->input->post('purchase_date')));
			
			$data['city_id']=$this->entity_city_id;
			// debug($data);die;
			if ($this->form_validation->run()) {
// echo "in";die;
				/*
				if($_FILES['icon']['size'] > 0)
				{
					$file_name = time();
					$config['upload_path'] = 'extras/images/car_images/';
					$config['allowed_types'] = '*';
					$config['max_size']	= '1000000';
					$config['max_width']  = '';
					$config['max_height']  = '';
					$config['file_name'] = $file_name;
					
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
						
					if ( ! $this->upload->do_upload('icon'))
					{
						$error = array('error' => $this->upload->display_errors());
					}
					else
					{
						$data1 = array('upload_data' => $this->upload->data());
						$data['image']=$data1['upload_data']['file_name']; 
					}
				}
				*/
				if($id==0)
				{
					// echo "in";die;
					$data['added_by']=$this->entity_user_id;
					$data['status']=ACTIVE;
					// debug($data);die;
					$this->custom_db->insert_record('car_supplier_vehicle_management', $data);
					redirect('car_supplier/vehical_list');
				}
				else {
					// echo "out";
					// print_r($id);die;
					// debug($data);die;
					// unset($data['vehicle_no']);
					$this->custom_db->update_record('car_supplier_vehicle_management', $data, array('id' => $id,'car_id'=>$car_id));


					redirect('car_supplier/vehical_list');
				}
				
			}
			else
			{
				// echo "out";die;
			}
		
		}
		
		if(!isset($data['ID']))
			$data['ID']=$id;
		if(!isset($data['CAR_ID'])){
			$data['CAR_ID'] = $car_id;
		}
		// debug($data['ID']);
		// debug($data['CAR_ID']);
		// die;
		$total_row_count = 0;

		
		// debug($this->entity_user_id);die;
		// echo $this->db->last_query();die;
		// echo "tmp";
		// debug($tmp_data);die;
		
	
		$tmp_data = $this->car_model->get_vehicle(0,$this->entity_user_id);
	
	
	
		// echo "out";
// debug($data);die;
	
			$data['table_data'] = $tmp_data['data'];

		if($data['table_data']){
			$total_row_count = count($data['table_data']);
		}
	

		// debug($data);die;

		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/car_supplier/vehical_list/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$config['total_rows'] = $total_row_count;
		$config['per_page'] = RECORDS_RANGE_1;
		$data['car_list']  = $this->car_model->active_car_list(0,$this->entity_user_id);
		if($data['car_list']['status']==1){
			$data['car_list'] = $data['car_list']['data']['list'];	
		}else{
			$data['car_list'] = array();
		}		
// debug($data);die;
		$this->template->view('Inventory/inventory_list',$data);
	}

public function driver_list($id=0){

		$d_id = $GLOBALS['CI']->uri->segment(3);
		$segment_4 = $GLOBALS['CI']->uri->segment(4);
		// debug($id);
		// debug($d_id);
		// debug($segment_4);die;

				//error_reporting(0);
				// 	$admin_type=$this->entity_user_type;
				// if($admin_type!=ADMIN)
				// {
				// 	if($admin_type==BRANCH_ADMIN)
				// 		privilege_handler('p28');
				// 	elseif($admin_type==MID_OFFICE)
				// 	privilege_handler('p41');
				// 	else
				// 		redirect('general/dashboard');
				// }
				$this->form_validation->set_message('required', 'Required.');
				
				$post_data=$this->input->post();

				// debug($post_data);

				if (valid_array($post_data) == false) {
					//edit data
					
					if($id>0 )
					{
						// echo "if";die;
				$tmp_data = $this->car_model->get_driver_inventory($id);
						// debug($tmp_data);die;
						// echo $this->db->last_query();die;
						if(valid_array($tmp_data['data']))
						{
							$data=$tmp_data['data'][0];
							// echo "if";
							// debug($data);die;
						}
						else 
							$data['ID']=$id;
						// echo "view";
						// debug($data);
						// exit;
					}
				}
				else{
// echo "in";
			 // debug($post_data);die;
			// debug($tmp_data);die;
			// echo "add";
			// $this->form_validation->set_rules('branch_id', 'Branch Name', 'required');
			$this->form_validation->set_rules('full_name', 'Driver Name', 'required');
			if($this->entity_user_type == ADMIN)
			{
			$this->form_validation->set_rules('supplier_id', 'Supplier Name', 'required');
			$this->form_validation->set_rules('branch_id', 'Branch User Name', 'required');
		}
			$this->form_validation->set_rules('email', 'Email', 'required');

			$this->form_validation->set_rules('password', 'Password', 'required');

			$this->form_validation->set_rules('phone', 'Phone Number', 'required');
			$this->form_validation->set_rules('address', 'Address', 'required');
			//$this->form_validation->set_rules('country_id', 'Country', 'required');
			//$this->form_validation->set_rules('full_city_name', 'Location', 'required');
			//$this->form_validation->set_rules('owner', 'Owner', 'required');
			
			$user_data['branch_id'] = $this->entity_branch_id;  //updated
			$data['agency_name'] = $this->entity_agency_name;
			$user_data['supplier_id'] = $this->entity_user_id;
			//$user_data['branch_id'] = $this->entity_branch_id;
			if($this->entity_user_type == ADMIN)
			{
			$user_data['branch_id'] = $this->input->post('branch_id');
			$data['agency_name'] = $this->entity_agency_name;
			$user_data['supplier_id'] = $this->input->post('supplier_id');
		}
		$user_data['branch_id'] = $this->input->post('branch_id');
			$user_data['email'] = $this->input->post('email');
			$user_data['user_name'] = $this->input->post('email');
			$user_data['password'] = md5($this->input->post('password'));
			$user_data['user_type'] = CAR_DRIVER;
			$user_data['uuid'] = time().rand(1, 1000);
			$user_data['domain_list_fk'] = get_domain_auth_id();
// $agency="select agency_name from user where supplier_id=$this->entity_user_id";
			
			$user_data['country_code'] = $this->input->post('country_id');
			$user_data['phone'] = $this->input->post('phone');

			$user_data['status'] =ACTIVE;
// echo "in";die;
 
			$data['full_name'] = $this->input->post('full_name');
			$data['phone'] = $this->input->post('phone');
			$data['address'] = $this->input->post('address');
			$data['country_id'] = $this->input->post('country_id');
			//$data['city_id'] = $this->input->post('city_id');
			$data['city_name'] = $this->input->post('city_name');
			$data['full_city_name'] = $this->input->post('full_city_name');
			$data['current_location'] = $this->input->post('current_location');
			//$data['owner'] = $this->input->post('owner');		
			// echo "in";die;
			$data['vendor_id'] = $this->entity_user_id;
			$data['license_no']=$this->input->post('license_no');
			$data['badge_no']=$this->input->post('badge_no');
			$data['email'] = $this->input->post('email');
			$data['password'] = $this->input->post('password');
			$data['validity']=$this->input->post('validity');
			if($data['validity']!="")
				$data['validity']=date("Y-m-d",strtotime($data['validity']));
			else 
				$data['validity']=date("Y-m-d");
			
			$data['badge_validity']=$this->input->post('badge_validity');
			if($data['badge_validity']!="")
				$data['badge_validity']=date("Y-m-d",strtotime($data['badge_validity']));
			else 
				$data['badge_validity']=date("Y-m-d");
			$data['police_validity']=$this->input->post('police_validity');
			if($data['police_validity']!="")
				$data['police_validity']=date("Y-m-d",strtotime($data['police_validity']));
			else 
				$data['police_validity']=date("Y-m-d");
			$data['cart_validity']=$this->input->post('cart_validity');
			if($data['cart_validity']!="")
				$data['cart_validity']=date("Y-m-d",strtotime($data['cart_validity']));
			else 
				$data['cart_validity']=date("Y-m-d");
			
				
			$data['police_number']=$this->input->post('police_number');
			$data['cart_number']=$this->input->post('cart_number');
			// debug($data);die;
			// echo $this->form_validation->run();
			// exit;
			//if ($this->form_validation->run()) {				
				// echo "ind";die;
				$photo = $_FILES;
				$cpt1 = count ( $_FILES ['photo'] ['name'] );
				if ($_FILES ['photo'] ['name'] != '') {
					$_FILES ['photo'] ['name'] = $photo ['photo'] ['name'];
					$_FILES ['photo'] ['type'] = $photo ['photo'] ['type'];
					$_FILES ['photo'] ['tmp_name'] = $photo ['photo'] ['tmp_name'];
					$_FILES ['photo'] ['error'] = $photo ['photo'] ['error'];
					$_FILES ['photo'] ['size'] = $photo ['photo'] ['size'];
					
					move_uploaded_file ( $_FILES ['photo'] ['tmp_name'], $this->template->domain_image_full_path ( $_FILES ['photo'] ['name'] ) );
					$photo = $_FILES ['photo'] ['name'];
					$data['driver_photo'] = $photo;
					$user_data['image'] = $photo;
				}
				
				// echo "in";die;
				if($id>0)
				{	
					// echo "if part";
				// debug($id);
					// debug($user_data);
					$data['branch_id']=$user_data['branch_id'];
					$data['vendor_id']=$user_data['supplier_id'];
					// debug($data);
					// die;

					$this->custom_db->update_record('user',$user_data,array('user_id'=>$id));

					$this->custom_db->update_record('driver_list', $data, array('driver_fk_id' => $id));
// echo $this->db->last_query();die;
					redirect('car_supplier/driver_list');
				}
				else {
					//echo "elsepart";
// debug($user_data);
					$user_table_driver_id = $this->custom_db->insert_record('user', $user_data);

					$data['driver_fk_id'] = $user_table_driver_id['insert_id'];
					// debug($user_data);
                    // debug($user_table_driver_id['insert_id']);
					// debug($data);die;
					if($this->entity_user_type == ADMIN)
					$data['vendor_id']=$user_data['supplier_id'];
				$data['branch_id']=$user_data['branch_id'];
					// exit;
					// debug($data);
					// die;
					$this->custom_db->insert_record('driver_list', $data);
					// echo $this->db->last_query();die;
					redirect('car_supplier/driver_list');
				}
			//}
		}
        $data['ID']=$id;
		if($this->entity_user_type == ADMIN)
		{
			// echo "in";die;
			if($id>0)
			{
				// echo "inn";
				$query="select * from driver_list where driver_fk_id=$id";
				$user_data=$this->db->query($query)->result_array();
				// $user_data['status']=1;
				//$tmp_data['user_data']=$user_data;
				$tmp_data = $this->car_model->get_driver_inventory(0,0);
// $tmp_data = $this->car_model->get_driver_inventory($id);
 // echo $this->db->last_query();die;
// debug($tmp_data);die;
// echo $this->db->last_query();die;
			}
			else
			{
            $tmp_data = $this->car_model->get_driver_inventory(0,0);
        }
            // echo $this->db->last_query();die;
            // debug($tmp_data);die;
        }
		
		else
		{
			// echo "out";die;
			
			if($id>0)
			{
				// echo "outfff";die;
				$query="select * from driver_list where driver_fk_id=$id";
				$user_data=$this->db->query($query)->result_array();
				// $user_data['status']=1;
				//$tmp_data['user_data']=$user_data;
				$tmp_data = $this->car_model->get_driver_inventory(0,$this->entity_user_id);
				// debug($tmp_data);die;
// $tmp_data = $this->car_model->get_driver_inventory($id);
// echo $this->db->last_query();die;
			}
			else
			{
				// echo "out1";die;
		    $tmp_data = $this->car_model->get_driver_inventory(0,$this->entity_user_id);
		    
		   }
		    // echo $this->db->last_query();die;
		    // debug($tmp_data);exit;
		}
		// echo $this->db->last_query();die;
		// debug($user_data);
		// debug($tmp_data);die;
        // echo $this->db->last_query();die;
		if($tmp_data['status']==1){
			$data['table_data'] = $tmp_data['data'];
			$data['user_data']=	$user_data;
		}else{
			$data['table_data'] = array();
		}
		$total_row_count = 0;
		if($data['table_data']){
			$total_row_count = count($data['table_data']);
		}
        $data ["country"] = $this->car_model->get_active_country_list()['data'];
        $this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/car_supplier/driver_list/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$config['total_rows'] = $total_row_count;
		$config['per_page'] = RECORDS_RANGE_1;
		// debug($data);die;
		$this->template->view('Inventory/driver_list',$data);
	}

		//$this->load->view('inventory/driver_inventory',$data);
	

	public function get_crs_city($city_id) {
		
		$city = $this->car_model->get_crs_city_list ( $city_id );
		
		$sHtml = "";
		$sHtml .= '<option value="">Select City</option>';
		if (! empty ( $city )) {
			foreach ( $city as $key => $value ) {

				$sHtml .= '<option value="' . $value->origin . '">' . $value->city_name . '</option>';
			}
		}
		
		echo json_encode ( array (
				'result' => $sHtml 
		) );
		exit ();
	}

	function get_active_city($origin,$select_city=''){
		$city = $this->custom_db->single_table_records('car_active_country_city_master','*',array('country_id'=>$origin));
		$sHtml = "";
		
		$sHtml .= '<option value="">Select City</option>';
		if ($city['status']==1) {
			foreach ( $city['data'] as $key => $value ) {
				$select = '';
				if($select_city){
					if($select_city==$value['origin']){
						$select = "selected=selected";
					}
				}
				$sHtml .= '<option value="' . $value['origin']. '" '.$select.'>' . $value['city_name'] . '</option>';
			}
		}
		
		echo json_encode ( array (
				'result' => $sHtml 
		) );
		exit ();
	}
	

	public function car_performance($id=0){
		$post_data=$this->input->post();

		if (valid_array($post_data) == false) {
			//edit
			
			if($id>0)
			{
				$tmp_data = $this->car_model->get_performance_list($id);

				$data= $tmp_data['data'][0];

			}
			
		}else{
			
			$this->form_validation->set_rules('vehicle_id', 'Vehicle Number', 'required');
			$this->form_validation->set_rules('type', 'Performance Type', 'required');
			$this->form_validation->set_rules('cost', 'Cost', 'required');
			$this->form_validation->set_rules('item', 'Item', 'required');
			$this->form_validation->set_rules('amount', 'Amount', 'required');
			$this->form_validation->set_rules('date', 'Date', 'required');
			
			$data['vehicle_id'] = $this->input->post('vehicle_id');
			$data['type'] = strip_tags($this->input->post('type'));
			$data['cost'] = $this->input->post('cost');
			$data['item'] = $this->input->post('item');
			$data['amount'] = $this->input->post('amount');
			$data['date'] = date("Y-m-d",strtotime($this->input->post('date')));
			$data['description'] = strip_tags($this->input->post('description'));
			
			if ($this->form_validation->run()) {
				if($id==0)
				{
					$this->custom_db->insert_record('vehicle_performance', $data);
				}
				else 
				{
					$this->custom_db->update_record('vehicle_performance', $data,array('id'=>$id));
				}
				redirect('car_supplier/car_performance');
			}
			
		}
		
		$tmp_data = $this->car_model->get_performance_list();
		$data['table_data'] = $tmp_data['data'];
		$data['ID']=$id;
		$total_row_count = 0;
		if($data['table_data']){
			$total_row_count = count($data['table_data']);
		}
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/car_supplier/car_performance/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$config['total_rows'] = $total_row_count;
		$config['per_page'] = RECORDS_RANGE_1;
		$data['vehical_list'] = $this->car_model->get_vehicle_inventory(0,$this->entity_user_id);
		if($data['vehical_list']['status']==1){
			$data['vehical_list'] = $data['vehical_list']['data'];
		}else{
			$data['vehical_list'] = array();
		}
		//debug($data);exit;
		$this->template->view('Inventory/car_performance',$data);
	}
	public function map_car_driver(){

		$data =array();
		$total_row_count = 0;
		$data['table_data']  = $this->car_model->map_car_driver($this->entity_user_id,$this->entity_branch_id);

		if($data['table_data']['data']){
			$total_row_count = count($data['table_data']['data']);
		}else{
			$data['table_data'] = array();
		}
		if($data['table_data']){
			$data['table_data']  = $data['table_data']['data'];	
		}
		
		$data['driver_list'] = $this->car_model->get_driver_inventory(0,$this->entity_user_id,ACTIVE);
		$data['driver_list'] = $data['driver_list']['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/car_supplier/map_car_driver/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$config['total_rows'] = $total_row_count;
		$config['per_page'] = RECORDS_RANGE_1;
		// debug($data);
		// exit;
		$this->template->view('Inventory/map_car_driver',$data);
	}
	public function update_map_car_driver($car_id=0,$driver_id=0,$map_id=0){
		
		$check_driver_exists = $this->custom_db->single_table_records('map_car_driver_list','driver_id',array('driver_id'=>$driver_id,'available_status'=>1));
		
		if($check_driver_exists['status']==0){
			if($map_id==0){
				$data['supplier_id'] = $this->entity_user_id;
				$data['branch_id'] = $this->entity_branch_id;
				$data['car_id'] = $car_id;
				$data['driver_id'] = $driver_id;
				$data['available_status'] = ACTIVE;
				$data['current_status'] = ACTIVE;
				$data['created_datetime'] = date('Y-m-d H:i:s');

				if($this->custom_db->insert_record('map_car_driver_list',$data)){
					echo "1";
				}else{
					echo "0";
				}
			}else{
				$data['driver_id'] = $driver_id;
				$data['updated_datetime'] =date('Y-m-d H:i:s');
				if($this->custom_db->update_record('map_car_driver_list',$data,array('origin'=>$map_id))){
					echo "1";
				}else{
					echo "0";
				}
			}
		}else{

			echo "2";
		}
	
		exit;
	}
	//update driver in that booking date
	public function update_car_driver_booking($car_id=0,$driver_id=0,$pickup_date,$pickup_time,$origin_id){
		
		if($car_id!=0&&$driver_id!=0){
			//check driver already assigned for another trip 

			$query = "select * from car_booking_trip as t left join car_booking_details as b ON b.booking_reference = t.booking_reference where t.driver_id=".$driver_id." AND t.pickup_date='".$pickup_date."' AND t.pickup_time='".$pickup_time."'";

			$booking_arr = array();
			$execute_query = $this->db->query($query);
			if($execute_query->num_rows()!=''){
				$booking_arr = $execute_query->result_array();
			}			
			//debug($booking_arr);
			if($booking_arr){
				echo "0";
			}
			else{
				
				$data['updated_datetime']  =date('Y-m-d H:i:s');
				$data['driver_id'] = $driver_id;
				if($this->custom_db->update_record('car_booking_trip',$data,array('origin'=>$origin_id))){
					echo "1";
				}else{
					echo "2";
				}
				


			}
		}
		
		exit;
	}
	public function driver_reports($offset=0){
		
		$get_data = $this->input->get();
		$filter_condition = array();
		if(valid_array($get_data)){
			if (empty($get_data['driver_id']) == false && strtolower($get_data['driver_id'])!='all') {
				$filter_condition[] = array('u.user_id', '=', $this->db->escape($get_data['driver_id']));				
			}
			if (empty($get_data['car_id']) == false && strtolower($get_data['car_id'])!='all') {
				$filter_condition[] = array('c.id', '=', $this->db->escape($get_data['car_id']));				
			}
			if (empty($get_data['status']) == false && strtolower($get_data['status']) != 'all') {
				$filter_condition[] = array('t.booking_status', '=', $this->db->escape($get_data['status']));
			}
            $filter_condition[] = array('c.vehicle_no', '=', $this->db->escape($get_data['vehicle_no']));
		}
		//total_records driver report
		$total_records = $this->car_model->booking_trip_details($filter_condition,true);

		$this->load->library('pagination');		
		$trip_details = $this->car_model->booking_trip_details($filter_condition, false, $offset, RECORDS_RANGE_2);
		
		$page_data['table_data'] = $trip_details;
		/** TABLE PAGINATION */
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/car_supplier/car_booking_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$page_data['status_options'] = get_enum_list('booking_status_options');
		$page_data['search_params'] = $get_data;	
		// debug($page_data);die;
		$this->template->view('car_reports/driver_report',$page_data);
	}
	public function update_driver_location(){

		$post_params = $this->input->post();

		$update_data  = array();
		$update_data['current_location'] = $post_params['locality'];
		$update_data['driver_lat'] = $post_params['lat'];
		$update_data['driver_lon'] = $post_params['lon'];
		$update_data['full_address'] = $post_params['formatted_address'];
		$update_data['current_status'] = true;
		$cond = array();
		$cond['supplier_id'] =$post_params['supplier_id'];
		$cond['driver_id']  = $post_params['driver_id'];

		if($this->custom_db->update_record('map_car_driver_list',$update_data,$cond)){

			echo "1";
		}else{

			$driver_data = $this->custom_db->single_table_records('map_car_driver_list','current_location',array('supplier_id'=>$post_params['supplier_id'],'driver_id'=>$post_params['driver_id'],'current_location'=>$post_params['locality']),0,1);
			if($driver_data['status']==1){
				echo "2";

			}else{
				echo "0";	
			}
			
		}
		//echo $this->db->last_query();
		exit;
	}
	public function add_location($driver_id){
		// echo "in";
		$data['driver_id'] = $driver_id;
		// debug($data);die;
		$post_params = $this->input->post();
		$cond = array();
		if(valid_array($post_params)){
			// echo "in2";die;
			// debug($post_params);die;
			$this->form_validation->set_rules('location','Location','required');
		
			if($this->form_validation->run()){
				
				$update_data['current_location'] = $post_params['location'];
				$update_data['driver_lat'] = $post_params['lat'];
				$update_data['driver_lon'] = $post_params['lng'];
				$update_data['full_address'] = $post_params['full_city'];
				$update_data['current_status'] = true;
				$cond['supplier_id'] =$this->entity_supplier_id;
				$cond['driver_id']  = $driver_id;
				
				// debug($cond);die;
				if($s=$this->custom_db->update_record('map_car_driver_list',$update_data,$cond)){
					// echo "in";die;
					// debug($s);die;
					// echo $this->db->last_query();die;
					if($s==1)
					// echo "in";die;
					redirect('car_supplier/add_location/'.$driver_id);

				}
				else
				{
					// echo "out";die;
					$update_data['supplier_id'] = $this->entity_supplier_id;
					$update_data['driver_id']=$driver_id;
					$query="select * from map_car_driver_list where driver_id=".$update_data['driver_id'];
					$result=$this->db->query($query);
					// echo $result->num_rows();die;
					if($result->num_rows()>0)
					{
$this->custom_db->update_record('map_car_driver_list',$update_data,$cond);
					}
					else
					{
				$query=$this->custom_db->insert_record('map_car_driver_list',$update_data);
			}
					// debug($query);die;	
				}
				
			}
		}
		// echo "out";die;
		$supplier_id = $this->entity_supplier_id;
		$sup_country_details =$this->car_model->supplier_country($supplier_id);
		$data['iso_country_code'] = $sup_country_details['data'][0]['iso_country_code'];
		// debug($data);die;
		$get_driver_location_details = $this->custom_db->single_table_records('map_car_driver_list','driver_id,supplier_id,current_location,driver_lat,driver_lon,full_address',array('driver_id'=>$driver_id,'current_status'=>1),0,1);
		// echo $this->db->last_query();die;
		// debug($get_driver_location_details);die;
		$data['driver_data'] = $get_driver_location_details['data'][0];
		// debug($data);die;
		$this->template->view('car_reports/add_driver_location',$data);
	}

	/*
	*Function to check current date avaiable and ontrip and waiting report
	*/
	public function car_report_today($offset=0){
		
		$get_data = $this->input->get();
		$filter_condition = array();
		if(valid_array($get_data)){
			if (empty($get_data['driver_id']) == false && strtolower($get_data['driver_id'])!='all') {
				$filter_condition[] = array('u.user_id', '=', $this->db->escape($get_data['driver_id']));				
			}
			if (empty($get_data['car_id']) == false && strtolower($get_data['car_id'])!='all') {
				$filter_condition[] = array('c.id', '=', $this->db->escape($get_data['car_id']));				
			}
			if (empty($get_data['status']) == false && strtolower($get_data['status']) != 'all') {
				$filter_condition[] = array('t.trip_status', '=', $this->db->escape($get_data['status']));
			}
		}
		//total_records driver report
		$total_records = $this->car_model->trip_details($filter_condition,true);
		$this->load->library('pagination');		
		$trip_details = $this->car_model->trip_details($filter_condition, false, $offset, RECORDS_RANGE_2);
		$page_data['table_data'] = $trip_details;
		/** TABLE PAGINATION */
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/car_supplier/car_report_today/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$page_data['status_options'] = get_enum_list('car_driver_trip_status');
		$page_data['search_params'] = $get_data;	
		$page_data['driver_list'] = $this->car_model->get_driver_inventory(0,$this->entity_user_id,ACTIVE);
		$page_data['driver_list'] = $page_data['driver_list']['data'];
		$this->template->view('car_reports/car_today_report',$page_data);
	}

	public function car_booking_report($offset=0){

		$get_data = $this->input->get();
		
		$filter_condition = array();
		if(valid_array($get_data)){
			if (empty($get_data['created_by_id']) == false && strtolower($get_data['created_by_id'])!='all') {
				$filter_condition[] = array('u.user_id', '=', $this->db->escape($get_data['created_by_id']));				
			}
			if (empty($get_data['booking_reference']) == false) {
				$filter_condition[] = array('b.booking_reference', ' like ', $this->db->escape('%'.$get_data['booking_reference'].'%'));
			}

			if (empty($get_data['status']) == false && strtolower($get_data['status']) != 'all') {
				$filter_condition[] = array('b.booking_status', '=', $this->db->escape($get_data['status']));
			}
		}
		//total_records driver report
		$total_records = $this->car_model->booking_trip_details($filter_condition,true);
		$this->load->library('pagination');		
		$trip_details = $this->car_model->booking_trip_details($filter_condition, false, $offset, RECORDS_RANGE_2);

		//$this->load->library('booking_data_formatter');
		//$format_data = $this->booking_data_formatter->format_car_booking_data($trip_details,'admin');

		$page_data['table_data'] = $trip_details;
		/** TABLE PAGINATION */
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/car_supplier/car_booking_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$page_data['status_options'] = get_enum_list('booking_status_options');
		$page_data['search_params'] = $get_data;	
		$page_data['driver_list'] = $this->car_model->get_driver_inventory(0,$this->entity_user_id,ACTIVE);
		// echo $this->db->last_query();die;
		$page_data['driver_list'] = $page_data['driver_list']['data'];
		// debug($page_data);die;
		$this->template->view('car_reports/car_supplier_reports',$page_data);
	}
	public function get_branch_supplier_list($offset=0){
// echo 1;exit();
		$total_records = $this->car_model->get_branch_supplier(array(),true);
		$data_list = $this->car_model->get_branch_supplier(array(), false, $offset, RECORDS_RANGE_2);

		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/car_supplier/get_branch_supplier_list/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		// $this->pagination->initialize($config);
		$page_data['data_list'] = $data_list;
		/** TABLE PAGINATION */
		$this->template->view('car/branch_supplier_user',$page_data);
	}
	public function supplier_car_drivers(){
		$page_data = array();
		
		$total_records = $this->car_model->get_supplier_car_drivers(array(),true);
		$data_list = $this->car_model->get_supplier_car_drivers(array(),false);	
		// debug($data_list);
		// exit;
		/** TABLE PAGINATION */
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/car_supplier/supplier_car_drivers/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		// $this->pagination->initialize($config);
		$page_data['data_list'] = $data_list;
		/** TABLE PAGINATION */
		$this->template->view('car/supplier_car_driver',$page_data);
	}
	public function get_all_supplier_driver_car_admin($branch_id=0,$supplier_id=0){
		$car_list = $this->car_model->get_vehicle_inventory_admin(0,$branch_id,$supplier_id);
		$total_records= 0;
		if($car_list['data']){
			$total_records = count($car_list['data']);
		}
		/** TABLE PAGINATION */
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/car_supplier/car_booking_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		$page_data['data_list'] = $car_list['data'];
		
		/** TABLE PAGINATION */
		$this->template->view('car/supplier_car_list',$page_data);
	}
	public function get_all_supplier_driver_car($name='',$supplier_id=''){
		if($name=='car'){
			$car_list = $this->car_model->get_vehicle_inventory(0,$supplier_id);
			$total_records= 0;
			if($car_list['data']){
				$total_records = count($car_list['data']);
			}
			/** TABLE PAGINATION */
			$this->load->library('pagination');
			if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
			$config['base_url'] = base_url().'index.php/car_supplier/car_booking_report/';
			$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
			$page_data['total_rows'] = $config['total_rows'] = $total_records;
			$config['per_page'] = RECORDS_RANGE_2;
			$this->pagination->initialize($config);
			$page_data['data_list'] = $car_list['data'];
            $page_data['name'] = $name;

			/** TABLE PAGINATION */
			$this->template->view('car/supplier_car_list',$page_data);
		}elseif($name=='driver'){
			$car_list = $this->car_model->get_driver_inventory(0,$supplier_id);
// echo "driver";
			// debug($car_list);die;
			$total_records= 0;
			if($car_list['data']){
				$total_records = count($car_list['data']);
			}
			/** TABLE PAGINATION */
			$this->load->library('pagination');
			if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
			$config['base_url'] = base_url().'index.php/car_supplier/car_booking_report/';
			$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
			$page_data['total_rows'] = $config['total_rows'] = $total_records;
			$config['per_page'] = RECORDS_RANGE_2;
			$this->pagination->initialize($config);
			$page_data['data_list'] = $car_list['data'];
			$page_data['name'] = $name;

			// debug($page_data);die;
			/** TABLE PAGINATION */
			$this->template->view('car/supplier_driver_list',$page_data);
		}
		


	}
	public function supplier_booking_report($offset=0){
 // echo "in";die;
		$get_data = $this->input->get();
		 // echo "test";
		 // debug($get_data);die;
        /*$filter_data = $this->format_basic_search_filters('car');
        // debug($filter_data);die;
        $page_data['from_date'] = $filter_data['from_date'];
        $page_data['to_date'] = $filter_data['to_date'];
        $condition = $filter_data['filter_condition'];*/
		$filter_condition = array();
		
		if(valid_array($get_data)){
			// echo "in";die;
			if (empty($get_data['created_by_id']) == false && strtolower($get_data['created_by_id'])!='all') {

				$filter_condition[] = array('u.user_id', '=', $this->db->escape($get_data['created_by_id']));				
			}
			if (empty($get_data['status']) == false && strtolower($get_data['status']) != 'all') {
				$filter_condition[] = array('b.booking_status', '=', $this->db->escape($get_data['status']));
			}
		}
		// echo "out";die;
		//total_records driver report
		$total_records = $this->car_model->booking_trip_details($filter_condition,true);
		$this->load->library('pagination');		
		$trip_details = $this->car_model->booking_trip_details($filter_condition, false, $offset, RECORDS_RANGE_2);
		
		//$this->load->library('booking_data_formatter');
		//$format_data = $this->booking_data_formatter->format_car_booking_data($trip_details,'admin');

		$page_data['table_data'] = $trip_details;
		// echo "test1";
		// debug($page_data);die;
		/** TABLE PAGINATION */
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/car_supplier/car_booking_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$page_data['status_options'] = get_enum_list('booking_status_options');
		$page_data['search_params'] = $get_data;	
		$page_data['supplier_list'] = $this->car_model->get_supplier_list(0,$this->entity_user_id,ACTIVE);

		//$page_data['supplier_list'] = $page_data['supplier_list'];
		// debug($page_data);die;
		// echo "in";die;
		$this->template->view('car_reports/branch_supplier_report',$page_data);
	}
}