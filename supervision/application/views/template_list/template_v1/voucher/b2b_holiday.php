<style>
.page-break {clear: both;margin-bottom: 20px;}
.print_btn_area {text-align: center;margin-bottom: 20px;}
th,td{padding: 5px;}
@media print {
	.page-break {page-break-after: always;}
	.main-footer, .main-header, .navbar, .main-sidebar, .print_btn_area {display: none;	}
	table {width: 100%;	}
	p {margin-bottom: 5px;}	
}
</style>

<?php


$holiday_data=$voucher_data;
$domain_data=$data;
$package_details=$package_details;

$attributes=json_decode($holiday_data['data'][0]['attributes'],1);
// debug($holiday_data);
// debug($attributes);
// debug($domain_data);
// debug($package_details);
// die;
// die;
$app_reference = $holiday_data['data'][0]['app_reference'];
//$booking_details = $data ['booking_details'] [0];
// debug($cancel_details);
// die;
 


?>


<div class="print_btn_area" style="width: 100%; max-width: 900px; margin: 10px auto">
	<div style="display: none; margin: 3px 10px;">
		<div class="squaredThree">
			<input id="btn-togglr-address" type="checkbox" name="tc"> <label
				for="btn-togglr-address"></label>
		</div>
		<label for="btn-togglr-address"> Hide Address </label>
	</div>

	<div style="display: none; margin: 3px 10px;">
		<div class="squaredThree">
			<input id="btn-togglr-fare" type="checkbox" name="tc"> <label
				for="btn-togglr-fare"></label>
		</div>
		<label for="btn-togglr-fare"> Hide Fare </label>
	</div>

	<!-- <button class="btn-sm btn-primary print" onclick="w=window.open();w.document.write(document.getElementById('tickect_hotel').innerHTML);w.print();w.close(); return true;">Print</button>
	<a href=""><button type="button"
			class="btn-sm btn-primary btn-popup bnt_orange"
			data-toggle="collapse" data-target="#emailmodel"
			aria-expanded="false" aria-controls="markup_update">Email</button></a> -->
	

	 <!-- <a href="<?php echo base_url () . 'index.php/voucher/holiday/'.$booking_details['app_reference'].'/'.$booking_details['status'].'/show_pdf';?>"  ><button class="btn-sm btn-primary pdf">PDF</button></a> -->

        <?php 
        if(is_logged_in_user()){ ?>
			<!-- <a href="<?=base_url().'index.php/report/hotel'?>"><button type="button"
			class="btn-sm btn-primary pdf">Back</button></a> -->
			<?php } ?>

	<!-- <button class="btn-sm btn-primary pdf">PDF</button> -->
	<div class="collapse" id="emailmodel">
<!-- 		<div class="well">
	<h4>Send Email</h4>
	<form name="agent_email" method="post"
		action="<?php echo base_url () . 'index.php/voucher/hotel/'.$booking_details['app_reference'].'/'.$booking_details['booking_source'].'/'.$booking_details['status'].'/email_voucher';?>"
		>
		<input id="inc_sddress" value="1" type="hidden" name="inc_sddress">
		<input id="inc_fare" value="1" type="hidden" name="inc_fare">
		<div class="row">
		<div class="col-xs-4">
			<label style="font-size:14px; font-weight:500">Email Id &nbsp; </label>
			</div>
		<div class="col-xs-6">
			<input id="email"
				placeholder="Please Enter Email Id"
				class="airlinecheckbox form-control validate_user_register" type="text" checked
				name="email"> &nbsp; 
		</div>
		<div class="col-xs-2">
				<button type="submit" class="btn btn-primary" value="Submit">Send Email</button>
		</div>
		</div>
		
	</form>
