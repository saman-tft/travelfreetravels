<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @package    Provab
 * @subpackage General
 * @author     Balu A<balu.provab@gmail.com>
 * @version    V1
 */
 //error_reporting(E_ALL);

class General extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		//$this->output->enable_profiler(TRUE);
		$this->load->model('user_model');
	}

	/**
	 * index page of application will be loaded here
	 */
	function index()
	{
            // echo "in";exit;
		if (is_logged_in_user()) {
			
			//$this->load->view('dashboard/reminder');
			redirect(base_url().'index.php/menu/index');
		} else {
			//show login
			$data = array();
			if($_POST)
			{
			 $data['error'] = 'Invalid credentials';
			}
      $pass= provab_encrypt(md5(trim($page_data['form_data']['password'])));
			$this->template->view('general/login',$data);
		}
	}

	/**
	 * Logout function for logout from account and unset all the session variables
	 */
	function initilize_logout() {
		if (is_logged_in_user()) {
			$this->user_model->update_login_manager($this->session->userdata(LOGIN_POINTER));
			$this->session->unset_userdata(array(AUTH_USER_POINTER => '',LOGIN_POINTER => '', DOMAIN_AUTH_ID => '', DOMAIN_KEY => ''));
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

	/*
	 * @domain Key
	 */
	public function view_subscribed_emails()
	{
		$params = $this->input->get();
	
		$domain_key = get_domain_auth_id();
		if(intval($domain_key) > 0) {
			$data['domain_admin_exists'] = true;
		} else {
			$data['domain_admin_exists'] = false;
		}
		$data['subscriber_list'] = $this->user_model->get_subscribed_emails($domain_key, $params['email']);
		$this->template->view('user/subscribed_email',$data);
	}
	public function active_emails($id){
		$cond['id'] = intval($id);
		$data['status'] = ACTIVE;
		$info = $this->user_model->update_subscribed_emails($data, $cond);
		
		exit;
	}
	public function deactive_emails($id){
		$cond['id'] = intval($id);
		$data['status'] = INACTIVE;
		$info = $this->user_model->update_subscribed_emails($data, $cond);
		
		exit;
	}
		public function email_exist(){
		echo "ok";
		
		exit;
	}
	function email_delete($id){
		if($id){
			$this->custom_db->delete_record('email_subscribtion',array('id' => $id));
		}
		redirect('general/view_subscribed_emails');		
	}
	function event_location_map()
	{
		$details = $this->input->get();
		$geo_codes['data']['latitude'] = $details['latitude'];
		$geo_codes['data']['longtitude'] = $details['longtitude'];
		$geo_codes['data']['name'] = 'Event Log Location';
		$geo_codes['data']['ip'] = $details['ip'];
		echo $this->template->isolated_view('general/event_location_map', $geo_codes);
	}
	
	function test($app_reference)
	{
		$this->load->model('flight_model');
		echo $this->flight_model->get_extra_services_total_price($app_reference);
		
		/*$query = 'select * from flight_booking_transaction_details where app_reference="'.$app_reference.'"  order by origin asc';
		$transaction_details = $this->db->query($query)->result_array();
		foreach($transaction_details as $tk => $tv){
			$query = 'select FP.origin, FP.first_name,FP.last_name,concat(FB.description, "-", FB.price) as Baggage 
			from flight_booking_passenger_details FP
			left join flight_booking_baggage_details FB on FP.origin=FB.passenger_fk
			where FP.flight_booking_transaction_details_fk='.$tv['origin'].' order by FP.origin';
			$baggae_details = $this->db->query($query)->result_array();
			
			$query = 'select FP.origin, FP.first_name,FP.last_name,concat(FM.description, "-", FM.price) as Meal 
			from flight_booking_passenger_details FP
			left join flight_booking_meal_details FM on FP.origin=FM.passenger_fk
			where FP.flight_booking_transaction_details_fk='.$tv['origin'].' order by FP.origin';
			$meal_details = $this->db->query($query)->result_array();
			echo '<br/>Baggage: ';
			debug($baggae_details);
			echo '<br/>MEALS: ';
			debug($meal_details);
		}
		echo 'DONE';*/
	}
	/*sending the OTP*/
	//  function send_otp($opt =''){
	// 	// error_reporting(E_ALL);
	// 	$post_data = $this->input->post();
	// 	$data=array();

	// 	$data['user_name'] =$this->db->escape_str( isset ( $post_data ['email'] ) ? $post_data ['email'] : '');
	// 	$data['password'] = $this->db->escape_str(isset ( $post_data ['password'] ) ? $post_data ['password'] : '');
	// 	$data['status']=true;
	// 	$data['user_name'] = provab_encrypt($data['user_name']);
	// 	$data['password'] = provab_encrypt(md5($data['password']));
	// 	$user_details = $this->user_model->get_admin_details($data);
	// 	// debug($user_details);
	// 	// die;
	// 	if(!isset($user_details['uuid']))
	// 	{
	// 	   echo "false";
	// 	   return false; 
	// 	}
	// 	$this->load->library('provab_mailer');
	// 	$email = $post_data ['email'];
	// 	$random_number = rand(100000,100000000);
	// 	//$mail_template  = 'Hello Admin, <br />Please enter the OTP to Login Admin Dashboard:- '.$random_number;
	// 	$otp_data['OTP'] = $random_number;
	// 	$otp_data['email'] = $email;
	// 	$otp_no = $this->session->set_userdata ( $otp_data );
	// 	$mail_template = $this->template->isolated_view('user/send_otp_template',$otp_data);
	// 	$res = $this->provab_mailer->send_mail($email, domain_name().' - Login OTP',$mail_template );
	// 	// print_r($res);exit;
	// 	if($res['status'] == true){
	// 		echo true;
	// 		// echo $otp_no;
	// 	}
	// 	else{
	// 		echo false;
	// 	}
	// 	exit;
	// }

   function activate_account_status() {
   //	echo "string";exit();
   	    $origin = $this->input->get('origin');
   	   // debug($origin);exit();
        $unsecure = substr($origin, 3);
        $secure_id = base64_decode($unsecure);
        //debug($secure_id);exit();
        $user_data = $this->custom_db->single_table_records('user','*',array('user_id' => $secure_id, 'email_activation'=> 1));
        //debug($user_data);exit();
        if($user_data['status'] == 0) {
          $status = ACTIVE;
          $email_activation = 1;
          $this->user_model->activate_account_status($status, $email_activation, $secure_id);
        } else {
          $this->session->set_flashdata(array('message' => 'AL009', 'type' => ERROR_MESSAGE));
          //$this->session->set_flashdata('Expired', 'FAILURE_MESSAGE', false, 'Expired');
          redirect ( base_url () );
        }
        //debug($user_data);die;
        
        redirect(base_url());
    }

 function send_otp($opt = '') {
        
        $post_data = $this->input->post();
        $data = array();

        $data['user_name'] = $this->db->escape_str(isset($post_data ['email']) ? $post_data ['email'] : '');
        $data['password'] = $this->db->escape_str(isset($post_data ['password']) ? $post_data ['password'] : '');
        $data['status'] = true;
        $data['user_name'] = provab_encrypt($data['user_name']);
        $data['password'] = provab_encrypt(md5($data['password']));
       // debug($data);exit();
        $user_details = $this->user_model->get_admin_details($data);

        //debug($user_details);exit();

        // debug($user_details);exit();
        if (!isset($user_details['uuid'])) {
            echo "false";
            return false;
        }
        $this->load->library('provab_mailer');
        $email = $post_data ['email'];
       //  $random_number = rand(100000, 100000000);
        $random_number = 12345;
        $mail_template = 'Hello Admin, <br />Please enter the OTP to Login Admin Dashboard:- ' . $random_number;
        $otp_data['OTP'] = $random_number;
        $this->session->set_userdata('OTP_status',$otp_data);
         $this->session->set_userdata('OTP',$otp_data['OTP']);
      //  echo true;
        //debug($otp_data);
        $res = $this->provab_mailer->send_mail($email, domain_name() . ' - Login OTP', $mail_template);
         //debug($res);
        $res['status']=true;
        if ($res['status'] == true) {
            echo true;
        } else {
            echo false;
        }
        exit;
    }


	public function change_admin_password(){
		$user_details = $this->custom_db->single_table_records('user','*',array('user_type'=>1));

		exit;
		foreach ($user_details['data'] as $key => $value) {
			

			$update = array();
			$condition = array();
			//$update['uuid'] = provab_encrypt($value['uuid']);
			//$update['email'] = provab_encrypt($value['email']);
			//$update['user_name'] = $update['email'];
			$update['password'] = provab_encrypt(md5('Provab@123'));
			$condition['user_id'] = $value['user_id'];
			
			if($this->custom_db->update_record('user',$update,$condition)){
				echo 'ss'.$value['email'].'<br/>';
			}else{
				echo 'dfal';
			}

		}
		
		exit;
	}

	public function email_configuration(){
		$encrypt_method = "AES-256-CBC";
		$secret_iv = PROVAB_SECRET_IV;
		$md5_key = PROVAB_MD5_SECRET;
        $encrypt_key = PROVAB_ENC_KEY;
        $page_data = array();
        $email_data  = $this->custom_db->single_table_records('email_configuration','*',array('origin' =>1));
       
		if($email_data['status'] == SUCCESS_STATUS){
			$email_data = $email_data['data'][0];
			$page_data['user_name'] = provab_decrypt($email_data['username']);
			$page_data['password'] = provab_decrypt($email_data['password']);
			$page_data['from'] = $email_data['from'];
			$page_data['host'] = provab_decrypt($email_data['host']);
			$page_data['port'] = provab_decrypt($email_data['port']);
			$page_data['cc'] = provab_decrypt($email_data['cc']);
			$page_data['bcc'] = provab_decrypt($email_data['bcc']);
		}
		if(empty($_POST) == false){
			$data['username'] = $_POST['username'];
			$data['password'] = $_POST['password'];
			$data['from'] = $_POST['from'];
			$data['host'] = $_POST['host'];
			$data['port'] = $_POST['port'];
			$data['cc'] = $_POST['cc_email'];
			$data['bcc'] = $_POST['bcc_email'];

			$decrypt_password = $this->db->query("SELECT AES_DECRYPT($encrypt_key,SHA2('".$md5_key."',512)) AS decrypt_data");
          
          	$db_data = $decrypt_password->row();
         
          	$secret_key = trim($db_data->decrypt_data); 

          	$key = hash('sha256', $secret_key);
	        $iv = substr(hash('sha256', $secret_iv), 0, 16);
	        $username = openssl_encrypt($data['username'], $encrypt_method, $key, 0, $iv);
	        $username = base64_encode($username);

	        $password = openssl_encrypt($data['password'], $encrypt_method, $key, 0, $iv);
          	$password = base64_encode($password);

          	$host = openssl_encrypt($data['host'], $encrypt_method, $key, 0, $iv);
          	$host = base64_encode($host);

          	$cc = openssl_encrypt($data['cc'], $encrypt_method, $key, 0, $iv);
          	$cc = base64_encode($cc);

          	$port = openssl_encrypt($data['port'], $encrypt_method, $key, 0, $iv);
          	$port = base64_encode($port);

          	$bcc = openssl_encrypt($data['bcc'], $encrypt_method, $key, 0, $iv);
          	$bcc = base64_encode($bcc);

          	$data1['username'] = $username;
			$data1['password'] = $password;
			$data1['from'] = $data['from'];
			$data1['host'] = $host;
			$data1['port'] = $port;
			$data1['cc'] = $cc;
			$data1['bcc'] = $bcc;
			$condition['origin'] = 1;
			// $page_data['message'] = 'Updated Successfully';
			$this->custom_db->update_record('email_configuration',$data1,$condition);
			redirect('general/email_configuration');
		}
		
		$this->template->view('user/email_configuration.php', $page_data);
	}

}