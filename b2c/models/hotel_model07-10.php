<?php

/**

 * Library which has generic functions to get data

 *

 * @package    Provab Application

 * @subpackage Hotel Model

 * @author     Balu A<balu.provab@gmail.com>

 * @version    V2

 */

Class Hotel_Model extends CI_Model

{

	private $master_search_data;



	/**

	 * return top destinations in hotel

	 */

	function hotel_top_destinations()

	{

		$query = 'SELECT * FROM (`all_api_city_master`),api_country_master WHERE all_api_city_master.country_code=api_country_master.iso_country_code AND `top_destination` = 1 AND home_status=1 ORDER BY `top_destination` DESC, `city_name` ASC LIMIT 100000';

		$data = $this->db->query($query)->result_array();

		return $data;

	}
	/**

	 * return hotel perfect packages

	 */

	function hotel_perfect_packages()

	{

		$query = 'SELECT *, all_api_city_master.origin as ID FROM (`all_api_city_master`),api_country_master WHERE all_api_city_master.country_code=api_country_master.iso_country_code AND `perfect_package` = 1 AND perfect_packages_status=1 ORDER BY `perfect_package` DESC, `city_name` ASC LIMIT 100000';

		$data = $this->db->query($query)->result_array();

		return $data;

	}

	function villa_display()

	{

		$query = 'SELECT *,t2.one_adult as price FROM crs_hotel_details AS t1 
					LEFT JOIN crs_room_price AS t2 ON t1.id=t2.hotel_id 
			         WHERE 
		
					 t1.displaytopvilla_status=1 
					
					GROUP BY t1.id  order by t1.id DESC LIMIT 15';
		 $data = $this->db->query($query)->result_array();
		 for($i=0;$i<count($data);$i++)
		{
		    	$q3=$this->db->get_where('crs_room_price',array('hotel_id'=>$data[$i]['id']));
		    	$roomprice = $q3->row();
		    
		    	$adult_price=$roomprice->two_adult;
		    	
		    	$child_price=0;
		    	if(empty($adult_price))
		   	{
		   	    array_pop($data[$i]);
		   	}
		   	
		   	$data[$i]['RPrice']=$adult_price;
		   		$city=$data[$i]['city'];
		   	$country=$data[$i]['country'];
		      	$queryc ="Select cm.country_name,cm.city_name,cm.origin,cm.country_code,cm.stateprovince from all_api_city_master as cm where  cm.city_name='$city' &&  cm.country_name='$country'";
		      	//echo $queryc;die;
		   			$cdata = $this->db->query($queryc)->result_array();
		   		//	debug(	$cdata);die;
		   		$data[$i]['cid']=$cdata[0]['origin'];
		   	
		}
	//	 debug($data);die;
		 return $data;

	}
	function save_booking_detailscrs($domain_origin, $status, $app_reference, $booking_source, $booking_id, $booking_reference, $confirmation_reference,
	$hotel_name, $star_rating, $hotel_code, $phone_number, $alternate_number, $email,
	$hotel_check_in, $hotel_check_out, $payment_mode,	$attributes, $created_by_id, $transaction_currency, $currency_conversion_rate)
	{
		$data['domain_origin'] = $domain_origin;
		$data['status'] = $status;
		$data['app_reference'] = $app_reference;
		$data['booking_source'] = $booking_source;
		$data['booking_id'] = $booking_id;
		$data['booking_reference'] = $booking_reference;
		$data['confirmation_reference'] = $confirmation_reference;
		$data['hotel_name'] = $hotel_name;
		$data['star_rating'] = $star_rating;
		$data['hotel_code'] = $hotel_code;
		$data['phone_number'] = $phone_number;
		$data['alternate_number'] = $alternate_number;
		$data['email'] = $email;
		$data['hotel_check_in'] = $hotel_check_in;
		$data['hotel_check_out'] = $hotel_check_out;
		$data['payment_mode'] = $payment_mode;
		$data['attributes'] = $attributes;
		$data['created_by_id'] = $created_by_id;
		$data['created_datetime'] = date('Y-m-d H:i:s');
		
		$data['currency'] = $transaction_currency;
		$data['currency_conversion_rate'] = $currency_conversion_rate;
		
		$status = $this->custom_db->insert_record('hotel_booking_details', $data);
		return $status;
	}

	
	
			function cabin_display()

	{

		$query = 'SELECT *,t2.one_adult as price FROM crs_hotel_details AS t1 
					LEFT JOIN crs_room_price AS t2 ON t1.id=t2.hotel_id 
			         WHERE 
		
					 t1.displaytopCabin_status=1 
					
					GROUP BY t1.id  order by t1.id DESC LIMIT 15';
		 $data = $this->db->query($query)->result_array();
		 for($i=0;$i<count($data);$i++)
		{
		    	$q3=$this->db->get_where('crs_room_price',array('hotel_id'=>$data[$i]['id']));
		    	$roomprice = $q3->row();
		    
		    	$adult_price=$roomprice->two_adult;
		    	
		    	$child_price=0;
		    	if(empty($adult_price))
		   	{
		   	    array_pop($data[$i]);
		   	}
		   	
		   	$data[$i]['RPrice']=$adult_price;
		   		$city=$data[$i]['city'];
		   	$country=$data[$i]['country'];
		      	$queryc ="Select cm.country_name,cm.city_name,cm.origin,cm.country_code,cm.stateprovince from all_api_city_master as cm where  cm.city_name='$city' &&  cm.country_name='$country'";
		      	//echo $queryc;die;
		   			$cdata = $this->db->query($queryc)->result_array();
		   		//	debug(	$cdata);die;
		   		$data[$i]['cid']=$cdata[0]['origin'];
		   	
		}
	//	 debug($data);die;
		 return $data;

	}
			function resort_display()

	{

		$query = 'SELECT *,t2.one_adult as price FROM crs_hotel_details AS t1 
					LEFT JOIN crs_room_price AS t2 ON t1.id=t2.hotel_id 
			         WHERE 
		
					 t1.displaytopResort_status=1 
					
					GROUP BY t1.id  order by t1.id DESC LIMIT 15';
		 $data = $this->db->query($query)->result_array();
		 for($i=0;$i<count($data);$i++)
		{
		    	$q3=$this->db->get_where('crs_room_price',array('hotel_id'=>$data[$i]['id']));
		    	$roomprice = $q3->row();
		    
		    	$adult_price=$roomprice->two_adult;
		    	
		    	$child_price=0;
		    	if(empty($adult_price))
		   	{
		   	    array_pop($data[$i]);
		   	}
		   	
		   	$data[$i]['RPrice']=$adult_price;
		   		$city=$data[$i]['city'];
		   	$country=$data[$i]['country'];
		      	$queryc ="Select cm.country_name,cm.city_name,cm.origin,cm.country_code,cm.stateprovince from all_api_city_master as cm where  cm.city_name='$city' &&  cm.country_name='$country'";
		      	//echo $queryc;die;
		   			$cdata = $this->db->query($queryc)->result_array();
		   		//	debug(	$cdata);die;
		   		$data[$i]['cid']=$cdata[0]['origin'];
		   	
		}
	 //debug($data);die;
		 return $data;

	}
		function villa_display2()

	{

		$query = 'SELECT *,t2.one_adult as price FROM crs_hotel_details AS t1 
					LEFT JOIN crs_room_price AS t2 ON t1.id=t2.hotel_id 
			         WHERE 
		
					 t1.displaytopApts_status=1 
					
					GROUP BY t1.id  order by t1.id DESC LIMIT 15';
		 $data = $this->db->query($query)->result_array();
		 for($i=0;$i<count($data);$i++)
		{
		    	$q3=$this->db->get_where('crs_room_price',array('hotel_id'=>$data[$i]['id']));
		    	$roomprice = $q3->row();
		    
		    	$adult_price=$roomprice->two_adult;
		    	
		    	$child_price=0;
		    	if(empty($adult_price))
		   	{
		   	    array_pop($data[$i]);
		   	}
		   	
		   	$data[$i]['RPrice']=$adult_price;
		   		$city=$data[$i]['city'];
		   	$country=$data[$i]['country'];
		      	$queryc ="Select cm.country_name,cm.city_name,cm.origin,cm.country_code,cm.stateprovince from all_api_city_master as cm where  cm.city_name='$city' &&  cm.country_name='$country'";
		      	//echo $queryc;die;
		   			$cdata = $this->db->query($queryc)->result_array();
		   		//	debug(	$cdata);die;
		   		$data[$i]['cid']=$cdata[0]['origin'];
		   	
		}
	//	 debug($data);die;
		 return $data;

	}
		public function update_hot_details($AppReference, $id)
	{
		$AppReference = trim($AppReference);
		$this->custom_db->update_record('hotel_booking_details', array('supplier_id' => $id), array('app_reference' => $AppReference));//later
	
	}
	function homepage_hotel_crs_display()

	{
$search_data=array();
		$query = 'SELECT * FROM crs_hotel_details AS t1 
			         WHERE 
		
					 t1.displayhp_crs_status=1 
					
					GROUP BY t1.id  order by t1.id DESC LIMIT 15';
		 $data = $this->db->query($query)->result_array();
		 //debug($data);die;
		
	for($i=0;$i<count($data);$i++)
		{
		    	$q3=$this->db->get_where('crs_room_price',array('hotel_id'=>$data[$i]['id']));
		    	$roomprice = $q3->row();
		    
		    	$adult_price=$roomprice->two_adult;
		    	
		    	$child_price=0;
		    	if(empty($adult_price))
		   	{
		   	    array_pop($data[$i]);
		   	}
		   	
		   	$data[$i]['RPrice']=$adult_price;
		   	$city=$data[$i]['city'];
		   	$country=$data[$i]['country'];
		      	$queryc ="Select cm.country_name,cm.city_name,cm.origin,cm.country_code,cm.stateprovince from all_api_city_master as cm where  cm.city_name='$city' &&  cm.country_name='$country'";
		      	//echo $queryc;die;
		   			$cdata = $this->db->query($queryc)->result_array();
		   		//	debug(	$cdata);die;
		   		$data[$i]['cid']=$cdata[0]['origin'];
		   	
		}
		 
//	debug($data);die;
		 return $data;

	}
		function homepage_hotel_crs_display2()

	{
$search_data=array();
		$query = 'SELECT * FROM crs_hotel_details AS t1 
			         WHERE 
		
					 t1.displayhptop_crs_status	=1 
					
					GROUP BY t1.id  order by t1.id DESC LIMIT 15';
		 $data = $this->db->query($query)->result_array();
		 //debug($data);die;
		
	for($i=0;$i<count($data);$i++)
		{
		    	$q3=$this->db->get_where('crs_room_price',array('hotel_id'=>$data[$i]['id']));
		    	$roomprice = $q3->row();
		    
		    	$adult_price=$roomprice->two_adult;
		    	
		    	$child_price=0;
		    	if(empty($adult_price))
		   	{
		   	    array_pop($data[$i]);
		   	}
		   	
		   	$data[$i]['RPrice']=$adult_price;
		   	$city=$data[$i]['city'];
		   	$country=$data[$i]['country'];
		      	$queryc ="Select cm.country_name,cm.city_name,cm.origin,cm.country_code,cm.stateprovince from all_api_city_master as cm where  cm.city_name='$city' &&  cm.country_name='$country'";
		      	//echo $queryc;die;
		   			$cdata = $this->db->query($queryc)->result_array();
		   		//	debug(	$cdata);die;
		   		$data[$i]['cid']=$cdata[0]['origin'];
		   	
		}
		 
//	debug($data);die;
		 return $data;

	}
		public function get_first_room_price($hotel_code=0,$season_id=0,$search_data=array(),$details=false)
	{
		$q3=$this->db->get_where('crs_room_price',array('id'=>$season_id));
		if($q3->num_rows()<1)
		{
			return	false;
		}
		$roomprice = $q3->row();
		
		
		$adult_price=0;
		$child_price=0;
	//debug($search_data);die;
	$search_data['data']['adult_config'][0]='2';
		$RoomPrice=0;
		foreach ($search_data['data']['adult_config']	 as $a => $adt) 
		{
			switch ($adt) {
				case 1:
					$adult_price+=$roomprice->one_adult;
				break;
				case 2:
					$adult_price+=$roomprice->two_adult;
				break;
				case 3:
					$adult_price+=$roomprice->three_adult;
				break;
				case 4:
					$adult_price+=($roomprice->three_adult+$roomprice->extrabed_price);
				break;				
				default:
					$adult_price+=0;
				break;
			}
		}
		$search_data['data']['child_config'][0]='0';
		foreach ($search_data['data']['child_config']	 as $c => $chd) 
		{
			
			switch ($chd) {
				case 1:
					$child_price+=$roomprice->child_price*1;
				break;
				case 2:
					$child_price+=$roomprice->child_price*2;
				break;
				default:
					$child_price+=0;
				break;
			}
		}
//	debug($adult_price);die;
		// debug($child_price);die;
		// $RoomPrice=($roomprice->one_adult*$s_max_adult)+($roomprice->room_child_price_b*$s_max_child);
		$RoomPrice = ($adult_price+$child_price)*$search_data['data']['no_of_nights'];
		//debug($RoomPrice);die;
		$price=array();
		$price['TBO_RoomPrice'] =$RoomPrice;
		$price['TBO_OfferedPriceRoundedOff'] =$RoomPrice;
		$price['TBO_PublishedPrice'] =$RoomPrice;
		$price['TBO_PublishedPriceRoundedOff'] =$RoomPrice;
		$price['Tax'] =0;
		$price['ExtraGuestCharge'] =0;
		$price['ChildCharge'] =0;
		$price['OtherCharges'] =0;
		$price['Discount'] =0;
		$price['PublishedPrice'] =$RoomPrice;
		$price['RoomPrice'] =$RoomPrice;
		$price['PublishedPriceRoundedOff'] =$RoomPrice;
		$price['OfferedPrice'] =$RoomPrice;
		$price['OfferedPriceRoundedOff'] =$RoomPrice;
		$price['AgentCommission'] =0;
		$price['AgentMarkUp'] =0;
		$price['ServiceTax'] =0;
		$price['TDS'] =0;
		$price['ServiceCharge'] =0;
		$price['TotalGSTAmount'] =0;
		$price['RoomPriceWoGST'] =$roomprice->room_id;
		$price['GSTPrice'] =0;
		$price['CurrencyCode'] =admin_base_currency();
		$data['Price']=$price;
		$data['room_name']='First Room';
		$data['Room_data']=array(	'RoomUniqueId'=>$roomprice->room_id,
									'rate_key'=>$roomprice->id,
									'group_code'=>$roomprice->room_id);
		// debug($price);die;
		if($details==false)
		{
			return $price;
		}
		return $data;
	}

	function acco_hotel_crs_display()

	{

		$query = 'SELECT * FROM crs_hotel_details AS t1 
			         WHERE 
		
					 t1.displaytophotel_status=1 
					
					GROUP BY t1.id  order by t1.id DESC LIMIT 15';
		 $data = $this->db->query($query)->result_array();
		 return $data;

	}
		function get_supplier_details($id)
	{
		//BT, CD, ID
		$query = 'select user.user_id,user.first_name,user.last_name,user.email,user.phone from crs_hotel_details  inner join user on crs_hotel_details.created_by=user.user_id where crs_hotel_details.id='.$id;
	//	echo $query;die;
		return $this->db->query($query)->result_array();
	}
	function hotel_top_destinations_home()

	{
$query='SELECT * FROM (`all_api_city_master`),api_country_master WHERE all_api_city_master.country_code=api_country_master.iso_country_code AND `top_destination` = 1 AND home_status=1 ORDER BY `top_destination` DESC, `city_name` ASC LIMIT 100000';
	//	$query = 'Select CT.*, CN.country_name AS country from all_api_city_master CT, api_country_master CN where CT.country_code=CN.iso_country_code AND home_status = '.ACTIVE;
//echo $query;die;
		$data = $this->db->query($query)->result_array();

		return $data;

	}

	/*

	 *

	 * Get Airport List

	 *

	 */

	function get_hotel_city_list($search_chars)

	{

		$raw_search_chars = $this->db->escape($search_chars);

		if(empty($search_chars)==false){

			$r_search_chars = $this->db->escape('%'.$search_chars.'%');

			$search_chars = $this->db->escape('%'.$search_chars.'%');

		}else{

			$r_search_chars = $this->db->escape($search_chars);

			$search_chars = $this->db->escape($search_chars);

		}

		

		$query = 'Select cm.country_name,cm.city_name,cm.origin,cm.country_code,cm.stateprovince from all_api_city_master as cm where  cm.city_name like '.$search_chars.' 

				ORDER BY cm.cache_hotels_count desc, CASE

			WHEN	cm.city_name	LIKE	'.$raw_search_chars.'	THEN 1

			WHEN	cm.city_name	LIKE	'.$r_search_chars.'	THEN 2	

			WHEN	cm.city_name	LIKE	'.$search_chars.'	THEN 3

			ELSE 4 END, cm.cache_hotels_count desc LIMIT 0, 30

		';


		return $this->db->query($query)->result_array();

	}

	function get_hotel_city_list_base($search_chars)

	{

		$raw_search_chars = $this->db->escape($search_chars);

		$r_search_chars = $this->db->escape($search_chars.'%');

		$search_chars = $this->db->escape('%'.$search_chars.'%');

		$query = 'Select * from hotels_city where city_name like '.$search_chars.'

		OR country_name like '.$search_chars.' OR country_code like '.$search_chars.'

		ORDER BY top_destination DESC, CASE

			WHEN	city_name	LIKE	'.$raw_search_chars.'	THEN 1

			WHEN	country_name	LIKE	'.$raw_search_chars.'	THEN 2

			WHEN	country_code			LIKE	'.$raw_search_chars.'	THEN 3

			

			WHEN	city_name	LIKE	'.$r_search_chars.'	THEN 4

			WHEN	country_name	LIKE	'.$r_search_chars.'	THEN 5

			WHEN	country_code			LIKE	'.$r_search_chars.'	THEN 6

			

			WHEN	city_name	LIKE	'.$search_chars.'	THEN 7

			WHEN	country_name	LIKE	'.$search_chars.'	THEN 8

			WHEN	country_code			LIKE	'.$search_chars.'	THEN 9

			ELSE 10 END, 

			cache_hotels_count DESC

		LIMIT 0, 20';

		return $this->db->query($query)->result_array();

	}



	/**

	 * get all the booking source which are active for current domain

	 */

	function active_booking_source()

	{

		$query = 'select BS.source_id, BS.origin from meta_course_list AS MCL, booking_source AS BS, activity_source_map AS ASM WHERE

		MCL.origin=ASM.meta_course_list_fk and ASM.booking_source_fk=BS.origin and MCL.course_id='.$this->db->escape(META_ACCOMODATION_COURSE).'

		and BS.booking_engine_status='.ACTIVE.' AND MCL.status='.ACTIVE.' AND ASM.status="active"';
		//print_r($query);exit;
		return $this->db->query($query)->result_array();

	}

	function active_booking_hotel_crs_source()

	{

		$query = 'select BS.source_id, BS.origin from meta_course_list AS MCL, booking_source AS BS, activity_source_map AS ASM WHERE

		MCL.origin=ASM.meta_course_list_fk and ASM.booking_source_fk=BS.origin and MCL.course_id='.$this->db->escape(META_ACCOMODATION_CRS).'

		and BS.booking_engine_status='.ACTIVE.' AND MCL.status='.ACTIVE.' AND ASM.status="active"';
		//print_r($query);exit;
		return $this->db->query($query)->result_array();

	}

	/**

	 * return booking list

	 */

	function booking($condition=array(), $count=false, $offset=0, $limit=100000000000)

	{

		$condition = $this->custom_db->get_custom_condition($condition);

		//BT, CD, ID

		if ($count) {

			$query = 'select count(distinct(BD.app_reference)) as total_records 

					from hotel_booking_details BD

					join hotel_booking_itinerary_details AS HBID on BD.app_reference=HBID.app_reference

					join payment_option_list AS POL on BD.payment_mode=POL.payment_category_code 

					where BD.domain_origin='.get_domain_auth_id().' and BD.created_by_id ='.$GLOBALS['CI']->entity_user_id.''.$condition;

			$data = $this->db->query($query)->row_array();

			return $data['total_records'];

		} else {

			$this->load->library('booking_data_formatter');

			$response['status'] = SUCCESS_STATUS;

			$response['data'] = array();

			$booking_itinerary_details	= array();

			$booking_customer_details	= array();

			$cancellation_details = array();

			$bd_query = 'select * from hotel_booking_details AS BD 

						WHERE BD.domain_origin='.get_domain_auth_id().' and BD.created_by_id ='.$GLOBALS['CI']->entity_user_id.''.$condition.'

						order by BD.origin desc limit '.$offset.', '.$limit;

			$booking_details = $this->db->query($bd_query)->result_array();

			$app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);

			if(empty($app_reference_ids) == false) {

				$id_query = 'select * from hotel_booking_itinerary_details AS ID 

							WHERE ID.app_reference IN ('.$app_reference_ids.')';

				$cd_query = 'select * from hotel_booking_pax_details AS CD 

							WHERE CD.app_reference IN ('.$app_reference_ids.')';

				$cancellation_details_query = 'select * from hotel_cancellation_details AS HCD 

							WHERE HCD.app_reference IN ('.$app_reference_ids.')';

				$booking_itinerary_details	= $this->db->query($id_query)->result_array();

				$booking_customer_details	= $this->db->query($cd_query)->result_array();

				$cancellation_details	= $this->db->query($cancellation_details_query)->result_array();

			}

			$response['data']['booking_details']			= $booking_details;

			$response['data']['booking_itinerary_details']	= $booking_itinerary_details;

			$response['data']['booking_customer_details']	= $booking_customer_details;

			$response['data']['cancellation_details']	= $cancellation_details;

			return $response;

		}

	}

	function booking_guest_user($app_reference, $booking_source, $booking_status){

		$response['status'] = SUCCESS_STATUS;

		$response['data'] = array();

		$booking_itinerary_details	= array();

		$booking_customer_details	= array();

		$cancellation_details = array();

		$bd_query = 'select * from hotel_booking_details AS BD WHERE (BD.app_reference like '.$this->db->escape($app_reference).' || BD.booking_id like '.$this->db->escape($app_reference).')';

		if (empty($booking_source) == false) {

			$bd_query .= ' AND BD.booking_source = '.$this->db->escape($booking_source);

		}

		if (empty($booking_status) == false) {

			$bd_query .= ' AND BD.status = '.$this->db->escape($booking_status);

		}

		$booking_details = $this->db->query($bd_query)->result_array();

		$app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);

		if(empty($app_reference_ids) == false) {

			$id_query = 'select * from hotel_booking_itinerary_details AS ID 

						WHERE ID.app_reference IN ('.$app_reference_ids.')';

			$cd_query = 'select * from hotel_booking_pax_details AS CD 

						WHERE CD.app_reference IN ('.$app_reference_ids.')';

			$cancellation_details_query = 'select * from hotel_cancellation_details AS HCD 

						WHERE HCD.app_reference IN ('.$app_reference_ids.')';

			$booking_itinerary_details	= $this->db->query($id_query)->result_array();

			$booking_customer_details	= $this->db->query($cd_query)->result_array();

			$cancellation_details	= $this->db->query($cancellation_details_query)->result_array();

		}

		$response['data']['booking_details']			= $booking_details;

		$response['data']['booking_itinerary_details']	= $booking_itinerary_details;

		$response['data']['booking_customer_details']	= $booking_customer_details;

		$response['data']['cancellation_details']	= $cancellation_details;

			return $response;

		return $response;

	}

	/**

	 * Return Booking Details based on the app_reference passed

	 * @param $app_reference

	 * @param $booking_source

	 * @param $booking_status

	 */

	function get_booking_details($app_reference, $booking_source, $booking_status='')

	{

		$response['status'] = FAILURE_STATUS;

		$response['data'] = array();

		$bd_query = 'select * from hotel_booking_details AS BD WHERE BD.app_reference like '.$this->db->escape($app_reference);

		if (empty($booking_source) == false) {

			$bd_query .= '	AND BD.booking_source = '.$this->db->escape($booking_source);

		}

		if (empty($booking_status) == false) {

			$bd_query .= ' AND BD.status = '.$this->db->escape($booking_status);

		}

		$id_query = 'select * from hotel_booking_itinerary_details AS ID WHERE ID.app_reference='.$this->db->escape($app_reference);

		$cd_query = 'select * from hotel_booking_pax_details AS CD WHERE CD.app_reference='.$this->db->escape($app_reference);

		$cancellation_details_query = 'select HCD.* from hotel_cancellation_details AS HCD WHERE HCD.app_reference='.$this->db->escape($app_reference);

		$response['data']['booking_details']			= $this->db->query($bd_query)->result_array();

		$response['data']['booking_itinerary_details']	= $this->db->query($id_query)->result_array();

		$response['data']['booking_customer_details']	= $this->db->query($cd_query)->result_array();

		$response['data']['cancellation_details']	= $this->db->query($cancellation_details_query)->result_array();

		if (valid_array($response['data']['booking_details']) == true and valid_array($response['data']['booking_itinerary_details']) == true and valid_array($response['data']['booking_customer_details']) == true) {

			$response['status'] = SUCCESS_STATUS;

		}

		return $response;

	}



	/**

	 * get search data and validate it

	 */

	function get_safe_search_data($search_id)

	{

		$search_data = $this->get_search_data($search_id);

//debug($search_id);die;
		$success = true;

		$clean_search = array();

		if ($search_data != false) {

			//validate


			$temp_search_data = json_decode($search_data['search_data'], true);



			$clean_search = $this->clean_search_data($temp_search_data);

			$success = $clean_search['status'];

			$clean_search = $clean_search['data'];

		} else {

			$success = false;

		}


		return array('status' =>true, 'data' => $clean_search);

	}



	/**

	 * Clean up search data

	 */

	function clean_search_data($temp_search_data)

	{


		$success = true;


		if ((strtotime($temp_search_data['hotel_checkin']) > time() && strtotime($temp_search_data['hotel_checkout']) > time()) || date('Y-m-d', strtotime($temp_search_data['hotel_checkin'])) == date('Y-m-d')) {

		

			//Swap dates if not correctly set

			$clean_search['from_date'] = $temp_search_data['hotel_checkin'];
		

			$clean_search['to_date'] = $temp_search_data['hotel_checkout'];

			
			$clean_search['no_of_nights'] = abs(get_date_difference($clean_search['from_date'], $clean_search['to_date']));

		} else {

			$success = false;

		}
	if ((strtotime($temp_search_data['hotel_checkin_1']) > time() && strtotime($temp_search_data['hotel_checkout_1']) > time()) || date('Y-m-d', strtotime($temp_search_data['hotel_checkin_1'])) == date('Y-m-d')) {

		

			//Swap dates if not correctly set

			$clean_search['from_date'] = $temp_search_data['hotel_checkin_1'];
		

			$clean_search['to_date'] = $temp_search_data['hotel_checkout_1'];

			
			$clean_search['no_of_nights'] = abs(get_date_difference($clean_search['from_date'], $clean_search['to_date']));

		} else {

			$success = false;

		}
		//city name and country name

		

		if (isset($temp_search_data['hotel_destination']) == true) {

			$clean_search['hotel_destination'] = $temp_search_data['hotel_destination'];

		}

		if (isset($temp_search_data['city']) == true) {

			$clean_search['location'] = $temp_search_data['city'];

			$temp_location = explode(',', $temp_search_data['city']);

			$clean_search['city_name'] = trim($temp_location[0]);

			$cn=end($temp_location);

			if (isset($cn) == true) {

				//Pop will get last element in the array since element patterns can repeat

				$clean_search['country_name'] = trim($cn);

			} else {

				$clean_search['country_name'] = '';

			}

		} else {

			$success = false;

		}



		//Occupancy

		if (isset($temp_search_data['rooms']) == true) {

			$clean_search['room_count'] = abs($temp_search_data['rooms']);

		} else {

			$success = false;

		}

		if (isset($temp_search_data['adult']) == true) {

			$clean_search['adult_config'] = $temp_search_data['adult'];

		} else {

			$success = false;

		}



		if (isset($temp_search_data['child']) == true) {

			$clean_search['child_config'] = $temp_search_data['child'];

		}



		if (valid_array($temp_search_data['child'])) {

			foreach ($temp_search_data['child'] as $tc_k => $tc_v) {

				if (intval($tc_v) > 0) {

					$child_age_index = $tc_v;

					

					if($child_age_index>1){

						foreach($temp_search_data['childAge_'.($tc_k+1)] as $ic_k => $ic_v) {



							$clean_search['child_age'][] = $ic_v;

						}

					}else{

						$clean_search['child_age'][] = $temp_search_data['childAge_'.($tc_k+1)][0];



					}

					

				}

			}

		}

		

		if (strtolower($clean_search['country_name']) == 'india') {

			$clean_search['is_domestic'] = true;

		} else {

			$clean_search['is_domestic'] = false;

		}

		

		if($temp_search_data['search_type'] == 'location_search'){

			$clean_search['location'] = $temp_search_data['location'];

			$clean_search['latitude'] = $temp_search_data['latitude'];

			$clean_search['longitude'] = $temp_search_data['longitude'];

			$clean_search['radius'] = $temp_search_data['radius'];

			$clean_search['countrycode'] = $temp_search_data['countrycode'];



		}

		$clean_search['search_type'] = $temp_search_data['search_type'];

	//	debug();die;

		return array('data' => $clean_search, 'status' => $success);

	}



	/**

	 * get search data without doing any validation

	 * @param $search_id

	 */

	function get_search_data($search_id)

	{

		if (empty($this->master_search_data)) {

			$search_data = $this->custom_db->single_table_records('search_history', '*', array('search_type' => META_ACCOMODATION_COURSE, 'origin' => $search_id));

			if ($search_data['status'] == true) {

				$this->master_search_data = $search_data['data'][0];

			} else {

				return false;

			}

		}

		return $this->master_search_data;

	}



	/**

	 * get hotel city id of tbo from tbo hotel city list

	 * @param string $city	  city name for which id has to be searched

	 * @param string $country country name in which the city is present

	 */

	function tbo_hotel_city_id($city, $country)

	{

		$response['status'] = true;

		$response['data'] = array();

		$location_details = $this->custom_db->single_table_records('hotels_city', 'country_code, origin', array('city_name like' => $city, 'country_name like' => $country));

		if ($location_details['status']) {

			$response['data'] = $location_details['data'][0];

		} else {

			$response['status'] = false;

		}

		return $response;

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

	$hotel_name, $star_rating, $hotel_code, $phone_number, $alternate_number, $email,

	$hotel_check_in, $hotel_check_out, $payment_mode,	$attributes, $created_by_id, $transaction_currency, $currency_conversion_rate, $phone_code)

	{

		$data['domain_origin'] = $domain_origin;

		$data['status'] = $status;

		$data['app_reference'] = $app_reference;

		$data['booking_source'] = $booking_source;

		$data['booking_id'] = $booking_id;

		$data['booking_reference'] = $booking_reference;

		$data['confirmation_reference'] = $confirmation_reference;

		$data['hotel_name'] = $hotel_name;

		$data['star_rating'] = $star_rating;

		$data['hotel_code'] = $hotel_code;

		$data['phone_number'] = $phone_number;

		$data['phone_code'] = $phone_code;

		$data['alternate_number'] = $alternate_number;

		$data['email'] = $email;

		$data['hotel_check_in'] = $hotel_check_in;

		$data['hotel_check_out'] = $hotel_check_out;

		$data['payment_mode'] = $payment_mode;

		$data['attributes'] = $attributes;

		$data['created_by_id'] = $created_by_id;

		$data['created_datetime'] = date('Y-m-d H:i:s');

		

		$data['currency'] = $transaction_currency;

		$data['currency_conversion_rate'] = $currency_conversion_rate;

		

		$status = $this->custom_db->insert_record('hotel_booking_details', $data);

		return $status;

	}



	/**

	 *

	 * @param string $app_reference

	 * @param string $location

	 * @param date	 $check_in

	 * @param date	 $check_out

	 * @param string $room_type_name

	 * @param string $bed_type_code

	 * @param string $status

	 * @param string $smoking_preference

	 * @param string $attributes

	 */

	function save_booking_itinerary_details($app_reference, $location, $check_in, $check_out, $room_type_name, $bed_type_code,

	$status, $smoking_preference, $total_fare, $admin_markup, $agent_markup, $currency, $attributes,

	$RoomPrice, $Tax, $ExtraGuestCharge, $ChildCharge, $OtherCharges,

	$Discount, $ServiceTax, $AgentCommission, $AgentMarkUp, $TDS, $gst)

	{

		$data['app_reference'] = $app_reference;

		$data['location'] = $location;

		$data['check_in'] = $check_in;

		$data['check_out'] = $check_out;

		$data['room_type_name'] = $room_type_name;

		$data['bed_type_code'] = $bed_type_code;

		$data['status'] = $status;

		$data['smoking_preference'] = $smoking_preference;

		$data['total_fare'] = $total_fare;

		$data['admin_markup'] = $admin_markup;

		$data['agent_markup'] = $agent_markup;

		$data['currency'] = $currency;

		$data['attributes'] = $attributes;



		$data['RoomPrice'] = floatval($RoomPrice);

		$data['Tax'] = floatval($Tax);

		$data['ExtraGuestCharge'] = floatval($ExtraGuestCharge);

		$data['ChildCharge'] = floatval($ChildCharge);

		$data['OtherCharges'] = floatval($OtherCharges);

		$data['Discount'] = floatval($Discount);

		$data['ServiceTax'] = floatval($ServiceTax);

		$data['AgentCommission'] = floatval($AgentCommission);

		$data['AgentMarkUp'] = floatval($AgentMarkUp);

		$data['TDS'] = floatval($TDS);

		//adding gst

		$data['gst'] = $gst;

		

		$status = $this->custom_db->insert_record('hotel_booking_itinerary_details', $data);

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

	function save_booking_pax_details($app_reference, $title, $first_name, $middle_name, $last_name,$phone, $email, $pax_type, $date_of_birth,

	$passenger_nationality, $passport_number, $passport_issuing_country, $passport_expiry_date, $status, $attributes)

	{

		$data['app_reference'] = $app_reference;

		$data['title'] = $title;

		$data['first_name'] = $first_name;

		$data['middle_name'] = (empty($middle_name) == true ?  $last_name: $middle_name);

		$data['last_name'] = $last_name;

		$data['phone'] = $phone;

		$data['email'] = $email;

		$data['pax_type'] = $pax_type;

		$data['date_of_birth'] = $date_of_birth;

		$data['passenger_nationality'] = $passenger_nationality;

		$data['passport_number'] = $passport_number;

		$data['passport_issuing_country'] = $passport_issuing_country;

		$data['passport_expiry_date'] = $passport_expiry_date;

		$data['status'] = $status;

		$data['attributes'] = $attributes;

		

		$status = $this->custom_db->insert_record('hotel_booking_pax_details', $data);

		return $status;

	}

	/**

	 *

	 */

	function get_static_response($token_id)

	{

		$static_response = $this->custom_db->single_table_records('test', '*', array('origin' => intval($token_id)));

		return json_decode($static_response['data'][0]['test'], true);

	}



	/**

	 * SAve search data for future use - Analytics

	 * @param array $params

	 */

	function save_search_data($search_data, $type)

	{

		$data['domain_origin'] = get_domain_auth_id();

		$data['search_type'] = $type;

		$data['created_by_id'] = intval(@$this->entity_user_id);

		$data['created_datetime'] = date('Y-m-d H:i:s');



		$temp_location = explode(',', $search_data['city']);

		$data['city'] = trim($temp_location[0]);

		$cn=end($temp_location);



		if (isset($cn) == true) {

			$data['country'] = trim($cn);

		} else {

			$data['country'] = '';

		}

		
		$data['check_in'] = date('Y-m-d', strtotime($search_data['hotel_checkin']));

		$data['nights'] = abs(get_date_difference($search_data['hotel_checkin'], $search_data['hotel_checkout']));

		$data['rooms'] = $search_data['rooms'];

		$data['total_pax'] = array_sum($search_data['adult']) + array_sum($search_data['child']);

		$this->custom_db->insert_record('search_hotel_history', $data);

	}

	/**

	 * Balu A

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

		$this->custom_db->update_record('hotel_booking_details', array('status' => $booking_status), array('app_reference' => $AppReference));//later

		//3.Update Itinerary Status

		$this->custom_db->update_record('hotel_booking_itinerary_details', array('status' => $booking_status), array('app_reference' => $AppReference));//later

	}

	/**

	 * Add Cancellation details

	 * @param unknown_type $AppReference

	 * @param unknown_type $cancellation_details

	 */

	private function update_cancellation_refund_details($AppReference, $cancellation_details)

	{

		$hotel_cancellation_details = array();

		$hotel_cancellation_details['app_reference'] = 				$AppReference;

		$hotel_cancellation_details['ChangeRequestId'] = 			$cancellation_details['ChangeRequestId'];

		$hotel_cancellation_details['ChangeRequestStatus'] = 		$cancellation_details['ChangeRequestStatus'];

		$hotel_cancellation_details['status_description'] = 		$cancellation_details['StatusDescription'];

		$hotel_cancellation_details['API_RefundedAmount'] = 		@$cancellation_details['RefundedAmount'];

		$hotel_cancellation_details['API_CancellationCharge'] = 	@$cancellation_details['CancellationCharge'];

		if($cancellation_details['ChangeRequestStatus'] == 3){

			$hotel_cancellation_details['cancellation_processed_on'] =	date('Y-m-d H:i:s');

		}

		$cancel_details_exists = $this->custom_db->single_table_records('hotel_cancellation_details', '*', array('app_reference' => $AppReference));

		if($cancel_details_exists['status'] == true) {

			//Update the Data

			unset($hotel_cancellation_details['app_reference']);

			$this->custom_db->update_record('hotel_cancellation_details', $hotel_cancellation_details, array('app_reference' => $AppReference));

		} else {

			//Insert Data

			$hotel_cancellation_details['created_by_id'] = 				(int)@$this->entity_user_id;

			$hotel_cancellation_details['created_datetime'] = 			date('Y-m-d H:i:s');

			$data['cancellation_requested_on'] = date('Y-m-d H:i:s');

			$this->custom_db->insert_record('hotel_cancellation_details',$hotel_cancellation_details);

		}

	}

	/**

	*Image masking

	*/

	function setImgDownload($imagePath){

		$image = imagecreatefromjpeg($imagePath);

	    header('Content-Type: image/jpeg');

	    imagejpeg($image);

	}

    function add_hotel_images($sid,$HotelPicture,$HotelCode) {

         

        $image_url= $this->custom_db->single_table_records('hotel_image_url','image_url',array('hotel_code'=>$HotelCode));            

     

        if($image_url['status']==0) {

            foreach($HotelPicture as $key=>$value) {

			$data['image_url'] = $value;

			$data['ResultIndex'] = $key;

	                $data['hotel_code'] = $HotelCode;

			$this->custom_db->insert_record('hotel_image_url', $data);

            }

        }

    }

}

