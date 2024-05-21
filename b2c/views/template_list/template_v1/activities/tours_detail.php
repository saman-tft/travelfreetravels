
<style>
.checklist li
{
  width: 100%!important;
}
</style>
<link href=
'https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/ui-lightness/jquery-ui.css'
          rel='stylesheet'>
      
    <script src=
"https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" >
    </script>
      
    <script src=
"https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" >
    </script>
<?php 
// debug($package);exit;
// debug($formated_data[$key]['transport_price']); exit;
$seasonsDateValue = $formated_data[$key]['seasonsDateValue'];
$weekDaysDate = $activity_dates['shift_days_week'];
$adult=$search_data['adult'];
$child=$search_data['child'];
$from_date=$search_data['from_date'];
$to_date=$search_data['to_date'];
$contrller = '';
if($package->module_type == 'transfers'){

  $contrller = 'transferv1';
}else{

  $contrller = 'activities';
} 


  
 $package_price=0;
 if(!empty($formated_data[$key]))
 { 

    if(isset($formated_data[$key]['Price']))
    {
    $package_price=$formated_data[$key]['Price']['TotalDisplayFare'];
          
    }
$admin_buy_price = $formated_data[$key]['Price']['_AdminBuying'];
$agent_price_with_markup = $admin_buy_price+$formated_data[$key]['Price']['_Markup'];
$diff_price = $package_price - $admin_buy_price;
$transfer_type =$formated_data[$key]['transfer_type'];   
 }
 $days =json_decode($package->weekdays);
  $days_count = count($days);
  $weekDay='';
  for($i=0;$i<$days_count;$i++)
  {
    if($days[$i]==1)
    {
      $wk_day = 'Monday';
    }else if($days[$i]==2)
    {
      $wk_day = 'Tuesday';
    }else if($days[$i]==3)
    {
      $wk_day = 'Wednesday';
    }else if($days[$i]==4)
    {
      $wk_day = 'Thursday';
    }else if($days[$i]==5)
    {
      $wk_day = 'Friday';
    }else if($days[$i]==6)
    {
      $wk_day = 'Saturday';
    }else if($days[$i]==7)
    {
      $wk_day = 'Sunday';
    }
    if($i==0){$weekDay .= $wk_day;}else{$weekDay .=  ','.$wk_day;}
    
  }

  $hr = json_decode($package->health_restrictn,true);
  // debug($hr);die;
 
  $pickup_country = $GLOBALS['CI']->db->select("*")->from('country')->where("country_id",$package->traveller_country)->get()->result_array()[0]['name'];

  if(!empty($hr))
  {
    $hr = "'" .implode("', '", $hr) . "'";
    $helth_restrictions = $GLOBALS['CI']->db->select("*")->from('health_instructions')->where("id IN (".$hr.")")->get()->result_array();
    
  }
  
  // debug($helth_restrictions);die;
?>
<?php
Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('owl.carousel.min.css'), 'media' => 'screen');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('owl.carousel.min.js'), 'defer' => 'defer');
?>
<link href="<?php echo $GLOBALS['CI']->template->template_css_dir('custom_tour_package.css') ?>" rel="stylesheet">
<link  href="<?php echo $GLOBALS['CI']->template->template_css_dir('custom_sky.css') ?>" rel="stylesheet">
<script src="<?php echo $GLOBALS['CI']->template->template_js_dir('initialize-carousel-detailspage.js') ?>" type="text/javascript"></script> 
<script src="<?php echo $GLOBALS['CI']->template->template_js_dir('jquery.provabpopup.js') ?>" type="text/javascript"></script>
<script src="<?php echo $GLOBALS['CI']->template->template_js_dir('jquery.carouFredSel-6.2.1-packed.js') ?>" type="text/javascript"></script>

<style type="text/css">
    footer { margin: 0px; }
    .ovrimpdiv { padding: 0px !important; margin: 0px; }
    .masthead-wrapper .actimgdiv { margin-top: 0px; }
    .ovrimpdiv .tab-content { margin-bottom: 0px; padding: 0px; box-shadow: none; }
    .innertabs { margin-bottom: 10px; }
    /*#owl-demobaner1 .owl-theme .owl-next, #owl-demobaner1 .owl-theme .owl-prev{width: 30px;
    height: 30px;
    margin-top: -19px;
    opacity: 1;
    background: #fff!important;
    border-radius: 0;
    box-shadow: 2px 2px 2px #ccc;
    font-size: 0;} */
   #owl-demobaner1 .owl-carousel{display: block!important;}

#owl-demobaner1 .owl-buttons {
    text-align: right;
    margin-right: 20px;
}
#owl-demobaner1.owl-theme .owl-next, #owl-demobaner1.owl-theme .owl-prev
{width: 30px;
    height: 30px;
    margin-top: -19px;
    opacity: 1;
    background: #fff!important;
    border-radius: 0;
    box-shadow: 2px 2px 2px #ccc;
    font-size: 0;}



#owl-demobaner1 .owl-theme .owl-prev:before {
    /* position: absolute; */
    font-size: 16px;
    content: '\f053' !important;
    font-family: 'Font Awesome 5 Pro';
    color: #fff;
    left: 10px;
    top: 4px;
}

#owl-demobaner1 .owl-theme .owl-next {
    right: 13px;
}

#owl-demobaner1 .owl-theme .owl-next:before {
    /* position: absolute; */
    font-size: 16px;
    content: '\f054' !important;
    font-family: 'Font Awesome 5 Pro';
    color: #fff;
    left: 13px;
    top: 4px;
}


#owl-demobaner1 .owl-theme .owl-next span{
    font-size: 0;
}
#owl-demobaner1 .owl-nav {
    margin-top: 5px;
}

.no-js .owl-carousel, .owl-carousel.owl-loaded {
    display: block;
}
.imgslwidth{width: 510px!important;}
</style>

