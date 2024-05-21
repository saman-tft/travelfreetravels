<?php
class Package_Model extends CI_Model {
	public function __construct(){
		parent::__construct();
	}
	public function getAllPackages(){
		$this->db->select('*');
		$this->db->where('status', '1');
		$query = $this->db->get('package');
		if ( $query->num_rows > 0 ) {
			return $query->result();
		}else{
			return array();
		}
	}

	/**
	 *@param Top Destination Packages
	 */
	public function get_package_top_destination()
	{
		$this->db->select('*');
		$this->db->where('top_destination',ACTIVE);
		$query = $this->db->get('package');
		if ( $query->num_rows > 0 ) {
			$data['data'] = $query->result();
			$data['total'] = $query->num_rows;
			return $data;
		}else{
			return array('data' => '', 'total' => 0);
		}
	}
	public function getPageCaption($page_name) {
		$this->db->where('page_name', $page_name);
		return $this->db->get('page_captions');
	}
	public function get_contact(){
		$contact = $this->db->get('contact_details');
		return $contact->row();
	}
	/**
	 *get country name
	 **/
	public function getCountryName($id){
		$this->db->select("*");
		$this->db->from("country");
		$this->db->where('country_id',$id);
		$query=$this->db->get();
		if($query->num_rows()){
			return $query->row();
		}else{
			return array();
		}
	}

	/**
	 * get package itinerary
	 */
	public function getPackageItinerary($package_id){
		$this->db->select("*");
		$this->db->from("activity_itinerary");
		$this->db->where('package_id',$package_id);
		$this->db->order_by('day','ASC');
		$query=$this->db->get();
		if($query->num_rows()){
			return $query->result();
		}else{
			return array();
		}
	}
	public function get_package_top_destination_home(){

		$query = 'SELECT *, t1.id as id, t1.inclusions_checks as inclusions_checks,tc.name as country,AA.adult_airliner_price,AA.child_airliner_price FROM tours AS t1 

					LEFT JOIN tours_itinerary AS ti ON ti.tour_id=t1.id 

					LEFT JOIN tours_country AS tc ON t1.tours_country=tc.id 

					LEFT JOIN tour_price_management AS AA  ON AA.tour_id=t1.id 

					WHERE 

					 t1.expire_date>"'.date('Y-m-d').'" 

					AND ti.publish_status=1 

					AND t1.status=1

					AND t1.home_status=6
					AND t1.home_page_status=1

					GROUP BY t1.id  order by t1.id DESC';

		 $query = $this->db->query($query);



		if ( $query->num_rows > 0 ) {

			$data['data'] = $query->result();

			$data['total'] = $query->num_rows;


			return $data;

		}else{

			return array('data' => '', 'total' => 0);

		}

	}
	/**
	 * get package pricing policy
	 */
	public function getPackagePricePolicy($package_id){
		$this->db->select("*");
		$this->db->from("activity_pricing_policy");
		$this->db->where('package_id',$package_id);
		$query=$this->db->get();
		if($query->num_rows()){
			return $query->row();
		}else{
			return array();
		}
	}

	/**
	 * get package traveller photos
	 */
	public function getTravellerPhotos($package_id){
		$this->db->select("*");
		$this->db->from("activity_traveller_photos");
		$this->db->where('package_id',$package_id);
		$this->db->where('status','1');
		$query=$this->db->get();
		if($query->num_rows()){
			return $query->result();
		}else{
			return array();
		}
	}
	/*8
	 * get getPackageCancelPolicy
	 */
	public function getPackageCancelPolicy($package_id){
		$this->db->select("*");
		$this->db->from("activity_cancellation");
		$this->db->where('package_id',$package_id);
		$query=$this->db->get();
		if($query->num_rows()){
			return $query->row();
		}else{
			return array();
		}
	}
	/**
	 * getPackage
	 */
public function getPackage($package_id){
		$this->db->select("*");
		$this->db->from("activity");
		$this->db->where('package_id',$package_id);
		$query=$this->db->get();
		if($query->num_rows()){
			return $query->row();
		}else{
			return array();
		}
	}


    public function getamenities($val){
   		$this->db->select("activity_amenties");
		$this->db->from("activity_amenties"); 
		$this->db->where('id',$val);
		$query=$this->db->get();
		if($query->num_rows()){
			return $query->result();
		}else{
			return array();
		}
   }

