<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * provab
 *
 * Travel Portal Application
 *
 */

class Provab_Mailer {


	/**
	 * Provab Email Class
	 *
	 * Permits email to be sent using Mail, Sendmail, or SMTP.
	 */

	protected $request_url   = '';
	protected $error_message = '';
	protected $api_user      = '';
	protected $api_key       = '';
	protected $api_format    = '';
	protected $http_user     = '';
	protected $http_pass     = '';
	protected $from         = '';


	public $CI;                     //instance of codeigniter super object
	public $mailer_status;         //mailer status which indicates if the mail should be sent or not
	public $mail_configuration;    //mail configurations defined by user

	/**
	 * Constructor - Loads configurations and get ci super object reference
	 */
	public function __construct($data='')
	{         error_reporting(E_ALL);
		if (valid_array($data) == true and intval($data['id']) > 0) {
			$id = intval($data['id']);
		} else {
			$id = GENERAL_EMAIL;
		}

		$this->CI =& get_instance();
		$return_data = $this->CI->custom_db->single_table_records('email_configuration','*' ,array('origin' => $id));
		$this->mail_configuration = $return_data['data'][0];

		// Send Grid
		$this->CI->load->config('sendgrid');
		$params = array();
		$params = config_item('sendgrid_creds');
	
		// initialize parameters
		$this->initialize_sendgrid($params);

	}


	/**
	 * Initialize settings
	 *
	 * @access public
	 * @param array $params Settings parameters
	 */
	public function initialize_sendgrid($params = array())
	{
		if (is_array($params) && ! empty($params))
		{
			foreach($params as $key => $val)
			{
				if (isset($this->$key))
				{
					$this->$key = $val;
				}
			}
		}
	}



	// --------------------------------------------------------------------