<div class="outactdiv sghtseen">
  <div class="container">
 <?php if($enquire_status == 1){ ?>
    <br>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="alert alert-dismissible alert-success">
              <button type="button" class="close" style="margin-top: -5px;" data-dismiss="alert">&times;</button>
              <strong> Your enquire is submitted successfully. one of our travel consultant will contact you soon.!</strong> 
            </div>
        </div>
    </div>
       
                    <?php } ?>
        

      <div class="col-xs-12 nopad">
        <div class="org_row">

            <div class="col-md-12 nopad">


        <div class="col-md-4 rit_summery">
         <div class="frmdiv">
           <div class="booking-price">
            <label>Transfer Type</label>
            <select class="form-control" id="transfer_option" name="transfer_option">

            <?php $transport_option = $formated_data[$key]['transport_price'];
              $ary = array();
            $status='';
            //  $phpVar =  $_COOKIE['myJavascriptVar'];
            // debug($phpVar);exit;
            for($i=0; $i<count($transport_option); $i++){
            	if($transport_option[$i]['duration']==$seasonsDateValue){
            $transport = $transport_option[$i]['transfer_option'];
            if($transport=='W'){
              $transfer_desc='Without Transfers';
            }
            if($transport=='S'){
              $transfer_desc='Sharing Transfers';
            }
            if($transport=='P'){
              $transfer_desc='Private Transfers';
            }
            if($transport==$transfer_type){
              $status='selected';
            }else{$status='';}
            if(!in_array($transport, $ary)){ ?>
              <option value="<?=$transport?>" <?=$status?> ><?=$transfer_desc?></option>
            <?php 
            }
            $ary[] = $transport;
        	}
            }
            ?>
            </select>
           
             <span class="price-from">Best Price From </span>
             <span class="price-amount price-amount-l">
             <span class="currency-sign"><?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?></span> 
                <span class="currency-amount"><?php //echo isset($package_price)? number_format(get_converted_currency_value ( $currency_obj->force_currency_conversion ( $package_price) ), 2):0; 

                      echo isset($package_price)? number_format($package_price, 2):0;
                ?> </span>
             </span>

           <!--   <span class="snote">*Per Person</span> -->
                            <div class="sdfr"><?php echo isset($package->duration)?$package->duration:0; ?> Days / <?php echo isset($package->duration)?($package->duration - 1):0; ?> Nights </div>

           </div>
           
               <div class="datediv">           
                

                <div class="clearfix"></div>
                

                <div class="patendiv">
                   <!--  <input type="submit" class="patencls" id="sendquery" value="Send Query" style="width: 150px; float: left; background: #888; border:1px solid #888;" />book_packages -->

                    <form  role="form" id="package"
                                    enctype="multipart/form-data" method="POST" action="<?php echo base_url(); ?>index.php/<?=$contrller?>/pre_booking"
                                    autocomplete="off" name="package">


                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                <label>Date of Travel <strong class="text-danger no_block">* </strong></label>
                                <?php 
                                $activity_dates_byDAte =json_decode($activity_dates['activity_booking_dates']); 
                                if($package->booking_availbale=='D' && count($activity_dates_byDAte )>0){ ?>

                                <div class="plcetogo datemark sidebord">
                                  <select class="form-control b-r-0 normalinput" data-date="true" readonly name="date_of_travel" id="date_of_travel_date" required>
                                <?php
                                //  debug($activity_dates);
                                
                              
                                for($i=0; $i<count($activity_dates_byDAte); $i++){
                                  $vall = $activity_dates_byDAte[$i];
                                  $vall = explode('/', $vall);
                                  $date_val = $vall[0].'-'.$vall[1].'-'.$vall[2]; ?>
                                  <!-- $option = '<option value="'.$date_val.'" >'.$date_val.'</option>';   -->  
                                   <option value="<?=$date_val?>" ><?=$date_val?></option>   
                                <?php }
                                 ?>
                                  </select>
                                  <i class="fa fa-times-circle" style="display: none;"></i> <span style="color: #912f3c;display: none;" id="cancellation_policy"></span>
                                </div>
                                <?php }else{
                                  ?>
                                <div class="plcetogo datemark sidebord">
                                  <input  type="text" class="form-control b-r-0 normalinput"  name="date_of_travel"   placeholder="Date of travel" id="date_of_travel" required />
                                  <i class="fa fa-times-circle" style="display: none;"></i> <span style="color: #912f3c;display: none;" id="cancellation_policy"></span>
                                  <!-- <div id="cancellation_policy"><i class="fa fa-times-circle"></i> <span style="color: #912f3c;"><?= $transfer_data['cancellation_details'] ?></span></div> -->
                                </div>
                                <?php } ?>
                              </div>
                                    <input type="hidden" id="seasonsDateValue" name="seasonsDateValue" value="<?=$seasonsDateValue?>" />
                                    <input type="hidden" id="seasonsDateValue_old" name="seasonsDateValue_old" value="<?=$seasonsDateValue?>" />
                                    <input type="hidden" name="book_id" value="<?=isset($temp_token['book_id'])?$temp_token['book_id']:''?>" />
                                    <input type="hidden" name="temp_token" value="<?=isset($temp_token['temp_booking_origin'])?$temp_token['temp_booking_origin']:''?>" />
                                    <input type="hidden" name="package_id" value="<?php echo $package_id?>" />
                                    <input type="hidden" name="agent_price_with_markup" id="agent_price_with_markup" value="<?php echo $agent_price_with_markup?>" />
                                    <input type="hidden" name="book_id" value="<?=isset($temp_token['book_id'])?$temp_token['book_id']:''?>" />
                                    <input type="hidden" name="cancellationPolicy" id="cancellationPolicy" value="" />
                                    <input type="hidden" name="transport_type" id="transport_type" value="<?=$formated_data[$key]['transfer_type']?>" />
                                    <input type="hidden" name="cancltnPlcy_avble" id="cancltnPlcy_avble" value="" />
                                    <?php
                                    $date_cnt = count($activity_dates);
                                    $date_ary = array();
                                    ?>
                                    <input type="hidden" name="date_count" id="date_count" value='<?=$date_cnt?>' />
                                    <?php
                                    for($i=0;$i<$date_cnt;$i++){
                                    	$arry = array();
									$date_duration = $activity_dates[$i]['duration'];
									$date_duration = explode(' - ', $date_duration);
									$date_duration_from = explode('/', $date_duration[0]);
                                  	$date_dur_frm = $date_duration_from[1].'-'.$date_duration_from[0].'-'.$date_duration_from[2];
                                  	$date_duration_to = explode('/', $date_duration[1]);
                                  	$date_dur_to = $date_duration_to[1].'-'.$date_duration_to[0].'-'.$date_duration_to[2];
									$weekDaysDate = $activity_dates[$i]['shift_days_week'];
									$arry[]=$date_dur_frm;
									$arry[]=$date_dur_to;
									$date_ary[$i] = $arry;

                                    
                                    $activity_date_dur = json_encode($date_ary);
									?>
                                    <!-- <input type="hidden" name="date_range" id="date_range" value='<?=$activity_date_dur?>' /> -->
                                    <input type="hidden" name="date_from<?=$i?>" id="date_from<?=$i?>" value='<?=$date_dur_frm?>' />
                                    <input type="hidden" name="date_to<?=$i?>" id="date_to<?=$i?>" value='<?=$date_dur_to?>' />
                                    <input type="hidden" name="week_<?=$i?>" id="week_<?=$i?>" value='<?=$weekDaysDate?>' />

                                <?php } ?>
                                 <input type="hidden" name="is_pickup_available" id="is_pickup_available"  value="NO" >
                            <input type="submit" class="patencls" id="book" value="Book Now" style="width: 150px; float: right;" />
                            </form>
                </div>

                </div>
           </div>
         </div>

    

                <div class="col-md-8 col-xs-12 nopad fulatnine">

                     <div class="htladdet light-border">             
                        <span><?php echo $package->package_name ?></span>                             
                        
                        <ul class="locdurdiv">              
                            <!-- <li>Location: Bengaluru, India</li>        -->                         
                            <li> Duration:<span><?php echo isset($package->duration)?$package->duration:0; ?> Days / <?php echo isset($package->duration)?($package->duration - 1):0; ?> Nights </span>(approx.)</li>                           
                        </ul>        
                   </div>

                   <div class="clearfix"></div>

                    <div id="act_sldr">  
                        <div id="hotel_top" class="owl-carousel indexbaner">
                        <?php if(!empty($package)){ ?>
                           <div class="item">
                                <div class="smalbanr"><img src="<?php echo $GLOBALS['CI']->template->domain_upload_acty_images($package->image); ?>" alt="" /></div>
                          </div>
                        <?php } ?>
                        </div>
                   </div>

                    <div class="clearfix"></div>

                    


