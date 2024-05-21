<?php
// include(APPPATH . 'config/services.php');
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
error_reporting(E_ALL);
/**
 *
 * @package Protect
 * @subpackage 
 * @author Saman <saman.teamtft@gmail.com>
 * @version V1
 */
class Insurance extends CI_Controller
{
    protected $service;
    protected $serviceLocator;
    protected $insuranceService;
    protected $CI;
    // public function __construct() {
    // $this->insuranceService = new Protect();
    //     // $this->load->library('ServiceLocator');
    //     // $this->serviceLocator = ServiceLocator::getInstance();
    //     // $this->service = $this->serviceLocator->resolve('InsuranceInterface', 'Insurance');
    // }
    public function __construct()
    {
        parent::__construct();
        $this->CI = &get_instance();
        $this->insuranceService = new Protect();
        $this->CI->load->model('flight_model');
        $this->CI->load->model('insurance_model');

    }
    public function GetAvailablePlansOTAWithRiders($searchId = '', $SegmentDetailsToken = '')
    {
        $methodName = 'GetAvailablePlansOTAWithRiders';
        if ($searchId != NULL && $searchId != '' && $SegmentDetailsToken != NULL && $SegmentDetailsToken != '') {
            $rawRequestData = $this->prepare_GetAvailablePlansOTAWithRiders_Request_Data($searchId, $SegmentDetailsToken);
            if (count($rawRequestData) > 0 && is_array($rawRequestData) == true && $rawRequestData['status'] === 1) {
                try {
                    $request = $this->insuranceService->getApiRequest($methodName, $rawRequestData);
                } catch (Exception $e) {
                    $request['status'] = 0;
                    $request['message'] = "Error Occured while getting $methodName request";
                    //log exception
                }
                if ($request['status'] === 1 && $request['data'] != '') {
                    try {
                        $rawApiResponse = $this->insuranceService->getApiResponse($request);
                    } catch (Exception $e) {
                        $rawApiResponse['status'] = 0;
                        $rawApiResponse['message'] = "Search Request Failed";
                    }
                    // extracting the xml as there were unnecessary texts in the response
                    if ($rawApiResponse['status'] === 1) {
                        $formattedApiData = $this->insuranceService->processApiResponse($methodName, $rawApiResponse);
                        if ($formattedApiData['status'] === 1) {
                            $insertionRecord = $this->insurance_model->saveInsuranceSearchDetails($searchId,$formattedApiData['PlanDetails']);
                            if($insertionRecord['status'] == 1){
                                $encodedPlans = json_encode($formattedApiData['PlanDetails'], true);
                                header('Content-Type: application/json');
                                echo $encodedPlans;
                            }else{
                                //saving the data failed
                            }
                        } else {
                            //data formatting failed
                        }
                    } else {
                        //failed to get request with the current details
                    }
                } else {
                    // no search data was found
                }
            } else {
                // not valid  request data obtained
            }
        } else {
            //failed response
        }
    }


    private function prepare_GetAvailablePlansOTAWithRiders_Request_Data(String $searchId, String $SegmentDetailsToken)
    {
        if($searchId == "" || $searchId == NULL || $SegmentDetailsToken == NULL || $SegmentDetailsToken == '' ){
            echo "Unauthorized Access";
            exit;
        }

        $searchData = $this->flight_model->get_safe_search_data($searchId);
        if ($searchData['status'] == 1 && isset($searchData['data'])) {
            $rawData['status'] = 1;
            $rawData['data']['search_id'] = $searchId;
            $rawData['data']['search_data'] = $searchData;
            $segmentDetails = json_decode(base64_decode($SegmentDetailsToken), true);
            $rawData['data']['segment_details'] = $segmentDetails;
        } else {
            $rawData['message'] = "No such record exists";
            $rawData['status'] = 0;
        }

        return $rawData;
    }
}