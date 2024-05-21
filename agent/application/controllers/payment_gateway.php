<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
/**
 *
 * @package Provab
 * @subpackage Transaction
 * @author Balu A <balu.provab@gmail.com>
 * @version V1
 */
class Payment_Gateway extends CI_Controller
{
	/**
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		// $this->output->enable_profiler(TRUE);
		$this->load->model('module_model');
		$this->load->model('transaction');
		$this->load->model('transaction_model');
	}

	/**
	 * Redirection to payment gateway
	 * @param string $book_id		Unique string to identify every booking - app_reference
	 * @param number $book_origin	Unique origin of booking
	 */
	public function payment($book_id, $book_origin)
	{


		$PG = $this->config->item('agent_pay');
		load_pg_lib($PG);

		$pg_record = $this->transaction->read_payment_record($book_id);
		// debug($pg_record);exit;
		$temp_booking = $this->custom_db->single_table_records('temp_booking', '', array(
			'book_id' => $book_id
		));
		$book_origin = $temp_booking['data']['0']['id'];

		if (empty($pg_record) == false and valid_array($pg_record) == true) {
			$params = json_decode($pg_record['request_params'], true);
			/*
			$pg_initialize_data = array (
				'txnid' => $params['txnid'],
				'pgi_amount' => ceil($pg_record['amount']),
				'firstname' => $params['firstname'],
				'email'=>$params['email'],
				'phone'=>$params['phone'],
				'productinfo'=> $params['productinfo']
			);*/
		} else {
			echo 'Under Construction :p';
			exit;
		}
		//defined in provab_config.php
		$payment_gateway_status = $this->config->item('enable_payment_gateway');
		if ($payment_gateway_status == true) {
			/*$this->pg->initialize ( $pg_initialize_data );
			$page_data['pay_data'] = $this->pg->process_payment ();
			//Not to show cache data in browser
			header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
			header("Cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
			echo $this->template->isolated_view('payment/'.$PG.'/pay', $page_data);*/
		} else {
			//directly going to process booking
			$this->redirect_booking($params['productinfo'], $params['txnid'], $book_origin);
		}
	}

	/**
	 *
	 */
	function success()
	{
		$this->load->model('transaction');
		$product = $_REQUEST['productinfo'];
		$book_id = $_REQUEST['txnid'];
		$temp_booking = $this->custom_db->single_table_records('temp_booking', '', array(
			'book_id' => $book_id
		));
		$pg_status = $_REQUEST['status'];
		$pg_record = $this->transaction->read_payment_record($book_id);
		if ($pg_status == 'success' and empty($pg_record) == false and valid_array($pg_record) == true && valid_array($temp_booking['data'])) {
			//update payment gateway status
			$response_params = $_REQUEST;
			$this->transaction->update_payment_record_status($book_id, ACCEPTED, $response_params);
			$book_origin = $temp_booking['data']['0']['id'];
			$this->redirect_booking($product, $book_id, $book_origin);
		}
	}

	private function redirect_booking($product, $book_id, $book_origin)
	{
		switch ($product) {
			case META_AIRLINE_COURSE:
				redirect(base_url() . 'index.php/flight/process_booking/' . $book_id . '/' . $book_origin);
				break;
			case META_BUS_COURSE:
				redirect(base_url() . 'index.php/bus/process_booking/' . $book_id . '/' . $book_origin);
				break;
			case META_ACCOMODATION_COURSE:
				redirect(base_url() . 'index.php/hotel/process_booking/' . $book_id . '/' . $book_origin);
				break;
			case rewardwallet:
				$book_origin = "rewardwallet";
				$this->transaction->update_payment_record_status($book_id, 'success', $book_origin);
				redirect(base_url() . 'index.php/loyalty_program/process_reward_points/' . $book_id . '/' . $book_origin);
				break;
			case META_CAR_COURSE:
				redirect(base_url() . 'index.php/car/process_booking/' . $book_id . '/' . $book_origin);
				break;
			default:
				redirect(base_url() . 'index.php/transaction/cancel');
				break;
		}
	}

	/**
	 *
	 */
	function cancel()
	{
		$this->load->model('transaction');
		$product = $_REQUEST['productinfo'];
		$book_id = $_REQUEST['txnid'];
		$temp_booking = $this->custom_db->single_table_records('temp_booking', '', array(
			'book_id' => $book_id
		));
		$pg_record = $this->transaction->read_payment_record($book_id);
		if (empty($pg_record) == false and valid_array($pg_record) == true && valid_array($temp_booking['data'])) {
			$response_params = $_REQUEST;
			$this->transaction->update_payment_record_status($book_id, DECLINED, $response_params);
			$msg = "Payment Unsuccessful, Please try again.";
			switch ($product) {
				case META_AIRLINE_COURSE:
					redirect(base_url() . 'index.php/flight/exception?op=booking_exception&notification=' . $msg);
					break;
				case META_BUS_COURSE:
					redirect(base_url() . 'index.php/bus/exception?op=booking_exception&notification=' . $msg);
					break;
				case META_ACCOMODATION_COURSE:
					redirect(base_url() . 'index.php/hotel/exception?op=booking_exception&notification=' . $msg);
					break;
			}
		}
	}