<div class="detailtab indtophd">
  
  <ul class="nav nav-tabs trul indtophdd ui-helper-reset ui-helper-clearfix ui-widget-header indihead" role="tablist">
    
    <li class="active inditab" role="tab" tabindex="0" aria-labelledby="ui-id-1" aria-selected="true" aria-expanded="true">
    <a href="#ovrvw" data-toggle="tab" class="indanchr" role="presentation" tabindex="-1" id="ui-id-1">Overview</a>
      
    </li>
    <li class="inditab" role="tab" tabindex="-1" aria-controls="itnery" aria-labelledby="ui-id-2" aria-selected="false" aria-expanded="false">
    <a href="#itnery" data-toggle="tab" class="indanchr" role="presentation" tabindex="-1" id="ui-id-2">Detailed Itinerary</a>
      
    </li>
    <li class="inditab" role="tab" tabindex="-1" aria-labelledby="ui-id-3" aria-selected="false" aria-expanded="false" aria-controls="tandc">
    <a href="#tandc" data-toggle="tab" class="indanchr" role="presentation" tabindex="-1" id="ui-id-3">Terms & Conditions</a>
      
    </li>
    <li class="inditab" role="tab" tabindex="-1" aria-labelledby="ui-id-4" aria-selected="false" aria-expanded="false" aria-controls="gallery">
    <a href="#gallery" data-toggle="tab" class="indanchr" role="presentation" tabindex="-1" id="ui-id-4">Gallery</a>
      
    </li>
    <li class="inditab" role="tab" tabindex="-1" aria-labelledby="ui-id-5" aria-selected="false" aria-expanded="false" aria-controls="rating">
    <a href="#rating" data-toggle="tab" class="indanchr" role="presentation" tabindex="-1" id="ui-id-5">Rating</a>
      
    </li>
    
  </ul>


  <div class="tab-content5">
    
    <!-- tab1 start -->
    <div class="tab-pane ui-tabs-panel ui-widget-content ui-corner-bottom" id="ovrvw" aria-labelledby="ui-id-1" role="tabpanel" aria-hidden="false" style="display: block;">
      <div class="innertabsxl">
        <div class="comenhtlsum">
          <li class="list-unstyled">
                    <label><i class="fa fa-map-marker"></i> Location</label>
                    <span><?=$package->package_location;?></span>
                  </li>
                  <li class="list-unstyled">
                    <label><i class="fa fa-clock"></i> Duration</label>
                    <span><?php echo isset($package->duration)?$package->duration:0; ?> Days / <?php echo isset($package->duration)?($package->duration - 1):0; ?> Nights (<?php echo sprintf("%02d",$package->activity_duration_hour).':'.sprintf("%02d",$package->activity_duration_min).' Hrs'; ?>)</span>
                  </li>
                  <li class="list-clock">
                    <label><i class="fa fa-language"></i> Language</label>
                    <span>English</span>
                  </li>
                  <li class="list-unstyled">
                    <label><i class="fa fa-check"></i> Available Weekdays</label>
                    <span><?=$weekDay?></span>
                  </li>
                  <li class="list-unstyled">
                    <label><i class="fa fa-users"></i> Total Pax</label>
                    <span><?=$search_data['adult']+$search_data['child']?></span>
                  </li>
                  <li class="list-unstyled">
                    <label><i class="fa fa-info-circle"></i> Details</label>
                    <span><?=$package->package_description?></span>
                  </li>
                  <li class="list-unstyled">
                    <label><i class="fa fa-tag"></i> Theme</label>
                    <span><?=$package->theme_types;?></span>
                  </li>
                  <?php 
                  $add_time = json_decode($package->add_time);
                  ?>
                  <li class="list-unstyled">
                    <label><i class="fa fa-tag"></i> Start Time</label>
                    <span><?=$add_time[0];?></span>
                  </li>
                  <?php
                  if($package->offer_ticket=='Y') {
                    $offer_tickt = 'Yes';
                   ?>
                  <li class="list-unstyled">
                    <label><i class="fa fa-tag"></i> Offer Ticket</label>
                    <span><?=$offer_tickt;?></span>
                  </li>
                  <div class="img_tour">

                                        <a target="_blank" href="<?php echo $GLOBALS['CI']->template->domain_upload_acty_images(basename($package->image_ticket)); ?>">
                                         <img src="<?php echo $GLOBALS['CI']->template->domain_upload_acty_images(basename($package->image_ticket)); ?>" alt="" /></a>
                                    </div>
                  <?php
                  }
                   ?>
                  <li class="list-unstyled">
                    <label><i class="fa fa-sign-language"></i> Physical Difficulty Level</label>
                    <?php if($package->difficulty_easy == 'E'){ ?>
                        <span><?php echo 'Easy'; ?></span>
                   <?php }  ?>
                    <?php if($package->difficulty_easy == 'M'){ ?>
                        <span><?php echo 'Moderate'; ?></span>
                   <?php }  ?>
                    <?php if($package->difficulty_easy == 'C'){ ?>
                        <span><?php echo 'Challenging'; ?></span>
                   <?php }  ?>
                    <?php if($package->difficulty_easy == 'O'){ 
                          if(($package->other_difficulty == 0) || ($package->other_difficulty == ' ' )) { ?>
                             <span><?php echo 'Other'; ?></span>
                              <?php } else { ?>
                                <span><?php echo 'Other - '.$package->other_difficulty ; ?></span>
                           <?php } } ?>
                  </li>

                   <li class="list-unstyled">
                    <label><i class="fa fa-address-book"></i> Pickup Address</label>
                    <span><?=$package->address;?></span>
                  </li>
                    <?php
                    if($package->meeting_instruction)
                    {
                    ?>
                   <li class="list-unstyled">
                    <label><i class="fa fa-info-circle"></i> Meeting Instructions</label>
                    <span><?=$package->meeting_instruction;?></span>
                  </li>
                  <?php } ?>

                   <?php
                    if($helth_restrictions)
                    {
                      ?>
                      <li class="list-unstyled">
                    <label><i class="fa fa-info-circle"></i> Helth Instructions</label>
                      <?php
                      foreach($helth_restrictions as $hr)
                      {
                    ?>
                   
                    <span><?=$hr['health_instructions'];?></span><br>
                  
                  <?php } 

                  ?>
                  </li>
                  <?php
                } ?>


                  <li class="list-unstyled hide" id="traveller_pickup">
                    <label><i class="fa fa-map-marker"></i>  Traveler Pick up Location</label>
                    <span><?=$package->traveller_cityname;?>, <?=$pickup_country;?></span>
                  </li>
          
        </div>
        <div class="linebrk"></div>
        
      </div>
      
    </div>
    <!-- tab1 end -->

     <!-- tab2 start -->
    <div class="tab-pane ui-tabs-panel ui-widget-content ui-corner-bottom" id="itnery" aria-labelledby="ui-id-2" role="tabpanel" aria-hidden="false" style="display: block;">
    <div class="lettrfty">
                                          <?php $i=1; ?>
                            <?php foreach($package_itinerary as $pi){
                              // debug($pi['sub_category_desc']);die;
                              $sub_dec = json_decode($pi->sub_category_desc,true);
                              // debug($sub_dec);die;
                             ?>
                            <div class="htlrumrowxl">
                                <div class="hotelistrowhtl">

                                    <div class="daytrip">
                                            <strong>Day</strong>
                                            <b><?php echo $i; ?></b>
                                     </div>

                                    <div class="clear"></div>
                                    <div class="dayecd">
                                     <div class="img_tour">


                                         <img src="<?php echo $GLOBALS['CI']->template->domain_upload_acty_images(basename($pi->itinerary_image)); ?>" alt="" />
                                    </div>
                                        <div class="hotelhed"><?php echo $pi->place; ?></div>
                                        <span class="singleadrspara"><?php echo $pi->itinerary_description; ?></span>
                                        <?php
                                        if(!empty($sub_dec))
                                          {                                  
                                        foreach($sub_dec as $sd)
                                        {
                                        ?>
                                        <br>
                                        <span class="singleadrspara"><?php echo $sd; ?></span>
                                      <?php } } ?>
                                    </div>

                                </div>

                            </div>
                            <?php $i++; ?>
                            <?php } ?>
                                      </div>
      
    </div>
    <!-- tab2 end -->


     <!-- tab1 start -->
    <div class="tab-pane ui-tabs-panel ui-widget-content ui-corner-bottom" id="tandc" aria-labelledby="ui-id-3" role="tabpanel" aria-hidden="false" style="display: block;">
      <div class="lettrfty">
                                           <div class="col-md-12 nopad">
                                    <div class="col-md-12">
                                    <h3 class="hedft">Price Includes: </h3>
                                    <ul class="checklist checklistxl">
                                        <li><?php echo isset($package_price_policy->price_includes)?($package_price_policy->price_includes):"No Description"; ?></li>
                                    </ul>
                                </div>
                                </div>
                                <div class="linebrk"></div>
                                <div class="col-md-12 nopad">
                                    <div class="col-md-12">
                                    <h3 class="hedft">Inclusion Policy: </h3>
                                    <ul class="checklist checklistxl">
                                        <li><?php echo isset($package->inclusion_policy)?($package->inclusion_policy):"No Description"; ?></li>
                                    </ul>
                                </div>


                                </div>
                                <div class="linebrk"></div>
                                <div class="col-md-12 nopad">
                                    <div class="col-md-12">
                                    <h3 class="hedft">Price Excludes: </h3>
                                    <ul class="checklist checklistxl">
                                        <li><?php echo isset($package_price_policy->price_excludes)?($package_price_policy->price_excludes):""; ?></li>
                                    </ul>
                                </div>
                                </div>
                                <div class="linebrk"></div>
                                <div class="col-md-12 nopad">
                                    <div class="col-md-12">
                                    <h3 class="hedft">Exclusion Policy: </h3>
                                    <ul class="checklist checklistxl">
                                        <li><?php echo isset($package->exclusion_policy)?($package->exclusion_policy):"No Description"; ?></li>
                                    </ul>
                                </div>


                                </div>
                                <div class="linebrk"></div>
                                <div class="col-md-12 nopad">
                                    <div class="col-md-12">
                                    <h3 class="hedft">Cancellation Terms: </h3>
                                    <ul class="checklist checklistxl">
                                        <li><?php echo isset($package_cancel_policy->cancellation_advance)?($package_cancel_policy->cancellation_advance):""; ?></li>
                                    </ul>
                                </div>
                                <div class="linebrk"></div>
                                <?php
