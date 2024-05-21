<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * provab
 *
 * Travel Portal Application
 *
 * @package		provab
 * @author		Balu A<balu.provab@gmail.com>
 * @copyright	Copyright (c) 2013 - 2014
 * @link		http://provab.com
 */

class Provab_Mailer {

	/**
	 * Provab Email Class
	 *
	 * Permits email to be sent using Mail, Sendmail, or SMTP.
	 *
	 * @package		provab
	 * @subpackage	Libraries
	 * @category	Libraries
	 * @author		Balu A<balu.provab@gmail.com>
	 * @link		http://provab.com
	 */
	public $CI;                     //instance of codeigniter super object
	public $mailer_status;         //mailer status which indicates if the mail should be sent or not
	public $mail_configuration;    //mail configurations defined by user

	/**
	 * Constructor - Loads configurations and get ci super object reference
	 */
	public function __construct($data='')
	{
	
		$this->mail_configuration = $this->decrypt_email_config_data();
	}

	/**
	 *Initialize mailer to send mail
	 *
	 *return array containing the status of initialization
	 */
	public function initialize_mailer()
	{
		error_reporting(0);
		$status = false;
		$message = 'Please Contact Admin To Setup Mail Configuration';
		$this->mailer_status = false;
	
		$this->mail_configuration['port']=465;

		if (is_array($this->mail_configuration) == true and count($this->mail_configuration) > 0) {
			if (intval($this->mail_configuration['status']) == 1 ) {
				if (isset($this->mail_configuration['username']) == true and empty($this->mail_configuration['username']) == false and
				isset($this->mail_configuration['password']) == true and empty($this->mail_configuration['password']) == false
				) {
					$config['STARTTLS'] = TRUE;

					$config['useragent'] = 'PHPMailer';

		
					$config['smtp_port']='587';

					$config['smtp_host'] = isset($this->mail_configuration['host']) == true ? $this->mail_configuration['host'] : 'smtp.gmail.com';

				       $config['smtp_host'] ='smtp.gmail.com';

					$config['smtp_timeout']     = 5;
				
				       $config['protocol'] = 'gsmtp';

					$config['wordwrap'] = TRUE;

					$config['mailtype'] = 'html';

					$config['charset'] = 'iso-8859-1';


					$config['crlf'] = "\r\n";

					$config['_smtp_auth'] = TRUE;

			              $config['smtp_crypto'] = 'tls';

					$config['newline'] = "\r\n";

				//	$this->CI->load->library('email', $config);
					if($_SERVER['REMOTE_ADDR']=="223.187.115.160")
				{
				//    debug($config);die;	 
				}
					$this->CI->load->library('email', $config);
					// $this->email->initialize($config);
					$from = isset($this->mail_configuration['from']) == true ? $this->mail_configuration['from'] : PROJECT_NAME;
					
					$this->CI->email->from($this->mail_configuration['username'], $from);
					$this->CI->email->set_newline("\r\n");

					//set cc and bcc
					if (isset($this->mail_configuration['bcc']) == true and empty($this->mail_configuration['bcc']) == false) {
						$this->CI->email->bcc($this->mail_configuration['bcc']);
					}
					if (isset($this->mail_configuration['cc']) == true and empty($this->mail_configuration['cc']) == false) {
						$this->CI->email->cc($this->mail_configuration['cc']);
					}
		//	debug($config);
		//	debug($this->mail_configuration);die;
					$this->mailer_status = true;
					$status = true;
					$message = 'Continue To Send Mail';
				}
			}
		}
		return array('status' => $status, 'message' => $message);
	}

	/**
	 *send mail to the user
	 *
	 *@param string $to_email     email id to which the mail has to be delivered
	 *@param string $mail_subject mail subject which has to be sent in the mail
	 *@param string $mail_message mail message which has to be sent in the mail body
	 *@return boolean status of sending mail
	 *@$attachment for single attachment pass file name , for multiple pass as array of filenames
	 *$cc and $bcc pass as array
	 */
		public function send_mail( $to_email, $mail_subject, $mail_message, $attachment='', $cc='', $bcc='')

