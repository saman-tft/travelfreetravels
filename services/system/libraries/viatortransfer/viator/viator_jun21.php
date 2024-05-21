    <?php
require_once BASEPATH . 'libraries/viatortransfer/Common_api_transferv1.php';
ob_start();
error_reporting(E_ALL);
class Viator extends Common_Api_Transferv1 {

    var $master_search_data;
    var $search_hash;
    protected $token;
    private $end_user_ip = '127.0.0.1';
    var $api_session_id;
    var $api_cancellation_polciy_day;
    var $viator_cancel_date= 2;
    function __construct() {

        parent::__construct(META_VIATOR_TRANSFER_COURSE, VIATOR_TRANSFER_BOOKING_SOURCE);
        $this->CI = &get_instance();
        $this->CI->load->library('Converter');
        $this->set_api_credentials();
        //$this->set_api_session_id();
        $this->get_cancellation_policy_day();
    }
     private function set_api_credentials() {
        $this->service_url = $this->config['api_url'];
       
        $this->api_key = $this->config['api_key'];
       
        $this->currency = $this->config['currency']; //debug($this->config);exit;
    
    }
    function get_cancellation_policy_day(){
        $cancellation_day = $this->CI->custom_db->single_table_records('set_hotel_cancellation','*',array('api'=>$this->booking_source));
        if($cancellation_day['status']==1){
            $this->api_cancellation_policy_day = trim($cancellation_day['data'][0]['day']);
        }else{
            $this->api_cancellation_policy_day = 1;
        }
    }

