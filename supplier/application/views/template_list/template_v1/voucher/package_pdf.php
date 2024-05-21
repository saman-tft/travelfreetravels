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
error_reporting(0);
// debug($details);exit;

// $booking_details = $data ['booking_details'] [0];
// debug($logo); exit;
$logo =$GLOBALS['CI']->template->domain_images($logo[0]['domain_logo']);
 //$logo = 'http://'.$_SERVER['HTTP_HOST'].$GLOBALS['CI']->template->domain_images($booking_details['domain_logo']);
// debug($booking_details);exit;
$itinerary_details = $details ['passenger_details'];

$attributes = $details ['booking_details'];

$price = $details['transaction_details'];
// debug($itinerary_details);exit;

$package = $details['package_details'];

?>
<div class="table-responsive">
<table
	style="border-collapse: collapse; background: #ffffff; font-size: 14px; margin: 0 auto; font-family: arial;"
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
												<td style="width: 60%">

        
            <img style="width: 100px;height:75px!important;" src="<?php
													if($user_type ==3 && !empty($b2b_logo))
													{
													 echo base_url()."../../".$GLOBALS['CI']->template->domain_images($b2b_logo);     
													}
													else
													{
													  echo base_url()."../../".$GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template->get_domain_logo());  
													}
													
													
													?>">
												</td>
												<td style="width: 40%">
													<table width="100%"
														style="border-collapse: collapse; text-align: right; line-height: 15px;"
														cellpadding="0" cellspacing="0" border="0">
														<tr>
															<td style="font-size: 14px;"><span
																style="width: 100%; float: left"><?php echo $address;?></span>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td width="100%"
										style="padding: 10px; border: 1px solid #cccccc; font-size: 14px; font-weight: bold;">Reservation
										Lookupasd</td>

								</tr>
								<tr>
									<td style="border: 1px solid #cccccc;">
										<table width="100%" cellpadding="5"
											style="padding: 10px; font-size: 14px;">
											<tr>
												<td><strong>Booking Reference</strong></td>
												<!-- <td><strong>Booking ID</strong></td>
												<td><strong>PNR</strong></td> -->
												<td><strong>Booked On</strong></td>
												<td><strong>Travelling Date</strong></td>
												<td><strong>Status</strong></td>
											</tr>
											<tr>

												<td><?=@$attributes[0]['app_reference']?></td>
												<!-- <td><?=@$booking_transaction_details[0]['book_id']?></td>
												<td><?=@$booking_transaction_details[0]['pnr']?></td> -->
												<td><?=app_friendly_absolute_date(@$attributes[0]['created_datetime'])?></td>
												<td><?=app_friendly_absolute_date(@$attributes[0]['date_of_travel'])?></td>
												<td><strong
												<?php $attributes[0]['status'] = $attributes[0]['status'] == 'CANCELLED' ? 'BOOKING_CANCELLED' : $attributes[0]['status']  ?>
													class="<?php echo booking_status_label( @$attributes[0]['status']);?>"
													style="font-size: 14px; color: #28b700">
											<?php
											switch (@$attributes[0] ['status']) {
												case 'BOOKING_CONFIRMED' :
													echo 'CONFIRMED';
													break;
												case 'BOOKING_CANCELLED' :
													echo 'CANCELLED';
													break;
													case 'CANCELLED' :
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
												<td><strong>Lead Passenger Name</strong></td>
												<td><strong>Count Of Sub Passanger</strong></td>
												<td><strong>Ticket No</strong></td>
												<td><strong>Status</strong></td>
											</tr>
									 <?php
										
										// debug($itinerary_details); exit;
										$cus_v = $itinerary_details[0];
										if (isset ( $cus_v )) {
											// foreach ( $itinerary_details as $cus_k => $cus_v ) {
												
												?>
										<tr>
										
		                                 <td><?php echo $cus_v['passenger_type'].'-'.$cus_v['first_name'].'  '.$cus_v['last_name'];?></td>
		                                <td><?php echo "Adult(".@$cus_v['adult'].")"."Child(".@$cus_v['child'].")"."Infant(".@$cus_v['infant'].")";?></td>
		                                
										 <td><?=@$cus_v['app_reference'];?></td>
												<td><strong
													class="<?php echo booking_status_label($cus_v['status'])?>">
										<?php
												switch ($attributes[0] ['status']) {
													case 'BOOKING_CONFIRMED' :
														echo 'CONFIRMED';
														break;
													case 'BOOKING_CANCELLED' :
														echo 'CANCELLED';
														break;
													case 'CANCELLED' :
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
											
//}
										}
										// } ?>
									</table>
									</td>
									<!-- <td></td> -->
								</tr>
								<tr>
									<td>&nbsp;</td>
								</tr>
							<?php  //if(count($booking_transaction_details) == 1) { ?>
							<tr>
									<td
										style="padding: 10px; border: 1px solid #cccccc; font-size: 14px; font-weight: bold;">Price
										Summary</td>

								</tr>
								<tr>
									<td style="border: 1px solid #cccccc;">
										<table width="100%" cellpadding="5"
											style="padding: 10px; font-size: 14px;">
											<tr>
												<td><strong>Base Fare</strong></td>
												<!-- <td><strong>Baggage Fare</strong></td>
											<td><strong>Meals Fare</strong></td>
											<td><strong>Service Fee</strong></td> 
											-->
											<td><strong>Discount</strong></td>
												<td><strong>Total Fee</strong></td>
											</tr>
											<?php
											// debug($price); exit;
											?>
											<tr>
											<?php if((isset($price[0]['discount'])) && ($price[0]['discount'] != "") && ($price[0]['discount'] > 0)){ ?>
											<td><?=@$price[0]['currency']?>  <?=number_format((@$price[0]['total_fare'] + @$price[0]['discount']), 2)?></td>
											<?php } else { ?>
											<td><?=@$price[0]['currency']?>  <?=number_format(@$price[0]['total_fare'], 2)?></td>
											<?php } ?>
												<td><?=@$price[0]['currency']?>  <?=number_format(@$price[0]['discount'], 2)?></td>
												<!--<td>0</td>
											<td>0</td>
											<td>375</td>
											<td>0</td>-->
												<td><?=@$price[0]['currency']?>  <?=number_format(@$price[0]['total_fare'], 2)?></td>
											</tr>
											
										</table>
									</td>
									<!-- <td></td> -->
								</tr> 
							<?php //} ?>                           
                            <tr>
									<td>&nbsp;</td>
								</tr>
								<tr>

									<tr>
									<td
										style="padding: 10px; border: 1px solid #cccccc; font-size: 14px; font-weight: bold;">Package Details
									</td>

								</tr>
								<tr>
									<td style="border: 1px solid #cccccc;">
										<table width="100%" cellpadding="5"
											style="padding: 10px; font-size: 14px;">
											<tr>
												<td><strong>Package Name</strong></td>
												<!-- <td><strong>Baggage Fare</strong></td>
											<td><strong>Meals Fare</strong></td>
											<td><strong>Service Fee</strong></td> 
											<td><strong>Discount</strong></td>-->
												<td><strong>Package City</strong></td>
												<td><strong>Package Location</strong></td>
												<td><strong>Package Code</strong></td>
												<!-- package_code -->
											</tr>
											<?php
											// debug($price); exit;
											?>
											<tr>
												<td><?=@$package[0]['package_name']?></td>
												
												<td><?=@$package[0]['package_city']?></td>

												<td><?=@$package[0]['package_location']?></td>

												<td><?=@$package[0]['package_code']?></td>
											</tr>
										
										</table>
									</td>
									<!-- <td></td> -->
								</tr> 
							<?php //} ?>                           
                            
							<td>&nbsp;</td>
								</tr>
								<tr>


							<tr>
									<td
										style="padding: 10px; border: 1px solid #cccccc; font-size: 14px; font-weight: bold;">Package Details
									</td>

								</tr>
								<tr>
									<td style="border: 1px solid #cccccc;">
										<table width="100%" cellpadding="5"
											style="padding: 10px; font-size: 14px;">
											<tr>
												<td><strong>Package Description</strong></td>
											
											</tr>
											<?php
											// debug($price); exit;
											?>
											<tr>
												<td><?=@$package[0]['package_description']?></td>
											</tr>
										
										</table>
									</td>
									<!-- <td></td> -->
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