</div> -->

			<div class="well max_wdth e_maill">
				<h4>Send Email</h4>
				<form name="agent_email" method="post"
					action="<?php echo base_url () . 'index.php/voucher/hotel/'.$booking_details['app_reference'].'/'.$booking_details['booking_source'].'/'.$booking_details['status'].'/email_voucher';?>"
					>
					<input id="inc_sddress" value="1" type="hidden" name="inc_sddress">
					<input id="inc_fare" value="1" type="hidden" name="inc_fare">
					<div class="row">
						<label class="wdt34">Email Id </label>
						<input id="voucher_email" placeholder="Please Enter Email Id"
							class="airlinecheckbox validate_user_register form-control wdt66" type="text" checked
							name="email" required="true">
					</div>
					<div class="text-center mt10">
						<button type="submit" class="btn btn-primary" value="Submit">Send
							Email</button>
					</div>
				</form>
			</div>
	</div>
</div>


<div class="table-responsive" id="tickect_hotel" style="background-color: #fff;">
	
	<table class="table" style="border-collapse: collapse; background: #f5f5f5; border: 15px solid #fff; font-size: 13px; line-height: 18px; margin:0 auto 15px; font-family: arial; max-width:900px;    box-shadow: 1px 1px 4px 3px #ddd;"
	width="100%" cellpadding="0" cellspacing="0" border="0">
		<tbody>
		
     <tr style="display: none">
      <td bgcolor="#ffffff" align="center" style="-webkit-text-size-adjust: none;border-collapse: collapse"><table width="900" cellspacing="0" cellpadding="0" border="0" style="border-collapse: collapse;mso-table-lspace: 0;mso-table-rspace: 0">
          <tbody>
            <tr>
              <td style="padding: 0 0px 0 0px;-webkit-text-size-adjust: none;border-collapse: collapse"><table width="100%;" style="border-collapse: collapse;mso-table-lspace: 0;mso-table-rspace: 0">
                  <tbody>
                    <tr>
                      <td style="background: #739417;color: #fff;display: block;font-size: 14px;text-align: center;overflow: hidden;padding: 10px 10px 10px 10px;-webkit-text-size-adjust: none;text-transform:uppercase;border-collapse: collapse;-webkit-print-color-adjust:exact;"> Please present this voucher to the local representative </td>
                    </tr>
                  </tbody>
                </table></td>
            </tr>
          </tbody>
        </table></td>
    </tr>
			<tr>
				<td style="border-collapse: collapse; ">
					<table width="100%" style="border-collapse: collapse;"
						cellpadding="0" cellspacing="0" border="0">

						<tr>
							<td>
								<table cellpadding="0" cellspacing="0" border="0" width="100%"
									style="border-collapse: collapse;">
							

							<tr>
					<td colspan="4" style="padding: 5px 14px;line-height: 17px;" class="logocvr">
					<p style="margin-bottom: 6px;font-size:14px;font-weight: 500;line-height: 19px;">Vivance Travels<br>
