<?php
if (! defined ( 'BASEPATH' ))
exit ( 'No direct script access allowed' );
/**
 *
 * @package Provab
 * @subpackage Transaction
 * @author Balu A <balu.provab@gmail.com>
 * @version V1
 */
class Payment_Gateway extends CI_Controller {
	/**
	 *
	 */
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'module_model' );
		$this->load->model('transaction'); 
	}
	
	/**
	 * Redirection to payment gateway
	 * @param string $book_id		Unique string to identify every booking - app_reference
	 * @param number $book_origin	Unique origin of booking
	 */
	public function payment($app_reference,$book_origin,$search_id=0)
	{
	// 	ini_set('display_errors', 1);
	// ini_set('display_startup_errors', 1);
	// error_reporting(E_ALL);
		
		$this->load->model('transaction');
		$PG = $this->config->item('active_payment_gateway');
		load_pg_lib ( $PG );
//debug($PG);exit;
		$pg_record = $this->transaction->read_payment_record($app_reference);
		//debug($pg_record);exit;
		$req_parms = json_decode($pg_record['request_params'],true);
		$meta_course = $req_parms['productinfo'];
		$temp_book_origin = $book_origin;
		$book_id = $app_reference;

		$temp_booking = $this->module_model->unserialize_temp_booking_record($book_id, $temp_book_origin);
	 
		$token_data_raw = $temp_booking['book_attributes']['token'];
		$token_data['token_data']['hotel_price'] = '';
       	$booking_source =  $temp_booking['book_attributes']['token']['booking_source'];

		$pg_record['amount'] = roundoff_number($pg_record['amount']*$pg_record['currency_conversion_rate']);
		
if($_SERVER['REMOTE_ADDR']=="157.49.217.22")
{
	//$pg_record['amount']=1;
}

		if (empty($pg_record) == false and valid_array($pg_record) == true) {
			
			$params = json_decode($pg_record['request_params'], true);
			$pg_initialize_data = array (
				'txnid' => $params['txnid'],
				'pgi_amount' => ceil($pg_record['amount']),
				'firstname' => $params['firstname'],
				'email'=>$params['email'],
				'phone'=>$params['phone'],
				'productinfo'=> $params['productinfo']
			);
		
		} else {
			echo 'Under Construction :p';
			exit;
		}
		
		$payment_gateway_status = $this->config->item('enable_payment_gateway');
		
		if ($payment_gateway_status == true) {	
			//debug($this->pg);exit;	
			$this->pg->initialize ( $pg_initialize_data );
			//debug($app_reference);exit;
			$page_data['pay_data'] = $this->pg->process_payment ($app_reference);
			$page_data['currency'] = $pg_record["currency"];
			
			header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
			header("Cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
			//debug($page_data);exit;
			echo $this->template->isolated_view('payment/'.$PG.'/pay', $page_data);
		
		} else {			
			
		echo "DIRECT BOOKING NOT ALLOWED";exit;
			
		}
	}
	function success($response) { 
	   //exit('success');exit;
	//	debug($response);exit;
	    $response_params = array();
		if(valid_array($response)){
			$pg_status = 'success';
			$response_params = $response;
		}
		
		$this->load->model('transaction');
		$book_id=$response;
		$temp_booking = $this->custom_db->single_table_records ( 'temp_booking', '', array (
				'book_id' => $response['PRN'] 
		) );
	   	
		$pg_record = $this->transaction->read_payment_record($response['PRN']);
		$pro=(json_decode($pg_record['request_params'])); 
		if (empty($pg_record) == false and valid_array($pg_record) == true) {
			//update payment gateway status		
			$res=$this->transaction->update_payment_record_status($response['PRN'], ACCEPTED, $response_params);

			$book_origin = $temp_booking ['data'] ['0'] ['id'];
		//	debug($pro);die;
			$product  =$pro->productinfo;
			$book_id=$book_id['PRN'];
if($product=="")
{
    $product="rewardwallet";
}
			switch ($product) {
				case META_AIRLINE_COURSE : 
				redirect ( base_url () . 'index.php/flight/process_booking/' . $book_id . '/' . $book_origin ); 
					break;
				case META_BUS_COURSE :
					redirect ( base_url () . 'index.php/bus/process_booking/' . $book_id . '/' . $book_origin );
					break;
				case META_ACCOMODATION_COURSE :
					redirect ( base_url () . 'index.php/hotel/process_booking/' . $book_id . '/' . $book_origin );
					break;

				case META_PACKAGE_COURSE :
					redirect ( base_url () . 'index.php/tours/process_booking/' . $book_id . '/' . $book_origin );
					break;
                case META_SIGHTSEEING_COURSE:
					redirect ( base_url () . 'index.php/sightseeing/process_booking/' . $book_id . '/' . $book_origin );
					break;
				case META_TRANSFERV1_COURSE:
					redirect ( base_url () . 'index.php/transferv1/process_booking/' . $book_id . '/' . $book_origin );
					break;
					case META_PRIVATECAR_COURSE:
					redirect ( base_url () . 'index.php/privatecar/process_booking/' . $book_id . '/' . $book_origin );
					break;
				case META_PRIVATETRANSFER_COURSE:
					redirect ( base_url () . 'index.php/activities/process_booking/' . $book_id . '/' . $book_origin );
					break;
				case META_CAR_COURSE:
					redirect ( base_url () . 'index.php/car/process_booking/' . $book_id . '/' . $book_origin );
					break;
				case rewardwallet :
				    redirect ( base_url () . 'index.php/user/process_reward_points/' . $book_id . '/' . $book_origin );
				     break;
				case META_RETIREMENT_COURSE : 
					$temp_booking123 = $this->custom_db->single_table_records ( 'payment_gateway_details', '', array (
							'app_reference' => $book_id 
					) );
					//$invest_data=json_decode($temp_booking123['data'][0]['request_params'], true);
					$user_payment_status['payment_status'] = $temp_booking123['data'][0]['status'];
            		$this->custom_db->update_record('plan_retirement', $user_payment_status, array('app_reference' => $book_id));
            		$invester_data = $this->custom_db->single_table_records ('plan_retirement', '', array (
					 		'app_reference' => $book_id 
					 ) );
					$invest_data['data'] = $invester_data['data'][0]['id'];
					$invest_email = $invester_data['data'][0]['email'];
                    $mail_template= $this->template->isolated_view('general/invester_template', $invest_data);
                    $this->load->library('provab_mailer');
                    $subject = 'Invester Details-'.$_SERVER['HTTP_HOST'];

                    $mail_status = $this->provab_mailer->send_mail($invest_email, $subject, $mail_template);
					redirect ( base_url () . 'index.php/user/invester_process_booking/'); 
					break;
				default : die('transaction/cancels');
					redirect ( base_url().'index.php/transaction/cancel' );
					break;
			}
		}
	}
	
	function success1($book_id,$product) { 

		$this->load->model('transaction');
		$temp_booking = $this->custom_db->single_table_records ( 'temp_booking', '', array (
				'book_id' => $book_id 
		) );
	   
		$pg_record = $this->transaction->read_payment_record($book_id);

		if (empty($pg_record) == false and valid_array($pg_record) == true) {
			//update payment gateway status
			@$response_params = @$_REQUEST;			
			$res=$this->transaction->update_payment_record_status($book_id, ACCEPTED, $response_params);
			
			$book_origin = $temp_booking ['data'] ['0'] ['id'];  
			switch ($product) {
				case META_AIRLINE_COURSE : 
					redirect ( base_url () . 'index.php/flight/process_booking/' . $book_id . '/' . $book_origin ); 
					break;
				case META_BUS_COURSE :
					redirect ( base_url () . 'index.php/bus/process_booking/' . $book_id . '/' . $book_origin );
					break;
				case META_ACCOMODATION_COURSE :
					redirect ( base_url () . 'index.php/hotel/process_booking/' . $book_id . '/' . $book_origin );
					break;

				case META_PACKAGE_COURSE :
					redirect ( base_url () . 'index.php/tours/process_booking/' . $book_id . '/' . $book_origin );
					break;

				default : die('transaction/cancels');
					redirect ( base_url().'index.php/transaction/cancel' );
					break;
			}
		}
	}

	/**
	 *
	 */
	function cancel($response_params) {
		$this->load->model('transaction');
		//$product = $productinfo;
		$book_id = $response['PRN'];
		$temp_booking = $this->custom_db->single_table_records ( 'temp_booking', '', array (
				'book_id' => $book_id 
		) );

		$pg_record = $this->transaction->read_payment_record($book_id);
		$pro=(json_decode($pg_record['request_params'])); 
		if (empty($pg_record) == false and valid_array($pg_record) == true && valid_array ( $temp_booking ['data'] )) {
			$this->transaction->update_payment_record_status($book_id, DECLINED, $response_params);
			$product  =$pro->productinfo;
			$msg = "Payment Unsuccessful, Please try again.";
			if($product=="")
{
    $product="rewardwallet";
    $book_origin="rewardwallet";
}
//echo $product;die;
			switch ($product) {
				case META_AIRLINE_COURSE :
					redirect ( base_url () . 'index.php/flight/exception?op=booking_exception&notification=' . $msg );
					break;
				case META_BUS_COURSE :
					redirect ( base_url () . 'index.php/bus/exception?op=booking_exception&notification=' . $msg );
					break;
				case META_ACCOMODATION_COURSE :
					redirect ( base_url () . 'index.php/hotel/exception?op=booking_exception&notification=' . $msg );
					break;
				case META_SIGHTSEEING_COURSE :
					redirect ( base_url () . 'index.php/sightseeing/exception?op=booking_exception&notification=' . $msg );
					break;
						case rewardwallet :
				    redirect ( base_url () . 'index.php/user/process_reward_points_fail/' . $book_id . '/' . $book_origin );
				     break;
				case META_TRANSFERV1_COURSE :
					redirect ( base_url () . 'index.php/transferv1/exception?op=booking_exception&notification=' . $msg );
					break;
				
					default : 
					redirect ( base_url().'index.php/user/cancel_booking/' );
					break;

			}
		}
	}


	function transaction_log(){
		load_pg_lib('PAYU');
		echo $this->template->isolated_view('payment/PAYU/pay');
	}
	public function ccavRequestHandler()
	 {
	 	
	 	$PG = $this->config->item('active_payment_gateway');
	 	if ($this->config->item('active_payment_system') == "test") {
	 		//echo "ddd";die;
	 		$working_key = '113224069233788E26890C3E247C7790';
			$access_code = 'AVDK02GC59BD85KDDB';
			$urlhit = 'https://test.ccavenue.com/transaction/transaction.do?command=initiateTransaction';
		} else {
			//live

			die;
			$working_key = '';
			$access_code = '';
			$urlhit = 'https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction';
	 	}
	 	$merchant_data = '190920';
	 	foreach ($_POST as $key => $value){
			$merchant_data.=$key.'='.$value.'&';
		}
		$page_data['encrypted_data'] = $this->encrypt($merchant_data,$working_key); // Method for encrypting the data.
		$page_data['access_code'] = $access_code;
		$page_data['urlhit'] = $urlhit;
		
		echo $this->template->isolated_view('payment/'.$PG.'/ccavRequestHandler', $page_data);
	 	
	 }
	 function encrypt($plainText,$key)
	{
		
		$secretKey = $this->hextobin(md5($key));
		$initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
	  	$openMode = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '','cbc', '');
	  	$blockSize = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, 'cbc');
		$plainPad = $this->pkcs5_pad($plainText, $blockSize);
	  	if (mcrypt_generic_init($openMode, $secretKey, $initVector) != -1) 
		{
		      $encryptedText = mcrypt_generic($openMode, $plainPad);
	      	      mcrypt_generic_deinit($openMode);
		      			
		} 
		return bin2hex($encryptedText);
	}
	 function response(){
	 	
	 	$this->custom_db->insert_record('test', array('test' => json_encode($_REQUEST)));
			if ($this->config->item('active_payment_system') == "test") {
	 		$working_key = '113224069233788E26890C3E247C7790';
			$access_code = 'AVDK02GC59BD85KDDB';
			$urlhit = 'https://test.ccavenue.com/transaction/transaction.do?command=initiateTransaction';
			} else {
				die;
				//live
				$working_key = '';
				$access_code = '';
				$urlhit = 'https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction';
		 	}
			
			$encResponse=$_POST["encResp"];			//This is the response sent by the CCAvenue Server
			$rcvdString=$this->decrypt($encResponse,$working_key);		//Crypto Decryption used as per the specified working key.
			
			$order_status="";
			$decryptValues=explode('&', $rcvdString);
			
			$dataSize=sizeof($decryptValues);
			$information=explode('=',$decryptValues[3]);
			$pgad=$pg_amount_deducted=explode('=',$decryptValues[10]);
			$booking_id=explode('=',$decryptValues[27]); 
			$book_id=$booking_id[1];
		
			$final_pg_record = $this->transaction->read_ccavenue_payment_record($book_id);   
			if ($information[1] == "Success" && $pgad[1]==$final_pg_record['pg_compare_amount']) { 
				$productinfo=explode('=',$decryptValues[26]);
				$booking_id=explode('=',$decryptValues[27]);
				
				redirect ( base_url () . 'index.php/payment_gateway/success/' . $booking_id[1] . '/' . $productinfo[1] );
			} 
			else 
			{  
				$tracking_id=explode('=',$decryptValues[1]);
				$information=explode('=',$decryptValues[3]);

				echo $msg="<p>your payment got failed please contact system admin with Tracking Number <span style='color:blue;font-weight:bold'>".$tracking_id[1]."</span></p>";
				die;
			}
	}
	function decrypt($encryptedText,$key)
	{
		$secretKey = $this->hextobin(md5($key));
		$initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
		$encryptedText=$this->hextobin($encryptedText);
	  	$openMode = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '','cbc', '');
		mcrypt_generic_init($openMode, $secretKey, $initVector);
		$decryptedText = mdecrypt_generic($openMode, $encryptedText);
		$decryptedText = rtrim($decryptedText, "\0");
	 	mcrypt_generic_deinit($openMode);
		return $decryptedText;
		
	}
	function hextobin($hexString) 
   	 { 
        	$length = strlen($hexString); 
        	$binString="";   
        	$count=0; 
        	while($count<$length) 
        	{       
        	    $subString =substr($hexString,$count,2);           
        	    $packedString = pack("H*",$subString); 
        	    if ($count==0)
		    {
				$binString=$packedString;
		    } 
        	    
		    else 
		    {
				$binString.=$packedString;
		    } 
        	    
		    $count+=2; 
        	} 
  	        return $binString; 
    	  }
