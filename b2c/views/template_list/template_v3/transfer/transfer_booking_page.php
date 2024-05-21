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

	// debug($phone_code);exit;
 $user_country_code = $pre_booking_params['traveller_details'][0]['country_code_value'];
// debug($pre_booking_params);exit;
// $product_details = json_decode(base64_decode($product_details));
// debug($product_details);exit;
 $agent_buying_price = $pre_booking_params['agent_buying_price'];
foreach ($product_details as $key => $value) {
	# code...

$travel_date = explode("-",$pre_booking_params['transfer_date']);
$trip_total_price = $value->display_price - $pre_booking_params['vat'];
$tax_total = $pre_booking_params['vat'];
$grand_total = $trip_total_price + $tax_total;
$adult = $pre_booking_params['adult'];
$child = $pre_booking_params['child'];
$total_pax = $pre_booking_params['total_pax'];
$question = $value->modalities[$modalities]->questions;
$questions = json_decode(json_encode($question), True);
$remarks = $value->modalities[$modalities]->comments[0]->text;
$price_id = $value->price_id;
$transfer_name = $value->transfer_name;
$departure_date = $pre_booking_params['from_date'];
$time_in_12_hour_format = $pre_booking_params['time_in_12_hour_format'];
 //debug($remarks); die;

//$age_band = base64_decode('W3siYmFuZElkIjoxLCJjb3VudCI6MX0seyJiYW5kSWQiOjMsImNvdW50IjowfV0=');

//debug($age_band); die;
//debug($pre_booking_params['markup_price_summary']['_GST']); die;

//debug($value); die;
// debug($pre_booking_params['BookingQuestions']);
// exit;
// $module_value = md5('activities');
// // debug($pre_booking_params);
// // exit;
// $currency_symbol = $this->currency->get_currency_symbol($pre_booking_params['default_currency']);
// //debug($pre_booking_params);exit;
// $CI=&get_instance();
// $template_images = $GLOBALS['CI']->template->template_images();
// $mandatory_filed_marker = '<sup class="text-danger">*</sup>';

// $travel_date = sightseeing_travel_date($pre_booking_params['booking_date']);
// $travel_date = explode('|', $travel_date);
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
// echo $agent_balance;exit;
// $passport_issuing_country = INDIA_CODE;
// $temp_passport_expiry_date = date('Y-m-d', strtotime('+5 years'));
// $static_passport_details = array();
// $static_passport_details['passenger_passport_expiry_day'] = date('d', strtotime($temp_passport_expiry_date));
// $static_passport_details['passenger_passport_expiry_month'] = date('m', strtotime($temp_passport_expiry_date));
// $static_passport_details['passenger_passport_expiry_year'] = date('Y', strtotime($temp_passport_expiry_date));



// if($pre_booking_params['default_currency']=='INR'){
// 	$trip_total_price = roundoff_number($this->sightseeing_lib->total_price($pre_booking_params['markup_price_summary']));	
// }else{
// 	$trip_total_price = $this->sightseeing_lib->total_price($pre_booking_params['markup_price_summary']);
// }



// /********************************* Convenience Fees *********************************/
// $subtotal = $trip_total_price;
// $pre_booking_params['convenience_fees'] = $convenience_fees;
// //$trip_total_price = roundoff_number($pre_booking_params['convenience_fees']+$trip_total_price);
// /********************************* Convenience Fees *********************************/
// Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('provablib.js'), 'defer' => 'defer');

// $book_login_auth_loading_image	 = '<div class="text-center loader-image"><img src="'.$GLOBALS['CI']->template->template_images('loader_v3.gif').'" alt="please wait"/></div>';
// //debug($pre_booking_params);
// $LastCancellationDate = $pre_booking_params['TM_LastCancellation_date'];
// $current_date = date('Y-m-d');
// if($current_date <$LastCancellationDate ){
// 	$LastCancellationDate = $LastCancellationDate;
// }else{
// 	$LastCancellationDate = '';
// }

// //calculating price
// $tax_total = 0;
// $grand_total = 0;


// $grand_total = $pre_booking_params['markup_price_summary']['TotalDisplayFare'];

// $tax_total += $pre_booking_params['convenience_fees'];
// $grand_total += $pre_booking_params['convenience_fees'];

// if($pre_booking_params['default_currency']=='INR'){
// 	$grand_total = ceil($grand_total);
// 	//echo $grand_total;exit;
// 	$trip_total_price = ceil($trip_total_price);	

// 	$trip_total_price  = ceil($trip_total_price);	
// }else{
// 	$grand_total = $grand_total;
// 	//echo $grand_total;exit;
// 	$trip_total_price = $trip_total_price;

// 	$trip_total_price  = $trip_total_price;
// }
// $trip_total_price = $trip_total_price - $pre_booking_params['markup_price_summary']['_GST'];

// //echo $total_room_price;exit;
// $total_pax = 0;

// //$search_params  = json_decode(base64_decode($pre_booking_params['search_params']),true);

//$age_bd = json_decode(base64_decode($search_params['age_band']),true);

// $total_adult_count = 0;
// $total_child_count = 0;

// $age_band_details_arr = array('1'=>'Adult','2'=>'Child');
// foreach ($pre_booking_params['AgeBands'] as $p_key => $p_value) {
// 	$total_pax += $p_value['count'];
// 	if($age_band_details_arr[$p_value['bandId']]=='Adult'){
// 		$total_adult_count +=$p_value['count'];
// 	}elseif($age_band_details_arr[$p_value['bandId']]=='Child'){
// 		$total_child_count += $p_value['count'];
// 	}
// }


?>


<style>
   /* .fixed {
	position: fixed;
	top:60px;
	width: 100%;
	bottom: 0;*/
}
	.topssec::after{display:none;}
	.main-footer{margin-top: 0px!important;}
	tr.rmdtls th {
    color: #fff!important;
}
.table-condensed.tblemd>tbody>tr>td{text-align: right!important;}
.tblemd th{padding: 10px 5px!important;}
.topalldesc{padding: 0px!important;margin: 3px 0px 10px 0px!important;}
.bookcol{margin: 0px!important;}
#nxtbarslider{padding: 0px!important;}

