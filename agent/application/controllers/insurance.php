<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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
                //failed to prepare header data
            }
            try {
                $formattedHeaderData = $this->insuranceService->getFormattedHeader($rawHeaderData);
            } catch (Exception $e) {
                $formattedHeaderData['status'] = 0;
                $formattedHeaderData['data'] = '';
                $formattedHeaderData['message'] = $e->getMessage();
            }

            $rawRequestData = $this->prepare_GetAvailablePlansOTAWithRiders_Request_Data($searchId, $SegmentDetailsToken);
            if (count($rawRequestData) > 0 && valid_array($rawRequestData) == true && $rawRequestData['status'] === 1) {
                $rawRequestData['method_name'] = $methodName;
                $rawRequestData['data']['header'] = $formattedHeaderData['data'];
                try {
                    $formattedRequest = $this->insuranceService->getFormattedApiRequest($rawRequestData);
                } catch (Exception $e) {
                    $formattedRequest['status'] = 0;
                    $formattedRequest['data'] = '';
                    $formattedRequest['message'] = $e->getMessage();
                }
                if ($formattedRequest['status'] === 1 && $formattedRequest['data'] != '') {
                    $formattedRequest['method_name'] = $methodName;
                    $formattedRequest['id'] = $searchId;
                    $formattedRequest['log_type'] = "search";
                    try {
                        $rawApiResponse = $this->insuranceService->getRawApiResponse($formattedRequest);
                    } catch (Exception $e) {
                        $rawApiResponse['status'] = 0;
                        $rawApiResponse['data'] = '';
                        $rawApiResponse['message'] = $e->getMessage();
                    }
                    if ($rawApiResponse['status'] === 1) {
                        $rawApiResponse['method_name'] = $methodName;
                        try {
                            $formattedApiData = $this->insuranceService->getFormattedApiResponse($rawApiResponse);
                            $formattedApiData['message'] = '';
                        } catch (Exception $e) {
                            $formattedApiData['status'] = 0;
                            $formattedApiData['data'] = '';
                            $formattedApiData['message'] = $e->getMessage();
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

    private function prepare_GetAvailablePlansOTAWithRiders_Header_Data(String $searchId)
    {
        if ($searchId == "" || $searchId == NULL) {
            echo "Unauthorized Access";
            exit;
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
