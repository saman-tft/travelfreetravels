<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-sanitize.js"></script>
<?php //debug($currency_obj);?>

<style type="text/css">
  .payinput {
    border: 1px solid 
#d6d6d6;
border-radius: 3px;
color:
    #333333;
    display: block;
    font-size: 13px;
    height: 45px;
    overflow: hidden;
    padding: 7px;
    width: 100% !important;
}
.sectionbuk .paylabel { margin: 4px 0px; }

.flpayinput {
    border: 1px solid 
#dddddd;
border-radius: 3px;
color:
    #333333;
    display: block;
    font-size: 14px;
    height: 45px;
    overflow: hidden;
    padding: 10px;
        padding-left: 10px;
    width: 100%;
}
.toalnright td{text-align: right!important;}
.table-condensed.tblemd>tbody>tr>td{text-align: right!important;} 
.insidechs.booklogin {
    float: none;
    width: 308px!important;
    max-width: 308px!important;
    display: block;
    /* max-width: 210px; */
    margin: 0 auto;
}


</style>
<?php
// echo "string";
//debug(diaplay_phonecode($phone_code,$active_data, $user_country_code));  exit();
/*$module_value = md5('Tours');*/
$module_value = md5('holiday');
$currency_symbol = $this->currency->get_currency_symbol($pre_booking_params['default_currency']);
$currency = $pre_booking_params['default_currency'];

/*$total_adult_count  = $this->session->userdata('adult_count');
$total_child_count  = $this->session->userdata('child_count');*/
$total_adult_count  = $safe_search_data['no_adults'];
$total_child_count  = $safe_search_data['no_child'];
$nights  = $this->session->userdata('no_of_nights_holiday');
// debug($this->entity_country_code);
// debug($this->entity_prf_country); 
// exit;
//debug($pre_booking_params);exit;
$CI=&get_instance();
$template_images = $GLOBALS['CI']->template->template_images();
$mandatory_filed_marker = '<sup class="text-danger">*</sup>';
//$hotel_checkin_date = hotel_check_in_out_dates($search_data['from_date']);

$add=$pre_booking_params['holiday_checkin'] = date('d-M-Y',strtotime($pre_booking_params['holiday_checkin']));


// $hotel_checkout_date = date('Y-m-d', strtotime($add. ' + '.$nights.' days'));
 //$hotel_checkout_date = date('d-M-Y',strtotime($hotel_checkout_date));

 $total_no_of_night= $duration_night['duration'];
 $hotel_checkout_date = date('Y-m-d', strtotime($add. ' + '.$total_no_of_night.' days'));
 $hotel_checkout_date = date('d-M-Y',strtotime($hotel_checkout_date));
// debug($hotel_checkout_date);exit;

 $hotel_checkin_date = explode('-', $pre_booking_params['holiday_checkin']);
 $hotel_checkout_date = explode('-', $hotel_checkout_date);
// $user_country_code='+60';
if(is_logged_in_user()) {
  $review_active_class = ' success ';
  $review_tab_details_class = '';
  $review_tab_class = ' inactive_review_tab_marker ';
  $travellers_active_class = ' active ';
  $travellers_tab_details_class = ' gohel ';
  $travellers_tab_class = ' travellers_tab_marker ';
} else {
  $review_active_class = ' active ';
  $review_tab_details_class = ' gohel ';
  $review_tab_class = ' review_tab_marker ';
  $travellers_active_class = '';
  $travellers_tab_details_class = '';
  $travellers_tab_class = ' inactive_travellers_tab_marker ';
}
$passport_issuing_country = INDIA_CODE;
$temp_passport_expiry_date = date('Y-m-d', strtotime('+5 years'));
$static_passport_details = array();
$static_passport_details['passenger_passport_expiry_day'] = date('d', strtotime($temp_passport_expiry_date));
$static_passport_details['passenger_passport_expiry_month'] = date('m', strtotime($temp_passport_expiry_date));
$static_passport_details['passenger_passport_expiry_year'] = date('Y', strtotime($temp_passport_expiry_date));


// $hotel_total_price = roundoff_number($this->hotel_lib->total_price($pre_booking_params['markup_price_summary']));

/********************************* Convenience Fees *********************************/
$subtotal = $hotel_total_price;
// debug($hotel_total_price);exit;
$pre_booking_params['convenience_fees'] = $convenience_fees;
//$pre_booking_params['convenience_fees'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $convenience_fees ) );
$hotel_total_price = roundoff_number($pre_booking_params['convenience_fees']+$hotel_total_price);
/********************************* Convenience Fees *********************************/
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('provablib.js'), 'defer' => 'defer');

$book_login_auth_loading_image   = '<div class="text-center loader-image"><img src="'.$GLOBALS['CI']->template->template_images('loader_v3.gif').'" alt="please wait"/></div>';
// debug($pre_booking_params);exit;
$LastCancellationDate = $pre_booking_params['LastCancellationDate'];
$RoomTypeName = $pre_booking_params['RoomTypeName'];
$Boardingdetails = $pre_booking_params['Boarding_details'];
//echo "LastCancellationDate".$LastCancellationDate;
//calculating price
$token = $pre_booking_params['price_token'];
$tax_total = 0;
$grand_total = 0;
//debug($currency_obj);
//echo $grand_total;
//echo $tax_total;exit;

 //debug($total_price);exit;
/*$tax_total += $pre_booking_params['convenience_fees'];
$grand_total += $pre_booking_params['convenience_fees'];*/
//debug($convenience_fees);
$tax_total += $convenience_fees;
$grand_total += $convenience_fees;
// debug($convenience_fees);exit;
//$tax_total = ceil($tax_total);

$tax_total = ($tax_total);
//added 
//debug($currency_objholiday);exit;
// oldone $total_price=(get_converted_currency_value ( $currency_obj->force_currency_conversion ( $total_price ) ));
//$currency_obj = new Currency(array('module_type' => 'flight','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
//$currency_objholiday = new Currency(array('module_type' => 'Holiday','from' => ADMIN_BASE_CURRENCY_STATIC, 'to' => get_application_currency_preference()));
//debug($currency_objholiday);exit;
// if($currency_objholiday->to_currency=="NPR"){
//  $total_price = $total_price;
//  $tax_total = $tax_total;
// }
// else{
//   $total_price = $currency_objholiday->getConversionRate() * $total_price; // newone
//   $tax_total = $currency_objholiday->getConversionRate() * $tax_total;
// }
//debug($total_price);exit;

// $currency_obj = new Currency(array('module_type' => 'flight','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
// $get_convenience_fees = $currency_obj->getConversionRate() * $convenience_fees;
// $tax_fees = $get_convenience_fees;

$grand_total = $total_price+$tax_total;
//$grand_total = $total_price+$tax_fees;
// echo $grand_total.'<br/>';
 //debug(number_format($grand_total));exit;

//$grand_total = ceil($grand_total);

 
// $grand_total = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $grand_total ) );


// $grand_total_1 =   isset($grand_total)? number_format(get_converted_currency_value ( $currency_obj->force_currency_conversion ( $grand_total ) ), 2):0;   
$grand_total_1 =   isset($grand_total)? ($grand_total):0;  

/*debug($grand_total);
 debug($grand_total_1);exit;*/

$grand_total_1=$grand_total_1+$pre_booking_params['sim_total_price'];

 //debug($grand_total_1);
// debug($tours_crs_markup);
/*debug($total_adult_count);
debug($total_child_count);*/
//  debug($tours_crs_markup['generic_markup_list'][0]['value']);
// exit();
//calculation for adding markup based on number of people:starts

//$tours_crs_markup['generic_markup_list'][0]['value']=(get_converted_currency_value ( $currency_obj->force_currency_conversion ( $tours_crs_markup['generic_markup_list'][0]['value'] ) ));

$num_of_count=$total_adult_count+$total_child_count;

$tours_crs_markup['generic_markup_list'][0]['value']=($tours_crs_markup['generic_markup_list'][0]['value'])*$num_of_count;

//value conversion
//debug($tours_crs_markup);exit;

//calculation for adding markup based on number of people:ends

//debug($grand_total_1);exit;
//debug($tours_crs_markup);exit();

      $admin_markup_holidaylcrs = $this->domain_management_model->addHolidayCrsMarkup($grand_total_1, $tours_crs_markup,$currency_obj);
   //debug($admin_markup_holidaylcrs);exit;

// debug($admin_markup_holidaylcrs);exit();
      $grand_total=str_replace(',','', $grand_total_1); 

      $grand_total=$grand_total+$admin_markup_holidaylcrs;
     // debug($grand_total);exit;
     
     // debug($gst_value);
     // debug($total_price);
     // debug($admin_markup_holidaylcrs);
     // debug($pre_booking_params['convenience_fees']);
     // exit;

      $Markup=$admin_markup_holidaylcrs;
        $gst_value = 0;
              //adding gst
             //debug($Markup);exit();
            if($Markup > 0 ){
                $gst_details = $this->custom_db->single_table_records('gst_master', '*', array('module' => 'holiday'));
                if($gst_details['status'] == true){
                    if($gst_details['data'][0]['gst'] > 0){
                        //added
                       //$gst_details['data'][0]['gst']= (get_converted_currency_value ( $currency_obj->force_currency_conversion ( $gst_details['data'][0]['gst'] ) ));
                        $gst_value = ($Markup/100) * $gst_details['data'][0]['gst'];
                    }
                }

             }
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
// debug($gst_value_conv);exit();
      // oldone $grand_total=$grand_total+ $gst_value+$gst_value_conv;
      $grand_total=number_format($grand_total+ $gst_value+$gst_value_conv,0); // newone
     // debug($grand_total);exit();
