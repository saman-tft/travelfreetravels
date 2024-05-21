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
	

    public function saveInsuranceSearchDetails($searchId, $searchData){
     
        if(valid_array($searchData) == true && $searchData != NULL && $searchId!= NULL && $searchId != ''){
        $searchData = json_encode($searchData, true);
        $insertionData['message'] = $searchData;
        $insertionData['sortcode'] = $searchId;
        $insertionRecord = $this->custom_db->insert_record('plan_retirement', $insertionData);
        }else{
            $insertionRecord['status'] = 0;
        }
        return $insertionRecord;
        
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
