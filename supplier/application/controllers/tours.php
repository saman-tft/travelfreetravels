<?php
if (! defined ( 'BASEPATH' ))
  exit ( 'No direct script access allowed' );
//error_reporting(E_ALL);

class Tours extends CI_Controller {
  public function __construct() {
    parent::__construct ();
     $this->load->model(array('tours_model','custom_db','Package_Model'));
  }
function nationality_region()
  {
      
     $nationality_region = $this->tours_model->nationality_region();
     // debug($nationality_region); exit;    
     $page_data['nationality_region'] = $nationality_region;
     $this->template->view('tours/nationality/nationality_region',$page_data);
  }
  function region_save()
  {
     $data = $this->input->post();
      $tour_region   = sql_injection($data['tour_region']);
      $check_availibility = $this->tours_model->check_region_exist_all($tour_region);
      if(!$check_availibility)
      {
        $query = "insert into all_nationality_region set name='$tour_region', status=1, module='tours', created_by=".$this->entity_user_id." ";        
            //echo $query; //exit;
        $return = $this->tours_model->query_run($query);
        if($return)
          {   $this->session->set_flashdata('message', UL0014);
        redirect('tours/nationality_region'); }
        else
          { echo $return; exit; } 
      }
      else
      {
       $this->session->set_flashdata('region_msg','Region is already exist');
       redirect('tours/nationality_region');
     }

  }
  public function activation_nationality_region($id,$status) {
    $return = $this->tours_model->record_activation('all_nationality_region',$id,$status);
    // debug($return);exit();
    if($return){redirect('tours/nationality_region');} 
    else { echo $return;} 
  }
  public function edit_nationality_region($id)
  {
     $region_details = $this->tours_model->table_record_details('all_nationality_region',$id);

      $page_data['region_details'] = $region_details;
        // debug($page_data); exit;
      $this->template->view('tours/nationality/edit_nationality_region',$page_data);
  }


  public function edit_nationality_region_save() {
    $data = $this->input->post();
      //debug($data); exit;
    $id             = $data['id'];
    $tour_region  = sql_injection($data['tour_region']);
    $query = "update all_nationality_region set name='$tour_region' where id='$id'";        
          //echo $query; //exit;
    $return = $this->tours_model->query_run($query);
    if($return)
      {   
      $this->session->set_flashdata('message', UL0013);
      redirect('tours/edit_nationality_region/'.$id); }
    else
      { echo $return; exit; }              
  }


  public function delete_nationality_region($id) {
    $return = $this->tours_model->record_delete('all_nationality_region',$id);
    if($return){
      $this->session->set_flashdata('message', UL0099);
      redirect('tours/nationality_region');} 
    else { echo $return;} 
  }
  public function view_notionality_country() 
    {
        
      $page_data ['notionality_country'] = $this->tours_model->get_nationalityCountryList();
       // debug($page_data);exit;
      $this->template->view ('tours/nationality/view_notionality_country', $page_data );
    }


  public function nationality_country($id = '') {
      //error_reporting(E_All);
      $page_data ['nationality_regions'] = $this->tours_model->get_nationality_regions();
      $page_data['country_list']=$this->tours_model->get_hb_country_list();
         $currency  = $this->tours_model->get_currency_list(); 
  $currency_nat_price  = $this->Package_Model->get_currency_list(); 
  $page_data['currency'] = $currency;
  $page_data['currency_nat_price'] = $currency_nat_price;
    $page_data ['edit_notionality_country']='';
      // debug($data ['tours_continent']);exit();
      if ($id != '') 
      {
        $page_data ['id']=$id;
        $page_data ['edit_notionality_country'] = $this->tours_model->get_nationalityCountryList($id);
        // debug($page_data ['edit_notionality_country']);exit;
        $this->template->view ( 'tours/nationality/notionality_country', $page_data );
      } else {
        $this->template->view ( 'tours/nationality/notionality_country',$page_data );
      }
    }

 
    public function save_nationalityCountries() 
    {
   
         $data = $this->input->post();
        
         $pack_id=$data['pack_id'];
         $hb_country_list=$this->tours_model->get_hb_country_list();

         $except_countryIds =array();
         $except_countryCodes =array();
         $except_countryNames  =array();

         $include_countryIds =array();
         $include_countryCodes =array();
         $include_countryNames =array();
         // debug($data['tours_country']);
         $raw_except_countries=[];
         foreach ($hb_country_list as $value)
         {
            foreach ($data['tours_country'] as $key => $val)
            {
              if($val == $value['origin']) 
              {               
                array_push($include_countryIds,$value['origin']);
                array_push($include_countryCodes,$value['country_code']);
                array_push($include_countryNames ,$value['country_name']);                 
              }
              else
              { 
                $raw_except_countries [$value['origin']] ['origin']=$value['origin'];
                $raw_except_countries [$value['origin']] ['country_code']=$value['country_code'];
                $raw_except_countries [$value['origin']] ['country_name']=$value['country_name'];

              }
            }
         }

         if(count($data['tours_country']>0)){
              foreach ($data['tours_country'] as  $value) {
                if(isset($raw_except_countries [$value])){
                  unset($raw_except_countries [$value]);
                }
              }
         }
        $except_countryIds   = array_column($raw_except_countries, 'origin');
        $except_countryCodes = array_column($raw_except_countries, 'country_code');
        $except_countryNames = array_column($raw_except_countries, 'country_name');        

         
     $except_countryIds      = implode(',',array_unique($except_countryIds));
     $except_countryCodes      = implode(',',array_unique($except_countryCodes));
     $except_countryNames      = implode(',',array_unique($except_countryNames));   
 
       $include_countryIds      = implode(',',array_unique($include_countryIds));
     $include_countryCodes      = implode(',',array_unique($include_countryCodes));
     $include_countryNames      = implode(',',array_unique($include_countryNames));
    
 
 
       $tours_continent = $this->input->post ( 'tours_continent' );
       $package_name = $this->input->post ( 'name' );
 $currency = $this->input->post ( 'currency_sel' );
     
       $data_ins=array(
           'name' => $package_name,
           'module' => 'tours', 
           'continent' =>$tours_continent, 
            'currency' =>$currency, 
           'except_countryIds' => $except_countryIds, 
           'except_countryCodes' => $except_countryCodes, 
           'except_countryNames' =>$except_countryNames,
           'include_countryIds' => $include_countryIds, 
           'include_countryCodes' => $include_countryCodes, 
           'include_countryNames' =>$include_countryNames,
           'created_by' =>$this->entity_user_id,      
           'created_datetime'=>date('Y-m-d H:m:s'),
           'status'=>1
       );
       

       // debug($data_ins);exit();
        $repeat_nationality = $this->tours_model->check_nationality_duplicate($tours_continent,$package_name);
       if(empty($repeat_nationality)){
       if($pack_id>0)
       {
         $price_cat = $this->tours_model->update_price_cat($data_ins,$pack_id);
       }
       else
       {
         $price_cat = $this->tours_model->add_price_cat($data_ins);

       }
      // debug($this->db->last_query());exit;

       if($price_cat)
       {
           $this->session->set_flashdata(array('message' => 'UL0014', 'type' => SUCCESS_MESSAGE));
           redirect ( 'tours/view_notionality_country' );
       }
       else
       {
           $this->session->set_flashdata(array('message' => 'UL0098', 'type' => ERROR_MESSAGE));
       }
     }else{
      $this->session->set_flashdata(array('message' => 'UL0098', 'type' => ERROR_MESSAGE));redirect ( 'tours/view_notionality_country' );
     }
 }

  public function tour_destinations() {   
   $tour_destinations = $this->tours_model->tour_destinations();
   $page_data['tour_destinations'] = $tour_destinations;
   $this->template->view('tours/tour_destinations',$page_data);
 }
 public function tour_booking_request(){
  $condition = array();
  $get_data = $this->input->get();
  if (valid_array($get_data) == true) {

  } else {
    $c_date = date('Y-m-d');
    $total_records = $this->tours_model->tour_booking_report($condition, true);
    $table_data = $this->tours_model->tour_booking_report($condition, false, $offset, RECORDS_RANGE_2);
    $tour_list = $this->tours_model->tour_list();
    $page_data['tour_list'] = $tour_list;
    $page_data ['request_list'] = $table_data['data'];
    $this->template->view('tours/tour_booking_request', $page_data);
    }
}
    public function add_tour_destination_save() {
      //echo '<pre>'; print_r($_FILES); exit;   
     $list  = $_FILES['gallery']['name'];
     $total_images = count($list);
     //print_r($list); exit;
     for($i=0;$i<$total_images;$i++)
     {         
       $filename  = basename($list[$i]);
       $extension = pathinfo($filename, PATHINFO_EXTENSION);
       $uniqueno  = substr(uniqid(),0,5);
       $randno    = substr(rand(),0,5);
       $new       = $uniqueno.$randno.'.'.$extension;
       $folder    = $this->template->domain_image_upload_path();
       $folderpath= trim($folder.$new);
       $path      = addslashes($folderpath);
       move_uploaded_file($_FILES['gallery']['tmp_name'][$i], $folderpath);              
       if($i==0)
       { 
         $Gallery_list = $new;
       }
       else
       {
         $Gallery_list = $Gallery_list.",".$new;   
       } 
     }  
     $banner_image = $_FILES['banner_image']['name'];
     $filename     = basename($banner_image);
     $extension    = pathinfo($filename, PATHINFO_EXTENSION);
     $uniqueno     = substr(uniqid(),0,5);
     $randno       = substr(rand(),0,5);
     $new          = $uniqueno.$randno.'.'.$extension;
     $folder       = $this->template->domain_image_upload_path();
     $folderpath   = trim($folder.$new);
     $path         = addslashes($folderpath);
     move_uploaded_file($_FILES['banner_image']['tmp_name'], $folderpath);             
     $banner_image = $new;         

     $data = $this->input->post();
    //debug($data);exit;
     $pkg_type    = sql_injection($data['pkg_type']);
     $destination = sql_injection($data['destination']);
     $description = sql_injection($data['description']);
     $highlights  = sql_injection($data['highlights']);
     $query       = "insert into tour_destinations set type='$pkg_type',
     destination='$destination', 
     description='$description',
     highlights='$highlights',
     status=1,
     gallery='$Gallery_list',
     banner_image='$banner_image',
     date=now()";
        //echo $query; exit;
     $return      = $this->tours_model->add_tour_destination_save($query);
     if(!$return)
     {
      echo $return; 
    } 
    redirect('tours/tour_destinations');  
  }
  public function delete_tour_destination($id) { //echo 'id'.$id; exit;
  $return = $this->tours_model->delete_tour_destination($id);
  if($return)
  {
   redirect('tours/tour_destinations'); 
 } 
 else
 {
   echo $return;
 }  
}
public function edit_tour_destination($id) {
  $tour_destination_details = $this->tours_model->tour_destination_details($id);
    //debug($tour_destination_details); //exit;     
  $page_data['tour_destination_details'] = $tour_destination_details;
    //debug($page_data); exit;
  $this->template->view('tours/edit_tour_destination',$page_data);
}
public function edit_tour_destination_save() {
  $data = $this->input->post();
    //debug($data);exit;
  $id          = sql_injection($data['id']);
  $pkg_type    = sql_injection($data['pkg_type']);
  $destination = sql_injection($data['destination']);
  $description = sql_injection($data['description']);
  $highlights  = sql_injection($data['highlights']);

  $ppg        = $_REQUEST['gallery_previous'];
  $total_ppg  = count($ppg) ;
  $ppg_list   = '';
  for($c=0;$c<$total_ppg;$c++)
  {
    if($ppg_list=='')
    {
      $ppg_list = $ppg[$c];
    }
    else
    {
      $ppg_list = $ppg_list.','.$ppg[$c];
    }       
  }
  if($total_ppg>0)
  {
    $ppg_list = $ppg_list.',';
  }
  else
  {
    $ppg_list = '';
  } 
  if($_FILES['gallery']['name'][0]!="")
  {       

    $list  = $_FILES['gallery']['name'];
    $total_images = count($list); 
     //print_r($list); exit;
    for($i=0;$i<$total_images;$i++)
    {
         // for setting the unique name of image starts @@@@@@@@@@@@@@@@@@@
      $filename  = basename($list[$i]);
      $extension = pathinfo($filename, PATHINFO_EXTENSION);
      $uniqueno  = substr(uniqid(),0,5);
      $randno    = substr(rand(),0,5);
      $new       = $uniqueno.$randno.'.'.$extension;
      $folder    = $this->template->domain_image_upload_path();
      $folderpath= trim($folder.$new);
      $path      = addslashes($folderpath);
      move_uploaded_file($_FILES['gallery']['tmp_name'][$i], $folderpath);  

      if($i==0)
      { 
       $Gallery_list = $new;
     }
     else
     {
       $Gallery_list = $Gallery_list.",".$new;   
     } 
   }  
 }   

 $Gallery_list = $ppg_list.$Gallery_list;

 if(!empty($_FILES['banner_image']['name']))
 {
   $banner_image = $_FILES['banner_image']['name'];
   $filename     = basename($banner_image);
   $extension    = pathinfo($filename, PATHINFO_EXTENSION);
   $uniqueno     = substr(uniqid(),0,5);
   $randno       = substr(rand(),0,5);
   $new          = $uniqueno.$randno.'.'.$extension;
   $folder       = $this->template->domain_image_upload_path();
   $folderpath   = trim($folder.$new);
   $path         = addslashes($folderpath);
   move_uploaded_file($_FILES['banner_image']['tmp_name'], $folderpath);             
   $banner_image = $new; 
   $banner_image_update = ",banner_image='$banner_image'"; 
 }

 $query = "update tour_destinations set type='$pkg_type',
 destination='$destination', 
 description='$description',
 highlights='$highlights',
 gallery='$Gallery_list' ".$banner_image_update."
 where id='$id'";
        //echo $query; exit;
 $return = $this->tours_model->edit_tour_destination_save($query);
 if($return)
 {
  redirect('tours/edit_tour_destination/'.$id); 
} 
else
{
 echo $return;
} 
}

public function send_link_to_user($enquiry_reference_no,$redirect = true){
  $data = $this->tours_model->enquiry_user_details($enquiry_reference_no);

  if(!empty($data[0]->email))
  {
   // $mail_template = $data['enquiry_reference_no'];
    //$this->load->library ( 'provab_mailer' );
   // $s = $this->provab_mailer->send_mail ( $data['user_email'], 'Package Url', $mail_template );
    //set_update_message ();
    if($redirect){      
      redirect ( base_url () . 'index.php/tours/tours_enquiry');
    }
    
  }
  /*$return = $this->custom_db->insert_record ( 'tour_booking_details', $data );
 
  if($return['status'])
  {
   // $mail_template = $data['enquiry_reference_no'];
    //$this->load->library ( 'provab_mailer' );
   // $s = $this->provab_mailer->send_mail ( $data['user_email'], 'Package Url', $mail_template );
    set_update_message ();
    redirect ( base_url () . 'index.php/tours/tours_enquiry');
      
  }*/
}
public function send_payment_link($enquiry_reference_no)
{
  $this->load->model('tours_model');
  $booking_fare = $this->input->post('new_price');
  $book_id='ZVZ-'.date('md').'-'.rand(1000,9999);
  $condition = array();
  $condition[] = array('TB.enquiry_reference_no', '=', $this->db->escape($enquiry_reference_no));
  $booking_data = $this->tours_model->tour_booking_report($condition);
  $booking_data=$booking_data['data'][$enquiry_reference_no];
  $tour_booking_details_data=array(
    'app_reference'=>$book_id,
    'basic_fare'=>$booking_fare,
    'currency_code'=>$booking_data['tours_details']['currency'],
    );
  $this->custom_db->update_record('tour_booking_details',$tour_booking_details_data,array('enquiry_reference_no'=>$enquiry_reference_no));
  $firstname = $booking_data['enquiry_details']['name'];
  $email = $booking_data['enquiry_details']['email'];
  $phone = $booking_data['enquiry_details']['phone'];
  $productinfo = 'package_id: '.$booking_data['tours_details']['package_id'];
  $convenience_fees = 0;
  $promocode_discount = 0;
  $promocode = '';
  $this->load->model('transaction_model');
  $this->transaction_model->create_payment_record($book_id, $booking_fare, $firstname, $email, $phone, $productinfo, $convenience_fees, $promocode_discount,$promocode);
  $payment_url = base_url().'index.php/payment_gateway/payment/'.$book_id;
  $payment_url = str_replace('supervision/', '', $payment_url);
  // echo $payment_url; exit('Exit script');
  //set_update_message ();
  redirect ( base_url () . 'index.php/tours/tour_booking_request');
}
public function activation_tour_destination($id,$status) {
  $data = $this->input->post();
    //debug($data);exit;
  $return = $this->tours_model->activation_tour_destination($id,$status);
  if($return){redirect('tours/tour_destinations');} 
  else { echo $return;} 
}
public function add_tour() {
  $tour_destinations = $this->tours_model->tour_destinations();     
  $tours_continent = $this->tours_model->get_tours_continent();
    // debug($tours_continent); exit;
  $page_data['tours_continent'] = $tours_continent;
  $page_data['tour_type'] = $this->tours_model->get_tour_type();
  $page_data['tour_subtheme'] = $this->tours_model->get_tour_subtheme();

 //debug($page_data); exit;
  $this->template->view('tours/add_tour',$page_data);
}

public function no_of_weather($no_of_weather) {
    //echo $no_of_weather; exit;
  for($i=1;$i<=$no_of_weather;$i++)
  {
    echo '<div class="form-group">
    <label class="control-label col-sm-3" for="validation_current">Day '.$i.' </label>
  </div>';
  echo '<div class="form-group">
  <label class="control-label col-sm-3" for="validation_current">Location Name </label>
  <div class="col-sm-4 controls">
   <input type="text" name="weather_loc_name'.$i.'"
   placeholder="Enter location name" data-rule-required="true"
   class="form-control" required>                 
 </div>
</div>';
echo '<div class="form-group">
<label class="control-label col-sm-3" for="validation_current">Temperature </label>
<div class="col-sm-4 controls">
  <select name="temperature'.$i.'" data-rule-required="true" class="form-control" required>';
    for($s=1;$s<=50;$s++)
    {
     echo '<option value="'.$s.'">'.$s.' Degree</option>';
   }  
   echo '</select>                
 </div>
</div>';
echo '<div class="form-group">
<label class="control-label col-sm-3" for="validation_current">Weather Type </label>
<div class="col-sm-4 controls">
  <input type="checkbox" name="weather_type'.$i.'[]" value="1"> Mostly Sunny <br>                 
  <input type="checkbox" name="weather_type'.$i.'[]" value="2"> Partly Cloudy <br>                  
  <input type="checkbox" name="weather_type'.$i.'[]" value="3"> PM Shower <br>              
</div>
</div>';
echo '<div class="form-group">
<label class="control-label col-sm-3" for="validation_current">Weather Details
</label>
<div class="col-sm-4 controls">
  <textarea name="weather_des'.$i.'" data-rule-required="true" class="form-control" data-rule-required="true" cols="70" rows="3" placeholder="Weather"></textarea>
</div>
</div>';                      
}
}

/*  public function add_tour_save() {
    $data = $this->input->post();
    //debug($data); exit;
    $package_name   = sql_injection($data['package_name']);
    $package_description = sql_injection($data['package_description']);
    $supplier_name   = sql_injection($data['supplier_name']);
        //$destination    = sql_injection($data['destination']);
        $tours_continent= sql_injection($data['tours_continent']);
        //$tours_country  = sql_injection($data['tours_country']);

        $tours_city      = $data['tours_city'];
        $tours_city_new     = $data['tours_city_new'];
        $tours_city = $tours_city_new;
        //debug($tours_city); exit;
        $tours_city     = implode(',',$tours_city);
        $duration       = sql_injection($data['duration']);

        //$tour_type      = sql_injection($data['tour_type']);

        $tour_type          = $data['tour_type'];
        $tour_type          = implode(',',$tour_type);

        $tours_country      = $data['tours_country'];
        $tours_country      = implode(',',$tours_country);


        $theme          = $data['theme'];
        $theme          = implode(',',$theme);
        $admin_approve_status = 1;


        
        $AUTO_INCREMENT = $this->tours_model->AUTO_INCREMENT('tours');
        $package_id     = 'AIRTP'.date('m').date('y').$AUTO_INCREMENT;

        $query = "insert into tours set package_id='$package_id',
        package_name='$package_name',
        package_description='$package_description',
        tour_type='$tour_type',
        theme='$theme', 
        tours_continent='$tours_continent', 
        tours_country='$tours_country',
        tours_city='$tours_city',
        duration='$duration',
        admin_approve_status = '$admin_approve_status',
        added_by = 'Admin',
        supplier_name = '$supplier_name',
        date=now()";        
    //  echo $query; exit;
    $return = $this->tours_model->add_tour_save($query);
    if($return)
    {   //echo 'saved'; exit;
            //redirect('tours/tour_list');
            redirect('tours/tour_dep_dates_p2/'.$package_id); 
        }
        else
        { echo $return; exit; }              
      }*/
      public function add_tour_save() {
        $data = $this->input->post();
        // debug($data);exit();
        $this->session->unset_userdata('edit_itinary');        
        $package_name   = sql_injection($data['package_name']);
         
        $package_description = sql_injection($data['package_description']);
        $exprie_date = date('Y-m-d',strtotime($data['tour_expire_date']));
        $start_date = date('Y-m-d',strtotime($data['tour_start_date']));
        $supplier_id=$this->entity_user_id; 
        $supplier_name   = sql_injection($data['supplier_name']);
        $tours_continent= sql_injection($data['tours_continent']);         

        $tours_city      = $data['tours_city'];
        $tours_city_new     = $data['tours_city_new'];
        $tours_city = $tours_city_new;
        $tours_city     = implode(',',$tours_city);
        $duration       = sql_injection($data['duration']);

       
        $tour_type          = $data['tour_type_new'];
        $tour_type          = implode(',',$tour_type);
        $tours_country      = $data['tours_country'];
        $tours_country      = implode(',',$tours_country);
        $theme          = $data['theme'];
        $theme          = implode(',',$theme);

        $sim_quantity = $data['sim_quantity'];
        $sim_type = $data['sim_type'];
        $sim_price = $data['sim_price'];
 
        $admin_approve_status = 1;        


        $AUTO_INCREMENT = $this->tours_model->AUTO_INCREMENT('tours');

       
        $package_id     = 'ZH'.date('m').date('y').$AUTO_INCREMENT;

     
        $tours_data = array(
         'package_id'=>$package_id,
         'package_name'=>ucwords($package_name),
         'package_description'=>$package_description,
         'expire_date' =>$exprie_date,
         'start_date' =>$start_date,
         'tour_type'=>$tour_type,
         'theme'=>$theme,
         'tours_continent'=>$tours_continent,
         'tours_country'=>$tours_country,
         'tours_city'=>$tours_city,
         'duration'=>$duration,
         'admin_approve_status'=>$admin_approve_status,
         'agent_id'=>$this->entity_user_id,
         'added_by'=>'Supplier',
         'supplier_id'=>$supplier_id,
         'supplier_name'=>$supplier_name,
         'date'=>date('Y-m-d'),
         'sim_quantity'=>$sim_quantity,
         'sim_type'=>$sim_type,
         'sim_price'=>$sim_price         
       );   

        // debug($tours_data);exit();  
        $return = $this->custom_db->insert_record('tours',$tours_data);
        //    debug($return);
        // exit(); 

        if($return['status'])
        {
         $tour_id = $return['insert_id'];
         foreach ($data['tours_country'] as $country_id) {

          // debug(array('tour_id' => $tour_id, 'country_id' => $country_id));exit();
          $this->custom_db->insert_record('tours_country_wise',array('tour_id' => $tour_id, 'country_id' => $country_id));
        }
        foreach ($data['tours_city_new'] as $city_id) {
          $this->custom_db->insert_record('tours_city_wise',array('tour_id' => $tour_id, 'city_id' => $city_id));
        }
        redirect('tours/tour_dep_dates_p2/'.$package_id); 
      }
      else
      { 
       echo $return; exit; 
     }              
   }
   public function tour_dep_date($tour_dep_date) {
    //echo $tour_dep_date; exit;
    echo '<div class="form-group">
    <label class="control-label col-sm-3" for="validation_current">Departure Dates : </label>
  </div>';
  echo '<div class="form-group">
  <label class="control-label col-sm-3" for="validation_current">Date </label>
  <div class="col-sm-4 controls">
   <input type="text" name="weather_loc_name'.$i.'" value="'.$tour_dep_date.'">                 
 </div>
</div>';
}
public function tour_list() { 

 $tour_list = $this->tours_model->tour_list();
 $page_data['tour_list'] = $tour_list;

        $tour_destinations = $this->tours_model->get_tour_destinations(); 
        // debug($tour_list);exit;
        // debug($tour_destinations);exit;
        $page_data['tour_destinations'] = $tour_destinations; 
        $tour_dep_dates_list_all = $this->tours_model->tour_dep_dates_list_all(); 
        // debug($tour_dep_dates_list_all);exit;
        $page_data['tour_dep_dates_list_all'] = $tour_dep_dates_list_all; 
        $tour_dep_dates_list_published = $this->tours_model->tour_dep_dates_list_published(); 
        $tour_dep_dates_list_published_wd = $this->tours_model->tour_dep_dates_list_published_wd(); 
        // debug($tour_dep_dates_list_all);exit;
        $page_data['tour_dep_dates_list_published'] = $tour_dep_dates_list_published; 
        $page_data['tour_dep_dates_list_published_wd'] = $tour_dep_dates_list_published_wd; 

       $page_data['tours_city_name'] = $this->tours_model->tours_city_name();
        $page_data['tours_country_name'] = $this->tours_model->tours_country_name();
        $page_data['country_code_list'] = $this->db_cache_api->get_country_code_list();
       // debug($page_data);exit();
        $this->template->view('tours/tour_list',$page_data);
        $array = array(
          'back_link' => base_url().$this->router->fetch_class().'/'.$this->router->fetch_method(),
          'edit_itinary' => true
          );    
        $this->session->set_userdata( $array );
      }
      public function agent_tour_list() { 
        $query = 'select * from tours where admin_approve_status = 1 AND agent_id IS NOT NULL  AND status_delete != "1" order by id desc'; 
        $tour_list = $this->custom_db->get_result_by_query($query);
        $page_data['tour_list'] = json_decode(json_encode($tour_list),true);
        $tour_destinations = $this->tours_model->get_tour_destinations();
        $page_data['tour_destinations'] = $tour_destinations; 
        $tour_dep_dates_list_all = $this->tours_model->tour_dep_dates_list_all();
        $page_data['tour_dep_dates_list_all'] = $tour_dep_dates_list_all; 
        $tour_dep_dates_list_published = $this->tours_model->tour_dep_dates_list_published(); 
        $tour_dep_dates_list_published_wd = $this->tours_model->tour_dep_dates_list_published_wd();
        $page_data['tour_dep_dates_list_published'] = $tour_dep_dates_list_published; 
        $page_data['tour_dep_dates_list_published_wd'] = $tour_dep_dates_list_published_wd;
        $page_data['tours_city_name'] = $this->tours_model->tours_city_name();
        $page_data['tours_country_name'] = $this->tours_model->tours_country_name();
        $page_data['country_code_list'] = $this->db_cache_api->get_country_code_list();
        $this->template->view('tours/tour_list_agent',$page_data);
        $array = array(
          'back_link' => base_url().$this->router->fetch_class().'/'.$this->router->fetch_method(),
          'edit_itinary' => true
          );    
        $this->session->set_userdata( $array );
      }

