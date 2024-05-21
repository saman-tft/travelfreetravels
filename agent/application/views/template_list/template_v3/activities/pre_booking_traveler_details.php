
<?php 
 // debug($formated_data);
 // debug($search_id); exit;

  $total_pax=0;
  if(isset($search_data['adult'])){   
    $total_pax+=$search_data['adult'];
  }
  if(isset($search_data['child'])){
    $total_pax+=$search_data['child'];

  }
$package->price=0;
$AgentCommission=0;
$AgentTdsOnCommision=0; 
$NetFare=0;
$_GST=0;
$_Markup=0;
 if(!empty($formated_data[$key]))
 { 
    if(isset($formated_data[$key]['Price']))
    {
      $package->price=$formated_data[$key]['Price']['TotalDisplayFare'];
      $AgentCommission=$formated_data[$key]['Price']['AgentCommission'];   
      $AgentTdsOnCommision=$formated_data[$key]['Price']['AgentTdsOnCommision'];   
      $NetFare=$formated_data[$key]['Price']['NetFare'];   
      $_GST=$formated_data[$key]['Price']['_GST'];   
      $_Markup=$formated_data[$key]['Price']['_Markup'];   
      $_TotalPayable=$formated_data[$key]['Price']['_TotalPayable'];   
      $_CustomerBuying=$formated_data[$key]['Price']['_CustomerBuying'];   
      $TotalDisplayFare=$formated_data[$key]['Price']['TotalDisplayFare']; 
      $ConvinienceFee=$formated_data[$key]['Price']['ConvinienceFee'];       
      $_AgentBuying=$formated_data[$key]['Price']['_AgentBuying'];       
      $_AgentMarkup=$formated_data[$key]['Price']['_AgentMarkup'];       
      $total_markup=  $_AgentMarkup+$formated_data[$key]['Price']['_AdminMarkup'];
      $total_chargable_gst=($total_markup/100)*$_GST;
      $grand_total=($TotalDisplayFare+$ConvinienceFee);
      $agent_buying_price = $_AgentBuying + $ConvinienceFee;
      $booking_source = $formated_data[$key]['booking_source'];

    }
    
 }

$price_details=json_encode($price_details);
 
$expiry_date = date('Y-m-d',strtotime($package->end_date));
$start_date = date('Y-m-d');
// debug($expiry_date);
// debug($start_date);
$date_diff= strtotime($expiry_date) - strtotime($start_date);
$actual_date = round($date_diff / 86400);
$childe_percentage=$package->child_discount_percentage;
if ($childe_percentage==0) {
  $child_price=$package->price;
}
else{
  $child_price=($package->price)-(($childe_percentage/100) * ($package->price));
}
//$child_price=($childe_percentage/100) * ($package->price);

$total= (($booking['no_adults']) *  $package->price+$booking['no_child'] *$child_price ); 
// debug($total);//exit;?>
<?php
// $package_datepicker = array(array('date_of_travel', FUTURE_DATE_DISABLED_MONTH));
// $GLOBALS['CI']->current_page->set_datepicker($package_datepicker);
?><?php $total= isset($total)? number_format($total, 2):0; 
// debug($formated_data[$key]['Price']);
// debug($total);exit();

//$default_currency=$currency_obj->get_currency_symbol($currency_obj->to_currency);
//$currency_obj->$currency_code="NPR";
//$default_currency=$currency_obj->get_currency_symbol($currency_obj->$currency_code);




$promo_module_type="";
if($package->module_type =="transfers")
{
  $promo_module_type="transfers";
}
elseif ($package->module_type =="activity") 
{
  $promo_module_type="activities";
}
 //debug($package);exit();