	/**
	 * Send a mail
	 *
	 * @access public
	 * @param string $to To address(es). Can be an array to send to multiple locations.
	 * @param string $subject Email subject
	 * @param string $text Text version of email. Required if $html is left empty.
	 * @param string $html HTML version of email. Requred if $text is left empty.
	 * @param string $from From email address from your domain.
	 * @param string $toname Recipient names. Must be an array of equal length if array provided to $to. (optional)
	 * @param string $xsmtpapi JSON headers (optional).
	 * @param string $bcc Email address(es) to blind cc. Can be an array to send to multiple locations (optional).
	 * @param string $fromname Name appended to $from email field (optional).
	 * @param string $replyto Email address used for replies from recipient (optional).
	 * @param string $date RFC 2822 formatted date string to use in email header (optional).
	 * @param string $files An array of file names with full paths to be attached to the email (optional). Must be less than 7MB total.
	 * @param string $headers An array of key/value pairs in JSON format to be placed into the header (optional).
	 * @return bool
	 */
	public function send_mail( $to=NULL, $subject=NULL, $html=NULL, $text=NULL, $files=NULL, $cc=NULL, $bcc=NULL, $from=NULL, $toname=NULL, $xsmtpapi=NULL, $fromname=NULL, $replyto=NULL, $date=NULL)
	{
		$response ['status'] = FAILURE_STATUS;
		$response ['data'] = array();

		
		$status = false;
		$message = 'Please Contact Admin To Setup Mail Configuration';
		$this->mailer_status = false;

		if (is_array($this->mail_configuration) == true and count($this->mail_configuration) > 0) {
			if (intval($this->mail_configuration['status']) == 1 ) {
				if (isset($this->mail_configuration['username']) == true and empty($this->mail_configuration['username']) == false and
				isset($this->mail_configuration['password']) == true and empty($this->mail_configuration['password']) == false
				) {
					$bccEmail = '';
					$ccEmail = '';
					//set mail configurations
					if(!empty($this->mail_configuration['bcc'])){
						$bccEmail = $this->mail_configuration['bcc'];
					}
					if(!empty($this->mail_configuration['cc'])){
						$ccEmail = $this->mail_configuration['cc'];
					}
					
					$from = $this->mail_configuration['from'];
	
					$username = trim($this->mail_configuration['username']);
					$password = trim($this->mail_configuration['password']);
				
					$error_message = array();
					if(!empty($files)){
						$files = explode("/",$files);
						$files = $files[4];
						$fileName = $files;

						$filePath = DOMAIN_PDF_DIR;
					}
					
					// input validation
					if(is_null($to)){
						$error_message[] = "Empty to email address (required).";
					}
					if(is_null($subject)){
						$error_message[] = "Missing subject.";
					}
					if (is_null($text) && is_null($html))
					{
						$error_message[] = "At minimum, either \$text or \$html must be provided.";
					}
					
					
					if(is_null($from)){
						$error_message[] = "Empty from email address (required).";
					}
					if(valid_array($error_message)){
						$response ['data'] = $error_message;
						return $response;
					}
					
					
					// add required data
					$email_data = array(
						//'api_user'  => $this->http_user,
						//'api_key'   => $this->http_pass,
						'to'      => $to,
						'subject' => $subject,
						'from'    => $from,
						'crlf'    => '\r\n',
						'newline'    => '\r\n',
						'charset'    => 'utf-8'
					);

					if ($files != ""){
						$imagedata = file_get_contents($filePath.$files);
					}

					// add optional data
					if ( ! is_null($text))     { $email_data['text']      = $text; }
					if ( ! is_null($html))     { $email_data['html']      = $html; }
					if ( ! is_null($toname))   { $email_data['toname']    = $toname; }
					if ( ! is_null($xsmtpapi)) { $email_data['x-smtpapi'] = $xsmtpapi; }
					if ( ! is_null($bccEmail)) { $email_data['bcc']  	  = $bccEmail; }
					if ( ! is_null($fromname)) { $email_data['fromname']  = $fromname; }
					if ( ! is_null($replyto))  { $email_data['replyto']   = $replyto; }
					if ( ! is_null($date))     { $email_data['date']      = $date; }
					if ($files != "")    { $email_data['files['.$files.']'] = $imagedata;}
					$ccEmail = '';
					if($ccEmail != ""){
						$email_data['cc'] =  ($ccEmail);
					}

					// debug($email_data); exit;
					
					$email_data1 = $this->encode($email_data);
				
					$api_response = $this->process_request($email_data1);
						// badri.provab@gmail.com
					//debug($api_response); exit;
					if(isset($api_response['message'])){
						if($api_response['message']=="error"){
							$response ['data'] = $api_response['errors'];
						}else{
							$response ['status'] = SUCCESS_STATUS;
						}
					}
				}
			}
		}

		return $response;
	}
	
	// returns an array
	function encode($params) {
		$out = array();
		foreach( $params as $key => $value ) {
			if( ! is_array($value) ) {
				$out[$key] = $value;
			} else {
				for( $i=0; $i<count($value); $i++ ) {
					$index = sprintf('%s[%d]', $key, $i);
					$out[$index] = $value[$i];
				}
			}
		}
		return $out;
	}

	function process_request($email_data) {
/*	echo "<br/>request_url->".$this->request_url;
		echo "<br/>api_format->".$this->api_format;
		echo "<br/>api_key->".$this->api_key;
		debug($email_data);die;*/
		$request =  $this->request_url.'api/mail.send.'.$this->api_format;
		
		$headr = array();
		$headr[] = 'Authorization: Bearer '.$this->api_key;

		$session = curl_init($request);
		curl_setopt ($session, CURLOPT_POST, true);
		curl_setopt ($session, CURLOPT_POSTFIELDS, $email_data);
		curl_setopt($session, CURLOPT_HEADER, false);
		curl_setopt($session, CURLOPT_HTTPHEADER,$headr);
		curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
		curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($session);

		$response = json_decode($response, true);
		curl_close($session);
		
		return $response;
	}


}
?>