// $total_price =$grand_total;
 /*debug($grand_total);
 debug($tax_total);
debug($total_price);*/
// $total_price =$grand_total-$tax_total;
 /*debug($total_price);
debug($Markup);
debug($gst_value);exit;*/
//debug($total_price);exit;

// oldone $total_price=$total_price+$Markup+$gst_value;
$total_price=number_format($total_price+$Markup+$gst_value,0); // newone 
// debug($total_price);exit();
/*debug($Markup);
debug($gst_value);
debug($total_price);
debug($total_price+$Markup+$gst_value);*/




///$total_price =  get_converted_currency_value ( $currency_obj->force_currency_conversion ( $total_price ) );

  //debug($total_price);exit;



 /*debug($grand_total_1);
   debug($grand_total);
  debug($total_price-$gst_value);
  debug($admin_markup_holidaylcrs);
 debug($gst_value);
  exit();*/


// $total_price =  get_converted_currency_value ( $currency_obj->force_currency_conversion ( $total_price ) );

// $tax_total = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $tax_total ) );
$tax_total=$tax_total;
//debug($tax_total);exit;
//echo $grand_total;exit;
$hotel_total_price = ceil($hotel_total_price);
//calculate total room price without tax


//echo $hotel_total_price;exit;
$total_room_price  = ceil($hotel_total_price - $tax_total);
//echo $total_room_price;exit;
$total_adult_count  = $safe_search_data['no_adults'];
$total_child_count  = $safe_search_data['no_child'];
// $total_pax = array_sum($search_data['adult_config'])+array_sum($search_data['child_config']);
$total_pax = $total_adult_count+$total_child_count;
$base_url=base_url().'index.php/hotel/image_details_cdn';
//check image exists or not in url
/*$file_header = @get_headers($pre_booking_params['HotelImage']);
$image_found=1;
if(!$file_header  || $file_header [0] =='HTTP/1.1 404 Not Found'){
  $image_found=0;

}*/

// $total_adult_count  = array_sum($search_data['adult_config']);
// $total_child_count  = array_sum($search_data['child_config']);
$no_adult='No of Adults';
  $no_child = 'No of Childrens';
  if($total_adult_count==1){
    $no_adult='No of Adult';                  
  }
  if($total_child_count==1 || $total_child_count ==0){
    $no_child = 'No of Children';
  }


// debug($grand_total);
// debug($pre_booking_params['sim_total_price']);
// exit;
?>


<style>
   /* .fixed {
  position: fixed;
  top:60px;
  width: 100%;
  bottom: 0;*/
}
  .topssec::after{display:none;}
</style>
<input type="hidden" id="total_pax" value="<?=$total_pax?>">

<div class="clearfix"></div>
<div class="alldownsectn" ng-app="myApp" ng-controller="myCtrl">
  <div class="container">
  <div class="ovrgo holi-prebook">
  <div class="bktab1 xlbox <?=$review_tab_details_class?>">

        <!--left side move-->
    <!--//-->
    <div class="col-xs-12 col-md-8 toprom ">
      <div class="col-xs-12 nopad full_room_buk">
      <div class="bookcol">
        <div class="hotelistrowhtl">
        <div class="col-md-4 col-sm-4 col-xs-12 nopad xcel">
          <div class="imagehotel">
              <?php 
