<?php 

  $booking_details = $data['booking_details'][0];
  // debug($booking_details);
  // exit;
 
  $itinerary_details = $data['booking_details'][0]['itinerary_details'][0];
  //debug($itinerary_details);

  $attributes = json_decode($booking_details['attributes'],true);

  //debug($s_search_params);
  if($attributes['Additional_info']){
      $additional_info = json_decode($attributes['Additional_info']);
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
   <table class="table" cellpadding="0" cellspacing="0" width="100%" style="font-size:13px; font-family: 'Open Sans', sans-serif; width:900px; margin:10px auto;background-color:#fff; padding:50px 45px;box-shadow: 0px 0px 8px rgb(0 0 0 / 15%);">
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
                                    <td style="font-size:22px; line-height:30px; width:100%; display:block; font-weight:600; text-align:center">Activity Voucher</td>
                                 </tr>
                                 <tr>
                                    <td>
                                       <table width="100%" style="border-collapse: collapse;" cellpadding="0" cellspacing="0" border="0">
                                          <tbody>
                                             <tr>
                                                <td colspan="2" style="padding: 0px;"><img style="width:200px;float: left;" src="https://www.travelfreetravels.com/extras/custom/TMX6244821650276433/images/TMX3644721637051232logo-loginpg.png"></td>
                                                <td colspan="2" style="padding: 0px;" align="right">
                                                   
                                                            <span>Travel Date: <?=date("l\, jS F Y",strtotime($booking_details['travel_date']));?></span><br><span>Booking Reference: <?=$booking_details['app_reference']?></span><br><span>Activity Code: <?=$booking_details['product_code']?></span>
                                                         
                                                </td>
                                             </tr>
                                          </tbody>
                                       </table>
                                    </td>
                                 </tr>
                                 <tr>
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
                                             <tr>
                                                <td style="padding:10px 0">
                                                  <?php if($attributes['ProductImage']):?>
                                                    <img style="width:160px;height:107px;" src="<?=$attributes['ProductImage']?>">
                                                  <?php else: ?>
                                                    <img style="width:160px;height:107px;" src="<?php echo $GLOBALS['CI']->template->template_images('no_image_available.jpg'); ?>">
                                                  <?php endif;?>
                                                </td>
                                                <td valign="top" style="padding:10px;"><span style="line-height:22px;font-size:16px;color:#00a9d6;;font-weight: 600;"><?=$booking_details['product_name']?></span><br><span style="display: block;line-height:22px;font-size: 13px;"><?=$attributes['Destination']?> </span><br><span style="display: block;line-height:22px;font-size: 13px;"><img style="width:70px;" src="<?php echo $GLOBALS['CI']->template->template_images('star_rating-'.$attributes["StarRating"].'.png'); ?>"></span></td>
                                                <td width="32%" style="padding:10px 0;text-align: center;"><span style="font-size:14px; border:2px solid #808080; display:block"><span style="color:#00a9d6;padding:5px; display:block;text-transform:uppercase">Booking ID</span><span style="font-size:14px;line-height:35px;padding-bottom: 5px;display:block;font-weight: 600;"><?=$booking_details['booking_reference']?></span></span></td>
                                             </tr>
                                          </tbody>
                                       </table>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td style="line-height:12px;">&nbsp;</td>
                                 </tr>
                                 <tr>
                                    <td style="background-color:#00a9d6;border: 1px solid #00a9d6; color:#fff; font-size:14px; padding:5px;text-align: left;"><img style="vertical-align:middle" src="<?=SYSTEM_IMAGE_DIR.'hotel_v.png'?>"> <span style="font-size:14px;color:#fff;"> &nbsp;Activity Details</span></td>
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
                                                <td style="padding:5px"><span style="width:100%; float:left"> <?=$attributes['SupplierName']?></span></td>
                                                <td style="padding:5px"><span style="width:100%; float:left">   <?=$attributes['SupplierPhoneNumber']?></span></td>
                                                <?php 
                $total_travell_count = $booking_details['adult_count']+$booking_details['child_count'] +$booking_details['senior_count']+$booking_details['youth_count']+$booking_details['infant_count'];
                ?>
                                                <td style="padding:5px"><?=$duration?></td>
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
                                    <td style="background-color:#00a9d6;border: 1px solid #00a9d6; color:#fff; font-size:14px; padding:5px;text-align: left;"><img style="vertical-align:middle" src="<?=SYSTEM_IMAGE_DIR.'people_group.png'?>"> <span style="font-size:14px;color:#fff;"> &nbsp;Traveller(s) Details</span></td>
                                 </tr>
                                 <tr>
                                    <td width="100%" style="border: 1px solid #00a9d6; padding:0px;">
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
                                                <td style="padding:5px"><?=$name['title'].'.  '.$name['first_name'].' '.$name['last_name']?></td>
                                                <td style="padding:5px;"><?=$name['pax_type']?></td>                            
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
                                                <td width="50%" style="padding:0;padding-right:14px;">
                                                   <table cellspacing="0" cellpadding="5" width="100%" style="font-size:12px; padding:0;border:1px solid #9a9a9a;">
                                                      <tbody>
                                                         <tr>
                                                            <td style="border-bottom:1px solid #ccc;padding:5px;"><span style="font-size:13px">Payment Details</span></td>
                                                            <td style="border-bottom:1px solid #ccc;padding:5px;"><span style="font-size:11px">Amount (<?=$booking_details['currency']?>)</span></td>
                                                         </tr>
                                                          <tr>
                                                            <td style="border-top:1px solid #ccc;padding:5px"><span style="font-size:13px">Base Fare</span></td>
                                                            <td style="border-top:1px solid #ccc;padding:5px"><span style="font-size:13px"><?php echo roundoff_number($booking_details['grand_total']-$booking_details['gst']); ?></span></td>
                                                         </tr> 
                                                         <?php if($booking_details['gst'] > 0){?>
                                                         <tr>
                                                            <td style="border-top:1px solid #ccc;padding:5px"><span style="font-size:13px">GST</span></td>
                                                            <td style="border-top:1px solid #ccc;padding:5px"><span style="font-size:13px"><?php echo roundoff_number($booking_details['gst']); ?></span></td>
                                                         </tr> 
                                                         <?php } ?>
                                                          <tr>
                                                            <td style="border-top:1px solid #ccc;padding:5px"><span style="font-size:13px">Total Fare</span></td>
                                                            <td style="border-top:1px solid #ccc;padding:5px"><span style="font-size:13px"><?php echo roundoff_number($booking_details['grand_total']); ?></span></td>
                                                         </tr>
                                                        
                                                       
                                                         
                                                      </tbody>
                                                   </table>
                                                </td>
                                                <td width="50%" style="padding:0;padding-left:14px; vertical-align:top">
                                                   <table cellspacing="0" cellpadding="5" width="100%" style="border:1px solid #9a9a9a;font-size:12px; padding:0;">
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
                                                   </table>
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
                                    <td align="center" colspan="4" style="border-bottom:1px solid #999999;padding-bottom:15px"><span style="font-size:13px; color:#555;">Customer Contact Details | E-mail : <?=$customer_details[0]['email']?> | Contact No : <?=$customer_details[0]['phone']?></span></td>
                                 </tr>
                                 <tr>
                                    <td style="line-height:12px;">&nbsp;</td>
                                 </tr>
                                 <tr>
                                    <td colspan="4" style="text-align: left;"><span style="line-height:26px;font-size: 15px;font-weight: 500;">Cancellation Policy</span></td>
                                 </tr>
                                 <tr>
                                    <td colspan="4" style="line-height:20px; font-size:12px; color:#555;text-align: left;"><?=$attributes['TM_Cancellation_Policy']?></td>
                                 </tr>
                                 <tr>
                                    <td style="line-height:12px;">&nbsp;</td>
                                 </tr>
                                 <tr>
                                    <td colspan="4" style="text-align: left;"><span style="line-height:26px;font-size: 15px;font-weight: 500;">Tour Information</span></td>
                                 </tr>
                                 <tr>
                                    <td colspan="4" style="line-height:20px; padding-bottom:15px; font-size:12px; color:#555;text-align: left;">
                                      <span style="line-height: 23px;font-size: 13px;font-weight: 500;color: #333;">Tour Description</span><br><?=$desc?><br>
                                      <span style="line-height: 23px;font-size: 13px;font-weight: 500;color: #333;">Information</span>
                                      <ul>
                                      <?php foreach($additional_info as $ad):?><li><?=$ad?></li><?php endforeach;?></ul>
                                      <span style="line-height: 23px;font-size: 13px;font-weight: 500;color: #333;">Exclusions</span>

                                      <ul><?php foreach($exclustions as $exclu):?><li><?=$exclu?></li><?php endforeach;?></ul>
                                    </td>
                                 </tr>                                 
                                 <tr>
                                    <td colspan="4" style="text-align: left;"><span style="line-height:26px;font-size: 15px;font-weight: 500;">Terms and Conditions</span></td>
                                 </tr>
                                 <tr>
                                    <td colspan="4" style="line-height:20px; border-bottom:1px solid #999999; padding-bottom:15px; font-size:12px; color:#555;text-align: left;"><?php echo $data['terms_conditions']; ?></td>
                                 </tr>
                                 <tr>
                                    <td colspan="4" style="text-align: left;"><span style="line-height:26px;font-size: 15px;font-weight: 500;">Note</span></td>
                                 </tr>
                                 <tr>
                                    <td colspan="4" style="line-height:20px; border-bottom:1px solid #999999; padding-bottom:15px; font-size:12px; color:#555;text-align: left;"><?php echo $data['note']; ?></td>
                                 </tr>
                                 <!-- <tr>
                                    <td colspan="4" align="right" style="padding-top:10px;font-size:13px;line-height:20px;"><?=$data['domainname']?><br>ContactNo : <?=$data['phone']?><br><?=$data['address']?></td>
                                 </tr> -->
                                 <tr>
                <td colspan="4" align="right" style="padding-top:10px;font-size:9pt;line-height:16px"><?=strtoupper($data['domainname'])?><br>Phone : <?=$data['voucher_phone']?><br>Email : <?=$data['voucher_email']?><br>Address : <?=STATIC_ADDRESS;?><br><?=STATIC_COUNTRY;?></td>
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
<?php if(!$email_send_check){ ?>
<div class="dwnprnt" style="text-align: center;">
    <a href="<?php echo base_url();?>" class="btn btn-grn btfl">Back</a>
    <?php if($booking_details['status']=="BOOKING_CONFIRMED" ){?>
    <button type="button" onClick="printDiv('tickect_hotel')" class="btn btn-orn btfl">Print</button>
    <?php }?>
</div>
<?php } ?>
<script type="text/javascript">
    function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
</script>