	{

ini_set ('display_errors', 1);  
ini_set ('display_startup_errors', 1);  
error_reporting (E_ALL);  
		$status = false;

		

		$ini_status = $this->initialize_mailer();

		$message = $ini_status['message'];
		
        require_once 'class.phpmailer.php';

        $email_id =$to_email;

         $mail = new PHPMailer();
    $mail->IsSMTP(); // telling the class to use SMTP
    $mail->Host       = "smtp.gmail.com"; // SMTP server
    $mail->SMTPAuth   = true;
    $mail->SMTPSecure = 'tls';
    
    $mail->Port       = 587;

   $mail->Username   = 'info@travelfreetravels.com'; // pls give user id & pwd
    $mail->Password   = 'sxllpiggrsyinkrk';

    $mail->FromName   ='travelfreetravels';
    $mail->AddAddress($email_id);


     $mail->AddCC($cc);
  


    $mail->Subject = $mail_subject;
    $mail->Body    = $mail_message;

    $mail->IsHTML(true);
    $mail->WordWrap = 500;   

    $body =$mail_message;
//debug($mail);die;
    if (!$mail->Send()) {
        return array('status' =>0, 'message' =>'sent');
    }else{
    	//debug($mail_message);
    	//echo "sent mailer".$to_email;die;

       return array('status' =>1, 'message' =>'sent');
    }
		

	}
	public function send_mailoldest($to_email, $mail_subject, $mail_message, $attachment='', $cc='', $bcc='')
	{
	    
	    
	    
	    

error_reporting(E_ALL);
	//	 echo "sadwere".$to_email;exit;
		//$to_email = 'anitha.g.provab@gmail.com';
		$status = false;
		//initializing mailer configurations
		$ini_status = $this->initialize_mailer();
		$message = $ini_status['message'];
		//sending mail based on mailer status
		if ($this->mailer_status == true) {
			if ($to_email != '' && $mail_message != '' && $mail_subject != '') {
				//set mail data
				$this->CI->email->to(trim(strip_tags($to_email)));
				$this->CI->email->subject(trim($mail_subject));
				$this->CI->email->message($mail_message);
				//and attachment
				if (empty($attachment) == false) {
					if(valid_array($attachment)) {
						//for multple attachements
						foreach($attachment as $k => $v) {
							if(empty($v) == false) {
								$this->CI->email->attach($v);
							}
						}
					} else if(strlen($attachment) > 1){
						//for single attachements
						$this->CI->email->attach($attachment);
					}
				}
				//add CC
				if(empty($cc) == false && valid_array($cc)) {
					$ccEmail = '';
					//Validating Email
					foreach($cc as $k => $v) {
						if(filter_var($v, FILTER_VALIDATE_EMAIL) == true) {
							$ccEmail[] = trim($v);							
						}
					}
					if(valid_array($ccEmail)) {
						$this->CI->email->cc($ccEmail);
					}					
				}				
			    //add BCC
				if(empty($bcc) == false && valid_array($bcc)) {
				    $bccEmail = '';
					//Validating Email
					foreach($bcc as $k => $v) {
						if(filter_var($v, FILTER_VALIDATE_EMAIL) == true) {
							$bccEmail[] = trim($v);							
						}
					}
					if(valid_array($bccEmail)) {
						$this->CI->email->bcc($bccEmail);
					}
				}
				$result = $this->CI->email->send();
					if($_SERVER['REMOTE_ADDR']=="223.187.115.106")

						
				{
			   debug($this->CI->email->print_debugger());die;	 
				}
			
				if($result) {
					$status = true;
					$message = 'Mail Sent Successfully'; 
				} else {
					$status = false;
					
					$message = $this->CI->email->print_debugger();
				}

			} else {
				$status = false;
				$message = 'Please Provide To Email Address, Mail Subject And Mail Message';
			}
		}
		// debug($status);
		// debug($message);
		
		return array('status' => $status, 'message' => $message);
	}
	public function decrypt_email_config_data(){
		$this->CI =& get_instance();

		$id = '1';
		$secret_iv = PROVAB_SECRET_IV;
		$output = false;
    	$encrypt_method = "AES-256-CBC";
    	$email_config_data = $this->CI->custom_db->single_table_records('email_configuration','*' ,array('origin' => $id));
		if($email_config_data['status'] == true){
	      	foreach($email_config_data['data'] as $data){
	      		
		        if(!empty($data['username'])){
					$md5_key = PROVAB_MD5_SECRET;
					$encrypt_key = PROVAB_ENC_KEY;
					$decrypt_password = $this->CI->db->query("SELECT AES_DECRYPT($encrypt_key,SHA2('".$md5_key."',512)) AS decrypt_data");
					$db_data = $decrypt_password->row();
					$secret_key = trim($db_data->decrypt_data);	
					$key = hash('sha256', $secret_key);
				    $iv = substr(hash('sha256', $secret_iv), 0, 16);
				   	$username = openssl_decrypt(base64_decode($data['username']), $encrypt_method, $key, 0, $iv);
					$password = openssl_decrypt(base64_decode($data['password']), $encrypt_method, $key, 0, $iv);
					$cc = openssl_decrypt(base64_decode($data['cc']), $encrypt_method, $key, 0, $iv);
					$bcc = openssl_decrypt(base64_decode($data['bcc']), $encrypt_method, $key, 0, $iv);
					$host = openssl_decrypt(base64_decode($data['host']), $encrypt_method, $key, 0, $iv);
					$port = openssl_decrypt(base64_decode($data['port']), $encrypt_method, $key, 0, $iv);
					$mail_configuration['domain_origin'] = $data['domain_origin'];
					$mail_configuration['username'] = 'info@travelfreetravels.com';


					$mail_configuration['password'] = 'tdxdjemacjwfawuu';
					$mail_configuration['cc'] =$cc;
					$mail_configuration['from'] = $data['from'];
					$mail_configuration['bcc'] = $bcc;
					$mail_configuration['port'] = '587';
					$mail_configuration['host'] = 'ssl://smtp.gmail.com';
					$mail_configuration['status'] = $data['status'];
					//debug($mail_configuration);exit;
					return $mail_configuration;
				}
			}
		}
	}
}
?>
