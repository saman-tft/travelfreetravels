<?php

//error_reporting(E_ALL);

class Package_Model extends CI_Model {

	public function __construct(){

		parent::__construct();

	}





	function b2c_package_report($condition=array(), $count=false, $offset=0, $limit=100000000000)

	{

		$condition = $this->custom_db->get_custom_condition($condition);



		/*if(isset($condition) == true)

		{

			$offset = 0;

		}else{

			$offset = $offset;

		}*/



		//BT, CD, ID

		if ($count) {

			/*$query = 'select count(distinct(BD.app_reference)) as total_records

					from package_booking_details BD

					join package_booking_itinerary_details AS HBID on BD.app_reference=HBID.app_reference

					join payment_option_list AS POL on BD.payment_mode=POL.payment_category_code

					left join user as U on BD.created_by_id = U.user_id 

					where (U.user_type='.B2C_USER.' OR BD.created_by_id = 0) AND BD.domain_origin='.get_domain_auth_id().' '.$condition.'';*/

			$query = 'select count(distinct(BD.app_reference)) as total_records

					from package_booking_details BD

					join package_booking_itinerary_details AS HBID on BD.app_reference=HBID.app_reference

					left join user as U on BD.created_by_id = U.user_id 

					where (U.user_type='.B2C_USER.' OR BD.created_by_id = 0) AND BD.domain_origin='.get_domain_auth_id().' '.$condition.'';

			$data = $this->db->query($query)->row_array();

			return $data['total_records'];

		} else {

			$this->load->library('booking_data_formatter');

			$response['status'] = SUCCESS_STATUS;

			$response['data'] = array();

			$booking_itinerary_details	= array();

			$booking_customer_details	= array();

			$cancellation_details = array();

			

			$bd_query = 'select BD.* ,U.user_name,U.first_name,U.last_name from package_booking_details AS BD

					     left join user U on BD.created_by_id =U.user_id 					     

						 WHERE  (U.user_type='.B2C_USER.' OR BD.created_by_id = 0) AND BD.domain_origin='.get_domain_auth_id().''.$condition.'						 

						 order by BD.created_datetime desc, BD.origin desc limit '.$offset.', '.$limit.'';



			$booking_details = $this->db->query($bd_query)->result_array();

			$app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);

			if(empty($app_reference_ids) == false) {

				$id_query = 'select * from package_booking_itinerary_details AS ID 

							WHERE ID.app_reference IN ('.$app_reference_ids.')';

				$cd_query = 'select * from package_booking_pax_details AS CD 

							WHERE CD.app_reference IN ('.$app_reference_ids.')';

				//$cancellation_details_query = 'select * from hotel_cancellation_details AS HCD 

				//			WHERE HCD.app_reference IN ('.$app_reference_ids.')';

				//$payment_details_query = 'select * from  payment_gateway_details AS PD

				//			WHERE PD.app_reference IN ('.$app_reference_ids.')';



				$booking_itinerary_details	= $this->db->query($id_query)->result_array();

				$booking_customer_details	= $this->db->query($cd_query)->result_array();

				//$cancellation_details	= $this->db->query($cancellation_details_query)->result_array();

				//$payment_details = $this->db->query($payment_details_query)->result_array();

			}

			$response['data']['booking_details']			= $booking_details;

			$response['data']['booking_itinerary_details']	= $booking_itinerary_details;

			$response['data']['booking_customer_details']	= $booking_customer_details;

			//$response['data']['cancellation_details']	= $cancellation_details;

			//$response['data']['payment_details']	= $payment_details;

		

			return $response;

		}

	}

	function get_booking_detailsold($app_reference, $booking_source, $booking_status='')
	{
		$response['status'] = FAILURE_STATUS;
		$response['data'] = array();
		$bd_query = 'select * from package_booking_details AS BD WHERE BD.app_reference like '.$this->db->escape($app_reference);
		if (empty($booking_source) == false) {
			$bd_query .= '	AND BD.booking_source = '.$this->db->escape($booking_source);
		}
		if (empty($booking_status) == false) {
			$bd_query .= ' AND BD.status = '.$this->db->escape($booking_status);
		}
		$id_query = 'select * from package_booking_itinerary_details AS ID WHERE ID.app_reference='.$this->db->escape($app_reference);
		$cd_query = 'select * from package_booking_pax_details AS CD WHERE CD.app_reference='.$this->db->escape($app_reference);
	//	echo $cd_query;die;
	//	$cancellation_details_query = 'select HCD.* from hotel_cancellation_details AS HCD WHERE HCD.app_reference='.$this->db->escape($app_reference);
		$response['data']['booking_details']			= $this->db->query($bd_query)->result_array();
		$response['data']['booking_itinerary_details']	= $this->db->query($id_query)->result_array();
		$response['data']['booking_customer_details']	= $this->db->query($cd_query)->result_array();
	//	$response['data']['cancellation_details']	= $this->db->query($cancellation_details_query)->result_array();
		if (valid_array($response['data']['booking_details']) == true and valid_array($response['data']['booking_itinerary_details']) == true and valid_array($response['data']['booking_customer_details']) == true) {
			$response['status'] = SUCCESS_STATUS;
		}
		return $response;
	}

	function b2c_dynamic_package_report($condition=array(), $count=false, $offset=0, $limit=100000000000)

	{ 

		$condition = $this->custom_db->get_custom_condition($condition);

		//BT, CD, ID

		if ($count) {		

		  /*$query = 'select count(distinct(BD.universal_reference_number)) as total_records

					from universal_reference_number BD

					left join user as U on BD.user_id = U.user_id  '.$condition.'';*/



	     $query = "select count(distinct(UR.universal_reference_number)) as total_records

					from universal_reference_number UR

					WHERE UR. universal_reference_number!='' ".$condition.'';

					//exit;

			$data = $this->db->query($query)->row_array();

			//debug($data);exit;

			return $data['total_records'];

		} else {

			$this->load->library('booking_data_formatter');

			$response['status'] = SUCCESS_STATUS;

			$response['data'] = array();

			$booking_itinerary_details	= array();

			$booking_customer_details	= array();

			$cancellation_details = array();

			

		 /*$bd_query = 'select UR.* ,U.user_name,U.first_name,U.last_name,U.email,U.phone,PG.amount,PG.currency,PB.search_data from universal_reference_number AS UR

					     left join user U on UR.user_id =U.user_id

					     LEFT JOIN payment_gateway_details as PG ON PG.app_reference=UR.universal_reference_number 

					     LEFT JOIN package_bundle_search_history As PB ON PB.origin=UR.bundle_search_id '.$condition.'						 

						 group by UR.universal_reference_number order by UR.create_date desc, UR.id desc limit '.$offset.', '.$limit.'';*/



		 $bd_query = "select UR.* ,U.user_name,U.first_name,U.last_name,U.email,U.phone,PG.amount,PG.currency,PB.search_data  from universal_reference_number AS UR left join user U on UR.user_id =U.user_id LEFT JOIN package_bundle_search_history As PB ON PB.origin=UR.bundle_search_id LEFT JOIN payment_gateway_details as PG ON PG.app_reference=UR.universal_reference_number where  UR. universal_reference_number!=''".$condition. "group by UR.universal_reference_number order by UR.create_date desc, UR.id desc limit ".$offset.", ".$limit.'';				 		 



						 //create_date



			$booking_details = $this->db->query($bd_query)->result_array();



			//debug($booking_details);exit;





			foreach ($booking_details as $key => $value) {

				$bundle_search_history = json_decode($value['search_data']);

	

	            $booking_details[$key]['day_count'] = array_sum($bundle_search_history->destination_city_day);

      

	            $booking_details[$key]['country'] = $bundle_search_history->destination_country_name;

	            $booking_details[$key]['departure_city'] = $bundle_search_history->start_city_name;

	            $booking_details[$key]['destination_cities'] = implode(',', $bundle_search_history->destination_city_name);

			}



			//debug($booking_details);exit;

			$response['data']['booking_details']			= $booking_details;



			//debug($response);exit;

		

			return $response;

		}

	}



		function get_booking_details($app_reference, $booking_source, $booking_status='')

	{

		$response['status'] = FAILURE_STATUS;

		$response['data'] = array();

		$bd_query = 'select * from package_booking_details AS BD WHERE BD.app_reference like '.$this->db->escape($app_reference);

		/*if (empty($booking_source) == false) {

			$bd_query .= '	AND BD.booking_source = '.$this->db->escape($booking_source);

		}

		if (empty($booking_status) == false) {

			$bd_query .= ' AND BD.status = '.$this->db->escape($booking_status);

		}*/

		// $id_query = 'select * from  package_booking_itinerary_details AS ID WHERE ID.app_reference='.$this->db->escape($app_reference);
		$id_query = 'select * from  package_booking_transaction_details AS ID WHERE ID.app_reference='.$this->db->escape($app_reference);

		

		// $cd_query = 'select * from package_booking_pax_details AS CD WHERE CD.app_reference='.$this->db->escape($app_reference);
		$cd_query = 'select * from package_booking_passenger_details AS CD WHERE CD.app_reference='.$this->db->escape($app_reference);

		//$cancellation_details_query = 'select HCD.* from hotel_cancellation_details AS HCD WHERE HCD.app_reference='.$this->db->escape($app_reference);

		$response['data']['booking_details']			= $this->db->query($bd_query)->result_array();
         
		$response['data']['booking_itinerary_details']	= $this->db->query($id_query)->result_array();
		
			$book_id = $response['data']['booking_itinerary_details'][0]['book_id'];
       
		$pack_details = "SELECT * FROM `package` WHERE package_id ='$book_id'";
        $cancel_pack_details = "SELECT * FROM `package_cancellation` WHERE package_id ='$book_id'";
        $price_pack_details = "SELECT * FROM `package_pricing_policy` WHERE package_id ='$book_id'";
		   $response['cancellation_details']  = $this->db->query($cancel_pack_details)->result_array();
			$response['package_details']  = $this->db->query($pack_details)->result_array();
			$response['price_info']  = $this->db->query($price_pack_details)->result_array();

		$response['data']['booking_customer_details']	= $this->db->query($cd_query)->result_array();

		$response['data']['cancellation_details']	= '';//$this->db->query($cancellation_details_query)->result_array();

		if (valid_array($response['data']['booking_details']) == true and valid_array($response['data']['booking_itinerary_details']) == true and valid_array($response['data']['booking_customer_details']) == true) {

			$response['status'] = SUCCESS_STATUS;

		}

		return $response;

	}