  public function tour_list_pending() { //echo 'tours'; exit;

  //  echo "hiii";exit();
 $tour_list = $this->tours_model->tour_list_pending();
    //debug($tour_list); exit();
 $page_data['tour_list'] = $tour_list;

    $tour_destinations = $this->tours_model->get_tour_destinations(); //debug($tour_destinations);exit;
    $page_data['tour_destinations'] = $tour_destinations; 
    $tour_dep_dates_list_all = $this->tours_model->tour_dep_dates_list_all(); //debug($tour_dep_dates_list_all);exit;
    $page_data['tour_dep_dates_list_all'] = $tour_dep_dates_list_all; 
    $tour_dep_dates_list_published = $this->tours_model->tour_dep_dates_list_published(); //debug($tour_dep_dates_list_all);exit;
    $page_data['tour_dep_dates_list_published'] = $tour_dep_dates_list_published; 

    $page_data['tours_city_name'] = $this->tours_model->tours_city_name();
    $page_data['tours_country_name'] = $this->tours_model->tours_country_name();
        //debug($page_data); exit;
    $this->template->view('tours/tour_list_pending',$page_data);
  }
  public function activation_tour_package($id,$status) {
    $query = "update tours set status='$status' where id='$id'";
    $return = $this->tours_model->activation_tour_package($query);
    if($return){redirect('tours/tour_list_pending');} 
    else { echo $return;} 
  }
  public function delete_tour_package($id) { //echo 'id'.$id; exit;
  $return = $this->tours_model->delete_tour_package($id);
  redirect('tours/tour_list');
}
  public function tour_dep_dates($tour_id) { //echo 'tour_dep_dates'; exit;
 $page_data['tour_id'] = $tour_id;
 $page_data['tour_data'] = $this->tours_model->tour_data($tour_id);
 $tour_dep_dates = $this->tours_model->tour_dep_dates($tour_id);
 $page_data['tour_dep_dates'] = $tour_dep_dates;
 $this->template->view('tours/tour_dep_dates',$page_data);
}
public function tour_dep_dates_p2($package_id) { 
    //debug($_POST); exit();
    $package_data = $this->tours_model->package_data($package_id)[0]; 
    //debug($package_id); debug($package_data);exit;
    $tour_id = $package_data['id'];

   $page_data['tour_id'] = $tour_id;
   $page_data['tour_data'] = $this->tours_model->tour_data($tour_id)[0];
   $tour_dep_dates = $this->tours_model->tour_dep_dates($tour_id);
   $page_data['tour_dep_dates'] = $tour_dep_dates;
   if(!empty($tour_dep_dates))
   {
     $page_data['flow'] = 'Next';
   }
    //debug($page_data);exit;   
   $this->template->view('tours/tour_dep_dates_p2',$page_data);
 }
 public function tour_dep_date_save() {
  $data = $this->input->post();
    //debug($data);exit;
  $dep_date = sql_injection($data['tour_dep_date']);
  $tour_id  = sql_injection($data['tour_id']);

  $check_tour_dep_dates = $this->tours_model->check_tour_dep_dates($tour_id,$dep_date);
  if($check_tour_dep_dates>0)
  {
   redirect('tours/tour_dep_dates/'.$tour_id); exit;
 }

 $query  = "insert into tour_dep_dates set tour_id='$tour_id',dep_date='$dep_date'";
 $return = $this->tours_model->tour_dep_date_save($query);
 if($return)
 {
   redirect('tours/tour_dep_dates/'.$tour_id);
 } 
 else { echo $return;}  
}
public function tour_dep_dates_p2_save() {
  $data = $this->input->post();   
    // debug($data); exit();
  $dep_date = sql_injection($data['tour_dep_date']);
  $tour_id  = sql_injection($data['tour_id']);

    $tour_data  = $this->tours_model->tour_data($tour_id)[0]; 
    // debug($tour_data);exit;
    $package_id = $tour_data['package_id'];
    //if(isset($data['ask_for_select']) && $data['ask_for_select']){

   $check_tour_dep_dates = $this->tours_model->check_tour_dep_dates($tour_id,$dep_date);

   if($check_tour_dep_dates>0)
   {
    redirect('tours/tour_dep_dates_p2/'.$package_id); exit;
  }

  $query  = "insert into tour_dep_dates set tour_id='$tour_id',dep_date='$dep_date'";
  $return = $this->tours_model->tour_dep_date_save($query);
  // debug($return);exit();
  if($return)
  {
    redirect('tours/tour_dep_dates_p2/'.$package_id);
  } 
  else { echo $return;} 
   //   }
    //redirect('tours/tour_dep_dates_p2/'.$package_id);
}
public function delete_tour_dep_date($id,$tour_id) { 
  $return = $this->tours_model->delete_tour_dep_date($id);
  if($return)
  {
   redirect('tours/tour_dep_dates/'.$tour_id);
 } 
 else
 {
   echo $return;
 }  
}
public function delete_tour_dep_date_p2($id,$tour_id) { 
  $return = $this->tours_model->delete_tour_dep_date($id);
  if($return)
  {
       $tour_data  = $this->tours_model->tour_data($tour_id); //debug($package_data);exit;
       $package_id = $tour_data['package_id'];
       redirect('tours/tour_dep_dates_p2/'.$package_id);
     } 
     else
     {
       echo $return;
     }  
   }
   public function tour_visited_cities($tour_id) {
     $page_data['tour_id'] = $tour_id;
     $page_data['tour_data'] = $this->tours_model->tour_data($tour_id);
     $tour_visited_cities = $this->tours_model->tour_visited_cities($tour_id);
     $page_data['tour_visited_cities'] = $tour_visited_cities;
     $page_data['tours_city_name'] = $this->tours_model->tours_city_name();

     if(!empty($tour_visited_cities))
     {
       $no_of_nights = 0;
       foreach($tour_visited_cities as $tvcKey => $tvcValue)
       {
        $no_of_nights += $tvcValue['no_of_nights'];
      }
      $page_data['total_no_of_nights'] = $no_of_nights;     
    }
    else
    {
     $page_data['total_no_of_nights'] = 0;
   }

   // debug($page_data); exit();

   $this->template->view('tours/tour_visited_cities',$page_data);
 }
  public function tour_visited_cities_p2($tour_id) { //echo $tour_id; exit; 
   $page_data['tour_id']   = $tour_id;
   $page_data['tour_data'] = $this->tours_model->tour_data($tour_id)[0];        
   $tour_visited_cities = $this->tours_model->tour_visited_cities($tour_id);
   // debug($tour_visited_cities);exit; 
   $page_data['tour_visited_cities'] = $tour_visited_cities;
   if(!empty($tour_visited_cities))
   {
     $no_of_nights = 0;
     foreach($tour_visited_cities as $tvcKey => $tvcValue)
     {
      $no_of_nights += $tvcValue['no_of_nights'];
    }
    if($page_data['tour_data']['duration']==$no_of_nights)
    {
      $page_data['flow'] = 'Next';
    }
    $page_data['total_no_of_nights'] = $no_of_nights;     
  }
  else
  {
   $page_data['total_no_of_nights'] = 0;
 }
 $page_data['tours_city_name'] = $this->tours_model->tours_city_name();
    //debug($page_data);exit;
 $this->template->view('tours/tour_visited_cities_p2',$page_data);
}
public function no_of_nights($no_of_nights) {
    //echo 'no_of_nights'.$no_of_nights; exit;
    //$page_data['no_of_nights'] = $no_of_nights;
    //$return = $this->template->view('tours/no_of_nights',$page_data);
    //return $return;

  for($i=1;$i<=$no_of_nights;$i++)
  {
    echo '<hr>';
    echo    '<div class="form-group">
    <label class="control-label col-sm-3" for="validation_current">Day '.$i.' </label>
  </div>';
  echo    '<div class="form-group">
  <label class="control-label col-sm-3" for="validation_current">Day Program Title </label>
  <div class="col-sm-4 controls">
   <input type="text" name="program_title[]"
   placeholder="Enter Program Title" data-rule-required="true"
   class="form-control" required>                 
 </div>
</div>';      
echo    '<div class="form-group">
<label class="control-label col-sm-3" for="validation_current">Program Description
</label>
<div class="col-sm-8 controls">
  <textarea name="program_des[]" data-rule-required="true" class="form-control" data-rule-required="true" cols="70" rows="10" placeholder="Description"></textarea>
</div>
</div>';
echo '<div class="form-group">
<label class="control-label col-sm-3" for="validation_current">Hotel Name </label>
<div class="col-sm-4 controls">
 <input type="text" name="hotel_name[]"
 placeholder="Enter hotel name" data-rule-required="true"
 class="form-control" required>                 
</div>
</div>';
echo '<div class="form-group">
<label class="control-label col-sm-3" for="validation_current">Star Rating </label>
<div class="col-sm-4 controls">
  <select name="rating[]" data-rule-required="true" class="form-control" required>';
    for($s=1;$s<=5;$s++)
    {
     echo '<option value="'.$s.'">'.$s.' Star</option>';
   }  
   echo '</select>                
 </div>
</div>';      
      /*echo '<div class="form-group">
                <label class="control-label col-sm-3" for="validation_current">Hotel Description
                </label>
                <div class="col-sm-4 controls">
                <textarea name="hotel_des[]" data-rule-required="true" class="form-control" data-rule-required="true" cols="70" rows="3" placeholder="Description"></textarea>
                </div>
              </div>';*/          
             echo    '<div class="form-group">
             <label class="control-label col-sm-3" for="validation_current">Accomodation </label>
             <div class="col-sm-4 controls">
               <input type="checkbox" name="accomodation['.($i-1).'][]" value="Breakfast"> Breakfast <br>                 
               <input type="checkbox" name="accomodation['.($i-1).'][]" value="Lunch"> Lunch <br>                 
               <input type="checkbox" name="accomodation['.($i-1).'][]" value="Dinner"> Dinner <br>                 
             </div>
           </div>';

         }    
       }

