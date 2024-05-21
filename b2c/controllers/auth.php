<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// added header here for login with google from login page
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, OPTIONS");

// ------------------------------------------------------------------------
/**
 * Controller for all ajax activities
 *
 * @package    Provab
 * @subpackage ajax loaders
 * @author     Balu A J<balu.provab@gmail.com>
 * @version    V1
 */
// ------------------------------------------------------------------------

class Auth extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->library('provab_sms');
		$this->load->library('provab_mailer');
		$this->load->library('rewards');
		$this->load->library('social_network/facebook');
	}

	/**
	 * index page of application will be loaded here
	 */
	function index()
	{
	}

	function register_on_light_box()
	{

		if (is_logged_in_user() == false) {
			$op_data = $this->input->post();
			$status = false;
			$data = '';
			if (valid_array($op_data) == true) {
				//validate
				$this->load->library('form_validation');
				$this->form_validation->set_rules('email', 'Email', 'valid_email|required|max_length[80]|callback_username_check'); //Username to be unique
				$this->form_validation->set_rules('password', 'Password', 'matches[confirm_password]|min_length[5]|max_length[45]|required|callback_valid_password');
				$this->form_validation->set_rules('confirm_password', 'Confirm');
				$this->form_validation->set_rules('first_name', 'Name', 'xss_clean|required|min_length[2]|max_length[45]');
				if ($this->form_validation->run()) {
					//Create New User

					$creation = $this->user_model->create_user($op_data['email'], $op_data['password'], $op_data['first_name'], $op_data['country_code'], $op_data['phone']);

					if ($creation['status'] == true and $creation['data'][0] == true) {
						//  debug($op_data);die;
						$total_referral = $this->custom_db->single_table_records('affiliates', '*', array('ref_code' => $op_data['refercodes']));

						//send activation mail
						$original = $creation['data'][0]['user_id'];
						if ($total_referral['status'] != 0) {
							$get_bonus = $this->custom_db->single_table_records('reward_settings', '*', array('id' => '1'));
							$get_refuser = $this->custom_db->single_table_records('user', '*', array('user_id' => $total_referral['data'][0]['aff_email']));
							$this->load->library('provab_mailer');
							// $mail_template="
							//     Dear Valued Customer,

							//      we appreciate your customer referral to our business travelfreetravels.com. we really value your confidence in our products, particularly as your referral has enabled us to build new business relationships.

							//      Thank you again for your kind gesture,and looking forward to serve you in future.
							// "; removed this for a new template loaded in vies/referral-email
							$mail_template =
								$this->template->isolated_view('user/referral-email', $creation['data'][0]);
							$mail_status = $this->provab_mailer->send_mail(provab_decrypt($get_refuser['data'][0]['email']), 'Your Referral ' . $op_data['first_name'] . '  has registered Successfully', $mail_template);
							$data_rewards = $this->rewards->user_reward_details($total_referral['data'][0]['aff_email']);
							$pending_rewards = $data_rewards['pending_reward'] + $get_bonus['data'][0]['rewardbonus'];
							$data_upadte_rewards = array(
								'pending_reward' => round($pending_rewards),
								'used_reward' => round($used_rewards),

							);
							$this->rewards->update_reward_record($get_refuser['data'][0]['user_id'], $data_upadte_rewards);
							$rdata = array(
								"ref_code" => $op_data['refercodes'],
								"comm_date" => date("Y-m-d"),
								"comm_amount" => $get_bonus['data'][0]['rewardbonus'],
								"ref_email" => provab_decrypt($get_refuser['data'][0]['email']),
								"user_email" => provab_decrypt($creation['data'][0]['email']),
								"status" => 'reward credited',
							);
							$this->custom_db->insert_record('commissions', $rdata);
						}
						$encoded_data = rand(100, 999) . base64_encode($original);
						$url = base_url() . 'index.php/general/activate_account_status?origin=' . $encoded_data;
						$creation['data'][0]['activation_link'] = $url;

						$creation['data'][0]['email']  = provab_decrypt($creation['data'][0]['email']);

						$mail_template = $this->template->isolated_view('user/user_registration_template', $creation['data'][0]);
						$email = $creation['data'][0]['email'];


						$mail_status = $this->provab_mailer->send_mail($email, 'New-User Account Activation', $mail_template);
						$status = true;
						$data = get_app_message('AL002');
					} else {
						$data = get_app_message('AL003');
					}
				} else {
					$data = validation_errors();
				}
			}
			header('content-type:application/json');
			echo json_encode(array('status' => $status, 'data' => $data));
			exit;
		} else {
			redirect(base_url());
		}
	}

	/**
	 * Balu A
	 */
	function register()
	{
		if (is_logged_in_user() == false) {
			$op_data = $this->input->post();
			//data posted
			if (valid_array($op_data) == true) {
				//validate

				$this->load->library('form_validation');
				$this->form_validation->set_rules('email', 'Email', 'valid_email|required|max_length[80]|callback_username_check'); //Username to be unique
				$this->form_validation->set_rules('password', 'Password', 'matches[confirm_password]|min_length[5]|max_length[45]|required');
				$this->form_validation->set_rules('confirm_password', 'Confirm');
				$this->form_validation->set_rules('first_name', 'Name', 'xss_clean|required|min_length[2]|max_length[45]');
				if ($this->form_validation->run()) {
					//Create New User
					$creation = $this->user_model->create_user($op_data['email'], $op_data['password'], $op_data['first_name'], $op_data['phone']);

					if ($creation['status'] == true and $creation['data'][0] == true) {

						//send activation mail
						$original = $creation['data'][0]['user_id'];
						$encoded_data = rand(100, 999) . base64_encode($original);
						$url = base_url() . 'index.php/general/activate_account_status?origin=' . $encoded_data;
						$creation['data'][0]['activation_link'] = $url;
						$mail_template = $this->template->isolated_view('user/user_registration_template', $creation['data'][0]);

						$email = $creation['data'][0]['email'];
						$this->load->library('provab_mailer');
						$this->provab_mailer->send_mail($email, 'New-User Account Activation', $mail_template);

						$this->session->set_flashdata(array('message' => 'AL002', 'type' => SUCCESS_MESSAGE));
						redirect(base_url() . 'index.php/auth/register');
					} else {
						$this->session->set_flashdata(array('message' => 'AL003', 'type' => ERROR_MESSAGE));
					}
				}
			}
			$this->template->view('user/register', array('form' => $op_data));
		} else {
			redirect(base_url());
		}
	}
	/*
	 * Jaganath
	 * Add guest User details
	 */
	function register_guest_user()
	{
		$post_data = $this->input->post();
		$status = false;
		$data = '';
		if (is_logged_in_user() == false && empty($post_data['username']) == false && empty($post_data['mobile_number']) == false) {
			$user_name = trim($post_data['username']);
			$mobile_number = trim($post_data['mobile_number']);
			$user_exists = $this->username_check($user_name);
			$status = true;
			if ($user_exists == false) { //Check User Exists based on Username
				$data = 'User Exists';
			} else { //If not exists add the guest user details
				$password = 'test';
				$first_name = 'user';
				$creation_source = 'guest';
				$user_type = 0;
				$creation = $this->user_model->create_user($user_name, $password, $first_name, $mobile_number, $creation_source, $user_type);
				$data = 'Added guest User';
			}
		}
		header('content-type:application/json');
		echo json_encode(array('status' => $status, 'data' => $data));
		exit;
	}
	/**
	 * Call back function to check username availability
	 * @param string $name
	 */
	public function username_check($name)
	{
		$condition['email'] = provab_encrypt($name);
		$condition['user_type'] = B2C_USER;
		$condition['domain_list_fk'] = intval(get_domain_auth_id());
		$data = $this->custom_db->single_table_records('user', 'user_id', $condition);
		if ($data['status'] == SUCCESS_STATUS and valid_array($data['data']) == true) {

			$this->form_validation->set_message('username_check', 'Email id already exists');
			return FALSE;
		} else {
			return TRUE;
		}
	}

	/**
	 * Balu A
	 */
	function forgot_password()
	{


		$post_data = $this->input->post();
		extract($post_data);
		//email, phone
		$condition['email'] = provab_encrypt($email);
		//$condition['phone'] = $phone;
		$condition['status'] = ACTIVE;
		$condition['user_type'] = B2C_USER;
		$user_record = $this->custom_db->single_table_records('user', 'email, password, user_id, first_name, last_name', $condition);
		if ($user_record['status'] == true and valid_array($user_record['data']) == true) {

			$user_record['data'][0]['password'] = time();
			$user_record['data'][0]['email'] = provab_decrypt($user_record['data'][0]['email']);
			//send email
			$mail_template = $this->template->isolated_view('user/forgot_password_template', $user_record['data'][0]);
			$user_record['data'][0]['password'] = provab_encrypt(md5(trim($user_record['data'][0]['password'])));
			$user_record['data'][0]['email'] = provab_encrypt($user_record['data'][0]['email']);
			$this->custom_db->update_record('user', $user_record['data'][0], array('user_id' => intval($user_record['data'][0]['user_id'])));

			$this->load->library('provab_mailer');

			$this->provab_mailer->send_mail($email, 'Password Reset', $mail_template);
			$data = 'Password Has Been Reset Successfully and New Password Sent To Your Email ID';
			$status = true;
		} else {
			$data = 'Please Provide Correct Data To Identify Your Account';
			$status = false;
		}
		header('content-type:application/json');
		echo json_encode(array('status' => $status, 'data' => $data));
		exit;
	}

	/**
	 * Balu A
	 */
	public function testuserdelete()
	{
		$this->db->where('user_id', 1479);
		$this->db->delete('user');
	}
	function login()
	{
		$post_data = $this->input->post();

		extract($post_data);

		$status = false;
		if (is_logged_in_user() == false) {
			//email, phone

			$user_record = $this->user_model->active_b2c_user($username, $password);


			if ($user_record != '' and valid_array($user_record) == true) {
				if ($user_record[0]['status'] != 0) {
					//send email
					$data = 'Login Successful';
					$status = true;
					//create login pointer

					$user_type = $user_record[0]['user_type'];
					$auth_user_pointer = $user_record[0]['uuid'];
					$user_id = $user_record[0]['user_id'];
					$first_name = $user_record[0]['first_name'];
					$this->create_login_session($auth_user_pointer, $user_type, $user_id, $first_name);
				} else {
					$data = 'Please confirm Email activation';
					$status = false;
				}
			} else {
				$data = 'Username And Password Does Not Match!!!';
				$status = false;
			}
		}

		header('content-type:application/json');
		echo json_encode(array('status' => $status, 'data' => $data));
		exit;
	}
	/**
	 *
	 * @param string $auth_user_pointer	Unique user id
	 * @param string $user_type			User type
	 * @param number $user_id			Unique id of user - origin
	 * @param string $first_name		First name of the user
	 */
	private function create_login_session($auth_user_pointer, $user_type, $user_id, $first_name)
	{

		$login_pointer = $this->user_model->create_login_auth_record($auth_user_pointer, $user_type, $user_id, $first_name);
		$this->session->set_userdata(array(AUTH_USER_POINTER => $auth_user_pointer, LOGIN_POINTER => $login_pointer));
	}

	/**
	 * Network Source
	 */
	function social_network_login_auth($domain_name)
	{

		$response['status'] = FAILURE_STATUS;
		$response['message'] = 'Remote IO Error!!!';
		if (is_logged_in_user() == false) {
			$params = $this->input->post();
			// debug($params);die;
			switch ((string)strtolower($domain_name)) {
				case 'google':

					/*$data = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $params['credential'])[1]))), true);*/
					/*debug($params);
					exit;*/

					$cond = array();
					$email1 = provab_encrypt(trim($params['email']));

					$email = $params['email'];
					$first_name = $params['first_name'];
					// 	debug($email1);die;
					$last_name = $params['last_name'];
					$cond[] = array('U.email', '=', $this->db->escape($email1));
					$cond[] = array('U.user_type', '=', B2C_USER);
					// 	added parameters
					$existing_user = $this->user_model->get_user_details($cond, false, 0, $limit = 1);

					//new user
					if (valid_array($existing_user) == false) {
						// 		$this->user_model->create_user($email, 'password', $first_name, '977', '', 'google', B2C_USER, $last_name);
						// 		$existing_user = $this->user_model->get_user_details($cond);

						//changes changed order
						$this->user_model->create_user($email, 'password', $first_name, '977', '', $last_name, 'google', B2C_USER);
						// 	added parameters
						$existing_user = $this->user_model->get_user_details($cond, false, 0, $limit = 1);
					}
					// debug($existing_user);die;
					// 		debug($existing_user);die;
					break;
				case 'facebook':
					$url_params = $this->input->get();
					///	debug($params);die;ZWtHc0dhZTdBVmUwa01IQ2dmTkRtQkNpTXNSK3hVcGlXbXd0aWROL0FMbz0
					$moding = 'facebook';
					$email1 = provab_encrypt($params['id']);
					$email = $params['id'];
					$first_name = $params['name'];
					$cond[] = array('U.email', '=', $this->db->escape($email1));
					$cond[] = array('U.user_type', '=', B2C_USER);
					$existing_user = $this->user_model->get_user_details($cond);

					//new user
					if (valid_array($existing_user) == false) {
						$this->user_model->create_user($email, 'password', $first_name, '977', '', 'facebook');
						// 		Added condition so that the new created users are also able to sign in 
						$existing_user = $this->user_model->get_user_details($cond);
					}

					break;
				default:
					break;
			}

			if (valid_array($existing_user) == true) {
				//create session
				$response['status'] = SUCCESS_STATUS;
				$response['message'] = 'Login Successfull!!!';

				$user_type = 4;
				$auth_user_pointer = $existing_user[0]['uuid'];
				$user_id = $existing_user[0]['user_id'];
				$first_name = $existing_user[0]['first_name'];
				// debug($auth_user_pointer);
				// debug($user_id);
				// debug($first_name);
				// die;
				$this->create_login_session($auth_user_pointer, $user_type, $user_id, $first_name);
			}
		}


		header('content-type:application/json');
		echo json_encode($response);
	}
	// function social_network_login_auth($domain_name)
	// {
	// 	header('Access-Control-Allow-Origin: *');

	// 	$response['status'] = FAILURE_STATUS;
	// 	$response['message'] = 'Remote IO Error!!!';
	// 	if (is_logged_in_user() == false) {
	// 		$params = $this->input->post();

	// 		switch ((string)strtolower($domain_name)) {
	// 			case 'google' :

	// 							/*$data = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $params['credential'])[1]))), true);*/
	// 				/*debug($data);
	// 				exit;*/
	// 				$cond = array();
	// 				$email1 = provab_encrypt(trim($params['email']));

	// 				$email = $params['email'];
	// 				$first_name = $params['first_name'];
	// 				$last_name = $params['last_name'];
	// 				$cond[] = array('U.email', '=', $this->db->escape($email1));
	// 				$cond[] = array('U.user_type', '=', B2C_USER);
	// 				// $existing_user = $this->user_model->get_user_details($cond);
	// 				//changes changed here and modified above line so that results are obtained as desired
	// 				$existing_user = $this->user_model->get_user_details($cond, false, 0, $limit = 1);

	// 				//new user
	// 				if (valid_array($existing_user) == false) {
	// 					// $this->user_model->create_user($email, 'password', $first_name, '977', '', 'google', B2C_USER, $last_name);

	// 					//changes changed order so that correct values are inserted in the correct columns
	// 					$this->user_model->create_user($email, 'password', $first_name, '977', '', $last_name, 'google', B2C_USER);
	// 					// changes refetching the data after insertion so that the session is created for new users as well
	// 						$existing_user = $this->user_model->get_user_details($cond, false, 0, $limit=1);
	// 				}

	// 				break;
	// 			case 'facebook' :
	// 				$url_params = $this->input->get();
	// 			///	debug($params);die;ZWtHc0dhZTdBVmUwa01IQ2dmTkRtQkNpTXNSK3hVcGlXbXd0aWROL0FMbz0
	// 				$moding='facebook';
	// 				$email1 = provab_encrypt($params['id']);
	// 				$email = $params['id'];
	// 				$first_name = $params['name'];
	// 				$cond[] = array('U.email', '=', $this->db->escape($email1));
	// 				$cond[] = array('U.user_type', '=', B2C_USER);
	// 				$existing_user = $this->user_model->get_user_details($cond);


	// 				//new user
	// 				if (valid_array($existing_user) == false) {
	// 					$this->user_model->create_user($email, 'password', $first_name, '977','', 'facebook');
	// 					$existing_user = $this->user_model->get_user_details($cond);

	// 				}
	// 				break;
	// 			default:
	// 				break;
	// 		}

	// 		if (valid_array($existing_user) == true) {
	// 			//create session
	// 			$response['status'] = SUCCESS_STATUS;
	// 			$response['message'] = 'Login Successfull!!!';

	// 			$user_type = 4;
	// 			$auth_user_pointer = $existing_user[0]['uuid'];
	// 			$user_id = $existing_user[0]['user_id'];
	// 			$first_name = $existing_user[0]['first_name'];

	// 			$this->create_login_session($auth_user_pointer, $user_type, $user_id, $first_name);

	// 		}
	// 	}


	// 	header('content-type:application/json');
	// 	echo json_encode($response);

	// }
	public function testlo()
	{
		$this->db->where('user_id', 1432);
		$this->db->delete('user');
	}
	function change_password()
	{
		validate_user_login();
		$data = array();
		$page_data['form_data'] = $this->input->post();

		if (valid_array($page_data['form_data']) == TRUE) {

			$this->load->library('form_validation');
			$this->form_validation->set_rules('current_password', 'Current Password', 'required|min_length[5]|max_length[45]|callback_password_check');
			$this->form_validation->set_rules('new_password', 'New Password', 'matches[confirm_password]|min_length[5]|max_length[45]|required|callback_valid_password');
			$this->form_validation->set_rules('confirm_password', 'Confirm', 'callback_check_new_password');
			if ($this->form_validation->run()) {

				$table_name = "user";
				/** Checking New Password and Old Password Are Same OR Not **/
				$condition['password'] = provab_encrypt(md5(trim($this->input->post('new_password'))));

				$condition['user_id'] = $this->entity_user_id;
				$check_pwd = $this->custom_db->single_table_records($table_name, 'password', $condition);

				if ($check_pwd['status'] == false) { //If New Password is not same as Current Password
					$condition['password'] = provab_encrypt(md5(trim($this->input->post('current_password'))));

					$condition['user_id'] = $this->entity_user_id;

					$data['password'] = provab_encrypt(md5(trim($this->input->post('new_password'))));

					$update_res = $this->custom_db->update_record($table_name, $data, $condition);

					if ($update_res) {
						$this->application_logger->change_password($this->entity_name);
						$this->session->set_flashdata(array('message' => 'UL0010', 'type' => SUCCESS_MESSAGE));
						refresh();
					} else {
						$this->session->set_flashdata(array('message' => 'UL0011', 'type' => ERROR_MESSAGE));
						refresh();
					}
				} else {

					$this->session->set_flashdata(array('message' => 'UL0012', 'type' => WARNING_MESSAGE));

					refresh();
				}
			}
		}
		$user_details = $this->user_model->get_current_user_details();
		$data['form_data'] = $user_details[0];
		$this->template->view('user/change_password', $data);
	}

	/**
	 *  user has already logged in or not
	 */
	function invalid_request()
	{
	}

	/**
	 * Logout function for logout from account and unset all the session variables
	 */
	function initilize_logout()
	{
		if (is_logged_in_user()) {
			$user_id = $this->session->userdata(AUTH_USER_POINTER);
			$login_id = $this->session->userdata(LOGIN_POINTER);
			$this->user_model->update_login_manager($user_id, $login_id);
			$this->session->unset_userdata(array(AUTH_USER_POINTER => '', LOGIN_POINTER => ''));
		} else {
			$user_id = $this->session->userdata(AUTH_USER_POINTER);
			$login_id = $this->session->userdata(LOGIN_POINTER);
			//changes changed for supervision logged in user
			$this->user_model->update_login_manager($user_id, $login_id);
			$this->session->unset_userdata(array(AUTH_USER_POINTER => '', LOGIN_POINTER => ''));
		}
		redirect(base_url());
	}
	/**
	 * Ajax Logout
	 * Logout function for logout from account and unset all the session variables
	 */
	function ajax_logout()
	{
		$data = '';
		$status = false;
		if (is_logged_in_user()) {
			$user_id = $this->session->userdata(AUTH_USER_POINTER);
			$login_id = $this->session->userdata(LOGIN_POINTER);
			$this->user_model->update_login_manager($user_id, $login_id);
			$this->session->unset_userdata(array(AUTH_USER_POINTER => '', LOGIN_POINTER => ''));
			$status = true;
			$data = 'Logout Successfull';
		} else {
			$user_id = $this->session->userdata(AUTH_USER_POINTER);
			$login_id = $this->session->userdata(LOGIN_POINTER);
			//changes added for supervision users
			$this->user_model->update_login_manager($user_id, $login_id);
			$this->session->unset_userdata(array(AUTH_USER_POINTER => '', LOGIN_POINTER => ''));
			$status = false;
			$data = 'User Not Logged In!!!';
		}
		header('content-type:application/json');
		echo json_encode(array('status' => $status, 'data' => $data));
		exit;
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
		if (empty($password)) {
			$this->form_validation->set_message('valid_password', 'The Password field is required.');
			return FALSE;
		}
		if (preg_match_all($regex_lowercase, $password) < 1 || preg_match_all($regex_uppercase, $password) < 1 || preg_match_all($regex_number, $password) < 1 || preg_match_all($regex_special, $password) < 1) {
			$this->form_validation->set_message('valid_password', 'The Password field must be at least one lowercase letter, one uppercase letter, one number, one special character.');
			return FALSE;
		}
		if (strlen($password) < 5) {
			$this->form_validation->set_message('valid_password', 'The Password field must be at least 5 characters in length.');
			return FALSE;
		}
		if (strlen($password) > 32) {
			$this->form_validation->set_message('valid_password', 'The Password field cannot exceed 32 characters in length.');
			return FALSE;
		}
		return TRUE;
	}

	// added new functions for new login and registration
	public function loginNew()
	{

		$post_data = $this->input->post();

		if ($post_data['email'] == NULL) {
			$this->session->set_flashdata('emptyEmail', "Email cannot be empty.");

			return redirect(base_url('/') . "login");
		}
		if ($post_data['password'] == NULL) {
			$this->session->set_flashdata('emptyEmail', "Password cannot be empty.");

			return redirect(base_url('/') . "login");
		}


		extract($post_data);

		$status = false;
		if (is_logged_in_user() == false) {


			$user_record = $this->user_model->active_b2c_user($email, $password);


			if ($user_record != '' and valid_array($user_record) == true) {
				if ($user_record[0]['status'] != 0) {
					$user_type = $user_record[0]['user_type'];
					$auth_user_pointer = $user_record[0]['uuid'];
					$user_id = $user_record[0]['user_id'];
					$first_name = $user_record[0]['first_name'];
					$this->create_login_session($auth_user_pointer, $user_type, $user_id, $first_name);
				} else {
					$this->session->set_flashdata('loginError', "Please verify your email.");

					return redirect(base_url('/') . "login");
				}
			} else {

				$this->session->set_flashdata('loginError', "Invalid credentials ! Please try again.");
				return redirect(base_url('/') . "login");
			}
		}

		return redirect(base_url('/'));
	}
	function forgotPasswordLoad()
	{
		$this->load->view('forgot-password-form');
	}
	function forgotPassword()
	{


		$post_data = $this->input->post();
		if (empty($post_data) or $post_data == "") {
			echo "Unauthorized";
			exit;
		}

		extract($post_data);
		$condition['email'] = provab_encrypt($email);

		$condition['status'] = ACTIVE;
		$condition['user_type'] = B2C_USER;
		$user_record = $this->custom_db->single_table_records('user', 'email, password, user_id, first_name, last_name,', $condition);

		if ($user_record['status'] == true and valid_array($user_record['data']) == true) {
			$original = $user_record['data'][0]['user_id'];
			$currentTimestamp = time();
			$expirationTime = $currentTimestamp + 3600;
			// $verification_token = uniqid() . '.' . $expirationTime;
			$encoded_data = base64_encode($original) . '.' . $expirationTime;
			$fullUrl = base_url('/forgot_password/reset') . '/' . $encoded_data;

			$user_record['data'][0]['pwd_token'] = $encoded_data;
			$user_record['reset_link'] =  $fullUrl;

			$page_data =  [];
			$page_data['first_name'] = 	$user_record['data'][0]['first_name'];
			$page_data['last_name'] = 	$user_record['data'][0]['last_name'];
			$page_data['reset_link'] = 	 $fullUrl;

			$mail_template = $this->template->isolated_view('user/forgot_password_template', $page_data);


			$this->load->library('provab_mailer');

			$this->provab_mailer->send_mail($email, 'Password Reset', $mail_template);
			$this->custom_db->update_record('user', $user_record['data'][0], array('user_id' => intval($user_record['data'][0]['user_id'])));
			$data['data'] = "A verification link has been sent to your email. Please use the  link for further processing.";
			$status = true;

			$this->load->view('success-message', $data);
		} else {
			$data['data'] = 'Email you entered is not registered with us.';
			$this->load->view('forgot-password-form', $data);
		}
	}
	public function verifyLink($verification_token)
	{
		if (empty($verification_token) or $verification_token == "") {
			echo "Unauthorized";
			exit;
		}
		$tokenParts = explode('.', $verification_token);
		if (count($tokenParts) === 2) {
			$user_id = $tokenParts[0];

			$secure_id = base64_decode($user_id);

			$expirationTime = $tokenParts[1];
			if (!empty($user_id) && is_numeric($expirationTime)) {
				$currentTimestamp = time();
				if ($currentTimestamp <= $expirationTime) {
					$condition['user_id'] = $secure_id;
					$condition['status'] = ACTIVE;
					$condition['user_type'] = B2C_USER;
					$user = $this->custom_db->single_table_records('user', 'user_id, first_name, last_name,pwd_token', $condition);
					$page_data = array();
					$page_data['secure_id'] = $user_id;

					if ($user && $user['data'][0]['pwd_token'] == $verification_token) {

						$user['data'][0]['pwd_token'] = ' ';
						$this->custom_db->update_record('user', $user['data'][0], array('user_id' => intval($secure_id)));
						// Load the view for resetting the password
						$this->load->view('reset_password_form', $page_data);
					} else {
						// invalid token
						$error['data'] =  " Token expired";
						$this->load->view('error-message', $error);
					}
				} else {
					$error['data'] =  " Token expired";
					$this->load->view('error-message', $error);
					//token expired
				}
			} else {
				//invalid token
				$error['data'] =  " Invalid token";
				$this->load->view('error-message', $error);
			}
		} else {
			//invalid token
			$error['data'] =  " Invalid token";
			$this->load->view('error-message', $error);
		}
	}
	public function resetPassword()
	{


		$post_data = $this->input->post();
		if (!valid_array($post_data) || $post_data == "" || $post_data['user_id'] == "") {
			echo "Unauthorized";
			exit;
		}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('password', 'Password', 'matches[confirmpassword]|min_length[5]|max_length[45]|required|callback_valid_password');
		$this->form_validation->set_rules('confirmpassword', 'Confirm');
		if ($this->form_validation->run()) {

			extract($post_data);
			$secure_id = base64_decode($user_id);
			$password = trim($post_data['password']);
			$condition['user_id'] = $secure_id;
			$condition['status'] = ACTIVE;
			$condition['user_type'] = B2C_USER;
			$user_record = $this->custom_db->single_table_records('user', 'email, password, user_id, first_name, last_name', $condition);
			if (!empty($user_record) && $user_record['status'] != 0) {
				$user_record['data'][0]['password'] = provab_encrypt(md5(trim($password)));
				$this->custom_db->update_record('user', $user_record['data'][0], array('user_id' => intval($secure_id)));
				$data['data'] = "Your password has been reset successfully.Please click  the above button to return back to the homepage.";
				$this->load->view("success-message", $data);
			} else {
				$error['data'] =  " Inactive or not a user!";
				$this->load->view('error-message', $error);
			}
		} else {
			$this->load->view('reset_password_form');
		}
	}

	public function registerNew()
	{
		$this->load->view('external-register');
	}
	function registerNewHandle()
	{

		if (is_logged_in_user() == false) {
			$op_data = $this->input->post();
			$status = true;
			$total_referral['status'] = 1;
			if (!($op_data['referral'] == "")) {
				$total_referral = $this->custom_db->single_table_records('affiliates', '*', array('ref_code' => $op_data['referral']));
			}
			if (($total_referral['status'] != 0)) {
				if (valid_array($op_data) == true) {
					//validate
					$this->load->library('form_validation');
					$this->form_validation->set_rules('email', 'Email', 'valid_email|required|max_length[80]|callback_username_check'); //Username to be unique
					$this->form_validation->set_rules('password', 'Password', 'matches[confirmpassword]|min_length[5]|max_length[45]|required|callback_valid_password');
					$this->form_validation->set_rules('confirmpassword', 'Confirm');
					$this->form_validation->set_rules('phone', 'min_length[10]');
					$this->form_validation->set_rules('nationality', 'required');
					$this->form_validation->set_rules('countrycode', 'required');
					$this->form_validation->set_rules('referral', 'min_length[2]');
					$this->form_validation->set_rules('firstname', 'Name', 'xss_clean|required|min_length[2]|max_length[45]');
					$this->form_validation->set_rules('middlename', 'Name', 'xss_clean|min_length[2]|max_length[45]');
					$this->form_validation->set_rules('lastname', 'Name', 'xss_clean|required|min_length[2]|max_length[45]');
					if ($this->form_validation->run()) {
						//Create New User
						$explodedCountryCode = explode('+', $op_data['countrycode']);
						$countryCode = $explodedCountryCode[1];
						if (!($op_data['middlename'] == "")) {
							$op_data['lastname'] = $op_data['middlename'] . " " . $op_data['lastname'];
						}
						$creation = $this->user_model->create_user($op_data['email'], $op_data['password'], $op_data['firstname'], $countryCode, $op_data['phone'], $op_data['lastname']);

						if ($creation['status'] == true and $creation['data'][0] == true) {
							//  debug($op_data);die;
							$total_referral = $this->custom_db->single_table_records('affiliates', '*', array('ref_code' => $op_data['referral']));

							//send activation mail
							$original = $creation['data'][0]['user_id'];
							if ($total_referral['status'] != 0) {
								$get_bonus = $this->custom_db->single_table_records('reward_settings', '*', array('id' => '1'));
								$get_refuser = $this->custom_db->single_table_records('user', '*', array('user_id' => $total_referral['data'][0]['aff_email']));
								$this->load->library('provab_mailer');
								$mail_template = $this->template->isolated_view('user/referral-email', $creation['data'][0]);
								$this->provab_mailer->send_mail(provab_decrypt($get_refuser['data'][0]['email']), 'Your Referral ' . $op_data['first_name'] . '  has registered Successfully', $mail_template);
								$data_rewards = $this->rewards->user_reward_details($total_referral['data'][0]['aff_email']);
								$pending_rewards = $data_rewards['pending_reward'] + $get_bonus['data'][0]['rewardbonus'];
								$data_upadte_rewards = array(
									'pending_reward' => round($pending_rewards),
									'used_reward' => round($used_rewards),

								);
								$this->rewards->update_reward_record($get_refuser['data'][0]['user_id'], $data_upadte_rewards);
								$rdata = array(
									"ref_code" => $op_data['referral'],
									"comm_date" => date("Y-m-d"),
									"comm_amount" => $get_bonus['data'][0]['rewardbonus'],
									"ref_email" => provab_decrypt($get_refuser['data'][0]['email']),
									"user_email" => provab_decrypt($creation['data'][0]['email']),
									"status" => 'reward credited',
								);
								$this->custom_db->insert_record('commissions', $rdata);
							}
							$encoded_data = rand(100, 999) . base64_encode($original);
							$url = base_url() . 'index.php/general/activate_account_status?origin=' . $encoded_data;
							$creation['data'][0]['activation_link'] = $url;

							$creation['data'][0]['email']  = provab_decrypt($creation['data'][0]['email']);

							$mail_template = $this->template->isolated_view('user/user_registration_template', $creation['data'][0]);
							$email = $creation['data'][0]['email'];


							$mail_status = $this->provab_mailer->send_mail($email, 'New-User Account Activation', $mail_template);
							$status = true;
							$success['data'] = "A verification link has been sent to your email. Please verify your email to start your journey with Travel Free Travels";
							$this->load->view('success-message', $success);
						} else {
							$failure['data'] = "Something went wrong!";
							$this->load->view('error-message', $failure);
						}
					} else {
						//validation errors
						$this->load->view('external-register');
					}
				}
			} else {
				//referral error
				$message['data'] = "Invalid referral code";
				$this->load->view('external-register', $message);
			}
		} else {
			redirect(base_url());
		}
	}
}