	public function getPackageCountries_new(){
    	$data = 'select C.name AS country_name, C.country_id as country_id FROM country C';
    
    	return $this->db->query($data)->result();
    	/*$this->db->select('package_country');
    	 $this->db->from('package'); 
    	 $this->db->group_by('package_country'); 
		$query = $this->db->get();
		if($query->num_rows()){
			return $query->result();
		}else{
			return array();
		}*/
    }

	public function saveEnquiry($data){
		$this->db->insert('package_enquiry',$data);
		return $this->db->insert_id();
	}

	public function getPackageCountries(){
		$data = 'select package_country, C.name AS country_name FROM package P, country C WHERE P.package_country=C.country_id';
    	return $this->db->query($data)->result();
	}
	public function gerEnquiryPackages($user_id){
		$data = 'select * from package_enquiry WHERE user_id='.$user_id;
    	return $this->db->query($data)->result();
	}
	public function getPackageTypes(){
		$this->db->select("*");
		$this->db->from("package_types");
		$query=$this->db->get();
		if($query->num_rows()){
			return $query->result();
		}else{
			return array();
		}
	}
	    public function getPackageTypes2(){

    	$this->db->select("*");

    	$this->db->where('status',ACTIVE);

    	$this->db->from("tour_subtheme");

		$query=$this->db->get();

		if($query->num_rows()){

			return $query->result();

		}else{

			return array();

		}

    }
	public function search($c,$p,$d,$b,$dmn_list_fk){
		$this->db->select("*");
		$this->db->from("package");
		$this->db->like('package_country', $c,'both');
		$this->db->like('package_type', $p,'both');
		if($d){
			$this->db->where($d);
		}else{
			$this->db->like('duration', $d,'both');
		}
		if($b){
			$this->db->where($b);
		}else{
			$this->db->like('price', $b,'both');
		}
		$this->db->where('domain_list_fk',$dmn_list_fk);
		$query=$this->db->get();
		//echo $this->db->last_query();
		//exit;
		if($query->num_rows()){
			return $query->result();
		}else{
			return array();
		}
	}


	function add_user_rating($arr_data)
	{
		$pkg_id=$arr_data['package_id'];
		$res=$this->db->insert('package_rating',$arr_data);

		if($res==true){
			 
			$this->db->select('rating');
			$this->db->where('package_id',$pkg_id);
			$res1=$this->db->get('package_rating');
			if($res1->num_rows()>0)
			{  // print_r($res1);
				$tot_no=count($res1->result());
				$results=$res1->result();
				//   sum=0;
				foreach($results as $r)
				{
					$sum+=$r->rating;
				}
				$rating=$sum/$tot_no;

				$da=array('rating'=> ceil($rating));
				$this->db->where('package_id',$pkg_id);
				$this->db->update('package',$da);

			}
			 
		}

	}


	  public function getPackageCRS($package_id){
   		$this->db->select("*");
		$this->db->from("activity"); 
		$this->db->where('package_id',$package_id);
		$query=$this->db->get();
		if($query->num_rows()){
			return $query->result();
		}else{
			return array();
		}
   }

public function getnationalityTypes(){
		$this->db->select("currency");
		$this->db->from("tour_price_management");
		$query=$this->db->get();
		if($query->num_rows()){
			return $query->result();
		}else{
			return array();
		}
	}
		public function flight_get_package_top_destination(){
		$query = 'SELECT *, t1.id as origin,t1.banner_image as image, t1.inclusions_checks as inclusions_checks,tc.name as country,AA.adult_airliner_price,AA.child_airliner_price FROM tours AS t1 
					LEFT JOIN tours_itinerary AS ti ON ti.tour_id=t1.id 
					LEFT JOIN tours_country AS tc ON t1.tours_country=tc.id 
					LEFT JOIN tour_price_management AS AA  ON AA.tour_id=t1.id 
					WHERE 
					 t1.expire_date>"'.date('Y-m-d').'" 
					AND ti.publish_status=1 
					AND t1.status=1
					AND t1.home_status=1
					GROUP BY t1.id  order by t1.id DESC LIMIT 15';
		 $data = $this->db->query($query)->result_array();
		 return $data;

		
	}
	public function hotel_get_package_top_destination(){
		$query = 'SELECT *, t1.id as origin,t1.banner_image as image, t1.inclusions_checks as inclusions_checks,tc.name as country,AA.adult_airliner_price,AA.child_airliner_price FROM tours AS t1 
					LEFT JOIN tours_itinerary AS ti ON ti.tour_id=t1.id 
					LEFT JOIN tours_country AS tc ON t1.tours_country=tc.id 
					LEFT JOIN tour_price_management AS AA  ON AA.tour_id=t1.id 
					WHERE 
					 t1.expire_date>"'.date('Y-m-d').'" 
					AND ti.publish_status=1 
					AND t1.status=1
					AND t1.home_status=2
					GROUP BY t1.id  order by t1.id DESC LIMIT 15';
		 $data = $this->db->query($query)->result_array();
		 return $data;

		
	}