       public function tour_visited_cities_save() {
        $data = $this->input->post();
    //debug($data); exit;
        $tour_id             = sql_injection($data['tour_id']);
        $city                = sql_injection($data['city']);
        $sightseeing         = sql_injection($data['sightseeing']);
        $no_of_nights        = sql_injection($data['no_of_nights']);
        $includes_city_tours = sql_injection($data['includes_city_tours']);

        $program_title       = $data['program_title'];
        $program_des         = $data['program_des'];
        $hotel_name          = $data['hotel_name'];
        $rating              = $data['rating'];
    //$hotel_des           = $data['hotel_des'];
        $accomodation        = $data['accomodation'];

        $itinerary = array();

        for($i=0;$i<$no_of_nights ;$i++)
        {
         $itinerary[$i]['program_title'] = sql_injection($program_title[$i]);
         $itinerary[$i]['program_des']   = sql_injection($program_des[$i]);
         $itinerary[$i]['hotel_name']    = sql_injection($hotel_name[$i]);
         $itinerary[$i]['rating']        = sql_injection($rating[$i]);
           //$itinerary[$i]['hotel_des']     = sql_injection($hotel_des[$i]);
         $itinerary[$i]['accomodation']  = $accomodation[$i];
       }
    //debug($itinerary); //exit;
       $itinerary = json_encode($itinerary,1);       
        //debug($itinerary);

       $query  = "insert into tour_visited_cities set tour_id='$tour_id',
       city='$city',
       sightseeing='$sightseeing',
       no_of_nights='$no_of_nights',
       includes_city_tours='$includes_city_tours',
       itinerary='$itinerary'";
        //echo $query; exit;
       $return = $this->tours_model->tour_visited_cities_save($query);
       if($return)
       {
         redirect('tours/tour_visited_cities/'.$tour_id);
       } 
       else { echo $return;}  
     }
     public function tour_visited_cities_p2_save() {
      $data = $this->input->post();
    //debug($data); exit;
      $tour_id             = sql_injection($data['tour_id']);
      $city                = $data['city'];
      $city                = json_encode($city,1);
      $no_of_nights        = sql_injection($data['no_of_nights']);
    //$sightseeing         = sql_injection($data['sightseeing']);
    //$includes_city_tours = sql_injection($data['includes_city_tours']);

      $query  = "insert into tour_visited_cities set tour_id='$tour_id',
      city='$city',
      no_of_nights='$no_of_nights'";
        //echo $query; exit;
      $return = $this->tours_model->query_run($query);
      if($return)
      {
       redirect('tours/tour_visited_cities_p2/'.$tour_id);
     } 
     else { echo $return;}  
   }  
  public function delete_tour_visited_cities($id,$tour_id) { //echo 'id'.$id; exit;
  $return = $this->tours_model->delete_tour_visited_cities($id);
  if($return)
  {
   redirect('tours/tour_visited_cities/'.$tour_id);
 } 
 else
 {
   echo $return;
 }  
}
  public function delete_tour_visited_cities_p2($id,$tour_id) { 
    //echo 'id'.$id; exit;
  $return = $this->tours_model->delete_tour_visited_cities($id);
  if($return)
  {
   redirect('tours/tour_visited_cities_p2/'.$tour_id);
 } 
 else
 {
   echo $return;
 }  
}
public function edit_tour_visited_cities($id,$tour_id) { 
  $tour_visited_cities_details = $this->tours_model->tour_visited_cities_details($id);
    //debug($tour_visited_cities_details); exit;
  $page_data['tour_visited_cities_details'] = $tour_visited_cities_details; 
  $page_data['id'] = $id;
  $page_data['tour_id'] = $tour_id;
  $page_data['tour_data'] = $this->tours_model->tour_data($tour_id);
  $page_data['tours_city_name'] = $this->tours_model->tours_city_name();  
  $this->template->view('tours/edit_tour_visited_cities',$page_data);
}
public function edit_tour_visited_cities_p2($id,$tour_id) { 
  $tour_visited_cities_details = $this->tours_model->tour_visited_cities_details($id);
    //debug($tour_visited_cities_details); exit;
  $page_data['tour_visited_cities_details'] = $tour_visited_cities_details; 
  $page_data['id'] = $id;
  $page_data['tour_id'] = $tour_id;
  $page_data['tour_data'] = $this->tours_model->tour_data($tour_id);  
  $page_data['tours_city_name'] = $this->tours_model->tours_city_name();
  $tour_visited_cities = $this->tours_model->tour_visited_cities($tour_id);
  $page_data['tour_visited_cities'] = $tour_visited_cities;
  if(!empty($tour_visited_cities))
  {
   $no_of_nights = 0;
   foreach($tour_visited_cities as $tvcKey => $tvcValue)
   {
    $no_of_nights += $tvcValue['no_of_nights'];
  }     
  $page_data['total_no_of_nights'] = $no_of_nights;     
}
else
{
 $page_data['total_no_of_nights'] = 0;
}
$this->template->view('tours/edit_tour_visited_cities_p2',$page_data);
}

public function edit_tour_visited_cities_save() {
  $data = $this->input->post();
    //debug($data); exit;

  $id                  = sql_injection($data['id']);
  $tour_id             = sql_injection($data['tour_id']);
  $city                = $data['city'];
  $city                = json_encode($city);
  $no_of_nights        = sql_injection($data['no_of_nights']);

  $query  = "update tour_visited_cities set city='$city', no_of_nights='$no_of_nights' where id='$id'";
        //echo $query; exit;
  $return = $this->tours_model->edit_tour_visited_cities_save($query);
  if($return)
  {
   redirect('tours/edit_tour_visited_cities/'.$id.'/'.$tour_id);
 } 
 else { echo $return; } 
}
public function edit_tour_visited_cities_p2_save() {
  $data = $this->input->post();
    //debug($data); exit;

  $id                  = sql_injection($data['id']);
  $tour_id             = sql_injection($data['tour_id']);
  $city                = $data['city'];
  $city                = json_encode($city,1);
  $no_of_nights        = sql_injection($data['no_of_nights']);

  $query  = "update tour_visited_cities set city='$city', no_of_nights='$no_of_nights' where id='$id'";
        //echo $query; exit;
  $return = $this->tours_model->edit_tour_visited_cities_save($query);
  if($return)
  {
   redirect('tours/tour_visited_cities_p2/'.$tour_id);
 } 
 else { echo $return; } 
}
public function no_of_nights2($no_of_nights,$id,$tour_id) {
      //echo $no_of_nights.$id.$tour_id; exit;
  $tour_data = $this->tours_model->tour_data($tour_id);
  $tour_visited_cities_details = $this->tours_model->tour_visited_cities_details($id);

  if($no_of_nights<$tour_visited_cities_details['no_of_nights'])
  {

   $itinerary = $tour_visited_cities_details['itinerary']; 
   $itinerary = json_decode($itinerary,1);
              //echo '<pre>'; print_r($itinerary);
   foreach($itinerary as $key => $value)
   {
     $accomodation = $value['accomodation'];
     if(in_array('Breakfast',$accomodation))
       {$Breakfast='checked';}else{$Breakfast='';}
     if(in_array('Lunch',$accomodation))
       {$Lunch='checked';}else{$Lunch='';}
     if(in_array('Dinner',$accomodation))
       {$Dinner='checked';}else{$Dinner='';}
     echo '<hr>';
     echo    '<div class="form-group">
     <label class="control-label col-sm-3" for="validation_current">Day '.($key+1).' </label>
   </div>';
   echo    '<div class="form-group">
   <label class="control-label col-sm-3" for="validation_current">Day Program Title </label>
   <div class="col-sm-4 controls">
     <input type="text" name="program_title[]" value="'.trim(addslashes($value['program_title'])).'"
     placeholder="Enter Program Title" data-rule-required="true"
     class="form-control" required>                 
   </div>
 </div>';     
 echo    '<div class="form-group">
 <label class="control-label col-sm-3" for="validation_current">Program Description
 </label>
 <div class="col-sm-8 controls">
  <textarea name="program_des[]" data-rule-required="true" class="form-control" data-rule-required="true" cols="70" rows="5" placeholder="Description">'.$value['program_des'].'</textarea>
</div>
</div>';
echo '<div class="form-group">
<label class="control-label col-sm-3" for="validation_current">Hotel Name </label>
<div class="col-sm-4 controls">
 <input type="text" name="hotel_name[]" value="'.trim(addslashes($value['hotel_name'])).'"
 placeholder="Enter hotel name" data-rule-required="true"
 class="form-control" required>                 
</div>
</div>';
echo '<div class="form-group">
<label class="control-label col-sm-3" for="validation_current">Star Rating </label>
<div class="col-sm-4 controls">
  <select name="rating[]" data-rule-required="true" class="form-control" required>';
    for($s=1;$s<=5;$s++)
    {
     echo '<option value="'.$s.'">'.$s.' Star</option>';
   }  
   echo '</select>                
 </div>
</div>';      
      /*echo '<div class="form-group">
                <label class="control-label col-sm-3" for="validation_current">Hotel Description
                </label>
                <div class="col-sm-4 controls">
                <textarea name="hotel_des[]" data-rule-required="true" class="form-control" data-rule-required="true" cols="70" rows="3" placeholder="Description">'.$value['hotel_des'].'</textarea>
                </div>
              </div>';*/          
             echo  '<div class="form-group">
             <label class="control-label col-sm-3" for="validation_current">Accomodation </label>
             <div class="col-sm-4 controls">
               <input type="checkbox" name="accomodation['.($key).'][]" value="Breakfast" '.$Breakfast.'> Breakfast <br>                  
               <input type="checkbox" name="accomodation['.($key).'][]" value="Lunch" '.$Lunch.'> Lunch <br>                  
               <input type="checkbox" name="accomodation['.($key).'][]" value="Dinner" '.$Dinner.'> Dinner <br>                 
             </div>
           </div>';
           if($no_of_nights==($key+1))
             {break;}
         }

       }
       else
       {
        $itinerary = $tour_visited_cities_details['itinerary']; 
        $itinerary = json_decode($itinerary,1);
              //echo '<pre>'; print_r($itinerary);
        foreach($itinerary as $key => $value)
        {
         $accomodation = $value['accomodation'];
         if(in_array('Breakfast',$accomodation))
           {$Breakfast='checked';}else{$Breakfast='';}
         if(in_array('Lunch',$accomodation))
           {$Lunch='checked';}else{$Lunch='';}
         if(in_array('Dinner',$accomodation))
           {$Dinner='checked';}else{$Dinner='';}
         echo '<hr>';
         echo    '<div class="form-group">
         <label class="control-label col-sm-3" for="validation_current">Day '.($key+1).' </label>
       </div>';
       echo    '<div class="form-group">
       <label class="control-label col-sm-3" for="validation_current">Day Program Title </label>
       <div class="col-sm-4 controls">
         <input type="text" name="program_title[]" value="'.trim(addslashes($value['program_title'])).'"
         placeholder="Enter Program Title" data-rule-required="true"
         class="form-control" required>                 
       </div>
     </div>';     
     echo    '<div class="form-group">
     <label class="control-label col-sm-3" for="validation_current">Program Description
     </label>
     <div class="col-sm-8 controls">
      <textarea name="program_des[]" data-rule-required="true" class="form-control" data-rule-required="true" cols="70" rows="5" placeholder="Description">'.$value['program_des'].'</textarea>
    </div>
  </div>';
  echo '<div class="form-group">
  <label class="control-label col-sm-3" for="validation_current">Hotel Name </label>
  <div class="col-sm-4 controls">
   <input type="text" name="hotel_name[]" value="'.trim(addslashes($value['hotel_name'])).'"
   placeholder="Enter hotel name" data-rule-required="true"
   class="form-control" required>                 
 </div>
</div>';
echo '<div class="form-group">
<label class="control-label col-sm-3" for="validation_current">Star Rating </label>
<div class="col-sm-4 controls">
  <select name="rating[]" data-rule-required="true" class="form-control" required>';
    for($s=1;$s<=5;$s++)
    {
     echo '<option value="'.$s.'">'.$s.' Star</option>';
   }  
   echo '</select>                
 </div>
</div>';      
      /*echo '<div class="form-group">
                <label class="control-label col-sm-3" for="validation_current">Hotel Description
                </label>
                <div class="col-sm-4 controls">
                <textarea name="hotel_des[]" data-rule-required="true" class="form-control" data-rule-required="true" cols="70" rows="3" placeholder="Description">'.$value['hotel_des'].'</textarea>
                </div>
              </div>';*/          
             echo  '<div class="form-group">
             <label class="control-label col-sm-3" for="validation_current">Accomodation </label>
             <div class="col-sm-4 controls">
               <input type="checkbox" name="accomodation['.($key).'][]" value="Breakfast" '.$Breakfast.'> Breakfast <br>                  
               <input type="checkbox" name="accomodation['.($key).'][]" value="Lunch" '.$Lunch.'> Lunch <br>                  
               <input type="checkbox" name="accomodation['.($key).'][]" value="Dinner" '.$Dinner.'> Dinner <br>                 
             </div>
           </div>';             
         }  
       }
       if($no_of_nights>$tour_visited_cities_details['no_of_nights']) {
        for($i=$tour_visited_cities_details['no_of_nights']+1;$i<=$no_of_nights;$i++)
        {
          echo '<hr>';
          echo    '<div class="form-group">
          <label class="control-label col-sm-3" for="validation_current">Day '.$i.' </label>
        </div>';
        echo    '<div class="form-group">
        <label class="control-label col-sm-3" for="validation_current">Day Program Title </label>
        <div class="col-sm-4 controls">
         <input type="text" name="program_title[]"
         placeholder="Enter Program Title" data-rule-required="true"
         class="form-control" required>                 
       </div>
     </div>';     
     echo    '<div class="form-group">
     <label class="control-label col-sm-3" for="validation_current">Program Description
     </label>
     <div class="col-sm-8 controls">
      <textarea name="program_des[]" data-rule-required="true" class="form-control" data-rule-required="true" cols="70" rows="5" placeholder="Description"></textarea>
    </div>
  </div>';
  echo '<div class="form-group">
  <label class="control-label col-sm-3" for="validation_current">Hotel Name </label>
  <div class="col-sm-4 controls">
   <input type="text" name="hotel_name[]"
   placeholder="Enter hotel name" data-rule-required="true"
   class="form-control" required>                 
 </div>
</div>';
echo '<div class="form-group">
<label class="control-label col-sm-3" for="validation_current">Star Rating </label>
<div class="col-sm-4 controls">
  <select name="rating[]" data-rule-required="true" class="form-control" required>';
    for($s=1;$s<=5;$s++)
    {
     echo '<option value="'.$s.'">'.$s.' Star</option>';
   }  
   echo '</select>                
 </div>
</div>';      
      /*echo '<div class="form-group">
                <label class="control-label col-sm-3" for="validation_current">Hotel Description
                </label>
                <div class="col-sm-4 controls">
                <textarea name="hotel_des[]" data-rule-required="true" class="form-control" data-rule-required="true" cols="70" rows="3" placeholder="Description"></textarea>
                </div>
              </div>';*/          
             echo  '<div class="form-group">
             <label class="control-label col-sm-3" for="validation_current">Accomodation </label>
             <div class="col-sm-4 controls">
               <input type="checkbox" name="accomodation['.($i-1).'][]" value="Breakfast"> Breakfast <br>                 
               <input type="checkbox" name="accomodation['.($i-1).'][]" value="Lunch"> Lunch <br>                 
               <input type="checkbox" name="accomodation['.($i-1).'][]" value="Dinner"> Dinner <br>                 
             </div>
           </div>';
         }
       }  
     }
     public function edit_tour_package($tour_id) {
      error_reporting(0);  




    $tour_data = $this->tours_model->tour_data($tour_id)[0]; //debug($tour_data); exit;  
    $page_data['tour_data'] = $tour_data;
    $tour_destinations = $this->tours_model->tour_destinations();
    $page_data['tour_destinations'] = $tour_destinations;
    $page_data['tour_id'] = $tour_id;

    $tours_continent = $this->tours_model->tours_continent();

    $page_data['tours_continent_country'] = $this->tours_model->tours_continent_country($tour_id);
    $page_data['tours_country_city']      = $this->tours_model->tours_country_city($tour_id);
    $page_data['tours_country_name']      = $this->tours_model->tour_country();

    $page_data['tours_continent'] = $tours_continent;
    $page_data['tour_type'] = $this->tours_model->tour_type();
    $page_data['tour_subtheme'] = $this->tours_model->tour_subtheme();
   // debug($page_data['tour_subtheme']); exit;
   // debug($page_data);exit();
    $this->template->view('tours/edit_tour_package',$page_data);
  }
  public function edit_tour_package_save() {
   //  error_reporting(E_ALL);
    $data = $this->input->post();
   //debug( $data );exit();
    $tour_id = $data['tour_id'];
        $query_x = "select * from tours where id='$tour_id'"; // echo $query; exit;
        /*$exe_x   = mysql_query($query_x);
        $fetch_x = mysql_fetch_array($exe_x);*/
        $fetch_x = $this->db->query ( $query_x )->result_array ()[0];
       // debug(   $fetch_x );exit;
        $old_image = $fetch_x['gallery'];
        $package_name          = ucwords($data['package_name']);
        $package_description = $data['package_description'];
        $tour_expire_date = $data['tour_expire_date'];
        $tour_start_date = $data['tour_start_date'];
        $supplier_name = $data['supplier_name'];

         $image_description = $data['image_description'];
         $tours_continent       = $data['tours_continent'];
         $tours_city_new     = $data['tours_city_new'];
         $tours_city = $tours_city_new;
         $tours_city     = implode(',',$tours_city);
        $duration       = $data['duration'];
        $tour_type          = $data['tour_type'];
        $tour_type          = implode(',',$tour_type);
       $tours_country      = $data['tours_country'];
       $tours_country      = implode(',',$tours_country);
        $theme          = $data['theme'];
        $theme          = implode(',',$theme);
        $adult_twin_sharing    = $data['adult_twin_sharing'];
        $adult_tripple_sharing = $data['adult_tripple_sharing'];
        if($adult_tripple_sharing=='')
        {
         $adult_tripple_sharing = 0;
       }
       else
       {
         $adult_tripple_sharing  = $adult_tripple_sharing;
      }

       $highlights            = $data['highlights'];
       $inclusions            = $data['inclusions'];
       $exclusions            = $data['exclusions'];
       $terms                 = $data['terms'];
       $optional_tours        = $data['optional_tours'];
       $canc_policy           = $data['canc_policy'];
       $trip_notes           = $data['trip_notes'];

       $ppg        = @$_REQUEST['gallery_previous'];
       $total_ppg  = count($ppg) ;
       $ppg_list   = '';
       for($c=0;$c<$total_ppg;$c++)
       {
        if($ppg_list=='')
        {
          $ppg_list = $ppg[$c];
        }
        else
        {
          $ppg_list = $ppg_list.','.$ppg[$c];
        }       
      }
      if($total_ppg>0)
      {
        $ppg_list = $ppg_list.',';
      }
      else
      {
        $ppg_list = '';
      } 
      $arr=array();
      if($_FILES['gallery']['name'][0]!="")
      {       
        $list  = $_FILES['gallery']['name'];
        $total_images = count($list); 
        for($i=0;$i<$total_images;$i++)
        {
             // for setting the unique name of image starts @@@@@@@@@@@@@@@@@@@
          $filename  = time().basename($list[$i]);
          $extension = pathinfo($filename, PATHINFO_EXTENSION);
          $uniqueno  = substr(uniqid(),0,5);
          $randno    = substr(rand(),0,5);
          $new       = $uniqueno.$randno.'.'.$extension;
          $folder    = $this->template->domain_image_upload_path();
          $folderpath= trim($folder.$new);
          $path      = addslashes($folderpath);
          move_uploaded_file($_FILES['gallery']['tmp_name'][$i], $folderpath);  
          array_push($arr,$new);

        } 
      } 
     

      if(!empty($_FILES['banner_image']['name']))
      {
       $banner_image = $_FILES['banner_image']['name'];
       $banner_image = time().$banner_image;
       $filename     = basename($banner_image);
       $extension    = pathinfo($filename, PATHINFO_EXTENSION);
       $uniqueno     = substr(uniqid(),0,5);
       $randno       = substr(rand(),0,5);
       $new          = $uniqueno.$randno.'.'.$extension;
       $folder       = $this->template->domain_image_upload_path();
       $folderpath   = trim($folder.$new);
       $path         = addslashes($folderpath);
       move_uploaded_file($_FILES['banner_image']['tmp_name'], $folderpath);             
       $banner_image = $new; 
       $banner_image_update = 'banner_image="'.$banner_image.'",'; 
     }else
     {
       $banner_image_update = '';
      
     }
     
     $old_image =explode(',', $old_image);
     $inclusions_checks   = $data['inclusions_checks'];
     $inclusions_checks   = json_encode($inclusions_checks,1);
    
     

     $Gallery_list = $Gallery_list_arr = array_merge($arr,$old_image);
     $Gallery_list = implode(',', $Gallery_list);
     $Gallery_list_arr = array_filter($Gallery_list_arr);
     //debug($Gallery_list_arr);die();
     ///code 2017-11-16 
      $img_desc = strip_tags($data['image_description']);

      if($img_desc){
       $desc_array = explode("#", $img_desc);
       unset($desc_array[0]);
       $desc_array = array_values($desc_array);
       //$desc_array = array_splice($desc_array, 0, 1);
       //debug($desc_array);exit();
       $ker_array = [];
       if($banner_image){
       array_push($ker_array, $banner_image);
       }else{
       array_push($ker_array, $fetch_x['banner_image']);
       }
       foreach($Gallery_list_arr as $value) {
          array_push($ker_array, $value);
       }
       //debug($ker_array);exit();
      if(count($ker_array)==count($desc_array)){
      $image_description_new = array_combine($ker_array,$desc_array);
      }else{
        //echo $fetch_x['image_description'];die();
      $image_description_new =   trim($fetch_x['image_description'],'"');
      }
      
      $image_description_new = json_encode($image_description_new);
      //debug($image_description_new );die();
     }else{
      $image_description_new='';
     }
      $image_description_new = trim($image_description_new);
     // debug($image_description_new);die();
     //end code

     /* tours_continent='$tours_continent',
      tours_country='$tours_country',
      tours_city='$tours_city',*/
      $highlights=str_replace("'", "", @$highlights);
      $inclusions=str_replace("'", "", @$inclusions);
      $exclusions=str_replace("'", "", @$exclusions);
      $terms=str_replace("'", "", @$terms);
      $canc_policy=str_replace("'", "", @$canc_policy);
      $trip_notes=str_replace("'", "", @$trip_notes);
      //debug($data['tour_id']);exit('123');

      $supplier_id=$this->entity_user_id; 
      $tour_id = $data['tour_id'];
      $query  = "update tours set package_name='$package_name',
      package_description='$package_description',
      expire_date='$tour_expire_date',
      start_date='$tour_start_date',
      supplier_id = '$supplier_id',
      supplier_name = '$supplier_name',
      duration='$duration',
      tour_type='$tour_type',
      theme='$theme',
      adult_twin_sharing='$adult_twin_sharing',
      adult_tripple_sharing='$adult_tripple_sharing',
      child_with_bed='$child_with_bed',
      child_without_bed='$child_without_bed',
      joining_directly='$joining_directly',
      single_suppliment='$single_suppliment',
      service_tax='$service_tax',
      tcs='$tcs',
      image_description='$image_description_new',
      highlights='$highlights',
      inclusions='$inclusions',
      exclusions='$exclusions',
      terms='$terms',
      optional_tours= '$optional_tours',
      canc_policy='$canc_policy',
      trip_notes='$trip_notes',
      inclusions_checks='$inclusions_checks',
      ".$banner_image_update."
      gallery='$Gallery_list'
      where id='$tour_id'";
     // echo $query;exit();
      //changed query by Nikhil
      // $data_update =array(
      //   'package_name'=>$package_name,


      //   );
      // $this->db->where(array('id'=>$tour_id));
      // $this->db->update('tours',$data_update);

      $return = $this->tours_model->query_run($query);
     // debug($this->db->last_query());exit();
      $tours_itinerary_data = array(
       'adult_twin_sharing'=>$adult_twin_sharing,
       'highlights'=>$highlights,
       'inclusions'=>$inclusions,
       'exclusions'=>$exclusions,
       'terms'=>$terms,
       'canc_policy'=>$canc_policy,
       'inclusions_checks'=>$inclusions_checks,
       );
     // debug($tours_itinerary_data);exit();
      $this->custom_db->update_record('tours_itinerary',$tours_itinerary_data , array('tour_id'=>$tour_id));
      if($return)
      {
       header('Location: '.base_url().'tours/edit_tour_package/'.$tour_id);

     } 
     else { echo $return; } 
   }
   public function tour_pricing($tour_id) { 
     $tour_data = $this->tours_model->tour_data($tour_id);

     $page_data['tour_data'] = $tour_data;
     $page_data['tour_id']   = $tour_id;
     $this->template->view('tours/tour_pricing',$page_data);
   }
   public function tour_pricing_p2($tour_id) {
     $tour_data = $this->tours_model->tour_data($tour_id)[0];
     $get_tc = $this->tours_model->get_holiday_tc();
     $page_data['terms_n_Conditions'] = $get_tc;
    //debug($get_tc); exit;
    //debug($tour_data); exit;

     $page_data['tour_data'] = $tour_data;
     $page_data['tour_id']   = $tour_id;
     $this->template->view('tours/tour_pricing_p2',$page_data);
   }
   public function tour_pricing_save() {



    $data = $this->input->post();
    //debug($data); exit;
    $tour_id               = sql_injection($data['tour_id']);
    $adult_twin_sharing    = sql_injection($data['adult_twin_sharing']);
    $adult_tripple_sharing = sql_injection($data['adult_tripple_sharing']);
    $child_with_bed        = sql_injection($data['child_with_bed']);
    $child_without_bed     = sql_injection($data['child_without_bed']);
    $joining_directly      = sql_injection($data['joining_directly']);

    $query  = "update tours set adult_twin_sharing='$adult_twin_sharing',
    adult_tripple_sharing='$adult_tripple_sharing',
    child_with_bed='$child_with_bed',
    child_without_bed='$child_without_bed',
    joining_directly='$joining_directly'
    where id='$tour_id'";
    // echo $query; exit;
    $return = $this->tours_model->query_run($query);
    if($return)
    {
     redirect('tours/tour_pricing/'.$tour_id);
   } 
   else { echo $return;}  
 }