// debug($package->refundable);die;

                                 if(!empty($package_cancel_policy->cancellation_penality) && $package->refundable != "N"){ ?>
                                <div class="col-md-12 nopad">
                                    <div class="col-md-12">
                                    <h3 class="hedft">Refundable Terms: </h3>
                                    <ul class="checklist checklistxl">
                                        <li><?php echo isset($package_cancel_policy->cancellation_penality)?($package_cancel_policy->cancellation_penality):""; ?></li>
                                    </ul>
                                </div>

                                </div>
                                <div class="linebrk"></div>
                              <?php } ?>
                                 <div class="col-md-12 nopad">
                                    <div class="col-md-12">
                                    <h3 class="hedft">Information travelers/ Trip: </h3>
                                    <ul class="checklist checklistxl">
                                        <li><?php echo isset($package->infor_travellers)?($package->infor_travellers):""; ?></li>
                                    </ul>
                                </div>

                                </div>
                                <div class="linebrk"></div>
                                <div class="col-md-12 nopad">
                                    <div class="col-md-12">
                                    <h3 class="hedft">Contact Address: </h3>
                                    <ul class="checklist checklistxl">
                                        <li><?php echo isset($package->contact_address)?($package->contact_address):""; ?></li>
                                    </ul>
                                </div>

                                </div>
                                <div class="linebrk"></div>
                                <div class="col-md-12 nopad">
                                    <div class="col-md-12">
                                    <h3 class="hedft">Contact Email: </h3>
                                    <ul class="checklist checklistxl">
                                        <li><?php echo isset($package->contact_email)?($package->contact_email):""; ?></li>
                                    </ul>
                                </div>

                                </div>
                                </div>
                                      </div>
      
    </div>
    <!-- tab1 end -->

     <!-- tab1 start -->
    <div class="tab-pane ui-tabs-panel ui-widget-content ui-corner-bottom" id="gallery" aria-labelledby="ui-id-4" role="tabpanel" aria-hidden="false">
 <div class='form-group'>
                          <label class='control-label col-sm-3' for='validation_company'>Excursion Gallery Image :</label>
                            <?php 
                            $gallery = json_decode($packdata->gallery_image);
                            foreach ($gallery as $key => $value) {
                              ?>
                              <div class="gallery">
                                  <a target="_blank" href="<?php echo $GLOBALS['CI']->template->domain_upload_acty_images($value); ?>">
                                    <img  src="<?php echo $GLOBALS['CI']->template->domain_upload_acty_images($value); ?>"  alt="Gallery Images" width="600" height="400">
                                  </a>
                                </div>
                                 
                                 <?php
                            }
                            ?>
                          
                           
                        </div> 
      <div id="owl-demobaner1" class="owl-carousel owlindex3 owl-theme owl-loaded owl-drag">
      <?php

       if(!empty($package->gallery_image)){ 
        $gallery = json_decode($package->gallery_image);
        ?>
          <?php foreach($gallery as $ptp){ ?>
         <div class="item">
          <div class="f-deal slide imgslwidth" data-slide-index="0">                                    
            <div class="f-deal-img imgs">                                        
              <img class="fixedgalhgt" src="<?php echo $GLOBALS['CI']->template->domain_upload_acty_images($ptp); ?>" alt="" /> 
             
             </div> 
          
          </div>
              <!-- <div class="xlimg"></div> -->
        </div>
        <?php } ?>
      <?php } ?>
      </div>
    </div>
      
    <!-- tab1 end -->

     <!-- tab1 start -->
    <div class="tab-pane ui-tabs-panel ui-widget-content ui-corner-bottom" id="rating" aria-labelledby="ui-id-5" role="tabpanel" aria-hidden="false" style="display: block;">
      <div class="innertabs">
        <div class="ratingusr">
            <strong><?php echo $package->rating; ?></strong>
            <span class="ratingimg"> Star<img src="/images/user-rating-<?php echo $package->rating; ?>.png" alt="" /></span>
            <b> User Rating</b>
        </div>
      </div>
      
    </div>
    <!-- tab1 end -->


    
  </div>
  
