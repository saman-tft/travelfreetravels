<?php
//error_reporting(E_ALL);
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 *
 * @package Provab - Provab Application
 * @subpackage rewards system
 * @author Nikhildas s
 * @version V2
 */
class Reward extends CI_Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->library('session');
		$this->load->model('rewards_model');
	}
	
	
	function reward_report($offset=0){

	
ini_set('memory_limit', '-1');
		$get_data =$this->input->get();
		$condition[] = array('U.user_type', '=', B2C_USER);
		if(valid_array($get_data) == true){
			$email = trim(@$get_data['email']);
			if(empty($email) == false) {
		
				$condition[] = array('email', '=', $this->db->escape($get_data['email']));
		
			}
			if(empty($get_data['app_reference']) == false) {
			
				$condition[] = array('book_id', '=', $this->db->escape($get_data['app_reference']));
			
			}
		}
		$total_records = $this->user_model->get_b2c_booked_detail($condition, true);
		$table_data = $this->user_model->get_b2c_booked_detail($condition, false, $offset, RECORDS_RANGE_2);
		$page_data['table_data'] = $table_data;
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/reward/reward_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		$page_data['total_records'] = $config['total_rows'];
		$page_data['search_params'] = $get_data;
	
		$user_type = B2C_USER;
		$get_b2c_user = $this->rewards_model->get_b2c_user ($user_type);
		//$page_data ['data'] = $get_b2c_user;
		//debug($page_data); die;
	
		$this->template->view ('reward/reward_report', $page_data );
		
	}
	
	function referral_report()
	{
	    $pagedata=array();
	    $temp_record = $this->custom_db->single_table_records('reward_settings', '*', array('id' =>'1'));
	    $con['id']=1;
	    $query = "SELECT * From commissions";
	    		$specific_data_list = $this->db->query($query)->result_array();
	    $page_data['users_data'] =$specific_data_list;
	    $temp_record = $this->custom_db->single_table_records('reward_settings', '*', array('id' =>'1'));
	    $page_data['bonus']=$temp_record['data'][0]['rewardbonus'];
	    $this->template->view('reward/referral_reward',$page_data);
	}
	function referralset()
	{
	    $post['rewardbonus']=$this->input->post('rewardbonus');
	    $pagedata=array();
	    $temp_record = $this->custom_db->single_table_records('reward_settings', '*', array('id' =>'1'));
	    $con['id']=1;
	   $query = "SELECT * From commissions inner join user on user.emaill=commissions.ref_email";
	    $page_data['users_data'] =$specific_data_list;
	    $this->custom_db->update_record ('reward_settings',$post,$con);
	    $page_data['bonus']=$temp_record['data'][0]['rewardbonus'];
	    $this->template->view('reward/referral_reward',$page_data);
	}
