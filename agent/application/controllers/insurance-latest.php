<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 *
 * @package Flight Insurance
 * @subpackage  Tune Protect
 * @author Saman <saman.teamtft@gmail.com>
 * @version V1
 */
class Insurance extends CI_Controller
{
    private $insuranceService;
    public function __construct()
    {
        parent::__construct();
        require_once(BASEPATH . 'libraries/insurance/Protect.php');
        require_once(BASEPATH . 'libraries/insurance/InsuranceService.php');
        $protectInstance = new Protect();
        $this->insuranceService = new InsuranceService($protectInstance);

        $this->load->model('flight_model');
        $this->load->model('insurance_model');
    }
    public function GetAvailablePlansOTAWithRiders($searchId = '', $SegmentDetailsToken = '')
    {

        $methodName = 'GetAvailablePlansOTAWithRiders';
        if ($searchId != NULL && $searchId != '' && $SegmentDetailsToken != NULL && $SegmentDetailsToken != '') {
            $rawHeaderData = $this->prepare_GetAvailablePlansOTAWithRiders_Header_Data($searchId);
            if ($rawHeaderData['status'] !== 1 && valid_array($rawHeaderData) !== true) {
                $exception['message'] = "Failed to prepare header data."
                $exception['methodName'] = $methodName;
                $exception = base64_encode(json_encode($exception));
                redirect(base_url() . 'index.php/insurance/insuranceExceptionHandler/' . $exception);
            }
            try {
                $formattedHeaderData = $this->insuranceService->getFormattedHeader($rawHeaderData);
            } catch (Exception $e) {
                $exception['methodName'] = $methodName;
                $exception['message'] = $e->getMessage();
                $exception = base64_encode(json_encode($exception));
                redirect(base_url() . 'index.php/insurance/insuranceExceptionHandler/' . $exception);
                
            }

            $rawRequestData = $this->prepare_GetAvailablePlansOTAWithRiders_Request_Data($searchId, $SegmentDetailsToken);
            if (count($rawRequestData) > 0 && valid_array($rawRequestData) == true && $rawRequestData['status'] === 1) {
                $rawRequestData['method_name'] = $methodName;
                $rawRequestData['data']['header'] = $formattedHeaderData['data'];
                try {
                    $formattedRequest = $this->insuranceService->getFormattedApiRequest($rawRequestData);
                } catch (Exception $e) {
                    $exception['methodName'] = $methodName;
                    $exception['message'] = $e->getMessage();
                    $exception = base64_encode(json_encode($exception));
                    redirect(base_url() . 'index.php/insurance/insuranceExceptionHandler/' . $exception);
                }
                if ($formattedRequest['status'] === 1 && $formattedRequest['data'] != '') {
                    $formattedRequest['method_name'] = $methodName;
                    $formattedRequest['id'] = $searchId;
                    $formattedRequest['log_type'] = "search";
                    try {
                        $rawApiResponse = $this->insuranceService->getRawApiResponse($formattedRequest);
                    } catch (Exception $e) {
                        $exception['methodName'] = $methodName;
                        $exception['message'] = $e->getMessage();
                        $exception = base64_encode(json_encode($exception));
                        redirect(base_url() . 'index.php/insurance/insuranceExceptionHandler/' . $exception);
                    }
                    if ($rawApiResponse['status'] === 1) {
                        $rawApiResponse['method_name'] = $methodName;
                        try {
                            $formattedApiData = $this->insuranceService->getFormattedApiResponse($rawApiResponse);
                            $formattedApiData['message'] = '';
                        } catch (Exception $e) {
                            $exception['methodName'] = $methodName;
                            $exception['message'] = $e->getMessage();
                            $exception = base64_encode(json_encode($exception));
                            redirect(base_url() . 'index.php/insurance/insuranceExceptionHandler/' . $exception);
                        }

                        if ($formattedApiData['status'] === 1) {
                          
                            $plansDetails['data'] = $formattedApiData['PlanDetails'];
                            $plansDetails['id'] = 'INS' . date('Ymd') . rand(1000, 2000);
                            $plansDetails['token'] = provab_encrypt(json_encode($plansDetails, true));
                            $encodedPlans = json_encode($plansDetails, true);
                            header('Content-Type: application/json');
                            echo $encodedPlans;
                        }
                    }
                }
            }
        }
    }
    private function prepare_ConfirmPurchase_Header_Data($searchId,$methodName, $pnr, $totalAmount, $bookId){
        if ($searchId == "" || $searchId == NULL || $methodName == '' || $methodName == NULL || $methodName == NULL || $pnr == '' || $pnr == NULL ||$totalAmount == ''|| $totalAmount == NULL) {
            $exception['methodName'] = 'prepare_ConfirmPurchase_Header_Data';
            $exception['message'] = "Unauthorized access";
            $exception = base64_encode(json_encode($exception));
            redirect(base_url() . 'index.php/insurance/insuranceExceptionHandler/' . $exception);
        }

        $searchData = $this->flight_model->get_safe_search_data($searchId);
        if ($searchData['status'] == 1 && isset($searchData['data'])) {
            $purchaseTime = $this->custom_db->single_table_records('flight_booking_details', 'created_datetime', array('app_reference'=>$bookId));
            if($purchaseTime['status'] == 1){
                $countryCode = $this->insurance_model->getNationalityCode($searchData['data']['country']);
                $rawData['status'] = 1;
                $rawData['data']['countryCode'] = $countryCode;
                $rawData['data']['search_id'] = $searchId;
                $rawData['data']['search_data'] = $searchData;
                $rawData['data']['purchaseDate'] = $purchaseTime['data'][0]['created_datetime'];
                $rawData['data']['pnr'] = $pnr;
                $rawData['data']['totalAmount'] = $totalAmount;
                $rawData['method_name'] = $methodName;
                $rawData['message'] = '';
            }else{
                $exception['methodName'] = 'prepare_ConfirmPurchase_Header_Data';
                $exception['message'] = "No such booking record exists";
                $exception = base64_encode(json_encode($exception));
                redirect(base_url() . 'index.php/insurance/insuranceExceptionHandler/' . $exception);
            }
           
        } else {
            $exception['methodName'] = 'prepare_ConfirmPurchase_Header_Data';
            $exception['message'] = "No such booking record exists";
            $exception = base64_encode(json_encode($exception));
            redirect(base_url() . 'index.php/insurance/insuranceExceptionHandler/' . $exception);
        }
        return $rawData;
    }

