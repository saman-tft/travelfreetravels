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
	public function payment($app_reference,$book_origin,$mode='',$search_id=0)
	{
	
		
		$this->load->model('transaction');
			
		$PG = $this->config->item('active_payment_gateway');

		if($mode=="connect")
		{
			$PG='Connect';
			
		$this->transaction->update_payment_record_status_new($app_reference,'pending','connect');
		}
		else
		{
			$PG='Fonepay';
			$this->transaction->update_payment_record_status_new($app_reference,'pending','Fonepay');
		}
		//debug($PG);exit;
		load_pg_lib ( $PG );

		$pg_record = $this->transaction->read_payment_record($app_reference);
		
		$req_parms = json_decode($pg_record['request_params'],true);
		$meta_course = $req_parms['productinfo'];
		$temp_book_origin = $book_origin;
		$book_id = $app_reference;

		$temp_booking = $this->module_model->unserialize_temp_booking_record($book_id, $temp_book_origin);
	 	//debug($temp_booking);die;
		$token_data_raw = $temp_booking['book_attributes']['token'];
		$token_data['token_data']['hotel_price'] = '';
       	$booking_source =  $temp_booking['book_attributes']['token']['booking_source'];

		$pg_record['amount'] = roundoff_number($pg_record['amount']*$pg_record['currency_conversion_rate']);
		


		if (empty($pg_record) == false and valid_array($pg_record) == true) {
			
			$params = json_decode($pg_record['request_params'], true);
			$pg_initialize_data = array (
				'txnid' => $params['txnid'],
				'pgi_amount' => ceil($pg_record['amount']),
				'firstname' => $params['firstname'],
				'email'=>$params['email'],
				'phone'=>$params['phone'],
				'productinfo'=> $pg_record['productinfo']
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
			$page_data['appref'] = $app_reference;

			$page_data['productin'] = $pg_record['product_info'];
			
			header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
			header("Cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
			//debug($PG);exit;
			echo $this->template->isolated_view('payment/'.$PG.'/pay', $page_data);
		
		} else {			
			
		echo "DIRECT BOOKING NOT ALLOWED";exit;
			
		}
	}
	function success($response) { 
	
	    $response_params = array();
		if(valid_array($response)){
			if($response['RC'] == 'successful'){
				$pg_status = 'success';
			}else{
				$pg_status = 'N/A';
			}
			
			$response_params = $response;
		}
		
		$this->load->model('transaction');
		$book_id=$response;
		$temp_booking = $this->custom_db->single_table_records ( 'temp_booking', '', array (
				'book_id' => $response['PRN'] 
		) );
	   	
		$pg_record = $this->transaction->read_payment_record($response['PRN']);
		$pro=(json_decode($pg_record['request_params'])); 

	//debug($pg_record);die;
		if (empty($pg_record) == false and valid_array($pg_record) == true) { // yaa changes cha
			//update payment gateway status	
			$create_process_booking_validation = md5("TFT".rand(1111,9999).date("Y-m-d"));	
			$res=$this->transaction->update_payment_record_status($response['PRN'], ACCEPTED,'fonepay',$response['PRN'],$response_params,$create_process_booking_validation);

			$book_origin = $temp_booking ['data'] ['0'] ['id'];
			$product  =$pg_record['product_info'];
			$book_id=$book_id['PRN'];
if($product=="")
{
    $product="rewardwallet";
}
		
			switch ($product) {
				case META_AIRLINE_COURSE : 
				redirect ( base_url () . 'index.php/flight/process_booking/' . $book_id . '/' . $book_origin.'/'.$create_process_booking_validation."/".$response['R_AMT']); 
					break;
				case META_BUS_COURSE :
					redirect ( base_url () . 'index.php/bus/process_booking/' . $book_id . '/' . $book_origin );
					break;
				case META_ACCOMODATION_COURSE :
					redirect ( base_url () . 'index.php/hotel/process_booking/' . $book_id . '/' . $book_origin.'/'.$create_process_booking_validation."/".$response['R_AMT']);
					break;

				case META_PACKAGE_COURSE :
					redirect ( base_url () . 'index.php/tours/process_booking/' . $book_id . '/' . $book_origin .'/'.$create_process_booking_validation."/".$response['R_AMT']);
					break;
                case META_SIGHTSEEING_COURSE:
					redirect ( base_url () . 'index.php/sightseeing/process_booking/' . $book_id . '/' . $book_origin );
					break;
				case META_TRANSFERV1_COURSE:
					redirect ( base_url () . 'index.php/transfer/process_booking/' . $book_id . '/' . $book_origin.'/'.$create_process_booking_validation."/".$response['R_AMT'] );
					break;
					case META_PRIVATECAR_COURSE:
					redirect ( base_url () . 'index.php/privatecar/process_booking/' . $book_id . '/' . $book_origin );
					break;
				case META_PRIVATETRANSFER_COURSE:
					redirect ( base_url () . 'index.php/activities/process_booking/' . $book_id . '/' . $book_origin.'/'.$create_process_booking_validation."/".$response['R_AMT']);
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
				default : 
                 $this->transaction->update_payment_record_status($response['PRN'],'failed','fonepay',$response['PRN'],$response_params);
				die('transaction/cancels');
					redirect ( base_url().'index.php/transaction/cancel' );
					break;
			}
		
		}else{
			$msg = "Wrong access";
			redirect ( base_url () . 'index.php/flight/exception?op=booking_exception&notification=' . $msg );
			
		}
	}
	public function pgsuccess($txid='')
	{
	//debug($_REQUEST);die;
		$txid=$_REQUEST['TXNID'];
		$_GET['TXNID']=$txid;
//echo $txid;die;
		//debug($_REQUEST['TXNID']);die;

	/*$merchant_id=NCHL_MERCHANT_ID;
$appid=NCHL_APP_ID;
$appname=NCHL_APP_NAME;*/
	$merchant_id = 199;
 	$appid = "MER-199-APP-4";
 	$appname = "Travel Free Travels";
	$txid=$_GET['TXNID'];
		$temp_booking = $this->custom_db->single_table_records ( 'temp_booking', '', array (
				'book_id' =>$txid 
		) );
	   //	debug($temp_booking);die;
		$pg_record = $this->transaction->read_payment_record($txid);
//debug($pg_record);die;
		if($pg_record['product_info']==META_AIRLINE_COURSE)
		{
			$pg_record['amount']=round($pg_record['amount']*$pg_record['currency_conversion_rate']);
		}
		$amount=5*100;
		//$amount=$pg_record['amount']*100;// here amount need to change 
		$string = "MERCHANTID=$merchant_id,APPID=$appid,REFERENCEID=$txid,TXNAMT=$amount";
		//echo $string;die;
		


		$this->createtoken($string,$txid);
		die("testing here ");




		$data = file_get_contents('https://travelfreetravels.com/TRAVELFREETRAVELS.pfx');
$certPassword = 'your password';
$result=openssl_pkcs12_read($data,$cert_info,'3567');
		
		if ($result) {
	 $private_key = openssl_pkey_get_private($cert_info['pkey']);
//debug($cert_info);die;
	 if (openssl_sign($string, $signature, $private_key, 'sha256WithRSAEncryption')) {
	//	 echo "sdfsdf";die;
            $hash = base64_encode($signature);
            openssl_free_key($private_key);
        }
//	echo "tsretser";
	//print_r($hash);die;
	$token=$hash;
	
 // print_r( $certs );
}
		$this->load->library('Api_Interface');
		
	//	$authresponse=$this->api_interface->get_json_response_pay(NCHL_VALIDATION_URL,json_encode($authdata));
		 $data=array(
			        'appId' =>$appid,
                    'txnAmt' => $amount,
                    'referenceId' =>$txid,
                    'merchantId' =>$merchant_id,
                    'token' => $token,
                   );
		//debu
	//	debug($data);die;
		$response=$this->api_interface->get_json_response_pay(NCHL_VALIDATION_URL,json_encode($data));
		//	debug($response);die;
		if($response['status']=="SUCCESS")
		{
				$confirmresponse=$this->api_interface->get_json_response_pay(NCHL_TRANSACTION_DETAIL_URL,json_encode($data));
			    if($confirmresponse['status']=="SUCCESS")
				{
					$res=$this->transaction->update_payment_record_status($confirmresponse['referenceId'], ACCEPTED,'connect',$confirmresponse['txnId'],$response_params);
					  	$temp_booking = $this->custom_db->single_table_records ( 'temp_booking', '', array (
				         'book_id' => $confirmresponse['referenceId'] 
		                  ));
					   $book_origin = $temp_booking ['data'] ['0'] ['id'];
			///$product  =$pro->productinfo;
			          $book_id=$confirmresponse['referenceId'];
					  $pg_record = $this->transaction->read_payment_record($confirmresponse['referenceId']);
		              $pro=(json_decode($pg_record['request_params'])); 
					if($product=="")
{
    $product="rewardwallet";
}
				$product  =$pg_record['product_info'];
				//	echo META_AIRLINE_COURSE;
				//	echo META_TRANSFERV1_COURSE;
					//echo $book_id;
				//	echo $book_origin;
					if($product=="PROVAB_TRANSFER_SOURCE_CRS")
					{
						$product=META_TRANSFERV1_COURSE;
					}
		//	debug($product);die;
					//	debug($product);die;
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
					redirect ( base_url () . 'index.php/transfer/secure_booking/' . $book_id . '/' . $book_origin );
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
				default : 
$this->transaction->update_payment_record_status($txid,'failed','connect',$txid,$response_params);
				die('transaction/cancels');
					redirect ( base_url().'index.php/transaction/cancel' );
					break;
			}
				}
			else
			{
				echo "test2";die;
					redirect ( base_url().'index.php/transaction/cancel' );
			}
			
		}
		else
		{
			echo "test4";die;
		   	redirect ( base_url().'index.php/transaction/cancel' );
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
if($_SERVER['REMOTE_ADDR']=="117.213.100.245")
	    	{
	    	//	debug($_GET);die;
	    	}
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
	
	public function verify_fonepaycodewaster(){
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
	$verifyLiveUrl = 'https://clientapi.fonepay.com/api/merchantRequest/verificationMerchant';

	}

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $verifyLiveUrl.'?'.http_build_query($requestData));

	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$responseXML = curl_exec($ch);


	if($response = simplexml_load_string($responseXML)){

	    if($response->success == 'true'){
//echo "Payment Verifcation Completed: ".$response->message;
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

	public function pgcancel(){
		//debug($_REQUEST['TXNID']);die;
		$this->load->model('transaction');
		//$product = $productinfo;
		$book_id = $_REQUEST['TXNID'];
		$temp_booking = $this->custom_db->single_table_records ( 'temp_booking', '', array (
				'book_id' => $book_id 
		) );
		$response_params='';
		$pg_record = $this->transaction->read_payment_record($book_id);

		$pro=(json_decode($pg_record['request_params'],1)); 
		//debug($pg_record['product_info']);die;
		if (empty($pg_record) == false and valid_array($pg_record) == true && valid_array ( $temp_booking ['data'] )) {
			//echo "dfdfdf";die;
			$this->transaction->update_payment_record_status($book_id, DECLINED,'', $response_params);
			//echo "hihih";die;
			$product  =$pg_record['product_info'];
			//echo $product ;die;
			$msg = "Payment Unsuccessful, Please try again.";
			if($product=="")
{
    $product="rewardwallet";
    $book_origin="rewardwallet"; 
}
//echo $product;die;
			switch ($product) {
				case META_AIRLINE_COURSE :
				//echo "test";die;
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

	


	public function createtoken($string , $appref){
		
		  ini_set('display_errors', '1');
    date_default_timezone_set("Asia/Kathmandu");	
    function generateHash($string) {
        // Try to locate certificate file
        if (!$cert_store = file_get_contents("https://travelfreetravels.com/b2c/views/template_list/template_v1/payment/Connect/TRAVELFREE.pfx")) {
        	echo "Error: Unable to read the cert file\n";
        	exit;
        }
        
        // Try to read certificate file
        if (openssl_pkcs12_read($cert_store, $cert_info, "TravelFRee@1")) {
        	if($private_key = openssl_pkey_get_private($cert_info['pkey'])){
        		$array = openssl_pkey_get_details($private_key);
        	    // print_r($array);
        	}
        } else {
        	echo "Error: Unable to read the cert store.\n";
        	exit;
        }
        $hash = "";
        if(openssl_sign($string, $signature , $private_key, "sha256WithRSAEncryption")){
	        $hash = base64_encode($signature);
	        openssl_free_key($private_key);
        } else {
            echo "Error: Unable openssl_sign";
            exit;
        }    
        return $hash;
    }
    
    $string = $string;

    $token = generateHash($string);

    	$payment_resp = $this->custom_db->single_table_records ( 'payment_gateway_details', '', array (
				'app_reference' => $appref 
		) );
		$amount=$payment_resp['data'][0]['amount']*100; //we need to pass in the paise , not in rs 
		//$amount=500;//need to remove

    $curl = curl_init();
$MID=IPSCONNCT_MID;
$appId=IPSCONNCT_APPID;
$url=IPSCONNCT_VALIDATION_URL;
$postdata='{
"merchantId": "'.$MID.'",
"appId": "'.$appId.'",
"referenceId": "'. $appref.'",
"txnAmt": "'.$amount.'",
"token":
"'. $token.'"
}'; 
//debug($postdata);die;
$header=array(
    'Content-Type: application/json',
    'Authorization: Basic TUVSLTE5OS1BUFAtNDpUUmF2ZWxAMTEyMg==', // here also need to chnage for the base  Authorization
    'Cookie: JSESSIONID=A9ECEF7A9D4C179B18578E64E9A98FBF.tc12'
  );
curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_SSL_VERIFYHOST=>0,
  CURLOPT_SSL_VERIFYPEER=>0,
  CURLOPT_SSL_VERIFYPEER=>false,
  CURLOPT_SSL_VERIFYHOST=>true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>$postdata,
  CURLOPT_HTTPHEADER => $header,
));

$response = curl_exec($curl);
//debug($response);die;
$validated_response=json_decode($response,true);
curl_close($curl);
//$validated_response['status']=='SUCCESS';
//$validated_response['statusDesc']=='SUCCESS';
	//debug($validated_response);die;
	if($validated_response['status']=='SUCCESS' AND $validated_response['statusDesc']=='TRANSACTION SUCCESSFUL'){
		$this->success_ips_connect($appref,$response);
	}else{
		$this->cancel_ips_connect($appref,$response);
	}

debug($response );die;
}

public function success_ips_connect($appref,$response){

	//echo "please contact admin for booking processs";die;

	/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(0);*/
	//echo $appref;
	//echo $response;
$resp_amount=json_decode($response,true);
	$book_id=$appref;
	$res_amount=$resp_amount['txnAmt'];


	$this->load->model('transaction');
		$temp_booking = $this->custom_db->single_table_records ( 'temp_booking', '', array (
				'book_id' => $book_id 
		) );
		//debug($temp_booking);die;
	   
		$pg_record = $this->transaction->read_payment_record($book_id);
		$product_information=$pg_record['product_info'];
		//debug($pg_record['product_info']);die;
		//echo "success functinality";die;
		if (empty($pg_record) == false and valid_array($pg_record) == true) {

			$create_process_booking_validation = md5("TFT".rand(1111,9999).date("Y-m-d"));	
			//$res=$this->transaction->update_payment_record_status($book_id, ACCEPTED,'connect',$response['PRN'],$response_params,$create_process_booking_validation);
			//echo "dfdfdfdf";die;
			//update payment gateway status
			$_REQUEST=$response;
			@$response_params = @$_REQUEST;			
			//echo $book_id;die;
			$res=$this->transaction->update_payment_record_status($book_id, ACCEPTED,$payment_mode='connect', $response_params,'',$create_process_booking_validation);
			//debug($res);die;
			$book_origin = $temp_booking ['data'] ['0'] ['id'];  
			//echo $book_origin;
			
			 $product=$product_information;
			
			switch ($product) {
				case META_AIRLINE_COURSE : 
				//echo base_url () . 'index.php/flight/process_booking/' . $book_id . '/' . $book_origin.'/'.$create_process_booking_validation.'/'.$res_amount;
				//echo 'please contact admin for the booking process';die;
					redirect ( base_url () . 'index.php/flight/process_booking/' . $book_id . '/' . $book_origin.'/'.$create_process_booking_validation.'/'.$res_amount); 
					break;
				case META_BUS_COURSE :
				echo 'please contact admin for the booking process';die;
					redirect ( base_url () . 'index.php/bus/process_booking/' . $book_id . '/' . $book_origin );
					break;
				case META_ACCOMODATION_COURSE :
				//echo 'please contact admin for the booking process';die;
					redirect ( base_url () . 'index.php/hotel/process_booking/' . $book_id . '/' . $book_origin );
					break;
				
				case META_PACKAGE_COURSE :
				echo 'please contact admin for the booking process';die;
					redirect ( base_url () . 'index.php/tours/process_booking/' . $book_id . '/' . $book_origin );
					break;
				echo 'please contact admin for the booking process';die;
				default : die('transaction/cancels');
					redirect ( base_url().'index.php/transaction/cancel' );
					break;
			}
		}

}
public function cancel_ips_connect(){
	echo 'cancel functionality';die;
}

}
