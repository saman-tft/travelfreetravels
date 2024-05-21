<?php  
//debug($currency_obj);
// debug($tour_data);die;
$expiry_date = date('Y-m-d',strtotime($tour_data['expire_date']));
$start_date = date('Y-m-d');

$date_diff= strtotime($expiry_date) - strtotime($start_date);
$actual_date = round($date_diff / 86400);
$visited_city_list = array();
Js_Loader::$css [] = array (
 'href' => $GLOBALS ['CI']->template->template_css_dir ( 'owl.carousel.min.css' ),
 'media' => 'screen' 
);
Js_Loader::$js [] = array (
 'src' => $GLOBALS ['CI']->template->template_js_dir ( 'owl.carousel.min.js' ),
 'defer' => 'defer' 
);
Js_Loader::$css [] = array (
  'href' => $GLOBALS ['CI']->template->template_css_dir ( 'fullcalendar.min.css' ),
  'media' => 'screen'
);
Js_Loader::$js [] = array (
  'src' => $GLOBALS ['CI']->template->template_js_dir ( 'fullcalendar.js' ),
  'defer' => 'defer'
);
Js_Loader::$js [] = array (
  'src' => $GLOBALS ['CI']->template->template_js_dir ( 'moment.js' ),
  'defer' => 'defer'
);
$template_images = $GLOBALS['CI']->template->template_images();

// debug($template_images);exit();
?>

<?php
//$package_datepicker = array(array('date_of_travel', FUTURE_DATE_DISABLED_MONTH));
//$GLOBALS['CI']->current_page->set_datepicker($package_datepicker);
?>
 

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="<?= SYSTEM_RESOURCE_LIBRARY ?>library/javascript/jquery-2.1.1.min.js"></script> -->

<link href="<?php echo $GLOBALS['CI']->template->template_css_dir('custom_tour.css') ?>" rel="stylesheet">

<link href="<?php echo $GLOBALS['CI']->template->template_css_dir('font-awesome.min.css') ?>" rel="stylesheet">

<link href="<?php echo $GLOBALS['CI']->template->template_css_dir('bootstrap.min.css') ?>" rel="stylesheet">
 
  <link href="<?php echo $GLOBALS['CI']->template->template_css_dir('custom_sky1.css') ?>" rel="stylesheet">
<style type="text/css">
/*.fulatnine p{margin: 0 !important;}*/
#alertp{background-color: transparent !important;color: #3c763d !important;padding: 10px 10px;font-size: 13px;}
.caption {
  background-color: rgba(0, 0, 0, 0.75);
  height: auto;
  position: absolute;
  bottom: 0;
  left: 0;
  padding: 10px;
  color: white;
  width: 100%;
}

.slideControls {
    position: absolute;
    width: 100%;
    top: 150px;
    left: 0;
    right: 0px;
    z-index: 999;
}
  .slideControls i{
    font-size: 60px;
    color: #fff;
  }
  .slideNext{
    right:40px;
    position:absolute;
    cursor:pointer;
  }
  .slidePrev{
    left:40px;
    position:absolute;
    cursor:pointer;
  }


#or {
  position: relative;
  width: 300px;
  height: 50px;
  margin-left: 25px;
  line-height: 50px;
  text-align: center;
}

#or::before,
#or::after {
  position: absolute;
  width: 130px;
  height: 1px;

  top: 24px;

  background-color: #aaa;

  content: '';
}

#or::before {
  left: 0;
}

#or::after {
  right: 0;
}

.fulldetab.mart20 .nav-tabs.trul li a span { display: block; margin:0 0 10px; height: 20px; }
.fulldetab.mart20 .nav-tabs.trul li a span { font-size: 20px; color: #000; }

.caption p{ margin: 0px; }

.imgs img {
    width: 100%;
    height: 370px;
    object-fit: cover;
}
img.star_rat {
    border-radius: 10px;
}


#holiday_load_new .owl-buttons {text-align: right;margin-right: 20px;}
#holiday_load_new.owl-theme .owl-prev{width: 30px;height: 30px;margin-top: -19px;opacity: 1;background: #40b5ec !important;border-radius: 50%;box-shadow: 2px 2px 2px #ccc;font-size: 0px;}
#holiday_load_new.owl-theme .owl-prev:before {/* position: absolute; */font-size: 16px;content: '\f053'!important;font-family: 'Font Awesome 5 Pro';color: #000;left: 10px;top: 4px;}
#holiday_load_new.owl-theme .owl-next {right: 13px;}
#holiday_load_new.owl-theme .owl-next:before {/* position: absolute; */font-size: 16px;content: '\f054'!important;font-family: 'Font Awesome 5 Pro';color: #000;left: 13px;top: 4px;}
#holiday_load_new.owl-theme .owl-next, #holiday_load_new.owl-theme .owl-prev {
    width: 30px;
    height: 30px;
    margin-top: -19px;
    opacity: 1;
    background: #fff !important;
    border-radius: 0px;
    box-shadow: 2px 2px 2px #ccc;
    border: 1px solid #f8f8f8;
    font-size: 0px;
}

</style>
<script  src="<?php echo $GLOBALS['CI']->template->template_js_dir('initialize-carousel-detailspage.js') ?>"
  type="text/javascript"></script>
  <script
  src="<?php echo $GLOBALS['CI']->template->template_js_dir('jquery.provabpopup.js') ?>"
  type="text/javascript"></script>
  <script
  src="<?php echo $GLOBALS['CI']->template->template_js_dir('page_resource/thumbnail-slider.js') ?>"
  type="text/javascript"></script>
  <script
  src="<?php echo $GLOBALS['CI']->template->template_js_dir('jquery.carouFredSel-6.2.1-packed.js') ?>"
  type="text/javascript"></script>
 
<script type="text/javascript">
  //$("#sim_quantity").onkeyup( function() {
  $(document).on("keypress","#sim_quantity", function(e) {
    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message
        e.preventDefault();
    }    
});
  $(document).on("keyup","#sim_quantity", function(e) {
   var searchQuery = $("#sim_quantity").val();
      //alert("Seacrh suggestion satrting with - " + searchQuery);   
});

</script>
  <?php

///price calculation for qatar 
$adult_count=1;//$this->session->userdata('adult_count');
$child_count=0;//$this->session->userdata('child_count');  
$tour_id=$tour_data['id'];
//debug($tour_data);exit;
$tour_price_changed = tour_price_list($currency_obj,$tour_id,$adult_count,$child_count,$_GET['radio']);
 //debug($tour_price_changed);exit();
foreach($tour_price_changed as $price_key => $price_data){
  $tour_price_list[$price_key]['from'] = $price_data['from_date'];
  $tour_price_list[$price_key]['to'] = $price_data['to_date'];
  $tour_price_list[$price_key]['price'] = $price_data['changed_price'];
}
// debug($tour_price_list);exit;
if(valid_array($tour_price_changed)){
 $min_numbers = array_column($tour_price_changed, 'changed_price');
  // debug($min_numbers);exit;
 $min_price = min($min_numbers);        
}
   //debug($min_price);exit;


     //oldone $min_price_1 =   isset($min_price)? (get_converted_currency_value ( $currency_obj->force_currency_conversion ( $min_price ) )):0;  
  //31-03-2021 commented  
      // $currency_obj = new Currency(array('module_type' => 'flight','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
      // $min_price_1 = $currency_obj->getConversionRate() * $min_price;  //newone

      // $admin_markup_holidaylcrs = $this->domain_management_model->addHolidayCrsMarkup($min_price_1, $tours_crs_markup,$currency_obj);

      // $min_price_1=str_replace(',','', $min_price_1); 

      // $min_price=$min_price_1+$admin_markup_holidaylcrs;     

      // $Markup=$admin_markup_holidaylcrs;
      //   $gst_value = 0;
      //         //adding gst
      //       if($Markup > 0 ){
      //           $gst_details = $this->custom_db->single_table_records('gst_master', '*', array('module' => 'holiday'));
      //           if($gst_details['status'] == true){
      //               if($gst_details['data'][0]['gst'] > 0){
                        
      //                   $gst_value = ($Markup/100) * $gst_details['data'][0]['gst'];
      //               }
      //           }

      //        }
      
      // $get_id = $tour_data['id'];
      //  $currency_obj = new Currency(array('module_type' => 'Holiday','from' => 'INR', 'to' => get_application_currency_preference()));     
      // $convenience_fees  = $currency_obj->convenience_fees_holiday($min_price,$get_id); 

      // $gst_value_conv=0;
      // if($convenience_fees > 0 )
      // {
      //         $gst_details = $GLOBALS['CI']->custom_db->single_table_records('gst_master', '*', array('module' => 'holiday'));
      //         // debug($gst_details);exit;
      //         if($gst_details['status'] == true){
      //             if($gst_details['data'][0]['gst'] > 0){
                     
                     
      //                $gst_value_conv = ($convenience_fees/100) * $gst_details['data'][0]['gst'];
      //             }
      //         }
      // }
//31-03-2021 commented 
 //debug($currency_obj); 
// debug($min_price);exit; 
$min_price_1 =   isset($min_price)? (get_converted_currency_value ( $currency_obj->force_currency_conversion ( $min_price ) )):0;    
  //debug($min_price_1);exit; 
         $admin_markup_holidaylcrs = $this->domain_management_model->addHolidayCrsMarkup($min_price_1, $tours_crs_markup,$currency_obj);
   
         $min_price_1=str_replace(',','', $min_price_1); 
   
         $min_price=$min_price_1+$admin_markup_holidaylcrs;     
   
         $Markup=$admin_markup_holidaylcrs;
           $gst_value = 0;
                 //adding gst
               if($Markup > 0 ){
                   $gst_details = $this->custom_db->single_table_records('gst_master', '*', array('module' => 'holiday'));
                   if($gst_details['status'] == true){
                       if($gst_details['data'][0]['gst'] > 0){
                           
                           $gst_value = ($Markup/100) * $gst_details['data'][0]['gst'];
                       }
                   }
   
                }
         //$convenience_fees  = $currency_obj->convenience_fees($min_price+$gst_value,1);

         $total_passengers = $adult_count + $child_count;
      //   $convinence_fees_row = $this->private_management_model->get_convinence_fees('Holiday');
              //debug($convinence_fees_row);
              if($convinence_fees_row['per_pax']==1 && $convinence_fees_row['type'] == 'plus'){
        if($convinence_fees_row['value']!=0)
              {
                   $convenience_fees= $convinence_fees_row['value']*$total_passengers;
                   if($currency_obj->to_currency=='NPR'){
                      $convenience_fees = $convenience_fees;
                    }else{
                      $convenience_fees = $currency_obj->getConversionRate() * $convenience_fees;
                    }


              }

      }else{
        
        $convenience_fees  = $currency_obj->convenience_fees($min_price+$gst_value,1);

      }


          //debug($convenience_fees);exit;
         $gst_value_conv=0;
         if($convenience_fees > 0 )
         {
                 $gst_details = $GLOBALS['CI']->custom_db->single_table_records('gst_master', '*', array('module' => 'holiday'));
                 // debug($gst_details);exit;
                 if($gst_details['status'] == true){
                     if($gst_details['data'][0]['gst'] > 0){
                        
                        
                        $gst_value_conv = ($convenience_fees/100) * $gst_details['data'][0]['gst'];
                     }
                 }
         }

      $min_price=$min_price+ $gst_value;
      $grand_total=$min_price;
      $grand_total +=$convenience_fees;
      $grand_total +=$gst_value_conv;
    //  debug($gst_value);

// echo "string";exit();
?>
<style type="text/css">
  .lSGallery{
    display: none;
  }
