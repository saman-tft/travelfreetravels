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
class Branch_Users extends CI_Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'Supplierpackage_Model' );
		$this->load->model('car_model');
	}
	/*************car supplier details******/
	public function add_car_branch($id){
// debug($id);die;
		if($id>0)
		{
			// echo "in1";die;
			$query="select U.* from user U left join user T on U.branch_id=T.user_id where U.user_id=$id";
			$data['user_data'] = $this->db->query($query)->row_array();
			// debug($data);exit;
			$data['country_origin']=$data['user_data']['country_name'];
			$data['city_origin']=$data['user_data']['city'];
			if($data['user_data']['branch_id']==0)
				{
					$branchid=2;
					// echo "in";die;
								$admin_agency=$this->entity_agency_name;
			$data['user_data']['new_agency_name']=$admin_agency;
			/*$data ["country"] = $this->Supplierpackage_Model->get_countries ();
			$data['city_list'] = $this->Supplierpackage_Model->get_crs_city_list($data['user_data']['country_code']);Removed for active country and city list*/ 
			$data ["country_list"] = $this->car_model->get_active_country_list ();
// echo $this->db->last_query();die;
        // debug($data);die;
        $data ["country_list"] = $data ["country_list"]['data'];
			// debug($data['user_data']);
			// debug($data['city_list']);exit;
			/*$data = array(  
'branch_id' => $branchid,  
'agency_name'=>$admin_agency
);  
$this->db->where('user_id', $id);  
$this->db->update(‘user’, $data);  
			}*/
				

			// debug($data);die;
		}

	}
	else
	{
		// echo "in";die;
	/*$data ["country"] = $this->Supplierpackage_Model->get_countries ();
	$data['city_list'] = $this->Supplierpackage_Model->get_crs_city_list($data['user_data']['country_code']);*/
	$data ["country_list"] = $this->car_model->get_active_country_list ();
// echo $this->db->last_query();die;
        // debug($data);die;
        $data ["country_list"] = $data ["country_list"]['data'];
	}	
	// debug($data);die;
		$this->template->view ( 'branch/add_branch', $data );

}
	public function add_branch($id=0){

		$post_params = $this->input->post();		
		
		// debug($post_params);die;
				if(valid_array($post_params)){		
			$this->form_validation->set_rules('supplier_name', 'Branch Users', 'required');	

			$this->form_validation->set_rules('email', 'Email', 'required|is_unique[user.email]');

			$this->form_validation->set_rules('country', 'Country', 'required');

			$insert_arr = array();
			$insert_arr['agency_name'] = $post_params['supplier_name'];

			$insert_arr['first_name'] = $post_params['supplier_name'];
			//$insert_arr['agent_name'] = $post_params['reg_no'];
			$insert_arr['email'] = $post_params['email'];
			$insert_arr['user_name']=$post_params['email'];
			$insert_arr['phone'] = $post_params['phone_no'];
			$insert_arr['pan_number'] = $post_params['pan_no'];
			//$insert_arr['aadhar_no'] = $post_params['adhar_no'];
			//$insert_arr['country'] = $post_params['country'];
			$insert_arr['country_name'] = $post_params['country'];
			$insert_arr['city'] = $post_params['cityname'];
			$insert_arr['location'] = $post_params['location'];
			$insert_arr['address'] = $post_params['address'];
			$insert_arr['password'] = md5($post_params['password']);
			$photo = $_FILES;
			$cpt1 = count ( $_FILES ['photo'] ['name'] );
			if ($_FILES ['photo'] ['name'] != '') {
				$_FILES ['photo'] ['name'] = $photo ['photo'] ['name'];
				$_FILES ['photo'] ['type'] = $photo ['photo'] ['type'];
				$_FILES ['photo'] ['tmp_name'] = $photo ['photo'] ['tmp_name'];
				$_FILES ['photo'] ['error'] = $photo ['photo'] ['error'];
				$_FILES ['photo'] ['size'] = $photo ['photo'] ['size'];
				
				// if(move_uploaded_file ( $_FILES ['photo'] ['tmp_name'], $this->template->domain_image_upload_path().$_FILES ['photo'] ['name'] ) ){
				// 	$photo = $_FILES ['photo'] ['name'];
				// 	$insert_arr['image'] = $photo;
				// 	echo "ds";die;
					
				// }
				// else{
				// 	$photo = $_FILES ['photo'] ['name'];
				// 	$insert_arr['image'] = 'no_image';
				// 	echo "wew";die;
				// }

				$r=move_uploaded_file ( $_FILES ['photo'] ['tmp_name'], $this->template->domain_image_full_path( $_FILES ['photo'] ['name'] ) );

				$photo = $_FILES ['photo'] ['name'];
				$insert_arr['image'] = $photo;
			}
			if($id>0){
                unset($insert_arr['email']);
                unset($insert_arr['password']);

                // debug($insert_arr);exit;
				$this->custom_db->update_record('user',$insert_arr,array('user_id'=>$id));

			}else{
				$insert_arr['created_by_id'] = $this->entity_user_id;
                $insert_arr['created_datetime'] = date ( 'Y-m-d H:i:s' );
				$insert_arr['user_type']  =CAR_BRANCH_USER;
				$insert_arr['uuid'] = time().rand(1, 1000);
				$insert_arr['status'] = ACTIVE;
				$insert_arr['domain_list_fk'] = get_domain_auth_id();

				// debug($insert_arr);exit();
				$this->custom_db->insert_record('user',$insert_arr);
			}
			

			$data ["country"] = $this->Supplierpackage_Model->get_countries ();
		
			redirect(base_url().'index.php/branch_users/car_branch_list');
		}else{
			if($id>0){
					redirect(base_url().'index.php/branch_users/car_branch_list/'.$id);
			}
		}
	}
	public function get_crs_city($city_id,$select_city='') {

		$city = $this->Supplierpackage_Model->get_crs_city_list ( $city_id );

		$sHtml = "";
		$sHtml .= '<option value="">Select City</option>';
		if (! empty ( $city )) {
			foreach ( $city as $key => $value ) {
				$selected = '';
				if($select_city){
					if($select_city==$value->id){
						$selected="selected=selected";
					}
				}
				$sHtml .= '<option value="' . $value->id . '" '.$selected.'>' . $value->city . '</option>';
			}
		}

		echo json_encode ( array (
				'result' => $sHtml 
		) );
		exit ();
	}
	public function car_branch_list($id=0,$offset=0){
		// echo  1; exit();
		//$supplier_list = $this->custom_db->single_table_records('car_supplier_list','*');
		$get_data = $this->input->get();
		$filter_condition = array();
		if(valid_array($get_data)){
			if (empty($get_data['user_id']) == false && strtolower($get_data['user_id'])!='all') {
				$filter_condition[] = array('u.user_id', '=', $this->db->escape($get_data['user_id']));				
			}
			if (empty($get_data['country']) == false && strtolower($get_data['country'])!='all') {
				$filter_condition[] = array('u.country_name', '=', $this->db->escape($get_data['country']));				
			}
			if (empty($get_data['email']) == false && strtolower($get_data['email']) != 'all') {
				$filter_condition[] = array('u.email', '=', $this->db->escape($get_data['email']));
			}
		}

		$total_records = $this->car_model->get_branch_user_list($filter_condition,true);
		// debug($total_records);exit();	


		
		if($id>0){
			// echo "in";die;

			$filter_condition[] = array('u.user_id', '=', $this->db->escape($id));	

			$edit_data = $this->car_model->get_branch_user_list($filter_condition,false,$offset,RECORDS_RANGE_1,$id);
			$data = $edit_data[0];
			
		}else{
			// echo "else";die;
			$data_list = $this->car_model->get_branch_user_list($filter_condition,false,$offset,RECORDS_RANGE_1);	
			
			$data['table_data'] = $data_list;

		}		
		// echo "out";die;
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/branch_users/car_branch_list/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_1;

		/*$this->pagination->initialize($config);
		$data['search_params'] = $get_data;
		$data['ID'] = $id;*/

		// $data ["country_list"] = $this->Supplierpackage_Model->get_countries ();
$data ["country_list"] = $this->car_model->get_active_country_list ();
// echo $this->db->last_query();die;
        // debug($data);die;
        $data ["country_list"] = $data ["country_list"]['data'];
		$branch_users_info = $this->custom_db->single_table_records('user','*',array('user_type'=>CAR_BRANCH_USER,'domain_list_fk'=>get_domain_auth_id()));
		
		$data['branch_users'] = magical_converter(array('k' => 'user_id', 'v' => 'agency_name'), $branch_users_info);
// debug($data);die;
		
		$this->template->view ( 'branch/branch_list', $data );

	}
		/**
	 * Activate User Account
	 */
	function activate_account($user_id, $uuid)
	{
		$cond['user_id'] = intval($user_id);
		$cond['uuid'] = $uuid;
		$data['status'] = ACTIVE;
		//if branch user block means corresponding supplier,car,driver everything will be block
		
		//active branch user
		if($this->custom_db->update_record('user',$data,$cond)){
			//update supplier,driver
			$sup_cond['branch_id'] = $user_id;
			 if($this->custom_db->update_record('user',$data,$sup_cond)){
			 	echo "1";
			 }else{
			 	echo "0";
			 }
		}else{
			echo "0";
		}
	}

	/**
	 * Deactiavte User Account
	 */
	function deactivate_account($user_id, $uuid)
	{
		$cond['user_id'] = intval($user_id);
		$cond['uuid'] = $uuid;
		$data['status'] = INACTIVE;
		//if branch user block means corresponding supplier,car,driver everything will be block
		
		//active branch user
		if($this->custom_db->update_record('user',$data,$cond)){
			//update supplier,driver
			$sup_cond['branch_id'] = $user_id;
			 if($this->custom_db->update_record('user',$data,$sup_cond)){
			 	echo "1";
			 }else{
			 	echo "0";
			 }
		}else{
			echo "0";
		}
		exit;
		/*redirect(base_url().'user/user_management?filter=user_type&q='.$info['data']['user_type']);*/
	}
	public function car_country_list($id=0){
		$post_params = $this->input->post();
		// debug($id);die;
		if(valid_array($post_params)){
			$insert_arr = array();
			$insert_arr['country_id'] = $post_params['country_id'];
			$insert_arr['country_code'] = $post_params['iso_country_code'];
			$insert_arr['city_name'] = $post_params['city'];
			$insert_arr['location'] = $post_params['full_city'];
			$insert_arr['lat'] = $post_params['lat'];
			$insert_arr['lng'] = $post_params['lng'];
			$insert_arr['bounds'] = $post_params['bounds'];
			// debug($insert_arr);
			if($id){
				// echo "in";die;
				// debug($insert_arr);
				// debug($id);
				// die;
				if($r=$this->custom_db->update_record('car_active_country_city_master',$insert_arr,array('origin'=>$id))){
					//debug($r);die;
					redirect('branch_users/car_country_list');
				}
			}else{
				$city=$insert_arr['city_name'];
				$cnty_cd=$insert_arr['country_code'];
				$condition = " WHERE city_name='$city' AND country_code='$cnty_cd'";
				// echo "in2";
				$query = "SELECT * from car_active_country_city_master".$condition;
			//	$res="select * from car_active_country_city_master where city_name='$post_params["city"]' AND country_id='$post_params["country_id"]';


				$query = $this->db->query($query);
$rs=$query->num_rows();
// echo $this->db->last_query();die;
if($rs>0)
{
	echo '<script>alert("duplicate city not allowed!!!!")</script>';
	// redirect('branch_users/car_country_list');
// echo "yes";die;
}
else
{
// echo "no";die;

//return $query->result_array();
				$r=$this->custom_db->insert_record('car_active_country_city_master',$insert_arr);
				if($r['status']){
					// echo $r['status']."in3";die;
					redirect('branch_users/car_country_list');
				}
				else
				{
					// echo $r['status']."out";die;
					echo '<script>alert("duplicate city not allowed!!!!")</script>';
					//redirect('branch_users/car_country_list');
				}
			}

			}
		}
		if($id>0){
			// echo "out1";die;
			$country_list = $this->car_model->get_active_country_city_list($id);
			//debug(			$country_list);die;
			$data = $country_list['data'][0];
			//debug($data);die;
		}


		$get_active_country_city_list = $this->car_model->get_active_country_city_list();
		//debug($get_active_country_city_list);die;
		$total_row_count = 0;
		if($get_active_country_city_list['status']==1){

			$data['table_data'] = $get_active_country_city_list['data'];
		 	$total_row_count = count($data['table_data']);
		 	// echo "in11";die;
		 	//debug($data);die;

		}else{
			// echo "in22";die;
			$data['table_data'] = array();
			//debug($data);die;
		}

		$data['ID']=$id;	
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/branch_users/car_country_list/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$config['total_rows'] = $total_row_count;
		$config['per_page'] = RECORDS_RANGE_1;
		// $this->pagination->initialize($config);

	
		$country_list = $this->custom_db->single_table_records('api_country_master','origin,iso_country_code,country,country_name');		
		$data['country_list'] = $country_list['data'];
		// debug($data);die;
		$this->template->view ( 'branch/car_country_list', $data );
		
		
	}
	public function unique_city_name(){
		// debug($_POST);die;
		$country_id = $_POST['country_id'];
		$city_name = $_POST['city_name'];
		$id = $_POST['id'];
		if($id ){
			//echo $id;die;
			$query = $this->custom_db->single_table_records('car_active_country_city_master','origin',array('country_id'=>$country_id,'city_name'=>$city_name),0,1);
			if($query['status']==1){
				echo "1";
			}else{
				echo "0";
			}
		}else{
			echo "0";
		}
		
	}


	////to check duplications
	public function check_duplicate(){

		$email = $this->input->post('email');
		$result = $this->car_model->check_duplicate($email,'user');
		echo $result;
	}
	
}