    /**
     * Header to be used for hotebeds - JSON API Version
     */
    private function json_header() {
        $this->json_api_header = 
        array('api-key:' .trim($this->api_key),           
            'Content-Type: application/json',
            'Accept: application/json'
        );

        return $this->json_api_header;
    }
    /**
     * (non-PHPdoc)
     * @see Common_Api_Grind::search_data()
     */
   public function search_data($search_id) {
   
        $response ['status'] = true;
        $response ['data'] = array();
        $CI = & get_instance();

        if (empty($this->master_search_data) == true and valid_array($this->master_search_data) == false) {
            $clean_search_details = $GLOBALS ['CI']->transferv1_model->get_safe_search_data($search_id);
          //  debug($clean_search_details);exit;
            if ($clean_search_details ['status'] == true) {
                $response ['status'] = true;
                $response ['data'] = $clean_search_details ['data'];
                $this->master_search_data = $response ['data'];
            } else {                
                $response ['status'] = false;
            }           
        }
        else{
             $response ['data'] = $this->master_search_data;
        }
        $this->search_hash = md5(serialized_data($response ['data']));
        return $response;
        //debug($response);exit;
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
        //debug($search_data);exit;
        if ($search_data ['status'] == SUCCESS_STATUS) {
            // transfers search RQ
            $search_request = $this->search_request($search_data ['data']);
          
            if ($search_request ['status'] = SUCCESS_STATUS) {
                $response ['status'] = SUCCESS_STATUS;

                $curl_request = $this->form_curl_params($search_request['data'] ['request'], $search_request['data']['api_key'],$search_request ['data']['service_url']);
                
                $response ['data'] = $curl_request['data'];
            }
        }       
        return $response;
    }
    /**
    * Form Search Request For Viator
    */
    private function search_request($search_params) {
        // form request for hb 
        $request = array();
        $request['destId'] = $search_params['destination_id'];
        $request['currencyCode'] = $this->currency;

        if($search_params['text']!=''){

            if($search_params['cat_id']>0){
                $request['catId'] = $search_params['cat_id']; 
            
            }else{
                $request['catId'] = 0;
            }

            $request['searchTypes'] = array('PRODUCT');
            $request['text'] = $search_params['text'];
            $response ['data'] ['service_url'] = $this->service_url . 'search/freetext?apiKey='.$this->api_key;
            $response ['data']['remarks'] = 'GetViatorTextSearchResult(Transfers Viator)';
        }else{
            if($search_params['start_date']!=''){
                $request['startDate'] = date('Y-m-d',strtotime($search_params['start_date']));
            }
            if($search_params['end_date']!=''){
                $request['endDate'] = date('Y-m-d',strtotime($search_params['end_date']));
                //$request['data']['startDate'] = $search_params['to_date'];
            }           
           
            if($search_params['cat_id'] > 0){
                $request['catId'] = $search_params['cat_id'];
            }else{
                $request['catId'] = 0;
            }
            
            if($search_params['sub_cat_id'] > 0){
                $request['subCatId'] = $search_params['sub_cat_id'];           
            }else{
                $request['subCatId'] = 0;   
            }
            if($search_params['sort_order']!=''){
                $request['sortOrder'] = $search_params['sort_order'];
                //debug($request['data']['sortOrder']);exit;
            }else{
                $request['sortOrder'] = 'PRICE_FROM_A';
            }         

            $request['topX'] ="1-1000"; 
            $request['dealsOnly'] = false;
            $response ['data'] ['service_url'] = $this->service_url . 'search/products?apiKey='.$this->api_key;
            $response ['data']['remarks'] = 'GetViatorSearchResult(Transfer Viator)';
        }
        
        //debug($request);exit;
        $response ['data'] ['request'] = json_encode($request);
        $response ['data'] ['api_key'] = $this->api_key;      
       
        
        $response ['status'] = SUCCESS_STATUS;
       
        return $response;
    }
    /*
    *Get Sightseeing list
    **/
    public function get_product_list($activity_raw_data, $search_id){
       
        $response ['status'] = FAILURE_STATUS; // Status Of Operation
        $response ['message'] = ''; // Message to be returned
        $response ['data'] = array(); // Data to be returned
        $search_data = $this->search_data($search_id);
        
        if ($search_data ['status'] == SUCCESS_STATUS) {
            $api_response = json_decode($activity_raw_data, true);
           // debug($api_response);exit;
            if ($this->valid_search_result($api_response) == TRUE) {
                $api_response = $api_response;
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
        //debug($response);exit;
        return $response;
       
    }
     /**
     * Formates Search Response
     * Enter description here ...
     * @param unknown_type $search_result
     * @param unknown_type $search_data
     */
    function format_search_data_response($search_result, $search_data) {       
        $CI = & get_instance();
        $response =array();       
        $product_list= array();
        foreach ($search_result['data'] as $result_k => $value) {
          
          if(isset($value['data'])){
             if(valid_array($value['data'])){
                 $value = $value['data'];
             }
          }
            $key = array ();
            $key['key'][$result_k]['booking_source'] = $this->booking_source;
            $key['key'][$result_k]['ProductCode'] = $value['code'];
           $response[$result_k]['ProductName'] = $value['title'];
           $response[$result_k]['ProductCode'] = $value['code'];
           $response[$result_k]['ImageUrl'] = $value['thumbnailURL'];
           $response[$result_k]['ImageHisUrl'] = $value['thumbnailHiResURL'];
           $response[$result_k]['BookingEngineId'] = $value['bookingEngineId'];
           $response[$result_k]['Promotion'] = $value['specialOfferAvailable'];
           $response[$result_k]['PromotionAmount'] = $value['savingAmount'];
           $response[$result_k]['StarRating'] = $value['rating'];
           $response[$result_k]['ReviewCount'] = $value['reviewCount'];
           $response[$result_k]['DestinationName'] = $value['primaryDestinationName'];

           $format_price_arr = array();
           $format_price_arr['merchantNetPriceFrom'] = $value['merchantNetPriceFrom'];
           $format_price_arr['price'] = $value['price'];
          
           $response[$result_k]['Price']=$this->format_TX_price($format_price_arr);
           $response[$result_k]['Description'] = $value['shortDescription'];
           $response[$result_k]['Cancellation_available'] = $value['merchantCancellable'];
           $response[$result_k]['Cat_Ids'] = $value['catIds'];
           $response[$result_k]['Sub_Cat_Ids'] = $value['subCatIds'];
           $response[$result_k]['Supplier_Code'] = $value['supplierCode'];
           $response[$result_k]['Duration'] = $value['duration'];

           $response[$result_k]['ResultToken']  = serialized_data ($key['key']);  
        }
        $product_list['SSSearchResult']['SightSeeingResults'] = $response;
        $product_list['SSSearchResult']['Destination_Id'] = $search_data['destination_id'];      
      
        return $product_list;

    }
    /*Format Tourgrade List*/
    private function format_Product_Tourgrade($tourGrades){
        $tourGrades_list = array();
        foreach ($tourGrades as $key => $value) {
            $tourGrades_list[$key] = $value;
            $tourGrades_list[$key]['langServices'] = $value['langServices'];
            $tourGrades_list[$key]['gradeCode'] = $value['gradeCode'];
            $tourGrades_list[$key]['gradeTitle'] = $value['gradeTitle'];
            $tourGrades_list[$key]['gradeDepartureTime'] = $value['gradeDepartureTime'];
            $tourGrades_list[$key]['gradeDescription'] = $value['gradeDescription'];
            $tourGrades_list[$key]['defaultLanguageCode'] = $value['defaultLanguageCode'];        

            $format_price_arr = array();
            $format_price_arr['price'] = $value['priceFrom'];
            $format_price_arr['merchantNetPriceFrom'] = $value['merchantNetPriceFrom'];
            //merchantNetPriceFrom price for tmx
            $tourGrades_list[$key]['Price'] = $this->format_TX_price($format_price_arr);
        }

        return $tourGrades_list;

    }
    /*Format Product Reviews*/
    private function format_product_reviews($product_review){
        $review_list = array();
       
        if($product_review){
             foreach ($product_review as $key => $value) {
                $review_list[$key]['UserName'] = $value['ownerName'];
                $review_list[$key]['UserCountry'] = $value['ownerCountry'];
                $review_list[$key]['UserImage'] = $value['ownerAvatarURL'];
                $review_list[$key]['Rating'] = $value['rating'];
                $review_list[$key]['Review'] = $value['review'];
                $review_list[$key]['Published_Date'] = $value['submissionDate'];

            }
        }
       
        return $review_list;
    }
    /*Format Product Photos*/
    private function format_photos_url($product_photos){
        $photo_list = array();
        if($product_photos){
            foreach ($product_photos as $key => $value) {            
                $photo_list[$key]['productTitle'] = $value['productTitle'];
                $photo_list[$key]['ImageTitle'] = $value['title'];
                $photo_list[$key]['ImageCaption'] = $value['caption'];
                $photo_list[$key]['photoHiResURL'] = $value['photoHiResURL'];
                if(isset($value['photoHiResURL'])&&empty($value['photoHiResURL'])==false){
                    $photo_list[$key]['photoURL'] = $value['photoHiResURL'];
                }else{
                    $photo_list[$key]['photoURL'] = $value['photoURL'];    
                }
                
            }
        }
       
        return $photo_list;
    }
    /**
     * Convert Price If domain currecny is INR
     */
    private function format_price($total_price, $API_Currency) {     
        $conversion_amount = $GLOBALS['CI']->domain_management_model->get_currency_conversion_rate($API_Currency);       
        $conversion_rate = $conversion_amount['conversion_rate'];           
        $amount = $total_price*$conversion_rate; 
        return $amount;
    }
    /*Format Price*/
    private function format_TX_price($price_val_arr,$is_block_tourgrade=false){
        $price = array();
       // echo "ela";exit;
        $domain_id = get_domain_auth_id();
        $domain_currency_details =  $this->CI->domain_management_model->get_domain_details($domain_id);
        $domain_currency = $domain_currency_details['domain_base_currency'];
       
        if($domain_currency =='INR' && $is_block_tourgrade == false){

           $com_converterd_rate = $this->format_price($price_val_arr['price'],$this->currency);

           $net_converted_rate = $this->format_price($price_val_arr['merchantNetPriceFrom'],$this->currency);

            $price['NetFare'] = $net_converted_rate;//merchant price
            $price['TotalDisplayFare'] = $com_converterd_rate;//price for selling
           

        }else{
            $price['NetFare'] = $price_val_arr['merchantNetPriceFrom'];//merchant price
            $price['TotalDisplayFare'] = $price_val_arr['price'];//price for selling
            
        }
        $price['NetFare'] = round($price['NetFare']);
        $price['TotalDisplayFare'] = round( $price['TotalDisplayFare']);
        
         
        $price_breackup = $this->calculate_commission($price);     
        return $price_breackup;

    }
    //Calculate Price
    private function calculate_commission($price_arr){

        $fare_breakup = array();
        $price_details = array();       
        $api_net_fare = $price_arr['NetFare'];
        $api_comm_fare = $price_arr['TotalDisplayFare'];
        $api_commission_amount = ($api_comm_fare - $api_net_fare );
        $price_details['TotalDisplayFare']=$api_comm_fare;
        $price_details['GSTPrice'] = 0;
        $price_details['NetFare'] = $api_net_fare;
        $fare_breakup['AgentCommission'] =  $api_commission_amount;
        $admin_tds = (5/100)*$api_commission_amount;
        $fare_breakup['AgentTdsOnCommision'] =$admin_tds;
        $price_details['PriceBreakup'] = $fare_breakup;
       /* if($check_commission->value_type=='percentage'){
            $com_percentage = intval($check_commission->value);
            $agent_commission_amount = ($com_percentage/100)*$api_commission_amount;
            $admin_commission  = ($api_commission_amount-$agent_commission_amount);
            $agent_commission = $agent_commission_amount;

            //calculating TDS 5 % for everyone
            $admin_tds = (5/100)*$admin_commission;
            $agent_tds = (5/100)*$agent_commission;
            //$fare_breakup['API_Commission'] = $api_commission_amount;
            //$fare_breakup['AdminCommission'] = $admin_commission;
            $fare_breakup['AgentCommission'] =  $admin_commission;
           // $fare_breakup['AdminTdsCommission'] =$admin_tds; 
            $fare_breakup['AgentTdsOnCommision'] =$admin_tds;
            
            //$agent_netfare = $api_comm_fare-$agent_commission+$agent_tds;
            $agent_comm_fare =$api_comm_fare;
            //$fare_breakup['NetFare'] = $agent_netfare;
            //$fare_breakup['Comm_Fare'] = $agent_comm_fare;           
            $price_details['TotalDisplayFare']=$api_comm_fare;
            $price_details['NetFare'] = $NetFare;
            $price_details['PriceBreakup'] = $fare_breakup;
            
        }*/

       return $price_details;
    }
    /*Format SighSeeing Cancellation Policy based on cancellation */
    private function format_Tx_Cancellation_Policy($cancel_available,$booking_date,$price_details){
        $policy_arr = array();
        $day = $this->api_cancellation_policy_day + $this->viator_cancel_date;
        $current_date = date('Y-m-d');
        //travelomatix date        
        $free_cancellation_date = date('Y-m-d',strtotime("-".$day." day",strtotime($booking_date)));
        if($cancel_available){
             if($current_date<$free_cancellation_date){
                $policy_arr[0]['ChargeType'] = 1;
                $policy_arr[0]['Charge'] = 0;
                $policy_arr[0]['FromDate'] = date('Y-m-d');
                $policy_arr[0]['ToDate'] = $free_cancellation_date;
                
                $policy_arr[1]['ChargeType'] = 2;
                $policy_arr[1]['Charge'] = 100;
                $policy_arr[1]['FromDate'] = $free_cancellation_date;
                $policy_arr[1]['ToDate'] = $booking_date;
                $free_cancellation_date = $free_cancellation_date;
            }else{
                $policy_arr[0]['ChargeType'] = 2;
                $policy_arr[0]['Charge'] = 100;
                $policy_arr[0]['FromDate'] = date('Y-m-d');
                $policy_arr[0]['ToDate'] = $booking_date;
                $free_cancellation_date = date('Y-m-d');
            } 
        }else{
                $policy_arr[0]['ChargeType'] = 2;
                $policy_arr[0]['Charge'] = 100;
                $policy_arr[0]['FromDate'] = date('Y-m-d');
                $policy_arr[0]['ToDate'] = $booking_date;
                 $free_cancellation_date = date('Y-m-d');
        }             
        $response = array(
                'policy_details'=>$policy_arr,
                'free_cancel_date'=>$free_cancellation_date
            ); 
        return $response;


    }
    private function format_Tx_str_cancellation_text(){
        $day = $this->api_cancellation_policy_day + $this->viator_cancel_date;
        $day_str = '';
        if($day>1){
            $day_str = $day." day(s)";
        }else{
            $day_str = $day." day ";
        }
        $str = "If you cancel at least ".$day_str." in advance of the scheduled departure, there is no cancellation fee.";
        $str .="If you cancel within ".$day_str." of the scheduled departure, there is a 100 percent cancellation fee.";
        return $str;
    }
    /**
     * Activity Details
     * @param unknown_type $request
     * @param unknown_type $search_id
     */
    public function get_product_details($request, $search_id) {

        $response ['status'] = FAILURE_STATUS; // Status Of Operation
        $response ['message'] = ''; // Message to be returned
        $response ['data'] = array(); // Data to be returned
        $search_data = $this->search_data($search_id);
        

        $product_details_request = $this->product_details_request($search_data['data'], $request[0]['ProductCode']);

        //debug($activity_details_request);exit;
        if ($product_details_request ['status'] == SUCCESS_STATUS) {

            $product_details_response = $this->process_request($product_details_request ['data']['request'], $this->json_header(), $product_details_request['data'] ['service_url'], $product_details_request['data']['remarks']);
            $product_details_response = json_decode($product_details_response, true);
           
            $get_review_request = $this->product_review_request($request[0]['ProductCode']);
            $product_review_response = array();
            if($get_review_request['status']==SUCCESS_STATUS){

                $product_review_response_res = $this->process_request($get_review_request['data']['request'],$this->json_header(), $get_review_request['data'] ['service_url'], $get_review_request['data']['remarks']);

                $product_review_response_arr = json_decode($product_review_response_res, true);

                if(valid_array($product_review_response_arr['data'])){
                    $product_review_response = $product_review_response_arr['data'];
                }
                // debug($product_review_response);
                // exit;
            }
            // debug($product_review_response);
            // exit;
            //Get Product Photos
            $get_photo_request = $this->product_photo_request($request[0]['ProductCode']);
            $product_photo_response = array();
            if($get_photo_request['status']==SUCCESS_STATUS){
                //echo $get_photo_request['data'] ['service_url'];
                $product_photo_response = $this->process_request($get_photo_request['data']['request'],$this->json_header(), $get_photo_request['data'] ['service_url'], $get_photo_request['data']['remarks']);               
                $product_photo_response = json_decode($product_photo_response, true);               
            }
            //Get Product Booking Avaiability Dates
             $product_date_available_response = array();
            $get_availability_date_req = $this->product_booking_available_date($request[0]['ProductCode']);
            if($get_availability_date_req['status']==SUCCESS_STATUS){

                $product_date_available_response = $this->process_request($get_availability_date_req['data']['request'],$this->json_header(), $get_availability_date_req['data'] ['service_url'], $get_availability_date_req['data']['remarks']);  

                    
                // $result = $this->CI->custom_db->single_table_records('provab_api_response_history','*',array('origin'=>6939));

                // $product_date_available_response =$result['data'][0]['response'];

                $product_date_available_response = json_decode($product_date_available_response, true);
            }           
            // debug($activity_details_response);exit;
            if (valid_array($product_details_response['data']) == true && isset($product_details_response['data']) == true) {
                $response ['status'] = SUCCESS_STATUS;
                $response ['data']['SSInfoResult'] = $this->format_product_details($product_details_response,$product_photo_response,$product_date_available_response,$product_review_response,$search_id);
            } else {
                $response ['message'] = 'Product Details Not Available';
            }
            
        }
        else{
            $response ['status'] = FAILURE_STATUS;
        }       
       return $response;
    }

    /*Get Category List based on destination id*/
    public function get_category_list($request){
        $response ['status'] = FAILURE_STATUS; // Status Of Operation
        $response ['message'] = ''; // Message to be returned
        $response ['data'] = array(); // Data to be returned
        //getting the destination id for requested origin
        $destination_details = $this->CI->custom_db->single_table_records('api_sightseeing_destination_list','destination_id',array('origin'=>$request['city_id']),0,1);
        if($destination_details['status']==1){
          
            $destination_id = $destination_details['data'][0]['destination_id'];
            $service_url = $this->service_url.'taxonomy/categories?destId='.$destination_id.'&apiKey='.$this->api_key;          

            $category_list_response = $this->process_request("", $this->json_header(),$service_url,"Get CategoryList(Transfer Viator)"); 
            $category_list_response  = json_decode($category_list_response,true);
            if(valid_array($category_list_response['data'])&&$category_list_response['success']==true){
                 $response ['status'] = SUCCESS_STATUS; 
                $response['data']['CategoryList'] = $this->format_category_list($category_list_response);
                $response['message'] = "Category List";

            }else{
                $response['message'] = $category_list_response['errorMessageText'][0];
            }
        }else{
            $response['message'] = "Destination_Id is not available";
        }
        
        return $response;
    }
    /*Format Category list based on location*/
    public function format_category_list($category_response){
        $format_response = array();

        foreach ($category_response['data'] as $key => $value) {
            if($value['productCount']>0){
                $format_response[$key]['category_name'] = $value['groupName'];
                $format_response[$key]['category_id'] = $value['id'];
            
                $format_response[$key]['product_count'] = $value['productCount'];

                $sub_car_arr = array();
                foreach ($value['subcategories'] as $s_key => $s_value) {
                    $sub_car_arr[$s_key]['category_id'] =$s_value['categoryId'];
                    $sub_car_arr[$s_key]['sub_category_id'] = $s_value['subcategoryId'];
                    $sub_car_arr[$s_key]['sub_cate_name'] = $s_value['subcategoryName'];
                }
                 $format_response[$key]['sub_categories'] = $sub_car_arr;
            }
            
        }
        return $format_response;

    }
    /* Select Tourgrade List*/

    public function get_tourgrade_details($request,$ageBands_request,$search_id){
         $response ['status'] = FAILURE_STATUS; // Status Of Operation
        $response ['message'] = ''; // Message to be returned
        $response ['data'] = array(); // Data to be returned
        $search_data = $this->search_data($search_id); 
        $product_tourgrade_request = $this->tourgrade_select_request($ageBands_request);
        if ($product_tourgrade_request ['status'] == SUCCESS_STATUS) {

            $product_tourgrade_response = $this->process_request($product_tourgrade_request ['data']['request'], $this->json_header(), $product_tourgrade_request['data'] ['service_url'], $product_tourgrade_request['data']['remarks'],'post');  


             // $result = $this->custom_db->single_table_records('provab_api_response_history','*',array('origin'=>6349));

             //  $product_tourgrade_response =$result['data'][0]['response'];


            $product_tourgrade_response = json_decode($product_tourgrade_response, true);

            if($product_tourgrade_response['success']==SUCCESS_STATUS&&valid_array($product_tourgrade_response['data'])){
                 $response ['status'] = SUCCESS_STATUS;
                 $response['data'] = $this->format_tourgrade_response($product_tourgrade_response,$request);
            }else{
                $response['message'] = $product_tourgrade_response['errorMessageText'][0];
            }
            
            
        }
        else{
            $response['message'] = "Tourgrade result Not available";
        }

        return $response;
    }
    /**
    *Product Review Request
    */
    private function product_review_request($product_code){
        $response = array();
        $response['status'] = FAILURE_STATUS;
        $request = array();
        if($product_code !=''){
            $url = "apiKey=".$this->api_key."&code=".$product_code."&sortOrde=REVIEW_RATING_SUBMISSION_DATE_D";
            $response ['data'] ['service_url'] = $this->service_url.'product/reviews?'.$url;
            $response ['data'] ['api_key'] = $this->api_key; 
            $response ['data'] ['request'] = json_encode($request);      
            $response ['data']['remarks'] = 'Get Product Reviews (Transfer Viator)';
            $response['status'] = SUCCESS_STATUS;
        }
        return $response;    
    }   
    /**
    *Product Booking Availability date Request
    */
    private function product_booking_available_date($product_code){
         $response = array();
        $response['status'] = FAILURE_STATUS;
        $request = array();
        if($product_code !=''){
            $url = "apiKey=".$this->api_key."&productCode=".$product_code;
            $response ['data'] ['service_url'] = $this->service_url.'booking/availability/dates?'.$url;
            $response ['data'] ['api_key'] = $this->api_key; 
            $response ['data'] ['request'] = json_encode($request);      
            $response ['data']['remarks'] = 'Get Product Availability Date (Viator)';
            $response['status'] = SUCCESS_STATUS;
        }
        return $response; 
     
    }
    /**
    *Product Photo Request
    */
    private function product_photo_request($product_code){
        $response = array();
        $response['status'] = FAILURE_STATUS;
        $request = array();
        if($product_code !=''){
            $url = "apiKey=".$this->api_key."&code=".$product_code."&topX=1-20";
            $response ['data'] ['service_url'] = $this->service_url.'product/photos?'.$url;
            $response ['data'] ['api_key'] = $this->api_key; 
            $response ['data'] ['request'] = json_encode($request);      
            $response ['data']['remarks'] = 'Get Product Photos ( Transfer Viator)';
            $response['status'] = SUCCESS_STATUS;
        }
        return $response;    
    }   
    private function tourgrade_select_request($check_list){

        $response =array();
        $request = array();
        $request['productCode'] = $check_list['ProductCode'];
        $request['bookingDate'] =date('Y-m-d',strtotime($check_list['BookingDate']));
        $request['currencyCode'] = $this->currency;
        $request['ageBands'] = $check_list['ageBands'];

        $response['status'] = SUCCESS_STATUS;
        $response['data']['request']=json_encode($request);
        $response['data']['service_url'] = $this->service_url.'booking/availability/tourgrades?apiKey='.$this->api_key;
        $response['data']['remarks'] = "Get Tourgrade Info( Transfer Viator)";
       
        return $response;
    }

    /* create request for Product detail API */

    function product_details_request($search_params, $ProductCode) {
        $response ['data'] = array();
        $request = array();

        //$ProductCode  = '5253LASMYS';
        $url = "apiKey=".$this->api_key."&code=".$ProductCode."&currencyCode=".$this->currency;
        $response ['data'] ['request'] = json_encode($request);
        $response ['data'] ['service_url'] = $this->service_url . 'product?'.$url;
        $response ['data'] ['api_key'] = $this->api_key;       
        $response ['data']['remarks'] = 'Get Product Details(Transfer Viator)';
        $response ['status'] = SUCCESS_STATUS;

        return $response;
    }
     /**
     * Format Product Details
     * @param unknown_type $product_details_response
     */
    private function format_product_details($product_details_response,$product_photos,$product_available_date,$product_review_response,$search_id) {
        #debug($product_details_response);exit;       
        $product_details_response = $product_details_response['data'];
        $product_details_response_arr = array();
         $product_details_response_arr['ProductName'] =$product_details_response['title'];
         $product_details_response_arr['ReviewCount'] =$product_details_response['reviewCount'];
         if(isset($product_details_response['thumbnailHiResURL'])&&empty(trim($product_details_response['thumbnailHiResURL']))==true){

            $product_details_response_arr['product_image'] =$product_details_response['thumbnailHiResURL'];
         }else{
            $product_details_response_arr['product_image'] =$product_details_response['thumbnailURL'];
         }
         
         $product_details_response_arr['StarRating'] =$product_details_response['rating'];

         $product_details_response_arr['Promotion'] =$product_details_response['specialOfferAvailable'];
         $product_details_response_arr['Duration'] = $product_details_response['duration'];
         $product_details_response_arr['ProductCode'] = $product_details_response['code'];
         

         if(count($product_photos['data'])>0){
              $product_details_response_arr['ProductPhotos'] = $this->format_photos_url($product_photos['data']);
         }else{
            
            $arr=array(
                'photoHiResURL'=>$product_details_response['thumbnailHiResURL'],
                'photoURL'=>$product_details_response['thumbnailURL'],
                'ImageCaption'=>$product_details_response_arr['ProductName']
                );
            $product_details_response_arr['ProductPhotos'] =array($arr);

         }
         if(valid_array($product_details_response['videos'])){
             if($product_details_response['videos']){

                $product_details_response_arr['Product_Video'] = $product_details_response['videos'][0];

             }else{
                $product_details_response_arr['Product_Video'] ='';
             }
         }else{
            $product_details_response_arr['Product_Video'] ='';
         }
                
         // debug(  $product_details_response_arr['ProductPhotos']);
         // exit;
         $format_price_arr = array();
         $format_price_arr['merchantNetPriceFrom'] = $product_details_response['merchantNetPriceFrom'];
          $format_price_arr['price'] = $product_details_response['price'];
        
         $product_details_response_arr['Price'] =$this->format_TX_price($format_price_arr);
        // debug($product_details_response_arr);
        // exit;
        $product_details_response_arr['Product_Reviews'] =$this->format_product_reviews($product_review_response);
        $product_details_response_arr['Product_Tourgrade'] =$this->format_Product_Tourgrade($product_details_response['tourGrades']);
        $product_details_response_arr['Product_available_date'] = $product_available_date['data'];
        //$product_details_response_arr['supplierName'] = $product_details_response['supplierName'];

        $product_details_response_arr['Cancellation_available'] =$product_details_response['merchantCancellable'];

        if($product_details_response['merchantCancellable']==SUCCESS_STATUS){
            $cancel_str = $this->format_Tx_str_cancellation_text();
            $day = $this->api_cancellation_policy_day + $this->viator_cancel_date;
            $product_details_response_arr['Cancellation_day']  =$day;

         }else{
            $cancel_str = "This Booking is Non Refundable rate.";
            $product_details_response_arr['Cancellation_day']  =0;
        }
        $product_details_response_arr['Cancellation_Policy'] =$cancel_str;

        //format cancellation policy 
       
        // $cancellation_policy = $this->format_Tx_Cancellation_Policy($product_details_response['merchantCancellable'],$product_details_response_arr['Price']);


        $product_details_response_arr['BookingEngineId'] = $product_details_response['bookingEngineId']; 
        $product_details_response_arr['Voucher_req'] = $product_details_response['voucherRequirements'];
        $product_details_response_arr['Tourgrade_available'] = $product_details_response['tourGradesAvailable'];
        $product_details_response_arr['UserPhotos'] = $product_details_response['userPhotos'];      

        $product_details_response_arr['Product_AgeBands'] =$product_details_response['ageBands'];
        $product_details_response_arr['BookingQuestions'] = $product_details_response['bookingQuestions'];
        $product_details_response_arr['Highlights'] = $product_details_response['highlights'];
        $product_details_response_arr['SalesPoints'] = $product_details_response['salesPoints'];
        $product_details_response_arr['TermsAndConditions'] =$product_details_response['termsAndConditions'];
         $product_details_response_arr['MaxTravellerCount'] =$product_details_response['maxTravellerCount'];
         $product_details_response_arr['Itinerary'] =$product_details_response['itinerary'];
         
         $voucher_option_text ='';
         if($product_details_response['voucherOption']){
            if($product_details_response['voucherOption']=='VOUCHER_E'){
                $voucher_option_text = 'EVouchers + Paper Vouchers accepted';
            }elseif ($product_details_response['voucherOption']=='VOUCHER_PAPER_ONLY') {
                $voucher_option_text = 'Paper Vouchers only accepted';
            }elseif ($product_details_response['voucherOption']=='VOUCHER_ID_ONLY') {
                $voucher_option_text ='ID + EVouchers + Paper vouchers accepted';
            }
         }
         $product_details_response_arr['VoucherOption'] =$voucher_option_text;
         $product_details_response_arr['VoucherOption_List'] = $product_details_response['voucherOption'];

         $product_details_response_arr['AdditionalInfo'] =$product_details_response['additionalInfo'];
         $product_details_response_arr['Inclusions'] =$product_details_response['inclusions'];
         $product_details_response_arr['DepartureTime'] =$product_details_response['departureTime'];
         $product_details_response_arr['DeparturePoint'] =$product_details_response['departurePoint'];
         $product_details_response_arr['DepartureTimeComments'] =$product_details_response['departureTimeComments'];

         $product_details_response_arr['ReturnDetails'] = $product_details_response['returnDetails'];

         $product_details_response_arr['Exclusions'] =$product_details_response['exclusions'];

         $product_details_response_arr['ShortDescription'] = $product_details_response['shortDescription'];

         $product_details_response_arr['Description'] =$product_details_response['description'];

         $product_details_response_arr['Location'] =$product_details_response['location'];
         $product_details_response_arr['AllTravellerNamesRequired'] = $product_details_response['allTravellerNamesRequired'];
         $product_details_response_arr['Country'] =$product_details_response['country'];
         $product_details_response_arr['Region'] =$product_details_response['region'];
         $detail = array();
         $detail['key'][0]['available_date'] = $product_details_response_arr['Product_available_date'];
         $detail['key'][0]['booking_question'] =  $product_details_response_arr['BookingQuestions'];
         $detail['key'][0]['Cancellation_available'] =  $product_details_response_arr['Cancellation_available'];
         $detail['key'][0]['booking_source'] = $this->booking_source;
         $detail['key'][0]['ProductCode'] = $product_details_response_arr['ProductCode'];
         $detail['key'][0]['HotelPickup'] = $product_details_response['hotelPickup'];
         $detail['key'][0]['ProductName'] = $product_details_response_arr['ProductName'];
         $detail['key'][0]['ReviewCount'] = $product_details_response_arr['ReviewCount'];
         $detail['key'][0]['ProductImage'] = $product_details_response_arr['product_image'];
         $detail['key'][0]['StarRating'] = round($product_details_response['rating']);
         $detail['key'][0]['Duration'] = $product_details_response['duration'];
         $product_details_response_arr['ResultToken'] = serialized_data($detail['key']);
         // debug($product_details_response_arr);
         // exit;
         return $product_details_response_arr;

    }
    /*Format Tourgrade Response*/
    private function format_tourgrade_response($tourgrade_response_arr,$available_date){
        $tourgrade_list = array();
        $tourgrade_response = array();
        $product_info = array();
        $product_info['ProductCode'] = $available_date[0]['ProductCode'];
        $product_info['ProductName'] =$available_date[0]['ProductName'];
        $product_info['ProductImage'] =$available_date[0]['ProductImage'];
        $product_info['StarRating'] = $available_date[0]['StarRating'];
        $product_info['Duration'] =$available_date[0]['Duration'];  
        $sort_avail_grade_list = array();

        foreach ($tourgrade_response_arr['data'] as $result_k => $value) {
            
         

           // if($value['available']==true){
                $tourgrade_list[$result_k]['bookingDate'] = $value['bookingDate'];
                $tourgrade_list[$result_k]['ageBandsRequired'] = $value['ageBandsRequired'];
                $tourgrade_list[$result_k]['langServices'] = $value['langServices'];
                $tourgrade_list[$result_k]['productCode'] = $available_date[0]['ProductCode'];
                $tourgrade_list[$result_k]['gradeCode'] = $value['gradeCode'];
                $tourgrade_list[$result_k]['gradeTitle'] = $value['gradeTitle'];
                $tourgrade_list[$result_k]['gradeDepartureTime'] = $value['gradeDepartureTime'];
                $tourgrade_list[$result_k]['gradeDescription'] = $value['gradeDescription'];
                $tourgrade_list[$result_k]['language_code'] = $value['defaultLanguageCode'];
                $tourgrade_list[$result_k]['available'] = $value['available'];
                $sort_avail_grade_list[$result_k] =  $tourgrade_list[$result_k]['available'];

                //form tourgrade ageband
                $ageband_arr = array();
                $total_band_count = 0;
                foreach ($value['ageBands'] as $a_key => $a_value) {
                    $ageband_arr[$a_key]['bandId'] = $a_value['bandId'];
                    $ageband_arr[$a_key]['count'] = $a_value['count'];
                    $total_band_count +=$a_value['count'];
                }
                $tourgrade_list[$result_k]['AgeBands'] = $ageband_arr;
                $tourgrade_list[$result_k]['TotalPax'] = $total_band_count;
                
                $format_price_arr = array();
                $format_price_arr['price'] = $value['retailPrice'];
                $format_price_arr['merchantNetPriceFrom'] = $value['merchantNetPrice'];
                $tourgrade_list[$result_k]['Price'] = $this->format_TX_price($format_price_arr);

               // $tourgrade_list[$result_k]['currencyCode'] = $value['currencyCode'];

                $key = array();
                $key['key'][$result_k]['grade_code'] = $value['gradeCode'];
                $key['key'][$result_k]['product_code'] = $available_date[0]['ProductCode'];
                $key['key'][$result_k]['booking_source'] = $this->booking_source;

                $key['key'][$result_k]['booking_question'] = $available_date[0]['booking_question'];
                $key['key'][$result_k]['available_date'] = $available_date[0]['available_date'];

                $key['key'][$result_k]['language_code'] = $value['defaultLanguageCode'];
                $key['key'][$result_k]['langServices'] = $value['langServices'];

                $key['key'][$result_k]['merchantNetPrice'] = $value['merchantNetPrice'];
                $key['key'][$result_k]['retailPrice'] = $value['retailPrice'];
                $key['key'][$result_k]['Cancellation_available']=$available_date[0]['Cancellation_available'];
                $key['key'][$result_k]['HotelPickup'] = $available_date[0]['HotelPickup'];
                $key['key'][$result_k]['ProductName'] = $available_date[0]['ProductName'];
                 $key['key'][$result_k]['ReviewCount'] = $available_date[0]['ReviewCount'];
                 $key['key'][$result_k]['ProductImage'] =$available_date[0]['ProductImage'];
                 $key['key'][$result_k]['StarRating'] = $available_date[0]['StarRating'];
                 $key['key'][$result_k]['Duration'] = $available_date[0]['Duration'];

                 $key['key'][$result_k]['Price'] =  $tourgrade_list[$result_k]['Price'];

                $tourgrade_list[$result_k]['TourUniqueId'] = serialized_data($key['key']);    
                         
            //} 

        }        
        $tourgrade_response['ProductDetails'] = $product_info;
        array_multisort($sort_avail_grade_list, SORT_DESC, $tourgrade_list);
        $tourgrade_response['Trip_list'] = $tourgrade_list;
        $tourgrade_response['Available_date'] = $available_date[0]['available_date'];
        $tourgrade_response['booking_question'] = $available_date[0]['booking_question'];
        $tourgrade_response['Cancellation_available'] = $available_date[0]['Cancellation_available'];
        // debug($tourgrade_response);
        // exit;
        return $tourgrade_response;
    }
    /*Format Tourgrade or trip details*/
    private function format_block_tourgrade_details($block_grade_response,$activity_info_data,$acitivity_data,$search_request,$search_params){
        

        $activity_info_data = $activity_info_data[0];
        $block_grade_response = $block_grade_response['data'];
        $format_block_response = array();
        $format_block_response['ProductName'] = $activity_info_data['ProductName'];

        $format_block_response['ProductCode'] = $activity_info_data['product_code'];
        $format_block_response['ProductImage'] = $activity_info_data['ProductImage'];
        $format_block_response['BookingDate'] =$block_grade_response['itinerary']['itemSummaries'][0]['travelDate'];
        $format_block_response['StarRating'] = $activity_info_data['StarRating'];
        $format_block_response['Duration'] = $activity_info_data['Duration'];
        $format_block_response['Destination'] = $search_params['data']['destination_name'];
        $format_block_response['GradeCode'] = $activity_info_data['grade_code'];
        $format_block_response['GradeDescription'] = $block_grade_response['itinerary']['itemSummaries'][0]['tourGradeDescription'];

        $format_block_response['DeparturePoint'] = 
        $block_grade_response['itinerary']['itemSummaries'][0]['departurePoint'];

        $format_block_response['DeparturePointAddress']=$block_grade_response['itinerary']['itemSummaries'][0]['departurePointAddress'];

        $format_block_response['BookingQuestions'] = $activity_info_data['booking_question'];

        $format_block_response['SupplierName'] = $block_grade_response['itinerary']['itemSummaries'][0]['supplierName'];

         $format_block_response['SupplierPhoneNumber'] = $block_grade_response['itinerary']['itemSummaries'][0]['supplierPhoneNumber'];

        $format_block_response['Cancellation_available'] = $block_grade_response['itinerary']['itemSummaries'][0]['merchantCancellable'];


        //$format_block_response['Free_Cancellation_available'] = $activity_info_data['Cancellation_available'];


        //activity_info_data block trip Price

        //$block_grade_response['itineraryNewPrice']  this is merchant net price
      
        $format_price_arr = array();
        $format_price_arr['price'] = $activity_info_data['retailPrice'];
        $format_price_arr['merchantNetPriceFrom'] = $block_grade_response['itineraryNewPrice'];
        //$format_price_arr['merchantNetPriceFrom'] = $activity_info_data['itineraryNewPrice'];        
        $format_block_response['Price'] = $this->format_TX_price($format_price_arr);

        //Format Cancellation policy 
        $policy = $this->format_Tx_Cancellation_Policy($format_block_response['Cancellation_available'],$format_block_response['BookingDate'],$format_block_response['Price']);
        
        

        //$format_block_response['TM_Cancellation_Charge'] = $this->CI->common_sightseeing->update_fare_markup_cancel_policy($policy,1,0,true,$this->booking_source);

        $format_block_response['TM_Cancellation_Charge']= $policy['policy_details'];
        $format_block_response['TM_LastCancellation_date'] = $policy['free_cancel_date'];

        $format_block_response['HotelPickup'] = $activity_info_data['HotelPickup'];
        //get hotel pickup locations by destination id
       // echo $format_block_response['HotelPickup'];
        $get_pickup_hotel_list =array();
        if($format_block_response['HotelPickup']){
            $destination_id = $search_params['data']['destination_id'];
            $get_pickup_hotel_list = $this->get_hotel_pickup_list($destination_id,$activity_info_data['product_code']);
            if($get_pickup_hotel_list['status']==SUCCESS_STATUS){
                $format_block_response['HotelList'] = $get_pickup_hotel_list['data'];
            }else{
                 $format_block_response['HotelList']= array();
            }
        }else{
             $format_block_response['HotelList'] = array();
        }  
        
        $format_block_response['hotel_pikcup_option'] = 
            array('local'=>'Live locally',
            'notBooked'=>'Not booked the hotel yet',
            'notListed'=>'Hotel not listed'
            );

        $key = array();
        $key['key'][0]['booking_source'] = $activity_info_data['booking_source'];
        $key['key'][0]['product_code'] = $activity_info_data['product_code'];
        $key['key'][0]['grade_code'] = $activity_info_data['grade_code'];
        $key['key'][0]['booking_question'] = $activity_info_data['booking_question'];
        $key['key'][0]['language_code'] = $activity_info_data['language_code'];
        $key['key'][0]['langServices'] = $activity_info_data['langServices'];
        $key['key'][0]['ageBands'] = $search_request['ageBands'];

        $key['key'][0]['HotelPickup']=$activity_info_data['HotelPickup'];
        $key['key'][0]['ProductName'] = $activity_info_data['ProductName'];
         $key['key'][0]['ReviewCount'] = $activity_info_data['ReviewCount'];
         $key['key'][0]['ProductImage'] =$activity_info_data['ProductImage'];
         $key['key'][0]['StarRating'] = $activity_info_data['StarRating'];
         $key['key'][0]['Duration'] = $activity_info_data['Duration'];
         $key['key'][0]['BlockTourDetails'] = $format_block_response;
        $format_block_response['BlockTourId'] = serialized_data($key['key']);        
       # debug($format_block_response);
       # exit;
        return $format_block_response;
    }
    /*Before Booking blockinf tourgrade for particular date*/
    function block_tourgrade($request, $activity_info_data, $activity_data,$search_id){       
       
        $get_safe_search_data = $this->search_data($search_id);
       
        $tourgrade_calculate_price_req = $this->tourgrade_calculate_price_request($request, $activity_info_data,$activity_data);

        if ($tourgrade_calculate_price_req ['status'] == SUCCESS_STATUS) {
            

            $tourgrade_calculate_price_response = $this->process_request($tourgrade_calculate_price_req ['data']['request'], $this->json_header(), $tourgrade_calculate_price_req['data'] ['service_url'], $tourgrade_calculate_price_req['data']['remarks'],'post');
            
            // debug($tourgrade_calculate_price_response);
            // exit;
            $tourgrade_calculate_price_response = json_decode($tourgrade_calculate_price_response, true);


            if (valid_array($tourgrade_calculate_price_response['data']) == true && isset($tourgrade_calculate_price_response['data']) == true) {
                $response ['status'] = SUCCESS_STATUS;
                $response ['data']['BlockTripResult'] =$this->format_block_tourgrade_details($tourgrade_calculate_price_response,$activity_info_data,$activity_data,$request,$get_safe_search_data);
            } else {
                $response ['message'] = 'Not Available';
            }
            
        }
        else{
            $response ['status'] = FAILURE_STATUS;
        }

        return $response;
    } 
    /*Get hotel list for the destination */
    private function get_hotel_pickup_list($destination_id,$product_code){

        $service_url = $this->service_url.'booking/hotels?apiKey='.$this->api_key.'&destId='.$destination_id;        
        //$service_url = $this->service_url.'booking/hotels?apiKey='.$this->api_key.'&productCode='.$product_code;

         $hotel_list_response = $this->process_request("", $this->json_header(),$service_url,'Get Hotel Pickup(Transfer Viator)');
         $hotel_list_response = json_decode($hotel_list_response,true);
         $response = array();
         $response['status'] = FAILURE_STATUS;
         $hotel_list = array();

         


         if(valid_array($hotel_list_response)&&$hotel_list_response['success']==true){
            $response['status'] = SUCCESS_STATUS;
            foreach ($hotel_list_response['data'] as $key => $value) {
               $hotel_list[$key]['hotel_name'] = $value['name'];
               $hotel_list[$key]['hotel_id'] = $value['id'];
               $hotel_list[$key]['address'] = $value['address'];
               $hotel_list[$key]['city'] =$value['city'];
            }
            $pickup_option = array();
            $pickup_option['hotel_id'] = 'local';
            $pickup_option['hotel_name'] ='Live locally';
            $hotel_list []=$pickup_option;
            $pickup_option = array();
            $pickup_option['hotel_id'] = 'notBooked';
            $pickup_option['hotel_name'] ='Not booked the hotel yet';
            $hotel_list []=$pickup_option;
            $pickup_option = array();
            $pickup_option['hotel_id'] = 'notListed';
            $pickup_option['hotel_name'] ='Hotel not listed';
            $hotel_list []=$pickup_option;

            $response['data'] = $hotel_list;
         }  

        return $response;
    }
    /*
     * Block  request
     * 
     */

    function tourgrade_calculate_price_request($search_params, $activity_info_data,$acitivity_data) {
        $response = array();
        $request =array();
        $age_band_data = $search_params['ageBands'];
        $band_id_arr = array();
       
        foreach ($age_band_data as $key => $value) {
            for($i=0;$i<$value['count'];$i++){
                $band_id_arr[]['bandId'] = $value['bandId'];
            }
        }
        $request['currencyCode'] = $this->currency;
        $items_arr = array(
                'travelDate'=>date('Y-m-d',strtotime($search_params['BookingDate'])),
                'productCode'=>$search_params['ProductCode'],
                'tourGradeCode'=>$search_params['GradeCode'],
                'travellers'=>$band_id_arr
            );
        $request['items'] =array($items_arr);
        $response['status'] = SUCCESS_STATUS;
        $response['data']['request'] = json_encode($request);
        $response['data']['service_url']=$this->service_url.'booking/calculateprice?apiKey='.$this->api_key;
        $response['data']['remarks'] = "Block Tourgrade (Transfer Viator)";

        return $response;
    }
  
    function process_request($request, $header, $url, $remarks = '',$method='') {
       
        $insert_id = $this->CI->api_model->store_api_request($url, $request, $remarks);
        $insert_id = intval(@$insert_id['insert_id']);
        // debug($header);exit;
        //error_reporting(E_ALL);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        if($method=="post"){
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$request);
        }

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        // Execute request, store response and HTTP response code
        $response = curl_exec($ch);       
      
        //$response = json_decode ( $response, true );
        //Update the API Response
        $this->CI->api_model->update_api_response($response, $insert_id);
        //echo 'hi'; debug($response); exit;

        $error = curl_getinfo($ch);
        curl_close($ch);
        
        return $response;
    }
   