</style>
<div class="full witcontent  marintopcnt">
 <div class="container">
  <div class="full">
   <div class="contentpad">
    <div class="fulldetab grbrdr">
     <div class="col-md-8 nopad">
      <div class="col-md-12 col-sm-12 col-xs-12 sldrs nopad">  
        <div id="holiday_load_new" class="owl-carousel owlindex3 owl-theme owl-loaded owl-drag" >
          <!-- <div class="item">
            <div class="imgs">
              <img class="star_rat" src="https://www.alkhaleejtours.com/extras/custom/TMX9604421616070986/images/607c112685.jpg" alt=""/>
            </div>
          </div> -->
          <?php
              $image_description = json_decode($tour_data['image_description'], TRUE);
            $gallery = $tour_data['gallery'];
            // debug($gallery);exit;
            $explode = explode(',',$gallery);  
         //debug($explode);die;
            $explode_new = array();
            $img_count = 0;
            foreach ($explode as $key => $value) {
             $file_name = str_ireplace("/quaqua/",base_url(), $this->template->domain_images($value));
             // echo $file_name;
             // debug(file_exists($file_name));die;
             // echo substr($file_name, -3);exit;
             if (getImageCondition(substr(strtolower($file_name), -3))) {
               // echo substr($file_name, -3)."<br/>";
               $explode_new[$img_count] = $value;
               $img_count++;
             }
             
           }
          // debug($explode_new);exit;
                          // die;
           $explode = array();
           $explode = $explode_new;
             // debug($explode);die;
            // debug($explode_new);die;             
           if(!empty($explode) && valid_array($explode)) { 
             for($g = 0; $g<=count($explode); $g++) 
             { 
              $image_description_text = "";
              if((isset($image_description[$explode[$g]])) && ($image_description[$explode[$g]] != "")){
                $image_description_text =  $image_description[$explode[$g]];
              }
             //  debug($explode);exit();
              if (!empty($explode[$g])){
            ?>
                                <div class="item">
                                  <div class="f-deal slide" data-slide-index="0">
                                    <div class="f-deal-img imgs">
            <?php 
                echo '<img class="star_rat" src="'.$this->template->domain_images($explode[$g]).'" alt=""/>'; 
            ?>
            </div>
          </div>
          </div>
        <?php 
             }
           } 
         }
      ?>
        </div>
        <!-- <div class="demo">
         <div class="item">            
          <div class="clearfix">

             <div class="slideControls">
            <a class="slidePrev">
              <i class="fa fa-angle-left"></i>
             </a>
            <a class="slideNext">
              <i class="fa fa-angle-right"></i>
            </a>
      </div> 

           <ul id="vertical">
           <?php 

          

            $image_description = json_decode($tour_data['image_description'], TRUE);
            $gallery = $tour_data['gallery'];
            // debug($gallery);exit;
            $explode = explode(',',$gallery);  
         //debug($explode);die;
            $explode_new = array();
            $img_count = 0;
            foreach ($explode as $key => $value) {
             $file_name = str_ireplace("/quaqua/",base_url(), $this->template->domain_images($value));
             // echo $file_name;
             // debug(file_exists($file_name));die;
             // echo substr($file_name, -3);exit;
             if (getImageCondition(substr(strtolower($file_name), -3))) {
               // echo substr($file_name, -3)."<br/>";
               $explode_new[$img_count] = $value;
               $img_count++;
             }
             
           }
          // debug($explode_new);exit;
                          // die;
           $explode = array();
           $explode = $explode_new;
             // debug($explode);die;
            // debug($explode_new);die;             
           if(!empty($explode) && valid_array($explode)) { 
             for($g = 0; $g<=count($explode); $g++) 
             { 
              $image_description_text = "";
              if((isset($image_description[$explode[$g]])) && ($image_description[$explode[$g]] != "")){
                $image_description_text =  $image_description[$explode[$g]];
              }
             //  debug($explode);exit();
              if (!empty($explode[$g])){
               echo '<li  data-thumb="'.$this->template->domain_images($explode[$g]).'"><img alt="" src="'.$this->template->domain_images($explode[$g]).'">
               <div class="caption"> <p>'.$image_description_text.'</p></div></li>';    
             }
           } 
         } /*else {
           echo '<li data-thumb="'.$this->template->template_images('no_image.png').'"><img src="'.$this->template->template_images('no_image.png').'"><div class="caption"> <p></p></div></li>';
         }*/
         $video = $tour_data['video'];
            // debug($gallery);exit;
            $explode_video = explode(',',$video);  
         //debug($explode);die;
            $explode_video_new = array();
            $video_count = 0;
            // debug($explode_video);exit;
            foreach ($explode_video as $key => $value) {

             $file_name = str_ireplace("/quaqua/",base_url(), $this->template->domain_images($value));
             // debug($file_name);exit;
             // echo $file_name;
             // debug(file_exists($file_name));die;
             // echo substr($file_name, -3);exit;
             /*if (getImageCondition(substr(strtolower($file_name), -3))) {*/
               // echo substr($file_name, -3)."<br/>";
              if($value !="")
              {
                $explode_video_new[$video_count] = $value;
                $video_count++;
              }
               
            /* }*/
             
           }
          // debug($explode_new);exit;
                          // die;
           $explode_video = array();
           $explode_video = $explode_video_new;
           // debug($explode_video);exit;
             // debug($explode);die;
            // debug($explode_new);die;             
           if(!empty($explode_video) && valid_array($explode_video)) { 
             for($g = 0; $g<=count($explode_video); $g++) 
             { 
             
             
             
              if (!empty($explode_video[$g])){
               echo '<li data-thumb="'.$this->template->domain_images($explode_video[$g]).'">
                      <video width="800" height="350" controls class="gal_img" >
                        <source src="'.$this->template->domain_images( $explode_video[$g] )  . '" >

                      </video>

               <div class="caption"> </div></li>';    
             }
           } 
         }
         if(empty($explode)  && empty($explode_video)) {
            echo '<li data-thumb="'.$this->template->template_images('no_image.png').'"><img alt="" src="'.$this->template->template_images('no_image.png').'"><div class="caption"> <p></p></div></li>';
         }  



         

         // debug("asd");exit();
         ?>
       </ul>
     </div>
   </div>
 </div>   -->     
</div>
<div class="col-md-12 col-sm-12 col-xs-12 nopad">
  <div class="trip_detailsmiddle">
    <div class="alltripstatus">
      <input type="hidden" id="min_price" name="min_price" value="<?=$min_price?>">
      <h3 class="gateway"><?=$tour_data['package_name']; ?></h3>
      <div class="clearfix"></div>
                     <!-- <div class="col-md-4 col-sm-4 col-xs-3 nopad">
                     
                      <a class="view_map_dets location-map " href="<?php echo base_url().'index.php/hotels/map?lat='.$latitude.'&lon='.$longitude.'&hn='.urlencode($tour_data['package_name']).'&sr='.intval(5).'&c='.urlencode($destination).'&img='.$image_url.urlencode($hotel_details['thumb_image']) ?>" target="map_box_frame">
                      <strong><?php echo $this->lang->line('hotel_view_map');?></strong>
                      </a>
                    </div> -->

                    <h3 class="nyt1"><?=($tour_data['duration']+1);?> Days / <?=$tour_data['duration'];?> <?=($tour_data['duration']==1)? 'Night': 'Nights';?></h3>
                    <a class="noover hide" href="#price_detail_tab" id="price_table" onclick="price_tableclick()" data-toggle="modal"><h4>&nbsp;&nbsp;&nbsp;Price Table</h4></a>
                  </div>
                  <div class="clearfix"></div>
                  <div class="alltripstatus">
                    <?php if($tour_price['0']['budget_hotel_price']>0 || ($tour_price['0']['standard_hotel_price']>0) || ($tour_price['0']['deluxe_hotel_price']>0) || ($tour_price['0']['deluxe_hotel_price']>0) || ($tour_price['0']['4_star_hotel_price']>0) || ($tour_price['0']['twin_share_hotel_price']>0) ){ ?>
                      <h3>Select Hotel</h3>
                    <?php } ?>
                     
                    <div class="cc-selector">
                      <?php if($tour_price['0']['budget_hotel_price']>0){ ?>
                        <input class="dynamicPriceBreakdown" checked="checked" id="star_1" type="radio" name="hotel_type" value="star_rating_1" data-type="hotel" data-priceTitle="budget_hotel_price" data-price="<?=$tour_price['0']['budget_hotel_price'];?> ">                            
                        <label class="drinkcard-cc star_rating_1" for="star_1" style="text-align: center;"><br>Budget<br><span id="sprice1"><?=get_application_display_currency_preference();?>&nbsp;<?=$tour_price['0']['budget_hotel_price'];?> </span><!-- <p style="font-weight: 200;font-size: 10px;">per person</p> --></label> 
                      <?php } ?>
                      <?php if($tour_price['0']['standard_hotel_price']>0){ ?>
                        <input class="dynamicPriceBreakdown" id="star_2" type="radio" name="hotel_type" value="star_rating_2" data-type="hotel" data-priceTitle="standard_hotel_price" data-price="<?=$tour_price['0']['standard_hotel_price'];?>">
                        <label class="drinkcard-cc star_rating_2" for="star_2" style="text-align: center;"><br>Standard<br><span id="sprice2"><?=get_application_display_currency_preference();?>&nbsp;<?=$tour_price['0']['standard_hotel_price'];?></span><!-- <p style="font-weight: 200;font-size: 10px;">per person</p> --></label>
                      <?php } ?>
                      <?php if($tour_price['0']['deluxe_hotel_price']>0){ ?>
                        <input class="dynamicPriceBreakdown"  id="star_3" type="radio" name="hotel_type" value="star_rating_3" data-type="hotel" data-priceTitle="deluxe_hotel_price" data-price="<?=$tour_price['0']['deluxe_hotel_price'];?>">
                        <label class="drinkcard-cc star_rating_3" for="star_3" style="text-align: center;"><br>Deluxe<br><span id="sprice3"><?=get_application_display_currency_preference();?>&nbsp;<?=$tour_price['0']['deluxe_hotel_price'];?></span><!-- <p style="font-weight: 200;font-size: 10px;">per person</p> --></label>
                      <?php } ?>
                      <?php  if($tour_price['0']['4_star_hotel_price']>0){ ?>
                        <input class="dynamicPriceBreakdown" id="star_4" type="radio" name="hotel_type" value="star_rating_4" data-type="hotel" data-priceTitle="4_star_hotel_price" data-price="<?=$tour_price['0']['4_star_hotel_price'];?>">
                        <label class="drinkcard-cc star_rating_4" for="star_4" style="text-align: center;"><br>4 Star<br><span id="sprice4"><?=get_application_display_currency_preference();?>&nbsp;<?=$tour_price['0']['4_star_hotel_price'];?></span><!-- <p style="font-weight: 200;font-size: 10px;">per person</p> --></label>
                      <?php } ?>
                          <!--<?php if($tour_price['0']['twin_share_hotel_price']>0){ ?>
                            <input class="dynamicPriceBreakdown" id="star_5" type="radio" name="hotel_type" value="star_rating_5" data-type="hotel" data-priceTitle="twin_share_hotel_price" data-price="<?=$tour_price['0']['twin_share_hotel_price'];?>">
                            <label class="drinkcard-cc star_rating_5" for="star_5" style="text-align: center;"><br>TwinShare<br><span id="sprice4"><?=get_application_display_currency_preference();?>&nbsp;<?=$tour_price['0']['twin_share_hotel_price'];?></span><p style="font-weight: 200;font-size: 10px;">per person</p></label>
                            <?php } ?>-->
                          </div> 
                          
                          <div class="clearfix"></div>
                        </div>
                    <?php 

                      ?>
                        <div class="alltripstatus car_status">
                          <?php if($tour_price['0']['standard_car_price']>0 || ($tour_price['0']['deluxe_car_price']>0) || ($tour_price['0']['suv_car_price']>0) || ($tour_price['0']['temp_traveller_price']>0) || ($tour_price['0']['bus_price']>0)){?>
                            <h3>Select Transfer</h3>
                          <?php } ?>
                          <div class="cc-selector">
                            <?php if($tour_price['0']['standard_car_price']>0){?>
                              <input class="dynamicPriceBreakdown"  checked="checked" id="visa" type="radio" name="car_type" value="Hatchback" data-type="car" data-priceTitle="standard_car_price" data-price="<?=$tour_price['0']['standard_car_price'];?>">
                              <label class="drinkcard-cc hatchback" for="visa">Standard</label>&nbsp;
                            <?php } if($tour_price['0']['deluxe_car_price']>0){ ?>
                              <input  class="dynamicPriceBreakdown"  id="mastercard" type="radio" name="car_type" value="Sedan" data-type="car" data-priceTitle="deluxe_car_price" data-price="<?=$tour_price['0']['deluxe_car_price'];?>">
                              <label class="drinkcard-cc sedan" for="mastercard">Deluxe</label>&nbsp;
                            <?php } if($tour_price['0']['suv_car_price']>0){ ?>
                              <input class="dynamicPriceBreakdown"  id="suv" type="radio" name="car_type" value="SUV" data-type="car" data-priceTitle="suv_car_price" data-price="<?=$tour_price['0']['suv_car_price'];?>">
                              <label class="drinkcard-cc suv" for="suv">SUV</label>&nbsp;
                            <?php } if($tour_price['0']['temp_traveller_price']>0){ ?>
                              <input class="dynamicPriceBreakdown"  id="temp_traveller" type="radio" name="car_type" value="SUV" data-type="car" data-priceTitle="temp_traveller_price" data-price="<?=$tour_price['0']['temp_traveller_price'];?>">
                              <label class="drinkcard-cc tempo-ico" for="temp_traveller">Temp Traveller</label>&nbsp;
                            <?php } if($tour_price['0']['bus_price']>0){ ?>
                              <input class="dynamicPriceBreakdown"  id="bus" type="radio" name="car_type" value="SUV" data-type="car" data-priceTitle="bus_price" data-price="<?=$tour_price['0']['bus_price'];?>">
                              <label class="drinkcard-cc bus-ico" for="bus">Bus</label>

                            <?php }?>
                          </div>
                        </div>
                        <!--  <h4>Duration  : <span class="ststus_color_duration">0N/1D</span></h4> -->
                        <?php 
                        $len=sizeof($tours_itinerary_dw); 
                     // debug($len);
                        $start_city_id=$tours_itinerary_dw[0]['visited_city_day'];
                      // debug($start_city_id);exit;
                        $end_city_id=$tours_itinerary_dw[$len-1]['visited_city_day'];
                        $start_city=$tours_city_name[$start_city_id];
                        $end_city=$tours_city_name[$end_city_id];
                      // $cities_covered=json_decode($tour_data['tours_city'],true);
                        $cities=explode(',',$tour_data['tours_city']);
                      // debug($cities);exit;

//                       debug($cities_covered);exit;
                        $palces_covered='';
                        foreach ($cities as $ckey => $cvalue) {
                          $visited_city_list[$ckey] = $tours_city_name[$cities[$ckey]];


                          // debug( $visited_city_list);exit();
                          $palces_covered.=$tours_city_name[$cities[$ckey]];
                           
                          $palces_covered.=',';


                          
                        }
                        $ss=substr($palces_covered, -1);
                          if($ss==',')
                          {
                            $palces_covered=substr($palces_covered, 0, -1);
                          }
                     // debug($visited_city_list);
