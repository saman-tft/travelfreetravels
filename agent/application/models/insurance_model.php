<?php
/**
 * Library which has generic functions to get data
 *
 * @package    Protect
 * @subpackage Insurance Model
 * @author     Saman A<saman.teamtft@gmail.com>
 * @version    V1
 */
Class Insurance_Model extends CI_Model
{
    public function __construct(){
		parent::__construct();
	}
	

    public function saveSelectedInsuranceDetails($id, $segmentDetails, $post_params, $searchData){
        try {
            $finalPlanDetails = [];
            $availableInsurancePlanDetails = json_decode(provab_decrypt($post_params['insuranceToken']), true);
            $insuranceId = $availableInsurancePlanDetails['id'];
            unset($availableInsurancePlanDetails['id']);
        
            if (valid_array($availableInsurancePlanDetails)) {
                $selectedPlanDetails = json_decode($post_params['selectedPlansJson'], true);
                $passengerDetails = $this->preparePassengerDetails($selectedPlanDetails, $post_params);
        
                $formattedArray = $this->prepareFormattedArray($post_params['search_id'], $selectedPlanDetails, $passengerDetails, $post_params, $segmentDetails, $searchData);
        
                $insuranceAmount = $this->calculateInsuranceAmount($selectedPlanDetails, $availableInsurancePlanDetails, $finalPlanDetails, $formattedArray);

                $nationalityCode = $this->getNationalityCode($searchData['data']['country']);
        
                $formattedArray['planDetails'] = $finalPlanDetails;
                $formattedArray['totalPrice'] = $insuranceAmount;
                $status = $this->createInsuranceRecord($id, $formattedArray, $nationalityCode, $insuranceId, $post_params['search_id'],$post_params['insuranceToken']);
                $status['total_price'] = $insuranceAmount;
            }
        } catch (Exception $e) {
            $status = 0;
            die('error');
            //unauth access k garney ho garr
        }
        return $status;
        
    }
    public function getNationalityCode($nationality){
        try{
        $nationality = $this->custom_db->single_table_records('country_list', 'country_code', array('nationality'=>trim($nationality)));
        if($nationality['status'] == 1){
            return $nationality['data'][0]['country_code'];
        }
        return 0;
        }catch(Exception $e){
            throw $e;
        }
    }

    private function createInsuranceRecord($bookingId, $formattedArray, $nationalityCode, $insuranceId, $searchId, $token){
        $creationData = [
            'message' => trim(json_encode($formattedArray, true)),
            'app_reference' => trim($bookingId),
            'sortcode' => 1,
            'fullname'=> trim($formattedArray['bookingPassengerDetails']['name']),
            'email'=>trim($formattedArray['bookingPassengerDetails']['email']),
            'phone'=>trim($formattedArray['bookingPassengerDetails']['phoneNumber']),
            'country'=>trim($nationalityCode),
            'passid'=>trim($insuranceId),
            'passno'=>trim($searchId),
            'state'=>trim($token)
        ];
        $creation = $this->custom_db->insert_record('plan_retirement', $creationData);
        return $creation;
    }
    private function preparePassengerDetails($selectedPlanDetails, $post_params) {
        $passengerDetails = [];
        foreach ($selectedPlanDetails as $key => $planDetail) {
            $planDetail['passengerDetails'] = [
                'name' => $planDetail['passenger'],
                'identificationType' => $post_params['identification_type'][$key],
                'identificationNumber' => $post_params['passenger_passport_number'][$key],
                'age' => $planDetail['passengerAge'],
                'gender' => $planDetail['passengerGender'],
                'dob' => $planDetail['passengerDOB'],
                'nationality'=> $post_params['passenger_passport_issuing_country'][$key],
                'isInfant'=>$planDetail['isInfant']
            ];
            $passengerDetails[] = $planDetail['passengerDetails'];
        }
        return $passengerDetails;
    }
    
    private function prepareFormattedArray($search_id, $selectedPlanDetails, $passengerDetails, $post_params, $segmentDetails, $searchData) {
        return [
            'searchId' => $search_id,
            'passengerDetails' => $passengerDetails,
            'bookingPassengerDetails' => [
                'name' => $selectedPlanDetails[0]['passenger'],
                'email' => $post_params['billing_email'],
                'phoneNumber' => $post_params['passenger_contact']
            ],
            'searchData' => $searchData['data'],
            'SegmentDetails' => $segmentDetails
        ];
    }
    
    private function calculateInsuranceAmount($selectedPlanDetails, $availableInsurancePlanDetails, &$finalPlanDetails, $formattedArray) {
        $insuranceAmount = 0;
        foreach ($selectedPlanDetails as $key => $planDetail) {
            $planType = $planDetail['type'] === "Individual" ? "perPassengerPlans" : "familyPlans";
            $planCategory = $planDetail['planType'];
    
            foreach ($availableInsurancePlanDetails[$planType][$planCategory . 'Plans'] as $availablePlan) {
                if ($planDetail['planId'] === $availablePlan['PlanCode']) {
                    $finalPlanDetails[$formattedArray['passengerDetails'][$key]['name']] = $availablePlan;
                    $insuranceAmount += (int) $availablePlan['TotalPremiumAmount'];
                    break;
                }
            }
        }
        return $insuranceAmount;
    }
    public function getCountryDetailsFromCityName($cityName){
        if (preg_match('/^(.*?)\s*\(.*/', $cityName, $matches)) {
            $cityName = $matches[1];
        }
        $cityName = $this->db->escape($cityName);
        $query = 'SELECT * FROM all_api_city_master WHERE city_name='."$cityName";
        $countryDetails = $this->db->query($query)->result_array()[0];
        return $countryDetails['country_code'];
    }
}
