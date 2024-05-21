<?php

require_once BASEPATH . 'libraries/flight/Common_api_flight.php';

class Sabare extends Common_Api_Flight {

    var $master_search_data;
    var $search_hash;
    protected $token;
    private $end_user_ip = '127.0.0.1';
    var $api_session_id;

    function __construct() {
        parent::__construct(META_AIRLINE_COURSE, SABARE_FLIGHT_BOOKING_SOURCE);
        $this->CI = &get_instance();
        $this->CI->load->library('Converter');
        $this->CI->load->library('ArrayToXML');
        $this->set_api_credentials();
        $this->set_api_session_id();
        
    }
    /**
     * Setting Api Credentials
    */
    private function set_api_credentials()
    {
        $this->conversation_id = time().$this->config['sabre_email'];

        $this->message_id = "mid:".time().$this->config['sabre_email'];
        $this->timestamp  = gmdate("Y-m-d\TH-i-s\Z");
        $this->timetolive = gmdate("Y-m-d\TH-i-s\Z");

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
       
        if (empty($this->api_session_id) == true) {
            // return failure object as the login signature is not set
            return $response;
        }
        /* get search criteria based on search id */
        $search_data = $this->search_data($search_id);
        if ($search_data ['status'] == SUCCESS_STATUS) {
            // Flight search RQ
            $search_request = $this->search_request($search_data ['data']);
            if ($search_request ['status'] = SUCCESS_STATUS) {
                $response ['status'] = SUCCESS_STATUS;
                $curl_request = $this->form_curl_params($search_request ['request'], $search_request ['url']);
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
        // debug($search_data);exit;
        $response['status'] = SUCCESS_STATUS;
        $response['data']   = array();
        $search_params = $search_data;
        $trip_type = $search_params['trip_type'];
        // debug($search_params);exit;
        $search_params['from'] = (is_array($search_params['from']) ? $search_params['from'] : array($search_params['from']));
        $search_params['to'] = (is_array($search_params['to']) ? $search_params['to'] : array($search_params['to']));
        $search_params['depature'] = (is_array($search_params['depature']) ? $search_params['depature'] : array($search_params['depature']));
        $search_params['return'] = (is_array(@$search_params['return']) ? @$search_params['return'] : array(@$search_params['return']));

        $way_request = $airline = $passengers = $max_stops =  $multi_ticket = $passengers_private = "";

        if(empty($search_params['carrier'][0]) == false){
            $airline = "<IncludeVendorPref Code='".$search_params['carrier'][0]."'/>";
        }

        if($search_params['adult_config'] > 0){
            $passengers .= "<PassengerTypeQuantity Code='ADT' Quantity='".$search_params['adult_config']."'/>";
            $passengers_private .= "<PassengerTypeQuantity Code='JCB' Quantity='".$search_params['adult_config']."'/>";
        }
        if($search_params['child_config'] > 0){
            $passengers .= "<PassengerTypeQuantity Code='CNN' Quantity='".$search_params['child_config']."'/>";
            $passengers_private .= "<PassengerTypeQuantity Code='JNN' Quantity='".$search_params['child_config']."'/>";
        }
        if($search_params['infant_config'] > 0){
            $passengers .= "<PassengerTypeQuantity Code='INF' Quantity='".$search_params['infant_config']."'/>";
            $passengers_private .= "<PassengerTypeQuantity Code='JNF' Quantity='".$search_params['infant_config']."'/>";
        }

        $class_code  = $this->get_search_class_code($search_params['v_class']);

        if($search_params['trip_type'] == "return"){
            $multi_ticket = '<MultiTicket DisplayPolicy="SCHS"/>';
        }

        for($i=0; $i<count($search_params['from']); $i++){
            $way_request .=  "<OriginDestinationInformation RPH='".($i+1)."'>
                                <DepartureDateTime>".$search_params['depature'][$i]."</DepartureDateTime>
                                <OriginLocation LocationCode='".trim($search_params['from'][$i])."'/>
                                <DestinationLocation LocationCode='".trim($search_params['to'][$i])."'/>   
                                <TPA_Extensions>
                                  ".$airline."
                                </TPA_Extensions>
                           </OriginDestinationInformation>";
            if($search_params['trip_type'] == 'return') {
            $way_request .=  "<OriginDestinationInformation RPH='".($i+2)."'>
                                <DepartureDateTime>".$search_params['return'][$i]."</DepartureDateTime>
                                <OriginLocation LocationCode='".trim($search_params['to'][$i])."'/>
                                <DestinationLocation LocationCode='".trim($search_params['from'][$i])."'/>   
                                <TPA_Extensions>
                                  ".$airline."
                                </TPA_Extensions>
                           </OriginDestinationInformation>";
            } 
            
        }
        
        $xml_request = 
        '<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
            <SOAP-ENV:Header>
                <m:MessageHeader xmlns:m="http://www.ebxml.org/namespaces/messageHeader">
                    <m:From>
                        <m:PartyId type="urn:x12.org:IO5:01">'.$this->config['sabre_email'].'</m:PartyId>
                    </m:From>
                    <m:To>
                        <m:PartyId type="urn:x12.org:IO5:01">webservices.sabre.com</m:PartyId>
                    </m:To>
                    <m:CPAId>D6YI</m:CPAId>
                    <m:ConversationId>'.$this->conversation_id.'</m:ConversationId>
                    <m:Service m:type="OTA">BargainFinderMaxRQ </m:Service>
                    <m:Action>BargainFinderMaxRQ</m:Action>
                    <m:MessageData>
                        <m:MessageId>'.$this->message_id.'</m:MessageId>
                        <m:Timestamp>'.$this->timestamp.'</m:Timestamp>
                        <m:TimeToLive>'.$this->timetolive.'</m:TimeToLive>
                    </m:MessageData>
                    <m:DuplicateElimination />
                    <m:Description>Bargain Finder Max Service</m:Description>
                </m:MessageHeader>
                <wsse:Security xmlns:wsse="http://schemas.xmlsoap.org/ws/2002/12/secext">
                    <wsse:BinarySecurityToken valueType="String" EncodingType="wsse:Base64Binary">'.$this->api_session_id.'</wsse:BinarySecurityToken>
                </wsse:Security>
            </SOAP-ENV:Header>
            <SOAP-ENV:Body>
                <OTA_AirLowFareSearchRQ ResponseType="OTA" ResponseVersion="3.0.0" Version="3.0.0" AvailableFlightsOnly="true" xmlns="http://www.opentravel.org/OTA/2003/05">
                    <POS>
                        <Source PseudoCityCode="'.$this->config['ipcc'].'">
                            <RequestorID ID="1" Type="1">
                                <CompanyName Code="TN">TN</CompanyName>
                            </RequestorID>
                        </Source>
                    </POS>
                    '.$way_request.'
                    <TravelPreferences>
                        <CabinPref PreferLevel="Preferred" Cabin="'.$class_code.'" />
                        <TPA_Extensions>
                            <LongConnectTime Min="5" Max="1439" Enable="true" />
                            <LongConnectPoints Min="1" Max="3" />
                            <JumpCabinLogic Disabled="true" />
                            <KeepSameCabin Enabled="true" />
                        </TPA_Extensions>
                    </TravelPreferences>
                    <TravelerInfoSummary>
                        <SeatsRequested>'.($search_params['adult_config']+$search_params['child_config']).'</SeatsRequested>
                        <AirTravelerAvail>
                            '.$passengers.'
                        </AirTravelerAvail>
                       
                    </TravelerInfoSummary>
                    <TPA_Extensions>
                        <IntelliSellTransaction>
                            <RequestType Name="50ITINS" />
                        </IntelliSellTransaction>
                        '.$multi_ticket.'
                    </TPA_Extensions>
                </OTA_AirLowFareSearchRQ>
            </SOAP-ENV:Body>
        </SOAP-ENV:Envelope>';
        $request ['request'] = $xml_request;
        $request ['url'] = $this->config['api_url'];
        $request ['status'] = SUCCESS_STATUS;
        // debug($request);exit;
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
            $api_response = Converter::createArray($flight_raw_data);
            // debug($api_response);exit;
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
        // error_reporting(E_ALL);
        // echo 'herrer I am';exit;
        $trip_type = isset($search_data ['is_domestic']) && !empty($search_data ['is_domestic']) ? 'domestic' : 'international';
        $Results = $search_result['SOAP-ENV:Envelope']['SOAP-ENV:Body']['OTA_AirLowFareSearchRS']['PricedItineraries']['PricedItinerary'];
        $Results = force_multple_data_format($Results);
        // debug($Results);exit;
        $flight_list = array();
        foreach ($Results as $result_k => $result_v) {
            $air_price_detail = $result_v['AirItineraryPricingInfo'];
            $flight_segments  = force_multple_data_format($result_v['AirItinerary']['OriginDestinationOptions']['OriginDestinationOption']);
            $response['FlightDataList'] = array();
            foreach ($flight_segments as $__seg_k => $__seg_v)
            {
                $segments = array();
                if(isset($__seg_v['FlightSegment']) ==true){
                    $__seg_v['FlightSegment'] = force_multple_data_format($__seg_v['FlightSegment']);
                }else{
                    $__seg_v['FlightSegment'] = force_multple_data_format($__seg_v);
                }
                $Store_Segments=array();
                foreach ($__seg_v['FlightSegment'] as $ins_seg_k => $ins_seg_v) 
                {
                    // Origin Details
                    $segments['Origin']['AirportCode'] = $DepAirportCode = $ins_seg_v['DepartureAirport']['@attributes']['LocationCode'];
                    $segments['Origin']['CityName'] =$this->get_airport_city ( $ins_seg_v['DepartureAirport']['@attributes']['LocationCode'] );
                    $segments['Origin']['AirportName'] = $this->get_airport_city ( $ins_seg_v['DepartureAirport']['@attributes']['LocationCode'] );
                    $segments['Origin']['DateTime'] = date('Y-m-d H:i:s',strtotime($ins_seg_v['@attributes']['DepartureDateTime']));
                    $d_time = date('H:i',strtotime($ins_seg_v['@attributes']['DepartureDateTime']));
                    $segments['Origin']['FDTV'] = strtotime($d_time);
                    $DepDateTime = strtotime($ins_seg_v['@attributes']['DepartureDateTime']);

                    // Destination Details
                    $segments['Destination']['AirportCode'] = $ArrAirportCode = $ins_seg_v['ArrivalAirport']['@attributes']['LocationCode'];
                    $segments['Destination']['CityName'] = $this->get_airport_city ( $ins_seg_v['ArrivalAirport']['@attributes']['LocationCode'] );
                    $segments['Destination']['AirportName'] = $this->get_airport_city ($ins_seg_v['ArrivalAirport']['@attributes']['LocationCode']);
                    $segments['Destination']['DateTime'] = date('Y-m-d H:i:s',strtotime($ins_seg_v['@attributes']['ArrivalDateTime']));
                    $a_time = date('H:i',strtotime($ins_seg_v['@attributes']['ArrivalDateTime']));
                    $segments['Destination']['FATV'] = strtotime($a_time);
                    $ArrDateTime = strtotime($ins_seg_v['@attributes']['ArrivalDateTime']);

                    // Airline Details
                    $segments['MarkettingAirline'] = $ins_seg_v['MarketingAirline']['@attributes']['Code'];
                    $segments['OperatorCode'] = $ins_seg_v['OperatingAirline']['@attributes']['Code'];
                    $segments['DisplayOperatorCode'] = $ins_seg_v['OperatingAirline']['@attributes']['Code'];
                    $segments['OperatorName'] = $this->get_airline_name($segments['OperatorCode']);
                    $segments['FlightNumber'] = $FlightNumber = $ins_seg_v['OperatingAirline']['@attributes']['FlightNumber'];
                    $segments['CabinClass']   = "";
                    $segments['Attr']['Baggage'] = "";
                    $segments['Attr']['CabinBaggage'] = "";

                    // API Extra Details
                    $segments['JourneyDetails']['DepartureDateTime'] =$ins_seg_v['@attributes']['DepartureDateTime'];
                    $segments['JourneyDetails']['ArrivalDateTime'] = $ins_seg_v['@attributes']['ArrivalDateTime'];
                    $segments['JourneyDetails']['Equipment'] =  $ins_seg_v['Equipment']['@attributes']['AirEquipType'];
                    $segments['JourneyDetails']['MarriageGrp'] = $ins_seg_v['MarriageGrp'];
                    
                    if(isset($ins_seg_v['@attributes']['ResBookDesigCode']) ==true){
                        $segments['JourneyDetails']['ResBookDesigCode'] = $ins_seg_v['@attributes']['ResBookDesigCode'];
                    }else{
                        $ins_seg_v['@attributes']['ResBookDesigCode'] = "";
                    }
                    
                    $segments['JourneyDetails']['MarkettingAirline'] = $ins_seg_v['MarketingAirline']['@attributes']['Code'];

                    //Store All Information
                    $Store_Segments[]=$segments;
                }
              
                // debug($Store_Segments);exit;
                $Price['Currency'] = $air_price_detail['ItinTotalFare']['BaseFare']['@attributes']['CurrencyCode'];
                $API_Currency = $air_price_detail['ItinTotalFare']['TotalFare']['@attributes']['CurrencyCode'];
                 $base_API_Currency = $air_price_detail['ItinTotalFare']['BaseFare']['@attributes']['CurrencyCode'];
                $tax_API_Currency = $air_price_detail['ItinTotalFare']['Taxes']['Tax']['@attributes']['CurrencyCode'];
                // echo $Price['Currency'];exit;
                $TotalDisplayFare = $air_price_detail['ItinTotalFare']['TotalFare']['@attributes']['Amount'];
                $conversion_amount = $GLOBALS ['CI']->domain_management_model->get_currency_conversion_rate($API_Currency);
                // debug($conversion_amount);
                $conversion_rate = $conversion_amount['conversion_rate'];
                $base_conversion_amount = $GLOBALS ['CI']->domain_management_model->get_currency_conversion_rate($base_API_Currency);
                // debug($conversion_amount);
                $base_conversion_rate = $base_conversion_amount['conversion_rate'];
                $tax_conversion_amount = $GLOBALS ['CI']->domain_management_model->get_currency_conversion_rate($tax_API_Currency);
                // debug($conversion_amount);
                $tax_conversion_rate = $tax_conversion_amount['conversion_rate'];
                $Price['TotalDisplayFare'] = $conversion_rate * $TotalDisplayFare;
                $Price['PriceBreakup']['BasicFare'] = $base_conversion_rate * $air_price_detail['ItinTotalFare']['BaseFare']['@attributes']['Amount'];
                // if($Price['Currency'] != "USD" || $Price['Currency'] != "CAD" ){
                //     $Price['Currency'] = $air_price_detail['ItinTotalFare']['EquivFare']['@attributes']['CurrencyCode'];
                //     $Price['PriceBreakup']['BasicFare'] = $air_price_detail['ItinTotalFare']['EquivFare']['@attributes']['Amount'];
                // }
                
                $Price['PriceBreakup']['Tax'] = $tax_conversion_rate*$air_price_detail['ItinTotalFare']['Taxes']['Tax']['@attributes']['Amount'];
                $Price['PriceBreakup']['AgentCommission'] = 0;
                $Price['PriceBreakup']['AgentTdsOnCommision'] = 0;
                if(isset($air_price_detail['PTC_FareBreakdowns']) ==false){
                    $PassengerBreakup = force_multple_data_format($air_price_detail['Tickets']['Ticket'][0]['AirItineraryPricingInfo']['PTC_FareBreakdowns']['PTC_FareBreakdown']);
                }else{
                    $PassengerBreakup = force_multple_data_format($air_price_detail['PTC_FareBreakdowns']['PTC_FareBreakdown']);
                }
                // debug($PassengerBreakup);exit;
                $PassengerBreakup_fare = array();
                foreach ($PassengerBreakup as $__apd_k => $__apd_v) {
                    $PaxTax = 0;
                    //debug($__apd_v['PassengerFare']);die;
                    if(isset( $__apd_v['PassengerFare']['Taxes']['TotalTax']) ==true){
                        $PaxTax = $__apd_v['PassengerFare']['Taxes']['TotalTax']['@attributes']['Amount'];
                    }
                    if(isset( $__apd_v['PassengerTypeQuantity']) ==true){
                        $PaxCode = $__apd_v['PassengerTypeQuantity']['@attributes']['Code'];
                        $PaxCount =  $__apd_v['PassengerTypeQuantity']['@attributes']['Quantity'];
                    }else{
                        $PaxCode = $__apd_v['@attributes']['Code'];
                        $PaxCount =  $__apd_v['@attributes']['Quantity'];
                    }
                    // debug($__apd_v);exit;
                    $base_pax_currency= $__apd_v['PassengerFare']['BaseFare']['@attributes']['CurrencyCode'];
                    $base_conversion_amount = $GLOBALS ['CI']->domain_management_model->get_currency_conversion_rate($base_pax_currency);
                    $base_conversion_rate = $base_conversion_amount['conversion_rate'];

                    $tax_pax_currency= @$__apd_v['PassengerFare']['Taxes']['TotalTax']['@attributes']['CurrencyCode'];
                    $tax_conversion_amount = $GLOBALS ['CI']->domain_management_model->get_currency_conversion_rate($tax_pax_currency);
                    $tax_conversion_rate = $tax_conversion_amount['conversion_rate'];

                    $PaxFare['BasePrice'] = $base_conversion_rate * ($__apd_v['PassengerFare']['BaseFare']['@attributes']['Amount']*$PaxCount);
                    $PaxFare['Tax'] = $tax_conversion_rate * ($PaxTax*$PaxCount);
                    $PaxFare['TotalPrice'] = $tax_conversion_rate*($__apd_v['PassengerFare']['TotalFare']['@attributes']['Amount']*$PaxCount);
                    $PaxFare['PassengerCount'] = $PaxCount;
                    if($PaxCode == 'ADT'){
                        $PaxFare['PassengerType'] = 1;
                    }
                    else if($PaxCode == 'CNN' || $PaxCode == 'CHD'){
                        $PaxFare['PassengerType'] = 2;
                    }
                    else{
                        $PaxFare['PassengerType'] = 3;
                    }
                    $PaxFare['PassengerCount'] = $PaxCount;
                    $PaxFare['Currency'] = 'INR';
                    $Price['PassengerBreakup'][$PaxCode] = $PaxFare;
                    $PassengerBreakup_fare[] = $PaxFare;
                    $IsRefundable = $__apd_v['Endorsements']['@attributes']['NonRefundableIndicator'];
                    if($IsRefundable == 'false'){
                        $IsRefundable = 1;
                    }else{
                        $IsRefundable = 0;
                    }

                    $Baggages = force_multple_data_format($__apd_v['PassengerFare']['TPA_Extensions']['BaggageInformationList']['BaggageInformation']);

                    foreach ($Baggages as $__bg_k => $__bg_v) {
                        $bag_value = "0 kg";
                        if(isset($__bg_v['Allowance']['@attributes']['Weight']) ==true){
                            $bag_value = $__bg_v['Allowance']['@attributes']['Weight']."kg";
                        }

                        $segment_count = count($Store_Segments);
                    }
                }
                $key = array();
                $key['key'][$result_k]['booking_source'] = $this->booking_source;
                $key['key'][$result_k]['ResultIndex'] = '';
                $key['key'][$result_k]['IsLCC'] = '';
                $key['key'][$result_k]['org_airportcode'] = $Store_Segments[0]['Origin']['AirportCode'];
                $key['key'][$result_k]['dest_airportcode'] = $Store_Segments[0]['Destination']['AirportCode'];
                $key['key'][$result_k]['marketing_airline'] = $Store_Segments[0]['MarkettingAirline'];
                $key['key'][$result_k]['depature_date'] = $Store_Segments[0]['Origin']['DateTime'];
                $key['key'][$result_k]['FareBreakdown'] = $PassengerBreakup_fare;
                $ResultToken = serialized_data($key['key']);
                $Attr['IsRefundable'] = $IsRefundable;
                $Attr['AirlineRemark'] = "";
                $Segment_Details['JourneyList'][0][$result_k]['FlightDetails']['Details'][$__seg_k] = $Store_Segments;
                $Segment_Details['JourneyList'][0][$result_k]['Price'] = $Price;
                $Segment_Details['JourneyList'][0][$result_k]['ResultToken'] = $ResultToken;
                $Segment_Details['JourneyList'][0][$result_k]['Attr'] = $Attr;
                $response['FlightDataList'] = $Segment_Details;
                
            }
        }
       
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
        $fare_rule_request = $this->fare_rule_request($request);
        if ($fare_rule_request['status'] == SUCCESS_STATUS) {
            $fare_rule_response = $this->process_request($fare_rule_request ['request'], $fare_rule_request ['url'], $fare_rule_request ['remarks']);
            $Rules = '';
            if (!empty($fare_rule_response) && substr($fare_rule_response, 0, 5) == "<?xml") {
                $doc = new DOMDOcument;
                $doc->loadxml($fare_rule_response);
                $FareBasis = $doc->getElementsByTagName("FareBasis");
                // debug($FareBasis);exit;
               
                foreach ($FareBasis as $fare) {
                    $FareBasisCode = $fare->getAttribute('Code');
                    $ota_request = $this->ota_fare_details_request($request,$FareBasisCode);
                    $ota_api_response = $this->process_request($ota_request ['request'], $ota_request ['url'], $ota_request ['remarks']);
                    if (!empty($ota_api_response) && substr($ota_api_response, 0, 5) == "<?xml") {
                        $doc = new DOMDOcument;
                        $doc->loadxml($ota_api_response);
                        $Paragraphs = $doc->getElementsByTagName("Paragraph");
                        $i=0;
                            if($Paragraphs->length > 0) {
                              foreach ($Paragraphs as $Paragraph) {         
                                $Title        = $Paragraph->getAttribute('Title');            
                                $Text         = $Paragraph->getElementsByTagName("Text");
                                $Text         = $Text->item(0)->nodeValue;
                                $Rules .= '<b>'.$Title . "</b><br/>".$Text."<br/>";
                                $i++;
                            }
                                $response['status'] = SUCCESS_STATUS;
                        }else {
                           $response['status'] = FAILURE_STATUS;
                        }
                    }else{
                        $response['status'] = FAILURE_STATUS;
                    }
                    if(isset($Rules[0]) && $Rules[0] != ''){ break; }
                }

                if(!empty($Rules)){
                    $fareResp1[0]['FareRules'] = $Rules;
                    $fareResp1[0]['Origin'] = $request['org_airportcode'];
                    $fareResp1[0]['Destination'] = $request['dest_airportcode'];
                    $fareResp1[0]['Airline'] = $request['marketing_airline'];
                    $response ['data']['FareRuleDetail'] = $fareResp1;
                }
                else{
                    $response['status'] = FAILURE_STATUS;
                    $response ['message'] = 'Not Available';  
                }
            }
            else{
              $response['status'] = FAILURE_STATUS;
              $response ['message'] = 'Not Available'; 
            }
        } else {
            $response ['status'] = FAILURE_STATUS;
        }
        // debug($Responsense);exit;
        return $response;
    }
     /**
     * Forms the fare rule request
     * @param unknown_type $request
     */
    private function fare_rule_request($params) {
        // debug($params);exit;
        $DepartureDate      = date('m-d',strtotime($params['depature_date']));
        $request = array();
        $fare_rule_request = 
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
                              <eb:Service eb:type='OTA'>FareLLSRQ</eb:Service>
                              <eb:Action>FareLLSRQ</eb:Action>
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
                          <FareRQ xmlns='http://webservices.sabre.com/sabreXML/2011/10' xmlns:xs='http://www.w3.org/2001/XMLSchema' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' Version='2.0.0'>
                            <OptionalQualifiers>
                               <FlightQualifiers>
                    <VendorPrefs>
                      <Airline Code='".$params['marketing_airline']."'/>
                    </VendorPrefs>
                  </FlightQualifiers>
                              <TimeQualifiers>
                                <TravelDateOptions Start='".$DepartureDate."'/>
                              </TimeQualifiers>
                            </OptionalQualifiers>
                            <OriginDestinationInformation>
                              <FlightSegment>
                                 <DestinationLocation LocationCode='".$params['org_airportcode']."'/>                         
                                 <OriginLocation LocationCode='".$params['dest_airportcode']."'/>
                              </FlightSegment>
                            </OriginDestinationInformation>
                          </FareRQ>
                      </soap-env:Body>
                  </soap-env:Envelope>";
        $request ['request'] = $fare_rule_request;
        $request ['url'] = $this->config['api_url'];
        $request ['remarks'] = 'FareRule(Sabare)';
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
    public function get_update_fare_quote_sabare($fare_search_data, $search_id) {
        // error_reporting(E_ALL);
        // debug($fare_search_data);exit;
        $response ['status'] = FAILURE_STATUS; // Status Of Operation
        $response ['message'] = ''; // Message to be returned
        $response ['data'] = array(); // Data to be returned
        if (valid_array($fare_search_data)) {
            
            if (valid_array($fare_search_data) == true && isset($fare_search_data['FlightDetails']) == true) {
                $response ['status'] = SUCCESS_STATUS;
                $response ['data']['FareQuoteDetails']['JourneyList'][0][0] = $fare_search_data;
            } else {
                $response ['message'] = 'Not Available';
            }
        } else {
            $response ['status'] = FAILURE_STATUS;
        }
        // debug($response);exit;
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
        $airline_name =ucfirst(strtolower($airline_data ['name']));
        return ($airline_name);
    }
     /**
     * check if the search RS is valid or not
     * @param array $search_result
     * search result RS to be validated
     */
    private function valid_search_result($search_result) {
        if (valid_array($search_result) == true && isset($search_result['SOAP-ENV:Envelope']['SOAP-ENV:Body']['OTA_AirLowFareSearchRS']) == true && isset($search_result['SOAP-ENV:Envelope']['SOAP-ENV:Body']['OTA_AirLowFareSearchRS']['PricedItineraries']) == true && valid_array($search_result['SOAP-ENV:Envelope']['SOAP-ENV:Body']['OTA_AirLowFareSearchRS']['PricedItineraries']) == true) {
            return true;
        } else {
            return false;
        }
    }
    public function get_search_class_code($class)
    {
        if ($class == 'Economy') {
            $economyCode = 'Y';
        }
        if ($class == 'PremiumEconomy') {
            $economyCode = 'S';
        }
        if ($class == 'Business') {
            $economyCode = 'C';
        }
        if ($class == 'PremiumBusiness') {
            $economyCode = 'J';
        }
        if ($class == 'First') {
            $economyCode = 'F';
        }
        if ($class == 'PremiumFirst') {
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
    function process_booking($booking_params, $app_reference, $sequence_number, $search_id) {
        // debug($booking_params);exit;
        $response ['status'] = FAILURE_STATUS; // Status Of Operation
        $response ['message'] = ''; // Message to be returned
        $response ['data'] = array(); // Data to be returned

        $ticket_response = array();
        $book_response = array();

        $ResultToken = $booking_params['ResultToken'];
         //Format Booking Passenger Data
        $booking_params['Passengers'] = $this->format_booking_passenger_object($booking_params);
        $book_service_response = $this->run_book_service($booking_params, $app_reference, $sequence_number, $search_id);
        if ($book_service_response['status'] == SUCCESS_STATUS) {
            $response ['status'] = SUCCESS_STATUS;
            $book_response = $book_service_response['data']['book_response'];
            // debug($book_response);exit;
            $ticketing_status = 'HOLD';
            // echo $ticketing_status;exit;
            //Save BookFlight Details
            $this->save_book_response_details($book_response, $app_reference, $sequence_number);
            $this->save_flight_ticket_details($booking_params, $book_response, $app_reference, $sequence_number, $search_id);

             //Run Non-LCC Ticket method
                if ($ticketing_status == "Tikcet") {
                    $ticket_request_params = array();
                    $ticket_request_params['PNR'] = $book_response['PNR'];
                    $ticket_request_params['BookingId'] = $book_response['BookingId'];
                    $ticket_request_params['TraceId'] = $ResultToken['TraceId'];

                    $ticket_service_response = $this->run_non_lcc_ticket_service($ticket_request_params, $app_reference, $sequence_number);

                    if ($ticket_service_response['status'] == SUCCESS_STATUS) {
                        $ticket_response = $ticket_service_response['data']['ticket_response'];

                        $flight_booking_status = 'BOOKING_CONFIRMED';
                        $this->CI->common_flight->update_flight_booking_status($flight_booking_status, $app_reference, $sequence_number, $this->booking_source);
                    }
                }
        }
        else {
            $response ['message'] = $book_service_response['message'];
            $flight_booking_status = 'BOOKING_FAILED';
            $this->CI->common_flight->update_flight_booking_status($flight_booking_status, $app_reference, $sequence_number, $this->booking_source);
        }
        return $response;
    }
     /**
     * Formats Passenger Details
     * Assign the FareBreakdown for Each Passenger
     */
    private function format_booking_passenger_object($request_params) {
        $fare_break_down = ($request_params['ResultToken']['FareBreakdown']); //FareBreakdown
        $passengers = $request_params['Passengers'];
        $passenger_fare_breakdown = $this->assign_booking_passenger_fare_breakdown($fare_break_down);
        $passenger_data = array();
        $IsLCC = $request_params['ResultToken']['IsLCC'];
        //Default Passport Expiry
        $default_passport_expiry_date = date('Y-m-d', strtotime('+5 years'));
        foreach ($passengers as $k => $v) {
            // debug($v);exit;
            //DOB
            if (isset($v['DateOfBirth']) == true && empty($v['DateOfBirth']) == false) {
                $pax_dob = $v['DateOfBirth'];
            } else {
                $pax_dob = $this->get_pax_default_dob($v['PaxType']);
            }
            //Passport
            if (isset($v['PassportExpiry']) == true && empty($v['PassportExpiry']) == false) {
                $passport_expiry = $v['PassportExpiry'];
            } else {
                $passport_expiry = $default_passport_expiry_date;
            }
            if (isset($v['PassportNumber']) == true && empty($v['PassportNumber']) == false) {
                $passport_number = $v['PassportNumber'];
            } else {
                $passport_number = rand(1111111111, 9999999999);
            }
            //AddressLine2
            if (isset($v['AddressLine2']) == true && empty($v['AddressLine2']) == false) {
                $address_line2 = $v['AddressLine2'];
            } else {
                $address_line2 = 'Bangalore';
            }
            $passenger_data [$k] ['Title'] = $v['Title'];
            $passenger_data [$k] ['FirstName'] = $v['FirstName'];
            $passenger_data [$k] ['LastName'] = $v['LastName'];
            $passenger_data [$k] ['PaxType'] = $v['PaxType'];
            $passenger_data [$k] ['DateOfBirth'] = $pax_dob . 'T00:00:00'; // optional
            $passenger_data [$k] ['Gender'] = $v['Gender'];
            //$passenger_data [$k] ['PassportNo'] = $passport_number;
            $passenger_data [$k] ['PassportNo'] = preg_replace('/\s+/', '', $passport_number); // optional

            $passenger_data [$k] ['PassportExpiry'] = $passport_expiry . 'T00:00:00'; // optional
            $passenger_data [$k] ['AddressLine1'] = substr($v['AddressLine1'], 0, 31);
            $passenger_data [$k] ['AddressLine2'] = ''; // optional
            $passenger_data [$k] ['City'] = $v['City'];
            $passenger_data [$k] ['CountryCode'] = $v['CountryCode'];
            $passenger_data [$k] ['CountryName'] = $v['CountryName'];
            $passenger_data [$k] ['ContactNo'] = $this->validate_mobile_number($v['ContactNo']);
            $passenger_data [$k] ['Email'] = $v['Email'];
            if ($v['IsLeadPax'] == 1) {
                $v['IsLeadPax'] = true;
            } else {
                $v['IsLeadPax'] = false;
            }
            $passenger_data [$k] ['IsLeadPax'] = $v['IsLeadPax'];
            $passenger_data [$k] ['FFAirline'] = null; // optional
            $passenger_data [$k] ['FFNumber'] = null; // optional
            $passenger_data [$k] ['Fare'] = $passenger_fare_breakdown [$v ['PaxType']];
            if (isset($v['SeatId']) == true && valid_array($v['SeatId']) == true) {
                $SeatDetails = $v['SeatId'];
            } else {
                $SeatDetails = array();
            }
            //Baggage Details
            $passenger_data [$k] ['Baggage'] = @$v['BaggageId'];
            //Meals Details
            $passenger_data [$k] ['MealId'] = @$v['MealId'];

            //Seat Details
            if (valid_array($SeatDetails) == true) {
                $passenger_data [$k] ['SeatDynamic'] = $SeatDetails;
            }

        }
        return $passenger_data;
    }

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
        $response ['data']['ExtraServiceDetails']['MealPreference'] = $this->getMeals($request);
        // debug($response);exit;
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
        $segment_data = $request_data['FlightDetails']['Details'];
        // debug($request_data);exit;
        $seatmapresponse = array();
       
        for($j=0;$j< count($segment_data); $j++){
            for($ss=0;$ss< count($segment_data[$j]); $ss++){
            $depature_date = date('Y-m-d',strtotime($segment_data[$j][$ss]['Origin']['DateTime']));
            $arrival_date = date('Y-m-d',strtotime($segment_data[$j][$ss]['Destination']['DateTime']));
            $cabin_class  = $segment_data[$j][$ss]['JourneyDetails']['ResBookDesigCode'];
            $appCurency = 'INR';
            $request_params = 
                    '<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:eb="http://www.ebxml.org/namespaces/messageHeader" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:xsd="http://www.w3.org/1999/XMLSchema">
                        <SOAP-ENV:Header>
                            <eb:MessageHeader
                                        xmlns:eb="http://www.ebxml.org/namespaces/messageHeader">
                                        <eb:From>
                                            <eb:PartyId eb:type="urn:x12.org.IO5:01">"'.$this->config['sabre_email'].'"</eb:PartyId>
                                        </eb:From>
                                        <eb:To>
                                            <eb:PartyId eb:type="urn:x12.org.IO5:01">webservices.sabre.com</eb:PartyId>
                                        </eb:To>
                                        <eb:ConversationId>"'.$this->conversation_id.'"</eb:ConversationId>
                                        <eb:Service eb:type="OTA">Air</eb:Service>
                                        <eb:Action>EnhancedSeatMapRQ</eb:Action>
                                        <eb:CPAID>'.$this->config['ipcc'].'</eb:CPAID>
                                        <eb:MessageData>
                                            <eb:MessageId>"'.$this->message_id.'"</eb:MessageId>
                                            <eb:Timestamp>"'.$this->timestamp.'"</eb:Timestamp>
                                            <eb:TimeToLive>"'.$this->timetolive.'"</eb:TimeToLive>
                                        </eb:MessageData>
                                    </eb:MessageHeader>
                            <wsse:Security xmlns:wsse="http://schemas.xmlsoap.org/ws/2002/12/secext" xmlns:wsu="http://schemas.xmlsoap.org/ws/2002/12/utility">
                             <wsse:BinarySecurityToken valueType="String" EncodingType="wsse:Base64Binary">' . $this->api_session_id. '</wsse:BinarySecurityToken>
                            </wsse:Security>
                        </SOAP-ENV:Header>
                        <SOAP-ENV:Body>
                            <EnhancedSeatMapRQ xmlns="http://stl.sabre.com/Merchandising/v4">
                                <SeatMapQueryEnhanced>
                                    <RequestType>Payload</RequestType>
                                    <Flight destination="'.$segment_data[$j][$ss]['Destination']["AirportCode"].'" origin="'.$segment_data[$j][$ss]['Origin']["AirportCode"].'">
                                      <DepartureDate>'.$depature_date.'</DepartureDate>
                                      <Operating carrier="'.$segment_data[$j][$ss]["OperatorCode"].'">'.$segment_data[$j][$ss]["FlightNumber"].'</Operating>
                                      <Marketing carrier="'.$segment_data[$j][$ss]["MarkettingAirline"].'">'.$segment_data[$j][$ss]["FlightNumber"].'</Marketing>
                                      <ArrivalDate>'.$arrival_date.'</ArrivalDate>
                                    </Flight>
                                    <CabinDefinition><RBD>'.$cabin_class.'</RBD></CabinDefinition>
                                    <Currency>'.$appCurency.'</Currency>
                                    <POS><PCC>'.$this->config['ipcc'].'</PCC></POS>
                                    <JourneyData>
                                      <JourneyFlight>
                                        <Flight destination="'.$segment_data[$j][$ss]['Destination']["AirportCode"].'" origin="'.$segment_data[$j][$ss]['Origin']["AirportCode"].'">
                                          <DepartureDate>'.$depature_date.'</DepartureDate>
                                          <Operating carrier="'.$segment_data[$j][$ss]["OperatorCode"].'">'.$segment_data[$j][$ss]["FlightNumber"].'</Operating>
                                          <Marketing carrier="'.$segment_data[$j][$ss]["MarkettingAirline"].'">'.$segment_data[$j][$ss]["FlightNumber"].'</Marketing>
                                          <ArrivalDate>'.$arrival_date.'</ArrivalDate>
                                        </Flight> 
                                      </JourneyFlight>
                                    </JourneyData>
                                </SeatMapQueryEnhanced>
                            </EnhancedSeatMapRQ>
                        </SOAP-ENV:Body>
                    </SOAP-ENV:Envelope>';
                    // echo $request_params;exit;
                $seat_response = $this->process_request($request_params, $this->config['api_url'], 'EnhancedSeatMApping(Sabare)');
                
                // $seat_response = file_get_contents(FCPATH."seat_mapping_res.xml");
               
                $seat_response = Converter::createArray($seat_response);
                // debug($seat_response);
                if(isset($seat_response['soap-env:Envelope']['soap-env:Body']['soap-env:Fault']) ==false && isset($seat_response['soap-env:Envelope']['soap-env:Body']['EnhancedSeatMapRS']['ns3:ApplicationResults']['ns3:Error']) ==false){
                    $seatmapresponse[]  = $this->seatmapformating($seat_response, $segment_data[$j][$ss]); 
                } 

            }
        }
        // debug($seatmapresponse);
        // exit;
        return $seatmapresponse;
        // debug($seatmapresponse);exit;
    }
    private function seatmapformating($SeatmapRS, $segment_data)
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
     private function save_flight_ticket_details($booking_params, $booking_response, $app_reference, $sequence_number, $search_id){
        // debug($booking_response);exit;
        $flight_booking_transaction_details_fk = $this->CI->custom_db->single_table_records('flight_booking_transaction_details', 'origin', array('app_reference' => $app_reference, 'sequence_number' => $sequence_number));
        $flight_booking_itinerary_details_fk = $this->CI->custom_db->single_table_records('flight_booking_itinerary_details', 'airline_code,origin', array('app_reference' => $app_reference));

        $flight_booking_transaction_details_fk = $flight_booking_transaction_details_fk['data'][0]['origin'];
        $passenger_details = $this->CI->custom_db->single_table_records('flight_booking_passenger_details', '', array('app_reference' => $app_reference));
        $passenger_details =$passenger_details['data'];
        // debug($flight_booking_itinerary_details_fk);exit;
        foreach($flight_booking_itinerary_details_fk['data'] as $itinerary){
            $update_itinerary_condition = array();
            $update_itinerary_condition['flight_booking_transaction_details_fk'] = $itinerary['origin'];
            $update_itinerary_condition['app_reference'] = $app_reference;
            //itinerary updated data
            $update_itinerary_data = array();
            if(isset($book_response['airline_pnr'])){
               $airline_pnr = $book_response['airline_pnr'];
  
            }
            else{
                $airline_pnr ='';   
            }
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
        // debug($passenger_details);exit;
        foreach ($passenger_details as $pax_k => $pax_v) {
            $passenger_fk = intval(array_shift($passenger_origins));
            $pax_type = array_shift($passenger_types);
            
            switch ($pax_type) {
                case 'Adult':
                 $pax_type = 'ADT';
                    break;
                case 'Child':
                    $pax_type = 'CNN';
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
    private function save_book_response_details($book_response, $app_reference, $sequence_number) {
        $update_data = array();
        $update_condition = array();

        $update_data['pnr'] = $book_response['gds_pnr'];
        $update_data['book_id'] = $book_response['airline_pnr'];
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
    function form_curl_params($request, $url) {
        $data['status'] = SUCCESS_STATUS;
        $data['message'] = '';
        $data['data'] = array();

        $curl_data = array();
        $curl_data['booking_source'] = $this->booking_source;
        $curl_data['request'] = $request;
        $curl_data['url'] = $url;
        $curl_data['header'] = array( 'Content-Type: text/xml; charset="utf-8"');
        $data['data'] = $curl_data;
        // debug($data);exit;
        return $data;
    }
    /**
     * Process API Request
     * @param unknown_type $request
     * @param unknown_type $url
     */
    function process_request($request, $url, $remarks = '') {
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
        // debug($response);exit;
        //Update the API Response
        $this->CI->api_model->update_api_response($response, $insert_id);
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
}