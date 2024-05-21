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
.foot_bottom li a{
	    padding: 4px;
	}
</style>

<style>
@media print {
    .page-break {page-break-after: always;}
	.main-footer, .main-header, .navbar, .main-sidebar, .print_btn_area, .al_btn, header, footer, .modal {display: none;	}
	table {width: 100%;	}
	p {margin-bottom: 5px;}
	.fldealsec { display: none; }
    #user_feedback_form{
    	display: none !important;
    }
    #printOption{
    	display: none !important;
    }
}

</style>

<?php
// error_reporting(0);
// debug($details);exit;
// debug($package_details); exit;
// $booking_details = $data ['booking_details'] [0];

$logo = $GLOBALS['CI']->template->domain_images($logo);
// debug($details);exit;
$itinerary_details = $details ['passenger_details'];

$attributes = $details ['booking_details'];

$price = $details['transaction_details'];

$package = $details['package_details'];
// debug($attributes);exit;

?>
<div style="clear:both"></div>
<!-- <div class="print_btn_area" style="width: 100%; max-width: 900px;text-align:center;clear:both; margin: 10px auto;margin-top: 0;padding-top: 12px;">
	<button class="btn-sm btn-primary print" onclick="w=window.open();w.document.write(document.getElementById('tickect_hotel').innerHTML);w.print();w.close(); return true;">Print</button>
</div> -->
<div class="table-responsive" style="margin-top:20px;">
<table style="border-collapse: collapse;background: #f5f5f5;border: 15px solid #fff;font-size: 13px;line-height: 18px;margin: 15px auto;margin-top:0;font-family: arial;max-width: 900px;"
	width="100%" cellpadding="0" cellspacing="0" border="0">
	<tbody>
		<tr>
			<td style="border-collapse: collapse; ">
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
													<!-- <p style="margin-bottom: 6px;font-size:14px;font-weight: 500;line-height: 19px;">Tripmia.com.au<br>
