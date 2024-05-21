<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//require_once BASEPATH . 'libraries/Encrypt.php';


/**

 * provab

 *

 * Travel Portal Application

 *

 * @package		provab

 * @author		Arjun J<arjun.provab@gmail.com>

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

	 * @author		Arjun J<arjun.provab@gmail.com>

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

		//debug($data);exit;

		if (valid_array($data) == true and intval($data['id']) > 0) {

			$id = intval($data['id']);

		} else {

			$id = '1';

		}

	



		$this->CI =& get_instance();
        //$this->CI->load->library('Encrypt');
		$return_data = $this->CI->custom_db->single_table_records('email_configuration','*' ,array('origin' => $id));

// 		debug($return_data);exit;

		$this->mail_configuration = $return_data['data'][0];

	}



	/**

	 *Initialize mailer to send mail

	 *

	 *return array containing the status of initialization

	 */

	public function initialize_mailer()

	{

		$status = false;

		$message = 'Please Contact Admin To Setup Mail Configuration';

		$this->mailer_status = false;

	

		if (is_array($this->mail_configuration) == true and count($this->mail_configuration) > 0) {

			if (intval($this->mail_configuration['status']) == 1 ) {

				if (isset($this->mail_configuration['username']) == true and empty($this->mail_configuration['username']) == false and

				isset($this->mail_configuration['password']) == true and empty($this->mail_configuration['password']) == false

				) {

					//configure email settings

					/*	for localhost

					 * $config['protocol'] = 'smtp';

					 * $config['charset'] = 'iso-8859-1';

					 */

					 /*	for server

					 * $config['protocol'] = 'gsmtp';

					 * $config['charset'] = 'utf-8';

					 */

					//set mail configurations

					$config['useragent'] = 'PHPMailer';

					

					$config['smtp_user'] = trim($this->mail_configuration['username']);

					$config['smtp_pass'] = trim($this->mail_configuration['password']);

					$config['smtp_port'] = isset($this->mail_configuration['port']) == true ? $this->mail_configuration['port'] : 587;

					$config['smtp_host'] = isset($this->mail_configuration['host']) == true ? $this->mail_configuration['host'] : 'ssl://smtp.gmail.com';



					$config['wordwrap'] = 500;

					$config['mailtype'] = 'html';


					$config['smtp_crypto'] = 'tls';

					

					 $config['protocol'] = 'gsmtp';

//					 $config['protocol'] = 'gsmtp';
					 // $config['smtp_user'] = 'info@trustedservicecenter.in';
					 // $config['smtp_pass'] = 'Shaik@123';
					 // $config['smtp_host'] = 'sg3plcpnl0187.prod.sin3.secureserver.net';

					 // $config['smtp_user'] = 'noreply@alcabana.com';
					 // $config['smtp_pass'] = 'Test#1234';
					 // $config['smtp_host'] = 'smtpout.secureserver.net';
// debug($config);die;
					

					$this->CI->load->library('email', $config);

					$from = isset($this->mail_configuration['from']) == true ? $this->mail_configuration['from'] : PROJECT_NAME;

					 $this->CI->email->from($this->mail_configuration['username'], $from);

					//$this->CI->email->from($this->mail_configuration['from'], "noreply");

					$this->CI->email->set_newline("\r\n");



					//set cc and bcc

					if (isset($this->mail_configuration['bcc']) == true and empty($this->mail_configuration['bcc']) == false) {

						$this->CI->email->bcc($this->mail_configuration['bcc']);

					}

					if (isset($this->mail_configuration['cc']) == true and empty($this->mail_configuration['cc']) == false) {

						$this->CI->email->cc($this->mail_configuration['cc']);

					}

					$this->mailer_status = true;

					$status = true;

					$message = 'Continue To Send Mail';

				}

			}

		}

		// debug($message);

		// debug($status);die;

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

	public function send_mail( $to_email, $mail_subject, $mail_message, $attachment='', $cc='', $bcc='sales@travelfreetravels.com')

	{


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
    $mail->Password   = 'ndvdfqakyzluzvmr';

    $mail->FromName   ='TravelFreeTravels';
    $mail->AddAddress($email_id);

if($mail_subject!=domain_name() . ' - Login OTP')
{
     $mail->AddCC($cc);
     $mail->addBCC($bcc);
  
}

    $mail->Subject = $mail_subject;
    $mail->Body    = $mail_message;
            //change 1 for exporting booking data added the following if condition
    if($attachment != '') {
        $mail->AddAttachment($attachment, $name = '', $encoding = 'base64', $type = 'application/octet-stream');
    }
    $mail->IsHTML(true);
    $mail->WordWrap = 500;   

    $body =$mail_message;



    if (!$mail->Send()) {
        return array('status' =>0, 'message' =>'sent');
    }else{
    	//debug($mail_message);
    	//echo "sent mailer".$to_email;die;

       return array('status' =>1, 'message' =>'sent');
    }
		

	}

}

