<?php
$time_arr=array(
'' => 'Please select',
'00:00' => '12:00 AM',
'00:30' => '12:30 AM',
'01:00' => '1:00 AM',
'01:30' => '1:30 AM',
'02:00' => '2:00 AM',
'02:30' => '2:30 AM',
'03:00' => '3:00 AM',
'03:30' => '3:30 AM',
'04:00' => '4:00 AM',
'04:30' => '4:30 AM',
'05:00' => '5:00 AM',
'05:30' => '5:30 AM',
'06:00' => '6:00 AM',
'06:30' => '6:30 AM',
'07:00' => '7:00 AM',
'07:30' => '7:30 AM',
'08:00' => '8:00 AM',
'08:30' => '8:30 AM',
'09:00' => '9:00 AM',
'09:30' => '9:30 AM',
'10:00' => '10:00 AM',
'10:30' => '10:30 AM',
'11:00' => '11:00 AM',
'11:30' => '11:30 AM',
'12:00' => '12:00 PM',
'12:30' => '12:30 PM',
'13:00' => '1:00 PM',
'13:30' => '1:30 PM',
'14:00' => '2:00 PM',
'14:30' => '2:30 PM',
'15:00' => '3:00 PM',
'15:30' => '3:30 PM',
'16:00' => '4:00 PM',
'16:30' => '4:30 PM',   
'17:00' => '5:00 PM',
'17:30' => '5:30 PM',
'18:00' => '6:00 PM',
'18:30' => '6:30 PM',
'19:00' => '7:00 PM',
'19:30' => '7:30 PM',
'20:00' => '8:00 PM',
'20:30' => '8:30 PM',
'21:00' => '9:00 PM',
'21:30' => '9:30 PM',
'22:00' => '10:00 PM',
'22:30' => '10:30 PM',
'23:00' => '11:00 PM',
'23:30' => '11:30 PM',
);
// debug($pre_booking_params);
// exit;
$user_country_code = $traveller_details[0]['country_code_value'];
// debug($user_country_code);
// exit;
$module_value = md5('transfers');
$currency_symbol = $this->currency->get_currency_symbol($pre_booking_params['default_currency']);
$CI=&get_instance();
$template_images = $GLOBALS['CI']->template->template_images();
$mandatory_filed_marker = '<sup class="text-danger">*</sup>';

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

$trip_total_price = $pre_booking_params['markup_price_summary']['NetFare'];
/*if($pre_booking_params['default_currency']=='INR'){
$trip_total_price = roundoff_number($this->transferv1_lib->total_price($pre_booking_params['markup_price_summary']));	
}else{
$trip_total_price = $this->transferv1_lib->total_price($pre_booking_params['markup_price_summary']);
}*/



/********************************* Convenience Fees *********************************/
$subtotal = $trip_total_price;
$pre_booking_params['convenience_fees'] = $convenience_fees;
//$trip_total_price = roundoff_number($pre_booking_params['convenience_fees']+$trip_total_price);
/********************************* Convenience Fees *********************************/
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('provablib.js'), 'defer' => 'defer');

$book_login_auth_loading_image	 = '<div class="text-center loader-image"><img src="'.$GLOBALS['CI']->template->template_images('loader_v3.gif').'" alt="please wait"/></div>';
//debug($pre_booking_params);
$LastCancellationDate =  date("Y-m-d", strtotime($pre_booking_params['cancellation_policies']['CancellationPolicy']['@attributes']['dateFrom']));

//calculating price
$tax_total = 0;
$grand_total = 0;


$grand_total = $pre_booking_params['markup_price_summary']['TotalDisplayFare'];

$tax_total += $pre_booking_params['convenience_fees'];
$grand_total += $pre_booking_params['convenience_fees'];

if($pre_booking_params['default_currency']=='INR'){
$grand_total = ceil($grand_total);
//echo $grand_total;exit;
$trip_total_price = ceil($trip_total_price);	

$trip_total_price  = ceil($trip_total_price);	
}else{
$grand_total = $grand_total;
//echo $grand_total;exit;
$trip_total_price = $trip_total_price;

$trip_total_price  = $trip_total_price;
}
$trip_total_price = $trip_total_price - $pre_booking_params['markup_price_summary']['_GST'];
//echo $total_room_price;exit;
$total_pax = 0;
$adult_count = $pre_booking_params['adult_count'];
$child_count = $pre_booking_params['child_count']; 
$total_pax = $adult_count + $child_count; 

if (isset($pre_booking_params['cancellation_policies']) && !empty($pre_booking_params['cancellation_policies']))
{//echo 'k';
$cancellation_policies[$pre_booking_params['transfer_type']] = @$pre_booking_params['cancellation_policies'];
}else{//echo 'am here';
$cancellation_policies = '';
}
?>