 public function tour_pricing_p2_save() {
  $list  = $_FILES['gallery']['name'];

  $total_images = count($list);
  for($i=0;$i<$total_images;$i++)
  {
   $filename  = basename($list[$i]);
   $extension = pathinfo($filename, PATHINFO_EXTENSION);
   $uniqueno  = substr(uniqid(),0,5);
   $randno = substr(rand(),0,5);

   if($_FILES['gallery']['name'])
   {
    $new = $uniqueno.$randno.'.'.$extension;
  }
  else
  {
    $new = $extension;   
  }
  
  $folder = $this->template->domain_image_upload_path();
  $folderpath = trim($folder.$new);
  $path = addslashes($folderpath);
  move_uploaded_file($_FILES['gallery']['tmp_name'][$i], $folderpath);            
  if($i==0)
  { 
    $Gallery_list = $new;
  }
  else
  {
    $Gallery_list = $Gallery_list.",".$new;  
  } 
} 
//debug($Gallery_list);exit();
$Gallery_list_check = explode(".", $Gallery_list);
if(end($Gallery_list_check)!=''){
if($Gallery_list){
     $image_name_db = explode(",",$Gallery_list);
 }
}else{
  $Gallery_list="";
}
//debug($image_name_db);exit();

$banner_image = $_FILES['banner_image']['name'];
$banner_image = $banner_image; 
$filename = basename($banner_image);

$extension = pathinfo($filename, PATHINFO_EXTENSION);
$uniqueno = substr(uniqid(),0,5);
$randno = substr(rand(),0,5);
if($banner_image)
{
 $new = $uniqueno.$randno.'.'.$extension; 
}
else
{
 $new = $extension;   
}     
$folder = $this->template->domain_image_upload_path();
$folderpath = trim($folder.$new);
$path = addslashes($folderpath);

move_uploaded_file($_FILES['banner_image']['tmp_name'], $folderpath);            
$banner_image = $new;  
//debug($banner_image);exit();
$data = $this->input->post();
$img_desc = strip_tags($data['image_description']);
if($img_desc){
  
  $total_image =[];
  array_push($total_image, $banner_image);
  
  //exit();
//   foreach($image_name_db as $value){
//    array_push($total_image,$value);
// }
$total_desc = explode("#",$img_desc);
unset($total_desc[0]);
$total_desc = array_values($total_desc);
//debug($total_desc);exit();
$image_description = array_combine($total_image, $total_desc);
//debug($image_description);exit();
$image_description = json_encode($image_description);
}else{
$image_description="";  
}

$Gallery_list_check = explode(".", $Gallery_list);

if(end($Gallery_list_check)==''){
$Gallery_list="";
}
//debug($Gallery_list);exit();
$tour_id = sql_injection($data['tour_id']);
$adult_twin_sharing = sql_injection($data['adult_twin_sharing']);
$highlights = sql_injection($data['highlights']);
$inclusions = sql_injection($data['inclusions']);
$exclusions = sql_injection($data['exclusions']);
$terms = sql_injection($data['terms']);
$optional_tours = sql_injection($data['optional_tours']);
$canc_policy = sql_injection($data['canc_policy']);
$trip_notes = sql_injection($data['trip_notes']);  
$query  = "update tours set adult_twin_sharing='$adult_twin_sharing',
adult_tripple_sharing='0',
child_with_bed='$child_with_bed',
child_without_bed='$child_without_bed',
joining_directly='$joining_directly',
single_suppliment='$single_suppliment',
service_tax='$service_tax',
tcs='$tcs',
image_description='$image_description',
highlights='$highlights',
inclusions='$inclusions',
exclusions='$exclusions',
terms='$terms',
optional_tours= '$optional_tours',
canc_policy='$canc_policy',
trip_notes='$trip_notes',
status=1,
banner_image='$banner_image',
gallery='$Gallery_list'
where id='$tour_id'";
$return = $this->tours_model->query_run($query);
$tours_itinerary_data = array(
  'adult_twin_sharing'=>$adult_twin_sharing,
  'highlights'=>$highlights,
  'inclusions'=>$inclusions,
  'exclusions'=>$exclusions,
  'terms'=>$terms,
  'canc_policy'=>$canc_policy,
  );
$this->custom_db->update_record('tours_itinerary',$tours_itinerary_data , array('tour_id'=>$tour_id));
if($return)
{
 redirect('tours/price_management/'.$tour_id);
} 
else { echo $return;} 
}

public function activation_top_tour_destination($id,$status) {
  $query = "update tour_destinations set cms_status='$status' where id='$id'";
  $return = $this->tours_model->query_run($query);
  if($return){redirect('cms/top_tour_destinations');} 
  else { echo $return;} 
}
public function edit_top_tour_destination($id) { 
  $tour_destinations_details = $this->tours_model->tour_destinations_details($id);
    //debug($tour_destinations_details); exit;
  $page_data['tour_destinations_details'] = $tour_destinations_details; 
  $page_data['id'] = $id;
  $tour_destinations = $this->tours_model->tour_destinations();
  $page_data['tour_destinations'] = $tour_destinations;
    //$page_data['tour_id'] = $tour_id;
    //$page_data['tour_data'] = $this->tours_model->tour_data($tour_id);  
  $this->template->view('tours/edit_top_tour_destination',$page_data);
}
public function itinerary($tour_id) { 
 $page_data['tour_id']   = $tour_id;
 $tour_data = $this->tours_model->tour_data($tour_id);
 $page_data['tour_data'] = $tour_data;
    //debug($page_data); exit()
 $tour_dep_dates_list = $this->tours_model->tour_dep_dates_list($tour_id);
 $page_data['tour_dep_dates_list'] = $tour_dep_dates_list;
 $this->template->view('tours/itinerary',$page_data);
}
public function itinerary_dep_date($tour_id,$dep_date) { 
  $page_data['tour_id']   = $tour_id;
  $tour_data = $this->tours_model->tour_data($tour_id);
  $page_data['tour_data'] = $tour_data;
  $tour_dep_dates_list = $this->tours_model->tour_dep_dates_list($tour_id);
  $page_data['tour_dep_dates_list'] = $tour_dep_dates_list;

  $page_data['dep_date']  = $dep_date;
  $tour_data = $this->tours_model->tour_data($tour_id);
  $tour_visited_cities_list = $this->tours_model->tour_visited_cities_list($tour_id);
  $page_data['tour_visited_cities_list'] = $tour_visited_cities_list;
  $tour_visited_cities_all = $this->tours_model->tour_visited_cities_all();
  $page_data['tour_visited_cities_all'] = $tour_visited_cities_all;
  $page_data['tours_city_name'] = $this->tours_model->tours_city_name();

  $page_data['tours_city_name'] = $this->tours_model->tours_city_name();

  $tours_itinerary = $this->tours_model->tours_itinerary($tour_id,$dep_date);
  if(empty($tours_itinerary))
  {
   $page_data['itinerary_page'] = 'ajax_itinerary';
 }
 else if(!empty($tours_itinerary))
 {
   $page_data['tours_itinerary'] = $tours_itinerary;
   $page_data['tours_itinerary_dw'] = $this->tours_model->tours_itinerary_dw($dep_date,$tour_id);
   $page_data['itinerary_page']  = 'ajax_itinerary_stored';
 }  
  // debug($page_data); exit;
 $this->template->view('tours/itinerary',$page_data);
}
public function ajax_itinerary($dep_date,$tour_id) { 
 $page_data['tour_id']   = $tour_id;
 $page_data['dep_date']  = $dep_date;
 $tour_data = $this->tours_model->tour_data($tour_id);
 $page_data['tour_data'] = $tour_data;
 $tour_visited_cities_list = $this->tours_model->tour_visited_cities_list($tour_id);
 $page_data['tour_visited_cities_list'] = $tour_visited_cities_list;
 $tour_visited_cities_all = $this->tours_model->tour_visited_cities_all();
 $page_data['tour_visited_cities_all'] = $tour_visited_cities_all;
 $page_data['tours_city_name'] = $this->tours_model->tours_city_name();

 $tours_itinerary = $this->tours_model->tours_itinerary($tour_id,$dep_date);
 if(empty($tours_itinerary))
 {
   echo $this->template->isolated_view('tours/ajax_itinerary',$page_data);
 }
 else if(!empty($tours_itinerary))
 {
   $page_data['tours_itinerary']    = $tours_itinerary;
   $page_data['tours_itinerary_dw'] = $this->tours_model->tours_itinerary_dw($dep_date,$tour_id);
   echo $this->template->isolated_view('tours/ajax_itinerary_stored',$page_data);
 }    
}
public function itinerary_save() {
  $data = $this->input->post();
    //debug($data); //exit;

  $tour_id               = sql_injection($data['tour_id']);
  $dep_date              = sql_injection($data['dep_date']);
  $publish_status        = sql_injection($data['publish_status']);

  $reporting             = sql_injection($data['reporting']);
  $reporting_date        = $data['reporting_date'];
  $reporting_desc        = sql_injection($data['reporting_desc']);

  $tour_visited_city_id  = $data['tour_visited_city_id'];
  $no_of_nights          = $data['no_of_nights'];
  $visited_city          = $data['visited_city'];

  $program_title         = $data['program_title'];
  $program_des           = $data['program_des'];
  $hotel_name            = $data['hotel_name'];
  $rating                = $data['rating'];
  $accomodation          = $data['accomodation'];
  $tours_itinerary_dw_id = $data['tours_itinerary_dw_id'];

  $adult_twin_sharing    = sql_injection($data['adult_twin_sharing']);
  $adult_tripple_sharing = sql_injection($data['adult_tripple_sharing']);
    /*$pricing['child_with_bed']        = sql_injection($data['child_with_bed']);
    $pricing['child_without_bed']     = sql_injection($data['child_without_bed']);
    $pricing['joining_directly']      = sql_injection($data['joining_directly']);
    $pricing['single_suppliment']     = sql_injection($data['single_suppliment']);

    $service_tax           = sql_injection($data['service_tax']);
    $tcs                   = sql_injection($data['tcs']);*/

    $highlights            = sql_injection($data['highlights']);
    $inclusions            = sql_injection($data['inclusions']);
    $exclusions            = sql_injection($data['exclusions']);
    $terms                 = sql_injection($data['terms']);
    $canc_policy           = sql_injection($data['canc_policy']);
    $inclusions_checks     = $data['inclusions_checks'];
    $inclusions_checks     = json_encode($inclusions_checks,1);

    $day = 0;
    foreach($no_of_nights as $index => $record)
    {
     for($i=0;$i<$record;$i++)
     {
      $itinerary[$day]['tour_visited_city_id'] = sql_injection($tour_visited_city_id[$index]);
             //$itinerary[$index]['no_of_nights']     = $no_of_nights[$index];
      $itinerary[$day]['visited_city']         = html_entity_decode(sql_injection($visited_city[$index]));
      $day++;
    }
  }
  $itinerary[$day]['visited_city']                = html_entity_decode(sql_injection($visited_city[$index]));
        //debug($itinerary); exit;

  foreach($program_title as $index => $record)
  {
   $itinerary[$index]['program_title'] = sql_injection($program_title[$index]);
   $itinerary[$index]['program_des']   = sql_injection($program_des[$index]);
   $itinerary[$index]['hotel_name']    = sql_injection($hotel_name[$index]);
   $itinerary[$index]['rating']        = sql_injection($rating[$index]);
   $itinerary[$index]['accomodation']  = $accomodation[$index];
   $itinerary[$index]['tours_itinerary_dw_id']  = $tours_itinerary_dw_id[$index]; 
 }
        //debug($itinerary); exit;
        //$json_encode =  json_encode($itinerary,1); debug($json_encode);exit;
        //$json_decode =  json_decode($json_encode,1); debug($json_decode);exit;

    $tour_visited_city_id = json_encode($tour_visited_city_id,1); //debug($sightseeing);
    $no_of_nights         = json_encode($no_of_nights,1);
    //$itinerary            = json_encode($itinerary,1);  //debug($itinerary); //exit;

    $tours_itinerary = $this->tours_model->tours_itinerary($tour_id,$dep_date);
    if(empty($tours_itinerary))
    {
      $AUTO_INCREMENT = $this->tours_model->AUTO_INCREMENT('tours_itinerary');
      $tour_code      = 'AIRHP'.date('m').date('y').$AUTO_INCREMENT;

      $query  = "insert into tours_itinerary set tour_id='$tour_id',
      tour_code='$tour_code',
      dep_date='$dep_date',
      publish_status='$publish_status',
      tour_visited_city_id='$tour_visited_city_id',
      no_of_nights='$no_of_nights',
      adult_twin_sharing='$adult_twin_sharing',
      adult_tripple_sharing='$adult_tripple_sharing',
      reporting='$reporting',
      reporting_date='$reporting_date',
      reporting_desc='$reporting_desc',
      service_tax='$service_tax',
      tcs='$tcs',
      highlights='$highlights',
      inclusions='$inclusions',
      exclusions='$exclusions',
      terms='$terms',
      canc_policy='$canc_policy',
      inclusions_checks='$inclusions_checks',
      date=now()";

      foreach($itinerary as $index => $record)
      {
        $visited_city  = $record['visited_city'];
        $program_title = $record['program_title'];
        $program_des   = $record['program_des'];
        $hotel_name    = $record['hotel_name'];
        $rating        = $record['rating'];
        $accomodation  = json_encode($record['accomodation'],1);

        $query_tours_itinerary_dw  = "insert into tours_itinerary_dw set tour_id='$tour_id',
        tour_code='$tour_code',
        dep_date='$dep_date',
        visited_city='$visited_city',
        program_title='$program_title',
        program_des='$program_des',
        hotel_name='$hotel_name',
        rating='$rating',
        accomodation='$accomodation'";
         //echo '<pre>'.$query_tours_itinerary_dw;
        $this->tours_model->query_run($query_tours_itinerary_dw); 
      }
    }
    else if(!empty($tours_itinerary))
    {
     $id     = $data['id'];
     $query  = "update tours_itinerary set tour_id='$tour_id',
     dep_date='$dep_date',
     publish_status='$publish_status',
     tour_visited_city_id='$tour_visited_city_id',
     no_of_nights='$no_of_nights',
     adult_twin_sharing='$adult_twin_sharing',
     adult_tripple_sharing='$adult_tripple_sharing',
     reporting='$reporting',
     reporting_date='$reporting_date',
     reporting_desc='$reporting_desc',
     service_tax='$service_tax',
     tcs='$tcs',
     highlights='$highlights',
     inclusions='$inclusions',
     exclusions='$exclusions',
     terms='$terms',
     canc_policy='$canc_policy',
     inclusions_checks='$inclusions_checks'
     where id='$id'";

     foreach($itinerary as $index => $record)
     {
      $tours_itinerary_dw_id = $record['tours_itinerary_dw_id'];
      $visited_city  = $record['visited_city'];
      $program_title =  $record['program_title'];
      $program_des   = $record['program_des'];
      $hotel_name    = $record['hotel_name'];
      $rating        = $record['rating'];
      $accomodation  = json_encode($record['accomodation'],1);

      $query_tours_itinerary_dw  = "update tours_itinerary_dw set visited_city='$visited_city',
      program_title='$program_title',
      program_des='$program_des',
      hotel_name='$hotel_name',
      rating='$rating',
      accomodation='$accomodation'
      where id='$tours_itinerary_dw_id'";
         //echo '<pre>'.$query_tours_itinerary_dw;
      $this->tours_model->query_run($query_tours_itinerary_dw); 
    }
  }     
        //echo $query; exit;
  $return = $this->tours_model->query_run($query);
  if($return)
  {
       //redirect('tours/itinerary/'.$tour_id);
   redirect('tours/itinerary_dep_date/'.$tour_id.'/'.$dep_date);
 } 
 else { echo $return;}  
}
public function ajax_tour_publish() {
  $data = $this->input->post();
  // debug($data); exit('');
  $tour_id        = sql_injection($data['tour_id']);
  $dep_date       = sql_injection($data['dep_date']);
  $publish_status = sql_injection($data['publish_status']);
  $query_1  = "select * from tours where id=  ".$tour_id." and (banner_image != '' AND gallery != '')";        
  $num_ajax_tour_publish_1 = $this->tours_model->ajax_tour_publish_1($query_1);
  $query3 = "select * from tour_price_management where tour_id=".$tour_id; 
  $num_ajax_tour_publish_3 = $this->tours_model->ajax_tour_publish_1($query3);
  $message = array();
  if($num_ajax_tour_publish_1 == 0)
  { 
   $message['first'][]= "Sorry! Please upload images";
 }
 if($num_ajax_tour_publish_3 == 0)
 {  
  $message['first'][]= "Unable to publish the Tours as the price info is missing. Please add the price information for the Tours using Price Management Option";
 }
 if($num_ajax_tour_publish_1 !=0 && $num_ajax_tour_publish_3!=0 && $publish_status == 1)
 {
   $query  = "update tours_itinerary set publish_status='$publish_status' where tour_id='$tour_id'";
   $return = $this->tours_model->query_run($query);
   if($return)
   {
    $message['sec'][]= "Thanks! This tour is successfully published now.";
  }else{
    $message['sec'][]= "Sorry|| some techinal .";
  }
}
if($publish_status != 1){
 $query  = "update tours_itinerary set publish_status='$publish_status' where tour_id='$tour_id'";
 $return = $this->tours_model->query_run($query);
 if($return)
 {
  $message['sec'][]= "Thanks! This tour is successfully unpublished now.";
}else{
  $message['sec'][]= "Sorry|| some techinal .";
}
}
echo json_encode($message); exit(); 
}
public function tour_itinerary_p2($tour_id) { 
 $page_data['tour_id']   = $tour_id;
 $page_data['tour_data'] = $this->tours_model->tour_data($tour_id)[0];
 $page_data['tour_visited_cities'] = $this->tours_model->tour_visited_cities($tour_id);
 $page_data['tours_city_name'] = $this->tours_model->tours_city_name();
 $page_data['tours_itinerary_dw'] = $this->custom_db->single_table_records('tours_itinerary_dw','*',array('tour_id'=>$tour_id));
 $page_data['tours_itinerary_dw'] = ($page_data['tours_itinerary_dw']['status'])? $page_data['tours_itinerary_dw']['data']: NULL;

   // debug(json_decode($page_data['tour_visited_cities'][0]['itinerary'],1));
  //  debug($page_data);exit;
 $this->template->view('tours/tour_itinerary_p2',$page_data);
}
public function tour_itinerary_p2_save() {
  $data = $this->input->post();
  // debug($data);exit();
  $tour_id = $data['tour_id'];
  $tours=$this->custom_db->get_result_by_query('SELECT * FROM tours WHERE  id='.$tour_id);
  // debug($tours);exit();
  $tours=json_decode(json_encode($tours),true);
  $id = $data['id'];
  $tour_visited_city_id_arr=array();
  $no_of_nights_arr = array();
  $this->custom_db->delete_record('tours_itinerary_dw',array('tour_id'=>$tour_id));
  foreach($id as $index => $record)
  {
    $program_title = $data['program_title'][$record];
    $program_des = $data['program_des'][$record];
    $hotel_name = $data['hotel_name'][$record];
    $rating = $data['rating'][$record];
    $accomodation = $data['accomodation'][$record];
    $tour_visited_cities=$this->custom_db->get_result_by_query('SELECT * FROM tour_visited_cities WHERE  id='.$record);
    $tour_visited_cities=json_decode(json_encode($tour_visited_cities),true);   
    $itinerary = array();
     // for($i=0;$i<count($program_title);$i++)
    /*Bishnu*/
    foreach ($program_title as $key => $program_title_val) 
    {
      $itinerary[$key]['program_title'] = $program_title_val;
      $itinerary[$key]['program_des']   = $program_des[$key];
      $itinerary[$key]['hotel_name']    = $hotel_name[$key];
      $itinerary[$key]['rating']        = $rating[$key];
      $itinerary[$key]['accomodation']  = $accomodation[$key];
      
      ##################################
      /*$visited_city  = $tour_visited_cities[0]['city'];
      $program_title = $itinerary[$key]['program_title'];
      $program_des   = $itinerary[$key]['program_des'];
      $hotel_name    = $itinerary[$key]['hotel_name'];
      $rating        = $itinerary[$key]['rating'];
      $accomodation  = json_encode($itinerary[$key]['accomodation'],1);*/
      $tours_itinerary_dw_data  =array( 
       'tour_id'=>$tour_id,
       'tour_code'=>$tours[0]['package_id'],
       'visited_city'=>$tour_visited_cities[0]['city'],
       'program_title'=>$itinerary[$key]['program_title'],
       'program_des'=>$itinerary[$key]['program_des'],
       'hotel_name'=>$itinerary[$key]['hotel_name'],
       'rating'=>$itinerary[$key]['rating'],
       'accomodation'=>json_encode($itinerary[$key]['accomodation'],1));
      $this->custom_db->insert_record('tours_itinerary_dw',$tours_itinerary_dw_data); 
      ################################################
    }
    $itinerary = json_encode($itinerary,1);
    $this->custom_db->update_record('tour_visited_cities',array('itinerary'=>$itinerary),array('id'=>$record));
    $tour_visited_city_id_arr[]=$record;
    $no_of_nights_arr[]  = $tour_visited_cities[0]['no_of_nights'];
  }
  $inclusions = $data['inclusions'];
  $inclusions = json_encode($inclusions,1);
  $query  = "update tours set inclusions_checks='$inclusions' where id='$tour_id'";
  $return = $this->tours_model->query_run($query);    
  ############################################################
  $tour_visited_city_id = json_encode($tour_visited_city_id_arr,1);
  $no_of_nights = json_encode($no_of_nights_arr,1);
  $AUTO_INCREMENT = $this->tours_model->AUTO_INCREMENT('tours_itinerary');
  $tours_itinerary_data = array(
   'tour_id'=>$tour_id,
   'tour_code'=>$tours[0]['package_id'],
   'tour_visited_city_id'=>$tour_visited_city_id,
   'no_of_nights'=>$no_of_nights,
   'adult_twin_sharing'=>$tours[0]['adult_twin_sharing'],
   'adult_tripple_sharing'=>$tours[0]['adult_tripple_sharing'],
   'highlights'=>$tours[0]['highlights'],
   'inclusions'=>$tours[0]['inclusions'],
   'exclusions'=>$tours[0]['exclusions'],
   'terms'=>$tours[0]['terms'],
   'canc_policy'=>$tours[0]['canc_policy'],
   'inclusions_checks'=>$inclusions,
   'date'=>$tours[0]['date'],
   );

  $check_tours_itinerary = $this->custom_db->single_table_records('tours_itinerary','count(*) total',array('tour_id'=>$tour_id));
  if($check_tours_itinerary['data'][0]['total']!=0){
    $this->custom_db->update_record('tours_itinerary',$tours_itinerary_data,array('tour_id'=>$tour_id));
  }else{
    $this->custom_db->insert_record('tours_itinerary',$tours_itinerary_data);
  }
      ##############################################################
  if($this->session->userdata('edit_itinary')){
    $this->session->unset_userdata('edit_itinary');
     //set_update_message();
     redirect('tours/tour_pricing_p2/'.$tour_id);
     // redirect('tours/tour_itinerary_p2/'.$tour_id);
  }else{
     //set_update_message();
    redirect('tours/tour_pricing_p2/'.$tour_id);  
    // redirect('tours/tour_itinerary_p2/'.$tour_id);  
  }
}

public function tour_destinations_banner() { 
 $tour_destinations = $this->tours_model->tour_destinations();
    //debug($tour_destinations); exit;
 $page_data['tour_destinations'] = $tour_destinations;
 debug($page_data);exit;
 $this->template->view('tours/tour_destinations_banner',$page_data);
}

public function tour_destinations_banner_save() {
  $data = $this->input->post();
    //debug($data); //exit;
  $id           = sql_injection($data['id']);
  $banner_image = sql_injection($data['radio'.$id]);

    $query  = "update tour_destinations set banner_image='$banner_image' where id='$id'"; //echo $query; exit;       
    $return = $this->tours_model->query_run($query);
    if($return)
    {
     redirect('tours/tour_destinations_banner');
   } 
   else { echo $return;}  
 }
 public function tour_date_list() { 

   $tour_date_list = $this->tours_model->tour_date_list();
   $page_data['tour_date_list'] = $tour_date_list;  
   $tour_list = $this->tours_model->tour_list();
   $page_data['tour_list'] = $tour_list;
   $tour_destinations = $this->tours_model->tour_destinations();
   $page_data['tour_destinations'] = $tour_destinations;
    //debug($page_data); exit;
   $this->template->view('tours/tour_date_list',$page_data);
 }
 public function publish_tours_itinerary($id,$status) {
  $query = "update tours_itinerary set publish_status='$status' where id='$id'";
  $return = $this->tours_model->activation_tour_package($query);
  if($return){redirect('tours/tour_date_list');} 
  else { echo $return;} 
}
public function delete_tours_itinerary($id) {
  $query = "delete from tours_itinerary where id='$id'";
  $return = $this->tours_model->query_run($query);
  if($return){redirect('tours/tour_date_list');} 
  else { echo $return;} 
}
public function seats_tours_itinerary($id,$tour_id,$dep_date) { 
  $page_data['id'] = $id;  
  $tours_itinerary = $this->tours_model->tours_itinerary($tour_id,$dep_date);
  $page_data['tours_itinerary'] = $tours_itinerary;  
    //debug($page_data); exit;
  $this->template->view('tours/seats_tours_itinerary',$page_data);
}
public function seats_tours_itinerary_save() {
  $data = $this->input->post();
    //debug($data); exit;
  $id              = sql_injection($data['id']);
  $tour_id         = sql_injection($data['tour_id']);
  $dep_date        = sql_injection($data['dep_date']);
  $no_of_seats     = sql_injection($data['no_of_seats']);
  $total_booked    = sql_injection($data['total_booked']);
  $available_seats = sql_injection($data['available_seats']);
  $booking_hold    = sql_injection($data['booking_hold']);

  $query  = "update tours_itinerary set no_of_seats='$no_of_seats',
  total_booked='$total_booked',
  available_seats='$available_seats',
  booking_hold='$booking_hold' where id='$id'"; 
    //echo $query; exit;       
  $return = $this->tours_model->query_run($query);
  if($return)
  {
   redirect('tours/seats_tours_itinerary/'.$id.'/'.$tour_id.'/'.$dep_date);
 } 
 else { echo $return;}  
}
public function tours_enquiry() {
        // if (!check_user_previlege('p250')) {
        //     set_update_message("You Don't have permission to do this action.", WARNING_MESSAGE, array(
        //         'override_app_msg' => true
        //     ));
        //     redirect(base_url());
        // }
        $get_data = $this->input->get();

        if($get_data)
        {
            $package_name = $get_data['package_name'];
            $get_package_id = $this->tours_model->get_package_id($package_name);
            $package_id = $get_package_id[0][0];
        }
        $page_data = array();
        $condition = array(
            'tour_id' => trim($this->input->get('phone')),
            'phone' => trim($this->input->get('phone')),
            'email' => trim($this->input->get('email'))
        );
        $total_records = $this->tours_model->tours_enquiry($condition);
        // debug($total_records);exit;
        $tours_enquiry = $this->tours_model->tours_enquiry($condition);

        $page_data['tours_enquiry'] = $tours_enquiry['tours_enquiry'];
        $page_data['tour_list']          = $this->tours_model->tour_list();
        $page_data['tours_itinerary']    = $this->tours_model->tours_itinerary_all();
        $page_data['tours_country_name'] = $this->tours_model->tours_country_name();
        $this->template->view('tours/tours_enquiry',$page_data);
        // $array = array(
        //   'back_link' => base_url().$this->router->fetch_class().'/'.$this->router->fetch_method()
        //   );
//        $this->session->set_userdata( $page_data );

                              
    }
public function activation_enquiry($id,$status) {
  $query = "update tours_enquiry set status='$status' where id='$id'";
  $return = $this->tours_model->query_run($query);
  if($return){redirect('tours/tours_enquiry');} 
  else { echo $return;} 
}
public function delete_enquiry($id) {
  $query = "delete from tours_enquiry where id='$id'";
  $return = $this->tours_model->query_run($query);
  if($return){redirect('tours/tours_enquiry');} 
  else { echo $return;} 
}

public function tour_type() {   
 $tour_type = $this->tours_model->tour_type();
    //debug($tour_type); exit;    
 $page_data['tour_type'] = $tour_type;
 $this->template->view('tours/tour_type',$page_data);
}
public function tour_type_save() {
  $data = $this->input->post();
    // debug($data); exit;
  $tour_type_name   = sql_injection($data['tour_type_name']);
  $query = "insert into tour_type set tour_type_name='$tour_type_name', status=1 ";        
        //echo $query; //exit;


  $this->db->where("tour_type_name",$tour_type_name);
    $qur = $this->db->get ("tour_type");
    $count=$qur->num_rows();

    if($count==0){ 

      $return = $this->tours_model->query_run($query);
      redirect('tours/tour_type/');
    }else{
      
      $this->session->set_flashdata('error_message', 'Duplicate data!!');
      redirect('tours/tour_type/');
    }

 /* $return = $this->tours_model->query_run($query);
  if($return)
    {   redirect('tours/tour_type/'); }
  else
    { echo $return; exit; }  */            
}
public function edit_tour_type($id) {
  $tour_type_details = $this->tours_model->tour_type_details($id);
    //debug($tour_type_details); //exit;      
  $page_data['tour_type_details'] = $tour_type_details;
    //debug($page_data); exit;
  $this->template->view('tours/edit_tour_type',$page_data);
}
public function edit_tour_type_save() {
  $data = $this->input->post();
    //debug($data); exit;
  $id             = $data['id'];
  $tour_type_name = sql_injection($data['tour_type_name']);
  $query = "update tour_type set tour_type_name='$tour_type_name' where id='$id'";        
        //echo $query; //exit;
  $return = $this->tours_model->query_run($query);
  if($return)
    {   redirect('tours/tour_type/'); }
  else
    { echo $return; exit; }              
}
public function tour_inclusions() {   
 $tour_inclusions = $this->tours_model->tour_inclusions();
 $page_data['tour_inclusions'] = $tour_inclusions;
 $this->template->view('tours/tour_inclusions',$page_data);
}
public function tour_inclusions_save() {
      //echo '<pre>'; print_r($_FILES); exit;   

 $banner_image = $_FILES['inclusion_image']['name'];
 $filename     = basename($banner_image);
 $extension    = pathinfo($filename, PATHINFO_EXTENSION);
 $uniqueno     = substr(uniqid(),0,5);
 $randno       = substr(rand(),0,5);
 $new          = $uniqueno.$randno.'.'.$extension;
 $folder       = $this->template->domain_image_upload_path();
 $folderpath   = trim($folder.$new);
 $path         = addslashes($folderpath);
 move_uploaded_file($_FILES['inclusion_image']['tmp_name'], $folderpath);              
 $inclusion_image = $new;         

 $data = $this->input->post();
    //debug($data);exit;
 $inclusion    = sql_injection($data['inclusion']);
 $query       = "insert into tour_inclusions set inclusion='$inclusion',
 status=1,
 inclusion_image='$inclusion_image'";
        //echo $query; exit;
 $return      = $this->tours_model->query_run($query);
 if(!$return)
 {
  echo $return; 
} 
redirect('tours/tour_inclusions');  
}
public function activation_tour_inclusion($id,$status) {
  $return = $this->tours_model->record_activation('tour_inclusions',$id,$status);
  if($return){redirect('tours/tour_inclusions');} 
  else { echo $return;} 
}
public function delete_tour_inclusion($id) {
  $return = $this->tours_model->record_delete('tour_inclusions',$id);
  if($return){redirect('tours/tour_inclusions');} 
  else { echo $return;} 
}
public function activation_tour_type($id,$status) {
  $return = $this->tours_model->record_activation('tour_type',$id,$status);
  if($return){redirect('tours/tour_type');} 
  else { echo $return;} 
}
public function delete_tour_type($id) {
  $return = $this->tours_model->record_delete('tour_type',$id);
  if($return){redirect('tours/tour_type');} 
  else { echo $return;} 
}
public function edit_tour_inclusion($id) {
  $tour_inclusions_details = $this->tours_model->table_record_details('tour_inclusions',$id);
    //debug($tour_inclusions_details); //exit;      
  $page_data['tour_inclusions_details'] = $tour_inclusions_details;
    //debug($page_data); exit;
  $this->template->view('tours/edit_tour_inclusion',$page_data);
}
public function edit_tour_inclusion_save() {
  $data = $this->input->post();
    //debug($data);exit;
  $id          = sql_injection($data['id']);
  $inclusion   = sql_injection($data['inclusion']);

  if(!empty($_FILES['inclusion_image']['name']))
  {
   $banner_image = $_FILES['inclusion_image']['name'];
   $filename     = basename($banner_image);
   $extension    = pathinfo($filename, PATHINFO_EXTENSION);
   $uniqueno     = substr(uniqid(),0,5);
   $randno       = substr(rand(),0,5);
   $new          = $uniqueno.$randno.'.'.$extension;
   $folder       = $this->template->domain_image_upload_path();
   $folderpath   = trim($folder.$new);
   $path         = addslashes($folderpath);
   move_uploaded_file($_FILES['inclusion_image']['tmp_name'], $folderpath);              
   $inclusion_image = $new; 
   $inclusion_image_update = ",inclusion_image='$inclusion_image'"; 
 }

 $query = "update tour_inclusions set inclusion='$inclusion' ".$inclusion_image_update." where id='$id'";
        //echo $query; exit;
 $return = $this->tours_model->query_run($query);
 if($return)
 {
  redirect('tours/edit_tour_inclusion/'.$id); 
} 
else
{
 echo $return;
} 
}
  /*public function tour_country() {  
      $tour_country = $this->tours_model->table_records('tour_country','country_name','asc');
    $page_data['tour_country'] = $tour_country;
    $this->template->view('tours/tour_country',$page_data);
  }
  public function tour_country_save() {
    $data = $this->input->post();
    //debug($data); exit;
    $country_name   = sql_injection($data['country_name']);
        $query = "insert into tour_country set country_name='$country_name', status=1 ";        
        //echo $query; exit;
    $return = $this->tours_model->query_run($query);
    if($return)
    { redirect('tours/tour_country/'); }
        else
        { echo $return; exit; }              
      }*/
      public function tour_subtheme() {   
       $tour_subtheme = $this->tours_model->tour_subtheme();
    //debug($tour_subtheme); exit;    
       $page_data['tour_subtheme'] = $tour_subtheme;
       $this->template->view('tours/tour_subtheme',$page_data);
     }
     public function tour_subtheme_save() {
      $data = $this->input->post();
    //debug($data); exit;
      $tour_subtheme   = sql_injection($data['tour_subtheme']);
      $query = "insert into tour_subtheme set tour_subtheme='$tour_subtheme', status=1 ";        
        //echo $query; //exit;

    $this->db->where("tour_subtheme",$tour_subtheme);
    $qur = $this->db->get ("tour_subtheme");
    $count=$qur->num_rows();
    //echo $count;die;
    if($count==0){ 
      $return = $this->tours_model->query_run($query);
       redirect('tours/tour_subtheme/');
       die;
    } else{
      $this->session->set_flashdata('error_message', 'Duplicate data!!');
      redirect('tours/tour_subtheme/');
      die;
    }

      /*$return = $this->tours_model->query_run($query);
      if($return)
        {   redirect('tours/tour_subtheme/'); }
      else
        { echo $return; exit; }*/              
    }
    public function activation_tour_subtheme($id,$status) {
      $return = $this->tours_model->record_activation('tour_subtheme',$id,$status);
      if($return){redirect('tours/tour_subtheme');} 
      else { echo $return;} 
    }
    public function delete_tour_subtheme($id) {
      $return = $this->tours_model->record_delete('tour_subtheme',$id);
      if($return){redirect('tours/tour_subtheme');} 
      else { echo $return;} 
    }
    public function edit_tour_subtheme($id) {
      $tour_subtheme_details = $this->tours_model->table_record_details('tour_subtheme',$id);
    //debug($tour_subtheme_details); //exit;      
      $page_data['tour_subtheme_details'] = $tour_subtheme_details;
    //debug($page_data); exit;
      $this->template->view('tours/edit_tour_subtheme',$page_data);
    }
    public function edit_tour_subtheme_save() {
      $data = $this->input->post();
    //debug($data); exit;
      $id             = $data['id'];
      $tour_subtheme  = sql_injection($data['tour_subtheme']);
      $query = "update tour_subtheme set tour_subtheme='$tour_subtheme' where id='$id'";        
        //echo $query; //exit;
      $return = $this->tours_model->query_run($query);
      if($return)
        {   redirect('tours/edit_tour_subtheme/'.$id); }
      else
        { echo $return; exit; }              
    }


