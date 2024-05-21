<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="stylesheet" href="http://192.168.0.50/nsw/admin-panel/assets/fonts/font-awesome/css/font-awesome.min.css">
  <title>Package Voucher</title>
  <style>
    @media print {
      .print {
        display: none;
      }
    }  
    td { vertical-align: top; }
  </style>
</head>

<body>
<?php 
// debug($booking_details);die;
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
<?php  if($menu == true){ ?>
  <div class="container">
    <div class="col-xs-12 text-center mt10">
      <ul class="list-inline">
        <li>
          <button class="btn-sm btn-primary print" onclick="window.print(); return true;">Print</button>
        </li>
        <li>
          <button type="button" class="btn-sm btn-primary btn-popup bnt_orange" data-toggle="collapse" data-target="#emailmodel" aria-expanded="false" aria-controls="markup_update">Email</button>
        </li>
        <li>
        <a href="<?php echo base_url () . 'index.php/voucher/holiday/'.$booking_details['app_reference'].'/'.$booking_details['status'].'/show_pdf';?>"  ><button class="btn-sm btn-primary pdf">PDF</button></a>
       </li>
       <li>
         <a href="<?php echo base_url () . 'index.php/report/holiday/';?>"  ><button class="btn-sm btn-primary pdf">Back</button></a>
       </li>
     </ul>
   </div>
 </div>
 <div class="collapse" id="emailmodel">
  <div class="well max_wd20">
    <h4>Send Email</h4>
    <form name="agent_email" method="post" action="<?php echo base_url () . 'index.php/voucher/holiday/'.$booking_details['app_reference'].'/'.$booking_details['status'].'/show_voucher/mail';?>">
      <input id="inc_sddress" value="1" type="hidden" name="inc_sddress">
      <input id="inc_fare" value="1" type="hidden" name="inc_fare">
      <div class="row">
        <label>Email Id </label><input id="email" placeholder="Please Enter Email Id" class="airlinecheckbox validate_user_register form-control" type="text" checked name="email">
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" value="Submit">Send Email</button>
      </div>
    </form>
  </div>
</div>
<?php } ?>
  <table class="table" bgcolor="#f5f5f5" style="border-collapse: collapse; background: #f5f5f5; border: 15px solid #fff; font-size: 12px; line-height: 18px; margin: 0 auto; font-family: arial; max-width:900px"
 width="100%" cellpadding="0" cellspacing="0" border="0">
    <tbody>
      <tr>
        <td style="padding:0px;">
          <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse;">
            

                    <tr bgcolor="#f5f5f5">
                            <td colspan="4" style="padding: 5px 9px; color: #333; line-height: normal;" class="logocvr">
                            <br><br>
                            <span style="margin: 0px; margin-top: 30px; font-size:9px; font-weight: 500;">Vivance Travels</span><br>

                         
                            <span style="margin: 0px; font-size:9px; color: #333;">   <?php