</div>

                    






<!-- early -->
                 <!--    <div class="masthead-wrapper act_wrp">

                        <div class="col-md-12 actimgdiv">
                            <div class="ovrimpdiv">
                                <div class="tab-content">

                                    <div class="tab-pane active">
                                     <div class="innertabs">
                                      <h3 class="mobile_view_header"><i class="fal fa-list-alt"></i> Overview</h3>
                                      <div class="clearfix"></div>

                                      <div class="lettrfty" style="display: none;" >
                                         <?php echo isset($package->package_description)?($package->package_description):"No Description"; ?>
                                         <?php 

$tours_data = json_encode($package);
$tourss_itinerary=json_encode($package_itinerary);
$tourss_itinerary_dw=json_encode($package_price_policy);
$tours_price=json_encode($package_cancel_policy);
$tours_data = urlencode($tours_data);
$tourss_itinerary = urlencode($tourss_itinerary);
$tourss_itinerary_dw = urlencode($tourss_itinerary_dw);
$tours_price = urlencode($tours_price);
       ?>
                                 <form method="post" action="<?=base_url().'index.php/voucher/activity_pdf'?>" target="_blank">
                                  <input type="hidden" name="tour_data" value="<?php echo $tours_data?>">
                                  <input type="hidden" name="tours_itinerary" value="<?php echo $tourss_itinerary?>">
                                  <input type="hidden" name="tours_itinerary_dw" value="<?php echo $tourss_itinerary_dw?>">
                                  <input type="hidden" name="tour_price" value="<?php echo $tours_price?>">
                                  <input type="submit" value="Export PDF" class="btn btn-warning pull-right" style='margin-top:20px;'>
                                </form>

                                      </div>

                                     </div>
                                    </div>

                                    <div class="tab-pane active">
                                     <div class="innertabs">
                                      <h3 class="mobile_view_header"><i class="fal fa-list-alt"></i> Detailed Itinerary</h3>
                                      <div class="clearfix"></div>

                                      <div class="lettrfty">
                                          <?php $i=1; ?>
                            <?php foreach($package_itinerary as $pi){ ?>
                            <div class="htlrumrowxl">
                                <div class="hotelistrowhtl">

                                    <div class="daytrip">
                                            <strong>Day</strong>
                                            <b><?php echo $i; ?></b>
                                     </div>

                                    <div class="clear"></div>
                                    <div class="dayecd">
                                     <div class="img_tour">


                                         <img src="<?php echo $GLOBALS['CI']->template->domain_upload_acty_images(basename($pi->itinerary_image)); ?>" alt="" />
                                    </div>
                                        <div class="hotelhed"><?php echo $pi->place; ?></div>
                                        <span class="singleadrspara"><?php echo $pi->itinerary_description; ?></span>
                                    </div>

                                </div>

                            </div>
                            <?php $i++; ?>
                            <?php } ?>
                                      </div>

                                     </div>
                                    </div>


                                    <div class="tab-pane active">
                                     <div class="innertabs">
                                      <h3 class="mobile_view_header"><i class="fal fa-list-alt"></i> Terms & Conditions</h3>
                                      <div class="clearfix"></div>

                                      <div class="lettrfty">
                                           <div class="col-md-12 nopad">
                                    <div class="col-md-12">
                                    <h3 class="hedft">Price Includes: </h3>
                                    <ul class="checklist checklistxl">
                                        <li><?php echo isset($package_price_policy->price_includes)?($package_price_policy->price_includes):"No Description"; ?></li>
                                    </ul>
                                </div>


                                </div>
                                <div class="linebrk"></div>
                                <div class="col-md-12 nopad">
                                    <div class="col-md-12">
                                    <h3 class="hedft">Price Excludes: </h3>
                                    <ul class="checklist checklistxl">
                                        <li><?php echo isset($package_price_policy->price_excludes)?($package_price_policy->price_excludes):""; ?></li>
                                    </ul>
                                </div>


                                </div>
                                <div class="linebrk"></div>
                                <div class="col-md-12 nopad">
                                    <div class="col-md-12">
                                    <h3 class="hedft">Cancellation Advance: </h3>
                                    <ul class="checklist checklistxl">
                                        <li><?php echo isset($package_cancel_policy->cancellation_advance)?($package_cancel_policy->cancellation_advance):""; ?></li>
                                    </ul>
                                </div>
                                <div class="linebrk"></div>
                                <div class="col-md-12 nopad">
                                    <div class="col-md-12">
                                    <h3 class="hedft">Cancellation penality: </h3>
                                    <ul class="checklist checklistxl">
                                        <li><?php echo isset($package_cancel_policy->cancellation_penality)?($package_cancel_policy->cancellation_penality):""; ?></li>
                                    </ul>
                                </div>

                                </div>
                                </div>
                                      </div>

                                     </div>
                                    </div>

                                    <div class="tab-pane active">
                                     <div class="innertabs">
                                      <h3 class="mobile_view_header"><i class="fal fa-list-alt"></i> Terms & Conditions</h3>
                                      <div class="clearfix"></div>

                                      <div class="lettrfty">
                                           <div class="col-md-12 nopad">
                                    <div class="col-md-12">
                                    <h3 class="hedft">Price Includes: </h3>
                                    <ul class="checklist checklistxl">
                                        <li><?php echo isset($package_price_policy->price_includes)?($package_price_policy->price_includes):"No Description"; ?></li>
                                    </ul>
                                </div>


                                </div>
                                <div class="linebrk"></div>
                                <div class="col-md-12 nopad">
                                    <div class="col-md-12">
                                    <h3 class="hedft">Price Excludes: </h3>
                                    <ul class="checklist checklistxl">
                                        <li><?php echo isset($package_price_policy->price_excludes)?($package_price_policy->price_excludes):""; ?></li>
                                    </ul>
                                </div>


                                </div>
                                <div class="linebrk"></div>
                                <div class="col-md-12 nopad">
                                    <div class="col-md-12">
                                    <h3 class="hedft">Cancellation Advance: </h3>
                                    <ul class="checklist checklistxl">
                                        <li><?php echo isset($package_cancel_policy->cancellation_advance)?($package_cancel_policy->cancellation_advance):""; ?></li>
                                    </ul>
                                </div>
                                <div class="linebrk"></div>
                                <div class="col-md-12 nopad">
                                    <div class="col-md-12">
                                    <h3 class="hedft">Cancellation penality: </h3>
                                    <ul class="checklist checklistxl">
                                        <li><?php echo isset($package_cancel_policy->cancellation_penality)?($package_cancel_policy->cancellation_penality):""; ?></li>
                                    </ul>
                                </div>

                                </div>
                                </div>
                                      </div>

                                     </div>
                                    </div>
                                    

                                    <div class="tab-pane active">
                                     <div class="innertabs">
                                      <h3 class="mobile_view_header"><i class="fal fa-list-alt"></i> Rating</h3>
                                      <div class="clearfix"></div>
                                        

                                         <div class="ratingusr">
                                    <strong><?php echo $package->rating; ?></strong>
                                    <span class="ratingimg">Star<img src="/images/user-rating-<?php echo $package->rating; ?>.png" alt="" /></span>
                                    <b>User Rating</b>
                                </div>
                                    

                                     </div>
                                    </div>


                                   

                                </div>
                            </div>
                        </div>

                    </div> -->
 
                   <div class="clearfix"></div>


                    <div class="detailtab hide">
                        <ul class="nav nav-tabs trul">
                         
                          <li><a href="#gallery" data-toggle="tab">Gallery</a></li>
                           <li><a href="#rating" data-toggle="tab">Rating</a></li>
                        </ul>
                        <div class="tab-content5">

                     
                       

                       

                        <!-- Map-->
                        <!-- <div class="tab-pane" id="gallery">
                          <div class="innertabs">

                                <div id="owl-demobaner1" class="owl-carousel indexbaner">
                                <?php if(!empty($package_traveller_photos)){ ?>
                                    <?php foreach($package_traveller_photos as $ptp){ ?>
                                   <div class="item">
                                        <div class="xlimg"><img class="fixedgalhgt" src="<?php echo $GLOBALS['CI']->template->domain_upload_acty_images($ptp->traveller_image); ?>" alt="" /></div>
                                  </div>
                                  <?php } ?>
                                <?php } ?>
                                </div>
              </div>
                        </div> -->
                        <!-- Map End-->

                        <!-- Reviews-->
                        <div class="tab-pane" id="rating">
                          <div class="innertabs">
                              <div class="ratingusr">
                                  <strong><?php echo $package->rating; ?></strong>
                                    <span class="ratingimg">Star<!-- <img src="/images/user-rating-<?php echo $package->rating; ?>.png" alt="" /> --></span>
                                    <b>User Rating</b>
                                </div>
                            </div>
                        </div>
                        <!-- Reviews End-->

                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
    </div>