	public function transfer_get_package_top_destination(){
		$query = 'SELECT *, t1.id as origin,t1.banner_image as image_transfers, t1.inclusions_checks as inclusions_checks,tc.name as country,AA.adult_airliner_price,AA.child_airliner_price FROM tours AS t1 
					LEFT JOIN tours_itinerary AS ti ON ti.tour_id=t1.id 
					LEFT JOIN tours_country AS tc ON t1.tours_country=tc.id 
					LEFT JOIN tour_price_management AS AA  ON AA.tour_id=t1.id 
					WHERE 
					 t1.expire_date>"'.date('Y-m-d').'" 
					AND ti.publish_status=1 
					AND t1.status=1
					AND t1.home_status=3
					GROUP BY t1.id  order by t1.id DESC LIMIT 15';
		 $data = $this->db->query($query)->result_array();
		 return $data;

	}

	public function car_get_package_top_destination(){
		$query = 'SELECT *, t1.id as origin,t1.banner_image as image, t1.inclusions_checks as inclusions_checks,tc.name as country,AA.adult_airliner_price,AA.child_airliner_price FROM tours AS t1 
					LEFT JOIN tours_itinerary AS ti ON ti.tour_id=t1.id 
					LEFT JOIN tours_country AS tc ON t1.tours_country=tc.id 
					LEFT JOIN tour_price_management AS AA  ON AA.tour_id=t1.id 
					WHERE 
					 t1.expire_date>"'.date('Y-m-d').'" 
					AND ti.publish_status=1 
					AND t1.status=1
					AND t1.home_status=4
					GROUP BY t1.id  order by t1.id DESC LIMIT 15';
		$data = $this->db->query($query)->result_array();
		 return $data;

	}

	public function car_get_package_top_destination_home(){
		$query = 'SELECT *, t1.id as origin,t1.banner_image as image, t1.inclusions_checks as inclusions_checks,tc.name as country,AA.adult_airliner_price,AA.child_airliner_price FROM tours AS t1 
					LEFT JOIN tours_itinerary AS ti ON ti.tour_id=t1.id 
					LEFT JOIN tours_country AS tc ON t1.tours_country=tc.id 
					LEFT JOIN tour_price_management AS AA  ON AA.tour_id=t1.id 
					WHERE 
					 t1.expire_date>"'.date('Y-m-d').'" 
					AND ti.publish_status=1 
					AND t1.status=1
					AND t1.home_status=4
					AND t1.home_page_status=1
					GROUP BY t1.id  order by t1.id DESC LIMIT 15';
		$data = $this->db->query($query)->result_array();
		 return $data;

	}

	public function activity_get_package_top_destination(){

		$query = 'SELECT *, t1.id as origin,t1.banner_image as image_activity, t1.inclusions_checks as inclusions_checks,tc.name as country,AA.adult_airliner_price,AA.child_airliner_price FROM tours AS t1 
					LEFT JOIN tours_itinerary AS ti ON ti.tour_id=t1.id 
					LEFT JOIN tours_country AS tc ON t1.tours_country=tc.id 
					LEFT JOIN tour_price_management AS AA  ON AA.tour_id=t1.id 
					WHERE 
					 t1.expire_date>"'.date('Y-m-d').'" 
					AND ti.publish_status=1 
					AND t1.status=1
					AND t1.home_status=5
					GROUP BY t1.id  order by t1.id DESC LIMIT 15';
		 $data = $this->db->query($query)->result_array();
		 
		 return $data;

	}

}