$HotelImage = $pre_booking_params['HotelImage'];
              if($HotelImage):?>
              <?php
                $image = $HotelImage;
              ?>
              <img alt="<?=$pre_booking_params['HotelName']?>" src="<?=$image?>">
            <?php else:?>
              <img alt="Hotel_img" src="<?=$GLOBALS['CI']->template->template_images('default_hotel_img.jpg')?>" class="lazy h-img">
            <?php endif;?>
          </div>
        </div>


        <div class="col-md-12 padall10 xcel">
          <div class="hotelhed"><?=$pre_booking_params['HotelName']?></div>
          <div class="clearfix"></div>
          <div class="bokratinghotl rating-no">          
           
          </div>
          <div class="clearfix"></div>
          <div class="mensionspl"> <?=$pre_booking_params['HotelAddress']?> </div>
          <div class="sckint">
        <div class="ffty">
        <div class="borddo brdrit"> <span class="lblbk_book">
        <span class="fal fa-calendar"></span>
        Check-in</span>
          <div class="fuldate_book"> <span class="bigdate_book"><?=$hotel_checkin_date[0]?></span>
          <div class="biginre_book"> <?=$hotel_checkin_date[1]?>
            <?=$hotel_checkin_date[2]?> </div>
          </div>
        </div>
        </div>
        <div class="ffty">
        <div class="borddo"> <span class="lblbk_book"> <span class="fal fa-calendar"></span> Check-out</span>
          <div class="fuldate_book"> <span class="bigdate_book"><?=$hotel_checkout_date[0]?></span>
          <div class="biginre_book"> <?=$hotel_checkout_date[1]?>
            <?=$hotel_checkout_date[2]?> </div>
          </div>
        </div>
        </div>
        <div class="clearfix"></div>
        <div class="nigthcunt"><?php //echo ($nights+1)." Days ". $nights. " Night package" ?>
          <?php echo ($duration_night['duration']+1)." Days ". $duration_night['duration']. " Night Tour" ?>
        </div>
      </div>
        </div>
        </div>
      </div>
      </div>
      
        <div class="col-xs-12 nopadding full_log_tab">
    <div class="fligthsdets">
    <div class="flitab1">
        <div class="clearfix"></div>
        <div class="sepertr"></div>
        <!--
        <div class="promocode">
          <div class="col-xs-6">
            <div class="mailsign">Have a discount / promo code to redeem</div>
          </div>
          <div class="col-xs-6">
            <div class="tablesign">
              <div class="inputsign">
              <input type="text" placeholder="Enter Coupon" class="newslterinput nputbrd">
              </div>
              <div class="submitsign">
              <button class="promobtn">Apply</button>
              </div>
             </div>
          </div>
        </div>
        -->
        <div class="sepertr"></div>
        <!-- LOGIN SECTION STARTS -->
        <? //echo "logged";debug(is_logged_in_user());exit;?>
        <?php if(is_logged_in_user() == false) { ?>
        <div class="loginspld">
          <div class="logininwrap">
          <div class="signinhde">
            Sign in now to Book Online
          </div>
          <div class="newloginsectn">
            <div class="col-xs-5 celoty nopad">
              <div class="insidechs">
                <div class="mailenter">
                  <input type="text" name="booking_user_name" id="booking_user_name"  placeholder="Your mail id" class="newslterinput nputbrd _guest_validate" maxlength="80">
                  <span class="err_msg" id="booking_user_name_error"></span>
                </div>  
                <div class="noteinote">Your booking details will be sent to this email address.</div>
                <div class="clearfix"></div>
                <div class="havealrdy">
                  <div class="squaredThree">
                    <input id="alreadyacnt" type="checkbox" name="check" value="None">
                    <label for="alreadyacnt"></label>
                  </div>
                  <label for="alreadyacnt" class="haveacntd">I have a TravelFreeTravels Account</label>
                </div>
                <div class="clearfix"></div>
                <div class="twotogle">
                <div class="cntgust">
                  <div class="phoneumber">
                    <div class="col-xs-5 ltpad">
                        <!-- <input type="text" placeholder="+91" class="newslterinput nputbrd"> -->
                        <?php
                          // debug($user_country_code);

                        ?>
                      <select name="" class="newslterinput nputbrd _numeric_only" id="before_country_code"  required>
                        <?php 
                        
                       
                        echo diaplay_phonecode($phone_code,$active_data, $user_country_code); ?>
                      </select> 
                    </div>
                    <div class="col-xs-1 nopadding"><div class="sidepo">-</div></div>
                    <div class="col-xs-6 ltpad">
                      <input type="text" id="booking_user_mobile" placeholder="Mobile Number" class="newslterinput nputbrd _numeric_only _guest_validate numer" maxlength="15">
                      <span class="err_msg" id="booking_mobile_number_error" style='color:red'></span>
                    </div>
                    <div class="clearfix"></div>
                    <div class="noteinote">We'll use this number to send possible update alerts.</div>
                  </div>
                  <div class="clearfix"></div>
                  <div class="continye col-xs-5 nopad">
                    <button class="bookcont" id="continue_as_guest">Book as Guest</button>
                  </div>
                  
                </div>
                <div class="alrdyacnt">
                  <div class="col-xs-12 nopad">
                     <div class="relativemask"> 
                      <input type="password" name="booking_user_password" id="booking_user_password" class="clainput" placeholder="Password" />
                      <span class="err_msg" id="booking_user_password_error"></span>
                     </div>
                     <div class="clearfix"></div>
                     <a class="frgotpaswrd">Forgot Password?</a>
                     <div style="" class="hide alert alert-danger"></div>
                  </div>
                  
                  <div id="book_login_auth_loading_image" style="display: none">
                    <?=$book_login_auth_loading_image?>
                  </div>
                            
                  <div class="clearfix"></div>
                  <div class="continye col-xs-8 nopad">
                    <button class="bookcont" id="continue_as_user">Proceed to Book</button>
                  </div>
                </div>
                </div>
              </div>
            </div>
             <?php $no_social=no_social(); if($no_social != 0) {?>
            <div class="col-xs-2 celoty nopad linetopbtm">
                <div class="orround">OR</div>
            </div>
            <?php } ?>
                  <div class="col-xs-2 celoty nopad linetopbtm">
                    <div class="orround">OR
                      
                    </div>
                  </div>
            <div class="col-xs-5 celoty nopad">
              <div class="insidechs booklogin">
                <div class="leftpul">
      <?php 
        $social_login1 = 'facebook';
        $social1 = is_active_social_login($social_login1);
        if($social1){
          $GLOBALS['CI']->load->library('social_network/facebook');
          echo $GLOBALS['CI']->facebook->login_button ();
        } 
        $social_login2 = 'twitter';
        $social2 = is_active_social_login($social_login2);
        if($social2){
        ?>
        <a class="logspecify tweetcolor"><span class="fa fa-twitter"></span>
        <div class="mensionsoc">Login with Twitter</div>
        </a>
      <?php } 
        $social_login3 = 'googleplus';
        $social3= is_active_social_login($social_login3);
        if($social3){
          $GLOBALS['CI']->load->library('social_network/google');
          echo $GLOBALS['CI']->google->login_button ();
        } ?>
              </div>
            </div>
          </div>
        </div>
        </div>
      </div>
      <?php } ?>
        <!-- LOGIN SECTION ENDS -->
        </div>
        </div>
     </div>
      <div class="col-xs-4 nopad full_room_buk hide">
      <div class="sckint">
        <div class="ffty">
        <div class="borddo brdrit"> <span class="lblbk_book">
        <span class="fal fa-calendar"></span>
        Check-in</span>
          <div class="fuldate_book"> <span class="bigdate_book"><?=$hotel_checkin_date[0]?></span>
          <div class="biginre_book"> <?=$hotel_checkin_date[1]?><br>
            <?=$hotel_checkin_date[2]?> </div>
          </div>
        </div>
        </div>
        <div class="ffty">
        <div class="borddo"> <span class="lblbk_book"> <span class="fal fa-calendar"></span> Check-out</span>
          <div class="fuldate_book"> <span class="bigdate_book"><?=$hotel_checkout_date[0]?></span>
          <div class="biginre_book"> <?=$hotel_checkout_date[1]?><br>
            <?=$hotel_checkout_date[2]?> </div>
          </div>
        </div>
        </div>
        <div class="clearfix"></div>
        <div class="nigthcunt">Night(s) <?=$search_data['no_of_nights']?> <?=$record['duration']+1;?>, Room(s) <?=$search_data['room_count']?></div>
      </div>
      </div>
    </div>

     <!--left side move-->
   
   
    <!--right side move-->
     <div class="col-xs-4 col-md-4 full_room_buk rhttbepa sidebuki frmbl" id="slidebarscr">
      <table class="table table-condensed tblemd toalnright">
        <tbody>
            <tr class="rmdtls">
                <th>Tour Details</th>
                <td></td>
              </tr>
              
              <tr class="aminitdv">
              
            </tr>
            
            <tr>
             <?php
                 $total_pax = $total_adult_count+$total_child_count;
                ?>

              <th>No of Guest</th>
              <td><?=$total_pax?></td>
            </tr>
            <tr>

              <th><?=$no_adult?></th>
              <td><?=$total_adult_count?></td>
            </tr>
            
            <tr>
              <th><?=$no_child?></th>
              <td><?=$total_child_count?></td>
            </tr>
            <tr>

              <th>Check-in Date</th>
              <td><?=$pre_booking_params['holiday_checkin']?></td>
            </tr>
            <?php if($pre_booking_params['no_of_transfer'] != ""){ ?>
            <tr>

              <th>Number of Transfer</th>
              <td><?=$pre_booking_params['no_of_transfer']?></td>
            </tr>
            <?php } ?>
             <?php if($pre_booking_params['no_of_room'] != ""){ ?>
            <tr>

              <th>Number of Room</th>
              <td><?=$pre_booking_params['no_of_room']?></td>
            </tr>
            <?php } ?>
            <?php if($pre_booking_params['no_of_extrabed'] != ""){ ?>
            <tr>

              <th>Number of Extra Bed</th>
              <td><?=$pre_booking_params['no_of_extrabed']?></td>
            </tr>
           <?php } ?>
        <!--   <tr class="frecanpy">
                <th><a  href="#" data-target="#roomCancelModal" data-toggle="modal" >View Cancellation Policy:</a></th>
            </tr> -->
            <tr>
              <th>Total Price</th>

              <!--<td><?=$this->currency->get_currency_symbol($pre_booking_params['default_currency'])?> <?=(number_format($total_price,2))?></td>-->
              <?php //debug($total_price);exit;  ?>
              <!-- <td><?=$this->currency->get_currency_symbol($currency_obj->to_currency)?> <?=(number_format($total_price,2))?></td> -->
              <?php //debug($total_price);?>
              <?php $total_price = str_replace( ',', '', $total_price ); ?>
              <td><?=$this->currency->get_currency_symbol($currency_obj->to_currency)?> <?=(number_format($total_price,2))?></td>
            </tr>
            <tr class="texdiv">
              <th>Taxes & Service fee</th>
            <!--  <td><?=$this->currency->get_currency_symbol($pre_booking_params['default_currency'])?> <?=number_format($tax_total,2)?></td>-->
              <td><?=$this->currency->get_currency_symbol($currency_obj->to_currency)?> <?=number_format($tax_total,2)?></td>
            </tr>
            <?php
              if($gst_value_conv)
              {
            ?>
            <tr class="texdiv">
              <th>GST</th>
            <!--  <td><?=$this->currency->get_currency_symbol($pre_booking_params['default_currency'])?> <?=number_format($tax_total,2)?></td>-->
              <td><?=$this->currency->get_currency_symbol($currency_obj->to_currency)?> <?=number_format($gst_value_conv,2)?></td>
            </tr>
            <?php } ?>

            <!-- <tr class="texdiv">
              <th>Sim Card Price</th>
             
              
              <td><?=$this->currency->get_currency_symbol($currency_obj->to_currency)?> <?=number_format($pre_booking_params['sim_total_price'],2)?></td>
            </tr>-->

            <tr class="grd_tol">
              <th>Grand Total</th>
              <!--<td><?=$this->currency->get_currency_symbol($pre_booking_params['default_currency'])?> <?=(number_format($grand_total+$pre_booking_params['sim_total_price'],2))?></td>-->
              <?php $grand_total = str_replace( ',', '', $grand_total ); ?>
              <td><?=$this->currency->get_currency_symbol($currency_obj->to_currency)?> <?=(number_format($grand_total+$pre_booking_params['sim_total_price'],2))?></td>
            </tr>
          </tbody>
        </table>
       </div>


        <!--right side move-->





    <div class="clearfix"></div>
    <div class="fullcard hide">
              <div class="price_htlin">
                <?php echo room_price_details($pre_booking_params, $search_data, $currency_obj);?>
                </div>
                <div class="clearfix"></div>
                <!--
                <div class="indiscount col-xs-8">
                  <div class="ftudiscnt">
                    <div class="cashdisc">Discount/Cashback Voucher</div>
                    <div class="col-xs-9 nopad">
                      <input type="text" class="discntcop" placeholder="Text input">
                    </div> 
                    <div class="col-xs-3">
                      <button class="b-btn bookallbtn splhtlbku" type="submit">Apply</button>
                    </div>  
                  </div>
                </div>-->
    </div>
    <div class="clearfix"></div>
  
  </div>


  <div class="bktab2 xlbox <?=$travellers_tab_details_class?>">

    <!--left move2 start-->
    <div class="col-xs-12 col-md-8 nopad" style="margin-top:0px;">
    <div class="col-xs-12 topalldesc nopad" style="box-shadow: none;">
      <div class="col-xs-12 nopad">
        <div class="bookcol nobx">
        <div class="hotelistrowhtl">
        <div class="col-md-4 nopad xcel">
          <div class="imagehotel">
              <?php 