     /**
     * process soap API request
     *
     * @param string $request
     */
    function form_curl_params($request, $api_key,$url) {
        $data['status'] = SUCCESS_STATUS;
        $data['message'] = '';
        $data['data'] = array();

        $curl_data = array();
        $curl_data['booking_source'] = $this->booking_source;
        $curl_data['request'] = $request;
        $curl_data['url'] = $url;
        $curl_data['header'] = array(
            'api-key:'.trim($api_key),
            'Content-Type:application/json',
            'Accept:application/json'            
        );
        $data['data'] = $curl_data;
        return $data;
    }
    /**
     * check if the search RS is valid or not
     * @param array $search_result
     * search result RS to be validated
     */
    private function valid_search_result($search_result) {

        if (isset($search_result['data']) == true && valid_array($search_result['data']) == true) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Update markup currency for price object of hotel
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

        $response ['status'] = FAILURE_STATUS; // Status Of Operation
        $response ['message'] = ''; // Message to be returned
        $response ['data'] = array (); // Data to be returned
        //exit;//live key
        $book_response = array();
        $search_data = $this->search_data($search_id);
        //debug($search_data);exit;
        $book_service_response = $this->run_book_service($booking_params, $app_reference, $search_id);
        
     
        if($book_service_response['status'] == SUCCESS_STATUS){
            $response ['status'] = SUCCESS_STATUS;
            
            $booking_details = $book_service_response['data']['book_response']['data'];
            $booking_items = $book_service_response['data']['book_response']['data']['itemSummaries'][0];
            $bookingStatus = $book_service_response['data']['book_response']['data']['bookingStatus'];
            $booking_params = $book_service_response['data']['booking_params'];

            $tour_attributes['travellerAgeBands']  = $booking_items['travellerAgeBands'];
            $tour_attributes['voucherURL'] = $booking_details['voucherURL'];
            $tour_attributes['voucherKey'] = $booking_details['voucherKey'];
            $tour_attributes['totalPrice'] = $booking_details['totalPrice'];
            $tour_attributes['supplierName'] = $booking_items['supplierName'];
            $tour_attributes['supplierPhoneNumber'] = $booking_items['supplierPhoneNumber'];

            $tour_attributes['departurePoint']= @$booking_items['departurePoint'];

            $tour_attributes['departurePointAddress'] = @$booking_items['departurePointAddress'];
            
            $tour_attributes['voucherRequirements'] = $booking_items['voucherRequirements'];
            $tour_attributes['Cancellation_available'] = $booking_params['ResultToken']['BlockTourDetails']['Cancellation_available'];
            $tour_attributes['Duration'] = $booking_params['ResultToken']['Duration'];
            $tour_attributes['ProductImage'] =  $booking_params['ResultToken']['ProductImage'];
            
            $tour_attributes['TM_Cancellation_Charge'] = $booking_params['ResultToken']['BlockTourDetails']['TM_Cancellation_Charge'];

            $tour_attributes['TM_LastCancellation_date'] = $booking_params['ResultToken']['BlockTourDetails']['TM_LastCancellation_date'];
            $tour_attributes = json_encode($tour_attributes);

            //checking one more request to get the supplier information
            
            $itinerary_id =$booking_details['itineraryId'];
            $distributorRef = $booking_details['distributorRef'];
            $item_id = $booking_items['itemId'];
            $distributorItemRef = $booking_items['distributorItemRef'];


            if($bookingStatus['confirmed']==true){                
                $booking_status = 'BOOKING_CONFIRMED';
                 $response ['message'] = 'Booking Confirmed'; 

            }elseif($bookingStatus['pending']==true){
                $booking_status = 'BOOKING_HOLD';
                $response ['message'] = 'Booking Hold';
            }
            //$booking_status = 'BOOKING_HOLD';
            //$response ['message'] = 'Booking Hold';

            //$booking_status = 'BOOKING_HOLD';
            $update_tour_data['item_id'] = 'TM-'.$item_id;
            $update_tour_data['viator_item_id'] = $item_id;

            $update_tour_data['itinerary_id'] = $itinerary_id;
            
            $update_tour_data['distributor_ref'] = $distributorRef;
            $update_tour_data['distributorItem_ref'] = $distributorItemRef;

            $total_passenger_count = 0;
            foreach ($booking_items['travellerAgeBands'] as $key => $value) {
                $total_passenger_count +=$value['count'];
            }

            $update_tour_data['total_pax'] = $total_passenger_count;
           
            $update_tour_data['attributes'] = $tour_attributes;
            //$update_hotel_data['']
            $this->CI->custom_db->update_record('viatortransfer_booking_details', $update_tour_data, array('app_reference' => trim($app_reference)));
            
        } else {
            $response ['message'] = $book_service_response['message'];

            $tour_attributes['Booking_request'] = $book_service_response['data']['book_request'];
            $tour_attributes['Booking_Params'] = $book_service_response['data']['booking_params'];
            $tour_attributes = json_encode($tour_attributes);
            $update_tour_data['attributes'] = $tour_attributes;
            //$update_hotel_data['']
            $this->CI->custom_db->update_record('viatortransfer_booking_details', $update_tour_data, array('app_reference' => trim($app_reference)));

            $booking_status = 'BOOKING_FAILED';
        }
        //Update the Booking Status
        $this->update_booking_status($app_reference, $booking_status);
        
        return $response;
    }
    /**
     * Update the transfers booking status
     * @param unknown_type $app_reference
     * @param unknown_type $booking_status
     */
    public function update_booking_status($app_reference, $booking_status)
    {
        $app_reference = trim($app_reference);
        $booking_status = trim($booking_status);
        if(empty($app_reference) == false && empty($booking_status) == false){
            $update_condition = array();
            $update_condition['app_reference'] = $app_reference;
            
            $update_data = array();
            $update_data['status'] = $booking_status;
            
            //update master table status
            $this->CI->custom_db->update_record('viatortransfer_booking_details', $update_data, $update_condition);
           
            //update passenger status
            $this->CI->custom_db->update_record('viatortransfer_booking_pax_details', $update_data, $update_condition);
        }
    }