<?php
echo $domain_data['address'];
?>
</p>
					</td>
					<td colspan="4" style="padding:5px 10px;" align="right" class="logocvr"><img
						style="max-width: 265px; height: 100px; margin-top: 0px;"
						src="<?=$GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template->get_domain_logo())?>"
						alt="" /></td>
				           </tr>

		           <tr>
				<td colspan="4" style="padding: 5px 10px; color: #fff; line-height: 17px;" class="logocvr">
				&nbsp;
				</td>
			     </tr>

			<tr>
				<td colspan="4" style="padding: 5px 14px; width: 65%; color: #374c5d; line-height: 17px;" class="logocvr">
				 <span style="padding: 8px 0px; font-size: 14px;">Holiday booking reference number:</span>
				 <h4 style="font-weight: bold; font-size: 17px;"><?php echo $app_reference; ?></h4>
				</td>
				<td colspan="4" style="padding:5px 0px;" align="right" class="logocvr">
					<h4 style="padding:11px 14px; background: #f89e2e; color: #fff; margin-top: 20px; line-height: 20px; text-align: left;">Please present this voucher to the service provider.</h4>
				</td>
			</tr>
			</table></td></tr>
			<tr>
			<td>
	<table width="100%" style="border-collapse: collapse;"
						cellpadding="0" cellspacing="0" border="0">

						<tr>
							<td>
					<?php 
						if($holiday_data['data'][0]['status'] =="BOOKING_CONFIRMED"){
					?>
			        <tr>
						<td colspan="" align="left" class="logocvr">
							<h4 style=" color: #374c5d; font-size: 20px; margin: 0px; line-height: 20px; text-align: left; font-weight: bold;
                                       ">BOOKING CONFIRMATION</h4>
						</td>
					</tr>


			        <tr>
						<td  style="padding: 10px;border: 1px solid #cccccc; font-size: 13px; font-weight: bold;">Holiday Voucher</td>
						</tr>
						<tr>
<td style="border: 1px solid #cccccc;"><table width="100%" cellpadding="5" style="padding: 10px;font-size: 13px;"><tbody> <tr><td style="font-size:13px; font-weight:normal;line-height:18px;">
                                      <span style="font-size: 14px;">Booking <?php
											switch ($holiday_data['data'][0]['status']) {
												case 'BOOKING_CONFIRMED' :
													echo 'Confirmed';
													break;
												case 'BOOKING_CANCELLED' :
													echo 'Cancelled';
													break;
												case 'BOOKING_FAILED' :
													echo 'Failed';
													break;
												case 'BOOKING_INPROGRESS' :
													echo 'Inprogress';
													break;
												case 'BOOKING_INCOMPLETE' :
													echo 'Incomplete';
													break;
												case 'BOOKING_HOLD' :
													echo 'Hold';
													break;
												case 'BOOKING_PENDING' :
													echo 'Pending';
													break;
												case 'BOOKING_ERROR' :
													echo 'Error';
													break;
											}
											
											?>  and guaranteed</span> 
						</td></tr></tbody></table>
						</td>
					</tr>


					<?php
						}
					?>	

					
					<!-- <tr>
						<td colspan="8" align="left" class="logocvr" style="border-bottom: 2px solid #fff; padding: 14px 14px; color: #374c5d; ">
							
						<span style="text-align: left; font-size: 17px; font-weight: bold;">Tripmia.com Reservation Code</span>		
						<h4 style="font-size: 14px;"><?php echo $booking_details['airliner_booking_reference']; ?></h4>			
						</td>
					</tr> -->




									<?php
									if($hotel_static_info['accomodation_type_code'] == 'APTHOTEL'){ 
										$hd['accomodation_type_code'] = "HOTEL APARTMENT";
									}else if($hd['accomodation_type'] == 'APART'){
										$hd['accomodation_type_code'] = "APARTMENT";
									}else{
										$hd['accomodation_type_code'] = $hd['accomodation_type'];
									}
									?>
