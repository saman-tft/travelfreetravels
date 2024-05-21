<style type="text/css">
  .checked {
  color: orange;
}
</style>
<?php 
$AgentCommission=0;
$AgentTdsOnCommision=0; 
$NetFare=0;
$_GST=0;
$_Markup=0;
 if(!empty($formated_data['0crs']))
 { 

    if(isset($formated_data['0crs']['Price']))
    {
      $package->price=$formated_data['0crs']['Price']['TotalDisplayFare'];
      $AgentCommission=$formated_data['0crs']['Price']['AgentCommission'];   
      $AgentTdsOnCommision=$formated_data['0crs']['Price']['AgentTdsOnCommision'];   
      $NetFare=$formated_data['0crs']['Price']['NetFare'];   
      $_GST=$formated_data['0crs']['Price']['_GST'];   
      $_Markup=$formated_data['0crs']['Price']['_Markup'];   
    }
    
 }

 // debug($price_details);exit();

 //added on 26-2-2020
 $add_convenience_fee=isset($convenience_fees)?$convenience_fees:0;
 //debug($add_convenience_fee);

$price_details=json_encode($price_details);

// $currency_obj = new Currency(array('module_type' => 'transferv1', 'from' => ADMIN_BASE_CURRENCY_STATIC, 'to' => get_application_currency_preference()));
/*converting package price USD to MYR*/
// $package->price = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $package->price) );

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