    public function processInsurancePurchase($bookId, $bookingSource){
        $methodName = 'ConfirmPurchase';
        if ($bookId != NULL && $bookId != '' && $bookingSource != NULL && $bookingSource != '') {
            $completeInsuranceInformation = $this->insurance_model->getInsuranceDetails(array('app_reference'=>$bookId));
            if($completeInsuranceInformation['status'] != 1){
                $exception['methodName'] = $methodName;
                $exception['message'] = "No such insurance record exists";
                $exception = base64_encode(json_encode($exception));
                redirect(base_url() . 'index.php/insurance/insuranceExceptionHandler/' . $exception);
            }
            $insuranceDetails = json_decode($completeInsuranceInformation['data'][0]['message'], true);

            $insuranceTotalAmount = $insuranceDetails['totalPrice'];
            $pnr = $completeInsuranceInformation['data'][0]['city'];
            $searchId = $insuranceDetails['searchId'];

            $this->ConfirmPurchase($searchId,$bookId, $methodName, $pnr, $insuranceTotalAmount, $insuranceDetails, $bookingSource);
        }else{
            $exception['methodName'] = $methodName;
            $exception['message'] = "Unauthorized access";
            $exception = base64_encode(json_encode($exception));
            redirect(base_url() . 'index.php/insurance/insuranceExceptionHandler/' . $exception);
        }
    }
    private function ConfirmPurchase($searchId, $bookId, $methodName, $pnr, $insuranceTotalAmount, $insuranceDetails, $bookingSource){
        if($bookId != NULL && $bookId != '' && $bookingSource != NULL && $bookingSource != ''){
            $rawHeaderData = $this->prepare_ConfirmPurchase_Header_Data($searchId,$methodName, $pnr, $insuranceTotalAmount, $bookId);
            if ($rawHeaderData['status'] !== 1 && valid_array($rawHeaderData) !== true) {
                $exception['message'] = "Failed to prepare header data."
                $exception['methodName'] = $methodName;
                $exception = base64_encode(json_encode($exception));
                redirect(base_url() . 'index.php/insurance/insuranceExceptionHandler/' . $exception);
            }
            try {
                $formattedHeaderData = $this->insuranceService->getFormattedHeader($rawHeaderData);
            } catch (Exception $e) {
                $exception['message'] = $e->getMessage();
                $exception['methodName'] = $methodName;
                $exception = base64_encode(json_encode($exception));
                redirect(base_url() . 'index.php/insurance/insuranceExceptionHandler/' . $exception);
            }

            $rawRequestData = $this->prepare_ConfirmPurchase_Request_Data($searchId, $insuranceDetails,$methodName);
            if (count($rawRequestData) > 0 && valid_array($rawRequestData) == true && $rawRequestData['status'] === 1) {
                $rawRequestData['method_name'] = $methodName;
                $rawRequestData['data']['header'] = $formattedHeaderData['data'];
                try {
                    $formattedRequest = $this->insuranceService->getFormattedApiRequest($rawRequestData);
                } catch (Exception $e) {
                    $exception['message'] = $e->getMessage();
                    $exception['methodName'] = $methodName;
                    $exception = base64_encode(json_encode($exception));
                    redirect(base_url() . 'index.php/insurance/insuranceExceptionHandler/' . $exception);
                }
                if ($formattedRequest['status'] === 1 && $formattedRequest['data'] != '') {
                    $formattedRequest['method_name'] = $methodName;
                    $formattedRequest['id'] = $searchId;
                    $formattedRequest['log_type'] = "purchase";
                    try {
                        $rawApiResponse = $this->insuranceService->getRawApiResponse($formattedRequest);
                    } catch (Exception $e) {
                        $exception['message'] = $e->getMessage();
                        $exception['methodName'] = $methodName;
                        $exception = base64_encode(json_encode($exception));
                        redirect(base_url() . 'index.php/insurance/insuranceExceptionHandler/' . $exception);
                    }
                    if ($rawApiResponse['status'] === 1) {
                        $rawApiResponse['method_name'] = $methodName;
                        try {
                            $formattedApiData = $this->insuranceService->getFormattedApiResponse($rawApiResponse);
                            $formattedApiData['message'] = '';
                        } catch (Exception $e) {
                            $formattedApiData['status'] = 'FAILED';
                            $exception['message'] = $e->getMessage();
                            $exception['methodName'] = $methodName;
                            $exception = base64_encode(json_encode($exception));
                            redirect(base_url() . 'index.php/insurance/insuranceExceptionHandler/' . $exception);
                        }

                        if ($formattedApiData['status'] == 'CONFIRMED') {
                            try{
                            $updateStatus = $this->insurance_model->updateConfirmedInsuranceDetails($bookId, $formattedApiData);
                            }catch(Exception $e){
                                $updateStatus['status'] = 0;
                                $exception['message'] = $e->getMessage();
                                $exception['methodName'] = $methodName;
                                $exception = base64_encode(json_encode($exception));
                                redirect(base_url() . 'index.php/insurance/insuranceExceptionHandler/' . $exception);
                            }
                        }else{
                            $exception['message'] = "Failed to confirm purchase";
                            $exception['methodName'] = $methodName;
                            $exception = base64_encode(json_encode($exception));
                            redirect(base_url() . 'index.php/insurance/insuranceExceptionHandler/' . $exception);
                        }
                        redirect(base_url() . 'index.php/voucher/flight/' . $bookId . '/' . $bookingSource . '/' . 'BOOKING_CONFIRMED' . '/show_voucher' . '/insurance'. '/'. $formattedApiData['status']);
                        
                    }
                }
            }
        }

    }