//                      exit;
                        ?>
                        <div class="alltripstatus">

                          <h4><span class="city-dtl">Start City: </span> <span class="ststus_color"><?=$start_city?></span></h4>
                        </div>
                        <div class="clearfix"></div>
                        <div class="alltripstatus">
                          <h4><span class="city-dtl">End City: </span> <span class="ststus_color"><?=$end_city?></span></h4>
                        </div>                 
                  <!--   <div class="clearfix"></div>
                    <div class="alltripstatus">
                      <h4>Category : <span class="ststus_color">                        Nature explorer,
                                             Hill Station,
                                             Leisure,
                     </span></h4>
                    </div>
                    <div class="clearfix"></div>
                    <div class="alltripstatus">
                      <h4> Package Includes : <span class="ststus_color">
                       Transfers,Sightseeing                     </span></h4>                      
                    </div>
                  -->
                  <div class="clearfix"></div>
                  <div class="alltripstatus">
                    <h4> <span class="city-dtl">Places covered: </span> <span class="ststus_color">
                     <?=$palces_covered?>                      </span></h4>                      
                   </div>
                   <div class="clearfix"></div>
                   <div class="alltripstatus">
                     
                   </div>
                   <!--  <div class="clearfix"></div>
                    <div class="alltripstatus">
                      <h4>With : <span class="ststus_color">Travis, Scott, Julie, Beth</span></h4>
                    </div>
                    <div class="clearfix"></div>
                    <div class="alltripstatus">
                      <h4>Hotel Selected : <span class="ststus_color"><a href="javascript:void(0);">Hilton, Lion’s Den, Zumba Resort</a></span></h4>
                    </div>
                    <div class="clearfix"></div>
                    <div class="alltripstatus">
                      <h4>Route : <span class="ststus_color">Mongolia, Russia, Siberia and the Russian Far East, Trans-Siberian Railway, Western Russia</span></h4>
                    </div> -->
                  </div>
                </div>
               
              </div>



              
              <div class="col-md-4 col-sm-4 col-xs-12 classic pull-right">
                <!-- <div class="col-xs-12 list1 nopad">
                 
                 <div class="form-group hldat">
                 </div>
               </div> -->
               <?php

               // debug($tour_data['inclusions_checks']);exit();
               $inclusions_checks = $tour_data['inclusions_checks'];
               $inclusions_checks = json_decode($inclusions_checks);
               if(!empty($inclusions_checks) && valid_array($inclusions_checks)) {
                 ?>
                 <div class="col-xs-12 list2 nopad">
                  <h3>Inclusions</h3>
                  <ul class="menu list-inline">
                   <?php    

                   // debug($inclusions_checks);                exit();
                   foreach ($inclusions_checks as $k => $v) {
                    echo '<li><i class="'.inclusions_class($v).'" aria-hidden="true"></i><span class="menu1">'.$v.'</span></li>';
                  }
                  ?>
                </ul>
              </div>

              <div id="farebreakdown">
               <?php 
             } 


             if(!empty($min_price))
             {
              ?>
              <?php if($tour_price['0']['budget_hotel_price']>0) { ?>
                <div style="display:none;" class="col-xs-12 trs-hotel list3">
                  <h4>Hotel :</h4>
                  <h4 class="num1 pull-left"><?=get_application_display_currency_preference();?> <span class="price"><?=$tour_price['0']['budget_hotel_price']?></span></h4>
                  <!-- <h4 class="prce1 pull-left">starting Price</h4> -->
                </div>
              <?php } if($tour_price['0']['standard_car_price']>0) { ?>
                <div style="display:none;" class="col-xs-12 trs-car list3">
                  <h4>Car :</h4>
                  <h4 class="num1 pull-left"><?=get_application_display_currency_preference();?> <span class="price"><?=$tour_price['0']['standard_car_price']?> </span></h4>
                  <!-- <h4 class="prce1 pull-left">starting Price</h4> -->
                </div>
              <?php } ?>

              
              <div class="col-xs-12 trs-total list3">
               
                 
                <h5>Total Price :</h5>

               <!--  <h4 class="num1 pull-left"><?=$this->currency->get_currency_symbol($default_currency);?> <span class="price"><?php if(!empty($min_price)) echo sprintf('%.2f',$min_price);?></span></h4> -->
               
               <!-- <h4 class="num1 pull-left"><?=$this->currency->get_currency_symbol($default_currency);?> <span class="price"><?php echo isset($min_price)? number_format($min_price, 2):0; ?></span></h4>-->
                
                 <h5 class="num1 pull-left"><?=$this->currency->get_currency_symbol($currency_obj->to_currency);?> <span class="price"><?php echo isset($min_price)? number_format($min_price,0):0; ?></span></h5>
                
                <!-- <h4 class="prce1 pull-left">starting Price</h4> -->
              </div>
              <?php
                if($convenience_fees >0)
                {
              ?>
              <div class="col-xs-12 trs-total list3">
               
                 
                <h5>Taxes :</h5>

               <!--  <h4 class="num1 pull-left"><?=$this->currency->get_currency_symbol($default_currency);?> <span class="price"><?php if(!empty($min_price)) echo sprintf('%.2f',$min_price);?></span></h4> -->
               
               <!-- <h4 class="num1 pull-left"><?=$this->currency->get_currency_symbol($default_currency);?> <span class="price"><?php echo isset($min_price)? number_format($min_price, 2):0; ?></span></h4>-->
                
                 <h5 class="num1 pull-left"><?=$this->currency->get_currency_symbol($currency_obj->to_currency);?> <span class="price"><?php echo isset($convenience_fees)? number_format($convenience_fees,0):0; ?></span></h5>
                
                <!-- <h4 class="prce1 pull-left">starting Price</h4> -->
              </div>
            <?php }
              if($gst_value_conv >0)
              {
            ?>
              <div class="col-xs-12 trs-total list3">
               
                 
                <h5>GST :</h5>

               <!--  <h4 class="num1 pull-left"><?=$this->currency->get_currency_symbol($default_currency);?> <span class="price"><?php if(!empty($min_price)) echo sprintf('%.2f',$min_price);?></span></h4> -->
               
               <!-- <h4 class="num1 pull-left"><?=$this->currency->get_currency_symbol($default_currency);?> <span class="price"><?php echo isset($min_price)? number_format($min_price, 2):0; ?></span></h4>-->
                
                 <h5 class="num1 pull-left"><?=$this->currency->get_currency_symbol($currency_obj->to_currency);?> <span class="price"><?php echo isset($gst_value_conv)? number_format($gst_value_conv, 0):0; ?></span></h5>
                
                <!-- <h4 class="prce1 pull-left">starting Price</h4> -->
              </div>
            <?php } ?>
              <div class="col-xs-12 trs-total list3">
               
                 
                <h4>Grand Total :</h4>

               <!--  <h4 class="num1 pull-left"><?=$this->currency->get_currency_symbol($default_currency);?> <span class="price"><?php if(!empty($min_price)) echo sprintf('%.2f',$min_price);?></span></h4> -->
               
               <!-- <h4 class="num1 pull-left"><?=$this->currency->get_currency_symbol($default_currency);?> <span class="price"><?php echo isset($min_price)? number_format($min_price, 2):0; ?></span></h4>-->
                
                 <h4 class="num1 pull-left str"><?=$this->currency->get_currency_symbol($currency_obj->to_currency);?> <span class="price num22"><?php echo isset($grand_total)? number_format($grand_total, 0):0; ?></span></h4>
                
                <!-- <h4 class="prce1 pull-left">starting Price</h4> -->
              </div>
              <?php 
            }
            ?>
          </div>

                

          <div class="col-xs-12 list4 nopad">
           <?php  $tour_id = $tour_data['id'];?>
           <form action="<?=base_url()?>index.php/tours/pre_booking/<?=$tour_id?>" method="POST" name="checkout-apartment" id="pre_booking_form" autocomplete="off"  >                      
            <input class="type_hotel" name="type_hotel" type="hidden" value="" />                        
            <input class="type_car" name="type_car" type="hidden" value="" />    
              <input class="type_car" name="nationality" type="hidden" value="<?=$_GET['radio']?>" />  
           <!--  <input class="package_origional_price" name="package_origional_price" type="hidden" value="<?=($tour_price[0]['adult_airliner_price'])?>" />    -->  
            <input class="package_origional_price" name="package_origional_price" type="hidden" value="<?=($tour_price[0]['convt_adult_airliner_price'])?>" />                     
            <div class="credit clearfix">
              
             <div class="credit_item col-md-12 nopad">
              <div class="col-md-6 col-xs-8 nopad">
               <p>No of Adults (12+ YRS)</p>
             </div>
             <!-- <div class="col-md-6 nopad">
               <select name="no_adults" class="no_adults holyday_selct1" id="no_adults" onchange="roomcountcalculation()">
                <option>1</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
                <option>5</option>
                <option>6</option>
                <option>7</option>
                <option>8</option>
                <option>9</option>
                <option>10</option>
              </select>
            </div> -->


         <!-- <div class="col-md-6 col-xs-4 nopad">
           <div class="input-group countmore pax-count-wrapper adult_count_div">
              <span class="input-group-btn">
              <button type="button" class="btn btn-default btn-number" data-type="minus" data-field="adult" onclick="manage_adult_count('adult','minus')"> 
              <span class="glyphicon glyphicon-minus"></span>
              </button></span>
              <input type="text" id="no_adults" name="no_adults" class="form-control input-number centertext valid_class pax_count_value" value="1" min="1" max="9" readonly="" onchange="roomcountcalculation()"><span class="input-group-btn">
                <button type="button" class="btn btn-default btn-number" onclick="manage_adult_count('adult','plus')" data-type="plus" data-field="adult"> <span class="glyphicon glyphicon-plus"></span> </button></span> 
           </div>
        </div>-->
        
         <div class="col-md-6 col-xs-4 nopad">
                 <div class="input-group countmore pax-count-wrapper adult_count_div">
                    <span class="input-group-btn">
                    <button type="button" class="btn btn-default btn-number" data-type="minus" data-field="adult" > 
                    <span class="glyphicon glyphicon-minus"></span>
                    </button></span>
                    <input type="text" id="no_adults" name="no_adults" class="form-control input-number centertext valid_class pax_count_value" value="1" min="1" max="9" readonly="" onchange="roomcountcalculation()"><span class="input-group-btn">
                      <button type="button" class="btn btn-default btn-number"  data-type="plus" data-field="adult"> <span class="glyphicon glyphicon-plus"></span> </button></span> 
                 </div>
              </div>


          </div>
          <div class="credit_item col-md-12 col-xs-12 nopad">
            <div class="col-md-6 col-xs-8 nopad">
             <p>No of children (2-11 YRS)</p>
           </div>
          <!--  <div class="col-md-6 nopad">
             <select name="no_child" class="no_child holyday_selct1" id="no_child" onchange="roomcountcalculation()">
              <option>0</option>
              <option>1</option>
              <option>2</option>
              <option>3</option>
              <option>4</option>
            </select>
          </div> -->

<!--
          <div class="col-md-6 col-xs-4 nopad">
           <div class="input-group countmore pax-count-wrapper adult_count_div">
              <span class="input-group-btn">
              <button type="button" class="btn btn-default btn-number" data-type="minus" data-field="adult" onclick="manage_child_count('child','minus')"> 
              <span class="glyphicon glyphicon-minus"></span>
              </button></span>
              <input type="text" id="no_child" name="no_child" class="form-control input-number centertext valid_class pax_count_value" value="0" min="1" max="9" readonly="" onchange="roomcountcalculation()"><span class="input-group-btn">
                <button type="button" class="btn btn-default btn-number" onclick="manage_child_count('child','plus')" data-type="plus" data-field="adult"> <span class="glyphicon glyphicon-plus"></span> </button></span> 
           </div>
        </div>-->
           <div class="col-md-6 col-xs-4 nopad">
           <div class="input-group countmore pax-count-wrapper adult_count_div">
              <span class="input-group-btn">
              <button type="button" class="btn btn-default btn-number" data-type="minus" data-field="child" > 
              <span class="glyphicon glyphicon-minus"></span>
              </button></span>
              <input type="text" id="no_child" name="no_child" class="form-control input-number centertext valid_class pax_count_value" value="0" min="1" max="9" readonly="" onchange="roomcountcalculation()"><span class="input-group-btn">
                <button type="button" class="btn btn-default btn-number" data-type="plus" data-field="child"> <span class="glyphicon glyphicon-plus"></span> </button></span> 
           </div>
        </div>


        </div>
        <div class="credit_item col-md-12 col-xs-12 nopad">
            <div class="col-md-6 col-xs-8 nopad">
             <p>No of Infant (0-2 YRS)</p>
           </div>
          <!--  <div class="col-md-6 nopad">
             <select name="no_child" class="no_child holyday_selct1" id="no_child" onchange="roomcountcalculation()">
              <option>0</option>
              <option>1</option>
              <option>2</option>
              <option>3</option>
              <option>4</option>
            </select>
          </div> -->

<!--
          <div class="col-md-6 col-xs-4 nopad">
           <div class="input-group countmore pax-count-wrapper adult_count_div">
              <span class="input-group-btn">
              <button type="button" class="btn btn-default btn-number" data-type="minus" data-field="adult" onclick="manage_child_count('child','minus')"> 
              <span class="glyphicon glyphicon-minus"></span>
              </button></span>
              <input type="text" id="no_child" name="no_child" class="form-control input-number centertext valid_class pax_count_value" value="0" min="1" max="9" readonly="" onchange="roomcountcalculation()"><span class="input-group-btn">
                <button type="button" class="btn btn-default btn-number" onclick="manage_child_count('child','plus')" data-type="plus" data-field="adult"> <span class="glyphicon glyphicon-plus"></span> </button></span> 
           </div>
        </div>-->
           <div class="col-md-6 col-xs-4 nopad">
           <div class="input-group countmore pax-count-wrapper adult_count_div">
              <span class="input-group-btn">
              <button type="button" class="btn btn-default btn-number" data-type="minus" data-field="infant" > 
              <span class="glyphicon glyphicon-minus"></span>
              </button></span>
              <input type="text" id="no_infant" name="no_infant" class="form-control input-number centertext valid_class pax_count_value" value="0" min="1" max="9" readonly="" onchange="roomcountcalculation()"><span class="input-group-btn">
                <button type="button" class="btn btn-default btn-number" data-type="plus" data-field="infant"> <span class="glyphicon glyphicon-plus"></span> </button></span> 
           </div>
        </div>


        </div>
        <?php if($tour_price['0']['standard_car_price']>0 || ($tour_price['0']['deluxe_car_price']>0) || ($tour_price['0']['suv_car_price']>0) || ($tour_price['0']['temp_traveller_price']>0) || ($tour_price['0']['bus_price']>0)){?>
         <div class="credit_item col-md-12 nopad">
          <div class="col-md-6 col-xs-6 nopad">
           <p>No of Transfer: </p>
           
         </div>
         <div class="col-md-6 nopad">
           <div class="plcetogo datemark n-transfer sidebord">
            <input  type="number" min="1"  oninput="validity.valid||(value='');" class="form-control b-r-0 normalinput" data-date="true" name="no_of_transfer" id="no_of_transfer"  placeholder="Number of transfer" onkeyup="roomcountcalculation()" value="1" required />
          </div>
        </div>
      </div>
    <?php } ?> 
    <?php if($tour_price['0']['budget_hotel_price']>0 || ($tour_price['0']['standard_hotel_price']>0) || ($tour_price['0']['deluxe_hotel_price']>0) || ($tour_price['0']['deluxe_hotel_price']>0) || ($tour_price['0']['4_star_hotel_price']>0) || ($tour_price['0']['twin_share_hotel_price']>0) ){ ?>
     <div class="credit_item col-md-12 nopad">
      <div class="col-md-6 nopad">
       <p>No of Room: </p>
       
     </div>
     <div class="col-md-6 nopad">
       <div class="plcetogo datemark rooms-no sidebord">
        <input  type="number" oninput="validity.valid||(value='');" min="1" class="form-control b-r-0 normalinput" data-date="true" name="no_of_room" id="no_of_room" onkeyup="dynamicPriceBreakdown()" placeholder="Number of Room"  value="1" required />
      </div>
    </div>
  </div>
  <div class="credit_item col-md-12 nopad">
    <div class="col-md-6 nopad">
     <p>No of Extra Bed: </p>
     
   </div>
   <div class="col-md-6 nopad">
     <div class="plcetogo datemark xtra-bed sidebord">
      <input  type="text" class="form-control b-r-0 normalinput" data-date="true" name="no_of_extrabed" id="no_of_extrabed" readonly="" placeholder="Enter the number of Room" value="0" required onchange="hidebook()" />
    </div>
  </div>
</div>
<?php } ?>
<div class="credit_item col-md-12 nopad">
  <div class="col-md-6 col-xs-6 nopad">
   <p>Date of Travel: </p>
   
 </div>
 <div class="col-md-6 col-xs-6 nopad">
   <div class="plcetogo datemark sidebord dtaln">
    <!-- <input  type="text" required="" class="form-control b-r-0 normalinput invalid-ip" readonly name="date_of_travel" id="date_of_travel"  placeholder="Date of Travel" value="" /> -->

   <!--  <input  type="numeric" class="form-control b-r-0 normalinput numeric" name="date_of_travel" id="date_of_travel"   placeholder="Date of Travel" required   min="1" max="100"   />-->
     <input  type="text" class="form-control  " name="date_of_travel" id="date_of_travel"   placeholder="Date of Travel"    min="1" max="100"  required="" onkeypress="return false;" />
  </div>