<tr><td>&nbsp;</td></tr>
									<tr>
										<td colspan="6" style=";margin-top: 0px;width: 100%;font-size: 14px;color: #374c5d;line-height: 20px;padding: 0">
										<h3 style="margin:0px; margin-bottom: 4px; font-weight: bold; font-size: 21px;"><?php echo $package_details[0]['package_name']; ?></h3>

										

                                        <table style="color: #374c5d; font-size: 13px; width: 100%;">
                                        <tr>
                                       
                                        
										 <!-- <tr><td>&nbsp;</td></tr> -->
                                        <tr>
                                        <td style="width:50%;padding: 10px;border: 1px solid #cccccc; font-size: 13px; font-weight: bold;">Lead guest</td> 
                                       
										 <td style="width:50%;padding: 10px;border: 1px solid #cccccc; font-size: 13px; font-weight: bold;">
                                           
										 <?php 
										 //debug($pax_details);exit(); 
										 $customer_title = $attributes['name_title'];
										  $customer_first_name = $attributes['first_name'];
										    $customer_last_name = $attributes['last_name'];
										 $title = get_enum_list('title',$customer_title[0]);

										 echo $title.' '.$customer_first_name[0].' '.$customer_last_name[0]
                                            ?>
                                            	
                                            </td>

									    </tr>	
									    <?php
									    if(count($customer_first_name)>1)
									    {
									    ?>
									    <tr>
                                        <td style="padding: 3px 0px; font-weight: bold;">Other guest(s)</td> 
                                       
										<td style="padding: 3px 0px;">
                                            <?php 

                                            for ($ps=0; $ps < count($customer_first_name) ; $ps++) { 

                                            	if($ps >=1){
                                            		$title = get_enum_list('title',$customer_title[$ps]);
                                            		$temp_pax[] = $title.' '.$customer_first_name[$ps].' '.$customer_last_name[$ps];
                                            	}
                                            } 
                                            echo implode(', ', $temp_pax);
                                            ?>
											
										</td>

									    </tr>	
									    <?php
										}
									    ?>

									    <tr>
                                      <td style="width:50%;padding: 10px;border: 1px solid #cccccc; font-size: 13px; font-weight: bold;">Booking Date</td> 
                                       <?php //debug($tours_details); ?>
										<td style="width:50%;padding: 10px;border: 1px solid #cccccc; font-size: 13px; font-weight: bold;"><?php echo date("d M Y",strtotime($holiday_data['data'][0]['created_datetime'])); ?>
                                          </td>
									    </tr>

									     <tr><td>&nbsp;</td></tr>	

									    <!--  <tr>
                                        <td style="padding: 3px 0px; font-weight: bold;">Number of nights</td> 
                                       
										<td style="padding: 3px 0px;"><?php echo ($booking_details['total_nights'])?>
                                          </td>
									    </tr> -->

									    <!-- <tr>
                                        <td style="padding: 3px 0px; font-weight: bold;">Check-in</td> 
                                       
										<td style="padding: 3px 0px;"><?=@date("d M Y",strtotime($itinerary_details['check_in']))?>
                                          </td>
									    </tr>

									    <tr>
                                        <td style="padding: 3px 0px; font-weight: bold;">Check-out</td> 
                                       
										<td style="padding: 3px 0px;"><?=@date("d M Y",strtotime($itinerary_details['check_out']))?>
                                          </td>
									    </tr> -->


										</table>	
										</td>

									</tr>
									<?php  // debug($customer_details);
											?>
									
									
									<tr style="display: none;">
										<td style="border: 1px solid #ddd;">
											<table width="100%" cellpadding="5"
												style="padding: 10px; font-size: 13px;">
												<tr>
													<td style="padding: 8px 5px; background: #eee;-webkit-print-color-adjust: exact;"><strong>Passenger Name</strong></td>
													<td style="padding: 8px 5px; background: #eee;-webkit-print-color-adjust: exact;"><strong>Pax Type</strong></td>
													<td style="padding: 8px 5px; background: #eee;-webkit-print-color-adjust: exact;"><strong>Age</strong></td>

												</tr>
												<?php  // debug($customer_details);
												//$pscount = count($customer_details) - 1;
													for ($ps=0; $ps < count($customer_details) ; $ps++) { 
												?>
												<tr>
													<td style="padding: 8px 5px;"><?php echo $customer_details[$ps]['title'].' '.$customer_details[$ps]['first_name'].' '.$customer_details[$ps]['last_name'];?></td>
													<td style="padding: 8px 5px;"><?php echo $customer_details[$ps]['pax_type'];?></td>
													<td style="padding: 8px 5px;"><?php 
														$birthDate = date("d/m/Y", strtotime($customer_details[$ps]['date_of_birth']));
													  $birthDate = explode("/", $birthDate);
													  $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
													    ? ((date("Y") - $birthDate[2]) - 1)
													    : (date("Y") - $birthDate[2]));

													  echo ($age != '') ? $age : 'N/A';

													    /*if($customer_details[$ps]['pax_type'] == 'Adult'){
													    	echo '18+';
													    }else{
													    	echo $customer_details[$ps]['age'];
													    }*/
													  ?></td>

												</tr>
												<?php } ?>
											</table>
										</td>
										<td></td>
									</tr>
									<table width="100%" border="0" cellpadding="6" cellspacing="0" style="border-collapse: collapse;">
                  <tr>
                    <td style="text-align:left; font-size:13px;  color:#374c5d; font-weight: bold; padding:6px 0px;">
                      <span style="padding: 0px 0px;">Itinerary Details</span>                        
                    </td>
                  </tr>
                </table>
                <table width="100%"  border="0" cellpadding="" cellspacing="0" style="border-collapse: collapse; font-size: 13px; color: #374c5d;">
                  <?php
      foreach ($package_details as $key => $itinary) {
      
        ?>
          <tr>
            <td width="10%" align="left" style="padding:10px;color: #374c5d;border:solid 1px #ccc; font-weight:bold; vertical-align: top;">Day <?php echo $day+1; ?></td>
            <td width="20%" style="border:solid 1px #ccc;padding:10px; vertical-align: top;"><strong>Visiting Place:</strong>
              <p style="margin-top:0; margin-bottom:5px; vertical-align: top;"><?php echo  $itinary['place']; ?></p>
            </td>
            <!-- <td width="20%" style="border:solid 1px #fff"><strong>Night Stay:</strong>
              <p style="margin-top:0; margin-bottom:5px;">
                <?php echo  $itinary['program_title']; ?>                      
              </p>
            </td> -->
            <td width="50%" style="border:solid 1px #ccc;padding:10px;"><strong>Description:</strong>
            <p style="margin:0;"><?php echo  $itinary['itinerary_description'];   ?></p>
                      
            
            </td>
          </tr>
          <?php
        }
        ?>

      </table>
	<tr><td>&nbsp;</td></tr>
									<tr style="border-bottom:1px solid #ccc">
						                <td width="50%" style="padding: 10px;border: 1px solid #cccccc; font-size: 13px; font-weight: bold;">     Purchase Summary (<?php echo get_application_default_currency(); ?>)</td>
											<tr><td style="border: 1px solid #cccccc;"><table width="100%" cellpadding="5" style="padding: 10px;font-size: 13px;"><tbody>
												

													<?php 
														 $base_fare = $attributes['tour_totalamount'];
														 
													?>