	function transaction_log()
	{
		load_pg_lib('PAYU');
		echo $this->template->isolated_view('payment/PAYU/pay');
	}


	function topup($amount = 0, $id = 0, $validationId = '')
	{

		if ($amount != 0 && $id != 0 && $validationId != '') {
			// debug($id);die;
			$condition = array('id' => $id);

			$validationIdQueryObject = $this->custom_db->single_table_records('offline_payment', 'refernce_code', $condition);

			if (is_array($validationIdQueryObject) && $validationIdQueryObject['status'] === 1) {

				$dbValidationId = $validationIdQueryObject['data'][0]['refernce_code'];

				if ($dbValidationId === $validationId) {
					$updateData['amount'] = $amount;
					$updateCondition = $condition;
					$updateStatus = $this->transaction_model->update_validation($updateData, $updateCondition);
					$page_data['data']['amount'] = $amount;
					$page_data['data']['id'] = $id;
					$page_data['data']['validation_id'] = $validationId;

					$this->template->view('payment/make_secure_payment', $page_data);
				} else {
					throw new Exception('Validation Error');
				}
			} else {
				throw new Exception('The validation id doesnt exist in the received origin');
			}
		} else {
			//unauthorized
			throw new Exception("Unauthorized access");
		}
	}


function processTopup()
	{
		
		$post_params_input = $this->input->post();

		if ($post_params_input['proceed-form-data'] != '') {
			$postParams = (array)json_decode($post_params_input['proceed-form-data']);

			$receivedAmount = $postParams['pg_convenience'] + $postParams['amount'];
			if (count($postParams) > 0 && isset($postParams['id'])) {
				$condition = array('id' => $postParams['id'], 'refernce_code' => $postParams['validation_id']);
				$validationIdQueryObject = $this->custom_db->single_table_records('offline_payment', array('refernce_code', 'amount'), $condition);


				if (is_array($validationIdQueryObject) && $validationIdQueryObject['status'] === 1) {
					$agentEmail  = $this->session->userdata('username') ?? '';
					$validationId = $postParams['validation_id'];
					$updateData['amount'] = $postParams['amount'];
					$remarks = array();
					$remarks['receivable_amount'] = $receivedAmount;
					$remarks['pg_convenience'] = $postParams['pg_convenience'];
					$remarks['topupStatus'] = TOPUP_INPROGRESS;
					$updateData['remarks'] = json_encode($remarks);
					$updateCondition = $condition;
					$updateStatus = $this->transaction_model->update_validation($updateData, $updateCondition);
					if ($updateStatus === 1) {
						switch ($postParams['payment_method']) {
							case PAY_NOW:
								$PG = 'Fonepay';
								break;
							case PAY_AT_BANK:
								$PG = 'Connect';
								break;
							case PAY_WITH_ESEWA:
								$PG = 'Esewa';
								break;
							case PAY_WITH_KHALTI:
								$PG = 'Khalti';
								break;
							case PAY_WITH_NICA:
								$PG = 'Nica';
								break;
							default:
								redirect(base_url('/'));
						}
						// debug("Reached");die;
						load_pg_lib($PG);
						$amount = roundoff_number($postParams['amount']);
						$userCondition['email'] = provab_encrypt($agentEmail);
						$userCondition['status'] = ACTIVE;
						$userCondition['user_type'] = B2B_USER;
						$user_record = $this->custom_db->single_table_records('user', 'email, password, user_id, first_name, last_name, phone, agency_name', $userCondition);
						
                        $productInfo = "TOPUP";
						if ($user_record['status'] == true and valid_array($user_record['data']) == true) {
						    
							$userInfo = $user_record['data'][0];
							if($postParams['payment_method'] ==PAY_WITH_NICA ){
							    $userInfo['first_name'] = $userInfo['first_name'] . ' '. $userInfo['last_name'];
							}
							$pg_initialize_data = array(
								'txnid' => $validationId,
								'pgi_amount' => ceil($amount),
								'firstname' => $userInfo['first_name'],
								'email' => $agentEmail,
								'phone' => $userInfo['phone'],
								'productinfo' => $productInfo
							);
							$updateData = array();
							$updateData['name'] = $userInfo['first_name'] . ' '. $userInfo['last_name'];
                            if($postParams['payment_method'] ==PAY_WITH_NICA){
                                $updateData['name'] = $userInfo['first_name']; 
                            }
							$updateData['email'] = provab_encrypt($agentEmail);
							$updateData['company_name'] = $userInfo['agency_name'];
							$updateData['phone'] = $userInfo['phone'];
							$updateData['created_date'] = date("Y-m-d H:i:s");
							$remarks['payment_status'] = PENDING;
							$remarks['payment_method'] = $postParams['payment_method'];
							$updateData['remarks'] = json_encode($remarks);
			
							$updateStatus = $this->transaction_model->update_validation($updateData, $condition);
							if ($updateStatus === 1) {
								$this->pg->initialize($pg_initialize_data);
								$page_data['pay_data'] = $this->pg->process_payment($validationId);
								$page_data['currency'] = 'NPR';
								$page_data['appref'] = $validationId;
								$page_data['productinfo'] = $productInfo;

								header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
								header("Cache-Control: post-check=0, pre-check=0", false);
								echo  $this->template->isolated_view('payment/' . $PG . '/pay', $page_data);
							}else{
                                throw new Exception("Updation and initiation of payment failed");
                            }
						}else{
                            throw new Exception("User not found");
                        }
					}else{
                        throw new Exception("Server Update Error.");
                    }
				}else{
                    throw new Exception("No such record exists.");
                }
			}
		} else {
			throw new Exception("Unauthorized Access. Your IP has been recorded.");
			//unauthorized access
		}
	}


