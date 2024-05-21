<style>
th,
td {
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

    .clearfix,
    .fstfooter,
    .container,
    .btmfooter,
    #myModal,
    .topssec,
    .section_top {
        display: none !important;
    }
}
</style>

<?php
$logo = $GLOBALS['CI']->template->domain_images($logo);
//debug($details);
$itinerary_details = $details ['passenger_details'];
$price = $details['transaction_details'];
$attributes = $details ['transaction_details'];
$price_attribute = json_decode($price[0]['attributes'],true);
// debug($price_attribute);die;
$convenience_fee = $price_attribute['convenience_fee'];
$total = $price_attribute['Fare'];
$price = $details['transaction_details'];
$total =$price[0]['total_fare'];
// debug(get_application_default_currency());
// debug($price[0]['currency']);
//debug($attributes);exit();
$currency_obj_custom = new Currency(array('module_type' => 'sightseeing', 'from' =>get_application_default_currency() , 'to' =>$price[0]['currency']));

$price[0]['discount'] = isset($price[0]['discount'])? get_converted_currency_value ( $currency_obj_custom->force_currency_conversion ( $price[0]['discount']) ):0;

$package = $details['package_details'];
//debug($details);exit;
$currency_obj = new Currency(array('module_type' => 'sightseeing', 'from' => $price[0]['currency'], 'to' =>get_application_default_currency()));
$booking_details=$details['booking_details'][0];
$customer_details=$details['passenger_details'];
//debug($details);die;
?>
<div class="table-responsive">
    <table style="    border-collapse: collapse;
    background: #ffffff;
    border: 15px solid #fff;
    font-size: 13px;
    line-height: 18px;
    margin: 15px auto;
    font-family: arial;
    max-width: 900px;" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tbody>
            <tr>
                <td style="border-collapse: collapse; padding: 10px 20px 20px">
                    <table width="100%" style="border-collapse: collapse;" cellpadding="0" cellspacing="0" border="0">


                        <tr>
                            <td style="padding: 10px;">
                                <table cellpadding="0" cellspacing="0" border="0" width="100%"
                                    style="border-collapse: collapse;">
                                    <tr>
                                        <td
                                            style="font-size:22px; line-height:30px; width:100%; display:block; font-weight:600; text-align:center;font-family:'Open Sans', sans-serif">
                                            Confirmation Voucher</td>
                                    </tr>



                                    <tr>
                                        <td>
                                            <table width="100%" style="border-collapse: collapse;" cellpadding="0"
                                                cellspacing="0" border="0">
                                                <tbody>
                                                    <tr>
                                                        <td style="padding: 0px;"><img style="width:130px;"
                                                                src="<?='https://www.travelsoho.com/travel-free-travels/extras/custom/TMX1512291534825461/images/TMX1512291534825461logo-loginpg.png'?>"
                                                                alt=""></td>
                                                        <td style="padding: 0px;">
                                                            <table width="100%"
                                                                style="font-size:13px; font-family: 'Open Sans', sans-serif;border-collapse: collapse;text-align: right; line-height:15px;"
                                                                cellpadding="0" cellspacing="0" border="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="padding-bottom:10px;line-height:20px"
                                                                            align="right"><span>Travel Date:
                                                                                <?=date("l\, jS F Y",strtotime($booking_details['travel_date']));?></span><br><span>Booking
                                                                                Reference:
                                                                                <?=$booking_details['app_reference']?></span><br><span>Transfers
                                                                                Code:
                                                                                <?=$details['package_details'][0]['package_code']?></span>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                   
 <?php
                                    if($booking_details['status']=="")
                                    {
                                        $booking_details['status']="BOOKING_INPROGRESS";
                                    }
                                   ?>
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
                                                     <img style="width:160px;height:107px;" src="<?=$attributes['ProductImage']?>" alt="">
                                                  <?php else:?>
                                                      <img style="width:160px;height:107px;" src="<?=$GLOBALS['CI']->template->template_images('no_image_available.jpg')?>" alt="">
                                                  <?php endif;?>
                                                </td>
                                                <td valign="top" style="padding:10px;"><span style="line-height:22px;font-size:16px;color:#2196f3;vertical-align:middle;font-weight: 600;"><?=$details['package_details'][0]['package_name']?></span><br><span style="display: block;line-height:22px;font-size: 13px;"><?=$details['package_details'][0]['city']?></span><br><span style="display: block;line-height:22px;font-size: 13px;"><img style="width:70px;" src="<?php echo $GLOBALS['CI']->template->template_images('star_rating-5.png'); ?>"></span></td>
                                              
                                             </tr>
                                          </tbody>
                                       </table>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td style="line-height:12px;">&nbsp;</td>
                                 </tr>
                                 <tr>
                                    <td style="background-color:#00a9d6;border: 1px solid #00a9d6; color:#fff; font-size:14px; padding:5px;"><img style="vertical-align:middle" src="<?=SYSTEM_IMAGE_DIR.'hotel_v.png'?>" alt=""> <span style="font-size:14px;color:#fff;vertical-align:middle;"> &nbsp; Details</span></td>
                                 </tr>
                                 <tr>
                                    <td width="100%" style="border: 1px solid #00a9d6; padding:0px;">
                                       <table width="100%" cellpadding="5" style="padding: 10px;font-size: 13px;padding:5px;">
                                          <tbody>
                                             <tr>
                                                <!-- <td>Phone</td> -->                                   
                                                <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Supplier</td>
                                                <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Supplier Email</td>
                                                <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Supplier Phone Number</td>
                                              
                                             </tr>
                                             <tr>
                                                <td style="padding:5px"><span style="width:100%; float:left"><?php echo $supplier_details[0]['first_name'].' '.$supplier_details[0]['last_name'] ?></span></td>
                                                <td style="padding:5px"><?php echo provab_decrypt($supplier_details[0]['email']) ?></span></td>
                                               <td style="padding:5px"><?php echo $supplier_details[0]['phone'] ?></td>

                                             </tr>
                                          </tbody>
                                       </table>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td style="line-height:12px;">&nbsp;</td>
                                 </tr>
                                 <tr>
                                    <td style="background-color:#666666;border: 1px solid #666666; color:#fff; font-size:14px; padding:5px;"><img style="vertical-align:middle" src="<?=SYSTEM_IMAGE_DIR.'people_group.png'?>" alt=""> <span style="font-size:14px;color:#fff;vertical-align:middle;"> &nbsp;Traveller(s) Details</span></td>
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
                                                <td style="padding:5px"><?=$name['title'].'.  '.$name['first_name'].' '.$name['last_name']?></td>
                                                <td style="padding:5px;"><?=$name['passenger_type']?></td>                            
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
                                                <td width="50%" style="padding:0;padding-right:14px;display: block;width: 100%;">
                                                   <table cellspacing="0" cellpadding="5" width="100%" style="font-size:12px; padding:0;border:1px solid #9a9a9a;">
                                                      <tbody>
                                                         <tr>
                                                         <td style="background-color:#666666;border: 1px solid #666666; color:#fff; font-size:14px; padding:5px;"> <span style="font-size:14px;color:#fff;vertical-align:middle;">Payment Details</span></td>
                                                         <td style="background-color:#858585;border: 1px solid #858585; color:#fff; font-size:14px; padding:5px;"> <span style="font-size:14px;color:#fff;vertical-align:middle;">Amount (<?=$booking_details['currency']?>)</span></td>
                                                         </tr>                                                         
                                                         <tr>
                                                            <td style="padding:5px"><span>Base Fare</span></td>
                                                            <td style="padding:5px"><span><?php echo roundoff_number($price_attribute['Fare']); ?></span></td>
                                                         </tr>
                                                         <tr>
                                                            <td style="padding:5px"><span>convenience_fee</span></td>
                                                            <td style="padding:5px"><span><?=roundoff_number($price_attribute['convenience_fee'])?></span></td>
                                                         </tr>
                                                        <?php if($itinerary_details['gst'] > 0){?>
                                                        
                                                        <?php } ?>
                                                         <tr>
                                                            <td style="padding:5px"><span>Discount</span></td>
                                                            <td style="padding:5px"><span><?php echo roundoff_number($price[0]['discount']); ?></span></td>
                                                         </tr>
                                                         <tr>
                                                            <td style="border-top:1px solid #ccc;padding:5px"><span style="font-size:13px">Total Fare</span></td>
                                                            <td style="border-top:1px solid #ccc;padding:5px"><span style="font-size:13px"><?php echo roundoff_number($price_attribute['Fare']+$price_attribute['convenience_fee']-$price[0]['discount']); ?></span></td>
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
                                    <td style="line-height:12px;">&nbsp;</td>
                                 </tr>


                               
                                 <tr>
                                    <td style="line-height:12px;">&nbsp;</td>
                                 </tr>
                                 <tr>
                                    <td colspan="4"><span style="line-height:26px;font-size: 15px;font-weight: 500;">Cancellation Advance</span></td>
                                 </tr>
                                 <tr>
                                    <td colspan="4" style="line-height:20px; font-size:12px; color:#555"><?=$details['cancellation_details'][0]['cancellation_advance']?></td>
                                 </tr>
                                  <tr>
                                    <td colspan="4"><span style="line-height:26px;font-size: 15px;font-weight: 500;">Cancellation Penalty</span></td>
                                 </tr>
                                 <tr>
                                    <td colspan="4" style="line-height:20px; font-size:12px; color:#555"><?=$details['cancellation_details'][0]['cancellation_penality']?></td>
                                 </tr>
                                 <tr>
                                    <td colspan="4"><span style="line-height:26px;font-size: 15px;font-weight: 500;">Price Includes</span></td>
                                 </tr>
                                 <tr>
                                    <td colspan="4" style="line-height:20px; font-size:12px; color:#555"><?=$details['price_info'][0]['price_includes']?></td>
                                 </tr>
                                 <tr>
                                    <td colspan="4"><span style="line-height:26px;font-size: 15px;font-weight: 500;">Price Excludes</span></td>
                                 </tr>
                                 <tr>
                                    <td colspan="4" style="line-height:20px; font-size:12px; color:#555"><?=$details['price_info'][0]['price_excludes']?></td>
                                 </tr>
                                 <tr>
                                    <td style="line-height:12px;">&nbsp;</td>
                                 </tr>
                                 <tr>
                                    <td colspan="4"><span style="line-height:26px;font-size: 15px;font-weight: 500;">Transfers Information</span></td>
                                 </tr>
                                 <tr>
                                    <td colspan="4" style="line-height:20px; padding-bottom:15px; font-size:12px; color:#555">
                                      <span style="line-height: 23px;font-size: 13px;font-weight: 500;color: #333;">Transfers Description</span><br><?php echo $details['package_details'][0]['package_description'] ?><br>
                                      <span style="line-height: 23px;font-size: 13px;font-weight: 500;color: #333;">Information</span>
                                      <ul>
                                      <?php foreach($additional_info as $ad):?><li><?=$ad?></li><?php endforeach;?></ul>
                                      

                                      <ul><?php foreach($exclustions as $exclu):?><li><?=$exclu?></li><?php endforeach;?></ul>
                                    </td>
                                 </tr>                                 
                                 <tr>
                                    <td colspan="4"><span style="line-height:26px;font-size: 15px;font-weight: 500;">Terms and Conditions</span></td>
                                 </tr>
                                 <tr>
                                    <td colspan="4" style="line-height:20px; border-bottom:1px solid #999999; padding-bottom:15px; font-size:12px; color:#555"> <?php echo $data['terms_conditions']; ?></td>
                                 </tr>
                                 
                                   <tr>
                              <td colspan="4" align="right" style="padding-top:10px;font-size:13px;line-height:20px;"><?=strtoupper($data['domainname'])?><br>Phone : <?=$data['phone']?><br>Email : <?=$data['email']?><br>Address : <?=STATIC_ADDRESS;?><br><?=STATIC_COUNTRY;?></td>
                          </tr>
                                  

                                  
                                  
                                 
                                   
                      


		



          
          
    </table>
    </td>
    </tr>
    <?php 
					 
					 if($booking_status!='BOOKING_CANCELLED'){
					 if($email_status){ ?>

    <!-- <tr><td><a href="<?=base_url()?>index.php/report/crs_cancel/<?=$details['booking_details'][0]['app_reference']?>/<?=$details['booking_details'][0]['booking_source']?>" class="viwedetsb">Cancel</a></td></tr> -->

    <?php }}
                     ?>
    <tr>
        <td>
            <div class="foot_bottom" style="display:none;">
                <ul>
                    <!-- <li class="list-unstyled">      <p>Follow us on social media!</p>      </li> -->
                    <li class="list-unstyled"><a class="col_fb" href="https://www.facebook.com"><i
                                class="fab fa-facebook-f"></i></a></li>
                    <li class="list-unstyled"><a class="col_twt" href="https://twitter.com"><i
                                class="fab fa-twitter"></i></a></li>
                    <li class="list-unstyled"><a class="col_istg" href="https://www.instagram.com"> <i
                                class="fab fa-instagram"></i></a></li>
                    <li class="list-unstyled"><a class="col_lin" href="https://www.linkedin.com"><i
                                class="fab fa-linkedin-in"></i></a></li>
                    <li class="list-unstyled"><a class="col_lin" href="https://www.youtube.com"><i
                                class="fab fa-youtube"></i></a></li>
                </ul>
            </div>
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