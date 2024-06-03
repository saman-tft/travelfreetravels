<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once('InsuranceInterface.php');
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
// error_reporting(E_ALL);
/**
 *
 * @package Protect
 * @subpackage Insurance
 * @author Saman <saman.teamtft@gmail.com>
 * @version V1
 */
class Protect implements InsuranceInterface
{

    private $userName;
    private $password;
    private $url;
    private $environment;
    private $channelCode;
    private $CI;
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->environment = $this->CI->config->item('protect_api_mode');
        $this->CI->load->model('insurance_model');
        //credentials configuration
        if ($this->environment == 'test') {
            $this->userName = PROTECT_TEST_USERNAME;
            $this->password = PROTECT_TEST_PASSWORD;
            $this->url = PROTECT_TEST_REQUEST_URL;
            $this->channelCode = PROTECT_TEST_CHANNEL_CODE;
        } else {
            $this->userName = PROTECT_LIVE_USERNAME;
            $this->password = PROTECT_LIVE_PASSWORD;
            $this->url = PROTECT_LIVE_REQUEST_URL;
            $this->channelCode = PROTECT_LIVE_CHANNEL_CODE;
        }
    }
    public function getFormattedHeader(array $headerData): array
    {
        if (valid_array($headerData) == true && $headerData !== '' && $headerData !== NULL && (isset($headerData['status']) && $headerData['status'] === 1)) {
            $headerName = $headerData['method_name'];
            switch ($headerName) {
                case 'GetAvailablePlansOTAWithRiders':
                    $formattedHeaderData = $this->get_GetAvailablePlansOTAWithRiders_Request_Header($headerData);
                    break;
                case 'ConfirmPurchase':
                    $formattedHeaderData = $this->get_ConfirmPurchase_Request_Header($headerData);
                    break;
                default:
                    throw new Exception("Invalid header name");
            }
            return $formattedHeaderData;
        } else {
            throw new Exception("Bad insurance header request.");
        }
    }

    public function getFormattedApiRequest(array $requestData): array
    {


        if ($requestData !== '' && $requestData !== NULL && (valid_array($requestData) == true) && isset($requestData['status']) && $requestData['status'] === 1) {
            $requestName = $requestData['method_name'];
            switch ($requestName) {
                case 'GetAvailablePlansOTAWithRiders':
                    $formattedApiRequest = $this->get_GetAvailablePlansOTAWithRiders_Request($requestData);
                    break;
                case 'ConfirmPurchase':
                    $formattedApiRequest = $this->get_ConfirmPurchase_Request($requestData);
                    break;
                default:
                    throw new Exception("Invalid request name provided");
            }
            return $formattedApiRequest;
        } else {
            throw new Exception("Invalid request data provided");
        }
    }

    public function getRawApiResponse(array $request): array
    {
        if ((is_array($request) == true) && isset($request['status']) && $request['status'] === 1) {
            $ch = curl_init($this->url);
            $testFile = fopen("newfile.xml", "w") or die("Unable to open file!");
            fwrite($testFile, $request['data']);
            fclose($testFile);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml', 'Accept : */*', ''));
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_USERPWD, $this->userName . ":" . $this->password);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request['data']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            $rawApiResponse = curl_exec($ch);
            if (curl_errno($ch)) {
                $response['message'] = 'Couldn\'t send request: ' . curl_error($ch);
                $response['data'] = '';
                $response['status'] = 0;
                $this->insuranceLogger($request['id'], $request['data'], $response['message'], $request['method_name'], 'error', $response['status']);
            } else {
                $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                if ($resultStatus == 200) {
                    //good response
                    $response['message'] = '';
                    $response['data'] = $rawApiResponse;
                    $response['status'] = 1;
                    $this->insuranceLogger($request['id'], $request['data'], $response['data'], $request['method_name'], $request['log_type'], $response['status']);
                } else {
                    //server responded with a http error
                    $response['message'] = 'Request failed: HTTP status code: ' . $resultStatus;
                    $response['data'] = '';
                    $response['status'] = 0;
                    $this->insuranceLogger($request['id'], $request['data'],  $response['message'], $request['method_name'], 'error', $response['status']);
                }
            }
        } else {
            throw new Exception("Invalid request data provided");
        }

        return $response;
    }

    public function getFormattedApiResponse(array $rawApiResponse): array
    {
        if ($rawApiResponse !== '' && $rawApiResponse !== NULL && (valid_array($rawApiResponse) == true) && isset($rawApiResponse['status']) && $rawApiResponse['status'] === 1) {
            $apiMethodName = $rawApiResponse['method_name'];
            switch ($apiMethodName) {
                case 'GetAvailablePlansOTAWithRiders':
                    $response = $this->process_GetAvailablePlansOTAWithRidersResponse($rawApiResponse);
                    break;
                case 'ConfirmPurchase':
                    $response = $this->process_ConfirmPurchaseResponse($rawApiResponse);
                    break;
                default:
                    throw new Exception("Invalid api method name provided");
            }
            return $response;
        } else {
            throw new Exception("Invalid raw response data");
        }
    }

    private function getAuthHeader(): String
    {
        $authHeader = '<Authentication>
                    <Username>' . $this->userName . '</Username>
                    <Password>' . $this->password . '</Password>
                 </Authentication>';
        return $authHeader;
    }

    private function get_GetAvailablePlansOTAWithRiders_Request_Header(Array $headerData): Array
    {
        if ((is_array($headerData) == true) && isset($headerData['status']) && $headerData['status'] === 1) {
            $searchId = $headerData['data']['search_id'];
            if ($searchId != NULL && $searchId != '') {
                $searchData = $headerData['data']['search_data'];
                if ($searchData['status'] == 1 && isset($searchData['data'])) {
                    //get current currency
                    $currentCurrency = get_application_currency_preference();

                    //IF number of adults,children,infants is blank 0 is assigned
                    $numberOfAdults = ($searchData['data']['adult_config'] == 0 || $searchData['data']['adult_config'] == NULL || $searchData['data']['adult_config'] == '') ? 0 : $searchData['data']['adult_config'];
                    $numberOfChildren = ($searchData['data']['child_config'] == 0 || $searchData['data']['child_config'] == NULL || $searchData['data']['child_config'] == '') ? 0 : $searchData['data']['child_config'];
                    $numberOfInfants = ($searchData['data']['infant_config'] == 0 || $searchData['data']['infant_config'] == NULL || $searchData['data']['infant_config'] == '') ? 0 : $searchData['data']['infant_config'];

                    //prepare the request to return
                    $response['data'] = "<Header>
            <Channel>" . $this->channelCode . "</Channel>
            <Currency>NPR</Currency>
            <CountryCode>EN</CountryCode>
            <CultureCode>EN</CultureCode>
            <TotalAdults>$numberOfAdults</TotalAdults>
            <TotalChild>$numberOfChildren</TotalChild>
            <TotalInfants>$numberOfInfants </TotalInfants>
         </Header>";

                    $response['status'] = 1;
                    $response['message'] = '';
                } else {
                    //search data not found
                    $response['status'] = 0;
                    $response['message'] = "No record with the search id was found";
                }
            } else {
                //null search id
                $response['status'] = 0;
                $response['data'] = '';
                $response['message'] = "Invalid search id";
            }
        } else {
            //invalid data provided or unauth access
            $response['status'] = 0;
            $response['data'] = '';
            $response['message'] = "Unauthorized access";
        }
        return $response;
    }

private function get_ConfirmPurchase_Request_Header(Array $headerData): Array{
    if ((is_array($headerData) == true) && isset($headerData['status']) && $headerData['status'] === 1) {
        $searchId = $headerData['data']['search_id'];
        if ($searchId != NULL && $searchId != '') {
            $searchData = $headerData['data']['search_data'];
            if ($searchData['status'] == 1 && isset($searchData['data'])) {
                $pnr = $headerData['data']['pnr'];
                $totalAmount = $headerData['data']['totalAmount'];
                $purchaseDate = $headerData['data']['purchaseDate'];
                $countryCode = $headerData['data']['countryCode'];
                //get current currency
                $currentCurrency = get_application_currency_preference();

                //IF number of adults,children,infants is blank 0 is assigned
                $numberOfAdults = ($searchData['data']['adult_config'] == 0 || $searchData['data']['adult_config'] == NULL || $searchData['data']['adult_config'] == '') ? 0 : $searchData['data']['adult_config'];
                $numberOfChildren = ($searchData['data']['child_config'] == 0 || $searchData['data']['child_config'] == NULL || $searchData['data']['child_config'] == '') ? 0 : $searchData['data']['child_config'];
                $numberOfInfants = ($searchData['data']['infant_config'] == 0 || $searchData['data']['infant_config'] == NULL || $searchData['data']['infant_config'] == '') ? 0 : $searchData['data']['infant_config'];

                //prepare the request to return
                $response['data'] = "<Header>
                <Channel>" . PROTECT_TEST_CHANNEL_CODE . "</Channel>
                <ItineraryID></ItineraryID>
                <PNR>$pnr</PNR>
                <PolicyNo/>
                <PurchaseDate>$purchaseDate</PurchaseDate>
                <SSRFeeCode>INSC</SSRFeeCode>
                <Currency>NPR</Currency>
                <TotalPremium>$totalAmount</TotalPremium>
                <CountryCode>EN</CountryCode>
                <CultureCode>EN</CultureCode>
                <TotalAdults>$numberOfAdults</TotalAdults>
                <TotalChild>$numberOfChildren</TotalChild>
                <TotalInfants>$numberOfInfants </TotalInfants>
             </Header>";

                $response['status'] = 1;
                $response['message'] = '';
            } else {
                //search data not found
                $response['status'] = 0;
                $response['message'] = "No record with the search id was found";
            }
        } else {
            //null search id
            $response['status'] = 0;
            $response['data'] = '';
            $response['message'] = "Invalid search id";
        }
    } else {
        //invalid data provided or unauth access
        $response['status'] = 0;
        $response['data'] = '';
        $response['message'] = "Unauthorized access";
    }
    return $response;

}

    private function get_GetAvailablePlansOTAWithRiders_Request(array $requestData): array
    {
        if ((is_array($requestData) == true) && isset($requestData['status']) && $requestData['status'] === 1) {
            $response['status'] = 1;
            $searchData = $requestData['data']['search_data'];
            $segmentDetails = $requestData['data']['segment_details'];
            $header = $requestData['data']['header'];
            $authHeader = $this->getAuthHeader();
            $currentFlightInformation = $this->formatFlightInformationToXML($searchData, $segmentDetails);
            $response['data'] = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:web="http://ZEUSTravelInsuranceGateway/WebServices">
            <soapenv:Header/>
            <soapenv:Body>
                <GetAvailablePlansOTAWithRiders xmlns="http://ZEUSTravelInsuranceGateway/WebServices">
                <GenericRequestOTALite>'
                . $authHeader .
                $header .
                $currentFlightInformation
                . '</GenericRequestOTALite>
            </GetAvailablePlansOTAWithRiders>
            </soapenv:Body>
            </soapenv:Envelope>';
            $response['message'] = '';
        } else {
            //no search id and unauthorized access
            $response['status'] = 0;
            $response['data'] = '';
            $response['message'] = 'Invalid request data for request method GetAvailablePlansOTAWithRiders ';
        }
        return $response;
    }
    private function getBookingPassengerInformation($insuranceDetails){
        $response = '<ContactDetails>
        <ContactPerson>'.$insuranceDetails['bookingPassengerDetails']['name'].'</ContactPerson>
        <Address1></Address1>
        <Address2></Address2>
        <Address3/>
        <HomePhoneNum/>
        <MobilePhoneNum>'.$insuranceDetails['bookingPassengerDetails']['phoneNumber'].'</MobilePhoneNum>
        <OtherPhoneNum/>
        <PostCode></PostCode>
        <City></City>
        <State></State>
        <Country></Country>
        <EmailAddress>'.$insuranceDetails['bookingPassengerDetails']['email'].'</EmailAddress>
        </ContactDetails>';
        return $response;
    }
    function formatPassengerInformationToXML($insuranceInformation){
        $passengerInformation = $insuranceInformation['passengerDetails'];
        $planDetails = $insuranceInformation['planDetails'];
        $request = '<Passengers>';
        foreach($passengerInformation as $k=>$v){
            $nameArray = explode(' ', $passengerInformation[$k]['name']);
            $firstName = $nameArray[0];
            $lastName = $nameArray[1];
            $age = $passengerInformation[$k]['age'];
            $dob = $passengerInformation[$k]['dob'];
            $gender = $passengerInformation[$k]['gender'];
            if($passengerInformation[$k]['identificationType'] == 'passport'){
                $documentType = "Passport";
            }else{
                $documentType = "IdentificationCard";
            }
            $currencyCode = $planDetails[$passengerInformation[$k]['name']]['CurrencyCode'];
            $identityNumber = $passengerInformation[$k]['identificationNumber']; 
            $ssrFeeCode = $planDetails[$passengerInformation[$k]['name']]['SSRFeeCode'];
            $planCode = $planDetails[$passengerInformation[$k]['name']]['PlanCode'];
            $paxTotalAmount = round($planDetails[$passengerInformation[$k]['name']]['TotalPremiumAmount']);
            $isInfant = $passengerInformation[$k]['isInfant'];
            $nationality =$passengerInformation[$k]['nationality']; 
            
            $request .= "<Passenger>
            <IsInfant>$isInfant</IsInfant>
            <FirstName>$firstName</FirstName>
            <LastName>$lastName</LastName>
            <Gender>$gender</Gender>
            <DOB>$dob 00:00:00</DOB>
            <Age>$age</Age>
            <IdentityType>$documentType</IdentityType>
            <IdentityNo>$identityNumber</IdentityNo>
            <IsQualified>true</IsQualified>
            <Nationality>$nationality</Nationality>
            <CountryOfResidence>$nationality</CountryOfResidence>
            <SelectedPlanCode>$planCode</SelectedPlanCode>
            <SelectedSSRFeeCode>$ssrFeeCode</SelectedSSRFeeCode>
            <CurrencyCode>$currencyCode</CurrencyCode>
            <PassengerPremiumAmount>$paxTotalAmount</PassengerPremiumAmount>
            </Passenger>";
        }

        $request .= '</Passengers>';
        return $request;
    }
    private function get_ConfirmPurchase_Request($requestData){
        if ((is_array($requestData) == true) && isset($requestData['status']) && $requestData['status'] === 1) {
            $response['status'] = 1;
            $searchData = $requestData['data']['search_data'];
            $segmentDetails = $requestData['data']['insuranceDetails']['SegmentDetails'];
            $header = $requestData['data']['header'];
            $insuranceDetails = $requestData['data']['insuranceDetails'];
            $authHeader = $this->getAuthHeader();
            $bookingPersonDetails =$this->getBookingPassengerInformation($insuranceDetails);
            $currentFlightInformation = $this->formatFlightInformationToXMLForPurchase($searchData, $segmentDetails);
            $currentFlightInformation = '    <Flights>   
            <Flight>   
            <DepartCountryCode>AE</DepartCountryCode>
            <DepartStationCode></DepartStationCode>
            <ArrivalCountryCode>IN</ArrivalCountryCode>
            <ArrivalStationCode></ArrivalStationCode>
            <DepartAirlineCode>AI</DepartAirlineCode>
            <DepartDateTime>2025-01-01 06:30:00</DepartDateTime>
            <ReturnAirlineCode></ReturnAirlineCode>
            <ReturnDateTime></ReturnDateTime>
            <DepartFlightNo>638</DepartFlightNo>
            <ReturnFlightNo></ReturnFlightNo>
            </Flight>
         </Flights>';
        $passengerInformation = $this->formatPassengerInformationToXML($insuranceDetails);
        $response['data'] = '<?xml version="1.0" encoding="utf-8"?>
        <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xmlns:xsd="http://www.w3.org/2001/XMLSchema"
        xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
        <soap:Body>
        <ConfirmPurchase xmlns="http://ZEUSTravelInsuranceGateway/WebServices">
        <GenericRequest>'
        . $authHeader .
        $header.
        $bookingPersonDetails.
        $currentFlightInformation
        .$passengerInformation.'
        </GenericRequest>
        </ConfirmPurchase>
        </soap:Body>
        </soap:Envelope>';
            $response['message'] = '';
        } else {
            //no search id and unauthorized access
            $response['status'] = 0;
            $response['data'] = '';
            $response['message'] = 'Invalid request data for request method GetAvailablePlansOTAWithRiders ';
        }
        return $response;
    }

    private function formatFlightInformationToXML($searchData = array(), $segmentDetails = array())
    {
        $request = '';
        $tripType = $searchData['data']['trip_type'];
        if ($tripType != 'circle') {
            foreach ($segmentDetails as $k => $v) {
                foreach ($v as $s_k => $s_v) {
                    $departureCityName = $segmentDetails[$k][$s_k]['OriginDetails']['CityName'];
                    $departureCountryCode = $this->CI->insurance_model->getCountryDetailsFromCityName($departureCityName);
                    $arrivalCityName = $segmentDetails[$k][$s_k]['DestinationDetails']['CityName'];
                    $arrivalCountryCode = $this->CI->insurance_model->getCountryDetailsFromCityName($arrivalCityName);
                    $departureDateTime = $segmentDetails[$k][$s_k]['OriginDetails']['DateTime'];
                    $departureAirlineCode = $segmentDetails[$k][$s_k]['AirlineDetails']['AirlineCode'];
                    $departureFlightNumber = $segmentDetails[$k][$s_k]['AirlineDetails']['FlightNumber'];
                    $request .= "
                    <Flights>
                <DepartCountryCode>$departureCountryCode</DepartCountryCode>
                <DepartStationCode></DepartStationCode>
                <ArrivalCountryCode>$arrivalCountryCode</ArrivalCountryCode>
                <ArrivalStationCode></ArrivalStationCode>
                <DepartAirlineCode>$departureAirlineCode</DepartAirlineCode>
                <DepartDateTime>$departureDateTime</DepartDateTime>
                <ReturnAirlineCode></ReturnAirlineCode>
                <ReturnDateTime></ReturnDateTime>
                <DepartFlightNo>$departureFlightNumber</DepartFlightNo>
                <ReturnFlightNo></ReturnFlightNo>
                </Flights>";
                }
            }
        } else {
            $segmentDetails[1] = array_reverse($segmentDetails[1]);
            foreach ($segmentDetails[0] as $k => $v) {
                $departureCityName = $segmentDetails[0][$k]['OriginDetails']['CityName'];
                $departureCountryCode = $this->CI->insurance_model->getCountryDetailsFromCityName($departureCityName);
                $arrivalCityName = $segmentDetails[0][$k]['DestinationDetails']['CityName'];

                $arrivalCountryCode = $this->CI->insurance_model->getCountryDetailsFromCityName($arrivalCityName);
                $departureDateTime = $segmentDetails[0][$k]['OriginDetails']['DateTime'];
                $departureAirlineCode = $segmentDetails[0][$k]['AirlineDetails']['AirlineCode'];
                $departureFlightNumber = $segmentDetails[0][$k]['AirlineDetails']['FlightNumber'];
                $returnAirlineCode = $segmentDetails[1][$k]['AirlineDetails']['AirlineCode'];
                $returnFlightNumber = $segmentDetails[1][$k]['AirlineDetails']['FlightNumber'];
                $returnDateTime = $segmentDetails[1][$k]['OriginDetails']['DateTime'];
                $request .= "
                <Flights>
                    <DepartCountryCode>$departureCountryCode</DepartCountryCode>
                    <DepartStationCode></DepartStationCode>
                    <ArrivalCountryCode>$arrivalCountryCode</ArrivalCountryCode>
                    <ArrivalStationCode></ArrivalStationCode>
                    <DepartAirlineCode>$departureAirlineCode</DepartAirlineCode>
                    <DepartDateTime>$departureDateTime</DepartDateTime>
                    <ReturnAirlineCode>$returnAirlineCode</ReturnAirlineCode>
                    <ReturnDateTime>$returnDateTime</ReturnDateTime>
                    <DepartFlightNo>$departureFlightNumber</DepartFlightNo>
                    <ReturnFlightNo>$returnFlightNumber</ReturnFlightNo>
                    </Flights>";
            }
        }
        // $request = '
        //     <Flights>
        //     <DepartCountryCode>NP</DepartCountryCode>
        //     <DepartStationCode></DepartStationCode>
        //     <ArrivalCountryCode>IN</ArrivalCountryCode>
        //     <ArrivalStationCode></ArrivalStationCode>
        //     <DepartAirlineCode>AI</DepartAirlineCode>
        //     <DepartDateTime>2025-01-01 06:30:00</DepartDateTime>
        //     <ReturnAirlineCode></ReturnAirlineCode>
        //     <ReturnDateTime></ReturnDateTime>
        //     <DepartFlightNo>638</DepartFlightNo>
        //     <ReturnFlightNo></ReturnFlightNo>
        // </Flights>';

        return $request;
    }
    private function formatFlightInformationToXMLForPurchase($searchData = array(), $segmentDetails = array())
    {

        $tripType = $searchData['data']['trip_type'];
        $request = '<Flights>';
        if ($tripType != 'circle') {
            foreach ($segmentDetails as $k => $v) {
                foreach ($v as $s_k => $s_v) {
                    $departureCityName = $segmentDetails[$k][$s_k]['OriginDetails']['CityName'];
                    $departureCountryCode = $this->CI->insurance_model->getCountryDetailsFromCityName($departureCityName);
                    $arrivalCityName = $segmentDetails[$k][$s_k]['DestinationDetails']['CityName'];
                    $arrivalCountryCode = $this->CI->insurance_model->getCountryDetailsFromCityName($arrivalCityName);
                    $departureDateTime = $segmentDetails[$k][$s_k]['OriginDetails']['DateTime'];
                    $departureAirlineCode = $segmentDetails[$k][$s_k]['AirlineDetails']['AirlineCode'];
                    $departureFlightNumber = $segmentDetails[$k][$s_k]['AirlineDetails']['FlightNumber'];
                    $request .= "<Flight>
                <DepartCountryCode>$departureCountryCode</DepartCountryCode>
                <DepartStationCode></DepartStationCode>
                <ArrivalCountryCode>$arrivalCountryCode</ArrivalCountryCode>
                <ArrivalStationCode></ArrivalStationCode>
                <DepartAirlineCode>$departureAirlineCode</DepartAirlineCode>
                <DepartDateTime>$departureDateTime</DepartDateTime>
                <ReturnAirlineCode></ReturnAirlineCode>
                <ReturnDateTime></ReturnDateTime>
                <DepartFlightNo>$departureFlightNumber</DepartFlightNo>
                <ReturnFlightNo></ReturnFlightNo></Flight>";
                }
            }
        } else {
            $segmentDetails[1] = array_reverse($segmentDetails[1]);
            foreach ($segmentDetails[0] as $k => $v) {
                $departureCityName = $segmentDetails[0][$k]['OriginDetails']['CityName'];
                $departureCountryCode = $this->CI->insurance_model->getCountryDetailsFromCityName($departureCityName);
                $arrivalCityName = $segmentDetails[0][$k]['DestinationDetails']['CityName'];

                $arrivalCountryCode = $this->CI->insurance_model->getCountryDetailsFromCityName($arrivalCityName);
                $departureDateTime = $segmentDetails[0][$k]['OriginDetails']['DateTime'];
                $departureAirlineCode = $segmentDetails[0][$k]['AirlineDetails']['AirlineCode'];
                $departureFlightNumber = $segmentDetails[0][$k]['AirlineDetails']['FlightNumber'];
                $returnAirlineCode = $segmentDetails[1][$k]['AirlineDetails']['AirlineCode'];
                $returnFlightNumber = $segmentDetails[1][$k]['AirlineDetails']['FlightNumber'];
                $returnDateTime = $segmentDetails[1][$k]['OriginDetails']['DateTime'];
                $request .= "
                <Flight>
                    <DepartCountryCode>$departureCountryCode</DepartCountryCode>
                    <DepartStationCode></DepartStationCode>
                    <ArrivalCountryCode>$arrivalCountryCode</ArrivalCountryCode>
                    <ArrivalStationCode></ArrivalStationCode>
                    <DepartAirlineCode>$departureAirlineCode</DepartAirlineCode>
                    <DepartDateTime>$departureDateTime</DepartDateTime>
                    <ReturnAirlineCode>$returnAirlineCode</ReturnAirlineCode>
                    <ReturnDateTime>$returnDateTime</ReturnDateTime>
                    <DepartFlightNo>$departureFlightNumber</DepartFlightNo>
                    <ReturnFlightNo>$returnFlightNumber</ReturnFlightNo>
                    </Flight>";
            }
        }
        $request .= '</Flights>';

        return $request;
    }





    private function process_GetAvailablePlansOTAWithRidersResponse(array $rawApiResponse)
    {
        $xmlStartPosition = strpos($rawApiResponse['data'], '<?xml');
        $xmlResponse = substr($rawApiResponse['data'], $xmlStartPosition);
        //conversion of xml to array starts here
        $xml = simplexml_load_string($xmlResponse);
        $rawResponseArray = $this->convertToArray($xml->asXML());
        if (empty($rawResponseArray['soap:Envelope']['soap:Body']['GetAvailablePlansOTAWithRidersResponse']['GenericResponse']['ErrorCode']) || $rawResponseArray['soap:Envelope']['soap:Body']['GetAvailablePlansOTAWithRidersResponse']['GenericResponse']['ErrorCode'] == 0) {
            // all good
            $availablePlans = $rawResponseArray['soap:Envelope']['soap:Body']['GetAvailablePlansOTAWithRidersResponse']['GenericResponse']['AvailablePlans']['AvailablePlan'];
            foreach ($availablePlans as $plan) {
                $planType = '';
                if (strpos($plan['PlanTitle'], 'Gold') !== false) {
                    $planType = 'goldPlans';
                } elseif (strpos($plan['PlanTitle'], 'Silver') !== false) {
                    $planType = 'silverPlans';
                } elseif (strpos($plan['PlanTitle'], 'Platinum') !== false) {
                    $planType = 'platinumPlans';
                }

                if ($planType !== '') {
                    $premiumType = ($plan['PlanPremiumChargeType'] === 'PerPassenger') ? 'perPassengerPlans' : 'familyPlans';
                    $sortedPlans[$premiumType][$planType][] = [
                        'SSRFeeCode'=>$plan['SSRFeeCode'],
                        'PlanCode' => $plan['PlanCode'],
                        'PlanTitle' => $plan['PlanTitle'],
                        'PlanType'=>$planType,
                        'CurrencyCode' => $plan['CurrencyCode'],
                        'TotalPremiumAmount' => $plan['TotalPremiumAmount'],
                        'PlanContent' => $plan['PlanContent'],
                        'MinAge' => $plan['PlanPricingBreakdown']['PricingBreakdown']['MinAge'],
                        'MaxAge' => $plan['PlanPricingBreakdown']['PricingBreakdown']['MaxAge'],
                        'Gender' => ($plan['PlanPricingBreakdown']['PricingBreakdown']['Gender'] === 'B') ? "Both" : (($plan['PlanPricingBreakdown']['PricingBreakdown']['Gender'] === 'M') ? "Male" : "Female"),
                        'BaseInsurancePrice' => $plan['PlanPricingBreakdown']['PricingBreakdown']['PremiumBreakdown']['PremiumCharges']['Charges']['Charge'][0]['AmountValue'],
                        'VATDetails' => [
                            'Amount' => $plan['PlanPricingBreakdown']['PricingBreakdown']['PremiumBreakdown']['PremiumCharges']['Charges']['Charge'][1]['AmountValue'],
                            'Percentage' => $plan['PlanPricingBreakdown']['PricingBreakdown']['PremiumBreakdown']['PremiumCharges']['Charges']['Charge'][1]['PercentageValue']
                        ]
                    ];
                }
            }
            $response['PlanDetails'] = $sortedPlans;
            $response['status'] = 1;
        } else {
            // error code is set
            $response['status'] = 0;
            $response['errorCode'] = $rawResponseArray['soap:Envelope']['soap:Body']['GetAvailablePlansOTAWithRidersResponse']['GenericResponse']['ErrorCode'];
            $response['errorMessage'] = $rawResponseArray['soap:Envelope']['soap:Body']['GetAvailablePlansOTAWithRidersResponse']['GenericResponse']['ErrorMessage'];
            $this->insuranceLogger($response['errorCode'], 'process_GetAvailablePlansOTAWithRidersResponse',  $response['errorMessage'], 'GetAvailablePlansOTAWithRidersResponse', 'error', $response['status']);
        }
        return $response;
    }
private function process_ConfirmPurchaseResponse(Array $rawApiResponse){
    $xmlStartPosition = strpos($rawApiResponse['data'], '<?xml');
        $xmlResponse = substr($rawApiResponse['data'], $xmlStartPosition);
        //conversion of xml to array starts here
        $xml = simplexml_load_string($xmlResponse);
        $rawResponseArray = $this->convertToArray($xml->asXML());
        if (empty($rawResponseArray['soap:Envelope']['soap:Body']['ConfirmPurchaseResponse']['PurchaseResponse']['ErrorCode']) || $rawResponseArray['soap:Envelope']['soap:Body']['GetAvailablePlansOTAWithRidersResponse']['GenericResponse']['ErrorCode'] == 0){
            $response['status'] = $rawResponseArray['soap:Envelope']['soap:Body']['ConfirmPurchaseResponse']['PurchaseResponse']['ProposalState'];
            $response['data']['iteneraryId'] = $rawResponseArray['soap:Envelope']['soap:Body']['ConfirmPurchaseResponse']['PurchaseResponse']['ItineraryID'];
            $response['data']['pnr'] = $rawResponseArray['soap:Envelope']['soap:Body']['ConfirmPurchaseResponse']['PurchaseResponse']['PNR']; 
            $response['data']['policyNumber'] = $rawResponseArray['soap:Envelope']['soap:Body']['ConfirmPurchaseResponse']['PurchaseResponse']['PolicyNo'];
            $response['data']['policyPurchasedDateTime'] = $rawResponseArray['soap:Envelope']['soap:Body']['ConfirmPurchaseResponse']['PurchaseResponse']['PolicyPurchasedDateTime']; 
            $response['data']['ConfirmedPassengerDetails'] = $rawResponseArray['soap:Envelope']['soap:Body']['ConfirmPurchaseResponse']['PurchaseResponse']['ConfirmedPassengers'];
            $response['data']['policyUrl'] = $rawResponseArray['soap:Envelope']['soap:Body']['ConfirmPurchaseResponse']['PurchaseResponse']['ConfirmedPassengers']['ConfirmedPassenger']['PolicyURLLink'];

        }else{
            $response['status'] = 'FAILED';
            $response['errorCode'] = $rawResponseArray['soap:Envelope']['soap:Body']['GetAvailablePlansOTAWithRidersResponse']['GenericResponse']['ErrorCode'];
            $response['errorMessage'] = $rawResponseArray['soap:Envelope']['soap:Body']['GetAvailablePlansOTAWithRidersResponse']['GenericResponse']['ErrorMessage'];
            $this->insuranceLogger($response['errorCode'], 'process_ConfirmPurchaseResponse',  $response['errorMessage'], 'ConfirmPurchase', 'error', $response['status']);

        }
return $response;
}
    private function convertToArray($xmlStr, $get_attributes = 1, $priority = 'tag'): array
    {

        $contents = "";
        if (!function_exists('xml_parser_create')) {
            return array();
        }
        $parser = xml_parser_create('');

        xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8");
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($parser, trim($xmlStr), $xml_values);
        xml_parser_free($parser);
        if (!$xml_values)
            return array();
        $xml_array = array();
        $parents = array();
        $opened_tags = array();
        $arr = array();
        $current = &$xml_array;
        $repeated_tag_index = array();
        foreach ($xml_values as $data) {
            unset($attributes, $value);

            extract($data);
            $result = array();
            $attributes_data = array();
            if (isset($value)) {
                if ($priority == 'tag')
                    $result = $value;
                else
                    $result['value'] = $value;
            }
            if (isset($attributes) and $get_attributes) {
                foreach ($attributes as $attr => $val) {
                    if ($priority == 'tag')
                        $attributes_data[$attr] = $val;
                    else
                        $result['attr'][$attr] = $val;
                }
            }
            if ($type == "open") {
                $parent[$level - 1] = &$current;
                if (!is_array($current) or (!in_array($tag, array_keys($current)))) {
                    $current[$tag] = $result;
                    if ($attributes_data)
                        $current[$tag . '_attr'] = $attributes_data;
                    $repeated_tag_index[$tag . '_' . $level] = 1;
                    $current = &$current[$tag];
                } else {
                    if (isset($current[$tag][0])) {
                        $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                        $repeated_tag_index[$tag . '_' . $level]++;
                    } else {
                        $current[$tag] = array(
                            $current[$tag],
                            $result
                        );
                        $repeated_tag_index[$tag . '_' . $level] = 2;
                        if (isset($current[$tag . '_attr'])) {
                            $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                            unset($current[$tag . '_attr']);
                        }
                    }
                    $last_item_index = $repeated_tag_index[$tag . '_' . $level] - 1;
                    $current = &$current[$tag][$last_item_index];
                }
            } elseif ($type == "complete") {
                if (!isset($current[$tag])) {
                    $current[$tag] = $result;
                    $repeated_tag_index[$tag . '_' . $level] = 1;
                    if ($priority == 'tag' and $attributes_data)
                        $current[$tag . '_attr'] = $attributes_data;
                } else {
                    if (isset($current[$tag][0]) and is_array($current[$tag])) {
                        $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                        if ($priority == 'tag' and $get_attributes and $attributes_data) {
                            $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                        }
                        $repeated_tag_index[$tag . '_' . $level]++;
                    } else {
                        $current[$tag] = array(
                            $current[$tag],
                            $result
                        );
                        $repeated_tag_index[$tag . '_' . $level] = 1;
                        if ($priority == 'tag' and $get_attributes) {
                            if (isset($current[$tag . '_attr'])) {
                                $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                                unset($current[$tag . '_attr']);
                            }
                            if ($attributes_data) {
                                $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                            }
                        }
                        $repeated_tag_index[$tag . '_' . $level]++;
                    }
                }
            } elseif ($type == 'close') {
                $current = &$parent[$level - 1];
            }
        }

        return ($xml_array);
    }
    private function insuranceLogger($id, $request, $response, $methodName, $logType, $status) {
        $log_entry = "\n========== BEGIN REQUEST/RESPONSE ==========\n";
        $log_entry .= "$logType ID: $id\n";
        $log_entry .= "Request Method Name: $methodName\n";
        $log_entry .= "Response Status: $status\n";
        $log_entry .= "Timestamp: " . date('Y-m-d H:i:s') . "\n";
        $log_entry .= "----------BEGIN REQUEST ----------\n";
        $log_entry .= $request . "\n";
        $log_entry .= "----------END REQUEST ----------\n";
        $log_entry .= "----------BEGIN RESPONSE ----------\n";
        $log_entry .= $response . "\n";
        $log_entry .= "----------END RESPONSE ----------\n";
        $log_entry .= "========== END REQUEST/RESPONSE ==========\n";


        $log_dir = BASEPATH . "../logs/insurance_logs/protect_logs";
        $log_file = "{$log_dir}/" . "{$logType}_log" . '.log';
    
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
