<?php

/**
 * 
 * @package    Provab Application
 * @subpackage Car Model
 * @author     Elavarasi 
 * @version    V1
 */
Class Transferv1_Model extends CI_Model {
     private $master_search_data;


      /**
     * Save Seaech Data
     * Enter description here ...
     * @param array $request
     */
    function save_search_data($request) {
        $data['status'] = SUCCESS_STATUS;
        $cache_key = $this->redis_server->generate_cache_key();
        //Checking is domest flight
        $search_history_data = array();
        $search_history_data['domain_origin'] = get_domain_auth_id();
        $search_history_data['cache_key'] = $cache_key;
        $search_history_data['search_type'] = META_SIGHTSEEING_COURSE;
        $search_history_data['search_data'] = json_encode($request);
        $search_history_data['created_datetime'] = db_current_datetime();
        $insert_data = $this->custom_db->insert_record('search_history', $search_history_data);
        if ($insert_data['status'] == QUERY_SUCCESS) {
            $data['cache_key'] = $cache_key;
            $data['search_id'] = $insert_data['insert_id'];
        } else {
            $data['status'] = FAILURE_STATUS;
        }
        return $data;
    }

    /**
     * get search data without doing any validation
     * @param $search_id
     */
    function get_search_data($search_id) {

        if (empty($this->master_search_data)) {
            $search_data = $this->custom_db->single_table_records('search_history', '*', array('search_type' => META_SIGHTSEEING_COURSE, 'origin' => $search_id));
            if ($search_data['status'] == true) {
                $this->master_search_data = $search_data['data'][0];
            } else {
                return false;
            }
        }
        return $this->master_search_data;
    }


     /**
     * get search data and validate it
     */
    function get_safe_search_data($search_id) {

        $search_data = $this->get_search_data($search_id);

        $success = true;
        $clean_search = array();

        if ($search_data != false) {
            //validate
            $temp_search_data = json_decode($search_data['search_data'], true);
            $clean_search['destination_name'] = $temp_search_data['destination_name'];
            $clean_search['destination_id'] = $temp_search_data['destination_id'];
            $clean_search['start_date'] =$temp_search_data['start_date'];
            $clean_search['end_date'] = $temp_search_data['end_date'];
            $clean_search['cat_id'] = $temp_search_data['cat_id'];
            $clean_search['sub_cat_id'] = $temp_search_data['sub_cat_id'];
            $clean_search['sort_order'] = $temp_search_data['sort_order'];
            $clean_search['text'] = $temp_search_data['text'];
        }
    
        return array('status' => $success, 'data' => $clean_search);
    }
    /**
    *Save Search histroy
    */
    function save_search_history_data($search_request){
      $data['status'] = SUCCESS_STATUS;
      $cache_key = $this->redis_server->generate_cache_key();

      $destination_details =$this->custom_db->single_table_records('api_sightseeing_destination_list','destination_name,destination_id',array('origin'=>$search_request['city_id']),0,1);

      if($destination_details['status']==SUCCESS_STATUS){
          $request['destination_name'] = $destination_details['data'][0]['destination_name'];
          $request['destination_id'] = $destination_details['data'][0]['destination_id'];
      }
      if($search_request['start_date']){
           $request['start_date'] = date('Y-m-d',strtotime($search_request['start_date']));
      }else{
         $request['start_date'] = '';
      }
      if($search_request['end_date']){
          $request['end_date'] = date('Y-m-d',strtotime($search_request['end_date']));
      }else{
         $request['end_date'] ='';
      }
      
      $request['cat_id'] = $search_request['cat_id'];
      $request['sub_cat_id'] = $search_request['sub_cat_id'];
      $request['sort_order'] = $search_request['sort_order'];
      $request['text'] = $search_request['text'];
      
      $search_history_data = array();
      $search_history_data['domain_origin'] =   get_domain_auth_id();
      $search_history_data['cache_key'] =     $cache_key;
      $search_history_data['search_type'] =     META_SIGHTSEEING_COURSE;
      $search_history_data['search_data'] =     json_encode($request);
      $search_history_data['created_datetime'] =  db_current_datetime();
      
      $insert_data = $this->custom_db->insert_record('search_history', $search_history_data);
      if($insert_data['status'] == QUERY_SUCCESS){
        $data['cache_key'] = $cache_key;
        $data['search_id'] = $insert_data['insert_id'];
      } else {
        $data['status'] = FAILURE_STATUS;
      }
      return $data;

    }

    /**
   *
   * @param number $domain_origin
   * @param string $status
   * @param string $app_reference
   * @param string $booking_source
   * @param string $booking_id
   * @param string $booking_reference
   * @param string $confirmation_reference
   * @param number $total_fare
   * @param number $domain_markup
   * @param number $level_one_markup
   * @param string $currency
   * @param string $hotel_name
   * @param number $star_rating
   * @param string $hotel_code
   * @param number $phone_number
   * @param string $alternate_number
   * @param string $email
   * @param string $payment_mode
   * @param string $attributes
   * @param number $created_by_id
   */
  function save_booking_details($domain_origin, $status, $app_reference, $booking_source, $booking_id, $booking_reference, $confirmation_reference,
  $total_fare, $domain_markup, $level_one_markup, $currency, $product_name, $star_rating, $product_code, $phone_number, $alternate_number, $email,
  $travel_date,$grade_code,$image,$destnation_name,$grade_desc,$payment_mode, $attributes, $created_by_id, $currency_conversion_rate=1, $sightseen_version=VIATOR_TRANSFER_VERSION_1,$gst=0,$admin_markup_gst=0,$admin_commission=0,$agent_commission=0,$admin_tds=0,$agent_tds=0,$book_total_net_fare)
  {
    $data['domain_origin'] = $domain_origin;
    $data['status'] = $status;
    $data['app_reference'] = $app_reference;
    $data['booking_source'] = $booking_source;
    $data['item_id'] = $booking_id;
    $data['itinerary_id'] = $booking_reference;
    $data['distributor_ref'] = $confirmation_reference;
    $data['distributorItem_ref'] = $confirmation_reference;
    $data['total_fare'] = $total_fare;
    $data['domain_markup'] = $domain_markup;
    $data['level_one_markup'] = $level_one_markup;
    $data['currency'] = $currency;
    $data['product_name'] = $product_name;
    $data['star_rating'] = $star_rating;
    $data['product_code'] = $product_code;
    $data['phone_number'] = $phone_number;
    $data['alternate_number'] = $alternate_number;
    $data['email'] = $email;
    $data['travel_date'] = $travel_date;
    $data['tour_grade_code']=$grade_code;
    $data['tour_description'] = $grade_desc;
    $data['product_image'] = $image;
    $data['destination_name'] =$destnation_name;   
    $data['payment_mode'] = $payment_mode;
    $data['attributes'] = $attributes;
    $data['created_by_id'] = $created_by_id;
    $data['created_datetime'] = date('Y-m-d H:i:s');
    $data['currency_conversion_rate'] = $currency_conversion_rate;
    $data['version'] = $sightseen_version;
    /*store gst Viator*/
    $data['domain_gst'] = $gst;
    //$data['ss_markup_price'] = $ss_markup_price;
    $data['admin_markup_gst'] = $admin_markup_gst;
    /*end*/
    /*Update Commission details start*/
    $data['admin_commission'] = $admin_commission;
    $data['agent_commission'] = $agent_commission;
    $data['admin_tds'] =$admin_tds;
    $data['agent_tds'] = $agent_tds;
    $data['net_fare'] = $book_total_net_fare;
    /*End*/

    $status = $this->custom_db->insert_record('viatortransfer_booking_details', $data);
    return $status;
  }

 

  /**
   *
   * @param $app_reference
   * @param $title
   * @param $first_name
   * @param $middle_name
   * @param $last_name
   * @param $phone
   * @param $email
   * @param $pax_type
   * @param $date_of_birth
   * @param $passenger_nationality
   * @param $passport_number
   * @param $passport_issuing_country
   * @param $passport_expiry_date
   * @param $status
   * @param $attributes
   */
  function save_booking_pax_details($title,$app_reference, $first_name, $middle_name, $last_name, $phone, $email, $pax_type,$status)
  {
    
    $data['app_reference'] = $app_reference;
    $data['title'] = $title;
    $data['first_name'] = $first_name;
    $data['middle_name'] = $middle_name;
    $data['last_name'] = $last_name;
    $data['phone'] = $phone;
    $data['email'] = $email;
    $data['pax_type'] = $pax_type;   
    $data['status'] = $status;       
    $status = $this->custom_db->insert_record('viatortransfer_booking_pax_details', $data);
    return $status;
  }
  /**
   * Elavarasi
   * Update Cancellation details and Status
   * @param $AppReference
   * @param $cancellation_details
   */
  public function update_cancellation_details($AppReference, $cancellation_details)
  {
    $AppReference = trim($AppReference);
    $booking_status = 'BOOKING_CANCELLED';
    //1. Add Cancellation details
    $this->update_cancellation_refund_details($AppReference, $cancellation_details);
    //2. Update Master Booking Status
    $this->custom_db->update_record('viatortransfer_booking_details', array('status' => $booking_status), array('app_reference' => $AppReference));//later
    //3.Update Pax Status
    $this->custom_db->update_record('viatortransfer_booking_pax_details', array('status' => $booking_status), array('app_reference' => $AppReference));//later
  }
  /**
   * Add Cancellation details
   * @param unknown_type $AppReference
   * @param unknown_type $cancellation_details
   */
  private function update_cancellation_refund_details($AppReference, $cancellation_details)
  {
    $cancellation_details = $cancellation_details['SSChangeRequestStatusResult'];
    $ss_cancellation_details = array();
    $ss_cancellation_details['app_reference'] =        $AppReference;
    $ss_cancellation_details['ChangeRequestId'] =      $cancellation_details['ChangeRequestId'];
    $ss_cancellation_details['ChangeRequestStatus'] =    $cancellation_details['ChangeRequestStatus'];
    $ss_cancellation_details['status_description'] =     $cancellation_details['StatusDescription'];
    $ss_cancellation_details['API_RefundedAmount'] =     $cancellation_details['RefundedAmount'];
    $ss_cancellation_details['API_CancellationCharge'] =   $cancellation_details['CancellationCharge'];
    if($cancellation_details['ChangeRequestStatus'] == 3){
      $ss_cancellation_details['cancellation_processed_on'] =  date('Y-m-d H:i:s');
      $attributes = array();
      $attributes['CreditNoteNo'] =                 @$cancellation_details['CreditNoteNo'];
      $attributes['CreditNoteCreatedOn'] =            @$cancellation_details['CreditNoteCreatedOn'];
      $ss_cancellation_details['attributes'] = json_encode($attributes);
    }
    $cancel_details_exists = $this->custom_db->single_table_records('viatortransfer_cancellation_details', '*', array('app_reference' => $AppReference));
    if($cancel_details_exists['status'] == true) {
      //Update the Data
      unset($ss_cancellation_details['app_reference']);
      $this->custom_db->update_record('viatortransfer_cancellation_details', $ss_cancellation_details, array('app_reference' => $AppReference));
    } else {
      //Insert Data
      $ss_cancellation_details['created_by_id'] =        (int)@$this->entity_user_id;
      $ss_cancellation_details['created_datetime'] =       date('Y-m-d H:i:s');
      $data['cancellation_requested_on'] = date('Y-m-d H:i:s');
      $this->custom_db->insert_record('viatortransfer_cancellation_details',$ss_cancellation_details);
    }
  }
  /**
   * Update the Refund details
   * @param unknown_type $app_reference
   * @param unknown_type $refund_status
   * @param unknown_type $refund_amount
   * @param unknown_type $currency
   * @param unknown_type $currency_conversion_rate
   */
  function update_refund_details($app_reference, $refund_status, $refund_amount,$cancellation_charge, $currency, $currency_conversion_rate)
  {
    $refund_details = array();
    $refund_details['refund_amount'] =        floatval($refund_amount);
    $refund_details['cancellation_charge'] =    floatval($cancellation_charge);
    $refund_details['refund_status'] =        $refund_status;
    $refund_details['refund_payment_mode'] =    'online';
    $refund_details['currency'] =           $currency;
    $refund_details['currency_conversion_rate'] =   $currency_conversion_rate;
    $refund_details['refund_date'] =        date('Y-m-d H:i:s');
    $this->custom_db->update_record('viatortransfer_cancellation_details', $refund_details, array('app_reference'=> $app_reference));
  }
  /**
   * Elavarasi
   * @param $id
   */
  function viatortransfer_commission_details($id){
    $cols = 'DL.domain_name,BTCD.value, BTCD.api_value,BTCD.value_type';
    $query = 'select '.$cols.' from domain_list DL
          left join b2b_viator_transfer_commission_details as BTCD on DL.origin = BTCD.domain_list_fk 
        where DL.origin ='.intval($id);
    return $this->db->query($query)->result_array();
  }
  /**
   * Returns Sightseeing Cancellation details
   * @param unknown_type $app_reference
   * @param unknown_type $ChangeRequestId
   */
  function get_sightseeing_cancellation_details($app_reference, $ChangeRequestId, $domain_id)
  {
    $query = 'select TCD.* from viatortransfer_booking_details TB
          join viatortransfer_cancellation_details TCD on TCD.app_reference=TB.app_reference
          where TB.app_reference='.$this->db->escape($app_reference).' and TB.domain_origin='.intval($domain_id).' and TCD.ChangeRequestId='.$this->db->escape($ChangeRequestId);
    $details = $this->db->query($query)->result_array();
    return $details;
  }
}