$HotelImage = $pre_booking_params['HotelImage'];
              if($HotelImage):?>
              <?php
                $image = $HotelImage;
              ?>
              <img alt="<?=$pre_booking_params['HotelName']?>" src="<?=$image?>">
            <?php else:?>
              <img alt="Hotel_img" src="<?=$GLOBALS['CI']->template->template_images('default_hotel_img.jpg')?>" class="lazy h-img">
            <?php endif;?>
          </div>
        </div>
        <div class="col-md-8 padall10 xcel">
          <div class="hotelhed"><?=$pre_booking_params['HotelName']?></div>
          <div class="clearfix"></div>
          
          <div class="clearfix"></div>
          
          <div class="bokkpricesml">
          <div class="travlrs"><span class="travlrsnms">Travelers: </span><span class="fa fa-male"></span> <?=$total_adult_count?><span class="fa fa-child"></span> <?=$total_child_count?></div>
          <div class="totlbkamnt grandtotal"> <span class="ttlamtdvot">Total Amount </span><?=$this->currency->get_currency_symbol($pre_booking_params['default_currency'])?> <?=number_format($grand_total,2)?></div>
          </div>
        </div>
        </div>
      </div>
      </div><!-- Outer Summary -->
      
    </div>
    <!-- added by hemanth -->
    <div class="col-xs-12 topalldesc pkg_desc hide">
      <div class="col-xs-12 nopad">
        <div class="bookcol">
        <div class="hotelistrowhtl">
        <div class="col-md-4 nopad xcel">
          <div class="imagehotel">
              <img ng-src="<?=$GLOBALS['CI']->template->domain_images()?>{{pkg_data['tour_data']['banner_image']}}" onerror="<?=$GLOBALS['CI']->template->template_images('default_hotel_img.jpg')?>">
              <!-- <img alt="Hotel_img" src="<?=$GLOBALS['CI']->template->template_images('default_hotel_img.jpg')?>" class="lazy h-img"> -->
          </div>
        </div>
        <div class="col-md-8 padall10 xcel">
          <div class="hotelhed">{{pkg_data['tour_data']['package_name']}}</div>
          <div class="clearfix"></div>
          
          <div class="clearfix"></div>
          <div class="mensionspl"> {{pkg_data['tour_data']['package_description']}} </div>
          <div class="bokkpricesml">
          
          <div class="totlbkamnt grandtotal"> <span class="ttlamtdvot">Total Amount</span><?=$this->currency->get_currency_symbol($pre_booking_params['default_currency'])?>{{pkg_data['min_price_adult']}}/-</div>
          </div>
        </div>
        </div>
      </div>
      </div><!-- Outer Summary -->
      
    </div>
    <!-- end  -->
    <div class="clearfix"></div>
    <div class="col-xs-12 padpaspotr nopad">
    <div class="col-xs-12 nopadding">
    <div class="fligthsdets ert">
    <?php
/**
 * Collection field name 
 */
//Title, Firstname, Middlename, Lastname, Phoneno, Email, PaxType, LeadPassenger, Age, PassportNo, PassportIssueDate, PassportExpDate
// $total_adult_count  = array_sum($search_data['adult_config']);
// $total_child_count  = array_sum($search_data['child_config']);



//------------------------------ DATEPICKER START
$i = 1;
$datepicker_list = array();
if ($total_adult_count > 0) {
  for ($i=1; $i<=$total_adult_count; $i++) {
    $datepicker_list[] = array('adult-date-picker-'.$i, ADULT_DATE_PICKER);
  }
}

if ($total_child_count > 0) {
  for ($i=$i; $i<=($total_child_count+$total_adult_count); $i++) {
    $datepicker_list[] = array('child-date-picker-'.$i, CHILD_DATE_PICKER);
  }
}
$GLOBALS['CI']->current_page->set_datepicker($datepicker_list);
//------------------------------ DATEPICKER END
$total_pax_count  = $total_adult_count+$total_child_count;
//First Adult is Primary and and Lead Pax
$adult_enum = get_enum_list('title');
$child_enum = get_enum_list('child_title');
$gender_enum = get_enum_list('gender');
unset($adult_enum[MASTER_TITLE]); // Master is for child so not required
unset($adult_enum[MISS_TITLE]); // Miss is not supported in GRN list
unset($adult_enum[A_MASTER]);
$adult_title_options = generate_options($adult_enum, false, true);
$child_title_options = generate_options($child_enum, false, true);
//debug($child_title_options);die;
$gender_options = generate_options($gender_enum);
$nationality_options = generate_options($iso_country_list, array(INDIA_CODE));//FIXME get ISO CODE --- ISO_INDIA
$passport_issuing_country_options = generate_options($country_list);
//lowest year wanted
$cutoff = date('Y', strtotime('+20 years'));
//current year
$now = date('Y');
$day_options  = generate_options(get_day_numbers());
$month_options  = generate_options(get_month_names());
$year_options = generate_options(get_years($now, $cutoff));
/**
 * check if current print index is of adult or child by taking adult and total pax count
 * @param number $total_pax   total pax count
 * @param number $total_adult total adult count
 */
function is_adult($total_pax, $total_adult)
{
  return ($total_pax>$total_adult ? false : true);
}

/**
 * check if current print index is of adult or child by taking adult and total pax count
 * @param number $total_pax   total pax count
 * @param number $total_adult total adult count
 */
function is_lead_pax($pax_count)
{
  return ($pax_count == 1 ? true : false);
}
$lead_pax_details = @$pax_details[0];
 ?>

   <form action="<?=base_url()?>index.php/tours/pre_booking_holiday/<?=$id?>" method="POST" class="needs-validation" novalidate autocomplete="off">
    <div class="hide">
      <?php $dynamic_params_url = serialized_data($pre_booking_params);?>
      <!-- <input type="hidden" required="required" name="token"   value="<?=$dynamic_params_url;?>" />
      <input type="hidden" required="required" name="token_key" value="<?=md5($dynamic_params_url);?>" /> -->
      <input type="hidden" required="required" name="op"      value="book_flight">
      <?php //debug($booking_source);exit(); ?>
      <input type="hidden" required="required" name="booking_source"    value="<?=$booking_source?>" readonly>
      <input type="hidden" required="required" name="currency_sel"    value="<?=$currency_sel?>" readonly>
      <input type="hidden" required="required" name="promo_code_discount_val" id="promo_code_discount_val" value="0.00" readonly>
      <input type="hidden" required="required" name="promo_code" id="promocode_val" value="0.00" readonly>
      <input type="hidden" name="pkg_added" class="pkg_added" value="0" readonly>
      <input type="hidden" name="tour_id" class="tour_id" value="<?=$tour_id?>" readonly>
      <input type="hidden" name="tour_amount" class="tour_amount" id="final_total" value="<?=$total_price?>" readonly> 
      <input type="hidden"  name="total_pax" value="<?=$total_pax?>">
      <input type="hidden" name="tax_total" value="<?=number_format($tax_total,2)?>" id="tax_total">

      <input type="text" name="markup" class="markup" id="markup" value="<?=$admin_markup_holidaylcrs ?>" readonly> 
      <input type="" name="gst_value" class="gst_value" id="gst_value" value="<?=$gst_value?>" readonly>


      <input type="hidden" name="departure_date" class="departure_date" value="<?=$pre_booking_params['holiday_checkin']?>" readonly>
      <input type="hidden" name="no_of_transfer" class="no_of_transfer" value="<?=$pre_booking_params['no_of_transfer']?>" readonly>
      <input type="hidden" name="no_of_room" class="no_of_room" value="<?=$pre_booking_params['no_of_room']?>" readonly>
      <input type="hidden" name="no_of_extrabed" class="no_of_extrabed" value="<?=$pre_booking_params['no_of_extrabed']?>" readonly>
       <input type="hidden" name="redeem_points_post" id="redeem_points_post" value="0">
          <input type="hidden" name="reward_usable" value="<?=round($reward_usable)?>">
          <input type="hidden" name="reward_earned" value="<?=round($reward_earned)?>">
          <input type="hidden" name="total_price_with_rewards" value="<?=round($total_price_with_rewards)?>">
          <input type="hidden" name="reducing_amount" value="<?=round($reducing_amount)?>">
      <input type="hidden" required="required" name="user_logged_in" id="user_logged_in" value="<?= is_logged_in_user() ? 'valid':'invalid' ?>">
    </div>




       <div class="flitab1">
      <div class="moreflt boksectn">
          <div class="ontyp" style="overflow: inherit;">
            <div class="labltowr arimobold" style="overflow: inherit;">Please enter the customer names.</div>