</style>
<input type="hidden" id="total_pax" value="<?=@$total_pax?>">
<input type="hidden" id="agent_buying_price" value="<?=$agent_buying_price?>">
<input type="hidden" id="agent_balance" value="<?=$agent_balance?>">
<div class="fldealsec">
  <div class="container">
	<div class="tabcontnue">
	<div class="col-xs-4 nopadding">
			<div class="rondsts <?=$review_active_class?>">
			<a class="taba core_review_tab <?=@$review_tab_class?>" id="stepbk1">
				<div class="iconstatus fa fa-eye"></div>
				<div class="stausline">Review</div>
			</a>
			</div>
		</div>
		<div class="col-xs-4 nopadding">
			<div class="rondsts <?=$travellers_active_class?>">
			<a class="taba core_travellers_tab <?=@$travellers_tab_class?>" id="stepbk2">
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
  <div class="ovrgo sight_book_page">
	<div class="bktab1 xlbox <?=$review_tab_details_class?>">
		<div class="col-xs-12 col-md-8 toprom nopad">
		  <div class="col-xs-12 nopad full_room_buk">
			<div class="bookcol">
			  <div class="hotelistrowhtl">
				<div class="col-md-4 col-sm-4 col-xs-4 nopad xcel">
				  <div class="imagehotel">

				  		<?php 
				  		
				  	//	debug($value);
				  		if($value->image!=''):?>
				  		<?php
				  			//$image = $base_url.'/'.base64_encode($pre_booking_params['HotelCode']).'/0';
				  			$image ="https://travelfreetravels.com/extras/custom/TMX6244821650276433/uploads/packages/".$value->image;
				  		?>
				  		<img alt="<?=$value->ProductName?>" src="<?=$image?>">
				  	<?php else:?>
				  		<img alt="Hotel_img" src="<?=$GLOBALS['CI']->template->template_images('no_image_available.jpg')?>" class="lazy h-img">
				  	<?php endif;?>
				  </div>
				</div>
				<div class="col-md-12 col-sm-8 col-xs-8 padall10 xcel">
				  <div class="hotelhed">Title: <?=$value->transfer_name?></div>
				  <div class="hotelhed1">Duration: <?=$value->duration?></div>
