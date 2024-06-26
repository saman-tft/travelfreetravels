<?php 

  $booking_details = $data['booking_details'][0];
  // debug($booking_details['itinerary_details'][$booking_details['app_reference']][0]['total_fare']);
  // exit;
 //debug($pack_details); die;
 //debug($booking_details); die;
  $total_fare = $booking_details['admin_buying_price']+$booking_details['admin_markup'];
  $total_fare = $booking_details['basic_fare'];
  $taxes = $booking_details['agent_markup'];
 
  $itinerary_details = $data['booking_details'][0]['itinerary_details'][0];
  //debug($itinerary_details); die;

  $attributes = json_decode($booking_details['attributes'],true);
$path = base_url ( '../extras' ).'/custom/'.CURRENT_DOMAIN_KEY.'/uploads/activity/';

  if($attributes['Additional_info']){
      $additional_info = json_decode($attributes['Additional_info'],true);
  }else{
    $additional_info =array();
  }
  if($attributes['Inclusions']){
    $inclustions =json_decode($attributes['Inclusions'],true);   
  }else{
    $inclustions = array();
  }
 
  if($attributes['Exclusions']){
    $exclustions = json_decode($attributes['Exclusions'],true);
  }else{
    $exclustions = array();
    
    
  }
  if($attributes['Duration']){
    $duration = $attributes['Duration'];
  }else{
    $duration = '';
  }
   if($attributes['ShortDesc']){
    $desc = $attributes['ShortDesc'];
  }else{
    $desc = $attributes['ShortDesc'];
  }

if(isset($booking_details)){
    $app_reference = $booking_details['app_reference'];
  }
  if(isset($booking_details)){
    $booking_source = $booking_details['booking_source'];
  }
  if(isset($booking_details)){
    $status = $booking_details['status'];
  }
  if(isset($booking_details)){
    $lead_pax_email = $booking_details['lead_pax_email'];
  }
  
  $customer_details = $booking_details['customer_details'];
 

?>


