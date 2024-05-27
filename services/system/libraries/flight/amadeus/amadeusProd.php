<?php
require_once BASEPATH . 'libraries/flight/Common_api_flight.php';
class Amadeus extends Common_Api_Flight
{
    var $master_search_data;
    var $search_hash;
    protected $token;
    private $end_user_ip = '127.0.0.1';
    var $api_session_id;
    //changes added new variables for fm exemption according to origin
    public $fm_origin = '';
    protected $fm_exempt_status;
    function __construct()
    {
        parent::__construct(META_AIRLINE_COURSE, AMADEUS_FLIGHT_BOOKING_SOURCE);
        $this->CI = &get_instance();
        $this->CI->load->library('Converter');
        $this->CI->load->library('ArrayToXML');
        $this->set_api_credentials();
        $this->CI->load->model('custom_db');
        //$this->set_api_session_id();
    }
    /**
     * Setting Api Credentials
     */
    private function set_api_credentials()
    {
        $this->wsap = trim($this->config['WSAP']);
        $this->api_url = trim($this->config['Api_URL']);
        $this->username = trim($this->config['Username']);
        $this->password = trim($this->config['Password']);
        $this->pos_type = trim($this->config['POS_Type']);
        $this->pseudo_city_code = trim($this->config['PseudoCityCode']);
        //$this->pseudo_city_code = trim("SNA1S210I");        
        $this->agent_duty_code = trim($this->config['AgentDutyCode']);
        $this->requestor_type = trim($this->config['RequestorType']);
        $this->created = $this->getCreateDate();
        $this->nonce = $this->getNoncevalue();
        $this->hashPwd = $this->DigestAlgo($this->password, $this->created, $this->nonce);
        $this->soap_url = 'http://webservices.amadeus.com/';
    }
    /**
     * Setting Session ID
     */
    public function set_api_session_id($authentication_response = '')
    {

        if (empty($this->api_session_id) == true) {

            if (empty($authentication_response) == false) {

                $authentication_response = Converter::createArray($authentication_response);
                // debug($authentication_response);exit;
                //store in database

                if ($this->valid_create_session_response($authentication_response)) {
                    $authenticate_token = $authentication_response['soap-env:Envelope']['soap-env:Header']['wsse:Security']['wsse:BinarySecurityToken']['@value'];
                    $session_id = trim($authenticate_token);
                    $this->CI->api_model->update_api_session_id($this->booking_source, $session_id);
                }
            } else {

                $session_expiry_time = 2; //In minutes

                $session_id = $this->CI->api_model->get_api_session_id($this->booking_source, $session_expiry_time);

                if (empty($session_id) == true) {

                    $authentication_request = $this->get_authentication_request(true);
                    if ($authentication_request['status'] == SUCCESS_STATUS) {
                        $authentication_request = $authentication_request['data'];
                        $authentication_response = $this->process_request($authentication_request['request'], $authentication_request['url'], $authentication_request['remarks']);
                        // debug($authentication_response);exit;
                        $this->set_api_session_id($authentication_response);
                    }
                }
            }
            if (empty($session_id) == false) {
                $this->api_session_id = $session_id;
            }
        }
    }
    public function valid_create_session_response($response)
    {
        $result = false;
        if (isset($response['soap-env:Envelope']['soap-env:Header']['wsse:Security']['wsse:BinarySecurityToken']['@value']) == true) {
            $result =  true;
        }
        return $result;
    }

    public function search_data($search_id)
    {
        $response['status'] = true;
        $response['data'] = array();
        if (empty($this->master_search_data) == true and valid_array($this->master_search_data) == false) {
            $clean_search_details = $this->CI->flight_model->get_safe_search_data($search_id);

            if ($clean_search_details['status'] == true) {
                $response['status'] = true;
                $response['data'] = $clean_search_details['data'];

                // 28/12/2014 00:00:00 - date format
                if ($clean_search_details['data']['trip_type'] == 'multicity') {
                    $response['data']['from_city'] = $clean_search_details['data']['from'];
                    $response['data']['to_city'] = $clean_search_details['data']['to'];
                    $response['data']['depature'] = $clean_search_details['data']['depature'];
                    $response['data']['return'] = $clean_search_details['data']['depature'];
                } else {
                    $response['data']['from'] = substr(chop(substr($clean_search_details['data']['from'], -5), ')'), -3);
                    $response['data']['to'] = substr(chop(substr($clean_search_details['data']['to'], -5), ')'), -3);
                    $response['data']['depature'] = date("Y-m-d", strtotime($clean_search_details['data']['depature'])) . 'T00:00:00';
                    if (isset($clean_search_details['data']['return'])) {
                        $response['data']['return'] = date("Y-m-d", strtotime($clean_search_details['data']['return'])) . 'T00:00:00';
                    }
                }
                if (is_array($response['data']['from'])) {
                    if (in_array('KTM', $response['data']['from'])) {
                        $this->fm_origin = 'KTM';
                    }
                } else {
                    $this->fm_origin = $response['data']['from'];
                }



                switch ($clean_search_details['data']['trip_type']) {

                    case 'oneway':
                        $response['data']['type'] = 'OneWay';
                        break;

                    case 'circle':
                        $response['data']['type'] = 'Return';
                        $response['data']['return'] = date("Y-m-d", strtotime($clean_search_details['data']['return'])) . 'T00:00:00';
                        break;

                    default:
                        $response['data']['type'] = 'OneWay';
                }
                if ($response['data']['is_domestic'] == true and $response['data']['trip_type'] == 'return') {
                    $response['data']['domestic_round_trip'] = true;
                    //$response ['status'] = false;
                } else {
                    $response['data']['domestic_round_trip'] = false;
                }
                $response['data']['adult'] = $clean_search_details['data']['adult_config'];
                $response['data']['child'] = $clean_search_details['data']['child_config'];
                $response['data']['infant'] = $clean_search_details['data']['infant_config'];
                $response['data']['v_class'] = @$clean_search_details['data']['v_class'];
                $response['data']['carrier'] = implode($clean_search_details['data']['carrier']);
                $this->master_search_data = $response['data'];
            } else {
                $response['status'] = false;
            }
        } else {
            $response['data'] = $this->master_search_data;
        }
        $this->search_hash = md5(serialized_data($response['data']));

        return $response;
    }
    /**
     * Search Request
     * @param unknown_type $search_id
     */
    public function get_search_request($search_id, $country)
    {

        $response['status'] = FAILURE_STATUS; // Status Of Operation
        $response['message'] = ''; // Message to be returned
        $response['data'] = array(); // Data to be returned       

        /* get search criteria based on search id */
        $search_data = $this->search_data($search_id);
        if ($search_data['status'] == SUCCESS_STATUS) {
            // Flight search RQ
            $search_request = $this->search_request($search_data['data'], $country);
            if ($search_request['status'] = SUCCESS_STATUS) {
                $response['status'] = SUCCESS_STATUS;
                $curl_request = $this->form_curl_params($search_request['request'], $search_request['url'], $search_request['soap_url']);
                $response['data'] = $curl_request['data'];
            }
        }
        // debug($response);exit;
        return $response;
    }
    public function get_flexible_search_request($search_id)
    {

        $response['status'] = FAILURE_STATUS; // Status Of Operation
        $response['message'] = ''; // Message to be returned
        $response['data'] = array(); // Data to be returned       

        /* get search criteria based on search id */
        $search_data = $this->search_data($search_id);

        if ($search_data['status'] == SUCCESS_STATUS) {
            // Flight search RQ
            $search_request = $this->search_flexible_request($search_data['data']);
            if ($search_request['status'] = SUCCESS_STATUS) {
                $response['status'] = SUCCESS_STATUS;
                $curl_request = $this->form_curl_params($search_request['request'], $search_request['url'], $search_request['soap_url']);
                $response['data'] = $curl_request['data'];
            }
        }
        // debug($response);exit;
        return $response;
    }