<div class="hotelhed1">Distance: <?=$value->distance?></div>
				  <div class="clearfix"></div>
				  <div class="mensionspl"> <?php
				  if($value->Location){
				  	echo $value->Location;
				  }
				  ?> </div>

				  <div class="sckint">
			  <div class="ffty">
				<div class="borddo brdrit"> <span class="lblbk_book">
				<span class="fa fa-calendar"></span>
				<input type="hidden" name="from_date" value="<?=$pre_booking_params['from_date']?>">
				<input type="hidden" name="to_date" value="<?=$pre_booking_params['to_date']?>">
				Travel Date</span>
				  <div class="fuldate_book"> <span class="bigdate_book"><?=date('d M Y',strtotime($departure_date))?></span>
					<div class="biginre_book"> <br>
					   </div>
				  </div>
				</div>
			  </div>
			
			  <div class="clearfix"></div>
			  <div class="nigthcunt">Total Pax:<?=$total_pax?></div>
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
				
				<div class="sepertr"></div>
				<!-- LOGIN SECTION STARTS -->
				<?php if(is_logged_in_user() == false) { ?>
				<div class="loginspld">
					<div class="logininwrap">
					<div class="signinhde">
						Sign in now to Book Online
					</div>
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
												echo diaplay_phonecode($phone_code,$active_data, $user_country_code); ?>
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
								</div>
								<div class="alrdyacnt">
									<div class="col-xs-12 nopad">
										 <div class="relativemask"> 
											<input type="password" name="booking_user_password" id="booking_user_password" class="clainput" placeholder="Password" />
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
						 <?php @$no_social=no_social(); if($no_social != 0) {?>
						<div class="col-xs-2 celoty nopad linetopbtm">
								<div class="orround">OR</div>
						</div>
						<?php } ?>
						<div class="col-xs-5 celoty">
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
				<span class="fa fa-calendar"></span>
				Travel Date</span>
				  <div class="fuldate_book"> <span class="bigdate_book"><?=@$travel_date[0]?></span>
					<div class="biginre_book"> <?=@$travel_date[1]?><br>
					  <?=@$travel_date[2]?> </div>
				  </div>
				</div>
			  </div>			 
			  <div class="clearfix"></div>
			  <div class="nigthcunt">Total Pax:<?=$total_pax?></div>
			</div>
		  </div>
		</div>
		<div class="col-xs-4 full_room_buk rhttbepa">
		   <div id="slidebarscr"><!-- style="width:370px;" -->
		 	<table class="table table-condensed tblemd">
			 	<tbody>
			 	  <tr class="rmdtls">
			        <th>Transfer Details</th>
			        <td></td>
			      </tr>
			    
			      <tr>
			        <th>No of Pax</th>
			        <td><?=$pre_booking_params['total_pax']?></td>
			      </tr>
			      <tr>
			        <th>Departure Date</th>
			        <td><?=date('d M Y',strtotime($departure_date))?></td>
			      </tr>				     
			      <?php if(@$LastCancellationDate):?>
				      <tr class="frecanpy">
				        <th>Free Cancellation till:<br/><a  href="#" data-target="#roomCancelModal"  data-toggle="modal" >View Cancellation Policy</a></th>
				        <td><?=local_month_date(@$LastCancellationDate)?></td>
				        
				      </tr>
				  <?php else:?>
				  		<tr class="frecanpy">
				        <th>Cancellation Policy:<br/><a  href="#" data-target="#roomCancelModal"  data-toggle="modal" >View Cancellation Policy</a></th>
				        <td><?php if($pre_booking_params['cancellation_available']==1):?>
							<span>Refundable</span>
						<?php else:?>
							<span>Non-Refundable</span>
						<?php endif;?></td>
				      </tr>
			 	 <?php endif;?>
			 	<!--   <tr class="frecanpy">
				        <th><a  href="#" data-target="#roomCancelModal" data-toggle="modal" >View Cancellation Policy:</a></th>
			      </tr> -->
			      <!--<tr>
			        <!<th>Total Price</th>
			        <td><?=$this->currency->get_currency_symbol(@$pre_booking_params['default_currency'])?> <?=$pre_booking_params['markup_price_summary']['TotalDisplayFare']?></td>
			      </tr>
			      <tr class="texdiv">
			        <th>Taxes & Service fee</th>
			        <td><?=$this->currency->get_currency_symbol(@$pre_booking_params['default_currency'])?> <?=@$tax_total?></td>
			      </tr>-->
			      <?php //if($pre_booking_params['markup_price_summary']['_GST'] > 0){ ?>
			      <!--<tr class="texdiv">
			        <th>GST</th>
			        <td><?=$this->currency->get_currency_symbol(@$pre_booking_params['default_currency'])?> <?=$pre_booking_params['markup_price_summary']['_GST'] ;?></td>
			      </tr>-->
			      <?php // } ?>
			      <?php if($pre_booking_params['convenience_fees'] > 0){ ?>
			      <tr class="texdiv">
			        <th>Convenience Fees</th>
			        <td><?=$this->currency->get_currency_symbol(@$pre_booking_params['default_currency'])?> <?=$pre_booking_params['convenience_fees'] ;?></td>
			      </tr>
			      <?php } ?>
		
			      <tr class="grd_tol">
			      	<span class="grandtotal_valueorg" hidden><?=$pre_booking_params['markup_price_summary']['NetFare']?></span>
			        <th>Grand Total</th>
			        <td><?=$this->currency->get_currency_symbol(@$pre_booking_params['default_currency'])?><span class="total_booking_amount grandtotal_value"><?=$pre_booking_params['markup_price_summary']['NetFare']?></span></td>
			      </tr>
			    </tbody>
			  </table>
			 
		   </div>
		  </div>
		
	
		<div class="clearfix"></div>
	
	</div>
	<div class="bktab2 xlbox <?=@$travellers_tab_details_class?>">
	  <div class="col-xs-12 col-md-8 ">
		<div class="col-xs-12 topalldesc">
			<div class="col-xs-12 nopad">
				<div class="bookcol">
			  <div class="hotelistrowhtl">
				<div class="col-md-4 nopad xcel">
				  <div class="imagehotel">
				  		<?php if($value->image!=''):?>

				  		<?php
				  			//$image = $base_url.'/'.base64_encode($pre_booking_params['HotelCode']).'/0';
				  			$image= $value->vehicle_image;
				  		?>

				  		<img alt="<?=$value->ProductName?>" src="<?=$GLOBALS['CI']->template->domain_upload_pckg_images($image)?>">
				  	<?php else:?>
				  		<img alt="Hotel_img" src="<?=$GLOBALS['CI']->template->template_images('no_image_available.jpg')?>" class="lazy h-img">
				  	<?php endif;?>
				  </div>
				</div>
				<div class="col-md-8 padall10 xcel">
				  <div class="hotelhed"><?=$value->ProductName?></div>
				  <div class="clearfix"></div>
				  	<div class="mensionspl"> <?php
				  		 if($value->Location){
						  	echo "$value->Location";
						  }


				  ?> </div>

				  <div class="bokkpricesml">
				  	<div class="totlbkamnt "> <span class="ttlamtdvot" style="color: #0095ce;"><?=$transfer_name?></div>
					<div class="travlrs"><span class="travlrsnms">Travelers:</span><span class="fa fa-male"></span> <?=$total_pax?></div>
					<div class="totlbkamnt "> <span class="ttlamtdvot">Total Amount</span><?=$this->currency->get_currency_symbol($pre_booking_params['default_currency'])?> <?=round(($pre_booking_params['markup_price_summary']['NetFare']),2)?>/-</div>
					</div>
				</div>
			  </div>
			</div>
			</div><!-- Outer Summary -->
			<div class="col-xs-4 nopadding celtbcel colrcelo hide">
				
			</div>
		</div>



		<div class="clearfix"></div>

	

		<div class="col-xs-12 padpaspotr nopad n">
		<div class="col-xs-12 nopadding">
		<div class="fligthsdets">
		<?php
/**
 * Collection field name 
 */
//Title, Firstname, Middlename, Lastname, Phoneno, Email, PaxType, LeadPassenger, Age, PassportNo, PassportIssueDate, PassportExpDate


//------------------------------ DATEPICKER END
$total_pax_count	= $adult+$child;
//First Adult is Primary and and Lead Pax
$adult_enum = $child_enum = get_enum_list('viator_title');
$gender_enum = get_enum_list('gender');
// debug($adult_enum);exit;
// unset($adult_enum[MASTER_TITLE]); // Master is for child so not required
// unset($child_enum[MASTER_TITLE]); // Master is not supported in TBO list
// unset($adult_enum[MISS_TITLE]); // Miss is not supported in GRN list
// unset($child_enum[MISS_TITLE]);
// unset($child_enum[C_MRS_TITLE]);
// unset($adult_enum[A_MASTER]);
unset($adult_enum[MISS_TITLE]);
unset($child_enum[MASTER_TITLE]);
$adult_title_options = generate_options($adult_enum, false, true);
$child_title_options = generate_options($child_enum, false, true);
// debug($adult_title_options);exit;
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
function is_lead_pax($pax_count)
{
	return ($pax_count == 1 ? true : false);
}
$lead_pax_details = @$pax_details[0];
 ?>
<form action="<?=base_url().'index.php/transfer/pre_booking/'.$search_id?>" method="POST" autocomplete="off">
	

		<div class="hide">
			<?php $dynamic_params_url = serialized_data($pre_booking_params);?>
			<input type="hidden" name="BlockTourId" value="<?=@$pre_booking_params['BlockTourId']?>">
			<input type="hidden" required="required" name="token"		value="<?=@$dynamic_params_url;?>" />
			<input type="hidden" required="required" name="token_key"	value="<?=md5(@$dynamic_params_url);?>" />
			<input type="hidden" required="required" name="op"			value="book_activity">
			<input type="hidden" required="required" name="booking_source"		value="<?=@$booking_source?>" readonly>
			<input type="hidden" required="required" name="promo_code_discount_val" id="promo_code_discount_val" value="0.00" readonly>
			<input type="hidden" required="required" name="promo_code" id="promocode_val" value="" readonly>
			<input type="hidden" name="module_type" id="module_type" class="promocode" value="<?=md5('transfers')?>" />

			<input type="hidden" required="required" name="promo_actual_value" id="promo_actual_value" value="" readonly>
			<input type="hidden" required="required" name="price_id" id="price_id" value="<?=$price_id?>" readonly>
          	<input type="hidden" name="redeem_points_post" id="redeem_points_post" value="0">
	          <input type="hidden" name="reward_usable" value="<?=round($reward_usable,2)?>">
	          <input type="hidden" name="reward_earned" value="<?=round($reward_earned,2)?>">
	          <input type="hidden" name="total_price_with_rewards" value="<?=round($total_price_with_rewards)?>">
	          <input type="hidden" name="reducing_amount" id="reduce_amount" value="<?=round($reducing_amount)?>">
		</div>
			 <div class="flitab1">
			<div class="moreflt boksectn">
					<div class="ontyp">
						<div class="labltowr arimobold">Please enter the customer names.</div>
<div class="pasngrinput _passenger_hiiden_inputs" style="padding: 5px !important;">
<?php
	
	if(is_logged_in_user()) {
		$traveller_class = ' user_traveller_details ';
	} else {
		$traveller_class = '';
	}
	$pax_index=1;

	//debug($pre_booking_params['AgeBands']);
	//echo $total_pax;
	foreach ($pre_booking_params['AgeBands'] as $p_key => $p_value) {
	 
	 $name_index = 1;
	//for($pax_index=1; $pax_index <= $total_pax_count; $pax_index++) {//START FOR LOOP FOR PAX DETAILS
	//$cur_pax_info = is_array($pax_details) ? array_shift($pax_details) : array();
		for($pax_index=1;$pax_index<=$p_value['count'];$pax_index++){
?>
			
				<div class="hide hidden_pax_details">
				  <?php 
				  	$passenger_type =1; //adult
				  	if($p_value['bandId']==1){
				  		$passenger_type = 1;
				  	}elseif ($p_value['bandId']==2) {
				  		$passenger_type = 2;//child
				  	}
				  	
				 	
				  ?>
					<input type="hidden" name="passenger_type[]" value="<?=$passenger_type?>">
					<input type="hidden" name="lead_passenger[]" value="<?=(is_lead_pax($p_key) ? true : false)?>">
					
				</div>
				<div class="col-md-12 nopad">
				<!-- <div class="col-md-4 nopad nopadding">
					
				   <div class="adltnom">
				   		<?
				   			$title_select_options = $adult_title_options;

				   		?><?=(is_lead_pax($p_key+$pax_index) ? '- Lead Pax' : '')?></div>
				
				 </div> -->
				 <div class="col-md-12 nopad nopadding">
				 <div class="inptalbox">
					<div class="col-xs-3 col-sm-3 col-md-3 spllty">
						<select class="mySelectBoxClass flyinputsnor name_title" name="name_title[]" required>
						<?php echo $adult_title_options;?>
						</select>
					</div>
					<div class="col-xs-4 col-md-5 spllty">
						  <input value="<?=@$cur_pax_info['first_name']?>" required="required" type="text" name="first_name[]" id="passenger-first-name-<?=$name_index?>" class="clainput alpha_space specialchar <?=$traveller_class?>"  minlength="2" maxlength="45" placeholder="Enter First Name" data-row-id="<?=($pax_index);?>"/>
						  
					</div>
					<div class="col-xs-4 col-md-4 spllty">
					 	<input value="<?=@$cur_pax_info['last_name']?>" required="required" type="text" name="last_name[]" id="passenger-last-name-<?=$name_index?>" class="clainput alpha_space specialchar last_name" minlength="2" maxlength="45" placeholder="Enter Last Name" />
					 </div>
					 <?php 
					 	// debug($pre_booking_params['BookingQuestions']);
					 	// exit;
					 ?>
					  <div class="col-md-8 nopad">
					 <?php if(isset($pre_booking_params['BookingQuestions'])):?>

					 		<?php foreach($pre_booking_params['BookingQuestions'] as $bq_key=>$bq_value):  ?><!-- start-->
                            	<?php if($bq_value['stringQuestionId']=='weights_passengerWeights'):?>
                            		<?php if($bq_value['required']==1){?>
                            				<div class="col-md-5 nopad">
		                                      	 <div class="col-md-6 spllty">
		                                      	
		                                         <input class="form-control numeric clainput" type="text" id="weight" placeholder="<?=$bq_value['title']?>" maxlength="3" name="pax_question[<?=$bq_value["questionId"]?>][]" required="required">
		                                        </div>		                          
		                                    <div class="col-md-6 spllty">
		                                          <div class="mr-slt-div">
		                                            <?php 
		                                              $disable ='disabled';
		                                            	//$disable='';
		                                              if($pax_index==1){
		                                                $disable='';                    
		                                              }
		                                              ?>
		                                          <select class="form-control p-weight weight_measure_<?=$bq_key?>" name="weight" 
		                                          data-key = "<?=$bq_key?>"
		                                          <?=$disable?> id="">
		                                                    <option value="kgs">Kg</option>
		                                                    <option value="pounds">lb</option>
		                                                </select>
		                                          </div>
		                                   </div> 
		                                   <span class="spllty" style="font-size: 11px; display: block;"><?=$bq_value['message'].''.$bq_value['subTitle']?></span>
	                                   </div>
                            		<?php } ?>
                            	<?php elseif($bq_value['stringQuestionId']=='heights_passengerHeights'):?>
                            		<?php if($bq_value['required']==1):?>
                            			<div class="col-md-5 nopad">
	                                      	<div class="col-md-6 spllty">
	                                         	<input class="form-control clainput numeric" type="text" id="weight" placeholder="Heights" maxlength="3" name="pax_question[<?=$bq_value["questionId"]?>][]" required="required">
	                                        </div>
			                                    <div class="col-md-6 spllty">
			                                          <div class="mr-slt-div">
			                                            <?php 
			                                              $disable ='disabled';
			                                            	//$disable='';
			                                              if($pax_index==1){
			                                                $disable='';                    
			                                              }
			                                              ?>
			                                          <select class="form-control p-height height_measure_<?=$bq_key?>" name="height" 
			                                          data-key = "<?=$bq_key?>"
			                                          <?=$disable?> id="">
			                                                    <option value="cm">cm</option>
			                                                    <option value="ft/in">ft/in</option>
			                                                </select>
			                                          </div>
			                                   </div> 
			                                  <span class="spllty" style="font-size: 11px; display: block;"><?=$bq_value['message'].''.$bq_value['subTitle']?></span>
	                                   </div>
                            		<?php endif;?>
                            	<?php elseif($bq_value['stringQuestionId']=='dateOfBirth_dob'):?>
                            		<?php if($bq_value['required']==1):?>
                            			<div class="col-md-2 nopad">
	                                      	<div class="col-md-12 spllty">
                            		
                            			 <input type="text" class="form-control clainput dobdatepickerbook" id=""  name="pax_question[<?=$bq_value["questionId"]?>][]" placeholder="<?=$bq_value['title']?>" required="required" readonly >
                                        </div>
                                        </div>
                            	    <?php endif;?>
                            	<?php elseif($bq_value['stringQuestionId']=='passport_expiry'):?>
                            		<?php if($bq_value['required']==1):?>
                            			<div class="col-md-3 nopad">
	                                      	<div class="col-md-12 spllty">
                            			 <input type="text" class="form-control clainput expiraydatepickerbook" id=""  name="pax_question[<?=$bq_value["questionId"]?>][]" placeholder="<?=$bq_value['title']?>" required="required" readonly >
                            			   </div>
                                        </div>
                            	    <?php endif;?>
                            	<?php elseif($bq_value['stringQuestionId']=='passport_passportNo'):?>
                            		<?php if($bq_value['required']==1):?>
                            			<div class="col-md-3 nopad">
	                                      	<div class="col-md-12 spllty">
                            			 <input type="text" class="form-control clainput " id=""  name="pax_question[<?=$bq_value["questionId"]?>][]" placeholder="<?=$bq_value['title']?>" required="required"  onkeypress="return alphanumeric(event)">
                            			   </div>
                                        </div>
                            	    <?php endif;?>
                            	<?php elseif($bq_value['stringQuestionId']=='passport_nationality'):?>
                            		<?php if($bq_value['required']==1):?>
                            			<div class="col-md-3 nopad">
	                                      	<div class="col-md-12 spllty">
	                                      	<select class="form-control" name="pax_question[<?=$bq_value["questionId"]?>][]" required="required">
	                                      		<?php foreach($phone_code as $phone_val):?>
	                                      			<option value="<?=$phone_val['name']?>"><?=$phone_val['name']?></option>
	                                      		<?php endforeach;?>
	                                      	</select>
                            			 	<span><?=$bq_value['title']?></span>
                            			   </div>
                                        </div>
                            	    <?php endif;?>
                            	<?php endif;?>

                            <?php endforeach;?><!-- end -->                             
                    <?php endif;?>
                                 
				</div>
				</div>
			</div>
		</div>
			 <div class="clearfix"></div>
<?php
		$name_index++;
		}//for loop
	}//foreach

//}//END FOR LOOP FOR PAX DETAILS
?>
					</div>
				</div>	

<!-- Start Hotel Pickup -->
 <div class="clearfix"></div>
<?php
 //debug($pre_booking_params);exit;
?>
  <!-- Hotel pickup -->
	<!-- <div class="pasngrinput _passenger_hiiden_inputs" style="margin-top: 25px;">
		<div class="col-md-8 nopad">
		 	 <div class="col-md-3 nopad">
		 	 	<div class="labbkdiv">
                         <label>Hotel Pickup<span class="starclr">*</span></label>
                  </div>
		 	 </div>


		 	  	<div class="col-md-7 nopad hide col-sm-offset-3" id="hotelPickup_name" style="margin-top: 10px;">
                      <input type="text" class="form-control " name="hotelPickup_name" placeholder="Hotel Pickup">
              	</div>


		 		<div class="col-md-7 nopad" id="hotel_pickup_id">
                      <input type="text" class="form-control" name="hotelPickup_name" placeholder="Hotel Pickup">
              	</div>

		</div>
		</div> -->

<input type="hidden" name="hotel_pickup_list_name" id="hotel_pickup_list_name" value="<?=$pre_booking_params['pickup_location']?>">
<div class="clearfix"></div>

<!--End -->			
<!-- Booking Question  Start-->
		 <?php if(valid_array($questions)):?>
                    <?php foreach($questions as $q_key=>$q_value): $requierd='';?>
              
                        <div class="pasngrinput _passenger_hiiden_inputs">
                             <div class="col-md-9 nopad">
                                   <div class="col-md-5 nopad">
                                       <div class="labbkdiv">
                                        <label><?=$q_value['text']?></label>
                                        <?php if($q_value['required']==1){
                                            $requierd='required';
                                            echo '<span class="starclr">*</span>';
                                        }?>
                                            
                                       </div> 
                                   </div>                                 
                                   <div class="col-md-7 nopad">
                                    <div class="mr-inp-div">
                               		<input type="hidden" name="question_code[<?=@$q_key?>][]" value="<?=$q_value['code']?>">
                                      <input type="text" class="form-control" id=""  name="answer[<?=@$q_key?>][]" placeholder="" <?=$requierd?>>
                                    </div>  
                                   </div>
                                
                                   
                          </div>
                          </div>
                    <?php endforeach;?>
            <?php endif;?>
<!-- End-->
<div class="clearfix"></div>

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
									                    <div class="col-md-4 col-xs-8 nopadding_right">
									                      <div class="cartprc">
									                        <div class="payblnhm singecartpricebuk ritaln">
									                    	 <input type="text" placeholder="Enter Promo" name="code" id="code" class="promocode" aria-required="true" />
									                          <input type="hidden" name="module_type" id="module_type" class="promocode" value="<?=@$module_value;?>" />
									                          <input type="hidden" name="total_amount_val" id="total_amount_val" class="promocode" value="<?=$pre_booking_params['markup_price_summary']['NetFare']?>" />
									                          <input type="hidden" name="convenience_fee" id="convenience_fee" class="promocode" value="<?=@$convenience_fees;?>" />
									                          <input type="hidden" name="currency_symbol" id="currency_symbol" value="<?=@$currency_symbol;?>" />
									                          <input type="hidden" name="currency" id="currency" value="<?=@$pre_booking_params['default_currency'];?>" />
									                         
									                           <p class="error_promocode text-danger" style="font-weight:bold"></p>                     
									                        </div>
									                      </div>
									                    </div>
									                    <div class="col-md-2 col-xs-4 nopadding_left">
									                      <input type="button" value="Apply" name="apply" id="apply" class="promosubmit">
									                    </div>
									                  </form>
													</div>
													<div class="loading hide" id="loading"><img src="<?php echo $GLOBALS['CI']->template->template_images('loader_v3.gif')?>"></div>
													<div class="clearfix"></div>
													<div class="savemessage"></div>
												</div>
											</div>
											</div>
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
					<div class="col-md-4 col-xs-5 nopadding">
					<select name="country_code" class="newslterinput nputbrd _numeric_only " id="after_country_code" required>
											<?php echo diaplay_phonecode($phone_code,$active_data, $user_country_code); ?>
										</select> 
					</div>
					<div class="col-md-1 col-xs-2"><div class="sidepo">-</div></div>
					<div class="col-md-4 col-xs-5 nopadding">
					<input value="<?=@$lead_pax_details['phone'] == 0 ? '' : @$lead_pax_details['phone'];?>" type="text" name="passenger_contact" id="passenger-contact" placeholder="Mobile Number" class="newslterinput nputbrd _numeric_only" maxlength="10" required="required">
					</div>
					<div class="clearfix"></div>
					<div class="emailperson col-md-9 col-xs-12 nopad">
					<input value="<?=@$lead_pax_details['email']?>" type="text" maxlength="80" required="required" id="billing-email" class="newslterinput nputbrd" placeholder="Email" name="billing_email">
					</div>
					</div>
					<div class="clearfix"></div>
					<div class="notese">Your mobile number will be used only for sending transfer related communication.</div>
					<div class="clearfix"></div>
					<div class="col-md-6 col-xs-12 nopad">
					<textarea id="remarks_user" class="form-control" placeholder="Remarks" name="remarks_user"></textarea>
					</div>
					</div>
					<div class="clearfix"></div>
					<div class="notese">Enter Flight and other details.It will be used only for sending transfer related communication.</div>
				</div>
				<div class="clikdiv">
					 <div class="squaredThree">
					 <input id="terms_cond1" type="checkbox" name="tc" checked="checked" required="required">
					 <label for="terms_cond1"></label>
					 </div>
					 <div class="clikagre" id="clikagre">
					 	<p><a href="<?php echo base_url();?>index.php/general/terms_n_condition/transfers" target="_blank">Terms & Conditions</a> and <a href="<?php echo base_url();?>index.php/general/privacy_policy/transfers" target="_blank">Privacy Policy</a></p>
					 </div>
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
							<!-- <div class="continye col-xs-3">
						<input type="radio" id="offline" name="payment_method" value="<?= OFFLINE_PAYMENT ?>" onclick="showoffline(this.value)" hidden>
				
						 <input type="radio" id="online" name="payment_method" value="<?= ONLINE_PAYMENT ?>" onclick="showonline(this.value)" checked hidden>
			
						</div> -->
						<div class="continye col-xs-3">
							<button  class="bookcont" type="submit" style="margin-top:10px;">Continue</button>
						</div>
						<div class="clearfix"></div>
						<div class="sepertr"></div>
						
						<div class="temsandcndtn">
						Most countries require travelers to have a passport valid for more than 3 to 6 months from the date of entry into or exit from the country. Please check the exact rules for your destination country before completing the booking.
						<br><br><!-- 
						<h5>CONTRACT REMARK </h5>
						<?=$remarks?> -->
						</div>
					</div>
				</div>
			</div>
			</form>
			</div>
		</div>
	 	<?php if(is_logged_in_user() == true) { ?>
			<!-- <div class="col-xs-12 nopadding">
				<div class="insiefare">
					<div class="farehd arimobold">Passenger List</div>
					<div class="fredivs">
						<div class="psngrnote">
							<?php
								if(valid_array(@$traveller_details)) {
									$traveller_tab_content = 'You have saved passenger details in your list,on typing, passenger details will auto populate.';
								} else {
									$traveller_tab_content = 'You do not have any passenger saved in your list, start adding passenger so that you fvdo not have to type every time. <a href="'.base_url().'index.php/user/profile?active=traveller" target="_blank">Add Now</a>';
								}
							?>
							<?=$traveller_tab_content;?>
						</div>
					</div>
				</div>
			</div> -->
		<?php } ?>
		</div>



		</div>

		
		<div class="col-xs-4 rhttbepa">
			   <div id="nxtbarslider">
			 	<table class="table table-condensed tblemd">
				 	<tbody>
				 	  <tr class="rmdtls" style="background-color: #007dc6!important;">
				        <th>Transfer Details</th>
				        <td></td>
				      </tr>
				  		
				      <tr>
				        <th>No of Pax</th>				        
				        <td><?=$total_pax?></td>
				      </tr>
				      <tr>
				        <th>Departure Date</th>
				        <td><?=date('d M Y',strtotime($departure_date))?></td>
				      </tr>
				      <tr>
				        <th>Departure Time</th>
				        <td><?=$time_in_12_hour_format?></td>
				      </tr>
				    
				       
					  		<tr class="frecanpy">
					        <th>Cancellation Policy:<br/><a  href="#" data-target="#roomCancelModal" data-toggle="modal">View Cancellation Policy</a></th>
					        <td><?php if($pre_booking_params['cancellation_available']==1):?>
							<span>Refundable</span>
						<?php else:?>
							<span>Non-Refundable</span>
						<?php endif;?></td>
					      </tr>
				 	<!--   <tr class="frecanpy">
				        <th><a  href="#" data-target="#roomCancelModal" data-toggle="modal" >View Cancellation Policy:</a></th>
			      		</tr>
				 	   -->
						<!--<tr>
						<th>Total Price</th>
						<td><?=$this->currency->get_currency_symbol(@$pre_booking_params['default_currency'])?> <?=round(($pre_booking_params['markup_price_summary']['TotalDisplayFare']),2)?></td>
						</tr>-->
						<!--<tr class="texdiv">
						 <th>Taxes & Service fee</th>
						<td><?=$this->currency->get_currency_symbol(@$pre_booking_params['default_currency'])?> <?=@$tax_total?></td>
						</tr> -->
						<?php //if($pre_booking_params['markup_price_summary']['_GST'] > 0){ ?>
						<!--<tr class="texdiv">
						<th>Taxes & Service fee</th>
						<td><?=$this->currency->get_currency_symbol(@$pre_booking_params['default_currency'])?> <?=$pre_booking_params['markup_price_summary']['_GST'] ;?></td>
						</tr>-->
						<?php // } ?>
						<?php // if($pre_booking_params['convenience_fees'] > 0){ ?>
						<!--<tr class="texdiv">
						<th>Convenience Fees</th>
						<td><?=$this->currency->get_currency_symbol(@$pre_booking_params['default_currency'])?> <?=round(($pre_booking_params['convenience_fees']),2) ;?></td>
						</tr>-->
						<?php //} ?>
						<tr class="promo_code_discount hide">
						<th>Promo Code Discount</th>
						<td class="promo_discount_val"></td>
						</tr>
						        <?php
			       if($reward_usable > 0){ ?>
                        <tr class="texdiv">
                            <th>Redeem Rewards
                                <label class="switch"> <input type="checkbox" id="redeem_points" data-toggle="toggle" data-size="mini" name="redeem_points"> <span class="slider_rew
                          round"></span> </label> 
                            </th> 
                            <td colspan="2"><span id="available_rewards">0 Points</span></td> 
                        </tr> 
                      <?php }
                         if($reward_earned > 0)
                          { ?> 
                            <tr class="texdiv"> <th>Rewards Earned</th>
                          <td><span class="label label-primary"><?=$reward_earned?></span></td> </tr>
                          <?php } ?>
						<tr class="grd_tol">
							<span class="grandtotal_valueorg" hidden><?=$pre_booking_params['markup_price_summary']['NetFare']?></span>
						<th>Grand Total</th>
						<td><?=$this->currency->get_currency_symbol(@$pre_booking_params['default_currency'])?> <span class="total_booking_amount grandtotal_value"><?=$pre_booking_params['markup_price_summary']['NetFare']?></span></td>
						</tr>
				      				    </tbody>
				  </table>
			   </div>
		</div>

		</div>
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
				  <img src="" id="trip_adv_img">
				</div>
			</div>
			<div class="modal-body">
				
				<p class="policy_text"><?php echo $pre_booking_params['cancellation_details']; ?></p>

				
			</div>
			<div class="modal-footer">
	          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        </div>
		</div>
	</div>
</div>
<?php } ?>
<!-- <script type="text/javascript">
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
        		$("#nxtbarslider").css({position: "fixed", top:10});  	
        	}   
        	else if ((top <243) || (height < 300))
        	{
        		$("#nxtbarslider").css({position: "", top:12});  	
        	}         
            
        }
    });  
});
</script> --> 