<tr>
												<td style="font-size:13px; width: 20%; font-weight:normal;line-height:18px;">Base Fare</td>
													<td style="font-size:13px; width: 20%; text-align: right; font-weight:normal;line-height:18px;">
													<?php

                          echo $base_fare;
													?>
													 	
													 </td>
												</tr>
														 
												<?php
											

												$convinence_amount = $attributes['cv'];

												
												?>

												<tr><td style="width: 50%;">Taxes</td>
													<td style="font-size:13px; width: 20%; text-align: right; font-weight:normal;line-height:18px;">
													<?php 
													echo sprintf("%.2f", $attributes['total_markup'])." ".$CurrencyCode;
													 
													 ?>
													 	
													 </td>

													 

												</tr>
												

												<tr>
												<td style="font-size:13px; width: 20%; font-weight:normal;line-height:18px;">GST</td>
													<td style="font-size:13px; width: 20%; text-align: right; font-weight:normal;line-height:18px;">
													<?php

                         echo $discount = sprintf("%.2f", $attributes['gst'])." ".$CurrencyCode;
													?>
													 	
													 </td>
												</tr>
												<?php
												
												?>

											

												<tr>
													<td style="font-size:13px; width: 50%; font-weight:normal;line-height:18px;"><strong>Total Amount Due </strong></td>
													<td style="font-size:13px; width:50%;  font-weight:normal;line-height:18px;"><strong>
													<?php 
													$grand_total=$attributes['tour_totalamount']+$attributes['total_markup']+$attributes['gst'];
													echo $grand_total;
													 ?></strong></td>
												</tr>
											</table>
										</td>

										<?php if($rate_comment['status']){

                        ?>	<tr><td>&nbsp;</td></tr>
                        <tr class="">
                            <td colspan="6" style="padding: 8px 14px; text-align: justify; font-size:13px; color: #374c5d;"><strong>Hotel Policy</strong><br><?php echo $rate_comment['comment'][0]['rateComments'][0]['description']; ?></td>
                        </tr>
                        <?php }else{ ?>
	<tr><td>&nbsp;</td></tr>
                        <tr class="hide">
                            <td colspan="6" style="padding: 8px 14px; color: #374c5d; text-align: justify; font-size:13px"><strong>Hotel Policy</strong><br>Not Available</td>
                        </tr>
                        <?php 
                        }
                        ?>

                        


                        <?php if(isset($attributes->special_req) && !empty($attributes->special_req)){

                        ?>

                        <tr class="hide">
                            <td colspan="6" style="padding: 8px 14px; line-height: 20px; text-align: justify; font-size:13px; color: #374c5d;"><strong>Special Requests</strong><br>
                            <ul>
                            <?php 
			                            foreach ($attributes->special_req as $special_req_key => $special_req_value) {
			                            	echo "<li>".$special_req_value."</li>";
			                            }
                             			?>
                             </ul>			
                             </td>
                        </tr>
                        <?php }else{ ?>

                        <tr class="hide">
                            <td colspan="6" style="padding: 8px 14px; text-align: justify; font-size:13px; color: #374c5d;"><strong>Special Requests</strong><br> Not Available</td>
                        </tr>
                        <?php 
                        }
                        ?>

                        <?php if(isset($attributes->users_comments) && !empty($attributes->users_comments)){

                        ?>

                        <tr class="hide">
                            <td colspan="6" style="padding: 8px 14px; text-align: justify; font-size:13px; color: #374c5d;"><strong>Customer Remarks</strong>
                            <br><?php echo $attributes->users_comments; ?></td>
                        </tr>
                        <?php }else{ ?>

                        <tr class="hide">
                            <td colspan="6" style="padding: 8px 14px; text-align: justify; font-size:13px; color: #374c5d;"><strong>Customer Remarks</strong>
                            <br>Not Available</td>
                        </tr>
                        <?php 
                        }
                        ?>


                        

                         <tr>
                          <td width="50%" style="padding: 10px;border: 1px solid #cccccc; font-size: 13px; font-weight: bold;"><strong>Supplier
                                    Information</strong><br>Payable through <?php echo $attributes->supplier; ?> - <?php echo $attributes->supplier_vat; ?>, acting as agent for the service operating company, details of which can be provided upon request.</td>
                        </tr>

									</tr>