     /**
     * Book Service API call
     * @param unknown_type $booking_params
     */
    private function run_book_service($booking_params, $app_reference, $search_id)
    {

        $response ['status'] = FAILURE_STATUS; // Status Of Operation
        $response ['message'] = ''; // Message to be returned
        $response ['data'] = array (); // Data to be returned
    
        $book_service_request = $this->book_service_request($booking_params, $search_id);   
        //exit;//booking blocked because of LIVE KEY

        if($book_service_request['status'] == SUCCESS_STATUS){    
           // exit;        
            $book_service_response = $this->process_request ( $book_service_request ['request'],$this->json_header(),$book_service_request ['service_url'], $book_service_request ['remarks'],'post');
           
          
            $book_service_response = json_decode($book_service_response, true);
            
            //debug($book_service_response);exit;
            /*$book_table_response =$this->CI->custom_db->single_table_records('provab_api_response_history','*',array('origin'=>5936));
        
            $book_table_response = json_decode($book_table_response['data'][0]['response'],true);
            $book_service_response = $book_table_response;*/
            
            
            if($book_service_response['data']&&$book_service_response['success']==true){
                    if(isset($book_service_response) == true && valid_array($book_service_response['data']) == true &&($book_service_response['data']['bookingStatus']['confirmed'] == true || $book_service_response['data']['bookingStatus']['pending']==true)){

                        $response ['status'] = SUCCESS_STATUS;
                        $response ['data']['book_response'] = $book_service_response;
                        
                    }else{
                        //echo "error_messafe";
                        $error_message = '';
                        if(isset($book_service_response['errorMessageText'])){
                            $error_message = implode(",",$book_service_response['errorMessageText']);
                        }
                        if(empty($error_message) == true){
                            $error_message = 'Booking Failed';
                        }
                        $response ['message'] = $error_message;
                        
                        //Log Exception
                        $exception_log_message = '';
                        $this->CI->exception_logger->log_exception($app_reference, $this->booking_source.'- (<strong>Book</strong>)', $exception_log_message, $book_service_response);
                    }
            }
             else {
                //echo "error_messafe";
                $error_message = '';
                if(isset($book_service_response['errorMessageText'])){
                    $error_message = implode(",",$book_service_response['errorMessageText']);
                }
                if(empty($error_message) == true){
                    $error_message = 'Booking Failed';
                }
                $response ['message'] = $error_message;
                //Log Exception
                $exception_log_message = '';
                $this->CI->exception_logger->log_exception($app_reference, $this->booking_source.'- (<strong>Book</strong>)', $exception_log_message, $book_service_response);
                //exit;
            }

        } 

        $response['data']['book_request'] = $book_service_request;
        $response['data']['booking_params'] = $booking_params;

        return $response;
    }
    /*Format Booking Request*/
    private function book_service_request($booking_params,$search_id){
       $request = array();
       $response = array();
       $response['status'] = SUCCESS_STATUS;
       $response['data']['demo'] = true;
       if(transferv1::get_credential_type()=='live'){
         $response['data']['demo'] = false;
       }
       $response['data']['currencyCode']= $this->currency;
       $response['data']['partnerDetail']=array(
            'distributorRef'=>'distroRef'.time()
        );
      
        $lead_passenger_details = $booking_params['Passengers'];
        $response['data']['booker'] = array(
                'email'=>$lead_passenger_details[0]['Email'],
                'firstname'=>$lead_passenger_details[0]['FirstName'],
                'surname'=>$lead_passenger_details[0]['LastName']         
        );
        $ite_items_arr  =array();
        $product_details = $booking_params['ProductDetails'];
        $ite_items_arr['partnerItemDetail'] = array(
                'distributorItemRef'=>'distributorItemRef'.$product_details['ProductCode'].time()
        );
        if($product_details['hotelId']){
            $ite_items_arr['hotelId'] =$product_details['hotelId'];
        }else{
            $ite_items_arr['hotelId'] =null;
        }
        if($product_details['pickupPoint']){
            $ite_items_arr['pickupPoint'] =$product_details['pickupPoint'];
        }else{
            $ite_items_arr['pickupPoint'] =null;
        }
        $ite_items_arr['travelDate'] = date('Y-m-d',strtotime($product_details['BookingDate']));
        $ite_items_arr['productCode'] = $product_details['ProductCode'];
        $ite_items_arr['tourGradeCode'] = $product_details['GradeCode'];

        $language_code = $booking_params['ResultToken']['language_code'];
        $lang_service = $booking_params['ResultToken']['langServices'];
        $lang_service_code = '';
        if(valid_array($lang_service)){
            foreach ($lang_service as $key => $value) {
                $lang_service_code = $key;
            }
        }
         $ite_items_arr['languageOptionCode'] =  $lang_service_code;
         $bookingQuestionAnswers =array();
         $booking_question = $booking_params['BookingQuestions'];
         if($booking_question){
            foreach ($booking_question as $key => $value) {
                 $bookingQuestionAnswers[$key]['questionId'] =$value['id'];
                 $bookingQuestionAnswers[$key]['answer'] = $value['answer'];
             }
         }
         
         $ite_items_arr['bookingQuestionAnswers'] = $bookingQuestionAnswers;

         $travellers_info = array();

         if($lead_passenger_details){
            foreach ($lead_passenger_details as $t_key => $t_value) {            
               $travellers_info[$t_key]['bandId'] = $t_value['PaxType'];
               $travellers_info[$t_key]['firstname'] = $t_value['FirstName'];
               $travellers_info[$t_key]['surname'] = $t_value['LastName'];
               if($t_value['LeadPassenger']==true){
                    $travellers_info[$t_key]['leadTraveller'] = true;
               }

            }
         }
         $ite_items_arr['travellers'] = $travellers_info;
         $response['data']['items'] = array($ite_items_arr);
         
         $response['request'] = json_encode($response['data']);
         $response['service_url'] = $this->service_url.'booking/book?apiKey='.$this->api_key;
         $response['remarks'] = "Transfer Booking(Transfer Viator)";
       // exit;
        return $response;
    }
    /**
    * Form the cancellation request
    */
    private function cancel_request($cancel_params){       
        $response = array();
        $request= array();
        $request['itineraryId'] = $cancel_params['itinerary_id'];
        $request['distributorRef'] = $cancel_params['distributor_ref'];
          $response['status'] = SUCCESS_STATUS;
        $cancelItems = array(
            'itemId'=>$cancel_params['item_id'],
            'distributorItemRef'=>$cancel_params['distributorItem_ref'],
            'cancelCode'=>$cancel_params['cancel_code']
            );
        if($cancel_params['cancel_code']==62 || $cancel_params['cancel_code']==66){
            if($cancel_params['cancel_description']!=''){
                $cancelItems['cancelDescription'] = $cancel_params['cancel_description'];
            }else{
                  $response['status'] = FAILURE_STATUS;
            }
        }
      
        $request['cancelItems'] = array($cancelItems);
        
        $response['data']['request'] = json_encode($request);
       
        $response['data']['service_url']= $this->service_url.'merchant/cancellation?apiKey='.$this->api_key;
        $response['data']['remarks']="Transfer CancelBooking(Transfer Viator)";

        return $response;
    }