	public function verify_fonepay()
	{

		$prn =  $_GET['PRN'];
		//$prn =  $_GET['PRN'];
		//$bid = $request->BID;
		if (isset($_GET['BID'])) {
			$bid = $_GET['BID'];
		} else {
			$bid = ' ';
		}
		//$uid = $request->UID;
		$uid =  $_GET['UID'];
		$amt = $_GET['R_AMT'];
		$PID = 'MEWH';
		$sharedSecretKey = '8b040428a1c2410ba02b2afdc351e752';

		//$dv = hash_hmac('sha512', $PID.',10'.','.$prn.','.$bid.','.$uid, $sharedSecretKey);

		$dv = hash_hmac('sha512', $PID . ',' . $amt . ',' . $prn . ',' . $bid . ',' . $uid, $sharedSecretKey);

		$requestData = [

			'PRN' => $prn,

			'PID' => $PID,

			'BID' => $bid,

			'AMT' => $amt, // original payment amount

			'UID' => $uid,

			'DV' => hash_hmac('sha512', $PID . ',' . $_GET['R_AMT'] . ',' . $_GET['PRN'] . ',' . $bid . ',' . $_GET['UID'], $sharedSecretKey),

		];


		$verifyLiveUrl = 'https://clientapi.fonepay.com/api/merchantRequest/MerchantVerification';

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $verifyLiveUrl . '?' . http_build_query($requestData));

		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$responseXML = curl_exec($ch);
		if ($_GET['RC'] == "successful") {
			$responseData = $_GET;
			$responseData['payment_method'] = PAY_NOW;
			$this->successTopup($responseData);
			//echo "Payment Verifcation Completed: ".$response->message;

		} else {

			//  echo "Payment Verifcation Failed: ".$response->message;
			// $this->cancel($_GET);  removed this and added the code below
			$responseData = $_GET;
			$responseData['payment_method'] = PAY_NOW;
			//  echo "Payment Verifcation Failed: ".$response->message;
			$this->cancelTopup($responseData);
		}
	}

	//added esewa and khalti's verify function
	public function verify_esewa()
{        if (!empty($_GET)) {
            $responseData = (array)(json_decode(base64_decode($_GET['data'])));
            $transaction_code = $responseData['transaction_code'];
            $status = $responseData['status'];
            $signed_field_names = $responseData['signed_field_names'];
            $total_amount = str_replace(',', '', $responseData['total_amount']);
            $transaction_uuid = $responseData['transaction_uuid'];
		

            $product_code = $responseData['product_code'];
            // $secretKey = '8gBm/:&EnhH.1/q';
            $secretKey = 'AAAAAABdDhUYHAVXWRIWCBccEV0xMTUjJw==';
            $message = "transaction_code=$transaction_code,status=$status,total_amount=$total_amount,transaction_uuid=$transaction_uuid,product_code=$product_code,signed_field_names=$signed_field_names";
            $s = hash_hmac('sha256', $message, $secretKey, true);
            $sign = base64_encode($s);
            if ($responseData['status'] == 'COMPLETE' && $responseData['signature'] == $sign) {
                $url = "https://epay.esewa.com.np/api/epay/transaction/status/";
                $data = [
                    'product_code' => $product_code,
                    'total_amount' => $total_amount,
                    'transaction_uuid' => $transaction_uuid,
                ];
				$url = $url . '?' . http_build_query($data);
				$curl = curl_init($url);
				curl_setopt($curl, CURLOPT_HTTPGET, true);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				$response = curl_exec($curl);
				curl_close($curl);
				if (curl_errno($curl)) {
					echo 'cURL error: ' . curl_error($curl);
				}

				$response = (array)(json_decode($response, true));
				$response['PRN'] = $response['transaction_uuid'];
				$response['R_AMT'] = $response['total_amount'];
				$response['payment_method'] = PAY_WITH_ESEWA;
				switch ($response['status']) {
					case "COMPLETE":
						$response['RC'] = 'COMPLETE';
						$response['status'] = ACCEPTED;
						$this->successTopup($response);
						break;
					case "PENDING":
						$response['RC'] = 'PENDING';
						$response['status'] = PENDING;
				$this->cancelTopup($response);

						break;
					case "FULL_REFUND":
						$response['RC'] = 'FULL_REFUND';
						$response['status'] = FULL_REFUND;
				$this->cancelTopup($response);

						break;
					case "PARTIAL_REFUND":
						$response['RC'] = 'PARTIAL_REFUND';
						$response['status'] = PARTIAL_REFUND;
				$this->cancelTopup($response);

						break;
					case "AMBIGIOUS":
						$response['RC'] = 'AMBIGUOUS';
						$response['status'] = AMBIGUOUS;
				$this->cancelTopup($response);

						break;
					case "NOT_FOUND":
						$response['RC'] = 'NOT_FOUND';
						$response['status'] = NOT_FOUND;
				$this->cancelTopup($response);

						break;
					default:
						if ($response['code'] == 0) {
							$response['RC'] = $response['error_message'];
						} else {
							$response['RC'] = NULL;
						}
						$response['status'] = DECLINED;
				$this->cancelTopup($response);

				}
				// debug($response);die('here');
			}
		} else {
			$response['payment_method'] = PAY_WITH_ESEWA;

			$response['PRN'] = $GLOBALS['CI']->session->userdata('esewa_prn');
			$GLOBALS['CI']->session->unset_userdata('esewa_prn');


			$response['RC'] = 'cancelled/time_out';


			$this->cancelTopup($response);
		}
	}

	//changes added a new verify_nica function 


	public function verify_nica()
	{
		$response = array();
		$response['payment_method'] = PAY_WITH_NICA;
		if (!empty($_POST)) {
			$responseData = $_POST;
			$response['PRN'] = $responseData['req_transaction_uuid'];
			$response['transaction_id'] = $responseData['transaction_id'];
			$response['R_AMT'] = $responseData['req_amount'];
			$transaction_id = $responseData['transaction_id'];
			$decision = $responseData['decision'];
			$req_access_key = $responseData['req_access_key'];
			$req_profile_id = $responseData['req_profile_id'];
			$req_transaction_uuid = $responseData['req_transaction_uuid'];
			$req_transaction_type = $responseData['req_transaction_type'];
			$req_reference_number = $responseData['req_reference_number'];
			$req_amount = $responseData['req_amount'];
			$req_currency = $responseData['req_currency'];
			$req_locale = $responseData['req_locale'];
			$req_payment_method = $responseData['req_payment_method'];
			$req_bill_to_forename = $responseData['req_bill_to_forename'];
			$req_bill_to_surname = $responseData['req_bill_to_surname'];
			$req_bill_to_email = $responseData['req_bill_to_email'];
			$req_bill_to_phone = $responseData['req_bill_to_phone'];
			$req_bill_to_address_line1 = $responseData['req_bill_to_address_line1'];
			$req_bill_to_address_city = $responseData['req_bill_to_address_city'];
			$req_bill_to_address_state = $responseData['req_bill_to_address_state'];
			$req_bill_to_address_country = $responseData['req_bill_to_address_country'];
			$req_bill_to_address_postal_code = $responseData['req_bill_to_address_postal_code'];
			$req_card_number = $responseData['req_card_number'];
			$req_card_type = $responseData['req_card_type'];
			$req_card_type_selection_indicator = $responseData['req_card_type_selection_indicator'];
			$req_card_expiry_date = $responseData['req_card_expiry_date'];
			$card_type_name = $responseData['card_type_name'];

			// live
			$req_payer_authentication_acs_window_size = $responseData['req_payer_authentication_acs_window_size'];
			$req_payer_authentication_indicator = $responseData['req_payer_authentication_indicator'];
			$req_payer_authentication_merchant_name = $responseData['req_payer_authentication_merchant_name'];
			// live_end

			// test
// 			$message = $responseData['message'];
// 			$reason_code = $responseData['reason_code'];
// 			$request_token = $responseData['request_token'];
			// test end

			$payer_authentication_reason_code = $responseData['payer_authentication_reason_code'];

			// live
			$payer_authentication_specification_version = $responseData['payer_authentication_specification_version'];
			$payer_authentication_transaction_id = $responseData['payer_authentication_transaction_id'];
			$payer_authentication_enroll_veres_enrolled = $responseData['payer_authentication_enroll_veres_enrolled'];
			$payer_authentication_acs_transaction_id = $responseData['payer_authentication_acs_transaction_id'];
			$message = $responseData['message'];
			$reason_code = $responseData['reason_code'];
			$auth_avs_code = $responseData['auth_avs_code'];
			$auth_avs_code_raw = $responseData['auth_avs_code_raw'];
			$auth_response = $responseData['auth_response'];
			$auth_amount = $responseData['auth_amount'];
			$auth_code = $responseData['auth_code'];
			$auth_cavv_result = $responseData['auth_cavv_result'];
			$auth_cavv_result_raw = $responseData['auth_cavv_result_raw'];
			$auth_cv_result = $responseData['auth_cv_result'];
			$auth_cv_result_raw = $responseData['auth_cv_result_raw'];
			$auth_trans_ref_no = $responseData['auth_trans_ref_no'];
			$auth_time = $responseData['auth_time'];
			$request_token = $responseData['request_token'];
			$auth_reconciliation_reference_number = $responseData['auth_reconciliation_reference_number'];
			$bill_trans_ref_no = $responseData['bill_trans_ref_no'];
			$payer_authentication_validate_result = $responseData['payer_authentication_validate_result'];
			$payer_authentication_cavv = $responseData['payer_authentication_cavv'];
			$payer_authentication_validate_e_commerce_indicator = $responseData['payer_authentication_validate_e_commerce_indicator'];
			$payer_authentication_eci = $responseData['payer_authentication_eci'];
			$payer_authentication_pares_status = $responseData['payer_authentication_pares_status'];
			$payer_authentication_xid = $responseData['payer_authentication_xid'];
			$payment_account_reference = $responseData['payment_account_reference'];
			// live_end

			$signed_field_names = $responseData['signed_field_names'];
			$signed_date_time = $responseData['signed_date_time'];

			$signed_field_values = [
				$transaction_id,
				$decision,
				$req_access_key,
				$req_profile_id,
				$req_transaction_uuid,
				$req_transaction_type,
				$req_reference_number,
				$req_amount,
				$req_currency,
				$req_locale,
				$req_payment_method,
				$req_bill_to_forename,
				$req_bill_to_surname,
				$req_bill_to_email,
				$req_bill_to_phone,
				$req_bill_to_address_line1,
				$req_bill_to_address_city,
				$req_bill_to_address_state,
				$req_bill_to_address_country,
				$req_bill_to_address_postal_code,
				$req_card_number,
				$req_card_type,
				$req_card_type_selection_indicator,
				$req_card_expiry_date,
				$card_type_name,
				// live
				$req_payer_authentication_acs_window_size,
				$req_payer_authentication_indicator,
				$req_payer_authentication_merchant_name,
				// live_end

				// test
				// $message,
				// $reason_code,
				// $request_token,
				// test end

				$payer_authentication_reason_code,

				// live
				$payer_authentication_specification_version,
				$payer_authentication_transaction_id,
				$payer_authentication_enroll_veres_enrolled,
				$payer_authentication_acs_transaction_id,
				$message,
				$reason_code,
				$auth_avs_code,
				$auth_avs_code_raw,
				$auth_response,
				$auth_amount,
				$auth_code,
				$auth_cavv_result,
				$auth_cavv_result_raw,
				$auth_cv_result,
				$auth_cv_result_raw,
				$auth_trans_ref_no,
				$auth_time,
				$request_token,
				$auth_reconciliation_reference_number,
				$bill_trans_ref_no,
				$payer_authentication_validate_result,
				$payer_authentication_cavv,
				$payer_authentication_validate_e_commerce_indicator,
				$payer_authentication_eci,
				$payer_authentication_pares_status,
				$payer_authentication_xid,
				$payment_account_reference,
				// live end

				$signed_field_names,
				$signed_date_time
			];
			load_pg_lib('Nica');
			$sign = $this->pg->confirm($signed_field_names, $signed_field_values);
// debug($responseData);die;
		if ($responseData['decision'] == 'ACCEPT' && $responseData['signature'] == $sign) {
				$response['RC'] = 'COMPLETE';
				$response['status'] = ACCEPTED;
				$this->successTopup($response);
			} else {
				$response['status'] = DECLINED;
				$response['RC'] = $responseData['decision'];
				$this->cancelTopup($response);
			}
		} else {
			$response['PRN'] = '';
			$response['status'] = DECLINED;
			$response['RC'] = 'PGERROR';
			$this->cancelTopup($response);
		}
	}

	public function verify_khalti()
	{
        // debug($_GET);die; //pidx, txnId, amount, mobile, purchase_order_id, purchase_order_name, transaction_id
        $data = array(
            'pidx' => $_GET['pidx']
        );
        $curlopt_headers = array(
            'Authorization: key live_secret_key_17daa0221d28431b86b0ed9a74da82d7',
            'Content-Type: application/json',
        );
         $jsonData = json_encode($data);
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://khalti.com/api/v2/epayment/lookup/',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => $jsonData,
			CURLOPT_HTTPHEADER => $curlopt_headers,
		));
		$response = curl_exec($curl);
		curl_close($curl);
		$response = json_decode($response, true);
		// debug($response);die;
		$response['PRN'] = $_GET['purchase_order_id'];
		$response['R_AMT'] = $response['total_amount'];