// $total= get_converted_currency_value ( $currency_obj->force_currency_conversion ( $total) );
//debug($total);exit;?>
<?php
// $package_datepicker = array(array('date_of_travel', FUTURE_DATE_DISABLED_MONTH));
// $GLOBALS['CI']->current_page->set_datepicker($package_datepicker);
?>

   <style>
   .imgsele > img {
  width: 100%;
}           
.pack_price { font-size: 15px; color:#006bd7;  }
.prebooking hr { margin:9px 0px; }                                       
     .imgcaption {
  left: 0;
  position: relative;
  right: 0; margin-bottom:10px;
  text-align: center;
  top: 15px;
}
.confirm_bboki { background-color: #006bd7 !important;
    border-radius: 0px; padding:10px 20px; color: #fff; font-size: 15px;
    border:1px solid #006bd7 !important; }
.hotel {
  color: #007c7b;
  font-size: 18px;
}
.bookdetails h2 { font-size: 20px; }
.butsele { background: #fff; margin-top: 20px; }
.butsele h2 { font-size: 20px; }
.prebooking .bookdetails strong { font-size: 15px; font-weight: 500 !important; }
.hotavai .fa.fa-star.yellow-star {
  color: #006bd7;
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
.imgcaption.prebuhe{
    left: 0 !important;
    position: unset;
    right: 0 !important;
    margin-bottom: 10px;
    text-align: left;
    top: 0 !important;
}
.hotavai .fa.fa-star.yellow-star{
      color: #ffb400 !important;
}
.col-md-4.nopad.sidebuki.frmbl.is_stuck{
top: 100px !important;
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
                <div class="col-md-12 col-sm-12 col-xs-12">
                                </div>
              
                 <div class="col-md-8"  style="margin-top: 10px;">
                  <div class="col-md-12 " style="background: #ffffff;padding: 0px 10px;">
                  <h2 style="margin-top: 10px;">Booking Details</h2>
                <div class="imgcaption prebuhe">
                    <div class="hotel"><?php echo  $package->package_name; ?></div>
                    <div class="hotavai">
                      <!-- <?=getstarhtml($package->rating)?> -->

                      <?php 
                      $checked = '';
                      for($i=0;$i<5;$i++){ 

                         $checked = ($package->rating>$i)?'checked':""; ?>

                        <span class="fa fa-star <?=$checked?>"></span>
                      
                     <?php  } ?>
                      
                      </div>
                  </div>
                </div>
                  <div class="col-md-12 col-sm-6 nopad col-xs-3 imgsele">
                  <img src="<?php echo $GLOBALS['CI']->template->domain_upload_pckg_images($package->image); ?>" alt="" height="342" width="342"/> 
                   </div>
                     <!-- Personal Information-->
              <div class="col-md-12 col-sm-12 col-xs-12 butsele" style="padding-bottom: 20px;">
                <div class="col-md-12 nopad">
                  <form action="<?php echo base_url()?>index.php/activities/pre_booking_itinary/<?php echo  $package->package_id; ?>" method="post" autocomplete='off'>
                    <div class="row">
                      <div class="clearfix"></div>
                      <div class="col-md-12 col-sm-12 col-xs-12">
                        <h2>Personal Information</h2>
                      </div>
                      <div class="col-md-12 col-sm-12 col-xs-12">
                        <hr>
                      </div>
                      <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>First Name <strong class="text-danger no_block">* </strong></label>
                        <input type="text" class="form-control" placeholder="First Name" name="first_name" value="<?=@$this->entity_first_name?>" minlength="3" required>
                      </div>
                      <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Last Name <strong class="text-danger no_block">* </strong></label>
                        <input type="text" class="form-control" placeholder="Last Name " name="last_name" value="<?=@$this->entity_last_name?>" required>
                      </div>


                      
                      <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                      <?php $gender = 'male';
                      if (isset($this->gender)) {
                        //echo $this->gender;
                        $gender = strtolower($this->gender);
                      }
                      ?>
                        <label>Gender <strong class="text-danger no_block">* </strong></label>
                      
                          <select name="gender" class="form-control" >
                <option value="female" <?php if($gender == "female") echo "selected"; ?>>Female</option>
                <option value="male" <?php if($gender == "male") echo "selected"; ?>>Male</option>
                
              </select>
                      </div>
                      <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                          
                         
                            <input  type="hidden" value="<?=$date_of_travel?>" />
                         
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                      <div class="cartitembuk prompform ">
                  <div class="col-md-12 col-sm-12 col-xs-12 nopad">
                  <form name="promocode" id="promocode" novalidate>

                                      <div class="col-md-8 col-xs-8 nopadding_right">

                                        <div class="cartprc">
 
                                          <div class="payblnhm singecartpricebuk ritaln">

                                         <input type="text" placeholder="Enter Promo" name="code" id="code" class="promocode" aria-required="true" />
 
                                            

                                            <p class="error_promocode text-danger"></p>                     

                                          </div>

                                        </div>

                                      </div>

                                      <div class="col-md-4 col-xs-4 nopadding_left">

                                        <input type="button" value="Apply" name="apply" id="apply" class="promosubmit">

                                      </div>

                                    </form>
                                  </div>
                                </div>
                              </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                          <h2>Billing Address</h2>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                          <hr>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Unit No / Street Number</label>
                        <!-- <textarea class="form-control" name="add1" required><?=@$this->entity_address?></textarea> -->
                        <input type="text" id="add1" name="unit_type" class="form-control numeric" maxlength="4">
                      </div>
                      <div class="col-md-6 col-sm-6 col-xs-12 form-group" >
                        <label>Street Address <strong class="text-danger no_block">* </strong></strong></label>
                        <input type="text" id="add2" name="street_type" required="required" class="form-control invalid-ip">
                      </div>
                      <div class="clearfix"></div>
                      <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Suburb / City <strong class="text-danger no_block">* </strong></label>
                        <input type="text" class="form-control" placeholder="City" name="city" value="<?=@$this->usercity_name?>" required="required">
                      </div>
                      <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>State / Province <strong class="text-danger no_block">* </strong></label>
                        <input type="text" class="form-control" placeholder="State " name="state" value="<?=@$this->userstate_name?>" required="required">
                      </div>

                       <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Country <strong class="text-danger no_block">* </strong></label>
                       
                       <select class='select2 form-control add_pckg_elements'
                      data-rule-required='true' name='country' id="country" required>
                      <!--  <input type="text" name="country" id="country" data-rule-required='true' class='form-control'>  -->
                      <!--<option value="">Select Location</option>-->
                      <?php $default_country = (isset($this->entity_country_code)) ? $this->entity_country_code : '212'; ?>
                          <?php foreach ($country as $coun) {?>
                                              
                          <option value="<?php echo $coun->origin; ?>" <?php if($coun->origin == $default_country) {echo 'selected';}?>><?php echo $coun->name; ?></option>
                          <?php }?>
                        </select>
                      </div>
                      <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Postal Code <strong class="text-danger no_block">* </strong></label>
                        <input type="text" class="form-control numeric" placeholder="000000 " minlength="3" maxlength="6" name="postal" value="<?=@$this->pin_code?>" required>
                      </div>
                      <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Contact No. <strong class="text-danger no_block">* </strong></label>
                        <?php 
                          if (isset($this->entity_phone)) {
                            $phone_number = str_ireplace("-", "", $this->entity_phone);
                          }
                         ?>
                        <input type="number" class="form-control" value="<?=@$phone_number?>" placeholder="Contact No." name="phone" minlength="8" maxlength="15" required>
                      </div>
                      <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Email ID <strong class="text-danger no_block">* </strong></label>
                        <input type="email" class="form-control" placeholder="Email address" name="email" value="<?=@$this->entity_email?>" required>
                        <input type="hidden" class="form-control"  name="date_of_travel" value="<?=@$date_of_travel?>" required>
                      </div>
                     
                     
                      
                      

                                <div class="col-md-12 col-sm-12 col-xs-12 nopad form-group">
                       
                        <div class="clikdiv">
           <div class="squaredThree">
           <input id="terms_cond1" type="checkbox" name="tc" required="required">
           <label for="terms_cond1"></label>
           </div>
           <span class="clikagre">
            <a href="<?=base_url ()?>index.php/terms-conditions" target="_blank">Terms and Conditions</a>
           </span>
        </div>
                      </div>
                      <div class="col-md-12 col-sm-12 col-xs-12 text-right">
                  <input type="hidden" name="pack_id" value="<?php echo  $activity_id; ?>" />
         

                 <input type="hidden" name="no_adults" value="<?php echo  $booking['no_adults']; ?>" />
              <input type="hidden" name="no_child" value="<?php echo  $booking['no_child']; ?>" />
              <input type="hidden" name="no_infant" value="<?php echo  $booking['no_infant']; ?>" />
              <input type="hidden" name="total" value="<?php echo  $total_amount ?>" />
              <input type="hidden" name="gst" value="<?php echo  number_format($booking['gst'],2); ?>" />
              <input type="hidden" name="admin_markup" value="<?php echo number_format($markup,2); ?>" />
              <input type="hidden" name="agent_markup" value="<?php echo number_format($agent_markup,2); ?>" />
              <input type="hidden" name="prom_value" id="promo_code_discount_val"/>
              <input type="hidden" name="promocode_val" id="promocode_val"/>
              <input type="hidden" name="convenience_fees" value="<?=number_format($convenience_fee,2);?>"/>
              <input type="hidden" name="booking_source" value="<?php echo PACKAGE_BOOKING_SOURCE ?>" />
              <input type="hidden" name="payment_method" value="<?php echo PAY_NOW ?>" />
            
             
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

                  <div class="col-md-4 nopad sidebuki frmbl" id="fixed_div" style="background: #fff;">
                      
                  <table class="table table-condensed tblemd">
        <tbody>
          <tbody>
          <tr class="rmdtls"><th> Transfer Details</th><td></td></tr>
         
         <?php
           $total_pax= $no_adults+$no_child+$no_infant;
           $passenger = ($total_pax>1)?"Passengers":"Passenger"; ?>
          <tr><th>No of <?=$passenger?></th><td><?=$no_adults+$no_child+$no_infant?></td> </tr>
          <tr><th>Departure Date</th><td><?=$_POST['date_of_travel']?></td> </tr>      
          <tr><th>Total Price</th><td><?=$currency." ".number_format($total_amount,2)?></td> </tr>  
          <tr><th>Taxes & Service fee</th><td><?=$currency." ".number_format($convenience_fee,2)?></td> </tr>  
          <tr class="fare_promocode" style="display: none;"><th>Discount</th><td class="promo_discount_val"></td> </tr> 

          <tr class="grd_tol"><th >Grand Total</th><td class="grandtotal"><?=$currency." ".number_format($grand_total,2)?></td> </tr>
          
         
          <?php
          if($this->router->fetch_class() == "activities")
          {
             ?>
             <input type="hidden" id="module_type" name="" value="<?=md5('activities')?>">
             <?php
          }
          else
          {
            ?>
             <input type="hidden" id="module_type" name="" value="<?=md5('transfers')?>">
             <?php  
          }?>
          
          
          <input type="hidden" id="total_amount_val" name="" value="<?=$grand_total?>">
          <input type="hidden" id="convenience_fee" name="" value="<?=$convenience_fee?>">
          <input type="hidden" id="email" name="" value="">
          <input type="hidden" id="currency_symbol" name="" value="">
          <input type="hidden" id="currency" name="" value="<?=$currency?>">
 
         
        </tbody>
      </table>
                
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
<script src="https://leafo.net/sticky-kit/example.js"></script>
<script src="https://leafo.net/sticky-kit/src/sticky-kit.js"></script>
<script>
$(document).ready(function() {
$("#fixed_div").stick_in_parent();
});
</script>
<script type="text/javascript">

 $("#apply").click(function(){
  

  $('.loading').removeClass('hide');
var currency = $("#currency").val()
$.ajax({
        type: "POST",
        url: app_base_url+'index.php/management/promocode',
         data: {promocode: $("#code").val(), moduletype: $("#module_type").val(), total_amount_val: $("#total_amount_val").val(), convenience_fee: 0,email: '<?=$booking["booking_user_name"]?>',currency_symbol: $("#currency_symbol").val(),currency: $("#currency").val()},
         //dataType: "text",  
         dataType:'json',
         cache:false,
         success: 
              function(data){
                            console.log(data);
                            if(data.status == 1){
                              $('.loading').addClass('hide');
                              $('.fare_promocode').show();
                              $(".promo_code_discount").removeClass('hide');
                              $(".promo_discount_val").html(currency+" "+data.value.toFixed(2));
                              $(".grandtotal").html(data.total_amount_data);
                              $('#total_booking_amount').text(data.total_amount_val);
                              $(".error_promocode").html('Applied Successfully');
                              $("#promocode_val").val(data.promocode);
                              $("#promo_code_discount_val").val(data.value);
                              $("#total_amount_payment").val(data.total_amount_val);

                            }else{
                              $('.loading').addClass('hide');
                              $(".promo_code_discount").addClass('hide');
                              $(".error_promocode").html(data.error_msg);
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
      $starhtml = '<i class="fa fa-star yellow-star"></i>';
      break;
    case 2:
      $starhtml = '<i class="fa fa-star yellow-star"></i> <i class="fa fa-star yellow-star"></i>';
      break;
    case 3:
     $starhtml = '<i class="fa fa-star yellow-star"></i> <i class="fa fa-star yellow-star"></i> <i class="fa fa-star yellow-star"></i> ';
      break;
    case 4:
     $starhtml = '<i class="fa fa-star yellow-star"></i> <i class="fa fa-star yellow-star"></i> <i class="fa fa-star yellow-star"></i> <i class="fa fa-star yellow-star"></i>';
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