<style>
/* .fixed {
position: fixed;
top:60px;
width: 100%;
bottom: 0;
}*/
.topssec::after{display:none;}
.sight_book_page .hotelhed1{line-height: 22px;}
.travlrsnms {
    font-size: 14px;
    color: #666;
}.sight_book_page .hotelhed1,.sight_book_page .hotelhed2{font-size: 14px}
.ttlamtdvot{color: #666;    font-size: 14px;}
</style>
<input type="hidden" id="total_pax" value="<?=$total_pax?>">
<div class="fldealsec">
	<div class="container">
		<div class="tabcontnue">
			<div class="col-xs-4 nopadding">
				<div class="rondsts <?=$review_active_class?>">
					<a class="taba core_review_tab <?=$review_tab_class?>" id="stepbk1">
						<div class="iconstatus fa fa-eye"></div>
						<div class="stausline">Review</div>
					</a>
				</div>
			</div>
			<div class="col-xs-4 nopadding">
				<div class="rondsts <?=$travellers_active_class?>">
					<a class="taba core_travellers_tab <?=$travellers_tab_class?>" id="stepbk2">
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
</div><!-- fldealsec end -->
<div class="clearfix"></div>
<div class="alldownsectn">
<div class="container">
<div class="ovrgo sight_book_page">
	<div class="bktab1 xlbox <?=$review_tab_details_class?>">
		<div class="col-xs-12 col-md-8 toprom nopad">
			<div class="col-xs-12 nopad full_room_buk">
				<div class="bookcol">
					<div class="hotelistrowhtl">
						<div class="col-md-4 col-sm-4 col-xs-4 nopad xcel">
							<div class="imagehotel">
							<?php if($pre_booking_params['ProductImage']!=''):?>
							<?php $image = $pre_booking_params['ProductImage']; ?>
							<img alt="<?=$pre_booking_params['ProductName']?>" src="<?=$image?>">
							<?php else:?>
							<img alt="Hotel_img" src="<?=$GLOBALS['CI']->template->template_images('no_image_available.jpg')?>" class="lazy h-img">
							<?php endif;?>
							</div>
						</div>
						<div class="col-md-12 col-sm-8 col-xs-8 padall10 xcel">
							<div class="hotelhed"><?=$pre_booking_params['ProductName']?></div>
							<div class="transfer_detail hide">
								<ul class="transfer_details">
									<?php

									 if (isset($pre_booking_params['transfer_info']) && valid_array($pre_booking_params['transfer_info'])) {
									foreach ($pre_booking_params['transfer_info'] as $transfer_info_k => $transfer_info_v) {
									?>
									<li><?= $transfer_info_v ?></li>
									<?php } } ?>
								</ul>
							</div>
							<div class="hotelhed1">Pickup : <?=$pre_booking_params['pickup']?></div>
							<div class="hotelhed1">Destination : <?=$pre_booking_params['destination']?></div>
							<div class="hotelhed2">
								<?php if(isset($hotel_data_array['hotel_data_from'])){
								echo "Hotel Address :- ";echo $hotel_data_array['hotel_data_from']['address'];echo $hotel_data_array['hotel_data_from']['postal_code'];
								}
								if(isset($hotel_data_array['hotel_data_to'])){
								echo "Hotel Address :-  "; echo $hotel_data_array['hotel_data_to']['address'];echo $hotel_data_array['hotel_data_to']['postal_code'];
								} ?>
							</div>

							<div class="clearfix"></div>
							<div class="clearfix"></div>
							<div class="mensionspl"></div>
							<div class="sckint">
								<div class="clearfix"></div>
								<div class="nigthcunt">Total Pax:<?=$total_pax?></div>
							</div>
						</div>
					</div>
				</div>
			</div><!-- full_room_buk end -->

			<div class="col-xs-12 nopadding full_log_tab">
			<div class="fligthsdets">
			<div class="flitab1">
				<div class="clearfix"></div>
				<div class="sepertr"></div>
				<div class="sepertr"></div>
				<!-- LOGIN SECTION STARTS -->
				<?php if(is_logged_in_user() == false) { ?>
					<div class="loginspld">
					<div class="logininwrap">
						<div class="signinhde">Sign in now to Book Online</div>
						<div class="newloginsectn">
							<div class="col-xs-7 celoty">
								<div class="insidechs">
									<div class="mailenter">
										<input type="text" name="booking_user_name" id="booking_user_name"  placeholder="Your mail id" class="newslterinput nputbrd _guest_validate" maxlength="80">
									</div>  
									<div class="noteinote">Your booking details will be sent to this email address.</div>
									<div class="clearfix"></div>
									<div class="havealrdy">
										<div class="squaredThree">
											<input id="alreadyacnt" type="checkbox" name="check" value="None">
											<label for="alreadyacnt"></label>
										</div>
										<label for="alreadyacnt" class="haveacntd">I have an Account</label>
									</div>
									<div class="clearfix"></div>
									<div class="twotogle">
										<div class="cntgust">
											<div class="phoneumber">
												<div class="col-xs-5 nopadding">
													<!-- <input type="text" placeholder="+91" class="newslterinput nputbrd"> -->
													<select name="" class="newslterinput nputbrd _numeric_only" id="before_country_code"  required>
													<?php 
													//debug($phone_code);exit;
													echo diaplay_phonecode_transfer($phone_code,$active_data, $user_country_code); ?>
													</select> 
												</div>
												<div class="col-xs-1 nopadding"><div class="sidepo">-</div></div>
												<div class="col-xs-6 nopadding">
													<input type="text" id="booking_user_mobile" placeholder="Mobile Number" class="newslterinput nputbrd _numeric_only _guest_validate" maxlength="10">
												</div>
												<div class="clearfix"></div>
												<div class="noteinote">We'll use this number to send possible update alerts.</div>
											</div>
											<div class="clearfix"></div>
											<div class="continye col-xs-8 nopad">
												<button class="bookcont" id="continue_as_guest">Book as Guest</button>
											</div>
										</div><!-- cntgust end -->
										<div class="alrdyacnt">
											<div class="col-xs-12 nopad">
												<div class="relativemask"> 
													<input type="password" name="booking_user_password" id="booking_user_password" class="clainput" placeholder="Password" />
												</div>
												<div class="clearfix"></div>
												<a class="frgotpaswrd">Forgot Password?</a>
												<div style="" class="hide alert alert-danger"></div>
											</div>

											<div id="book_login_auth_loading_image" style="display: none"><?=$book_login_auth_loading_image?></div>

											<div class="clearfix"></div>
											<div class="continye col-xs-8 nopad">
												<button class="bookcont" id="continue_as_user">Proceed to Book</button>
											</div>
										</div>
									</div><!-- twotogle end -->
								</div><!-- insidechs end -->
							</div><!-- celoty end -->
							<?php $no_social=no_social(); if($no_social != 0) {?>
								<div class="col-xs-2 celoty nopad linetopbtm">
									<div class="orround">OR</div>
								</div>
							<?php } ?>
							<div class="col-xs-5 celoty">
								<div class="insidechs booklogin">
									<div class="leftpul">
										<?php $social_login1 = 'facebook';
										$social1 = is_active_social_login($social_login1);
										if($social1){
											$GLOBALS['CI']->load->library('social_network/facebook');
											echo $GLOBALS['CI']->facebook->login_button ();
										} 
										$social_login2 = 'twitter';
										$social2 = is_active_social_login($social_login2);
										if($social2){ ?>
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
					</div><!-- loginspld end -->
				<?php } ?>
				<!-- LOGIN SECTION ENDS -->
			</div>
			</div>
			</div><!-- full_log_tab end -->

			<div class="col-xs-4 nopad full_room_buk hide">
				<div class="sckint">
					<div class="ffty">
						<div class="borddo brdrit"> <span class="lblbk_book">
							<span class="fa fa-calendar"></span>
							Travel Date</span>
							<div class="fuldate_book"> 
								<span class="bigdate_book"><?php // echo $travel_date[0]?></span>
								<div class="biginre_book"> <?php // echo $travel_date[1]?><br>
								<?php // echo $travel_date[2]?> </div>
							</div>
						</div>
					</div>			 
					<div class="clearfix"></div>
					<div class="nigthcunt">Total Pax:<?=$total_pax?></div>
				</div>
			</div>
		</div>
		<div class="col-xs-4 full_room_buk rhttbepa">
			<div id="slidebarscr" style="width:100%;">
				<table class="table table-condensed tblemd">
					<tbody>
						<tr class="rmdtls">
							<th colspan="2">Transfers Details</th>
						</tr>
						<tr>
							<th>No of Pax</th>
							<td><?=$total_pax?></td>
						</tr>	
						<tr>
							<th>Deperture Date</th>
							<td><?=date('M',strtotime($depature[1]))?></td>
						</tr>		     
						<?php if($LastCancellationDate):?>
							<tr class="frecanpy">
								<th>Free Cancellation till:<br/><a  href="#" data-target="#roomCancelModal"  data-toggle="modal" >View Cancellation Policy</a></th>
								<td><?=local_month_date($LastCancellationDate)?></td>
							</tr>
						<?php else:?>
							<tr class="frecanpy">
								<th>Cancellation Policy:<br/><a  href="#" data-target="#roomCancelModal"  data-toggle="modal" >View Cancellation Policy</a></th>
								<td>Non-Refundable</td>
							</tr>
						<?php endif;?>
						<!--   <tr class="frecanpy">
						<th><a  href="#" data-target="#roomCancelModal" data-toggle="modal" >View Cancellation Policy:</a></th>
						</tr> -->
						<tr>
							<th>Total Price</th>
							<td><?=$this->currency->get_currency_symbol($pre_booking_params['default_currency'])?> <?=($trip_total_price)?></td>
						</tr>
						<tr class="texdiv">
							<th>Taxes & Service fee</th>
							<td><?=$this->currency->get_currency_symbol($pre_booking_params['default_currency'])?> <?=$tax_total?></td>
						</tr>
						<?php if($pre_booking_params['markup_price_summary']['_GST'] > 0){?>
							<tr class="texdiv">
								<th>GST</th>
								<td><?=$this->currency->get_currency_symbol($pre_booking_params['default_currency'])?> <?=$pre_booking_params['markup_price_summary']['_GST'];?></td>
							</tr>
						<?php } ?>
						<tr class="grd_tol">
							<th>Grand Total</th>
							<td><?=$this->currency->get_currency_symbol($pre_booking_params['default_currency'])?> <?=($grand_total)?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
<div class="bktab2 xlbox <?=$travellers_tab_details_class?>">
	<div class="col-xs-12 col-md-8 nopad">
		<div class="col-xs-12 topalldesc">
			<div class="col-xs-12 nopad">
				<div class="bookcol">
					<div class="hotelistrowhtl">
						<div class="col-md-4 nopad xcel">
							<div class="imagehotel">
								<?php if($pre_booking_params['ProductImage']!=''):?>
									<?php $image= $pre_booking_params['ProductImage']; ?>
									<img alt="<?=$pre_booking_params['ProductName']?>" src="<?=$image?>">
								<?php else:?>
									<img alt="Hotel_img" src="<?=$GLOBALS['CI']->template->template_images('no_image_available.jpg')?>" class="lazy h-img">
								<?php endif;?>
							</div>
						</div>
						<div class="col-md-8 padall10 xcel">
							<div class="hotelhed"><?=$pre_booking_params['ProductName']?></div>
							<div class="transfer_detail hide">
								<ul class="transfer_details">
								<?php if (isset($pre_booking_params['transfer_info']) && valid_array($pre_booking_params['transfer_info'])) {
								foreach ($pre_booking_params['transfer_info'] as $transfer_info_k => $transfer_info_v) {
								?>
									<li><?= $transfer_info_v ?></li>
								<?php } } ?>
								</ul>
							</div>
							<div class="hotelhed1">Pickup : <?=$pre_booking_params['pickup']?></div>
							<div class="hotelhed1">Destination : <?=$pre_booking_params['destination']?></div>
							<div class="hotelhed2">
								<?php if(isset($hotel_data_array['hotel_data_from'])){
									echo "Hotel Address :- ";echo $hotel_data_array['hotel_data_from']['address'];echo $hotel_data_array['hotel_data_from']['postal_code'];
								}
								if(isset($hotel_data_array['hotel_data_to'])){
									echo "Hotel Address :-  "; echo $hotel_data_array['hotel_data_to']['address'];echo $hotel_data_array['hotel_data_to']['postal_code'];
								} ?>
							</div>
							<div class="clearfix"></div>
							<div class="clearfix"></div>
							<div class="bokkpricesml">
								<div class="travlrs"><span class="travlrsnms">Travelers:</span><span class="fa fa-male"></span> <?=$total_pax?></div>
								<div class="totlbkamnt grandtotal"> <span class="ttlamtdvot">Total Amount:</span><?=$this->currency->get_currency_symbol($pre_booking_params['default_currency'])?> <?=($grand_total)?>/-</div>
							</div>
						</div>
					</div>
				</div>
			</div><!-- Outer Summary -->
			<div class="col-xs-4 nopadding celtbcel colrcelo hide"></div>
		</div><!-- topalldesc end -->
		
<div class="col-xs-12 padpaspotr">
<div class="col-xs-12 nopadding">
<div class="fligthsdets">
<?php
/**
* Collection field name 
*/
//Title, Firstname, Middlename, Lastname, Phoneno, Email, PaxType, LeadPassenger, Age, PassportNo, PassportIssueDate, PassportExpDate
$total_adult_count	= $pre_booking_params['adult_count'];//$total_adult_count;
$total_child_count	= $pre_booking_params['child_count'];//$total_child_count;
$total_infant_count = 0; //$total_infant_count;
$total_youth_count 	= 0; //$total_youth_count;
$total_senior_count = 0; //$total_senior_count;

//------------------------------ DATEPICKER START
$i = 1;
$datepicker_list = array();

//------------------------------ DATEPICKER END
$total_pax_count	= $total_adult_count+$total_child_count+$total_infant_count+$total_youth_count+$total_senior_count;
//First Adult is Primary and and Lead Pax
$adult_enum = $child_enum = get_enum_list('viator_title');
$gender_enum = get_enum_list('gender');

unset($adult_enum[MISS_TITLE]);
unset($child_enum[MASTER_TITLE]);
$adult_title_options = generate_options($adult_enum, false, true);
$child_title_options = generate_options($child_enum, false, true);
$youth_title_options = $adult_title_options;
$senior_title_options = $adult_title_options;
$infant_title_options  = $child_title_options;

$gender_options	= generate_options($gender_enum);
$nationality_options = generate_options($iso_country_list, array(INDIA_CODE));//FIXME get ISO CODE --- ISO_INDIA
$passport_issuing_country_options = generate_options($country_list);
//lowest year wanted
$cutoff = date('Y', strtotime('+20 years'));
//current year
$now = date('Y');
$day_options	= generate_options(get_day_numbers());
$month_options	= generate_options(get_month_names());
$year_options	= generate_options(get_years($now, $cutoff));
/**
* check if current print index is of adult or child by taking adult and total pax count
* @param number $total_pax		total pax count
* @param number $total_adult	total adult count
*/
/*function is_adult($total_pax, $total_adult)
{
return ($total_pax>$total_adult ?	false : true);
}*/

/**
* check if current print index is of adult or child by taking adult and total pax count
* @param number $total_pax		total pax count
* @param number $total_adult	total adult count
*/
/*function is_lead_pax($pax_count)
{
return ($pax_count == 1 ? true : false);
}*/
$lead_pax_details = @$pax_details[0];
$adult_age_in_string  = isset($raw_transfer_book['adult_age_list']) && !empty($raw_transfer_book['adult_age_list']) ? implode(",", $raw_transfer_book['adult_age_list']) : '';
$child_age_in_string  = isset($raw_transfer_book['child_age_list']) && !empty($raw_transfer_book['child_age_list']) ? implode(",", $raw_transfer_book['child_age_list']) : '';
$currency_code        = @$raw_transfer_book['currency_code'];
?>
<form action="<?=base_url().'index.php/transfer/pre_booking_api/'?>" method="POST" autocomplete="off">

<?php if (isset($raw_transfer_book['purchase_details']) && valid_array($raw_transfer_book['purchase_details'])) {
    foreach ($raw_transfer_book['purchase_details'] as $pd_key => $purchase_detail) {
       // debug($purchase_detail); exit;
        $cancellation_policies=array();
        $vehicle_type         = $purchase_detail['vehicle_type'];
        $purchase_token       = $purchase_detail['purchase_token'];
        $time_to_expiration   = $purchase_detail['time_to_expiration'];
        $agency_code          = $purchase_detail['agency_code'];
        $creation_user        = $purchase_detail['creation_user'];
        $transfer_type        = $purchase_detail['transfer_type'];
        $SPUI                 = $purchase_detail['SPUI'];
        $image                = $purchase_detail['image'];
        $pax_count            = $adult_count + $child_count;
        $pickup_location_code = $purchase_detail['pickup_location_code'];
        $pickup_location_name = $purchase_detail['pickup_location_name'];
       	
      // $purchase_detail['cancellation_policies'] = force_multple_data_format($purchase_detail['cancellation_policies']);
        
        if (isset($purchase_detail['cancellation_policies']) && !empty($purchase_detail['cancellation_policies']))
        {//echo 'k';
            $cancellation_policies[$transfer_type] = @$purchase_detail['cancellation_policies'];
        }
        else
        {//echo 'am here';
            $cancellation_policies = '';
        }
       
        if (isset($purchase_detail['transfer_pickup_date']) && !empty($purchase_detail['transfer_pickup_date']))
        {
            $transfer_pickup_date = @$purchase_detail['transfer_pickup_date'];
        }
        else
        {
            $transfer_pickup_date = '';
        }
        
        if (isset($purchase_detail['transfer_pickup_time']) && !empty($purchase_detail['transfer_pickup_time']))
        {
            $transfer_pickup_time = @$purchase_detail['transfer_pickup_time'];
        }
        else
        {
            $transfer_pickup_time = '';
        }
        
        $destination_location_code = $purchase_detail['destination_location_code'];
        $destination_location_name = $purchase_detail['destination_location_name'];
        //$currency_code_cancel = $purchase_detail['cancel_currency_code'];
        $from_date                 = $purchase_detail['from_date'];
        $from_time                 = $purchase_detail['from_time'];
        $currency                  = $purchase_detail['currency'];
        
        $purchase_token_array[]            = $purchase_token;
        $time_to_expiration_array[]        = $time_to_expiration;
        $agency_code_array[]               = $agency_code;
        $transfer_type_array[]             = $transfer_type;
        $pickup_location_code_array[]      = $pickup_location_code;
        $pickup_location_name_array[]      = $pickup_location_name;
        $destination_location_code_array[] = $destination_location_code;
        $destination_location_name_array[] = $destination_location_name;
        $from_date_array[]                 = $from_date;
        $from_time_array[]                 = $from_time;
        $service_price_array[]             = $purchase_detail['total_amount'];
        $vehicle_type_array[]              = $purchase_detail['vehicle_type'];
       //debug($purchase_detail);exit;
        $transfer_info                     = $purchase_detail['transfer_info'];
} }
?>
<div class="hide">
	<input type="hidden" name="search_id" value="<?= $search_id ?>" /> 
	<input type="hidden" name="booking_source" value="<?= $booking_source ?>" readonly>
	<?php
		if (valid_array($purchase_token_array)) {
    		foreach ($purchase_token_array as $pta_key => $purchase_tkn) {
	?>
	<input type="hidden" name="purchase_token[]" value="<?= $purchase_tkn ?>" readonly />
	<?php
    	}
		}
		if (valid_array($time_to_expiration_array)) {
    	foreach ($time_to_expiration_array as $ttea_key => $time_to_expiry) {
	?>
	<input type="hidden" name="time_to_expiration[]" value="<?= $time_to_expiry ?>" readonly />
	<?php
    	}
		}
		if (valid_array($agency_code_array)) {
    	foreach ($agency_code_array as $aca_key => $agency_code) {
	?>
	<input type="hidden" name="agency_code[]" value="<?= $agency_code ?>"readonly />
	<?php
    	}
		}
	?>
	<input type="hidden" name="creation_user" value="<?= $creation_user ?>" readonly>
	 <?php
		if (valid_array($transfer_type_array)) {
    	foreach ($transfer_type_array as $tta_key => $transfertype) {
	?>
	<input type="hidden" name="transfer_type[]" value="<?= $transfertype ?>" readonly />
	<?php
    	}
		}
	?>
	<input type="hidden" name="SPUI" value="<?= @$SPUI ?>" readonly> 
	<input type="hidden" name="currency" value="<?= @$currency ?>" /> 
	<?php
		if (isset($service_price_array) && valid_array($service_price_array)) {
    	foreach ($service_price_array as $spa_key => $serviceprice) {
	?>
	<input type="hidden" name="total_amount[]" value="<?= $serviceprice ?>" readonly />
	<?php
    	}
		}
	?> 
	<input type="hidden" name="adult_count" value="<?= $adult_count ?>" readonly>
	<input type="hidden" name="child_count" value="<?= $child_count ?>" readonly> 
	<?php
		if (isset($pickup_location_code_array) && valid_array($pickup_location_code_array)) {
    	foreach ($pickup_location_code_array as $plca_key => $pickup_location_codel) {
	?>
	<input type="hidden" name="pickup_location_code[]" value="<?= $pickup_location_codel ?>" readonly />
	<?php
    	}
		}
		if (isset($pickup_location_name_array) && valid_array($pickup_location_name_array)) {
    	foreach ($pickup_location_name_array as $plna_key => $pickup_location_nme) {
	?>
	<input type="hidden" name="pickup_location_name[]" value="<?= $pickup_location_nme ?>" readonly />
	<?php
    	}
		}
		if (isset($destination_location_code_array) && valid_array($destination_location_code_array)) {
    	foreach ($destination_location_code_array as $dlca_key => $desti_location_code) {
	?>
	<input type="hidden" name="destination_location_code[]" value="<?= $desti_location_code ?>" readonly />
	<?php
    	}
		}
		if (isset($destination_location_name_array) && valid_array($destination_location_name_array)) {
    		foreach ($destination_location_name_array as $dlna_key => $desti_location_namw) {
	?>
	<input type="hidden" name="destination_location_name[]" value="<?= $desti_location_namw ?>" readonly />
	<?php
    	}
		}
		if (isset($vehicle_type_array) && valid_array($vehicle_type_array)) {
    	foreach ($vehicle_type_array as $vt_key => $vt_value) {
	?>
	<input type="hidden" name="vehicle_type[]" value="<?= $vt_value ?>" readonly />
	<?php
    	}
		}
	?> 
	<input type="hidden" name="currency_code" value="<?= $currency_code ?>" readonly />
	<input type="hidden" name="adult_age_in_string" value="<?= $adult_age_in_string ?>" readonly>
	<input type="hidden" name="child_age_in_string" value="<?= $child_age_in_string ?>" readonly> 
	<input type="hidden" name="transfers_total_amount" id="total_amount_payment" value="<?= $grand_total ?>" />
	<?php
		if (valid_array($from_date_array)) {
    	foreach ($from_date_array as $fda_key => $fromdate) {
	?>
	<input type="hidden" name="from_date[]" value="<?= $fromdate ?>" readonly />
	<?php
    	}
		}
		if (valid_array($from_time_array)) {
    	foreach ($from_time_array as $dta_key => $fromtime) {
	?>
	<input type="hidden" name="from_time[]" value="<?= $fromtime ?>" readonly />
	<?php
    	}
		}	
	?> 
	<!--<input type="hidden" name="token" value="<?php // echo $token ?>" readonly> 
	<input type="hidden" name="token_key" value="<?php // echo $tokenKey ?>" readonly>-->
	<?php $dynamic_params_url = serialized_data($raw_transfer_book);?>
	<input type="hidden" required="required" name="token" value="<?=$dynamic_params_url;?>" />
	<input type="hidden" required="required" name="token_key" value="<?=md5($dynamic_params_url);?>" />
	<input type="hidden" name="promocode_val" id="promocode_val" value="" />
</div>

<div class="flitab1">
<div class="moreflt boksectn">
	<div class="ontyp">
		<div class="labltowr arimobold">Please enter the customer names.</div>
		<div class="pasngrinput _passenger_hiiden_inputs" style="padding: 5px !important;">
		<?php if(is_logged_in_user()) {
			$traveller_class = ' user_traveller_details ';
		} else {
			$traveller_class = '';
		}
		$pax_index=1;$name_index = 1; ?>

			<div class="hide hidden_pax_details">
				<?php $passenger_type =1; //adult ?>
				<input type="hidden" name="passenger_type[]" value="<?=$passenger_type?>">
				<input type="hidden" name="lead_passenger[]" value="">
			</div>
			<div class="col-xs-12 col-sm-2 col-md-1 nopadding">
				<div class="adltnom">
					<?php $title_select_options = $adult_title_options;
					$passenger_title =1; //adult
					$passenger_title = 'Adult';
					echo $passenger_title;
					?><?php //echo (is_lead_pax($p_key+$pax_index) ? '- Lead Pax' : '')?>
				</div>
			</div>
			<div class="col-xs-12 col-sm-10 col-md-11 nopadding">
				<div class="inptalbox">
					<div class="col-xs-3 col-sm-3 col-md-3 spllty">
						<select class="mySelectBoxClass flyinputsnor name_title" name="name_title" required>
						<?php echo $title_select_options;?>
						</select>
					</div>
					<div class="col-xs-4 col-md-4 spllty">
						<input value="<?=@$cur_pax_info['first_name']?>" required="required" type="text" name="first_name" id="passenger-first-name-0" class="clainput alpha_space <?=$traveller_class?>"  minlength="2" maxlength="45" placeholder="Enter First Name"/>					  
					</div>
					<div class="col-xs-3 col-md-3 spllty">
						<input value="<?=@$cur_pax_info['last_name']?>" required="required" type="text" name="last_name" id="passenger-last-name-0" class="clainput alpha_space last_name" minlength="2" maxlength="45" placeholder="Enter Last Name" />
					</div>
					<div class="col-md-8 nopad"></div>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>		

<div class="clearfix"></div>
<!-- <div class="ontyp">
	<div class="comon_backbg add_info">
		<h3 class="inpagehed">Additional Information</h3>
		<div class="sectionbuk billingnob">
			<div class="payrow payrow1">
				<div class="col-md-12">
					<input type="hidden" name="triptype" value="<?php echo $trip_type;?>" />
					<?php if($hotel_data_array['search_data']['from_transfer_type']=='ProductTransferHotel' && $hotel_data_array['search_data']['to_transfer_type']=='ProductTransferTerminal')
					{?>
						<div class="paylabel paylabel_st">Arrival Airport</div>
					<?php }else{ ?>
						<div class="paylabel paylabel_st">Departure Airport</div>
					<?php } ?>
					<div class="relativemask" style="margin-bottom: 15px;">
						<?php if($hotel_data_array['search_data']['from_transfer_type']=='ProductTransferHotel' && $hotel_data_array['search_data']['to_transfer_type']=='ProductTransferTerminal')
						{?>
							<input type="text" style="border: 1px solid #eeeeee;box-shadow: 0 0 10px -5px #cccccc inset;color: #ffffff;float: left;font-size: 14px;height: 50px;padding: 0 8px 0 25px;width: 100%;border-radius: 0px;background: #848484;" value="<?php echo $hotel_data_array['search_data']['to']; ?>" readonly="readonly" placeholder="From, Airport, City Name" name="transfer_from" id="transfer_from" class="ft fromtransfer ui-autocomplete-input" required="" aria-required="true" autocomplete="off"> 
							<input class="hide loc_id_holder" name="from_loc_id" type="hidden" value="<?= @$hotel_data_array['search_data']['to_code'] ?>">
						<?php }else{ ?>
							<input type="text" style="border: 1px solid #eeeeee;box-shadow: 0 0 10px -5px #cccccc inset;color: #ffffff;float: left;font-size: 14px;height: 50px;padding: 0 8px 0 25px;width: 100%;border-radius: 0px;background: #848484;" value="<?php echo $hotel_data_array['search_data']['from']; ?>" readonly="readonly" placeholder="From, Airport, City Name" name="transfer_from" id="transfer_from" class="ft fromtransfer ui-autocomplete-input" required="" aria-required="true" autocomplete="off"> 
							<input class="hide loc_id_holder" name="from_loc_id" type="hidden" value="<?= @$hotel_data_array['search_data']['from_code'] ?>">
						<?php } ?>

					</div>
				</div> 
				<div class="col-md-6 col-xs-6">
					<div class="paylabel">Airline Name  <span class="travmandate">*</span></div>
					<input type="text" value="<?php echo @$transfer_search_params['travel_company_name']; ?>" placeholder="Airline" name="travel_company_name" id="travel_company_name" class="payinput" required="" aria-required="true" autocomplete="off" maxlength="30"/> 
					<input class="hide company_name" name="company_loc_id" type="hidden" value="<?= @$transfer_search_params['company_loc_id'] ?>">
					<span id="travel_company_name_msg" class="val_error"></span>
				</div>
				<div class="col-md-6 col-xs-6">
					<div class="paylabel">Flight Number  <span class="travmandate">*</span></div>
					<input type="text" value="" class="payinput" name="travel_number" id="travel_number" placeholder="Flight Number (including company code)" required="" aria-required="true" maxlength="10"/>
					<span id="travel_number_msg" class="val_error"></span>
				</div>
				<div class="col-md-6 col-xs-6">
					<div class="paylabel">Time</div>
					<input type="text" value="<?=@$flight_time['depature_time_flight']?>" class="payinput" name="travel_time" id="" readonly="readonly" placeholder="Time" required="" aria-required="true">
				</div>
			</div>

			<?php if($trip_type=='circle'){ ?>
				<div class="payrow ">
					<div class="col-md-12">
						<div class="paylabel paylabel_st">Arrival Airport</div>
						<div class="relativemask">
							<span class="maskimg hfrom"></span>
							<?php if($hotel_data_array['search_data']['from_transfer_type']=='ProductTransferHotel' && $hotel_data_array['search_data']['to_transfer_type']=='ProductTransferTerminal')
							{?>
								<input type="text" value="<?php echo $hotel_data_array['search_data']['to']; ?>" readonly="readonly" placeholder="From, Airport, City Name" name="transfer_to" id="transfer_from" class="ft fromtransfer ui-autocomplete-input" required="" aria-required="true" autocomplete="off"> 
								<input class="hide loc_id_holder" name="to_loc_id" type="hidden" value="<?= @$hotel_data_array['search_data']['to_code'] ?>">

							<?php }else{ ?>
								<input type="text" value="<?php echo $hotel_data_array['search_data']['from']; ?>" readonly="readonly" placeholder="From, Airport, City Name" name="transfer_to" id="transfer_from" class="ft fromtransfer ui-autocomplete-input" required="" aria-required="true" autocomplete="off"> 
								<input class="hide loc_id_holder" name="to_loc_id" type="hidden" value="<?= @$hotel_data_array['search_data']['from_code'] ?>">

							<?php } ?>
						</div>
					</div>
				</div>
				<div class="payrow">
					<div class="col-md-6 col-xs-6">
						<div class="paylabel">Airline Name</div>
						<input type="text" value="<?php echo @$transfer_search_params['arrival_travel_company_name']; ?>" placeholder="Airline" name="arrival_travel_company_name" id="arrival_travel_company_name" class="payinput" required="" aria-required="true" autocomplete="off" maxlength="30"/> 
						<input class="hide company_name" name="arrival_company_loc_id" type="hidden" value="<?= @$transfer_search_params['arrival_company_loc_id'] ?>">
						<span id="arrival_travel_company_name_msg" class="val_error"></span>
					</div>
					<div class="col-md-6 col-xs-6">
						<div class="paylabel">Flight Number</div>
						<input type="text" value="" class="payinput" name="arrival_travel_number" id="arrival_travel_number" placeholder="Flight Number (including company code)" required="" aria-required="true" maxlength="10"/>
						<span id="arrival_travel_number_msg" class="val_error"></span>
					</div>
					<div class="col-md-6 col-xs-6">
						<div class="paylabel">Time</div>
						<input type="text" value="<?=@$flight_time['return_time_flight']?>" class="payinput" name="arrival_travel_time" id="" readonly="readonly" placeholder="Time" required="" aria-required="true">
					</div>
				</div>
			<?php } ?>

			<div class="payrow">
				<div class="col-md-6 col-xs-6">
					<div class="paylabel">Comment</div>
					<textarea class="set_textarea" name="comment" id="comment" placeholder="Comment for the transfer incoming office." maxlength="200"></textarea>
				</div>
				<div class="col-md-6 col-xs-6">
					<div class="paylabel">Additional Comments</div>
					<textarea class="set_textarea" name="additional_comments" id="additional_comments" placeholder="Additional comments about you'r transfer" maxlength="200"></textarea> 
				</div>
			</div>

			<span class="noteclick"> After clicking "Book it" you will be redirected to payment gateway. You must complete the process or the transaction will not occur. </span>
		</div>
	</div>
</div> --><!-- ontyp end -->



<!-- <div class="ontyp">
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
<div class="col-md-4 col-xs-6 nopadding_right">
<div class="cartprc">
<div class="payblnhm singecartpricebuk ritaln">
<input type="text" placeholder="Enter Promo" name="code" id="code" class="promocode" aria-required="true" />
<input type="hidden" name="module_type" id="module_type" class="promocode" value="<?=@$module_value;?>" />
<input type="hidden" name="total_amount_val" id="total_amount_val" class="promocode" value="<?=@$subtotal;?>" />
<input type="hidden" name="convenience_fee" id="convenience_fee" class="promocode" value="<?=@$convenience_fees;?>" />
<input type="hidden" name="currency_symbol" id="currency_symbol" value="<?=@$currency_symbol;?>" />
<input type="hidden" name="currency" id="currency" value="<?=@$pre_booking_params['default_currency'];?>" />

<p class="error_promocode text-danger" style="font-weight:bold"></p>                     
</div>
</div>
</div>
<div class="col-md-2 col-xs-3 nopadding_left">
<input type="button" value="Apply" name="apply" id="apply" class="promosubmit">
</div>
</form>
</div>
<div class="loading hide" id="loading"><img src="<?php echo $GLOBALS['CI']->template->template_images('loader_v3.gif')?>"></div>
<div class="clearfix"></div>
<div class="savemessage"></div>
</div>
</div>
</div> -->
<div class="clearfix"></div>
<div class="contbk">
<div class="contcthdngs">CONTACT DETAILS</div>
<div class="hide">
<input type="hidden" name="billing_country" value="92">
<input type="hidden" name="billing_city" value="test">
<input type="hidden" name="billing_zipcode" value="test">
<input type="hidden" name="billing_address_1" value="test">
</div>
<div class="col-xs-12 col-md-8 nopad">
<div class="col-xs-4 nopadding">
<select name="phone_country_code" class="newslterinput nputbrd _numeric_only " id="after_country_code" required>
<?php echo diaplay_phonecode_transfer($phone_code,$active_data, $user_country_code); ?>
</select> 
</div>
<div class="col-xs-1"><div class="sidepo">-</div></div>
<div class="col-xs-4 nopadding">
<input value="<?=@$lead_pax_details['phone'] == 0 ? '' : @$lead_pax_details['phone'];?>" type="text" name="passenger_contact" id="passenger-contact" placeholder="Mobile Number" class="newslterinput nputbrd _numeric_only" maxlength="10" required="required">
</div>
<div class="clearfix"></div>
<div class="emailperson col-xs-9 nopad">
<input value="<?=@$lead_pax_details['email']?>" type="text" maxlength="80" required="required" id="billing-email" class="newslterinput nputbrd" placeholder="Email" name="billing_email">
</div>
</div>
<div class="clearfix"></div>
<div class="notese">Your mobile number will be used only for sending transfers related communication.</div>
</div>
<div class="clikdiv">
<div class="squaredThree">
<input id="terms_cond1" type="checkbox" name="tc" checked="checked" required="required">
<label for="terms_cond1"></label>
</div>
<span class="clikagre" id="clikagre">
Terms and Conditions
</span>
</div>
<div class="continye col-xs-3">
						<input type="radio" id="offline" name="payment_method" value="<?= OFFLINE_PAYMENT ?>" onclick="showoffline(this.value)">
						<label for="offline">Offline Payment</label><br>
						 <input type="radio" id="online" name="payment_method" value="<?= ONLINE_PAYMENT ?>" onclick="showonline(this.value)">
						<label for="online">Online Payment</label><br>  
						</div>
<div class="clearfix"></div>
<div class="loginspld">
<div class="collogg">

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
<div class="col-xs-4 nopadding">
<div class="insiefare">
<!-- <div class="farehd arimobold">Passenger List</div>
<div class="fredivs">
<div class="psngrnote">
<?php
if(valid_array($traveller_details)) {
$traveller_tab_content = 'You have saved passenger details in your list,on typing, passenger details will auto populate.';
} else {
$traveller_tab_content = 'You do not have any passenger saved in your list, start adding passenger so that you fvdo not have to type every time. <a href="'.base_url().'index.php/user/profile?active=traveller" target="_blank">Add Now</a>';
}
?>
<?=$traveller_tab_content;?>
</div>
</div> -->
</div>
</div>
<?php } ?>
</div>
	</div>
</div>
	<div class="col-xs-4 rhttbepa">
	<div id="nxtbarslider" style="width:100%;">
		<table class="table table-condensed tblemd">
			<tbody>
				<tr class="rmdtls">
					<th colspan="2">Transfers Details</th>
				</tr>
				<tr>
					<th>No of Pax</th>				        
					<td><?=$total_pax?></td>
				</tr>
				<tr>
					<th>Departure Date</th>
							<td><?=date('d M Y',strtotime($depature))?></td>
				</tr>
				<tr>
					<th>Departure Time</th>
							<td><?=$depature_time_flight?></td>
				</tr>
				<?php if($LastCancellationDate):?>
					<tr class="frecanpy">
						<th>Free Cancellation till:<br/><a  href="#" data-target="#roomCancelModal"  data-toggle="modal" >View Cancellation Policy</a></th>
						<td><?=local_month_date($LastCancellationDate)?></td>
					</tr>
				<?php else:?>
					<tr class="frecanpy">
						<th>Cancellation Policy:<br/><a  href="#" data-target="#roomCancelModal" data-toggle="modal">View Cancellation Policy</a></th>
						<td>Non-Refundable</td>
					</tr>
				<?php endif;?>
				<tr>
					<th>Total Price</th>
					<td><?=$this->currency->get_currency_symbol($pre_booking_params['default_currency'])?> <?=$trip_total_price?></td>
				</tr>
				<!-- <tr class="texdiv">
					<th>Taxes & Service fee</th>
					<td><?=$this->currency->get_currency_symbol($pre_booking_params['default_currency'])?> <?=$tax_total?></td>
				</tr> -->
				<?php if($pre_booking_params['convenience_fees'] > 0){?>
					<tr class="texdiv">
						<th>Convenience Fee</th>
						<td><?=$pre_booking_params['convenience_fees'];?></td>
					</tr>
				<?php } ?>
				<?php if($pre_booking_params['markup_price_summary']['_GST'] > 0){?>
					<tr class="texdiv">
						<th>Taxes & Service fee</th>
						<td><?=$this->currency->get_currency_symbol($pre_booking_params['default_currency'])?> <?=$pre_booking_params['markup_price_summary']['_GST'];?></td>
					</tr>
				<?php } ?>
				<tr class="promo_code_discount hide">
					<th>Promo Code Discount</th>
					<td class="promo_discount_val"></td>
				</tr>
				<tr class="grd_tol">
					<th>Grand Total</th>
					<td class="grandtotal"><?=$this->currency->get_currency_symbol($pre_booking_params['default_currency'])?> <?=$grand_total?>/-</td>
				</tr>
			</tbody>
		</table>
	</div>
	</div>
</div>

</div>
</div>
</div>
<span class="hide">
<input type="hidden" id="pri_journey_date" value='<?php //echo date('Y-m-d',strtotime($search_data['from_date']))?>'>
</span>



<div class="modal fade bs-example-modal-lg" id="roomCancelModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
<div class="modal-dialog modal-lg">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<h5 class="modal-title" id="myModalLabel">Cancellation Policy</h5>

<div class="imghtltrpadv hide">
<img src="" id="trip_adv_img">
</div>
</div>
<div class="modal-body">

<p class="policy_text"><?php 	//debug($cancellation_policies);
if (isset($cancellation_policies) && !empty($cancellation_policies)) {
// echo "<b>Cancellation Policy</b>";
echo "<ul>";
foreach ($cancellation_policies as $cancellation_policies_key => $cancellation_policies_val) {
/*if ($cancellation_policies_key == "IN")
$trans_type = "From";
else
$trans_type = "To";*/

foreach ($cancellation_policies_val as $cancellation_policies_k => $cancellation_policies_v) {
//foreach ($cancellation_policies[$cancellation_policies_key] as $cancellation_policies_k => $cancellation_policies_v) {
//	foreach ($cancellation_policies_v as $c_key => $c_data){

$amount = $cancellation_policies_v['@attributes']['amount'];
$currency_code_cancel = $cancellation_policies_v['@attributes']['cancel_currency_code'];

$dateFrom = date("Y-m-d", strtotime($cancellation_policies_v['@attributes']['dateFrom']));
$time     = date("H:i", strtotime($cancellation_policies_v['@attributes']['time']));
//echo "<li>" . $trans_type . " - If cancellation done on or after " . $dateFrom . " " . $time . " , amount " . $currency_code_cancel . " " . round($amount) . " will be charged.</li>";
echo "<li>" .$cancellation_policies_v['@attributes']['msg']."</li>";
//exit;
//	}
}
}
echo "</ul>";
}

?></p>


</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
</div>
</div>
</div>

<!-- <script type="text/javascript">
$(document).ready(function(){
$(document).on('scroll', function(){
if ($('#slidebarscr')[0].offsetTop < $(document).scrollTop()){
var top = $(document).scrollTop();
var height = $(window).height();
//alert(height);
if((top >= 150) || (top >= 285))  // || (height > 300))
{
//alert('top'+top);
$("#slidebarscr").css({position: "fixed", top:0});  	
}   
/*else if((top >= 285) && (top < 300))
{
$("#slidebarscr").css({position: "fixed", top:0});  	
}*/
else 
{
//alert('bottom '+top);
$("#slidebarscr").css({position: "",top:0});  	
}                     
}
});  
});
</script> -->
<!-- <script type="text/javascript">
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
</script> -->

<?php 

function diaplay_phonecode_transfer($phone_code,$active_data, $user_country_code)
{

$list='';
	foreach($phone_code as $code){
	if(!empty($user_country_code)){
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
	
	if($code['name']=='Default'){}else{
		$list .="<option value=".$code['name']." ".$code['country_code']."  ".$selected." >".$code['name']." ".$code['country_code']."</option>";
	}   
	}
	 return $list;
}
?>
<script type="text/javascript">
	function showoffline(offline)
	{
		document.getElementById('online').checked = false;
	}
	function showonline(online)
	{
		document.getElementById('offline').checked = false;
	}
	$(document).ready(function(){
    $(document).on('scroll', function(){
        if ($('#slidebarscr')[0].offsetTop < $(document).scrollTop()){
        	var top = $(document).scrollTop();
        	var height = $(window).height();
        	//alert(height);
        	if((top >= 150) || (top >= 285))  // || (height > 300))
        	{
        		//alert('top'+top);
        		$("#slidebarscr").css({position: "fixed", top:0});  	
        	}   
        	else if((top >= 285) && (top < 300))
        	{
        		$("#slidebarscr").css({position: "fixed", top:0});  	
        	}
        	else 
        	{
        		//alert('bottom '+top);
        		$("#slidebarscr").css({position: "",top:0});  	
        	}                     
        }
    });  
});
</script>
<script type="text/javascript">
$(function(){		
var start = new Date();
start.setFullYear(start.getFullYear() - 70);
var end = new Date();
end.setFullYear(end.getFullYear() - 1);

$(".datepickerbook" ).datepicker(
{
dateFormat: 'dd MM yy',
changeMonth: true,
changeYear: true,
yearRange: '1970:'+(new Date).getFullYear()    
});

$(".dobdatepickerbook").datepicker({
dateFormat: 'dd MM yy',
changeMonth: true,
changeYear: true,
yearRange: start.getFullYear()+':'+end.getFullYear()
});
$(".expiraydatepickerbook").datepicker({
dateFormat:'dd MM yy',
changeMonth:true,
changeYear:true,
yearRange:'2000:'+((new Date).getFullYear()+10)
});
$(".transferdeparturedate").datepicker({
dateFormat:'dd MM yy',
changeMonth:true,
changeYear:true,
minDate:0
});
$(".transferarrivaldate").datepicker({
dateFormat:'dd MM yy',
changeMonth:true,
changeYear:true,
minDate:0
});

$(".p-weight").on("change",function(){

var product_key = $(this).data('key');
var selected_weight = $(this).val();
$(".weight_measure_"+product_key).val(selected_weight);
});
$(".p-height").on("change",function(){

var product_key = $(this).data('key');
var selected_height = $(this).val();
$(".height_measure_"+product_key).val(selected_height);
});


var selected_text  = $(".hotelPickup option:selected").text();
$("#hotel_pickup_list_name").val(selected_text);

$(".hotelPickup").on("change",function(){
var selected_value = $(this).val();
var selected_text  = $(".hotelPickup option:selected").text();
$("#hotel_pickup_list_name").val(selected_text);
if(selected_value =='notListed'){         
$("#hotelPickup_name").removeClass('hide');
}else{         
$("#hotelPickup_name").addClass('hide');
}

});
});
</script>
<?php
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/sightseeing_booking.js'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/booking_script.js'), 'defer' => 'defer');?>