<?php
  $child_age = @$search_data['child_age'];
  if(is_logged_in_user()) {
    $traveller_class = ' user_traveller_details ';
  } else {
    $traveller_class = '';
  }
  $first_name = '';
  $last_name = '';
  $count = 0;
  for($pax_index=1; $pax_index <= $total_pax_count; $pax_index++) {//START FOR LOOP FOR PAX DETAILS
    $count++;
    if($count == 1){

    $first_name = @$this->entity_first_name;
    $last_name = @$this->entity_last_name;
    }else{

    $first_name = '';
    $last_name = '';

    }
  $cur_pax_info = is_array($pax_details) ? array_shift($pax_details) : array();
?>


  <div class="pasngrinput _passenger_hiiden_inputs">
    <div class=" hide hidden_pax_details">
    <?php
    if(is_adult($pax_index, $total_adult_count) == true) {
       $static_date_of_birth = date('Y-m-d', strtotime('-30 years'));;
       } else {//child
        $static_date_of_birth = date('Y-m-d', strtotime('-13 years'));;
       }
       $passport_number = rand(1111111111,9999999999);
      ?>
      <input type="hidden" name="passenger_type[]" value="<?=(is_adult($pax_index, $total_adult_count) ? 1 : 2)?>">
      <input type="hidden" name="lead_passenger[]" value="<?=(is_lead_pax($pax_index) ? true : false)?>">
      <input type="hidden" name="date_of_birth[]" value="<?=$static_date_of_birth?>">
      <input type="hidden" name="gender[]" value="1" class="pax_gender">
      <input type="hidden" required="required" name="passenger_nationality[]" id="passenger-nationality-<?=$pax_index?>" value="92">
      <!-- Static Passport Details -->
      <input type="hidden" name="passenger_passport_number[]" value="<?=$passport_number?>" id="passenger_passport_number_<?=$pax_index?>">
      <input type="hidden" name="passenger_passport_issuing_country[]" value="<?=$passport_issuing_country?>" id="passenger_passport_issuing_country_<?=$pax_index?>">
      <input type="hidden" name="passenger_passport_expiry_day[]" value="<?=$static_passport_details['passenger_passport_expiry_day']?>" id="passenger_passport_expiry_day_<?=$pax_index?>">
      <input type="hidden" name="passenger_passport_expiry_month[]" value="<?=$static_passport_details['passenger_passport_expiry_month']?>" id="passenger_passport_expiry_month_<?=$pax_index?>">
      <input type="hidden" name="passenger_passport_expiry_year[]" value="<?=$static_passport_details['passenger_passport_expiry_year']?>" id="passenger_passport_expiry_year_<?=$pax_index?>">
    </div>
    <div class="col-xs-2 nopadding">
       <div class="adltnom"><?=(is_adult($pax_index, $total_adult_count) ? 'Adult' : 'Child')?><?=(is_lead_pax($pax_index) ? '- Lead Pax' : '')?></div>
     </div>
     <div class="col-xs-10 nopadding">
     <div class="inptalbox">
      <div class="col-xs-3 spllty">
      <div class="selectedwrap">
      <select class="mySelectBoxClass flyinputsnor name_title" name="name_title[]" required>
      <?php echo (is_adult($pax_index, $total_adult_count) ? $adult_title_options : $child_title_options)?>
      </select>
      </div>
      </div>
      <div class="col-xs-4 spllty">
          <input value="<?=@$first_name?>" required="required" type="text" name="first_name[]" id="passenger-first-name-<?=$pax_index?>" class="clainput alpha <?=$traveller_class?>"  minlength="2" maxlength="15" placeholder="Enter First Name" data-row-id="<?=($pax_index);?>"/>
          <input type="hidden" class="hide"  maxlength="45" name="middle_name[]">
      </div>
      <div class="col-xs-4 spllty">
        <input value="<?=@$last_name?>" required="required" type="text" name="last_name[]" id="passenger-last-name-<?=$pax_index?>" class="clainput alpha last_name" minlength="2" maxlength="15" placeholder="Enter Last Name" />
       </div>
    </div>
    </div>
  </div>
<?php
}//END FOR LOOP FOR PAX DETAILS
?>
          </div>
        </div>
        
         <div class="ontyp promo_offer ltmarhtl">
                    <div class="kindrest">
                    <div class="cartlistingbuk">
                          <div class="cartitembuk">
                            <div class="col-md-12">
                              <div class="payblnhmxm">Have an e-coupon or a deal-code ? (Optional)</div>
                            </div>
                          </div>
                          <div class="clearfix"></div>
                          <div class="cartitembuk prompform">
                            <form name="promocode" id="promocode" novalidate>
                                      <div class="col-md-4 col-xs-10 nopadding_right">
                                        <div class="cartprc">
                                          <div class="payblnhm singecartpricebuk ritaln">
                                         <input type="text" placeholder="Enter Promo" name="code" id="code" class="promocode" aria-required="true" maxlength="15" />
                                            <input type="hidden" name="module_type" id="module_type" class="promocode" value="<?=@$module_value;?>" />
                                                <input type="hidden" name="total_amount_val" id="orgtotal_amount_val" class="promocode" value="<?=@$total_price;?>" />
                                            <input type="hidden" name="total_amount_val" id="total_amount_val" class="promocode" value="<?=@$total_price;?>" />
                                            <input type="hidden" name="grand_total" id="grand_total" class="grand_total" value="<?=@$grand_total;?>" />
                                            <input type="hidden" name="convenience_fee" id="convenience_fee" class="promocode" value="<?=@$convenience_fees;?>" />
                                            <input type="hidden" name="convenience_fee_gst" id="convenience_fee_gst" class="promocode" value="<?= @$gst_value_conv; ?>" />
                                            <input type="hidden" name="currency_symbol" id="currency_symbol" value="<?=@$currency_symbol;?>" />
                                            <input type="hidden" name="currency" id="currency" value="<?=@$pre_booking_params['default_currency'];?>" />
                                            <?php $promo_for_city= md5($pre_booking_params['HotelName']); ?>
                                           
                                           <input type="hidden" name="promo_for_city" id="promo_for_city" value="<?=@$promo_for_city?>" />
                                            <p class="error_promocode text-danger"></p>                     
                                          </div>
                                        </div>
                                      </div>
                                      <div class="col-md-2 col-xs-2 nopadding_left">
                                        <input type="button" value="Apply" name="apply" id="apply" class="promosubmit">
                                      </div>
                                    </form>
                          </div>
                          <div class="clearfix"></div>
                          <div class="savemessage"></div>
                        </div>
                      </div>
                      </div> 
                
        <div class="clearfix"></div>
        <div class="contbk">
          <div class="contcthdngs">CONTACT DETAILS</div>
          <div class="hide">
          <!-- <input type="hidden" name="billing_country" value="92">
          <input type="hidden" name="billing_city" value="test">
          <input type="hidden" name="billing_zipcode" value="test">
          <input type="hidden" name="billing_address_1" value="test"> -->
          </div>
          <?php //debug($user_country_code);exit;  ?> 
          <!--left move2 panel-->
          <div class="col-xs-12 col-md-8 nopad">
          <div class="col-xs-4 nopadding">
          <select name="country_code" class="newslterinput nputbrd _numeric_only " id="after_country_code" required>
                      <?php echo diaplay_phonecode($phone_code,$active_data, $user_country_code); ?>
                    </select> 
          </div>
          <div class="col-xs-1"><div class="sidepo">-</div></div>
          <div class="col-xs-4 nopadding">
            <?php 
              if (isset($this->entity_phone)) {
                $phone_number = str_ireplace("-", "", $this->entity_phone);
                if($phone_number==0)
                {
                  $phone_number="";
                }
              }
             ?>

          <input value="<?=@$phone_number?>" type="text" name="passenger_contact" id="passenger-contact" placeholder="Mobile Number" class="newslterinput nputbrd _numeric_only" maxlength="15" required="required">
          </div>
          <div class="clearfix"></div>
          <div class="emailperson col-xs-9 nopad">
          <input value="<?=@$this->entity_email?>" type="text" maxlength="80" required="required" id="billing-email" class="newslterinput nputbrd" placeholder="Email" name="billing_email">
          </div>
          </div>
          <div class="clearfix"></div>
          <div class="notese">Your mobile number will be used only for sending tour related communication.</div>
        </div>

        <div class="comon_backbg mt20">
          <h3 class="inpagehed">Billing Address</h3>
          <div class="sectionbuk billingnob">
            <div class="payrow1">
              <div class="col-md-6">
                <div class="paylabel">Unit No / Street Number<span class="text-danger" style="font-size: 18px">*</span></div>
                <input type="text" class="form-control" style="border-radius: 8px;font-size: 13px; height: 45px;padding: 7px;width: 100% !important;" name="billing_address_1" id="street_address2"  placeholder="" value="" required/>
              </div>
              <div class="col-md-6">
                <div class="paylabel">Street Address <span class="text-danger" style="font-size: 18px">*</span>
                </div>
                <input type="text" class="form-control" name="address2" id="validationCustom01" style="border-radius: 8px;color:  #333333; display: block;font-size: 13px; height: 45px;overflow: hidden;padding: 7px;width: 100% !important;"   placeholder="" value="" required >
              </div><!-- id="address2" -->
            </div>
            <div class="payrow1">
              <div class="col-md-6">
                <div class="paylabel">Suburb / City <span class="text-danger" style="font-size: 18px">*</span>
                </div>
                <!-- <input type="text" required="" value="" class="payinput" name="billing_city" id="city" aria-required="true"> -->
               <!--  id="billing_city_x" -->
                <input type="text"  name="billing_city_x" style="border-radius: 8px;color:  #333333; display: block;font-size: 13px; height: 45px;overflow: hidden;padding: 7px;width: 100% !important;" class="form-control alpha"  value="" id="validationCustom02" required >
              </div>
              <div class="col-md-6">
                <div class="paylabel">State / Province <span class="text-danger" style="font-size: 18px">*</span>
                </div><!-- id="billing_state"  -->
                <input type="hidden" name="billing_state" class="payinput alpha" value=""  >
                <fieldset id="state_holder">
                 <!--  id="state" -->
                  <input type="text"   class="form-control alpha" style="border-radius: 8px;color:  #333333; display: block;font-size: 13px; height: 45px;overflow: hidden;padding: 7px;width: 100% !important;"  name="state" id="validationCustom03"  aria-required="true" placeholder="" value="" required >
                </fieldset>
              </div>
              <div class="col-md-6">
                <div class="paylabel">Country <span class="text-danger" style="font-size: 18px">*</span>
                </div>
                <div class="selectedwrap"><!-- id="country" -->
                  <select   name="billing_country"  class="form-control valid"  style="border-radius: 8px;color:  #333333; display: block;font-size: 13px; height: 45px;overflow: hidden;padding: 7px;width: 100% !important;" aria-required="true" id="validationCustom04" aria-invalid="false" required >
                    
                  <!--   <option value="ZM">Zambia</option>
                    <option value="ZW">Zimbabwe</option>
                    <option value="AX">Åland</option> -->

                <option value="">Please Select</option>
               <!--  <option value="INVALIDIP">Please Select</option> -->
                <?=$passport_issuing_country_options?>
                    ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="payrow1">
              <div class="col-md-6">
                <div class="paylabel">Postal Code <span class="text-danger" style="font-size: 18px">*</span>
                </div>
                <input type="text"   class="form-control numeric" name="billing_zipcode" maxlength="7" aria-required="true" placeholder="" style="border-radius: 8px;color:  #333333; display: block;font-size: 13px; height: 45px;overflow: hidden;padding: 7px;width: 100% !important;" value="" id="validationCustom05" required > <!-- id="zip" -->
              </div>
              <div class="col-md-6">
                <div class="paylabel">Email Address <span class="text-danger" style="font-size: 18px">*</span>
                </div>
                <input type="email" required="required" value="<?=@$this->entity_email?>" class="form-control" name="billing_email"  aria-required="true" style="border-radius: 8px;color:  #333333; display: block;font-size: 13px; height: 45px;overflow: hidden;padding: 7px;width: 100% !important;" id="validationCustom06" placeholder=""> <!-- id="billing-email" -->
              </div>
              <div class="col-md-6">
                <div class="paylabel">Mobile/Cell <span class="text-danger" style="font-size: 18px">*</span>
                </div>
                <!-- <input type="text" class="col-md-4 payinput1 payinput hide" id="country_mobel_code" readonly="true"> -->

                <?php 
                 // debug($phone_code);
                        // debug($active_data);
                        // debug($user_country_code);
                        // exit;
                ?>
              
                
                  <input type="text"   value="<?=@$phone_number?>" class="form-control col-md-6 mobile numeric" minlength="10" maxlength="15" name="passenger_contact"  style="border-radius: 8px;color:  #333333; display: block;font-size: 13px; height: 45px;overflow: hidden;padding: 7px;width: 100% !important;"   id="passenger-contact" aria-required="true" required >
              
              </div>
            </div>
            <div class="clearfix"></div><span class="noteclick"></span>
          </div>
        </div>

        <div class="clikdiv">
           <div class="squaredThree">
           <input id="terms_cond1" type="checkbox" name="tc" required="required">
           <label for="terms_cond1"></label>
           </div>
           <span class="clikagre">
            <a href="https://www.travelfreetravels.com/terms-and-conditions-" target="_blank">Terms and Conditions</a>
           </span>
        </div>
        <div class="clearfix"></div>
        <div class="loginspld">
          <div class="collogg">
            <?php
            //If single payment option then hide selection and select by default
            if (count($active_payment_options) == 1) {
              $payment_option_visibility = 'hide';
              $default_payment_option = 'checked="checked"';
            } else {
              $payment_option_visibility = 'show';
              $default_payment_option = '';
            }
            ?>
            <div class="row <?=$payment_option_visibility?>">
              <?php if (in_array(PAY_NOW, $active_payment_options)) {?>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="payment-mode-<?=PAY_NOW?>">
                      <input <?=$default_payment_option?> name="payment_method" type="radio" required="required" value="<?=PAY_NOW?>" id="payment-mode-<?=PAY_NOW?>" class="form-control b-r-0" placeholder="Payment Mode">
                      Fonepay
                    </label>
                    <img src="https://www.travelfreetravels.com/extras/system/template_list/template_v3/images/fonepay.png">
                  </div>
                </div>
              <?php } ?>
              <?php if (in_array(PAY_AT_BANK, $active_payment_options)) {?>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="payment-mode-<?=PAY_AT_BANK?>">
                      <input <?=$default_payment_option?> name="payment_method" type="radio" required="required" value="<?=PAY_AT_BANK?>" id="payment-mode-<?=PAY_AT_BANK?>" class="form-control b-r-0" placeholder="Payment Mode">
                      Connect IPS
                    </label>
                    <img src="https://www.travelfreetravels.com/extras/system/template_list/template_v3/images/connectips.png">
                  </div>
                </div>
              <?php } ?>
              </div>
            <div class="continye col-xs-3">
              <button id="flip" class="bookcont" type="submit">Continue</button>
            </div>
            <div class="clearfix"></div>
            <div class="sepertr"></div>
            
            <div class="temsandcndtn">
            Most countries require travelers to have a passport valid for more than 3 to 6 months from the date of entry into or exit from the country. Please check the exact rules for your destination country before completing the booking.
            </div>
          </div>
        </div>
      </div>
      </form>
      </div>
    </div>
    <?php if(is_logged_in_user() == true) { ?>
      <div class="col-xs-12 nopadding">
        <div class="insiefare">
          <div class="farehd arimobold">Passenger List</div>
          <div class="fredivs">
            <div class="psngrnote">
              <?php
                if(valid_array($traveller_details)) {
                  $traveller_tab_content = 'You have saved passenger details in your list,on typing, passenger details will auto populate.';
                } else {
                  $traveller_tab_content = 'You do not have any passenger saved in your list, start adding passenger so that you do not have to type every time. <a href="'.base_url().'index.php/user/profile?active=traveller" target="_blank">Add Now</a>';
                }
              ?>
              <?=$traveller_tab_content;?>
            </div>
          </div>
        </div>
      </div>
    <?php } ?>
    </div>
    </div>

 <!--left move2 end-->