?>
<style>
   .imgsele > img {
  width: 100%;
}    
.promocode{ margin:0px; padding: 10px; width: 100%; }       
.pack_price { font-size: 15px; color:#05bfed;  }
.prebooking hr { margin:9px 0px; }                                       
.imgcaption {
  left: 0;
  position: relative; padding: 10px;
  right: 0; margin-bottom:10px;
  text-align: center;
  top: 0px;
}
.promosubmit{ background-color: #007dc6 !important; width: 100%;
    border-radius: 0px; padding:10px 10px; color: #fff; font-size: 15px; height: auto;
    border:1px solid #f04c23 !important; }
.confirm_bboki { background-color: #007dc6 !important;
    border-radius: 0px; padding:10px 20px; color: #fff; font-size: 15px;
    border:1px solid #007dc6 !important; }
.hotel {
  color: #fff;
  font-size: 18px;
}
.bookdetails h2 { font-size: 20px; }
.butsele { background: #fff; margin-top: 20px; }
.butsele h2 { font-size: 20px; }
.prebooking .bookdetails strong { font-size: 15px; font-weight: 500 !important; }
.hotavai .fa.fa-star.yellow-star {
  color: #ff9800;
  font-size: 14px;
  margin: 5px 0;
}
.butsele .form-group > input {
  border-color: #c8c8c8; height: 40px;
  border-radius: 0;
  left: 15px;
}
.butsele .form-control {
  border-color: #c8c8c8;  height: 40px;
  border-radius: 0;
}
.butsele .form-group > label {
  color: #000000;
  font-size: 14px;
  font-weight: normal;
}
  </style>                          
  <div class="prebook">
  <div class="container">
    <div class="staffareadash1">
      <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="profile">
          <div class="trvlwrap">
            <div class="row prebooking"> 
              <!-- Booking Details-->
              <div class="col-md-12 col-sm-12 col-xs-12 nopad bookdetails">
               <div class="imgcaption drkblue">
                  <div class="hotel"><?php echo  $package->package_name; ?></div>
                </div> 
                <!-- <div class="col-md-12 col-sm-12 col-xs-12">
                <h2>Booking Details</h2>
                </div> -->
              
                 <div class="col-md-8 actipre">
                  <div class="col-md-12 col-sm-6 nopad col-xs-3 imgsele">
                  <img src="<?php echo $GLOBALS['CI']->template->domain_upload_acty_images($package->image); ?>" alt="" height="342" width="342"/> 
                   </div>
                     <!-- Personal Information-->
              <div class="col-md-12 col-sm-12 col-xs-12 butsele">
                <div class="col-md-12 nopad">
                  <form action="<?php echo base_url()?>index.php/activities/pre_booking_itinary/<?php echo  $search_id; ?>" method="post" autocomplete='off'>
                    <div class="row">
                      <div class="clearfix"></div>
                      <div class="col-md-12 col-sm-12 col-xs-12">
                        <h2>Personal Information</h2>
                      </div>
                      <div class="col-md-12 col-sm-12 col-xs-12">
                        <hr>
                      </div>
                      <?php 
                      $adult_count = $search_data['adult'];
                      $child_count = $search_data['child'];
                      $k=1;
                      for ($i=0; $i < $total_pax; $i++) { 
                        if($k<=$adult_count){ ?>
                          <div class="col-md-1 col-sm-4 col-xs-12 form-group">
                            <label>Adult</label>
                            <i class="glyphicon glyphicon-user"></i>
                          </div>
                          <div class="col-md-3 col-sm-4 col-xs-12 form-group">
                            <label>Title<strong class="text-danger no_block">* </strong></label>
                            <select class="mySelectBoxClass flyinputsnor name_title" name="name_title[]" required="">
                              <option value="1">Mr</option>
                              <option value="2">Mrs</option>
                              <option value="3">Miss</option> 
                            </select>
                          </div>

                        <?php }else{ ?>
                          <div class="col-md-1 col-sm-4 col-xs-12 form-group">
                            <label>Child</label>
                            <i class="glyphicon glyphicon-user"></i>
                          </div>
                          <div class="col-md-3 col-sm-4 col-xs-12 form-group">
                            <label>Title<strong class="text-danger no_block">* </strong></label>
                            <select class="mySelectBoxClass flyinputsnor name_title" name="name_title[]" required="">
                              <option value="3">Miss</option>
                              <option value="4">Master</option>                              
                            </select>
                          </div>
                         <?php }
                        ?>
                          
                          <div class="col-md-4 col-sm-4 col-xs-12 form-group">                            
                            <label>First Name <strong class="text-danger no_block">* </strong></label>
                            <input type="text" class="form-control" placeholder="First Name" name="first_name[]"  required>
                          </div>
                          <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                            <label>Last Name <strong class="text-danger no_block">* </strong></label>
                            <input type="text" class="form-control" placeholder="Last Name " name="last_name[]" value="<?=@$this->entity_last_name?>" required>
                          </div>
                          <?php 
                          if($i==0){?>
                               <!-- <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                                <label>Date of travel <strong class="text-danger no_block">* </strong></label>
                                <div class="plcetogo datemark sidebord">
                                  <input  type="text" class="form-control b-r-0 normalinput" data-date="true" readonly name="date_of_travel" id="date_of_travel"  placeholder="Date of travel" required />
                                </div>
                              </div> -->
                          <?php }
                          ?>
                      <?php $k++; }
                      ?>
                    
                      
                    <!--   <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                      <?php $gender = 'male';
                      if (isset($this->gender)) {
                        //echo $this->gender;
                        $gender = strtolower($this->gender);
                      }
                      ?>
                        <label>Gender <strong class="text-danger no_block">* </strong></label>
                      
                          <select name="gender" class="form-control" >
                            <option value="2" <?php if($gender == "female") echo "selected"; ?>>Female</option>
                            <option value="1" <?php if($gender == "male") echo "selected"; ?>>Male</option>
                            
                          </select>
                      </div>
                      <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                          <label>Date of travel <strong class="text-danger no_block">* </strong></label>
                          <div class="plcetogo datemark sidebord">
                            <input  type="text" class="form-control b-r-0 normalinput" data-date="true" readonly name="date_of_travel" id="date_of_travel"  placeholder="Date of travel" required />
                          </div>
                        </div> -->
                        <!-- <div class="col-md-12 col-sm-12 col-xs-12">
                          <h2>Billing Address</h2>
                        </div> -->
                        <div class="col-md-12 col-sm-12 col-xs-12">
                          <h2>CONTACT DETAILS</h2>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                          <hr>
                        </div>
                       <!--  <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Unit No / Street Number</label> -->
                        <!-- <textarea class="form-control" name="add1" required><?=@$this->entity_address?></textarea> -->
                       <!--  <input type="text" id="add1" placeholder="Street Number" name="unit_type" class="form-control">
                      </div>
                      <div class="col-md-6 col-sm-6 col-xs-12 form-group" >
                        <label>Street Address <strong class="text-danger no_block"></strong></strong></label>
                        <input type="text" id="add2" name="street_type" placeholder="Street Address" class="form-control">
                      </div>
                      <div class="clearfix"></div>
                      <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Suburb / City <strong class="text-danger no_block"> </strong></label>
                        <input type="text" class="form-control" placeholder="City" name="city" value="<?=@$this->usercity_name?>"  >
                      </div>
                      <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>State / Province <strong class="text-danger no_block"></strong></label>
                        <input type="text" class="form-control" placeholder="State " name="state" value="<?=@$this->userstate_name?>"  >
                      </div>

                       <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Country <strong class="text-danger no_block"> </strong></label>
                       
                       <select class='select2 form-control add_pckg_elements'
                      data-rule-required='true' name='country' id="country" > -->
                      <!--  <input type="text" name="country" id="country" data-rule-required='true' class='form-control'>  -->
                      <!--<option value="">Select Location</option>-->
                      <!-- <?php $default_country = (isset($this->entity_country_code)) ? $this->entity_country_code : '119'; ?>
                          <?php foreach ($country as $coun) {?>
                                              
                          <option value="<?php echo $coun->origin; ?>" <?php if($coun->origin == $default_country) {echo 'selected';}?>><?php echo $coun->name; ?></option>
                          <?php }?>
                        </select>
                      </div>
                      <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Postal Code <strong class="text-danger no_block"> </strong></label>
                        <input type="text" class="form-control numeric" placeholder="000000 " minlength="6" maxlength="10" name="postal" value="<?=@$this->pin_code?>" >
                      </div> -->
                      <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Contact No. <strong class="text-danger no_block">* </strong></label>
                        <?php 
                          if (isset($this->entity_phone)) {
                            $phone_number = str_ireplace("-", "", $this->entity_phone);
                          }
                         ?>
                        <input type="text" class="form-control numeric" value="<?=@$phone_number?>" placeholder="Contact No." name="phone" minlength="8" maxlength="15" required>
                      </div>
                      <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Email ID <strong class="text-danger no_block">* </strong></label>
                        <input type="email" class="form-control" placeholder="Email address" name="email" value="<?=@$this->entity_email?>" required>
                      </div>
                      <div class="form-group" style="padding-left: 12px">
                        Your mobile number will be used only for sending transfer related communication.
                      </div>
                      <?php 
                      if($is_pickup_available=="YES")
                      {
                      ?>
                      <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <input type="text" class="form-control" placeholder="Pickup Location" name="pickup_location" value="" required>
                      </div>
                      <?php 
                    }
                      ?>

                      <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                       <textarea id="remarks_user" class="form-control" placeholder="Remarks" name="remarks_user"></textarea>
                      </div>


                    
                                    
                      <div class="col-md-12 col-sm-12 col-xs-12 nopad form-group">
                        <input type="checkbox" id="terms_cond1" name="terms_cond1" placeholder="City" style="float: left; height: auto; margin-right: 10px;"  required />
                        &nbsp;&nbsp;
                        <p class="resposelect" style="float: right;width: 95%;">I agree with the booking conditions and to pay the total amount shown, which includes Service Fees, on the right and to the <a href="<?php echo base_url();?>index.php/general/terms_n_condition/excursion" target="_blank">Terms of Service</a> and <a href="<?php echo base_url();?>index.php/general/privacy_policy/excursion" target="_blank">Privacy Policy</a></p> 
                       <p class="text-danger" id="invalid_cond_msg"> </p>
                        <div class="continye col-xs-3" style="display:none">
                          <input type="radio" id="offline" name="payment_method" value="<?= OFFLINE_PAYMENT ?>" onclick="showoffline(this.value)" checked="checked">
                          <label for="offline">Offline Payment</label><br>
                           <input type="radio" id="online" name="payment_method" value="<?= ONLINE_PAYMENT ?>" onclick="showonline(this.value)">
                          <label for="online">Online Payment</label><br>  
                          </div>
                      </div>
                      <div class="col-md-12 col-sm-12 col-xs-12 text-right">
                        <input type="hidden" name="book_id" value="<?php echo  $book_id; ?>" />
						    <input type="hidden" name="package_id" value="<?php echo  $package->package_id; ?>" />
                        <input type="hidden" name="temp_token" value="<?php echo  $temp_token; ?>" />
                        <input type="hidden" name="date_of_travel" value="<?php echo  $date_of_travel; ?>" />
                        <input type="hidden" name="cancellationPolicy" value="<?php echo  $cancellationPolicy; ?>" />
                        <input type="hidden" name="cancltnPlcy_avble" value="<?php echo  $cancltnPlcy_avble; ?>" />  
                        <input type="hidden" id="agent_balance" value="<?=$agent_balance?>">
                        <input type="hidden" required="required" name="booking_source"    value="<?=$booking_source?>">
                        <input type="hidden" id="agent_buying_price" name="agent_buying_price" value="<?=$agent_buying_price?>"> 
                         <input type="hidden" name="transport_type" id="transport_type"  value="<?=$transport_type;?>" />
                        <input type="hidden" name="total" id="total" value="<?=$ConvinienceFee+$TotalDisplayFare;?>" />
                          <input type="hidden" name="redeem_points_post" id="redeem_points_post" value="0">
	        
                        	 <input type="hidden" name="reward_usable" value="<?=round($reward_usable)?>">
                  <input type="hidden" name="reward_earned" value="<?=round($reward_earned)?>">
                  <input type="hidden" name="total_price_with_rewards" value="<?=round($total_price_with_rewards)?>">
                  <input type="hidden" id="reduce_amount" name="reducing_amount" value="<?=round($reducing_amount)?>">
                        <input type="submit" class="confirm_bboki" value="CONFIRM BOOKING" />                    
                      </div>
                    </form>
                </div>
                
                  </div>
                
              </div>


              <div class="col-md-12 col-sm-12 col-xs-12 detailsele hide">
                 <div class="col-md-12 col-sm-12 col-xs-12">
                    <h2>Other Details</h2>
                  </div>
                  <div class="col-md-12 col-sm-12 col-xs-12 detailsele1">
                    <?php echo isset($package->package_description)?($package->package_description):"No Description"; ?>
                  </div>
              </div>
              <!-- Personal Information--> 

                 </div>

                  <div class="col-md-4 nopad sidebuki frmbl sticky_div " style="background: #fff;min-height: 342px;">
                    <div class="brdsec clearfix">
                    <div id="nxtbarslider" style="width: 362px; top: 0px;">
                  <div class="imgcaption rmdtls">
                    <div class="hotel">Excursion Details</div>
                    <div class="hotavai"><?=getstarhtml($package->rating)?></div>
                  </div>  
                  <div class="clearfix"></div>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <hr>
                  </div>
                  
                  <div class="col-md-7 col-sm-7 col-xs-7"> 
                    <strong>Transfer Type</strong>
                  </div>
                  <div class="col-md-5 col-sm-5 col-xs-5 text-right">
                    <span class="pack_price"> <?php

                        $transport=array();
                        $transport=$transport_type;
                     if($transport=='W'){
                          $transfer_desc='Without Transfers';
                          echo $transfer_desc;
                        }
                        if($transport=='S'){
                          $transfer_desc='Sharing Transfers';
                          echo $transfer_desc;
                        }
                        if($transport=='P'){
                          $transfer_desc='Private Transfers';
                          echo $transfer_desc;
                        }
                    ?></span>
                  </div>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <hr>
                  </div>
                  


                  <div class="col-md-7 col-sm-7 col-xs-7"> 
                    <strong>No of Pax</strong>
                  </div>
                  <div class="col-md-5 col-sm-5 col-xs-5 text-right"><span class="pack_price">                   
                    
                    <?php 
                    if(isset($search_data['adult'])){
                      echo 'Adult '.$search_data['adult'];
                    }
                    if(isset($search_data['child'])){
                      echo '</br>Child '.$search_data['child'];                      

                    }?>
                  </span>
                  </div>
                   <div class="col-md-12 col-sm-12 col-xs-12">
                    <hr>
                  </div>
                  <div class="col-md-7 col-sm-7 col-xs-7"> 
                    <strong>Date of Travel:</strong>
                  </div>
                  <div class="col-md-5 col-sm-5 col-xs-5 text-right"><span class="pack_price">  <?=$date_of_travel?>
                  </span>
                  </div>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <hr>
                  </div>
                  <div class="col-md-7 col-sm-7 col-xs-7"> 
                    <strong>Cancellation Policy:</strong>
                  </div>
                  <div class="col-md-5 col-sm-5 col-xs-5 text-right"><span class="pack_price">  <?=$cancellationPolicy?>
                  </span>
                  </div>
                   <div class="col-md-12 col-sm-12 col-xs-12">
                    <!--<hr>-->
                  </div>
                  
                  <!-- <div class="col-md-7 col-sm-7 col-xs-7"> <strong>Adult </strong></div>
                  <div class="col-md-5 col-sm-5 col-xs-5 text-right"><?php echo  $booking['no_adults']; ?></div>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <hr>
                  </div>
                  <div class="col-md-7 col-sm-7 col-xs-7"> <strong> Child </strong></div>
                  <div class="col-md-5 col-sm-5 col-xs-5 text-right"><?php echo  $booking['no_child']; ?></div>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <hr>
                  </div>

                  <div class="col-md-7 col-sm-7 col-xs-7"> <strong> Infant </strong></div>
                  <div class="col-md-5 col-sm-5 col-xs-5 text-right"><?php echo  $booking['no_infant']; ?></div>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <hr>
                  </div> -->
                  

                 <!--  <div class="col-md-7 col-sm-7 col-xs-7"> <strong> Activity Price per Person </strong></div> -->
                <!--   <div class="col-md-7 col-sm-7 col-xs-7"> 
                    <strong> Activity Price  </strong>
                  </div>
                  
                  <div class="col-md-5 col-sm-5 col-xs-5 text-right"><span class="pack_price">
                    <?php
                    // echo $currency_obj->get_currency_symbol($currency_obj->to_currency); 
                    // echo debug($currency_obj);
                    ?>  
                    <?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?>  
                    <?php echo $total;
                          //echo isset($package->price)? number_format($total, 2):0; 
                    ?>
                  </span>
                  </div>
                   <div class="col-md-12 col-sm-12 col-xs-12">
                    <hr>
                  </div> -->
                  <!--<div class="col-md-7 col-sm-7 col-xs-7"> 
                    <strong> Agent Costing </strong>
                  </div>
                  <div class="col-md-5 col-sm-5 col-xs-5 text-right"><span class="pack_price">
                   
                    <?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?>  
                    <?=$NetFare?>
                  </span>
                  </div>

                   <div class="col-md-12 col-sm-12 col-xs-12">
                    <hr>
                  </div>
                  <div class="col-md-7 col-sm-7 col-xs-7"> 
                    <strong> Agent Markup </strong>
                  </div>
                  <div class="col-md-5 col-sm-5 col-xs-5 text-right"><span class="pack_price">                   
                    <?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?>  
                    <?= $_AgentMarkup?>
                  </span>
                  </div>

                   <div class="col-md-12 col-sm-12 col-xs-12">
                    <hr>
                  </div>
                 
                  <div class="col-md-7 col-sm-7 col-xs-7"> 
                    <strong> Taxes & Service fee  </strong>
                  </div>
                  <div class="col-md-5 col-sm-5 col-xs-5 text-right"><span class="pack_price">
                   
                    <?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?>  
                    <?=$_GST?>
                  </span>
                  </div>

                   <div class="col-md-12 col-sm-12 col-xs-12">
                    <hr>
                  </div>
                   <div class="col-md-7 col-sm-7 col-xs-7"> 
                    <strong> Convinience Fee  </strong>
                  </div>
                     <div class="col-md-5 col-sm-5 col-xs-5 text-right"><span class="pack_price">                   
                    <?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?>  
                    <?=$ConvinienceFee?>
                  </span>
                  </div>-->
                  <!--  <div class="col-md-12 col-sm-12 col-xs-12">
                    <hr>
                  </div> -->
                  
                  
                  
                  <div class="col-md-7 col-sm-7 col-xs-7"> 
                    <strong> Convinience Fee  </strong>
                  </div>
                     <div class="col-md-5 col-sm-5 col-xs-5 text-right"><span class="pack_price">                   
                    <?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?>  
                    <?=$ConvinienceFee?>
                  </span>
                  </div>
                  
                  
                  <?php  if($childe_percentage!=0){ ?>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <hr>
                  </div>
                  <div class="col-md-7 col-sm-7 col-xs-7"> <strong> Discount For Child: </strong></div>
                  <div class="col-md-5 col-sm-5 col-xs-5 text-right"><span ><?php  echo  $childe_percentage; ?>%</span>
                  </div>

                   <div class="col-md-12 col-sm-12 col-xs-12">
                    <hr>
                  </div>                   

                 <?php } ?>
                




                 
                  <div class="col-md-7 col-sm-7 col-xs-7 promo_code_discount hide"> <strong> DISCOUNT </strong></div>
                  <div class="col-md-5 col-sm-5 col-xs-5 text-right promo_code_discount hide"><span class="pack_price promo_discount_val"></span></div>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <hr>
                  </div>
                    <?php
			       if($reward_usable > 0){ ?>
                        
                            <div class="col-md-7 col-sm-7 col-xs-7">Redeem Rewards
                                <label class="switch"> <input type="checkbox" id="redeem_points" data-toggle="toggle" data-size="mini" name="redeem_points"> <span class="slider_rew
                          round"></span> </label> 
                            </div> 
                           <span id="available_rewards">0 Points</span>
                        </tr> 
                      <?php }
                         if($reward_earned > 0)
                          { ?> 
                             <div class="col-md-7 col-sm-7 col-xs-7">Rewards Earned</div>
                          <iv class="col-md-5 col-sm-5 col-xs-5 text-right"><span class="label label-primary"><?=$reward_earned?></span></div> 
                          <?php } ?>
                <div class="col-md-7 col-sm-7 col-xs-7"> <strong> TOTAL PRICE </strong></div>
                  <div class="col-md-5 col-sm-5 col-xs-5 text-right "><span class="pack_price" id="total_booking_amount">  
                    <?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency);?>
<span class="grandtotalord" hidden><?php echo $grand_total; ?></span> 
                    <span class="grandtotal"><?php echo $grand_total; ?></span> 
                  </span></div>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <hr>
                  </div>
                  
                 <div class="cartitembuk prompform ">
                  <div class="col-md-12 col-sm-12 col-xs-12 nopad">
                  <form name="promocode" id="promocode" novalidate>

                                      <div class="col-md-8 col-xs-8 nopadding_right">

                                        <div class="cartprc">
 
                                          <div class="payblnhm singecartpricebuk ritaln">

                                         <!-- <input type="text" placeholder="Enter Promo" name="code" id="code" class="promocode" aria-required="true" /> -->
 
                                           <!--  <input type="hidden" name="module_type" id="module_type" class="promocode" value="<?=md5($promo_module_type)?>" /> -->

                                            <input type="hidden" name="total_amount_val" id="total_amount_val" class="promocode" value="<?=$agent_buying_price;?>" />  


                                            

                                            <!-- <input type="hidden" name="convenience_fee" id="convenience_fee" class="promocode" value="<?=@$convenience_fees;?>" /> -->

                                            <!-- <input type="hidden" name="currency_symbol" id="currency_symbol" value="<?=@$currency_symbol;?>" /> -->

                                            <!-- <input type="hidden" name="currency" id="currency" value="<?=@$currency;?>" /> -->

                                           

                                            <p class="error_promocode text-danger"></p>                     

                                          </div>

                                        </div>

                                      </div>

                                      <div class="col-md-4 col-xs-4 nopadding_left">

                                        <!-- <input type="button" value="Apply" name="apply" id="apply" class="promosubmit"> -->

                                      </div>

                                    </form>
                                  </div>
                                </div>
                                </div> <!-- brdsec -->
                              </div>
                
              </div>
                </div>
              <!-- Booking Details--> 
              
              
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
 /*  $('.confirm_bboki').click(function(){
    var error = 0;
        if (!($('#terms_cond1').is(':checked'))) {
            error = 1
            alert("Please Tick the Terms and Conditions!!");return false;
        }
         if (!($('#offline').is(':checked')) && !($('#online').is(':checked'))) {
            error = 1
            alert("Please select any of the payment method!!");return false;
        }
        if($('#offline').is(':checked')){
        var agent_buying_price = $('#agent_buying_price').val();
        var agent_balance = $('#agent_balance').val();
        if(parseFloat(agent_balance)<parseFloat(agent_buying_price)){
          alert('Insufficient Balance!!');
                return false;
              }else{}
              }
   });*/

});
 $("#apply").click(function(){
  
function showoffline(offline)
  {
    document.getElementById('online').checked = false;
  }
  function showonline(online)
  {
    document.getElementById('offline').checked = false;
  }

  $('.loading').removeClass('hide');
    $.ajax({
        type: "POST",
        url: app_base_url+'index.php/management/promocode',
         data: {promocode: $("#code").val(), moduletype: $("#module_type").val(), total_amount_val: $("#total_amount_val").val(), convenience_fee: $("#convenience_fee").val(),email: $("#email").val(),currency_symbol: $("#currency_symbol").val(),currency: $("#currency").val()},
         //dataType: "text",  
         dataType:'json',
         cache:false,
         success: 
              function(data){
                var actual_amount_without_promo = $("#total_amount_val").val();
                // alert(actual_amount_without_promo);
        //console.log(data); 
        if(data.status == 1){
          $('.loading').addClass('hide');
          $(".promo_code_discount").removeClass('hide');
          $(".promo_discount_val").html(data.value);
          $(".grandtotal").html(data.total_amount_data);
          $('#total_booking_amount').text("<?php echo $default_currency;?>"+data.total_amount_val);
          $(".error_promocode").html('Applied Successfully');
          $(".error_promocode").removeClass('text-danger');
          $(".error_promocode").addClass('text-success');
          $("#promocode_val").val(data.promocode);
          $("#promo_code_discount_val").val(data.actual_value);
          $("#total_amount_payment").val(data.total_amount_val);
        }else{
          $("#total_booking_amount").html("<?php echo $default_currency;?>"+actual_amount_without_promo);
          $('.loading').addClass('hide');
          $(".promo_code_discount").addClass('hide');
          $(".error_promocode").html(data.error_msg);
          $('.error_promocode').removeClass('text-success');
          $('.error_promocode').addClass('text-danger');
        }
            
              }
          });
     return false;
 }); 

$(document).ready(function() {
$("#date_of_travel").datepicker({
           dateFormat:'dd-mm-yy',
           minDate: 0,
           maxDate: <?php echo $actual_date;?>
        });


});



</script>
<?php 
function getstarhtml($rating)
{
  $starhtml = "";
  switch ($rating) {
    case 1:
      $starhtml = '<i class="fa fa-star yellow-star"></i><i class="fa fa-star gray-star"></i><i class="fa fa-star gray-star"></i><i class="fa fa-star gray-star"></i><i class="fa fa-star gray-star"></i>';
      break;
    case 2:
      $starhtml = '<i class="fa fa-star yellow-star"></i> <i class="fa fa-star yellow-star"></i><i class="fa fa-star gray-star"></i><i class="fa fa-star gray-star"></i><i class="fa fa-star gray-star"></i>';
      break;
    case 3:
     $starhtml = '<i class="fa fa-star yellow-star"></i> <i class="fa fa-star yellow-star"></i> <i class="fa fa-star yellow-star"></i><i class="fa fa-star gray-star"></i><i class="fa fa-star gray-star"></i> ';
      break;
    case 4:
     $starhtml = '<i class="fa fa-star yellow-star"></i> <i class="fa fa-star yellow-star"></i> <i class="fa fa-star yellow-star"></i> <i class="fa fa-star yellow-star"></i><i class="fa fa-star gray-star"></i>';
      break;
    case 5:
      $starhtml = '<i class="fa fa-star yellow-star"></i> <i class="fa fa-star yellow-star"></i> <i class="fa fa-star yellow-star"></i> <i class="fa fa-star yellow-star"></i> <i class="fa fa-star yellow-star"></i>';
      break;
    default:
     $starhtml = "";
      break;
  }
  return $starhtml;
}
 ?>


<script>
  ////Rewards//////////////////

$("#redeem_points").change(function(){
    
        var amount = $(".discount_total").text();
         var promodiscount = $("#promo_code_discount_val").val();
        if(promodiscount=="0.00")
        {
            promodiscount=0;
        }
        var reducereward=$("#reduce_amount").val();

        var orgamount=$(".grandtotalord").html();
          console.log(reducereward);
        console.log(orgamount);
        var div = $('#prompform');
      
    if($(this). prop("checked") == true)
    {
      div.hide();
      $("#redeem_points_post").val(1);
      $("#total_amount_val").val(parseInt(orgamount)-parseInt(reducereward));
      $("#available_rewards").text('<?php echo round($reward_usable)?> Points');
      var total=parseInt(orgamount)-parseInt(reducereward);
      $(".grandtotal").html(total);
  
     // $(".grandtotal").text('<?php echo (ceil($grand_total+$convenience_fees)); ?>');
     

    }else{
      // alert('false');
      div.show();
      $("#redeem_points_post").val(0);
     $("#available_rewards").text('0 Points');
       $("#total_amount_val").val(parseInt(orgamount));
       var total=parseInt(orgamount);
      $(".grandtotal").html(total);

       
    }
  
     });

     $(document).ready(function(){
      if($("#redeem_points"). prop("checked") == true)
      {
      $('#prompform').hide();
      }
   });
  </script>
<script type="text/javascript">
$('[type="submit"]').on('click', function(e) 
{
 
$('#invalid_cond_msg').remove();
if(!$('#terms_cond1').is(':checked')){
// alert("hiii");
_status = false;
$('.resposelect').after('<p class="text-danger" id="invalid_cond_msg">Please select terms and conditions.</p>');
}
});
</script>

<script type="text/javascript">
  $(document).ready(function(){
    $(document).on('scroll', function(){
        if ($('#nxtbarslider')[0].offsetTop < $(document).scrollTop()){
          var top = $(document).scrollTop();
          var height = $(window).height();
          //alert(top);
          if((top >= 243) || (height <300))
          {
            $("#nxtbarslider").css({position: "fixed", top:0, border: "1.5px solid #e6e6e6", padding: "0 0 20px"});   
          }   
          else if ((top <243) || (height < 300))
          {
            $("#nxtbarslider").css({position: "", top:0, border: "0", padding: "0 0 20px"});    
          }         
            
        }
    });  
});
</script>