    private function prepare_GetAvailablePlansOTAWithRiders_Header_Data(String $searchId)
    {
        if ($searchId == "" || $searchId == NULL) {
            $exception['message'] = "Unauthorized access";
            $exception['methodName'] = "prepare_GetAvailablePlansOTAWithRiders_Header_Data";
            $exception = base64_encode(json_encode($exception));
            redirect(base_url() . 'index.php/insurance/insuranceExceptionHandler/' . $exception);
        }

        $searchData = $this->flight_model->get_safe_search_data($searchId);
        if ($searchData['status'] == 1 && isset($searchData['data'])) {
            $rawData['status'] = 1;
            $rawData['data']['search_id'] = $searchId;
            $rawData['data']['search_data'] = $searchData;
            $rawData['method_name'] = 'GetAvailablePlansOTAWithRiders';
            $rawData['message'] = '';
        } else {
            $rawData['message'] = "No such record exists";
            $rawData['data'] = '';
            $rawData['status'] = 0;
        }
        return $rawData;
    }
    private function prepare_GetAvailablePlansOTAWithRiders_Request_Data(String $searchId, String $SegmentDetailsToken)
    {
        if ($searchId == "" || $searchId == NULL || $SegmentDetailsToken == NULL || $SegmentDetailsToken == '') {
            $exception['message'] = "Unauthorized access";
            $exception['methodName'] = "prepare_GetAvailablePlansOTAWithRiders_Request_Data";
            $exception = base64_encode(json_encode($exception));
            redirect(base_url() . 'index.php/insurance/insuranceExceptionHandler/' . $exception);
        }

        $searchData = $this->flight_model->get_safe_search_data($searchId);
        if ($searchData['status'] == 1 && isset($searchData['data'])) {
            $rawData['status'] = 1;
            $rawData['data']['search_id'] = $searchId;
            $rawData['data']['search_data'] = $searchData;
            $segmentDetails = json_decode(base64_decode($SegmentDetailsToken), true);
            $rawData['data']['segment_details'] = $segmentDetails;
        } else {
            $rawData['data'] = '';
            $rawData['message'] = "No such record exists";
            $rawData['status'] = 0;
            $exception['message'] = "No such record exists";
            $exception['methodName'] = "prepare_GetAvailablePlansOTAWithRiders_Request_Data";
            $exception = base64_encode(json_encode($exception));
            redirect(base_url() . 'index.php/insurance/insuranceExceptionHandler/' . $exception);
        }
        return $rawData;
    }
    private function prepare_ConfirmPurchase_Request_Data($searchId, $insuranceDetails,$methodName){
        if ($searchId == "" || $searchId == NULL || $methodName == NULL || $methodName == '' || !valid_array($insuranceDetails)) {
            $exception['message'] = "Unauthorized access";
            $exception['methodName'] = "prepare_ConfirmPurchase_Request_Data";
            $exception = base64_encode(json_encode($exception));
            redirect(base_url() . 'index.php/insurance/insuranceExceptionHandler/' . $exception);
        }

        $searchData = $this->flight_model->get_safe_search_data($searchId);
        if ($searchData['status'] == 1 && isset($searchData['data'])) {
            $rawData['status'] = 1;
            $rawData['data']['search_data'] = $searchData;
            $rawData['data']['insuranceDetails'] = $insuranceDetails;
            $rawData['method_name'] = $methodName;
        } else {
            $rawData['data'] = '';
            $rawData['message'] = "No such record exists";
            $rawData['status'] = 0;
        }
        return $rawData; 
    }

