<?php
$booking_details = $data['booking_details'][0];
//echo debug($booking_details);
//exit;
   $itinerary_details = $booking_details['itinerary_details'][0];
   $attributes = json_decode($booking_details['attributes'],true);
   $customer_details = $booking_details['customer_details'];

// debug($customer_details);
// exit;
$domain_details = $booking_details;
$lead_pax_details = $booking_details['customer_details'];
?>

<table style="border-collapse: collapse; background: #ffffff;font-size: 12pt; margin: 0 auto; font-family: arial;" width="100%" cellpadding="0" cellspacing="0" border="0">
  <tbody>
   <tr>
   <td style="border-collapse: collapse; padding:0px 10px 0px" ><table width="100%" style="border-collapse: collapse;" cellpadding="0" cellspacing="0" border="0">
			<tr><td style="font-size:10pt; line-height:15px; width:100%; display:block; font-weight:600; text-align:center">Confirmation Voucher</td></tr>
			<tr>
					<td><table width="100%" style="border-collapse: collapse;" cellpadding="0" cellspacing="0" border="0">
			     <tr>
					<td colspan="2" valign="bottom">
            <img style="width:70px;" src="https://www.alkhaleejtours.com/extras/custom/TMX9604421616070986/images/logo1.png">
            
          </td>
					<td style="padding: 10px;width:35%"><table width="100%" style="border-collapse: collapse;text-align: right; font-size:10pt" cellpadding="0" cellspacing="0" border="0">         		
	                     <tr>
	                        <td style="padding-bottom:10px;line-height:20px" align="right">
                            <!-- <br> -->
                            <span>Booking Reference: <?php echo $booking_details['app_reference']; ?></span>
                            <span>Booked Date : <?php echo date("d M Y",strtotime($booking_details['created_datetime'])); ?></span></td>
	                     </tr>
                         </table></td>
				  </tr>
				  
				  
				</table>
				</td>
			</tr>
            <tr><td style="line-height:6px;">&nbsp;</td></tr>    
           <tr>
              <td align="right" style="line-height:24px;font-size:10pt;border-top:1px solid #00a9d6;border-bottom:1px solid #00a9d6;padding: 5px;">Status: <strong class="<?php echo booking_status_label( $booking_details['status']);?>" style=" font-size:10pt;">
                 <?php 
                    switch($booking_details['status']){
                    	case 'BOOKING_CONFIRMED': echo 'CONFIRMED';break;
                    	case 'BOOKING_CANCELLED': echo 'CANCELLED';break;
                    	case 'BOOKING_FAILED': echo 'FAILED';break;
                    	case 'BOOKING_INPROGRESS': echo 'INPROGRESS';break;
                    	case 'BOOKING_INCOMPLETE': echo 'INCOMPLETE';break;
                    	case 'BOOKING_HOLD': echo 'HOLD';break;
                    	case 'BOOKING_PENDING': echo 'PENDING';break;
                    	case 'BOOKING_ERROR': echo 'ERROR';break;
                    	
                    }
                    
                    									
                    ?>
                 </strong>
              </td>
           </tr>
            <tr><td style="line-height:6px;">&nbsp;</td></tr>                  
                           <tr>
                              <td style="padding:0;"><table width="100%" cellpadding="5" style="padding: 10px;font-size: 10pt;padding:5px;">
                                    <tbody>
                                    <tr>
                                       <?php if($attributes['HotelImage'] !='/'):?>
                                          <td width="26%" style="padding:0px 0"><img style="width:100px; height:60px;object-fit:cover;" src="<?=$attributes['HotelImage'];?>" /></td>
                                       <?php else:?>
                                          <td style="padding:0px 0"><img style="width:100px; height:50px;object-fit:cover;" src="<?=$GLOBALS['CI']->template->template_images("default_hotel_img.jpg");?>" /></td>
                                       <?php endif;?>
                                       <td width="40%" valign="top" style="padding:0px;line-height:18px;"><span style="font-size:12pt;color:#00a9d6;vertical-align:middle;font-weight: 600;"><?php echo $booking_details['hotel_name']; ?></span><br><span style="display: block;font-size: 10pt;"><?php echo $booking_details['hotel_address']; ?> </span><br><br><span style="display: block;font-size: 10pt;"><img style="width:50px;" src="<?php echo 'https://www.alkhaleejtours.com/extras/system/template_list/template_v1/images/star_rating-'.$attributes["StarRating"].'.png'; ?>" /></span></td>

                                       <td width="34%" style="padding:0px 0;text-align: center;line-height:25px;"><table style="border:2px solid #808080;">
                                       	<tbody>
                                       		<tr>
                                       			<td><span style="font-size:11pt; display:block"><span style="color:#00a9d6;padding:5px; display:block;text-transform:uppercase">Booking ID</span><br><span style="font-size:10pt;padding-bottom: 0px;display:block;font-weight: 600;"><?php echo $booking_details['booking_id']; ?></span></span></td>
                                       		</tr>
                                       	</tbody>
                                       </table>
                                       </td>
                                    </tr>
                                    </tbody>
                                 </table>
                              </td>
                           </tr>
                           <tr><td style="line-height:2px;">&nbsp;</td></tr>   
					<tr>
					<td style="padding: 10px;"><table cellpadding="5" cellspacing="0" border="0" width="100%" style="border-collapse: collapse;">
                           <tr>
                              <td style="background-color:#00a9d6;border: 1px solid #00a9d6; color:#fff; font-size:10pt; padding:5px; line-height:normal;"><img width="12" src="<?=SYSTEM_IMAGE_DIR.'hotel_v.png'?>" /> <span style="font-size:10pt;color:#fff;line-height:12px;"> &nbsp;Hotel Details</span></td>
                           </tr>
							<tr>
								<td width="100%" style="border: 1px solid #00a9d6;padding:0"><table width="100%" cellpadding="5" style="padding: 10px;font-size: 9pt;">	    
									    <tr>
											<td style="background-color:#d9d9d9;color: #333333;" width="16.6%"><strong>Check-In</strong></td>
											<td style="background-color:#d9d9d9;color: #333333;" width="16.6%"><strong>Check-Out</strong></td>
											<td align="center" style="background-color:#d9d9d9;color: #333333;" width="16%"><strong>No of Room's</strong></td>
											<td style="background-color:#d9d9d9;color: #333333;" width="32%"><strong>Room Type</strong></td>
											<td align="center" style="background-color:#d9d9d9;color: #333333;" width="10%"><strong>Adult's</strong></td>
											<td align="center" style="background-color:#d9d9d9;color: #333333;" width="10%"><strong>Children</strong></td>							   
									    </tr>									    
									    <tr>
											<td><?=@date("d M Y",strtotime($itinerary_details['check_in']))?></td>
											<td><?=@date("d M Y",strtotime($itinerary_details['check_out']))?></td>
											<td align="center"><?php echo $booking_details['total_rooms']; ?></td>
											<td><?php echo $itinerary_details['room_type_name']; ?></td>
											<td align="center"><?php echo $booking_details['adult_count']; ?></td>
											<td align="center"><?php echo $booking_details['child_count']; ?></td>
									    </tr>
									</table>
									</td>
							</tr>
							<tr><td style="line-height:2px;">&nbsp;</td></tr>


                           <tr>
                              <td style="background-color:#666666;border: 1px solid #666666; color:#fff; font-size:9pt; padding:5px;"><img width="12" style="vertical-align:middle" src="<?=SYSTEM_IMAGE_DIR.'people_group.png'?>" /> <span style="font-size:9pt;color:#fff;vertical-align:middle;"> &nbsp;Guest(s) Details</span></td>
                           </tr>
                           <tr>
								<td width="100%" style="border: 1px solid #666666;padding:0"><table width="100%" cellpadding="5" style="padding: 10px;font-size: 9pt;">	 
                                    <tr>
                                       <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Sr No.</td>
                                       <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Passenger(s) Name</td>
                                       <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Type</td>
                                       <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Age</td>
                                    </tr>
                                   <?php
                                          $i=1;
                                       ?> 
                                    <?php foreach($customer_details as $details):?>
                                    <tr>
                                      
                                       <td style="padding:5px;"><?=$i?></td>
                                       <td style="padding:5px"><?php echo $details['title'].' '.$details['first_name'].' '.$details['last_name']?></td>
                                       <td style="padding:5px;"><?=$details['pax_type']?></td>
                                       <?php
                                          
                                          $age = '';

                                         $current_date = date('Y-m-d');
                                          $date1 = date_create($current_date);
                                           $date2 = date_create($details['date_of_birth']);
                                          $date_obj = date_diff($date1,$date2);
                                          

                                          if($details['pax_type']=='Child'){
                                             $age = $date_obj->y;
                                          }
                                          $i++;
                                          if($age!="")
                                          {
                                            $age="NIL";
                                          }
                                       ?>
                                       <td style="padding:5px;"><?="NIL"?></td>                                       
                                      
                                    </tr>
                                    <?php endforeach;?> 
                                 </table>
                              </td>
                           </tr>
                           <tr><td style="line-height:2px;">&nbsp;</td></tr>



                           <tr>
                              <td style="background-color:#00a9d6;border: 1px solid #00a9d6; color:#fff; font-size:9pt; padding:5px;"><img width="12" style="vertical-align:middle" src="<?=SYSTEM_IMAGE_DIR.'people_group.png'?>" /> <span style="font-size:9pt;color:#fff;vertical-align:middle;"> &nbsp;Supplier Details</span></td>
                           </tr>
                           <tr>
								<td width="100%" style="border: 1px solid #00a9d6;padding:0"><table width="100%" cellpadding="5" style="padding: 10px;font-size: 9pt;">	 
                                    <tr>
                                       <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Supplier Name</td>
                                       <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Supplier Email</td>
                                       <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Supplier Phone</td>
                                    </tr>
                                  
                                  <tr>                                      
                                       <td style="padding:5px"><?php echo $supplier_details[0]['first_name'].' '.$supplier_details[0]['last_name'] ?></td>
                                       <td style="padding:5px;"><?php echo provab_decrypt($supplier_details[0]['email']) ?></td>                                       
                                       <td style="padding:5px;"><?php echo $supplier_details[0]['phone'] ?></td>                             
                                    </tr>
                                 </table>
                              </td>
                           </tr>
                           <tr><td style="line-height:2px;">&nbsp;</td></tr>


							<tr>                     
                <td width="100%" style="padding:0;padding-right: 5px;"><table width="100%" cellspacing="-9" cellpadding="5" style="padding: 0;font-size: 9pt;">
                      <tr>
                        <td><table cellspacing="0" cellpadding="5" width="100%" style="font-size:9pt; padding:0;border:1px solid #9a9a9a;">
                            
                              <tr>
                                <td style="border-bottom:1px solid #ccc;padding:5px;"><span style="font-size:10pt">Payment Details</span></td>
                                <td style="border-bottom:1px solid #ccc;padding:5px;"><span style="font-size:9pt">Amount (<?=$booking_details['currency']?>)</span></td>
                              </tr>
                              <tr>
                                <td style="padding:5px"><span>Base Fare</span></td>
                                <td style="padding:5px"><span><?php echo round($booking_details['fare']+$booking_details['admin_markup']+$booking_details['agent_markup']); ?></span></td>
                              </tr>
                              <tr>
                                <td style="padding:5px"><span>Convinence Fee</span></td>
                                <td style="padding:5px"><span><?php echo $booking_details['convinence_amount']; ?></span></td>
                              </tr>
                             <!--  <?php if($itinerary_details['gst'] > 0){?> -->
                               <tr>
                                  <td style="padding:5px"><span>GST</span></td>
                                  <td style="padding:5px"><span><?php echo $itinerary_details['gst']; ?></span></td>
                               </tr>
                            <!--   <?php } ?> -->
                              <tr>
                                <td style="padding:5px"><span>Discount</span></td>
                                <td style="padding:5px"><span><?php echo $booking_details['discount']; ?></span></td>
                              </tr>
                              
                              <tr>
                                <td style="border-top:1px solid #ccc;padding:5px"><span style="font-size:10pt">Total Fare</span></td>
                                <td style="border-top:1px solid #ccc;padding:5px;font-size:10pt;"><span style=""><?php echo round($booking_details['grand_total']); ?></span></td>
                              </tr>
                            
                          </table>
                        </td>
                        <td><table cellspacing="0" cellpadding="5" width="100%" style="border:1px solid #9a9a9a;font-size:10pt; padding:0;">

                              <tr>
                                <td style="background-color:#d9d9d9;border-bottom:1px solid #ccc;border-left:1px solid #9a9a9a;padding:5px; color:#333"><span style="font-size:10pt">Room Inclusions</span></td>
                              </tr>
                                             <?php if($attributes['Boarding_details']): ?>
                                                <?php foreach($attributes['Boarding_details'] as $b_value):?>
                                    <tr>
                                      <td style="padding:5px"><span><?=$b_value?></span></td> 
                                    </tr>
                                
                                                <?php endforeach;?>
                                             <?php else:?>
                                                   <tr>
                                                      <td style="padding:5px"><span>Room Only</span></td>  
                                                   </tr>
                                             <?php endif;?>
                              <tr>
                                <td style="border-top:1px solid #ccc;padding:5px; line-height:16px"><span style="font-size:8pt; color:#666;">* Room inclusions are subject to change with Hotels.</span></td>
                              </tr>
                          
                          </table>
                        </td>
                      </tr>
                    
                  </table>
                </td>
              </tr>
              <tr><td align="center" style="border-bottom:1px solid #999999;padding-bottom:5px"><span style="font-size:10pt; color:#555;">Customer Contact Details | E-mail : <?=$customer_details[0]['email']?> | Contact No :<?=$booking_details['phone_code']?> <?=$customer_details[0]['phone']?></span></td></tr>
              <!-- <tr><td style="line-height:2px;">&nbsp;</td></tr> -->
              <tr>
              <td><span style="font-size: 10pt;line-height:12px;font-weight: 500;">Cancellation Policy</span></td></tr>
              <tr>
                <td style="font-size:9pt; color:#555; line-height:16px"><?=$booking_details['cancellation_policy'][0]?></td>
              </tr>
              <!-- <tr><td style="line-height:2px;">&nbsp;</td></tr> -->
              <tr>
              <td><span style="font-size: 10pt;line-height:12px;font-weight: 500;">Terms and Conditions</span></td></tr>
              <tr>
                <td style="border-bottom:1px solid #999999; padding-bottom:0px; font-size:8pt; color:#555;line-height:16px"><?php echo $data['terms_conditions']; ?></td>
              </tr>
              <!-- <tr><td style="line-height:2px;">&nbsp;</td></tr> -->
              <tr>
                <td align="right" style="padding-top:5px;font-size:8pt;line-height:16px"><?=strtoupper($data['domainname'])?><br>Phone : <?=$data['phone']?> | Email : <?=$data['email']?><br>Address : <?=STATIC_ADDRESS;?> <?=STATIC_COUNTRY;?></td>
              </tr>			
							
			         </table>
			         </td>
			</tr>
			
			</table>
    </td>
   </tr>
  </tbody>
</table>  