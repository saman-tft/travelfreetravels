<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Flight extends CI_Controller {
    //TODO: validate Client request

    /**
     * 1.Validate the Request Based On Method
     */ 
    private static $credential_type; //live/test
    private static $track_log_id; //Track Log ID
    private static $AppReference; //App Reference-- Booking Reference Id
    private $domain_commission_percentage = 0; //Domain Commission on Commision of Provab
    private $domain_id = 0; //domain origin
    private $course_version = FLIGHT_VERSION_2; //course version

    function __construct() {
        parent::__construct();
        $this->load->library('domain_management');
        $this->load->library('Exception_Logger', array('meta_course' => META_AIRLINE_COURSE));
        $this->load->library('flight/flight_blender');
        $this->load->model('flight_model');
        $this->load->helper('custom/app_helper');
        //$this->output->enable_profiler(TRUE);
    }

    /**
     * Test Method 
     * @param unknown_type $parameter
     */
    public function TestConnection($parameter = '') {
        echo 'New Flight Server Connected';
        exit;
    }

    public function is_valid_user($authentication_details = array()) {

        ////Enable for testing
        /* $UserName = 'test';
          $Password = 'password';
          $DomainKey = 'Vc8Z7XLsyys9TEQdFPkV';
          $System = 'test'; */
        //debug($authentication_details);exit;
        $UserName = $authentication_details['HTTP_X_USERNAME'];
        $Password = $authentication_details['HTTP_X_PASSWORD'];
        $DomainKey = $authentication_details['HTTP_X_DOMAINKEY'];
        $System = $authentication_details['HTTP_X_SYSTEM'];
        $SERVER_IP = $authentication_details['REMOTE_ADDR'];

        $domain_user_details = array();
        $domain_user_details['System'] = $System;
        $domain_user_details['DomainKey'] = $DomainKey;
        $domain_user_details['UserName'] = $UserName;
        $domain_user_details['Password'] = $Password;
        $domain_user_details['SERVER_ADDR'] = $SERVER_IP;
        // debug($domain_user_details);exit;        
        $this->custom_db->insert_record('customer_ip_address', $domain_user_details);

        $course = META_AIRLINE_COURSE;
        $domin_details = $this->domain_management->validate_domain($domain_user_details);
        if ($domin_details['status'] == SUCCESS_STATUS) {
            //Checking Domain Version
            $domain_course_version = $this->domain_management->validate_domain_course_version($course, $this->course_version, $domin_details['data']);
            if ($domain_course_version['status'] == SUCCESS_STATUS) {
                $this->domain_commission_percentage = $this->domain_management->get_flight_commission($domin_details['data']['domain_origin']); //Assiging Domain Flight Commission
                $this->domain_id = intval($domin_details['data']['domain_origin']);
                self::$credential_type = $domain_user_details['System'];
                self::$track_log_id = PROJECT_PREFIX . '-DOMAIN-' . $this->domain_id . '-' . time();
            } else {
                $domin_details = $domain_course_version;
            }
        }
        return $domin_details;
    }

    /**
     * Return Environment Live/Test
     */
    public static function get_credential_type() {
        return self::$credential_type;
    }

    /**
     * Handles Flight Requests
     * @param unknown_type $request_type
     * @param unknown_type $request
     * @param unknown_type $_header
     */
    public function service($request_type, $api_name='') {
        $request = file_get_contents("php://input");
        $headers_info = $_SERVER;
        //Vlaidte All Requests
        $is_valid_domain = $this->is_valid_user($headers_info);

        if ($is_valid_domain['status'] == true) {
            $this->load->model('api_model');
            $request = json_decode($request, true);
            //Store the Request Details
            $this->api_model->store_client_request($request_type, $request);
            //Track Log
            $cache_track_log_flag = $this->api_model->inactive_client_cache_services($request_type);

            if ($cache_track_log_flag === false) {
                $this->domain_management_model->create_track_log(self::$track_log_id, $request_type . ' - Started - Flight');
            }

            switch ($request_type) {
                case 'Search' :
                    $response = $this->Search($request, $api_name);
                    break;
                case 'FlexiSearch' :
                    $response = $this->FlexiSearch($request);
                    break;
                case 'FareRule' :
                    $response = $this->FareRule($request);
                    break;
                case 'UpdateFareQuote' :
                    $response = $this->UpdateFareQuote($request);
                    break;
                case 'ExtraServices' :
                    $response = $this->ExtraServices($request);
                    break;
                case 'HoldTicket' :
                    $response = $this->HoldTicket($request);
                    break;
                case 'CommitBooking' :
                    $response = $this->CommitBooking($request); 
                    break;
                case 'IssueHoldTicket' :
                    $response = $this->IssueHoldTicket($request);
                    break;
                case 'CancelBooking';
                    $response = $this->CancelBooking($request);
                    break;
                case 'GetCalendarFare' :
                    $response = $this->GetCalendarFare($request);
                    break;
                case 'UpdateCalendarFareOfDay' :
                    $response = $this->UpdateCalendarFareOfDay($request);
                    break;
                case 'BookingDetails' :
                    $response = $this->BookingDetails($request);
                    break;
                case 'TicketRefundDetails' :
                    $response = $this->TicketRefundDetails($request);

                    break;
                case 'PreConfirmation' :
                    $response = $this->PreConfirmation($request);
                    break;
               
                default:
                    $response['status'] = FAILURE_STATUS;
                    $response['message'] = 'Invalid Service';
            }

            //Track Log
            if ($cache_track_log_flag === false) {
                $track_log_comments = array($request_type . ' - Completed - Flight', $response);
                $track_log_comments = json_encode($track_log_comments);
                $this->domain_management_model->create_track_log(self::$track_log_id, $track_log_comments);
            }
        } else {
            //Invalid Domain User
            $response['status'] = FAILURE_STATUS;
            $response['message'] = $is_valid_domain['message'];
        }
        if (isset($response['status']) == true) {
            $data['Status'] = $response['status'];
            $data['Message'] = $response['message'];
            if (valid_array($response['data']) == true) {
                $data[$request_type] = $response['data'];
            }
        } else {
            $data['Status'] = FAILURE_STATUS;
            $data['Message'] = 'Invalid Service';
        }
         if($request_type=='HoldTicket' || $request_type=='CommitBooking' || $request_type=='IssueHoldTicket' || $request_type=='CancelBooking' || $request_type=='BookingDetails') {       
           $this->api_model->store_client_return_response($request_type, $request, $data);
         }
        
        
        output_service_json_data($data);
    }

    /**
     * Returns Flight List
     * 
     */
    public function Search($request, $api_name='') {
        $data['status'] = FAILURE_STATUS;
        $data['message'] = '';
        $data['data'] = array();
        $request['Sources']='LCC';
        if(empty($request['Sources'])==false)
        {
           $Sources=$request['Sources'];   
        } else 
        {
           $request['Sources']="ALL";   
        }
        $save_search_data = $this->flight_model->save_search_data($request);

        $search_type = META_AIRLINE_COURSE; // Static Data and created_by_id is also static
        $total_pax = $request['AdultCount'] + $request['ChildCount'] + $request['InfantCount'];
        if ($request ['JourneyType'] == "OneWay") {
            $trip_type = "oneway";
        } else if ($request ['JourneyType'] == "Return") {
            $trip_type = "roundway";
        } else if ($request ['JourneyType'] == "Multicity") {
            $trip_type = "multistop";
        } else {
            
        }

        if ($trip_type == "oneway" || $trip_type == "roundway") {
            $from_code = $request ['Segments'] [0] ['Origin'];
            $from_location_data = $this->flight_model->get_airport_city_name($from_code);
            $from_location = $from_location_data->airport_city;
            $to_code = $request ['Segments'] [0] ['Destination'];
            $to_location_data = $this->flight_model->get_airport_city_name($to_code);
            $to_location = $to_location_data->airport_city;
            $journey_date = $request ['Segments'] [0] ['DepartureDate'];
            $return_date = @$request ['Segments'] [0] ['ReturnDate'];
        } else {
            $seg_count = count($request ['Segments']);
            $from_code = $request ['Segments'] [0] ['Origin'];
            $from_location_data = $this->flight_model->get_airport_city_name($from_code);
            $from_location = $from_location_data->airport_city;
            $to_code = $request ['Segments'] [$seg_count - 1] ['Destination'];
            $to_location_data = $this->flight_model->get_airport_city_name($to_code);
            $to_location = $to_location_data->airport_city;
            $journey_date = $request ['Segments'] [0] ['DepartureDate'];
            $return_date = @$request ['Segments'] [$seg_count - 1] ['DepartureDate'];
        }
        $country = @$request['Country'];

        $this->custom_db->insert_record('search_flight_history', array(
            'domain_origin' => get_domain_auth_id(),
            'search_type' => $search_type,
            'from_location' => $from_location,
            'to_location' => $to_location,
            'from_code' => $from_code,
            'to_code' => $to_code,
            'trip_type' => $trip_type,
            'journey_date' => $journey_date,
            'total_pax' => $total_pax,
            'created_by_id' => '0',
            'created_datetime' => date('Y-m-d H:i:s')
        ));
        if ($save_search_data['status'] == true) {
            $search_id = $save_search_data['search_id'];
            $cache_key = $save_search_data['cache_key'];
            $active_api = array('Amadeus');
            
            $flight_list = $this->flight_blender->flight_list($search_id, $cache_key, $active_api, $country);
            if ($flight_list['status'] == SUCCESS_STATUS) {
                $data['status'] = $flight_list['status'];
                $data['data'] = $flight_list['data'];
            } else {
                $data['message'] = $flight_list['message'];
            }
        } else {
            $data['message'] = 'Invalid Search Request';
        }

        $provab_api_response_return = array();
        $provab_api_response_return['response_return_time'] = date('Y-m-d H:i:s');

        //commented since data is not updating and only time is updating so its not required 
       //$this->custom_db->update_record('provab_api_response_history', $provab_api_response_return, array('search_id' => intval($search_id)));


        return $data;
    }

    public function FlexiSearch($request) {
        $data['status'] = FAILURE_STATUS;
        $data['message'] = '';
        $data['data'] = array();
        
        $request['Sources']='LCC';
        if(empty($request['Sources'])==false)
        {
           $Sources=$request['Sources'];   
        } else 
        {
           $request['Sources']="ALL";   
        }
        $save_search_data = $this->flight_model->save_search_data($request);

        $search_type = META_AIRLINE_COURSE; // Static Data and created_by_id is also static
       
        $total_pax = $request['AdultCount'] + $request['ChildCount'] + $request['InfantCount'];
        if ($request ['JourneyType'] == "OneWay") {
            $trip_type = "oneway";
        } else if ($request ['JourneyType'] == "Return") {
            $trip_type = "roundway";
        } else if ($request ['JourneyType'] == "Multicity") {
            $trip_type = "multistop";
        } else {
            
        }

        if ($trip_type == "oneway" || $trip_type == "roundway") {
            $from_code = $request ['Segments'] [0] ['Origin'];
            $from_location_data = $this->flight_model->get_airport_city_name($from_code);
            $from_location = $from_location_data->airport_city;
            $to_code = $request ['Segments'] [0] ['Destination'];
            $to_location_data = $this->flight_model->get_airport_city_name($to_code);
            $to_location = $to_location_data->airport_city;
            $journey_date = $request ['Segments'] [0] ['DepartureDate'];
            $return_date = @$request ['Segments'] [0] ['ReturnDate'];
        } else {
            $seg_count = count($request ['Segments']);
            $from_code = $request ['Segments'] [0] ['Origin'];
            $from_location_data = $this->flight_model->get_airport_city_name($from_code);
            $from_location = $from_location_data->airport_city;
            $to_code = $request ['Segments'] [$seg_count - 1] ['Destination'];
            $to_location_data = $this->flight_model->get_airport_city_name($to_code);
            $to_location = $to_location_data->airport_city;
            $journey_date = $request ['Segments'] [0] ['DepartureDate'];
            $return_date = @$request ['Segments'] [$seg_count - 1] ['DepartureDate'];
        }
        

        $this->custom_db->insert_record('search_flight_history', array(
            'domain_origin' => get_domain_auth_id(),
            'search_type' => $search_type,
            'from_location' => $from_location,
            'to_location' => $to_location,
            'from_code' => $from_code,
            'to_code' => $to_code,
            'trip_type' => $trip_type,
            'journey_date' => $journey_date,
            'total_pax' => $total_pax,
            'created_by_id' => '0',
            'created_datetime' => date('Y-m-d H:i:s')
        ));
        if ($save_search_data['status'] == true) {
            $search_id = $save_search_data['search_id'];
            $cache_key = $save_search_data['cache_key'];

            $flight_list = $this->flight_blender->flexi_flight_list($search_id, $cache_key);

            if ($flight_list['status'] == SUCCESS_STATUS) {
                $data['status'] = $flight_list['status'];
                $data['data'] = $flight_list['data'];
            } else {
                $data['message'] = $flight_list['message'];
            }
        } else {
            $data['message'] = 'Invalid Search Request';
        }

        $provab_api_response_return = array();
        $provab_api_response_return['response_return_time'] = date('Y-m-d H:i:s');

        $this->custom_db->update_record('provab_api_response_history', $provab_api_response_return, array('search_id' => intval($search_id)));


        return $data;
    }

    /**
     * Returns Fare Rules
     * 
     */
    public function FareRule($request) {
        $data['status'] = FAILURE_STATUS;
        $data['message'] = '';
        $data['data'] = array();
        $ResultToken = trim($request['ResultToken']);
        // $ResultToken = trim(json_decode($request)->ResultToken);
        $search_id_cache_key = $this->get_search_id_cache_key($ResultToken);
        $search_id = $search_id_cache_key['search_id'];
        
        if (valid_array($request) == true) {
            $fare_rule_list = $this->flight_blender->fare_rules($request, $search_id);
            if ($fare_rule_list['status'] == SUCCESS_STATUS) {
                $data['status'] = $fare_rule_list['status'];
                $data['data'] = $fare_rule_list['data'];
            } else {
                $data['message'] = $fare_rule_list['message'];
            }
        } else {
            $data['message'] = 'Invalid FareRule Request';
        }
        return $data;
    }

    /**
     * Returns Updated Fare
     * 
     */
    public function UpdateFareQuote($request) {
        $data['status'] = FAILURE_STATUS;
        $data['message'] = '';
        $data['data'] = array();
        $ResultToken = trim($request['ResultToken']);
        $search_id_cache_key = $this->get_search_id_cache_key($ResultToken);
        if ($search_id_cache_key['status'] == true) {
            $search_id = $search_id_cache_key['search_id'];
            $cache_key = $search_id_cache_key['cache_key'];
            $updated_fare_quote = $this->flight_blender->update_fare_quote($request, $search_id, $cache_key);
            if ($updated_fare_quote['status'] == SUCCESS_STATUS) {
                $data['status'] = $updated_fare_quote['status'];
                $data['data'] = $updated_fare_quote['data'];
            } else {
                $data['message'] = $updated_fare_quote['message'];
            }
        } else {
            $data['message'] = 'Invalid UpdateFareQuote Request';
        }
        return $data;
    }

    /**
     * Returns ExtraServices
     * 
     */
    public function ExtraServices($request) {
        $data['status'] = FAILURE_STATUS;
        $data['message'] = '';
        $data['data'] = array();
        $ResultToken = trim($request['ResultToken']);
        $search_id_cache_key = $this->get_search_id_cache_key($ResultToken);
        if ($search_id_cache_key['status'] == true) {
            $search_id = $search_id_cache_key['search_id'];
            $cache_key = $search_id_cache_key['cache_key'];
            $extra_services = $this->flight_blender->get_extra_services($request, $search_id, $cache_key);
            if ($extra_services['status'] == SUCCESS_STATUS) {
                $data['status'] = $extra_services['status'];
                $data['data'] = $extra_services['data'];
            } else {
                $data['message'] = $extra_services['message'];
            }
        } else {
            $data['message'] = 'Invalid ExtraServices Request';
        }
        return $data;
    }

    /**
     * Process the Booking
     * 
     */
    public function CommitBooking($request) {
        $data['status'] = FAILURE_STATUS;
        $data['message'] = '';
        $data['data'] = array();
        $ResultToken = trim($request['ResultToken']);
        $search_id_cache_key = $this->get_search_id_cache_key($ResultToken);
        
        if ($search_id_cache_key['status'] == true) {
            $search_id = $search_id_cache_key['search_id'];
            $cache_key = $search_id_cache_key['cache_key'];

            $commit_book_response = $this->flight_blender->process_booking($request, $search_id, $cache_key);
            $data = $commit_book_response;
        } else {
            $data['message'] = 'Invalid CommitBooking Request or Session Expired';
        }
        return $data;
    }

    /**
     * Pre Confirmation
     * 
     */
    public function PreConfirmation($request) {

        $data['status'] = FAILURE_STATUS;
        $data['message'] = '';
        $data['data'] = array();
        $ResultToken = trim($request['ResultToken']);
        $search_id_cache_key = $this->get_search_id_cache_key($ResultToken);
        //debug($search_id_cache_key);exit;
        if ($search_id_cache_key['status'] == true) {
            $search_id = $search_id_cache_key['search_id'];
            $cache_key = $search_id_cache_key['cache_key'];
            $commit_book_response = $this->flight_blender->pre_confirmation($request, $search_id, $cache_key);
            $data = $commit_book_response;
        } else {
            $data['message'] = 'Invalid CommitBooking Request or Session Expired';
        }
        return $data;
    }

    /**
     * Hold the Ticket
     * 
     */
    public function HoldTicket($request) {
        // debug($request);die("hold ticket request");
        $data['status'] = FAILURE_STATUS;
        $data['message'] = '';
        $data['data'] = array();
        $ResultToken = trim($request['ResultToken']);
        $search_id_cache_key = $this->get_search_id_cache_key($ResultToken);
        if ($search_id_cache_key['status'] == true) {
            $search_id = $search_id_cache_key['search_id'];
            $cache_key = $search_id_cache_key['cache_key'];
            $hold_ticket_response = $this->flight_blender->hold_ticket($request, $search_id, $cache_key);
            $data = $hold_ticket_response;
        } else {
            $data['message'] = 'Invalid HoldTicket Request';
        }
        return $data;
    }

    /**
     * IssueHoldTicket
     * 
     */
    public function IssueHoldTicket($request) {
        $data['status'] = FAILURE_STATUS;
        $data['message'] = '';
        $data['data'] = array();
        if (valid_array($request) == true) {
            $issue_hold_ticket_response = $this->flight_blender->issue_hold_ticket($request);
            $data['status'] = $issue_hold_ticket_response['status'];
            $data['message'] = $issue_hold_ticket_response['message'];
        } else {
            $data['message'] = 'Invalid IssueHoldTicket Request';
        }
        return $data;
    }

    /**
     * Get the Booking Details
     * 
     */
    public function BookingDetails($request) {
        $data['status'] = FAILURE_STATUS;
        $data['message'] = '';
        $data['data'] = array();

        if (isset($request['AppReference']) == true && empty($request['AppReference']) == false) {
            $update_pnr_response = $this->flight_blender->booking_details($request);
            if ($update_pnr_response['status'] == SUCCESS_STATUS) {
                $data['status'] = $update_pnr_response['status'];
                $data['data'] = $update_pnr_response['data'];
            } else {
                $data['message'] = $update_pnr_response['message'];
            }
        } else {
            $data['message'] = 'Invalid BookingDetails Request';
        }
        return $data;
    }

    /**
     * Process Cancel Booking
     * 
     */
    public function CancelBooking($request) {


        $data['status'] = FAILURE_STATUS;
        $data['message'] = '';
        $data['data'] = array();
        if (valid_array($request) == true) {
            $cancel_book_response = $this->flight_blender->cancel_booking($request);
            $data = $cancel_book_response;
        } else {
            $data['message'] = 'Invalid CancelBooking Request';
        }
        return $data;
    }

    /**
     * Returns Search Id and Cache key based on Result Token
     * @param unknown_type $ResultToken
     */
    private function get_search_id_cache_key($ResultToken) {
        $data = array();
        $data['status'] = FAILURE_STATUS;
        $cache_key = $this->redis_server->extract_cache_key($ResultToken);
        $search_history = $this->custom_db->single_table_records('search_history', '*', array('cache_key' => trim($cache_key)));
        if ($search_history['status'] == SUCCESS_STATUS && valid_array($search_history['data'][0]) == true) {
            $data['status'] = SUCCESS_STATUS;
            $data['search_id'] = $search_history['data'][0]['origin'];
            $data['cache_key'] = trim($search_history['data'][0]['cache_key']);
        }
        return $data;
    }

    /**
     * Returns Flight List
     * 
     */
    public function GetCalendarFare($request) {
        $data['status'] = FAILURE_STATUS;
        $data['message'] = '';
        $data['data'] = array();
        if (valid_array($request) == true) {
            $calendar_fare = $this->flight_blender->calendar_fare($request);
            if ($calendar_fare['status'] == SUCCESS_STATUS) {
                $data['status'] = $calendar_fare['status'];
                $data['data'] = $calendar_fare['data'];
            } else {
                $data['message'] = $calendar_fare['message'];
            }
        } else {
            $data['message'] = 'Invalid GetCalendarFare Request';
        }
        return $data;
    }

    /**
     * Returns Flight List
     * 
     */
    public function UpdateCalendarFareOfDay($request) {
        $data['status'] = FAILURE_STATUS;
        $data['message'] = '';
        $data['data'] = array();
        if (valid_array($request) == true) {
            $calendar_fare = $this->flight_blender->update_calendar_fare($request);
            if ($calendar_fare['status'] == SUCCESS_STATUS) {
                $data['status'] = $calendar_fare['status'];
                $data['data'] = $calendar_fare['data'];
            } else {
                $data['message'] = $calendar_fare['message'];
            }
        } else {
            $data['message'] = 'Invalid UpdateCalendarFareOfDay Request';
        }
        return $data;
    }

    function synchronous_call() {
        $search_id = 123;
        $flight_list = $this->flight_blender->synchronous_call($search_id);
        echo 'Search Data---Synchronus';
        debug($flight_list);
    }

    private function TicketRefundDetails($request) {

        $data = array();
        $data['status'] = FAILURE_STATUS;
        $data['message'] = '';
        $app_reference = $request['AppReference'];
        $sequence_number = $request['SequenceNumber'];
        $booking_id = $request['BookingId'];
        $pnr = $request['PNR'];
        $ticket_id = $request['TicketId'];
        $change_request_id = $request['ChangeRequestId'];
        $booking_details = $this->flight_model->get_passenger_ticket_info($app_reference, $sequence_number, $booking_id, $pnr, $ticket_id);

        if ($booking_details['status'] == true) {
            $booking_details = $booking_details['data'];
            $master_booking_details = $booking_details['booking_details'][0];
            $booking_customer_details = $booking_details['booking_customer_details'][0];
            $cancellation_details = $booking_details['cancellation_details'][0];
            $currency_conversion_rate = $cancellation_details['currency_conversion_rate'];
            $TicketRefundDetails = array();
            $TicketRefundDetails['AppReference'] = $master_booking_details['app_reference'];
            $TicketRefundDetails['TicketId'] = $booking_customer_details['TicketId'];
            $TicketRefundDetails['ChangeRequestId'] = $cancellation_details['RequestId'];
            $TicketRefundDetails['ChangeRequestStatus'] = $cancellation_details['ChangeRequestStatus'];
            $TicketRefundDetails['StatusDescription'] = $cancellation_details['statusDescription'];
            $TicketRefundDetails['RefundStatus'] = $cancellation_details['refund_status'];
            $TicketRefundDetails['RefundedAmount'] = ($cancellation_details['refund_amount'] * $currency_conversion_rate);
            $TicketRefundDetails['CancellationCharge'] = ($cancellation_details['cancellation_charge'] * $currency_conversion_rate);
            $TicketRefundDetails['ServiceTaxOnRefundAmount'] = ($cancellation_details['service_tax_on_refund_amount'] * $currency_conversion_rate);
            $TicketRefundDetails['SwachhBharatCess'] = ($cancellation_details['swachh_bharat_cess'] * $currency_conversion_rate);

            $data['status'] = SUCCESS_STATUS;
            $data['data']['RefundDetails'] = $TicketRefundDetails;
        }
        // $response= json_encode($data);
        // debug($response);exit;
        return $data;
    }
    function TiecktNumbersPush(){
        $post_data = $_POST;
        $post_data = '{
    "orderNum": "1010792124",
    "airPnr": "CA/JFDKHF",
    "pnr": "HYFBKU",
    "paymentGate": "ALIPAY",
    "serialNum": "2013012200021000200172105074",
    "merchantOrder": "ALIPAY_20161101105225_1028814703",
    "permitVoid": 1,
    "lastVoidTime": "2018-01-03 13:00:00",
    "voidServiceFee": 5.00,
    "currency": "USD",
    "data":
    {
        "remark": {}
        },
    "ticketNums": [
            {
                "birthday": "1980-10-11",
                "cardNum": "G154017482",
                "cardType": "P",
                "firstName": "MARY",
                "lastName": "SMITH",
                "sex": "F",
                "ticketNum": "9990000000001"
            },
            {
                "birthday": "1980-10-11",
                "cardNum": "G17492581",
                "cardType": "P",
                "firstName": "JO",
                "lastName": "SMITH",
                "sex": "M",
                "ticketNum": "9990000000002"
            },
            {
                "birthday": "2016-08-01",
                "cardNum": "G58396302",
                "cardType": "P",
                "firstName": "ANGEL",
                "lastName": "SMITH",
                "sex": "F",
                "ticketNum": "9990000000003"
            }
        ]
}';
        $this->flight_blender->ticket_number_push($post_data);
    }
    function VoidResultPush(){
        $post_data = $_POST;
        $this->flight_blender->void_result_push($post_data);
    }
}