</div>

<div id="subtqry" class="wellme minwnwidth" style="display: none;">
    <div class="popuperror" style="display:none;"></div>
    <div  class="pophed"> Send Enquiry For <?php echo $package->package_name; ?></div>
    <div class="signdiv">
      <form action="<?php echo base_url(); ?>index.php/activities/enquiry" method="post">
        <input type="hidden" class="fulwishxl" id="package_id"  name="package_id" value="<?php echo $package->package_id; ?>"/>
        <div class="rowlistwish">
            <div class="col-md-4">
              <span class="qrylbl">Name<span class="dfman">*</span></span>
            </div>
            <div class="col-md-8">
              <input type="text" class="fulwishxl alpha" id="first_name"  name="first_name" value="<?=@$this->entity_name?>" required/>
              <span id="verificationCodeErr" style="color:red; font-size: small"></span>
            </div>
        </div>
        <div class="rowlistwish">
            <div class="col-md-4">
              <span class="qrylbl"> Contact Number<span class="dfman">*</span> </span>
            </div>
            <div class="col-md-8">
              <input type="text" class="fulwishxl numeric" maxlength='15' id="phone1" name="phone1" value="<?=@$this->entity_phone?>" required/>
              <span id="verificationCodeErr" style="color:red; font-size: small"></span>
            </div>
        </div>
        <div class="rowlistwish">
            <div class="col-md-4">
              <span class="qrylbl"> Email<span class="dfman">*</span> </span>
            </div>
            <div class="col-md-8">
              <input type="email" class="fulwishxl" id="activiest_email" name="email" value="<?=@$this->entity_email?>" required/>
              <span id="verificationCodeErr" style="color:red; font-size: small"></span>
            </div>
        </div>
        <div class="rowlistwish">
            <div class="col-md-4">
              <span class="qrylbl">Departure Place<span class="dfman">*</span> </span>
            </div>
            <div class="col-md-8">
              <input type="text" class="fulwishxl" id="place1" name="place1" required/>
              <span id="verificationCodeErr" style="color:red; font-size: small"></span>
            </div>
        </div>
          <div class="rowlistwish">
              <div class="col-md-4">
                  <span class="qrylbl">Number of Passengers<span class="dfman">*</span> </span>
              </div>
              <div class="col-md-8">
                  <input type="text" class="fulwishxl numeric " id="pax" name="pax" required maxlength="6" />
                  <span id="verificationCodeErr" style="color:red; font-size: small"></span>
              </div>
          </div>
        <div class="rowlistwish">
            <div class="col-md-4">
              <span class="qrylbl">Message<span class="dfman">*</span> </span>
            </div>
            <div class="col-md-8">
              <input type="text" class="fulwishxl" id="message1" name="message1" required/>
              <span id="verificationCodeErr" style="color:red; font-size: small"></span>
            </div>
        </div>


      <div class="clear"></div>
      <div class="downselfom">
      <div class="col-md-10 col-xs-12">
        <div class="col-md-6 col-xs-12 "><input type="cancel"  value="Cancel" class="btn colorcancel closequery" id="hideCancelPopup" readonly/></div>
        <div class="col-md-6 col-xs-12"><input type="submit"  value="Send Enquiry" class="btn colorsave" id="send_enquiry_button"/></div>
        </div>
      </div>
      </form>
    </div>
</div>




<!--<script src="<?php echo $GLOBALS['CI']->template->template_js_dir('jquery.masonry.min.js') ?>" type="text/javascript"></script> -->


<script type="text/javascript">
$('#transfer_option').on('change', function() {



        


          var transfer_option_type = $("#transfer_option").val();

          if(transfer_option_type != "W")
          {
            $("#traveller_pickup").removeClass('hide')
            $("#is_pickup_available").val("YES")
          }
          else
          {
            $("#traveller_pickup").addClass('hide')
            $("#is_pickup_available").val("NO")
          }

          <?php 
          $transport_option = $formated_data[$key]['transport_price'];
          for($i=0; $i<count($transport_option); $i++){
            $transport = $transport_option[$i]['transfer_option'];
            $total_price = $transport_option[$i]['total_price'];
            $duration = $transport_option[$i]['duration']; ?>
            var duration = '<?=$duration?>';
            var seasonsDateValue = $("#seasonsDateValue").val();
            var cur_transport = '<?=$transport?>';
            if((cur_transport==transfer_option_type) && (seasonsDateValue==duration) ){
               var total_price = '<?=$total_price?>';
            }
         <?php  }
          ?>
          //var diff_price = '<?=$diff_price?>';
          var diff_price = 0;

          $.ajax({
            url:app_base_url+'activities/change_transport_type',
            type:'POST',
            data:{total_price:total_price,diff_price:diff_price},
            success:function(str){
          $('.currency-amount').html('');
          $('.currency-amount').text(str);
          $('#transport_type').val(transfer_option_type);
               
            },
            error:function(){
            }
         }) ;

          
      });