    /*Cacenllation Request*/
    private function send_cancel_request($request_params)
    {

        $response ['status'] = FAILURE_STATUS; // Status Of Operation
        $response ['message'] = ''; // Message to be returned
        $response ['data'] = array (); // Data to be returned
      
        $send_change_request = $this->cancel_request($request_params);

        if($send_change_request['status'] == SUCCESS_STATUS){           
            //debug($send_change_request);
            $send_change_response = $this->process_request ( $send_change_request['data']['request'],$this->json_header(), $send_change_request['data']['service_url'],$send_change_request['data']['remarks'],'post');             
            //$send_change_response = $this->CI->custom_db->get_static_response (901);
            
            $send_change_response = json_decode($send_change_response, true);             

            if($send_change_response['success']==SUCCESS_STATUS&&$send_change_response['data']['cancelItems'][0]['cancellationResponseStatusCode']=='Confirmed') {

                $response ['status'] = SUCCESS_STATUS;
                $response['data']['status_code'] = $send_change_response['data']['cancelItems'][0]['cancellationResponseStatusCode'];
                $response ['data']['send_cancel_response'] = $send_change_response;
            } else {
                $error_message = '';
                if($send_change_response['data']['cancelItems'][0]['cancellationResponseStatusCode']!='Confirmed'){

                    $error_message = $send_change_response['data']['cancelItems'][0]['cancellationResponseDescription'];

                }
                if(empty($error_message) == true){
                    $error_message = 'Cancellation Failed';
                }
                $response['data']['status_code'] = $send_change_response['data']['cancelItems'][0]['cancellationResponseStatusCode'];

                $response ['message'] = $error_message;
            }
        } else {
            $response ['status'] = FAILURE_STATUS;
            $response['message'] = 'Invalid Cancellation Request, if cancel code contains 62 or 66 CancelDescription is required';
        }

        return $response;
    }
    /**
     *Process Cancel Booking From Admin End
     *Online Cancellation
     */
    public function admin_cancel_booking($request)
    {
       
        $response ['status'] = FAILURE_STATUS; // Status Of Operation
        $response ['message'] = ''; // Message to be returned
        $response ['data'] = array (); // Data to be returned
        $app_reference = trim($request['AppReference']);

        $booking_details = $this->CI->custom_db->single_table_records('viatortransfer_booking_details', '*', array('app_reference' => $app_reference));
            //$booking_details['data'][0]['status'] = 'BOOKING_CONFIRMED';
        if($booking_details['status'] == SUCCESS_STATUS && $booking_details['data'][0]['status'] == 'BOOKING_CONFIRMED'){
             $booking_details = $booking_details['data'][0];
            $request_params = array();
            $request_params['itinerary_id'] = $booking_details['itinerary_id'];
            $request_params['item_id']= $booking_details['viator_item_id'];
            $request_params['distributor_ref'] = $booking_details['distributor_ref'];
            $request_params['distributorItem_ref'] = $booking_details['distributorItem_ref'];
            $request_params['cancel_code'] = $request['CancelCode'];
            $request_params['cancel_description'] = $request['CancelDescription'];

            $booking_attributes= json_decode($booking_details['attributes'],true);

            $send_cancel_request_response = $this->send_cancel_request($request_params);
           

            $total_booking_amount = roundoff_number($booking_details['total_fare']-$booking_details['agent_commission']+$booking_details['agent_tds']+$booking_details['domain_markup']);
           
           
            if($send_cancel_request_response['status'] == SUCCESS_STATUS){
                $cancel_response = $send_cancel_request_response['data']['send_cancel_response'];

                $cancel_response_status = $cancel_response['data']['cancelItems'][0]['cancellationResponseStatusCode'];
                        //NotSet = 0,Pending = 1,InProgress = 2,Processed = 3,Rejected = 4
                $ChangeRequestId = 0;
                
                switch (strtolower($cancel_response_status)) {
                    case 'confirmed':
                        $ChangeRequestId = 3;
                        break;
                    case 'failed':
                        $ChangeRequestId = 2;
                        break;
                    case 'rejected':
                        $ChangeRequestId = 4;
                        break;
                    case 'pending':
                        $ChangeRequestId = 1;
                        break;
                    default:
                        $ChangeRequestId = 0;
                        break;
                }
            
                $response ['status'] = SUCCESS_STATUS;  
                  /** cancellation Charge Start**/
                $get_cancellation_details_db = json_decode($booking_details['attributes'],true);

                $tm_cancel_charge = $get_cancellation_details_db['TM_Cancellation_Charge'];
                
                $tm_last_cancel_date = date('Y-m-d',strtotime($get_cancellation_details_db['TM_LastCancellation_date']));
                $current_date = date('Y-m-d');
                $cancel_charge=0;                
                $tm_cancel_charge = array_reverse($tm_cancel_charge);  
                
                //At the time of cancelling if current date is lessthan traveldate cancel charge is 0,otherwise total amount will be charged

                if($current_date<$tm_last_cancel_date){
                    $cancel_charge = 0;
                }else{
                   $cancel_charge  = round($total_booking_amount);
                }           
                /**End**/
                if($booking_attributes['Cancellation_available']==SUCCESS_STATUS){
                    $cancel_charge = $cancel_charge;
                }else{
                    $cancel_charge = $total_booking_amount;
                }

                if($cancel_charge>0){
                    $ChangeRequestId =2;
                }else{
                    $ChangeRequestId=$ChangeRequestId;
                }
                $get_change_request_status_response['StatusDescription'] = $this->get_cancellation_status_description($ChangeRequestId);
               
               
                $cancellation_details = array();
                $cancellation_details['SSChangeRequestStatusResult'] = $get_change_request_status_response;

                //calculate cancellation charge according to Travelomatix, because we chaning the cancellation date by -1 , -2 days

                $price =abs($total_booking_amount - $cancel_charge);
                $cancellation_details['SSChangeRequestStatusResult']['RefundedAmount'] = $price;
                $cancellation_details['SSChangeRequestStatusResult']['CancellationCharge'] = $cancel_charge;
                $cancellation_details['SSChangeRequestStatusResult']['ChangeRequestId'] = $ChangeRequestId;
                $cancellation_details['SSChangeRequestStatusResult']['ChangeRequestStatus'] = $ChangeRequestId;

                $get_change_request_status_response['ChangeRequestId'] = $ChangeRequestId;
                $get_change_request_status_response['RefundedAmount'] =  $price;
                $get_change_request_status_response['CancellationCharge']= $cancel_charge;
                $get_change_request_status_response['ChangeRequestStatus'] = $get_change_request_status_response['StatusDescription'];

                $response['data']['CancellationDetails'] = $get_change_request_status_response;
                $response['data']['update_cancel_details'] = $cancellation_details;
            }else{
                $response ['status'] = FAILURE_STATUS;
                $response['data']['status_code'] = $send_cancel_request_response['data']['status_code'];
                $response['message'] = $send_cancel_request_response['message'];
            }
        } else {
            $response ['message'] = 'Invalid Request';
        }
      
        return $response;
    }
    /**
     *Process Cancel Booking
     *Online Cancellation
     */
    public function cancel_booking($request)
    {
        $response ['status'] = FAILURE_STATUS; // Status Of Operation
        $response ['message'] = ''; // Message to be returned
        $response ['data'] = array (); // Data to be returned
        $app_reference = trim($request['AppReference']);
        
        
        $booking_details = $this->CI->custom_db->single_table_records('viatortransfer_booking_details', '*', array('app_reference' => $app_reference));
        if($booking_details['status'] == SUCCESS_STATUS && $booking_details['data'][0]['status'] == 'BOOKING_CONFIRMED'){

            $booking_details = $booking_details['data'][0];
            $request_params = array();
            $request_params['itinerary_id'] = $booking_details['itinerary_id'];
            $request_params['item_id']= $booking_details['viator_item_id'];
            $request_params['distributor_ref'] = $booking_details['distributor_ref'];
            $request_params['distributorItem_ref'] = $booking_details['distributorItem_ref'];
            $request_params['cancel_code'] = $request['CancelCode'];
            $request_params['cancel_description'] = $request['CancelDescription'];

            $booking_attributes= json_decode($booking_details['attributes'],true);

            $send_cancel_request_response = $this->send_cancel_request($request_params);

           // $total_booking_amount = round($booking_details['total_fare']+$booking_details['domain_markup']);

            $total_booking_amount = 
            roundoff_number($booking_details['total_fare']-$booking_details['agent_commission']+$booking_details['agent_tds']+$booking_details['domain_markup']);

            if($send_cancel_request_response['status'] == SUCCESS_STATUS){
                    $cancel_response = $send_cancel_request_response['data']['send_cancel_response'];

                    $cancel_response_status = $cancel_response['data']['cancelItems'][0]['cancellationResponseStatusCode'];
                    
                        //NotSet = 0,Pending = 1,InProgress = 2,Processed = 3,Rejected = 4
                $ChangeRequestId = 0;
            
                switch (strtolower($cancel_response_status)) {
                    case 'confirmed':
                        $ChangeRequestId = 3;
                        break;
                    case 'failed':
                        $ChangeRequestId = 2;
                        break;
                    case 'rejected':
                        $ChangeRequestId = 4;
                        break;
                    case 'pending':
                        $ChangeRequestId = 1;
                        break;
                    default:
                        $ChangeRequestId = 0;
                        break;
                }

                $response ['status'] = SUCCESS_STATUS;
                //calculating cancellation charge;
                /** cancellation Charge Start**/
                $get_cancellation_details_db = json_decode($booking_details['attributes'],true);

                $tm_cancel_charge = $get_cancellation_details_db['TM_Cancellation_Charge'];
                
                $tm_last_cancel_date = date('Y-m-d',strtotime($get_cancellation_details_db['TM_LastCancellation_date']));
                $current_date = date('Y-m-d');
                $cancel_charge=0;                
                $tm_cancel_charge = array_reverse($tm_cancel_charge);  
                
                //At the time of cancelling if current date is lessthan traveldate cancel charge is 0,otherwise total amount will be charged

                if($current_date<$tm_last_cancel_date){
                    $cancel_charge = 0;
                }else{
                   $cancel_charge  = round($total_booking_amount);
                }           
                /**End**/
                
                if($booking_attributes['Cancellation_available']==SUCCESS_STATUS){
                    $cancel_charge = $cancel_charge;
                }else{
                    $cancel_charge = $total_booking_amount;
                }
                if($cancel_charge>0){
                    $ChangeRequestId = 2;
                }else{
                    $ChangeRequestId = $ChangeRequestId;
                }
                $get_change_request_status_response['StatusDescription'] = $this->get_cancellation_status_description($ChangeRequestId);
                
               
                $cancellation_details = array();
                $cancellation_details['SSChangeRequestStatusResult'] = $get_change_request_status_response;

                //calculate cancellation charge according to Travelomatix, because we chaning the cancellation date by -1 , -2 days

                
                //$price =abs($total_booking_amount - $api_cancel_charge);
                //$price =abs($total_booking_amount - $cancel_charge);
                $price =abs($total_booking_amount - $cancel_charge);
                $cancellation_details['SSChangeRequestStatusResult']['RefundedAmount'] = $price;
                $cancellation_details['SSChangeRequestStatusResult']['CancellationCharge'] = $cancel_charge;
                $cancellation_details['SSChangeRequestStatusResult']['ChangeRequestId'] = $ChangeRequestId;
                $cancellation_details['SSChangeRequestStatusResult']['ChangeRequestStatus'] = $ChangeRequestId;
                $get_change_request_status_response['ChangeRequestId'] = $ChangeRequestId;
                $get_change_request_status_response['RefundedAmount'] =  $price;
                $get_change_request_status_response['CancellationCharge']= $cancel_charge;
                //Update Cancellation Details
                //debug($cancellation_details);
            //  echo $ChangeRequestId;
                $this->CI->transferv1_model->update_cancellation_details($app_reference, $cancellation_details);
                
                //Process the refund to client
                if($ChangeRequestId == 3){//if refund processed from supplier
                    $get_change_request_status_response['ChangeRequestStatus'] = $get_change_request_status_response['StatusDescription'];
                    $response['data']['CancellationDetails'] = $this->CI->common_transferv1->update_domain_cancellation_refund_details($get_change_request_status_response, $app_reference);
                } else {
                    $get_change_request_status_response['ChangeRequestStatus'] = $get_change_request_status_response['StatusDescription'];

                    $response['data']['CancellationDetails'] = $get_change_request_status_response;
                }

            }else{
                 $response ['status'] = FAILURE_STATUS;
                 $response['data']['status_code'] = $send_cancel_request_response['data']['status_code'];
                 $response['message'] = $send_cancel_request_response['message'];
            }

        }elseif ($booking_details['status'] == SUCCESS_STATUS && $booking_details['data'][0]['status'] == 'BOOKING_CANCELLED') {
                $booking_details = $booking_details['data'][0];
                $app_reference = $booking_details['app_reference'];
                $get_cancellation_details = $this->CI->custom_db->single_table_records('viatortransfer_cancellation_details','*',array('app_reference'=>$app_reference));
               
                // debug($get_cancellation_details);
                // exit;
                if($get_cancellation_details['status']==true){
                    $cancel_details = $get_cancellation_details['data'][0];
                    $response ['status'] = SUCCESS_STATUS;
                    $cancel_details_data['ChangeRequestId'] =  $cancel_details['ChangeRequestId'];
                    $cancel_details_data['ChangeRequestStatus']  = $cancel_details['ChangeRequestStatus'];
                    $cancel_details_data['RefundedAmount']  = $cancel_details['refund_amount'];
                    $cancel_details_data['CancellationCharge']  = $cancel_details['cancellation_charge'];
                    $cancel_details_data['StatusDescription']  = $cancel_details['refund_status'];
                    $response['data']['CancellationDetails'] = $cancel_details_data;
                    $response ['message'] = 'Booking Already Cancelled';
                }else{
                    $response['message'] = 'Invalid Request';
                }
                
                
        } 
        else {
            $response ['message'] = 'Invalid Request';
        }
       
        return $response;
    }
    /**
     * Returns Cancellation status description
     */
    private function get_cancellation_status_description($ChangeRequestStatus)
    {
        $description = '';
        //NotSet = 0,Pending = 1,InProgress = 2,Processed = 3,Rejected = 4
        switch($ChangeRequestStatus){
            case 1: $description = 'Pending';
                break;
            case 2: $description = 'InProgress';
                break;
            case 3: $description = 'Processed';
                break;
            case 4: $description = 'Rejected';
                break;
            default:$description = 'NotSet';
        }
        return $description;
    }
    /*Get Hold Booking Status*/
    public function get_supplier_ref_req_response($booking_info){

        $data['status'] = FAILURE_STATUS;
        $data['data'] = array();       
        $request['itineraryIds'] = $booking_info['itineraryIds'];
        $request['itemIds'] = $booking_info['itemIds'];
        $request['distributorRefs'] = $booking_info['distributorRefs'];
        $request['distributorItemRefs'] = $booking_info['distributorItemRefs'];
        $request['test'] = $booking_info['booking_system'];
        $url =  $this->service_url.'/booking/status?apiKey='.$this->api_key;
        $remarks = 'Transfer Viator(Transfer Get Booking Status)';

        $get_booking_status = $this->process_request (json_encode($request),$this->json_header(),$url,$remarks,'post');
        $generate_supplier_info_arr = json_decode($get_booking_status,true);
      
        if(valid_array($generate_supplier_info_arr['data'])&& $generate_supplier_info_arr['success']==true){

            $data['data'] =$generate_supplier_info_arr['data'];

            $data['status'] = SUCCESS_STATUS;
        }else{
            $data['status'] = FAILURE_STATUS;
        }

        return $data;

    }
    /**
    *Get Hold Booking Status
    */
    public function get_hold_booking_status($request){
        $app_reference = $request['app_reference'];
        $data['status'] = false;
        $data['data'] =array();
        $get_booking_details = $this->CI->custom_db->single_table_records('viatortransfer_booking_details','*',array('status'=>'BOOKING_CONFIRMED','app_reference'=>$app_reference));
    
        if($get_booking_details['status']==1){
            $booking_id= $get_booking_details['data'][0]['item_id'];
            $data['data'] = array('booking_id'=>$booking_id);
            $data['status'] =true;
        }
        
        return $data;
        
    }
}