<?php 

function diaplay_phonecode($phone_code,$active_data, $user_country_code)
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
	$(document).ready(function() {
   $('#flip').click(function(){
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
        //  alert('Insufficient Balance!!');
                return true;
              }else{}
              }
   });

});
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
	        minDate:0,
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

(function($) {
  $.fn.inputFilter = function(inputFilter) {
    return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function() {
      if (inputFilter(this.value)) {
        this.oldValue = this.value;
        this.oldSelectionStart = this.selectionStart;
        this.oldSelectionEnd = this.selectionEnd;
      } else if (this.hasOwnProperty("oldValue")) {
        this.value = this.oldValue;
        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
      } else {
        this.value = "";
      }
    });
  };
}(jQuery));
$(".specialchar").inputFilter(function(value) {
  return /^[a-z]*$/i.test(value); });
</script>

<?php
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/sightseeing_booking.js'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/booking_script.js'), 'defer' => 'defer');?>

	<script>
  ////Rewards//////////////////

$("#redeem_points").change(function(){
    
        var promodiscount = $("#promo_code_discount_val").val();
        if(promodiscount=="0.00")
        {
promodiscount=0;
        }
        var reducereward=$("#reduce_amount").val();
        var orgamount=$(".grandtotal_valueorg").html();
        console.log(orgamount);
       // alert(reducereward);
        var div = $('#prompform');
    if($(this). prop("checked") == true)
    {
      div.hide();
      $("#redeem_points_post").val(1);
      $(".grandtotal_value").text();
      // alert('true');
         $("#total_amount_val").val(parseInt(orgamount)-parseInt(reducereward));
      $("#available_rewards").text('<?php echo round($reward_usable)?> Points');
     // $()
      var total=parseInt(orgamount)-parseInt(reducereward)-parseInt(promodiscount);
   
      $(".grandtotal_value").html(total);
     

    }else{
      // alert('false');
      div.show();
      $("#redeem_points_post").val(0);
      $(".grandtotal_value").text();
      $("#available_rewards").text('0 Points');
       $("#total_amount_val").val(parseInt(orgamount));
       var total=parseInt(orgamount)-parseInt(promodiscount);
      $(".grandtotal_value").html(total);

       
    }
  
     });

     $(document).ready(function(){
      if($("#redeem_points"). prop("checked") == true)
      {
      $('#prompform').hide();
      }
   });
  </script>