<tr><td>&nbsp;</td></tr>
									   <tr>
                            
                         <tr>
                            <td colspan="6" style="padding: 8px 14px; text-align: justify; color: #374c5d; font-size:13px">
                            <strong>Cancellation
                                    Policies</strong>
                                <br/>
                                <ul>
                                    <?php
                                    foreach ($cancel_details as $key => $value) {
                                    	// debug($value);
                                    	// 	die;
                                    	foreach ($value as $key1 => $value1) {
                                    		// debug($value1);
                                    		// die;
                                        ?>
                                        <?php 
if($key1=='cancellation_advance')
                                        echo '<li><strong>Cancellation in advance:</strong>&nbsp;'.$value1.'<br/></li>';
                                            
if($key1=='cancellation_penality')
                                           echo '<li><strong>Cancellation penalty:</strong>&nbsp;'.$value1.'<br/></li>';
                                    }
                                }
                                    ?>
                                </ul>

                            </td>
                        </tr> 
                         <tr>
                            <td style="padding: 8px; text-align: justify; font-size:13px"><strong>Amendment
                                    Policies</strong> - We're here to help! If you need assistance
                                with your reservation, please visit our Help Center. For urgent
                                situations,: such as check-in troubles or arriving to something
                                unexpected</td>
                        </tr> 
                       

                        

                        <td width="50%" style="padding: 10px;border: 1px solid #cccccc; font-size: 13px; font-weight: bold;">
                                Terms &amp; Conditions</td>
                        </tr>
                        <tr>
                             <?php if(isset($attributes->terms_n_condition) && !empty($attributes->terms_n_condition)){
                        	?>
                            <td width="100%" style="border: 1px solid #cccccc;"><table width="100%" cellpadding="5" style="padding: 10px 20px;font-size: 13px;"><tbody><tr>                                        <td><?php //echo $attributes->terms_n_condition; ?>
                            	<p>Company Terms and Conditions are as follows</p>
                            	<br/>
								<p>	1. Booking process (test data) </p>
								<br/>
								<p>2. Payment process (test data)</p>
                            </td></tr></tbody></table></td>
                        <?php }else{ ?>
                        	<td width="100%" style="border: 1px solid #cccccc;"><table width="100%" cellpadding="5" style="padding: 10px 20px;font-size: 13px;"><tbody><tr>                                        <td>Not Available</td></tr></tbody></table></td>
                        <?php } ?>
                        </tr>

                       

                        <tr><td>&nbsp;</td></tr>

                        <tr style="border-bottom:1px solid #ccc; border-top:1px solid #ccc; color:#374c5d;">
						<td width="50%" style="padding: 10px;border: 1px solid #cccccc; font-size: 13px; font-weight: bold;">
							Contact Information
							</td></tr>
									<tr><td width="100%" style="border: 1px solid #cccccc;"><table width="100%" cellpadding="5" style="padding: 10px 20px;font-size: 13px;"><tbody><tr>                                        <td>  For any questions or inquiries please email us at <a href="mailto:support@gmail.com">support@gmail.com</a> or contact our 24/7 Help Desk at <span style="color:#39b274; ">0123456789,</span> our dedicated and well experienced staff will assist you all the way.</td></tr></tbody></table></td></tr>
                                    
                                    <tr class="hide">
										 <td style="text-align:left; color: #374c5d; font-size: 13px;border-collapse: collapse;padding:0px;"><br>
									We at Vivance are here to make your travel worry free. So zip freely, hop often and remember to always enjoy the journey!   </td>
									</tr>
								
						</td>
					</tr>


									<tr style="display: none;">
									   <td style="padding: 0 0px 0 0px;-webkit-text-size-adjust: none;border-collapse: collapse">
									  
									   </td>
									</tr>



									<!-- <tr>
								<td style="padding: 10px; background:#a1a1a1; color: #fff; border: 1px solid #ddd; font-size: 13px; font-weight: bold;">Price Summary</td>
								
							</tr> -->
									<!-- <tr>
								<td style="border: 1px solid #ddd;">
									<table width="100%" cellpadding="5" style="padding: 10px;font-size: 13px;">
										<tr>
											<td style="padding: 5px; background: #eee;"><strong>Base Fare</strong></td>
											<td style="padding: 5px; background: #eee;"><strong>Taxes</strong></td>
											<td style="padding: 5px; background: #eee;"><strong>Discount</strong></td>
											
											<td style="padding: 5px; background: #eee;"><strong>Total Fare</strong></td>
										</tr>
										<tr>
											<td><?php echo $booking_details['currency']; ?> <?php echo $booking_details['grand_total']; ?></td>
                                            <td><?php echo $booking_details['currency']; ?> <?php echo $itinerary_details['Tax']; ?></td>
                                            <td><?php echo $booking_details['currency']; ?> <?php echo $itinerary_details['Discount']; ?></td>
                                            <td> <?php echo $booking_details['currency']; ?> <?php echo $booking_details['grand_total']; ?></td>
										</tr>
										<tr style="font-size:13px;">
                                        	<td colspan="5" align="right"><strong>Total Fare</strong></td>
											<td><strong><?php echo $booking_details['currency']; ?> <?php echo $booking_details['grand_total']; ?></strong></td>
										</tr>
									</table>
								</td>
								<td></td>
							</tr>   -->

