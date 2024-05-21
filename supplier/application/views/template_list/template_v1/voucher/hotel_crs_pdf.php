<?php
$booking_details = $data['booking_details'][0];
//exit;
$itinerary_details = $data['booking_itinerary_details'][0];
$extra_details = json_decode($itinerary_details['passenger_details']);

$request = json_decode($data['booking_itinerary_details'][0]['request'],true);
// $adult_count = array_sum(explode(',',$request['adults'][0]));
// $child_count = array_sum(explode(',',$request['childs'][0]));
$adult_count = $itinerary_details['adult'];
$child_count = $itinerary_details['child'];

$domain_details = $booking_details;

if($booking_details['added_by_type']=='Supplier'){
   $module = 'supplier';
}else{
   $module = 'supervision';
}

$img_url    = 'http://' . $_SERVER ['HTTP_HOST'].APP_ROOT_DIR.'/'.$module.'/';
$image_baseurl =$img_url.'uploads/hotel_images/';

?>

<table style="border-collapse: collapse; background: #ffffff;font-size: 12pt; margin: 0 auto; font-family: arial;" width="100%" cellpadding="0" cellspacing="0" border="0">
  <tbody>
   <tr>
   <td style="border-collapse: collapse; padding:10px 20px 20px" ><table width="100%" style="border-collapse: collapse;" cellpadding="0" cellspacing="0" border="0">
			<tr><td style="font-size:15pt; line-height:30px; width:100%; display:block; font-weight:600; text-align:center">E-Ticket</td></tr>
			<tr>
					<td><table width="100%" style="border-collapse: collapse;" cellpadding="0" cellspacing="0" border="0">
			     <tr>
					<td style="padding: 10px;width:65%;"><img style="width:150px;" src="<?=$GLOBALS['CI']->template->domain_images($data['logo'])?>"></td>
					<td style="padding: 10px;width:35%"><table width="100%" style="border-collapse: collapse;text-align: right; font-size:10pt" cellpadding="0" cellspacing="0" border="0">         		
	                     <tr>
	                        <td style="padding-bottom:10px;line-height:20px" align="right"><span>Booking Reference:<?php echo $booking_details['parent_pnr']; ?></span><br><span>Booked Date : <?php echo date("d M Y",strtotime($booking_details['creation_date'])); ?></span></td>
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
                 </strong>
              </td>
           </tr>
            <tr><td style="line-height:6px;">&nbsp;</td></tr>                  
                           <tr>
                              <td style="padding:0;"><table width="100%" cellpadding="5" style="padding: 10px;font-size: 10pt;padding:5px;">
                                    <tbody>
                                    <tr>
                                       <?php if($booking_details['thumb_image']):?>
                                          <td width="26%" style="padding:10px 0"><img style="width:130px; height:107px;" src="<?=$image_baseurl.'/'.$booking_details['thumb_image'];?>" /></td>
                                       <?php else:?>
                                          <td style="padding:10px 0"><img style="width:130px; height:107px;>" src="<?=$GLOBALS['CI']->template->template_images("default_hotel_img.jpg");?>" /></td>
                                       <?php endif;?>
                                       <td width="40%" valign="top" style="padding:10px;line-height:18px;"><span style="font-size:12pt;color:#00a9d6;vertical-align:middle;font-weight: 600;"><?php echo $booking_details['hotel_name']; ?></span><br><span style="display: block;font-size: 10pt;"><?php echo $booking_details['hotel_address']; ?> </span><br><br><span style="display: block;font-size: 10pt;"><img style="width:50px;" src="<?php echo $GLOBALS['CI']->template->template_images('star_rating-'.(empty($booking_details["star_rating"]) ? 0 : $booking_details["star_rating"]).'.png'); ?>" /></span></td>

                                       <td width="34%" style="padding:10px 0;text-align: center;line-height:25px;"><table style="border:2px solid #808080;">
                                       	<tbody>
                                       		<tr>
                                       			<td><span style="font-size:11pt; display:block"><span style="color:#00a9d6;padding:5px; display:block;text-transform:uppercase">Booking ID</span><br><span style="font-size:10pt;padding-bottom: 5px;display:block;font-weight: 600;"><?php echo $booking_details['pnr_no']; ?></span></span></td>
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
											<td style="background-color:#d9d9d9;color: #333333;"><strong>Check-In</strong></td>
											<td style="background-color:#d9d9d9;color: #333333;"><strong>Check-Out</strong></td>
											<td align="center" style="background-color:#d9d9d9;color: #333333;"><strong>No of Room's</strong></td>
											<td style="background-color:#d9d9d9;color: #333333;"><strong>Room Type</strong></td>
											<td align="center" style="background-color:#d9d9d9;color: #333333;"><strong>Adult's</strong></td>
											<td align="center" style="background-color:#d9d9d9;color: #333333;"><strong>Children</strong></td>							   
									    </tr>									    
									    <tr>
											<td><?=@date("d M Y",strtotime($itinerary_details['check_in_date']))?></td>
											<td><?=@date("d M Y",strtotime($itinerary_details['check_out_date']))?></td>
											<td align="center"><?php echo $itinerary_details['room_count']; ?></td>
											<td><?php echo $itinerary_details['room_type_name']; ?></td>
											<td align="center"><?php echo $adult_count; ?></td>
											<td align="center"><?php echo $child_count; ?></td>
									    </tr>
									</table>
									</td>
							</tr>
							<tr><td style="line-height:2px;">&nbsp;</td></tr>

                           <tr>
                              <td style="background-color:#666666;border: 1px solid #666666; color:#fff; font-size:9pt; padding:5px;"><img width="12" style="vertical-align:middle" src="<?=SYSTEM_IMAGE_DIR.'people_group.png'?>" /> <span style="font-size:9pt;color:#fff;vertical-align:middle;"> &nbsp;Guest Details</span></td>
                           </tr>
                           <tr>
								<td width="100%" style="border: 1px solid #666666;padding:0"><table width="100%" cellpadding="5" style="padding: 10px;font-size: 9pt;">	 
                                  <tr>
                                       <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Lead Passenger Name</td>
                                       <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Mobile</td>
                                       <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Email</td>
                                       <td style="background-color:#d9d9d9;padding:5px;color: #333333;">City</td>
                                    </tr>

                                       <tr>
                                       <td><?php echo $itinerary_details['contact_fname'].' '.$itinerary_details['contact_mname'].' '.$itinerary_details['contact_sur_name'];?></td>
                                          <td><?php echo $itinerary_details['contact_mobile_number'];?></td>
                                          <td><?php echo $itinerary_details['contact_email'];?></td>
                                          <td><?php echo $extra_details->billing_city;?></td>
                                           
                                    </tr>  
                              <!--      <?php
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
                                       ?>
                                       <td style="padding:5px;"><?=$age?></td>                                       
                                      
                                    </tr>
                                    <?php endforeach;?>  -->
                                 </table>
                              </td>
                           </tr>
							<tr>
                <td width="100%" style="padding:0"><table width="100%" cellpadding="5" style="padding: 0;font-size: 9pt;">   
                    <tbody>
                      <tr>
                        <td><table cellspacing="0" cellpadding="5" width="100%" style="font-size:9pt; padding:0;border:1px solid #9a9a9a;">
                            <tbody>
                              <tr>
                                <td style="border-bottom:1px solid #ccc;padding:5px;"><span style="font-size:10pt">Payment Details</span></td>
                                <td style="border-bottom:1px solid #ccc;padding:5px;"><span style="font-size:9pt">Amount (<?=$booking_details['currency']?>)</span></td>
                              </tr>
                              <tr>
                                <td style="padding:5px"><span>Base Fare</span></td>
                                <td style="padding:5px"><span><?php echo $booking_details['admin_currency']; ?> <?php echo number_format($booking_details['total_room_price'], 2); ?></span></td>
                              </tr>
                              <tr>
                                <td style="padding:5px"><span>Taxes</span></td>
                                <td style="padding:5px"><span><?php echo $booking_details['admin_currency']; ?> <?php echo number_format($itinerary_details['tax_rate_info_id'], 2); ?></span></td>
                              </tr>
                              <tr>
                                <td style="padding:5px"><span>Discount</span></td>
                                <td style="padding:5px"><span><?php echo $booking_details['admin_currency']; ?> <?php echo number_format($booking_details['discount_amount'], 2); ?></span></td>
                              </tr>
                              
                              <tr>
                                <td style="border-top:1px solid #ccc;padding:5px"><span style="font-size:10pt">Total Fare</span></td>
                                <td style="border-top:1px solid #ccc;padding:5px;font-size:10pt;"><span style=""><?php echo $booking_details['admin_currency']; ?> <?php echo number_format($booking_details['total_room_price'], 2); ?></span></td>
                              </tr>
                            </tbody>
                          </table>
                        </td>
                        <td><table cellspacing="0" cellpadding="5" width="100%" style="border:1px solid #9a9a9a;font-size:10pt; padding:0;">

                            <tbody>
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
                            </tbody>
                          </table>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
              <!-- <tr><td align="center" style="border-bottom:1px solid #999999;padding-bottom:15px"><span style="font-size:10pt; color:#555;">Customer Contact Details | E-mail : <?=$customer_details[0]['email']?> | Contact No : <?=$customer_details[0]['phone']?></span></td></tr> -->
              <tr><td style="line-height:2px;">&nbsp;</td></tr>
              <tr>
              <td><span style="font-size: 10pt;line-height:12px;font-weight: 500;">Cancellation Policy</span></td></tr>
              <tr>
                <td style="font-size:9pt; color:#555; line-height:16px"><?=$booking_details['cancellation_policy'][0]?></td>
              </tr>
              <tr><td style="line-height:2px;">&nbsp;</td></tr>
              <tr>
              <td><span style="font-size: 10pt;line-height:12px;font-weight: 500;">Terms and Conditions</span></td></tr>
              <tr>
                <td style="border-bottom:1px solid #999999; padding-bottom:15px; font-size:9pt; color:#555;line-height:16px">1.Please ensure that operator BookingID is filled, otherwise the ticket is not valid.</td>
              </tr>
              <tr><td style="line-height:2px;">&nbsp;</td></tr>
              <tr>
                <td align="right" style="padding-top:10px;font-size:9pt;line-height:16px"><?=$data['domainname']?><br>ContactNo : <?=$data['phone']?><br><?=$data['address']?></td>
              </tr>			
							
			         </table>
			         </td>
			</tr>
			
			</table>
    </td>
   </tr>
  </tbody>
</table>  