function pkcs5_pad ($plainText, $blockSize)
	{
	    $pad = $blockSize - (strlen($plainText) % $blockSize);
	    return $plainText . str_repeat(chr($pad), $pad);
	}

	public function verify_fonepay(){

		//debug($_GET);exit;
		$prn =  $_GET['PRN'];
		//$prn =  $_GET['PRN'];
		//$bid = $request->BID;
		if(isset($_GET['BID'])){
			$bid = $_GET['BID'];
		}else{
			$bid = ' ';
		}
		//$uid = $request->UID;
		$uid =  $_GET['UID'];
		$amt = $_GET['R_AMT'];
		$PID = 'MEWH';
		$sharedSecretKey = '8b040428a1c2410ba02b2afdc351e752';

		//$dv = hash_hmac('sha512', $PID.',10'.','.$prn.','.$bid.','.$uid, $sharedSecretKey);

		$dv = hash_hmac('sha512', $PID.','.$amt.','.$prn.','.$bid.','.$uid, $sharedSecretKey);

		$requestData = [

    	'PRN' => $prn,

	    'PID' => $PID,

	    'BID' => $bid,

	    'AMT' => $amt, // original payment amount

	    'UID' => $uid,

	    'DV' => hash_hmac('sha512', $PID.','.$_GET['R_AMT'].','.$_GET['PRN'].','.$bid.','.$_GET['UID'], $sharedSecretKey),

];

	//for test server
	if ($this->config->item('active_payment_system') == "test") {
	$verifyLiveUrl = 'https://dev-clientapi.fonepay.com/api/merchantRequest/verificationMerchant';
	}else{
	// for live server
	$verifyLiveUrl = 'https://clientapi.fonepay.com/api/merchantRequest/MerchantVerification';

	}

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $verifyLiveUrl.'?'.http_build_query($requestData));

	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$responseXML = curl_exec($ch);
