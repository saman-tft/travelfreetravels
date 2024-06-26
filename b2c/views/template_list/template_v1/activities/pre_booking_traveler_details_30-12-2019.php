
<?php 
// debug($booking); debug($package);die;
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
//debug($total);exit;?>
<?php
// $package_datepicker = array(array('date_of_travel', FUTURE_DATE_DISABLED_MONTH));
// $GLOBALS['CI']->current_page->set_datepicker($package_datepicker);
?>

   <style>
   .imgsele > img {
  width: 100%;
}    
.promocode{ margin:0px; padding: 10px; width: 100%; }       
.pack_price { font-size: 15px; color:#f04c23;  }
.prebooking hr { margin:9px 0px; }                                       
.imgcaption {
  left: 0;
  position: relative; padding: 10px;
  right: 0; margin-bottom:10px;
  text-align: center;
  top: 0px;
}
.promosubmit{ background-color: #f04c23 !important; width: 100%;
    border-radius: 0px; padding:10px 10px; color: #fff; font-size: 15px; height: auto;
    border:1px solid #f04c23 !important; }
.confirm_bboki { background-color: #f04c23 !important;
    border-radius: 0px; padding:10px 20px; color: #fff; font-size: 15px;
    border:1px solid #f04c23 !important; }
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
                <div class="col-md-12 col-sm-12 col-xs-12">
                <h2>Booking Details</h2>
                </div>
              
                 <div class="col-md-8">
                  <div class="col-md-12 col-sm-6 nopad col-xs-3 imgsele">
                  <img src="<?php echo $GLOBALS['CI']->template->domain_upload_pckg_images($package->image); ?>" alt="" height="342" width="342"/> 
                   </div>
                     <!-- Personal Information-->
              <div class="col-md-12 col-sm-12 col-xs-12 butsele">
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
                        <input type="text" class="form-control" placeholder="First Name" name="first_name" value="<?=@$this->entity_first_name?>" required>
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
                <option value="2" <?php if($gender == "female") echo "selected"; ?>>Female</option>
                <option value="1" <?php if($gender == "male") echo "selected"; ?>>Male</option>
                
              </select>
                      </div>
                      <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                          <label>Date of travel <strong class="text-danger no_block">* </strong></label>
                          <div class="plcetogo datemark sidebord">
                            <input  type="text" class="form-control b-r-0 normalinput" data-date="true" readonly name="date_of_travel" id="date_of_travel"  placeholder="Date of travel" required />
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
                        <input type="text" id="add1" name="unit_type" class="form-control">
                      </div>
                      <div class="col-md-6 col-sm-6 col-xs-12 form-group" >
                        <label>Street Address <strong class="text-danger no_block">* </strong></strong></label>
                        <input type="text" id="add2" name="street_type" required="required" class="form-control">
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
                      <?php $default_country = (isset($this->entity_country_code)) ? $this->entity_country_code : '119'; ?>
                          <?php foreach ($country as $coun) {?>
                                              
                          <option value="<?php echo $coun->origin; ?>" <?php if($coun->origin == $default_country) {echo 'selected';}?>><?php echo $coun->name; ?></option>
                          <?php }?>
                        </select>
                      </div>
                      <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <label>Postal Code <strong class="text-danger no_block">* </strong></label>
                        <input type="number" class="form-control" placeholder="000000 " minlength="6" maxlength="15" name="postal" value="<?=@$this->pin_code?>" required>
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
                      </div>
                     
                     
                      
                      

                      <div class="col-md-12 col-sm-12 col-xs-12 nopad form-group">
                        <input type="checkbox" placeholder="City" style="float: left; height: auto; margin-right: 10px;"  required />
                        &nbsp;&nbsp;
                        <p class="resposelect" style="float: right;width: 95%;">I agree with the booking conditions and to pay the total amount shown, which includes Service Fees, on the right and to the <a href="<?php echo base_url();?>index.php/terms-conditions" target="_blank">Terms of Service</a> and <a href="<?php echo base_url();?>index.php/privacy-policy" target="_blank">Privacy Policy</a></p> 
                      </div>
                      <div class="col-md-12 col-sm-12 col-xs-12 text-right">
                         <input type="hidden" name="pack_id" value="<?php echo  $booking['pack_id']; ?>" />
         

           <input type="hidden" name="no_adults" value="<?php echo  $booking['no_adults']; ?>" />
            <input type="hidden" name="no_child" value="<?php echo  $booking['no_child']; ?>" />
             <input type="hidden" name="no_infant" value="<?php echo  $booking['no_infant']; ?>" />
             <input type="hidden" name="total" value="<?php echo  $total ?>" />
             <input type="hidden" name="prom_value" id="promo_code_discount_val"/>
             <input type="hidden" name="promocode_val" id="promocode_val"/>
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

                  <div class="col-md-4 nopad sidebuki frmbl" id="fixed_div" style="background: #fff;min-height: 342px;">
                    <div class="imgcaption rmdtls">
                    <div class="hotel"><?php echo  $package->package_name; ?></div>
                    <div class="hotavai"><?=getstarhtml($package->rating)?></div>
                  </div>  
                  <div class="clearfix"></div>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <hr>
                  </div>
                  <div class="col-md-7 col-sm-7 col-xs-7"> <strong>Adult </strong></div>
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
                  </div>
                  

                  <div class="col-md-7 col-sm-7 col-xs-7"> <strong> Activity Price per Person </strong></div>
                  <div class="col-md-5 col-sm-5 col-xs-5 text-right"><span class="pack_price">AUD <?php  echo  $package->price; ?></span>
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
                  <div class="col-md-7 col-sm-7 col-xs-7"> <strong> TOTAL PRICE </strong></div>
                  <div class="col-md-5 col-sm-5 col-xs-5 text-right"><span class="pack_price" id="total_booking_amount">AUD <?php

                                        echo  $total; ?></span></div>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <hr>
                  </div>
                  
                 <div class="cartitembuk prompform ">
                  <div class="col-md-12 col-sm-12 col-xs-12 nopad">
                  <form name="promocode" id="promocode" novalidate>

                                      <div class="col-md-8 col-xs-8 nopadding_right">

                                        <div class="cartprc">

                                          <div class="payblnhm singecartpricebuk ritaln">

                                         <input type="text" placeholder="Enter Promo" name="code" id="code" class="promocode" aria-required="true" />

                                            <input type="hidden" name="module_type" id="module_type" class="promocode" value="<?=md5('activities')?>" />

                                            <input type="hidden" name="total_amount_val" id="total_amount_val" class="promocode" value="<?=@$total;?>" />

                                            <!-- <input type="hidden" name="convenience_fee" id="convenience_fee" class="promocode" value="<?=@$convenience_fees;?>" /> -->

                                            <!-- <input type="hidden" name="currency_symbol" id="currency_symbol" value="<?=@$currency_symbol;?>" /> -->

                                            <!-- <input type="hidden" name="currency" id="currency" value="<?=@$currency;?>" /> -->

                                           

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
    $.ajax({
        type: "POST",
        url: app_base_url+'index.php/management/promocode',
         data: {promocode: $("#code").val(), moduletype: $("#module_type").val(), total_amount_val: $("#total_amount_val").val(), convenience_fee: $("#convenience_fee").val(),email: $("#email").val(),currency_symbol: $("#currency_symbol").val(),currency: $("#currency").val()},
         //dataType: "text",  
         dataType:'json',
         cache:false,
         success: 
              function(data){
        //console.log(data);
        if(data.status == 1){
          $('.loading').addClass('hide');
          $(".promo_code_discount").removeClass('hide');
          $(".promo_discount_val").html(data.value);
          $(".grandtotal").html(data.total_amount_data);
          $('#total_booking_amount').text(data.total_amount_val);
          $(".error_promocode").html('Applied Successfully');
          $("#promocode_val").val(data.promocode);
          $("#promo_code_discount_val").val(data.actual_value);
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