</div>
</div>
<div class="credit_item col-md-12 nopad">
          <span id="err" style="color: red;"></span>
        </div>

 <!--  <div class="credit_item col-md-12 nopad">
    <div class="col-md-6 nopad">
     <p>Sim Card Quantity: </p>
     
   </div>
   <div class="col-md-6 nopad">
       <div class="plcetogo sidebord">
      
        <?php

          if($tour_data['sim_type']=="Free")
          {
            echo ' <input  type="numeric" class="form-control b-r-0 normalinput numeric"   name="sim_quantity" id="sim_quantity" value="1" readonly placeholder="Sim Card Quantity" required  min="1" max="100"  />';        
          }else
          {
            echo '
               <input  type="numeric" class="form-control b-r-0 normalinput numeric"   name="sim_quantity" id="sim_quantity"  placeholder="Sim Card Quantity" required   min="1" max="100"   />
            ';
          }
         ?>

       
      </div>
  </div>
</div> -->


<!--  <div class="credit_item col-md-12 nopad">
    <div class="col-md-6 nopad">
     <p>Sim Price/Per Sim (<?php echo $this->currency->get_currency_symbol($default_currency);?>): </p>
     
   </div>
   <div class="col-md-6 nopad">
       <div class="plcetogo sidebord">
        
        <?php

          if($tour_data['sim_type']=="Free")
          {
            echo ' <input  type="numeric" class="form-control b-r-0 normalinput numeric" data-date="true"  name="sim_price" id="sim_price" value="0" readonly placeholder="Sim Card Quantity" required  min="1" max="100" />';        
          }else
          {

            $tour_data['sim_price'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $tour_data['sim_price'] ) );
           

            echo '
               <input  type="numeric" class="form-control b-r-0 normalinput numeric" data-date="true"  name="sim_price" id="sim_price"  placeholder="Sim Card Price" required  value="'.$tour_data['sim_price'].'" min="1" max="100" readonly />
            ';
          }
         ?>

       
      </div>
  </div>
</div> -->


<div class="col-md-12 ">
<!--  <button type="button" data-toggle="modal" data-target="#myModal" id="enquiry_pop_button" class="btn btn-default booknowhtl hide" >Send Enquiry</button> -->
</div>
</div>
<!-- <div class="text-center hidbook" id="or">OR</div> -->
   <?php 
   //debug($tour_data);exit();
  $start_date=$tour_data['start_date'];
  $expire_date=$tour_data['expire_date'];

  ?>
<input type="hidden" id="strt" value="<?=$start_date?>" name="strt">
  <input type="hidden" id="expr" value="<?=$expire_date?>" name="ex">
<input type="submit" class="booknow hidbook" id="check" value="Book">

</form>
<div class="col-xs-12 nopad">
  <input type="submit" class="booknowhtl" id="sendquery" value="Send Enquiry" />
</div>
<!--  <button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal" id="enquiry_pop_button">Send Inquiry</button> -->
</div>


</div>
</div>