//echo $verifyLiveUrl.'?'.http_build_query($requestData);
//debug($_GET);die;

	if($_GET['RC']=="successful"){

	    if($_GET['RC']=="successful"){
//echo "Payment Verifcation Completed: ".$response->message;$response->success == 'true'$response = simplexml_load_string($responseXML)
	        $this->success($_GET);
	        //echo "Payment Verifcation Completed: ".$response->message;

	    }else{

	      //  echo "Payment Verifcation Failed: ".$response->message;
	        $this->cancel($_GET);

	    }

	}


	}
	
	
	
public function verify($book_id='', $productinfo='') {
	
		$this->active_payment_system="test";
		if ($this->active_payment_system == 'test') {
			//test
			$keyId = 'rzp_test_dHI6SoUpWPKjGt';
			$keySecret = 'trRxQnTVxtTv8hwgWY70DIWn';
		
		} else {
			//live
			$keyId = 'rzp_live_Hp3TQ7lL15kDfn';
			$keySecret = 'mfL6is3bp2nwGc16k7UwkgeO';
		
		}	
		

		$displayCurrency = 'USD';

		session_start();
		$error = "Payment Failed";
		if (valid_array($_POST) && empty($_POST['txn_id']) === false && empty($_POST['item_number1']) === false)
		{
		
			$payment = $this->custom_db->single_table_records('temp_booking','*',array('book_id' => $_POST['item_number1']));

			if($payment['status'] == SUCCESS_STATUS){
				if($_POST['txn_id']  && $_POST['payment_status'] =="Completed"){
					
		            $this->success($_POST,$productinfo);
		            
				}
				else{
					 $html = "<p>Your payment failed</p>
		             <p>{$error}</p>";
		             $this->cancel($productinfo, $book_id,$_POST);
				}
			}
			else{
				 $html = "<p>Your payment failed</p>
		             <p>{$error}</p>";
		             $this->cancel($productinfo, $book_id,$_POST);
			}
		}
		else
		{
		    $html = "<p>Your payment failed</p>
		             <p>{$error}</p>";
		             $this->cancel($productinfo, $book_id,$_POST);
		}
		echo $html;
	}
	
	public function verify_old($book_id='') {
	    echo "NOT IN USE";exit;
		

		$v['payer_email'] = 'Faisalhhh28@gmail.com';
		$v['payer_id'] = 'BBFTTL8LWFGT8';
		$v['payer_status'] = 'UNVERIFIED';
		$v['first_name'] = 'ajeeba';
		$v['last_name'] = 'musema';
		$v['txn_id'] = '1NF25180EB9839215';
		$v['mc_currency'] = 'USD';
		$v['mc_fee'] = '0.44';
		$v['mc_gross'] = '4.00';
		$v['protection_eligibility'] = 'INELIGIBLE';
		$v['payment_fee'] = '0.44';
		$v['payment_gross'] = '4.00';
		$v['payment_status'] = 'Completed';
		$v['payment_type'] = 'instant';
		$v['handling_amount'] = '0.00';
		$v['shipping'] = '0.00';
		$v['tax'] = '0.00';
		$v['item_name1'] = 'VHCID1420613784';
		$v['item_number1'] = 'FB21-194220-621314';
		$v['quantity1'] = '1';
		$v['mc_gross_1'] = '4.00';
		$v['tax1'] = '0.00';
		$v['num_cart_items'] = '1';
		$v['txn_type'] = 'cart';
		$v['payment_date'] = '2021-06-21T14:12:34Z';
		$v['business'] = 'faisalhhh288@outlook.com';
		$v['receiver_id'] = 'DFMGYCH4VBLJW';
		$v['notify_version'] = 'UNVERSIONED';
		$v['verify_sign'] = 'AMKzGuRNizgN68daFwacqsaLFAYqA2UJehglhDhbyahMGIwKpihLEFZ1';
		$_POST = $v;

		
			$this->CI = &get_instance ();
		$this->active_payment_system = $this->CI->config->item('active_payment_system');
		if ($this->active_payment_system == 'test') {
			$keyId = 'rzp_test_dHI6SoUpWPKjGt';
			$keySecret = 'trRxQnTVxtTv8hwgWY70DIWn';
		} else {
			
			$keyId = 'rzp_live_Hp3TQ7lL15kDfn';
			$keySecret = 'mfL6is3bp2nwGc16k7UwkgeO';
			
		}	
		
		$displayCurrency = 'INR';
		include('b2c/views/template_list/template_v1/payment/Razorpay/Razorpay.php');
		
		$success = false;
		$error = "Payment Failed";
		
		if (empty($_POST['shopping_order_id']) === false && empty($_POST['order_id']) === false)
		{
			$success = true;
		  
		}
		
		if ($success === true)
		{
			
		    $html = "<p>Your payment was successful</p>
		             <p>Payment ID: {$_POST['razorpay_payment_id']}</p>";
		             
		            $request = $_REQUEST;
		            $this->success($request);
		          
		}
		else
		{
		    $html = "<p>Your payment failed</p>
		             <p>{$error}</p>";
		             $this->cancel();
		}

		echo $html;

	}

}