<!--right move2-->
    <div class="col-xs-4 rhttbepa" id="slidebarscr">
         <div>
        <table class="table table-condensed tblemd toalnright">
          <tbody>
            <tr class="rmdtls">
                <th>Tour Details</th>
                <td></td>
              </tr>
              
              <tr class="aminitdv">
              
            </tr>
            
              <tr class="rmdtlstwo">
                <th>No of Guest</th>
                <?php
                 $total_pax = $total_adult_count+$total_child_count;
                ?>
                <td><?=$total_pax?></td>
              </tr>
              <tr>
                <th><?=$no_adult?></th>
                <td><?=$total_adult_count?></td>
              </tr>
              <tr>
                <th><?=$no_child?></th>
                <td><?=$total_child_count?></td>
              </tr>
              <tr>

              <th>Departure Date</th>
              <td><?=$pre_booking_params['holiday_checkin']?></td>
            </tr>
            <?php if($pre_booking_params['no_of_transfer'] != ""){ ?>
            <tr>

              <th>Number of Transfer</th>
              <td><?=$pre_booking_params['no_of_transfer']?></td>
            </tr>
            <?php } ?>
             <?php if($pre_booking_params['no_of_room'] != ""){ ?>
            <tr>

              <th>Number of Room</th>
              <td><?=$pre_booking_params['no_of_room']?></td>
            </tr>
            <?php } ?>
            <?php if($pre_booking_params['no_of_extrabed'] != ""){ ?>
            <tr>

              <th>Number of Extra Bed</th>
              <td><?=$pre_booking_params['no_of_extrabed']?></td>
            </tr>
           <?php } ?>
              <tr>
                <th>Total Price</th>
               <!-- <td><?=$this->currency->get_currency_symbol($pre_booking_params['default_currency'])?> <?=number_format($total_price,2)?></td>-->
                <td><?=$this->currency->get_currency_symbol($currency_obj->to_currency)?> <?=number_format($total_price,2)?></td>
              </tr>
              <tr class="texdiv">
                <th>Taxes & Service fee</th>
                <!--<td><?=$this->currency->get_currency_symbol($pre_booking_params['default_currency'])?> <?=number_format($tax_total,2)?></td>-->
                <?php //$currency_obj = new Currency(array('module_type' => 'flight','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
                //$get_convenience_fees = $currency_obj->getConversionRate() * $convenience_fees;
                //$tax_fees = $get_convenience_fees; ?>
                <td><?=$this->currency->get_currency_symbol($currency_obj->to_currency)?> <?=number_format($tax_total,2)?></td>
                <!-- <td><?=$this->currency->get_currency_symbol($currency_obj->to_currency)?> <?=number_format($tax_fees,2)?></td> -->
              </tr>
               <?php
              if($gst_value_conv)
              {
            ?>
            <tr class="texdiv">
              <th>GST</th>
            <!--  <td><?=$this->currency->get_currency_symbol($pre_booking_params['default_currency'])?> <?=number_format($tax_total,2)?></td>-->
              <td><?=$this->currency->get_currency_symbol($currency_obj->to_currency)?> <?=number_format($gst_value_conv,2)?></td>
            </tr>
            <?php } ?>
               <tr class="texdiv">
            <!--  <th>Sim Card Price</th>
            
              <td><?=$this->currency->get_currency_symbol($currency_obj->to_currency)?> <?=number_format($pre_booking_params['sim_total_price'],2)?></td>-->
            </tr>
              <tr class="promo_code_discount hide">
                <th> Discount</th>
                <td class="promo_discount_val" ><div id="promo_discount_value"></div></td>
              </tr>
              <tr class="pkg_value hide">
                <th>Added Package</th>
                <td class="pkg_total_value"></td>
              </tr>
                <?php if($reward_usable){ ?>
                        <tr class="texdiv">
                            <th>Redeem Rewards
                                <label class="switch"> <input type="checkbox" id="redeem_points" data-toggle="toggle" data-size="mini" name="redeem_points"> <span class="slider_rew
                          round"></span> </label> 
                            </th> 
                            <td colspan="2"><span id="available_rewards"><?php echo $reward_usable; ?> Points</span></span></td> 
                        </tr> 
                      <?php }
                         if($reward_earned)
                          { ?> 
                            <tr class="texdiv"> <th>Rewards Earned</th>
                          <td><span class="label label-primary"><?=$reward_earned?></span></td> </tr>
                          <?php } ?>
          <tr class="grd_tol" >
                <th style="font-size: 18px !important">Grand Total</th>
     <!--           <td class="grandtotal" style="font-size: 18px !important">
                  <?=$this->currency->get_currency_symbol($pre_booking_params['default_currency'])?>&nbsp;
                  <span class="tot_amt" id="total_booking_amount_show"><?=number_format($grand_total+$pre_booking_params['sim_total_price'],2)?></span> 