echo $domain_data['address'];
?></span><br>
                           <!--  <span style="margin: 0px; font-size:9px; color: #dfdfdf;">TICO #4631677</span><br> -->
                            <span style="color:#f5aa1c; margin-top: 9px; font-size: 9px; margin-bottom: 0px;">support@gmail.com</span>
                            </td>
                            <td colspan="4" style="padding:5px 9px;" align="right" class="logocvr"><img
                            style="max-width: 265px; height: 100px; margin-top: 20px;"
                            src="<?=$GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template->get_domain_logo())?>"
                            alt="" /></td>
                            </tr>


                            <tr>
                            <td colspan="4" style="padding: 5px 9px; color: #fff; line-height: 17px;" class="logocvr">
                            &nbsp;
                            </td>
                            </tr>

                  <tr>
                  <td colspan="4" style="padding: 5px 14px; color: #374c5d; font-size: 9px; line-height: 17px;" class="logocvr">
                  <span style="padding: 8px 0px; font-size: 11px;">Holiday booking reference number:</span><br>
                  <span style="font-weight: bold; font-size: 14px;"><?=$app_reference?></span>
                  </td>
                  <td bgcolor="#f89e2e" colspan="4" style="padding:5px 0px;" align="right" class="logocvr">
                  <span style="padding:11px 14px;  color: #fff; font-size:11px; line-height: normal; text-align: left;">Please present this voucher to the service provider.</span>
                  </td>
                  </tr>

                  <tr>
                            <td colspan="4" style="padding: 5px 9px; color: #fff; line-height: 17px;" class="logocvr">
                            &nbsp;
                            </td>
                            </tr>

                     <?php 
                     if($holiday_data['data'][0]['status'] == 'BOOKING_CONFIRMED')
                     {
                      ?>
                      <tr>
                       <td colspan="8" align="left" class="logocvr"><h4 style="padding: 14px 14px; color: #374c5d; margin: 0px; font-size: 15px; line-height: 20px; text-align: left; font-weight: bold; border-bottom: 2px solid #fff;">BOOKING CONFIRMATION</h4></td>
                     </tr>
                     <tr>
                      <td style="padding: 0px;" colspan="6" align="left" class="logocvr">
                       <!--  <h4 style="padding: 14px 14px; color: #374c5d; margin: 0px; line-height: 20px; text-align: left; font-weight: bold; font-size: 14px;">Accommodation Voucher</h4> -->
                        <span style="font-size: 11px;">Confirmed and guaranteed</span>
                      </td>
                    </tr>
                    <?php 
                  } 
                  ?>

         <tr>
          <td colspan="6" style="padding: 10px 14px;margin-top: 0px;width: 100%;font-size: 13px;color: #374c5d;line-height: 20px;">
           <span style="margin:0px; margin-bottom: 4px; font-weight: bold; font-size: 14px;">Package Name: <?=$package_details[0]['package_name']?></span>
           <br>
            <table cellpadding="0" cellspacing="0" style="color: #374c5d; font-size: 9px; width: 100%;">
        
              <tr>
               <td style="padding: 3px 0px; font-weight: bold;">Booking Date</td> 
               <td style="padding: 3px 5px;"><?=$holiday_data['data'][0]['created_datetime']?>
               </td>
              </tr>
             
             
              <tr>
               <td style="padding: 3px 0px; font-weight: bold;">Departure Date</td> 
               <td style="padding: 3px 5px;"><?=$holiday_data['data'][0]['date_of_travel']?>
               </td>
              </tr>
             
              <tr>
               <td style="padding: 3px 0px; font-weight: bold;">Duration</td> 
               <td style="padding: 3px 5px;"><?php
                      $duration = $package_details[0]['duration'].' Days';
                      
                    echo $duration?>
               </td>
              </tr>

              <tr>
               <td style="padding: 3px 0px; font-weight: bold;">Passenger Details</td> 
               <td style="padding: 3px 5px;">
               <?php
$customer_title = $attributes['name_title'];
                      $customer_first_name = $attributes['first_name'];
                        $customer_last_name = $attributes['last_name'];
                     $title = get_enum_list('title',$customer_title[0]);