function wallet_settings()
{
    $page_data=array();
    	$query = "SELECT * From wallet_setting";
	
		$specific_data_list = $this->db->query($query)->result_array();
	$page_data['users_data'] =$specific_data_list;
    $this->template->view ('reward/wallet_settings', $page_data );
}
function add_wallet_settings()
  { 
    $data =$this->input->post(); 
    $post_data=array(
        "reward-points"=>$data["reward-points"],
        "price"=>$data["price"],
        "created_at"=>date("Y-m-d"),
        "updated_at"=>date("Y-m-d")
        );
    
    $this->db->insert('wallet_setting',$post_data);
  //  echo "sdf";die;
    	$query = "SELECT * From wallet_setting";
	
		$specific_data_list = $this->db->query($query)->result_array();
	$page_data['users_data'] =$specific_data_list;
    $this->template->view ('reward/wallet_settings', $page_data );
}
function delete_wallet($id)
{
    $bool=$this->rewards_model->delete_wallet_setting ($id);
   $page_data=array();
    	$query = "SELECT * From wallet_setting";
	
		$specific_data_list = $this->db->query($query)->result_array();
	$page_data['users_data'] =$specific_data_list;
    $this->template->view ('reward/wallet_settings', $page_data );
    
}
function wallet_transaction()
{
  $page_data=array(); 
  	$query = "SELECT * From wallet_transaction inner join user on user.user_id=wallet_transaction.created_by_id";
	
		$specific_data_list = $this->db->query($query)->result_array();
	$page_data['users_data'] =$specific_data_list;
//	debug($page_data);die;
   $this->template->view ('reward/wallet_transaction', $page_data );   
}

	//for update general reward for each user
	function update_general_reward_for_all_users_based_on_id($reward){
		$this->db->select('*');
		$this->db->where(array('user_type'=>B2C_USER));
		$query = $this->db->get('user');
		$users_data = $query->result_array();
		foreach ($users_data as $key => $value) {
		  $current_pending_rewards = $this->calculate_pending_rewards($value['user_id']);
		  $data = array('pending_reward'=>$current_pending_rewards+$reward);
		  $this->db->where(array('user_id'=>$value['user_id']));
		  $re = $this->db->update('user',$data);
		}
		if($re){
			return TRUE;
		}else{
			return FLASE;
		}	
	}	

    /*
	function :to calculate the pending rewards
     */
	function calculate_pending_rewards($id){

		        $this->db->where(array('user_id'=>$id));
				$this->db->select('*');
				$get_query = $this->db->get('user');
				$current_pending_rewards_tmp = $get_query->result_array();
				$current_pending_rewards = $current_pending_rewards_tmp[0]['pending_reward'];
				return $current_pending_rewards;
	}
	/**
	 * Jaganath
	 * Manages Bank Account Details
	 */
	function add_rewards($offset=0) {
		// error_reporting(E_ALL);
		$post_data = $this->input->post ();
		$get_data =$this->input->get();
		//debug($post_data);exit;

		$condition[] = array('U.user_type', '=', B2C_USER);
		$condition[] = array('U.status ', '=', "1");
		if(valid_array($get_data) == true){
			$email = trim(@$get_data['email']);
			if(empty($email) == false) {
				$condition[] = array('email', '=', $this->db->escape($get_data['email']));
				
			}
			// debug($condition);exit();
		}
		if($post_data ['spefic_reward'] == true){
			$email = trim(@$post_data['email']);
			if(empty($email) == false) {
				$condition[] = array('email', '=', $this->db->escape($post_data['email']));
				
			}
		}
		$total_records = $this->user_model->get_b2cuser_details($condition, true);

		$table_data = $this->user_model->get_b2cuser_details($condition, false, $offset, RECORDS_RANGE_2);
		// debug($table_data);exit();
		$page_data['table_data'] = $table_data;
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/reward/add_rewards/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		$page_data['total_records'] = $config['total_rows'];
		$page_data['search_params'] = $get_data;
		
		
		// debug($post_data);exit();
		$user_type = B2C_USER;
		$get_b2c_user = $this->rewards_model->get_b2c_user ($user_type);
		//$page_data ['data'] = $get_b2c_user; 
		// debug($page_data); die;
		// debug($post_data); exit('110');	
		// debug($post_data);exit();
		if (! empty ( $post_data )) {		
			// debug($post_data);exit('143');	
			if (isset ( $post_data ['general_reward'] )) {
				$reward = $post_data ['general_reward'];
				//debug($reward);exit();				
				$user_type = B2C_USER;
				$this->db->where ( 'user_type', $user_type );
				$this->db->where ( 'status', '1' );
				$this->db->set ( 'general_reward', $post_data ['general_reward'] );
				$this->db->set ( 'modified_datetime', date ( 'Y-m-d H:i:s' ) );
				//$this->db->set ( 'pending_reward',$current_pending_rewards+$reward, FALSE );
			    $this->db->update ( 'user' );
			    $this->update_general_reward_for_all_users_based_on_id($reward);
			   
			} else{
				
				if (isset ( $post_data ['spefic_reward'] )) {
				$reward = $post_data ['spefic_reward'];
				$user_id = trim ( $post_data ['user_id'] );	
				$current_pending_rewards = $this->calculate_pending_rewards($user_id);
                $this->db->where ( 'user_id', $user_id );				
				$this->db->set ( 'spefic_reward', $post_data ['spefic_reward'] );
				$this->db->set ( 'modified_datetime', date ( 'Y-m-d H:i:s' ) );
				$this->db->set ( 'pending_reward',$current_pending_rewards+$reward, FALSE );
				$this->db->update ( 'user' );
			}
			}
			set_update_message();
			redirect('reward/add_rewards');
			// debug($page_data);exit();
			// $this->template->view ( 'reward/add_rewards', $page_data );
		}else{
			 //debug($page_data);exit();
			$this->template->view ( 'reward/add_rewards', $page_data );
		}
		
		
		
	}
	function update_wallet_settings()
	{
	    $id=$this->input->post("wallet-id");
	    $data=$this->input->post();
	    $post_data=array(
        "reward-points"=>$data["reward-points"],
        "price"=>$data["price"],
        "updated_at"=>date("Y-m-d")
        );
	    $this->rewards_model->update_wallet_setting($id,$post_data);
	    	$query = "SELECT * From wallet_setting";
	
		$specific_data_list = $this->db->query($query)->result_array();
	$page_data['users_data'] =$specific_data_list;
    $this->template->view ('reward/wallet_settings', $page_data );
	}
	function reward_conversion() {
		$post_data = $this->input->post ();
		$data ['data'] = $this->rewards_model->get_reward_conversion ();
		//debug($data ['data']);exit();
		if (! empty ( $post_data )) {
			// debug($post_data); die;
			$origin = $post_data ['origin'];
			$post_data ['created'] = date ( 'Y-m-d H:i:s' );
			// UPDATE
			if (intval ( $origin ) > 0) { // Specific Agent Commission
				$update_condition ['origin'] = $origin;
			} else { // Default Commission
				$update_condition ['origin'] = 1;
			}
			if ($origin > 0) {
				$this->custom_db->update_record ( 'rewards', $post_data, $update_condition );

			}
			set_update_message();
			redirect('/reward/reward_conversion');	

		}else{
         $this->template->view ( 'reward/reward_conversion', $data );
		}
	}
	public function reward_range($module=META_ACCOMODATION_COURSE){
	    
	    
	    
		
		// debug($module);exit();
		$module_wise_reward =  array(
			   		'flight'=>META_AIRLINE_COURSE,
			   		'hotel'=>META_ACCOMODATION_COURSE,
			   	//	'car'=>META_CAR_COURSE,
			   		'activity'=>META_SIGHTSEEING_COURSE,
			   		'transfers'=>META_TRANSFERV1_COURSE,
			   		'holidays'=>META_PACKAGE_COURSE,
			   );
		foreach ($module_wise_reward as $key => $value) {
		$page_data['rewards_range'][$key] = $this->rewards_model->get_reward_range($value);
		// $page_data['rewards_range'][$key]['module'] = $value;
		}
		//debug($page_data['rewards_range']);exit();
		$page_data['current_module'] = $module;
		
		$this->template->view('reward/reward_range',$page_data);
	}
	public function reward_range_submit(){
		$post_data = $this->input->post();
		$re = $this->rewards_model->reward_range($post_data);
		if($re)
		{
		// $this->session->set_flashdata('message', 'Reward range added successfully');
		 set_update_message();
		 redirect("/reward/reward_range/".$post_data['module']);	
		}else{
		redirect("/reward/reward_range/".$post_data['module']);		
		}
	
	}
	public function reward_manage(){
		$post_data = $this->input->post();
		$re = $this->rewards_model->delete_reward_range($post_data['id']);
		if($re){
			echo TRUE;
		}else{
			echo FALSE;
		}
	}
	
}