</td>-->
           <td class="" style="font-size: 18px !important">
                 
                  <span class="tot_amt grandtotal" id="total_booking_amount_show"> <?=$this->currency->get_currency_symbol($currency_obj->to_currency)?>&nbsp;<?=number_format($grand_total+$pre_booking_params['sim_total_price'],2)?></span> 
</td>
              </tr>
                          </tbody>
          </table>
         </div>
        <!--  <div class="ontyp promo_offer">
                    <div class="kindrest">
                    <div class="cartlistingbuk">
                          <div class="cartitembuk">
                            <div class="col-md-12">
                              <div class="payblnhmxm">Have an e-coupon or a deal-code ? (Optional)</div>
                            </div>
                          </div>
                          <div class="clearfix"></div>
                          <div class="cartitembuk prompform">
                            <form name="promocode" id="promocode" novalidate>
                                      <div class="col-md-6 col-xs-8 nopadding_right">
                                        <div class="cartprc">
                                          <div class="payblnhm singecartpricebuk ritaln">
                                         <input type="text" placeholder="Enter Promo" name="code" id="code" class="promocode" aria-required="true" />
                                            <input type="hidden" name="module_type" id="module_type" class="promocode" value="<?=@$module_value;?>" />
                                            <input type="hidden" name="total_amount_val" id="total_amount_val" class="promocode" value="<?=@$total_price;?>" />

                                            <input type="hidden" name="convenience_fee" id="convenience_fee" class="promocode" value="<?=@$convenience_fees;?>" />
                                            <input type="hidden" name="currency_symbol" id="currency_symbol" value="<?=@$currency_symbol;?>" />
                                            <input type="hidden" name="currency" id="currency" value="<?=@$pre_booking_params['default_currency'];?>" />
                                           
                                            <p class="error_promocode text-danger"></p>                     
                                          </div>
                                        </div>
                                      </div>
                                      <div class="col-md-6 col-xs-4 nopadding_left">
                                        <input type="button" value="Apply Promo" name="apply" id="apply" class="promosubmitt">
                                      </div>
                                    </form>
                          </div>
                          <div class="clearfix"></div>
                          <div class="savemessage"></div>
                        </div>
                      </div>
                      </div> -->
    </div>
    <!--rignt move2 end-->



 
    
  </div>
 </div>
  </div>
</div>
<span class="hide">
  <input type="hidden" id="pri_journey_date" value='<?=date('Y-m-d',strtotime($search_data['from_date']))?>'>
</span>
<div class="modal fade bs-example-modal-lg" id="roomCancelModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h5 class="modal-title" id="myModalLabel">Cancellation Policy</h5>
        
        <div class="imghtltrpadv hide">
          <img src="" id="trip_adv_img" alt="">
        </div>
      </div>
      <div class="modal-body">
        
        <p class="policy_text"><?php echo array_shift($pre_booking_params['CancellationPolicy']); ?></p>

        
      </div>
      <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
    </div>
  </div>
</div>

<script src="https://leafo.net/sticky-kit/example.js"></script>
<script src="https://leafo.net/sticky-kit/src/sticky-kit.js"></script>

<script type="text/javascript">
  $(document).ready(function(){
    $("#slidebarscr").stick_in_parent();
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
            $("#nxtbarslider").css({position: "fixed", top:0});   
          }   
          else if ((top <243) || (height < 300))
          {
            $("#nxtbarslider").css({position: "", top:0});    
          }         
            
        }
    });  
});
</script>

<?php 
function room_price_details($pre_booking_params, $search_data, $currency_obj)
{
  $token = $pre_booking_params['price_token'];
  $adult_config = $search_data['adult_config'];
  $child_config = $search_data['child_config'];
  $no_of_nights = $search_data['no_of_nights'];

  // $default_currency = $currency_obj->get_currency_symbol($pre_booking_params['default_currency']);
$default_currency = get_application_currency_preference();
  $CancellationPolicy = $pre_booking_params['CancellationPolicy'];
  $grand_total = 0;
  $room_price_details = '';
  $room_price_details .= '<ul>';
  $room_price_details .= '<li class="baseli hedli">
                  <ul>
                    <li class="wid10">Room</li>
                    <li class="wid20">Guest</li>
                    <li class="wid20">Price/Night</li>
                    
                    <li class="wid10">Night(s)</li>
                    <li class="wid20 textrit">Total Price</li>
                  </ul>
                  </li>';
    $tax_total = 0;
    foreach($token as $token_k => $token_v) {
      $room_number = ($token_k+1);
      $adult_count = array_shift($adult_config);
      if(valid_array($child_config)) {
        $child_count = array_shift($child_config);
      } else {
        $child_count = 0;
      }
      $temp_price_details = $GLOBALS['CI']->hotel_lib->update_room_markup_currency($token_v, $currency_obj, $search_data['search_id']);
      $RoomPrice = $temp_price_details['RoomPrice'];
      $per_night_price = ($RoomPrice/$no_of_nights);
      $room_tax = $GLOBALS['CI']->hotel_lib->tax_service_sum($temp_price_details, $token_v);
      $subtotal = $RoomPrice+$room_tax; 
      //echo 'herre'.$subtotal;exit;
      $grand_total += $RoomPrice+$room_tax;
      $tax_total += $room_tax;
      $room_price_details .= '<li class="baseli secf">
                      <ul class="responsive_li">
                        <li class="wid10"><span class="res_op">Room</span>'.$room_number.'</li>
                        <li class="wid20">
                        <span class="res_op">Guest</span>
                        <div class="plusic">
                          <div class="left adultic fa fa-male"></div>
                          <div class="left cunt">'.$adult_count.'</div>';
            if(intval($child_count) >= 1) {
              $room_price_details .= '<div class="left cunt">+</div>
                            <div class="left childic fa fa-child"></div>
                            <div class="left cunt">'.$child_count.'</div>
                            <div class="left cunt">=</div>
                            <div class="left cunt totl">'.($adult_count+$child_count).'</div>';
            }
            $room_price_details .= '</div>
                        </li>
                        <li class="wid20"><span class="res_op">Price/Night</span>'.$default_currency.' '.$per_night_price.'</li>
                        
                        <li class="wid10"><span class="res_op">Night(s)</span>'.$no_of_nights.'</li>
                        <li class="wid20 textrit"><span class="res_op">Total Price</span>'.$default_currency.' '.$RoomPrice.'</li>
                      </ul>
                      </li>';
    }
    $tax_total += $pre_booking_params['convenience_fees'];
    $grand_total += $pre_booking_params['convenience_fees'];
    $room_price_details .='<li class="baselicenter">
                    <div class="wid80 left textrit">taxes &amp; service fee</div>
                    <div class="wid20 left textrit">'.$default_currency.' '.$tax_total.'</div>
                  </li>
              <li class="baselicenter">
                <!--<div class="wid80 left textrit green">Discount</div>
                <div class="wid20 left textrit green">---</div>-->
               </li>
               <li class="baseli price_cet">
                 <div class="wid80 left textrit bigtext colrdark">Grand Total</div>
                 <div class="wid20 left textrit">
                   <div class="priceflights">
                   <strong> '.$default_currency.' </strong> <span class="h-p">'.roundoff_number($grand_total).'</span> 
                   </div>
                 </div>
                </li>';
       $room_price_details .= '</ul>';
  return $room_price_details;
}
function diaplay_phonecode($phone_code,$active_data, $user_country_code="")
{
  
  
  $list='';
    //$user_country_code="+60";
    // console.log("oo");
 //console.log(@$this->entity_country_code);
    //$user_country_code="+971";
    //$user_country_code="+971";
   // if (!is_logged_in_user()) 
   //  {
   //      //$user_country_code="+971";
   //        $user_country_code="+91";
   //  }
  foreach($phone_code as $code){
  if(!empty($user_country_code)){
    // if($user_country_code==$code['country_code']){
    if($user_country_code==$code['country_code']){
      $selected ="selected";
    }
    else {
      $selected="";
    }
  }
  else{
    
    if($active_data['api_country_list_fk']==$code['origin']){
      $selected ="selected";
    }
    else {
      $selected="";
    }
  }
  
  
    $list .="<option value=".$code['name']." ".$code['country_code']."  ".$selected." >".$code['name']." ".$code['country_code']."</option>";
  }   
   return $list;
  
}
?>
<?php
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/hotel_booking.js'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/booking_script.js'), 'defer' => 'defer');?>

<!-- <link
  href="<?php echo $GLOBALS['CI']->template->template_css_dir('custom_tour.css') ?>"
  rel="stylesheet"> -->