<div class="modal fade" id="myModal" role="dialog">
 <div class="modal-dialog">
   <div class="modal-content">
     <div class="modal-header" style="background: #16acdf !important;
     border: 1px solid #1397c4;
     color: #ffffff !important;
     line-height: 20px;
     font-size: 15px;
     text-align: center;">

     <button type="button" class="close" data-dismiss="modal">&times;</button>
     <h4 class="modal-title" style="color: #fff;">Tours Enquiry</h4>

   </div>
   <div id="alertp" class="hide text-success">
    <div class="confirm-span" style="text-align: center;">Your request has been submitted successfully!</div></div>

   <form id="tour_enquiry_form" action="javascript:void(0);">
     <div class="modal-body">
       <h3 class="gateway" style="text-align: center;"><?=$tour_data['package_name']; ?></h3>
       <div class="form-group modl">
         <label class="control-label col-md-4 col-xs-4" for="user_name">Title : <strong class="text-danger"></strong></label>
         <div class="col-md-7 col-xs-8">
           <i style="visibility: hidden;" class="fa fa-user" aria-hidden="true"></i>
           <select class="form-control mntxt" name="title" id="tour_title" aria-required="true" required="required">
             <?=generate_options(get_enum_list('title'),(array)$this->entity_title)?>
           </select>
         </div>
       </div>


       <div class="form-group modl"><label class="control-label col-md-4 col-xs-4" for="user_name">First Name : <strong class="text-danger"></strong></label><div class="col-md-7 col-xs-8"><i style="visibility: hidden;" class="fa fa-user" aria-hidden="true"></i><input type="text" class="form-control mntxt alpha_space" name="ename" id="tour_ename" placeholder="" aria-required="true" required="required" value="<?=$this->entity_first_name?>" style="text-transform: capitalize !important;"></div></div>
       <div class="form-group modl"><label class="control-label col-md-4 col-xs-4" for="user_name">Last Name : <strong class="text-danger"></strong></label><div class="col-md-7 col-xs-8"><i style="visibility: hidden;" class="fa fa-user" aria-hidden="true"></i><input type="text" class="form-control mntxt alpha_space" name="elname" maxlength="50" id="tour_elname" placeholder="" aria-required="true" required="required" value="<?=$this->entity_last_name?>" style="text-transform: capitalize !important;"></div></div>

       <div class="form-group modl contct">
         <label class="control-label col-md-4 col-xs-4" for="user_mobile"> Country Code  :</label>
         <div class="col-md-7 col-xs-8">
           <?php
           if(isset($country_code) && valid_array($country_code))
           {
             ?>
             <div class="selectedwrap"><select name="pn_country_code" id="tour_pn_country_code" required="" aria-required="true" class="mySelectBoxClass flyinputsnor">
                                                <!--  <option value="+1_Canada +1">Canada +1</option>
                                                 <option value="+1_United States +1">United States +1</option> -->
                                                 <?php foreach($country_code as $c__k => $__country) {
                                                   ?><option value="<?=trim(@$c__k."_".$__country)?>"
                                                    <?php 
                                                    if($c__k == $this->entity_phone_code) {echo 'selected';}

                                                    else  if($c__k == '+60') {echo 'selected';} ?>>
                                                    <?=@$__country?></option><?php
                                                  }?>
                                                </select></div>
                                                <?php
                                              }
                                              ?>
                                            </div>
                                          </div>
                                          <div class="form-group modl contct">
                                           <label class="control-label col-md-4 col-xs-4" for="user_mobile"> Phone Number  :</label>
                                           <div class="col-md-7 col-xs-8">
                                             <i style="font-size: 25px;" class="fas fa-mobile-alt" aria-hidden="true"></i>
                                             <input type="text" class="form-control mntxt mobile _numeric_only" name="emobile" id="tour_emobile" minlength="10" maxlength="15" placeholder="" value="<?php if($this->entity_phone != 0){echo $this->entity_phone;}?>">
                                           </div>
                                         </div>
                                         <div class="form-group modl">
                                           <label class="control-label col-md-4 col-xs-4" for="user_email"> Departure Date : <strong class="text-danger"></strong>
                                           </label>
                                           <!--AB removed class form tour_departure_date eml n_psngr -->
                                           <div class="col-md-7 col-xs-8">
                                             <i class="fal fa-calendar-alt" aria-hidden="true" style="right: 20px; left: inherit !important;"></i>
                                             <input type="text" class="form-control mntxt" id="tour_departure_date" name="departure_date" placeholder="" aria-required="true" required="required" readonly>
                                           </div>


                                         </div>
                                         <div class="form-group modl"><label class="control-label col-md-4 col-xs-4" for="user_name">Number of passengers : <strong class="text-danger"></strong></label><div class="col-md-7 col-xs-8"><i class="fa fa-user" aria-hidden="true"></i><input type="text" class="form-control mntxt _numeric_only" maxlength="3" name="number_of_passengers" id="tour_number_of_passengers" placeholder="" aria-required="true" required="required"></div></div>
                                         <div class="form-group modl"><label class="control-label col-md-4 col-xs-4" for="user_name">Duration : <strong class="text-danger"></strong></label><div class="col-md-7 col-xs-8"><i class="fa fa-clock" aria-hidden="true"></i><input type="text" class="form-control mntxt" name="durations" id="tour_durations" value="<?php echo ($tour_data['duration']+1)." Days ".($tour_data['duration'])." Nights"; ?>" placeholder=""></div></div>

                                         <div class="form-group modl"><label class="control-label col-md-4 col-xs-4" for="user_email"> Email : <strong class="text-danger"></strong></label><div class="col-md-7 col-xs-8 eml"><i class="fa fa-envelope" aria-hidden="true"></i><input type="email" class="form-control mntxt" name="eemail" id="tour_eemail" placeholder="" aria-required="true" required="required" value="<?=$this->entity_email?>"></div></div>
                                         <div class="form-group modl"><label class="control-label col-md-4 col-xs-4" for="comment"> Details  : <strong class="text-danger"></strong></label><div class="col-md-7 col-xs-8"><i class="fa fa-pencil-square-o" aria-hidden="true"></i><textarea rows="3" cols="40" class="form-control mntxt" name="ecomment" id="tour_ecomment" placeholder="" ></textarea></div></div>
                                       </div>
                                       <div class="modal-footer">
                                         <input type="hidden" id="tour_id1" value="<?=$tour_data['id']?>">
                                         <input type="hidden" id="tours_itinerary_id1" value="<?=$tours_itinerary['id']?>">
                                         <input type="submit" class="btn btn-default" id="send_enquiry_button_tour" value="Send" />
                                       </div>
                                     </form>
                                   </div>
                                 </div>
                               </div>
                               <br>
                               <?php 

                               $tours_data = json_encode($tour_data);
                               $tourss_itinerary=json_encode($tours_itinerary);
                               $tourss_itinerary_dw=json_encode($tours_itinerary_dw);
                               $tours_price=json_encode($tour_price);
                               $tours_data = urlencode($tours_data);
                               $tourss_itinerary = urlencode($tourss_itinerary);
                               $tourss_itinerary_dw = urlencode($tourss_itinerary_dw);
                               $tours_price = urlencode($tours_price);

                               ?>
                               <br>
                               <form method="post" action="<?=base_url().'index.php/voucher/holidays_pdf'?>" target="_blank">
                                <input type="hidden" name="tour_data" value="<?php echo $tours_data?>">
                                <input type="hidden" name="tours_itinerary" value="<?php echo $tourss_itinerary?>">
                                <input type="hidden" name="tours_itinerary_dw" value="<?php echo $tourss_itinerary_dw?>">
                                <input type="hidden" name="tour_price" value="<?php echo $tours_price?>">
                                <input type="hidden" value="Export PDF" class="btn btn-warning pull-right" style='margin-top:20px;'>
                              </form>
                              <!-- <a href="<?=base_url().'index.php/voucher/holidays_pdf'?>?tour_data=<?php echo $tours_data?>&tours_itinerary=<?php echo $tourss_itinerary?>&tours_itinerary_dw=<?php echo $tourss_itinerary_dw?>&tour_price=<?php echo $tours_price?>" style='margin-top:20px;' target="_blank" class="btn btn-warning pull-right"><i class="fa fa-file"></i> Export PDF</a> --> 

                             <!---new overview section requirement start-->

                            <!-- <div class="fulldetab tour-dtl mart20">
                                <div class="clearfix visible-sm-block visible-xs-block"></div>
                                  <div class="col-md-12 col-xs-12 nopad fulatnine">
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a data-toggle="tab" href="#overview">Overview</a></li>
                                            <li><a data-toggle="tab" href="#itinerary">Itinerary</a></li>
                                            <li><a data-toggle="tab" href="#inclusions">Inclusions &amp; Exclusions</a></li>
                                            <li><a data-toggle="tab" href="#terms">Terms &amp; Conditions</a></li>
                                            <li><a data-toggle="tab" href="#optionals">Optional Tours</a></li>
                                        </ul>

                                       <div class="tab-content">
                                          <div id="hoverview" class="tab-pane fade in active">
                                               <?= html_entity_decode($tour_data['highlights']);?>
                                          </div>
                                          <div id="itinerary" class="tab-pane fade">
                                             <div class="col-xs-12 trtab nopad">
                                                <div class="col-xs-3 col-md-2 tr_lft">
                                                <div class="thumbs2" id="thumbs2">

                                                <ul class="nav nav-tabs ">
                                                <?php
                                                if(valid_array($tours_itinerary_dw))
                                                foreach ($tours_itinerary_dw as $k => $v) {
                                                if($k==0) { $active = 'active'; }
                                                else { $active = ''; }
                                                echo '<li class="'.$active.' ">
                                                <a href="#day'.$k.'" data-toggle="tab" class="tabs_left"><span class="fa fa-calendar-o"></span> Day '.($k+1).'</a></li>';
                                                } 
                                                ?>
                                                </ul>

                                                </div>

                                                </div>
                                                <div class="col-xs-9  col-md-10 tr_rgt">
                                                <div class="tab-content">
                                                <?php


                                                foreach ($tours_itinerary_dw as $k => $v) {
                                                if($k==0) { 
                                                $active = 'active'; 
                                                }
                                                else 
                                                { 
                                                $active = ''; 
                                                }

                                                $visited_city = $v['visited_city'];
                                                $visited_city = json_decode($visited_city,1);
                                                $accomodation = $v['accomodation'];
                                                $accomodation = json_decode($accomodation,1);
                                                foreach ($accomodation as $acc_k => $acc_v) {
                                                if($acc_k==0) { $accomodation_str = $acc_v; }
                                                else { $accomodation_str = $accomodation_str.', '.$acc_v; }                         
                                                }

                                                $plus_days    = '+'.($k).' days';
                                                $display_date = date('Y-m-d', strtotime($plus_days, strtotime($v['dep_date'])));
                                                $display_date = changeDateFormat($display_date);
                                                ?>
                                                <div class="tab-pane <?=$active?>" id="day<?=$k?>">
                                                <h3><?//=$visited_city_str?></h3>
                                                <div class="clearfix"></div>
                                                <div class="hldycont col-xs-12 nopad">
                                                <div class="hlrgt">
                                                <h3>Day <?=($k+1)?> : <?=$v['program_title']?></h3>
                                                </div>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="hldydet col-xs-12 nopad">
                                                <?php echo html_entity_decode($v['program_des']);
                                                if(html_entity_decode($v['hotel_name'])!='') {
                                                ?>
                                                <div class="htl_inf">
                                                <i class="fa fa-bed" aria-hidden="true"></i>
                                                <strong>Hotel Name : </strong><?=html_entity_decode($v['hotel_name'])?>
                                                <?php 
                                                if ($v['rating']) {
                                                ?>
                                                <span class="star_detail">
                                                <span class="stra_hotel" data-star="<?=$v['rating']?>"> 
                                                <span class="fa fa-star"></span> 
                                                <span class="fa fa-star"></span> 
                                                <span class="fa fa-star"></span> 
                                                <span class="fa fa-star"></span> 
                                                <span class="fa fa-star"></span> 
                                                </span>
                                                </span>
                                                <?php 
                                                }
                                                ?>
                                                </div>
                                                <?php
                                                }

                                                if(!empty($accomodation)) {    
                                                echo '<div class="htl_inf"><i class="fal fa-utensils" aria-hidden="true"></i><strong>Meals : </strong> '.$accomodation_str.'</div>';
                                                }
                                                echo '</div></div>';
                                                } 


                                                ?>    
                                                </div>
                                                </div>
                                                </div>
                                                </div>
                                                </div>
                                          </div>
                                          <div id="inclusions" class="tab-pane fade">
                                            <?php 
                                                if ($tour_data['inclusions']!='') 
                                                {
                                                 ?>
                                                 <div class="linebrk"></div>
                                                 <div class="col-md-12 nopad">
                                                  <div class="col-md-12">
                                                   <h3 class="hedft">Price Includes:</h3>
                                                   <ul class="checklist checklistxl">
                                                    <li><?= html_entity_decode($tour_data['inclusions']);?></li>
                                                  </ul>
                                                </div>
                                              </div>
                                              <?php 
                                            }
                                            if ($tour_data['exclusions']!='') 
                                            {
                                             ?>
                                             <div class="linebrk"></div>
                                             <div class="col-md-12 nopad">
                                              <div class="col-md-12">
                                               <h3 class="hedft">Price Excludes:</h3>
                                               <ul class="checklist checklistxl">
                                                <li><?= html_entity_decode($tour_data['exclusions']);?></li>
                                              </ul>
                                            </div>
                                          </div>
                                          <?php 
                                        }
                                        
                                        ?>
                                          </div>
                                          <div id="terms" class="tab-pane fade">
                                            <?php 
                                              if ($tour_data['terms']!='') 
                                              {
                                               ?>
                                               <div class="linebrk"></div>
                                               <div class="col-md-12 nopad">
                                                <div class="col-md-12">
                                                 <h3 class="hedft">Terms &amp; Conditions:</h3>
                                                 <ul class="checklist checklistxl">
                                                  <li><?= html_entity_decode($tour_data['terms']);?></li>
                                                </ul>
                                              </div>
                                            </div>
                                            <?php 
                                          }
                                          if ($tour_data['canc_policy']!='') 
                                          {
                                           ?>
                                           <div class="linebrk"></div>
                                           <div class="col-md-12 nopad">
                                            <div class="col-md-12">
                                             <h3 class="hedft">Cancellation Policy:</h3>
                                             <ul class="checklist checklistxl">
                                              <li><?= html_entity_decode($tour_data['canc_policy']);?></li>
                                            </ul>
                                          </div>
                                        </div>
                                        <?php 
                                      }
                                      ?>
                                          </div>
                                          <div id="optionals" class="tab-pane fade">
                                            <?= html_entity_decode($tour_data['optional_tours']);?>
                                          </div>
                                       </div>

                                  </div>
                            </div> -->















                            <!---new overview section requirement end-->



                              <div class="fulldetab mart20 daytabb">
                                <div class="clearfix visible-sm-block visible-xs-block"></div>
                                <div class="col-md-12 col-xs-12 nopad fulatnine">
                                 <div class="detailtab">
                                   <div class="holidays_tab">
                                    <ul class="nav nav-tabs trul">
                                     <li class=""><a href="#tab_Overview" data-toggle="tab"><!-- <span><img src="<?=$GLOBALS['CI']->template->template_images('overview.png'); ?>"></span> --><strong>Overview</strong></a></li>

                                     <li class="active trooms"><a href="#tab_Itinerary" data-toggle="tab"><!-- <span><img src="<?=$GLOBALS['CI']->template->template_images('itinerary.png'); ?>"></span> --><strong> Itinerary</strong></a></li>

                                     
                                     <li class="tfacility"><a href="#tab_inclusions" data-toggle="tab"><!-- <span><img src="<?=$GLOBALS['CI']->template->template_images('inclusions.png'); ?>"></span> --><strong>Inclusions &amp; Exclusions</strong></a></li>
                                     
                                     <li class="tfacility"><a href="#tab_Terms" data-toggle="tab"><!-- <span class="fal fa-file-alt"></span> --><strong>Terms &amp; Conditions</strong></a></li>

                                     <li class="tfacility"><a href="#optionals" data-toggle="tab"><!-- <span><img src="<?=$GLOBALS['CI']->template->template_images('inclusions.png'); ?>"></span> --><strong>Optional Tours</strong></a></li>

                                    <!--  <li class="tfacility"><a href="#review" data-toggle="tab"><span class="fal fa-comments"></span><strong>Post a Review</strong></a></li> -->

                                    <!--  <li class="tfacility"><a href="#price_calendar" data-toggle="tab"><span class="fal fa-dollar-sign"></span><strong>Price Calendar</strong></a></li> -->

                                   </ul>
                                 </div>

                                 <div class="tab-content5">
                                   <div class="tab-pane" id="tab_Overview">
                                    <div class="innertabsxl">
                                     <div class="comenhtlsum">
                                      <ul class="checklist checklistxl">
                                        <?= html_entity_decode($tour_data['highlights']);?>
                                       </ul>
                                    </div>
                                  </div>
                                </div>

                                <div class="tab-pane" id="trip_notes">
                                  <div class="innertabsxl">
                                   <div class="comenhtlsum">
                                    <ul class="checklist checklistxl">
                                    <?= html_entity_decode($tour_data['trip_notes']);?>
                                   </ul>
                                  </div>
                                </div>
                              </div>
                              <div class="tab-pane active" id="tab_Itinerary">
                                <div class="innertabsxl">
                                 <div class="tr_row">
                                  <div class="col-xs-12 trtab nopad">
                                   <div class="col-xs-3 col-md-2 tr_lft">
                                    

                                   <div class="thumbs2" id="thumbs2">
                                      
                                      <ul class="nav nav-tabs sideday ">
                                       <?php
                                       if(valid_array($tours_itinerary_dw))
                                        foreach ($tours_itinerary_dw as $k => $v) {
                                         if($k==0) { $active = 'active'; }
                                         else { $active = ''; }
                                         echo '<li class="'.$active.' ">
                                         <a href="#day'.$k.'" data-toggle="tab" class="tabs_left"><span class="fa fa-calendar-o"></span> Day '.($k+1).'</a></li>';
                                       } 
                                       ?>
                                     </ul>
                                     
                                   </div> 

                                 </div>
                                 <div class="col-xs-9  col-md-10 tr_rgt">
                                   <div class="tab-content">
                                    <?php


                                    foreach ($tours_itinerary_dw as $k => $v) {
                                     if($k==0) { 
                                      $active = 'active'; 
                                    }
                                    else 
                                    { 
                                      $active = ''; 
                                    }

                                    $visited_city = $v['visited_city'];
                                    $visited_city = json_decode($visited_city,1);
                                    $accomodation = $v['accomodation'];
                                    $accomodation = json_decode($accomodation,1);
                                    foreach ($accomodation as $acc_k => $acc_v) {
                                      if($acc_k==0) { $accomodation_str = $acc_v; }
                                      else { $accomodation_str = $accomodation_str.', '.$acc_v; }                         
                                    }

                                    $plus_days    = '+'.($k).' days';
                                    $display_date = date('Y-m-d', strtotime($plus_days, strtotime($v['dep_date'])));
                                    $display_date = changeDateFormat($display_date);
                                    ?>
                                    <div class="tab-pane <?=$active?>" id="day<?=$k?>">
                                      <h3><?//=$visited_city_str?></h3>
                                      <div class="clearfix"></div>
                                      <div class="hldycont col-xs-12 nopad">
                                       <div class="hlrgt">
                                        <h3>Day <?=($k+1)?> : <?=$v['program_title']?></h3>
                                      </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="hldydet col-xs-12 nopad">
                                      <ul class="checklist checklistxl nopad fnt">
                                        
                                     <?php echo html_entity_decode($v['program_des']);
                                     if(html_entity_decode($v['hotel_name'])!='') {
                                      ?>
                                      <div class="htl_inf">
                                       <i class="fa fa-bed" aria-hidden="true"></i>
                                       <strong>Hotel Name : </strong><?=html_entity_decode($v['hotel_name'])?>
                                       <?php 
                                       if ($v['rating']) {
                                        ?>
                                        <span class="star_detail">
                                         <span class="stra_hotel" data-star="<?=$v['rating']?>"> 
                                          <span class="fa fa-star"></span> 
                                          <span class="fa fa-star"></span> 
                                          <span class="fa fa-star"></span> 
                                          <span class="fa fa-star"></span> 
                                          <span class="fa fa-star"></span> 
                                        </span>
                                      </span>
                                      <?php 
                                    }
                                    ?>
                                  </div>
                                  <?php
                                }

                                if(!empty($accomodation)) {    
                                  echo '<div class="htl_inf"><i class="fal fa-utensils" aria-hidden="true"></i><strong>Meals : </strong> '.$accomodation_str.'</div>';
                                }
                                echo '</div></div>';
                              } 


                              ?>  
                            
                              </ul>  
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane" id="tab_inclusions">
                   <div class="innertabs">          
                    <?php 
                    if ($tour_data['inclusions']!='') 
                    {
                     ?>
                     <div class="linebrk"></div>
                     <div class="col-md-12 nopad">
                      <div class="col-md-12">
                       <h3 class="hedft">Price Includes:</h3>
                       <ul class="checklist checklistxl">
                        <?= html_entity_decode($tour_data['inclusions']);?>
                      </ul>
                    </div>
                  </div>
                  <?php 
                }
                if ($tour_data['exclusions']!='') 
                {
                 ?>
                 <div class="linebrk"></div>
                 <div class="col-md-12 nopad">
                  <div class="col-md-12">
                   <h3 class="hedft">Price Excludes:</h3>
                   <ul class="checklist checklistxl">
                   <?= html_entity_decode($tour_data['exclusions']);?>
                  </ul>
                </div>
              </div>
              <?php 
            }
            
            ?>
          </div>
        </div>  

        <div class="tab-pane" id="tab_Terms">
         <div class="innertabs">          
          <?php 
          if ($tour_data['terms']!='') 
          {
           ?>
           <div class="linebrk"></div>
           <div class="col-md-12 nopad">
            <div class="col-md-12">
             <h3 class="hedft">Terms &amp; Conditions:</h3>
             <ul class="checklist checklistxl">
              <?= html_entity_decode($tour_data['terms']);?>
            </ul>
          </div>
        </div>
        <?php 
      }
      if ($tour_data['canc_policy']!='') 
      {
       ?>
       <div class="linebrk"></div>
       <div class="col-md-12 nopad">
        <div class="col-md-12">
         <h3 class="hedft">Cancellation Policy:</h3>
         <ul class="checklist checklistxl">
          <?= html_entity_decode($tour_data['canc_policy']);?>
        </ul>
      </div>
    </div>
    <?php 
  }
  ?>
</div>
</div>
<div class="tab-pane" id="optionals">
 <div class="innertabs">          
   <h3 class="hedft">Added Optionals Tours</h3>
   <ul class="checklist checklistxl">
    <?= html_entity_decode($tour_data['optional_tours']);?>
  </ul>
</div>
</div>
<div class="tab-pane" id="review">

  <div class="innertabs">
    <div class="ratingusr">
      <div class="mlgnformin">
        <?php
        $post_review_data['required']['module'] = 'Holiday';
        $post_review_data['required']['tour_id'] = $tour_data['id'];
        $post_review_data['required']['tours_itinerary_id'] = $tours_itinerary['id'];
        $rev_data['default_country_code'] = '+61';
       echo $this->template->isolated_view('hotel/post_review',$rev_data);

        // debug($tours_itinerary_dw);exit();
        ?>
      </div>
    </div>
  </div>