<!-- 									<tr class="hide">
										<td width="100%"
											style="padding: 10px; background: #a1a1a1; color: #fff; border: 1px solid #ddd; font-size: 13px; font-weight: bold;">Terms
											and Conditions</td>
									</tr>

									<tr class="hide">
										<td width="100%">
											<table width="100%" cellpadding="5"
												style="padding: 5px; font-size: 11px; line-height: 22px;">
												<tr>
													<td>1.The primary guest must be at least 18 years of age to
														check into this hotel.</td>
												</tr>
												<tr>
													<td>2.As per Government regulations, It is mandatory for
														all guests above 18 years of age to carry a valid photo
														identity card & address proof at the time of check-in. In
														case, check-in is denied by the hotel due to lack of
														required documents, you cannot claim for the refund & the
														booking will be considered as NO SHOW.</td>
												</tr>
												<tr>
													<td>3.Unless mentioned, the tariff does not include charges
														for optional room services (such as telephone calls, room
														service, mini bar, snacks, laundry etc). In case, such
														additional charges are levied by the hotel, we shall not
														be held responsible for it.</td>
												</tr>
												<tr>
													<td>4.All hotels charge a compulsory Gala Dinner Supplement
														on Christmas and New Year's eve. Other special supplements
														may also be applicable during festival periods such as
														Dusshera, Diwali etc. Any such charge would have to be
														cleared directly at the hotel.</td>
												</tr>
												<tr>
													<td>5.Please ensure that the passenger names are mentioned
														exactly as on Passport and VISA.</td>
												</tr>
												<tr>
													<td>6.In case of an increase in the hotel tariff (for
														example, URS period in Ajmer or Lord Jagannath Rath Yatra
														in Puri) the customer is liable to pay the difference if
														the stay period falls during these dates.</td>
												</tr>
												<tr>
													<td>7.Neptune will not be responsible for any service
														issues at the hotel.</td>
												</tr>
												<tr>
													<td>8.Accommodation can be denied to guests posing as a
														couple if suitable proof of identification is not
														presented at the time of check in. Hotel reserves the
														right of admission.</td>
												</tr>
											</table>
										</td>
									</tr> -->
								</table>
							</td>
						</tr>
						<tr><td style="border: none;">
						<div class="foot_bottom hide">      
            <ul>            <!--    <li class="list-unstyled">                    <p></p>                </li> -->                                        <li class="list-unstyled"><a class="col_fb" href="https://www.facebook.com/tripmiaa"><i class="fab fa-facebook-f"></i></a></li>                                        <li class="list-unstyled"><a class="col_twt" href="https://twitter.com/tripmiaa"><i class="fab fa-twitter"></i></a></li>                    
                                                <li class="list-unstyled"><a class="col_istg" href="https://www.instagram.com/tripmiaa"> <i class="fab fa-instagram"></i></a></li>                                                <li class="list-unstyled"><a class="col_lin" href="https://www.linkedin.com/tripmiaa"><i class="fab fa-linkedin-in"></i></a></li>                                                <li class="list-unstyled"><a class="col_lin" href="https://www.youtube.com/tripmiaa"><i class="fab fa-youtube"></i></a></li>                                    </ul>      
      </div></td></tr>
					</table>
				</td>
			</tr>

		</tbody>
	</table>