$(document).ready(function(){
    $("#hotel_top").owlCarousel({
        items : 1,
        itemsDesktop : [1000,1],
        itemsDesktopSmall : [900,1],
        itemsTablet: [600,1],
        itemsMobile : [479,1],
        navigation : false,
        pagination : true,
        autoPlay : true
      });

    // $("#owl-demobaner1").owlCarousel({
    //     items : 1,
    //     itemsDesktop : [1000,1],
    //     itemsDesktopSmall : [900,1],
    //     itemsTablet: [600,1],
    //     itemsMobile : [479,1],
    //     navigation : true,
    //     pagination : true,
    //     autoHeight : true,
    //     autoPlay : true
    //   });

    $('#sendquery').on('click', function(e) {
        e.preventDefault();

        $('#subtqry').provabPopup({
                    modalClose: true,
                    closeClass: 'closequery',
                    zIndex: 100005
        });
    });

});</script>

<script type="text/javascript">
$(document).ready(function(){
    $( ".detailtab" ).tabs();
//  Check Radio-box
    $(".rating input:radio").attr("checked", false);
    $('.rating input').click(function () {
        $(".rating span").removeClass('checked');
        $(this).parent().addClass('checked');
    });

    $('input:radio').change(
    function(){

        var userRating = this.value;
        var pkg_id=$('#pkg_id').val();

        //alert(userRating);
        //alert(pkg_id);
       var str=pkg_id+','+userRating;
        $.ajax({
            url:app_base_url+'activities/package_user_rating',
            type:'POST',
            data:'rate='+str,
            success:function(msg){
                //alert(msg);
               $('#msg_pak').show();
               $('#msg_pak').text('Thank you for rating this package').css('color','green').fadeOut(3000);
            },
            error:function(){
            }
         }) ;

    });

});
 $('#date_of_travel').change(
    function(){
        var travel_date = this.value;
        var pkg_id=$('#package_id').val();
        var trans_desc = '';
        // var price=$('#agent_price_with_markup').val();
        var transfer_option=$('#transfer_option').val();
        var diff_price =0;
        var price_change = get_date_price(travel_date,transfer_option,diff_price);
        if(transfer_option=='W'){
          trans_desc='Without Transfers';
        }else if(transfer_option=='W'){
          trans_desc='Sharing Transfers';
        }else{
          trans_desc='Private Transfers';       
        }
        if(price_change==undefined){
          alert(''+trans_desc+' is not available on the selected date');
          $('#date_of_travel').val('');
          return false;
        }
        var price_change = price_change.split('*');
        var price = price_change[0];
        var date_duration = price_change[1];
        $.ajax({
            url:app_base_url+'activities/get_cancellation_policy_details',
            type:'POST',
            data:{travel_date:travel_date,pkg_id:pkg_id,price:price,transfer_option:transfer_option,diff_price:diff_price,date_duration:date_duration},
            success:function(msg){
                var msg = msg.split('*');
               $('.fa-times-circle').show();
               $('#cancellation_policy').show();
               <?php 
               if($package->refundable == "N")
               {
                ?> $('#cancellation_policy').text("NON Refundable"); <?php
               }
               else
               {
                ?> $('#cancellation_policy').text(msg[0]); <?php
               }
               ?>
               
               $('#cancellationPolicy').val(msg[0]);
               $('#cancltnPlcy_avble').val(msg[1]);
               $('.currency-amount').html('');
	             $('.currency-amount').text(price);
	             $('#seasonsDateValue').val(date_duration);
               $('#transfer_option').html('');
               $('#transfer_option').html(msg[2]);
               $('#agent_price_with_markup').val(price);

            },
            error:function(){
            }
         }) ;

    });

function get_date_price(travel_date,transfer_option,diff_price){
	<?php 
          $transport_option = $formated_data[$key]['transport_price'];
          for($i=0; $i<count($transport_option); $i++){
            $transport = $transport_option[$i]['transfer_option'];
            $total_price = $transport_option[$i]['total_price'];
            $duration = $transport_option[$i]['duration']; 
            $seasons_date_wise = explode(' - ', $duration);
			$seasons1 = explode('/', $seasons_date_wise[0]);
			$fromSeasons=$seasons1[2].'-'.$seasons1[1].'-'.$seasons1[0];
			$seasons2 = explode('/', $seasons_date_wise[1]);
			$toSeasons=$seasons2[2].'-'.$seasons2[1].'-'.$seasons2[0];
			 ?> 
            var duration = '<?=$duration?>';
            var fromSeasons = '<?=$fromSeasons?>';
            var toSeasons = '<?=$toSeasons?>';
            var cur_transport = '<?=$transport?>';
            var d = new Date(travel_date);
			var travel_date = $.datepicker.formatDate('yy-mm-dd', d);
			if( (fromSeasons<=travel_date)&&(toSeasons>=travel_date) && (cur_transport==transfer_option) ){
               var total_price = '<?=$total_price?>';	
	 			total_price = parseInt(total_price)+parseInt(diff_price);	
	 			return total_price+'*'+duration; 	
	 				}
         <?php  }
          ?>
}
</script>