<?php if(is_logged_in_user()){
  // $wallet_perc = $this->custom_db->single_table_records('wallet_perc','',array('wallet_perc_origin' => 1));
  // $wallet_perc = $wallet_perc['data'][0]['wallet_perc_value'];
?>

<script>

$(document).ready(function(){
  $("#wallet_promo").attr('checked','checked');
  $('.promo_wallet').hide();

  $('input[name=promo_wallet]').on('change', function() {
    var data = this.value;
    if(data == 'wallet_promo'){
      $("#promo_code_discount_val").val('0');
      $(".error_promocode").html('');
      $("#code").val('');
      $('.promo_offer').show();
      $('.promo_wallet').hide();

      var total = Math.round(parseFloat($('#total_amount_val').val()) + parseFloat($('#convenience_fee').val()));
      $(".grandtotal").html($('#currency_symbol').val()+'. '+total+"/");
      $('#total_booking_amount').text(total);

    }
    if(data == 'wallet_balance'){
      $("#promo_code_discount_val").val('0');
      $(".error_promocode").html('');
      $("#code").val('');
      $('.promo_wallet').show();
      $('.promo_offer').hide();

      var user_wallet_balance = parseFloat($('#user_wallet_balance').val());
      var total = parseFloat($('#total_amount_val').val()) + parseFloat($('#convenience_fee').val());
      var extra_baggage = parseFloat($("#extra_baggage_charge").text());
      var extra_meal = parseFloat($("#extra_meal_charge").text());
      var extra_seat = parseFloat($("#extra_seat_charge").text());

      var total_amount = total;
      var available_wallet_balance = 0;

      if($.isNumeric(extra_baggage)){
        total_amount = total_amount + extra_baggage;
      }
      if($.isNumeric(extra_meal)){
        total_amount = total_amount + extra_meal;
      }
      if($.isNumeric(extra_seat)){
        total_amount = total_amount + extra_seat;
      }
      total_amount = Math.round(total_amount);

      var wallet_amount_check = Math.round(parseFloat(total_amount * <?= ($wallet_perc/100) ?> ));

      if( user_wallet_balance > wallet_amount_check){
        var available_wallet_balance = Math.round(parseFloat(user_wallet_balance) - wallet_amount_check);
        var discount = Math.round(parseFloat(total_amount - wallet_amount_check));
        var applied_cash = wallet_amount_check;
      }
      else{
        var available_wallet_balance = 0;
        var discount = Math.round(parseFloat(total_amount - user_wallet_balance));
        var applied_cash = user_wallet_balance;
      }
      
      $(".grandtotal").html($('#currency_symbol').val()+'. '+discount+"/");
      $('#total_booking_amount').text(discount);
      $("#promo_code_discount_val").val(applied_cash);
      $('.promo_wallet_balace').text('Succesfully Applied oicash balance of '+$('#currency_symbol').val()+'. '+applied_cash);
    }
    if(data == 'wallet_promo_instant'){

      var val = $(this).data("attr");
      $("#promo_code_discount_val").val('0');
      $(".error_promocode").html('');
      $('.promo_offer').show();
      $('.promo_wallet').hide();
      var total = Math.round(parseFloat($('#total_amount_val').val()) + parseFloat($('#convenience_fee').val()));

      var extra_baggage = parseFloat($("#extra_baggage_charge").text());
      var extra_meal = parseFloat($("#extra_meal_charge").text());
      var extra_seat = parseFloat($("#extra_seat_charge").text());

      if($.isNumeric(extra_baggage)){
        total = total + extra_baggage;
      }
      if($.isNumeric(extra_meal)){
        total = total + extra_meal;
      }
      if($.isNumeric(extra_seat)){
        total = total + extra_seat;
      }
      total = Math.round(total);

      $(".grandtotal").html($('#currency_symbol').val()+'. '+total+"/");
      $('#total_booking_amount').text(total);

      if(val == '' || val == null){

      }else{
        $("#code").val(val);
        $('#apply').click();
      }

    }
  });

});

</script>
<?php }else{ ?>

<script>
$(document).ready(function(){
  $("#wallet_promo").attr('checked','checked');
  $('input[name=promo_wallet]').on('change', function() {
    var val = $(this).data("attr");
    $(".promo_code_discount").hide();
    $("#code").val('');
    $(".error_promocode").html('');
    var total = Math.round(parseFloat($('#total_amount_val').val()) + parseFloat($('#convenience_fee').val()));
    var extra_baggage = parseFloat($("#extra_baggage_charge").text());
    var extra_meal = parseFloat($("#extra_meal_charge").text());
    var extra_seat = parseFloat($("#extra_seat_charge").text());

    if($.isNumeric(extra_baggage)){
      total = total + extra_baggage;
    }
    if($.isNumeric(extra_meal)){
      total = total + extra_meal;
    }
    if($.isNumeric(extra_seat)){
      total = total + extra_seat;
    }
    total = Math.round(total);
    $(".grandtotal").html($('#currency_symbol').val()+'. '+total+"/");

    if(val == '' || val == null){
      $('#total_booking_amount').text(total);
    }else{
      $(".promo_code_discount").show();
      $("#code").val(val);
      $('#apply').click();
    }
  });
});

</script>

<?php } ?>

<script>
var app = angular.module('myApp', ["ngSanitize"]);
// var base_url=<?=base_url()?>;
// app.config(function($sceDelegateProvider) {
//   $sceDelegateProvider.resourceUrlWhitelist([
//     'http://192.168.0.24/qatar/**'
//   ]);
// });

app.controller('myCtrl', function($scope,$http) {
  $scope.url='';
  $scope.pkg_data='';
  $scope.get_package_details=function(pkg_id)
  {
    $scope.url='<?php echo base_url() ?>/index.php/tours/pkg_details/'+pkg_id;
  }
  $scope.select_package=function(pkg_id){
              $http({
                method : "POST",
                url: '<?php echo base_url() . 'index.php/tours/details_in_json/'?>'+pkg_id,
                headers : {'Content-Type': 'application/x-www-form-urlencoded'},
               
              })
              .then(function mySuccess(response){
                $scope.pkg_data=response.data;
                 $('#tour_details').modal('hide');
                angular.element('.pkg_desc').removeClass('hide'); 
                angular.element('.pkg_list').addClass('hide'); 
                var grand_total=angular.element('.tot_amt').text(); 
                angular.element('.pkg_total_value').text($scope.pkg_data['min_price_adult']); 
                var total_amt=parseInt(grand_total)+parseInt($scope.pkg_data['min_price_adult']);
                angular.element('.tot_amt').text(total_amt);
                angular.element('.pkg_value').removeClass('hide'); 
                angular.element('.pkg_added').val('1'); 
                angular.element('.tour_id').val(pkg_id); 
                angular.element('.tour_amount').val($scope.pkg_data['min_price_adult']); 
        console.log($scope.pkg_data);
                 // $scope.$apply();
              });
  }
});

$(document).ready(function() {
   $('#flip').click(function(){
    var error = 0;
        if (!($('#terms_cond1').is(':checked'))) {
            error = 1
            alert("Please Tick the Terms and Conditions");
        }
   });
});


$('.numeric').bind("keyup blur change focus", function() {
    if (this.value != '' || this.value != null) {
      $(this).val($(this).val().replace(/[^-?][^0-9.]/, ''));
    }
  });


</script>
<script type="text/javascript">
  (function() {
'use strict';
window.addEventListener('load', function() {
// Fetch all the forms we want to apply custom Bootstrap validation styles to
var forms = document.getElementsByClassName('needs-validation');
// Loop over them and prevent submission
var validation = Array.prototype.filter.call(forms, function(form) {
form.addEventListener('submit', function(event) {
if (form.checkValidity() === false) {
event.preventDefault();
event.stopPropagation();
}
form.classList.add('was-validated');
}, false);
});
}, false);
})();
</script>
 <script>
  ////Rewards//////////////////

$("#redeem_points").change(function(){
    
        var amount = $(".discount_total").text();
        //alert(amount);
        var div = $('#prompform');
        var discount=$("#promo_code_discount_val").val();
        var curval=$("#orgtotal_amount_val").val();
  var cur='<?=$this->currency->get_currency_symbol($currency_obj->to_currency)?>';
    if($(this). prop("checked") == true){
      div.hide();
      $("#redeem_points_post").val(1);
      // alert('true');
      $("#available_rewards").text('<?php echo round($reward_usable)?> Points');
        $(".tot_amt").html('');

        var ttf=' <?=(number_format($grand_total+$pre_booking_params['sim_total_price']-$reducing_amount,2))?>';
          console.log(ttf);
            var ttf2=ttf-discount;
       $(".tot_amt").html(cur+' '+ttf2);
       var disass=<?=$reward_usable?>;
       var curval2=parseInt(curval)-parseInt(disass);
    $("#total_amount_val").val(curval2);
      $(".discount_total").text('<?php echo (ceil($price ['api_total_display_fare_with_rewards']+$convenience_fees)); ?>');
     

    }else{
      // alert('false');
      div.show();
      $("#redeem_points_post").val(0);
       $("#available_rewards").text('0 Points');
      $(".discount_total").text('<?php echo (ceil($price['api_total_display_fare']+$convenience_fees)); ?>');
       $(".tot_amt").html('');
       var ttf='<?=(number_format($grand_total+$pre_booking_params['sim_total_price'],2))?>';
       var ttf2=ttf-discount;
       console.log(ttf);
     
      $("#total_amount_val").val(curval);
       $(".tot_amt").html(cur +' '+ttf2);

   //   $(".tot_amt").html();
      //$('.discount_total').animateNumber({ number: '<?php echo $ret_data['grand_total_without_rewards']; ?>' });
      // $('#total_price_with_rewards').val(0);
      // $("#prompform").show(); 
       
    }
  
     });

     $(document).ready(function(){
      if($("#redeem_points"). prop("checked") == true)
      {
      $('#prompform').hide();
      }
   });
  </script>