<!-- 	<table id="printOption"
		style="border-collapse: collapse; font-size: 14px; margin: 10px auto; font-family: arial;"
		width="70%" cellpadding="0" cellspacing="0" border="0">
		<tbody>
			<tr>
				<td align="center"><input
					style="background: #418bca; height: 34px; padding: 10px; border-radius: 4px; border: none; color: #fff; margin: 0 2px;"
					onclick="w=window.open();w.document.write(document.getElementById('tickect_hotel').innerHTML);w.print();w.close(); return true;"
					type="button" value="Print" />
			
			</tr>
		</tbody>
	</table> -->
</div>

<!-- Modal -->
<style>
[type="checkbox"]:not (:checked ), [type="checkbox"]:checked {
	left: 135px;
	position: absolute;
}
</style>


<script>
$(document).ready(function() {
  $('.btn-popup').on('click', function(e) {
    e.preventDefault();
  });
  $('#btn-togglr-fare').on('change', function(){
		var $check = $('#inc_fare');
		if($(this).prop('checked')){
			//$(this).addClass('btn-checked');
			$('.fare-details').hide();
			$check.val(0);		
		} else {
			//$(this).removeClass('btn-checked');
			$('.fare-details').show();
			$check.val(1);
		}
	});
	$('#btn-togglr-address').on('change', function(){
		var $check = $('#inc_sddress');
		if($(this).prop('checked')){
			$('.address-details').hide();
			$check.val(0);
		} else {
			$('.address-details').show();
			$check.val(1);
		}
		
	});
});
</script>