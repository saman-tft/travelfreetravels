<?php

require_once BASEPATH . 'libraries/flight/Common_api_flight.php';

class Amadeus extends Common_Api_Flight {

    var $master_search_data;
    var $search_hash;
    protected $token;
    private $end_user_ip = '127.0.0.1';
    var $api_session_id;

    function __construct() {

        parent::__construct(META_AIRLINE_COURSE, AMADEUS_FLIGHT_BOOKING_SOURCE);
        $this->CI = &get_instance();
        $this->CI->load->library('Converter');
        $this->CI->load->library('ArrayToXML');
        $this->set_api_credentials();
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
       // $this->pseudo_city_code = trim($this->config['PseudoCityCode']);
        $this->pseudo_city_code = trim("SNA1S210I");        
        $this->agent_duty_code = trim($this->config['AgentDutyCode']);
        $this->requestor_type = trim($this->config['RequestorType']);
        $this->created = $this->getCreateDate();
        $this->nonce = $this->getNoncevalue();
        $this->hashPwd = $this->DigestAlgo($this->password,$this->created,$this->nonce);
        $this->soap_url = 'http://webservices.amadeus.com/';
        
    }
     /**
     * Setting Session ID
     */
    public function set_api_session_id($authentication_response = '') {

        if (empty($this->api_session_id) == true) {

            if (empty($authentication_response) == false) {
               
                $authentication_response = Converter::createArray($authentication_response);
                // debug($authentication_response);exit;
                //store in database
                
                if ($this->valid_create_session_response($authentication_response)) {
                    $authenticate_token =$authentication_response['soap-env:Envelope']['soap-env:Header']['wsse:Security']['wsse:BinarySecurityToken']['@value'];                    
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
                        $authentication_response = $this->process_request($authentication_request ['request'], $authentication_request ['url'], $authentication_request ['remarks']);
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
        if(isset($response['soap-env:Envelope']['soap-env:Header']['wsse:Security']['wsse:BinarySecurityToken']['@value']) ==true){
            $result =  true;
        }
        return $result;
    }

    public function search_data($search_id) {
        $response ['status'] = true;
        $response ['data'] = array();
        if (empty($this->master_search_data) == true and valid_array($this->master_search_data) == false) {
            $clean_search_details = $this->CI->flight_model->get_safe_search_data($search_id);

            if ($clean_search_details ['status'] == true) {
                $response ['status'] = true;
                $response ['data'] = $clean_search_details ['data'];

                // 28/12/2014 00:00:00 - date format
                if ($clean_search_details['data']['trip_type'] == 'multicity') {
                    $response ['data'] ['from_city'] = $clean_search_details ['data'] ['from'];
                    $response ['data'] ['to_city'] = $clean_search_details ['data'] ['to'];
                    $response ['data'] ['depature'] = $clean_search_details ['data'] ['depature'];
                    $response ['data'] ['return'] = $clean_search_details ['data'] ['depature'];
                } else {
                    $response ['data'] ['from'] = substr(chop(substr($clean_search_details ['data'] ['from'], - 5), ')'), - 3);
                    $response ['data'] ['to'] = substr(chop(substr($clean_search_details ['data'] ['to'], - 5), ')'), - 3);
                    $response ['data'] ['depature'] = date("Y-m-d", strtotime($clean_search_details ['data'] ['depature'])) . 'T00:00:00';
                    if(isset($clean_search_details ['data'] ['return'])) {
                    $response ['data'] ['return'] = date("Y-m-d", strtotime($clean_search_details ['data'] ['return'])) . 'T00:00:00';
                    }
                }

                switch ($clean_search_details ['data'] ['trip_type']) {

                    case 'oneway' :
                        $response ['data'] ['type'] = 'OneWay';
                        break;

                    case 'circle' :
                        $response ['data'] ['type'] = 'Return';
                        $response ['data'] ['return'] = date("Y-m-d", strtotime($clean_search_details ['data'] ['return'])) . 'T00:00:00';
                        break;

                    default :
                        $response ['data'] ['type'] = 'OneWay';
                }
                if ($response ['data'] ['is_domestic'] == true and $response ['data'] ['trip_type'] == 'return') {
                    $response ['data'] ['domestic_round_trip'] = true;
                    //$response ['status'] = false;
                } else {
                    $response ['data'] ['domestic_round_trip'] = false;
                }
                $response ['data'] ['adult'] = $clean_search_details ['data'] ['adult_config'];
                $response ['data'] ['child'] = $clean_search_details ['data'] ['child_config'];
                $response ['data'] ['infant'] = $clean_search_details ['data'] ['infant_config'];
                $response ['data'] ['v_class'] = @$clean_search_details ['data'] ['v_class'];
                $response ['data'] ['carrier'] = implode($clean_search_details ['data'] ['carrier']);
                $this->master_search_data = $response ['data'];
            } else {
                $response ['status'] = false;
            }
        } else {
            $response ['data'] = $this->master_search_data;
        }
        $this->search_hash = md5(serialized_data($response ['data']));

        return $response;
    }
    /**
     * Search Request
     * @param unknown_type $search_id
     */
    public function get_search_request($search_id) {
       
        $response ['status'] = FAILURE_STATUS; // Status Of Operation
        $response ['message'] = ''; // Message to be returned
        $response ['data'] = array(); // Data to be returned       
       
        /* get search criteria based on search id */
        $search_data = $this->search_data($search_id);
        if ($search_data ['status'] == SUCCESS_STATUS) {
            // Flight search RQ
            $search_request = $this->search_request($search_data ['data']);
            if ($search_request ['status'] = SUCCESS_STATUS) {
                $response ['status'] = SUCCESS_STATUS;
                $curl_request = $this->form_curl_params($search_request ['request'], $search_request ['url'],$search_request['soap_url']);
                $response ['data'] = $curl_request['data'];
            }
        }
        // debug($response);exit;
        return $response;
    }
     /**
     * Formates Search Request
     */
    private function search_request($search_data) {
       
        $response['status'] = SUCCESS_STATUS;
        $response['data']   = array();
        $search_params = $search_data;
        $trip_type = $search_params['trip_type'];
        $segref = '';
        $from =array();
        $to =array();
        $depature = array();
        $return = array();
       # debug($search_params);exit;
        if($trip_type !=='multicity'){
            $depature[] = $search_params['depature'];
            $from[] = $search_params['from'];
            $to[] = $search_params['to'];  
            if($trip_type =='return'){
                $from[] =$search_params['to']; 
                $to [] =$search_params['from'];
                $depature[] = $search_params['return'];
            }          
        }else{
            $depature= $search_params['depature'];
            $from = $search_params['from'];
            $to = $search_params['to'];
        }
        $seg_count = 1;
        foreach ($from as $key => $value) {
           $segref .='<itinerary>
                    <requestedSegmentRef>
                        <segRef>'.$seg_count.'</segRef>
                    </requestedSegmentRef>
                    <departureLocalization>
                        <departurePoint>                            
                            <locationId>'.$value.'</locationId>
                        </departurePoint>
                    </departureLocalization>
                    <arrivalLocalization>
                        <arrivalPointDetails>                           
                            <locationId>'.$to[$key].'</locationId>
                        </arrivalPointDetails>
                    </arrivalLocalization>
                    <timeDetails>
                        <firstDateTimeDetail>
                            <date>'.date('dmy',strtotime($depature[$key])).'</date>
                        </firstDateTimeDetail>
                    </timeDetails>
                </itinerary>';
            $seg_count++;
        }
        //only adult + child not infant
        $total_pax =$search_params['adult_config'] + $search_params['child_config'];
        $paxTag_reference = '';
        $paxTagCHD ='';
        $paxTagINF ='';
        $paxTag = $this->get_paxref_search_req($search_params['adult_config'],'ADT',1);
        $paxTagADT =$paxTag['paxtag'];      
        $paxTag_reference .='<paxReference>'.$paxTagADT.'</paxReference>';
        if($search_params['child_config']>0){
            $paxTagc = $this->get_paxref_search_req($search_params['child_config'],'CH',$paxTag['paxRef']);
            $paxTagCHD = $paxTagc['paxtag'];
            $paxTag_reference .='<paxReference>'.$paxTagCHD.'</paxReference>';
        }
        if($search_params['infant_config']>0){
            $paxTagi = $this->get_paxref_search_req($search_params['infant_config'],'INF',1);
            $paxTagINF = $paxTagi['paxtag'];
            $paxTag_reference .='<paxReference>'.$paxTagINF.'</paxReference>';
        }
        
        $class_code  = $this->get_search_class_code(strtolower($search_params['cabin_class']));
        $cabin_text_value = '<cabinId><cabinQualifier>RC</cabinQualifier><cabin>' . $class_code . '</cabin></cabinId>';
        $soapAction = "FMPTBQ_17_4_1A";// //FMPTBQ_17_4_1A
    $xml_query='
            <?xml version="1.0" encoding="UTF-8"?>           
            <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:sec="http://xml.amadeus.com/2010/06/Security_v1" xmlns:typ="http://xml.amadeus.com/2010/06/Type">
            <soapenv:Header>
            <add:MessageID xmlns:add="http://www.w3.org/2005/08/addressing">'.$this->getuuid().'</add:MessageID>
            <add:Action xmlns:add="http://www.w3.org/2005/08/addressing">http://webservices.amadeus.com/'.$soapAction.'</add:Action>
            <add:To xmlns:add="http://www.w3.org/2005/08/addressing">'.$this->api_url.'</add:To>
            <link:TransactionFlowLink xmlns:link="http://wsdl.amadeus.com/2010/06/ws/Link_v1"/>
            <oas:Security xmlns:oas="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
            <oas:UsernameToken oas1:Id="UsernameToken-1" xmlns:oas1="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
            <oas:Username>'.$this->username.'</oas:Username>
            <oas:Nonce EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary">'.$this->nonce.'</oas:Nonce>
            <oas:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest">'.$this->hashPwd.'</oas:Password>
            <oas1:Created>'.$this->created.'</oas1:Created>
        </oas:UsernameToken>
        </oas:Security>
        <AMA_SecurityHostedUser xmlns="http://xml.amadeus.com/2010/06/Security_v1">
        <UserID AgentDutyCode="'.$this->agent_duty_code.'" RequestorType="'.$this->requestor_type.'" PseudoCityCode="'.$this->pseudo_city_code.'" POS_Type="'.$this->pos_type.'"/>
        </AMA_SecurityHostedUser>
        </soapenv:Header>
        <soapenv:Body>
        <Fare_MasterPricerTravelBoardSearch xmlns="http://xml.amadeus.com/'.$soapAction.'"> ';
        $xml_query .=' <numberOfUnit>
                    <unitNumberDetail>
                        <numberOfUnits>'.$total_pax.'</numberOfUnits>
                        <typeOfUnit>PX</typeOfUnit>
                    </unitNumberDetail>
                    <unitNumberDetail>
                        <numberOfUnits>100</numberOfUnits>
                        <typeOfUnit>RC</typeOfUnit>
                    </unitNumberDetail>
                </numberOfUnit>';
        $xml_query .=$paxTag_reference;
        $xml_query.='<fareOptions>   
                        <pricingTickInfo>
                            <pricingTicketing>
                                <priceType>CUC</priceType>
                                <priceType>RP</priceType>
                                <priceType>RU</priceType>
                                <priceType>TAC</priceType>
                                <priceType>ET</priceType>
                            </pricingTicketing>
                        </pricingTickInfo>
                        <conversionRate>
                            <conversionRateDetail>
                                <currency>USD</currency>
                            </conversionRateDetail>
                        </conversionRate>
                    </fareOptions>';
        $xml_query.='<travelFlightInfo>
                        '.$cabin_text_value.'
                    </travelFlightInfo>';
        $xml_query .=$segref;
        $xml_query .='</Fare_MasterPricerTravelBoardSearch>
        </soapenv:Body>
    </soapenv:Envelope>'; 
        $request ['request'] = $xml_query;
        $request ['url'] = $this->api_url;
        $request ['soap_url'] = $this->soap_url.$soapAction;
        $request ['status'] = SUCCESS_STATUS;   
        return $request;
    }
    /**
     * Returns Flight List
     * @param unknown_type $search_id
     */
    public function get_flight_list($flight_raw_data, $search_id) {
        $response ['status'] = FAILURE_STATUS; // Status Of Operation
        $response ['message'] = ''; // Message to be returned
        $response ['data'] = array(); // Data to be returned
        
        $search_data = $this->search_data($search_id);
        if ($search_data ['status'] == SUCCESS_STATUS) {
            //$api_response = Converter::createArray($flight_raw_data);
            $api_response = $this->xml2array($flight_raw_data);
            
            if ($this->valid_search_result($api_response) == TRUE) {
                $clean_format_data = $this->format_search_data_response($api_response, $search_data ['data']);
                if ($clean_format_data) {
                    $response ['status'] = SUCCESS_STATUS;
                } else {
                    $response ['status'] = FAILURE_STATUS;
                }
            } else {
                $response ['status'] = FAILURE_STATUS;
            }

            if ($response ['status'] == SUCCESS_STATUS) {
                $response ['data'] = $clean_format_data;
            }
        } else {
            $response ['status'] = FAILURE_STATUS;
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
    function format_search_data_response($search_result, $search_data) {
       
        if($search_data['trip_type']=='multicity'){
            $SearchOrigin = $search_data['from'][0];
            $SearchDestination = end($search_data['to']);
        }else{
            $SearchOrigin = $search_data['from'];
            $SearchDestination = $search_data['to'];
        }
        
        $trip_type = isset($search_data ['is_domestic']) && !empty($search_data ['is_domestic']) ? 'domestic' : 'international';
        $currency = $search_result['soapenv:Envelope']['soapenv:Body']['Fare_MasterPricerTravelBoardSearchReply']['conversionRate']['conversionRateDetail']['currency'];

        $Results = $search_result['soapenv:Envelope']['soapenv:Body']['Fare_MasterPricerTravelBoardSearchReply']['flightIndex'];
        $Session_id = $search_result['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SessionId'];
        $SecurityToken = $search_result['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SecurityToken'];
        $SequenceNumber = $search_result['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SequenceNumber'];
        $Recommedation = $search_result['soapenv:Envelope']['soapenv:Body']['Fare_MasterPricerTravelBoardSearchReply']['recommendation'];
        $flightDetails = array();
        $Results = force_multple_data_format($Results);
        $Recommedation = force_multple_data_format($Recommedation);
        foreach ($Recommedation as $p => $rs_value) {
            if(isset($rs_value['itemNumber']['itemNumberId']['numberType'])){
                $price = $p;$flag = "MTK";
            }else{
                $price = 0;$flag = "Normal";
            }
            $segmentFlightRef = force_multple_data_format($rs_value['segmentFlightRef']);
            foreach ($segmentFlightRef as $sfr => $sfr_value) {
                $referencingDetail = force_multple_data_format($sfr_value['referencingDetail']);
                foreach($referencingDetail as $rd=>$rd_value ){
                    $refNumber              = $rd_value['refNumber']."-".$flag."-".$p;
                    $refNumberFlight        = $rd_value['refNumber'];
                    $refQualifier           = $rd_value['refQualifier'];
                    if(isset($rs_value['itemNumber']['itemNumberId']['numberType'])){
                        $flightDetails[$refNumber][$price]['PriceInfo']['MultiTicket']          = "Yes";
                        // $flightDetails[$refNumber][$p]['PriceInfo']['MultiTicket_type']      = $s['itemNumber']['itemNumberId']['numberType'];
                        $flightDetails[$refNumber][$price]['PriceInfo']['MultiTicket_number']   = $rs_value['itemNumber']['itemNumberId']['number'];
                    }else{
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
                            if(isset($paxFareProduct[$pfp]['paxReference']['traveller'][0]))
                                $paxReference           = $paxFareProduct[$pfp]['paxReference']['traveller'];
                            else
                                $paxReference[0]        = $paxFareProduct[$pfp]['paxReference']['traveller'];
                            $passengerType = $paxFareProduct[$pfp]['paxReference']['ptc'];
                            if($paxFareProduct[$pfp]['paxReference']['ptc']=='CNN' || $paxFareProduct[$pfp]['paxReference']['ptc']=='CH'){
                                $passengerType = 'CHD';
                            }
                           // $flightDetails[$refNumber][$price]['PriceInfo']['passengerType'] = $passengerType ;  
                            for($pr = 0; $pr < (count($paxReference)); $pr++) {
                                $flightDetails[$refNumber][$price]['PriceInfo']['PassengerFare'][$passengerType]['PassengerCount']       = ($pr+1);
                            }
                            
                           // $flightDetails[$refNumber][$price]['PriceInfo']['PassengerFare'][$passengerType]['totalFareAmount']         = $paxFareProduct[$pfp]['paxFareDetail']['totalFareAmount'];

                             $flightDetails[$refNumber][$price]['PriceInfo']['PassengerFare'][$passengerType]['BasePrice']         =( $paxFareProduct[$pfp]['paxFareDetail']['totalFareAmount'] - $paxFareProduct[$pfp]['paxFareDetail']['totalTaxAmount']);

                              $flightDetails[$refNumber][$price]['PriceInfo']['PassengerFare'][$passengerType]['Tax'] = $paxFareProduct[$pfp]['paxFareDetail']['totalTaxAmount'];
                              $flightDetails[$refNumber][$price]['PriceInfo']['PassengerFare'][$passengerType]['TotalPrice']  = $paxFareProduct[$pfp]['paxFareDetail']['totalFareAmount'];

                            //$flightDetails[$refNumber][$price]['PriceInfo']['PassengerFare'][$passengerType]['totalTaxAmount']          = $paxFareProduct[$pfp]['paxFareDetail']['totalTaxAmount'];
                            if(isset($paxFareProduct[$pfp]['paxFareDetail']['codeShareDetails']['transportStageQualifier'])){
                                $flightDetails[$refNumber][$price]['PriceInfo']['PassengerFare'][$passengerType]['transportStageQualifier'] = $paxFareProduct[$pfp]['paxFareDetail']['codeShareDetails']['transportStageQualifier'];
                            }else{
                                $flightDetails[$refNumber][$price]['PriceInfo']['PassengerFare'][$passengerType]['transportStageQualifier'] = '';
                            }
                            if(isset($paxFareProduct[$pfp]['paxFareDetail']['codeShareDetails']['company'])){
                                $flightDetails[$refNumber][$price]['PriceInfo']['PassengerFare'][$passengerType]['company']                 = $paxFareProduct[$pfp]['paxFareDetail']['codeShareDetails']['company'];
                            }else{
                                $flightDetails[$refNumber][$price]['PriceInfo']['PassengerFare'][$passengerType]['company']                 = '';
                            }
                                
                        
                          $fare = array();
                            if(isset($paxFareProduct[$pfp]['fare'][0]))
                                $fare           = $paxFareProduct[$pfp]['fare'];
                            else
                                $fare[0]        = $paxFareProduct[$pfp]['fare'];
                            
                            for($fa = 0; $fa < (count($fare)); $fa++) {
                                $flightDetails[$refNumber][$price]['PriceInfo']['PassengerFare'][$passengerType]['fare'][$fa]['description'] = '';  
                                $flightDetails[$refNumber][$price]['PriceInfo']['PassengerFare'][$passengerType]['fare'][$fa]['textSubjectQualifier']   = $fare[$fa]['pricingMessage']['freeTextQualification']['textSubjectQualifier'];
                                $flightDetails[$refNumber][$price]['PriceInfo']['PassengerFare'][$passengerType]['fare'][$fa]['informationType']        = $fare[$fa]['pricingMessage']['freeTextQualification']['informationType'];
                                $description = array();
                                if(is_array($fare[$fa]['pricingMessage']['description']))
                                    $description            = $fare[$fa]['pricingMessage']['description'];
                                else
                                    $description[0]         = $fare[$fa]['pricingMessage']['description'];
                                $flightDetails[$refNumber][$price]['PriceInfo']['fare'][$fa]['description'] = ''    ;
                                for ($d = 0; $d < count($description); $d++) {
                                    if(isset($description[$d]))
                                        $flightDetails[$refNumber][$price]['PriceInfo']['PassengerFare'][$passengerType]['fare'][$fa]['description'] .= $description[$d] . " - ";
                                }
                            }
                            $fareDetails = array();
                            if(isset($paxFareProduct[$pfp]['fareDetails'][0]))
                                $fareDetails            = $paxFareProduct[$pfp]['fareDetails'];
                            else
                                $fareDetails[0]         = $paxFareProduct[$pfp]['fareDetails'];
                            for($fd = 0; $fd < (count($fareDetails)); $fd++) 
                            {
                                $flightDetails[$refNumber][$price]['PriceInfo']['fareDetails'][$fd]['flightMtkSegRef']              = $fareDetails[$fd]['segmentRef']['segRef'];
                                $flightDetails[$refNumber][$price]['PriceInfo']['fareDetails'][$fd]['designator']           = $fareDetails[$fd]['majCabin']['bookingClassDetails']['designator'];                                   
                                $groupOfFares = array();
                                if(isset($fareDetails[$fd]['groupOfFares'][0]))
                                    $groupOfFares           = $fareDetails[$fd]['groupOfFares'];
                                else
                                    $groupOfFares[0]        = $fareDetails[$fd]['groupOfFares'];
                                for($gf = 0; $gf < (count($groupOfFares)); $gf++) 
                                {
                                    $flightDetails[$refNumber][$price]['PriceInfo']['fareDetails'][$fd]['rbd'][$gf]         = $groupOfFares[$gf]['productInformation']['cabinProduct']['rbd'];
                                    $flightDetails[$refNumber][$price]['PriceInfo']['fareDetails'][$fd]['cabin'][$gf]       = $groupOfFares[$gf]['productInformation']['cabinProduct']['cabin'];
                                    $flightDetails[$refNumber][$price]['PriceInfo']['fareDetails'][$fd]['avlStatus'][$gf]   = $groupOfFares[$gf]['productInformation']['cabinProduct']['avlStatus'];
                                    $flightDetails[$refNumber][$price]['PriceInfo']['fareDetails'][$fd]['breakPoint'][$gf]  = $groupOfFares[$gf]['productInformation']['breakPoint'];
                                    $flightDetails[$refNumber][$price]['PriceInfo']['fareDetails'][$fd]['fareType'][$gf]    = $groupOfFares[$gf]['productInformation']['fareProductDetail']['fareType'];

                                     $flightDetails[$refNumber][$price]['PriceInfo']['fareDetails'][$fd]['fareBasis'][$gf] = $groupOfFares[$gf]['productInformation']['fareProductDetail']['fareBasis'];


                                }
                            }
                        }
                }
            }
        }
        #debug($flightDetails);exit;
        $flight_list = array();
        $flightDetails1  =array();
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
                foreach ($FlightSegment as $s_key => $s_value) {
                    
                    $seg_value = $s_value['flightInformation'];
                    $Flight_SegDetails[$s_key]['Origin']['AirportCode'] = $seg_value['location'][0]['locationId'];
                    $Flight_SegDetails[$s_key]['Origin']['CityName'] =  $this->get_airport_city ($seg_value['location'][0]['locationId'] );
                    $Flight_SegDetails[$s_key]['Origin']['AirportName'] =  $this->get_airport_city ($seg_value['location'][0]['locationId'] );                   


                    $departureDate = $seg_value['productDateTime']['dateOfDeparture'];
                    $departureTime = $seg_value['productDateTime']['timeOfDeparture'];                    
                    $departure_date =  ((substr("$departureDate", 0, -4)) . "-" . (substr("$departureDate", -4, 2)) . "-20" . (substr("$departureDate", -2)));
                    $departure_time = ((substr("$departureTime", 0, -2)) . ":" . (substr("$departureTime", -2)));                 
                    $DapartureDate = $departure_date.' '.$departure_time;
                    
                    $d_time = date('H:i',strtotime($departureDate));
                    $Flight_SegDetails[$s_key]['Origin']['DateTime'] = date('Y-m-d H:i:s',strtotime($DapartureDate));
                    $Flight_SegDetails[$s_key]['Origin']['FDTV'] = strtotime($d_time);
                    $arrivalDate = $seg_value['productDateTime']['dateOfArrival'];
                    $arrivalTime = $seg_value['productDateTime']['timeOfArrival'];
                    $arrival_date =  ((substr("$arrivalDate", 0, -4)) . "-" . (substr("$arrivalDate", -4, 2)) . "-20" . (substr("$arrivalDate", -2)));
                    $arrival_time = ((substr("$arrivalTime", 0, -2)) . ":" . (substr("$arrivalTime", -2)));
                    $Flight_SegDetails[$s_key]['Destination']['AirportCode'] = $seg_value['location'][1]['locationId'];

                    $Flight_SegDetails[$s_key]['Destination']['CityName'] =  $this->get_airport_city ($seg_value['location'][1]['locationId'] );
                    $Flight_SegDetails[$s_key]['Destination']['AirportName'] =  $this->get_airport_city ($seg_value['location'][1]['locationId'] );

                   
                    $ArrivalDate = $arrival_date.' '.$arrival_time;
                    $a_time = date('H:i',strtotime($ArrivalDate));
                    $Flight_SegDetails[$s_key]['Destination']['DateTime'] = date('Y-m-d H:i:s',strtotime($ArrivalDate));
                    $Flight_SegDetails[$s_key]['Destination']['FATV'] = strtotime($a_time);                 
                    $Flight_SegDetails[$s_key]['OperatorCode'] = $seg_value['companyId']['marketingCarrier'];
                    $Flight_SegDetails[$s_key]['CabinClass'] = '';
                    $Flight_SegDetails[$s_key]['DisplayOperatorCode'] =  $seg_value['companyId']['marketingCarrier'];
                    $Flight_SegDetails[$s_key]['Duration'] = '';
                    $Flight_SegDetails[$s_key]['FlightNumber'] = $seg_value['flightOrtrainNumber'];
                    $Flight_SegDetails[$s_key]['OperatorName'] = $this->get_airline_name($seg_value['companyId']['marketingCarrier']);                   
                    $Flight_SegDetails[$s_key]['Attr']['AvailableSeats']= '';
                    $Flight_SegDetails[$s_key]['Attr']['Baggage']= '';
                    $Flight_SegDetails[$s_key]['Attr']['CabinBaggage']= '';
                    
                    $flightDetails1[$flight_id]['Details'][$result_k] = $Flight_SegDetails;
                    //$flightDetails1[$flight_id]['Details'][$result_k]['FlightId'] = $flight_id; 

                }
               
                // $Flight_SegDetails_arr[$f_key]['FlightDetails']['Details'][$result_k] = $Flight_SegDetails;
                // $Flight_SegDetails_arr[$flight_id]['Price'] = array();
                // $Flight_SegDetails_arr[$flight_id]['ResultToken'] = '';
                // $Flight_SegDetails_arr[$flight_id]['Attr'] = array();

            }
           # debug($Flight_SegDetails_arr);exit;
        }
        
        $x=0;
         foreach ($Recommedation as $p => $s) {
                if(isset($s['itemNumber']['itemNumberId']['numberType'])) { $price = $p;$flag = "MTK"; }else{ $price = 0;$flag = "Normal"; }
                $segmentFlightRef = array();
                if(isset($s['segmentFlightRef'][0]))
                    $segmentFlightRef           = $s['segmentFlightRef'];
                else
                    $segmentFlightRef[0]        = $s['segmentFlightRef'];
                for ($sfr = 0; $sfr <  (count($segmentFlightRef)); $sfr++) {


                    $referencingDetail = array();
                    if(isset($segmentFlightRef[$sfr]['referencingDetail'][0]))
                        $referencingDetail      = $segmentFlightRef[$sfr]['referencingDetail'];
                    else
                        $referencingDetail[0]   = $segmentFlightRef[$sfr]['referencingDetail'];
                  
                    for ($rd = 0; $rd < (count($referencingDetail)); $rd++) {
                        $refNumber              = $referencingDetail[$rd]['refNumber']."-".$flag."-".$p;
                        $refNumberFlight        = $referencingDetail[$rd]['refNumber'];
                        $refQualifier           = $referencingDetail[$rd]['refQualifier'];
                        
                       // $FinalResult[$x]['FlightDetailsID']     = $x;
                        if(isset($flightDetails1[$refNumberFlight]['Details'][$rd])){
                            $FinalResult[$x]['FlightDetails']['Details'][$rd]  = $flightDetails1[$refNumberFlight]['Details'][$rd];    
                        }
                        
                    }                  
                    
                    $priceDetailsfinal = array();
                    foreach($flightDetails[$refNumber] as $price)
                        $priceDetailsfinal[] = $price;
                                       
                   #debug($priceDetailsfinal);exit;
                   foreach ($priceDetailsfinal[0]['PriceInfo']['fareDetails'] as $c_key => $c_value) {
                        foreach ($c_value['cabin'] as $cc_key => $cc_value) {
                            $FinalResult[$x]['FlightDetails']['Details'][$c_key][$cc_key]['CabinClass'] = $cc_value;
                             $FinalResult[$x]['FlightDetails']['Details'][$c_key][$cc_key]['Attr']['AvailableSeats']=$c_value['avlStatus'][$cc_key];

                         }

                   } 

                    // debug($priceDetailsfinal);exit;
                    // debug($FinalResult[$x]['FlightDetails']['Details'][$x]);                                   
                    $FinalResult[$x]['Price']['PassengerBreakup']      = $priceDetailsfinal[0]['PriceInfo']['PassengerFare'];

                    $FinalResult[$x]['Price']['Currency'] = $currency;
                    $FinalResult[$x]['Price']['TotalDisplayFare'] =  $priceDetailsfinal[0]['PriceInfo']['totalFareAmount'];
                    $FinalResult[$x]['Price']['PriceBreakup']['BasicFare'] =  ($priceDetailsfinal[0]['PriceInfo']['totalFareAmount']- $priceDetailsfinal[0]['PriceInfo']['totalTaxAmount']);
                    $FinalResult[$x]['Price']['PriceBreakup']['Tax'] = $priceDetailsfinal[0]['PriceInfo']['totalTaxAmount'];
                    $FinalResult[$x]['Price']['PriceBreakup']['AgentCommission'] = 0;
                    $FinalResult[$x]['Price']['PriceBreakup']['AgentTdsOnCommision'] = 0;
                       
                    if (!isset($s['paxFareProduct'][0])) {
                        $paxFareProduct[0] = $s['paxFareProduct'];
                    } else {
                        $paxFareProduct = $s['paxFareProduct'];
                    }
                    $is_Refund_text = 'Non Refundable';

                    if(isset($paxFareProduct[0]['fare'][0]['pricingMessage']['description'])){
                        if(is_array($paxFareProduct[0]['fare'][0]['pricingMessage']['description'])){
                            $is_Refund_text = implode("",$paxFareProduct[0]['fare'][0]['pricingMessage']['description']);
                        }else{
                            $is_Refund_text = $paxFareProduct[0]['fare'][0]['pricingMessage']['description'];
                        }
                    }
                    if(strpos(strtolower($is_Refund_text),'non')==false){
                         $FinalResult[$x]['Attr']['IsRefundable'] = 1;
                    }else{
                         $FinalResult[$x]['Attr']['IsRefundable'] = 0;
                    }

                    $FinalResult[$x]['Attr']['AirlineRemark'] = $is_Refund_text;
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
                    $specificRecDetails ='';   
                     if (!empty($s['specificRecDetails'])){
                        if (!empty($s['specificRecDetails'][0]['specificProductDetails'][0])){
                            $specificRecDetails = $s['specificRecDetails']['0']['specificProductDetails'][0]['fareContextDetails']['cnxContextDetails'][0]['fareCnxInfo']['contextDetails']['availabilityCnxType'];
                        } else {
                            $specificRecDetails = $s['specificRecDetails']['0']['specificProductDetails']['fareContextDetails']['cnxContextDetails'][0]['fareCnxInfo']['contextDetails']['availabilityCnxType'];
                        }                       
                    }
                     $FinalResult[$x]['specificRecDetails'] = $specificRecDetails;
                    $key['key'][$x]['FlightInfo'] = $FinalResult[$x];
                    $ResultToken = serialized_data($key['key']);
                    $FinalResult[$x]['ResultToken'] = $ResultToken;
                    $FinalResult[$x]['booking_source'] =$this->booking_source;
                    $x++;
                }
            }  
           # debug($FinalResult);exit;
            $response['FlightDataList']['JourneyList'][0] = $FinalResult;
         # debug($response);exit;
        return $response;       

    }
    /*Calculating Agent Commission For Sabre*/
    private function Calculate_AgentCommission($search_response){
        // debug($search_response);exit;
        foreach($search_response as $key => $response){
            debug($response);exit;
            if(isset($response['Publish_Price'])){

                $agent_commission = $response['Publish_Price']['TotalDisplayFare'] - $response['Private_Price']['TotalDisplayFare'];
                $AgentCommission = roundoff_number($agent_commission, 2);
                $AgentTdsOnCommision = roundoff_number((($agent_commission * 5) / 100), 2); //Calculate it from currency library
                // echo 'herre'.$AgentCommission;
                $search_response[$key ]['Price']['PriceBreakup']['AgentCommission'] = $AgentCommission;
                $search_response[$key ]['Price']['PriceBreakup']['AgentTdsOnCommision'] = $AgentTdsOnCommision;
                unset($search_response[$key]['Publish_Price']);
                unset($search_response[$key]['Private_Price']);
            }
            else{
               $search_response[$key ]['Price']['PriceBreakup']['AgentCommission'] = 0;
               $search_response[$key ]['Price']['PriceBreakup']['AgentTdsOnCommision'] = 0;
            }
        }
        // exit;
        
        return $search_response;
    }
     /**
     * Fare Rule
     * @param unknown_type $request
     */
    public function get_fare_rules($request) {
        $response ['status'] = FAILURE_STATUS; // Status Of Operation
        $response ['message'] = ''; // Message to be returned
        $response ['data'] = array(); // Data to be returned
       # debug($request);exit;
        $fare_rule_request = $this->fare_rule_request($request);
        #debug($fare_rule_request);exit;
        if ($fare_rule_request['status'] == SUCCESS_STATUS) {
            $fare_rule_response = $this->process_request($fare_rule_request ['request'], $fare_rule_request ['url'], $fare_rule_request ['remarks'],$fare_rule_request['soap_url']);

           // $fare_rule_response = file_get_contents(FCPATH."fare_rule_response.xml");
            $fare_rule_xml_data = array();
            if($fare_rule_response){
                $fare_rule_xml_data = $this->xml2array($fare_rule_response);                    
            }
           
            $Rules = '';
            #debug($fare_rule_xml_data);exit;
            if ($fare_rule_xml_data && !isset($fare_rule_xml_data['soapenv:Envelope']['soapenv:Body']['Fare_GetFareRulesReply']['errorInfo']) && isset($fare_rule_xml_data['soapenv:Envelope']['soapenv:Body']['Fare_GetFareRulesReply'])) {
                    $fare_ruleResult1 = array();
                    $fare_ruleResult = $fare_rule_xml_data['soapenv:Envelope']['soapenv:Body']['Fare_GetFareRulesReply'];
                     foreach($fare_ruleResult['tariffInfo'] as $key=>$val){
                            $index_name = explode('.',$val['fareRuleText'][0]['freeText'])[1];

                            $fare_ruleResult1[$index_name] = '';
                            foreach($val['fareRuleText'] as $text_key=>$text_val){
                                $fare_ruleResult1[$index_name] .= '';
                                if($text_key == 0)
                                    continue;
                                if(empty($text_val['freeText']))
                                    continue;
                               
                                $fare_ruleResult1[$index_name] .= trim($text_val['freeText']);
                            }
                    }
                    if(valid_array($fare_ruleResult1)){
                        foreach ($fare_ruleResult1 as $i_key => $i_value) {
                           
                           $Rules .= ' '.$i_key.' \n============================\n '.$i_value.'============================\n ';
                        }
                    }
                    
                    
                if(!empty($Rules)){
                    $fareResp1[0]['FareRules'] = $Rules;
                    $fareResp1[0]['Origin'] = $fare_rule_request['fare_rule_request']['origin'];
                    $fareResp1[0]['Destination'] = $fare_rule_request['fare_rule_request']['destination'];
                    $fareResp1[0]['Airline'] = $fare_rule_request['fare_rule_request']['carrier'];
                    $response ['data']['FareRuleDetail'] = $fareResp1;
                    $response['status'] = SUCCESS_STATUS;
                }
                else{
                    $response['status'] = FAILURE_STATUS;
                }
            }
            else{
              $response['status'] = FAILURE_STATUS;
            }
        } else {
            $response ['status'] = FAILURE_STATUS;
        }
       # debug($response);exit;
        // debug($Responsense);exit;
        return $response;
    }
     /**
     * Forms the fare rule request
     * @param unknown_type $request
     */
    private function fare_rule_request($params) {
        
         $request = array();
         $search_request = array();
         $search_request['origin'] = $params['SearchOrigin'];
         $search_request['destination']= $params['SearchDestination'];
         $farebasis = $params['FareBreakdown'][0]['PriceInfo']['fareDetails'][0]['fareBasis'][0];

         $search_request['farebasis']= $farebasis;
         $search_request['carrier']=  $params['FlightInfo']['FlightDetails']['Details'][0][0]['OperatorCode'];
         $search_request['date'] =date('dmy');
         #debug($search_request);exit;
        $soapAction = "FARRNQ_10_1_1A";
        $xml_query='
                <?xml version="1.0" encoding="UTF-8"?>           
                <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:sec="http://xml.amadeus.com/2010/06/Security_v1" xmlns:typ="http://xml.amadeus.com/2010/06/Type">
                <soapenv:Header>
                <add:MessageID xmlns:add="http://www.w3.org/2005/08/addressing">'.$this->getuuid().'</add:MessageID>
                <add:Action xmlns:add="http://www.w3.org/2005/08/addressing">http://webservices.amadeus.com/'.$soapAction.'</add:Action>
                <add:To xmlns:add="http://www.w3.org/2005/08/addressing">'.$this->api_url.'</add:To>
                <link:TransactionFlowLink xmlns:link="http://wsdl.amadeus.com/2010/06/ws/Link_v1"/>
                <oas:Security xmlns:oas="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
                <oas:UsernameToken oas1:Id="UsernameToken-1" xmlns:oas1="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
                <oas:Username>'.$this->username.'</oas:Username>
                <oas:Nonce EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary">'.$this->nonce.'</oas:Nonce>
                <oas:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest">'.$this->hashPwd.'</oas:Password>
                <oas1:Created>'.$this->created.'</oas1:Created>
            </oas:UsernameToken>
            </oas:Security>
            <AMA_SecurityHostedUser xmlns="http://xml.amadeus.com/2010/06/Security_v1">
            <UserID AgentDutyCode="'.$this->agent_duty_code.'" RequestorType="'.$this->requestor_type.'" PseudoCityCode="'.$this->pseudo_city_code.'" POS_Type="'.$this->pos_type.'"/>
            </AMA_SecurityHostedUser>
            </soapenv:Header>
            <soapenv:Body>';
        $xml_query .= '<Fare_GetFareRules xmlns="http://xml.amadeus.com/"'.$soapAction.' >
                        <msgType>
                            <messageFunctionDetails>
                            <messageFunction>FRN</messageFunction>
                            </messageFunctionDetails>
                        </msgType>
                        <pricingTickInfo>
                            <productDateTimeDetails>
                            <ticketingDate>'.$search_request['date'].'</ticketingDate>
                            </productDateTimeDetails>
                        </pricingTickInfo>
                        <flightQualification>
                            <additionalFareDetails>
                                <rateClass>'.$search_request['farebasis'].'</rateClass>
                            </additionalFareDetails>
                        </flightQualification>
                        <transportInformation>
                            <transportService>
                                <companyIdentification>
                                    <marketingCompany>'.$search_request['carrier'].'</marketingCompany>
                                </companyIdentification>
                            </transportService>
                        </transportInformation>
                        <tripDescription>
                            <origDest>
                                <origin>'.$search_request['origin'].'</origin>
                                <destination>'.$search_request['destination'].'</destination>
                            </origDest>
                        </tripDescription>
                    </Fare_GetFareRules>';

         $xml_query .= '
        </soapenv:Body>
        </soapenv:Envelope>';
       
        $request ['request'] = $xml_query;
        $request ['url'] = $this->api_url;
        $request['fare_rule_request'] = $search_request;
        $request ['soap_url'] = $this->soap_url.$soapAction;
        $request ['remarks'] = 'FareRule(Amadeus)';
        $request ['status'] = SUCCESS_STATUS;
        // debug($request);exit;
        return $request;
    }
    /**
     * get fare rules request
     * @param $data_key     data to be used in the result index - comes from search result
     * @param $search_key   session id of the search  -  session identifies each search
     */
    function ota_fare_details_request($params,$fare_basic_code)
    {
        $DepartureAirportCode   = $params['org_airportcode'];
        $ArrivalAirportCode   = $params['dest_airportcode'];
        $DepartureDate      = date('m-d',strtotime($params['depature_date']));
        $MarketingAirlineCode   = $params['marketing_airline'];
        $request_params = 
            "<?xml version='1.0' encoding='utf-8'?>
            <soap-env:Envelope xmlns:soap-env='http://schemas.xmlsoap.org/soap/envelope/'>
              <soap-env:Header>
                <eb:MessageHeader
                  xmlns:eb='http://www.ebxml.org/namespaces/messageHeader'>
                  <eb:From>
                    <eb:PartyId eb:type='urn:x12.org.IO5:01'>".$this->config['sabre_email']."</eb:PartyId>
                  </eb:From>
                  <eb:To>
                    <eb:PartyId eb:type='urn:x12.org.IO5:01'>webservices.sabre.com</eb:PartyId>
                  </eb:To>
                  <eb:ConversationId>".$this->conversation_id."</eb:ConversationId>
                  <eb:Service eb:type='OTA'>OTA_AirRulesLLSRQ</eb:Service>
                  <eb:Action>OTA_AirRulesLLSRQ</eb:Action>
                  <eb:CPAID>".$this->config['ipcc']."</eb:CPAID>
                  <eb:MessageData>
                    <eb:MessageId>".$this->message_id."</eb:MessageId>
                    <eb:Timestamp>".$this->timestamp."</eb:Timestamp>
                    <eb:TimeToLive>".$this->timetolive."</eb:TimeToLive>
                  </eb:MessageData>
                </eb:MessageHeader>
                <wsse:Security xmlns:wsse='http://schemas.xmlsoap.org/ws/2002/12/secext'>
                  <wsse:UsernameToken>
                    <wsse:Username>".$this->config['username']."</wsse:Username>
                    <wsse:Password>".$this->config['password']."</wsse:Password>
                    <Organization>".$this->config['ipcc']."</Organization>
                    <Domain>Default</Domain>
                  </wsse:UsernameToken>
                  <wsse:BinarySecurityToken>".$this->api_session_id."</wsse:BinarySecurityToken>
                </wsse:Security>
              </soap-env:Header>
              <soap-env:Body>
                <OTA_AirRulesRQ xmlns='http://webservices.sabre.com/sabreXML/2011/10' xmlns:xs='http://www.w3.org/2001/XMLSchema' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' ReturnHostCommand='true' Version='2.2.0'>
                <OriginDestinationInformation>
                  <FlightSegment DepartureDateTime='" . $DepartureDate . "'>
                    <DestinationLocation LocationCode='".$ArrivalAirportCode."'/>
                    <MarketingCarrier Code='" . $MarketingAirlineCode . "'/>
                    <OriginLocation LocationCode='".$DepartureAirportCode."'/>
                  </FlightSegment>
                </OriginDestinationInformation>
                <RuleReqInfo>
                  <FareBasis Code='" . $fare_basic_code ."'/>
                </RuleReqInfo>
                </OTA_AirRulesRQ>
              </soap-env:Body>
            </soap-env:Envelope>";
          
        $request ['request'] = $request_params;
        $request ['url'] = $this->config['api_url'];
        $request ['remarks'] = 'OtaFareRule(Sabare)';
        $request ['status'] = SUCCESS_STATUS;
        // debug($request);exit;
        return $request;
    }
     /**
     * Update Fare Quote
     * @param unknown_type $request
     */

    public function get_update_fare_quote($fare_search_data, $search_id) {
       # debug($fare_search_data);exit;
        $response ['status'] = FAILURE_STATUS; // Status Of Operation
        $response ['message'] = ''; // Message to be returned
        $response ['data'] = array(); // Data to be returned
        #debug($fare_search_data);exit;
        if (valid_array($fare_search_data)) {
            
            if (valid_array($fare_search_data) == true && isset($fare_search_data['FlightInfo']['FlightDetails']) == true) {
                $response ['status'] = SUCCESS_STATUS;
                $response ['data']['FareQuoteDetails']['JourneyList'][0][0] = $fare_search_data['FlightInfo'];
                $response ['data']['FareQuoteDetails']['JourneyList'][0][0]['HoldTicket'] =1; 
                $result_token[0]['booking_source'] = $fare_search_data['booking_source'];                
                 $response ['data']['FareQuoteDetails']['JourneyList'][0][0]['ResultToken'] =serialized_data($result_token);

                //$response ['data']['FareQuoteDetails']['JourneyList'][0][0]['HoldTicket'] =; 

                

            } else {
                $response ['message'] = 'Not Available';
            }
        } else {
            $response ['status'] = FAILURE_STATUS;
        }
        #debug($response);exit;
        return $response;
    }
  
     /*
     *
     * get airport city based on airport code
     */
    private function get_airport_city($airport_code) {
        $CI = & get_instance ();
        
        $airport_name = $CI->db_cache_api->get_airport_city_name ( array (
                'airport_code' => $airport_code 
        ) );
        $airport_name = @$airport_name ['airport_city'];
        return ($airport_name);
    }
     /*
     *
     * get airline name based on airport code
     */
    private function get_airline_name($airline_code) {
        $CI = & get_instance ();
        
        $airline_data = $CI->db_cache_api->get_airline_name ( array (
                'code' => $airline_code 
        ) );
        $airline_name = ucfirst(strtolower(@$airline_data ['name']));
        return ($airline_name);
    }
     /**
     * check if the search RS is valid or not
     * @param array $search_result
     * search result RS to be validated
     */
    private function valid_search_result($search_result) {
       if(!isset($search_result['soapenv:Envelope']['soapenv:Body']['   Fare_MasterPricerTravelBoardSearchReply']['errorMessage'])){
            return true;
       }else{
            return false;
       }
    }
    public function get_search_class_code($class)
    {
        
        if ($class == 'economy') {
            $economyCode = 'Y';
        }
        elseif ($class == 'premiumeconomy') {
            $economyCode = 'S';
        }
        elseif ($class == 'business') {
            $economyCode = 'C';
        }
        elseif ($class == 'premiumbusiness') {
            $economyCode = 'J';
        }
        elseif ($class == 'first') {
            $economyCode = 'F';
        }
        elseif ($class == 'premiumfirst') {
            $economyCode = 'P';
        }
        else{
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
    function update_markup_currency(& $price_summary, & $currency_obj) {
        
    }
    /**
     * get total price from summary object
     * 
     * @param object $price_summary         
     */
    function total_price($price_summary) {
        
    }
     /**
     * Process booking
     * @param array $booking_params
     */
    function process_booking($booking_params,$app_reference,$sequence_number,$search_id){
        $response ['status'] = FAILURE_STATUS; // Status Of Operation
        $response ['message'] = ''; // Message to be returned
        $response ['data'] = array(); // Data to be returned
        $search_data = $this->search_data($search_id);        
        $AirSellRecommdation = $this->AirSellRecommdation($booking_params,$search_data['data']);  
        
        $JourneyList = $booking_params['flight_data'];
        $Price = $booking_params['flight_data']['PriceBreakup'];
        if($AirSellRecommdation['status']==SUCCESS_STATUS){//segment confirm need to save passenger info in PNR AddElement option 0         
            $PNR_AddMultiElements_Option0 = $this->PNR_AddMultiElements_0(0,'',$AirSellRecommdation['data'],$booking_params,array(),false,false);  
            if($PNR_AddMultiElements_Option0['status']==SUCCESS_STATUS){
                //checking flight class availablity
                $flight_marketing_carrier = $booking_params['flight_data']['FlightDetails']['Details'][0][0]['OperatorCode'];
                $PNR_AddMultiElements_BookingClass = $this->PNR_AddMultiElements_BookingClass($PNR_AddMultiElements_Option0['data'],$flight_marketing_carrier);
                if($PNR_AddMultiElements_BookingClass['status']==SUCCESS_STATUS){
                    $Ticket_CreateTSTFromPricing = $this->Ticket_CreateTSTFromPricing($search_data['data'],$PNR_AddMultiElements_BookingClass['data']);
                    if($Ticket_CreateTSTFromPricing['status']==SUCCESS_STATUS){
                        //sending fop 
                        $FOP = $this->FOP($booking_params,$flight_marketing_carrier,$Ticket_CreateTSTFromPricing['data']);
                        
                        if($FOP['status']==SUCCESS_STATUS){
                            $PNR_AddMultiElements_Option10 = $this->PNR_AddMultiElements_10($FOP['data'],$booking_params,$search_data['data']);
                           //booking confirmed
                           if($PNR_AddMultiElements_Option10['status']==SUCCESS_STATUS){

                                $response ['status'] = SUCCESS_STATUS;
                                //if required need to run pnr retrieve
                                //placing in queue
                                $Place_Queue = $this->Place_Queue($PNR_AddMultiElements_Option10['data']);
                               // sleep(10);// if we need direct ticketing need to wait 10 secondds

                                 //storing in database
                                $this->save_book_response_details($PNR_AddMultiElements_Option10['data']['Pnr_No'], $app_reference, $sequence_number);

                                $PNRRetrieve = $this->PNRRetrieve($PNR_AddMultiElements_Option10['data']);
                              
                                if($PNRRetrieve['status']==SUCCESS_STATUS){
                                    $DocIssurance_Issue_Ticket = $this->DocIssuance_IssueTicket($PNRRetrieve['data']);    
                                    
                                      // Save Ticket Details
                                     $this->save_flight_ticket_details($booking_params,$PNRRetrieve['data']['Pnr_No'], $app_reference, $sequence_number, $search_id);
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
    /*Booking Procedure starts*/
    private function AirSellRecommdation($booking_params,$search_data){
        $response['status'] = FAILURE_STATUS;
        $response['data'] = array();
        if(valid_array($booking_params['flight_data']['FlightDetails']['Details'])){
            $quantity = $search_data['adult_config']+$search_data['child_config'];
            $specificRecDetails = $booking_params['flight_data']['specificRecDetails'];
            $ite_query = '';
            $subsegment = '';
            $flight_identification ='';
            if($specificRecDetails){
                 $flight_identification  = ' <flightTypeDetails><flightIndicator>'.$specificRecDetails.'</flightIndicator></flightTypeDetails>';
            } 
            foreach ($booking_params['flight_data']['FlightDetails']['Details'] as $s_key => $s_value) { 

                $Origin = $s_value[0]['Origin']['AirportCode'];
                $Destination_details = end($s_value);
                $Destination = $Destination_details['Destination']['AirportCode'];
                    $ite_query .='<itineraryDetails><originDestinationDetails>
                                    <origin>'.$Origin.'</origin>
                                    <destination>'.$Destination.'</destination>
                                </originDestinationDetails>
                                <message>
                                    <messageFunctionDetails>
                                        <messageFunction>183</messageFunction>
                                    </messageFunctionDetails>
                                </message>';
                    //subsegment
                   $subsegment ='';
                   foreach ($s_value as $ss_key => $ss_value) {
                        
                       $subsegment .='<segmentInformation>
                                        <travelProductInformation>
                                            <flightDate>
                                                <departureDate>'.date('dmy',strtotime($ss_value['Origin']['DateTime'])).'</departureDate>
                                            </flightDate>
                                            <boardPointDetails>
                                                <trueLocationId>'.$ss_value['Origin']['AirportCode'].'</trueLocationId>
                                            </boardPointDetails>
                                            <offpointDetails>
                                                <trueLocationId>'.$ss_value['Destination']['AirportCode'].'</trueLocationId>
                                            </offpointDetails>
                                            <companyDetails>
                                                <marketingCompany>'.$ss_value['OperatorCode'].'</marketingCompany>
                                            </companyDetails>
                                            <flightIdentification>
                                                <flightNumber>'.$ss_value['FlightNumber'].'</flightNumber>
                                                <bookingClass>'.$ss_value['CabinClass'].'</bookingClass>
                                            </flightIdentification>
                                           '.$flight_identification.'
                                        </travelProductInformation>
                                        <relatedproductInformation>
                                            <quantity>'.$quantity.'</quantity>
                                            <statusCode>NN</statusCode>
                                        </relatedproductInformation>
                                    </segmentInformation>';
                   }

                 $ite_query .=$subsegment.'</itineraryDetails>';
                  
            }
            $SellRequest = '<?xml version="1.0" encoding="UTF-8"?>           
                    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:sec="http://xml.amadeus.com/2010/06/Security_v1" xmlns:typ="http://xml.amadeus.com/2010/06/Type">
                    <soapenv:Header>
                    <add:MessageID xmlns:add="http://www.w3.org/2005/08/addressing">'.$this->getuuid().'</add:MessageID>
                    <add:Action xmlns:add="http://www.w3.org/2005/08/addressing">http://webservices.amadeus.com/ITAREQ_05_2_IA</add:Action>
                    <add:To xmlns:add="http://www.w3.org/2005/08/addressing">'.$this->api_url.'</add:To>
                    <link:TransactionFlowLink xmlns:link="http://wsdl.amadeus.com/2010/06/ws/Link_v1"/>
                    <oas:Security xmlns:oas="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
                    <oas:UsernameToken oas1:Id="UsernameToken-1" xmlns:oas1="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
                    <oas:Username>'.$this->username.'</oas:Username>
                    <oas:Nonce EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary">'.$this->nonce.'</oas:Nonce>
                    <oas:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest">'.$this->hashPwd.'</oas:Password>
                    <oas1:Created>'.$this->created.'</oas1:Created>
                    </oas:UsernameToken>
                    </oas:Security>
                    <AMA_SecurityHostedUser xmlns="http://xml.amadeus.com/2010/06/Security_v1">
                    <UserID AgentDutyCode="'.$this->agent_duty_code.'" RequestorType="'.$this->requestor_type.'" PseudoCityCode="'.$this->pseudo_city_code.'" POS_Type="'.$this->pos_type.'"/>
                    </AMA_SecurityHostedUser>
                    <awsse:Session TransactionStatusCode="Start" xmlns:awsse="http://xml.amadeus.com/2010/06/Session_v3"/>
                    </soapenv:Header>
                    <soapenv:Body>
                    <itar:Air_SellFromRecommendation xmlns:itar="http://xml.amadeus.com/ITAREQ_05_2_IA">
                    <itar:messageActionDetails>
                    <itar:messageFunctionDetails>
                    <itar:messageFunction>183</itar:messageFunction>
                    <itar:additionalMessageFunction>M1</itar:additionalMessageFunction>
                    </itar:messageFunctionDetails>
                    </itar:messageActionDetails>
                    '.$ite_query.'
                    </Air_SellFromRecommendation>
                    </soapenv:Body>
                    </soapenv:Envelope>';
            $soapAction = "ITAREQ_05_2_IA"; 
            $api_url = $this->api_url;
            $soap_url = $this->soap_url.$soapAction;
            $remarks = 'AirSellRecommdation(Amadeus)';
            $airsell_response = $this->process_request($SellRequest,$api_url,$remarks,$soap_url);
           // $airsell_response  = file_get_contents(FCPATH.'air_sel_res.xml');
            $Airsell_Response = array();
            if($airsell_response){
                $Airsell_Response = $this->xml2array($airsell_response);
                
                $SecuritySession=$Airsell_Response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SessionId'];
               $SequenceNumber=$Airsell_Response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SequenceNumber'];
               $SequenceNumber = ($SequenceNumber+1);
                $SecurityToken=$Airsell_Response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SecurityToken'];                        
                if(isset($Airsell_Response['soapenv:Envelope']['soapenv:Body']['soap:Fault']) || isset($Airsell_Response['soapenv:Envelope']['soapenv:Body']['Fare_PricePNRWithBookingClassReply']['applicationError']) || isset($Airsell_Response['soap:Envelope']['soap:Body']['soap:Fault'])){

                   $this->Security_SignOut($SecuritySession,$SequenceNumber,$SecurityToken);
                }else{
                    //if not error checking the segment confirmation 
                    $segmentResult1 = array(); $segmentResult = array(); $status_msg = ""; $status_flag="";                  
                    $status = array();
                    if (isset($Airsell_Response['soapenv:Envelope']['soapenv:Body']['Air_SellFromRecommendationReply']['itineraryDetails'])) {
                        $segmentResult1 = $Airsell_Response['soapenv:Envelope']['soapenv:Body']['Air_SellFromRecommendationReply']['itineraryDetails'];
                        $segmentResult = force_multple_data_format($segmentResult1);
                        if(valid_array($segmentResult))
                        {
                            $flag = "TRUE";
                            for ($si = 0; $si < (count($segmentResult)); $si++) {
                                    $segmentInformation = array();
                                    if(!isset($segmentResult[$si]['segmentInformation'][0]))
                                        $segmentInformation[0] = $segmentResult[$si]['segmentInformation'];
                                    else
                                        $segmentInformation = $segmentResult[$si]['segmentInformation'];    
                                            
                                    for ($s = 0; $s < (count($segmentInformation)); $s++) {
                                    $statusCode = $segmentInformation[$s]['actionDetails']['statusCode'];

                                    if($statusCode == "OK" )
                                    {
                                        $status[$si][$s] = "Sold";
                                        if($flag == "TRUE")
                                            $status_flag = "true";
                                        else
                                            $status_flag = "false";
                                    }
                                    else if($statusCode== "UNS")
                                    {
                                        $status[$si][$s] = "Unable to sell";
                                        $status_flag = "false"; $flag = "FALSE";
                                    }
                                    else if($statusCode== "WL")
                                    {
                                        $status[$si][$s] = "Wait listed";
                                        $status_flag = "false"; $flag = "FALSE";
                                    }
                                    else if($statusCode== "X")
                                    {
                                        $status[$si][$s] = "Cancelled after a successful sell";
                                        $status_flag = "false"; $flag = "FALSE";
                                    }
                                    else if($statusCode== "RQ")
                                    {
                                        $status[$si][$s] = "Sell was not even attempted";
                                        $status_flag = "false"; $flag = "FALSE";
                                    } 
                                }
                            }//close the segment checking
                        }
                        if($status_flag=="false"){
                            //if any one of segment not confirm need to close the session
                            $this->Security_SignOut($SecuritySession,$SequenceNumber,$SecurityToken);
                        }elseif($status_flag=="true"){
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
        if(valid_array($passenger_details)){
            foreach ($passenger_details as $p_key => $p_value) {
                if($p_key==0){
                    $contact_email = $p_value['Email'];
                    $contact_no= $p_value['ContactNo'];
                    $country_code = $p_value['CountryCode'];
                }                
                if($p_value['PaxType']==1){
                    $booking_passenger_arr['ADT'][] = $p_value;
                }elseif($p_value['PaxType']==2){
                    $booking_passenger_arr['CHD'][] = $p_value;
                }elseif($p_value['PaxType']==3){
                    $booking_passenger_arr['INF'][] = $p_value;
                }
            }
        }
        return array('pax'=>$booking_passenger_arr,'contact_email'=>$contact_email,'contact_no'=>$contact_no,'country_code'=>$country_code);
        
    }
    /**
    *Saving the info in PNR
    *@param $counter - 0  saving the passenger data in PNR,10 status of PNR,11 adding the meal to the PNR 
    *@param $parent_pnr - PNR Number from counter 1    
    */

    private function PNR_AddMultiElements_0($counter,$parent_pnr,$header_data,$booking_params,$seat_info=array(),$reconfirm_meal=false,$is_seat_req=false){
        
        $response['status']=FAILURE_STATUS;
        $response['data'] = array();
        $response['message'] = ''; 
        //PaxType 1 -adult,2-child,3 -infant
        $booking_passenger_arr = array();
        $country_code_num = '+1';
         $format_pax_data= $this->format_passenger_data($booking_params);
        $booking_passenger_arr = $format_pax_data['pax'];
        $contact_email = $format_pax_data['contact_email'];
        $contact_no= $format_pax_data['contact_no'];
        $country_code = $format_pax_data['country_code'];
        $country_code_data  = $this->CI->custom_db->single_table_records('api_country_list','*',array('iso_country_code'=>$country_code));
        if($country_code_data['status']==1){
            $country_code_num = $country_code_data['data'][0]['country_code'];    
        }        
       

        $traveller_info = '';
        $pax_count = 1;
        $seat_pt_count =2;
        $seat_format_xml='';
        //appending adult + infant
      
        foreach ($booking_passenger_arr['ADT'] as $key => $value) {
            $traveller_info .='<travellerInfo>
                                <elementManagementPassenger>
                                    <reference>
                                        <qualifier>PR</qualifier>
                                        <number>'.$pax_count.'</number>
                                    </reference>
                                    <segmentName>NM</segmentName>
                                </elementManagementPassenger>';
            if(isset($booking_passenger_arr['INF'][$key])){
                $traveller_info .='<passengerData>
                                        <travellerInformation>
                                            <traveller>
                                                <surname>'.$value['LastName'].'</surname>
                                                <quantity>2</quantity>
                                            </traveller>
                                            <passenger>
                                                <firstName>'.$value['FirstName'].'</firstName>
                                                <type>ADT</type>
                                                <infantIndicator>3</infantIndicator>
                                            </passenger>
                                        </travellerInformation>
                                    </passengerData>';           
                $traveller_info .='<passengerData>
                                <travellerInformation>
                                    <traveller>
                                        <surname>'.$booking_passenger_arr['INF'][$key]['LastName'].'</surname>
                                    </traveller>
                                <passenger>
                                    <firstName>'.$booking_passenger_arr['INF'][$key]['FirstName'].'</firstName>
                                    <type>INF</type>
                                </passenger>
                                </travellerInformation>
                                <dateOfBirth>
                                    <dateAndTimeDetails> 
                                        <date>'.date('dMy',strtotime($booking_passenger_arr['INF'][$key]['DateOfBirth'])).'</date>
                                    </dateAndTimeDetails>
                                </dateOfBirth>
                            </passengerData>';
            }else{
                $traveller_info .='<passengerData>
                                    <travellerInformation>
                                        <traveller>
                                            <surname>'.$value['LastName'].'</surname>
                                            <quantity>1</quantity>
                                        </traveller>
                                        <passenger>
                                            <firstName>'.$value['FirstName'].'</firstName>
                                            <type>ADT</type>
                                        </passenger>
                                    </travellerInformation>
                                </passengerData>';
            }
                                
            $traveller_info .='</travellerInfo>';           
            $pax_count++;

            //checking seat request
            if(isset($value['SeatId'])){
                if(valid_array($value['SeatId'])){
                    foreach ($value['SeatId'] as $s_key => $s_value) {
                        $seat_data = Common_Flight::read_record($s_value);
                        $seat_data = json_decode($seat_data[0], true);
                        $SeatIddata = array_values(unserialized_data($seat_data['SeatId']));
                        $Segment_Type = $SeatIddata[0]['Segment_Type'];
                        $seat_format_xml.='<dataElementsIndiv>
                    <elementManagementData>
                        <segmentName>STR</segmentName>
                    </elementManagementData>
                    <seatGroup>
                        <seatRequest>
                            <seat>
                                <type>RQST</type>
                            </seat>
                            <special>
                                <data>'.$seat_data['SeatNumber'].'</data>
                            </special>
                        </seatRequest>
                    </seatGroup>
                    <referenceForDataElement>
                        <reference>
                            <qualifier>PT</qualifier>
                            <number>' .($seat_pt_count). '</number>
                        </reference>
                        <reference>
                            <qualifier>ST</qualifier>
                            <number>'.$Segment_Type.'</number>
                        </reference>
                    </referenceForDataElement>
                </dataElementsIndiv>';
                    }
                }                
            }
            if(isset($booking_passenger_arr['INF'][$key])){
                $seat_pt_count = $seat_pt_count+2;
            }else{
                $seat_pt_count =$seat_pt_count+1;
            }
        }
       
        //creating child tag
        if(isset($booking_passenger_arr['CHD']) && valid_array($booking_passenger_arr['CHD'])){
            foreach ($booking_passenger_arr['CHD'] as $c_key => $c_value) {
               $traveller_info .='<travellerInfo>
                                    <elementManagementPassenger>
                                        <reference>
                                            <qualifier>PR</qualifier>
                                            <number>'.$pax_count.'</number>
                                        </reference>
                                    <segmentName>NM</segmentName>
                                    </elementManagementPassenger>
                                    <passengerData>
                                        <travellerInformation>
                                            <traveller>
                                                <surname>'.$c_value['LastName'].'</surname>
                                            </traveller>
                                            <passenger>
                                                <firstName>'.$c_value['FirstName'].'</firstName>
                                                <type>CHD</type>
                                            </passenger>
                                        </travellerInformation>
                                        <dateOfBirth>
                                            <dateAndTimeDetails>  
                                                <date>'.date('dMy',strtotime($c_value['DateOfBirth'])).'</date>
                                            </dateAndTimeDetails>
                                        </dateOfBirth>
                                    </passengerData>
                                </travellerInfo>';


                  if(isset($c_value['SeatId'])){
                    if(valid_array($c_value['SeatId'])){
                        foreach ($c_value['SeatId'] as $sc_key => $sc_value) {
                                $seat_data = Common_Flight::read_record($sc_value);
                                $seat_data = json_decode($seat_data[0], true);
                                $SeatIddata = array_values(unserialized_data($seat_data['SeatId']));
                                $Segment_Type = $SeatIddata[0]['Segment_Type'];

                                $seat_format_xml.='<dataElementsIndiv>
                                <elementManagementData>
                                    <segmentName>STR</segmentName>
                                </elementManagementData>
                                <seatGroup>
                                    <seatRequest>
                                        <seat>
                                            <type>RQST</type>
                                        </seat>
                                        <special>
                                            <data>'.$seat_data['SeatNumber'].'</data>
                                        </special>
                                    </seatRequest>
                                </seatGroup>
                                <referenceForDataElement>
                                    <reference>
                                        <qualifier>PT</qualifier>
                                        <number>' .($seat_pt_count). '</number>
                                    </reference>
                                    <reference>
                                        <qualifier>ST</qualifier>
                                        <number>'.($Segment_Type).'</number>
                                    </reference>
                                </referenceForDataElement>
                            </dataElementsIndiv>';
                              $seat_pt_count++;
                        }
                    }
                        
                }               

            }
        }
        if($traveller_info){
            $SecuritySession = $header_data['SessionId'];
            $SequenceNumber = $header_data['SequenceNumber'];
            $SecurityToken = $header_data['SecurityToken'];
             $soapAction = 'PNRADD_17_1_1A';
            $PNR_AddMultiElements = '';
            $PNR_AddMultiElements.= '<?xml version="1.0" encoding="utf-8"?>
                        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
                        <soapenv:Header>
                            <awsse:Session TransactionStatusCode="InSeries" xmlns:awsse="http://xml.amadeus.com/2010/06/Session_v3">
                                <awsse:SessionId>' . $SecuritySession . '</awsse:SessionId>
                                <awsse:SequenceNumber>' . $SequenceNumber . '</awsse:SequenceNumber>
                                <awsse:SecurityToken>' . $SecurityToken . '</awsse:SecurityToken>
                            </awsse:Session>
                            <add:MessageID xmlns:add="http://www.w3.org/2005/08/addressing">' .$this-> getuuid() . '</add:MessageID>
                            <add:Action xmlns:add="http://www.w3.org/2005/08/addressing">http://webservices.amadeus.com/'.$soapAction.'</add:Action>
                            <add:To xmlns:add="http://www.w3.org/2005/08/addressing">'.$this->api_url.'</add:To>
                        </soapenv:Header>
                        <soapenv:Body>
                            <PNR_AddMultiElements xmlns="http://xml.amadeus.com/'.$soapAction.'" >
                            ';
            $PNR_AddMultiElements.= '
                   <pnrActions>
                        <optionCode>0</optionCode>
                    </pnrActions>
                     '.$traveller_info;
            $PNR_AddMultiElements.= '
                    <dataElementsMaster>
                    <marker1/>
                    '.$seat_format_xml.'                   
                    <dataElementsIndiv>
                        <elementManagementData>
                            <reference>
                                <qualifier>OT</qualifier>
                                <number>2</number>
                            </reference>
                            <segmentName>RF</segmentName>
                        </elementManagementData>
                        <freetextData>
                            <freetextDetail>
                                <subjectQualifier>3</subjectQualifier>
                                <type>P23</type>
                            </freetextDetail>
                            <longFreetext>Example</longFreetext>
                        </freetextData>
                    </dataElementsIndiv>
                    <dataElementsIndiv>
                         <elementManagementData>
                            <segmentName>TK</segmentName>
                         </elementManagementData>
                         <ticketElement>
                            <ticket>
                               <indicator>TL</indicator>
                               <date>'.date('dmy',strtotime("+1 Year")).'</date>
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
                            <longFreetext>' .$contact_email. '</longFreetext>
                        </freetextData>
                    </dataElementsIndiv>                    
                    <dataElementsIndiv>
                        <elementManagementData>
                            <reference>
                                <qualifier>OT</qualifier>
                                <number>2</number>
                            </reference>
                            <segmentName>AP</segmentName>
                        </elementManagementData>
                        <freetextData>
                            <freetextDetail>
                                <subjectQualifier>3</subjectQualifier>
                                <type>7</type>
                            </freetextDetail>
                            <longFreetext>'.$country_code_num.$contact_no. '</longFreetext>
                        </freetextData>
                    </dataElementsIndiv>
                </dataElementsMaster>
            </PNR_AddMultiElements>
            </soapenv:Body>
            </soapenv:Envelope>';

            $api_url = $this->api_url;
            $soap_url = $this->soap_url.$soapAction;
            $remarks = 'PNR_AddMultiElements_Option0(amadeus)';

            $PNR_AddElements_Response = $this->process_request($PNR_AddMultiElements,$api_url,$remarks,$soap_url);
           // $PNR_AddElements_Response  = file_get_contents(FCPATH.'pnr_0.xml');
            $PNR_AddElements_Response_arr = array();
            if($PNR_AddElements_Response){
                $PNR_AddElements_Response_arr = $this->xml2array($PNR_AddElements_Response);

                $SessionId = $PNR_AddElements_Response_arr['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SessionId'];
                $SecurityToken = $PNR_AddElements_Response_arr['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SecurityToken'];
                $SequenceNumber = $PNR_AddElements_Response_arr['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SequenceNumber'];
                $SequenceNumber  = ($SequenceNumber +1);
               if(isset($PNR_AddElements_Response_arr['soapenv:Envelope']['soapenv:Body']['soap:Fault']) || isset($PNR_AddElements_Response_arr['soapenv:Envelope']['soapenv:Body']['PNR_Reply']['applicationError']) || isset($PNR_AddElements_Response_arr['soap:Envelope']['soap:Body']['soap:Fault'])){
                    $this->Security_SignOut($SessionId,$SequenceNumber,$SecurityToken);
               }elseif(isset($PNR_AddElements_Response_arr['soapenv:Envelope']['soapenv:Body']['PNR_Reply']['pnrHeader'])){

                //if success
                    $response['status'] = SUCCESS_STATUS;
                    $response['message'] = 'success';
                    $response['data']['SessionId'] =$SessionId;
                    $response['data']['SecurityToken'] =$SecurityToken;
                    $response['data']['SequenceNumber'] = $SequenceNumber;
               }
                
            }
        }
        return $response;

    }
    /*PNR_AddMultiElements_BookingClass checking flight marketing carrier 9w*/
    private function PNR_AddMultiElements_BookingClass($header_data,$carrier){
        $response['status'] = FAILURE_STATUS;
        $response['data'] = array();
        $response['message'] = '';
        $soapAction =trim('TPCBRQ_18_1_1A');
        $SessionId = $header_data['SessionId'];
        $SecurityToken = $header_data['SecurityToken'];
        $SequenceNumber = $header_data['SequenceNumber'];
        $xml_query='
            <?xml version="1.0" encoding="utf-8"?>
                <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
                <soapenv:Header>
                <awsse:Session TransactionStatusCode="InSeries" xmlns:awsse="http://xml.amadeus.com/2010/06/Session_v3">
                <awsse:SessionId>'.trim($SessionId).'</awsse:SessionId>
                <awsse:SequenceNumber>'.trim($header_data['SequenceNumber']).'</awsse:SequenceNumber>
                <awsse:SecurityToken>'.trim($header_data['SecurityToken']).'</awsse:SecurityToken>
            </awsse:Session>
            <add:MessageID xmlns:add="http://www.w3.org/2005/08/addressing">'.$this->getuuid().'</add:MessageID>
            <add:Action xmlns:add="http://www.w3.org/2005/08/addressing">http://webservices.amadeus.com/'.$soapAction.'</add:Action>
            <add:To xmlns:add="http://www.w3.org/2005/08/addressing">'.$this->api_url.'</add:To>
            </soapenv:Header>
            <soapenv:Body>
            <tpc:Fare_PricePNRWithBookingClass xmlns:tpc="http://xml.amadeus.com/'.$soapAction.'" >
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
                <tpc:otherCompany>'.$carrier.'</tpc:otherCompany>
                </tpc:companyIdentification>
                </tpc:carrierInformation>
                </tpc:pricingOptionGroup>
             <tpc:pricingOptionGroup>
            <tpc:pricingOptionKey>
            <tpc:pricingOptionKey>FCO</tpc:pricingOptionKey>
            </tpc:pricingOptionKey>
            <tpc:currency>
            <tpc:firstCurrencyDetails>
            <tpc:currencyQualifier>FCO</tpc:currencyQualifier>
            <tpc:currencyIsoCode>USD</tpc:currencyIsoCode>
            </tpc:firstCurrencyDetails>
            </tpc:currency>
            </tpc:pricingOptionGroup>
            </tpc:Fare_PricePNRWithBookingClass>
            </soapenv:Body>
            </soapenv:Envelope>';

        if($xml_query){
            $soap_url = $this->soap_url.$soapAction;
            $remarks = 'PNR_AddMultiElements_BookingClass(amadeus)';
            $PNR_Booking_Class = $this->process_request(trim($xml_query),$this->api_url,$remarks,$soap_url);
            //$PNR_Booking_Class  = file_get_contents(FCPATH.'fare_class.xml');
            $PNR_Booking_Class_arr = array();
            if($PNR_Booking_Class){
                $PNR_Booking_Class_arr = $this->xml2array($PNR_Booking_Class);
                if(isset($PNR_Booking_Class_arr['soapenv:Envelope']['soapenv:Header'])){

                    $SessionId = $PNR_Booking_Class_arr['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SessionId'];    
                    $SecurityToken = $PNR_Booking_Class_arr['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SecurityToken'];
                    $SequenceNumber =  $PNR_Booking_Class_arr['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SequenceNumber'];
                    $SequenceNumber = ($SequenceNumber+1);
                }
                if(isset($PNR_Booking_Class_arr['soapenv:Envelope']['soapenv:Body']['soap:Fault']) || isset($PNR_Booking_Class_arr['soapenv:Envelope']['soapenv:Body']['Fare_PricePNRWithBookingClassReply']['applicationError']) || isset($PNR_Booking_Class_arr['soap:Envelope']['soap:Body']['soap:Fault'])){

                    $this->Security_SignOut($SessionId,$SequenceNumber,$SecurityToken);
                }else{
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
    private function Ticket_CreateTSTFromPricing($search_data,$header_data){
         $response['status'] = FAILURE_STATUS;
        $response['data'] = array();
        $response['message'] = '';
        $SessionId = $header_data['SessionId'];
        $SequenceNumber = $header_data['SequenceNumber'];
        $SecurityToken = $header_data['SecurityToken'];
        $paList='';
        if($search_data['adult_config']>0){
            $paList .= '<psaList>
                        <itemReference>
                            <referenceType>TST</referenceType>
                            <uniqueReference>1</uniqueReference>
                        </itemReference>
                    </psaList>';
        }
        if($search_data['child_config']>0){
             $paList .= '<psaList>
                        <itemReference>
                            <referenceType>TST</referenceType>
                            <uniqueReference>2</uniqueReference>
                        </itemReference>
                    </psaList>';
        }
        if(($search_data['infant_config']>0 && $search_data['child_config'] > 0)){
            $paList .= '<psaList>
                        <itemReference>
                            <referenceType>TST</referenceType>
                            <uniqueReference>3</uniqueReference>
                        </itemReference>
                    </psaList>';
        }elseif (($search_data['infant_config']>0 && $search_data['child_config'] == 0)) {
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
                <awsse:SessionId>'.$SessionId.'</awsse:SessionId>
                <awsse:SequenceNumber>'.$SequenceNumber.'</awsse:SequenceNumber>
                <awsse:SecurityToken>'.$SecurityToken.'</awsse:SecurityToken>
                </awsse:Session>
                <add:MessageID xmlns:add="http://www.w3.org/2005/08/addressing">'.$this->getuuid().'</add:MessageID>
                <add:Action xmlns:add="http://www.w3.org/2005/08/addressing">http://webservices.amadeus.com/'.$soapAction.'</add:Action>
                <add:To xmlns:add="http://www.w3.org/2005/08/addressing">'.$this->api_url.'</add:To>
                </soapenv:Header>
                <soapenv:Body>
                <taut:Ticket_CreateTSTFromPricing xmlns:taus="http://xml.amadeus.com/'.$soapAction.'">
                '.$paList.'
                </taut:Ticket_CreateTSTFromPricing>
                </soapenv:Body>
                </soapenv:Envelope>';

        $soap_url = $this->soap_url.$soapAction;
        $api_response = $this->process_request($xml_query,$this->api_url,'TicketPricingReq(amadeus)',$soap_url);
        //$api_response =  file_get_contents(FCPATH.'ticket.xml');
        if($api_response){
            $api_response = $this->xml2array($api_response);
            if(isset($api_response['soapenv:Envelope']['soapenv:Header'])){
                $SessionId = $api_response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SessionId'];    
                $SecurityToken = $api_response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SecurityToken'];
                $SequenceNumber =  $api_response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SequenceNumber'];
                $SequenceNumber = ($SequenceNumber+1);
            }
            if(isset($api_response['soapenv:Envelope']['soapenv:Body']['soap:Fault']) || isset($api_response['soapenv:Envelope']['soapenv:Body']['Ticket_CreateTSTFromPricingReply']['applicationError']) || isset($api_response['soap:Envelope']['soap:Body']['soap:Fault'])){
                 $this->Security_SignOut($SessionId,$SequenceNumber,$SecurityToken);
            }else{
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
    private function FOP($booking_params,$carrier,$header_data){
        $response['status'] = FAILURE_STATUS;
        $response['data'] = array();
        $response['message'] = '';
        $SessionId = $header_data['SessionId'];
        $SequenceNumber = $header_data['SequenceNumber'];
        $SecurityToken = $header_data['SecurityToken'];
        $cardNumber='4900000000000003';
        $cvv_code='123';
        $expire_date='1228';//monthyear
        $cardholdername='Elavarasi';
        $soapAction ='TFOPCQ_15_4_1A';
        $xml_query='<?xml version="1.0" encoding="utf-8"?>
                <soapenv:Envelope
                    xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
                    <soapenv:Header>
                        <awsse:Session TransactionStatusCode="InSeries"
                            xmlns:awsse="http://xml.amadeus.com/2010/06/Session_v3">
                            <awsse:SessionId>'.$SessionId.'</awsse:SessionId>
                            <awsse:SequenceNumber>'.$SequenceNumber.'</awsse:SequenceNumber>
                            <awsse:SecurityToken>'.$SecurityToken.'</awsse:SecurityToken>
                        </awsse:Session>
                        <add:MessageID xmlns:add="http://www.w3.org/2005/08/addressing">'.$this->getuuid().'</add:MessageID>
                        <add:Action xmlns:add="http://www.w3.org/2005/08/addressing">http://webservices.amadeus.com/'.$soapAction.'</add:Action>
                        <add:To xmlns:add="http://www.w3.org/2005/08/addressing">'.$this->api_url.'</add:To>
                    </soapenv:Header>
                    <soapenv:Body>
                        <FOP_CreateFormOfPayment
                            xmlns="http://xml.amadeus.com/'.$soapAction.'">
                            <fopGroup>
                                <fopReference/>
                                <mopDescription>
                                    <fopSequenceNumber>
                                        <sequenceDetails>
                                            <number>1</number>
                                        </sequenceDetails>
                                    </fopSequenceNumber>
                                    <paymentModule>
                                        <groupUsage>
                                            <attributeDetails>
                                                <attributeType>DEFP</attributeType>
                                            </attributeDetails>
                                        </groupUsage>
                                        <paymentData>
                                            <merchantInformation>
                                                <companyCode>'.$carrier.'</companyCode>
                                            </merchantInformation>
                                        </paymentData>
                                        <mopInformation>
                                            <fopInformation>
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
                                            </creditCardData>
                                        </mopInformation>
                                        <dummy/>
                                    </paymentModule>
                                </mopDescription>
                            </fopGroup>
                        </FOP_CreateFormOfPayment>
                    </soapenv:Body>
                </soapenv:Envelope>';

            $soap_url = $this->soap_url.$soapAction;
            $fop_xml_response = $this->process_request($xml_query,$this->api_url,'formOfPayment(amadeus)',$soap_url);
           // $fop_xml_response =  file_get_contents(FCPATH.'fop.xml');
            if($fop_xml_response){
                $fop_xml_response = $this->xml2array($fop_xml_response);
                if(isset($fop_xml_response['soapenv:Envelope']['soapenv:Header'])){
                    $SessionId = $fop_xml_response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SessionId'];    
                    $SecurityToken = $fop_xml_response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SecurityToken'];
                    $SequenceNumber =  $fop_xml_response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SequenceNumber'];
                    $SequenceNumber = ($SequenceNumber+1);
                }
                if(isset($fop_xml_response['soapenv:Envelope']['soapenv:Body']['FOP_CreateFormOfPaymentReply']['fopDescription'])){

                    $response['status'] = SUCCESS_STATUS;
                    $response['message'] = 'success';
                    $response['data']['SessionId'] = $SessionId;
                    $response['data']['SecurityToken'] = $SecurityToken;
                    $response['data']['SequenceNumber'] = $SequenceNumber;

                }else{
                     $this->Security_SignOut($SessionId,$SequenceNumber,$SecurityToken);
                }
            }
        return $response;
    }
    //checking the pnr status 
    private function PNR_AddMultiElements_10($header_data,$booking_params,$search_data){
        $response['status'] = FAILURE_STATUS;
        $response['data'] = array();
        $response['message'] = '';

        $passenger_xml ='';
        $passenger_data = $this->format_passenger_data($booking_params);
        $booking_passenger_arr = $passenger_data['pax'];        
        $pt_count =2;       
        $SessionId = $header_data['SessionId'];
        $SequenceNumber = $header_data['SequenceNumber'];
        $SecurityToken = $header_data['SecurityToken'];
        if($search_data['is_domestic']==false){
            if($booking_passenger_arr['ADT']){
                foreach ($booking_passenger_arr['ADT'] as $key => $value) {
                    if($value['Gender']==1||$value['Gender']==6){
                        $title = 'M';
                    }else{
                        $title = 'F';
                    }   
                    $dob= strtoupper(strtolower(date('dMy',strtotime($value['DateOfBirth']))));
                    $PassportIssuingC= strtoupper($value['PassporIssuingCountry']);
                    $passport_no = strtoupper(strtolower($value['PassportNumber']));
                    $expire = strtoupper(strtolower(date('dMy',strtotime($value['PassportExpiry']))));
                    $CountryCode= $value['CountryCode'];
                    $full_name = $value['LastName'].'/'.$value['FirstName'];
                   // $passport_info = 'P/IN/IND906/IN/19JAN78/M/24APR25/PANDEY/RAHUL';
                    $passport_info='P/'.$CountryCode.'/'.$passport_no.'/'.$PassportIssuingC.'/'.$dob.'/'.$title.'/'.$expire.'/'.$full_name;

                    $passenger_xml .='<dataElementsIndiv>
                                        <elementManagementData>
                                             <segmentName>SSR</segmentName>
                                        </elementManagementData>
                                        <serviceRequest>
                                            <ssr>
                                                <type>DOCS</type>
                                                <status>HK</status>
                                                <quantity>1</quantity>
                                                <companyId>YY</companyId>
                                                <freetext>'.$passport_info.'</freetext>
                                            </ssr>
                                        </serviceRequest>
                                        <referenceForDataElement>
                                            <reference>
                                                <qualifier>PT</qualifier>
                                            <number>'.$pt_count.'</number>
                                            </reference>
                                        </referenceForDataElement>
                                    </dataElementsIndiv>';
                    if(isset($booking_passenger_arr['INF'][$key])){
                        $f_value = $booking_passenger_arr['INF'][$key];

                        if($f_value['Gender']==1||$f_value['Gender']==6){
                            $title = 'MI';
                        }else{
                            $title = 'FI';
                        }
                        $dob= strtoupper(strtolower(date('dMy',strtotime($f_value['DateOfBirth']))));
                        $PassportIssuingC= strtoupper($f_value['PassporIssuingCountry']);
                        $passport_no = strtoupper(strtolower($f_value['PassportNumber']));
                        $expire = strtoupper(strtolower(date('dMy',strtotime($f_value['PassportExpiry']))));
                        $CountryCode= $f_value['CountryCode'];
                        $full_name = $f_value['LastName'].'/'.$f_value['FirstName'];

                        $passport_info='P/'.$CountryCode.'/'.$passport_no.'/'.$PassportIssuingC.'/'.$dob.'/'.$title.'/'.$expire.'/'.$full_name;
                        $passenger_xml .='<dataElementsIndiv>
                                        <elementManagementData>
                                             <segmentName>SSR</segmentName>
                                        </elementManagementData>
                                        <serviceRequest>
                                            <ssr>
                                                <type>DOCS</type>
                                                <status>HK</status>
                                                <quantity>1</quantity>
                                                <companyId>YY</companyId>
                                                <freetext>'.$passport_info.'</freetext>
                                            </ssr>
                                        </serviceRequest>
                                        <referenceForDataElement>
                                            <reference>
                                                <qualifier>PT</qualifier>
                                            <number>'.$pt_count.'</number>
                                            </reference>
                                        </referenceForDataElement>
                                    </dataElementsIndiv>';
                        $pt_count=$pt_count+2;
                    }else{
                       $pt_count=$pt_count+1;
                    }
                }
            }
            if(isset($booking_passenger_arr['CHD'])){
                if(valid_array($booking_passenger_arr['CHD'])){
                    foreach ($booking_passenger_arr['CHD'] as $c_key => $c_value) {                    
                        if($c_value['Gender']==1||$c_value['Gender']==6){
                            $title = 'M';
                        }else{
                            $title = 'F';
                        }   
                        $dob= strtoupper(strtolower(date('dMy',strtotime($c_value['DateOfBirth']))));
                        $PassportIssuingC= strtoupper($c_value['PassporIssuingCountry']);
                        $passport_no = strtoupper(strtolower($c_value['PassportNumber']));
                        $expire = strtoupper(strtolower(date('dMy',strtotime($c_value['PassportExpiry']))));
                        $CountryCode= $c_value['CountryCode'];
                        $full_name = $c_value['LastName'].'/'.$c_value['FirstName'];
                       // $passport_info = 'P/IN/IND906/IN/19JAN78/M/24APR25/PANDEY/RAHUL';
                        $passport_info='P/'.$CountryCode.'/'.$passport_no.'/'.$PassportIssuingC.'/'.$dob.'/'.$title.'/'.$expire.'/'.$full_name;

                        $passenger_xml .='<dataElementsIndiv>
                                            <elementManagementData>
                                                 <segmentName>SSR</segmentName>
                                            </elementManagementData>
                                        <serviceRequest>
                                            <ssr>
                                                <type>DOCS</type>
                                                <status>HK</status>
                                                <quantity>1</quantity>
                                                <companyId>YY</companyId>
                                                <freetext>'.$passport_info.'</freetext>
                                            </ssr>
                                        </serviceRequest>
                                        <referenceForDataElement>
                                            <reference>
                                                <qualifier>PT</qualifier>
                                            <number>'.$pt_count.'</number>
                                            </reference>
                                        </referenceForDataElement>
                                    </dataElementsIndiv>';
                        $pt_count++;

                    }
                }
            }
            $pax_xml_query = $passenger_xml;
        }else{
            $pax_xml_query ='';
        }
        $soapAction ='PNRADD_17_1_1A';
        $xml_query ='<?xml version="1.0" encoding="utf-8"?>
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
                    <PNR_AddMultiElements xmlns="http://xml.amadeus.com/'.$soapAction.'" >
                                 <pnrActions>
                                    <optionCode>10</optionCode>
                                </pnrActions>
                                <dataElementsMaster>
                                    <marker1/>
                                    '.$pax_xml_query.'  
                                </dataElementsMaster>                   
                    </PNR_AddMultiElements>
            </soapenv:Body>
        </soapenv:Envelope>';
        $soap_url = $this->soap_url.$soapAction;
        $pnr_option10_response = $this->process_request($xml_query,$this->api_url,'PNRAddElementOption10',$soap_url);
        //$pnr_option10_response  = file_get_contents(FCPATH.'pnr10.xml');
        if($pnr_option10_response){
            $pnr_option10_response = $this->xml2array($pnr_option10_response);
           if(isset($pnr_option10_response['soapenv:Envelope']['soapenv:Header'])){
                $SessionId = $pnr_option10_response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SessionId'];    
                $SecurityToken = $pnr_option10_response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SecurityToken'];
                $SequenceNumber =  $pnr_option10_response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SequenceNumber'];
                $SequenceNumber = ($SequenceNumber+1);
            }
      
            if((isset($pnr_option10_response['soapenv:Envelope']['soapenv:Body']['PNR_Reply'])) && (isset($pnr_option10_response['soapenv:Envelope']['soapenv:Body']['PNR_Reply']['pnrHeader']['reservationInfo']['reservation']['controlNumber'])) ){
                
                $controlNumber = $pnr_option10_response['soapenv:Envelope']['soapenv:Body']['PNR_Reply']['pnrHeader']['reservationInfo']['reservation']['controlNumber'];
                $response['status'] = SUCCESS_STATUS;
                $response['message'] = 'success';
                $response['data']['SessionId'] = $SessionId;
                $response['data']['SecurityToken'] = $SecurityToken;
                $response['data']['SequenceNumber'] = $SequenceNumber;
                $response['data']['Pnr_No'] = $controlNumber;
            }else{
            
                $this->Security_SignOut($SessionId,$SequenceNumber,$SecurityToken);
            }
          
        }
        return $response;
        
    }
    //placing in queue
    private function Place_Queue($booking_data){
        $response['status'] = FAILURE_STATUS;
        $response['data'] = array();
        $response['message'] = '';
        $SessionId = $booking_data['SessionId'];
        $SequenceNumber = $booking_data['SequenceNumber'];
        $SecurityToken = $booking_data['SecurityToken'];
        $pnr_no = $booking_data['Pnr_No'];
        if($pnr_no){
            //$soapAction ='QUQPCQ_03_1_1A';
            $soapAction ='QUQPCR_03_1_1A';
            $xml_query ='<?xml version="1.0" encoding="UTF-8"?>           
                        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:sec="http://xml.amadeus
                        .com/2010/06/Security_v1" xmlns:typ="http://xml.amadeus.com/2010/06/Type">
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
                                        <inHouseIdentification1>'.$this->pseudo_city_code.'</inHouseIdentification1>
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
                                        <controlNumber>'.$pnr_no.'</controlNumber>
                                    </reservation>
                                </recordLocator>
                            </Queue_PlacePNR>
                        </soapenv:Body>
                    </soapenv:Envelope>';
            $soap_url  = $this->soap_url.$soapAction;
            $place_queue_response = $this->process_request($xml_query,$this->api_url,'Queue_PlacePNR(amadeus)',$soap_url);

            if($place_queue_response){
                $place_queue_response = $this->xml2array($place_queue_response);
               
                if(isset($place_queue_response['soapenv:Envelope']['soapenv:Header'])){
                    $SessionId = $place_queue_response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SessionId'];    
                    $SecurityToken = $place_queue_response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SecurityToken'];
                    $SequenceNumber =  $place_queue_response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SequenceNumber'];
                    $SequenceNumber = ($SequenceNumber+1);
                }
                if(isset($place_queue_response['soapenv:Envelope']['soapenv:Body']['Queue_PlacePNRReply']['recordLocator'])){
                    if(isset($place_queue_response['soapenv:Envelope']['soapenv:Body']['Queue_PlacePNRReply']['recordLocator']['reservation']['controlNumber'])){

                        $controlNumber = $place_queue_response['soapenv:Envelope']['soapenv:Body']['Queue_PlacePNRReply']['recordLocator']['reservation']['controlNumber'];

                        $response['status'] = SUCCESS_STATUS;   
                        $response['message'] = 'success';
                        $response['data']['SessionId'] = $SessionId;
                        $response['data']['SecurityToken'] = $SecurityToken;
                        $response['data']['SequenceNumber'] = $SequenceNumber;
                        $response['data']['Pnr_No'] = $controlNumber;


                    }
                }else{
                     // $this->Security_SignOut($SessionId,$SequenceNumber,$SecurityToken);
                }
              
            }
        }
        return $response;
    }
    /*Retrieve booked  PNR details*/
    private function PNRRetrieve($header_data){
        $response['status'] = FAILURE_STATUS;
        $response['data'] = array();
        $response['message'] = '';
        $SessionId = $header_data['SessionId'];
        $SequenceNumber = $header_data['SequenceNumber'];
        $SecurityToken = $header_data['SecurityToken'];
        $pnr_no = $header_data['Pnr_No'];
        $soapAction = 'PNRRET_17_1_1A';
        $xml_query ='<?xml version="1.0" encoding="UTF-8"?>
                    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:sec="http://xml.amadeus.com/2010/06/Security_v1" xmlns:typ="http://xml.amadeus.com/2010/06/Type">
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
                <PNR_Retrieve xmlns="http://xml.amadeus.com/'.$soapAction.'">
                    <retrievalFacts>
                        <retrieve>
                            <type>2</type>
                        </retrieve>
                        <reservationOrProfileIdentifier>
                            <reservation>
                                <controlNumber>'.$pnr_no.'</controlNumber>
                            </reservation>
                        </reservationOrProfileIdentifier>
                    </retrievalFacts>
                </PNR_Retrieve>
                </soapenv:Body>
                </soapenv:Envelope>';

            $soap_url = $this->soap_url.$soapAction;
            $pnr_rerieve_response = $this->process_request($xml_query,$this->api_url,'PNR_Retrieve(amadeus)',$soap_url);
            if($pnr_rerieve_response){
                $pnr_rerieve_response = $this->xml2array($pnr_rerieve_response);
                if(isset($pnr_rerieve_response['soapenv:Envelope']['soapenv:Header'])){
                    $SessionId = $pnr_rerieve_response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SessionId'];    
                    $SecurityToken = $pnr_rerieve_response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SecurityToken'];
                    $SequenceNumber =  $pnr_rerieve_response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SequenceNumber'];
                    $SequenceNumber = ($SequenceNumber+1);
                    if(isset($pnr_rerieve_response['soapenv:Envelope']['soapenv:Body']['PNR_Reply']['pnrHeader']['reservationInfo']['reservation'])){
                        $controlNumber = $pnr_rerieve_response['soapenv:Envelope']['soapenv:Body']['PNR_Reply']['pnrHeader']['reservationInfo']['reservation']['controlNumber'];

                        $response['status'] = SUCCESS_STATUS;   
                        $response['message'] = 'success';
                        $response['data']['SessionId'] = $SessionId;
                        $response['data']['SecurityToken'] = $SecurityToken;
                        $response['data']['SequenceNumber'] = $SequenceNumber;
                        $response['data']['Pnr_No'] = $controlNumber;
                    }
                }else{
                     //$this->Security_SignOut($SessionId,$SequenceNumber,$SecurityToken);
                }

            }
        return $response;
            
    }
    private function DocIssuance_IssueTicket($header_data){
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
            <awsse:SessionId>'.$SessionId.'</awsse:SessionId>
            <awsse:SequenceNumber>'.$SequenceNumber.'</awsse:SequenceNumber>
            <awsse:SecurityToken>'.$SecurityToken.'</awsse:SecurityToken>
            </awsse:Session>
            <add:MessageID xmlns:add="http://www.w3.org/2005/08/addressing">'.$this->getuuid().'</add:MessageID>
            <add:Action xmlns:add="http://www.w3.org/2005/08/addressing">http://webservices.amadeus.com/'.$soapAction.'</add:Action>
            <add:To xmlns:add="http://www.w3.org/2005/08/addressing">'.$this->api_url.'</add:To>
            </soapenv:Header>';
            $DocIssuance_IssueTicket = '<?xml version="1.0" encoding="utf-8"?>
            <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
            '.$soapHeaderFor_IssueTicket.'
            <soapenv:Body>
            <DocIssuance_IssueTicket>
            <optionGroup>
                <switches>
                    <statusDetails>
                        <indicator>ET</indicator>
                    </statusDetails>
                </switches>
            </optionGroup>
            <otherCompoundOptions>
            <attributeDetails>
                <attributeType>ETC</attributeType>
            </attributeDetails>
            </otherCompoundOptions>
            </DocIssuance_IssueTicket>
            </soapenv:Body>
            </soapenv:Envelope>';
            $soap_url = $this->soap_url.$soapAction;            
            $ticket_response = $this->process_request($DocIssuance_IssueTicket,$this->api_url,'IssueTicket(amadeus)',$soap_url);
           if($ticket_response){
             $ticket_response = $this->xml2array($ticket_response);
               if(isset($ticket_response['soapenv:Envelope']['soapenv:Header'])){
                     $SessionId = $ticket_response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SessionId'];    
                    $SecurityToken = $ticket_response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SecurityToken'];
                    $SequenceNumber =  $ticket_response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SequenceNumber'];
                    $SequenceNumber = ($SequenceNumber+1);
                    $response['status'] = SUCCESS_STATUS;
               }

                $this->Security_SignOut($SessionId,$SequenceNumber,$SecurityToken);
           }

        return $response;
    }

    /*Booking Procedure Ends*/    
    

    /**
     * Formats the Fare Request For Passenger-wise
     *
     * @param unknown_type $passenger_token
     */
    private function assign_booking_passenger_fare_breakdown($passenger_token) {
        $passenger_token = force_multple_data_format($passenger_token);
        $Fare = array();
        foreach ($passenger_token as $k => $v) {
            $Fare [$v ['PassengerType']] ['BaseFare'] = ($v ['BasePrice'] / $v ['PassengerCount']);
            $Fare [$v ['PassengerType']] ['Tax'] = ($v ['Tax'] / $v ['PassengerCount']);
            //$Fare [$v ['PassengerType']] ['TransactionFee'] = ($v ['TransactionFee'] / $v ['PassengerCount']);
            $Fare [$v ['PassengerType']] ['TransactionFee'] = 0;
            // $Fare [$v ['PassengerType']] ['YQTax'] = ($v ['YQTax'] / $v ['PassengerCount']);
            // $Fare [$v ['PassengerType']] ['AdditionalTxnFeeOfrd'] = ($v ['AdditionalTxnFeeOfrd'] / $v ['PassengerCount']);
            // $Fare [$v ['PassengerType']] ['AdditionalTxnFeePub'] = ($v ['AdditionalTxnFeePub'] / $v ['PassengerCount']);
            //$Fare [$v ['PassengerType']] ['AirTransFee'] = ($v ['AirTransFee'] / $v ['PassengerCount']);
            $Fare [$v ['PassengerType']] ['AirTransFee'] = 0;
        }
        return $Fare;
    }

     /**
     *
     * Enter description here ...
     * @param unknown_type $booking_params
     * @param unknown_type $app_reference
     * @param unknown_type $sequence_number
     */
    private function run_book_service($booking_params, $app_reference, $sequence_number, $search_id) {
        $response ['status'] = FAILURE_STATUS; // Status Of Operation
        $response ['message'] = ''; // Message to be returned
        $response ['data'] = array(); // Data to be returned
        $book_service_response = $this->run_book_service_request($booking_params, $search_id);
        if ($book_service_response['status'] == SUCCESS_STATUS) {

            if (valid_array($book_service_response) == true && isset($book_service_response['data']['airline_pnr']) == true && isset($book_service_response['data']['gds_pnr']) == true) {
                $response ['status'] = SUCCESS_STATUS;
                $response ['data']['book_response'] = $book_service_response['data'];
            } else {
                $error_message = '';
                if (isset($book_service_response['Response'] ['Error'] ['ErrorMessage'])) {
                    $error_message = $book_service_response['Response'] ['Error'] ['ErrorMessage'];
                }
                if (empty($error_message) == true) {
                    $error_message = 'Booking Failed';
                }
                $response ['message'] = $error_message;

                //Log Exception
                $exception_log_message = '';
                $this->CI->exception_logger->log_exception($app_reference, $this->booking_source_name . '- (<strong>BOOK</strong>)', $exception_log_message, $book_service_response);
            }
        } else {
            $response ['status'] = FAILURE_STATUS;
        }
       
        return $response;
    }

    /**
     * Validates the mobile number
     * @param unknown_type $mobile_number
     */
    private function validate_mobile_number($mobile_number) {
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
     * Forms the Book request
     * @param unknown_type $request
     */
    private function run_book_service_request($params, $search_id) {
        // debug($params);exit;
        $response ['status'] = FAILURE_STATUS; // Status Of Operation
        $response ['message'] = ''; // Message to be returned
        $response ['data'] = array();
        if(valid_array($params)) {
          
            $travel_itinerary_request = $this->TravelItineraryAddInfo_Request($params);
            if($travel_itinerary_request['status'] == true){
                $travel_itinerary_response = $this->process_request($travel_itinerary_request ['request'], $travel_itinerary_request ['url'], $travel_itinerary_request ['remarks']);    
                $travel_itinerary_resposne  = Converter::createArray($travel_itinerary_response);
                // debug($travel_itinerary_response);exit;
                if($this->valid_travelintinerary_response($travel_itinerary_resposne) ==true){
                    $TravelItineraryAddInfoStatus = $travel_itinerary_resposne['soap-env:Envelope']['soap-env:Body']['TravelItineraryAddInfoRS']['stl:ApplicationResults']['@attributes']['status'];
                    if ($TravelItineraryAddInfoStatus == 'Complete') {
                        $add_remark_request = $this->AddRemark_Request($params);
                        $add_remark_response = $this->process_request($add_remark_request ['request'], $add_remark_request ['url'], $add_remark_request ['remarks']);    
                        // debug($add_remark_response);exit;
                        $Add_remark_resposne = Converter::createArray($add_remark_response);
                        if($this->valid_travelintinerary_response($add_remark_response) ==true){
                            $ota_airbook_request = $this->OTA_AirBook_Request($params, $search_id);
                            $ota_airbook_response = $this->process_request_book($ota_airbook_request ['request'], $ota_airbook_request ['url'], $ota_airbook_request ['remarks']);    
                            // $ota_airbook_response = file_get_contents(FCPATH."enhanced_air_book_res.xml");
                            $ota_airbook_response = Converter::createArray($ota_airbook_response);
                            // debug($ota_airbook_response);exit;
                            if($this->valid_airbook_response($ota_airbook_response) ==true){
                                $ReserveAirseatRQ_RS = $this->ReserveAirSeatLLS_request($params);
                                if(isset($ota_airbook_response['soap-env:Envelope']['soap-env:Body']['EnhancedAirBookRS']['TravelItineraryReadRS']['TravelItinerary']['ItineraryInfo']['ReservationItems']['Item'][0]['FlightSegment'][0]['SupplierRef']['@attributes']['ID'])){
                                    $airline_pnr = $ota_airbook_response['soap-env:Envelope']['soap-env:Body']['EnhancedAirBookRS']['TravelItineraryReadRS']['TravelItinerary']['ItineraryInfo']['ReservationItems']['Item'][0]['FlightSegment'][0]['SupplierRef']['@attributes']['ID'];
                                    // $airline_pnr = explode('*', $airline_pnr1);  
                                    // $airline_pnr = $airline_pnr[1];                            
                                }
                                if(isset($ota_airbook_response['soap-env:Envelope']['soap-env:Body']['EnhancedAirBookRS']['TravelItineraryReadRS']['TravelItinerary']['ItineraryInfo']['ReservationItems']['Item'][0]['FlightSegment']['SupplierRef']['@attributes']['ID'])){
                                    $airline_pnr = $ota_airbook_response['soap-env:Envelope']['soap-env:Body']['EnhancedAirBookRS']['TravelItineraryReadRS']['TravelItinerary']['ItineraryInfo']['ReservationItems']['Item'][0]['FlightSegment']['SupplierRef']['@attributes']['ID'];
                                    // $airline_pnr = explode('*', $airline_pnr1);  
                                    // $airline_pnr = $airline_pnr[1];     
                                }
                                // echo $airline_pnr;exit;
                              
                                $ota_airprice_request = $this->OTA_AirPrice_Request($params,$search_id);
                                $ota_airprice_response = $this->process_request($ota_airprice_request ['request'], $ota_airprice_request ['url'], $ota_airprice_request ['remarks']);    
                                $ota_price_resposne = Converter::createArray($ota_airprice_response);
                                if($this->valid_travelintinerary_response($ota_price_resposne) ==true){
                                    $special_service_request = $this->SpecialService_Request($params,$search_id);
                                    $special_service_response = $this->process_request($special_service_request ['request'], $special_service_request ['url'], $special_service_request ['remarks']);    
                                    $special_service_response = Converter::createArray($special_service_response);
                                    if($this->valid_specialservice_response($special_service_response) ==true){
                                        $end_transaction_request = $this->EndTransaction_Request($params);
                                        $end_transaction_response = $this->process_request($end_transaction_request ['request'], $end_transaction_request ['url'], $end_transaction_request ['remarks']);    
                                        // $end_transaction_response = file_get_contents(FCPATH."end_transaction_res.xml");
                                        $end_transaction_response = Converter::createArray($end_transaction_response);
                                        $gds_pnr = $end_transaction_response['soap-env:Envelope']['soap-env:Body']['EndTransactionRS']['ItineraryRef']['@attributes']['ID'];
                                        $travel_read_itinerary_request = $this->TravelItineraryReadInfo_Request($gds_pnr); 
                                        // $travel_read_itinerary_res = file_get_contents(FCPATH."travel_intenary_read.xml");
                                        $travel_read_itinerary_res = $this->process_request($travel_read_itinerary_request ['request'], $travel_read_itinerary_request ['url'], $travel_read_itinerary_request ['remarks']);    
                                        $travel_read_itinerary_res = Converter::createArray($travel_read_itinerary_res);
                                        if(isset($travel_read_itinerary_res['soap-env:Envelope']['soap-env:Body']['TravelItineraryReadRS']['TravelItinerary']['ItineraryInfo']['ReservationItems']['Item'][0]['FlightSegment'][0]['SupplierRef']['@attributes']['ID'])){
                                            $airline_pnr = @$travel_read_itinerary_res['soap-env:Envelope']['soap-env:Body']['TravelItineraryReadRS']['TravelItinerary']['ItineraryInfo']['ReservationItems']['Item'][0]['FlightSegment'][0]['SupplierRef']['@attributes']['ID'];

                                        }
                                        if(isset($travel_read_itinerary_res['soap-env:Envelope']['soap-env:Body']['TravelItineraryReadRS']['TravelItinerary']['ItineraryInfo']['ReservationItems']['Item'][0]['FlightSegment']['SupplierRef']['@attributes']['ID'])){
                                            $airline_pnr = @$travel_read_itinerary_res['soap-env:Envelope']['soap-env:Body']['TravelItineraryReadRS']['TravelItinerary']['ItineraryInfo']['ReservationItems']['Item'][0]['FlightSegment']['SupplierRef']['@attributes']['ID'];

                                        }
                                        if(isset($travel_read_itinerary_res['soap-env:Envelope']['soap-env:Body']['TravelItineraryReadRS']['TravelItinerary']['ItineraryInfo']['ReservationItems']['Item']['FlightSegment']['SupplierRef']['@attributes']['ID'])){
                                            $airline_pnr = @$travel_read_itinerary_res['soap-env:Envelope']['soap-env:Body']['TravelItineraryReadRS']['TravelItinerary']['ItineraryInfo']['ReservationItems']['Item']['FlightSegment']['SupplierRef']['@attributes']['ID'];

                                        }
                                        if(isset($travel_read_itinerary_res['soap-env:Envelope']['soap-env:Body']['TravelItineraryReadRS']['TravelItinerary']['ItineraryInfo']['ReservationItems']['Item']['FlightSegment'][0]['SupplierRef']['@attributes']['ID'])){
                                            $airline_pnr = @$travel_read_itinerary_res['soap-env:Envelope']['soap-env:Body']['TravelItineraryReadRS']['TravelItinerary']['ItineraryInfo']['ReservationItems']['Item']['FlightSegment'][0]['SupplierRef']['@attributes']['ID'];
                                        }
                                        // debug($airline_pnr);exit;
                                        $response ['data']['airline_pnr'] = $airline_pnr;
                                        $response ['data']['gds_pnr'] = $gds_pnr;
                                        $response ['status'] = SUCCESS_STATUS;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        // debug($response);exit;
      return $response;
    }
    private function TravelItineraryAddInfo_Request($booking_param)
    {
        // debug($booking_param);exit;
        $passenger_contact_number = $booking_param['Passengers'][0]['ContactNo'];
        $passenger_email = $booking_param['Passengers'][0]['Email'];
        $a = 1;
        $travellers ='';
        for($aaa=0;$aaa < count($booking_param['Passengers']);$aaa++) {       
            if ($booking_param['Passengers'][$aaa]['PaxType'] == 1) {
                $pmytitle = $booking_param['Passengers'][$aaa]['Title'];
                $pfirst_name = $booking_param['Passengers'][$aaa]['FirstName'];
                $adultDOB = strtoupper(date("dMy", strtotime($booking_param['Passengers'][$aaa]['DateOfBirth'])));
                // echo $adultDOB;exit;
                $GivenNameXMLADT = "<GivenName>".$pfirst_name."</GivenName>";
                $travellers .= "<PersonName NameNumber='".$a.".1' PassengerType='ADT' NameReference='".$pmytitle."'>
                    ".$GivenNameXMLADT."
                    <Surname>".$booking_param['Passengers'][$aaa]['LastName']."</Surname>
                  </PersonName>";
            }
             if ($booking_param['Passengers'][$aaa]['PaxType'] == 2) {
                $pmytitle = $booking_param['Passengers'][$aaa]['Title'];
                $pfirst_name = $booking_param['Passengers'][$aaa]['FirstName'];
                $adultDOB = strtoupper(date("dMy", strtotime($booking_param['Passengers'][$aaa]['DateOfBirth'])));
                // echo $adultDOB;exit;
                $GivenNameXMLADT = "<GivenName>".$pfirst_name."</GivenName>";
                $travellers .= "<PersonName NameNumber='".$a.".1' PassengerType='CNN' NameReference='".$pmytitle."'>
                    ".$GivenNameXMLADT."
                    <Surname>".$booking_param['Passengers'][$aaa]['LastName']."</Surname>
                  </PersonName>";
            }
             if ($booking_param['Passengers'][$aaa]['PaxType'] == 3) {
                $pmytitle = $booking_param['Passengers'][$aaa]['Title'];
                $pfirst_name = $booking_param['Passengers'][$aaa]['FirstName'];
                $adultDOB = strtoupper(date("dMy", strtotime($booking_param['Passengers'][$aaa]['DateOfBirth'])));
                // echo $adultDOB;exit;
                $GivenNameXMLADT = "<GivenName>".$pfirst_name."</GivenName>";
                $travellers .= "<PersonName Infant='true' NameNumber='".$a.".1' PassengerType='INF' NameReference='".$pmytitle."'>
                    ".$GivenNameXMLADT."
                    <Surname>".$booking_param['Passengers'][$aaa]['LastName']."</Surname>
                  </PersonName>";
            }
            $a++;
        }

        $request_params = 
            "<?xml version='1.0' encoding='utf-8'?>
                <soap-env:Envelope xmlns:soap-env='http://schemas.xmlsoap.org/soap/envelope/'>
                    <soap-env:Header>
                        <eb:MessageHeader
                          xmlns:eb='http://www.ebxml.org/namespaces/messageHeader'>
                            <eb:From>
                                <eb:PartyId eb:type='urn:x12.org.IO5:01'>".$this->config['sabre_email']."</eb:PartyId>
                            </eb:From>
                            <eb:To>
                              <eb:PartyId eb:type='urn:x12.org.IO5:01'>webservices.sabre.com</eb:PartyId>
                            </eb:To>
                          <eb:ConversationId>".$this->conversation_id."</eb:ConversationId>
                          <eb:Service eb:type='OTA'>Air</eb:Service>
                          <eb:Action>TravelItineraryAddInfoLLSRQ</eb:Action>
                          <eb:CPAID>".$this->config['ipcc']."</eb:CPAID>
                          <eb:MessageData>
                              <eb:MessageId>".$this->message_id."</eb:MessageId>
                              <eb:Timestamp>".$this->timestamp."</eb:Timestamp>
                              <eb:TimeToLive>".$this->timetolive."</eb:TimeToLive>
                          </eb:MessageData>
                      </eb:MessageHeader>
                      <wsse:Security xmlns:wsse='http://schemas.xmlsoap.org/ws/2002/12/secext'>
                          <wsse:UsernameToken>
                              <wsse:Username>".$this->config['username']."</wsse:Username>
                              <wsse:Password>".$this->config['password']."</wsse:Password>
                              <Organization>".$this->config['ipcc']."</Organization>
                              <Domain>Default</Domain>
                          </wsse:UsernameToken>
                          <wsse:BinarySecurityToken>".$this->api_session_id."</wsse:BinarySecurityToken>
                      </wsse:Security>
                  </soap-env:Header>
                  <soap-env:Body>
                      <TravelItineraryAddInfoRQ Version='2.0.2' xmlns='http://webservices.sabre.com/sabreXML/2011/10' xmlns:xs='http://www.w3.org/2001/XMLSchema' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance'>
                        <AgencyInfo>
                             <Address>
                                <AddressLine>electronic city bangalore</AddressLine>
                                <CityName>Bangalore Karnataka</CityName>
                                <PostalCode>79956</PostalCode>
                                <StateCountyProv StateCode='ON'/>
                                <StreetNmbr>201</StreetNmbr>                              
                            </Address>
                            <Ticketing TicketType='7T-A/PENDING TKT ISSUE'/>
                        </AgencyInfo>
                        <CustomerInfo>
                            <ContactNumbers>
                            <ContactNumber Phone='".$passenger_contact_number."' PhoneUseType='P'/>
                                <ContactNumber Phone='".$passenger_contact_number."' PhoneUseType='A'/>
                            </ContactNumbers>
                            <Email Address='".$passenger_email."' Type='CC'/>
                            ".$travellers."
                        </CustomerInfo>
                    </TravelItineraryAddInfoRQ>
                  </soap-env:Body>
              </soap-env:Envelope>";
        $request ['request'] = $request_params;
        $request ['url'] = $this->config['api_url'];
        $request ['remarks'] = 'TravelItineraryAddInfo(Sabare)';
        $request ['status'] = SUCCESS_STATUS;
        // debug($request);exit;
        return $request;
    }
    private function AddRemark_Request($booking_params)
    {
        // debug($booking_params);exit;
        $traveler_details   = $booking_params;
        if(isset($booking_params['card_number']) == true && isset($booking_params['card_expmonth']) ==true && isset($booking_params['card_expyear'])==true && isset($booking_params['cvv_code']) ==true){
            $customer_payment_details['cardholdername'] = $booking_params['cardholdername'];
            $customer_payment_details['street'] = $booking_params['street'][0].' '.$booking_params['street'][1];
            $customer_payment_details["city"] = $booking_params['city'];
            $customer_payment_details["state"] = $booking_params['state'];
            $customer_payment_details["postal_code"] = $booking_params['zipcode'];
            $customer_payment_details['card_type'] = $this->get_card_type_code($booking_params['card_type']);
            $customer_payment_details["card_expyear"] = $booking_params['card_expyear'];
            $customer_payment_details["card_expmonth"] = $booking_params['card_expmonth'];
            $customer_payment_details['card_number'] = $booking_params['card_number'];
            $customer_payment_details['cvv_code'] = $booking_params['cvv_code'];
        }
        if(empty($customer_payment_details) ==false){
            $customer_payment_details_address = $customer_payment_details['cardholdername'];
            $customer_payment_details_address1 = ' '.$customer_payment_details["street"];
         
            $customer_payment_details_address2 = $customer_payment_details["city"];
            $customer_payment_details_address2 .= ','.$customer_payment_details["state"];
            $customer_payment_details_address2 .= ' '.$customer_payment_details["postal_code"]; 

            $PaymentDetails = '<FOP_Remark>
                 <CC_Info>
                  <PaymentCard Code="'.$customer_payment_details["card_type"].'" ExpireDate="'.$customer_payment_details["card_expyear"].'-'.$customer_payment_details["card_expmonth"].'" Number="'.$customer_payment_details["card_number"].'" />
                </CC_Info>
              </FOP_Remark>';

            $GeneralReamrk = '<Remark Type="General">
                  <Text>CVV - '.$customer_payment_details["cvv_code"].'</Text>
                </Remark>';

            $BillingAddress = '<Remark Type="Client Address">
                  <Text>'.$customer_payment_details_address.'</Text>
                  </Remark>';

            $BillingAddress .= '<Remark Type="Client Address">
                  <Text>'.$customer_payment_details_address1.'</Text>
                  </Remark>';

            $BillingAddress .= '<Remark Type="Client Address">
                  <Text>'.$customer_payment_details_address2.'</Text>
                  </Remark>';

        }else{
            $PaymentDetails = '';

            $GeneralReamrk = '<Remark Type="General">
                  <Text>X/-DK/020 1 6 1</Text>
                </Remark>';
            $booking_params['Passengers'][0]['LastName'] = '560100';
            $BillingAddress = '<Remark Type="Client Address">

                  <Text>'.$booking_params['Passengers'][0]['FirstName'].$booking_params['Passengers'][0]['LastName']."".$booking_params['Passengers'][0]['City']."".$booking_params['Passengers'][0]['CountryName']."".$booking_params['Passengers'][0]['LastName'].'</Text>
                  </Remark>';
        }
        
        /**<FOP_Remark>
                 <CC_Info>
                  <PaymentCard Code="VI" ExpireDate="2018-12" Number="4242424242424242" />
                </CC_Info>
              </FOP_Remark>
        **/  
        $request_params = 
            "<?xml version='1.0' encoding='utf-8'?>
                <soap-env:Envelope xmlns:soap-env='http://schemas.xmlsoap.org/soap/envelope/'>
                    <soap-env:Header>
                        <eb:MessageHeader
                          xmlns:eb='http://www.ebxml.org/namespaces/messageHeader'>
                          <eb:From>
                              <eb:PartyId eb:type='urn:x12.org.IO5:01'>".$this->config['sabre_email']."</eb:PartyId>
                          </eb:From>
                          <eb:To>
                              <eb:PartyId eb:type='urn:x12.org.IO5:01'>webservices.sabre.com</eb:PartyId>
                          </eb:To>
                          <eb:ConversationId>".$this->conversation_id."</eb:ConversationId>
                          <eb:Service eb:type='OTA'>Air</eb:Service>
                          <eb:Action>AddRemarkLLSRQ</eb:Action>
                          <eb:CPAID>".$this->config['ipcc']."</eb:CPAID>
                          <eb:MessageData>
                              <eb:MessageId>".$this->message_id."</eb:MessageId>
                              <eb:Timestamp>".$this->timestamp."</eb:Timestamp>
                              <eb:TimeToLive>".$this->timetolive."</eb:TimeToLive>
                          </eb:MessageData>
                      </eb:MessageHeader>
                      <wsse:Security xmlns:wsse='http://schemas.xmlsoap.org/ws/2002/12/secext'>
                          <wsse:UsernameToken>
                              <wsse:Username>".$this->config['username']."</wsse:Username>
                              <wsse:Password>".$this->config['password']."</wsse:Password>
                              <Organization>".$this->config['ipcc']."</Organization>
                              <Domain>Default</Domain>
                          </wsse:UsernameToken>
                          <wsse:BinarySecurityToken>".$this->api_session_id."</wsse:BinarySecurityToken>
                      </wsse:Security>
                  </soap-env:Header>
                  <soap-env:Body>
                      <AddRemarkRQ xmlns='http://webservices.sabre.com/sabreXML/2011/10' xmlns:xs='http://www.w3.org/2001/XMLSchema' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' ReturnHostCommand='false' Version='2.1.0'>
              <RemarkInfo>
              ".$PaymentDetails."
              ".$GeneralReamrk."
              ".$BillingAddress."
              </RemarkInfo>
                     </AddRemarkRQ>
                  </soap-env:Body>
              </soap-env:Envelope>";
        $request ['request'] = $request_params;
        $request ['url'] = $this->config['api_url'];
        $request ['remarks'] = 'AddRemark(Sabare)';
        $request ['status'] = SUCCESS_STATUS;
        // debug($request);exit;
        return $request;
    }
    private function OTA_AirBook_Request($booking_params, $search_id)
    {
        $search_data = $this->search_data($search_id);
        // debug($search_data);
        // debug($booking_params);exit;
       
        $segment_data   = $booking_params['flight_data']['FlightDetails']['Details'];
        // debug($segment_data);exit;
        $Segments = '';
        for($j=0;$j< count($segment_data); $j++){

            for($ss=0;$ss< count($segment_data[$j]); $ss++){
                $MarriageGrp_status="false";
                if($segment_data[$j][$ss]['JourneyDetails']['MarriageGrp'] =="O"){
                    $MarriageGrp_status="false";
                }else if($segment_data[$j][$ss]['JourneyDetails']['MarriageGrp'] =="I"){
                    $MarriageGrp_status="true";
                }
                $MarriageGrp_status="true";
                $Segments .= "<FlightSegment DepartureDateTime='".$segment_data[$j][$ss]['JourneyDetails']['DepartureDateTime']."' ArrivalDateTime='".$segment_data[$j][$ss]['JourneyDetails']['ArrivalDateTime']."' FlightNumber='".$segment_data[$j][$ss]['FlightNumber']."' NumberInParty='".($search_data['data']['adult_config'] + $search_data['data']['child_config'])."' ResBookDesigCode='".$segment_data[$j][$ss]['JourneyDetails']['ResBookDesigCode']."' Status='NN'>
                   <DestinationLocation LocationCode='".$segment_data[$j][$ss]['Destination']['AirportCode']."'/>
                   <Equipment AirEquipType='".$segment_data[$j][$ss]['JourneyDetails']['Equipment']."'/>
                   <MarketingAirline Code='".$segment_data[$j][$ss]['JourneyDetails']['MarkettingAirline']."' FlightNumber='".$segment_data[$j][$ss]['FlightNumber']."'/>
                   <MarriageGrp Ind='".$MarriageGrp_status."'/>
                   <OperatingAirline Code='".$segment_data[$j][$ss]['OperatorCode']."'/>
                   <OriginLocation LocationCode='".$segment_data[$j][$ss]['Origin']['AirportCode']."'/>
                  </FlightSegment>";
            }
        }
        
        $request_params = "<?xml version='1.0' encoding='utf-8'?>
              <soap-env:Envelope xmlns:soap-env='http://schemas.xmlsoap.org/soap/envelope/'>
                  <soap-env:Header>
                      <eb:MessageHeader
                          xmlns:eb='http://www.ebxml.org/namespaces/messageHeader'>
                          <eb:From>
                              <eb:PartyId eb:type='urn:x12.org.IO5:01'>".$this->config['sabre_email']."</eb:PartyId>
                          </eb:From>
                          <eb:To>
                              <eb:PartyId eb:type='urn:x12.org.IO5:01'>webservices.sabre.com</eb:PartyId>
                          </eb:To>
                          <eb:ConversationId>".$this->conversation_id ."</eb:ConversationId>
                          <eb:Service>EnhancedAirBookRQ</eb:Service>
                          <eb:Action>EnhancedAirBookRQ</eb:Action>
                          <eb:CPAID>".$this->config['ipcc']."</eb:CPAID>
                          <eb:MessageData>
                              <eb:MessageId>".$this->message_id ."</eb:MessageId>
                              <eb:Timestamp>".$this->timestamp."</eb:Timestamp>
                              <eb:TimeToLive>".$this->timetolive."</eb:TimeToLive>
                          </eb:MessageData>
                      </eb:MessageHeader>
                      <wsse:Security xmlns:wsse='http://schemas.xmlsoap.org/ws/2002/12/secext'>
                          <wsse:UsernameToken>
                              <wsse:Username>".$this->config['username']."</wsse:Username>
                              <wsse:Password>".$this->config['password']."</wsse:Password>
                              <Organization>".$this->config['ipcc']."</Organization>
                              <Domain>Default</Domain>
                          </wsse:UsernameToken>
                          <wsse:BinarySecurityToken>".$this->api_session_id."</wsse:BinarySecurityToken>
                      </wsse:Security>
                  </soap-env:Header>
                  <soap-env:Body>
                    <EnhancedAirBookRQ Version='2.3.0' ReturnHostCommand='true' xmlns='http://webservices.sabre.com/sabreXML/2011/10' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xsi:schemaLocation='http://webservices.sabre.com/sabreXML/2011/10 http://webservices.sabre.com/wsdl/swso/EnhancedAirBook2.3.0RQ.xsd'>
                      <OTA_AirBookRQ HaltOnError='true'>
                        <HaltOnStatus Code='NN'/>
                        <HaltOnStatus Code='UC'/>
                        <HaltOnStatus Code='NO'/>
                        <HaltOnStatus Code='US'/>
                        <HaltOnStatus Code='LL'/>
                        <OriginDestinationInformation>
                          " . $Segments . "
                        </OriginDestinationInformation>
                        <RedisplayReservation NumAttempts='10' WaitInterval='10000'/>
                      </OTA_AirBookRQ>
                      <PostProcessing HaltOnError='true' IgnoreAfter='false' RedisplayReservation='true'/>
                      <PreProcessing HaltOnError='true' IgnoreBefore='false'/>  
                    </EnhancedAirBookRQ>
                  </soap-env:Body>
              </soap-env:Envelope>";
               $request ['request'] = $request_params;
               $request ['url'] = $this->config['api_url'];
               $request ['remarks'] = 'EnhancedAirBook(Sabare)';
               $request ['status'] = SUCCESS_STATUS;
                // debug($request);exit;
               return $request;
           
    }

    private function OTA_AirPrice_Request($booking_params,$search_id)
    { 
        $search_data = $this->search_data($search_id);
        $search_data = $search_data['data'];
        // debug($search_data);exit;
        $adult_patch  = $child_patch = $infant_patch = '';
        if($search_data['adult_config'] > 0){
            $adult_patch = "<PassengerType Code='ADT' Quantity='".$search_data['adult_config']."' Force='true'/>";
        }
        if($search_data['child_config'] > 0){
            $child_patch = "<PassengerType Code='CNN' Quantity='".$search_data['child_config']."' Force='true'/>";
        }
        if($search_data['infant_config'] > 0){
            $infant_patch = "<PassengerType Code='INF' Quantity='".$search_data['infant_config']."' Force='true'/>";
        }
   
        $request_params = "<?xml version='1.0' encoding='utf-8'?>
              <soap-env:Envelope xmlns:soap-env='http://schemas.xmlsoap.org/soap/envelope/'>
                  <soap-env:Header>
                      <eb:MessageHeader
                          xmlns:eb='http://www.ebxml.org/namespaces/messageHeader'>
                          <eb:From>
                              <eb:PartyId eb:type='urn:x12.org.IO5:01'>".$this->config['sabre_email']."</eb:PartyId>
                          </eb:From>
                          <eb:To>
                              <eb:PartyId eb:type='urn:x12.org.IO5:01'>webservices.sabre.com</eb:PartyId>
                          </eb:To>
                          <eb:ConversationId>".$this->conversation_id."</eb:ConversationId>
                          <eb:Service>OTA_AirPriceLLSRQ</eb:Service>
                          <eb:Action>OTA_AirPriceLLSRQ</eb:Action>
                          <eb:CPAID>".$this->config['ipcc']."</eb:CPAID>
                          <eb:MessageData>
                              <eb:MessageId>".$this->message_id."</eb:MessageId>
                              <eb:Timestamp>".$this->timestamp."</eb:Timestamp>
                              <eb:TimeToLive>".$this->timetolive."</eb:TimeToLive>
                          </eb:MessageData>
                      </eb:MessageHeader>
                      <wsse:Security xmlns:wsse='http://schemas.xmlsoap.org/ws/2002/12/secext'>
                          <wsse:UsernameToken>
                              <wsse:Username>".$this->config['username']."</wsse:Username>
                              <wsse:Password>".$this->config['password']."</wsse:Password>
                              <Organization>".$this->config['ipcc']."</Organization>
                              <Domain>Default</Domain>
                          </wsse:UsernameToken>
                          <wsse:BinarySecurityToken>".$this->api_session_id."</wsse:BinarySecurityToken>
                      </wsse:Security>
                  </soap-env:Header>
                  <soap-env:Body>
                    <OTA_AirPriceRQ Version='2.8.0' xmlns='http://webservices.sabre.com/sabreXML/2011/10' xmlns:xs='http://www.w3.org/2001/XMLSchema' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' ReturnHostCommand='true'>
                      <PriceRequestInformation>
                        <OptionalQualifiers>
                          <PricingQualifiers>
                            ".$adult_patch.$child_patch.$infant_patch."
                          </PricingQualifiers>
                        </OptionalQualifiers>
                      </PriceRequestInformation>
                    </OTA_AirPriceRQ>
                  </soap-env:Body>
              </soap-env:Envelope>";
               
            $request ['request'] = $request_params;
            $request ['url'] = $this->config['api_url'];
            $request ['remarks'] = 'AirPrice(Sabare)';
            $request ['status'] = SUCCESS_STATUS;
            // debug($request);exit;
            return $request;
            
    }
    private function SpecialService_Request($booking_params,$search_id)
    {
        // debug($booking_params);exit;
        $search_data = $this->search_data($search_id);
        $search_data = $search_data['data'];
        $segment_data   = $booking_params['flight_data']['FlightDetails']['Details'];
        $travellers1    = $travellers   = $PassengerType = '';$a = 1;$c = 0;$i = 0;
      
        $Segments = ''; $MarketingAirlineCode = array(); $ac = 0;
        for($j=0;$j< count($segment_data); $j++){
            for($ss=0;$ss< count($segment_data[$j]); $ss++){
              $MarketingAirlineCode[$ac++] = $segment_data[$j][$ss]['MarkettingAirline'];
            }
        }
  
        $AirlineHostedTrue = '';$AirlineHostedFalse = '';$psAirlineHostedTrue = '';
        if(in_array("AA",$MarketingAirlineCode)){ $AirlineHostedTrue = "Available";
            $psAirlineHostedTrue = "true";
            for($AAA = 0; $AAA < count($MarketingAirlineCode);$AAA++){
                if($MarketingAirlineCode[$AAA] !='AA'){ $AirlineHostedFalse = "Available";
                    $psAirlineHostedTrue = "false";
                    break; 
                }
            }
        }else{ 
            $AirlineHostedFalse = "Available";
            $psAirlineHostedTrue = "false";
        }
        
        $pcounter = 0;
        $adul_count = 1;
        $segment_data = $booking_params['flight_data']['FlightDetails']['Details'];
        for($aaa=0;$aaa < count($booking_params['Passengers']);$aaa++) {  
            if ($booking_params['Passengers'][$aaa]['PaxType'] == 1) { 
                $booking_params['Passengers'][$aaa]['CountryCode'] = $booking_params['Passengers'][$aaa]['CountryCode'];
                $exp_pass_date = $booking_params['Passengers'][$aaa]['PassportExpiry'];
                $exp_passport_date =  strtoupper(date("dMy", strtotime($exp_pass_date)));
                $adultDOB = strtoupper(date("dMy", strtotime($booking_params['Passengers'][$aaa]['DateOfBirth'])));
  
                $adultText    = 'P/'.$booking_params['Passengers'][$aaa]['CountryCode'].'/'.$booking_params['Passengers'][$aaa]['PassportNo'].'/'.$booking_params['Passengers'][$aaa]['CountryCode'].'/'.$adultDOB.'/'.($booking_params['Passengers'][$aaa]['Gender']=='1'?'M':'F').'/'.$exp_passport_date.'/'.$booking_params['Passengers'][$aaa]['LastName'].'/'.$booking_params['Passengers'][$aaa]['FirstName'].'/DK-'.$a .'.'.'1';                
                $adultTextName  = $booking_params['Passengers'][$aaa]['LastName'].' '.$booking_params['Passengers'][$aaa]['FirstName'];
                $GivenNameXMLADT = "<GivenName>".$booking_params['Passengers'][$aaa]['FirstName']."</GivenName>";

                $travellers .= '<Service SSR_Code="DOCS">
                          <PersonName/>
                          <Text>DB/'.$adultDOB.'/'.($booking_params['Passengers'][$aaa]['Gender']=='1'?'M':'F').'/'.$booking_params['Passengers'][$aaa]['LastName'].'/'.$booking_params['Passengers'][$aaa]['FirstName'].'/DK-'.$a.'.1</Text>
                          <VendorPrefs><Airline Hosted="false"/></VendorPrefs>
                        </Service>';

                if ($booking_params['Passengers'][$aaa]['PassportExpiry'] != "INVALIDIP") {
                    if($AirlineHostedFalse !=''){ 
                        $travellers .= '<Service SSR_Code="DOCS">
                                <PersonName/>
                                <Text>'.$adultText.'</Text>
                                <VendorPrefs><Airline Hosted="'.$psAirlineHostedTrue.'"/></VendorPrefs>
                              </Service>';
                    }
                    if($AirlineHostedTrue !=''){
                        $travellers .= '<Service SSR_Code="DOCS">
                              <PersonName/>
                              <Text>'.$adultText.'</Text>
                              <VendorPrefs><Airline Hosted="'.$psAirlineHostedTrue.'"/></VendorPrefs>
                            </Service>';
                    }
                }
                if(isset($booking_params['Passengers'][$aaa]['MealId'])){
                    $MealsDetails = $this->meal_request_details($booking_params['Passengers'][$aaa]['MealId']);
                }
                else{
                    $MealsDetails = array();
                }
                // debug($MealsDetails);exit;
              
                for($j=0;$j< count($segment_data); $j++){
                    // for($ss=0;$ss< count($segment_data[$j]); $ss++){  
                        if (isset($MealsDetails) && valid_array($MealsDetails)) {
                            $travellers .= '<Service SegmentNumber="'.($j+1).'" SSR_Code="'.$MealsDetails[$j]['Code'].'">
                              <PersonName NameNumber="'.$a .'.1"/>
                              <VendorPrefs>
                                <Airline Hosted="'.$psAirlineHostedTrue.'"/>
                              </VendorPrefs>
                            </Service>';
                        }
                       
                    // }
                }
                
                $travellers1 .= '<Service SSR_Code="DOCS">
                        <PersonName/>
                        <Text>DB/'.$adultDOB.'/'.($booking_params['Passengers'][$aaa]['Gender']=="1"?"M":"F").'/'.$booking_params['Passengers'][$aaa]['LastName'].'/'.$booking_params['Passengers'][$aaa]['FirstName'].'/DK-'.$a.'.1</Text>
                        <VendorPrefs><Airline Hosted="false"/></VendorPrefs>
                      </Service>';
                $a++; 
                $pcounter++;
                $adul_count++;
      
            }
        }
 
        $child_count = 0;
        $pcounter = $search_data['adult_config'] + 1;
         for($aaa=0;$aaa < count($booking_params['Passengers']);$aaa++) {  
            if ($booking_params['Passengers'][$aaa]['PaxType'] == 2) {
                $child_count++;
                $booking_params['Passengers'][$aaa]['CountryCode'] = $booking_params['Passengers'][$aaa]['CountryCode'];
                $exp_pass_date = $booking_params['Passengers'][$aaa]['PassportExpiry'];
                $exp_passport_date =  strtoupper(date("dMy", strtotime($exp_pass_date)));
                $adultDOB = strtoupper(date("dMy", strtotime($booking_params['Passengers'][$aaa]['DateOfBirth'])));
  
                $adultText    = 'P/'.$booking_params['Passengers'][$aaa]['CountryCode'].'/'.$booking_params['Passengers'][$aaa]['PassportNo'].'/'.$booking_params['Passengers'][$aaa]['CountryCode'].'/'.$adultDOB.'/'.($booking_params['Passengers'][$aaa]['Gender']=='1'?'M':'F').'/'.$exp_passport_date.'/'.$booking_params['Passengers'][$aaa]['LastName'].'/'.$booking_params['Passengers'][$aaa]['FirstName'].'/DK-'.$pcounter .'.'.'1';                
                $adultTextName  = $booking_params['Passengers'][$aaa]['LastName'].' '.$booking_params['Passengers'][$aaa]['FirstName'];
                $GivenNameXMLADT = "<GivenName>".$booking_params['Passengers'][$aaa]['FirstName']."</GivenName>";
                if ($booking_params['Passengers'][$aaa]['PassportExpiry'] != "INVALIDIP") {
                    if($AirlineHostedFalse !=''){ 
                        $travellers .= '<Service SSR_Code="DOCS">
                                <PersonName/>
                                <Text>'.$adultText.'</Text>
                                <VendorPrefs><Airline Hosted="'.$psAirlineHostedTrue.'"/></VendorPrefs>
                              </Service>';
                    }
                    if($AirlineHostedTrue !=''){
                        $travellers .= '<Service SSR_Code="DOCS">
                              <PersonName NameNumber="'.$pcounter .'.1"/>
                              <Text>'.$adultText.'</Text>
                              <VendorPrefs><Airline Hosted="'.$psAirlineHostedTrue.'"/></VendorPrefs>
                            </Service>';
                    }
                }
                if(isset($booking_params['Passengers'][$aaa]['MealId'])){
                    $MealsDetails = $this->meal_request_details($booking_params['Passengers'][$aaa]['MealId']);
                }
                else{
                    $MealsDetails = array();
                }
               
                // debug($MealsDetails);exit;
                for($j=0;$j< count($segment_data); $j++){
                    // for($ss=0;$ss< count($segment_data[$j]); $ss++){    
                         if (isset($MealsDetails) && valid_array($MealsDetails)) {
                            //($meals_details[$i][$aaa]); die;
                            $travellers .= '<Service SegmentNumber="'.($j+1).'" SSR_Code="'.$MealsDetails[$j]['Code'].'">
                              <PersonName NameNumber="'.$pcounter .'.1"/>
                              <VendorPrefs>
                                <Airline Hosted="'.$psAirlineHostedTrue.'"/>
                              </VendorPrefs>
                            </Service>';
                        }
                     
                    // }
                }
                $travellers .= '<Service SSR_Code="DOCS">
                         <PersonName/>
                        <Text>DB/'.$adultDOB.'/'.($booking_params['Passengers'][$aaa]['Gender']=="1"?"M":"F").'/'.$booking_params['Passengers'][$aaa]['LastName'].'/'.$booking_params['Passengers'][$aaa]['FirstName'].'/DK-'.$pcounter.'.1</Text>
                        <VendorPrefs><Airline Hosted="false"/></VendorPrefs>
                      </Service>';
                $pcounter++;   
             $a++;    
            }       
           
        }
        $inf_count = 1;
        $adult_count_val = $adul_count-1;
        for($aaa=0;$aaa < count($booking_params['Passengers']);$aaa++) {  
            if ($booking_params['Passengers'][$aaa]['PaxType'] == 3) {         
                $booking_params['Passengers'][$aaa]['CountryCode'] = $booking_params['Passengers'][$aaa]['CountryCode'];
                $exp_pass_date = $booking_params['Passengers'][$aaa]['PassportExpiry'];
                $exp_passport_date =  strtoupper(date("dMy", strtotime($exp_pass_date)));
                $infantDOB = strtoupper(date("dMy", strtotime($booking_params['Passengers'][$aaa]['DateOfBirth'])));
  
                $infText      = $booking_params['Passengers'][$aaa]['LastName'].'/'.$booking_params['Passengers'][$aaa]['FirstName'].'/'.$infantDOB.'';
                $adultTextName  = $booking_params['Passengers'][$aaa]['LastName'].' '.$booking_params['Passengers'][$aaa]['FirstName'];
                $GivenNameXMLADT = "<GivenName>".$booking_params['Passengers'][$aaa]['FirstName']."</GivenName>";

                if($AirlineHostedFalse !=''){ 
                    $travellers .='<Service SSR_Code="INFT">
                          <PersonName NameNumber="'.$inf_count . '.1"/>
                          <Text>'.$infText.'</Text>
                          <VendorPrefs><Airline Hosted="false"/></VendorPrefs>
                        </Service>';
                }
                if($AirlineHostedTrue !=''){
                    $travellers .= '<Service SSR_Code="INFT">
                      <PersonName NameNumber="'.$inf_count. '.1" />
                       <Text>'.$infText.'</Text>
                       <VendorPrefs><Airline Hosted="'.$psAirlineHostedTrue.'"/></VendorPrefs>
                      </Service>';
                }
                $inf_count++;
                $a++;   
                $pcounter++;
            }
    
        }

        $request_params = "<?xml version='1.0' encoding='utf-8'?>
              <soap-env:Envelope xmlns:soap-env='http://schemas.xmlsoap.org/soap/envelope/'>
                  <soap-env:Header>
                      <eb:MessageHeader
                          xmlns:eb='http://www.ebxml.org/namespaces/messageHeader'>
                          <eb:From>
                              <eb:PartyId eb:type='urn:x12.org.IO5:01'>".$this->config['sabre_email']."</eb:PartyId>
                          </eb:From>
                          <eb:To>
                              <eb:PartyId eb:type='urn:x12.org.IO5:01'>webservices.sabre.com</eb:PartyId>
                          </eb:To>
                          <eb:ConversationId>".$this->conversation_id."</eb:ConversationId>
                          <eb:Service>SpecialServiceLLSRQ</eb:Service>
                          <eb:Action>SpecialServiceLLSRQ</eb:Action>
                          <eb:CPAID>".$this->config['ipcc']."</eb:CPAID>
                          <eb:MessageData>
                              <eb:MessageId>".$this->message_id."</eb:MessageId>
                              <eb:Timestamp>".$this->timestamp."</eb:Timestamp>
                              <eb:TimeToLive>".$this->timetolive."</eb:TimeToLive>
                          </eb:MessageData>
                      </eb:MessageHeader>
                      <wsse:Security xmlns:wsse='http://schemas.xmlsoap.org/ws/2002/12/secext'>
                          <wsse:UsernameToken>
                              <wsse:Username>".$this->config['username']."</wsse:Username>
                              <wsse:Password>".$this->config['password']."</wsse:Password>
                              <Organization>".$this->config['ipcc']."</Organization>
                              <Domain>Default</Domain>
                          </wsse:UsernameToken>
                          <wsse:BinarySecurityToken>".$this->api_session_id."</wsse:BinarySecurityToken>
                      </wsse:Security>
                  </soap-env:Header>
                  <soap-env:Body>
                    <SpecialServiceRQ Version='2.0.2' xmlns='http://webservices.sabre.com/sabreXML/2011/10' xmlns:xs='http://www.w3.org/2001/XMLSchema' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' ReturnHostCommand='true'>
                      <SpecialServiceInfo>
                        ".$travellers."
                      </SpecialServiceInfo>
                    </SpecialServiceRQ>
                  </soap-env:Body>
              </soap-env:Envelope>";
         // echo $request_params;exit;     
            $request ['request'] = $request_params;
            $request ['url'] = $this->config['api_url'];
            $request ['remarks'] = 'SpecialRequest(Sabare)';
            $request ['status'] = SUCCESS_STATUS;
            // debug($request);exit;
            return $request;
    }
    private function EndTransaction_Request($booking_params)
    {
        $request_params = 
            '<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:eb="http://www.ebxml.org/namespaces/messageHeader" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:xsd="http://www.w3.org/1999/XMLSchema">
              <SOAP-ENV:Header>
                <eb:MessageHeader SOAP-ENV:mustUnderstand="1" eb:version="1.0">
                  <eb:From>
                 <eb:PartyId eb:type="urn:x12.org.IO5:01">'.$this->config['sabre_email'].'</eb:PartyId>
                  </eb:From>
                  <eb:To>
                    <eb:PartyId type="urn:x12.org:IO5:01">webservices.sabre.com</eb:PartyId>
                  </eb:To>
                  <eb:CPAId>'.$this->config['ipcc'].'</eb:CPAId>
                  <eb:ConversationId>'.$this->conversation_id.'</eb:ConversationId>
                  <eb:Service>EndTransactionLLSRQ</eb:Service>
                  <eb:Action>EndTransactionLLSRQ</eb:Action>
                  <eb:MessageData>
                    <eb:MessageId>mid:'.$this->message_id.'</eb:MessageId>
                    <eb:Timestamp>'.$this->timestamp.'</eb:Timestamp>
                    <eb:TimeToLive>'.$this->timetolive.'</eb:TimeToLive>
                    <eb:Timeout>40</eb:Timeout>
                  </eb:MessageData>
                </eb:MessageHeader>
                <wsse:Security xmlns:wsse="http://schemas.xmlsoap.org/ws/2002/12/secext" xmlns:wsu="http://schemas.xmlsoap.org/ws/2002/12/utility">
                  <wsse:BinarySecurityToken valueType="String" EncodingType="wsse:Base64Binary">' . $this->api_session_id . '</wsse:BinarySecurityToken>
                </wsse:Security>
              </SOAP-ENV:Header>
                <SOAP-ENV:Body>
                 <EndTransactionRQ Version="2.0.1" xmlns="http://webservices.sabre.com/sabreXML/2011/10" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
                <EndTransaction Ind="true">
                </EndTransaction>
              <Source ReceivedFrom="Reservation"/>
            </EndTransactionRQ>
          </SOAP-ENV:Body>
        </SOAP-ENV:Envelope>';
        $request ['request'] = $request_params;
        $request ['url'] = $this->config['api_url'];
        $request ['remarks'] = 'EndTransaction(Sabare)';
        $request ['status'] = SUCCESS_STATUS;
        // debug($request);exit;
        return $request;
  
    }
    private function TravelItineraryReadInfo_Request($pnr)
    {
    
     $request_params = "<?xml version='1.0' encoding='utf-8'?>
                  <soap-env:Envelope xmlns:soap-env='http://schemas.xmlsoap.org/soap/envelope/'>
                      <soap-env:Header>
                          <eb:MessageHeader xmlns:eb='http://www.ebxml.org/namespaces/messageHeader'>
                              <eb:From>
                                  <eb:PartyId eb:type='urn:x12.org.IO5:01'>".$this->config['sabre_email']."</eb:PartyId>
                              </eb:From>
                              <eb:To>
                                  <eb:PartyId eb:type='urn:x12.org.IO5:01'>webservices.sabre.com</eb:PartyId>
                              </eb:To>
                              <eb:ConversationId>".$this->conversation_id."</eb:ConversationId>
                                <eb:Service>TravelItineraryReadLLSRQ</eb:Service>
                                <eb:Action>TravelItineraryReadLLSRQ</eb:Action>
                              <eb:CPAID>".$this->config['ipcc']."</eb:CPAID>
                              <eb:MessageData>
                                  <eb:MessageId>".$this->message_id."</eb:MessageId>
                                  <eb:Timestamp>".$this->timestamp."</eb:Timestamp>
                                  <eb:TimeToLive>".$this->timetolive."</eb:TimeToLive>
                              </eb:MessageData>
                          </eb:MessageHeader>
                          <wsse:Security xmlns:wsse='http://schemas.xmlsoap.org/ws/2002/12/secext'>
                              <wsse:UsernameToken>
                                  <wsse:Username>".$this->config['username']."</wsse:Username>
                                  <wsse:Password>".$this->config['password']."</wsse:Password>
                                  <Organization>".$this->config['ipcc']."</Organization>
                                  <Domain>Default</Domain>
                              </wsse:UsernameToken>
                              <wsse:BinarySecurityToken>".$this->api_session_id."</wsse:BinarySecurityToken>
                          </wsse:Security>
                      </soap-env:Header>
                      <soap-env:Body>
                        <TravelItineraryReadRQ Version='2.2.0' xmlns='http://webservices.sabre.com/sabreXML/2011/10' xmlns:xs='http://www.w3.org/2001/XMLSchema' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance'>
                          <MessagingDetails>
                           <Transaction Code='PNR'/>
                          </MessagingDetails>
                          <UniqueID ID='" . $pnr . "'/>
                        </TravelItineraryReadRQ>                    
                      </soap-env:Body>
                  </soap-env:Envelope>";
        $request ['request'] = $request_params;
        $request ['url'] = $this->config['api_url'];
        $request ['remarks'] = 'TravelReadInfo(Sabare)';
        $request ['status'] = SUCCESS_STATUS;
        // debug($request);exit;
        return $request;
    }
    private function ReserveAirSeatLLS_request($booking_params)
    {
        // debug($booking_params);exit;
            $segment_data = $booking_params['flight_data']['FlightDetails']['Details'];
            // echo $segment_count;exit;
            for($j=0;$j< count($segment_data); $j++){
            for($ss=0;$ss< count($segment_data[$j]); $ss++){   
                for($p=0;$p < count($booking_params['Passengers']);$p++) {
                    if(isset($booking_params['Passengers'][$p]['SeatDynamic'])){
                        $SeatDetails = $this->seat_request_details($booking_params['Passengers'][$p]['SeatDynamic']);
                        // debug($SeatDetails);exit;
                        $seat_number = $SeatDetails[$ss]['SeatNumber'];
                        $seat_number = $SeatDetails[$ss]['RowNumber'].$seat_number;
                        $AirSeatRQ = 
                        "<?xml version='1.0' encoding='utf-8'?>
                            <soap-env:Envelope xmlns:soap-env='http://schemas.xmlsoap.org/soap/envelope/'>
                              <soap-env:Header>
                                  <eb:MessageHeader
                                      xmlns:eb='http://www.ebxml.org/namespaces/messageHeader'>
                                      <eb:From>
                                          <eb:PartyId eb:type='urn:x12.org.IO5:01'>".$this->config['sabre_email']."</eb:PartyId>
                                      </eb:From>
                                      <eb:To>
                                          <eb:PartyId eb:type='urn:x12.org.IO5:01'>webservices.sabre.com</eb:PartyId>
                                      </eb:To>
                                      <eb:ConversationId>".$this->conversation_id."</eb:ConversationId>
                                      <eb:Service eb:type='OTA'>Air</eb:Service>
                                      <eb:Action>AirSeatLLSRQ</eb:Action>
                                      <eb:CPAID>".$this->config['ipcc']."</eb:CPAID>
                                      <eb:MessageData>
                                          <eb:MessageId>".$this->message_id."</eb:MessageId>
                                          <eb:Timestamp>".$this->timestamp."</eb:Timestamp>
                                          <eb:TimeToLive>".$this->timetolive."</eb:TimeToLive>
                                      </eb:MessageData>
                                  </eb:MessageHeader>
                                  <wsse:Security xmlns:wsse='http://schemas.xmlsoap.org/ws/2002/12/secext'>
                                      <wsse:UsernameToken>
                                          <wsse:Username>".$this->config['username']."</wsse:Username>
                                          <wsse:Password>".$this->config['password']."</wsse:Password>
                                          <Organization>".$this->config['ipcc']."</Organization>
                                          <Domain>Default</Domain>
                                      </wsse:UsernameToken>
                                      <wsse:BinarySecurityToken>".$this->api_session_id."</wsse:BinarySecurityToken>
                                  </wsse:Security>
                              </soap-env:Header>
                              <soap-env:Body>
                                  <AirSeatRQ xmlns='http://webservices.sabre.com/sabreXML/2011/10' xmlns:xs='http://www.w3.org/2001/XMLSchema' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' ReturnHostCommand='false' Version='2.0.0'>
                                      <Seats>
                                          <Seat NameNumber='".($p+1).'.1'."' Number='".$seat_number."' SegmentNumber='".($ss+1)."'/>
                                      </Seats>
                                  </AirSeatRQ>
                              </soap-env:Body>
                            </soap-env:Envelope>";
                        $seat_response = $this->process_request($AirSeatRQ, $this->config['api_url'], 'ReservedSeat(Sabare)');
                    // debug($seat_response);
                    }
                   
                    // echo $AirSeatRQ;
                }      
            }

        }
        // exit;   
    }

     /**
     * Seat Details For Flights
     */
    private function seat_request_details($SeatDetails) {
        $seat = array();
        if (valid_array($SeatDetails) == true) {
            foreach ($SeatDetails as $seat_k => $seat_v) {
                if (empty($seat_v) == false) {
                    $seat_data = Common_Flight::read_record($seat_v);
                    if (valid_array($seat_data) == true) {
                        $seat_data = json_decode($seat_data[0], true);
                        $temp_seat = array_values(unserialized_data($seat_data['SeatId']));

                        if (isset($temp_seat[0]['Type'])) {
                            unset($temp_seat[0]['Type']);
                        }

                        $seat[$seat_k] = $temp_seat[0];
                    }
                }
            }
        }
        return $seat;
    }

    /**
     * Extra Services
     * @param unknown_type $request
     */
    public function get_extra_services($request, $search_id) {
       
        $CI = &get_instance();
        $response ['status'] = FAILURE_STATUS; // Status Of Operation
        $response ['message'] = ''; // Message to be returned
        $response ['data'] = array(); // Data to be returned
        $seat_response = $this->seat_request($request, $search_id);
        // debug($seat_response);exit;
        $response ['data']['ExtraServiceDetails']['Seat'] = $seat_response;
        $response ['status'] = SUCCESS_STATUS;
        //$response ['data']['ExtraServiceDetails']['MealPreference'] = $this->getMeals($request);
         $response ['data']['ExtraServiceDetails']['MealPreference']  ='';
        #debug($response);exit;
        return $response;
        // debug($response);exit;
    }
    function getMeals($request) {
        // debug($request);exit;
        $CI = &get_instance();
        $meals_list = $CI->db_cache_api->get_meals_sabre();

      
        $segment_data = $request['FlightDetails']['Details'];
        // debug($segment_data);exit;
        $meals_list_arr = array();
        for($j=0;$j< count($segment_data); $j++){
            $inner_count = count($segment_data[$j]);
            $inner_count = $inner_count-1;
            // echo 'herre'.$inner_count;exit;
            $meals_list_data = array();
            // for($ss=0;$ss< count($segment_data[$j]); $ss++){
            // debug($segment_data[$j][$ss]);
                foreach ($meals_list as $meal_j => $meals) {
                    // debug($meals);exit;
                    $meals_list_data1[0]['Code'] = $meals_list_data[$meal_j]['Code'] = $meals['Code'];
                    $meals_list_data1[0]['Description'] = $meals_list_data[$meal_j]['Description'] = $meals['Description'];
                    $meals_list_data[$meal_j]['Origin'] = $segment_data[$j][0]['Origin']['AirportCode'];
                    $meals_list_data[$meal_j]['Destination'] = $segment_data[$j][$inner_count]['Destination']['AirportCode'];
                    $meals_list_data1[0]['Type'] = 'static';
                    // debug($meals_list_data1);
                    $meals_list_data[$meal_j]['MealId'] = base64_encode(serialize($meals_list_data1));
                    unset($meals_list_data1[$meal_j]);
                }

            // }
             $meals_list_arr[$j] = $meals_list_data;
           
        }
        // exit;
        // debug($meals_list_arr);exit;
        return $meals_list_arr;
        // debug($meals_list_arr);exit;
    }
    public function seat_request($request_data, $search_id) {
        // debug($request);exit;
        $search_data = $this->search_data($search_id);
        $segment_data = $request_data['FlightInfo']['FlightDetails']['Details'];
       
        // debug($request_data);exit;
        $seatmapresponse = array();
        $segment_type = 1;
        $SessionId=$SecurityToken=$SequenceNumber='';
        for($j=0;$j< count($segment_data); $j++){
            for($ss=0;$ss< count($segment_data[$j]); $ss++){
            //if($ss>0){
                 $this->created = $this->getCreateDate();
                $this->nonce = $this->getNoncevalue();
                $this->hashPwd = $this->DigestAlgo($this->password,$this->created,$this->nonce);

                $depature_date = date('dmy',strtotime($segment_data[$j][$ss]['Origin']['DateTime']));
                $arrival_date = date('dmy',strtotime($segment_data[$j][$ss]['Destination']['DateTime']));
                $cabin_class  =$segment_data[$j][$ss]['CabinClass'];
                $OperatorCode = $segment_data[$j][$ss]['OperatorCode'];
                $Origin = $segment_data[$j][$ss]['Origin']['AirportCode'];
                $Destination = $segment_data[$j][$ss]['Destination']['AirportCode'];
                $FlightNumber  = $segment_data[$j][$ss]['FlightNumber'];
                $soapAction = "SMPREQ_17_1_1A";               
                $Header ='<soapenv:Header>
                        <add:MessageID xmlns:add="http://www.w3.org/2005/08/addressing">'.$this->getuuid().'</add:MessageID>
                        <add:Action xmlns:add="http://www.w3.org/2005/08/addressing">http://webservices.amadeus.com/'.$soapAction.'</add:Action>
                        <add:To xmlns:add="http://www.w3.org/2005/08/addressing">'.$this->api_url.'</add:To>
                        <link:TransactionFlowLink xmlns:link="http://wsdl.amadeus.com/2010/06/ws/Link_v1"/>
                        <oas:Security xmlns:oas="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
                        <oas:UsernameToken oas1:Id="UsernameToken-1" xmlns:oas1="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
                        <oas:Username>'.$this->username.'</oas:Username>
                        <oas:Nonce EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary">'.$this->nonce.'</oas:Nonce>
                        <oas:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest">'.$this->hashPwd.'</oas:Password>
                        <oas1:Created>'.$this->created.'</oas1:Created>
                    </oas:UsernameToken>
                    </oas:Security>
                    <AMA_SecurityHostedUser xmlns="http://xml.amadeus.com/2010/06/Security_v1">
                    <UserID AgentDutyCode="'.$this->agent_duty_code.'" RequestorType="'.$this->requestor_type.'" PseudoCityCode="'.$this->pseudo_city_code.'" POS_Type="'.$this->pos_type.'"/>
                    </AMA_SecurityHostedUser>
                </soapenv:Header>';
                
            $seat_request =' <?xml version="1.0" encoding="UTF-8"?>           
                    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:sec="http://xml.amadeus.com/2010/06/Security_v1" xmlns:typ="http://xml.amadeus.com/2010/06/Type">
                    '.$Header.'
                <soapenv:Body>
                <Air_RetrieveSeatMap xmlns="http://xml.amadeus.com/'.$soapAction.'">            
                    <travelProductIdent>
                                    <flightDate>
                                        <departureDate>'.$depature_date.'</departureDate>
                                    </flightDate>
                                    <boardPointDetails>
                                        <trueLocationId>'.$Origin.'</trueLocationId>
                                    </boardPointDetails>
                                    <offpointDetails>
                                        <trueLocationId>'.$Destination.'</trueLocationId>
                                    </offpointDetails>
                                    <companyDetails>
                                        <marketingCompany>'.$OperatorCode.'</marketingCompany>
                                    </companyDetails>
                                    <flightIdentification>
                                        <flightNumber>'.$FlightNumber.'</flightNumber>
                                        <bookingClass>'.$cabin_class.'</bookingClass>
                                    </flightIdentification>
                                </travelProductIdent>
                            <seatRequestParameters>
                                <processingIndicator>FT</processingIndicator>
                            </seatRequestParameters>
                        </Air_RetrieveSeatMap>
                </soapenv:Body>
                </soapenv:Envelope>';           
            

                    // echo $request_params;exit;
            
            $soap_url = $this->soap_url.$soapAction;
            $seat_response = $this->process_request($seat_request,$this->api_url,'SeatMapReq(amadeus)',$soap_url);
           # echo $seat_response.'<br/>';
            #echo "=====";
           # $seat_response = file_get_contents(FCPATH.'amadeuslogs/'.'SeatMapReq(amadeus)_Res20190225_160125_4514.xml');

                if($seat_response){
                    $seat_response = $this->xml2array($seat_response);

                    if(isset($seat_response['soapenv:Envelope']['soapenv:Header'])){
                        $SessionId = $seat_response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SessionId'];    
                        $SecurityToken = $seat_response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SecurityToken'];
                        $SequenceNumber =  $seat_response['soapenv:Envelope']['soapenv:Header']['awsse:Session']['awsse:SequenceNumber'];
                        $SequenceNumber = ($SequenceNumber+1);
                        // $this->Security_SignOut($SessionId,$SequenceNumber,$SecurityToken);
                    }
                    #echo 'SequenceNumber..'.$SequenceNumber.'<br/>';

                    if(isset($seat_response['soapenv:Envelope']['soapenv:Body']['Air_RetrieveSeatMapReply']['seatmapInformation'])){
                       // echo "here";
                        //$seatmapresponse[$j]['SeatColumn'] = 1;
                        $seat_format_response  = $this->format_seat_mapping_response($seat_response,$segment_data[$j][$ss],$segment_type);
                       // $seatmapresponse[] = $seat_format_response['seat_data'];
                       $seatmapresponse[$ss]['SeatDetails'] = $seat_format_response['seat_data'];
                       $seatmapresponse[$ss]['SeatColumn'] = $seat_format_response['available_columns']; 
                    }else{
                        $seatmapresponse[] = array();
                    }
                 }

            //}
                 $segment_type++;
            }
        }
        return $seatmapresponse;
    }
    /*Amadeus Seat Format*/
    private function format_seat_mapping_response($seat_response,$segment_data,$segment_type){
       
        $seatmapInformation = $seat_response['soapenv:Envelope']['soapenv:Body']['Air_RetrieveSeatMapReply']['seatmapInformation'];
        
        $CabinClass_arr = force_multple_data_format($seatmapInformation['cabin']);
        
        $rowDetails = force_multple_data_format($seat_response['soapenv:Envelope']['soapenv:Body']['Air_RetrieveSeatMapReply']['seatmapInformation']['row']);
       

        // debug($seatmapInformation);  
        // echo "=====";          
        //$alpha_char = array('A'=>'A','B'=>'B','C'=>'C','D'=>'D','E'=>'E','F'=>'F','G'=>'G','H'=>'H','I'=>'I','J'=>'J','K'=>'K','L'=>'L','M'=>'M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        
        $airRowDetails = array();
        $compartmentDetails_Row = array();
        if($CabinClass_arr){
            $available_columns = array();
            foreach ($CabinClass_arr as $c_key => $c_value) {
                if(isset($c_value['compartmentDetails']['seatRowRange']['number'])){
                    $start_row=$c_value['compartmentDetails']['seatRowRange']['number'][0];
                    $end_row = $c_value['compartmentDetails']['seatRowRange']['number'][1];
                    if(isset($c_value['compartmentDetails']['defaultSeatOccupation'])){
                         $row_key = $start_row.'_'.$end_row;
                         $compartmentDetails_Row[$row_key]['defaultSeatOccupation'] =$c_value['compartmentDetails']['defaultSeatOccupation'];
                    }
                   
 
                }
                if(isset($c_value['compartmentDetails']['columnDetails'])){
                    foreach ($c_value['compartmentDetails']['columnDetails'] as $clkey => $clvalue) { 
                        if(!in_array($clvalue['seatColumn'],$available_columns)){
                            $available_columns[] = $clvalue['seatColumn'];
                        }                       
                        
                    }
                    $compartmentDetails_Row[$row_key]['columnDetails'] = $c_value['compartmentDetails']['columnDetails'];
                    
                }
            }
            
        } 
        asort($available_columns);        
        if($rowDetails){
            foreach ($rowDetails as $r_key => $r_value) {
                  $Row_Number = $r_value['rowDetails']['seatRowNumber'];

                if(isset($r_value['rowDetails'])){                    
                    $airSeatMapDetails  =array();
                    if(isset($r_value['rowDetails']['seatOccupationDetails'])){
                          $airSeatMapDetails=$this->seat_format_row($r_key,$r_value,$segment_data,$compartmentDetails_Row,$available_columns,$segment_type);  
                    }else{
                        $airSeatMapDetails = array();
                        //need to take from compartment details
                        $Default_compartment_details = $this->checking_compartment_data($r_value,$compartmentDetails_Row);
                       
                        $r_value['rowDetails']['seatOccupationDetails'] =$Default_compartment_details['seatOccupationDetails'];  
                        $airSeatMapDetails=$this->seat_format_row($r_key,$r_value,$segment_data,$compartmentDetails_Row,$available_columns,$segment_type);
                    }                
                    $airSeatMapDetails_arr = array();
                    //to insert missing columns
                   # debug($available_columns);exit;
                    foreach ($available_columns as $av_key => $av_value) {
                       if(isset($airSeatMapDetails[$av_value])){
                          $airSeatMapDetails_arr[] =$airSeatMapDetails[$av_value];
                       }else{
                            $missin_cl_update['AvailablityType'] =0;
                            $missin_cl_update['Destination'] =  $segment_data['Destination']['AirportCode'];
                            $missin_cl_update['FlightNumber'] =$segment_data['FlightNumber']; 
                            $missin_cl_update['Origin'] = $segment_data['Origin']['AirportCode'];
                            $missin_cl_update['Price'] = 0;
                            $missin_cl_update['RowNumber'] = $Row_Number;
                            $missin_cl_update['SeatColumn'] = $av_value;
                            $missin_cl_update['SeatNumber'] =$Row_Number.$av_value;
                            $key['key'][0]['Code'] = $missin_cl_update['SeatNumber'];
                            $key['key'][0]['Type'] ='dynamic';
                            $key['key'][0]['Description'] ='no row';
                            $key['key'][0]['Segement_Type'] =$segment_type;

                            $ResultToken = serialized_data($key['key']);
                            $missin_cl_update['SeatId'] = $ResultToken;
                            $airSeatMapDetails_arr[] = $missin_cl_update;
                       }
                    }
                    
                   
                    $airRowDetails[$r_key] =$airSeatMapDetails_arr;
                }
            }
        }
       
        $final_seat_array = array('available_columns'=>$available_columns,'seat_data'=>$airRowDetails);       
       #debug($final_seat_array);exit;
        return $final_seat_array;
        
    }
   
    /*Taking the compartment data*/
    private function checking_compartment_data($r_value,$compartmentDetails_Row){
        $Default_compartment_details = array();
        $Row_Number = $r_value['rowDetails']['seatRowNumber'];
        $seat_char = '';
        if(isset($r_value['rowDetails']['rowCharacteristicDetails'])){
           if(isset($r_value['rowDetails']['rowCharacteristicDetails']['rowCharacteristic'])){
                 $seat_char = $r_value['rowDetails']['rowCharacteristicDetails']['rowCharacteristic'];
            }
        }
        foreach ($compartmentDetails_Row as $ch_key => $ch_value) {
           $explode_arr  = explode("_",$ch_key);
            for ($i=$explode_arr[0]; $i <=$explode_arr[1] ; $i++) { 
                
                if($i==$Row_Number){
                    
                    foreach ($ch_value['columnDetails'] as $cc_key => $cc_value) {
                        $ch_value['columnDetails'][$cc_key]['seatOccupation'] = $ch_value['defaultSeatOccupation'];
                        if($seat_char){
                            $ch_value['columnDetails'][$cc_key]['seatCharacteristic'] = $seat_char;    
                        }else{
                            $ch_value['columnDetails'][$cc_key]['seatCharacteristic'] = $cc_value['description'];
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
    private function seat_format_row($r_key,$r_value,$segment_data,$compartmentDetails_Row,$available_columns,$segment_no){
        $airSeatMapDetails  =array();        
        $r_value['rowDetails']['seatOccupationDetails'] = force_multple_data_format($r_value['rowDetails']['seatOccupationDetails']);
        foreach ($r_value['rowDetails']['seatOccupationDetails'] as $rcl_key => $rcl_value) {          
            $Row_Number = $r_value['rowDetails']['seatRowNumber'];
            $airSeatMapDetails[$rcl_value['seatColumn']]['AirlineCode'] = $segment_data['OperatorCode'];
            //Availability type 
            //0-blocked,1 - available 2- space
            $available_seat = 0;
            $is_available =0;
             $check_character = array();
            if(!isset($rcl_value['seatOccupation'])){
                $Default_compartment_details = array();
                $Default_compartment_details = $this->checking_compartment_data($r_value,$compartmentDetails_Row);                                              
               //$is_available = $available_seat = $this->seat_occupation($Default_compartment_details['defaultSeatOccupation']);
               # debug($Default_compartment_details);
               $rcl_value['seatOccupation'] = $Default_compartment_details['defaultSeatOccupation'];

            }
            $is_char='';
            if(isset($rcl_value['seatOccupation'])){
                $is_available = $this->seat_occupation($rcl_value['seatOccupation']);

                if($is_available){

                    if(isset($rcl_value['seatCharacteristic'])){
                         if(is_array($rcl_value['seatCharacteristic'])){
                           
                            foreach ($rcl_value['seatCharacteristic'] as $sc_key => $sc_value) {
                              $is_char = $this->seat_characteristics($sc_value);

                                if($is_char=='seat_availble'||$is_char=='infant_adult_seat' || $is_char =='center_seat'                        
                                    ){
                                    //$available_seat = 1;
                                    $check_character[] = 1;
                                }else{
                                    $check_character[]=0;
                                }
                                
                            }
                        }else{
                            $is_char = $this->seat_characteristics($rcl_value['seatCharacteristic']);

                           
                            if($is_char=='seat_availble'||$is_char=='infant_adult_seat' || $is_char =='center_seat'){
                                    $check_character[] = 1;
                                }else{
                                    $check_character[]=0;
                                }

                        }
                    }else{
                       
                        foreach ($Default_compartment_details['seatOccupationDetails'] as $char_key => $char_value) {
                            
                            if($char_value['seatColumn']==$rcl_value['seatColumn']){
                                if(isset($char_value['description'])){
                                    $is_char = $this->seat_characteristics($char_value['description']);
                                }else{
                                    $is_char ='blocked';
                                }
                                
                                if($is_char=='seat_availble'||$is_char=='infant_adult_seat' || $is_char =='center_seat'){
                                        $check_character[] = 1;
                                    }else{
                                        $check_character[] = 0;
                                    }
                                }
                        }
                        
                    }
                   
                }else{
                    $check_character[]=0;
                }
            }
           
            $check_character = array_unique($check_character);
            if(in_array(0,$check_character)){
                $available_seat =0;
            }else{
                $available_seat =1;
            }
            
            $airSeatMapDetails[$rcl_value['seatColumn']]['AvailablityType'] =$available_seat;
            $airSeatMapDetails[$rcl_value['seatColumn']]['Destination'] =  $segment_data['Destination']['AirportCode'];
            $airSeatMapDetails[$rcl_value['seatColumn']]['FlightNumber'] =$segment_data['FlightNumber']; 
            $airSeatMapDetails[$rcl_value['seatColumn']]['Origin'] = $segment_data['Origin']['AirportCode'];
            $airSeatMapDetails[$rcl_value['seatColumn']]['Price'] = 0;
            $airSeatMapDetails[$rcl_value['seatColumn']]['RowNumber'] = $Row_Number;
            $airSeatMapDetails[$rcl_value['seatColumn']]['SeatColumn'] = $rcl_value['seatColumn'];
            $airSeatMapDetails[$rcl_value['seatColumn']]['SeatNumber'] =$Row_Number.$rcl_value['seatColumn'];
             $key['key'][0]['Code'] = $airSeatMapDetails[$rcl_value['seatColumn']]['SeatNumber'];
             $key['key'][0]['Type'] ='dynamic';
             $key['key'][0]['Description'] =$is_char;    
             $key['key'][0]['Segment_Type'] = $segment_no;         
            $ResultToken = serialized_data($key['key']);
            $airSeatMapDetails[$rcl_key]['SeatId'] = $ResultToken;
        }
        return $airSeatMapDetails;
    }
    /*Amadeus Seat Occupation Details*/
    private function seat_occupation($code){
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
                $avaiable_status =0;
                break;
        }
        return $avaiable_status;
    }
    /*Seat Characteristics*/
    private function seat_characteristics($value){
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
                $seat_characteristics = "blocked";//no seat at this location
            break;
             case '9':
            $seat_characteristics = "center_seat";//available
            break;
             case '1A':
            $seat_characteristics = "seat_availble";//seat_available for adult
            break;
             case '1B':
            $seat_characteristics = "blocked";//Seat not available for medical.
            break;
            case '1C':
            $seat_characteristics = "blocked";// Seat not available for unaccompanied minor.
            break;
             case '1D':
            $seat_characteristics = "blocked";//    Restricted reclined seat.
            break;
             case '1M':
            $seat_characteristics = "seat_availble";//Seat with movie view
            break;
             case '1W':
            $seat_characteristics = "seat_availble";//Window seat without window.
            break;
             case '3A':
            $seat_characteristics = "seat_availble";// Individual video screen - No choice of movie.
            break;
             case '6A':
            $seat_characteristics = "seat_availble";//In front of galley seat.
            break;
             case '6B':
            $seat_characteristics = "seat_availble";//Behind galley seat.
            break;
             case '7A':
            $seat_characteristics = "blocked";//In front of toilet seat.
            break;
             case '7B':
            $seat_characteristics = "blocked";//Behind toilet seat.
            break;
             case 'A':
            $seat_characteristics = "seat_availble";//Asile
            break;
             case 'AB':
            $seat_characteristics = "seat_availble";//  Seat adjacent to bar.
            break;
             case 'AC':
            $seat_characteristics = "seat_availble";//Seat adjacent to closet.
            break;
            case 'AG':
            $seat_characteristics = "seat_availble";//Seat adjacent to galley.
            break; 
            case 'AJ':
            $seat_characteristics = "seat_availble";//Adjacent aisle seat.
            break;
            case 'AL':
            $seat_characteristics = "seat_availble";//Seat adjacent to lavatory.
            break;
            case 'AM':
            $seat_characteristics = "seat_availble";//Individual movie screen - No choice of movie selection.
            break;
            case 'AS':
            $seat_characteristics = "seat_availble";//Individual airphone.
            break;
            case 'AT':
            $seat_characteristics = "seat_availble";//Seat adjacent to table.
            break;
            case 'AU':
            $seat_characteristics = "seat_availble";//Seat adjacent to stairs to upper deck.seat.
            break;
            case 'B':
            $seat_characteristics = "seat_availble";//Seat with bassinet facility.seat.
            break;
            case 'C':
            $seat_characteristics = "seat_availble";//Crew seat. facility.seat.
            break;
            case 'CH':
            $seat_characteristics = "blocked";//Chargeable seat.facility.seat.
            break;
            case 'DE':
            $seat_characteristics = "blocked";//Seat suitable for deportee.
            break;
            case 'EC':
            $seat_characteristics = "blocked";//    Electronic connection for laptop or Fax machine.
            break;
            case 'EK':
            $seat_characteristics = "seat_availble";//Economy comfort seat.
            break;
            case 'H':
            $seat_characteristics = "blocked";//Seat with facility for handicapped/incapacitated passenger.
            break;
            case 'I':
            $seat_characteristics = "seat_availble";//Seat suitable for adult with infant.
            break;
              case 'IE':
            $seat_characteristics = "blocked";// Seat not suitable for child.
            break;
              case 'J':
            $seat_characteristics = "seat_availble";//Rear facing seat.
            break;
            case 'K':
            $seat_characteristics = "blocked";//Bulkhead seat.
            break;
            case 'KA':
            $seat_characteristics = "blocked";//Bulkhead seat with movie screen.
            break;
            case 'L':
            $seat_characteristics ="seat_availble";//  Leg space seat.
            break;
            case 'LS':
            $seat_characteristics ="seat_availble";// Left side of aircraft.
            break;
            case 'M':
            $seat_characteristics ="seat_availble";//  Seat without a movie view.
            break;
            case 'N':
            $seat_characteristics ="blocked";//  NonSmokeing seat
            break;
            case 'O':
            $seat_characteristics ="blocked";// Preferential seat.
            break;
            case 'OW':
            $seat_characteristics ="blocked";// Overwing seat.
            break;
            case 'PC':
            $seat_characteristics ="blocked";//Pet cabin.
            break;
            case 'Q':
            $seat_characteristics ="blocked";//Seat in a quiet zone.
            break;
            case 'RS':
            $seat_characteristics ="seat_availble";//Right side of aircraft.
            break;
            case 'S':
            $seat_characteristics ="blocked";// Smoking seat.
            break;
            case 'U':
            $seat_characteristics ="blocked";//Seat suitable for unaccompanied minor.
            break;
            case 'UP':
            $seat_characteristics ="seat_availble";// Upper deck seat.
            break;
            case 'V':
            $seat_characteristics ="seat_availble";// Seat to be left vacant or last offered.
            break;
            case 'W':
            $seat_characteristics ="seat_availble";// Window seat.
            break;
            case 'WA':
            $seat_characteristics ="seat_availble";// Window and Aisle together.
            break;
            case 'X':
            $seat_characteristics ="blocked";// No facility seat (indifferent seat).
            break;
        }
        return $seat_characteristics;
    }
   

    private function seatmapformating_old($SeatmapRS, $segment_data)
    {
        // debug($segment_data);exit;
        $airSeatMapDeatils = "";
        // debug($SeatmapRS);exit;
        if (isset($SeatmapRS['soap-env:Envelope']['soap-env:Body']['ns6:EnhancedSeatMapRS']['ns4:ApplicationResults']['@attributes']['status'])){
            $air_map_status = $SeatmapRS['soap-env:Envelope']['soap-env:Body']['ns6:EnhancedSeatMapRS']['ns4:ApplicationResults']['@attributes']['status'];
            if($air_map_status == "Complete"){
              $air_map_data = $SeatmapRS['soap-env:Envelope']['soap-env:Body']['ns6:EnhancedSeatMapRS']['ns6:SeatMap']; 
            }else{
              $air_map_data = "";
            }
        }else if(isset($SeatmapRS['soap-env:Envelope']['soap-env:Body']['EnhancedSeatMapRS']['ns3:ApplicationResults']['@attributes']['status'])){
            $air_map_status = $SeatmapRS['soap-env:Envelope']['soap-env:Body']['EnhancedSeatMapRS']['ns3:ApplicationResults']['@attributes']['status'];
            if($air_map_status == "Complete"){
                $air_map_data = $SeatmapRS['soap-env:Envelope']['soap-env:Body']['EnhancedSeatMapRS']['SeatMap']; 
            }else{
                $air_map_data = "";
            }
        }else{
            $air_map_data = "";
        }
        // debug($air_map_data);exit;
        if($air_map_data !=''){
            if(isset($air_map_data['ns6:Cabin'])){
                $CabinDetails1 = $air_map_data['ns6:Cabin'];
            }else{
                $CabinDetails1 = $air_map_data['Cabin'];
            }
            if(isset($CabinDetails1[0])){
                $CabinDetails = $CabinDetails1;
            }else{
                $CabinDetails[0] = $CabinDetails1;
            }
            $airSeatMapDeatils = "";
            foreach($CabinDetails as $c => $cabin){
                // // $airSeatMapDeatils['cabin']['firstRow'][$c]         = $cabin['@attributes']['firstRow'];
                // // $airSeatMapDeatils['cabin']['lastRow'][$c]          = $cabin['@attributes']['lastRow'];
                // // $airSeatMapDeatils['cabin']['classLocation'][$c]      = @$cabin['@attributes']['classLocation'];
                // // $airSeatMapDeatils['cabin']['seatOccupationDefault'][$c]  = $cabin['@attributes']['seatOccupationDefault'];
                // if(isset($cabin['ns6:Column'])){
                //     $cabinColumnDetails = $cabin['ns6:Column'];
                // }else{
                //     $cabinColumnDetails = $cabin['Column'];
                // }
         
                // foreach($cabinColumnDetails as $cd => $ColumnDetails){
                //     if(isset($ColumnDetails['ns6:Column']['value'])){
                //         $airSeatMapDeatils['cabin']['seatColumn'][$cd]       = $ColumnDetails['ns6:Column']['value'];
                //         $airSeatMapDeatils['cabin']['columnCharacteristic'][$cd] = @$ColumnDetails['ns6:Characteristics']['value'];
                //     }else{
                //         $airSeatMapDeatils['cabin']['seatColumn'][$cd]       = $ColumnDetails['Column'];
                //         $airSeatMapDeatils['cabin']['columnCharacteristic'][$cd] = @$ColumnDetails['Characteristics'];
                //     }
                // }
                    
                if(isset($cabin['ns6:Row'])){
                    $row_details = $cabin['ns6:Row'];
                }else{
                    $row_details = $cabin['Row'];
                }
       
                foreach($row_details as $r => $row)
                {
                    if(isset($row['ns6:RowNumber']['value'])){
                        // $airSeatMapDeatils['row'][$r]['seatRowNumber'] = $row['ns6:RowNumber'];
                    }else{
                        // $airSeatMapDeatils['row'][$r]['seatRowNumber'] = $row['RowNumber'];
                    }
                    if(isset($row['rowDetails']['rowCharacteristicsDetails'])) {
                        $airSeatMapDeatils['row'][$r]['rowCharacteristicsDetails'] = $row['rowDetails']['rowCharacteristicsDetails']['rowCharacteristic'];
                    }
                    if(isset($row['ns6:Seat'])){
                        $rowDetails = $row['ns6:Seat'];
                        foreach($rowDetails as $s => $seat){

                            $airSeatMapDeatils['row'][$r]['seatColumn'][$s] = $seat['ns6:Number']['value'];
                            if(isset($seat['ns6:Limitations']['ns6:Detail']['value'])){
                                $airSeatMapDeatils['row'][$r]['Limitations'][$s] = $seat['ns6:Limitations']['ns6:Detail']['value'];
                            }
                          
                            if(isset($seat['ns6:Facilities']['ns6:Detail']['value'])){
                                $airSeatMapDeatils['row'][$r]['Facilities'][$s] = $seat['ns6:Facilities']['ns6:Detail']['value'];
                            }
                            if(isset($seat['ns6:Price']['ns6:TotalAmount']['value'])){
                                $airSeatMapDeatils['row'][$r]['TotalAmount'][$s] = $seat['ns6:Price']['ns6:TotalAmount']['value'];
                            }else{
                                $airSeatMapDeatils['row'][$r]['TotalAmount'][$s] = 0;
                            }
                          
                            if(isset($seat['attr']['occupiedInd'])){
                                $airSeatMapDeatils['row'][$r]['occupiedInd'][$s]          = $seat['attr']['occupiedInd'];
                                $airSeatMapDeatils['row'][$r]['inoperativeInd'][$s]       = $seat['attr']['inoperativeInd'];
                                $airSeatMapDeatils['row'][$r]['premiumInd'][$s]           = $seat['attr']['premiumInd'];
                                $airSeatMapDeatils['row'][$r]['chargeableInd'][$s]        = $seat['attr']['chargeableInd'];
                                $airSeatMapDeatils['row'][$r]['exitRowInd'][$s]           = $seat['attr']['exitRowInd'];
                                $airSeatMapDeatils['row'][$r]['restrictedReclineInd'][$s] = $seat['attr']['restrictedReclineInd'];
                                $airSeatMapDeatils['row'][$r]['noInfantInd'][$s]          = $seat['attr']['noInfantInd'];
                            }
                        }
                    }else if(isset($row['Seat'])){
                         // echo 'hererre I am';exit;
                        $rowDetails = $row['Seat'];
                        $rowDetails = force_multple_data_format($rowDetails);
                        // $colum_array = array('A','B','C','D','E','F');
                        foreach($rowDetails as $s => $seat){
                            // debug($seat);
                                // $exit_column[] = $key_data[$s]['SeatNumber'];
                            // echo 'herrer I am'.$seat['@attributes']['occupiedInd'];exit;
                                $airSeatMapDeatils[$r][$s]['SeatNumber'] = $key_data[$s]['SeatNumber'] =  $key_data[$s]['Code'] = $seat['Number'];
                                $airSeatMapDeatils[$r][$s]['RowNumber'] = $key_data[$s]['RowNumber'] = $row['RowNumber'];
                                $airSeatMapDeatils[$r][$s]['Origin'] = $key_data[$s]['Origin'] = $segment_data['Origin']['AirportCode'];
                                $airSeatMapDeatils[$r][$s]['Destination'] = $key_data[$s]['Destination'] =$segment_data['Destination']['AirportCode'];
                                $airSeatMapDeatils[$r][$s]['AirlineCode'] = $key_data[$s]['AirlineCode'] = $segment_data['OperatorCode'];
                                $airSeatMapDeatils[$r][$s]['FlightNumber'] = $key_data[$s]['FlightNumber'] = $segment_data['FlightNumber'];
                               
                                if(isset($seat['Price']['TotalAmount']['@value'])){
                                    $airSeatMapDeatils[$r][$s]['Price'] = $key_data[$s]['Price'] = $seat['Price']['TotalAmount']['@value'];
                                }
                                else{
                                    $airSeatMapDeatils[$r][$s]['Price'] = $key_data[$s]['Price'] = 0;  
                                }
                                if(isset($seat['@attributes']['occupiedInd']) && ($seat['@attributes']['occupiedInd'] == 'false')){
                                    
                                    $airSeatMapDeatils[$r][$s]['AvailablityType'] = $key_data[$s]['AvailablityType'] = 1; 
                                }
                                else{
                                    $airSeatMapDeatils[$r][$s]['AvailablityType'] = $key_data[$s]['AvailablityType'] = 0;  
                                }
                                $key_data[$s]['Type'] = 'dynamic';
                                // debug($airSeatMapDeatils);exit;
                                $seat_id = serialized_data($key_data);
                                $airSeatMapDeatils[$r][$s]['SeatId'] = $seat_id;


                            
                            // if(isset($seat['Limitations']['Detail']['value'])){
                            //     $airSeatMapDeatils['row'][$r]['Limitations'][$s] = $seat['Limitations']['Detail']['value'];
                            // }
                            // if(isset($seat['Facilities']['Detail']['value'])){
                            //     $airSeatMapDeatils['row'][$r]['Facilities'][$s] = $seat['Facilities']['Detail']['value'];
                            // }
                            // if(isset($seat['Price']['TotalAmount']['value'])){
                            //     $airSeatMapDeatils['row'][$r]['TotalAmount'][$s] = $seat['Price']['TotalAmount']['value'];
                            // }else{
                            //     $airSeatMapDeatils['row'][$r]['TotalAmount'][$s] = 0;
                            // }
                      
                            // if(isset($seat['attr']['occupiedInd'])){
                            //     $airSeatMapDeatils['row'][$r]['occupiedInd'][$s]          = $seat['attr']['occupiedInd'];
                            //     $airSeatMapDeatils['row'][$r]['inoperativeInd'][$s]       = $seat['attr']['inoperativeInd'];
                            //     $airSeatMapDeatils['row'][$r]['premiumInd'][$s]           = $seat['attr']['premiumInd'];
                            //     $airSeatMapDeatils['row'][$r]['chargeableInd'][$s]        = $seat['attr']['chargeableInd'];
                            //     $airSeatMapDeatils['row'][$r]['exitRowInd'][$s]           = $seat['attr']['exitRowInd'];
                            //     $airSeatMapDeatils['row'][$r]['restrictedReclineInd'][$s] = $seat['attr']['restrictedReclineInd'];
                            //     $airSeatMapDeatils['row'][$r]['noInfantInd'][$s]          = $seat['attr']['noInfantInd'];
                            // }
                            unset($key_data);
                        }
                    }
                } 
                       
            }
        }
        // debug($airSeatMapDeatils);exit;
       return $airSeatMapDeatils;
    }

    /**
     * Meal Details 
     */
    private function meal_request_details($MealDetails) {

        $meal = array();
        if (valid_array($MealDetails) == true) {
            foreach ($MealDetails as $meal_k => $meal_v) {
                if (empty($meal_v) == false) {
                    $meal_data = Common_Flight::read_record($meal_v);

                    if (valid_array($meal_data) == true) {
                        $meal_data = json_decode($meal_data[0], true);
                        $temp_meal = array_values(unserialized_data($meal_data['MealId']));
                        if (isset($temp_meal[0]['Type'])) {
                            unset($temp_meal[0]['Type']);
                        }
                        $meal[$meal_k] = $temp_meal[0];
                    }
                }
            }
        }
        // debug($meal);exit;
        return $meal;
    }
      /**
     * Process Cancel Booking
     * Online Cancellation
     */
    public function cancel_booking($request) {

        $response ['status'] = FAILURE_STATUS; // Status Of Operation
        $response ['message'] = ''; // Message to be returned
        $response ['data'] = array(); // Data to be returned

        $app_reference = $request['AppReference'];
        $sequence_number = $request['SequenceNumber'];
        $IsFullBookingCancel = $request['IsFullBookingCancel'];
        $ticket_ids = $request['TicketId'];
        // debug($request);exit;
        $elgible_for_ticket_cancellation = $this->CI->common_flight->elgible_for_ticket_cancellation($app_reference, $sequence_number, $ticket_ids, $IsFullBookingCancel, $this->booking_source);
        // debug($elgible_for_ticket_cancellation);exit;
        if ($elgible_for_ticket_cancellation['status'] == SUCCESS_STATUS) {
            $booking_details = $this->CI->flight_model->get_flight_booking_transaction_details($app_reference, $sequence_number, $this->booking_source);
            $booking_details = $booking_details['data'];
            $booking_transaction_details = $booking_details['booking_transaction_details'][0];
            $flight_booking_transaction_details_origin = $booking_transaction_details['origin'];

            $request_params = $booking_details;
            $request_params['passenger_origins'] = $ticket_ids;
            $request_params['IsFullBookingCancel'] = $IsFullBookingCancel;
            //debug($request_params);exit;
            //SendChange Request
            $send_change_request = $this->send_change_request($request_params);
            if ($send_change_request['status'] == SUCCESS_STATUS) {
                $response ['status'] = SUCCESS_STATUS;
                $response ['message'] = 'Cancellation Request is processing';
                $send_change_response = $send_change_request['data']['send_change_response'];
                $passenger_origin = $request_params['passenger_origins'];
                foreach ($passenger_origin as $origin) {
                    $this->CI->common_flight->update_ticket_cancel_status($app_reference, $sequence_number, $origin);
                }
            }
            else {
                $response ['message'] = $send_change_request['message'];
            }
        }
        else {
            $response ['message'] = $elgible_for_ticket_cancellation['message'];
        }
        return $response;
    }
     /**
     * Send ChangeRequest
     * @param unknown_type $booking_details
     * //ChangeRequestStatus: NotSet = 0,Unassigned = 1,Assigned = 2,Acknowledged = 3,Completed = 4,Rejected = 5,Closed = 6,Pending = 7,Other = 8
     */
    private function send_change_request($request_params) {
        $response ['status'] = FAILURE_STATUS; // Status Of Operation
        $response ['message'] = ''; // Message to be returned
        $response ['data'] = array(); // Data to be returned
        $send_change_request = $this->format_send_change_request($request_params);
        if ($send_change_request['status'] == SUCCESS_STATUS) {
            $send_change_response = Converter::createArray($send_change_request['cancel_response']);
            // debug($send_change_response);
            if(valid_array($send_change_response) && $send_change_response['soap-env:Envelope']['soap-env:Body']['OTA_CancelRS']['stl:ApplicationResults']['@attributes']['status'] == 'Complete'){
                $response ['status'] = SUCCESS_STATUS;
                $response ['data']['send_change_response'] = $send_change_response;
            }
            else{
                $error_message = '';
                if (isset($send_change_response['SOAP:Envelope']['SOAP:Body']['SOAP:Fault']['faultstring'])) {
                    $error_message = $send_change_response['SOAP:Envelope']['SOAP:Body']['SOAP:Fault']['faultstring'];
                }
                if (empty($error_message) == true) {
                    $error_message = 'Cancellation Failed';
                }
                $response ['message'] = $error_message;
            }
        }
        else {
            $response ['status'] = FAILURE_STATUS;
        }
        // echo 'herer I am';
        // debug($response);exit;
        return $response;
    }
    /**
     * Forms the SendChangeRequest
     * @param unknown_type $request
     */
    private function format_send_change_request($params) {
        // debug($params);exit;
        // echo 'herrer I am';exit;
        $booking_transaction_details = $params['booking_transaction_details'][0];
        $pnr = trim($booking_transaction_details['pnr']);
        $travel_read_itinerary_request = $this->TravelItineraryReadInfo_Request($pnr);
        $this->process_request($travel_read_itinerary_request ['request'], $travel_read_itinerary_request ['url'], $travel_read_itinerary_request ['remarks']);    
        $cancel_request = $this->OTA_CancelRQ();
        // debug($cancel_request);exit;
        $cancel_response = $this->process_request($cancel_request ['request'], $cancel_request ['url'], $cancel_request ['remarks']);    
        $end_transaction_request = $this->EndTransaction_Request($pnr);
        $this->process_request($end_transaction_request ['request'], $end_transaction_request ['url'], $end_transaction_request ['remarks']);    
        $request ['status'] = SUCCESS_STATUS;
        $request ['cancel_response'] = $cancel_response;
        return $request;
    }
    function OTA_CancelRQ(){
        $request_params = "<?xml version='1.0' encoding='utf-8'?>
                  <soap-env:Envelope xmlns:soap-env='http://schemas.xmlsoap.org/soap/envelope/'>
                      <soap-env:Header>
                          <eb:MessageHeader xmlns:eb='http://www.ebxml.org/namespaces/messageHeader'>
                              <eb:From>
                                  <eb:PartyId eb:type='urn:x12.org.IO5:01'>".$this->config['sabre_email']."</eb:PartyId>
                              </eb:From>
                              <eb:To>
                                  <eb:PartyId eb:type='urn:x12.org.IO5:01'>webservices.sabre.com</eb:PartyId>
                              </eb:To>
                              <eb:ConversationId>".$this->conversation_id."</eb:ConversationId>
                                <eb:Service>OTA_CancelLLSRQ</eb:Service>
                                <eb:Action>OTA_CancelLLSRQ</eb:Action>
                              <eb:CPAID>".$this->config['ipcc']."</eb:CPAID>
                              <eb:MessageData>
                                  <eb:MessageId>".$this->message_id."</eb:MessageId>
                                  <eb:Timestamp>".$this->timestamp."</eb:Timestamp>
                                  <eb:TimeToLive>".$this->timetolive."</eb:TimeToLive>
                              </eb:MessageData>
                          </eb:MessageHeader>
                          <wsse:Security xmlns:wsse='http://schemas.xmlsoap.org/ws/2002/12/secext'>
                              <wsse:UsernameToken>
                                  <wsse:Username>".$this->config['username']."</wsse:Username>
                                  <wsse:Password>".$this->config['password']."</wsse:Password>
                                  <Organization>".$this->config['ipcc']."</Organization>
                                  <Domain>Default</Domain>
                              </wsse:UsernameToken>
                              <wsse:BinarySecurityToken>".$this->api_session_id."</wsse:BinarySecurityToken>
                          </wsse:Security>
                      </soap-env:Header>
                      <soap-env:Body>
                        <OTA_CancelRQ Version='2.0.0' xmlns='http://webservices.sabre.com/sabreXML/2011/10' xmlns:xs='http://www.w3.org/2001/XMLSchema' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance'>
                            <Segment Type='air'/>
                        </OTA_CancelRQ>                   
                      </soap-env:Body>
                  </soap-env:Envelope>";
        $request ['request'] = $request_params;
        $request ['url'] = $this->config['api_url'];
        $request ['remarks'] = 'Cancellation(Sabare)';
        $request ['status'] = SUCCESS_STATUS;
        // debug($request);exit;
        return $request;
    }
     private function save_flight_ticket_details($booking_params, $airline_pnr, $app_reference, $sequence_number, $search_id){
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
        $passenger_details =$passenger_details['data'];
        // debug($flight_booking_itinerary_details_fk);exit;
        foreach($flight_booking_itinerary_details_fk['data'] as $itinerary){
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
            $ticket_id = '';
            $ticket_number = '';

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
    private function save_book_response_details($pnr, $app_reference, $sequence_number) {
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
        if(isset($api_response['soap-env:Envelope']['soap-env:Body']['TravelItineraryAddInfoRS']['stl:ApplicationResults']['stl:Error']) ==false){
            $response = true;
        }
        return $response;
    }
    private function valid_airbook_response($api_response)
    {
        $response = false;
        if(isset($api_response['soap-env:Envelope']['soap-env:Body']['EnhancedAirBookRS']['stl:ApplicationResults']['stl:Error']) ==false){
            $response = true;
        }
        return $response;
    }

    private function valid_specialservice_response($api_response)
    {
        $response = false;
        if(isset($api_response['soap-env:Envelope']['soap-env:Body']['SpecialServiceRS']['stl:ApplicationResults']['stl:Error']) ==false){
            $response = true;
        }
        return $response;
    }
     /**
     * Authentication Request
     */
    public function get_authentication_request($internal_request = false) {
        $response ['status'] = FAILURE_STATUS; // Status Of Operation
        $response ['message'] = ''; // Message to be returned
        $response ['data'] = array(); // Data to be returned

        $active_booking_source = $this->is_active_booking_source();
        
        if ($active_booking_source['status'] == SUCCESS_STATUS) {
            $authenticate_request = $this->authenticate_request();

            if ($authenticate_request['status'] = SUCCESS_STATUS) {
                $response ['status'] = SUCCESS_STATUS;
                $curl_request = $this->form_curl_params($authenticate_request['request'], $authenticate_request['url']);

                $response ['data'] = $curl_request['data'];
            }
            if ($internal_request == true) {
                $response ['data']['remarks'] = 'Authentication(Sabare)';
            }
        }

        return $response;
    }
     /**
     * Authentcation RQ for api
     */
    private function authenticate_request() {
        $request = array();
        $authentication_request = 
        '<?xml version="1.0" encoding="utf-8"?>
            <soap-env:Envelope xmlns:soap-env="http://schemas.xmlsoap.org/soap/envelope/">
                <soap-env:Header>
                    <eb:MessageHeader xmlns:eb="http://www.ebxml.org/namespaces/messageHeader">
                        <eb:From>
                            <eb:PartyId eb:type="urn:x12.org.IO5:01">'.$this->config['sabre_email'].'</eb:PartyId>
                        </eb:From>
                        <eb:To>
                            <eb:PartyId eb:type="urn:x12.org.IO5:01">webservices3.sabre.com</eb:PartyId>
                        </eb:To>
                        <eb:ConversationId>'.$this->conversation_id.'</eb:ConversationId>
                        <eb:Service eb:type="SabreXML">Session</eb:Service>
                        <eb:Action>SessionCreateRQ</eb:Action>
                        <eb:CPAID>'.$this->config['ipcc'].'</eb:CPAID>
                        <eb:MessageData>
                            <eb:MessageId>'.$this->message_id.'</eb:MessageId>
                            <eb:Timestamp>'.$this->timestamp.'</eb:Timestamp>
                            <eb:TimeToLive>'.$this->timetolive.'</eb:TimeToLive>
                        </eb:MessageData>
                    </eb:MessageHeader>
                    <wsse:Security xmlns:wsse="http://schemas.xmlsoap.org/ws/2002/12/secext">
                        <wsse:UsernameToken>
                            <wsse:Username>'.$this->config['username'].'</wsse:Username>
                            <wsse:Password>'.$this->config['password'].'</wsse:Password>
                            <Organization>'.$this->config['ipcc'].'</Organization>
                            <Domain>Default</Domain>
                        </wsse:UsernameToken>
                    </wsse:Security>
                </soap-env:Header>
                <soap-env:Body>
                    <SessionCreateRQ>
                        <POS>
                            <Source PseudoCityCode="'.$this->config['ipcc'].'" />
                        </POS>
                    </SessionCreateRQ>
                </soap-env:Body>
            </soap-env:Envelope>';
        $request ['request'] = $authentication_request;
        $request ['url'] = $this->config['api_url'];
        $request ['status'] = SUCCESS_STATUS;
        // debug($request);exit;
        return $request;
    }
    /**
     * process soap API request
     *
     * @param string $request
    */
    function form_curl_params($request, $url,$soap_url='') {
        $data['status'] = SUCCESS_STATUS;
        $data['message'] = '';
        $data['data'] = array();

        $curl_data = array();
        $curl_data['booking_source'] = $this->booking_source;
        $curl_data['request'] = $request;
        $curl_data['url'] = $url;
        $curl_data['header'] = array('Content-Type: text/xml; charset="utf-8"', 
            'Content-Length: '.strlen($request), 
            //'Accept-Encoding: gzip,deflate',
            'Accept: text/xml', 
            'Cache-Control: no-cache', 
            'Pragma: no-cache',
            'SOAPAction: "'.$soap_url.'"');

        $data['data'] = $curl_data;
        // debug($data);exit;
        return $data;
    }
    /**
     * Process API Request
     * @param unknown_type $request
     * @param unknown_type $url
     */
    function process_request($request, $url, $remarks = '',$soap_url) {
        $insert_id = $this->CI->api_model->store_api_request($url, $request, $remarks);
        $insert_id = intval(@$insert_id['insert_id']);

        try {

            $headers = array( 
            'Content-Type: text/xml; charset="utf-8"', 
            'Content-Length: '.strlen($request), 
            //'Accept-Encoding: gzip,deflate',
            'Accept: text/xml', 
            'Cache-Control: no-cache', 
            'Pragma: no-cache',
            'SOAPAction: "'.$soap_url.'"'
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
            #echo $response;exit;
            $error = curl_getinfo($ch);
        } catch (Exception $e) {
            $response = 'No Response Recieved From API';
        }
        // debug($response);exit;
        //Update the API Response
        $this->CI->api_model->update_api_response($response, $insert_id);
        $final_de = date('Ymd_His')."_".rand(1,10000);
        $XmlReqFileName = $remarks.'_Req'.$final_de; 
        $XmlResFileName = $remarks.'_Res'.$final_de;
        $fp = fopen(FCPATH.'amadeuslogs/'.$XmlReqFileName.'.xml', 'a+');
        fwrite($fp, $request);
        fclose($fp);
        $fp = fopen(FCPATH.'amadeuslogs/'.$XmlResFileName.'.xml', 'a+');
        fwrite($fp, $response);
        fclose($fp);
        $error = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $response;
    }
    /**
     * Process API Request
     * @param unknown_type $request
     * @param unknown_type $url
     */
    function process_request_book($request, $url, $remarks = '') {
        $insert_id = $this->CI->api_model->store_api_request($url, $request, $remarks);
        $insert_id = intval(@$insert_id['insert_id']);

        try {

            $httpHeader = array( 'Content-Type: text/xml; charset="utf-8"',);
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
    public function get_sabre_response($url,$request)
    {
        $httpHeader = array( 'Content-Length: 0','Content-Type: text/xml; charset="utf-8"',);
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
    function getNoncevalue() {
        $Nonce = base64_encode(time());
        return $Nonce;
    }

    function getCreateDate() {
        $gmdate = gmdate('Y-m-d\TH:i:s\Z');
        return $gmdate;

    }

    function hextobin($hexstr)
    {
        $n = strlen($hexstr);
        $sbin=""; 
        $i=0;
        while($i<$n)
        {     
            $a =substr($hexstr,$i,2);         
            $c = pack("H*",$a);
            if ($i==0){$sbin=$c;}
            else {$sbin.=$c;}
            $i+=2;
        } 
        return $sbin;
    }


    function DigestAlgo($pwd,$created,$nonce)
    {
        $passha = $this->hextobin(strtoupper(sha1($pwd)));
        $Nonces = base64_decode($nonce);
        $DigHex = $this->hextobin(strtoupper(sha1($Nonces.$created.$passha)));
        return $passwordDigest = base64_encode($DigHex);
    }  
        function uuid($serverID=1)
    { 
        $t=explode(" ",microtime());
        return sprintf( '%04x-%08s-%08s-%04s-%04x%04x',$serverID,$this->clientIPToHex(),substr("00000000".dechex($t[1]),-8),   
       substr("0000".dechex(round($t[0]*65536)),-4), // get 4HEX of microtime
       mt_rand(0,0xffff), mt_rand(0,0xffff));
    }

    function clientIPToHex($ip="") 
    { 
        $hex="";
        if($ip=="") $ip=getEnv("REMOTE_ADDR");
        $part=explode('.', $ip);
        for ($i=0; $i<=count($part)-1; $i++) {
            $hex.=substr("0".dechex($part[$i]),-2);
        }
        return $hex;
    }


    function getuuid()
    {
        return 'urn:uuid:'.$this->uuid();
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
        $current = & $xml_array;
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
                $parent[$level - 1] = & $current; 
                if (!is_array($current) or (!in_array($tag, array_keys($current)))) {
                    $current[$tag] = $result;
                    if ($attributes_data)
                        $current[$tag . '_attr'] = $attributes_data;
                    $repeated_tag_index[$tag . '_' . $level] = 1;
                    $current = & $current[$tag];
                }
                else {
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
                    $current = & $current[$tag][$last_item_index];
                }
            } elseif ($type == "complete") {
                if (!isset($current[$tag])) {
                    $current[$tag] = $result;
                    $repeated_tag_index[$tag . '_' . $level] = 1;
                    if ($priority == 'tag' and $attributes_data)
                        $current[$tag . '_attr'] = $attributes_data;
                }
                else {
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
                $current = & $parent[$level - 1];
            }
        }
        //echo "<pre>"; print_r($xml_array); echo "</pre>";  die();
        return ($xml_array);
    }
    //get the pax reference
    private function get_paxref_search_req($pax_count,$pax_type,$paxRef){
        if($pax_count>0){
            $pax_reference ='';
           
            for ($p=0; $p < $pax_count; $p++) { 
                
                if($p==0){
                    $pax_reference .='<ptc>'.$pax_type.'</ptc>';
                }                    
                if($pax_type=='INF'){
                     $pax_reference .='<traveller>
                            <ref>'.($p+1).'</ref>
                            <infantIndicator>'.($p+1).'</infantIndicator>
                        </traveller>';                  
                   
                }else{
                     $pax_reference .='<traveller>
                            <ref>'.$paxRef.'</ref>
                        </traveller>';    
                }
               $paxRef++;
               
            }
        }
        return array('paxtag'=>$pax_reference,'paxRef'=>$paxRef);
         
    }
    //If any error occured need to close the current session
    private function Security_SignOut($SecuritySession,$seq,$SecurityToken){ 
            $xml='<?xml version="1.0" encoding="UTF-8"?>           
            <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:sec="http://xml.amadeus
            .com/2010/06/Security_v1" xmlns:typ="http://xml.amadeus.com/2010/06/Type">
            <soapenv:Header>
            <awsse:Session TransactionStatusCode="End" xmlns:awsse="http://xml.amadeus.com/2010/06/Session_v3">     
            <awsse:SessionId>'.$SecuritySession.'</awsse:SessionId>
            <awsse:SequenceNumber>'.$seq.'</awsse:SequenceNumber>
            <awsse:SecurityToken>'.$SecurityToken.'</awsse:SecurityToken>
            </awsse:Session>
            <add:MessageID xmlns:add="http://www.w3.org/2005/08/addressing">'.$this->getuuid().'</add:MessageID>
            <add:Action xmlns:add="http://www.w3.org/2005/08/addressing">http://webservices.amadeus.com/VLSSOQ_04_1_1A</add:Action>
            <add:To xmlns:add="http://www.w3.org/2005/08/addressing">'.$this->api_url.'</add:To>
            </soapenv:Header>
            <soapenv:Body>
            <Security_SignOut xmlns="http://xml.amadeus.com/VLSSOQ_04_1_1A"></Security_SignOut>
            </soapenv:Body>
            </soapenv:Envelope>';
            $soapAction = "VLSSOQ_04_1_1A";
            $api_url = $this->api_url;
            $soap_url = $this->soap_url.$soapAction;
            $remarks = 'Security_SignOut(amadeus)';
            $this->process_request($xml,$api_url,$remarks,$soap_url);
        }

}