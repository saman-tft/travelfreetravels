<style>
th,td{padding:5px;}
</style>
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

?>
<div class="table-responsive">
  <table style="    border-collapse: collapse;
    background: #f5f5f5;
    border: 15px solid #fff;
    font-size: 13px;
    line-height: 18px;
    margin: 0 auto;
    font-family: arial;
    max-width: 900px;  " width="100%" cellpadding="0" cellspacing="0" border="0">
    <tbody>
      <tr>
        <td style="border-collapse: collapse; padding:10px 20px 20px" >
          <table width="100%" style="border-collapse: collapse;" cellpadding="0" cellspacing="0" border="0">
            <tr>
              <td style="padding: 0px;">
                <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-collapse: collapse;">
                  <tr>
                    <td  colspan="2" style="font-size:22px; line-height:30px; font-weight:600; text-align:center">E-Ticket</td>
                  </tr>
                  <tr style="">
                    <td width="50%" style="padding: 5px 0px;
    line-height: 17px;">
                      <p style="margin-bottom: 6px;font-size:14px;font-weight: 500;line-height: 19px;">Tripmia.com.au<br>
ABN# 21615437002<br>
PO Box 5034<br>
Kingsdene NSW 2118<br>
61879792323<br>
contact@tripmia.com.au</p>
                    </td>
                          <td style="width:50%; text-align: right; "><img style="    max-width: 265px;
    height: 100px;
   " src="<?=$GLOBALS['CI']->template->domain_images($data['logo'])?>"></td>
                         
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr>
                  	<td  width="50%" style="float: left; font-size: 25px; font-weight: 600; line-height: 80px;">Your Trip</td>
                  
                  </tr>
                  <tr>
                    <td width="50%" style="padding: 10px;border: 1px solid #cccccc; font-size: 14px; font-weight: bold;">Hotel Booking Lookup</td>
                  </tr>
                  <tr>
                    <td style="border: 1px solid #cccccc;">
                      <table width="100%" cellpadding="5" style="padding: 10px;font-size: 13px;">
                        <tr>
                          <td style="width: 50%;"><strong>Booking Reference</strong></td>
                           <td  style="width: 50%;"><?php echo $booking_details['parent_pnr']; ?></td>
                        </tr>
                        <tr>
                          <td style="width: 50%;"><strong>Booking ID</strong></td>
                          <td style="width: 50%;"><?php echo $booking_details['pnr_no']; ?></td>
                        </tr>
                        <tr>
                          <td style="width: 50%;"><strong>Booking Date</strong></td>
                           <td style="width: 50%;"><?php echo date("d M Y",strtotime($itinerary_details['book_date'])); ?></td>
                        </tr>
                        <tr>
                          <td style="width: 50%;"><strong>Status</strong></td>
                           <td style="width: 50%;">
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
                            </strong>
                          </td>
                        </tr>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td style="padding: 10px;border: 1px solid #cccccc; font-size: 14px; font-weight: bold;">Hotel Information</td>
                  </tr>
                  <tr>
                    <td  width="100%" style="border: 1px solid #cccccc;">
                      <table width="100%" cellpadding="5" style="padding: 10px;font-size: 13px;">
                        <tr>
                          <td width="50%"><strong>Hotel Name</strong></td>
                           <td  width="50%">
                            <?php echo $booking_details['hotel_name']; ?>
                          </td>
                        </tr>
                        <tr>
                          <td  width="50%"><strong>Hotel Address</strong></td>
                            <td  width="50%">
                            <?php echo $booking_details['hotel_address']; ?> 
                          </td>
                        </tr>
                          <!-- <td><strong>Phone</strong></td> -->
                        <tr>
                          <td  width="50%"><strong>Check-In</strong></td>
                          <td  width="50%"><span style="width:100%; float:left"> <?=@date("d M Y",strtotime($itinerary_details['check_in_date']))?></span></td>
                        </tr>
                        <tr>
                          <td  width="50%"><strong>Check-Out</strong></td>
                          <td width="50%"><span style="width:100%; float:left">   <?=@date("d M Y",strtotime($itinerary_details['check_out_date']))?></span></td>
                        </tr>
                        <tr>
                          <td width="50%"><strong>No of Room's</strong></td>
                           <td  width="50%"><?php echo $itinerary_details['room_count']; ?></span></td>
                        </tr>
                        <tr>
                          <td width="50%"><strong>Room Type</strong></td>
                          <td  width="50%"><?php echo $itinerary_details['room_type_name']; ?></td>
                        </tr>
                        <tr>
                          <td width="50%"><strong>Adult's</strong></td>
                          <td width="50%"><?php echo $adult_count; ?></td>
                        </tr>
                        <tr>
                          <td  width="50%"><strong>Children</strong></td>
                               <td  width="50%"><?php echo $child_count; ?></td>
                          </tr>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td style="padding: 10px;border: 1px solid #cccccc; font-size: 13px; font-weight: bold;">Contact Information</td>
                  </tr>
                  <tr>
                    <td style="border: 1px solid #cccccc;">
                      <table width="100%" cellpadding="5" style="padding: 10px;font-size: 13px;">
                        <tr>
                          <td width="50%"><strong>Passenger Name</strong></td>
                          <td width="50%"><?php echo $itinerary_details['contact_fname'].' '.$itinerary_details['contact_mname'].' '.$itinerary_details['contact_sur_name'];?></td>
                        </tr>
                        <tr>
                          <td width="50%"><strong>Mobile</strong></td>
                          <td width="50%"><?php echo $itinerary_details['contact_mobile_number'];?></td>
                        </tr>
                        <tr>
                          <td width="50%"><strong>Email</strong></td>
                          <td width="50%"><?php echo $itinerary_details['contact_email'];?></td>
                        </tr>
                        <tr>
                          <td width="50%"><strong>City</strong></td>
                           <td width="50%"><?php echo $extra_details->billing_city;?></td>
                        </tr>
                       
                      </table>
                    </td>
                    <td></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td style="padding: 10px;border: 1px solid #cccccc; font-size: 13px; font-weight: bold;">Price Summary</td>
                  </tr>
                  <tr>
                    <td style="border: 1px solid #cccccc;">
                      <table width="100%" cellpadding="5" style="padding: 10px;font-size: 13px;">
                        <tr>
                          <td width="50%"><strong>Base Fare</strong></td>
                           <td width="50%"><?php echo $booking_details['admin_currency']; ?> <?php echo number_format($booking_details['total_room_price'], 2); ?></td>
                          </tr>
                          <tr>
                            <td width="50%"><strong>Taxes</strong></td>
                             <td width="50%"><?php echo $booking_details['admin_currency']; ?> <?php echo number_format($itinerary_details['tax_rate_info_id'], 2); ?></td>
                          </tr>
                          <tr>
                            <td width="50%"><strong>Discount</strong></td>
                             <td width="50%"><?php echo $booking_details['admin_currency']; ?> <?php echo number_format($booking_details['discount_amount'], 2); ?></td>
                          </tr>
                          <tr>
                           <td width="50%"><strong>Total Fare</strong></td>
                             <td width="50%"><strong><?php echo $booking_details['admin_currency']; ?> <?php echo number_format($booking_details['total_room_price'], 2); ?></strong></td>
                         </tr>
                        
                      </table>
                    </td>
                    <td></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td width="50%" style="padding: 10px;border: 1px solid #cccccc; font-size: 13px; font-weight: bold;">Terms and Conditions</td>
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
            <tr><td style="border: none;"><div class="foot_bottom">      
            <ul class="social_icon">            <!--    <li class="list-unstyled">                    <p></p>                </li> -->                                        <li class="list-unstyled"><a class="col_fb" href="https://www.facebook.com/tripmiaa"><i class="fab fa-facebook-f"></i></a></li>                                        <li class="list-unstyled"><a class="col_twt" href="https://twitter.com/tripmiaa"><i class="fab fa-twitter"></i></a></li>                    
                                                <li class="list-unstyled"><a class="col_istg" href="https://www.instagram.com/tripmiaa"> <i class="fab fa-instagram"></i></a></li>                                                <li class="list-unstyled"><a class="col_lin" href="https://www.linkedin.com/tripmiaa"><i class="fab fa-linkedin-in"></i></a></li>                                                <li class="list-unstyled"><a class="col_lin" href="https://www.youtube.com/tripmiaa"><i class="fab fa-youtube"></i></a></li>                                    </ul>      
      </div></td></tr>
          </table>
        </td>
      </tr>
    </tbody>
  </table>
  <table id="printOption"  onclick="document.getElementById('printOption').style.visibility = ''; print(); return true;" style="border-collapse: collapse;font-size: 14px; margin: 10px auto; font-family: arial;" width="70%" cellpadding="0" cellspacing="0" border="0">
    <tbody>
      <tr>
        <td align="center"><input style="background:#418bca; height:34px; padding:10px; border-radius:4px; border:none; color:#fff; margin:0 2px;" type="button" value="Print" />
      </tr>
    </tbody>
  </table>
</div>