// 		debug($response);die;
		$response['payment_method'] = PAY_WITH_KHALTI;
		switch ($response['status']) {
			case "Completed":
				$response['RC'] = 'successful';
				$response['status'] = ACCEPTED;
				$this->successTopup($response);
				break;
			case "Pending":
				$response['RC'] = 'pending';
				$response['status'] = PENDING;
					$this->cancelTopup($response);
				break;
			case "Initiated":
				$response['RC'] = 'initiated';
				$response['status'] = INITIATED;
					$this->cancelTopup($response);
				break;
			case "Refunded":
				$response['RC'] = 'refunded';
				$response['status'] = REFUNDED;
					$this->cancelTopup($response);
				break;
			case "Expired":
				$response['RC'] = 'expired';
				$response['status'] = EXPIRED;
								$this->cancelTopup($response);
					break;
			default:

				$response['RC'] = '';
				$response['status'] = DECLINED;
				$this->cancelTopup($response);
		}
	
	}


	function successTopup($response)
{

    $response_params = array();
    if (valid_array($response)) {
        //added payment modes
        switch ($response['payment_method']) {
            case PAY_NOW:
                $mode = 'fonepay';
                $transaction_id = $response['PRN'];
                break;
            case PAY_AT_BANK:
                $mode = 'connect';
                $transaction_id = $response['PRN'];
                break;
            case PAY_WITH_ESEWA:
                $mode = 'esewa';
                // $transaction_id = $response['PRN'];
                $transaction_id = $response['ref_id'];
                break;
            case PAY_WITH_KHALTI:
                $mode = 'khalti';
                $transaction_id = $response['transaction_id'];
                break;
                //changes added for nica
            case PAY_WITH_NICA:
                $mode = 'nica';
                $transaction_id = $response['transaction_id'];
                break;
            default:
        }
    }
    $this->load->model('transaction_model');
    $tempTopupData = $this->custom_db->single_table_records('offline_payment', '', array(
        'refernce_code' => $response['PRN']
    ));

    if (empty($tempTopupData) == false and valid_array($tempTopupData) == true) {

        $remarks = (array)json_decode($tempTopupData['data'][0]['remarks']);
        if ($remarks['topupStatus'] != TOPUP_SUCCESSFUL) {
            $remarks['payment_status'] = ACCEPTED;
            $remarks['topupStatus'] = TOPUP_SUCCESSFUL;
            $remarks['received_amount'] = $remarks['receivable_amount'];
            $remarks = json_encode($remarks);


            $updateData['remarks'] = $remarks;

            $updateCondition = array('refernce_code' => $response['PRN']);
            $updateStatus = $this->transaction_model->update_validation($updateData, $updateCondition);
            $validationId = $response['PRN'];
            if ($validationId != '') {
                $tempTopupData = $this->custom_db->single_table_records('offline_payment', '', array(
                    'refernce_code' => $response['PRN']
                ));
                if (empty($tempTopupData) == false and valid_array($tempTopupData) == true) {

                    $remarks = (array)json_decode($tempTopupData['data'][0]['remarks']);

                    if ($remarks['payment_status'] == ACCEPTED) {
                        $amount = $tempTopupData['data'][0]['amount'];

                        $agentDetails = $this->custom_db->single_table_records('user', 'user_id', array(
                            'email' => $tempTopupData['data'][0]['email'],
                            'user_type' => B2B_USER
                        ));

                        $tempUserData = $this->custom_db->single_table_records('b2b_user_details', 'balance', array(
                            'user_oid' => $agentDetails['data'][0]['user_id']
                        ));
                        if (empty($tempUserData) == false and valid_array($tempUserData) == true) {
                            $amount += $tempUserData['data'][0]['balance'];
                            $updateStatus = $this->transaction_model->updateBalance($validationId, $amount, $agentDetails['data'][0]['user_id']);

                            if ($updateStatus === 1) {
                                $page_data = array();
                                echo $this->template->view('payment/success', $page_data);
                            } else {
                                throw new Exception("The balance updation process failed");
                            }
                        }
                    }
                }
            }
        } else {
            echo $this->template->view('payment/success', $page_data);
        }
    }else {
        $msg = "Wrong access";
        redirect(base_url() . 'index.php/flight/exception?op=booking_exception&notification=' . $msg);
    }
}


	function cancelTopup($response)
	{

		if (valid_array($response)) {
			switch ($response['payment_method']) {
				case PAY_NOW:

					$mode = 'fonepay';
					$transaction_id = $response['PRN'];
					break;
				case PAY_AT_BANK:
					$mode = 'connect';
					$transaction_id = $response['PRN'];
					break;
				case PAY_WITH_ESEWA:

					$mode = 'esewa';
					$transaction_id = $response['PRN'];


					break;
				case PAY_WITH_KHALTI:
					$mode = 'khalti';
					$transaction_id = $response['transaction_id'];
					break;
					//changes addded new case for nica
				case PAY_WITH_NICA:
					$mode = 'nica';
					$transaction_id = @$response['transaction_id'];
					break;
				default:
			}
		}


		if (isset($response['status']) && $response['status'] != '') {
			$status = $response['status'];
		} else {
			$status = DECLINED;
		}

		//$product = $productinfo;
		$book_id = $response['PRN'];

		$this->load->model('transaction_model');
		$tempTopupData = $this->custom_db->single_table_records('offline_payment', '', array(
			'refernce_code' => $response['PRN']
		));
		if (empty($tempTopupData) == false and valid_array($tempTopupData) == true) {
			$remarks = (array)json_decode($tempTopupData['data'][0]['remarks']);
			$remarks['payment_status'] = $status;
			$remarks['topupStatus'] = TOPUP_FAILED;
			$remarks = json_encode($remarks);
			$updateData['remarks'] = $remarks;
			$updateCondition = array('refernce_code' => $response['PRN']);
			$updateStatus = $this->transaction_model->update_validation($updateData, $updateCondition);
			$page_data = array();
			$this->template->view('payment/failed', $page_data);
		}
	}


