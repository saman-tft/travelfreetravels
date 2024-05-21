<?php

//TODO: validate Client request
/**
 * Combines the Data from multiple API's
 * @author Elavarasi
 *
 */
error_reporting(0);
Class Transferv1_Blender {

    function __construct() {
        $this->CI = &get_instance();
        $this->CI->load->library('multi_curl');
        $this->CI->load->library('viatortransfer/Common_Transferv1');
    }

    /**
     * Assigns the Curl Parameters(URL,Header info.,Request)
     * @param unknown_type $request_params
     * @param unknown_type $curl_request
     * @param unknown_type $curl_url
     * @param unknown_type $curl_header
     * @param unknown_type $curl_booking_source
     */
    public function assign_curl_params($request_params, & $curl_request, & $curl_url, & $curl_header, & $curl_booking_source, & $curl_remarks) {
        $request = array($request_params['request']);
        $url = array($request_params['url']);
        $header = array($request_params['header']);
        $booking_source = array($request_params['booking_source']);
        $remarks = (isset($request_params['remarks']) == true ? array(trim($request_params['remarks'])) : array(''));

        $curl_request = array_merge($curl_request, $request);
        $curl_url = array_merge($curl_url, $url);
        $curl_header = array_merge($curl_header, $header);
        $curl_booking_source = array_merge($curl_booking_source, $booking_source);
        $curl_remarks = array_merge($curl_remarks, $remarks);
    }

    /**
     * Activity Active Booking Sources
     * 
     */
    private function activity_active_booking_sources($condition = array()) {
        $active_booking_source_condition = array();
        $active_booking_source_condition[] = array('BS.meta_course_list_id', '=', '"' . META_VIATOR_TRANSFER_COURSE . '"');
        $active_booking_source_condition[] = array('DL.origin', '=', get_domain_auth_id());
        $active_booking_source_condition = array_merge($active_booking_source_condition, $condition);
        $active_booking_sources = $this->CI->db_cache_api->get_active_api_booking_source($active_booking_source_condition);

        return $active_booking_sources;
    }

    /**
     * 	FIXME: check for other services(other than search)
     * Authenticates the API
     */
    private function api_authentication($active_booking_sources = array()) {
        $curl_params = array();
        $curl_request = array();
        $curl_url = array();
        $curl_header = array();
        $curl_booking_source = array();
        $curl_remarks = array();
        if (valid_array($active_booking_sources) == true) {
            $activity_active_booking_sources = $active_booking_sources;
        } else {
            $activity_active_booking_sources = $this->activity_active_booking_sources();
        }
        $activity_obj = array();
        
        foreach ($activity_active_booking_sources as $bs_k => $bs_v) {
            
            // debug($bs_v);exit;
                $activity_obj[$bs_k] = load_activity_lib($bs_v['booking_source'], '', true);
                //Authentication Request
                $authentication_request = $this->CI->$activity_obj[$bs_k]->get_authentication_request();
                if (empty($this->CI->$activity_obj[$bs_k]->api_session_id) == true && $authentication_request['status'] == SUCCESS_STATUS) {
                    $authentication_request['data']['remarks'] = $this->CI->$activity_obj[$bs_k]->booking_source_name;
                    $this->assign_curl_params($authentication_request['data'], $curl_request, $curl_url, $curl_header, $curl_booking_source, $curl_remarks);
                }
                
                
        }
       
        $curl_params['booking_source'] = $curl_booking_source;
        $curl_params['request'] = $curl_request;
        $curl_params['url'] = $curl_url;
        $curl_params['header'] = $curl_header;
        $curl_params['remarks'] = $curl_remarks;

        $authentication_result = $this->CI->multi_curl->execute_multi_curl($curl_params);
        debug($authentication_result);exit;
        foreach ($activity_obj as $obj_k => $obj_v) {
            if (valid_array($authentication_result) == true) {
                if (isset($authentication_result[$this->CI->$obj_v->booking_source])) {
                    $this->CI->$obj_v->set_api_session_id($authentication_result[$this->CI->$obj_v->booking_source]);
                }
            }
        }
    }
    /*
    *Returns the category list based on destination id
    */
    public function get_category_list($request){
        $response['status'] = FAILURE_STATUS;
        $response['data'] = array();
        $response['message'] ="";
        if($request){
            $product_active_booking_sources = $this->activity_active_booking_sources();           
            $activity_obj_ref = load_viatortransfer_lib($product_active_booking_sources[0]['booking_source'], '', true);                
            $category_list = $this->CI->$activity_obj_ref->get_category_list($request);

            if($category_list['status']==SUCCESS_STATUS){
                $response['status'] = SUCCESS_STATUS;
                 $response['data']= $category_list['data'];
            }else{
                $response['message'] = $category_list['message'];
            }         
        }else{
            $response['message'] = "Invalid Request";
        }
        return $response;      

    }
     /**
     * Returns Product list
     * @param int $search_id
     */
    public function product_list($search_id, $cache_key)
    {
        $curl_params = array();
        $curl_request = array();
        $curl_url = array();
        $curl_header = array();
        $curl_booking_source = array();
        $curl_remarks = array();
        
        $seach_result = array();
        $formatted_seach_result = array();
        $final_product_list = array();
        $final_product_list['status'] = FAILURE_STATUS;
        
        
        $product_active_booking_sources = $this->activity_active_booking_sources();
       
        $activity_obj = array();
        foreach ($product_active_booking_sources as $bs_k => $bs_v){

            $activity_obj_ref = load_viatortransfer_lib($bs_v['booking_source'], '', true);

            $activity_obj[$bs_v['booking_source']] = $activity_obj_ref;
            $search_request = $this->CI->$activity_obj_ref->get_search_request($search_id);
            //debug($search_request);exit;        
            if($search_request['status'] == SUCCESS_STATUS){
                $search_request['data']['remarks'] = $this->CI->$activity_obj_ref->booking_source_name;
                $this->assign_curl_params($search_request['data'],$curl_request, $curl_url, $curl_header, $curl_booking_source, $curl_remarks);
            }
        }
                
        $curl_params['booking_source'] = $curl_booking_source;
        $curl_params['request'] = $curl_request;
        $curl_params['url'] = $curl_url;
        $curl_params['header'] = $curl_header;
        $curl_params['remarks'] = $curl_remarks;
      
        //Mutl-Curl API Call
        $seach_result = $this->CI->multi_curl->execute_multi_curl_sightseeing($curl_params);
        

        foreach ($activity_obj as $fo_k => $fo_v){
            if(isset($seach_result[$fo_k]) == true){
              
                $activity_data = $this->CI->$fo_v->get_product_list($seach_result[$fo_k], $search_id);            
                // debug($flight_data);
                // echo "**************";
                // exit;
                if($activity_data['status'] == SUCCESS_STATUS && valid_array($activity_data['data']['TransferSearchResult']['TransferResults']) == true){
                    //Merge Flight List
                    if(valid_array($formatted_seach_result) == false){//Assiging the flight data, if not set(only for first API data, for next API's, it will be merged)
                        $formatted_seach_result = $activity_data;
                    } else {
                        $formatted_seach_result['data']['TransferSearchResult']['TransferResults'] = array_merge($formatted_seach_result['data']['TransferSearchResult']['TransferResults'], $activity_data['data']['TransferSearchResult']['TransferResults']);

                        $formatted_seach_result['status'] = SUCCESS_STATUS;
                    }
                }
            }
        }
        
       // exit;
        if (isset($formatted_seach_result['status']) == true && $formatted_seach_result['status'] == SUCCESS_STATUS) {

            if (count($product_active_booking_sources) > 1) {
                $ActivityList = array();
                $ActivityList = $formatted_seach_result['data']['TransferSearchResult']['TransferResults'];
                //TODO: Sort based on price

                $formatted_seach_result['data']['TransferSearchResult']['TransferResults'] = $ActivityList;
            }
            $carry_cache_key = $cache_key;
           # debug($formatted_seach_result);
          #  exit;
            $this->CI->load->library('viatortransfer/common_transferv1');

            $transfer_results[0] = $this->CI->common_transferv1->update_markup_and_insert_cache_key_to_token($formatted_seach_result['data']['TransferSearchResult'] ['TransferResults'], $carry_cache_key, $search_id);
            
            $transfer_results_sort = $this->sort_product_list($transfer_results);
           // debug($transfer_results_sort);exit;
            $formatted_seach_result['data']['TransferSearchResult'] ['TransferResults'] = $transfer_results_sort[0];
          
            $final_product_list = $formatted_seach_result;

        } else {
            $final_product_list['message'] = 'No Transfers Found';
        }
        
        // debug($final_product_list);
        // exit;
        // echo 'hhfhff';      
        return $final_product_list;
    }
     /*
     * Sort the hotesl based on price
     */

    private function sort_product_list($transfer_list) {
        $sorted_transfer_list = array();
        
        foreach ($transfer_list as $jl_k => $jl_v) {
            $sort_item = array();
            foreach ($jl_v as $row_k => $row_v) {
                
                $sort_item [$row_k] = floatval($row_v ['Price']['TotalDisplayFare']-$row_v ['Price']['PriceBreakup']['AgentCommission']+$row_v ['Price']['PriceBreakup']['AgentTdsOnCommision']);
            }
            
            array_multisort($sort_item, SORT_ASC, $jl_v);
            $sorted_transfer_list[$jl_k] = $jl_v;
        }
       
        return $sorted_transfer_list;
    }

     /**
     * 
     * Product  Details
     * @param unknown_type $request
     * @param unknown_type $search_id
     * @param unknown_type $cache_key
     */
    public function product_details($request, $search_id, $cache_key){

        $activity_details = array();
        $activity_details['data'] = array();
        $activity_details['status'] = FAILURE_STATUS;
        $activity_details['message'] = '';
        $ResultToken = trim($request['ResultToken']);        
        $product_search_data = Common_Transferv1::read_record($ResultToken);

        if (valid_array($product_search_data) == true) {
            $product_search_data = json_decode($product_search_data[0], true);
           
            $activity_details_request = array_values(unserialized_data($product_search_data['ResultToken']));
            
            $booking_source = $activity_details_request[0]['booking_source'];
            $activity_obj_ref = load_viatortransfer_lib($booking_source);         
           
            $activity_details_data = $this->CI->$activity_obj_ref->get_product_details($activity_details_request, $search_id);
            
            
            if (isset($activity_details_data['data']['TransferInfoResult'])) {

                if (isset($activity_details_data['data']['TransferInfoResult'])) {
                    $carry_cache_key = $cache_key;

                    //updating tourgrade price details
                    $activity_details_data['data']['TransferInfoResult'] = $this->CI->common_transferv1->update_activity_details_markup($activity_details_data['data']['TransferInfoResult'], $booking_source, $carry_cache_key, $search_id);

                    //update product price
                     $activity_details_data['data']['TransferInfoResult']['Price'] =  $this->CI->common_transferv1->update_activity_details_price_markup($activity_details_data['data']['TransferInfoResult']['Price'], $booking_source, $carry_cache_key, $search_id);


                }
            }            
           if($activity_details_data['status'] == SUCCESS_STATUS){
                $activity_details['status'] = SUCCESS_STATUS;
                $activity_details['data'] = $activity_details_data['data'];
           }
           else {
                $activity_details['message'] = $activity_details_data['message'];
            }
        }
        else {
            $activity_details['message'] = 'Session Expired for TransferDetails Request';
        }
        // debug($activity_details);
        // exit;
        return $activity_details;
    }
    public function select_tourgrage_list($request, $search_id, $cache_key){

        $activity_details = array();
        $activity_details['data'] = array();
        $activity_details['status'] = FAILURE_STATUS;
        $activity_details['message'] = '';
        $ResultToken = trim($request['ResultToken']);      

        $product_search_data = Common_Transferv1::read_record($ResultToken);
     // debug($product_search_data);exit;
        if (valid_array($product_search_data) == true) {
            $product_search_data = json_decode($product_search_data[0], true);
           
            $activity_details_request = array_values(unserialized_data($product_search_data));
            
            $booking_source = $activity_details_request[0]['booking_source'];
            $activity_obj_ref = load_viatortransfer_lib($booking_source);         
            
            $activity_details_data = $this->CI->$activity_obj_ref->get_tourgrade_details($activity_details_request,$request,$search_id);
           
            if (isset($activity_details_data['data']['Trip_list'])) {

                if (isset($activity_details_data['data']['Trip_list'])) {
                    $carry_cache_key = $cache_key;
                    $activity_details_data['data']['Trip_list'] = $this->CI->common_transferv1->update_tourgrade_markup($activity_details_data['data']['Trip_list'], $booking_source, $carry_cache_key, $search_id);
                }
            }            
           if($activity_details_data['status'] == SUCCESS_STATUS){
                $activity_details['status'] = SUCCESS_STATUS;
                $activity_details['data'] = $activity_details_data['data'];
           }
           else {
                $activity_details['message'] = $activity_details_data['message'];
            }
        }
        else {
            $activity_details['message'] = 'Invalid Tourgrade Request';
        }
        return $activity_details;
    }
    /**
     * 
     * Block Particular Tourgrade and check the Price
     * @param unknown_type $request
     * @param unknown_type $search_id
     * @param unknown_type $cache_key
     */
    public function block_tourgrade($request, $search_id, $cache_key){
        $block_tour_response = array();
        $block_tour_response['data'] = array();
        $block_tour_response['status'] = FAILURE_STATUS;
        $block_tour_response['message'] = '';
        $ResultToken = trim($request['ResultToken']);
        
       //Get Tourgrafe Details
        $activity_data = Common_Transferv1::read_record($ResultToken);
        $activity_data_response = json_decode($activity_data[0],true);
         
        if(valid_array($activity_data_response)){
           //Unserialized Product Details
            $activity_info_data = array_values(unserialized_data($activity_data_response['TourUniqueId']));            
        }
        if (valid_array($activity_data) == true && valid_array($activity_info_data) == true) {

            //Extract Tourgrade data
            $activity_data = json_decode($activity_data[0], true);
            $booking_source = $activity_info_data[0]['booking_source'];
            $activity_obj_ref = load_viatortransfer_lib($booking_source);

            $product_block_data = $this->CI->$activity_obj_ref->block_tourgrade($request, $activity_info_data,$activity_data,$search_id);
            if ($product_block_data['status'] == SUCCESS_STATUS) {

                $carry_cache_key = $cache_key;
               
                $block_tour_response['status'] = SUCCESS_STATUS;
                $block_tour_response['data'] = $product_block_data['data'];
                //debug($block_room_data);exit;
                $this->CI->load->library('viatortransfer/common_transferv1');

                $block_tour_response['data']['BlockTripResult'] = $this->CI->common_transferv1->cache_block_tour_data($product_block_data['data']['BlockTripResult'], $carry_cache_key, $search_id);
                $block_tour_response['message'] = "Block Tour Success";
            } else {
                $block_tour_response['message'] = $product_block_data['message'];
            }
            
        }else{
            $block_tour_response['message'] = 'Invalid BlockTrip Request';
        }
        // debug($block_tour_response);
        // exit;
        return $block_tour_response;

    }
      /**
     * Process the booking
     * @param unknown_type $request
     */
    public function process_booking($request, $search_id, $cache_key) {
        $booking_response = array();
        $booking_response['data'] = array();
        $booking_response['status'] = FAILURE_STATUS;
        $booking_response['message'] = '';

      
        $BlockTourId = trim($request['BlockTourId']);
        //Get Room Details
        $room_data = Common_Transferv1::read_record($BlockTourId);

        if (valid_array($room_data) == true) {

            $this->CI->load->library('viatortransfer/common_transferv1');
            //Extracting Product Details        
            
            $room_data = json_decode($room_data[0],true);
            
            $ResultToken = array_values(unserialized_data($room_data['BlockTourId']));
           
            $ResultToken = $ResultToken[0];
            $booking_source = $ResultToken['booking_source'];

            //Updating the Fare Details with Markup & commission
            $sightseen_price_details = $this->CI->common_transferv1->final_booking_transaction_fare_details($ResultToken['BlockTourDetails'], $search_id, $booking_source);

            $product_data['ResultToken'] = $ResultToken;
            $product_data['Price'] = $sightseen_price_details['Price'];
           // $product_data['RoomPriceBreakup'] = $sightseen_price_details['RoomPriceBreakup'];

            //1.Check Domain Balance For the Booking
            $booking_transaction_amount = $product_data['Price']['client_buying_price'];
            // echo 'herre'.$booking_transaction_amount;exit;
            if ($this->CI->domain_management->verify_domain_balance_viator($booking_transaction_amount, transferv1::get_credential_type()) == SUCCESS_STATUS) {//Verify Domain Balance

                $app_reference = $request['AppReference'];
                
                $passenger_details = $request['PassengerDetails'];
                $product_data['ProductDetails'] = $request['ProductDetails'];

                //echo "app_reference".$app_reference;
                //2.Save Sightseeing Details
                   
                $save_sightseeing_booking = $this->CI->common_transferv1->save_sightseen_booking($product_data, $passenger_details, $app_reference, $booking_source, $search_id);
                // debug($save_hotel_booking);exit;
              

                if ($save_sightseeing_booking['status'] == SUCCESS_STATUS) {
                    $book_req_params = array();

                    $book_req_params['ResultToken'] = $ResultToken;
                    $book_req_params['Passengers'] = $passenger_details;
                    //$book_req_params['RoomPriceBreakup'] = $sightseen_price_details['RoomPriceBreakup'];
                    $book_req_params['BookingQuestions'] = $request['BookingQuestions'];
                    $book_req_params['ProductDetails'] = $request['ProductDetails'];
                 
                    //$book_req_params[] = 
                    $active_booking_source_condition = array();
                    $active_booking_source_condition[] = array('BS.source_id', '=', '"' . $booking_source . '"');
                    $activity_active_booking_sources = $this->activity_active_booking_sources($active_booking_source_condition);
                   
                    //Authenticate the API's
                    //$this->api_authentication($activity_active_booking_sources);
                    
                    $tour_obj_ref = load_viatortransfer_lib($booking_source);
                   
                    $process_booking_response = $this->CI->$tour_obj_ref->process_booking($book_req_params, $app_reference, '', $search_id);

                   
                    if ($process_booking_response['status'] == SUCCESS_STATUS) {
                        //Deduct Sightseeing Booking Amount
                        $this->CI->common_transferv1->deduct_sightseeing_booking_amount($app_reference);
                        //Get Sightseeing Booking Details
                        $ss_booking_details = $this->CI->common_transferv1->get_ss_booking_transaction_details($app_reference);
                        $booking_response = $ss_booking_details;
                    } else {
                        $booking_response['message'] = $process_booking_response['message'];
                    }
                } else {//End of Save Booking Condition
                    $booking_response['message'] = $save_sightseeing_booking['message'];
                }
            } else {//End of Verify Balance Condition
                $booking_response['message'] = 'In Sufficiant Balance';
            }
        } else {
            $booking_response['message'] = 'Invalid CommitBooking Request';
        }
        //debug($booking_response);exit;
        return $booking_response;        
    }
    /**
    Hold booking
    */
    function hold_booking($request, $search_id, $cache_key){
        // debug($request);exit;
        $booking_response = array();
        $booking_response['data'] = array();
        $booking_response['status'] = FAILURE_STATUS;
        $booking_response['message'] = '';
        // debug($request);exit;
      
        $ResultToken = trim($request['ResultToken']);
        //Get Room Details
        $transfer_data = Common_Transferv1::read_record($ResultToken);
        $product_data = Common_Transferv1::read_record($request['ProductToken']);
       
        if (valid_array($transfer_data) == true) {
            $transfer_data = json_decode($transfer_data[0], true);
           
            $product_data_info = array_values(unserialized_data($product_data[0]));
            $product_data_info = $product_data_info[0];
            $transfer_data = $transfer_data;
            // debug($transfer_data);exit;
            // $transfer_data = json_decode($transfer_data[0],true);
            $booking_source = VIATOR_TRANSFER_BOOKING_SOURCE;
            // debug($transfer_data);exit;
            //Updating the Fare Details with Markup & commission
            $transfer_price_details = $this->CI->common_transferv1->final_booking_transaction_fare_details($transfer_data, $search_id, $booking_source);
            
            // $product_data['ResultToken'] = $ResultToken;
            $transfer_data['Price'] = $transfer_price_details['Price'];
           // $product_data['RoomPriceBreakup'] = $sightseen_price_details['RoomPriceBreakup'];
            // debug($transfer_data);exit;
            //1.Check Domain Balance For the Booking
            $booking_transaction_amount = $transfer_data['Price']['client_buying_price'];
            // debug($booking_transaction_amount);exit;
            // echo 'herre'.$booking_transaction_amount;exit;
            if ($this->CI->domain_management->verify_domain_balance_viator($booking_transaction_amount, transferv1::get_credential_type()) == SUCCESS_STATUS) {//Verify Domain Balance
                $app_reference = $request['AppReference'];
                $request['ProductDetails']['ProductCode'] = $request['ProductCode'];
                $request['ProductDetails']['BookingDate'] = $request['BookingDate'];
                $request['ProductDetails']['GradeCode'] = '';
                $request['ProductDetails']['pickupPoint'] = '';
                $request['ProductDetails']['hotelId'] = '';
                $product_data['Price'] = $transfer_data['Price'];
                $passenger_details = $request['PassengerDetails'];
                $product_data['ProductDetails'] = $request['ProductDetails'];
                $product_data['ResultToken']['ProductName'] = $transfer_data['ProductName'];
                $product_data['ResultToken']['StarRating'] = $transfer_data['StarRating'];
                $product_data['ResultToken']['grade_code'] = $transfer_data['ProductCode'];
                $product_data['ResultToken']['BlockTourDetails']['GradeDescription'] = $transfer_data['ProductCode'];
                $product_data['ResultToken']['product_code'] = $transfer_data['ProductCode'];
                $product_data['ResultToken']['Duration'] = $transfer_data['Duration'];
                $product_data['ResultToken']['ProductImage'] = $transfer_data['ImageUrl'];
                // $product_data = $transfer_data;
                // debug($product_data);exit;
                //2.Save Transfer details
                   
                $save_transfer_booking = $this->CI->common_transferv1->save_sightseen_booking($product_data, $passenger_details, $app_reference, $booking_source, $search_id);
                // debug($product_data_info);exit;
                if ($save_transfer_booking['status'] == SUCCESS_STATUS) {
                    $tour_obj_ref = load_viatortransfer_lib($booking_source);
                   // debug($tour_obj_ref);exit;
                    $policy = $this->CI->$tour_obj_ref->format_Tx_Cancellation_Policy($product_data_info['Cancellation_available'],$request['BookingDate'],'');
                    // debug($policy);exit;

                    $booking_response['status'] = SUCCESS_STATUS;
                    $item_id = time();
                    $update_tour_data['item_id'] = 'TM-'.$item_id;
                    $update_tour_data['viator_item_id'] = $item_id;
                    $update_tour_data['total_pax'] = $request['Totalpax'];
                    $tour_attributes['Cancellation_available'] = $transfer_data['Cancellation_available'];
                    $tour_attributes['Duration'] = $transfer_data['Duration'];
                    $tour_attributes['ProductImage'] = $transfer_data['ProductImage']; 
                               
                    $tour_attributes['Additional_info'] = $product_data_info['Additional_info'];
                    $tour_attributes['Exclusions'] = $product_data_info['Exclusions'];
                    $tour_attributes['Inclusions'] = $product_data_info['Inclusions'];
                    $tour_attributes['ShortDesc'] = $product_data_info['ShortDescription'];
                    $tour_attributes['TM_Cancellation_Charge'] = $policy['policy_details'];

                    $tour_attributes['TM_LastCancellation_date'] = $policy['free_cancel_date'];
                    $tour_attributes = json_encode($tour_attributes);

                    $this->CI->custom_db->update_record('viatortransfer_booking_details', $update_tour_data, array('app_reference' => trim($app_reference)));
                    
                    $booking_status = 'BOOKING_CONFIRMED';
                    $process_booking_response = $this->CI->$tour_obj_ref->update_booking_status($app_reference, $booking_status);
                    
                    //Deduct Sightseeing Booking Amount
                    $this->CI->common_transferv1->deduct_sightseeing_booking_amount($app_reference);
                    //Get Sightseeing Booking Details
                    $ss_booking_details = $this->CI->common_transferv1->get_ss_booking_transaction_details($app_reference);
                    // debug($ss_booking_details);exit;
                    $booking_response = $ss_booking_details;
                    
                }
                else {//End of Save Booking Condition
                    $booking_response['message'] = $save_sightseeing_booking['message'];
                }
            } else {//End of Verify Balance Condition
                    $booking_response['message'] = 'In Sufficiant Balance';
            }
        }else {//End of Verify Balance Condition
            $booking_response['message'] = 'Invalid CommitBooking Request';
        }
        // debug($booking_response);exit;
        return $booking_response;
        
    }
     /**
     * Elavarasi
     * Process cancel Booking Request
     * @param unknown_type $request
     */
    public function cancel_booking($request) {

        $cancel_booking_response = array();
        $cancel_booking_response['data'] = array();
        $cancel_booking_response['status'] = FAILURE_STATUS;
        $cancel_booking_response['message'] = '';

        if (valid_array($request) == true) {
            $app_reference = trim($request['AppReference']);
            $tour_booking_details = $this->CI->custom_db->single_table_records('viatortransfer_booking_details', '*', array('app_reference' => $app_reference));
           
            if ($tour_booking_details['status'] == SUCCESS_STATUS && $tour_booking_details['data'][0]['status'] == 'BOOKING_CONFIRMED') {

                $booking_transaction_details = $tour_booking_details['data'][0];
                $booking_source = $booking_transaction_details['booking_source'];

                $active_booking_source_condition = array();
                $active_booking_source_condition[] = array('BS.source_id', '=', '"' . $booking_source . '"');
                $tour_active_booking_sources = $this->activity_active_booking_sources($active_booking_source_condition);
                //Authenticate the API's
                //$this->api_authentication($tour_active_booking_sources);

                $ss_obj_ref = load_viatortransfer_lib($booking_source);

                $cancel_booking_details = $this->CI->$ss_obj_ref->cancel_booking($request);

                if ($cancel_booking_details['status'] == SUCCESS_STATUS) {
                    //Refund the cancellation Amount
                    $app_reference = $request['AppReference'];
                    $cancel_booking_response = $cancel_booking_details;
                } else {
                    $cancel_booking_response['message'] = $cancel_booking_details['message'];
                }
            } elseif ($tour_booking_details['status'] == SUCCESS_STATUS && $tour_booking_details['data'][0]['status'] == 'BOOKING_CANCELLED') {

                $booking_transaction_details = $tour_booking_details['data'][0];
                $booking_source = $booking_transaction_details['booking_source'];

                $active_booking_source_condition = array();
                $active_booking_source_condition[] = array('BS.source_id', '=', '"' . $booking_source . '"');
                $hotel_active_booking_sources = $this->activity_active_booking_sources($active_booking_source_condition);
                //Authenticate the API's
                //$this->api_authentication($hotel_active_booking_sources);

                $ss_obj_ref = load_viatortransfer_lib($booking_source);
                $cancel_booking_details = $this->CI->$ss_obj_ref->cancel_booking($request);

                if ($cancel_booking_details['status'] == SUCCESS_STATUS) {
                    //Refund the cancellation Amount
                    $app_reference = $request['AppReference'];
                    $cancel_booking_response = $cancel_booking_details;
                } else {
                    $cancel_booking_response['message'] = $cancel_booking_details['message'];
                }
                $cancel_booking_response['message'] = 'Booking Already Cancelled';
            } else {
                $cancel_booking_response['message'] = 'Invalid AppReference';
            }
        } else {
            $cancel_booking_response['message'] = 'Invalid CancelBooking Request';
        }

        return $cancel_booking_response;
    }
    /*Get Hold Booking Status*/
    public function get_hold_booking_status($request){
        $data = array();
        $data['status'] = FAILURE_STATUS;
        if ($request) {
            $activity_active_booking_sources = $this->activity_active_booking_sources();
            //Authenticate the API's
           // $this->api_authentication($activity_active_booking_sources);
            $hold_booking_info = array();
            foreach ($activity_active_booking_sources as $bs_k => $bs_v) {
                $ss_obj_ref = load_viatortransfer_lib($bs_v['booking_source'], '', true);
                $hold_booking_info = $this->CI->$ss_obj_ref->get_hold_booking_status($request);
               
            }
            if ($hold_booking_info['status'] == true) {
                $data['data'] = $hold_booking_info;
                $data['status'] = SUCCESS_STATUS;
                $data['message'] = 'Transfers Hold Booking Status';
            } else {
                $data['data'] = array();
                $data['status'] = FAILURE_STATUS;
                $data['message'] = 'Hold Booking Id Not Found';
            }
        }

        return $data;
    }

}