    private function search_flexible_request($search_data)
    {

        $response['status'] = SUCCESS_STATUS;
        $response['data']   = array();
        $search_params = $search_data;

        $trip_type = $search_params['trip_type'];
        $segref = '';
        $from = array();
        $to = array();
        $depature = array();
        $return = array();

        $corporate_code_text = '';
        $price_type_corporate = '';
        if (isset($search_params['corporate_codes'])) {
            if ($search_params['corporate_codes']) {
                $price_type_corporate .= '<priceType>RW</priceType>';
                $corporate_arr_code = $search_params['corporate_codes'];
                $corporate_code_text = '<corporate>
                    <corporateId>
                        <corporateQualifier>RW</corporateQualifier>';
                foreach ($corporate_arr_code as $cv) {
                    $corporate_code_text .= '<identity>' . $cv . '</identity>';
                }
                $corporate_code_text .= '</corporateId>
                </corporate>';
            }
        }

        if ($trip_type !== 'multicity') {
            $depature[] = $search_params['depature'];
            $from[] = $search_params['from'];
            $to[] = $search_params['to'];
            if ($trip_type == 'return') {
                $from[] = $search_params['to'];
                $to[] = $search_params['from'];
                $depature[] = $search_params['return'];
            }
        } else {
            $depature = $search_params['depature'];
            $from = $search_params['from'];
            $to = $search_params['to'];
        }
        $seg_count = 1;
        foreach ($from as $key => $value) {
            $segref .= '<itinerary>
                    <requestedSegmentRef>
                        <segRef>' . $seg_count . '</segRef>
                    </requestedSegmentRef>
                    <departureLocalization>
                        <departurePoint>                            
                            <locationId>' . $value . '</locationId>
                        </departurePoint>
                    </departureLocalization>
                    <arrivalLocalization>
                        <arrivalPointDetails>                           
                            <locationId>' . $to[$key] . '</locationId>
                        </arrivalPointDetails>
                    </arrivalLocalization>
                    <timeDetails>
                        <firstDateTimeDetail>
                            <date>' . date('dmy', strtotime($depature[$key])) . '</date>
                        </firstDateTimeDetail>';
            if ($search_params['flexible_dates'] == 'flexible_dates') {
                $segref .= '<rangeOfDate>
                                <rangeQualifier>C</rangeQualifier>
                                <dayInterval>3</dayInterval>
                            </rangeOfDate>';
            }
            $segref .= '</timeDetails>
                </itinerary>';
            $seg_count++;
        }
        //only adult + child not infant
        $total_pax = $search_params['adult_config'] + $search_params['child_config'];
        $paxTag_reference = '';
        $paxTagCHD = '';
        $paxTagINF = '';
        $paxTag = $this->get_paxref_search_req($search_params['adult_config'], 'ADT', 1);
        $paxTagADT = $paxTag['paxtag'];
        $paxTag_reference .= '<paxReference>' . $paxTagADT . '</paxReference>';
        if ($search_params['child_config'] > 0) {
            $paxTagc = $this->get_paxref_search_req($search_params['child_config'], 'CH', $paxTag['paxRef']);
            $paxTagCHD = $paxTagc['paxtag'];
            $paxTag_reference .= '<paxReference>' . $paxTagCHD . '</paxReference>';
        }
        if ($search_params['infant_config'] > 0) {
            $paxTagi = $this->get_paxref_search_req($search_params['infant_config'], 'INF', 1);
            $paxTagINF = $paxTagi['paxtag'];
            $paxTag_reference .= '<paxReference>' . $paxTagINF . '</paxReference>';
        }

        $class_code  = $this->get_search_class_code(strtolower($search_params['cabin_class']));
        $cabin_text_value = '<cabinId><cabinQualifier>RC</cabinQualifier><cabin>' . $class_code . '</cabin></cabinId>';
        $soapAction = "FMPTBQ_18_1_1A"; // //FMPTBQ_17_4_1A
        //$soapAction = "FMPCAQ_16_3_1A";
        $xml_query = '
            <?xml version="1.0" encoding="UTF-8"?>           
            <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:sec="http://xml.amadeus.com/2010/06/Security_v1" xmlns:typ="http://xml.amadeus.com/2010/06/Type">
            <soapenv:Header>
            <add:MessageID xmlns:add="http://www.w3.org/2005/08/addressing">' . $this->getuuid() . '</add:MessageID>
            <add:Action xmlns:add="http://www.w3.org/2005/08/addressing">http://webservices.amadeus.com/' . $soapAction . '</add:Action>
            <add:To xmlns:add="http://www.w3.org/2005/08/addressing">' . $this->api_url . '</add:To>
            <link:TransactionFlowLink xmlns:link="http://wsdl.amadeus.com/2010/06/ws/Link_v1"/>
            <oas:Security xmlns:oas="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
            <oas:UsernameToken oas1:Id="UsernameToken-1" xmlns:oas1="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
            <oas:Username>' . $this->username . '</oas:Username>
            <oas:Nonce EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary">' . $this->nonce . '</oas:Nonce>
            <oas:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest">' . $this->hashPwd . '</oas:Password>
            <oas1:Created>' . $this->created . '</oas1:Created>
        </oas:UsernameToken>
        </oas:Security>
        <AMA_SecurityHostedUser xmlns="http://xml.amadeus.com/2010/06/Security_v1">
        <UserID AgentDutyCode="' . $this->agent_duty_code . '" RequestorType="' . $this->requestor_type . '" PseudoCityCode="' . $this->pseudo_city_code . '" POS_Type="' . $this->pos_type . '"/>
        </AMA_SecurityHostedUser>
        </soapenv:Header>
        <soapenv:Body>

        <Fare_MasterPricerCalendar xmlns="http://xml.amadeus.com/' . $soapAction . '"> ';
        $xml_query .= ' <numberOfUnit>
                    <unitNumberDetail>
                        <numberOfUnits>' . $total_pax . '</numberOfUnits>
                        <typeOfUnit>PX</typeOfUnit>
                    </unitNumberDetail>
                    <unitNumberDetail>
                        <numberOfUnits>100</numberOfUnits>
                        <typeOfUnit>RC</typeOfUnit>
                    </unitNumberDetail>
                </numberOfUnit>';
        $xml_query .= $paxTag_reference;
        $xml_query .= '<fareOptions>   
                        <pricingTickInfo>
                            <pricingTicketing>
                                <priceType>CUC</priceType>
                                <priceType>RP</priceType>
                                <priceType>RU</priceType>
                                <priceType>TAC</priceType>
                                <priceType>ET</priceType>
                                ' . $price_type_corporate . '
                            </pricingTicketing>
                        </pricingTickInfo>
                        ' . $corporate_code_text . '
                        <conversionRate>
                            <conversionRateDetail>
                                <currency>INR</currency>
                            </conversionRateDetail>
                        </conversionRate>
                    </fareOptions>';
        $xml_query .= '<travelFlightInfo>
                        ' . $cabin_text_value . '
                    </travelFlightInfo>';
        $xml_query .= $segref;
        $xml_query .= '</Fare_MasterPricerCalendar>
        </soapenv:Body>
    </soapenv:Envelope>';
        // echo $xml_query;
        // exit; 
        $request['request'] = $xml_query;
        $request['url'] = $this->api_url;
        $request['soap_url'] = $this->soap_url . $soapAction;
        $request['status'] = SUCCESS_STATUS;
        return $request;
    }
    /**
     * Formates Search Request
     */
    private function search_request($search_data, $country)
    {
        $response['status'] = SUCCESS_STATUS;
        $response['data']   = array();
        $search_params = $search_data;
        $trip_type = $search_params['trip_type'];
        $segref = '';
        $from = array();
        $to = array();
        $depature = array();
        $return = array();
        $corporate_code_text = '';
        $price_type_corporate = '';
        //$search_params['corporate_codes'] = array('042383');
        if (isset($search_params['corporate_codes'])) {
            if ($search_params['corporate_codes']) {
                $price_type_corporate .= '<priceType>RW</priceType>';
                $corporate_arr_code = $search_params['corporate_codes'];
                $corporate_code_text = '<corporate>
                    <corporateId>
                        <corporateQualifier>RW</corporateQualifier>';
                foreach ($corporate_arr_code as $cv) {
                    $corporate_code_text .= '<identity>' . $cv . '</identity>';
                }
                $corporate_code_text .= '</corporateId>
                </corporate>';
            }
        }

        if ($trip_type !== 'multicity') {
            $depature[] = $search_params['depature'];
            $from[] = $search_params['from'];
            $to[] = $search_params['to'];
            if ($trip_type == 'return') {
                $from[] = $search_params['to'];
                $to[] = $search_params['from'];
                $depature[] = $search_params['return'];
            }
        } else {
            $depature = $search_params['depature'];
            $from = $search_params['from'];
            $to = $search_params['to'];
        }

        $seg_count = 1;
        foreach ($from as $key => $value) {
            $segref .= '<itinerary>
                    <requestedSegmentRef>
                        <segRef>' . $seg_count . '</segRef>
                    </requestedSegmentRef>
                    <departureLocalization>
                        <departurePoint>                            
                            <locationId>' . $value . '</locationId>
                        </departurePoint>
                    </departureLocalization>
                    <arrivalLocalization>
                        <arrivalPointDetails>                           
                            <locationId>' . $to[$key] . '</locationId>
                        </arrivalPointDetails>
                    </arrivalLocalization>
                    <timeDetails>
                        <firstDateTimeDetail>
                            <date>' . date('dmy', strtotime($depature[$key])) . '</date>
                        </firstDateTimeDetail>';
            if (@$search_params['flexible_dates'] == 'flexible_dates') {
                $segref .= '<rangeOfDate>
                                <rangeQualifier>C</rangeQualifier>
                                <dayInterval>1</dayInterval>
                            </rangeOfDate>';
            }
            $segref .= '</timeDetails>
                </itinerary>';
            $seg_count++;
        }
        //only adult + child not infant
        $total_pax = $search_params['adult_config'] + $search_params['child_config'];
        $paxTag_reference = '';
        $paxTagCHD = '';
        $paxTagINF = '';
        $paxTag = $this->get_paxref_search_req($search_params['adult_config'], 'ADT', 1);
        $paxTagADT = $paxTag['paxtag'];
        $paxTag_reference .= '<paxReference>' . $paxTagADT . '</paxReference>';
        if ($search_params['child_config'] > 0) {
            $paxTagc = $this->get_paxref_search_req($search_params['child_config'], 'CH', $paxTag['paxRef']);
            $paxTagCHD = $paxTagc['paxtag'];
            $paxTag_reference .= '<paxReference>' . $paxTagCHD . '</paxReference>';
        }
        if ($search_params['infant_config'] > 0) {
            $paxTagi = $this->get_paxref_search_req($search_params['infant_config'], 'INF', 1);
            $paxTagINF = $paxTagi['paxtag'];
            $paxTag_reference .= '<paxReference>' . $paxTagINF . '</paxReference>';
        }

        $class_code  = $this->get_search_class_code(strtolower($search_params['cabin_class']));
        $cabin_text_value = '<cabinId><cabinQualifier>RC</cabinQualifier><cabin>' . $class_code . '</cabin></cabinId>';

        $soapAction = "FMPTBQ_21_4_1A";

        $nq_tax = '';
        //added new condition for checking origin
        if ($country == 'Nepalese' && $this->fm_origin == 'KTM') {
            $this->fm_exempt_status = 1;
            $nq_tax = '<taxInfo>
                <withholdTaxSurcharge>ET</withholdTaxSurcharge>
                <taxDetail>
                   <country>NQ</country>
                   <type>TO</type>
                </taxDetail>
             </taxInfo>';
        }
        $xml_query = '
            <?xml version="1.0" encoding="UTF-8"?>           
            <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:sec="http://xml.amadeus.com/2010/06/Security_v1" xmlns:typ="http://xml.amadeus.com/2010/06/Type">
            <soapenv:Header>
            <add:MessageID xmlns:add="http://www.w3.org/2005/08/addressing">' . $this->getuuid() . '</add:MessageID>
            <add:Action xmlns:add="http://www.w3.org/2005/08/addressing">http://webservices.amadeus.com/' . $soapAction . '</add:Action>
            <add:To xmlns:add="http://www.w3.org/2005/08/addressing">' . $this->api_url . '</add:To>
            <link:TransactionFlowLink xmlns:link="http://wsdl.amadeus.com/2010/06/ws/Link_v1"/>
            <oas:Security xmlns:oas="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
            <oas:UsernameToken oas1:Id="UsernameToken-1" xmlns:oas1="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
            <oas:Username>' . $this->username . '</oas:Username>
            <oas:Nonce EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary">' . $this->nonce . '</oas:Nonce>
            <oas:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest">' . $this->hashPwd . '</oas:Password>
            <oas1:Created>' . $this->created . '</oas1:Created>
        </oas:UsernameToken>
        </oas:Security>
        <AMA_SecurityHostedUser xmlns="http://xml.amadeus.com/2010/06/Security_v1">
        <UserID AgentDutyCode="' . $this->agent_duty_code . '" RequestorType="' . $this->requestor_type . '" PseudoCityCode="' . $this->pseudo_city_code . '" POS_Type="' . $this->pos_type . '"/>
        </AMA_SecurityHostedUser>
        </soapenv:Header>
        <soapenv:Body>
        <Fare_MasterPricerTravelBoardSearch xmlns="http://xml.amadeus.com/' . $soapAction . '"> ';
        $xml_query .= ' <numberOfUnit>
                    <unitNumberDetail>
                        <numberOfUnits>' . $total_pax . '</numberOfUnits>
                        <typeOfUnit>PX</typeOfUnit>
                    </unitNumberDetail>
                    <unitNumberDetail>
                        <numberOfUnits>100</numberOfUnits>
                        <typeOfUnit>RC</typeOfUnit>
                    </unitNumberDetail>
                </numberOfUnit>';
        $xml_query .= $paxTag_reference;
        $xml_query .= '<fareOptions>   
                        <pricingTickInfo>
                            <pricingTicketing>
                                <priceType>CUC</priceType>
                                <priceType>RP</priceType>
                                <priceType>RU</priceType>
                                <priceType>TAC</priceType>
                                <priceType>ET</priceType>
                                ' . $price_type_corporate . '
                            </pricingTicketing>
                        </pricingTickInfo>
                        ' . $corporate_code_text . '
                        <conversionRate>
                            <conversionRateDetail>
                                <currency>NPR</currency>
                            </conversionRateDetail>
                        </conversionRate>
                    </fareOptions>';
        $xml_query .= $nq_tax;
        // For Cabin Class
        /*$xml_query.='<travelFlightInfo>
                        '.$cabin_text_value.'
                    </travelFlightInfo>';*/
        $xml_query .= $segref;
        $xml_query .= '</Fare_MasterPricerTravelBoardSearch>
        </soapenv:Body>
    </soapenv:Envelope>';

        $request['request'] = $xml_query;
        $request['url'] = $this->api_url;
        $request['soap_url'] = $this->soap_url . $soapAction;
        $request['status'] = SUCCESS_STATUS;
        return $request;
    }
    /**
     * Returns Flight List
     * @param unknown_type $search_id
     */
    public function get_flight_list($flight_raw_data, $search_id, $country = '')
    {
        $response['status'] = FAILURE_STATUS; // Status Of Operation
        $response['message'] = ''; // Message to be returned
        $response['data'] = array(); // Data to be returned
        $search_data = $this->search_data($search_id);
        if ($search_data['status'] == SUCCESS_STATUS) {
            $api_response = $this->xml2array($flight_raw_data);

            if ($this->valid_search_result($api_response) == TRUE) {
                $clean_format_data = $this->format_search_data_response($api_response, $search_data['data'], $country);
                if ($clean_format_data) {
                    $response['status'] = SUCCESS_STATUS;
                } else {
                    $response['status'] = FAILURE_STATUS;
                }
            } else {
                $response['status'] = FAILURE_STATUS;
            }

            if ($response['status'] == SUCCESS_STATUS) {
                $response['data'] = $clean_format_data;
            }
        } else {
            $response['status'] = FAILURE_STATUS;
        }
        return $response;
    }
    public function get_flexible_flight_list($flight_raw_data, $search_id)
    {
        $response['status'] = FAILURE_STATUS; // Status Of Operation
        $response['message'] = ''; // Message to be returned
        $response['data'] = array(); // Data to be returned

        $search_data = $this->search_data($search_id);
        if ($search_data['status'] == SUCCESS_STATUS) {
            //$api_response = Converter::createArray($flight_raw_data);
            $api_response = $this->xml2array($flight_raw_data);
            if ($this->valid_search_result($api_response) == TRUE) {
                $clean_format_data = $this->format__flexible_search_data_response($api_response, $search_data['data']);
                if ($clean_format_data) {
                    $response['status'] = SUCCESS_STATUS;
                } else {
                    $response['status'] = FAILURE_STATUS;
                }
            } else {
                $response['status'] = FAILURE_STATUS;
            }

            if ($response['status'] == SUCCESS_STATUS) {
                $response['data'] = $clean_format_data;
            }
        } else {
            $response['status'] = FAILURE_STATUS;
        }
        // debug($response);exit;
        return $response;
    }
    /**
     * Formates Search Response
     * Enter description here ...
     * @param unknown_type $search_result
     * @param unknown_type $search_data
     */
    function format__flexible_search_data_response($search_result, $search_data)
    {
        $currency = $search_result['soapenv:Envelope']['soapenv:Body']['Fare_MasterPricerCalendarReply']['conversionRate']['conversionRateDetail']['currency'];
        $flight_list = $search_result['soapenv:Envelope']['soapenv:Body']['Fare_MasterPricerCalendarReply']['flightIndex'];

        if (!isset($flight_list[0])) {
            $flight_list = array($flight_list);
        }

        $recommendation = $search_result['soapenv:Envelope']['soapenv:Body']['Fare_MasterPricerCalendarReply']['recommendation'];
        $fgrop = array();
        foreach ($flight_list as $fl_key => $fl_val) {
            $group_of_flights = $fl_val['groupOfFlights'];
            if (!isset($group_of_flights[0])) {
                $group_of_flights = array($group_of_flights);
            }
            foreach ($group_of_flights as $group_key => $group_val) {
                $fgrop[$fl_key][$group_val['propFlightGrDetail']['flightProposal'][0]['ref']] = $group_val;
            }
        }
        if (!isset($recommendation[0])) {
            $recommendation = array($recommendation);
        }
        $flight_list = array();
        $i = 0;
        foreach ($recommendation as $recom_key => $recom_val) {
            $price = 0;
            foreach ($recom_val['recPriceInfo']['monetaryDetail'] as $pri_key => $pricing) {
                $price += $pricing['amount'];
            }
            $pricing = array('Currency' => $currency, 'Price' => $price);
            if (!isset($recom_val['segmentFlightRef'][0])) {
                $recom_val['segmentFlightRef'] = array($recom_val['segmentFlightRef']);
            }
            $fl_list = array();
            foreach ($recom_val['segmentFlightRef'] as $seg_ref_key => $seg_ref_val) {
                if (!isset($seg_ref_val['referencingDetail'][0])) {
                    $seg_ref_val['referencingDetail'] = array($seg_ref_val['referencingDetail']);
                }
                $flist = array();
                foreach ($seg_ref_val['referencingDetail'] as $key => $flight_val) {
                    $flist[$key] = $fgrop[$key][$flight_val['refNumber']];
                }
                $fl_list[$i]['flight'] = $flist;
                $fl_list[$i]['price'] = $pricing;
                $i++;
            }
            $flight_list = array_merge($flight_list, $fl_list);
        }
        return $flight_list;
    }
    function format_search_data_response($search_result, $search_data)
    {

        if ($search_data['trip_type'] == 'multicity') {
            $SearchOrigin = $search_data['from'][0];
            $SearchDestination = end($search_data['to']);
        } else {
            $SearchOrigin = $search_data['from'];
            $SearchDestination = $search_data['to'];
        }

        $trip_type = isset($search_data['is_domestic']) && !empty($search_data['is_domestic']) ? 'domestic' : 'international';
        $currency = @$search_result['soapenv:Envelope']['soapenv:Body']['Fare_MasterPricerTravelBoardSearchReply']['conversionRate']['conversionRateDetail']['currency'];
        if (empty($currency)) {
            $currency = $search_result['soapenv:Envelope']['soapenv:Body']['Fare_MasterPricerTravelBoardSearchReply']['conversionRate']['conversionRateDetail'][0]['currency'];
        }
        $Results = $search_result['soapenv:Envelope']['soapenv:Body']['Fare_MasterPricerTravelBoardSearchReply']['flightIndex'];
        $Session_id = $search_result['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SessionId'];
        $SecurityToken = $search_result['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SecurityToken'];
        $SequenceNumber = $search_result['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SequenceNumber'];
        $Recommedation = $search_result['soapenv:Envelope']['soapenv:Body']['Fare_MasterPricerTravelBoardSearchReply']['recommendation'];

        $ServiceFeesGrp = $search_result['soapenv:Envelope']['soapenv:Body']['Fare_MasterPricerTravelBoardSearchReply']['serviceFeesGrp'];
        $ServiceFeesGrp = force_multple_data_format($ServiceFeesGrp);

        $flightDetails = array();
        $Results = force_multple_data_format($Results);
        $Recommedation = force_multple_data_format($Recommedation);
        /*debug($Recommedation);
        exit;*/
        foreach ($Recommedation as $p => $rs_value) {
            $flightDetails['api_name'] = 'Amadeus';
            if (isset($rs_value['itemNumber']['itemNumberId']['numberType'])) {
                $price = $p;
                $flag = "MTK";
            } else {
                $price = 0;
                $flag = "Normal";
            }
            $segmentFlightRef = force_multple_data_format($rs_value['segmentFlightRef']);
            foreach ($segmentFlightRef as $sfr => $sfr_value) {
                $referencingDetail = force_multple_data_format($sfr_value['referencingDetail']);
                foreach ($referencingDetail as $rd => $rd_value) {
                    $refNumber              = $rd_value['refNumber'] . "-" . $flag . "-" . $p;
                    $refNumberFlight        = $rd_value['refNumber'];
                    $refQualifier           = $rd_value['refQualifier'];
                    if (isset($rs_value['itemNumber']['itemNumberId']['numberType'])) {
                        $flightDetails[$refNumber][$price]['PriceInfo']['MultiTicket']          = "Yes";
                        // $flightDetails[$refNumber][$p]['PriceInfo']['MultiTicket_type']      = $s['itemNumber']['itemNumberId']['numberType'];
                        $flightDetails[$refNumber][$price]['PriceInfo']['MultiTicket_number']   = $rs_value['itemNumber']['itemNumberId']['number'];
                    } else {
                        $flightDetails[$refNumber][$price]['PriceInfo']['MultiTicket']          = "No";
                        $flightDetails[$refNumber][$price]['PriceInfo']['MultiTicket_number']   = $rs_value['itemNumber']['itemNumberId']['number'];
                    }
                    $flightDetails[$refNumber][$price]['PriceInfo']['refQualifier']             = $refQualifier;
                    $flightDetails[$refNumber][$price]['PriceInfo']['totalFareAmount']          = $rs_value['recPriceInfo']['monetaryDetail'][0]['amount'];
                    $flightDetails[$refNumber][$price]['PriceInfo']['totalTaxAmount']           = $rs_value['recPriceInfo']['monetaryDetail'][1]['amount'];
                    $paxFareProduct = force_multple_data_format($rs_value['paxFareProduct']);
                    //paxwise price                  
                    foreach ($paxFareProduct as $pfp => $pfp_value) {
                        $paxReference = array();
                        if (isset($paxFareProduct[$pfp]['paxReference']['traveller'][0]))
                            $paxReference           = $paxFareProduct[$pfp]['paxReference']['traveller'];
                        else
                            $paxReference[0]        = $paxFareProduct[$pfp]['paxReference']['traveller'];
                        $passengerType = $paxFareProduct[$pfp]['paxReference']['ptc'];
                        if ($paxFareProduct[$pfp]['paxReference']['ptc'] == 'CNN' || $paxFareProduct[$pfp]['paxReference']['ptc'] == 'CH') {
                            $passengerType = 'CHD';
                        }
                        // $flightDetails[$refNumber][$price]['PriceInfo']['passengerType'] = $passengerType ;  
                        for ($pr = 0; $pr < (count($paxReference)); $pr++) {
                            $flightDetails[$refNumber][$price]['PriceInfo']['PassengerFare'][$passengerType]['PassengerCount']       = ($pr + 1);
                        }

                        // $flightDetails[$refNumber][$price]['PriceInfo']['PassengerFare'][$passengerType]['totalFareAmount']         = $paxFareProduct[$pfp]['paxFareDetail']['totalFareAmount'];

                        $flightDetails[$refNumber][$price]['PriceInfo']['PassengerFare'][$passengerType]['BasePrice']         = ($paxFareProduct[$pfp]['paxFareDetail']['totalFareAmount'] - $paxFareProduct[$pfp]['paxFareDetail']['totalTaxAmount']);

                        $flightDetails[$refNumber][$price]['PriceInfo']['PassengerFare'][$passengerType]['Tax'] = $paxFareProduct[$pfp]['paxFareDetail']['totalTaxAmount'];
                        $flightDetails[$refNumber][$price]['PriceInfo']['PassengerFare'][$passengerType]['TotalPrice']  = $paxFareProduct[$pfp]['paxFareDetail']['totalFareAmount'];

                        //$flightDetails[$refNumber][$price]['PriceInfo']['PassengerFare'][$passengerType]['totalTaxAmount']          = $paxFareProduct[$pfp]['paxFareDetail']['totalTaxAmount'];
                        if (isset($paxFareProduct[$pfp]['paxFareDetail']['codeShareDetails']['transportStageQualifier'])) {
                            $flightDetails[$refNumber][$price]['PriceInfo']['PassengerFare'][$passengerType]['transportStageQualifier'] = $paxFareProduct[$pfp]['paxFareDetail']['codeShareDetails']['transportStageQualifier'];
                        } else {
                            $flightDetails[$refNumber][$price]['PriceInfo']['PassengerFare'][$passengerType]['transportStageQualifier'] = '';
                        }
                        if (isset($paxFareProduct[$pfp]['paxFareDetail']['codeShareDetails']['company'])) {
                            $flightDetails[$refNumber][$price]['PriceInfo']['PassengerFare'][$passengerType]['company']                 = $paxFareProduct[$pfp]['paxFareDetail']['codeShareDetails']['company'];
                        } else {
                            $flightDetails[$refNumber][$price]['PriceInfo']['PassengerFare'][$passengerType]['company']                 = '';
                        }


                        $fare = array();
                        if (isset($paxFareProduct[$pfp]['fare'][0]))
                            $fare           = $paxFareProduct[$pfp]['fare'];
                        else
                            $fare[0]        = $paxFareProduct[$pfp]['fare'];
                        $last_ticketing_date = date('Y-m-d', strtotime("+10 days"));

                        for ($fa = 0; $fa < (count($fare)); $fa++) {
                            $flightDetails[$refNumber][$price]['PriceInfo']['PassengerFare'][$passengerType]['fare'][$fa]['description'] = '';
                            $flightDetails[$refNumber][$price]['PriceInfo']['PassengerFare'][$passengerType]['fare'][$fa]['textSubjectQualifier']   = $fare[$fa]['pricingMessage']['freeTextQualification']['textSubjectQualifier'];
                            $flightDetails[$refNumber][$price]['PriceInfo']['PassengerFare'][$passengerType]['fare'][$fa]['informationType']        = $fare[$fa]['pricingMessage']['freeTextQualification']['informationType'];
                            $description = array();
                            if (is_array($fare[$fa]['pricingMessage']['description']))
                                $description            = $fare[$fa]['pricingMessage']['description'];
                            else
                                $description[0]         = $fare[$fa]['pricingMessage']['description'];
                            $flightDetails[$refNumber][$price]['PriceInfo']['fare'][$fa]['description'] = '';

                            for ($d = 0; $d < count($description); $d++) {
                                if (isset($description[$d]))
                                    $flightDetails[$refNumber][$price]['PriceInfo']['PassengerFare'][$passengerType]['fare'][$fa]['description'] .= $description[$d] . " - ";
                                if (strtoupper(trim($description[$d])) == 'LAST TKT DTE') {
                                    $last_ticketing_date = $description[$d + 1];
                                }
                            }
                        }
                        // $flightDetails[$refNumber][$price]['PriceInfo']['LAST_TICKET_DATE'] = $last_ticketing_date;
                        $flightDetails[$refNumber][$price]['PriceInfo']['PassengerFare']['ADT']['LAST_TICKET_DATE'] = $last_ticketing_date;

                        $fareDetails = array();
                        if (isset($paxFareProduct[$pfp]['fareDetails'][0]))
                            $fareDetails            = $paxFareProduct[$pfp]['fareDetails'];
                        else
                            $fareDetails[0]         = $paxFareProduct[$pfp]['fareDetails'];
                        for ($fd = 0; $fd < (count($fareDetails)); $fd++) {
                            $flightDetails[$refNumber][$price]['PriceInfo']['fareDetails'][$fd]['flightMtkSegRef']              = $fareDetails[$fd]['segmentRef']['segRef'];
                            $flightDetails[$refNumber][$price]['PriceInfo']['fareDetails'][$fd]['designator']           = $fareDetails[$fd]['majCabin']['bookingClassDetails']['designator'];
                            $groupOfFares = array();
                            if (isset($fareDetails[$fd]['groupOfFares'][0]))
                                $groupOfFares           = $fareDetails[$fd]['groupOfFares'];
                            else
                                $groupOfFares[0]        = $fareDetails[$fd]['groupOfFares'];
                            for ($gf = 0; $gf < (count($groupOfFares)); $gf++) {
                                $flightDetails[$refNumber][$price]['PriceInfo']['fareDetails'][$fd]['rbd'][$gf]         = $groupOfFares[$gf]['productInformation']['cabinProduct']['rbd'];
                                $flightDetails[$refNumber][$price]['PriceInfo']['fareDetails'][$fd]['cabin'][$gf]       = $groupOfFares[$gf]['productInformation']['cabinProduct']['cabin'];
                                $flightDetails[$refNumber][$price]['PriceInfo']['fareDetails'][$fd]['avlStatus'][$gf]   = $groupOfFares[$gf]['productInformation']['cabinProduct']['avlStatus'];
                                $flightDetails[$refNumber][$price]['PriceInfo']['fareDetails'][$fd]['breakPoint'][$gf]  = $groupOfFares[$gf]['productInformation']['breakPoint'];
                                $flightDetails[$refNumber][$price]['PriceInfo']['fareDetails'][$fd]['fareType'][$gf]    = $groupOfFares[$gf]['productInformation']['fareProductDetail']['fareType'];

                                $flightDetails[$refNumber][$price]['PriceInfo']['fareDetails'][$fd]['fareBasis'][$gf] = $groupOfFares[$gf]['productInformation']['fareProductDetail']['fareBasis'];

                                if (isset($groupOfFares[$gf]['productInformation']['fareProductDetail']['fareBasis']['corporateId'])) {
                                    if ($groupOfFares[$gf]['productInformation']['fareProductDetail']['fareBasis']['corporateId']) {

                                        $flightDetails[$refNumber][$price]['PriceInfo']['fareDetails'][$fd]['corporateId'][$gf] = $groupOfFares[$gf]['productInformation']['fareProductDetail']['corporateId'];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        #debug($flightDetails);exit;
        $flight_list = array();
        $flightDetails1  = array();
        foreach ($Results as $result_k => $result_v) {

            $flight_details  = $result_v['groupOfFlights'];

            $Flight_SegDetails_arr = array();
            foreach ($flight_details as $f_key => $f_value) {
                //  debug($f_value);exit;
                $FlightSegment = force_multple_data_format($f_value['flightDetails']);

                // echo $f_key;
                // debug($f_value['propFlightGrDetail']['flightProposal']);
                // echo "====";
                $Flight_SegDetails = array();
                $flight_id = $f_value['propFlightGrDetail']['flightProposal'][0]['ref'];
                //changes added new counter for stop details
                $seg_counter = 0;
                foreach ($FlightSegment as $s_key => $s_value) {

                    $seg_value = $s_value['flightInformation'];
                    // $Flight_SegDetails[$s_key]['Origin']['AirportCode'] = $seg_value['location'][0]['locationId'];
                    // $Flight_SegDetails[$s_key]['Origin']['CityName'] =  $this->get_airport_city ($seg_value['location'][0]['locationId'] );
                    // $Flight_SegDetails[$s_key]['Origin']['AirportName'] =  $this->get_airport_city ($seg_value['location'][0]['locationId'] );               
                    //changes changed above code and added below ones for stop details 
                    if (isset($s_value['technicalStop'])) {
                        $technicalStopDetails = force_multple_data_format($s_value['technicalStop']['stopDetails']);
                        foreach ($technicalStopDetails as $t_s_k => $t_s_v) {
                            if ($t_s_k == 0) {
                                $Flight_SegDetails[$seg_counter]['Origin']['AirportCode'] = $seg_value['location'][0]['locationId'];


                                $departureDate = $seg_value['productDateTime']['dateOfDeparture'];
                                $departureTime = $seg_value['productDateTime']['timeOfDeparture'];
                                //changes start of addition of stop details
                                $arrivalDate = $t_s_v['date'];
                                $arrivalTime = $t_s_v['firstTime'];
                                $Flight_SegDetails[$seg_counter]['Destination']['AirportCode'] = $t_s_v['locationId'];
                                $Flight_SegDetails[$seg_counter]['isTechStop'] = 1;
                            } else {
                                $Flight_SegDetails[$seg_counter]['Origin']['AirportCode'] = $technicalStopDetails[0]['locationId'];
                                $departureDate = $t_s_v['date'];
                                $departureTime = $t_s_v['firstTime'];
                                $arrivalDate = $seg_value['productDateTime']['dateOfArrival'];
                                $arrivalTime = $seg_value['productDateTime']['timeOfArrival'];
                                $Flight_SegDetails[$seg_counter]['Destination']['AirportCode'] = $seg_value['location'][1]['locationId'];
                            }
                            $Flight_SegDetails[$seg_counter]['Origin']['CityName'] =  $this->get_airport_city($Flight_SegDetails[$seg_counter]['Origin']['AirportCode']);
                            $Flight_SegDetails[$seg_counter]['Origin']['AirportName'] =  $this->get_airport_city($Flight_SegDetails[$seg_counter]['Origin']['AirportCode']);
                            //changes end of addition for stop details                   
                            $departure_date =  ((substr("$departureDate", 0, -4)) . "-" . (substr("$departureDate", -4, 2)) . "-20" . (substr("$departureDate", -2)));
                            $departure_time = ((substr("$departureTime", 0, -2)) . ":" . (substr("$departureTime", -2)));
                            $DapartureDate = $departure_date . ' ' . $departure_time;
                            //changes start commented below code and added new for stop details   
                            // $d_time = date('H:i',strtotime($departureDate));
                            // $Flight_SegDetails[$s_key]['Origin']['DateTime'] = date('Y-m-d H:i:s',strtotime($DapartureDate));
                            // $Flight_SegDetails[$s_key]['Origin']['FDTV'] = strtotime($d_time);
                            // $departure_time;
                            $d_time = date('H:i', strtotime($departureDate));
                            $Flight_SegDetails[$seg_counter]['Origin']['DateTime'] = date('Y-m-d H:i:s', strtotime($DapartureDate));
                            $Flight_SegDetails[$seg_counter]['Origin']['FDTV'] = strtotime($d_time);
                            $arrival_date =  ((substr("$arrivalDate", 0, -4)) . "-" . (substr("$arrivalDate", -4, 2)) . "-20" . (substr("$arrivalDate", -2)));
                            $arrival_time = ((substr("$arrivalTime", 0, -2)) . ":" . (substr("$arrivalTime", -2)));
                            $Flight_SegDetails[$seg_counter]['Destination']['CityName'] =  $this->get_airport_city($Flight_SegDetails[$seg_counter]['Destination']['AirportCode']);
                            $Flight_SegDetails[$seg_counter]['Destination']['AirportName'] =  $this->get_airport_city($Flight_SegDetails[$seg_counter]['Destination']['AirportCode']);
                            $ArrivalDate = $arrival_date . ' ' . $arrival_time;
                            $a_time = date('H:i', strtotime($ArrivalDate));
                            $Flight_SegDetails[$seg_counter]['Destination']['DateTime'] = date('Y-m-d H:i:s', strtotime($ArrivalDate));
                            $Flight_SegDetails[$seg_counter]['Destination']['FATV'] = strtotime($a_time);
                            $Flight_SegDetails[$seg_counter]['OperatorCode'] = $seg_value['companyId']['marketingCarrier'];
                            $Flight_SegDetails[$seg_counter]['CabinClass'] = '';
                            $Flight_SegDetails[$seg_counter]['DisplayOperatorCode'] =  $seg_value['companyId']['marketingCarrier'];
                            $Flight_SegDetails[$seg_counter]['Duration'] = '';
                            $Flight_SegDetails[$seg_counter]['FlightNumber'] = $seg_value['flightOrtrainNumber'];
                            $Flight_SegDetails[$seg_counter]['OperatorName'] = $this->get_airline_name($seg_value['companyId']['marketingCarrier']);
                            $Flight_SegDetails[$seg_counter]['Attr']['AvailableSeats'] = '';
                            $Flight_SegDetails[$seg_counter]['Attr']['Baggage'] = '';
                            $Flight_SegDetails[$seg_counter]['Attr']['CabinBaggage'] = '';
                            // append
                            $flightDetails1[$flight_id]['Details'][$result_k] = $Flight_SegDetails;
                            $seg_counter++;
                        }
                    } else {
                        $Flight_SegDetails[$seg_counter]['Origin']['AirportCode'] = $seg_value['location'][0]['locationId'];
                        $Flight_SegDetails[$seg_counter]['Origin']['CityName'] =  $this->get_airport_city($seg_value['location'][0]['locationId']);
                        $Flight_SegDetails[$seg_counter]['Origin']['AirportName'] =  $this->get_airport_city($seg_value['location'][0]['locationId']);
                        $departureDate = $seg_value['productDateTime']['dateOfDeparture'];
                        $departureTime = $seg_value['productDateTime']['timeOfDeparture'];
                        $departure_date =  ((substr("$departureDate", 0, -4)) . "-" . (substr("$departureDate", -4, 2)) . "-20" . (substr("$departureDate", -2)));
                        $departure_time = ((substr("$departureTime", 0, -2)) . ":" . (substr("$departureTime", -2)));
                        $DapartureDate = $departure_date . ' ' . $departure_time;
                        $d_time = date('H:i', strtotime($departureDate));
                        $Flight_SegDetails[$seg_counter]['Origin']['DateTime'] = date('Y-m-d H:i:s', strtotime($DapartureDate));
                        $Flight_SegDetails[$seg_counter]['Origin']['FDTV'] = strtotime($d_time);
                        //changes end commented below code and added new for stop details  

                        $arrivalDate = $seg_value['productDateTime']['dateOfArrival'];
                        $arrivalTime = $seg_value['productDateTime']['timeOfArrival'];
                        $arrival_date =  ((substr("$arrivalDate", 0, -4)) . "-" . (substr("$arrivalDate", -4, 2)) . "-20" . (substr("$arrivalDate", -2)));
                        $arrival_time = ((substr("$arrivalTime", 0, -2)) . ":" . (substr("$arrivalTime", -2)));
                        //changes start changed following code for stop
                        $Flight_SegDetails[$seg_counter]['Destination']['AirportCode'] = $seg_value['location'][1]['locationId'];
                        $Flight_SegDetails[$seg_counter]['Destination']['CityName'] =  $this->get_airport_city($seg_value['location'][1]['locationId']);
                        $Flight_SegDetails[$seg_counter]['Destination']['AirportName'] =  $this->get_airport_city($seg_value['location'][1]['locationId']);
                        $ArrivalDate = $arrival_date . ' ' . $arrival_time;
                        $a_time = date('H:i', strtotime($ArrivalDate));
                        $Flight_SegDetails[$seg_counter]['Destination']['DateTime'] = date('Y-m-d H:i:s', strtotime($ArrivalDate));
                        $Flight_SegDetails[$seg_counter]['Destination']['FATV'] = strtotime($a_time);
                        $Flight_SegDetails[$seg_counter]['OperatorCode'] = $seg_value['companyId']['marketingCarrier'];
                        $Flight_SegDetails[$seg_counter]['CabinClass'] = '';
                        $Flight_SegDetails[$seg_counter]['DisplayOperatorCode'] =  $seg_value['companyId']['marketingCarrier'];
                        $Flight_SegDetails[$seg_counter]['Duration'] = '';
                        $Flight_SegDetails[$seg_counter]['FlightNumber'] = $seg_value['flightOrtrainNumber'];
                        $Flight_SegDetails[$seg_counter]['OperatorName'] = $this->get_airline_name($seg_value['companyId']['marketingCarrier']);
                        $Flight_SegDetails[$seg_counter]['Attr']['AvailableSeats'] = '';
                        $Flight_SegDetails[$seg_counter]['Attr']['Baggage'] = '';
                        $Flight_SegDetails[$seg_counter]['Attr']['CabinBaggage'] = '';
                        // append
                        $flightDetails1[$flight_id]['Details'][$result_k] = $Flight_SegDetails;
                        $seg_counter++;
                    }
                }

                //changes end for stop




            }
            # debug($Flight_SegDetails_arr);exit;
        }

        $x = 0;
        foreach ($Recommedation as $p => $s) {
            if (isset($s['itemNumber']['itemNumberId']['numberType'])) {
                $price = $p;
                $flag = "MTK";
            } else {
                $price = 0;
                $flag = "Normal";
            }
            $segmentFlightRef = array();
            if (isset($s['segmentFlightRef'][0]))
                $segmentFlightRef           = $s['segmentFlightRef'];
            else
                $segmentFlightRef[0]        = $s['segmentFlightRef'];
            for ($sfr = 0; $sfr <  (count($segmentFlightRef)); $sfr++) {


                $referencingDetail = array();
                if (isset($segmentFlightRef[$sfr]['referencingDetail'][0]))
                    $referencingDetail      = $segmentFlightRef[$sfr]['referencingDetail'];
                else
                    $referencingDetail[0]   = $segmentFlightRef[$sfr]['referencingDetail'];
                for ($rd = 0; $rd < (count($referencingDetail)); $rd++) {
                    $refNumber              = $referencingDetail[$rd]['refNumber'] . "-" . $flag . "-" . $p;
                    $refNumberFlight        = $referencingDetail[$rd]['refNumber'];
                    $refQualifier           = $referencingDetail[$rd]['refQualifier'];

                    // $FinalResult[$x]['FlightDetailsID']     = $x;

                    if (isset($flightDetails1[$refNumberFlight]['Details'][$rd])) {

                        $FinalResult[$x]['FlightDetails']['Details'][$rd]  = $flightDetails1[$refNumberFlight]['Details'][$rd];
                    }

                    if ($refQualifier == 'B') {
                        //$flightDetails1[$refNumberFlight]['Details']                           
                        if ($ServiceFeesGrp) {
                            foreach ($ServiceFeesGrp as $s_key => $s_value) {

                                if (isset($s_value['serviceTypeInfo']['carrierFeeDetails'])) {
                                    if ($s_value['serviceTypeInfo']['carrierFeeDetails']['type'] == 'FBA') {
                                        $FreeBaggageAllowance = force_multple_data_format($s_value['freeBagAllowanceGrp']);
                                        $baggage_allowance_str = $this->format_baggage_info($FreeBaggageAllowance, $refNumberFlight);
                                        if ($baggage_allowance_str) {

                                            foreach ($FinalResult[$x]['FlightDetails']['Details'] as $s_key => $s_value) {
                                                $isTechStopSkipCounter = 0;

                                                foreach ($s_value as $ss_key => $ss_value) {
                                                    $FinalResult[$x]['FlightDetails']['Details'][$s_key][$ss_key + $isTechStopSkipCounter]['Attr']['Baggage'] = $baggage_allowance_str;
                                                    // commented following for stop
                                                    // if(isset($FinalResult[$x]['FlightDetails']['Details'][$s_key][$ss_key+$isTechStopSkipCounter]['isTechStop']) && $FinalResult[$x]['FlightDetails']['Details'][$s_key][$ss_key+$isTechStopSkipCounter]['isTechStop'] == 1) {
                                                    //     $isTechStopSkipCounter++;
                                                    //     $FinalResult[$x]['FlightDetails']['Details'][$s_key][$ss_key+$isTechStopSkipCounter]['Attr']['Baggage'] = $baggage_allowance_str;
                                                    // }
                                                }
                                            }
                                        }
                                        //    $FinalResult[$x]['FlightDetails']['Details'][$s_key][$ss_key]['Attr']['Baggage'] = $baggage_allowance_str;


                                        //        }
                                        //    }


                                    }
                                }
                            }
                        }
                    }
                }


                $priceDetailsfinal = array();
                foreach ($flightDetails[$refNumber] as $price)
                    $priceDetailsfinal[] = $price;


                foreach ($priceDetailsfinal[0]['PriceInfo']['fareDetails'] as $c_key => $c_value) {
                    $isTechStopSkipCounter = 0;
                    //changes changed the following foreach loop
                    //         foreach ($c_value['cabin'] as $cc_key => $cc_value) {
                    //             $FinalResult[$x]['FlightDetails']['Details'][$c_key][$cc_key]['CabinClass'] = $cc_value;
                    //              $FinalResult[$x]['FlightDetails']['Details'][$c_key][$cc_key]['Attr']['AvailableSeats']=$c_value['avlStatus'][$cc_key];

                    //          }

                    //    } 
                    foreach ($c_value['cabin'] as $cc_key => $cc_value) {
                        $FinalResult[$x]['FlightDetails']['Details'][$c_key][$cc_key + $isTechStopSkipCounter]['CabinClass'] = $cc_value;
                        $FinalResult[$x]['FlightDetails']['Details'][$c_key][$cc_key + $isTechStopSkipCounter]['Attr']['AvailableSeats'] = $c_value['avlStatus'][$cc_key];
                        if (isset($FinalResult[$x]['FlightDetails']['Details'][$c_key][$cc_key + $isTechStopSkipCounter]['isTechStop']) && $FinalResult[$x]['FlightDetails']['Details'][$c_key][$cc_key + $isTechStopSkipCounter]['isTechStop'] == 1) {
                            $isTechStopSkipCounter++;
                            $FinalResult[$x]['FlightDetails']['Details'][$c_key][$cc_key + $isTechStopSkipCounter]['CabinClass'] = $cc_value;
                            $FinalResult[$x]['FlightDetails']['Details'][$c_key][$cc_key + $isTechStopSkipCounter]['Attr']['AvailableSeats'] = $c_value['avlStatus'][$cc_key];
                        }
                    }
                }
                //upto here
                // debug($priceDetailsfinal);exit;
                // debug($FinalResult[$x]['FlightDetails']['Details'][$x]);                                   
                $FinalResult[$x]['Price']['PassengerBreakup']      = $priceDetailsfinal[0]['PriceInfo']['PassengerFare'];

                $FinalResult[$x]['Price']['Currency'] = $currency;
                $FinalResult[$x]['Price']['TotalDisplayFare'] =  $priceDetailsfinal[0]['PriceInfo']['totalFareAmount'];
                $FinalResult[$x]['Price']['PriceBreakup']['BasicFare'] =  ($priceDetailsfinal[0]['PriceInfo']['totalFareAmount'] - $priceDetailsfinal[0]['PriceInfo']['totalTaxAmount']);
                $FinalResult[$x]['Price']['PriceBreakup']['Tax'] = $priceDetailsfinal[0]['PriceInfo']['totalTaxAmount'];
                $FinalResult[$x]['Price']['PriceBreakup']['AgentCommission'] = 0;
                $FinalResult[$x]['Price']['PriceBreakup']['AgentTdsOnCommision'] = 0;

                if (!isset($s['paxFareProduct'][0])) {
                    $paxFareProduct[0] = $s['paxFareProduct'];
                } else {
                    $paxFareProduct = $s['paxFareProduct'];
                }
                $is_Refund_text = 'Non Refundable';

                if (isset($paxFareProduct[0]['fare'][0]['pricingMessage']['description'])) {
                    if (is_array($paxFareProduct[0]['fare'][0]['pricingMessage']['description'])) {
                        $is_Refund_text = implode("", $paxFareProduct[0]['fare'][0]['pricingMessage']['description']);
                    } else {
                        $is_Refund_text = $paxFareProduct[0]['fare'][0]['pricingMessage']['description'];
                    }
                }
                if (strpos(strtolower($is_Refund_text), 'non') == false) {
                    $FinalResult[$x]['Attr']['IsRefundable'] = 1;
                } else {
                    $FinalResult[$x]['Attr']['IsRefundable'] = 0;
                }

                $FinalResult[$x]['Attr']['AirlineRemark'] = $is_Refund_text;
                $FinalResult[$x]['Attr']['FareDetails'] = $priceDetailsfinal[0]['PriceInfo']['fareDetails'];

                $key = array();
                $key['key'][$x]['booking_source'] = $this->booking_source;

                $key['key'][$x]['ResultIndex'] = '';
                $key['key'][$x]['IsLCC'] = '';
                $key['key'][$x]['FareBreakdown'] = $priceDetailsfinal;
                $key['key'][$x]['Session_id'] = $Session_id;
                $key['key'][$x]['SecurityToken'] = $SecurityToken;
                $key['key'][$x]['SequenceNumber'] = $SequenceNumber;
                $key['key'][$x]['SearchOrigin'] = $SearchOrigin;
                $key['key'][$x]['SearchDestination'] = $SearchDestination;

                //  $FinalResult[$x]['paxFareProduct'] = $paxFareProduct;                    
                $specificRecDetails = '';
                # debug($s['specificRecDetails']);
                if (!empty($s['specificRecDetails'])) {
                    if (!empty($s['specificRecDetails'][0]['specificProductDetails'][0])) {
                        $specificRecDetails = @$s['specificRecDetails']['0']['specificProductDetails'][0]['fareContextDetails']['cnxContextDetails'][0]['fareCnxInfo']['contextDetails']['availabilityCnxType'];
                    } else {
                        $specificRecDetails = @$s['specificRecDetails']['0']['specificProductDetails']['fareContextDetails']['cnxContextDetails'][0]['fareCnxInfo']['contextDetails']['availabilityCnxType'];
                    }
                }
                $fareContextDetails = array();
                if (isset($s['specificRecDetails'])) {
                    $s['specificRecDetails'] = force_multple_data_format($s['specificRecDetails']);
                    if (isset($s['specificRecDetails'][0]['specificProductDetails'])) {
                        $s['specificRecDetails'][0]['specificProductDetails'] = force_multple_data_format($s['specificRecDetails'][0]['specificProductDetails']);
                        if (isset($s['specificRecDetails'][0]['specificProductDetails'][0]['fareContextDetails'])) {
                            $fareContextDetails = force_multple_data_format($s['specificRecDetails'][0]['specificProductDetails'][0]['fareContextDetails']);
                        }
                    }
                }


                $FinalResult[$x]['specificProductDetails'] = $fareContextDetails;
                $FinalResult[$x]['specificRecDetails'] = $specificRecDetails;
                $key['key'][$x]['FlightInfo'] = $FinalResult[$x];
                $ResultToken = serialized_data($key['key']);
                $FinalResult[$x]['ResultToken'] = $ResultToken;
                $FinalResult[$x]['booking_source'] = $this->booking_source;
                $FinalResult[$x]['api_name'] = 'amadeus';
                $x++;
            }
        }
        $response['FlightDataList']['JourneyList'][0] = $FinalResult;
        # debug($response);exit;
        return $response;
    }
    /*foramt baggage information*/
    private function format_baggage_info($FreeBaggageAllowance, $flight_ref_num)
    {
        $pck = '';
        if ($FreeBaggageAllowance) {
            foreach ($FreeBaggageAllowance as $fb_key => $fb_value) {

                if ($fb_value['itemNumberInfo']['itemNumberDetails']['number'] == $flight_ref_num) {
                    $type_str = 'Pieces';
                    if (isset($fb_value['freeBagAllownceInfo']['baggageDetails']['unitQualifier'])) {

                        if ($fb_value['freeBagAllownceInfo']['baggageDetails']['unitQualifier'] == 'K') {
                            $type_str = 'Kg';
                        } elseif ($fb_value['freeBagAllownceInfo']['baggageDetails']['unitQualifier'] == 'L') {
                            $type_str = 'Pounds';
                        }
                    }
                    $q_code = $type_str;
                    if (isset($fb_value['freeBagAllownceInfo']['baggageDetails']['freeAllowance']['quantityCode'])) {

                        if ($fb_value['freeBagAllownceInfo']['baggageDetails']['freeAllowance']['quantityCode'] == 'W') {
                            $q_code = $type_str;
                        } elseif ($fb_value['freeBagAllownceInfo']['baggageDetails']['freeAllowance']['quantityCode'] == 'N') {
                            $q_code = 'Pieces';
                        }
                    }
                    $allowed_pck = 1;
                    if (isset($fb_value['freeBagAllownceInfo']['baggageDetails']['freeAllowance'])) {
                        $allowed_pck = $fb_value['freeBagAllownceInfo']['baggageDetails']['freeAllowance'];
                        $pck  = $allowed_pck . ' ' . $q_code;
                    }
                }
            }
        }
        return $pck;
    }
    /**
     * Fare Rule
     * @param unknown_type $request
     */
    public function fare_informative($request)
    {
        $validating_carrier = $request['FlightInfo']['FlightDetails']['Details'][0][0]['DisplayOperatorCode'];
        $response = array();
        $response['status'] = FAILURE_STATUS;
        $response['message'] = '';
        $response['data'] = array();
        $passenger_fare = $request['FareBreakdown'][0]['PriceInfo']['PassengerFare'];
        $rbd = $request['FareBreakdown'][0]['PriceInfo']['fareDetails'][0]['rbd'][0];
        $adult_cnt = 0;
        $child_cnt = 0;
        $infant_cnt = 0;
        if (isset($passenger_fare['ADT'])) {
            $adult_cnt = $passenger_fare['ADT']['PassengerCount'];
        }
        if (isset($passenger_fare['CHD'])) {
            $child_cnt = $passenger_fare['CHD']['PassengerCount'];
        }
        if (isset($passenger_fare['INF'])) {
            $infant_cnt = $passenger_fare['INF']['PassengerCount'];
        }
        $adult_infant_cnt = $adult_cnt;
        $adt = '<passengersGroup>';
        $adt .= '<segmentRepetitionControl>
                    <segmentControlDetails>
                        <quantity>1</quantity>
                        <numberOfUnits>' . ($adult_cnt) . '</numberOfUnits>
                    </segmentControlDetails>
                </segmentRepetitionControl>';
        $adt .= '<travellersID>';
        for ($ai = 1; $ai <= ($adult_cnt); $ai++) {
            $adt .= '<travellerDetails>';
            $adt .= '<measurementValue>' . $ai . '</measurementValue>';
            $adt .= '</travellerDetails>';
        }
        $adt .= '</travellersID>';
        $adt .= '<discountPtc>
                    <valueQualifier>ADT</valueQualifier>
                </discountPtc>';
        $adt .= '</passengersGroup>';

        if ($infant_cnt > 0) {
            $inf = '<passengersGroup>';
            $inf .= '<segmentRepetitionControl>
                        <segmentControlDetails>
                            <quantity>2</quantity>
                            <numberOfUnits>' . ($infant_cnt) . '</numberOfUnits>
                        </segmentControlDetails>
                    </segmentRepetitionControl>';
            $inf .= '<travellersID>';
            for ($ci = 1; $ci <= ($infant_cnt); $ci++) {
                $inf .= '<travellerDetails>';
                $inf .= '<measurementValue>' . $ci . '</measurementValue>';
                $inf .= '</travellerDetails>';
            }
            $inf .= '</travellersID>';
            $inf .= '<discountPtc>
                        <valueQualifier>INF</valueQualifier>
                        <fareDetails>
                            <qualifier>766</qualifier>
                        </fareDetails>
                    </discountPtc>';
            $inf .= '</passengersGroup>';
        }

        if ($child_cnt > 0) {
            $chd = '<passengersGroup>';
            $chd .= '<segmentRepetitionControl>
                        <segmentControlDetails>
                            <quantity>3</quantity>
                            <numberOfUnits>' . $child_cnt . '</numberOfUnits>
                        </segmentControlDetails>
                    </segmentRepetitionControl>';
            $chd .= '<travellersID>';
            for ($ii = 1; $ii <= $child_cnt; $ii++) {
                $adult_infant_cnt = $adult_infant_cnt + 1;
                $chd .= '<travellerDetails>';
                $chd .= '<measurementValue>' . $adult_infant_cnt . '</measurementValue>';
                $chd .= '</travellerDetails>';
            }
            $chd .= '</travellersID>';
            $chd .= '<discountPtc>
                        <valueQualifier>CH</valueQualifier>
                    </discountPtc>';
            $chd .= '</passengersGroup>';
        }

        $passenger_group = $adt . @$inf . @$chd;
        $flight_info = $request['FlightInfo']['FlightDetails']['Details'];
        $seg = '';
        $ind = 1;

        foreach ($flight_info as $fi_k => $fi_v) {
            foreach ($fi_v as $__sk => $__sv) {
                $dt = $__sv['Origin']['DateTime'];
                $dep_d = date('dmy', strtotime($dt));
                $exp = explode(' ', $dt);
                $txp = explode(':', $exp[1]);
                $dep_t = $txp[0] . $txp[1];

                $adt = $__sv['Destination']['DateTime'];
                $arr_d = date('dmy', strtotime($adt));
                $ad_exp = explode(' ', $adt);
                $adt_txp = explode(':', $ad_exp[1]);
                $arr_t = $adt_txp[0] . $adt_txp[1];

                $seg .= '<segmentGroup>';
                $seg .= '<segmentInformation>';
                //<operatingCompany>'.$__sv['OperatorCode'].'</operatingCompany>
                $seg .= '<flightDate>
                            <departureDate>' . $dep_d . '</departureDate>
                            <departureTime>' . $dep_t . '</departureTime>
                            <arrivalDate>' . $arr_d . '</arrivalDate>
                            <arrivalTime>' . $arr_t . '</arrivalTime>
                        </flightDate>';
                $seg .= '<boardPointDetails>
                            <trueLocationId>' . $__sv['Origin']['AirportCode'] . '</trueLocationId>
                        </boardPointDetails>';
                $seg .= '<offpointDetails>
                            <trueLocationId>' . $__sv['Destination']['AirportCode'] . '</trueLocationId>
                        </offpointDetails>';
                $seg .= '<companyDetails>
                            <marketingCompany>' . $__sv['OperatorCode'] . '</marketingCompany>
                        </companyDetails>';
                $seg .= '<flightIdentification>
                            <flightNumber>' . $__sv['FlightNumber'] . '</flightNumber>
                            <bookingClass>' . $rbd . '</bookingClass>
                        </flightIdentification>';
                $seg .= '<flightTypeDetails>
                            <flightIndicator>' . ($fi_k + 1) . '</flightIndicator>
                        </flightTypeDetails>
                        <itemNumber>' . $ind . '</itemNumber>';
                $seg .= '</segmentInformation>';
                $seg .= '</segmentGroup>';
                $ind++;
            }
        }
        $pricing_option_group = '<pricingOptionGroup>
                                    <pricingOptionKey>
                                        <pricingOptionKey>RP</pricingOptionKey>
                                    </pricingOptionKey>
                                </pricingOptionGroup>
                                <pricingOptionGroup>
                                    <pricingOptionKey>
                                        <pricingOptionKey>RU</pricingOptionKey>
                                    </pricingOptionKey>
                                </pricingOptionGroup>
                                <pricingOptionGroup>
                                    <pricingOptionKey>
                                        <pricingOptionKey>VC</pricingOptionKey>
                                    </pricingOptionKey>
                                    <carrierInformation>
                                        <companyIdentification>
                                            <otherCompany>' . $validating_carrier . '</otherCompany>
                                        </companyIdentification>
                                    </carrierInformation>   
                                </pricingOptionGroup>';
        /*<pricingOptionGroup>
                                        <pricingOptionKey>
                                            <pricingOptionKey>RU</pricingOptionKey>
                                        </pricingOptionKey>
                                    </pricingOptionGroup>*/
        $soapAction = 'TIPNRQ_18_1_1A';
        $soap_body = '<Fare_InformativePricingWithoutPNR xmlns="http://xml.amadeus.com/' . $soapAction . '">
                        ' . $passenger_group . '
                        ' . $seg . '
                        ' . $pricing_option_group . '
                    </Fare_InformativePricingWithoutPNR>';
        $xml = '<?xml version="1.0" encoding="UTF-8"?>           
    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:sec="http://xml.amadeus.com/2010/06/Security_v1" xmlns:typ="http://xml.amadeus.com/2010/06/Type">
    <soapenv:Header>
        <add:MessageID xmlns:add="http://www.w3.org/2005/08/addressing">' . $this->getuuid() . '</add:MessageID>
        <add:Action xmlns:add="http://www.w3.org/2005/08/addressing">http://webservices.amadeus.com/' . $soapAction . '</add:Action>
        <add:To xmlns:add="http://www.w3.org/2005/08/addressing">' . $this->api_url . '</add:To>
        <link:TransactionFlowLink xmlns:link="http://wsdl.amadeus.com/2010/06/ws/Link_v1"/>
        <oas:Security xmlns:oas="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
            <oas:UsernameToken oas1:Id="UsernameToken-1" xmlns:oas1="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
                <oas:Username>' . $this->username . '</oas:Username>
                <oas:Nonce EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary">' . $this->nonce . '</oas:Nonce>
                <oas:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest">' . $this->hashPwd . '</oas:Password>
                <oas1:Created>' . $this->created . '</oas1:Created>
            </oas:UsernameToken>
        </oas:Security>
        <AMA_SecurityHostedUser xmlns="http://xml.amadeus.com/2010/06/Security_v1">
            <UserID AgentDutyCode="' . $this->agent_duty_code . '" RequestorType="' . $this->requestor_type . '" PseudoCityCode="' . $this->pseudo_city_code . '" POS_Type="' . $this->pos_type . '"/>
        </AMA_SecurityHostedUser>
        <awsse:Session TransactionStatusCode="Start" xmlns:awsse="http://xml.amadeus.com/2010/06/Session_v3"/>
    </soapenv:Header>
    <soapenv:Body>
        ' . $soap_body . '
    </soapenv:Body>
</soapenv:Envelope>';
        $request = array();
        $request['request'] = $xml;
        $request['url'] = $this->api_url;
        $request['soap_url'] = $this->soap_url . $soapAction;
        $request['remarks'] = 'FareRule(Amadeus)';
        $request['status'] = SUCCESS_STATUS;
        /*debug($request ['request']);*/
        $fare_rule_response = $this->process_request($request['request'], $request['url'], $request['remarks'], $request['soap_url']);
        /*debug($fare_rule_response);*/
        $fare_rule_response = $this->xml2array($fare_rule_response);
        if (isset($fare_rule_response['soapenv:Envelope']['soapenv:Body']['Fare_InformativePricingWithoutPNRReply'])) {
            $response['status'] = SUCCESS_STATUS;
            $response['message'] = '';
            $response['data'] = $fare_rule_response;
        }
        return $response;
    }

    public function get_fare_rules($request, $search_id)
    {
        $response['status'] = FAILURE_STATUS; // Status Of Operation
        $response['message'] = ''; // Message to be returned
        $response['data'] = array(); // Data to be returned

        $fare_informative = $this->fare_informative($request);
        $fare_rule_request = $this->fare_rule_request($fare_informative);

        if ($fare_rule_request['status'] == SUCCESS_STATUS) {
            $fare_rule_response = $this->process_request($fare_rule_request['request'], $fare_rule_request['url'], $fare_rule_request['remarks'], $fare_rule_request['soap_url']);
            $fare_rule_xml_data = array();
            if ($fare_rule_response) {
                $fare_rule_xml_data = $this->xml2array($fare_rule_response);
            }
            $session_details = $fare_rule_xml_data['soapenv:Envelope']['soapenv:Header']['awsse:Session'];
            $SessionId = $session_details['awsse:SessionId'];
            $SequenceNumber = $session_details['awsse:SequenceNumber'] + 1;
            $SecurityToken = $session_details['awsse:SecurityToken'];
            $this->Security_SignOut($SessionId, $SequenceNumber, $SecurityToken);
            debug($fare_rule_xml_data);
            exit;
            $Rules = '';
            #debug($fare_rule_xml_data);exit;
            if ($fare_rule_xml_data && !isset($fare_rule_xml_data['soapenv:Envelope']['soapenv:Body']['Fare_GetFareRulesReply']['errorInfo']) && isset($fare_rule_xml_data['soapenv:Envelope']['soapenv:Body']['Fare_GetFareRulesReply'])) {
                $fare_ruleResult1 = array();
                $fare_ruleResult = $fare_rule_xml_data['soapenv:Envelope']['soapenv:Body']['Fare_GetFareRulesReply'];
                foreach ($fare_ruleResult['tariffInfo'] as $key => $val) {
                    $index_name = explode('.', $val['fareRuleText'][0]['freeText'])[1];

                    $fare_ruleResult1[$index_name] = '';
                    foreach ($val['fareRuleText'] as $text_key => $text_val) {
                        $fare_ruleResult1[$index_name] .= '';
                        if ($text_key == 0)
                            continue;
                        if (empty($text_val['freeText']))
                            continue;

                        $fare_ruleResult1[$index_name] .= trim($text_val['freeText']);
                    }
                }
                if (valid_array($fare_ruleResult1)) {
                    foreach ($fare_ruleResult1 as $i_key => $i_value) {

                        $Rules .= ' ' . $i_key . ' \n============================\n ' . $i_value . '============================\n ';
                    }
                }


                if (!empty($Rules)) {
                    $fareResp1[0]['FareRules'] = $Rules;
                    $fareResp1[0]['Origin'] = $fare_rule_request['fare_rule_request']['origin'];
                    $fareResp1[0]['Destination'] = $fare_rule_request['fare_rule_request']['destination'];
                    $fareResp1[0]['Airline'] = $fare_rule_request['fare_rule_request']['carrier'];
                    $response['data']['FareRuleDetail'] = $fareResp1;
                    $response['status'] = SUCCESS_STATUS;
                } else {
                    $response['status'] = FAILURE_STATUS;
                }
            } else {
                $response['status'] = FAILURE_STATUS;
            }
        } else {
            $response['status'] = FAILURE_STATUS;
        }
        # debug($response);exit;
        // debug($Responsense);exit;
        return $response;
    }
    /**
     * Forms the fare rule request
     * @param unknown_type $request
     */
    private function fare_rule_request($params)
    {
        $request = array();
        $search_request = array();
        //$soapAction = 'FARQNQ_07_1_1A';
        $soapAction = 'TMRXRQ_18_1_1A';
        $session_details = $params['data']['soapenv:Envelope']['soapenv:Header']['awsse:Session'];
        $unique_reference = $params['data']['soapenv:Envelope']['soapenv:Body']['Fare_InformativePricingWithoutPNRReply']['mainGroup']['pricingGroupLevelGroup']['fareInfoGroup']['offerReferences']['offerIdentifier']['uniqueOfferReference'];
        $session_id      = $session_details['awsse:SessionId'];
        $sequence_number = $session_details['awsse:SequenceNumber'] + 1;
        $security_token = $session_details['awsse:SecurityToken'];
        /*$fare_check_rules = '<MiniRule_GetFromRec >
                               <groupRecords>
                                  <recordID>
                                     <referenceType>PNR</referenceType>
                                     <uniqueReference>'.$unique_reference.'</uniqueReference>
                                  </recordID>
                               </groupRecords>
                            </MiniRule_GetFromRec>';*/

        $fare_check_rules = '<MiniRule_GetFromRec>
                                  <groupRecords>
                                    <recordID>
                                      <referenceType>FRN</referenceType>
                                      <uniqueReference>ALL</uniqueReference>
                                    </recordID>
                                  </groupRecords>
                            </MiniRule_GetFromRec>';
        /*$fare_check_rules = '<Fare_CheckRules xmlns="http://xml.amadeus.com/'.$soapAction.'">
                                    <msgType>
                                        <messageFunctionDetails>
                                            <messageFunction>712</messageFunction>
                                        </messageFunctionDetails>
                                    </msgType>
                                    <itemNumber>
                                        <itemNumberDetails>
                                            <number>1</number>
                                        </itemNumberDetails>
                                    </itemNumber>
                                </Fare_CheckRules>';*/


        $xml_query = '<?xml version="1.0" encoding="UTF-8"?>
                                <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:sec="http://xml.amadeus.com/2010/06/Security_v1" xmlns:typ="http://xml.amadeus.com/2010/06/Type">
                                    <soapenv:Header>
                                        <awsse:Session TransactionStatusCode="InSeries" xmlns:awsse="http://xml.amadeus.com/2010/06/Session_v3">
                                            <awsse:SessionId>' . $session_id . '</awsse:SessionId>
                                            <awsse:SequenceNumber>' . $sequence_number . '</awsse:SequenceNumber>
                                            <awsse:SecurityToken>' . $security_token . '</awsse:SecurityToken>
                                        </awsse:Session>
                                        <add:MessageID xmlns:add="http://www.w3.org/2005/08/addressing">' . $this->getuuid() . '</add:MessageID>
                                        <add:Action xmlns:add="http://www.w3.org/2005/08/addressing">http://webservices.amadeus.com/' . $soapAction . '</add:Action>
                                        <add:To xmlns:add="http://www.w3.org/2005/08/addressing">' . $this->api_url . '</add:To>
                                    </soapenv:Header>
                                    <soapenv:Body>
                                        ' . $fare_check_rules . '
                                    </soapenv:Body>
                                </soapenv:Envelope>';
        $request['request'] = $xml_query;
        $request['url'] = $this->api_url;
        $request['fare_rule_request'] = $xml_query;
        $request['soap_url'] = $this->soap_url . $soapAction;
        $request['remarks'] = 'FareMiniRule(Amadeus)';
        $request['status'] = SUCCESS_STATUS;
        return $request;
    }

    private function fare_rule_request_old($params)
    {
        $request = array();
        $search_request = array();

        $search_request['origin'] = $params['SearchOrigin'];
        $search_request['destination'] = $params['SearchDestination'];
        $farebasis = $params['FareBreakdown'][0]['PriceInfo']['fareDetails'][0]['fareBasis'][0];
        $search_request['farebasis'] = $farebasis;
        $search_request['carrier'] =  $params['FlightInfo']['FlightDetails']['Details'][0][0]['OperatorCode'];
        $search_request['date'] = date('dmy');
        #debug($search_request);exit;
        $soapAction = "FARRNQ_10_1_1A";
        $xml_query = '
                <?xml version="1.0" encoding="UTF-8"?>           
                <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:sec="http://xml.amadeus.com/2010/06/Security_v1" xmlns:typ="http://xml.amadeus.com/2010/06/Type">
                <soapenv:Header>
                <add:MessageID xmlns:add="http://www.w3.org/2005/08/addressing">' . $this->getuuid() . '</add:MessageID>
                <add:Action xmlns:add="http://www.w3.org/2005/08/addressing">http://webservices.amadeus.com/' . $soapAction . '</add:Action>
                <add:To xmlns:add="http://www.w3.org/2005/08/addressing">' . $this->api_url . '</add:To>
                <link:TransactionFlowLink xmlns:link="http://wsdl.amadeus.com/2010/06/ws/Link_v1"/>
                <oas:Security xmlns:oas="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
                <oas:UsernameToken oas1:Id="UsernameToken-1" xmlns:oas1="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
                <oas:Username>' . $this->username . '</oas:Username>
                <oas:Nonce EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary">' . $this->nonce . '</oas:Nonce>
                <oas:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest">' . $this->hashPwd . '</oas:Password>
                <oas1:Created>' . $this->created . '</oas1:Created>
            </oas:UsernameToken>
            </oas:Security>
            <AMA_SecurityHostedUser xmlns="http://xml.amadeus.com/2010/06/Security_v1">
            <UserID AgentDutyCode="' . $this->agent_duty_code . '" RequestorType="' . $this->requestor_type . '" PseudoCityCode="' . $this->pseudo_city_code . '" POS_Type="' . $this->pos_type . '"/>
            </AMA_SecurityHostedUser>
            </soapenv:Header>
            <soapenv:Body>';
        $xml_query .= '<Fare_GetFareRules xmlns="http://xml.amadeus.com/"' . $soapAction . ' >
                        <msgType>
                            <messageFunctionDetails>
                            <messageFunction>FRN</messageFunction>
                            </messageFunctionDetails>
                        </msgType>
                        <pricingTickInfo>
                            <productDateTimeDetails>
                            <ticketingDate>' . $search_request['date'] . '</ticketingDate>
                            </productDateTimeDetails>
                        </pricingTickInfo>
                        <flightQualification>
                            <additionalFareDetails>
                                <rateClass>' . $search_request['farebasis'] . '</rateClass>
                            </additionalFareDetails>
                        </flightQualification>
                        <transportInformation>
                            <transportService>
                                <companyIdentification>
                                    <marketingCompany>' . $search_request['carrier'] . '</marketingCompany>
                                </companyIdentification>
                            </transportService>
                        </transportInformation>
                        <tripDescription>
                            <origDest>
                                <origin>' . $search_request['origin'] . '</origin>
                                <destination>' . $search_request['destination'] . '</destination>
                            </origDest>
                        </tripDescription>
                    </Fare_GetFareRules>';

        $xml_query .= '
        </soapenv:Body>
        </soapenv:Envelope>';

        $request['request'] = $xml_query;
        $request['url'] = $this->api_url;
        $request['fare_rule_request'] = $search_request;
        $request['soap_url'] = $this->soap_url . $soapAction;
        $request['remarks'] = 'FareRule(Amadeus)';
        $request['status'] = SUCCESS_STATUS;
        // debug($request);exit;
        return $request;
    }

    /**
     * Update Fare Quote
     * @param unknown_type $request
     */

    public function get_update_fare_quote($fare_search_data, $search_id)
    {
        # debug($fare_search_data);exit;
        $response['status'] = FAILURE_STATUS; // Status Of Operation
        $response['message'] = ''; // Message to be returned
        $response['data'] = array(); // Data to be returned
        #debug($fare_search_data);exit;
        if (valid_array($fare_search_data)) {

            if (valid_array($fare_search_data) == true && isset($fare_search_data['FlightInfo']['FlightDetails']) == true) {
                $response['status'] = SUCCESS_STATUS;
                $response['data']['FareQuoteDetails']['JourneyList'][0][0] = $fare_search_data['FlightInfo'];
                $response['data']['FareQuoteDetails']['JourneyList'][0][0]['HoldTicket'] = 1;
                $result_token[0]['booking_source'] = $fare_search_data['booking_source'];
                $response['data']['FareQuoteDetails']['JourneyList'][0][0]['ResultToken'] = serialized_data($result_token);

                //$response ['data']['FareQuoteDetails']['JourneyList'][0][0]['HoldTicket'] =; 



            } else {
                $response['message'] = 'Not Available';
            }
        } else {
            $response['status'] = FAILURE_STATUS;
        }
        #debug($response);exit;
        return $response;
    }

    /*
     *
     * get airport city based on airport code
     */
    private function get_airport_city($airport_code)
    {
        $CI = &get_instance();

        $airport_name = $CI->db_cache_api->get_airport_city_name(array(
            'airport_code' => $airport_code
        ));
        $airport_name = @$airport_name['airport_city'];
        return ($airport_name);
    }
    /*
     *
     * get airline name based on airport code
     */
    private function get_airline_name($airline_code)
    {
        $CI = &get_instance();
        # echo "hiee";exit;    
        $airline_data = $CI->db_cache_api->get_airline_name(array(
            'code' => $airline_code
        ));
        $airline_name = ucfirst(strtolower(@$airline_data['name']));
        return ($airline_name);
    }
    /**
     * check if the search RS is valid or not
     * @param array $search_result
     * search result RS to be validated
     */
    private function valid_search_result($search_result)
    {
        if (!isset($search_result['soapenv:Envelope']['soapenv:Body']['   Fare_MasterPricerTravelBoardSearchReply']['errorMessage'])) {
            return true;
        } else {
            return false;
        }
    }
    public function get_search_class_code($class)
    {

        if ($class == 'economy') {
            $economyCode = 'Y';
        } elseif ($class == 'premiumeconomy') {
            $economyCode = 'S';
        } elseif ($class == 'business') {
            $economyCode = 'C';
        } elseif ($class == 'premiumbusiness') {
            $economyCode = 'J';
        } elseif ($class == 'first') {
            $economyCode = 'F';
        } elseif ($class == 'premiumfirst') {
            $economyCode = 'P';
        } else {
            $economyCode = 'Y';
        }
        return $economyCode;
    }
    /**
     * Update markup currency for price object of flight
     * 
     * @param object $price_summary         
     * @param object $currency_obj          
     */
    function update_markup_currency(&$price_summary, &$currency_obj)
    {
    }
    /**
     * get total price from summary object
     * 
     * @param object $price_summary         
     */
    function total_price($price_summary)
    {
    }
    /**
     * Process booking
     * @param array $booking_params
     */
    function process_booking($booking_params, $app_reference, $sequence_number, $search_id)
    {

        // echo 'live key';exit;
        // exit;
        //passing nationality for nq exemption
        if ($booking_params['Passengers'][0]['CountryCode'] == 'NP') {
            $country = "Nepalese";
        } else {
            $country = "others";
        }
        $response['status'] = FAILURE_STATUS; // Status Of Operation
        $response['message'] = ''; // Message to be returned
        $response['data'] = array(); // Data to be returned
        $search_data = $this->search_data($search_id);
        #debug($booking_params);exit;   
        $AirSellRecommdation = $this->AirSellRecommdation($booking_params, $search_data['data']);
        $JourneyList = $booking_params['flight_data'];
        $Price = $booking_params['flight_data']['PriceBreakup'];
        if ($AirSellRecommdation['status'] == SUCCESS_STATUS) {
            //segment confirm need to save passenger info in PNR AddElement option 0         
            $PNR_AddMultiElements_Option0 = $this->PNR_AddMultiElements_0(0, '', $AirSellRecommdation['data'], $booking_params, array(), false, false);
            if ($PNR_AddMultiElements_Option0['status'] == SUCCESS_STATUS) {
                //checking flight class availablity
                $flight_marketing_carrier = $booking_params['flight_data']['FlightDetails']['Details'][0][0]['OperatorCode'];

                /*$FOP = $this->FOP($booking_params,$flight_marketing_carrier,$PNR_AddMultiElements_Option0['data']);*/

                $PNR_AddMultiElements_BookingClass = $this->PNR_AddMultiElements_BookingClass($PNR_AddMultiElements_Option0['data'], $flight_marketing_carrier, $search_id, $country);
                if ($PNR_AddMultiElements_BookingClass['status'] == SUCCESS_STATUS) {
                    $Ticket_CreateTSTFromPricing = $this->Ticket_CreateTSTFromPricing($search_data['data'], $PNR_AddMultiElements_BookingClass['data']);
                    if ($Ticket_CreateTSTFromPricing['status'] == SUCCESS_STATUS) {
                        //sending fop 
                        /*$FOP = $this->FOP($booking_params,$flight_marketing_carrier,$Ticket_CreateTSTFromPricing['data']);*/
                        $FOP['status'] = SUCCESS_STATUS;
                        if ($FOP['status'] == SUCCESS_STATUS) {
                            $PNR_AddMultiElements_Option10 = $this->PNR_AddMultiElements_10($Ticket_CreateTSTFromPricing['data'], $booking_params, $search_data['data']);
                            //booking confirmed
                            if ($PNR_AddMultiElements_Option10['status'] == SUCCESS_STATUS) {
                                $response['status'] = SUCCESS_STATUS;

                                /*$Place_Queue = $this->Place_Queue($PNR_AddMultiElements_Option10['data']);
                                
                                $SessionId = $Place_Queue['data']['SessionId'];
                                $SequenceNumber = $Place_Queue['data']['SequenceNumber'];
                                $SecurityToken = $Place_Queue['data']['SecurityToken'];
        
                                $this->Security_SignOut($SessionId,$SequenceNumber,$SecurityToken);*/

                                $this->save_book_response_details($PNR_AddMultiElements_Option10['data']['Pnr_No'], $app_reference, $sequence_number);
                                    /*
                                $header_data = array();
                                $header_data['Pnr_No'] = $Place_Queue['data']['Pnr_No']*/;
                                //$PNRRetrieve = $this->PNRRetrieve($header_data);
                                $PNRRetrieve = $this->PNRRetrieve($PNR_AddMultiElements_Option10['data']);
                                //sleep(3);// if we need direct ticketing need to wait 10 secondds
                                sleep(10);
                                if ($PNRRetrieve['status'] == SUCCESS_STATUS) {
                                    $DocIssurance_Issue_Ticket = $this->DocIssuance_IssueTicket($PNRRetrieve['data']);
                                    sleep(10);
                                    $PNRRetrieve = $this->PNRRetrieve($DocIssurance_Issue_Ticket['data']);

                                    $SessionId = $PNRRetrieve['data']['SessionId'];
                                    $SecurityToken = $PNRRetrieve['data']['SecurityToken'];
                                    $SequenceNumber = $PNRRetrieve['data']['SequenceNumber'];

                                    $this->Security_SignOut($SessionId, $SequenceNumber, $SecurityToken);

                                    $raw_response = $PNRRetrieve['raw_response']['soapenv:Envelope']['soapenv:Body'];
                                    $this->save_flight_ticket_details($booking_params, $PNRRetrieve['data']['Pnr_No'], $app_reference, $sequence_number, $search_id, $raw_response);
                                    $response['message'] = 'Ticket method called';
                                }
                            }
                        }
                    }
                }
            }
        }
        return $response;
    }
    //new function for hold booking
    function hold_ticket($booking_params, $app_reference, $sequence_number, $search_id)
    {
        //passing nationality for nq exemption
        if ($booking_params['Passengers'][0]['CountryCode'] == 'NP') {
            $country = "Nepalese";
        } else {
            $country = "others";
        }
        // echo 'live key';exit;
        // exit;
        $response['status'] = FAILURE_STATUS; // Status Of Operation
        $response['message'] = ''; // Message to be returned
        $response['data'] = array(); // Data to be returned
        $search_data = $this->search_data($search_id);
        #debug($booking_params);exit;   
        $AirSellRecommdation = $this->AirSellRecommdation($booking_params, $search_data['data']);
        $JourneyList = $booking_params['flight_data'];
        $Price = $booking_params['flight_data']['PriceBreakup'];
        if ($AirSellRecommdation['status'] == SUCCESS_STATUS) {
            //segment confirm need to save passenger info in PNR AddElement option 0         
            $PNR_AddMultiElements_Option0 = $this->PNR_AddMultiElements_0(0, '', $AirSellRecommdation['data'], $booking_params, array(), false, false);
            if ($PNR_AddMultiElements_Option0['status'] == SUCCESS_STATUS) {
                //checking flight class availablity
                $flight_marketing_carrier = $booking_params['flight_data']['FlightDetails']['Details'][0][0]['OperatorCode'];

                //    expired wsdl
                // $FOP = $this->FOP($booking_params, $flight_marketing_carrier, $PNR_AddMultiElements_Option0['data']);

                // $PNR_AddMultiElements_BookingClass = $this->PNR_AddMultiElements_BookingClass($FOP['data'], $flight_marketing_carrier, $search_id);
                //passing $country for nq exemption
                $PNR_AddMultiElements_BookingClass = $this->PNR_AddMultiElements_BookingClass($PNR_AddMultiElements_Option0['data'], $flight_marketing_carrier, $search_id,$country);
                if ($PNR_AddMultiElements_BookingClass['status'] == SUCCESS_STATUS) {
                    $Ticket_CreateTSTFromPricing = $this->Ticket_CreateTSTFromPricing($search_data['data'], $PNR_AddMultiElements_BookingClass['data']);
                    if ($Ticket_CreateTSTFromPricing['status'] == SUCCESS_STATUS) {
                        $FOP['status'] = SUCCESS_STATUS;
                        if ($FOP['status'] == SUCCESS_STATUS) {
                            $PNR_AddMultiElements_Option10 = $this->PNR_AddMultiElements_10($Ticket_CreateTSTFromPricing['data'], $booking_params, $search_data['data']);
                            //booking confirmed
                            if ($PNR_AddMultiElements_Option10['status'] == SUCCESS_STATUS) {
                                $response['status'] = SUCCESS_STATUS;
                                //if required need to run pnr retrieve
                                //placing in queue
                                // $Place_Queue = $this->Place_Queue($PNR_AddMultiElements_Option10['data']);
                                //storing in database
                                // $SessionId = $Place_Queue['data']['SessionId'];
                                // $SequenceNumber = $Place_Queue['data']['SequenceNumber'];
                                // $SecurityToken = $Place_Queue['data']['SecurityToken'];

                                $SessionId = $PNR_AddMultiElements_Option10['data']['SessionId'];
                                $SequenceNumber = $PNR_AddMultiElements_Option10['data']['SequenceNumber'];
                                $SecurityToken = $PNR_AddMultiElements_Option10['data']['SecurityToken'];
                                $booking_params['PNR_AddMultiElements_Option10'] = $PNR_AddMultiElements_Option10['data'];
                                // $this->Security_SignOut($SessionId, $SequenceNumber, $SecurityToken);

                                $this->save_book_response_details($PNR_AddMultiElements_Option10['data']['Pnr_No'], $app_reference, $sequence_number);
                                $this->saveBookingParams($app_reference, $booking_params, $search_id);
                            }
                        }
                    }
                }
            }
        }
        return $response;
    }
    function hold_ticket_old($booking_params, $app_reference, $sequence_number, $search_id)
    {
        $response['status'] = FAILURE_STATUS; // Status Of Operation
        $response['message'] = ''; // Message to be returned
        $response['data'] = array(); // Data to be returned
        $search_data = $this->search_data($search_id);
        $AirSellRecommdation = $this->AirSellRecommdation($booking_params, $search_data['data']);

        $JourneyList = $booking_params['flight_data'];
        $Price = $booking_params['flight_data']['PriceBreakup'];
        if ($AirSellRecommdation['status'] == SUCCESS_STATUS) {
            //segment confirm need to save passenger info in PNR AddElement option 0         
            $PNR_AddMultiElements_Option0 = $this->PNR_AddMultiElements_0(0, '', $AirSellRecommdation['data'], $booking_params, array(), false, false);
            if ($PNR_AddMultiElements_Option0['status'] == SUCCESS_STATUS) {
                //checking flight class availablity
                $flight_marketing_carrier = $booking_params['flight_data']['FlightDetails']['Details'][0][0]['OperatorCode'];
                $PNR_AddMultiElements_BookingClass = $this->PNR_AddMultiElements_BookingClass($PNR_AddMultiElements_Option0['data'], $flight_marketing_carrier);
                if ($PNR_AddMultiElements_BookingClass['status'] == SUCCESS_STATUS) {
                    $Ticket_CreateTSTFromPricing = $this->Ticket_CreateTSTFromPricing($search_data['data'], $PNR_AddMultiElements_BookingClass['data']);
                    if ($Ticket_CreateTSTFromPricing['status'] == SUCCESS_STATUS) {
                        //sending fop 
                        $FOP = $this->FOP($booking_params, $flight_marketing_carrier, $Ticket_CreateTSTFromPricing['data']);
                        //$FOP['status'] = SUCCESS_STATUS;
                        if ($FOP['status'] == SUCCESS_STATUS) {
                            $PNR_AddMultiElements_Option10 = $this->PNR_AddMultiElements_10($Ticket_CreateTSTFromPricing['data'], $booking_params, $search_data['data']);
                            //booking confirmed
                            if ($PNR_AddMultiElements_Option10['status'] == SUCCESS_STATUS) {
                                $response['status'] = SUCCESS_STATUS;
                                //if required need to run pnr retrieve
                                //placing in queue
                                $Place_Queue = $this->Place_Queue($PNR_AddMultiElements_Option10['data']);
                                //storing in database
                                $this->save_book_response_details($PNR_AddMultiElements_Option10['data']['Pnr_No'], $app_reference, $sequence_number);

                                $PNRRetrieve = $this->PNRRetrieve($Place_Queue['data']);
                                $SessionId = $PNRRetrieve['data']['SessionId'];
                                $SequenceNumber = $PNRRetrieve['data']['SequenceNumber'] + 1;
                                $SecurityToken = $PNRRetrieve['data']['SecurityToken'];
                                $this->Security_SignOut($SessionId, $SequenceNumber, $SecurityToken);

                                //$PNRRetrieve = $this->PNRRetrieve($PNR_AddMultiElements_Option10['data']);
                                sleep(3); // if we need direct ticketing need to wait 10 secondds
                                if ($PNRRetrieve['status'] == SUCCESS_STATUS) {
                                    /*$DocIssurance_Issue_Ticket = $this->DocIssuance_IssueTicket($PNRRetrieve['data']); 
                                     $this->save_flight_ticket_details($booking_params,$PNRRetrieve['data']['Pnr_No'], $app_reference, $sequence_number, $search_id);
                                    $response['message'] = 'Ticket method called';*/
                                }
                            }
                        }
                    }
                }
            }
        }

        return $response;
    }
    /*Booking Procedure starts*/
    private function AirSellRecommdation($booking_params, $search_data)
    {
        $response['status'] = FAILURE_STATUS;
        $response['data'] = array();

        if (valid_array($booking_params['flight_data']['FlightDetails']['Details'])) {

            $quantity = $search_data['adult_config'] + $search_data['child_config'];
            $specificRecDetails = $booking_params['flight_data']['specificProductDetails'];
            $slice_dice_seg = array();
            //debug($specificRecDetails);
            foreach ($specificRecDetails as $sr_key => $sr_value) {
                $slice_dice_seg[$sr_value['requestedSegmentInfo']['segRef']] = $sr_value['cnxContextDetails'];
            }
            $ite_query = '';
            foreach ($booking_params['flight_data']['FlightDetails']['Details'] as $s_key => $s_value) {
                $Origin = $s_value[0]['Origin']['AirportCode'];
                $Destination_details = end($s_value);
                $Destination = $Destination_details['Destination']['AirportCode'];
                $ite_query .= '<itineraryDetails><originDestinationDetails>
                                    <origin>' . $Origin . '</origin>
                                    <destination>' . $Destination . '</destination>
                                </originDestinationDetails>
                                <message>
                                    <messageFunctionDetails>
                                        <messageFunction>183</messageFunction>
                                    </messageFunctionDetails>
                                </message>';
                $subsegment = '';
                $slice_dice_seg_val = array();
                if (isset($slice_dice_seg[$s_key + 1])) {
                    $slice_dice_seg_val = $slice_dice_seg[$s_key + 1];
                }
                foreach ($s_value as $ss_key => $ss_value) {
                    $book_clas = $booking_params['flight_data']['Attr']['FareDetails'][$s_key]['rbd'][$ss_key];
                    $flight_identification = '';
                    if ($slice_dice_seg_val) {
                        if (isset($slice_dice_seg_val[$ss_key]['fareCnxInfo']['contextDetails']['availabilityCnxType'])) {
                            $flight_identification  = ' <flightTypeDetails><flightIndicator>' . $slice_dice_seg_val[$ss_key]['fareCnxInfo']['contextDetails']['availabilityCnxType'] . '</flightIndicator></flightTypeDetails>';
                        }
                    }
                    $subsegment .= '<segmentInformation>
                                        <travelProductInformation>
                                            <flightDate>
                                                <departureDate>' . date('dmy', strtotime($ss_value['Origin']['DateTime'])) . '</departureDate>
                                            </flightDate>
                                            <boardPointDetails>
                                                <trueLocationId>' . $ss_value['Origin']['AirportCode'] . '</trueLocationId>
                                            </boardPointDetails>
                                            <offpointDetails>
                                                <trueLocationId>' . $ss_value['Destination']['AirportCode'] . '</trueLocationId>
                                            </offpointDetails>
                                            <companyDetails>
                                                <marketingCompany>' . $ss_value['OperatorCode'] . '</marketingCompany>
                                            </companyDetails>
                                            <flightIdentification>
                                                <flightNumber>' . $ss_value['FlightNumber'] . '</flightNumber>
                                                <bookingClass>' . $book_clas . '</bookingClass>
                                            </flightIdentification>
                                           ' . $flight_identification . '
                                        </travelProductInformation>
                                        <relatedproductInformation>
                                            <quantity>' . $quantity . '</quantity>
                                            <statusCode>NN</statusCode>
                                        </relatedproductInformation>
                                    </segmentInformation>';
                }

                $ite_query .= $subsegment . '</itineraryDetails>';
            }
            $SellRequest = '<?xml version="1.0" encoding="UTF-8"?>           
                    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:sec="http://xml.amadeus.com/2010/06/Security_v1" xmlns:typ="http://xml.amadeus.com/2010/06/Type" >
                    <soapenv:Header>
                    <add:MessageID xmlns:add="http://www.w3.org/2005/08/addressing">' . $this->getuuid() . '</add:MessageID>
                    <add:Action xmlns:add="http://www.w3.org/2005/08/addressing">http://webservices.amadeus.com/ITAREQ_05_2_IA</add:Action>
                    <add:To xmlns:add="http://www.w3.org/2005/08/addressing">' . $this->api_url . '</add:To>
                    <link:TransactionFlowLink xmlns:link="http://wsdl.amadeus.com/2010/06/ws/Link_v1"/>
                    <oas:Security xmlns:oas="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
                    <oas:UsernameToken oas1:Id="UsernameToken-1" xmlns:oas1="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
                    <oas:Username>' . $this->username . '</oas:Username>
                    <oas:Nonce EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary">' . $this->nonce . '</oas:Nonce>
                    <oas:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest">' . $this->hashPwd . '</oas:Password>
                    <oas1:Created>' . $this->getCreateDate() . '</oas1:Created>
                    </oas:UsernameToken>
                    </oas:Security>
                    <AMA_SecurityHostedUser xmlns="http://xml.amadeus.com/2010/06/Security_v1">
                    <UserID AgentDutyCode="' . $this->agent_duty_code . '" RequestorType="' . $this->requestor_type . '" PseudoCityCode="' . $this->pseudo_city_code . '" POS_Type="' . $this->pos_type . '"/>
                    </AMA_SecurityHostedUser>
                    <awsse:Session TransactionStatusCode="Start" xmlns:awsse="http://xml.amadeus.com/2010/06/Session_v3"/>
                    </soapenv:Header>
                    <soapenv:Body>
                    <Air_SellFromRecommendation xmlns="http://xml.amadeus.com/ITAREQ_05_2_IA">
                        <messageActionDetails>
                            <messageFunctionDetails>
                                <messageFunction>183</messageFunction>
                                <additionalMessageFunction>M1</additionalMessageFunction>
                            </messageFunctionDetails>
                        </messageActionDetails>
                    ' . $ite_query . '
                    </Air_SellFromRecommendation>
                    </soapenv:Body>
                    </soapenv:Envelope>';
            $soapAction = "ITAREQ_05_2_IA";
            $api_url = $this->api_url;
            $soap_url = $this->soap_url . $soapAction;
            $remarks = 'AirSellRecommdation(Amadeus)';
            $this->CI->custom_db->generate_static_response($SellRequest, 'Amadeus AirSellRecommdation Request');
            $airsell_response = $this->process_request($SellRequest, $api_url, $remarks, $soap_url);
            $this->CI->custom_db->generate_static_response($airsell_response, 'Amadeus AirSellRecommdation Response');
            $Airsell_Response = array();
            if ($airsell_response) {
                $Airsell_Response = $this->xml2array($airsell_response);
                /* debug($Airsell_Response); exit();*/
                $SecuritySession = $Airsell_Response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SessionId'];
                $SequenceNumber = $Airsell_Response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SequenceNumber'];
                $SequenceNumber = ($SequenceNumber + 1);
                $SecurityToken = $Airsell_Response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SecurityToken'];
                /*$this->Security_SignOut($SecuritySession,$SequenceNumber,$SecurityToken);
                debug($Airsell_Response);
                exit; */
                if (isset($Airsell_Response['soapenv:Envelope']['soapenv:Body']['soap:Fault']) || isset($Airsell_Response['soapenv:Envelope']['soapenv:Body']['Fare_PricePNRWithBookingClassReply']['applicationError']) || isset($Airsell_Response['soap:Envelope']['soap:Body']['soap:Fault'])) {

                    $this->Security_SignOut($SecuritySession, $SequenceNumber, $SecurityToken);
                } else {
                    //if not error checking the segment confirmation 
                    $segmentResult1 = array();
                    $segmentResult = array();
                    $status_msg = "";
                    $status_flag = "";
                    $status = array();
                    if (isset($Airsell_Response['soapenv:Envelope']['soapenv:Body']['Air_SellFromRecommendationReply']['itineraryDetails'])) {
                        $segmentResult1 = $Airsell_Response['soapenv:Envelope']['soapenv:Body']['Air_SellFromRecommendationReply']['itineraryDetails'];
                        $segmentResult = force_multple_data_format($segmentResult1);
                        if (valid_array($segmentResult)) {
                            $flag = "TRUE";
                            for ($si = 0; $si < (count($segmentResult)); $si++) {
                                $segmentInformation = array();
                                if (!isset($segmentResult[$si]['segmentInformation'][0]))
                                    $segmentInformation[0] = $segmentResult[$si]['segmentInformation'];
                                else
                                    $segmentInformation = $segmentResult[$si]['segmentInformation'];

                                for ($s = 0; $s < (count($segmentInformation)); $s++) {
                                    $statusCode = $segmentInformation[$s]['actionDetails']['statusCode'];

                                    if ($statusCode == "OK") {
                                        $status[$si][$s] = "Sold";
                                        if ($flag == "TRUE")
                                            $status_flag = "true";
                                        else
                                            $status_flag = "false";
                                    } else if ($statusCode == "UNS") {
                                        $status[$si][$s] = "Unable to sell";
                                        $status_flag = "false";
                                        $flag = "FALSE";
                                    } else if ($statusCode == "WL") {
                                        $status[$si][$s] = "Wait listed";
                                        $status_flag = "false";
                                        $flag = "FALSE";
                                    } else if ($statusCode == "X") {
                                        $status[$si][$s] = "Cancelled after a successful sell";
                                        $status_flag = "false";
                                        $flag = "FALSE";
                                    } else if ($statusCode == "RQ") {
                                        $status[$si][$s] = "Sell was not even attempted";
                                        $status_flag = "false";
                                        $flag = "FALSE";
                                    }
                                }
                            } //close the segment checking
                        }
                        if ($status_flag == "false") {
                            //if any one of segment not confirm need to close the session
                            $this->Security_SignOut($SecuritySession, $SequenceNumber, $SecurityToken);
                        } elseif ($status_flag == "true") {
                            //segment confirm proceed further
                            $response['status'] = SUCCESS_STATUS;
                            $response['data']['SessionId'] = $SecuritySession;
                            $response['data']['SecurityToken'] = $SecurityToken;
                            $response['data']['SequenceNumber'] = $SequenceNumber;
                        }
                    }
                }
            }
        }
        return $response;
    }
    private function format_passenger_data($booking_params)
    {

        $passenger_details = $booking_params['Passengers'];
        $booking_passenger_arr = array();
        $contact_email = '';
        $contact_no = '';
        $country_code  = '';
        //PaxType 1 -adult,2-child,3 -infant                    
        if (valid_array($passenger_details)) {
            foreach ($passenger_details as $p_key => $p_value) {
                if ($p_key == 0) {
                    $contact_email = $p_value['Email'];
                    $contact_no = $p_value['ContactNo'];
                    $country_code = $p_value['CountryCode'];
                }
                if ($p_value['PaxType'] == 1) {
                    $booking_passenger_arr['ADT'][] = $p_value;
                } elseif ($p_value['PaxType'] == 2) {
                    $booking_passenger_arr['CHD'][] = $p_value;
                } elseif ($p_value['PaxType'] == 3) {
                    $booking_passenger_arr['INF'][] = $p_value;
                }
            }
        }
        return array('pax' => $booking_passenger_arr, 'contact_email' => $contact_email, 'contact_no' => $contact_no, 'country_code' => $country_code);
    }
    /**
     *Saving the info in PNR
     *@param $counter - 0  saving the passenger data in PNR,10 status of PNR,11 adding the meal to the PNR 
     *@param $parent_pnr - PNR Number from counter 1    
     */

    private function PNR_AddMultiElements_0($counter, $parent_pnr, $header_data, $booking_params, $seat_info = array(), $reconfirm_meal = false, $is_seat_req = false)
    {

        $response['status'] = FAILURE_STATUS;
        $response['data'] = array();
        $response['message'] = '';
        //PaxType 1 -adult,2-child,3 -infant
        //added these
        $airCode = $booking_params['flight_data']['FlightDetails']['Details'][0][0]['OperatorCode'];
        switch ($airCode) {

                //air india
            case 'AI':
                $FM_adult_com = 1.5;
                $FM_child_com = 1.5;
                $FM_infant_com = 1.5;
                break;
                //thai smile
            case 'WE':
                $FM_adult_com = 5;
                $FM_child_com = 5;
                $FM_infant_com = 5;
                break;
                //sichuan airlines
            case '3U':
                if ($this->fm_origin === 'KTM') {
                    $FM_adult_com = 7;
                    $FM_child_com = 7;
                    $FM_infant_com = 7;
                } else {
                    $FM_adult_com = 1;
                    $FM_child_com = 1;
                    $FM_infant_com = 1;
                }

                break;
                //klm royal dutch
            case 'KL':
                $FM_adult_com = 3;
                $FM_child_com = 3;
                $FM_infant_com = 3;
                break;
                //srilankan air
            case 'UL':
                $FM_adult_com = 2;
                $FM_child_com = 2;
                $FM_infant_com = 2;
                break;
                //phillipines airlines
            case 'PR':
                $FM_adult_com = 3;
                $FM_child_com = 3;
                $FM_infant_com = 3;
                break;
                //batik or malindo
            case 'OD':
                $FM_adult_com = 5;
                $FM_child_com = 5;
                $FM_infant_com = 5;
                break;

                // thai lions 
            case 'SL':
                $FM_adult_com = 3;
                $FM_child_com = 3;
                $FM_infant_com = 3;
                break;

                //china southern
            case 'CZ':
                $FM_adult_com = 4;
                $FM_child_com = 4;
                $FM_infant_com = 4;
                break;

                //korean airlines
            case 'KE':
                if (strtolower($this->fm_origin) == 'kathmandu' || strtolower($this->fm_origin) == 'ktm') {
                    $FM_adult_com = 5;
                    $FM_child_com = 5;
                    $FM_infant_com = 5;
                } else {
                    $FM_adult_com = 0;
                    $FM_child_com = 0;
                    $FM_infant_com = 0;
                }

                break;

                //biman bangla
            case 'BG':
                $FM_adult_com = 7;
                $FM_child_com = 7;
                $FM_infant_com = 7;
                break;
                // air china 
            case 'CA':
                if (strtolower($this->fm_origin) == 'kathmandu' || strtolower($this->fm_origin) == 'ktm') {
                    $FM_adult_com = 5;
                    $FM_child_com = 5;
                    $FM_infant_com = 5;
                } else {
                    $FM_adult_com = 0;
                    $FM_child_com = 0;
                    $FM_infant_com = 0;
                }

                break;


            default:
                $FM_adult_com = 0;
                $FM_child_com = 0;
                $FM_infant_com = 0;
        }
        $booking_passenger_arr = array();
        $country_code_num = '+1';
        $last_ticketing_date = $booking_params['flight_data']['Price']['passenger_breakup']['ADT']['LAST_TICKET_DATE'];
        $format_pax_data = $this->format_passenger_data($booking_params);
        $booking_passenger_arr = $format_pax_data['pax'];
        $contact_email = $format_pax_data['contact_email'];
        $contact_no = $format_pax_data['contact_no'];
        $country_code = $format_pax_data['country_code'];
        $country_code_data  = $this->CI->custom_db->single_table_records('api_country_list', '*', array('iso_country_code' => $country_code));
        if ($country_code_data['status'] == 1) {
            $country_code_num = $country_code_data['data'][0]['country_code'];
        }

        $traveller_info = '';
        $pax_count = 1;
        $seat_pt_count = 2;
        $seat_format_xml = '';
        //appending adult + infant
        $inf_count_start = 2;
        $commission_tag_count = 2;
        $commission_info = '';
        foreach ($booking_passenger_arr['ADT'] as $key => $value) {
            $traveller_info .= '<travellerInfo>
                                <elementManagementPassenger>
                                    <reference>
                                        <qualifier>PR</qualifier>
                                        <number>' . $pax_count . '</number>
                                    </reference>
                                    <segmentName>NM</segmentName>
                                </elementManagementPassenger>';
            $commission_info .= '<dataElementsIndiv>
                              <elementManagementData>
                                <reference>
                                  <qualifier>OT</qualifier>
                                  <number>' . $pax_count . '</number>
                                </reference>
                                <segmentName>FM</segmentName>
                              </elementManagementData>
                              <commission>
                                <passengerType>ADT</passengerType>
                                <commissionInfo>
                                  <percentage>' . $FM_adult_com . '</percentage>
                                </commissionInfo>
                              </commission>
                              <referenceForDataElement>
                                <reference>
                                  <qualifier>PT</qualifier>
                                  <number>' . $commission_tag_count . '</number>
                                </reference>
                              </referenceForDataElement>
                            </dataElementsIndiv>';

            if (isset($booking_passenger_arr['INF'][$key])) {
                $commission_info .= '<dataElementsIndiv>
                              <elementManagementData>
                                <reference>
                                  <qualifier>OT</qualifier>
                                  <number>' . $pax_count . '</number>
                                </reference>
                                <segmentName>FM</segmentName>
                              </elementManagementData>
                              <commission>
                                <passengerType>INF</passengerType>
                                <commissionInfo>
                                  <percentage>' . $FM_infant_com . '</percentage>
                                </commissionInfo>
                              </commission>
                              <referenceForDataElement>
                                <reference>
                                  <qualifier>PT</qualifier>
                                  <number>' . $commission_tag_count . '</number>
                                </reference>
                              </referenceForDataElement>
                            </dataElementsIndiv>';

                $traveller_info .= '<passengerData>
                                        <travellerInformation>
                                            <traveller>
                                                <surname>' . $value['LastName'] . '</surname>
                                                <quantity>2</quantity>
                                            </traveller>
                                            <passenger>
                                                <firstName>' . $value['FirstName'] . '</firstName>
                                                <type>ADT</type>
                                                <infantIndicator>3</infantIndicator>
                                            </passenger>
                                        </travellerInformation>
                                    </passengerData>';
                $traveller_info .= '<passengerData>
                                <travellerInformation>
                                    <traveller>
                                        <surname>' . $booking_passenger_arr['INF'][$key]['LastName'] . '</surname>
                                    </traveller>
                                <passenger>
                                    <firstName>' . $booking_passenger_arr['INF'][$key]['FirstName'] . '</firstName>
                                    <type>INF</type>
                                </passenger>
                                </travellerInformation>
                                <dateOfBirth>
                                    <dateAndTimeDetails> 
                                        <date>' . date('dMy', strtotime($booking_passenger_arr['INF'][$key]['DateOfBirth'])) . '</date>
                                    </dateAndTimeDetails>
                                </dateOfBirth>
                            </passengerData>';

                $commission_tag_count = $commission_tag_count + 2;
            } else {
                $traveller_info .= '<passengerData>
                                    <travellerInformation>
                                        <traveller>
                                            <surname>' . $value['LastName'] . '</surname>
                                            <quantity>1</quantity>
                                        </traveller>
                                        <passenger>
                                            <firstName>' . $value['FirstName'] . '</firstName>
                                            <type>ADT</type>
                                        </passenger>
                                    </travellerInformation>
                                </passengerData>';
                $commission_tag_count++;
            }

            $traveller_info .= '</travellerInfo>';
            $pax_count++;

            //checking seat request
            if (isset($value['SeatId'])) {
                if (valid_array($value['SeatId'])) {
                    foreach ($value['SeatId'] as $s_key => $s_value) {
                        $seat_data = Common_Flight::read_record($s_value);
                        $seat_data = json_decode($seat_data[0], true);
                        $SeatIddata = array_values(unserialized_data($seat_data['SeatId']));
                        $Segment_Type = $SeatIddata[0]['Segment_Type'];
                        $seat_format_xml .= '<dataElementsIndiv>
                    <elementManagementData>
                        <segmentName>STR</segmentName>
                    </elementManagementData>
                    <seatGroup>
                        <seatRequest>
                            <seat>
                                <type>RQST</type>
                            </seat>
                            <special>
                                <data>' . $seat_data['SeatNumber'] . '</data>
                            </special>
                        </seatRequest>
                    </seatGroup>
                    <referenceForDataElement>
                        <reference>
                            <qualifier>PT</qualifier>
                            <number>' . ($seat_pt_count) . '</number>
                        </reference>
                        <reference>
                            <qualifier>ST</qualifier>
                            <number>' . $Segment_Type . '</number>
                        </reference>
                    </referenceForDataElement>
                </dataElementsIndiv>';
                    }
                }
            }

            if (isset($booking_passenger_arr['INF'][$key])) {
                $seat_pt_count = $seat_pt_count + 2;
            } else {
                $seat_pt_count = $seat_pt_count + 1;
            }
            $inf_count_start++;
        }

        //creating child tag
        if (isset($booking_passenger_arr['CHD']) && valid_array($booking_passenger_arr['CHD'])) {
            foreach ($booking_passenger_arr['CHD'] as $c_key => $c_value) {
                $commission_info .= '<dataElementsIndiv>
                              <elementManagementData>
                                <reference>
                                  <qualifier>OT</qualifier>
                                  <number>' . $pax_count . '</number>
                                </reference>
                                <segmentName>FM</segmentName>
                              </elementManagementData>
                              <commission>
                                <passengerType>CHD</passengerType>
                                <commissionInfo>
                                  <percentage>' . $FM_child_com . '</percentage>
                                </commissionInfo>
                              </commission>
                              <referenceForDataElement>
                                <reference>
                                  <qualifier>PT</qualifier>
                                  <number>' . $commission_tag_count . '</number>
                                </reference>
                              </referenceForDataElement>
                            </dataElementsIndiv>';

                $traveller_info .= '<travellerInfo>
                                    <elementManagementPassenger>
                                        <reference>
                                            <qualifier>PR</qualifier>
                                            <number>' . $pax_count . '</number>
                                        </reference>
                                    <segmentName>NM</segmentName>
                                    </elementManagementPassenger>
                                    <passengerData>
                                        <travellerInformation>
                                            <traveller>
                                                <surname>' . $c_value['LastName'] . '</surname>
                                            </traveller>
                                            <passenger>
                                                <firstName>' . $c_value['FirstName'] . '</firstName>
                                                <type>CHD</type>
                                            </passenger>
                                        </travellerInformation>
                                        <dateOfBirth>
                                            <dateAndTimeDetails>  
                                                <date>' . date('dMy', strtotime($c_value['DateOfBirth'])) . '</date>
                                            </dateAndTimeDetails>
                                        </dateOfBirth>
                                    </passengerData>
                                </travellerInfo>';


                if (isset($c_value['SeatId'])) {
                    if (valid_array($c_value['SeatId'])) {
                        foreach ($c_value['SeatId'] as $sc_key => $sc_value) {
                            $seat_data = Common_Flight::read_record($sc_value);
                            $seat_data = json_decode($seat_data[0], true);
                            $SeatIddata = array_values(unserialized_data($seat_data['SeatId']));
                            $Segment_Type = $SeatIddata[0]['Segment_Type'];

                            $seat_format_xml .= '<dataElementsIndiv>
                                <elementManagementData>
                                    <segmentName>STR</segmentName>
                                </elementManagementData>
                                <seatGroup>
                                    <seatRequest>
                                        <seat>
                                            <type>RQST</type>
                                        </seat>
                                        <special>
                                            <data>' . $seat_data['SeatNumber'] . '</data>
                                        </special>
                                    </seatRequest>
                                </seatGroup>
                                <referenceForDataElement>
                                    <reference>
                                        <qualifier>PT</qualifier>
                                        <number>' . ($seat_pt_count) . '</number>
                                    </reference>
                                    <reference>
                                        <qualifier>ST</qualifier>
                                        <number>' . ($Segment_Type) . '</number>
                                    </reference>
                                </referenceForDataElement>
                            </dataElementsIndiv>';
                            $seat_pt_count++;
                        }
                    }
                }
                $pax_count++;
                $commission_tag_count++;
            }

            $inf_count_start++;
        }
        if ($traveller_info) {
            $SecuritySession = $header_data['SessionId'];
            $SequenceNumber = $header_data['SequenceNumber'];
            $SecurityToken = $header_data['SecurityToken'];
            $soapAction = 'PNRADD_21_1_1A';
            $PNR_AddMultiElements = '';
            $PNR_AddMultiElements .= '<?xml version="1.0" encoding="utf-8"?>
                        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
                        <soapenv:Header>
                            <awsse:Session TransactionStatusCode="InSeries" xmlns:awsse="http://xml.amadeus.com/2010/06/Session_v3">
                                <awsse:SessionId>' . $SecuritySession . '</awsse:SessionId>
                                <awsse:SequenceNumber>' . $SequenceNumber . '</awsse:SequenceNumber>
                                <awsse:SecurityToken>' . $SecurityToken . '</awsse:SecurityToken>
                            </awsse:Session>
                            <add:MessageID xmlns:add="http://www.w3.org/2005/08/addressing">' . $this->getuuid() . '</add:MessageID>
                            <add:Action xmlns:add="http://www.w3.org/2005/08/addressing">http://webservices.amadeus.com/' . $soapAction . '</add:Action>
                            <add:To xmlns:add="http://www.w3.org/2005/08/addressing">' . $this->api_url . '</add:To>
                        </soapenv:Header>
                        <soapenv:Body>
                            <PNR_AddMultiElements xmlns="http://xml.amadeus.com/' . $soapAction . '" >
                            ';
            $PNR_AddMultiElements .= '
                   <pnrActions>
                        <optionCode>0</optionCode>
                    </pnrActions>
                     ' . $traveller_info;
            $PNR_AddMultiElements .= '
                    <dataElementsMaster>
                    <marker1/>
                    ' . $seat_format_xml . '                   
                    <dataElementsIndiv>
                        <elementManagementData>
                            <reference>
                                <qualifier>OT</qualifier>
                                <number>1</number>
                            </reference>
                            <segmentName>RF</segmentName>
                        </elementManagementData>
                        <freetextData>
                            <freetextDetail>
                                <subjectQualifier>3</subjectQualifier>
                                <type>P22</type>
                            </freetextDetail>
                            <longFreetext>TINGU</longFreetext>
                        </freetextData>
                    </dataElementsIndiv>

                    <dataElementsIndiv>
                        <elementManagementData>
                            <reference>
                                <qualifier>OT</qualifier>
                                <number>8</number>
                            </reference>
                            <segmentName>FP</segmentName>
                        </elementManagementData>
                        <formOfPayment>
                            <fop>
                                <identification>CA</identification>
                            </fop>
                        </formOfPayment>
                    </dataElementsIndiv>

                    <dataElementsIndiv>
                         <elementManagementData>
                            <segmentName>TK</segmentName>
                         </elementManagementData>
                         <ticketElement>
                            <ticket>
                               <indicator>TL</indicator>
                               <date>' . date('dmy', strtotime($last_ticketing_date)) . '</date>
                            </ticket>
                         </ticketElement>
                    </dataElementsIndiv>
                    <dataElementsIndiv>
                        <elementManagementData>
                            <segmentName>AP</segmentName>
                        </elementManagementData>
                        <freetextData>
                            <freetextDetail>
                                <subjectQualifier>3</subjectQualifier>
                                <type>P02</type>
                            </freetextDetail>
                            <longFreetext>' . $contact_email . '</longFreetext>
                        </freetextData>
                    </dataElementsIndiv>
                    ' . $commission_info . '               
                    <dataElementsIndiv>
                        <elementManagementData>
                            <reference>
                                <qualifier>OT</qualifier>
                                <number>4</number>
                            </reference>
                            <segmentName>AP</segmentName>
                        </elementManagementData>
                        <freetextData>
                            <freetextDetail>
                                <subjectQualifier>3</subjectQualifier>
                                <type>7</type>
                            </freetextDetail>
                            <longFreetext>' . $country_code_num . $contact_no . '</longFreetext>
                        </freetextData>
                    </dataElementsIndiv>
                </dataElementsMaster>
            </PNR_AddMultiElements>
            </soapenv:Body>
            </soapenv:Envelope>';
            //echo $PNR_AddMultiElements;
            $api_url = $this->api_url;
            $soap_url = $this->soap_url . $soapAction;
            $remarks = 'PNR_AddMultiElements_Option0(amadeus)';
            $this->CI->custom_db->generate_static_response($PNR_AddMultiElements, 'Amadeus PNR_AddMultiElements_Option0 Request');
            $PNR_AddElements_Response = $this->process_request($PNR_AddMultiElements, $api_url, $remarks, $soap_url);
            /* debug($PNR_AddElements_Response);
             exit;*/
            $this->CI->custom_db->generate_static_response($PNR_AddElements_Response, 'Amadeus PNR_AddMultiElements_Option0 Response');

            // $PNR_AddElements_Response  = file_get_contents(FCPATH.'pnr_0.xml');
            $PNR_AddElements_Response_arr = array();
            if ($PNR_AddElements_Response) {
                $PNR_AddElements_Response_arr = $this->xml2array($PNR_AddElements_Response);
                /*debug('Library Amadeus 2023');
                debug($PNR_AddElements_Response_arr);*/
                $SessionId = $PNR_AddElements_Response_arr['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SessionId'];
                $SecurityToken = $PNR_AddElements_Response_arr['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SecurityToken'];
                $SequenceNumber = $PNR_AddElements_Response_arr['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SequenceNumber'];
                $SequenceNumber  = ($SequenceNumber + 1);
                /*$this->Security_SignOut($SessionId,$SequenceNumber,$SecurityToken);
                exit('2029');*/
                if (isset($PNR_AddElements_Response_arr['soapenv:Envelope']['soapenv:Body']['soap:Fault']) || isset($PNR_AddElements_Response_arr['soapenv:Envelope']['soapenv:Body']['PNR_Reply']['applicationError']) || isset($PNR_AddElements_Response_arr['soap:Envelope']['soap:Body']['soap:Fault'])) {
                    $this->Security_SignOut($SessionId, $SequenceNumber, $SecurityToken);
                } elseif (isset($PNR_AddElements_Response_arr['soapenv:Envelope']['soapenv:Body']['PNR_Reply']['pnrHeader'])) {
                    //if success
                    $response['status'] = SUCCESS_STATUS;
                    $response['message'] = 'success';
                    $response['data']['SessionId'] = $SessionId;
                    $response['data']['SecurityToken'] = $SecurityToken;
                    $response['data']['SequenceNumber'] = $SequenceNumber;
                }
            }
        }
        return $response;
    }
    /*PNR_AddMultiElements_BookingClass checking flight marketing carrier 9w*/
    private function PNR_AddMultiElements_BookingClass($header_data, $carrier, $search_id = "", $country = "")
    {
        $response['status'] = FAILURE_STATUS;
        $response['data'] = array();
        $response['message'] = '';
        $soapAction = trim('TPCBRQ_18_1_1A');
        $SessionId = $header_data['SessionId'];
        $SecurityToken = $header_data['SecurityToken'];
        $SequenceNumber = $header_data['SequenceNumber'];
        if ($search_id != "") {
            $clean_search_details = $this->CI->flight_model->get_safe_search_data($search_id);

            if ($clean_search_details['status'] == true) {
                $nq_response['status'] = true;
                $nq_response['data'] = $clean_search_details['data'];

                // 28/12/2014 00:00:00 - date format
                if ($clean_search_details['data']['trip_type'] == 'multicity') {
                    $nq_response['data']['from_city'] = $clean_search_details['data']['from'];
                    $nq_response['data']['to_city'] = $clean_search_details['data']['to'];
                    $nq_response['data']['depature'] = $clean_search_details['data']['depature'];
                    $nq_response['data']['return'] = $clean_search_details['data']['depature'];
                } else {
                    $nq_response['data']['from'] = substr(chop(substr($clean_search_details['data']['from'], -5), ')'), -3);
                    $nq_response['data']['to'] = substr(chop(substr($clean_search_details['data']['to'], -5), ')'), -3);
                    $nq_response['data']['depature'] = date("Y-m-d", strtotime($clean_search_details['data']['depature'])) . 'T00:00:00';
                    if (isset($clean_search_details['data']['return'])) {
                        $nq_response['data']['return'] = date("Y-m-d", strtotime($clean_search_details['data']['return'])) . 'T00:00:00';
                    }
                }
                // $country = $clean_search_details['data']['country'];
                if (is_array($nq_response['data']['from'])) {
                    if (in_array('KTM', $nq_response['data']['from'])) {
                        $nqOrigin = 'KTM';
                    }
                } else {
                    $nqOrigin = $nq_response['data']['from'];
                }
            }
        }
        //added these lines for exempting NQ tax
        $nq_tax = '';
        if (($nqOrigin == "KTM" || $nqOrigin == "ktm" || $nqOrigin == "Kathmandu" || $nqOrigin == "kathmandu") && ($country == "Nepalese" || $country == "Nepal" || $country = "nepal")) {
            $nq_tax = '<pricingOptionGroup>
                    <pricingOptionKey>
                    <pricingOptionKey>ET</pricingOptionKey>
                    </pricingOptionKey>
                    <taxInformation>
                    <taxQualifier>7</taxQualifier>
                    <taxType>
                    <isoCountry>NQ</isoCountry>
                    </taxType>
                    <taxData>
                    <taxRate>1130</taxRate>
                    <taxValueQualifier>A</taxValueQualifier>
                     </taxData>
                    </taxInformation>
                </pricingOptionGroup>';
        }
        $xml_query = '
            <?xml version="1.0" encoding="utf-8"?>
                <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
                <soapenv:Header>
                <awsse:Session TransactionStatusCode="InSeries" xmlns:awsse="http://xml.amadeus.com/2010/06/Session_v3">
                <awsse:SessionId>' . trim($SessionId) . '</awsse:SessionId>
                <awsse:SequenceNumber>' . trim($header_data['SequenceNumber']) . '</awsse:SequenceNumber>
                <awsse:SecurityToken>' . trim($header_data['SecurityToken']) . '</awsse:SecurityToken>
            </awsse:Session>
            <add:MessageID xmlns:add="http://www.w3.org/2005/08/addressing">' . $this->getuuid() . '</add:MessageID>
            <add:Action xmlns:add="http://www.w3.org/2005/08/addressing">http://webservices.amadeus.com/' . $soapAction . '</add:Action>
            <add:To xmlns:add="http://www.w3.org/2005/08/addressing">' . $this->api_url . '</add:To>
            </soapenv:Header>
            <soapenv:Body>
            <tpc:Fare_PricePNRWithBookingClass xmlns:tpc="http://xml.amadeus.com/' . $soapAction . '" >
                <tpc:pricingOptionGroup>
                    <tpc:pricingOptionKey>
                        <tpc:pricingOptionKey>RP</tpc:pricingOptionKey>
                    </tpc:pricingOptionKey>
                </tpc:pricingOptionGroup>
                
                <tpc:pricingOptionGroup>
                    <tpc:pricingOptionKey>
                        <tpc:pricingOptionKey>RU</tpc:pricingOptionKey>
                    </tpc:pricingOptionKey>
                </tpc:pricingOptionGroup>
                <tpc:pricingOptionGroup>
                    <tpc:pricingOptionKey>
                        <tpc:pricingOptionKey>VC</tpc:pricingOptionKey>
                    </tpc:pricingOptionKey>
                    <tpc:carrierInformation>
                        <tpc:companyIdentification>
                            <tpc:otherCompany>' . $carrier . '</tpc:otherCompany>
                        </tpc:companyIdentification>
                    </tpc:carrierInformation>
                    </tpc:pricingOptionGroup>'
            . $nq_tax .

            '</tpc:Fare_PricePNRWithBookingClass>
            </soapenv:Body>
            </soapenv:Envelope>';

        if ($xml_query) {
            $soap_url = $this->soap_url . $soapAction;
            $remarks = 'PNR_AddMultiElements_BookingClass(amadeus)';
            $this->CI->custom_db->generate_static_response($xml_query, 'Amadeus Flight PNR_AddMultiElements_BookingClass Request');
            $PNR_Booking_Class = $this->process_request(trim($xml_query), $this->api_url, $remarks, $soap_url);
            $this->CI->custom_db->generate_static_response($PNR_Booking_Class, 'Amadeus Flight PNR_AddMultiElements_BookingClass Response');
            //$PNR_Booking_Class  = file_get_contents(FCPATH.'fare_class.xml');
            $PNR_Booking_Class_arr = array();
            if ($PNR_Booking_Class) {
                $PNR_Booking_Class_arr = $this->xml2array($PNR_Booking_Class);
                if (isset($PNR_Booking_Class_arr['soapenv:Envelope']['soapenv:Header'])) {

                    $SessionId = $PNR_Booking_Class_arr['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SessionId'];
                    $SecurityToken = $PNR_Booking_Class_arr['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SecurityToken'];
                    $SequenceNumber =  $PNR_Booking_Class_arr['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SequenceNumber'];
                    $SequenceNumber = ($SequenceNumber + 1);
                }
                if (isset($PNR_Booking_Class_arr['soapenv:Envelope']['soapenv:Body']['soap:Fault']) || isset($PNR_Booking_Class_arr['soapenv:Envelope']['soapenv:Body']['Fare_PricePNRWithBookingClassReply']['applicationError']) || isset($PNR_Booking_Class_arr['soap:Envelope']['soap:Body']['soap:Fault'])) {

                    $this->Security_SignOut($SessionId, $SequenceNumber, $SecurityToken);
                } else {
                    $response['status'] = SUCCESS_STATUS;
                    $response['message'] = 'success';
                    $response['data']['SessionId'] = $SessionId;
                    $response['data']['SecurityToken'] = $SecurityToken;
                    $response['data']['SequenceNumber'] = $SequenceNumber;
                }
            }
        }
        return $response;
    }
    //Checking the price again
    private function Ticket_CreateTSTFromPricing($search_data, $header_data)
    {
        $response['status'] = FAILURE_STATUS;
        $response['data'] = array();
        $response['message'] = '';
        $SessionId = $header_data['SessionId'];
        $SequenceNumber = $header_data['SequenceNumber'];
        $SecurityToken = $header_data['SecurityToken'];
        $paList = '';
        if ($search_data['adult_config'] > 0) {
            $paList .= '<psaList>
                        <itemReference>
                            <referenceType>TST</referenceType>
                            <uniqueReference>1</uniqueReference>
                        </itemReference>
                    </psaList>';
        }
        if ($search_data['child_config'] > 0) {
            $paList .= '<psaList>
                        <itemReference>
                            <referenceType>TST</referenceType>
                            <uniqueReference>2</uniqueReference>
                        </itemReference>
                    </psaList>';
        }
        if (($search_data['infant_config'] > 0 && $search_data['child_config'] > 0)) {
            $paList .= '<psaList>
                        <itemReference>
                            <referenceType>TST</referenceType>
                            <uniqueReference>3</uniqueReference>
                        </itemReference>
                    </psaList>';
        } elseif (($search_data['infant_config'] > 0 && $search_data['child_config'] == 0)) {
            $paList .= '<psaList>
                        <itemReference>
                            <referenceType>TST</referenceType>
                            <uniqueReference>2</uniqueReference>
                        </itemReference>
                    </psaList>';
        }

        $soapAction = 'TAUTCQ_04_1_1A';
        $xml_query = '<?xml version="1.0" encoding="utf-8"?>
                <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
                <soapenv:Header>
                <awsse:Session TransactionStatusCode="InSeries" xmlns:awsse="http://xml.amadeus.com/2010/06/Session_v3">
                <awsse:SessionId>' . $SessionId . '</awsse:SessionId>
                <awsse:SequenceNumber>' . $SequenceNumber . '</awsse:SequenceNumber>
                <awsse:SecurityToken>' . $SecurityToken . '</awsse:SecurityToken>
                </awsse:Session>
                <add:MessageID xmlns:add="http://www.w3.org/2005/08/addressing">' . $this->getuuid() . '</add:MessageID>
                <add:Action xmlns:add="http://www.w3.org/2005/08/addressing">http://webservices.amadeus.com/' . $soapAction . '</add:Action>
                <add:To xmlns:add="http://www.w3.org/2005/08/addressing">' . $this->api_url . '</add:To>
                </soapenv:Header>
                <soapenv:Body>
                <taut:Ticket_CreateTSTFromPricing xmlns:taus="http://xml.amadeus.com/' . $soapAction . '">
                ' . $paList . '
                </taut:Ticket_CreateTSTFromPricing>
                </soapenv:Body>
                </soapenv:Envelope>';

        $soap_url = $this->soap_url . $soapAction;
        $this->CI->custom_db->generate_static_response($xml_query, 'Amadeus Ticket_CreateTSTFromPricing Request');
        $api_response = $this->process_request($xml_query, $this->api_url, 'TicketPricingReq(amadeus)', $soap_url);
        $this->CI->custom_db->generate_static_response($api_response, 'Amadeus Ticket_CreateTSTFromPricing Response');
        //$api_response =  file_get_contents(FCPATH.'ticket.xml');
        if ($api_response) {
            $api_response = $this->xml2array($api_response);
            if (isset($api_response['soapenv:Envelope']['soapenv:Header'])) {
                $SessionId = $api_response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SessionId'];
                $SecurityToken = $api_response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SecurityToken'];
                $SequenceNumber =  $api_response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SequenceNumber'];
                $SequenceNumber = ($SequenceNumber + 1);
            }
            if (isset($api_response['soapenv:Envelope']['soapenv:Body']['soap:Fault']) || isset($api_response['soapenv:Envelope']['soapenv:Body']['Ticket_CreateTSTFromPricingReply']['applicationError']) || isset($api_response['soap:Envelope']['soap:Body']['soap:Fault'])) {
                $this->Security_SignOut($SessionId, $SequenceNumber, $SecurityToken);
            } else {
                $response['status'] = SUCCESS_STATUS;
                $response['message'] = 'success';
                $response['data']['SessionId'] = $SessionId;
                $response['data']['SecurityToken'] = $SecurityToken;
                $response['data']['SequenceNumber'] = $SequenceNumber;
            }
        }
        return $response;
    }
    //Sending the form of payment
    private function FOP($booking_params, $carrier, $header_data)
    {
        $response['status'] = FAILURE_STATUS;
        $response['data'] = array();
        $response['message'] = '';
        $CardInfo = @$booking_params['CardInfo'];
        $SessionId = $header_data['SessionId'];
        $SequenceNumber = $header_data['SequenceNumber'];
        $SecurityToken = $header_data['SecurityToken'];
        $cardNumber = $CardInfo['card_number'];
        $cvv_code = $CardInfo['card_cvv'];
        $exp_date = implode("", explode("-", $CardInfo['expire_date']));
        $expire_date = $exp_date;
        $cardholdername = $CardInfo['holder_name'];

        $soapAction = 'TFOPCQ_19_2_1A';
        $fop_tag = '';
        //if($booking_params['Module']==B2B_USER){
        /*$fop_tag ='<fopInformation>
                            <formOfPayment>
                                <type>CASH</type>
                            </formOfPayment>
                        </fopInformation>
                        <dummy/>';*/
        /*}else{
            $fop_tag =' <fopInformation>
                            <formOfPayment>
                                <type>CC</type>
                            </formOfPayment>
                        </fopInformation>
                        <dummy/>
                        <creditCardData>
                            <creditCardDetails>
                                <ccInfo>
                                    <vendorCode>VI</vendorCode>
                                    <cardNumber>'.$cardNumber.'</cardNumber>
                                    <securityId>'.$cvv_code.'</securityId>
                                    <expiryDate>'.$expire_date.'</expiryDate>
                                    <ccHolderName>'.$cardholdername.'</ccHolderName>
                                </ccInfo>
                            </creditCardDetails>
                        </creditCardData>';
        }*/

        //<securityId>737</securityId>
        /*$fop_tag =' <fopInformation>
                            <formOfPayment>
                                <type>CC</type>
                            </formOfPayment>
                        </fopInformation>
                        <dummy/>
                        <creditCardData>
                            <creditCardDetails>
                                <ccInfo>
                                    <vendorCode>VI</vendorCode>
                                    <cardNumber>4622670370960502</cardNumber>
                                    <expiryDate>1222</expiryDate>
                                    <ccHolderName>qwer</ccHolderName>
                                </ccInfo>
                            </creditCardDetails>
                        </creditCardData>';*/
        /*$fop_tag ='<fopInformation>
                            <formOfPayment>
                                <type>CASH</type>
                            </formOfPayment>
                        </fopInformation>
                        <dummy/>';*/
        /*$xml_query_old = '<?xml version="1.0" encoding="utf-8"?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
    <soapenv:Header>
        <awsse:Session TransactionStatusCode="InSeries" xmlns:awsse="http://xml.amadeus.com/2010/06/Session_v3">
            <awsse:SessionId>'.$SessionId.'</awsse:SessionId>
            <awsse:SequenceNumber>'.$SequenceNumber.'</awsse:SequenceNumber>
            <awsse:SecurityToken>'.$SecurityToken.'</awsse:SecurityToken>
        </awsse:Session>
        <add:MessageID xmlns:add="http://www.w3.org/2005/08/addressing">'.$this->getuuid().'</add:MessageID>
        <add:Action xmlns:add="http://www.w3.org/2005/08/addressing">http://webservices.amadeus.com/'.$soapAction.'</add:Action>
        <add:To xmlns:add="http://www.w3.org/2005/08/addressing">'.$this->api_url.'</add:To>
    </soapenv:Header>
    <soapenv:Body>
        <FOP_CreateFormOfPayment xmlns="http://xml.amadeus.com/'.$soapAction.'">
            <fopGroup>
                <fopReference/>
                <mopDescription>
                    <fopSequenceNumber>
                        <sequenceDetails>
                            <number>1</number>
                        </sequenceDetails>
                    </fopSequenceNumber>
                    <mopDetails>
                        <fopPNRDetails>
                            <fopDetails>
                                <fopCode>CCVI</fopCode>
                            </fopDetails>
                        </fopPNRDetails>
                    </mopDetails>
                    <paymentModule>
                        <groupUsage>
                            <attributeDetails>
                                <attributeType>FP</attributeType>
                            </attributeDetails>
                        </groupUsage>
                        <paymentData>
                            <merchantInformation>
                                <companyCode>'.$carrier.'</companyCode>
                            </merchantInformation>
                        </paymentData>
                        <mopInformation>
                           '.$fop_tag.'
                        </mopInformation>
                        <dummy/>
                    </paymentModule>
                </mopDescription>
            </fopGroup>
        </FOP_CreateFormOfPayment>
    </soapenv:Body>
</soapenv:Envelope>';*/
        $xml_query = '<?xml version="1.0" encoding="utf-8"?>
                    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
                        <soapenv:Header>
                            <awsse:Session TransactionStatusCode="InSeries" xmlns:awsse="http://xml.amadeus.com/2010/06/Session_v3">
                                <awsse:SessionId>' . $SessionId . '</awsse:SessionId>
                                <awsse:SequenceNumber>' . $SequenceNumber . '</awsse:SequenceNumber>
                                <awsse:SecurityToken>' . $SecurityToken . '</awsse:SecurityToken>
                            </awsse:Session>
                            <add:MessageID xmlns:add="http://www.w3.org/2005/08/addressing">' . $this->getuuid() . '</add:MessageID>
                            <add:Action xmlns:add="http://www.w3.org/2005/08/addressing">http://webservices.amadeus.com/' . $soapAction . '</add:Action>
                            <add:To xmlns:add="http://www.w3.org/2005/08/addressing">' . $this->api_url . '</add:To>
                        </soapenv:Header>
                        <soapenv:Body>
                            <FOP_CreateFormOfPayment xmlns="http://xml.amadeus.com/' . $soapAction . '">
                                <fopGroup>
                                    <fopReference/>
                                    <mopDescription>
                                        <fopSequenceNumber>
                                            <sequenceDetails>
                                                <number>1</number>
                                            </sequenceDetails>
                                        </fopSequenceNumber>
                                        <mopDetails>
                                            <fopPNRDetails>
                                                <fopDetails>
                                                    <fopCode>CASH</fopCode>
                                                </fopDetails>
                                            </fopPNRDetails>
                                        </mopDetails>
                                        <paymentModule>
                                            <groupUsage>
                                                <attributeDetails>
                                                    <attributeType>FP</attributeType>
                                                </attributeDetails>
                                            </groupUsage>
                                            <paymentData>
                                                <merchantInformation>
                                                    <companyCode>' . $carrier . '</companyCode>
                                                </merchantInformation>
                                            </paymentData>
                                            <mopInformation>
                                                <fopInformation>
                                                    <formOfPayment>
                                                        <type>CA</type>
                                                    </formOfPayment>
                                                </fopInformation>
                                                <dummy />
                                            </mopInformation>
                                            <dummy/>
                                        </paymentModule>
                                    </mopDescription>
                                </fopGroup>
                            </FOP_CreateFormOfPayment>
                        </soapenv:Body>
                    </soapenv:Envelope>';
        $soap_url = $this->soap_url . $soapAction;
        $fop_xml_response = $this->process_request($xml_query, $this->api_url, 'formOfPayment(amadeus)', $soap_url);
        // $fop_xml_response =  file_get_contents(FCPATH.'fop.xml');
        if ($fop_xml_response) {
            $fop_xml_response = $this->xml2array($fop_xml_response);
            if (isset($fop_xml_response['soapenv:Envelope']['soapenv:Header'])) {
                $SessionId = $fop_xml_response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SessionId'];
                $SecurityToken = $fop_xml_response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SecurityToken'];
                $SequenceNumber =  $fop_xml_response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SequenceNumber'];
                $SequenceNumber = ($SequenceNumber + 1);
            }
            if (isset($fop_xml_response['soapenv:Envelope']['soapenv:Body']['FOP_CreateFormOfPaymentReply']['fopDescription'])) {

                $response['status'] = SUCCESS_STATUS;
                $response['message'] = 'success';
                $response['data']['SessionId'] = $SessionId;
                $response['data']['SecurityToken'] = $SecurityToken;
                $response['data']['SequenceNumber'] = $SequenceNumber;
            } else {
                $this->Security_SignOut($SessionId, $SequenceNumber, $SecurityToken);
            }
        }
        return $response;
    }
    //checking the pnr status 
    private function PNR_AddMultiElements_10($header_data, $booking_params, $search_data)
    {
        $response['status'] = FAILURE_STATUS;
        $response['data'] = array();
        $response['message'] = '';

        $passenger_xml = '';
        $passenger_data = $this->format_passenger_data($booking_params);
        $booking_passenger_arr = $passenger_data['pax'];
        $pt_count = 2;
        $SessionId = $header_data['SessionId'];
        $SequenceNumber = $header_data['SequenceNumber'];
        $SecurityToken = $header_data['SecurityToken'];
        if ($search_data['is_domestic'] == false) {
            if ($booking_passenger_arr['ADT']) {
                foreach ($booking_passenger_arr['ADT'] as $key => $value) {
                    if ($value['Gender'] == 1 || $value['Gender'] == 6) {
                        $title = 'M';
                    } else {
                        $title = 'F';
                    }
                    $dob = strtoupper(strtolower(date('dMy', strtotime($value['DateOfBirth']))));
                    $PassportIssuingC = strtoupper($value['PassporIssuingCountry']);
                    $passport_no = strtoupper(strtolower($value['PassportNumber']));
                    $expire = strtoupper(strtolower(date('dMy', strtotime($value['PassportExpiry']))));
                    $CountryCode = $value['CountryCode'];
                    $full_name = $value['LastName'] . '/' . $value['FirstName'];
                    // $passport_info = 'P/IN/IND906/IN/19JAN78/M/24APR25/PANDEY/RAHUL';
                    //sending requests according to id type
                    if ($value['identification_type'] === 'citizenship') {
                        $passport_info = 'C/' . $CountryCode . '/' . $passport_no . '/' . $PassportIssuingC . '/' . $dob . '/' . $title . '/' . $expire . '/' . $full_name;
                    } else {
                        $passport_info = 'P/' . $CountryCode . '/' . $passport_no . '/' . $PassportIssuingC . '/' . $dob . '/' . $title . '/' . $expire . '/' . $full_name;
                    }
                    // $passport_info = 'P/' . $CountryCode . '/' . $passport_no . '/' . $PassportIssuingC . '/' . $dob . '/' . $title . '/' . $expire . '/' . $full_name;

                    $passenger_xml .= '<dataElementsIndiv>
                                        <elementManagementData>
                                             <segmentName>SSR</segmentName>
                                        </elementManagementData>
                                        <serviceRequest>
                                            <ssr>
                                                <type>DOCS</type>
                                                <status>HK</status>
                                                <quantity>1</quantity>
                                                <companyId>YY</companyId>
                                                <freetext>' . $passport_info . '</freetext>
                                            </ssr>
                                        </serviceRequest>
                                        <referenceForDataElement>
                                            <reference>
                                                <qualifier>PT</qualifier>
                                            <number>' . $pt_count . '</number>
                                            </reference>
                                        </referenceForDataElement>
                                    </dataElementsIndiv>';
                    if (isset($booking_passenger_arr['INF'][$key])) {
                        $f_value = $booking_passenger_arr['INF'][$key];

                        if ($f_value['Gender'] == 1 || $f_value['Gender'] == 6) {
                            $title = 'MI';
                        } else {
                            $title = 'FI';
                        }
                        $dob = strtoupper(strtolower(date('dMy', strtotime($f_value['DateOfBirth']))));
                        $PassportIssuingC = strtoupper($f_value['PassporIssuingCountry']);
                        $passport_no = strtoupper(strtolower($f_value['PassportNumber']));
                        $expire = strtoupper(strtolower(date('dMy', strtotime($f_value['PassportExpiry']))));
                        $CountryCode = $f_value['CountryCode'];
                        $full_name = $f_value['LastName'] . '/' . $f_value['FirstName'];
                        //request according to id type
                        if ($value['identification_type'] === 'citizenship') {
                            $passport_info = 'C/' . $CountryCode . '/' . $passport_no . '/' . $PassportIssuingC . '/' . $dob . '/' . $title . '/' . $expire . '/' . $full_name;
                        } else {
                            $passport_info = 'P/' . $CountryCode . '/' . $passport_no . '/' . $PassportIssuingC . '/' . $dob . '/' . $title . '/' . $expire . '/' . $full_name;
                        }
                        // $passport_info = 'P/' . $CountryCode . '/' . $passport_no . '/' . $PassportIssuingC . '/' . $dob . '/' . $title . '/' . $expire . '/' . $full_name;
                        $passenger_xml .= '<dataElementsIndiv>
                                        <elementManagementData>
                                             <segmentName>SSR</segmentName>
                                        </elementManagementData>
                                        <serviceRequest>
                                            <ssr>
                                                <type>DOCS</type>
                                                <status>HK</status>
                                                <quantity>1</quantity>
                                                <companyId>YY</companyId>
                                                <freetext>' . $passport_info . '</freetext>
                                            </ssr>
                                        </serviceRequest>
                                        <referenceForDataElement>
                                            <reference>
                                                <qualifier>PT</qualifier>
                                            <number>' . $pt_count . '</number>
                                            </reference>
                                        </referenceForDataElement>
                                    </dataElementsIndiv>';
                        $pt_count = $pt_count + 2;
                    } else {
                        $pt_count = $pt_count + 1;
                    }
                }
            }
            if (isset($booking_passenger_arr['CHD'])) {
                if (valid_array($booking_passenger_arr['CHD'])) {
                    foreach ($booking_passenger_arr['CHD'] as $c_key => $c_value) {
                        if ($c_value['Gender'] == 1 || $c_value['Gender'] == 6) {
                            $title = 'M';
                        } else {
                            $title = 'F';
                        }
                        $dob = strtoupper(strtolower(date('dMy', strtotime($c_value['DateOfBirth']))));
                        $PassportIssuingC = strtoupper($c_value['PassporIssuingCountry']);
                        $passport_no = strtoupper(strtolower($c_value['PassportNumber']));
                        $expire = strtoupper(strtolower(date('dMy', strtotime($c_value['PassportExpiry']))));
                        $CountryCode = $c_value['CountryCode'];
                        $full_name = $c_value['LastName'] . '/' . $c_value['FirstName'];
                        // $passport_info = 'P/IN/IND906/IN/19JAN78/M/24APR25/PANDEY/RAHUL';
                        //value according to id type
                        if ($value['identification_type'] === 'citizenship') {
                            $passport_info = 'C/' . $CountryCode . '/' . $passport_no . '/' . $PassportIssuingC . '/' . $dob . '/' . $title . '/' . $expire . '/' . $full_name;
                        } else {
                            $passport_info = 'P/' . $CountryCode . '/' . $passport_no . '/' . $PassportIssuingC . '/' . $dob . '/' . $title . '/' . $expire . '/' . $full_name;
                        }
                        // $passport_info = 'P/' . $CountryCode . '/' . $passport_no . '/' . $PassportIssuingC . '/' . $dob . '/' . $title . '/' . $expire . '/' . $full_name;

                        $passenger_xml .= '<dataElementsIndiv>
                                            <elementManagementData>
                                                 <segmentName>SSR</segmentName>
                                            </elementManagementData>
                                        <serviceRequest>
                                            <ssr>
                                                <type>DOCS</type>
                                                <status>HK</status>
                                                <quantity>1</quantity>
                                                <companyId>YY</companyId>
                                                <freetext>' . $passport_info . '</freetext>
                                            </ssr>
                                        </serviceRequest>
                                        <referenceForDataElement>
                                            <reference>
                                                <qualifier>PT</qualifier>
                                            <number>' . $pt_count . '</number>
                                            </reference>
                                        </referenceForDataElement>
                                    </dataElementsIndiv>';
                        $pt_count++;
                    }
                }
            }
            $pax_xml_query = $passenger_xml;
        } else {
            $pax_xml_query = '';
        }
        $soapAction = 'PNRADD_21_1_1A';
        $xml_query = '<?xml version="1.0" encoding="utf-8"?>
                <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
                <soapenv:Header>
                    <awsse:Session TransactionStatusCode="InSeries" xmlns:awsse="http://xml.amadeus.com/2010/06/Session_v3">
                        <awsse:SessionId>' . $SessionId . '</awsse:SessionId>
                        <awsse:SequenceNumber>' . $SequenceNumber . '</awsse:SequenceNumber>
                        <awsse:SecurityToken>' . $SecurityToken . '</awsse:SecurityToken>
                    </awsse:Session>
                    <add:MessageID xmlns:add="http://www.w3.org/2005/08/addressing">' . $this->getuuid() . '</add:MessageID>
                    <add:Action xmlns:add="http://www.w3.org/2005/08/addressing">http://webservices.amadeus.com/' . $soapAction . '</add:Action>
                    <add:To xmlns:add="http://www.w3.org/2005/08/addressing">' . $this->api_url . '</add:To>
                </soapenv:Header>
                <soapenv:Body>
                    <PNR_AddMultiElements xmlns="http://xml.amadeus.com/' . $soapAction . '" >
                                 <pnrActions>
                                    <optionCode>10</optionCode> 
                                </pnrActions>
                                <dataElementsMaster>
                                    <marker1/>
                                    ' . $pax_xml_query . '  
                                </dataElementsMaster>                   
                    </PNR_AddMultiElements>
            </soapenv:Body>
        </soapenv:Envelope>';
        $soap_url = $this->soap_url . $soapAction;
        $this->CI->custom_db->generate_static_response($xml_query, 'Amadeus PNR_AddMultiElements Request');
        $pnr_option10_response = $this->process_request($xml_query, $this->api_url, 'PNRAddElementOption10', $soap_url);
        $this->CI->custom_db->generate_static_response($pnr_option10_response, 'Amadeus PNR_AddMultiElements Response');
        //$pnr_option10_response  = file_get_contents(FCPATH.'pnr10.xml');
        if ($pnr_option10_response) {
            $pnr_option10_response = $this->xml2array($pnr_option10_response);
            if (isset($pnr_option10_response['soapenv:Envelope']['soapenv:Header'])) {
                $SessionId = $pnr_option10_response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SessionId'];
                $SecurityToken = $pnr_option10_response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SecurityToken'];
                $SequenceNumber =  $pnr_option10_response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SequenceNumber'];
                $SequenceNumber = ($SequenceNumber + 1);
            }

            if ((isset($pnr_option10_response['soapenv:Envelope']['soapenv:Body']['PNR_Reply'])) && (isset($pnr_option10_response['soapenv:Envelope']['soapenv:Body']['PNR_Reply']['pnrHeader']['reservationInfo']['reservation']['controlNumber']))) {

                $controlNumber = $pnr_option10_response['soapenv:Envelope']['soapenv:Body']['PNR_Reply']['pnrHeader']['reservationInfo']['reservation']['controlNumber'];
                $response['status'] = SUCCESS_STATUS;
                $response['message'] = 'success';
                $response['data']['SessionId'] = $SessionId;
                $response['data']['SecurityToken'] = $SecurityToken;
                $response['data']['SequenceNumber'] = $SequenceNumber;
                $response['data']['Pnr_No'] = $controlNumber;
            } else {

                $this->Security_SignOut($SessionId, $SequenceNumber, $SecurityToken);
            }
        }
        return $response;
    }
    //placing in queue
    private function Place_Queue($booking_data)
    {
        $response['status'] = FAILURE_STATUS;
        $response['data'] = array();
        $response['message'] = '';
        $SessionId = $booking_data['SessionId'];
        $SequenceNumber = $booking_data['SequenceNumber'];
        $SecurityToken = $booking_data['SecurityToken'];
        $pnr_no = $booking_data['Pnr_No'];
        if ($pnr_no) {
            //$soapAction ='QUQPCQ_03_1_1A';
            $soapAction = 'QUQPCQ_03_1_1A';
            $xml_query = '<?xml version="1.0" encoding="UTF-8"?>           
                        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:sec="http://xml.amadeus
                        .com/2010/06/Security_v1" xmlns:typ="http://xml.amadeus.com/2010/06/Type">
                        <soapenv:Header>
                        <awsse:Session TransactionStatusCode="InSeries" xmlns:awsse="http://xml.amadeus.com/2010/06/Session_v3">
                        <awsse:SessionId>' . $SessionId . '</awsse:SessionId>
                        <awsse:SequenceNumber>' . $SequenceNumber . '</awsse:SequenceNumber>
                        <awsse:SecurityToken>' . $SecurityToken . '</awsse:SecurityToken>
                        </awsse:Session>
                        <add:MessageID xmlns:add="http://www.w3.org/2005/08/addressing">' . $this->getuuid() . '</add:MessageID>
                        <add:Action xmlns:add="http://www.w3.org/2005/08/addressing">http://webservices.amadeus.com/' . $soapAction . '</add:Action>
                        <add:To xmlns:add="http://www.w3.org/2005/08/addressing">' . $this->api_url . '</add:To>
                        </soapenv:Header>
                        <soapenv:Body>
                            <Queue_PlacePNR>
                                <placementOption>
                                    <selectionDetails>
                                        <option>QEQ</option>
                                    </selectionDetails>
                                </placementOption>
                                <targetDetails>
                                <targetOffice>
                                    <sourceType>
                                        <sourceQualifier1>3</sourceQualifier1>
                                    </sourceType>
                                    <originatorDetails>
                                        <inHouseIdentification1>' . $this->pseudo_city_code . '</inHouseIdentification1>
                                    </originatorDetails>
                                </targetOffice>
                                <queueNumber>
                                    <queueDetails>
                                        <number>1</number>
                                    </queueDetails>
                                </queueNumber>
                                <categoryDetails>
                                    <subQueueInfoDetails>
                                        <identificationType>C</identificationType>
                                        <itemNumber>0</itemNumber>
                                    </subQueueInfoDetails>
                                </categoryDetails>
                                </targetDetails>
                                <recordLocator>
                                    <reservation>
                                        <controlNumber>' . $pnr_no . '</controlNumber>
                                    </reservation>
                                </recordLocator>
                            </Queue_PlacePNR>
                        </soapenv:Body>
                    </soapenv:Envelope>';
            $soap_url  = $this->soap_url . $soapAction;
            $this->CI->custom_db->generate_static_response($xml_query, 'Amadeus Queue_PlacePNR Request');
            $place_queue_response = $this->process_request($xml_query, $this->api_url, 'Queue_PlacePNR(amadeus)', $soap_url);

            $this->CI->custom_db->generate_static_response($place_queue_response, 'Amadeus Queue_PlacePNR Resonse');
            if ($place_queue_response) {
                $place_queue_response = $this->xml2array($place_queue_response);

                if (isset($place_queue_response['soapenv:Envelope']['soapenv:Header'])) {
                    $SessionId = $place_queue_response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SessionId'];
                    $SecurityToken = $place_queue_response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SecurityToken'];
                    $SequenceNumber =  $place_queue_response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SequenceNumber'];
                    $SequenceNumber = ($SequenceNumber + 1);
                }
                if (isset($place_queue_response['soapenv:Envelope']['soapenv:Body']['Queue_PlacePNRReply']['recordLocator'])) {
                    if (isset($place_queue_response['soapenv:Envelope']['soapenv:Body']['Queue_PlacePNRReply']['recordLocator']['reservation']['controlNumber'])) {

                        $controlNumber = $place_queue_response['soapenv:Envelope']['soapenv:Body']['Queue_PlacePNRReply']['recordLocator']['reservation']['controlNumber'];

                        $response['status'] = SUCCESS_STATUS;
                        $response['message'] = 'success';
                        $response['data']['SessionId'] = $SessionId;
                        $response['data']['SecurityToken'] = $SecurityToken;
                        $response['data']['SequenceNumber'] = $SequenceNumber;
                        $response['data']['Pnr_No'] = $controlNumber;
                    }
                } else {
                    // $this->Security_SignOut($SessionId,$SequenceNumber,$SecurityToken);
                }
            }
        }
        return $response;
    }
    /*Retrieve booked  PNR details*/
    private function PNRRetrieve($header_data)
    {
        $response['status'] = FAILURE_STATUS;
        $response['data'] = array();
        $response['message'] = '';
        $SessionId = @$header_data['SessionId'];
        $SequenceNumber = @$header_data['SequenceNumber'];
        $SecurityToken = @$header_data['SecurityToken'];
        $pnr_no = $header_data['Pnr_No'];
        $soapAction = 'PNRRET_21_1_1A';
        $nonce = $this->getNoncevalue();
        $created = $this->getCreateDate();
        $digest_passwrod = $this->DigestAlgo($this->password, $created, $nonce);

        //$am_url = 'https://noded2.test.webservices.amadeus.com/1ASIWCTIZ77';
        //$this->api_url
        if (empty($header_data['SessionId']) == false) {
            $header = '<soapenv:Header>
                        <awsse:Session TransactionStatusCode="InSeries" xmlns:awsse="http://xml.amadeus.com/2010/06/Session_v3">
                            <awsse:SessionId>' . $SessionId . '</awsse:SessionId>
                            <awsse:SequenceNumber>' . $SequenceNumber . '</awsse:SequenceNumber>
                            <awsse:SecurityToken>' . $SecurityToken . '</awsse:SecurityToken>
                        </awsse:Session>
                        <add:MessageID xmlns:add="http://www.w3.org/2005/08/addressing">' . $this->getuuid() . '</add:MessageID>
                        <add:Action xmlns:add="http://www.w3.org/2005/08/addressing">http://webservices.amadeus.com/' . $soapAction . '</add:Action>
                        <add:To xmlns:add="http://www.w3.org/2005/08/addressing">' . $this->api_url . '</add:To>
                    </soapenv:Header>';
        } else {
            $header = '<soapenv:Header>
                    <add:MessageID xmlns:add="http://www.w3.org/2005/08/addressing">' . $this->getuuid() . '</add:MessageID>
                    <add:Action xmlns:add="http://www.w3.org/2005/08/addressing">http://webservices.amadeus.com/' . $soapAction . '</add:Action>
                    <add:To xmlns:add="http://www.w3.org/2005/08/addressing">' . $this->api_url . '</add:To>
                    <link:TransactionFlowLink xmlns:link="http://wsdl.amadeus.com/2010/06/ws/Link_v1"/>
                    <oas:Security xmlns:oas="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
                    <oas:UsernameToken oas1:Id="UsernameToken-1" xmlns:oas1="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
                    <oas:Username>' . $this->username . '</oas:Username>
                    <oas:Nonce EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary">' . $nonce . '</oas:Nonce>
                    <oas:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest">' . $digest_passwrod . '</oas:Password>
                    <oas1:Created>' . $created . '</oas1:Created>
                    </oas:UsernameToken>
                    </oas:Security>
                    <AMA_SecurityHostedUser xmlns="http://xml.amadeus.com/2010/06/Security_v1">
                    <UserID AgentDutyCode="' . $this->agent_duty_code . '" RequestorType="' . $this->requestor_type . '" PseudoCityCode="' . $this->pseudo_city_code . '" POS_Type="' . $this->pos_type . '"/>
                    </AMA_SecurityHostedUser>
                    <awsse:Session TransactionStatusCode="Start" xmlns:awsse="http://xml.amadeus.com/2010/06/Session_v3"/>
                    </soapenv:Header>';
        }
        $xml_query = '<?xml version="1.0" encoding="UTF-8"?>           
                    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:sec="http://xml.amadeus.com/2010/06/Security_v1" xmlns:typ="http://xml.amadeus.com/2010/06/Type" >
                        ' . $header . '
                    <soapenv:Body>
                <PNR_Retrieve xmlns="http://xml.amadeus.com/' . $soapAction . '">
                    <retrievalFacts>
                        <retrieve>
                            <type>2</type>
                        </retrieve>
                        <reservationOrProfileIdentifier>
                            <reservation>
                                <controlNumber>' . $pnr_no . '</controlNumber>
                            </reservation>
                        </reservationOrProfileIdentifier>
                    </retrievalFacts>
                </PNR_Retrieve>
                </soapenv:Body>
                </soapenv:Envelope>';
        $soap_url = $this->soap_url . $soapAction;
        $this->CI->custom_db->generate_static_response($xml_query, 'Amadeus Flight PNR_Retrieve Request');
        $pnr_rerieve_response = $this->process_request($xml_query, $this->api_url, 'PNR_Retrieve(amadeus)', $soap_url);
        $this->CI->custom_db->generate_static_response($pnr_rerieve_response, 'Amadeus Flight PNR_Retrieve Response');
        if ($pnr_rerieve_response) {
            $pnr_rerieve_response = $this->xml2array($pnr_rerieve_response);

            if (isset($pnr_rerieve_response['soapenv:Envelope']['soapenv:Header'])) {
                $SessionId = $pnr_rerieve_response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SessionId'];
                $SecurityToken = $pnr_rerieve_response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SecurityToken'];
                $SequenceNumber =  $pnr_rerieve_response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SequenceNumber'];
                $SequenceNumber = ($SequenceNumber + 1);
                if (isset($pnr_rerieve_response['soapenv:Envelope']['soapenv:Body']['PNR_Reply']['pnrHeader']['reservationInfo']['reservation'])) {
                    $controlNumber = $pnr_rerieve_response['soapenv:Envelope']['soapenv:Body']['PNR_Reply']['pnrHeader']['reservationInfo']['reservation']['controlNumber'];
                    $response['status'] = SUCCESS_STATUS;
                    $response['message'] = 'success';
                    $response['data']['SessionId'] = $SessionId;
                    $response['data']['SecurityToken'] = $SecurityToken;
                    $response['data']['SequenceNumber'] = $SequenceNumber;
                    $response['data']['Pnr_No'] = $controlNumber;
                    $response['raw_response'] = $pnr_rerieve_response;
                }
            } else {
                //$this->Security_SignOut($SessionId,$SequenceNumber,$SecurityToken);
            }
        }
        return $response;
    }
    private function DocIssuance_IssueTicket($header_data)
    {
        $response['status'] = FAILURE_STATUS;
        $response['data'] = array();
        $response['message'] = '';
        $SessionId = $header_data['SessionId'];
        $SequenceNumber = $header_data['SequenceNumber'];
        $SecurityToken = $header_data['SecurityToken'];
        $pnr_no = $header_data['Pnr_No'];
        $soapAction  = 'TTKTIQ_15_1_1A';
        $soapHeaderFor_IssueTicket  = '<soapenv:Header>
            <awsse:Session TransactionStatusCode="InSeries" xmlns:awsse="http://xml.amadeus.com/2010/06/Session_v3">
            <awsse:SessionId>' . $SessionId . '</awsse:SessionId>
            <awsse:SequenceNumber>' . $SequenceNumber . '</awsse:SequenceNumber>
            <awsse:SecurityToken>' . $SecurityToken . '</awsse:SecurityToken>
            </awsse:Session>
            <add:MessageID xmlns:add="http://www.w3.org/2005/08/addressing">' . $this->getuuid() . '</add:MessageID>
            <add:Action xmlns:add="http://www.w3.org/2005/08/addressing">http://webservices.amadeus.com/' . $soapAction . '</add:Action>
            <add:To xmlns:add="http://www.w3.org/2005/08/addressing">' . $this->api_url . '</add:To>
            </soapenv:Header>';
        $DocIssuance_IssueTicket = '<?xml version="1.0" encoding="utf-8"?>
            <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
            ' . $soapHeaderFor_IssueTicket . '
            <soapenv:Body>
            <DocIssuance_IssueTicket>
            <optionGroup>
                <switches>
                    <statusDetails>
                        <indicator>ET</indicator>
                    </statusDetails>
                </switches>
            </optionGroup>
            </DocIssuance_IssueTicket>
            </soapenv:Body>
            </soapenv:Envelope>';
        $soap_url = $this->soap_url . $soapAction;
        $this->CI->custom_db->generate_static_response($DocIssuance_IssueTicket, 'DocIssuance_IssueTicket Request');
        $ticket_response = $this->process_request($DocIssuance_IssueTicket, $this->api_url, 'IssueTicket(amadeus)', $soap_url);
        $this->CI->custom_db->generate_static_response($ticket_response, 'Amadeus DocIssuance_IssueTicket Response');
        if ($ticket_response) {
            $ticket_response = $this->xml2array($ticket_response);
            if (isset($ticket_response['soapenv:Envelope']['soapenv:Header'])) {
                $SessionId = $ticket_response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SessionId'];
                $SecurityToken = $ticket_response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SecurityToken'];
                $SequenceNumber =  $ticket_response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SequenceNumber'];
                $SequenceNumber = ($SequenceNumber + 1);
                $response['status'] = SUCCESS_STATUS;
            }

            $data['SessionId'] = $SessionId;
            $data['SecurityToken'] = $SecurityToken;
            $data['SequenceNumber'] = $SequenceNumber;
            $data['Pnr_No'] = $pnr_no;
            $response['data'] = $data;
        }

        return $response;
    }

    /*Booking Procedure Ends*/


    /**
     * Formats the Fare Request For Passenger-wise
     *
     * @param unknown_type $passenger_token
     */
    private function assign_booking_passenger_fare_breakdown($passenger_token)
    {
        $passenger_token = force_multple_data_format($passenger_token);
        $Fare = array();
        foreach ($passenger_token as $k => $v) {
            $Fare[$v['PassengerType']]['BaseFare'] = ($v['BasePrice'] / $v['PassengerCount']);
            $Fare[$v['PassengerType']]['Tax'] = ($v['Tax'] / $v['PassengerCount']);
            //$Fare [$v ['PassengerType']] ['TransactionFee'] = ($v ['TransactionFee'] / $v ['PassengerCount']);
            $Fare[$v['PassengerType']]['TransactionFee'] = 0;
            // $Fare [$v ['PassengerType']] ['YQTax'] = ($v ['YQTax'] / $v ['PassengerCount']);
            // $Fare [$v ['PassengerType']] ['AdditionalTxnFeeOfrd'] = ($v ['AdditionalTxnFeeOfrd'] / $v ['PassengerCount']);
            // $Fare [$v ['PassengerType']] ['AdditionalTxnFeePub'] = ($v ['AdditionalTxnFeePub'] / $v ['PassengerCount']);
            //$Fare [$v ['PassengerType']] ['AirTransFee'] = ($v ['AirTransFee'] / $v ['PassengerCount']);
            $Fare[$v['PassengerType']]['AirTransFee'] = 0;
        }
        return $Fare;
    }
    /**
     * Validates the mobile number
     * @param unknown_type $mobile_number
     */
    private function validate_mobile_number($mobile_number)
    {
        $mobile_number = trim($mobile_number);
        $mobile_number = ltrim($mobile_number, '0');
        if (strlen($mobile_number) < 10) {
            $mobile_number_length = strlen($mobile_number);
            $required_extra_number_lengths = (10) - $mobile_number_length;
            $extra_numbers = str_repeat('0', $required_extra_number_lengths);
            $mobile_number = $mobile_number . '' . $extra_numbers;
        }
        return $mobile_number;
    }

    /**
     * Extra Services
     * @param unknown_type $request
     */
    public function get_extra_services($request, $search_id)
    {

        $CI = &get_instance();
        $response['status'] = FAILURE_STATUS; // Status Of Operation
        $response['message'] = ''; // Message to be returned
        $response['data'] = array(); // Data to be returned
        $seat_response = $this->seat_request($request, $search_id);
        // debug($seat_response);exit;
        $response['data']['ExtraServiceDetails']['Seat'] = $seat_response;
        $response['status'] = SUCCESS_STATUS;
        //$response ['data']['ExtraServiceDetails']['MealPreference'] = $this->getMeals($request);
        $response['data']['ExtraServiceDetails']['MealPreference']  = '';
        #debug($response);exit;
        return $response;
        // debug($response);exit;
    }
    public function seat_request($request_data, $search_id)
    {
        // debug($request);exit;
        $search_data = $this->search_data($search_id);
        $segment_data = $request_data['FlightInfo']['FlightDetails']['Details'];
        #debug($segment_data);exit;
        // debug($request_data);exit;
        $seatmapresponse = array();
        $segment_type = 1;
        $seg_count = 0;
        $SessionId = $SecurityToken = $SequenceNumber = '';
        for ($j = 0; $j < count($segment_data); $j++) {
            for ($ss = 0; $ss < count($segment_data[$j]); $ss++) {
                //if($ss>0){
                $this->created = $this->getCreateDate();
                $this->nonce = $this->getNoncevalue();
                $this->hashPwd = $this->DigestAlgo($this->password, $this->created, $this->nonce);

                $depature_date = date('dmy', strtotime($segment_data[$j][$ss]['Origin']['DateTime']));
                $arrival_date = date('dmy', strtotime($segment_data[$j][$ss]['Destination']['DateTime']));
                $cabin_class  = $segment_data[$j][$ss]['CabinClass'];
                $OperatorCode = $segment_data[$j][$ss]['OperatorCode'];
                $Origin = $segment_data[$j][$ss]['Origin']['AirportCode'];
                $Destination = $segment_data[$j][$ss]['Destination']['AirportCode'];
                $FlightNumber  = $segment_data[$j][$ss]['FlightNumber'];
                $soapAction = "SMPREQ_97_1_IA";
                $Header = '<soapenv:Header>
                        <add:MessageID xmlns:add="http://www.w3.org/2005/08/addressing">' . $this->getuuid() . '</add:MessageID>
                        <add:Action xmlns:add="http://www.w3.org/2005/08/addressing">http://webservices.amadeus.com/' . $soapAction . '</add:Action>
                        <add:To xmlns:add="http://www.w3.org/2005/08/addressing">' . $this->api_url . '</add:To>
                        <link:TransactionFlowLink xmlns:link="http://wsdl.amadeus.com/2010/06/ws/Link_v1"/>
                        <oas:Security xmlns:oas="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
                        <oas:UsernameToken oas1:Id="UsernameToken-1" xmlns:oas1="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
                        <oas:Username>' . $this->username . '</oas:Username>
                        <oas:Nonce EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary">' . $this->nonce . '</oas:Nonce>
                        <oas:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest">' . $this->hashPwd . '</oas:Password>
                        <oas1:Created>' . $this->created . '</oas1:Created>
                    </oas:UsernameToken>
                    </oas:Security>
                    <AMA_SecurityHostedUser xmlns="http://xml.amadeus.com/2010/06/Security_v1">
                    <UserID AgentDutyCode="' . $this->agent_duty_code . '" RequestorType="' . $this->requestor_type . '" PseudoCityCode="' . $this->pseudo_city_code . '" POS_Type="' . $this->pos_type . '"/>
                    </AMA_SecurityHostedUser>
                </soapenv:Header>';

                $seat_request = ' <?xml version="1.0" encoding="UTF-8"?>           
                    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:sec="http://xml.amadeus.com/2010/06/Security_v1" xmlns:typ="http://xml.amadeus.com/2010/06/Type">
                    ' . $Header . '
                <soapenv:Body>
                <Air_RetrieveSeatMap xmlns="http://xml.amadeus.com/' . $soapAction . '">            
                    <travelProductIdent>
                                    <flightDate>
                                        <departureDate>' . $depature_date . '</departureDate>
                                    </flightDate>
                                    <boardPointDetails>
                                        <trueLocationId>' . $Origin . '</trueLocationId>
                                    </boardPointDetails>
                                    <offpointDetails>
                                        <trueLocationId>' . $Destination . '</trueLocationId>
                                    </offpointDetails>
                                    <companyDetails>
                                        <marketingCompany>' . $OperatorCode . '</marketingCompany>
                                    </companyDetails>
                                    <flightIdentification>
                                        <flightNumber>' . $FlightNumber . '</flightNumber>
                                        <bookingClass>' . $cabin_class . '</bookingClass>
                                    </flightIdentification>
                                </travelProductIdent>
                            <seatRequestParameters>
                                <processingIndicator>FT</processingIndicator>
                            </seatRequestParameters>
                        </Air_RetrieveSeatMap>
                </soapenv:Body>
                </soapenv:Envelope>';


                // echo $request_params;exit;

                $soap_url = $this->soap_url . $soapAction;
                $seat_response = $this->process_request($seat_request, $this->api_url, 'SeatMapReq(amadeus)', $soap_url);
                if ($seat_response) {
                    $seat_response = $this->xml2array($seat_response);

                    if (isset($seat_response['soapenv:Envelope']['soapenv:Header'])) {
                        $SessionId = $seat_response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SessionId'];
                        $SecurityToken = $seat_response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SecurityToken'];
                        $SequenceNumber =  $seat_response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SequenceNumber'];
                        $SequenceNumber = ($SequenceNumber + 1);
                        // $this->Security_SignOut($SessionId,$SequenceNumber,$SecurityToken);
                    }
                    #echo 'SequenceNumber..'.$SequenceNumber.'<br/>';

                    if (isset($seat_response['soapenv:Envelope']['soapenv:Body']['Air_RetrieveSeatMapReply']['seatmapInformation'])) {

                        $seat_format_response  = $this->format_seat_mapping_response($seat_response, $segment_data[$j][$ss], $segment_type);

                        if (valid_array($seat_format_response['seat_data'])) {
                            $seatmapresponse[$seg_count]['SeatDetails'] = $seat_format_response['seat_data'];
                            $seatmapresponse[$seg_count]['SeatColumn'] = $seat_format_response['available_columns'];
                            $seg_count++;
                        }
                    } else {
                        // $seatmapresponse[$seg_count]['SeatDetails'] =array();
                        //$seatmapresponse[$seg_count]['SeatColumn'] = array();
                    }
                }

                //}
                $segment_type++;
            }
        }

        return $seatmapresponse;
    }
    /*Amadeus Seat Format*/
    private function format_seat_mapping_response($seat_response, $segment_data, $segment_type)
    {

        $seatmapInformation = $seat_response['soapenv:Envelope']['soapenv:Body']['Air_RetrieveSeatMapReply']['seatmapInformation'];

        $final_seat_array = array('available_columns' => '', 'seat_data' => array());

        if (!isset($seat_response['soapenv:Envelope']['soapenv:Body']['Air_RetrieveSeatMapReply']['errorInformation'])) {


            if (isset($seatmapInformation['cabin'])) {

                $CabinClass_arr = force_multple_data_format($seatmapInformation['cabin']);
                $rowDetails = force_multple_data_format($seat_response['soapenv:Envelope']['soapenv:Body']['Air_RetrieveSeatMapReply']['seatmapInformation']['row']);
                // debug($seatmapInformation);  
                // echo "=====";          
                //$alpha_char = array('A'=>'A','B'=>'B','C'=>'C','D'=>'D','E'=>'E','F'=>'F','G'=>'G','H'=>'H','I'=>'I','J'=>'J','K'=>'K','L'=>'L','M'=>'M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');            
                $airRowDetails = array();
                $compartmentDetails_Row = array();
                if ($CabinClass_arr) {
                    $available_columns = array();
                    foreach ($CabinClass_arr as $c_key => $c_value) {
                        if (isset($c_value['compartmentDetails']['seatRowRange']['number'])) {
                            $start_row = $c_value['compartmentDetails']['seatRowRange']['number'][0];
                            $end_row = $c_value['compartmentDetails']['seatRowRange']['number'][1];
                            if (isset($c_value['compartmentDetails']['defaultSeatOccupation'])) {
                                $row_key = $start_row . '_' . $end_row;
                                $compartmentDetails_Row[$row_key]['defaultSeatOccupation'] = $c_value['compartmentDetails']['defaultSeatOccupation'];
                            }
                        }
                        if (isset($c_value['compartmentDetails']['columnDetails'])) {
                            foreach ($c_value['compartmentDetails']['columnDetails'] as $clkey => $clvalue) {
                                if (!in_array($clvalue['seatColumn'], $available_columns)) {
                                    $available_columns[] = $clvalue['seatColumn'];
                                }
                            }
                            $compartmentDetails_Row[$row_key]['columnDetails'] = $c_value['compartmentDetails']['columnDetails'];
                        }
                    }
                }
                asort($available_columns);
                if ($rowDetails) {
                    foreach ($rowDetails as $r_key => $r_value) {
                        $Row_Number = $r_value['rowDetails']['seatRowNumber'];

                        if (isset($r_value['rowDetails'])) {
                            $airSeatMapDetails  = array();
                            if (isset($r_value['rowDetails']['seatOccupationDetails'])) {
                                $airSeatMapDetails = $this->seat_format_row($r_key, $r_value, $segment_data, $compartmentDetails_Row, $available_columns, $segment_type);
                            } else {
                                $airSeatMapDetails = array();
                                //need to take from compartment details
                                $Default_compartment_details = $this->checking_compartment_data($r_value, $compartmentDetails_Row);

                                $r_value['rowDetails']['seatOccupationDetails'] = $Default_compartment_details['seatOccupationDetails'];
                                $airSeatMapDetails = $this->seat_format_row($r_key, $r_value, $segment_data, $compartmentDetails_Row, $available_columns, $segment_type);
                            }
                            $airSeatMapDetails_arr = array();
                            //to insert missing columns
                            # debug($available_columns);exit;
                            foreach ($available_columns as $av_key => $av_value) {
                                if (isset($airSeatMapDetails[$av_value])) {
                                    $airSeatMapDetails_arr[] = $airSeatMapDetails[$av_value];
                                } else {
                                    $missin_cl_update['AvailablityType'] = 0;
                                    $missin_cl_update['Destination'] =  $segment_data['Destination']['AirportCode'];
                                    $missin_cl_update['FlightNumber'] = $segment_data['FlightNumber'];
                                    $missin_cl_update['Origin'] = $segment_data['Origin']['AirportCode'];
                                    $missin_cl_update['Price'] = 0;
                                    $missin_cl_update['RowNumber'] = $Row_Number;
                                    $missin_cl_update['SeatColumn'] = $av_value;
                                    $missin_cl_update['SeatNumber'] = $Row_Number . $av_value;
                                    $key['key'][0]['Code'] = $missin_cl_update['SeatNumber'];
                                    $key['key'][0]['Type'] = 'dynamic';
                                    $key['key'][0]['Description'] = 'no row';
                                    $key['key'][0]['Segement_Type'] = $segment_type;

                                    $ResultToken = serialized_data($key['key']);
                                    $missin_cl_update['SeatId'] = $ResultToken;
                                    $airSeatMapDetails_arr[] = $missin_cl_update;
                                }
                            }


                            $airRowDetails[$r_key] = $airSeatMapDetails_arr;
                        }
                    }
                }

                $final_seat_array = array('available_columns' => $available_columns, 'seat_data' => $airRowDetails);
            }
        } else {
            $final_seat_array = array('available_columns' => '', 'seat_data' => array());
        }

        #debug($final_seat_array);exit;
        return $final_seat_array;
    }

    /*Taking the compartment data*/
    private function checking_compartment_data($r_value, $compartmentDetails_Row)
    {


        $Default_compartment_details = array();
        $Row_Number = $r_value['rowDetails']['seatRowNumber'];
        $seat_char = '';
        if (isset($r_value['rowDetails']['rowCharacteristicDetails'])) {
            if (isset($r_value['rowDetails']['rowCharacteristicDetails']['rowCharacteristic'])) {
                $seat_char = $r_value['rowDetails']['rowCharacteristicDetails']['rowCharacteristic'];
            }
        }
        foreach ($compartmentDetails_Row as $ch_key => $ch_value) {
            $explode_arr  = explode("_", $ch_key);
            #debug($ch_value);

            for ($i = $explode_arr[0]; $i <= $explode_arr[1]; $i++) {

                if ($i == $Row_Number) {

                    foreach ($ch_value['columnDetails'] as $cc_key => $cc_value) {


                        $ch_value['columnDetails'][$cc_key]['seatOccupation'] = $ch_value['defaultSeatOccupation'];
                        if ($seat_char) {
                            $ch_value['columnDetails'][$cc_key]['seatCharacteristic'] = $seat_char;
                        } else {
                            $ch_value['columnDetails'][$cc_key]['seatCharacteristic'] = @$cc_value['description'];
                        }
                    }
                    $Default_compartment_details['defaultSeatOccupation'] = $ch_value['defaultSeatOccupation'];
                    $Default_compartment_details['seatOccupationDetails'] = $ch_value['columnDetails'];
                }
            }
        }
        return $Default_compartment_details;
    }
    /*formating seat rows*/
    private function seat_format_row($r_key, $r_value, $segment_data, $compartmentDetails_Row, $available_columns, $segment_no)
    {
        $airSeatMapDetails  = array();
        $r_value['rowDetails']['seatOccupationDetails'] = force_multple_data_format($r_value['rowDetails']['seatOccupationDetails']);
        foreach ($r_value['rowDetails']['seatOccupationDetails'] as $rcl_key => $rcl_value) {
            $Row_Number = $r_value['rowDetails']['seatRowNumber'];
            $airSeatMapDetails[$rcl_value['seatColumn']]['AirlineCode'] = $segment_data['OperatorCode'];
            //Availability type 
            //0-blocked,1 - available 2- space
            $available_seat = 0;
            $is_available = 0;
            $check_character = array();
            # debug($rcl_value);
            #echo 'Row....'.$Row_Number.'<br/>';        
            if (!isset($rcl_value['seatOccupation']) || !isset($rcl_value['seatCharacteristic'])) {
                $Default_compartment_details = array();
                $Default_compartment_details = $this->checking_compartment_data($r_value, $compartmentDetails_Row);
                if (!isset($rcl_value['seatOccupation'])) {
                    $rcl_value['seatOccupation'] = $Default_compartment_details['defaultSeatOccupation'];
                }
            }
            $is_char = '';
            if (isset($rcl_value['seatOccupation'])) {
                $is_available = $this->seat_occupation($rcl_value['seatOccupation']);

                if ($is_available) {

                    if (isset($rcl_value['seatCharacteristic'])) {
                        if (is_array($rcl_value['seatCharacteristic'])) {

                            foreach ($rcl_value['seatCharacteristic'] as $sc_key => $sc_value) {
                                $is_char = $this->seat_characteristics($sc_value);

                                if (
                                    $is_char == 'seat_availble' || $is_char == 'infant_adult_seat' || $is_char == 'center_seat'
                                ) {
                                    //$available_seat = 1;
                                    $check_character[] = 1;
                                } else {
                                    $check_character[] = 0;
                                }
                            }
                        } else {
                            $is_char = $this->seat_characteristics($rcl_value['seatCharacteristic']);


                            if ($is_char == 'seat_availble' || $is_char == 'infant_adult_seat' || $is_char == 'center_seat') {
                                $check_character[] = 1;
                            } else {
                                $check_character[] = 0;
                            }
                        }
                    } else {

                        foreach ($Default_compartment_details['seatOccupationDetails'] as $char_key => $char_value) {

                            if ($char_value['seatColumn'] == $rcl_value['seatColumn']) {
                                if (isset($char_value['description'])) {
                                    $is_char = $this->seat_characteristics($char_value['description']);
                                } else {
                                    $is_char = 'blocked';
                                }

                                if ($is_char == 'seat_availble' || $is_char == 'infant_adult_seat' || $is_char == 'center_seat') {
                                    $check_character[] = 1;
                                } else {
                                    $check_character[] = 0;
                                }
                            }
                        }
                    }
                } else {
                    $check_character[] = 0;
                }
            }

            $check_character = array_unique($check_character);
            if (in_array(0, $check_character)) {
                $available_seat = 0;
            } else {
                $available_seat = 1;
            }

            $airSeatMapDetails[$rcl_value['seatColumn']]['AvailablityType'] = $available_seat;
            $airSeatMapDetails[$rcl_value['seatColumn']]['Destination'] =  $segment_data['Destination']['AirportCode'];
            $airSeatMapDetails[$rcl_value['seatColumn']]['FlightNumber'] = $segment_data['FlightNumber'];
            $airSeatMapDetails[$rcl_value['seatColumn']]['Origin'] = $segment_data['Origin']['AirportCode'];
            $airSeatMapDetails[$rcl_value['seatColumn']]['Price'] = 0;
            $airSeatMapDetails[$rcl_value['seatColumn']]['RowNumber'] = $Row_Number;
            $airSeatMapDetails[$rcl_value['seatColumn']]['SeatColumn'] = $rcl_value['seatColumn'];
            $airSeatMapDetails[$rcl_value['seatColumn']]['SeatNumber'] = $Row_Number . $rcl_value['seatColumn'];
            $key['key'][0]['Code'] = $airSeatMapDetails[$rcl_value['seatColumn']]['SeatNumber'];
            $key['key'][0]['Type'] = 'dynamic';
            $key['key'][0]['Description'] = $is_char;
            $key['key'][0]['Segment_Type'] = $segment_no;
            $ResultToken = serialized_data($key['key']);
            $airSeatMapDetails[$rcl_value['seatColumn']]['SeatId'] = $ResultToken;
        }
        return $airSeatMapDetails;
    }
    /*Amadeus Seat Occupation Details*/
    private function seat_occupation($code)
    {
        $avaiable_status = false;
        switch ($code) {
            case 'F':
                $avaiable_status = 1;
                break;
            case 'G':
                $avaiable_status = 0;
                break;
            case 'O':
                $avaiable_status = 0;
                break;
            case 'Z':
                $avaiable_status = 0;
                break;

            default:
                $avaiable_status = 0;
                break;
        }
        return $avaiable_status;
    }
    /*Seat Characteristics*/
    private function seat_characteristics($value)
    {
        $seat_characteristics = "";
        switch ($value) {
            case '1':
                $seat_characteristics = "restricted_seat";
                break;
            case '2':
                $seat_characteristics = "leg_rest_avaiable";
                break;
            case '3':
                $seat_characteristics = "video_screen";
                break;
            case '8':
                $seat_characteristics = "blocked"; //no seat at this location
                break;
            case '9':
                $seat_characteristics = "center_seat"; //available
                break;
            case '1A':
                $seat_characteristics = "seat_availble"; //seat_available for adult
                break;
            case '1B':
                $seat_characteristics = "blocked"; //Seat not available for medical.
                break;
            case '1C':
                $seat_characteristics = "blocked"; // Seat not available for unaccompanied minor.
                break;
            case '1D':
                $seat_characteristics = "blocked"; //    Restricted reclined seat.
                break;
            case '1M':
                $seat_characteristics = "seat_availble"; //Seat with movie view
                break;
            case '1W':
                $seat_characteristics = "seat_availble"; //Window seat without window.
                break;
            case '3A':
                $seat_characteristics = "seat_availble"; // Individual video screen - No choice of movie.
                break;
            case '6A':
                $seat_characteristics = "seat_availble"; //In front of galley seat.
                break;
            case '6B':
                $seat_characteristics = "seat_availble"; //Behind galley seat.
                break;
            case '7A':
                $seat_characteristics = "blocked"; //In front of toilet seat.
                break;
            case '7B':
                $seat_characteristics = "blocked"; //Behind toilet seat.
                break;
            case 'A':
                $seat_characteristics = "seat_availble"; //Asile
                break;
            case 'AB':
                $seat_characteristics = "seat_availble"; //  Seat adjacent to bar.
                break;
            case 'AC':
                $seat_characteristics = "seat_availble"; //Seat adjacent to closet.
                break;
            case 'AG':
                $seat_characteristics = "seat_availble"; //Seat adjacent to galley.
                break;
            case 'AJ':
                $seat_characteristics = "seat_availble"; //Adjacent aisle seat.
                break;
            case 'AL':
                $seat_characteristics = "seat_availble"; //Seat adjacent to lavatory.
                break;
            case 'AM':
                $seat_characteristics = "seat_availble"; //Individual movie screen - No choice of movie selection.
                break;
            case 'AS':
                $seat_characteristics = "seat_availble"; //Individual airphone.
                break;
            case 'AT':
                $seat_characteristics = "seat_availble"; //Seat adjacent to table.
                break;
            case 'AU':
                $seat_characteristics = "seat_availble"; //Seat adjacent to stairs to upper deck.seat.
                break;
            case 'B':
                $seat_characteristics = "seat_availble"; //Seat with bassinet facility.seat.
                break;
            case 'C':
                $seat_characteristics = "seat_availble"; //Crew seat. facility.seat.
                break;
            case 'CH':
                $seat_characteristics = "blocked"; //Chargeable seat.facility.seat.
                break;
            case 'DE':
                $seat_characteristics = "blocked"; //Seat suitable for deportee.
                break;
            case 'EC':
                $seat_characteristics = "blocked"; //    Electronic connection for laptop or Fax machine.
                break;
            case 'EK':
                $seat_characteristics = "seat_availble"; //Economy comfort seat.
                break;
            case 'H':
                $seat_characteristics = "blocked"; //Seat with facility for handicapped/incapacitated passenger.
                break;
            case 'I':
                $seat_characteristics = "seat_availble"; //Seat suitable for adult with infant.
                break;
            case 'IE':
                $seat_characteristics = "blocked"; // Seat not suitable for child.
                break;
            case 'J':
                $seat_characteristics = "seat_availble"; //Rear facing seat.
                break;
            case 'K':
                $seat_characteristics = "blocked"; //Bulkhead seat.
                break;
            case 'KA':
                $seat_characteristics = "blocked"; //Bulkhead seat with movie screen.
                break;
            case 'L':
                $seat_characteristics = "seat_availble"; //  Leg space seat.
                break;
            case 'LS':
                $seat_characteristics = "seat_availble"; // Left side of aircraft.
                break;
            case 'M':
                $seat_characteristics = "seat_availble"; //  Seat without a movie view.
                break;
            case 'N':
                $seat_characteristics = "blocked"; //  NonSmokeing seat
                break;
            case 'O':
                $seat_characteristics = "blocked"; // Preferential seat.
                break;
            case 'OW':
                $seat_characteristics = "blocked"; // Overwing seat.
                break;
            case 'PC':
                $seat_characteristics = "blocked"; //Pet cabin.
                break;
            case 'Q':
                $seat_characteristics = "blocked"; //Seat in a quiet zone.
                break;
            case 'RS':
                $seat_characteristics = "seat_availble"; //Right side of aircraft.
                break;
            case 'S':
                $seat_characteristics = "blocked"; // Smoking seat.
                break;
            case 'U':
                $seat_characteristics = "blocked"; //Seat suitable for unaccompanied minor.
                break;
            case 'UP':
                $seat_characteristics = "seat_availble"; // Upper deck seat.
                break;
            case 'V':
                $seat_characteristics = "seat_availble"; // Seat to be left vacant or last offered.
                break;
            case 'W':
                $seat_characteristics = "seat_availble"; // Window seat.
                break;
            case 'WA':
                $seat_characteristics = "seat_availble"; // Window and Aisle together.
                break;
            case 'X':
                $seat_characteristics = "blocked"; // No facility seat (indifferent seat).
                break;
            default:
                $seat_characteristics = "blocked";
                break;
        }
        return $seat_characteristics;
    }
    /**
     * Process Cancel Booking
     * Online Cancellation
     */
    public function cancel_booking($request)
    {
        $response['status'] = FAILURE_STATUS; // Status Of Operation
        $response['message'] = ''; // Message to be returned
        $response['data'] = array(); // Data to be returned
        $app_reference = $request['AppReference'];
        $sequence_number = $request['SequenceNumber'];
        $IsFullBookingCancel = $request['IsFullBookingCancel'];
        $ticket_ids = $request['TicketId'];
        $pnr_no = $request['PNR'];
        //sdebug($request);exit;
        $elgible_for_ticket_cancellation = $this->CI->common_flight->elgible_for_ticket_cancellation($app_reference, $sequence_number, $ticket_ids, $IsFullBookingCancel, $this->booking_source);
        //debug($elgible_for_ticket_cancellation);exit;
        if ($elgible_for_ticket_cancellation['status'] == SUCCESS_STATUS) {

            $booking_details = $this->CI->flight_model->get_flight_booking_transaction_details($app_reference, $sequence_number, $this->booking_source);
            $booking_details = $booking_details['data'];
            $booking_transaction_details = $booking_details['booking_transaction_details'][0];
            $flight_booking_transaction_details_origin = $booking_transaction_details['origin'];

            $request_params = $booking_details;
            $request_params['passenger_origins'] = $ticket_ids;
            $request_params['IsFullBookingCancel'] = $IsFullBookingCancel;
            $request_params['PNR_NO'] = $pnr_no;
            //debug($request_params);exit;
            //SendChange Request
            $send_change_request = $this->pnr_cancel_request($request_params);

            if ($send_change_request['status'] == SUCCESS_STATUS) {
                $response['status'] = SUCCESS_STATUS;
                $response['message'] = 'Cancellation Done';
                // $send_change_response = $send_change_request['data']['send_change_response'];
                $passenger_origin = $request_params['passenger_origins'];
                foreach ($passenger_origin as $origin) {
                    $this->CI->common_flight->update_ticket_cancel_status($app_reference, $sequence_number, $origin);
                }
            } else {
                $response['message'] = $send_change_request['message'];
            }
        } else {
            $response['message'] = $elgible_for_ticket_cancellation['message'];
        }
        return $response;
    }
    /**
     * Send ChangeRequest
     * @param unknown_type $booking_details
     * //ChangeRequestStatus: NotSet = 0,Unassigned = 1,Assigned = 2,Acknowledged = 3,Completed = 4,Rejected = 5,Closed = 6,Pending = 7,Other = 8
     */
    private function pnr_cancel_request($request_params)
    {
        $response['status'] = FAILURE_STATUS; // Status Of Operation
        $response['message'] = ''; // Message to be returned
        $response['data'] = array(); // Data to be returned
        if ($request_params['PNR_NO']) {
            $pnr_req['SessionId'] = '';
            $pnr_req['SequenceNumber'] = '';
            $pnr_req['SecurityToken'] = '';
            $pnr_req['Pnr_No'] = $request_params['PNR_NO'];
            $pnr_rerieve_response = $this->PNRRetrieve($pnr_req);
            if ($pnr_rerieve_response['status'] == SUCCESS_STATUS) {

                $cancel_second_response = $this->pnr_canel_second_request($pnr_rerieve_response);
                $soapAction = "PNRXCL_17_2_1A";
                $soap_url = $this->soap_url . $soapAction;
                $this->api_url = $this->api_url;
                //echo $this->api_url; exit;
                //$this->api_url = 'https://noded2.test.webservices.amadeus.com/1ASIWCTIZ77';
                $pnr_cancel_response = $this->process_request($cancel_second_response, $this->api_url, 'PNR_Cancel(amadeus)', $soap_url);
                $this->CI->custom_db->generate_static_response($pnr_cancel_response, 'Amadeus Flight PNR_Cancel Request');
                $this->CI->custom_db->generate_static_response($pnr_cancel_response, 'Amadeus Flight PNR_Cancel Response');

                if ($pnr_cancel_response) {
                    $pnr_cancel_response = $this->xml2array($pnr_cancel_response);
                    $session_id = $pnr_cancel_response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SessionId'];
                    $sequence_number = $pnr_cancel_response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SequenceNumber'] + 1;
                    $security_token = $pnr_cancel_response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SecurityToken'];
                    $control_number = $pnr_cancel_response['soapenv:Envelope']['soapenv:Body']['PNR_Reply']['pnrHeader']['reservationInfo']['reservation']['controlNumber'];
                    if (!empty($pnr_cancel_response)) {
                        $pnr_add_elements = $this->pnr_add_multi_elements($session_id, $sequence_number, $security_token, $control_number);

                        $pnr_add_elements = $this->xml2array($pnr_add_elements);
                        if ($pnr_add_elements['soapenv:Envelope']['soapenv:Body']['PNR_Reply']['pnrHeader']['reservationInfo']['reservation']['controlNumber'] == $request_params['PNR_NO']) {
                            $response['status'] = SUCCESS_STATUS;
                            $response['message'] = 'Cancelled successfully';
                        }
                    }
                }
            }
        }
        return $response;
    }
    public function pnr_canel_second_request($pnr_rerieve_response)
    {
        $soapAction = "PNRXCL_17_2_1A";
        $xml_pnrCancle = '<?xml version="1.0" encoding="UTF-8"?><soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:awsse="http://xml.amadeus.com/2010/06/Session_v3" xmlns:iat="http://www.iata.org/IATA/2007/00/IATA2010.1" xmlns:sec="http://xml.amadeus.com/2010/06/Security_v1">
                    <soapenv:Header xmlns:add="http://www.w3.org/2005/08/addressing">
                        <awsse:Session TransactionStatusCode="InSeries">
                            <awsse:SessionId>' . $pnr_rerieve_response['data']['SessionId'] . '</awsse:SessionId>
                            <awsse:SequenceNumber>' . $pnr_rerieve_response['data']['SequenceNumber'] . '</awsse:SequenceNumber>
                            <awsse:SecurityToken>' . $pnr_rerieve_response['data']['SecurityToken'] . '</awsse:SecurityToken>
                        </awsse:Session>
                        <add:MessageID>' . $this->getuuid() . '</add:MessageID>
                        <add:Action>http://webservices.amadeus.com/' . $soapAction . '</add:Action>
                        <add:To>' . trim($this->api_url) . '</add:To>
                    </soapenv:Header>
                   <soapenv:Body>
                   <PNR_Cancel>
                        <reservationInfo>
                            <reservation>
                                <controlNumber>' . $pnr_rerieve_response['data']['Pnr_No'] . '</controlNumber>
                            </reservation>
                        </reservationInfo>
                        <pnrActions>
                            <optionCode>0</optionCode>
                        </pnrActions>
                        <cancelElements>
                            <entryType>I</entryType>
                        </cancelElements>
                    </PNR_Cancel>
                   </soapenv:Body>
                </soapenv:Envelope>';
        return $xml_pnrCancle;
    }
    public function pnr_add_multi_elements($session_id, $sequence_number, $security_token, $control_number)
    {
        $soapAction = "PNRADD_19_1_1A";
        $xml_pnrCancle = '<?xml version="1.0" encoding="UTF-8"?><soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:awsse="http://xml.amadeus.com/2010/06/Session_v3" xmlns:iat="http://www.iata.org/IATA/2007/00/IATA2010.1" xmlns:sec="http://xml.amadeus.com/2010/06/Security_v1">
                    <soapenv:Header xmlns:add="http://www.w3.org/2005/08/addressing">
                        <awsse:Session TransactionStatusCode="InSeries">
                            <awsse:SessionId>' . $session_id . '</awsse:SessionId>
                            <awsse:SequenceNumber>' . $sequence_number . '</awsse:SequenceNumber>
                            <awsse:SecurityToken>' . $security_token . '</awsse:SecurityToken>
                        </awsse:Session>
                        <add:MessageID>' . $this->getuuid() . '</add:MessageID>
                        <add:Action>http://webservices.amadeus.com/' . $soapAction . '</add:Action>
                        <add:To>' . trim($this->api_url) . '</add:To>
                    </soapenv:Header>
                   <soapenv:Body>
                   <PNR_AddMultiElements>
                        <pnrActions>
                            <optionCode>10</optionCode>
                        </pnrActions>
                        <dataElementsMaster>
                            <marker1/>
                            <dataElementsIndiv>
                                <elementManagementData>
                                    <segmentName>RF</segmentName>
                                </elementManagementData>
                                <freetextData>
                                    <freetextDetail>
                                        <subjectQualifier>3</subjectQualifier>
                                        <type>P22</type>
                                    </freetextDetail>
                                    <longFreetext>FREE TEXT</longFreetext>
                                </freetextData>
                            </dataElementsIndiv>
                        </dataElementsMaster>
                    </PNR_AddMultiElements>
                   </soapenv:Body>
                </soapenv:Envelope>';
        $soap_url = $this->soap_url . $soapAction;
        $this->api_url = $this->api_url;
        $this->api_url = 'https://noded2.test.webservices.amadeus.com/1ASIWCTIZ77';
        $this->CI->custom_db->generate_static_response($xml_pnrCancle, 'Amadeus Flight PNR_Multi10_Cancel Request');
        $pnr_cancel_response = $this->process_request($xml_pnrCancle, $this->api_url, 'PNR_CancelADD(amadeus)', $soap_url);
        $this->CI->custom_db->generate_static_response($pnr_cancel_response, 'Amadeus Flight PNR_Multi10_Cancel Response');
        return $pnr_cancel_response;
    }
    /**
     * Forms the SendChangeRequest
     * @param unknown_type $request
     */
    private function format_send_change_request($params)
    {
        // debug($params);exit;
        // echo 'herrer I am';exit;
        $booking_transaction_details = $params['booking_transaction_details'][0];
        $pnr = trim($booking_transaction_details['pnr']);
        $travel_read_itinerary_request = $this->TravelItineraryReadInfo_Request($pnr);
        $this->process_request($travel_read_itinerary_request['request'], $travel_read_itinerary_request['url'], $travel_read_itinerary_request['remarks']);
        $cancel_request = $this->OTA_CancelRQ();
        // debug($cancel_request);exit;
        $cancel_response = $this->process_request($cancel_request['request'], $cancel_request['url'], $cancel_request['remarks']);
        $end_transaction_request = $this->EndTransaction_Request($pnr);
        $this->process_request($end_transaction_request['request'], $end_transaction_request['url'], $end_transaction_request['remarks']);
        $request['status'] = SUCCESS_STATUS;
        $request['cancel_response'] = $cancel_response;
        return $request;
    }
    function OTA_CancelRQ()
    {
        $request_params = "<?xml version='1.0' encoding='utf-8'?>
                  <soap-env:Envelope xmlns:soap-env='http://schemas.xmlsoap.org/soap/envelope/'>
                      <soap-env:Header>
                          <eb:MessageHeader xmlns:eb='http://www.ebxml.org/namespaces/messageHeader'>
                              <eb:From>
                                  <eb:PartyId eb:type='urn:x12.org.IO5:01'>" . $this->config['sabre_email'] . "</eb:PartyId>
                              </eb:From>
                              <eb:To>
                                  <eb:PartyId eb:type='urn:x12.org.IO5:01'>webservices.sabre.com</eb:PartyId>
                              </eb:To>
                              <eb:ConversationId>" . $this->conversation_id . "</eb:ConversationId>
                                <eb:Service>OTA_CancelLLSRQ</eb:Service>
                                <eb:Action>OTA_CancelLLSRQ</eb:Action>
                              <eb:CPAID>" . $this->config['ipcc'] . "</eb:CPAID>
                              <eb:MessageData>
                                  <eb:MessageId>" . $this->message_id . "</eb:MessageId>
                                  <eb:Timestamp>" . $this->timestamp . "</eb:Timestamp>
                                  <eb:TimeToLive>" . $this->timetolive . "</eb:TimeToLive>
                              </eb:MessageData>
                          </eb:MessageHeader>
                          <wsse:Security xmlns:wsse='http://schemas.xmlsoap.org/ws/2002/12/secext'>
                              <wsse:UsernameToken>
                                  <wsse:Username>" . $this->config['username'] . "</wsse:Username>
                                  <wsse:Password>" . $this->config['password'] . "</wsse:Password>
                                  <Organization>" . $this->config['ipcc'] . "</Organization>
                                  <Domain>Default</Domain>
                              </wsse:UsernameToken>
                              <wsse:BinarySecurityToken>" . $this->api_session_id . "</wsse:BinarySecurityToken>
                          </wsse:Security>
                      </soap-env:Header>
                      <soap-env:Body>
                        <OTA_CancelRQ Version='2.0.0' xmlns='http://webservices.sabre.com/sabreXML/2011/10' xmlns:xs='http://www.w3.org/2001/XMLSchema' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance'>
                            <Segment Type='air'/>
                        </OTA_CancelRQ>                   
                      </soap-env:Body>
                  </soap-env:Envelope>";
        $request['request'] = $request_params;
        $request['url'] = $this->config['api_url'];
        $request['remarks'] = 'Cancellation(Sabare)';
        $request['status'] = SUCCESS_STATUS;
        // debug($request);exit;
        return $request;
    }
    private function save_flight_ticket_details($booking_params, $airline_pnr, $app_reference, $sequence_number, $search_id, $retrieve_response)
    {
        // debug($booking_response);exit;
        $flight_booking_transaction_details_fk = $this->CI->custom_db->single_table_records('flight_booking_transaction_details', 'origin', array('app_reference' => $app_reference, 'sequence_number' => $sequence_number));
        $flight_booking_itinerary_details_fk = $this->CI->custom_db->single_table_records('flight_booking_itinerary_details', 'airline_code,origin', array('app_reference' => $app_reference));

        $flight_booking_transaction_details_fk = $flight_booking_transaction_details_fk['data'][0]['origin'];
        $update_data['airline_pnr'] = $airline_pnr;
        $update_condition['app_reference'] = $app_reference;
        $update_condition['sequence_number'] = $sequence_number;
        $this->CI->custom_db->update_record('flight_booking_transaction_details', $update_data, $update_condition);
        $flight_booking_status = 'BOOKING_CONFIRMED';
        $this->CI->common_flight->update_flight_booking_status($flight_booking_status, $app_reference, $sequence_number, $this->booking_source);
        $passenger_details = $this->CI->custom_db->single_table_records('flight_booking_passenger_details', '', array('app_reference' => $app_reference));
        $passenger_details = $passenger_details['data'];
        // debug($flight_booking_itinerary_details_fk);exit;
        foreach ($flight_booking_itinerary_details_fk['data'] as $itinerary) {
            $update_itinerary_condition = array();
            $update_itinerary_condition['flight_booking_transaction_details_fk'] = $itinerary['origin'];
            $update_itinerary_condition['app_reference'] = $app_reference;
            //itinerary updated data
            $update_itinerary_data = array();

            $update_itinerary_data['airline_pnr'] = $airline_pnr;
            $GLOBALS['CI']->custom_db->update_record('flight_booking_itinerary_details', $update_itinerary_data, $update_itinerary_condition);
        }
        $itineray_price_details = $booking_params['flight_data']['PriceBreakup'];
        // debug($itineray_price_details);exit;
        $airline_code = '';
        if (isset($flight_booking_itinerary_details_fk['data'][0]['airline_code'])) {
            $airline_code = $flight_booking_itinerary_details_fk['data'][0]['airline_code'];
        }
        $flight_price_details = $this->CI->common_flight->final_booking_transaction_fare_details($itineray_price_details, $search_id, $this->booking_source, $airline_code);
        // debug($flight_price_details);exit;
        $fare_details = $flight_price_details['Price'];
        $fare_breakup = $flight_price_details['PriceBreakup'];
        $passenger_breakup = $fare_breakup['PassengerBreakup'];
        $single_pax_fare_breakup = $this->CI->common_flight->get_single_pax_fare_breakup($passenger_breakup);


        // $passenger_details = force_multple_data_format($passenger_details);
        $get_passenger_details_condition = array();
        $get_passenger_details_condition['flight_booking_transaction_details_fk'] = $flight_booking_transaction_details_fk;
        $passenger_details_data = $GLOBALS['CI']->custom_db->single_table_records('flight_booking_passenger_details', 'origin, passenger_type', $get_passenger_details_condition);
        $passenger_details_data = $passenger_details_data['data'];
        $passenger_origins = group_array_column($passenger_details_data, 'origin');
        $passenger_types = group_array_column($passenger_details_data, 'passenger_type');
        // echo 'mnngng';
        $passenger_ticket = array();
        if (isset($retrieve_response['PNR_Reply']['dataElementsMaster']['dataElementsIndiv'])) {
            $pass_tkt = $retrieve_response['PNR_Reply']['dataElementsMaster']['dataElementsIndiv'];
            $pik = 0;
            foreach ($pass_tkt as $pass_k => $pass_v) {
                if ($pass_v['elementManagementData']['segmentName'] == 'FA') {
                    $long_text = explode('/', $pass_v['otherDataFreetext']['longFreetext']);
                    $long_free_text = explode(' ', $long_text[0]);
                    $ticket_number = str_replace('-', '', $long_free_text[1]);
                    $passenger_ticket[$pik] = $ticket_number;
                    $pik++;
                }
            }
        }
        foreach ($passenger_details as $pax_k => $pax_v) {
            $passenger_fk = intval(array_shift($passenger_origins));
            $pax_type = array_shift($passenger_types);
            switch ($pax_type) {
                case 'Adult':
                    $pax_type = 'ADT';
                    break;
                case 'Child':
                    $pax_type = 'CHD';
                    break;
                case 'Infant':
                    $pax_type = 'INF';
                    break;
            }
            if (empty($passenger_ticket)) {
                $ticket_id = $passenger_ticket[$pax_k];
                $ticket_number = $passenger_ticket[$pax_k];
            } else {
                $ticket_id = $passenger_ticket[$pax_k];
                $ticket_number = $passenger_ticket[$pax_k];
            }
            //Update Passenger Ticket Details
            $this->CI->common_flight->update_passenger_ticket_info($passenger_fk, $ticket_id, $ticket_number, $single_pax_fare_breakup[$pax_type]);
        }
    }
    /**
     * Save Book Service Response
     * @param unknown_type $book_response
     * @param unknown_type $app_reference
     * @param unknown_type $sequence_number
     */
    private function save_book_response_details($pnr, $app_reference, $sequence_number)
    {
        $update_data = array();
        $update_condition = array();

        $update_data['pnr'] = $pnr;
        $update_data['book_id'] = $pnr;

        // debug($update_data);exit;
        $update_condition['app_reference'] = $app_reference;
        $update_condition['sequence_number'] = $sequence_number;

        $this->CI->custom_db->update_record('flight_booking_transaction_details', $update_data, $update_condition);

        $flight_booking_status = 'BOOKING_HOLD';
        $this->CI->common_flight->update_flight_booking_status($flight_booking_status, $app_reference, $sequence_number, $this->booking_source);
    }
    private function valid_travelintinerary_response($api_response)
    {
        $response = false;
        if (isset($api_response['soap-env:Envelope']['soap-env:Body']['TravelItineraryAddInfoRS']['stl:ApplicationResults']['stl:Error']) == false) {
            $response = true;
        }
        return $response;
    }
    private function valid_airbook_response($api_response)
    {
        $response = false;
        if (isset($api_response['soap-env:Envelope']['soap-env:Body']['EnhancedAirBookRS']['stl:ApplicationResults']['stl:Error']) == false) {
            $response = true;
        }
        return $response;
    }

    private function valid_specialservice_response($api_response)
    {
        $response = false;
        if (isset($api_response['soap-env:Envelope']['soap-env:Body']['SpecialServiceRS']['stl:ApplicationResults']['stl:Error']) == false) {
            $response = true;
        }
        return $response;
    }
    /**
     * Authentication Request
     */
    public function get_authentication_request($internal_request = false)
    {
        $response['status'] = FAILURE_STATUS; // Status Of Operation
        $response['message'] = ''; // Message to be returned
        $response['data'] = array(); // Data to be returned

        $active_booking_source = $this->is_active_booking_source();

        if ($active_booking_source['status'] == SUCCESS_STATUS) {
            $authenticate_request = $this->authenticate_request();

            if ($authenticate_request['status'] = SUCCESS_STATUS) {
                $response['status'] = SUCCESS_STATUS;
                $curl_request = $this->form_curl_params($authenticate_request['request'], $authenticate_request['url']);

                $response['data'] = $curl_request['data'];
            }
            if ($internal_request == true) {
                $response['data']['remarks'] = 'Authentication(Sabare)';
            }
        }

        return $response;
    }
    /**
     * Authentcation RQ for api
     */
    private function authenticate_request()
    {
        $request = array();
        $authentication_request =
            '<?xml version="1.0" encoding="utf-8"?>
            <soap-env:Envelope xmlns:soap-env="http://schemas.xmlsoap.org/soap/envelope/">
                <soap-env:Header>
                    <eb:MessageHeader xmlns:eb="http://www.ebxml.org/namespaces/messageHeader">
                        <eb:From>
                            <eb:PartyId eb:type="urn:x12.org.IO5:01">' . $this->config['sabre_email'] . '</eb:PartyId>
                        </eb:From>
                        <eb:To>
                            <eb:PartyId eb:type="urn:x12.org.IO5:01">webservices3.sabre.com</eb:PartyId>
                        </eb:To>
                        <eb:ConversationId>' . $this->conversation_id . '</eb:ConversationId>
                        <eb:Service eb:type="SabreXML">Session</eb:Service>
                        <eb:Action>SessionCreateRQ</eb:Action>
                        <eb:CPAID>' . $this->config['ipcc'] . '</eb:CPAID>
                        <eb:MessageData>
                            <eb:MessageId>' . $this->message_id . '</eb:MessageId>
                            <eb:Timestamp>' . $this->timestamp . '</eb:Timestamp>
                            <eb:TimeToLive>' . $this->timetolive . '</eb:TimeToLive>
                        </eb:MessageData>
                    </eb:MessageHeader>
                    <wsse:Security xmlns:wsse="http://schemas.xmlsoap.org/ws/2002/12/secext">
                        <wsse:UsernameToken>
                            <wsse:Username>' . $this->config['username'] . '</wsse:Username>
                            <wsse:Password>' . $this->config['password'] . '</wsse:Password>
                            <Organization>' . $this->config['ipcc'] . '</Organization>
                            <Domain>Default</Domain>
                        </wsse:UsernameToken>
                    </wsse:Security>
                </soap-env:Header>
                <soap-env:Body>
                    <SessionCreateRQ>
                        <POS>
                            <Source PseudoCityCode="' . $this->config['ipcc'] . '" />
                        </POS>
                    </SessionCreateRQ>
                </soap-env:Body>
            </soap-env:Envelope>';
        $request['request'] = $authentication_request;
        $request['url'] = $this->config['api_url'];
        $request['status'] = SUCCESS_STATUS;
        // debug($request);exit;
        return $request;
    }
    /**
     * process soap API request
     *
     * @param string $request
     */
    function form_curl_params($request, $url, $soap_url = '')
    {
        $data['status'] = SUCCESS_STATUS;
        $data['message'] = '';
        $data['data'] = array();

        $curl_data = array();
        $curl_data['booking_source'] = $this->booking_source;
        $curl_data['request'] = $request;
        $curl_data['url'] = $url;
        $curl_data['header'] = array(
            'Content-Type: text/xml; charset="utf-8"',
            'Content-Length: ' . strlen($request),
            //'Accept-Encoding: gzip,deflate',
            'Accept: text/xml',
            'Cache-Control: no-cache',
            'Pragma: no-cache',
            'SOAPAction: "' . $soap_url . '"'
        );

        $data['data'] = $curl_data;
        // debug($data);exit;
        return $data;
    }
    /**
     * Process API Request
     * @param unknown_type $request
     * @param unknown_type $url
     */
    function process_request($request, $url, $remarks = '', $soap_url)
    {
        $insert_id = $this->CI->api_model->store_api_request($url, $request, $remarks);
        $insert_id = intval(@$insert_id['insert_id']);
        try {
            $headers = array(
                'Content-Type: text/xml; charset="utf-8"',
                'Content-Length: ' . strlen($request),
                'Host: nodeD1.production.webservices.amadeus.com',
                'POST: "https://nodeD1.production.webservices.amadeus.com/"',
                //'Accept-Encoding: gzip,deflate',
                'Accept: text/xml',
                'Cache-Control: no-cache',
                'Pragma: no-cache',
                'SOAPAction: "' . $soap_url . '"'
            );
            global $curtime;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            //curl_setopt($ch, CURLOPT_TIMEOUT, 60); 
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
            # curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");
            $response = curl_exec($ch);
            //if($remarks == 'PNR_Cancel(amadeus)'){
            /* echo 'REMARKS****** : ';
                debug($response);*/
            //}
            //debug($response);
            #echo $response;exit;
            $error = curl_getinfo($ch);
        } catch (Exception $e) {
            $response = 'No Response Recieved From API';
        }
        //debug($response);exit;
        //Update the API Response
        $this->CI->api_model->update_api_response($response, $insert_id);
        /*$final_de = date('Ymd_His')."_".rand(1,10000);
        $XmlReqFileName = $remarks.'_Req'.$final_de; 
        $XmlResFileName = $remarks.'_Res'.$final_de;
        $fp = fopen(FCPATH.'amadeuslogs/'.$XmlReqFileName.'.xml', 'a+');
        fwrite($fp, $request);
        fclose($fp);
        $fp = fopen(FCPATH.'amadeuslogs/'.$XmlResFileName.'.xml', 'a+');
        fwrite($fp, $response);
        fclose($fp);*/
        $error = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $response;
    }
    /**
     * Process API Request
     * @param unknown_type $request
     * @param unknown_type $url
     */
    function process_request_book($request, $url, $remarks = '')
    {
        $insert_id = $this->CI->api_model->store_api_request($url, $request, $remarks);
        $insert_id = intval(@$insert_id['insert_id']);
        try {
            $httpHeader = array('Content-Type: text/xml; charset="utf-8"',);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeader);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
            curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");
            $response = curl_exec($ch);

            $error = curl_getinfo($ch);
        } catch (Exception $e) {
            $response = 'No Response Recieved From API';
        }
        // debug($response);
        // debug($error);
        // exit;
        //Update the API Response
        $this->CI->api_model->update_api_response($response, $insert_id);
        $error = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $response;
    }
    public function get_sabre_response($url, $request)
    {
        $httpHeader = array('Content-Length: 0', 'Content-Type: text/xml; charset="utf-8"',);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeader);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");
        $response = curl_exec($ch);
        return $response;
    }
    function getNoncevalue()
    {
        $Nonce = base64_encode(date('s:i:H Y-m-d'));
        return $Nonce;
    }

    function getCreateDate()
    {
        $gmdate = gmdate('Y-m-d\TH:i:s\Z');
        return $gmdate;
    }

    function hextobin($hexstr)
    {
        $n = strlen($hexstr);
        $sbin = "";
        $i = 0;
        while ($i < $n) {
            $a = substr($hexstr, $i, 2);
            $c = pack("H*", $a);
            if ($i == 0) {
                $sbin = $c;
            } else {
                $sbin .= $c;
            }
            $i += 2;
        }
        return $sbin;
    }


    function DigestAlgo($pwd, $created, $nonce)
    {
        $passha = $this->hextobin(strtoupper(sha1($pwd)));
        $Nonces = base64_decode($nonce);
        $DigHex = $this->hextobin(strtoupper(sha1($Nonces . $created . $passha)));
        return $passwordDigest = base64_encode($DigHex);
    }
    function uuid($serverID = 1)
    {
        $t = explode(" ", microtime());
        return sprintf(
            '%04x-%08s-%08s-%04s-%04x%04x',
            $serverID,
            $this->clientIPToHex(),
            substr("00000000" . dechex($t[1]), -8),
            substr("0000" . dechex(round($t[0] * 65536)), -4), // get 4HEX of microtime
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }

    function clientIPToHex($ip = "")
    {
        $hex = "";
        if ($ip == "") $ip = getEnv("REMOTE_ADDR");
        $part = explode('.', $ip);
        for ($i = 0; $i <= count($part) - 1; $i++) {
            $hex .= substr("0" . dechex($part[$i]), -2);
        }
        return $hex;
    }


    function getuuid()
    {
        return 'urn:uuid:' . $this->uuid();
    }


    function xml2array($xmlStr, $get_attributes = 1, $priority = 'tag')
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
            return;
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
                        $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
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
                        $repeated_tag_index[$tag . '_' . $level]++; //0 and 1 index is already taken
                    }
                }
            } elseif ($type == 'close') {
                $current = &$parent[$level - 1];
            }
        }
        //echo "<pre>"; print_r($xml_array); echo "</pre>";  die();
        return ($xml_array);
    }
    //get the pax reference
    private function get_paxref_search_req($pax_count, $pax_type, $paxRef)
    {
        if ($pax_count > 0) {
            $pax_reference = '';

            for ($p = 0; $p < $pax_count; $p++) {
                if ($pax_type == 'CH') {
                    $pax_type = 'CNN';
                }
                if ($p == 0) {
                    $pax_reference .= '<ptc>' . $pax_type . '</ptc>';
                }
                if ($pax_type == 'INF') {
                    $pax_reference .= '<traveller>
                            <ref>' . ($p + 1) . '</ref>
                            <infantIndicator>' . ($p + 1) . '</infantIndicator>
                        </traveller>';
                } else {
                    $pax_reference .= '<traveller>
                            <ref>' . $paxRef . '</ref>
                        </traveller>';
                }
                $paxRef++;
            }
        }
        return array('paxtag' => $pax_reference, 'paxRef' => $paxRef);
    }
    //If any error occured need to close the current session
    private function Security_SignOut($SecuritySession, $seq, $SecurityToken)
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>           
            <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:sec="http://xml.amadeus
            .com/2010/06/Security_v1" xmlns:typ="http://xml.amadeus.com/2010/06/Type">
            <soapenv:Header>
            <awsse:Session TransactionStatusCode="End" xmlns:awsse="http://xml.amadeus.com/2010/06/Session_v3">     
            <awsse:SessionId>' . $SecuritySession . '</awsse:SessionId>
            <awsse:SequenceNumber>' . $seq . '</awsse:SequenceNumber>
            <awsse:SecurityToken>' . $SecurityToken . '</awsse:SecurityToken>
            </awsse:Session>
            <add:MessageID xmlns:add="http://www.w3.org/2005/08/addressing">' . $this->getuuid() . '</add:MessageID>
            <add:Action xmlns:add="http://www.w3.org/2005/08/addressing">http://webservices.amadeus.com/VLSSOQ_04_1_1A</add:Action>
            <add:To xmlns:add="http://www.w3.org/2005/08/addressing">' . $this->api_url . '</add:To>
            </soapenv:Header>
            <soapenv:Body>
            <Security_SignOut xmlns="http://xml.amadeus.com/VLSSOQ_04_1_1A"></Security_SignOut>
            </soapenv:Body>
            </soapenv:Envelope>';
        $soapAction = "VLSSOQ_04_1_1A";
        $api_url = $this->api_url;
        $soap_url = $this->soap_url . $soapAction;
        $remarks = 'Security_SignOut(amadeus)';
        $this->process_request($xml, $api_url, $remarks, $soap_url);
    }

    //function to issue ticket of the held booking
    function issueHoldTicket($appref, $booking_params, $search_id)
    {
        //   debug("reached");die;
        $booking_params = (array) $booking_params;
        //   debug($booking_params);die;
        $PNR_AddMultiElements_Option10['data'] = (array)$booking_params['PNR_AddMultiElements_Option10'];

        unset($booking_params['PNR_AddMultiElements_Option10']);

        $PNRRetrieve = $this->PNRRetrieve($PNR_AddMultiElements_Option10['data']);
        // debug($PNRRetrieve);die;

        //sleep(3);// if we need direct ticketing need 
        if (true) {
            $DocIssurance_Issue_Ticket = $this->DocIssuance_IssueTicket($PNR_AddMultiElements_Option10['data']);

            // die;

            $PNRRetrieve = $this->PNRRetrieve($DocIssurance_Issue_Ticket['data']);

            $SessionId = $PNRRetrieve['data']['SessionId'];
            $SecurityToken = $PNRRetrieve['data']['SecurityToken'];
            $SequenceNumber = $PNRRetrieve['data']['SequenceNumber'];

            $this->Security_SignOut($SessionId, $SequenceNumber, $SecurityToken);

            $raw_response = $PNRRetrieve['raw_response']['soapenv:Envelope']['soapenv:Body'];
            // debug($raw_response);die("raw_response");
            $status = $this->save_flight_ticket_details($booking_params, $PNRRetrieve['data']['Pnr_No'], $appref, 0, $search_id, $raw_response);
            if ($status) {
                $response['status'] = 1;
            }
            $response['message'] = 'Ticket method called';
        }
        return $response;
    }

    //save the held booking params for later ticket issue
    function saveBookingParams($app_reference = '', $booking_params = '', $search_data = '')
    {
        $condition['app_reference'] = $app_reference;
        $condition['status'] = BOOKING_HOLD;
        $attributesData['booking_params'] = $booking_params;
        $attributesData['search_id'] = $search_data;
        $data['attributes'] = json_encode($attributesData);

        $this->CI->custom_db->update_record('flight_booking_transaction_details', $data, $condition);
    }
}
