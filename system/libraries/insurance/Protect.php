<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once('InsuranceInterface.php');
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
            $this->url = PROTECT_LIVE_CHANNEL_CODE;
            $this->channelCode = PROTECT_LIVE_CHANNEL_CODE;
        }
    }
    public function getFormattedHeader(array $headerData): array
    {
        if (valid_array($headerData) == true && $headerData !== '' && $headerData !== NULL && (isset($headerData['status']) && $headerData['status'] === 1)) {
            $headerName = $headerData['headerName'];
            $rawHeaderData = $headerData['rawHeaderData'];
            switch ($headerName) {
                case 'GetAvailablePlansOTAWithRiders':
                    $formattedHeaderData = $this->get_GetAvailablePlansOTAWithRiders_Request_Header($rawHeaderData);
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
            $rawRequestData = $requestData['data'];
            switch ($requestName) {
                case 'GetAvailablePlansOTAWithRiders':
                    $formattedApiRequest = $this->get_GetAvailablePlansOTAWithRiders_Request($rawRequestData);
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
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_USERPWD, $this->userName . ":" . $this->password);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request['data']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            $rawApiResponse = curl_exec($ch);
            if (curl_errno($ch)) {
                // request couldn't be sent
                $response['message'] = 'Couldn\'t send request: ' . curl_error($ch);
                $response['data'] = $rawApiResponse;
                $response['status'] = 0;
            } else {
                $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                if ($resultStatus == 200) {
                    //good response
                    $response['message'] = '';
                    $response['data'] = $rawApiResponse;
                    $response['status'] = 1;
                } else {
                    //server responded with a http error
                    $response['message'] = 'Request failed: HTTP status code: ' . $resultStatus;
                    $response['data'] = $rawApiResponse;
                    $response['status'] = 0;
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
            $rawApiResponseData = $rawApiResponse['data'];
            switch ($apiMethodName) {
                case 'GetAvailablePlansOTAWithRiders':
                    $response = $this->process_GetAvailablePlansOTAWithRidersResponse($rawApiResponseData);
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

    private function get_GetAvailablePlansOTAWithRiders_Request_Header($headerData): array
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
            <Currency>AED</Currency>
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



    private function get_GetAvailablePlansOTAWithRiders_Request($request = array()): array
    {
        if ((is_array($request) == true) && isset($request['status']) && $request['status'] === 1) {
            $response['status'] = 1;
            $searchData = $request['data']['search_data'];
            $segmentDetails = $request['data']['segment_details'];
            $header = $request['data']['header'];
            $authHeader = $this->getAuthHeader();
            $currentFlightInformation = $this->formatFlightInformationToXML($searchData, $segmentDetails);
            $response['data'] = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:web="http://ZEUSTravelInsuranceGateway/WebServices">
            <soapenv:Header/>
            <soapenv:Body>
                <GetAvailablePlansOTAWithRiders xmlns="http://ZEUSTravelInsuranceGateway/WebServices">
                <GenericRequestOTALite>'
                . $authHeader .
                $header['data'] .
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

    private function formatFlightInformationToXML($searchData = array(), $segmentDetails = array())
    {

        $tripType = $searchData['data']['trip_type'];
        $request = '<flights>';
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
                <Flight>
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
             </Flight>";
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
        $request = '
            <Flights>
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
        
        return $request;
    }






    private function process_GetAvailablePlansOTAWithRidersResponse(array $rawApiResponse)
    {
        $xmlStartPosition = strpos($rawApiResponse['response'], '<?xml');
        $xmlResponse = substr($rawApiResponse['response'], $xmlStartPosition);
        //conversion of xml to array starts here
        $xml = simplexml_load_string($xmlResponse);
        $rawResponseArray = $this->convertToArray($xml->asXML());
        if (empty($rawResponseArray['soap:Envelope']['soap:Body']['GetAvailablePlansOTAWithRidersResponse']['GenericResponse']['ErrorCode']) && $rawResponseArray['soap:Envelope']['soap:Body']['GetAvailablePlansOTAWithRidersResponse']['GenericResponse']['ErrorCode'] == 0) {
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
                        'PlanCode' => $plan['PlanCode'],
                        'PlanTitle' => $plan['PlanTitle'],
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
            $response['errorCode'] = $rawResponseArray['soap:Envelope']['soap:Body']['GetAvailablePlansOTAWithRidersResponse']['GenericResponse']['ErrorCode'];
            $response['ErrorMessage'] = $rawResponseArray['soap:Envelope']['soap:Body']['GetAvailablePlansOTAWithRidersResponse']['GenericResponse']['ErrorMessage'];
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
}
