<?php

require_once BASEPATH . 'libraries/flight/Common_api_flight.php';

class Pkfare extends Common_Api_Flight {


    var $master_search_data;
    var $search_hash;
    protected $token;
    private $end_user_ip = '127.0.0.1';
    var $api_session_id;

    function __construct() {

        parent::__construct(META_AIRLINE_COURSE, PK_FARE_FLIGHT_BOOKING_SOURCE);
        $this->CI = &get_instance();
        $this->CI->load->library('Converter');
        $this->CI->load->library('ArrayToXML');
    }
 
    /**
     * Search Request
     * @param unknown_type $search_id
     */
    public function get_search_request($search_id) {

        $response ['status'] = FAILURE_STATUS; // Status Of Operation
        $response ['message'] = ''; // Message to be returned
        $response ['data'] = array(); // Data to be returned
        $search_data = $this->search_data($search_id);
        if ( $this->search_allowed($search_data) === false) {
            // return failure object as the login signature is not set
            return $response;
        }
        /* get search criteria based on search id */
        
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
   private function search_allowed($search_data)
    {
        $search_data = $search_data['data'];
       
        if(empty($search_data['is_domestic']) == true){
            return TRUE;
        } else {
            return FALSE;
        }
       
    }
     /**
     * Formates Search Request
     */
    private function search_request($search_data) {
       
        // debug($search_data);exit;
        $search_params = $search_data;
        $depature_date = explode('T', $search_params['depature']);
        $search_request['search']['adults'] = (int)$search_params['adult_config'];
        $search_request['search']['children'] = (int)$search_params['child_config'];
        $search_request['search']['infants'] = (int)$search_params['infant_config'];
        $search_request['search']['nonstop'] = 0;
        if($search_data['trip_type'] == 'oneway' || $search_data['trip_type'] == 'return'){
            $search_air_legs[0] = array('cabinClass' => ucfirst($search_params['cabin_class']),
                                'departureDate' => $depature_date[0],
                                'destination' => $search_data['from'],
                                'origin' => $search_data['to']);
        }
        if($search_data['trip_type'] == 'return'){
            $return_date = explode('T', $search_params['return']);
            $search_air_legs[1] = array('cabinClass' => ucfirst($search_params['cabin_class']),
                'departureDate' => $return_date[0],
                'destination' => $search_data['to'],
                'origin' => $search_data['from']);
        }
        
        $search_request['search']['searchAirLegs'] = $search_air_legs;
        $search_request['search']['solutions'] = 0;
        $search_request['authentication']['partnerId'] = $this->config['PartnerID'];
        $search_request['authentication']['sign'] = $this->config['Sign'];
        $request ['request'] = base64_encode(json_encode($search_request));
        
        $request ['url'] = $this->config['Endpoint'].'shoppingV2';
        $request ['status'] = SUCCESS_STATUS;
        // debug($request);exit;
        return $request;
        // debug($request);exit;
    }
    public function get_flight_list($flight_raw_data, $search_id) {
    
        $response ['status'] = FAILURE_STATUS; // Status Of Operation
        $response ['message'] = ''; // Message to be returned
        $response ['data'] = array(); // Data to be returned
        $search_data = $this->search_data($search_id);
        $clean_format_data = array();
        $statu_array = array();
        if ($search_data ['status'] == SUCCESS_STATUS) {
            // $flight_raw_data =  gzdecode($flight_raw_data);
           // debug($flight_raw_data);exit;
            $api_response = json_decode($flight_raw_data, true);
          
            if ($this->valid_search_result($api_response) == TRUE) {
                $clean_format_data = $this->format_search_data_response($api_response, $search_data ['data']);
                
                if ($clean_format_data) {
                    $response ['status'] = SUCCESS_STATUS;
                } else {
                    $response ['status'] = FAILURE_STATUS;
                }
            }
            else{
                $response ['status'] = FAILURE_STATUS;
            }
            if ($response ['status'] == SUCCESS_STATUS) {
                $response ['data'] = $clean_format_data;
            }
        }
        else {
            $response ['status'] = FAILURE_STATUS;
        }
       
        return $response;
    }
      /**
     * Formates Search Response
     * Enter description here ...
     * @param unknown_type $search_result
     * @param unknown_type $search_data
     */
    function format_search_data_response($search_result, $search_data) {
        // debug($search_result);exit;
        $Results = $search_result['data']['solutions'];
        $searchKey = $search_result['data']['searchKey'];
        $flight_list_array = array();
        if(valid_array($Results)){
           foreach ($Results as $result_k => $result_v) {
            // if($result_v['solutionKey'] == 'ea61236e547ce87432d7dc80a728d316'){
                $flight_details = $this->flight_segment_summary($result_v, $result_k, $key, '',$search_data, $search_result['data']);
                    if (valid_array($flight_details)) {
                         $flight_list_array[$result_k] = $flight_details;
                         
                    }
                }
            // }
             
            $flight_list['JourneyList'][0] = $flight_list_array;
        }

        $response ['FlightDataList'] = $flight_list;
        // debug($response);exit;
        return $response;
    }
     /**
     * Get flight details only
     *
     * @param array $segment
     */
    private function flight_segment_summary($journey_array, $journey_number, & $key, $cache_fare_object = false, $search_data = '', $search_result='') {
        $flight_details = array();
        $final_flight = array();
        // Loop on data to form details array
        $details = array();
        $precise_price = array();
        $flightNumberList = array();
        $segment_intl = array();
        $FareSellKey = array();
        $fare_object = array();
    // debug($search_result);exit;
        $journeys = $journey_array['journeys'];
        // debug($journeys);exit;
        $flights = $search_result['flights'];

        $segments = $search_result['segments'];
        $attr = array();
        $journey_key = 0;
        foreach ($journeys as $k => $v) {
         
            foreach($flights as $flight_data){
                  // debug($flight_data);exit;
                if($v[0] == $flight_data['flightId']){
                    $is_leg = true;
                    if(isset($flight_data['segmengtIds'])){
                        if(count($flight_data['segmengtIds']) > 1){
                            $segment_ids = array_reverse($flight_data['segmengtIds']);
                        }
                        else{
                            $segment_ids = $flight_data['segmengtIds'];
                        }

                    } 
                    else if(isset($flight_data['segmentIds'])){
                        if(count($flight_data['segmentIds']) > 1){
                            $segment_ids = array_reverse($flight_data['segmentIds']);
                        }
                        else{
                            $segment_ids = $flight_data['segmentIds'];
                        }
                    }

                   
                    foreach( $segment_ids as $seg_key => $segments_data){
                        foreach($segments as $segment_info){
                            if($segments_data == $segment_info['segmentId']){

                                if(count($segment_ids) > 1 && ($seg_key == count($segment_ids)-1)){
                                   $total_duration = $flight_data['journeyTime'];
                                }
                                else{
                                    $total_duration = 0;
                                }
                                $origin_code = $segment_info['arrival']; 
                                $destination_code = $segment_info['departure'];
                                $departure_dt = $segment_info['strDepartureDate']." ".$segment_info['strDepartureTime'];
                                $arrival_dt = $segment_info['strArrivalDate']." ".$segment_info['strArrivalTime'];
                                $operator_code = $segment_info['airline'];
                                $no_of_stops = 0;
                                $cabin_class = $segment_info['cabinClass'];
                                $operator_name = $this->get_airline_name($operator_code);
                                $flight_number = $segment_info['flightNum'];
                                
                                $stop_over ='';
                                $booking_code = $segment_info['bookingCode'];
                                $departure_time = $segment_info['strDepartureTime'];
                                $arrival_time = $segment_info['strArrivalTime'];
                                $departure_date = $segment_info['strDepartureDate'];
                                $arrival_date = $segment_info['strArrivalDate'];
                                $duration = $segment_info['flightTime'];
                                if(isset($journey_array['baggageMap']['ADT'][0]['baggageWeight'])){
                                    $attr['Baggage'] = @$journey_array['baggageMap']['ADT'][0]['baggageWeight'];
                                }
                                else if(isset($journey_array['baggageMap']['ADT'][0]['baggageAmount'])){
                                    $attr['Baggage'] = @$journey_array['baggageMap']['ADT'][0]['baggageAmount'];
                                }
                                // echo $departure_dt;
                                // echo "sfgrdsgfd";
                                // echo $arrival_dt;exit;
                                $attr['CabinBaggage'] = @$journey_array['baggageMap']['ADT'][0]['carryOnWeight'];
                                $details[$journey_key][] = $this->format_summary_array($k, $origin_code, $destination_code, $arrival_dt, $departure_dt, $operator_code, $operator_name, $flight_number, $no_of_stops, $cabin_class, '', '', $duration, $is_leg, $attr, '','','', $total_duration);
                                $journey_key_price = 'journey_'.$journey_key;
                                $precise_price[$journey_key_price][] = $this->precise_price_format($origin_code, $destination_code, $departure_date, $arrival_date, $operator_code, $flight_number, $booking_code, $departure_time, $arrival_time, $flight_number);
                                $is_leg = false;
                            }
                        }
                       
                    }
                   
                }
            }
            $journey_key++;
        }
       
        //Fare
        $price = $this->format_itineray_price_details($journey_array, $search_data);
       debug($details);exit;
        $is_refundable ='';
        $AirlineRemark = '';
        $flight_details ['Details'] = $details;
        $flight_ar ['FlightDetails'] = $flight_details;
        $flight_ar ['Price'] = $price;
        $key['key'][0]['booking_source'] = $this->booking_source;
        $key['key'][0]['Precise_price'] = json_encode($precise_price);
        // debug($key);exit;
        // $key ['key'] [$journey_number]['IsLCC'] = $IsLCC;
        $flight_ar ['ResultToken'] = serialized_data($key['key']);

        $flight_ar ['Attr']['IsRefundable'] = $is_refundable;
        $flight_ar ['Attr']['AirlineRemark'] = $AirlineRemark;
        $final_flight = $flight_ar;
        $response = $final_flight;
       
        return $response;
    }
    /**
     * Get flight details only
     *
     * @param array $segment
     */
    private function precise_price_format($origin_code, $destination_code, $departure_dt, $arrival_dt, $operator_code, $flight_number, $booking_code, $departure_time, $arrival_time){
        $summary_array = array ();
        $summary_array ['arrival'] = $origin_code; // Airline code 9w
        $summary_array ['airline'] = $operator_code;
        $summary_array ['arrivalDate'] = $arrival_dt; // Airline name
        $summary_array ['arrivalTime'] = $arrival_time;
        $summary_array ['bookingCode'] = $booking_code;
        $summary_array ['departure'] = $destination_code;
        $summary_array ['departureDate'] = $departure_dt;
        $summary_array ['departureTime'] = $departure_time;
        $summary_array ['flightNum'] = $flight_number;
        // debug($summary_array);exit;
        return $summary_array;
    }
    /**
     * Balu A
     * Calculates the flight segment duration based on airport time zone offset
     * @param $departure_airport_code
     * @param $arrival_airport_code
     * @param $departure_datetime
     * @param $arrival_datetime
     */
    private function wating_segment_time($arrival_airport_city, $departure_airport_city, $arrival_datetime, $departure_datetime) {
        $departure_datetime = date('Y-m-d H:i:s', strtotime($departure_datetime));
        $arrival_datetime = date('Y-m-d H:i:s', strtotime($arrival_datetime));
        //Get TimeZone of Departure and Arrival Airport
        $departure_timezone_offset = $GLOBALS['CI']->flight_model->get_airport_timezone_offset($departure_airport_city, $departure_datetime);
        $arrival_timezone_offset = $GLOBALS['CI']->flight_model->get_airport_timezone_offset($arrival_airport_city, $arrival_datetime);
     
        //Converting TimeZone to Minutes
        $departure_timezone_offset = $this->convert_timezone_offset_to_minutes($departure_timezone_offset);
        $arrival_timezone_offset = $this->convert_timezone_offset_to_minutes($arrival_timezone_offset);
        //Getting Total time difference between 2 airports
        $timezone_offset = ($arrival_timezone_offset - $departure_timezone_offset);
        //Calculating the Waiting time between 2 segments
        $current_segment_arr = strtotime($arrival_datetime);
        $next_segment_dep = strtotime($departure_datetime);
        $segment_waiting_time = ($next_segment_dep - $current_segment_arr);

        //Converting into minutes
        $segment_waiting_time = ($segment_waiting_time) / 60; //Converting into minutes
        //Updating the total duration with time zone offset difference
        $segment_waiting_time = ($segment_waiting_time + $timezone_offset);
        return $segment_waiting_time;
    }
     /**
     * Converts the time zone offset to minutes
     * @param unknown_type $timezone_offset
     */
    private function convert_timezone_offset_to_minutes($timezone_offset) {
        $add_mode_sign = $timezone_offset[0];
        $time_zone_details = explode(':', $timezone_offset);
        $hours = abs(intval($time_zone_details[0]));
        $minutes = abs(intval($time_zone_details[1]));
        $minutes = $hours * 60 + $minutes;
        $minutes = ($add_mode_sign . $minutes);
        return $minutes;
    }
    /**
     * Formates Itineray Price Details
     * @param unknown_type $itineray_price
     */
    private function format_itineray_price_details($itineray_price, $search_data) {
        $api_currency = 'USD'; 

        $conversion_amount = $GLOBALS ['CI']->domain_management_model->get_currency_conversion_rate($api_currency);
        $conversion_amount = $conversion_amount['conversion_rate'];
        // $conversion_amount= 1;
        $api_currency = 'INR'; 
        $total_adult_fare = 0;
        $total_child_fare = 0;
        $total_infant_fare = 0;
        if($search_data['adult_config'] > 0){
            $pax_type = 'ADT';
            $passenger_breakup[$pax_type]['BasePrice'] = $conversion_amount*$itineray_price['adtFare'];
            $passenger_breakup[$pax_type]['Tax'] = $conversion_amount*($itineray_price['adtTax']+$itineray_price['tktFee']);
            $passenger_breakup[$pax_type]['TotalPrice'] = $total_adult_fare = $conversion_amount*($itineray_price['adtFare']+$itineray_price['adtTax']+$itineray_price['tktFee']);
            $passenger_breakup[$pax_type]['PassengerCount'] = $search_data['adult_config'];
        }
        if($search_data['child_config'] > 0){
            $pax_type = 'CHD';
            $passenger_breakup[$pax_type]['BasePrice'] = $conversion_amount*$itineray_price['chdFare'];
            $passenger_breakup[$pax_type]['Tax'] = $conversion_amount*($itineray_price['chdTax']+$itineray_price['tktFee']);
            $passenger_breakup[$pax_type]['TotalPrice'] = $total_child_fare = $conversion_amount*($itineray_price['chdFare']+$itineray_price['chdTax']+$itineray_price['tktFee']);
            $passenger_breakup[$pax_type]['PassengerCount'] = $search_data['child_config'];
        }
        if($search_data['infant_config'] > 0){
            $pax_type = 'INF';
            $passenger_breakup[$pax_type]['BasePrice'] = $conversion_amount*$itineray_price['infFare'];
            $passenger_breakup[$pax_type]['Tax'] = $conversion_amount*($itineray_price['infTax']+$itineray_price['tktFee']);
            $passenger_breakup[$pax_type]['TotalPrice'] = $total_infant_fare = $conversion_amount*($itineray_price['infFare']+$itineray_price['infTax']+$itineray_price['tktFee']);
            $passenger_breakup[$pax_type]['PassengerCount'] = $search_data['infant_config'];
        }
        
        $total_fare = $search_data['adult_config']*$total_adult_fare+$search_data['child_config']*$total_child_fare+$search_data['infant_config']*$total_infant_fare+$itineray_price['platformServiceFee']+$itineray_price['platformServiceFee']+$itineray_price['merchantFee'];
        $total_tax = $search_data['adult_config']*$itineray_price['adtTax']+$search_data['child_config']*$itineray_price['chdTax']+$search_data['infant_config']*@$itineray_price['infTax'];
        $total_base_fare = $search_data['adult_config']*$itineray_price['adtFare']+$search_data['child_config']*$itineray_price['chdFare']+$search_data['infant_config']*@$itineray_price['infFare'];
        $agent_commission = 0;
        $agent_tds= 0;
        $currency_code = $itineray_price['currency'];
        $price = $this->get_price_object();
        $price['Currency'] = $api_currency;
        $price['TotalDisplayFare'] = $total_fare;
        $price['PriceBreakup']['Tax'] = $conversion_amount*$total_tax;
        $price['PriceBreakup']['BasicFare'] = $conversion_amount*$total_base_fare;
        $price['PriceBreakup']['AgentCommission'] = $agent_commission; // need to check
        $price['PriceBreakup']['AgentTdsOnCommision'] = $agent_tds;// need to check

        $price['PassengerBreakup'] = $passenger_breakup;
       
   
        return $price;
    }
    /**
     * Fare Rule
     * @param unknown_type $request
     */
    public function get_fare_rules($request) {
        // debug($request);exit;
        $response ['status'] = FAILURE_STATUS; // Status Of Operation
        $response ['message'] = ''; // Message to be returned
        $response ['data'] = array(); // Data to be returned
        $fare_rule_request = $this->fare_rule_request($request);
        if ($fare_rule_request['status'] == SUCCESS_STATUS) {
            $fare_rule_response = $this->process_request($fare_rule_request ['request'], $fare_rule_request ['url'], $fare_rule_request ['remarks']);
            debug($fare_rule_response);exit;
            $fare_rule_response = json_decode($fare_rule_response, true);
            if (valid_array($fare_rule_response) == true && isset($fare_rule_response['Response']) == true && $fare_rule_response['Response']['ResponseStatus'] == SUCCESS_STATUS) {
                $response ['status'] = SUCCESS_STATUS;
                $response ['data']['FareRuleDetail'] = $this->format_fare_rule_response($fare_rule_response);
            } else {
                $response ['message'] = 'Not Available';
            }
        } else {
            $response ['status'] = FAILURE_STATUS;
        }
        return $response;
    }
     /**
     * Forms the fare rule request
     * @param unknown_type $request
     */
    private function fare_rule_request($params) {
        $request = array();
        $fare_rule_request = array();
        $fare_rule_request['authentication']['partnerId'] = $this->config['PartnerID'];
        $fare_rule_request['authentication']['sign'] = $this->config['Sign'];
        $fare_rule_request['penalty']['journeys'] = json_decode($params['Precise_price'], true);
        
        $request ['request'] = base64_encode(json_encode($fare_rule_request));
        $request ['url'] = $this->config['Endpoint'] . 'penalty';
        $request ['remarks'] = 'FareRule(PKfare)';
        $request ['status'] = SUCCESS_STATUS;
        // debug($request);exit;
        return $request;
    }
     function format_fare_rule_response($fare_rule_response) {
        $fare_rules = array();
        foreach ($fare_rule_response as $k => $v) {
            
            
            $fare_rules[$k]['Origin'] = $v['Origin'];
            $fare_rules[$k]['Destination'] = $v['Destination'];
            $fare_rules[$k]['Airline'] = $v['Airline'];


            $domain_base_currency = domain_base_currency();
            // $domain_base_currency="USD";

            $currency_obj = new Currency(array('from' => get_application_default_currency(), 'to' => $domain_base_currency));

            $FareRulesArray = explode(" ", $v['FareRules']);

            foreach ($FareRulesArray as $key => $value) {
 
                if (trim($FareRulesArray[$key])=="INR" || trim($FareRulesArray[$key])=="-INR") {

                    $FareRulesArray[$key] = $domain_base_currency;
                    if (is_numeric($FareRulesArray[$key - 1])) {
                        $FareRulesArray[$key - 1] = get_converted_currency_value($currency_obj->force_currency_conversion($FareRulesArray[$key - 1]));
                        
                    }
                    else
                    {
                        $Amount = preg_replace("/[^0-9]/", "", $FareRulesArray[$key - 1]);
                        if(is_numeric($Amount))
                        {
                           
                           $FareRulesArray[$key - 1] = get_converted_currency_value($currency_obj->force_currency_conversion($Amount));
                        }
                    }
                    if (is_numeric($FareRulesArray[$key + 1])) {
                         
                        $FareRulesArray[$key + 1] = get_converted_currency_value($currency_obj->force_currency_conversion($FareRulesArray[$key + 1]));
                        
                    }
                    else
                    {
                        $Amount = preg_replace("/[^0-9]/", "", $FareRulesArray[$key + 1]);
                        if(is_numeric($Amount))
                        {
                           $FareRulesArray[$key + 1] = get_converted_currency_value($currency_obj->force_currency_conversion($Amount));
                        }
                    }
                }
            }
            
            $fare_rules[$k]['FareRules'] = implode(" ",$FareRulesArray);
           
        }
      
        return $fare_rules;
    }
     /**
     * Update Fare Quote
     * @param unknown_type $request
     */
    public function get_update_fare_quote($request, $search_id) {
       
        $response ['status'] = FAILURE_STATUS; // Status Of Operation
        $response ['message'] = ''; // Message to be returned
        $response ['data'] = array(); // Data to be returned
        $update_fare_quote_request = $this->update_fare_quote_request($request, $search_id);
        if ($update_fare_quote_request['status'] == SUCCESS_STATUS) {
            $update_fare_quote_response = $this->process_request($update_fare_quote_request ['request'], $update_fare_quote_request ['url'], $update_fare_quote_request ['remarks']);
            // $update_fare_quote_response = $this->CI->custom_db->get_static_response (625);

            $update_fare_quote_response = json_decode($update_fare_quote_response, true);
            
            if ((valid_array($update_fare_quote_response) == true) && ($update_fare_quote_response['errorCode'] == 0) && ($update_fare_quote_response['errorMsg'] == 'ok')) {
                $response ['status'] = SUCCESS_STATUS;
                $response ['data']['FareQuoteDetails'] = $this->format_update_fare_quote_response($update_fare_quote_response, $search_id);
            } else {
                $response ['message'] = 'Not Available';
            }
        } else {
            $response ['status'] = FAILURE_STATUS;
        }
        return $response;
    }
     /**
     * Forms the update fare quote request
     * @param unknown_type $request
     */
    private function update_fare_quote_request($params, $search_id) {
        $request = array();
        $fare_quote_request = array();
        $search_data = $this->search_data($search_id);
        $fare_quote_request['authentication']['partnerId'] = $this->config['PartnerID'];
        $fare_quote_request['authentication']['sign'] = $this->config['Sign'];
        $fare_quote_request['pricing']['adults'] = $search_data['data']['adult_config'];
        $fare_quote_request['pricing']['children'] = $search_data['data']['child_config'];
        $fare_quote_request['pricing']['journeys'] = json_decode($params['Precise_price'], true);
        
        $request ['request'] = base64_encode(json_encode($fare_quote_request));
        $request ['url'] = $this->config['Endpoint'] . 'precisePricing_V2';
        $request ['remarks'] = 'PrecisePricing(PKfare)';
        $request ['status'] = SUCCESS_STATUS;
      
        return $request;
    }
     /**
     * Format Update Farequote Response
     * @param unknown_type $update_fare_quote_response
     */
    function format_update_fare_quote_response($update_fare_quote_response, $search_id) {
        $search_data = $this->search_data($search_id);
        $search_data= $search_data['data'];
      // debug($update_fare_quote_response);exit;
        $Results = force_multple_data_format($update_fare_quote_response['data']['solution']);
        $solutionkey = $update_fare_quote_response['data']['solution']['solutionKey'];
        $flight_list_array = array();
       
        if(valid_array($Results)){
           foreach ($Results as $result_k => $result_v) {
                $key = array();
                $key['key'][$result_k]['booking_source'] = $this->booking_source;
                $key['key'][$result_k]['TraceId'] = $solutionkey;
                $price_array['adtFare'] = $result_v['adtFare'];
                $price_array['adtTax'] = $result_v['adtTax'];
                $price_array['chdFare'] = $result_v['chdFare'];
                $price_array['chdTax'] = $result_v['chdTax'];
                if($search_data['infant_config'] > 0){
                    $price_array['infFare'] = $result_v['infFare'];
                    $price_array['infTax'] = $result_v['infTax'];
                }
                $key['key'][$result_k]['Price'] = $price_array;
                $flight_details = $this->flight_segment_summary($result_v, $result_k, $key, '',$search_data, $update_fare_quote_response['data']);
                if (valid_array($flight_details)) {
                    $flight_details['HoldTicket'] = true;
                    $flight_list_array[$result_k] = $flight_details;
                }
            }
            $flight_list['JourneyList'][0] = $flight_list_array;
        }

        $response = $flight_list;
        return $response;

    }
     /**
     * Extra Services
     * @param unknown_type $request
     */
    public function get_extra_services($request, $search_id) {

        $response ['status'] = FAILURE_STATUS; // Status Of Operation
        $response ['message'] = ''; // Message to be returned
        $response ['data'] = array(); // Data to be returned
        $search_data = $this->search_data($search_id);
        $flight_data = unserialized_data($request['ResultToken']);
        if (valid_array($request) == true && valid_array($flight_data)) {
            if(isset($flight_data[0]['Signature'])){
                $this->api_session_id = $flight_data[0]['Signature'];
            }
        }
        if (valid_array ($ssr_response ) || valid_array ($seat_available_res )) {
            $response ['status'] = SUCCESS_STATUS;
            $response ['data']['ExtraServiceDetails'] = $this->format_extra_services($ssr_response, $flight_data, $search_id, $seat_available_res);
        }
        else{
            $response ['message'] = 'Not Available';
        }
        // debug($response);exit;
        return $response;
        
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
        $book_service_response = $this->run_book_service($booking_params, $app_reference, $sequence_number, $search_id);
        if ($book_service_response['status'] == SUCCESS_STATUS) {
            $response ['status'] = SUCCESS_STATUS;
            $book_response = $book_service_response['data']['book_response'];
            // debug($book_response);exit;
            //Save BookFlight Details
            $this->save_book_response_details($book_response, $app_reference, $sequence_number);
            $ticketing_status = 'Tikcet';
            if ($ticketing_status == "Tikcet") {
                 //Run Non-LCC Ticket method
                $ticket_request_params = array();
                $ticket_request_params['PNR'] = $book_response['data']['pnr'];
                $ticket_request_params['BookingId'] = $book_response['data']['orderNum'];
                $ticket_request_params['name'] = $booking_params['Passengers'][0]['FirstName'];
                $ticket_request_params['telNum'] = $booking_params['Passengers'][0]['ContactNo'];
                $ticket_request_params['email'] = $booking_params['Passengers'][0]['Email'];
                $ticket_service_response = $this->run_non_lcc_ticket_service($ticket_request_params, $app_reference, $sequence_number);
                if ($ticket_service_response['status'] == SUCCESS_STATUS) {
                    $ticket_response = $ticket_service_response['data']['ticket_response'];
                    // $flight_booking_status = 'BOOKING_CONFIRMED';
                    // $this->CI->common_flight->update_flight_booking_status($flight_booking_status, $app_reference, $sequence_number, $this->booking_source);
                }
            }
        }
        else{
            $response ['message'] = $book_service_response['message'];
            $flight_booking_status = 'BOOKING_FAILED';
            $this->CI->common_flight->update_flight_booking_status($flight_booking_status, $app_reference, $sequence_number, $this->booking_source);
        }

        return $response;
   
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
        $book_service_request = $this->run_book_service_request($booking_params, $search_id);
      
        if ($book_service_request['status'] == SUCCESS_STATUS) {
            $book_service_response = $this->process_request($book_service_request ['request'], $book_service_request ['url'], $book_service_request ['remarks']);
            // debug($book_service_response);exit;
            // $book_service_response = $this->CI->custom_db->get_static_response (698);
            $book_service_response = json_decode($book_service_response, true);
            if (valid_array($book_service_response) == true && ($book_service_response['errorCode'] == 0 ) &&($book_service_response['errorMsg'] == 'ok') ) {
                $response ['status'] = SUCCESS_STATUS;
                $response ['data']['book_response'] = $book_service_response;
            } else {
                $error_message = '';
                if (isset($book_service_response['errorMsg'])) {
                    $error_message = $book_service_response['errorMsg'];
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
    /* * Forms the Book request
     * @param unknown_type $request
     */
    private function run_book_service_request($params, $search_id) {
        $request = array();
        $book_request = array();
        $book_request['authentication']['partnerId'] = $this->config['PartnerID'];
        $book_request['authentication']['sign'] = $this->config['Sign'];
        $passenger_array = array();

        foreach($params['Passengers'] as $pass_key => $passenger){
            $passenger_type = $this->get_passenger_type($passenger['PaxType']);
            if($passenger['Gender'] == 1){
                $gender = 'M';
            }
            else{
                $gender = 'F';
            }
            $passenger_array[$pass_key]['birthday'] = $passenger['DateOfBirth'];
            $passenger_array[$pass_key]['cardExpiredDate'] = $passenger['PassportExpiry'];
            $passenger_array[$pass_key]['cardNum'] = $passenger['PassportNumber'];
            $passenger_array[$pass_key]['cardType'] = 'P';
            $passenger_array[$pass_key]['firstName'] = $passenger['FirstName'];
            $passenger_array[$pass_key]['lastName'] = $passenger['LastName'];
            $passenger_array[$pass_key]['nationality'] = $passenger['CountryCode'];
            $passenger_array[$pass_key]['psgType'] = $passenger_type;
            $passenger_array[$pass_key]['sex'] = $gender;
        }
        // debug($params);
        $precise_price = json_decode($params['ResultToken']['Precise_price'], true);
        $precise_prices['journeys'] = $precise_price;
        $precise_price_merge = array_merge($params['ResultToken']['Price'], $precise_prices);
       
        $book_request['booking']['passengers'] = $passenger_array;
        $book_request['booking']['solution'] = $precise_price_merge;
        
        $request ['request'] = base64_encode(json_encode($book_request));
        $request ['url'] = $this->config['Endpoint'] . 'preciseBooking';
        $request ['remarks'] = 'Book(PKfare)';
        $request ['status'] = SUCCESS_STATUS;
        
        return $request;
    }
     /**
     *
     * Enter description here ...
     * @param unknown_type $booking_params
     * @param unknown_type $app_reference
     * @param unknown_type $sequence_number
     */
    private function run_non_lcc_ticket_service($booking_params, $app_reference, $sequence_number) {
        $response ['status'] = FAILURE_STATUS; // Status Of Operation
        $response ['message'] = ''; // Message to be returned
        $response ['data'] = array(); // Data to be returned

        $non_lcc_ticket_service_request = $this->run_non_lcc_ticket_service_request($booking_params);
       
        if ($non_lcc_ticket_service_request['status'] == SUCCESS_STATUS) {

            $non_lcc_ticket_service_response = $this->process_request($non_lcc_ticket_service_request ['request'], $non_lcc_ticket_service_request ['url'], $non_lcc_ticket_service_request ['remarks']);
           
            // $non_lcc_ticket_service_response = $this->CI->custom_db->get_static_response (694);//Static bOOk Data//4=>Failed;3=> Success

            $non_lcc_ticket_service_response = json_decode($non_lcc_ticket_service_response, true);
            if (valid_array($non_lcc_ticket_service_response) == true && ($non_lcc_ticket_service_response['errorCode'] == 0 ) &&($non_lcc_ticket_service_response['errorMsg'] == 'ok') ) {
                $response ['status'] = SUCCESS_STATUS;
                $response ['data']['ticket_response'] = $non_lcc_ticket_service_response;
            } else {
                $error_message = '';
                if (isset($non_lcc_ticket_service_response['errorMsg'])) {
                    $error_message = $non_lcc_ticket_service_response['errorMsg'];
                }
                if (empty($error_message) == true) {
                    $error_message = 'Ticketing Failed';
                }
                $response ['message'] = $error_message;
                //Log Exception
                $exception_log_message = '';
                $this->CI->exception_logger->log_exception($app_reference, $this->booking_source_name . '- (<strong>TICKET</strong>)', $exception_log_message, $non_lcc_ticket_service_response);
            }
        } else {
            $response ['status'] = FAILURE_STATUS;
        }
        return $response;
    }
    /**
     * Forms the Non-LCC Ticket request
     * @param unknown_type $request
     */
    private function run_non_lcc_ticket_service_request($params) {

        $request ['status'] = FAILURE_STATUS;
        $order_price_request['authentication']['partnerId'] = $this->config['PartnerID'];
        $order_price_request['authentication']['sign'] = $this->config['Sign'];
        $order_price_request['orderPricing']['orderNum'] = $params['BookingId'];
        $price_request = base64_encode(json_encode($order_price_request));
        $url = $this->config['Endpoint'] . 'orderPricing';
        $remarks = 'OrderPricing(PKFare)';
        
        $order_price_response = $this->process_request($price_request, $url, $remarks);
        // debug($order_price_response);exit;
        $order_price_response = json_decode($order_price_response, true);
        
        if (valid_array($order_price_response) == true && ($order_price_response['errorCode'] == 0 ) &&($order_price_response['errorMsg'] == 'ok') ) {
            $ticket_request['authentication']['partnerId'] = $this->config['PartnerID'];
            $ticket_request['authentication']['sign'] = $this->config['Sign'];
            $ticket_request['ticketing']['email'] = $params['email'];
            $ticket_request['ticketing']['name'] = $params['name'];
            $ticket_request['ticketing']['orderNum'] = $params['BookingId'];
            $ticket_request['ticketing']['telNum'] = $params['telNum'];
            $ticket_request['ticketing']['PNR'] = $params['PNR'];
            $request ['status'] = SUCCESS_STATUS;
            $request ['request'] = base64_encode(json_encode($ticket_request));
            $request ['url'] = $this->config['Endpoint'] . 'ticketing';
            $request ['remarks'] = 'Ticket(PKfare)';
        }
        return $request;
    }
    /**
     * Process Cancel Booking
     * Online Cancellation
     */
    public function cancel_booking($request) {
        // debug($request);exit;
        $response ['status'] = FAILURE_STATUS; // Status Of Operation
        $response ['message'] = ''; // Message to be returned
        $response ['data'] = array(); // Data to be returned

        $app_reference = $request['AppReference'];
        $sequence_number = $request['SequenceNumber'];
        $IsFullBookingCancel = $request['IsFullBookingCancel'];
        $ticket_ids = $request['TicketId'];

        $elgible_for_ticket_cancellation = $this->CI->common_flight->elgible_for_ticket_cancellation($app_reference, $sequence_number, $ticket_ids, $IsFullBookingCancel, $this->booking_source);
        // $elgible_for_ticket_cancellation['status'] = SUCCESS_STATUS;
       
        if ($elgible_for_ticket_cancellation['status'] == SUCCESS_STATUS) {
            $booking_details = $this->CI->flight_model->get_flight_booking_transaction_details($app_reference, $sequence_number, $this->booking_source);
            $booking_details = $booking_details['data'];
            $booking_transaction_details = $booking_details['booking_transaction_details'][0];
            $flight_booking_transaction_details_origin = $booking_transaction_details['origin'];
            $request_params = $booking_details;
            $request_params['passenger_origins'] = $ticket_ids;
            $send_change_request = $this->send_change_request($request);
            // debug($send_change_request);exit;
            if ($send_change_request['status'] == SUCCESS_STATUS) {
                $response ['status'] = SUCCESS_STATUS;
                $response ['message'] = 'Cancellation Request is processing';
                $send_change_response = $send_change_request['data']['send_change_response'];
                $passenger_origin = $request_params['passenger_origins'];
                $pass_count = count($passenger_origin);
                // debug($passenger_origin);exit;
                foreach ($passenger_origin as $origin) {
                    $this->save_ticket_cancellation_details($send_change_response, $origin, $pass_count, $request);
                    $this->CI->common_flight->update_ticket_cancel_status($app_reference, $sequence_number, $origin);
                }
            
            } else {
                $response ['message'] = $send_change_request['message'];
            }
        } else {
            $response ['message'] = $elgible_for_ticket_cancellation['message'];
        }
        // debug($response);exit;
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
            $send_change_response = $this->process_request($send_change_request ['request'], $send_change_request ['url'], $send_change_request ['remarks']);
           
            $send_change_response = json_decode($send_change_response, true);
            if (valid_array($send_change_response) == true && ($send_change_response['errorCode'] == 0 ) &&($send_change_response['errorMsg'] == 'ok') ) {
                $response ['status'] = SUCCESS_STATUS;
                $response ['data']['send_change_response'] = $send_change_response;
            }
            else{
                $error_message = 'Cancellation Failed';
                $response ['message'] = $error_message;
            }
        }
        return $response;
    }
     /**
     * Forms the SendChangeRequest
     * @param unknown_type $request
     */
    private function format_send_change_request($params) {
        $send_change_request['authentication']['partnerId'] = $this->config['PartnerID'];
        $send_change_request['authentication']['sign'] = $this->config['Sign'];
        $send_change_request['cancel']['orderNum'] = (int)$params['BookingId'];
        $send_change_request['cancel']['virtualPnr'] = $params['PNR'];
        $request ['request'] = base64_encode(json_encode($send_change_request));
        $request ['url'] = $this->config['Endpoint'] . 'cancel';
        $request ['remarks'] = 'SendChangeRequest(PKfare)';
        $request ['status'] = SUCCESS_STATUS;
        return $request;
      
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

        $update_data['pnr'] = $book_response['data']['pnr'];
        $update_data['book_id'] = $book_response['data']['orderNum'];

        $update_condition['app_reference'] = $app_reference;
        $update_condition['sequence_number'] = $sequence_number;

        $this->CI->custom_db->update_record('flight_booking_transaction_details', $update_data, $update_condition);

        $flight_booking_status = 'BOOKING_HOLD';
        $this->CI->common_flight->update_flight_booking_status($flight_booking_status, $app_reference, $sequence_number, $this->booking_source);
    }
    /* save flight ticket details */
    private function save_flight_ticket_details($booking_params, $book_response, $app_reference, $sequence_number, $search_id){
        // debug($booking_params);exit;
        $flight_booking_transaction_details = $this->CI->custom_db->single_table_records('flight_booking_transaction_details', '*', array('app_reference' => $app_reference, 'sequence_number' => $sequence_number));
        $flight_booking_transaction_details_fk = $flight_booking_transaction_details['data'][0]['origin'];
        $flight_booking_itinerary_details_fk = $this->CI->custom_db->single_table_records('flight_booking_itinerary_details', 'airline_code', array('app_reference' => $app_reference));
        $pnr = $book_response['s:Envelope']['s:Body']['BookingCommitResponse']['BookingUpdateResponseData']['Success']['RecordLocator'];
        $book_id = $pnr;

        //1. Update Bookinf Id and PNR Details
        $update_pnr_data = array();
        $update_pnr_data['book_id'] = $book_id;
        $update_pnr_data['pnr'] = $pnr;
        $this->CI->custom_db->update_record('flight_booking_transaction_details', $update_pnr_data, array('origin' => $flight_booking_transaction_details_fk));

        //2.Update Price Details
        $passenger_details = $this->CI->custom_db->single_table_records('flight_booking_passenger_details', '', array('app_reference' => $app_reference));
        $passenger_details =$passenger_details['data'];
        // debug($passenger_details);exit;
        $itineray_price_details = $booking_params['flight_data']['PriceBreakup'];
        $itineray_price_details['TotalDisplayFare'] = $flight_booking_transaction_details['data'][0]['total_fare'];
  
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
        //I have to fix this one
        // $this->CI->common_flight->update_flight_booking_tranaction_price_details($app_reference, $sequence_number, $fare_details['commissionable_fare'], $fare_details['admin_commission'], $fare_details['agent_commission'], $fare_details['admin_tds'], $fare_details['agent_tds'], $fare_details['admin_markup'], $fare_breakup);
        $update_data['fare_breakup'] = json_encode($itineray_price_details);
        
        $update_condition = array();
        $update_condition['app_reference'] = $app_reference;
        $update_condition['sequence_number'] = $sequence_number;
        $this->CI->custom_db->update_record('flight_booking_transaction_details', $update_data, $update_condition);
        //update Airline PNR
        $airsegment = $booking_params['flight_data']['FlightDetails']['Details'][0];
        
        foreach($airsegment as $seg_key => $seg_value){
            
            $origin = $seg_value['Origin']['AirportCode'];
            $destination = $seg_value['Destination']['AirportCode'];
            $dept_time = $seg_value['Origin']['DateTime'];
           
            //itinerary condition for update
            $update_itinerary_condition = array();
            $update_itinerary_condition['flight_booking_transaction_details_fk'] = $flight_booking_transaction_details_fk;
            $update_itinerary_condition['app_reference'] = $app_reference;
            $update_itinerary_condition['from_airport_code'] = $origin;
            $update_itinerary_condition['to_airport_code'] = $destination;
            $update_itinerary_condition['departure_datetime'] = $dept_time;

            //itinerary updated data
            $update_itinerary_data = array();
            $update_itinerary_data['airline_pnr'] = $pnr;
            $GLOBALS['CI']->custom_db->update_record('flight_booking_itinerary_details', $update_itinerary_data, $update_itinerary_condition);
        }
       

        // $passenger_details = force_multple_data_format($passenger_details);
        $get_passenger_details_condition = array();
        $get_passenger_details_condition['flight_booking_transaction_details_fk'] = $flight_booking_transaction_details_fk;
        $passenger_details_data = $GLOBALS['CI']->custom_db->single_table_records('flight_booking_passenger_details', 'origin, passenger_type', $get_passenger_details_condition);
        $passenger_details_data = $passenger_details_data['data'];
        $passenger_origins = group_array_column($passenger_details_data, 'origin');
        $passenger_types = group_array_column($passenger_details_data, 'passenger_type');
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
            $ticket_id = $pnr;
            $tkt_number = $pnr;
          
            //Update Passenger Ticket Details
            $this->CI->common_flight->update_passenger_ticket_info($passenger_fk, $ticket_id, $tkt_number, @$single_pax_fare_breakup[$pax_type]);
        }
      
    }
    /**
     * Save Cancellation Details
     * @param unknown_type $cancellation_details
     * @param unknown_type $passenger_origin
     */
    public function save_ticket_cancellation_details($cancellation_details, $passenger_origin, $pass_count, $request) {
        $tcrn_number = $request['BookingId'];
        $refund_amount = 0;
        $cancellation_chargers = 0;
        $cancelltion_tax = 0;
        $refund_amount = 0;
        $data['cancellation_processed_on'] = date('Y-m-d H:i:s');
        $data['RequestId'] = $tcrn_number;
        $data['API_RefundedAmount'] = ltrim($refund_amount,'-');
        $data['API_CancellationCharge'] = '0.00';
        $data['API_ServiceTaxOnRefundAmount'] = $cancelltion_tax;
        $data['API_SwachhBharatCess'] = '0.00';
        $data['API_KrishiKalyanCess'] = '0.00';
        $data['ChangeRequestStatus'] = 1;
        $data['statusDescription'] = 'Unassigned';
        $data['current_status'] = 1;
        $pax_cancel_details_exists = $this->CI->custom_db->single_table_records('flight_cancellation_details', '*', array('passenger_fk' => $passenger_origin));
        if ($pax_cancel_details_exists['status'] == true) {
            //Update the Data
            $this->CI->custom_db->update_record('flight_cancellation_details', $data, array('passenger_fk' => $passenger_origin));
        } else {
            //Insert Data
            $data['passenger_fk'] = $passenger_origin;
            $data['created_by_id'] = intval(@$this->entity_user_id);
            $data['created_datetime'] = date('Y-m-d H:i:s');
            $data['cancellation_requested_on'] = date('Y-m-d H:i:s');
            $this->CI->custom_db->insert_record('flight_cancellation_details', $data);
        } 
        // debug($cancellation_details);exit;
    }
   
     /**
     * check if the search RS is valid or not
     * @param array $search_result
     * search result RS to be validated
     */
    private function valid_search_result($flight_raw_data) {
        if($flight_raw_data['errorCode'] == 0 && $flight_raw_data['errorMsg'] == 'ok'){
            return true;
        }
     }
   
    function valid_cancellation_response($cancellation_response) {
        if (isset ( $cancellation_response ['s:Envelope'] ['s:Body'] ['CancelResponse'] ['BookingUpdateResponseData'] ['Success'] )) {
            return true;
        }
        return false;
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
        $curl_data['header'] = array( 'Content-Type: application/json; charset="utf-8"');
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
        
        $api_url = $url.'?param='.$request;
        $insert_id = $this->CI->api_model->store_api_request($url, $request, $remarks);
       
          // echo $request;exit;
        $insert_id = intval(@$insert_id['insert_id']);

        try {
            $httpHeader = array( 'Content-Type: text/xml; charset="utf-8"');
            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL, $api_url); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
            curl_setopt($ch, CURLOPT_TIMEOUT, 60); 
            curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeader);
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
    public function search_data($search_id) {
        $response ['status'] = true;
        $response ['data'] = array();
        if (empty($this->master_search_data) == true and valid_array($this->master_search_data) == false) {
            $clean_search_details = $this->CI->flight_model->get_safe_search_data($search_id);

            if ($clean_search_details ['status'] == true) {
                $response ['status'] = true;
                $response ['data'] = $clean_search_details ['data'];
                //debug($clean_search_details);exit;
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
    /**
     * Returns Airline Name based on airline code
     * @param unknown_type $airline_code
     */
    private function get_airline_name($airline_code) {
        $airline_name = '';
        $airline_code_list = $this->CI->db_cache_api->get_airline_code_list();
        if (isset($airline_code_list[$airline_code])) {
            $airline_name = $airline_code_list[$airline_code];
        }
        return $airline_name;
    }
    /**
     * Rerurns Passenger Type
     * @param unknown_type $PassengerType
     */
    private function get_passenger_type($PassengerType) {
        $type = '';
        switch ($PassengerType) {
            case 1;
                $type = 'ADT';
                break;
            case 2;
                $type = 'CHD';
                break;
            case 3;
                $type = 'INF';
                break;
        }
        return $type;
    }
}