$t=0;
               // <?=$name
                      if(count($customer_first_name)>1)
                      {
                        foreach($customer_first_name as $g)
                        {

                          echo $title.' '.$customer_first_name[$t].' '.$customer_last_name[$t].'<br>';
                          $t++;
                        }


                      }
 

                     ?>




               </td>
              </tr>
             
             </table>  
            </td>
           </tr>


           <tr>
                            <td colspan="4" style="padding: 5px 9px; color: #fff; line-height: 10px;" class="logocvr">
                            &nbsp;
                            </td>
                            </tr>

            


          <tr style="border-bottom:1px solid #fff; border-top:1px solid #fff;">
    <td colspan="6" width="100%" style="padding:20px 14px">
     <br> <br>
     <span style="margin-top:0; font-weight:bold; color:#374c5d; font-size: 9px; margin-bottom: 10px;">Fare Breakup</span>
     <br>
     <table style="width: 100%; color:#374c5d;">
      
      <tbody>
       
       <tr>
        <td style="font-size:9px; padding:0px; width: 40%; font-weight:normal;line-height:18px; font-weight: bold; vertical-align: middle;"><strong>Total Amount Due</strong> 

        </td>
        <td style="font-size:9px; padding:0px; width: 40%; text-align: right; vertical-align: top; font-weight:normal;line-height:18px;">
         <?php 
          $base_fare = $attributes['tour_totalamount'];
          $convinence_amount = $attributes['cv'];
         echo "<span style='margin:0px;'>Base Fare: ".$base_fare."</span><br>";
                      if ($attributes['tour_basefareadult']) {
                        echo '<span style="margin:0px;">Adult Fare : '. sprintf("%.2f", ceil($attributes['tour_basefareadult'])).'</span><br>';
                      }
                      if ($attributes['tour_basefarechild']) {
                        echo '<span style="margin:0px;">Child Fare : '. sprintf("%.2f", ceil($attributes['tour_basefarechild'])).'</span><br>';
                      }
                      if ($attributes['tour_basefareinfant']) {
                        echo '<span style="margin:0px;">Infant Fare : '. sprintf("%.2f", ceil($attributes['tour_basefareinfant'])).'</span><br>';
                      }
                      // if($booking_details['discount']){
                      //  echo "<p>Promocode Amount: ".$booking_details['discount']." ".$booking_details['currency_code']."</p>";
                      // }
                     ;
                      
                      echo "<span style='margin:0px;'>Taxes: ".($attributes['total_markup']+$attributes['gst'])."</span><br>";
                      echo "<span style='margin:0px;'>Convenience Fee: ".$convinence_amount."</span><br>";
                     if($attributes['discount']=='')
                      echo "<span style='margin:0px;'>Discount: 0</span><br>";
                    else
                      echo "<span style='margin:0px;'>Discount: ".$attributes['discount']."</span><br>";
                      ?>
                      <?php 
                      //$final_total = $booking_details['basic_fare']-$booking_details['discount']; 
 
                      $grand_total=$attributes['tour_totalamount']+$attributes['total_markup']+$attributes['gst']+$attributes['cv']-$attributes['discount'];
                      echo "<span style='margin:0px;'> <strong>Total Fare:".$grand_total."</strong></span><br>";
                      ?>
                      
        </td>



       </tr>
      </tbody>
     </table>
    </td>
   </tr> 

         
      </table>

                <table width="100%" border="0" cellpadding="6" cellspacing="0" style="border-collapse: collapse;">
                  <tr>
                    <td style="text-align:left; font-size:9px; color:#374c5d; font-weight: bold;">Itinerary Details                        
                    </td>
                  </tr>
                </table>
                <table width="100%"  border="0" cellpadding="6" cellspacing="5" style="border-collapse: collapse; font-size: 9px; color: #374c5d;">
                  <?php
      foreach ($package_details as $key => $itinary) {
      
        ?>
          <tr>
            <td width="10%" align="left" style="color: #374c5d;border:solid 1px #fff; font-weight:bold">Day <?php echo $day+1; ?></td>
            <td width="20%" style="border:solid 1px #fff"><strong>Visiting Place:</strong>
              <p style="margin-top:0; margin-bottom:5px;"><?php echo  $itinary['place']; ?></p>
            </td>
            <!-- <td width="20%" style="border:solid 1px #fff"><strong>Night Stay:</strong>
              <p style="margin-top:0; margin-bottom:5px;">
                <?php echo  $itinary['program_title']; ?>                      
              </p>
            </td> -->
            <td width="50%" style="border:solid 1px #fff"><strong>Description:</strong>
            <span style="margin:0;"><?php echo  $itinary['itinerary_description'];   ?></span><br>
           <!--  <span style="margin:0;">Overnight at hotel <?=$itinary['hotel_name']?></span><br> -->
            <span>
              <?php 
              if($itinary['rating'])
              {
                $base='http://'.$_SERVER['HTTP_HOST'].PROJECT_BASEURI.'/';
                ?>
                <!-- <img src="<?php echo $base;?>extras/custom/keWD7SNXhVwQmNRymfGN/images/star_rating_<?=$itinary['rating']?>.png"> -->
                <?php
              }
              ?>
            </span><br>
            <?php 
            $meals = json_decode($itinary['accomodation'],true);
            $meals = implode(' | ',$meals);
            ?>
            <!-- <strong>Meal Plan: <?=$meals; ?></strong> -->
            </td>
          </tr>
          <?php
        }
        ?>

      </table>
      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; font-size: 9px; color: #374c5d; ">
         <tr>
          <td style="text-align:left; font-size:13px; color:#374c5d;font-weight: bold; padding: 0px 18px;">
            <span>Services Prepaid By Supplier</span><br>
          </td>
        </tr>
      </table>

      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; font-size: 9px; color: #374c5d; ">