ABN# 21615437002<br>
PO Box 5034<br>
Kingsdene NSW 2118<br>
61879792323<br>
contact@tripmia.com.au</p> -->
												</td>

												<td style="width: 60%" align="right">
												<img style="width: 265px;height: 100px;"
													src="<?= $logo ?>">
												</td>
												
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td width="50%"
										style="padding: 10px; border: 1px solid #cccccc; font-size: 14px; font-weight: bold;">Reservation
										Lookup</td>

								</tr>
								<tr>
									<td style="border: 1px solid #cccccc;">
										<table width="100%" cellpadding="5"
											style="padding: 10px; font-size: 14px;">
											<tr>
												<td style="width: 50%;"><strong>Booking Reference</strong></td>
												<td  style="width: 50%;"><?=@$attributes[0]['app_reference']?></td>
											</tr>
												<!-- <td><strong>Booking ID</strong></td>
												<td><strong>PNR</strong></td> -->
											<tr>
												<td><strong>Booked On</strong></td>
												<td><?=app_friendly_absolute_date(@$attributes[0]['created_datetime'])?></td>
											</tr>
											<tr>
												<td  style="width: 50%;"><strong>Travelling Date</strong></td>
												<td  style="width: 50%;"><?=app_friendly_absolute_date(@$attributes[0]['date_of_travel'])?></td>
											</tr>
											<tr>
												<td  style="width: 50%;"><strong>Status</strong></td>
												<td  style="width: 50%;"><strong
													class="<?php echo $details['status'];?>"
													style="font-size: 14px; color: #28b700">
											<?php
											switch ($details['status']) {
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
								<tr>
									<td>&nbsp;</td>
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
									<td
										style="padding: 10px; border: 1px solid #cccccc; font-size: 14px; font-weight: bold;">Travelers
										Information</td>

								</tr>
								<tr>
									<td style="border: 1px solid #cccccc;">
										<table width="100%" cellpadding="5"
											style="padding: 10px; font-size: 14px;">
											<tr>
												<td style="width: 50%;"><strong>Lead Passenger Name</strong></td>
												 <td style="width: 50%;"><?php echo $itinerary_details[0]['first_name'].'  '.$itinerary_details[0]['last_name'];?></td>
												</tr>
												<tr>
												<td  style="width: 50%;"><strong>Count Of Sub Passanger</strong></td>
												<td  style="width: 50%;"><?php echo "Adult(".@$itinerary_details[0]['adult'].")"."Child(".@$itinerary_details[0]['child'].")"."Infant(".@$itinerary_details[0]['infant'].")";?></td>
												</tr>
												<tr>
												<td   style="width: 50%;"><strong>Ticket No</strong></td>
												<td   style="width: 50%;">
													
													<?=@$attributes[0]['app_reference']?>
												</td>
												</tr>
												<!-- <tr>
												<td   style="width: 50%;"><strong>Status</strong></td>
													<td><strong
													class="<?php echo $details['status']?>">
										<?php
												switch ($details['status']) {
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
											</tr> -->
									 <?php
										// echo $cus_v['passenger_type'].
										$cus_v = $itinerary_details[0];

										// debug($cus_v);die;
										if (isset ( $cus_v )) {
										?>
										<tr>
										
		                                
		                                
										 
											
											</tr>
										<?php
										}
										else
										{

?>
										<tr>
										
		                                 <td style="width: 50%;"><?php echo $cus_v['passenger_type'].'-'.$cus_v['first_name'].'  '.$cus_v['last_name'];?></td>
		                                <td><?php echo "Adult(".@$cus_v['adult'].")"."Child(".@$cus_v['child'].")"."Infant(".@$cus_v['infant'].")";?></td>
										 <td><?=@$cus_v['app_reference'];?></td>
												<td><strong
													class="<?php echo booking_status_label( @$attributes[0]['status'])?>">
										<?php
												switch (@$attributes[0]['status']) {
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
									<td
										style="padding: 10px; border: 1px solid #cccccc; font-size: 14px; font-weight: bold;">Price Summary</td>

								</tr>
								<tr>
									<td style="border: 1px solid #cccccc;">
										<table width="100%" cellpadding="5"
											style="padding: 10px; font-size: 14px;">
											<tr>
												<td style="width: 50%; white-space: nowrap;text-align:left;"><strong>Base Fare</strong></td>
													<?php if((isset($price[0]['discount'])) && ($price[0]['discount'] != "") && ($price[0]['discount'] > 0)){ ?>
												<td style="width: 50%;  white-space: nowrap;text-align:left;"><?=@$price[0]['currency']?> <?=number_format((@$price[0]['total_fare'] + @$price[0]['discount']), 2)?></td>
											<?php } else { ?>
												<td style="width: 50%;  white-space: nowrap;text-align:left;"><?=@$price[0]['currency']?> <?=number_format(@$price[0]['total_fare'], 2)?></td>
											<?php } ?>
											</tr>
												<!-- <td><strong>Baggage Fare</strong></td>
											<td><strong>Meals Fare</strong></td>
											<td><strong>Service Fee</strong></td> 
											-->
											<tr>
											<td style="width: 50%;  white-space: nowrap;text-align:left; "><strong>Discount</strong></td>
												<td style="width: 50%;  white-space: nowrap;text-align:left; "><?=@$price[0]['currency']?> <?=number_format(@$price[0]['discount'], 2)?></td>
											</tr>
											<tr>
												<td style="width: 50%;  white-space: nowrap;text-align:left;"><strong>Total Fee</strong></td>
													<td style="width: 50%;  white-space: nowrap;text-align:left;"><?=@$price[0]['currency']?> <?=number_format(@$price[0]['total_fare'], 2)?></td>
											</tr>
											<?php
											// debug($price); exit;
											?>
											
											<tr style="font-size: 15px;">
												<td style="width: 50%;  white-space: nowrap;text-align:left;"><strong>Total Fare</strong></td>
												<td style="width: 50%;  white-space: nowrap;text-align:left;"><strong><?=@$price[0]['currency']?> <?=number_format(@$price[0]['total_fare'], 2)?></strong></td>
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
									<td style="padding: 10px; border: 1px solid #cccccc; font-size: 14px; font-weight: bold;">Activity Details
									</td>

								</tr>
								<tr>
									<td style="border: 1px solid #cccccc;">
										<table width="100%" cellpadding="5"
											style="padding: 10px; font-size: 14px;">
											<tr>
												<td style="width: 50%; white-space: nowrap;text-align:left;"><strong>Activity Name</strong></td>
												<td style="width: 50%; white-space: nowrap;text-align:left;"><?=@$package[0]['package_name']?></td>
												</tr>
												<!-- <td><strong>Baggage Fare</strong></td>
											<td><strong>Meals Fare</strong></td>
											<td><strong>Service Fee</strong></td> 
											<td><strong>Discount</strong></td>-->
											<tr>
												<td style="width: 50%; white-space: nowrap;text-align:left;"><strong>Activity City</strong></td>
												<td style="width: 50%; white-space: nowrap;text-align:left;"><?=@$package[0]['package_city']?></td>
											</tr>
											<tr>
												<td style="width: 50%; white-space: nowrap;text-align:left;"><strong>Activity Location</strong></td>
												<td style="width: 50%; white-space: nowrap;text-align:left;"><?=@$package[0]['package_location']?></td>
											</tr>
											<tr>
												<td style="width: 50%; white-space: nowrap;text-align:left;"><strong>Activity Code</strong></td>
												<td style="width: 50%; white-space: nowrap;text-align:left;"><?=@$package[0]['package_code']?></td>
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
										style="padding: 10px; border: 1px solid #cccccc; font-size: 14px; font-weight: bold;">Activity Details
									</td>

								</tr>
								<tr>
									<td style="border: 1px solid #cccccc;">
										<table width="100%" cellpadding="5"
											style="padding: 10px; font-size: 14px;">
											<tr>
												<td><strong>Activity Description</strong></td>
											</tr>
											<tr>
												<td><?=@$package[0]['package_description']?></td>
											</tr>
											<?php
											// debug($price); exit;
											?>
											<tr>
												
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
										style="padding: 10px; border: 1px solid #cccccc; font-size: 14px; font-weight: bold;">Terms
										and Conditions</td>
								</tr>
								<tr>
									<td width="100%" style="border: 1px solid #cccccc;">
										<table width="100%" cellpadding="5"
											style="padding: 10px 20px; font-size: 13px;">
											<tr>
												<td>1.A printed copy of this e-ticket display on
													laptop,tablet or phone must be presented at a time of
													checking.</td>
											</tr>
											<tr>
												<td>2.Check-in starts 2 hours before scheduled departure,
													and closes 60 minutes prior to departure time.We recommend
													you report at check-in counter at least 2 hours prior to
													departure time.</td>
											</tr>
											<tr>
												<td>3.It is mandatory to carry Government recognised photo
													identification along with your E-ticket.This can be
													include:Driving License, Passport, Pan Card, Voter Id Card or
													any other ID issued by Government of India. for infant
													passengers it is mandatory to carry the Date Of Birth
													certificate.</td>
											</tr>

										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr style="display:none;">
<td>
<div class="foot_bottom">
      
	      	<ul>
	      	<!-- 	<li class="list-unstyled">
	      			<p><?php echo $this->lang->line('follow_us_on_social_media');?></p>
	      		</li> -->

	      		<?php
						$temp = $this->custom_db->single_table_records ( 'social_links' );
						//debug($temp);
						if ($temp ['data'] ['0'] ['status'] == ACTIVE) {
				?>
						<li class="list-unstyled"><a class="col_fb" href="<?php echo $temp['data']['0']['url_link'];?>"
						><i class="fab fa-facebook-f"></i></a></li>

				<?php } if ($temp ['data'] ['1'] ['status'] == ACTIVE) { ?>
						<li class="list-unstyled"><a class="col_twt" href="<?php echo $temp['data']['1']['url_link'];?>"
						><i class="fab fa-twitter"></i></a></li>
					
						<?php } if ($temp['data']['2']['status'] == ACTIVE) {?>
						<li class="list-unstyled"><a class="col_istg" href="<?php echo $temp['data']['2']['url_link'];?>"
						> <i class="fab fa-instagram"></i></a></li>

						<?php } if ($temp['data']['4']['status'] == ACTIVE) {?>
						<li class="list-unstyled"><a class="col_lin" href="<?php echo $temp['data']['4']['url_link'];?>"
						><i class="fab fa-linkedin-in"></i></a></li>
						<?php } if ($temp['data']['3']['status'] == ACTIVE) {?>
						<li class="list-unstyled"><a class="col_lin" href="<?php echo $temp['data']['3']['url_link'];?>"
						><i class="fab fa-youtube"></i></a></li>
						<?php } ?>
			</ul>
      
      </div>
</td>
		</tr>
	</tbody>
</table>


<?php //} ?>
<table id="printOption"
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
</table>
</div>
