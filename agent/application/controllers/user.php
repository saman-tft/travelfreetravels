<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @package    Provab
 * @subpackage General
 * @author     Balu A<balu.provab@gmail.com>
 * @version    V1
 */
// error_reporting(0);
class User extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('module_model');
			$this->load->library('rewards');
		//$this->output->enable_profiler(TRUE);
	}

	function create_default_domain($domain_key_name='192.168.0.26')
	{
		include_once DOMAIN_CONFIG.'default_domain_configuration.php';
	}

	/**
	 * index page of application will be loaded here
	 */
	function index()
	{
		if (is_logged_in_user()) {
			redirect('menu/index');
		}
	}
	public function comingsoon(){
	    $post_data[]='';
          $this->template->view('general/coming_soon', $post_data);
    }
public function coming_soon_pj(){
	    $post_data[]='';
          $this->template->view('general/coming_soon_pj.php', $post_data);
    }
    public function coming_soon_pt(){
	    $post_data[]='';
          $this->template->view('general/coming_soon_pt.php', $post_data);
    }
    public function coming_soon_pc(){
	    $post_data[]='';
          $this->template->view('general/coming_soon_pc.php', $post_data);
    }
     public function coming_soon_ac(){
	    $post_data[]='';
          $this->template->view('general/coming_soon_ac.php', $post_data);
    }
	/**
	 * Generate my account view to user
	 */
	function account()
	{
		$page_data['form_data'] = $this->input->post();
		$get_data = $this->input->get();
		// debug($get_data);exit;
		/**
		 * USE USER PAGE FOR MY ACCOUNT
		 * @var unknown_type
		 */
		$this->user_page = new Provab_Page_Loader('user_management');
		if (isset($get_data['uid']) == true) {
			$get_data['uid'] = intval($get_data['uid']);
			if (valid_array($page_data['form_data']) == false) {
				/*** EDIT DATA ***/
				$cond = array(array('U.user_id', '=', intval($get_data['uid'])));
				$edit_data = $this->user_model->get_user_details($cond);
				// debug($edit_data);exit;
				if (valid_array($edit_data) == true) {
					$page_data['form_data'] = $edit_data[0];
					$page_data['form_data']['uuid'] = provab_decrypt($page_data['form_data']['uuid']);
					$page_data['form_data']['email'] = provab_decrypt($page_data['form_data']['email']);

				
				} else {
					redirect('security/log_event');
				}
			} elseif (valid_array($page_data['form_data']) == true && (check_default_edit_privilege($get_data['uid']) || super_privilege())) {
				/** AUTOMATE VALIDATOR **/
				$page_data['form_data']['language_preference'] = 'english';
				//$this->user_page->set_auto_validator();
				$this->form_validation->set_rules('title', 'Title', 'trim|required|min_length[1]|max_length[4]');
				$this->form_validation->set_rules('first_name', 'First Name', 'trim|required|min_length[2]|max_length[45]|xss_clean');
				$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|min_length[1]|max_length[45]|xss_clean');
				$this->form_validation->set_rules('country_code', 'Country Code', 'trim|required|min_length[1]|max_length[6]');
				$this->form_validation->set_rules('phone', 'Mobile Number', 'trim|required|min_length[7]|max_length[10]|numeric');
				$this->form_validation->set_rules('address', 'Address', 'trim|required|min_length[5]|max_length[500]|xss_clean');
				$this->form_validation->set_rules('date_of_birth', 'Date of Birth', 'trim|min_length[5]|xss_clean');
				$this->form_validation->set_rules('user_id', 'UserId', 'trim|min_length[1]|max_length[10]|numeric');
				if ($this->form_validation->run()) {
					if (intval($get_data['uid']) === intval($page_data['form_data']['user_id']) && intval($page_data['form_data']['user_id']) > 0) {
						
						//Application Logger
						$notification_users = $this->user_model->get_admin_user_id();
						$remarks = $page_data['form_data']['first_name'].' Updated Profile Details';
						$action_query_string = array();
						$action_query_string['user_id'] = $this->entity_user_id;
						$action_query_string['uuid'] = $this->entity_uuid;
						$action_query_string['user_type'] = B2B_USER;
						$this->application_logger->profile_update($page_data['form_data']['first_name'], $remarks, $action_query_string, array(), $this->entity_user_id, $notification_users);
						
						//Update Data -- LETS UNSET POSTED DATA
						unset($page_data['form_data']['FID']);
						unset($page_data['form_data']['email']);
						unset($page_data['form_data']['uuid']);
						$user_id = intval($page_data['form_data']['user_id']);
						unset($page_data['form_data']['user_id']);
						$page_data['form_data']['date_of_birth'] = date('Y-m-d', strtotime($page_data['form_data']['date_of_birth']));
						$this->custom_db->update_record('user', $page_data['form_data'], array('user_id' => $user_id));
						//set_update_message();
						// $this->session->set_flashdata(array('message' => 'AL004', 'type' => SUCCESS_MESSAGE));
						$this->session->set_flashdata('message', AL004); 
						//FILE UPLOAD
						if (valid_array($_FILES) == true and $_FILES['image']['error'] == 0 and $_FILES['image']['size'] > 0) {
							if( function_exists( "check_mime_image_type" ) ) {
							    if ( !check_mime_image_type( $_FILES['image']['tmp_name'] ) ) {
							    	echo "Please select the image files only (gif|jpg|png|jpeg)"; exit;
							    }
							}

							$config['upload_path'] = $this->template->domain_image_upload_path();
							$config['allowed_types'] = 'gif|jpg|png|jpeg';
							$config['file_name'] = time();
							$config['max_size'] = MAX_DOMAIN_LOGO_SIZE;
							$config['max_width']  = MAX_DOMAIN_LOGO_WIDTH;
							$config['max_height']  = MAX_DOMAIN_LOGO_HEIGHT;
							$config['remove_spaces']  = false;
							//UPDATE
							$temp_record = $this->custom_db->single_table_records('user', 'image', array('user_id' => $user_id));
							$icon = $temp_record['data'][0]['image'];
							//DELETE OLD FILES
							if (empty($icon) == false) {
								$temp_profile_image = $this->template->domain_image_full_path($icon);//GETTING FILE PATH
								if (file_exists($temp_profile_image)) {
									unlink($temp_profile_image);
								}
							}
							//UPLOAD IMAGE
							$this->load->library('upload', $config);
							if ( ! $this->upload->do_upload('image')) {
								$message = $this->upload->display_errors();
								if($message == '<p>The filetype you are attempting to upload is not allowed.</p>'){
									// $this->session->set_flashdata(array('message' => 'AL005', 'type' => FAILURE_MESSAGE));
									$this->session->set_flashdata('message', AL005); 
								}
							} else {
								$image_data =  $this->upload->data();
							}
							//debug($image_data);exit;
							$this->custom_db->update_record('user', array('image' => @$image_data['file_name']), array('user_id' => $user_id));
						}
						refresh();
					} else {
						redirect('security/log_event');
					}
				}
			}
			$page_data['country_code_list'] = $this->db_cache_api->get_country_code_list();
			$country_code = $this->db_cache_api->get_country_code_list_profile();
			$mobile_code = $this->db_cache_api->get_mobile_code($page_data['form_data']['country_code']);
			$page_data['mobile_code'] = $mobile_code;
			// echo $mobile_code;exit;
			$phone_code_array = array();
			foreach($country_code['data'] as $c_key => $c_value){
				$phone_code_array[$c_value['origin']] = $c_value['name'].' '.$c_value['country_code'];
				
			}
			// debug($phone_code_array);exit;
			$page_data['phone_code_array'] = $phone_code_array;
			$this->template->view('user/account', $page_data);
		} else {
			redirect('security/log_event');
		}
	}
	/**
	 * Agent Registration
	 */
	function agentRegister()
	{

		error_reporting(0);
		$page_data['form_data'] = $this->input->post();

		if(valid_array($page_data['form_data']) == true){
			
			$page_data['form_data']['language_preference'] = 'english';
			$this->form_validation->set_rules('company_name', 'Company', 'trim|required|min_length[2]|max_length[45]|xss_clean');
			$this->form_validation->set_rules('title', 'Title', 'trim|required|min_length[1]|max_length[4]');
			$this->form_validation->set_rules('first_name', 'FirstName', 'trim|required|min_length[2]|max_length[45]|xss_clean');
			$this->form_validation->set_rules('last_name', 'LastName', 'trim|required|min_length[1]|max_length[45]|xss_clean');
			$this->form_validation->set_rules('email', 'Email', 'valid_email|required|max_length[80]|callback_useremail_check');
			$this->form_validation->set_rules('user_name', 'Username', 'valid_email|required|max_length[80]|callback_username_check');
			$this->form_validation->set_rules('password', 'Password', 'matches[password_c]|required');
			$this->form_validation->set_rules('password_c', 'Confirm');
			$this->form_validation->set_rules('country_code', 'CountryCode', 'trim|required|min_length[1]|max_length[6]');
			$this->form_validation->set_rules('phone', 'Mobile', 'trim|required|min_length[7]|max_length[10]|numeric');
			$this->form_validation->set_rules('office_phone', 'Phone', 'trim|required|min_length[7]|max_length[15]|numeric');
			$this->form_validation->set_rules('address', 'Address', 'trim|required|max_length[500]|xss_clean');
			$this->form_validation->set_rules('city', 'City Name', 'trim|required');
			$this->form_validation->set_rules('country', 'Country Name', 'trim|required');
			$this->form_validation->set_rules('term_condition', 'Term And condition', 'trim|required');
		//	$this->form_validation->set_rules('pin_code', 'Pincode', 'trim|required');
			
			if ($this->form_validation->run()) {
					//unset($page_data['form_data']['password_c']);

				if (valid_array ( $_FILES ) == true and $_FILES ['attachment'] ['size'] > 0) {
					$img_name = 'Passport-'.time();
					// if( function_exists( "check_mime_image_type" ) ) {
					//     if (!check_mime_image_type( $_FILES['panimage']['tmp_name'])  && !check_mime_image_type( $_FILES['gstimage']['tmp_name']) ) {
					//     	echo "Please select the image files only (gif|jpg|png|jpeg)"; exit;
					//     }
					// }
					// debug($_FILES);exit;
					$config ['upload_path'] = $this->template->domain_image_upload_path ();
					$temp_panfile_name = $_FILES ['attachment'] ['name'];
					$config['allowed_types'] = 'jpg|pdf|jpeg';
					$config ['file_name'] =$img_name;
					$config ['remove_spaces'] = false;

					// UPLOAD IMAGE
					$this->load->library ( 'upload', $config );
					$this->upload->initialize ( $config );
					if (! $this->upload->do_upload ( 'attachment' )) {
						echo $this->upload->display_errors ();
					} else {
						$image_data = $this->upload->data ();
					}
				}

				$page_data_arr['form_data']['attachment'] = (empty($image_data ['file_name']) == false ? $image_data ['file_name'] : '');
  $total_referral = $this->custom_db->single_table_records('affiliates', '*', array('ref_code' =>$page_data['form_data']['refercode']));
					// $page_data_arr['form_data']['uuid'] =provab_encrypt(PROJECT_PREFIX.time());
					$get_rand = mt_rand(10000000, 99999999);
					$page_data_arr['form_data']['uuid'] =provab_encrypt(PROJECT_AGENT_PREFIX.$get_rand);
					$page_data_arr['form_data']['password'] =  provab_encrypt(md5(trim($page_data['form_data']['password'])));
					$page_data_arr['form_data']['title'] = $page_data['form_data']['title'];
					$page_data_arr['form_data']['user_type'] = B2B_USER;
					$page_data_arr['form_data']['created_datetime'] = date("Y-m-d h:i:sa");
					$page_data_arr['form_data']['domain_list_fk'] = intval(get_domain_auth_id());
					$page_data_arr['form_data']['status'] = FAILURE_STATUS;
					$page_data_arr['form_data']['first_name'] = $page_data['form_data']['first_name'];
					$page_data_arr['form_data']['last_name'] = $page_data['form_data']['last_name'];
					$page_data_arr['form_data']['country_code'] =$page_data['form_data']['country_code'];

					$page_data_arr['form_data']['phone'] =$page_data['form_data']['phone'];
					$page_data_arr['form_data']['email'] =provab_encrypt(trim($page_data['form_data']['email']));
					$page_data_arr['form_data']['agency_name'] =$page_data['form_data']['company_name'];
					$page_data_arr['form_data']['pan_number'] = @$page_data['form_data']['pan_number'];
					$page_data_arr['form_data']['pan_holdername'] = @$page_data['form_data']['pan_holdername'];
					$page_data_arr['form_data']['address'] =$page_data['form_data']['address'];

					$page_data_arr['form_data']['country_name'] =$page_data['form_data']['country'];
					$page_data_arr['form_data']['city'] =$page_data['form_data']['city'];
					$page_data_arr['form_data']['pin_code'] =$page_data['form_data']['pin_code'];
					$page_data_arr['form_data']['office_phone'] =$page_data['form_data']['office_phone'];

					$page_data_arr['form_data']['user_name'] =provab_encrypt($page_data['form_data']['user_name']);
					$page_data_arr['form_data']['creation_source'] = 'portal';
					$page_data_arr['form_data']['terms_conditions'] = 1;
					$page_data_arr['form_data']['created_by_id'] = 0;
					$page_data_arr['form_data']['pwd_token'] = 1;
					$insert_id = $this->custom_db->insert_record('user', $page_data_arr['form_data']);
					$insert_id = $insert_id['insert_id'];
					//B2B User Details					
					//get the admin currency
					$b2b_user_details = array();
					$get_admin_currency = $this->custom_db->single_table_records('domain_list','*',array('domain_key'=>CURRENT_DOMAIN_KEY));
					$b2b_user_details['currency_converter_fk'] = $get_admin_currency['data'][0]['currency_converter_fk'];
					
					
					$image = '';
					$b2b_user_details['user_oid'] = $insert_id;
					$original=$insert_id;
					$b2b_user_details['logo'] = $image;
					$b2b_user_details['balance'] = 0;
					$b2b_user_details['created_datetime'] = $page_data_arr['form_data']['created_datetime'];
					$this->custom_db->insert_record('b2b_user_details', $b2b_user_details);
					
					$page_data_arr['form_data']['password'] = $page_data['form_data']['password'];//Dont remove
					$data['agent'] = $page_data_arr['form_data'];
					$data['admin_address'] = $get_admin_currency['data'][0]['address'];
					if($total_referral['status']!=0)
					    {
					         $$get_bonus = $this->custom_db->single_table_records('reward_settings', '*', array('id' =>'1'));
					         $get_refuser = $this->custom_db->single_table_records('user', '*', array('user_id' =>$get_bonus['data'][0]['aff_email']));
					         $data_rewards = $this->rewards->user_reward_details($original);
                              $pending_rewards = $data_rewards['pending_reward']+$get_bonus['data'][0]['rewardbonus'];
                              	$data_upadte_rewards = array(
			                   'pending_reward'=>round($pending_rewards),
			                    'used_reward'=>round($used_rewards),

		                         );
		                         if(isset($op_data['refercode']))
		                         {
	                         $this->rewards->update_reward_record($get_refuser['data'][0]['user_id'],$data_upadte_rewards);
	                         	$rdata=array(
                            	    "ref_code"=>$op_data['refercode'],
                            	    "comm_date"=>date("Y-m-d"),
                            	    "comm_amount"=>$get_bonus['data'][0]['rewardbonus'],
                            	     "ref_email"=>provab_decrypt($get_refuser['data'][0]['email']),
                            	      "user_email"=>$page_data['form_data']['email'],
                            	       "status"=>'reward credited',
        	                    );
        	                    $this->custom_db->insert_record('commissions', $rdata);
		                         }
					    }
					$mail_template = $this->template->isolated_view('agent/agent_template', $data);
					// debug($mail_template);exit;
					$email = provab_decrypt($page_data_arr['form_data']['email']);
					$this->load->library('provab_mailer');
					//$this->provab_mailer->send_mail('sagar@provab.com', 'New-Agent Registered', $mail_template);
					$subject = 'Registration Acknowledgment '.$_SERVER['HTTP_HOST'];
					$mail_status = $this->provab_mailer->send_mail($email, $subject, $mail_template);
					// $data['message'] = $banner;
					
					//Application Logger
					$remarks = $email.' Has Registered From Agent Portal';
					$notification_users = $this->user_model->get_admin_user_id();
					$action_query_string = array();
					$action_query_string['user_id'] = $insert_id;
					$action_query_string['uuid'] = provab_decrypt($page_data_arr['form_data']['uuid']);
					$action_query_string['user_type'] = B2B_USER;
					
					$this->application_logger->registration($email, $remarks, $insert_id, $action_query_string, array(), $notification_users);
					
					$this->session->set_flashdata('message', 'Congratulations!! You are successfully registered as an Agent. Admin will activate your account soon.');
					redirect('user/agentRegister/show');
			}
		}
		$data['message'] = @$banner;
		$temp_record = $this->custom_db->single_table_records('domain_list', '*');
		$data['active_data'] =$temp_record['data'][0];

		$temp_record = $this->custom_db->single_table_records('api_country_list', '*');
		$data['phone_code'] =$temp_record['data'];
		$city_record = $this->custom_db->single_table_records('api_city_list', 'destination',array('country'=>$data['active_data']['api_country_list_fk']));
		$data['city_list'] =$city_record['data'][0];
		$data['country_code_list'] = $this->db_cache_api->get_country_code_list();
		$country_code = $this->db_cache_api->get_country_code_list_profile();
		// debug($country_code);exit;
		$phone_code_array = array();
		foreach($country_code['data'] as $c_key => $c_value){
			$phone_code_array[$c_value['origin']] = $c_value['name'].' '.$c_value['country_code'];
			
		}
		// debug($phone_code_array);exit;
		$data['phone_code_array'] = $phone_code_array;
		$data['country_list'] = $this->db_cache_api->get_country_list();
		$this->template->view('agent/agent_register', $data);
	}
	public function testuser(){
		$city_record = $this->custom_db->single_table_records('user', '*',array('status'=>-1));
		debug($city_record);
		debug(provab_decrypt("WjhYMXZHaGxZR0tPeUpXUmhzbG5ZSEZqUGZCdGMxNlNJUjQwZy9hd2xGYz0="));
	}
	public function testdelete(){
		$this->db->where('user_id',1480);
		$this->db->delete('user');
	}
	public function testcode()
	{
		$get_admin_currency = $this->custom_db->single_table_records('domain_list','*',array('domain_key'=>CURRENT_DOMAIN_KEY));
		$b2b_user_details['currency_converter_fk'] = $get_admin_currency['data'][0]['currency_converter_fk'];
		debug($b2b_user_details['currency_converter_fk']);
	}
	public function username_check($name)
	{
		$condition['user_name'] = provab_encrypt($name);
		$condition['user_type'] = B2B_USER;
		$condition['domain_list_fk'] = intval(get_domain_auth_id());
		$data = $this->custom_db->single_table_records('user', 'user_id', $condition);
		if ($data['status'] == SUCCESS_STATUS and valid_array($data['data']) == true) {
			$this->form_validation->set_message(__FUNCTION__, $name.' Already this email has been registered!!!');
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	public function useremail_check($name)
	{
		$condition['email'] = provab_encrypt($name);
		$condition['user_type'] = B2B_USER;
		$condition['domain_list_fk'] = intval(get_domain_auth_id());
		$data = $this->custom_db->single_table_records('user', 'user_id', $condition);
		if ($data['status'] == SUCCESS_STATUS and valid_array($data['data']) == true) {
			$this->form_validation->set_message(__FUNCTION__, $name.' Already this email has been registered!!!');
			return FALSE;
		} else {
			return TRUE;
		}
	}

	/**
	 * Logout function for logout from account and unset all the session variables
	 */
	function initilize_logout(){
		if (is_logged_in_user()) {
			$this->general_model->update_login_manager($this->session->userdata(LOGIN_POINTER));
			$this->session->unset_userdata(array(AUTH_USER_POINTER => '',LOGIN_POINTER => '') );
			// added by nithin for unseting the email username
			$this->session->unset_userdata('mail_user');
			redirect('general/index');
		}
	}
	/**
	 * oops page of application will be loaded here
	 */
	public function ooops()
	{
		$this->template->view('utilities/404.php');
	}

	/**
	 * Function to Change the Password of a User
	 */
	public function change_password()
	{
		$data=array();
		$get_data = $this->input->get();
		if(isset($get_data['uid'])) {
			$user_id = $get_data['uid'];
		} else {
			redirect("general/initilize_logout");
		}
		$page_data['form_data'] = $this->input->post();	
		if(valid_array($page_data['form_data'])==TRUE) {
			// $this->current_page->set_auto_validator();	
			$this->load->library('form_validation');
			$this->form_validation->set_rules('current_password', 'Current Password', 'required|min_length[5]|max_length[45]|callback_password_check');
			$this->form_validation->set_rules('new_password', 'New Password', 'matches[confirm_password]|min_length[5]|max_length[45]|required|callback_valid_password');
			$this->form_validation->set_rules('confirm_password', 'Confirm', 'callback_check_new_password');					
			if ($this->form_validation->run()) {				
				$table_name="user";
				/** Checking New Password and Old Password Are Same OR Not **/
				$condition['password'] = provab_encrypt(md5(trim($this->input->post('new_password'))));
				$condition['user_id'] = $user_id;				
				$check_pwd = $this->custom_db->single_table_records($table_name,'password',$condition);
				if($check_pwd['status'] == false) {
					$condition['password'] = provab_encrypt(md5(trim($this->input->post('current_password'))));
					$condition['user_id'] = $user_id;
					$data['password'] = provab_encrypt(md5(trim($this->input->post('new_password'))));
					$update_res=$this->custom_db->update_record($table_name, $data, $condition);				
					if($update_res)	{
						// $this->session->set_flashdata(array('message' => 'Password Changed Successfully', 'type' => SUCCESS_MESSAGE, 'override_app_msg' => true));
						$this->session->set_flashdata('message', 'Password Changed Successfully');
						refresh();
					} else {
						// $this->session->set_flashdata(array('message' => 'Invalid Current Password', 'type' => ERROR_MESSAGE, 'override_app_msg' => true));
						$this->session->set_flashdata('message', 'Invalid Current Password');
						refresh();
						/*$data['msg'] = 'UL0011';
						 $data['type'] = ERROR_MESSAGE;*/
					}
				} else {
					// $this->session->set_flashdata(array('message' => 'Current Password and New Password Are Same', 'type'=>WARNING_MESSAGE, 'override_app_msg' => true));
					$this->session->set_flashdata('message', 'Current Password and New Password Are Same');
					refresh();
					//redirect('general/change_password?uid='.urlencode($get_data['uid']));
				}
			}
		}
		$this->template->view('user/change_password', $data);
	}

	/**
	 * Manage Domain Logo
	 * Balu A (25-05-2015) - 26-05-2015
	 */
	function domain_logo()
	{
		$post_data = $this->input->post();
		if(valid_array($post_data) == true && isset($post_data['origin']) == true) {
			$GLOBALS['CI']->template->domain_images();
			if(intval($post_data['origin']) == get_domain_auth_id() && get_domain_auth_id() > 0) {
				$domain_origin = get_domain_auth_id();
				//FILE UPLOAD
				if (valid_array($_FILES) == true and $_FILES['domain_logo']['error'] == 0 and $_FILES['domain_logo']['size'] > 0) {
					
					// if( function_exists( "check_mime_image_type" ) ) {
					//     if ( !check_mime_image_type( $_FILES['image']['tmp_name'] ) ) {
					//     	echo "Please select the image files only (gif|jpg|png|jpeg)"; exit;
					//     }
					// }
					$config['upload_path'] = $this->template->domain_image_upload_path();
					$temp_file_name = $_FILES['domain_logo']['name'];
					$config['allowed_types'] = 'gif|jpg|png|jpeg';
					$config['file_name'] = get_domain_key().$temp_file_name;
					$config['max_size'] ='' ;
					$config['max_width']  = '';
					$config['max_height']  = '';
					$config['remove_spaces']  = false;
					//UPDATE
					$temp_record = $this->custom_db->single_table_records('b2b_user_details', 'logo', array('user_oid' => intval($this->entity_user_id)));
					$domain_logo = $temp_record['data'][0]['logo'];
					//DELETE OLD FILES
					if (empty($domain_logo) == false) {
						$temp_domain_logo = $this->template->domain_image_full_path($domain_logo);//GETTING FILE PATH
						if (file_exists($temp_domain_logo)) {
							unlink($temp_domain_logo);
						}
					}
					//UPLOAD IMAGE
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if ( ! $this->upload->do_upload('domain_logo')) {
						echo $this->upload->display_errors();
					} else {
						$image_data =  $this->upload->data();
					}
					$this->custom_db->update_record('b2b_user_details', array('logo' => @$image_data['file_name']), array('user_oid' => intval($this->entity_user_id)));
				}
				refresh();
			}

		}
		$temp_details = $this->custom_db->single_table_records('b2b_user_details', 'logo', array('user_oid' => intval($this->entity_user_id)));
		if($temp_details['status'] == true) {
			$page_data['domain_logo'] = $temp_details['data'][0]['logo'];
		} else {
			$page_data['domain_logo'] = '';
		}
		$this->template->view('user/domain_logo', $page_data);
	}
	
	function get_city_data()
	{
		echo 'hi'; die;
		$country_id = $this->input->post('country_id');
		$city_list = $this->custom_db->single_table_records('api_city_list', '*', array('country' => $country_id),0,100000000,array('destination'=>'asc'));
		$options ='';
		$city_list = $city_list['data'];
		foreach ($city_list as $city) {
			$options .="<option value=".$city['origin'].">".$city['destination']."</option>";
		}
		print_r($options);
	}
	/**
	 * Validate the password
	 *
	 * @param string $password
	 *
	 * @return bool
	 */
	public function valid_password($password)
	{
		$password = trim($password);
		$regex_lowercase = '/[a-z]/';
		$regex_uppercase = '/[A-Z]/';
		$regex_number = '/[0-9]/';
		$regex_special = '/[!@#$%^&*()\-_=+{};:,<.>§~]/';
		if (empty($password))
		{
			$this->form_validation->set_message('valid_password', 'The Password field is required.');
			return FALSE;
		}
		if (preg_match_all($regex_lowercase, $password) < 1)
		{
			$this->form_validation->set_message('valid_password', 'The Password field must be at least one lowercase letter.');
			return FALSE;
		}
		if (preg_match_all($regex_uppercase, $password) < 1)
		{
			$this->form_validation->set_message('valid_password', 'The Password field must be at least one uppercase letter.');
			return FALSE;
		}
		if (preg_match_all($regex_number, $password) < 1)
		{
			$this->form_validation->set_message('valid_password', 'The Password field must have at least one number.');
			return FALSE;
		}
		if (preg_match_all($regex_special, $password) < 1)
		{
			$this->form_validation->set_message('valid_password', 'The Password field must have at least one special character.' . ' ' . htmlentities('!@#$%^&*()\-_=+{};:,<.>§~'));
			return FALSE;
		}
		if (strlen($password) < 5)
		{
			$this->form_validation->set_message('valid_password', 'The Password field must be at least 5 characters in length.');
			return FALSE;
		}
		if (strlen($password) > 32)
		{
			$this->form_validation->set_message('valid_password', 'The Password field cannot exceed 32 characters in length.');
			return FALSE;
		}
		return TRUE;
	}
}