    public function tour_activity() {   
     $tour_activity = $this->tours_model->tour_activity();
    //debug($tour_activity); exit;    
     $page_data['tour_activity'] = $tour_activity;
     $this->template->view('tours/tour_activity',$page_data);
   }
   public function tour_activity_save() {
    $data = $this->input->post();
    //debug($data); exit;
    $tour_activity   = sql_injection($data['tour_activity']);
    $query = "insert into tour_activity set tour_activity='$tour_activity', status=1 ";        
        //echo $query; //exit;
    $return = $this->tours_model->query_run($query);
    if($return)
      {   redirect('tours/tour_activity/'); }
    else
      { echo $return; exit; }              
  }
  public function activation_tour_activity($id,$status) {
    $return = $this->tours_model->record_activation('tour_activity',$id,$status);
    if($return){redirect('tours/tour_activity');} 
    else { echo $return;} 
  }
  public function delete_tour_activity($id) {
    $return = $this->tours_model->record_delete('tour_activity',$id);
    if($return){redirect('tours/tour_activity');} 
    else { echo $return;} 
  }
  public function edit_tour_activity($id) {
    $tour_activity_details = $this->tours_model->table_record_details('tour_activity',$id);
    //debug($tour_activity_details); //exit;      
    $page_data['tour_activity_details'] = $tour_activity_details;
    //debug($page_data); exit;
    $this->template->view('tours/edit_tour_activity',$page_data);
  }
  public function edit_tour_activity_save() {
    $data = $this->input->post();
    //debug($data); exit;
    $id             = $data['id'];
    $tour_activity  = sql_injection($data['tour_activity']);
    $query = "update tour_activity set tour_activity='$tour_activity' where id='$id'";        
        //echo $query; //exit;
    $return = $this->tours_model->query_run($query);
    if($return)
      {   redirect('tours/edit_tour_activity/'.$id); }
    else
      { echo $return; exit; }              
  }
  public function ajax_tours_continent() {
    $data = $this->input->post();

    $tours_continent = $data['tours_continent'];      
        // debug($tours_continent); exit; 
    $tours_continent = $this->tours_model->ajax_tours_continent($tours_continent);          
    foreach($tours_continent as $key => $value)
    {
      $options .=  '<option value="'.$value['id'].'">'.$value['name'].'</option>';
    } 
    echo $options;      
  }
  public function ajax_tours_country() {
    $data = $this->input->post();
    //debug($data); exit;
    $tours_country = $data['tours_country'];      
    $tours_country = $this->tours_model->ajax_tours_country($tours_country);          
        //debug($tours_country); exit; 
    foreach($tours_country as $key => $value)
    {
      $options .=  '<option value="'.$value['id'].'">'.$value['CityName'].'</option>';
    } 
    echo $options;           
  }
  public function reviews() {
    $page_data['reviews']            = $this->tours_model->reviews();
    $page_data['tour_list']          = $this->tours_model->tour_list();
    $page_data['tours_itinerary']    = $this->tours_model->tours_itinerary_all();
    $page_data['tours_country_name'] = $this->tours_model->tours_country_name();
  //  debug($page_data); exit;
    $this->template->view('tours/reviews',$page_data);          
  }
  public function activation_review($id,$status) {
    $query = "update user_review set status='$status' where origin='$id'";
    $return = $this->tours_model->query_run($query);
    if($return){redirect('tours/reviews');} 
    else { echo $return;} 
  }
  public function delete_review($id) {
    $query = "delete from user_review where origin='$id'";
    $return = $this->tours_model->query_run($query);
    if($return){redirect('tours/reviews');} 
    else { echo $return;} 
  }
  public function hotel_reviews() {
    $page_data['hotel_reviews']            = $this->tours_model->hotel_reviews();
    //debug($page_data); exit;
    $this->template->view('tours/hotel_reviews',$page_data);          
  }
  public function activation_hotel_review($id,$status) {
    $query = "update user_review set status='$status' where origin='$id'";
    $return = $this->tours_model->query_run($query);
    if($return){redirect('tours/hotel_reviews');} 
    else { echo $return;} 
  }
  public function delete_hotel_review($id) {
    $query = "delete from user_review where origin='$id'";
    $return = $this->tours_model->query_run($query);
    if($return){redirect('tours/hotel_reviews');} 
    else { echo $return;} 
  }
  public function perfect_holidays($user_type_idp) {    
   $tour_list = $this->tours_model->tour_list();
   $page_data['tour_list'] = $tour_list;

    $tour_destinations = $this->tours_model->get_tour_destinations(); //debug($tour_destinations);exit;
    $page_data['tour_destinations'] = $tour_destinations; 
    $tour_dep_dates_list_all = $this->tours_model->tour_dep_dates_list_all(); //debug($tour_dep_dates_list_all);exit;
    $page_data['tour_dep_dates_list_all'] = $tour_dep_dates_list_all; 
    $tour_dep_dates_list_published = $this->tours_model->tour_dep_dates_list_published(); //debug($tour_dep_dates_list_all);exit;
    $page_data['tour_dep_dates_list_published'] = $tour_dep_dates_list_published; 

    $page_data['tours_city_name'] = $this->tours_model->tours_city_name();
    $page_data['tours_country_name'] = $this->tours_model->tours_country_name();
        //debug($page_data); exit;
    $this->template->view('tours/perfect_holidays',$page_data);
  }
  public function publish_perfect_holidays($id,$status) {
    $query = "update tours set perfect_holidays='$status' where id='$id'";
    $return = $this->tours_model->query_run($query);
    if($return){redirect('tours/perfect_holidays');} 
    else { echo $return;} 
  }

  public function tour_region() {   
   $tour_region = $this->tours_model->tour_region();
  //  debug($tour_region); exit;    
   $page_data['tour_region'] = $tour_region;
   $this->template->view('tours/tour_region',$page_data);
 }