<tr>
          <td style="text-align:left; font-size:9px; color:#374c5d;font-weight: bold; padding: 0px 14px;">
            <span><strong>Cancellation
                                    Policies</strong>
                                <br/></span><br>

<ul>
                                    <?php
                                    foreach ($cancel_details as $key => $value) {
                                      // debug($value);
                                      //  die;
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
                            <td style="padding: 8px; text-align: justify; font-size:9px"><strong>Amendment
                                    Policies</strong> - We're here to help! If you need assistance
                                with your reservation, please visit our Help Center. For urgent
                                situations,: such as check-in troubles or arriving to something
                                unexpected</td>
                        </tr> 

                        <tr>
                            <td style="padding: 8px; text-align: justify; font-size:9px"><strong>Terms &amp; Conditions</strong> 
 <tr>
                             <?php if(isset($attributes->terms_n_condition) && !empty($attributes->terms_n_condition)){
                          ?>
                            <td width="100%" style="border: 1px solid #cccccc;"><table width="100%" cellpadding="5" style="padding: 10px 20px;font-size: 9px;"><tbody><tr>                                        <td><?php //echo $attributes->terms_n_condition; ?>
                              <p>Company Terms and Conditions are as follows</p>
                              <br/>
                <p> 1. Booking process (test data) </p>
                <br/>
                <p>2. Payment process (test data)</p>
                            </td></tr></tbody></table></td>
                        <?php }else{ ?>
                          <td width="100%" style="border: 1px solid #cccccc;"><table width="100%" cellpadding="5" style="padding: 10px 20px;font-size: 9px;"><tbody><tr>                                        <td>Not Available</td></tr></tbody></table></td>
                        <?php } ?>
                        </tr>

                            </td>
                        </tr> 
                       

     
      <?php 
      if($tours_details['inclusions']){ ?>
       <!--  <tr>
          <td style="text-align:left; font-size:9px; color:#374c5d;font-weight: bold; padding: 0px 14px;">
            <span>Tour Inclusions</span><br>
            <span><?php echo htmlspecialchars_decode($tours_details['inclusions']);?></span>
          </td>
        </tr> -->
        <?php } ?>
       <!--  <tr>
           <td style="text-align:left; font-size:9px; padding: 0px 14px; color:#374c5d;font-weight: bold;">
            <span>Tour Exclusions</span>
            <br>
             <?php echo htmlspecialchars_decode($tours_details['exclusions']);?>
          </td>
        </tr> -->
        
     
    
       <!--  <tr>
           <td style="text-align:left; font-size:9px; padding: 0px 14px; color:#374c5d;font-weight: bold;">
            <span>Terms &amp; Conditions</span>

             <span style="margin:0;white-space: normal; font-weight: normal">
              <?php echo htmlspecialchars_decode($tours_details['terms']); ?>
              </span>
          </td>
        </tr> -->
     
        <!-- <tr>
          <td style="text-align:left; font-size:9px; color:#374c5d; padding: 0px 14px; font-weight: bold;">
            <span>Cancellation Policy</span>
            <br>
             <span style="margin:0;white-space: normal;">
              <?php echo htmlspecialchars_decode($tours_details['canc_policy']); ?>
             </span>
          </td>
        </tr> -->
        
       

        <tr style="border-bottom:1px solid #fff; color:#374c5d;">
                  <td colspan="7" width="100%" style="line-height: normal;"><span style="margin-top:0; font-weight:bold; color: #374c5d; font-size: 9px;">Contact Information</span>
                     <br>
                     <table cellpadding="0" cellspacing="0">
                     <tbody>
                  <tr>
                     <td style="text-align:left; color: #374c5d; font-size: 9px;border-collapse: collapse;padding:0px;">For any questions or inquiries please email us at support@gmail.com or contact our 24/7 Help Desk at <span style="color:#ff9a03; ">0123456789,</span> our dedicated and well experienced staff will assist you all the way.</td>
                  </tr>

                                    
                  <tr>
                     <td style="text-align:left; color: #374c5d; font-size: 9px;border-collapse: collapse;padding:0px;">We at Vivance are here to make your travel worry free and always enjoy the journey!   </td>
                  </tr>
                </tbody>
                     </table>
                  </td>
               </tr>

      </table>

</td>
</tr>
</tbody>
</table>

</body>
</html>