<script type="text/javascript">
  $(document).ready(function() {
    var date_range = $('#date_range').val();
    var date_count = $('#date_count').val();
    var check_in = [];
    var week = [];
    for(var i=0; i<date_count; i++){
    	var date_from = $('#date_from'+i).val();
    	var date_to = $('#date_to'+i).val();    	
    	var check_in_dates = [date_from, date_to];
    	check_in[i] = check_in_dates;
    	week[i] = $('#week_'+i).val();

    }
    // var vall= date_range.split(' - ');
    // var val_from = vall[0].split('/');
    // val_from = val_from[0]+'-'+val_from[1]+'-'+val_from[2];
    // var val_to = vall[1].split('/');
    // val_to = val_to[0]+'-'+val_to[1]+'-'+val_to[2];
    // var d = new Date();
    // var strDate = d.getDate() + "-" + (d.getMonth()+1) + "-" + d.getFullYear();
    // if(val_from<strDate){
    //   val_from = strDate;
    // }
    // var check_in = date_range;
    var date = new Date();
    $( "#date_of_travel" ).datepicker({
       minDate:0,
    });
/*$('#date_of_travel').datepicker({
  dateFormat: 'd-M-y',
  minDate: date,
  beforeShowDay: function(date) {
    var string = jQuery.datepicker.formatDate('M D d, yy', date);
    for (var i = 0; i < check_in.length; i++) {
      if (Array.isArray(check_in[i])) {
      	var day = date.getDay();
        var from = new Date(check_in[i][0]);
        var to = new Date(check_in[i][1]);
        var current = new Date(string);
        var obj =  week[i];
        var weekDay = JSON.parse(obj);
	      var trans_ary = [];
	      var k=0;
	      var j=0;
	      for(var s=0;s<7;s++){
	        j=s+1;
	      if(jQuery.inArray(''+j+'', weekDay) != -1) {
	        if(j==7){
	        trans_ary [k] =  0;
	        }else{
	        trans_ary [k] =  j;
	        }
	        k++;
	        }
	      }
	      if(trans_ary.length==1){
	      	if (current >= from && current <= to) return [check_in.indexOf(string) == -1], [day == trans_ary[0],""];
                
              }
              if(trans_ary.length==2){
                if (current >= from && current <= to) return [check_in.indexOf(string) == -1], [day == trans_ary[0] || day == trans_ary[1] ,""];
              }
              if(trans_ary.length==3){
               if (current >= from && current <= to) return [check_in.indexOf(string) == -1],
        		[day == trans_ary[0] || day == trans_ary[1] || day == trans_ary[2],""]
              }
              if(trans_ary.length==4){
                if (current >= from && current <= to) return [check_in.indexOf(string) == -1], [day == trans_ary[0] || day == trans_ary[1] || day == trans_ary[2] || day == trans_ary[3],""];
              }
              if(trans_ary.length==5){
                if (current >= from && current <= to) return [check_in.indexOf(string) == -1], [day == trans_ary[0] || day == trans_ary[1] || day == trans_ary[2] || day == trans_ary[3] || day == trans_ary[4],""];
              }
              if(trans_ary.length==6){
                if (current >= from && current <= to) return [check_in.indexOf(string) == -1], [day == trans_ary[0] || day == trans_ary[1] || day == trans_ary[2] || day == trans_ary[3] || day == trans_ary[4] || day == trans_ary[5],""];
              }
              if(trans_ary.length==7){
                if (current >= from && current <= to) return [check_in.indexOf(string) == -1], [day == trans_ary[0] || day == trans_ary[1] || day == trans_ary[2] || day == trans_ary[3] || day == trans_ary[4] || day == trans_ary[5] || day == trans_ary[6] ,""];
              }
        
        

      }
    }
    return false;
  }
});*/
// $("#date_of_travel_demo").datepicker({
//            dateFormat:'dd-mm-yy',
//            minDate:  val_from,
//            beforeShowDay: function(date){ 
//             var day = date.getDay();
//               var weekDay =  <?=json_encode($weekDaysDate)?>;
//               var trans_ary = [];
//               var k=0;
//               var j=0;
//               for(var i=0;i<7;i++){
//                 j=i+1;
//               if(jQuery.inArray(''+j+'', weekDay) != -1) {
//                 if(j==7){
//                 trans_ary [k] =  0;
//                 }else{
//                 trans_ary [k] =  j;
//                 }
//                 k++;
//                 }
//               }
//               if(trans_ary.length==1){
//                 return [day == trans_ary[0],""];
//               }
//               if(trans_ary.length==2){
//                 return [day == trans_ary[0] || day == trans_ary[1] ,""];
//               }
//               if(trans_ary.length==3){
//                 return [day == trans_ary[0] || day == trans_ary[1] || day == trans_ary[2],""];
//               }
//               if(trans_ary.length==4){
//                 return [day == trans_ary[0] || day == trans_ary[1] || day == trans_ary[2] || day == trans_ary[3],""];
//               }
//               if(trans_ary.length==5){
//                 return [day == trans_ary[0] || day == trans_ary[1] || day == trans_ary[2] || day == trans_ary[3] || day == trans_ary[4],""];
//               }
//               if(trans_ary.length==6){
//                 return [day == trans_ary[0] || day == trans_ary[1] || day == trans_ary[2] || day == trans_ary[3] || day == trans_ary[4] || day == trans_ary[5],""];
//               }
//               if(trans_ary.length==7){
//                 return [day == trans_ary[0] || day == trans_ary[1] || day == trans_ary[2] || day == trans_ary[3] || day == trans_ary[4] || day == trans_ary[5] || day == trans_ary[6] ,""];
//               }
//           },
//         maxDate:  val_to,
        
//         });



});
    $(document).ready(function(){


        if('<?php echo @$current_user_details['phone_code']?>'){
            $("#pn_country_code").val('<?php echo @$current_user_details['phone_code']."_".@$current_user_details['phone_country']." ".$current_user_details['phone_code'];?>');
        }
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
        $('#send_enquiry_button').click(function() { 
            $first_name    = $('#first_name').val();
            $phone   = $('#phone1').val();
            $email  = $('#activiest_email').val(); 
            $place = $('#place1').val(); 
            $pax  = $('#pax').val();
            $message  = $('#message1').val(); 

            $package_id = "<?php echo $package->package_id; ?>";
            

            if($first_name==''  || $phone==''|| $email=='' || $place=='' || $pax==''|| $message=='') 
            {  
              return false;
            }
            // $.post('<?=base_url();?>index.php/tours/ajax_enquiry_activities', {'first_name': $first_name,'phone': $phone,'email': $email,'place':$place,'pax': $pax,'message': $message,'package_id':$package_id}, function(data) {
            //     $('#enquiry_form').trigger("reset");
            //     $('#alertp').removeClass('hide');
            //     $('#enquiry_form').addClass('hide');
            // });
        });
        // $('#send_enquiry_button').click(function() {
        //     $title    = $('#title').val();
        //     $ename    = $('#ename').val();
        //     $elname   = $('#elname').val();
        //     $emobile  = $('#emobile').val();
        //     $eemail   = $('#eemail').val();
        //     $ecomment = $('#ecomment').val();
        //     $tour_id  = $('#tour_id').val();
        //     $number_of_passengers  = $('#number_of_passengers').val();
        //     $durations  = $('#durations').val();
        //     $departure_date  = $('#departure_date').val();

        //     $pn_country_code  = $('#pn_country_code').val();
        //     $pn_country_code  =  $pn_country_code.split("_");
        //     $pn_country_code = $pn_country_code[0];
        //     // alert($pn_country_code);

        //     $tours_itinerary_id = $('#tours_itinerary_id').val();
        //     console.log(title);
        //     console.log();
        //     console.log();
        //     console.log();
        //     console.log();

        //     if($ename==''  || $eemail==''|| $number_of_passengers=='' || $departure_date=='') { return false; }
        //     $.post('<?=base_url();?>index.php/tours/ajax_enquiry_activities', {'title': $title,'ename': $ename,'elname': $elname,'pn_country_code':$pn_country_code,'emobile': $emobile,'eemail': $eemail,'ecomment': $ecomment,'tour_id': $tour_id,'tours_itinerary_id': $tours_itinerary_id,'number_of_passengers':$number_of_passengers,'durations':$durations,'departure_date':$departure_date}, function(data) {
        //         $('#enquiry_form').trigger("reset");
        //         $('#alertp').removeClass('hide');
        //         $('#enquiry_form').addClass('hide');
        //     });
        // });

        $('#enquiry_pop_button').click(function() {
            $('#alertp').addClass('hide');
            $('#enquiry_form').removeClass('hide');
        });
    });


</script>
<script>
        $(document).ready(function() {
            $("#owl-demobaner1").owlCarousel({

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

</body></html>