<?php
require_once BASEPATH . 'libraries/flight/Common_api_flight.php';

class Mystifly extends Common_Api_Flight {

    var $master_search_data;
    var $search_hash;
    protected $token;
    var $api_session_id;

    function __construct() {
        parent::__construct(META_AIRLINE_COURSE, MYSTIFLY_FLIGHT_BOOKING_SOURCE);
        $this->CI = &get_instance();
        $this->CI->load->library('Converter');
        $this->set_api_session_id();
    }

    /**
     * Setting Session ID 
     */
    public function set_api_session_id($authentication_response = '') {

        if (empty($this->api_session_id) == true) {
            if (empty($authentication_response) == false) {
                $authentication_response = Converter::createArray($authentication_response);
                if (empty($authentication_response) == false && @empty($authentication_response ['s:Envelope'] ['s:Body'] ['CreateSessionResponse'] ['CreateSessionResult']['a:SessionId']) == false) {
                    $session_id = $authentication_response['s:Envelope'] ['s:Body'] ['CreateSessionResponse'] ['CreateSessionResult']['a:SessionId'];
                    $this->CI->api_model->update_api_session_id($this->booking_source, $session_id);
                }
            } else {
                $session_expiry_time = 15; //In minutes

                $session_id = $this->CI->api_model->get_api_session_id($this->booking_source, $session_expiry_time);
                // echo $session_id;exit;
                if (empty($session_id) == true) {

                    $authentication_request = $this->get_authentication_request(true);
                    // debug($authentication_request);exit;
                    if ($authentication_request['status'] == SUCCESS_STATUS) {
                        $authentication_request = $authentication_request['data'];
                        $authentication_response = $this->process_request($authentication_request ['request'], $authentication_request ['url'], $authentication_request['soap_action'], $authentication_request ['remarks']);
                        $this->set_api_session_id($authentication_response);
                    }
                }
            }
            if (empty($session_id) == false) {
                $this->api_session_id = $session_id;
            }
        }
    }

