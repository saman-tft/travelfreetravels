<style>
th,td{padding:5px;}
</style> 
<?php
	$booking_details = $data['booking_details'][0];
	$itinerary_details = $data['booking_itinerary_details'][0];
	$extra_details = json_decode($itinerary_details['passenger_details']);
	$domain_details = $booking_details;

	$request = json_decode($data['booking_itinerary_details'][0]['request'],true);
	// $adult_count = array_sum(explode(',',$request['adults'][0]));
	// $child_count = array_sum(explode(',',$request['childs'][0]));
?>
<table style="border-collapse: collapse; background: #ffffff;font-size: 14px; margin: 0 auto; font-family: arial;" width="100%" cellpadding="0" cellspacing="0" border="0">
	<tbody>
		<tr>
			<td style="border-collapse: collapse; padding:10px 20px 20px" >
      			<table width="100%" style="border-collapse: collapse;" cellpadding="0" cellspacing="0" border="0">
      				<tr>
			          <td style="padding: 10px;">
			            <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-collapse: collapse;">
			            <tr>
			              <td style="font-size:22px; line-height:30px; width:100%; display:block; font-weight:600; text-align:center">E-Ticket</td>
			            </tr>
			             <tr>
			                <td>
			                  <table width="100%" style="border-collapse: collapse;" cellpadding="0" cellspacing="0" border="0">
			                    <tr>
			                      <td><img style="height:35px;" src="<?=$GLOBALS['CI']->template->domain_images($data['logo'])?>"></td>
			                      <td style="padding: 10px;width:35%">
	                                  <table width="100%" style="border-collapse: collapse;text-align: right; line-height:15px;" cellpadding="0" cellspacing="0" border="0">
	                                    
	                                    <tr>
	                                    <td style="font-size:14px;"><span style="width:100%; float:left"><?php echo $data['address'];?></span>
	                                    </td>
	                                    </tr>
	                                     </table>
			                       </td>
			                    </tr>
			                  </table>
			                </td>
			            </tr>
			            	<tr>
			                <td width="100%" style="padding: 10px;border: 1px solid #cccccc; font-size: 14px; font-weight: bold;">Hotel Booking Lookup</td>
			              </tr>
			              <tr>
			                <td style="border: 1px solid #cccccc;">
			                  <table width="100%" cellpadding="5" style="padding: 10px;font-size: 13px;">
			                    <tr>
			                      <td><strong>Booking Reference</strong></td>
			                      <td><strong>Booking ID</strong></td>                    
			                      <td><strong>Booking Date</strong></td>
			                      <td><strong>Status</strong></td>
			                    </tr>
			                    <tr>
			                      <td><?php echo $booking_details['parent_pnr']; ?></td>
			                      <td><?php echo $booking_details['pnr_no']; ?></td>
			                      <td><?php echo date("d M Y",strtotime($itinerary_details['book_date'])); ?></td>
			                      
			                                           <td>
			                                           <strong class="<?php echo booking_status_label( $booking_details['booking_status']);?>" style=" font-size:14px;">
			                      <?php 
			                      switch($booking_details['booking_status']){
			                        case 'CONFIRM': echo 'CONFIRMED';break;
			                        case 'CANCELLED': echo 'CANCELLED';break;
			                        case 'FAILED': echo 'FAILED';break;
			                        case 'PROCESS': echo 'INPROGRESS';break;
			                        case 'ON REQUEST': echo 'INCOMPLETE';break;
			                        case 'HOLD': echo 'HOLD';break;
			                        case 'PENDING': echo 'PENDING';break;
			                        case 'ERROR': echo 'ERROR';break;
			                        
			                      }
			                      
			                                        
			                      ?>
			                      </strong></td>
			                                           
			                    </tr>
			                  </table>
			                </td>
			              </tr>
			                <tr><td>&nbsp;</td></tr>
			              <tr>
			                <td style="padding: 10px;border: 1px solid #cccccc; font-size: 14px; font-weight: bold;">Hotel Information</td>
			              </tr>
			              <tr>
			                <td  width="100%" style="border: 1px solid #cccccc;">
			                  <table width="100%" cellpadding="5" style="padding: 10px;font-size: 13px;">
			                    <tr>
			                      <td width="10%"><strong>Hotel Name</strong></td>
			                      <td width="16%"><strong>Hotel Address</strong></td>
			                      <td><strong>Check-In</strong></td>
			                      <td><strong>Check-Out</strong></td>
			                      <td><strong>No of Room's</strong></td>
			                      <td><strong>Room Type</strong></td>
			                      <td><strong>Adult's</strong></td>
			                      <td><strong>Children</strong></td>
			                    </tr>
			                    <tr>
                                    <td>
                                    <?php echo $booking_details['hotel_name']; ?>
                                    </td>
                                    <td style="line-height:16px">
                                    <?php echo $booking_details['hotel_address']; ?> 
                                    </td>
                                   
                                    <td><span style="width:100%; float:left"> <?=@date("d M Y",strtotime($itinerary_details['check_in_date']))?></span></td>
                                    <td><span style="width:100%; float:left">   <?=@date("d M Y",strtotime($itinerary_details['check_out_date']))?></span></td>
                                    <td align="center"><?php echo $itinerary_details['room_count']; ?></td>
                                    <td  width="13%"><?php echo $itinerary_details['room_type_name']; ?></td>
                                    <td align="center"><?php echo $booking_details['adult_count']; ?></td>
                                    <td align="center"><?php echo $booking_details['child_count']; ?></td>
			                    </tr>
			                  </table>
			                </td>
			              </tr>
			               <tr><td>&nbsp;</td></tr>
			              <tr>
			                <td style="padding: 10px;border: 1px solid #cccccc; font-size: 13px; font-weight: bold;">Contact Information</td>
			                
			              </tr>
			              	<tr>
			                <td style="border: 1px solid #cccccc;">
			                  <table width="100%" cellpadding="5" style="padding: 10px;font-size: 13px;">
			                    <tr>
			                      <td><strong>Passenger Name</strong></td>
			                      <td><strong>Mobile</strong></td>
			                      <td><strong>Email</strong></td>
			                      <td><strong>City</strong></td>
			                      
			                    </tr>
			                    <tr>
			                     <td><?php echo $itinerary_details['contact_fname'].' '.$itinerary_details['contact_mname'].' '.$itinerary_details['contact_sur_name'];?></td>
                                <td><?php echo $itinerary_details['contact_mobile_number'];?></td>
                                <td><?php echo $itinerary_details['contact_email'];?></td>
                                <td><?php echo $extra_details->billing_city;?></td>
			                                             
			                    </tr>   
			                  </table>
			                </td>
			              </tr>
						<tr><td>&nbsp;</td></tr>
						<tr>
			                <td style="padding: 10px;border: 1px solid #cccccc; font-size: 13px; font-weight: bold;">Price Summary</td>
			                
			              </tr>
			              <tr>
			                <td style="border: 1px solid #cccccc;">
			                  <table width="100%" cellpadding="5" style="padding: 10px;font-size: 13px;">
			                    <tr>
			                      <td><strong>Base Fare</strong></td>
			                      <td><strong>Taxes</strong></td>
			                      <td><strong>Discount</strong></td>
			                      <td><strong>Total Fare</strong></td>
			                    </tr>
			                    <tr>
			                      <td><?php echo $booking_details['payment_currency']; ?> <?php echo number_format($booking_details['total_room_price'], 2); ?></td>
                                    <td><?php echo $booking_details['payment_currency']; ?> <?php echo number_format($itinerary_details['tax_rate_info_id'], 2); ?></td>
                                    <td><?php echo $booking_details['payment_currency']; ?> <?php echo number_format($booking_details['discount_amount'], 2); ?></td>
                                    <td> <?php echo $booking_details['payment_currency']; ?> <?php echo number_format($booking_details['total_room_price'], 2); ?></td>
			                    </tr>
			                    <tr style="font-size:13px;">
			                       <td  align="right"><strong>Total Fare</strong></td>
			                      <td>
			                      <strong><?php echo $booking_details['payment_currency']; ?> <?php echo number_format($booking_details['total_room_price'], 2); ?></strong></td>
			                      <td></td>
			                      <td></td>
			                    </tr>
			                  </table>
			                </td>
			              </tr>
			              <tr><td>&nbsp;</td></tr>
			              <tr>
			                <td width="100%" style="padding: 10px;border: 1px solid #cccccc; font-size: 13px; font-weight: bold;">Terms and Conditions</td>
			              </tr>
			              <tr>
			                <td  width="100%" style="border: 1px solid #cccccc;">
			                  <table width="100%" cellpadding="5" style="padding: 10px 20px;font-size: 13px;">
			                   <tr>
                                        	<td>
                                        	<strong>Hotel</strong> - We're here to help! If you need assistance with your reservation, please visit our Help Center. For urgent situations,: such as check-in troubles or arriving to something unexpected 
                                        	</td>
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
                                   