<style>
th, td {
	padding: 5px;
}

table {
	page-break-inside: auto
}

tr {
	page-break-inside: avoid;
	page-break-after: auto
}
</style>

<style>
@media print {
    .clearfix, .fstfooter, .container, .btmfooter, #myModal, .topssec, .section_top  {
        display: none !important;
    }
}
</style>

<?php

$con_rate =  $details['booking_details'][0]['currency_conversion_rate'];
$logo = $GLOBALS['CI']->template->domain_images($logo);
$itinerary_details = $details ['passenger_details'];
$price = $details['transaction_details'];
$attributes = $details ['booking_details'];
$price_attribute = json_decode($price[0]['attributes'],true);
$discount = $price[0]['discount'];
$convenience_fee = $price_attribute['convenience_fee']/$con_rate;
$total = $price_attribute['Fare']/$con_rate;
$discount = $discount;
$package = $details['package_details'];
$currency_obj = new Currency(array('module_type' => 'sightseeing', 'from' => $price[0]['currency'], 'to' =>get_application_default_currency()));
// debug($currency_obj);die;
?>
<div class="table-responsive">
<table
	style="    border-collapse: collapse;
    background: #f5f5f5;
    border: 15px solid #fff;
    font-size: 13px;
    line-height: 18px;
    margin: 15px auto;
    font-family: arial;
    max-width: 900px;"
	width="100%" cellpadding="0" cellspacing="0" border="0">
	<tbody>
		<tr>
			<td style="border-collapse: collapse; padding: 10px 20px 20px">
				<table width="100%" style="border-collapse: collapse;"
					cellpadding="0" cellspacing="0" border="0">


					<tr>
						<td style="padding: 10px;">
							<table cellpadding="0" cellspacing="0" border="0" width="100%"
								style="border-collapse: collapse;">
								<tr>
									<td
										style="font-size: 22px; line-height: 30px; width: 100%; display: block; font-weight: 600; text-align: center">
						        		  E-Ticket
						          </td>
								</tr>
								<tr>
									<td>
										<table width="100%" style="border-collapse: collapse;"
											cellpadding="0" cellspacing="0" border="0">
											<tr>
												
												<td style="width: 40%">
													<table width="100%"
														style="border-collapse: collapse;  line-height: 15px;"
														cellpadding="0" cellspacing="0" border="0">
														<tr>
															<td style="font-size: 14px;    padding: 0;"><!-- <span
																style="width: 100%; float: left">
																<p style="margin-bottom: 6px;font-size:14px;font-weight: 500;line-height: 19px;">Tripmia.com.au<br>