    public function insuranceExceptionHandler($exception){
        if(!$exception){
            $exception['message'] = "Please try again";
            $exception['status'] = 0;
            $exception['methodName'] = "Unknown"; 
        }
        $this->exceptionLogger($exception);
        $this->template->view('insurance/exception');
    }

    private function exceptionLogger($exception){
        if(!$exception){
            $exception['message'] = "Please try again";
            $exception['status'] = 0;
            $exception['methodName'] = "Unknown"; 
        }else{
            $exception = base64_decode($exception, true)
        }
        $status = 0;
        $methodName = $exception['methodName'];
        $message = $exception['message'];
        $log_entry = "\n========== BEGIN EXCEPTION ==========\n";
        $log_entry .= "Exception Method Name: $methodName\n";
        $log_entry .= "Response Status: $status\n";
        $log_entry .= "Timestamp: " . date('Y-m-d H:i:s') . "\n";
        $log_entry .= "$message\n";
        $log_entry .= "========== END EXCEPTION ==========\n";


        $log_dir = BASEPATH . "../logs/insurance_logs/protect_logs";
        $log_file = "{$log_dir}/" . "exception_log" . '.log';
    
        if (!is_dir($log_dir)) {
            if (!mkdir($log_dir, 0755, true)) {
                log_message('error', 'Unable to create log directory: ' . $log_dir);
                return;
            }
        }
    
        if (!file_exists($log_file)) {
            if (!touch($log_file)) {
                log_message('error', 'Unable to create log file: ' . $log_file);
                return;
            }
        }
    
        if (!write_file($log_file, $log_entry, 'a')) {
            log_message('error', 'Unable to write to log file: ' . $log_file);
        } else {
            log_message('debug', 'Successfully wrote to log file: ' . $log_file);
        }
    }
}