</div>
<div class="tab-pane" id="price_calendar">
  <div id='calendar'></div>
</div>

<div class="tab-pane" id="tab_dates">
 <div class="innertabs">
  <div class="ratingusr">                   
   <div class="table-responsive col-xs-12 col-md-10 col-md-offset-1">      
    <?php
    $tp_arr = array();
    $tp_occupancy = array();
    foreach($tour_price_changed as $tp) {
     $tp_arr[$tp['from_date'].'|'.$tp['to_date']][$tp['occupancy_name']] = $tp;
     $tp_occupancy[$tp['occ']] = $tp['occupancy_name'];
   }
   ?>    
   <table class="table table-bordered hide" id="departure_date_table">
     <thead>  
      <?php 
      $currencyp = ($this->session->userdata('currency') != "") ? $this->session->userdata('currency') : "CAD"; 
      ?>    
      <tr>
       <th>From Date</th>
       <th>To Date</th>
       <?php foreach ($tp_occupancy as $key => $value): ?>
        <th><?=$value;?> (<?=get_application_display_currency_preference(); ?>)</th>
      <?php endforeach ?>
    </tr>
  </thead>
  <tbody id="departure_date_list">
    <?php
    $temp_count = 0;
    foreach ($tp_arr as $key => $occ_data):
     list($from_date, $end_date) = explode('|', $key);
     if($end_date > date('Y-m-d'))
     {
      $temp_count++;
      ?>
      <tr>
        <td><?=$from_date;?></td>
        <td><?=$end_date;?></td>
        <?php 
        foreach ($tp_occupancy as $key => $value):
         ?>
         <td><?php if(!empty($occ_data[$value]['changed_price'])) echo sprintf('%.2f',$occ_data[$value]['changed_price']) ; 
         else echo "N/A";?></td>
         <?php 
       endforeach 
       ?>
     </tr>
     <?php
   }
 endforeach 
 ?>    
</tbody>
</table>
<?php 
if ($temp_count) {
  ?>
  <script type="text/javascript">
    $(function() {
      $('#departure_date_table').removeClass('hide');
    });
  </script>
  <?php
}else{
  echo '<p>The web prices are currently not available for this package. Please contact us on the support number listed on this page and one of our tour specialists will be more than glad to work out a price package for you.</p><br><br>';
}
?>
</div>
</div>
</div>
<?php 
if(count($date_array)>0)
{ 
 ?>
 <div class="table-responsive col-xs-12 col-md-10 col-md-offset-1 dep_dates hide" id="departure_date_list_ul">
  <h4 style="margin-left:5px;font-size: 16px;width:100%;margin-top:0;">DEPARTURE DATES</h4>
  <div class="in_depdat">
   <ul>
    <?php 
    $temp_count_list = 0;
    foreach ($date_array as  $date) 
    {
     if($date > date('Y-m-d'))
     {
      $temp_count_list++;
      ?>
      <li><?php echo changeDateFormat($date); ?></li>
      <?php
    }
  }
  ?>
</ul>
<?php 
if ($temp_count_list) {
  ?>
  <script type="text/javascript">
    $(function() {
      $('#departure_date_list_ul').removeClass('hide');
    });
  </script>
  <?php
}
?>
</div>
</div>
<?php 
}
?>
</div>
<div class="tab-pane" id="tab_review">
  <div class="innertabs">
   <div class="ratingusr">
    <div class="mlgnformin">
     <?php
     $post_review_data['required']['module'] = 'Holiday';
     $post_review_data['required']['tour_id'] = $tour_data['id'];
     $post_review_data['required']['tours_itinerary_id'] = $tours_itinerary['id'];
                  // echo $this->template->isolated_view('hotel/post_review'); 
     ?>
   </div>
 </div>
</div>
</div>
</div>
</div>
</div>
</div>       
</div>
</div>
</div>
</div>
<div class="modal fade" id="myModal" role="dialog">
  <div class="modal-dialog">
   <div class="modal-content">
    <div class="modal-header">
     <button type="button" class="close" data-dismiss="modal">&times;</button>
     <h4 class="modal-title">Holiday Inquiry</h4>
     <h3 class="gateway" style="text-align: center;"><?=$tour_data['package_name']; ?></h3>
   </div>
   <p id="alertp" class="hide text-success">Thank you for choosing Ziphop.com. <br>One of our travel experts will contact you soon to discuss your travel plans</p>
   <form id="enquiry_form" action="javascript:void(0);">
     <div class="modal-body">
      <div class="form-group modl">
       <label class="control-label col-md-4 col-xs-4" for="user_name">Title : <strong class="text-danger"></strong></label>
       <div class="col-md-7 col-xs-8">
        <i style="visibility: hidden;" class="fa fa-user" aria-hidden="true"></i>            
        <select class="form-control mntxt" name="title" id="title" aria-required="true" required="required">
         <?=generate_options(get_enum_list('title'),(array)$this->entity_title)?>
       </select>
     </div>
   </div>
   <div class="form-group modl"><label class="control-label col-md-4 col-xs-4" for="user_name">First Name1 : <strong class="text-danger"></strong></label><div class="col-md-7 col-xs-8"><i style="visibility: hidden;" class="fa fa-user" aria-hidden="true"></i><input type="text" class="form-control mntxt" name="ename" id="ename" placeholder="" aria-required="true" required="required" value="<?=$this->entity_first_name?>"></div></div>
   <div class="form-group modl"><label class="control-label col-md-4 col-xs-4" for="user_name">Last Name : <strong class="text-danger"></strong></label><div class="col-md-7 col-xs-8"><i style="visibility: hidden;" class="fa fa-user" aria-hidden="true"></i><input type="text" class="form-control mntxt" name="elname" id="elname" placeholder="" aria-required="true" required="required" value="<?=$this->entity_last_name?>"></div></div>

   <div class="form-group modl contct">
     <label class="control-label col-md-4 col-xs-4" for="user_mobile"> Country Code  :</label>
     <div class="col-md-7 col-xs-8">
      <?php 
      if(isset($country_code) && valid_array($country_code)) 
      { 
       ?>
       <div class="selectedwrap"><select name="pn_country_code" id="pn_country_code" required="" aria-required="true" class="mySelectBoxClass flyinputsnor">
        <option value="+1">Canada +1</option>
        <option value="+1">United States +1</option>
        <?php 
        foreach($country_code as $c__k => $__country) 
        {
         ?>
         <option value="<?=@$c__k?>" <?php  if($c__k == $this->entity_phone_code) {echo 'selected';} ?>><?=$__country?></option>
         <?php 
       }
       ?>
     </select></div>
     <?php
   }
   ?>      
 </div>
</div>
<div class="form-group modl contct">
 <label class="control-label col-md-4 col-xs-4" for="user_mobile"> Phone Number  :</label>                
 <div class="col-md-7 col-xs-8">
  <i style="font-size: 25px;" class="fa fa-mobile" aria-hidden="true"></i>
  <input type="text" class="form-control mntxt mobile" name="emobile" id="emobile" placeholder="" value="<?=$this->entity_phone?>">
</div>
</div>
<div class="form-group modl">
 <label class="control-label col-md-4 col-xs-4" for="user_email"> Approximate Departure Date : <strong class="text-danger"></strong>
 </label>
 <div class="col-md-7 col-xs-8 eml n_psngr">
  <i class="fa fa-calendar" aria-hidden="true" style="right: 20px; left: inherit !important;"></i>
  <input type="text" class="form-control mntxt" id="departure_date" name="edeparture_date" placeholder="" aria-required="true" required="required" readonly="readonly">    
</div>
</div>
<div class="form-group modl"><label class="control-label col-md-4 col-xs-4" for="user_name">Number of passengers : <strong class="text-danger"></strong></label><div class="col-md-7 col-xs-8"><i class="fa fa-user" aria-hidden="true"></i><input type="text" class="form-control mntxt" name="number_of_passengers" id="number_of_passengers" placeholder="" aria-required="true" required="required"></div></div>
<div class="form-group modl"><label class="control-label col-md-4 col-xs-4" for="user_name">Duration : <strong class="text-danger"></strong></label><div class="col-md-7 col-xs-8"><i class="fa fa-clock-o" aria-hidden="true"></i><input type="text" class="form-control mntxt" name="durations" id="durations" placeholder=""></div></div>

<div class="form-group modl"><label class="control-label col-md-4 col-xs-4" for="user_email"> Email ID  : <strong class="text-danger"></strong></label><div class="col-md-7 col-xs-8 eml"><i class="fa fa-envelope" aria-hidden="true"></i><input type="email" class="form-control mntxt" name="eemail" id="eemail" placeholder="" aria-required="true" required="required" value="<?=$this->entity_email?>"></div></div>
<div class="form-group modl"><label class="control-label col-md-4 col-xs-4" for="comment"> Tell us about your trip plans  : <strong class="text-danger"></strong></label><div class="col-md-7 col-xs-8"><i class="fa fa-pencil-square-o" aria-hidden="true"></i><textarea rows="3" cols="40" class="form-control mntxt" name="ecomment" id="ecomment" placeholder="" aria-required="true" required="required"></textarea></div></div>
</div>
<div class="modal-footer">
  <input type="hidden" id="tour_id2" value="<?=$tour_data['id']?>">
  <input type="hidden" id="tours_itinerary_id" value="<?=$tours_itinerary['id']?>">
  <input type="submit" class="btn btn-default" id="send_enquiry_button" value="Send" />
</div>
</form>
</div>
</div>
</div>

<div id="subtqry" class="wellme minwnwidth" style="display: none;">
    <div class="popuperror" style="display:none;"></div>
    <button type="button" class="close log_close close_with_reset holiclose" data-dismiss="modal" style="font-size: 14px!important;">X</button>
    <div  class="pophed"> Send Enquiry For <?=$tour_data['package_name']; ?></div>
    <div class="signdiv">
      <form action="" method="post" id="tourenquiry">
        <input type="hidden" class="fulwishxl" id="package_id"  name="package_id" value="<?=$tour_data['id']; ?>"/>
        <span class="success_msg" style="color:#008000;font-weight: bold;"></span>
        <div class="rowlistwish">
            <div class="col-md-4">
              <span class="qrylbl">Name<span class="dfman">*</span></span>
            </div>
            <div class="col-md-8">
              <input type="text" class="fulwishxl alpha" id="first_name"  name="first_name" required maxlength="20" />
              <span id="verificationCodeErr" style="color:red; font-size: small"></span>
            </div>
        </div>
        <div class="rowlistwish">
            <div class="col-md-4">
              <span class="qrylbl"> Contact Number<span class="dfman">*</span> </span>
            </div>
            <div class="col-md-8">
              <input type="text" class="fulwishxl _numeric_only" maxlength='13' id="phone" name="phone" required="" />
              <span id="verificationCodeErr" style="color:red; font-size: small"></span>
            </div>
        </div>
        <div class="rowlistwish">
            <div class="col-md-4">
              <span class="qrylbl"> Email<span class="dfman">*</span> </span>
            </div>
            <div class="col-md-8">
              <input type="email" class="fulwishxl"  id="email_en" name="email" required/>
              <span id="verificationCodeErr" style="color:red; font-size: small"></span>
            </div>
        </div>
        <div class="rowlistwish">
            <div class="col-md-4">
              <span class="qrylbl">Departure Place<span class="dfman">*</span> </span>
            </div>
            <div class="col-md-8">
              <input type="text" class="fulwishxl" id="place" name="place" required/>
              <span id="verificationCodeErr" style="color:red; font-size: small"></span>
            </div>
        </div>
        <div class="rowlistwish">
            <div class="col-md-4">
              <span class="qrylbl">Departure Date<span class="dfman">*</span> </span>
            </div>
            <div class="col-md-8">
              <input type="text" class="fulwishxl" id="departure_date12" name="departure_date" required readonly="" />
              <span id="verificationCodeErr" style="color:red; font-size: small"></span>
            </div>
        </div>
        <div class="rowlistwish">
            <div class="col-md-4">
              <span class="qrylbl">Message<span class="dfman">*</span> </span>
            </div>
            <div class="col-md-8">
              <input type="text" class="fulwishxl" id="message" name="message" required/>
              <span id="verificationCodeErr" style="color:red; font-size: small"></span>
            </div>
        </div>


      <div class="clear"></div>
      <div class="downselfom">
      <div class="col-md-9 col-xs-12 col-md-offset-4">
        <div class="col-md-4 col-xs-6 nopad"><input type="cancel"  value="Cancel" class="btn  closequery" id="hideCancelPopup" readonly/></div>
        <div class="col-md-4 col-xs-6"><button type="submit"  value="Send Enquiry" class="btn colorsave colorcancel" id="startCancelProc">Send Enquiry</button></div>
        </div>
      </div>
      </form>
    </div>
</div>
<?php 





function getImageCondition($value)
{
 if (($value == "peg") || ($value == "jpg") || ($value == "gif") || ($value == "png")) {
   return true;
 } else {
   return false;
 }
 
}
?>





<script type="text/javascript">


/*$(".holiclose").click(function(){
  $("#subtqry").hide();
})*/

$('#startCancelProc').on('click',function (e) {
        e.preventDefault();

        var input_text=$("#email_en").val();
        // alert(input_text);
            var mailformat =/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/.test(input_text);
            // var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            
            if(!mailformat)
            {
               alert("Enter Valid email address!");
               return false;
            }


        $.ajax({
            url:app_base_url+'tours/enquiry',
            type:'POST',
            data:$("#tourenquiry").serialize(),
            success:function(msg){

                if(msg.status == true){
                    $('.success_msg').text(msg.message);
                    setTimeout(function() {
                        $('.success_msg').text('');
                    }, 3000); 
                    $('#tourenquiry')[0].reset(); 
                    $("#startCancelProc").blur();
                    // $('#subtqry').hide();
                    setTimeout(function(){ $('#subtqry').provabPopup().close(); }, 5000);
                    
                }
            },
            error:function(){
            }
         }) ;

    });






  $(document).ready(function(){
   /* $(".hasDatepicker").change(function(){
      alert();
    })*/


   $("#owl-demobaner").owlCarousel({
    items : 1,
    itemsDesktop : [1000,1],
    itemsDesktopSmall : [900,1],
    itemsTablet: [600,1],
    itemsMobile : [479,1],
    navigation : false,
    pagination : true,
    autoPlay : true
  });

   $("#owl-demobaner1").owlCarousel({
    items : 1,
    itemsDesktop : [1000,1],
    itemsDesktopSmall : [900,1],
    itemsTablet: [600,1],
    itemsMobile : [479,1],
    navigation : false,
    pagination : true,
    autoHeight : true,
    autoPlay : true
  });

   $('#sendquery').on('click', function(e) {
    e.preventDefault();

    $('#subtqry').provabPopup({
     modalClose: true,
     closeClass: 'closequery',
     zIndex: 100005
   });
  });
 });