public function createtoken($string , $appref){
		
		  ini_set('display_errors', '1');
    date_default_timezone_set("Asia/Kathmandu");	
    function generateHash($string) {
    	// debug($string);die;
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


   		$tempTopupData = $this->custom_db->single_table_records('offline_payment', '', array(
			'refernce_code' => $appref
		));

		if (empty($tempTopupData) == false and valid_array($tempTopupData) == true) {
			$remarks = (array)json_decode($tempTopupData['data'][0]['remarks']);
		$amount=$remarks['receivable_amount'] * 100; 
		// $amount=500;//need to remove
}else{
	throw new Exception("Unable to confirm the amount");
}

    $curl = curl_init();

$MID=199;
$appId='MER-199-APP-5';
$url='https://login.connectips.com/connectipswebws/api/creditor/validatetxn';
$postdata='{
"merchantId": "'.$MID.'",
"appId": "'.$appId.'",
"referenceId": "'. $appref.'",
"txnAmt": "'.$amount.'",
"token":
"'. $token.'"
}'; 
$header=array(
    'Content-Type: application/json',
    'Authorization: Basic TUVSLTE5OS1BUFAtNTpUcmFWZWxATmV3MQ==', 
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
// debug($response);die;
$validated_response=json_decode($response,true);
curl_close($curl);
//$validated_response['status']=='SUCCESS';
//$validated_response['statusDesc']=='SUCCESS';
	//debug($validated_response);die;
	if($validated_response['status']=='SUCCESS' AND $validated_response['statusDesc']=='TRANSACTION SUCCESSFUL'){
		$this->success_ips_connect($appref,$response);
	}else{
		$response['payment_method'] = PAY_AT_BANK;
		//SUCCESS FAILED ERROR
		$response['status'] = $validated_response['status'];
		$response['PRN'] = $appref;
		$this->$this->cancelTopup($response);
	}

// debug($response );die;
}

public function pgsuccess($txid='')
	{
	// debug("reached");die;
		$txid=$_REQUEST['TXNID'];
		$_GET['TXNID']=$txid;
// 			debug($_REQUEST['TXNID']);die;
// echo $txid;die;
	

	/*$merchant_id=NCHL_MERCHANT_ID;
$appid=NCHL_APP_ID;
$appname=NCHL_APP_NAME;*/
	$merchant_id = 199;
 	$appid = "MER-199-APP-5";
 	$appname = "Travel Free Travels";
	$txid=$_GET['TXNID'];

		$tempTopupData = $this->custom_db->single_table_records('offline_payment', '', array(
			'refernce_code' => $txid
		));

		$pg_record = $tempTopupData['data'][0];
			$pg_record['amount']=round($pg_record['amount']);
		$amount=$pg_record['amount']*100;// added variable here to generate token properly.
			// $amount=500;
		$string = "MERCHANTID=$merchant_id,APPID=$appid,REFERENCEID=$txid,TXNAMT=$amount";


		


		$this->createtoken($string,$txid);

	}

function success_ips_connect($appref, $response)
{

    // echo "please contact admin for booking processs";die;

    /*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(0);*/
    // echo $response;die;
    // echo $appref;die;

    $resp_amount = json_decode($response, true);
    $book_id = $appref;
    $res_amount = $resp_amount['txnAmt'];

    $tempTopupData = $this->custom_db->single_table_records('offline_payment', '', array(
        'refernce_code' => $book_id
    ));
    // debug($tempTopupData);die;
    if (empty($tempTopupData) == false and valid_array($tempTopupData) == true) {
        $remarks = (array)json_decode($tempTopupData['data'][0]['remarks']);
        $amount = $remarks['receivable_amount'] * 100;
        // $amount=500;//need to remove
    } else {
        throw new Exception("Unable to confirm the amount");
    }
    //debug($temp_booking);die;

    if (empty($tempTopupData) == false and valid_array($tempTopupData) == true) {
        if ($remarks['topupStatus'] != TOPUP_SUCCESSFUL) {
            $remarks = (array)json_decode($tempTopupData['data'][0]['remarks']);
            $remarks['payment_status'] = ACCEPTED;
            $remarks['topupStatus'] = TOPUP_SUCCESSFUL;
            $remarks['received_amount'] = $remarks['receivable_amount'];
            $remarks = json_encode($remarks);


            $updateData['remarks'] = $remarks;

            $updateCondition = array('refernce_code' => $book_id);
            $updateStatus = $this->transaction_model->update_validation($updateData, $updateCondition);
            $validationId = $book_id;
            if ($validationId != '') {
                $tempTopupData = $this->custom_db->single_table_records('offline_payment', '', array(
                    'refernce_code' => $validationId
                ));
                if (empty($tempTopupData) == false and valid_array($tempTopupData) == true) {

                    $remarks = (array)json_decode($tempTopupData['data'][0]['remarks']);

                    if ($remarks['payment_status'] == ACCEPTED) {
                        $amount = $tempTopupData['data'][0]['amount'];

                        $agentDetails = $this->custom_db->single_table_records('user', 'user_id', array(
                            'email' => $tempTopupData['data'][0]['email'],
                            'user_type' => B2B_USER
                        ));

                        $tempUserData = $this->custom_db->single_table_records('b2b_user_details', 'balance', array(
                            'user_oid' => $agentDetails['data'][0]['user_id']
                        ));
                        if (empty($tempUserData) == false and valid_array($tempUserData) == true) {
                            $amount += $tempUserData['data'][0]['balance'];
                            $updateStatus = $this->transaction_model->updateBalance($validationId, $amount, $agentDetails['data'][0]['user_id']);

                            if ($updateStatus === 1) {
                                $page_data = array();
                                echo $this->template->view('payment/success', $page_data);
                            } else {
                                throw new Exception("The balance updation process failed");
                            }
                        }
                    }
                }
            }
        } else {
            echo $this->template->view('payment/success', $page_data);
        }
    }	else {
        $msg = "Wrong access";
        redirect(base_url() . 'index.php/flight/exception?op=booking_exception&notification=' . $msg);
    }
}

public function pgcancel(){
		$book_id = $_REQUEST['TXNID'];
		$response['PRN'] = $book_id;
			$response['payment_method'] = PAY_AT_BANK;
		//SUCCESS FAILED ERROR
		$response['status'] = $validated_response['status'];
		$this->cancelTopup($response);


}

}