<div class="table-responsive" style="width:100%; position:relative" id="tickect_hotel">
   <table class="table" cellpadding="0" cellspacing="0" width="100%" style="font-size:13px; font-family: 'Open Sans', sans-serif; width:900px; margin:0px auto;background-color:#fff; padding:50px 45px;">
      <tbody>
         <tr>
            <td style="border-collapse: collapse; padding:50px 35px;">
               <table width="100%" style="border-collapse: collapse;" cellpadding="0" cellspacing="0" border="0">
                  <tbody>
                     <tr>
                        <td style="padding: 0px;">
                           <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-collapse: collapse;">
                              <tbody>
                                 <tr>
                                    <td style="font-size:22px; line-height:30px; width:100%; display:block; font-weight:600; text-align:center">Voucher PDF</td>
                                 </tr>
                                 <tr>
                                    <td>
                                       <table width="100%" style="border-collapse: collapse;" cellpadding="0" cellspacing="0" border="0">
                                          <tbody>
                                            <?php $url=base_url(); 
                                                    $baseurl=str_replace('supervision/', '', $url);
                                                    // echo $baseurl."extras/custom/TMX1512291534825461/images/".;

                                              ?>
                                             <tr>
                                                <td style="padding: 0px;"><img style="width:200px;" src="<?php echo $baseurl."extras/custom/".CURRENT_DOMAIN_KEY."/images/".$GLOBALS['CI']->template->get_domain_logo();?>" alt="logo"></td>
                                                <td style="padding: 0px;">
                                                   <table width="100%" style="font-size:13px; font-family: 'Open Sans', sans-serif;border-collapse: collapse;text-align: right; line-height:15px;" cellpadding="0" cellspacing="0" border="0">
                                                    <tbody>
                                                         <tr>
                                                            <td style="padding-bottom:10px;line-height:20px" align="right"><span>Travel Date: <?=date("l\, jS F Y",strtotime($booking_details['date_of_travel']));?></span><br><span>Booking Reference: <?=$booking_details['app_reference']?></span><br><span>Excursion Code: <?=$pack_details->package_code?></span></td>
                                                         </tr>
                                                      </tbody>
                                                   </table>
                                                </td>
                                             </tr>
                                          </tbody>
                                       </table>
                                    </td>
                                 </tr>
                                 <tr>
                                  <?php 
                                  if($booking_details['status'] == 'CANCELLED'){
                                    $booking_details['status'] = 'BOOKING_CANCELLED';
                                  }
                                  ?>
                                    <td align="right" style="line-height:24px;font-size:13px;border-top:1px solid #00a9d6;border-bottom:1px solid #00a9d6;padding: 5px;">Status: <strong class="<?php echo booking_status_label( $booking_details['status']);?>" style=" font-size:14px;"><?php 
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
                      ?></strong>                              </td>
                                 </tr>
                                 <tr>
                                    <td style="line-height:12px;">&nbsp;</td>
                                 </tr>
                                 <tr>
                                    <td>
                                       <table width="100%" cellpadding="5" style="padding: 10px;font-size: 13px;padding:5px;">
                                          <tbody>
                                            <?php $url=base_url(); 
                                                    $imgurl=str_replace('voyages/supervision/', '', $url);

                                              ?>
                                             <tr>
                                                <td style="padding:10px 0">
                                                <?php if($pack_details->image):?>
                                                   <img style="width:160px;height:107px;" src="<?=$path.$pack_details->image?>"></td>
                                                <?php else:?>
                                                     <img style="width:160px;height:107px;" src="<?=$imgurl.$GLOBALS['CI']->template->template_images("no_image_available.jpg");?>"></td>
                                                <?php endif; ?>
                                                <td valign="top" style="padding:10px;"><span style="line-height:22px;font-size:16px;color:#00a9d6;vertical-align:middle;font-weight: 600;"><?=$booking_details['product_name']?></span><br><span style="display: block;line-height:22px;font-size: 13px;"><?=$attributes['Destination']?> </span><br><span style="display: block;line-height:22px;font-size: 13px;"><!-- <img style="width:70px;" src="<?php echo $GLOBALS['CI']->template->template_images('star_rating-'.$attributes["StarRating"].'.png'); ?>"> --></span></td>
                                                <td><span style="font-size:14pt;text-align: center; display:block"><span style="color:#00a9d6;padding:5px; display:block;text-transform:uppercase">Booking ID</span><br><span style="font-size:14pt;padding-bottom: 5px;display:block;font-weight: 600;"><?=$booking_details['app_reference']?></span></span></td>
                                                <!--<td width="32%" style="padding:10px 0;text-align: center;"><span style="font-size:14px; border:2px solid #808080; display:block"><span style="color:#00a9d6;padding:5px; display:block;text-transform:uppercase">Booking ID</span><span style="font-size:14px;line-height:35px;padding-bottom: 5px;display:block;font-weight: 600;"><?=$booking_details['app_reference']?></span></span></td> -->
                                             </tr>
                                          </tbody>
                                       </table>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td style="line-height:12px;">&nbsp;</td>
                                 </tr>
                                 <tr>
                                    <td style="background-color:#00a9d6;border: 1px solid #00a9d6; color:#fff; font-size:14px; padding:5px;"><img style="vertical-align:middle" src="<?=DOMAIN_LINK_URL.SYSTEM_IMAGE_DIR.'hotel_v.png'?>" /> <span style="font-size:14px;color:#fff;vertical-align:middle;"> &nbsp;Excursion Details</span></td>
                                 </tr>
                                 <tr>
                                    <td width="100%" style="border: 1px solid #00a9d6; padding:0px;">
                                       <table width="100%" cellpadding="5" style="padding: 10px;font-size: 13px;padding:5px;">
                                          <tbody>
                                             <tr>
                                                <!-- <td>Phone</td> -->                                   
                                                <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Supplier</td>
                                                <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Supplier Phone No</td>
                                                <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Duration</td>
                                                <td style="background-color:#d9d9d9;padding:5px;color: #333333;text-align:center">Total Traveler(s)</td>
                                             </tr>
                                             <tr>
                                                <td style="padding:5px"><span style="width:100%; float:left"> <?=$pack_details->package_name?></span></td><!-- <td style="padding:5px"><span style="width:100%; float:left"> <?=$attributes['SupplierName']?></span></td> -->
                                                <td style="padding:5px"><span style="width:100%; float:left">  <!--  <?=$attributes['SupplierPhoneNumber']?> --> <?=$data['phone']?></span></td>
                                                <?php 
                $total_travell_count = $booking_details['adult_count']+$booking_details['child_count'] +$booking_details['senior_count']+$booking_details['youth_count']+$booking_details['infant_count'];
                $total_travell_count =count($booking_details['customer_details']);
                ?>
                                                <td style="padding:5px"><?=$pack_details->duration?></td>
                                                <td style="padding:5px" align="center"><?=$total_travell_count?></td>
                                             </tr>
                                          </tbody>
                                       </table>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td style="line-height:12px;">&nbsp;</td>
                                 </tr>
                                 <tr>
                                    <td style="background-color:#666666;border: 1px solid #666666; color:#fff; font-size:14px; padding:5px;"><img style="vertical-align:middle" src="<?=DOMAIN_LINK_URL.SYSTEM_IMAGE_DIR.'people_group.png'?>"> <span style="font-size:14px;color:#fff;vertical-align:middle;"> &nbsp;Traveller(s) Details</span></td>
                                 </tr>
                                 <tr>
                                    <td width="100%" style="border: 1px solid #666666; padding:0px;">
                                       <table width="100%" cellpadding="5" style="padding: 10px;font-size: 13px;">
                                          <tbody>
                                             <tr>
                                                <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Sr No.</td>
                                                <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Passenger(s) Name</td>
                                                <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Type</td>
                                             </tr>
                                             <?php $i=1;?>  
                                             <?php foreach($customer_details as $name): ?>                               
                                             <tr>
                                                <td style="padding:5px;"><?=$i?></td>
                                                <td style="padding:5px"><?=@$name['title'].'  '.$name['first_name'].' '.$name['last_name']?></td>
                                               <!--  <td style="padding:5px;"><?=$name['pax_type']?></td>  -->
                                                <td style="padding:5px;"><?=$name['gender']?></td>                            
                                             </tr>
                                              <?php $i++;?>
                                             <?php endforeach;?>
                                          </tbody>
                                       </table>
                                    </td>
                                    <td></td>
                                 </tr>
                                 <tr>
                                    <td style="line-height:12px;">&nbsp;</td>
                                 </tr>
                                 <tr>
                                    <td colspan="4" style="padding:0;">
                                       <table cellspacing="0" cellpadding="5" width="100%" style="font-size:12px; padding:0;">
                                          <tbody>
                                             <tr>
                                               <!-- <td width="50%" style="padding:0;padding-right:14px;">
                                                   <table cellspacing="0" cellpadding="5" width="100%" style="font-size:12px; padding:0;border:1px solid #9a9a9a;">
                                                      <tbody>
                                                         <tr>
                                                            <td style="border-bottom:1px solid #ccc;padding:5px;"><span style="font-size:13px">Payment Details</span></td>
                                                            <td style="border-bottom:1px solid #ccc;padding:5px;"><span style="font-size:11px">Amount (<?=$booking_details['currency_code']?>)</span></td>
                                                         </tr>
                                                         <tr>
                                                            <td style="padding:5px"><span>Base Fare</span></td>
                                                            <td style="padding:5px"><span><?php echo roundoff_number($total_fare); ?></span></td>
                                                         </tr> -->
                                                       <!--   <tr>
                                                            <td style="padding:5px"><span>Taxes</span></td>
                                                            <td style="padding:5px"><span><?=$taxes?></span></td>
                                                         </tr> -->
                                                  <!--        <?php if($itinerary_details['gst'] > 0){?>
                                                         <tr>
                                                            <td style="padding:5px"><span>GST</span></td>
                                                            <td style="padding:5px"><span><?php echo round($itinerary_details['gst']); ?></span></td>
                                                         </tr>
                                                        <?php } ?> -->
                                                       <!--   <tr>
                                                            <td style="padding:5px"><span>Discount</span></td>
                                                            <td style="padding:5px"><span><?php echo roundoff_number($booking_details['discount']); ?></span></td>
                                                         </tr> -->
                                                        <!-- <tr>
                                                            <td style="border-top:1px solid #ccc;padding:5px"><span style="font-size:13px">Total Fare</span></td>
                                                            <td style="border-top:1px solid #ccc;padding:5px"><span style="font-size:13px"><?php 
                                                            echo $total_fare; //echo roundoff_number($booking_details['product_total_price']+$itinerary_details['gst']+$booking_details['convinence_amount']-$booking_details['discount']); ?></span></td>
                                                         </tr>
                                                        
                                                      </tbody>
                                                   </table>
                                                </td> -->
                                                <td width="50%" style="padding:0;padding-left:14px; vertical-align:top">
                                               <!--     <table cellspacing="0" cellpadding="5" width="100%" style="border:1px solid #9a9a9a;font-size:12px; padding:0;">
                                                      <tbody>
                                                         <tr>
                                                            <td style="background-color:#d9d9d9;border-bottom:1px solid #ccc;padding:5px; color:#333"><span style="font-size:13px">Activity Inclusions</span></td>
                                                         </tr>
                                                         <?php if($inclustions):?>
                                                          <?php foreach($inclustions as $incl):?>
                                                         <tr>
                                                            <td style="padding:5px"><span><?=$incl?></span></td>
                                                         </tr>
                                                       <?php endforeach;?>
                                                     <?php else:?>
                                                      <tr>
                                                        <td style="padding:5px">Acitity Only</td>
                                                      </tr>
                                                        <?php endif;?>
                                                         
                                                      </tbody>
                                                   </table> -->
                                                </td>
                                             </tr>
                                          </tbody>
                                       </table>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td style="line-height:12px;">&nbsp;</td>
                                 </tr>
                                 <tr>
                                    <td align="center" colspan="4" style="border-bottom:1px solid #999999;padding-bottom:15px"><span style="font-size:13px; color:#555;">Customer Contact Details | E-mail : <?=$booking_details['lead_pax_email']?> | Contact No : <?=$booking_details['phone']?></span></td>
                                 </tr>
                                 <tr>
                                    <td style="line-height:12px;">&nbsp;</td>
                                 </tr>
                                 <tr>
                                    <td colspan="4"><span style="line-height:26px;font-size: 15px;font-weight: 500;">Cancellation Policy</span></td>
                                 </tr>
                                 <tr>
                                    <td colspan="4" style="line-height:20px; font-size:12px; color:#555"><?=$booking_details['cancellationPolicy']?></td>
                                 </tr>
                                 <tr>
                                    <td style="line-height:12px;">&nbsp;</td>
                                 </tr>
                                 <tr>
                                    <td colspan="4"><span style="line-height:26px;font-size: 15px;font-weight: 500;">Tour Information</span></td>
                                 </tr>
                                 <tr>
                                    <td colspan="4" style="line-height:20px; font-size:12px; color:#555"><?=$pack_details->infor_travellers?></td>
                                 </tr>
                                 <tr>
                                    <td colspan="4" style="line-height:20px; padding-bottom:15px; font-size:12px; color:#555">
                                      <span style="line-height: 23px;font-size: 13px;font-weight: 500;color: #333;">Tour Description</span><br><?=$pack_details->package_description?><br>
                                      <span style="line-height: 23px;font-size: 13px;font-weight: 500;color: #333;">Information</span>
                                      <ul>
                                      <?php foreach($additional_info as $ad):?><li><?=$ad?></li><?php endforeach;?></ul>
                                      <!--<span style="line-height: 23px;font-size: 13px;font-weight: 500;color: #333;">Exclusions</span>-->

                                      <ul><?php foreach($exclustions as $exclu):?><li><?=$exclu?></li><?php endforeach;?></ul>
                                    </td>
                                 </tr>                                 
                                 <tr>
                                    <td colspan="4"><span style="line-height:26px;font-size: 15px;font-weight: 500;">Terms and Conditions</span></td>
                                 </tr>
                                 <tr>
                                    <td colspan="4" style="line-height:20px; border-bottom:1px solid #999999; padding-bottom:15px; font-size:12px; color:#555">1.Please ensure that operator BookingID is filled, otherwise the ticket is not valid.</td>
                                 </tr>
                                 <tr>
                                    <td colspan="4" align="right" style="padding-top:10px;font-size:13px;line-height:20px;"><?=$data['domainname']?><br>ContactNo : <?=$data['phone']?><br><?=$data['address']?></td>
                                 </tr>
                              </tbody>
                           </table>
                        </td>
                     </tr>
                  </tbody>
               </table>
            </td>
         </tr>
      </tbody>
   </table>
  
</div>
