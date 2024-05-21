<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 *
 * @package Provab - Provab Application
 * @subpackage Travel Portal
 * @author Arjun J<arjunjgowda260389@gmail.com> on 01-06-2015
 * @version V2
 */
class privatecar extends CI_Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'private_car_model');
		$this->load->model('car_model');
	}
	public function view_packages_types() {
		
		$data ['package_view_data'] = $this->private_car_model->package_view_data_types ()->result ();
		$this->template->view ( 'private-car/view_package_types', $data );
	}
		public function extra_services() {
		
		$data ['package_view_data'] = $this->private_car_model->extra_services ()->result ();
		$this->template->view ( 'private-car/extra_services', $data );
	}
	public function view_category_types() {
		
		$data ['package_view_data'] = $this->private_car_model->category_view_data_types ()->result ();
		$this->template->view ( 'private-car/view_category_types', $data );
	}
	public  function add_priced_coverage($id)
	{
	  $data ['package_view_data'] = $this->private_car_model->pricecover_view_data_types($id)->result ();
	  $data['id']=$id;
	  $this->template->view ( 'private-car/add_priced_coverage', $data );
	}
	public  function add_inclusion_list($id)
	{
	  $data ['package_view_data'] = $this->private_car_model->inlusionlist_view_data_types($id)->result ();
	  $data['id']=$id;
	  $this->template->view ( 'private-car/add_inclusion_list', $data );
	}
	public function view_car_types() {
		
		$data ['package_view_data'] = $this->private_car_model->car_view_data_types ()->result ();
		$this->template->view ( 'private-car/view_car_types', $data );
	}
	public function view_size_types() {
		
		$data ['package_view_data'] = $this->private_car_model->size_view_data_types ()->result ();
		$this->template->view ( 'private-car/view_size_types', $data );
	}
	public function delete_package_type($id) {
		$this->private_car_model->delete_package_type ( $id );
		redirect ( 'privatecar/view_packages_types' );
	}
		public function delete_extra_service($id) {
		$this->private_car_model->delete_exs ( $id );
		redirect ( 'privatecar/extra_services' );
	}
	public function delete_desc($id,$pid) {
	   
		$this->private_car_model->delete_desc ( $id );
		$id=$this->input->get('id');
		redirect ( 'privatecar/add_priced_coverage/'.$pid);
	}
	public function delete_category_type($id) {
		$this->private_car_model->delete_category_type ( $id );
		redirect ( 'privatecar/view_category_types' );
	}
	public function delete_car_type($id) {
		$this->private_car_model->delete_car_type ( $id );
		redirect ( 'privatecar/view_car_types' );
	}
	public function delete_size_type($id) {
		$this->private_car_model->delete_size_type ( $id );
		redirect ( 'privatecar/view_size_types' );
	}
	
	public function delete_traveller_img($pack_id,$img_id) {
		$this->private_car_model->delete_traveller_img ( $pack_id,$img_id );
		redirect ( 'privatecar/images/'.$img_id.'/w' );
	}
	public function delete_package($id) {
		$this->private_car_model->delete_package ( $id );
		redirect ( 'privatecar/view_with_price' );
	}
	public function add_package_type($id = '') {
		if ($id != '') {
			$data ['pack_data'] = $this->private_car_model->get_pack_id ( $id );
			$this->template->view ( 'private-car/add_package_type', $data );
		} else {
			$this->template->view ( 'private-car/add_package_type' );
		}
	}
	public function add_category_type($id = '') {
		if ($id != '') {
			$data ['pack_data'] = $this->private_car_model->get_cat_id ( $id );
			$this->template->view ( 'private-car/add_category_type', $data );
		} else {
			$this->template->view ( 'private-car/add_category_type' );
		}
	}
	public function add_extra_service($id = '') {
		if ($id != '') {
			$data ['pack_data'] = $this->private_car_model->get_exs_id ( $id );
			$this->template->view ( 'private-car/add_extras_service', $data );
		} else {
			$this->template->view ( 'private-car/add_extras_service' );
		}
	}
	public function add_car_type($id = '') {
		if ($id != '') {
			$data ['pack_data'] = $this->private_car_model->get_car_id ( $id );
			$this->template->view ( 'private-car/add_car_type', $data );
		} else {
			$this->template->view ( 'private-car/add_car_type' );
		}
	}
	public function edit_car($id = '') {
	   
	    $data=array();
	    $data ['package_type_data'] = $this->private_car_model->package_view_data_types ()->result ();
		$data ['VehicleCategoryName'] = $this->private_car_model->category_view_data_types ()->result ();
		$data ['VehClassSizeName'] = $this->private_car_model->size_view_data_types ()->result ();
		$data ['Vendor'] = $this->private_car_model->car_view_data_types ()->result ();
       	$data ['pricequip'] = $this->private_car_model->extra_services ()->result ();
		// debug($data ['package_type_data']);exit();
		$data ["country"] = $this->private_car_model->get_countries ();
	
		if ($id != '') {
		   $data ['pid']=$id;
			$data ['pack_data'] = $this->private_car_model->get_car_edit ( $id );
		// debug($data ['pid']);die;
			$this->template->view ( 'private-car/add_package', $data );
		} else {
				$this->template->view ( 'private-car/add_package', $data );
		}
	}
	public function add_size_type($id = '') {
		if ($id != '') {
			$data ['pack_data'] = $this->private_car_model->get_size_id ( $id );
			$this->template->view ( 'private-car/add_size_type', $data );
		} else {
			$this->template->view ( 'private-car/add_size_type' );
		}
	}

	public function save_packages_type_old() {
		$pack_id = $this->input->post ( 'package_types_id' );
		// debug($pack_id);exit();
		$package_name = $this->input->post ( 'name' );
		$domain_list_fk = get_domain_auth_id ();
		// echo "<pre>";print_r($domain_list_fk);exit;
		$module_type="private-car";
		$add_package_data = array (
				'package_types_name' => $package_name,
				'domain_list_fk' => $domain_list_fk,
				'module_type' => $module_type 
		);

		$this->db->where("package_types_name",$package_name );
		$this->db->where("module_type",$module_type );
		$qur = $this->db->get ("package_types");
		$count=$qur->num_rows();
 // debug($count);exit();
		// if ($pack_id > 0) 
		// {
			
		// 	if($count<1)
		// 	{
		//   		 $this->Supplierpackage_Model->update_package_type ( $add_package_data, $pack_id );
		// 	}
		// } 
		// else 
		// { 
		// 	if($count<1)
		// 	{
		// 		$this->db->insert ("package_types",$add_package_data );
		// 		$this->session->set_flashdata(array('message' => 'UL0014', 'type' => SUCCESS_MESSAGE));
		// 		redirect ( 'private-car/view_packages_types' );
		// 	} else{
		// 		$this->session->set_flashdata('error_message', 'Duplicate data!!');
		// 		redirect ( 'private-car/view_packages_types' );
		// 	}			
		// }
		if($count<1)
		{


$this->db->where('package_types_id', $pack_id);
$this->db->update('package_types', $add_package_data);
$this->session->set_flashdata(array('message' => 'UL0013', 'type' => SUCCESS_MESSAGE));
redirect ( 'private-car/view_packages_types' );
		}
		else{
		$this->session->set_flashdata(array('message' => 'UL0098', 'type' => ERROR_MESSAGE));

redirect ( 'privatecar/add_package_type/'.$pack_id );
		}
		
	}


	public function save_packages_type() {
		$pack_id = $this->input->post ( 'package_id' );
		$package_name = $this->input->post ( 'name' );
		$domain_list_fk = get_domain_auth_id ();
		$module_type="private-car";
		$add_package_data = array (
				'package_name' => $package_name,
				'created_by_id' => $domain_list_fk, 
				'created_datetime' =>date('Y-m-d')
		);
		if ($pack_id > 0) {
			$this->private_car_model->update_package_type ( $add_package_data, $pack_id );
		} else {
			$this->db->insert ( "crs_car_package_type", $add_package_data );
		}
		redirect ( 'privatecar/view_packages_types' );
	}
		public function save_extra_service() {
		$pack_id = $this->input->post ( 'Equipid' );
		$package_name = $this->input->post ( 'name' );
		$domain_list_fk = get_domain_auth_id ();
		$module_type="private-car";
		if($this->input->post ( 'PolicyName' )=="Add Driver")
		{
		    $pn=$this->input->post ( 'PolicyName' );
		    $name="add_driver";
		    $equip=222;
		}
			if($this->input->post ( 'PolicyName' )=="Full Protection")
		{
		     $pn=$this->input->post ( 'PolicyName' );
		      $name="full_prot";
		    $equip=413;
		}
			if($this->input->post ( 'PolicyName' )=="Add Driver")
		{
		     $pn=$this->input->post ( 'PolicyName' );
		      $name="snow";
		    $equip=10;
		}
			if($this->input->post ( 'PolicyName' )=="Booster Seat (4-12 years)")
		{
		     $pn=$this->input->post ( 'PolicyName' );
		      $name="Booster";
		    $equip=42;
		}
			if($this->input->post ( 'PolicyName' )=="Child Seat (1-3 years)")
		{
		     $pn=$this->input->post ( 'PolicyName' );
		      $name="Child";
		    $equip=8;
		}
	
			if($this->input->post ( 'PolicyName')=="Infant")
		{
		   // $this->input->post ( 'PolicyName')="Infant Seat (0-1 year)";
		    $pn="Infant Seat (0-1 year)";
		      $name="Infant";
		    $equip=7;
		}
			if($this->input->post ( 'PolicyName' )=="GPS (Global Positioning System)")
		{
		     $pn=$this->input->post ( 'PolicyName' );
		      $name="gps";
		    $equip=13;
		}
		$add_package_data = array (
				'PolicyName' =>$pn,
			     'name' =>$name,
			     'Amount' =>$this->input->post ( 'Amount' ),
			     'DetailedInformation' =>$this->input->post ( 'DetailedInformation' ),
			    'EquipType' =>$equip,
			     'Underwriter' =>$this->input->post ( 'Underwriter' ),
			     'PolicyUrl' => $this->input->post ( 'PolicyUrl' ),
			     'Disclaimer' =>$this->input->post ( 'Disclaimer' ),
			     'InsuranceSupplier' => $this->input->post ( 'InsuranceSupplier' ),
				'created_datetime' =>date('Y-m-d')
		);
		if ($pack_id > 0) {
			$this->private_car_model->update_pricedequip( $add_package_data, $pack_id );
		} else {
			$this->db->insert ( "crs_car_pricedequip", $add_package_data );
		}
		redirect ( 'privatecar/extra_services' );
	}
		public function save_inclusion_list() {
		$pack_id = $this->input->post ( 'package_id' );
		$desc_id = $this->input->post ( 'desc_id' );
		$CoverageType = $this->input->post ( 'title' );
		$Desscription = $this->input->post ( 'content' );
	
		$add_package_data = array (
				'title' => $CoverageType,
				'content' => $Desscription,
				'equipid' => $pack_id,
				'created_at' =>date('Y-m-d')
		);
		if ($desc_id > 0) {
			$this->private_car_model->update_inclusion_list( $add_package_data, $desc_id );
		} else {
			$this->db->insert ( "priceequip_inclusionlist", $add_package_data );
		}
		redirect ( 'privatecar/add_inclusion_list/'.$pack_id);
	}
	public function save_packages_desc() {
		$pack_id = $this->input->post ( 'package_id' );
		$desc_id = $this->input->post ( 'desc_id' );
		$CoverageType = $this->input->post ( 'CoverageType' );
		$Desscription = $this->input->post ( 'Description' );
		$inclusion_exclusion_status = $this->input->post('inclusion_exclusion_status');
		$domain_list_fk = get_domain_auth_id ();
		$module_type="private-car";
		$add_package_data = array (
				'CoverageType' => $CoverageType,
				'Description' => $Desscription,
				 'Code' => rand(10,100),
				'Currency' => "USD",
				'Amount' => 0,
				'package_id' => $pack_id,
				'inclusion_exclusion_status' => $inclusion_exclusion_status,
				'created_by_id' => $domain_list_fk, 
				'created_datetime' =>date('Y-m-d')
		);
		if ($desc_id > 0) {
			$this->private_car_model->update_package_desc ( $add_package_data, $desc_id );
		} else {
			$this->db->insert ( "crs_car_package_desc", $add_package_data );
		}
		redirect ( 'privatecar/add_priced_coverage/'.$pack_id);
	}
	public function save_category_type() {
	    
		$pack_id = $this->input->post ( 'category_id' );
		$package_name = $this->input->post ( 'name' );
		$domain_list_fk = get_domain_auth_id ();
		$module_type="private-car";
		$add_package_data = array (
				'category_name' => $package_name,
					'maximium_age' =>$this->input->post ( 'maximium_age' ),
						'minimium_age' =>$this->input->post ( 'minimium_age' ),
						'Young_driver_charge' =>$this->input->post ( 'young_driver' ),
							'Senior_driver_charge' =>$this->input->post ( 'senior_driver' ),
		     	'created_by_id' => $domain_list_fk, 
				'created_at' =>date('Y-m-d')
		);
		if ($pack_id > 0) {
			$this->private_car_model->update_category_type ( $add_package_data, $pack_id );
		} else {
			$this->db->insert ( "crs_car_category", $add_package_data );
		}
		redirect ( 'privatecar/view_category_types' );
	}
	public function save_car_type() {
		$pack_id = $this->input->post ( 'vendorid' );
		$package_name = $this->input->post ( 'name' );
		$email = $this->input->post ( 'email' );
		$phone = $this->input->post ( 'phone' );
		$rule = $this->input->post ( 'pre-paymentRule' );
		$domain_list_fk = get_domain_auth_id ();
		$module_type="private-car";
			$logo = $_FILES;
		$cpt1 = count ( $_FILES ['logo'] ['name'] );
		if ($_FILES ['logo']['name'] != '') {
			$_FILES ['logo'] ['name'] = $logo ['logo'] ['name'];
			$_FILES ['logo'] ['type'] = $logo ['logo'] ['type'];
			$_FILES ['logo'] ['tmp_name'] = $logo ['logo'] ['tmp_name'];
			$_FILES ['logo'] ['error'] = $logo ['logo'] ['error'];
			$_FILES ['logo'] ['size'] = $logo ['logo'] ['size'];
			
			$string = $_FILES ['logo'] ['name'];
			$exe = explode(".",$string);
			$logo = date('dmYhis').'.'.$exe[1];
			// move_uploaded_file ( $_FILES ['photo'] ['tmp_name'], $this->template->domain_uploads_packages ( $_FILES ['photo'] ['name'] ) );
			move_uploaded_file ( $_FILES ['logo'] ['tmp_name'], $this->template->domain_uploads_packages ($logo) );
		}
		$Vendor = $_FILES;
		$cpt1 = count ( $_FILES ['Vendor'] ['name'] );
		if ($_FILES ['Vendor'] ['name'] != '') {
			$_FILES ['Vendor'] ['name'] = $Vendor ['Vendor'] ['name'];
			$_FILES ['Vendor'] ['type'] = $Vendor ['Vendor'] ['type'];
			$_FILES ['Vendor'] ['tmp_name'] = $Vendor ['Vendor'] ['tmp_name'];
			$_FILES ['Vendor'] ['error'] = $Vendor ['Vendor'] ['error'];
			$_FILES ['Vendor'] ['size'] = $Vendor ['Vendor'] ['size'];
			
			$string = $_FILES ['Vendor'] ['name'];
			$exe = explode(".",$string);
			$Vendor = date('dmYhis').'.'.$exe[1];
			// move_uploaded_file ( $_FILES ['photo'] ['tmp_name'], $this->template->domain_uploads_packages ( $_FILES ['photo'] ['name'] ) );
			move_uploaded_file ( $_FILES ['Vendor'] ['tmp_name'], $this->template->domain_uploads_packages ($Vendor) );
		}
		$add_package_data = array (
				'name' => $package_name,
				'email' => $email,
				'phone' => $phone,
				'termsconditions' => $Vendor,
				'prepaymentRule' => $rule,
				'logo' => $logo,
				'created_at' =>date('Y-m-d')
		);
		if ($pack_id > 0) {
			$this->private_car_model->update_car_type ( $add_package_data, $pack_id );
		} else {
			$this->db->insert ( "crs_car_vendor", $add_package_data );
		}
		redirect ( 'privatecar/view_car_types' );
	}
	public function save_size_type() {
		$pack_id = $this->input->post ( 'c_size_id' );
		$package_name = $this->input->post ( 'name' );
		$domain_list_fk = get_domain_auth_id ();
		$module_type="private-car";
		$add_package_data = array (
				'car_size' => $package_name,
		     	'created_by_id' => $domain_list_fk, 
				'created_at' =>date('Y-m-d')
		);
		if ($pack_id > 0) {
			$this->private_car_model->update_size_type ( $add_package_data, $pack_id );
		} else {
			$this->db->insert ( "crs_car_size", $add_package_data );
		}
		redirect ( 'privatecar/view_size_types' );
	}
	public function add_with_price() 
	{
		
		// error_reporting(E_ALL);
		$data ['package_type_data'] = $this->private_car_model->package_view_data_types ()->result ();
		$data ['VehicleCategoryName'] = $this->private_car_model->category_view_data_types ()->result ();
		$data ['VehClassSizeName'] = $this->private_car_model->size_view_data_types ()->result ();
		$data ['Vendor'] = $this->private_car_model->car_view_data_types ()->result ();
       	$data ['pricequip'] = $this->private_car_model->extra_services ()->result ();
		// debug($data ['package_type_data']);exit();
		$data ["country"] = $this->private_car_model->get_countries ();
		$this->template->view ( 'private-car/add_package', $data );
	}
	public function get_countries() {
		$this->db->limit ( 1000 );
		$this->db->order_by ( "name", "asc" );
		$qur = $this->db->get ( "country" );
		return $qur->result ();
	}
		public function update_package_new() {
	  
		$photo = $_FILES;
		$cpt1 = count ( $_FILES ['photo'] ['name'] );
		if ($_FILES ['photo'] ['name'] != '') {
			$_FILES ['photo'] ['name'] = $photo ['photo'] ['name'];
			$_FILES ['photo'] ['type'] = $photo ['photo'] ['type'];
			$_FILES ['photo'] ['tmp_name'] = $photo ['photo'] ['tmp_name'];
			$_FILES ['photo'] ['error'] = $photo ['photo'] ['error'];
			$_FILES ['photo'] ['size'] = $photo ['photo'] ['size'];
			
			$string = $_FILES ['photo'] ['name'];
			//debug($string);die;
			if(!empty($string))
			{
			$exe = explode(".",$string);
			$photo = date('dmYhis').'.'.$exe[1];
			
			// move_uploaded_file ( $_FILES ['photo'] ['tmp_name'], $this->template->domain_uploads_packages ( $_FILES ['photo'] ['name'] ) );
			move_uploaded_file ( $_FILES ['photo'] ['tmp_name'], $this->template->domain_uploads_packages ($photo) );
			}
		}
		$pack_id=$this->input->post('packagetype');
	    $category_id=$this->input->post('VehicleCategoryName');
	    $equip_id=array();
	    $equip_id=$this->input->post('extraservices');
	$data ['package_type_data'] = $this->private_car_model->get_package_type_data($pack_id)->result ();
	$data ['category_data'] = $this->private_car_model->get_category_view_data_types($category_id)->result ();
		$data ['country'] = $this->private_car_model->get_crs_city_listname($this->input->post('country'))->result ();
	//	debug($data ['country']);die;
     $pricequipformated=array();
      $pricecoverage=array();
     
      $cancellationpolicy=array();
	for($i=0;$i<count($equip_id);$i++)
	{
	     
	     $data['equipdata']= $this->private_car_model->get_exs_id($equip_id[$i]);
	     
	     $data['inclusionlist']=$this->private_car_model->get_priceequip_inclusionlist($data['equipdata'][0]->Equipid)->result ();
	     $incllistformmated=array();
	     for($j=0;$j<count($data['inclusionlist']);$j++)
	     {
    	    $incllist=array(
    	         "Title"=>$data['inclusionlist'][$j]->title,
    	         "Content"=>$data['inclusionlist'][$j]->content
    	       );
    	       array_push($incllistformmated,$incllist);
	    }
	    	
	     
	     
	    $policydesc=array(
	       
	         "PolicyName"=>$data['equipdata'][0]->PolicyName,
	         "Description"=>$data['equipdata'][0]->DetailedInformation,
	         "DetailedInformation"=>$data['inclusionlist'][$i]->DetailedInformation,
	         "InclusionsList"=>json_encode($incllistformmated),
	         "PolicyUrl"=>$data['equipdata'][0]->PolicyUrl,
	         "Disclaimer"=>$data['equipdata'][0]->Disclaimer,
	         "InsuranceSupplier"=>$data['equipdata'][0]->InsuranceSupplier,
	         "Underwriter"=>$data['equipdata'][0]->Underwriter,
	         
	   );
	    $pricequip=array(
	         "Description"=> $data['equipdata'][0]->PolicyName,
             "EquipType"=> $data['equipdata'][0]->Equipid,
             "CurrencyCode"=> "USD",
             "name"=> $data['equipdata'][0]->name,
              "Amount"=> $data['equipdata'][0]->Amount,
             "policy_description"=>$policydesc 
	       
	       );
	        array_push($pricequipformated,$pricequip);
	    
	}

	$age=array(
	         "MinimumAge"=>$data ['category_data'][0]->minimium_age,
             "MaximumAge"=>$data ['category_data'][0]->maximium_age,
             "NoShowFeeInd"=>"false"
	   );

	$data ['package_coverage'] = $this->private_car_model->pricecover_view_data_types($data ['package_type_data'][0]->package_id)->result ();
	for($i=0;$i<count($data ['package_coverage']);$i++)
	{
	  $pricecover=array(
	            "Code"=>$data ['package_coverage'][$i]->desc_id,
                "CoverageType"=>$data ['package_coverage'][$i]->CoverageType,
                "Currency"=>"USD",
                "Amount"=>$data ['package_coverage'][$i]->Amount,
                "Desscription"=>$data ['package_coverage'][$i]->Description,
                "IncludedInRate"=>"true"
	    );
	      array_push($pricecoverage,$pricecover);
	}

		$module_type="private-car";

	     $paymentrules=array(
	         "PaymentRule"=>"21",
             "PaymentType"=>"4"
	     );
	      $TPAExt=array(
	       "TermsConditions"=>"https://static.carhire-solutions.com/pdf/cnx_tac_en-gb.pdf",
           "SupplierLogo"=>"https://static.carhire-solutions.com/images/supplier/logo/logo36.png"
	     );
	    
	   
	    $pricebreak=array(
	        "RentalPrice"=>$this->input->post('RentalPrice'),
            "OnewayFee"=>0,
            "OtherTaxes"=>0,
            "YoungDriverAmount"=>$data ['category_data'][0]->Young_driver_charge,
            "SeniorDriverAmount"=>$data ['category_data'][0]->Young_driver_charge
	   );
	   $totalbreakup=array(
	      "EstimatedTotalAmount"=>$this->input->post('RentalPrice'),
          "Pricebreakup"=>$pricebreak,
          "CurrencyCode"=>"USD",    
	   );
	   
	   $canel_start_data=$this->input->post('cancel_start_date');
	   $canel_end_data=$this->input->post('cancel_end_date');
	   $camount=$this->input->post('camount');
	 for($i=0;$i<count($canel_start_data);$i++)
	{
	   $cancel=array(
	       "FromDate"=>$canel_start_data[$i],
            "ToDate"=>$canel_end_data[$i],
            "CurrencyCode"=>"USD",
            "Amount"=>$camount[$i]
	       );
	       array_push($cancellationpolicy,$cancel);
	}
	
	   $operationschedules=array();
	   $time=$this->input->post('days');
	   for($i=0;$i<count($time);$i++)
	{
	   $openinghours=array(
	        "Day"=>$time[$i],
               "Start"=>$this->input->post('start_time'),
            "End"=>$this->input->post('end_time')
	   );
	   array_push($operationschedules,$openinghours);
	}
	   $scheduletime=array(
	        "Start"=>$this->input->post('start_time'),
            "End"=>$this->input->post('end_time')
	   );
	 
	   $additionalinfo=array
	   (
	     "ParkLocation"=> "Airport Location, please follow signs to the car rental stations.",
	     "OpeningHours"=>json_encode($operationschedules),
	    "OperationSchedules"=>json_encode($scheduletime),
	   
	   );
	   $pickup=array();
	   $pick=$this->input->post('car_to');
	   for($i=0;$i<count($pick);$i++)
	{
	     $location=array(
	      "StreetNmbr"=> $pick,
	      'CountryName' =>$data ['country'][0]->country_name,
				'CityName' =>$this->input->post('cityname'),
         "PostalCode"=>$this->input->post('PickPostalCode')[$i]
	   );
	   array_push($pickup,$location);
	}
	    $drop=$this->input->post('drop_to');
	    $dropup=array();
	   for($i=0;$i<count($drop);$i++)
	{
	   $location=array(
	      "StreetNmbr"=>$drop[$i],
	      'CountryName' =>$data ['country'][0]->country_name,
				'CityName' =>$this->input->post('cityname'),
         "PostalCode"=>$this->input->post('DropPostalCode')[$i]
	   );
	   array_push($dropup,$location);
	}

	$locationdetail=array(
	       "PickUpLocation"=>$pickup,
	       "DropLocation"=>$dropup
	    );
	//  debug($locationdetail);die;
		$newpackage = array (
				'carcode' =>rand(),
				'Status' =>true,
				'TransmissionType' =>$this->input->post('TransmissionType'),
				'FuelType' =>$this->input->post('FuelType'),
				'PassengerQuantity' =>$this->input->post('PassengerQuantity'),
				'BaggageQuantity' =>$this->input->post('BaggageQuantity'),
				'VendorCarType' =>$this->input->post('VehicleCategoryName'),
				'VehicleCategoryName' =>$data['category_data'][0]->category_name,
				'DoorCount' =>$this->input->post('DoorCount'),
				'rentalprice' =>$this->input->post('RentalPrice'),
				'VehClassSizeName' =>$this->input->post('VehClassSizeName'),
				'Name' =>$this->input->post('VehClassSizeName'),
				'AirConditionInd' =>$this->input->post('AirConditionInd'),
				'pickuplocation' =>$this->input->post('car_to'),
				'dropuplocation' =>json_encode($this->input->post('drop_to')),
				'LocationDetails' =>json_encode($locationdetail),
				
				'Unlimited' => $this->input->post('Mileage_Allowance'),
				'DistUnitName' =>  "Mile",
				'RateComments' =>$data ['package_type_data'][0]->package_name,
				'RateRestrictions' =>json_encode($age),
				'reference_url' => null,
				'Vendor' =>"Test",
				'PaymentRules' =>json_encode($paymentrules),
				'CancellationPolicy' =>json_encode($cancellationpolicy),
				'TPA_Extensions' =>json_encode($TPAExt),
				'TotalCharge' =>json_encode($totalbreakup),
				'PricedCoverage' =>json_encode($pricecoverage),
				'OperationSchedules' =>json_encode($scheduletime),
				'PricedEquip' =>json_encode($pricequipformated),
				'AdditionalInfo' =>json_encode($additionalinfo),
				'country' =>$data ['country'][0]->country_name,
				'city' =>$this->input->post('cityname'),
				'state' =>$this->input->post('cityname'),
				'created_by_id' =>$this->entity_user_id,
				'created_datetime' =>date("Y-m-d")
				

		);
		if($_FILES ['photo'] ['name'] != '')
		{
		  // debug($photo);die;
		    $newpackage['PictureURL']=$photo;
		}
	   $pid=$this->input->post('pid');
		$package = $this->private_car_model->update_new_car($newpackage,$pid);

		redirect ( 'privatecar/view_with_price' );
	}
	public function add_package_new() {
	  
		$photo = $_FILES;
		$cpt1 = count ( $_FILES ['photo'] ['name'] );
		if ($_FILES ['photo'] ['name'] != '') {
			$_FILES ['photo'] ['name'] = $photo ['photo'] ['name'];
			$_FILES ['photo'] ['type'] = $photo ['photo'] ['type'];
			$_FILES ['photo'] ['tmp_name'] = $photo ['photo'] ['tmp_name'];
			$_FILES ['photo'] ['error'] = $photo ['photo'] ['error'];
			$_FILES ['photo'] ['size'] = $photo ['photo'] ['size'];
			
			$string = $_FILES ['photo'] ['name'];
			$exe = explode(".",$string);
			$photo = date('dmYhis').'.'.$exe[1];
			// move_uploaded_file ( $_FILES ['photo'] ['tmp_name'], $this->template->domain_uploads_packages ( $_FILES ['photo'] ['name'] ) );
			move_uploaded_file ( $_FILES ['photo'] ['tmp_name'], $this->template->domain_uploads_packages ($photo) );
		}
		$pack_id=$this->input->post('packagetype');
	    $category_id=$this->input->post('VehicleCategoryName');
	    $equip_id=array();
	    $equip_id=$this->input->post('extraservices');
	$data ['package_type_data'] = $this->private_car_model->get_package_type_data($pack_id)->result ();
	$data ['category_data'] = $this->private_car_model->get_category_view_data_types($category_id)->result ();
		$data ['country'] = $this->private_car_model->get_crs_city_listname($this->input->post('country'))->result ();
		//debug($data ['country']);die;
     $pricequipformated=array();
      $pricecoverage=array();
     
      $cancellationpolicy=array();
	for($i=0;$i<count($equip_id);$i++)
	{
	     
	     $data['equipdata']= $this->private_car_model->get_exs_id($equip_id[$i]);
	     
	     $data['inclusionlist']=$this->private_car_model->get_priceequip_inclusionlist($data['equipdata'][0]->Equipid)->result ();
	     $incllistformmated=array();
	     for($j=0;$j<count($data['inclusionlist']);$j++)
	     {
    	    $incllist=array(
    	         "Title"=>$data['inclusionlist'][$j]->title,
    	         "Content"=>$data['inclusionlist'][$j]->content
    	       );
    	       array_push($incllistformmated,$incllist);
	    }
	    	
	     
	     
	    $policydesc=array(
	       
	         "PolicyName"=>$data['equipdata'][0]->PolicyName,
	         "Description"=>$data['equipdata'][0]->DetailedInformation,
	         "DetailedInformation"=>$data['inclusionlist'][$i]->DetailedInformation,
	         "InclusionsList"=>json_encode($incllistformmated),
	         "PolicyUrl"=>$data['equipdata'][0]->PolicyUrl,
	         "Disclaimer"=>$data['equipdata'][0]->Disclaimer,
	         "InsuranceSupplier"=>$data['equipdata'][0]->InsuranceSupplier,
	         "Underwriter"=>$data['equipdata'][0]->Underwriter,
	         
	   );
	    $pricequip=array(
	         "Description"=> $data['equipdata'][0]->PolicyName,
             "EquipType"=> $data['equipdata'][0]->Equipid,
             "CurrencyCode"=> "USD",
             "name"=> $data['equipdata'][0]->name,
              "Amount"=> $data['equipdata'][0]->Amount,
             "policy_description"=>$policydesc 
	       
	       );
	        array_push($pricequipformated,$pricequip);
	    
	}

	$age=array(
	         "MinimumAge"=>$data ['category_data'][0]->minimium_age,
             "MaximumAge"=>$data ['category_data'][0]->maximium_age,
             "NoShowFeeInd"=>"false"
	   );

	$data ['package_coverage'] = $this->private_car_model->pricecover_view_data_types($data ['package_type_data'][0]->package_id)->result ();
	for($i=0;$i<count($data ['package_coverage']);$i++)
	{
	  $pricecover=array(
	            "Code"=>$data ['package_coverage'][$i]->desc_id,
                "CoverageType"=>$data ['package_coverage'][$i]->CoverageType,
                "Currency"=>"USD",
                "Amount"=>$data ['package_coverage'][$i]->Amount,
                "Desscription"=>$data ['package_coverage'][$i]->Description,
                "IncludedInRate"=>"true"
	    );
	      array_push($pricecoverage,$pricecover);
	}

		$module_type="private-car";

	     $paymentrules=array(
	         "PaymentRule"=>"21",
             "PaymentType"=>"4"
	     );
	      $TPAExt=array(
	       "TermsConditions"=>"https://static.carhire-solutions.com/pdf/cnx_tac_en-gb.pdf",
           "SupplierLogo"=>"https://static.carhire-solutions.com/images/supplier/logo/logo36.png"
	     );
	    
	   
	    $pricebreak=array(
	        "RentalPrice"=>$this->input->post('RentalPrice'),
            "OnewayFee"=>0,
            "OtherTaxes"=>0,
            "YoungDriverAmount"=>$data ['category_data'][0]->Young_driver_charge,
            "SeniorDriverAmount"=>$data ['category_data'][0]->Young_driver_charge
	   );
	   $totalbreakup=array(
	      "EstimatedTotalAmount"=>$this->input->post('RentalPrice'),
          "Pricebreakup"=>$pricebreak,
          "CurrencyCode"=>"USD",    
	   );
	   
	   $canel_start_data=$this->input->post('cancel_start_date');
	   $canel_end_data=$this->input->post('cancel_end_date');
	   $camount=$this->input->post('camount');
	 for($i=0;$i<count($canel_start_data);$i++)
	{
	   $cancel=array(
	       "FromDate"=>$canel_start_data[$i],
            "ToDate"=>$canel_end_data[$i],
            "CurrencyCode"=>"USD",
            "Amount"=>$camount[$i]
	       );
	       array_push($cancellationpolicy,$cancel);
	}
	
	   $operationschedules=array();
	   $time=$this->input->post('days');
	   for($i=0;$i<count($time);$i++)
	{
	   $openinghours=array(
	        "Day"=>$time[$i],
               "Start"=>$this->input->post('start_time'),
            "End"=>$this->input->post('end_time')
	   );
	   array_push($operationschedules,$openinghours);
	}
	   $scheduletime=array(
	        "Start"=>$this->input->post('start_time'),
            "End"=>$this->input->post('end_time')
	   );
	 
	   $additionalinfo=array
	   (
	     "ParkLocation"=> "Airport Location, please follow signs to the car rental stations.",
	     "OpeningHours"=>json_encode($operationschedules),
	    "OperationSchedules"=>json_encode($scheduletime),
	   
	   );
	   $pickup=array();
	   $pick=$this->input->post('car_to');
	   for($i=0;$i<count($pick);$i++)
	{
	     $location=array(
	      "StreetNmbr"=> $pick,
	      'CountryName' =>$data ['country'][0]->country_name,
				'CityName' =>$this->input->post('cityname'),
         "PostalCode"=>$this->input->post('PickPostalCode')[$i]
	   );
	   array_push($pickup,$location);
	}
	    $drop=$this->input->post('drop_to');
	    $dropup=array();
	   for($i=0;$i<count($drop);$i++)
	{
	   $location=array(
	      "StreetNmbr"=>$drop[$i],
	      'CountryName' =>$data ['country'][0]->country_name,
				'CityName' =>$this->input->post('cityname'),
         "PostalCode"=>$this->input->post('DropPostalCode')[$i]
	   );
	   array_push($dropup,$location);
	}

	$locationdetail=array(
	       "PickUpLocation"=>$pickup,
	       "DropLocation"=>$dropup
	    );
	//  debug($locationdetail);die;
		$newpackage = array (
				'carcode' =>rand(),
				'Status' =>true,
				'TransmissionType' =>$this->input->post('TransmissionType'),
				'FuelType' =>$this->input->post('FuelType'),
				'PassengerQuantity' =>$this->input->post('PassengerQuantity'),
				'BaggageQuantity' =>$this->input->post('BaggageQuantity'),
				'VendorCarType' =>$this->input->post('VehicleCategoryName'),
				'VehicleCategoryName' =>$data['category_data'][0]->category_name,
				'DoorCount' =>$this->input->post('DoorCount'),
				'rentalprice' =>$this->input->post('RentalPrice'),
				'VehClassSizeName' =>$this->input->post('VehClassSizeName'),
				'Name' =>$this->input->post('VehClassSizeName'),
				'AirConditionInd' =>$this->input->post('AirConditionInd'),
				'pickuplocation' =>$this->input->post('car_to'),
				'dropuplocation' =>json_encode($this->input->post('drop_to')),
				'LocationDetails' =>json_encode($locationdetail),
				'PictureURL' => $photo,
				'Unlimited' => $this->input->post('Mileage_Allowance'),
				'DistUnitName' =>  "Mile",
				'RateComments' =>$data ['package_type_data'][0]->package_name,
				'RateRestrictions' =>json_encode($age),
				'reference_url' => null,
				'Vendor' =>"Test",
				'PaymentRules' =>json_encode($paymentrules),
				'CancellationPolicy' =>json_encode($cancellationpolicy),
				'TPA_Extensions' =>json_encode($TPAExt),
				'TotalCharge' =>json_encode($totalbreakup),
				'PricedCoverage' =>json_encode($pricecoverage),
				'OperationSchedules' =>json_encode($scheduletime),
				'PricedEquip' =>json_encode($pricequipformated),
				'AdditionalInfo' =>json_encode($additionalinfo),
				'country' =>$data ['country'][0]->country_name,
				'city' =>$this->input->post('cityname'),
				'state' =>$this->input->post('cityname'),
				'created_by_id' =>$this->entity_user_id,
				'created_datetime' =>date("Y-m-d")
				

		);
	
		$package = $this->private_car_model->add_new_car( $newpackage );

		redirect ( 'privatecar/view_with_price' );
	}
	public function view_without_price() {
		$data ['newpackage'] = $this->private_car_model->without_price ();
		$this->template->view ( 'private-car/view_without_price', $data );
	}
	public function view_with_price() {
		
		$data ['newpackage'] = $this->private_car_model->with_price ();

		// debug($data ['newpackage']);exit(	);
		$this->template->view ( 'private-car/view_with_price', $data );
	}
	public function update_deal_status($package_id, $deals) {
		$this->private_car_model->update_deal_status ( $package_id, $deals );
		if ($deals == '1') {
			$data ['package_id'] = $package_id;
			$this->template->view ( 'private-car/view_city_request', $data );
		} else if ($deals == '0') {
			$data ['package_id'] = $package_id;
			$this->private_car_model->delete_deal_id ( $package_id );
			redirect ( 'private-car/view_city_request', 'refresh' );
		} else {
			redirect ( 'private-car/view_deals', 'refresh' );
		}
	}
	public function update_status($package_id, $status) {
		$this->private_car_model->update_status ( $package_id, $status );
		redirect ( 'private-car/view_with_price/', 'refresh' );
	}
	public function read_enquiry($package_id) {
		$status=ACTIVE;
		$this->private_car_model->update_enquiry_status ( $package_id, $status );
		redirect ( 'privatecar/enquiries/', 'refresh' );
	}
	
	public function view_deals() {
		$data ['newpackage'] = $this->private_car_model->get_supplier ();
		$this->template->view ( 'private-car/suppliers', $data );
	}
	public function update_homepage_status2($package_id, $home_page) {
		$this->private_car_model->update_homepage_status ( $package_id, $home_page );
		redirect ( 'private-car/view_without_price/' . $package_id, 'refresh' );
	}
	public function edit_without_price($package_id) {
		$data ['packdata'] = $this->private_car_model->get_package_id ( $package_id );
		$data ['price'] = $this->private_car_model->get_price ( $package_id );
		$data ['countries'] = $this->private_car_model->get_country_city_list ();
		// print_r($data);exit;
		$this->template->view ( 'private-car/edit_without_price', $data );
	}
	public function edit_itinerary($package_id) {
		$data ['pack_data'] = $this->private_car_model->get_itinerary_id ( $package_id );
		// print_r($data);die;
		$data ['package_id'] = $package_id;
		$this->template->view ( 'private-car/edit_itinerary', $data );
	}
	public function quesans_view($package_id) {
		$data ['que_ans'] = $this->private_car_model->get_que_ans ( $package_id );
		$data ['package_id'] = $package_id;
		$this->template->view ( 'private-car/quesans_view', $data );
	}
	public function enquiries() {
		
		$data ['enquiries'] = $this->private_car_model->enquiries ();
		//debug($data);exit;
		$this->template->view ( 'private-car/enquiries', $data );
	}
	public function delete_enquiry($id, $package_id) {
		$this->private_car_model->delete_enquiry ( $id );
		redirect ( 'private-car/enquiries/' );
	}
	public function itinerary() {
		// print_r($itinerarydesc);exit;
		$itinerary = array (
				'itinerary_description' => $itinerarydesc,
				'day' => $days 
		);
		$it = $this->private_car_model->itinerary ( $itinerary );
		if ($it) {
			redirect ( 'private-car/view_deals', 'refresh' );
		}
	}
	public function itinerary_loop($duration) {
		$data ['duration'] = $duration;
		echo $this->template->isolated_view ( 'private-car/duration_itinerary', $data );
	}
	public function itinerary_loop1($questions) {
		$data ['questions'] = $questions;
		$this->template->isolated_view ( 'private-car/question', $data );
	}
	public function itinerary_loop2($pricee) {
		$data ['pricee'] = $pricee;
		$this->template->isolated_view ( 'private-car/withprice', $data );
	}
	public function get_crs_city($city_id) {
		$city = $this->private_car_model->get_crs_city_list ( $city_id );
		$sHtml = "";
		$sHtml .= '<option value="">Select City</option>';
		if (! empty ( $city )) {
			foreach ( $city as $key => $value ) {

				$sHtml .= '<option value="' . $value->city . '">' . $value->city . '</option>';
			}
		}
		
		echo json_encode ( array (
				'result' => $sHtml 
		) );
		exit ();
	}
	public function get_tour($tour_id) {
		// echo "hi";
		// echo $tour_id;die;
		$tours = $this->private_car_model->get_tour_list ( $tour_id );
		$sHtml = "";
		$sHtml .= '<option value="">Select Tours</option>';
		if (! empty ( $tours )) {
			// debug($tours);exit;
			foreach ( $tours as $key => $value ) {
				$sHtml .= '<option value="' . $value->package_types_id . '">' . $value->package_types_name . '</option>';
			}
		}
		echo json_encode ( array (
				'result' => $sHtml 
		) );
		exit ();
	}
	public function edit_with_price($package_id) {
		$data ['packdata'] = $this->private_car_model->get_package_id ( $package_id );
		$data ['price'] = $this->private_car_model->get_price ( $package_id );
		$data ['countries'] = $this->private_car_model->get_country_city_list ();
		// print_r($data);exit;
		$this->template->view ( 'private-car/edit_with_price', $data );
	}
	public function update_package($package_id) {
		$name = $this->input->post ( 'name' );
		$location = $this->input->post ( 'location' );
		$description = $this->input->post ( 'Description' );
		$photo = $_FILES;
		if ($_FILES ['photo'] ['name'] != '') {



			$config['upload_path']          = str_replace('/troogles', '', $this->template->domain_uploads_packages ());
            $config['allowed_types']        = 'jpg|png|jpeg';
            $config['max_size']             = 1024;
            $config['max_width']            = 1024;
            $config['max_height']           = 768;
            $config['encrypt_name']         = true;
            $this->load->library('upload', $config);
			if ( ! $this->upload->do_upload('photo'))
			{
				echo $this->upload->display_errors();die;
			}
			$photo = $this->upload->data()['file_name'];
			
			/*$_FILES ['photo'] ['name'] = $photo ['photo'] ['name'];
			$_FILES ['photo'] ['type'] = $photo ['photo'] ['type'];
			$_FILES ['photo'] ['tmp_name'] = $photo ['photo'] ['tmp_name'];
			$_FILES ['photo'] ['error'] = $photo ['photo'] ['error'];
			$_FILES ['photo'] ['size'] = $photo ['photo'] ['size'];
			
			$string = $_FILES ['photo'] ['name'];
			$exe = explode(".",$string);
			$photo = date('dmYhis').'.'.$exe[1];

			$path=str_replace('../', '', $this->template->domain_uploads_packages ( $photo ));
			move_uploaded_file ( $_FILES ['photo'] ['tmp_name'], $path );*/
				// debug($path);exit();
			// move_uploaded_file ( $_FILES ['photo'] ['tmp_name'], $this->template->domain_uploads_packages ( $_FILES ['photo'] ['name'] ) );
			// $photo = $_FILES ['photo'] ['name'];
		} else {
			$photo = $this->input->post ( 'hidephoto' );
		}
		// debug($this->input->post());die;
		$end_date = $this->input->post ( 'tour_expire_date' );
		$start_date = $this->input->post ( 'tour_start_date' );
		$includes = $this->input->post ( 'includes' );
		$excludes = $this->input->post ( 'excludes' );
		$price = $this->input->post ( 'p_price' );
		$child_price = $this->input->post ( 'child_price' );
		$infant_price = $this->input->post ( 'infant_price' );
		$advance = $this->input->post ( 'advance' );
		$penality = $this->input->post ( 'penality' );
		$value = $this->input->post ( 'value' );
		$discount = $this->input->post ( 'discount' );
		$save = $this->input->post ( 'save' );
		$seats = $this->input->post ( 'seats' );
		$time = $this->input->post ( 'time' );
		$sd = $this->input->post ( 'sd' );
		$ed = $this->input->post ( 'ed' );
		$adult = $this->input->post ( 'adult' );
		$child = $this->input->post ( 'child' );
		$packtypew = $this->input->post ( 'w_wo_d' );
		$rating = $this->input->post ( 'rating' );
		$p_price = $this->input->post ( 'p_price' );
		$data = array (
				'package_name' => ucwords($name),
				'start_date' => $start_date,
				'end_date' => $end_date,
				'package_location' => $location,
				'package_description' => $description,
				'image' => $photo,
				'rating' => $rating,
				'price' => $p_price ,
				'child_price' => $child_price, 
				'infant_price' => $infant_price 
		);
		// print_r($data);exit;
		$policy = array (
				'price_includes' => $includes,
				'price_excludes' => $excludes 
		);
		$can = array (
				'cancellation_advance' => $advance,
				'cancellation_penality' => $penality 
		);
		$dea = array (
				'value' => $value,
				'discount' => $discount,
				'you_save' => $save,
				'seats' => $seats,
				'time' => $time 
		);
		if (! empty ( $sd )) {
			for($i = 0; $i < count ( $sd ); $i ++) {
				$pri = array (
						'from_date' => $sd [$i],
						'to_date' => $ed [$i],
						'adult_price' => $adult [$i],
						'child_price' => $child [$i] 
				);
				$this->private_car_model->update_edit_pri ( $package_id, $pri );
			}
		}
		// print_r($data);die;
		$this->private_car_model->update_edit_package ( $package_id, $data );
		$this->private_car_model->update_edit_policy ( $package_id, $policy );
		$this->private_car_model->update_edit_can ( $package_id, $can );
		$this->private_car_model->update_edit_dea ( $package_id, $dea );
		
		if ($packtypew == "w") {
			redirect ( 'privatecar/view_with_price' );
		} elseif ($packtypew == 'wo') {
			redirect ( 'privatecar/view_without_price' );
		} else {
			redirect ( 'privatecar/view_deals' );
		}
		
		redirect ( 'private-car/view_with_price' );
	}
	public function images($package_id) {

		// error_reporting(E_ALL);

		if ($_FILES) {
			$tra = $_FILES;
			$_FILES ['traveller'] ['name'] = $tra ['traveller'] ['name'];
			$_FILES ['traveller'] ['type'] = $tra ['traveller'] ['type'];
			$_FILES ['traveller'] ['tmp_name'] = $tra ['traveller'] ['tmp_name'];
			$_FILES ['traveller'] ['error'] = $tra ['traveller'] ['error'];
			$_FILES ['traveller'] ['size'] = $tra ['traveller'] ['size'];
			$name_of_traveller_img = 'traveller-' . time () . $_FILES ['traveller'] ['name'];
			// echo '/extras/custom/TMX1512291534825461/uploads/packages/traveller-1582290931flight.jpg';
			// debug('../'.$this->template->domain_uploads_packages ( $name_of_traveller_img ));exit;
			move_uploaded_file ( $_FILES ['traveller'] ['tmp_name'],$this->template->domain_uploads_packages ( $name_of_traveller_img ) );
			$traveller_img = $name_of_traveller_img;
			$sup_id = $this->entity_user_id;
			$pckge_id = $this->input->post ( 'pckge_id' );
			$traveller = array (
					'traveller_image' => $traveller_img,
					'user_id' => $sup_id,
					'package_id' => $pckge_id 
			);
			$travel = $this->private_car_model->travel_images ( $traveller );

			// debug($package_id);exit();
		//	redirect ( base_url () . 'index.php/private-car/images/'. $package_id .'/w' );
		}
		$data ['traveller'] = $this->private_car_model->get_image ( $package_id );
		$data ['package_id'] = $package_id;
		$this->template->view ( 'private-car/images', $data );
	}
	public function update_itinerary() {
		$package_id = $this->input->post ( 'package_id' );
		$itinerary_id = $this->input->post ( 'itinerary_id' );
		$itinerarydesc = $this->input->post ( 'desc' );
		$days = $this->input->post ( 'days' );
		$place = $this->input->post ( 'place' );
		$hiddenimage = $this->input->post ( 'hiddenimage' );
		// print_r($hiddenimage);
		// exit;
		$img = $_FILES;
		$cp = count ( $itinerary_id );
		// print_r($cp);exit;
		for($i = 0; $i < $cp; $i ++) {
			
			if (! empty ( $_FILES ['imagelable' . $i] ['name'] )) {
				$_FILES ['imagelable'] ['name'] = $img ['imagelable' . $i] ['name'];
				$_FILES ['imagelable'] ['type'] = $img ['imagelable' . $i] ['type'];
				$_FILES ['imagelable'] ['tmp_name'] = $img ['imagelable' . $i] ['tmp_name'];
				$_FILES ['imagelable'] ['error'] = $img ['imagelable' . $i] ['error'];
				$_FILES ['imagelable'] ['size'] = $img ['imagelable' . $i] ['size'];
				move_uploaded_file ( $_FILES ['imagelable'] ['tmp_name'], $this->template->domain_uploads_packages ( $_FILES ['imagelable' . $i] ['name'] ) );
				$image = $this->template->domain_uploads_packages ( $_FILES ['imagelable' . $i] ['name'] );
			} else {
				$image = $hiddenimage [$i];
			}
			
			$data = array (
					'itinerary_description' => $itinerarydesc [$i],
					'place' => $place [$i],
					'day' => $days [$i],
					'itinerary_image' => $image 
			);
			
			$this->private_car_model->update_itinerary ( $package_id, $itinerary_id [$i], $data );
		}
		redirect ( 'private-car/edit_itinerary/' . $package_id, 'refresh' );
	}
	public function view_enquiries($package_id) {
		$data ['enquiries'] = $this->private_car_model->view_enqur ( $package_id );
		$data ['package_id'] = $package_id;
		$this->template->view ( 'private-car/enquiry_package', $data );
	}
	/*************car supplier details******/
	public function add_car_supplier(){

		$data ["country"] = $this->private_car_model->get_countries ();
		
		$data["branch_user_list"] = $this->car_model->get_branch_user_list(array());
		$this->template->view ( 'car/add_supplier', $data );
	}
	public function add_supplier($id=0){
		$post_params = $this->input->post();		
// debug($post_params);die;
		if(valid_array($post_params)){
			// debug($post_params);die;
			$insert_arr = array();
			$insert_arr['agency_name'] = $post_params['supplier_name'];
			$insert_arr['branch_id'] = $post_params['branch_id']; 
			$insert_arr['first_name'] = $post_params['supplier_name']; 
			//$insert_arr['supplier_id'] = $post_params['branch_id']; 
			//$insert_arr['agent_name'] = $post_params['reg_no'];
			$insert_arr['email'] = $post_params['email'];
			$insert_arr['user_name']=$post_params['email'];
			$insert_arr['phone'] = $post_params['phone_no'];
			//$insert_arr['pan_number'] = $post_params['pan_no'];
			//$insert_arr['aadhar_no'] = $post_params['adhar_no'];
			//$insert_arr['country'] = $post_params['country'];
			$insert_arr['country_name'] = $post_params['country'];
			$insert_arr['city'] = $post_params['cityname'];
			$insert_arr['location'] = $post_params['location'];
			$insert_arr['address'] = $post_params['address'];
			$insert_arr['password'] = md5($post_params['password']);
			$photo = $_FILES;
			$cpt1 = count ( $_FILES ['photo'] ['name'] );
			if ($_FILES ['photo'] ['name'] != '') {
				$_FILES ['photo'] ['name'] = $photo ['photo'] ['name'];
				$_FILES ['photo'] ['type'] = $photo ['photo'] ['type'];
				$_FILES ['photo'] ['tmp_name'] = $photo ['photo'] ['tmp_name'];
				$_FILES ['photo'] ['error'] = $photo ['photo'] ['error'];
				$_FILES ['photo'] ['size'] = $photo ['photo'] ['size'];
				

				move_uploaded_file ( $_FILES ['photo'] ['tmp_name'], $this->template->domain_image_full_path ( $_FILES ['photo'] ['name'] ) );
				
				// if(move_uploaded_file ( $_FILES ['photo'] ['tmp_name'], $this->template->domain_image_upload_path().$_FILES ['photo'] ['name'] ) ){
				// 	$photo = $_FILES ['photo'] ['name'];
				// 	$insert_arr['image'] = $photo;
					
				// }
				// else{
				// 	$photo = $_FILES ['photo'] ['name'];
				// 	$insert_arr['image'] = 'no_image';
				// }
				
				//domain_uploads_car
				 $photo = $_FILES ['photo'] ['name'];
				 $insert_arr['image'] = $photo;
			}
			



			// debug($id);exit();
			if($id>0){
				// echo "in if";
				// debug($insert_arr);die;
                unset($insert_arr['email']);
                unset($insert_arr['password']);
				$this->custom_db->update_record('user',$insert_arr,array('user_id'=>$id));
			}else{
				// echo "out else";



				// debug($GLOBALS['CI']->entity_user_type);
				// debug(CAR_BRANCH_USER);exit;
				// if($GLOBALS['CI']->entity_user_type == CAR_BRANCH_USER)
				$insert_arr['branch_id'] = $this->entity_user_id;
				$insert_arr['created_by_id'] = $this->entity_user_id;
                $insert_arr['created_datetime'] = date ( 'Y-m-d H:i:s' );
				$insert_arr['user_type']  =CAR_SUPPLIER;
				$insert_arr['uuid'] = time().rand(1, 1000);
				$insert_arr['status'] = ACTIVE;
				$insert_arr['domain_list_fk'] = get_domain_auth_id();
				// debug($insert_arr);die;
				$r=$this->custom_db->insert_record('user',$insert_arr);
				// debug($r);die;
			}
			

			$data ["country"] = $this->private_car_model->get_countries ();
		if($GLOBALS['CI']->entity_user_type == ADMIN)
			// echo "sad";
			redirect(base_url().'index.php/private-car/all_car_supplier_list');
		else
			// echo "d";
			redirect(base_url().'index.php/private-car/car_supplier_list');
		}else{
			 // echo "out";die;
			if($id>0){
				redirect(base_url().'index.php/private-car/all_car_supplier_list/'.$id);
			}
		}
	}

    public function all_car_supplier_list($id=0){

        //$supplier_list = $this->custom_db->single_table_records('car_supplier_list','*');

        $supplier_list = $this->car_model->get_supplier_list(0,'');

        // debug($supplier_list);exit();
        if($id>0){
            $edit_data = $this->car_model->get_supplier_list($id,'');
            $data = $edit_data[0];
        }
// debug($supplier_list);die;
        foreach($supplier_list as $key=>$list){
            $supplier_list[$key]['branchuser_name']=$this->car_model->get_branch_user_name_on_id($list['branch_id'])['agency_name'];
            // debug($supplier_list[$key]['branchuser_name']);
        }
        // debug($supplier_list);die;
// echo $this->db->last_query();die;
        // debug($supplier_list);die;
        $total_row_count = 0;
        if($supplier_list){

            $data['table_data'] = $supplier_list;
            $total_row_count = count($data['table_data']);
        }else{
            $data['table_data'] = array();
        }
        // debug($data);die;
        $this->load->library('pagination');
        if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
        $config['base_url'] = base_url().'index.php/private-car/car_supplier_list/';
        $config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
        $config['total_rows'] = $total_row_count;
        $config['per_page'] = RECORDS_RANGE_1;
        // $this->pagination->initialize($config);

        $data ["country"] = $this->car_model->get_active_country_list ();
// echo $this->db->last_query();die;
        // debug($data);die;
        $data ["country"] = $data ["country"]['data'];
        $data['ID'] = $id;
// debug($data);die;
        $data["branch_user_list"] = $this->car_model->get_branch_user_list(array());
// echo $this->db->last_query();die;
// debug($data["branch_user_list"]);die;

        // echo $this->db->last_query();die;
        $this->template->view ( 'car/supplier_list', $data );

    }

	public function car_supplier_list($id=0){

		//$supplier_list = $this->custom_db->single_table_records('car_supplier_list','*');
// debug($id);debug($this->entity_user_id);die;
		if($GLOBALS['CI']->entity_user_type == ADMIN){
			// echo "in";die;
			$supplier_list = $this->car_model->get_supplier_list(0,'');	
			// echo $this->db->last_query();die;
			$edit_data = $this->car_model->get_supplier_list($id,'');
			// echo $this->db->last_query();die;
			// debug($edit_data);die;
			$data = $edit_data[0];

	
		}
		/*else if($GLOBALS['CI']->entity_user_type == CAR_BRANCH_USER)
{
	// echo "out";die;
		$supplier_list = $this->car_model->get_supplier_list(0,$this->entity_branch_id);	
		// debug($supplier_list);die;
		if($id>0){
			$edit_data = $this->car_model->get_supplier_list($id,$this->entity_user_id);
			$data = $edit_data[0];
		}
	}*/
	else
	{
		$supplier_list = $this->car_model->get_supplier_list(0,$this->entity_user_id);	
		// debug($supplier_list);die;
		if($id>0){
			$edit_data = $this->car_model->get_supplier_list($id,$this->entity_user_id);
			$data = $edit_data[0];
		}
	}
	// debug($supplier_list);die;
	
        // debug($supplier_list);die;
// echo $this->db->last_query();die;
        // debug($supplier_list);die;
        
// debug($supplier_list);die;
	
		// echo $this->db->last_query();die;
		$total_row_count = 0;
		if($supplier_list){

		 	$data['table_data'] = $supplier_list;
		 	$total_row_count = count($data['table_data']);
		}else{
			$data['table_data'] = array();
		}	
		// debug($data);die;
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/private-car/car_supplier_list/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$config['total_rows'] = $total_row_count;
		$config['per_page'] = RECORDS_RANGE_1;
		// $this->pagination->initialize($config);

		$data ["country"] = $this->car_model->get_active_country_list ();

		$data ["country"] = $data ["country"]['data'];
		$data['ID'] = $id;

		$data["branch_user_list"] = $this->car_model->get_branch_user_list(array());
		// debug($data);die;
		// echo $this->db->last_query();die;
		$this->template->view ( 'car/supplier_list', $data );

	}
		/**
	 * Activate User Account
	 */
	function activate_supplier($user_id)
	{
		$cond['user_id'] = intval($user_id);		
		$data['status'] = ACTIVE;
		$driver_con['supplier_id'] = $user_id;
		if($this->custom_db->update_record('user',$data,$cond) && $this->custom_db->update_record('user',$data,$driver_con) && $this->custom_db->update_record('car_details',$data,$driver_con)){			
			echo "1";
		}else{
			echo "0";
		}

		exit;
		/*redirect(base_url().'user/user_management?filter=user_type&q='.$info['data']['user_type']);*/
	}

	/**
	 * Deactiavte User Account
	 */
	function deactivate_supplier($user_id)
	{
		$cond['user_id'] = intval($user_id);		
		$data['status'] = INACTIVE;
		$driver_con['supplier_id'] = $user_id;
		if($this->custom_db->update_record('user',$data,$cond) && $this->custom_db->update_record('user',$data,$driver_con) && $this->custom_db->update_record('car_details',$data,$driver_con)){

			echo "1";
		}else{
			echo "0";
		}
		exit;
		/*redirect(base_url().'user/user_management?filter=user_type&q='.$info['data']['user_type']);*/
	}
	function get_active_city($origin,$select_city=''){
		// echo "fsdfds";exit;
		$city = $this->custom_db->single_table_records('car_active_country_city_master','*',array('country_id'=>$origin));
		$sHtml = "";
		
		$sHtml .= '<option value="">Select City</option>';
		if ($city['status']==1) {
			foreach ( $city['data'] as $key => $value ) {
				$select = '';
				if($select_city){
					if($select_city==$value['origin']){
						$select = "selected=selected";
					}
				}
				$sHtml .= '<option value="' . $value['origin']. '" '.$select.'>' . $value['city_name'] . '</option>';
			}
		}
		
		echo json_encode ( array (
				'result' => $sHtml 
		) );
		exit ();
	}

    public function send_booking_link($redirect=true)
    {
        //error_reporting(E_ALL);
        $post_data=$this->input->post();
       // debug($post_data);exit();

        //calculation for counting

        $post_data['adult_price'] = round($post_data['adult_price']);
        $post_data['child_price'] = round($post_data['child_price']);
        $post_data['infant_price'] = round($post_data['infant_price']);
        if($post_data['adult_price']!='' || $post_data['child_price']!=''|| $post_data['infant_price']){
            $post_data['total'] = ($post_data['adult_price']+$post_data['child_price']+$post_data['infant_price']);
        }


        //end

            $post_data['departure_date']=date('Y-m-d',strtotime($post_data['departure_date']));
            $enquiry_data = $post_data;

        $enquiry_data['tour_id'] = ($post_data['tour_id'])? $post_data['tour_id'] : $enquiry_data['tour_id'];

        $quote_reference = generate_holiday_reference_number('ZVQ');
        
        $tours_quotation_log_data=array();
        $tours_quotation_log_data['quote_reference']=$quote_reference;
        $tours_quotation_log_data['tour_id']=$enquiry_data['tour_id'];
        $tours_quotation_log_data['first_name']=$enquiry_data['name'];
        $tours_quotation_log_data['email']=$enquiry_data['email'];
        $tours_quotation_log_data['phone']=$enquiry_data['phone'];
        $tours_quotation_log_data['en_note'] =  $post_data['en_note'];
        $tours_quotation_log_data['quoted_price']=round($post_data['total']);
        $tours_quotation_log_data['currency_code']=get_application_currency_preference();
        $tours_quotation_log_data['user_attributes']=json_encode($post_data);
        $tours_quotation_log_data['created_by_id']=$this->entity_user_id;
        $tours_quotation_log_data['created_datetime']=date('Y-m-d H:i:s');

        $this->custom_db->insert_record('tours_quotation_log',$tours_quotation_log_data);
        $ex_data['en_note'] = $post_data['en_note'];
        if($post_data['quote_type']=='request_quote'){
            # debug($enquiry_data['email']); exit('xxx');
            if(!empty($enquiry_data['email']))
            {
                $ex_data['name']=$enquiry_data['name'];
                #debug($ex_data['name']); exit;
                $res = $this->voucher($enquiry_data['tour_id'],'mail','mail',$quote_reference,'',$enquiry_data['email'],'redirect',$ex_data);

            }
        }else{
//            if($post_data['enquiry_reference_no']){
//                $tours_enquiry = $this->custom_db->get_result_by_query('SELECT * FROM tours_enquiry WHERE enquiry_reference_no = "'.$post_data['enquiry_reference_no'].'" ');
//                if($tours_enquiry){
//                    $tours_enquiry = json_decode(json_encode($tours_enquiry),1);
//                    $post_data['tour_id'] = $tours_enquiry[0]['tour_id'];
//                    $post_data['departure_date'] = $tours_enquiry[0]['departure_date'];
//                }
//            }
            $app_reference = generate_holiday_reference_number('ZVZ');
            $tour_booking_details_data=array();
//            $tour_booking_details_data['enquiry_reference_no']=$post_data['enquiry_reference_no'];
            $tour_booking_details_data['app_reference']=$app_reference;
            $tour_booking_details_data['status']='PROCESSING';
            $tour_booking_details_data['basic_fare']=round($post_data['total']);
            $tour_booking_details_data['currency_code']=$post_data['currency'];
            $tour_booking_details_data['payment_status']='unpaid';
            $tour_booking_details_data['created_datetime']=date('Y-m-d H:i:s');
            $tour_booking_details_data['created_by_id']=$this->entity_user_id;
            $tour_booking_details_data['attributes']=json_encode($post_data);

            $this->custom_db->insert_record('tour_booking_details',$tour_booking_details_data);
            $booking_url = base_url().'index.php/tours/pre_booking/'.$app_reference;
            $booking_url = str_replace('supervision/', '', $booking_url);
            if(!empty($enquiry_data['email']))
            {
                $ex_data['booking_url']=$booking_url;
                $ex_data['name']=$enquiry_data['name'];
                $res = $this->voucher($enquiry_data['tour_id'],'mail','mail','',$app_reference,$enquiry_data['email'],'redirect',$ex_data);
            }
        }

        set_update_message ();
        if(1){
            $this->load->library('user_agent');
            if ($this->agent->is_referral())
            {
                redirect ( $this->agent->referrer());
            }else{
                redirect ( base_url () . 'index.php/tours/tours_enquiry');
            }
        }
    }

    public function voucher($tour_id,$operation='show_broucher',$mail = 'no-mail',$quotation_id = '',$app_reference = '',$email = '',$redirect = '',$ex_data = array())
    {
        $page_data['tour_id'] = $tour_id;
        $this->load->model('tours_model');
        $page_data['menu'] = false;
        // debug( $page_data['tour_id']);exit;
        $where = ['package_id'=>$tour_id];
        $page_data ['tour_data']            = $this->tours_model->package_data('package', $where);
        // debug($page_data ['tour_data']); exit('I am ready');
        $page_data ['tours_itinerary']      = $this->tours_model->tours_itinerary($tour_id,$dep_date);
        $page_data ['tours_itinerary_dw']   = $this->tours_model->tours_itinerary_dw($tour_id,$dep_date);
        $page_data ['tours_itinerary_wd']   = $this->tours_model->tours_itinerary_dw($tour_id);
        $page_data ['tours_date_price']     = $this->tours_model->tours_date_price($tour_id);
        $tour_data = $this->custom_db->get_result_by_query("select group_concat(airliner_price) pricing, group_concat(occupancy) occ, group_concat(markup) markup ,tour_id, from_date, to_date , currency from tour_price_management where tour_id = ".$tour_id." group by from_date, to_date ");
        $page_data['tour_price'] = json_decode(json_encode($tour_data),true);
         
        $tour_cities =  $page_data['tour_data']['tours_city'];
        $tour_cities_array = json_decode($tour_cities);
        foreach ($tour_cities_array as $t_city) {
            $query_x = "select * from tours_city where id='$t_city'";
            $exe_x   = mysql_query($query_x);
            $visited_city[] = mysql_fetch_assoc($exe_x);
        }
        $page_data['visited_city'] = $visited_city;
        if ($quotation_id!='') {
            $quotation_details = $this->tours_model->quotation_details($quotation_id);
            if ($quotation_details['status']==1) {
                $page_data['quotation_details'] = $quotation_details['data'];
            }
        }
        if ($app_reference!='') {
            $booking_details = $this->tours_model->booking_details($app_reference);
            if ($booking_details['status']==1) {
                $page_data['booking_details'] = $booking_details['data'];
            }
        }

        if($mail == 'mail') {
            $operation="mail";
            if($this->input->post('email')){
                $email = $this->input->post('email');
            }
        }
        switch ($operation) {
            case 'show_broucher' :
                $page_data['menu'] = true;
                $this->template->view('tours/broucher',$page_data);
                break;
            case 'show_pdf' :
                $get_view = $this->template->isolated_view ( 'tours/broucher_pdf',$page_data );

                $this->load->library ( 'provab_pdf' );
                $this->provab_pdf->create_pdf ( $get_view, 'D');
                break;
            case 'mail' :
            // debug($ex_data['booking_url']);
            // debug($page_data);die;
               // $mail_template_mail =$this->template->isolated_view('tours/activity_broucher',$page_data);
                $mail_template =$this->template->isolated_view('tours/activity_broucher_pdf',$page_data);
                // echo $mail_template;die;
                $this->load->library ( 'provab_pdf' );
                $this->load->library ( 'provab_mailer' );
                $pdf = $this->provab_pdf->create_pdf($mail_template,'F');
                if(count($ex_data)>0){
                    $message = '<strong>Dear '.$ex_data['name'].',<br><br></strong>';
                    if($ex_data['booking_url']){
                        $message .= '<b>Please find the Link below.</b><br><a href="'.$ex_data['booking_url'].'" target="_blank"><h3>Click here to Book</h3></a>';
                    }
                }
                $res = $this->provab_mailer->send_mail($email, 'Broucher', $message.$mail_template,$pdf);
                if($redirect != ''){
                    return true;
                }else{
                    redirect(base_url().'tours/voucher/'.$tour_id,'refresh');
                }
                break;
        }
    }

}