 public function tour_region_save() {
  $data = $this->input->post();
  $tour_region   = sql_injection($data['tour_region']);
  $check_availibility = $this->tours_model->check_region_exist($tour_region);
  if(!$check_availibility)
  {
    $query = "insert into tours_continent set name='$tour_region', status=1 ";        
        //echo $query; //exit;
    $return = $this->tours_model->query_run($query);
    if($return)
      {   redirect('tours/tour_region/'); }
    else
      { echo $return; exit; } 
  }
  else
  {
   $this->session->set_flashdata('region_msg','Region is already exist');
   redirect('tours/tour_region');
 }

}

public function delete_tour_region($id) {
  $return = $this->tours_model->record_delete('tours_continent',$id);

  if($return){redirect('tours/tour_region');} 
  else { echo $return;} 
}
public function tour_city() {  
  $tour_country = $this->tours_model->tour_country();
  $page_data['tour_country'] = $tour_country;
  if ($this->input->post()) {
   $post_data = $this->input->post();
   $post_data['CountryName'] = $this->custom_db->single_table_records('tours_country','name',array('id'=>$post_data['country_id']));
   $post_data['CountryName'] = $post_data['CountryName']['data'][0]['name'];
   $city_arr=explode(',',$post_data['CityName']);
   $city_arr=array_map('trim', $city_arr);
   foreach ($city_arr as $city) {
    $tours_city_data=array(
     'country_id'=>$post_data['country_id'],
     'CountryName'=>$post_data['CountryName'],
     'CityName'=>$city
     );
    $this->custom_db->insert_record('tours_city',$tours_city_data);
  }
  set_insert_message();
  refresh ();
}
$tour_city = $this->custom_db->single_table_records('tours_city','*',array(),0, 100000000,array('CityName'=>'ASC'));
$page_data['tour_city'] = $tour_city['data'];
//debug($page_data['tour_city']);exit();
$this->template->view('tours/tour_city',$page_data);
}
public function edit_tour_city($id) {  
  if ($this->input->post()) {
   $post_data = $this->input->post();
   $this->custom_db->update_record('tours_city',$post_data,array('id'=>$id));
   //set_update_message();
   //refresh ();
 }
 $data = $this->custom_db->single_table_records('tours_city','*',array('id'=>$id));
 $page_data['data'] = $data['data'][0];
 $page_data['id'] = $id;
 $this->template->view('tours/edit_tour_city',$page_data);
}
public function delete_tour_city($id) {
  $return = $this->custom_db->delete_record('tours_city',array('id'=>$id));
  //set_update_message('UL0100');
  redirect('tours/tour_city');
}
public function activation_tour_region($id,$status) {
  $return = $this->tours_model->record_activation('tours_continent',$id,$status);
  if($return){redirect('tours/tour_region');} 
  else { echo $return;} 
}

public function edit_tour_region($id) {
  $tour_region_details = $this->tours_model->table_record_details('tours_continent',$id);
  //  debug($tour_region_details); //exit;      
  $page_data['tour_region_details'] = $tour_region_details;
    //debug($page_data); exit;
  $this->template->view('tours/edit_tour_region',$page_data);
}
public function edit_tour_region_save() {
  $data = $this->input->post();
    //debug($data); exit;
  $id             = $data['id'];
  $tour_region  = sql_injection($data['tour_region']);
  $query = "update tours_continent set name='$tour_region' where id='$id'";        
        //echo $query; //exit;
  $return = $this->tours_model->query_run($query);
  if($return)
    {   redirect('tours/edit_tour_region/'.$id); }
  else
    { echo $return; exit; }              
}

public function tour_country() {  
 $tour_country = $this->tours_model->tour_country();
     //debug($tour_country); exit;
 $tour_region = $this->tours_model->tour_region();
  //  debug($tour_region); exit;
 $page_data['tour_region'] = $tour_region;
 $page_data['tour_country'] = $tour_country;
 $this->template->view('tours/tour_country',$page_data);
}

public function tour_country_save() {
  $data = $this->input->post();
  $tour_country   = sql_injection($data['tour_country']);
  $tours_continent = sql_injection($data['continent']);
      //debug($tours_continent); exit;
       // $check_availibility = $this->tours_model->check_region_exist($tour_country);
        //debug($check_availibility); exit();
       // if(!$check_availibility)
       // {
  $this->db->where("name",$tour_country);
    $this->db->where("continent",$tours_continent);
    $qur = $this->db->get ("tours_country");
    $count=$qur->num_rows();
    //echo $count;die;
    if($count==0){ 
       $query = "insert into tours_country set name='$tour_country', status=1 , continent = '$tours_continent'";        
        //echo $query; //exit;
        $return = $this->tours_model->query_run($query);
       redirect('tours/tour_country/');
       die;
    } else{
      $this->session->set_flashdata('error_message', 'Duplicate data!!');
      redirect('tours/tour_country/');
      die;
    }     
        //echo $query; //exit;
 /* $return = $this->tours_model->query_run($query);
  if($return)
    {   redirect('tours/tour_country/'); }
  else
    { echo $return; exit; } */
      //  }
      /*  else
        {
          $this->session->set_flashdata('region_msg','Region is already exist');
          redirect('tours/tour_country');
        }*/

       }

       public function approve_package($p_id)
       {
        $approve_status = $this->tours_model->approve_package($p_id);
        redirect('tours/tour_list_pending');

      }

      public function holiday_terms_n_condition()
      {
        error_reporting(0);
    //echo "hiii";exit();
        $page_data['tours_data'] =  $this->tours_model->check_exist_tc();
        $this->template->view('tours/tours_terms_n_conditions',$page_data);
      }

      public function save_terms_n_conditions()
      {
        error_reporting(0);
        $t_c = $this->input->post('terms_n_conditions');
        $insert_data['terms_n_conditions'] =  $this->input->post('terms_n_conditions');
        $insert_data['cancellation_policy'] = $this->input->post('cancellation_policy');
    //debug($_POST); exit;
        $check_exist = $this->tours_model->check_exist_tc();
        if($check_exist)
        {

         $this->tours_model->update_tc($insert_data);
         redirect('tours/holiday_terms_n_condition');
       }
       $this->db->insert('holiday_terms_n_condition',$insert_data);
       redirect('tours/holiday_terms_n_condition');
    //debug($_POST); exit();
     }

     public function holiday_cancellation_policy()
     {
      error_reporting(0); 
    //echo "hiii";exit();
    //$page_data['tours_data'] =  $this->tours_model->check_exist_tc();
      $this->template->view('tours/tours_cancellation');
    }

    public function save_cancellation_policy()
    {
      error_reporting(0);
      $t_c = $this->input->post('terms_n_conditions');
      $insert_data['terms_n_conditions'] =  $this->input->post('terms_n_conditions');
    //debug($_POST); exit;
      $check_exist = $this->tours_model->check_exist_tc();
      if($check_exist)
      {

       $this->tours_model->update_tc($t_c);
       redirect('tours/holiday_terms_n_condition');
     }
     $this->db->insert('holiday_terms_n_condition',$insert_data);
     redirect('tours/holiday_terms_n_condition');
    //debug($_POST); exit();
   }

   public function activation_country($id,$status) {
    $return = $this->tours_model->record_activation('tours_country',$id,$status);
    if($return){redirect('tours/tour_country');} 
    else { echo $return;} 
  }

  public function edit_tour_country($id) {
    $tour_country_details = $this->tours_model->table_record_details('tours_country',$id);
  //  debug($tour_region_details); //exit;      
    $page_data['tour_country_details'] = $tour_country_details;
    //debug($page_data); exit;
    $this->template->view('tours/edit_tour_country',$page_data);
  }
  public function edit_tour_country_save() {
    $data = $this->input->post();
    //debug($data); exit;
    $id             = $data['id'];
    $tour_country  = sql_injection($data['tour_country']);
    $query = "update tours_country set name='$tour_country' where id='$id'";        

    $return = $this->tours_model->query_run($query);
    if($return)
      {   redirect('tours/tour_country/'); }
    else
      { echo $return; exit; }              
  }



  public function occupancy_managment()
  {
    $page_data['occupancy_details'] = $this->tours_model->get_occupancy();
    //debug($page_data); exit();
    $this->template->view('tours/occupancy_managment',$page_data);
  }

  public function delete_occupancy_managment($id)
  {

    $return = $this->tours_model->delete_occupancy_managment($id);
    if($return)
    {
     header('Location: '.base_url().'tours/occupancy_managment/');
   } 
   else
   {
     echo $return;
   }  
 }


 public function save_occupancy()
 {
  $insert_data['occupancy_name']  = $this->input->post('occupancy_name');
  $this->db->insert('occupancy_managment',$insert_data);
  redirect('tours/occupancy_managment');
}

public function price_management($id)
{
  //  debug($id);exit;
  $page_data['occupancy_details'] = $this->tours_model->get_occupancy();
  $page_data['price_details']   = $this->tours_model->get_price_details($id);
  $page_data['tour_id'] = $id;
  $currency  = $this->tours_model->get_currency_list(); 
    $currency_nat_price  = $this->Package_Model->get_currency_list(); 
  $page_data['currency'] = $currency;
   $page_data['currency_nat_price'] = $currency_nat_price;
  $page_data['tour_data'] = $this->tours_model->tour_data($id)[0];
  	$page_data ['nationality_group'] = $this->tours_model->nationality_group_data ();
  // debug($page_data['tour_data']);exit();
  	$data["country"] = $this->custom_db->single_table_records('tours_country', '*')[data];
		    //	debug($data["country"]);die;
            	 $page_data['holiday_data'] =$data; 
  // debug($page_data['tour_data']);exit();
  $this->template->view('tours/price_management',$page_data);
}
public function save_price_management()
{
   
 // debug($uri=$this->uri->segment(2));exit();
  // debug($this->input->post());exit();

// error_reporting(E_ALL);



$currency_selected = $this->input->post('currency_sel');
 $from_date = $this->input->post('from_date');
  $to_date = $this->input->post('to_date');
  $from_date = date("Y-m-d", strtotime($from_date) );
  $to_date = date("Y-m-d", strtotime($to_date) );
  $occupancy = $this->input->post('occupancy');
  $depature_price = round($this->input->post('depature_price'));
  $sessional_price = $this->input->post('sessional_price');
  $tour_id = $this->input->post('tour_id');
  $currency = $this->input->post('currency');
   $currency_selected = $this->input->post('currency_sel');
  $value_type = $this->input->post('value_type');
  $markup = $this->input->post('markup');
  //$currency=admin_base_currency();
  // echo  $currency;exit;
   $currency_converter = $this->custom_db->single_table_records('currency_converter','country,original_value,value',array('country'=>$currency));
  // debug($currency);exit;
  if($currency!='INR')
  {
    $converted_currency_rate= $currency_converter['data'][0]['value'];
  }
  else
  {
    $converted_currency_rate= 1;  
 }
  // debug($converted_currency_rate);exit;
  $airliner_price = $sessional_price/$converted_currency_rate;
  $final_airliner_price = $airliner_price;
  if($value_type == 'percentage')
  {
    $calculated_markup = ($airliner_price*$markup)/100;
 }else
 {
   $calculated_markup = $markup;
 }
 $airliner_price += $calculated_markup;
 // $calculated_markup = $markup;
  $adult_airliner_price=$this->input->post('adult_sessional_price');
  $child_sessional_price=$this->input->post('child_sessional_price');
    $infant_sessional_price=$this->input->post('Infant_sessional_price');
  $budget_hotel_price=$this->input->post('budget_hotel_price');
  $standard_hotel_price=$this->input->post('standard_hotel_price');
  $deluxe_hotel_price=$this->input->post('deluxe_hotel_price');
  $four_star_hotel_price=$this->input->post('4_star_hotel_price');
  $twin_share_hotel_price=$this->input->post('twin_share_hotel_price');
  $standard_car_price=$this->input->post('standard_car_price');
  $deluxe_car_price=$this->input->post('deluxe_car_price');
  $suv_car_price=$this->input->post('suv_car_price');
  $temp_traveller_price=$this->input->post('temp_traveller_price');
  $bus_price=$this->input->post('bus_price');
   $type=$this->input->post('radio');
   
   //--------currency---------//
      $currency_obj = new Currency(array('module_type' => 'holiday','from' => $currency_selected, 'to' => 'NPR')); 
         $get_currency_symbol = ($this->session->userdata('currency') != '') ? $this->CI->session->userdata('currency') : 'NPR';
            //debug($get_currency_symbol);exit;
         //$current_currency_symbol = $currency_obj->get_currency_symbol($get_currency_symbol);
         $converted_currency_rate = $currency_obj->getConversionRate(false);
         //debug($converted_currency_rate);exit;
          $converted_airliner_price = $converted_currency_rate*$adult_airliner_price;
          $converted_child_sessional_price = $converted_currency_rate*$child_sessional_price;
          $converted_infant_sessional_price = $converted_currency_rate*$infant_sessional_price;
          $nationality=$this->input->post('nationality');
//--------currency---------//
   //debug($this->input->post());die;
 $insert_data = array('from_date' => $from_date,
   'to_date'   => $to_date,
   'occupancy' => $occupancy,
   'sessional_price' => round($sessional_price),
   'currency'=>$currency,
   'currency_sel'=>$currency_selected,
     'nationality' =>$nationality,
   'final_airliner_price' => round($final_airliner_price),
   'markup'     => round($markup),
   'value_type' => $value_type,
   'calculated_markup' => round($calculated_markup),
   'airliner_price' => round($airliner_price),
   'adult_airliner_price' => round($adult_airliner_price),
   'child_airliner_price' => round($child_sessional_price),
    'infant_airline_price' => round($infant_sessional_price),
   'budget_hotel_price' => round($budget_hotel_price),
   'standard_hotel_price' => round($standard_hotel_price),
   'deluxe_hotel_price' => round($deluxe_hotel_price),
   '4_star_hotel_price' => round($four_star_hotel_price),
   'twin_share_hotel_price' => round($twin_share_hotel_price),
   'standard_car_price' => round($standard_car_price),
   'deluxe_car_price' => round($deluxe_car_price),
   'suv_car_price' => round($suv_car_price),
   
   'temp_traveller_price' => round($temp_traveller_price),
   'bus_price' => round($bus_price),
      'Type'=>$type,
   'tour_id' => $tour_id);
  // debug( $insert_data);die;
 $this->db->select('*');
$this->db->from('tour_price_management');
$this->db->where('from_date',$from_date);
$this->db->where('to_date',$to_date);
$this->db->where('tour_id',$tour_id);
$query=$this->db->get();

//debug($query);exit();
if($query->num_rows()>0){
  // set_duplicate_message();
   $this->session->set_flashdata('message', UL0098);
   header('Location: '.base_url().'index.php/tours/price_management/'.$tour_id);
}else{


 $result = $this->db->insert('tour_price_management',$insert_data);
 // debug($result);exit();
 if($result)
 {
   header('Location: '.base_url().'index.php/tours/price_management/'.$tour_id);
 }
}
}
public function save_price_managementold()
{
  // debug($this->input->post());exit();
  $from_date = $this->input->post('from_date');
  $to_date = $this->input->post('to_date');
  $from_date = date("Y-m-d", strtotime($from_date) );
  $to_date = date("Y-m-d", strtotime($to_date) );
  $occupancy = $this->input->post('occupancy');
  $depature_price = round($this->input->post('depature_price'));
  $sessional_price = $this->input->post('sessional_price');
  $tour_id = $this->input->post('tour_id');
  $currency = $this->input->post('currency');
  $value_type = $this->input->post('value_type');
  $markup = $this->input->post('markup');
  $currency_converter = $this->custom_db->single_table_records('currency_converter','country,original_value',array('country'=>$currency));
  if($currency!='AUD'){
  $converted_currency_rate= $currency_converter['data'][0]['original_value'];
  }else{
  $converted_currency_rate= 1;  
  }
  $airliner_price = $sessional_price/$converted_currency_rate;
  $final_airliner_price = $airliner_price;
  if($value_type == 'percentage')
  {
   $calculated_markup = ($airliner_price*$markup)/100;
 }else
 {
   $calculated_markup = $markup;
 }
 $airliner_price += $calculated_markup;
 // $calculated_markup = $markup;
  $adult_airliner_price=$this->input->post('adult_sessional_price');
  $child_sessional_price=$this->input->post('child_sessional_price');
  $budget_hotel_price=$this->input->post('budget_hotel_price');
  $standard_hotel_price=$this->input->post('standard_hotel_price');
  $deluxe_hotel_price=$this->input->post('deluxe_hotel_price');
  $four_star_hotel_price=$this->input->post('4_star_hotel_price');
  $twin_share_hotel_price=$this->input->post('twin_share_hotel_price');
  $standard_car_price=$this->input->post('standard_car_price');
  $deluxe_car_price=$this->input->post('deluxe_car_price');
  $suv_car_price=$this->input->post('suv_car_price');
  $temp_traveller_price=$this->input->post('temp_traveller_price');
  $bus_price=$this->input->post('bus_price');
  $type=$this->input->post('radio');
  if($final_airliner_price='NAN'){
    $final_airliner_price=0;
  }
  if($value_type==''){
    $value_type=0;
  }
  if($airliner_price='NAN'){
    $airliner_price=0;
  }

 $insert_data = array('from_date' => $from_date,
   'to_date'   => $to_date,
   'occupancy' => $occupancy,
   'sessional_price' => round($sessional_price),
   'currency' =>$currency,
   'final_airliner_price' => round($final_airliner_price),
   'markup'     => round($markup),
   'value_type' => $value_type,
   'calculated_markup' => round($calculated_markup),
   'airliner_price' => round($airliner_price),
   'adult_airliner_price' => round($adult_airliner_price),
   'child_airliner_price' => round($child_sessional_price),
   'budget_hotel_price' => round($budget_hotel_price),
   'standard_hotel_price' => round($standard_hotel_price),
   'deluxe_hotel_price' => round($deluxe_hotel_price),
   '4_star_hotel_price' => round($four_star_hotel_price),
   'twin_share_hotel_price' => round($twin_share_hotel_price),
   'standard_car_price' => round($standard_car_price),
   'deluxe_car_price' => round($deluxe_car_price),
   'suv_car_price' => round($suv_car_price),
   'temp_traveller_price' => round($temp_traveller_price),
   'bus_price' => round($bus_price),
    'Type' =>$type,
   'tour_id' => $tour_id);

 $result = $this->db->insert('tour_price_management',$insert_data);
 // debug($result);exit();
 if($result)
 {
   header('Location: '.base_url().'index.php/tours/price_management/'.$tour_id);
 }
}
public function edit_price($id)
{
  

  $page_data['price_details_single']  = $this->tours_model->get_price_details_single($id);
  $page_data['tour_data'] = $this->tours_model->tour_data( $page_data['price_details_single'][0]['tour_id']);
    $page_data ['nationality_group'] = $this->tours_model->nationality_group_data ();
  $page_data['occupancy_details'] = $this->tours_model->get_occupancy();
  $currency  = $this->tours_model->get_currency_list(); 
   $currency_nat_price  = $this->Package_Model->get_currency_list(); 
  $page_data['currency'] = 'NPR';
  $page_data['currency_nat_price'] = $currency_nat_price;
  // debug($page_data);exit;
    $data["country"] = $this->custom_db->single_table_records('tours_country', '*')[data];
		    //	debug($data["country"]);die;
            	 $page_data['holiday_data'] =$data; 
  // debug($page_data);exit;
  $this->template->view('tours/price_management_edit',$page_data);
}
public function delete_price($id,$tour_id)
{
  $get_tour_data = $this->db->get_where('tour_price_management', array('id' => $id))->row_array();
  //debug($get_tour_data['tour_id']);die;
  $tour_id = $get_tour_data['tour_id'];
  $return = $this->tours_model->delete_tour_price($id);
  if($return)
  {
   header('Location: '.base_url().'index.php/tours/price_management/'.$tour_id);
 } 
 else
 {
   echo $return;
 }  
}

public function price_management_pending($id)
{
  $page_data['occupancy_details'] = $this->tours_model->get_occupancy();
  $page_data['price_details']   = $this->tours_model->get_price_details($id);
  $page_data['tour_id'] = $id;
  $this->template->view('tours/price_management_pending',$page_data);
}
public function save_edit_price_management($id='')
{
//  error_reporting(E_ALL);
  $id = $this->input->post('id');
  $all_post = $this->input->post();
  $from_date = $this->input->post('from_date');
  $to_date = $this->input->post('to_date');
  $from_date = date("Y-m-d", strtotime($from_date) );
  $to_date = date("Y-m-d", strtotime($to_date) );
  $occupancy = $this->input->post('occupancy');
        // $depature_price = $this->input->post('depature_price');
  $sessional_price = $this->input->post('airliner_price');
  $tour_id = $this->input->post('tour_id');
  $currency = $this->input->post('currency');
   $nationality = $this->input->post('nationality');
  $currency_selected = $this->input->post('currency_sel');
  $value_type = $this->input->post('value_type');
  $markup = $this->input->post('markup');
  // $currency_obj = new Currency(array('module_type' => 'Holiday','from' => $currency , 'to' => 'CAD')); 
  // $converted_currency_rate = $currency_obj->getConversionRate(true);
  $currency_converter = $this->custom_db->single_table_records('currency_converter','country,original_value',array('country'=>$currency));
  $converted_currency_rate= $currency_converter['data'][0]['original_value'];
       // $final_airliner_price = $sessional_price+$calculated_markup;
  
   /*if($currency!='INR'){
  $converted_currency_rate= $currency_converter['data'][0]['original_value'];
  }else{*/
  $converted_currency_rate= 1;  
  /*}*/
  // debug($converted_currency_rate);exit;
  $airliner_price = $sessional_price/$converted_currency_rate;
  $final_airliner_price = $airliner_price;
  if($value_type == 'percentage')
  {
   $calculated_markup = ($airliner_price*$markup)/100;
 }
 else
 {
   $calculated_markup = $markup;
 }
 $airliner_price += $calculated_markup;
   $adult_airliner_price=$this->input->post('adult_sessional_price');
  $child_sessional_price=$this->input->post('child_sessional_price');
  $infant_sessional_price=$this->input->post('Infant_sessional_price');
  $budget_hotel_price=$this->input->post('budget_hotel_price');
  $standard_hotel_price=$this->input->post('standard_hotel_price');
  $deluxe_hotel_price=$this->input->post('deluxe_hotel_price');
  $four_star_hotel_price=$this->input->post('4_star_hotel_price');
  $twin_share_hotel_price=$this->input->post('twin_share_hotel_price');
  $standard_car_price=$this->input->post('standard_car_price');
  $deluxe_car_price=$this->input->post('deluxe_car_price');
  $suv_car_price=$this->input->post('suv_car_price');
  $temp_traveller_price=$this->input->post('temp_traveller_price');
  $bus_price=$this->input->post('bus_price');
  $type=$this->input->post('radio');
       //--------currency---------//
      $currency_obj = new Currency(array('module_type' => 'holiday','from' => $currency_selected, 'to' => 'NPR')); 
         $get_currency_symbol = ($this->session->userdata('currency') != '') ? $this->CI->session->userdata('currency') : 'NPR';
            //debug($get_currency_symbol);exit;
         //$current_currency_symbol = $currency_obj->get_currency_symbol($get_currency_symbol);
         $converted_currency_rate = $currency_obj->getConversionRate(false);
         //debug($converted_currency_rate);exit;
          $converted_airliner_price = $converted_currency_rate*$adult_airliner_price;
          $converted_child_sessional_price = $converted_currency_rate*$child_sessional_price;
          $converted_infant_sessional_price = $converted_currency_rate*$infant_sessional_price;
//--------currency---------//
 $data_arr=array(
   'occupancy'=>$occupancy,
   'sessional_price'=>round($sessional_price),
   'airliner_price'=>round($airliner_price),
   'tour_id'=>$tour_id,
   'markup'=>round($markup),
   'calculated_markup'=>round($calculated_markup),
   'final_airliner_price'=>round($final_airliner_price),
   'value_type'=>$value_type,
   'from_date'=>$from_date,
   'to_date'=>$to_date,
    'nationality'=>$nationality,
   'currency'=>$currency,
   'currency_sel'=>$currency_selected,
   'adult_airliner_price' => round($adult_airliner_price),
   'child_airliner_price' => round($child_sessional_price),
    'infant_airline_price' => round($infant_sessional_price),
    'budget_hotel_price' => round($budget_hotel_price),
   'standard_hotel_price' => round($standard_hotel_price),
   'deluxe_hotel_price' => round($deluxe_hotel_price),
   '4_star_hotel_price' => round($four_star_hotel_price),
   'twin_share_hotel_price' => round($twin_share_hotel_price),
   'standard_car_price' => round($standard_car_price),
   'deluxe_car_price' => round($deluxe_car_price),
   'suv_car_price' => round($suv_car_price),
   'temp_traveller_price' => round($temp_traveller_price),
   'bus_price' => round($bus_price),
   'Type' =>$type,
   'tour_id' => $tour_id
   );
 $result = $this->custom_db->update_record('tour_price_management',$data_arr,array('id' => $id));
 // set_update_message();
 $this->session->set_flashdata('message', UL0013);
 header('Location: '.base_url().'index.php/tours/price_management/'.$tour_id);
       /*if($result)
       {
       }*/
     }

public function save_edit_price_managementold($id='')
{
  $id = $this->input->post('id');
  $all_post = $this->input->post();
  $from_date = $this->input->post('from_date');
  $to_date = $this->input->post('to_date');
  $from_date = date("Y-m-d", strtotime($from_date) );
  $to_date = date("Y-m-d", strtotime($to_date) );
  $occupancy = $this->input->post('occupancy');
        // $depature_price = $this->input->post('depature_price');
  $sessional_price = $this->input->post('airliner_price');
  $tour_id = $this->input->post('tour_id');
  $currency = $this->input->post('currency');
  $value_type = $this->input->post('value_type');
  $markup = $this->input->post('markup');
  // $currency_obj = new Currency(array('module_type' => 'Holiday','from' => $currency , 'to' => 'CAD')); 
  // $converted_currency_rate = $currency_obj->getConversionRate(true);
  $currency_converter = $this->custom_db->single_table_records('currency_converter','country,original_value',array('country'=>$currency));
  $converted_currency_rate= $currency_converter['data'][0]['original_value'];
       // $final_airliner_price = $sessional_price+$calculated_markup;
   if($currency!='CAD'){
  $converted_currency_rate= $currency_converter['data'][0]['original_value'];
  }else{
  $converted_currency_rate= 1;  
  }
  $airliner_price = $sessional_price/$converted_currency_rate;
  $final_airliner_price = $airliner_price;
  if($value_type == 'percentage')
  {
   $calculated_markup = ($airliner_price*$markup)/100;
 }
 else
 {
   $calculated_markup = $markup;
 }
 $airliner_price += $calculated_markup;
   $adult_airliner_price=$this->input->post('adult_sessional_price');
  $child_sessional_price=$this->input->post('child_sessional_price');
  $budget_hotel_price=$this->input->post('budget_hotel_price');
  $standard_hotel_price=$this->input->post('standard_hotel_price');
  $deluxe_hotel_price=$this->input->post('deluxe_hotel_price');
  $four_star_hotel_price=$this->input->post('4_star_hotel_price');
  $twin_share_hotel_price=$this->input->post('twin_share_hotel_price');
  $standard_car_price=$this->input->post('standard_car_price');
  $deluxe_car_price=$this->input->post('deluxe_car_price');
  $suv_car_price=$this->input->post('suv_car_price');
  $temp_traveller_price=$this->input->post('temp_traveller_price');
  $bus_price=$this->input->post('bus_price');
   $type=$this->input->post('radio');
 $data_arr=array(
   'occupancy'=>$occupancy,
   'sessional_price'=>round($sessional_price),
   'airliner_price'=>round($airliner_price),
   'tour_id'=>$tour_id,
   'markup'=>round($markup),
   'calculated_markup'=>round($calculated_markup),
   'final_airliner_price'=>round($final_airliner_price),
   'value_type'=>$value_type,
   'from_date'=>$from_date,
   'to_date'=>$to_date,
   'currency'=>$currency,
   'adult_airliner_price' => round($adult_airliner_price),
   'child_airliner_price' => round($child_sessional_price),
    'budget_hotel_price' => round($budget_hotel_price),
   'standard_hotel_price' => round($standard_hotel_price),
   'deluxe_hotel_price' => round($deluxe_hotel_price),
   '4_star_hotel_price' => round($four_star_hotel_price),
   'twin_share_hotel_price' => round($twin_share_hotel_price),
   'standard_car_price' => round($standard_car_price),
   'deluxe_car_price' => round($deluxe_car_price),
   'suv_car_price' => round($suv_car_price),
   'temp_traveller_price' => round($temp_traveller_price),
   'bus_price' => round($bus_price),
    'Type' =>$type,
   'tour_id' => $tour_id
   );
 $result = $this->custom_db->update_record('tour_price_management',$data_arr,array('id' => $id));
 //set_update_message();
 header('Location: '.base_url().'index.php/tours/price_management/'.$tour_id);
       /*if($result)
       {
       }*/
     }