</script>
<script type="text/javascript">
  $(document).ready(function(){
   $(".rating input:radio").attr("checked", false);
   $('.rating input').click(function () {
    $(".rating span").removeClass('checked');
    $(this).parent().addClass('checked');
  });
   $('input:radio').change(
    function(){
     var userRating = this.value;
     var pkg_id=$('#pkg_id').val();
     var str=pkg_id+','+userRating;
     $.ajax({
      url:app_base_url+'index.php/tours/package_user_rating',
      type:'POST',
      data:'rate='+str,
      success:function(msg){
       $('#msg_pak').show();
       $('#msg_pak').text('Thank you for rating this package').css('color','green').fadeOut(3000);
     },
     error:function(){
     }
   }) ;

   });
 });
</script>
<script type="text/javascript">  
  $(document).ready(function(){
   var sync1 = $("#sync1");
   var sync2 = $("#sync2");
   sync1.owlCarousel({
    singleItem : true,
    slideSpeed : 1000,
    navigation: true,
    pagination:false,
    afterAction : syncPosition,
    responsiveRefreshRate : 200,
  });

   sync2.owlCarousel({
    items : 4,
    itemsDesktop  : [1199,4],
    itemsDesktopSmall  : [979,4],
    itemsTablet  : [768,4],
    itemsMobile  : [479,2],
    pagination:false,
    responsiveRefreshRate : 100,
    afterInit : function(el){
     el.find(".owl-item").eq(0).addClass("synced");
   }
 });

   function syncPosition(el){
    var current = this.currentItem;
    $("#sync2")
    .find(".owl-item")
    .removeClass("synced")
    .eq(current)
    .addClass("synced")
    if($("#sync2").data("owlCarousel") !== undefined){
     center(current)
   }

 }

 $("#sync2").on("click", ".owl-item", function(e){
  e.preventDefault();
  var number = $(this).data("owlItem");
  sync1.trigger("owl.goTo",number);
});

 function center(number){
  var sync2visible = sync2.data("owlCarousel").owl.visibleItems;

  var num = number;
  var found = false;
  for(var i in sync2visible){
   if(num === sync2visible[i]){
    var found = true;
  }
}

if(found===false){
 if(num>sync2visible[sync2visible.length-1]){
  sync2.trigger("owl.goTo", num - sync2visible.length+2)
}else{
  if(num - 1 === -1){
   num = 0;
 }
 sync2.trigger("owl.goTo", num);
}
} else if(num === sync2visible[sync2visible.length-1]){
 sync2.trigger("owl.goTo", sync2visible[1])
} else if(num === sync2visible[0]){
 sync2.trigger("owl.goTo", num-1)
}
}
$('#send_enquiry_button_tour').on('click',function() {


  $title    = $('#tour_title').val();
  $ename    = $('#tour_ename').val();
  $elname   = $('#tour_elname').val();
  $emobile  = $('#tour_emobile').val();
  $eemail   = $('#tour_eemail').val();
  $ecomment = $('#tour_ecomment').val();
  $tour_id  = $('#tour_id1').val();
  $number_of_passengers  = $('#tour_number_of_passengers').val();
  $durations  = $('#tour_durations').val();
  $departure_date  = $('#tour_departure_date').val();
  $pn_country_code  = $('#tour_pn_country_code').val();
  $tours_itinerary_id = $('#tours_itinerary_id1').val();
  if($ename==''  || $eemail=='' || $ecomment=='' || $number_of_passengers=='' || $number_of_passengers== 0 || $departure_date=='' || $emobile.length < 10 ) { alert("Kindly fill all the fields!!"); return false; }
  $.post('<?=base_url();?>index.php/tours/ajax_enquiry', {'title': $title,'ename': $ename,'elname': $elname,'pn_country_code':$pn_country_code,'emobile': $emobile,'eemail': $eemail,'ecomment': $ecomment,'tour_id': $tour_id,'tours_itinerary_id': $tours_itinerary_id,'number_of_passengers':$number_of_passengers,'durations':$durations,'departure_date':$departure_date}, function(data) {
   $('#tour_enquiry_form').trigger("reset");
   $('#alertp').removeClass('hide');
   $('#tour_enquiry_form').addClass('hide');
 });
});
$('#enquiry_pop_button').click(function() {
  // alert("asd");
  $('#alertp').addClass('hide');
  $('#tour_enquiry_form').removeClass('hide');
}); 
});
</script>
<script src="https://travelfreetravels.com/extras/system/template_list/template_v1/javascript/page_resource/lightslider.js" ></script> 
<script>
  $(document).ready(function() {

    $('._numeric_only').on('keydown focus blur keyup change cut copy paste', function (e) {
    isNumber(e, e.keyCode, e.ctrlKey, e.metaKey, e.shiftKey);
  });

    $('.alpha_space').keypress(function (e) {
    var regex = new RegExp("^[a-zA-Z ]+$");
    var strigChar = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (regex.test(strigChar)) {
        return true;
    }
    return false
  });

   if ($('#departure_date_list').find('tr').length == 0) {
    $('#departure_date_table').hide();
  }
  var slider = $('#vertical').lightSlider({
    gallery:true,
    item:1,
    vertical:false,
     controls: false,
    verticalHeight:370,
    vThumbWidth:120,
    thumbItem:4,
    thumbMargin:8,
    slideMargin:0,
    loop:true
  });

  $('.slideControls .slidePrev').on('click',function() {
            slider.goToPrevSlide();
        });

        $('.slideControls .slideNext').on('click',function() {
            slider.goToNextSlide();
        });

  $( function() {
    $( "#departure_date" ).datepicker({
     minDate : 0,
   });
  }); 
  $(".n_psngr").click(function(){
    $('#departure_date').datepicker('show');
  });  

  $('.nav-tabs a').on('click',function(){
    $(this).tab('show');
  });    

  // $("#date_of_travel").datepicker({
  //  dateFormat:'dd-mm-yy',
  //  minDate: 0,
  //  maxDate: <?php echo $actual_date;?>,
  //  onSelect: function(selected,evnt) {
  //                // console.log(selected);
  //                // console.log(evnt);
  //                checkPrice(selected);
  //              }
  //            });
  $("#date_of_travel").datepicker({
             dateFormat:'dd-mm-yy',
           minDate: 0,
              maxDate: "<?=date("d-m-Y",strtotime($tour_data['expire_date']))?>",
           // maxDate: maxDate
           });
  $("#tour_departure_date").datepicker({
    dateFormat:'dd-mm-yy',
    minDate: 0,
    maxDate: <?php echo $actual_date;?>
  });

  $("#departure_date12").datepicker({
    dateFormat:'dd-mm-yy',
    minDate: 0,
    maxDate: <?php echo $actual_date;?>
  });

});

  $(document).on('change', '.dynamicPriceBreakdown', function(){
   dynamicPriceBreakdown1();
   dynamicPriceBreakdown();
 });

  function dynamicPriceBreakdown1(){
    $('.dynamicPriceBreakdown:checked').each(function(){
              // console.log($(this).data('type'));
              // console.log($(this).attr('data-priceTitle'));
              if($(this).data('price')!='undefined' && $(this).data('price')>0)
              {

                $('.trs-'+$(this).data('type')).find('.price').text($(this).data('price'));
                $('.type_'+$(this).data('type')).val($(this).attr('data-priceTitle'));
              }
              var total_price =parseInt($('.trs-hotel').find('.price').text()) + parseInt($('.trs-car').find('.price').text()) + parseInt($('.package_origional_price').val());
              $('.trs-total').find('.price').text(total_price);
            });
  }



  function dynamicPriceBreakdown(){
    $('.dynamicPriceBreakdown:checked').each(function(){

      var tour_id           = '<?php echo $tour_id;?>'
      var category_hotel    = $(".select_hotel").find(".dynamicPriceBreakdown:checked").attr('data-priceTitle');
      var category_transfer = $(".select_transfer").find(".dynamicPriceBreakdown:checked").attr('data-priceTitle');
      var no_of_adults      = $("#no_adults").val();
      var no_of_childs      = $("#no_child").val();
      var date_of_travel    = $("#date_of_travel").val();
      var no_of_transfer    = $("#no_of_transfer").val();
      var no_of_hotel_room  = $('#no_of_room').val();
              // alert(no_of_hotel_room);

              if (no_of_transfer==null || no_of_transfer=="" || no_of_transfer==0) { no_of_transfer = 1;}
              if (no_of_hotel_room==null || no_of_hotel_room=="" || no_of_hotel_room==0) { no_of_hotel_room = 1;}
              
              var no_of_room        = $("#no_of_room").val();
              // if(date_of_travel==""){
                if($(this).data('price')!='undefined' && $(this).data('price')>0)
                {
                 $('.trs-'+$(this).data('type')).find('.price').text($(this).data('price'));
                 $('.type_'+$(this).data('type')).val($(this).attr('data-priceTitle'));
               }
               var total_price =parseInt($('.trs-hotel').find('.price').text()) + parseInt($('.trs-car').find('.price').text()) + parseInt($('.package_origional_price').val());
               $('.trs-total').find('.price').text(total_price);
               $('#total_price').val(total_price);
               var ht_price =parseInt($('.trs-hotel').find('.price').text() * parseInt(no_of_hotel_room)) + parseInt($('.trs-car').find('.price').text() * parseInt(no_of_transfer));
              // }
              $.ajax({
                type:'POST',
                async:false,
                url:app_base_url+'index.php/tours/find_tourprice_by_categories',
                data:{'tour_id':tour_id,'no_of_room':no_of_room,'no_of_transfer':no_of_transfer,'category_transfer':category_transfer,'category_hotel':category_hotel,'no_of_adults':no_of_adults,'no_of_childs':no_of_childs,'date_of_travel':date_of_travel,'ht_price':ht_price},
                success:function(result){
                 if(result){
                  $('.trs-total').find('.price').text(result);
                  $('#total_price').val(result);
                }
              },
            });
              //end ajax call
            });
  }


