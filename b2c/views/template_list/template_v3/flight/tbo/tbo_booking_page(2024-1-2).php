<!--styles added here-->
<style>
                                                                        *{
                                                                            scroll-behavior: smooth;
                                                                        }
                                                                        @media (min-width:1200px){
                                                                        #dob__input{
                                                                            margin-top:2em;
                                                                            width: 600px;
                                                                            margin:0;
                                                                            margin-left:0;
                                                                        }
                                                                        }
                                                                        @media only screen and (min-width:549px) and (max-width:1185px){
                                                                            #dob__input{
                                                                                width:80vw;
                                                                                margin-left: 0;
                                                                            }
                                                                        }
                                                                                    @media only screen and (min-width:650px) and (max-width:790px){
                                                                            #dob__input{
                                                                                width:75vw;
                                                                                margin-left: 0;
                                                                            }
                                                                        }
                                                                        @media only screen and (min-width:650px) and (max-width:790px){
                                                                            #dob__input{
                                                                                width:75vw;
                                                                                margin-left: 0;
                                                                            }
                                                                        }
                                                                        @media only screen and (min-width:790px) and (max-width:880px){
                                                                            #dob__input{
                                                                                width:65vw;
                                                                                margin-left: 0;
                                                                            }
                                                                        }
                                                                        @media only screen and (min-width:880px) and (max-width:989px){
                                                                            #dob__input{
                                                                                width:60vw;
                                                                                margin-left: 0;
                                                                            }
                                                                        }
                                                                        @media only screen and (min-width:990px) and (max-width:1087px){
                                                                            #dob__input{
                                                                                width:50vw;
                                                                                margin-left: 0;
                                                                            }
                                                                        }
                                                                        @media only screen and (min-width:1087px) and (max-width:1150px){
                                                                            #dob__input{
                                                                                width:42vw;
                                                                                margin-left: 0px;
                                                                            }
                                                                        }
                                                                        @media only screen and (min-width:1150px) and (max-width:1200px){
                                                                            #dob__input{
                                                                                width:42vw;
                                                                                margin-left: 0px;
                                                                            }
                                                                        }
                                                                    </style>
<?php
$total_seg_cnt = 0;
foreach($pre_booking_summery['SegmentDetails'] as $segk => $segv){
    $total_seg_cnt += count($segv);
}
//discount_details = discount_details();
$discount = 0;
if($discount_details['status'] == SUCCESS_STATUS){
    $discount = $total_seg_cnt * $discount_details['data']['value'];
}
include_once 'process_tbo_response.php';
$template_images = $GLOBALS['CI']->template->template_images();
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('provablib.js'), 'defer' => 'defer');
//Flight Booking Summary

$FareDetails = $pre_booking_summery['FareDetails']['b2c_PriceDetails'];
$ApiPriceDetails = $pre_booking_summery['api_original_price_details'];

// debug($ApiPriceDetails);exit;
$api_markup_details = $pre_booking_summery['FareDetails']['api_PriceDetails'];
$PassengerFareBreakdown = $pre_booking_summery['PassengerFareBreakdown'];
$SegmentDetails = $pre_booking_summery['SegmentDetails'];
$SegmentSummary = $pre_booking_summery['SegmentSummary'];
/* * ******************************* Convenience Fees ******************************** */
$flight_total_amount = $FareDetails['TotalFare'] + $convenience_fees - $discount;
$flight_amount = $FareDetails['TotalFare'];
/* * ******************************* Convenience Fees ******************************** */
$currency_symbol = $FareDetails['CurrencySymbol'];
$currency = $FareDetails['Currency'];

$is_domestic = $pre_booking_params['is_domestic'];
//Segment Summary and Details
$flight_segment_details = flight_segment_details($SegmentDetails, $SegmentSummary);
if ($is_domestic != true) {
    $pass_mand = '<sup class="text-danger">*</sup>';
    $pass_req = 'required="required"';
} else {
    $pass_mand = '';
    $pass_req = '';
}
$mandatory_filed_marker = '<sup class="text-danger">*</sup>';
//Balu A
$is_domestic_flight = $search_data['is_domestic_flight'];
if ($is_domestic_flight) {
    $temp_passport_expiry_date = date('Y-m-d', strtotime('+5 years'));
    $static_passport_details = array();
    $static_passport_details['passenger_passport_expiry_day'] = date('d', strtotime($temp_passport_expiry_date));
    $static_passport_details['passenger_passport_expiry_month'] = date('m', strtotime($temp_passport_expiry_date));
    $static_passport_details['passenger_passport_expiry_year'] = date('Y', strtotime($temp_passport_expiry_date));
}