     public function voucher($tour_id,$operation='show_broucher',$mail = 'no-mail',$quotation_id = '',$app_reference = '',$email = '',$redirect = '',$ex_data = array())
    {
        $page_data['tour_id'] = $tour_id;
        $this->load->model('tours_model');
        $page_data['menu'] = false;
        $where = ['id'=>$tour_id];
        // debug($where); exit();
        $page_data ['tour_data']    = $this->tours_model->holiday_tour_data('tours', $where);
        // debug($page_data ['tour_data'] );exit;
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
                // $mail_template_mail =$this->template->isolated_view('tours/broucher',$page_data);
                $mail_template =$this->template->isolated_view('tours/broucher_pdf',$page_data);
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

//      public function voucher($tour_id,$operation='show_broucher',$mail = 'no-mail',$quotation_id = '',$app_reference = '',$email = '',$redirect = '',$ex_data = array())
//      {
     
//       $page_data['tour_id'] = $tour_id;
      
//       $page_data['en_note'] = $ex_data['en_note'];
//       $this->load->model('tours_model');
//       $page_data['menu'] = false;
//       // $page_data ['tour_data']            = $this->tours_model->tour_data($tour_id);
//         $where = ['id'=>$page_data['tour_id']];
//         $page_data ['tour_data']            = $this->tours_model->tour_data('tours', $where);
//       // debug($page_data); echo "I am here"; exit;

//       $page_data ['tours_itinerary']      = $this->tours_model->tours_itinerary($tour_id,$dep_date);
        
//       $page_data ['tours_itinerary_dw']   = $this->tours_model->tours_itinerary_dw($tour_id,$dep_date);

//       #debug($page_data ['tours_itinerary_dw']); exit;
//       $page_data ['tours_itinerary_wd']   = $this->tours_model->tours_itinerary_dw($tour_id);
//       $page_data ['tours_date_price']     = $this->tours_model->tours_date_price($tour_id);

      
//       $tour_data = $this->custom_db->get_result_by_query("select group_concat(airliner_price) pricing, group_concat(occupancy) occ,final_airliner_price,markup,group_concat(markup) markup ,tour_id, from_date, to_date , currency from tour_price_management where tour_id = ".$tour_id." group by from_date, to_date ");



//       $page_data['tour_price'] = json_decode(json_encode($tour_data),true);
//       // debug($page_data['tour_price']); exit('');
//       $tour_cities =  $page_data['tour_data']['tours_city'];
//       $tour_cities_array = json_decode($tour_cities);
//       foreach ($tour_cities_array as $t_city) {
//        $query_x = "select * from tours_city where id='$t_city'";
//        $exe_x   = mysql_query($query_x);
//        $visited_city[] = mysql_fetch_assoc($exe_x);
//      }
//      $page_data['visited_city'] = $visited_city;
//      if ($quotation_id!='') {
//        $quotation_details = $this->tours_model->quotation_details($quotation_id);
//        if ($quotation_details['status']==1) {
//         $page_data['quotation_details'] = $quotation_details['data'];
//       }
//     }
//     if ($app_reference!='') {
//      $booking_details = $this->tours_model->booking_details($app_reference);
//      if ($booking_details['status']==1) {
//       $page_data['booking_details'] = $booking_details['data'];
//     }
//   }
  
//   if($mail == 'mail') { 
//    $operation="mail";
//    if($this->input->post('email')){  
//     $email = $this->input->post('email');
//   }
// }

// if(isset($ex_data['en_note'])){
//   $page_data['en_note'] = $ex_data['en_note'];
//   }
//    //debug();exit();

//   switch ($operation) {
//   case 'show_broucher' : 
//   $page_data['menu'] = true;
//   //debug($page_data);exit();
//   $this->template->view('tours/broucher',$page_data);
//   break;
//   case 'show_pdf' :
//   $get_view = $this->template->isolated_view ( 'tours/broucher_pdf',$page_data );
//   $this->load->library ( 'provab_pdf' );
//   $this->provab_pdf->create_pdf ( $get_view, 'D');   
//   break;
//   case 'mail' :

//   $page_data['en_note'] = $ex_data['en_note'];
// //   debug($page_data);exit();
  
//   $mail_template =$this->template->isolated_view('tours/broucher_pdf',$page_data);

//   $this->load->library ( 'provab_pdf' );
//   $this->load->library ( 'provab_mailer' ); 
//  // $pdf = $this->provab_pdf->create_pdf($mail_template,'F');
//   if(count($ex_data)>0){       
//    $message = '<strong style="line-height:25px; font-size:16px;">Good day '.$ex_data['name'].',</strong><br>
//    <span style="line-height:25px; font-size:15px;">Please find the Holiday Package below. </span>';
//    if($ex_data['booking_url']){  
//     $message .= '<a style="line-height:25px; font-size:16px;" href="'.$ex_data['booking_url'].'" target="_blank">Click here to pay</a><br><br>';
//   }
// }

// //$res = $this->provab_mailer->send_mail(19, $email, 'Holiday Brochure', $message.$mail_template);

// if(!empty($redirect)){

//  return true;
// }else{
    
//   redirect(base_url().'tours/voucher/'.$tour_id,'refresh');
// }
// break;
// }
// }

public function email_voucher($email,$tour_id)
{

    //$tour_id = 1;
  
  error_reporting(0);
  $this->load->model('tours_model');
  $page_data ['tour_data']            = $this->tours_model->tour_data($tour_id);
  $page_data ['tours_itinerary']      = $this->tours_model->tours_itinerary($tour_id,$dep_date);
  $page_data ['tours_itinerary_dw']   = $this->tours_model->tours_itinerary_dw($tour_id,$dep_date);
  $page_data ['tours_itinerary_wd']   = $this->tours_model->tours_itinerary_dw($tour_id);
  $page_data ['tours_date_price']     = $this->tours_model->tours_date_price($tour_id);
  $tour_data = $this->custom_db->get_result_by_query("select group_concat(airliner_price) pricing, group_concat(occupancy) occ, group_concat(markup) markup ,tour_id, from_date, to_date from tour_price_management where tour_id = ".$tour_id." group by from_date, to_date ");
  $page_data['tour_price'] = json_decode(json_encode($tour_data),true);

    //debug($page_data); exit();
  $tour_cities =  $page_data['tour_data']['tours_city'];
  $tour_cities_array = json_decode($tour_cities);

  foreach ($tour_cities_array as $t_city) {
      $query_x = "select * from tours_city where id='$t_city'"; // echo $query; exit;
      $exe_x   = mysql_query($query_x);
      $visited_city[] = mysql_fetch_assoc($exe_x);

    }
    //debug($page_data); exit();  
    $page_data['visited_city'] = $visited_city;
    $page_data['tour_id'] = $tour_id;
    $page_data['menu'] = false;
    $mail_template =$this->template->isolated_view('tours/broucher',$page_data);
    //debug($mail_template); exit();
    //$mail_template = '<h1>hello</h1>';
  //  $email =
    $this->load->library ( 'provab_pdf' );
    $this->load->library ( 'provab_mailer' ); 
    $pdf = $this->provab_pdf->create_pdf($mail_template,'F');


    $s = $this->provab_mailer->send_mail ($email, 'Activity Confirmation', 'Content',$pdf);
    //debug($s); exit();
    $status = true;
    echo 'success';

    //$this->template->view('tours/broucher',$page_data);

  }

  public function check_price_avilability()
  {
    //debug($_POST); exit();
    $from = $this->input->post('from');
    $to = $this->input->post('to');
    $from = date("Y-m-d", strtotime($from));
    $to = date("Y-m-d", strtotime($to) );
    $occupency = $this->input->post('occupency');
    $tour_id = $this->input->post('tour_id');
    $nationality = $this->input->post('nationality');
   /* debug("supp");
    debug($tour_id);exit;*/
    // $price_avilability = $this->tours_model->check_price_avilability($from,$to,$occupency,$tour_id);
     $price_avilability = $this->tours_model->check_price_avilability($from,$to,$tour_id,$nationality);
    //debug($price_avilability); exit();
    if($price_avilability)
    {
     echo json_encode(array('status'=>false));
   }
   else
   {
     echo json_encode(array('status'=>true));
   }
 }

 public function tours_delete_image_id()
 {

  $deletename = $this->input->post('image_name');
  $deleteid   = $this->input->post('image_id');
  $tours_data = $this->tours_model->tour_data($deleteid);
    //debug($tours_data); exit();
  $images   = $tours_data['gallery'];
  $image_data = explode(',', $images);
  foreach($image_data as $key => $images_values)
  {
    if($images_values == $deletename)
    {

      unset($image_data[$key]);
    }
  }

  $new_data = implode(',', $image_data);
  $new_data = array('gallery' => $new_data);


  $info = $this->tours_model->update_tours_images($new_data, $deleteid);
  echo "1";
}

public function update_tour_voucher($tour_id) {
    $tour_data = $this->tours_model->tour_data_temp($tour_id); //debug($tour_data); exit; 
    $page_data['tour_data'] = $tour_data;
    $tour_destinations = $this->tours_model->tour_destinations();
    $page_data['tour_destinations'] = $tour_destinations;
    $page_data['tour_id'] = $tour_id;

    $tours_continent = $this->tours_model->tours_continent();

    $page_data['tours_continent_country'] = $this->tours_model->tours_continent_country($tour_id);
    $page_data['tours_country_city']      = $this->tours_model->tours_country_city($tour_id);
    $page_data['tours_country_name']      = $this->tours_model->tour_country();

    $page_data['tours_continent'] = $tours_continent;
    $page_data['tour_type'] = $this->tours_model->tour_type();
    $page_data['tour_subtheme'] = $this->tours_model->tour_subtheme();
    // /debug($page_data); exit;
    $this->template->view('tours/update_tour_package',$page_data);
  }

  public function update_tour_voucher_save()
  {
    $data = $this->input->post();
    //debug($data); exit();
    $tour_id               = sql_injection($data['tour_id']);
    $query_x = "select * from tours_temp where id='$tour_id'"; // echo $query; exit;
    $exe_x   = mysql_query($query_x);
    $fetch_x = mysql_fetch_array($exe_x);
    //debug($fetch_x); exit();
    if($fetch_x)
    {
      $old_image = $fetch_x['gallery'];
    }
    $package_name          = sql_injection($data['package_name']);
    $tours_continent       = sql_injection($data['tours_continent']);
    $tours_city_new     = $data['tours_city_new'];
    $tours_city = $tours_city_new;
    $tours_city     = implode(',',$tours_city);
    $duration       = sql_injection($data['duration']);
    $tour_type          = $data['tour_type'];
    $tour_type          = implode(',',$tour_type);
    $tours_country      = $data['tours_country'];
    $tours_country      = implode(',',$tours_country);
    $theme          = $data['theme'];
    $theme          = implode(',',$theme);
    $adult_twin_sharing    = sql_injection($data['adult_twin_sharing']);
    $adult_tripple_sharing = $data['adult_tripple_sharing'];
    if($adult_tripple_sharing=='')
    {
     $adult_tripple_sharing = 0;
   }
   else
   {
     $adult_tripple_sharing = sql_injection($adult_tripple_sharing);
   }

   $highlights            = sql_injection($data['highlights']);
   $inclusions            = sql_injection($data['inclusions']);
   $exclusions            = sql_injection($data['exclusions']);
   $terms                 = sql_injection($data['terms']);
   $canc_policy           = sql_injection($data['canc_policy']);

   $ppg        = $_REQUEST['gallery_previous'];
   $total_ppg  = count($ppg) ;
   $ppg_list   = '';
   for($c=0;$c<$total_ppg;$c++)
   {
    if($ppg_list=='')
    {
      $ppg_list = $ppg[$c];
    }
    else
    {
      $ppg_list = $ppg_list.','.$ppg[$c];
    }       
  }
  if($total_ppg>0)
  {
    $ppg_list = $ppg_list.',';
  }
  else
  {
    $ppg_list = '';
  } 
  $arr=array();
  if($_FILES['gallery']['name'][0]!="")
  {       
    $list  = $_FILES['gallery']['name'];
    $total_images = count($list); 
    for($i=0;$i<$total_images;$i++)
    {
         // for setting the unique name of image starts @@@@@@@@@@@@@@@@@@@
      $filename  = basename($list[$i]);
      $extension = pathinfo($filename, PATHINFO_EXTENSION);
      $uniqueno  = substr(uniqid(),0,5);
      $randno    = substr(rand(),0,5);
      $new       = $uniqueno.$randno.'.'.$extension;
      $folder    = $this->template->domain_image_upload_path();
      $folderpath= trim($folder.$new);
      $path      = addslashes($folderpath);
      move_uploaded_file($_FILES['gallery']['tmp_name'][$i], $folderpath);  
      array_push($arr,$new);

    } 
  }   
  if(!empty($_FILES['banner_image']['name']))
  {
   $banner_image = $_FILES['banner_image']['name'];
   $filename     = basename($banner_image);
   $extension    = pathinfo($filename, PATHINFO_EXTENSION);
   $uniqueno     = substr(uniqid(),0,5);
   $randno       = substr(rand(),0,5);
   $new          = $uniqueno.$randno.'.'.$extension;
   $folder       = $this->template->domain_image_upload_path();
   $folderpath   = trim($folder.$new);
   $path         = addslashes($folderpath);
   move_uploaded_file($_FILES['banner_image']['tmp_name'], $folderpath);             
   $banner_image = $new; 
   $banner_image_update = 'banner_image="'.$banner_image.'",'; 
 }else
 {
   $banner_image_update = '';
 }
 
 $old_image = explode(',', $old_image);
 $inclusions_checks   = $data['inclusions_checks'];
 $inclusions_checks   = json_encode($inclusions_checks,1);
 $Gallery_list = array_merge($arr,$old_image);
 $Gallery_list = implode(',', $Gallery_list);

 if($fetch_x)
 {
      //echo "update";exit();
  $query  = "update tours_temp set package_name='$package_name',
  tours_continent='$tours_continent',
  tours_country='$tours_country',
  tours_city='$tours_city',
  duration='$duration',
  tour_type='$tour_type',
  theme='$theme',
  adult_twin_sharing='$adult_twin_sharing',
  adult_tripple_sharing='$adult_tripple_sharing',
  child_with_bed='$child_with_bed',
  child_without_bed='$child_without_bed',
  joining_directly='$joining_directly',
  single_suppliment='$single_suppliment',
  service_tax='$service_tax',
  tcs='$tcs',
  highlights='$highlights',
  inclusions='$inclusions',
  exclusions='$exclusions',
  terms='$terms',
  canc_policy='$canc_policy',
  inclusions_checks='$inclusions_checks',
  ".$banner_image_update."
  gallery='$Gallery_list'
  where id='$tour_id'";
}
else
{

  $query  = "insert into tours_temp set package_name='$package_name',
  tours_continent='$tours_continent',
  tours_country='$tours_country',
  tours_city='$tours_city',
  duration='$duration',
  tour_type='$tour_type',
  theme='$theme',
  adult_twin_sharing='$adult_twin_sharing',
  adult_tripple_sharing='$adult_tripple_sharing',
  child_with_bed='$child_with_bed',
  child_without_bed='$child_without_bed',
  joining_directly='$joining_directly',
  single_suppliment='$single_suppliment',
  service_tax='$service_tax',
  tcs='$tcs',
  highlights='$highlights',
  inclusions='$inclusions',
  exclusions='$exclusions',
  terms='$terms',
  canc_policy='$canc_policy',
  inclusions_checks='$inclusions_checks',
  ".$banner_image_update."
  gallery='$Gallery_list'
  where id='$tour_id'";
}

        // echo $query; exit;
$return = $this->tours_model->query_run($query);
if($return)
{
 header('Location: '.base_url().'index.php/tours/edit_tour_package/'.$tour_id);

} 
else { echo $return; }        
}

public function updated_voucher($tour_id)
{

    //$tour_id = 1;

  error_reporting(0);
  $this->load->model('tours_model');
  $page_data ['tour_data']            = $this->tours_model->tour_data_temp($tour_id);
  $page_data ['tours_itinerary']      = $this->tours_model->tours_itinerary($tour_id,$dep_date);
  $page_data ['tours_itinerary_dw']   = $this->tours_model->tours_itinerary_dw($tour_id,$dep_date);
  $page_data ['tours_itinerary_wd']   = $this->tours_model->tours_itinerary_dw($tour_id);
  $page_data ['tours_date_price']     = $this->tours_model->tours_date_price($tour_id);
  $tour_data = $this->custom_db->get_result_by_query("select group_concat(airliner_price) pricing, group_concat(occupancy) occ, group_concat(markup) markup ,tour_id, from_date, to_date from tour_price_management where tour_id = ".$tour_id." group by from_date, to_date ");
  $page_data['tour_price'] = json_decode(json_encode($tour_data),true);

    //debug($page_data); exit();
  $tour_cities =  $page_data['tour_data']['tours_city'];
  $tour_cities_array = json_decode($tour_cities);

  foreach ($tour_cities_array as $t_city) {
      $query_x = "select * from tours_city where id='$t_city'"; // echo $query; exit;
      $exe_x   = mysql_query($query_x);
      $visited_city[] = mysql_fetch_assoc($exe_x);

    }
    //debug($page_data); exit();  
    $page_data['visited_city'] = $visited_city;
    $page_data['tour_id'] = $tour_id;
    $page_data['menu'] = false;
    $mail_template =$this->template->isolated_view('tours/broucher',$page_data);
    //debug($mail_template); exit();
    //$mail_template = '<h1>hello</h1>';
  //  $email =
    $this->load->library ( 'provab_pdf' );
    $this->load->library ( 'provab_mailer' ); 
    $pdf = $this->provab_pdf->create_pdf($mail_template,'F');


    
    
    $this->template->view('tours/broucher',$page_data);
    
  }
  public function cancel_booking($app_reference)
  {
    $this->load->model('custom_db');   
    $condition[]=array(
      'app_reference','=','"'.$app_reference.'"'
      );
    $booking_details = $this->tours_model->booking($condition);
    $this->load->library('provab_mailer'); 
    $page_data['data'] = $booking_details['data'][$app_reference];
    // debug($page_data);die;
   $this->template->view('tours/pre_cancellation', $page_data);
 }
 public function cancel_full_booking($app_reference)
  {
    // error_reporting(E_ALL);ini_set('display_error', 'on');
    $this->load->model('custom_db');
    $this->custom_db->update_record('tour_booking_details',array('status'=>'CANCELLED','final_cancel_date'=>date("Y-m-d h:i:sa")),array('app_reference'=>$app_reference));    
    $condition[]=array(
      'app_reference','=','"'.$app_reference.'"'
      );
    $page_data['app_reference'] = $app_reference;
    $page_data['status'] = 'CANCELLED';
    $booking_details = $this->tours_model->booking($condition);
    $this->load->library ( 'provab_mailer' );
    foreach ($booking_details['data'] as $key => $data) {
     $enquiry_reference_no=$key;
   }
   $voucher_data = $data;
   $attributes = json_decode($data['booking_details']['attributes'], true);
   $user_attributes = json_decode($data['booking_details']['user_attributes'], true);
   $voucher_data ['tours_itinerary_dw']   = $this->tours_model->tours_itinerary_dw($attributes['tour_id'],$attributes['departure_date']);
   $email = $user_attributes['email'];
   $voucher_data['menu'] = false;
   // debug($voucher_data);die('false');
   $sdata['app_reference'] = $voucher_data['booking_details']['app_reference'];
  $sdata['user_name'] = ucwords($voucher_data['pax_details'][0]['pax_first_name']);
  // debug($sdata);die('false');
   // $mail_template =$this->template->isolated_view('voucher/finalcancellationtemplate',$sdata);
   // die('30');
    // echo $mail_template; exit();
  /* $this->load->library ( 'provab_pdf' );
   $pdf = $this->provab_pdf->create_pdf($mail_template,'F', $app_reference);*/
   // echo $pdf;die;
   // debug($email);die;
   // $email = 'pankajprovab212@gmail.com';
   // $email_subject = "Your booking with ZipHop - Booking Reservation Code ".$sdata['app_reference']." has been cancelled.";
   // $this->provab_mailer->send_mail(21, $email, $email_subject, $mail_template,false);
   // debug($app_reference);die;
  $this->template->view('tours/cancellation_details',$page_data);
 }

 public function request_booking()
 {
  $post_data = $this->input->post();
  $enquiry_reference_no = generate_holiday_reference_number('ZHI');
  $post_data['enquiry_reference_no']=$enquiry_reference_no;
    // $post_data['tour_id']=$tour_id;
  $post_data['created_by']='supervision';
  $post_data['date']=date('Y-m-d H:i:s');
  $post_data['status']=1;
  $post_data['created_by_id']=$this->entity_user_id;
  $this->load->model('custom_db');
  $return = $this->custom_db->insert_record('tours_enquiry',$post_data);
  $this->send_link_to_user($enquiry_reference_no,false);
  redirect(base_url().'tours/tour_list','refresh');
}
/*public function send_booking_link($redirect=true)
{
  error_reporting(E_ALL);
  $post_data=$this->input->post();
  debug($post_data); exit;
  if ($post_data['enquiry_reference_no']) {
   $enquiry_data = $this->tours_model->enquiry_user_details($post_data['enquiry_reference_no']);
   $enquiry_data = json_decode(json_encode($enquiry_data[0]),true);
 }else{
   $post_data['departure_date']=date('Y-m-d',strtotime($post_data['departure_date']));
   $enquiry_data = $post_data;
 }
 $enquiry_data['tour_id'] = ($post_data['tour_id'])? $post_data['tour_id'] : $enquiry_data['tour_id'];

 $quote_reference = generate_holiday_reference_number('ZVQ');
 $tours_quotation_log_data=array();
 $tours_quotation_log_data['quote_reference']=$quote_reference;
 $tours_quotation_log_data['enquiry_reference_no']=$post_data['enquiry_reference_no'];
 $tours_quotation_log_data['tour_id']=$enquiry_data['tour_id'];
 $tours_quotation_log_data['departure_date']=$enquiry_data['departure_date'];
 $tours_quotation_log_data['title']=$enquiry_data['title'];
 $tours_quotation_log_data['first_name']=$enquiry_data['name'];
 $tours_quotation_log_data['middle_name']=$enquiry_data['mname'];
 $tours_quotation_log_data['last_name']=$enquiry_data['lname'];
 $tours_quotation_log_data['email']=$enquiry_data['email'];
 $tours_quotation_log_data['phone']=$enquiry_data['pn_country_code'].' '.$enquiry_data['phone'];
 $tours_quotation_log_data['quoted_price']=$post_data['total'];
 $tours_quotation_log_data['currency_code']=get_application_currency_preference();     
 $tours_quotation_log_data['user_attributes']=json_encode($post_data);
 $tours_quotation_log_data['created_by_id']=$this->entity_user_id;
 $tours_quotation_log_data['created_datetime']=date('Y-m-d H:i:s');
 $this->custom_db->insert_record('tours_quotation_log',$tours_quotation_log_data);
 
 if($post_data['quote_type']=='request_quote'){     
     // debug($enquiry_data['email']); exit('xxx');
   if(!empty($enquiry_data['email']))
   {
    $ex_data['name']=$enquiry_data['name'];
    $res = $this->voucher($enquiry_data['tour_id'],'mail','mail',$quote_reference,'',$enquiry_data['email'],'redirect',$ex_data);      
  }
}else{
  if($post_data['enquiry_reference_no']){
    $tours_enquiry = $this->custom_db->get_result_by_query('SELECT * FROM tours_enquiry WHERE enquiry_reference_no = "'.$post_data['enquiry_reference_no'].'" ');
    if($tours_enquiry){
      $tours_enquiry = json_decode(json_encode($tours_enquiry),1);
      $post_data['tour_id'] = $tours_enquiry[0]['tour_id'];
      $post_data['departure_date'] = $tours_enquiry[0]['departure_date'];
    }
  }
  $app_reference = generate_holiday_reference_number('ZVZ');
  $tour_booking_details_data=array();
  $tour_booking_details_data['enquiry_reference_no']=$post_data['enquiry_reference_no'];
  $tour_booking_details_data['app_reference']=$app_reference;
  $tour_booking_details_data['status']='PROCESSING';
  $tour_booking_details_data['basic_fare']=$post_data['total'];
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
if($res){
 $this->load->library('user_agent');
 if ($this->agent->is_referral())
 {
  redirect ( $this->agent->referrer());
}else{
  redirect ( base_url () . 'index.php/tours/tours_enquiry');
}
}
}*/
public function send_booking_link($redirect=true)
{
//  error_reporting(E_ALL);
  $post_data=$this->input->post();
//  debug($post_data);exit();

  //calculation for counting

  $post_data['adult_price'] = round($post_data['adult_price']);
  $post_data['child_price'] = round($post_data['child_price']);
  $post_data['infant_price'] = round($post_data['infant_price']);
  if($post_data['adult_price']!='' || $post_data['child_price']!=''|| $post_data['infant_price']){
  $post_data['total'] = ($post_data['adult_price']+$post_data['child_price']+$post_data['infant_price']);
  }
 

  //end
  if ($post_data['enquiry_reference_no']) {
   $enquiry_data = $this->tours_model->enquiry_user_details($post_data['enquiry_reference_no']);
   $enquiry_data = json_decode(json_encode($enquiry_data[0]),true);
 }else{
   $post_data['departure_date']=date('Y-m-d',strtotime($post_data['departure_date']));
   $enquiry_data = $post_data;
 }
 $enquiry_data['tour_id'] = (isset($post_data['tour_id']) && !empty($post_data['tour_id']))? $post_data['tour_id'] : $enquiry_data['tour_id'];
 $tt_id = $enquiry_data['tour_id'];

 $quote_reference = generate_holiday_reference_number('ZVQ');
 $tours_quotation_log_data=array();
 $tours_quotation_log_data['quote_reference']=$quote_reference;
 $tours_quotation_log_data['enquiry_reference_no']=$post_data['enquiry_reference_no'];
 $tours_quotation_log_data['tour_id']=$enquiry_data['tour_id'];
 $tours_quotation_log_data['departure_date']=$enquiry_data['departure_date'];
 $tours_quotation_log_data['title']=$enquiry_data['title'];
 $tours_quotation_log_data['first_name']=$enquiry_data['name'];
// $tours_quotation_log_data['middle_name']=$enquiry_data['mname'];
 $tours_quotation_log_data['last_name']=$enquiry_data['lname'];
 $tours_quotation_log_data['email']=$enquiry_data['email'];
 $tours_quotation_log_data['phone']=$enquiry_data['pn_country_code'].' '.$enquiry_data['phone'];
 $tours_quotation_log_data['en_note'] =  $post_data['en_note'];
 $tours_quotation_log_data['quoted_price']=round($post_data['total']);
 $tours_quotation_log_data['currency_code']=get_application_currency_preference();     
 $tours_quotation_log_data['user_attributes']=json_encode($post_data);
 $tours_quotation_log_data['created_by_id']=$this->entity_user_id;
 $tours_quotation_log_data['created_datetime']=date('Y-m-d H:i:s');
//debug($tours_quotation_log_data);
//exit;
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
  if($post_data['enquiry_reference_no']){
    $tours_enquiry = $this->custom_db->get_result_by_query('SELECT * FROM tours_enquiry WHERE enquiry_reference_no = "'.$post_data['enquiry_reference_no'].'" ');
    if($tours_enquiry){
      $tours_enquiry = json_decode(json_encode($tours_enquiry),1);
      $post_data['tour_id'] = $tours_enquiry[0]['tour_id'];
      $post_data['departure_date'] = $tours_enquiry[0]['departure_date'];
    }
  }
  $app_reference = generate_holiday_reference_number('ZVZ');
  $tour_booking_details_data=array();
  $tour_booking_details_data['enquiry_reference_no']=$post_data['enquiry_reference_no'];
  $tour_booking_details_data['app_reference']=$app_reference;
  $tour_booking_details_data['status']='PROCESSING';
  $tour_booking_details_data['basic_fare']=round($post_data['total']);
  $tour_booking_details_data['currency_code']=$post_data['currency'];
  $tour_booking_details_data['payment_status']='unpaid';
  $tour_booking_details_data['created_datetime']=date('Y-m-d H:i:s');
  $tour_booking_details_data['created_by_id']=$this->entity_user_id;
  $tour_booking_details_data['attributes']=json_encode($post_data);

  $this->custom_db->insert_record('tour_booking_details',$tour_booking_details_data);
  // $booking_url = base_url().'index.php/tours/pre_booking/'.$app_reference;
  $booking_url = base_url().'index.php/tours/holiday_pre_booking/'.$tt_id.'/'.$app_reference;
  $booking_url = str_replace('supervision/', '', $booking_url);
  if(!empty($enquiry_data['email']))
  {
    $ex_data['booking_url']=$booking_url;
    $ex_data['name']=$enquiry_data['name'];
    $res = $this->voucher($enquiry_data['tour_id'],'mail','mail','',$app_reference,$enquiry_data['email'],'redirect',$ex_data);
  }
}

//set_update_message ();
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
public function quotation_list()
{
  // if (!check_user_previlege('p250')) {
  //  set_update_message("You Don't have permission to do this action.", WARNING_MESSAGE, array(
  //   'override_app_msg' => true
  //   ));
  //  redirect(base_url());
  // }


  
    // $order_by = array('id' => 'DESC');
    // $quotation_list = $this->custom_db->single_table_records('tours_quotation_log', $cols = '*', $condition = array(), $offset = 0, $limit = 100000000,$order_by);
    // $page_data['quotation_list'] = $quotation_list['data'];
$query = 'SELECT tql.*, u.title as a_title,u.first_name as a_f_name,u.last_name as a_l_name,t.package_name FROM tours_quotation_log AS tql LEFT JOIN tours AS t ON tql.tour_id = t.id  LEFT JOIN user AS u ON tql.created_by_id = u.user_id ORDER BY tql.id DESC';
 $quotation_list = $this->custom_db->get_result_by_query($query);    
 $page_data['quotation_list'] = json_decode(json_encode($quotation_list),true);
 $this->template->view('tours/quotation_list',$page_data);
 $array = array(
  'back_link' => base_url().$this->router->fetch_class().'/'.$this->router->fetch_method()
  );    
 $this->session->set_userdata( $array );
}

  //for adding agent remark
  //for adding agent remark
  public function add_agent_remark(){
      // echo "I am ready";
      // exit;
     $this->db->where(array('uuid'=>$this->session->userdata('AID')));
     $this->db->select('title,first_name,last_name');
     $re = $this->db->get('user');
     if($re){
     $name = $re->result_array(); 
     $title = get_enum_list('title',$name[0]['title']);
     $name = $title." ".$name[0]['first_name']." ".$name[0]['last_name'];
     $r_id = $this->input->post('r_id');
     $agent_remark = $this->input->post('agent_remark');
     // debug($agent_remark);exit;

     ///select current agent remark
     $this->db->where(array('id'=>$r_id));
     $this->db->select('agent_remark');
     $re = $this->db->get('tours_enquiry');
     $re = $re->result_array();
     if($re[0]['agent_remark']){
      $agent_details = json_decode($re[0]['agent_remark'],TRUE);
       $update_data = array(
                          'agent_remark'=>$agent_details['agent_remark']."|".$agent_remark,
                          'created_date'=>$agent_details['created_date']."|".date('Y-m-d'),
                          'updated_by'=>$agent_details['updated_by']."|".$name,
                          'updated_id'=>$this->entity_user_id,
      ); 
     }else{
      $update_data= array(
                          'agent_remark'=>$this->input->post('agent_remark'),
                          'created_date'=>date('Y-m-d'),
                          'updated_by'=>$name,
                          'updated_id'=>$this->entity_user_id,
      ); 
     }
     // debug($r_id);exit();
     $update_data = json_encode($update_data);
     // echo json_encode($update_data);
     // exit;
     $this->db->where(array('id'=>$r_id));
      if( $this->db->update('tours_enquiry',array('agent_remark'=> $update_data,'status'=>0,'created_by_name'=>$name))){
        echo json_encode(TRUE);
      }else{
       echo json_encode(FALSE); }

     }else{
      echo json_encode(FALSE);
     }

       }
       //to insert contact information

       public function update_contact_info(){

                 $r_id = $this->input->post('r_id');
                 $update_contact_info = $this->input->post('agent_remark');
                 $data = array(
                    'contact_info' =>$update_contact_info,
                    'emergency_contact'=>$this->input->post('emergency_contact')
                  );
                 $this->db->where(array('app_reference'=>$r_id));
                 if($this->db->update('tour_booking_details',$data)){
                  echo  TRUE;
                 }else{
                  echo  FALSE;
                 }
       }
          //to insert contact information

       public function update_invoice_info(){

                 $r_id = $this->input->post('r_id');
                 $update_contact_info = $this->input->post('agent_remark');

                 $data = array(
                    'additional_invoice_info' =>$update_contact_info,
                  );
                 $this->db->where(array('app_reference'=>$r_id));
                 if($this->db->update('tour_booking_details',$data)){
                  echo  TRUE;
                 }else{
                  echo  FALSE;
                 }
       }







      // to update price
       public function update_price(){

        //echo "dregregref";exit();
        $res = $this->tours_model->fetch_price();
    $i=0;     
foreach($res as $value){
  
   $total = $value['final_airliner_price']+$value['calculated_markup'];
   $data = array('airliner_price'=>$total);
   $id = $value['id'];
   $res = $this->tours_model->update_final_price($id,$data);
   if($res){
    $i++;
   }
  }
     echo "Total ".$i." value changed";
       }


         public function update_price_cad(){

        //echo "dregregref";exit();
        $res = $this->tours_model->fetch_price();
        //debug($res);exit();
    $i=0;     
    
    foreach($res as $value){
  if($value['currency']=='CAD'){
   $total = $value['sessional_price']+$value['calculated_markup'];
   $data = array('airliner_price'=>$total);
   $id = $value['id'];
   $res = $this->tours_model->update_final_price($id,$data);
   if($res){
    $i++;
   }
  }}
     echo "Total ".$i." value changed";
       
     }


     //to update the new cities of tours from atif

     public function update_city_holiday_from_air(){
      //error_reporting(E_ALL);
      $result = $this->db->select('CityCode,country_id,CityName,CountryCode,CountryName')->get('tours_city');
      $city_list = $result->result_array();
     // $count_updated = 1;
      //debug($city_list);exit();
      $count_updated = 1;
      foreach ($city_list as $value) {
          $data_country_id = array('country_id'=>$value['country_id'],
            'CityCode'=>$value['CityCode'],
            'CityName'=>$value['CityName'],
            'CountryCode'=>$value['CountryCode'],
            'CountryName'=>$value['CountryName'],

            );
          //debug($data_country_id);exit();
           //$this->db->where('CountryCode',$value['CountryCode']);
           //$this->db->update('tours_city',$data_country_id);
           $this->db->insert('tours_city_06_04_2018',$data_country_id);
           $count_updated++;
          

      }
      echo $count_updated." Country id updated";
      }
}
