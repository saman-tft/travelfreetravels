
<?php
/**
 * Library which has generic functions to get data
 *
 * @package    Provab Application
 * @subpackage Travel Portal
 * @author     Gokila <gokila.provab@gmail.com>
 * @version    V1
 */
Class Visa_Model extends CI_Model
{
	/**
	 *verify is the user credentials are valid
	 *
	 *@param string $email    email of the user
	 *@param string @password password of the user
	 *
	 * 
	 *return boolean status of the user credentials
	 */
public function get_countries() {
		$this->db->limit ( 1000 );
		$this->db->order_by ( "country_name", "asc" );
		$qur = $this->db->get ( "country_list" );
		return $qur->result ();
	}
	public function get_country_name($id) {
		$this->db->where ( "country_list",$id );
		$qur = $this->db->get ( "country_list" );
		return $qur->result ();
	}
	public function get_country_id($country_name) {
		$this->db->where ( "country_name",$country_name );
		$qur = $this->db->get ( "country_list" );
		return $qur->result ();
	}
	public function get_currency_list()
	{
		$this->db->select('*');
		//$this->db->where('name',$tours_continent);
		$query = $this->db->get('currency_converter');
		if ( $query->num_rows > 0 ) {
	 		return $query->result_array();
		}else{
			return array();
		}	
	}
	function update_agent_balance($amount)
	{
	    // debug($amount);exit;
	    $current_balance = 0;
	    $cond = array('user_oid' => intval($this->entity_user_id));
	    $details = $this->custom_db->single_table_records('b2b_user_details', 'balance,due_amount,credit_limit', $cond);
	    // debug($details);exit;
	    if ($details['status'] == true) {
	      $details ['data'] [0] ['balance'] = $current_balance = ($details ['data'] [0] ['balance'] + $amount);
	                if ($details ['data'] [0] ['balance'] < 0) {
	          $details ['data'] [0] ['due_amount'] += $details ['data'] [0] ['balance'];
	                    $details ['data'] [0] ['balance'] = 0;
	                }
	                // debug($details);exit;
	      // $details['data'][0]['balance'] = $current_balance = ($details['data'][0]['balance'] + $amount);
	      $this->custom_db->update_record('b2b_user_details', $details['data'][0], $cond);
	      $this->balance_notification($current_balance);
	    }
	    return $current_balance;
	}
	function balance_notification($current_balance)
	{
		$condition = array('agent_fk' => intval($this->entity_user_id));
		$details = $this->custom_db->single_table_records('agent_balance_alert_details', '*', $condition);
		if ($details['status'] == true) {
			$threshold_amount = $details['data'][0]['threshold_amount'];
			$mobile_number = trim($details['data'][0]['mobile_number']);
			$email_id = trim($details['data'][0]['email_id']);
			$enable_sms_notification = $details['data'][0]['enable_sms_notification'];
			$enable_email_notification = $details['data'][0]['enable_email_notification'];
			if($current_balance <= $threshold_amount) {
				//FIXME:Send Notification
				//SMS ALERT
				if($enable_sms_notification == ACTIVE && empty($mobile_number) == false) {
					//Send SMS Alert for Low Balance
				}
				//EMAIL NOTIFICATION
				if($enable_email_notification == ACTIVE && empty($email_id) == false) {
					//Send Email Notification for Low Balance
					$subject = $this->agency_name.'- Low Balance Alert';
					$message = 'Dear '.$this->entity_name.'<br/> <h1>Your Agent Balance is Low.</h1><br/><h2>Agent Balance as on '.date("Y-m-d h:i:sa").'is : '.COURSE_LIST_DEFAULT_CURRENCY_VALUE.' '.$threshold_amount.'/-</h2><h3>Please Recharge Your Account to enjoy UnInterrupted Bookings. :)</h3>';
					$this->load->library('provab_mailer');
					$mail_status = $this->provab_mailer->send_mail($email_id, $subject, $message);
				}
			}
		}
	}
	public function get_markup_for_visa_details($nationality,$min_price){

			// debug($package_details);exit;
			// debug($searchdata);exit;
			$markup_value=0;

			$admin_markup=$this->admin_markup($nationality,$min_price);
			// echo $admin_markup;exit;
			$markup_value +=$admin_markup;
			
			// debug($markup_value);exit;
			return $markup_value;




	}
	public function agent_markup_details($nationality,$min_price){
		$markup_value=0;
		$agent_markup=$this->agent_markup($nationality,$min_price);

		$markup_value +=$agent_markup; 
		
		return $markup_value;
	}
	public function get_gst_markup()
	{
				$query = "select gst from gst_master where module='visa'";
				// debug($query);  
				$specific_data_list = $this->db->query($query)->row_array();
				return $specific_data_list['gst'];
	}
	public function agent_markup($nationality,$min_price)
	{

		$where=""; 
				// $product_id=$package_details['package_id'];
				// echo $product_id;exit;
			  	$where=""; 
				// $product_id=$package_details['package_id'];
				// echo $product_id;exit;
			  	if($nationality)
	 			{ 
	 				$where .=" and ML.country='$nationality'";
	 			}

		
		// echo $city_id;exit;
		$query = 'SELECT
		ML.origin AS markup_origin, ML.value, ML.value_type,  ML.markup_currency AS markup_currency
		FROM domain_list AS DL JOIN markup_list AS ML where ML.value != "" and
		ML.module_type = "b2b_visa" and ML.markup_level = "level_4" and DL.origin=ML.domain_list_fk and ML.type="generic"
		and ML.domain_list_fk != 0 and ML.reference_id=0 and ML.domain_list_fk = '.get_domain_auth_id().' and ML.user_oid='.$this->entity_user_id.' '.$where.' order by DL.created_datetime DESC';
	 	// echo $query;exit;		
	 	$specific_data_list2 = $this->db->query($query)->result_array();
		// echo $this->db->last_query();exit;
		// debug($specific_data_list1);exit;
		if($specific_data_list2){
			switch ($specific_data_list2[0]['value_type']) {
                case 'percentage' :
                    //Just need to calculate percentage of the values
                    $markup_value = (($min_price/ 100) * $specific_data_list2[0]['value']);
                    $original_markup = $specific_data_list2[0]['value'];
                    $markup_type = 'Pecentage';
                    break;
                case 'plus' :
                    $original_markup = $specific_data_list2[0]['value'];
                    //convert value to required currency and then add the value
                    // $temp_conversion = $this->currency_conversion_value(false, $__iv['markup_currency'], $this->to_currency);
                    $markup_value =$specific_data_list2[0]['value'];
                    $markup_type = 'Plus';
                    break;
        	}
        	return $markup_value;
        	
    	}
    	else
    	{
    		$query = 'SELECT
					ML.origin AS markup_origin, ML.value, ML.value_type,  ML.markup_currency AS markup_currency
					FROM domain_list AS DL JOIN markup_list AS ML where ML.value != "" and
					ML.module_type = "b2b_visa" and ML.markup_level = "level_4" and DL.origin=ML.domain_list_fk and ML.domain_list_fk != 0 and ML.reference_id=0 and ML.domain_list_fk = '.get_domain_auth_id().' and ML.user_oid='.$this->entity_user_id.' and ML.country="" order by DL.created_datetime DESC';
					// debug($query);  exit;
					$specific_data_list1 = $this->db->query($query)->row_array();
					// debug($specific_data_list);exit;
					if($specific_data_list1){
						switch ($specific_data_list1['value_type']) {
		                    case 'percentage' :
		                        //Just need to calculate percentage of the values
		                        $markup_value = (($min_price / 100) * $specific_data_list1['value']);
		                        $original_markup = $specific_data_list1['value'];
		                        $markup_type = 'Pecentage';
		                        break;
		                    case 'plus' :
		                        $original_markup = $specific_data_list1['value'];
		                        //convert value to required currency and then add the value
		                        // $temp_conversion = $this->currency_conversion_value(false, $__iv['markup_currency'], $this->to_currency);
		                        $markup_value =$specific_data_list1['value'];
		                        $markup_type = 'Plus';
		                        break;
	                	}
	                	return $markup_value;
					}
					else
					{
						return 0;
					}
    	}
	}
	public function admin_markup($nationality,$min_price){
		
				$where=""; 
				// $product_id=$package_details['package_id'];
				// echo $product_id;exit;
			  	if($nationality)
	 			{ 
	 				$where .=" and ML.country='$nationality'";
	 			}

				$query = 'SELECT
				ML.origin AS markup_origin, ML.value, ML.value_type,  ML.markup_currency AS markup_currency
				FROM domain_list AS DL JOIN markup_list AS ML where ML.value != "" and
				ML.module_type = "b2b_visa" and ML.markup_level = "level_3" and DL.origin=ML.domain_list_fk and ML.domain_list_fk != 0 and ML.reference_id=0 and ML.domain_list_fk = '.get_domain_auth_id().' '.$where.' order by DL.created_datetime DESC';
				// debug($query);  exit;
				$specific_data_list = $this->db->query($query)->result_array();
				// debug($specific_data_list);exit;
				if($specific_data_list){
					switch ($specific_data_list[0]['value_type']) {
	                    case 'percentage' :
	                        //Just need to calculate percentage of the values
	                        $markup_value = (($min_price / 100) * $specific_data_list[0]['value']);
	                        $original_markup = $specific_data_list[0]['value'];
	                        $markup_type = 'Pecentage';
	                        break;
	                    case 'plus' :
	                        $original_markup = $specific_data_list[0]['value'];
	                        //convert value to required currency and then add the value
	                        // $temp_conversion = $this->currency_conversion_value(false, $__iv['markup_currency'], $this->to_currency);
	                        $markup_value =$specific_data_list[0]['value'];
	                        $markup_type = 'Plus';
	                        break;
                	}
                	return $markup_value;
				}
				else
				{
					$query = 'SELECT
					ML.origin AS markup_origin, ML.value, ML.value_type,  ML.markup_currency AS markup_currency
					FROM domain_list AS DL JOIN markup_list AS ML where ML.value != "" and
					ML.module_type = "b2b_visa" and ML.markup_level = "level_3" and DL.origin=ML.domain_list_fk and ML.domain_list_fk != 0 and ML.reference_id=0 and ML.domain_list_fk = '.get_domain_auth_id().' and ML.country="" order by DL.created_datetime DESC';
					// debug($query);  exit;
					$specific_data_list1 = $this->db->query($query)->row_array();
					// debug($specific_data_list);exit;
					if($specific_data_list1){
						switch ($specific_data_list1['value_type']) {
		                    case 'percentage' :
		                        //Just need to calculate percentage of the values
		                        $markup_value = (($min_price / 100) * $specific_data_list1['value']);
		                        $original_markup = $specific_data_list1['value'];
		                        $markup_type = 'Pecentage';
		                        break;
		                    case 'plus' :
		                        $original_markup = $specific_data_list1['value'];
		                        //convert value to required currency and then add the value
		                        // $temp_conversion = $this->currency_conversion_value(false, $__iv['markup_currency'], $this->to_currency);
		                        $markup_value =$specific_data_list1['value'];
		                        $markup_type = 'Plus';
		                        break;
	                	}
	                	return $markup_value;
					}
					else
					{
						return 0;
					}
				}
	}
	function get_all_fare($nationality,$applying_country,$visa_type,$visa_others='',$price_id='')
	{
		$first_date=date("Y-m-d");
		$this->db->select('a.*');
		$this->db->from('country_wise_price as a');
		$this->db->join ( 'visa_nationality_country as b', 'b.origin = a.residency' );
		// $this->db->where('a.residency',$nationality);
		$this->db->where('a.visa_country',$applying_country);
		$this->db->where('find_in_set("'.$nationality.'", b.include_countryCodes)');
		if($visa_type=='Other')
		{
			$this->db->where('a.visa_others',$visa_others);
		}
		else{
			$this->db->where('a.visa_type',$visa_type);
		}
		if($price_id!='')
		{
			$this->db->where('a.id',$price_id);
		}			
		$this->db->where('a.seasonality_from <=',$first_date);
		$this->db->where('a.seasonality_to >=',$first_date);
		$res = $this->db->get();
		// echo $this->db->last_query();exit;
		return $res->result();
	}
	function get_currency_id($currency_id)
	{
		$query = "select * from currency_converter where id='".$currency_id."'" ;
		return $this->db->query($query)->result();
	}
	function get_uid($uid)
	{
		$query = "select * from user where user_id='".$uid."'" ;
		return $this->db->query($query)->result();
	}
	function get_all_details($nationality,$applying_country,$visa_type,$visa_others,$travelling_date,$currency='')
	{
		$this->db->select('a.*');
		$this->db->from('country_wise_price as a');
		$this->db->join ( 'visa_nationality_country as b', 'b.origin = a.residency' );
		// $this->db->where('a.residency',$nationality);
		$this->db->where('a.visa_country',$applying_country);
		$this->db->where('find_in_set("'.$nationality.'", b.include_countryCodes)');
		if($visa_type=='Other')
		{
			$this->db->where('a.visa_others',$visa_others);
		}
		else{
			$this->db->where('a.visa_type',$visa_type);
		}	
		$this->db->where('a.seasonality_from <=',$travelling_date);
		$this->db->where('a.seasonality_to >=',$travelling_date);
		$res = $this->db->get();
		// echo $this->db->last_query();exit;
		return $res->result();
	}
	function get_booking_details($app_reference,$booking_id='')
	{
		$this->db->select('*');
		$this->db->from('visa_data');
		$this->db->where('ref_no',$app_reference);
		if(!empty($booking_id))
		{
			$this->db->where('user_id',$booking_id);
		}
		$res = $this->db->get();
		return $res->result();
	}
	function get_agent_info($user_id)
	{
		$query = 'select U.*,BU.logo,CNT.iso_country_code from user AS U
					      join  b2b_user_details BU on U.user_id = BU.user_oid
				          join  currency_converter CUC on CUC.id = BU.currency_converter_fk
				          join  api_country_list CNT on CNT.origin = U.country_name
						  WHERE  U.user_type='.B2B_USER.' AND U.user_id='.$user_id;
						  // echo $query;exit;
		return $this->db->query($query)->result_array();
			
	}
	function get_currency_name($currency)
	{
		$query = 'select * from country_wise_price where residency='.$currency;
						  // echo $query;exit;
		return $this->db->query($query)->result();
			
	}
	function get_reference_info()
	{
		$this->db->select("visa_ref_id,first_no,second_no,third_no");
		$this->db->from("reference_id_visa");
		$this->db->limit(1);
		$this->db->order_by('id',"DESC");
		$query = $this->db->get();
		$result = $query->result();
		return $result;
	}
	public function get_gst_visa()
	{
		$this->db->select("*");
		$this->db->from("gst_master");
		$this->db->where(module,'visa');
		$query = $this->db->get();
		$result = $query->result();
		return $result;	
	}
	public function get_convenience_fees()
	{
		$this->db->select("*");
		$this->db->from("convenience_fees");
		$this->db->where(module,'visa');
		$query = $this->db->get();
		$result = $query->result();
		return $result;	
	}
	function restriction_check($id){
	   $this->db->select("restricted_countries,restriction_desc");
	   $this->db->from("country_restriction_details");
	   $this->db->where('travelling_country',$id);
	   $this->db->where('status',1);
	   $this->db->GROUP_BY('travelling_country');
	   $res = $this->db->get()->result_array(); 
	   return $res;
	}
	 function get_monthly_booking_summary($condition=array())
    {
        //Balu A
       // $condition = $this->custom_db->get_custom_condition($condition);
        $query = 'select count(BD.ref_no) AS total_booking, 
                sum(BD.total_fare+BD.agent_markup) as monthly_payment, sum(BD.agent_markup) as monthly_earning, 
                MONTH(BD.created) as month_number 
                from visa_data AS BD
                where (YEAR(BD.created) BETWEEN '.date('Y').' AND '.date('Y', strtotime('+1 year')).') AND BD.created_user_id = '.$GLOBALS['CI']->entity_user_id.'
                GROUP BY YEAR(BD.created), 
                MONTH(BD.created)';
               // echo  $query; exit;;
        return $this->db->query($query)->result_array();
    }
}