    /**
     * 
     * @see Common_Api_Grind::search_data()
     */
    public function search_data($search_id) {
        $response ['status'] = true;
        $response ['data'] = array();
        $CI = & get_instance();
        if (empty($this->master_search_data) == true and valid_array($this->master_search_data) == false) {
            //$clean_search_details = $this->get_test_params('search');
            $clean_search_details = $CI->flight_model->get_safe_search_data($search_id);
            if ($clean_search_details ['status'] == true) {
                $response ['status'] = true;
                $response ['data'] = $clean_search_details ['data'];

                if ($clean_search_details ['data'] ['trip_type'] == 'multicity') {
                    $response ['data'] ['from'] = $clean_search_details ['data'] ['from'];
                    $response ['data'] ['to'] = $clean_search_details ['data'] ['to'];
                    $response ['data'] ['depature'] = $clean_search_details ['data'] ['depature'];
                    $response ['data'] ['return'] = $clean_search_details ['data'] ['depature'];
                } else {
                    $response ['data'] ['from'] = substr(chop(substr($clean_search_details ['data'] ['from'], - 5), ')'), - 3);
                    $response ['data'] ['to'] = substr(chop(substr($clean_search_details ['data'] ['to'], - 5), ')'), - 3);
                    $response ['data'] ['depature'] = date("Y-m-d", strtotime($clean_search_details ['data'] ['depature'])) . 'T00:00:00';
                    $response ['data'] ['return'] = date("Y-m-d", strtotime($clean_search_details ['data'] ['depature'])) . 'T00:00:00';
                }
                switch ($clean_search_details ['data'] ['trip_type']) {

                    case 'oneway' :
                        $response ['data'] ['type'] = 'OneWay';
                        break;

                    case 'return' :
                        $response ['data'] ['type'] = 'return';
                        $response ['data'] ['return'] = date("Y-m-d", strtotime($clean_search_details ['data'] ['return'])) . 'T00:00:00';
                        break;
                    case 'multicity' :
                        $response ['data'] ['type'] = 'OpenJaw';
                        return false; # When you want multicity data from mystifly, Then this line need to remove.
                        break;
                    default :
                        $response ['data'] ['type'] = 'oneway';
                }

                if ($response ['data'] ['is_domestic'] == true and $response ['data'] ['trip_type'] == 'return') {
                    $response ['data'] ['domestic_round_trip'] = true;
                } else {
                    $response ['data'] ['domestic_round_trip'] = false;
                }
                $response ['data'] ['adult'] = $clean_search_details ['data'] ['adult_config'];
                $response ['data'] ['child'] = $clean_search_details ['data'] ['child_config'];
                $response ['data'] ['infant'] = $clean_search_details ['data'] ['infant_config'];
                $response ['data'] ['total_pax'] = $clean_search_details ['data'] ['total_pax'];
                $response ['data'] ['carrier'] = $clean_search_details ['data'] ['carrier'];
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
     * get create session RQ for api
     */
    private function authenticate_request() {
        $request = array();
        $CretateSessionRequest = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:mys="Mystifly.OnePoint" xmlns:mys1="http://schemas.datacontract.org/2004/07/Mystifly.OnePoint" xmlns:arr="http://schemas.microsoft.com/2003/10/Serialization/Arrays" xmlns:mys2="Mystifly.OnePoint.OnePointEntities">
								<soapenv:Header/>
								<soapenv:Body>';
        $CretateSessionRequest .= '<mys:CreateSession>
						<mys:rq>
							<mys1:AccountNumber>' . $this->config['AccountNumber'] . '</mys1:AccountNumber>
							<mys1:Password>' . $this->config['Password'] . '</mys1:Password>
							<mys1:Target>' . $this->config['Target'] . '</mys1:Target>
							<mys1:UserName>' . $this->config['UserName'] . '</mys1:UserName>
						</mys:rq>
					</mys:CreateSession>';
        $CretateSessionRequest .= '</soapenv:Body></soapenv:Envelope>';

        $request ['payload'] = $CretateSessionRequest;
        $request ['url'] = $this->config['EndPointUrl'];
        $request ['soap_action'] = 'Mystifly.OnePoint/OnePoint/CreateSession';
        $request ['status'] = SUCCESS_STATUS;
        return $request;
    }

    /**
     * Formates Search Request
     */
    private function search_request($search_data) {
        
        //Cabin Class: Y-Economy, C-Business, F- First
        $travels = array();
        $locs = array();
        $from = (!valid_array($search_data ['from'])) ? array(
            $search_data ['from']
                ) : $search_data ['from'];
        $to = (!valid_array($search_data ['to'])) ? array(
            $search_data ['to']
                ) : $search_data ['to'];
        $start = (!valid_array($search_data ['depature'])) ? array(
            $search_data ['depature']
                ) : $search_data ['depature'];
        if ($search_data ['trip_type'] == 'oneway') {
            $travels [] = array(
                'from' => $from [0],
                'to' => $to [0],
                'start' => $start [0]
            );
        } elseif ($search_data ['trip_type'] == 'return') {
            $travels [] = array(
                'from' => $from [0],
                'to' => $to [0],
                'start' => $start [0]
            );
            $travels [] = array(
                'from' => $to [0],
                'to' => $from [0],
                'start' => $search_data ['return']
            );
        } else {
            // multicity

            foreach ($from as $tk => $tv) {
                $travels [] = array(
                    'from' => $from [$tk],
                    'to' => $to [$tk],
                    'start' => $start [$tk]
                );
            }
        }
        $search_data['cabin_class'] = $this->get_cabin_class_id($search_data['cabin_class']);

        //FIXME: <mys1:IsRefundable>false</mys1:IsRefundable>



        $request = array();
        $search_request = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:mys="Mystifly.OnePoint" xmlns:mys1="http://schemas.datacontract.org/2004/07/Mystifly.OnePoint" xmlns:arr="http://schemas.microsoft.com/2003/10/Serialization/Arrays" xmlns:mys2="Mystifly.OnePoint.OnePointEntities">
					<soapenv:Header/>
					<soapenv:Body>';
        $search_request .= '<mys:AirLowFareSearch>
									<mys:rq>
									<mys1:IsRefundable>false</mys1:IsRefundable>';

        $search_request .= '<mys1:OriginDestinationInformations>';
        foreach ($travels as $tk => $tv) {
            $search_request .= '<mys1:OriginDestinationInformation>
							                  				<mys1:DepartureDateTime>' . $tv['start'] . '</mys1:DepartureDateTime>
							                  				<mys1:DestinationLocationCode>' . $tv['to'] . '</mys1:DestinationLocationCode>
							                  				<mys1:OriginLocationCode>' . $tv['from'] . '</mys1:OriginLocationCode>
							               					</mys1:OriginDestinationInformation>';
        }
        $search_request .= '</mys1:OriginDestinationInformations>';
        //PAX CONFIG
        $pax_config = ' <mys1:PassengerTypeQuantities>
					               		<mys1:PassengerTypeQuantity>
						               <mys1:Code>ADT</mys1:Code>
						               <mys1:Quantity>' . intval($search_data ['adult']) . '
						               </mys1:Quantity>
						               </mys1:PassengerTypeQuantity>';
        if (intval($search_data ['child']) > 0) {
            $pax_config .= '<mys1:PassengerTypeQuantity> 
					                  						<mys1:Code>CHD</mys1:Code>
					               							<mys1:Quantity>
					               							' . intval($search_data ['child']) . '
					               							</mys1:Quantity>
					               							</mys1:PassengerTypeQuantity>';
        }
        if (intval($search_data ['infant']) > 0) {
            $pax_config .= '<mys1:PassengerTypeQuantity> 
					                  						<mys1:Code>INF</mys1:Code>
					               							<mys1:Quantity>' . intval($search_data ['infant']) . '
					               							</mys1:Quantity>
					               							</mys1:PassengerTypeQuantity>';
        }
        $pax_config .= '</mys1:PassengerTypeQuantities>';
        $search_request .= $pax_config;
        $search_request .= '<mys1:PricingSourceType>All</mys1:PricingSourceType>
											<mys1:RequestOptions>TwoHundred</mys1:RequestOptions>';
        $search_request .= '<mys1:SessionId>' . $this->api_session_id . '</mys1:SessionId>
					    					<mys1:Target>' . $this->config['Target'] . '</mys1:Target>
										<mys1:TravelPreferences>
						               <mys1:AirTripType>' . ucfirst($search_data['type']) . '</mys1:AirTripType>
						               <mys1:CabinPreference>' . $search_data['cabin_class'] . '</mys1:CabinPreference>
						               <mys1:MaxStopsQuantity>All</mys1:MaxStopsQuantity>';


        $search_request .= '<mys1:Preferences>
						                  <mys1:CabinClassPreference>
						                     <mys1:CabinType>' . $search_data['cabin_class'] . '</mys1:CabinType>
						                     <mys1:PreferenceLevel>Restricted</mys1:PreferenceLevel>
						                  </mys1:CabinClassPreference>
						               </mys1:Preferences>';
        //Carrier
        if (empty($search_data['carrier']) == false) {
            $search_request .= '<mys1:VendorPreferenceCodes>
															<mys1:VendorPreferenceCode>' . $search_data['carrier'][0] . '</mys1:VendorPreferenceCode>
														</mys1:VendorPreferenceCodes>';
        }
        $search_request .= '</mys1:TravelPreferences>
										</mys:rq>
									</mys:AirLowFareSearch>';
        $search_request .= '</soapenv:Body></soapenv:Envelope>';

        $request ['payload'] = $search_request;
        $request ['url'] = $this->config['EndPointUrl'];
        $request ['soap_action'] = 'Mystifly.OnePoint/OnePoint/AirLowFareSearch';
        $request ['status'] = SUCCESS_STATUS;
        return $request;
    }

    /**
     * Forms the fare rule request
     * @param unknown_type $request
     */
    private function fare_rule_request($params) {
        $request = array();
        $fare_rule_request = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:mys="Mystifly.OnePoint" xmlns:mys1="http://schemas.datacontract.org/2004/07/Mystifly.OnePoint.AirRules1_1" xmlns:arr="http://schemas.microsoft.com/2003/10/Serialization/Arrays" xmlns:mys2="Mystifly.OnePoint.OnePointEntities">
								<soapenv:Header/>
								<soapenv:Body>';
        $fare_rule_request .= '<mys:FareRules1_1>
									<mys:rq>
									<mys1:FareSourceCode>' . $params['FareSourceCode'] . '</mys1:FareSourceCode>
										<mys1:SessionId>' . $this->api_session_id . '</mys1:SessionId>
										<mys1:Target>' . $this->config['Target'] . '</mys1:Target>
							 		</mys:rq>
								</mys:FareRules1_1>';
        $fare_rule_request .= '</soapenv:Body></soapenv:Envelope>';

        $request ['payload'] = $fare_rule_request;
        $request ['url'] = $this->config['EndPointUrl'];
        $request ['soap_action'] = 'Mystifly.OnePoint/OnePoint/FareRules1_1';
        $request ['remarks'] = 'FareRules1_1(Mystifly)';
        $request ['status'] = SUCCESS_STATUS;
        return $request;
    }

    /**
     * Forms the fare quote request
     * @param unknown_type $request
     */
    private function update_fare_quote_request($params) {
        $request = array();
        $update_fare_quote_request = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:mys="Mystifly.OnePoint" xmlns:mys1="http://schemas.datacontract.org/2004/07/Mystifly.OnePoint" xmlns:arr="http://schemas.microsoft.com/2003/10/Serialization/Arrays" xmlns:mys2="Mystifly.OnePoint.OnePointEntities">
								<soapenv:Header/>
								<soapenv:Body>';
        $update_fare_quote_request .= '<mys:AirRevalidate>
									<mys:rq>
									<mys1:FareSourceCode>' . $params['FareSourceCode'] . '</mys1:FareSourceCode>
										<mys1:SessionId>' . $this->api_session_id . '</mys1:SessionId>
										<mys1:Target>' . $this->config['Target'] . '</mys1:Target>
							 		</mys:rq>
								</mys:AirRevalidate>';
        $update_fare_quote_request .= '</soapenv:Body></soapenv:Envelope>';

        $request ['payload'] = $update_fare_quote_request;
        $request ['url'] = $this->config['EndPointUrl'];
        $request ['soap_action'] = 'Mystifly.OnePoint/OnePoint/AirRevalidate';
        $request ['remarks'] = 'AirRevalidate(Mystifly)';
        $request ['status'] = SUCCESS_STATUS;
        return $request;
    }

    /**
     * Forms Book Flight Request
     * @param unknown_type $request
     */
    private function book_flight_service_request($params) {
        $CountryPhoneCode = $this->CI->custom_db->single_table_records('api_country_list', 'country_code', array('iso_country_code' => trim($params['Passengers'][0]['CountryCode'])));
        if ($CountryPhoneCode['status'] == SUCCESS_STATUS) {
            $CountryPhoneCode = $CountryPhoneCode['data'][0]['country_code'];
            $CountryPhoneCode = str_replace('+', '', $CountryPhoneCode);
        } else {
            $CountryPhoneCode = '91';
        }
        $request = array();
        $book_flight_service_request = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:mys="Mystifly.OnePoint" xmlns:mys1="http://schemas.datacontract.org/2004/07/Mystifly.OnePoint" xmlns:arr="http://schemas.microsoft.com/2003/10/Serialization/Arrays" xmlns:mys2="Mystifly.OnePoint.OnePointEntities">
								<soapenv:Header/>
								<soapenv:Body>';
        $book_flight_service_request .= '<mys:BookFlight>
									<mys:rq>
									<mys1:FareSourceCode>' . $params['ResultToken']['FareSourceCode'] . '</mys1:FareSourceCode>
                                                                        <mys1:LccHoldBooking>true</mys1:LccHoldBooking>
									<mys1:SessionId>' . $this->api_session_id . '</mys1:SessionId>
									<mys1:Target>' . $this->config['Target'] . '</mys1:Target>';
        $passenger_request = '';
        $passenger_request .= '<mys1:TravelerInfo>
							<mys1:AirTravelers>';
        foreach ($params['Passengers'] as $pax_k => $pax_v) {
            $extra_services = '';
            if (isset($pax_v['BaggageId']) == true && valid_array($pax_v['BaggageId']) == true) {
                foreach ($pax_v['BaggageId'] as $bg_k => $bg_v) {
                    if (empty($bg_v) == false) {
                        $extra_services .= '<mys2:Services>
												<mys2:ExtraServiceId>' . trim($bg_v) . '</mys2:ExtraServiceId>
											  </mys2:Services>';
                    }
                }
            }
            $passenger_request .= '<mys1:AirTraveler>
										<mys1:DateOfBirth>' . $pax_v['DateOfBirth'] . 'T00:00:00</mys1:DateOfBirth>
										<mys1:ExtraServices1_1>' . $extra_services . '</mys1:ExtraServices1_1>
										<mys1:FrequentFlyerNumber></mys1:FrequentFlyerNumber>
										<mys1:Gender>' . $this->get_gender_type($pax_v['Gender']) . '</mys1:Gender>
										<mys1:PassengerName>
											<mys1:PassengerFirstName>' . $pax_v['FirstName'] . '</mys1:PassengerFirstName>
											<mys1:PassengerLastName>' . $pax_v['LastName'] . '</mys1:PassengerLastName>
											<mys1:PassengerTitle>' . strtoupper($pax_v['Title']) . '</mys1:PassengerTitle>
										</mys1:PassengerName>
										<mys1:PassengerNationality>' . $pax_v['CountryCode'] . '</mys1:PassengerNationality>
										<mys1:PassengerType>' . $this->get_passenger_type($pax_v['PaxType']) . '</mys1:PassengerType>
										<mys1:Passport>
											<mys1:Country>' . $pax_v['CountryCode'] . '</mys1:Country>
											<mys1:ExpiryDate>' . $pax_v['PassportExpiry'] . 'T00:00:00</mys1:ExpiryDate>
											<mys1:PassportNumber>' . $pax_v['PassportNumber'] . '</mys1:PassportNumber>
										</mys1:Passport>
								</mys1:AirTraveler>';
        }
        $passenger_request .= '</mys1:AirTravelers>
				<mys1:CountryCode>' . $CountryPhoneCode . '</mys1:CountryCode>
				<mys1:Email>' . $params['Passengers'][0]['Email'] . '</mys1:Email>
				<mys1:PhoneNumber>' . $params['Passengers'][0]['ContactNo'] . '</mys1:PhoneNumber>
		</mys1:TravelerInfo>';
        //Assigning passenger Details
        $book_flight_service_request .= $passenger_request;
        $book_flight_service_request .= '</mys:rq>
								</mys:BookFlight>';
        $book_flight_service_request .= '</soapenv:Body></soapenv:Envelope>';
        $request ['payload'] = $book_flight_service_request;
        $request ['url'] = $this->config['EndPointUrl'];
        $request ['soap_action'] = 'Mystifly.OnePoint/OnePoint/BookFlight';
        $request ['remarks'] = 'BookFlight(Mystifly)';
        $request ['status'] = SUCCESS_STATUS;
        // debug($request);exit;
        return $request;
    }

    /**
     * Ticket Order Request
     * @param unknown_type $params
     */
    private function order_ticket_service_request($params) {
        $request = array();
        $order_ticket_service_request = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:mys="Mystifly.OnePoint" xmlns:mys1="http://schemas.datacontract.org/2004/07/Mystifly.OnePoint" xmlns:arr="http://schemas.microsoft.com/2003/10/Serialization/Arrays" xmlns:mys2="Mystifly.OnePoint.OnePointEntities">
								<soapenv:Header/>
								<soapenv:Body>';
        $order_ticket_service_request .= '<mys:TicketOrder>
									<mys:rq>
										<mys1:SessionId>' . $this->api_session_id . '</mys1:SessionId>
										<mys1:Target>' . $this->config['Target'] . '</mys1:Target>
										<mys1:UniqueID>' . $params['a:UniqueID'] . '</mys1:UniqueID>
							 		</mys:rq>
								</mys:TicketOrder>';
        $order_ticket_service_request .= '</soapenv:Body></soapenv:Envelope>';
        $request ['payload'] = $order_ticket_service_request;
        $request ['url'] = $this->config['EndPointUrl'];
        $request ['soap_action'] = 'Mystifly.OnePoint/OnePoint/TicketOrder';
        $request ['remarks'] = 'TicketOrder(Mystifly)';
        $request ['status'] = SUCCESS_STATUS;
        return $request;
    }

    /**
     * Trip Details Request
     * @param unknown_type $params
     */
    private function trip_details_service_request($params) {
        if (isset($params['a:UniqueID']) == true) {
            $UniqueID = $params['a:UniqueID'];
        } else {
            $UniqueID = $params['UniqueID'];
        }

        $request = array();
        $trip_details_service_request = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:mys="Mystifly.OnePoint" xmlns:mys1="http://schemas.datacontract.org/2004/07/Mystifly.OnePoint" xmlns:arr="http://schemas.microsoft.com/2003/10/Serialization/Arrays" xmlns:mys2="Mystifly.OnePoint.OnePointEntities">
								<soapenv:Header/>
								<soapenv:Body>';
        $trip_details_service_request .= '<mys:TripDetails>
									<mys:rq>
										<mys1:SessionId>' . $this->api_session_id . '</mys1:SessionId>
										<mys1:UniqueID>' . $UniqueID . '</mys1:UniqueID>
										<mys1:Target>' . $this->config['Target'] . '</mys1:Target>
							 		</mys:rq>
								</mys:TripDetails>';
        $trip_details_service_request .= '</soapenv:Body></soapenv:Envelope>';

        $request ['payload'] = $trip_details_service_request;
        $request ['url'] = $this->config['EndPointUrl'];
        $request ['soap_action'] = 'Mystifly.OnePoint/OnePoint/TripDetails';
        $request ['remarks'] = 'TripDetails(Mystifly)';
        $request ['status'] = SUCCESS_STATUS;
        return $request;
    }

    /**
     * Message Queue Request
     * @param unknown_type $params
     */
    private function message_queues_service_request($params) {
        $request = array();
        $message_queues_service_request = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:mys="Mystifly.OnePoint" xmlns:mys1="http://schemas.datacontract.org/2004/07/Mystifly.OnePoint" xmlns:arr="http://schemas.microsoft.com/2003/10/Serialization/Arrays" xmlns:mys2="Mystifly.OnePoint.OnePointEntities">
								<soapenv:Header/>
								<soapenv:Body>';
        $message_queues_service_request .= '<mys:MessageQueues>
									<mys:rq>
										<mys1:CategoryId>' . $params['CategoryId'] . '</mys1:CategoryId>
										<mys1:SessionId>' . $this->api_session_id . '</mys1:SessionId>
										<mys1:Target>' . $this->config['Target'] . '</mys1:Target>
							 		</mys:rq>
								</mys:MessageQueues>';
        $message_queues_service_request .= '</soapenv:Body></soapenv:Envelope>';
        $request ['payload'] = $message_queues_service_request;
        $request ['url'] = $this->config['EndPointUrl'];
        $request ['soap_action'] = 'Mystifly.OnePoint/OnePoint/MessageQueues';
        $request ['remarks'] = 'MessageQueues(Mystifly)';
        $request ['status'] = SUCCESS_STATUS;
        return $request;
    }

    /**
     * Remove Message Queue Request
     * @param unknown_type $params
     */
    private function remove_message_queues_service_request($params) {
        $request = array();
        $remove_message_queues_service_request = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:mys="Mystifly.OnePoint" xmlns:mys1="http://schemas.datacontract.org/2004/07/Mystifly.OnePoint" xmlns:arr="http://schemas.microsoft.com/2003/10/Serialization/Arrays" xmlns:mys2="Mystifly.OnePoint.OnePointEntities">
								<soapenv:Header/>
								<soapenv:Body>';
        $remove_message_queues_service_request .= '<mys:RemoveMessageQueues>
									<mys:rq>
										<mys:Items>';
        foreach ($params as $pk => $pv) {
            $remove_message_queues_service_request .= '<mys:Item>
														<mys:CategoryId>' . $this->message_queue_category($pv['CategoryId']) . '</mys:CategoryId>
														<mys:UniqueId>' . trim($pv['UniqueId']) . '</mys:UniqueId>
													</mys:Item>';
        }
        $remove_message_queues_service_request .= '</mys:Items>
										<mys1:SessionId>' . $this->api_session_id . '</mys1:SessionId>
										<mys1:Target>' . $this->config['Target'] . '</mys1:Target>
							 		</mys:rq>
								</mys:RemoveMessageQueues>';
        $remove_message_queues_service_request .= '</soapenv:Body></soapenv:Envelope>';
        $request ['payload'] = $remove_message_queues_service_request;
        $request ['url'] = $this->config['EndPointUrl'];
        $request ['soap_action'] = 'Mystifly.OnePoint/OnePoint/RemoveMessageQueues';
        $request ['remarks'] = 'RemoveMessageQueues(Mystifly)';
        $request ['status'] = SUCCESS_STATUS;
        return $request;
    }

    /**
     * Cancel Booking Request
     * @param unknown_type $uniqueId
     */
    public function cancel_booking_service_request($uniqueId) {
        //$uniqueId = 'MF00758517';
        $request = array();
        $cancel_booking_service_request = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:mys="Mystifly.OnePoint" xmlns:mys1="http://schemas.datacontract.org/2004/07/Mystifly.OnePoint" xmlns:arr="http://schemas.microsoft.com/2003/10/Serialization/Arrays" xmlns:mys2="Mystifly.OnePoint.OnePointEntities">
								<soapenv:Header/>
								<soapenv:Body>';
        $cancel_booking_service_request .= '<mys:CancelBooking>
									<mys:rq>
										<mys1:SessionId>' . $this->api_session_id . '</mys1:SessionId>
										<mys1:Target>' . $this->config['Target'] . '</mys1:Target>
										<mys1:UniqueID>' . $uniqueId . '</mys1:UniqueID>
							 		</mys:rq>
								</mys:CancelBooking>';
        $cancel_booking_service_request .= '</soapenv:Body></soapenv:Envelope>';
        $request ['payload'] = $cancel_booking_service_request;
        $request ['url'] = $this->config['EndPointUrl'];
        $request ['soap_action'] = 'Mystifly.OnePoint/OnePoint/CancelBooking';
        $request ['remarks'] = 'CancelBooking(Mystifly)';
        $request ['status'] = SUCCESS_STATUS;
        return $request;
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
            // debug($authenticate_request);exit;
            if ($authenticate_request['status'] = SUCCESS_STATUS) {
                $response ['status'] = SUCCESS_STATUS;
                $curl_request = $this->form_curl_params($authenticate_request ['payload'], $authenticate_request ['url'], $authenticate_request ['soap_action']);
                $response ['data'] = $curl_request['data'];

                if ($internal_request == true) {
                    $response ['data']['soap_action'] = $authenticate_request ['soap_action'];
                    $response ['data']['remarks'] = 'Authentication(Mystifly)';
                }
            }
        }
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


        if (empty($this->api_session_id) == true || $this->search_allowed($search_data) === false) {
            return $response;
        }

        if ($search_data ['status'] == SUCCESS_STATUS) {
            // Flight search RQ
            $search_request = $this->search_request($search_data ['data']);
            if ($search_request ['status'] = SUCCESS_STATUS) {
                $response ['status'] = SUCCESS_STATUS;
                $curl_request = $this->form_curl_params($search_request ['payload'], $search_request ['url'], $search_request ['soap_action']);
                $response ['data'] = $curl_request['data'];
            }
        }
        return $response;
    }

    /**
     * Flight Search allowed or not
     * @param unknown_type $search_data
     */
    private function search_allowed($search_data) {
        $search_data = $search_data['data'];

        if ($search_data['is_domestic'] === true) {
            return false;
        } else {
            return true;
        }
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
            if (strpos($flight_raw_data, '<HTML>') !== false) {
                $api_response_array = array();
            } else {
                $api_response_array = Converter::createArray($flight_raw_data);
            }
            //debug($api_response_array);exit;
            if ($this->valid_search_result($api_response_array) == TRUE) {
                $clean_format_data = $this->format_search_data_response($api_response_array, $search_data ['data']);
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
        return $response;
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
            $fare_rule_response = $this->process_request($fare_rule_request ['payload'], $fare_rule_request ['url'], $fare_rule_request['soap_action'], $fare_rule_request ['remarks']);
            $fare_rule_response = Converter::createArray($fare_rule_response);
            if (valid_array($fare_rule_response) == true && isset($fare_rule_response['s:Envelope']['s:Body']['FareRules1_1Response']['FareRules1_1Result']) == true && $fare_rule_response['s:Envelope']['s:Body']['FareRules1_1Response']['FareRules1_1Result']['a:Success'] === 'true') {
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
     * Update Fare Quote
     * @param unknown_type $request
     */
    public function get_update_fare_quote($request, $search_id) {
        // echo "dsfdsfgd";exit;
        $search_data = $this->search_data($search_id);
        $response ['status'] = FAILURE_STATUS; // Status Of Operation
        $response ['message'] = ''; // Message to be returned
        $response ['data'] = array(); // Data to be returned
        $update_fare_quote_request = $this->update_fare_quote_request($request);
        // debug($update_fare_quote_request);exit;
        if ($update_fare_quote_request['status'] == SUCCESS_STATUS) {

            $update_fare_quote_response = $this->process_request($update_fare_quote_request ['payload'], $update_fare_quote_request ['url'], $update_fare_quote_request['soap_action'], $update_fare_quote_request ['remarks']);
            // $update_fare_quote_response =  file_get_contents(FCPATH."travelport_xmls/mystyfly_revalidate.xml");
            //$update_fare_quote_response = $this->CI->custom_db->get_static_response(56);
            // debug($update_fare_quote_response);exit;
            $update_fare_quote_response = Converter::createArray($update_fare_quote_response);

            if ($this->valid_air_revalidate_response($update_fare_quote_response)) {
                $response ['status'] = SUCCESS_STATUS;
                $response ['data']['FareQuoteDetails'] = $this->format_update_fare_quote_response($update_fare_quote_response['s:Envelope'] ['s:Body'] ['AirRevalidateResponse']['AirRevalidateResult'], $search_data);
            } else {
                $response ['message'] = 'Not Available';
            }
        } else {
            $response ['status'] = FAILURE_STATUS;
        }
        return $response;
    }

    /**
     * Extra Services
     * @param unknown_type $request
     */
    public function get_extra_services($request, $search_id, $result_token) {
        $response ['status'] = FAILURE_STATUS;
        $response ['message'] = 'Not Available'; // Message to be returned
        $response ['data'] = array(); // Data to be returned
        $result_token = $this->CI->custom_db->single_table_records('MY_result_token', 'fare_quote_token', array('search_token' => $result_token));
        if ($result_token['status'] == SUCCESS_STATUS) {
            $flight_search_data = Common_Flight::read_record($result_token['data'][0]['fare_quote_token']);
            $flight_search_data = json_decode($flight_search_data[0], true);
            if (isset($flight_search_data['ExtraServices']) && valid_array($flight_search_data['ExtraServices'])) {
                $response ['status'] = SUCCESS_STATUS;
                $response ['message'] = ''; // Message to be returned
                $response ['data']['ExtraServiceDetails'] = $flight_search_data['ExtraServices'];
            }
        }
        // debug($response);exit;
        // Status Of Operation


        return $response;
    }

    /**
     * Process booking
     * 
     * @param string $book_id        	
     * @param array $booking_params        	
     */
    function process_booking($booking_params, $app_reference, $sequence_number, $search_id) {
        $response ['status'] = FAILURE_STATUS; // Status Of Operation
        $response ['message'] = ''; // Message to be returned
        $response ['data'] = array(); // Data to be returned
        //Format Booking Passenger Data
        $booking_params = $this->format_booking_passenger_object($booking_params);

        // $baggage_data = Common_Flight::read_record($booking_params['Passengers'][0]['BaggageId'][0]);
        // debug($baggage_data);exit;
        $ResultToken = $booking_params['ResultToken'];
        $this->CI->load->library('flight/common_flight');

        //Again Run AirRevalidate method
        $air_revalidate_response = $this->get_update_fare_quote($ResultToken, $search_id);

        $AirlineCode = $booking_params['flight_data']['FlightDetails']['Details'][0][0]['OperatorCode'];
        if ($air_revalidate_response['status'] == SUCCESS_STATUS) {
            $book_flight_service_response = $this->run_book_flight_service($booking_params, $app_reference, $sequence_number);
            if ($book_flight_service_response['status'] == SUCCESS_STATUS) {
                $trip_details_request_params = array();
                //BookFlight is Success
                $response ['status'] = SUCCESS_STATUS;

                $book_flight_response = $book_flight_service_response['data']['book_flight_response'];
                //Save BookFlight Details
                $this->save_book_flight_response_details($book_flight_response, $app_reference, $sequence_number);


                //Run Order Ticket Method
                if (strtolower($ResultToken['FareType']) == 'public' || strtolower($ResultToken['FareType']) == 'private') {

                    $all_hold_airline_list_1 = $this->CI->custom_db->single_table_records('hold_airline_list', '*', array('status' => 1, 'domain_origin' => get_domain_auth_id(), 'code' => $AirlineCode));
                    $ticketing_status = '';
                    if ($all_hold_airline_list_1['status'] == true) {
                        $ticketing_status = "HOLD";
                    }
                    if ($ticketing_status == "HOLD") {
                        $order_ticket_service_response['status'] = 'BOOKING_HOLD';
                    } else {
                        $order_ticket_service_response = $this->run_order_ticket_service($book_flight_response, $app_reference, $sequence_number);
                    }

                    // if(get_domain_auth_id()==176) { # Direct Booking allowed for Meelotrip 
                    // $order_ticket_service_response = $this->run_order_ticket_service($book_flight_response, $app_reference, $sequence_number);
                    // } else  {
                    // $order_ticket_service_response['status'] = 'BOOKING_HOLD';
                    // }
                    //  $order_ticket_service_response = $this->run_order_ticket_service($book_flight_response, $app_reference, $sequence_number);
                    // $order_ticket_service_response['status'] = 'BOOKING_HOLD';
                    if ($order_ticket_service_response['status'] == SUCCESS_STATUS) {
                        //TicketOrder is Success

                        $order_ticket_response = $order_ticket_service_response['data']['order_ticket_response'];
                        $trip_details_request_params = $order_ticket_response;
                    } else if ($order_ticket_service_response['status'] == 'BOOKING_HOLD') {
                        //Ticketing not confimed But booking Done
                        $order_ticket_response = $order_ticket_service_response['data']['order_ticket_response'];
                        $trip_details_request_params = $order_ticket_response;
                    } else {
                        //TicketOrder is Failed
                        $response ['status'] = FAILURE_STATUS;

                        $flight_booking_status = 'BOOKING_FAILED';
                        $this->CI->common_flight->update_flight_booking_status($flight_booking_status, $app_reference, $sequence_number, $this->booking_source);
                        $response['message'] = $order_ticket_service_response['message'];
                    }
                } else {//WebFare
                    $trip_details_request_params = $book_flight_response;
                }

                //Run Trip Details Request
                if (valid_array($trip_details_request_params) == true) {
                    $trip_details_service_response = $this->run_trip_details_service($trip_details_request_params, $app_reference);

                    if ($trip_details_service_response['status'] == SUCCESS_STATUS) {
                        //Save TripDetails Response
                        $trip_details_response = $trip_details_service_response['data']['trip_details_response'];
                        $this->save_trip_details_response($trip_details_response, $app_reference, $sequence_number, $search_id);
                    } else {
                        $response['message'] = $trip_details_service_response['message'];
                    }
                }
            } else {
                //BookFlight is Failed
                $flight_booking_status = 'BOOKING_FAILED';
                $this->CI->common_flight->update_flight_booking_status($flight_booking_status, $app_reference, $sequence_number, $this->booking_source);
                $response['message'] = $book_flight_service_response['message'];
            }
        } {//AirRevalidate Loop Ends Here
            $response['message'] = $air_revalidate_response['message'];
        }

        return $response;
    }

    /**
     * Formats Passenger Details
     * Assign the FareBreakdown for Each Passenger
     */
    private function format_booking_passenger_object($request_params) {
        $passengers = $request_params['Passengers'];
        foreach ($passengers as $k => $v) {
            // debug($v);exit;
            //Baggage Details
            if (isset($v['BaggageId']) == true && valid_array($v['BaggageId']) == true) {
                $baggage = array();
                if (valid_array($v['BaggageId']) == true) {
                    foreach ($v['BaggageId'] as $bag_k => $bag_v) {
                        if (empty($bag_v) == false) {
                            $baggage_data = Common_Flight::read_record($bag_v);

                            if (valid_array($baggage_data) == true) {
                                $baggage_data = json_decode($baggage_data[0], true);
                                $baggage[$bag_k] = $baggage_data['Code'];
                            }
                        }
                    }
                }
            }
            $passengers[$k]['BaggageId'] = $baggage;
        }
        $request_params['Passengers'] = $passengers;
        return $request_params;
        // debug($request_params);exit;
    }

    /**
     * Process Cancel Booking
     * Offline Cancellation
     */
    public function cancel_booking($request) {
        $response ['status'] = FAILURE_STATUS; // Status Of Operation
        $response ['message'] = ''; // Message to be returned
        $response ['data'] = array(); // Data to be returned

        $app_reference = $request['AppReference'];
        $sequence_number = $request['SequenceNumber'];
        $IsFullBookingCancel = $request['IsFullBookingCancel'];
        $ticket_ids = $request['TicketId'];

        $elgible_for_ticket_cancellation = $this->CI->common_flight->elgible_for_ticket_cancellation($app_reference, $sequence_number, $ticket_ids, $IsFullBookingCancel, $this->booking_source);
        if ($elgible_for_ticket_cancellation['status'] == SUCCESS_STATUS) {
            $response ['status'] = SUCCESS_STATUS;
        } else {
            $response ['message'] = $elgible_for_ticket_cancellation['message'];
        }
        /*
          API - Full Booking Cancellation
          $booking_transaction_details = $booking_details['data']['booking_transaction_details'][0];
          $UniqueId = $booking_transaction_details['book_id'];
          $cancel_booking_response = $this->run_cancel_booking_service($UniqueId);

          if($cancel_booking_response['status'] == SUCCESS_STATUS){
          $flight_booking_status = 'BOOKING_CANCELLED';
          $this->CI->common_flight->update_flight_booking_status($flight_booking_status, $app_reference, $sequence_number, $this->booking_source);
          $response ['status'] = SUCCESS_STATUS;
          $response ['message'] = 'Cancellation Request is processing';

          } else {
          $response ['message'] = $cancel_booking_response['message'];
          }
         */
        return $response;
    }

    /**
     * BookFlight
     * @param unknown_type $request
     */
    public function run_book_flight_service($booking_params, $app_reference, $sequence_number) {
        $response ['status'] = FAILURE_STATUS; // Status Of Operation
        $response ['message'] = ''; // Message to be returned
        $response ['data'] = array(); // Data to be returned
        $book_flight_service_request = $this->book_flight_service_request($booking_params);
        if ($book_flight_service_request['status'] == SUCCESS_STATUS) {

            $book_flight_service_response_xml = $this->process_request($book_flight_service_request ['payload'], $book_flight_service_request ['url'], $book_flight_service_request['soap_action'], $book_flight_service_request ['remarks']);

            //$book_flight_service_response_xml = $this->CI->custom_db->get_static_response (69);//Static bOOk Data//355-->success;347--->failure//357--->Pending

            $book_flight_service_response = Converter::createArray($book_flight_service_response_xml);

            if ($this->valid_book_flight_response($book_flight_service_response)) {
                $response ['status'] = SUCCESS_STATUS;
                $response ['data']['book_flight_response'] = $book_flight_service_response['s:Envelope'] ['s:Body'] ['BookFlightResponse']['BookFlightResult'];
            } else {
                $error_message = '';
                if (isset($book_flight_service_response['s:Envelope'] ['s:Body'] ['BookFlightResponse']['BookFlightResult']['a:Errors']['a:Error']['a:Message'])) {
                    $error_message = $book_flight_service_response['s:Envelope'] ['s:Body'] ['BookFlightResponse']['BookFlightResult']['a:Errors']['a:Error']['a:Message'];
                }
                if (empty($error_message) == true) {
                    $error_message = 'Booking Failed';
                }
                $response ['message'] = $error_message;

                //Log Exception
                $exception_log_message = '';
                $this->CI->exception_logger->log_exception($app_reference, $this->booking_source_name . '- (<strong>BookFlight</strong>)', $exception_log_message, $book_flight_service_response_xml);
            }
        } else {
            $response ['status'] = FAILURE_STATUS;
        }
        return $response;
    }

    /**
     * Ticket Order
     * @param unknown_type $request
     */
    public function run_order_ticket_service($book_flight_response, $app_reference, $sequence_number) {
        $response ['status'] = FAILURE_STATUS; // Status Of Operation
        $response ['message'] = ''; // Message to be returned
        $response ['data'] = array(); // Data to be returned
        $order_ticket_service_request = $this->order_ticket_service_request($book_flight_response);
        if ($order_ticket_service_request['status'] == SUCCESS_STATUS) {

            $order_ticket_service_response_xml = $this->process_request($order_ticket_service_request ['payload'], $order_ticket_service_request ['url'], $order_ticket_service_request['soap_action'], $order_ticket_service_request ['remarks']);

            $order_ticket_service_response = Converter::createArray($order_ticket_service_response_xml);


            //$response ['status'] = 'BOOKING_HOLD';
            //$response ['data']['order_ticket_response']='';
            if ($this->valid_order_ticket_response($order_ticket_service_response)) {
                $response ['status'] = SUCCESS_STATUS;
                $response ['data']['order_ticket_response'] = $order_ticket_service_response['s:Envelope'] ['s:Body'] ['TicketOrderResponse']['TicketOrderResult'];
            } else {

                $response ['status'] = 'BOOKING_HOLD';
                $response ['data']['order_ticket_response'] = $order_ticket_service_response['s:Envelope'] ['s:Body'] ['TicketOrderResponse']['TicketOrderResult'];
            }
        } else {
            $response ['status'] = FAILURE_STATUS;
        }
        return $response;
    }

    /**
     * Trip Details
     * @param unknown_type $request
     */
    public function run_trip_details_service($request_params, $app_reference = '') {
        $response ['status'] = FAILURE_STATUS; // Status Of Operation
        $response ['message'] = ''; // Message to be returned
        $response ['data'] = array(); // Data to be returned
        $trip_details_service_request = $this->trip_details_service_request($request_params);
        if ($trip_details_service_request['status'] == SUCCESS_STATUS) {

            $trip_details_service_response = $this->process_request($trip_details_service_request ['payload'], $trip_details_service_request ['url'], $trip_details_service_request['soap_action'], $trip_details_service_request ['remarks']);

            //$trip_details_service_response = $this->CI->custom_db->get_static_response(356);

            $trip_details_service_response = Converter::createArray($trip_details_service_response);

            if ($this->valid_trip_details_response($trip_details_service_response)) {
                $response ['status'] = SUCCESS_STATUS;
                $response ['data']['trip_details_response'] = $trip_details_service_response['s:Envelope'] ['s:Body'] ['TripDetailsResponse']['TripDetailsResult'];
            } else {
                $error_message = '';
                if (isset($trip_details_service_response['s:Envelope'] ['s:Body'] ['TripDetailsResponse']['TripDetailsResult']['a:Errors']['a:Error']['a:Message'])) {
                    $error_message = $trip_details_service_response['s:Envelope'] ['s:Body'] ['TripDetailsResponse']['TripDetailsResult']['a:Errors']['a:Error']['a:Message'];
                }
                if (empty($error_message) == true) {
                    $error_message = 'Invalid Trip Details';
                }
                $response ['message'] = $error_message;
            }
        } else {
            $response ['status'] = FAILURE_STATUS;
        }

        return $response;
    }

    /**
     * MessageQueues
     * @param unknown_type $request
     */
    public function run_message_queues_service($request_params) {
        $response ['status'] = FAILURE_STATUS; // Status Of Operation
        $response ['message'] = ''; // Message to be returned
        $response ['data'] = array(); // Data to be returned
        if (isset($request_params['CategoryId']) == true && intval($request_params['CategoryId']) > 0) {
            $CategoryId = intval($request_params['CategoryId']);
            $CategoryId = $this->message_queue_category($CategoryId);

            $request_params = array();
            $request_params['CategoryId'] = trim($CategoryId);
            $message_queues_service_request = $this->message_queues_service_request($request_params);

            if ($message_queues_service_request['status'] == SUCCESS_STATUS) {

                $message_queues_service_response = $this->process_request($message_queues_service_request ['payload'], $message_queues_service_request ['url'], $message_queues_service_request['soap_action'], $message_queues_service_request ['remarks']);

                //$trip_details_service_response = $this->CI->custom_db->get_static_response(356);

                $message_queues_service_response = Converter::createArray($message_queues_service_response);

                if (valid_array($message_queues_service_response) == true && isset($message_queues_service_response['s:Envelope'] ['s:Body']['MessageQueuesResponse']['MessageQueuesResult']) == true && $message_queues_service_response['s:Envelope'] ['s:Body']['MessageQueuesResponse']['MessageQueuesResult']['a:Success'] == 'true') {
                    $response ['status'] = SUCCESS_STATUS;
                    $response ['data']['message_queue_response'] = force_multple_data_format($message_queues_service_response['s:Envelope'] ['s:Body']['MessageQueuesResponse']['MessageQueuesResult']['a:MessageItems']['a:MessageItem']);
                } else {
                    $error_message = '';
                    if (isset($message_queues_service_response['s:Envelope'] ['s:Body']['MessageQueuesResponse']['MessageQueuesResult']['a:Errors']['a:Error']['a:Message'])) {
                        $error_message = $message_queues_service_response['s:Envelope'] ['s:Body']['MessageQueuesResponse']['MessageQueuesResult']['a:Errors']['a:Error']['a:Message'];
                    }
                    if (empty($error_message) == true) {
                        $error_message = 'No Data Found !!';
                    }
                    $response ['message'] = $error_message;
                }
            }
        } else {
            $response ['message'] = 'Invalid CategoryId';
        }
        return $response;
    }

    /**
     * RemoveMessageQueues
     * @param unknown_type $request
     */
    public function run_remove_message_queues_service($request_params) {
        $response ['status'] = FAILURE_STATUS; // Status Of Operation
        $response ['message'] = ''; // Message to be returned
        $response ['data'] = array(); // Data to be returned
        if (valid_array($request_params) == true) {
            $remove_message_queues_service_request = $this->remove_message_queues_service_request($request_params);
            if ($remove_message_queues_service_request['status'] == SUCCESS_STATUS) {

                $remove_message_queues_service_response = $this->process_request($remove_message_queues_service_request ['payload'], $remove_message_queues_service_request ['url'], $remove_message_queues_service_request['soap_action'], $remove_message_queues_service_request ['remarks']);

                //$remove_message_queues_service_response = $this->CI->custom_db->get_static_response(356);

                $remove_message_queues_service_response = Converter::createArray($remove_message_queues_service_response);
                debug($remove_message_queues_service_response);
                exit;
                if (valid_array($remove_message_queues_service_response) == true && isset($remove_message_queues_service_response['s:Envelope'] ['s:Body']['MessageQueuesResponse']['MessageQueuesResult']) == true && $remove_message_queues_service_response['s:Envelope'] ['s:Body']['MessageQueuesResponse']['MessageQueuesResult']['a:Success'] == 'true') {
                    $response ['status'] = SUCCESS_STATUS;
                    $response ['data']['remove_message_queue_response'] = force_multple_data_format($remove_message_queues_service_response['s:Envelope'] ['s:Body']['MessageQueuesResponse']['MessageQueuesResult']['a:MessageItems']['a:MessageItem']);
                } else {
                    $error_message = '';
                    if (isset($remove_message_queues_service_response['s:Envelope'] ['s:Body']['MessageQueuesResponse']['MessageQueuesResult']['a:Errors']['a:Error']['a:Message'])) {
                        $error_message = $remove_message_queues_service_response['s:Envelope'] ['s:Body']['MessageQueuesResponse']['MessageQueuesResult']['a:Errors']['a:Error']['a:Message'];
                    }
                    if (empty($error_message) == true) {
                        $error_message = 'Failed!!';
                    }
                    $response ['message'] = $error_message;
                }
            }
        } else {
            $response ['message'] = 'Invalid RemoveMessageQueues Request';
        }
        debug($response);
        exit;
        return $response;
    }

    /**
     * Cancel Booking
     */
    public function run_cancel_booking_service($UniqueId) {
        $response ['status'] = FAILURE_STATUS; // Status Of Operation
        $response ['message'] = ''; // Message to be returned
        $response ['data'] = array(); // Data to be returned

        $cancel_booking_service_request = $this->cancel_booking_service_request($UniqueId);
        if ($cancel_booking_service_request['status'] == SUCCESS_STATUS) {

            $cancel_booking_service_response = $this->process_request($cancel_booking_service_request ['payload'], $cancel_booking_service_request ['url'], $cancel_booking_service_request['soap_action'], $cancel_booking_service_request ['remarks']);

            //$cancel_booking_service_response = $this->CI->custom_db->get_static_response(390);//390=> Success;391 => Failed

            $cancel_booking_service_response = Converter::createArray($cancel_booking_service_response);
            if ($this->valid_cancel_booking_response($cancel_booking_service_response)) {
                $response ['status'] = SUCCESS_STATUS;
                $response ['data']['cancel_booking_response'] = $cancel_booking_service_response['s:Envelope'] ['s:Body'] ['CancelBookingResponse']['CancelBookingResult'];
            } else {
                $error_message = 'Cancellation Failed';
                $response ['message'] = $error_message;
            }
        } else {
            $response ['status'] = FAILURE_STATUS;
        }
        return $response;
    }

    /**
     * Save BookFlight Response
     * @param unknown_type $book_flight_response
     * @param unknown_type $app_reference
     * @param unknown_type $sequence_number
     */
    private function save_book_flight_response_details($book_flight_response, $app_reference, $sequence_number) {
        $update_data['book_id'] = $book_flight_response['a:UniqueID'];
        $api_book_status = strtoupper($book_flight_response['a:Status']);
        $update_data['attributes'] = json_encode(array('BookFlight' => $book_flight_response));

        $update_condition['app_reference'] = $app_reference;
        $update_condition['sequence_number'] = $sequence_number;
        $this->CI->custom_db->update_record('flight_booking_transaction_details', $update_data, $update_condition);

        //Update the status
        if ($api_book_status == 'CONFIRMED') {
            $flight_booking_status = 'BOOKING_CONFIRMED';
        } else if ($api_book_status == 'PENDING') {
            $flight_booking_status = 'BOOKING_FAILED';
        }
        $this->CI->common_flight->update_flight_booking_status($flight_booking_status, $app_reference, $sequence_number, $this->booking_source);
    }

    /**
     * Save TripDetails Response
     * @param unknown_type $book_flight_response
     * @param unknown_type $app_reference
     * @param unknown_type $sequence_number
     */
    public function save_trip_details_response($trip_details_response, $app_reference, $sequence_number, $search_id = 0) {
        if (intval($search_id) > 0) {
            $update_fare = true;
        } else {
            $update_fare = false;
        }
        $this->CI->load->library('flight/common_flight');

        $flight_booking_transaction_details_fk = $this->CI->custom_db->single_table_records('flight_booking_transaction_details', 'origin', array('app_reference' => $app_reference, 'sequence_number' => $sequence_number));
        $flight_booking_itinerary_details_fk = $this->CI->custom_db->single_table_records('flight_booking_itinerary_details', 'airline_code', array('app_reference' => $app_reference));
        $flight_booking_transaction_details_fk = $flight_booking_transaction_details_fk['data'][0]['origin'];

        $TravelItinerary = $trip_details_response['a:TravelItinerary'];
        $ItineraryInfo = $TravelItinerary['a:ItineraryInfo'];

        $book_id = $TravelItinerary['a:UniqueID'];
        //1.Update Price Details
        if ($update_fare === true) {

            $itineray_price_details = array();
            $itineray_price_details['PriceDetails'] = $ItineraryInfo['a:ItineraryPricing'];
            $itineray_price_details['PriceBreakup'] = $ItineraryInfo['a:TripDetailsPTC_FareBreakdowns']['a:TripDetailsPTC_FareBreakdown'];
            $itineray_price_details = $this->format_itineray_price_details($itineray_price_details, true);

            $airline_code = '';
            if (isset($flight_booking_itinerary_details_fk['data'][0]['airline_code'])) {
                $airline_code = $flight_booking_itinerary_details_fk['data'][0]['airline_code'];
            }

            $this->CI->load->library('flight/common_flight');


            $flight_price_details = $this->CI->common_flight->final_booking_transaction_fare_details($itineray_price_details, $search_id, $this->booking_source, $airline_code);
            $fare_details = $flight_price_details['Price'];
            $fare_breakup = $flight_price_details['PriceBreakup'];
            $passenger_breakup = $fare_details['passenger_breakup'];
            $single_pax_fare_breakup = $this->CI->common_flight->get_single_pax_fare_breakup($passenger_breakup);
            $this->CI->common_flight->update_flight_booking_tranaction_price_details($app_reference, $sequence_number, $fare_details['commissionable_fare'], $fare_details['admin_commission'], $fare_details['agent_commission'], $fare_details['admin_tds'], $fare_details['agent_tds'], $fare_details['admin_markup'], $fare_breakup);
        }

        //2.Update Segment Details:PNR,Terminal,Baggage
        $segment_details = force_multple_data_format($ItineraryInfo['a:ReservationItems']['a:ReservationItem']);

        foreach ($segment_details as $bsk => $bsv) {
            //itinerary condition for update
            $update_itinerary_condition = array();
            $update_itinerary_condition['flight_booking_transaction_details_fk'] = $flight_booking_transaction_details_fk;
            $update_itinerary_condition['app_reference'] = $app_reference;
            $update_itinerary_condition['from_airport_code'] = $bsv['a:DepartureAirportLocationCode'];
            $update_itinerary_condition['to_airport_code'] = $bsv['a:ArrivalAirportLocationCode'];
            $update_itinerary_condition['departure_datetime'] = date('Y-m-d H:i:s', strtotime($bsv['a:DepartureDateTime']));

            //itinerary updated data
            $update_itinerary_data = array();
            $update_itinerary_data['airline_pnr'] = $bsv['a:AirlinePNR'];
            $attributes['baggage'] = $bsv['a:Baggage'];
            $attributes['departure_terminal'] = $bsv['a:DepartureTerminal'];
            $attributes['arrival_terminal'] = $bsv['a:ArrivalTerminal'];
            $update_itinerary_data['attributes'] = json_encode($attributes);

            $GLOBALS['CI']->custom_db->update_record('flight_booking_itinerary_details', $update_itinerary_data, $update_itinerary_condition);
        }
        //3.Update Passenger Details: Ticket Details,Passenger Breakdown
        $passenger_details = force_multple_data_format($ItineraryInfo['a:CustomerInfos']['a:CustomerInfo']);
        $get_passenger_details_condition = array();
        $get_passenger_details_condition['flight_booking_transaction_details_fk'] = $flight_booking_transaction_details_fk;
        $passenger_details_data = $GLOBALS['CI']->custom_db->single_table_records('flight_booking_passenger_details', 'origin, passenger_type', $get_passenger_details_condition);
        $passenger_details_data = $passenger_details_data['data'];
        $passenger_origins = group_array_column($passenger_details_data, 'origin');
        $passenger_types = group_array_column($passenger_details_data, 'passenger_type');
        foreach ($passenger_details as $pax_k => $pax_v) {
            $passenger_fk = intval(array_shift($passenger_origins));
            $pax_type = array_shift($passenger_types);
            $ticket_id = '';
            switch (strtolower($pax_type)) {
                case 'adult':
                    $pax_type = 'ADT';
                    break;
                case 'child':
                    $pax_type = 'CHD';
                    break;
                case 'infant':
                    $pax_type = 'INF';
                    break;
            }
            if (isset($pax_v['a:ETickets']['a:ETicket']) == true && valid_array($pax_v['a:ETickets']['a:ETicket']) == true) {
                $ticket_number = $pax_v['a:ETickets']['a:ETicket']['a:eTicketNumber'];
            } else {
                $ticket_number = '';
            }
            if (isset($single_pax_fare_breakup[$pax_type]) == true) {
                $pax_fare_breakup = $single_pax_fare_breakup[$pax_type];
            } else {
                $pax_fare_breakup = array();
            }
            $this->CI->common_flight->update_passenger_ticket_info($passenger_fk, $ticket_id, $ticket_number, $pax_fare_breakup);
        }
    }

    /**
     * Formates Search Response
     * Enter description here ...
     * @param unknown_type $search_result
     * @param unknown_type $search_data
     */
    function format_search_data_response($search_result, $search_data) {
        $trip_type = isset($search_data ['is_domestic']) && !empty($search_data ['is_domestic']) ? 'domestic' : 'international';
        $PricedItinerary = $search_result ['s:Envelope'] ['s:Body'] ['AirLowFareSearchResponse']['AirLowFareSearchResult']['a:PricedItineraries']['a:PricedItinerary'];
        $PricedItinerary = force_multple_data_format($PricedItinerary);
        if (isset($PricedItinerary[0][0]['a:AirItineraryPricingInfo']) == false) {
            $PricedItinerary = array(($PricedItinerary));
        }

        $flight_list = array();
        foreach ($PricedItinerary as $result_k => $result_v) {
            foreach ($result_v as $journey_array_k => $journey_array_v) {
                $flight_details = array();
                $key = array();
                $key['key'][$journey_array_k]['booking_source'] = $this->booking_source;
                $flight_details = $this->flight_segment_summary($journey_array_v, $journey_array_k, $key, $search_data);
                if (valid_array($flight_details)) {
                    foreach ($flight_details as $f__key => $flight_detail) {
                        $flight_list['JourneyList'] [$result_k] [] = $flight_detail;
                    }
                }
            }
        }
        $response ['FlightDataList'] = $flight_list;
        return $response;
    }

    /**
     * Get flight details only
     *
     * @param array $segment        	
     */
    private function flight_segment_summary($journey_array, $journey_number, & $key, $search_data) {
        $itineray_price = $journey_array['a:AirItineraryPricingInfo']; //Price Details
        $segments = $journey_array['a:OriginDestinationOptions']['a:OriginDestinationOption'];
        $IsRefundable = $journey_array['a:AirItineraryPricingInfo']['a:IsRefundable'];
        $FareType = $journey_array['a:AirItineraryPricingInfo']['a:FareType'];


        $FareSourceCode = $journey_array['a:AirItineraryPricingInfo']['a:FareSourceCode'];
        $DirectionInd = $journey_array['a:DirectionInd'];
        if ($IsRefundable === 'Yes') {
            $IsRefundable = true;
        } else {
            $IsRefundable = false;
        }
        $summary = array();
        $flight_details = array();
        $price_details = array();
        $final_flight = array();

        // Loop on data to form details array
        $details = array();
        $flightNumberList = array();
        $segment_intl = array();
        $FareSellKey = array();
        $fare_object = array();
        $total_stop_count = - 1;

        if (strtolower($DirectionInd) === 'openjaw') {
            $segments = $this->format_multicity_segment_data($segments);
        } else if (strtolower($DirectionInd) === 'return') {
            $segments = $this->format_roundway_segment_data($segments);
        }
        $temp_segments = force_multple_data_format($segments['a:FlightSegments']['a:FlightSegment']);

        if (isset($temp_segments[0][0]) == false) {
            $segments_list[0] = $temp_segments;
        } else {
            $segments_list = $temp_segments;
        }
        foreach ($segments_list as $k => $v) {
            // legs Loop
            $legs = force_multple_data_format($v);
            $is_leg = true;
            $attr = array();
            foreach ($legs as $l_k => $l_v) {
                $total_stop_count ++;
                $origin_code = $l_v ['a:DepartureAirportLocationCode'];
                $destination_code = $l_v ['a:ArrivalAirportLocationCode'];
                $departure_dt = db_current_datetime($l_v ['a:DepartureDateTime']);
                $arrival_dt = db_current_datetime($l_v ['a:ArrivalDateTime']);
                $no_of_stops = 0;

                $cabin_class = $l_v['a:CabinClassCode'];
                $operator_code = $l_v ['a:OperatingAirline'] ['a:Code'];
                $operator_name = $this->get_airline_name($operator_code);
                $flight_number = $l_v ['a:OperatingAirline'] ['a:FlightNumber'];
                $total_duration = $l_v['a:JourneyDuration'];
                if (isset($l_v['a:SeatsRemaining']['a:Number']) == true) {
                    $AvailableSeats = $l_v['a:SeatsRemaining']['a:Number'];
                    /* if($l_v['a:SeatsRemaining']['a:BelowMinimum'] !== 'false' && intval($AvailableSeats) != 0){
                      $attr['AvailableSeats'] = $AvailableSeats;
                      } */

                    if ($AvailableSeats == 0) {
                        $AvailableSeats = rand(1, 4);
                    }

                    $attr['AvailableSeats'] = $AvailableSeats; //Check this
                }
                $stop_over = '';
                if ($l_v['a:StopQuantity'] == 1) {
                    if (isset($l_v['a:StopQuantityInformations']) && isset($l_v['a:StopQuantityInformations']['a:StopQuantityInfo'])) {
                        // debug($l_v['a:StopQuantityInformations']);exit;
                        $flight_airport = $this->CI->flight_model->get_airport_city_name($l_v['a:StopQuantityInformations']['a:StopQuantityInfo']['a:LocationCode']);
                        if (isset($flight_airport) && valid_array($flight_airport)) {
                            // echo $l_v['StopPointArrivalTime'];
                            $arrival_time = str_replace('T', ' ', $l_v['a:StopQuantityInformations']['a:StopQuantityInfo']['a:ArrivalDateTime']);

                            $arrival_time = date_create($arrival_time);

                            $departure_time = str_replace('T', ' ', $l_v['a:StopQuantityInformations']['a:StopQuantityInfo']['a:DepartureDateTime']);
                            $departure_time = date_create($departure_time);
                            $duration = date_diff($departure_time, $arrival_time);

                            $stop_over = 'This Flight has a technical stop at ' . $flight_airport->airport_city . '(' . $flight_airport->airport_code . ') for ' . $duration->format('%h') . ' hour ' . $duration->format('%i') . ' minutes';
                        } else {
                            if (isset($l_v['a:StopQuantityInformations']['a:StopQuantityInfo']['a:ArrivalDateTime']) && isset($l_v['a:StopQuantityInformations']['a:StopQuantityInfo']['a:ArrivalDateTime'])) {
                                $arrival_time = str_replace('T', ' ', $l_v['a:StopQuantityInformations']['a:StopQuantityInfo']['a:ArrivalDateTime']);

                                $arrival_time = date_create($arrival_time);

                                $departure_time = str_replace('T', ' ', $l_v['a:StopQuantityInformations']['a:StopQuantityInfo']['a:DepartureDateTime']);
                                $departure_time = date_create($departure_time);
                                $duration = date_diff($departure_time, $arrival_time);
                                $stop_over = 'This Flight has a technical stop at ' . $l_v['a:StopQuantityInformations']['a:StopQuantityInfo']['a:LocationCode'] . ' ' . $duration->format('%h') . ' hour ' . $duration->format('%i') . ' minutes';
                            }
                        }
                    }
                }

                $details[$k] [] = $this->format_summary_array($journey_number, $origin_code, $destination_code, $departure_dt, $arrival_dt, $operator_code, $operator_name, $flight_number, $no_of_stops, $cabin_class, '', '', $total_duration, $is_leg, $attr, $stop_over);
                $flightNumberList [] = $l_v ['a:OperatingAirline'] ['a:FlightNumber'];
                $is_leg = false;
            }
        }
        //Fare
        $price = $this->format_itineray_price_details($itineray_price);

        $flight_details ['Details'] = $details;
        $flight_ar ['FlightDetails'] = $flight_details;
        $flight_ar ['Price'] = $price;

        $key ['key'] [$journey_number]['FareSourceCode'] = $FareSourceCode;
        $key ['key'] [$journey_number]['FareType'] = $FareType;
        $flight_ar ['ResultToken'] = serialized_data($key ['key']);
        $is_refundable = $IsRefundable;
        $flight_ar ['Attr']['IsRefundable'] = $is_refundable;
        $final_flight[] = $flight_ar;
        $response = $final_flight;
        return $response;
    }

    /**
     * Formates Extra Services Details
     * @param unknown_type $extra_services
     */
    private function format_extra_services($extra_services, $search_data) {
        $formatted_extra_services = array();
        $Baggage = array();
        $extra_services = force_multple_data_format($extra_services['b:Services']['b:Service']);
        $index = 0;
        $main_index = 0;
        foreach ($extra_services as $exk => $exv) {
            $Type = strtoupper($exv['b:Type']);

            if ($Type == 'BAGGAGE') {//Only Baggage extra services
                $allowed_behavior = array('PER_PAX', 'PER_PAX_INBOUND', 'PER_PAX_OUTBOUND');
                $Behavior = $exv['b:Behavior'];

                if (in_array($Behavior, $allowed_behavior) == true) {
                    //TODO: Convert to Domain Currency
                    $Price = $exv['b:ServiceCost']['a:Amount'];

                    //Extract Wieght Details from Description
                    $Description = $exv['b:Description'];
                    $Description = explode('||', $exv['b:Description']);
                    if (!$Description[1]) {
                        debug($Description);
                        exit;
                    }

                    if (count($Description) == 2) {
                        $Description = $Description[0];
                    } else if (count($Description) == 3) {
                        $Description = $Description[1];
                    } else {
                        $Description = $Description[0];
                    }
                    $weight = preg_replace("/[^+0-9]/", "", $Description);
                    // echo $weight;exit;
                    $weight = array_sum(explode('+', $weight));

                    if ($Behavior == 'PER_PAX' || $Behavior == 'PER_PAX_OUTBOUND') {
                        $main_index = 0;
                    } else if ($Behavior == 'PER_PAX_INBOUND') {
                        if ($main_index != 1) {
                            $index = 0;
                        }
                        $main_index = 1;
                    }

                    if ($search_data['data']['type'] == 'OneWay' || $search_data['data']['type'] == 'return') {
                        if ($Behavior == 'PER_PAX_OUTBOUND' || $Behavior == 'PER_PAX') {
                            $origin = $search_data['data']['from'];
                            $destination = $search_data['data']['to'];
                        } elseif ($Behavior == 'PER_PAX_INBOUND') {
                            $destination = $search_data['data']['from'];
                            $origin = $search_data['data']['to'];
                        }
                    }
                    // $key ['key'][$index]['baggage_id'] = $exv['b:ServiceId'];
                    //                $baggage_id = serialized_data($key['key']);
                    $Baggage[$main_index][$index]['Code'] = $exv['b:ServiceId'];
                    $Baggage[$main_index][$index]['Price'] = $Price;
                    $Baggage[$main_index][$index]['Weight'] = $weight . ' Kg';
                    $Baggage[$main_index][$index]['Type'] = 'Dynamic';
                    $Baggage[$main_index][$index]['Origin'] = $origin;
                    $Baggage[$main_index][$index]['Destination'] = $destination;
                    $Baggage[$main_index][$index]['BaggageId'] = $exv['b:ServiceId'];
                    $index++;
                }
            }
        }
        if (valid_array($Baggage) == true) {
            $formatted_extra_services['Baggage'] = $Baggage;
        }

        return $formatted_extra_services;
    }

    /**
     * Formates the Multicity Segments
     * Enter description here ...
     */
    private function format_multicity_segment_data($segments) {
        $FlightSegment = force_multple_data_format($segments['a:FlightSegments']['a:FlightSegment']);
        $formatted_segment_list = array();
        $segment_list = array();
        $outer_index = 0;
        foreach ($FlightSegment as $k => $v) {
            $MarriageGroup = trim($v['a:MarriageGroup']);
            if ($k == 0) {
                $inner_index = 0;
            } else if ($k > 0 && strtoupper($MarriageGroup) === 'O') {
                $outer_index++;
                $inner_index = 0;
            }
            $segment_list[$outer_index][$inner_index++] = $v;
        }
        $formatted_segment_list['a:FlightSegments']['a:FlightSegment'] = $segment_list;
        return $formatted_segment_list;
    }

    /**
     * Formates the Roundway Segments
     * Enter description here ...
     */
    private function format_roundway_segment_data($segments) {
        $FlightSegment = force_multple_data_format($segments);
        $formatted_segment_list = array();
        $segment_list = array();
        foreach ($FlightSegment as $k => $v) {
            $segment_list[$k] = force_multple_data_format(($v['a:FlightSegments']['a:FlightSegment']));
        }
        $formatted_segment_list['a:FlightSegments']['a:FlightSegment'] = $segment_list;
        return $formatted_segment_list;
    }

    /**
     * Formates Itineray Price Details
     * @param unknown_type $itineray_price
     */
    private function format_itineray_price_details($itineray_price, $trip_details_fare = false) {
        $price = array();
        $passenger_breakup = array();
        if ($trip_details_fare == true) {
            $itineray_price = $this->format_trip_details_resonse_fare_details($itineray_price);
        }
        $currency_code = $itineray_price['a:ItinTotalFare']['a:EquivFare']['a:CurrencyCode'];
        $base_fare = $itineray_price['a:ItinTotalFare']['a:EquivFare']['a:Amount'];
        $tax = $itineray_price['a:ItinTotalFare']['a:TotalTax']['a:Amount'];
        $total_fare = $itineray_price['a:ItinTotalFare']['a:TotalFare']['a:Amount'];
        $pax_fare_breakdown = $itineray_price['a:PTC_FareBreakdowns']['a:PTC_FareBreakdown'];

        $pax_fare_breakdown = force_multple_data_format($pax_fare_breakdown);

        foreach ($pax_fare_breakdown as $k => $v) {
            $pax_type = $v['a:PassengerTypeQuantity']['a:Code'];
            $pax_count = $v['a:PassengerTypeQuantity']['a:Quantity'];
            $passenger_fare = $v['a:PassengerFare'];
            $pax_base_fare = $passenger_fare['a:EquivFare']['a:Amount'];
            $pax_total_fare = $passenger_fare['a:TotalFare']['a:Amount'];
            //Tax
            $pax_tax = 0;
            $taxes = force_multple_data_format($passenger_fare['a:Taxes']['a:Tax']);
            foreach ($taxes as $tax_k => $tax_v) {
                $pax_tax += $tax_v['a:Amount'];
            }
            $passenger_breakup[$pax_type]['BasePrice'] = ($pax_base_fare * $pax_count);
            $passenger_breakup[$pax_type]['Tax'] = ($pax_tax * $pax_count);
            $passenger_breakup[$pax_type]['TotalPrice'] = ($pax_total_fare * $pax_count);
            $passenger_breakup[$pax_type]['PassengerCount'] = $pax_count;
        }
        //Assigning to Fare Object
        $price = $this->get_price_object();
        $price['Currency'] = $currency_code;
        $price['TotalDisplayFare'] = $total_fare;
        $price['PriceBreakup']['Tax'] = $tax;
        $price['PriceBreakup']['BasicFare'] = $base_fare;
        $price['PassengerBreakup'] = $passenger_breakup;
        return $price;
    }

    /**
     * Formates TripDetails Response Details
     * @param unknown_type $itineray_price
     */
    private function format_trip_details_resonse_fare_details($itineray_price) {
        $formatted_itineray_price = array();

        $ItinTotalFare = array();
        $ItinTotalFare = $itineray_price['PriceDetails'];
        $ItinTotalFare['a:EquivFare'] = $ItinTotalFare['a:EquiFare'];
        $ItinTotalFare['a:TotalTax'] = $ItinTotalFare['a:Tax'];
        unset($ItinTotalFare['a:EquiFare'], $ItinTotalFare['a:Tax']);

        $PTC_FareBreakdown = array();
        $FareBreakdown = force_multple_data_format($itineray_price['PriceBreakup']);
        foreach ($FareBreakdown as $k => $v) {

            $PTC_FareBreakdown[$k] = $v;
            $PassengerFare = array();

            $PassengerFare = $v['a:TripDetailsPassengerFare'];
            $PassengerFare['a:EquivFare'] = $PassengerFare['a:EquiFare'];
            $PassengerFare['a:Taxes']['a:Tax'] = force_multple_data_format($PassengerFare['a:Tax']);
            unset($PTC_FareBreakdown[$k]['a:TripDetailsPassengerFare'], $PassengerFare['a:EquiFare'], $PassengerFare['a:Tax']);

            $PTC_FareBreakdown[$k]['a:PassengerFare'] = $PassengerFare;
        }

        $formatted_itineray_price['a:ItinTotalFare'] = $ItinTotalFare;
        $formatted_itineray_price['a:PTC_FareBreakdowns']['a:PTC_FareBreakdown'] = $PTC_FareBreakdown;
        return $formatted_itineray_price;
    }

    /**
     * Formates Fare Rule Response
     * @param unknown_type $fare_rule_response
     */
    function format_fare_rule_response($fare_rule_response) {
        $fare_rule_response = $fare_rule_response['s:Envelope']['s:Body']['FareRules1_1Response']['FareRules1_1Result']['a:FareRules']['a:FareRule'];
        $fare_rule_response = force_multple_data_format($fare_rule_response);

        $fare_rules = array();
        foreach ($fare_rule_response as $k => $v) {
            $origin = substr($v['a:CityPair'], 0, 3);
            $destination = substr($v['a:CityPair'], 3, 3);
            $fareRule_details = '';
            $rule_details = force_multple_data_format($v['a:RuleDetails']['a:RuleDetail']);
            foreach ($rule_details as $rule_k => $rule_v) {
                $fareRule_details .= '<strong>' . ($rule_k + 1) . '. ' . $rule_v['a:Category'] . ': </strong>';
                $fareRule_details .= $rule_v['a:Rules'] . '<br />';
            }
            $fare_rules[$k]['Origin'] = $origin;
            $fare_rules[$k]['Destination'] = $destination;
            $fare_rules[$k]['Airline'] = $v['a:Airline'];
            $fare_rules[$k]['FareRules'] = $fareRule_details;
        }
        return $fare_rules;
    }

    /**
     * 
     * Enter description here ...
     * @param unknown_type $update_fare_quote_response
     */
    function format_update_fare_quote_response($update_fare_quote_response, $search_data) {

        $PricedItinerary[] = force_multple_data_format($update_fare_quote_response['a:PricedItineraries']['a:PricedItinerary']);
        $update_fare_quote = array();
        foreach ($PricedItinerary as $result_k => $result_v) {
            foreach ($result_v as $journey_array_k => $journey_array_v) {
                $flight_details = array();
                $key = array();
                $key['key'][$journey_array_k]['booking_source'] = $this->booking_source;

                //Extra services
                if (isset($update_fare_quote_response['a:ExtraServices1_1']) == true && valid_array($update_fare_quote_response['a:ExtraServices1_1']) == true) {
                    $ExtraServices1_1 = $update_fare_quote_response['a:ExtraServices1_1'];
                    $key['key'][$journey_array_k]['a:ExtraServices1_1'] = $ExtraServices1_1;
                    $extra_services = $this->format_extra_services($ExtraServices1_1, $search_data);
                }

                $flight_details = $this->flight_segment_summary($journey_array_v, $journey_array_k, $key, $search_data);
                if (valid_array($flight_details)) {
                    $fr_index = 0;
                    foreach ($flight_details as $f__key => $flight_detail) {
                        $update_fare_quote['JourneyList'] [$result_k] [$fr_index] = $flight_detail;
                        if (isset($extra_services) == true && valid_array($extra_services) == true) {
                            $update_fare_quote['JourneyList'] [$result_k] [$fr_index]['ExtraServices'] = $extra_services;
                        }
                        $fr_index++;
                    }
                }
            }
        }
        return $update_fare_quote;
    }

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
     * check if the search RS is valid or not
     * @param array $search_result
     * search result RS to be validated
     */
    private function valid_search_result($search_result) {
        if (valid_array($search_result) == true and isset($search_result ['s:Envelope']) == true and isset($search_result ['s:Envelope'] ['s:Body']) == true and
                isset($search_result ['s:Envelope'] ['s:Body'] ['AirLowFareSearchResponse']) == true and isset($search_result ['s:Envelope'] ['s:Body'] ['AirLowFareSearchResponse']['AirLowFareSearchResult']) == true && $search_result ['s:Envelope'] ['s:Body'] ['AirLowFareSearchResponse']['AirLowFareSearchResult']['a:Success'] === 'true' && valid_array($search_result ['s:Envelope'] ['s:Body'] ['AirLowFareSearchResponse']['AirLowFareSearchResult']['a:PricedItineraries']['a:PricedItinerary']) == true) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * check if the AirRevalidate RS is valid or not
     * @param array $search_result
     * search result RS to be validated
     */
    private function valid_air_revalidate_response($air_revalidate_response) {
        if (valid_array($air_revalidate_response) == true and isset($air_revalidate_response ['s:Envelope']) == true and isset($air_revalidate_response ['s:Envelope'] ['s:Body']) == true and
                isset($air_revalidate_response ['s:Envelope'] ['s:Body'] ['AirRevalidateResponse']) == true and isset($air_revalidate_response ['s:Envelope'] ['s:Body'] ['AirRevalidateResponse']['AirRevalidateResult']) == true && $air_revalidate_response ['s:Envelope'] ['s:Body'] ['AirRevalidateResponse']['AirRevalidateResult']['a:IsValid'] === 'true' && $air_revalidate_response ['s:Envelope'] ['s:Body'] ['AirRevalidateResponse']['AirRevalidateResult']['a:Success'] === 'true' && valid_array($air_revalidate_response ['s:Envelope'] ['s:Body'] ['AirRevalidateResponse']['AirRevalidateResult']['a:PricedItineraries']['a:PricedItinerary']) == true) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * check if the BookFlight RS is valid or not
     * @param array $search_result
     * search result RS to be validated
     */
    private function valid_book_flight_response($book_flight_response) {
        if (valid_array($book_flight_response) == true and isset($book_flight_response ['s:Envelope']) == true and isset($book_flight_response ['s:Envelope'] ['s:Body']) == true and
                isset($book_flight_response ['s:Envelope'] ['s:Body'] ['BookFlightResponse']) == true and isset($book_flight_response ['s:Envelope'] ['s:Body'] ['BookFlightResponse']['BookFlightResult']) == true &&
                (strtoupper($book_flight_response ['s:Envelope'] ['s:Body'] ['BookFlightResponse']['BookFlightResult']['a:Status']) === 'CONFIRMED'
                OR strtoupper($book_flight_response ['s:Envelope'] ['s:Body'] ['BookFlightResponse']['BookFlightResult']['a:Status']) === 'PENDING'
                ) && $book_flight_response ['s:Envelope'] ['s:Body'] ['BookFlightResponse']['BookFlightResult']['a:Success'] === 'true') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * check if the TicketOrder RS is valid or not
     * @param array $search_result
     * search result RS to be validated
     */
    private function valid_order_ticket_response($order_ticket_response) {
        if (valid_array($order_ticket_response) == true and isset($order_ticket_response ['s:Envelope']) == true and isset($order_ticket_response ['s:Envelope'] ['s:Body']) == true and
                isset($order_ticket_response ['s:Envelope'] ['s:Body'] ['TicketOrderResponse']) == true and isset($order_ticket_response ['s:Envelope'] ['s:Body'] ['TicketOrderResponse']['TicketOrderResult']) == true && $order_ticket_response ['s:Envelope'] ['s:Body'] ['TicketOrderResponse']['TicketOrderResult']['a:Success'] === 'true') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * check if the TripDetails RS is valid or not
     * @param array $search_result
     * search result RS to be validated
     */
    private function valid_trip_details_response($trip_details_response) {
        if (valid_array($trip_details_response) == true and isset($trip_details_response ['s:Envelope']) == true and isset($trip_details_response ['s:Envelope'] ['s:Body']) == true and
                isset($trip_details_response ['s:Envelope'] ['s:Body'] ['TripDetailsResponse']) == true and isset($trip_details_response ['s:Envelope'] ['s:Body'] ['TripDetailsResponse']['TripDetailsResult']) == true && $trip_details_response ['s:Envelope'] ['s:Body'] ['TripDetailsResponse']['TripDetailsResult']['a:Success'] === 'true') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * check if the CancelBooking RS is valid or not
     * @param array $search_result
     * search result RS to be validated
     */
    private function valid_cancel_booking_response($cancel_booking_response) {
        if (valid_array($cancel_booking_response) == true and isset($cancel_booking_response ['s:Envelope']) == true and isset($cancel_booking_response ['s:Envelope'] ['s:Body']) == true and
                isset($cancel_booking_response ['s:Envelope'] ['s:Body'] ['CancelBookingResponse']) == true and isset($cancel_booking_response ['s:Envelope'] ['s:Body'] ['CancelBookingResponse']['CancelBookingResult']) == true && $cancel_booking_response ['s:Envelope'] ['s:Body'] ['CancelBookingResponse']['CancelBookingResult']['a:Success'] === 'true') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * process soap API request
     *
     * @param string $request        	
     */
    function form_curl_params($request, $url, $soapAction) {
        $data['status'] = SUCCESS_STATUS;
        $data['message'] = '';
        $data['data'] = array();
        $curl_data = array();
        $curl_data['booking_source'] = $this->booking_source;
        $curl_data['request'] = $request;
        $curl_data['url'] = $url;
        $curl_data['header'] = array(
            "SOAPAction: {$soapAction}",
            "Content-Type: text/xml; charset=UTF-8",
            "Content-Encoding: UTF-8",
            "Content-length: " . strlen($request),
            "Accept-Encoding: gzip,deflate"
        );
        $data['data'] = $curl_data;
        return $data;
    }

    /**
     * process soap API request
     *
     * @param string $request        	
     */
    function process_request($request, $url, $soapAction, $remarks = '') {
        $insert_id = $this->CI->api_model->store_api_request($url, $request, $remarks);
        $insert_id = intval(@$insert_id['insert_id']);

        $httpHeader = array(
            "SOAPAction: {$soapAction}",
            "Content-Type: text/xml; charset=UTF-8",
            "Content-Encoding: UTF-8",
            "Content-length: " . strlen($request),
            "Accept-Encoding: gzip,deflate"
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 180);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeader);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");
        $response = curl_exec($ch);
        //Update the API Response
        $this->CI->api_model->update_api_response($response, $insert_id);
        $error = curl_getinfo($ch);
        curl_close($ch);
        return $response;
    }

    /*     * ***************************************** REMOVE LATER ****************************** */

    /**
     * Test Method
     * 
     */
    function test_method() {
        echo 'Test Mystifly';
        exit;
    }

    /*
      $arrApi['CretateSession']	 = "Mystifly.OnePoint/OnePoint/CreateSession";
      $arrApi['AirLowFareSearch']  = "Mystifly.OnePoint/OnePoint/AirLowFareSearch";
      $arrApi['FareRules']		 = "Mystifly.OnePoint/OnePoint/FareRules1_1";
      $arrApi['Revalidate']   	 = "Mystifly.OnePoint/OnePoint/AirRevalidate";
      $arrApi['AirSeatMap']		 = "Mystifly.OnePoint/OnePoint/AirSeatMap";
      $arrApi['BookFlight']		 = "Mystifly.OnePoint/OnePoint/BookFlight";
      $arrApi['AirBookingData'] 	 = "Mystifly.OnePoint/OnePoint/AirBookingData";
      $arrApi['TicketOrder'] 		 = "Mystifly.OnePoint/OnePoint/TicketOrder";
      $arrApi['MessageQueues'] 	 = "Mystifly.OnePoint/OnePoint/MessageQueues";
      $arrApi['TripDetails']		 = "Mystifly.OnePoint/OnePoint/TripDetails";
      $arrApi['CancelBooking']     = "Mystifly.OnePoint/OnePoint/CancelBooking";
      $arrApi['RemoveMessageQueues']= "Mystifly.OnePoint/OnePoint/RemoveMessageQueues";
      $arrApi['EndPoint']			= "http://onepointdemo.myfarebox.com/V2/OnePoint.svc";
      $arrApi['Target']			= "Test"; // For test environment = "Test", For Production = "PRODUCTION"


      $strAccountNum 		= "MCN000017";
      $strUserName		= "IHOXML";
      $strPassword     	= "IHO2016_xml";
      $strEndPoint		= "http://onepointdemo.myfarebox.com/V2/OnePoint.svc";
     */

    /**
     * 
     * Enter description here ...
     */
    private function create_session_request() {
        $ApiPrefix = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:mys="Mystifly.OnePoint" xmlns:mys1="http://schemas.datacontract.org/2004/07/Mystifly.OnePoint" xmlns:arr="http://schemas.microsoft.com/2003/10/Serialization/Arrays" xmlns:mys2="Mystifly.OnePoint.OnePointEntities">
							<soapenv:Header/>
							<soapenv:Body>';
        $ApiSufix = '</soapenv:Body></soapenv:Envelope>';

        $strAccountNum = "MCN000017";
        $strUserName = "IHOXML";
        $strPassword = "IHO2016_xml";
        $strEndPoint = "http://onepointdemo.myfarebox.com/V2/OnePoint.svc";
        $strTarget = 'Test';
        $request = array();
        $CretateSessionRequest = '';
        $CretateSessionRequest .= $ApiPrefix;
        $CretateSessionRequest .= '<mys:CreateSession>
						<mys:rq>
							<mys1:AccountNumber>' . $strAccountNum . '</mys1:AccountNumber>
							<mys1:Password>' . $strPassword . '</mys1:Password>
							<mys1:Target>' . $strTarget . '</mys1:Target>
							<mys1:UserName>' . $strUserName . '</mys1:UserName>
						</mys:rq>
					</mys:CreateSession>';


        $CretateSessionRequest .= $ApiSufix;
        $request ['request'] = $CretateSessionRequest;
        $request ['url'] = $strEndPoint;
        $request ['soap_action'] = 'Mystifly.OnePoint/OnePoint/CreateSession';
        $request ['status'] = SUCCESS_STATUS;
        return $request;
    }

    /**
     * Get Cabin Class Search ID
     * @param unknown_type $CabinClass
     */
    private function get_cabin_class_id($CabinClass) {
        //Cabin Class: Y-Economy, C-Business, F- First
        switch (strtolower($CabinClass)) {
            case 'economy';
                $FlightCabinClass = 'Y';
                break;
            case 'business';
                $FlightCabinClass = 'C';
                break;
            case 'first';
                $FlightCabinClass = 'F';
                break;
            default:
                $FlightCabinClass = '';
        }
        return $FlightCabinClass;
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

    private function get_gender_type($pax_type) {
        switch (strtoupper($pax_type)) {
            case 1 : $pax_type = "M";
                break;
            case 2 : $pax_type = "F";
        }
        return $pax_type;
    }

    /**
     * Returns Message Queue Category
     * @param unknown_type $category_id
     */
    private function message_queue_category($category_id) {
        $category_id = intval($category_id);
        $category = '';
        switch ($category_id) {
            case 1:
                $category = 'Ticketed';
                break;
            case 2:
                $category = 'Booking';
                break;
            case 3:
                $category = 'Urgent';
                break;
            case 4:
                $category = 'ScheduleChange';
                break;
            case 5:
                $category = 'Cancelled';
                break;
            default:
                $category = 'Ticketed';
        }
        return $category;
    }

}
