<?php
class Seasons_Model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    } 
    
    function get_seasons_list($seasons_details_id = '',$hotel_details_id){
		$this->db->select('sd.*,hd.hotel_name');
		$this->db->from('seasons_details sd');
		if($seasons_details_id !='')
			$this->db->where('sd.seasons_details_id', $seasons_details_id);
		if($hotel_details_id !='')
			$this->db->where('sd.hotel_details_id', $hotel_details_id);
		$this->db->join('hotel_details hd', 'hd.hotel_details_id = sd.hotel_details_id');
		$query=$this->db->get();

		if($query->num_rows() ==''){
			return '';
		}else{
			return $query->result();
		}
	}

   function get_cancellation_policy($seasons_details_id = "",$hotel_details_id =""){
   		$this->db->select('*');
		$this->db->from('hotel_cancellation_policy');
	    $this->db->where('hotel_details_id', $hotel_details_id);
	    $this->db->where('session_detail_id',$seasons_details_id);
		$query=$this->db->get();
		//echo $this->db->last_query();
		if($query->num_rows() ==''){
			return '';
		}else{
			return $query->result();
		}
   }

   function get_season_room_type($room_type_id,$status = ""){
   	  $this->db->select('*');
   	  $this->db->where('hotel_room_type_id', $room_type_id);	  
   	  if($status != "")
   	  	$this->db->where('status',$status); 
	  $query=$this->db->get('seasons_details');	
	  //debug($query); exit;
	  if($query->num_rows() ==''){
			return '';
	  }else{
			return $query->result();
	  }
   }
	
	
	function add_seasons($input){	
		//echo "<pre>";print_r($input); exit();
		if($input['seasons_date_range'] != ''){
			$date_range_array = explode("-", $input['seasons_date_range']);
		
			//My Code
			$fromorderdate = explode('/', $date_range_array[0]);
			
				$monthfrom = $fromorderdate[1];
				$dayfrom   = $fromorderdate[0];
				$yearfrom  = $fromorderdate[2];
		 	$fromdate = $monthfrom.'/'.$dayfrom.'/'.$yearfrom;

		 	$toorderdate = explode('/', trim($date_range_array[1]));
			
				$monthto = $toorderdate[1];
				$dayto   = $toorderdate[0];
				$yearto  = $toorderdate[2];
		 	$todate = $monthto.'/'.$dayto.'/'.$yearto;

			//End My code
			$from_date = date_format(date_create(trim($fromdate)), 'Y-m-d');
			
			$to_date = date_format(date_create(trim($todate)), 'Y-m-d');
			//echo '<pre>sanjay'; print_r($to_date); exit();
		}

		//print_r($from_date);  print_r($to_date); exit();
		$room_type = $input['room_type'];	
		//print_r($room_type);exit;
		//echo count($room_type);		
		for($j=0;$j<count($room_type);$j++){			
		$insert_data = array(
							'hotel_details_id'			=> $input['hotel_details_id'],
							'seasons_name' 				=> $input['seasons_name'],
							'seasons_from_date'			=> $from_date,
							'seasons_to_date'			=> $to_date,
							'creation_date'				=> (date('Y-m-d H:i:s')),
							'hotel_room_type_id'	    => $room_type[$j],
							//'season_type'				=> $input['season_type'],			
							//'cutoff'   					=> $input['cutoff'],
							'minimum_stays'				=> $input['minimum_stays'],
							'status'   					=> 'ACTIVE'
						);	
						// echo "<pre>";print_r($insert_data);exit();
	    try{	
	    	$seasons_details_id = "";	
			$this->db->insert('seasons_details',$insert_data);

			$seasons_details_id = $this->db->insert_id();
			// debug($seasons_details_id );exit();
			$params = array(
							'hotel_details_id' => $input['hotel_details_id'],
							'hotel_room_type_id' => $room_type[0],
							'hotel_season_id' => $seasons_details_id
							);
			$this->add_season_room_count_row($params); // add add_season_room_count_row
			// debug($params);exit();
		} catch(Exception $e) {
			return $e;
		}
	


		if(sizeof($input['cancellation_from']) > 0){
			for($i=0; $i<count($input['cancellation_from']); $i++) {
				$data = array(	
				            'hotel_details_id' => $input['hotel_details_id'],
				            'hotel_room_type_id' => $room_type[$j],
				            'session_detail_id' => $seasons_details_id,
							'cancellation_from' => $input['cancellation_from'][$i],
							'cancellation_to' => $input['cancellation_to'][$i],
							'cancellation_night_charge' => $input['cancellation_nightcharge'][$i],
							'cancellation_percentage_charge' => $input['cancellation_percentage'][$i],
							'creation_date'			=> (date('Y-m-d H:i:s')),
							'cancellation_daterange' => $input['seasons_date_range'],
							'cancellation_status'   => 'ACTIVE'//$input['status']
				);			
				//echo "<pre>";print_r($data);
				$this->db->insert('hotel_cancellation_policy', $data); 
		    }//for			
		}
	}//for   		
	//exit();

	}

	function get_roomCountInfo_data($params)
	{
		// debug($params);
		$q = '
			SELECT 
				* 
			FROM 
				hotel_room_details rd
			LEFT JOIN 
				hotel_room_count_info hrci ON (rd.hotel_details_id = hrci.hotel_details_id AND rd.hotel_room_type_id = hrci.hotel_room_details_id)
			WHERE 
				rd.hotel_details_id='.$params['hotel_details_id'].'
				AND
				rd.hotel_room_type_id='.$params['hotel_room_type_id'].'
			LIMIT 1
			';

			// debug($q);exit();
		return $this->db->query($q);
	}

	function add_season_room_count_row($params)
	{
	
		$room_count_info = $this->get_roomCountInfo_data(
								array(
									'hotel_details_id' => $params['hotel_details_id'],
									'hotel_room_type_id' => $params['hotel_room_type_id']
									)
								);
		// debug($room_count_info); exit;	
		$rd = $room_count_info->result_array()[0];
		$data = array(
						'hotel_details_id' => $params['hotel_details_id'],
						'hotel_room_type_id' => $params['hotel_room_type_id'],
						'hotel_season_id' => $params['hotel_season_id'],
						'no_of_room' => $rd['no_of_room'],
						'hotel_room_count_info_id' => $rd['hotel_room_count_info_id'],
						'no_of_room_available' => $rd['no_of_room_available'],
						'no_of_room_booked' => '0',
						'adult' => $rd['adult'],
						'child' => $rd['child'],
						'status' => 'ACTIVE',

					);
		$this->db->insert('hotel_season_room_count_info', $data); 
	}

	function update_season_room_count_row($params)
	{
		// debug($params);
		$q = "
			UPDATE 
				hotel_season_room_count_info 
			SET 
				no_of_room = ".$params['no_of_room'].",
				no_of_room_available = ".$params['no_of_room'].",
				adult = ".$params['adult'].",
				child = ".$params['child']."
			WHERE 
				hotel_details_id = ".$params['hotel_details_id']." AND
				hotel_room_type_id = ".$params['hotel_room_type_id']." AND
				hotel_room_count_info_id = ".$params['hotel_room_count_info_id'];
// debug($q);die;

		return $this->db->query($q);

	}

	function delete_cancellation_policy($policy_id = ""){		
		$this->db->where('hotel_cancellation_id',$policy_id);		
		$this->db->delete('hotel_cancellation_policy');		
		return $this->db->last_query();
	}
	
	function inactive_seasons($seasons_details_id){
		$data = array(
					'status' => 'INACTIVE'
					);
		$this->db->where('seasons_details_id', $seasons_details_id);
		$this->db->update('seasons_details', $data);
		
	}
	
	function active_seasons($seasons_details_id){
		$data = array(
					'status' => 'ACTIVE'
					);
		$this->db->where('seasons_details_id', $seasons_details_id);
		$this->db->update('seasons_details', $data);
		
	}
	function delete_seasons($seasons_details_id){
		$this->db->where('seasons_details_id', $seasons_details_id);
		$this->db->delete('seasons_details');
		
	}
	
	function update_seasons($input,$seasons_details_id){		
		/*if(!isset($input['status']))
			$input['status'] = "ACTIVE";	*/

		if($input['seasons_date_range'] != ''){
			$date_range_array = explode("-", $input['seasons_date_range']);

			//My Code
			$fromorderdate = explode('/', $date_range_array[0]);
			
				$monthfrom = $fromorderdate[1];
				$dayfrom   = $fromorderdate[0];
				$yearfrom  = $fromorderdate[2];
		 	$fromdate = $monthfrom.'/'.$dayfrom.'/'.$yearfrom;

		 	$toorderdate = explode('/', trim($date_range_array[1]));
			
				$monthto = $toorderdate[1];
				$dayto   = $toorderdate[0];
				$yearto  = $toorderdate[2];
		 	$todate = $monthto.'/'.$dayto.'/'.$yearto;

			//End My code
			$from_date = date_format(date_create(trim($fromdate)), 'Y-m-d');
			$to_date = date_format(date_create(trim($todate)), 'Y-m-d');
			//$from_date = date_format(date_create(trim($date_range_array[0])), 'Y-m-d');
			//$to_date = date_format(date_create(trim($date_range_array[1])), 'Y-m-d');
			
		}
		//echo $from_date."--".$to_date;print_r($input);exit();
		$insert_data = array(
							'hotel_details_id'			=> $input['hotel_details_id'],
							'hotel_room_type_id'	    => $input['room_type'],
							'seasons_from_date'			=> $from_date,
							'seasons_to_date'			=> $to_date,
							'seasons_name' 				=> $input['seasons_name'],
							//'cutoff'   					=> $input['cutoff'],
							'minimum_stays'				=> $input['minimum_stays'],															
							//'season_type'				=> $input['season_type']													
						);
	  // try{			
			$this->db->where('seasons_details_id', $seasons_details_id);
			$this->db->update('seasons_details', $insert_data);
			
		/*} catch(Exception $e) {
			return $e;
		}*/

		/*if(sizeof($input['cancellation_from']) > 0){
		 for($i=0; $i<count($input['cancellation_from']); $i++) {
				$data = array(	
				            'hotel_details_id' => $input['hotel_details_id'],
				            'hotel_room_type_id' => $input['room_type'],
				            'session_detail_id' => $seasons_details_id,
							'cancellation_from' => $input['cancellation_from'][$i],
							'cancellation_to' => $input['cancellation_to'][$i],
							'cancellation_night_charge' => $input['cancellation_nightcharge'][$i],
							'cancellation_percentage_charge' => $input['cancellation_percentage'][$i],
							'creation_date'			=> (date('Y-m-d H:i:s')),
							'cancellation_daterange' => $input['seasons_date_range']														
				);		

              if($input['cancellation_policy_id'][$i] != ""){  
                //echo  $input['cancellation_policy_id'][$i]."<br>";           	
              	$this->db->select('*');
			    $this->db->where('session_detail_id',$seasons_details_id);
			    $this->db->where('hotel_cancellation_id',$input['cancellation_policy_id'][$i]);
			    $query = $this->db->get('hotel_cancellation_policy');
			    //echo $this->db->last_query();
			    if($query->num_rows() > 0){			    	
			    	$this->db->where('hotel_cancellation_id',$input['cancellation_policy_id'][$i]);
			    	$this->db->update('hotel_cancellation_policy', $data); 
			    }
			    else{
			      $this->db->insert('hotel_cancellation_policy', $data); 		
			    }
              }
			  else{ 			    
				$this->db->insert('hotel_cancellation_policy', $data); 
			  }	
		    }//for			
		}	*/
		
	}

	function getAdultChildCount($room_type_id){
   	  $this->db->select('*');
   	  $this->db->where('hotel_room_type_id', $room_type_id);	  
   	 
	  $query=$this->db->get('hotel_room_type');		
	  if($query->num_rows() ==''){
			return '';
	  }else{
			return $query->result();
	  }
   }
	
}