ABN# 21615437002<br>
PO Box 5034<br>
Kingsdene NSW 2118<br>
61879792323<br>
contact@tripmia.com.au</p>
																</span> -->
															</td>
														</tr>
													</table>
												</td><td style="text-align: right;width: 60%"><img style="max-width: 265px;"
													src="<?=base_url()."../../".$GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template->get_domain_logo())?>"></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td width="100%"
										style="background:#666;color:white;padding: 10px; border: 1px solid #cccccc; font-size: 14px; font-weight: bold;">Reservation
										Lookup</td>

								</tr>
								<tr>
									<td style="border: 1px solid #cccccc;" width="100%">
										<table width="100%" cellpadding="5"
											style="padding: 10px; font-size: 14px;">
											<tr>
												<td width="50%"><strong>Booking Reference</strong></td>
												<td width="50%"><?=@$attributes[0]['app_reference']?></td>
											</tr>
											<tr>
												<!-- <td><strong>Booking ID</strong></td>
												<td><strong>PNR</strong></td> -->
												<td  width="50%"><strong>Booked On</strong></td>
												<td  width="50%"><?=app_friendly_absolute_date(@$attributes[0]['created_datetime'])?></td>
											</tr>
											<tr>
												<td  width="50%"><strong>Travelling Date</strong></td>
												<td  width="50%"><?=app_friendly_absolute_date(@$attributes[0]['date_of_travel'])?></td>
											</tr>
											<tr>
												<!-- <td  width="50%"><strong>Status</strong></td>
													<td  width="50%"><strong
													class="<?php echo booking_status_label( @$attributes[0]['status']);?>"
													style="font-size: 14px; color: #28b700;    padding: 3px 10px !important;"> -->
											<?php
											if($attributes[0]['status']=="")
											{
											    $attributes[0]['status']='BOOKING_INPROGRESS';
											}
											switch (@$attributes[0] ['status']) {
												case 'BOOKING_CONFIRMED' :
													echo 'CONFIRMED';
													break;
												case 'BOOKING_CANCELLED' :
													echo 'CANCELLED';
													break;
												case 'BOOKING_FAILED' :
													echo 'FAILED';
													break;
												case 'BOOKING_INPROGRESS' :
													echo 'INPROGRESS';
													break;
												case 'BOOKING_INCOMPLETE' :
													echo 'INCOMPLETE';
													break;
												case 'BOOKING_HOLD' :
													echo 'HOLD';
													break;
												case 'BOOKING_PENDING' :
													echo 'PENDING';
													break;
												case 'BOOKING_ERROR' :
													echo 'ERROR';
													break;
											}
											
											?>
											</strong></td>
											</tr>
										</table>
									</td>
								</tr>
								
								<!-- <tr>
									<td
										style="padding: 10px; border: 1px solid #cccccc; font-size: 14px; font-weight: bold;">Journey
										Information</td>

								</tr>
								<tr>
									<td width="100%" style="border: 1px solid #cccccc;">
										<table width="100%" cellpadding="5"
											style="padding: 10px; font-size: 14px;">
											<tr>
												<td><strong>Flight</strong></td>
												<td><strong>AirlinePNR</strong></td>
												<td><strong>Departure</strong></td>
												<td><strong>Arrival</strong></td>
												<td><strong>Journey Time</strong></td>
											</tr>
											<tr>
										<?php
										if (isset ( $booking_transaction_details ) && $booking_transaction_details != "") {
											
											if ($booking_details ['is_domestic'] == true && count ( $booking_transaction_details ) == 2) {
												$itinerary_details = array ();
												$itinerary_details = $onward_segment_details;
											}
											foreach ( $itinerary_details as $segment_details_k => $segment_details_v ) {
												
												$itinerary_details_attributes = json_decode ( $segment_details_v ['attributes'], true);
												$airline_terminal_origin = @$itinerary_details_attributes['departure_terminal'];
												$airline_terminal_destination = @$itinerary_details_attributes['arrival_terminal'];
												$origin_terminal = '';
												$destination_terminal = '';
												if ($airline_terminal_origin != '') {
													$origin_terminal = 'Terminal ' . $airline_terminal_origin;
												}
												if ($airline_terminal_destination != '') {
													$destination_terminal = 'Terminal ' . $airline_terminal_destination;
												}
												
												if (valid_array ( $segment_details_v ) == true) {
													?>
                              
                                 <?php if($booking_details['trip_type'] == 'circle' && $booking_details['is_domestic'] == true){?>
                                  <?php }?>
                                        <td><img
													style="max-height: 30px"
													src="<?=SYSTEM_IMAGE_DIR.'airline_logo/'.$segment_details_v['airline_code'].'.gif'?>"
													alt="flight-logo" /> <span style="width: 100%; float: left"><?=@$segment_details_v['airline_name']?></span>
													<span
													style="width: 100%; float: left; font-size: 13px; font-weight: bold"><?php echo $segment_details_v['airline_code'].' '.$segment_details_v['flight_number'];?></span></td>
													<td><?=$segment_details_v['airline_pnr'];?></td>
													
												<td style="line-height: 16px"><span
													style="width: 100%; float: left; font-size: 13px; font-weight: bold"> <?=@$segment_details_v['from_airport_name'] ?>(<?=@$segment_details_v['from_airport_code']?>)</span>
													<span style="width: 100%; float: left"><?php echo $origin_terminal;?></span>
													<span style="width: 100%; float: left; font-weight: bold"> <?php echo date("d M Y",strtotime($segment_details_v['departure_datetime'])).", ".date("H:i",strtotime($segment_details_v['departure_datetime']));?></span></td>
												<td style="line-height: 16px"><span
													style="width: 100%; float: left; font-size: 13px; font-weight: bold"> <?=@$segment_details_v['to_airport_name']?>(<?=@$segment_details_v['to_airport_code']?>)</span>
													<span style="width: 100%; float: left"> <?php echo $destination_terminal;?></span>
													<span style="width: 100%; float: left; font-weight: bold">  <?php echo date("d M Y",strtotime($segment_details_v['arrival_datetime'])).", ".date("H:i",strtotime($segment_details_v['arrival_datetime']));?></span></td>
												<td>
													<!-- <span style="width:100%; float:left">Non-Stop</span> -->
													<!-- <span style="width: 100%; float: left"><?php echo $segment_details_v['total_duration'];?></span>
												</td>
											</tr>
									 <?php
												
}
											}
										}
										?>	
									</table>
									</td>
								</tr>  -->
								<tr>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td width="100%"
										style="background:#666;color:#fff;padding: 10px; border: 1px solid #cccccc; font-size: 14px; font-weight: bold;">Travelers
										Information</td>

								</tr>
								<tr>
									<td width="100%" style="border: 1px solid #cccccc;">
										<table width="100%" cellpadding="5"
											style="padding: 10px; font-size: 14px;">
											 <?php
										// echo $cus_v['passenger_type'].
										$cus_v = $itinerary_details[0];
										
										if (isset ( $cus_v )) {
										?>
											<tr>
												<td style="width:50%;"><strong>Lead Passenger Name</strong></td>
												<td style="width:50%;"><?php echo $cus_v['passenger_type'].'-'.$cus_v['first_name'].'  '.$cus_v['last_name'];?></td>
											</tr>
											<tr>
												<td style="width:50%;"><strong>Count Of Sub Passanger</strong></td>
												<td style="width:50%;"><?php echo "Adult(".@$cus_v['adult'].")"."Child(".@$cus_v['child'].")"."Infant(".@$cus_v['infant'].")";?></td>
											</tr>
											<!-- <tr>
												<td style="width:50%;"><strong>Ticket No</strong></td>
												<td style="width:50%;"><?=@$cus_v['app_reference'];?></td>
											</tr> -->
											<tr>	
												<td style="width:50%;"><strong>Status</strong></td>
												<td style="width:50%;"><strong style="font-size: 14px;
    padding: 3px 10px !important;    border-radius: 4px!important;" class="<?php echo booking_status_label($cus_v['status'])?>">
										<?php
												switch ($cus_v ['status']) {
													case 'BOOKING_CONFIRMED' :
														echo 'CONFIRMED';
														break;
													case 'BOOKING_CANCELLED' :
														echo 'CANCELLED';
														break;
													case 'BOOKING_FAILED' :
														echo 'FAILED';
														break;
													case 'BOOKING_INPROGRESS' :
														echo 'INPROGRESS';
														break;
													case 'BOOKING_INCOMPLETE' :
														echo 'INCOMPLETE';
														break;
													case 'BOOKING_HOLD' :
														echo 'HOLD';
														break;
													case 'BOOKING_PENDING' :
														echo 'PENDING';
														break;
													case 'BOOKING_ERROR' :
														echo 'ERROR';
														break;
												}
												?>
										</strong></td>
											</tr>
										<?php
										}
										?>
										
									</table>
									</td>
									<td></td>
								</tr>
								<tr>
									<td>&nbsp;</td>
								</tr>
							<?php  //if(count($booking_transaction_details) == 1) { ?>
							<tr>
									<td width="100%"
										style="background:#666;color:#fff;padding: 10px; border: 1px solid #cccccc; font-size: 14px; font-weight: bold;">Price
										Summary</td>

								</tr>
								<tr>
									<td style="border: 1px solid #cccccc;" width="100%">
										<table width="100%" cellpadding="5"
											style="padding: 10px; font-size: 14px;">
											
											<tr>
												<td style="width: 50%;  white-space: nowrap;text-align:left;"><strong>Total Fee</strong></td>
													<td style="width: 50%;  white-space: nowrap;text-align:left;"><?=get_application_default_currency()." ".number_format(@$total, 2)?></td>
											</tr>
											<tr>
												<td style="width: 50%;  white-space: nowrap;text-align:left;"><strong>Convenience Fee</strong></td>
													<td style="width: 50%;  white-space: nowrap;text-align:left;"><?=get_application_default_currency()." ".number_format(@$convenience_fee, 2)?></td>
											</tr>
											<tr>
											<td style="width: 50%;  white-space: nowrap;text-align:left; "><strong>Discount</strong></td>
												<td style="width: 50%;  white-space: nowrap;text-align:left; "><?=get_application_default_currency()." ".number_format(@$discount, 2)?></td>
											</tr>
											<tr>
												
												<td style="width: 50%;  white-space: nowrap;text-align:left;"><strong>Grand Total</strong></td>
													<td style="width: 50%;  white-space: nowrap;text-align:left;"><?=get_application_default_currency()." ".number_format(($total+$convenience_fee)-$discount,2)?></td>
											</tr>
										</table>
									</td>
									<td></td>
								</tr> 
							<?php //} ?>                           
                            

							<td>&nbsp;</td>
								</tr>
								<tr>

                            	<tr>
									<td
										style="background:#666;color:#fff;padding: 10px; border: 1px solid #cccccc; font-size: 14px; font-weight: bold;">Activity Details
									</td>

								</tr>
								<tr>
									<td style="border: 1px solid #cccccc;">
										<table width="100%" cellpadding="5"
											style="padding: 10px; font-size: 14px;">
											<tr>
												<td><strong>Activity Name</strong></td>
												<td><?=@$package[0]['package_name']?></td>
											</tr>
												<!-- <td><strong>Baggage Fare</strong></td>
											<td><strong>Meals Fare</strong></td>
											<td><strong>Service Fee</strong></td> 
											<td><strong>Discount</strong></td>-->
											<tr>
												<td width="50%"><strong>Activity City</strong></td>
												<td width="50%"><?=@$package[0]['package_city']?></td>
											</tr>
											<tr>
												<td width="50%"><strong>Activity Location</strong></td>
												<td width="50%"><?=@$package[0]['package_location']?></td>
											</tr>
											<tr>
												<td width="50%"><strong>Activity Code</strong></td>
												<td width="50%"><?=@$package[0]['package_code']?></td>
												<!-- package_code -->
											</tr>
											<?php
											// debug($price); exit;
											?>
										</table>
									</td>
									<td></td>
								</tr> 
							<?php //} ?>                           
                            
							<td>&nbsp;</td>
								</tr>
								<tr>


							<tr>
									<td
										style="background:#666;color:#fff;padding: 10px; border: 1px solid #cccccc; font-size: 14px; font-weight: bold;">Activity Details
									</td>

								</tr>
								<tr>
									<td style="border: 1px solid #cccccc;">
										<table width="100%" cellpadding="5"
											style="padding: 10px; font-size: 14px;">
											<tr>
												<td><strong>Activity Description</strong></td>
											
											</tr>
											<?php
											// debug($price); exit;
											?>
											<tr>
												<td><?=@$package[0]['package_description']?></td>
											</tr>
										
										</table>
									</td>
									<td></td>
								</tr> 
							<?php //} ?>   




								<td>&nbsp;</td>
								</tr>
								<tr>
									<td width="50%"
										style="background:#666;color:#fff;padding: 10px; border: 1px solid #cccccc; font-size: 14px; font-weight: bold;">Terms
										and Conditions</td>
								</tr>
								<tr>
									<td width="100%" style="border: 1px solid #cccccc;">
										<table width="100%" cellpadding="5"
											style="padding: 10px 20px; font-size: 13px;">
											<tr>
												<td><?=$terms_condition['terms_n_conditions']?></td>
											</tr>
											

										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr><td><div class="foot_bottom" style="display:none;">      
	      <ul>      <!-- <li class="list-unstyled">      <p>Follow us on social media!</p>      </li> -->      <li class="list-unstyled"><a class="col_fb" href="https://www.facebook.com/tripmiaa"><i class="fab fa-facebook-f"></i></a></li><li class="list-unstyled"><a class="col_twt" href="https://twitter.com/tripmiaa"><i class="fab fa-twitter"></i></a></li><li class="list-unstyled"><a class="col_istg" href="https://www.instagram.com/tripmiaa"> <i class="fab fa-instagram"></i></a></li><li class="list-unstyled"><a class="col_lin" href="https://www.linkedin.com/tripmiaa"><i class="fab fa-linkedin-in"></i></a></li><li class="list-unstyled"><a class="col_lin" href="https://www.youtube.com/tripmiaa"><i class="fab fa-youtube"></i></a></li></ul>      
      </div></td></tr>
				</table>
			</td>
		</tr>
	</tbody>
</table>


<?php //} ?>
<!-- <table id="printOption"
	onclick="document.getElementById('printOption').style.visibility = ''; print(); return true;"
	style="border-collapse: collapse; font-size: 14px; margin: 10px auto; font-family: arial;"
	width="70%" cellpadding="0" cellspacing="0" border="0">
	<tbody>
		<tr>
			<td align="center"><input
				style="background: #418bca; height: 34px; padding: 10px; border-radius: 4px; border: none; color: #fff; margin: 0 2px;"
				type="button" value="Print" /></td>
		</tr>
	</tbody>
</table> -->
</div>
