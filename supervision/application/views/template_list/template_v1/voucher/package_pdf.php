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
$logo = $GLOBALS['CI']->template->domain_images($logo);
$itinerary_details = $details ['passenger_details'];
$price = $details['transaction_details'];
$attributes = $details ['booking_details'];
$price_attribute = json_decode($price[0]['attributes'],true);
$convenience_fee = $price_attribute['convenience_fee'];
$total = $price_attribute['Fare'];
$price = $details['transaction_details'];

// debug(get_application_default_currency());
// debug($price[0]['currency']);
$currency_obj_custom = new Currency(array('module_type' => 'sightseeing', 'from' =>get_application_default_currency() , 'to' =>$price[0]['currency']));

$price[0]['discount'] = isset($price[0]['discount'])? get_converted_currency_value ( $currency_obj_custom->force_currency_conversion ( $price[0]['discount']) ):0;

$package = $details['package_details'];
// debug($attributes);exit;
$currency_obj = new Currency(array('module_type' => 'sightseeing', 'from' => $price[0]['currency'], 'to' =>get_application_default_currency()));


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
												</td><td style="text-align: right;width: 60%">

															
<?php $user_type=$attributes[0]['user_type'];

?>

<img
            style="width:150px;"
            src="<?php
                          if($user_type==3 && !empty($b2b_logo))
                          {
                           echo "https://www.travelsoho.com/travel-free-travels/extras/custom/TMX1512291534825461/images/TMX1512291534825461logo-loginpg.png";     
                          }
                          else
                          {
                            echo base_url()."../../".$GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template->get_domain_logo());  
                          }
                          
                          
                          ?>"
            alt="" />





												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td width="100%"
										style="color:#fff; background-color:#666;padding: 10px; border: 1px solid #cccccc; font-size: 14px; font-weight: bold;">Reservation
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
											
										</table>
									</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
								</tr>
								
								
									<tr>
									<td width="100%"
										style="background-color:#666;color:#fff;padding: 10px; border: 1px solid #cccccc; font-size: 14px; font-weight: bold;">Travelers
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
												$booking_status = $details['transaction_details'][0]['status'];
												// switch ($cus_v ['status']) {
												switch ($booking_status) {
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
									
								</tr>
								<tr>
									<td>&nbsp;</td>
								</tr>
							<tr>
								<tr>
									<td width="100%"
										style="background-color:#666;color:#000;padding: 10px; border: 1px solid #cccccc; font-size: 14px; font-weight: bold;">Price
										Summary</td>

								</tr>
								<tr>
								<td style="border: 1px solid #cccccc;" width="100%">
										<table width="100%" cellpadding="5"
											style="padding: 10px; font-size: 14px;">
											<?php if($user_type!=3){ ?>
											<tr>
												<td style="width: 50%;  white-space: nowrap;text-align:left;"><strong>Total Fee</strong></td>
													<td style="width: 50%;  white-space: nowrap;text-align:left;"><?=@$price[0]['currency']?> <?=number_format(@$total, 2)?></td>
											</tr>
											<tr>
												<td style="width: 50%;  white-space: nowrap;text-align:left;"><strong>Convenience Fee</strong></td>
													<td style="width: 50%;  white-space: nowrap;text-align:left;"><?=@$price[0]['currency']?> <?=number_format(@$convenience_fee, 2)?></td>
											</tr>
											<tr>
											<td style="width: 50%;  white-space: nowrap;text-align:left; "><strong>Discount</strong></td>
												<td style="width: 50%;  white-space: nowrap;text-align:left; "><?=$price[0]['currency']." ".number_format($price[0]['discount'],2)?>
													

												</td>
											</tr>
										<?php }?>
											<tr>
												
												<td style="width: 50%;  white-space: nowrap;text-align:left;"><strong>Grand Total</strong></td>
													<td style="width: 50%;  white-space: nowrap;text-align:left;"><?=@$price[0]['currency']?> <?=number_format($total+$convenience_fee - $price[0]['discount'] ,2)?></td>
											</tr>
										</table>
									</td>
									<td></td>
								</tr>  
						                         
                            
									<td>&nbsp;</td>
								</tr>
								<tr>
                                        	<?php 
									//debug($attributes);exit();

									$module_name = ($attributes[0]['module_type']=='transfers')?"Transfer":"Activity"; ?>
								<tr style="background-color:#666;">
									<td
										style="background-color:#666;color:#000;padding: 10px; border: 1px solid #cccccc; font-size: 14px; font-weight: bold;"><?=$module_name?> Details
									</td>

								</tr>
							<tr>
									<td style="border: 1px solid #cccccc;">
										<table width="100%" cellpadding="5"
											style="padding: 10px; font-size: 14px; border: solid 1px #e3e3e3;">
											<tr>
												<td><strong><?=$module_name?> Name</strong></td>
												<td><?=@$package[0]['package_name']?></td>
											</tr>
												<!-- <td><strong>Baggage Fare</strong></td>
											<td><strong>Meals Fare</strong></td>
											<td><strong>Service Fee</strong></td> 
											<td><strong>Discount</strong></td>-->
											<tr>
												<td width="50%"><strong><?=$module_name?> City</strong></td>
												<td width="50%"><?=@$package[0]['package_city']?></td>
											</tr>
											<!-- <tr>
												<td width="50%"><strong><?=$module_name?> Location</strong></td>
												<td width="50%"><?=@$package[0]['package_location']?></td>
											</tr> -->
											<tr>
												<td width="50%"><strong><?=$module_name?> Code</strong></td>
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
										style="background:#666;color:#000;padding: 10px; border: 1px solid #cccccc; font-size: 14px; font-weight: bold;"><?=$module_name?> Description
									</td>

								</tr>
							
								<tr>
									<td style="border: 1px solid #cccccc;">
										<table width="100%" cellpadding="5"
											style="padding: 10px; font-size: 14px;">
											
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
								<?php if($terms_condition['terms_n_conditions']){ ?>
									<tr>
									<td width="50%"
										style="background-color:#666;color:#000;padding: 10px; border: 1px solid #cccccc; font-size: 14px; font-weight: bold;">Terms
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
							<?php } ?>
							</table>
						</td>
					</tr>
					 <?php 
                    if($booking_status!='BOOKING_CANCELLED'){
					 if($email_status){ ?>

					 <!-- <tr><td><a href="<?=base_url()?>index.php/report/crs_cancel/<?=$details['booking_details'][0]['app_reference']?>/<?=$details['booking_details'][0]['booking_source']?>" class="viwedetsb">Cancel</a></td></tr> -->
                    
                    <?php }}
                     ?>
                     				

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