function get_activity_booking_details($app_reference, $booking_source='', $booking_status='')
	{
		// echo "string";exit();
		$pass_query = 'select * from package_booking_passenger_details AS ID WHERE ID.app_reference='.$this->db->escape($app_reference).' order by origin asc';

		$tran_query = 'select * from package_booking_transaction_details AS ID WHERE ID.app_reference='.$this->db->escape($app_reference).' order by origin asc';
		// debug($tran_query);exit;

		$book_query = 'select * from package_booking_details AS ID WHERE ID.app_reference='.$this->db->escape($app_reference).' order by origin asc';

		$response['passenger_details']  = $this->db->query($pass_query)->result_array();

		$response['transaction_details']  = $this->db->query($tran_query)->result_array();
         // debug($response['transaction_details']);exit();
		$book_id = $response['transaction_details'][0]['book_id'];
        //debug($book_id);exit();

		$pack_details = "SELECT * FROM `package` WHERE package_id ='$book_id'";
        $cancel_pack_details = "SELECT * FROM `package_cancellation` WHERE package_id ='$book_id'";
        $price_pack_details = "SELECT * FROM `package_pricing_policy` WHERE package_id ='$book_id'";
		$response['package_details']  = $this->db->query($pack_details)->result_array();

		$response['booking_details']  = $this->db->query($book_query)->result_array();
		
		$response['cancellation_details']  = $this->db->query($cancel_pack_details)->result_array();
		
			$response['price_info']  = $this->db->query($price_pack_details)->result_array();

        $response['status'] = $response['transaction_details'][0]['status'];
        
        return $response;
	}
	function get_supplier_details($id)
	{
		//BT, CD, ID
		$query = 'select user.user_id,user.first_name,user.last_name,user.email,user.phone from package  inner join user on package.supplier_id=user.user_id where package.package_id='.$id;
	
		return $this->db->query($query)->result_array();
	}
		public function update_pack_details($AppReference, $id)
	{
		$AppReference = trim($AppReference);
		$this->custom_db->update_record('package_booking_details', array('supplier_id' => $id), array('app_reference' => $AppReference));//later
	
	}
	function b2c_holiday_report($condition=array(), $count=false, $offset=0, $limit=100000000000)
	{


		$condition = $this->custom_db->get_custom_condition($condition);
		// debug($condition);exit();
		//$b2c_condition_array = array('U.user_type', '=', B2C_USER, ' OR ', 'BD.created_by_id', '=', 0);
		
		//BT, CD, ID


		 
		 // debug($condition);exit();

		if(isset($condition) == true)
		{
			$offset = 0;
		}else{
			
			$offset = $offset;
		}

			// debug($count);exit();
		if ($count) {
			
			$query = 'select count(distinct(BD.app_reference)) AS total_records from package_booking_details BD
					 left join package P on P.package_id = BD.package_type
					left join user U on U.user_id = BD.created_by_id
					left join user_type UT on UT.origin = U.user_type
					join package_booking_transaction_details as BT on BD.app_reference = BT.app_reference	
					where BD.domain_origin='.get_domain_auth_id().''.$condition;
		//	echo debug($query);exit;
			
			$data = $this->db->query($query)->row_array();
			// debug($data);exit();
			// echo $this->db->last_query();exit();
			return $data['total_records'];

		} else {
			$this->load->library('booking_data_formatter');
			$response['status'] = SUCCESS_STATUS;
			$response['data'] = array();
			$booking_itinerary_details	= array();
			$booking_customer_details	= array();
			$booking_transaction_details = array();
			$cancellation_details = array();
			$payment_details = array();
			//Booking Details
			$bd_query = 'select P.package_name,P.module_type,BD.*,U.user_name,U.first_name,U.last_name from package_booking_details AS BD
					     left join package P on P.package_id = BD.package_type
					     left join user U on U.user_id = BD.created_by_id
					     left join user_type UT on UT.origin = U.user_type
					     join package_booking_transaction_details as BT on BD.app_reference = BT.app_reference		
						 WHERE BD.domain_origin='.get_domain_auth_id().' '.$condition.'
						 order by BD.created_datetime desc, BD.origin desc limit '.$offset.', '.$limit;		

					
						 
			$booking_details	= $this->db->query($bd_query)->result_array();
			// debug($booking_details);		exit;
			$app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
			if(empty($app_reference_ids) == false) {
				//Itinerary Details
				$id_query = 'select * from `package_booking_passenger_details`  AS ID
							WHERE ID.app_reference IN ('.$app_reference_ids.')';
				//Transaction Details
				$td_query = 'select * from package_booking_transaction_details AS TD
							WHERE TD.app_reference IN ('.$app_reference_ids.')';
				//Customer and Ticket Details
				// $cd_query = 'select CD.*,FPTI.TicketId,FPTI.TicketNumber,FPTI.IssueDate,FPTI.Fare,FPTI.SegmentAdditionalInfo
				// 			from flight_booking_passenger_details AS CD
				// 			left join flight_passenger_ticket_info FPTI on CD.origin=FPTI.passenger_fk
				// 			WHERE CD.flight_booking_transaction_details_fk IN
				// 			(select TD.origin from flight_booking_transaction_details AS TD
				// 			WHERE TD.app_reference IN ('.$app_reference_ids.'))';
				//Cancellation Details
				// $cancellation_details_query = 'select FCD.*
				// 		from flight_booking_passenger_details AS CD
				// 		left join flight_cancellation_details AS FCD ON FCD.passenger_fk=CD.origin
				// 		WHERE CD.flight_booking_transaction_details_fk IN
				// 		(select TD.origin from flight_booking_transaction_details AS TD
				// 		WHERE TD.app_reference IN ('.$app_reference_ids.'))';
				//$payment_details_query = '';
				$booking_itinerary_details	= $this->db->query($id_query)->result_array();
				//$booking_customer_details	= $this->db->query($cd_query)->result_array();
				$booking_transaction_details = $this->db->query($td_query)->result_array();
				//$cancellation_details = $this->db->query($cancellation_details_query)->result_array();
				//$payment_details = $this->db->query($payment_details_query)->result_array();
			}
	
			$response['data']['booking_details']			= $booking_details;
			$response['data']['booking_itinerary_details']	= $booking_itinerary_details;
			$response['data']['booking_transaction_details']	= $booking_transaction_details;
			// $response['data']['booking_customer_details']	= $booking_customer_details;
			// $response['data']['cancellation_details']	= $cancellation_details;
			//$response['data']['payment_details']	= $payment_details;
			return $response;
		}
	}
	function b2b_holiday_report($condition=array(), $count=false, $offset=0, $limit=100000000000)
	{


		$condition = $this->custom_db->get_custom_condition($condition);
		// debug($condition);exit();
		//$b2c_condition_array = array('U.user_type', '=', B2C_USER, ' OR ', 'BD.created_by_id', '=', 0);
		
		//BT, CD, ID


		 
		 // debug($condition);exit();

		if(isset($condition) == true)
		{
			$offset = 0;
		}else{
			
			$offset = $offset;
		}

			// debug($count);exit();
		if ($count) {
			
			$query = 'select count(distinct(BD.app_reference)) AS total_records from package_booking_details BD
					 left join package P on P.package_id = BD.package_type
					left join user U on U.user_id = BD.created_by_id
					left join user_type UT on UT.origin = U.user_type
					join package_booking_transaction_details as BT on BD.app_reference = BT.app_reference	
					where BD.user_type=4 and BD.domain_origin='.get_domain_auth_id().''.$condition;
			// echo debug($query);exit;
			
			$data = $this->db->query($query)->row_array();
			// debug($data);exit();
			// echo $this->db->last_query();exit();
			return $data['total_records'];

		} else {
			$this->load->library('booking_data_formatter');
			$response['status'] = SUCCESS_STATUS;
			$response['data'] = array();
			$booking_itinerary_details	= array();
			$booking_customer_details	= array();
			$booking_transaction_details = array();
			$cancellation_details = array();
			$payment_details = array();
			//Booking Details
			$bd_query = 'select P.package_name,P.module_type,BD.*,U.user_name,U.first_name,U.last_name from package_booking_details AS BD
					     left join package P on P.package_id = BD.package_type
					     left join user U on U.user_id = BD.created_by_id
					     left join user_type UT on UT.origin = U.user_type
					     join package_booking_transaction_details as BT on BD.app_reference = BT.app_reference		
						 WHERE BD.user_type=4 and BD.domain_origin='.get_domain_auth_id().' '.$condition.'
						 order by BD.created_datetime desc, BD.origin desc limit '.$offset.', '.$limit;		

					
						 
			$booking_details	= $this->db->query($bd_query)->result_array();
			// debug($booking_details);		exit;
			$app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
			if(empty($app_reference_ids) == false) {
				//Itinerary Details
				$id_query = 'select * from `package_booking_passenger_details`  AS ID
							WHERE ID.app_reference IN ('.$app_reference_ids.')';
				//Transaction Details
				$td_query = 'select * from package_booking_transaction_details AS TD
							WHERE TD.app_reference IN ('.$app_reference_ids.')';
				//Customer and Ticket Details
				// $cd_query = 'select CD.*,FPTI.TicketId,FPTI.TicketNumber,FPTI.IssueDate,FPTI.Fare,FPTI.SegmentAdditionalInfo
				// 			from flight_booking_passenger_details AS CD
				// 			left join flight_passenger_ticket_info FPTI on CD.origin=FPTI.passenger_fk
				// 			WHERE CD.flight_booking_transaction_details_fk IN
				// 			(select TD.origin from flight_booking_transaction_details AS TD
				// 			WHERE TD.app_reference IN ('.$app_reference_ids.'))';
				//Cancellation Details
				// $cancellation_details_query = 'select FCD.*
				// 		from flight_booking_passenger_details AS CD
				// 		left join flight_cancellation_details AS FCD ON FCD.passenger_fk=CD.origin
				// 		WHERE CD.flight_booking_transaction_details_fk IN
				// 		(select TD.origin from flight_booking_transaction_details AS TD
				// 		WHERE TD.app_reference IN ('.$app_reference_ids.'))';
				//$payment_details_query = '';
				$booking_itinerary_details	= $this->db->query($id_query)->result_array();
				//$booking_customer_details	= $this->db->query($cd_query)->result_array();
				$booking_transaction_details = $this->db->query($td_query)->result_array();
				//$cancellation_details = $this->db->query($cancellation_details_query)->result_array();
				//$payment_details = $this->db->query($payment_details_query)->result_array();
			}
	
			$response['data']['booking_details']			= $booking_details;
			$response['data']['booking_itinerary_details']	= $booking_itinerary_details;
			$response['data']['booking_transaction_details']	= $booking_transaction_details;
			// $response['data']['booking_customer_details']	= $booking_customer_details;
			// $response['data']['cancellation_details']	= $cancellation_details;
			//$response['data']['payment_details']	= $payment_details;
			return $response;
		}
	}

		public function get_api_country_list(){
    	$data = 'select name,iso_country_code,country_code FROM api_country_list';

    	return $this->db->query($data)->result();
    	
    }

    public function get_currency_list()
	{
		$query = "SELECT * FROM currency_converter where status = 1 ORDER BY (CASE WHEN country = 'NPR' THEN 0 ELSE 1 END), country";
		return $this->db->query($query)->result_array();

	
		// $this->db->select('*');
		// $this->db->where('status',1);
		// $query = $this->db->get('currency_converter');
		// if ( $query->num_rows > 0 ) {
		// 	return $query->result_array();
		// }else{
		// 	return array();
		// }	
	}



}