if (is_logged_in_user()) {
    $review_active_class = ' success ';
    $review_tab_details_class = '  ';
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

$book_login_auth_loading_image = '<div class="text-center loader-image"><img src="' . $GLOBALS['CI']->template->template_images('loader_v3.gif') . '" alt="please wait"/></div>';
$gst_value_conv=0;
if($convenience_fees > 0 ){
        $gst_details = $GLOBALS['CI']->custom_db->single_table_records('gst_master', '*', array('module' => 'flight'));
        if($gst_details['status'] == true){
            if($gst_details['data'][0]['gst'] > 0){
               // $gst_value = ($Markup/100) * $gst_details['data'][0]['gst'];
                //print_r($gst_details['data'][0]['gst']);exit;
                 // $gst_value = $gst_details['data'][0]['gst'];
                
                 $gst_value_conv = round(($convenience_fees/100) * $gst_details['data'][0]['gst']);
            }
        }
    }

if(valid_array($insurance))
{
    $insurance_amount=$insurance['amount']; // Add the amount When you activate the insurance tab
   if($insurance['status']==1)
   // For Active Insurance please remove hide and pass empty 
   {$insurance_status=""; } else { $insurance_status="hide"; }
}
?>
<style>
    .topssec::after{display:none;}
   /* .nsm7Bb-HzV7m-LgbsSe{width: 210px!important;max-width: 210px!important;}*/
    .farehd {
    margin: 0 15px 15px 15px!important;
}
.topalldesc {
    box-shadow: 0px 0px 4px 1px #ccc;}
    .insidechs.booklogin {
    float: none;
    width: 308px!important;max-width: 308px!important;
    display: block;
    /* max-width: 210px; */
    margin: 0 auto;
}
</style>
<div class="fldealsec">
    <div class="container">
        <div class="tabcontnue">
            <div class="col-xs-4 nopadding">
                <div class="rondsts <?= $review_active_class ?>">
                    <a class="taba core_review_tab <?= $review_tab_class ?>" id="stepbk1">
                        <div class="iconstatus fa fa-eye"></div>
                        <div class="stausline">Review</div>
                    </a>
                </div>
            </div>
            <div class="col-xs-4 nopadding">
                <div class="rondsts <?= $travellers_active_class ?>">
                    <a class="taba core_travellers_tab <?= $travellers_tab_class ?>" id="stepbk2">
                        <div class="iconstatus fa fa-group"></div>
                        <div class="stausline">Travellers</div>
                    </a>
                </div>
            </div>
            <div class="col-xs-4 nopadding">
                <div class="rondsts">
                    <a class="taba" id="stepbk3">
                        <div class="iconstatus fa fa-money"></div>
                        <div class="stausline">Payments</div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<div class="alldownsectn">
    <div class="container">
<?php if ($is_price_Changed == true) { ?>
            <div class="farehd arimobold">
                <span class="text-danger">* Price has been changed from supplier end</span>
            </div>
        <?php } ?>
        <div class="ovrgo">
        <div class="srch_ldr hide">
                    <img src="<?=$GLOBALS['CI']->template->template_images('image_loader.gif')?>" alt=""/>
                    </div>
            <div class="bktab1 xlbox <?= $review_tab_details_class ?>">
                <!-- Balu A - Fare Summery -->

                <div class="col-xs-8 nopadding full_summery_tab">
                    <div class="fligthsdets">
                        <div class="flitab1">
                            <!-- Segment Details Starts-->
                            <div class="moreflt boksectn">
<?= $flight_segment_details['segment_full_details']; ?>
                            </div>
                            <!-- Segment Details Ends-->
                            <div class="clearfix"></div>
                            <div class="sepertr"></div>
                           
                           
                            <!-- LOGIN SECTION STARTS -->
<?php if (is_logged_in_user() == false) { ?>
                                <div class="loginspld">
                                    <div class="logininwrap">
                                        <div class="signinhde">
                                            Sign in now to Book Online
                                        </div>
                                        <div class="newloginsectn">
                                            <div class="col-xs-5 celoty nopad">
                                                <div class="insidechs">
                                                    <div class="mailenter">
                                                        <input type="text" name="booking_user_name" id="booking_user_name" maxlength="80"  placeholder="Your mail id" class="newslterinput nputbrd _guest_validate invalid-ip">
                                                        <span class="err_msg" id="booking_user_name_error" style='color:red'></span>
                                                    </div>
                                                    <div class="noteinote">Your booking details will be sent to this email address.</div>
                                                    <div class="clearfix"></div>
                                                    <div class="havealrdy">
                                                        <div class="squaredThree">
                                                            <input id="alreadyacnt" type="checkbox" name="check" value="None">
                                                            <label for="alreadyacnt"></label>
                                                        </div>
                                                        <label for="alreadyacnt" class="haveacntd">I have a Travel Free Travels password</label>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="twotogle">
                                                        <div class="cntgust">
                                                            <div class="phoneumber">
                                                                <div class="col-xs-5 nopadding">
                                                                    <select class="newslterinput nputbrd _numeric_only" id="before_country_code" >
    <?php echo diaplay_phonecode($phone_code, $active_data, $user_country_code); ?>
                                                                    </select> 
                                                                </div>
                                                                <div class="col-xs-1 nopadding">
                                                                    <div class="sidepo">-</div>
                                                                </div>
                                                                <div class="col-xs-6 nopadding">
                                                                    <input type="text" id="booking_user_mobile" placeholder="Mobile Number" class="newslterinput _numeric_only _guest_validate" >
                                                                    <span class="err_msg" id="booking_mobile_number_error" style='color:red'></span>

                                                                </div>
                                                                <div class="clearfix"></div>
                                                                <div class="noteinote">We'll use this number to send possible update alerts.</div>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                            <div class="continye col-xs-8 nopad">
                                                                <button class="bookcont" id="continue_as_guest">Book as guest</button>
                                                            </div>
                                                        </div>
                                                        <div class="alrdyacnt">
                                                            <div class="col-xs-12 nopad">
                                                                <div class="relativemask"> 
                                                                    <input type="password" name="booking_user_password" id="booking_user_password" class="clainput invalid-ip" placeholder="Password" />
                                                                    <span class="err_msg" id="booking_user_password_error" style='color:red'></span>
                                                                </div>
                                                                <div class="clearfix"></div>
                                                                <a class="frgotpaswrd">Forgot Password?</a>
                                                                <div style="" class="hide alert alert-danger"></div>
                                                            </div>

                                                            <div id="book_login_auth_loading_image" style="display: none">
    <?= $book_login_auth_loading_image ?>
                                                            </div>

                                                            <div class="clearfix"></div>
                                                            <div class="continye col-xs-8 nopad">
                                                                <button class="bookcont" id="continue_as_user">Proceed to Book</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-2 celoty nopad linetopbtm">
                                                <div class="orround">OR</div>
                                            </div>

                                            <div class="col-xs-5 celoty nopad">
                                                <div class="insidechs booklogin">
                                                    <div class="leftpul">
    <?php
    $social_login1 = 'facebook';
    $social1 = is_active_social_login($social_login1);
    if ($social1) {
        $GLOBALS['CI']->load->library('social_network/facebook');
        echo $GLOBALS['CI']->facebook->login_button();
    }
    $social_login2 = 'twitter';
    $social2 = is_active_social_login($social_login2);
    if ($social2) {
        ?>
                                                            <a class="logspecify tweetcolor">
                                                                <span class="fa fa-twitter"></span>
                                                                <div class="mensionsoc">Login with Twitter</div>
                                                            </a>
        <?php
    }
    $social_login3 = 'googleplus';
    $social3 = is_active_social_login($social_login3);
    if ($social3) {
        $GLOBALS['CI']->load->library('social_network/google');
        echo $GLOBALS['CI']->google->login_button();
    }
    ?>
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

                <div class="col-xs-4 nopadding rit_summery">
                    <div class="copy_fare_summery">

                         <?php if (is_logged_in_user() == false) { ?>
<?php echo get_fare_summary($FareDetails, $PassengerFareBreakdown, $convenience_fees, $insurance_amount, $insurance_status, $org_convience_fee, $ApiPriceDetails, $api_markup_details, $convenience_fees_orginal,$gst_value_conv, $discount); ?>
                        <?php } ?>
                        </div>
                </div>

            </div>
            <div class="bktab2 xlbox <?= $travellers_tab_details_class ?> flight_booking_desc">
                <div class="topalldesc">
                    <div class="col-xs-8 nopadding celtbcel segment_seg">
<?= $flight_segment_details['segment_abstract_details']; ?>
                    </div>

                    <!-- Outer Summary -->
                    <div class="col-xs-4 nopadding celtbcel colrcelo">
                        <div class="bokkpricesml">
                            <div class="travlrs">Travellers: <span class="fa fa-male"></span> <?php echo $search_data['adult']; ?> |  <span class="fa fa-child"></span> <?php echo $search_data['child']; ?> |  <span class="infantbay"><img src="<?= $template_images ?>infant.png" alt="" /></span> <?php echo $search_data['infant']; ?></div>
                            <!--<div class="baggagecharge" id="extra_baggage_charge_label" style="display:none">
                                Extra Baggage Charge <?php echo $currency_symbol; ?> 
                                <span id="extra_baggage_charge"></span>
                                <span class="btn btn-sm btn-default" id="remove_extra_baggage"><i class="fa fa-times" aria-hidden="true"></i></span>
                            </div>
                            <div class="baggagecharge" id="extra_meal_charge_label" style="display:none">
                                Meal Charge <?php echo $currency_symbol; ?> 
                                <span id="extra_meal_charge"></span>
                                <span class="btn btn-sm btn-default" id="remove_extra_meal"><i class="fa fa-times" aria-hidden="true"></i></span>
                            </div>
                            <div class="baggagecharge" id="extra_seat_charge_label" style="display:none">
                                Seat Charge <?php echo $currency_symbol; ?> 
                                <span id="extra_seat_charge"></span>
                                <span class="btn btn-sm btn-default" id="remove_extra_seat"><i class="fa fa-times" aria-hidden="true"></i></span>
                            </div>-->
                            <div class="totlbkamnt"> Total Amount <span class="total_amount_span"><?php echo $currency_symbol; ?> </span> <span id="total_booking_amount"><?php echo round($flight_total_amount+$gst_value_conv); ?></span></div>
                             <input type="hidden" value="<?=$flight_total_amount?>" id="new_total_booking_amount" >
                             <input type="hidden" value="<?=$flight_amount?>" id="flight_amount" >
                            <a class="fligthdets" data-toggle="collapse" data-target="#fligtdetails">Flight Details</a>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <!-- Segment Details Starts-->
                <div class="collapse splbukdets" id="fligtdetails">
                    <div class="moreflt insideagain">
<?= $flight_segment_details['segment_full_details']; ?>
                    </div>
                </div>
                <!-- Segment Details Ends-->
               
                
                <div class="clearfix"></div>
                <div class="padpaspotr">
                    <div class="col-md-8 col-xs-12 nopadding tab_pasnger">
                        
                         <!-- Insuarance design starts here-->
                            <div class="wrapper center-block insrnce mt10 <?php echo $insurance_status; ?>">
                                <div class="panel-group mb0" id="accordion" role="tablist" aria-multiselectable="true">
                                    <div class="panel panel-default">
                                        <div class="panel-heading active" role="tab" id="headingOne">
                                            <h4 class="panel-title">
                                                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                    <h5><i class="fas fa-hand-holding-usd"></i> <strong>40% of our customers insure their trip.</strong> See all the benefits you get for just Rs <?php echo $insurance_amount; ?></h5>
                                                    <!-- <span><img src="<?= $GLOBALS['CI']->template->template_images('ins.png'); ?>"></span> -->
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                            <div class="panel-body">
                                                <div class="insrnc_lst">
                                                    <div class="insrnc_info">
                                                        <span class="insrnc_img">
                                                            <span class="insrnc_flt blk"></span>
                                                        </span>
                                                        <span class="blk ins_titl robotomedium ins_cont">Trip Cancellation</span>
                                                        <span class="blk ins_clm"> <span class="ins_clm_cont">Claim upto </span><span class="INR">Rs </span>20,000</span>
                                                    </div>
                                                </div>
                                                <div class="insrnc_lst">
                                                    <div class="insrnc_info">
                                                        <span class="insrnc_img">
                                                            <span class="insrnc_flt flt_dly blk"></span>
                                                        </span>
                                                        <span class="blk ins_titl robotomedium ins_cont">Flight Delay</span>
                                                        <span class="blk ins_clm"> <span class="ins_clm_cont">Claim upto </span><span class="INR">Rs </span>20,000</span>
                                                    </div>
                                                </div>
                                                <div class="insrnc_lst">
                                                    <div class="insrnc_info">
                                                        <span class="insrnc_img">
                                                            <span class="insrnc_flt los_bag blk"></span>
                                                        </span>
                                                        <span class="blk ins_titl robotomedium ins_cont">Loss of baggage</span>
                                                        <span class="blk ins_clm"> <span class="ins_clm_cont">Claim upto </span><span class="INR">Rs </span>20,000</span>
                                                    </div>
                                                </div>
                                                <div class="insrnc_lst cont_plus" style="overflow: hidden; display: block;">
                                                    <div class="insrnc_info">
                                                        <span class="ins_plus blk robotomedium">+7</span>
                                                        <span class="blk font_12">More</span>
                                                    </div>
                                                </div>
                                                <div class="insrnc_lst cont_show">
                                                    <div class="insrnc_info">
                                                        <span class="insrnc_img">
                                                            <span class="insrnc_flt dely_bag blk"></span>
                                                        </span>
                                                        <span class="blk ins_titl robotomedium ins_cont">Delay of baggage</span>
                                                        <span class="blk ins_clm"> <span class="ins_clm_cont">Claim upto </span><span class="INR">Rs </span>20,000</span>
                                                    </div>
                                                </div>
                                                <div class="insrnc_lst cont_show">
                                                    <div class="insrnc_info">
                                                        <span class="insrnc_img">
                                                            <span class="insrnc_flt prsnl_acdnt blk"></span>
                                                        </span>
                                                        <span class="blk ins_titl robotomedium ins_cont">Personal Accident</span>
                                                        <span class="blk ins_clm"> <span class="ins_clm_cont">Claim upto </span><span class="INR">Rs </span>20,000</span>
                                                    </div>
                                                </div>
                                                <div class="insrnc_lst cont_show">
                                                    <div class="insrnc_info">
                                                        <span class="insrnc_img">
                                                            <span class="insrnc_flt acdnt_mdcl blk"></span>
                                                        </span>
                                                        <span class="blk ins_titl robotomedium ins_cont">Accidental Medical Treatment</span>
                                                        <span class="blk ins_clm"> <span class="insurance_claim_txt">Claim upto </span><span class="INR">Rs </span>20,000</span>
                                                    </div>
                                                </div>
                                                <div class="insrnc_lst cont_show">
                                                    <div class="insrnc_info">
                                                        <span class="insrnc_img">
                                                            <span class="insrnc_flt trnsprt_mort blk"></span>
                                                        </span>
                                                        <span class="blk ins_titl robotomedium ins_cont">Transportation of Mortal Remains</span>
                                                        <span class="blk ins_clm"> <span class="insurance_claim_txt">Claim upto </span><span class="INR">Rs </span>20,000</span>
                                                    </div>
                                                </div>
                                                <div class="insrnc_lst cont_show">
                                                    <div class="insrnc_info">
                                                        <span class="insrnc_img">
                                                            <span class="insrnc_flt trp_dely blk"></span>
                                                        </span>
                                                        <span class="blk ins_titl robotomedium ins_cont">Trip Delay</span>
                                                        <span class="blk ins_clm"> <span class="insurance_claim_txt">Claim upto </span><span class="INR">Rs </span>20,000</span>
                                                    </div>
                                                </div>
                                                <div class="insrnc_lst cont_show">
                                                    <div class="insrnc_info">
                                                        <span class="insrnc_img">
                                                            <span class="insrnc_flt emrcny_htl blk"></span>
                                                        </span>
                                                        <span class="blk ins_titl robotomedium ins_cont">Emergency Hotel</span>
                                                        <span class="blk ins_clm"> <span class="insurance_claim_txt">Claim upto </span><span class="INR">Rs </span>20,000</span>
                                                    </div>
                                                </div>
                                                <div class="insrnc_lst cont_show">
                                                    <div class="insrnc_info">
                                                        <span class="insrnc_img">
                                                            <span class="insrnc_flt emrcny_trvl blk"></span>
                                                        </span>
                                                        <span class="blk ins_titl robotomedium ins_cont">Emergency Travel</span>
                                                        <span class="blk ins_clm"> <span class="insurance_claim_txt">Claim upto </span><span class="INR">Rs </span>20,000</span>
                                                    </div>
                                                </div>
                                                <div class="insrnc_lst cont_show">
                                                    <div class="insrnc_info cont_minus" style="overflow: hidden; display: block;">
                                                        <span class="ins_plus blk robotomedium"></span>
                                                        <span class="blk font_12 ins_plus">Less</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            <!-- Insuarance design ends here-->
                        <div class="fligthsdets">
<?php
$module_value = md5('flight');
/**
 * Collection field name 
 */
//Title, Firstname, Middlename, Lastname, Phoneno, Email, PaxType, LeadPassenger, Age, PassportNo, PassportIssueDate, PassportExpDate
$total_adult_count = is_array($search_data['adult_config']) ? array_sum($search_data['adult_config']) : intval($search_data['adult_config']);
$total_child_count = is_array($search_data['child_config']) ? array_sum($search_data['child_config']) : intval($search_data['child_config']);
$total_infant_count = is_array($search_data['infant_config']) ? array_sum($search_data['infant_config']) : intval($search_data['infant_config']);
//------------------------------ DATEPICKER START
$i = 1;
$datepicker_list = array();
if ($total_adult_count > 0) {
    for ($i = 1; $i <= $total_adult_count; $i++) {
        $datepicker_list[] = array('adult-date-picker-' . $i, ADULT_DATE_PICKER);
    }
}

if ($total_child_count > 0) {
    //id should be auto picked so initialize $i to previous value of $i
    for ($i = $i; $i <= ($total_child_count + $total_adult_count); $i++) {
        $datepicker_list[] = array('child-date-picker-' . $i, CHILD_DATE_PICKER);
    }
}
if ($total_infant_count > 0) {
    //id should be auto picked so initialize $i to previous value of $i
    for ($i = $i; $i <= ($total_child_count + $total_adult_count + $total_infant_count); $i++) {
        $datepicker_list[] = array('infant-date-picker-' . $i, INFANT_DATE_PICKER);
    }
}
$GLOBALS['CI']->current_page->set_datepicker($datepicker_list);
//------------------------------ DATEPICKER END
$total_pax_count = $total_adult_count + $total_child_count + $total_infant_count;
//First Adult is Primary and and Lead Pax
$adult_enum = $child_enum = get_enum_list('title');
$gender_enum = get_enum_list('gender');
unset($adult_enum[MASTER_TITLE]); // Master is for child so not required
unset($child_enum[MASTER_TITLE]); // Master is not supported in TBO list
$adult_title_options = generate_options($adult_enum, false, true);
$child_title_options = generate_options($child_enum, false, true);
$gender_options = generate_options($gender_enum);
$nationality_options = generate_options($iso_country_list, array(INDIA_CODE)); //FIXME get ISO CODE --- ISO_INDIA
$passport_issuing_country_options = generate_options($country_list);
if ($search_data['trip_type'] == 'oneway') {
    $passport_minimum_expiry_date = date('Y-m-d', strtotime($search_data['depature']));
} else if ($search_data['trip_type'] == 'circle') {
    $passport_minimum_expiry_date = date('Y-m-d', strtotime($search_data['return']));
} else {
    $passport_minimum_expiry_date = date('Y-m-d', strtotime(end($search_data['depature'])));
}

//lowest year wanted
$cutoff = date('Y', strtotime('+20 years', strtotime($passport_minimum_expiry_date)));
// echo $cutoff;exit;
//current year
$now = date('Y', strtotime($passport_minimum_expiry_date));
$now_day = date('d', strtotime($passport_minimum_expiry_date));
$now_month = date('m', strtotime($passport_minimum_expiry_date));

// debug($now_day);exit;
$day_options = generate_options(get_day_numbers($now_day));
$month_options = generate_options(get_month_names($now_month));
$year_options = generate_options(get_years($now, $cutoff));

/**
 * check if current print index is of adult or child by taking adult and total pax count
 * @param number $total_pax     total pax count
 * @param number $total_adult   total adult count
 */
function pax_type($pax_index, $total_adult, $total_child, $total_infant) {
    if ($pax_index <= $total_adult) {
        $pax_type = 'adult';
    } elseif ($pax_index <= ($total_adult + $total_child)) {
        $pax_type = 'child';
    } else {
        $pax_type = 'infant';
    }
    return $pax_type;
}

/**
 * check if current print index is of adult or child by taking adult and total pax count
 * @param number $total_pax     total pax count
 * @param number $total_adult   total adult count
 */
function is_adult($pax_index, $total_adult) {
    return ($pax_index > $total_adult ? false : true);
}

function pax_type_count($pax_index, $total_adult, $total_child, $total_infant) {
    if ($pax_index <= $total_adult) {
        $pax_count = ($pax_index);
    } elseif ($pax_index <= ($total_adult + $total_child)) {
        $pax_count = ($pax_index - $total_adult);
    } else {
        $pax_count = ($pax_index - ($total_adult + $total_child));
    }
    return $pax_count;
}

/**
 * check if current print index is of adult or child by taking adult and total pax count
 * @param number $total_pax     total pax count
 * @param number $total_adult   total adult count
 */
function is_lead_pax($pax_count) {
    return ($pax_count == 1 ? true : false);
}

function diaplay_phonecode($phone_code, $active_data, $user_country_code) {

// debug($phone_code);exit;
    $list = '';
    foreach ($phone_code as $code) {
        if (!empty($user_country_code)) {
            if ($user_country_code == $code['country_code']) {
                $selected = "selected";
            } else {
                $selected = "";
            }
        } else {

            if ($active_data['api_country_list_fk'] == $code['origin']) {
                $selected = "selected";
            } else {
                $selected = "";
            }
        }
        $list .= "<option value=" . $code['country_code'] . "  " . $selected . " >" . $code['name'] . " " . $code['country_code'] . "</option>";
    }
    return $list;
}
//$pre_booking_params['extra_services']=array();
?>
<!-- changes: change action url from pre_booking to make_secure_payment -->
                            <form action="<?= base_url() . 'index.php/flight/make_secure_payment/' . $search_data['search_id'] ?>" method="POST" autocomplete="off" id="pre-booking-form">
                                <div class="hide">
                                        <input type="text" name="redeem_points_post" id="redeem_points_post" value="0">
                                    <input type="hidden" required="required" name="search_id"       value="<?= $search_data['search_id']; ?>" />
                            <?php $dynamic_params_url = serialized_data($pre_booking_params); ?>
                                    <input type="hidden" required="required" name="token"       value="<?= $dynamic_params_url; ?>" />
                                    <input type="hidden" required="required" name="token_key"   value="<?= md5($dynamic_params_url); ?>" />
                                    <input type="hidden" required="required" name="op"          value="book_room">
                                    <input type="hidden" required="required" name="booking_source"      value="<?= $booking_source ?>" readonly>
                                    <input type="hidden" required="required" name="promo_code_discount_val" id="promo_code_discount_val" value="0.00" readonly>
                                    <input type="hidden" required="required" name="promo_code" id="promocode_val" value="" readonly>
                                         
             <input type="hidden" name="reward_usable" value="<?=round($reward_usable)?>">
                  <input type="hidden" name="reward_earned" value="<?=round($reward_earned)?>">
                  <input type="hidden" name="total_price_with_rewards" value="<?=round($total_price_with_rewards)?>">
                  <input type="hidden" name="reducing_amount" class="reduceamount" value="<?=round($reducing_amount)?>"
                                    <!--<input type="hidden" required="required" name="provab_auth_key" value="?=$ProvabAuthKey ?>" readonly>
                                    --></div>
                                <div class="clearfix"></div>
                                <div class="col-xs-12 <?php echo $insurance_status; ?>">
                                    <div class="radio">
                                        <label><input class="add_insurance" type="radio" value="<?php echo $insurance_amount; ?>" name="insurance" >Yes, secure my trip with insurance. I agree to the <a href="#">terms &amp; conditions</a></label>
                                    </div>
                                    <div class="radio">
                                        <label><input class="remove_insurance" type="radio" value="0" name="insurance" checked>No, I do not want to insure my trip</label>
                                    </div>
                                </div>
                                 <div class="clearfix"></div>
                                <div class="flitab1">
                                    <div class="moreflt boksectn">
                                        <div class="ontyp">
                                            <div class="labltowr arimobold">Please enter names as on passport. </div>
<?php
$pax_index = 1;
$lead_pax_details = @$pax_details[0];
// debug($lead_pax_details);
if (is_logged_in_user()) {
    $traveller_class = ' user_traveller_details';
} else {
    $traveller_class = '';
}
for ($pax_index = 1; $pax_index <= $total_pax_count; $pax_index++) {//START FOR LOOP FOR PAX DETAILS
    $cur_pax_info = is_array($pax_details) ? array_shift($pax_details) : array();

    $pax_type = pax_type($pax_index, $total_adult_count, $total_child_count, $total_infant_count);
    $pax_type_count = pax_type_count($pax_index, $total_adult_count, $total_child_count, $total_infant_count);

    if ($pax_type != 'infant') {
        $extract_pax_name_cls = ' extract_pax_name_cls ';

    } else {
        $extract_pax_name_cls = '';
    }
    ?>
                                                <div class="pasngr_input pasngrinput _passenger_hiiden_inputs">
                                                    <div class="hide hidden_pax_details">
                                                        <input type="hidden" name="passenger_type[]" value="<?= ucfirst($pax_type) ?>">
                                                        <input type="hidden" name="lead_passenger[]" value="<?= (is_lead_pax($pax_index) ? true : false) ?>">
                                                        <input type="hidden" name="gender[]" value="1" class="pax_gender">
                                                        <input type="hidden" required="required" name="passenger_nationality[]" id="passenger-nationality-<?= $pax_index ?>" value="<?php  echo $passnationality; ?>">
                                                    </div>
                                                    <div class="col-xs-1 nopadding full_dets_aps">
                                                        <div class="adltnom"><?= ucfirst($pax_type) ?><?= $pax_type_count ?><?= $mandatory_filed_marker ?></div>
                                                    </div>
                                                    <div class="col-xs-11 nopadding full_dets_aps">

                                                        <div class="inptalbox">
                                                            <div class="col-xs-12 col-md-3 spllty">
                                                                <div class="selectedwrap">
                                                                    <select class="mySelectBoxClass flyinputsnor name_title" name="name_title[]" required>
    <?php echo (is_adult($pax_index, $total_adult_count) ? $adult_title_options : $child_title_options) ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-5 spllty">
                                                                <input value="<?php if(ctype_alpha($cur_pax_info['first_name'])){ echo $cur_pax_info['first_name'];} ?>" required="required" type="text" name="first_name[]" id="passenger-first-name-<?= $pax_index ?>" class="<?= $extract_pax_name_cls ?> clainput   <?= $traveller_class.'_'.$pax_type ?>" maxlength="20" data-id="<?=$pax_type?>" placeholder="Enter First Name" data-row-id="<?= ($pax_index); ?>"/>
                                                            </div>
                                                            <div class="col-xs-4 spllty">
                                                                <input value="<?php if(ctype_alpha($cur_pax_info['first_name']) && ctype_alpha($cur_pax_info['last_name'])){ echo $cur_pax_info['last_name'];} ?>" required="required" type="text" name="last_name[]" id="passenger-last-name-<?= $pax_index ?>" class="<?= $extract_pax_name_cls ?> clainput  " maxlength="20" placeholder="Enter Last Name" />
                                                            </div>
    <?php if ($pax_type == 'infant') {//Only For Infant  ?>
                                                                <div class="col-xs-6 spllty infant_dob_div">
                                                                    <!-- <div class="col-xs-4 nopadding"><span class="fmlbl">Date of Birth <?= $mandatory_filed_marker ?></span></div> -->
                                                                    <!-- <div class="col-xs-12 col-md-8 nopadding"> -->
                                                                        <!--id changed here-->         <div id="dob__input" class="nopadding">
                                                                        <!-- <span class="fmlbl">Date of Birth <?= $mandatory_filed_marker ?></span> -->
                                                                        <input placeholder="Date of Birth" type="text" class="clainput"  name="date_of_birth[]" readonly <?= (is_adult($pax_index, $total_adult_count) ? 'required="required"' : 'required="required"') ?> id="<?= strtolower(pax_type($pax_index, $total_adult_count, $total_child_count, $total_infant_count)) ?>-date-picker-<?= $pax_index ?>">
                                                                    </div>
                                                                </div>
        <?php
    } else { //Adult/Child
        if (($pax_type == 'adult' && $is_domestic_flight == false)) {
            ?> 
                                                                    <div class="col-xs-6 spllty infant_dob_div">
                                                                        <!-- <div class="col-xs-4 nopadding"><span class="fmlbl">Date of Birth <?= $mandatory_filed_marker ?></span></div> -->
                                                                        <!-- <div class="col-xs-8 nopadding"> -->
                                                                            <!--id changed here-->         <div id="dob__input" class="nopadding">
                                                                            <!-- <span class="fmlbl">Date of Birth <?= $mandatory_filed_marker ?></span> -->
                                                                            <input placeholder="Date of Birth" type="text" class="clainput"  name="date_of_birth[]" readonly <?= (is_adult($pax_index, $total_adult_count) ? 'required="required"' : 'required="required"') ?> id="<?= strtolower(pax_type($pax_index, $total_adult_count, $total_child_count, $total_infant_count)) ?>-date-picker-<?= $pax_index ?>">
                                                                        </div>
                                                                    </div>
        <?php } else if (($pax_type == 'child')) { ?>
                                                                    <div class="col-xs-6 spllty infant_dob_div">
                                                                        <!-- <div class="col-xs-4 nopadding"><span class="fmlbl">Date of Birth <?= $mandatory_filed_marker ?></span></div> -->
                                                                        <!--id changed here-->         <div id="dob__input" class="nopadding">
                                                                        <!-- <span class="fmlbl">Date of Birth <?= $mandatory_filed_marker ?></span> -->
                                                                        <!-- <div class="col-xs-12 col-md-8 nopadding"> -->
                                                                            <input placeholder="Date of Birth" type="text" class="clainput"  name="date_of_birth[]" readonly <?= (is_adult($pax_index, $total_adult_count) ? 'required="required"' : 'required="required"') ?> id="<?= strtolower(pax_type($pax_index, $total_adult_count, $total_child_count, $total_infant_count)) ?>-date-picker-<?= $pax_index ?>">
                                                                        </div>
                                                                    </div>
        <?php }
    } ?>
                                                            <div class="clearfix"></div>
                                                            <!-- Passport Section Starts -->
                                                            <div class="passport_content_div">
    <?php if ($is_domestic_flight == false) {$addob = date('Y-m-d', strtotime('-30 years')); //For Internatinal Travel  ?>
                                                                    <!--<input placeholder="DOB" type="hidden" class="clainput" name="date_of_birth[]" value="<?php echo $addob?>">-->
                                                                    <div class="international_passport_content_div">
                                                                        <div class="col-xs-4 spllty">
                                                                            <span class="formlabel">Passport Number <?= $pass_mand ?></span>
                                                                            <div class="relativemask"> 
                                                                                <input type="text" name="passenger_passport_number[]" <?= $pass_req ?> id="passenger_passport_number_<?= $pax_index ?>" class="clainput" maxlength="10" placeholder="Passport Number" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-xs-3 spllty">
                                                                            <span class="formlabel">Issuing Country <?= $pass_mand ?></span>
                                                                            <div class="selectedwrap">
                                                                                <select name="passenger_passport_issuing_country[]" <?= $pass_req ?> id="passenger_passport_issuing_country_<?= $pax_index ?>" class="mySelectBoxClass flyinputsnor">
                                                                                    <option value="INVALIDIP">Please Select</option>
        <?= $passport_issuing_country_options ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                 
                                                                        <div class="col-xs-5 spllty">
                                                                            <span class="formlabel">Date of Expire <?= $pass_mand ?></span>
                                                                             <!-- Changed layout of this div to fix tab error previour:(dd/mm/yyyy)-> now (yyyy/mm/dd) -->
                                                                            <div class="relativemask">
                                                                                <div class="col-xs-4 splinmar">
                                                                                    <div class="selectedwrap">
                                                                                        <select
                                                                                            name="passenger_passport_expiry_year[]"
                                                                                            <?= $pass_req ?>
                                                                                            class="mySelectBoxClass flyinputsnor passport_expiry_year"
                                                                                            data-expiry-type="year"
                                                                                            id="passenger_passport_expiry_year_<?= $pax_index ?>"
                                                                                            data-row-id="<?= ($pax_index); ?>">
                                                                                            <option value="INVALIDIP">YYYY
                                                                                            </option>
                                                                                            <?= $year_options; ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-xs-4 splinmar">
                                                                                    <div class="selectedwrap">
                                                                                        <select
                                                                                            name="passenger_passport_expiry_month[]"
                                                                                            <?= $pass_req ?>
                                                                                            class="mySelectBoxClass flyinputsnor passport_expiry_month"
                                                                                            data-expiry-type="month"
                                                                                            id="passenger_passport_expiry_month_<?= $pax_index ?>"
                                                                                            data-row-id="<?= ($pax_index); ?>">
                                                                                            <option value="INVALIDIP">MM
                                                                                            </option>
                                                                                            <?= $month_options; ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-xs-4 splinmar">
                                                                                    <div class="selectedwrap">
                                                                                        <select
                                                                                            name="passenger_passport_expiry_day[]"
                                                                                            <?= $pass_req ?>
                                                                                            class="mySelectBoxClass flyinputsnor passport_expiry_day"
                                                                                            data-expiry-type="day"
                                                                                            id="passenger_passport_expiry_day_<?= $pax_index ?>"
                                                                                            data-row-id="<?= ($pax_index); ?>">
                                                                                            <option value="INVALIDIP">DD
                                                                                            </option>
                                                                                            <?= $day_options; ?>
                                                                                        </select>
                                                                                        <input type="hidden"
                                                                                            value="<?php echo $now; ?>"
                                                                                            id="travel_year">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                    </div>
                                                                        <div class="pull-right text-danger hide" id="passport_error_msg_<?= $pax_index ?>"></div>
                                                                    </div>
        <?php
    } else { //For Domestic Travel, Set Static Passport Data
        $passport_number = rand(1111111111, 9999999999);
        $passport_issuing_country = 92;
        ?>
                                                                    <div class="domestic_passport_content_div hide">
                                                                        <input type="hidden" name="passenger_passport_number[]" value="<?= $passport_number ?>" id="passenger_passport_number_<?= $pax_index ?>">
                                                                        <input type="hidden" name="passenger_passport_issuing_country[]" value="<?= $passport_issuing_country ?>" id="passenger_passport_issuing_country_<?= $pax_index ?>">
                                                                        <input type="hidden" name="passenger_passport_expiry_day[]" value="<?= $static_passport_details['passenger_passport_expiry_day'] ?>" id="passenger_passport_expiry_day_<?= $pax_index ?>">
                                                                        <input type="hidden" name="passenger_passport_expiry_month[]" value="<?= $static_passport_details['passenger_passport_expiry_month'] ?>" id="passenger_passport_expiry_month_<?= $pax_index ?>">
                                                                        <input type="hidden" name="passenger_passport_expiry_year[]" value="<?= $static_passport_details['passenger_passport_expiry_year'] ?>" id="passenger_passport_expiry_year_<?= $pax_index ?>">
                                                                    </div>
    <?php } ?>
                                                            </div>
                                                            <!-- Passport Section Ends-->

                                                        </div><!-- inptalbox class ends -->
                                                    </div>
                                                </div>
    <?php
}//END FOR LOOP FOR PAX DETAILS
?>
                                        </div>
                                    </div>
                                     <!-- For guest users -->
                                    <?php if (!is_logged_in_user()) { ?>
                                        <form name="promocode" id="promocode" novalidate>
                                            <?php
                                            $promo_to_location = explode(" ", $search_data['to_city']);
                                            $promo_for_location = explode(" ", $search_data['from_city']);


                                            $promo_for_city = md5($promo_for_location[0]);
                                            $promo_to_city = md5($promo_to_location[0]);

                                            ?>

                                            <input type="hidden" placeholder="Enter Promo" name="code" id="code"
                                                class="promocode" aria-required="true" maxlength="16" />

                                            <input type="hidden" name="module_type" id="module_type" class="promocode"
                                                value="<?= @$module_value; ?>" />
                                            <?php
                                            if (is_logged_in_user()) {
                                                $tmt = ($FareDetails['TotalFare'] - $convenience_fees) - $discount;
                                            } else {
                                                $tmt = ($FareDetails['TotalFare'] + $convenience_fees) - $discount;
                                            }
                                            ?>
                                            <input type="hidden" name="total_amount_val" id="total_amount_val"
                                                class="promocode" value="<?= @$tmt ?>" />
                                            <input type="hidden" name="convenience_fee" id="convenience_fee"
                                                class="promocode" value="0" />
                                            <input type="hidden" name="convenience_fee_gst" id="convenience_fee_gst"
                                                class="promocode" value="<?= @$gst_value_conv; ?>" />
                                            <input type="hidden" name="currency_symbol" id="currency_symbol"
                                                value="<?= @$currency_symbol; ?>" />
                                            <input type="hidden" name="currency" id="currency" value="<?= @$currency; ?>" />
                                            <?php /*
<input type="hidden" name="promo_for_city" data-val="<?=$promo_for_location[0]?>" id="promo_for_city" value="<?=@$promo_for_city?>" />
<input type="hidden" name="promo_to_city" data-val="<?=$promo_to_location[0]?>" id="promo_to_city" value="<?=@$promo_to_city?>" />
*/?>

                                            <input type="hidden" name="promo_for_city" id="promo_for_city"
                                                value="<?= @$promo_for_city ?>" />
                                            <input type="hidden" name="promo_to_city" id="promo_to_city"
                                                value="<?= @$promo_to_city ?>" />



                                            </ </form>
                                        <?php } ?>
                                        <!-- End of for guest users -->
                                    <!-- start of promocode -->
                    <?php if (is_logged_in_user()) {?>
                                    <div class="ontyp">
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
                                                        <?php 
                                                        $promo_to_location=explode(" ",$search_data['to_city']);
                                                        $promo_for_location=explode(" ",$search_data['from_city']);
                                                        
                                                        
                                                        $promo_for_city= md5($promo_for_location[0]);
                                                        $promo_to_city= md5($promo_to_location[0]);
                                                        
                                                        ?>
                                                        <div class="col-md-4 col-xs-8 nopadding_right">
                                                            <div class="cartprc">
                                                                <div class="payblnhm singecartpricebuk ritaln">
                                                                    <input type="text" placeholder="Enter Promo" name="code" id="code" class="promocode" aria-required="true" maxlength="16" />
                                                                    <input type="hidden" name="module_type" id="module_type" class="promocode" value="<?= @$module_value; ?>" />
                                                                    <input type="hidden" name="total_amount_val" id="total_amount_val" value="<?= @round($FareDetails['TotalFare'])- $discount; ?>" />
                                                                    <input type="hidden" name="convenience_fee" id="convenience_fee" class="promocode" value="<?= @$convenience_fees; ?>" />
                                                                    <input type="hidden" name="convenience_fee_gst" id="convenience_fee_gst" class="promocode" value="<?= @$gst_value_conv; ?>" />
                                                                    <input type="hidden" name="currency_symbol" id="currency_symbol" value="<?= @$currency_symbol; ?>" />
                                                                    <input type="hidden" name="currency" id="currency" value="<?= @$currency; ?>" />
                                                                    <?php /*
                                                                    <input type="hidden" name="promo_for_city" data-val="<?=$promo_for_location[0]?>" id="promo_for_city" value="<?=@$promo_for_city?>" />
                                                                    <input type="hidden" name="promo_to_city" data-val="<?=$promo_to_location[0]?>" id="promo_to_city" value="<?=@$promo_to_city?>" /> 
                                                                    */ ?>
                                                                    
                                                                    <input type="hidden" name="promo_for_city" id="promo_for_city" value="<?=@$promo_for_city?>" />
                                                                    <input type="hidden" name="promo_to_city" id="promo_to_city" value="<?=@$promo_to_city?>" />

                                                                    <p class="error_promocode text-danger" style="font-weight:bold"></p>                                        
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2 col-xs-4 nopadding_left">
                                                            <input type="button" value="Apply" name="apply" id="apply" class="promosubmit">
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="savemessage"></div>
                                            </div>
                                        </div>
                                    </div>
                                                      <?php }?>
                                    <!-- end of promo code -->
                                    
                                    <div class="sepertr"></div>
                                    <div class="clearfix"></div>
                                    <div class="contbk">
                                        <div class="contcthdngs">CONTACT DETAILS</div>
                                        <div class="col-xs-12 nopad full_smal_forty">
                                            <div class="col-xs-12 mb10 nopad full_smal_forty">
                                                <div class="col-xs-5 nopadding">
                                                    <div class="hide">
                                                        <input type="hidden" name="billing_country" value="140">
                                                        <input type="hidden" name="billing_city" value="test">
                                                        <input type="hidden" name="billing_zipcode" value="test">
                                                        <input type="hidden" name="billing_address_1" value="test">

                                                    </div>
                                                    <select name="phone_country_code" class="newslterinput nputbrd _numeric_only" id="after_country_code" required>
<?php echo diaplay_phonecode($phone_code, $active_data, $user_country_code); ?>
                                                    </select> 
                                                </div>
                                                <div class="col-xs-1 nopadding">
                                                    <div class="sidepo">-</div>
                                                </div>
                                                <div class="col-xs-6 nopadding">
                                                    <input value="<?= @$lead_pax_details['phone'] == 0 ? '' : @$lead_pax_details['phone']; ?>" type="text" name="passenger_contact" id="passenger-contact" placeholder="Mobile Number" class="newslterinput nputbrd _numeric_only" maxlength="10" required="required">
                                                </div>
                                            </div>


                                            <div class="emailperson col-xs-12    nopad full_smal_forty">
                                                <input value="<?= @$lead_pax_details['email'] ?>" type="text" maxlength="80" required="required" id="billing-email" class="newslterinput nputbrd" placeholder="Email" name="billing_email">
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="notese">Your mobile number will be used only for sending flight related communication.</div>
                                    </div>
                                   <!-- <div class="panel-group mb0" role="tablist" aria-multiselectable="true">
                                        <div class="panel panel-default for_gst flight_special_req">
                                            <div class="panel-heading" role="tab" id="gst_opt1">
                                                <h4 class="panel-title">
                                                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#gst_optnl" aria-expanded="true" aria-controls="gst_optnl">

                                                        <div class="labltowr arimobold">GST number for business travel (Optional) <i class="more-less glyphicon glyphicon-plus"></i></div>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="gst_optnl" class="panel-collapse collapse" role="tabpanel" aria-labelledby="gst_opt">
                                                <!-- <div class="contcthdngs">GST Information(Optional)</div> 
                                                <div class="col-xs-12 gst_det" id="gst_form_div">
                                                    <div class="row">
                                                        <div class="col-xs-5"> GST Number </div>
                                                        <div class="col-xs-7"> 
                                                            <input type="text" class="newslterinput clainput nputbrd" id="gst_number" name="gst_number" value="" maxlength="25">  
                                                            <div class="clearfix"></div>
                                                 <div class="gst_number_error alert-danger hide" style="width:250px;">Please Enter Valid GST Number</div>  
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-5"> GST company Name </div>
                                                        <div class="col-xs-7"> 
                                                            <input type="text" class="newslterinput nputbrd" id="gst_company_name" name="gst_company_name" vaule="" maxlength="40">  
                                                            <div class="clearfix"></div>
                                                 <div class="gst_name_error alert-danger hide" style="width:250px;">Please Enter Valid Company Name</div>    
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-5"> Email </div>
                                                        <div class="col-xs-7"> 
                                                            <input type="email" class="newslterinput nputbrd" id="gst_email" name="gst_email" value="" maxlength="25"> 
                                                            <div class="clearfix"></div>
                                        <div class="gst_email_error alert-danger hide" style="width:250px;">Please Enter Valid Email</div>       
                                                        </div>
                                                    </div>                                         
                                                    <div class="row">
                                                        <div class="col-xs-5"> Phone Number </div>
                                                        <div class="col-xs-7"> 
                                                            <input type="text" class="newslterinput nputbrd _numeric_only" id="gst_phone" name="gst_phone" maxlength="10" value="" >
                                                        <div class="clearfix"></div>
                                        <div class="gst_phone_error alert-danger hide" style="width:250px;">Please Enter Valid Phone Number</div> 
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-5"> Address </div>
                                                        <div class="col-xs-7"> 
                                                            <input type="text" class="newslterinput nputbrd" name="gst_address" id="gst_address" value="" maxlength="100">  
                                                             <div class="clearfix"></div>
                                        <div class="gst_address_error alert-danger hide" style="width:250px;">Please Enter Valid Address</div>  
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-5"> State </div>
                                                        <div class="col-xs-7">
<?php $state_list = generate_options($state_list); ?>
                                                            <select id="gst_state" name="gst_state" class="newslterinput nputbrd clainput" >
                                                                <option value="INVALIDIP">Please Select</option>
                                                            <?= $state_list ?>
                                                            </select>
                                                           <div class="clearfix"></div>
                                        <div class="gst_state_error alert-danger hide" style="width:250px;">Please Enter Valid State</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>-->
                                   
                                    <div class="clearfix"></div>
                                    <div class="sepertr"></div>
                                    <div class="clearfix"></div>
                                    <!-- Dyanamic Baggage&Meals Section Starts -->
<?php
if (valid_array($extra_services) == true) {
    if (isset($extra_services['ExtraServiceDetails']['Baggage'])) {
        $baggage_meal_seat_details['baggage_meal_details']['Baggage'] = $extra_services['ExtraServiceDetails']['Baggage'];
    }
    if (isset($extra_services['ExtraServiceDetails']['Meals'])) {
        $baggage_meal_seat_details['baggage_meal_details']['Meals'] = $extra_services['ExtraServiceDetails']['Meals'];
    }
    if (isset($extra_services['ExtraServiceDetails']['Seat'])) {
        $baggage_meal_seat_details['baggage_meal_details']['Seat'] = $extra_services['ExtraServiceDetails']['Seat'];
    }
    $baggage_meal_seat_details['total_adult_count'] = $total_adult_count;
    $baggage_meal_seat_details['total_child_count'] = $total_child_count;
    $baggage_meal_seat_details['total_infant_count'] = $total_infant_count;
    $baggage_meal_seat_details['total_pax_count'] = $total_pax_count;
    echo $GLOBALS['CI']->template->isolated_view('flight/dynamic_baggage_meal_seat_details', $baggage_meal_seat_details);
}
?>
                                    <!-- Dyanamic Baggage&Meals Section Ends -->
                                    <!-- Seats&Meals Preference Section Starts -->
                                    <?php
                                    if (valid_array($extra_services) == true) {
                                        if (isset($extra_services['ExtraServiceDetails']['MealPreference'])) {
                                            $seat_meal_preference_details['seat_meal_preference_details']['MealPreference'] = $extra_services['ExtraServiceDetails']['MealPreference'];
                                        }
                                        if (isset($extra_services['ExtraServiceDetails']['SeatPreference'])) {
                                            $seat_meal_preference_details['seat_meal_preference_details']['SeatPreference'] = $extra_services['ExtraServiceDetails']['SeatPreference'];
                                        }
                                        $seat_meal_preference_details['total_adult_count'] = $total_adult_count;
                                        $seat_meal_preference_details['total_child_count'] = $total_child_count;
                                        $seat_meal_preference_details['total_infant_count'] = $total_infant_count;
                                        $seat_meal_preference_details['total_pax_count'] = $total_pax_count;
                                        echo $GLOBALS['CI']->template->isolated_view('flight/seat_meal_preference_details', $seat_meal_preference_details);
                                    }
                                    ?>
                                    <!-- Seats&Meals Preference Section Ends -->
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
                                            

                                            <div class="clearfix"></div>

                                            <div class="clikdiv">
                                            <div class="squaredThree">
                                            <input id="terms_cond1" type="checkbox" name="tc" required="required">
                                            <label for="terms_cond1"></label>
                                            </div>
                                            <span class="clikagre" id="clikagre">
                                            <a target="_blank" href="<?=base_url().'index.php/'?>terms">Terms and Conditions</a>
                                            </span>
                                            </div>

                                            <div class="clearfix"></div>
                                            <p style="color:red;" id="passenger_error"></p>
                                            <div class="continye col-sm-4 col-xs-6 nopad">
                                                <button type="submit" id="flip" name="flight" class="bookcont continue_booking_button" style="margin-left: 10px!important">Continue</button>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="sepertr"></div>
                                            <div class="temsandcndtn">
                                                Most countries require travellers to have a passport valid for more than 3 to 6 months from the date of entry into or exit from the country. Please check the exact rules for your destination country before completing the booking.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-xs-4 nopadding rit_summery a">
                          <?php if($reward_usable){ ?>
                  <div class="redmpoints">
                  <div class="col-xs-6">Redeem rewards 
                    
                    <label class="switch"> <input type="checkbox" id="redeem_points"
data-toggle="toggle" data-size="mini" name="redeem_points"> <span class="slider_rew
round"></span> </label> </div> <div class="col-xs-6"><span
id="booking_amount"><?=round($reward_usable)." Points"?></span></div>  <?php } ?>
<?php if($reward_earned){ ?> <div class="col-xs-6">Earning reward</div>
<div class="col-xs-6"><span class="label label-primary"><?=$reward_earned." Points"?></span></div> 
<?php } ?> </div> 
<?php echo get_fare_summary($FareDetails, $PassengerFareBreakdown, $convenience_fees, $insurance_amount, $insurance_status, $org_convience_fee, $ApiPriceDetails, $api_markup_details, $convenience_fees_orginal,$gst_value_conv, $discount); ?>
                    </div>
                        <?php if (is_logged_in_user() == true) { ?>
                        <div class="col-xs-12 col-md-4 nopadding">
                            <div class="insiefare v">
                                <div class="farehd arimobold">Passenger List</div>
                                <div class="fredivs">
                                    <div class="psngrnote">
    <?php
    if (valid_array($traveller_details)) {
        $traveller_tab_content = 'You have saved passenger details in your list,on typing, passenger details will auto populate.';
    } else {
        $traveller_tab_content = 'You do not have any passenger saved in your list, start adding passenger so that you do not have to type every time. <a href="' . base_url() . 'index.php/user/profile?active=traveller" target="_blank">Add Now</a>';
    }
    ?>
                                        <?= $traveller_tab_content; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
<?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<span class="hide">
    <input type="hidden" id="pri_passport_min_exp" value="<?= $passport_minimum_expiry_date ?>">
</span>

<?php echo $GLOBALS['CI']->template->isolated_view('share/flight_session_expiry_popup'); ?>
<?php echo $GLOBALS['CI']->template->isolated_view('share/passenger_confirm_popup'); ?>

<?php
/*
 * Balu A
 * Flight segment details
 * Outer summary and Inner Summary
 */

function flight_segment_details($SegmentDetails, $SegmentSummary) {
    $loc_dir_icon = '<span class="fadr fa fa-long-arrow-right textcntr"></span>';
    $inner_summary = $outer_summary = '';
    //Inner Summary
    foreach ($SegmentDetails as $__segment_k => $__segment_v) {
        $segment_summary = $SegmentSummary[$__segment_k];
        //Calculate Total Duration of Onward/Return Journey
        $inner_summary .= '<div class="ontyp">';
        //Way Summary in one line - Start
        $inner_summary .= '<div class="labltowr arimobold">';
        $inner_summary .= $segment_summary['OriginDetails']['CityName'] . ' to ' . $segment_summary['DestinationDetails']['CityName'];
        $inner_summary .= '</div>';
        //Way Summary in one line - End
        foreach ($__segment_v as $__stop => $__segment_flight) {
            //Summary of Way - Start
            $inner_summary .= '<div class="allboxflt">';
            //airline
            $inner_summary .= '<div class="col-xs-3 nopadding width_adjst">
                                    <div class="jetimg">
                                    <img  alt="' . $__segment_flight['AirlineDetails']['AirlineCode'] . '" src="' . SYSTEM_IMAGE_DIR . 'airline_logo/' . $__segment_flight['AirlineDetails']['AirlineCode'] . '.gif" >
                                    </div>
                                    <div class="alldiscrpo">
                                    ' . $__segment_flight['AirlineDetails']['AirlineName'] . '
                                    <span class="sgsmal">' . $__segment_flight['AirlineDetails'][''] . ' 
                                    ' . $__segment_flight['AirlineDetails']['FlightNumber'] . '</span>
                                    </div>
                                  </div>';
            //depart
            $inner_summary .= '<div class="col-xs-7 nopadding width_adjst">';
            $inner_summary .= '<div class="col-xs-6">
                                    <span class="airlblxl">' . month_date_year_time($__segment_flight['OriginDetails']['DateTime']) . '</span>
                                    <span class="portnme">' . $__segment_flight['OriginDetails']['AirportName'] . ' (' . $__segment_flight['OriginDetails']['AirportCode'] . ')</span>
                                    </div>';
            //direction indicator
            // $inner_summary .= '<div class="col-xs-2">
            // '.$loc_dir_icon.'</div>';
            //arrival
            $inner_summary .= '<div class="col-xs-6">
                                    <span class="airlblxl">' . month_date_year_time($__segment_flight['DestinationDetails']['DateTime']) . '</span>
                                    <span class="portnme">' . $__segment_flight['DestinationDetails']['AirportName'] . ' (' . $__segment_flight['DestinationDetails']['AirportCode'] . ')</span>
                                    </div>';
            $inner_summary .= '</div>';

            //Between Content -----
            $inner_summary .= '<div class="col-xs-2 nopadding width_adjst">
                                <span class="portnme textcntr">' . $__segment_flight['SegmentDuration'] . '</span>
                                <span class="portnme textcntr">Stop : ' . ($__stop) . '</span>
                                </div>';
            //Summary of Way - End
            $inner_summary .= '</div>';
            if (isset($__segment_v['WaitingTime']) == true) {
                $next_seg_info = $seg_v[$seg_details_k + 1];
                $waiting_time = $__segment_v['WaitingTime'];
                $inner_summary .= '
            <div class="clearfix"></div>
            <div class="connectnflt">
                <div class="conctncentr">
                <span class="fa fa-plane"></span>Change of planes at ' . $next_seg_info['OriginDetails']['AirportName'] . ' | <span class="fa fa-clock-o"></span> Waiting : ' . $waiting_time . '
            </div>
            </div>
            <div class="clearfix"></div>';
            }
        }
        $inner_summary .= '</div>';
    }
    //Outer Summry
    $total_stop_count = 0;
    $outer_summary .= '<div class="moreflt spltopbk">';
    foreach ($SegmentSummary as $__segment_k => $__segment_v) {
        $total_segment_travel_duration = $__segment_v['TotalDuaration'];
        $__stop_count = $__segment_v['TotalStops'];
        $total_stop_count += $__stop_count;
        $outer_summary .= '<div class="ontypsec">
                        <div class="allboxflt">';
        //airline
        $outer_summary .= '<div class="col-xs-3 nopadding width_adjst">
                            <div class="jetimg">
                            <img class="airline-logo" alt="' . $__segment_v['AirlineDetails']['AirlineCode'] . '" src="' . SYSTEM_IMAGE_DIR . 'airline_logo/' . $__segment_v['AirlineDetails']['AirlineCode'] . '.gif">
                            </div>
                            <div class="alldiscrpo">
                                    <span class="air_name">' . $__segment_v['AirlineDetails']['AirlineName'] . '</span>
                                    <span class="sgsmal"> ' . $__segment_v['AirlineDetails'][''] . '' . $__segment_v['AirlineDetails']['FlightNumber'] . '</span>
                            </div>
                          </div>';
        $outer_summary .= '<div class="col-xs-7 nopadding width_adjst">';
        //depart
        $outer_summary .= '<div class="col-xs-6">
                                    <span class="airlblxl">' . $__segment_v['OriginDetails']['AirportName'] . ' (' . $__segment_v['OriginDetails']['AirportCode'] . ')</span>
                                    <span class="portnme">' . month_date_year_time($__segment_v['OriginDetails']['DateTime']) . '</span>
                            </div>';
        //direction indicator
        //$outer_summary .= '<div class="col-xs-2"><span class="fadr fa fa-long-arrow-right textcntr"></span></div>';
        //arrival
        $outer_summary .= '<div class="col-xs-6">
                                    <span class="airlblxl">' . $__segment_v['DestinationDetails']['AirportName'] . ' (' . $__segment_v['DestinationDetails']['AirportCode'] . ')</span>
                                    <span class="portnme">' . month_date_year_time($__segment_v['DestinationDetails']['DateTime']) . '</span>
                                </div>
                                </div>';
        //Stops/Class details
        $outer_summary .= '<div class="col-xs-2 nopadding width_adjst">
                                <span class="portnme textcntr">' . ($total_segment_travel_duration) . '</span>
                                <span class="portnme textcntr" >Stop:' . ($__stop_count) . '</span>
                        </div>';
        $outer_summary .= '</div></div>';
    }
    $outer_summary .= '</div>';
    return array('segment_abstract_details' => $outer_summary, 'segment_full_details' => $inner_summary);
}

function get_fare_summary($FareDetails, $PassengerFareBreakdown, $convenience_fees, $insurance_amount,$insurance_status, $org_convience_fee, $ApiPriceDetails, $api_markup_details, $convenience_fees_orginal,$gst_value_conv='', $discount=0) {
           $currency_obj = new Currency(array('module_type' => 'flight', 'from' => provab_get_api_data_currency(), 'to' => get_application_currency_preference()));    
//  debug($FareDetails);
    $api_price_details = base64_encode(json_encode($ApiPriceDetails));
    $api_markup_details = base64_encode(json_encode($api_markup_details));
    $convenience_fees_orginal = base64_encode(json_encode($convenience_fees_orginal));
    // $total_tax = $FareDetails['TotalTax'] - $FareDetails['_GST'];

   
    // $total_tax = round($FareDetails['TotalTax']+$ApiPriceDetails['PriceBreakup']['AgentCommission']+@$ApiPriceDetails['PriceBreakup']['PLBEarned']);
    $total_tax = round($FareDetails['TotalTax']);

    $flight_total_price = round(($FareDetails['TotalFare']+ $convenience_fees) - $discount);
    $currency_symbol = $FareDetails['CurrencySymbol'];
    $gst_data = '';
    $i = 0;
    if ($gst_value_conv > 0) {
        $gst_data = '<div class="col-xs-8 nopadding"> 
                        <div class="faresty">GST</div>
                        </div>
                    <div class="col-xs-4 nopadding">
                        <div class="amnter arimobold">' . $currency_symbol . ' ' . $gst_value_conv . ' </div>
                    </div>
                    ';
    }
    
    $currency = $FareDetails['Currency'];
    $fare_summary = '<div class="insiefare">
                <div class="farehd arimobold">Fare Summary</div>
                <div class="fredivs">';
    $pax_base_fare_details = '<div class="kindrest">
                            <div class="freshd">Base Fare</div>';
    $pax_tax_details = '<div class="kindrest">
                                <div class="freshd">Taxes</div>';
    // debug($ApiPriceDetails['AgentCommission']);
    foreach ($PassengerFareBreakdown as $k => $v) {
      //  debug($v);die;
        $pax_type = $v['PassengerType'];
       $pax_base_fare = round($v['BaseFare']);
        $pax_count = $v['Count'];
        $pax_base_fare_details .= '<div class="reptallt">
                        <div class="col-xs-8 nopadding">
                            <div class="faresty">' . $pax_count . ' ' . $pax_type . '(s) </div>
                        </div>
                        <div class="col-xs-4 nopadding">
                            <div class="amnter"><span class="base_fare_span">' . $currency_symbol . '</span> <span class="base_fare_value'.$i.'">' . $pax_base_fare. ' </span></div>
                        </div>
                    </div>';
        $i++;
    }
$extar_service_charge_details='';
$extar_service_charge_details = '<div class="clearfix"></div><div class="">';
    $extar_service_charge_details .= '<div class="baggagecharge-agent" id="extra_baggage_charge_label" style="display:none">
                                                        <div class="col-xs-8 nopadding">Extra Baggage Charge
                                                        <span class="btn btn-sm btn-default" id="remove_extra_baggage"><i class="fa fa-times" aria-hidden="true"></i></span></div>
                                                        <div class="col-xs-4 nopadding text-right">' . $currency_symbol . ' 
                                                            <span class="amnter arimobold" id="extra_baggage_charge"></span>
                                                           
                                                            </div>
                                                    </div>
                                                    <div class="baggagecharge-agent" id="extra_meal_charge_label" style="display:none">
                                                        <div class="col-xs-8 nopadding">Meal Charge
                                                        <span class="btn btn-sm btn-default" id="remove_extra_meal"><i class="fa fa-times" aria-hidden="true"></i></span></div>
                                                            <div class="col-xs-4 nopadding text-right">' . $currency_symbol . '
                                                            <span class="amnter arimobold" id="extra_meal_charge"></span>
                                                           
                                                            </div>
                                                    </div>
                                                    <div class="baggagecharge-agent" id="extra_seat_charge_label" style="display:none">
                                                        <div class="col-xs-8 nopadding">Seat Charge
                                                        <span class="btn btn-sm btn-default" id="remove_extra_seat"><i class="fa fa-times" aria-hidden="true"></i></span></div>
                                                            <div class="col-xs-4 nopadding text-right">' . $currency_symbol . '
                                                            <span class="amnter arimobold" id="extra_seat_charge"></span>
                                                           
                                                            </div>
                                                    </div>
                                                    ';

    $extar_service_charge_details .= '</div><div class="clearfix"></div>';

    $pax_tax_details .= '<div class="reptallt">
                        <div class="col-xs-8 nopadding">
                            <div class="faresty">Taxes & Fees</div>
                        </div>
                        <div class="col-xs-4 nopadding">
                            <div class="amnter arimobold"><span class="tax_fees_span">' . $currency_symbol . '</span> <span class="tax_fees_value">' . $total_tax . ' </span></div>
                        </div>
                        <div class="col-xs-8 nopadding">
                            <div class="faresty">Convenience Fees</div>
                        </div>
                        <span class="org_convience_fee hide">'.$org_convience_fee.'</span>
                        <div class="col-xs-4 nopadding">
                            <div class="amnter arimobold"><span class="convenience_fees_span">'.$currency_symbol.'</span> <span class="convenience_fees_value"> '.$convenience_fees.' </span></div>
                        </div>

                        <div class="col-xs-8 nopadding">
                            <div class="faresty">Discount </div>
                        </div>
                        <div class="col-xs-4 nopadding">
                          <span class="discount_amoutn2 hidden"> '.$discount.' </span>
                            <div class="amnter arimobold"><span class="discount_fees_span">'.$currency_symbol.'</span> <span class="discount_amoutn"> '.$discount.' </span></div>
                        </div>

                        <div class="col-xs-8 nopadding insurance '.$insurance_status.'">
                            <div class="faresty">Insurance</div>
                        </div>
                        
                        <div class="col-xs-4 nopadding insurance '.$insurance_status.'">
                            <div class="amnter arimobold">' . ($insurance_amount * ($k + 1)) . ' </div>
                        </div>

                        ' . $gst_data . '
                        

                        '.$extar_service_charge_details.'

                        <div class="col-xs-8 nopadding promo_code_discount hide">
                            <div class="faresty">Promo Code Discount</div>
                        </div>
                        <div class="col-xs-4 nopadding promo_code_discount hide">
                            <div class="amnter arimobold promo_discount_val"> </div>
                        </div>
                    </div>';
    $pax_base_fare_details .= '</div>';
    $pax_tax_details .= '</div>';
    
    ?>


                                                     
    <?php
    $fare_summary .= $pax_base_fare_details;
    $fare_summary .= $pax_tax_details;
    $fare_summary .= '
                    <div class="clearfix"></div>
                        <div class="reptalltftr">
                            <div class="col-xs-6 nopadding">
                                <div class="farestybig">Grand Total</div>
                            </div>
                            <div class="col-xs-6 nopadding ">
                                <div id="grandtotal_valid" class="grandtotal_valid hide">' .($flight_total_price+$gst_value_conv). '</div>
                                <div class="amnterbig arimobold"><span class="grandtotal_span"> ' .$currency_symbol . ' </span> <span class="total_booking_amount grandtotal_value"> ' . ($flight_total_price+round($gst_value_conv)) . '</span>  </div>
                                <div class="hide" id="api_price_details" data-api_price ="'.$api_price_details.'"></div>
                                <div class="hide" id="api_markup_details" data-markup_price ="'.$api_markup_details.'"></div>
                                <div class="hide" id="convenience_fees_original" data-convience_fee ="'.$convenience_fees_orginal.'"></div>
                            </div>
                        </div>
                    </div>
                    </div>';
    return $fare_summary;
}

//Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/flight_session_expiry_script.js'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/booking_script.js'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/flight_booking.js'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/flight_extra_services.js'), 'defer' => 'defer');
Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('flight_extra_services.css'), 'media' => 'screen');
?>
<script type="text/javascript">
    /*
     session time out variables defined
     */
    var search_session_expiry = "<?php echo $GLOBALS ['CI']->config->item('flight_search_session_expiry_period'); ?>";
    var search_session_alert_expiry = "<?php echo $GLOBALS ['CI']->config->item('flight_search_session_expiry_alert_period'); ?>";
    var search_hash = "<?php echo $session_expiry_details['search_hash']; ?>";
    var start_time = "<?php echo $session_expiry_details['session_start_time']; ?>";
    var session_time_out_function_call = 1;

    $('.panel-collapse').on('show.bs.collapse', function () {
        $(this).siblings('.panel-heading').addClass('active');
    });

    $('.panel-collapse').on('hide.bs.collapse', function () {
        $(this).siblings('.panel-heading').removeClass('active');
    });
    $(".cont_plus").click(function () {
        $(".cont_show").show(500).css('display', 'inline-block');
        $(".cont_minus").show(500);
        $(".cont_plus").hide(500);
    });
    $(".cont_minus").click(function () {
        $(".cont_show").hide(500);
        $('.cont_plus').show();
        $(".cont_minus").hide(500);
    });
    $('.insurance').hide();
   
$('#gst_number, #billing-email, #booking_user_name').on('keypress', function(e) {
            if (e.which == 32){
               return false;
            }
        });

</script>
<script>
    
    $(window).scroll(function(){
            if ($("input").is(":focus")) {
            $('#passenger-first-name-1').blur();
            } 
            
        });
  $("#redeem_points").change(function(){
        
        var amount = $(".discount_total").text();
        //alert(amount);
       var rdv=$(".reduceamount").val();
        var grandtotal=$(".grandtotal_valid ").html();
         var disc=$(".discount_amoutn2").html();
        var div = $('#prompform');
        var convfees=$("#convenience_fee").val();
        if($(this). prop("checked") == true){
            div.hide();
            $("#reward_tab").removeClass("hide");
            $("#redeem_points_post").val(1);
            $('#booking_amount').html('<?=$reward_usable?>'+' Points');
            // alert('true');
            $(".discount_total").text('<?php echo $this->currency->get_currency_symbol($pre_booking_params['default_currency'])." ". round($price ['api_total_display_fare_with_rewards']+$convenience_fees,2); ?>');
var nedi=parseInt(disc)+parseInt(rdv);
var nedi2=parseInt(grandtotal)-parseInt(rdv);
var nedi3=parseInt(nedi2)-parseInt(convfees);
            $(".discount_amoutn").html(nedi);
            
             $(".grandtotal_value").html(nedi2);
               $("#total_booking_amount").html(nedi2);
             $("#total_amount_val").val(nedi3);
            //$('.discount_total').animateNumber({ number: '<?php echo $total_price_with_rewards+$convenience_fees; ?>' });
            //alert('<?php echo $total_price_with_rewards+$convenience_fees; ?>');
            // $('#total_price_with_rewards').val('<?php echo $ret_data['grand_total_without_rewards']; ?>');
            // $("#prompform").hide();

            

        }else{
            
            div.show();
            //$reward_usable
            $("#reward_tab").addClass("hide");
            $('#booking_amount').html('0 Points');
            $("#redeem_points_post").val(0);
            var nedi=parseInt(disc);
            var nedi2=parseInt(grandtotal);
            var nedi3=parseInt(nedi2)-parseInt(convfees);
             $(".discount_amoutn").html(nedi);
               $(".grandtotal_value").html(nedi2);
                 $("#total_booking_amount").html(nedi2);
                 $("#total_amount_val").val(nedi2);
            $(".discount_total").text('<?php echo $this->currency->get_currency_symbol($pre_booking_params['default_currency'])." ".round($price['api_total_display_fare']+$convenience_fees,2); ?>');
            //$('.discount_total').animateNumber({ number: '<?php echo $ret_data['grand_total_without_rewards']; ?>' });
            // $('#total_price_with_rewards').val(0);
            // $("#prompform").show(); 
             
        }
  
     });
    
</script>