</script>

    <!--  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAzBM4zsiMhJp1fUnlDMOU6gvLNoL8cvhM&callback=initMap">
    </script> -->

    <script type='text/javascript'>

      $(document).ready(function() {

        ViewCustInGoogleMap();

        function formatDate(date) {
          var d = new Date(date),
          month = '' + (d.getMonth() + 1),
          day = '' + d.getDate(),
          year = d.getFullYear();

          if (month.length < 2) month = '0' + month;
          if (day.length < 2) day = '0' + day;

          return [year, month, day].join('-');
        }

        var tour_price_list = <?php echo json_encode($tour_price_list); ?>;

        
//        console.log(tour_price_list);
var start = new Date("<?=date("Y-m-d")?>");
var end = new Date("<?=date('Y-m-d',strtotime($tour_data['expire_date']))?>");


var loop = new Date(start);
var event_option = '[';
while(loop <= end){
  var tour_price = '';
  for(var i=0;i<tour_price_list.length;i++){

    var dateFrom = tour_price_list[i]['from'];
    var dateTo = tour_price_list[i]['to'];
    var dateCheck = formatDate(loop).toString();

    var d1 = dateFrom.split("-");
    var d2 = dateTo.split("-");
    var c = dateCheck.split("-");

                var from = new Date(d1);  // -1 because months are from 0 to 11
                var to   = new Date(d2);
                var check = new Date(c);

                if(check >= from && check <= to){
                  tour_price = '<?=$this->currency->get_currency_symbol(get_application_display_currency_preference());?> ' + tour_price_list[i]['price'];
                }

              }

              event_option += '{';
              event_option += '"title": "'+tour_price;
              event_option += '", "start": "'+ formatDate(loop) +'" }';

              if(formatDate(loop) == formatDate(end) ) {

                event_option += ']';
              }
              else
                event_option += ',';

              var newDate = loop.setDate(loop.getDate() + 1);
              loop = new Date(newDate);
            }

            event_option = JSON.parse(event_option);

            /*$('#calendar').fullCalendar({

              events: event_option,

              eventRender: function(event, element) {
                element.css("font-size", "1.2em");
                element.css("text-align", "center");
              }


            });*/




          });

      var map;
      var geocoder;
      var marker;
      var people = new Array();
      var latlng;
      var infowindow;
      var visited_city = <?php echo json_encode($visited_city_list); ?>;

      function setMarker(people) {

        geocoder = new google.maps.Geocoder();
        infowindow = new google.maps.InfoWindow();
        if ((people["LatitudeLongitude"] == null) || (people["LatitudeLongitude"] == 'null') || (people["LatitudeLongitude"] == '')) {
          geocoder.geocode({ 'address': people["Address"] }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
              latlng = new google.maps.LatLng(results[0].geometry.location.lat(), results[0].geometry.location.lng());
              marker = new google.maps.Marker({
                position: latlng,
                map: map,
                draggable: false,
                html: people["DisplayText"],
                icon: "images/marker/" + people["MarkerId"] + ".png"
              });
                    //marker.setPosition(latlng);
                    //map.setCenter(latlng);
                    google.maps.event.addListener(marker, 'click', function(event) {
                      infowindow.setContent(this.html);
                      infowindow.setPosition(event.latLng);
                      infowindow.open(map, this);
                    });
                  }
                  else {
                    alert(people["DisplayText"] + " -- " + people["Address"] + ". This address couldn't be found");
                  }
                });
        }
        else {
          var latlngStr = people["LatitudeLongitude"].split(",");
          var lat = parseFloat(latlngStr[0]);
          var lng = parseFloat(latlngStr[1]);
          latlng = new google.maps.LatLng(lat, lng);
          marker = new google.maps.Marker({
            position: latlng,
            map: map,
                draggable: false,               // cant drag it
                html: people["DisplayText"]    // Content display on marker click
                //icon: "images/marker.png"       // Give ur own image
              });
            //marker.setPosition(latlng);
            //map.setCenter(latlng);
            google.maps.event.addListener(marker, 'click', function(event) {
              infowindow.setContent(this.html);
              infowindow.setPosition(event.latLng);
              infowindow.open(map, this);
            });
          }
        }

        function ViewCustInGoogleMap() {

          var k = 0;
          var mark_data = '[';
          visited_city.forEach(function (entry) {
            // $.getJSON("https://maps.googleapis.com/maps/api/geocode/json?address=" + encodeURIComponent(entry), function (val) {
              $.getJSON("https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyBeYTHE-zI8GJwbtgrfTv_FWHFMRwyagjk&address=" + encodeURIComponent(entry), function (val) {
                console.log("https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyBeYTHE-zI8GJwbtgrfTv_FWHFMRwyagjk&address=" + encodeURIComponent(entry));
                if (val.results.length) {
                  var location = val.results[0].geometry.location;


                  mark_data += '{ "DisplayText": "'+entry+'", "LatitudeLongitude": "'+location.lat + ',' + location.lng+'"}';
                  if(k == (visited_city.length - 1)){
                    $("#mapid").css('visibility', 'visible');
                    mark_data += ']';

                    var mapOptions = {
                            center: new google.maps.LatLng(location.lat, location.lng),   // Coimbatore = (11.0168445, 76.9558321)
                            zoom: 7,
                            mapTypeId: google.maps.MapTypeId.ROADMAP
                          };
                          map = new google.maps.Map(document.getElementById("mapid"), mapOptions);
                          people = JSON.parse(mark_data);

                          for(var i = 0;i < people.length;i++){
                            setMarker(people[i]);
                          }
                        }
                        else{
                          mark_data += ',';
                        }

                        k++;
                      }

                    })

            });

        }




        
      </script>
      <script type="text/javascript">
        function hidebook() {
          if($('#no_of_extrabed').val() > 0){
            $('.hidbook').addClass('hide');
          } else{
            if($('.hidbook').hasClass('hide')){
              $('.hidbook').removeClass('hide');
            }
          }
        }
        function roomcountcalculation() {
          var adt_count = $('#no_adults').val();
          var cnn_count = $('#no_child').val();
           var inn_count = $('#no_infant').val();
          adt_count = parseInt(adt_count) + parseInt(cnn_count)+ parseInt(inn_count);
          var adt_count_org = adt_count;
          if (adt_count == 1) {
            $('#no_of_room').val(1);
            $('#no_of_extrabed').val(0);
            hidebook();
          } else {
            var extras_bed = adt_count % 2;
            $('#no_of_room').val(parseInt(adt_count / 2));
            $('#no_of_extrabed').val(extras_bed);
            if (adt_count_org > 1) {
              hidebook();
            }
          }
          dynamicPriceBreakdown();
        }
        function checkPrice(argument) {
          var tour_price_list_val = <?php echo json_encode($tour_price_list); ?>;
          var errorflag = 1;
        // console.log(argument);
        for(var i=0;i<tour_price_list_val.length;i++){

          var dateFrom = tour_price_list_val[i]['from'];
          var dateTo = tour_price_list_val[i]['to'];
          var d1 = dateFrom.split("-");
          var d2 = dateTo.split("-");
                var from = d1.reverse().join('-');  // -1 because months are from 0 to 11
                var to   = d2.reverse().join('-');
                // console.log(dateFrom);
                // console.log(dateTo);
                // console.log(from);
                // console.log(to);

                var d1 = from.split("-");
                var d2 = to.split("-");
                var c = argument.split("-");
                var from1 = new Date(d1[2], parseInt(d1[1])-1, d1[0]);  // -1 because months are from 0 to 11
                var to1   = new Date(d2[2], parseInt(d2[1])-1, d2[0]);
                var check = new Date(c[2], parseInt(c[1])-1, c[0]);
                if(check >= from1 && check <= to1){
                  errorflag = 0;
                }
                // console.log(check > from1 && check < to1)

}
// if (errorflag == 1) {
//   $('.hidbook').addClass('hide');
// } else{
//   if($('.hidbook').hasClass('hide')){
//     $('.hidbook').removeClass('hide');
//   }
// }
}
</script>


<script>
  
function manage_adult_count(pax_type,cal) 
{
   
 var adult_count = parseInt($('#no_adults').val());
    var count=0;
     if((pax_type == 'adult') && (cal=='plus'))
     { 
          if(adult_count >9)
          {
            return false;
          }

        count=adult_count+1; 
        $('#no_adults').val(""+count);
     }
     else
     {

      if(adult_count <=1)
          {
            return false;
          }

        count=adult_count-1; 
        $('#no_adults').val(""+count);
     }

}


function manage_child_count(pax_type,cal) 
{
   
 var no_child = parseInt($('#no_child').val());
    var count=0;
     if((pax_type == 'child') && (cal=='plus'))
     { 
          if(no_child >3)
          {
            return false;
          }

        count=no_child+1; 
        $('#no_child').val(""+count);
     }
     else
     {

      if(no_child <=0)
          {
            return false;
          }

        count=no_child-1; 
        $('#no_child').val(""+count);
     }

}

</script>

<script>
 /*   $("#date_of_travel").keydown(function(e){
        e.preventDefault();
    });*/
</script>

   <?php 
   //debug($tour_data);exit();
  $start_date=$tour_data['start_date'];
  $expire_date=$tour_data['expire_date'];

  ?>
  <input type="hidden" id="strt" value="<?=$start_date?>" name="strt">
  <input type="hidden" id="expr" value="<?=$expire_date?>" name="ex">
  
<!--   <script type="text/javascript">
    $(document).ready(function(){
    
        var st=$("#strt").val();
        var t = new Date(st);
        var stt = ((t.getMonth().length+1) === 1)? (t.getMonth()+1) : '0' + (t.getMonth()+1);
 
          var std = t.getDate() + "-" + stt + "-" + t.getFullYear();

         var ex=$("#expr").val();
          var e = new Date(ex);
           var exm = ((e.getMonth().length+1) === 1)? (e.getMonth()+1) : '0' + (e.getMonth()+1);
 
          var exd = e.getDate() + "-" + exm + "-" + e.getFullYear();
        // alert(std);

      $("#date_of_travel").change(function(){
        var td=$("#date_of_travel").val();
        var msg=$("#err").val();
        if(td>=std && td<=exd)
        {
          $("#err").text("");
         $('#check').prop('disabled', false);
          return true;

        }
        else {
           
         $("#err").text("Choose date between("+ std+'   '+exd+')');
        $('#check').prop('disabled', true);
         return false;

        }
      });
    });


  </script> -->
  <script>
       <?php 
  $start_date=$tour_data['start_date'];
  $expire_date=$tour_data['expire_date'];

  ?>
$(document).ready(function() {
//   var currentTime = new Date() 
// var minDate = new Date(currentTime.getFullYear(), currentTime.getMonth(), +1); //one day next before month
// var maxDate =  new Date(currentTime.getFullYear(), currentTime.getMonth() +1, +0);

$("#date_of_travel").datepicker({
             dateFormat:'dd-mm-yy',
             minDate: '<?=date("d,m,Y",strtotime($start_date))?>',
             maxDate: '<?=date("d,m,Y",strtotime($expire_date))?>'
           });
        


});
  </script>  
  <script>
    $("#thumbs2 li").on('click',function() {
    $('html, body').animate({
        scrollTop: $(".daytabb").offset().top
    }, 2000);
}); 
    $(".holiclose").on('click',function() {
        /*$('.pro-modal').hide();
        $("#subtqry").css('opacity',0);
     $("#subtqry").hide();*/
     
    $("#subtqry").provabPopup().close();
   // close();

});
    $(document).on('keyup','#phone', function() {
      var numvalue=$("#phone").val();
      let isnum = /^\d+$/.test(numvalue);
      if(!isnum){
        $("#phone").val('');
      }
   // code
});

  </script>
  <script>
        $(document).ready(function() {
            $("#holiday_load_new").owlCarousel({

                autoPlay: 3000, //Set AutoPlay to 3 seconds

                items: 1,
                itemsDesktop: [1199, 3],
                itemsDesktopSmall: [979, 2],
                nav: true,
                pagination: false,
                autoPlay: true,
                
            });
        });
        </script>
<script type="text/javascript">
  
  function PackagePriceCalculator(adultCount,childCount,infantCount,date){
  $("#check").addClass('Hide');
  $("#date_of_travel").val(date);
  /*$("#adultPax").val(adultCount);
  $("#childPax").val(childCount);
  $("#adultPaxBook").val(adultCount);
  $("#childPaxBook").val(childCount);*/
 
     var packageCurrency;
    
  


    var tourPrice = <?php echo json_encode($tour_price_changed);?>;
       tourPrice = JSON.stringify(tourPrice);
         // console.log(tourPrice);
    var adult_genral_markup=0;
    var child_genral_markup=0;
     adt_genral_markup=<?php echo @$Markup?>;
       tourPrice = JSON.parse(tourPrice);
    packageCurrency="<?php echo $packageCurrency;?>";    
        date = new Date(date);
          console.log(tourPrice);
          var fromDate;
          var toDate;
          var grandTotal;
          var adultPrice=tourPrice[tourPrice.length-1].changed_price;
          // var childPrice=tourPrice[tourPrice.length-1].child_airliner_price;
          //  var infantPrice=tourPrice[tourPrice.length-1].infant_airline_price;
          var childPrice=tourPrice[tourPrice.length-1].convt_child_airliner_price;
           var infantPrice=tourPrice[tourPrice.length-1].convt_infant_airline_price;
          
          for (var i = 0; i < tourPrice.length; i++) {
            fromDate = new Date(tourPrice[i].from_date);
            toDate =new Date(tourPrice[i].to_date);
            
            if(fromDate < date && date < toDate){
              adultPrice=tourPrice[i].changed_price;
              // childPrice=tourPrice[i].child_airliner_price;
              // infantPrice=tourPrice[i].infant_airline_price;
               childPrice=tourPrice[i].convt_child_airliner_price;
              infantPrice=tourPrice[i].convt_infant_airline_price;
              $("#check").removeClass('Hide');
            }
          }
          adultTotalPrice=adultPrice * adultCount;
          childTotalPrice=childPrice * childCount;
          var total_count=parseInt(adultCount)+parseInt(childCount);

             $.ajax({
                url:app_base_url+'tours/tourprice_change_adult_child',
                type:'POST',
                data:{'adultPrice':parseInt(adultPrice),'childprice':parseInt(childPrice),'infantprice':parseInt(infantPrice),'total_count':total_count,'adultCount':adultCount,'childCount':childCount,'infantCount':infantCount},
                success:function(msg){

                    if(msg){

                     var data=JSON.parse(msg);
                     console.log(data.grandtotal);
                         /*adult_genral_markup=data.adult_Markup;
                        child_genral_markup=data.child_Markup;
                       
                         if(parseInt(adult_genral_markup) >0)
                          {
                            var adult_gst_value=data.adult_gst_value;
                            adult_genral_markup=adult_genral_markup*adultCount;
                            adult_gst_value=parseInt(adult_gst_value)*adultCount;
                          }
                          if(parseInt(child_genral_markup) >0)
                          {
                            var child_gst_value=data.child_gst_value;
                            child_genral_markup=child_genral_markup*childCount;
                            child_gst_value=parseInt(child_gst_value)*childCount;
                          }

                          // alert(adultTotalPrice);
                          // alert(childTotalPrice);
                          // alert(genral_markup);
                          // alert(gst_value);
                          
                          var convenience_fees=<?php echo $convenience_fees;?>;
                          var gst_value_conv=<?php echo $gst_value_conv;?>;
                          // alert(Math.round(gst_value_conv));
                           if(parseInt(convenience_fees) >0)
                          {
                           
                            convenience_fees=parseInt(Math.round(convenience_fees))*total_count;
                            gst_value_conv=parseInt(Math.round(gst_value_conv))*total_count;
                          }*/
                          // alert(convenience_fees);
                          // alert(gst_value_conv);
                          
                         /* grandTotal =adultTotalPrice + childTotalPrice + parseInt(genral_markup)+ parseInt(gst_value)+ parseInt(convenience_fees)+ parseInt(gst_value_conv);
                          grandTotal = (Math.round(grandTotal * 100) / 100).toFixed(2);
                          grandTotal = grandTotal; */
                         
                    // $(".num22").text(grandTotal);
                    // alert(data.grandtotal);
                    $(".num22").text(data.grandtotal);


                        
                    }
                },
                error:function(){
                }
             }) ;
         
  } 
$(function() {
          var d = new Date();
          var currMonth = d.getMonth()+1;
          var currYear = d.getFullYear();
          var currDay = d.getDate()+1;
          if(currDay<10) 
        {
            currDay='0'+currDay;
        } 

        if(currMonth<10) 
        {
            currMonth='0'+currMonth;
        } 
          var startDate = currDay+"-"+currMonth+"-"+currYear;
          adultCount =parseInt($("#no_adults").val());
          childCount =parseInt($("#no_child").val());
          document.getElementById("date_of_travel").defaultValue = startDate;

});
  </script>
 <script>
     $(document).ready(function(){
  $('.btn-number').click(function(){
        
        var action = $(this).attr('data-type');
        var category = $(this).attr('data-field');
        adultCount =parseInt($("#no_adults").val());
        childCount =parseInt($("#no_child").val());
        InfantCount =parseInt($("#no_infant").val());

        if(action == 'plus' && category=='adult'){
          adultCount = adultCount +1;
        }else if(action == 'minus' && category=='adult' && adultCount!=1){
          adultCount = adultCount -1;
        }else if(action == 'plus' && category=='child'){
          childCount =childCount + 1;    
        }else if(action == 'minus' && category=='child' && childCount !=0){
          childCount =childCount - 1;
        }else if(action == 'plus' && category=='infant'){
          InfantCount =InfantCount + 1;    
        }else if(action == 'minus' && category=='infant' && InfantCount !=0){
          InfantCount =InfantCount - 1;
        }
        $("#no_adults").val(adultCount);
        $("#no_child").val(childCount);
         $("#no_infant").val(InfantCount);
        date = $("#date_of_travel").val();
        PackagePriceCalculator(adultCount,childCount,InfantCount,date);

      });
});
 </script>
  